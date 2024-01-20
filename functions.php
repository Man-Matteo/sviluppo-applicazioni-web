<?php
    function updateOrAddCart($conn, $userEmail, $productId, $quantity) {

        $selectQuery = "SELECT * FROM cart WHERE email = ? AND productId = ?";
        $selectParams = "si";
        $selectElem = array($userEmail, $productId);
        $selectResult = execStmt($conn, $selectQuery, $selectElem, $selectParams);
        if(!$selectResult)
            die("error in select query");
    
        if ($selectResult->num_rows > 0) {
            $updateQuery = "UPDATE cart SET quantity = quantity + ? WHERE email = ? AND productId = ?";
            $updateParams = "isi";
            $updateElem = array($quantity, $userEmail, $productId);
            $updateResult = execStmt($conn, $updateQuery, $updateElem, $updateParams);
            if(!$updateResult)
                die("error in update query");
        } 
        else {
            $insertQuery = "INSERT INTO cart (email, productId, quantity) VALUES (?, ?, ?)";
            $insertParams = "sii";
            $insertElem = array($userEmail, $productId, $quantity);
            $insertResult = execStmt($conn, $insertQuery, $insertElem, $insertParams);
            if (!$insertResult)
                die("error in insert query");
            /*
            if (!$insertResult->affected_rows) {
                die("Error in updating cart: " . $insertResult->error);
            }
            */
        }
    }

    function readOnlyConnection() {
        $host = 'localhost';
        $username = 'read_only';
        $password = 'read_only';
        $database = 'baldurdb';
    
        $conn = new mysqli($host, $username, $password, $database);
    
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    
        $conn->set_charset('utf8mb4');
    
        return $conn;
    }

    function readWriteConnection() {
        $host = 'localhost';
        $username = 'read_write';
        $password = 'read_write';
        $database = 'baldurdb';
    
        $conn = new mysqli($host, $username, $password, $database);
    
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    
        $conn->set_charset('utf8mb4');
    
        return $conn;
    }

    function execStmt($conn, $sql, $elements=[], $paramTypes=""){

        if(!($stmt = $conn->prepare($sql))) {
            echo $conn->error;
            return(false);
        }

        array_unshift($elements,$paramTypes);
        for ($i=0; $i < count($elements); $i++) { 
            $elements[$i] =& $elements[$i];
        }
        if(!(call_user_func_array(array($stmt,'bind_param'),$elements))) {
            echo $conn->error;
            return(false);
        }

        if(!($stmt->execute())) {
            echo $conn->error;
            return(false);
        }

        if(substr($sql,0,6) === "SELECT")
            $result = $stmt->get_result();
        else
            $result = $stmt->affected_rows;

        $stmt->close();
        return $result;
    }

    function updateRating($product_id) {
        $conn = readWriteConnection();

        $rateQuery = "SELECT AVG(rating) as avg_rating FROM orders WHERE productId = ?";
        $rateParams = "i";
        $rateElem = array($product_id);
        $rateResult = execStmt($conn, $rateQuery, $rateElem, $rateParams);
        if(!$rateResult)
            die("error in averagerate query");
        $row = $rateResult->fetch_assoc();
        $avg_rating = $row['avg_rating'];

        $newRateQuery = "UPDATE products SET rating = ? WHERE productId = ?";
        $newRateParams = "ii";
        $newRateElem = array($avg_rating, $product_id);
        $newRateResult = execStmt($conn, $newRateQuery, $newRateElem, $newRateParams);
        if(!$newRateResult){
            echo $newRateResult;
            die("error in update rate query");
        }
    }

<<<<<<< HEAD
    function IfLogged(){
        session_start();
        if (isset($_SESSION['username'])) {
            return true;
        }
=======
    function clean_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
>>>>>>> 1c3d79a6e4306840c6abe09eefd4f5d5030169f9
    }
?>