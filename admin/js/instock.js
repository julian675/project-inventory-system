function loadItems() {
    $.get("instock_backend.php", function(response) {
        $("#itemsTable").html(response);
    });
}

$(document).ready(function () {
    loadItems();

    // Add new product
    $("#addProductForm").submit(function (e) {
        e.preventDefault();
        $.post("instock_backend.php", $(this).serialize() + '&add=true', function () {
            loadItems();
            $("#addProductForm")[0].reset(); // Clear form after adding
        });
    });

    // Increase quantity
    $(document).on('click', '.plus-btn', function () {
        const id = $(this).data('id');
        $.post("instock_backend.php", { update_quantity: true, id: id, delta: 1 }, function () {
            loadItems();
        });
    });

    // Decrease quantity
    $(document).on('click', '.minus-btn', function () {
        const id = $(this).data('id');
        $.post("instock_backend.php", { update_quantity: true, id: id, delta: -1 }, function () {
            loadItems();
        });
    });

    // Select/Deselect all checkboxes
    $("#selectAll").on("change", function () {
        $(".row-checkbox").prop("checked", this.checked);
    });

    // Delete selected rows
    $("#deleteSelected").click(function () {
        const ids = $(".row-checkbox:checked").map(function () {
            return this.value;
        }).get();

        if (ids.length === 0) {
            alert("Select at least one item.");
            return;
        }

        $.post("instock_backend.php", { delete_ids: ids }, function () {
            loadItems();
        });
    });

    // Live search
    $("#searchInput").on("input", function () {
        const filter = $(this).val().toLowerCase();
        $("#itemsTable .product-item").each(function () {
            const text = $(this).text().toLowerCase();
            $(this).toggle(text.includes(filter));
        });
    });
});
