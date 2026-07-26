<?php
// Start the session if you need session management
session_start();
 include("../includes/header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions - Baby Products Store</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
</head>
<body>
    <main>
        <section>
            <h2>Introduction</h2>
            <p>Welcome to our Baby Products Store. These terms and conditions outline the rules and regulations for the use of our website and the purchase of products from us. By accessing this website and placing an order, you agree to these terms and conditions.</p>
        </section>

        <section>
            <h2>General Terms</h2>
            <ul>
                <li>By using our website, you confirm that you are at least 18 years old or have the permission of a guardian to make purchases.</li>
                <li>We reserve the right to update these terms and conditions at any time without prior notice. All changes will be posted on this page.</li>
                <li>All content on this site, including text, images, and logos, is owned by our company and cannot be used without permission.</li>
            </ul>
        </section>

        <section>
            <h2>Products and Pricing</h2>
            <ul>
                <li>We strive to provide accurate descriptions of all our products, but we do not guarantee that the descriptions are error-free.</li>
                <li>Prices of products are subject to change without notice. All prices are displayed in the local currency and include applicable taxes.</li>
                <li>We reserve the right to refuse any order if a product is listed at an incorrect price or is unavailable.</li>
            </ul>
        </section>

        <section>
            <h2>Shipping & Delivery</h2>
            <ul>
                <li>We offer shipping to most locations. Delivery times vary based on your location and the shipping method chosen during checkout.</li>
                <li>Shipping costs will be calculated during the checkout process.</li>
                <li>We are not responsible for any delays caused by the shipping carrier or issues outside of our control.</li>
            </ul>
        </section>

        <section>
            <h2>Returns & Refunds</h2>
            <ul>
                <li>We accept returns of unused, unopened products within 30 days of purchase.</li>
                <li>To initiate a return, please contact our customer service team with your order number.</li>
                <li>Refunds will be issued once the returned product is received and inspected. Shipping fees are non-refundable.</li>
            </ul>
        </section>

        <section>
            <h2>Customer Responsibilities</h2>
            <ul>
                <li>Customers are responsible for providing accurate information during the checkout process, including shipping address and contact details.</li>
                <li>We are not responsible for any issues or delays that arise due to incorrect information provided by the customer.</li>
            </ul>
        </section>

        <section>
            <h2>Limitation of Liability</h2>
            <ul>
                <li>We are not liable for any damages arising from the use or inability to use our products, including any indirect or consequential damages.</li>
                <li>Our liability is limited to the purchase price of the product in question.</li>
            </ul>
        </section>

        <section>
            <h2>Privacy and Data Protection</h2>
            <p>We value your privacy. Your personal data will be handled according to our <a href="privacy-policy.php">Privacy Policy</a>.</p>
        </section>

        <section>
            <h2>Governing Law</h2>
            <p>These terms and conditions are governed by and construed in accordance with the laws of [Your Country]. Any disputes will be resolved under the jurisdiction of [Your Country].</p>
        </section>

        <section>
            <h2>Contact Us</h2>
            <p>If you have any questions or concerns regarding these terms and conditions, please contact us through our <a href="contact.php">Contact Page</a>.</p>
        </section>
    </main>
<style>
    /* Basic Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    line-height: 1.6;
    margin: 0;
    padding: 0;
}

header, footer {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 15px;
}

main {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

h2 {
    font-size: 2rem;
    color: #2c3e50;
    margin-top: 30px;
}

p {
    font-size: 1rem;
    margin: 10px 0;
}

ul {
    list-style-type: disc;
    margin-left: 20px;
    padding: 10px 0;
}

li {
    font-size: 1rem;
    margin-bottom: 8px;
}

/* Links */
a {
    color: #3498db;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}
/* Responsive Design */
@media (max-width: 768px) {
    main {
        padding: 10px;
    }

    h2 {
        font-size: 1.5rem;
    }

    p, li {
        font-size: 0.9rem;
    }
}

    </style>
    <?php include ("../includes/footer.php"); ?>
</body>
</html>
