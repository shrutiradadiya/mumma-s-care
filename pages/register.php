<?php
session_start();
$conn = new mysqli("localhost", "root", "", "mumma's_care");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all fields are set
    if (!empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["password"]) && !empty($_POST["g-recaptcha-response"])) {
        $name = trim($_POST["name"]);
        $email = trim($_POST["email"]);
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $recaptcha_response = $_POST["g-recaptcha-response"];

        // Verify CAPTCHA
        $secret_key = "6LeCd9MqAAAAAAklKfqKkHow_N-DX2bdHfCm8nsK";  // Replace with your actual Secret Key
        $recaptcha_url = "https://www.google.com/recaptcha/api/siteverify";
        $response = file_get_contents($recaptcha_url . "?secret=" . $secret_key . "&response=" . $recaptcha_response);
        $response_keys = json_decode($response, true);

        if(intval($response_keys["success"]) !== 1) {
            // CAPTCHA verification failed
            echo "<p style='color:red;'>Please complete the CAPTCHA.</p>";
        } else {
            // CAPTCHA passed, continue with the registration process
            $check_email_stmt = $conn->prepare("SELECT id FROM customers WHERE email = ?");
            $check_email_stmt->bind_param("s", $email);
            $check_email_stmt->execute();
            $check_email_stmt->store_result();

            if ($check_email_stmt->num_rows > 0) {
                // Email already exists
                echo "<p style='color:red;'>This email is already registered. Please use a different email.</p>";
            } else {
                // Email is unique, proceed with registration
                $stmt = $conn->prepare("INSERT INTO customers (name, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $email, $password);

                if ($stmt->execute()) {
                    $customer_id = $stmt->insert_id; // Get the ID of the newly inserted customer

                    // Now, add empty records for cart and wishlist
                    $product_id = 0; // Placeholder for product ID in cart and wishlist
                    $default_quantity = 0; // Empty cart for new customer

                    // Ensure product_id exists in the products table before inserting into cart
                    $product_check_query = "SELECT id FROM products WHERE id = ?";
                    $product_check_stmt = $conn->prepare($product_check_query);
                    $product_check_stmt->bind_param("i", $product_id);
                    $product_check_stmt->execute();
                    $product_check_stmt->store_result();

                    if ($product_check_stmt->num_rows > 0) {
                        // Product exists, proceed with inserting into cart
                        $cart_stmt = $conn->prepare("INSERT INTO cart (customer_id, product_id, quantity) VALUES (?, ?, ?)");
                        $cart_stmt->bind_param("iii", $customer_id, $product_id, $default_quantity); 
                        $cart_stmt->execute();
                    } else {
                        // Product does not exist, handle the error
                        echo "";
                    }

                    // Insert into wishlist with placeholder values
                    $wishlist_stmt = $conn->prepare("INSERT INTO wishlist (customer_id, product_id) VALUES (?, ?)");
                    $wishlist_stmt->bind_param("ii", $customer_id, $product_id); 
                    $wishlist_stmt->execute();

                    echo "Registration successful. <a href='login.php'>Login here</a>";
                } else {
                    echo "Error: " . $stmt->error;
                }

                $stmt->close();
            }

            $check_email_stmt->close();
        }
    } else {
        echo "<p style='color:red;'>All fields are required, including CAPTCHA!</p>";
    }
}
$conn->close();
?>

<!-- HTML Form -->
<div class="container">
    <h2>Register</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Enter Name" required>
        <input type="email" name="email" placeholder="Enter Email" required>
        <input type="password" name="password" placeholder="Enter Password" required>

        <!-- Google reCAPTCHA -->
        <div class="g-recaptcha" data-sitekey="6LeCd9MqAAAAAApXq10BnY33EgQ4KZf3-r2kDjjF"></div>

        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<style>
/* style.css */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 400px;
    margin: 100px auto;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
}

button:hover {
    background-color: #007bff;
}

p {
    text-align: center;
}

a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

p.red {
    color: red;
    font-size: 14px;
}

.g-recaptcha {
    margin: 10px 0;
    text-align: center;
}
</style>
