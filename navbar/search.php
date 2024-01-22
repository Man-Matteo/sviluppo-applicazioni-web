<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=IM+Fell+English">
        <link rel="stylesheet" href="../css/order_storage_style.css">
        <script src="../functions/functions.js"></script>
        <title>Search results</title>
    </head>
        <?php
            include '../partials/navbar.php';
            require '../functions/functions.php';
            $search_query = clean_input($_GET['search']);
            $conn = readOnlyConnection();

            // Search the products in the database
            $searchQuery = "SELECT p.productId, p.name, p.price, p.description, p.image, p.storage FROM products p  WHERE p.name LIKE ?";
            $searchElem = array("%" . $search_query . "%");
            $searchParam = "s";
            $searchResult = execStmt($conn, $searchQuery, $searchElem, $searchParam);
            if(!$searchResult)
                die("something went wrong");

            if ($searchResult->num_rows > 0) {
                    echo "<table>";
                    echo "<tr><th>Name</th><th>Price</th><th>Description</th><th>Image</th><th>Select quantity</th><th></th></tr>";
                while($row = $searchResult->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>{$row['price']}</td>";
                    echo "<td>{$row['description']}</td>";
                    echo "<td><img src='../{$row['image']}' width='100' height='100' alt='search result image'></td>";
                    echo '<td><input type="number" id="quantity_' . $row["productId"] . '" name="quantity" min="1" max="100" value="1"></td>';
                    echo "<td><button onclick='addToCart({$row['productId']}, {$row['storage']})'>Add to cart</button></td>";
                    echo "</tr>";
                
                }
                echo "</table>";
            } else 
                echo "No results found";
                
            $conn->close();
            include '../partials/footer.php';
        ?>

    </body>
</html>