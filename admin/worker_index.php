<?php
session_start();
if (!isset($_SESSION['is_worker']) || $_SESSION['is_worker'] !== true) {
    header("Location: worker_login.php");
    exit();
}

include 'db_con.php';

// Check for database connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get worker username and worker_id from session
$workerUsername = $_SESSION['username'];

// Debugging: Output the worker username
//echo "<pre>Worker Username: $workerUsername</pre>";

// Get worker_id based on username
$workerIdQuery = "SELECT worker_id FROM workers WHERE username = ?";
$workerIdStmt = $conn->prepare($workerIdQuery);
if ($workerIdStmt === false) {
    die("Error preparing statement: " . $conn->error);
}

$workerIdStmt->bind_param("s", $workerUsername);
$workerIdStmt->execute();
$workerIdResult = $workerIdStmt->get_result();
$workerId = $workerIdResult->fetch_assoc()['worker_id'] ?? null;

if (!$workerId) {
    die("Worker not found in the database.");
}

$workerIdStmt->close();

// Get the current month
$currentMonth = date('Y-m');

// Total customers served by the worker for the current month
$totalCustomersQuery = "SELECT COUNT(*) as total_customers FROM bookings WHERE worker_id = ? AND booking_date LIKE ?";
$totalCustomersStmt = $conn->prepare($totalCustomersQuery);

if ($totalCustomersStmt === false) {
    die("Error preparing statement: " . $conn->error);
}

// Prepare the parameters for binding
$currentMonthLike = $currentMonth . '%'; // Ensure month filter works correctly
$totalCustomersStmt->bind_param("is", $workerId, $currentMonthLike);

// Execute the statement
$totalCustomersStmt->execute();
$totalCustomersResult = $totalCustomersStmt->get_result();

if ($totalCustomersResult) {
    $totalCustomers = $totalCustomersResult->fetch_assoc()['total_customers'] ?? 0;
} else {
    $totalCustomers = 0;
}

// Debugging: Output the total customers
//echo "<pre>Total Customers: $totalCustomers</pre>";

$totalCustomersStmt->close();

// Count of bookings by status (Accepted, Pending, Finished) for the current month
$statusCountsQuery = "SELECT status, COUNT(*) as count FROM bookings WHERE worker_id = ? AND booking_date LIKE ? GROUP BY status";
$statusCountsStmt = $conn->prepare($statusCountsQuery);

if ($statusCountsStmt === false) {
    die("Error preparing statement: " . $conn->error);
}

$statusCountsStmt->bind_param("is", $workerId, $currentMonthLike);
$statusCountsStmt->execute();
$statusCountsResult = $statusCountsStmt->get_result();

$statusCounts = [
    'Confirmed' => 0,  // Changed to match the possible status name in your case
    'Pending' => 0,
    'Done' => 0
];

while ($row = $statusCountsResult->fetch_assoc()) {
    // Adjust to match the actual status in the database (case-sensitive)
    if (isset($statusCounts[$row['status']])) {
        $statusCounts[$row['status']] = $row['count'];
    }
}

// Debugging: Output the status counts
//echo "<pre>Status Counts: " . print_r($statusCounts, true) . "</pre>";

$statusCountsStmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Dashboard - Glamour Nail Salon</title>
    <link rel="stylesheet" href="style.css">
    <style>
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

        .logo-container {
            margin-right: auto;
        }

        .logo {
            height: 80px;
            width: 170px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .summary-box {
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 6px;
            width: calc(100% - 90px);
            box-sizing: border-box;
            text-align: center;
        }

        .summary {
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 6px;
            width: calc(20% - 10px);
            box-sizing: border-box;
            text-align: center;
            margin-bottom: 10px;
        }

        .summary-box h3 {
            margin-bottom: 10px;
            font-size: 1.5em;
            color: #333;
        }

        .summary-box p {
            margin: 5px 0;
            font-size: 1.1em;
        }

        .dashboard-container {
            max-width: 1000px;
            margin: 0 auto;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Navigation -->
    <nav class="worker-nav">
        <div class="logo-container">
            <img src="logo.png" alt="Logo" class="logo">
        </div>
        <ul>
            <li><a class="active" href="worker_index.php">Home</a></li>            
            <li><a href="worker_dashboard.php">Bookings</a></li>
            <li><a href="logout_worker.php">Logout</a></li>
        </ul>
    </nav>

    <section id="worker-dashboard">
        <div class="dashboard-container">
            <h2>Worker Dashboard</h2>

            <!-- Monthly Summary Report Boxes -->
            <div class="summary-row">
                <div class="summary">
                    <h3>Total Customers</h3>
                    <p><?php echo $totalCustomers; ?></p>
                </div>                
                <div class="summary">
                    <h3>Accepted Clients</h3>
                    <p><?php echo $statusCounts['Confirmed']; ?></p>
                </div>

                <div class="summary">
                    <h3>Finished Clients</h3>
                    <p><?php echo $statusCounts['Done']; ?></p>
                </div>
            </div>

            <!-- Bar Chart for Booking Status Counts -->
            <div class="summary-box">
                <h3>Booking Status for <?php echo date('F Y'); ?></h3>
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </section>

    <script>
        // Prepare data for the chart
        const statusData = {
            labels: ['Accepted',  'Finished'],
            datasets: [{
                label: 'Booking Status',
                data: [
                    <?php echo $statusCounts['Confirmed']; ?>,
                    <?php echo $statusCounts['Done']; ?>
                ],
                backgroundColor: ['#3498db', '#f39c12', '#2ecc71'],
                borderColor: ['#2980b9', '#e67e22', '#27ae60'],
                borderWidth: 1
            }]
        };

        // Configurations for the chart
        const config = {
            type: 'bar',
            data: statusData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Bookings'
                        }
                    }
                }
            }
        };

        // Create the chart
        const statusChart = new Chart(
            document.getElementById('statusChart'),
            config
        );
    </script>
</body>
</html>
