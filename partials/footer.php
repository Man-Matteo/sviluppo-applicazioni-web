<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/footer.css">
    <title>Footer</title>
</head>
<body>
    <?php
        if (!isset($_SESSION['username'])) {
            header("Location: ../user/login.php");
            exit();
    }
    ?>
    <footer>
        &copy; 2023 Cancelli di Baldur. All rights reserved.
    </footer>
</body>
</html>
