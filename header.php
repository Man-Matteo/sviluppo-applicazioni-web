<?php 
session_set_cookie_params(0);
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/index_style.css">
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Bilbo+Swash+Caps">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <title>Cancelli di Baldur</title> 
        <link rel="shortcut icon" href="Images/DnD-Symbol.png">
    </head>
    <body>
        <header>
            <div class="title">
                <img alt="Title" src="Images/title_white.png">
            </div>

            <form class="search" action="search.php">
                <input type="text" placeholder="Search.." name="search">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>

            <?php
                if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
                    // Utente loggato, mostra il menu completo
                    echo '
                    <div class="dropdown">
                        <button id="myBtn" class="dropbtn">My Account ↓</button>
                        <div id="myDropdown" class="dropdown-content">     
                            <a href="show_profile.php">Show profile</a>
                            <a href="cart.php">Cart</a>
                            <a href="crowdfunding.php">Support us</a>
                            <a href="logout.php">Logout</a>
                        </div>
                    </div>';
                } else {
                    // Utente non loggato, mostra solo il pulsante "Login"
                    echo '<button type="submit" class="dropbtn login-button" onclick="location.href=\'login.php\'">Login</button>';
                }
            ?>
        </header>
    </body>
</html>