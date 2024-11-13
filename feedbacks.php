<?php
session_start();
include 'admin/db_con.php'; // Include database connection file

// Check if the user is logged in
if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
    header("Location: login_process.php");  // Redirect to the login page if not logged in
    exit();
}

// Get user details from session
$name = $_SESSION['username']; // Assuming 'username' holds the user's name
$email = isset($_SESSION['email']) ? $_SESSION['email'] : ''; // Ensure 'email' is set
$message = "";
$successMessage = $errorMessage = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = htmlspecialchars(trim($_POST['message']));

    // Validate input
    if (!empty($message)) {
        // Insert feedback into the database
        $sql = "INSERT INTO feedback (username, email, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $message);

        if ($stmt->execute()) {
            $successMessage = "Thank you for your feedback, $name! We appreciate your input.";
        } else {
            $errorMessage = "There was an error submitting your feedback. Please try again.";
        }
    } else {
        $errorMessage = "Please fill in the feedback message.";
    }
}

// Fetch feedback from the database
$feedbacks = [];
$sql = "SELECT username, message, created_at FROM feedback ORDER BY created_at DESC";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $feedbacks[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Testimonials - Glamour Nail Salon</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .container {
            width: 80%;
            display: flex;
            justify-content: space-between;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 30px;
        }
        .form-container, .feedback-container {
            width: 48%; /* Adjust width to leave space for margins */
        }
        .feedback-container {
            height: 400px; /* Set a fixed height */
            overflow-y: auto; /* Enable vertical scrolling */
            padding: 10px; /* Optional: add some padding */
            border: 1px solid #ccc; /* Optional: add a border */
            border-radius: 4px; /* Optional: add rounded corners */
            background-color: #f9f9f9; /* Optional: background color */
        }
        h2 {
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 8px;
            font-weight: bold;
        }
        textarea {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }
        .success {
            color: green;
            text-align: center;
        }
        .feedback-item {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f9f9f9;
        }
        .feedback-item h4 {
            margin: 0;
            font-size: 1.2em;
        }
        .feedback-item p {
            margin: 5px 0;
        }
        .feedback-item small {
            color: #777;
        }
    </style>
</head>
<body>

<header>
<div class="header-content">
<img src="logo.png" alt="Logo" class="logo" style="margin-top:-60px; height: 200px; position:left; width:200px;">
<p>Your nails, your style. Pamper yourself with our luxury services!</p>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a class="active" href="feedbacks.php">Feedbacks</a></li>
            <li><a href="booking.php">Book Now</a></li>
            <?php if (isset($_SESSION['username'])): ?>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
</header>

    <div class="container">
        <div class="form-container">
            <h2>Share Your Feedback</h2>
            <?php if ($successMessage): ?>
                <p class="success"><?php echo $successMessage; ?></p>
            <?php elseif ($errorMessage): ?>
                <p class="error"><?php echo $errorMessage; ?></p>
            <?php endif; ?>
            <form method="post" action="">
                <label for="message">Your Feedback</label>
                <textarea id="message" name="message" required><?php echo $message; ?></textarea>
                
                <button type="submit">Submit Feedback</button>
            </form>
        </div>

        <div class="feedback-container">
            <h2>Customer Feedback</h2>
            <?php if (empty($feedbacks)): ?>
                <p>No feedback available yet.</p>
            <?php else: ?>
                <?php foreach ($feedbacks as $feedback): ?>
                    <div class="feedback-item">
                        <h4><?php echo htmlspecialchars($feedback['username']); ?></h4>
                        <p><?php echo htmlspecialchars($feedback['message']); ?></p>
                        <small>Submitted on <?php echo date('F j, Y, g:i a', strtotime($feedback['created_at'])); ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
