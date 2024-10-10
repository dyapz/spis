<?php 
	$csrf = array(
		'name' => $this->security->get_csrf_token_name(),
		'hash' => $this->security->get_csrf_hash()
		);

        $new_id = "";
        $formatted_id = "";
        foreach ($id_validation as $client_id){
            $new_id = $client_id->client_id + 1;
            $formatted_id = str_pad($new_id, 5, '0', STR_PAD_LEFT);
        }
		// error_reporting(0);

    $client_data = $this->session->userdata('client_data');

?>

<!-- <?php if (isset($message) && !empty($message)): ?>
    <div class="alert alert-info">
        <?php echo $message; ?>
    </div>
<?php endif; ?> -->


<style>
    .multisteps-form_progress {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(0, 1fr));
    }
    .multisteps-form_progress-btn {
        transition-property: all;
        transition-duration: 0.15s;
        transition-timing-function: linear;
        transition-delay: 0s;
        position: relative;
        padding-top: 20px;
        color: rgba(108, 117, 125, 0.7);
        text-indent: -9999px;
        border: none;
        background-color: transparent;
        outline: none !important;
        cursor: pointer;
    }
    @media (min-width: 500px) {
        .multisteps-form_progress-btn {
            text-indent: 0;
        }
    }
    .multisteps-form_progress-btn:before {
        position: absolute;
        top: 0;
        left: 50%;
        display: block;
        width: 13px;
        height: 13px;
        content: '';
        -webkit-transform: translateX(-50%);
                transform: translateX(-50%);
        transition: all 0.15s linear 0s, -webkit-transform 0.15s cubic-bezier(0.05, 1.09, 0.16, 1.4) 0s;
        transition: all 0.15s linear 0s, transform 0.15s cubic-bezier(0.05, 1.09, 0.16, 1.4) 0s;
        transition: all 0.15s linear 0s, transform 0.15s cubic-bezier(0.05, 1.09, 0.16, 1.4) 0s, -webkit-transform 0.15s cubic-bezier(0.05, 1.09, 0.16, 1.4) 0s;
        border: 2px solid currentColor;
        border-radius: 50%;
        background-color: #fff;
        box-sizing: border-box;
        z-index: 3;
    }
    .multisteps-form_progress-btn:after {
        position: absolute;
        top: 5px;
        left: calc(-50% - 13px / 2);
        transition-property: all;
        transition-duration: 0.15s;
        transition-timing-function: linear;
        transition-delay: 0s;
        display: block;
        width: 100%;
        height: 2px;
        content: '';
        background-color: currentColor;
        z-index: 1;
    }
    .multisteps-form_progress-btn:first-child:after {
        display: none;
    }
    .multisteps-form_progress-btn.js-active {
        color: #337d94;
    }
    .multisteps-form_progress-btn.js-active:before {
        -webkit-transform: translateX(-50%) scale(1.2);
                transform: translateX(-50%) scale(1.2);
        background-color: currentColor;
    }
    .multisteps-form_form {
        position: relative;
    }

    .multisteps-form_title{
        font-size: 1rem;
        font-weight: bold;
    }


    .multisteps-form_panel {
        transition: left 0.25s cubic-bezier(0.2, 1.13, 0.38, 1.43), opacity 0.25s ease-in-out;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 0;
        opacity: 0;
        visibility: hidden;
        overflow-y: auto; 
        max-height: 750px; 
    }

    
    .multisteps-form_panel.js-active {
        left: 0;
        height: auto;
        opacity: 1;
        visibility: visible;
    }

    .multisteps-form_panel[data-animation="js-btn-next"] {
        left: 10px;
    }

    .multisteps-form_panel[data-animation="js-btn-prev"] {
        left: -10px; 
    }

    .multisteps-form {
        width: 100%; 
        max-width: 1800px; 
        margin: 0 auto; 
    }

    .button-row {
        display: flex;
        align-items: center; 
    }

    .button-end {
        margin-left: auto;
    }

    label{
        font-size: .8rem;
    }


    table.table-bordered{
    border:1px solid #4a4a4a;
    margin-top:20px;
  }

    table.table-bordered > tbody > tr > td{
        border:1px solid #4a4a4a;
    }

 td {
        vertical-align: bottom;
    }
    
    td input, td select {
        width: 100%;
        margin-top: auto;
    }
    
    td input, td select {
        padding: 5px;
        box-sizing: border-box;
    }

   input[disabled] {
        background-color: lightgray; 
        border-color: lightgray; 
    }

    .unclickable {
        pointer-events: none; 
        opacity: 0.6; 
    }
    .font_size{
        font-size: 15px;
        font-style: italic;
        margin-top: -10px;
        margin-bottom: -20px;

    }
   
</style>

