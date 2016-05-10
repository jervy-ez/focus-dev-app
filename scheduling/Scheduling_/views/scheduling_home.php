<?php $this->load->module('scheduling'); ?>



<!-- DataTables -->
<link rel="stylesheet" href="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.css">

<!-- =============================================== -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <section class="content-header"><h1>Course Scheduler</h1></section>

  <!-- Main content -->
  <section class="content">

    <!-- row -->
    <div class="row">

    <div class="col-md-12">

        <!-- general form elements -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">General Information</h3>          
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <div class="box-body" style="display: block;">
            <div class="">
              <div class="row" id="">

                <div id="" class="col-md-2">
                  <select class="form-control" id="select_campus">
                    <option disabled="" selected="">Campus</option>
                    <?php foreach ($campus_data as $campus): ?>
                      <option value="<?php echo $campus->id; ?>"><?php echo $campus->campus_code; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div id="" class="col-md-2">
                  <select class="form-control" id="select_college">
                    <option disabled="" selected="">Select College</option>
                  </select>
                </div>

                <div id="" class="col-md-2">
                  <select class="form-control" id="select_dep_code">
                    <option disabled="" selected="">Dept Code</option>
                  </select>
                </div>



                <div id="" class="col-md-4">
                  <select class="form-control" id="select_course_program">
                    <option disabled="" selected="">Program</option>
                  </select>
                </div>

                <div id="" class="col-md-2 hide select_sem_list">
                  <select class="form-control mrg-left-10 input-sm" id="select_semester" onchange="select_sem(this.value);">
                    <option disabled="" selected="">Semester</option>
                    <option value="">View All</option>
                    <option value="1">1st Semester</option>
                    <option value="2">2nd Semester</option>
                    <option value="3">Summer</option>
                    <option value="0">Graduate Programs</option>
                  </select>
                  <div class="btn btn-sm btn-info mrg-left-10 hide" data-toggle="modal" data-target="#special_courses_selection">Special Course</div>

                </div>

                <div id="" class="col-md-1">
                  <div class="btn btn-primary btn-md pull-left search_table_program">Search</div>
                </div>
            </div>
          </div>


        </div>
        <!-- /.box-body -->

      </div>
      <!-- /.box -->






 
    </div>


      <div class="col-md-12">
        <div class="box course_table" style="display:none">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="course_table" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Course Code</th>
                    <th>Old Course Code</th>
                    <th>Course Title</th>
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
                     <td><?php echo $course->course_code_old; ?></td>
                     <td><?php echo $course->course_title; ?></td>
                     <td><?php echo $course->credit_units; ?></td> 
                     <td>
                       <a href="<?php echo base_url(); ?>Scheduling/set_schedule/<?php echo $course->class_subject_id.'-'.$course->campus_id.'-'.$course->college_id.'-'.$course->department_id.'-'.$course->program_id.'-0'; ?>" class="btn btn-primary btn-xs pull-left mrg-right-5">Set</a>
                     </td>
  
                     <td><?php echo $course->campus_code; ?></td>
                     <td><?php echo $course->college_code; ?></td>
                     <td><?php echo $course->department_code; ?></td>
                     <td><?php echo $course->program_name.' '.$course->school_year.' '.$course->prg_mjr_code; ?></td>
                     <td><?php echo $course->semester; ?></td>
                   </tr>
                 <?php endforeach; ?>

             </table>
           </div>
           <!-- /.box-body -->
         </div>
         <!-- /.box -->
       </div>





    <div class="col-md-12 hide">


      <div class="box box-primary collapsed-box">
        <div class="box-header with-border">
          <h3 class="box-title">Print Schedule</h3>  

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
            </button>
          </div>
          <!-- /.box-tools -->
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="display: none;">
            <div class="">
              <div class="row" id="">

                <div id="" class="col-md-3">
                  <select class="form-control" id="select_print_type">
                    <option disabled="" selected="">Select Print</option>
                    <option value="room">Room</option>
                    <option value="faculty">Faculty</option>
                  </select>
                </div>

                <div id="" class="col-md-3">
                  <select class="form-control" id="select_building">
                    <option disabled selected >Building*</option>
                    <?php foreach ($building_data as $building): ?>
                      <option value="<?php echo $building->id; ?>"><?php echo $building->building_code.' - '.ucwords(strtolower($building->building_desc)); ?></option>
                    <?php endforeach; ?>
                  </select>
                </div> 


                <div id="" class="col-md-3">
                   <select class="form-control" id="select_room">
                    <option disabled selected value="">Room*</option>
                  </select>
                </div>

                <div id="" class="col-md-3">
                  <div class="btn btn-primary btn-md pull-left print_schedule_type">Print</div>
                </div>
            </div>
          </div>




        </div>
        <!-- /.box-body -->
      </div>

 
    </div>



    <div class="col-md-12">

      

          
 <div id="" class="hide">
   <iframe src="<?php echo base_url(); ?>Scheduling/room_calendar/0" width="100%" height="735px" id="room_iframe" class="tab_iframe"></iframe>
   <iframe src="<?php echo base_url(); ?>Scheduling/faculty_calendar/0-0" width="100%" height="735px" id="faculty_iframe" class="tab_iframe"></iframe>
 </div>


    </div>



       
     </div>
     <!-- /.box -->
   </section>
   <!-- /.content -->
 </div>  
 <!-- /.content-wrapper -->

