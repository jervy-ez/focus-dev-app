<?php $this->load->module('scheduling'); ?>
<style>

  #calendar {
    max-width: 100%;
    margin: 0 auto;
  }

  #calendar .fc-toolbar,.fc-day-grid{
    display: none;
  }

  #calendar .fc-widget-header table th{
    display: none;
  }

</style>




<!-- =============================================== -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <section class="content-header">
    <h1>
      Course Scheduler
      <small><?php echo $course_details->course_code; ?> - <?php echo $course_details->course_title; ?> (<?php echo $course_details->credit_units; ?> Units)</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Course Scheduler</li>
    </ol>
  </section>

  <!-- Main content -->
  <section class="content">

    <!-- row -->
    <div class="row">

      <div id="" class="">



      </div>



      <div class="col-md-12">

        <div class="box  box-danger  collapsed-box box-solid" id="schedule_error_box" style="display:none">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="icon fa fa-ban"></i> Alert! &nbsp; <small style="color:#fff;">You have conflicts.</small></h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
              <button type="button" class="btn btn-box-tool" id="close_alert_box" aria-hidden="true" ><i class="fa fa-times"></i></button>
            </div>
            <!-- /.box-tools -->
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <p id="conflict_error_msg"></p>
            <div class="box-footer pad-none-imp">
             <div class="row mrg-top-10">
                <div class="col-md-6"> <input type="text" class="form-control remarks_over_ride" value="" id="remarks_over_ride" placeholder="Remarks"></div>
                <button type="button" class="btn btn-danger  over_ride_schedule pull-left">Accept Schedule</button>
              </div>
            </div>
          </div>
          <!-- /.box-body -->
        </div>



        <!-- general form elements -->
        <div class="box box-primary collapsed-box">
          <div class="box-header with-border">

            <?php // var_dump($course_details); ?>
            <h3 class="box-title">General Information</h3>
            <small> &nbsp; College: <strong><?php echo $course_details->college_name; ?></strong>    &nbsp;  &nbsp;  &nbsp;  &nbsp;  Department: <strong><?php echo $course_details->department_name; ?></strong>   &nbsp;  &nbsp;  &nbsp;  &nbsp; Program: <strong><?php echo $course_details->program_name; ?></strong></small>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
        </div>
        <!-- /.box -->





      </div>
      <!-- /.box-body --> 



      <div class="col-md-12">

        <!-- general form elements -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Set Schedule</h3>
            <small>&nbsp; Please fill the <strong>required*</strong> fields below.</small>

            <button type="button" class="btn btn-warning btn-xs pull-right select_course_btn" data-toggle="modal" data-target="#select_course">Select Course</button>
          </div>
          <!-- /.box-header -->
          <!-- form start -->


          <div class="box-body">

            <div class="form-group">
              <div class="row" id="">

                <h4 class="pad-left-10 mrg-left-5">Schedule Setup</h4>


                <div class="col-md-3" id="">
                  <input type="text" class="form-control" readonly="readonly" name="schedule_name" id="schedule_name" placeholder="Schedule Name" value="">
                </div>



                <div id="" class="col-md-3">
                  <select class="form-control" id="term">
                    <option selected value="">Academic Term*</option>
                    <?php foreach ($academic_year as $ay_data): ?>
                      <option value="<?php echo $ay_data->id; ?>"><?php echo $ay_data->school_year.'-'.$ay_data->semester ; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>


                <div class="col-md-2" id="">
                  <select class="form-control" id="class_block">
                    <option selected value="" >Course Yr Block*</option>
                    <?php foreach ($class_block as $data):?>

                      <?php // if(intval($data->year_level) ==  $course_details->year_level ): ?>
                       <option value="<?php echo $data->id; ?>"><?php echo $data->year_level.' '.$data->block; ?></option>
                      <?php // endif; ?>

                    <?php endforeach; ?>
                    <option value="0">*Class / Open Block</option>
                  </select>
                </div>

                <div class="col-md-2" id="">
                  <select class="form-control" id="class_event">
                    <option selected value="">Class Event*</option>
                    <?php foreach ($class_event_data as $class_event): ?>
                      <option value="<?php echo $class_event->id; ?>"><?php echo $class_event->class_event; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>



                <div class="col-md-2" id="">
                  <select class="form-control" id="class_units">
                    <option  selected value="">Unit(s)*</option>
                    <?php
                    $unit_limit = $course_details->credit_units;
                    for ($x = 0.5; $x <= $unit_limit; $x = $x + 0.5){
                      echo '<option value="'.$x.'">'.$x.'</option>';
                    }
                    ?>
                  </select>
                  <script type="text/javascript"> $('select#class_units').val('<?php echo intval($course_details->credit_units); ?>');  </script>
                </div>


              </div>


            </div>




            <div class=" clearfix">


              <div class="row" id="">
                <div class="col-md-6" id="">


                  <div id="" class="form-control">
                    <p class="pull-left mrg-right-10"><label class="" for="day_sun"><input type="checkbox" id="day_sun" class=""> Sun</label></p>
                    <p class="pull-left mrg-right-10 mrg-left-5"><label class="" for="day_mon"><input type="checkbox" id="day_mon" class=""> Mon</label></p>
                    <p class="pull-left mrg-right-10 mrg-left-5"><label class="" for="day_tue"><input type="checkbox" id="day_tue" class=""> Tue</label></p>
                    <p class="pull-left mrg-right-10 mrg-left-5"><label class="" for="day_wed"><input type="checkbox" id="day_wed" class=""> Wed</label></p>
                    <p class="pull-left mrg-right-10 mrg-left-5"><label class="" for="day_thu"><input type="checkbox" id="day_thu" class=""> Thu</label></p>
                    <p class="pull-left mrg-right-10 mrg-left-5"><label class="" for="day_fri"><input type="checkbox" id="day_fri" class=""> Fri</label></p>
                    <p class="pull-left mrg-right-10 mrg-left-5"><label class="" for="day_sat"><input type="checkbox" id="day_sat" class=""> Sat</label></p>
                    <p class="pull-left mrg-right-10 mrg-left-5"><label class="tba_chk" for="day_tba"><input type="checkbox" id="day_tba" class=""> TBA</label></p>


                  </div>
                </div>

                <div id="" class="">
                  <div id="" class="col-md-3">
                    <div class="form-group">
                      <div class='input-group date' id='datetimepicker1'>
                        <input type='text' class="form-control select_all" id="start_time" value="" placeholder="HH:MM NN*" />
                        <span class="input-group-addon">
                          <span class="glyphicon glyphicon-chevron-down"></span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>

                <div id="" class="">
                  <div id="" class="col-md-3">
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


 
              </div>



            </div>
            <!-- /.box-body -->


            <div class="form-group">
              <div class="row" id="">

                <h4 class="pad-left-10 mrg-left-5">Room Selection</h4>

                <div id="" class="col-md-3">
                  <select class="form-control" id="select_campus">
                    <option  selected="">Select Campus*</option>
                    <?php foreach ($campus_data as $campus): ?>
                      <option value="<?php echo $campus->id; ?>"><?php echo $campus->campus_code; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div id="" class="col-md-5">
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
 

                <div id="" class="clearfix"></div>
                <h4 class="pad-left-10 mrg-left-5 mrg-top-20">Faculty Selection</h4>

                <div id="" class="col-md-3 hide">
                  <select class="form-control" id="select_faculty_campus">
                    <option selected="">Select Faculty Campus*</option>
                    <?php foreach ($campus_data as $campus): ?>
                      <option value="<?php echo $campus->id; ?>"><?php echo $campus->campus_code; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>



                <div id="" class="col-md-2 hide">
                  <select class="form-control" id="select_college">
                    <option selected="">Select College*</option>
                  </select>
                </div>


                <div class="col-md-3" id="">
                  <select class="form-control" id="select_faculty">
                    <option  selected value="">Coordinator*</option>
                    <?php foreach ($employee_data as $faculty): ?>
                      <option value="<?php echo $faculty->id; ?>"><?php echo $faculty->employee_lname.' '.$faculty->employee_fname; ?></option>
                    <?php endforeach; ?>
                    <option value="0">*TBA</option>
                  </select>
                </div>


                <div class="col-md-3" id="">
                  <select class="form-control" id="select_class_faculty">
                    <option  selected value="">Class Faculty*</option>
                    <?php foreach ($employee_data as $faculty): ?>
                      <option value="<?php echo $faculty->id; ?>"><?php echo $faculty->employee_lname.' '.$faculty->employee_fname; ?></option>
                    <?php endforeach; ?>
                    <option value="0">*TBA</option>
                  </select>
                </div>




              </div>

            </div>



            <div class="box-footer">
              <button type="button" class="btn btn-success check_schedule pull-right">Submit</button>
            </div>




          </div>
          <!-- /.box -->



        </div>
        <!-- /.box-body --> 



        <div class="">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#fa-icons" data-toggle="tab" aria-expanded="true">Class</a></li>
              <li class=""><a href="#glyphicons" data-toggle="tab" aria-expanded="false">Room</a></li>
              <li class=""><a href="#faculty_tab" data-toggle="tab" aria-expanded="false">Faculty</a></li>
            </ul>
            <div class="tab-content">
              <!-- Font Awesome Icons -->
              <div class="tab-pane active" id="fa-icons">
                <section id="new">
                  <h4 class="page-header">Class Schedule</h4>
                  <p class="please_select_block" >Please select academic term and then  class block.</p>
                  <div id="" class="class_loading_frame" style="display:none; height: 735px;"><div id="" class="text-center"><i class="fa fa-cog fa-5x fa-spin"></i><br />Loading</div></div>
                  <iframe src="<?php echo base_url(); ?>Scheduling/class_calendar/0-0" width="100%" height="735px" id="class_iframe" class="tab_iframe"></iframe>
                </section>   
              </div>
              <!-- /#fa-icons -->

              <!-- glyphicons-->
              <div class="tab-pane" id="glyphicons">


                <section id="new">
                  <h4 class="page-header">Room Schedule</h4>
                  <p class="please_select_room" >Please select academic term and then room.</p>
                  <div id="" class="room_loading_frame" style="display:none; height: 735px;"><div id="" class="text-center"><i class="fa fa-cog fa-5x fa-spin"></i><br />Loading</div></div>
                  <iframe src="<?php echo base_url(); ?>Scheduling/room_calendar/0-0" width="100%" height="735px" id="room_iframe" class="tab_iframe"></iframe>
                </section>
              </div>
              <!-- /#ion-icons -->

              <!-- faculty-->
              <div class="tab-pane" id="faculty_tab">
                <section id="new">
                  <h4 class="page-header">Faculty Schedule</h4>
                  <p class="please_select_faculty" >Please select academic term  and then faculty.</p>
                  <div id="" class="faculty_loading_frame" style="display:none; height: 735px;"><div id="" class="text-center"><i class="fa fa-cog fa-5x fa-spin"></i><br />Loading</div></div>
                  <iframe src="<?php echo base_url(); ?>Scheduling/faculty_calendar/0-0" width="100%" height="735px" id="faculty_iframe" class="tab_iframe"></iframe>
                </section>   
              </div>
              <!-- faculty-->


            </div>
            <!-- /.tab-content -->
          </div>




        </div>



      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>  
  <!-- /.content-wrapper -->


  <div class="modal fade success_added_schedule" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">

        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Success!</h4>
            </div>
            <div class="modal-body">
              <p>New schedule is now added!</p>
            </div>
          </div>

        </div>
      </div>
    </div>




    <!-- Modal -->
    <div class="modal fade" id="select_course" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Select Course</h4>
          </div>
          <div class="modal-body">
           
           <!-- selector -->

                <div id="" class="hide select_sem_list">
                  <select class="form-control mrg-left-10 input-sm pull-right" id="select_semester" onchange="select_sem(this.value);">
                    <option  selected="">Semester</option>
                    <option value="">View All</option>
                    <option value="1">1st Semester</option>
                    <option value="2">2nd Semester</option>
                    <option value="3">Summer</option>
                    <option value="0">Graduate Programs</option>
                  </select>
                </div>
           
           <!-- selector -->



           <!-- table -->

        <div class="box course_table" style="display:block;">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="course_table" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Course Code</th>
                    <th>Course Title</th>
                    <th>Descriptive Title</th>
                    <th>Units</th>
                    <th>Action</th>


                    <th>aa</th> 
                    <th>bb</th> 
                    <th>cc</th> 
                    <th>dd</th> 
                    <th>sem</th>
                  </tr>
                </thead>
                <tbody>
                 
                  <?php foreach ($courses_data as $course): ?>
                    <tr>
                     <td><?php echo $course->course_code; ?></td>
                     <td><?php echo $course->course_title; ?></td>
                     <td><?php //echo $course->descriptive_title; ?></td>
                     <td><?php echo $course->credit_units; ?></td> 
                     <td>
                       <a href="<?php echo base_url(); ?>Scheduling/set_schedule/<?php echo $course->class_subject_id.'-'.$course->campus_id.'-'.$course->college_id.'-'.$course->department_id.'-'.$course->program_id; ?>" class="btn btn-primary btn-xs pull-left mrg-right-5">Set</a>
                       <a href="#" class="btn btn-info btn-xs pull-left hide">Edit</a>
                     </td>
  
                     <td><?php echo $course->campus_code; ?></td>
                     <td><?php echo $course->college_code; ?></td>
                     <td><?php echo $course->department_code; ?></td>
                     <td><?php echo $course->program_name; ?></td>
                     <td><?php echo $course->semester; ?></td>
                   </tr>
                 <?php endforeach; ?>

               </tfoot>
             </table>
     
           <!-- /.box-body -->
         </div>
         <!-- /.box -->
       </div>


