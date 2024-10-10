<?php 
$csrf = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
);
?>

<div class="container-fluid d-flex justify-content-center">
    <div class="card card-shadow my-4" style="width: 115rem;">
        <div class="card-header">PVAO Data</div>
        <div class="card-body">
            <div id="bfrtip-btn" class="d-flex justify-content-end"></div>

            <table class="table table-bordered table-hover table-responsive spis_table" id="spis_table" style="width: 100%;">
                <thead>
                    <tr class="text-center align-middle">
                        <th class="text-center">First Name</th>
                        <th class="text-center">Middle Name</th>
                        <th class="text-center">Last Name</th>
                        <th class="text-center">Ext Name</th>
                        <th class="text-center">Birthdate</th>
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
                dom: 'rtip',
                pageLength: 10,
                order: [[0, 'asc']],
                language: {          
                    processing: '<div class="loading-indicator"><img src="<?php echo base_url('assets/img/loading.gif'); ?>" alt="Loading" /></div>',
                },
                ajax: {
                    url: "<?php echo base_url('data/pvao_data'); ?>",
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
                        if (title == "Birthdate") {
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
                }
            });
        });
    </script>
