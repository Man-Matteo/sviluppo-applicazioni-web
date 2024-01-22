<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/order_storage_style.css">
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=IM+Fell+English">
        <title>Update Rating</title>
    </head>
    <body>
        <?php
            require "functions.php";
            if(!IfLogged()){
                header("Location: ../login.php");
                exit();
            }
            session_set_cookie_params(0);
            session_start();

            $order_id = $_POST['order_id'];
            $rating = $_POST['rating'];
            $userEmail = $_SESSION['username'];
            //check if rating is between 1 and 5
            if($rating < 1 || $rating > 5){
                die("rating must be between 1 and 5");
            }
            $conn = readWriteConnection();
            try{

                $conn -> begin_transaction();

                $ratingQuery = "UPDATE orders SET rating = ? WHERE orderId = ? ";
                $ratingElem = array($rating, $order_id);
                $ratingParams = "ii";
                if (!execStmt($conn, $ratingQuery, $ratingElem, $ratingParams)) {
                    die("something went wrong with the rating query");
                }
            
                $updateRateQuery = "SELECT productId FROM orders WHERE email = ? AND orderId = ?";
                $updateRateElem = array($userEmail,$order_id);
                $updateRateParams = "si";
                $updateRateResult = execStmt($conn, $updateRateQuery, $updateRateElem, $updateRateParams);
                if (!$updateRateResult) 
                    die("something went wrong with the update query");
                
                $conn -> commit();

                while($row = $updateRateResult -> fetch_assoc()){
                    updateRating($row['productId']);
                }


            }catch(Exception $e){
                error_log ("failed to update data in db: " . $e->getMessage() . "/n" , 3, "error.log");
                $conn -> rollback();
            }
            
            $conn->close();
            header("Location: ../orders/order_storage.php");
        ?>
    </body>
</html>