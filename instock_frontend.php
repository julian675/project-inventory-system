<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Inventory Management</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
    }

    .status-pill {
      display: inline-block;
      width: 16px;
      height: 16px;
      border-radius: 12px;
    }

    .status-good { background-color: green; }
    .status-warning { background-color: orange; }
    .status-critical { background-color: red; }

    .plus-btn, .minus-btn {
      padding: 4px 8px;
      margin: 0 2px;
      background-color: #000;
      color: white;
      border: 1px solid #000;
      cursor: pointer;
    }

    .plus-btn:hover, .minus-btn:hover {
      background-color: #0056b3;
    }

    .price-input {
      width: 60px;
      border: 1px solid #ccc;
      padding: 2px 5px;
      text-align: right;
    }

    input[type="text"], input[type="number"], button {
      padding: 6px 10px;
      font-size: 14px;
    }

    #searchBox {
      margin-bottom: 10px;
      padding: 6px;
      width: 250px;
      font-size: 14px;
    }

    table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    border-top: 2px solid black;
    border-bottom: 2px solid black;
    }


    thead th {
    font-weight: bold;
    border-bottom: 2px solid black;
    }

    tr {
    border-bottom: 1px solid black;
    }

    th, td {
    text-align: center;
    padding: 8px;
    }

  </style>
</head>
<body>

<h2>Manage In-Stock Items</h2>

<input type="text" id="searchBox" placeholder="Search by product name...">

<form id="addProductForm" style="margin-bottom: 10px; display: flex; gap: 10px;">
  <input type="text" name="product" placeholder="Product name" required>
  <input type="number" name="items" placeholder="Quantity" min="1" required>
  <input type="number" name="price" placeholder="Price" step="0.01" min="0" required>
  <button type="submit">Add</button>
  <button type="button" id="deleteSelected">Delete Selected</button>
</form>

<table>
  <thead>
    <tr>
      <th><input type="checkbox" id="selectAll"></th>
      <th>Product</th>
      <th>Items</th>
      <th>Status</th>
      <th>Change</th>
      <th>Price</th>
    </tr>
  </thead>
  <tbody id="itemsTable"></tbody>
</table>

<script>
function loadItems() {
  $.get("instock_backend.php", function(data) {
    $("#itemsTable").html(data);
  });
}

$("#addProductForm").submit(function(e) {
  e.preventDefault();
  $.post("instock_backend.php", $(this).serialize() + '&add=true', loadItems);
});

$("#deleteSelected").click(function() {
  const ids = $(".row-checkbox:checked").map(function() {
    return this.value;
  }).get();

  if (ids.length === 0) return alert("Select at least one item.");

  $.post("instock_backend.php", { delete_ids: ids }, loadItems);
});

$(document).on('click', '.plus-btn', function() {
  const id = $(this).data('id');
  $.post("instock_backend.php", { update_quantity: true, id: id, delta: 1 }, loadItems);
});

$(document).on('click', '.minus-btn', function() {
  const id = $(this).data('id');
  $.post("instock_backend.php", { update_quantity: true, id: id, delta: -1 }, loadItems);
});

$(document).on('change', '.price-input', function() {
  const id = $(this).data('id');
  const price = $(this).val();
  $.post("instock_backend.php", { update_price: true, id: id, price: price }, loadItems);
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

</body>
</html>
