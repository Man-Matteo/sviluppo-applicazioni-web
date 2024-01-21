<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=IM+Fell+English">
        <link rel="stylesheet" href="../css/bestseller_style.css">
        <script src="../functions/functions.js"></script>
        <link rel="stylesheet" href="../css/navbar.css">
        <link rel="stylesheet" href="../css/footer.css">
        <title>Bestsellers</title>
    </head>
        <?php
            include '../partials/navbar.php';
            require '../functions/functions.php';
            $conn = readOnlyConnection();

            // Search the products in the database
            $sql = "SELECT p.productId, p.name, p.price, p.description, p.storage, p.image, MAX(p.rating)
                    FROM products p 
                    WHERE (SELECT COUNT(*)
                                            FROM orders o
                                            WHERE o.productId = p.productId) > 2";

            $result = $conn->query($sql);
            if(!$result)
                die("Something went wrong while retrieving products.");

            // If there are results, output them
            if ($result->num_rows > 1) {
                    echo "<table>";
                    echo "<tr><th>Name</th><th>Price</th><th>Description</th><th>Image</th><th>Select quantity</th><th></th></tr>";
                while($row = $result->fetch_assoc()) {
                    
                    echo "<tr>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>{$row['price']}</td>";
                    echo "<td>{$row['description']}</td>";
                    echo "<td><img src='../{$row['image']}' width='100' height='100' alt='bestseller product'></td>";
                    echo '<td><input type="number" id="quantity_' . $row["productId"] . '" name="quantity" min="1" max="100" value="1"></td>';
                    echo "<td><button onclick='addToCart({$row['productId']}, {$row['storage']})'>Add to cart</button></td>";
                    echo "</tr>";
                    echo "<p hidden id='storage_{$row['productId']}'>{$row['storage']}</p>";
                }
                echo "</table>";         
            } else {
                echo "No results found";
                echo "<br>";
                echo "<a href='../index.php'>Torna alla home</a>";
            }

            $conn->close();
            include '../partials/footer.php';
        ?>
    </body>
</html>