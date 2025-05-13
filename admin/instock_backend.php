<?php
$conn = new mysqli("localhost", "root", "", "ims_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add product
if (isset($_POST['add'])) {
    $product = $_POST['product'];
    $items = (int) $_POST['items'];

    $status = ($items >= 500) ? 'good' : (($items > 200) ? 'warning' : 'critical');

    $stmt = $conn->prepare("INSERT INTO instock (product, items, status) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $product, $items, $status);
    $stmt->execute();
    exit;
}

// Delete selected items
if (isset($_POST['delete_ids'])) {
    $ids = $_POST['delete_ids'];
    foreach ($ids as $id) {
        $conn->query("DELETE FROM instock WHERE id = " . (int)$id);
    }
    exit;
}

// Update quantity
if (isset($_POST['update_quantity'])) {
    $id = (int) $_POST['id'];
    $delta = (int) $_POST['delta'];

    $conn->query("UPDATE instock SET items = GREATEST(items + $delta, 0) WHERE id = $id");

    // Update status based on new quantity
    $conn->query("
        UPDATE instock SET status = 
        CASE 
            WHEN items >= 500 THEN 'good'
            WHEN items > 100 THEN 'warning'
            ELSE 'critical'
        END
        WHERE id = $id
    ");
    exit;
}

// Display table rows
$result = $conn->query("SELECT * FROM instock");

if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $statusClass = '';
            switch ($row['status']) {
                case 'good':
                    $statusClass = 'status-good';
                    break;
                case 'warning':
                    $statusClass = 'status-warning';
                    break;
                case 'critical':
                    $statusClass = 'status-critical';
                    break;
            }

            echo "<tr>
                <td><input type='checkbox' class='row-checkbox' value='{$row['id']}'></td>
                <td>{$row['product']}</td>
                <td>{$row['items']}</td>
                <td><span class='status-pill {$statusClass}'></span></td>
                <td>
                    <button class='minus-btn' data-id='{$row['id']}'>−</button>
                    <button class='plus-btn' data-id='{$row['id']}'>+</button>
                </td>
            </tr>";
        }

} else {
    echo "<tr><td colspan='5'>No items in stock</td></tr>";
}

?>
