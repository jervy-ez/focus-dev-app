<script type="text/javascript">
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();




</script>

<script type="text/javascript" src="<?php echo base_url(); ?>temp_script/moment.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-3.5.0.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>temp_script/datatables.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>temp_script/bootstrap-datetimepicker.min.js" charset="utf-8"></script>

<script type="text/javascript">
    $.noConflict();

    <?php if(isset($side_bar_primary) && $side_bar_primary!= ''): ?>
      jQuery( document ).ready(function( $ ) {        $('a#<?php echo $side_bar_primary; ?>').addClass('mm-active');  });
    <?php endif; ?>

    jQuery( document ).ready(function( $ ) {
      // OG jquery codes here

       

      $('#dn_date_complete').datetimepicker({ 
        format: 'DD/MM/YYYY'
      });

      $('#start_date_picker').datetimepicker({ 
        format: 'DD/MM/YYYY'
      });

      $('#dataTable_development').dataTable({
        "iDisplayLength": 20,
        "aLengthMenu": [[20, 30, 40, 50, -1], [20, 30, 40, 50, "All"]],   
        "aoColumnDefs":[
        {"targets": 4,"orderable": false} ,{ "bVisible": false, "aTargets":[7] }]
      });

    var table = $('#dataTable_development').dataTable();
    table.fnFilter('Outstanding','3');

    $("select#select-status-dnotes-tbl").on("change", function(e) { 
      var table = $('#dataTable_development').dataTable();
      var search = $(this).val(); 
      table.fnFilter(search,'3');
    }); 

    $('#dataTable_noCustom_dnotes_bugs').dataTable({
      "iDisplayLength": 20,
      "aLengthMenu": [[20, 30, 40, 50, -1], [20, 30, 40, 50, "All"]],   
      "aoColumnDefs": [
      { "bVisible": false, "aTargets":[0] },
      {"targets": 4,"orderable": false},
      {"bVisible": false, "aTargets":[7] }
      ],
    });

    var table = $('#dataTable_noCustom_dnotes_bugs').dataTable();
    table.fnFilter('Outstanding','3');

    $("select#select-status-dnotes-tbl").on("change", function(e) { 
      var table = $('#dataTable_noCustom_dnotes_bugs').dataTable();
      var search = $(this).val(); 
      table.fnFilter(search,'3');
    }); 


    });

    jQuery(function(e) {
        "use strict";
      //   jQuery("input#officeExt").inputmask("999");  no conflit jquer here
    });

</script>




