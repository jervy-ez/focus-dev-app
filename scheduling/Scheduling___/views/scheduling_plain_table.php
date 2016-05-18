<?php $base_url = base_url();  ?>


  <link rel="stylesheet" href="<?php echo $base_url; ?>css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $base_url; ?>css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo $base_url; ?>css/skins/_all-skins.min.css">



  <!-- jQuery 2.2.0 -->
  <script src="<?php echo $base_url; ?>plugins/jQuery/jQuery-2.2.0.min.js"></script>
  
  <!-- Bootstrap 3.3.5 -->
  <script src="<?php echo $base_url; ?>js/bootstrap.min.js"></script>



<?php $this->load->module('Scheduling'); ?>
<?php $this->load->module('Functions'); ?>

<?php $page_type = strtolower($page_type); ?>


<?php //var_dump(expression) ?>
<script>

  $(document).ready(function() {


    $('#calendar').fullCalendar({
      defaultDate: '2015-11-01',
      defaultView: 'agendaWeek',
      editable: false,
      slotDuration: '00:15:00',
      minTime: "06:00:00",
      maxTime: "22:00:00",
      contentHeight: 713,
      eventLimit: true, // allow "more" link when too many events
      events: [

<?php foreach ($sched as $fct): ?>

  <?php $class_day = $fct->day; ?>
  <?php $s_time = $fct->start_time; ?>
  <?php $f_time = $fct->end_time; ?>

  <?php
  
	if(strlen($s_time) == 3){
		$s_time = '0'.$s_time;		
	}
	if(strlen($f_time) == 3){
		$f_time = '0'.$f_time;		
	}
		
	$s_time = substr($s_time, 0,-2).':'.substr($s_time, 2,2 ).':00';
	$f_time = substr($f_time, 0,-2).':'.substr($f_time, 2,2 ).':00'; 
  
  
    $room_data = $this->scheduling->_fetch_room_details($fct->room_id);




    $block_data = $this->scheduling->_fetch_block_details($fct->block_id);

    if($room_data){
      $data_room_name = 'Bldg:'.$room_data[0]->building_code.' Rm:'.$room_data[0]->room_code;
    }else{
      $data_room_name = '';
    }



    if($fct->faculty_in_charge_id >= 1){
      $employee_data = $this->scheduling->_fetch_employee_details($fct->faculty_in_charge_id);
      $data_faculty_name = $employee_data[0]->employee_lname.' '.$employee_data[0]->employee_fname;
    }else{
      $data_faculty_name = '';
    }

    $event = $this->scheduling->_fetch_event_details($fct->class_event_id);

    $days_arr_data = ["","Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];


    



    $ends = array('th','st','nd','rd','th','th','th','th','th','th','');


    if($block_data){
      $index_end = $block_data[0]->year_level;
      $block_year = $block_data[0]->year_level;
      $block_code = $block_data[0]->block;
    }else{
      $index_end = '10';
      $block_year = 'Class/Open Block';
      $block_code = '';
    }
    
  ?>


  <?php //$s_time = ( strlen ( $s_time ) == 4 ? $s_time  : '0'.$s_time );  ?>
  <?php //$f_time = ( strlen ( $f_time ) == 4 ? $f_time  : '0'.$f_time );  ?>

  {
    <?php echo "id: '$fct->class_sched_id',"; ?>
    <?php echo "title: '$data_faculty_name<br />Year: $block_year Block: $block_code<br />".$days_arr_data[$fct->day]." on $fct->time<br />$data_room_name,"; ?>
    <?php 
      if($page_type == 'faculty'){
        echo "$fct->schedule_name: ";
      }
    ?>
    <?php echo "$event->class_event - $fct->course_code',"; ?>
    <?php echo "start: '2015-11-0".$class_day."T".$s_time."',"; ?>
    <?php echo "end: '2015-11-0".$class_day."T".$f_time."',"; ?>
    <?php  //var_dump($fct); echo '<hr /><br />'; ?>
  },

 
<?php endforeach; ?>


],
eventRender: function (event, element) {

  //element.find('.fc-time').before($("<div class=\"fc-event-icons\"></div>").html("<p class=\"pull-right\">"+event.id+" <i class=\"fa fa-times\"></i></p>"));
  //element.find('.fc-time').prepend('<i class="fa fa-clock-o"></i> ');

  element.find('.fc-time').remove();
  element.find('.fc-title').empty();
  var title = event.title;

  var title_arr = title.split(",");
  element.find('.fc-title').append(title_arr[1]+'<br />'+title_arr[0]);

}

    });


  });


  var days_arr = ["","Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
  var date_counter = 0;

setTimeout(function(){  



 
  $('#calendar .fc-widget-header table th').each(function(index){
    $(this).show();
    var date_text = $(this).text();
    if(date_text!=''){
      $(this).text(days_arr[date_counter]);
    }
    date_counter++;
  });
 




},100);






</script>
<style>
/*
.fc-agendaWeek-view tr {
    height: 30px;
}

.fc-agendaDay-view tr {
    height: 30px;
}
*/

  #calendar {
    max-width: 100%;
    margin: 0 auto;
  }

  #calendar .fc-toolbar,.fc-day-grid{
    display: none;
  }

  #calendar .fc-widget-header table th{
   /* display: none;*/
  }

  #calendar .fc-title{
    font-size: 10px;
  }

</style>


<?php if(@$data_var): ?>
  <?php 

  $data_title = explode('.', $data_var);



  var_dump($data_title);



  ?>
<?php endif; ?>


 <div id="calendar" class=""  ></div> 
   


<link href='<?php echo base_url(); ?>css/fullcalendar.css' rel='stylesheet' />
<link href='<?php echo base_url(); ?>css/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='<?php echo base_url(); ?>js/moment.min.js'></script>

<script src='<?php echo base_url(); ?>js/fullcalendar.min.js'></script> 
 


<!-- SlimScroll -->
<script src="<?php echo $base_url; ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo $base_url; ?>plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo $base_url; ?>js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo $base_url; ?>js/demo.js"></script>

<script src="<?php echo $base_url; ?>js/main.js"></script>
 
