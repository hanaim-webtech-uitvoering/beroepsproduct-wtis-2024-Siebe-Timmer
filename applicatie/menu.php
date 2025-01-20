<?php

session_start();

if(!$_SESSION['username']){
  header('Location: login.php');
}

require_once 'db_connectie.php';

$db = maakVerbinding();

$query = 'SELECT * FROM [pizzeria].[dbo].[Product]';

$data = $db->query($query);

$html_table = '<table>';
$html_table = $html_table . '<tr><th>Product</th><th>Prijs</th><th>Categorie</th></tr>';

while($rij = $data->fetch()) {
  $product = $rij['name'];
  $price = $rij['price'];
  $category = $rij['type_id'];

  $html_table = $html_table . "<tr><td>$product</td><td>$price</td><td>$category</td><td><button>toevoegen</button></td></tr>";
}

$html_table = $html_table . "</table>";

?>

<html>
    <body>
        <?= $html_table ?>
    </body>
</html>