<?php
require_once './functions/checkUserRole.php';
require_once './db_connectie.php';

session_start();

$db = maakVerbinding();
$isEmployee = checkIsEmployee(); 

$orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
if ($orderId <= 0) {
    die("Ongeldig ordernummer.");
}

if ($isEmployee && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_status'])) {
    $newStatusId = (int)$_POST['new_status'];

    $updateStmt = $db->prepare("UPDATE Pizza_Order SET status_id = :status_id WHERE order_id = :order_id");
    $updateStmt->execute([
        ':status_id' => $newStatusId,
        ':order_id' => $orderId
    ]);
}

$sql = "SELECT po.client_name, po.datetime, po.address, s.status_id, s.status_name
        FROM Pizza_Order po
        LEFT JOIN Status s ON po.status_id = s.status_id
        WHERE po.order_id = :order_id";
$stmt = $db->prepare($sql);
$stmt->execute([':order_id' => $orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$order) {
    die("Bestelling niet gevonden.");
}

$sqlProducts = "SELECT p.name, p.price, pop.quantity
                FROM Pizza_Order_Product pop
                JOIN Product p ON pop.product_id = p.product_id
                WHERE pop.order_id = :order_id";
$stmt = $db->prepare($sqlProducts);
$stmt->execute([':order_id' => $orderId]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$statusStmt = $db->query("SELECT status_id, status_name FROM Status");
$allStatuses = $statusStmt->fetchAll(PDO::FETCH_ASSOC);

$totaal = 0;
$productRowsHtml = '';
foreach ($products as $product) {
    $subtotaal = $product['price'] * $product['quantity'];
    $totaal += $subtotaal;

    $productRowsHtml .= '<tr>';
    $productRowsHtml .= '<td>' . htmlspecialchars($product['name']) . '</td>';
    $productRowsHtml .= '<td>€ ' . number_format($product['price'], 2, ',', '.') . '</td>';
    $productRowsHtml .= '<td>' . $product['quantity'] . '</td>';
    $productRowsHtml .= '<td>€ ' . number_format($subtotaal, 2, ',', '.') . '</td>';
    $productRowsHtml .= '</tr>';
}
$productRowsHtml .= '<tr>';
$productRowsHtml .= '<td colspan="3"><strong>Totaal</strong></td>';
$productRowsHtml .= '<td><strong>€ ' . number_format($totaal, 2, ',', '.') . '</strong></td>';
$productRowsHtml .= '</tr>';

$statusOptionsHtml = '';
if ($isEmployee) {
    foreach ($allStatuses as $status) {
        $selected = ($status['status_id'] == $order['status_id']) ? 'selected' : '';
        $statusOptionsHtml .= '<option value="' . $status['status_id'] . '" ' . $selected . '>' 
            . htmlspecialchars($status['status_name']) . '</option>';
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bestelling #<?= $orderId ?></title>
</head>
<body>
    <h1>Bestelling #<?= $orderId ?></h1>

    <p><strong>Klant:</strong> <?= htmlspecialchars($order['client_name']) ?></p>
    <p><strong>Datum/Tijd:</strong> <?= $order['datetime'] ?></p>
    <p><strong>Adres:</strong> <?= htmlspecialchars($order['address']) ?></p>

    <p><strong>Status:</strong></p>
    <?php if ($isEmployee): ?>
        <form method="POST">
            <select name="new_status" id="new_status" onchange="this.form.submit()">
                <?= $statusOptionsHtml ?>
            </select>
        </form>
    <?php else: ?>
        <p><?= htmlspecialchars($order['status_name']) ?></p>
    <?php endif; ?>

    <h2>Bestelde producten</h2>
    <?php if (empty($products)): ?>
        <p>Geen producten gevonden.</p>
    <?php else: ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr>
                <th>Product</th>
                <th>Prijs (per stuk)</th>
                <th>Aantal</th>
                <th>Subtotaal</th>
            </tr>
            <?= $productRowsHtml ?>
        </table>
    <?php endif; ?>

    <p><a href="<?= $isEmployee ? 'employeeOverview.php' : 'index.php' ?>">← Terug naar overzicht</a></p>
</body>
</html>
