<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventory Management System</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="css/dashboard.css" rel="stylesheet"> 
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
        <div class="instock" onclick="window.location.href='In Stock.php'">
            <i class="fas fa-boxes sidebar-icon"></i>
            <div class="menu-label">In Stock</div> 
        </div>
        <div class="products" onclick="window.location.href='products.php'">
            <i class="fas fa-tags sidebar-icon"></i>
            <div class="menu-label">Products</div> 
        </div>
        <div class="sales" onclick="window.location.href='sales.php'">
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

    <div class="card card-revenue">
      <div class="card-title">Revenue</div>
      <div class="card-content">
        <i class="fas fa-hand-holding-usd icon"></i>
        <span class="plus">+</span>
        <span class="amount">30,000</span>
      </div>
    </div>
    <div class="card card-srevenue">
      <div class="card-title">Sales Revenue</div>
      <div class="card-content">
        <i class="fas fa-hand-holding-usd icon"></i>
        <span class="plus">+</span>
        <span class="amount">12,500</span>
      </div>
    </div>
    <div class="card card-profit">
      <div class="card-title">Purchase</div>
      <div class="card-content">
        <i class="fas fa-hand-holding-usd icon"></i>
        <span class="plus">+</span>
        <span class="amount">16,500</span>
      </div>
    </div>
    <div class="card card-income">
      <div class="card-title">Income</div>
      <div class="card-content">
        <i class="fas fa-hand-holding-usd icon"></i>
        <span class="plus">+</span>
        <span class="amount">26,500</span>
      </div>
    </div>
    
    <div class="bar">
      <canvas id="barChart" width="940" height="240"></canvas>
    </div>
    <div class="pie">
    <canvas id="topProducts" width="300" height="240"></canvas>
    </div>

    <div class="stock-alert-container">
      <h3>Stock Alert</h3>
      <hr>
      <table class="stock-alert-table">
        <thead>
          <tr>
            <th>order ID</th>
            <th>Date</th>
            <th>Quantity</th>
            <th>Alert amt.</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>order ID</td>
            <td>Date</td>
            <td>Quantity</td>
            <td>Alert amt.</td>
            <td>Status</td>
          </tr>
          <tr>
            <td>order ID</td>
            <td>Date</td>
            <td>Quantity</td>
            <td>Alert amt.</td>
            <td>Status</td>
          </tr>
          <tr>
            <td>order ID</td>
            <td>Date</td>
            <td>Quantity</td>
            <td>Alert amt.</td>
            <td>Status</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="ts-product-container">
      <h3>Top Selling Product</h3>
      <hr>
      <table class="ts-product-table">
        <thead>
          <tr>
            <th>order ID</th>
            <th>Quantity</th>
            <th>Alert amt.</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>order ID</td>
            <td>Quantity</td>
            <td>Alert amt.</td>
          </tr>
          <tr>
            <td>order ID</td>
            <td>Quantity</td>
            <td>Alert amt.</td>
          </tr>
          <tr>
            <td>order ID</td>
            <td>Quantity</td>
            <td>Alert amt.</td>
          </tr>
        </tbody>
      </table>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/header.js"></script>
    <script src="js/charts.js"></script>

</body>
</html>
