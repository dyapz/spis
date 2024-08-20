<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Address extends CI_Controller {

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

	//  PROVINCE
	public function province() {
		if ($this->input->post('region_id')) {
			echo $this->Addressmodel->select_province($this->input->post('region_id'));
		} 
	}

	//  MUNICIPALITY
	public function municipality(){
		if($this->input->post('province_id')){
			echo $this->Addressmodel->select_municipality($this->input->post('province_id'));
		}
	}

	//  BARANGAY
	public function barangay(){
		if($this->input->post('city_id')){
			echo $this->Addressmodel->select_barangay($this->input->post('city_id'));
		}
	}

}
