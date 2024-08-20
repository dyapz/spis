<?php 
    $csrf = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
    );
    
    $hashed_id = md5($this->session->userdata('user_id'));
?>

<div class="container d-flex justify-content-center">
    <div class="card card-shadow mt-4" style="width: 30rem;">
        <div class="card-header">Settings</div>
        <div class="card-body">
            <table class="table">
                <tbody>
                    <tr>
                        <td onclick="window.location='<?php echo base_url().'account/profile/'.$hashed_id; ?>'" class="clickable-row"><i class="fa-solid fa-circle-user"></i> Update Personal and Account Information</td>
                    </tr>
                    <tr>
                        <td onclick="window.location='<?php echo base_url().'account/change_password/'.$hashed_id; ?>';" class="clickable-row"><i class="fa-solid fa-key"></i> Change Password</td>
                    </tr>
                    <tr>
                        <td onclick="window.location='<?php echo base_url().'account/user_list'; ?>';" class="clickable-row"><i class="fa-solid fa-users-gear"></i> User List</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

