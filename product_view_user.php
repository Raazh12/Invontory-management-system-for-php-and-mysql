<?php
session_start();
$con = new mysqli("localhost", "root", "", "inventory_management_db");
if ($con->connect_error) {
    die("Couldn't connect to the server: " . $con->connect_error);
}

$productId = $_GET['id'] ?? null;
if ($productId) {
    $sql = "SELECT * FROM products WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
} else {
    header("Location: select.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="mb-4 text-center"><?php echo htmlspecialchars($product['name']); ?></h1>
        <img src="<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid mb-4" alt="Product Image">
        <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
        <p><strong>Price:</strong> $<?php echo htmlspecialchars($product['price']); ?></p>
        <a href="select.php" class="btn btn-secondary">Back to Product Lists</a>
    </div>
</body>
</html>