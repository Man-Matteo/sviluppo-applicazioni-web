<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Contact us</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=IM+Fell+English">
        <link rel="stylesheet" href="../css/contact_us_style.css">

    </head>
    <body>
        <?php include '../navbar.html'; ?>
        <h1>Contact us</h1>
        <p>Contattaci compilando il form qui sotto</p>
        
        <form action="process_contact.php" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br><br>
            
            <label for="message">Message:</label><br>
            <textarea id="message" name="message" rows="5" required></textarea><br><br>
            
            <input type="submit" value="Submit">
        </form>
        <?php include '../footer.html'; ?>
    </body>
</html>