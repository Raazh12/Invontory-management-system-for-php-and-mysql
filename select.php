<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
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
                        <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Update</a>
                      
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

    <?php
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
    ?>
    
</body>
</html>