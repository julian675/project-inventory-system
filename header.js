const caretIcon = document.querySelector('.caret-section i');
const loginBox = document.getElementById('login-box');

// Toggle the visibility of the login box when the caret icon is clicked
caretIcon.addEventListener('click', () => {
  loginBox.style.display = loginBox.style.display === 'none' ? 'block' : 'none';
});
