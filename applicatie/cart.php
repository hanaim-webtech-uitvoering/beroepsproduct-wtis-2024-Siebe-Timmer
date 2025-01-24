<?php
session_start();

if(!$_SESSION['username']){
    header('Location: login.php');
}

require_once 'db_connectie.php';

$db = maakVerbinding();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$winkelwagen_items = [];
if (!empty($_SESSION['cart'])) {
    $product_ids = array_keys($_SESSION['cart']);
    $placeholders = str_repeat('?,', count($product_ids) - 1) . '?';
    
    $query = "SELECT productId, productName, price 
              FROM [pizzeria].[dbo].[Product] 
              WHERE productId IN ($placeholders)";
              
    $stmt = $db->prepare($query);
    $stmt->execute($product_ids);
    
    while ($row = $stmt->fetch()) {
        $quantity = $_SESSION['cart'][$row['productId']];
        $winkelwagen_items[] = [
            'id' => $row['productId'],
            'name' => $row['productName'],
            'price' => $row['price'],
            'quantity' => $quantity,
            'subtotal' => $row['price'] * $quantity
        ];
    }
}

$total = 0;
foreach ($winkelwagen_items as $item) {
    $total += $item['subtotal'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Winkelwagen</title>
</head>
<body>
    <h1>Winkelwagen</h1>
    
    <?php if (empty($winkelwagen_items)): ?>
        <p>Je winkelwagen is leeg</p>
    <?php else: ?>
        <form method="POST" action="functions/update_cart.php">
            <table>
                <tr>
                    <th>Product</th>
                    <th>Prijs per stuk</th>
                    <th>Aantal</th>
                    <th>Subtotaal</th>
                    <th>Acties</th>
                </tr>
                <?php foreach ($winkelwagen_items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td>€<?= number_format($item['price'], 2) ?></td>
                        <td>
                            <input type="number" 
                                   name="quantity[<?= $item['id'] ?>]" 
                                   value="<?= $item['quantity'] ?>" 
                                   min="1">
                        </td>
                        <td>€<?= number_format($item['subtotal'], 2) ?></td>
                        <td>
                            <a href="functions/update_cart.php?action=remove&productId=<?= $item['id'] ?>">Verwijderen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3"><strong>Totaal</strong></td>
                    <td colspan="2"><strong>€<?= number_format($total, 2) ?></strong></td>
                </tr>
            </table>
            
            <input type="hidden" name="action" value="update_all">
            <input type="submit" value="Winkelwagen bijwerken">
            <a href="checkout.php"><button type="button">Bestelling afronden</button></a>
        </form>
    <?php endif; ?>
    
    <p><a href="menu.php">Terug naar menu</a></p>
</body>
</html> 