<?php 
function checkIsEmployee() {
    
    if (!isset($_SESSION['username'])) {
        return false;
    }
    
    $db = maakVerbinding();

    $query = 'SELECT isEmployee FROM [pizzeria].[dbo].[User] WHERE username = :username';
    $stmt = $db->prepare($query);
    $params = array(':username' => $_SESSION['username']);
    $stmt->execute($params);
    $result = $stmt->fetch();
    if ($result['isEmployee'] == 1) {
        return true;
    }
    return false;
}
?>