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
              <select class="form-control" id="select_campus_filter">
                <option selected="">Select Campus*</option>
                <?php foreach ($campus_data as $campus): ?>
                  <option value="<?php echo $campus->id; ?>"><?php echo $campus->campus_code; ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div id="" class="col-md-5">
              <select class="form-control" id="select_building_filter">
                <option selected value="">Building*</option>
              </select>
            </div>

            <div id="" class="col-md-2">
              <select class="form-control" id="select_room_filter">
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
              <select class="form-control" id="select_faculty_set">
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
                  <th>Schedule</th>
                  <th>Units</th>
                  <th>Event</th>
                  <th>Campus</th>
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
                     <td><?php echo $course->block_code.' - '.$course->year_level.''.$course->block; ?></td>
                     <td><?php echo $days_arr_data[$course->day].' '.$course->time; ?></td>
                     <td><?php echo $course->no_of_units; ?></td>
                     <td><?php echo $course->class_event; ?></td>
                     <td><?php echo $course->college_code; ?></td>
                     <td><button type="button" class="btn btn-info btn-xs pull-right select_course_btn" onClick="schedule_get_data('<?php echo $course->class_sched_id.'-'.$course->class_event_id.'-'.$course->schedule_name.'-'.$course->fctyl_inchd_id.'-'.$course->day.'-'.$course->start_time.'-'.$course->end_time.'-'.$course->no_of_units.'-'.$course->time.'-'.$course->cctyl_inchd_id.'-'.$course->room_id; ?>')" data-toggle="modal" data-target="#view_schedule">View</button> </td>
                   
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



<!-- view modal schedule -->



  <div class="modal fade view_schedule" id="view_schedule" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">

        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
              <h4 class="modal-title">View Schedule <button class="btn btn-xs btn-danger disable_sched_bttn mrg-left-15" id="" >Disable</button></h4>
             
            </div>
            <div class="modal-body clearfix">


            <div id="" class="form-group clearfix">

                <div id="" class="col-sm-6">
                 <select class="form-control" id="class_event">
                  <option selected value="">Class Event*</option>
                  <?php foreach ($class_event_data as $class_event): ?>
                    <option value="<?php echo $class_event->id; ?>"><?php echo $class_event->class_event; ?></option>
                  <?php endforeach; ?>
                </select>


              </div>
            </div>
            
            <div id="" class="form-group clearfix">

             <div id="" class="col-sm-6">
              <input type="text" class="form-control units" value="" id="units" placeholder="Unit">
            </div>

            <div id="" class="col-sm-6">
              <select class="form-control" id="day_update">
                <option value="1">Sun</option>
                <option value="2">Mon</option>
                <option value="3">Tue</option>
                <option value="4">Wed</option>
                <option value="5">Thu</option>
                <option value="6">Fri</option>
                <option value="7">Sat</option>
                <option value="0">TBA</option>
              </select>
            </div>
          </div>
 

            
            <div id="" class="form-group clearfix">

              <div id="" class="col-sm-6">
                <div class="form-group">
                  <div class='input-group date' id='datetimepicker1'>
                    <input type='text' class="form-control select_all" id="start_time" value="" placeholder="HH:MM NN*" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-chevron-down"></span>
                    </span>
                  </div>
                </div>
              </div>

              <div id="" class="col-sm-6">
                <div class="form-group">
                  <div class='input-group date' id='datetimepicker2'>
                    <input type='text' class="form-control select_all" id="end_time" value="" placeholder="HH:MM NN*" />
                    <span class="input-group-addon">
                      <span class="glyphicon glyphicon-chevron-down"></span>
                    </span>
                  </div>
                </div>
              </div>

            </div>


            <div id="" class="form-group clearfix">




              <h4 class="pad-left-10 mrg-left-5">Change Faculty</h4>

              <div id="" class="col-md-6">

                <select class="form-control" id="select_faculty">
                  <option  selected value="">Coordinator*</option>
                  <?php foreach ($employee_data as $faculty): ?>
                    <option value="<?php echo $faculty->id; ?>"><?php echo $faculty->employee_lname.' '.$faculty->employee_fname; ?></option>
                  <?php endforeach; ?>
                  <option value="0">*TBA</option>
                </select>

              </div>

              <div id="" class="col-md-6">


                <select class="form-control" id="select_class_faculty">
                  <option  selected value="">Class Faculty*</option>
                  <?php foreach ($employee_data as $faculty): ?>
                    <option value="<?php echo $faculty->id; ?>"><?php echo $faculty->employee_lname.' '.$faculty->employee_fname; ?></option>
                  <?php endforeach; ?>
                  <option value="0">*TBA</option>
                </select>

              </div>
            </div>



            <div id="" class="form-group clearfix">




                <h4 class="pad-left-10 mrg-left-5">Change Room</h4>

                <div id="" class="col-md-3">
                  <select class="form-control" id="select_campus">
                    <option  selected="">Campus*</option>
                    <?php foreach ($campus_data as $campus): ?>
                      <option value="<?php echo $campus->id; ?>"><?php echo $campus->campus_code; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div id="" class="col-md-6">
                  <select class="form-control" id="select_building">
                    <option  selected value="">Building*</option>
                    <option value="0">*Open Building</option>
                  </select>
                </div>

                <div id="" class="col-md-3">
                  <select class="form-control" id="select_room">
                    <option  selected value="">Room*</option>
                  </select>
                </div>
 



            </div>




              <input type="hidden" id="sched_id" value="0">



              <div id="" class="form-group">
                <button class="btn-primary btn pull-right update_schedule">Update</button>

              </div>

            </div>
          </div>

        </div>
      </div>
    </div>




