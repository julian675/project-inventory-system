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

$conn = new mysqli('localhost', 'root', '', 'ims_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle client deletion
if (isset($_POST['delete_client'])) {
    $delete_client_id = intval($_POST['delete_client_id']);
    $conn->begin_transaction();

    try {
        // Delete order_items linked to this client via orders
        $stmt = $conn->prepare("DELETE oi FROM order_items oi JOIN orders o ON oi.order_id = o.id WHERE o.client_id = ?");
        if (!$stmt) throw new Exception($conn->error);
        $stmt->bind_param("i", $delete_client_id);
        $stmt->execute();

        // Delete orders for this client
        $stmt = $conn->prepare("DELETE FROM orders WHERE client_id = ?");
        if (!$stmt) throw new Exception($conn->error);
        $stmt->bind_param("i", $delete_client_id);
        $stmt->execute();

        // Delete the client
        $stmt = $conn->prepare("DELETE FROM clients WHERE id = ?");
        if (!$stmt) throw new Exception($conn->error);
        $stmt->bind_param("i", $delete_client_id);
        $stmt->execute();

        $conn->commit();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Failed to delete client: " . $e->getMessage();
        exit();
    }
}

// Fetch products for dropdown
$instock_result = $conn->query("SELECT * FROM instock");
$instock = [];
while ($row = $instock_result->fetch_assoc()) {
    $instock[] = $row;
}

// Handle new order submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['delete_client'])) {
    $conn->begin_transaction();

    try {
        // Insert client
        $stmt = $conn->prepare("INSERT INTO clients (name, address, contact_number, company_name) VALUES (?, ?, ?, ?)");
        if (!$stmt) throw new Exception($conn->error);
        $stmt->bind_param("ssss", $_POST['name'], $_POST['address'], $_POST['contact'], $_POST['company']);
        $stmt->execute();
        $client_id = $stmt->insert_id;

        $grand_total = 0;
        $order_items = [];

        for ($i = 0; $i < count($_POST['instock']); $i++) {
            $product_id = $_POST['instock'][$i];
            $quantity = $_POST['quantity'][$i];

            // Get product price
            $stmt = $conn->prepare("SELECT price FROM instock WHERE id = ?");
            if (!$stmt) throw new Exception($conn->error);
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $price = $result['price'];
            $total = $price * $quantity;
            $grand_total += $total;

            $order_items[] = [
                'product_id' => $product_id,
                'quantity' => $quantity,
                'price' => $price,
                'total_price' => $total
            ];
        }

        // Insert order
        $stmt = $conn->prepare("INSERT INTO orders (client_id, order_date, grand_total) VALUES (?, NOW(), ?)");
        if (!$stmt) throw new Exception($conn->error);
        $stmt->bind_param("id", $client_id, $grand_total);
        $stmt->execute();
        $order_id = $stmt->insert_id;

        foreach ($order_items as $item) {
            // Check stock availability
            $stmtCheck = $conn->prepare("SELECT quantity FROM instock WHERE id = ?");
            if (!$stmtCheck) throw new Exception($conn->error);
            $stmtCheck->bind_param("i", $item['product_id']);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->get_result()->fetch_assoc();

            if ($resultCheck['quantity'] < $item['quantity']) {
                throw new Exception("Not enough stock for product ID " . $item['product_id']);
            }

            // Insert order item
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price, total_price) VALUES (?, ?, ?, ?, ?)");
            if (!$stmt) throw new Exception($conn->error);
            $stmt->bind_param("iiidd", $order_id, $item['product_id'], $item['quantity'], $item['price'], $item['total_price']);
            $stmt->execute();

            // Update inventory
            $stmt = $conn->prepare("UPDATE instock SET quantity = quantity - ? WHERE id = ?");
            if (!$stmt) throw new Exception($conn->error);
            $stmt->bind_param("ii", $item['quantity'], $item['product_id']);
            $stmt->execute();
        }

        $conn->commit();
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Failed to place order: " . $e->getMessage();
        exit();
    }
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
  <link href="css/orders.css" rel="stylesheet">
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
            <a href="/project-inventory-system/logout.php" class="login-button">Log Out</a>
        <?php else: ?>
            <a href="/project-inventory-system/login.php" class="login-button">Log In</a>
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
          <div class="menu-item orders">
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
  <div class="form-and-list">

    <!-- Form Container -->
    <div class="container">
      <h1>Orders</h1>
      <form method="POST">
        <div class="form-container">
          <div class="form-section">
            <h3>Client Info</h3>
            <label>Name: <input type="text" name="name" required></label>
            <label>Address: <input type="text" name="address" required></label>
            <label>Contact Number: <input type="text" name="contact" required></label>
            <label>Company Name: <input type="text" name="company"></label>
          </div>

          <div class="form-section instock">
            <h3>Products</h3>
            <div class="scroll-box" id="instock">
              <div class="product-row" data-price="0">
                <select name="instock[]" onchange="updateUnitPrice(this)">
                  <option value="" disabled selected>Select Product</option>
                  <?php foreach ($instock as $product): ?>
                    <option value="<?= $product['id'] ?>" data-price="<?= $product['price'] ?>">
                      <?= htmlspecialchars($product['product']) ?> - ₱<?= $product['price'] ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <button type="button" onclick="changeQty(this, -1)">−</button>
                <input type="number" name="quantity[]" value="1" min="1" readonly style="width: 30px; text-align: center;">
                <button type="button" onclick="changeQty(this, 1)">+</button>
                <span>₱<span class="price">0.00</span></span>
              </div>
            </div>

            <p><strong>Total: ₱<span id="grandTotal">0.00</span></strong></p>
            <button type="button" onclick="addProduct()">Add Another Product</button>
            <button type="submit">Submit Order</button>
          </div>
        </div>
      </form>
    </div>

    <!-- Clients List -->
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
              <th>Action</th>
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

            if ($result->num_rows > 0):
              while ($row = $result->fetch_assoc()):
            ?>
              <tr class="clickable-row" data-id="<?= $row['order_id'] ?>" data-client-id="<?= $row['client_id'] ?>">
                <td><?= htmlspecialchars($row['order_id']) ?></td>
                <td><?= htmlspecialchars($row['client_name']) ?></td>
                <td><?= htmlspecialchars($row['order_date']) ?></td>
                <td>₱<?= number_format($row['grand_total'], 2) ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td>
                  <form method="POST" onsubmit="return confirm('Are you sure you want to delete this client and all related data?');">
                    <input type="hidden" name="delete_client_id" value="<?= $row['client_id'] ?>">
                    <button type="submit" name="delete_client">Remove Client</button>
                  </form>
                </td>
              </tr>
            <?php endwhile; else: ?>
              <tr><td colspan="6">No orders found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div> <!-- end .form-and-list -->
</div> <!-- end .main -->


  <script src="/project-inventory-system/js/header.js"></script>
  <script src="js/orders.js"></script>
</body>
</html>