<div class="multisteps-form">
  <div class="row d-flex justify-content-center">
    <div class="col-12 col-lg-8 ml-auto mr-auto mb-4">
      <div class="multisteps-form_progress">
        <button class="multisteps-form_progress-btn js-active" type="button" title="Identifying Information">Identifying Information</button>
        <button class="multisteps-form_progress-btn" type="button" title="Faimly Information">Family Information</button>
        <button class="multisteps-form_progress-btn" type="button" title="Eligibility Assessment">Eligibility Assessment</button>
        <button class="multisteps-form_progress-btn" type="button" title="Recommendation">Recommendation</button>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12 col-lg-8 m-auto">
        <form class="multisteps-form_form" action="" method="POST">
        <!-- IDENTIFYING INFORMATION -->
        <div class="multisteps-form_panel card-shadow p-4 rounded bg-white js-active" data-animation="scaleIn">
            <h5 class="multisteps-form_title">I. IDENTIFYING INFORMATION</h5>

            
            <div class="multisteps-form_content mt-4">
                <div class="row">
                    <label class="label mb-3 fw-bold">NAME:</label><br>
                    <div class="form-group col-md-3">
                        <input type="text" class="form-control" name="client_lname" value="<?php echo set_value('client_lname', isset($client_data['client_lname']) ? $client_data['client_lname'] : '');?>"  placeholder="" required>
                        <label class="form-label fw-bold">LAST NAME <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-group col-md-3">
                        <input type="text" class="form-control" name="client_fname" value="<?php echo set_value('client_fname', isset($client_data['client_fname']) ? $client_data['client_fname'] : ''); ?>" placeholder="" required>
                        <label class="form-label fw-bold">FIRST NAME <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-group col-md-3">
                        <input type="text" class="form-control" name="client_mname" value="<?php echo set_value('client_mname', isset($client_data['client_mname']) ? $client_data['client_mname'] : '');?>" placeholder="">
                        <label class="form-label fw-bold">MIDDLE NAME</label>
                    </div>

                    <div class="form-group col-md-3">
                        <input type="text" class="form-control" name="client_ename" value="<?php echo set_value('client_ename', isset($client_data['client_ename']) ? $client_data['client_ename'] : '');?>" placeholder="">
                        <label class="form-label fw-bold">EXT. NAME</label>
                    </div>


                    <div class="form-group col-md-4">
                        
                        <input type="text" class="form-control" name="osca_id" value="<?php echo set_value('osca_id', isset($client_data['osca_id']) ? $client_data['osca_id'] : '');?>" placeholder="" required>
                        <label class="form-label fw-bold">OSCA ID No. <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-group col-md-4">
                        <input type="text" class="form-control" name="philsys_id" value="<?php echo set_value('philsys_id', isset($client_data['philsys_id']) ? $client_data['philsys_id'] : '');?>" placeholder="">
                        <label class="form-label fw-bold">PhilSys. ID No.</label>
                    </div>
                    <div class="form-group col-md-4">
                        Pantawid Beneficiary (Benepisyaryo ng 4Ps): <span class="text-danger">*</span>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pantawid_y_n" id="4psyes" value="1" <?php echo (isset($client_data['pantawid_y_n']) && $client_data['pantawid_y_n'] == 1) ? 'checked' : ''; ?> required="required">
                            <label class="form-check-label" for="4psyes">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="pantawid_y_n" id="4psno" value="2" <?php echo (isset($client_data['pantawid_y_n']) && $client_data['pantawid_y_n'] == 2) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="4psno">No</label>
                        </div>
                    </div>

                    <label class="label mb-3 fw-bold">MOTHER'S MAIDEN NAME:</label><br>
                    <div class="form-group col-md-4">
                        <input type="text" class="form-control"  name="mothers_maiden_lname" value="<?php echo set_value('mothers_maiden_lname', isset($client_data['mothers_maiden_lname']) ? $client_data['mothers_maiden_lname'] : '');?>" placeholder="" required>
                        <label class="form-label fw-bold">LAST NAME <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-group col-md-4">
                        <input type="text" class="form-control"  name="mothers_maiden_fname" value="<?php echo set_value('mothers_maiden_fname', isset($client_data['mothers_maiden_fname']) ? $client_data['mothers_maiden_fname'] : '');?>" placeholder="" required>
                        <label class="form-label fw-bold">FIRST NAME <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-group col-md-4">
                        <input type="text" class="form-control"  name="mothers_maiden_mname" value="<?php echo set_value('mothers_maiden_mname', isset($client_data['mothers_maiden_mname']) ? $client_data['mothers_maiden_mname'] : '');?>" placeholder="" required>
                        <label class="form-label fw-bold">MIDDLE NAME <span class="text-danger">*</span></label>
                    </div>

                    <label class="label mb-3 fw-bold">PERMANENT ADDRESS:</label><br>
                    <div class="form-group col-md-4">
                        <select name="perm_region" id="perm_region" class="form-select <?php echo (isset($client_data['perm_region']) && $client_data['perm_region'] == $client_data['perm_region']) ? 'has-value' : ''; ?>" required>
                            <option value="" disabled selected></option>
                            <?php foreach($region as $row): ?>
                                <option value="<?php echo $row->region_id; ?>" 
                                        <?php echo (isset($client_data['perm_region']) && $client_data['perm_region'] == $row->region_id) ? 'selected' : ''; ?>>
                                    <?php echo $row->region_name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <label for="perm_region" class="form-label fw-bold">REGION <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-group col-md-4">
                        <select class="form-select <?php echo (isset($client_data['perm_prov']) && $client_data['perm_prov'] == $client_data['perm_prov']) ? 'has-value' : ''; ?>" name="perm_prov" id="perm_prov" required>
                            <option value="<?php echo $client_data['perm_prov']; ?>"><?php echo $client_data['province_name']; ?></option>
                        </select>
                        <label class="form-label fw-bold">PROVINCE <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-group col-md-4">
                        <select class="form-select <?php echo (isset($client_data['perm_muni']) && $client_data['perm_muni'] == $client_data['perm_muni']) ? 'has-value' : ''; ?>" name="perm_muni" id="perm_muni" required>
                            <option value="<?php echo $client_data['perm_muni']; ?>"><?php echo $client_data['city_name']; ?></option>
                        </select>
                        <label class="form-label fw-bold">CITY/MUNICIPALITY <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-group col-md-4">
                        <select class="form-select <?php echo (isset($client_data['perm_brgy']) && $client_data['perm_brgy'] == $client_data['perm_brgy']) ? 'has-value' : ''; ?>" name="perm_brgy" id="perm_brgy" required>
                            <option value="<?php echo $client_data['perm_brgy']; ?>"><?php echo $client_data['brgy_name']; ?></option>
                        </select>
                        <label class="form-label fw-bold">BARANGAY <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-group col-md-4">
                        <input type="text" class="form-control" name="perm_sitio" id="perm_sitio" value="<?php echo set_value('perm_sitio', isset($client_data['perm_sitio']) ? $client_data['perm_sitio'] : '');?>" placeholder="" required>
                        <label class="form-label fw-bold">HOUSE NO./ PUROK <span class="text-danger">*</span></label>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <input type="text" class="form-control" name="perm_street" id="perm_street" value="<?php echo set_value('perm_street', isset($client_data['perm_street']) ? $client_data['perm_street'] : '');?>" placeholder="" required>
                        <label class="form-label fw-bold">STREET <span class="text-danger">*</span></label>
                    </div>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" value="" id="copyResult" name="same_address" <?php echo (isset($client_data['same_address']) && $client_data['same_address'] == 1) ? 'checked' : ''; ?>>
                    <label class="form-check-label text-secondary fw-bold" for="flexCheckChecked">Check if same address above</label>
                </div>


                <div class="row">
                    <label class="label mb-3 fw-bold">PRESENT ADDRESS:</label><br>
                    <div class="row">
                        <div class="form-group col-md-4">
                        <select name="pre_region" id="pre_region" class="form-select <?php echo (isset($client_data['pre_region']) && $client_data['pre_region'] == $client_data['pre_region']) ? 'has-value' : ''; ?>" required>
                            <option value="" disabled selected></option>
                            <?php foreach($region as $row): ?>
                                <option value="<?php echo $row->region_id; ?>" 
                                        <?php echo (isset($client_data['pre_region']) && $client_data['pre_region'] == $row->region_id) ? 'selected' : ''; ?>>
                                    <?php echo $row->region_name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <label for="pre_region" class="form-label fw-bold">REGION <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-group col-md-4">
                        <select class="form-select <?php echo (isset($client_data['pre_prov']) && $client_data['pre_prov'] == $client_data['pre_prov']) ? 'has-value' : ''; ?>" name="pre_prov" id="pre_prov" required>
                            <option value="<?php echo $client_data['pre_prov']; ?>"><?php echo $client_data['pre_province_name']; ?></option>
                        </select>
                        <label class="form-label fw-bold">PROVINCE <span class="text-danger">*</span></label>
                    </div>


                    <div class="form-group col-md-4">
                        <select class="form-select <?php echo (isset($client_data['pre_muni']) && $client_data['pre_muni'] == $client_data['pre_muni']) ? 'has-value' : ''; ?>" name="pre_muni" id="pre_muni" required>
                            <option value="<?php echo $client_data['pre_muni']; ?>"><?php echo $client_data['pre_city_name']; ?></option>
                        </select>
                        <label class="form-label fw-bold">MUNICIPALITY <span class="text-danger">*</span></label>
                    </div>


                    <div class="form-group col-md-4">
                        <select class="form-select <?php echo (isset($client_data['pre_brgy']) && $client_data['pre_brgy'] == $client_data['pre_brgy']) ? 'has-value' : ''; ?>" name="pre_brgy" id="pre_brgy" required>
                            <option value="<?php echo $client_data['pre_brgy']; ?>"><?php echo $client_data['pre_brgy_name']; ?></option>
                        </select>
                        <label class="form-label fw-bold">BARANGAY <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-group col-md-4">
                        <input type="text" class="form-control" name="pre_sitio" id="pre_sitio" value="<?php echo set_value('pre_sitio', isset($client_data['pre_sitio']) ? $client_data['pre_sitio'] : '');?>" placeholder="" required>
                        <label class="form-label fw-bold">HOUSE NO./ PUROK <span class="text-danger">*</span></label>
                    </div>

                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" name="pre_street" id="pre_street" value="<?php echo set_value('pre_street', isset($client_data['pre_street']) ? $client_data['pre_street'] : '');?>" placeholder="" required>
                            <label class="form-label fw-bold">STREET <span class="text-danger">*</span></label>
                        </div>
                    </div>

                    <div class="form-group col-md-2">
                        <input type="date" class="form-control" name="birthdate" id="bday" value="<?php echo set_value('birthdate', isset($client_data['birthdate']) ? $client_data['birthdate'] : '');?>" onchange="submitBday()">
                        <label class="form-label fw-bold">BIRTHDATE <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-group col-md-2">
                        <input type="text" class="form-control" name="birth_place" value="<?php echo set_value('birth_place', isset($client_data['birth_place']) ? $client_data['birth_place'] : '');?>" placeholder="" required>
                        <label class="form-label fw-bold">PLACE OF BIRTH <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-group col-md-1">
                        <textarea class="form-control" name="age" rows="1" id="age" placeholder="" required readonly><?php echo set_value('age');?></textarea>
                        <label class="form-label fw-bold">AGE <i class="fas fa-lock" id="lock" aria-hidden="true"></i></label>
                    </div>

                    <div class="form-group col-md-3">
                        <select class="form-select <?php echo (isset($client_data['gender']) && $client_data['gender'] == $client_data['gender']) ? 'has-value' : ''; ?>" name="gender" required>
                            <option value="" disabled selected></option>
                            <option value="1" <?php echo (isset($client_data['gender']) && $client_data['gender'] == 1) ? 'selected' : ''; ?>>1 - MALE</option>
                            <option value="2" <?php echo (isset($client_data['gender']) && $client_data['gender'] == 2) ? 'selected' : ''; ?>>2 - FEMALE</option>
                            <option value="3" <?php echo (isset($client_data['gender']) && $client_data['gender'] == 3) ? 'selected' : ''; ?>>3 - Member of LGBTTQQIA+++</option>
                            </select>
                        <label class="form-label fw-bold">GENDER <span class="text-danger">*</span></label>
                    </div>	

                    <div class="form-group col-md-2">
                        <select class="form-select <?php echo (isset($client_data['civil_status']) && $client_data['civil_status'] == $client_data['civil_status']) ? 'has-value' : ''; ?>" name="civil_status" id="civil_status" required>
                            <option value="" disabled selected></option>
                            <option value="1" <?php echo (isset($client_data['civil_status']) && $client_data['civil_status'] == 1) ? 'selected' : ''; ?>>1 - SINGLE</option>
                            <option value="2" <?php echo (isset($client_data['civil_status']) && $client_data['civil_status'] == 2) ? 'selected' : ''; ?>>2 - MARRIED</option>
                            <option value="3" <?php echo (isset($client_data['civil_status']) && $client_data['civil_status'] == 5) ? 'selected' : ''; ?>>3 - SEPARATED</option>
                            <option value="4" <?php echo (isset($client_data['civil_status']) && $client_data['civil_status'] == 3) ? 'selected' : ''; ?>>4 - WIDOWED</option>
                            <option value="5" <?php echo (isset($client_data['civil_status']) && $client_data['civil_status'] == 4) ? 'selected' : ''; ?>>5 - LIVE-IN</option>
                            <option value="6" <?php echo (isset($client_data['civil_status']) && $client_data['civil_status'] == 6) ? 'selected' : ''; ?>>6 - OTHERS</option>
                        </select>
                        <label class="form-label fw-bold">CIVIL STATUS <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-group col-md-2" id="cs_others_group" style="display: none;">
                        <input type="text" id="cs_others" class="form-control" name="cs_others" value="<?php echo set_value('cs_others', isset($client_data['cs_others']) ? $client_data['cs_others'] : '');?>" placeholder="">
                        <label class="form-label fw-bold">SPECIFY <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-group col-md-3 d-flex justify-content-start">
                        <select id="living_arrangement" name="living_arrangement" class="form-select <?php echo (isset($client_data['living_arrangement']) && $client_data['living_arrangement'] == $client_data['living_arrangement']) ? 'has-value' : ''; ?>" required>
                            <option value="" disabled selected></option>
                            <option value="1" <?php echo (isset($client_data['living_arrangement']) && $client_data['living_arrangement'] == 1) ? 'selected' : ''; ?>>1 - LIVING ALONE</option>
                            <option value="2" <?php echo (isset($client_data['living_arrangement']) && $client_data['living_arrangement'] == 2) ? 'selected' : ''; ?>>2 - LIVING WITH SPOUSE</option>
                            <option value="3" <?php echo (isset($client_data['living_arrangement']) && $client_data['living_arrangement'] == 3) ? 'selected' : ''; ?>>3 - LIVING WITH CHILDREN</option>
                            <option value="4" <?php echo (isset($client_data['living_arrangement']) && $client_data['living_arrangement'] == 4) ? 'selected' : ''; ?>>4 - OTHERS</option>
                        </select>
                        <label class="form-label fw-bold">LIVING ARRANGEMENT <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-group col-md-3" id="living_arrangement_others_group" style="display: none;">
                        <input type="text" id="living_arrangement_others" class="form-control" name="living_arrangement_others" value="<?php echo set_value('living_arrangement_others', isset($client_data['living_arrangement_others']) ? $client_data['living_arrangement_others'] : '');?>" placeholder="">
                        <label class="form-label fw-bold">SPECIFY <span class="text-danger">*</span></label>
                    </div>



                    <div class="form-group col-md-3">
                        <select id="sector" class="form-select <?php echo (isset($client_data['sector']) && $client_data['sector'] == $client_data['sector']) ? 'has-value' : ''; ?>" name="sector" required>
                            <option value="" disabled selected></option>
                            <option value="1" <?php echo (isset($client_data['sector']) && $client_data['sector'] == 1) ? 'selected' : ''; ?>>1 - INDIGENOUS PEOPLE (Mga Katutubo)</option>
                            <option value="2" <?php echo (isset($client_data['sector']) && $client_data['sector'] == 2) ? 'selected' : ''; ?>>2 - PERSON WITH DISABILITY</option>
                            <option value="3" <?php echo (isset($client_data['sector']) && $client_data['sector'] == 3) ? 'selected' : ''; ?>>3 - SOLO PARENT</option>
                            <option value="4" <?php echo (isset($client_data['sector']) && $client_data['sector'] == 4) ? 'selected' : ''; ?>>4 - MEMBER OF LGBTTQIA+++</option>
                        </select>
                        <label class="form-label fw-bold">SECTOR <span class="text-danger">*</span></label>
                    </div>

                    <div class="form-group col-md-3" >
                    <div id="ip_specify_group" style="display: none;">
                            <input type="text" id="ip_specify" class="form-control" name="ip_specify" value="<?php echo set_value('ip_specify', isset($client_data['ip_specify']) ? $client_data['ip_specify'] : '');?>" placeholder="">
                            <label class="form-label fw-bold">SPECIFY <span class="text-danger">*</span></label>
                        </div>
                    </div>

                              

                    <div class="button-row d-flex mt-5">
                        <button class="btn btn-custom button-end ml-auto js-btn-next" type="button" title="Next">Next</button>
                    </div>
                </div>
            </div>
        </div>
        <!--FAMILY INFORMATION-->
        <div class="multisteps-form_panel card-shadow p-4 rounded bg-white" data-animation="scaleIn">
            <h5 class="multisteps-form_title">II. FAMILY INFORMATION</h5>
            <div class="multisteps-form_content">
                <div id="cs_married_group" style="display: none;">
                    <div class="row">
                        <label class="label mb-3 fw-bold">NAME OF SPOUSE:</label><br>

                        <div class="form-group col-md-3">
                            <input type="text" id="cs_lname" class="form-control" name="cs_lname" value="<?php echo set_value('cs_lname', isset($client_data['cs_lname']) ? $client_data['cs_lname'] : '');?>" placeholder="">
                            <label class="form-label fw-bold">LAST NAME <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-group col-md-3">
                            <input type="text" id="cs_fname" class="form-control" name="cs_fname" value="<?php echo set_value('cs_fname', isset($client_data['cs_fname']) ? $client_data['cs_fname'] : ''); ?>" placeholder="">
                            <label class="form-label fw-bold">FIRST NAME <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-group col-md-3">
                            <input type="text" class="form-control" name="cs_mname" value="<?php echo set_value('cs_mname', isset($client_data['cs_mname']) ? $client_data['cs_mname'] : '');?>" placeholder="">
                            <label class="form-label fw-bold">MIDDLE NAME</label>
                        </div>

                        <div class="form-group col-md-3">
                            <input type="text" class="form-control" name="cs_ename" value="<?php echo set_value('cs_ename', isset($client_data['cs_ename']) ? $client_data['cs_ename'] : '');?>" placeholder="">
                            <label class="form-label fw-bold">EXT. NAME</label>
                        </div>

                        <div class="form-group col-md-9">
                            <input type="text" id="cs_address" class="form-control" name="cs_address" value="<?php echo set_value('cs_address', isset($client_data['cs_address']) ? $client_data['cs_address'] : '');?>" placeholder="">
                            <label class="form-label fw-bold">ADDRESS <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-group col-md-3">
                            <input type="text" class="form-control" name="cs_contact_num" value="<?php echo set_value('cs_contact_num', isset($client_data['cs_contact_num']) ? $client_data['client_ename'] : '');?>" placeholder="">
                            <label class="form-label fw-bold">CONTACT</label>
                        </div>
                    </div>
                </div>

                <label class="label mb-3 fw-bold">HOUSEHOLD MEMBERS:</label>
                <table id="fam_info_details" class="table table-bordered" style="width:100%; margin-top: 0px">
                    <tr class="text-center">
                        <th style="width:30%">NAME <span class="text-danger fw-bold">*</span></th>
                        <th style="width:8%">AGE <span class="text-danger fw-bold">*</span></th>
                        <th style="width:15%">CIVIL STATUS <span class="text-danger fw-bold">*</span></th>
                        <th style="width:15%">RELATIONSHIP TO BENEFICIARY <span class="text-danger fw-bold">*</span></th>
                        <th style="width:17%">OCCUPATION <span class="text-danger fw-bold">*</span></th>
                        <th style="width:13%">MONTHLY INCOME <span class="text-danger fw-bold">*</span></th>
                        <th style="width:2%"><button type="button" id="chm-add-row" class="btn btn-info btn-sm mb-2 text-light">+</button></th>
                    </tr>
                    <tr class="align-text-bottom">
                    <?php if (!empty($household_members)) : ?>
                    <?php foreach ($household_members as $index => $member): ?>
                        <td><input type="text" name="fam_info[<?php echo $index; ?>][chm_name]" class="form-control" value="<?php echo $member['chm_name']; ?>" required></td>
                        <td><input type="text" name="fam_info[<?php echo $index; ?>][chm_age]" class="form-control" value="<?php echo $member['chm_age']; ?>" required></td>
                        <td>
                            <select class="form-select chm-civil-status-select" name="fam_info[<?php echo $index; ?>][chm_civil_status]" required>
                                <option value="" disabled selected></option>
                                <option value="1" <?php echo (isset($member['chm_civil_status']) && $member['chm_civil_status'] == 1) ? 'selected' : ''; ?>>1 - SINGLE</option>
                                <option value="2" <?php echo (isset($member['chm_civil_status']) && $member['chm_civil_status'] == 2) ? 'selected' : ''; ?>>2 - MARRIED</option>
                                <option value="3" <?php echo (isset($member['chm_civil_status']) && $member['chm_civil_status'] == 3) ? 'selected' : ''; ?>>3 - SEPARATED</option>
                                <option value="4" <?php echo (isset($member['chm_civil_status']) && $member['chm_civil_status'] == 4) ? 'selected' : ''; ?>>4 - WIDOWED</option>
                                <option value="5" <?php echo (isset($member['chm_civil_status']) && $member['chm_civil_status'] == 5) ? 'selected' : ''; ?>>5 - LIVE-IN</option>
                                <option value="6" <?php echo (isset($member['chm_civil_status']) && $member['chm_civil_status'] == 6) ? 'selected' : ''; ?>>6 - OTHERS</option>
                            </select>
                            <div class="chm-civil-status-others-group" style="display: none;">
                                <input type="text" class="form-control chm-civil-status-others" name="fam_info[<?php echo $index; ?>][chm_civil_status_others]" value="<?php echo $member['chm_civil_status_others']; ?>" placeholder="SPECIFY">
                            </div>
                        </td>
                        <td>
                            <select class="form-select chm-relationship-select" name="fam_info[<?php echo $index; ?>][chm_relationship]" required>
                                <option value="" disabled selected></option>
                                <option value="1" <?php echo (isset($member['chm_relationship']) && $member['chm_relationship'] == 1) ? 'selected' : ''; ?>>1 - SPOUSE</option>
                                <option value="2" <?php echo (isset($member['chm_relationship']) && $member['chm_relationship'] == 2) ? 'selected' : ''; ?>>2 - CHILD</option>
                                <option value="3" <?php echo (isset($member['chm_relationship']) && $member['chm_relationship'] == 3) ? 'selected' : ''; ?>>3 - SIBLING</option>
                                <option value="4" <?php echo (isset($member['chm_relationship']) && $member['chm_relationship'] == 4) ? 'selected' : ''; ?>>4 - CARETAKER</option>
                                <option value="5" <?php echo (isset($member['chm_relationship']) && $member['chm_relationship'] == 5) ? 'selected' : ''; ?>>5 - OTHERS</option>
                            </select>
                            <div class="chm-relationship-others-group" style="display: none;">
                                <input type="text" class="form-control chm-relationship-others" name="fam_info[<?php echo $index; ?>][chm_relationship_others]" value="<?php echo $member['chm_relationship_others']; ?>" placeholder="SPECIFY">
                            </div>
                        </td>
                        <td><input type="text" name="fam_info[<?php echo $index; ?>][chm_occupation]" class="form-control" value="<?php echo $member['chm_occupation']; ?>" required></td>
                        <td><input type="text" name="fam_info[<?php echo $index; ?>][chm_monthly_income]" class="form-control" value="<?php echo $member['chm_monthly_income']; ?>" required></td>
                        <td><button type="button" class="chm-remove-row btn btn-danger btn-sm">-</button></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </table>

                <script>
                    // HOUSEHOLD MEMBERS
                    $(document).ready(function () {
                        var rowIndex = <?php echo isset($household_members) ? count($household_members) : 1; ?>;

                        function setupRow(index) {
                            $('select[name="fam_info[' + index + '][chm_civil_status]"]').on('change', function () {
                                toggleCHMCivilStatusOthers($(this));
                            });

                            $('select[name="fam_info[' + index + '][chm_relationship]"]').on('change', function () {
                                toggleCHMRelationshipOthers($(this));
                            });
                        }

                        // CIVIL STATUS OTHERS
                        function toggleCHMCivilStatusOthers(selectElement) {
                            var selectedVal = selectElement.val();
                            var othersGroup = selectElement.closest('td').find('.chm-civil-status-others-group');
                            var othersInput = othersGroup.find('.chm-civil-status-others');

                            if (selectedVal == "6") { 
                                othersGroup.show();
                                othersInput.prop('required', true);
                            } else {
                                othersGroup.hide();
                                othersInput.prop('required', false);
                            }
                        }

                        // RELATIONSHIP OTHERS
                        function toggleCHMRelationshipOthers(selectElement) {
                            var selectedVal = selectElement.val();
                            var othersGroup = selectElement.closest('td').find('.chm-relationship-others-group');
                            var othersInput = othersGroup.find('.chm-relationship-others');

                            if (selectedVal == "5") { 
                                othersGroup.show();
                                othersInput.prop('required', true);
                            } else {
                                othersGroup.hide();
                                othersInput.prop('required', false);
                            }
                        }

                        <?php if (!empty($household_members)): ?>
                        for (var i = 0; i < <?php echo count($household_members); ?>; i++) {
                            setupRow(i);
                            var selectElementCivilStatus = $('select[name="fam_info[' + i + '][chm_civil_status]"]');
                            toggleCHMCivilStatusOthers(selectElementCivilStatus);  
                            var selectElementRelationship = $('select[name="fam_info[' + i + '][chm_relationship]"]');
                            toggleCHMRelationshipOthers(selectElementRelationship);  
                        }
                        <?php endif; ?>

                        $("#chm-add-row").click(function () {
                            var newRow = '<tr>' +
                                '<td><input type="text" name="fam_info[' + rowIndex + '][chm_name]" class="form-control" required></td>' +
                                '<td><input type="text" name="fam_info[' + rowIndex + '][chm_age]" class="form-control" required></td>' +
                                '<td><select class="form-select chm-civil-status-select" name="fam_info[' + rowIndex + '][chm_civil_status]" required>' +
                                '<option value="" disabled selected></option>' +
                                '<option value="1">1 - SINGLE</option>' +
                                '<option value="2">2 - MARRIED</option>' +
                                '<option value="3">3 - SEPARATED</option>' +
                                '<option value="4">4 - WIDOWED</option>' +
                                '<option value="5">5 - LIVE-IN</option>' +
                                '<option value="6">6 - OTHERS</option>' +
                                '</select>' +
                                '<div class="chm-civil-status-others-group" style="display: none;">' +
                                '<input type="text" class="form-control chm-civil-status-others" name="fam_info[' + rowIndex + '][chm_civil_status_others]" placeholder="SPECIFY">' +
                                '</div></td>' +
                                '<td><select class="form-select chm-relationship-select" name="fam_info[' + rowIndex + '][chm_relationship]" required>' +
                                '<option value="" disabled selected></option>' +
                                '<option value="1">1 - SPOUSE</option>' +
                                '<option value="2">2 - CHILD</option>' +
                                '<option value="3">3 - SIBLING</option>' +
                                '<option value="4">4 - CARETAKER</option>' +
                                '<option value="5">5 - OTHERS</option>' +
                                '</select>' +
                                '<div class="chm-relationship-others-group" style="display: none;">' +
                                '<input type="text" class="form-control chm-relationship-others" name="fam_info[' + rowIndex + '][chm_relationship_others]" placeholder="SPECIFY">' +
                                '</div></td>' +
                                '<td><input type="text" name="fam_info[' + rowIndex + '][chm_occupation]" class="form-control" required></td>' +
                                '<td><input type="text" name="fam_info[' + rowIndex + '][chm_monthly_income]" class="form-control" required></td>' +
                                '<td><button type="button" class="chm-remove-row btn btn-danger btn-sm">-</button></td>' +
                                '</tr>';

                            $("#fam_info_details tbody").append(newRow);
                            setupRow(rowIndex);
                            rowIndex++;
                        });

                        $(document).on("click", ".chm-remove-row", function() {
                            $(this).closest("tr").remove();
                        });

                        <?php if (!empty($household_members)): ?>
                        for (var i = 0; i < <?php echo count($household_members); ?>; i++) {
                            setupRow(i); 
                        }
                        <?php endif; ?>
                            setupRow(0);
                        });
                </script>

                <label class="label my-3 fw-bold">NAME OF AUTHORIZED REPRESENTATIVES:</label>
                <table id="auth_rep_details" class="table table-bordered" style="width:100%; margin-top: 0px">
                    <tr class="text-center">
                        <th style="width:48%">NAME <span class="text-danger fw-bold">*</span></th>
                        <th style="width:10%">AGE <span class="text-danger fw-bold">*</span></th>
                        <th style="width:20%">RELATIONSHIP TO BENEFICIARY <span class="text-danger fw-bold">*</span></th>
                        <th style="width:20%">CONTACT NUMBER <span class="text-danger fw-bold">*</span></th>
                        <th style="width:2%"><button type="button" id="car-add-row" class="btn btn-info btn-sm mb-2 text-light">+</button></th>
                    </tr>
                    <tr class="align-text-bottom">
                    <?php if (!empty($authorized_representatives)) : ?>
                    <?php foreach ($authorized_representatives as $index => $represnetative): ?>
                        <td><input type="text" name="fam_info[<?php echo $index; ?>][car_name]" class="form-control" value="<?php echo $represnetative['car_name']; ?>" required></td>
                        <td><input type="text" name="fam_info[<?php echo $index; ?>][car_age]" class="form-control" value="<?php echo $represnetative['car_age']; ?>" required></td>
                        <td>
                            <select class="form-select car-relationship-select" name="fam_info[<?php echo $index; ?>][car_relationship]" required>
                                <option value="" disabled selected></option>
                                <option value="1" <?php echo (isset($represnetative['car_relationship']) && $represnetative['car_relationship'] == 1) ? 'selected' : ''; ?>>1 - SPOUSE</option>
                                <option value="2" <?php echo (isset($represnetative['car_relationship']) && $represnetative['car_relationship'] == 2) ? 'selected' : ''; ?>>2 - CHILD</option>
                                <option value="3" <?php echo (isset($represnetative['car_relationship']) && $represnetative['car_relationship'] == 3) ? 'selected' : ''; ?>>3 - SIBLING</option>
                                <option value="4" <?php echo (isset($represnetative['car_relationship']) && $represnetative['car_relationship'] == 4) ? 'selected' : ''; ?>>4 - CARETAKER</option>
                                <option value="5" <?php echo (isset($represnetative['car_relationship']) && $represnetative['car_relationship'] == 5) ? 'selected' : ''; ?>>5 - OTHERS</option>
                            </select>
                            <div class="car-relationship-others-group" style="display: none;">
                                <input type="text" class="form-control car-relationship-others" name="fam_info[<?php echo $index; ?>][car_relationship_others]" value="<?php echo $member['car_relationship_others']; ?>" placeholder="SPECIFY">
                            </div>
                        </td>
                        <td><input type="text" name="fam_info[<?php echo $index; ?>][car_contact]" class="form-control" value="<?php echo $represnetative['car_contact']; ?>" required></td>
                        <td><button type="button" class="car-remove-row btn btn-danger btn-sm">-</button></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </table>

            <script>
                // NAME OF AUTHORIZED REPRESENTATIVES
                $(document).ready(function () {
                    var rowIndex = <?php echo isset($authorized_representatives) ? count($authorized_representatives) : 1; ?>;

                    function setupRow(index) {
                        $('select[name="fam_info[' + index + '][car_relationship]"]').on('change', function () {
                            toggleCARRelationshipOthers($(this));
                        });
                    }

                    // RELATIONSHIP OTHERS
                    function toggleCARRelationshipOthers(selectElement) {
                        var selectedVal = selectElement.val();
                        var othersGroup = selectElement.closest('td').find('.car-relationship-others-group');
                        var othersInput = othersGroup.find('.car-relationship-others');

                        if (selectedVal == "5") { 
                            othersGroup.show();
                            othersInput.prop('required', true);
                        } else {
                            othersGroup.hide();
                            othersInput.prop('required', false);
                        }
                    }

                    <?php if (!empty($authorized_representatives)): ?>
                    for (var i = 0; i < <?php echo count($authorized_representatives); ?>; i++) {
                        setupRow(i);
                        var selectElementRelationship = $('select[name="fam_info[' + i + '][car_relationship]"]');
                        toggleCARRelationshipOthers(selectElementRelationship);  
                    }
                    <?php endif; ?>

                    $("#car-add-row").click(function () {
                        var newRow = '<tr>' +
                            '<td><input type="text" name="fam_info[' + rowIndex + '][car_name]" class="form-control" required></td>' +
                            '<td><input type="text" name="fam_info[' + rowIndex + '][car_age]" class="form-control" required></td>' +
                            '<td><select class="form-select car-relationship-select" name="fam_info[' + rowIndex + '][car_relationship]" required>' +
                            '<option value="" disabled selected></option>' +
                            '<option value="1">1 - SPOUSE</option>' +
                            '<option value="2">2 - CHILD</option>' +
                            '<option value="3">3 - SIBLING</option>' +
                            '<option value="4">4 - CARETAKER</option>' +
                            '<option value="5">5 - OTHERS</option>' +
                            '</select>' +
                            '<div class="car-relationship-others-group" style="display: none;">' +
                            '<input type="text" class="form-control car-relationship-others" name="fam_info[' + rowIndex + '][car_relationship_others]" placeholder="SPECIFY">' +
                            '</div></td>' +
                            '<td><input type="text" name="fam_info[' + rowIndex + '][car_contact]" class="form-control" required></td>' +
                            '<td><button type="button" class="car-remove-row btn btn-danger btn-sm">-</button></td>' +
                            '</tr>';

                        $("#auth_rep_details tbody").append(newRow);
                        setupRow(rowIndex);
                        rowIndex++;
                    });

                    $(document).on("click", ".car-remove-row", function() {
                        $(this).closest("tr").remove();
                    });

                    <?php if (!empty($authorized_representatives)): ?>
                    for (var i = 0; i < <?php echo count($authorized_representatives); ?>; i++) {
                        setupRow(i); 
                    }
                    <?php endif; ?>
                        setupRow(0);
                    });
            </script>



                <div class="button-row d-flex mt-5">
                    <button class="btn btn-secondary js-btn-prev" type="button" title="Previous">Previous</button>
                    <button class="btn btn-custom button-end ml-auto js-btn-next" type="button" title="Next">Next</button>
                </div>
            </div>
        </div>
        <!--ELIGIBILITY ASSESSMENT-->
        <div class="multisteps-form_panel card-shadow p-4 rounded bg-white" data-animation="scaleIn">
            <h5 class="multisteps-form_title">III. ELIGIBILITY ASSESSMENT</h5>
            <div class="multisteps-form_content">
                <div class="row">
                    <div class="form-group col-md-12">
                        1. Applicant is Sixty (60) years old and above:
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="ea_sixty_yrs_old" id="ea_sixty_yrs_old_yes" value="1">
                            <label class="form-check-label" for="ea_sixty_yrs_old_yes">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="ea_sixty_yrs_old" id="ea_sixty_yrs_old_no" value="2">
                            <label class="form-check-label" for="ea_sixty_yrs_old_no">No</label>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group col-md-12">
                        2. Applicant is receiving any pension: <span class="text-danger fw-bold">*</span>
                        <input type="radio" name="ea_pension" id="ea_pension_yes" value="1" class="form-check-input" <?php echo (isset($message) && ($message == 'GSIS Match found. Do you want to continue?' || $message == 'PVAO Match found. Do you want to continue?')) ? 'checked' : ''; ?>>
                        <label for="ea_pension_yes">Yes</label>
                        
                        <input type="radio" name="ea_pension" id="ea_pension_no" value="2" class="form-check-input">
                        <label for="ea_pension_no">No</label>

                    </div>
                    <div class="form-group col-md-12">
                        If yes, specify: 
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_pension_sss" id="ea_pension_sss" value="1">
                            <label class="form-check-label" for="ea_pension_sss">SSS</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_pension_gsis" id="ea_pension_gsis" value="1" <?php echo (isset($message) && ($message == 'GSIS Match found. Do you want to continue?')) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="ea_pension_gsis">GSIS</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_pension_pvao" id="ea_pension_pvao" value="1" <?php echo (isset($message) && ($message == 'PVAO Match found. Do you want to continue?')) ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="ea_pension_pvao">PVAO</label>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group col-md-12">
                        3. Applicant has regular source of income / compensation and/or financial support from relatives
                        <input type="radio" name="ea_regular_income" id="ea_regular_income_yes" value="1" class="form-check-input">
                        <label for="ea_regular_income_yes">Yes</label>
                        <input type="radio" name="ea_regular_income" id="ea_regular_income_no" value="2" class="form-check-input">
                        <label for="ea_regular_income_no">No</label>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="form-group col-md-3">
                            <input type="text" class="form-control" name="ea_regular_income_specify"  id="ea_regular_income_specify" value="" placeholder="" >
                            <label class="form-label fw-bold">If yes, specify source <span class="text-danger">*</span></label>
                        </div>
                    </div>

                    <hr style="margin-top: -13px">
                    <div class="form-group col-md-12">
                        4. Applicant is frail, sickly, or with disability
                    </div>
                    <div class="form-group col-md-12">
                        4.1. Applicant has existing illness:
                        <input type="radio" name="ea_existing_illness" id="ea_existing_illness_yes" value="1" class="form-check-input">
                        <label for="ea_existing_illness_yes">Yes</label>
                        <input type="radio" name="ea_existing_illness" id="ea_existing_illness_no" value="2" class="form-check-input">
                        <label for="ea_existing_illness_no">No</label>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" name="ea_existing_illness_specify"  id="ea_existing_illness_specify" value="" placeholder="" >
                            <label class="form-label fw-bold">If yes, specify (sample: hypertension, diabetes, arthritis, etc.) <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        4.2. Applicant has disability:
                        <input type="radio" name="ea_disability" id="ea_disability_yes" value="1" class="form-check-input">
                        <label for="ea_disability_yes">Yes</label>
                        <input type="radio" name="ea_disability" id="ea_disabilitys_no" value="2" class="form-check-input">
                        <label for="ea_disabilitys_no">No</label>
                    </div>
                    <div class="form-group col-md-12">
                        <p class="font_size">If yes, tick the corresponding box:</p>
                    </div>
                    <div class="form-group col-md-12 mb-5">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_disability_deaf" id="ea_disability_deaf" value="1">
                            <label class="form-check-label" for="ea_disability_deaf">Deaf/Hard of Hearing</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_disability_intellectual" id="ea_disability_intellectual" value="1">
                            <label class="form-check-label" for="ea_disability_intellectual">Intellectual</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_disability_learning" id="ea_disability_learning" value="1">
                            <label class="form-check-label" for="ea_disability_learning">Learning</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_disability_mental" id="ea_disability_mental" value="1">
                            <label class="form-check-label" for="ea_disability_mental">Mental</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_disability_psychosocial" id="ea_disability_psychosocial" value="1">
                            <label class="form-check-label" for="ea_disability_psychosocial">Psychosocial</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_disability_cancer" id="ea_disability_cancer" value="1">
                            <label class="form-check-label" for="ea_disability_cancer">Non-apparent cancer</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_disability_visual" id="ea_disability_visual" value="1">
                            <label class="form-check-label" for="ea_disability_visual">Non-apparent visual</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_disability_speech_and_language" id="ea_disability_speech_and_language" value="1">
                            <label class="form-check-label" for="ea_disability_speech_and_language">Non-apparent speech and language impairment</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_disability_rare_disease" id="ea_disability_rare_disease" value="1">
                            <label class="form-check-label" for="ea_disability_rare_disease">Non-apparent rare disease</label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        4.3. With difficulty in doing Activity of Daily Living:
                        <input type="radio" name="ea_daily_living" id="ea_daily_living_yes" value="1" class="form-check-input">
                        <label for="ea_daily_living_yes">Yes</label>
                        <input type="radio" name="ea_daily_living" id="ea_daily_living_no" value="2" class="form-check-input">
                        <label for="ea_daily_living_no">No</label>
                    </div>
                    
                    <div class="form-group col-md-12">
                        <p class="font_size">If yes, tick the corresponding box:</p>
                    </div>
                    <div class="form-group col-md-12 mb-0">
                        <div class="form-check-inline">
                            <span>ADLs:</span>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_daily_living_bathing" id="ea_daily_living_bathing" value="1">
                            <label class="form-check-label" for="ea_daily_living_bathing">Bathing</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_daily_living_dressing" id="ea_daily_living_dressing" value="1">
                            <label class="form-check-label" for="ea_daily_living_dressing">Dressing</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_daily_living_grooming" id="ea_daily_living_grooming" value="1">
                            <label class="form-check-label" for="ea_daily_living_grooming">Grooming</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_daily_living_eating" id="ea_daily_living_eating" value="1">
                            <label class="form-check-label" for="ea_daily_living_eating">Eating</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_daily_living_toileting" id="ea_daily_living_toileting" value="1">
                            <label class="form-check-label" for="ea_daily_living_toileting">Toileting</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_daily_living_transferring" id="ea_daily_living_transferring" value="1">
                            <label class="form-check-label" for="ea_daily_living_transferring">Transferring</label>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="form-check-inline">
                            <span>IADLs:</span>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_daily_living_cooking" id="ea_daily_living_cooking" value="1">
                            <label class="form-check-label" for="ea_daily_living_cooking">Cooking </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_daily_living_cleaning" id="ea_daily_living_cleaning" value="1">
                            <label class="form-check-label" for="ea_daily_living_cleaning">Cleaning </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_daily_living_managing_finance" id="ea_daily_living_managing_finance" value="1">
                            <label class="form-check-label" for="ea_daily_living_managing_finance">Managing Finances</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_daily_living_grocery_shopping" id="ea_daily_living_grocery_shopping" value="1">
                            <label class="form-check-label" for="ea_daily_living_grocery_shopping">Grocery shopping</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="ea_daily_living_managing_medications" id="ea_daily_living_managing_medications" value="1">
                            <label class="form-check-label" for="ea_daily_living_managing_medications">Managing medications</label>
                        </div>
                    </div>
                
                </div>



                <script>
                    // SOURCE INCOME
                        document.addEventListener('DOMContentLoaded', function() {
                            const incomeYes = document.getElementById('ea_regular_income_yes');
                            const incomeNo = document.getElementById('ea_regular_income_no');
                            const specifyInput = document.getElementById('ea_regular_income_specify');

                            const inputRadiosCheckboxIncome = [
                                'ea_existing_illness_yes', 'ea_existing_illness_no', 
                                'ea_disability_yes', 'ea_disabilitys_no', 
                                'ea_disability_deaf', 'ea_disability_intellectual', 
                                'ea_disability_learning', 'ea_disability_mental', 
                                'ea_disability_psychosocial', 'ea_disability_cancer', 
                                'ea_disability_speech_and_language', 'ea_disability_visual', 
                                'ea_disability_rare_disease', 'ea_daily_living_yes', 
                                'ea_daily_living_no', 'ea_daily_living_bathing', 
                                'ea_daily_living_dressing', 'ea_daily_living_grooming', 
                                'ea_daily_living_eating', 'ea_daily_living_toileting', 
                                'ea_daily_living_transferring', 'ea_daily_living_cooking', 
                                'ea_daily_living_cleaning', 'ea_daily_living_managing_finance', 
                                'ea_daily_living_grocery_shopping', 'ea_daily_living_managing_medications'
                            ];
                            
                            const inputsTextIncome = document.getElementById('ea_existing_illness_specify');

                            function toggleSpecifyInput(enable) {
                                specifyInput.disabled = !enable;
                                specifyInput.required = enable;
                                if (!enable) {
                                    specifyInput.value = ''; 
                                }
                            }

                            function toggleOtherFields(disable) {
                                inputRadiosCheckboxIncome.forEach(function(id) {
                                    const radio = document.getElementById(id);
                                    if (radio) {
                                        radio.disabled = disable;
                                        if (disable) {
                                            radio.checked = false;
                                        }
                                    }
                                });

                                inputsTextIncome.disabled = disable;
                                if (disable) {
                                    inputsTextIncome.value = ''; 
                                }
                            }

                            function updateState() {
                                if (incomeYes.checked) {
                                    toggleSpecifyInput(true);  
                                    toggleOtherFields(true);  
                                } else {
                                    toggleSpecifyInput(false);
                                    toggleOtherFields(false);   
                                }
                            }

                            incomeYes.addEventListener('change', updateState);
                            incomeNo.addEventListener('change', updateState);

                            setTimeout(function() {
                                updateState();
                            }, 100);  
                        });

                </script>




                <script>
                    // AUTO CALCULATE AGE FROM BIRTHDATE
                    function submitBday() {
                        var Bdate = document.getElementById('bday').value;
                        if (Bdate) {
                        var Bday = +new Date(Bdate);
                        var age = ~~((Date.now() - Bday) / (31557600000)); 
                        document.getElementById('age').value = age; 
                        updateRadioButtons(); 
                        }
                    }

                    // IF AGE IS >= 60 YES ELSE NO
                    function updateRadioButtons() {
                        var age = document.getElementById('age').value;
                        var yesRadio = document.getElementById('ea_sixty_yrs_old_yes');
                        var noRadio = document.getElementById('ea_sixty_yrs_old_no');

                        if (age !== '') {
                            age = parseInt(age, 10);

                            if (age >= 60) {
                                yesRadio.checked = true;
                                noRadio.checked = false;
                                makeRadioButtonsUnclickable(true); 
                                toggleRadioInputs(false); 
                            } else {
                                noRadio.checked = true;
                                yesRadio.checked = false;
                                makeRadioButtonsUnclickable(true); 
                                toggleRadioInputs(true); 
                                clearRadioInputs(); 
                            }
                        } else {
                            yesRadio.checked = false;
                            noRadio.checked = false;
                            makeRadioButtonsUnclickable(false); 
                            toggleRadioInputs(false); 
                        }
                    }

                    // IF AGE IS <= 59 DISABLED THE NEXT RADIO
                    function toggleRadioInputs(disable) {
                        var radioGroups = ['ea_pension_yes', 'ea_pension_no', 'ea_pension_sss', 'ea_pension_gsis', 'ea_pension_pvao', 'ea_regular_income_yes', 'ea_regular_income_no', 'ea_existing_illness_yes', 'ea_existing_illness_no', 'ea_regular_income_specify', 'ea_existing_illness_specify', 'ea_disability_yes', 'ea_disabilitys_no', 'ea_disability_deaf', 'ea_disability_intellectual', 'ea_disability_learning', 'ea_disability_mental', 'ea_disability_psychosocial', 'ea_disability_cancer', 'ea_disability_speech_and_language', 'ea_disability_visual', 'ea_disability_rare_disease', 'ea_daily_living_yes', 'ea_daily_living_no', 'ea_daily_living_bathing', 'ea_daily_living_dressing', 'ea_daily_living_grooming', 'ea_daily_living_eating', 'ea_daily_living_toileting', 'ea_daily_living_transferring', 'ea_daily_living_cooking', 'ea_daily_living_cleaning', 'ea_daily_living_managing_finance', 'ea_daily_living_grocery_shopping', 'ea_daily_living_managing_medications'];
                        radioGroups.forEach(function(id) {
                            var input = document.getElementById(id);
                            input.disabled = disable; 
                        });
                    }

                    // RESET THE SELECTED ON RADIO AND CLEAR TEXT
                    function clearRadioInputs() {
                        var inputs = document.querySelectorAll('input[name="ea_pension"], input[name="ea_regular_income"], input[name="ea_existing_illness"], input[name="ea_pension_sss"], input[name="ea_pension_gsis"], input[name="ea_pension_pvao"], input[name="ea_regular_income_yes"], input[name="ea_regular_income_no"], input[name="ea_existing_illness_yes"]');
                        inputs.forEach(function(input) {
                            input.checked = false;
                        });

                        var regularIncomeSpecify = document.getElementById('ea_regular_income_specify');
                        if (regularIncomeSpecify) {
                            regularIncomeSpecify.value = ''; 
                        }
                    }
                    

                    // UNCLICKABLE RADIO
                    function makeRadioButtonsUnclickable(makeUnclickable) {
                        var yesRadio = document.getElementById('ea_sixty_yrs_old_yes');
                        var noRadio = document.getElementById('ea_sixty_yrs_old_no');

                        if (makeUnclickable) {
                            yesRadio.classList.add('unclickable');
                            noRadio.classList.add('unclickable');
                        } else {
                            yesRadio.classList.remove('unclickable');
                            noRadio.classList.remove('unclickable');
                        }
                    }

                    document.getElementById('ea_sixty_yrs_old_yes').addEventListener('change', function() {
                        toggleRadioInputs(false); 
                    });

                    document.getElementById('ea_sixty_yrs_old_no').addEventListener('change', function() {
                        toggleRadioInputs(true); 
                        clearRadioInputs(); 
                    });

                    document.getElementById('bday').addEventListener('change', submitBday);

                    window.onload = function() {
                        submitBday(); 
                    };


                    // OTHER PENSION 
                    document.addEventListener('DOMContentLoaded', function() {
                    const pensionYes = document.getElementById('ea_pension_yes');
                    const pensionNo = document.getElementById('ea_pension_no');
                    const pensionCheckboxes = ['ea_pension_sss', 'ea_pension_gsis', 'ea_pension_pvao'];
                    const inputRadiosCheckbox = ['ea_regular_income_yes', 'ea_regular_income_no', 'ea_existing_illness_yes', 'ea_existing_illness_no', 'ea_disability_yes', 'ea_disabilitys_no', 'ea_disability_deaf', 'ea_disability_intellectual', 'ea_disability_learning', 'ea_disability_mental', 'ea_disability_psychosocial', 'ea_disability_cancer', 'ea_disability_speech_and_language', 'ea_disability_visual', 'ea_disability_rare_disease', 'ea_daily_living_yes', 'ea_daily_living_no', 'ea_daily_living_bathing', 'ea_daily_living_dressing', 'ea_daily_living_grooming', 'ea_daily_living_eating', 'ea_daily_living_toileting', 'ea_daily_living_transferring', 'ea_daily_living_cooking', 'ea_daily_living_cleaning', 'ea_daily_living_managing_finance', 'ea_daily_living_grocery_shopping', 'ea_daily_living_managing_medications'];
                    const inputsText = [document.getElementById('ea_regular_income_specify'), document.getElementById('ea_existing_illness_specify')];

                    function togglePensionCheckboxes(enable) {
                        pensionCheckboxes.forEach(function(id) {
                            const checkbox = document.getElementById(id);
                            checkbox.disabled = !enable;
                            checkbox.required = enable;
                            if (!enable) {
                                checkbox.checked = false;
                            }
                        });
                    }

                    function toggleOtherFields(disable) {
                        inputRadiosCheckbox.forEach(function(id) {
                            const radio = document.getElementById(id);
                            radio.disabled = disable;
                            if (disable) {
                                radio.checked = false;
                            }
                        });
                        
                        inputsText.forEach(function(input) {
                            if (input) {
                                input.disabled = disable;
                            }
                        });

                    }

                    function updateState() {
                        if (pensionYes.checked) {
                            togglePensionCheckboxes(true); 
                            toggleOtherFields(true); 
                        } else {
                            togglePensionCheckboxes(false); 
                            toggleOtherFields(false); 
                        }
                    }

                    pensionYes.addEventListener('change', updateState);
                    pensionNo.addEventListener('change', updateState);

                    setTimeout(function() {
                        if (pensionYes.checked) {
                            updateState();  
                        }
                    }, 100);  
                });


                </script>

                
                <div class="row">
                    <div class="button-row d-flex mt-5 col-12">
                        <button class="btn btn-secondary js-btn-prev" type="button" title="Previous">Previous</button>
                        <button class="btn btn-custom button-end ml-auto js-btn-next" type="button" title="Next">Next</button>
                    </div>
                </div>
            </div>
        </div>
        <!--RECOMMENDATION-->
        <div class="multisteps-form_panel card-shadow p-4 rounded bg-white" data-animation="scaleIn">
          <h5 class="multisteps-form_title">IV. RECOMMENDATION</h5>
          <div class="multisteps-form_content">
            <!-- recommendation form here -->
            <div class="button-row d-flex mt-5">
              <button class="btn btn-secondary js-btn-prev" type="button" title="Previous">Previous</button>
              <button class="btn btn-primary button-end ml-auto" type="button" title="Submit">Submit</button>
            </div>
          </div>
        </div>
      </form>
     </div>
  </div>
