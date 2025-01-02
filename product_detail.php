<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <?php
        $con = new mysqli("localhost", "root", "", "inventory_management_db");
        if ($con->connect_error) {
            die("Couldn't connect to the server: " . $con->connect_error);
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM products WHERE id=?";
            $stmt = $con->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($row = $result->fetch_array()) {
        ?>
        <div class="card">
            <img src="<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top" alt="Product Image" style="height: 300px; object-fit: cover;">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                <p class="card-text"><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
                <p class="card-text"><strong>Price:</strong> $<?php echo htmlspecialchars($row['price']); ?></p>
                <a href="select.php" class="btn btn-primary">Back to Products</a>
            </div>
        </div>
        <?php
            } else {
                echo "<p class='text-danger'>Product not found.</p>";
            }
        } else {
            echo "<p class='text-danger'>No product selected.</p>";
        }
        ?>
    </div>
</body>
</html>