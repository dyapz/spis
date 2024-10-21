<?php  
 defined('BASEPATH') OR exit('No direct script access allowed');

 class Client extends CI_Controller {

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

        $this->load->model('supervisor/Clientmodel');

        $this->user_type_redirects = [
			'1' => 'admin',
			'3' => 'validator',
			'4' => 'finance',
			'5' => 'encoder'
		];

		$this->user_type = $this->session->userdata('user_type');

    }
    
    
    // READ
    //FOR VALIDATION
    public function validation() {
        if (!$this->user_type) {
            $this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
            redirect('auth');
    
        } elseif (isset($this->user_type_redirects[$this->user_type])) {
            $this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
            redirect($this->user_type_redirects[$this->user_type].'/dashboard');
    
       } elseif ($this->session->userdata('user_active') == 0) {
    
           $this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
           redirect('auth/newuser');
            
        } else {

            $nav['badge_validation'] = $this->Clientmodel->get_for_validation();
            $nav['badge_approval'] = $this->Clientmodel->get_for_approval();
            $data['validation'] = $this->Clientmodel->get_client_for_validation();
            $data['validation_pending'] = $this->Clientmodel->get_client_validation_pending();
            $data['user_validation'] = $this->Clientmodel->get_user_validation();
            
            $this->load->view('template/header');
            $this->load->view('template/nav', $nav);
            $this->load->view('supervisor/for_validation', $data);
            $this->load->view('template/footer');

        }
    }


    public function assign_validator() {
        if ($this->input->post()) {
            $selected_clients = $this->input->post('selected_clients'); 
            $user_id = $this->input->post('user_id'); 
    
            if (!empty($selected_clients) && $user_id) {
                $this->Clientmodel->assign_clients_to_user($selected_clients, $user_id);
                $this->session->set_flashdata('success', 'Clients assigned successfully.');
            } else {
                $this->session->set_flashdata('error', 'Please select clients.');
            }
    
            redirect('supervisor/client/validation');
        }
    }


    // FOR APPROVAL
    public function approval() {
        if (!$this->user_type) {
            $this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
            redirect('auth');

        } elseif (isset($this->user_type_redirects[$this->user_type])) {
            $this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
            redirect($this->user_type_redirects[$this->user_type].'/dashboard');

    } elseif ($this->session->userdata('user_active') == 0) {

        $this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
        redirect('auth/newuser');
            
        } else {

            $nav['badge_validation'] = $this->Clientmodel->get_for_validation();
            $nav['badge_approval'] = $this->Clientmodel->get_for_approval();
            
            $this->load->view('template/header');
            $this->load->view('template/nav', $nav);
            $this->load->view('supervisor/for_approval');
            $this->load->view('template/footer');

        }
    }

    public function client_data_for_approval_list() {

        $this->output->set_header('X-CSRF-TOKEN: ' . $this->security->get_csrf_hash());

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order_index = $this->input->post('order')[0]['column'];
        $order_dir = $this->input->post('order')[0]['dir'];
        $search_value = $this->input->post('search')['value'];

        $columns = ['ref_code', 'client_fname', 'client_mname', 'client_lname', 'client_ename', 'birthdate', 'brgy_name', 'city_name', 'province_name',  'region_name',  'client_status', 'sub_client_status', 'user_fname'];
        $order = isset($columns[$order_index]) ? $columns[$order_index] : 'ref_code';

        $column_search = [];
        foreach ($columns as $colIdx => $colName) {
            if (isset($this->input->post('columns')[$colIdx]['search']['value'])) {
                $column_search[$colName] = $this->input->post('columns')[$colIdx]['search']['value'];
            } else {
                $column_search[$colName] = '';  
            }
        }

        $posts = $this->Clientmodel->get_approval_data_list($limit, $start, $order, $order_dir, $search_value, $column_search);

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
                    $client_status = 'For Validation';
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
                    $sub_client_status = 'GSIS Match Found';
                    break;
                case 4:
                    $sub_client_status = 'PVAO Match Found';
                    break;
                case 5:
                    $sub_client_status = 'Duplicate';
                    break;
                case 6:
                    $sub_client_status = 'Underage';
                    break;
                case 7:
                    $sub_client_status = 'Pensioner';
                    break;
                case 8:
                    $sub_client_status = 'Supported';
                    break;
                case 9:
                    $sub_client_status = 'Change Status ';
                    break;
            } 


            $hashed_id = md5($post->client_id);

            $row = array();
            $onclick = "window.location='" . base_url() . "supervisor/client/informationApproval/" . $hashed_id . "'";
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
            $row[] = $post->user_fname;
            
            $data[] = $row;
        }

        $output = array(
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => $this->Clientmodel->count_all_approval_data_list(),
            "recordsFiltered" => $this->Clientmodel->count_filtered_approval_data_list($search_value, $column_search),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function informationApproval($hashed_id = null) {
        if (!$this->user_type) {
            $this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
            redirect('auth');

        } elseif (isset($this->user_type_redirects[$this->user_type])) {
            $this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
            redirect($this->user_type_redirects[$this->user_type].'/dashboard');

    } elseif ($this->session->userdata('user_active') == 0) {

        $this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
        redirect('auth/newuser');
            
        } else {
            if ($hashed_id !== null) {

                $clients = $this->Clientmodel->get_all_clients(); 

                $client_id = null;
                foreach ($clients as $client) {
                    if (md5($client->client_id) === $hashed_id) {
                        $client_id = $client->client_id;
                        break;
                    }
                }

                if ($client_id !== null) {

                    $nav['badge_validation'] = $this->Clientmodel->get_for_validation();
                    $nav['badge_approval'] = $this->Clientmodel->get_for_approval();
                    $data['client_information'] = $this->Clientmodel->client_information_model($client_id);
                    $data['household_members'] = $this->Clientmodel->get_household_members($client_id);
                    $data['authorized_representatives'] = $this->Clientmodel->get_authorized_representatives($client_id);
                    $data['region'] = $this->Addressmodel->select_region();

                    $this->load->view('template/header');
                    $this->load->view('template/nav', $nav);
                    $this->load->view('supervisor/client_information_approval', $data);
                    $this->load->view('template/footer');
                } else {
                    show_404();
                }
            } else {
                show_404();
            }
        }
    }


    public function conform() {

        $dateToday = date('Y-m-d');

        $data = array(
            'client_id' => strip_tags($this->input->post('client_id')),
            'cr_conformed_by' => $this->session->userdata('user_id'),
            'cr_conformed_date'  => $dateToday
        );

        $dataxss = $this->security->xss_clean($data);

        if ($this->Clientmodel->conform_model($dataxss)){

            $getip = json_decode(file_get_contents("http://ipinfo.io/"));

            $dataaudit = array(
                'user_id'     => $this->session->userdata('user_id'),
                'controller'     => 'supervisor/client/conform',
                'action'     => 'Approved Client Validation',
                'key_value'     => 'U',
                'field'     => 'cr_conformed_by',
                'old_value'     => 0,
                'new_value'     => $this->session->userdata('user_id'),
                'ip_address'     => $getip->ip
            );
    
            $dataauditxss = $this->security->xss_clean($dataaudit);
            $this->Auditmodel->insert_audit($dataauditxss);

            $this->session->set_flashdata('success', 'Approved successfully!');

            redirect('supervisor/client/approval');
        }
    } 
    

    // CLIENT LIST
    public function list() {
        if (!$this->user_type) {
            $this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
            redirect('auth');
    
        } elseif (isset($this->user_type_redirects[$this->user_type])) {
            $this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
            redirect($this->user_type_redirects[$this->user_type].'/dashboard');
    
       } elseif ($this->session->userdata('user_active') == 0) {
    
           $this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
           redirect('auth/newuser');
            
        } else {
            $nav['badge_validation'] = $this->Clientmodel->get_for_validation();
            $nav['badge_approval'] = $this->Clientmodel->get_for_approval();
            $this->load->view('template/header');
            $this->load->view('template/nav', $nav);
            $this->load->view('supervisor/client_list');
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
                    $client_status = 'For Validation';
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
                    $sub_client_status = 'GSIS Match Found';
                    break;
                case 4:
                    $sub_client_status = 'PVAO Match Found';
                    break;
                case 5:
                    $sub_client_status = 'Duplicate';
                    break;
                case 6:
                    $sub_client_status = 'Underage';
                    break;
                case 7:
                    $sub_client_status = 'Pensioner';
                    break;
                case 8:
                    $sub_client_status = 'Supported';
                    break;
                case 9:
                    $sub_client_status = 'Change Status ';
                    break;
            } 

            $hashed_id = md5($post->client_id);
    
            $row = array();
            $onclick = "window.location='" . base_url() . "supervisor/client/information/" . $hashed_id . "'";
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
            $row[] = $post->user_fname;
            
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



    // VIEW DETAIL
    // CLIENT INFORMATION
    public function validationInfo($hashed_id = null) {
        if (!$this->user_type) {
            $this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
            redirect('auth');

        } elseif (isset($this->user_type_redirects[$this->user_type])) {
            $this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
            redirect($this->user_type_redirects[$this->user_type].'/dashboard');

    } elseif ($this->session->userdata('user_active') == 0) {

        $this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
        redirect('auth/newuser');
            
        } else {
        if ($hashed_id !== null) {

            $clients = $this->Clientmodel->get_all_clients(); 

            $client_id = null;
            foreach ($clients as $client) {
                if (md5($client->client_id) === $hashed_id) {
                    $client_id = $client->client_id;
                    break;
                }
            }

            if ($client_id !== null) {
                $nav['badge_validation'] = $this->Clientmodel->get_for_validation();
                $nav['badge_approval'] = $this->Clientmodel->get_for_approval();
                $data['client_information'] = $this->Clientmodel->client_information_model($client_id);
                $data['household_members'] = $this->Clientmodel->get_household_members($client_id);
                $data['authorized_representatives'] = $this->Clientmodel->get_authorized_representatives($client_id);
                $data['region'] = $this->Addressmodel->select_region();

                $this->load->view('template/header');
                $this->load->view('template/nav', $nav);
                $this->load->view('supervisor/client_validation_info', $data);
                $this->load->view('template/footer');
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }
}



    // CLIENT INFORMATION
    public function information($hashed_id = null) {
        if (!$this->user_type) {
            $this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
            redirect('auth');
    
        } elseif (isset($this->user_type_redirects[$this->user_type])) {
            $this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
            redirect($this->user_type_redirects[$this->user_type].'/dashboard');
    
       } elseif ($this->session->userdata('user_active') == 0) {
    
           $this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
           redirect('auth/newuser');
            
        } else {
            if ($hashed_id !== null) {
    
                $clients = $this->Clientmodel->get_all_clients(); 
    
                $client_id = null;
                foreach ($clients as $client) {
                    if (md5($client->client_id) === $hashed_id) {
                        $client_id = $client->client_id;
                        break;
                    }
                }
    
                if ($client_id !== null) {
                    $nav['badge_validation'] = $this->Clientmodel->get_for_validation();
                    $nav['badge_approval'] = $this->Clientmodel->get_for_approval();
                    $data['client_information'] = $this->Clientmodel->client_information_model($client_id);
                    $data['household_members'] = $this->Clientmodel->get_household_members($client_id);
                    $data['authorized_representatives'] = $this->Clientmodel->get_authorized_representatives($client_id);
                    $data['region'] = $this->Addressmodel->select_region();
    
                    $this->load->view('template/header');
                    $this->load->view('template/nav', $nav);
                    $this->load->view('supervisor/client_information', $data);
                    $this->load->view('template/footer');
                } else {
                    show_404();
                }
            } else {
                show_404();
            }
        }
    }


} 

?>

