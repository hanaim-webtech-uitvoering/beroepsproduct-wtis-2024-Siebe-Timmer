<?php
require_once 'db_connectie.php';
require_once 'functions/checkUserRole.php';

if(checkIsEmployee()){
    return "<li><a href='orders.php'>Alle bestellingen</a></li>
            <li><a href='myorders.php'>Mijn bestellingen</a></li>
            <li><a href='logout.php'>Uitloggen</a></li>";
} else{
    return "<li><a href='orderhistory.php'>Mijn bestellingen</a></li>
            <li><a href='cart.php'>Winkelwagen</a></li>
            <li><a href='logout.php'>Uitloggen</a></li>";
}




