<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=IM+Fell+English">
        <link rel="stylesheet" href="css/registration_style.css">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="logout.js"></script>
        <title>Registration</title>
    </head>
    <body>
        <button class="submit-btn" onclick="location.href='index.php'">Home</button>
        <div class="container">
            <?php
                require 'functions.php';
                if(IfLogged()){
                    header("Location: http://localhost/index.php");
                    exit();
                }    
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Rimuovo la visualizzazione diretta degli errori per motivi di sicurezza
                    error_reporting(0);

                    // Funzione per escapare i dati prima di visualizzarli nella pagina
                    function escape($value) {
                        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                    }

                    $firstname = clean_input($_POST["firstname"]);
                    $lastname = clean_input($_POST["lastname"]);
                    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
                    $password = clean_input($_POST["pass"]);
                    $confirm = clean_input($_POST["confirm"]);

                    // Controllo se le password coincidono
                    if ($password !== $confirm)
                        die('<div class="error">Error: passwords does not match.</div>');
                    
                    // Hash della password
                    $hashed_pass = password_hash($password, PASSWORD_DEFAULT); 

                    // Connessione al database                    
                    $conn = readWriteConnection();

                    //controllo che l'email non sia già presente nel database
                    $checkQuery = "SELECT email FROM users WHERE email = ?";
                    $checkElem = array($email);
                    $checkParams = "s";
                    $checkResult = execStmt($conn, $checkQuery, $checkElem, $checkParams);
                    if (!$checkResult)
                        die('<div class="error">Error in check query.</div>');
        

                    if ($checkResult->num_rows === 1) 
                        die('<div class="error">Error: email not available.</div>');
                    

                    // Inserimento dei dati nel database
                    $insertQuery = "INSERT INTO users(firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
                    $insertParams = "ssss";
                    $insertElem = array($firstname, $lastname, $email, $hashed_pass);
                    $insertResult = execStmt($conn, $insertQuery, $insertElem, $insertParams);
                    if (!$insertResult)
                        die('<div class="error">Error in insert query.</div>');

                    echo '<div class="success">Registration was successful!</div>';
                    echo '<br><br><a href="index.php"><button>Home</button></a>';
                    $conn->close();
                    exit();    
                }
            ?>

            <form id="registration-form" method="post">
                <label for="firstname">Firstname</label>
                <input type="text" name="firstname" pattern="\w{2,16}" title="The name must contain at least 2 alphanumeric characters." required>

                <label for="lastname">Lastname</label>
                <input type="text" name="lastname" pattern="\w{2,16}" title="The surname must contain at least 2 alphanumeric characters" required>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" pattern="\w+@\w+\.\w+" title="Enter a valid email address." required>

                <label for="pass">Password</label>
                <input type="password" name="pass" pattern="^(?=.*[a-zA-Z0-9])(?=.*[!@#$%^&*])(?=.*[0-9]).{8,}$" title="The password must contain at least one alphabetic character, one special character, one number and be at least 8 characters long." required>

                <label for="confirm">Confirm password</label>
                <input type="password" name="confirm" required>

                <input type="submit" name="submit" class="submit-btn" value="Register">
            </form>
        </div>
    </body>
</html>