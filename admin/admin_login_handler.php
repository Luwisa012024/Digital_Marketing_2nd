<?php
session_start();
include 'db_con.php'; // Ensure this file exists and is correctly referenced

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];

    // Prepare and execute SQL statement
    $sql = "SELECT * FROM admin_users WHERE username = ?";
    
    // Check if connection is established
    if ($conn) {
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows === 1) {
                $admin = $result->fetch_assoc();

                // Verify password
                if (password_verify($password, $admin['password_hash'])) {
                    $_SESSION['is_admin'] = true;
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_username'] = $admin['username'];
                    header("Location: admin_index.php");
                    exit();
                } else {
                    // Password does not match
                    header("Location: admin_login.php?error=wrong_password");
                    exit();
                }
            } else {
                // No user found
                header("Location: admin_login.php?error=user_not_found");
                exit();
            }
        } else {
            // Statement preparation failed
            header("Location: admin_login.php?error=stmt_failed");
            exit();
        }
    } else {
        // Connection failed
        header("Location: admin_login.php?error=connection_failed");
        exit();
    }
}

$conn->close();
