<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jervy Zaballa */

class Scheduling extends MX_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->module('Functions');
		$this->load->model('scheduling_m');

		if(!$this->session->userdata('id')){
			//echo '<script> window.location = "https://my.bicol-u.edu.ph/alpha/default/user/logout"; </script>';
		}
	}	

	function index(){
		$year = date("Y");
		$data['main_content'] = 'scheduling_home';


		$data['courses_data'] = $this->_fetch_courses($year);
		$data['campus_data'] = $this->_fetch_campus();


		$data['room_data'] = $this->_fetch_room();
		$data['employee_data'] = $this->_fetch_employee();
		$data['building_data'] = $this->_fetch_buildings();

		$q_all_courses = $this->scheduling_m->_get_all_courses();
		$data['all_courses'] = $q_all_courses->result();

		$this->load->view('page', $data);	
	}

	function _fetch_course_details($course_id, $campus_id, $college_id, $department_id, $program_id){
		$q_course_details = $this->scheduling_m->_get_course_details($course_id, $campus_id, $college_id, $department_id, $program_id);
		$course_details = $q_course_details->result();
		return $course_details[0];
	}

	function _fetch_class_block($program_id){
		$block_details_q = $this->scheduling_m->_get_class_block($program_id);
		return $block_details_q->result();
	}

	function _fetch_block_details($id){
		if($id >= 1 && $id != 'NULL' ){
			$q_block = $this->scheduling_m->_get_block_details($id);
			return $q_block->result();
		}else{
			return false;
		}
	}

	function _fetch_room($bldg_id=''){
		$q_room = $this->scheduling_m->_get_room($bldg_id);
		return $q_room->result();
	}

	function _fetch_room_details($room_id){
		if($room_id >= 1 && $room_id != 'NULL' ){
			$q_room = $this->scheduling_m->_get_room_details($room_id);
			return $q_room->result();
		}else{
			return false;
		}
	}

	function _fetch_employee(){
		$q_scheduling = $this->scheduling_m->_get_employee();
		return $q_scheduling->result();
	}

	function _fetch_employee_details($id){
		$q_employee = $this->scheduling_m->_get_employee_details($id);
		return $q_employee->result();
	}

	function _fetch_buildings(){
		$q_buildings = $this->scheduling_m->_get_buildings();
		return $q_buildings->result();
	}

	function _fetch_event(){
		$q_event = $this->scheduling_m->_get_class_event();
		return $q_event->result();
	}

	function _fetch_event_details($id){
		$q_event_details = $this->scheduling_m->_get_class_event_by($id);
		$event_details = $q_event_details->result();
		return $event_details[0];
	}

	function _fetch_schedules(){
		$q_scheds = $this->scheduling_m->_get_schedules();
		return $q_scheds->result();
	}

	function _fetch_academic_year($set_year=''){
		if($set_year!=''){
			$year = $set_year;
		}else{
			$year=date("Y");
		}

		$n_year = $year + 1;

		$term = $year.'-'.$n_year;
		$q_academic_term = $this->scheduling_m->_get_academic_term($term);
		return $q_academic_term->result();
	}




	function _fetch_courses($set_year=''){

		if($set_year!=''){
			$year = $set_year;
		}else{
			$year=date("Y");
		}

		$n_year = $year + 1;
		$term = $year.'-'.$n_year;

		$q_scheduling = $this->scheduling_m->_get_course($term);
		return $q_scheduling->result();
	}

	function _fetch_campus(){
		$q_campus = $this->scheduling_m->_get_campus();
		return $q_campus->result();
	}

	function _fetch_faculty(){
		$q_campus = $this->scheduling_m->_get_all_faculty();
		return $q_campus->result();
	}


	function disable(){
		$id = $this->uri->segment(3);

		$this->scheduling_m->_disable_schedule($id);

		$link = base_url().'Scheduling/view_schedule';
		echo '<script> window.location = "'.$link.'"; </script>';
	}


	function faculty_calendar(){
		$get_data = $this->uri->segment(3);
		$data = explode('-',$get_data);

		list($data_id,$term_id) = $data;

		if( $data_id >= 1 && $term_id >= 1){

			$q_faculty_data = $this->scheduling_m->_get_faculty_schedule($data_id,$term_id);
			$data['sched'] = $q_faculty_data->result();
			$data['page_type'] = 'Faculty';

			$data['main_content'] = 'scheduling_plain_table';
			$this->load->view('scheduling_plain_table', $data);
		}
	}


	function room_calendar(){
		$get_data = $this->uri->segment(3);
		$data = explode('-',$get_data);

		list($data_id,$term_id) = $data;

// this is work in progress print out section
		$pos = strpos($get_data,'|');
		if($pos === false) {
			// string needle NOT found in haystack
			$room_id = $get_data;
		}
		else {
 			// string needle found in haystack
			$room_arr = explode('-', $get_data);
			list($room_id, $data_var) = $room_arr;
			$data['data_var'] = $data_var;
		}
// this is work in progress print out section

		if( $data_id >= 1 && $term_id >= 1){

			$q_room_data = $this->scheduling_m->_get_room_schedule($data_id,$term_id);
			$data['sched'] = $q_room_data->result();
			$data['page_type'] = 'Room';

			$data['main_content'] = 'scheduling_plain_table';
			$this->load->view('scheduling_plain_table', $data);
		}
	}


	function class_calendar(){

		$get_data = $this->uri->segment(3);
		$data = explode('-',$get_data);

		list($data_id,$term_id) = $data;

		if( $data_id >= 1 && $term_id >= 1){

			$q_block_data = $this->scheduling_m->_get_block_schedule($data_id,$term_id);
			$data['sched'] = $q_block_data->result();
			$data['page_type'] = 'Class';

			$data['main_content'] = 'scheduling_plain_table';
			$this->load->view('scheduling_plain_table', $data);
		}
	}



	function count_schedule(){

		$faculty_id = $_POST['ajax_post'];

//		$post_data = explode('-',$_POST['ajax_post']);

//		list($faculty_id,$term_id,$block_id,$course_id) = $post_data;


/*
$faculty_id = 106;
$term_id = 28;
$block_id = 1;
$course_id = 1217;

*/


		$counter = 1;
		$faculty_q = $this->scheduling_m->_get_schedule_faculty($faculty_id);

		foreach ($faculty_q->result() as $result) {
			$counter++;
		}
		echo $counter;
	}

	function catch_campus(){
		$campus_id = $_POST['ajax_post'];
		$q_college = $this->scheduling_m->_get_college($campus_id);
		
		echo '<option  selected="">Select College</option>';
		foreach ($q_college->result() as $result) {
			echo '<option value="'.$result->id.'">'.$result->college_code.'</option>';
		}
	}

	function catch_campus_bldg(){
		$campus_id = $_POST['ajax_post'];
		$q_college = $this->scheduling_m->_get_bldg($campus_id);
		
		echo '<option selected="">Select Building*</option><option value="0">*Open Building</option>';
		foreach ($q_college->result() as $result) {
			echo '<option value="'.$result->id.'">'.$result->building_code.' - '.$result->building_name.'</option>';
		}
	}


	function catch_faculty_campus(){
		$college_id = $_POST['ajax_post'];

	//	$college_id = 1;
		$q_college = $this->scheduling_m->_get_faculty_college($college_id);
		
		//echo '<option disabled="" selected="">Select Faculty*</option>';
		foreach ($q_college->result() as $result) {
			echo '<option value="'.$result->id.'">'.$result->employee_lname.', '.$result->employee_fname.'</option>';
		}		
	}

	function catch_college(){
		$college_id = $_POST['ajax_post'];
		$q_department = $this->scheduling_m->_get_department($college_id);
		
		echo '<option selected="">Dept Code</option>';
		foreach ($q_department->result() as $result) {
			echo '<option value="'.$result->college_dep_id.'">'.$result->department_code.'</option>';
		}
	}

	function catch_department(){
		$department_id = $_POST['ajax_post'];
		$q_program = $this->scheduling_m->_get_program($department_id);
		
		echo '<option  selected="">Select Program</option>';
		foreach ($q_program->result() as $result) {
			echo '<option value="'.$result->program_id.'">'.$result->program_name.' '.$result->school_year.' '.$result->prg_mjr_code.'</option>';
		}

	}


	function update_sched(){
 /*

$class_event = 1;
 $faculty_id = 444;
$units = 6;
$day = 1;
$start_time = 1700;
 $end_time = 1900;
$sched_id = 500;

*/


		$ajax_post = $_POST['ajax_post'];
		$arr_post = explode('-', $ajax_post );
		list($class_event, $faculty_id, $units, $day, $start_time, $end_time, $sched_id, $room_id, $coordinator_id) = $arr_post;



		if($room_id == 0){
			$room_id = 'NULL';
		}

		if($faculty_id == 0){
			$faculty_id = 'NULL';
		}

		if($coordinator_id == 0){
			$coordinator_id = 'NULL';
		}


		$time_a = $this->functions->army_to_standard_time($start_time);
		$time_b = $this->functions->army_to_standard_time($end_time);

		$time_set = substr($time_a,0,-3).'-'.substr($time_b,0,-3);


		$this->scheduling_m->_update_schedule($class_event,$units, $day, $time_set , $start_time  ,$end_time ,$sched_id , $faculty_id, $room_id, $coordinator_id);

		echo '2';
 
	}

	function override_sched(){
		$ajax_post = $_POST['ajax_post'];
		$arr_post = explode('|', $ajax_post );
		list($days, $room_id, $start_time, $end_time, $faculty_id, $course_id, $block_id, $term_id, $class_event, $class_faculty_id, $schedule_name,$no_of_units,$remarks_over_ride) = $arr_post;

		if($faculty_id == 0){
			$faculty_id = 'NULL';
		}

		if($class_faculty_id == 0){
			$class_faculty_id = 'NULL';
		}

		if($room_id == 0){
			$room_id = 'NULL';
		}

		$user_id = $this->session->userdata('id'); //this is a dummy test user
	//	$user_id = '1314';


		$my_time_log = $this->functions->get_time_record();

		$arr_days = explode(',', $days);

		$q_course_schedule = $this->scheduling_m->_get_course_schedule($course_id,$faculty_id,$term_id,$block_id);
		$course_schedule = $q_course_schedule->result();

		if($course_schedule){
			foreach ($course_schedule as $course_data) {
				$course_schedule_id = $course_data->id;
			}	
		}else{
			$course_schedule_id = $this->scheduling_m->_insert_course_schedule($course_id, $class_faculty_id, $term_id, $my_time_log, $user_id, $block_id);
		}


		foreach ($arr_days as $key => $day_val) {
			$time_a = $this->functions->army_to_standard_time($start_time);
			$time_b = $this->functions->army_to_standard_time($end_time);

			$daySet = $this->functions->_get_day_to_digit($day_val);
			$time_set = substr($time_a,0,-3).'-'.substr($time_b,0,-3);

			if($day_val  == 'TBA'){
				$this->scheduling_m->_insert_class_schedule($course_schedule_id, $class_event, $schedule_name, $room_id, $daySet, $time_set, $no_of_units, $class_faculty_id, $my_time_log, $user_id, $start_time, $end_time);
				break;
			}

			$this->scheduling_m->_insert_class_schedule($course_schedule_id, $class_event, $schedule_name, $room_id, $daySet, $time_set, $no_of_units, $class_faculty_id, $my_time_log, $user_id, $start_time, $end_time, $remarks_over_ride);
		}

		echo '1';
	}

	function check_sched(){
	$ajax_post = $_POST['ajax_post'];
	$arr_post = explode('|', $ajax_post );

		$my_time_log = $this->functions->get_time_record();

		$user_id = $this->session->userdata('id'); //this is a dummy test user
	//	$user_id = '1314';

		$has_conflict = 0;
		$has_final_conflict = 0;


		$err_msg_rm = array();
		$err_msg_fclty = array();
		$err_msg_blk = array();

		$err_msg_final = array();
		$err_msg = array();

		//	var_dump($arr_post);

	list($days, $room_id, $start_time, $end_time, $faculty_id, $course_id, $block_id, $term_id, $class_event, $class_faculty_id, $schedule_name,$no_of_units) = $arr_post;

/*
$days = 'Mon';
$room_id = '550';
$start_time = '1300';
$end_time = '1600';
$faculty_id = '67';
$course_id = '576';
$block_id = '186';
$term_id = '28';
$class_event = '2';
$class_faculty_id = '67';
$schedule_name = 'sch 10';
$no_of_units = '1';
*/





		if($faculty_id == 0){
			$faculty_id = 'NULL';
		}


		if($class_faculty_id == 0){
			$class_faculty_id = 'NULL';
		}

		if($room_id == 0){
			$room_id = 'NULL';
		}


		$arr_days = explode(',', $days);

		if($block_id == 0){
			$block_id = 'NULL';
		}


		$q_course_schedule = $this->scheduling_m->_get_course_schedule($course_id,$faculty_id,$term_id,$block_id);
		$course_schedule = $q_course_schedule->result();

		if($course_schedule){
			foreach ($course_schedule as $course_data) {
				$course_schedule_id = $course_data->id;
			}	
		}else{
			$course_schedule_id = $this->scheduling_m->_insert_course_schedule($course_id, $class_faculty_id, $term_id, $my_time_log, $user_id, $block_id);
		}


		// loops days selected
		foreach ($arr_days as $key => $day_val) {

			$time_a = $this->functions->army_to_standard_time($start_time);
			$time_b = $this->functions->army_to_standard_time($end_time);

			$daySet = $this->functions->_get_day_to_digit($day_val);
			$time_set = substr($time_a,0,-3).'-'.substr($time_b,0,-3);

			if($day_val  == 'TBA'){
				$this->scheduling_m->_insert_class_schedule($course_schedule_id, $class_event, $schedule_name, $room_id, $daySet, $time_set, $no_of_units, $class_faculty_id, $my_time_log, $user_id, $start_time, $end_time);
				break;
			}


			//echo "$block_id,$term_id,$daySet,'2'";

		//var_dump($class_schedule_blk);

		//echo $has_conflict_token.'-';

if($room_id >= 1){
/*
			$q_class_schedule = $this->scheduling_m->_get_class_schedule($room_id,$term_id,$daySet); //checks schedule by room
			$class_schedule = $q_class_schedule->result();
			$has_conflict_token = $this->_check_time_conflict($class_schedule,$room_id,$faculty_id,$block_id,$start_time,$end_time,$daySet,'Room');

			if($has_conflict_token > 0){
				$has_final_conflict = 1;
			}
*/			
}


  


//echo $has_conflict_token.'-';

if($class_faculty_id > 0){
/*	
			$q_class_schedule_fclty = $this->scheduling_m->_get_class_schedule($class_faculty_id,$term_id,$daySet,'1'); //checks schedule by faculty
			$class_schedule_faculty = $q_class_schedule_fclty->result();
			$has_conflict_token = $this->_check_time_conflict($class_schedule_faculty,$room_id,$faculty_id,$block_id,$start_time,$end_time,$daySet,'Faculty');

			if($has_conflict_token > 0){
				$has_final_conflict = 1;
			}
 */
}

//echo $has_conflict_token.'-';


if($block_id >= 1){
/*	
			$q_class_schedule_blk = $this->scheduling_m->_get_class_schedule($block_id,$term_id,$daySet,'2'); //checks schedule by block
			$class_schedule_blk = $q_class_schedule_blk->result();
			$has_conflict_token = $this->_check_time_conflict($class_schedule_blk,$room_id,$faculty_id,$block_id,$start_time,$end_time,$daySet,'Block');

			if($has_conflict_token > 0){
				$has_final_conflict = 1;
			}
*/			
}


			if($has_final_conflict != 1){
				$this->scheduling_m->_insert_class_schedule($course_schedule_id, $class_event, $schedule_name, $room_id, $daySet, $time_set, $no_of_units, $class_faculty_id, $my_time_log, $user_id, $start_time, $end_time);
			}else{
				
			}


		}


		if($has_final_conflict != 1){
			echo '1';
		}



		$err_msg_final = array_merge($err_msg_blk,$err_msg_fclty,$err_msg_rm);

		/*
		$err_msg_final = array_unique($err_msg_final);

		$display_msg = array_diff( $err_msg_final, array( '' ) );
*/
		
	}

	function _check_time_conflict($query_result,$room_id,$faculty_id,$block_id,$start_time,$end_time,$days,$type){
		$has_conflict = 0;
		$error_message = array();
		$message = '';
		$errors = array();

		$test_counter = 0;

		//var_dump($query_result,$room_id,$faculty_id,$block_id,$start_time,$end_time,$days,$type); echo" <br /><br />";


		if($query_result){

			foreach ($query_result as $class_data){

			//	var_dump($class_data);
			//	echo "<br /><hr /><br />";


				//echo "<h1>".$test_counter++."</h1><br />";

				$stored_day = ucfirst(substr($class_data->day, 0, 3));
				//$check_day = strtoupper($days);

				$room_data = $this->_fetch_room_details($class_data->room_id);
				//$block_data = $this->_fetch_block_details($class_data->block_id);

				//var_dump($class_data);

				$data_room_name = $room_data[0]->room_name;


				if($class_data->faculty_in_charge_id >= 1){
					$employee_data = $this->_fetch_employee_details($class_data->faculty_in_charge_id);
					$data_faculty_name = $employee_data[0]->employee_lname.' '.$employee_data[0]->employee_fname;
				}else{
					$data_faculty_name = 'No faculty yet';
				}

				$ends = array('th','st','nd','rd','th','th','th','th','th','th');
				$day_arr = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');

 

				// checkes first if time conflict

				if( intval($class_data->start_time) <= intval($start_time) && intval($start_time) < intval($class_data->end_time )){

					//checks stat time if conflict, if true has error
					// checks time start conflict
-
					$message = "<p><strong>$type Conflict</strong> : ".$day_arr[$stored_day]." on $class_data->time at Room:$data_room_name by $data_faculty_name, Class ".$class_data->year_level." ".$class_data->block."</p>";
					array_push($error_message, $message);
//					$has_conflict = 1;

				}elseif( intval($class_data->start_time) <= intval($end_time) && intval($end_time) <= intval($class_data->end_time )){

					// checks time ending conflict

					$message = "<p><strong>$type Conflict</strong> : ".$day_arr[$stored_day]." on $class_data->time at Room:$data_room_name by $data_faculty_name, Class ".$class_data->year_level." ".$class_data->block."</p>";
					array_push($error_message, $message);
//					$has_conflict = 1;

				}elseif( intval($class_data->start_time) >= intval($start_time) && intval($end_time) >= intval($class_data->end_time )){

					//checks start and end time overlaps to saved schedule

					$message = "<p><strong>$type Conflict</strong> : ".$day_arr[$stored_day]." on $class_data->time at Room:$data_room_name by $data_faculty_name, Class ".$class_data->year_level." ".$class_data->block."</p>";
					array_push($error_message, $message);
//					$has_conflict = 1;

				}else{

					// no time conflicts here

					//echo "5"; // returns value 1 means inserts new record
					//	$this->scheduling_m->_insert_class_schedule($course_schedule_id, $class_event, $schedule_name, $room_id, $days, $time_set, $no_of_units, $faculty_id, $my_time_log, $user_id, $start_time, $end_time);
				}

				// checkes first if time conflict 




			}

		}else{
			// no conflict goes here
			//if($query_result) else line

			//echo "4"; // returns value 1 means inserts new record
			//$this->scheduling_m->_insert_class_schedule($course_schedule_id, $class_event, $schedule_name, $room_id, $days, $time_set, $no_of_units, $faculty_id, $my_time_log, $user_id, $start_time, $end_time);
		}

		$err_msg = $error_message;

/*

				foreach ( $err_msg as $key => $value) {
					echo "<p>$value</p>";
				}
*/

// echo "$has_conflict*";

		//var_dump($err_msg);

	
		return $has_conflict;
	}

	function test_mode(){
		//$course_schedule_id = $this->scheduling_m->_insert_course_schedule('151','1','66','1','' ,'2');

		echo substr("abcdef", 0, -1);
		echo "<br />";
		echo substr("abcdef", 0, -1);
		echo "<br />";
		echo substr("abcdef", 0, -1);
		echo "<br />";
		echo substr("abcdef", 0, -1);
		echo "<br />";
		echo substr("ab55555", 0, 3);
		echo "<br />";
	}


	function get_room(){
		$bldg_id = $_POST['ajax_post'];
		$q_room = $this->_fetch_room($bldg_id);

		if($q_room){
			echo "<option selected value=\"\">Select Room*</option>";
			foreach ($q_room as $room) {
				echo "<option value='".$room->id."'>".$room->room_code."</option>";
			}

		}else{
			echo "<option value=\"0\" selected=\"selected\">*No Room</option>";
		}
	}

	function view_schedule(){
		$year = date("Y");
		$data['main_content'] = 'scheduling_view_sched';
		$data['campus_data'] = $this->_fetch_campus();
		$data['academic_year'] = $this->_fetch_academic_year($year);
		$data['schedule_data'] = $this->_fetch_schedules();		

		$data['employee_data'] = $this->_fetch_employee();
		$data['building_data'] = $this->_fetch_buildings();
		$data['class_event_data'] = $this->_fetch_event();

		$this->load->view('page', $data);
	}

	function set_schedule(){
		$course_data = $this->uri->segment(3);
		$course_arr = explode('-', $course_data);
		$year = date("Y");

		//166-3-3-3-4
		list($course_id, $campus_id, $college_id, $department_id, $department_program_id, $is_special) = $course_arr;

		if($is_special==0){
			$data['course_details'] = $this->_fetch_course_details($course_id, $campus_id, $college_id, $department_id, $department_program_id);
			$department_program = $data['course_details']->department_p_id;
			$year_level = $data['course_details']->year_level;
			$data['class_block'] = $this->_fetch_class_block($department_program);
		}else{

			$selected_course_q = $this->scheduling_m->_get_one_course($course_id);
			$course = $selected_course_q->result();
			$data['course_details'] = $course[0];
		}

		$data['is_special'] = $is_special;

	//	var_dump($department_program,$year_level);
		$data['courses_data'] = $this->_fetch_courses($year);



		$data['course_id'] = $course_id;
		$data['room_data'] = $this->_fetch_room();
		$data['main_content'] = 'scheduling_main';	
		$data['employee_data'] = $this->_fetch_employee();
		$data['academic_year'] = $this->_fetch_academic_year($year);
		$data['term_data'] = $this->_fetch_academic_year($year);
		$data['building_data'] = $this->_fetch_buildings();
		$data['class_event_data'] = $this->_fetch_event();


		$data['campus_data'] = $this->_fetch_campus();


		$this->load->view('page', $data);
	}
}