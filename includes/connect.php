<?php
// Use environment variables for Render deployment
// Fallback to localhost for local XAMPP development
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$db   = getenv('DB_NAME') ?: "mumma's_care";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Failed to connect DB: " . $conn->connect_error);
}
?>
