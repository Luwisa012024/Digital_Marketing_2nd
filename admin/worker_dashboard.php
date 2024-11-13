<?php
session_start();
if (!isset($_SESSION['worker_name']) || !isset($_SESSION['worker_contact']) || !isset($_SESSION['worker_id'])) {
    echo "Worker details are missing. Please log in again.";
    exit();
}

include_once 'db_con.php';

if (!$conn) {
    die("Database connection was not established.");
}

$message = "";
$message_type = "";

// Get worker_id from session
$worker_id = $_SESSION['worker_id'];

// Check and handle booking actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $booking_id = (int) $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'confirm') {
        $worker_name = $_SESSION['worker_name'];
        $worker_contact = $_SESSION['worker_contact'];
        $sql = "UPDATE bookings SET status = 'Confirmed', worker_name = ?, worker_contact = ?, worker_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $worker_name, $worker_contact, $worker_id, $booking_id);

        if ($stmt->execute()) {
            header("Location: worker_dashboard.php");
            exit();
        } else {
            $message = "Error confirming booking: " . $stmt->error;
            $message_type = "error-message";
        }
    } elseif ($action == 'done') {
        $sql = "UPDATE bookings SET status = 'Done', worker_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $worker_id, $booking_id);

        if ($stmt->execute()) {
            header("Location: worker_dashboard.php");
            exit();
        } else {
            $message = "Error marking as done: " . $stmt->error;
            $message_type = "error-message";
        }
    } elseif ($action == 'cancel') {
        $sql = "UPDATE bookings SET status = 'Canceled', worker_id = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $worker_id, $booking_id);

        if ($stmt->execute()) {
            header("Location: worker_dashboard.php");
            exit();
        } else {
            $message = "Error canceling booking: " . $stmt->error;
            $message_type = "error-message";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Dashboard - Glamour Nail Salon</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* CSS Styling */
        .action-button {
            padding: 8px 15px;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            cursor: pointer;
            color: white;
        }

        .confirm-button {
            background-color: #28a745; /* Green for confirm */
            border: 2px solid #28a745;
        }

        .confirm-button:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .cancel-button {
            background-color: #dc3545; /* Red for reject */
            color: white;
            border: 1px solid #dc3545;
        }

        .done-button {
            background-color: #007bff; /* Blue for done */
            color: white;
            border: 2px solid #007bff;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f8f9fa;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .dashboard-container {
            padding: 20px;
        }

        .success-message {
            color: green;
            font-weight: bold;
        }

        .error-message {
            color: red;
            font-weight: bold;
        }
        .worker-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #ff7cac;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 10px 0px;
        }

        .worker-nav ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .worker-nav li {
            margin-left: 10px;
        }

        .worker-nav a {
            color: #333;
            text-decoration: none;
            font-weight: bold;
            padding: 10px 15px;
        }

        .worker-nav a:hover {
            color: #555;
            background-color: #fff;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <nav class="worker-nav">
        <div class="logo-container">
            <img src="logo.png" alt="Logo" class="logo">
        </div>
        <ul>
            <li><a href="worker_index.php">Home</a></li>
            <li><a class="active" href="worker_dashboard.php">Bookings</a></li>
            <li><a href="logout_worker.php">Logout</a></li>
        </ul>
    </nav>

    <section id="worker-dashboard">
        <div class="dashboard-container">
            <h2>Worker Dashboard</h2>
            <h3>Client Bookings Overview</h3>

            <?php if ($message): ?>
                <p class="<?= $message_type; ?>"><?= htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Service</th>
                        <th>Booking Date</th>
                        <th>Booking Time</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Worker Name</th>
                        <th>Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT * FROM bookings ORDER BY booking_date DESC";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()):
                ?>
                    <tr>
                        <td><?= isset($row['id']) ? $row['id'] : 'N/A'; ?></td>
                        <td><?= htmlspecialchars($row['username']); ?></td>
                        <td><?= htmlspecialchars($row['service']); ?></td>
                        <td><?= htmlspecialchars($row['booking_date']); ?></td>
                        <td><?= htmlspecialchars($row['booking_time']); ?></td>
                        <td><?= htmlspecialchars($row['status']); ?></td>
                        <td><?= htmlspecialchars($row['amount']); ?></td>
                        <td><?= htmlspecialchars($row['worker_name']); ?></td>
                        <td><?= htmlspecialchars($row['worker_contact']); ?></td>
                        <td>
                            <?php
                            // Check if the booking is confirmed by another worker
                            if ($row['status'] == 'pending'): ?>
                                <a href="?action=confirm&id=<?= $row['id']; ?>" class="action-button confirm-button">Confirm</a>
                            <?php elseif ($row['status'] == 'Confirmed' && $row['worker_id'] == $worker_id): ?>
                                <a href="?action=done&id=<?= $row['id']; ?>" class="action-button done-button">Done</a> |
                                <a href="?action=cancel&id=<?= $row['id']; ?>" class="action-button cancel-button">Cancel</a>
                            <?php elseif ($row['status'] == 'Confirmed' && $row['worker_id'] != $worker_id): ?>
                                <!-- Booking is confirmed by another worker, show nothing -->
                                <?= htmlspecialchars($row['status']); ?>
                            <?php else: ?>
                                <?= htmlspecialchars($row['status']); ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>

<?php
$conn->close(); // Close the database connection at the end
?>
