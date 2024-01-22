<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style_order_storage.css">
        <link rel="stylesheet" href="../css/style_rating.css">
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=IM+Fell+English">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <title>Update Rating</title>
    </head>
    <body>
        <?php
            include '../partials/navbar.php';
            require '../functions/functions.php';
            if(!IfLogged()){
                header("Location: ../login.php");
                exit();
            }
            $order_id = $_GET['orderId'];
        ?>
        <h1>Rate your product from 1 to 5 stars</h1><br>
        <form action='../functions/rating.php' method='post'>
            <input type='hidden' id='order_id' name='order_id' value='<?php echo $order_id; ?>'><br>
            <label for='rating'>Rating:</label>
            <div class="star-rating">
                <div class="star-rating__wrap">
                <input class="star-rating__input" id="star-rating-5" type="radio" name="rating" value="5">
                <label class="star-rating__ico fa fa-star-o fa-lg" for="star-rating-5" title="5 out of 5 stars"></label>
                <input class="star-rating__input" id="star-rating-4" type="radio" name="rating" value="4">
                <label class="star-rating__ico fa fa-star-o fa-lg" for="star-rating-4" title="4 out of 5 stars"></label>
                <input class="star-rating__input" id="star-rating-3" type="radio" name="rating" value="3">
                <label class="star-rating__ico fa fa-star-o fa-lg" for="star-rating-3" title="3 out of 5 stars"></label>
                <input class="star-rating__input" id="star-rating-2" type="radio" name="rating" value="2">
                <label class="star-rating__ico fa fa-star-o fa-lg" for="star-rating-2" title="2 out of 5 stars"></label>
                <input class="star-rating__input" id="star-rating-1" type="radio" name="rating" value="1">
                <label class="star-rating__ico fa fa-star-o fa-lg" for="star-rating-1" title="1 out of 5 stars"></label>
                </div>
                <br>
            </div>
            <button type='submit' value='submit'>Rate</button>
        </form>
        <br>
        <button class="menu" onclick="location.href='order_storage.php'">Go back to order history</button>
        <?php include '../partials/footer.php'; ?>
    </body>
</html>