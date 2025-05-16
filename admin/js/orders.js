const form = document.getElementById('orderForm');
  const messageEl = document.getElementById('message');

  form.addEventListener('submit', function(e) {
    e.preventDefault();

    // Gather input values
    const clientName = form.clientName.value.trim();
    const clientAddress = form.clientAddress.value.trim();
    const contactNumber = form.contactNumber.value.trim();
    const companyName = form.companyName.value.trim();

    // Validate inputs (HTML5 validation mostly handles this)
    if (!clientName || !clientAddress || !contactNumber || !companyName) {
      messageEl.style.color = 'red';
      messageEl.textContent = 'Please fill in all fields correctly.';
      return;
    }

    // Capture current time and date
    const currentDateTime = new Date().toISOString();

    // Construct order object
    const order = {
      clientName,
      clientAddress,
      contactNumber,
      companyName,
      timestamp: currentDateTime
    };

    // Get existing orders from localStorage (simulate "ims_db")
    const existingData = localStorage.getItem('ims_db');
    const orders = existingData ? JSON.parse(existingData) : [];

    // Add new order
    orders.push(order);

    // Save back to localStorage
    localStorage.setItem('ims_db', JSON.stringify(orders));

    // Reset form
    form.reset();

    // Show success message
    messageEl.style.color = 'green';
    messageEl.textContent = 'Order submitted successfully!';

    // Clear message after 4 seconds
    setTimeout(() => {
      messageEl.textContent = '';
    }, 4000);
  });