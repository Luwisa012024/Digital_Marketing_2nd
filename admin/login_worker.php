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
            $_SESSION['worker_name'] = $worker['worker_name'];  // Store worker's name
            $_SESSION['worker_contact'] = $worker['worker_contact'];  // Store worker's contact

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
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Login - Glamour Nail Salon</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f9f9f9;
            flex-direction: column;
        }

        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            text-align: center;
        }

        .login-form {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-form h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .login-form label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .login-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .login-form button {
            width: 100%;
            padding: 10px;
            background-color: #ff7cac;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .login-form button:hover {
            background-color: pink;
        }

        .error-message {
            text-align: center;
            color: red;
            font-size: 14px;
            margin-top: 15px;
        }
        #logo-container {
            margin-bottom: 10px; /* Space between logo and form */
            margin:0;
            margin-top: -80px;
        }

        .logo {
            width: 300px; /* Adjust the logo size */
            height: 250px;
            margin: 0 auto; /* Center the logo */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div id="logo-container">
            <img src="logo.png" alt="Glamour Nail Salon Logo" class="logo"> <!-- Logo -->
        </div>
        <form class="login-form" action="" method="POST">
            <h2>Worker Login</h2>
                        <!-- Error message -->
             <?php if (isset($error_message)) { ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php } ?>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Login</button>
            <div>
        <a href="admin_login.php" style="color: blue; text-decoration: none; ">Login as Admin</a>
    </div>


        </form>


    </div>
</body>
</html>
