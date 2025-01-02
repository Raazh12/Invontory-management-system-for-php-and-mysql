<?php
session_start();
$con = new mysqli("localhost", "root", "", "inventory_management_db");

if ($con->connect_error) {
    die("Couldn't connect to the server: " . $con->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collecting order data
    $customerName = htmlspecialchars($_POST['customer_name']);
    $productId = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $email = htmlspecialchars($_POST['email']);

    // Log the received product ID for debugging
    error_log("Received product ID: " . $productId);

    // Validate product ID
    $productCheck = $con->prepare("SELECT * FROM products WHERE id = ?");
    if (!$productCheck) {
        die("Prepare failed: " . htmlspecialchars($con->error));
    }

    $productCheck->bind_param("i", $productId);
    $productCheck->execute();
    $productResult = $productCheck->get_result();

    if ($productResult->num_rows === 0) {
        // Product not found
        echo "<h1>Error</h1>";
        echo "<p>The selected product does not exist. Product ID: $productId</p>";
        echo "<a href='product_list.php'>Go back to the product list</a>";
        exit;
    }

    // Insert order into the database
    $sql = "INSERT INTO orders (customer_name, product_id, quantity, email) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    
    if (!$stmt) {
        die("Prepare failed: " . htmlspecialchars($con->error));
    }

    $stmt->bind_param("siis", $customerName, $productId, $quantity, $email);

    if ($stmt->execute()) {
        echo "<h1>Order Confirmation</h1>";
        echo "<p>Thank you, $customerName! Your order has been placed successfully.</p>";
        echo "<p>Product ID: $productId</p>";
        echo "<p>Quantity: $quantity</p>";
        echo "<p>Email: $email</p>";
    } else {
        echo "<h1>Error</h1>";
        echo "<p>There was an issue placing your order: " . htmlspecialchars($stmt->error) . "</p>";
    }

    $stmt->close();
} else {
    // Redirect back to the form if accessed directly
    header("Location: product_list.php");
    exit();
}

$con->close();
?>