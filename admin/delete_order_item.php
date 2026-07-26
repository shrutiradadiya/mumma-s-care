<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Database Connection
$conn = new mysqli(getenv("DB_HOST") ?: "localhost", getenv("DB_USER") ?: "root", getenv("DB_PASS") ?: "", getenv("DB_NAME") ?: "mumma's_care");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if an order item ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $order_item_id = $_GET['id'];

    // Prepare and execute the DELETE query
    $delete_query = "DELETE FROM order_items WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $order_item_id);

    if ($stmt->execute()) {
        // If deletion was successful, redirect to the order items page
        header('Location: order_items.php');
        exit();
    } else {
        // If deletion failed, display an error message
        echo "Error: Unable to delete the order item.";
    }

    $stmt->close();
} else {
    // If no valid ID is provided, redirect to the order items page
    header('Location: order_items.php');
    exit();
}

$conn->close();
?>
