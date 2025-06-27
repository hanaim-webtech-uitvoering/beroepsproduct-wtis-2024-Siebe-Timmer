<?php
session_start();
require_once 'db_connectie.php';

$db = maakVerbinding();

$cart = $_SESSION['cart'] ?? [];
$totalPrice = 0;
$productsById = [];
$cartRowsHtml = '';

if (!empty($cart)) {
    $productIds = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));
    $sql = "SELECT product_id, name, price FROM [pizzeria].[dbo].[Product] WHERE product_id IN ($placeholders)";
    $stmt = $db->prepare($sql);
    $stmt->execute($productIds);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as $product) {
        $productsById[$product['product_id']] = $product;
    }

    foreach ($cart as $productId => $quantity) {
        if (!isset($productsById[$productId])) continue;

        $product = $productsById[$productId];
        $name = htmlspecialchars($product['name']);
        $price = number_format($product['price'], 2, ',', '.');
        $subtotal = $product['price'] * $quantity;
        $subtotalFormatted = number_format($subtotal, 2, ',', '.');
        $totalPrice += $subtotal;

        $cartRowsHtml .= "
        <tr>
            <td>{$name}</td>
            <td>€ {$price}</td>
            <td>
                <form action=\"functions/updateCart.php\" method=\"POST\" style=\"margin:0;\">
                    <input type=\"number\" name=\"quantity\" value=\"{$quantity}\" min=\"1\" style=\"width:50px;\">
            </td>
            <td>€ {$subtotalFormatted}</td>
            <td>
                    <input type=\"hidden\" name=\"productId\" value=\"{$productId}\">

                    <button type=\"submit\" name=\"action\" value=\"set\" title=\"Aantal bijwerken\">Bijwerken</button>
                    <button type=\"submit\" name=\"action\" value=\"removeOne\" title=\"Verwijder 1\">-1</button>
                    <button type=\"submit\" name=\"action\" value=\"remove\" title=\"Verwijder alles\">Verwijder alles</button>
                </form>
            </td>
        </tr>";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Winkelwagen</title>
</head>
<body>
    <h1>Winkelwagen</h1>

    <?php if (empty($cartRowsHtml)): ?>
        <p>Je winkelwagen is nog leeg.</p>
    <?php else: ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Product</th>
                <th>Prijs per stuk</th>
                <th>Aantal</th>
                <th>Subtotaal</th>
                <th>Acties</th>
            </tr>

            <?= $cartRowsHtml ?>

            <tr>
                <td colspan="3"><strong>Totaal</strong></td>
                <td colspan="2"><strong>€ <?= number_format($totalPrice, 2, ',', '.') ?></strong></td>
            </tr>
        </table>

        <p><a href="index.php">Verder winkelen</a> | <a href="checkout.php">Afrekenen</a></p>
    <?php endif; ?>
</body>
</html>
