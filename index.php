<?php
session_start();

// Initialize variables
$name = $email = $message = "";
$successMessage = $errorMessage = "";

// Include the database connection file
include 'admin/db_con.php'; // Assumes db_con.php contains the database connection

// Check if the form for booking is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_appointment'])) {
    $name = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));
    $bookingDate = date('Y-m-d H:i:s'); // Get the current date and time

    // Validate input
    if (!empty($name) && !empty($email) && !empty($message) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Prepare SQL statement to insert data into the bookings table
        $stmt = $conn->prepare("INSERT INTO bookings (name, email, message, booking_date, status) VALUES (?, ?, ?, ?, ?)");
        $status = 'Pending'; // Default status for new bookings
        $stmt->bind_param("sssss", $name, $email, $message, $bookingDate, $status);

        // Execute the statement
        if ($stmt->execute()) {
            $successMessage = "Thank you for your message, $name! We will get back to you soon.";
            // Clear the form fields
            $name = $email = $message = "";
        } else {
            $errorMessage = "Something went wrong. Please try again later.";
        }

        // Close the statement
        $stmt->close();
    } else {
        $errorMessage = "Please fill in all fields correctly.";
    }
}

// Fetch existing services
$sql = "SELECT * FROM products";
$service_result = $conn->query($sql);

// Fetch feedbacks (limit to 4 results)
$feedbackQuery = "SELECT * FROM feedback ORDER BY created_at DESC"; // Order by most recent
$feedback_result = $conn->query($feedbackQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glamour Nail Salon</title>
    <link rel="stylesheet" href="styles.css">
    <style>
    header {
        background: #f15da7;
        color: white;
        padding: 30px 0;
        text-align: center;
        position: relative; /* Allow absolute positioning of the logo */
    }
    #feedbacks {
        margin-top: 40px;
        text-align: center; /* Center the feedback section */
    }
    .feedback-container {
        display: flex; /* Arrange feedbacks in a row */
        overflow-x: auto; /* Enable horizontal scrolling */
        padding: 10px;
        justify-content: start; /* Align feedbacks to the start of the container */
        gap: 10px; /* Add space between feedback items */
    }
    .feedback {
        background: #f9f9f9;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        width: 280px; /* Width of each feedback box */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1); /* Add shadow for depth */
        flex-shrink: 0; /* Prevent shrinking of the feedback item */
    }
    .feedback h4 {
        color: #f15da7;
        font-size: 1.3em;
        margin-bottom: 10px;
    }
    .feedback p {
        font-size: 1.1em;
        margin-bottom: 10px;
    }
    .feedback small {
        color: #555;
        font-style: italic;
    }
    @media (max-width: 768px) {
        .feedback {
            width: 100%; /* On smaller screens, feedbacks take full width */
        }
    }
    </style>
</head>
<body>

    <!-- Header Section -->
    <header>
        <div class="header-content">
            <img src="logo.png" alt="Logo" class="logo" style="margin-top:-60px; height: 200px; position:left; width:200px;">
            <p>Your nails, your style. Pamper yourself with our luxury services!</p>
            <!-- Navigation Menu -->
            <nav>
                <ul>
                    <li><a class="active" href="index.php">Home</a></li>
                    <li><a href="feedbacks.php">Feedbacks</a></li>
                    <li><a href="booking.php">Book Now</a></li>
                    <?php if (isset($_SESSION['username'])): ?>
                        <li><a href="logout.php">Logout</a></li> <!-- Show Logout if logged in -->
                    <?php else: ?>
                        <li><a href="login_process.php">Login</a></li> <!-- Show Login if not logged in -->
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    
    <!-- Services Section -->
    <section id="services">
        <div class="container">
            <h1 style="font-size: 30px;">Our Nail Services</h1>
            <div class="service-list">
                <?php while ($service_row = $service_result->fetch_assoc()): ?>
                    <div class="service">
                        <?php
                        // Check if the 'image_path' key exists and has a value
                        if (isset($service_row['image_path']) && !empty($service_row['image_path'])) {
                            $imagePath = './admin/uploads/' . $service_row['image_path']; // Correct path to image
                            // Check if the image file exists
                            if (file_exists($imagePath)) {
                                echo "<img src='$imagePath' alt='" . htmlspecialchars($service_row['name']) . "'>";
                            } else {
                                echo "<img src='default-image.jpg' alt='Default Image'>";
                            }
                        } else {
                            echo "<img src='default-image.jpg' alt='Default Image'>";
                        }
                        ?>
                        <h3><?php echo htmlspecialchars($service_row['name']); ?></h3>
                        <p><?php echo htmlspecialchars($service_row['description']); ?></p>
                        <p><strong>Price: ₱<?php echo number_format($service_row['price'], 2); ?></strong></p> <!-- Display price -->
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <!-- Feedback Section -->
    <section id="feedbacks">
        <div class="container">
            <h2>Client Feedbacks</h2>
            <div class="feedback-container">
                <?php if ($feedback_result->num_rows > 0): ?>
                    <?php while ($feedback = $feedback_result->fetch_assoc()): ?>
                        <div class="feedback">
                            <h4><?php echo htmlspecialchars($feedback['username']); ?> </h4>
                            <p><?php echo nl2br(htmlspecialchars($feedback['message'])); ?></p>
                            <small>Posted on: <?php echo date('F j, Y, g:i a', strtotime($feedback['created_at'])); ?></small>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No feedbacks available yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 Glamour Nail Salon. All Rights Reserved.</p>
            <p>Address: 123 Nail St, BGC, Building 1234</p> <!-- Add your salon's address here -->
        </div>
    </footer>

</body>
</html>
