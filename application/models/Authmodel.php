<?php
class Authmodel extends CI_Model {


// USERNAME AND PASSWORD IF EXISTING VALIDATION 
public $username;
public $password;

public function validate($username,$password){
        $this->db->where('username',$username);
        $this->db->where('password',$password);
        $result = $this->db->get('tbl_users', 1);

        return $result;
      }


    // EXISTING USERNAME CHECK DURING VALIDATION 
    function getUsername($params = array()){ 
        $this->db->select('*'); 
        $this->db->from('tbl_users'); 
            
        if(array_key_exists("conditions", $params)){ 
        foreach($params['conditions'] as $key => $val){ 
            $this->db->where($key, $val); 
        } 
        } 
            
        if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){ 
            $result = $this->db->count_all_results(); 
        }else{ 
        if(array_key_exists("username", $params) || $params['returnType'] == 'single'){ 
            if(!empty($params['username'])){ 
                $this->db->where('username', $params['username']); 
            } 
            $query = $this->db->get(); 
            $result = $query->row_array(); 
        }else{ 
            $this->db->order_by('username', 'desc'); 
            if(array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                $this->db->limit($params['limit'],$params['start']); 
            }elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params)){ 
                $this->db->limit($params['limit']); 
            } 
                
            $query = $this->db->get(); 
            $result = ($query->num_rows() > 0)?$query->result_array():FALSE; 
        } 
        } 
        return $result; 
    } 


    // EXISTING EMAIL CHECK DURING VALIDATION 
    function getEmail($params = array()){ 
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
        if(array_key_exists("user_email", $params) || $params['returnType'] == 'single'){ 
            if(!empty($params['user_email'])){ 
                $this->db->where('user_email', $params['user_email']); 
            } 
            $query = $this->db->get(); 
            $result = $query->row_array(); 
        } else { 
            $this->db->order_by('user_email', 'desc'); 
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

    //REGISTRATION
    function register_model($dataxss){
        $insert = $this->db->insert('tbl_users', $dataxss); 
        return true;
    }

    //FORGOT PASSWORD CHECK EMAIL IF EXIST
    public function emailexists_forgotpassword_model($user_email){
       $this->db->select('user_email');
       $this->db->from('tbl_users'); 
       $this->db->where('user_email', $user_email); 
       $this->db->where('user_type!=', '0'); 
       $query=$this->db->get();
       return $query->row_array();
    }

    //FORGOT PASSWORD SEND OTP
    public function sendemail_forgotpassword_model($data){
        $recaptchaResponse = trim($this->input->post('g-recaptcha-response'));
     
        $userIp=$this->input->ip_address();
     
        $secret = $this->config->item('google_secret');
    
        $url="https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$recaptchaResponse."&remoteip=".$userIp;
    
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $output = curl_exec($ch); 
        curl_close($ch);      
         
        $status= json_decode($output, true);
    
    
            $user_email = $data['user_email'];
            $query1=$this->db->query("SELECT * from tbl_users where user_email = '".$user_email."' ");
            $row=$query1->result_array();
            if($query1->num_rows()>0 && $status['success']){
        
            $generatenewotp = "";
            $generatenewotp  = rand(100000, 999999);
            $generatenewotpxss = $this->security->xss_clean($generatenewotp);
            $newotp['user_otp'] = $generatenewotpxss;
            $this->db->where('user_email', $user_email);
            $this->db->update('tbl_users', $newotp); 


            $hashed_id = md5($row[0]['user_id']);
        
            $mail_message='Dear '.$row[0]['user_fname'].','. "\r\n";
            $mail_message.='<br><br>Verification Code: <b>'.$generatenewotp.'</b>'."\r\n";
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
            $this->email->subject('Reset Password');
            $this->email->message($mail_message);
    
            if ($this->email->send() && $status['success']) {
                redirect('auth/otp_verification/'.$hashed_id);        
            } else {
                $this->session->set_flashdata('error','Failed to send password, please try again!');
                redirect('auth');        
            }
        }else{  
            $this->session->set_flashdata('error','Sorry Google Recaptcha Unsuccessful!!');
            redirect('auth');
        }
    }

    // OTP VERIFICATION
    public function otp_verification_model($hashed_id) {
        $this->db->select('user_id');
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

    // OTP VERIFICATION VALID
    public function otp_verify_model($user_id) {
        $this->db->select('*');
        $this->db->from('tbl_users'); 
        $this->db->where('user_id', $user_id); 
        $query = $this->db->get();
        return $query->row_array();
    }

    // OTP EXPIRY CHECK
    public function otp_verify_expired_model($user_id) {
        $this->db->select('user_id');
        $this->db->from('tbl_users');
        $this->db->where('user_id', $user_id);
        $this->db->where('user_timestamp <=', 'DATE_SUB(NOW(), INTERVAL 5 MINUTE)', FALSE);
        $query = $this->db->get();
        return $query->row_array();
    }


	// CHANGE PASSWORD DURING FORGOT PASSWORD
    public function new_password_model($hashed_id, $hashed_otp_id) {
        $this->db->select('user_id, user_otp');
        $users = $this->db->get('tbl_users')->result();
        
        foreach ($users as $user) {
            if (md5($user->user_id) == $hashed_id && md5($user->user_otp) == $hashed_otp_id) {
                $this->db->where('user_id', $user->user_id);
                $result = $this->db->get('tbl_users');
                
                if ($result->num_rows() > 0) {
                    return $result->result(); 
                }
            }
        }
    
        return false; 
    }
    

	// UPDATE PASSWORD DURING FORGOT PASSWORD
    public function update_new_password_model($dataxss){
        $this->db->set($dataxss);
        $this->db->where('user_id', $dataxss['user_id']);
        $this->db->update('tbl_users');
    
        return true;
    }
    

    //RE-SEND OTP
    public function resend_otp_model($user_email){

            $query1=$this->db->query("SELECT * from tbl_users where user_email = '".$user_email."' ");
            $row=$query1->result_array();
            if($query1->num_rows()>0){
        
            $generatenewotp = "";
            $generatenewotp  = rand(100000, 999999);
            $generatenewotpxss = $this->security->xss_clean($generatenewotp);
            $newotp['user_otp'] = $generatenewotpxss;
            $this->db->where('user_email', $user_email);
            $this->db->update('tbl_users', $newotp); 


            $hashed_id = md5($row[0]['user_id']);
        
            $mail_message='Dear '.$row[0]['user_fname'].','. "\r\n";
            $mail_message.='<br><br>Verification Code: <b>'.$generatenewotp.'</b>'."\r\n";
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
            $this->email->subject('Reset Password');
            $this->email->message($mail_message);
    
            if ($this->email->send()) {
                redirect('auth/otp_verification/'.$hashed_id);        
            } else {
                $this->session->set_flashdata('error','Failed to send OTP, please try again!');
                redirect('auth');        
            }
        }
    }

}

?>