<?php 
$csrf = array(
  'name' => $this->security->get_csrf_token_name(),
  'hash' => $this->security->get_csrf_hash()
);
foreach($profile as $row){ 
    $user_types = [
        1 => 'Admin',
        2 => 'Supervisor',
        3 => 'Finance',
        4 => 'Encoder'
    ];
    
    $user_type = $user_types[$row->user_type] ?? 'Unknown';
?>

<div class="container d-flex justify-content-center">
    <div class="card card-shadow mt-4" style="width: 30rem;">
        <div class="card-header">Profile</div>
        <div class="card-body">
            <form action="" method="POST" autocomplete="off" class="p-4">
                <div class="form-group ">
                    <input type="text" name="user_fname" class="form-control form-custom <?php echo set_value('user_fname') ? 'has-value' : ''; ?> " id="user_fname" value="<?php echo set_value('user_fname', $row->user_fname); ?>" placeholder="" required>
                    <label for="user_fname" class="form-label">First Name</label>
                    <?php echo form_error('user_fname','<p class="help-block text-danger">','</p>'); ?>
                </div>
                <div class="form-group">
                    <input type="text" name="user_mname" class="form-control form-custom  <?php echo set_value('user_mname') ? 'has-value' : ''; ?>" id="user_mname" value="<?php echo set_value('user_mname', $row->user_mname); ?>" placeholder="">
                    <label for="user_mname" class="form-label">Middle Name</label>
                    <?php echo form_error('user_mname','<p class="help-block text-danger">','</p>'); ?>
                </div>
                <div class="form-group">
                    <input type="text" name="user_lname" class="form-control form-custom  <?php echo set_value('user_lname') ? 'has-value' : ''; ?>" id="user_lname" value="<?php echo set_value('user_lname', $row->user_lname); ?>" placeholder="" required>
                    <label for="user_lname" class="form-label">Last Name</label>
                    <?php echo form_error('user_lname','<p class="help-block text-danger">','</p>'); ?>
                </div>
                <div class="form-group">
                    <input type="text" name="user_ename" class="form-control form-custom  <?php echo set_value('user_ename') ? 'has-value' : ''; ?>" id="user_ename" value="<?php echo set_value('user_ename', $row->user_ename); ?>" placeholder="">
                    <label for="user_ename" class="form-label">Ext. (III, Jr.)</label>
                    <?php echo form_error('user_ename','<p class="help-block text-danger">','</p>'); ?>
                </div>
                <div class="form-group">
                    <select name="user_gender" class="form-select <?php echo (!empty($row->user_gender) || set_value('user_gender')) ? 'has-value' : ''; ?>" id="user_gender" required>
                        <option value="" disabled <?php echo empty($row->user_gender) && empty(set_value('user_gender')) ? 'selected' : ''; ?>></option>
                        <option value="1" <?php echo ($row->user_gender == 1 || set_value('user_gender') == 1) ? 'selected ' : ''; ?>>Male</option>
                        <option value="2" <?php echo ($row->user_gender == 2 || set_value('user_gender') == 2) ? 'selected ' : ''; ?>>Female</option>
                        <option value="3" <?php echo ($row->user_gender == 3 || set_value('user_gender') == 3) ? 'selected ' : ''; ?>>Member of LGBTTQQIA+++</option>
                    </select>
                    <label for="user_gender" class="form-label">Gender</label>
                </div>

                <div class="form-group">
                    <input type="email" name="" class="form-control form-custom" id="user_email" value="<?php echo $row->user_email; ?>" placeholder="" readonly>
                    <label for="user_email" class="form-label">Email Address</label>
                </div>
                <div class="form-group">
                    <input type="text" name="user_designation" class="form-control form-custom <?php echo set_value('user_designation') ? 'has-value' : ''; ?>" id="user_designation" value="<?php echo set_value('user_designation', $row->user_designation); ?>" placeholder="" required>
                    <label for="user_designation" class="form-label">Designation</label>
                    <?php echo form_error('user_designation','<p class="help-block text-danger">','</p>'); ?>
                </div>
                <div class="form-group">
                    <input type="text" name="" class="form-control form-custom" id="user_type" value="<?php echo $user_type; ?>" placeholder="" readonly>
                    <label for="user_type" class="form-label">Access Level</label>
                </div>
                <div class="form-group">
                    <input type="text" name="" class="form-control form-custom" id="username" value="<?php echo $row->username; ?>" placeholder="" readonly>
                    <label for="username" class="form-label">Username</label>
                </div>
                <!-- OLD VALUE -->
                <input type="hidden" name="old_user_fname" value="<?php echo $row->user_fname; ?>" />
                <input type="hidden" name="old_user_mname" value="<?php echo $row->user_mname; ?>" />
                <input type="hidden" name="old_user_lname" value="<?php echo $row->user_lname; ?>" />
                <input type="hidden" name="old_user_ename" value="<?php echo $row->user_ename; ?>" />
                <input type="hidden" name="old_user_gender" value="<?php echo $row->user_gender; ?>" />
                <input type="hidden" name="old_user_designation" value="<?php echo $row->user_designation; ?>" />

                <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
                <input type="hidden" name="user_id" value="<?php echo $row->user_id; ?>" />
                <button type="submit" name="updateProfileSubmit" class="btn btn-custom w-100 mb-4 hvr-float-shadow" onclick="return confirm('Are you sure you want to update?')"><i class="fa-solid fa-floppy-disk"></i> Update</button>

                <a href="<?php echo base_url().'account/settings'; ?>" class="btn btn-danger w-100 hvr-float-shadow"><i class="fa-solid fa-xmark"></i> Cancel</a>
            </form>
            <?php } ?>
        </div>
    </div>
</div>

