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

          <form method="POST" action="/new_exp/admin/transfer.php" class="stock-transfer-form" style="margin-top: 20px;">
            <label for="stock_id">Product ID:</label><br>
            <input type="number" id="stock_id" name="stock_id" required style="padding: 8px; width: 100%; margin-bottom: 10px;"><br>

            <label for="transfer_qty">Quantity to Transfer:</label><br>
            <input type="number" id="transfer_qty" name="transfer_qty" required style="padding: 8px; width: 100%; margin-bottom: 10px;"><br>

            <button type="submit" style="padding: 10px 20px; background-color: #4CAF50; color: white; border: none; cursor: pointer;">
              Transfer Stock
            </button>
          </form>
          <table border="1" cellspacing="0" cellpadding="10" style="width:100%; text-align:center;">
  <thead>
    <tr>
      <th>ID</th>
      <th>Product</th>
      <th>Category</th>
      <th>Sales Channel</th>
      <th>Instruction</th>
      <th>Quantity In</th>
      <th>Remaining</th>
      <th>Entry Date</th>
      <th>Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>1</td>
      <td>Inverter</td>
      <td>cat1</td>
      <td>Store name</td>
      <td>Initial stock</td>
      <td>50</td>
      <td>0</td>
      <td>2024-12-01 10:00:00</td>
      <td>Pending</td>
      <td>
        <button>➕</button>
        <button>➖</button>
      </td>
    </tr>
    <!-- Repeat for all 10 rows with appropriate data -->
  </tbody>
</table>

      </div>
    </div>
  </div>
   

  <script src="/project-inventory-system/js/header.js"></script>
</body>
</html>