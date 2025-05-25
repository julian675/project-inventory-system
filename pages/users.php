<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: /project-inventory-system/login.php");
    exit;
}

$username = 'Guest';
if (isset($_SESSION['username'])) {
    $username = htmlspecialchars($_SESSION['username']);
}

$host = "localhost";
$db = "ims_db";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, uname, fname, lname, role FROM users";
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
  <link href="css/users.css" rel="stylesheet">
  <link href="/project-inventory-system/css/header.css" rel="stylesheet">
</head>
<body>

<div class="header">
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
  <div class="menu-item dashboard" onclick="window.location.href='/project-inventory-system/pages/dashboard.php'">
    <i class="fas fa-chart-line sidebar-icon"></i>
    <div class="menu-label">Dashboard</div> 
  </div>
  <div class="menu-item inventory" onclick="window.location.href='/project-inventory-system/pages/inventory.php'">
      <i class="fas fa-boxes sidebar-icon"></i>
      <div class="menu-label">Inventory</div> 
  </div>
  <div class="menu-item products" onclick="window.location.href='/project-inventory-system/pages/products.php'">
      <i class="fas fa-tags sidebar-icon"></i>
      <div class="menu-label">Products</div> 
  </div>
  <div class="menu-item orders" onclick="window.location.href='/project-inventory-system/pages/orders.php'">
      <i class="fas fa-receipt sidebar-icon"></i>
      <div class="menu-label">Orders</div>
  </div>
  <div class="menu-item users">
    <i class="fas fa-users sidebar-icon"></i>
    <div class="menu-label">Users</div>
  </div>
  <div class="menu-item invoice" onclick="window.location.href='/project-inventory-system/pages/invoice.php'">
    <i class="fas fa-file-invoice sidebar-icon"></i>
    <div class="menu-label">Invoice</div>
  </div>
</div>

<div class="main">
  <div class="container">
    <h2>Users Table</h2>
    <?php if (isset($_GET['msg'])): ?>
      <p style="color: green;">
        <?php
          if ($_GET['msg'] == 'removed') echo "User account removed successfully.";
          elseif ($_GET['msg'] == 'promoted') echo "User promoted to admin successfully.";
        ?>
      </p>
    <?php endif; ?>
    <div class="center-wrapper">
      <table border="1" cellpadding="10" cellspacing="0">
        <thead>
          <tr>
            <th style="width: 80%;">Account Info</th>
            <th style="width: 20%;">Role & Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($result && $result->num_rows > 0):
              while ($row = $result->fetch_assoc()):
          ?>
              <tr>
                <td>
                  <div>
                    <div><strong>Username:</strong> <?= htmlspecialchars($row['uname']) ?></div>
                    <div><strong>First Name:</strong> <?= htmlspecialchars($row['fname']) ?></div>
                    <div><strong>Last Name:</strong> <?= htmlspecialchars($row['lname']) ?></div>
                  </div>
                </td>
                <td>
                  <div>
                    <div><strong><?= htmlspecialchars($row['role']) ?></strong></div>
                      <select onchange="handleAction(this.value, <?= $row['id'] ?>)" style="width: 100px; margin-top: 5px;">
                        <option value="">Actions</option>
                          <option value="promote">Promote to Admin</option>
                        <option value="remove">Remove Account</option>
                      </select>
                  </div>
                </td>
              </tr>
          <?php
              endwhile;
          else:
              echo "<tr><td colspan='2'>No users found.</td></tr>";
          endif;
          ?>
        </tbody>
      </table>
    </div>
  </div>           
</div>

<script>
function handleAction(action, userId) {
  if (!action) return;
  const confirmMsg = action === 'promote'
    ? "Are you sure you want to promote this user to Admin?"
    : "Are you sure you want to remove this account?";

  if (confirm(confirmMsg)) {
    fetch(`/project-inventory-system/pages/backend/user_action.php?action=${action}&id=${userId}`)
      .then(response => response.text())
      .then(data => {
        if (data.trim() === 'OK') {
          location.reload(); 
        } else {
          alert(data); 
        }
      })
      .catch(err => {
        alert("An error occurred: " + err);
      });
  }
}

</script>


<script src="/project-inventory-system/js/header.js"></script>
</body>
</html>
