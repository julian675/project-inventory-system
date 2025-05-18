<?php
session_start();

// Block access if not logged in or not admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: manager/login.php");
    exit;
}

// Set username for display
$username = 'Guest';
if (isset($_SESSION['username'])) {
    $username = htmlspecialchars($_SESSION['username']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard UI</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="css/sales.css" rel="stylesheet">
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
        <div class="menu-item dashboard" onclick="window.location.href='/project-inventory-system/manager/dashboard.php'">
          <i class="fas fa-chart-line sidebar-icon"></i>
          <div class="menu-label">Dashboard</div> 
        </div>
        <div class="menu-item instock" onclick="window.location.href='/project-inventory-system/manager/instock.php'">
            <i class="fas fa-boxes sidebar-icon"></i>
            <div class="menu-label">In Stock</div> 
        </div>
        <div class="menu-item products" onclick="window.location.href='/project-inventory-system/manager/products.php'">
            <i class="fas fa-tags sidebar-icon"></i>
            <div class="menu-label">Products</div> 
        </div>
          <div class="menu-item orders" onclick="window.location.href='/project-inventory-system/manager/orders.php'">
            <i class="fas fa-receipt sidebar-icon"></i>
            <div class="menu-label">Orders</div>
          </div>
          <div class="menu-item invoice" onclick="window.location.href='/project-inventory-system/manager/invoice.php'">
            <i class="fas fa-file-invoice sidebar-icon"></i>
            <div class="menu-label">Invoice</div>
          </div>
  </div>


 <div class="main">

      <div class="chart-grid-2">
      <div class="chart-box">Bar Chart</div>
      <div class="pie-box">Pie Chart</div>
    </div>

    <div class="card-grid-2">
      <div class="table-card">
        <h4>Stock Alert</h4>
        <table>
          <thead>
            <tr><th>Order ID</th><th>Date</th><th>Quantity</th><th>Alert amt.</th><th>Status</th></tr>
          </thead>
          <tbody>
            <tr><td>001</td><td>2025-05-01</td><td>20</td><td>5</td><td>Low</td></tr>
            <tr><td>002</td><td>2025-05-02</td><td>10</td><td>2</td><td>Critical</td></tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>
 
    

  <script src="/project-inventory-system/js/header.js"></script>
</body>
</html>