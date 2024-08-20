document.addEventListener('DOMContentLoaded', function() {
  var selects = document.querySelectorAll('.form-select');
  selects.forEach(function(select) {
    select.addEventListener('change', function() {
      if (select.value) {
        select.classList.add('has-value');
      } else {
        select.classList.remove('has-value');
      }
    });
  });
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


$(document).on('click', '.toggle-currentpassword', function() {

  $(this).toggleClass("fa-eye fa-eye-slash");

  var input = $("#currentpassword");
  input.attr('type') === 'password' ? input.attr('type','text') : input.attr('type','password')
});

