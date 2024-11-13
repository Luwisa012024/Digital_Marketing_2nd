<?php
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: admin_login.php");
    exit();
}

include 'db_con.php';

// Define constants
define('UPLOAD_DIR', './admin/uploads/');
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);

// Handle product addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    // Get form inputs
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_description = $_POST['product_description'];
    $product_availability = 'Yes'; // Automatically set availability to "Yes"

    // Initialize image variable
    $product_image = '';

    // Handle image upload
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['product_image']['tmp_name'];
        $fileName = $_FILES['product_image']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Check if the file has an allowed extension
        if (in_array($fileExtension, ALLOWED_EXTENSIONS)) {
            // Ensure the uploads directory exists
            if (!is_dir(UPLOAD_DIR)) {
                mkdir(UPLOAD_DIR, 0777, true);
            }

            // Create a unique file name to avoid conflicts
            $dest_path = UPLOAD_DIR . basename($product_name . '.' . $fileExtension);

            // Move the uploaded file to the specified directory
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $product_image = basename($product_name . '.' . $fileExtension);
            } else {
                echo "Error moving the uploaded file.";
            }
        } else {
            echo "Upload failed. Allowed file types: " . implode(", ", ALLOWED_EXTENSIONS);
        }
    }

    // Insert new product into the database
    $sql = "INSERT INTO products (name, price, description, availability, image_path) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsss", $product_name, $product_price, $product_description, $product_availability, $product_image);
    $stmt->execute();
}

// Handle product deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];

    // Delete the product image file
    $imageQuery = "SELECT image_path FROM products WHERE id = ?";
    $stmt = $conn->prepare($imageQuery);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($image_path);
    $stmt->fetch();
    $stmt->close();

    if ($image_path && file_exists(UPLOAD_DIR . $image_path)) {
        unlink(UPLOAD_DIR . $image_path);
    }

    // Delete the product from the database
    $deleteQuery = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
}

// Fetch existing products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Check if the query was successful
if ($result === false) {
    die('Error executing query: ' . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Product Management - Glamour Nail Salon</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-container {
            display: flex;
            justify-content: center;
            margin: 20px;
            gap: 20px;
        }

        .add-product-box, .product-container {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 20px;
            max-width: 48%;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        .add-product-box h3, .product-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .add-product-box label, .product-container label {
            display: block;
            margin: 10px 0 5px;
        }

        .add-product-box input[type="text"], .add-product-box input[type="number"], .add-product-box input[type="file"], .add-product-box textarea,
        .product-container input[type="text"], .product-container input[type="number"], .product-container input[type="file"], .product-container textarea {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .add-product-box button, .product-container button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius : 5px;
            cursor: pointer;
        }

        .add-product-box button:hover, .product-container button:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        .delete-button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .delete-button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="admin-nav">
    <div class="logo-container">
        <img src="logo.png" alt="Logo" class="logo">
    </div>
        <ul>
            <li><a href="admin_index.php">Home</a></li>            
            <li><a href="admin_dashboard.php">Details</a></li>
            <li><a class="active" href="admin_products.php">Manage Products</a></li>
            <li><a href="admin_feedbacks.php">Customer Feedback</a></li>
            <li><a href="create_admin.php">Create Admin</a></li>
            <li><a href="admin_create_worker.php">Create Worker</a></li>
            <li><a href="admin_logout.php">Logout</a></li>
        </ul>
    </nav>

    <div class="admin-container">
        <!-- Add Product Section -->
        <div class="add-product-box">
            <h3>Add Product</h3>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                <label for="product_name">Product Name</label>
                <input type="text" id="product_name" name="product_name" required>

                <label for="product_price">Price</label>
                <input type="number" id="product_price" name="product_price" required>

                <label for="product_description">Description</label>
                <textarea id="product_description" name="product_description" rows="4" required></textarea>

                <label for="product_image">Product Image</label>
                <input type="file" id="product_image" name="product_image" accept="image/*">

                <button type="submit" name="add_product">Add Product</button>
            </form>
        </div>

        <!-- Product List Section -->
        <div class="product-container">
            <h2>Product List</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']); ?></td>
                            <td><?= htmlspecialchars($row['price']); ?></td>
                            <td><?= htmlspecialchars($row['description']); ?></td>
                            <td><img src="admin/uploads/<?= htmlspecialchars($row['image_path']); ?>" alt="Product Image" width="50" height="50"></td>
                            <td>
                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?= $row['id']; ?>">
                                    <button type="submit" name="delete_product" class="delete-button">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
