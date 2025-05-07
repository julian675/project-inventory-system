<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blank Template</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link href="dashboard.css" rel="stylesheet"> 
  <link href="header.css" rel="stylesheet"> 
  <link href="side-bar.css" rel="stylesheet"> 
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
            <span class="username">Guest</span>
        </div>
        <div class="caret-section">
            <i class="fas fa-caret-down header-icon black-icon"></i>
        </div>
        <div id="login-box" class="login-box" style="display: none;">
            <a href="login.php" id="login-btn" class="login-btn">Login</a>
        </div>
    </nav>
    <div class="sidebar">
        <div class="vertical-line"></div>
        <div class="dashboard">
          <i class="fas fa-chart-line sidebar-icon"></i>
          <div class="menu-label">Dashboard</div> 
        </div>
        <div class="instock">
            <i class="fas fa-boxes sidebar-icon"></i>
            <div class="menu-label">In Stock</div> 
        </div>
        <div class="products">
            <i class="fas fa-tags sidebar-icon"></i>
            <div class="menu-label">Products</div> 
        </div>
        <div class="sales">
            <i class="fas fa-cash-register sidebar-icon"></i>
            <div class="menu-label">Sales</div>
          </div>
          <div class="orders">
            <i class="fas fa-receipt sidebar-icon"></i>
            <div class="menu-label">Orders</div>
          </div>
          <div class="users">
            <i class="fas fa-users sidebar-icon"></i>
            <div class="menu-label">Users</div>
          </div>
          <div class="invoice" >
            <i class="fas fa-file-invoice sidebar-icon"></i>
            <div class="menu-label">Invoice</div>
          </div>
    </div>
      
    <script src="header.js"></script>
</body>
</html>
