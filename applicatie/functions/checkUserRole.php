<?php 
function checkIsEmployee() {

    
    if (!isset($_SESSION['username'])) {
        return false;
    }
    
    $db = maakVerbinding();

    $query = 'SELECT role FROM [pizzeria].[dbo].[User] WHERE username = :username';
    $stmt = $db->prepare($query);
    $params = array(':username' => $_SESSION['username']);
    $stmt->execute($params);
    $result = $stmt->fetch();
    if ($result['role'] === 'Personnel') {
        return true;
    }else{
    return false;
}}
?>