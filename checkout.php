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

    <form id="checkoutForm" class="grid lg:grid-cols-3 gap-8">

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
                        <input type="text" name="fullName" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        <p class="error-msg hidden text-xs text-red-500 mt-1">Full name is required.</p>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Email Address</label>
                        <input type="email" name="email" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        <p class="error-msg hidden text-xs text-red-500 mt-1">Enter a valid email address.</p>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Street Address</label>
                        <input type="text" name="address" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        <p class="error-msg hidden text-xs text-red-500 mt-1">Street address is required.</p>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">City</label>
                        <input type="text" name="city" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        <p class="error-msg hidden text-xs text-red-500 mt-1">City is required.</p>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">State / Province</label>
                        <input type="text" name="state" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        <p class="error-msg hidden text-xs text-red-500 mt-1">State is required.</p>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Postal Code</label>
                        <input type="text" name="postal" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        <p class="error-msg hidden text-xs text-red-500 mt-1">Postal code is required.</p>
                    </div>

                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" required placeholder="e.g. 0800 000 0000"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                        <p class="error-msg hidden text-xs text-red-500 mt-1">Phone number is required — we may call to confirm your order.</p>
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
                            <input type="radio" name="shippingMethod" value="0" class="ship-option" checked>
                            <span class="text-sm">
                                <span class="font-medium block">Standard Shipping</span>
                                <span class="text-xs text-gray-500">5-7 business days</span>
                            </span>
                        </span>
                        <span class="text-sm font-semibold">Free</span>
                    </label>
                    <label class="flex items-center justify-between border border-gray-300 rounded-lg px-4 py-3 cursor-pointer has-[:checked]:border-black has-[:checked]:bg-gray-50">
                        <span class="flex items-center gap-3">
                            <input type="radio" name="shippingMethod" value="14.99" class="ship-option">
                            <span class="text-sm">
                                <span class="font-medium block">Express Shipping</span>
                                <span class="text-xs text-gray-500">2-3 business days</span>
                            </span>
                        </span>
                        <span class="text-sm font-semibold">$14.99</span>
                    </label>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6">
                <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i data-lucide="wallet" class="w-4 h-4"></i> Payment Method
                </h2>

                <div class="space-y-3">
                    <label class="payment-method-option flex items-start gap-3 border-2 rounded-xl p-4 cursor-pointer border-black bg-gray-50">
                        <input type="radio" name="paymentMethod" value="paystack" class="payment-radio mt-1" checked>
                        <div class="w-10 h-10 bg-black rounded-full flex items-center justify-center flex-shrink-0">
                            <i data-lucide="credit-card" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">Pay Now with Paystack</p>
                            <p class="text-xs text-gray-500 mt-1">
                                Pay securely by card, bank transfer, or USSD. You'll be redirected to
                                Paystack's secure payment page to complete payment.
                            </p>
                        </div>
                    </label>

                    <label class="payment-method-option flex items-start gap-3 border-2 rounded-xl p-4 cursor-pointer border-gray-200">
                        <input type="radio" name="paymentMethod" value="cod" class="payment-radio mt-1">
                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center flex-shrink-0">
                            <i data-lucide="banknote" class="w-5 h-5 text-gray-600"></i>
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

                <!-- NOTE: real Paystack integration happens in the PHP phase —
                     initializing a transaction via their API and redirecting
                     here. COD skips payment entirely and creates the order
                     directly with status 'pending'. -->
            </div>
        </div>

        <!-- ===================== ORDER SUMMARY ===================== -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-200 p-6 sticky top-24">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Order Summary</h2>

                <div id="summaryItems" class="space-y-3 mb-4 max-h-48 overflow-y-auto">
                    <!-- Items injected by JS -->
                </div>

                <div class="space-y-2 text-sm border-t border-gray-100 pt-4">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span id="sumSubtotal">$0.00</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Shipping</span>
                        <span id="sumShipping">$0.00</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Tax (est.)</span>
                        <span id="sumTax">$0.00</span>
                    </div>
                </div>

                <div class="border-t border-gray-100 mt-3 pt-3 flex justify-between font-bold text-gray-900">
                    <span>Total</span>
                    <span id="sumTotal">$0.00</span>
                </div>

                <button type="submit" id="placeOrderBtn" class="mt-6 w-full bg-black text-white font-semibold py-3.5 rounded-lg hover:bg-gray-800 transition flex items-center justify-center gap-2">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                    <span id="placeOrderBtnText">Pay with Paystack</span>
                </button>

                <p id="paymentNote" class="text-[11px] text-gray-400 text-center mt-3">
                    You'll be redirected to Paystack to complete payment securely.
                </p>
            </div>
        </div>
    </form>
</main>

