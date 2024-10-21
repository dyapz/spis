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

        $this->load->model('validator/Clientmodel');

        $this->user_type_redirects = [
			'1' => 'admin',
			'2' => 'supervisor',
			'4' => 'finance',
			'5' => 'encoder'
		];

		$this->user_type = $this->session->userdata('user_type');

    }
    
    
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

            $data['validation'] = $this->Clientmodel->get_client_for_validation();
            
            $this->load->view('template/header');
			$this->load->view('template/nav');
            $this->load->view('validator/for_validation', $data);
            $this->load->view('template/footer');

        }
    }


    // CLIENT INFORMATION FOR VALIDATION
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
                $data['client_information'] = $this->Clientmodel->client_information_model($client_id);
                $data['household_members'] = $this->Clientmodel->get_household_members($client_id);
                $data['authorized_representatives'] = $this->Clientmodel->get_authorized_representatives($client_id);
                $data['region'] = $this->Addressmodel->select_region();

                $this->load->view('template/header');
                $this->load->view('template/nav');
                $this->load->view('validator/client_validation_info', $data);
                $this->load->view('template/footer');
            } else {
                show_404();
            }
        } else {
            show_404();
        }
    }
}


public function update_client_assessment(){


    if($this->input->post('eligible') == 1){
        $client_status = '2';
    }else{
        $client_status = '4';
    }

    if($this->input->post('eligible') == 1){
        $sub_client_status = '1';
    } else if ($this->input->post('age') <= '59') {
        $sub_client_status = '6';
    }else if ($this->input->post('cea_pension') == 1) {
        $sub_client_status = '7';
    }else if ($this->input->post('cea_regular_income') == 1) {
        $sub_client_status = '8';
    } else {
        $sub_client_status = '';
    }

    $client_status = array(
        'client_id' => strip_tags($this->input->post('client_id')),
        'client_status' => $client_status,
        'sub_client_status' => $sub_client_status,
        'eligible' => strip_tags($this->input->post('eligible')),
    );

    $client_statusxss = $this->security->xss_clean($client_status);
    $this->Clientmodel->update_client_status_model($client_statusxss);



    $eligibility_assessment = array(
        'client_id' => $this->input->post('client_id'),
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


    $recommendation = array(
        'client_id' => $this->input->post('client_id'),
        'cr_remarks' => strip_tags($this->input->post('cr_remarks')),
        'cr_validated_by' => strip_tags($this->input->post('cr_validated_by')),
        'cr_validated_date' => strip_tags($this->input->post('cr_validated_date'))
    );

    $recommendationxss = $this->security->xss_clean($recommendation);

    if ($this->Clientmodel->save_client_recommendation_model($recommendationxss)){

        $this->session->set_flashdata('success', 'Successfully updated for review by the supervisor!');

        redirect('validator/client/validation');
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
        
        $this->load->view('template/header');
        $this->load->view('template/nav');
        $this->load->view('validator/for_approval');
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
        $onclick = "window.location='" . base_url() . "validator/client/informationApproval/" . $hashed_id . "'";
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
                $data['client_information'] = $this->Clientmodel->client_information_model($client_id);
                $data['household_members'] = $this->Clientmodel->get_household_members($client_id);
                $data['authorized_representatives'] = $this->Clientmodel->get_authorized_representatives($client_id);
                $data['region'] = $this->Addressmodel->select_region();

                $this->load->view('template/header');
                $this->load->view('template/nav');
                $this->load->view('validator/client_information_approval', $data);
                $this->load->view('template/footer');
            } else {
                show_404();
            }
        } else {
            show_404();
        }
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
            
            $this->load->view('template/header');
			$this->load->view('template/nav');
            $this->load->view('validator/client_list');
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

            $row_class = 'clickable-row';

            if ($post->cr_conformed_by == 0) {
                $row_class .= ' text-danger';
            }

            $hashed_id = md5($post->client_id);
    
            $row = array();
            $onclick = "window.location='" . base_url() . "validator/client/information/" . $hashed_id . "'";
            $row['DT_RowClass'] = $row_class;
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
                    $this->load->view('template/nav');
                    $this->load->view('validator/client_information', $data);
                    $this->load->view('template/footer');
                } else {
                    show_404();
                }
            } else {
                show_404();
            }
        }
    }


    public function update_recommendation() {

        $client_status = array(
            'client_id' => strip_tags($this->input->post('client_id')),
            'client_status' => strip_tags($this->input->post('client_status')),
            'sub_client_status' =>  9
        );
        
        $client_statusxss = $this->security->xss_clean($client_status);
        $this->Clientmodel->update_client_status_model($client_statusxss);

        $dataconform = array(
            'client_id' => strip_tags($this->input->post('client_id')),
            'cr_conformed_by' => 0,
            'cr_conformed_date'  => 0000-00-00
        );

        $dataconformxss = $this->security->xss_clean($dataconform);

        if ($this->Clientmodel->update_conform_model($dataconformxss)){

            $getip = json_decode(file_get_contents("http://ipinfo.io/"));

            $dataaudit = array(
                'user_id'     => $this->session->userdata('user_id'),
                'controller'     => 'validator/client/update_recommendation',
                'action'     => 'Update Client Status',
                'key_value'     => 'U',
                'field'     => 'client_status',
                'old_value'     => strip_tags($this->input->post('old_client_status')),
                'new_value'     => strip_tags($this->input->post('client_status')),
                'ip_address'     => $getip->ip
            );
            
            $dataauditxss = $this->security->xss_clean($dataaudit);
            $this->Auditmodel->insert_audit($dataauditxss);

            $this->session->set_flashdata('success', 'Updated successfully!');

            redirect('validator/client/list');
        }
    } 


} 

?>