<!-- table -->
          </div> 
        </div>
      </div>
    </div>






    <link href='<?php echo base_url(); ?>css/fullcalendar.css' rel='stylesheet' />
    <link href='<?php echo base_url(); ?>css/fullcalendar.print.css' rel='stylesheet' media='print' />
    <script src='<?php echo base_url(); ?>js/moment.min.js'></script>


    <link href='<?php echo base_url(); ?>css/bootstrap-datetimepicker.css' rel='stylesheet' />
    <script src='<?php echo base_url(); ?>js/bootstrap-datetimepicker.min.js'></script>

    <script src='<?php echo base_url(); ?>js/fullcalendar.min.js'></script> 
    <script src="<?php echo base_url(); ?>plugins/iCheck/icheck.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url(); ?>plugins/iCheck/all.css">
 

   <!-- DataTables -->
   <script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
   <script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.min.js"></script>
   <!-- DataTables -->


    <script type="text/javascript">

      function select_sem(val){
        var course_table = $('#course_table').dataTable();
        var search = val;
        course_table.fnFilter(search,'9');
      }

      $(function () {
    $("#course_table").DataTable({
      "iDisplayLength": 10,
      "aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]],
      "aoColumnDefs": [{ "bVisible": false, "aTargets":[5] },{ "bVisible": false, "aTargets":[6] },{ "bVisible": false, "aTargets":[7] },{ "bVisible": false, "aTargets":[8] },{ "bVisible": false, "aTargets":[9] },{"targets": 4,"orderable": false}],
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

//iCheck for checkbox and radio inputs
$('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
  checkboxClass: 'icheckbox_minimal-blue',
  radioClass: 'iradio_minimal-blue'
});


$('button#close_error_box').click(function(){
  $(this).parent().hide();
});

$('button#close_alert_box').click(function(){
  $(this).parent().parent().parent().hide();
});


$('.select_course_btn').click(function(){
 
 

  var select_campus_text = "<?php echo $course_details->campus_code; ?>";
  var select_college_text = "<?php echo $course_details->college_code; ?>";
  var select_dep_code_text = "<?php echo $course_details->department_code; ?>";
  var select_course_program_text = "<?php echo $course_details->program_name; ?>";
 
 
  //alert($(this).val());
  var table = $('#course_table').dataTable();


  table.fnFilter(select_campus_text,'5');
  table.fnFilter(select_college_text,'6');
  table.fnFilter(select_dep_code_text,'7');
  table.fnFilter(select_course_program_text,'8');


  $('#course_table_filter select#select_semester').remove();
  $('#course_table_filter').append($('.select_sem_list').html());
 
 
});

$("select#select_dep_code").change(function(){
  $("select#select_course_program").val();
  var department_id = $(this).val();
  $('.course_table').hide();
  $.ajax({
    'url' : base_url+'Scheduling/catch_department',
    'type' : 'POST',
    'data' : {'ajax_post' : department_id },
    'success' : function(data){
      if(data){
        //alert(data);
        $("select#select_course_program").empty().append(data);
      } 
    }
  });
});




$("select#select_college_set").change(function(){
  $("select#select_dep_code").val();
  $("select#select_course_program").val();
  $('.course_table').hide();

  var college_id = $(this).val();
  $.ajax({
    'url' : base_url+'Scheduling/catch_college',
    'type' : 'POST',
    'data' : {'ajax_post' : college_id },
    'success' : function(data){
      if(data){
        //alert(data);
        $("select#select_dep_code").empty().append(data);
      } 
    }
  });
});






$("select#select_campus_set").change(function(){

    $("select#select_college_set").val('');
    $("select#select_dep_code").val('');
    $("select#select_course_program").val('');
  $('.course_table').hide();



  var campus_id = $(this).val();
  $.ajax({
    'url' : base_url+'Scheduling/catch_campus',
    'type' : 'POST',
    'data' : {'ajax_post' : campus_id },
    'success' : function(data){
      if(data){
        //alert(data);
        $("select#select_college_set").empty().append(data);
      } 
    }
  });

});




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
//alert(data);
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
  /*
  var class_block = $('select#class_block').val();
  var course_id = '<?php echo $course_details->class_subject_id; ?>';
*/
  $('#faculty_iframe').hide();
  $('p.please_select_faculty').hide();

  var faculty_id = $(this).val();
  var data_faculty = $(this).val();

  $("select#select_class_faculty").val(faculty_id);
  var faculty_url = '<?php echo base_url(); ?>Scheduling/faculty_calendar/'+data_faculty+'-'+term;

//$faculty_id,$term_id,$block_id,$course_id
//$faculty_id,$term_id,$block_id,$course_id
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


$("select#select_class_faculty").change(function(){
  $('#faculty_iframe').hide();
  $('p.please_select_faculty').hide();

  var term = $('select#term').val();

  // var class_block = $('select#class_block').val();
  // var course_id = '<?php echo $course_details->class_subject_id; ?>';


  var faculty_id = $(this).val();
  var faculty_url = '<?php echo base_url(); ?>Scheduling/faculty_calendar/'+faculty_id+'-'+term;

 var post_data = faculty_id;
 
  $.ajax({
    'url' : base_url+'Scheduling/count_schedule',
    'type' : 'POST',
    'data' : {'ajax_post' : post_data },
    'success' : function(data){
      if(data){
        $('input#schedule_name').val('Schedule '+data);
//$("select#select_room").empty().append(data);
} 
}
}); 




//$('iframe#faculty_iframe')[0].contentWindow.location.reload(true);
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
  $("select#select_class_faculty").val('');

  var campus_id = $(this).val();
  $.ajax({
    'url' : base_url+'Scheduling/catch_faculty_campus',
    'type' : 'POST',
    'data' : {'ajax_post' : campus_id },
    'success' : function(data){
      if(data){
      //alert(data);
        $("select#select_faculty").empty().append('<option selected="">Select Coordinator*</option><option value="0">*TBA</option>').append(data);
        $("select#select_class_faculty").empty().append('<option selected="">Select Class Faculty*</option><option value="0">*TBA</option>').append(data);
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


$('.tba_chk').delegate('#day_tba', 'change', function () {
    if (this.checked) {
     $('input:checkbox').removeAttr('checked');
     $('#day_tba').prop('checked', true);
    }
});


$('.input#day_tba').click(function(){
   $('input:checkbox').removeAttr('checked');
  /*
  if($('input#day_tba').prop('checked')) {
     $('input:checkbox').removeAttr('checked');
    if(days!=''){
      days = 'TBA';
    }else{
      days = 'TBA';
    }
  }**/

});






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
/*

  if(!$('select#select_faculty_campus').val()){
    has_error = 1;
    $('select#select_faculty_campus').parent().addClass('has-error');
  }else{
    $('select#select_faculty_campus').parent().removeClass('has-error');
    var select_faculty_campus = $('select#select_faculty_campus').val();
  }
*/

/*
  if(!$('select#select_college').val()){
    has_error = 1;
    $('select#select_college').parent().addClass('has-error');
  }else{
    $('select#select_college').parent().removeClass('has-error');
    var select_college = $('select#select_college').val();
  }
*/

  if(!$('select#select_building').val()){
    has_error = 1;
    $('select#select_building').parent().addClass('has-error');
  }else{
    $('select#select_building').parent().removeClass('has-error');
    var select_building = $('select#select_building').val();
  }

  if(!$('select#select_room').val()){
    has_error = 1;
    $('select#select_room').parent().addClass('has-error');
  }else{
    $('select#select_room').parent().removeClass('has-error');
    var select_room = $('select#select_room').val();
  }


  if(!$('select#select_campus').val()){
    has_error = 1;
    $('select#select_campus').parent().addClass('has-error');
  }else{
    $('select#select_campus').parent().removeClass('has-error');
    var select_campus = $('select#select_room').val();
  }

  var start_time = checkTime($('input#start_time').val());
  var end_time = checkTime($('input#end_time').val());


  if(!start_time){
    start_time = '000';
  }

  if(!end_time){
    end_time = '000';
  }

  if(!$('select#select_faculty').val()){
    has_error = 1;
    $('select#select_faculty').parent().addClass('has-error');
  }else{
    $('select#select_faculty').parent().removeClass('has-error');
    var select_faculty = $('select#select_faculty').val();
  }

  if(!$('select#select_class_faculty').val()){
    has_error = 1;
    $('select#select_class_faculty').parent().addClass('has-error');
  }else{
    $('select#select_class_faculty').parent().removeClass('has-error');
    var select_class_faculty = $('select#select_class_faculty').val();
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

  if($('input#day_tba').prop('checked')) {
    if(days!=''){
      days = 'TBA';
    }else{
      days = 'TBA';
    }
  }


  if(days==''){
    has_error = 1;
    $('input#day_sun').parent().parent().parent().parent().parent().addClass('has-error');
  }else{
    $('input#day_sun').parent().parent().parent().parent().parent().removeClass('has-error');
  }

  var course_id = <?php echo $course_id; ?>;

  var class_block = $('select#class_block').val();
//  var term ='1'; //
var class_event = $('select#class_event').val();
//var class_year = $('select#class_year').val();
$("#datePiccc").attr("readonly", false);
var schedule_name = $('input#schedule_name').val();
var input_data = days+'|'+select_room+'|'+start_time+'|'+end_time+'|'+select_faculty+'|'+course_id+'|'+class_block+'|'+term+'|'+class_event+'|'+select_class_faculty+'|'+schedule_name+'|'+class_units;
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

  var course_id = <?php echo $course_id; ?>;
  var class_block = $('select#class_block').val();

 
  var term = $('select#term').val();
  var class_event = $('select#class_event').val();
  var select_class_faculty = $('select#select_class_faculty').val();
  var schedule_name = $('input#schedule_name').val();
  var class_units = $('select#class_units').val();


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
   var input_data = days+'|'+select_room+'|'+start_time+'|'+end_time+'|'+select_faculty+'|'+course_id+'|'+class_block+'|'+term+'|'+class_event+'|'+select_class_faculty+'|'+schedule_name+'|'+class_units+'|'+remarks_over_ride;

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