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

	 private $user_type_redirects;
	 private $user_type;
 
	 public function __construct() {
		 parent::__construct();
 

		 $this->user_type_redirects = [
			'2' => 'supervisor',
			'3' => 'finance',
			'4' => 'encoder'
		   ];

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
		}else if($this->session->userdata('user_type') != 1){
			$this->session->set_flashdata("error", "Sorry you don't have permission to access the page you were trying to reach!");
			redirect($this->user_type_redirects[$this->user_type]);
		}else{
			$this->load->view('template/header');
			if($this->session->userdata('user_type') == 1){ 
				$this->load->view('template/admin-nav');
			}else{
				$this->load->view('template/nav');
			}
			$this->load->view('data/gsis');
			$this->load->view('template/footer');
		}
	}

	
	// GSIS DATA
	
	public function gsis_data() {
		if(!$this->session->userdata('user_type')){ 
			$this->session->set_flashdata("error", "Sorry you don't have permission to access the page you were trying to reach!");
			redirect('auth');
		}else if($this->user_type && $this->session->userdata('user_active') == 0){
			$this->session->set_flashdata("error", "Sorry you don't have permission to access the page you were trying to reach!");
			redirect('auth');
		}else{
			$this->output->set_header('X-CSRF-TOKEN: ' . $this->security->get_csrf_hash());

			$limit = $this->input->post('length');
			$start = $this->input->post('start');
			$order_index = $this->input->post('order')[0]['column'];
			$order_dir = $this->input->post('order')[0]['dir'];
			$search_value = $this->input->post('search')['value'];
		
			$columns = ['gsis_fname', 'gsis_mname', 'gsis_lname', 'gsis_ename', 'gsis_birthdate', 'gsis_pension_type', 'gsis_pension_status'];
			$order = isset($columns[$order_index]) ? $columns[$order_index] : 'gsis_fname';
		
			$column_search = [];
			foreach ($columns as $colIdx => $colName) {
				$column_search[$colName] = $this->input->post('columns')[$colIdx]['search']['value'];
			}
		
			$posts = $this->Datamodel->get_gsis_data($limit, $start, $order, $order_dir, $search_value, $column_search);
		
			$data = array();
			foreach ($posts as $post) {
		
				$gsis_pension_status = ($post->gsis_pension_status == 1) ? 'Active' : 'Suspended';
		
				$gsis_pension_type = '';
				switch ($post->gsis_pension_type) {
					case 1:
						$gsis_pension_type = 'Disability Pensioner';
						break;
					case 2:
						$gsis_pension_type = 'Old Age Pensioner';
						break;
					case 3:
						$gsis_pension_type = 'Survivorship Pensioner';
						break;
				}
		
				$row = array();
				$row[] = $post->gsis_fname;
				$row[] = $post->gsis_mname;
				$row[] = $post->gsis_lname;
				$row[] = $post->gsis_ename;
				$row[] = date('F d, Y',strtotime($post->gsis_birthdate));
				$row[] = $gsis_pension_type;
				$row[] = $gsis_pension_status;
		
				$data[] = $row;
			}
		
			$output = array(
				"draw" => intval($this->input->post('draw')),
				"recordsTotal" => $this->Datamodel->count_all_gsis_data(),
				"recordsFiltered" => $this->Datamodel->count_filtered_gsis_data($search_value, $column_search),
				"data" => $data,
			);
			echo json_encode($output);
		}
	}
	
	

	// PVAO
	public function pvao(){
		if(!$this->session->userdata('user_type')){ 
			$this->session->set_flashdata("error", "Sorry you don't have permission to access the page you were trying to reach!");
			redirect('auth');
		}else if($this->user_type && $this->session->userdata('user_active') == 0){
			$this->session->set_flashdata("error", "Sorry you don't have permission to access the page you were trying to reach!");
			redirect('auth');
		}else if($this->session->userdata('user_type') != 1){
			$this->session->set_flashdata("error", "Sorry you don't have permission to access the page you were trying to reach!");
			redirect($this->user_type_redirects[$this->user_type]);
		}else{
			$this->load->view('template/header');
			if($this->session->userdata('user_type') == 1){ 
				$this->load->view('template/admin-nav');
			}else{
				$this->load->view('template/nav');
			}
			$this->load->view('data/pvao');
			$this->load->view('template/footer');
		}
	}
	
	// PVAO DATA
	public function pvao_data() {
		if(!$this->session->userdata('user_type')){ 
			$this->session->set_flashdata("error", "Sorry you don't have permission to access the page you were trying to reach!");
			redirect('auth');
		}else if($this->user_type && $this->session->userdata('user_active') == 0){
			$this->session->set_flashdata("error", "Sorry you don't have permission to access the page you were trying to reach!");
			redirect('auth');
		}else{
			$this->output->set_header('X-CSRF-TOKEN: ' . $this->security->get_csrf_hash());
		
			$limit = $this->input->post('length');
			$start = $this->input->post('start');
			$order_index = $this->input->post('order')[0]['column'];
			$order_dir = $this->input->post('order')[0]['dir'];
			$search_value = $this->input->post('search')['value'];
		
			$columns = ['pvao_fname', 'pvao_mname', 'pvao_lname', 'pvao_ename', 'pvao_birthdate'];
			$order = isset($columns[$order_index]) ? $columns[$order_index] : 'pvao_fname';
		
			$column_search = [];
			foreach ($columns as $colIdx => $colName) {
				$column_search[$colName] = $this->input->post('columns')[$colIdx]['search']['value'];
			}
		
			$posts = $this->Datamodel->get_pvao_data($limit, $start, $order, $order_dir, $search_value, $column_search);
		
			$data = array();
			foreach ($posts as $post) {
		
				$row = array();
				$row[] = $post->pvao_fname;
				$row[] = $post->pvao_mname;
				$row[] = $post->pvao_lname;
				$row[] = $post->pvao_ename;
				$row[] = date('F d, Y',strtotime($post->pvao_birthdate));
				$data[] = $row;
			}
		
			$output = array(
				"draw" => intval($this->input->post('draw')),
				"recordsTotal" => $this->Datamodel->count_all_pvao_data(),
				"recordsFiltered" => $this->Datamodel->count_filtered_pvao_data($search_value, $column_search),
				"data" => $data,
			);
			echo json_encode($output);
		}
	}
	
	
}
