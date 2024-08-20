<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Finance extends CI_Controller {

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
	private $user_type_redirects;

	public function __construct() {
		parent::__construct();

		$this->user_type_redirects = [
			'1' => 'admin',
			'2' => 'supervisor',
			'4' => 'encoder'
		];

		$this->user_type = $this->session->userdata('user_type');
	}

	public function index() {

		if (!$this->user_type) {
			$this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
			redirect('auth');
			
		} elseif (isset($this->user_type_redirects[$this->user_type])) {
			$this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
			redirect($this->user_type_redirects[$this->user_type]);

		} elseif ($this->session->userdata('user_active') == 0) {

			$this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
			redirect('auth/newuser');

		} else {
			$this->load->view('template/header');
			$this->load->view('template/nav');
			$this->load->view('finance/index');
			$this->load->view('template/footer');
		}
	}


}
