
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventory Management System</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link href="css/invoice.css" rel="stylesheet"> 
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
        <div class="invoice">
            <i class="fas fa-file-invoice sidebar-icon"></i>
            <div class="menu-label">Invoice</div>
          </div>
    </div>
    <div class="container">
      <h2>Invoice</h2>
    </div>
    <div class="page">
  <div class="client-list">
    <button class="client-button" onclick="showDetails(this)">Client Name #1</button>
    <button class="client-button" onclick="showDetails(this)">Client Name #2</button>
    <button class="client-button" onclick="showDetails(this)">Client Name #</button>
    <button class="client-button" onclick="showDetails(this)">Client Name #</button>
    <button class="client-button" onclick="showDetails(this)">Client Name #</button>
    <button class="client-button" onclick="showDetails(this)">Client Name #</button>
    <button class="client-button" onclick="showDetails(this)">Client Name #</button>
    <button class="client-button" onclick="showDetails(this)">Client Name #</button>
    <button class="client-button" onclick="showDetails(this)">Client Name #</button>
    <button class="client-button" onclick="showDetails(this)">Client Name #</button>
    <button class="client-button" onclick="showDetails(this)">Client Name #</button>
    <button class="client-button" onclick="showDetails(this)">Client Name #</button>
    <button class="client-button" onclick="showDetails(this)">Client Name #</button>
    <button class="client-button" onclick="showDetails(this)">Client Name #</button>
    <button class="client-button" onclick="showDetails(this)">Client Name #</button>
    <button class="client-button" onclick="showDetails(this)">Client Name #</button>
  </div>

  <div id="details" class="details-section">
    <h3>YOUR DETAILS</h3>
    <p><strong>FROM</strong><br>Web Developer<br>ABC SELLER<br>Street, City<br>Philippines</p>
    <p>writer@gmail.com</p>
    <hr>
    <p>Invoice Number: 1234567</p>
    <p>Invoice Date: Dec 14th, 2023</p>
    <p>Due Date: Dec 14th, 2024</p>
    <hr>
    <p><strong>ITEM:</strong> Wordpress Design & Development<br>
    Responsive Wordpress site with booking/payment functionality</p>
    <p>Qty: 1 | Rate: PHP 23,100.00 | Subtotal: PHP 23,100.00</p>
    <hr>
    <p><strong>Invoice Summary</strong><br>
    Subtotal: PHP 23,100.00<br>
    Total: PHP 23,100.00</p>
  </div>
</div>


<script>
  function showDetails(button) {
    // Remove 'active' from all buttons
    document.querySelectorAll('.client-button').forEach(btn => btn.classList.remove('active'));

    // Add 'active' to the clicked one
    button.classList.add('active');

    // Show the details section
    document.getElementById('details').classList.add('active');
  }
</script>

    <script src="js/header.js"></script>
</body>
</html>
