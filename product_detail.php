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
            $id = (int)$_GET['id']; // Ensure ID is an integer
            $sql = "SELECT * FROM products WHERE id=?";
            $stmt = $con->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        ?>
        <div class="card">
            <img src="<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top" alt="Product Image" style="height: 300px; object-fit: cover;">
            <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                <p class="card-text"><strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?></p>
                <p class="card-text"><strong>Price:</strong> $<?php echo htmlspecialchars($row['price']); ?></p>
                <form action="process_order.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <div class="mb-3">
                        <label for="customer_name" class="form-label">Customer Name:</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity:</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Place Order</button>
                </form>
                <a href="product_list.php" class="btn btn-secondary">Back to Products</a>
            </div>
        </div>
        <?php
                } else {
                    echo "<p class='text-danger'>Product not found.</p>";
                }
                $stmt->close();
            } else {
                echo "<p class='text-danger'>Database query failed: " . htmlspecialchars($con->error) . "</p>";
            }
        } else {
            echo "<p class='text-danger'>No product selected.</p>";
        }
        $con->close();
        ?>
    </div>
</body>
</html>