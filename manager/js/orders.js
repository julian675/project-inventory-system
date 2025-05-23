function updateUnitPrice(select) {
    const selectedProductId = select.value;
    const allSelects = document.querySelectorAll('select[name="inventory[]"]');
    let duplicateFound = false;

    allSelects.forEach(sel => {
        if (sel !== select && sel.value === selectedProductId) {
            duplicateFound = true;
        }
    });

    if (duplicateFound) {
        alert("This product is already selected. Please choose a different product.");
        select.selectedIndex = 0;
        return;
    }

    const productRow = select.closest('.product-row');
    const price = parseFloat(select.selectedOptions[0].getAttribute('data-price'));
    productRow.setAttribute('data-price', price);
    updateRowTotal(productRow);
    updateGrandTotal();
}

function removeProductRow(button) {
    const row = button.closest('.product-row');
    row.classList.add('removed-row');  // visually mark as removed
    row.setAttribute('data-removed', 'true');

    // Optionally disable all inputs to avoid submission
    row.querySelectorAll('input, select, button').forEach(el => {
        el.disabled = true;
    });

    updateGrandTotal();
}


// Other existing JavaScript functions...

function changeQty(button, delta) {
    const input = button.parentNode.querySelector('input[name="quantity[]"]');
    let qty = parseInt(input.value);
    if (isNaN(qty) || qty < 1) qty = 1;
    qty += delta;
    if (qty < 1) qty = 1;
    input.value = qty;

    const row = button.closest('.product-row');
    updateRowTotal(row);
    updateGrandTotal();
}


function updateRowTotal(row) {
    const price = parseFloat(row.getAttribute('data-price')) || 0;
    const qty = parseInt(row.querySelector('input[name="quantity[]"]').value);
    const total = price * qty;
    row.querySelector('.price').innerText = total.toFixed(2);
}

function updateGrandTotal() {
    let total = 0;
    document.querySelectorAll('.product-row').forEach(row => {
        if (row.getAttribute('data-removed') === 'true') return;
        const qty = parseInt(row.querySelector('input[name="quantity[]"]').value);
        const price = parseFloat(row.getAttribute('data-price')) || 0;
        total += price * qty;
    });
    document.getElementById('grandTotal').innerText = total.toFixed(2);
}


function addProduct() {
    const first = document.querySelector('.product-row');
    const clone = first.cloneNode(true);
    clone.querySelector('select').selectedIndex = 0;
    clone.querySelector('input[name="quantity[]"]').value = 1;
    clone.querySelector('.price').innerText = "0.00";
    clone.setAttribute('data-price', 0);
    document.getElementById('instock').appendChild(clone);
}

document.addEventListener("DOMContentLoaded", function () {
    const rows = document.querySelectorAll(".clickable-row");
    rows.forEach(row => {
        row.addEventListener("click", function () {
            const clientId = this.getAttribute("data-client-id");
            if (clientId) {
                window.location.href = "invoice.php?client_id=" + clientId;
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const rows = document.querySelectorAll(".clickable-row");
    rows.forEach(row => {
        if (row.classList.contains('removed-row')) return; // skip removed rows
        row.addEventListener("click", function () {
            const clientId = this.getAttribute("data-client-id");
            if (clientId) {
                window.location.href = "invoice.php?client_id=" + clientId;
            }
        });
    });
});



