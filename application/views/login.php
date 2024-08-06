<?php 
	$csrf = array(
		'name' => $this->security->get_csrf_token_name(),
		'hash' => $this->security->get_csrf_hash()
  );

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" href="<?php echo base_url().'assets/img/socpen-icon2.png'; ?>" />
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo base_url().'assets/css/auth.css'; ?>" rel="stylesheet">
    <script src='https://code.jquery.com/jquery-3.5.1.js'></script>
</head>
<body>



<div id="main-content" class="page-transition">
 
  <div class="card shadow">
    <div class="card-body-login">
      <div class="mb-2">
        <!-- Flashdata Success-->
        <p class="text-success"><?php echo $this->session->flashdata('success');?></p>
        <!-- Flashdata Error-->
        <p class="text-danger"><?php echo $this->session->flashdata('error');?></p>
        <div class="text-danger text-center"><?=$this->session->flashdata('gcaptcha_error')?></div>
      </div>


      <h4 class="card-title mb-4">Login</h4>
      <form action="<?php echo base_url(); ?>auth/authenticate" method="POST" autocomplete="off">
        <div class="form-group">
          <input type="text" name="username" class="form-control" id="username" placeholder="" required>
          <label for="username" class="form-label">Username</label>
        </div>
        <div class="form-group position-relative">
          <input type="password" name="password" class="form-control" id="password" placeholder="" required>
          <label for="password" class="form-label">Password</label>
          <span class="position-absolute top-50 end-0 translate-middle-y me-3 fa fa-fw fa-eye field_icon toggle-password">
          </span>
        </div>
        <p class="mb-4"><a href="#" data-bs-toggle="modal" data-bs-target="#forgotPassword">forgot password</a></p>
        <div class="mb-3">
          <div class="g-recaptcha" name="gcaptcha" data-sitekey="<?php echo $this->config->item('google_key') ?>"></div>
        </div>
				<input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
        <button type="submit" class="btn btn-custom w-100">Login</button>
      </form>
      <p class="mt-2">Don't have an account?  <a id="register-link" href="#" data-url-register="<?php echo base_url().'auth/register'; ?>">Register</a></p>

    </div>
    <div class="card-body-right">
        <img src="<?php echo base_url().'assets/img/socpen-logo2.png'; ?>" alt="SOCIAL PENSION INFORMATION SYSTEM" >
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
          <form action="<?php echo base_url().'auth/forgotpassword';?>" method="POST" autocomplete="off">
              <div class="form-group">
                  <input type="email" name="user_email" class="form-control" id="user_email" placeholder=" ">
                  <label for="user_email" class="form-label">Email Address</label>
              </div>
              <div class="mb-3">
                <div class="g-recaptcha" name="gcaptcha" data-sitekey="<?php echo $this->config->item('google_key') ?>"></div>
              </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
            <button type="submit" class="btn btn-custom">Reset</button>
          </form>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>


<script src="https://kit.fontawesome.com/092843e6dd.js" crossorigin="anonymous"></script>
<script src="<?php echo base_url().'assets/js/auth.js'; ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>

</body>
</html>
