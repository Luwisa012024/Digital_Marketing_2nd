<?php
// Include database connection
include 'admin/db_con.php';

// Initialize variables
$successMessage = $errorMessage = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize inputs
    $customer_ = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $service = htmlspecialchars(trim($_POST['service']));
    $booking_date = htmlspecialchars(trim($_POST['booking_date']));

    // Validate form inputs
    if (!empty($customer_name) && !empty($email) && !empty($service) && !empty($booking_date) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Prepare SQL to insert booking
        $sql = "INSERT INTO booking (customer_name, service, booking_date, status, created_at) VALUES (?, ?, ?, 'Pending', NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $customer_name, $service, $booking_date);

        // Execute and check if successful
        if ($stmt->execute()) {
            $successMessage = "Thank you, $customer_name! Your booking has been received.";
            header("Location: booking.html?successMessage=" . urlencode($successMessage));
            exit();
        } else {
            $errorMessage = "Failed to book your appointment. Please try again.";
        }
        $stmt->close();
    } else {
        $errorMessage = "Please fill in all fields correctly.";
    }
    $conn->close();
}

