<?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header("Location: ../user/login.php");
        exit();
    }
            
    require('functions.php');
    $conn = readWriteConnection();

    $productId = $_GET['productId'];
    $quantity = $_GET['quantity'];

    if (isset($_SESSION['username'])) 
        $userEmail = $_SESSION['username'];
    else 
        $userEmail = session_id();
    

    updateOrAddCart($conn, $userEmail, $productId, $quantity);

    $conn->close();

    header("Location: ../navbar/cart.php");
    exit();
?>