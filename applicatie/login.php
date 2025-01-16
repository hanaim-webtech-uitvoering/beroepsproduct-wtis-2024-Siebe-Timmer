<?php
session_start();
require_once './db_connectie.php';

$melding = '';

if (isset($_POST['login'])) {
    $errors = [];

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username)) {
        $errors[] = 'Gebruikersnaam is vereist.';
    }

    if (empty($password)) {
        $errors[] = 'Wachtwoord is vereist.';
    }

    if (!empty($username) && !empty($password)) {
        $db = maakVerbinding();
        $sql = 'SELECT username, password
                FROM [pizzeria].[dbo].[User]
                WHERE username = :username';
        $query = $db->prepare($sql);
        $data_array = [
            ':username' => $username,
        ];
        $query->execute($data_array);
        
        if ($row = $query->fetch()) {
            $username = $row['username'];
            $passwordhash = $row['password'];

            if(password_verify($password, $passwordhash)) {
                $_SESSION['username'] = $username;
                header('location: ./../index.php');
                exit();
            } else {
                $errors[] = 'De combinatie van gebruikersnaam en wachtwoord is niet geldig.';
            }
        } else {
            $errors[] = 'De combinatie van gebruikersnaam en wachtwoord is niet geldig.';
        }
    }

    if (count($errors) > 0) {
        $melding = "<div>";
        foreach ($errors as $error) {
            $melding .= "<li>$error</li>";
        }
        $melding .= "</div>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen</title>
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
</head>

<body>


    <main>

        <div>
            <h2>Welkom terug,</h2>

            <form method="post">
                <div>
                    <input type="text" name="username" id="username" placeholder="user123" value="<?php if (isset($username)) {
                                                                                                                    echo $username;
                                                                                                                } ?>">
                    <label for="username">Gebruikersnaam</label>
                </div>
                <div>
                    <input type="password" name="password" id="password" placeholder="Wachtwoord" value="<?php if (isset($password)) {
                                                                                                                    echo $password;
                                                                                                                } ?>">
                    <label for="password">Wachtwoord</label>
                </div>
                <?= $melding ?>
                <input type="submit" value="Inloggen" name="login">
                <p>Nog geen account bij ons? <a href="registreren.php">Account aanmaken</a>.</p>
            </form>
        </div>


    </main>
 
</body>

</html>