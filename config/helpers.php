<?php
// ============================================
// config/helpers.php
// Shared toolbox: small functions used by several pages.
// ============================================

// Tax rate used everywhere (7.5%). Change it here, and it changes everywhere.
const TAX_RATE = 0.075;

// Shipping price. Standard is free over $75 (otherwise $6.99); express is a flat $14.99.
// ": float" means this function promises to return a decimal number.
function shippingCost(string $method, float $subtotal): float
{
    if ($method === 'express') {
        return 14.99;
    }
    return $subtotal >= 75 ? 0.00 : 6.99;
}

// Looks up the size/color labels for a list of variant IDs.
// Returns something like: [1 => ['M', 'White'], 2 => ['L', 'Blue']]
function variantLabels(PDO $pdo, array $variantIds): array
{
    if (empty($variantIds)) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($variantIds), '?'));

    $stmt = $pdo->prepare("
        SELECT vav.variant_id, av.value
        FROM variant_attribute_values vav
        JOIN attribute_values av ON vav.attribute_value_id = av.id
        WHERE vav.variant_id IN ($placeholders)
    ");
    $stmt->execute(array_values($variantIds));

    $labels = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $labels[$row['variant_id']][] = $row['value'];
    }
    return $labels;
}


// What can an order's status change to? Forward only, plus cancel before delivery.
function allowedNextStatuses(string $current): array
{
    $map = [
        'pending'    => ['processing', 'cancelled'],
        'processing' => ['shipped', 'cancelled'],
        'shipped'    => ['delivered'],
        'delivered'  => [],
        'cancelled'  => [],
    ];
    return $map[$current] ?? [];
}

// Changes an order's status safely, inside a transaction.
// Returns '' on success, or an error message to show the admin.
function updateOrderStatus(PDO $pdo, int $orderId, string $newStatus): string
{
    try {
        $pdo->beginTransaction();

        // Lock the order row while we work on it
        $stmt = $pdo->prepare("SELECT status, payment_method FROM orders WHERE id = ? FOR UPDATE");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            $pdo->rollBack();
            return "Order not found.";
        }

        // Enforce the rules ON THE SERVER, whatever the browser sent
        if (!in_array($newStatus, allowedNextStatuses($order['status']), true)) {
            $pdo->rollBack();
            return "That status change isn't allowed.";
        }

        // Cancelling puts the stock back on the shelf
        if ($newStatus === 'cancelled') {
            $restore = $pdo->prepare("
                UPDATE product_variants pv
                JOIN order_items oi ON oi.variant_id = pv.id
                SET pv.stock_quantity = pv.stock_quantity + oi.quantity
                WHERE oi.order_id = ?
            ");
            $restore->execute([$orderId]);
        }

        // Cash on Delivery: delivered means the cash has been collected
        if ($newStatus === 'delivered' && $order['payment_method'] === 'cod') {
            $update = $pdo->prepare("UPDATE orders SET status = ?, payment_status = 'paid' WHERE id = ?");
        } else {
            $update = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        }
        $update->execute([$newStatus, $orderId]);

        $pdo->commit();
        return '';

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log($e->getMessage());
        return "Something went wrong. Please try again.";
    }
}

?>