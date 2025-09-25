<!DOCTYPE html>
<html>
<head>
    <title>Store Interface</title>
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
</head>
<body>

<?php if (isset($products)): ?>
    <!-- Product Catalog View -->
    <h1>Product Catalog</h1>
    <table>
        <tr style="font-size: large;">
            <th>Product Name</th>
            <th>Description</th>
            <th>Cost</th>
            <th>Add to Cart</th>
        </tr>
        <?php while($row = mysqli_fetch_array($products)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row["prod_name"]); ?></td>
                <td><?php echo htmlspecialchars($row["prod_desc"]); ?></td>
                <td>$<?php echo number_format($row["prod_cost"], 2); ?></td>
                <td>
                    <form method="POST" action="controller.php">
                        <input type="hidden" name="prod_id" value="<?php echo $row["prod_id"]; ?>">
                        <input type="number" name="Qty" value="1" min="1">
                        <input type="submit" value="Add to Cart" name="add">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
    <br>
    <a href="controller.php?action=view_cart">Go to Cart</a>

<?php elseif (isset($cartItems)): ?>
    <!-- Shopping Cart View -->
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
        while($row = mysqli_fetch_array($cartItems)):
            $subtotal = $row["prod_cost"] * $row["cart_quantity"];
            $totalCost += $subtotal;
        ?>
            <tr>
                <td><?php echo htmlspecialchars($row["prod_name"]); ?></td>
                <td>$<?php echo number_format($row["prod_cost"], 2); ?></td>
                <td>
                    <form method="POST" action="controller.php">
                        <input type="hidden" name="entry_id" value="<?php echo $row["entry_id"]; ?>">
                        <input type="number" name="cart_quantity" value="<?php echo $row["cart_quantity"]; ?>" min="1">
                        <input type="submit" name="update" value="Update">
                    </form>
                </td>
                <td>$<?php echo number_format($subtotal, 2); ?></td>
                <td>
                    <form method="POST" action="controller.php">
                        <input type="hidden" name="entry_id" value="<?php echo $row["entry_id"]; ?>">
                        <input type="submit" name="delete" value="Delete">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>

        <!-- Begin: Added logic for sales tax, shipping, and final total -->
        <?php
        $salesTax = $totalCost * 0.05;
        $shipping = $totalCost * 0.10;
        $finalTotal = $totalCost + $salesTax + $shipping;
        ?>

        <tr>
            <td colspan="3" style="text-align:right; font-weight:bold;">Sales Tax (5%):</td>
            <td colspan="2">$<?php echo number_format($salesTax, 2); ?></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:right; font-weight:bold;">Shipping/Handling (10%):</td>
            <td colspan="2">$<?php echo number_format($shipping, 2); ?></td>
        </tr>
        <tr>
            <td colspan="3" style="text-align:right; font-weight:bold;">Final Total:</td>
            <td colspan="2">$<?php echo number_format($finalTotal, 2); ?></td>
        </tr>
        <!-- End: Added logic for sales tax, shipping, and final total -->
    </table>
    <br>
    <a href="controller.php?action=catalog">Continue Shopping</a><br>
    <a href="controller.php?action=checkout">Check Out</a>

<?php elseif (isset($checkout)): ?>
    <!-- Checkout View -->
    <h1>Processing Checkout...</h1>
    <p>If you're seeing this, the redirect didn't work.</p>
    <a href="controller.php?action=catalog">Return to Catalog</a>
<?php endif; ?>

</body>
</html>