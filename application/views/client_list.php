<div class="container-fluid ">

    <div class="card mt-3">
        <div class="card-body">
                <div class="row d-flex justify-content-end">
                    <div class="col-md-4 me-3">
                        <div id="bfrtip-btn"></div>
                    </div>
                </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            Client List
        </div>
        <div class="card-body">

                <table class="table table-bordered table-hover" id="spis_table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th class="text-center">1</th>
                            <th class="text-center">2</th>
                            <th class="text-center">3</th>
                            <th class="text-center">4</th>
                            <th class="text-center">5</th>
                            <th class="text-center">6</th>
                            <th class="text-center">7</th>
                            <th class="text-center">8</th>
                            <th class="text-center">9</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>a</td>
                            <td>b</td>                        
                            <td>c</td>
                            <td>d</td>
                            <td>e</td>
                            <td>f</td>
                            <td>g</td>
                            <td>h</td>
                            <td>i</td>
                            <td><i class="fa-solid fa-print text-primary"></i></td>
                        </tr>
                    </tbody>
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
            order: [[5, 'asc']],
            orderCellsTop: true,
            fixedHeader: true,
            dom: 'lBfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf'],
            // ajax: {
            // url: "#",
            // type: "POST",
            // data: function (data) {
            //     data.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
            //     },
            // },
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