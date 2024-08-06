<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Addressmodel extends CI_Model 
{
    function __construct() {
        parent::__construct();  
        
    }


    public function insert_audit($dataaudit){
        $this->db->insert('tbl_audit', $dataaudit); 
        return true;
        
    }
    
    function select_region(){
      $this->db->order_by("region_id", "ASC");
      $query = $this->db->get("tbl_region");
      return $query->result();
    }
}