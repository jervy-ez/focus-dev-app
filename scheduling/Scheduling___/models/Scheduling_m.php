<?php

class Scheduling_m extends CI_Model{
	
	function test(){
		$query = $this->db->query("SELECT * FROM room");
		return $query;	
	}

	function _get_course($year_term = ''){
		$query = $this->db->query("SELECT


			course.id AS class_subject_id, program.id AS program_id, campus.id AS campus_id, college.id AS college_id, department.id AS department_id   , program_major.program_major_shortname AS prg_mjr_code,



			course.course_code,
course.course_code_old,
course.course_title,
course.credit_units,

campus.campus_code,
college.college_code,
department.department_code,
program.program_name,
ref_academic_term.school_year,
ref_academic_term.semester


			FROM curriculum 
			LEFT JOIN curriculum_course ON curriculum_course.curriculum_id = curriculum.id
			LEFT JOIN ref_academic_term ON ref_academic_term.id = curriculum.academic_term_id
			LEFT JOIN department_program ON department_program.curriculum_id = curriculum.id
			LEFT JOIN college_department ON college_department.id = department_program.college_department_id
			LEFT JOIN program ON program.id = department_program.program_id
			LEFT JOIN college ON college.id = college_department.college_id
			LEFT JOIN campus ON campus.id = college.campus_id
			LEFT JOIN department ON department.id = college_department.department_id
			LEFT JOIN course ON course.id = curriculum_course.course_id
			LEFT JOIN ref_year_term ON ref_year_term.id = curriculum_course.year_term_id

			LEFT JOIN program_major ON  program_major.program_id = program.id

			/*WHERE ref_academic_term.school_year = '$year_term'*/
			/*
			WHERE curriculum_course.is_active = 'T'
			AND ref_academic_term.is_active = 'T' 
			AND department_program.is_active = 'T' 
			AND college_department.is_active = 'T' 
			AND program.is_active = 'T' 
			AND college.is_active = 'T' 
			AND campus.is_active = 'T' 
			AND department.is_active = 'T' 
			AND course.is_active = 'T'
			AND ref_year_term.is_active = 'T'
*/
");
		return $query;
	}

	function _get_room($bldg_id=''){
		if($bldg_id!=''){
			$query = $this->db->query("SELECT * FROM room WHERE room.building_id = '$bldg_id' /*AND room.is_active = 'T'*/ ORDER BY room_name ASC ");
		}else{
			$query = $this->db->query("SELECT * FROM room /*WHERE room.is_active = 'T'*/ ");
		}
		return $query;		
	}

	function _get_room_details($id){

		$query = $this->db->query("SELECT * FROM room LEFT JOIN building ON building.id = room.building_id WHERE room.id = '$id' ");
		return $query;

	}	

	function _get_employee(){
		$query = $this->db->query("SELECT * FROM employee /*WHERE employee.is_active = 'T'*/ ORDER BY employee_lname ASC");
		return $query;
	}

	function _get_employee_details($id){
		$query = $this->db->query("SELECT * FROM employee WHERE employee.id = '$id' ");
		return $query;		
	}

	function _get_academic_term($term){
		$query = $this->db->query("SELECT * FROM ref_academic_term WHERE ref_academic_term.school_year = '$term' ORDER BY ref_academic_term.id ASC");
		return $query;
	}

	function _get_buildings(){
		$query = $this->db->query("SELECT * FROM building  /*WHERE is_active = 'T' AND active = 'T' */");
		return $query;
	}

	function _get_class_event(){
		$query = $this->db->query("SELECT * FROM class_event /*WHERE is_active = 'T'*/");
		return $query;
	}

	function _get_class_event_by($id){
		$query = $this->db->query("SELECT * FROM class_event WHERE id = '$id' ");
		return $query;
	}

	function _get_sched_by_room_faculty($room_id,$faculty_id){
		$query = $this->db->query("SELECT * FROM class_schedule  WHERE class_schedule.room_id = '$room_id' AND class_schedule.faculty_in_charge_id = '$faculty_id' ");
		return $query;
	}

	function _get_block_details($id){
		$query = $this->db->query("SELECT * FROM block WHERE block.id = '$id' ");
		return $query;
	}

	function _get_course_schedule($course_id, $faculty_id, $term_id, $block_id){
		$query = $this->db->query("SELECT * FROM course_schedule  
			WHERE course_schedule.course_id = '$course_id' 
			AND course_schedule.faculty_in_charge_id = $faculty_id 
		 	AND course_schedule.block_id = $block_id
			AND course_schedule.academic_term_id = '$term_id'  AND course_schedule.active = 'T' AND course_schedule.is_active = 'T' ");
		return $query;
	}

	function _get_all_faculty(){
		$query = $this->db->query("SELECT * FROM employee ORDER BY employee_lname ASC ");
		return $query;
	}

	function _insert_course_schedule($course_id,  $faculty_id, $term_id, $date_time_log , $user_id, $block_id){
		$this->db->query("INSERT INTO course_schedule (course_id, faculty_in_charge_id, academic_term_id, active, is_active, created_on, created_by, modified_on, modified_by, block_id)
		 VALUES ('$course_id',  $faculty_id, '$term_id', 'T', 'T', '$date_time_log', '$user_id', '$date_time_log', '$user_id', $block_id  )");
		
		$last_insert_id = $this->db->insert_id();
		return $last_insert_id;
	}

	function _get_class_schedule($id,$term_id,$days,$type=''){

		if($type != '' && $type == '1'){ // 1 = seach by faculty
			$query = $this->db->query("SELECT * FROM class_schedule 
				LEFT JOIN course_schedule ON course_schedule.id = class_schedule.course_schedule_id
				LEFT JOIN block ON block.id = course_schedule.block_id
				WHERE class_schedule.faculty_in_charge_id = '$id' AND class_schedule.day = '$days' AND course_schedule.academic_term_id = '$term_id' AND course_schedule.active = 'T' AND course_schedule.is_active = 'T' AND class_schedule.active = 'T' AND class_schedule.is_active = 'T' ORDER BY class_schedule.start_time ASC");
		}elseif($type != '' && $type == '2'){ // 2 = seach by class block
			$query = $this->db->query("SELECT *, block.id AS block_id FROM class_schedule 
				LEFT JOIN course_schedule ON course_schedule.id = class_schedule.course_schedule_id
				LEFT JOIN block ON block.id = course_schedule.block_id
				WHERE block.id = '$id' AND class_schedule.day = '$days' AND course_schedule.academic_term_id = '$term_id' AND course_schedule.active = 'T' AND course_schedule.is_active = 'T' AND class_schedule.active = 'T' AND class_schedule.is_active = 'T' ORDER BY class_schedule.start_time ASC");
		}else{ // default seach by room id
			$query = $this->db->query("SELECT * FROM class_schedule 
				LEFT JOIN course_schedule ON course_schedule.id = class_schedule.course_schedule_id
				LEFT JOIN block ON block.id = course_schedule.block_id
				WHERE class_schedule.room_id = '$id' AND class_schedule.day = '$days' AND course_schedule.academic_term_id = '$term_id' AND course_schedule.active = 'T' AND course_schedule.is_active = 'T' AND class_schedule.active = 'T' AND class_schedule.is_active = 'T' ORDER BY class_schedule.start_time ASC");
		}
		return $query;
	}

	function _insert_class_schedule($course_schedule_id, $class_event_id, $schedule_name, $room_id, $days, $time, $no_of_units, $faculty_in_charge_id, $date_time_log, $user_id, $start_time, $end_time, $remakrs=''){
		$this->db->query("INSERT INTO class_schedule (course_schedule_id, class_event_id, schedule_name, room_id, day, time, no_of_units, faculty_in_charge_id, active, is_active, created_on, created_by, modified_on, modified_by, start_time, end_time, remarks)
		 VALUES ('$course_schedule_id', '$class_event_id', '$schedule_name', $room_id, '$days', '$time', '$no_of_units', $faculty_in_charge_id, 'T', 'T', '$date_time_log', '$user_id', '$date_time_log', '$user_id', '$start_time' , '$end_time', '$remakrs'  )");
		
		$last_insert_id = $this->db->insert_id();
		return $last_insert_id;
	}
/*
	function _insert_class_block($course_schedule_id,$block_id,$time,$user_id){
		$this->db->query("INSERT INTO course_schedule_block (course_schedule_id, block_id , active, is_active, created_on, created_by, modified_on, modified_by)
			VALUES ('$course_schedule_id',  '$block_id',  'T' ,  'T', '$time', '$user_id', '$time', '$user_id'  )");
		$last_insert_id = $this->db->insert_id();
		return $last_insert_id;
	}
*/
	function _get_schedule_faculty($faculty_id){
		$query = $this->db->query("SELECT DISTINCT schedule_name FROM class_schedule 
			WHERE faculty_in_charge_id = '$faculty_id'	AND active = 'T' AND is_active = 'T' ");
		return $query;
	}

	function _get_faculty_schedule($faculty_id,$term_id){
		$query = $this->db->query("SELECT * , class_schedule.id AS class_sched_id FROM course_schedule
			LEFT JOIN class_schedule ON class_schedule.course_schedule_id = course_schedule.id 
			LEFT JOIN course ON course.id = course_schedule.course_id
			WHERE class_schedule.faculty_in_charge_id = '$faculty_id' AND course_schedule.academic_term_id = '$term_id'
			AND class_schedule.active = 'T' AND class_schedule.is_active = 'T'  ORDER BY class_schedule.start_time ASC ");
		return $query;
	}

	function _get_room_schedule($id,$term_id){
		$query = $this->db->query("SELECT * , class_schedule.id AS class_sched_id FROM class_schedule 
			LEFT JOIN course_schedule ON course_schedule.id = class_schedule.course_schedule_id
			LEFT JOIN course ON course.id = course_schedule.course_id
			WHERE class_schedule.room_id = '$id'  AND course_schedule.academic_term_id = '$term_id'
			AND class_schedule.active = 'T' AND class_schedule.is_active = 'T' ORDER BY class_schedule.start_time ASC");
		return $query;
	}

	function _get_all_courses(){
		$query = $this->db->query("SELECT * FROM course WHERE active = 'T' AND is_active = 'T' ORDER BY course_code ASC ");
		return $query;
	}

	function _get_one_course($id){
		$query = $this->db->query("SELECT * FROM course WHERE active = 'T' AND is_active = 'T' AND id = '$id'");
		return $query;
	}

	function _get_block_schedule($id,$term_id){
		$query = $this->db->query("SELECT * , class_schedule.id AS class_sched_id FROM class_schedule 
			LEFT JOIN course_schedule ON course_schedule.id = class_schedule.course_schedule_id
			LEFT JOIN course ON course.id = course_schedule.course_id
			WHERE course_schedule.block_id = '$id'  AND course_schedule.academic_term_id = '$term_id'
			AND class_schedule.active = 'T' AND class_schedule.is_active = 'T' ORDER BY class_schedule.start_time ASC");
		return $query;
	}

	function _get_campus(){
		$query = $this->db->query("SELECT * FROM campus /*WHERE campus.is_active = 'T'*/ ORDER BY campus.campus_code ASC");
		return $query;
	}

	function _get_bldg($campus_id){
		$query = $this->db->query("SELECT * FROM building WHERE campus_id = '$campus_id' ORDER BY building_code ASC");
		return $query;
	}

	function _get_faculty_college($college_id){
		$query = $this->db->query("SELECT * FROM employee WHERE college_id = '$college_id' ORDER BY employee_lname ASC");
		return $query;
	}

	function _get_college($id){
		$query = $this->db->query("SELECT * FROM college WHERE college.campus_id = '$id' /*AND college.is_active = 'T' */ ORDER BY college.college_code ASC");
		return $query;
	}

	function _get_department($id){
		$query = $this->db->query("SELECT *, college_department.id AS college_dep_id FROM college_department LEFT JOIN department ON department.id = college_department.department_id
			WHERE college_department.college_id= '$id' AND college_department.active = 'T' AND department.active = 'T' ");
		return $query;
	}

	function _get_program($id){
		$query = $this->db->query("SELECT *, program.id AS program_id, program_major.program_major_shortname AS prg_mjr_code FROM department_program 
			LEFT JOIN program ON program.id = department_program.program_id
			LEFT JOIN curriculum ON curriculum.id = department_program.curriculum_id

			LEFT JOIN ref_academic_term ON ref_academic_term.id = curriculum.academic_term_id

			LEFT JOIN program_major ON  program_major.program_id = program.id
			WHERE department_program.college_department_id = '$id'   AND department_program.active = 'T' ");
		return $query;
	}
 

	function _get_course_details($course_id, $campus_id, $college_id, $department_id, $program_id){
		$query = $this->db->query("SELECT *, course.id AS class_subject_id, department_program.id AS department_p_id FROM curriculum 
			LEFT JOIN curriculum_course ON curriculum_course.curriculum_id = curriculum.id
			LEFT JOIN ref_academic_term ON ref_academic_term.id = curriculum.academic_term_id
			LEFT JOIN department_program ON department_program.curriculum_id = curriculum.id
			LEFT JOIN college_department ON college_department.id = department_program.college_department_id
			LEFT JOIN program ON program.id = department_program.program_id
			
			LEFT JOIN college ON college.id = college_department.college_id
			LEFT JOIN campus ON campus.id = college.campus_id
			LEFT JOIN department ON department.id = college_department.department_id
			LEFT JOIN block ON block.department_program_id = department_program.id
			LEFT JOIN course ON course.id = curriculum_course.course_id
			LEFT JOIN ref_year_term ON ref_year_term.id = curriculum_course.year_term_id
/*
			WHERE curriculum_course.is_active = 'T'
			AND ref_academic_term.is_active = 'T' 
			AND department_program.is_active = 'T' 
			AND college_department.is_active = 'T' 
			AND program.is_active = 'T' 
			AND college.is_active = 'T' 
			AND campus.is_active = 'T' 
			AND department.is_active = 'T' 
			AND course.is_active = 'T'
			AND ref_year_term.is_active = 'T'
*/


		/*	AND*/
			WHERE curriculum_course.course_id = '$course_id'
			AND program.id = '$program_id'
			AND campus.id = '$campus_id'
			AND college.id = '$college_id'
			AND department.id = '$department_id' ");
		return $query;
	}

	function _get_class_block($department_program_id){
		$query = $this->db->query("SELECT * FROM block 
			WHERE department_program_id = '$department_program_id' 
			 AND active = 'T' ORDER BY block_code ASC");
		return $query;
	}


	function _get_class_block_general($id){
		$query = $this->db->query("SELECT * FROM block
LEFT JOIN college_department ON college_department.id = block.college_department_id
WHERE college_department.department_id = '$id'  AND block.active = 'T' ORDER BY block.block_code ASC

 

			 ");
		return $query;
	}

	function _get_schedules(){
		$query = $this->db->query("SELECT *,  



			class_schedule.id AS class_sched_id, class_schedule.faculty_in_charge_id AS fctyl_inchd_id, course_schedule.faculty_in_charge_id AS cctyl_inchd_id  FROM class_schedule
			LEFT JOIN employee ON employee.id = class_schedule.faculty_in_charge_id
			LEFT JOIN room ON room.id = class_schedule.room_id
			LEFT JOIN class_event ON class_event.id = class_schedule.class_event_id
			LEFT JOIN course_schedule ON course_schedule.id = class_schedule.course_schedule_id
			LEFT JOIN block ON block.id = course_schedule.block_id
			LEFT JOIN course ON course.id = course_schedule.course_id



LEFT JOIN department_program ON department_program.id = block.department_program_id 

LEFT JOIN college_department ON college_department.id = department_program.college_department_id
LEFT JOIN college ON college.id = college_department.college_id

			WHERE class_schedule.active = 'T' AND  class_schedule.is_active= 'T' ORDER BY class_sched_id DESC");
		return $query;
	}


	function _disable_schedule($id){
		$query = $this->db->query("UPDATE class_schedule SET active = 'F' , is_active = 'F' 
			WHERE id = '$id'  ");
		return $query;
	}

	function _update_schedule($class_event_id,$no_of_units, $day, $time , $start_time  ,$end_time ,$id , $faculty_id ,$room_id, $coordinator_id){
		$this->db->query("UPDATE class_schedule SET class_event_id = '$class_event_id' ,
		 no_of_units = '$no_of_units' , 
		 day = '$day' ,
		  time = '$time' 
		 , start_time = '$start_time' , faculty_in_charge_id =  $faculty_id, room_id = $room_id,
		 end_time = '$end_time' WHERE id = '$id' ");



		$this->db->query("UPDATE course_schedule SET faculty_in_charge_id = $coordinator_id
			FROM (SELECT class_schedule.course_schedule_id AS csid  FROM class_schedule  WHERE id = '$id' ) AS class_sched
			WHERE course_schedule.id = class_sched.csid ");

		return $query;
	}




 
}

/*
SELECT *, department_program.id AS department_p_id FROM curriculum 
			LEFT JOIN department_program ON department_program.curriculum_id = curriculum.id
			LEFT JOIN curriculum_course ON curriculum_course.curriculum_id = curriculum.id

			LEFT JOIN block ON block.department_program_id = department_program.id

WHERE curriculum_course.course_id = '$course_id'


*/

