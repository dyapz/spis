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






