<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

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


	public function index()	{
		if(!$this->session->userdata('user_type')){ 
            $this->session->set_flashdata("error", "Sorry you don't have permission to access the page you were trying to reach!");
			redirect('auth');
		}else{

			$this->load->view('template/header');
			$this->load->view('template/nav');
			$this->load->view('index');
			$this->load->view('template/footer');
		}
	}

	public function client_list()	{
		if(!$this->session->userdata('user_type')){ 
            $this->session->set_flashdata("error", "Sorry you don't have permission to access the page you were trying to reach!");
			redirect('auth');
		}else{
		$this->load->view('template/header');
		$this->load->view('template/nav');
		$this->load->view('client_list');
		$this->load->view('template/footer');
		}
	}

}
