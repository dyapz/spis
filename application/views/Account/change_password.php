<?php 
$csrf = array(
  'name' => $this->security->get_csrf_token_name(),
  'hash' => $this->security->get_csrf_hash()
);
foreach($change_password as $row){ 

?>

<div class="container d-flex justify-content-center">
    <div class="card card-shadow mt-4" style="width: 30rem;">
        <div class="card-header">Change Password</div>
        <div class="card-body">
            <form action="" method="POST" autocomplete="off" class="p-4">
                <div class="form-group">
                    <input type="password" name="currentpassword" class="form-control form-custom" id="currentpassword" placeholder="" required>
                    <label for="currentpassword" class="form-label">Current Password</label>
                    <span class="position-absolute end-0 translate-middle-y me-3 fa fa-fw fa-eye field_icon toggle-currentpassword" style="top: 20px"></span>
                    <?php echo form_error('currentpassword','<p class="help-block text-danger">','</p>'); ?>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control form-custom" id="password" placeholder="" required>
                    <label for="password" class="form-label">New Password</label>
                    <span class="position-absolute end-0 translate-middle-y me-3 fa fa-fw fa-eye field_icon toggle-password" style="top: 20px"></span>
                    <?php echo form_error('password','<p class="help-block text-danger">','</p>'); ?>
                </div>
                    <div class="form-group">
                    <input type="password" name="cpassword" class="form-control form-custom" id="cpassword" placeholder="" required>
                    <label for="cpassword" class="form-label">Confirm Password</label>
                    <span class="position-absolute end-0 translate-middle-y me-3 fa fa-fw fa-eye field_icon toggle-cpassword" style="top: 20px"></span>
                    <?php echo form_error('cpassword','<p class="help-block text-danger">','</p>'); ?>
                </div>

                <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
                <input type="hidden" name="user_id" value="<?php echo $row->user_id; ?>" />

                <button type="submit" name="updatePasswordSubmit" class="btn btn-custom w-100 mb-4 hvr-float-shadow" onclick="return confirm('Are you sure you want to update?')"><i class="fa-solid fa-floppy-disk"></i> Update</button>
                <a href="<?php echo base_url().'account/settings'; ?>" class="btn btn-danger w-100 hvr-float-shadow"><i class="fa-solid fa-xmark"></i> Cancel</a>
            </form>
            <?php } ?>
        </div>
    </div>
</div>

