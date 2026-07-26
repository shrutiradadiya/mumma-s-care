<?php 
// Include header.php
require_once '../includes/connect.php'; // Adjust path if necessary
include("../includes/header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Policy</title>
    <style>
        /* Styles for the return policy page */
        .content {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .content h1 {
            font-size: 2rem;
            text-align: center;
            color: #333;
        }
        
        .content h2 {
            font-size: 1.5rem;
            color: #2980b9;
            margin-top: 20px;
        }
        
        .content p {
            font-size: 1rem;
            line-height: 1.6;
            color: #555;
        }
        
        .content ul {
            margin-top: 10px;
        }
        
        .content li {
            font-size: 1rem;
            color: #555;
            margin-left: 20px;
        }

        /* Styles for the footer or any common section */
        footer {
            text-align: center;
            padding: 20px;
            background-color: #2980b9;
            color: white;
        }
    </style>
</head>
<body>

<div class="content">
    <h1>Return Policy</h1>
    
    <p>We understand that sometimes things just don't work out. That's why we offer a simple return policy for all our customers.</p>

    <h2>1. Returns Eligibility</h2>
    <p>To be eligible for a return, your item must meet the following conditions:</p>
    <ul>
        <li>The item must be unused and in the same condition that you received it.</li>
        <li>The item must be in the original packaging with all tags and labels attached.</li>
        <li>You must have proof of purchase (receipt or order confirmation).</li>
    </ul>

    <h2>2. Non-Returnable Items</h2>
    <p>Unfortunately, we cannot accept returns for the following items:</p>
    <ul>
        <li>Gift cards or promotional items</li>
        <li>Items on sale or discounted</li>
        <li>Perishable items like food or plants</li>
        <li>Items that have been used or damaged after delivery</li>
    </ul>

    <h2>3. Return Process</h2>
    <p>If you meet the eligibility requirements, here's how to return your item:</p>
    <ol>
        <li>Contact our customer service team at <strong>support@mumma's care.com</strong> or call us at <strong> +123-456-7890</strong> to initiate the return.</li>
        <li>Once the return request is received, you will be provided with a return authorization number.</li>
        <li>Pack the item securely in its original packaging and include a copy of the receipt.</li>
        <li>Ship the item back to us within 30 days of receiving the order. Return shipping fees are at the customer's expense unless the item was damaged or defective.</li>
    </ol>

    <h2>4. Refunds</h2>
    <p>Once we receive your returned item, we will inspect it and notify you of the status of your refund. If your return is approved, we will process the refund to your original payment method. Please allow 7-10 business days for the refund to appear in your account.</p>

    <h2>5. Exchange Policy</h2>
    <p>If you wish to exchange an item, please contact our customer service team. We can assist you with processing an exchange or offering a store credit if applicable.</p>
    
    <h2>6. Contact Us</h2>
    <p>If you have any questions about our return policy or need assistance with a return, please contact us:</p>
    <p>Email: support@mumma's care.com</p>
    <p>Phone: +123-456-7890</p>
</div>

<?php include ("../includes/footer.php"); ?>

</body>
</html>
