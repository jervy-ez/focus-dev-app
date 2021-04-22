	 <!-- this is a temporary fix for the bug at chrome -->
<input type="text" name="test" style="    width: 1px;     position: absolute;    z-index: -10;"> 
<!-- this is a temporary fix for the bug at chrome -->

  <style>
    .idle_alert {
      position: fixed;
      bottom: 0;
      right: 0;
      color: red;
      background-color: #F6766D;
    }
  </style>
  <div class="col-sm-12">
    <div class="idle_alert"></div> 
  </div>
  <div class="container-fluid">
		<div class="row">	
			<footer>
				<hr />

  
<!-- Modal -->
<div class="modal fade" id="idle_log_in_form" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">You Have Been Idle for 15 Mins. Please Relogin.</h4>
        <b style = "color: red">Warning: You will lose Unsaved Data if you Refresh the Page</b>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">

            <div class="form-group pad-20">
              <label for="inputUserName" class="col-sm-2 control-label">User Name</label>
              <div class="col-sm-10">
                <div class="input-group <?php if(form_error('user_name')){ echo 'has-error has-feedback';} ?>">
                  <span class="input-group-addon"><i class="fa fa-user"></i></span>
                  <input type="text" id="inputUserName" placeholder="User Name" name="user_name" class="form-control"  value="">
                </div>
              </div>
            </div>
                      
            <div class="form-group pad-20">
              <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
              <div class="col-sm-10">
                <div class="input-group <?php if(form_error('password')){ echo 'has-error has-feedback';} ?>">
                  <span class="input-group-addon"><i class="fa fa-unlock-alt"></i></span>
                  <input type="password" id="inputPassword" placeholder="Password" name="password" value="" class="form-control">
                </div>
              </div>
            </div>

            <div class="input-group pad-20">
              <input type="checkbox" name="remember" id = "remember">&nbsp;
              <label for="remember" class="control-label"> Remember me</label>
            </div>
 
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <b class = "pull-left">You have <i id = "no_of_tries" style = "color: red"></i> tries to log-in</b>
        <div class = "col-sm-12">
          <button style="margin-top: 5px;" type="button" class="btn btn-danger pull-left" onclick = "sign_out()"><i class="fa fa-sign-out"></i> Sign out</button>
          <button style="margin-top: 5px;" type="button" class="btn btn-primary pull-right" onclick = "resign_in()"><i class="fa fa-sign-in"></i> Sign in</button>
        </div>
      </div>
    </div>
  </div>
</div>



<div class="modal fade" id="loading_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-sm">
    <div class="modal-content">
      <div class="modal-body clearfix pad-10">
        <center><h3>Loading Please Wait</h3></center>
        <center><h2><i class="fa fa-circle-o-notch fa-spin fa-5x"></i></h2></center>
        <p>&nbsp;</p>
      </div>
    </div>
  </div>
</div>



			</footer>
		</div>
	</div>





<p class="text-center">&copy; FSF Group <?php echo date("Y"); ?></p>



</div>



