<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url().'assets/css/auth.css'; ?>" rel="stylesheet">
</head>
<body>




<div class="card shadow" style="width: 50rem; margin-top: -500px">

<div class="card-body">
<div>
  <p class="text-danger"><?php echo $this->session->flashdata('error');?></p>
</div>
    <h5 class="card-title mb-4">We're reviewing your account. </h5>
    <p class="card-text">For security purposes, your account is pending and under review by our staff before activation. <br>
    This usually takes less than an hour. We'll update you soon! For more information, please email us at _________. </p>
    <a href="<?php echo base_url().'auth/logout';?>" class="btn btn-danger mt-4"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
  </div>
</div>

<script src="https://kit.fontawesome.com/092843e6dd.js" crossorigin="anonymous"></script>
</body>
</html>