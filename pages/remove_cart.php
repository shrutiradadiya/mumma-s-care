<?php
session_start();

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    unset($_SESSION['cart'][$product_id]); // Remove item from cart
}

header("Location: add_to_cart.php");
exit();
?>
