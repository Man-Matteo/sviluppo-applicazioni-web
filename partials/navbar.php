<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://saw21.dibris.unige.it/~S4628329/css/navbar.css">
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Bilbo+Swash+Caps">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <title>Navbar</title>
    </head>
    <body class="navbar-body">
        <nav class="navbar">
            <div class="navbar-container">
                <div class="navbar-title">
                    <a href="https://saw21.dibris.unige.it/~S4628329/index.php"><img alt="Title" src="https://saw21.dibris.unige.it/~S4628329/Images/title_white.png"></a>
                </div>

                <form class="navbar-search" action="../navbar/search.php" method="get">
                    <input type="text" placeholder="Search..." name="search">
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
                
                <ul class="navbar-nav-links">
                    <li><a href='../navbar/bestiary.php'>Bestiary</a></li>
                    <li><a href='../navbar/bestseller.php'>Bestseller</a></li>
                    <li><a href='../navbar/cart.php'>Cart</a></li>
                    <li><a href='../show_profile.php'>Profile</a></li>
                    <li><a href='../navbar/contact_us.php'>Contact us</a></li>
                    <li><a href='../crowdfunding/crowdfunding.php'>Support us</a></li>
                </ul>
            </div>
        </nav>
    </body>
</html>
