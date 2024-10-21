<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientmodel extends CI_Model 
{
    function __construct() {
        parent::__construct();  
        
    }


  // READ
  public function get_client_for_validation(){
    $this->db->select('*'); 
    $this->db->from('tbl_client');
    $this->db->join('lib_barangay', 'tbl_client.pre_brgy = lib_barangay.brgy_id', 'left');
    $this->db->join('lib_municipality', 'tbl_client.pre_muni = lib_municipality.city_id', 'left');
    $this->db->join('lib_province', 'tbl_client.pre_prov = lib_province.province_id', 'left');
    $this->db->join('lib_region', 'tbl_client.pre_region = lib_region.region_id', 'left');
    $this->db->where('validation_id', $this->session->userdata('user_id'));
    $this->db->where('perm_region', $this->session->userdata('region_id'));
    $this->db->where('client_status', 5);
    $query = $this->db->get();
    return $query->result();
  }


  public function get_approval_data_list($limit, $start, $order, $dir, $search = null, $column_search = []) {
    $this->_get_datatables_query_approval_data_list($order, $dir, $search, $column_search);

    if ($limit != -1) {
        $this->db->limit($limit, $start);
    }

    $query = $this->db->get();
    return $query->result();
  }

  public function count_all_approval_data_list() {
    $this->db->from('tbl_client');
    return $this->db->count_all_results();
  }

  public function count_filtered_approval_data_list($search = null, $column_search = []) {
    $this->_get_datatables_query_approval_data_list(null, null, $search, $column_search);
    return $this->db->count_all_results();
  }

  private function _get_datatables_query_approval_data_list($order = null, $dir = null, $search = null, $column_search = []) {
    $this->db->select('*');
    $this->db->from('tbl_client');
    $this->db->join('lib_barangay', 'tbl_client.pre_brgy = lib_barangay.brgy_id', 'left');
    $this->db->join('lib_municipality', 'tbl_client.pre_muni = lib_municipality.city_id', 'left');
    $this->db->join('lib_province', 'tbl_client.pre_prov = lib_province.province_id', 'left');
    $this->db->join('lib_region', 'tbl_client.pre_region = lib_region.region_id', 'left');
    $this->db->join('tbl_client_recommendation', 'tbl_client.client_id = tbl_client_recommendation.client_id', 'left');
    $this->db->join('tbl_users', 'tbl_users.user_id = tbl_client.validation_id', 'left');
    $this->db->where('perm_region', $this->session->userdata('region_id'));
    $this->db->where('client_status !=', 5);
    $this->db->where('cr_conformed_by', 0);
    

    $hasColumnSearch = false;

    foreach ($column_search as $column => $value) {
        if (!empty($value)) {

            if ($column == 'client_status') {
                if (stripos('Active', $value) !== false) {
                    $value = 1;
                } elseif (stripos('Waitlisted', $value) !== false) {
                    $value = 2;
                } elseif (stripos('Delisted', $value) !== false) {
                    $value = 3;
                } elseif (stripos('Not Eligible', $value) !== false) {
                  $value = 4;
                } elseif (stripos('For Validation', $value) !== false) {
                  $value = 5;
              }
            }

            if ($column == 'sub_client_status') {
              if (stripos('Eligible', $value) !== false) {
                    $value = 1;
                } elseif (stripos('Not Eligible', $value) !== false) {
                    $value = 2;
                } elseif (stripos('GSIS Match Found', $value) !== false) {
                    $value = 3;
                } elseif (stripos('PVAO Match Found', $value) !== false) {
                  $value = 4;
                } elseif (stripos('Duplicate', $value) !== false) {
                  $value = 5;
                } elseif (stripos('Underage', $value) !== false) {
                  $value = 6;
                } elseif (stripos('Pensioner', $value) !== false) {
                  $value = 7;
                } elseif (stripos('Supported', $value) !== false) {
                  $value = 8;
                } elseif (stripos('Change Status ', $value) !== false) {
                  $value = 9;
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
        $this->db->like('ref_code', $search);
        $this->db->or_like('client_fname', $search);
        $this->db->or_like('client_mname', $search);
        $this->db->or_like('client_lname', $search);
        $this->db->or_like('client_ename', $search);
        $this->db->or_like('birthdate', $search);
        $this->db->or_like('user_fname', $search);


        if (stripos('Active', $search) !== false) {
            $this->db->or_like('client_status', 1);
        } elseif (stripos('Waitlisted', $search) !== false) {
              $this->db->or_like('client_status', 2);
        } elseif (stripos('Delisted', $search) !== false) {
              $this->db->or_like('client_status', 3);
        } elseif (stripos('Not Eligible', $search) !== false) {
            $this->db->or_like('client_status', 4);
        } elseif (stripos('For Validation', $search) !== false) {
            $this->db->or_like('client_status', 5);
        }


      if (stripos('Eligible', $search) !== false) {
        $this->db->or_like('sub_client_status', 1);
      } elseif (stripos('Not Eligible', $search) !== false) {
            $this->db->or_like('sub_client_status', 2);
      } elseif (stripos('GSIS Match Found', $search) !== false) {
            $this->db->or_like('sub_client_status', 3);
      }  elseif (stripos('PVAO Match Found', $search) !== false) {
        $this->db->or_like('sub_client_status', 4);
      } elseif (stripos('Duplicate', $search) !== false) {
        $this->db->or_like('sub_client_status', 5);
      } elseif (stripos('Underage', $search) !== false) {
        $this->db->or_like('sub_client_status', 6);
      } elseif (stripos('Pensioner', $search) !== false) {
        $this->db->or_like('sub_client_status', 7);
      } elseif (stripos('Supported', $search) !== false) {
        $this->db->or_like('sub_client_status', 8);
      } elseif (stripos('Change Status ', $search) !== false) {
        $this->db->or_like('sub_client_status', 9);
      }
        $this->db->group_end();
    }

    if ($order && $dir) {
        $this->db->order_by($order, $dir);
    }
  }



  // GET CLIENT DATA LIST
  public function get_client_data_list($limit, $start, $order, $dir, $search = null, $column_search = []) {
    $this->_get_datatables_query_client_data_list($order, $dir, $search, $column_search);

    if ($limit != -1) {
        $this->db->limit($limit, $start);
    }

    $query = $this->db->get();
    return $query->result();
  }

  public function count_all_client_data_list() {
    $this->db->from('tbl_client');
    return $this->db->count_all_results();
  }

  public function count_filtered_client_data_list($search = null, $column_search = []) {
    $this->_get_datatables_query_client_data_list(null, null, $search, $column_search);
    return $this->db->count_all_results();
  }

  private function _get_datatables_query_client_data_list($order = null, $dir = null, $search = null, $column_search = []) {
    $this->db->select('*');
    $this->db->from('tbl_client');
    $this->db->join('lib_barangay', 'tbl_client.pre_brgy = lib_barangay.brgy_id', 'left');
    $this->db->join('lib_municipality', 'tbl_client.pre_muni = lib_municipality.city_id', 'left');
    $this->db->join('lib_province', 'tbl_client.pre_prov = lib_province.province_id', 'left');
    $this->db->join('lib_region', 'tbl_client.pre_region = lib_region.region_id', 'left');
    $this->db->join('tbl_client_recommendation', 'tbl_client.client_id = tbl_client_recommendation.client_id', 'left');
    $this->db->join('tbl_users', 'tbl_users.user_id = tbl_client.validation_id', 'left');
    $this->db->where('perm_region', $this->session->userdata('region_id'));
    $this->db->where('client_status !=', 5);
    $this->db->where('cr_conformed_by !=', 0);
    

    $hasColumnSearch = false;

    foreach ($column_search as $column => $value) {
        if (!empty($value)) {

            if ($column == 'client_status') {
                if (stripos('Active', $value) !== false) {
                    $value = 1;
                } elseif (stripos('Waitlisted', $value) !== false) {
                    $value = 2;
                } elseif (stripos('Delisted', $value) !== false) {
                    $value = 3;
                } elseif (stripos('Not Eligible', $value) !== false) {
                  $value = 4;
                } elseif (stripos('For Validation', $value) !== false) {
                  $value = 5;
              }
            }

            if ($column == 'sub_client_status') {
              if (stripos('Eligible', $value) !== false) {
                    $value = 1;
                } elseif (stripos('Not Eligible', $value) !== false) {
                    $value = 2;
                } elseif (stripos('GSIS Match Found', $value) !== false) {
                    $value = 3;
                } elseif (stripos('PVAO Match Found', $value) !== false) {
                  $value = 4;
                } elseif (stripos('Duplicate', $value) !== false) {
                  $value = 5;
                } elseif (stripos('Underage', $value) !== false) {
                  $value = 6;
                } elseif (stripos('Pensioner', $value) !== false) {
                  $value = 7;
                } elseif (stripos('Supported', $value) !== false) {
                  $value = 8;
                } elseif (stripos('Change Status ', $value) !== false) {
                  $value = 9;
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
        $this->db->like('ref_code', $search);
        $this->db->or_like('client_fname', $search);
        $this->db->or_like('client_mname', $search);
        $this->db->or_like('client_lname', $search);
        $this->db->or_like('client_ename', $search);
        $this->db->or_like('birthdate', $search);
        $this->db->or_like('user_fname', $search);


        if (stripos('Active', $search) !== false) {
            $this->db->or_like('client_status', 1);
        } elseif (stripos('Waitlisted', $search) !== false) {
              $this->db->or_like('client_status', 2);
        } elseif (stripos('Delisted', $search) !== false) {
              $this->db->or_like('client_status', 3);
        } elseif (stripos('Not Eligible', $search) !== false) {
            $this->db->or_like('client_status', 4);
        } elseif (stripos('For Validation', $search) !== false) {
            $this->db->or_like('client_status', 5);
        }


      if (stripos('Eligible', $search) !== false) {
        $this->db->or_like('sub_client_status', 1);
      } elseif (stripos('Not Eligible', $search) !== false) {
            $this->db->or_like('sub_client_status', 2);
      } elseif (stripos('GSIS Match Found', $search) !== false) {
            $this->db->or_like('sub_client_status', 3);
      }  elseif (stripos('PVAO Match Found', $search) !== false) {
        $this->db->or_like('sub_client_status', 4);
      } elseif (stripos('Duplicate', $search) !== false) {
        $this->db->or_like('sub_client_status', 5);
      } elseif (stripos('Underage', $search) !== false) {
        $this->db->or_like('sub_client_status', 6);
      } elseif (stripos('Pensioner', $search) !== false) {
        $this->db->or_like('sub_client_status', 7);
      } elseif (stripos('Supported', $search) !== false) {
        $this->db->or_like('sub_client_status', 8);
      } elseif (stripos('Change Status ', $search) !== false) {
        $this->db->or_like('sub_client_status', 9);
      }
        $this->db->group_end();
    }

    if ($order && $dir) {
        $this->db->order_by($order, $dir);
    }
  }



  // UPDATE
  public function get_all_clients() {
    $this->db->select('client_id'); 
    $this->db->from('tbl_client');
    $query = $this->db->get();
    
    return $query->result();
  }


  // get client info
  public function client_information_model($client_id) {
    // Get the client_id by comparing the hashed value
      $this->db->select('tbl_client.*, tbl_client_spouse.*, tbl_client_eligibility_assessment.*, tbl_client_recommendation.*,  lib_province_perm.province_name, lib_municipality_perm.city_name, lib_barangay_perm.brgy_name, lib_province_pre.province_name AS pre_province_name, lib_municipality_pre.city_name AS pre_city_name, lib_barangay_pre.brgy_name AS pre_brgy_name, tbl_users_validated.user_fname, tbl_users_validated.user_mname, tbl_users_validated.user_lname, tbl_users_validated.user_ename, tbl_users_validated.user_designation, tbl_users_conformed.user_fname AS conformed_fname, tbl_users_conformed.user_mname AS conformed_mname, tbl_users_conformed.user_lname AS conformed_lname, tbl_users_conformed.user_ename AS conformed_ename, tbl_users_conformed.user_designation AS conformed_designation ');
      $this->db->from('tbl_client');
      
      // Joins for Permanent Address
      $this->db->join('lib_province AS lib_province_perm', 'tbl_client.perm_prov = lib_province_perm.province_id', 'left');
      $this->db->join('lib_municipality AS lib_municipality_perm', 'tbl_client.perm_muni = lib_municipality_perm.city_id', 'left');
      $this->db->join('lib_barangay AS lib_barangay_perm', 'tbl_client.perm_brgy = lib_barangay_perm.brgy_id', 'left');
      
      // Joins for Present Address
      $this->db->join('lib_province AS lib_province_pre', 'tbl_client.pre_prov = lib_province_pre.province_id', 'left');
      $this->db->join('lib_municipality AS lib_municipality_pre', 'tbl_client.pre_muni = lib_municipality_pre.city_id', 'left');
      $this->db->join('lib_barangay AS lib_barangay_pre', 'tbl_client.pre_brgy = lib_barangay_pre.brgy_id', 'left');
      
    // Join for spouse, eligibility assessment, recommendation, and users
    $this->db->join('tbl_client_recommendation', 'tbl_client.client_id = tbl_client_recommendation.client_id', 'left');
    $this->db->join('tbl_client_eligibility_assessment', 'tbl_client.client_id = tbl_client_eligibility_assessment.client_id', 'left');
    $this->db->join('tbl_client_spouse', 'tbl_client.client_id = tbl_client_spouse.client_id', 'left');




    $this->db->join('tbl_users AS tbl_users_validated', 'tbl_client_recommendation.cr_validated_by = tbl_users_validated.user_id', 'left');
    $this->db->join('tbl_users AS tbl_users_conformed', 'tbl_client_recommendation.cr_conformed_by = tbl_users_conformed.user_id', 'left');
      
      // Where condition to match the hashed client_id
      $this->db->where('tbl_client.client_id', $client_id);
      
      $result = $this->db->get();
      if ($result->num_rows() > 0) {
        return $result->result();

      }

    return false; 
  }


  // get ghousehold members
  public function get_household_members($client_id) {
        $this->db->select('*');
        $this->db->from('tbl_client_household_members');
        $this->db->where('client_id', $client_id); 
        $query = $this->db->get();
        return $query->result_array();  
  }


  // get authorized representatives
  public function get_authorized_representatives($client_id) {
        $this->db->select('*');
        $this->db->from('tbl_client_authorized_representatives');
        $this->db->where('client_id', $client_id); 
        $query = $this->db->get();
        return $query->result_array();  
  }
  
  // uodate intentifying information
  public function update_client_status_model($client_statusxss){
    $this->db->set($client_statusxss);
    $this->db->where('client_id', $client_statusxss['client_id']);
    $this->db->update('tbl_client');

    return true;
  }

  // eligibility assessment
  public function save_client_eligibility_assessment_model($eligibility_assessmentxss){
    $this->db->insert('tbl_client_eligibility_assessment', $eligibility_assessmentxss);
    return true;
  }

  // client recommendation
  public function save_client_recommendation_model($recommendationxss){
    $this->db->insert('tbl_client_recommendation', $recommendationxss);
    return true;
  }


  public function update_conform_model($dataconformxss){
    $this->db->set($dataconformxss);
    $this->db->where('client_id', $dataconformxss['client_id']);
    $this->db->update('tbl_client_recommendation');
    return true;
  }



}?>