<?php

session_start();

require_once 'functions/checkUserRole.php';
require_once 'db_connectie.php';

$db = maakVerbinding();

if (!isset($_SESSION['username']) || !checkIsEmployee()) {
    header('Location: index.php');
    exit();
}


?>