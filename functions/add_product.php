<?php
    session_start();
    require('functions.php');
    $conn = readWriteConnection();

    $productId = $_GET['productId'];
    $quantity = $_GET['quantity'];
    $userEmail =  $_SESSION['email'];
    //$userEmail = IfLogged() ? $_SESSION['email'] : session_id();

    updateOrAddCart($conn, $userEmail, $productId, $quantity);

    $conn->close();

    header("Location: ../navbar/cart.php");
    exit();
?>