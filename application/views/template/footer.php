
<script>
    // Alert Fade Effect
    $(document).ready(function() {
        $('.alert').hide();
        $('.alert').fadeTo(2000, 500).slideUp(500);
    });
</script>

<script>
    // Show/hide main dropdown menu
    $('.dropdown').hover(function() {
        $(this).children('.dropdown-menu').stop(true, true).slideDown('fast');
    }, function() {
        $(this).children('.dropdown-menu').stop(true, true).slideUp('fast');
    });

    // Show/hide nested dropdown menu for Service 3
    $('.nested-dropdown').hover(function() {
        $(this).children('.nested-menu').stop(true, true).slideDown('fast');
    }, function() {
        $(this).children('.nested-menu').stop(true, true).slideUp('fast');
    });
</script>


    <script src="<?php echo base_url().'assets/js/main.js'; ?>" crossorigin="anonymous"></script>
    <!-- Bootstrap JS Bundle (Optional) -->
    <script src="<?php echo base_url().'assets/js/bootstrap.bundle.min.js'; ?>"></script>
    <script src="<?php echo base_url().'assets/js/fontawesome.js'; ?>"></script>

</body>
</html>