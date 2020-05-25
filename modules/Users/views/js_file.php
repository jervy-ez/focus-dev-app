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


    function copyHddnPwrdTxt(btnid,elmIdObj) {
      var copyText = document.getElementById(elmIdObj);
      copyText.select();
      copyText.setSelectionRange(0, 99999);
      document.execCommand("copy");

      var btnText = document.getElementById(btnid);
      btnText.innerHTML = "Copied to clipboard";
      copyText.blur();
  }

  function outFuncBtnCopy(elmIdObj) {
      var btnText = document.getElementById(elmIdObj);
      btnText.innerHTML = 'Click to Copy &nbsp; <em id="" class="fas fa-copy"></em>';
  }




</script>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.0.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/inputMask/dist/jquery.inputmask.js" charset="utf-8"></script>

<script type="text/javascript">
    $.noConflict();

    <?php if(isset($side_bar_primary) && $side_bar_primary!= ''): ?>
    jQuery( document ).ready(function( $ ) {        $('a#<?php echo $side_bar_primary; ?>').addClass('mm-active');  });
    <?php endif; ?>

    jQuery( document ).ready(function( $ ) {
        $('.toggle').click(function(){
            if ($(this).hasClass("off")) {
                $(this).removeClass("off");
                $(this).removeClass("btn-light");
                $(this).find('input').val('on');
            }else{
                $(this).addClass("off");
                $(this).addClass("btn-light");
                $(this).find('input').val('off');
            }
        });


        $('.ac_ca').click(function(){
          if ($(this).hasClass("off")) {
            // turn OFF
            $(this).next().addClass("off");
            $(this).next().addClass("btn-light");
            $(this).next().find('input').val('off');
          }else{
            // turn ON !!!!!
          }       


        });

        
        $('.ac_cc').click(function(){
          if ($(this).hasClass("off")) {
            // turn OFF
          }else{
            // turn ON !!!!!

            $(this).prev().removeClass("off");
            $(this).prev().removeClass("btn-light");
            $(this).prev().find('input').val('on');
          }          
        });






    });




    jQuery(function(e) {
        "use strict";

         jQuery("input#officeExt").inputmask("999");
         jQuery("input#officeNumber").inputmask("09 9999 9999");
         jQuery("input#mobileNumber").inputmask("0999 999 999");



        jQuery(".email-inputmask").inputmask({
            mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[*{2,6}][*{1,2}].*{1,}[.*{2,6}][.*{1,2}]"
            , greedy: !1
            , onBeforePaste: function (n, a) {
                return (e = e.toLowerCase()).replace("mailto:", "")
            }
            , definitions: {
                "*": {
                    validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~/-]"
                    , cardinality: 1
                    , casing: "lower"
                }
            }
        })

        jQuery('#userPhotoFile').change(function() {
          jQuery('form#userPhoto').submit();
        });

        jQuery('.uploadProfilePhoto').click(function(){
            jQuery('input#userPhotoFile').trigger('click');
        });
    });

</script>