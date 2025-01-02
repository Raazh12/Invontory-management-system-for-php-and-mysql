<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card w-50">
            <div class="card-body">
                <h3 class="text-center mb-4">Add New Product</h3>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name:</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description:</label>
                        <textarea name="description" id="description" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price:</label>
                        <input type="text" name="price" id="price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="image" class="form-label">Upload Image:</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="saveData">Save Product</button>
                </form>
            </div>
        </div>
    </div>

    <?php 
    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Database connection
    $con = new mysqli("localhost", "root", "", "inventory_management_db");
    if ($con->connect_error) {
        die("Couldn't connect to the server: " . $con->connect_error);
    }

    if (isset($_POST['saveData'])) {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];

        // Handle file upload
        $image = $_FILES['image']['name'];
        $target = "uploads/" . basename($image);

        // Move the uploaded file to the designated directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $sql = "INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("ssds", $name, $description, $price, $target);
            if ($stmt->execute()) {
                echo "<script>alert('Product inserted successfully'); window.location.href='select.php';</script>";
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Failed to upload image.";
        }
    }   
    ?>
</body>
</html>