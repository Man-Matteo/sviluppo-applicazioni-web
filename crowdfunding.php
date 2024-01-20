<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/crowdfunding_style.css">
    <title>Crowdfunding</title>
</head>

<body>
    <?php
    session_set_cookie_params(0);
    session_start();
    require('functions.php');

    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();

    } else {
        $conn = readOnlyConnection();

        $totalAmountResult = $conn->query("SELECT SUM(donation_amount) AS total_amount FROM crowdfunding");
        if (!$totalAmountResult)
            die("Error in total amount query");
        $totalAmountRow = $totalAmountResult->fetch_assoc();
        $total = $totalAmountRow['total_amount'];

        $target = 10000;
        $achievedPercentage = ($total / $target) * 100;

        $conn->close();
    }
    ?>
    <div id="progress-bar">
        <div id="progress" style="width: <?php echo htmlspecialchars($achievedPercentage); ?>%"><?php echo "$" . number_format($total, 2); ?></div>
    </div>

    <?php
    if ($total >= $target) {
        echo "<p id='goal-reached-message'>Congratulations! The donation goal has been reached.</p>";
    } else {
    ?>
        <div id="donation-form">
            <h2>Donation</h2>
            <form id="crowdfunding-form" action="crowdfunding_process.php" method="post">
                <label for="firstname">Firstname:</label>
                <input type="text" id="firstname" name="firstname" required><br>

                <label for="lastname">Lastname:</label>
                <input type="text" id="lastname" name="lastname" required><br>

                <label for="credit_card_number">Credit card number:</label>
                <input type="text" id="credit_card_number" name="credit_card_number" required><br>

                <label for="donation_amount">Donation amount:</label>
                <input type="number" id="donation_amount" name="donation_amount" required oninput="checkDonationAmount(this)"><br>

                <button type="submit" id="donateButton">Donate</button>
            </form>
        </div>
    <?php } ?>

    <script>
        // Aggiorna la larghezza della barra di avanzamento al caricamento della pagina
        document.addEventListener('DOMContentLoaded', function() {
            updateProgressBarWidth();
        });

        // Aggiorna la larghezza della barra di avanzamento quando la finestra viene ridimensionata
        window.addEventListener('resize', function() {
            updateProgressBarWidth();
        });

        // Funzione per aggiornare la larghezza della barra di avanzamento
        function updateProgressBarWidth() {
            var progress = document.getElementById('progress');
            var achievedPercentage = parseFloat(progress.style.width);
            progress.style.width = achievedPercentage + '%';
        }

        function checkDonationAmount(input) {
            var maxDonation = 10000; 

            var donationAmount = parseFloat(input.value);

            if (isNaN(donationAmount) || donationAmount < 0) 
                alert("Please enter a valid positive donation amount.");
            else if (donationAmount > maxDonation) {
                alert("Maximum donation amount exceeded. The maximum allowed is $" + maxDonation + ".");
                input.value = maxDonation;
            } else if (donationAmount + <?php echo $total; ?> > maxDonation) {
                alert("Exceeding the maximum threshold.");
                input.value = maxDonation - <?php echo $total; ?>; // Imposta l'importo massimo consentito
            }
        }
    </script>
</body>

</html>
