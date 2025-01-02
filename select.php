<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container my-5">
        <h1 class="mb-4">Product List</h1>
        <a href="insert.php" class="btn btn-primary mb-3">Add New Product</a>
        <div class="row">
            <?php
            $con = new mysqli("localhost", "root", "", "inventory_management_db");
            if ($con->connect_error) {
                die("Couldn't connect to the server: " . $con->connect_error);
            }

            // Handle deletion
            if (isset($_GET['did'])) {
                $pid = $_GET['did'];
                $sql = "DELETE FROM products WHERE id=?";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("i", $pid);
                if ($stmt->execute()) {
                    echo "<script>alert('Data with ID $pid was deleted successfully'); window.location.href='select.php';</script>";
                }
            }

            // Handle update
            if (isset($_POST['updateData'])) {
                $fn = $_POST['name'];
                $add = $_POST['description'];
                $ph = $_POST['price'];
                $sd = $_POST['updateId'];
                $imgs = $_FILES['image']['name'];
                $target = "uploads/" . basename($imgs);

                // If a new image is uploaded, update the image path
                if ($imgs) {
                    move_uploaded_file($_FILES['image']['tmp_name'], $target);
                    $sqlupdate = "UPDATE products SET name=?, description=?, price=?, image=? WHERE id=?";
                    $stmt = $con->prepare($sqlupdate);
                    $stmt->bind_param("ssdsi", $fn, $add, $ph, $target, $sd);
                } else {
                    $sqlupdate = "UPDATE products SET name=?, description=?, price=? WHERE id=?";
                    $stmt = $con->prepare($sqlupdate);
                    $stmt->bind_param("ssdi", $fn, $add, $ph, $sd);
                }

                if ($stmt->execute()) {
                    header("location: select.php");
                    exit; // Ensure no further code is executed
                } else {
                    echo "<script>alert('Error updating product: " . $stmt->error . "');</script>";
                }
            }

            // Fetch products
            $sql = "SELECT * FROM products";
            $result = $con->query($sql);
            if (!$result) {
                die("Query failed: " . $con->error);
            }

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_array()) {
            ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top" alt="Product Image" style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                        <p class="card-text"><strong>Price:</strong> $<?php echo htmlspecialchars($row['price']); ?></p>
                        <a href="product_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-info">View Details</a>
                        <a href="select.php?did=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal" 
                            data-id="<?php echo $row['id']; ?>" 
                            data-name="<?php echo htmlspecialchars($row['name']); ?>" 
                            data-description="<?php echo htmlspecialchars($row['description']); ?>" 
                            data-price="<?php echo htmlspecialchars($row['price']); ?>">
                            Update
                        </button>
                    </div>
                </div>
            </div>
            <?php
                }
            } else {
                echo "<p>No products found.</p>";
            }
            ?>
        </div>
    </div>

    <!-- Update Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateModalLabel">Update Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="updateId" id="updateId">
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
                            <label for="image" class="form-label">Upload New Image (optional):</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="updateData">Update Product</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Populate modal with product data
        $('#updateModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id');
            var name = button.data('name');
            var description = button.data('description');
            var price = button.data('price');

            var modal = $(this);
            modal.find('#updateId').val(id);
            modal.find('#name').val(name);
            modal.find('#description').val(description);
            modal.find('#price').val(price);
        });
    </script>
</body>
</html>