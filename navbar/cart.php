<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=IM+Fell+English">
        <link rel="stylesheet" href="../css/cart_style.css">
        <title>Cart</title>
    </head>
    <body>
        <?php
            include '../partials/navbar.php';
            session_set_cookie_params(0);
            session_start();
            require('../functions/functions.php');
            $conn = readOnlyConnection();

            $userEmail = isset($_SESSION['username']) ? $_SESSION['username'] : session_id();
            $cartQuery = "SELECT p.name, p.price, c.quantity, c.productId FROM cart c JOIN products p ON c.productId = p.productId WHERE c.email = ?";
            $elements = array($userEmail);
            $paramTypes = "s";
            $cartResult = execStmt($conn, $cartQuery, $elements, $paramTypes);
            if(!$cartResult)
                die("Something went wrong while retrieving cart.");
            

            if ($cartResult->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Product</th><th>Price</th><th>Quantity</th><th>Change quantity</th></tr>";

                $total = 0;

                while ($row = $cartResult->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>€{$row['price']}</td>";
                    echo "<td>{$row['quantity']}</td>";
                    echo "<td><input type='number' id='quantityToRemove_" . $row["productId"] . "' name='quantityToRemove' min='1' max='" . $row['quantity'] . "' value='1' </td>";
                    echo "<button onclick='removeFromCart({$row['productId']}, {$row['quantity']})'>Remove from cart</button>";
                    echo "</tr>";



                    $productTotal = $row['price'] * $row['quantity'];
                    $total += $productTotal;
                }

                echo "</table>";

                echo "<p>Total to pay: €{$total}</p>";
            } else 
                echo "<p>Cart is empty.</p>";
            

            echo '<button type="submit" onclick="location.href=\'bestiary.php\'">Keep shopping</button>';
            echo '<button type="submit" onclick="location.href=\'../index.php\'">Home</button>';
            echo '<button type="submit" onclick="location.href=\'../functions/checkout.php\'">Checkout</button>';

            $conn->close();
            include '../partials/footer.php';

            
        ?>
    
        <script>
            function removeFromCart(productId, cartQuantity) {
                var quantityToRemove = document.getElementById("quantityToRemove_" + productId).value;

                if(cartQuantity < quantityToRemove || quantityToRemove < 1) {
                    alert("Invalid quantity.");
                    return false;
                }
                window.location.href = "../functions/remove_product.php?productId=" + productId + "&quantityToRemove=" + quantityToRemove;
            }
        </script>

    </body>
</html>