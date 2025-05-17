<?php
session_start();

// Block access if not logged in or not admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin/login.php");
    exit;
}

// DB connection
$host = "localhost";
$db = "ims_db";
$user = "root";
$pass = ""; // change if you have a password

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
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
  <link href="css/users.css" rel="stylesheet">
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
            <a href="/new_exp/logout.php" class="login-button">Log Out</a>
        <?php else: ?>
            <a href="/new_exp/login.php" class="login-button">Log In</a>
        <?php endif; ?>
    </div>




  <div class="sidebar">
        <div class="menu-item dashboard" onclick="window.location.href='/project-inventory-system/admin/dashboard.php'">
          <i class="fas fa-chart-line sidebar-icon"></i>
          <div class="menu-label">Dashboard</div> 
        </div>
        <div class="menu-item instock" onclick="window.location.href='/project-inventory-system/admin/instock.php'">
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
          <div class="menu-item users">
            <i class="fas fa-users sidebar-icon"></i>
            <div class="menu-label">Users</div>
          </div>
          <div class="menu-item invoice" onclick="window.location.href='/project-inventory-system/admin/invoice.php'">
            <i class="fas fa-file-invoice sidebar-icon"></i>
            <div class="menu-label">Invoice</div>
          </div>
  </div>



  <div class="main">
<div class="main">
  <h2 style="margin-bottom: 1rem;">Users Table</h2>
  <table border="1" cellpadding="10" cellspacing="0">
    <thead>
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Role</th>
        <th>Email</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT id, username, role, email FROM users";
      $result = $conn->query($sql);

      if ($result && $result->num_rows > 0):
          while ($row = $result->fetch_assoc()):
      ?>
          <tr>
              <td><?= htmlspecialchars($row['id']) ?></td>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td><?= htmlspecialchars($row['role']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
          </tr>
      <?php
          endwhile;
      else:
          echo "<tr><td colspan='4'>No users found.</td></tr>";
      endif;
      ?>
    </tbody>
  </table>
</div>

</div>
  

  <script src="/project-inventory-system/js/header.js"></script>
</body>
</html>
