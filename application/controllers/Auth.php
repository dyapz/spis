<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

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

	 public function index(){
		if($this->session->userdata('user_type') == '1'){ 
			redirect('main');
		}else{
			$this->load->view('login');
		}
	}


	//SESSION LOGIN
	public function authenticate(){
		$username    = $this->input->post('username',TRUE);
		$password = md5($this->input->post('password',TRUE));
		$validate = $this->Authmodel->validate($username,$password);

		$recaptchaResponse = trim($this->input->post('g-recaptcha-response'));

		$userIp=$this->input->ip_address();
	
		$secret = $this->config->item('google_secret');

		$url="https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$recaptchaResponse."&remoteip=".$userIp;

		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$output = curl_exec($ch); 
		curl_close($ch);      
		
		$status= json_decode($output, true);

		// VALIDATE IF USERNAME AND RECAPTCHA IS SUCCESSFULL
		if($validate->num_rows() > 0 && $status['success']){
			$data  = $validate->row_array();
			$user_id = $data['user_id'];
			$user_fname = $data['user_fname'];
			$user_mname = $data['user_mname'];
			$user_lname = $data['user_lname'];
			$user_type = $data['user_type'];
			$sesdata = array(
				'user_id' => $user_id,
				'user_fname' => $user_fname,
				'user_mname' => $user_mname,
				'user_lname' => $user_lname,
				'user_type' => $user_type,
				'logged_in' => TRUE
			);
			$this->session->set_userdata($sesdata);
			$isLoggedIn = $this->session->user_id;

			$getip = json_decode(file_get_contents("http://ipinfo.io/"));

			$dataaudit = array(
				'user_id'     => $this->session->user_id,
				'action'     => 'Login',
				'controller'     => 'auth/authenticate',
				'ip_address'     => $getip->ip
			);

			$dataauditxss = $this->security->xss_clean($dataaudit);
			$this->Auditmodel->insert_audit($dataauditxss);
			
			//LOGIN USER TYPE
			if($user_type == '1'){
				echo $this->session->set_flashdata('success','Welcome Back!');
				redirect('main');
			}else if($user_type == '0'){
				redirect('main/newuser');
			}

		}elseif($validate->num_rows() == False){
			echo $this->session->set_flashdata('error','Wrong username or password!');
			redirect('auth');
		}elseif($status['success'] == False){
			echo $this->session->set_flashdata('gcaptcha_error','Sorry Recaptcha Unsuccessful!!!');
			redirect('auth');
		}
	}


	//LOGOUT	  
	public function logout(){
		session_destroy();

		$getip = json_decode(file_get_contents("http://ipinfo.io/"));

		$dataaudit = array(
			'user_id'     => $this->session->user_id,
			'action'     => 'Logout',
			'controller'     => 'auth/logout',
			'ip_address'     => $getip->ip
		);

		$dataauditxss = $this->security->xss_clean($dataaudit);
		$this->Auditmodel->insert_audit($dataauditxss);
		redirect('auth');
	}


	//REGISTRATION
	public function register() {

		$data = $userData = array(); 

		$recaptchaResponse = trim($this->input->post('g-recaptcha-response'));

		$userIp=$this->input->ip_address();
		
		$secret = $this->config->item('google_secret');

		$url="https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$recaptchaResponse."&remoteip=".$userIp;

		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$output = curl_exec($ch); 
		curl_close($ch);      
			
		$status= json_decode($output, true);

		if($this->input->post('registerSubmit')){ 
			$this->form_validation->set_rules('region_id', 'Region', 'required'); 
			$this->form_validation->set_rules('user_fname', 'First Name', 'required|callback_text_only'); 
			$this->form_validation->set_rules('user_mname', 'Middle Name', 'callback_text_only'); 
			$this->form_validation->set_rules('user_lname', 'Last Name', 'required|callback_text_only'); 
			$this->form_validation->set_rules('user_ename', 'Suffix Name', 'callback_text_only'); 
			$this->form_validation->set_rules('user_gender', 'Sex', 'required'); 
			$this->form_validation->set_rules('user_email', 'Email', 'required|callback_checkEmail|valid_email'); 
			$this->form_validation->set_rules('user_designation', 'Designation', 'required|callback_text_only'); 
			$this->form_validation->set_rules('username', 'Username', 'required|callback_checkUsername|callback_textnumber_only'); 
			$this->form_validation->set_rules('password', 'password', 'required|min_length[6]|max_length[25]|callback_password_strong'); 
			$this->form_validation->set_rules('cpassword', 'confirm password', 'required|matches[password]'); 
			
			$userData = array( 
				'region_id' => strip_tags($this->input->post('region_id')), 
				'user_fname' => strip_tags($this->input->post('user_fname')), 
				'user_mname' => strip_tags($this->input->post('user_mname')), 
				'user_lname' => strip_tags($this->input->post('user_lname')), 
				'user_ename' => strip_tags($this->input->post('user_ename')), 
				'user_gender' => strip_tags($this->input->post('user_gender')), 
				'user_email' => strip_tags($this->input->post('user_email')), 
				'user_designation' => strip_tags($this->input->post('user_designation')), 
				'username' => strip_tags($this->input->post('username')), 
				'password' => md5($this->input->post('password'))
			); 

			$dataxss = $this->security->xss_clean($userData);

			if($this->form_validation->run() == true && $status['success']){ 
				$insert = $this->Authmodel->register_model($dataxss); 
				if($insert){ 
					$insert_id = $this->db->insert_id();
					$getip = json_decode(file_get_contents("http://ipinfo.io/"));
					$dataaudit = array(
						'user_id'     => $insert_id,
						'action'     => 'Registration',
						'controller'     => 'auth/register',
						'ip_address'     => $getip->ip
					);

					$dataauditxss = $this->security->xss_clean($dataaudit);
					$this->Auditmodel->insert_audit($dataauditxss);

					echo $this->session->set_flashdata('success', 'Your account registration has been successful. For security purposes, Your account is pending and for review by our staff before activation. This usally takes less than an a hour! Thank you'); 
					redirect('auth'); 
				}else{ 
					$data['error_msg'] = 'Some problems occured, please try again.'; 
				} 
			}else{ 
				$this->session->set_flashdata('gcaptcha_error', 'Sorry Google Recaptcha Unsuccessful!!');
			}
		} 

		$data['user'] = $userData;
		$data['region'] = $this->Addressmodel->select_region();
		$this->load->view('register', $data);
	}



	//TEXT ONLY VALIDATION
	public function text_only($str){
		if(preg_match('/^[a-zA-Z ]+$/', $str)){
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

		// EXISTING EMAIL VALIDATION
		public function checkEmail($str) {
			$data_email = array(
				'returnType' => 'count',
				'conditions' => array(
					'user_email' => $str
				)
			);
			$checkEmail = $this->Authmodel->getEmail($data_email);
			if ($checkEmail > 0) {
				$this->form_validation->set_message('checkEmail', 'The given Email already exists.');
				return FALSE;
			} else {
				return TRUE;
			}
		}


	//EXISTING USERNAME VALIDATION
    public function checkUsername($str){ 
        $conUser = array( 
            'returnType' => 'count', 
            'conditions' => array( 
                'username' => $str 
            ) 
        ); 
        $checkUser = $this->Authmodel->getUsername($conUser); 
        if($checkUser > 0){ 
            $this->form_validation->set_message('checkUsername', 'The given Username already exists.'); 
            return FALSE;
		}else{
			return TRUE; 
        } 
    } 



	// FORGOT PASSWORD
	public function forgotpassword(){
		  $user_email = $this->input->post('user_email');      
		  $findemail = $this->Authmodel->emailexists_forgotpassword_model($user_email);  
		  if($findemail){
		   		$this->Authmodel->sendemail_forgotpassword_model($findemail);        
			}else{
		   $this->session->set_flashdata('error',' Email not found or not active!');
		   redirect('auth');
	   }
	}

    // OTP VERIFICATION
	public function otp_verification($user_id = null) {
		if ($user_id !== null) {
			$data['otp_verification'] = $this->Authmodel->otp_verification_model($user_id);
			$this->load->view('otp_verification', $data);
		} else {
			show_404();
		}
	}

	// OTP VERIFICATION VALID
	public function otp_verify() {
		$user_id = strip_tags($this->input->post('user_id'));
		$user_otp_verify = strip_tags($this->input->post('user_otp_verify'));
		$hashed_id = md5($user_id);
		$hashed_otp_id = md5($user_otp_verify);
	
		$verify_user_id = $this->Authmodel->otp_verify_model($user_id);
	
		if($verify_user_id) {
			if($verify_user_id['user_otp'] == $user_otp_verify) {
				$verify_otp_expiry = $this->Authmodel->otp_verify_expired_model($user_id);
	
				if($verify_otp_expiry) {
					$this->session->set_flashdata('error', 'Your OTP is expired!');
					redirect('auth/otp_verification/'.$hashed_id);
				} else {
					redirect('auth/new_password/'.$hashed_id.'/'.$hashed_otp_id);
				}
			} else {
				$this->session->set_flashdata('error', 'Invalid OTP verification code!');
				redirect('auth/otp_verification/'.$hashed_id);
			}
		} else {
			$this->session->set_flashdata('error', 'User not found!');
			return FALSE;
		}
	}
	

	// CHANGE PASSWORD DURING FORGOT PASSWORD
	public function new_password($user_id = null, $otp_id = null) {
		$recaptchaResponse = trim($this->input->post('g-recaptcha-response'));

		$userIp=$this->input->ip_address();
		
		$secret = $this->config->item('google_secret');

		$url="https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$recaptchaResponse."&remoteip=".$userIp;

		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$output = curl_exec($ch); 
		curl_close($ch);      
			
		$status= json_decode($output, true);

		if($this->input->post('password')){ 

			$this->form_validation->set_rules('password', 'password', 'required|min_length[6]|max_length[25]|callback_password_strong'); 
			$this->form_validation->set_rules('cpassword', 'confirm password', 'required|matches[password]'); 

			$data = array( 
				'user_id' => strip_tags($this->input->post('user_id')), 
				'password' => md5($this->input->post('password'))
			); 

			$dataxss = $this->security->xss_clean($data);

			if($this->input->post('old_value') == md5($this->input->post('password'))){
				$this->session->set_flashdata('error', 'Your new password cannot be the same as your current password. Please choose a different password.');

			}else{

				if($this->form_validation->run() == true && $status['success']){
					$update = $this->Authmodel->update_new_password_model($dataxss);

					if($update){

						$getip = json_decode(file_get_contents("http://ipinfo.io/"));
						$dataaudit = array(
							'user_id'     => strip_tags($this->input->post('user_id')),
							'controller'     => 'auth/new_password',
							'action'     => 'Change Password During the Forgotten/Reset Password Process',
							'key_value'     => 'U',
							'field'     => 'password',
							'old_value'     => $this->input->post('old_value'),
							'new_value'     => md5($this->input->post('password')),
							'ip_address'     => $getip->ip
						);

						$dataauditxss = $this->security->xss_clean($dataaudit);
						$this->Auditmodel->insert_audit($dataauditxss);

						$this->session->set_flashdata('success', 'Password Changed Successfully!');
						redirect('auth');

					}else{ 
						$data['error_msg'] = 'Some problems occured, please try again.'; 
					} 
				}else{ 
					$this->session->set_flashdata('gcaptcha_error', 'Sorry Google Recaptcha Unsuccessful!!');
				}

			}

		}

		if ($user_id !== null && $otp_id !== null) {
			$data['new_password'] = $this->Authmodel->new_password_model($user_id, $otp_id);
			$this->load->view('new_password', $data);
		} else {
			show_404();
		}
	}

	// public function resend_otp() {
	// 	$user_id = strip_tags($this->input->post('user_id'));
	// 	$verify_user_id = $this->Authmodel->otp_verify_model($user_id);  

	// 	if($verify_user_id){

	// 		$email


	// 		$verify_user_id = $this->Authmodel->resend_otp_model($user_id);  

	// 	}

	// }

}
