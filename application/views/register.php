<?php 
	$csrf = array(
		'name' => $this->security->get_csrf_token_name(),
		'hash' => $this->security->get_csrf_hash()
  );

	error_reporting(0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" href="<?php echo base_url().'assets/img/socpen-icon2.png'; ?>"  />
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo base_url().'assets/css/auth.css'; ?>" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

<div id="main-content" class="page-transition">
  <div class="card card-shadow">
    <div class="card-body-registration">
      <h4 class="card-title mb-4">Register</h4>
      <form action="" method="POST" autocomplete="off">
        <div class="form-group">
          <select name="region_id" class="form-select" id="region_id" required>
            <option value="" disabled selected></option>
              <?php foreach($region as $row){ ?>
                <option value="<?php echo $row->region_id; ?>"><?php echo $row->region_name; ?></option>
              <?php } ?>
          </select>
          <label for="region_id" class="form-label">DSWD Field Office Region</label>
        </div>

        <div class="form-group">
          <select name="province_id" class="form-select <?php echo set_value('province_id') !== '' ? 'has-value' : ''; ?>" id="province_id">
            <option value="" disabled selected>Select Region First</option>
          </select>
          <label for="province_id" class="form-label">DSWD Field Office Province</label>
        </div>
        <div class="form-group">
          <input type="text" name="user_fname" class="form-control" id="user_fname" value="<?php echo !empty($user['user_fname'])?$user['user_fname']:''; ?>" placeholder="" required>
          <label for="user_fname" class="form-label">First Name</label>
          <?php echo form_error('user_fname','<p class="help-block text-danger">','</p>'); ?>
        </div>
        <div class="form-group">
          <input type="text" name="user_mname" class="form-control" id="user_mname" value="<?php echo !empty($user['user_mname'])?$user['user_mname']:''; ?>" placeholder="">
          <label for="user_mname" class="form-label">Middle Name</label>
          <?php echo form_error('user_mname','<p class="help-block text-danger">','</p>'); ?>
        </div>
        <div class="form-group">
          <input type="text" name="user_lname" class="form-control" id="user_lname" value="<?php echo !empty($user['user_lname'])?$user['user_lname']:''; ?>" placeholder="" required>
          <label for="user_lname" class="form-label">Last Name</label>
          <?php echo form_error('user_lname','<p class="help-block text-danger">','</p>'); ?>
        </div>
        <div class="form-group">
          <input type="text" name="user_ename" class="form-control" id="user_ename" value="<?php echo !empty($user['user_ename'])?$user['user_ename']:''; ?>" placeholder="">
          <label for="user_ename" class="form-label">Ext. (III, Jr.)</label>
          <?php echo form_error('user_ename','<p class="help-block text-danger">','</p>'); ?>
        </div>
        <div class="form-group">
            <select name="user_gender" class="form-select <?php echo set_value('user_gender') ? 'has-value' : ''; ?>" id="user_gender" required>
                <option value="" disabled <?php echo empty($user['user_gender']) ? 'selected' : ''; ?>></option>
                <option value="1" <?php echo set_select('user_gender', '1'); ?>>Male</option>
                <option value="2" <?php echo set_select('user_gender', '2'); ?>>Female</option>
                <option value="3" <?php echo set_select('user_gender', '3'); ?>>Member of LGBTTQQIA+++</option>
            </select>
            <label for="user_gender" class="form-label">Gender</label>
        </div>

        <div class="form-group">
          <input type="email" name="user_email" class="form-control" id="user_email" value="<?php echo !empty($user['user_email'])?$user['user_email']:''; ?>" placeholder="" required>
          <label for="user_email" class="form-label">Email Address</label>
          <?php echo form_error('user_email','<p class="help-block text-danger">','</p>'); ?>
        </div>
        <div class="form-group">
          <input type="text" name="user_designation" class="form-control" id="user_designation" value="<?php echo !empty($user['user_designation'])?$user['user_designation']:''; ?>" placeholder="" required>
          <label for="user_designation" class="form-label">Designation</label>
          <?php echo form_error('user_designation','<p class="help-block text-danger">','</p>'); ?>
        </div>
        <div class="form-group">
            <select name="user_type" class="form-select <?php echo set_value('user_type') ? 'has-value' : ''; ?>" id="user_type" required>
                <option value="" disabled <?php echo empty($user['user_type']) ? 'selected' : ''; ?>></option>
                <option value="2" <?php echo set_select('user_type', '2'); ?>>Supervisor</option>
                <option value="3" <?php echo set_select('user_type', '3'); ?>>Finance</option>
                <option value="4" <?php echo set_select('user_type', '4'); ?>>Encoder</option>
            </select>
            <label for="user_type" class="form-label">Access Level</label>
        </div>
        <div class="form-group">
          <input type="text" name="username" class="form-control" id="username" value="<?php echo !empty($user['username'])?$user['username']:''; ?>" placeholder="" required>
          <label for="username" class="form-label">Username</label>
          <?php echo form_error('username','<p class="help-block text-danger">','</p>'); ?>
        </div>
        <div class="form-group">
          <input type="password" name="password" class="form-control" id="password" placeholder="" required>
          <label for="password" class="form-label">Password</label>
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
				<input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
        <input type="submit" name="registerSubmit" value="Register" class="btn btn-custom w-100">
      </form>
      <p class="mt-2">Already have an account?<a id="login-link" href="#" data-url-login="<?php echo base_url().'auth'; ?>">Login</a></p>
    </div>
    <div class="card-body-right">
      <img src="<?php echo base_url().'assets/img/socpen-logo2.png'; ?>" alt="SOCIAL PENSION INFORMATION SYSTEM" >
    </div>
  </div>
</div>

<script src="https://kit.fontawesome.com/092843e6dd.js" crossorigin="anonymous"></script>
  <script src="<?php echo base_url().'assets/js/auth.js'; ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src='https://www.google.com/recaptcha/api.js'></script>
</body>
</html>


<script>
$(document).ready(function() {
  // Handle region_id change
  $('#region_id').on('change', function() {
    var region_id = $(this).val();
    if (region_id !== '') {
      $.ajax({
        url: "<?php echo base_url(); ?>address/province",
        method: "POST",
        data: { 
          region_id: region_id,
          '<?php echo $csrf['name']; ?>': '<?php echo $csrf['hash']; ?>' 
        },
        success: function(data) {
          $('#province_id').html(data);
          // Check if the province_id has a value and apply has-value class
          if ($('#province_id').val() !== '') {
            $('#province_id').addClass('has-value');
          } else {
            $('#province_id').removeClass('has-value');
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error: " + status + ": " + error);
          console.log("Response: ", xhr.responseText); 
        }
      });
    } else {
      $('#province_id').html('<option value="" disabled selected>Select Region First</option>');
      $('#province_id').removeClass('has-value');
    }
  });

  // Check if province_id has a value on page load
  if ($('#province_id').val() !== '') {
    $('#province_id').addClass('has-value');
  }
});



</script>