<script type="text/javascript">
  
   $('.dynmc_sb').hide();

  $(document).ready(function(){


    $(document).bind("click touchstart", function(event){
      var trigger = $(".prj_amndnts_bttn")[0];
      var prj_cmmnts_trigger = $('.sb-open-right')[0];
      var users_lgin_triggr = $(".currently_logged_user")[0];
      var obj_clicked = event.target.className;

      if( obj_clicked.includes('prj_amndnts_bttn')){
        $('.toggle_project_amendments').show().animate({
          right: '0'
        });

      }else if( obj_clicked.includes('sb-open-right')){
        $('#prj_comments_sidebar').show().animate({
          right: '0'
        });

      }else if( obj_clicked.includes('currently_logged_user')){
        $('#loggedin_sidebar').show().animate({
          right: '0'
        });

      }else{

        if ( $('.dynmc_sb') !== event.target && !$('.dynmc_sb').has(event.target).length && trigger !== event.target && prj_cmmnts_trigger !== event.target && users_lgin_triggr !== event.target ) {
          $('.dynmc_sb').animate({ right: '-35%' });
          setTimeout(function(){ $('.dynmc_sb').hide(); },750);
        }else{
        //  $('.dynmc_sb').show();
        }  
      }

    });

  });


  $('.proj_amnds_search_bttn').click(function(){


    if ($("select.amnds_project_id  option:selected").hasClass('prj_disabled_amnd')) {
      $('.amnds_side_form').hide();
      alert('This project has commenced, posting amendments is disabled.');
    }
    else {
      $('.amnds_side_form').show();
    }

    $('.amnds_line_select_project').text('Amendments Posted');
    $('.amnds_side_content').show();
    $('.proj_amnd_reload_bttn').trigger('click');
  });


  


   $("select.amnds_project_id").on("change", function(e) {

    $('.amnds_side_form').hide();
    $('.amnds_line_select_project').text('Please click search.');

    
    $('.amnds_side_content').hide();



  });



  $('.close_toggle_amnds, .close-sb, .close_dynmc_sb').click(function(e) {
    $('.dynmc_sb').animate({
      right: '-35%'
    });

    setTimeout(function(){ 
      $('.dynmc_sb').hide();
    },750);

  });
 


  $('.submit_amnds_prj').click(function(){

  $('.proj_amnd_reload_bttn').find('i').addClass('fa-spin');
  $('.amnds_side_content').empty().append('<div class="notes_line no_posted_comment"><p><i class="fa fa-cog fa-spin"></i> Loading...</p></div>');


    $('.no_posted_comment').remove();

    var prjc_user_id = $('.prjamnd_user_id').val();
    var prjc_user_first_name = $('.prjamnd_user_first_name').val();
    var prjc_user_last_name = $('.prjamnd_user_last_name').val();
    var prjc_project_id = $('select.amnds_project_id ').val();
    var notes_comment_text = $('.amnds_comment_text').val();
    var result = '';
    var dataString = prjc_user_id+'`'+prjc_project_id+'`'+notes_comment_text+'`0`2';

    $('.notes_comment_text').empty().val('');

  //$('.notes_side_content').prepend('<div class="notes_line"><p>'+notes_comment_text+'</p><small><i class="fa fa-user"></i> '+prjc_user_first_name+' '+prjc_user_last_name+'<br><i class="fa fa-calendar"></i> '+result+'</small></div>');


  if(notes_comment_text!=''){
    $.post(baseurl+"projects/add_project_comment",{ 
      'ajax_var': dataString
    },function(result){
     // $('.amnds_side_content').prepend('<div class="notes_line"><p class="" style="">'+notes_comment_text+'</p><br /><small><i class="fa fa-user"></i> '+prjc_user_first_name+' '+prjc_user_last_name+'<br><i class="fa fa-calendar"></i> '+result+'</small></div>');
    //  $('.recent_prj_comment').empty().append('<p>'+notes_comment_text+'</p><small><i class="fa fa-user"></i> '+prjc_user_first_name+' '+prjc_user_last_name+'<br><i class="fa fa-calendar"></i> '+result+'</small>');
    });
  }
$('.amnds_comment_text').val('');

  setTimeout(function(){   
    $('.proj_amnd_reload_bttn').find('i').removeClass('fa-spin');
    $('.proj_amnd_reload_bttn').trigger('click');
 },1000);




});




   $('.proj_amnd_reload_bttn').click(function(){



  $(this).find('i').addClass('fa-spin');

  setTimeout(function(){   
    $('.proj_amnd_reload_bttn').find('i').removeClass('fa-spin');
 },1000);




    var prjc_project_id = $('select.amnds_project_id ').val();

     $('.amnds_side_content').empty().append('<div class="notes_line no_posted_comment"><p><i class="fa fa-cog fa-spin"></i> Loading...</p></div>');

    $.post(baseurl+"projects/list_project_comments",{ 'project_id': prjc_project_id, 'is_prj_rvw': '2'  },function(result){    
      if(result == 'Error'){
        setTimeout(function(){   
          $('.amnds_side_content').empty().append('<div class="notes_line no_posted_comment"><p>Project Not Found!</p></div>');
        },1000);
      }else{
        setTimeout(function(){        


          $('.amnds_side_content').empty().append(result);
        },1000);
      }    
    });



   });




   $(document).on('click', '.view_delete', function(){
    $(this).parent().addClass('deleted').prepend('<div class="pull-right btn btn-warning view_deleted btn-xs fa fa-eye-slash"> </div>');
    $(this).hide();
    var comments_id = $(this).attr('id');
    var prjc_project_id = $('select.amnds_project_id ').val();
    var user_id = <?php echo $this->session->userdata('user_id'); ?>;
    $.post(baseurl+"projects/project_comments_deleted",{ 'comments_id': comments_id, 'project_id': prjc_project_id, 'user_id': user_id   });
  });

   $(document).on('click', '.view_deleted', function(){
    $(this).parent().css('height','auto').prepend('<div class="pull-right btn btn-warning viewing_deleted btn-xs fa fa-eye"> </div>');
    $(this).hide();
  });

   $(document).on('click', '.viewing_deleted', function(){
    $(this).parent().css('height','30px').prepend('<div class="pull-right btn btn-warning view_deleted btn-xs fa fa-eye-slash"> </div>');
    $(this).hide();
  });




