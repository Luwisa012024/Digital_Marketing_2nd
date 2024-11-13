<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

include 'db_con.php';

$successMessage = $errorMessage = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // Validate input and check passwords match
    if (!empty($username) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password) && $password === $password_confirm) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Insert the new admin into the database
        $sql = "INSERT INTO admin_users (username, password_hash, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password_hash, $email);

        if ($stmt->execute()) {
            $successMessage = "Admin account created successfully.";
        } else {
            $errorMessage = "Error creating admin account. Please try again.";
        }
        $stmt->close();
    } else {
        $errorMessage = "Please fill in all fields correctly and ensure passwords match.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin - Glamour Nail Salon</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }

        .create-admin-container {
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            margin-top: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            text-align: left;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        button {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: pink;
            color:black;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .success {
            color: #28a745;
        }

        .error {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="admin-nav">
    <div class="logo-container">
        <img src="logo.png" alt="Logo" class="logo">
    </div>
        <ul>
            <li><a href="admin_index.php">Home</a></li>
            <li><a href="admin_dashboard.php">Details</a></li>
            <li><a href="admin_products.php">Manage Products</a></li>
            <li><a  href="admin_feedbacks.php">Customer Feedback</a></li>
            <li><a class="active" href="create_admin.php">Create Admin</a></li>
            <li><a href="admin_create_worker.php">Create Worker</a></li>
            <li><a href="admin_logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="create-admin-container">
        <h2>Create Admin</h2>
        <?php if ($successMessage): ?>
            <p class="success"><?php echo $successMessage; ?></p>
        <?php elseif ($errorMessage): ?>
            <p class="error"><?php echo $errorMessage; ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            
            <label for="email">Email </label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            
            <label for="password_confirm">Confirm Password</label>
            <input type="password" id="password_confirm" name="password_confirm" required>
            
            <button type="submit">Create Admin</button>
        </form>
    </div>
</body>
</html> 