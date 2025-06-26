<?php
session_start();
require_once './db_connectie.php';
require_once './functions/checkUserRole.php';

if (!checkIsEmployee()) {
    header('Location: index.php');
    exit;
}

$db = maakVerbinding();
$personnelUsername = $_SESSION['username'];
$errors = [];

// Verwerk 'accepteren'-actie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accepteer_order_id'])) {
    $orderId = $_POST['accepteer_order_id'];

    $updateSql = "UPDATE Pizza_Order SET personnel_username = :username WHERE order_id = :order_id";
    $stmt = $db->prepare($updateSql);
    $stmt->execute([
        ':username' => $personnelUsername,
        ':order_id' => $orderId
    ]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status_id'], $_POST['order_id'])) {
    $statusId = $_POST['update_status_id'];
    $orderId = $_POST['order_id'];

    $stmt = $db->prepare("UPDATE Pizza_Order SET status_id = :status_id WHERE order_id = :order_id");
    $stmt->execute([
        ':status_id' => $statusId,
        ':order_id' => $orderId
    ]);
}

$statusStmt = $db->query("SELECT status_id, status_name FROM Status");
$statuses = $statusStmt->fetchAll(PDO::FETCH_KEY_PAIR);

$stmtOpen = $db->prepare("SELECT po.*, s.status_name FROM Pizza_Order po
    JOIN Status s ON po.status_id = s.status_id
    WHERE po.personnel_username IS NULL
    ORDER BY datetime DESC");
$stmtOpen->execute();
$openOrders = $stmtOpen->fetchAll(PDO::FETCH_ASSOC);

$stmtAll = $db->prepare("SELECT po.*, s.status_name, po.personnel_username
    FROM Pizza_Order po
    JOIN Status s ON po.status_id = s.status_id
    ORDER BY datetime DESC");
$stmtAll->execute();
$orders = $stmtAll->fetchAll(PDO::FETCH_ASSOC);

$openOrdersRowsHtml = '';
foreach ($openOrders as $order) {
    $openOrdersRowsHtml .= '<tr>';
    $openOrdersRowsHtml .= '<td>' . $order['order_id'] . '</td>';
    $openOrdersRowsHtml .= '<td>' . htmlspecialchars($order['client_name']) . '</td>';
    $openOrdersRowsHtml .= '<td>' . htmlspecialchars($order['address']) . '</td>';
    $openOrdersRowsHtml .= '<td>' . date('d-m-Y H:i', strtotime($order['datetime'])) . '</td>';
    $openOrdersRowsHtml .= '<td>' . htmlspecialchars($order['status_name']) . '</td>';
    $openOrdersRowsHtml .= '<td>
        <form method="POST">
            <input type="hidden" name="accepteer_order_id" value="' . $order['order_id'] . '">
            <button type="submit">Accepteren</button>
        </form>
    </td>';
    $openOrdersRowsHtml .= '</tr>';
}

$statusOptionsHtml = '';
foreach ($statuses as $id => $name) {
    $statusOptionsHtml .= '<option value="' . $id . '">' . htmlspecialchars($name) . '</option>';
}

$ordersRowsHtml = '';
foreach ($orders as $order) {
    $ordersRowsHtml .= '<tr>';
    $ordersRowsHtml .= '<td><a href="orderOverview.php?order_id=' . $order['order_id'] . '">' . $order['order_id'] . '</a></td>';
    $ordersRowsHtml .= '<td>' . htmlspecialchars($order['client_name']) . '</td>';
    $ordersRowsHtml .= '<td>' . htmlspecialchars($order['address']) . '</td>';
    $ordersRowsHtml .= '<td>' . date('d-m-Y H:i', strtotime($order['datetime'])) . '</td>';
    $ordersRowsHtml .= '<td>' . htmlspecialchars($order['personnel_username'] ?? 'Nog niet toegewezen') . '</td>';
    $ordersRowsHtml .= '<td>
        <form method="POST">
            <input type="hidden" name="order_id" value="' . $order['order_id'] . '">
            <select name="update_status_id" onchange="this.form.submit()">';

    foreach ($statuses as $id => $name) {
        $selected = ($id == $order['status_id']) ? 'selected' : '';
        $ordersRowsHtml .= '<option value="' . $id . '" ' . $selected . '>' . htmlspecialchars($name) . '</option>';
    }
    $ordersRowsHtml .= '</select>
        </form>
    </td>';
    $ordersRowsHtml .= '<td><a href="orderOverview.php?order_id=' . $order['order_id'] . '">Details</a></td>';
    $ordersRowsHtml .= '</tr>';
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Personeel - Bestellingen</title>
</head>
<body>
    <h1>Personeelsoverzicht</h1>

    <?php if (!empty($openOrders)): ?>
        <h2>Openstaande bestellingen (zonder toegewezen medewerker)</h2>
        <table border="1" cellpadding="5">
            <tr>
                <th>Bestelling ID</th>
                <th>Naam</th>
                <th>Adres</th>
                <th>Datum/tijd</th>
                <th>Status</th>
                <th>Actie</th>
            </tr>
            <?= $openOrdersRowsHtml ?>
        </table>
    <?php endif; ?>

    <h2>Alle bestellingen</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Naam</th>
            <th>Adres</th>
            <th>Datum</th>
            <th>Personeelslid</th>
            <th>Status</th>
            <th>Actie</th>
        </tr>
        <?= $ordersRowsHtml ?>
    </table>
</body>
</html>
