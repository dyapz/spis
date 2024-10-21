<?php 
	$csrf = array(
		'name' => $this->security->get_csrf_token_name(),
		'hash' => $this->security->get_csrf_hash()
		);
?>


<div class="container-fluid d-flex justify-content-center">
    <div class="card card-shadow my-4" style="width: 115rem;">
        <div class="card-header">Client List</div>
        <div class="card-body">
            <div id="bfrtip-btn" class="d-flex justify-content-end"></div>


            <table class="table table-bordered table-hover table-responsive spis_table" id="spis_table" style="width: 100%;">
                <thead>
                    <tr class="text-center align-middle">
                        <th class="text-center"></th>
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
            </table>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmationModal">
    <div class="confirmationModal">
        <p id="confirmationMessage"></p>
        <button id="confirmYes" class="btn btn-primary">Yes</button>
        <button id="confirmNo" class="btn btn-secondary">No</button>
    </div>
</div>

<!-- Loading Spinner -->
<div id="loadingSpinner">
    <div class="loadingSpinner">
        <div class="spinner"></div>
        <p>Checking, please wait...</p>
    </div>
</div>

<!-- Add New Modal-->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add New Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="saveForm" action="#" method="POST" autocomplete="off">

                <div class="form-group">
                      <input type="text" name="client_lname" class="form-control form-custom" id="client_lname" placeholder="" required>
                      <label for="client_lname" class="form-label">Last Name</label>
                    </div>

                    <div class="form-group">
                      <input type="text" name="client_fname" class="form-control form-custom" id="client_fname" placeholder="" required>
                      <label for="client_fname" class="form-label">First Name</label>
                    </div>

                    <div class="form-group">
                      <input type="text" name="birthdate" class="form-control form-custom date-picker" id="birthdate" placeholder="" required>
                      <label for="birthdate" class="form-label">Birthdate <span style="font-size: 12px" >(yyyy-mm-dd)</span></label>
                    </div>
            </div>
            <div class="modal-footer">
                    <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
                    <button type="submit" class="btn btn-custom hvr-float-shadow"><i class="fa-solid fa-magnifying-glass"></i> Search</button>

                </form>
                <button type="button" class="btn btn-danger hvr-float-shadow" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> Close</button>
            </div>
        </div>
    </div>
</div>

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
    });


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
        dom: 'Blrtip', 
        pageLength: 10, 
        order: [[0, 'desc']],
        language: {          
            processing: '<div class="loading-indicator"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" alt="Loading" /></div>',
        },
        buttons: [
                // {
                //     extend: 'excelHtml5',
                //     text: '<i class="fa-solid fa-file-excel"></i> EXPORT TO EXCEL',
                //     className: 'excel-btn hvr-float-shadow',
                //     title: 'SOCIAL PENSION CLIENT LIST',
                //     footer: true,
                //     exportOptions: {
                //     columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 ]
                //     }
                // },
            {
                text: '<i class="fa-solid fa-plus "></i> Add New Record',
                className: 'add-btn hvr-float-shadow',
                action: function () {
                    $('#addModal').modal('show');
                },
            },
        ],
        ajax: {
            url: "<?php echo base_url('admin/client/client_data_list'); ?>",
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
});

</script>



<script>
    document.getElementById('saveForm').addEventListener('submit', function(event) {
    event.preventDefault();

    // Close the Bootstrap modal if it's open
    var addModal = bootstrap.Modal.getInstance(document.getElementById('addModal'));
    if (addModal) {
        addModal.hide();
    }

    // Show the loading spinner
    document.getElementById('loadingSpinner').style.display = 'flex';

    // Fetch form data
    var formData = new FormData(this);

    // Add CSRF token to the form data
    formData.append('<?php echo $csrf['name']; ?>', '<?php echo $csrf['hash']; ?>');

    // Send data to the server for checking
    fetch('<?php echo base_url('client/check_client'); ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Hide the loading spinner
        document.getElementById('loadingSpinner').style.display = 'none';

        // Display the modal with the received message
        document.getElementById('confirmationMessage').innerHTML = data;
        document.getElementById('confirmationModal').style.display = 'flex';

        // Handle Yes/No buttons
        document.getElementById('confirmYes').onclick = function() {
            window.location.href = '<?php echo base_url('client/new'); ?>';
        };
        document.getElementById('confirmNo').onclick = function() {
            document.getElementById('confirmationModal').style.display = 'none';
        };
    })
    .catch(error => {
        // Hide the loading spinner and show an error message
        document.getElementById('loadingSpinner').style.display = 'none';
        alert('An error occurred. Please try again.');
    });
});


    // Spinner animation
    const style = document.createElement('style');
    style.innerHTML = `
        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
</script>
