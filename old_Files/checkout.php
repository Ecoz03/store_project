<?php
//Connect to database
$hostname = "localhost";
$username = "ecpi_user";
$password = "Password1";
$dbname = "store_db";
$conn = mysqli_connect($hostname, $username, $password, $dbname);

//Clear cart contents
$clear = "DELETE FROM cart_items";
mysqli_query($conn, $clear);

//Redirect back to catalog
header("Location: index.php");
exit();
?>

<html>
<head>
    <title>Checkout</title>
</head>
<body>
    <h1>Processing Checkout...</h1>
    <p>If you're seeing this, the redirect didn't work.</p>
    <a href="index.php">Return to Catalog</a>
</body>
</html>