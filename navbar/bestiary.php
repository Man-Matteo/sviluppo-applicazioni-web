<!DOCTYPE html>
<html lang="en">
    <head>
        <title> Bestiary </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=IM+Fell+English">
        <link rel="stylesheet" href="../css/bestiary_style.css">
        <link rel="stylesheet" href="../css/navbar.css">
        <link rel="stylesheet" href="../css/footer.css">
        <script src="../functions/functions.js"></script>
    </head>
    <body>
        <?php
            include '../html/navbar.html';
            require '../functions/functions.php';
            $conn = readOnlyConnection();

            // Query per ottenere i prodotti dalla tabella "prodotti"
            $result = $conn->query("SELECT productId, name, price, description, image, storage, rating FROM products");
            if(!$result)
                die("Something went wrong while retrieving products.");
        ?>

        <!-- Sidebar (nascosta di default) -->
        <nav class="w3-sidebar w3-bar-block w3-card w3-top w3-xlarge w3-animate-left" style="display:none; z-index:2; width:20%; min-width:300px; border-radius:10px" id="mySidebar">
            <a href="javascript:void(0)" onclick="w3_close()" class="w3-bar-item w3-button">Close menu</a>
            <a href="../index.php" onclick="w3_close()" class="w3-bar-item w3-button">Home</a>
            <a href="cart.php" onclick="w3_close()" class="w3-bar-item w3-button">Cart</a>
            <a href="contact_us.php" onclick="w3_close()" class="w3-bar-item w3-button">Contact us</a>
        </nav>

        <!-- Top menu -->
        <div class="w3-top">
            <div class="w3-white w3-xlarge" style="max-width:1200px; margin:auto; border-radius: 10px; margin-top: 5px; box-shadow: 10px 5px 8px black; opacity: .8;">
                <div class="w3-button w3-padding-16 w3-left" style="border-radius: 10px;" onclick="w3_open()">☰</div>
                <div class="w3-center w3-padding-16">Bestiary</div>
            </div>
        </div>


        <!-- !PAGE CONTENT! -->
        <div class="w3-main w3-content w3-padding" style="max-width:1200px;margin-top:100px">

            <!-- Prima Griglia -->
            <div class="w3-row-padding w3-padding-16 w3-center" id="monsters">
                <?php
                    // Stampa i prodotti
                    
                
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="w3-quarter" style="margin: 5px;">';
                        echo "<img src='../{$row["image"]}' alt='{$row["name"]}' style='width:100%; border: 3px solid #000; border-radius: 10px; box-shadow: 10px 5px 8px black;'>";
                        echo "<h3 style='font-size: 40px; text-shadow: 2px 2px white;'>{$row['name']}</h3>";
                        echo "<p style='color:black; font-size: 30px; background-color: #FFA500; border-radius: 5px;'>Price: €{$row['price']}</p>";
                        echo "<p style='color:black; font-size: 20px; background-color: #FFA500; border-radius: 5px;'>Availability: {$row['storage']}</p>";
                        echo "<p style='color:black; font-size: 20px; background-color: #d4af37; border-radius: 5px;'>{$row['description']}</p>";
                        echo "<p style='color:black; font-size: 30px; background-color: #d4af37; border-radius: 5px;'>Rating: {$row['rating']}</p>";
                        echo '<input type="number" id="quantity_' . $row["productId"] . '" name="quantity" min="1" max="' . $row['storage'] . '" value="1" style="font-size: 20px;">';                        
                        echo "<button onclick='addToCart({$row['productId']}, {$row['storage']})'>Add to cart</button>";
                        echo '</div>';
                    }
                ?>
            </div>
        </div>

        <!-- Footer -->
        <?php include '../html/footer.html'; ?>
                    
        <script>
            // Script to open and close sidebar
            function w3_open() {
                document.getElementById("mySidebar").style.display = "block";
            }

            function w3_close() {
                document.getElementById("mySidebar").style.display = "none";
            }
        </script>
    </body>
</html>