<?php if(isset($date_site_commencement)): ?>
if ($("input.quick_input#site_start").length){  
  var prj_start_tmsp = <?php echo strtotime ( date_format(date_create_from_format('d/m/Y', $date_site_commencement), 'Y-m-d') ); ?>;
  var today = <?php  echo  strtotime("today");  ?>;

  if(prj_start_tmsp <= today){
   setTimeout(function(){ 
    $('.amnds_side_form').hide();
    $('.side_bar_label').hide();

  },1000);
 }
}
<?php endif; ?>





  $('.prj_amndnts_bttn').click(function(event) {

      event.preventDefault(); // because it is an anchor element

      var notes_height = $('.toggle_project_amendments').innerHeight() - 525;

      $('.amnds_side_content').css('height',notes_height+'px');

 

//    $('.amnds_side_content').empty().append('<div class="notes_line no_posted_comment"><p><i class="fa fa-cog fa-spin"></i> Loading...</p></div>');
/*
    $.post(baseurl+"projects/list_project_comments",{ 'project_id': project_id, 'is_prj_rvw': '1'  },function(result){    
      if(result == 'Error'){
        $('.notes_side_form').hide();
        setTimeout(function(){   
          $('.notes_side_content').empty().append('<div class="notes_line no_posted_comment"><p>Project Not Found!</p></div>');
        },1000);
      }else{
        setTimeout(function(){   
          $('.notes_side_form').show();         
          $('.notes_side_content').empty().append(result);
        },1000);
      }    
    });

   */
  });


</script>

<style type="text/css">
  
.notes_line small{
  color: #8A8A8A !important;
}


.amnds_side_content .notes_line br {
    display: block !important;
}

.notes_line.comment_type_2, .notes_line.comment_type_2 small {
        background-color: #9E9E9E;
    padding: 5px;
    color: #000 !important;
}

.deleted.notes_line{
  text-decoration: line-through;
  height: 30px;
  overflow: hidden;
}

</style>
	
	<script type="text/javascript"> var base_url = '<?php echo site_url(); //you have to load the "url_helper" to use this function ?>'; </script>
	<script src="<?php echo base_url(); ?>js/vendor/bootstrap.min.js"></script>	
	

	<?php //if($chart): ?>
	<!-- <script src="<?php echo base_url(); ?>js/c3/charts.js"></script> -->
	<?php //endif; ?>
	
	<?php //if($tour): ?>
	<script src="<?php echo base_url(); ?>js/bootstrap-tour.min.js"></script>
	<link href="<?php echo base_url(); ?>css/bootstrap-tour.min.css" rel="stylesheet">
	<script src="<?php echo base_url(); ?>js/tour.js"></script>
	<?php //endif; ?>

 
  <script src="<?php echo base_url(); ?>js/bootstrap-datepicker.js"></script>
  <link href="<?php echo base_url(); ?>css/datepicker.css" rel="stylesheet">

	
	<?php //if($table): ?>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/dataTables.bootstrap.css">
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>js/datatables/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo base_url(); ?>js/datatables/dataTables.bootstrap.js"></script>
  <script src="<?php echo base_url(); ?>js/datatables/table.js?ver=845"></script>

  <script src="<?php echo base_url(); ?>js/jquery.maxlength.min.js"></script>

  <script src="<?php echo base_url(); ?>js/select2.js"></script>
    
	<script src="<?php echo base_url(); ?>js/plugins.js"></script>
	<script src="<?php echo base_url(); ?>js/main.js<?php #echo '?ver='.rand(100000,999999); ?>"></script>



  <link href="<?php echo base_url(); ?>css/bootstrap-switch.css" rel="stylesheet">
  <script src="<?php echo base_url(); ?>js/bootstrap-switch.min.js"></script>
 <script src="<?php echo base_url(); ?>js/jquery.simple-sidebar.js"></script>


  <script src="<?php echo base_url(); ?>js/support-main.js?ver=87<?php # echo '?ver='.rand(100000,999999); ?>"></script>

  <script src="<?php echo base_url(); ?>js/jquery.mockjax.js"></script> 
  <script src="<?php echo base_url(); ?>js/jquery.autocomplete.js"></script> 

<script type="text/javascript">
   	var controller = 'company';
    var baseurl = "<?php print base_url(); ?>";
    /*$.post("<?php echo site_url('works/display_work_table') ?>", 
    {}, 
    function(result){
       $("#tbl_works").html(result);
    });*/
	//dynamic_value_ajax
	function dynamic_value_ajax(value,method,classLocation){
    	$.ajax({
        	'url' : base_url+controller+'/'+method,
            'type' : 'POST',
            'data' : {'ajax_var' : value },
            'success' : function(data){
            	var divLocation = $(classLocation);
                if(data){
                	divLocation.html(data);
                  
            	}
        	}
    	});
   }
   //dynamic_value_ajax
