<?php
    session_start();
    require('functions.php');
    if (!isset($_SESSION['username'])) {
        echo "<p>You need to be logged in to make a donation.</p>";
        exit();
    }

    // Pulizia e validazione degli input
    $firstname = clean_input($_POST['firstname']);
    $lastname = clean_input($_POST['lastname']);
    $credit_card_number = clean_input($_POST['credit_card_number']);
    $donation_amount = clean_input($_POST['donation_amount']);

  
    if (!is_numeric($donation_amount) || $donation_amount <= 0) {
        echo "<p>Donation amount not valid.</p>";
        exit();
    }

    
    $conn = readWriteConnection();

    $username = $_SESSION['username'];
    $insertDonationQuery = "INSERT INTO crowdfunding (email, firstname, lastname, donation_amount) VALUES (?, ?, ?, ?)";
    $insertDonationParams = "sssi";
    $insertDonationElements = array($username, $firstname, $lastname, $donation_amount);
    $insertDonationResult = execStmt($conn, $insertDonationQuery, $insertDonationElements, $insertDonationParams);
    if (!$insertDonationResult)
        die("Error in donation query");
   
    header("Location: crowdfunding.php");
    exit();

    // Funzione per pulire e validare l'input
    function clean_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>