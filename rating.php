<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/order_storage_style.css">
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=IM Fell English">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="logout.js"></script>
        <title>Update Rating</title>
    </head>
    <body>
        <?php
            session_start();
            require "functions.php";
            $conn = readWriteConnection();

            $order_id = $_POST['order_id'];
            $rating = $_POST['rating'];
            $userEmail = $_SESSION['username'];

            try{

                $conn -> begin_transaction();

                $ratingQuery = "UPDATE orders SET rating = ? WHERE orderId = ? ";
                $ratingElem = array($rating, $order_id);
                $ratingParams = "ii";
                $ratingResult = execStmt($conn, $ratingQuery, $ratingElem, $ratingParams);

                if (!$ratingResult) {
                    die("Error in rating query 1");
                }else {
                    echo "Thank You for your vote!!!";
                }
            
                $updateRateQuery = "SELECT productId FROM orders WHERE email = ? AND orderId = ?";
                $updateRateElem = array($userEmail,$order_id);
                $updateRateParams = "si";
                $updateRateResult = execStmt($conn, $updateRateQuery, $updateRateElem, $updateRateParams);

                $conn -> commit();
            
                if (!$updateRateResult) 
                    die("Error in rating query 2");
            
                while($row = $updateRateResult -> fetch_assoc()){
                    updateRating($row['productId']);
                }
            }catch(Exception $e){
                $conn -> rollback();
                echo "Error in rating query 3";
            }

            
            $conn->close();
        ?>
        <br>
        <a href="order_storage.php"><button type='submit'>Go back to order history</button></a>
    </body>
</html>