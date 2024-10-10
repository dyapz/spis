<?php
class Accountmodel extends CI_Model {

    // USER DATA
    public function user_data_model($hashed_id) {
        $this->db->select('*');
        $users = $this->db->get('tbl_users')->result();
        
        foreach ($users as $user) {
            if (md5($user->user_id) == $hashed_id) {
                $this->db->where('user_id', $user->user_id);
                $result = $this->db->get('tbl_users');
                
                if ($result->num_rows() > 0) {
                    return $result->result(); 
                }
            }
        }
    
        return false; 
    }



    // PASSWORD DATA
    public function password_data_model($id) {
        $this->db->where('user_id', $id);
        return $this->db->get('tbl_users')->row();
      }
    
	// UPDATE PROFILE
    public function update_profile_model($dataxss) {
        $this->db->set($dataxss);
        $this->db->where('user_id', $dataxss['user_id']);
        $this->db->update('tbl_users');
        return true;
    }



    // GET CURRENT PASSWORD
    function getPassword($params = array()){ 
        $this->db->select('*'); 
        $this->db->from('tbl_users'); 

        if(array_key_exists("conditions", $params)){ 
        foreach($params['conditions'] as $key => $val){ 
            $this->db->where($key, $val); 
        } 
        } 

        if(array_key_exists("returnType", $params) && $params['returnType'] == 'count'){ 
            $result = $this->db->count_all_results(); 
        } else { 
        if(array_key_exists("password", $params) || $params['returnType'] == 'single'){ 
            if(!empty($params['password'])){ 
                $this->db->where('password', $params['password']); 
            } 
            $query = $this->db->get(); 
            $result = $query->row_array(); 
        } else { 
            $this->db->order_by('password', 'desc'); 
            if(array_key_exists("start", $params) && array_key_exists("limit", $params)){ 
                $this->db->limit($params['limit'], $params['start']); 
            } elseif(!array_key_exists("start", $params) && array_key_exists("limit", $params)){ 
                $this->db->limit($params['limit']); 
            } 
            $query = $this->db->get(); 
            $result = ($query->num_rows() > 0) ? $query->result_array() : FALSE; 
        } 
        } 
        return $result; 
    } 


    // NEW REGISTERED NOTIFICATION
    // public function new_registered_notif_model() {
    //     $this->db->from('tbl_users');
    //     $this->db->where('user_active', 0);
    //     return $this->db->count_all_results();
    // }

    
    // USER LIST DATA
    public function get_user_list_data(){
        $this->_get_datatables_query_user_list_data();
        $query = $this->db->get();
        return $query->result();
    }
    
    public function count_all_user_list_data(){
        $this->db->from('tbl_users');
        return $this->db->count_all_results();
    }


    private function _get_datatables_query_user_list_data() {
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->join('lib_region', 'lib_region.region_id = tbl_users.region_id', 'left');
        $this->db->join('lib_province', 'lib_province.province_id = tbl_users.province_id', 'left');
    }


	// USER DEACTIVATE
    public function deactivate_model($user_id) {
        $this->db->set('user_active', 0);
        $this->db->where('user_id', $user_id);
        $this->db->update('tbl_users');
        return true;
    }

	// USER ACTIVATE
    public function activate_model($user_id) {
        $this->db->set('user_active', 1);
        $this->db->where('user_id', $user_id);
        $this->db->update('tbl_users');
        return true;
    }

    public function get_user_email($user_id) {
        $this->db->where('user_id', $user_id);
        return $this->db->get('tbl_users')->row();
      }

	// USER ACTIVATE RECEIVE EMAIL
    public function activate_email_model($user_email){

        $query1=$this->db->query("SELECT * from tbl_users where user_email = '".$user_email."' ");
        $row=$query1->result_array();
        if($query1->num_rows()>0){
    
        $mail_message='Dear '.$row[0]['user_fname'].','. "\r\n";
        $mail_message.='<br><br>We are pleased to inform you that your account has been reviewed and successfully activated. You can now log in using your credentials and access all the features and services available.'."\r\n";
        $mail_message.='<br><br>Should you have any questions or encounter any issues while logging in or using our platform, please feel free to reach out to our team at __________________.';
        $mail_message.='<br><br>Thanks & Regards';
        $mail_message.='<br><br>SocPen Team';  
    
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_port'] = '465';
        $config['smtp_user'] = 'idbs.spt.tm@gmail.com'; 
        $config['smtp_pass'] = 'tqfakdwakoqvtjxb'; 
        $config['mailtype'] = 'html'; 
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE; 
        $config['newline'] = "\r\n"; 

        $this->load->library('email', $config);
        $this->email->initialize($config);                        
        $this->email->from('idbs.spt.tm@gmail.com', 'NOREPLY');
        $this->email->to($user_email);
        $this->email->subject('SPIS ACTIVATION OF ACCOUNT');
        $this->email->message($mail_message);

        if ($this->email->send()) {
        } 

    }
}


	// USER DELETE
    public function delete_model($user_id){
        $this->db->where('user_id', $user_id);
        $this->db->delete('tbl_users');
        return true;
    }

}

?>