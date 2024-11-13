<?php
session_start();

include 'db_con.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the entered username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute the SQL query to find the worker by username
    $sql = "SELECT * FROM workers WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the worker exists
    if ($result->num_rows > 0) {
        $worker = $result->fetch_assoc();

        // Verify the password using bcrypt
        if (password_verify($password, $worker['password'])) {
            // Start a session and store worker details
            $_SESSION['is_worker'] = true;
            $_SESSION['worker_id'] = $worker['worker_id'];
            $_SESSION['username'] = $worker['username'];

            // Redirect to the worker dashboard
            header("Location: worker_index.php");
            exit();
        } else {
            // Invalid password
            header("Location: login_worker.php?error=invalid_password");
            exit();
        }
    } else {
        // No worker found with the provided username
        header("Location: login_worker.php?error=invalid_username");
        exit();
    }
}

