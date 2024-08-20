<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mainmodel extends CI_Model 
{
    function __construct() {
        parent::__construct();  
        
    }


// Notification for New User
public function notif_model(){ 
  $this->db->select ( '*' ); 
  $this->db->from ('tbl_users');
  $this->db->where ('status', '1');
  $query = $this->db->get();
  return $query->result();
}

// Insert Client Data
public function insert_client_model($dataAddxss){
    $this->db->insert('tbl_client', $dataAddxss); 
    return true;
  }

// Client Data Table
function client_table_model(){
  $this->db->select('*'); 
  $this->db->from('tbl_client');
  $this->db->join('lib_region', 'lib_region.region_id = tbl_client.field_office', 'left');
  $query = $this->db->get();    
  return $query->result();
}

//Payroll History
public function payroll_history_model($payroll_history_id){ 
  $this->db->select ( '*' ); 
  $this->db->from ('tbl_payroll_history');
  $this->db->where_in('client_id', $client_id);
  $query = $this->db->get();    
  if($query->num_rows() > 0)
  return $query->result();

}


















 //============= User Region List
 function fetch_user_region()
 {
  $this->db->order_by("region_name", "ASC");
  $query = $this->db->get("lib_region");
  return $query->result();
 }

 function id_validation_model(){
  $this->db->select_max( 'client_id' ); 
  $this->db->from  ('tbl_client');
  $query = $this->db->get();    
  return $query->result();
}

function fetch_province_model($region_id){
  $this->db->where('region_id', $region_id);
  $this->db->order_by('province_name', 'ASC');    
    $query = $this->db->get('lib_province');
    $output = '<option value="">Select province</option>';
    $counter = 1;
  foreach($query->result() as $row)
  {
      $output .= '<option value="'.$row->province_id.'">'.$counter.' - '.$row->province_name.'</option>';
      $counter++;
  }
  return $output;
}

public function fetch_city_model($province_id){
  $this->db->where('province_id', $province_id);
  $this->db->order_by('city_name', 'ASC');
  $query = $this->db->get('lib_municipality');
  $output = '<option value="">Select Municipality</option>';
  $counter = 1;
  foreach($query->result() as $row)
  {
      $output .= '<option value="'.$row->city_id.'">'.$counter.' - '.$row->city_name.'</option>';
      $counter++;
  }
  return $output;
}

public function fetch_brgy_model($city_id){
  $this->db->where('city_id', $city_id);
  $this->db->order_by('brgy_name', 'ASC');
  $query = $this->db->get('lib_barangay');
  $output = '<option value="">Select Barangay</option>';
  $counter = 1;
  foreach($query->result() as $row)
  {
      $output .= '<option value="'.$row->brgy_id.'">'.$counter.' - '.$row->brgy_name.'</option>';
      $counter++;
  }
  return $output;
}


public function getActivityTitle($params = array()){ 
  $this->db->select('*'); 
  $this->db->from('tbl_client'); 
   
  if(array_key_exists("conditions", $params)){ 
      foreach($params['conditions'] as $key => $val){ 
          $this->db->where($key, $val); 
      } 
  } 
   
  if(array_key_exists("returnType",$params) && $params['returnType'] == 'count'){ 
      $result = $this->db->count_all_results(); 
  }else{ 
      if(array_key_exists("osca_id", $params) || $params['returnType'] == 'single'){ 
          if(!empty($params['osca_id'])){ 
              $this->db->where('osca_id', $params['osca_id']); 
          } 
          $query = $this->db->get(); 
          $result = $query->row_array(); 
      }else{ 
          $this->db->order_by('osca_id', 'desc'); 
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

}?>