</div>


<script>
    // MULTI-STEP FORM
    const DOMstrings = {
    stepsBtnClass: 'multisteps-form_progress-btn',
    stepsBtns: document.querySelectorAll(`.multisteps-form_progress-btn`),
    stepsBar: document.querySelector('.multisteps-form_progress'),
    stepsForm: document.querySelector('.multisteps-form_form'),
    stepFormPanelClass: 'multisteps-form_panel',
    stepFormPanels: document.querySelectorAll('.multisteps-form_panel'),
    stepPrevBtnClass: 'js-btn-prev',
    stepNextBtnClass: 'js-btn-next'
    };

    const removeClasses = (elemSet, className) => {
        elemSet.forEach(elem => {
            elem.classList.remove(className);
        });
    };

    const findParent = (elem, parentClass) => {
        let currentNode = elem;
        while (!currentNode.classList.contains(parentClass)) {
            currentNode = currentNode.parentNode;
        }
        return currentNode;
    };

    const getActiveStep = elem => {
        return Array.from(DOMstrings.stepsBtns).indexOf(elem);
    };

    const setActiveStep = activeStepNum => {
        removeClasses(DOMstrings.stepsBtns, 'js-active');
        DOMstrings.stepsBtns.forEach((elem, index) => {
            if (index <= activeStepNum) {
                elem.classList.add('js-active');
            }
        });
    };

    const getActivePanel = () => {
        let activePanel;
        DOMstrings.stepFormPanels.forEach(elem => {
            if (elem.classList.contains('js-active')) {
                activePanel = elem;
            }
        });
        return activePanel;
    };

    const setActivePanel = (activePanelNum, animationType) => {
        removeClasses(DOMstrings.stepFormPanels, 'js-active');
        DOMstrings.stepFormPanels.forEach((elem, index) => {
            if (index === activePanelNum) {
                elem.classList.add('js-active');
                elem.setAttribute('data-animation', animationType);
                setFormHeight(elem);
            } else {
                elem.setAttribute('data-animation', '');
            }
        });
    };

    const formHeight = activePanel => {
        const activePanelHeight = activePanel.offsetHeight;
        DOMstrings.stepsForm.style.height = `${activePanelHeight}px`;
    };

    const setFormHeight = () => {
        const activePanel = getActivePanel();
        formHeight(activePanel);
    };

    const areRequiredFieldsFilled = (panel) => {
    const requiredFields = panel.querySelectorAll('[required]');
        return Array.from(requiredFields).every(field => {
            if (field.type === 'radio') {
                // Check if at least one radio button in the group is selected
                const radioGroup = panel.querySelectorAll(`[name="${field.name}"]`);
                return Array.from(radioGroup).some(radio => radio.checked);
            } else {
                // For other fields, just check if they're filled
                return field.value.trim() !== '';
            }
        });
    };


    DOMstrings.stepsBar.addEventListener('click', e => {
        const eventTarget = e.target;
        if (!eventTarget.classList.contains(`${DOMstrings.stepsBtnClass}`)) {
            return;
        }
        const activeStep = getActiveStep(eventTarget);
        setActiveStep(activeStep);
        setActivePanel(activeStep, 'scaleIn');
    });

    DOMstrings.stepsForm.addEventListener('click', e => {
        const eventTarget = e.target;
        if (!(eventTarget.classList.contains(`${DOMstrings.stepPrevBtnClass}`) || eventTarget.classList.contains(`${DOMstrings.stepNextBtnClass}`))) {
            return;
        }
        const activePanel = findParent(eventTarget, `${DOMstrings.stepFormPanelClass}`);
        let activePanelNum = Array.from(DOMstrings.stepFormPanels).indexOf(activePanel);
        const animationType = eventTarget.classList.contains(`${DOMstrings.stepPrevBtnClass}`) ? 'js-btn-prev' : 'js-btn-next';

        if (eventTarget.classList.contains(`${DOMstrings.stepPrevBtnClass}`)) {
            activePanelNum--;
        } else {
            if (areRequiredFieldsFilled(activePanel)) {
                activePanelNum++;
            } else {
                alert('Please fill out all required fields.');
                return; 
            }
        }

        setActiveStep(activePanelNum);
        setActivePanel(activePanelNum, animationType);
    });

    window.addEventListener('load', setFormHeight, false);
    window.addEventListener('resize', setFormHeight, false);
                

    //LIVING ARRANGEMENT SPECIFY
    document.addEventListener('DOMContentLoaded', function () {
        var livingArrangementSelect = document.getElementById('living_arrangement');
        var othersInputGroup = document.getElementById('living_arrangement_others_group');
        var othersInput = document.getElementById('living_arrangement_others');

        function toggleOthersInput() {
            if (livingArrangementSelect.value == "4") {
                othersInputGroup.style.display = 'block';
                othersInput.setAttribute('required', 'required');
            } else {
                othersInputGroup.style.display = 'none';
                othersInput.removeAttribute('required');
            }
        }

        toggleOthersInput();

        livingArrangementSelect.addEventListener('change', toggleOthersInput);
    });

    // SECTOR SPECIFY
    document.addEventListener('DOMContentLoaded', function () {
        var SectorSelect = document.getElementById('sector');
        var othersInputGroup = document.getElementById('ip_specify_group');
        var othersInput = document.getElementById('ip_specify');

        function toggleOthersInput() {
            if (SectorSelect.value == "1") {
                othersInputGroup.style.display = 'block';
                othersInput.setAttribute('required', 'required');
            } else {
                othersInputGroup.style.display = 'none';
                othersInput.removeAttribute('required');
            }
        }

        toggleOthersInput();
        SectorSelect.addEventListener('change', toggleOthersInput);
    });

    // CIVIL STATUS
    document.addEventListener('DOMContentLoaded', function () {
        var CivilStatusSelect = document.getElementById('civil_status');
        var othersInputGroup = document.getElementById('cs_others_group');
        var csMarriedGroup = document.getElementById('cs_married_group');

        var spouseLname = document.getElementById('cs_lname');
        var spouseFname = document.getElementById('cs_fname');
        var spouseAddress = document.getElementById('cs_address');

        function toggleInputs() {
            if (CivilStatusSelect.value == "6") {
                othersInputGroup.style.display = 'block';
                othersInputGroup.querySelector('input').setAttribute('required', 'required');
            } else {
                othersInputGroup.style.display = 'none';
                othersInputGroup.querySelector('input').removeAttribute('required');
            }

            if (CivilStatusSelect.value == "2" || CivilStatusSelect.value == "4") { 
                csMarriedGroup.style.display = 'block';
                spouseLname.setAttribute('required', 'required');
                spouseFname.setAttribute('required', 'required');
                spouseAddress.setAttribute('required', 'required');
            } else {
                csMarriedGroup.style.display = 'none';
                spouseLname.removeAttribute('required');
                spouseFname.removeAttribute('required');
                spouseAddress.removeAttribute('required');
            }
        }

        toggleInputs(); 

        CivilStatusSelect.addEventListener('change', toggleInputs);
    });


