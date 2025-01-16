<?php //TODO: emailadres opslaan uit session en het is niet meer mogelijk om het te wijzigen
session_start();
if (isset($_SESSION['username'])) {
    header('Location: index.php');
}
    require_once 'db_connectie.php';

    $db = maakVerbinding();


    if (isset($_POST['sign-up'])) {
        $errors = [];
 
        $username          = trim(strip_tags($_POST['username']));
        $password          = trim(strip_tags($_POST['password']));
        $firstName          = trim(strip_tags($_POST['firstName']));
        $lastName          = trim(strip_tags($_POST['lastName']));
        $address          = trim(strip_tags($_POST['address']));
        
        }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>

<body>
    <main>
        <form method="POST">
            <h2>Registreren</h2>
            
            <div class="form-group">
                <label for="username">Gebruikersnaam:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Wachtwoord:</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="firstName">Voornaam:</label>
                <input type="text" id="firstName" name="firstName" required>
            </div>

            <div class="form-group">
                <label for="lastName">Achternaam:</label>
                <input type="text" id="lastName" name="lastName" required>
            </div>

            <div class="form-group">
                <label for="address">Adres:</label>
                <input type="text" id="address" name="address" required>
            </div>

            <button type="submit" name="sign-up">Registreren</button>
            
            <p>Heb je al een account? <a href="login.php">Log hier in</a></p>
        </form>
    </main>
</body>

</html>