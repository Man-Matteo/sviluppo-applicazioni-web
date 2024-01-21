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
            include '../partials/navbar.php';
            require '../functions/functions.php';
            $conn = readOnlyConnection();

            // Query per ottenere i prodotti dalla tabella "prodotti"
            $result = $conn->query("SELECT productId, name, price, description, image, storage, rating FROM products");
            if(!$result)
                die("Something went wrong while retrieving products.");
        ?>

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

        <?php include '../partials/footer.php'; ?>
    </body>
</html>