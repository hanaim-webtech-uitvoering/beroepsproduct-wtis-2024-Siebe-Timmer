<?php
require_once 'db_connectie.php';

$db = maakVerbinding();

$cart = $_SESSION['cart'] ?? [];

$productTypesSql = "SELECT type_id, name FROM [pizzeria].[dbo].[Product_Type] ORDER BY name";
$productTypesStmt = $db->query($productTypesSql);
$productTypes = [];
while ($type = $productTypesStmt->fetch(PDO::FETCH_ASSOC)) {
    $productTypes[$type['type_id']] = [
        'name' => $type['name'],
        'products' => []
    ];
}

$productsSql = "SELECT product_id, name, price, type_id FROM [pizzeria].[dbo].[Product]";
$productsStmt = $db->query($productsSql);
$products = [];
while ($product = $productsStmt->fetch(PDO::FETCH_ASSOC)) {
    $products[$product['product_id']] = $product;
    if (isset($productTypes[$product['type_id']])) {
        $productTypes[$product['type_id']]['products'][] = &$products[$product['product_id']];
    }
}

$ingredientsSql = "
    SELECT pi.product_id, i.name AS ingredientName
    FROM [pizzeria].[dbo].[Product_Ingredient] pi
    INNER JOIN [pizzeria].[dbo].[Ingredient] i ON pi.ingredient_id = i.ingredient_id
";
$ingredientsStmt = $db->query($ingredientsSql);
while ($ingredient = $ingredientsStmt->fetch(PDO::FETCH_ASSOC)) {
    $pid = $ingredient['product_id'];
    if (isset($products[$pid])) {
        if (!isset($products[$pid]['ingredients'])) {
            $products[$pid]['ingredients'] = [];
        }
        $products[$pid]['ingredients'][] = $ingredient['ingredientName'];
    }
}

function renderProductForm($productId, $name, $price, $ingredients = [], $cartQuantity = 0) {
    $ingredientsText = !empty($ingredients)
        ? 'Ingrediënten: ' . htmlspecialchars(implode(', ', $ingredients))
        : 'Geen ingrediënten gevonden.';

    $priceFormatted = number_format($price, 2, ',', '.');

    $html = "
    <form action='functions/updateCart.php' method='POST'>
        <h3>" . htmlspecialchars($name) . "</h3>
        <p>Prijs: €{$priceFormatted}</p>
        <p>{$ingredientsText}</p>
        <p>In winkelwagen: {$cartQuantity}</p>
        <label>
            Aantal toevoegen:
            <input type='number' name='quantity' value='1' min='1' required>
        </label>
        <input type='hidden' name='productId' value='{$productId}'>
        <input type='hidden' name='action' value='update'>
        <button type='submit'>Toevoegen</button>
    </form>";

    if ($cartQuantity > 0) {
        $html .= "
        <form action='functions/updateCart.php' method='POST'>
            <input type='hidden' name='productId' value='{$productId}'>
            <input type='hidden' name='quantity' value='1'>
            <input type='hidden' name='action' value='removeOne'>
            <button type='submit'>Verwijder 1</button>
        </form>";
    }

    return $html;
}

$menu = '';
foreach ($productTypes as $type) {
    $menu .= "<h2>" . htmlspecialchars($type['name']) . "</h2>";

    if (empty($type['products'])) {
        $menu .= "<p>Geen producten beschikbaar.</p>";
    } else {
        foreach ($type['products'] as $product) {
            $pid = $product['product_id'];
            $ingredients = $product['ingredients'] ?? [];
            $cartQuantity = $cart[$pid] ?? 0;
            $menu .= renderProductForm($pid, $product['name'], $product['price'], $ingredients, $cartQuantity);
        }
    }
}

echo $menu;
?>
