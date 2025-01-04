<?php
session_start();
$con = new mysqli("localhost", "root", "", "inventory_management_db");
if ($con->connect_error) {
    die("Couldn't connect to the server: " . $con->connect_error);
}

$sql = "SELECT s.id, p.name AS product_name, s.quantity, s.sale_date, s.total_price 
        FROM sales s 
        JOIN products p ON s.product_id = p.id";
$result = $con->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Records</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h1 class="mb-4 text-center">Sales Records</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Sale Date</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['product_name']}</td>
                                <td>{$row['quantity']}</td>
                                <td>{$row['sale_date']}</td>
                                <td>$" . htmlspecialchars($row['total_price']) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No sales recorded.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>