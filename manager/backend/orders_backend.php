<?php
session_start();
// Include database connection
include 'db.php';

// Fetch products
$inventory = $conn->query("SELECT * FROM inventory");
$inventory = [];
while ($row = $inventory_result->fetch_assoc()) {
    $inventory[] = $row;
}

if (isset($_POST['remove_order'])) {
    $order_id = intval($_POST['remove_order_id']);
    $stmt = $conn->prepare("UPDATE orders SET is_removed = 1 WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle order submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['delete_client'])) {
    $conn->begin_transaction();

    try {
        $name = htmlspecialchars(trim($_POST['name']));
        $address = htmlspecialchars(trim($_POST['address']));
        $contact = htmlspecialchars(trim($_POST['contact']));
        $company = htmlspecialchars(trim($_POST['company']));

        $stmt = $conn->prepare("INSERT INTO clients (name, address, contact_number, company_name) VALUES (?, ?, ?, ?)");
        if (!$stmt) throw new Exception($conn->error);
        $stmt->bind_param("ssss", $name, $address, $contact, $company);

        $stmt->execute();
        $client_id = $stmt->insert_id;

        $grand_total = 0;
        $order_items = [];

        for ($i = 0; $i < count($_POST['inventory']); $i++) {
            $product_id = $_POST['inventory'][$i];
            $quantity = $_POST['quantity'][$i];

            $stmt = $conn->prepare("SELECT price FROM inventory WHERE id = ?");
            if (!$stmt) throw new Exception($conn->error);
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $price = $result['price'];
            $total = $price * $quantity;
            $grand_total += $total;

            $order_items[] = [
                'product_id' => $product_id,
                'quantity' => $quantity,
                'price' => $price,
                'total_price' => $total
            ];
        }

        $stmt = $conn->prepare("INSERT INTO orders (client_id, order_date, grand_total) VALUES (?, NOW(), ?)");
        if (!$stmt) throw new Exception($conn->error);
        $stmt->bind_param("id", $client_id, $grand_total);
        $stmt->execute();
        $order_id = $stmt->insert_id;

        foreach ($order_items as $item) {
            $stmtCheck = $conn->prepare("SELECT quantity FROM inventory WHERE id = ?");
            if (!$stmtCheck) throw new Exception($conn->error);
            $stmtCheck->bind_param("i", $item['product_id']);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result()->fetch_assoc();

            if ($resultCheck['quantity'] < $item['quantity']) {
                throw new Exception("Not enough stock for product ID " . $item['product_id']);
            }

            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, total_price) VALUES (?, ?, ?, ?, ?)");
            if (!$stmt) throw new Exception($conn->error);
            $stmt->bind_param("iiidd", $order_id, $item['product_id'], $item['quantity'], $item['price'], $item['total_price']);
            $stmt->execute();

            $stmt = $conn->prepare("UPDATE inventory SET quantity = quantity - ? WHERE id = ?");
            if (!$stmt) throw new Exception($conn->error);
            $stmt->bind_param("ii", $item['quantity'], $item['product_id']);
            $stmt->execute();

            require_once('utils.php');
            updateStatus($conn, $item['product_id']);
        }

        $conn->commit();
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Order placement error: " . $e->getMessage());  // logs to server
        echo "Failed to place order. Please try again later.";

        exit();
    }
}
?>
