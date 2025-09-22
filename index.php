<?php
//Connect to database
$hostname = "localhost";
$username = "ecpi_user";
$password = "Password1";
$dbname = "store_db";
$conn = mysqli_connect($hostname, $username, $password, $dbname);

//Handle Add to Cart logic
//$_SERVER["REQUEST_METHOD"] == "POST" checks if the form was submitted using POST (not GET)
//$_POST["add"] checks if the "Add to Cart" button was clicked
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add"])) {
    //$_POST grabs form data sent by the user
    //intval() converts it to a safe integer to prevent SQL injection
    $prod_id = intval($_POST["prod_id"]);
    $quantity = intval($_POST["Qty"]);

    if ($quantity > 0) {
        //Check if product is already in the cart
        $check = "SELECT cart_quantity FROM cart_items WHERE prod_id = $prod_id";
        $check_result = mysqli_query($conn, $check);

        if (mysqli_num_rows($check_result) > 0) {
            //Product exists, update quantity
            $row = mysqli_fetch_assoc($check_result);
            $new_quantity = $row["cart_quantity"] + $quantity;
            $update = "UPDATE cart_items SET cart_quantity = $new_quantity WHERE prod_id = $prod_id";
            mysqli_query($conn, $update);
        }
        else {
            //Product not in cart, insert new row aka cart entry
            $insert = "INSERT INTO cart_items (prod_id, cart_quantity) VALUES ($prod_id, $quantity)";
            mysqli_query($conn, $insert);
        }
    }

    //Redirect to avoid resubmission
    header("Location: index.php");
    exit();
}

//Query for all products
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);
?>

<style>
    table {
        border-spacing: 5px;
    }
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
    th, td {
        padding: 15px;
        text-align: center;
    }
    th {
        background-color: lightskyblue;
    }
    tr:nth-child(even) {
        background-color: whitesmoke;
    }
    tr:nth-child(odd) {
        background-color: lightgray;
    }
</style>

<html>
<head>
    <title>Product Catalog</title>
</head>
<body>
    <h1>Product Catalog</h1>

    <table>
        <tr style="font-size: large;">
            <th>Product Name</th>
            <th>Description</th>
            <th>Cost</th>
            <th>Add to Cart</th>
        </tr>
        <?php while($row = mysqli_fetch_array($result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row["prod_name"]); ?></td>
                <td><?php echo htmlspecialchars($row["prod_desc"]); ?></td>
                <td>$<?php echo number_format($row["prod_cost"], 2); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="prod_id" value="<?php echo $row["prod_id"]; ?>">
                        <input type="number" name="Qty" value="1" min="1">
                        <input type="submit" value="Add to Cart" name="add">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <a href="cart.php">Go to Cart</a>
</body>
</html>