<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=IM Fell English">
        <link rel="stylesheet" href="css/order_storage_style.css">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="logout.js"></script>
        <title>Search results</title>
    </head>
        <?php
            $search_query = $_GET['search'];

            require 'functions.php';
            $conn = readOnlyConnection();

            // Search the products in the database
            $searchQuery = "SELECT p.productId, p.name, p.price, p.description, p.image FROM products p  WHERE p.name LIKE ?";
            $searchElem = array("%" . $search_query . "%");
            $searchParam = "s";
            $searchResult = execStmt($conn, $searchQuery, $searchElem, $searchParam);
            if(!$searchResult)
                die("error in search query");

            if ($searchResult->num_rows > 0) {
                echo '<link rel="stylesheet" href="css/style_order_storage.css">';
                    echo "<table>";
                    echo "<tr><th>Name</th><th>Price</th><th>Description</th><th>Image</th><th>Select quantity</th></tr>";
                while($row = $searchResult->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>{$row['price']}</td>";
                    echo "<td>{$row['description']}</td>";
                    echo "<td><img src='{$row['image']}' width='100' height='100'></td>";
                    echo '<td><input type="number" id="quantity_' . $row["productId"] . '" name="quantity" min="1" max="100" value="1"></td>';
                    echo "<td><button onclick='addToCart({$row['productId']})'>Add to cart</button></td>";
                    echo "</tr>";
                
                }
                echo "</table>";
            } else 
                echo "No results found";
                
            $conn->close();
        ?>

        <script>
            function addToCart(productId) {
                var quantity = document.getElementById("quantity_" + productId).value;
                window.location.href = "add_product.php?productId=" + productId + "&quantity=" + quantity;
            }
        </script>
    </body>
</html>