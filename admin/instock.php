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

// Run the query to fetch inventory data
$sql = "SELECT id, product, items, status FROM instock";
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

    <div id="login-dropdown" class="dropdown-box" style="display: none;">
        <?php if (isset($_SESSION['username'])): ?>
            <a href="/project-inventory-system/logout.php" class="login-button">Log Out</a>
        <?php else: ?>
            <a href="/project-inventory-system/login.php" class="login-button">Log In</a>
        <?php endif; ?>
    </div>




  <div class="sidebar">
        <div class="menu-item dashboard" onclick="window.location.href='/project-inventory-system/admin/index.php'">
          <i class="fas fa-chart-line sidebar-icon"></i>
          <div class="menu-label">Dashboard</div> 
        </div>
        <div class="menu-item instock">
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



  <div class="main">
     <div class="blank-container">
      <div class="container">
        <h1>In Stock</h1>

            <!-- Search Bar -->
            <div class="search">
                <input class="search-section"
                    type="text"
                    id="searchInput"
                    placeholder="Search products...">
                <i class="fas fa-search"></i>
            </div>
                        
          <form id="addProductForm" style="margin-bottom: 10px; display: flex; gap: 10px;">
              <input type="text" name="product" placeholder="Product name" required>
              <input type="number" name="items" placeholder="Quantity" min="1" required>
              <button type="submit">Add</button>
              <button type="button" id="deleteSelected">Delete Selected</button>
          </form>


          <!-- In Stock Table -->
          <table border="1" cellpadding="6">
              <thead>
                  <tr>
                      <th><input type="checkbox" id="selectAll"></th>
                      <th>Product</th>
                      <th>Items</th>
                      <th>Status</th>
                      <th>Change</th>
                  </tr>
              </thead>
              <tbody id="itemsTable">
                  <?php include 'instock_backend.php'; ?>
              </tbody>
          </table>

      </div>
    </div>
  </div>
   
    <script src="js/instock.js"></script>
    <script src="/project-inventory-system/js/header.js"></script>
  </body>
</html>