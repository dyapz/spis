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
                        <th class="text-center">ASSIGNED VALIDATOR</th>
                    </tr>
                </thead>
            </table>
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
                    title: 'SOCIAL PENSION CLIENT LIST',
                    footer: true,
                    exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13 ]
                    }
                },
        ],
        ajax: {
            url: "<?php echo base_url('supervisor/client/client_data_for_approval_list'); ?>",
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
