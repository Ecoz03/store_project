<?php
//The controller coordinates user actions and determines which model methods to call
//It also decides which view to render based on the current action
//The launch.json file must reference controller.php when launching a web browser through the debugger

require_once 'model.php';
$model = new StoreModel();

//Handle POST actions (form submissions)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add"])) {
        //Add product to cart
        $model->addToCart($_POST["prod_id"], $_POST["Qty"]);
        header("Location: controller.php?action=catalog");
        exit;
    }

    if (isset($_POST["update"])) {
        //Update cart quantity
        $model->updateCartQuantity($_POST["entry_id"], $_POST["cart_quantity"]);
        header("Location: controller.php?action=view_cart");
        exit;
    }

    if (isset($_POST["delete"])) {
        //Delete item from cart
        $model->deleteCartItem($_POST["entry_id"]);
        header("Location: controller.php?action=view_cart");
        exit;
    }
}

//Handle GET actions (navigation)
$action = $_GET["action"] ?? "catalog";

switch ($action) {
    case "catalog":
        //Show product catalog
        $products = $model->getProducts();
        include 'view.php';
        break;

    case "view_cart":
        //Show cart contents
        $cartItems = $model->getCartItems();
        include 'view.php';
        break;

    case "checkout":
        //Clear cart and redirect to catalog
        $model->clearCart();
        header("Location: controller.php?action=catalog");
        exit;

    default:
        //Fallback to catalog view
        $products = $model->getProducts();
        include 'view.php';
        break;
}
?>