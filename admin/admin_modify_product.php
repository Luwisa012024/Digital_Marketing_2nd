<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

include 'db_con.php';

// Define the default image path
$default_image = 'admin/uploads/default_manicure.jpg';

// Check if 'product_id' is passed in the URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Fetch product details from the database
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if product is found
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        die('Product not found!');
    }
} else {
    die('Product ID is missing!');
}

// Handle product modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modify_product'])) {
    // Get form inputs
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_description = $_POST['product_description'];
    $product_availability = $_POST['product_availability'];
    $product_image = $product['image_path'] ? $product['image_path'] : $default_image; // Use the existing image or default image

    // Handle image upload
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['product_image']['tmp_name'];
        $fileName = $_FILES['product_image']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Specify the allowed file extensions
        $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        // Check if the file has an allowed extension
        if (in_array($fileExtension, $allowedfileExtensions)) {
            // Define the upload directory
            $uploadFileDir = './uploads/';

            // Ensure the uploads directory exists
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0777, true);
            }

            // Create a unique file name to avoid conflicts
            $dest_path = $uploadFileDir . $product_name . '.' . $fileExtension;

            // Move the uploaded file to the specified directory
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $product_image = $dest_path;
            } else {
                echo "Error moving the uploaded file.";
            }
        } else {
            echo "Upload failed. Allowed file types: " . implode(", ", $allowedfileExtensions);
        }
    }

    // Update product details in the database
    $sql = "UPDATE products SET name = ?, price = ?, description = ?, availability = ?, image_path = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsssi", $product_name, $product_price, $product_description, $product_availability, $product_image, $product_id);
    $stmt->execute();

    // Redirect back to the admin page after successful update
    header("Location: admin_index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify Product - Glamour Nail Salon</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .modify-product-box {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 20px;
            width: 50%;
            margin: 0 auto;
            border-radius: 10px;
        }

        .modify-product-box h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        .modify-product-box label {
            display: block;
            margin: 10px 0 5px;
        }

        .modify-product-box input[type="text"],
        .modify-product-box input[type="number"],
        .modify-product-box input[type="file"],
        .modify-product-box textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .modify-product-box button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .modify-product-box button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="admin-nav">
        <ul>
            <li><a href="admin_index.php">Home</a></li>
            <li><a href="admin_logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="modify-product-box">
        <h3>Modify Product Details</h3>
        <form action="admin_modify_product.php?product_id=<?= $product['id'] ?>" method="POST" enctype="multipart/form-data">
            <label for="product_name">Product Name</label>
            <input type="text" name="product_name" value="<?= htmlspecialchars($product['name']); ?>" required>

            <label for="product_price">Product Price</label>
            <input type="number" name="product_price" value="<?= htmlspecialchars($product['price']); ?>" required>

            <label for="product_description">Product Description</label>
            <textarea name="product_description" rows="4" required><?= htmlspecialchars($product['description']); ?></textarea>

            <label for="product_image">Product Image</label>
            <input type="file" name="product_image">

            <label for="product_availability">Product Availability</label>
            <select name="product_availability" required>
                <option value="Yes" <?= $product['availability'] == 'Yes' ? 'selected' : ''; ?>>Yes</option>
                <option value="No" <?= $product['availability'] == 'No' ? 'selected' : ''; ?>>No</option>
            </select>

            <button type="submit" name="modify_product">Modify Product</button>
        </form>
    </div>
</body>
</html>
