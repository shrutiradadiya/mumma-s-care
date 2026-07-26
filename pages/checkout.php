

<?php
session_start();

// Redirect to the cart page if the cart is empty
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    header("Location:add_to_cart.php");
    exit;
}

// Ensure the user is logged in and has a valid customer_id in the session
if (!isset($_SESSION['customer_id'])) {
    // Redirect to login page if customer is not logged in
    header("Location: login.php");
    exit;
}

// Get customer_id from session
$customerId = $_SESSION['customer_id'];

// Fetch product details from the cart
$cartProducts = $_SESSION['cart'];
$productDetails = [];
$totalPrice = 0;

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

// Fetch product details from the database
if ($cartProducts) {
    $productIds = array_keys($cartProducts);
    $query = "SELECT * FROM products WHERE id IN (" . implode(",", $productIds) . ")";
    $stmt = $pdo->query($query);
    $productDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Calculate total price for the order
foreach ($productDetails as $product) {
    $productId = $product['id'];
    $quantity = $cartProducts[$productId]['quantity'];
    $totalPrice += $product['price'] * $quantity;
}

// Fetch customer details from the database using customer_id
$stmt = $pdo->prepare("SELECT email FROM customers WHERE id = ?");
$stmt->execute([$customerId]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    // Handle case where customer doesn't exist in the database
    echo "Customer not found.";
    exit;
}

$email = $customer['email']; // Fetch the email associated with customer_id

// Handle checkout form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect user data from the form (no need for email anymore)
    $name = $_POST['name'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];
    $payment_method = $_POST['payment_method'];

    // Begin transaction to insert order and order items
    try {
        $pdo->beginTransaction();
        
        // Insert the order with customer_id, contact number, payment method, and order status
        $stmt = $pdo->prepare("INSERT INTO orders (customer_id, name, email, address, contact_number, total_price, payment_method, order_status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([$customerId, $name, $email, $address, $contact_number, $totalPrice, $payment_method]);
        $orderId = $pdo->lastInsertId(); // Get the last inserted order ID
        
        // Insert order items
        foreach ($productDetails as $product) {
            $productId = $product['id'];
            $quantity = $cartProducts[$productId]['quantity'];
            $totalForProduct = $product['price'] * $quantity;
            
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, total) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$orderId, $productId, $quantity, $product['price'], $totalForProduct]);
        }
        
        // Commit transaction
        $pdo->commit();
        
        // Clear the cart session
        unset($_SESSION['cart']);
        
        // Check if payment method is Online
        if ($payment_method != 'COD') {
            // Redirect to Razorpay payment page and pass the order ID
            header("Location: payment.php?order_id=" . $orderId);
            exit;
        }

        // Redirect to the success page if Cash on Delivery (COD) is selected
        header("Location: order_success.php?order_id=" . $orderId);
        exit;

    } catch (Exception $e) {
        // Rollback if an error occurs
        $pdo->rollBack();
        echo "<p>Error: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .checkout-container {
            padding: 30px;
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .checkout-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .checkout-form label {
            display: block;
            margin: 10px 0 5px;
        }
        .checkout-form input,
        .checkout-form textarea,
        .checkout-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .checkout-form button {
            background-color: #f1c40f;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease;
        }
        .checkout-form button:hover {
            background-color: #e67e22;
        }
        .order-summary {
            margin-top: 30px;
        }
        .order-summary p {
            font-size: 1.2em;
            margin: 5px 0;
        }
    </style>
</head>
<body>
<?php include ("../includes/header.php"); ?>
<div class="checkout-container">
    <h2>Checkout</h2>
    
    <!-- Display the order summary -->
    <div class="order-summary">
    <h3>Order Summary:</h3>
    <?php if ($productDetails): ?>
        <table>
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
            <?php foreach ($productDetails as $product): ?>
                <?php
                    $productId = $product['id'];
                    $quantity = $cartProducts[$productId]['quantity'];
                    $totalPriceForProduct = $product['price'] * $quantity;
                    $image = $product['image'];  // Assuming the image URL is stored in the `image_url` column
                ?>
                <tr>
                    <td><img src="../image/<?= $image?>" alt="<?= $product['name']; ?>" style="width: 50px; height: 50px; object-fit: cover;"></td>
                    <td><?= $product['name']; ?></td>
                    <td><?= $quantity; ?></td>
                    <td>₹<?= $product['price']; ?></td>
                    <td>₹<?= $totalPriceForProduct; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    <p><strong>Total: ₹<?= $totalPrice; ?></strong></p>
</div>

    <!-- Checkout form to collect user details -->
    <form method="POST" class="checkout-form">
        <label for="name">Full Name</label>
        <input type="text" name="name" id="name" required>

        <label for="address">Shipping Address</label>
        <textarea name="address" id="address" required></textarea>

        <label for="contact_number">Contact Number</label>
        <input type="text" name="contact_number" id="contact_number" required>

        <!-- Payment Method -->
        <label for="payment_method">Payment Method</label>
        <select name="payment_method" id="payment_method" required>
            <option value="COD">Cash On Delivery</option>
            <option value="Online">Online Payment</option>
        </select>

        <!-- Place Order Button -->
        <button type="submit">Place Order</button>
    </form>
</div>

<?php include ("../includes/footer.php"); ?>
</body>
</html>
