<?php
    session_set_cookie_params(0);
    session_start();

    require 'functions.php';
    $conn = readWriteConnection();

    $productId = $_GET['productId'];
    $quantityToRemove = $_GET['quantityToRemove'];

    if (isset($_SESSION['username'])) 
        $userEmail = $_SESSION['username'];
    else 
        $userEmail = session_id();
    

    $selectQuery = "SELECT quantity FROM cart WHERE email = ? AND productId = ?";
    $selectParams = "si";
    $selectElem = array($userEmail, $productId);
    $selectResult = execStmt($conn, $selectQuery, $selectElem, $selectParams);
    if (!$selectResult)
        die('Error in select query.');

    $row = $selectResult->fetch_assoc();
    if ($row["quantity"] > $quantityToRemove) {
        $updateQuery = "UPDATE cart SET quantity = quantity - ? WHERE email = ? AND productId = ?";
        $updateParams = "isi";
        $updateElem = array($quantityToRemove, $userEmail, $productId);
        $updateResult = execStmt($conn, $updateQuery, $updateElem, $updateParams);
        if (!$updateResult)
            die('Error in update query.');
    } else if ($row["quantity"] == $quantityToRemove) {
        $removeQuery = "DELETE FROM cart WHERE email = ? AND productId = ?";
        $removeParams = "si";
        $removeElem = array($userEmail, $productId);
        $removeResult = execStmt($conn, $removeQuery, $removeElem, $removeParams);
        if (!$removeResult)
            die('Error in remove query.');
    } else
        die('You are trying to remove more products than you have in your cart.');

    $conn->close();

    header("Location: cart.php");
    exit();
?>