<?php
require_once 'db_connectie.php';
$db = maakVerbinding();

$orderInfo = null;
$producten = [];
$foutmelding = '';
$totaalPrijs = 0.0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = (int) $_POST['order_id'];
    $clientName = trim($_POST['client_name']);

    $sql = "
        SELECT 
            po.order_id,
            po.client_name,
            po.datetime,
            po.address,
            s.status_name,
            pop.product_id,
            pop.quantity,
            p.name AS product_name,
            p.price
        FROM [pizzeria].[dbo].[Pizza_Order] po
        LEFT JOIN [pizzeria].[dbo].[Status] s ON po.status_id = s.status_id
        LEFT JOIN [pizzeria].[dbo].[Pizza_Order_Product] pop ON po.order_id = pop.order_id
        LEFT JOIN [pizzeria].[dbo].[Product] p ON pop.product_id = p.product_id
        WHERE po.order_id = ? AND po.client_name = ?
    ";

    $stmt = $db->prepare($sql);
    $stmt->execute([$orderId, $clientName]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($result)) {
        $foutmelding = 'Geen bestelling gevonden voor dit ordernummer en naam.';
    } else {
        $orderInfo = $result[0];
        foreach ($result as $row) {
            if (!$row['product_name']) continue;
            $producten[] = [
                'name' => $row['product_name'],
                'quantity' => (int) $row['quantity'],
                'price' => (float) $row['price'],
                'subtotal' => (float) $row['price'] * (int) $row['quantity']
            ];
            $totaalPrijs += (float) $row['price'] * (int) $row['quantity'];
        }
    }
}

$content = '';

if ($foutmelding !== '') {
    $content .= '<p style="color:red;">' . htmlspecialchars($foutmelding) . '</p>';
} elseif ($orderInfo !== null) {
    $content .= '<h2>Bestelling #' . htmlspecialchars($orderInfo['order_id']) . '</h2>';
    $content .= '<p><strong>Klant:</strong> ' . htmlspecialchars($orderInfo['client_name']) . '</p>';
    $content .= '<p><strong>Bezorgadres:</strong> ' . htmlspecialchars($orderInfo['address']) . '</p>';
    $content .= '<p><strong>Datum/tijd:</strong> ' . htmlspecialchars($orderInfo['datetime']) . '</p>';
    $content .= '<p><strong>Status:</strong> ' . htmlspecialchars($orderInfo['status_name'] ?? 'Onbekend') . '</p>';

    $content .= '<h3>Bestelde producten:</h3>';
    $content .= '<table border="1" cellpadding="5" cellspacing="0">';
    $content .= '<tr><th>Product</th><th>Aantal</th><th>Prijs per stuk</th><th>Subtotaal</th></tr>';

    foreach ($producten as $item) {
        $content .= '<tr>';
        $content .= '<td>' . htmlspecialchars($item['name']) . '</td>';
        $content .= '<td>' . $item['quantity'] . '</td>';
        $content .= '<td>€ ' . number_format($item['price'], 2, ',', '.') . '</td>';
        $content .= '<td>€ ' . number_format($item['subtotal'], 2, ',', '.') . '</td>';
        $content .= '</tr>';
    }

    $content .= '<tr>';
    $content .= '<td colspan="3"><strong>Totaal</strong></td>';
    $content .= '<td><strong>€ ' . number_format($totaalPrijs, 2, ',', '.') . '</strong></td>';
    $content .= '</tr>';

    $content .= '</table>';
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bestelstatus</title>
</head>
<body>
    <h1>Bestelstatus opvragen</h1>

    <form method="POST">
        <label for="order_id">Ordernummer:</label>
        <input type="number" name="order_id" id="order_id" required><br><br>

        <label for="client_name">Naam:</label>
        <input type="text" name="client_name" id="client_name" required><br><br>

        <button type="submit">Bekijk bestelling</button>
    </form>

    <?= $content ?>
</body>
</html>
