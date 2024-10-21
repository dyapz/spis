<?php 
    $csrf = array(
        'name' => $this->security->get_csrf_token_name(),
        'hash' => $this->security->get_csrf_hash()
    );

    foreach($client_information as $row) { 
        $client_id = $row->client_id;
        $client_lname = $row->client_lname;
        $client_fname = $row->client_fname;
        $client_mname = $row->client_mname;
        $client_ename = $row->client_ename;
        $osca_id = $row->osca_id;
        $philsys_id = $row->philsys_id;
        $pantawid_y_n = $row->pantawid_y_n;
        $mothers_maiden_lname = $row->mothers_maiden_lname;
        $mothers_maiden_fname = $row->mothers_maiden_fname;
        $mothers_maiden_mname = $row->mothers_maiden_mname;
        $perm_region = $row->perm_region;
        $perm_prov = $row->perm_prov;
        $province_name = $row->province_name;
        $perm_muni = $row->perm_muni;
        $city_name = $row->city_name;
        $perm_brgy = $row->perm_brgy;
        $brgy_name = $row->brgy_name;
        $perm_sitio = $row->perm_sitio;
        $perm_street = $row->perm_street;
        $same_address = $row->same_address;
        $pre_region = $row->pre_region;
        $pre_prov = $row->pre_prov;
        $pre_province_name = $row->pre_province_name;
        $pre_muni = $row->pre_muni;
        $pre_city_name = $row->pre_city_name;
        $pre_brgy = $row->pre_brgy;
        $pre_brgy_name = $row->pre_brgy_name;
        $pre_sitio = $row->pre_sitio;
        $pre_street = $row->pre_street;
        $birthdate = $row->birthdate;
        $birth_place = $row->birth_place;
        $sex = $row->sex;
        $civil_status = $row->civil_status;
        $cs_others = $row->cs_others;
        $living_arrangement = $row->living_arrangement;
        $living_arrangement_others = $row->living_arrangement_others;
        $sector = $row->sector;
        $ip_specify = $row->ip_specify;
        $remarks = $row->remarks;
        $validation_id = $row->validation_id;
        
        if($row->client_status != 1){
            $styleDisplay = 'style="display: none"';

        }else{
            $styleDisplay = '';

        } 

        if($row->client_status != '5'){
            Redirect('auth');
        }
    }
    
?>

<style>
    body {font-family: Arial;}

    .tab {
    overflow: hidden;
    border: 1px solid #ccc;
    background-color: #47a1bd;
    }

    .tab button {
    background-color: inherit;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 4px 16px;
    transition: 0.3s;
    font-size: 15px;
    color: #fff;
    }

    .tab button:hover {
    background-color: #337d94;
    }

    .tab button.active {
    background-color: #337d94;
    }

    .tabcontent {
    display: none;
    padding: 6px 12px;
    border: 1px solid #ccc;
    border-top: none;
    max-height: 700px; 
    overflow-y: auto;  
    }

    h5{
        font-size: 1rem;
    }

    .btn-back {
    background-color: #2c9ff2;
    color: white;
    padding: 1px 7px 1px 7px
    }

    .btn-back:hover {
    background-color: #1f6aa1;
    color: white;
    }

    .btn-edit {
    background-color: #47a1bd;
    color: white;
    padding: 1px 7px 1px 7px
    }

    .btn-edit:hover {
    background-color: #337d94;
    color: white;
    }

    .btn-edit-cancel {
    background-color: #eb742a;
    color: white;
    padding: 1px 7px 1px 7px
    }

    .btn-edit-cancel:hover {
    background-color: #c26125;
    color: white;
    }

    .btn-replace {
    background-color: #24bd9c;
    color: white;
    padding: 1px 7px 1px 7px
    }

    .btn-replace:hover {
    background-color: #198a71;
    color: white;
    }
</style>

