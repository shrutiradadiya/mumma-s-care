<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Database connection
$conn = new mysqli(getenv("DB_HOST") ?: "localhost", getenv("DB_USER") ?: "root", getenv("DB_PASS") ?: "", getenv("DB_NAME") ?: "mumma's_care");

// Get the customer ID from the session
$customer_id = $_SESSION['customer_id'];

// Fetch customer details from the database
$stmt = $conn->prepare("SELECT name, email FROM customers WHERE id = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($name, $email);
$stmt->fetch();
$stmt->close();
$conn->close();

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];

    // Update the customer's profile information in the database
    $conn = new mysqli(getenv("DB_HOST") ?: "localhost", getenv("DB_USER") ?: "root", getenv("DB_PASS") ?: "", getenv("DB_NAME") ?: "mumma's_care");
    $update_stmt = $conn->prepare("UPDATE customers SET name = ?, email = ? WHERE id = ?");
    $update_stmt->bind_param("ssi", $new_name, $new_email, $customer_id);
    $update_stmt->execute();
    $update_stmt->close();
    $conn->close();

    // Update session values
    $_SESSION['customer_name'] = $new_name;
    $_SESSION['customer_email'] = $new_email;

    // Redirect to profile page after update
    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Mumma's Care</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: rgb(190, 242, 241);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
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

        .profile-info {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .profile-info p {
            margin: 10px 0;
        }

        .update-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .update-form button {
            width: 100%;
            padding: 10px;
            background-color: #06b192;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .update-form button:hover {
            background-color: #024343;
        }

        .logout-button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #ff4081;
            color: white;
            text-decoration: none;
            text-align: center;
            margin-top: 20px;
            border-radius: 5px;
        }

        .logout-button:hover {
            background-color: #ff80ab;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Profile</h2>

    <!-- Profile Information Display -->
    <div class="profile-info">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($name); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
    </div>

    <!-- Profile Update Form -->
    <h3>Update Your Information</h3>
    <form method="POST" class="update-form">
        <input type="text" name="name" placeholder="Enter New Name" value="<?php echo htmlspecialchars($name); ?>" required>
        <input type="email" name="email" placeholder="Enter New Email" value="<?php echo htmlspecialchars($email); ?>" required>
        <button type="submit" name="update">Update</button>
    </form>
    <a href="home.php" class="logout-button">Back to home</a>
    <!-- Logout Button -->
    <a href="logout.php" class="logout-button">Logout</a>
</div>

</body>
</html>
