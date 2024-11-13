<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

include 'db_con.php';

// Fetch customer feedback
$feedbackQuery = "SELECT username, email, message, created_at FROM feedback ORDER BY created_at DESC";
$feedbackResult = $conn->query($feedbackQuery);
$feedbacks = [];
if ($feedbackResult) {
    while ($row = $feedbackResult->fetch_assoc()) {
        $feedbacks[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Feedback - Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>

        .dashboard-container {
            max-width: 1000px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .summary-box {
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            padding: 10px; /* Smaller padding */
            border-radius: 6px;
            width: calc(110% - 100px); /* Smaller width for smaller boxes */
            box-sizing: border-box;
            text-align: center;
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
            <li><a class="active" href="admin_feedbacks.php">Customer Feedback</a></li>
            <li><a href="create_admin.php">Create Admin</a></li>
            <li><a href="admin_create_worker.php">Create Worker</a></li>
            <li><a href="admin_logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="dashboard-container">
        <h2>Customer Feedback</h2>

        <!-- Customer Feedback Table -->
        <div class="summary-box">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($feedbacks)): ?>
                        <?php foreach ($feedbacks as $feedback): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($feedback['username']); ?></td>
                                <td><?php echo htmlspecialchars($feedback['email']); ?></td>
                                <td><?php echo htmlspecialchars($feedback['message']); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d H:i', strtotime($feedback['created_at']))); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No feedback available.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>