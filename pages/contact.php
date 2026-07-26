<?php
// Start the session to store success or error messages
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Baby Products Store</title>
    
    <!-- Inline CSS Styling -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
        }
        main {
            margin: 2em;
        }

        section {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            font-size: 1.8em;
        }

        p {
            font-size: 1.1em;
            color: #555;
        }

        label {
            font-size: 1em;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1em;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php include ("../includes/header.php"); ?>
    <main>
        <section>
            <h2>Get in Touch</h2>
            <p>If you have any questions, concerns, or feedback, feel free to reach out to us using the contact form below. We’re here to help you with your baby product needs!</p>
        </section>

        <section>
            <h2>Contact Form</h2>
            
            <!-- Display message from session -->
            <?php
            if (isset($_SESSION['message'])) {
                echo "<p class='message'>{$_SESSION['message']}</p>";
                unset($_SESSION['message']);
            }
            ?>

            <form action="submit_contact.php" method="POST">
                <div>
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div>
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div>
                    <label for="message">Your Message:</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                <div>
                    <input type="submit" value="Send Message">
                </div>
            </form>
        </section>
    </main>

    <?php include ("../includes/footer.php"); ?>
</body>
</html>
