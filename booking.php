<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    echo "<script>alert('You must be logged in to book an appointment.'); window.location.href='login_process.php';</script>";
    exit();
}

$customer_name = $service = $booking_date = $booking_time = "";
$successMessage = $errorMessage = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'admin/db_con.php';

    $customer_name = htmlspecialchars(trim($_POST['customer_name'])); // Changed to 'customer_name'
    $service = htmlspecialchars(trim($_POST['service']));
    $booking_date = htmlspecialchars(trim($_POST['booking_date']));
    $booking_time = isset($_POST['booking_time']) ? htmlspecialchars(trim($_POST['booking_time'])) : ''; // Fixed the warning
    $status = 'Pending';
    $created_at = date('Y-m-d H:i:s');

    // Validate input
    if (!empty($customer_name) && !empty($service) && !empty($booking_date) && !empty($booking_time)) {
        // Prepare SQL query to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO bookings (username, service, booking_date, booking_time, status, created_at) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $customer_name, $service, $booking_date, $booking_time, $status, $created_at);

        // Execute the prepared statement
        if ($stmt->execute()) {
            $successMessage = "Your booking has been submitted successfully!";
        } else {
            $errorMessage = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $errorMessage = "Please fill in all fields.";
    }
}

// Fetch the user's past bookings
include 'admin/db_con.php';
$username = $_SESSION['username']; // Use username to fetch bookings
$sql = "SELECT * FROM bookings WHERE username = ? ORDER BY booking_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$bookings_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment - Glamour Nail Salon</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Basic form styles */
        form input,
        form select,
        form textarea {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%; /* Ensure inputs fill their containers */
        }

        .container {
            display: flex;
            justify-content: space-between;
            gap: 30px;
            padding: 20px;
        }

        .booking-container {
            flex: 2;
        }

        .booking-monitoring-container {
            flex: 1;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .booking-monitoring {
            margin-top: 20px;
            max-height: 300px; /* Set a maximum height */
            overflow-y: auto; /* Enable vertical scroll */
        }

        .booking-monitoring table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .booking-monitoring th, .booking-monitoring td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .booking-monitoring th {
            background-color: #f1f1f1;
        }

        .nail-aesthetician {
            display: none;
            margin-top: 15px;
        }

        .nail-aesthetician.active {
            display: block;
        }

        /* Flex box layout for the booking form */
        .clearfix {
            display: flex;
            flex-wrap: wrap; /* Allow wrapping of items */
            gap:  30px;
            justify-content: space-between;
        }

        .form-column {
            flex: 1;
            min-width: 45%; /* Ensures two columns with some space in between */
        }

        /* Label alignment */
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        /* Ensure price text is bold */
        #service_price {
            font-weight: bold;
        }
    </style>
    <script>
        // Define service prices
        const servicePrices = {
            "Manicure": 250,
            "Pedicure": 300,
            "Gel Nails": 400,
            "Acrylic Nails": 500,
            "Nail Art": 350,
            "Spa Treatments": 700
        };

        function updatePrice() {
            const serviceSelect = document.getElementById("service");
            const priceDisplay = document.getElementById("service_price");
            const selectedService = serviceSelect.options[serviceSelect.selectedIndex].value;

            if (selectedService) {
                const price = servicePrices[selectedService];
                priceDisplay.innerText = `Price: ₱${price}`;
            } else {
                priceDisplay.innerText = '';
            }
        }

        function showAestheticianField(status) {
            const aestheticianField = document.getElementById("nail_aesthetician");
            if (status === 'Accepted') {
                aestheticianField.classList.add("active");
            } else {
                aestheticianField.classList.remove("active");
            }
        }
    </script>
</head>
<body>

<header>
    <div class="header-content">
        <img src="logo.png" alt="Logo" class="logo" style="margin-top:-60px; height: 200px; position:left; width:200px;">
        <p>Your nails, your style. Pamper yourself with our luxury services!</p>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="feedbacks.php">Feedbacks</a></li>
                <li><a class="active" href="booking.php">Book Now</a></li>
                <?php if (isset($_SESSION['username'])): ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<section id="booking">
    <div class="container">
        <!-- Booking Form -->
        <div class="booking-container">
            <h2>Book Your Appointment</h2>
            <?php if ($successMessage): ?>
                <p class="success"><?php echo $successMessage; ?></p>
            <?php elseif ($errorMessage): ?>
                <p class="error"><?php echo $errorMessage; ?></p>
            <?php endif; ?>

            <form method="post" action="">
                <div class="clearfix">
                    <div class="form-column">
                        <label for="customer_name">Name</label>
                        <input type="text" id="customer_name" name="customer_name" required value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
                    </div>
                    
                    <div class="form-column">
                        <label for="service">Service</label>
                        <select id="service" name="service" required onchange="updatePrice()">
                            <option value="">Select a Service</option>
                            <option value="Manicure">Manicure</option>
                            <option value="Pedicure">Pedicure</option>
                            <option value="Gel Nails">Gel Nails</option>
                            <option value="Acrylic Nails">Acrylic Nails</option>
                            <option value="Nail Art">Nail Art</option>
                            <option value="Spa Treatments">Spa Treatments</option>
                        </select>
                        <p id="service_price" style="font-weight: bold;"></p> <!-- Price display -->
                    </div>
                </div>

                <div class="clearfix">
                    <div class="form-column">
                        <label for="booking_date">Booking Date</label>
                        <input type="date" id="booking_date" name="booking_date" required>
                    </div>

                    <div class="form-column">
                        <label for="booking_time">Booking Time</label>
                        <select id="booking_time" name="booking_time" required>
                            <?php for ($i = 10; $i <= 19; $i++): ?>
                                <option value="<?php echo str_pad($i, 2, "0", STR_PAD_LEFT); ?>:00"><?php echo str_pad($i, 2, "0", STR_PAD_LEFT); ?>:00</option>
                                <option value="<?php echo str_pad($i, 2, "0", STR_PAD_LEFT); ?>:30"><?php echo str_pad($i, 2, "0", STR_PAD_LEFT); ?>:30</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn">Book Appointment</button>
            </form>
        </div>

        <!-- Booking Monitoring -->
        <div class="booking-monitoring-container">
            <h3>Your Booking History</h3>
            <div class="booking-monitoring">
                <table>
                    <tr>
                        <th>Service</th>
                        <th>Booking Date</th>
                        <th>Booking Time</th> <!-- New column for Booking Time -->
                        <th>Status</th>
                        <th>Nail Aesthetician</th>
                    </tr>
                    <?php while ($row = $bookings_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['service']); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($row['booking_date'])); ?></td> <!-- Displaying only the date -->
                            <td>
                                <?php 
                                    // Convert booking time to 12-hour format
                                    $time = date('h:i A', strtotime($row['booking_time']));
                                    echo htmlspecialchars($time); 
                                ?>
                            </td> <!-- Displaying Booking Time in 12-hour format -->
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td class="nail-aesthetician <?php echo ($row['status'] === 'Accepted') ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($row['worker']); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        </div>
    </div>
</section>

</body>
</html>