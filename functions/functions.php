<?php
    function updateOrAddCart($conn, $userEmail, $productId, $quantity) {

        if(!IfLogged()){
            echo "You must have to Sign Up or Sign In to add Product to the Cart!!!!";
            header("Location: ../user/login.php");
            exit();
        }
        //recupero il carrello dell'utente
        $selectQuery = "SELECT * FROM cart WHERE email = ? AND productId = ?";
        $selectParams = "si";
        $selectElem = array($userEmail, $productId);
        $selectResult = execStmt($conn, $selectQuery, $selectElem, $selectParams);
        if(!$selectResult)
            die("something went wrong");

        //controllo se ci sono abbastanza prodotti in magazzino
        $storageQuery = "SELECT storage FROM products WHERE productId = ?";
        $storageParams = "i";
        $storageElem = array($productId);
        $storageResult = execStmt($conn, $storageQuery, $storageElem, $storageParams);
        if(!$storageResult)
            die("something went wrong");
        $row = $storageResult->fetch_assoc();
        if($row["storage"] < $quantity)
            die("not enough products in storage");
     
        //aggiorno il carrello nel caso in cui il prodotto sia già presente oppure lo aggiungo
        if ($selectResult->num_rows > 0) {
            $updateQuery = "UPDATE cart SET quantity = quantity + ? WHERE email = ? AND productId = ?";
            $updateParams = "isi";
            $updateElem = array($quantity, $userEmail, $productId);
            if(!execStmt($conn, $updateQuery, $updateElem, $updateParams))
                die("something went wrong");
        } 
        else {
            $insertQuery = "INSERT INTO cart (email, productId, quantity) VALUES (?, ?, ?)";
            $insertParams = "sii";
            $insertElem = array($userEmail, $productId, $quantity);
            if (!execStmt($conn, $insertQuery, $insertElem, $insertParams))
                die("something went wrong");
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
            die("something went wrong");
        $row = $rateResult->fetch_assoc();
        $avg_rating = $row['avg_rating'];
        echo $avg_rating;

        $newRateQuery = "UPDATE products SET rating = ? WHERE productId = ?";
        $newRateParams = "ii";
        $newRateElem = array($avg_rating, $product_id);
        $newRateResult = execStmt($conn, $newRateQuery, $newRateElem, $newRateParams);
        if(!$newRateResult && $newRateResult != 0){
            echo $newRateResult;
            die("something went wrong");
        }
    }
    function IfLogged(){
        session_start();
        return isset($_SESSION['username']);
    }

    function clean_input($data) {
        return htmlspecialchars($data);
    }
?>