function loadItems() {
  $.get("/project-inventory-system/manager/backend/instock_backend.php", function(data) {
    $("#itemsTable").html(data);
  });
}

$("#addProductForm").submit(function(e) {
  e.preventDefault();
  const form = this;

  $.ajax({
    url: "/project-inventory-system/manager/backend/instock_backend.php",
    method: "POST",
    data: $(form).serialize() + '&add=true',
    success: function(response) {
      // On success, reload items and reset form
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

$(document).on('click', '.plus-btn', function() {
  const id = $(this).data('id');
  $.post("/project-inventory-system/manager/backend/instock_backend.php", { update_quantity: true, id: id, delta: 1 }, loadItems);
});

$(document).on('change', '.price-input', function() {
  const id = $(this).data('id');
  const price = $(this).val();
  $.post("/project-inventory-system/manager/backend/instock_backend.php", { update_price: true, id: id, price: price }, loadItems);
});

$("#searchBox").on("input", function() {
  const filter = $(this).val().toLowerCase();
  $("#itemsTable tr").filter(function() {
    $(this).toggle($(this).find("td:eq(0)").text().toLowerCase().includes(filter));
  });
});

$(document).ready(loadItems);
