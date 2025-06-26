<?php
session_start();
require_once 'db_connectie.php';

$db = maakVerbinding();

$cart = $_SESSION['cart'] ?? [];
$totalPrice = 0;
?>
<h1>Winkelwagen</h1>

<?php if (empty($cart)): ?>
    <p>Je winkelwagen is nog leeg.</p>
<?php else: ?>
    <?php
    $productIds = array_keys($cart);
    $productsById = [];

    if (!empty($productIds)) {
        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        $sql = "SELECT product_id, name, price FROM [pizzeria].[dbo].[Product] WHERE product_id IN ($placeholders)";
        $stmt = $db->prepare($sql);
        $stmt->execute($productIds);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as $product) {
            $productsById[$product['product_id']] = $product;
        }
    }
    ?>

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Product</th>
            <th>Prijs per stuk</th>
            <th>Aantal</th>
            <th>Subtotaal</th>
            <th>Acties</th>
        </tr>

        <?php 
        $totalPrice = 0;
        foreach ($cart as $productId => $quantity): 
            if (!isset($productsById[$productId])) continue;
            $product = $productsById[$productId];
            $subtotal = $product['price'] * $quantity;
            $totalPrice += $subtotal;
        ?>
        <tr>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td>€ <?= number_format($product['price'], 2, ',', '.') ?></td>
            <td>
                <form action="functions/updateCart.php" method="POST" style="margin:0;">
                    <input type="number" name="quantity" value="<?= (int)$quantity ?>" min="1" style="width:50px;">
            </td>
            <td>€ <?= number_format($subtotal, 2, ',', '.') ?></td>
            <td>
                    <input type="hidden" name="productId" value="<?= $productId ?>">
                    
                    <button type="submit" name="action" value="set" title="Aantal bijwerken">Bijwerken</button>

                    <button type="submit" name="action" value="removeOne" title="Verwijder 1">-1</button>

                    <button type="submit" name="action" value="remove" title="Verwijder alles">Verwijder alles</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>

        <tr>
            <td colspan="3"><strong>Totaal</strong></td>
            <td colspan="2"><strong>€ <?= number_format($totalPrice, 2, ',', '.') ?></strong></td>
        </tr>
    </table>

    <p><a href="index.php">Verder winkelen</a> | <a href="checkout.php">Afrekenen</a></p>

<?php endif; ?>
