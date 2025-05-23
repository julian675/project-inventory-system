<?php
$conn = new mysqli("localhost", "root", "", "ims_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Utility function to update status based on quantity
function updateStatus($conn, $id) {
    $conn->query("
        UPDATE inventory SET status = 
        CASE 
            WHEN quantity >= 500 THEN 'Good'
            WHEN quantity > 200 THEN 'Warning'
            ELSE 'Critical'
        END
        WHERE id = $id
    ");
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
        http_response_code(409);
        echo json_encode(["error" => "Product already exists"]);
        exit;
    }

    // Insert with temporary status (we’ll correct it with updateStatus)
    $status = 'Pending';
    $stmt = $conn->prepare("INSERT INTO inventory (product, price, quantity, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdis", $product, $price, $quantity, $status);
    $stmt->execute();

    $id = $stmt->insert_id;
    updateStatus($conn, $id);
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

    updateStatus($conn, $id);
    exit;
}

// Update price
if (isset($_POST['update_price'])) {
    $id = (int) $_POST['id'];
    $price = (float) $_POST['price'];
    $conn->query("UPDATE inventory SET price = $price WHERE id = $id");
    exit;
}

// Display inventory table rows
$result = $conn->query("SELECT * FROM inventory");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $statusClass = 'status-pill status-' . strtolower($row['status']);
        echo "<tr>
                <td><input type='checkbox' class='row-checkbox' value='{$row['id']}'></td>
                <td>{$row['product']}</td>
                <td>{$row['quantity']}</td>
                <td><span class='{$statusClass}' title='{$row['status']}'></span></td>
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
