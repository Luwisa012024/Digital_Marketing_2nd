<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Glamour Nail Salon</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f9f9f9;
            flex-direction: column;
        }

        .container {
            width: 100%; /* Full width */
            max-width: 400px; /* Maximum width for the login container */
            background-color: white; /* White background for the login form */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
            padding: 20px; /* Padding inside the container */
            text-align: center; /* Center the text */
        }

        .login-container {
            margin-top: 10px; /* Add space between the logo and form */
        }

        h2 {
            margin-bottom: 20px; /* Space below the heading */
        }

        label {
            display: block; /* Make labels block elements */
            margin-bottom: 5px; /* Space below labels */
        }

        input[type="text"],
        input[type="password"] {
            width: 90%; /* Full width inputs */
            padding: 10px; /* Padding inside inputs */
            margin-bottom: 15px; /* Space below inputs */
            border: 1px solid #ccc; /* Border for inputs */
            border-radius: 4px; /* Rounded corners for inputs */
        }

        button {
            width: 100%; /* Full width button */
            padding: 10px; /* Padding inside button */
            background-color: #5cb85c; /* Bootstrap success color */
            color: white; /* White text */
            border: none; /* No border */
            border-radius: 4px; /* Rounded corners for button */
            cursor: pointer; /* Pointer cursor on hover */
        }

        button:hover {
            background-color: #4cae4c; /* Darker green on hover */
        }

        .error {
            color: red; /* Red color for error messages */
            margin-top: 10px; /* Space above error message */
        }

        #logo-container {
            margin-bottom: 10px; /* Space between logo and form */
            margin:0;
            margin-top: -50px;
        }

        .logo {
            width: 300px; /* Adjust the logo size */
            height: 250px;
            margin: 0 auto; /* Center the logo */
        }
    </style>
</head>
<body>
    <div id="logo-container">
        <img src="logo.png" alt="Glamour Nail Salon Logo" class="logo"> <!-- Logo -->
    </div>
    <div class="container"> <!-- Outer container for centering -->
        <div class="login-container">
            <h2>Admin Login</h2>
            <form action="admin_login_handler.php" method="post">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
                
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit">Login</button>
                <?php if (isset($_GET['error'])): ?>
                    <p class="error">Invalid username or password.</p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
