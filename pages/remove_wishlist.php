<?php
// Start the session
session_start();

// Check if the product ID to be removed is set
if (isset($_POST['remove_from_wishlist'])) {
    $productId = $_POST['remove_from_wishlist'];

    // Remove the product from the wishlist array
    if (($key = array_search($productId, $_SESSION['wishlist'])) !== false) {
        unset($_SESSION['wishlist'][$key]);
    }

    // Re-index the array to maintain the correct keys
    $_SESSION['wishlist'] = array_values($_SESSION['wishlist']);
}

// Redirect back to the wishlist page
header("Location: wishlist.php");
exit;
?>
<style>
    .remove-btn {
    display: block;
    background-color: #e74c3c;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    margin: 5px 0;
    text-align: center;
    cursor: pointer;
    border: none;
}

.remove-btn:hover {
    background-color: #c0392b;
}

</style>