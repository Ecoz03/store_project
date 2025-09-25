<?php
//Connect to database
$hostname = "localhost";
$username = "ecpi_user";
$password = "Password1";
$dbname = "store_db";
$conn = mysqli_connect($hostname, $username, $password, $dbname);

//Handle quantity update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $entry_id = intval($_POST["entry_id"]);
    $new_quantity = intval($_POST["cart_quantity"]);

    if ($new_quantity > 0) {
        $update = "UPDATE cart_items SET cart_quantity = $new_quantity WHERE entry_id = $entry_id";
        mysqli_query($conn, $update);
    }
}

//Handle item deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $entry_id = intval($_POST["entry_id"]);
    $delete = "DELETE FROM cart_items WHERE entry_id = $entry_id";
    mysqli_query($conn, $delete);
}

//Query for cart items joined with products
$query = "SELECT 
            cart_items.entry_id,
            cart_items.cart_quantity,
            products.prod_name,
            products.prod_cost
          FROM cart_items
          JOIN products ON cart_items.prod_id = products.prod_id";
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
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Your Cart</h1>

    <table>
        <tr style="font-size: large;">
            <th>Product Name</th>
            <th>Cost</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th>Actions</th>
        </tr>

        <?php
        $totalCost = 0;
        while($row = mysqli_fetch_array($result)):
            $subtotal = $row["prod_cost"] * $row["cart_quantity"];
            $totalCost += $subtotal;
        ?>
            <tr>
                <td><?php echo htmlspecialchars($row["prod_name"]); ?></td>
                <td>$<?php echo number_format($row["prod_cost"], 2); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="entry_id" value="<?php echo $row["entry_id"]; ?>">
                        <input type="number" name="cart_quantity" value="<?php echo $row["cart_quantity"]; ?>" min="1">
                        <input type="submit" name="update" value="Update">
                    </form>
                </td>
                <td>$<?php echo number_format($subtotal, 2); ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="entry_id" value="<?php echo $row["entry_id"]; ?>">
                        <input type="submit" name="delete" value="Delete">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        
        <?php
            //Shipping/Handling, Tax, and Final Total Calculations
            $shippingHandling = $totalCost * 0.10;
            $tax = $totalCost * 0.05;
            $finalTotal = $totalCost + $shippingHandling + $tax;
        ?>

        <tr>
            <td colspan="3" style="text-align:right; font-weight:bold;">Subtotal:</td>
            <td colspan="2">$<?php echo number_format($totalCost, 2); ?></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:right; font-weight:bold;">Shipping/Handling (10%):</td>
            <td colspan="2">$<?php echo number_format($shippingHandling, 2); ?></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:right; font-weight:bold;">Tax (5%):</td>
            <td colspan="2">$<?php echo number_format($tax, 2); ?></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:right; font-weight:bold;">Final Total Cost:</td>
            <td colspan="2">$<?php echo number_format($finalTotal, 2); ?></strong></td>
        </tr>
    </table>

    <br>
    <a href="index.php">Continue Shopping</a><br>
    <a href="checkout.php">Check Out</a>
</body>
</html>