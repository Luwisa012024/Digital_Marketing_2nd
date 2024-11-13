<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

include 'db_con.php';

$message = ''; // Variable to hold the message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $worker_name = $_POST['worker_name'];
    $lastName = $_POST['last_name'];
    $phoneNumber = $_POST['phone_number'];

    $sql = "INSERT INTO workers (username, password, worker_name, last_name, worker_contact) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $username, $password, $worker_name, $lastName, $phoneNumber);

    if ($stmt->execute()) {
        $message = "<p class='success-message'>Nail aesthetician login created successfully.</p>";
    } else {
        $message = "<p class='error-message'>Error: " . $stmt->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Worker - Glamour Nail Salon</title>
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

        /* Center and style the form */
        .create-worker-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            width: 100%;
            padding-top: 20px;
        }

        .worker-form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 800px; /* Increase width for 2-column layout */
        }

        .worker-form h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        /* Use grid for two columns */
        .worker-form .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Two equal columns */
            gap: 15px;
            margin-bottom: 20px;
        }

        .worker-form label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .worker-form input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .worker-form .form-row input {
            margin-bottom: 0;
        }

        .worker-form button {
            width: 100%;
            padding: 10px;
            background-color: #ff7cac;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .worker-form button:hover {
            background-color: pink;
        }

        .success-message, .error-message {
            text-align: center;
            font-size: 14px;
            margin-top: 15px;
        }

        .success-message {
            color: green;
        }

        .error-message {
            color: red;
        }

        /* Ensure form labels and inputs align properly */
        .worker-form .form-row input {
            margin-bottom: 15px;
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
            <li><a  href="create_admin.php">Create Admin</a></li>
            <li><a class="active" href="admin_create_worker.php">Create Worker</a></li>
            <li><a href="admin_logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="create-worker-container">
        <form class="worker-form" action="" method="POST">
            <!-- Display success or error message -->
            <?php if (!empty($message)) echo $message; ?>
            <h2>Create Worker Account</h2>
            <div class="form-row">
                <div>
                    <label for="worker_name">First Name:</label>
                    <input type="text" id="worker_name" name="worker_name" required>
                </div>
                <div>
                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
            </div>

            <div class="form-row">
                <div>
                    <label for="phone_number">Phone Number:</label>
                    <input type="text" id="phone_number" name="phone_number" required>
                </div>
            </div>

            <button type="submit">Create Worker</button>
        </form>
    </div>
</body>
</html>
