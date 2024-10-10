<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll extends CI_Controller {

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

 
	 public function __construct() {
		 parent::__construct();
 
	 }

	 


	// UPDATE PROFILE
	public function index() {
	
		$this->load->view('template/header');
		if($this->session->userdata('user_type') == 1){ 
			$this->load->view('template/admin-nav');
		}else{
			$this->load->view('template/nav');
		}
		$this->load->view('payroll/index');
		$this->load->view('template/footer');
	} 
}
	
