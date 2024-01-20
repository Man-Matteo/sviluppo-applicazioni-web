<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="logout.js"></script>
        <title>Checkout</title>
    </head>
    <body>
        <?php
            session_start();
            require('functions.php');
            $conn = readWriteConnection();

            // Verifica se l'utente è loggato
            if (isset($_SESSION['username'])) 
                $userEmail = $_SESSION['username'];
            else
                header('Location: http://localhost/login.php');
                exit();
            try {
                $conn->begin_transaction();
            
                // Controllo disponibilità prodotti in magazzino
                $availabilityCheckQuery = "SELECT p.productId, p.storage, p.price, c.quantity FROM products p JOIN cart c ON p.productId = c.productId WHERE c.email = ?";
                $availabilityCheckElements = array($userEmail);
                $availabilityCheckParam = "s";
                $availabilityCheckResult = execStmt($conn, $availabilityCheckQuery, $availabilityCheckElements, $availabilityCheckParam);
                //controllo valore di ritorno

                $availability = true;
                
                while ($row = $availabilityCheckResult->fetch_assoc()) {
                    $productId = $row['productId'];
                    $orderedQuantity = $row['quantity'];
            
                    // Verifica se ci sono abbastanza prodotti disponibili
                    if ($row['storage'] < $orderedQuantity) {
                        $availability = false;
                        break;
                    }
                    $productPrice = $row['price'];
            
                    // Esegui l'eliminazione dal carrello del prodotto corrente
                    $deleteCartQuery = "DELETE FROM cart WHERE email = ? AND productId = ?";
                    $deleteCartElements = array($userEmail, $productId);
                    $deleteCartParams = "si";
                    $deleteCartResult = execStmt($conn, $deleteCartQuery, $deleteCartElements, $deleteCartParams);
                    //controllo valore di ritorno
            
                    // Esegui l'istruzione di inserimento nell'ordine per il prodotto corrente
                    $updateOrderQuery = "INSERT INTO orders (productId, email, price, quantity) VALUES (?, ?, ?, ?)";
                    $updateOrderElements = array($productId, $userEmail, $productPrice, $orderedQuantity);
                    $updateOrderParams = "isdi";
                    $updateOrderResult = execStmt($conn, $updateOrderQuery, $updateOrderElements, $updateOrderParams);
                    //controllo valore di ritorno
                    
                    // Esegui l'aggiornamento del magazzino per il prodotto corrente
                    $updateStorageQuery = "UPDATE products SET storage = storage - ? WHERE productId = ?";
                    $updateStorageElements = array($orderedQuantity, $productId);
                    $updateStorageParams = "ii";
                    $updateStorageResult = execStmt($conn, $updateStorageQuery, $updateStorageElements, $updateStorageParams);
                    //controllo valore di ritorno
                }
            
                // Commit della transazione
                $conn->commit();
            
                if (!$availability) {
                    echo "<p>Error: Not enough products to complete the order.</p>";
                    echo '<button type="submit" onclick="location.href=\'cart.php\'">Cart</button>';
                    exit();
                }
            
                echo "<p>Order completed successfully!</p>";
                echo '<button type="submit" onclick="location.href=\'cart.php\'">Cart</button>';
            } catch (Exception $e) {
                // Rollback in caso di errore
                $conn->rollback();
                echo "Error: " . $e->getMessage();
            }
            
            $conn->close()
        ?>
    </body>
</html>