</script>



<?php if($this->users->_is_logged_in()): ?>

<script type="text/javascript">
//<?php echo  $this->router->fetch_class(); ?>
// for projects auto view personal projects
<?php if($this->session->userdata('default_projects_view_personal') == 1 &&  $this->router->fetch_class() == 'projects' ) : ?>

  var select_personal = $('select.select-personal').val();
  var projectTable = $('#projectTable').dataTable();
  projectTable.fnFilter(select_personal,'6');

<?php endif; ?>
// for projects auto view personal projects




$("#save_company_name").click(function(){
  var comp_id = $('#company_id_data').val();
  var comp_name = $('#company_name_data').val();
  var data = comp_id+'|'+comp_name;
  if(comp_name!=''){
    dynamic_value_ajax(data,'update_name_company');
  }
});


$("#save_physical_address").click(function(){
  var phys_address_id = $('#physical_address_id_data').val();
  var unit_level = $('#unit_level').val();
  var number = $('#number').val();
  var street = $('#street').val();
  var postcode_a = $('#postcode_a').val();


  var state_a_raw = $('#state_a').val().split("|");
  var state_a = state_a_raw[1];  

  var suburb_a_raw = $('#suburb_a').val().split("|");
  var suburb_a = suburb_a_raw[0];  
  var data = phys_address_id+'|'+number+'|'+unit_level+'|'+street+'|'+suburb_a+'|'+postcode_a;


  $('span.data-unit_level').empty().text(unit_level);
  $('span.data-unit_number').empty().text(number);
  $('span.data-street').empty().text(street);
  $('span.data-state').empty().text(state_a);
  $('span.data-suburb').empty().text(toTitleCase(suburb_a));
  $('span.data-postcode').empty().text(postcode_a);

  dynamic_value_ajax(data,'update_details_address');

});

$("#save_postal_address").click(function(){

  var postal_address_id_data = $('#postal_address_id_data').val();
  var po_box = $('#po_box').val();
  var p_unit_level = $('#p_unit_level').val();
  var p_number = $('#p_number').val();
  var p_street = $('#p_street').val();
  var postcode_b = $('#postcode_b').val();


  var state_b_raw = $('#state_b').val().split("|");
  var state_b = state_b_raw[1];  

  var suburb_b_raw = $('#suburb_b').val().split("|");
  var suburb_b = suburb_b_raw[0];  
  var data = postal_address_id_data+'|'+p_number+'|'+p_unit_level+'|'+p_street+'|'+suburb_b+'|'+postcode_b+'|'+po_box;


  $('span.data-po_box').empty().text(po_box);
  $('span.data-p_unit_level').empty().text(p_unit_level);
  $('span.data-p_number').empty().text(p_number);
  $('span.data-p_street').empty().text(p_street);
  $('span.data-p_state').empty().text(state_b);
  $('span.data-p_suburb').empty().text(toTitleCase(suburb_b));
  $('span.data-p_postcode').empty().text(postcode_b);

  dynamic_value_ajax(data,'update_details_address');

});

$("#save_bank_details").click(function(){

  var bank_account_id = $('#bank_account_id').val();
  var bank_name = $('#bank-name').val();
  var account_name = $('#account-name').val();
  var account_number = $('#account-number').val();
  var bsb_number = $('#bsb-number').val();

  var data = bank_account_id+'|'+account_name+'|'+account_number+'|'+bank_name+'|'+bsb_number;
  dynamic_value_ajax(data,'update_bank_details_account');
});

function saving_more_details(){
  var type_raw = $('#type').val().split("|");
  var type = type_raw[1];  

  var parent_raw = $('#parent').val().split("|");
  var parent = parent_raw[1];

  if(parent == ''){
    parent = 0;
  }

  var activity_raw = $('#activity').val().split("|");
  var activity = activity_raw[1];

  var abn = $('#abn').val();
  var acn = $('#acn').val();
  var company_id = $('#company_id_data').val();
  
  
  var sub_client_raw = $('#sub_client').val().split("|");
  var sub_client = sub_client_raw[1];

  if(sub_client == ''){
    sub_client = 0;
  }

  if (abn == '' && acn == ''){
    $('#confirmModal').modal('show');

    $('#confirmText').text('ABN is a required field.');
    $('#confirmButtons').html('<button type="button" class="btn btn-info" data-dismiss="modal">Okay</button>');

    return false;
  } else {
    var data = abn+'|'+acn+'|'+activity+'|'+type+'|'+parent+'|'+company_id+'|'+sub_client;

    $('span.data-abn').empty().text(abn);
    $('span.data-acn').empty().text(acn);
    $('span.data-company_type').empty().text(type_raw[0]);
    $('span.data-parent_company_name').empty().text(parent_raw[0]);
    $('span.data-company_activity').empty().text(activity_raw[0]);
    $('span.data-sub_client').empty().text(sub_client_raw[0]);

    dynamic_value_ajax(data,'update_details_other');

    $("#save_more_details").hide();
    $("#edit_more_details").show();
    $('.more_details_group').show();
    $('.more_details_group_data').hide();

    location.reload();
  }
}

