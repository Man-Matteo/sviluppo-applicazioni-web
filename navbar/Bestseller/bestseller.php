<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=IM+Fell+English">
        <link rel="stylesheet" href="../../css/bestseller.css">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="../../logout.js"></script>
        <script src="../../functions.js"></script>
        <title>Bestsellers</title>
    </head>
        <?php        
            require '../../functions.php';
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

            if ($result->num_rows > 0) {
                // Output the products
                    echo "<table>";
                    echo "<tr><th>Name</th><th>Price</th><th>Description</th><th>Image</th><th>Select quantity</th><th></th></tr>";
                while($row = $result->fetch_assoc()) {
                    
                    echo "<tr>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>{$row['price']}</td>";
                    echo "<td>{$row['description']}</td>";
                    echo "<td><img src='../../{$row['image']}' width='100' height='100' alt='bestseller product'></td>";
                    echo '<td><input type="number" id="quantity_' . $row["productId"] . '" name="quantity" min="1" max="100" value="1"></td>';
                    echo "<td><button onclick='addToCart({$row['productId']}, {$row['storage']})'>Add to cart</button></td>";
                    echo "</tr>";
                    echo "<p hidden id='storage_{$row['productId']}'>{$row['storage']}</p>";
                }
                echo "</table>";
            } else {
                echo "No results found";
            }

            $conn->close();
        ?>
    </body>
</html>