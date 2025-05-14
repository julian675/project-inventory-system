<?php
session_start();

// Block access if not logged in or not admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin/login.php");
    exit;
}

// Set username for display
$username = 'Guest';
if (isset($_SESSION['username'])) {
    $username = htmlspecialchars($_SESSION['username']);
}

// Database connection
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "ims_db"; // Your database name

$conn = new mysqli($servername, $username_db, $password_db, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check product availability (for example, Product 1)
$product_id = 1; // You can change this dynamically depending on the product you're checking
$sql = "SELECT * FROM `in_stock` WHERE `product_id` = $product_id AND `quantity` > 0"; 
$result = $conn->query($sql);

// Check if the product is in stock
$product_in_stock = false;
if ($result->num_rows > 0) {
    $product_in_stock = true;
}

$conn->close();
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
        <div class="menu-item instock" onclick="window.location.href='/project-inventory-system/admin/instock.php'">
            <i class="fas fa-boxes sidebar-icon"></i>
            <div class="menu-label">In Stock</div> 
        </div>
        <div class="menu-item products">
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
        <div class="left-side" style="background-image: url('img/product1.jpg');"></div>
        <div class="middle">
            <?php if ($product_in_stock): ?>
                <h3>Product 1</h3>
                <p>Product details go here.</p>
            <?php else: ?>
                <h3>Product Unavailable</h3>
                <p>Sorry, this product is currently out of stock.</p>
            <?php endif; ?>
        </div>
        <div class="right-side">
            <p>$19.99</p>
            <p>Additional details</p>
        </div>
    </div>
    </div>

  <script src="/project-inventory-system/js/header.js"></script>
</body>
</html>