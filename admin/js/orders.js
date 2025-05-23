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
  let value = parseInt(input.value);
  if (isNaN(value) || value < 1) {
    input.value = 1;
    value = 1;
  }
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
    const first = document.querySelector('.product-row');
    const clone = first.cloneNode(true);
    clone.querySelector('select').selectedIndex = 0;
    clone.querySelector('input[name="quantity[]"]').value = 1;
    clone.querySelector('.price').innerText = "0.00";
    clone.setAttribute('data-price', 0);
    document.getElementById('inventory').appendChild(clone);
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