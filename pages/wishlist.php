<?php
// Start the session
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mumma's_care";

// Create a new PDO instance for the database connection
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}

// Initialize the wishlist if it's not set yet
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}

// Add product to wishlist
if (isset($_POST['add_to_wishlist'])) {
    $productId = $_POST['add_to_wishlist'];

    // Add the product ID to the session wishlist if not already added
    if (!in_array($productId, $_SESSION['wishlist'])) {
        $_SESSION['wishlist'][] = $productId;
    }
}

// Fetch products from the database based on the wishlist session
if (count($_SESSION['wishlist']) > 0) {
    $placeholders = implode(",", array_fill(0, count($_SESSION['wishlist']), "?"));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($_SESSION['wishlist']);
    $wishlistProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $wishlistProducts = [];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
    <style>
        /* General Styles */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

h1, h2, h3 {
    color: #333;
}

a {
    text-decoration: none;
    color: #3498db;
}

a:hover {
    color: #2980b9;
}


/* Wishlist Section */
.wishlist-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    padding: 40px 20px;
    background-color: #fff;
}

.product {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    width: 220px;
    padding: 15px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease;
    overflow: hidden;
}

.product:hover {
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.product img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 5px;
}

.product h3 {
    font-size: 18px;
    margin-top: 15px;
    color: #333;
}

.product p {
    font-size: 16px;
    color: #f39c12;
    margin: 10px 0;
}

.product .btn {
    display: block;
    background-color: #3498db;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    margin: 5px 0;
    text-align: center;
    font-size: 14px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.product .btn:hover {
    background-color: #2980b9;
}

.remove-btn {
    background-color: #e74c3c;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    margin: 5px 0;
    text-align: center;
    font-size: 14px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.remove-btn:hover {
    background-color: #c0392b;
}

/* Responsive Design */
@media (max-width: 768px) {
    header nav ul {
        flex-direction: column;
        align-items: center;
    }

    .wishlist-container {
        padding: 20px 10px;
    }

    .product {
        width: 45%;
    }
}

@media (max-width: 480px) {
    .product {
        width: 100%;
    }

    header nav ul {
        gap: 10px;
    }

    .product .btn, .remove-btn {
        font-size: 12px;
    }
}

    </style>
</head>
<body>
<?php include ("../includes/header.php"); ?>
<h2>Whislist</h2>
    <!-- Wishlist Section -->
    <main>
   
        <div class="wishlist-container">
            <?php if (count($wishlistProducts) > 0): ?>
                <?php foreach ($wishlistProducts as $product): ?>
                    <div class="product">
                        <img src="../image/<?= $product['image']; ?>" alt="<?= $product['name']; ?>">
                        <h3><?= $product['name']; ?></h3>
                        <p>₹<?= $product['price']; ?></p>
                        <h3><?= $product['description']; ?></h3>
                        <form action="cart.php" method="POST">
                            <button type="submit" name="add_to_cart" value="<?= $product['id']; ?>" class="btn">Add to Cart</button>
                        </form>
                        <form action="remove_wishlist.php" method="POST">
                            <button type="submit" name="remove_from_wishlist" value="<?= $product['id']; ?>" class="remove-btn">Remove from Wishlist</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products found in your wishlist.</p>
            <?php endif; ?>
        </div>
    </main>

    <?php include ("../includes/footer.php"); ?>
</body>
</html>
