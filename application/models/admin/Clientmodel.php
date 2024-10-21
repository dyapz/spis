<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientmodel extends CI_Model 
{
    function __construct() {
        parent::__construct();  
        
    }


  // CHECK MATCHES/DUPLICATE
  public function check_for_matches($data) {
    $fname = trim($data['client_fname']);
    $lname = trim($data['client_lname']);
    $birthdate = trim($data['birthdate']);

    // Check for exact matches
    $exact_match_gsis = $this->check_exact_match_in_table('lib_gsis', 'gsis_fname', 'gsis_lname', 'gsis_birthdate', $fname, $lname, $birthdate);
    $exact_match_pvao = $this->check_exact_match_in_table('lib_pvao', 'pvao_fname', 'pvao_lname', 'pvao_birthdate', $fname, $lname, $birthdate);
    $duplicate_client = $this->check_exact_match_in_table('tbl_client', 'client_fname', 'client_lname', 'birthdate', $fname, $lname, $birthdate);

    if ($exact_match_gsis) {
        return ['status' => 'gsis_match'];

    } elseif ($exact_match_pvao) {
        return ['status' => 'pvao_match'];

    } elseif ($duplicate_client) {
        return ['status' => 'duplicate'];
    }

    // Check for possible matches
    // $possible_match_gsis = $this->check_possible_match_in_table('lib_gsis', 'gsis_fname', 'gsis_lname', 'gsis_birthdate', $fname, $lname, $birthdate);
    // $possible_match_pvao = $this->check_possible_match_in_table('lib_pvao', 'pvao_fname', 'pvao_lname', 'pvao_birthdate', $fname, $lname, $birthdate);
    // $possible_duplicate = $this->check_possible_duplicate_in_table('tbl_client', 'client_fname', 'client_lname', 'birthdate', $fname, $lname, $birthdate);

    // if ($possible_match_gsis || $possible_match_pvao) {
    //     return ['status' => 'possible match'];
    // }elseif($possible_duplicate){
    //     return ['status' => 'possible duplicate'];
    // }

    return ['status' => 'no match'];
  }


  private function check_exact_match_in_table($table, $fname_col, $lname_col, $birthdate_col, $fname, $lname, $birthdate) {
    // Clean and prepare the input data
    $fname = trim($fname);
    $lname = trim($lname);
    $birthdate = trim($birthdate);

    // Convert inputs to lowercase for case-insensitive comparison
    $this->db->select('*');
    $this->db->from($table);
    $this->db->where("LOWER(TRIM($fname_col))", strtolower($fname));
    $this->db->where("LOWER(TRIM($lname_col))", strtolower($lname));
    $this->db->where("$birthdate_col", $birthdate); // Assuming birthdate format is consistent

    $query = $this->db->get();

    // Debugging output (remove in production)
    log_message('debug', "Query executed: " . $this->db->last_query());

    return $query->num_rows() > 0;
  }


  private function check_possible_match_in_table($table, $fname_col, $lname_col, $birthdate_col, $fname, $lname, $birthdate) {
    // Clean and prepare the input data
    $fname = trim($fname);
    $lname = trim($lname);
    $birthdate = trim($birthdate);

    // Convert inputs to lowercase for case-insensitive comparison
    $this->db->select('*');
    $this->db->from($table);

    // Check for similar names with different birthdates
    $this->db->where("(LOWER(TRIM($fname_col)) = LOWER(TRIM('$fname')) OR LEAST(LENGTH(TRIM($fname_col)), LENGTH(TRIM('$fname'))) - LENGTH(REPLACE(LOWER(TRIM($fname_col)), LOWER(TRIM('$fname')), '')) = 1)");
    $this->db->where("(LOWER(TRIM($lname_col)) = LOWER(TRIM('$lname')) OR LEAST(LENGTH(TRIM($lname_col)), LENGTH(TRIM('$lname'))) - LENGTH(REPLACE(LOWER(TRIM($lname_col)), LOWER(TRIM('$lname')), '')) = 1)");
    $this->db->where("LOWER(TRIM($birthdate_col)) != LOWER(TRIM('$birthdate'))");

    $query = $this->db->get();

    // Debugging output (remove in production)
    log_message('debug', "Query executed: " . $this->db->last_query());

    return $query->num_rows() > 0;
  }


  private function check_possible_duplicate_in_table($table, $fname_col, $lname_col, $birthdate_col, $fname, $lname, $birthdate) {
    // Clean and prepare the input data
    $fname = trim($fname);
    $lname = trim($lname);
    $birthdate = trim($birthdate);

    // Convert inputs to lowercase for case-insensitive comparison
    $this->db->select('*');
    $this->db->from($table);

    // Check for similar names with different birthdates
    $this->db->where("(LOWER(TRIM($fname_col)) = LOWER(TRIM('$fname')) OR LEAST(LENGTH(TRIM($fname_col)), LENGTH(TRIM('$fname'))) - LENGTH(REPLACE(LOWER(TRIM($fname_col)), LOWER(TRIM('$fname')), '')) = 1)");
    $this->db->where("(LOWER(TRIM($lname_col)) = LOWER(TRIM('$lname')) OR LEAST(LENGTH(TRIM($lname_col)), LENGTH(TRIM('$lname'))) - LENGTH(REPLACE(LOWER(TRIM($lname_col)), LOWER(TRIM('$lname')), '')) = 1)");
    $this->db->where("LOWER(TRIM($birthdate_col)) != LOWER(TRIM('$birthdate'))");

    $query = $this->db->get();

    // Debugging output (remove in production)
    log_message('debug', "Query executed: " . $this->db->last_query());

    return $query->num_rows() > 0;
  }

  public function get_client_data($data) {
    $this->db->select('tbl_client.*, tbl_client_spouse.*, tbl_client_eligibility_assessment.*, tbl_client_recommendation.*, lib_province_perm.province_name, lib_municipality_perm.city_name, lib_barangay_perm.brgy_name, lib_province_pre.province_name AS pre_province_name, lib_municipality_pre.city_name AS pre_city_name, lib_barangay_pre.brgy_name AS pre_brgy_name');
    $this->db->from('tbl_client');

    // Join for Permanent Address
    $this->db->join('lib_province AS lib_province_perm', 'tbl_client.perm_prov = lib_province_perm.province_id', 'left');
    $this->db->join('lib_municipality AS lib_municipality_perm', 'tbl_client.perm_muni = lib_municipality_perm.city_id', 'left');
    $this->db->join('lib_barangay AS lib_barangay_perm', 'tbl_client.perm_brgy = lib_barangay_perm.brgy_id', 'left');
    
    // Join for Present Address
    $this->db->join('lib_province AS lib_province_pre', 'tbl_client.pre_prov = lib_province_pre.province_id', 'left');
    $this->db->join('lib_municipality AS lib_municipality_pre', 'tbl_client.pre_muni = lib_municipality_pre.city_id', 'left');
    $this->db->join('lib_barangay AS lib_barangay_pre', 'tbl_client.pre_brgy = lib_barangay_pre.brgy_id', 'left');
    
    // Join for spouse, eligibility assessment, and recommendation
    $this->db->join('tbl_client_recommendation', 'tbl_client.client_id = tbl_client_recommendation.client_id', 'left');
    $this->db->join('tbl_client_eligibility_assessment', 'tbl_client.client_id = tbl_client_eligibility_assessment.client_id', 'left');
    $this->db->join('tbl_client_spouse', 'tbl_client.client_id = tbl_client_spouse.client_id', 'left');
    
    // Where conditions
    $this->db->where('tbl_client.client_fname', $data['client_fname']);
    $this->db->where('tbl_client.client_lname', $data['client_lname']);
    $this->db->where('tbl_client.birthdate', $data['birthdate']);
    
    return $this->db->get()->row_array();
  }

  // get household members
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


  // get eligibility_assessment
  public function get_eligibility_assessment($client_id) {
    $this->db->select('*');
    $this->db->from('tbl_client_eligibility_assessment');
    $this->db->where('client_id', $client_id);  
    $query = $this->db->get();
    return $query->result_array(); 
  }





  // SAVE CLIENT 
  // get brgy psgc
  public function get_brgy_psgc_model($brgy_id) {
    $this->db->where('brgy_id', $brgy_id);
    return $this->db->get('lib_barangay')->row();
  }


  public function get_client_last_id_model() {
    $this->db->select_max('client_id'); // Select the maximum client_id
    $query = $this->db->get('tbl_client'); // Specify the table directly in the get() method
    return $query->row_array(); // Return a single row as an associative array
}

  // identifying information
  public function save_client_identifying_information_model($identifying_informationxss){
    $this->db->insert('tbl_client', $identifying_informationxss); 
    return true;
  }

  // spouse
  public function save_client_spouse_model($spousexss){
    $this->db->insert('tbl_client_spouse', $spousexss); 
    return true;
  }

  // household members
  public function save_client_household_members_model($household_membersxss){
    $this->db->insert('tbl_client_household_members', $household_membersxss);
    return true;
  }

  // authorized representatives
  public function save_client_authorized_representatives_model($authorized_representativessxss){
    $this->db->insert('tbl_client_authorized_representatives', $authorized_representativessxss);
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


  // GET CLIENT DATA LIST
  public function get_client_data_list($limit, $start, $order, $dir, $search = null, $column_search = []) {
    $this->_get_datatables_query_client_data_list($order, $dir, $search, $column_search);

    if ($limit != -1) {
        $this->db->limit($limit, $start);
    }

    $query = $this->db->get();
    return $query->result();
  }

  // COUNT ALL CLIENT DATA LIST
  public function count_all_client_data_list() {
    $this->db->from('tbl_client');
    return $this->db->count_all_results();
  }

  // COUNT FILTERED CLIENT DATA LIST
  public function count_filtered_client_data_list($search = null, $column_search = []) {
    $this->_get_datatables_query_client_data_list(null, null, $search, $column_search);
    return $this->db->count_all_results();
  }

  // GET DATATABLES QUERY CLIENT DATA LIST
  private function _get_datatables_query_client_data_list($order = null, $dir = null, $search = null, $column_search = []) {
    $this->db->select('*');
    $this->db->from('tbl_client');
    $this->db->join('lib_barangay', 'tbl_client.pre_brgy = lib_barangay.brgy_id', 'left');
    $this->db->join('lib_municipality', 'tbl_client.pre_muni = lib_municipality.city_id', 'left');
    $this->db->join('lib_province', 'tbl_client.pre_prov = lib_province.province_id', 'left');
    $this->db->join('lib_region', 'tbl_client.pre_region = lib_region.region_id', 'left');
    

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
                } elseif (stripos('For Verification', $value) !== false) {
                  $value = 5;
              }
            }

            if ($column == 'sub_client_status') {
              if (stripos('Eligible', $value) !== false) {
                    $value = 1;
                } elseif (stripos('Not Eligible', $value) !== false) {
                    $value = 2;
                } elseif (stripos('Duplicate', $value) !== false) {
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
        $this->db->like('ref_code', $search);
        $this->db->or_like('client_fname', $search);
        $this->db->or_like('client_mname', $search);
        $this->db->or_like('client_lname', $search);
        $this->db->or_like('client_ename', $search);
        $this->db->or_like('birthdate', $search);

        // if (stripos('active', $search) !== false) {
        //     $this->db->or_like('gsis_pension_status', 1);
        // } elseif (stripos('suspended', $search) !== false) {
        //     $this->db->or_like('gsis_pension_status', 0);
        // }

        if (stripos('Active', $search) !== false) {
            $this->db->or_like('client_status', 1);
        } elseif (stripos('Waitlisted', $search) !== false) {
              $this->db->or_like('client_status', 2);
        } elseif (stripos('Delisted', $search) !== false) {
              $this->db->or_like('client_status', 3);
        } elseif (stripos('Not Eligible', $search) !== false) {
            $this->db->or_like('client_status', 4);
        } elseif (stripos('For Verification', $search) !== false) {
            $this->db->or_like('client_status', 5);
        }


        if (stripos('Eligible', $search) !== false) {
          $this->db->or_like('sub_client_status', 1);
      } elseif (stripos('Not Eligible', $search) !== false) {
            $this->db->or_like('sub_client_status', 2);
      } elseif (stripos('Duplicate', $search) !== false) {
            $this->db->or_like('sub_client_status', 3);
      } 
        $this->db->group_end();
    }

    if ($order && $dir) {
        $this->db->order_by($order, $dir);
    }
  }



  // CLIENT INFORMATION
  public function client_information_model($hashed_id) {
    // Get the client_id by comparing the hashed value
    $this->db->select('client_id');
    $users = $this->db->get('tbl_client')->result();
    
    foreach ($users as $user) {
        if (md5($user->client_id) == $hashed_id) {
            // Start the query to get client information with joins
            $this->db->select('tbl_client.*, tbl_client_spouse.*, tbl_client_eligibility_assessment.*, tbl_client_recommendation.*, lib_province_perm.province_name, lib_municipality_perm.city_name, lib_barangay_perm.brgy_name, lib_province_pre.province_name AS pre_province_name, lib_municipality_pre.city_name AS pre_city_name, lib_barangay_pre.brgy_name AS pre_brgy_name');
            $this->db->from('tbl_client');
            
            // Joins for Permanent Address
            $this->db->join('lib_province AS lib_province_perm', 'tbl_client.perm_prov = lib_province_perm.province_id', 'left');
            $this->db->join('lib_municipality AS lib_municipality_perm', 'tbl_client.perm_muni = lib_municipality_perm.city_id', 'left');
            $this->db->join('lib_barangay AS lib_barangay_perm', 'tbl_client.perm_brgy = lib_barangay_perm.brgy_id', 'left');
            
            // Joins for Present Address
            $this->db->join('lib_province AS lib_province_pre', 'tbl_client.pre_prov = lib_province_pre.province_id', 'left');
            $this->db->join('lib_municipality AS lib_municipality_pre', 'tbl_client.pre_muni = lib_municipality_pre.city_id', 'left');
            $this->db->join('lib_barangay AS lib_barangay_pre', 'tbl_client.pre_brgy = lib_barangay_pre.brgy_id', 'left');
            
            // Joins for spouse, eligibility assessment, and recommendation
            $this->db->join('tbl_client_recommendation', 'tbl_client.client_id = tbl_client_recommendation.client_id', 'left');
            $this->db->join('tbl_client_eligibility_assessment', 'tbl_client.client_id = tbl_client_eligibility_assessment.client_id', 'left');
            $this->db->join('tbl_client_spouse', 'tbl_client.client_id = tbl_client_spouse.client_id', 'left');
            
            // Where condition to match the hashed client_id
            $this->db->where('tbl_client.client_id', $user->client_id);
            
            $result = $this->db->get();
            if ($result->num_rows() > 0) {
                return $result->result();
            }
        }
    }

    return false; 
}



// GET CLIENT DATA LIST
public function get_client_waitlisted_list($limit, $start, $order, $dir, $search = null, $column_search = []) {
  $this->_get_datatables_query_client_waitlisted_list($order, $dir, $search, $column_search);

  if ($limit != -1) {
      $this->db->limit($limit, $start);
  }

  $query = $this->db->get();
  return $query->result();
}

// COUNT ALL CLIENT DATA LIST
public function count_all_client_waitlisted_list() {
  $this->db->from('tbl_client');
  return $this->db->count_all_results();
}

// COUNT FILTERED CLIENT DATA LIST
public function count_filtered_client_waitlisted_list($search = null, $column_search = []) {
  $this->_get_datatables_query_client_waitlisted_list(null, null, $search, $column_search);
  return $this->db->count_all_results();
}

// GET DATATABLES QUERY CLIENT DATA LIST
private function _get_datatables_query_client_waitlisted_list($order = null, $dir = null, $search = null, $column_search = []) {
  $this->db->select('*');
  $this->db->from('tbl_client');
  $this->db->join('tbl_client_eligibility_assessment', 'tbl_client.client_id = tbl_client_eligibility_assessment.client_id', 'left');
  $this->db->where('client_status', 2);

  foreach ($column_search as $column => $value) {
      if (!empty($value)) {
          $this->db->like($column, $value);
      }
  }

  if ($search) {
    $this->db->group_start();
    $this->db->like('dateandtimeApply', $search);
    $this->db->or_like('client_fname', $search);
    $this->db->or_like('client_mname', $search);
    $this->db->or_like('client_lname', $search);
    $this->db->or_like('client_ename', $search);
    $this->db->group_end();
}

  if ($order && $dir) {
      $this->db->order_by($order, $dir);
  }
}


public function update_client_identifying_information_model($identifying_informationxss){
  $this->db->set($identifying_informationxss);
  $this->db->where('client_id', $identifying_informationxss['client_id']);
  $this->db->update('tbl_client');

  return true;
}


public function client_delete_model($client_id) {
  $this->db->trans_start(); 

  $this->db->where('client_id', $client_id);
  $this->db->delete('tbl_client');

  $this->db->where('client_id', $client_id);
  $this->db->delete('tbl_client_authorized_representatives');

  $this->db->where('client_id', $client_id);
  $this->db->delete('tbl_client_eligibility_assessment');

  $this->db->where('client_id', $client_id);
  $this->db->delete('tbl_client_household_members');

  $this->db->where('client_id', $client_id);
  $this->db->delete('tbl_client_recommendation');

  $this->db->where('client_id', $client_id);
  $this->db->delete('tbl_client_spouse');


  if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback(); 
      return false;
  } else {
      $this->db->trans_commit();
      return true;
  }
}



}?>