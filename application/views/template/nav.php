<?php 
  $csrf = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
  );

  $user_type_redirects = [
    '1' => 'admin',
    '2' => 'supervisor',
    '3' => 'validator',
    '4' => 'finance',
    '5' => 'encoder'
  ];

  $user_type = $this->session->userdata('user_type');

  if(count($badge_validation) > 0) {
    $badge_v = "<span class='notification-badge'>" . count($badge_validation) . "</span>";
  } else {
      $badge_v = "";
  }

  if(count($badge_approval) > 0) {
      $badge_a = "<span class='notification-badge'>" . count($badge_approval) . "</span>";
  } else {
      $badge_a = "";
  }

$count_validation = count($badge_validation);
$count_approval = count($badge_approval);

$total_badge = $count_validation + $count_approval;


if($total_badge > 0) {
  $badge = "<span class='notification-total-badge'>" . $total_badge . "</span>";
} else {
  $badge = "";
}


?>

  

<style>
  .active{
    color: #a2f5ee;
  }

  .notification-badge {
    background-color: red;
    color: white;
    border-radius: 50%;
    padding: 0px 3px;
    font-size: 12px;
    font-weight: bold;
    position: absolute;
    /* top: 5px; */
    right: 25px;
}

.notification-total-badge{

  background-color: red;
    color: white;
    border-radius: 50%;
    padding: 0px 2px;
    font-size: 12px;
    font-weight: bold;
    position: absolute;
    top: -3px;
    right: 17px;  
}
</style>

<nav class="navbar navbar-expand-lg shadow">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo base_url().'auth'; ?>">PSIS </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
        <li class="nav-item">
        <a class="nav-link <?php echo ($this->uri->uri_string() == $user_type_redirects[$user_type] . '/dashboard') ? 'active' : ''; ?>"  href="<?php echo base_url() . $user_type_redirects[$user_type] . '/dashboard'; ?>"><i class="fa-solid fa-house fa-sm"></i> Home</a>

        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle  <?php if($this->uri->uri_string() == $user_type_redirects[$user_type] . '/client/list' || (strpos($this->uri->uri_string(), $user_type_redirects[$user_type] . '/client/information/') !== false)) { echo 'active'; }?>" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-person-cane fa-sm"></i>
            Client <?php echo $badge; ?>
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item" href="<?php echo base_url(). $user_type_redirects[$user_type] . '/client/validation';?>">For Validation <?php echo $badge_v; ?></a></li>
            <li><a class="dropdown-item" href="<?php echo base_url(). $user_type_redirects[$user_type] . '/client/approval';?>">For Approval  <?php echo $badge_a; ?></a></li>
            <li><a class="dropdown-item" href="<?php echo base_url(). $user_type_redirects[$user_type] . '/client/list';?>">List</a></li>
          </ul>
        </li>
      </ul>
      <div class="ms-auto">
        <ul class="navbar-nav">
          <li class="nav-item dropdown ">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <?php echo $this->session->user_fname.' '.substr($this->session->user_mname, 0, 1).' '.$this->session->user_lname; ?> 
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown" style="right: 0; left: auto;">
              <li><a class="dropdown-item" href="<?php echo base_url().'account/settings'; ?>"><i class="fa-solid fa-gear fa-sm fa-fw"></i> Settings</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="<?php echo base_url().'auth/logout'; ?>"><i class="fas fa-sign-out-alt fa-sm fa-fw"></i> Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>


<div style="margin-top:30px"></div>

<!-- Flasdata success  -->			
<?php if($this->session->flashdata('success')){ ?>
		<div class="alert alert-success"> 
			<?php  echo $this->session->flashdata('success'); ?>
		</div>

<!-- Flasdata error  -->			
	<?php } else if($this->session->flashdata('error')){ ?>
		<div class = "alert alert-danger">
			<?php echo $this->session->flashdata('error'); ?>
		</div>

	<?php } 
	error_reporting(0);
	?> 	