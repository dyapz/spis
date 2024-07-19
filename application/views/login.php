<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" href="<?php echo base_url().'assets/img/socpen-icon.png'; ?>" />
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo base_url().'assets/css/auth.css'; ?>" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body>
<div id="main-content" class="page-transition">
  <div class="card shadow">
    <div class="card-body-login">
      <h4 class="card-title mb-4">Login</h4>
      <form action="" method="POST" autocomplete="off">
        <div class="form-group">
          <input type="text" name="" class="form-control" id="username" placeholder="" required>
          <label for="username" class="form-label">Username</label>
        </div>
        <div class="form-group position-relative">
          <input type="password" name="" class="form-control" id="password" placeholder="" required>
          <label for="password" class="form-label">Password</label>
          <span class="position-absolute top-50 end-0 translate-middle-y me-3" onclick="togglePassword()">
            <i class="bi bi-eye" id="togglePasswordIcon"></i>
          </span>
        </div>
        <p class="mb-4"><a href="#" data-bs-toggle="modal" data-bs-target="#forgotPassword">forgot password</a></p>
        <div class="mb-3">
          <div class="g-recaptcha" name="gcaptcha" data-sitekey="<?php echo $this->config->item('google_key') ?>"></div>
        </div>
        <button type="submit" class="btn btn-custom w-100">Login</button>
      </form>
      <p class="mt-2">Don't have an account?  <a id="register-link" href="#" data-url-register="<?php echo base_url().'auth/register'; ?>">Register</a></p>
    </div>
    <div class="card-body-right">
        <img src="<?php echo base_url().'assets/img/02 SocPen Logo (no background).png'; ?>" alt="SOCIAL PENSION INFORMATION SYSTEM" >

    </div>
  </div>
</div>

  <!-- Forgot Password -->
  <div class="modal fade" id="forgotPassword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="forgotPasswordLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="forgotPasswordLabel">Forgot Password</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        <form action="" method="POST" autocomplete="off">
            <div class="form-group">
                <input type="email" name="" class="form-control" id="email" placeholder=" ">
                <label for="email" class="form-label">Email Address</label>
            </div>
            <div class="mb-3">
              <div class="g-recaptcha" name="gcaptcha" data-sitekey="<?php echo $this->config->item('google_key') ?>"></div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-custom">Reset</button>
        </form>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <script src="<?php echo base_url().'assets/js/auth.js'; ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src='https://www.google.com/recaptcha/api.js'></script>

</body>
</html>
