<?php 
  $csrf = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
  );
?>
  
<nav class="navbar navbar-expand-lg shadow">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo base_url().'auth'; ?>">PSIS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url().'auth'; ?>"><i class="fa-solid fa-house fa-sm"></i> Home</a></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fa-solid fa-plus fa-sm"></i> New</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo base_url().'client/list';?>"><i class="fa-solid fa-person-cane fa-sm"></i> Client List</a></a>
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


<!-- Confirmation Modal -->
<div id="confirmationModal">
    <div class="confirmationModal">
        <p id="confirmationMessage"></p>
        <button id="confirmYes" class="btn btn-primary">Yes</button>
        <button id="confirmNo" class="btn btn-secondary">No</button>
    </div>
</div>

<!-- Loading Spinner -->
<div id="loadingSpinner">
    <div class="loadingSpinner">
        <div class="spinner"></div>
        <p>Checking, please wait...</p>
    </div>
</div>

<!-- Add New Modal-->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add New Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="saveForm" action="<?php echo base_url().'client/list'; ?>" method="POST">

                <div class="form-group">
                      <input type="text" name="client_lname" class="form-control form-custom" id="client_lname" placeholder="" required>
                      <label for="client_lname" class="form-label">Last Name</label>
                    </div>

                    <div class="form-group">
                      <input type="text" name="client_fname" class="form-control form-custom" id="client_fname" placeholder="" required>
                      <label for="client_fname" class="form-label">First Name</label>
                    </div>

                    <div class="form-group">
                      <input type="text" name="client_mname" class="form-control form-custom" id="client_mname" placeholder="">
                      <label for="client_mname" class="form-label">Middle Name</label>
                    </div>
<!-- 
                    <div class="form-group">
                      <input type="text" name="client_mname" class="form-control form-custom" id="client_mname" placeholder="">
                      <label for="client_mname" class="form-label">Ext Name</label>
                    </div> -->

                    <div class="form-group">
                      <input type="date" name="birthdate" class="form-control form-custom" id="birthdate" placeholder="" required>
                      <label for="birthdate" class="form-label">Birthdate</label>
                    </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
                <input type="submit" value="Search" class="btn btn-custom">
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




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