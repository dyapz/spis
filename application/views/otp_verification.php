<?php 
$csrf = array(
  'name' => $this->security->get_csrf_token_name(),
  'hash' => $this->security->get_csrf_hash()
);

foreach($otp_verification as $row) { 
  $user_id = $row->user_id;
  $user_timestamp = $row->user_timestamp;
  $user_email = $row->user_email;
  list($local, $domain) = explode('@', $user_email);
  $first_char = $local[0];
  $last_char = $local[strlen($local) - 1];
  $masked_email = $first_char . str_repeat('*', strlen($local) - 2) . $last_char . '@' . $domain;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="icon" href="<?php echo base_url().'assets/img/socpen-icon2.png'; ?>" />
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OTP Verification</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo base_url().'assets/css/auth.css'; ?>" rel="stylesheet">
  <script src='https://code.jquery.com/jquery-3.5.1.js'></script>
</head>
<body>

<div class="card shadow" style="width: 30rem; margin-top: -500px">
  <div class="card-body">
    <div class="container">
      <form action="<?php echo base_url(); ?>auth/otp_verify" method="POST" autocomplete="off">
        <p class="text-danger text-center"><?php echo $this->session->flashdata('error');?></p>
        <h6 class="text-center">Please enter the one time password <br> to verify your account</h6>
        <div class="text-center mb-5"><span>A code has been sent to</span> <small><?php echo $masked_email; ?></small></div>
        <div class="form-group">
          <input type="password" name="user_otp_verify" class="form-control" id="user_otp" placeholder=" ">
          <label for="user_otp" class="form-label">OTP Verification Code</label>
        </div>

        <div class="row">
          <div class="col-md-2-2">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
            <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
            <button type="submit" class="btn btn-custom">Submit</button>
          </div>
      </form>
          <div class="col-md-4">
            <form action="<?php echo base_url(); ?>auth/resend_otp" method="POST" autocomplete="off">
              <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
              <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
              <button type="submit" class="btn btn-secondary" id="resend_otp" style="display:none;">Resend OTP</button>
            </form>
            <div id="countdown"></div>
          </div>
        </div>
    </div>
  </div>
</div>

<script src="https://kit.fontawesome.com/092843e6dd.js" crossorigin="anonymous"></script>
<script src="<?php echo base_url().'assets/js/auth.js'; ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src='https://www.google.com/recaptcha/api.js'></script>

<script>
  $(document).ready(function() {
    // 3 MINUTES COUNTDOWN FOR RESEND OTP
    var userTimestamp = new Date("<?php echo $user_timestamp; ?>").getTime(); 
    var countdownTime = userTimestamp + (5 * 60 * 1000); 

    function updateCountdown() {
      var now = new Date().getTime();
      var distance = countdownTime - now;

      if (distance < 0) {
        $('#resend_otp').show();
        $('#countdown').hide();
      } else {
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        $('#countdown').text(+ minutes + 'm ' + seconds + 's');
        setTimeout(updateCountdown, 1000);
      }
    }

    updateCountdown();

    //DISABLE COPY AND PASTE 
    $('#user_otp').on('copy paste', function(e) {
      e.preventDefault();
    });
  });
</script>

</body>
</html>