$("#save_comment_details").click(function(){
  var notes_id = $('#notes_id').val();
  var comments = $('.comments').val();
 //Added Company ID incase company notes_id is 0 -- Added by Mark;
  var comp_id = $('#company_id').val();
  //Added Company ID incase company notes_id is 0 -- Added by Mark;
  var data = notes_id+'|'+comments+'|'+comp_id;
  dynamic_value_ajax(data,'update_comments_notes');

});

$("#save_primary_contact").click(function(){
  var has_error = 0;
  var primary_email_id = $("#primary_email_id").val();
  var primary_contact_number_id = $("#primary_contact_number_id").val();
  var primary_contact_person_id = $("#primary_contact_person_id").val();

  var primary_first_name = $("#primary_first_name").val();
  var primary_last_name = $("#primary_last_name").val();
  var primary_contact_gender = $("#primary_contact_gender").val();
  var primary_contact_type = $("#primary_contact_type").val();
  var primary_office_number = $("#primary_office_number").val();
  var primary_after_hours = $("#primary_after_hours").val();
  var primary_mobile_number = $("#primary_mobile_number").val();
  var primary_general_email = $("#primary_general_email").val();
  var primary_area_code = $("#primary_area_code").text();

  var data = primary_first_name+'|'+primary_last_name+'|'+primary_contact_gender+'|'+primary_general_email+'|'+primary_office_number+'|'+primary_mobile_number+'|'+primary_after_hours+'|'+primary_contact_type+'|1|'+primary_contact_person_id+'|'+primary_email_id+'|'+primary_contact_number_id;


  if(primary_office_number == ''){
    if(primary_mobile_number == '' ){
      has_error = 1;
      $("#primary_office_number").parent().addClass('has-error');
    }else{
      $("#primary_office_number").parent().removeClass('has-error');
    }
  }


  if(primary_mobile_number == '' ){
    if(primary_office_number == ''){
      has_error = 1;
      $("#primary_mobile_number").parent().addClass('has-error');
    }else{
      $("#primary_mobile_number").parent().removeClass('has-error');
    }
  }



  $(".data-first_name").empty().text(primary_first_name);
  $(".data-last_name").empty().text(primary_last_name);
  $(".data-gender").empty().text(primary_contact_gender);
  $(".data-type").empty().text(primary_contact_type);
  $(".data-office_number").empty().text(primary_area_code+' '+primary_office_number);
  $(".data-after_hours").empty().text(primary_area_code+' '+primary_after_hours);
  $(".data-mobile_number").empty().text(primary_mobile_number);
  $(".data-general_email").empty();
 $(".data-general_email").append('<a href="mailto:'+primary_general_email+'">'+primary_general_email+'</a>');




 setTimeout(function(){

   if(has_error == 0){
    var company_id = $('input#company_id_data').val();
    dynamic_value_ajax(data,'update_person_contact');
  window.location.reload(true);
  // window.location.assign("?reload="+company_id);
}
},1000);

});




