<?php
// Include any necessary PHP code for handling the signup process, like validation, error handling, etc.
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Glamour Nail Salon</title>
    <link rel="stylesheet" href="styles.css">
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
        #logo-container {
            margin-bottom: 10px; /* Space between logo and form */
            margin: 0px;
            margin-top: -60px;
        }

        .logo {
            width: 200px; /* Adjust the logo size */
            margin: 0px;
        }

        #signup {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px; /* Adjust max width for larger forms */
            text-align: center;
        }

        #signup h2 {
            margin-bottom: 20px;
            color: #f15da7;
        }

        .error {
            color: red;
            margin-bottom: 20px;
        }

        /* Grid layout for the form */
        .form-container {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Two columns */
            gap: 20px;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }

        .form-container label {
            display: block;
            font-weight: bold;
            text-align: left;
        }

        .form-container input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container .full-width {
            grid-column: span 2; /* This spans the input to both columns */
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #f15da7;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #e14b8d;
        }

        p {
            margin-top: 20px;
        }

        a {
            color: #f15da7;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div id="logo-container">
    <img src="logo.png" alt="Glamour Nail Salon Logo" class="logo"> <!-- Logo -->
</div>

<section id="signup">
    <h2>Create a New Account</h2>

    <?php if (isset($errorMessage)): ?>
        <p class="error"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <form method="post" action="signup_process.php">
        <div class="form-container">
            <div>
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div>
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
        </div>

        <button type="submit">Sign Up</button>
    </form>

    <p>Already have an account? <a href="login_process.php">Login here</a></p>
</section>

</body>
</html>
