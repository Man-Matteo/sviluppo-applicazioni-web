<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/show_profile_style.css">
        <title>Profile</title>
    </head>
    <body>
        <header>
            <button type="button" onclick="location.href='../index.php'">Home</button>
            <div class="header">
                <h1>Account Profile</h1>
            </div>
        </header>
        <div class="container">
            <?php
                session_set_cookie_params(0);
                session_start();
                require '../functions/functions.php';
                
                if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
                    header("Location: ../user/login.php");
                    exit();
                }

                $userEmail = $_SESSION['username'];
                try {
                    $conn = readOnlyConnection();

                    $profileQuery = "SELECT firstname, lastname, email, city, aboutme, social FROM users WHERE email = ?";
                    $profileElem = array($userEmail);
                    $profileParams = "s";
                    $profileResult = execStmt($conn, $profileQuery, $profileElem, $profileParams);
                    if(!$profileResult)
                        die("something went wrong");

                    if ($profileResult->num_rows == 1) {
                        echo "<table class='profile-table'>";
                        while ($row = mysqli_fetch_assoc($profileResult)) {
                            echo "<tr>";
                            echo "<th>Attribute</th>";
                            echo "<th>Value</th>";
                            echo "</tr>";

                            foreach ($row as $key => $value) {
                                echo "<tr>";
                                echo "<td>$key:</td>";
                                echo "<td>$value</td>";
                                echo "</tr>";
                            }
                        }
                        echo "</table>";
                        echo "<div class='profile-buttons'>";

                        echo "<button type='button' onclick='location.href=\"../user/update_profile.php\"'>Modify profile</button>";
                        echo "<button type='button' onclick='location.href=\"../user/update_password.php\"'>Modify password</button>";
                        echo "<button type='submit' onclick='location.href=\"../orders/order_storage.php\"'>Order history</button>";
                        echo "<button type='submit' onclick='location.href=\"../functions/logout.php\"'>Logout</button>";
                        
                        echo "</div>";

                    } else 
                        echo "Profile data not found";
                } catch (Exception $e) {
                    echo "An error occurred while retrieving profile data.";
                } finally {
                    $conn->close();
                }
            ?>
        </div>
    </body>
</html>