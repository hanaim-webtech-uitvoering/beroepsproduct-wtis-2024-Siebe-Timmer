<?php

    session_start();
    
    require_once 'functions/checkUserRole.php';

    require_once 'db_connectie.php';

    $db = maakVerbinding();

    if (isset($_SESSION['username']) && !checkIsEmployee()) {
        header('Location: index.php');
        exit();
    }


    if (isset($_POST['sign-up'])) {
        $errors = [];
 
        $username          = trim(strip_tags($_POST['username']));
        $password          = trim(strip_tags($_POST['password']));
        $firstName          = trim(strip_tags($_POST['firstName']));
        $lastName          = trim(strip_tags($_POST['lastName']));
        $address          = trim(strip_tags($_POST['address']));

        if (empty($username)) {
            $errors[] = "Gebruikersnaam is verplicht";
        } else {

            $checkUsername = "SELECT COUNT(*) FROM [User] WHERE username = :username";
            $query = $db->prepare($checkUsername);
            $query->execute(['username' => $username]);
            if ($query->fetchColumn() > 0) {
                $errors[] = "Deze gebruikersnaam bestaat al";
            }
        }

        if (strlen($username) > 255) {
            $errors[] = "Gebruikersnaam mag niet langer zijn dan 255 karakters";
        }
        
        if (empty($password)) {
            $errors[] = "Wachtwoord is verplicht";
        }

        if (empty($firstName)) {
            $errors[] = "Voornaam is verplicht";
        } elseif (strlen($firstName) > 255) {
            $errors[] = "Voornaam mag niet langer zijn dan 255 karakters";
        }

        if (empty($lastName)) {
            $errors[] = "Achternaam is verplicht";
        } elseif (strlen($lastName) > 255) {
            $errors[] = "Achternaam mag niet langer zijn dan 255 karakters";
        }

        if (!empty($address) && strlen($address) > 255) {
            $errors[] = "Adres mag niet langer zijn dan 255 karakters";
        }

        if (!empty($errors)) { 
            $message = "<div>";
            foreach ($errors as $error) {
                $message .= "<li>$error</li>";
            }
            $message .= "</div>";
        } else {
            
            $passwordhash = password_hash($password, PASSWORD_DEFAULT);
            $role = checkIsEmployee() ? 'Personnel' : 'Client';
        
            $signupQuery = 'INSERT INTO [User](username, password, first_name, last_name, address, role)
                    values (:username, :password, :first_name, :last_name, :address, :role)';
            $query = $db->prepare($signupQuery);
            $data = $query->execute([
                'username' => $username,
                'password' => $passwordhash,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'address' => $address,
                'role' => $role,
            ]);
            $message = "Account succesvol aangemaakt!";
        }
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
                <input type="text" id="username" name="username" value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="password">Wachtwoord:</label>
                <input type="password" id="password" name="password">
            </div>

            <div class="form-group">
                <label for="firstName">Voornaam:</label>
                <input type="text" id="firstName" name="firstName" value="<?php echo isset($firstName) ? htmlspecialchars($firstName) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="lastName">Achternaam:</label>
                <input type="text" id="lastName" name="lastName" value="<?php echo isset($lastName) ? htmlspecialchars($lastName) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="address">Adres:</label>
                <input type="text" id="address" name="address" value="<?php echo isset($address) ? htmlspecialchars($address) : ''; ?>">
            </div>

            <button type="submit" name="sign-up">Registreren</button>
            
            <?php if(isset($message)){ echo $message; } ?>
             
            
            <p>Heb je al een account? <a href="login.php">Log hier in</a></p>
        </form>
    </main>
</body>

</html>