<script>
    lucide.createIcons();

    // ---- Sample cart summary (mirrors cart.php cart array) ----
    const cart = [
        { name: 'Classic White Sneakers', variant: 'White, Size M', price: 59.99, qty: 1 },
        { name: 'Oversized Denim Jacket', variant: 'Blue, Size L', price: 89.99, qty: 1 }
    ];

    const summaryItems = document.getElementById('summaryItems');
    cart.forEach(item => {
        const row = document.createElement('div');
        row.className = 'flex justify-between text-sm';
        row.innerHTML = `
            <div class="min-w-0">
                <p class="font-medium text-gray-900 truncate">${item.name} <span class="text-gray-400">×${item.qty}</span></p>
                <p class="text-xs text-gray-500">${item.variant}</p>
            </div>
            <span class="font-semibold text-gray-900 flex-shrink-0">$${(item.price * item.qty).toFixed(2)}</span>
        `;
        summaryItems.appendChild(row);
    });

    function calcTotals() {
        const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
        const shippingCost = parseFloat(document.querySelector('.ship-option:checked').value);
        const tax = subtotal * 0.075;
        const total = subtotal + shippingCost + tax;

        document.getElementById('sumSubtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('sumShipping').textContent = shippingCost === 0 ? 'Free' : `$${shippingCost.toFixed(2)}`;
        document.getElementById('sumTax').textContent = `$${tax.toFixed(2)}`;
        document.getElementById('sumTotal').textContent = `$${total.toFixed(2)}`;
    }

    document.querySelectorAll('.ship-option').forEach(radio => {
        radio.addEventListener('change', calcTotals);
    });
    calcTotals();

    // ---- Payment method selection (visual + button text swap) ----
    const paymentOptions = document.querySelectorAll('.payment-method-option');
    const placeOrderBtnText = document.getElementById('placeOrderBtnText');
    const paymentNote = document.getElementById('paymentNote');

    document.querySelectorAll('.payment-radio').forEach(radio => {
        radio.addEventListener('change', () => {
            paymentOptions.forEach(opt => {
                opt.classList.remove('border-black', 'bg-gray-50');
                opt.classList.add('border-gray-200');
                opt.querySelector('.w-10').classList.remove('bg-black');
                opt.querySelector('.w-10').classList.add('bg-gray-200');
                opt.querySelector('.w-10 i').setAttribute('class', 'w-5 h-5 text-gray-600');
            });

            const selectedOption = radio.closest('.payment-method-option');
            selectedOption.classList.remove('border-gray-200');
            selectedOption.classList.add('border-black', 'bg-gray-50');
            selectedOption.querySelector('.w-10').classList.remove('bg-gray-200');
            selectedOption.querySelector('.w-10').classList.add('bg-black');
            selectedOption.querySelector('.w-10 i').setAttribute('class', 'w-5 h-5 text-white');
            lucide.createIcons();

            if (radio.value === 'paystack') {
                placeOrderBtnText.textContent = 'Pay with Paystack';
                paymentNote.textContent = "You'll be redirected to Paystack to complete payment securely.";
            } else {
                placeOrderBtnText.textContent = 'Confirm Order';
                paymentNote.textContent = 'No payment is taken now. You\'ll pay cash when your order is delivered.';
            }
        });
    });

    // ---- Form validation + submit ----
    const form = document.getElementById('checkoutForm');
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        let valid = true;

        form.querySelectorAll('input[required]').forEach(input => {
            const errorMsg = input.parentElement.querySelector('.error-msg');
            let fieldValid = input.value.trim() !== '';

            if (input.type === 'email' && fieldValid) {
                fieldValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value);
            }
            if (input.name === 'phone' && fieldValid) {
                fieldValid = input.value.trim().length >= 7;
            }

            if (!fieldValid) {
                input.classList.add('border-red-500');
                if (errorMsg) errorMsg.classList.remove('hidden');
                valid = false;
            } else {
                input.classList.remove('border-red-500');
                if (errorMsg) errorMsg.classList.add('hidden');
            }
        });

        if (!valid) {
            const firstError = form.querySelector('.border-red-500');
            if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        const selectedMethod = document.querySelector('.payment-radio:checked').value;

        // NOTE: this is where the two paths genuinely diverge in the PHP
        // phase:
        // - Paystack: PHP calls Paystack's "initialize transaction" API,
        //   then redirects the browser to the authorization_url Paystack
        //   returns. Order is only created as "paid" after verification.
        // - COD: PHP creates the order directly with status "pending" —
        //   no external API call needed.
        // For now, both paths just confirm the form is valid and move
        // forward, proving the branching logic works.
        if (selectedMethod === 'paystack') {
            console.log('Would redirect to Paystack checkout here.');
        } else {
            console.log('Would create COD order directly here.');
        }

        window.location.href = 'order-confirmation.php';
    });
</script>

</body>
</html>