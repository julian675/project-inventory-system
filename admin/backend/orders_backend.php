<?php
session_start();

// Block access if not logged in or not admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin/login.php");
    exit;
}

// Set username for display
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest';

// Include database connection
include 'db.php';

// Handle client deletion
if (isset($_POST['delete_client'])) {
    $delete_client_id = intval($_POST['delete_client_id']);
    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("DELETE oi FROM order_items oi JOIN orders o ON oi.order_id = o.id WHERE o.client_id = ?");
        if (!$stmt) throw new Exception($conn->error);
        $stmt->bind_param("i", $delete_client_id);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM orders WHERE client_id = ?");
        if (!$stmt) throw new Exception($conn->error);
        $stmt->bind_param("i", $delete_client_id);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM clients WHERE id = ?");
        if (!$stmt) throw new Exception($conn->error);
        $stmt->bind_param("i", $delete_client_id);
        $stmt->execute();

        $conn->commit();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Failed to delete client: " . $e->getMessage();
        exit();
    }
}

// Fetch products
$inventory_result = $conn->query("SELECT * FROM inventory");
$inventory = [];
while ($row = $inventory_result->fetch_assoc()) {
    $inventory[] = $row;
}

// Handle order submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['delete_client'])) {
    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("INSERT INTO clients (name, address, contact_number, company_name) VALUES (?, ?, ?, ?)");
        if (!$stmt) throw new Exception($conn->error);
        $stmt->bind_param("ssss", $_POST['name'], $_POST['address'], $_POST['contact'], $_POST['company']);
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
        }

        $conn->commit();
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Failed to place order: " . $e->getMessage();
        exit();
    }
}
?>
