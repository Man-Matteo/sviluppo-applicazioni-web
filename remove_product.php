<?php
    session_start();

    require 'functions.php';
    $conn = readWriteConnection();

    $productId = $_GET['productId'];

    if (isset($_SESSION['username'])) 
        $userEmail = $_SESSION['username'];
    else 
        $userEmail = session_id();
    

    $removeQuery = "DELETE FROM cart WHERE email = ? AND productId = ?";
    $removeParams = "si";
    $removeElem = array($userEmail, $productId);
    $removeResult = execStmt($conn, $removeQuery, $removeElem, $removeParams);
    if (!$removeResult)
        die('Error in remove query.');
    $conn->close();

    header("Location: cart.php");
    exit();
?>