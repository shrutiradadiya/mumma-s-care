<?php
session_start();

// Check if the order was successfully processed
echo "<h2>Order Confirmation</h2>";
echo "<p>Your order has been successfully placed. You will receive a confirmation email shortly.</p>";
echo "<p>Thank you for shopping with us!</p>";

// Add a "Back to Shop" button
echo "<br><br>";
echo "<a href='home.php' class='back-to-shop-btn'>Back to Shop</a>";
?>
<style>
    .back-to-shop-btn {
        display: inline-block;
        padding: 12px 20px;
        font-size: 1.2em;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        text-align: center;
        margin-top: 20px;
        transition: background-color 0.3s ease;
    }

    .back-to-shop-btn:hover {
        background-color: #0056b3;
    }
</style>
