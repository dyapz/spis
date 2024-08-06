<?php 
$csrf = array(
  'name' => $this->security->get_csrf_token_name(),
  'hash' => $this->security->get_csrf_hash()
);

foreach($new_password as $row) { 
  $user_id = $row->user_id;
  $user_timestamp = $row->user_timestamp;
  $password = $row->password;
}
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

<div class="card shadow" style="width: 30rem; margin-top: -200px">
    <div class="card-body">
        <div class="container">
            <form action="" method="POST" autocomplete="off" class="my-4">
                <h6 class="text-center mb-5">CHANGE PASSWORD</h6>
                <p class="text-danger"><?php echo $this->session->flashdata('error');?></p>

                <div class="form-group">
                    <input type="password" name="password" class="form-control" id="password" placeholder="" required>
                    <label for="password" class="form-label">New Password</label>
                    <span class="position-absolute end-0 translate-middle-y me-3 fa fa-fw fa-eye field_icon toggle-password" style="top: 20px"></span>
                    <?php echo form_error('password','<p class="help-block text-danger">','</p>'); ?>
                </div>
                <div class="form-group">
                    <input type="password" name="cpassword" class="form-control" id="cpassword" placeholder="" required>
                    <label for="cpassword" class="form-label">Confirm Password</label>
                    <span class="position-absolute end-0 translate-middle-y me-3 fa fa-fw fa-eye field_icon toggle-cpassword" style="top: 20px"></span>
                    <?php echo form_error('cpassword','<p class="help-block text-danger">','</p>'); ?>
                </div>
                <div class="mb-3">
                    <div class="g-recaptcha" name="gcaptcha" data-sitekey="<?php echo $this->config->item('google_key') ?>"></div>
                    <div class="text-danger text-center"><?=$this->session->flashdata('gcaptcha_error')?></div>
                </div>
                <input type="hidden" name="old_value" value="<?php echo $password; ?>" />
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-custom">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

    
<script src="https://kit.fontawesome.com/092843e6dd.js" crossorigin="anonymous"></script>
<script src="<?php echo base_url().'assets/js/auth.js'; ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>



</body>
</html>
