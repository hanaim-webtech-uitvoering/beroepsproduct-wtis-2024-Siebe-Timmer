<?php
require_once 'db_connectie.php';
require_once 'functions/checkUserRole.php';

if(isset($_SESSION['username'])){
if(checkIsEmployee()){
    return "<li><a href='employeeOverview.php'>Alle bestellingen</a></li>
            <li><a href='myEmployeeOverview.php'>Mijn bestellingen</a></li>
            <li><a href='logout.php'>Uitloggen</a></li>";
} else{
    return "<li><a href='myOrders.php'>Mijn bestellingen</a></li>
            <li><a href='cart.php'>Winkelwagen</a></li>
            <li><a href='logout.php'>Uitloggen</a></li>";
}
} else{
    return "<li><a href='login.php'>Inloggen</a></li>
    <li><a href='cart.php'>Winkelwagen</a></li>
    <li><a href='orderStatus.php'>Status bestelling</a></li>";
}




