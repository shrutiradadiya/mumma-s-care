<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $customer_id = $_GET['id'];

    // Database Connection
    $conn = new mysqli("localhost", "root", "", "mumma's_care");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to delete the customer
    $sql = "DELETE FROM customers WHERE id = ?";
    
    // Prepare and bind the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $customer_id);
        if ($stmt->execute()) {
            header('Location: customer.php');  // Redirect back to the customers page after successful deletion
        } else {
            echo "Error deleting customer: " . $conn->error;
        }
        $stmt->close();
    }

    $conn->close();
} else {
    echo "No customer ID specified for deletion.";
}
?>
