<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /project-inventory-system/login.php");
    exit;
}

$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest';

$host = "localhost";
$user = "root";
$password = "";
$database = "ims_db";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT product, price FROM inventory";
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
  <link href="css/products.css" rel="stylesheet">
  <link href="/project-inventory-system/css/header.css" rel="stylesheet">
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

     <div id="login-dropdown" class="dropdown-box">
        <?php if (isset($_SESSION['username'])): ?>
            <a href="/project-inventory-system/logout.php" class="login-button">Log Out</a>
        <?php else: ?>
            <a href="/project-inventory-system/login.php" class="login-button">Log In</a>
        <?php endif; ?>
    </div>




  <div class="sidebar">
        <div class="menu-item dashboard" onclick="window.location.href='/project-inventory-system/admin/dashboard.php'">
          <i class="fas fa-chart-line sidebar-icon"></i>
          <div class="menu-label">Dashboard</div> 
        </div>
        <div class="menu-item inventory" onclick="window.location.href='/project-inventory-system/admin/inventory.php'">
            <i class="fas fa-boxes sidebar-icon"></i>
            <div class="menu-label">Inventory</div> 
        </div>
        <div class="menu-item products">
            <i class="fas fa-tags sidebar-icon"></i>
            <div class="menu-label">Products</div> 
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
       <h1>Products</h1>

       <div class="search-wrapper">
        <input type="text" id="searchInput" placeholder="Search products..." class="search-option">
        <i class="fa fa-search search-icon"></i>
      </div>
          <table border="1" cellpadding="6" class="products-table">
              <thead>
                  <tr>
                      <th class="product-class">Product</th>
                      <th class="price-class">Price (₱)</th>
                  </tr>
              </thead>
              <tbody id="itemsTable" class="table-item">
                  <?php include 'backend/products_backend.php'; ?>
              </tbody>
          </table>
      </div>
    </div>
  </div>
  
  <script src="js/products.js"></script>
  <script src="/project-inventory-system/js/header.js"></script>
</body>
</html>