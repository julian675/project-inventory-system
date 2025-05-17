document.getElementById('searchInput').addEventListener('keyup', function () {
  const filter = this.value.toLowerCase();
  const rows = document.querySelectorAll('#quantityTable tr');

  rows.forEach(row => {
    const productName = row.cells[0]?.textContent.toLowerCase();
    if (productName && productName.includes(filter)) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
});