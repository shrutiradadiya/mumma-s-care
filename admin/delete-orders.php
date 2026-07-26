<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Database Connection
$conn = new mysqli("localhost", "root", "", "mumma's_care");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the order ID is set and is numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $order_id = $_GET['id'];

    // Debugging: Display the order ID
    echo "Attempting to delete order with ID: " . $order_id . "<br>";

    // Check if the order exists in the database
    $check_sql = "SELECT * FROM orders WHERE id = ?";
    $stmt_check = $conn->prepare($check_sql);
    if ($stmt_check === false) {
        echo "Error preparing check query: " . $conn->error . "<br>";
    } else {
        $stmt_check->bind_param("i", $order_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        // If the order exists, delete it
        if ($result_check->num_rows > 0) {
            // Order found, now attempt to delete it
            $delete_sql = "DELETE FROM orders WHERE id = ?";
            if ($stmt_delete = $conn->prepare($delete_sql)) {
                $stmt_delete->bind_param("i", $order_id);
                if ($stmt_delete->execute()) {
                    // Success message
                    header('Location: orders.php?message=Order deleted successfully');
                    exit();
                } else {
                    // Error executing delete query
                    echo "Error deleting order: " . $stmt_delete->error . "<br>";
                }
            } else {
                echo "Error preparing delete query: " . $conn->error . "<br>";
            }
        } else {
            echo "Order with ID: " . $order_id . " not found in the database.<br>";
        }
    }
} else {
    echo "Invalid or missing order ID.<br>";
}

$conn->close();
?>
