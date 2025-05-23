
  function toggleLogin() {
    const dropdown = document.getElementById('login-dropdown');
    const isVisible = dropdown.style.display === 'block';
    dropdown.style.display = isVisible ? 'none' : 'block';
  }

  document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('login-dropdown');
    const caret = document.querySelector('.fa-caret-down');
    if (!dropdown.contains(event.target) && !caret.contains(event.target)) {
      dropdown.style.display = 'none';
    }
  });

