<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['customer_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// Get customer ID and order ID from the POST request
$customerId = $_SESSION['customer_id'];
$orderId = $_POST['order_id'] ?? null;

if ($orderId) {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mumma's_care";

    try {
        $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the order belongs to the logged-in customer and is not already completed or cancelled
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ? AND customer_id = ? AND order_status != 'completed' AND order_status != 'cancelled'");
        $stmt->execute([$orderId, $customerId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            // Update the order status to 'cancelled'
            $stmt = $pdo->prepare("UPDATE orders SET order_status = 'cancelled' WHERE id = ?");
            $stmt->execute([$orderId]);

            // Redirect to the orders page with a success message
            header("Location: my_order.php?message=Order cancelled successfully");
            exit;
        } else {
            // If the order doesn't belong to the customer or is already cancelled/completed
            header("Location: my_order.php?error=Unable to cancel this order");
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
} else {
    // If no order ID is provided
    header("Location: my_order.php?error=Invalid order ID");
    exit;
}
?>
