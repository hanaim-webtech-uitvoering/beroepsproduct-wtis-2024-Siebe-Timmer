<?php
session_start();
require_once './db_connectie.php';

if (!isset($_SESSION['username'])) {
    die("Alleen ingelogd personeel heeft toegang.");
}

$db = maakVerbinding();
$personnelUsername = $_SESSION['username'];

$statusStmt = $db->query("SELECT status_id, status_name FROM Status");
$statuses = $statusStmt->fetchAll(PDO::FETCH_KEY_PAIR);

$stmt = $db->prepare("SELECT po.*, s.status_name 
                      FROM Pizza_Order po
                      JOIN Status s ON po.status_id = s.status_id
                      WHERE po.personnel_username = :username
                      ORDER BY datetime DESC");
$stmt->execute([':username' => $personnelUsername]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    <?php if (count($orders) === 0): ?>
        <p>Je hebt nog geen bestellingen toegewezen gekregen.</p>
    <?php else: ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Naam klant</th>
                    <th>Adres</th>
                    <th>Datum</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order['order_id'] ?></td>
                        <td><?= htmlspecialchars($order['client_name']) ?></td>
                        <td><?= htmlspecialchars($order['address']) ?></td>
                        <td><?= date('d-m-Y H:i', strtotime($order['datetime'])) ?></td>
                        <td><?= htmlspecialchars($order['status_name']) ?></td>
                        <td><a href="orderOverview.php?order_id=<?= $order['order_id'] ?>">Bekijk</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
