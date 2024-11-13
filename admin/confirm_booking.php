<?php
include 'db_con.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "UPDATE bookings SET status = 'Confirmed' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
    } else {
        echo "Error confirming booking.";
    }
    $stmt->close();
}

$conn->close();

