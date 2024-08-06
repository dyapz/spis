<div class="container-fluid">
    <div class="card mt-3 shadow">
        <div class="card-body">
                <div class="row d-flex justify-content-end" style="margin-right: -600px">
                    <div class="col-md-4">
                        <div id="bfrtip-btn"></div>
                    </div>
                </div>
        </div>
    </div>

    <div class="card card-shadow mt-4">
        <div class="card-header">
            Client List
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover table-responsive" id="spis_table" style="width: 100%;">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 2%;"><input type="checkbox" id="selectAll"></th>
                        <th class="text-center">Client Name</th>
                        <th class="text-center">Birthdate</th>
                        <th class="text-center">Age</th>
                        <th class="text-center">Brgy</th>
                        <th class="text-center">Municipality</th>
                        <th class="text-center">Province</th>
                        <th class="text-center">Region</th>
                        <th class="text-center">Status</th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="text-center"></th>
                        <th class="text-center">Client Name</th>
                        <th class="text-center">Birthdate</th>
                        <th class="text-center">Age</th>
                        <th class="text-center">Brgy</th>
                        <th class="text-center">Municipality</th>
                        <th class="text-center">Province</th>
                        <th class="text-center">Region</th>
                        <th class="text-center">Status</th>
                        <th class="text-center"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Setup - add a text input to each footer cell
        $('#spis_table thead tr')
            .clone(true)
            .addClass('datatableFilters')
            .appendTo('#spis_table thead');

        var table = $('#spis_table').DataTable({
			responsive: true,
            order: [[5, 'asc']],
            orderCellsTop: true,
            fixedHeader: true,
            dom: 'lBfrtip',
            buttons: ['copy', 'csv', 'excel'],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                
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
                        $(cell).html('<input type="text" placeholder="' + title + '" />');

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