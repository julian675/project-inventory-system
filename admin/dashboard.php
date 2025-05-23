<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin/login.php");
    exit;
}

$username = 'Guest';
if (isset($_SESSION['username'])) {
    $username = htmlspecialchars($_SESSION['username']);
}

include 'db_connection.php'; // Ensure this connects to `ims_db`

$orderData = [];

$sql = "
    SELECT DATE(o.order_date) AS order_day, SUM(oi.quantity) AS total_quantity
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    GROUP BY order_day
    ORDER BY order_day ASC
";

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $orderData[] = [
        'date' => $row['order_day'],
        'quantity' => $row['total_quantity']
    ];
}

$productSales = [];

// Fetch all products for pie chart
$sql = "
    SELECT product, quantity 
    FROM inventory
";

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $productSales[] = [
        'product' => $row['product'],
        'quantity' => $row['quantity']
    ];
}

// Fetch top 3 sold products for table
$topSoldProducts = [];

$sql = "
    SELECT i.product, SUM(oi.quantity) AS total_sold
    FROM order_items oi
    JOIN inventory i ON oi.product_id = i.id
    GROUP BY i.id, i.product
    ORDER BY total_sold DESC
    LIMIT 3
";

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $topSoldProducts[] = [
        'product' => $row['product'],
        'quantity' => $row['total_sold']
    ];
}

// ✅ Get critical stock for stock alert
$stockAlerts = [];

$sql = "SELECT product, quantity, status FROM inventory WHERE status = 'critical'";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $stockAlerts[] = [
        'product' => $row['product'],
        'quantity' => $row['quantity'],
        'status' => $row['status']
    ];
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
  <link href="css/dashboard.css" rel="stylesheet">
  <link href="/project-inventory-system/css/header.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<!-- Header -->
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

<!-- Sidebar -->
<div class="sidebar">
  <div class="menu-item dashboard">
    <i class="fas fa-chart-line sidebar-icon"></i>
    <div class="menu-label">Dashboard</div> 
  </div>
  <div class="menu-item inventory" onclick="window.location.href='/project-inventory-system/admin/inventory.php'">
    <i class="fas fa-boxes sidebar-icon"></i>
    <div class="menu-label">Inventory</div> 
  </div>
  <div class="menu-item products" onclick="window.location.href='/project-inventory-system/admin/products.php'">
    <i class="fas fa-tags sidebar-icon"></i>
    <div class="menu-label">Products</div> 
  </div>
  <div class="menu-item orders" onclick="window.location.href='/project-inventory-system/admin/orders.php'">
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

<!-- Main Content -->
<div class="main">
  <div class="chart-grid-2">
    <div class="chart-box">
      <div class="chart-wrapper">
        <canvas id="lineChart"></canvas>
      </div>
    </div>
    <div class="chart-box">
      <canvas id="pieChart"></canvas>
    </div>
  </div>

  <!-- ✅ Stock Report Section -->
  <div class="card-grid-2">
    <div class="table-card">
      <h4>Stock Alert</h4>
      <?php if (count($stockAlerts) > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($stockAlerts as $alert): ?>
            <tr>
              <td><?= htmlspecialchars($alert['product']) ?></td>
              <td><?= $alert['quantity'] ?></td>
              <td><?= ucfirst($alert['status']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
        <p>No critical stock items.</p>
      <?php endif; ?>
    </div>

    <div class="table-card">
      <h4>Top 3 Sold Products</h4>
      <?php if (count($topSoldProducts) > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Product</th>
            <th>Total Sold</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($topSoldProducts as $product): ?>
            <tr>
              <td><?= htmlspecialchars($product['product']) ?></td>
              <td><?= $product['quantity'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
        <p>No sales data.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
    const orderData = <?= json_encode($orderData); ?>;
    const productSales = <?= json_encode($productSales); ?>;
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('lineChart').getContext('2d');
    const labels = orderData.map(item => item.date);
    const data = orderData.map(item => item.quantity);

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Quantity Ordered',
          data: data,
          borderColor: 'rgba(54, 162, 235, 1)',
          backgroundColor: 'rgba(54, 162, 235, 0.2)',
          fill: true,
          tension: 0.3,
          pointRadius: 4
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: true },
          tooltip: { mode: 'index', intersect: false }
        },
        interaction: {
          mode: 'nearest',
          axis: 'x',
          intersect: false
        },
        scales: {
          x: { title: { display: true, text: 'Date' }},
          y: { beginAtZero: true, title: { display: true, text: 'Total Quantity' }}
        }
      }
    });

    const pieCtx = document.getElementById('pieChart').getContext('2d');
    const pieLabels = productSales.map(p => p.product);
    const pieData = productSales.map(p => p.quantity);

    new Chart(pieCtx, {
      type: 'pie',
      data: {
        labels: pieLabels,
        datasets: [{
          label: 'Product Stock',
          data: pieData,
          backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#FF9F40', '#4BC0C0', '#9966FF', '#C9CBCF'],
          borderColor: '#fff',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'right' },
          tooltip: {
            callbacks: {
              label: function (context) {
                return `${context.label}: ${context.raw} in stock`;
              }
            }
          }
        }
      }
    });
  });
</script>

<script src="/project-inventory-system/js/header.js"></script>
</body>
</html>
