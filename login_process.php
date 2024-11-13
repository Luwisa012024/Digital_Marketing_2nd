<?php
session_start();
include 'admin/db_con.php'; // Include database connection file

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Validate input
    if (!empty($username) && !empty($password)) {
        // Query to check if the user exists
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows == 1) {
            // Fetch user data
            $user = $result->fetch_assoc();

            // Verify the password with MD5 hash
            if (md5($password) === $user['password_hash']) {
                // Store session data
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['is_logged_in'] = true; // Set the logged-in session variable

                // Redirect to the referring page or fallback page
                $redirect_page = isset($_SESSION['redirect_page']) ? $_SESSION['redirect_page'] : 'index.php';
                header("Location: " . $redirect_page);
                exit();
            } else {
                $errorMessage = "Invalid password. Please try again.";
            }
        } else {
            $errorMessage = "No account found with that username.";
        }
    } else {
        $errorMessage = "Please enter both username and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Glamour Nail Salon</title>
    <link rel="stylesheet" href="styles.css">
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

        #logo-container {
            margin-bottom: 20px; /* Space between logo and form */
            margin: 0px;
            margin-top: -60px;
        }

        .logo {
            width: 200px; /* Adjust the logo size */
            margin: 0px;
        }

        #login {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        #login h2 {
            margin-bottom: 20px;
            color: #f15da7;
        }

        .error {
            color: red;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-top: 10px;
            text-align: left;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #f15da7;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #e14b8d;
        }

        p {
            margin-top: 20px;
        }

        a {
            color: #f15da7;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div id="logo-container">
    <img src="logo.png" alt="Glamour Nail Salon Logo" class="logo"> <!-- Logo -->
</div>

<section id="login">
    <h2>Login to Your Account</h2>

    <?php if (isset($errorMessage)): ?>
        <p class="error"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="signup.php">Register here</a>.</p>
</section>

</body>
</html>
