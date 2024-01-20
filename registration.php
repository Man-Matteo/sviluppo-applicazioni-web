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
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    // Rimuovo la visualizzazione diretta degli errori per motivi di sicurezza
                    error_reporting(0);

                    // Funzione per escapare i dati prima di visualizzarli nella pagina
                    function escape($value) {
                        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                    }

                    // Salva i dati immessi dal form in un array
                    $form_fields = [
                        $_POST["firstname"],
                        $_POST["lastname"],
                        filter_var($_POST['email'], FILTER_VALIDATE_EMAIL),
                        $_POST["pass"],
                        $_POST["confirm"]
                    ];

                    // Salva le regex in un altro array
                    $regex_patterns = [
                        "/\w{2,16}/",
                        "/\w{2,16}/",
                        "/\w+@\w+\.\w+/",
                        "/^(?=.*[a-zA-Z0-9])(?=.*[!@#$%^&*])(?=.*[0-9]).{8,}$/",
                    ];

                    // Controllo se i dati immessi rispettano le REGEX
                    for ($i = 0; $i < count($regex_patterns); $i++)
                        if (!preg_match($regex_patterns[$i], $form_fields[$i]))
                            die('<div class="error">Error: field ' . ($i + 1) . ' is not valid.</div>');
        
                    // Controllo se le password coincidono
                    if ($form_fields[3] !== $form_fields[4])
                        die('<div class="error">Error: passwords does not match.</div>');
                    

                    // Hash della password
                    $hashed_pass = password_hash($_POST["pass"], PASSWORD_DEFAULT); 

                    // Connessione al database
                    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                    
                    $conn = readWriteConnection();

                    //controllo che l'email non sia già presente nel database (PREPARE STATEMENT)
                    $checkQuery = "SELECT email FROM users WHERE email = ?";
                    $checkElem = array($form_fields[2]);
                    $checkParams = "s";
                    $checkResult = execStmt($conn, $checkQuery, $checkElem, $checkParams);
                    if (!$checkResult)
                        die('<div class="error">Error in check query.</div>');
        

                    if ($checkResult->num_rows > 0) 
                        die('<div class="error">Error: email not available.</div>');
                    

                    // Inserimento dei dati nel database (PREPARE STATEMENT)
                    $insertQuery = "INSERT INTO users(firstname, lastname, email, password) VALUES (?, ?, ?, ?)";
                    $insertParams = "ssss";
                    $insertElem = array($form_fields[0], $form_fields[1], $form_fields[2], $hashed_pass);
                    $insertResult = execStmt($conn, $insertQuery, $insertElem, $insertParams);
                    if (!$insertResult)
                        die('<div class="error">Error in insert query.</div>');

                    echo '<div class="success">Registration was successful!</div>';
                    echo '<br><br><a href="index.php"><button>Home</button></a>';
                    exit;    
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