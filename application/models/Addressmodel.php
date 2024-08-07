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
        $output = '<option value="" disabled selected>Select Province</option>';
        foreach($query->result() as $row) {
          $output .= '<option value="'.$row->province_id.'">'.$row->province_name.'</option>';
        }
        return $output;
      }
}