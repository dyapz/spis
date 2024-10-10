<?php
class Datamodel extends CI_Model {

    
    // GET GSIS DATA
    public function get_gsis_data($limit, $start, $order, $dir, $search = null, $column_search = []) {
        $this->_get_datatables_query_gsis_data($order, $dir, $search, $column_search);
    
        if ($limit != -1) {
            $this->db->limit($limit, $start);
        }
    
        $query = $this->db->get();
        return $query->result();
    }
    
    // COUNT ALL GSIS DATA
    public function count_all_gsis_data() {
        $this->db->from('lib_gsis');
        return $this->db->count_all_results();
    }
    
    // COUNT FILTERED GSIS DATA
    public function count_filtered_gsis_data($search = null, $column_search = []) {
        $this->_get_datatables_query_gsis_data(null, null, $search, $column_search);
        return $this->db->count_all_results();
    }
    
    // GET DATATABLES QUERY GSIS DATA
    private function _get_datatables_query_gsis_data($order = null, $dir = null, $search = null, $column_search = []) {
        $this->db->select('*');
        $this->db->from('lib_gsis');
    
        $hasColumnSearch = false;
    
        foreach ($column_search as $column => $value) {
            if (!empty($value)) {

                if ($column == 'gsis_pension_status') {
                    if (stripos('active', $value) !== false) {
                        $value = 1;
                    } elseif (stripos('suspended', $value) !== false) {
                        $value = 2;
                    }
                }
    
                if ($column == 'gsis_pension_type') {
                    if (stripos('disability pensioner', $value) !== false) {
                        $value = 1;
                    } elseif (stripos('old age pensioner', $value) !== false) {
                        $value = 2;
                    } elseif (stripos('survivorship pensioner', $value) !== false) {
                        $value = 3;
                    }
                }
    
                if (!$hasColumnSearch) {
                    $this->db->group_start();
                    $hasColumnSearch = true;
                }
                $this->db->like($column, $value);
            }
        }
    
        if ($hasColumnSearch) {
            $this->db->group_end();
        }
    
        if ($search) {
            $this->db->group_start();
            $this->db->like('gsis_fname', $search);
            $this->db->or_like('gsis_mname', $search);
            $this->db->or_like('gsis_lname', $search);
            $this->db->or_like('gsis_ename', $search);
            $this->db->or_like('gsis_birthdate', $search);

            if (stripos('active', $search) !== false) {
                $this->db->or_like('gsis_pension_status', 1);
            } elseif (stripos('suspended', $search) !== false) {
                $this->db->or_like('gsis_pension_status', 0);
            }

            if (stripos('disability pensioner', $search) !== false) {
                $this->db->or_like('gsis_pension_type', 1);
            } elseif (stripos('old age pensioner', $search) !== false) {
                $this->db->or_like('gsis_pension_type', 2);
            } elseif (stripos('survivorship pensioner', $search) !== false) {
                $this->db->or_like('gsis_pension_type', 3);
            }
            $this->db->group_end();
        }
    
        if ($order && $dir) {
            $this->db->order_by($order, $dir);
        }
    }
    
    
    
    // GET PVAO DATA
    public function get_pvao_data($limit, $start, $order, $dir, $search = null, $column_search = []) {
        $this->_get_datatables_query_pvao_data($order, $dir, $search, $column_search);
    
        if ($limit != -1) {
            $this->db->limit($limit, $start);
        }
    
        $query = $this->db->get();
        return $query->result();
    }
    
    // COUNT ALL PVAO DATA
    public function count_all_pvao_data() {
        $this->db->from('lib_pvao');
        return $this->db->count_all_results();
    }
    
    // COUNT FILTERED PVAO DATA
    public function count_filtered_pvao_data($search = null, $column_search = []) {
        $this->_get_datatables_query_pvao_data(null, null, $search, $column_search);
        return $this->db->count_all_results();
    }
    
    // GET DATATABLES QUERY PVAO DATA
    private function _get_datatables_query_pvao_data($order = null, $dir = null, $search = null, $column_search = []) {
        $this->db->select('*');
        $this->db->from('lib_pvao');
    
        $hasColumnSearch = false;
    
        foreach ($column_search as $column => $value) {
            if (!empty($value)) {
                if (!$hasColumnSearch) {
                    $this->db->group_start();
                    $hasColumnSearch = true;
                }
                $this->db->like($column, $value);
            }
        }
    
        if ($hasColumnSearch) {
            $this->db->group_end();
        }
    
        if ($search) {
            $this->db->group_start();
            $this->db->like('pvao_fname', $search);
            $this->db->or_like('pvao_mname', $search);
            $this->db->or_like('pvao_lname', $search);
            $this->db->or_like('pvao_ename', $search);
            $this->db->or_like('pvao_birthdate', $search);
            $this->db->group_end();
        }
    
        if ($order && $dir) {
            $this->db->order_by($order, $dir);
        }
    }


}

?>