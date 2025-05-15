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
        <div class="menu-item dashboard" onclick="window.location.href='/project-inventory-system/manager/dashboard.php'">
          <i class="fas fa-chart-line sidebar-icon"></i>
          <div class="menu-label">Dashboard</div> 
        </div>
        <div class="menu-item instock" onclick="window.location.href='/project-inventory-system/manager/instock.php'">
            <i class="fas fa-boxes sidebar-icon"></i>
            <div class="menu-label">In Stock</div> 
        </div>
        <div class="menu-item products">
            <i class="fas fa-tags sidebar-icon"></i>
            <div class="menu-label">Products</div> 
        </div>
        <div class="menu-item sales" onclick="window.location.href='/project-inventory-system/manager/sales.php'">
            <i class="fas fa-cash-register sidebar-icon"></i>
            <div class="menu-label">Sales</div>
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
   <div class="blank-container">
      <div class="container">
        <div class="left-side" style="background-image: url('img/product1.jpg');"></div>
        <div class="middle">
          <h3>Product 1</h3>
          <p>Product details go here.</p>
        </div>
        <div class="right-side">
          <p>$19.99</p>
          <p>Additional details</p>
        </div>
      </div>
    </div>
    <div class="blank-container">
      <div class="container">
        <div class="left-side" style="background-image: url('img/product2.png');"></div>
        <div class="middle">
          <h3>Product 2</h3>
          <p>Product details go here.</p>
        </div>
        <div class="right-side">
          <p>$19.99</p>
          <p>Additional details</p>
        </div>
      </div>
    </div>
    <div class="blank-container">
      <div class="container">
        <div class="left-side" style="background-image: url('img/product3.png');"></div>
        <div class="middle">
          <h3>Product 3</h3>
          <p>Product details go here.</p>
        </div>
        <div class="right-side">
          <p>$19.99</p>
          <p>Additional details</p>
        </div>
      </div>
    </div>
    <div class="blank-container">
      <div class="container">
        <div class="left-side" style="background-image: url('img/product4.jpg');"></div>
        <div class="middle">
          <h3>Product 4</h3>
          <p>Product details go here.</p>
        </div>
        <div class="right-side">
          <p>$19.99</p>
          <p>Additional details</p>
        </div>
      </div>
    </div>
    <div class="blank-container">
      <div class="container">
        <div class="left-side" style="background-image: url('img/product5.jpg');"></div>
        <div class="middle">
          <h3>Product 5</h3>
          <p>Product details go here.</p>
        </div>
        <div class="right-side">
          <p>$19.99</p>
          <p>Additional details</p>
        </div>
      </div>
    </div>
    <div class="blank-container">
      <div class="container">
        <div class="left-side" style="background-image: url('img/product6.jpg');"></div>
        <div class="middle">
          <h3>Product 6</h3>
          <p>Product details go here.</p>
        </div>
        <div class="right-side">
          <p>$19.99</p>
          <p>Additional details</p>
        </div>
      </div>
    </div>
    <div class="blank-container">
      <div class="container">
        <div class="left-side" style="background-image: url('img/product7.jpg');"></div>
        <div class="middle">
          <h3>Product 7</h3>
          <p>Product details go here.</p>
        </div>
        <div class="right-side">
          <p>$19.99</p>
          <p>Additional details</p>
        </div>
      </div>
    </div>
    <div class="blank-container">
      <div class="container">
        <div class="left-side" style="background-image: url('img/product8.jpg');"></div>
        <div class="middle">
          <h3>Product 8</h3>
          <p>Product details go here.</p>
        </div>
        <div class="right-side">
          <p>$19.99</p>
          <p>Additional details</p>
        </div>
      </div>
    </div>
    <div class="blank-container">
      <div class="container">
        <div class="left-side" style="background-image: url('img/product9.png');"></div>
        <div class="middle">
          <h3>Product 9</h3>
          <p>Product details go here.</p>
        </div>
        <div class="right-side">
          <p>$19.99</p>
          <p>Additional details</p>
        </div>
      </div>
    </div>
    <div class="blank-container">
      <div class="container">
        <div class="left-side" style="background-image: url('img/product10.jpg');"></div>
        <div class="middle">
          <h3>Product 10</h3>
          <p>Product details go here.</p>
        </div>
        <div class="right-side">
          <p>$19.99</p>
          <p>Additional details</p>
        </div>
      </div>
    </div>
  </div>
   

  <script src="/project-inventory-system/js/header.js"></script>
</body>
</html>