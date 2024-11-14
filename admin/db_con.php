<?php
// Local settings for testing or traditional deployment
$db_host = "localhost"; // Your database host
$db_user = "root"; // Your database username (default for XAMPP)
$db_password = ""; // Your database password (leave empty for XAMPP default)
$db_name = "glamour_nail_salon"; // Your database name

// Connect to the database
$conn = new mysqli($db_host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set to avoid charset issues
$conn->set_charset("utf8mb4");
?>
