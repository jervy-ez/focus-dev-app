

<!-- =============================================== -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

  <?php

  $data['title'] = 'Course Scheduler';
  $data['subject'] = 'CS11 - Intro to Computer Science (3 Units)';

  ?>


  <?php $this->load->view('/assets/bread_crumbs',$data) ?>

  <!-- Main content -->
  <section class="content">

    <!-- row -->
    <div class="row">

    <div id="" class="">


      
    </div>



      <div class="col-md-12">

        <div class="alert alert-danger alert-dismissible" id="schedule_error_box" style="display:none">
          <button type="button" class="close" id="close_error_box" aria-hidden="true" >×</button>
          <h4><i class="icon fa fa-ban"></i> Alert! &nbsp; <small style="color:#fff; ">You have conflicts to the following schedules.</small></h4>
          <p id="conflict_error_msg"></p>
        </div>


        <!-- general form elements -->
        <div class="box box-primary collapsed-box">
          <div class="box-header with-border">
            <h3 class="box-title">General Information</h3>
            <small> &nbsp; AY. Year <strong>2016-2017</strong> &nbsp;  &nbsp;  &nbsp;  &nbsp; Program: <strong>Master of Arts in Teaching, Major in Physics</strong> &nbsp;  &nbsp;  &nbsp;  &nbsp; Campus: <strong>Main Campus</strong></small>
            <div class="box-tools pull-right gen_info_tools">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
            </div>
          </div>
          <!-- /.box-header -->
          <!-- form start -->

          <div class="box-body">


            <div class="form-group">
              <div class="row" id="">

                <div id="" class="col-md-4">
                  <select class="form-control">
                    <option disabled selected>Academic Year</option>
                    <?php foreach ($academic_year as $ay_data): ?>
                      <option value="<?php echo $ay_data->school_year; ?>"><?php echo $ay_data->school_year; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>


                <div id="" class="col-md-4">
                  <select class="form-control">
                    <option disabled selected >Campus</option>
                    <option>Monday</option>
                    <option>Tuesday</option>
                    <option>Wednesday</option>
                    <option>Thursday</option>
                    <option>Friday</option>
                    <option>Saturday</option>
                  </select>
                </div>


                <div id="" class="col-md-4">
                  <select class="form-control">
                    <option disabled selected>Program</option>
                    <option>option 2</option>
                    <option>option 3</option>
                    <option>option 4</option>
                    <option>option 5</option>
                  </select>
                </div>


            </div>
          </div>








        </div>
        <!-- /.box-body -->

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
        </div>
        <!-- /.box-header -->
        <!-- form start -->


        <div class="box-body">

          <div class="form-group">
            <div class="row" id="">
              <div class="col-md-2" id="">
                <select class="form-control" id="select_faculty">
                  <option disabled selected value="">Course Faculty*</option>
                  <?php foreach ($employee_data as $employee): ?>
                    <option value="<?php echo $employee->id; ?>"><?php echo $employee->employee_lname.' '.$employee->employee_fname.' '.$employee->employee_mname; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>


              <div class="col-md-2" id="">
                <select class="form-control" id="select_class_faculty">
                  <option disabled selected value="">Class Faculty*</option>
                  <?php foreach ($employee_data as $employee): ?>
                    <option value="<?php echo $employee->id; ?>"><?php echo $employee->employee_lname.' '.$employee->employee_fname.' '.$employee->employee_mname; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-2" id="">
                <input type="text" class="form-control" name="schedule_name" id="schedule_name" placeholder="Schedule Name" value="Schedule 1">
              </div>

              <div id="" class="col-md-4">
                <select class="form-control" id="select_building">
                  <option disabled selected >Building*</option>
                  <?php foreach ($building_data as $building): ?>
                    <option value="<?php echo $building->id; ?>"><?php echo $building->building_code.' - '.ucwords(strtolower($building->building_desc)); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div id="" class="col-md-2">
                <select class="form-control" id="select_room">
                  <option disabled selected value="">Room*</option>
                  <?php // foreach ($room_data as $room): ?>
                    <!-- <option value="<?php //echo $room->id; ?>"><?php //echo $room->room_name; ?></option> -->
                  <?php // endforeach; ?>
                </select>
              </div>

            </div>
          </div>



          <div class="form-group">
            <div class="row" id="">
              <div class="col-md-3" id="">
                <select class="form-control" id="class_year">
                  <option disabled selected >Year</option>
                  <option value="1">First Year</option>
                  <option value="2">Second Year</option>
                  <option value="3">Third Year</option>
                  <option value="4">Fourth Year</option>
                  <option value="5">Fifth Year</option>
                </select>
              </div>

              <div class="col-md-3" id="">
                <select class="form-control" id="class_block">
                  <option disabled selected >Block*</option>
                  <option value="1">A</option>
                  <option value="2">B</option>
                  <option value="3">C</option>
                  <option value="4">D</option>
                  <option value="5">E</option>
                  <option value="6">F</option>
                </select>
              </div>

              <div id="" class="col-md-3">
                <select class="form-control" id="term">
                  <option disabled selected>Term*</option>
                  <?php foreach ($term_data as $term): ?>
                      <option value="<?php echo $term->id; ?>"><?php echo $term->semester; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-3" id="">
                <select class="form-control" id="class_event">
                  <option disabled selected >Class Event*</option>
                  <?php foreach ($class_event_data as $class_event): ?>
                    <option value="<?php echo $class_event->id; ?>"><?php echo $class_event->class_event; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

            </div>
          </div>




          <div class="form-group clearfix">


            <div class="row" id="">
              <div class="col-md-8" id="">


                <div id="" class="form-control">
                  <p class="pull-left mrg-right-10"><label class="" for="day_sun"><input type="checkbox" id="day_sun" class="minimal"> Sun</label></p>
                  <p class="pull-left mrg-right-10 mrg-left-5"><label class="" for="day_mon"><input type="checkbox" id="day_mon" class="minimal"> Mon</label></p>
                  <p class="pull-left mrg-right-10 mrg-left-5"><label class="" for="day_tue"><input type="checkbox" id="day_tue" class="minimal"> Tue</label></p>
                  <p class="pull-left mrg-right-10 mrg-left-5"><label class="" for="day_wed"><input type="checkbox" id="day_wed" class="minimal"> Wed</label></p>
                  <p class="pull-left mrg-right-10 mrg-left-5"><label class="" for="day_thu"><input type="checkbox" id="day_thu" class="minimal"> Thu</label></p>
                  <p class="pull-left mrg-right-10 mrg-left-5"><label class="" for="day_fri"><input type="checkbox" id="day_fri" class="minimal"> Fri</label></p>
                  <p class="pull-left mrg-right-10 mrg-left-5"><label class="" for="day_sat"><input type="checkbox" id="day_sat" class="minimal"> Sat</label></p>


                </div>
              </div>

              <div id="" class="col-md-2">
                <select class="form-control" style="padding:5px;" id="start_time">
                  <option disabled selected value="">Start*</option>
                  <option value="600">6:00 AM</option>
                  <option value="630">6:30 AM</option>
                  <option value="700">7:00 AM</option>
                  <option value="730">7:30 AM</option>
                  <option value="800">8:00 AM</option>
                  <option value="830">8:30 AM</option>
                  <option value="900">9:00 AM</option>
                  <option value="930">9:30 AM</option>
                  <option value="1000">10:00 AM</option>
                  <option value="1030">10:30 AM</option>
                  <option value="1100">11:00 AM</option>
                  <option value="1130">11:30 AM</option>
                  <option value="1200">12:00 PM</option>
                  <option value="1230">12:30 PM</option>
                  <option value="1300">1:00 PM</option>
                  <option value="1330">1:30 PM</option>
                  <option value="1400">2:00 PM</option>
                  <option value="1430">2:30 PM</option>
                  <option value="1500">3:00 PM</option>
                  <option value="1530">3:30 PM</option>
                  <option value="1600">4:00 PM</option>
                  <option value="1630">4:30 PM</option>
                  <option value="1700">5:00 PM</option>
                  <option value="1730">5:30 PM</option>
                  <option value="1800">6:00 PM</option>
                  <option value="1830">6:30 PM</option>
                  <option value="1900">7:00 PM</option>
                  <option value="1930">7:30 PM</option>
                  <option value="2000">8:00 PM</option>
                  <option value="2030">8:30 PM</option>


                </select>

              </div>


              <div id="" class="col-md-2">
                <select class="form-control" style="padding:5px;" id="end_time">
                  <option disabled selected value="" >Finish*</option>
                  <option value="600">6:00 AM</option>
                  <option value="630">6:30 AM</option>
                  <option value="700">7:00 AM</option>
                  <option value="730">7:30 AM</option>
                  <option value="800">8:00 AM</option>
                  <option value="830">8:30 AM</option>
                  <option value="900">9:00 AM</option>
                  <option value="930">9:30 AM</option>
                  <option value="1000">10:00 AM</option>
                  <option value="1030">10:30 AM</option>
                  <option value="1100">11:00 AM</option>
                  <option value="1130">11:30 AM</option>
                  <option value="1200">12:00 PM</option>
                  <option value="1230">12:30 PM</option>
                  <option value="1300">1:00 PM</option>
                  <option value="1330">1:30 PM</option>
                  <option value="1400">2:00 PM</option>
                  <option value="1430">2:30 PM</option>
                  <option value="1500">3:00 PM</option>
                  <option value="1530">3:30 PM</option>
                  <option value="1600">4:00 PM</option>
                  <option value="1630">4:30 PM</option>
                  <option value="1700">5:00 PM</option>
                  <option value="1730">5:30 PM</option>
                  <option value="1800">6:00 PM</option>
                  <option value="1830">6:30 PM</option>
                  <option value="1900">7:00 PM</option>
                  <option value="1930">7:30 PM</option>
                  <option value="2000">8:00 PM</option>
                  <option value="2030">8:30 PM</option>


                </select>

              </div>


            </div>



          </div>


        </div>
        <!-- /.box-body -->

        <div class="box-footer">
        <button type="button" class="btn btn-success check_schedule pull-right">Submit</button>
        </div>
      </div>
      <!-- /.box -->





    </div>
    <!-- /.box-body --> 


    <div class="col-md-12">



      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#fa-icons" data-toggle="tab" aria-expanded="true">Class</a></li>
          <li class=""><a href="#glyphicons" data-toggle="tab" aria-expanded="false">Room</a></li>
          <li class=""><a href="#faculty" data-toggle="tab" aria-expanded="false">Faculty</a></li>
        </ul>
        <div class="tab-content">
          <!-- Font Awesome Icons -->
          <div class="tab-pane active" id="fa-icons">
            <section id="new">
              <h4 class="page-header">Title here</h4>

              <div id='calendar'></div>
            </section>   
          </div>
          <!-- /#fa-icons -->

          <!-- glyphicons-->
          <div class="tab-pane" id="glyphicons">

            <ul class="bs-glyphicons">
              <li>
                <span class="glyphicon glyphicon-asterisk"></span>
                <span class="glyphicon-class">glyphicon glyphicon-asterisk</span>
              </li>
              <li>
                <span class="glyphicon glyphicon-plus"></span>
                <span class="glyphicon-class">glyphicon glyphicon-plus</span>
              </li>

              <li>
                <span class="glyphicon glyphicon-menu-right"></span>
                <span class="glyphicon-class">glyphicon glyphicon-menu-right</span>
              </li>
              <li>
                <span class="glyphicon glyphicon-menu-down"></span>
                <span class="glyphicon-class">glyphicon glyphicon-menu-down</span>
              </li>
              <li>
                <span class="glyphicon glyphicon-menu-up"></span>
                <span class="glyphicon-class">glyphicon glyphicon-menu-up</span>
              </li>
            </ul>
          </div>
          <!-- /#ion-icons -->




          <!-- faculty-->
          <div class="tab-pane" id="faculty">

            <ul class="bs-glyphicons">
              <li>
                <span class="glyphicon glyphicon-asterisk"></span>
                <span class="glyphicon-class">glyphicon glyphicon-asterisk</span>
              </li>
              <li>
                <span class="glyphicon glyphicon-plus"></span>
                <span class="glyphicon-class">glyphicon glyphicon-plus</span>
              </li>

              <li>
                <span class="glyphicon glyphicon-menu-right"></span>
                <span class="glyphicon-class">glyphicon glyphicon-menu-right</span>
              </li>
              <li>
                <span class="glyphicon glyphicon-menu-down"></span>
                <span class="glyphicon-class">glyphicon glyphicon-menu-down</span>
              </li>
              <li>
                <span class="glyphicon glyphicon-menu-up"></span>
                <span class="glyphicon-class">glyphicon glyphicon-menu-up</span>
              </li>
            </ul>
          </div>
          <!-- /#faculty -->



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




<link href='<?php echo base_url(); ?>css/fullcalendar.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>css/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='<?php echo base_url(); ?>js/moment.min.js'></script>

<script src='<?php echo base_url(); ?>js/fullcalendar.min.js'></script> 
<script src="<?php echo base_url(); ?>plugins/iCheck/icheck.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>plugins/iCheck/all.css">