<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/update_profile_style.css">
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=IM+Fell+English">
        <title>Update Profile</title>
    </head>
    <body>
        <?php include '../partials/navbar.php'; ?>
        <header>
            <div class="header">
                <h1>Update Profile</h1>
            </div>
        </header>
        <?php
            session_set_cookie_params(0);
            session_start();
            require '../functions/functions.php';
            if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
                header("Location: login.php");
                exit();
            }

            $userEmail = $_SESSION['username'];

            try {
                
                $conn = readWriteConnection();

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $newFirstname = !empty($_POST['firstname']) ? clean_input($_POST['firstname']) : null;
                    $newLastname = !empty($_POST['lastname']) ? clean_input($_POST['lastname']) : null;
                    $newEmail = !empty($_POST['email']) ? filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) : null;

                    if ($userEmail != $newEmail) 
                        $_SESSION['username'] = $newEmail;

                    $newCity = !empty($_POST['city']) ? clean_input($_POST['city']) : null;
                    $newAboutme = !empty($_POST['aboutme']) ? clean_input($_POST['aboutme']) : null;
                    $newSocial = !empty($_POST['social']) ? clean_input($_POST['social']) : null;

                    $updateQuery = "UPDATE users SET firstname = ?, lastname = ?, email = ?, city = ?, aboutme = ?, social = ? WHERE email = ?";
                    $updateParams = "sssssss";
                    $updateElem = array($newFirstname, $newLastname, $newEmail ,$newCity, $newAboutme, $newSocial, $userEmail);
                    if(!execStmt($conn, $updateQuery, $updateElem, $updateParams)) {
                        $_SESSION['username'] = $userEmail;
                        die("Something went wrong");
                    }

                    header("Location: ../navbar/show_profile.php");
                    exit();
                }

                $profileQuery = "SELECT firstname, lastname, email, city, aboutme, social FROM users WHERE email = ?";
                $profileParams = "s";
                $profileElem = array($userEmail);
                $profileResult = execStmt($conn, $profileQuery, $profileElem, $profileParams);
                if(!$profileResult)
                    die("error in collect profile data query");

                if ($profileResult->num_rows == 1) {
                    echo "<div class='profile-table'>";
                    while ($row = mysqli_fetch_assoc($profileResult)) {
                            echo "<form method='post' action='update_profile.php'>";
                                echo "<label for='firstname'>First Name:</label><br>";
                                echo "<input type='text' id='firstname' name='firstname' value='" . $row['firstname'] . "'><br>";

                                echo "<label for='lastname'>Last Name:</label><br>";
                                echo "<input type='text' id='lastname' name='lastname' value='" . $row['lastname'] . "'><br>";

                                echo "<label for='email'>Email:</label><br>";
                                echo "<input type='text' id='email' name='email' value='" . $row['email'] . "'><br>";
                                if (isset($_SESSION['errorMessage'])) {
                                    echo "<p style='font-size:15px'>";
                                    echo $_SESSION['errorMessage'];
                                    echo "</p>";
                                    echo "<br>";
                                    unset($_SESSION['errorMessage']); // Unset the error message
                                }


                                echo "<label for='city'>City:</label><br>";
                                echo "<input type='text' id='city' name='city' value='" . $row['city'] . "'><br>";

                                echo "<label for='aboutme'>About Me:</label><br>";
                                echo "<input type='text' id='aboutme' name='aboutme' value='" . $row['aboutme'] . "'><br>";

                                echo "<label for='social'>Social:</label><br>";
                                echo "<input type='text' id='social' name='social' value='" . $row['social'] . "'><br>";

                                echo "<button type='submit' value='Save'>Save</button>";
                                echo "<br>";
                            echo "</form>";
                            echo '<button type="submit" onclick="location.href=\'../navbar/show_profile.php\'">Go back to your profile</button>';
                    }
                    echo "</div>";
                } else 
                    echo "Profile data not found";   
            } catch (Exception $e) {
                $_SESSION['errorMessage'] = "This email is not available!"; // Store the error message in a session variable
                header("Location: update_profile.php");
                exit();
            } finally {
                $conn->close();
            }
        include '../partials/footer.php';
        ?>
    </body>
</html>