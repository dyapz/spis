<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Addressmodel extends CI_Model 
{
    function __construct() {
        parent::__construct();  
        
    }


    // REGION
    function select_region(){
      $this->db->order_by("region_id", "ASC");
      $query = $this->db->get("lib_region");
      return $query->result();
    }

    // PROVINCE
    public function select_province($region_id) {
        $this->db->where('region_id', $region_id);
        $this->db->order_by('province_id', 'ASC');
        $query = $this->db->get('lib_province');
        $output = '<option value="" disabled selected></option>';
        foreach($query->result() as $row) {
          $output .= '<option value="'.$row->province_id.'">'.$row->province_name.'</option>';
        }
        return $output;
    }


    public function select_municipality($province_id) {
      $this->db->where('province_id', $province_id);
      $this->db->order_by('city_id', 'ASC');
      $query = $this->db->get('lib_municipality');
      $output = '<option value="" disabled selected></option>';
      foreach($query->result() as $row) {
        $output .= '<option value="'.$row->city_id.'">'.$row->city_name.'</option>';
      }
      return $output;
    }

    public function select_barangay($city_id) {
      $this->db->where('city_id', $city_id);
      $this->db->order_by('brgy_id', 'ASC');
      $query = $this->db->get('lib_barangay');
      $output = '<option value="" disabled selected></option>';
      foreach($query->result() as $row) {
        $output .= '<option value="'.$row->brgy_id.'">'.$row->brgy_name.'</option>';
      }
      return $output;
    }
}