<?php
//The StoreModel class encapsulates all database logic.
//Using a class allows us to modularize functionality, reuse methods, and maintain clean separation of concerns.
//This aligns with MVC principles: the model handles data, not presentation or control logic.

class StoreModel {
    private $conn; //Holds the database connection

    //Constructor: establishes a connection to the database when the model is instantiated
    public function __construct() {
        $hostname = "localhost";
        $username = "ecpi_user";
        $password = "Password1";
        $dbname = "store_db";

        //mysqli_connect returns a connection object used for all queries
        $this->conn = mysqli_connect($hostname, $username, $password, $dbname);
    }

    //Retrieves all products from the catalog
    public function getProducts() {
        $query = "SELECT * FROM products";
        return mysqli_query($this->conn, $query);
    }

    //Retrieves all cart items, joined with product details
    public function getCartItems() {
        $query = "SELECT 
                    cart_items.entry_id,
                    cart_items.cart_quantity,
                    products.prod_name,
                    products.prod_cost
                  FROM cart_items
                  JOIN products ON cart_items.prod_id = products.prod_id";
        return mysqli_query($this->conn, $query);
    }

    //Adds a product to the cart, or updates quantity if already present
    public function addToCart($prod_id, $quantity) {
        $prod_id = intval($prod_id);       //Sanitize input to prevent SQL injection
        $quantity = intval($quantity);

        if ($quantity > 0) {
            //Check if the product is already in the cart
            $check = "SELECT cart_quantity FROM cart_items WHERE prod_id = $prod_id";
            $check_result = mysqli_query($this->conn, $check);

            if (mysqli_num_rows($check_result) > 0) {
                //If it exists, update the quantity
                $row = mysqli_fetch_assoc($check_result);
                $new_quantity = $row["cart_quantity"] + $quantity;
                $update = "UPDATE cart_items SET cart_quantity = $new_quantity WHERE prod_id = $prod_id";
                mysqli_query($this->conn, $update);
            } else {
                //If not, insert a new cart entry
                $insert = "INSERT INTO cart_items (prod_id, cart_quantity) VALUES ($prod_id, $quantity)";
                mysqli_query($this->conn, $insert);
            }
        }
    }

    //Updates the quantity of a specific cart item
    public function updateCartQuantity($entry_id, $new_quantity) {
        $entry_id = intval($entry_id);
        $new_quantity = intval($new_quantity);

        if ($new_quantity > 0) {
            $update = "UPDATE cart_items SET cart_quantity = $new_quantity WHERE entry_id = $entry_id";
            mysqli_query($this->conn, $update);
        }
    }

    //Deletes a specific item from the cart
    public function deleteCartItem($entry_id) {
        $entry_id = intval($entry_id);
        $delete = "DELETE FROM cart_items WHERE entry_id = $entry_id";
        mysqli_query($this->conn, $delete);
    }

    //Clears all items from the cart (used during checkout)
    public function clearCart() {
        $clear = "DELETE FROM cart_items";
        mysqli_query($this->conn, $clear);
    }
}
?>