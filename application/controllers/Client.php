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

    
    // CHECK MATCHES/DUPLICATE
    public function check_client() {
        $data = array(
            'client_fname' => strip_tags($this->input->post('client_fname')),
            'client_mname' => strip_tags($this->input->post('client_mname')),
            'client_lname' => strip_tags($this->input->post('client_lname')),
            'client_ename' => strip_tags($this->input->post('client_ename')),
            'birthdate'    => strip_tags($this->input->post('birthdate'))
        );

        $dataxss = $this->security->xss_clean($data);
        $result = $this->Clientmodel->check_for_matches($dataxss);

        if ($result['status'] == 'duplicate') {
            $client_data = $this->Clientmodel->get_client_data($dataxss);
            $household_members = $this->Clientmodel->get_household_members($client_data['client_id']);  
            $authorized_representatives = $this->Clientmodel->get_authorized_representatives($client_data['client_id']); 
            
            $this->session->set_userdata('client_data', $client_data);
            $this->session->set_userdata('household_members', $household_members);
            $this->session->set_userdata('authorized_representatives', $authorized_representatives);
        } else {
            $this->session->set_userdata('client_data', $dataxss);
            $this->session->set_userdata('household_members', []);  
            $this->session->set_userdata('authorized_representatives', []);  
        }

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
        if(!$this->session->userdata('user_type')){
			$this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
			redirect('auth');
        }else{
            
            $data['region'] = $this->Addressmodel->select_region();
            $data['client_data'] = $this->session->userdata('client_data');
            $data['household_members'] = $this->session->userdata('household_members');
            $data['authorized_representatives'] = $this->session->userdata('authorized_representatives');
            $data['message'] = $this->session->userdata('message');

            $this->load->view('template/header');
            if($this->session->userdata('user_type') == 1){ 
				$this->load->view('template/admin-nav');
			}else{
				$this->load->view('template/nav');
			}
            $this->load->view('client_new', $data);
            $this->load->view('template/footer');

        }
    }


    // SAVE CLIENT
    public function save_client() {
        // identifying information

        $dateandtimeToday = date('Y-m-d H:i:s');

        if ($this->input->post('sub_client_status') == 'Duplicate') {
            $client_status = '5';
        }else{
            $client_status = ($this->input->post('eligible') == 1) ? '2' : '4';
        }


        
        if ($this->input->post('sub_client_status') == 'Duplicate') {
            $sub_client_status = '3';
        } else {
            $sub_client_status = ($this->input->post('eligible') == 1) ? '1' : '2';
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
            'client_status' => $client_status,
            'sub_client_status' => $sub_client_status,
            'user_id' => $this->session->userdata('user_id'),
            'dateandtimeApply'  => $dateandtimeToday

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


        // eligibility assessment
        $eligibility_assessment = array(
            'client_id' => $last_id,
            'cea_sixty_yrs_old' => strip_tags($this->input->post('cea_sixty_yrs_old')),
            'cea_pension' => strip_tags($this->input->post('cea_pension')),
            'cea_pension_sss' => strip_tags($this->input->post('cea_pension_sss')),
            'cea_pension_gsis' => strip_tags($this->input->post('cea_pension_gsis')),
            'cea_pension_pvao' => strip_tags($this->input->post('cea_pension_pvao')),
            'cea_regular_income' => strip_tags($this->input->post('cea_regular_income')),
            'cea_regular_income_specify' => strip_tags($this->input->post('cea_regular_income_specify')),
            'cea_existing_illness' => strip_tags($this->input->post('cea_existing_illness')),
            'cea_existing_illness_specify' => strip_tags($this->input->post('cea_existing_illness_specify')),
            'cea_disability' => strip_tags($this->input->post('cea_disability')),
            'cea_disability_deaf' => strip_tags($this->input->post('cea_disability_deaf')),
            'cea_disability_intellectual' => strip_tags($this->input->post('cea_disability_intellectual')),
            'cea_disability_learning' => strip_tags($this->input->post('cea_disability_learning')),
            'cea_disability_mental' => strip_tags($this->input->post('cea_disability_mental')),
            'cea_disability_psychosocial' => strip_tags($this->input->post('cea_disability_psychosocial')),
            'cea_disability_visual' => strip_tags($this->input->post('cea_disability_visual')),
            'cea_disability_speech_and_language' => strip_tags($this->input->post('cea_disability_speech_and_language')),
            'cea_disability_rare_disease' => strip_tags($this->input->post('cea_disability_rare_disease')),
            'cea_daily_living' => strip_tags($this->input->post('cea_daily_living')),
            'cea_daily_living_bathing' => strip_tags($this->input->post('cea_daily_living_bathing')),
            'cea_daily_living_dressing' => strip_tags($this->input->post('cea_daily_living_dressing')),
            'cea_daily_living_grooming' => strip_tags($this->input->post('cea_daily_living_grooming')),
            'cea_daily_living_eating' => strip_tags($this->input->post('cea_daily_living_eating')),
            'cea_daily_living_toileting' => strip_tags($this->input->post('cea_daily_living_toileting')),
            'cea_daily_living_transferring' => strip_tags($this->input->post('cea_daily_living_transferring')),
            'cea_daily_living_cooking' => strip_tags($this->input->post('cea_daily_living_cooking')),
            'cea_daily_living_cleaning' => strip_tags($this->input->post('cea_daily_living_cleaning')),
            'cea_daily_living_managing_finance' => strip_tags($this->input->post('cea_daily_living_managing_finance')),
            'cea_daily_living_grocery_shopping' => strip_tags($this->input->post('cea_daily_living_grocery_shopping')),
            'cea_daily_living_managing_medications' => strip_tags($this->input->post('cea_daily_living_managing_medications'))
        );

        $eligibility_assessmentxss = $this->security->xss_clean($eligibility_assessment);
        $this->Clientmodel->save_client_eligibility_assessment_model($eligibility_assessmentxss);


        // recommendation
        $recommendation = array(
            'client_id' => $last_id,
            'cr_remarks' => strip_tags($this->input->post('cr_remarks')),
            'cr_validated_by' => strip_tags($this->input->post('cr_validated_by')),
            'cr_validated_designation' => strip_tags($this->input->post('cr_validated_designation')),
            'cr_validated_date' => strip_tags($this->input->post('cr_validated_date')),
            'cr_conformed_by' => strip_tags($this->input->post('cr_conformed_by')),
            'cr_conformed_designation' => strip_tags($this->input->post('cr_conformed_designation')),
            'cr_conformed_date' => strip_tags($this->input->post('cr_conformed_date'))
        );

        $recommendationxss = $this->security->xss_clean($recommendation);

        if ($this->Clientmodel->save_client_recommendation_model($recommendationxss)){

            $getip = json_decode(file_get_contents("http://ipinfo.io/"));

            $dataaudit = array(
                'user_id'     => $this->session->user_id,
                'action'     => 'Insert New Client, client_id:'.$last_id ,
                'key_value'     => 'I' ,
                'controller'     => 'main/save_client',
                'ip_address'     => $getip->ip
            );
    
            $dataauditxss = $this->security->xss_clean($dataaudit);
            $this->Auditmodel->insert_audit($dataauditxss);


            $this->session->set_flashdata('success', 'New client successfully save!');

            $hashed_id = md5($last_id);


            redirect('client/information/' . $hashed_id);

        }
    
    } 



    // CLIENT LIST
    public function list() {
        if(!$this->session->userdata('user_type')){
			$this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
			redirect('auth');
        }else{
            
            $this->load->view('template/header');
            if($this->session->userdata('user_type') == 1){ 
				$this->load->view('template/admin-nav');
			}else{
				$this->load->view('template/nav');
			}
            $this->load->view('client_list', $data);
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


    // CLIENT INFORMATION
    public function information($client_id = null) {
        if(!$this->session->userdata('user_type')){
			$this->session->set_flashdata("error", "Sorry, you don't have permission to access the page you were trying to reach!");
			redirect('auth');
        }else{

            if ($client_id !== null) {
                $data['client_information'] = $this->Clientmodel->client_information_model($client_id);
                $data['region'] = $this->Addressmodel->select_region();

                $this->load->view('template/header');
                if($this->session->userdata('user_type') == 1){ 
                    $this->load->view('template/admin-nav');
                }else{
                    $this->load->view('template/nav');
                }
                $this->load->view('client_information', $data);
                $this->load->view('template/footer');
            } else {
                show_404();
            }
	    }
    }


    // CLIENT WAITLISTED LIST
    public function client_waitlisted_list() {
        $this->output->set_header('X-CSRF-TOKEN: ' . $this->security->get_csrf_hash());
    
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $order_index = $this->input->post('order')[0]['column'];
        $order_dir = $this->input->post('order')[0]['dir'];
        $search_value = $this->input->post('search')['value'];
    
        $columns = ['dateandtimeApply', 'client_fname', 'client_mname', 'client_lname', 'client_ename'];
        $order = isset($columns[$order_index]) ? $columns[$order_index] : 'ref_code';
    
        $column_search = [];
        foreach ($columns as $colIdx => $colName) {
            if (isset($this->input->post('columns')[$colIdx]['search']['value'])) {
                $column_search[$colName] = $this->input->post('columns')[$colIdx]['search']['value'];
            } else {
                $column_search[$colName] = '';  
            }
        }
    
        $posts = $this->Clientmodel->get_client_waitlisted_list($limit, $start, $order, $order_dir, $search_value, $column_search);
    
        $data = array();
        foreach ($posts as $post) {
            
            $age = '';
            if ($post->age >= 71) {
                $age = '20';
            } elseif ($post->age >= 66 && $post->age <= 70) { 
                $age = '15';
            } elseif ($post->age >= 60 && $post->age <= 65) { 
                $age = '10';
            }

            $cea_pension = '';
            if($post->cea_pension == 2){
                $cea_pension = '25';
            }else{
                $cea_pension = '15';
            }

            $living_arrangement = '';
            if($post->living_arrangement == 1){
                $living_arrangement = '25';
                
            }else if($post->living_arrangement == 2 && $post->cea_regular_income == 2 || $post->living_arrangement == 3 && $post->cea_regular_income == 2 ){
                $living_arrangement = '15';


            }else if($post->living_arrangement == 2 && $post->cea_regular_income == 1 || $post->living_arrangement == 3 && $post->cea_regular_income == 1 ){
                $living_arrangement = '10';


            }

            $points = intval($age) + intval($cea_pension) + intval($living_arrangement);

    
            $row = array();
            $row[] = date('F d, Y', strtotime($post->dateandtimeApply));
            $row[] = '<div class="text-center">' .$post->client_fname. '</div>';
            $row[] = '<div class="text-center">' .$post->client_mname. '</div>';
            $row[] = '<div class="text-center">' .$post->client_lname. '</div>';
            $row[] = '<div class="text-center">' .$post->client_ename. '</div>';
            $row[] = '<div class="text-center">' .$points.'% </div>';
            $row[] = '<a href=""><i class="fa-solid fa-retweet text-success"></i></a>';
    
            $data[] = $row;
        }
    
        $output = array(
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => $this->Clientmodel->count_all_client_waitlisted_list(),
            "recordsFiltered" => $this->Clientmodel->count_filtered_client_waitlisted_list($search_value, $column_search),
            "data" => $data,
        );
        echo json_encode($output);
    }
        

    


} 

?>

