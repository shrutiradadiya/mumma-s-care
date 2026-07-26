<?php
// Start the session to check login status
session_start();

// Display all errors for debugging purposes
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli("localhost", "root", "", "Mumma's_care");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    // Connection was successful, print for debugging
    // echo "Connection successful!";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the posted data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if both fields are provided
    if (empty($username) || empty($password)) {
        echo "Please enter both username and password.";
        return;  // Stop execution if fields are empty
    }

    // SQL query to find user by username
    $query = "SELECT * FROM admin WHERE username=?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("s", $username);
    $stmt->execute();

    // Get the result of the query
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, fetch data
        $user = $result->fetch_assoc();

        // Check password (plain text comparison)
        if ($user['password'] == $password) {
            $_SESSION['admin_logged_in'] = true;
            header('Location: dashboard.php');  // Redirect to dashboard
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found with this username!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        /* Styles for login form */
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background:rgb(55, 173, 169);
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background:rgb(23, 191, 194);
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
