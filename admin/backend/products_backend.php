<?php
$conn = new mysqli("localhost", "root", "", "ims_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch product and price only
$result = $conn->query("SELECT product, price FROM inventory");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['product']) . "</td>
                <td>₱" . number_format($row['price'], 2) . "</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='2'>No products in stock.</td></tr>";
}

$conn->close();
?>
