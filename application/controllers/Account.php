<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */

	 private $user_type;
 
	 public function __construct() {
		 parent::__construct();
 
		 $this->user_type = $this->session->userdata('user_type');
	 }

	 
	// SETTINGS
	public function settings(){
		if(!$this->session->userdata('user_type')){ 
			$this->session->set_flashdata("error", "Sorry you don't have permission to access the page you were trying to reach!");
			redirect('auth');
		}else if($this->user_type && $this->session->userdata('user_active') == 0){
			$this->session->set_flashdata("error", "Sorry you don't have permission to access the page you were trying to reach!");
			redirect('auth');
		}else{
			$this->load->view('template/header');
			$this->load->view('template/nav');
			if($this->session->userdata('user_type') != 1){
				$this->load->view('account/settings');
			}else{
				$this->load->view('account/admin_settings');
			}
			$this->load->view('template/footer');
		}
	}

	// UPDATE PROFILE
	public function profile($user_id = null) {
		if(!$this->session->userdata('user_type')){ 
			$this->session->set_flashdata("error", "Sorry you don't have permission to access the page you were trying to reach!");
			redirect('auth');
		}else if($this->user_type && $this->session->userdata('user_active') == 0){
			$this->session->set_flashdata("error", "Sorry you don't have permission to access the page you were trying to reach!");
			redirect('auth');
		}else{

			$data = $userData = array();
		
			if ($this->input->post('updateProfileSubmit')) {
				$this->form_validation->set_rules('user_fname', 'First Name', 'required|callback_text_only');
				$this->form_validation->set_rules('user_mname', 'Middle Name', 'callback_text_only');
				$this->form_validation->set_rules('user_lname', 'Last Name', 'required|callback_text_only');
				$this->form_validation->set_rules('user_ename', 'Suffix Name', 'callback_text_only');
				$this->form_validation->set_rules('user_gender', 'Sex', 'required');
				$this->form_validation->set_rules('user_designation', 'Designation', 'required|callback_text_only');
		
				$userData = array(
					'user_id' => strip_tags($this->input->post('user_id')),
					'user_fname' => strip_tags($this->input->post('user_fname')),
					'user_mname' => strip_tags($this->input->post('user_mname')),
					'user_lname' => strip_tags($this->input->post('user_lname')),
					'user_ename' => strip_tags($this->input->post('user_ename')),
					'user_gender' => strip_tags($this->input->post('user_gender')),
					'user_designation' => strip_tags($this->input->post('user_designation'))
				);
		
				$dataxss = $this->security->xss_clean($userData);
		
				if ($this->form_validation->run() == true) {
					$insert = $this->Accountmodel->update_profile_model($dataxss);
					if ($insert) {
						$getip = json_decode(file_get_contents("http://ipinfo.io/"));
						$ip_address = $getip->ip;
		
						// Audit trails
						$this->profile_log_audit_trail($userData['user_id'], 'user_fname', $this->input->post('old_user_fname'), $userData['user_fname'], $ip_address);
						$this->profile_log_audit_trail($userData['user_id'], 'user_mname', $this->input->post('old_user_mname'), $userData['user_mname'], $ip_address);
						$this->profile_log_audit_trail($userData['user_id'], 'user_lname', $this->input->post('old_user_lname'), $userData['user_lname'], $ip_address);
						$this->profile_log_audit_trail($userData['user_id'], 'user_ename', $this->input->post('old_user_ename'), $userData['user_ename'], $ip_address);
						$this->profile_log_audit_trail($userData['user_id'], 'user_gender', $this->input->post('old_user_gender'), $userData['user_gender'], $ip_address);
						$this->profile_log_audit_trail($userData['user_id'], 'user_designation', $this->input->post('old_user_designation'), $userData['user_designation'], $ip_address);
		
						$this->session->set_flashdata('success', 'Profile was updated successfully!');
					} else {
						$data['error_msg'] = 'Some problems occurred, please try again.';
					}
				} 
			}
		
			$data['user'] = $userData;
		
			if ($user_id !== null) {
				$data['profile'] = $this->Accountmodel->user_data_model($user_id);
				$this->load->view('template/header');
				$this->load->view('template/nav');
				$this->load->view('account/profile', $data);
				$this->load->view('template/footer');
			} else {
				show_404();
			}
		}
	}
	

	// PROFILE AUDIT TRAIL
	private function profile_log_audit_trail($user_id, $field, $old_value, $new_value, $ip_address) {
		if ($old_value != $new_value) {
			$data = array(
				'user_id' => strip_tags($user_id),
				'action' => 'Update Profile',
				'controller' => 'account/profile',
				'key_value' => 'U',
				'field' => $field,
				'old_value' => strip_tags($old_value),
				'new_value' => strip_tags($new_value),
				'ip_address' => $ip_address
			);
	
			$dataauditxss = $this->security->xss_clean($data);
			$this->Auditmodel->insert_audit($dataauditxss);
		}
	}
	

	// TEXT ONLY VALIDATION
	public function text_only($str) {
		if (empty($str)) {
			return TRUE;
		}
	
		if (preg_match('/^[a-zA-Z ]+$/', $str)) {
			return TRUE;
		}
	
		$this->form_validation->set_message('text_only', 'No special characters and numbers allowed');
		return FALSE;
	}
	

	//TEXT AND NUMBER ONLY VALIDATION
	public function textnumber_only($str){
		if(preg_match('/^[a-zA-Z0-9 ]+$/', $str)){
			return TRUE;
		}
		$this->form_validation->set_message('textnumber_only', 'No special characters allowed'); 
			return FALSE;
	}


	// CHANGE PASSWORD
	public function change_password($user_id = null) {

		if(!$this->session->userdata('user_type')){ 
			$this->session->set_flashdata("error", "Sorry you don't have permission to access the page you were trying to reach!");
			redirect('auth');
		}else if($this->user_type && $this->session->userdata('user_active') == 0){
			$this->session->set_flashdata("error", "Sorry you don't have permission to access the page you were trying to reach!");
			redirect('auth');
		}else{

			$data = $userData = array();
		
			if ($this->input->post('updatePasswordSubmit')) {
				$this->form_validation->set_rules('currentpassword', 'Current Password', 'required|callback_checkCurrentPassword');
				$this->form_validation->set_rules('password', 'New Password', 'required|min_length[6]|max_length[25]|callback_password_strong');
				$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|matches[password]');
		
				$userData = array(
					'user_id' => strip_tags($this->input->post('user_id')),
					'password' => md5($this->input->post('password'))
				);
		
				$dataxss = $this->security->xss_clean($userData);
		
				if ($this->form_validation->run() == true) {
					$insert = $this->Accountmodel->update_profile_model($dataxss);
					if ($insert) {
						$getip = json_decode(file_get_contents("http://ipinfo.io/"));

						$id = $this->input->post('user_id');

						$dataaudit = array(
							'user_id' => strip_tags($this->input->post('user_id')),
							'action' => 'Registration',
							'controller' => 'account/change_password',
							'key_value' => 'U',
							'field' => 'password',
							'old_value' =>  md5(strip_tags($this->input->post('currentpassword'))),
							'new_value' => md5(strip_tags($this->input->post('password'))),
							'ip_address' => $getip->ip
						);

						$dataauditxss = $this->security->xss_clean($dataaudit);
						$this->Auditmodel->insert_audit($dataauditxss);
						$this->session->set_flashdata('success', 'Password changed successfully!');
					} else {
						$data['error_msg'] = 'Some problems occurred, please try again.';
					}
				} 
			}
		
			$data['user'] = $userData;
		
			if ($user_id !== null) {
				$data['change_password'] = $this->Accountmodel->user_data_model($user_id);
				$this->load->view('template/header');
				$this->load->view('template/nav');
				$this->load->view('account/change_password', $data);
				$this->load->view('template/footer');
			} else {
				show_404();
			}
		}
	}


	// CURRENT PASSWORD VALIDATION
	public function checkCurrentPassword($str) {
		$current_password = md5($str);

		$data_password = array(
			'returnType' => 'count',
			'conditions' => array(
				'password' => $current_password
			)
		);
		$checkCurrentPassword = $this->Accountmodel->getPassword($data_password);
		if ($checkCurrentPassword > 0) {
			return TRUE;
		} else {
			$this->form_validation->set_message('checkCurrentPassword', 'The current password does not match.');
			return FALSE;
		}
	}


	//PASSWORD STRONG CHECKER VALIDATION
	public function password_strong($str){
		if(preg_match('#[0-9]#', $str) && preg_match('#[A-Z]#', $str) && preg_match('#[/\W/]#', $str) && preg_match('#[a-z]#', $str)) {
			return TRUE;
		}
		$this->form_validation->set_message('password_strong', 'Your password must: <br>	  
		1. Be eight (8) - twenty five (25) characters <br>
		2. Have at least one (1) upper case letter <br>
		3. Have at least one (1) lower case letter <br>
		4. Have at least one (1) number <br>
		5. Have at least one (1) special character 
		'); 
		return FALSE;
	}


	// USER LIST
    public function user_list(){
		if(!$this->session->userdata('user_type')){ 
			$this->session->set_flashdata("error", "Sorry you don't have permission to access the page you were trying to reach!");
			redirect('auth');
		}else if($this->user_type && $this->session->userdata('user_active') == 0){
			$this->session->set_flashdata("error", "Sorry you don't have permission to access the page you were trying to reach!");
			redirect('auth');
		}else{

			// $data['new_registered_notif'] = $this->Accountmodel->new_registered_notif_model();
			$this->load->view('template/header');
			$this->load->view('template/nav');
			$this->load->view('account/user_list');
			$this->load->view('template/footer');
		}
	}


	// USER LIST DATA
    public function user_list_data(){
		$posts = $this->Accountmodel->get_user_list_data(); 
	
		$data = array();
		$no = $this->input->post('start');
		foreach ($posts as $post){
			$user_gender = ($post->user_gender == 1) ? 'Male' :
						   (($post->user_gender == 2) ? 'Female' : 'FMember of LGBTTQQIA+++');
	
			$user_active = ($post->user_active == 1) ? 'Active' : 'Deactivated';

	
			$icon = ($post->user_active == 1) ?
					'<a href="deactivate/'.$post->user_id.'" onclick="return confirm(\'Are you sure you want to deactivate?\')"><i class="fa-solid fa-circle-xmark text-danger"></i></a>' :
					'<a href="activate/'.$post->user_id.'" onclick="return confirm(\'Are you sure you want to activate?\')"><i class="fa-solid fa-circle-check text-success"></i></a>';
	
			$user_type = ($post->user_type == 1) ? 'Admin' :
						 (($post->user_type == 2) ? 'Supervisor' :
						  (($post->user_type == 3) ? 'Finance' : 'Encoder'));
	
			$no++;
			$row = array();
			$row[] = $icon;
			$row[] = $post->region_name;
			$row[] = $post->province_name;
			$row[] = $post->user_fname.' '.$post->user_mname.' '.$post->user_lname.' '.$post->user_ename;
			$row[] = $user_gender;
			$row[] = $post->user_email;
			$row[] = $post->user_designation;
			$row[] = '<div class="text-center">'.$post->username.'</div>';
			$row[] = '<div class="text-center user-type">'.$user_type.'</div>';
			$row[] = '<div class="text-center">'.date('d F Y h:i A',strtotime($post->user_timestamp)).'</div>';
			$row[] = '<div class="text-center"><p class="'.$user_active.'">'.$user_active.'</p></div>';
			$row[] = '<div class="text-center"><a href="#" class="edit-user" data-userid="'.$post->user_id.'" data-type="'.$post->user_type.'"><i class="fa-solid fa-pen-to-square"></i></a> | <a href="delete/'.$post->user_id.'" onclick="return confirm(\'Are you sure you want to delete?\')"><i class="fa-solid fa-trash-can text-danger"></i></a></div>';

			$data[] = $row;
		}
	
		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->Accountmodel->count_all_user_list_data(),
			"data" => $data,
		);
		echo json_encode($output);
	}
	

	// USER DEACTIVATE
	public function deactivate($user_id){
        if($this->Accountmodel->deactivate_model($user_id)){

			$getip = json_decode(file_get_contents("http://ipinfo.io/"));

			$dataaudit = array(
				'user_id' => $this->session->userdata('user_id'),
				'action' => 'Deactivate user id '.$user_id,
				'controller' => 'account/deactivate',
				'key_value' => 'U',
				'field' => 'user_active',
				'old_value' =>  1,
				'new_value' => 0,
				'ip_address' => $getip->ip
			);

			$dataauditxss = $this->security->xss_clean($dataaudit);
			$this->Auditmodel->insert_audit($dataauditxss);

            $this->session->set_flashdata('success', 'The account has been deactivated successfully!');
            Redirect('account/user_list', true);

        }else{
            $this->session->set_flashdata('error', "Error!");
            Redirect('account/user_list', false);

        }
    }


	// USER ACTIVATE
	public function activate($user_id){
        if($this->Accountmodel->activate_model($user_id)){

			$getip = json_decode(file_get_contents("http://ipinfo.io/"));

			$dataaudit = array(
				'user_id' => $this->session->userdata('user_id'),
				'action' => 'Activate user id '.$user_id,
				'controller' => 'account/activate',
				'key_value' => 'U',
				'field' => 'user_active',
				'old_value' =>  0,
				'new_value' => 1,
				'ip_address' => $getip->ip
			);

			$dataauditxss = $this->security->xss_clean($dataaudit);
			$this->Auditmodel->insert_audit($dataauditxss);

            $this->session->set_flashdata('success', 'The account has been activated successfully!');
            Redirect('account/user_list', true);

        }else{
            $this->session->set_flashdata('error', "Error!");
            Redirect('account/user_list', false);

        }
    }

	// USER DELETE
	public function delete($user_id){
        if($this->Accountmodel->delete_model($user_id)){

			$getip = json_decode(file_get_contents("http://ipinfo.io/"));

			$dataaudit = array(
				'user_id' => $this->session->userdata('user_id'),
				'action' => 'Delete user id '.$user_id,
				'controller' => 'account/delete',
				'key_value' => 'D',
				'ip_address' => $getip->ip
			);

			$dataauditxss = $this->security->xss_clean($dataaudit);
			$this->Auditmodel->insert_audit($dataauditxss);

            $this->session->set_flashdata('success', 'The account has been deleted successfully!');
            Redirect('account/user_list', true);

        }else{
            $this->session->set_flashdata('error', "Error!");
            Redirect('account/user_list', false);

        }
    }

	public function update_user(){
		$data = array(
			'user_id' => strip_tags($this->input->post('user_id')),
			'user_type' => strip_tags($this->input->post('user_type'))
		);

		$dataxss = $this->security->xss_clean($data);
		$insert = $this->Accountmodel->update_profile_model($dataxss);

		if ($insert) {
			$getip = json_decode(file_get_contents("http://ipinfo.io/"));

			$dataaudit = array(
				'user_id' => strip_tags($this->input->post('user_id')),
				'action' => 'Update User Type/Access Level',
				'controller' => 'account/update_user',
				'key_value' => 'U',
				'field' => 'user_type',
				'old_value' =>  strip_tags($this->input->post('old_user_type')),
				'new_value' => strip_tags($this->input->post('user_type')),
				'ip_address' => $getip->ip
			);

			$dataauditxss = $this->security->xss_clean($dataaudit);

			$this->Auditmodel->insert_audit($dataauditxss);

			$this->session->set_flashdata('success', 'Updated successfully!');

			Redirect('account/user_list', true);


		} else {
			$this->session->set_flashdata('error', "Error!");
			Redirect('account/user_list', false);
		}

	}
		

}
