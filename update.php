<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="mb-4">Update Product</h1>
        <?php
        // Database connection
        $con = new mysqli("localhost", "root", "", "inventory_management_db");
        if ($con->connect_error) {
            die("Couldn't connect to the server: " . $con->connect_error);
        }

        // Fetch existing product details
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM products WHERE id=?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product = $result->fetch_assoc();

            if (!$product) {
                echo "<p>Product not found.</p>";
                exit;
            }
        }

        // Handle update
        if (isset($_POST['updateData'])) {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $image = $_FILES['image']['name'];
            $target = "uploads/" . basename($image);

            // If a new image is uploaded, update the image path
            if ($image) {
                move_uploaded_file($_FILES['image']['tmp_name'], $target);
                $sql = "UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("ssdsi", $name, $description, $price, $target, $id);
            } else {
                $sql = "UPDATE products SET name=?, description=?, price=? WHERE id=?";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("ssdi", $name, $description, $price, $id);
            }

            if ($stmt->execute()) {
                echo "<script>alert('Product updated successfully'); window.location.href='select.php';</script>";
            } else {
                echo "<script>alert('Error updating product: " . $stmt->error . "');</script>";
            }
        }
        ?>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name:</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea name="description" id="description" class="form-control" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price:</label>
                <input type="text" name="price" id="price" class="form-control" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Upload New Image (optional):</label>
                <input type="file" name="image" id="image" class="form-control" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary" name="updateData">Update Product</button>
        </form>
    </div>
</body>
</html>