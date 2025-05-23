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

    // 🔽 Set quantity to 1 if it is 0 or empty
    const quantityInput = productRow.querySelector('input[name="quantity[]"]');
    if (!quantityInput.value || parseInt(quantityInput.value) === 0) {
        quantityInput.value = 1;
    }

    updateRowTotal(productRow);
    updateGrandTotal();
    cleanEmptyProductRows();
}


function changeQty(button, delta) {
  const input = button.parentNode.querySelector('input[name="quantity[]"]');
  let current = parseInt(input.value) || 1;
  current += delta;
  if (current < 1) current = 1;
  input.value = current;
  const productRow = input.closest('.product-row');
  updateRowTotal(productRow);
  updateGrandTotal();
}

function manualQtyChange(input) {
  const productRow = input.closest('.product-row');
  updateRowTotal(productRow);
  updateGrandTotal();
}

function updateRowTotal(productRow) {
  const price = parseFloat(productRow.getAttribute('data-price')) || 0;
  const quantityInput = productRow.querySelector('input[name="quantity[]"]');
  const quantity = parseInt(quantityInput.value) || 1;
  const total = price * quantity;
  productRow.querySelector('.price').textContent = total.toFixed(2);
}

function updateGrandTotal() {
  const allRows = document.querySelectorAll('.product-row');
  let grandTotal = 0;
  allRows.forEach(row => {
    const rowTotal = parseFloat(row.querySelector('.price').textContent) || 0;
    grandTotal += rowTotal;
  });
  document.getElementById('grandTotal').textContent = grandTotal.toFixed(2);
}

function addProduct() {
    cleanEmptyProductRows();

    const last = document.querySelectorAll('.product-row');
    const lastSelect = last[last.length - 1].querySelector('select');
    if (!lastSelect.value) {
        alert("Please select a product before adding another.");
        return;
    }

    const first = document.querySelector('.product-row');
    const clone = first.cloneNode(true);
    clone.querySelector('select').selectedIndex = 0;
    clone.querySelector('input[name="quantity[]"]').value = 0;
    clone.querySelector('.price').innerText = "0.00";
    clone.setAttribute('data-price', 0);
    document.getElementById('inventory').appendChild(clone);
}

function removeProductRow(button) {
    const rows = document.querySelectorAll('.product-row');
    if (rows.length > 1) {
        const row = button.closest('.product-row');
        row.remove();
        updateGrandTotal();
    } else {
        alert("At least one product must remain.");
    }
}

function cleanEmptyProductRows() {
    const rows = document.querySelectorAll('.product-row');
    if (rows.length <= 1) return;

    rows.forEach(row => {
        const select = row.querySelector('select');
        if (!select.value) {
            row.remove();
        }
    });
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