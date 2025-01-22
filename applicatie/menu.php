<?php

session_start();

if(!$_SESSION['username']){
  header('Location: login.php');
}

require_once 'db_connectie.php';

$db = maakVerbinding();

// Eerste query voor product informatie
$query = 'SELECT productId, productName, price, typeName 
          FROM [pizzeria].[dbo].[Product] p 
          INNER JOIN [pizzeria].[dbo].[ProductType] pt ON p.typeId = pt.typeId';

$data = $db->query($query);
$products = [];

// Verzamel eerst alle product informatie
while($rij = $data->fetch()) {
    $products[$rij['productId']] = [
        'name' => $rij['productName'],
        'price' => $rij['price'],
        'category' => $rij['typeName'],
        'ingredients' => []
    ];
}

// Tweede query voor ingrediënten
$query_ingredients = 'SELECT productId, ingredientName 
                     FROM [pizzeria].[dbo].[Product_Ingredient] pi 
                     INNER JOIN [pizzeria].[dbo].[Ingredient] i 
                     ON pi.ingredientId = i.ingredientId';

$data_ingredients = $db->query($query_ingredients);

// Voeg ingrediënten toe aan de juiste producten
while($rij = $data_ingredients->fetch()) {
    if(isset($products[$rij['productId']])) {
        $products[$rij['productId']]['ingredients'][] = $rij['ingredientName'];
    }
}

// Bouw de menu HTML
$menu = '<div>';

foreach($products as $productId => $product) {
    $ingredients_part = '';
    if (!empty($product['ingredients'])) {
        $ingredients_list = implode(', ', $product['ingredients']);
        $ingredients_part = "<p>Ingrediënten: {$ingredients_list}</p>";
    }
    
    $menu .= "
        <h2>{$product['name']}</h2>
        <h3>€{$product['price']}</h3>
        <p>{$product['category']}</p>
        {$ingredients_part}
        <input type='number' id='quantity-{$productId}' value='1' min='1' style='width: 50px;'>
        <button onclick='addToCart({$productId})' data-product-id='{$productId}'>Toevoegen</button>
    ";
}

$menu .= "</div>";

?>

<html>
    <body>
        <?= $menu ?>
    </body>
</html>