<?php
// ============================================
// checkout.php
// Shows the checkout form and, on submit, creates a REAL order.
// ============================================

require 'config/auth_check.php'; // starts the session + blocks logged-out visitors
require 'config/db.php';
require 'config/helpers.php';

$userId = (int)$_SESSION['user_id'];

// ---- Load this user's cart ----
$cartStmt = $pdo->prepare("
    SELECT ci.variant_id, ci.quantity, pv.sku, pv.price, pv.stock_quantity, p.name
    FROM cart_items ci
    JOIN product_variants pv ON ci.variant_id = pv.id
    JOIN products p ON pv.product_id = p.id
    WHERE ci.user_id = ? AND p.is_active = 1
");
$cartStmt->execute([$userId]);
$cartRows = $cartStmt->fetchAll(PDO::FETCH_ASSOC);

// Nothing to check out? Go back to the cart.
if (empty($cartRows)) {
    header("Location: cart.php");
    exit;
}

$labels = variantLabels($pdo, array_column($cartRows, 'variant_id'));

$subtotal = 0;
foreach ($cartRows as $row) {
    $subtotal += $row['price'] * $row['quantity'];
}
$subtotal = round($subtotal, 2);

// ---- Pre-fill the form with the customer's account details ----
$userStmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
$userStmt->execute([$userId]);
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

$errors = [];

// These are the form fields and their starting values.
$form = [
    'fullName'       => $user['name'] ?? '',
    'email'          => $user['email'] ?? '',
    'address'        => '',
    'city'           => '',
    'state'          => '',
    'postal'         => '',
    'phone'          => '',
    'shippingMethod' => 'standard',
];

// ---- Handle the submitted form ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Replace each default with what the user typed. If validation fails,
    // the form refills itself, so nobody has to type everything again.
    foreach ($form as $key => $default) {
        $form[$key] = trim($_POST[$key] ?? $default);
    }

    // ---- Server-side validation (the real check) ----
    if ($form['fullName'] === '') {
        $errors[] = "Full name is required.";
    }
    if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email address is required.";
    }
    if ($form['address'] === '') {
        $errors[] = "Street address is required.";
    }
    if ($form['city'] === '') {
        $errors[] = "City is required.";
    }
    if ($form['state'] === '') {
        $errors[] = "State is required.";
    }
    if ($form['postal'] === '') {
        $errors[] = "Postal code is required.";
    }
    if (strlen($form['phone']) < 7) {
        $errors[] = "A phone number is required, because we call to confirm your order.";
    }
    if (!in_array($form['shippingMethod'], ['standard', 'express'], true)) {
        $form['shippingMethod'] = 'standard';
    }

    // ---- Everything valid? Create the order ----
    if (empty($errors)) {

        // Paystack isn't connected yet, so every order is Cash on Delivery,
        // whatever the browser sent.
        $paymentMethod = 'cod';

        try {
            // From here on, nothing is permanent until commit().
            $pdo->beginTransaction();

            // Read the cart again INSIDE the transaction, and lock those rows
            // (FOR UPDATE) so nobody else can change this stock meanwhile.
            $lockStmt = $pdo->prepare("
                SELECT ci.variant_id, ci.quantity, pv.price, pv.stock_quantity, p.name
                FROM cart_items ci
                JOIN product_variants pv ON ci.variant_id = pv.id
                JOIN products p ON pv.product_id = p.id
                WHERE ci.user_id = ? AND p.is_active = 1
                FOR UPDATE
            ");
            $lockStmt->execute([$userId]);
            $items = $lockStmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($items)) {
                throw new Exception("Your cart is empty.");
            }

            // Check stock and work out the totals from DATABASE prices.
            $orderSubtotal = 0;
            foreach ($items as $item) {
                if ($item['quantity'] > $item['stock_quantity']) {
                    throw new Exception(
                        "Sorry, only {$item['stock_quantity']} of \"{$item['name']}\" left in stock. Please update your cart."
                    );
                }
                $orderSubtotal += $item['price'] * $item['quantity'];
            }
            $orderSubtotal = round($orderSubtotal, 2);
            $orderShipping = shippingCost($form['shippingMethod'], $orderSubtotal);
            $orderTax      = round($orderSubtotal * TAX_RATE, 2);
            $orderTotal    = round($orderSubtotal + $orderShipping + $orderTax, 2);

            // The orders table has one address column, so join the parts together.
            $fullAddress = $form['address'] . ', ' . $form['city'] . ', ' . $form['state'] . ' ' . $form['postal'];

            // 1) Create the order row
            $orderStmt = $pdo->prepare("
                INSERT INTO orders
                    (user_id, customer_name, customer_email, phone, subtotal, shipping_cost, tax,
                     total_amount, payment_method, payment_status, shipping_address)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'unpaid', ?)
            ");
            $orderStmt->execute([
                $userId, $form['fullName'], $form['email'], $form['phone'],
                $orderSubtotal, $orderShipping, $orderTax, $orderTotal,
                $paymentMethod, $fullAddress
            ]);

            // The ID MySQL just gave this order
            $orderId = (int)$pdo->lastInsertId();

            // 2) Build a readable order number from that ID (ID 1 becomes ORD-100001)
            $orderNumber = 'ORD-' . (100000 + $orderId);
            $numStmt = $pdo->prepare("UPDATE orders SET order_number = ? WHERE id = ?");
            $numStmt->execute([$orderNumber, $orderId]);

            // 3) One order_items row per product, and reduce its stock
            $itemStmt = $pdo->prepare("
                INSERT INTO order_items (order_id, variant_id, quantity, price_at_purchase)
                VALUES (?, ?, ?, ?)
            ");
            // "AND stock_quantity >= ?" is a second safety net: it only
            // reduces stock if there is enough of it.
            $stockStmt = $pdo->prepare("
                UPDATE product_variants
                SET stock_quantity = stock_quantity - ?
                WHERE id = ? AND stock_quantity >= ?
            ");

            foreach ($items as $item) {
                $itemStmt->execute([$orderId, $item['variant_id'], $item['quantity'], $item['price']]);
                $stockStmt->execute([$item['quantity'], $item['variant_id'], $item['quantity']]);

                if ($stockStmt->rowCount() !== 1) {
                    throw new Exception("\"{$item['name']}\" just sold out. Please update your cart.");
                }
            }

            // 4) Empty this user's cart
            $clearStmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
            $clearStmt->execute([$userId]);

            // 5) Save everything at once
            $pdo->commit();

            header("Location: order-confirmation.php?order=" . urlencode($orderNumber));
            exit;

        } catch (Exception $e) {
            // Something failed, so undo EVERYTHING done since beginTransaction()
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            if ($e instanceof PDOException) {
                // A database problem: log the details, but don't show them to customers.
                // While building, you can temporarily use $e->getMessage() below instead.
                error_log($e->getMessage());
                $errors[] = "Something went wrong while placing your order. Please try again.";
            } else {
                // One of our own friendly messages (stock, empty cart, etc.)
                $errors[] = $e->getMessage();
            }
        }
    }
}

// ---- Numbers for the order summary (display only; the server recalculates on submit) ----
$standardCost = shippingCost('standard', $subtotal);
$expressCost  = shippingCost('express', $subtotal);
$shipping = ($form['shippingMethod'] === 'express') ? $expressCost : $standardCost;
$tax = round($subtotal * TAX_RATE, 2);
$total = round($subtotal + $shipping + $tax, 2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | ShopName</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

<!-- ===================== HEADER (simplified for checkout) ===================== -->
<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="index.php" class="text-xl font-bold text-gray-900 tracking-tight">ShopName</a>
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                Secure Checkout
            </div>
        </div>
    </div>
</header>

<main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- ===================== STEP PROGRESS ===================== -->
    <div class="flex items-center justify-center gap-2 mb-8 text-xs font-medium">
        <div class="flex items-center gap-2 text-gray-900">
            <span class="w-6 h-6 rounded-full bg-black text-white flex items-center justify-center text-[11px]">1</span>
            Cart
        </div>
        <div class="w-8 h-px bg-gray-300"></div>
        <div class="flex items-center gap-2 text-gray-900">
            <span class="w-6 h-6 rounded-full bg-black text-white flex items-center justify-center text-[11px]">2</span>
            Checkout
        </div>
        <div class="w-8 h-px bg-gray-300"></div>
        <div class="flex items-center gap-2 text-gray-400">
            <span class="w-6 h-6 rounded-full border border-gray-300 flex items-center justify-center text-[11px]">3</span>
            Confirmation
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg px-4 py-3 mb-6">
            <ul class="list-disc list-inside space-y-1">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form id="checkoutForm" method="POST" class="grid lg:grid-cols-3 gap-8">

        <!-- ===================== SHIPPING FORM ===================== -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Shipping Info -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i data-lucide="truck" class="w-4 h-4"></i> Shipping Information
                </h2>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Full Name</label>
                        <input type="text" name="fullName" required value="<?= htmlspecialchars($form['fullName']) ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Email Address</label>
                        <input type="email" name="email" required value="<?= htmlspecialchars($form['email']) ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Street Address</label>
                        <input type="text" name="address" required value="<?= htmlspecialchars($form['address']) ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">City</label>
                        <input type="text" name="city" required value="<?= htmlspecialchars($form['city']) ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">State / Province</label>
                        <input type="text" name="state" required value="<?= htmlspecialchars($form['state']) ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Postal Code</label>
                        <input type="text" name="postal" required value="<?= htmlspecialchars($form['postal']) ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" required placeholder="e.g. 0800 000 0000" value="<?= htmlspecialchars($form['phone']) ?>"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        <p class="text-[11px] text-gray-400 mt-1">We call this number to confirm your order.</p>
                    </div>
                </div>
            </div>

            <!-- Shipping Method -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i data-lucide="package" class="w-4 h-4"></i> Shipping Method
                </h2>
                <div class="space-y-3">
                    <label class="flex items-center justify-between border border-gray-300 rounded-lg px-4 py-3 cursor-pointer has-[:checked]:border-black has-[:checked]:bg-gray-50">
                        <span class="flex items-center gap-3">
                            <input type="radio" name="shippingMethod" value="standard" class="ship-option"
                                data-cost="<?= $standardCost ?>" <?= $form['shippingMethod'] === 'standard' ? 'checked' : '' ?>>
                            <span class="text-sm">
                                <span class="font-medium block">Standard Shipping</span>
                                <span class="text-xs text-gray-500">5-7 business days</span>
                            </span>
                        </span>
                        <span class="text-sm font-semibold"><?= $standardCost == 0 ? 'Free' : '$' . number_format($standardCost, 2) ?></span>
                    </label>
                    <label class="flex items-center justify-between border border-gray-300 rounded-lg px-4 py-3 cursor-pointer has-[:checked]:border-black has-[:checked]:bg-gray-50">
                        <span class="flex items-center gap-3">
                            <input type="radio" name="shippingMethod" value="express" class="ship-option"
                                data-cost="<?= $expressCost ?>" <?= $form['shippingMethod'] === 'express' ? 'checked' : '' ?>>
                            <span class="text-sm">
                                <span class="font-medium block">Express Shipping</span>
                                <span class="text-xs text-gray-500">2-3 business days</span>
                            </span>
                        </span>
                        <span class="text-sm font-semibold">$<?= number_format($expressCost, 2) ?></span>
                    </label>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i data-lucide="wallet" class="w-4 h-4"></i> Payment Method
                </h2>

                <div class="space-y-3">
                    <!-- Paystack: shown, but switched off until we connect it -->
                    <label class="flex items-start gap-3 border-2 border-gray-200 rounded-xl p-4 opacity-60 cursor-not-allowed">
                        <input type="radio" name="paymentMethod" value="paystack" disabled class="mt-1">
                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center flex-shrink-0">
                            <i data-lucide="credit-card" class="w-5 h-5 text-gray-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                Pay Now with Paystack
                                <span class="ml-2 text-[10px] font-semibold bg-gray-200 text-gray-600 px-2 py-0.5 rounded-full">Coming soon</span>
                            </p>
                            <p class="text-xs text-gray-500 mt-1">Pay by card, bank transfer, or USSD.</p>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 border-2 border-black bg-gray-50 rounded-xl p-4 cursor-pointer">
                        <input type="radio" name="paymentMethod" value="cod" checked class="mt-1">
                        <div class="w-10 h-10 bg-black rounded-full flex items-center justify-center flex-shrink-0">
                            <i data-lucide="banknote" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Cash on Delivery</p>
                            <p class="text-xs text-gray-500 mt-1">
                                Pay in cash when your order arrives. We'll call the phone number
                                above to confirm your order before it ships.
                            </p>
                        </div>
                    </label>
                </div>
            </div>
        </div>

        <!-- ===================== ORDER SUMMARY ===================== -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-24">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Order Summary</h2>

                <div class="space-y-3 mb-4 max-h-48 overflow-y-auto">
                    <?php foreach ($cartRows as $row): ?>
                        <?php
                        $label = isset($labels[$row['variant_id']])
                            ? implode(', ', $labels[$row['variant_id']])
                            : $row['sku'];
                        ?>
                        <div class="flex justify-between text-sm">
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 truncate">
                                    <?= htmlspecialchars($row['name']) ?>
                                    <span class="text-gray-400">×<?= (int)$row['quantity'] ?></span>
                                </p>
                                <p class="text-xs text-gray-500"><?= htmlspecialchars($label) ?></p>
                            </div>
                            <span class="font-semibold text-gray-900 flex-shrink-0">$<?= number_format($row['price'] * $row['quantity'], 2) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="space-y-2 text-sm border-t border-gray-100 pt-4">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span id="sumSubtotal">$<?= number_format($subtotal, 2) ?></span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Shipping</span>
                        <span id="sumShipping"><?= $shipping == 0 ? 'Free' : '$' . number_format($shipping, 2) ?></span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Tax (est.)</span>
                        <span id="sumTax">$<?= number_format($tax, 2) ?></span>
                    </div>
                </div>

                <div class="border-t border-gray-100 mt-3 pt-3 flex justify-between font-bold text-gray-900">
                    <span>Total (Pay on Delivery)</span>
                    <span id="sumTotal">$<?= number_format($total, 2) ?></span>
                </div>

                <button type="submit" id="placeOrderBtn" class="mt-6 w-full bg-black text-white font-semibold py-3.5 rounded-lg hover:bg-gray-800 transition flex items-center justify-center gap-2 disabled:opacity-60">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    Confirm Order
                </button>

                <p class="text-[11px] text-gray-400 text-center mt-3">
                    No payment is taken now. You'll pay cash when your order is delivered.
                </p>
            </div>
        </div>
    </form>
</main>

<script>
    lucide.createIcons();

    // ---- Update the summary when the shipping method changes ----
    // This is DISPLAY ONLY. When the form is submitted, PHP recalculates
    // everything from the database and ignores these numbers.
    const subtotal = <?= json_encode((float)$subtotal) ?>;
    const taxRate = <?= TAX_RATE ?>;

    function money(n) { return '$' + n.toFixed(2); }

    function recalc() {
        const cost = parseFloat(document.querySelector('.ship-option:checked').dataset.cost);
        const tax = Math.round(subtotal * taxRate * 100) / 100;
        document.getElementById('sumShipping').textContent = cost === 0 ? 'Free' : money(cost);
        document.getElementById('sumTax').textContent = money(tax);
        document.getElementById('sumTotal').textContent = money(subtotal + cost + tax);
    }

    document.querySelectorAll('.ship-option').forEach(radio => radio.addEventListener('change', recalc));

    // ---- Stop double-clicks from placing the order twice ----
    // Note: no preventDefault(). The form really submits to PHP.
    document.getElementById('checkoutForm').addEventListener('submit', () => {
        const btn = document.getElementById('placeOrderBtn');
        btn.disabled = true;
        btn.textContent = 'Placing your order...';
    });
</script>

</body>
</html>