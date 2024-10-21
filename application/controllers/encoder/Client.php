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

        $this->load->model('encoder/Clientmodel');

        $this->user_type_redirects = [
			'1' => 'admin',
			'2' => 'supervisor',
			'3' => 'validator',
			'4' => 'finance'
		];

		$this->user_type = $this->session->userdata('user_type');

    }
    
    // CREATE
    // CHECK MATCHES/DUPLICATE
    public function check_client() {
        $data = array(
            'client_fname' => strip_tags($this->input->post('client_fname')),
            'client_lname' => strip_tags($this->input->post('client_lname')),
            'birthdate'    => strip_tags($this->input->post('birthdate'))
        );

        $dataxss = $this->security->xss_clean($data);
        $result = $this->Clientmodel->check_for_matches($dataxss);
        $this->session->set_userdata('client_data', $dataxss);

        $message = '';
        switch ($result['status']) {
            case 'gsis_match':
                $message = "GSIS Match found. Do you want to continue?";
                break;
            case 'pvao_match':
                $message = "PVAO Match found. Do you want to continue?";
                break;
            case 'duplicate':
                $message = "Duplicate found. Do you want to continue?";
                break;
            default:
                $message = "No match found. Do you want to continue?";
                break;
        }

        $this->output->set_header('X-CSRF-TOKEN: ' . $this->security->get_csrf_hash());
        
        $this->session->set_userdata('message', $message);

        echo $message;
    }


    // CLIENT NEW FORM
    public function new() {
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
            
            
            $data['region'] = $this->Addressmodel->select_region();
            $data['client_data'] = $this->session->userdata('client_data');
            $data['message'] = $this->session->userdata('message');

            $this->load->view('template/header');
            $this->load->view('template/encoder-nav');
            $this->load->view('encoder/client_new', $data);
            $this->load->view('template/footer');

        }
    }


    // SAVE CLIENT
    public function save_client() {

        // identifying information
        $dateandtimeToday = date('Y-m-d H:i:s');

        // SUB CLIENT STATUS
        // 3 - GSISS MATCH FOUND
        // 4 - PVAO MATCH OUND
        // 5 - DUPLICATE
        // 6 - UNDERAGE

        if ($this->input->post('sub_client_status') == '1') {
            $sub_client_status = '3';
        }else if ($this->input->post('sub_client_status') == '2') {
            $sub_client_status = '4';
        }else if ($this->input->post('sub_client_status') == '3') {
            $sub_client_status = '5';
        }else if ($this->input->post('age') <= '59') {
            $sub_client_status = '6';
        } else {
            $sub_client_status = '';
        }

        // inital of name
        $fname = strtoupper(substr($this->input->post('client_fname'), 0, 1));
        $mname = strtoupper(substr($this->input->post('client_mname'), 0, 1));
        $lname = strtoupper(substr($this->input->post('client_lname'), 0, 1));
        
        // birthdate
        $birthdate = $this->input->post('birthdate');

        $date = new DateTime($birthdate);

        $month_number = (int)$date->format('m');
        $month_letter = chr($month_number + 64); 

        $day = $date->format('d'); 

        $year = $date->format('Y'); 
        $last_two_digits_year = substr($year, -2);

        // psgc present brgy
        $brgy_id = $this->input->post('pre_brgy');
        
        $psgc  = $this->Clientmodel->get_brgy_psgc_model($brgy_id);

        // number series
        $get_client_last_id  = $this->Clientmodel->get_client_last_id_model();

        if ($get_client_last_id['client_id'] !== null) {
            $max_client_id = $get_client_last_id['client_id']; 
        } else {
            $max_client_id = 0; 
        }

        $client_last_id = $max_client_id + 1;

        $seriesNumber = sprintf('%08d', $client_last_id);

        // ref code
        $ref_id = $fname . $mname . $lname . '-' .  $month_letter .  $day .  $last_two_digits_year . '-' . $psgc->psgc_bgy . '-' . $seriesNumber;
        
        
        $identifying_information = array(
            'ref_code' => $ref_id,
            'client_lname' => strip_tags($this->input->post('client_lname')),
            'client_fname' => strip_tags($this->input->post('client_fname')),
            'client_mname' => strip_tags($this->input->post('client_mname')),
            'client_ename' => strip_tags($this->input->post('client_ename')),
            'osca_id' => strip_tags($this->input->post('osca_id')),
            'philsys_id' => strip_tags($this->input->post('philsys_id')),
            'pantawid_y_n' => strip_tags($this->input->post('pantawid_y_n')),
            'mothers_maiden_lname' => strip_tags($this->input->post('mothers_maiden_lname')),
            'mothers_maiden_fname' => strip_tags($this->input->post('mothers_maiden_fname')),
            'mothers_maiden_mname' => strip_tags($this->input->post('mothers_maiden_mname')),
            'perm_region' => strip_tags($this->input->post('perm_region')),
            'perm_prov' => strip_tags($this->input->post('perm_prov')),
            'perm_muni' => strip_tags($this->input->post('perm_muni')),
            'perm_brgy' => strip_tags($this->input->post('perm_brgy')),
            'perm_sitio' => strip_tags($this->input->post('perm_sitio')),
            'perm_street' => strip_tags($this->input->post('perm_street')),
            'same_address' => strip_tags($this->input->post('same_address')),
            'pre_region' => strip_tags($this->input->post('pre_region')),
            'pre_prov' => strip_tags($this->input->post('pre_prov')),
            'pre_muni' => strip_tags($this->input->post('pre_muni')),
            'pre_brgy' => strip_tags($this->input->post('pre_brgy')),
            'pre_sitio' => strip_tags($this->input->post('pre_sitio')),
            'pre_street' => strip_tags($this->input->post('pre_street')),
            'birthdate' => strip_tags($this->input->post('birthdate')),
            'birth_place' => strip_tags($this->input->post('birth_place')),
            'age' => strip_tags($this->input->post('age')),
            'sex' => strip_tags($this->input->post('sex')),
            'civil_status' => strip_tags($this->input->post('civil_status')),
            'cs_others' => strip_tags($this->input->post('cs_others')),
            'living_arrangement' => strip_tags($this->input->post('living_arrangement')),
            'living_arrangement_others' => strip_tags($this->input->post('living_arrangement_others')),
            'sector' => strip_tags($this->input->post('sector')),
            'ip_specify' => strip_tags($this->input->post('ip_specify')),
            'eligible' => strip_tags($this->input->post('eligible')),
            'client_status' => '5',
            'sub_client_status' => $sub_client_status,
            'date_submitted' => strip_tags($this->input->post('date_submitted')),
            'remarks' => strip_tags($this->input->post('remarks')),
            'user_id' => $this->session->userdata('user_id'),
            'date_encoded'  => $dateandtimeToday

        );

        $identifying_informationxss = $this->security->xss_clean($identifying_information);
        $this->Clientmodel->save_client_identifying_information_model($identifying_informationxss);
        $last_id = $this->db->insert_id();


        // spouse
        $spouse = array(
            'client_id' => $last_id,
            'cs_lname' => strip_tags($this->input->post('cs_lname')),
            'cs_fname' => strip_tags($this->input->post('cs_fname')),
            'cs_mname' => strip_tags($this->input->post('cs_mname')),
            'cs_ename' => strip_tags($this->input->post('cs_ename')),
            'cs_address' => strip_tags($this->input->post('cs_address')),
            'cs_contact_num' => strip_tags($this->input->post('cs_contact_num'))
        );

        $spousexss = $this->security->xss_clean($spouse);
        $this->Clientmodel->save_client_spouse_model($spousexss);


        // household member and authorized representatives
        $fam_info = $this->input->post('fam_info');

        if (!empty($fam_info)) {
            foreach ($fam_info as $fam_infos) {
                // household member
                $household_members = array(
                    'client_id' => $last_id,
                    'chm_name' => $fam_infos['chm_name'],
                    'chm_age' => $fam_infos['chm_age'],
                    'chm_civil_status' => $fam_infos['chm_civil_status'],
                    'chm_civil_status_others' => $fam_infos['chm_civil_status_others'],
                    'chm_relationship' => $fam_infos['chm_relationship'],
                    'chm_relationship_others' => $fam_infos['chm_relationship_others'],
                    'chm_occupation' => $fam_infos['chm_occupation'],
                    'chm_monthly_income' => $fam_infos['chm_monthly_income']
                );

                $household_membersxss = $this->security->xss_clean($household_members);
                $this->Clientmodel->save_client_household_members_model($household_membersxss);

                // authorized representatives - Check if any authorized representative data exists
                if (!empty($fam_infos['car_name']) || !empty($fam_infos['car_age']) || !empty($fam_infos['car_relationship']) || !empty($fam_infos['car_contact'])) {
                    $authorized_representatives = array(
                        'client_id' => $last_id,
                        'car_name' => isset($fam_infos['car_name']) ? $fam_infos['car_name'] : null,
                        'car_age' => isset($fam_infos['car_age']) ? $fam_infos['car_age'] : null,
                        'car_relationship' => isset($fam_infos['car_relationship']) ? $fam_infos['car_relationship'] : null,
                        'car_relationship_others' => isset($fam_infos['car_relationship_others']) ? $fam_infos['car_relationship_others'] : null,
                        'car_contact' => isset($fam_infos['car_contact']) ? $fam_infos['car_contact'] : null
                    );

                    $authorized_representativessxss = $this->security->xss_clean($authorized_representatives);
                    $this->Clientmodel->save_client_authorized_representatives_model($authorized_representativessxss);
                }
            }
        }

        $getip = json_decode(file_get_contents("http://ipinfo.io/"));

        $dataaudit = array(
            'user_id'     => $this->session->userdata('user_id'),
            'action'     => 'Insert New Client, client_id:'.$last_id ,
            'key_value'     => 'I' ,
            'controller'     => 'client/save_client',
            'ip_address'     => $getip->ip
        );

        $dataauditxss = $this->security->xss_clean($dataaudit);

        if ($this->Auditmodel->insert_audit($dataauditxss)){

            $this->session->set_flashdata('success', 'New client successfully save!');

            $hashed_id = md5($last_id);

            redirect('encoder/client/information/' . $hashed_id);

        }
    
    } 



    // READ
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
            
            $this->load->view('template/header');
            $this->load->view('template/encoder-nav');
            $this->load->view('encoder/client_list');
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
            } 

            $hashed_id = md5($post->client_id);
    
            $row = array();
            $onclick = "window.location='" . base_url() . "encoder/client/information/" . $hashed_id . "'";
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



    // UPDATE
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
                    $data['client_information'] = $this->Clientmodel->client_information_model($client_id);
                    $data['household_members'] = $this->Clientmodel->get_household_members($client_id);
                    $data['authorized_representatives'] = $this->Clientmodel->get_authorized_representatives($client_id);
                    $data['region'] = $this->Addressmodel->select_region();
    
                    $this->load->view('template/header');
                    $this->load->view('template/encoder-nav');
                    $this->load->view('encoder/client_information', $data);
                    $this->load->view('template/footer');
                } else {
                    show_404();
                }
            } else {
                show_404();
            }
        }
    }


    public function update_client_identifying_information(){

        $identifying_information = array(
            'client_id' => strip_tags($this->input->post('client_id')),
            'client_lname' => strip_tags($this->input->post('client_lname')),
            'client_fname' => strip_tags($this->input->post('client_fname')),
            'client_mname' => strip_tags($this->input->post('client_mname')),
            'client_ename' => strip_tags($this->input->post('client_ename')),
            'osca_id' => strip_tags($this->input->post('osca_id')),
            'philsys_id' => strip_tags($this->input->post('philsys_id')),
            'pantawid_y_n' => strip_tags($this->input->post('pantawid_y_n')),
            'mothers_maiden_lname' => strip_tags($this->input->post('mothers_maiden_lname')),
            'mothers_maiden_fname' => strip_tags($this->input->post('mothers_maiden_fname')),
            'mothers_maiden_mname' => strip_tags($this->input->post('mothers_maiden_mname')),
            'perm_region' => strip_tags($this->input->post('perm_region')),
            'perm_prov' => strip_tags($this->input->post('perm_prov')),
            'perm_muni' => strip_tags($this->input->post('perm_muni')),
            'perm_brgy' => strip_tags($this->input->post('perm_brgy')),
            'perm_sitio' => strip_tags($this->input->post('perm_sitio')),
            'perm_street' => strip_tags($this->input->post('perm_street')),
            'same_address' => strip_tags($this->input->post('same_address')),
            'pre_region' => strip_tags($this->input->post('pre_region')),
            'pre_prov' => strip_tags($this->input->post('pre_prov')),
            'pre_muni' => strip_tags($this->input->post('pre_muni')),
            'pre_brgy' => strip_tags($this->input->post('pre_brgy')),
            'pre_sitio' => strip_tags($this->input->post('pre_sitio')),
            'pre_street' => strip_tags($this->input->post('pre_street')),
            'birthdate' => strip_tags($this->input->post('birthdate')),
            'birth_place' => strip_tags($this->input->post('birth_place')),
            'age' => strip_tags($this->input->post('age')),
            'sex' => strip_tags($this->input->post('sex')),
            'civil_status' => strip_tags($this->input->post('civil_status')),
            'cs_others' => strip_tags($this->input->post('cs_others')),
            'living_arrangement' => strip_tags($this->input->post('living_arrangement')),
            'living_arrangement_others' => strip_tags($this->input->post('living_arrangement_others')),
            'sector' => strip_tags($this->input->post('sector')),
            'ip_specify' => strip_tags($this->input->post('ip_specify'))
        );

        $identifying_informationxss = $this->security->xss_clean($identifying_information);


        if ($this->Clientmodel->update_client_identifying_information_model($identifying_informationxss)){

            $this->session->set_flashdata('success', 'Successfully updated!');

            $hashed_id = md5($identifying_information['client_id']);

            redirect('encoder/client/information/' . $hashed_id);
        }

    }


    public function update_hm_family_information() {

        $client_id = array(
            'client_id' => $this->input->post('client_id')
        );
    
        $client_idxss = $this->security->xss_clean($client_id);
    
        $fam_info = $this->input->post('fam_info');
    
        if (!empty($fam_info)) {
            foreach ($fam_info as $fam_infos) {
                // household member
                $update_household_members = array(
                    'chm_id' => $fam_infos['chm_id'],
                    'chm_name' => $fam_infos['chm_name'],
                    'chm_age' => $fam_infos['chm_age'],
                    'chm_civil_status' => $fam_infos['chm_civil_status'],
                    'chm_civil_status_others' => $fam_infos['chm_civil_status_others'],
                    'chm_relationship' => $fam_infos['chm_relationship'],
                    'chm_relationship_others' => $fam_infos['chm_relationship_others'],
                    'chm_occupation' => $fam_infos['chm_occupation'],
                    'chm_monthly_income' => $fam_infos['chm_monthly_income']
                );
    
                $update_household_membersxss = $this->security->xss_clean($update_household_members);
    
                $insert_household_members = array(
                    'client_id' => $client_idxss['client_id'],
                    'chm_name' => $fam_infos['chm_name'],
                    'chm_age' => $fam_infos['chm_age'],
                    'chm_civil_status' => $fam_infos['chm_civil_status'],
                    'chm_civil_status_others' => $fam_infos['chm_civil_status_others'],
                    'chm_relationship' => $fam_infos['chm_relationship'],
                    'chm_relationship_others' => $fam_infos['chm_relationship_others'],
                    'chm_occupation' => $fam_infos['chm_occupation'],
                    'chm_monthly_income' => $fam_infos['chm_monthly_income']
                );
    
                $household_membersxss = $this->security->xss_clean($insert_household_members);
    
                $existing_chm_id = $this->Clientmodel->get_existing_chm_id($fam_infos['chm_id']);
    
                if ($existing_chm_id) {
                    $this->Clientmodel->update_client_household_members_model($update_household_membersxss);
                } else {
                    $this->Clientmodel->save_client_household_members_model($household_membersxss);
                }
            }
        }


        $rep_info = $this->input->post('rep_info');

        if (!empty($rep_info)) {
            foreach ($rep_info as $rep_infos) {
                // Prepare data for update/insert
                $update_authorized_representative = array(
                    'car_id' => isset($rep_infos['car_id']) ? $rep_infos['car_id'] : null,
                    'car_name' => $rep_infos['car_name'],
                    'car_age' => $rep_infos['car_age'],
                    'car_relationship' => $rep_infos['car_relationship'],
                    'car_relationship_others' => $rep_infos['car_relationship_others'],
                    'car_contact' => $rep_infos['car_contact']
                );
        
                $update_authorized_representativexss = $this->security->xss_clean($update_authorized_representative);
        
                // If `car_id` exists, update the representative
                if (!empty($rep_infos['car_id'])) {
                    $existing_car_id = $this->Clientmodel->get_existing_car_id($rep_infos['car_id']);
        
                    if ($existing_car_id) {
                        // Update the record
                        $this->Clientmodel->update_client_authorized_representative_model($update_authorized_representativexss);
                    }
                } else {
                    // Insert new representative
                    $insert_authorized_representative = array(
                        'client_id' => $client_idxss['client_id'],
                        'car_name' => $rep_infos['car_name'],
                        'car_age' => $rep_infos['car_age'],
                        'car_relationship' => $rep_infos['car_relationship'],
                        'car_relationship_others' => $rep_infos['car_relationship_others'],
                        'car_contact' => $rep_infos['car_contact']
                    );
        
                    $authorized_representativessxss = $this->security->xss_clean($insert_authorized_representative);
        
                    $this->Clientmodel->save_client_authorized_representatives_model($authorized_representativessxss);
                }
            }
        }
    
        $this->session->set_flashdata('success', 'Successfully updated!');
    
        $hashed_id = md5($client_id['client_id']);
    
        redirect('encoder/client/information/' . $hashed_id);
    }
    
    

    public function update_client_identifying_information_remarks(){

        $identifying_information_remarks = array(
            'client_id' => strip_tags($this->input->post('client_id')),
            'remarks' => strip_tags($this->input->post('remarks'))
        );

        $identifying_informationxss = $this->security->xss_clean($identifying_information_remarks);


        if ($this->Clientmodel->update_client_identifying_information_model($identifying_informationxss)){

            $this->session->set_flashdata('success', 'Successfully updated!');

            $hashed_id = md5($identifying_information_remarks['client_id']);

            redirect('encoder/client/information/' . $hashed_id);
        }
    }

    

    

} 

?>