<style type="text/css">
  
  #dataTables_wrapper .dt-buttons{
    width: 80px !important;
    float: left !important;
  }


  #dataTables_wrapper .dt-buttons a{
    background-color: #00acd6 !important;
    border-radius: 3px !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: 1px solid transparent !important;
  }


  #dataTables_wrapper .dt-buttons a:hover{
        color: #fff !important;
    background-color: #31b0d5 !important;
    border-color: #269abc !important;
  }

  #schedule_table_length{
    float: left !important;
    width: 250px !important;
  }
</style>

<!-- view modal schedule -->

 <!-- DataTables -->
 <script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
 <script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.1.2/js/buttons.print.min.js"></script>




<script src='<?php echo base_url(); ?>js/moment.min.js'></script>
 <link href='<?php echo base_url(); ?>css/bootstrap-datetimepicker.css' rel='stylesheet' />
 <script src='<?php echo base_url(); ?>js/bootstrap-datetimepicker.min.js'></script>



 <script type="text/javascript">
  $(function () {
    $('#datetimepicker1').datetimepicker({
      format: 'LT',
      stepping: 5
    });
  }); 


  $(function () {
    $('#datetimepicker2').datetimepicker({
      format: 'LT',
      stepping: 5
    });
  });
</script>

<script type="text/javascript">


function schedule_get_data(sched_data){
  var schd = sched_data.split('-');


// <?php echo $course->class_sched_id.'-'.$course->class_event_id.'-'.$course->schedule_name.'-'.$course->faculty_in_charge_id.'-'.$course->day.'-'.$course->start_time.'-'.$course->end_time; ?>" data-toggle="modal" data-target="#view_schedule">View</button> </td>
                   
  $('.disable_sched_bttn').attr("id",schd[0]);

  $('input#sched_id').val(schd[0]);
  $('select#class_event').val(schd[1]);
  $('select#select_class_faculty').val(schd[3]);
  $('input#units').val(schd[7]);
  $('select#day_update').val(schd[4]);
  $('select#select_faculty').val(schd[10]);

  $('select#select_campus').val('');
  $('select#select_building').val('');


  $('select#select_room').html('<option value="'+schd[11]+'">Default</option>' );
  $('select#select_room').val(schd[11]);
 

  var start_time = schd[5];
  var end_time = schd[6];

  if(start_time >= 1200){
    var start = schd[8]+' PM';
  }else{
    var start = schd[8]+' AM';
  }

  if(end_time >= 1200){
    var end = schd[9]+' PM';
  }else{
    var end = schd[9]+' AM';
  }


  $('input#start_time').val(start);
  $('input#end_time').val(end);
 
}


$('.update_schedule').click(function(){

  var class_event = $('select#class_event').val();  


  var coordinator =  $('select#select_faculty').val();  
  var select_class_faculty = $('select#select_class_faculty').val();  

  var units = $('input#units').val(); 
  var day = $('select#day_update').val(); 
  var room_id = $('select#select_room').val();  

  var sched_id = $('input#sched_id').val();

  var start_time = checkTime($('input#start_time').val());
  var end_time = checkTime($('input#end_time').val());

  if(!start_time){
    start_time = '000';
  }else{
    if(start_time == 2400){
      start_time = 1200;
    }
  }

  if(!end_time){
    end_time = '000';
  }else{
    if(end_time == 2400){
      end_time = 1200;
    }
  }

  var data_input = class_event+'-'+select_class_faculty+'-'+units+'-'+day+'-'+start_time+'-'+end_time+'-'+sched_id+'-'+room_id+'-'+coordinator;

  //alert(data_input);


   $.ajax({
      'url' : base_url+'Scheduling/update_sched',
      'type' : 'POST',
      'data' : {'ajax_post' : data_input },
      'success' : function(data){
        if(data){

          //alert(data);

          window.location.href = base_url+'Scheduling/view_schedule';

        } 
      }
    });
 
});

 

$('.disable_sched_bttn').click(function(){
  var sched_id  = $(this).attr('id');
  window.location.href = base_url+'Scheduling/disable/'+sched_id; 
});

 $(function () {
    $("#schedule_table").DataTable({
      "iDisplayLength": 10,
      "aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
       dom: 'Blfrtip',
        buttons: [
            {
              extend: 'print',
              text: '<i class="fa fa-print" aria-hidden="true"></i> Temporary Print',
              customize: function ( win ) {
                       $(win.document.body)
                        .css( 'font-size', '7pt' );
 
                    $(win.document.body).find( 'table' )
                        .addClass( 'compact' )
                        .css( 'font-size', 'inherit' );
 
                }
            }
        ]
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
    var select_class_faculty = $(this).val();
    $('select#select_class_faculty').val(select_class_faculty);
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