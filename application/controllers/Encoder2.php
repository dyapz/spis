<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Encoder extends CI_Controller {

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
			'3' => 'validator',
			'4' => 'finance'
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
			$this->load->view('encoder/index');
			$this->load->view('template/footer');
		}
	}



	// CLIENT LIST
	public function list() {
		if(!$this->session->userdata('user_type')){
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
			$this->load->view('encoder/client_list', $data);
			$this->load->view('template/footer');

		}
	}


// CLIENT DATA LIST
public function client_data_list() {

	$this->output->set_header('X-CSRF-TOKEN: ' . $this->security->get_csrf_hash());

	$limit = $this->input->post('length');
	$start = $this->input->post('start');
	$order_index = $this->input->post('order')[0]['column'];
	$order_dir = $this->input->post('order')[0]['dir'];
	$search_value = $this->input->post('search')['value'];

	$columns = ['ref_code', 'client_fname', 'client_mname', 'client_lname', 'client_ename', 'birthdate', 'brgy_name', 'city_name', 'province_name',  'region_name',  'client_status', 'sub_client_status'];
	$order = isset($columns[$order_index]) ? $columns[$order_index] : 'ref_code';

	$column_search = [];
	foreach ($columns as $colIdx => $colName) {
		if (isset($this->input->post('columns')[$colIdx]['search']['value'])) {
			$column_search[$colName] = $this->input->post('columns')[$colIdx]['search']['value'];
		} else {
			$column_search[$colName] = '';  
		}
	}

	$posts = $this->Clientmodel->get_client_data_list($limit, $start, $order, $order_dir, $search_value, $column_search);

	$data = array();
	foreach ($posts as $post) {

		// $gsis_pension_status = ($post->gsis_pension_status == 1) ? 'Active' : 'Suspended';

		$client_status = '';
		switch ($post->client_status) {
			case 1:
				$client_status = 'Active';
				break;
			case 2:
				$client_status = 'Waitlisted';
				break;
			case 3:
				$client_status = 'Delisted';
				break;
			case 4:
				$client_status = 'Not Eligible';
				break;
			case 5:
				$client_status = 'For Verification';
				break;
		}

		$sub_client_status = '';
		switch ($post->sub_client_status) {
			case 1:
				$sub_client_status = 'Eligible';
				break;
			case 2:
				$sub_client_status = 'Not Eligible';
				break;
			case 3:
				$sub_client_status = 'Duplicate';
				break;
				
		} 

		$hashed_id = md5($post->client_id);

		$row = array();
		$onclick = "window.location='" . base_url() . "client/information/" . $hashed_id . "'";
		$row['DT_RowClass'] = 'clickable-row';
		$row['DT_RowAttr'] = array('onclick' => $onclick);  

		$row[] = $post->client_id;
		$row[] = $post->ref_code;
		$row[] = $post->client_fname;
		$row[] = $post->client_mname;
		$row[] = $post->client_lname;
		$row[] = $post->client_ename;
		$row[] = date('F d, Y',strtotime($post->birthdate));
		$row[] = $post->brgy_name;
		$row[] = $post->city_name;
		$row[] = $post->province_name;
		$row[] = $post->region_name;
		$row[] = $client_status;
		$row[] = $sub_client_status;
		
		$data[] = $row;
	}

	$output = array(
		"draw" => intval($this->input->post('draw')),
		"recordsTotal" => $this->Clientmodel->count_all_client_data_list(),
		"recordsFiltered" => $this->Clientmodel->count_filtered_client_data_list($search_value, $column_search),
		"data" => $data,
	);
	echo json_encode($output);
}












}
