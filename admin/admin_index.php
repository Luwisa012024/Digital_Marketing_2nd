<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

include 'db_con.php';

// Get the current month
$currentMonth = date('Y-m');

// Total customers for the current month
$totalCustomersQuery = "SELECT COUNT(*) as total_customers FROM bookings WHERE booking_date LIKE '$currentMonth%'";
$totalCustomersResult = $conn->query($totalCustomersQuery);
$totalCustomers = $totalCustomersResult->fetch_assoc()['total_customers'];

// Count of bookings by status
$statusCountsQuery = "SELECT status, COUNT(*) as count FROM bookings WHERE booking_date LIKE '$currentMonth%' GROUP BY status";
$statusCountsResult = $conn->query($statusCountsQuery);

// Initialize status counts
$statusCounts = [
    'Pending' => 0,
    'Confirmed' => 0,
    'Done' => 0,
    'Cancelled' => 0 // Ensure cancelled clients are also included
];

// Fetch status counts
while ($row = $statusCountsResult->fetch_assoc()) {
    // Convert status to proper case
    $statusKey = ucfirst(strtolower($row['status'])); // Convert to 'Pending', 'Ongoing', etc.
    
    if (array_key_exists($statusKey, $statusCounts)) {
        $statusCounts[$statusKey] = $row['count'];
    }
}

// Calculate total revenue for the current month by summing the amount of 'Done' bookings
$revenueQuery = "SELECT SUM(amount) as total_revenue FROM bookings WHERE booking_date LIKE '$currentMonth%' AND status = 'Done'";
$revenueResult = $conn->query($revenueQuery);

// Fetch the total revenue
$totalRevenue = $revenueResult->fetch_assoc()['total_revenue'] ?? 0; // Default to 0 if no results

// Get worker statistics, correctly counting 'Done' clients
$workerStatsQuery = "
    SELECT 
        w.worker_id, 
        w.worker_name, 
        w.last_name, 
        SUM(CASE WHEN b.status = 'Done' THEN 1 ELSE 0 END) as finished_count,
        COUNT(b.id) as total_clients
    FROM 
        workers w
    LEFT JOIN 
        bookings b ON w.worker_id = b.worker_id
    WHERE 
        b.booking_date LIKE '$currentMonth%' 
    GROUP BY 
        w.worker_id
";
$workerStatsResult = $conn->query($workerStatsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Glamour Nail Salon</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .summary-row {
            display: flex;
            justify-content: space-between; /* Distributes space between boxes */
            flex-wrap: wrap; /* Allows wrapping for smaller screens */
            gap: 10px; /* Adds space between the boxes */
        }
        .summary {
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            padding: 10px; /* Smaller padding */
            border-radius: 6px;
            width: calc(20% - 10px); /* Smaller width for smaller boxes */
            box-sizing: border-box;
            text-align: center;
            margin-bottom: 10px;
        }
        .dashboard-container {
            width: 1200px;
            margin: 0 auto;
        }
        /* Two-column layout */
        .two-column {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
        .column {
            flex: 1; /* Each column takes equal width */
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow-y: auto; /* Enable vertical scrolling */
        }
        .summary-box {
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            padding : 10px; /* Smaller padding */
            border-radius: 6px;
            width: calc(122% - 100px); /* Smaller width for smaller boxes */
            box-sizing: border-box;
            text-align: center;
        }
    </style>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Navigation -->
    <nav class="admin-nav">
        <div class="logo-container">
            <img src="logo.png" alt="Logo" class="logo">
        </div>
        <ul>
            <li><a class="active" href="admin_index.php">Home</a></li>
            <li><a href="admin_dashboard.php">Details</a></li>
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

            <!-- Monthly Summary Report Boxes -->
            <div class="summary-row">
                <div class="summary">
                    <h3>Total Customers</h3>
                    <p><?php echo $totalCustomers; ?></p>
                </div>
                <div class="summary">
                    <h3>Pending Clients</h3>
                    <p><?php echo $statusCounts['Pending']; ?></p>
                </div>
                <div class="summary">
                    <h3>Ongoing Clients</h3>
                    <p><?php echo $statusCounts['Confirmed']; ?></p>
                </div>
                <div class="summary">
                    <h3>Finished Clients</h3>
                    <p><?php echo $statusCounts['Done']; ?></p>
                </div>
                <div class="summary">
                    <h3>Total Revenue</h3>
                    <p>₱<?php echo number_format($totalRevenue, 2); ?></p>
                </div>
            </div>  
            <!-- Two-Column Layout -->
            <div class="two-column">
                <!-- Worker Statistics -->
                <div class="column">
                    <h2>Worker Statistics</h2>
                    <div class="summary-row">
                        <?php while ($worker = $workerStatsResult->fetch_assoc()): ?>
                            <div class="summary">
                                <h3><?php echo htmlspecialchars($worker['worker_name'] . ' ' . $worker['last_name']); ?></h3>
                                <p>Total Clients: <?php echo $worker['total_clients']; ?></p>
                                <p>Finished Clients: <?php echo $worker['finished_count']; ?></p>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <!-- Client Monitoring with Bar Chart -->
                <div class="column">
                    <h2>Client Monitoring</h2>
                    <!-- Bar Chart for Customer Status Counts -->
                    <div class="summary-box">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Client Status Bar Chart
        const ctx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pending', 'Ongoing', 'Finished', 'Cancelled'],
                datasets: [{
                    label: 'Booking Status Distribution',
                    data: [
                        <?php echo $statusCounts['Pending']; ?>,
                        <?php echo $statusCounts['Confirmed']; ?>,
                        <?php echo $statusCounts['Done']; ?>,
                        <?php echo $statusCounts['Cancelled']; ?>
                    ],
                    backgroundColor: ['#FF5733', '#FF8C00', '#28a745', '#6c757d'],
                    borderColor: ['#FF5733', '#FF8C00', '#28a745', '#6c757d'],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