</script>

<script>
    //LGU
    $('#lgu_region').change(function() {
            const region_id = $('#lgu_region').val();
        if (region_id != '') {
            $.ajax({
                url: "<?php echo base_url(); ?>Address/province",
                method: "POST",
                data: { region_id: region_id },
                success: function(data) {
                    $('#lgu_province').html(data);
                    $('#lgu_city_municipality').html('<option value=""></option>');
                }
            });
        } else {
            $('#lgu_province').html('<option value=""></option>');
            $('#lgu_city_municipality').html('<option value=""></option>');
        }
    });

    $('#lgu_province').change(function() {
        const province_id = $('#lgu_province').val();
        if (province_id != '') {
            $.ajax({
                url: "<?php echo base_url(); ?>Address/municipality",
                method: "POST",
                data: { province_id: province_id },
                success: function(data) {
                    $('#lgu_city_municipality').html(data);
                }
            });
        } else {
            $('#lgu_city_municipality').html('<option value=""></option>');
        }
    });


    //CLIENT PERMANENT ADDRESS
    $('#perm_region').change(function() {
        const region_id = $('#perm_region').val();
        if (region_id != '') {
            $.ajax({
                url: "<?php echo base_url(); ?>Address/province",
                method: "POST",
                data: { region_id: region_id },
                success: function(data) {
                    $('#perm_prov').html(data);
                    $('#perm_muni').html('<option value=""></option>');
                    $('#perm_brgy').html('<option value=""></option>');
                }
            });
        } else {
            $('#perm_prov').html('<option value=""></option>');
            $('#perm_muni').html('<option value=""></option>');
            $('#perm_brgy').html('<option value=""></option>');
        }
    });

    $('#perm_prov').change(function() {
        const province_id = $('#perm_prov').val();
        if (province_id != '') {
            $.ajax({
                url: "<?php echo base_url(); ?>Address/municipality",
                method: "POST",
                data: { province_id: province_id },
                success: function(data) {
                    $('#perm_muni').html(data);
                    $('#perm_brgy').html('<option value=""></option>');
                }
            });
        } else {
            $('#perm_muni').html('<option value=""></option>');
            $('#perm_brgy').html('<option value=""></option>');
        }
    });

    $('#perm_muni').change(function() {
        const city_id = $('#perm_muni').val();
        if (city_id != '') {
            $.ajax({
                url: "<?php echo base_url(); ?>Address/barangay",
                method: "POST",
                data: { city_id: city_id },
                success: function(data) {
                    $('#perm_brgy').html(data);
                }
            });
        } else {
            $('#perm_brgy').html('<option value=""></option>');
        }
    });

    //CLIENT PRESENT ADDRESS
    $('#pre_region').change(function() {
        const region_id = $('#pre_region').val();
        if (region_id != '') {
            $.ajax({
                url: "<?php echo base_url(); ?>Address/province",
                method: "POST",
                data: { region_id: region_id },
                success: function(data) {
                    $('#pre_prov').html(data);
                    $('#pre_muni').html('<option value=""></option>');
                    $('#pre_brgy').html('<option value=""></option>');
                }
            });
        } else {
            $('#pre_prov').html('<option value=""></option>');
            $('#pre_muni').html('<option value=""></option>');
            $('#pre_brgy').html('<option value=""></option>');
        }
    });

    $('#pre_prov').change(function() {
        const province_id = $('#pre_prov').val();
        if (province_id != '') {
            $.ajax({
                url: "<?php echo base_url(); ?>Address/municipality",
                method: "POST",
                data: { province_id: province_id },
                success: function(data) {
                    $('#pre_muni').html(data);
                    $('#pre_brgy').html('<option value=""></option>');
                }
            });
        } else {
            $('#pre_muni').html('<option value=""></option>');
            $('#pre_brgy').html('<option value=""></option>');
        }
    });

    $('#pre_muni').change(function() {
        const city_id = $('#pre_muni').val();
        if (city_id != '') {
            $.ajax({
                url: "<?php echo base_url(); ?>Address/barangay",
                method: "POST",
                data: { city_id: city_id },
                success: function(data) {
                    $('#pre_brgy').html(data);
                }
            });
        } else {
            $('#pre_brgy').html('<option value=""></option>');
        }
    });


    // COPY THE PERMANENT ADDRESS
    $(document).ready(function() {
    function copyPermanentAddress() {
        if ($('#copyResult').is(':checked')) {
            $('#pre_region').val($('#perm_region').val()).trigger('change');
            updateHasValue('#pre_region');

            setTimeout(function() {
                $('#pre_prov').val($('#perm_prov').val()).trigger('change');
                updateHasValue('#pre_prov');
            }, 500); 

            setTimeout(function() {
                $('#pre_muni').val($('#perm_muni').val()).trigger('change');
                updateHasValue('#pre_muni');
            }, 1000); 

            setTimeout(function() {
                $('#pre_brgy').val($('#perm_brgy').val());
                updateHasValue('#pre_brgy');
            }, 1500); 

            $('#pre_sitio').val($('#perm_sitio').val());
            $('#pre_street').val($('#perm_street').val());
            updateHasValue('#pre_sitio');
            updateHasValue('#pre_street');
        } else {
            $('#pre_region').val('').trigger('change'); 
            $('#pre_prov').html('<option value="" disabled selected></option>');
            $('#pre_muni').html('<option value="" disabled selected></option>');
            $('#pre_brgy').html('<option value="" disabled selected></option>'); 
            $('#pre_sitio').val(''); 
            $('#pre_street').val('');
            removeHasValue('#pre_region');
            removeHasValue('#pre_prov');
            removeHasValue('#pre_muni');
            removeHasValue('#pre_brgy');
            removeHasValue('#pre_sitio');
            removeHasValue('#pre_street');
        }
    }

    function updateHasValue(selector) {
        if ($(selector).val() !== '') {
            $(selector).addClass('has-value');
        } else {
            $(selector).removeClass('has-value');
        }
    }

    function removeHasValue(selector) {
        $(selector).removeClass('has-value');
    }

    $('#copyResult').change(function() {
        copyPermanentAddress();
    });

    if ($('#copyResult').is(':checked')) {
        copyPermanentAddress();
    }
});

</script>
