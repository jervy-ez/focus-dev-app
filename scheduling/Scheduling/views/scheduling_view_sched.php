<?php $this->load->module('scheduling'); ?>
<!-- =============================================== -->

<style type="text/css">
  .dataTables_filter, #schedule_table_paginate{
    float: right;
  }

   .dataTables_filter input{
    margin-left: 10px;
   }
</style>

<?php $days_arr_data = ["","Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];  ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <section class="content-header">
    <h1>View Schedule</h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">View Schedule</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <!-- row -->
    <div class="row">


      <div class="col-md-12 hide">

        <!-- general form elements -->
        <div class="box box-primary">

          <div class="box-body" style="display: block;">

      


          <div class="row form-group">


           <div id="" class="col-md-3">
                <select class="form-control" id="term">
                  <option selected value="">Academic Term*</option>
                  <?php foreach ($academic_year as $ay_data): ?>
                    <option value="<?php echo $ay_data->id; ?>"><?php echo $ay_data->school_year.'-'.$ay_data->semester ; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>


            <div id="" class="col-md-2">
              <select class="form-control" id="select_campus">
                <option selected="">Select Campus*</option>
                <?php foreach ($campus_data as $campus): ?>
                  <option value="<?php echo $campus->id; ?>"><?php echo $campus->campus_code; ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div id="" class="col-md-5">
              <select class="form-control" id="select_building">
                <option selected value="">Building*</option>
              </select>
            </div>

            <div id="" class="col-md-2">
              <select class="form-control" id="select_room">
                <option selected value="">Room*</option>
              </select>
            </div>

          </div>



          <div class="row">




            <div id="" class="col-md-3">
              <select class="form-control" id="select_faculty_campus">
                <option selected="">Select Faculty Campus*</option>
                <?php foreach ($campus_data as $campus): ?>
                  <option value="<?php echo $campus->id; ?>"><?php echo $campus->campus_code; ?></option>
                <?php endforeach; ?>
              </select>
            </div>



            <div id="" class="col-md-2">
              <select class="form-control" id="select_college">
                <option selected="">Select College*</option>
              </select>
            </div>


            <div class="col-md-3" id="">
              <select class="form-control" id="select_faculty">
                <option selected value="">Faculty*</option>                    
              </select>
            </div>


            <div id="" class="col-md-2">
              <div class="btn btn-primary">Seach Faculty Schedule</div>
            </div>





          </div>






          </div>

        </div>



      </div>







      <div class="col-md-12">
        <div class="box room_table" style="">


          <div class="box-header with-border">
            <h3 class="box-title">Schedules</h3>          
          </div>



          <!-- /.box-header -->
          <div class="box-body">
            <table id="schedule_table" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Course Code</th>
                  <th>Course Title</th>
                  <th>Faculty Name</th>
                  <th>Room</th>
                  <th>Class</th>
                  <th>Faculty</th>
                  <th>Schedule</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>


                  <?php foreach ($schedule_data as $course): ?>
                    <tr>
                     <td><?php echo $course->course_code; ?></td>
                     <td><?php echo $course->course_title; ?></td>
                     <td><?php echo $course->employee_lname.' '.$course->employee_fname; ?></td>
                     <td><?php echo $course->room_code; ?></td>
                     <td><?php echo $course->block_code; ?></td>
                     <td><?php echo $course->employee_lname.' '.$course->employee_fname; ?></td>
                     <td><?php echo $days_arr_data[$course->day].' '.$course->time; ?></td>
                     <td><a href="<?php echo base_url(); ?>Scheduling/disable/<?php echo $course->class_sched_id; ?>" class="btn btn-sm btn-primary view_sched_bttn" id="<?php echo $course->class_sched_id; ?>" >Disable</a></td>
                   
                 <?php endforeach; ?>


              </tbody>

            </table>







          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>














    </div>



  </section>
  <!-- /.content -->
</div>  
<!-- /.content-wrapper -->


 <!-- DataTables -->
 <script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
 <script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.min.js"></script>



<script type="text/javascript">

 $(function () {
    $("#schedule_table").DataTable({
      "iDisplayLength": 10,
      "aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
    //   "aoColumnDefs": [{ "bVisible": false, "aTargets":[5] },{ "bVisible": false, "aTargets":[6] },{ "bVisible": false, "aTargets":[7] },{ "bVisible": false, "aTargets":[8] },{ "bVisible": false, "aTargets":[9] },{"targets": 4,"orderable": false}],
   /*   "aoColumns" : [
      { sWidth: '15%' },
      { sWidth: '35%' },
      { sWidth: '35%' },
      { sWidth: '5%' },
      { sWidth: '10%' }
      ]
*/
    });
  });



  var base_url = "<?php echo base_url(); ?>";






  $("select#select_campus").change(function(){
    $("select#select_building").val('');
    $("select#select_room").val('');


    var campus_id = $(this).val();
    $.ajax({
      'url' : base_url+'Scheduling/catch_campus_bldg',
      'type' : 'POST',
      'data' : {'ajax_post' : campus_id },
      'success' : function(data){
        if(data){
          $("select#select_building").empty().append(data);
        } 
      }
    });
  });


  $("select#select_building").change(function(){
    var bldg = $(this).val();
    $.ajax({
      'url' : base_url+'Scheduling/get_room',
      'type' : 'POST',
      'data' : {'ajax_post' : bldg },
      'success' : function(data){
        if(data){
          $("select#select_room").empty().append(data);
        } 
      }
    });
  });


  $("select#select_faculty").change(function(){

    var term = $('select#term').val();


    $('#faculty_iframe').hide();
    $('p.please_select_faculty').hide();

    var faculty_id = $(this).val();
    var data_faculty = $(this).val();


    var faculty_url = '<?php echo base_url(); ?>Scheduling/faculty_calendar/'+data_faculty+'-'+term;


    var post_data = faculty_id;


    $.ajax({
      'url' : base_url+'Scheduling/count_schedule',
      'type' : 'POST',
      'data' : {'ajax_post' : post_data },
      'success' : function(data){
        if(data){
          $('input#schedule_name').val('Schedule '+data);
        } 
      }
    }); 



    $('iframe#faculty_iframe').attr('src', faculty_url);

    $('.faculty_loading_frame').show();

    setTimeout(function(){  
      $('iframe#faculty_iframe').show();
      $('.faculty_loading_frame').hide();
    },2000);

  });




  $("select#select_room").change(function(){
    $('#room_iframe').hide();
    $('p.please_select_room').hide();
    var room_id = $(this).val();
    var term = $('select#term').val();
    var room_url = '<?php echo base_url(); ?>Scheduling/room_calendar/'+room_id+'-'+term;



//$('iframe#faculty_iframe')[0].contentWindow.location.reload(true);
$('iframe#room_iframe').attr('src', room_url);

$('.room_loading_frame').show();

setTimeout(function(){  
  $('iframe#room_iframe').show();
  $('.room_loading_frame').hide();
},2000);

});





  $("select#select_faculty_campus").change(function(){
    $("select#select_college").val('');

    var campus_id = $(this).val();
    $.ajax({
      'url' : base_url+'Scheduling/catch_campus',
      'type' : 'POST',
      'data' : {'ajax_post' : campus_id },
      'success' : function(data){
        if(data){
//alert(data);
$("select#select_college").empty().append(data);
} 
}
});
  });




  $("select#select_college").change(function(){
    $("select#select_faculty").val('');

    var campus_id = $(this).val();
    $.ajax({
      'url' : base_url+'Scheduling/catch_faculty_campus',
      'type' : 'POST',
      'data' : {'ajax_post' : campus_id },
      'success' : function(data){
        if(data){
//alert(data);
$("select#select_faculty").empty().append('<option disabled="" selected="">Select Coordinator*</option>').append(data);
} 
}
});
  });



  $("select#class_block").change(function(){
    $('#class_iframe').hide();
    $('p.please_select_block').hide();
    var block_id = $(this).val();
    var term = $('select#term').val();
    var block_url = '<?php echo base_url(); ?>Scheduling/class_calendar/'+block_id+'-'+term;



//$('iframe#faculty_iframe')[0].contentWindow.location.reload(true);
$('iframe#class_iframe').attr('src', block_url);

$('.class_loading_frame').show();

setTimeout(function(){  
  $('iframe#class_iframe').show();
  $('.class_loading_frame').hide();
},2000);

});






  function checkTime(var_time){

    if(var_time!=''){

      var length = var_time.length;

      var time_mode_arr = var_time.split(' ');

      if(length == 7){
        var add_date = '0'+var_time;
      }else{
        var add_date = var_time;    
      }

      var updated_date = add_date.substring(0,5);

      var time_arr = updated_date.split(':');

      if(time_mode_arr[1] == 'PM'){
        time_arr[0] = parseInt(time_arr[0]) + parseInt(12);
      }else{
        if(time_arr[0] == 12){
          time_arr[0] = '00';
        }

      }

      return parseInt(time_arr[0])+''+time_arr[1];
    }
  }




  $('button.check_schedule').click(function(){

    var has_error = 2;




    if(!$('select#class_units').val()){
      has_error = 1;
      $('select#class_units').parent().addClass('has-error');
    }else{
      $('select#class_units').parent().removeClass('has-error');
      var class_units = $('select#class_units').val();
    }


    if(!$('select#class_event').val()){
      has_error = 1;
      $('select#class_event').parent().addClass('has-error');
    }else{
      $('select#class_event').parent().removeClass('has-error');
      var class_event = $('select#class_event').val();
    }


    if(!$('select#class_block').val()){
      has_error = 1;
      $('select#class_block').parent().addClass('has-error');
    }else{
      $('select#class_block').parent().removeClass('has-error');
      var class_block = $('select#class_block').val();
    }



    if(!$('select#select_faculty_campus').val()){
      has_error = 1;
      $('select#select_faculty_campus').parent().addClass('has-error');
    }else{
      $('select#select_faculty_campus').parent().removeClass('has-error');
      var select_faculty_campus = $('select#select_faculty_campus').val();
    }

    if(!$('select#select_college').val()){
      has_error = 1;
      $('select#select_college').parent().addClass('has-error');
    }else{
      $('select#select_college').parent().removeClass('has-error');
      var select_college = $('select#select_college').val();
    }



    if(!$('select#select_room').val()){
      has_error = 1;
      $('select#select_room').parent().addClass('has-error');
    }else{
      $('select#select_room').parent().removeClass('has-error');
      var select_room = $('select#select_room').val();
    }









    if(!$('input#start_time').val()){
      has_error = 1;
      $('input#start_time').parent().addClass('has-error');
    }else{
      $('input#start_time').parent().removeClass('has-error');
      var start_time = checkTime($('input#start_time').val());
    }

    if(!$('input#end_time').val()){
      has_error = 1;
      $('input#end_time').parent().addClass('has-error');
    }else{
      $('input#end_time').parent().removeClass('has-error');
      var end_time = checkTime($('input#end_time').val());
    }





    if(!$('select#select_faculty').val()){
      has_error = 1;
      $('select#select_faculty').parent().addClass('has-error');
    }else{
      $('select#select_faculty').parent().removeClass('has-error');
      var select_faculty = $('select#select_faculty').val();
    }




    if(!$('select#term').val()){
      has_error = 1;
      $('select#term').parent().addClass('has-error');
    }else{
      $('select#term').parent().removeClass('has-error');
      var term = $('select#term').val();
    }

    var days = '';

    if($('input#day_sun').prop('checked')) {
      days = 'Sun';
    }

    if($('input#day_mon').prop('checked')) {
      if(days!=''){
        days = days+',Mon';
      }else{
        days = 'Mon';
      }
    } 

    if($('input#day_tue').prop('checked')) {
      if(days!=''){
        days = days+',Tue';
      }else{
        days = 'Tue';
      }
    } 

    if($('input#day_wed').prop('checked')) {
      if(days!=''){
        days = days+',Wed';
      }else{
        days = 'Wed';
      }
    } 

    if($('input#day_thu').prop('checked')) {
      if(days!=''){
        days = days+',Thu';
      }else{
        days = 'Thu';
      }
    } 

    if($('input#day_fri').prop('checked')) {
      if(days!=''){
        days = days+',Fri';
      }else{
        days = 'Fri';
      }
    } 

    if($('input#day_sat').prop('checked')) {
      if(days!=''){
        days = days+',Sat';
      }else{
        days = 'Sat';
      }
    }


    if(days==''){
      has_error = 1;
      $('input#day_sun').parent().parent().parent().parent().parent().addClass('has-error');
    }else{
      $('input#day_sun').parent().parent().parent().parent().parent().removeClass('has-error');
    }


    var class_block = $('select#class_block').val();
//  var term ='1'; //
var class_event = $('select#class_event').val();
//var class_year = $('select#class_year').val();
$("#datePiccc").attr("readonly", false);
var schedule_name = $('input#schedule_name').val();
var input_data = days+'|'+select_room+'|'+start_time+'|'+end_time+'|'+select_faculty+'|'+course_id+'|'+class_block+'|'+term+'|'+class_event+'|'+schedule_name+'|'+class_units;
//+'|'+class_year;

//alert(input_data);

// controller_method class/method

if(has_error == 2){
  $.ajax({
    'url' : base_url+'Scheduling/check_sched',
    'type' : 'POST',
    'data' : {'ajax_post' : input_data },
    'success' : function(data){
// alert(data);

if(data == 1){

//alert('Cheers! New schedule is been added.');
//window.location.reload(true);



$('p#conflict_error_msg').html('');
$('#schedule_error_box').hide();


$('.success_added_schedule').modal({
  keyboard: false,
  backdrop: 'static',
  show: true
});


$('iframe').each(function() {
  this.contentWindow.location.reload(true);
});

$('.form-control').each(function(){
  $(this).val('');
});





}else{ 
//   alert(data+' Error! You have conflicts.');
// $('p#conflict_error_msg').html();
$('#schedule_error_box').show();
$('p#conflict_error_msg').html('');
$('p#conflict_error_msg').html(data);


}
}
});
}

});

var current_url = window.location.href;

// $('.rm_pfr_tm').modal({
//   backdrop: 'static',
//   keyboard: false,
//   show: true
// });




$('.over_ride_schedule').click(function(){

  var days = '';

  if($('input#day_sun').prop('checked')) {
    days = 'Sun';
  }

  if($('input#day_mon').prop('checked')) {
    if(days!=''){
      days = days+',Mon';
    }else{
      days = 'Mon';
    }
  } 

  if($('input#day_tue').prop('checked')) {
    if(days!=''){
      days = days+',Tue';
    }else{
      days = 'Tue';
    }
  } 

  if($('input#day_wed').prop('checked')) {
    if(days!=''){
      days = days+',Wed';
    }else{
      days = 'Wed';
    }
  } 

  if($('input#day_thu').prop('checked')) {
    if(days!=''){
      days = days+',Thu';
    }else{
      days = 'Thu';
    }
  } 

  if($('input#day_fri').prop('checked')) {
    if(days!=''){
      days = days+',Fri';
    }else{
      days = 'Fri';
    }
  } 

  if($('input#day_sat').prop('checked')) {
    if(days!=''){
      days = days+',Sat';
    }else{
      days = 'Sat';
    }
  }

  if(!$('select#select_room').val()){
    has_error = 1;
    $('select#select_room').parent().addClass('has-error');
  }else{
    $('select#select_room').parent().removeClass('has-error');
    var select_room = $('select#select_room').val();
  }


  if(!$('input#start_time').val()){
    has_error = 1;
    $('input#start_time').parent().addClass('has-error');
  }else{
    $('input#start_time').parent().removeClass('has-error');
    var start_time = checkTime($('input#start_time').val());
  }

  if(!$('input#end_time').val()){
    has_error = 1;
    $('input#end_time').parent().addClass('has-error');
  }else{
    $('input#end_time').parent().removeClass('has-error');
    var end_time = checkTime($('input#end_time').val());
  }

  if(!$('select#select_faculty').val()){
    has_error = 1;
    $('select#select_faculty').parent().addClass('has-error');
  }else{
    $('select#select_faculty').parent().removeClass('has-error');
    var select_faculty = $('select#select_faculty').val();
  }

  var class_block = $('select#class_block').val();

  if(!$('select#term').val()){
    has_error = 1;
    $('select#term').parent().addClass('has-error');
  }else{
    $('select#term').parent().removeClass('has-error');
    var term = $('select#term').val();
  }

  var class_event = $('select#class_event').val();




  var schedule_name = $('input#schedule_name').val();


  if(!$('select#class_units').val()){
    has_error = 1;
    $('select#class_units').parent().addClass('has-error');
  }else{
    $('select#class_units').parent().removeClass('has-error');
    var class_units = $('select#class_units').val();
  }


  if(!$('input#remarks_over_ride').val()){
    has_error = 1;
    $('input#remarks_over_ride').parent().addClass('has-error');
    var remarks_over_ride = '';
  }else{
    $('input#remarks_over_ride').parent().removeClass('has-error');
    var remarks_over_ride = $('input#remarks_over_ride').val();
  }


  if(remarks_over_ride == ''){
    alert('Please put a remark to continue.');

  }else{
    var input_data = days+'|'+select_room+'|'+start_time+'|'+end_time+'|'+select_faculty+'|'+course_id+'|'+class_block+'|'+term+'|'+class_event+'|'+schedule_name+'|'+class_units+'|'+remarks_over_ride;

    $.ajax({
      'url' : base_url+'Scheduling/override_sched',
      'type' : 'POST',
      'data' : {'ajax_post' : input_data },
      'success' : function(data){
// alert(data);

//alert('Cheers! New schedule is been added.');
//window.location.reload(true);

if(data == 1){

  $('.schedule_error_box').hide();

  $('p#conflict_error_msg').html('');
  $('#schedule_error_box').hide();

  $('.success_added_schedule').modal({
    keyboard: false,
    backdrop: 'static',
    show: true
  });

  $('iframe').each(function() {
    this.contentWindow.location.reload(true);
  });

  $('.form-control').each(function(){
    $(this).val('');
  });
}

}
});

  }



});


$("#datePiccc").attr("readonly", true);

</script>