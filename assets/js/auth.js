document.addEventListener('DOMContentLoaded', function() {
  var selects = document.querySelectorAll('.form-select');
  selects.forEach(function(select) {
    if (select.value) {
      select.classList.add('has-value');
    }

    select.addEventListener('change', function() {
      if (select.value) {
        select.classList.add('has-value');
      } else {
        select.classList.remove('has-value');
      }
    });
  });
});



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



// View Password
$(document).on('click', '.toggle-password', function() {

  $(this).toggleClass("fa-eye fa-eye-slash");

  var input = $("#password");
  input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
});

$(document).on('click', '.toggle-cpassword', function() {

  $(this).toggleClass("fa-eye fa-eye-slash");

  var input = $("#cpassword");
  input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
});