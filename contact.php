<?php
// Include database connection
include 'admin/db_con.php';

$successMessage = $errorMessage = "";
$name = $email = $message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));
    $booking_date = date('Y-m-d H:i:s'); // Use current date and time or add an input field for users to choose a date

    // Validate input
    if (!empty($name) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Insert booking into bookings table
        $sql = "INSERT INTO bookings (username, email, message, booking_date) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $message, $booking_date);
        
        if ($stmt->execute()) {
            $successMessage = "Thank you, $name! Your booking has been received. We will contact you shortly.";
        } else {
            $errorMessage = "An error occurred. Please try again.";
        }
        $stmt->close();
    } else {
        $errorMessage = "Please fill in all fields correctly.";
    }
}

$conn->close();

