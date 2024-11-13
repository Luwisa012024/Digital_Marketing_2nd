<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

include 'db_con.php';

if (!$conn) {
    die("Database connection was not established.");
}

$currentMonth = date('Y-m');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Glamour Nail Salon</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="admin-nav">
        <div class="logo-container">
            <img src="logo.png" alt="Logo" class="logo">
        </div>
        <ul>
            <li><a href="admin_index.php">Home</a></li>            
            <li><a class="active" href="admin_dashboard.php">Details</a></li>
            <li><a href="admin_products.php">Manage Products</a></li>
            <li><a href="admin_feedbacks.php">Customer Feedback</a></li>
            <li><a href="create_admin.php">Create Admin</a></li>
            <li><a href="admin_create_worker.php">Create Worker</a></li>
            <li><a href="admin_logout.php">Logout</a></li>
        </ul>
    </nav>

    <section id="admin-dashboard">
        <div class="dashboard-container">
            <h2>Admin Dashboard</h2>
            <h3>Bookings Overview</h3>
            <table>
                <thead>
                    <tr>
                        <th>Name</th> <!-- Updated to Username -->
                        <th>Service</th>
                        <th>Price</th>
                        <th>Booking Date</th>
                        <th>Booking Hour</th> <!-- New Column for Booking Hour -->
                        <th>Status</th>
                        <th>Aesthetician Name</th>
                        <th>Aesthetician Contact</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT b.*, a.worker_name AS worker_name, a.worker_contact AS worker_contact 
                        FROM bookings b 
                        LEFT JOIN workers a ON b.worker_id = a.worker_id 
                        ORDER BY b.booking_date DESC";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo isset($row['username']) ? $row['username'] : 'N/A'; ?></td>
                        <td><?php echo isset($row['service']) ? $row['service'] : 'N/A'; ?></td>
                        <td><?php echo isset($row['amount']) ? '₱' . number_format($row['amount'], 2) : 'N/A'; ?></td> 
                        <td><?php echo isset($row['booking_date']) ? $row['booking_date'] : 'N/A'; ?></td>
                        <td><?php echo isset($row['booking_time']) ? date('h:i A', strtotime($row['booking_time'])) : 'N/A'; ?></td>
                        <td><?php echo isset($row['status']) ? $row['status'] : 'N/A'; ?></td>
                        <td><?php echo isset($row['worker_name']) ? $row['worker_name'] : 'N/A'; ?></td>
                        <td><?php echo isset($row['worker_contact']) ? $row['worker_contact'] : 'N/A'; ?></td><!-- Displaying Price -->
                        <td>
                            <?php if (strtolower($row['status']) !== 'done'): // Check if status is not 'done' ?>
                                <a href="confirm_booking.php?id=<?php echo $row['id']; ?>">Confirm</a> |
                                <a href="cancel_booking.php?id=<?php echo $row['id']; ?>">Cancel</a>
                            <?php else: ?>
                                <span>Done</span> <!-- Or you can leave it empty -->
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