<?php
session_start();
require_once 'db_connectie.php';

$db = maakVerbinding();

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}
$orders_data = [];
$query_orders = "SELECT orderId, statusName 
                 FROM [pizzeria].[dbo].[Pizza_Order] po 
                 INNER JOIN [pizzeria].[dbo].[User] u ON po.clientId = u.userId 
                 INNER JOIN [pizzeria].[dbo].[Status] s ON po.statusId = s.statusId
                 WHERE u.username = :username";
$stmt_orders = $db->prepare($query_orders);
$stmt_orders->execute([':username' => $_SESSION['username']]);

$htmlOutput = '';

while ($order = $stmt_orders->fetch(PDO::FETCH_ASSOC)) {
    $orderId = $order['orderId'];
    
    $query_products = "SELECT productName, quantity, price, (quantity * price) AS totalProductPrice 
                       FROM [pizzeria].[dbo].[Pizza_Order_Product] pop 
                       INNER JOIN [pizzeria].[dbo].[Product] p ON pop.productId = p.productId
                       WHERE orderId = :orderId";
    $stmt_products = $db->prepare($query_products);
    $stmt_products->execute([':orderId' => $orderId]);
    
    $products = $stmt_products->fetchAll(PDO::FETCH_ASSOC);
    
    $orderTotal = 0;
    $tableRows = '';
    foreach ($products as $product) {
        $orderTotal += $product['totalProductPrice'];
        $tableRows .= "
            <tr>
                <td>{$product['productName']}</td>
                <td>{$product['quantity']}</td>
                <td>€" . number_format($product['price'], 2) . "</td>
                <td>€" . number_format($product['totalProductPrice'], 2) . "</td>
            </tr>";
    }
    
    $htmlOutput .= "
    <div class='order'>
        <h2>Order #{$order['orderId']}</h2>
        <p>Status: {$order['statusName']}</p>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                {$tableRows}
            </tbody>
            <tfoot>
                <tr>
                    <td colspan='3'><strong>Order Total</strong></td>
                    <td><strong>€" . number_format($orderTotal, 2) . "</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>";
}

?>

<!DOCTYPE html>
<html>
<body>
    <?= !empty($htmlOutput) ? $htmlOutput : '<p>No orders found.</p>' ?>
</body>
</html>