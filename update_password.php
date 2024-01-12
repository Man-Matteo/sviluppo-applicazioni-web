<?php
    session_start();
    require 'functions.php';
    if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
        header("Location: login.php");
        exit();
    }

    $conn = readWriteConnection();

    // Ottieni l'ID dell'utente dalla sessione
    $userEmail = $_SESSION['username'];

    // Estrai l'hash della password corrente dal database
    $passQuery = "SELECT password FROM users WHERE email = ?";
    $passParam = "s";
    $passElem = array($userEmail);
    $passResult = execStmt($conn, $passQuery, $passElem, $passParam);
    if(!$passResult)
        die("erorr in old pass query");
    $row = $passResult->fetch_assoc();
    $current_password_hash = $row['password'];

    // Verifica se il modulo è stato inviato
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ricevi i dati dal modulo
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $regex = "/^(?=.*[a-zA-Z0-9])(?=.*[!@#$%^&*])(?=.*[0-9]).{8,}$/";

        if (!preg_match($regex, $new_password)) {
            die ("Error: meet all password requirements");
        }
        if (password_verify($old_password, $current_password_hash)) {
            if ($new_password === $confirm_password) {
                $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                $newPassQuery = "UPDATE users SET password = ? WHERE email = ?";
                $newPassParams = "ss";
                $newPassElem = array($new_password_hash, $userEmail);
                $newPassResult = execStmt($conn, $newPassQuery, $newPassElem, $newPassParams);
                if(!$newPassResult)
                    die("error in update pass query");
               
                // Reindirizza alla pagina di successo o al pannello utente
                header('Location: logout.php');
                exit();
            } else 
                $error_message = "new passwords does not match.";   
        } else 
            $error_message = "old password is not correct.";
    }

    // Chiudi la connessione al database
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="logout.js"></script>
    <title>Update Password</title>
</head>
<body>
    <h2>Update Password</h2>

    <?php if (isset($error_message)) : ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form method="post" action="update_password.php">

        <label for="old_password">Old password:</label>
        <input type="password" name="old_password" required><br>

        <label for="new_password">New password:</label>
        <input type="password" name="new_password" pattern="^(?=.*[a-zA-Z0-9])(?=.*[!@#$%^&*])(?=.*[0-9]).{8,}$" title="The password must contain at least one alphabetic character, one special character, one number and be at least 8 characters long." required><br>
        
        <label for="confirm_password">Confirm new password:</label>
        <input type="password" name="confirm_password" required><br>

        <input type="submit" value="Update password">
    </form>
</body>
</html>