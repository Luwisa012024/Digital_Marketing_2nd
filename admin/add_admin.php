<?php
// add_admin.php
include 'db_con.php'; // Ensure this file exists and is correctly referenced

// Sample admin credentials
$username = 'admin'; // Change this to your desired username
$password = '12345'; // Change this to your desired password

// Hash the password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Prepare the SQL statement to insert the admin user
$sql = "INSERT INTO admin_users (username, password_hash) VALUES (?, ?)";

if ($conn) {
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ss", $username, $password_hash);
        if ($stmt->execute()) {
            echo "Admin user created successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Statement preparation failed: " . $conn->error;
    }
} else {
    echo "Connection failed: " . $conn->connect_error;
}

$conn->close();
