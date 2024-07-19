<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" href="<?php echo base_url().'assets/img/socpen-icon.png'; ?>"  />
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo base_url().'assets/css/auth.css'; ?>" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body>

<div id="main-content" class="page-transition">
  <div class="card shadow">
    <div class="card-body-registration">
      <h4 class="card-title mb-4">Register</h4>
      <form action="" method="POST" autocomplete="off">
        <div class="form-group">
          <input type="text" name="fname" class="form-control" id="fname" placeholder="" required>
          <label for="fname" class="form-label">First Name</label>
        </div>
        <div class="form-group">
          <input type="text" name="mname" class="form-control" id="mname" placeholder="">
          <label for="mname" class="form-label">Middle Name</label>
        </div>
        <div class="form-group">
          <input type="text" name="lname" class="form-control" id="lname" placeholder="" required>
          <label for="lname" class="form-label">Last Name</label>
        </div>
        <div class="form-group">
          <input type="text" name="extname" class="form-control" id="extname" placeholder="">
          <label for="extname" class="form-label">Ext Name</label>
        </div>
        <div class="form-group">
          <input type="text" name="idnumber" class="form-control" id="idnumber" placeholder="" required>
          <label for="idnumber" class="form-label">ID Number</label>
        </div>
        <div class="form-group">
          <input type="email" name="email" class="form-control" id="email" placeholder="" required>
          <label for="email" class="form-label">Email Address</label>
        </div>
        <div class="form-group">
          <select name="" class="form-select" id="region_id" required>
            <option value="" disabled selected></option>
            <option value="1">NCR</option>
            <option value="2">CAR</option>
          </select>
          <label for="region_id" class="form-label">Region</label>
        </div>
        <div class="form-group">
          <input type="text" name="username" class="form-control" id="username" placeholder="" required>
          <label for="username" class="form-label">Username</label>
        </div>
        <div class="form-group">
          <input type="password" name="password" class="form-control" id="password" placeholder="" required>
          <label for="password" class="form-label">Password</label>
          <span class="position-absolute top-50 end-0 translate-middle-y me-3" onclick="togglePassword()">
            <i class="bi bi-eye" id="togglePasswordIcon"></i>
        </div>
        <div class="form-group">
          <input type="password" name="cpassword" class="form-control" id="cpassword" placeholder="" required>
          <label for="cpassword" class="form-label">Confirm Password</label>
          <span class="position-absolute top-50 end-0 translate-middle-y me-3" onclick="toggleCPassword()">
            <i class="bi bi-eye" id="toggleCPasswordIcon"></i>
        </div>
        <div class="mb-3">
          <div class="g-recaptcha" name="gcaptcha" data-sitekey="<?php echo $this->config->item('google_key') ?>"></div> 
        </div>
        <button type="submit" class="btn btn-custom w-100">Register</button>
      </form>
      <p class="mt-2">Already have an account?<a id="login-link" href="#" data-url-login="<?php echo base_url().'auth'; ?>">Login</a></p>
    </div>
    <div class="card-body-right">
      <img src="<?php echo base_url().'assets/img/02 SocPen Logo (no background).png'; ?>" alt="SOCIAL PENSION INFORMATION SYSTEM" >
    </div>
  </div>
</div>

<script>
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
</script>
  <script src="<?php echo base_url().'assets/js/auth.js'; ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src='https://www.google.com/recaptcha/api.js'></script>
</body>
</html>

