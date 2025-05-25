<?php
session_start();
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'manager'])) {
    header("Location: /project-inventory-system/login.php");
    exit;
}

$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest';
require_once('db_connection.php');
require_once('backend/orders_backend.php');

$error_message = $_SESSION['error_message'] ?? '';
unset($_SESSION['error_message']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_client'])) {
        deleteClient($conn, intval($_POST['delete_client_id']));
    } else {
        placeOrder($conn, $_POST);
    }
}

$inventory = getInventory($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard UI</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="css/orders.css" rel="stylesheet">
  <link href="/project-inventory-system/css/header.css" rel="stylesheet">
</head>
<body>


    <?php if (!empty($error_message)): ?> 
    <div id="notification">
      <?= htmlspecialchars($error_message) ?>
    </div>
  <?php endif; ?>


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
          <div class="menu-item orders">
            <i class="fas fa-receipt sidebar-icon"></i>
            <div class="menu-label">Orders</div>
          </div>
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <div class="menu-item users" onclick="window.location.href='/project-inventory-system/pages/users.php'">
              <i class="fas fa-users sidebar-icon"></i>
              <div class="menu-label">Users</div>
            </div>
          <?php endif; ?>
          <div class="menu-item invoice" onclick="window.location.href='/project-inventory-system/pages/invoice.php'">
            <i class="fas fa-file-invoice sidebar-icon"></i>
            <div class="menu-label">Invoice</div>
          </div>
  </div>



<div class="main">
  <div class="form-and-list">

    <div class="container">
      <h1>Orders</h1>
      <form method="POST" onsubmit="return validateOrderForm();">
        <div class="form-container">
          <div class="form-section">
            <h3>Client Info</h3>
            <label>Name: <input type="text" name="name" required></label>
            <label>Address: <input type="text" name="address" required></label>
            <label>Contact Number: <input type="text" name="contact" required></label>
            <label>Company Name: <input type="text" name="company"></label>
          </div>

          <div class="form-section inventory">
            <h3>Products</h3>
            
            <div class="scroll-box" id="inventory">
              <div class="product-row" data-price="0" style="display: flex; gap: 8px; align-items: center; margin-bottom: 8px;">
                <select name="inventory[]" onchange="updateUnitPrice(this)">
                  <option value="" disabled selected>Select Product</option>
                  <?php foreach ($inventory as $product): ?>
                    <option value="<?= $product['id'] ?>" data-price="<?= $product['price'] ?>">
                      <?= htmlspecialchars($product['product']) ?> - ₱<?= $product['price'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>

                <button type="button" onclick="changeQty(this, -1)">−</button>
                <input type="number" name="quantity[]" value="0" min="0" 
                      style="width: auto; max-width: 80px; text-align: center;" 
                      oninput="manualQtyChange(this)">
                <button type="button" onclick="changeQty(this, 1)">+</button>
                <span>₱<span class="price">0.00</span></span>

                <button type="button" onclick="removeProductRow(this)" style="color: white; background: red; border: none; padding: 4px 8px; border-radius: 4px;">
                  Remove
                </button>
              </div>
            </div>

            <div class="form-summary" >
              <p><strong>Total: ₱<span id="grandTotal">0.00</span></strong></p>
            </div>
            <div class="form-actions">
              <button type="button" class="action-button" onclick="addProduct()">Add Another Product</button>
              <button type="submit" class="action-button">Submit Order</button>
            </div>
          </div>

      </form>
    </div>

    <div class="clients-list">
      <h3>Existing Orders</h3>
      <div style="overflow-x: auto; width: 100%;">
        <table border="1" cellpadding="8" cellspacing="0" class="order-table">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Client Name</th>
              <th>Order Date</th>
              <th>Grand Total</th>
              <th>Status</th>
              <?php if ($_SESSION['role'] === 'admin'): ?>
                  <th>Action</th>
              <?php endif; ?>
            </tr>
          </thead>
            <tbody>
            <?php
            $result = $conn->query("
              SELECT orders.id AS order_id, clients.id AS client_id, clients.name AS client_name, 
                    orders.order_date, orders.grand_total, orders.status
              FROM orders
              JOIN clients ON orders.client_id = clients.id
              ORDER BY orders.order_date ASC
            ");
              if (!$result) {
                  echo "<tr><td colspan='6'>Error fetching orders: " . $conn->error . "</td></tr>";
              } elseif ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    ?>
                    <tr class="clickable-row" data-id="<?= $row['order_id'] ?>" data-client-id="<?= $row['client_id'] ?>">
                      <td><?= htmlspecialchars($row['order_id']) ?></td>
                      <td><?= htmlspecialchars($row['client_name']) ?></td>
                      <td><?= htmlspecialchars($row['order_date']) ?></td>
                      <td>₱<?= number_format($row['grand_total'], 2) ?></td>
                      <td><?= htmlspecialchars($row['status']) ?></td>
                      <?php if ($_SESSION['role'] === 'admin'): ?>
                      <td>
                        <form method="POST" onsubmit="return confirm('Are you sure you want to delete this client and all related data?');">
                          <input type="hidden" name="delete_client_id" value="<?= $row['client_id'] ?>">
                          <button type="submit" name="delete_client">Remove Client</button>
                        </form>
                      </td>
                    <?php endif; ?>
                    </tr>
                    <?php
                  } 
              } else {
                  echo "<tr><td colspan='6'>No orders found.</td></tr>";
              }
            ?>
            </tbody>

        </table>
      </div>
    </div>

  </div> 
</div> 

              
  <script src="/project-inventory-system/js/header.js"></script>
  <script src="js/orders.js"></script>
</body>
</html>
