<?php
// Start the session if you need session management
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy</title>
    <style>
        /* Reset default margin and padding */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    color: #333;
    line-height: 1.6;
    padding: 20px;
}

/* Main content styling */
main {
    max-width: 1200px;
    margin: 40px auto;
    padding: 20px;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h2 {
    color: #4CAF50;
    font-size: 28px;
    margin-bottom: 10px;
}

p {
    font-size: 16px;
    margin-bottom: 20px;
}

ul {
    margin-left: 20px;
    font-size: 16px;
}

ul li {
    margin-bottom: 10px;
}

/* Section Styling */
section {
    margin-bottom: 30px;
}

/* Link styling */
a {
    color: #4CAF50;
    text-decoration: none;
    transition: color 0.3s ease;
}

a:hover {
    color: #FFD700;
}

/* Responsive Design */
@media (max-width: 768px) {
    header h1 {
        font-size: 28px;
    }

    nav ul {
        flex-direction: column;
        align-items: center;
    }

    nav ul li {
        margin: 10px 0;
    }

    main {
        padding: 10px;
    }

    footer p {
        font-size: 12px;
    }
}

        </style>
</head>
<body>
<?php include ("../includes/header.php"); ?>
   
    <main>
        <section>
            <h2>Introduction</h2>
            <p>Welcome to our website. This privacy policy explains how we collect, use, and protect your personal data when you visit our site.</p>
        </section>

        <section>
            <h2>Information We Collect</h2>
            <p>We collect the following information from our visitors:</p>
            <ul>
                <li>Personal Identification Information (name, email address, etc.)</li>
                <li>Non-personal Identification Information (IP address, browser type, etc.)</li>
            </ul>
        </section>

        <section>
            <h2>How We Use Your Information</h2>
            <p>Your information is used to improve our website and provide a better user experience. We may use your data for:</p>
            <ul>
                <li>Responding to inquiries</li>
                <li>Personalizing content</li>
                <li>Improving customer service</li>
            </ul>
        </section>

        <section>
            <h2>Data Protection</h2>
            <p>We are committed to protecting your personal data. We implement appropriate security measures to ensure your data is safe.</p>
        </section>

        <section>
            <h2>Cookies</h2>
            <p>Our website may use cookies to improve your experience. You can choose to accept or decline cookies in your browser settings.</p>
        </section>

        <section>
            <h2>Third-Party Links</h2>
            <p>Our site may contain links to third-party websites. We are not responsible for their privacy practices, and we recommend reviewing their privacy policies.</p>
        </section>

        <section>
            <h2>Contact Us</h2>
            <p>If you have any questions regarding this privacy policy, please feel free to <a href="contact.php">contact us</a>.</p>
        </section>
    </main>
    <?php include ("../includes/footer.php"); ?>
</body>
</html>
