<?php 
$csrf = array(
  'name' => $this->security->get_csrf_token_name(),
  'hash' => $this->security->get_csrf_hash()
);


?>


<div class="container-fluid d-flex justify-content-center">
    <div class="card card-shadow mt-4" style="width: 115rem;">
    <div class="card-header">GSIS Data</div>
        <div class="card-body">
        <div id="bfrtip-btn" class="d-flex justify-content-end"></div>

            <table class="table table-bordered table-hover table-responsive" id="spis_table" style="width: 100%;">
                <thead>
                    <tr>
                        <th class="text-center">Region</th>
                        <th class="text-center">Province</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Gender</th>

                    </tr>
                </thead>
            </table>

        </div>
    </div>
</div>


<script>
    $(document).ready(function () {

        // Retrieve CSRF token from PHP
        var csrfName = '<?php echo $csrf['name']; ?>';
        var csrfHash = '<?php echo $csrf['hash']; ?>';

        $('#spis_table thead tr')
            .clone(true)
            .addClass('datatableFilters')
            .appendTo('#spis_table thead');

        var table = $('#spis_table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            order: [[1, 'asc']],
            orderCellsTop: true,
            fixedHeader: true,
            dom: 'lBfrtip',
            buttons: [
                {
                    extend: 'copyHtml5',
                    text: '<i class="fa-solid fa-copy"></i> COPY',
                    className: 'copy-btn',
                    title: '',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                    }
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa-solid fa-file-excel"></i> EXPORT TO EXCEL',
                    className: 'excel-btn',
                    title: 'Government Service Insurance System',
                    footer: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                    }
                },
            ],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            ajax: {
                url: "<?php echo base_url('data/gsis_data'); ?>",
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfHash  // Include CSRF token in headers
                },
                data: function(d) {
                    d[csrfName] = csrfHash;  // Add CSRF token to the data
                }
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
    });
</script>
