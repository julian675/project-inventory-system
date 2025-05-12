<?php
$conn = mysqli_connect("localhost", "root", "", "your_database_name");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stock_id = $_POST['stock_id'];
    $transfer_qty = $_POST['transfer_qty'];

    $query = "SELECT product_name, quantity FROM stock WHERE stock_id = $stock_id";
    $result = mysqli_query($conn, $query);
    $stock = mysqli_fetch_assoc($result);

    if (!$stock) {
        die("Product not found.");
    }

    $product_name = $stock['product_name'];
    $current_qty = $stock['quantity'];

    if ($transfer_qty > $current_qty) {
        die("Not enough stock available.");
    }

    $remaining = $current_qty - $transfer_qty;
    $status = ($remaining > 0) ? 'Pending' : 'Completed';

    // Update stock quantity
    mysqli_query($conn, "UPDATE stock SET quantity = quantity - $transfer_qty WHERE stock_id = $stock_id");

    // Insert into orders table
    mysqli_query($conn, "
        INSERT INTO orders (stock_id, product_name, date_transferred, quantity_transferred, remaining_quantity, status)
        VALUES ($stock_id, '$product_name', NOW(), $transfer_qty, $remaining, '$status')
    ");

    header("Location: /new_exp/admin/index.php?success=1");
    exit;
}
?>
