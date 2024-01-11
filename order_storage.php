<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=IM Fell English">
        <link rel="stylesheet" href="css/order_storage_style.css">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="logout.js"></script>
        <title>Order history</title>
    </head>
    <body>
        <?php
            session_start();
            require('functions.php');
            if (isset($_SESSION['username'])) 
                $userEmail = $_SESSION['username'];
            else 
                header('Location: login.php');

            
            $conn = readOnlyConnection();
            
            $orderQuery = "SELECT o.orderId, o.productId, p.name, o.email, o.price, o.quantity, o.date, o.rating FROM orders o JOIN products p ON o.productId = p.productId WHERE o.email = ?";
            $orderElem = array($userEmail);
            $orderParams = "s";
            $orderResult = execStmt($conn, $orderQuery, $orderElem, $orderParams);
            
            if($orderResult -> num_rows == 0){
                echo "<p> You haven't placed any orders yet </p>";
                $conn->close();
                exit();
            }

            try{
                echo "<table align='center' border='5' width='400' height='150' bordercolor='grey'>";
                echo "<tr><th>Order number</th><th>Product name</th><th>Price</th><th>Quantity</th><th>Date</th><th>Rating</th></tr>";
                while ($row = $orderResult->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['orderId']}</td>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>{$row['price']}</td>";
                    echo "<td>{$row['quantity']}</td>";
                    echo "<td>{$row['date']}</td>";
                    echo "<td>{$row['rating']}</td>";
                    echo "<td><button type='button' id='get_eval' onclick='location.href=\"update_rating.php?orderId={$row['orderId']}\"'>Rate your products</button></td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            catch (Exception $e) {
                echo "Profile data not found";   
            }
            $conn->close();
            echo "<br>";
            echo "<td><button type='button' onclick='location.href=\"show_profile.php\"'>Go back to profile</button>";
            echo "";
        ?>
    </body>
</html>