<div class="container-fluid d-flex justify-content-center">
    <div class="card card-shadow my-4" style="width: 75rem;">
        <div class="card-header">Client Profile</div>
        <div class="card-body">

            <div class="tab">
                <button class="tablinks" onclick="openCity(event, 'IndentifyingInformation')">IDENTIFYING INFORMATION</button>
                <button class="tablinks" onclick="openCity(event, 'FamilyInformation')">FAMILY INFORMATION</button>
                <button class="tablinks" onclick="openCity(event, 'Remarks')">REMARKS</button>
            </div>

            <div id="IndentifyingInformation" class="tabcontent">
                <div class="mb-3 mt-2">
                    <a href="<?php echo base_url().'supervisor/client/validation'; ?>" class="btn btn-back hvr-float-shadow"><i class="fa-solid fa-caret-left"></i> Back</a>
                </div>

                    <div class="row">
                        <label class="label mb-3 fw-bold">NAME:</label><br>
                        <div class="form-group col-md-3">
                            <input type="text" class="form-control form-custom identifying_information_edit" pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed" name="client_lname" value="<?php echo set_value('client_lname', isset($client_lname) ? $client_lname : '');?>"  placeholder="" required readonly>
                            <label class="form-label fw-bold">LAST NAME <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-group col-md-3">
                            <input type="text" class="form-control form-custom identifying_information_edit" pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed" name="client_fname" value="<?php echo set_value('client_fname', isset($client_fname) ? $client_fname : ''); ?>" placeholder="" required readonly>
                            <label class="form-label fw-bold">FIRST NAME <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-group col-md-3">
                            <input type="text" class="form-control form-custom identifying_information_edit" pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed" name="client_mname" value="<?php echo set_value('client_mname', isset($client_mname) ? $client_mname : '');?>" placeholder="" readonly>
                            <label class="form-label fw-bold">MIDDLE NAME</label>
                        </div>

                        <div class="form-group col-md-3">
                            <input type="text" class="form-control form-custom identifying_information_edit" pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed" name="client_ename" value="<?php echo set_value('client_ename', isset($client_ename) ? $client_ename : '');?>" placeholder="" readonly>
                            <label class="form-label fw-bold">EXT. NAME</label>
                        </div>

                        <div class="form-group col-md-4">
                            <input type="text" class="form-control form-custom identifying_information_edit" pattern="[A-Za-z0-9\s]+" title="Only letters, numbers, and spaces are allowed" name="osca_id" value="<?php echo set_value('osca_id', isset($osca_id) ? $osca_id : '');?>" placeholder="" required readonly>
                            <label class="form-label fw-bold">OSCA ID No. <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-group col-md-4">
                            <input type="text" class="form-control form-custom identifying_information_edit" pattern="[A-Za-z0-9\s]+" title="Only letters, numbers, and spaces are allowed" name="philsys_id" value="<?php echo set_value('philsys_id', isset($philsys_id) ? $philsys_id : '');?>" placeholder="" readonly>
                            <label class="form-label fw-bold">PhilSys. ID No.</label>
                        </div>
                    
                        <div class="form-group col-md-4">
                            Pantawid Beneficiary (Benepisyaryo ng 4Ps): <span class="text-danger">*</span>
                            <div class="radio-btn-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input identifying_information_edit" type="radio" name="pantawid_y_n" id="4psyes" value="1" <?php echo (isset($pantawid_y_n) && $pantawid_y_n == 1) ? 'checked' : ''; ?> required disabled>
                                    <label class="form-check-label" for="4psyes">Yes</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input identifying_information_edit" type="radio" name="pantawid_y_n" id="4psno" value="2" <?php echo (isset($pantawid_y_n) && $pantawid_y_n == 2) ? 'checked' : ''; ?> disabled>
                                    <label class="form-check-label" for="4psno">No</label>
                                </div>
                            </div>
                        </div>

                        <label class="label mb-3 fw-bold">MOTHER'S MAIDEN NAME:</label><br>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control form-custom identifying_information_edit" pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed"   name="mothers_maiden_lname" value="<?php echo set_value('mothers_maiden_lname', isset($mothers_maiden_lname) ? $mothers_maiden_lname : '');?>" placeholder="" required readonly>
                            <label class="form-label fw-bold">LAST NAME <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-group col-md-4">
                            <input type="text" class="form-control form-custom identifying_information_edit" pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed"   name="mothers_maiden_fname" value="<?php echo set_value('mothers_maiden_fname', isset($mothers_maiden_fname) ? $mothers_maiden_fname : '');?>" placeholder="" required readonly>
                            <label class="form-label fw-bold">FIRST NAME <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-group col-md-4">
                            <input type="text" class="form-control form-custom identifying_information_edit" pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed"   name="mothers_maiden_mname" value="<?php echo set_value('mothers_maiden_mname', isset($mothers_maiden_mname) ? $mothers_maiden_mname : '');?>" placeholder="" readonly>
                            <label class="form-label fw-bold">MIDDLE NAME</label>
                        </div>

                        <label class="label mb-3 fw-bold">PERMANENT ADDRESS:</label><br>
                        <div class="form-group col-md-4">
                            <select name="perm_region" id="perm_region" class="form-select <?php echo (isset($perm_region) && $perm_region == $perm_region) ? 'has-value' : ''; ?> identifying_information_edit" required disabled>
                                <option value="" disabled selected></option>
                                <?php foreach($region as $row): ?>
                                    <option value="<?php echo $row->region_id; ?>" 
                                            <?php echo (isset($perm_region) && $perm_region == $row->region_id) ? 'selected' : ''; ?>>
                                        <?php echo $row->region_name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <label for="perm_region" class="form-label fw-bold">REGION <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-group col-md-4">
                            <select class="form-select <?php echo (isset($perm_prov) && $perm_prov == $perm_prov) ? 'has-value' : ''; ?> identifying_information_edit" name="perm_prov" id="perm_prov" required disabled>
                                <option value="<?php echo $perm_prov; ?>"><?php echo $province_name; ?></option>
                            </select>
                            <label class="form-label fw-bold">PROVINCE <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-group col-md-4">
                            <select class="form-select <?php echo (isset($perm_muni) && $perm_muni == $perm_muni) ? 'has-value' : ''; ?> identifying_information_edit" name="perm_muni" id="perm_muni" required disabled>
                                <option value="<?php echo $perm_muni; ?>"><?php echo $city_name; ?></option>
                            </select>
                            <label class="form-label fw-bold">CITY/MUNICIPALITY <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-group col-md-4">
                            <select class="form-select <?php echo (isset($perm_brgy) && $perm_brgy == $perm_brgy) ? 'has-value' : ''; ?> identifying_information_edit" name="perm_brgy" id="perm_brgy" required disabled>
                                <option value="<?php echo $perm_brgy; ?>"><?php echo $brgy_name; ?></option>
                            </select>
                            <label class="form-label fw-bold">BARANGAY <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-group col-md-4">
                            <input type="text" class="form-control form-custom identifying_information_edit" pattern="[A-Za-z0-9\s]+" title="Only letters, numbers, and spaces are allowed" name="perm_sitio" id="perm_sitio" value="<?php echo set_value('perm_sitio', isset($perm_sitio) ? $perm_sitio : '');?>" placeholder="" required readonly>
                            <label class="form-label fw-bold">HOUSE NO./ PUROK <span class="text-danger">*</span></label>
                        </div>
                        
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control form-custom identifying_information_edit" pattern="[A-Za-z0-9\s]+" title="Only letters, numbers, and spaces are allowed" name="perm_street" id="perm_street" value="<?php echo set_value('perm_street', isset($perm_street) ? $perm_street : '');?>" placeholder="" required readonly>
                            <label class="form-label fw-bold">STREET <span class="text-danger">*</span></label>
                        </div>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input identifying_information_edit" type="checkbox" value="1" id="copyResult" name="same_address" <?php echo (isset($same_address) && $same_address == 1) ? 'checked' : ''; ?> disabled>
                        <label class="form-check-label text-secondary fw-bold" for="flexCheckChecked">Check if same address above</label>
                    </div>

                    <div class="row">
                        <label class="label mb-3 fw-bold">PRESENT ADDRESS:</label><br>
                        <div class="row">
                            <div class="form-group col-md-4">
                            <select name="pre_region" id="pre_region" class="form-select <?php echo (isset($pre_region) && $pre_region == $pre_region) ? 'has-value' : ''; ?> identifying_information_edit" required disabled>
                                <option value="" disabled selected></option>
                                <?php foreach($region as $row): ?>
                                    <option value="<?php echo $row->region_id; ?>" 
                                            <?php echo (isset($pre_region) && $pre_region == $row->region_id) ? 'selected' : ''; ?>>
                                        <?php echo $row->region_name; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <label for="pre_region" class="form-label fw-bold">REGION <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-group col-md-4">
                            <select class="form-select <?php echo (isset($pre_prov) && $pre_prov == $pre_prov) ? 'has-value' : ''; ?> identifying_information_edit" name="pre_prov" id="pre_prov" required disabled>
                                <option value="<?php echo $pre_prov; ?>"><?php echo $pre_province_name; ?></option>
                            </select>
                            <label class="form-label fw-bold">PROVINCE <span class="text-danger">*</span></label>
                        </div>


                        <div class="form-group col-md-4">
                            <select class="form-select <?php echo (isset($pre_muni) && $pre_muni == $pre_muni) ? 'has-value' : ''; ?> identifying_information_edit" name="pre_muni" id="pre_muni" required disabled>
                                <option value="<?php echo $pre_muni; ?>"><?php echo $pre_city_name; ?></option>
                            </select>
                            <label class="form-label fw-bold">MUNICIPALITY <span class="text-danger">*</span></label>
                        </div>


                        <div class="form-group col-md-4">
                            <select class="form-select <?php echo (isset($pre_brgy) && $pre_brgy == $pre_brgy) ? 'has-value' : ''; ?> identifying_information_edit" name="pre_brgy" id="pre_brgy" required disabled>
                                <option value="<?php echo $pre_brgy; ?>"><?php echo $pre_brgy_name; ?></option>
                            </select>
                            <label class="form-label fw-bold">BARANGAY <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-group col-md-4">
                            <input type="text" class="form-control form-custom identifying_information_edit" pattern="[A-Za-z0-9\s]+" title="Only letters, numbers, and spaces are allowed" name="pre_sitio" id="pre_sitio" value="<?php echo set_value('pre_sitio', isset($pre_sitio) ? $pre_sitio : '');?>" placeholder="" required readonly>
                            <label class="form-label fw-bold">HOUSE NO./ PUROK <span class="text-danger">*</span></label>
                        </div>

                            <div class="form-group col-md-4">
                                <input type="text" class="form-control form-custom identifying_information_edit" pattern="[A-Za-z0-9\s]+" title="Only letters, numbers, and spaces are allowed" name="pre_street" id="pre_street" value="<?php echo set_value('pre_street', isset($pre_street) ? $pre_street : '');?>" placeholder="" required readonly>
                                <label class="form-label fw-bold">STREET <span class="text-danger">*</span></label>
                            </div>
                        </div>

                        <div class="form-group col-md-2">
                            <input type="text" class="form-control form-custom date-picker identifying_information_edit" name="birthdate" id="bday" value="<?php echo set_value('birthdate', isset($birthdate) ? $birthdate : '');?>" onchange="submitBday()" placeholder="" required readonly>
                            <label class="form-label fw-bold">BIRTHDATE <span style="font-size: 12px" >(yyyy-mm-dd)</span> <span class="text-danger">*</span></label>
                        </div>




                        <div class="form-group col-md-2">
                            <input type="text" class="form-control form-custom identifying_information_edit" pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed" name="birth_place" value="<?php echo set_value('birth_place', isset($birth_place) ? $birth_place : '');?>" placeholder="" required readonly>
                            <label class="form-label fw-bold">PLACE OF BIRTH <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-group col-md-1">
                            <textarea class="form-control form-custom " name="age" rows="1" id="age" placeholder="" required readonly><?php echo set_value('age');?></textarea>
                            <label class="form-label fw-bold">AGE</label>
                        </div>

                        <div class="form-group col-md-3">
                            <select class="form-select <?php echo (isset($sex) && $sex == $sex) ? 'has-value' : ''; ?> identifying_information_edit" name="sex" required disabled>
                                <option value="" disabled selected></option>
                                <option value="1" <?php echo (isset($sex) && $sex == 1) ? 'selected' : ''; ?>>1 - MALE</option>
                                <option value="2" <?php echo (isset($sex) && $sex == 2) ? 'selected' : ''; ?>>2 - FEMALE</option>
                                </select>
                            <label class="form-label fw-bold">SEX <span class="text-danger">*</span></label>
                        </div>	

                        <div class="form-group col-md-2">
                            <select class="form-select <?php echo (isset($civil_status) && $civil_status == $civil_status) ? 'has-value' : ''; ?> identifying_information_edit" name="civil_status" id="civil_status" required disabled>
                                <option value="" disabled selected></option>
                                <option value="1" <?php echo (isset($civil_status) && $civil_status == 1) ? 'selected' : ''; ?>>1 - SINGLE</option>
                                <option value="2" <?php echo (isset($civil_status) && $civil_status == 2) ? 'selected' : ''; ?>>2 - MARRIED</option>
                                <option value="3" <?php echo (isset($civil_status) && $civil_status == 5) ? 'selected' : ''; ?>>3 - SEPARATED</option>
                                <option value="4" <?php echo (isset($civil_status) && $civil_status == 3) ? 'selected' : ''; ?>>4 - WIDOWED</option>
                                <option value="5" <?php echo (isset($civil_status) && $civil_status == 4) ? 'selected' : ''; ?>>5 - LIVE-IN</option>
                                <option value="6" <?php echo (isset($civil_status) && $civil_status == 6) ? 'selected' : ''; ?>>6 - OTHERS</option>
                            </select>
                            <label class="form-label fw-bold">CIVIL STATUS <span class="text-danger">*</span></label>
                        </div>

                        
                        <div class="form-group col-md-2" id="cs_others_group" style="display: none;">
                            <input type="text" id="cs_others" class="form-control form-custom identifying_information_edit" pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed" name="cs_others" value="<?php echo set_value('cs_others', isset($cs_others) ? $cs_others : '');?>" placeholder="" readonly>
                            <label class="form-label fw-bold">SPECIFY <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-group col-md-3 d-flex justify-content-start">
                            <select id="living_arrangement" name="living_arrangement" class="form-select <?php echo (isset($living_arrangement) && $living_arrangement == $living_arrangement) ? 'has-value' : ''; ?> identifying_information_edit" required disabled>
                                <option value="" disabled selected></option>
                                <option value="1" <?php echo (isset($living_arrangement) && $living_arrangement == 1) ? 'selected' : ''; ?>>1 - LIVING ALONE</option>
                                <option value="2" <?php echo (isset($living_arrangement) && $living_arrangement == 2) ? 'selected' : ''; ?>>2 - LIVING WITH SPOUSE</option>
                                <option value="3" <?php echo (isset($living_arrangement) && $living_arrangement == 3) ? 'selected' : ''; ?>>3 - LIVING WITH CHILDREN</option>
                                <option value="4" <?php echo (isset($living_arrangement) && $living_arrangement == 4) ? 'selected' : ''; ?>>4 - OTHERS</option>
                            </select>
                            <label class="form-label fw-bold">LIVING ARRANGEMENT <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-group col-md-3" id="living_arrangement_others_group" style="display: none;">
                            <input type="text" id="living_arrangement_others" class="form-control form-custom identifying_information_edit" pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed" name="living_arrangement_others" value="<?php echo set_value('living_arrangement_others', isset($living_arrangement_others) ? $living_arrangement_others : '');?>" placeholder="" readonly>
                            <label class="form-label fw-bold">SPECIFY <span class="text-danger">*</span></label>
                        </div>



                        <div class="form-group col-md-3">
                            <select id="sector" class="form-select <?php echo (isset($sector) && $sector == $sector) ? 'has-value' : ''; ?> identifying_information_edit" name="sector" required disabled>
                                <option value="" disabled selected></option>
                                <option value="1" <?php echo (isset($sector) && $sector == 1) ? 'selected' : ''; ?>>1 - INDIGENOUS PEOPLE (Mga Katutubo)</option>
                                <option value="2" <?php echo (isset($sector) && $sector == 2) ? 'selected' : ''; ?>>2 - PERSON WITH DISABILITY</option>
                                <option value="3" <?php echo (isset($sector) && $sector == 3) ? 'selected' : ''; ?>>3 - SOLO PARENT</option>
                                <option value="4" <?php echo (isset($sector) && $sector == 4) ? 'selected' : ''; ?>>4 - MEMBER OF LGBTTQIA+++</option>
                            </select>
                            <label class="form-label fw-bold">SECTOR <span class="text-danger">*</span></label>
                        </div>

                        <div class="form-group col-md-3" >
                        <div id="ip_specify_group" style="display: none;">
                                <input type="text" id="ip_specify" class="form-control form-custom identifying_information_edit" name="ip_specify" value="<?php echo set_value('ip_specify', isset($ip_specify) ? $ip_specify : '');?>" placeholder="" readonly>
                                <label class="form-label fw-bold">SPECIFY <span class="text-danger">*</span></label>
                            </div>
                        </div>
                    </div>
            </div>

            <div id="FamilyInformation" class="tabcontent">
                <div class="mb-3 mt-2">
                    <a href="<?php echo base_url().'supervisor/client/validation'; ?>" class="btn btn-back hvr-float-shadow"><i class="fa-solid fa-caret-left"></i> Back</a>
                </div>

                    <label class="label mt-3 fw-bold">HOUSEHOLD MEMBERS:</label>
                    <table id="fam_info_details" class="table table-bordered spis_table" style="width:100%; margin-top: 0px">
                        <tr class="text-center align-middle">
                            <th style="width:30%">NAME <span class="text-danger fw-bold">*</span></th>
                            <th style="width:8%">AGE <span class="text-danger fw-bold">*</span></th>
                            <th style="width:15%">CIVIL STATUS <span class="text-danger fw-bold">*</span></th>
                            <th style="width:15%">RELATIONSHIP TO BENEFICIARY <span class="text-danger fw-bold">*</span></th>
                            <th style="width:17%">OCCUPATION <span class="text-danger fw-bold">*</span></th>
                            <th style="width:13%">MONTHLY INCOME <span class="text-danger fw-bold">*</span></th>
                            <th style="width:2%"><button type="button" id="chm-add-row" class="btn btn-custom btn-sm px-1 py-0 text-light" style="display: none;">+</button></th>
                        </tr>
                        <tr class="align-text-bottom">
                        <?php if (!empty($household_members)) : ?>
                        <?php foreach ($household_members as $index => $member): ?>
                            <td><input type="hidden" name="fam_info[<?php echo $index; ?>][chm_id]" class="form-control form-custom" value="<?php echo $member['chm_id']; ?>" required>   <input type="text" name="fam_info[<?php echo $index; ?>][chm_name]" class="form-control form-custom family_information_edit" value="<?php echo $member['chm_name']; ?>" required readonly></td>
                            <td><input type="text" name="fam_info[<?php echo $index; ?>][chm_age]" class="form-control form-custom family_information_edit" value="<?php echo $member['chm_age']; ?>" required readonly></td>
                            <td>
                                <select class="form-select chm-civil-status-select family_information_edit" name="fam_info[<?php echo $index; ?>][chm_civil_status]" required disabled>
                                    <option value="" disabled selected></option>
                                    <option value="1" <?php echo (isset($member['chm_civil_status']) && $member['chm_civil_status'] == 1) ? 'selected' : ''; ?>>1 - SINGLE</option>
                                    <option value="2" <?php echo (isset($member['chm_civil_status']) && $member['chm_civil_status'] == 2) ? 'selected' : ''; ?>>2 - MARRIED</option>
                                    <option value="3" <?php echo (isset($member['chm_civil_status']) && $member['chm_civil_status'] == 3) ? 'selected' : ''; ?>>3 - SEPARATED</option>
                                    <option value="4" <?php echo (isset($member['chm_civil_status']) && $member['chm_civil_status'] == 4) ? 'selected' : ''; ?>>4 - WIDOWED</option>
                                    <option value="5" <?php echo (isset($member['chm_civil_status']) && $member['chm_civil_status'] == 5) ? 'selected' : ''; ?>>5 - LIVE-IN</option>
                                    <option value="6" <?php echo (isset($member['chm_civil_status']) && $member['chm_civil_status'] == 6) ? 'selected' : ''; ?>>6 - OTHERS</option>
                                </select>
                                <div class="chm-civil-status-others-group" style="display: none;">
                                    <input type="text" class="form-control form-custom chm-civil-status-others family_information_edit" name="fam_info[<?php echo $index; ?>][chm_civil_status_others]" value="<?php echo $member['chm_civil_status_others']; ?>" placeholder="SPECIFY" readonly>
                                </div>
                            </td>
                            <td>
                                <select class="form-select chm-relationship-select family_information_edit" name="fam_info[<?php echo $index; ?>][chm_relationship]" required disabled>
                                    <option value="" disabled selected></option>
                                    <option value="1" <?php echo (isset($member['chm_relationship']) && $member['chm_relationship'] == 1) ? 'selected' : ''; ?>>1 - SPOUSE</option>
                                    <option value="2" <?php echo (isset($member['chm_relationship']) && $member['chm_relationship'] == 2) ? 'selected' : ''; ?>>2 - CHILD</option>
                                    <option value="3" <?php echo (isset($member['chm_relationship']) && $member['chm_relationship'] == 3) ? 'selected' : ''; ?>>3 - SIBLING</option>
                                    <option value="4" <?php echo (isset($member['chm_relationship']) && $member['chm_relationship'] == 4) ? 'selected' : ''; ?>>4 - CARETAKER</option>
                                    <option value="5" <?php echo (isset($member['chm_relationship']) && $member['chm_relationship'] == 5) ? 'selected' : ''; ?>>5 - OTHERS</option>
                                </select>
                                <div class="chm-relationship-others-group" style="display: none;">
                                    <input type="text" class="form-control form-custom chm-relationship-others family_information_edit" name="fam_info[<?php echo $index; ?>][chm_relationship_others]" value="<?php echo $member['chm_relationship_others']; ?>" placeholder="SPECIFY" readonly>
                                </div>
                            </td>
                            <td><input type="text" name="fam_info[<?php echo $index; ?>][chm_occupation]" class="form-control form-custom family_information_edit" value="<?php echo $member['chm_occupation']; ?>" required readonly></td>
                            <td><input type="text" name="fam_info[<?php echo $index; ?>][chm_monthly_income]" class="form-control form-custom family_information_edit" value="<?php echo $member['chm_monthly_income']; ?>" required readonly></td>
                            <td><button type="button" class="chm-remove-row btn btn-danger px-2 py-0 btn-sm" style="display: none;">-</button></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </table>

                
                    <label class="label mt-3 fw-bold">NAME OF AUTHORIZED REPRESENTATIVES:</label>
                    <table id="auth_rep_details" class="table table-bordered spis_table" style="width:100%; margin-top: 0px">
                        <tr class="text-center align-middle">
                            <th style="width:48%">NAME <span class="text-danger fw-bold">*</span></th>
                            <th style="width:10%">AGE <span class="text-danger fw-bold">*</span></th>
                            <th style="width:20%">RELATIONSHIP TO BENEFICIARY <span class="text-danger fw-bold">*</span></th>
                            <th style="width:20%">CONTACT NUMBER <span class="text-danger fw-bold">*</span></th>
                            <th style="width:2%"><button type="button" id="car-add-row" class="btn btn-custom btn-sm px-1 py-0 text-light" style="display: none;">+</button></th>
                        </tr>
                        <tr class="align-text-bottom">
                        <?php if (!empty($authorized_representatives)) : ?>
                        <?php foreach ($authorized_representatives as $index => $represnetative): ?>
                            <td><input type="hidden" name="rep_info[<?php echo $index; ?>][car_id]" class="form-control form-custom family_information_edit" value="<?php echo $represnetative['car_id']; ?>" required readonly> <input type="text" name="rep_info[<?php echo $index; ?>][car_name]" class="form-control form-custom family_information_edit" value="<?php echo $represnetative['car_name']; ?>" required readonly></td>
                            <td><input type="text" name="rep_info[<?php echo $index; ?>][car_age]" class="form-control form-custom family_information_edit" value="<?php echo $represnetative['car_age']; ?>" required readonly></td>
                            <td>
                                <select class="form-select car-relationship-select family_information_edit" name="rep_info[<?php echo $index; ?>][car_relationship]" required disabled>
                                    <option value="" disabled selected></option>
                                    <option value="1" <?php echo (isset($represnetative['car_relationship']) && $represnetative['car_relationship'] == 1) ? 'selected' : ''; ?>>1 - SPOUSE</option>
                                    <option value="2" <?php echo (isset($represnetative['car_relationship']) && $represnetative['car_relationship'] == 2) ? 'selected' : ''; ?>>2 - CHILD</option>
                                    <option value="3" <?php echo (isset($represnetative['car_relationship']) && $represnetative['car_relationship'] == 3) ? 'selected' : ''; ?>>3 - SIBLING</option>
                                    <option value="4" <?php echo (isset($represnetative['car_relationship']) && $represnetative['car_relationship'] == 4) ? 'selected' : ''; ?>>4 - CARETAKER</option>
                                    <option value="5" <?php echo (isset($represnetative['car_relationship']) && $represnetative['car_relationship'] == 5) ? 'selected' : ''; ?>>5 - OTHERS</option>
                                </select>
                                <div class="car-relationship-others-group" style="display: none;">
                                    <input type="text" class="form-control form-custom car-relationship-others family_information_edit" name="rep_info[<?php echo $index; ?>][car_relationship_others]" value="<?php echo $represnetative['car_relationship_others']; ?>" placeholder="SPECIFY" readonly>
                                </div>
                            </td>
                            <td><input type="text" name="rep_info[<?php echo $index; ?>][car_contact]" class="form-control form-custom family_information_edit" value="<?php echo $represnetative['car_contact']; ?>" required readonly></td>
                            <td><button type="button" class="car-remove-row btn btn-danger px-2 py-0 btn-sm" style="display: none;">-</button></td>
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
                                '<td><input type="text" name="fam_info[' + rowIndex + '][chm_name]" class="form-control form-custom" required></td>' +
                                '<td><input type="text" name="fam_info[' + rowIndex + '][chm_age]" class="form-control form-custom" required></td>' +
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
                                '<input type="text" class="form-control form-custom chm-civil-status-others" name="fam_info[' + rowIndex + '][chm_civil_status_others]" placeholder="SPECIFY">' +
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
                                '<input type="text" class="form-control form-custom chm-relationship-others" name="fam_info[' + rowIndex + '][chm_relationship_others]" placeholder="SPECIFY">' +
                                '</div></td>' +
                                '<td><input type="text" name="fam_info[' + rowIndex + '][chm_occupation]" class="form-control form-custom" required></td>' +
                                '<td><input type="text" name="fam_info[' + rowIndex + '][chm_monthly_income]" class="form-control form-custom" required></td>' +
                                '<td class="align-middle"><button type="button" class="chm-remove-row btn btn-danger px-2 py-0 btn-sm" style="display: none;">-</button></td>' +
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


                    // NAME OF AUTHORIZED REPRESENTATIVES
                    $(document).ready(function () {
                        var rowIndex = <?php echo isset($authorized_representatives) ? count($authorized_representatives) : 1; ?>;

                        function setupRow(index) {
                            $('select[name="rep_info[' + index + '][car_relationship]"]').on('change', function () {
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
                            var selectElementRelationship = $('select[name="rep_info[' + i + '][car_relationship]"]');
                            toggleCARRelationshipOthers(selectElementRelationship);  
                        }
                        <?php endif; ?>

                        $("#car-add-row").click(function () {
                            var newRow = '<tr>' +
                                '<td><input type="text" name="rep_info[' + rowIndex + '][car_name]" class="form-control form-custom" required></td>' +
                                '<td><input type="text" name="rep_info[' + rowIndex + '][car_age]" class="form-control form-custom" required></td>' +
                                '<td><select class="form-select car-relationship-select" name="rep_info[' + rowIndex + '][car_relationship]" required>' +
                                '<option value="" disabled selected></option>' +
                                '<option value="1">1 - SPOUSE</option>' +
                                '<option value="2">2 - CHILD</option>' +
                                '<option value="3">3 - SIBLING</option>' +
                                '<option value="4">4 - CARETAKER</option>' +
                                '<option value="5">5 - OTHERS</option>' +
                                '</select>' +
                                '<div class="car-relationship-others-group" style="display: none;">' +
                                '<input type="text" class="form-control form-custom car-relationship-others" name="rep_info[' + rowIndex + '][car_relationship_others]" placeholder="SPECIFY">' +
                                '</div></td>' +
                                '<td><input type="text" name="rep_info[' + rowIndex + '][car_contact]" class="form-control form-custom" required></td>' +
                                '<td><button type="button" class="car-remove-row btn btn-danger px-2 py-0 btn-sm" style="display: none;">-</button></td>' +
                                '</tr class="align-middle">';

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
            </div>

            <div id="Remarks" class="tabcontent">
                <div class="mb-3 mt-2">
                    <a href="<?php echo base_url().'supervisor/client/validation'; ?>" class="btn btn-back hvr-float-shadow"><i class="fa-solid fa-caret-left"></i> Back</a>
                </div>
                    <div class="row mt-4">
                        <div class="col">
                            <div class="form-group">
                                <textarea class="form-control form-custom textarea-custom remarks_edit" name="remarks" placeholder="" rows="10" readonly><?php echo $remarks; ?></textarea>
                                <label class="form-label textarea-label fw-bold">REMARKS</label>
                            </div>
                        </div>
                    </div>
            </div>


            <script>
                $(document).ready(function () {
                    var csrfName = '<?php echo $csrf['name']; ?>';
                    var csrfHash = '<?php echo $csrf['hash']; ?>';

                    $.ajaxSetup({
                        data: {
                            [csrfName]: csrfHash
                        },
                        beforeSend: function(xhr, settings) {
                            settings.data += "&" + csrfName + "=" + csrfHash;
                        },
                        complete: function (xhr) {
                            csrfHash = xhr.getResponseHeader('X-CSRF-TOKEN');
                        }
                    });

                    $('#spis_table thead tr')
                        .clone(true)
                        .addClass('datatableFilters')
                        .appendTo('#spis_table thead');

                    var table = $('#spis_table').DataTable({
                        processing: true,
                        serverSide: true,
                        responsive: true,
                        orderCellsTop: true,
                        fixedHeader: true,
                        dom: 'lrtip', 
                        pageLength: 10, 
                        order: [[5, 'desc']], 
                        columnDefs: [
                            {
                                targets: [0], 
                                type: 'num' 
                            }
                        ],
                        language: {          
                            processing: '<div class="loading-indicator"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" alt="Loading" /></div>',
                        },
                        ajax: {
                            url: "<?php echo base_url('client/client_waitlisted_list'); ?>",
                            type: 'POST',
                            data: function(d) {
                                d[csrfName] = csrfHash; 
                                $('#spis_table thead .datatableFilters input').each(function(i) {
                                    d.columns[i].search.value = this.value;  
                                });
                            }
                        },
                        initComplete: function () {
                            var api = this.api();
                            api.columns().eq(0).each(function (colIdx) {
                                var cell = $('.datatableFilters th').eq($(api.column(colIdx).header()).index());
                                var title = $(cell).text();
                                if (title == "DATE APPLIED") {
                                    $(cell).html('<input type="text" class="form-control text-center datepicker input-light-gray" placeholder="yyyy-mm-dd" autocomplete="off"/>');
                                    
                                    $('.datepicker').datepicker({
                                        changeMonth: true,
                                        changeYear: true,
                                        showButtonPanel: true,
                                        dateFormat: "yy-mm-dd", 
                                        autoclose: true,
                                        yearRange: "1900:c", 
                                        beforeShow: function (input, inst) {
                                            var rect = input.getBoundingClientRect();
                                            var optimalTop = rect.bottom + window.scrollY;
                                            var optimalLeft = rect.left + window.scrollX;
                                            inst.dpDiv.css({ top: optimalTop, left: optimalLeft });
                                        },
                                        onSelect: function() {
                                            api.column(colIdx).search(this.value).draw();
                                        }
                                    });
                                } else if (title == "" || title == "POINTS") {
                                    cell.addClass('no-filter').html('');
                                } else {
                                    $(cell).html('<input type="text" class="form-control text-center input-light-gray" placeholder="" autocomplete="off"/>');
                                }

                                $('input', $('.datatableFilters th').eq($(api.column(colIdx).header()).index()))
                                    .off('keyup change')
                                    .on('change', function (e) {
                                        api.column(colIdx).search(this.value).draw();
                                    })
                                    .on('keyup', function (e) {
                                        e.stopPropagation();
                                        $(this).trigger('change');
                                    });
                            });

                            // Date formatting logic outside of datepicker block
                            document.querySelectorAll('.datepicker').forEach(function (element) {
                                element.addEventListener('input', function () {
                                    let value = this.value.replace(/[^0-9]/g, '');

                                    if (value.length >= 4 && value.length < 6) {
                                        value = value.substring(0, 4) + '-' + value.substring(4);
                                    } else if (value.length >= 6) {
                                        value = value.substring(0, 4) + '-' + value.substring(4, 6) + '-' + value.substring(6, 8);
                                    }

                                    this.value = value;

                                    setTimeout(() => {
                                        this.setSelectionRange(value.length, value.length);
                                    }, 0);
                                });
                            });
                        }
                    });

                });
            </script>

            <script>
                function openCity(evt, cityName) {
                    var i, tabcontent, tablinks;
                    tabcontent = document.getElementsByClassName("tabcontent");
                    for (i = 0; i < tabcontent.length; i++) {
                        tabcontent[i].style.display = "none";
                    }
                    tablinks = document.getElementsByClassName("tablinks");
                    for (i = 0; i < tablinks.length; i++) {
                        tablinks[i].className = tablinks[i].className.replace(" active", "");
                    }
                    document.getElementById(cityName).style.display = "block";
                    if (evt) {
                        evt.currentTarget.className += " active";
                    }
                }

                document.addEventListener("DOMContentLoaded", function() {
                    // Activate the 'Identifying Information' tab on page load
                    openCity(null, 'IndentifyingInformation');
                    document.querySelector(".tab button").classList.add("active");
                });
            </script>

            <script>
                function submitBday() {
                    var Bdate = document.getElementById('bday').value;
                    if (Bdate) {
                    var Bday = +new Date(Bdate);
                    var age = ~~((Date.now() - Bday) / (31557600000)); 
                    document.getElementById('age').value = age; 
                    updateRadioButtons(); 
                    }
                }

                document.getElementById('bday').addEventListener('change', submitBday);

            window.onload = function() {
                submitBday(); 
            };


                var csrfName = '<?php echo $csrf['name']; ?>';
                var csrfHash = '<?php echo $csrf['hash']; ?>';

                $.ajaxSetup({
                    data: {
                        [csrfName]: csrfHash
                    },
                    beforeSend: function(xhr, settings) {
                        settings.data += "&" + csrfName + "=" + csrfHash;
                    },
                    complete: function (xhr) {
                        csrfHash = xhr.getResponseHeader('X-CSRF-TOKEN');
                    }
                });


                //CLIENT PERMANENT ADDRESS
                $('#perm_region').change(function() {
                    const region_id = $('#perm_region').val();
                    if (region_id != '') {
                        $.ajax({
                            url: "<?php echo base_url(); ?>Address/province",
                            method: "POST",
                            data: { 
                                region_id: region_id 
                            },
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


            // CIVIL STATUS
            document.addEventListener('DOMContentLoaded', function () {
                    var CivilStatusSelect = document.getElementById('civil_status');
                    var othersInputGroup = document.getElementById('cs_others_group');
                    // var csMarriedGroup = document.getElementById('cs_married_group');

                    // var spouseLname = document.getElementById('cs_lname');
                    // var spouseFname = document.getElementById('cs_fname');
                    // var spouseAddress = document.getElementById('cs_address');

                    function toggleInputs() {
                        if (CivilStatusSelect.value == "6") {
                            othersInputGroup.style.display = 'block';
                            othersInputGroup.querySelector('input').setAttribute('required', 'required');
                        } else {
                            othersInputGroup.style.display = 'none';
                            othersInputGroup.querySelector('input').removeAttribute('required');
                        }

                        // if (CivilStatusSelect.value == "2" || CivilStatusSelect.value == "4") { 
                        //     csMarriedGroup.style.display = 'block';
                        //     spouseLname.setAttribute('required', 'required');
                        //     spouseFname.setAttribute('required', 'required');
                        //     spouseAddress.setAttribute('required', 'required');
                        // } else {
                        //     csMarriedGroup.style.display = 'none';
                        //     spouseLname.removeAttribute('required');
                        //     spouseFname.removeAttribute('required');
                        //     spouseAddress.removeAttribute('required');
                        // }
                    }

                    toggleInputs(); 

                    CivilStatusSelect.addEventListener('change', toggleInputs);
                });

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

            </script>

            <script>
            $('.date-picker').datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "1900:+0", 
                showButtonPanel: true,
                dateFormat: "yy-mm-dd", 
                autoclose: true,
                beforeShow: function (input, inst) {
                    var rect = input.getBoundingClientRect();
                    var optimalTop = rect.bottom + window.scrollY;
                    var optimalLeft = rect.left + window.scrollX;
                    inst.dpDiv.css({ top: optimalTop, left: optimalLeft });
                }
            }).datepicker("disable");  


                document.querySelectorAll('.date-picker').forEach(function (element) {
                    element.addEventListener('input', function () {
                        let value = this.value.replace(/[^0-9]/g, '');

                        if (value.length >= 4 && value.length < 6) {
                            value = value.substring(0, 4) + '-' + value.substring(4);
                        } else if (value.length >= 6) {
                            value = value.substring(0, 4) + '-' + value.substring(4, 6) + '-' + value.substring(6, 8);
                        }

                        this.value = value;

                        setTimeout(() => {
                            this.setSelectionRange(value.length, value.length);
                        }, 0);
                    });
                });
            </script>
        </div>
    </div>
</div>
