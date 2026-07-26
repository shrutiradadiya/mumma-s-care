<?php
session_start();
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session
header('Location: home.php'); // Redirect the user to the homepage
exit();
?>
