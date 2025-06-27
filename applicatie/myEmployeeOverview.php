<?php
session_start();
require_once './db_connectie.php';

if (!isset($_SESSION['username'])) {
    die("Alleen ingelogd personeel heeft toegang.");
}

$db = maakVerbinding();
$personnelUsername = $_SESSION['username'];

$stmt = $db->prepare("
    SELECT po.*, s.status_name 
    FROM Pizza_Order po
    JOIN Status s ON po.status_id = s.status_id
    WHERE po.personnel_username = :username
    ORDER BY datetime DESC
");
$stmt->execute([':username' => $personnelUsername]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

function renderBestellingen(array $orders): string {
    if (empty($orders)) {
        return '<p>Je hebt nog geen bestellingen toegewezen gekregen.</p>';
    }

    $html = '<table border="1" cellpadding="5" cellspacing="0">';
    $html .= '<thead><tr>
                <th>ID</th>
                <th>Klantnaam</th>
                <th>Adres</th>
                <th>Datum</th>
                <th>Status</th>
                <th>Details</th>
              </tr></thead><tbody>';

    foreach ($orders as $order) {
        $html .= '<tr>';
        $html .= '<td>' . (int)$order['order_id'] . '</td>';
        $html .= '<td>' . htmlspecialchars($order['client_name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($order['address']) . '</td>';
        $html .= '<td>' . date('d-m-Y H:i', strtotime($order['datetime'])) . '</td>';
        $html .= '<td>' . htmlspecialchars($order['status_name']) . '</td>';
        $html .= '<td><a href="orderOverview.php?order_id=' . (int)$order['order_id'] . '">Bekijk</a></td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table>';
    return $html;
}

$bestellingenHtml = renderBestellingen($orders);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Mijn bestellingen</title>
</head>
<body>
    <h1>Mijn bestellingen (<?= htmlspecialchars($personnelUsername) ?>)</h1>
    <p><a href="employeeOverview.php">← Terug naar overzicht</a></p>
    <?= $bestellingenHtml ?>
</body>
</html>