<!-- slecial course selection -->

<div class="modal fade" id="special_courses_selection" tabindex="-1" role="dialog">
  <div class="modal-dialog  modal-lg">
    <div class="modal-content">

      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Select Course</h4>
          </div>
          <div class="modal-body">

          

          <div id="" class="all_courses">

           <table id="all_courses_table" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Course Code</th>
                <th>Old Course Code</th>
                <th>Course Title</th>
                <th>Units</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>

              <?php foreach ($all_courses as $course): ?>
                <tr>
                 <td><?php echo $course->course_code; ?></td>
                 <td><?php echo $course->course_code_old; ?></td>
                 <td><?php echo $course->course_title; ?></td>
                 <td><?php echo $course->credit_units; ?></td> 
                 <td>
                   <div id="<?php echo $course->id; ?>" class="btn btn-primary btn-xs pull-left mrg-right-5 special_set">Set</div>
                 </td>
               </tr>
             <?php endforeach; ?>

           </table>
         </div>



          </div>
        </div>

      </div>
    </div>
  </div>

<!-- slecial course selection -->
 
 

 <!-- DataTables -->
 <script src="<?php echo base_url(); ?>plugins/datatables/jquery.dataTables.min.js"></script>
 <script src="<?php echo base_url(); ?>plugins/datatables/dataTables.bootstrap.min.js"></script>




 

 <script>
  $(function () {



    
    
    $("#all_courses_table").DataTable({
      "iDisplayLength": 10,
      "aLengthMenu": [[10, 25, 50, 75, -1], [10, 25, 50, 75, "All"]]

    });
 



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





$('.special_set').click(function(){
  var class_subject_id = $(this).attr('id');
  var campus_id = $('select#select_campus').val();
  var college_id = $('select#select_college').val();
  var department_id = $('select#select_dep_code').val();
  var program_id = $('select#select_course_program').val();
  window.location.href = base_url+'Scheduling/set_schedule/'+class_subject_id+'-'+campus_id+'-'+college_id+'-'+department_id+'-'+program_id+'-1'; 
});

  $("select#select_campus").change(function(){
    $("select#select_college").val('');
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
        $("select#select_college").empty().append(data);
      } 
    }
  });
});


$("select#select_college").change(function(){
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



$('.search_table_program').click(function(){

  $('.course_table').show();

  var select_campus = $("select#select_campus").val()
  var select_college = $("select#select_college").val();
  var select_dep_code = $("select#select_dep_code").val();
  var select_course_program = $("select#select_course_program").val();

  var select_campus_text = $("select#select_campus").find('option:selected').text();
  var select_college_text = $("select#select_college").find('option:selected').text();
  var select_dep_code_text = $("select#select_dep_code").find('option:selected').text();
  var select_course_program_text = $("select#select_course_program").find('option:selected').text();


 
  //alert($(this).val());
  var table = $('#course_table').dataTable();


  table.fnFilter(select_campus_text,'5');
  table.fnFilter(select_college_text,'6');
  table.fnFilter(select_dep_code_text,'7');
  table.fnFilter(select_course_program_text,'8');


  $('#course_table_filter select#select_semester').remove();
  $('#course_table_filter').append($('.select_sem_list').html());
 

 // alert(select_campus_text+'--'+select_college_text+'--'+select_dep_code_text+'--'+select_course_program_text)
});



function select_sem(val){
  var course_table = $('#course_table').dataTable();
  var search = val;
  course_table.fnFilter(search,'9');
}

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



$("select#select_room").change(function(){
  $('#room_iframe').hide();
  $('p.please_select_room').hide();
  var room_id = $(this).val();


  var room_name = $(this).find('option:selected').text();
  var bldg_name = $("select#select_building").find('option:selected').text();

  room_name = room_name.replace(/\s+/g, '-').toLowerCase();
  bldg_name = bldg_name.replace(/\s+/g, '-').toLowerCase();

alert(room_name+'  '+bldg_name);


/*
  |1-Room Name
|2-Faculty Name
*/

  var room_url = '<?php echo base_url(); ?>Scheduling/room_calendar/'+room_id+'-1_'+bldg_name+'.'+room_name;



  //$('iframe#faculty_iframe')[0].contentWindow.location.reload(true);
  $('iframe#room_iframe').attr('src', room_url);

  $('.room_loading_frame').show();

  setTimeout(function(){  
    $('iframe#room_iframe').show();
    $('.room_loading_frame').hide();
  },2000);

});


$('.print_schedule_type').click(function(){
  document.getElementById('room_iframe').contentWindow.print();
});

</script>


