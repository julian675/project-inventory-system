
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventory Management System</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link href="css/orders.css" rel="stylesheet"> 
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
          <div class="orders">
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
    <div class="container">
      <h2>Orders</h2>

      <div class="top-buttons">
        <button>Export to Excel</button>
        <button>Import Orders</button>
        <button class="new-order">+ New Orders</button>
      </div>

      <!-- Filters -->
      <div style="display: flex; gap: 10px; margin-bottom: 10px;">
        <input type="text" placeholder="Search order ID" />
        <button>📅</button>
        <select><option>Sales</option></select>
        <select><option>Status</option></select>
        <button>Filter</button>
      </div>

      <!-- Orders Table -->
      <table>
        <thead>
          <tr>
            <th><input type="checkbox" /></th>
            <th>Order ID</th>
            <th>Date</th>
            <th>Customer</th>
            <th>Sales channel</th>
            <th>Destination</th>
            <th>Items</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><input type="checkbox" /></td>
            <td>#7676</td>
            <td>08/30/2022</td>
            <td>Ramesh Chaudhary</td>
            <td>Store name</td>
            <td>Lalitpur</td>
            <td>3</td>
            <td><span class="status completed">Completed</span></td>
          </tr>
          <tr>
            <td><input type="checkbox" /></td>
            <td>#7676</td>
            <td>08/30/2022</td>
            <td>Ramesh Chaudhary</td>
            <td>Store name</td>
            <td>Lalitpur</td>
            <td>3</td>
            <td><span class="status pending">Pending</span></td>
          </tr>
          <tr>
            <td><input type="checkbox" /></td>
            <td>#7676</td>
            <td>08/30/2022</td>
            <td>Ramesh Chaudhary</td>
            <td>Store name</td>
            <td>Lalitpur</td>
            <td>3</td>
            <td><span class="status completed">Completed</span></td>
          </tr>
          <tr>
            <td><input type="checkbox" /></td>
            <td>#7676</td>
            <td>08/30/2022</td>
            <td>Ramesh Chaudhary</td>
            <td>Store name</td>
            <td>Lalitpur</td>
            <td>3</td>
            <td><span class="status completed">Completed</span></td>
          </tr>
          <tr>
            <td><input type="checkbox" /></td>
            <td>#7676</td>
            <td>08/30/2022</td>
            <td>Ramesh Chaudhary</td>
            <td>Store name</td>
            <td>Lalitpur</td>
            <td>3</td>
            <td><span class="status completed">Completed</span></td>
          </tr>
          <tr>
            <td><input type="checkbox" /></td>
            <td>#7676</td>
            <td>08/30/2022</td>
            <td>Ramesh Chaudhary</td>
            <td>Store name</td>
            <td>Lalitpur</td>
            <td>3</td>
            <td><span class="status completed">Completed</span></td>
          </tr>
          <tr>
            <td><input type="checkbox" /></td>
            <td>#7676</td>
            <td>08/30/2022</td>
            <td>Ramesh Chaudhary</td>
            <td>Store name</td>
            <td>Lalitpur</td>
            <td>3</td>
            <td><span class="status completed">Completed</span></td>
          </tr>
          <tr>
            <td><input type="checkbox" /></td>
            <td>#7676</td>
            <td>08/30/2022</td>
            <td>Ramesh Chaudhary</td>
            <td>Store name</td>
            <td>Lalitpur</td>
            <td>3</td>
            <td><span class="status completed">Completed</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  
    <script src="js/header.js"></script>
</body>
</html>
