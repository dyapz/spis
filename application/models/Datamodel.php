<?php
class Datamodel extends CI_Model {

    
    // USER LIST DATA
    public function get_gsis_data($limit, $start, $order, $dir, $search = null) {
        $this->_get_datatables_query_gsis_data($order, $dir, $search);
    
        if ($limit != -1) {
            $this->db->limit($limit, $start);
        }
    
        $query = $this->db->get();
        return $query->result();
    }
    
    public function count_all_gsis_data() {
        $this->db->from('lib_gsis');
        return $this->db->count_all_results();
    }
    
    public function count_filtered_gsis_data($search = null) {
        $this->_get_datatables_query_gsis_data(null, null, $search);
        return $this->db->count_all_results();
    }
    
    private function _get_datatables_query_gsis_data($order = null, $dir = null, $search = null) {
        $this->db->select('gsis_fname, gsis_mname, gsis_lname, gsis_ename');
        $this->db->from('lib_gsis');
    
        if ($search) {
            $this->db->group_start();
            $this->db->like('gsis_fname', $search);
            $this->db->or_like('gsis_mname', $search);
            $this->db->or_like('gsis_lname', $search);
            $this->db->or_like('gsis_ename', $search);
            $this->db->group_end();
        }
    
        if ($order && $dir) {
            $this->db->order_by($order, $dir);
        }
    }
    
    
    
    











}

?>