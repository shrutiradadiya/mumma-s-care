<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

$conn = new mysqli("localhost", "root", "", "mumma's_care");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if product ID is passed
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Prepare the SQL query to delete the product
    $delete_query = "DELETE FROM products WHERE id = '$product_id'";

    if ($conn->query($delete_query) === TRUE) {
        // Redirect back to the products page after successful deletion
        header('Location: product.php');
        exit();
    } else {
        echo "Error deleting product: " . $conn->error;
    }
} else {
    echo "Product ID not specified.";
}
?>
