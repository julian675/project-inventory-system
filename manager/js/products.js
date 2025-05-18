document.getElementById('searchInput').addEventListener('keyup', function () {
  const filter = this.value.toLowerCase();
  const rows = document.querySelectorAll('#itemsTable tr');

  rows.forEach(row => {
    const productName = row.cells[0]?.textContent.toLowerCase();
    row.style.display = (productName && productName.includes(filter)) ? '' : 'none';
  });
});
