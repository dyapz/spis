  <nav class="navbar navbar-expand-lg shadow">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">PSIS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="#"><i class="fa-solid fa-house fa-sm"></i> Home</a></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#"><i class="fa-solid fa-plus fa-sm"></i> New</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url().'main/search_client';?>"><i class="fa-solid fa-person-cane fa-sm"></i> Client List</a></a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Pensions
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <li><a class="dropdown-item" href="<?php echo base_url().'data/gsis';?>">GSIS</a></li>
            <li><a class="dropdown-item" href="<?php echo base_url().'data/pvao';?>">PVAO</a></li>
            <li><a class="dropdown-item" href="#">SSS</a></li>
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