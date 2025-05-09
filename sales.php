
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventory Management System</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link href="css/sales.css" rel="stylesheet"> 
  <link href="css/header.css" rel="stylesheet"> 
</head>
<body>  
<nav>
        <div class="menu-toggle-icon">&#9776;</div>
        <div class="search-section">
            <i class="fas fa-search header-icon black-icon"></i>
        </div>
        <div class="bell-section">
            <i class="fas fa-bell header-icon black-icon"></i>
        </div>
        <div class="user-section">
            <i class="fas fa-circle-user header-icon user-icon"></i>
        </div>
        <div class="user-name">
          <span class="username">
                <?php 
                session_start(); 
                echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; 
                ?>
            </span>
        </div>

        <div class="caret-section">
            <i class="fas fa-caret-down header-icon black-icon"></i>
        </div>
        <div id="login-box" class="login-box">
          <?php if (isset($_SESSION['username'])): ?>
              <a href="logout.php" id="logout-btn" class="login-btn">Logout</a>
          <?php else: ?>
              <a href="login.php" id="login-btn" class="login-btn">Login</a>
          <?php endif; ?>
      </div>
    </nav>
    <div class="sidebar">
        <div class="vertical-line"></div>
        <div class="dashboard" onclick="window.location.href='dashboard.php'">
          <i class="fas fa-chart-line sidebar-icon"></i>
          <div class="menu-label">Dashboard</div> 
        </div>
        <div class="instock" onclick="window.location.href='In Stock.php'">
            <i class="fas fa-boxes sidebar-icon"></i>
            <div class="menu-label">In Stock</div> 
        </div>
        <div class="products" onclick="window.location.href='products.php'">
            <i class="fas fa-tags sidebar-icon"></i>
            <div class="menu-label">Products</div> 
        </div>
        <div class="sales">
            <i class="fas fa-cash-register sidebar-icon"></i>
            <div class="menu-label">Sales</div>
          </div>
          <div class="orders" onclick="window.location.href='orders.php'">
            <i class="fas fa-receipt sidebar-icon"></i>
            <div class="menu-label">Orders</div>
          </div>
          <div class="users" onclick="window.location.href='users.php'">
            <i class="fas fa-users sidebar-icon"></i>
            <div class="menu-label">Users</div>
          </div>
          <div class="invoice" onclick="window.location.href='invoice.php'">
            <i class="fas fa-file-invoice sidebar-icon"></i>
            <div class="menu-label">Invoice</div>
          </div>
    </div>

    <script src="js/header.js"></script>
</body>
</html>
