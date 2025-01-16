<?php

session_start();

if(!$_SESSION['username']){
  header('Location: login.php');
}

require_once 'db_connectie.php';

$db = maakVerbinding();

$query = 'select username
          from [pizzeria].[dbo].[User]';

$data = $db->query($query);

$html_table = '<table>';
$html_table = $html_table . '<tr><th>Naam</th></tr>';

while($rij = $data->fetch()) {
  $username = $rij['username'];

  $html_table = $html_table . "<tr><td>$username</td></tr>";
}

$html_table = $html_table . "</table>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
</head>
<body>
    <h1>It Works!</h1>
    <br>
    <?php echo $html_table ?>
    <br>
    Alle technische informatie over je webserver vind je hier: <a href="phpinfo.php">http://<?=$_SERVER['HTTP_HOST']?>/phpinfo.php</a>
    <br>
    <br>
    Een voorbeeld van een pagina die gegevens uit de database haalt vind je hier: <a href="componist-aantalstukken.php">http://<?=$_SERVER['HTTP_HOST']?>/componist-aantalstukken.php</a>
</body>
</html>