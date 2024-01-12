<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/index_style.css">
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Bilbo+Swash+Caps">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="logout.js"></script>
        <title>Cancelli di Baldur</title>
        <link rel="shortcut icon" href="Images/DnD-Symbol.png">
    </head>
    <body>
        <header>
            <div class="title">
                <a href="index.php">
                    <img border="0" alt="Title" src="Images/title_white.png">
                </a>
            </div>
            
            <?php
                if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
                    // Utente loggato, mostra il menu completo
                    echo '
                    <div class="dropdown">
                        <button id="myBtn" class="dropbtn">My Account ↓</button>
                        <div id="myDropdown" class="dropdown-content">
                            <a href="logout.php">Logout</a>
                            <a href="show_profile.php">Show profile</a>
                            <a href="cart.php">Cart</a>
                            <a href="crowdfunding.php">Support us</a>
                        </div>
                    </div>';
                } else {
                    // Utente non loggato, mostra solo il pulsante "Login"
                    echo '<button type="submit" class="dropbtn login-button" onclick="location.href=\'login.php\'">Login</button>';
                }
            ?>

            <form class="search" action="search.php">
                <input type="text" placeholder="Search.." name="search">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
            
        </header>

        <div class="container">
            <div class="buttonContainer">
                <button class="menu" onclick="location.href='navbar/Bestiary/bestiary.php'"><img src="Images/bestiary_icon.png" alt="bestiary_icon" style="width:30px">Bestiary</button>
                <button class="menu" onclick="location.href='navbar/Bestseller/bestseller.php'"><img src="Images/bestseller_icon.png" alt="bestseller_icon" style="width:30px">Bestseller</button>
                <button class="menu" onclick="location.href='cart.php'"><img src="Images/icon-cart.png" alt="cart_icon" style="width:30px">Cart</button>
                <button class="menu" onclick="location.href='navbar/Saved/saved.php'"><img src="Images/saved_icon.png" alt="saved_icon" style="width:30px">Saved</button>
                <button class="menu" onclick="location.href='navbar/About_us/about_us.php'"><img src="Images/about_us_icon.png" alt="about_us_icon" style="width:30px">About us</button>
                <button class="menu" onclick="location.href='navbar/Contact_us/contact_us.php'"><img src="Images/contact_us_icon.png" alt="contact_us_icon" style="width:30px">Contact us</button>
            </div>

            <div class="slideshow-container">

                <div class="mySlides fade">
                <img src="Images/Sconti_1.png" alt="primo sconto">
                </div>

                <div class="mySlides fade">
                <img src="Images/Sconti_2.png" alt="secondo sconto">
                </div>

                <div class="mySlides fade">
                <img src="Images/Sconti_3.png" alt="terzo sconto">
                </div>

            </div>
        </div>

        <div class="app-promotion">
            <h2>Download the app</h2>
            <p>Download our app on AppStore and PlayStore</p>
            <a href="https://www.apple.com/app-store/" target="_blank">
                <img src="Images/App_Store_(iOS).svg.png" alt="Download dall'App Store" width="60" height="60">
            </a>
            <a href="https://play.google.com/store/apps?hl=it&gl=US&pli=1" target="_blank">
                <img src="Images/playstore-icon.jpg" alt="Download dal Play Store" width="60" height="60">
            </a>
        </div>

        <script>
            let slideIndex = 0;
            showSlides();

            function showSlides() {
                let i;
                let slides = document.getElementsByClassName("mySlides");
                for (i = 0; i < slides.length; i++) {
                    slides[i].style.display = "none";
                }
                slideIndex++;
                if (slideIndex > slides.length) {slideIndex = 1}
                slides[slideIndex-1].style.display = "block";
                setTimeout(showSlides, 3000);
            }

            /* myFunction toggles between adding and removing the show class, which is used to hide and show the dropdown content */
            document.addEventListener('DOMContentLoaded', function () {
                var dropdown = document.querySelector('.dropdown');

                if(dropdown)
                    dropdown.addEventListener('click', function () {
                        var dropdownContent = document.querySelector('.dropdown-content');
                        dropdownContent.classList.toggle('show');
                });

                window.addEventListener('click', function (event) {
                    if (!event.target.matches('.dropbtn')) {
                    var dropdowns = document.querySelectorAll('.dropdown-content');
                        dropdowns.forEach(function (dropdown) {
                            if (dropdown.classList.contains('show')) {
                                dropdown.classList.remove('show');
                            }
                        });
                    }
                });
            });
        </script> 

        <footer>
            &copy; 2023 Cancelli di Baldur. All rights reserved.
        </footer>
    </body>
</html>