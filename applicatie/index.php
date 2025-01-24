<?php

session_start();

if(!$_SESSION['username']){
  header('Location: login.php');
}

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
    <?= require_once 'navbar.php' ?>
    <?= require_once 'menu.php' ?>
</body>
</html>