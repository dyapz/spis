<?php 
$csrf = array(
  'name' => $this->security->get_csrf_token_name(),
  'hash' => $this->security->get_csrf_hash()
);

// if($new_registered_notif == 0){

// $notif = 'style="display: none"';
// }
?>

<div class="container-fluid d-flex justify-content-center">
    <div class="card card-shadow mt-4" style="width: 115rem;">
    <div class="card-header">User</div>
        <div class="card-body">
        <div id="bfrtip-btn" class="d-flex justify-content-end"></div>

            <table class="table table-bordered table-hover table-responsive" id="spis_table" style="width: 100%;">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 2%;"></th>
                        <th class="text-center">Region</th>
                        <th class="text-center">Province</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Gender</th>
                        <th class="text-center">Email</th>
                        <th class="text-center">Designation</th>
                        <th class="text-center">Username</th>
                        <th class="text-center">Access Level</th>
                        <th class="text-center">Date and Time Registered</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
            </table>

        </div>
    </div>
</div>


<!-- Update Access Level Modal -->
<div class="modal fade" id="updateUserModal" tabindex="-1" aria-labelledby="updateUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateUserModalLabel">Update Access Level</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?php echo base_url().'account/update_user'; ?>" method="POST">
                    <div class="form-group">
                        <select name="user_type" class="form-select <?php echo set_value('user_type') ? 'has-value' : ''; ?> " id="editUserType" required>
                            <option value="" disabled>Select user type</option>
                            <option value="1">Admin</option>
                            <option value="2">Supervisor</option>
                            <option value="3">Finance</option>
                            <option value="4">Encoder</option>
                        </select>
                        <label for="editUserType" class="form-label">Access Level</label>
                    </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="editUserId" name="user_id">
                <input type="hidden" id="editOldUserType" name="old_user_type">
                <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
                <button type="submit" class="btn btn-custom hvr-float-shadow" onclick="return confirm('Are you sure you want to update?')"><i class="fa-solid fa-floppy-disk"></i> Search</button>
                <button type="button" class="btn btn-danger hvr-float-shadow" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i> Close</button>

                </form>
            </div>
        </div>
    </div>
</div>


<!-- Add New Modal-->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" autocomplete="off">

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
                <input type="submit" value="Search" class="btn btn-custom">
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        $('#spis_table thead tr')
            .clone(true)
            .addClass('datatableFilters')
            .appendTo('#spis_table thead');

        var table = $('#spis_table').DataTable({
            processing: true,
            order: [[9, 'desc']],
            orderCellsTop: true,
            fixedHeader: true,
            dom: 'Blrtip', 
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

            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            ajax: {
                url: "<?php echo base_url('account/user_list_data'); ?>",
            },
            initComplete: function () {
                var api = this.api();
                // For each column
                api
                    .columns()
                    .eq(0)
                    .each(function (colIdx) {
                        // Set the header cell to contain the input element
                        var cell = $('.datatableFilters th').eq(
                            $(api.column(colIdx).header()).index()
                        );
                        var title = $(cell).text();
                        $(cell).html('<input type="text" placeholder="' + title + '" class="text-center" />');


                        // On every keypress in this input
                        $('input', $('.datatableFilters th').eq($(api.column(colIdx).header()).index()))
                            .off('keyup change')
                            .on('change', function (e) {
                                // Get the search value
                                $(this).attr('title', $(this).val());
                                var regexr = '({search})';

                                // Search the column for that value
                                api
                                    .column(colIdx)
                                    .search(
                                        this.value != ''
                                            ? regexr.replace('{search}', '(((' + this.value + ')))')
                                            : '',
                                        this.value != '',
                                        this.value == ''
                                    )
                                    .draw();
                            })
                            .on('keyup', function (e) {
                                e.stopPropagation();
                                $(this).trigger('change');
                            });

                        // Add a condition for the "Action" column
                        if (title == "") {
                            cell.addClass('no-filter').html('');
                        }
                    });
            },
        });
        table.buttons().container().appendTo($('#bfrtip-btn'));

        if ($('#editUserType').val() !== '') {
            $('#editUserType').addClass('has-value');
        }

        // Attach a click event to the edit buttons
        $('#spis_table tbody').on('click', 'a.edit-user', function () {
            var $row = $(this).closest('tr');
            var rowData = table.row($row).data();

            // Extract user data
            var userId = $(this).data('userid');
            var userType = $row.find('.user-type').text().trim(); // Get the user type from the table cell
            var OlduserType = $(this).data('type');

            // Set form values in modal
            $('#editUserId').val(userId);
            $('#editOldUserType').val(OlduserType);

            // Set the selected value for the dropdown
            $('#editUserType').val(function() {
                var options = $('#editUserType').find('option');
                for (var i = 0; i < options.length; i++) {
                    if ($(options[i]).text() === userType) {
                        return $(options[i]).val();
                    }
                }
                return ''; // Default to an empty value if no match found
            });

            // Open the modal
            $('#updateUserModal').modal('show');
        });
    });
</script>