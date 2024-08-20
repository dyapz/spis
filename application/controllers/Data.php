<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller {

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

	

	// GSIS
    public function gsis(){
		if(!$this->session->userdata('user_type')){ 
			$this->session->set_flashdata("error", "Sorry you don't have permission to access the page you were trying to reach!");
			redirect('auth');
		}else if($this->user_type && $this->session->userdata('user_active') == 0){
			$this->session->set_flashdata("error", "Sorry you don't have permission to access the page you were trying to reach!");
			redirect('auth');
		}else{

			$this->load->view('template/header');
			$this->load->view('template/nav');
			$this->load->view('data/gsis');
			$this->load->view('template/footer');
		}
	}


	// GSIS DATA
	public function gsis_data() {
		$limit = $this->input->post('length');
		$start = $this->input->post('start');
		$order_index = $this->input->post('order')[0]['column'];
		$order_dir = $this->input->post('order')[0]['dir'];
		$search_value = $this->input->post('search')['value'];
	
		// Get the column name to order by
		$columns = ['gsis_fname', 'gsis_mname', 'gsis_lname', 'gsis_ename'];
		$order = isset($columns[$order_index]) ? $columns[$order_index] : 'gsis_fname';
	
		// Fetch filtered data
		$posts = $this->Datamodel->get_gsis_data($limit, $start, $order, $order_dir, $search_value);
	
		$data = array();
		foreach ($posts as $post) {
			$row = array();
			$row[] = $post->gsis_fname;
			$row[] = $post->gsis_mname;
			$row[] = $post->gsis_lname;
			$row[] = $post->gsis_ename;
	
			$data[] = $row;
		}
	
		// Output result
		$output = array(
			"draw" => intval($this->input->post('draw')),
			"recordsTotal" => $this->Datamodel->count_all_gsis_data(),
			"recordsFiltered" => $this->Datamodel->count_filtered_gsis_data($search_value),
			"data" => $data,
		);
		echo json_encode($output);
	}
	
	
	
	
}
