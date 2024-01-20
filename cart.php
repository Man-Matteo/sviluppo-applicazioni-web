<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=IM+Fell+English">
        <link rel="stylesheet" href="css/cart_style.css">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="logout.js"></script>
        <title>Cart</title>
    </head>
    <body>
        <?php
            session_start();
            require('functions.php');
            $conn = readOnlyConnection();

            $userEmail = isset($_SESSION['username']) ? $_SESSION['username'] : session_id();
            $cartQuery = "SELECT p.name, p.price, c.quantity, c.productId FROM cart c JOIN products p ON c.productId = p.productId WHERE c.email = ?";
            $elements = array($userEmail);
            $paramTypes = "s";
            $cartResult = execStmt($conn, $cartQuery, $elements, $paramTypes);
            if(!$cartResult)
                die("error in cart query");
            

            if ($cartResult && $cartResult->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Action</th></tr>";

                $total = 0;

                while ($row = $cartResult->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>€{$row['price']}</td>";
                    echo "<td>{$row['quantity']}</td>";
                    echo "<td><button onclick='removeFromCart({$row['productId']})'>Remove from cart</button></td>";
                    echo "</tr>";

                    $productTotal = $row['price'] * $row['quantity'];
                    $total += $productTotal;
                }

                echo "</table>";

                echo "<p>Total to pay: €{$total}</p>";
            } else 
                echo "<p>Cart is empty.</p>";
            

            echo '<button type="submit" onclick="location.href=\'navbar/Bestiary/bestiary.php\'">Keep shopping</button>';
            echo '<button type="submit" onclick="location.href=\'index.php\'">Home</button>';
            echo '<button type="submit" onclick="location.href=\'checkout.php\'">Checkout</button>';

            $conn->close() 
        ?>
    
        <script>
            function removeFromCart(productId) {
                window.location.href = "remove_product.php?productId=" + productId;
            }
        </script>

    </body>
</html>