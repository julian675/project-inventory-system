<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin/login.php");
    exit;
}

$username = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard UI</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="css/inventory.css" rel="stylesheet">
  <link href="/project-inventory-system/css/header.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
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
  <div class="menu-item inventory active">
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

<div class="main">
  <div class="blank-container">
    <div class="container">
      <h1>Inventory</h1>
        <div class="search-wrapper">
          <input type="text" id="searchBox" placeholder="Search by product name...">
          <i class="fas fa-search search-icon"></i>
        </div>
        <form id="addProductForm" class="form-production">
          <input type="text" name="product" placeholder="Product name" required>
          <input type="number" name="quantity" placeholder="Quantity" min="1" required>
          <input type="number" name="price" placeholder="Price" step="0.01" min="0" required>
          <button type="submit" class="primary-btn">Add</button>
          <button type="button" id="deleteSelected" class="primary-btn">Delete Selected</button>
        </form>

        <table>
          <thead>
            <tr>
              <th><input type="checkbox" id="selectAll"></th>
              <th>Product Name</th>
              <th>Quantity</th>
              <th>Status</th>
              <th>Change</th>
              <th>Unit Price</th>
            </tr>
          </thead>
          <tbody id="itemsTable"></tbody>
        </table>
    </div>
  </div>
</div>

<script>
function loadItems() {
  $.get("/project-inventory-system/admin/backend/inventory_backend.php", function(data) {
    $("#itemsTable").html(data);
  });
}

$("#addProductForm").submit(function(e) {
  e.preventDefault();
  const form = this;

  $.ajax({
    url: "/project-inventory-system/admin/backend/inventory_backend.php",
    method: "POST",
    data: $(form).serialize() + '&add=true',
    success: function() {
      loadItems();
      form.reset();
      alert('Product added successfully.');
    },
    error: function(xhr) {
      if (xhr.status === 409) {
        alert("Product already exists!");
      } else {
        alert("Failed to add product.");
      }
    }
  });
});

$("#deleteSelected").click(function() {
  const ids = $(".row-checkbox:checked").map(function() {
    return this.value;
  }).get();

  if (ids.length === 0) return alert("Select at least one item.");

  $.post("/project-inventory-system/admin/backend/inventory_backend.php", { delete_ids: ids }, loadItems);
});

$(document).on('click', '.plus-btn', function() {
  const id = $(this).data('id');
  $.post("/project-inventory-system/admin/backend/inventory_backend.php", { update_quantity: true, id: id, delta: 1 }, loadItems);
});

$(document).on('click', '.minus-btn', function() {
  const id = $(this).data('id');
  $.post("/project-inventory-system/admin/backend/inventory_backend.php", { update_quantity: true, id: id, delta: -1 }, loadItems);
});

$(document).on('change', '.price-input', function() {
  const id = $(this).data('id');
  const price = $(this).val();
  $.post("/project-inventory-system/admin/backend/inventory_backend.php", { update_price: true, id: id, price: price }, loadItems);
});

$("#selectAll").on("change", function() {
  $(".row-checkbox").prop("checked", this.checked);
});

$("#searchBox").on("input", function() {
  const filter = $(this).val().toLowerCase();
  $("#itemsTable tr").filter(function() {
    $(this).toggle($(this).find("td:eq(1)").text().toLowerCase().includes(filter));
  });
});

$(document).ready(loadItems);
</script>
<script src="/project-inventory-system/js/header.js"></script>
</body>
</html>
