<?php 

$csrf = array(
'name' => $this->security->get_csrf_token_name(),
'hash' => $this->security->get_csrf_hash()
);

$mainmodel = new Mainmodel();

if ($this->session->user_type == 'admin'){
  $user_type = 'admin';

}elseif ($this->session->user_type == 'co'){
  $user_type = 'central';

}elseif ($this->session->user_type == 'user'){
  $user_type = 'user';
}
?>



<section id="search">
  <div class="container-fluid">

    <div class="card">
        <div class="card-body d-flex flex-wrap justify-content-left">
          <!-- Button trigger modal -->
          <a href="#" data-bs-toggle="modal" data-bs-target="#PayrollModal<?php  ?>" class="btn btn-primary hvr-float-shadow m-1" style="text-decoration: none;" data-bs-placement="top" title="Child Case Status">Generate Payroll</a>
        </div>
    </div>

      <div class="card mt-4">
          <div class="card-header fw-bold">Search Client</div>
          <div class="card-body" style="overflow-x: auto;">

            <div id="bfrtip-btn"></div><br>

            <table id="client_table" class="table table-striped table-bordered nowrap">
              <thead>
                <tr>
                    <th class="text-center text-nowrap" width="2%"></th>
                    <th class="text-center text-nowrap">Office</th>
                    <th class="text-center text-nowrap">Client Name</th>
                    <th class="text-center text-nowrap">Birthdate</th>
                    <th class="text-center text-nowrap">Age</th>
                    <th class="text-center text-nowrap">Status</th>
                    <th class="text-center text-nowrap">Action</th>
                </tr>
              </thead>
              <tbody>
                    <?php foreach($client_table as $row){ 


                    if($row->client_mname == ''){
                        $name = $row->client_fname.' '.$row->client_lname;
                    }else{
                        $name =  $row->client_fname.' '.substr($row->client_mname, 0, 1).'. '.$row->client_lname;
                    }

                    $birthdate = $row->birthdate;

                    // Calculate the age
                    $currentDate = date('Y-m-d');
                    $birthDateTime = new DateTime($birthdate);
                    $currentDateTime = new DateTime($currentDate);
                    $ageInterval = $currentDateTime->diff($birthDateTime);

                    // Retrieve the age components
                    $years = $ageInterval->y;
                    $months = $ageInterval->m;
                    $days = $ageInterval->d;
                    
                    $current_age = "$years Years Old";

                    if($row->client_status == '1'){
                        $client_status = 'ACTIVE';
                    }else if($row->client_status == '2'){
                        $client_status = 'IN-ACTIVE';
                    }else if($row->client_status == '3'){
                        $client_status = 'DELISTED';
                    }else if($row->client_status == '4'){
                        $client_status = 'REPLACEMENT';
                    }else if($row->client_status == '5'){
                        $client_status = 'PENSIONER';
                    }else{
                        $client_status = '';
                    }

                       ?>

                    <tr>
                        <td class="text-center align-middle"><input type="checkbox"><input type="hidden" value="<?= $client_id=$row->client_id ?>" name="clien_id"></td>
                        <td class="text-center align-middle"><?php echo $row->region_name; ?></td>
                        <td class="text-center align-middle"><?php echo $name; ?></td>
                        <td class="text-center align-middle"><?php echo $birthdate; ?></td>
                        <td class="text-center align-middle"><?php echo $current_age; ?></td>
                        <td class="text-center align-middle"><?php echo $client_status; ?></td>
                        
                        <td class="text-center">
                          <a href="<?php echo base_url(); ?>admin/client_profile/<?php echo $client_id=$row->client_id ?>" data-bs-toggle="tooltip" data-bs-placement="top" title="View Profile">
                          <i class="fa-solid far fa-eye text-success"></i></a> | 

                          <a href="<?php echo base_url(); ?>admin/delete_data_idcb_plan/<?php echo $client_id=$row->client_id ?>" 
                              onclick="return confirm('Are you sure you want to delete?')" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                  <i class="fa-solid fa-trash-can fa-sm fa-fw mr-2 text-danger"></i></a>
                        </td>
                      </tr>


                  <?php } ?>
              </tbody>

            </table>


          </div>
      </div>
  </div>
</section>

<script>
$(document).ready(function() {
    $('#client_table thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#client_table thead');

    var table = $('#client_table').DataTable({
        orderCellsTop: true,
        responsive: true,
        fixedHeader: true,
        initComplete: function() {
            var api = this.api();

            // Loop through each column
            api.columns().eq(0).each(function(colIdx) {
                var column = api.column(colIdx);
                var title = $(column.header()).text();
                var header = $('.filters th').eq($(column.header()).index());

                if (title == "Action" || title == "") {
                    $(header).addClass('no-filter').html('');
                } else {
                    if (title == "Birthdate") {
                        // Replace text input with datepicker
                        $(header).html('<input type="text" class="form-control text-center datepicker" placeholder="' + title + '" />');
                        
                        // Initialize datepicker
                        $('.datepicker').datepicker({
                            dateFormat: 'yy-mm-dd', // Set desired date format
                            onSelect: function() {
                                api.column(colIdx).search(this.value).draw();
                            }
                        });
                    } else {
                        // Default text input for other columns
                        $(header).html('<input type="text" class="form-control text-center" placeholder="' + title + '" />');
                    }

                    // Add keyup/change event for filtering
                    $('input', header).on('keyup change', function(e) {
                        e.stopPropagation();
                        var regexr = '({search})';
                        var cursorPosition = this.selectionStart;

                        column
                            .search(
                                this.value != '' ?
                                regexr.replace('{search}', '(((' + this.value + ')))') :
                                '',
                                this.value != '',
                                this.value == ''
                            )
                            .draw();

                        $(this).focus()[0].setSelectionRange(cursorPosition, cursorPosition);
                    });
                }
            });

            // Additional logic can go here
            api.columns.adjust();
        }
    });
});



</script> 

<script>
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })
</script>

