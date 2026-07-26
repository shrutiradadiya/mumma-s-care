<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mumma's_care";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

// Get payment details
$payment_id = $_GET['payment_id'] ?? '';
$order_id = $_GET['order_id'] ?? '';

if (!$payment_id || !$order_id) {
    die("Error: Payment ID or Order ID is missing.");
}

// Verify if order exists
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("Error: Order not found in database.");
}

// Update payment status
$stmt = $pdo->prepare("UPDATE payment SET razorpay_payment_id = ?, status = 'Success' WHERE order_id = ?");
if ($stmt->execute([$payment_id, $order_id])) {
    echo "✅ Payment updated successfully!";
} else {
    echo "❌ Error updating payment!";
}

// Update order status
$stmt = $pdo->prepare("UPDATE orders SET order_status = 'Paid', razorpay_payment_id = ? WHERE id = ?");
if ($stmt->execute([$payment_id, $order_id])) {
    echo "✅ Order status and Razorpay Payment ID updated!";
} else {
    // Print any error that occurred during the execution of the query
    $errorInfo = $stmt->errorInfo();
    echo "❌ Error updating order: " . $errorInfo[2];
}

// Fetch razorpay_payment_id to display on success page
$stmt = $pdo->prepare("SELECT razorpay_payment_id FROM payment WHERE order_id = ?");
$stmt->execute([$order_id]);
$payment = $stmt->fetch(PDO::FETCH_ASSOC);
$razorpay_payment_id = $payment['razorpay_payment_id'] ?? 'N/A';

// Redirect to success page
header("Location: order_success.php?order_id=".$order_id."&razorpay_payment_id=".$razorpay_payment_id);
exit;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .success-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .success-container h2 {
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .success-container p {
            font-size: 1.2em;
            margin: 10px 0;
        }

        .success-container .order-id {
            font-weight: bold;
            color: #e67e22;
        }

        .success-container .total-price {
            font-weight: bold;
            color: #2c3e50;
        }

        .back-to-shop-btn {
            background-color: #f1c40f;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
        }

        .back-to-shop-btn:hover {
            background-color: #e67e22;
        }
    </style>
</head>
<body>
<?php include ("../includes/header.php"); ?>
<div class="success-container">
    <h2>Order Successful</h2>
    <p>Thank you for your order!</p>
    <p class="order-id">Order ID: #<?= htmlspecialchars($order['id']) ?></p>
    <p class="total-price">Total Price: ₹<?= htmlspecialchars($order['total_price']) ?></p>
    <p class="payment-id">Payment ID: <?= htmlspecialchars($_GET['razorpay_payment_id']) ?></p>
    <p>Your order is being processed and will be shipped soon. We will send you an email with tracking details.</p>

    <a href="home.php">
        <button class="back-to-shop-btn">Back to Shop</button>
    </a>
</div>
    <?php include ("../includes/footer.php"); ?>
</html>

