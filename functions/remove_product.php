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
        die('sommething went wrong');

    $row = $selectResult->fetch_assoc();
    if ($row["quantity"] > $quantityToRemove) {
        $updateQuery = "UPDATE cart SET quantity = quantity - ? WHERE email = ? AND productId = ?";
        $updateParams = "isi";
        $updateElem = array($quantityToRemove, $userEmail, $productId);
        if (!execStmt($conn, $updateQuery, $updateElem, $updateParams))
            die('sommething went wrong');
    } else if ($row["quantity"] == $quantityToRemove) {
        $removeQuery = "DELETE FROM cart WHERE email = ? AND productId = ?";
        $removeParams = "si";
        $removeElem = array($userEmail, $productId);
        if (!execStmt($conn, $removeQuery, $removeElem, $removeParams))
            die('sommething went wrong');
    } else
        die('You are trying to remove more products than you have in your cart.');

    $conn->close();

    header("Location: cart.php");
    exit();
?>