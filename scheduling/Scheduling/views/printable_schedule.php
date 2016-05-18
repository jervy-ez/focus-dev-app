<?php $days_arr_data = ["","Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];  ?>

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
    </tr>
  </thead>
  <tbody>


    <?php foreach ($schedule_data->result() as $course): ?>
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
     <?php endforeach; ?>


   </tbody>

 </table>