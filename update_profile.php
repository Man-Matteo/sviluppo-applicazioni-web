<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- css di un'altra pagina qui? -->
        <link rel="stylesheet" href="css/update_profile_style.css">
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=IM+Fell+English">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="logout.js"></script>
        <title>Update Profile</title>
    </head>
    <body>
        <?php
            session_start();
            require 'functions.php';
            if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
                header("Location: login.php");
                exit();
            }

            $userEmail = $_SESSION['username'];

            try {
                
                $conn = readWriteConnection();

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $newFirstname = !empty($_POST['firstname']) ? $_POST['firstname'] : null;
                    $newLastname = !empty($_POST['lastname']) ? $_POST['lastname'] : null;
                    $newEmail = !empty($_POST['email']) ? $_POST['email'] : null;

                    $newCity = !empty($_POST['city']) ? $_POST['city'] : null;
                    $newAboutme = !empty($_POST['aboutme']) ? $_POST['aboutme'] : null;
                    $newSocial = !empty($_POST['social']) ? $_POST['social'] : null;

                    // CONTROLLI ERRORI PREPARE STATEMENT
                    $updateQuery = "UPDATE users SET firstname = ?, lastname = ?, email = ?, city = ?, aboutme = ?, social = ? WHERE email = ?";
                    $updateParams = "sssssss";
                    $updateElem = array($newFirstname, $newLastname, $newEmail ,$newCity, $newAboutme, $newSocial, $userEmail);
                    $updateResult = execStmt($conn, $updateQuery, $updateElem, $updateParams);
                    if(!$updateResult)
                        die("error in update profile query");

                    header("Location: show_profile.php");
                }

                $profileQuery = "SELECT firstname, lastname, email, password, city, aboutme, social FROM users WHERE email = ?";
                $profileParams = "s";
                $profileElem = array($userEmail);
                $profileResult = execStmt($conn, $profileQuery, $profileElem, $profileParams);
                if(!$profileResult)
                    die("error in collect profile data query");

                if ($profileResult->num_rows == 1) {
                    while ($row = mysqli_fetch_assoc($profileResult)) {
                            echo "<form method='post'>";
                                echo "<label for='firstname'>First Name:</label><br>";
                                echo "<input type='text' id='firstname' name='firstname' value='" . $row['firstname'] . "'><br>";

                                echo "<label for='lastname'>Last Name:</label><br>";
                                echo "<input type='text' id='lastname' name='lastname' value='" . $row['lastname'] . "'><br>";

                                echo "<label for='email'>Email:</label><br>";
                                echo "<input type='text' id='email' name='email' value='" . $row['email'] . "'><br>";

                                echo "<label for='city'>City:</label><br>";
                                echo "<input type='text' id='city' name='city' value='" . $row['city'] . "'><br>";

                                echo "<label for='aboutme'>About Me:</label><br>";
                                echo "<input type='text' id='aboutme' name='aboutme' value='" . $row['aboutme'] . "'><br>";

                                echo "<label for='social'>Social:</label><br>";
                                echo "<input type='text' id='social' name='social' value='" . $row['social'] . "'><br>";

                                echo "<button type='submit' value='Save'>Save</button>";
                                echo "<br>";
                                echo '<button type="submit" onclick="location.href=\'show_profile.php\'">Go back to your profile</button>';
                            echo "</form>";
                    }
                } else 
                    echo "Profile data not found";   
            } catch (Exception $e) {
                echo "An error occurred while retrieving profile data.";
            } finally {
                $conn->close();
            }
        ?>
    </body>
</html>