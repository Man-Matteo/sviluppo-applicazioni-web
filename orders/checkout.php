<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Checkout</title>
    </head>
    <body>
        <?php
            session_set_cookie_params(0);
            session_start();
            require('../functions/functions.php');
            $conn = readWriteConnection();

            // Verifica se l'utente è loggato
            if (isset($_SESSION['username'])) 
                $userEmail = $_SESSION['username'];
            else {
                header('Location: ../login.php');
                exit();
            }

            //eseguo una query per contrtollare che il carrello non sia vuoto
            $cartQuery = "SELECT * FROM cart WHERE email = ?";
            $cartParams = "s";
            $cartElem = array($userEmail);
            $cartResult = execStmt($conn, $cartQuery, $cartElem, $cartParams);
            if(!$cartResult)
                die("something went wrong");
            if($cartResult->num_rows == 0)
                die("cart is empty");

            try {
                $conn->begin_transaction();
                // Eseguo la query per ottenere i prodotti nel carrello
                $availabilityCheckQuery = "SELECT p.productId, p.storage, p.price, c.quantity FROM products p JOIN cart c ON p.productId = c.productId WHERE c.email = ?";
                $availabilityCheckElements = array($userEmail);
                $availabilityCheckParam = "s";
                $availabilityCheckResult = execStmt($conn, $availabilityCheckQuery, $availabilityCheckElements, $availabilityCheckParam);
                if(!$availabilityCheckResult)
                    die("something went wrong");
                // Verifico che i prodotti nel carrello siano disponibili in magazzino
                while ($row = $availabilityCheckResult->fetch_assoc()) {
                    $productId = $row['productId'];
                    $orderedQuantity = $row['quantity'];
                    $productPrice = $row['price'];
            
                    // Eseguo l'istruzione di inserimento nell'ordine per il prodotto corrente
                    $updateOrderQuery = "INSERT INTO orders (productId, email, price, quantity) VALUES (?, ?, ?, ?)";
                    $updateOrderElements = array($productId, $userEmail, $productPrice, $orderedQuantity);
                    $updateOrderParams = "isdi";
                    if(!execStmt($conn, $updateOrderQuery, $updateOrderElements, $updateOrderParams))
                        die("something went wrong");
                    
                    // Eseguo l'aggiornamento del magazzino per il prodotto corrente
                    $updateStorageQuery = "UPDATE products SET storage = storage - ? WHERE productId = ?";
                    $updateStorageElements = array($orderedQuantity, $productId);
                    $updateStorageParams = "ii";
                    if(!execStmt($conn, $updateStorageQuery, $updateStorageElements, $updateStorageParams))
                        die("something went wrong");
                }

                // Eseguo l'eliminazione dal carrello
                $deleteCartQuery = "DELETE FROM cart WHERE email = ?";
                $deleteCartElements = array($userEmail);
                $deleteCartParams = "s";
                if(!execStmt($conn, $deleteCartQuery, $deleteCartElements, $deleteCartParams))
                    die("something went wrong");


                // Commit della transazione
                $conn->commit();

                header('Location: ../orders/order_storage.php');
            } catch (Exception $e) {
                // Rollback in caso di errore
                $conn->rollback();
                echo "Error: " . $e->getMessage();
            }
            
            $conn->close()
        ?>
    </body>
</html>
