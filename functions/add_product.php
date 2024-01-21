<?php
    //non posso controllare che l'utente sia loggato o meno perchè sennò quando aggiungo un prodotto al carrello da non loggato mi manda al login     
    require('functions.php');
    $conn = readWriteConnection();

    $productId = $_GET['productId'];
    $quantity = $_GET['quantity'];
    //if user logged in use email as id, else use session id
    $userEmail = IfLogged() ? $_SESSION['email'] : session_id();

    updateOrAddCart($conn, $userEmail, $productId, $quantity);

    $conn->close();

    header("Location: ../navbar/cart.php");
    exit();
?>