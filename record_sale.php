<?php
session_start();
$con = new mysqli("localhost", "root", "", "inventory_management_db");
if ($con->connect_error) {
    die("Couldn't connect to the server: " . $con->connect_error);
}

// Check if the form is submitted
if (isset($_POST['recordSale'])) {
    $productId = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Fetch product price
    $sql = "SELECT price FROM products WHERE id=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $totalPrice = $product['price'] * $quantity;

        // Insert sale record
        $sqlInsert = "INSERT INTO sales (product_id, quantity, total_price) VALUES (?, ?, ?)";
        $stmtInsert = $con->prepare($sqlInsert);
        $stmtInsert->bind_param("iid", $productId, $quantity, $totalPrice);
        
        if ($stmtInsert->execute()) {
            echo "<script>alert('Sale recorded successfully!'); window.location.href='sales.php';</script>";
        } else {
            echo "<script>alert('Error recording sale: " . $stmtInsert->error . "');</script>";
        }
    } else {
        echo "<script>alert('Product not found!');</script>";
    }
}

// Fetch products for the sale form
$sqlProducts = "SELECT id, name FROM products";
$resultProducts = $con->query($sqlProducts);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Sale</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="mb-4 text-center">Record Sale</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="product_id" class="form-label">Select Product:</label>
                <select name="product_id" id="product_id" class="form-select" required>
                    <option value="">-- Select Product --</option>
                    <?php while ($product = $resultProducts->fetch_assoc()) { ?>
                        <option value="<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['name']); ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity:</label>
                <input type="number" name="quantity" id="quantity" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary" name="recordSale">Record Sale</button>
        </form>
    </div>
</body>
</html>