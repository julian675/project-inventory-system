<?php
$conn = new mysqli("localhost", "root", "", "ims_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add product
if (isset($_POST['add'])) {
    $product = $_POST['product'];
    $quantity = (int)$_POST['quantity'];
    $price = (float)$_POST['price'];

    // Check if product already exists
    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM inventory WHERE product = ?");
    $checkStmt->bind_param("s", $product);
    $checkStmt->execute();
    $checkStmt->bind_result($count);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($count > 0) {
        // Product already exists - send 409 Conflict
        http_response_code(409);
        echo json_encode(["error" => "Product already exists"]);
        exit;
    }

    $status = ($quantity >= 500) ? 'good' : (($quantity > 200) ? 'warning' : 'critical');

    $stmt = $conn->prepare("INSERT INTO inventory (product, price, quantity, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdis", $product, $price, $quantity, $status);
    $stmt->execute();
    exit;
}



// Delete selected items
if (isset($_POST['delete_ids'])) {
    foreach ($_POST['delete_ids'] as $id) {
        $conn->query("DELETE FROM inventory WHERE id = " . (int)$id);
    }
    exit;
}

// Update quantity
if (isset($_POST['update_quantity'])) {
    $id = (int) $_POST['id'];
    $delta = (int) $_POST['delta'];

    $conn->query("UPDATE inventory SET quantity = GREATEST(quantity + $delta, 0) WHERE id = $id");

    $conn->query("
        UPDATE inventory SET status = 
        CASE 
            WHEN quantity >= 500 THEN 'good'
            WHEN quantity > 200 THEN 'warning'
            ELSE 'critical'
        END
        WHERE id = $id
    ");
    exit;
}

// Update price
if (isset($_POST['update_price'])) {
    $id = (int) $_POST['id'];
    $price = (float) $_POST['price'];
    $conn->query("UPDATE inventory SET price = $price WHERE id = $id");
    exit;
}

// Display table
$result = $conn->query("SELECT * FROM inventory");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $statusClass = 'status-pill status-' . strtolower($row['status']);
        echo "<tr>
                <td><input type='checkbox' class='row-checkbox' value='{$row['id']}'></td>
                <td>{$row['product']}</td>
                <td>{$row['quantity']}</td>
                <td><span class='{$statusClass}'></span></td>
                <td>
                    <button class='minus-btn' data-id='{$row['id']}'>−</button>
                    <button class='plus-btn' data-id='{$row['id']}'>+</button>
                </td>
                <td><input type='number' step='0.01' class='price-input' data-id='{$row['id']}' value='{$row['price']}'></td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No items in stock</td></tr>";
}
?>
