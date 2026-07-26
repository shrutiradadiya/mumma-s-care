<?php
// Start the session
session_start();

// Database connection variables
$servername = "localhost";
$username = "root"; // replace with your correct database username
$password = ""; // replace with your correct database password
$dbname = "mumma's_care"; // replace with your database name

// Create a new connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the form data
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

// Prepare the SQL query to insert the data into the contact_form table
$sql = "INSERT INTO contact_form (name, email, message) VALUES ('$name', '$email', '$message')";

// Execute the query
if ($conn->query($sql) === TRUE) {
    // If the data is inserted successfully, store a success message in the session
    $_SESSION['message'] = "Thank you for contacting us! We will get back to you soon.";
} else {
    // If there is an error, store the error message in the session
    $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
}

// Close the connection
$conn->close();

// Redirect back to the contact page with a message
header('Location: contact.php');
exit();
?>
