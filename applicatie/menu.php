<?php

if(!$_SESSION['username']){
  header('Location: login.php');
}

require_once 'db_connectie.php';

$db = maakVerbinding();

$query = 'SELECT productId, productName, price, typeName 
          FROM [pizzeria].[dbo].[Product] p 
          INNER JOIN [pizzeria].[dbo].[ProductType] pt ON p.typeId = pt.typeId';

$data = $db->query($query);
$products = [];

while($rij = $data->fetch()) {
    $products[$rij['productId']] = [
        'name' => $rij['productName'],
        'price' => $rij['price'],
        'category' => $rij['typeName'],
        'ingredients' => []
    ];
}

$query_ingredients = 'SELECT productId, ingredientName 
                     FROM [pizzeria].[dbo].[Product_Ingredient] pi 
                     INNER JOIN [pizzeria].[dbo].[Ingredient] i 
                     ON pi.ingredientId = i.ingredientId';

$data_ingredients = $db->query($query_ingredients);

while($rij = $data_ingredients->fetch()) {
    if(isset($products[$rij['productId']])) {
        $products[$rij['productId']]['ingredients'][] = $rij['ingredientName'];
    }
}

$menu = '<div>';

foreach($products as $productId => $product) {
    $ingredients_part = '';
    if (!empty($product['ingredients'])) {
        $ingredients_list = implode(', ', $product['ingredients']);
        $ingredients_part = "<p>Ingrediënten: {$ingredients_list}</p>";
    }
    
    $menu .= "
        <form action='functions/updateCart.php' method='POST'>
            <h2>{$product['name']}</h2>
            <h3>€{$product['price']}</h3>
            <p>{$product['category']}</p>
            {$ingredients_part}
            <input type='number' name='quantity' value='1' min='1' style='width: 50px;'>
            <input type='hidden' name='productId' value='{$productId}'>
            <input type='hidden' name='action' value='update'>
            <button type='submit'>Toevoegen</button>
        </form>
    ";
}

$menu .= "</div>";

return $menu;

?>

