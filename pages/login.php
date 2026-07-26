<?php
session_start();
$conn = new mysqli(getenv("DB_HOST") ?: "localhost", getenv("DB_USER") ?: "root", getenv("DB_PASS") ?: "", getenv("DB_NAME") ?: "mumma's_care");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $captcha_response = $_POST["g-recaptcha-response"]; // Get reCAPTCHA response

    // Secret key for Google reCAPTCHA
    $secret_key = "6LeCd9MqAAAAAAklKfqKkHow_N-DX2bdHfCm8nsK";  // Replace with your secret key
    $verify_url = "https://www.google.com/recaptcha/api/siteverify";

    // Prepare data to send for verification
    $response = file_get_contents("$verify_url?secret=$secret_key&response=$captcha_response");
    $response_keys = json_decode($response, true);

    // Check if CAPTCHA was verified successfully
    if (intval($response_keys["success"]) !== 1) {
        echo "<p>Please verify that you are not a robot.</p>";
    } else {
        // CAPTCHA is verified, now proceed with email and password check
        $stmt = $conn->prepare("SELECT id, name, password FROM customers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $name, $hashed_password);
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION["customer_id"] = $id;
                $_SESSION["customer_name"] = $name;
                header("Location: home.php");
                exit;
            } else {
                echo "<p>Invalid password!</p>";
            }
        } else {
            echo "<p>Customer not found!</p>";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script> <!-- reCAPTCHA API -->
    <link rel="stylesheet" href="style.css"> <!-- External CSS link -->
</head>
<body>

<div class="container">
    <h2>Login</h2>
    <form method="POST">
        <input type="email" name="email" placeholder="Enter Email" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        
        <!-- Google reCAPTCHA -->
        <div class="g-recaptcha" data-sitekey="6LeCd9MqAAAAAApXq10BnY33EgQ4KZf3-r2kDjjF"></div> <!-- Replace with your site key -->
        
        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register</a></p>
</div>

</body>
</html>

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
    font-size: 24px;
}

input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

button {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

p {
    text-align: center;
    font-size: 14px;
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
    </style>        