<?php
    session_start();
    require('functions.php');
    $conn = readWriteConnection();

    $productId = $_GET['productId'];
    $quantity = $_GET['quantity'];
    $userEmail =  $_SESSION['username'];
    //check if quantity is a number and if it is greater than 0
    if (!is_numeric($quantity) || $quantity <= 0) {
        die("Invalid quantity");
    }
    //$userEmail = IfLogged() ? $_SESSION['email'] : session_id();

    updateOrAddCart($conn, $userEmail, $productId, $quantity);

    $conn->close();

    header("Location: ../navbar/cart.php");
    exit();
?>