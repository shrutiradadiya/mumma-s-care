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

// Handle Add to Cart
if (isset($_POST['add_to_cart'])) {
    $productId = $_POST['add_to_cart'];
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; // Default quantity is 1

    // Fetch the current stock for the product
    $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = :id");
    $stmt->execute(['id' => $productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $availableStock = $product['stock'];

        // Check if the requested quantity is available in stock
        if ($quantity <= $availableStock) {
            // Add product to the cart
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            if (isset($_SESSION['cart'][$productId])) {
                $_SESSION['cart'][$productId]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$productId] = ['quantity' => $quantity];
            }

            // Reduce stock in the database by exactly the quantity purchased
            $newStock = $availableStock - $quantity;
            $stmt = $pdo->prepare("UPDATE products SET stock = :stock WHERE id = :id");
            $stmt->execute(['stock' => $newStock, 'id' => $productId]);

        } else {
            $errorMessage = "Sorry, only $availableStock items are available in stock.";
        }
    }

    // Buy Now: skip cart page, go straight to checkout
    if (!isset($errorMessage) && isset($_GET['buy_now'])) {
        header("Location: checkout.php");
        exit;
    }
}

// Handle Update Quantity
if (isset($_POST['update_cart'])) {
    $productId = $_POST['product_id'];
    $newQuantity = $_POST['quantity'];

    // Fetch current stock for the product
    $stmt = $pdo->prepare("SELECT stock FROM products WHERE id = :id");
    $stmt->execute(['id' => $productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $availableStock = $product['stock'];

        // Ensure the new quantity does not exceed stock
        if ($newQuantity <= $availableStock && $newQuantity > 0) {
            // Calculate the quantity difference
            $oldQuantity = $_SESSION['cart'][$productId]['quantity'];
            $quantityDifference = $newQuantity - $oldQuantity;

            // Update the quantity in the session cart
            $_SESSION['cart'][$productId]['quantity'] = $newQuantity;

            // Update the stock in the database based on the quantity difference
            $newStock = $availableStock - $quantityDifference;
            $stmt = $pdo->prepare("UPDATE products SET stock = :stock WHERE id = :id");
            $stmt->execute(['stock' => $newStock, 'id' => $productId]);
        } else {
            $errorMessage = "Sorry, only $availableStock items are available in stock.";
        }
    }
}

// Handle Remove product from cart
if (isset($_POST['remove_product'])) {
    $productId = $_POST['remove_product'];

    // Get product details before removing it
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->execute(['id' => $productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product && isset($_SESSION['cart'][$productId])) {
        $quantityToRemove = $_SESSION['cart'][$productId]['quantity'];
        
        // Restore the stock in the database
        $newStock = $product['stock'] + $quantityToRemove;
        $stmt = $pdo->prepare("UPDATE products SET stock = :stock WHERE id = :id");
        $stmt->execute(['stock' => $newStock, 'id' => $productId]);

        // Remove the product from the cart
        unset($_SESSION['cart'][$productId]);
    }
}

// Handle Clear Cart (for order cancel)
if (isset($_POST['clear_cart'])) {
    // Loop through all products in the cart and restore stock
    foreach ($_SESSION['cart'] as $productId => $cartItem) {
        // Fetch product details
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $quantityToRestore = $cartItem['quantity'];

            // Restore the stock in the database
            $newStock = $product['stock'] + $quantityToRestore;
            $stmt = $pdo->prepare("UPDATE products SET stock = :stock WHERE id = :id");
            $stmt->execute(['stock' => $newStock, 'id' => $productId]);
        }
    }

    // Clear the cart session
    unset($_SESSION['cart']);
}

// Fetch cart products for display
$cartProducts = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$productDetails = [];

if ($cartProducts) {
    $productIds = array_keys($cartProducts);
    $query = "SELECT * FROM products WHERE id IN (" . implode(",", $productIds) . ")";
    $stmt = $pdo->query($query);
    $productDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$totalPrice = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <style>
        /* Your CSS styles here (same as provided previously) */
        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.cart-container {
    display: flex;
    justify-content: center;
    padding: 30px 10px;
}

.cart-items {
    width: 100%;
    max-width: 1200px;
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.cart-items h2 {
    text-align: center;
    font-size: 2em;
    margin-bottom: 20px;
    color: #333;
}

.cart-items table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 30px;
}

.cart-items th,
.cart-items td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

.cart-items th {
    background-color: #f1c40f;
    color: #fff;
}

.cart-items td {
    background-color: #f9f9f9;
}

.cart-items img {
    max-width: 100px;
    height: auto;
    border-radius: 8px;
}

.cart-items input[type="number"] {
    width: 60px;
    padding: 6px;
    text-align: center;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin: 5px 0;
}

.cart-items button {
    background-color: #f1c40f;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.cart-items button:hover {
    background-color: #e67e22;
}

.cart-items input[type="submit"] {
    background-color: #3498db;
    color: white;
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.cart-items input[type="submit"]:hover {
    background-color: #2980b9;
}

.cart-items td button {
    background-color: #e74c3c;
    padding: 5px 10px;
    border-radius: 5px;
    border: none;
    color: white;
    cursor: pointer;
    font-size: 14px;
}

.cart-items td button:hover {
    background-color: #c0392b;
}

.cart-summary {
    width: 100%;
    max-width: 1200px;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    text-align: center;
}

.cart-summary p {
    font-size: 1.4em;
    margin: 10px 0;
    font-weight: bold;
}

.cart-summary .total-price {
    font-size: 1.8em;
    color: #e74c3c;
}

.cart-summary button {
    background-color: #2ecc71;
    color: #fff;
    padding: 12px 25px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.cart-summary button:hover {
    background-color: #27ae60;
}

.cart-summary .back-to-shop-btn {
    background-color: #3498db;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.cart-summary .back-to-shop-btn:hover {
    background-color: #2980b9;
}

.error-message {
    color: #e74c3c;
    font-size: 1.2em;
    text-align: center;
    margin-bottom: 20px;
}
    </style>
</head>
<body>

<div class="cart-container">
    <div class="cart-items">
        <h2>Your Cart</h2>

        <?php if (isset($errorMessage)): ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <?php if ($cartProducts && count($cartProducts) > 0): ?>
            <form method="POST">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productDetails as $product): ?>
                            <?php
                                $productId = $product['id'];
                                $quantity = $cartProducts[$productId]['quantity'];
                                $totalPriceForProduct = $product['price'] * $quantity;
                                $totalPrice += $totalPriceForProduct;
                            ?>
                            <tr>
                                <td><img src="../image/<?= $product['image']; ?>" alt="<?= $product['name']; ?>"></td>
                                <td><?= $product['name']; ?></td>
                                <td>
                                    <input type="number" name="quantity" value="<?= $quantity; ?>" min="1" max="10">
                                </td>
                                <td>₹<?= $product['price']; ?></td>
                                <td>₹<?= $totalPriceForProduct; ?></td>
                                <td>
                                    <button type="submit" name="update_cart" value="update">Update</button>
                                    <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                                    <button type="submit" name="remove_product" value="<?= $product['id']; ?>">Remove</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>

            <div class="cart-summary">
                <p class="total-price">Total Price: ₹<?= $totalPrice; ?></p>
                <a href="checkout.php">
                    <button>Proceed to Checkout</button>
                </a>
                <a href="category.php">
                    <button class="back-to-shop-btn">Continue Shopping</button>
                </a>
            </div>
        <?php else: ?>
            <p>Your cart is empty.</p>
            <div class="cart-summary">
                <a href="category.php">
                    <button class="back-to-shop-btn">Continue Shopping</button>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
