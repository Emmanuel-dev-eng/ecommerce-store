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
?>