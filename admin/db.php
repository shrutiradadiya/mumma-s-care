<?php
$servername = "localhost";
$username = "root";  // Your database username
$password = "";  // Your database password
$dbname = "mumma's_care";  // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
