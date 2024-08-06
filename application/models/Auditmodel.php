<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auditmodel extends CI_Model 
{
    function __construct() {
        parent::__construct();  
        
    }


    public function insert_audit($dataaudit){
        $this->db->insert('tbl_audit', $dataaudit); 
        return true;
        
    }
    
}