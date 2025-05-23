<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin/login.php");
    exit;
}

$username = 'Guest';
if (isset($_SESSION['username'])) {
    $username = htmlspecialchars($_SESSION['username']);
}

$host = "localhost";
$username_db = "root";
$password = "";
$database = "ims_db";

$conn = new mysqli($host, $username_db, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$clients_result = $conn->query("SELECT id, name FROM clients");
$selected_client_id = isset($_GET['client_id']) ? intval($_GET['client_id']) : null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Client Orders</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="css/invoice.css" rel="stylesheet">
    <link href="/project-inventory-system/css/header.css" rel="stylesheet">
    <script>
        function toggleLogin() {
            const dropdown = document.getElementById("login-dropdown");
            dropdown.style.display = dropdown.style.display === "flex" ? "none" : "flex";
        }
    </script>
</head>
<body>

<div class="header">
    <div class="left-icon"><i class="fas fa-bars"></i></div>
    <div class="right-contents">
        <i class="fas fa-search"></i>
        <i class="fas fa-bell"></i>
        <div class="user-info">
            <i class="fas fa-circle-user"></i>
            <span id="user-name"><?= htmlspecialchars($username) ?></span>
        </div>
        <i class="fas fa-caret-down" onclick="toggleLogin()"></i>
    </div>
</div>

<div id="login-dropdown" class="dropdown-box">
    <?php if (isset($_SESSION['username'])): ?>
        <a href="/project-inventory-system/logout.php" class="login-button">Log Out</a>
    <?php else: ?>
        <a href="/project-inventory-system/login.php" class="login-button">Log In</a>
    <?php endif; ?>
</div>

<div class="sidebar">
    <div class="menu-item" onclick="window.location.href='/project-inventory-system/admin/dashboard.php'">
        <i class="fas fa-chart-line sidebar-icon"></i>
        <div class="menu-label">Dashboard</div> 
    </div>
    <div class="menu-item" onclick="window.location.href='/project-inventory-system/admin/inventory.php'">
        <i class="fas fa-boxes sidebar-icon"></i>
        <div class="menu-label">Inventory</div> 
    </div>
    <div class="menu-item" onclick="window.location.href='/project-inventory-system/admin/products.php'">
        <i class="fas fa-tags sidebar-icon"></i>
        <div class="menu-label">Products</div> 
    </div>
    <div class="menu-item" onclick="window.location.href='/project-inventory-system/admin/orders.php'">
        <i class="fas fa-receipt sidebar-icon"></i>
        <div class="menu-label">Orders</div>
    </div>
    <div class="menu-item" onclick="window.location.href='/project-inventory-system/admin/users.php'">
        <i class="fas fa-users sidebar-icon"></i>
        <div class="menu-label">Users</div>
    </div>
    <div class="menu-item">
        <i class="fas fa-file-invoice sidebar-icon"></i>
        <div class="menu-label">Invoice</div>
    </div>
</div>

<div class="client-sidebar">
    <h2>Clients</h2>
    <?php
    if ($clients_result && $clients_result->num_rows > 0) {
        while($client = $clients_result->fetch_assoc()) {
            echo "<a href='?client_id={$client['id']}' class='client-link'>" . htmlspecialchars($client['name']) . "</a>";
        }
    } else {
        echo "No clients found.";
    }
    ?>
</div>

<div class="main-content">
    <?php if ($selected_client_id): ?>
        <h2>Order Details</h2>
        <?php
            $client_sql = "SELECT name, address, contact_number, company_name FROM clients WHERE id = $selected_client_id";
            $client_result = $conn->query($client_sql);
            $client_info = $client_result->fetch_assoc();

            if ($client_info) {
                echo "<div class='client-info'>";
                echo "<h2>Client Information</h2>";
                echo "<p><strong>Name:</strong> " . htmlspecialchars($client_info['name']) . "</p>";
                echo "<p><strong>Address:</strong> " . htmlspecialchars($client_info['address']) . "</p>";
                echo "<p><strong>Contact Number:</strong> " . htmlspecialchars($client_info['contact_number']) . "</p>";
                echo "<p><strong>Company Name:</strong> " . htmlspecialchars($client_info['company_name']) . "</p>";
                echo "</div>";
            }

            $orders_sql = "SELECT id AS order_id, order_date, grand_total FROM orders WHERE client_id = $selected_client_id ORDER BY order_date DESC";
            $orders_result = $conn->query($orders_sql);

            if ($orders_result && $orders_result->num_rows > 0) {
                while ($order = $orders_result->fetch_assoc()) {
                    echo "<h3>Order #{$order['order_id']} - " . date('Y-m-d', strtotime($order['order_date'])) . "</h3>";
                    echo "<p><strong>Total:</strong> ₱" . number_format($order['grand_total'], 2) . "</p>";

                    $items_sql = "
                        SELECT oi.quantity, oi.price, oi.total_price, p.product AS product_name
                        FROM order_items oi
                        JOIN inventory p ON oi.product_id = p.id
                        WHERE oi.order_id = {$order['order_id']}
                    ";
                    $items_result = $conn->query($items_sql);

                    if ($items_result && $items_result->num_rows > 0) {
                        echo "<table>";
                        echo "<tr><th>Product</th><th>Quantity</th><th>Price</th><th>Total</th></tr>";
                        while ($item = $items_result->fetch_assoc()) {
                            echo "<tr>
                                <td>" . htmlspecialchars($item['product_name']) . "</td>
                                <td>{$item['quantity']}</td>
                                <td>₱" . number_format($item['price'], 2) . "</td>
                                <td>₱" . number_format($item['total_price'], 2) . "</td>
                            </tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "<p>No items for this order.</p>";
                    }
                }
            } else {
                echo "<p>No orders found for this client.</p>";
            }
        ?>
    <?php else: ?>
        <h2>Welcome</h2>
        <p>Select a client to view their order details.</p>
    <?php endif; ?>
</div>

</body>
</html>

<?php $conn->close(); ?>
