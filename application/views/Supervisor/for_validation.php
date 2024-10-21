<?php 
	$csrf = array(
		'name' => $this->security->get_csrf_token_name(),
		'hash' => $this->security->get_csrf_hash()
		);
?>


<div class="container-fluid d-flex justify-content-center">
    <div class="card card-shadow my-4" style="width: 115rem;">
        <div class="card-header">For Validation</div>
        <div class="card-body">
            <div id="bfrtip-btn" class="d-flex justify-content-end"></div>
            <form action="<?php echo base_url().'supervisor/client/assign_validator' ?>" method="post" id="assignForm">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="" disabled selected>Select Assign Validator</option>
                                <?php foreach($user_validation as $user): ?>
                                    <option value="<?php echo $user->user_id; ?>">
                                        <?php echo $user->user_fname.' '.$user->user_mname.' '.$user->user_lname; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-custom"><i class="fa-solid fa-person-circle-check"></i> Assign</button>
                    </div>
                </div>

                <!-- Table with checkboxes to select rows -->
                <table class="table table-bordered table-hover table-responsive spis_table" id="spis_table" style="width: 100%;">
                    <thead>
                        <tr class="text-center align-middle">
                            <th class="text-center"><input type="checkbox" id="select_all"></th> <!-- Select All Checkbox -->
                            <th class="text-center">UNIQUE ID</th>
                            <th class="text-center">FIRST NAME</th>
                            <th class="text-center">MIDDLE NAME</th>
                            <th class="text-center">LAST NAME</th>
                            <th class="text-center">EXT NAME</th>
                            <th class="text-center">BIRTHDATE</th>
                            <th class="text-center">BARANGAY</th>
                            <th class="text-center">MUNICIPALITY</th>
                            <th class="text-center">PROVINCE</th>
                            <th class="text-center">REGION</th>
                            <th class="text-center">STATUS</th>
                            <th class="text-center">SUB STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($validation as $row): 
                        $hashed_id = md5($row->client_id);


                        $client_status = '';
                        switch ($row->client_status) {
                            case 1:
                                $client_status = 'Active';
                                break;
                            case 2:
                                $client_status = 'Waitlisted';
                                break;
                            case 3:
                                $client_status = 'Delisted';
                                break;
                            case 4:
                                $client_status = 'Not Eligible';
                                break;
                            case 5:
                                $client_status = 'For Validation';
                                break;
                        }

                        $sub_client_status = '';
                        switch ($row->sub_client_status) {
                            case 1:
                                $sub_client_status = 'Eligible';
                                break;
                            case 2:
                                $sub_client_status = 'Not Eligible';
                                break;
                            case 3:
                                $sub_client_status = 'GSIS Match Found';
                                break;
                            case 4:
                                $sub_client_status = 'PVAO Match Found';
                                break;
                            case 5:
                                $sub_client_status = 'Duplicate';
                                break;
                            case 6:
                                $sub_client_status = 'Underage';
                                break;
                        } 
                        ?>

                    <tr onclick="window.location='<?php echo base_url().'supervisor/client/validationInfo/'.$hashed_id; ?>';" class="clickable-row">
                        <td onclick="stopTdClick(event);"><input type="checkbox" name="selected_clients[]" value="<?php echo $row->client_id; ?>"></td> 
                        <td><?php echo $row->ref_code; ?></td>
                        <td><?php echo $row->client_lname; ?></td>
                        <td><?php echo $row->client_fname; ?></td>
                        <td><?php echo $row->client_mname; ?></td>
                        <td><?php echo $row->client_ename; ?></td>
                        <td><?php echo date('F d, Y',strtotime($row->birthdate)); ?></td>
                        <td><?php echo $row->brgy_name; ?></td>
                        <td><?php echo $row->city_name; ?></td>
                        <td><?php echo $row->province_name; ?></td>
                        <td><?php echo $row->region_name; ?></td>
                        <td><?php echo $client_status; ?></td>
                        <td><?php echo $sub_client_status; ?></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </form>

            <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
                <div class="modal-dialog" style="max-width: 100%; width: 70vw;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addModalLabel">Ongoing</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover spis_table" id="pending_table" style="width: 100%;">
                                    <thead>
                                        <tr class="text-center align-middle">
                                            <th class="text-center">UNIQUE ID</th>
                                            <th class="text-center">FIRST NAME</th>
                                            <th class="text-center">MIDDLE NAME</th>
                                            <th class="text-center">LAST NAME</th>
                                            <th class="text-center">EXT NAME</th>
                                            <th class="text-center">BIRTHDATE</th>
                                            <th class="text-center">BARANGAY</th>
                                            <th class="text-center">MUNICIPALITY</th>
                                            <th class="text-center">PROVINCE</th>
                                            <th class="text-center">REGION</th>
                                            <th class="text-center">ASSIGNED TO</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($validation_pending as $pending): ?>
                                        <tr>
                                            <td><?php echo $pending->ref_code; ?></td>
                                            <td><?php echo $pending->client_fname; ?></td>
                                            <td><?php echo $pending->client_mname; ?></td>
                                            <td><?php echo $pending->client_lname; ?></td>
                                            <td><?php echo $pending->client_ename; ?></td>
                                            <td><?php echo date('F d, Y',strtotime($row->birthdate)); ?></td>
                                            <td><?php echo $pending->brgy_name; ?></td>
                                            <td><?php echo $pending->city_name; ?></td>
                                            <td><?php echo $pending->province_name; ?></td>
                                            <td><?php echo $pending->region_name; ?></td>
                                            <td><?php echo $pending->user_fname.' '.$pending->user_mname.' '.$pending->user_lname; ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger hvr-float-shadow" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> Close</button>
                        </div>
                    </div>
                </div>
            </div>



            <script>
                $('#pending_table thead tr')
                    .clone(true)
                    .addClass('datatableFilters')
                    .appendTo('#pending_table thead');

                var table = $('#pending_table').DataTable({
                    responsive: true,
                    orderCellsTop: true,
                    fixedHeader: true,
                    dom: 'lrtip', 
                    pageLength: 10, 
                    order: [[0, 'desc']],
                    language: {          
                        processing: '<div class="loading-indicator"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" alt="Loading" /></div>',
                    },
                
                    initComplete: function () {
                        var api = this.api();
                        api.columns().eq(0).each(function (colIdx) {
                            var cell = $('.datatableFilters th').eq($(api.column(colIdx).header()).index());
                            var title = $(cell).text();
                            if (title == "BIRTHDATE") {
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
                            } else if (title == "") {
                                cell.addClass('no-filter').html('');
                            } else {
                                $(cell).html('<input type="text" class="form-control text-center input-light-gray" placeholder=" " autocomplete="off"/>');
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
            </script>
            
            <script>
                function stopTdClick(event) {
                    event.stopPropagation();
                }

                // Select All Checkboxes functionality
                document.getElementById('select_all').onclick = function() {
                    var checkboxes = document.getElementsByName('selected_clients[]');
                    for (var checkbox of checkboxes) {
                        checkbox.checked = this.checked;
                    }
                }

                $('#spis_table thead tr')
                    .clone(true)
                    .addClass('datatableFilters')
                    .appendTo('#spis_table thead');

                var table = $('#spis_table').DataTable({
                    responsive: true,
                    orderCellsTop: true,
                    fixedHeader: true,
                    dom: 'Blrtip', 
                    pageLength: 10, 
                    order: [[0, 'desc']],
                    language: {          
                        processing: '<div class="loading-indicator"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" alt="Loading" /></div>',
                    },
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            text: '<i class="fa-solid fa-file-excel"></i> EXPORT TO EXCEL',
                            className: 'excel-btn hvr-float-shadow',
                            title: 'SOCIAL PENSION FOR VALIDATION',
                            footer: true,
                            exportOptions: {
                                columns: [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 ]
                            }
                        },
                        {
                            text: '<i class="fa-regular fa-hourglass-half"></i> Ongoing Assigned List',
                            className: 'add-btn hvr-float-shadow',
                            action: function () {
                                $('#addModal').modal('show');
                            },
                        }
                    ],
                    initComplete: function () {
                        var api = this.api();
                        api.columns().eq(0).each(function (colIdx) {
                            var cell = $('.datatableFilters th').eq($(api.column(colIdx).header()).index());
                            var title = $(cell).text();
                            if (title == "BIRTHDATE") {
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
                            } else if (title == "") {
                                cell.addClass('no-filter').html('');
                            } else {
                                $(cell).html('<input type="text" class="form-control text-center input-light-gray" placeholder=" " autocomplete="off"/>');
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

                table.buttons().container().appendTo($('#bfrtip-btn'));

            </script>
        </div>
    </div>
</div>

