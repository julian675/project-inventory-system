function loadItems() {
  $.get("/project-inventory-system/admin/backend/instock_backend.php", function(data) {
    $("#itemsTable").html(data);
  });
}

$("#addProductForm").submit(function(e) {
  e.preventDefault();
  $.post("/project-inventory-system/admin/backend/instock_backend.php", $(this).serialize() + '&add=true', loadItems);
});

$("#deleteSelected").click(function() {
  const ids = $(".row-checkbox:checked").map(function() {
    return this.value;
  }).get();

  if (ids.length === 0) return alert("Select at least one item.");

  $.post("/project-inventory-system/admin/backend/instock_backend.php", { delete_ids: ids }, loadItems);
});

$(document).on('click', '.plus-btn', function() {
  const id = $(this).data('id');
  $.post("/project-inventory-system/admin/backend/instock_backend.php", { update_quantity: true, id: id, delta: 1 }, loadItems);
});

$(document).on('click', '.minus-btn', function() {
  const id = $(this).data('id');
  $.post("/project-inventory-system/admin/backend/instock_backend.php", { update_quantity: true, id: id, delta: -1 }, loadItems);
});

$(document).on('change', '.price-input', function() {
  const id = $(this).data('id');
  const price = $(this).val();
  $.post("/project-inventory-system/admin/backend/instock_backend.php", { update_price: true, id: id, price: price }, loadItems);
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