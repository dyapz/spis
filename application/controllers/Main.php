<?php  
 defined('BASEPATH') OR exit('No direct script access allowed');

 class Main extends CI_Controller {

// ==================================================================
// =========================== INDEX ================================
// ================================================================== 
public function index(){
    if(!$this->session->userdata('user_type')){ 
        $this->session->set_flashdata("error", "Sorry you don't have permission to access the page you were trying to reach!");
        redirect('auth');
    }else{

        $this->load->view('template/header');
        $this->load->view('template/nav');
        $this->load->view('admin/index');
        $this->load->view('template/footer');
    }
}

public function newuser(){
    $this->load->view('newuser');

}   


// ==================================================================
// ===================== CLIENT SEARCH ==============================
// ================================================================== 
public function search_client()
{
    if ($this->session->userdata('user_type')) {
        if ($this->input->post('idcbPlanUpdateStatus')) {

            // File Upload
            $rand = rand(999, 9999);
            $datefile = date('Ymd');
            $upload_path = realpath(APPPATH . '../upload/');

            if (!empty($_FILES['upload']['name'])) {
                $config['upload_path'] = $upload_path;
                $config['allowed_types'] = 'jpg|pdf|xlsx|xls|mpeg4|mp4';
                $config['file_name'] = $rand . $datefile . $_FILES['upload']['name'];
                $config['max_size'] = '100000';

                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if (!$this->upload->do_upload('upload')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect('admin/client_table');
                }

                $uploadData = $this->upload->data();
                $filename = $uploadData['file_name'];
            } else {
                $filename = ''; // Handle if no file uploaded
            }

            // Sanitize input data
            $cb_plan_id = $this->input->post('cb_plan_id');
            $cb_status = $this->input->post('cb_status');
            $cb_updated_status_by = $this->session->userdata('user_id');

            // Update data array
            $dataEdit = array(
                'cb_status' => $cb_status,
                'filename' => $filename,
                'cb_updated_status_by' => $cb_updated_status_by
            );

            // Sanitize data
            $dataEditxss = $this->security->xss_clean($dataEdit);
            $this->Mainmodel->update_data_cb_plan_model($cb_plan_id, $dataEditxss);

            $this->session->set_flashdata('success', 'Data Successfully Updated!');
            redirect('admin/search');
        }

        // Load view
        $data['form_data'] = $this->input->post();
        // $data['notification'] = $this->Mainmodel->notif_model();
        // $data['ta_notif'] = $this->Mainmodel->ta_notif_model();
        $data['client_table'] = $this->Mainmodel->client_table_model();

        $this->load->view('template/header');
        $this->load->view('template/nav');
        $this->load->view('admin/search_client', $data);
        $this->load->view('template/footer');
    } else {
        redirect($route);
    }
}


// ==================================================================
// ==================== Create/Insert ===============================
// ==================================================================
public function add_client()
{
    if (!$this->session->userdata('user_type')) {
        // $user_type = $this->session->userdata('user_type');
        redirect($route);

    }else{
        // Fetch the new ID for display
        $id_validation = $this->Mainmodel->id_validation_model();
        $new_id = "";
        $formatted_id = "";
        foreach ($id_validation as $client_id) {
            $new_id = $client_id->client_id + 1;
            $formatted_id = str_pad($new_id, 5, '0', STR_PAD_LEFT);
        }

        if ($this->input->post('addClientSubmit')) {
            $this->form_validation->set_rules('osca_id', 'OSCA ID NO', 'trim|required|callback_osca_id_check');

            if ($this->form_validation->run() == TRUE) {
                $dataAdd = array(
                    'ref_code' => $this->input->post('ref_code'),
                    'osca_id' => $this->input->post('osca_id'),
                    'ncsc_rrn' => $this->input->post('ncsc_rrn'),
                    'last_name' => $this->input->post('last_name'),
                    'first_name' => $this->input->post('first_name'),
                    'middle_name' => $this->input->post('middle_name'),
                    'ext_name' => $this->input->post('ext_name'),
                    'mothers_maiden_name' => $this->input->post('mothers_maiden_name'),
                    'perm_region' => $this->input->post('perm_region'),
                    'perm_prov' => $this->input->post('perm_prov'),
                    'perm_muni' => $this->input->post('perm_muni'),
                    'perm_brgy' => $this->input->post('perm_brgy'),
                    'perm_sitio' => $this->input->post('perm_sitio'),
                    'perm_street' => $this->input->post('perm_street'),
                    'pre_region' => $this->input->post('pre_region'),
                    'pre_prov' => $this->input->post('pre_prov'),
                    'pre_muni' => $this->input->post('pre_muni'),
                    'pre_brgy' => $this->input->post('pre_brgy'),
                    'pre_sitio' => $this->input->post('pre_sitio'),
                    'pre_street' => $this->input->post('pre_street'),
                    'birthdate' => $this->input->post('birthdate'),
                    'age' => $this->input->post('age'),
                    'gender' => $this->input->post('gender'),
                    'civil_status' => $this->input->post('civil_status'),
                    'birth_place' => $this->input->post('birth_place'),
                    'listahanan_y_n' => $this->input->post('listahanan_y_n'),
                    'household_id_no' => $this->input->post('household_id_no'),
                    'ip_y_n' => $this->input->post('ip_y_n'),
                    'ethnicity' => $this->input->post('ethnicity'),
                    'pantawid_y_n' => $this->input->post('pantawid_y_n'),
                    'spouse_fname' => $this->input->post('spouse_fname'),
                    'spouse_mname' => $this->input->post('spouse_mname'),
                    'spouse_lname' => $this->input->post('spouse_lname'),
                    'spouse_ename' => $this->input->post('spouse_ename'),
                    'spouse_contact_num' => $this->input->post('spouse_contact_num'),
                    'spouse_region' => $this->input->post('spouse_region'),
                    'spouse_prov' => $this->input->post('spouse_prov'),
                    'spouse_muni' => $this->input->post('spouse_muni'),
                    'spouse_brgy' => $this->input->post('spouse_brgy'),
                    'spouse_sitio' => $this->input->post('spouse_sitio'),
                    'spouse_street' => $this->input->post('spouse_street'),
                    'house' => $this->input->post('house'),
                    'living_with' => $this->input->post('living_with'),
                    'other_living_with' => $this->input->post('other_living_with'),
                    'pension_source' => $this->input->post('pension_source'),
                    'perm_income_y_n' => $this->input->post('perm_income_y_n'),
                    'perm_income_amount' => $this->input->post('perm_income_amount'),
                    'regular_support' => $this->input->post('regular_support'),
                    'regular_support_amount' => $this->input->post('regular_support_amount'),
                    'total_income_support' => $this->input->post('total_income_support'),
                    'illness' => $this->input->post('illness'),
                    'illness_specify' => $this->input->post('illness_specify'),
                    'disability_y_n' => $this->input->post('disability_y_n'),
                    'disability' => $this->input->post('disability'),
                    'adl_difficulty_y_n' => $this->input->post('adl_difficulty_y_n'),
                    'adl_do_someone_y_n' => $this->input->post('adl_do_someone_y_n'),
                    'exhaustion_exp_y_n' => $this->input->post('exhaustion_exp_y_n'),
                    'assessment' => $this->input->post('assessment'),
                    'recommendation' => $this->input->post('recommendation'),
                    'encoder_name' => $this->input->post('encoder_name'),
                    'date_encoded' => $this->input->post('date_encoded'),
                    'validator_name' => $this->input->post('validator_name'),
                    'date_validate' => $this->input->post('date_validate'),
                    'respondent_name' => $this->input->post('respondent_name'),
                    'date_confirmed' => $this->input->post('date_confirmed'),
                    'encoded_by_user' => $this->session->user_id,
                    'field_office' => $this->session->region_id
                );

                $dataAddxss = $this->security->xss_clean($dataAdd);
                $this->Mainmodel->insert_client_model($dataAddxss);

                $last_id = $this->db->insert_id();

                // INSERT REPRESENTATIVE
                $post = $this->input->post();
                for ($i = 0; $i < count($post['representative_name']); $i++){
                    $data2=array(
                                    'representative_id' => $last_id,
                                    'representative_name' => $post['representative_name'][$i],
                                    'representative_relationship' => $post['representative_relationship'][$i],
                                    'representative_contact_num' => $post['representative_contact_num'][$i]
                                );

                    $dataxss = $this->security->xss_clean($data2);
                                
                }

                if($this->db->insert('tbl_client_representative',$dataxss)){ 
                    echo $this->session->set_flashdata('success', 'Client Successfully Added!'); 
                    redirect('admin/index');
                }else{ 
                    $data['error_msg'] = 'Some problems occured, please try again.'; 

                }

                // INSERT ADDRESS
                $post = $this->input->post();
                for ($i = 0; $i < count($post['representative_name']); $i++){
                    $data2=array(
                                    'id' => $last_id,
                                    'representative_name' => $post['representative_name'][$i],
                                    'representative_relationship' => $post['representative_relationship'][$i],
                                    'representative_contact_num' => $post['representative_contact_num'][$i]
                                );

                    $dataxss = $this->security->xss_clean($data2);
                                
                }

                if($this->db->insert('tbl_client_representative',$dataxss)){ 
                    echo $this->session->set_flashdata('success', 'Client Successfully Added!'); 
                    redirect('admin/index');
                }else{ 
                    $data['error_msg'] = 'Some problems occured, please try again.'; 

                }
            }
        }

        // Load form data for repopulation
        $data['form_data'] = $this->input->post();
        // $data['user_type'] = $user_type;
        $data['new_id'] = $formatted_id;
        $data['id_validation'] = $id_validation;  // Make sure to pass the fetched id validation data to the view
        $data['region'] = $this->Mainmodel->fetch_user_region();

        $this->load->view('template/header');
        $this->load->view('template/nav');
        $this->load->view('admin/add_client', $data);
        $this->load->view('template/footer');
    }

}


// Existing DSWDID check during validation 
public function osca_id_check($str) {
    // Trim the input to handle cases with spaces
    $str = trim($str);
 
    // If the input is empty, validation passes
    if (empty($str)) {
        return TRUE;
    }
 
    $con = array(
        'returnType' => 'count',
        'conditions' => array(
            'osca_id' => $str
        )
    );
 
    // Debugging
    $checkActivityTitle = $this->Mainmodel->getActivityTitle($con);
 
 
    if ($checkActivityTitle > 0) {
        $this->form_validation->set_message('osca_id_check', 'The given OSCA ID is already exists.');
        return FALSE;
    } else {
        return TRUE;
    }
 }

// ==================================================================
// =================== VIEW CLIENT PROFILE ==========================
// ==================================================================
 public function client_profile($client_id)
 {
   if($this->session->userdata('user_type')){
 
    $notif['notification'] = $this->Admin_model->notif_model();    
    $data['view_profile'] = $this->Admin_model->view_profile_model($client_id);
    $data['family_compo'] = $this->Admin_model->fam_compo_data_model($client_id);
    $data['payroll_history'] = $this->Admin_model->payroll_history_model($client_id);
    $data['region_list'] = $this->Admin_model->region_list_model();

    $this->load->view('template/header');
    $this->load->view('template/nav', $notif);
    $this->load->view('admin/client_profile', $data);
    $this->load->view('template/footer');
 
   }else{
     Redirect($route);
   }
 }



} ?>

