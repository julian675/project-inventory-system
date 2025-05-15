<?php
session_start();

// Block access if not logged in or not admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin/login.php");
    exit;
}

// Set username for display
$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest';

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "ims_db";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Step 1: Handle Add Product submission
if (isset($_POST['add'])) {
    $product = trim($_POST['product']);
    $items = (int) $_POST['items'];
    $price = number_format((float) $_POST['price'], 2, '.', '');

    if ($product === '') {
        echo "Product name is required.";
        exit;
    }

    if ($price < 0) {
        echo "Invalid price: must be 0 or higher.";
        exit;
    }

    $status = ($items >= 500) ? 'good' : (($items > 200) ? 'warning' : 'critical');

    $stmt = $conn->prepare("INSERT INTO instock (product, items, price, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sids", $product, $items, $price, $status);
    $stmt->execute();

    // Redirect to prevent form resubmission on refresh
    header("Location: instock.php");
    exit;
}

// Get current stock list
$sql = "SELECT id, product, items, price, status FROM instock";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard UI</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="css/instock.css" rel="stylesheet">
  <link href="/project-inventory-system/css/header.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
</head>
<body>

<!-- Header -->
<div class="header">
  <div class="left-icon">
    <i class="fas fa-bars"></i>
  </div>
  <div class="right-contents">
    <i class="fas fa-search"></i>
    <i class="fas fa-bell"></i>
    <div class="user-info">
      <i class="fas fa-circle-user"></i>
      <span id="user-name"><?= $username ?></span>
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

<!-- Sidebar -->
<div class="sidebar">
  <div class="menu-item dashboard" onclick="window.location.href='/project-inventory-system/admin/dashboard.php'">
    <i class="fas fa-chart-line sidebar-icon"></i>
    <div class="menu-label">Dashboard</div> 
  </div>
  <div class="menu-item instock active">
    <i class="fas fa-boxes sidebar-icon"></i>
    <div class="menu-label">In Stock</div> 
  </div>
  <div class="menu-item products" onclick="window.location.href='/project-inventory-system/admin/products.php'">
    <i class="fas fa-tags sidebar-icon"></i>
    <div class="menu-label">Products</div> 
  </div>
  <div class="menu-item sales" onclick="window.location.href='/project-inventory-system/admin/sales.php'">
    <i class="fas fa-cash-register sidebar-icon"></i>
    <div class="menu-label">Sales</div>
  </div>
  <div class="menu-item orders" onclick="window.location.href='/project-inventory-system/admin/orders.php'">
    <i class="fas fa-receipt sidebar-icon"></i>
    <div class="menu-label">Orders</div>
  </div>
  <div class="menu-item users" onclick="window.location.href='/project-inventory-system/admin/users.php'">
    <i class="fas fa-users sidebar-icon"></i>
    <div class="menu-label">Users</div>
  </div>
  <div class="menu-item invoice" onclick="window.location.href='/project-inventory-system/admin/invoice.php'">
    <i class="fas fa-file-invoice sidebar-icon"></i>
    <div class="menu-label">Invoice</div>
  </div>
</div>

<!-- Main content -->
<div class="main">
  <div class="blank-container">
    <div class="container">
      <h1>In Stock</h1>

      <!-- Search Bar -->
      <div class="search">
        <input class="search-section" type="text" id="searchInput" placeholder="Search products...">
        <i class="fas fa-search"></i>
      </div>

      <!-- Add Product Form -->
      <form id="addProductForm" method="post" style="margin-bottom: 10px; display: flex; gap: 10px;">
        <input type="text" name="product" placeholder="Product name" required>
        <input type="number" name="items" placeholder="Quantity" min="1" required>
        <input type="number" step="0.01" name="price" placeholder="Unit Price" min="0" required>
        <button type="submit" name="add">Add</button>
        <button type="button" id="deleteSelected">Delete Selected</button>
      </form>

      <!-- In Stock Table -->
      <table border="1" cellpadding="6">
        <thead>
          <tr>
            <th><input type="checkbox" id="selectAll"></th>
            <th>Product</th>
            <th>Unit Price</th>
            <th>Items</th>
            <th>Status</th>
            <th>Change</th>
          </tr>
        </thead>
        <tbody id="itemsTable">
          <?php
            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  $statusClass = '';
                  switch ($row['status']) {
                      case 'good': $statusClass = 'status-good'; break;
                      case 'warning': $statusClass = 'status-warning'; break;
                      case 'critical': $statusClass = 'status-critical'; break;
                  }

                  echo "<tr>
                      <td><input type='checkbox' class='row-checkbox' value='{$row['id']}'></td>
                      <td>{$row['product']}</td>
                      <td>{$row['price']}</td>
                      <td>{$row['items']}</td>
                      <td><span class='status-pill {$statusClass}'></span></td>
                      <td>
                          <button class='minus-btn' data-id='{$row['id']}'>−</button>
                          <button class='plus-btn' data-id='{$row['id']}'>+</button>
                      </td>
                  </tr>";
              }
            } else {
              echo "<tr><td colspan='6'>No items in stock</td></tr>";
            }
          ?>
        </tbody>
      </table>

    </div>
  </div>
</div>

<script src="js/instock.js"></script>
<script src="/project-inventory-system/js/header.js"></script>
</body>
</html>
