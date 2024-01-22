<?php
    session_set_cookie_params(0);
    session_start();
    if(!(isset($_SESSION['logged_in'])) || !($_SESSION['logged_in'])){
        die("You can't logout because you are not logged in");
    }

    $_SESSION = array();
    session_unset();
    session_destroy();
    echo json_encode(["status" => "success"]);
    header('Location: ../index.php');
    exit();
?>


