

// View password toggle
function togglePassword() {
  const password = document.getElementById('password');
  const icon = document.getElementById('togglePasswordIcon');
  if (password.type === 'password') {
      password.type = 'text';
      icon.classList.remove('bi-eye');
      icon.classList.add('bi-eye-slash');
  } else {
      password.type = 'password';
      icon.classList.remove('bi-eye-slash');
      icon.classList.add('bi-eye');
  }
}

function toggleCPassword() {
  const password = document.getElementById('cpassword');
  const icon = document.getElementById('toggleCPasswordIcon');
  if (password.type === 'password') {
      password.type = 'text';
      icon.classList.remove('bi-eye');
      icon.classList.add('bi-eye-slash');
  } else {
      password.type = 'password';
      icon.classList.remove('bi-eye-slash');
      icon.classList.add('bi-eye');
  }
}

// Attach toggle password functions
document.getElementById('togglePasswordIcon')?.addEventListener('click', togglePassword);
document.getElementById('toggleCPasswordIcon')?.addEventListener('click', toggleCPassword);

// Page transition
const mainContent = document.getElementById("main-content");

document.getElementById("register-link")?.addEventListener("click", function(event) {
  event.preventDefault();
  mainContent.classList.remove("visible");
  const registerUrl = this.getAttribute('data-url-register');
  setTimeout(() => {
      window.location.href = registerUrl;
  }, 500);
});

document.getElementById("login-link")?.addEventListener("click", function(event) {
  event.preventDefault();
  mainContent.classList.remove("visible");
  const loginUrl = this.getAttribute('data-url-login');
  setTimeout(() => {
      window.location.href = loginUrl;
  }, 500);
});

// Add the visible class to trigger the transition when the page loads
window.addEventListener('load', () => {
  mainContent.classList.add('visible');
});






