<?php
session_start();
require_once './db_connectie.php';

$db = maakVerbinding();
$errors = [];
$success = false;

$username = $_SESSION['username'] ?? null;
$client_name = '';
$address = '';

if ($username) {
    $sql = "SELECT first_name, address FROM [User] WHERE username = :username";
    $stmt = $db->prepare($sql);
    $stmt->execute([':username' => $username]);
    if ($userData = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $client_name = $userData['first_name'] ?? '';
        $address = $userData['address'] ?? '';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$username) {
        $client_name = trim($_POST['client_name'] ?? '');
    }

    $address = trim($_POST['address'] ?? '');
    $datetimeInput = $_POST['datetime'] ?? '';

    $client_username = $username;

    if (!$client_username && empty($client_name)) {
        $errors[] = 'Naam is verplicht voor niet-ingelogde gebruikers.';
    }

    if (empty($address)) {
        $errors[] = 'Adres is verplicht.';
    }

    if (empty($datetimeInput)) {
        $errors[] = 'Datum en tijd zijn verplicht.';
    } else {
        $datetimeFormatted = str_replace('T', ' ', $datetimeInput);
        $dt = DateTime::createFromFormat('Y-m-d H:i', $datetimeFormatted);

        if (!$dt) {
            $errors[] = 'Ongeldige datum/tijd.';
        } else {
            $datetime = $dt->format('Y-m-d H:i:s');
        }
    }

    if (empty($_SESSION['cart']) || !is_array($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
        $errors[] = 'Je winkelwagen is leeg.';
    }

    if (empty($errors)) {
        try {
            $db->beginTransaction();

            $sql = "INSERT INTO Pizza_Order (client_username, client_name, personnel_username, datetime, status_id, address)
                    VALUES (:client_username, :client_name, NULL, :datetime, 1, :address)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':client_username' => $client_username,
                ':client_name' => $client_name,
                ':datetime' => $datetime,
                ':address' => $address
            ]);

            $order_id = $db->lastInsertId();

            $insertProductSql = "INSERT INTO Pizza_Order_Product (order_id, product_id, quantity) VALUES (:order_id, :product_id, :quantity)";
            $insertProductStmt = $db->prepare($insertProductSql);

            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                $insertProductStmt->execute([
                    ':order_id' => $order_id,
                    ':product_id' => $product_id,
                    ':quantity' => $quantity,
                ]);
            }

            $db->commit();

            unset($_SESSION['cart']);

            $success = true;
        } catch (Exception $e) {
            $db->rollBack();
            $errors[] = "Er is iets misgegaan bij het plaatsen van je bestelling: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bestelling afronden</title>
</head>
<body>
    <h1>Afrekenen</h1>

    <?php if ($success): ?>
        <p>Je bestelling is succesvol geplaatst!</p>
        <p><a href="index.php">Terug naar home</a></p>
    <?php else: ?>
        <?php if (!empty($errors)): ?>
            <ul style="color:red;">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="POST">
            <?php if ($username): ?>
                <p><strong>Ingelogd als:</strong> <?= htmlspecialchars($username) ?></p>

                <label for="client_name">Naam:</label><br>
                <input type="text" name="client_name" id="client_name" value="<?= htmlspecialchars($client_name) ?>" readonly><br><br>
            <?php else: ?>
                <label for="client_name">Naam:</label><br>
                <input type="text" name="client_name" id="client_name" value="<?= htmlspecialchars($client_name) ?>" required><br><br>
            <?php endif; ?>

            <label for="address">Adres:</label><br>
            <input type="text" name="address" id="address" value="<?= htmlspecialchars($address) ?>" required><br><br>

            <label for="datetime">Gewenste datum en tijd:</label><br>
            <input type="datetime-local" name="datetime" id="datetime" required><br><br>

            <button type="submit">Plaats bestelling</button>
        </form>
    <?php endif; ?>

    <p><a href="cart.php">Terug naar winkelwagen</a></p>
</body>
</html>