$(".save_other_contact").click(function(){
var target = $(this).attr('id').substring(19);


  var other_email_id = $("#other_email_id_"+target).val();
  var other_contact_number_id = $("#other_contact_number_id_"+target).val();
  var other_contact_person_id = $("#other_contact_person_id_"+target).val();

  var other_first_name = $("#other_first_name_"+target).val();
  var other_last_name = $("#other_last_name_"+target).val();
  var other_contact_gender = $("#other_contact_gender_"+target).val();
  var other_contact_type = $("#other_contact_type_"+target).val();
  var other_office_number = $("#other_office_number_"+target).val();
  var other_after_hours = $("#other_after_hours_"+target).val();
  var other_mobile_number = $("#other_mobile_number_"+target).val();
  var other_general_email = $("#other_general_email_"+target).val();
  var other_area_code = $("#other_area_code_"+target).text();

  var data = other_first_name+'|'+other_last_name+'|'+other_contact_gender+'|'+other_general_email+'|'+other_office_number+'|'+other_mobile_number+'|'+other_after_hours+'|'+other_contact_type+'|0|'+other_contact_person_id+'|'+other_email_id+'|'+other_contact_number_id;



$(".other_data-first_name_"+target).empty().text(other_first_name);
$(".other_data-last_name_"+target).empty().text(other_last_name);
$(".other_data-gender_"+target).empty().text(other_contact_gender);
$(".other_data-type_"+target).empty().text(other_contact_type);
$(".other_data-office_number_"+target).empty().text(other_office_number);
$(".other_data-after_hours_"+target).empty().text(other_after_hours);
$(".other_data-mobile_number_"+target).empty().text(other_mobile_number);
$(".other_data-general_email_"+target).empty().append('<a href="mailto:'+other_general_email+'">'+other_general_email+'</a>');


dynamic_value_ajax(data,'update_person_contact');

var company_id = $('#company_id_data').val();


var company_contact_id = $("#other_contact_person_company_id_"+target).val();
var primary_contact_person_id = $("#main_primary_contact_person_company_id").val();


var primary = company_contact_id+'|1';
var other = primary_contact_person_id+'|0';

//id="set_as_primary_

if ($('input#set_as_primary_'+target).prop('checked')) {

  //alert(primary+'-'+other);
  dynamic_value_ajax(primary,'update_contact_primary');
 // dynamic_value_ajax(other,'update_contact_primary');
 
}

    $('#loading_modal').modal({"backdrop": "static", "show" : true} );
       setTimeout(function(){
        window.location.reload(true);

      },1000);
});


   $("#add_save_contact").click(function(){
      

    $('#loading_modal').modal({"backdrop": "static", "show" : true} );
var can_add_contact = 1;

      
  var first_name = $("#other_first_name").val();
  var last_name = $("#other_last_name").val();

  var contact_gender = $("#other_contact_gender").val();
  var contact_type = $("#other_contact_type").val();
  var office_number = $("#other_office_number").val();
  var after_hours = $("#other_after_hours").val();
  var mobile_number = $("#other_mobile_number").val();
  var general_email = $("#other_general_email").val();
  var other_area_code = $("#other_area_code").text();
  var comp_id = $('#company_id_data').val();
  var error_message = '';


 
  if(last_name == '' || first_name == ''){
    error_message = 'Please Fill First and Last Name';
    can_add_contact = 0;
  }

  if(office_number == '' && mobile_number == ''){
    error_message = error_message+'\nPlease assign Office Number or Mobile Number';
    can_add_contact = 0;
  }

  if(general_email == ''){
    error_message = error_message+'\nPlease assign Email';
    can_add_contact = 0;
  }


if(can_add_contact == 0){
  alert(error_message);
}

if(can_add_contact == 1){
      
        $('.new_contact_area').hide();
        $("#add_new_contact").hide();
        $('#add_save_contact').hide();
        $('#cancel_contact').hide();

        var data = first_name+'|'+last_name+'|'+contact_gender+'|'+contact_type+'|'+office_number+'|'+after_hours+'|'+mobile_number+'|'+general_email+'|'+other_area_code+'|'+comp_id;

        dynamic_value_ajax(data,'add_new_contact_dynamic');
        //location.reload();
        
       setTimeout(function(){
        window.location.reload(true);
      },1000);


      }


    });



  $(".delete_other_contact").click(function(){
    $('#loading_modal').modal({"backdrop": "static", "show" : true} );
    $(this).remove();
    var target = $(this).attr('id').substring(21);


    $('.other-contact-group_'+target).hide();
    $('.other-contact-group-other_data_'+target).hide();

    var delte_contact_id = $("#other_contact_person_company_id_"+target).val();

    dynamic_value_ajax(delte_contact_id,'delete_person_contact');
    $('.other-contact-group_'+target).remove();
    $('.other-contact-group-other_data_'+target).remove();

    $('#edit_other_contact_'+target).remove();
    $('#save_other_contact_'+target).remove();
  window.location.reload(true);

  });


  $("#delete_company").click(function(){
    var comp_id = $('#company_id_data').val();
    dynamic_value_ajax(comp_id,'delete_company'); 
    window.location = '../';
  });

  $("#delete_focus").click(function(){
    var comp_id = $('#company_id_data').val();
    dynamic_value_ajax(comp_id,'delete_company'); 
    window.location = '../company';
  });







   $("#save_other_details").click(function(){

    var abn = $("#abn").val();
    var acn = $("#acn").val();
    var jurisdiction = $("#jurisdiction").val();
    var company_id_data = $("#company_id_data").val();

    var data = abn+'_'+acn+'_'+jurisdiction+'_'+company_id_data;
   dynamic_value_ajax(data,'update_abn_acn_jurisdiction','#profile');    
  window.location.reload(true);

  });


    $("#save_contact_details").click(function(){

      var admin_contact_number_id = $("#admin_contact_number_id").val();
      var admin_email_id = $("#admin_email_id").val();
      var office_number = $("#office_number").val();
      var mobile_number = $("#mobile_number").val();
      var general_email = $("#general_email").val();

      $('.office_number').empty().text(office_number);
      $('.data_mobile_number').empty().text(mobile_number);
      $('.data_general_email').empty().text(general_email);


      var data = admin_contact_number_id+'|'+admin_email_id+'|'+office_number+'|'+mobile_number+'|'+general_email;
      dynamic_value_ajax(data,'updat_admin_contact_email');  

    });















   $(".state-option-a").on("change", function(e) {

    var stateRaw = $(this).val().split("|");
    var data = stateRaw[3]+'|dropdown|state_id|'+stateRaw[1]+'|'+stateRaw[2];
    //alert(stateRaw[3]);

    $("#areacode").val(stateRaw[2]);
    $('.area-code-text').text('');
    $('.area-code-text').text(stateRaw[2]);

    
    dynamic_value_ajax(data,'get_suburb_list','#suburb_a');
    $('#postcode_a').empty().append('<option value="">Choose a Postcode...</option>');

    $('.suburb-option-a .select2-chosen').text("Choose a Suburb...");
    $('.postcode-option-a .select2-chosen').text("Choose a Postcode...");
 	}); //this is working select callbak!

  $(".suburb-option-a").on("change", function(e) {
    var setValRaw = $(this).val().split("|");
    var data = setValRaw[0];
    //alert(stateRaw[3]);    
    var postCodeOptionA = dynamic_value_ajax(data,'get_post_code_list','#postcode_a');

    if(data == ''){
      $('#postcode_a').empty().append('<option value="">Choose a Postcode...</option>');
    }


    $('.postcode-option-a .select2-chosen').text("Choose a Postcode...");
  }); //this is working select callbak!

  
  $(".state-option-b").on("change", function(e) {
    var stateRaw = $(this).val().split("|");
    var data = stateRaw[3]+'|dropdown|state_id|'+stateRaw[1]+'|'+stateRaw[2];
    //alert(stateRaw[3]);
    
    dynamic_value_ajax(data,'get_suburb_list','#suburb_b');
    //$('.postcode-option-b').empty().append('<option value="">Choose a Postcode...</option>');


    $('.suburb-option-b .select2-chosen').text("Choose a Suburb...");
    $('.postcode-option-b .select2-chosen').text("Choose a Postcode...");
  }); //this is working select callbak!
        
 	   	
	$(".suburb-option-b").on("change", function(e) {
 		var setValRaw = $(this).val().split("|");
    var data = setValRaw[0];

    if(data == ''){
      $('.postcode-option-b').empty().append('<option value="">Choose a Postcode...</option>');
    }

    //alert(stateRaw[3]);    
    dynamic_value_ajax(data,'get_post_code_list','#postcode_b');
 	}); 

 	$("#type").on("change", function(e) {
    var type_val = $(this).val().split("|");
 		dynamic_value_ajax(type_val[0],'activity','#activity');
    $('.activity .select2-chosen').text("Choose Activity...");
 		//alert($(this).val());


    dynamic_value_ajax(type_val[1],'company_by_type','#parent');
 	});

  /* ======== OH&S ======== */
  $("#workplace_health_safety_save_btn").click(function(){
    var company_id = $('#company_id_data').val();
    var workplace_health_safety = $("input[name='workplace_health_safety']:checked").val();
    var workplace_health_safety_notes = $('#workplace_health_safety_notes').val();

    var data = company_id+'|'+workplace_health_safety+'|'+workplace_health_safety_notes;

    $('span.workplace_health_safety_notes-data').empty().text(workplace_health_safety_notes);

    if (workplace_health_safety == 1){
      $("span.workplace_health_safety-yes-icon").html('<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>');
      $("span.workplace_health_safety-no-icon").html('<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>');
    } else {
      $("span.workplace_health_safety-yes-icon").html('<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>');
      $("span.workplace_health_safety-no-icon").html('<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>');
    }

    // alert('<?php //echo $workplace_health_safety; ?>');

    dynamic_value_ajax(data,'update_workplace_health_safety');

    // location.reload(true);

  });

  $("#swms_save_btn").click(function(){
    var company_id = $('#company_id_data').val();
    var swms = $('input[name=swms]:checked').val();
    var swms_notes = $('#swms_notes').val();
   
    var data = company_id+'|'+swms+'|'+swms_notes;

    $('span.swms_notes-data').empty().text(swms_notes);

    if (swms == 1){
      $("span.swms-yes-icon").html('<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>');
      $("span.swms-no-icon").html('<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>');
    } else {
      $("span.swms-yes-icon").html('<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>');
      $("span.swms-no-icon").html('<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>');
    }

    dynamic_value_ajax(data,'update_swms');

    // location.reload(true);

  });

  $("#jsa_save_btn").click(function(){
    var company_id = $('#company_id_data').val();
    var jsa = $('input[name=jsa]:checked').val();
    var jsa_notes = $('#jsa_notes').val();

    var data = company_id+'|'+jsa+'|'+jsa_notes;

    $('span.jsa_notes-data').empty().text(jsa_notes);

    if (jsa == 1){
      $("span.jsa-yes-icon").html('<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>');
      $("span.jsa-no-icon").html('<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>');
    } else {
      $("span.jsa-yes-icon").html('<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>');
      $("span.jsa-no-icon").html('<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>');
    }

    dynamic_value_ajax(data,'update_jsa');

    // location.reload(true);

  });

  $("#reviewed_swms_save_btn").click(function(){
    var company_id = $('#company_id_data').val();
    var reviewed_swms = $('input[name=reviewed_swms]:checked').val();
    var reviewed_swms_notes = $('#reviewed_swms_notes').val();

    var data = company_id+'|'+reviewed_swms+'|'+reviewed_swms_notes;

    $('span.reviewed_swms_notes-data').empty().text(reviewed_swms_notes);

    if (reviewed_swms == 1){
      $("span.reviewed_swms-yes-icon").html('<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>');
      $("span.reviewed_swms-no-icon").html('<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>');
    } else {
      $("span.reviewed_swms-yes-icon").html('<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>');
      $("span.reviewed_swms-no-icon").html('<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>');
    }

    dynamic_value_ajax(data,'update_reviewed_swms');

    // location.reload(true);

  });

  $("#safety_related_convictions_save_btn").click(function(){
    var company_id = $('#company_id_data').val();
    var safety_related_convictions = $('input[name=safety_related_convictions]:checked').val();
    var safety_related_convictions_notes = $('#safety_related_convictions_notes').val();

    var data = company_id+'|'+safety_related_convictions+'|'+safety_related_convictions_notes;

    $('span.safety_related_convictions_notes-data').empty().text(safety_related_convictions_notes);

    if (safety_related_convictions == 1){
      $("span.safety_related_convictions-yes-icon").html('<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>');
      $("span.safety_related_convictions-no-icon").html('<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>');

      $(".safety_related_convictions_details_wrap").show();

    } else {
      $("span.safety_related_convictions-yes-icon").html('<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>');
      $("span.safety_related_convictions-no-icon").html('<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>');

      $(".safety_related_convictions_details_wrap").hide();
    }

    dynamic_value_ajax(data,'update_safety_related_convictions');

    // location.reload(true);

  });

  $("#confirm_licences_certifications_save_btn").click(function(){
    var company_id = $('#company_id_data').val();
    var confirm_licences_certifications = $('input[name=confirm_licences_certifications]:checked').val();
    var confirm_licences_certifications_notes = $('#confirm_licences_certifications_notes').val();

    var data = company_id+'|'+confirm_licences_certifications+'|'+confirm_licences_certifications_notes;

    $('span.confirm_licences_certifications_notes-data').empty().text(confirm_licences_certifications_notes);

    if (confirm_licences_certifications == 1){
      $("span.confirm_licences_certifications-yes-icon").html('<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>');
      $("span.confirm_licences_certifications-no-icon").html('<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>');
    } else {
      $("span.confirm_licences_certifications-yes-icon").html('<i class="fa fa-times fa-lg" style="color: #d9534f;"></i>');
      $("span.confirm_licences_certifications-no-icon").html('<i class="fa fa-check fa-lg" style="color: #5cb85c;"></i>');
    }

    dynamic_value_ajax(data,'update_confirm_licences_certifications');

    // location.reload(true);

  });
  /* ======== OH&S ======== */

 	$('#contactperson').on("change", function(e){   		
 		if($(this).val() == 'add'){
 			//$('#add_contact').modal('show');
      $('.new-contact-details').slideToggle();
      $('.set_add_new').val('1');
 		}else{
      $('.new-contact-details').hide();
      $('.set_add_new').val('0');

    }
 	}); //this is working select callbak!
 	
 	$('.presonel_add').on("change", function(e){   		
 		if($(this).val() == 'add'){
 			alert($(this).attr('id'));
 			//$('#add_contact').modal('show');
 		}
 	}); //this is working select callbak!
</script>

<?php if($this->session->userdata('is_show_test') > 0): ?>
  <script type="text/javascript">
    $('#board').modal('show');
    $('.side-tools i#bulletin_board_lbl_sb').after('<span class="is_show_counter_bb"><?php echo $this->session->userdata("is_show_counter_bb"); ?></span>');
  </script>
  <?php $this->session->set_userdata('is_show_test','0'); ?>
<?php else: ?>
  <script type="text/javascript">$('.is_show_counter_bb').remove();</script>
<?php endif; ?>


<?php endif; ?>

</body>
</html>