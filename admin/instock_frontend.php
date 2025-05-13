<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery for AJAX -->
    <style>

</style>

</head>
<body>

<h2>In Stock Items</h2>
<h2>Manage In-Stock Items</h2>

<!-- Add/Delete Controls -->
<form id="addProductForm" style="margin-bottom: 10px; display: flex; gap: 10px;">
    <input type="text" name="product" placeholder="Product name" required>
    <input type="number" name="items" placeholder="Quantity" min="1" required>
    <button type="submit">Add</button>
    <button type="button" id="deleteSelected">Delete Selected</button>
</form>


<!-- In Stock Table -->
<table border="1" cellpadding="6">
    <thead>
        <tr>
            <th><input type="checkbox" id="selectAll"></th>
            <th>Product</th>
            <th>Items</th>
            <th>Status</th>
            <th>Change</th>
        </tr>
    </thead>
    <tbody id="itemsTable">
        <?php include 'instock_backend.php'; ?>
    </tbody>
</table>



<script>
function loadItems() {
    $.get("instock_backend.php", function(response) {
        $("#itemsTable").html(response);
    });
}

$("#addProductForm").submit(function(e) {
    e.preventDefault();
    $.post("instock_backend.php", $(this).serialize() + '&add=true', function() {
        loadItems();
    });
});

$(document).on('click', '.plus-btn', function() {
    const id = $(this).data('id');
    $.post("instock_backend.php", { update_quantity: true, id: id, delta: 1 }, function() {
        loadItems();
    });
});

$(document).on('click', '.minus-btn', function() {
    const id = $(this).data('id');
    $.post("instock_backend.php", { update_quantity: true, id: id, delta: -1 }, function() {
        loadItems();
    });
});

$("#selectAll").on("change", function() {
    $(".row-checkbox").prop("checked", this.checked);
});

$("#deleteSelected").click(function() {
    const ids = $(".row-checkbox:checked").map(function() {
        return this.value;
    }).get();

    if (ids.length === 0) {
        alert("Select at least one item.");
        return;
    }

    $.post("instock_backend.php", { delete_ids: ids }, function() {
        loadItems();
    });
});

$(document).ready(loadItems);
</script>


</body>
</html>
