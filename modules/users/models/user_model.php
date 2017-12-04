<?php

class User_model extends CI_Model{	
	
	function __construct(){
		parent::__construct();
	}
	
	function validate($user_name, $password, $ip_add = 0){
		$query = $this->db->query("SELECT `users`.*,`role`.`role_types` FROM `users` LEFT JOIN `role` ON `role`.`role_id` = `users`.`user_role_id`  WHERE `users`.`login_name` = '$user_name' AND `users`.`password` = '$password'");
		if($query->num_rows === 1){
			foreach ($query->result() as $row)
			{
			    $user_id = $row->user_id;
			    $ip_address = $row->ip_address;
			    $user_log_stat = $row->user_login_status;
			}
			if($user_log_stat == 1){
				if($ip_add == $ip_address){
					$update_query = $this->db->query("UPDATE users set user_login_status = 1, ip_address = '$ip_add' where user_id = '$user_id'");
					return $query->row();
				}else{
					return "1";
				}
			}else{
				$update_query = $this->db->query("UPDATE users set user_login_status = 1, ip_address = '$ip_add' where user_id = '$user_id'");
				return $query->row();
			}
		}else{
			return "0";
		}
	}

	function get_user_id($user_name, $password, $ip_add = 0){
		$query = $this->db->query("SELECT * FROM `users` WHERE `login_name` = '$user_name' AND `password` = '$password'");
		return $query->row();
	}

	public function fetch_pms_year($year){
		$query = $this->db->query(" SELECT `project`.`project_manager_id`,`users`.*  FROM `project`
			LEFT JOIN `users` ON`users`.`user_id` = `project`.`project_manager_id`

			WHERE `project`.`is_active` = '1' AND  `project`.`project_date` LIKE '%$year' AND `project`.`job_category` <> 'Company'
			GROUP BY `project`.`project_manager_id` ");
		return $query;
	}

	public function fetch_company_group($company_id = '' ,$is_prime = ''){


		if($is_prime != ''){

			$query = $this->db->query("SELECT `admin_company`.* , `company_details`.`company_name`,  `states`.`name` AS `state_name` FROM `admin_company`
				LEFT JOIN `company_details` ON `company_details`.`company_id` =  `admin_company`.`admin_company_details_id`
				LEFT JOIN  `address_detail` ON  `address_detail`.`address_detail_id` =  `company_details`.`address_id`
				LEFT JOIN  `address_general` ON  `address_general`.`general_address_id` =  `address_detail`.`general_address_id`
				LEFT JOIN  `states` ON  `states`.`id` =  `address_general`.`state_id`
				WHERE `admin_company`.`admin_company_details_id`  = '$company_id' AND  `company_details`.`active` = '1'");
		}else{
			$query = $this->db->query("SELECT `admin_company`.* , `company_details`.`company_name`,  `states`.`name` AS `state_name` FROM `admin_company`
				LEFT JOIN `company_details` ON `company_details`.`company_id` =  `admin_company`.`admin_company_details_id`
				LEFT JOIN  `address_detail` ON  `address_detail`.`address_detail_id` =  `company_details`.`address_id`
				LEFT JOIN  `address_general` ON  `address_general`.`general_address_id` =  `address_detail`.`general_address_id`
				LEFT JOIN  `states` ON  `states`.`id` =  `address_general`.`state_id`
				WHERE `admin_company`.`parent`  = '$company_id' AND  `company_details`.`active` = '1'");
		}
		return $query;
	}

	function list_active_reoccur_availability($time_stamp){
		$query = $this->db->query("SELECT * FROM `user_reoccur_availability` 
			WHERE `user_reoccur_availability`.`date_future` = '$time_stamp' 
			AND `user_reoccur_availability`.`date_range_a` <= '$time_stamp' 
			AND `user_reoccur_availability`.`date_range_b` >= '$time_stamp' 
			AND `user_reoccur_availability`.`is_active` = '1' AND `user_reoccur_availability`.`date_future` != '' ");
			return $query;
	}

	function update_future_reoccur_present_date($date_range_a,$date_future,$reoccur_id){
		$query = $this->db->query(" UPDATE `user_reoccur_availability` SET `date_range_a` = '$date_range_a', `date_future` = '$date_future' WHERE `user_reoccur_availability`.`reoccur_id` = $reoccur_id ");
		return $query;
	}

	function get_reoccur_ave_year_month($time_stamp,$time_set,$user_id){
		$query = $this->db->query(" SELECT * FROM `user_reoccur_availability`

			LEFT JOIN `user_availability` ON `user_availability`.`reoccur_id` =  `user_reoccur_availability`.`reoccur_id`
			LEFT JOIN  `users` ON `users`.`user_id` =  `user_availability`.`user_id`
			LEFT JOIN `company_details` ON `company_details`.`company_id` = `users`.`user_focus_company_id`

			WHERE `user_reoccur_availability`.`date_range_a` = '$time_stamp'
			AND   `user_reoccur_availability`.`date_future` <> ''
			AND   `user_reoccur_availability`.`start_time` <= '$time_set'
			AND   `user_reoccur_availability`.`end_time` >= '$time_set'
			AND   `user_availability`.`user_id` = '$user_id' AND `user_reoccur_availability`.`is_active` = '1'
			ORDER BY `user_reoccur_availability`.`reoccur_id` DESC LIMIT 1");
		return $query;
	}

	function insert_user_availability_reoccur($start_time,$end_time,$pattern_type,$limits,$range_reoccur,$date_range_a,$date_range_b,$is_no_end,$date_future){
		$this->db->query(" INSERT INTO `user_reoccur_availability` (`start_time`, `end_time`, `pattern_type`, `limits`, `range_reoccur`, `date_range_a`, `date_range_b`, `is_no_end`, `date_future` ) VALUES ( '$start_time', '$end_time', '$pattern_type', '$limits', '$range_reoccur', '$date_range_a', '$date_range_b', '$is_no_end', '$date_future') ");
		$last_insert_id = $this->db->insert_id();
		return $last_insert_id;
	}

	public function update_company_director($user_id,$company){
		$query = $this->db->query("UPDATE  `users` SET `users`.`direct_company` = $company WHERE `users`.`user_id` = '$user_id' ");
		return $query;
	}

	public function fetch_all_departments(){
		$query = $this->db->query("SELECT * FROM `department` ORDER BY `department`.`department_name` ASC");
		return $query;
	}

	public function insert_user_log($user_id,$date,$time,$actions,$project_id,$type){
		$query = $this->db->query("INSERT INTO `user_log` (`user_id`, `date`,`time`, `actions`, `project_id`, `type`) VALUES ('$user_id', '$date','$time', '$actions', '$project_id', '$type')");
		return $query;
	}

	public function fetch_user_role_dept($user_id){
		$query = $this->db->query("SELECT `users`.`user_department_id`,  `users`.`user_role_id`,`users`.`user_focus_company_id`,`users`.`direct_company`  FROM `users` WHERE `users`.`is_active` = '1' AND `users`.`user_id` = '$user_id' ");
		return $query;
	}

	public function fetch_user_logs(){
		$query = $this->db->query("SELECT `user_log`.*, `users`.`user_first_name`, `users`.`user_last_name` ,`project`.`project_name`, `company_details`.`company_name`
			FROM `user_log` 
			LEFT JOIN `users` ON `users`.`user_id` = `user_log`.`user_id` 
			LEFT JOIN `project` ON  `project`.`project_id` = `user_log`.`project_id` 
			LEFT JOIN `company_details` ON  `company_details`.`company_id` = `project`.`client_id`
			ORDER BY `user_log`.`user_log_id` DESC");
		return $query;
	}

	public function fetch_all_roles(){
		$query = $this->db->query("SELECT * FROM `role` ORDER BY `role`.`role_types` ASC");
		return $query;
	}

	public function fetch_role_access($preset_name){
		$query = $this->db->query("SELECT * FROM `user_access` WHERE `user_access`.`preset_name` = '$preset_name'");
		return $query;
	}

	public function fetch_all_access($user_id){
		$query = $this->db->query("SELECT * FROM `user_access` WHERE `user_access`.`user_id` = '$user_id' ORDER BY `user_id` ASC");
		return $query;
	}

	public function insert_user_access($user_id,$dashboard,$company,$projects,$wip,$purchase_orders,$invoice,$users,$bulletin_board,$project_schedule,$labour_schedule,$job_date_access){
		$query = $this->db->query("INSERT INTO `user_access` (`user_id`, `dashboard`, `company`, `projects`, `wip`, `purchase_orders`, `invoice`, `users`, `bulletin_board`, `project_schedule`, `labour_schedule`, `job_date`)
				 VALUES ( '$user_id', '$dashboard', '$company', '$projects', '$wip', '$purchase_orders', '$invoice', '$users', '$bulletin_board', '$project_schedule', '$labour_schedule', '$job_date_access')	");
		return $query;			
	}

	public function update_user_access($user_id,$is_admin,$dashboard,$company,$projects,$wip,$purchase_orders,$invoice,$users,$role_id,$bulletin_board,$project_schedule,$labour_schedule,$company_project,$shopping_center,$site_labour,$site_labour_app,$quick_quote,$quote_deadline,$leave_requests,$job_date_access,$purchase_order){
		$query = $this->db->query("UPDATE `user_access` SET `dashboard` = '$dashboard', `company` = '$company', `projects` = '$projects', `wip` = '$wip', `purchase_orders` = '$purchase_orders', `invoice` = '$invoice', `users` = '$users', `bulletin_board` = '$bulletin_board', `project_schedule` = '$project_schedule', `labour_schedule` = '$labour_schedule', `company_project` = '$company_project', `purchase_order` = '$purchase_order', `shopping_centre` = '$shopping_center',`site_labour` = '$site_labour',`quick_quote` = '$quick_quote',`quote_deadline` = '$quote_deadline',`leave_requests` = '$leave_requests',`job_date` = '$job_date_access' WHERE `user_access`.`user_id` = '$user_id' ");
		$this->db->flush_cache();
		$query = $this->db->query("UPDATE `users` SET `if_admin` = '$is_admin', `user_role_id` = '$role_id', `site_access` = '$site_labour_app' WHERE `users`.`user_id` ='$user_id' ");
		return $query;
		// $query = $this->db->query("UPDATE `user_access` SET `dashboard` = '$dashboard', `company` = '$company', `projects` = '$projects', `wip` = '$wip', `purchase_orders` = '$purchase_orders', `invoice` = '$invoice', `users` = '$users', `bulletin_board` = '$bulletin_board', `project_schedule` = '$project_schedule', `labour_schedule` = '$labour_schedule', `company_project` = '$company_project', `shopping_centre` = '$shopping_center' WHERE `user_access`.`user_id` = '$user_id' ");
		// $this->db->flush_cache();
		// $query = $this->db->query("UPDATE `users` SET `if_admin` = '$is_admin', `user_role_id` = '$role_id' WHERE `users`.`user_id` ='$user_id' ");
		// return $query;
	}

	public function inset_availability($user_id,$status,$notes,$date_time_stamp_a,$date_time_stamp_b,$reoccur_id = '0'){
		$query = $this->db->query(" INSERT INTO  `user_availability` (`user_id`, `status`, `notes`, `date_time_stamp_a`, `date_time_stamp_b`, `reoccur_id`) VALUES ( '$user_id', '$status', '$notes', '$date_time_stamp_a', '$date_time_stamp_b', '$reoccur_id')	");
		return $query;
	}

	public function delete_ava($ava_id){
		$query = $this->db->query("UPDATE `user_availability` SET `is_active` = '0' WHERE `user_availability`.`user_availability_id` = '$ava_id' ");
		return $query;
	}

	public function delete_ava_rec($ava_id){
		$query = $this->db->query("UPDATE `user_reoccur_availability` SET `is_active` = '0' WHERE `user_reoccur_availability`.`reoccur_id` = '$ava_id' ");
		return $query;
	}	

	public function get_upcomming_reoccuring_ave($user_id){
		$query = $this->db->query("SELECT * FROM `user_reoccur_availability`
			LEFT JOIN `user_availability` ON `user_availability`.`reoccur_id`  = `user_reoccur_availability`.`reoccur_id` 
			WHERE `user_reoccur_availability`.`is_active` = '1'   AND `user_availability`.`user_id` = '$user_id' ORDER BY `user_availability`.`date_time_stamp_a` ASC");
		return $query;
	}

	public function remove_availability($availability_id){
		$query = $this->db->query("UPDATE `user_availability` SET `is_active` = '0' WHERE `user_availability`.`user_availability_id` = '$availability_id' ");
	}

	public function update_ava($user_availability_id,$notes, $date_time_stamp_a , $date_time_stamp_b){
		$query = $this->db->query("UPDATE `user_availability` SET `notes` = '$notes', `date_time_stamp_a` = '$date_time_stamp_a', `date_time_stamp_b` = '$date_time_stamp_b' WHERE `user_availability`.`user_availability_id` = '$user_availability_id'");
		return $query;
	}

	public function fetch_future_availability($user_id,$time_stamp){
		$query = $this->db->query("SELECT * FROM `user_availability` WHERE `user_availability`.`user_id` = '$user_id' AND `user_availability`.`date_time_stamp_a` > '$time_stamp' AND `user_availability`.`is_active` = '1' AND `user_availability`.`reoccur_id` = '0' ORDER BY `user_availability`.`user_availability_id` ASC");
		return $query;
	}

	public function list_user_short(){
		$query = $this->db->query("SELECT `users`.`user_id` AS `primary_user_id` , `users`.`user_first_name`, `users`.`user_last_name` ,`users`.`user_role_id` , `users`.`user_focus_company_id`,`users`.`user_profile_photo`, `users`.`user_focus_company_id`, `company_details`.`company_name` FROM `users`
		LEFT JOIN `company_details` ON `company_details`.`company_id` = `users`.`user_focus_company_id`
		 WHERE `users`.`is_active` = '1' ORDER BY `users`.`user_first_name` ASC");
		return $query;
	}

	public function get_user_availability($user_id,$time_stamp){
		$query = $this->db->query("SELECT * FROM `user_availability`
			LEFT JOIN  `users` ON `users`.`user_id` =  `user_availability`.`user_id`
			LEFT JOIN `company_details` ON `company_details`.`company_id` = `users`.`user_focus_company_id`
			WHERE `user_availability`.`user_id` = '$user_id' 
			AND `user_availability`.`date_time_stamp_b` >= '$time_stamp' AND `user_availability`.`reoccur_id` = '0'
			AND `user_availability`.`date_time_stamp_a` <= '$time_stamp' AND `user_availability`.`is_active` = '1'
			ORDER BY `user_availability`.`user_availability_id` DESC LIMIT 1");
		return $query;
		//if($query->num_rows === 1){
	}


	public function get_reoccur_availability($current_timestamp, $time_extended, $day_like,$user_id){

		$query = $this->db->query("SELECT * FROM `user_reoccur_availability`
			LEFT JOIN `user_availability` ON `user_availability`.`reoccur_id` =  `user_reoccur_availability`.`reoccur_id`
			LEFT JOIN  `users` ON `users`.`user_id` =  `user_availability`.`user_id`
			LEFT JOIN `company_details` ON `company_details`.`company_id` = `users`.`user_focus_company_id`

			WHERE `user_reoccur_availability`.`date_range_a` <= '$current_timestamp'
			AND   `user_reoccur_availability`.`date_range_b` >= '$current_timestamp'
			/*AND `user_reoccur_availability`.`start_time` <= '$time_extended'
			AND `user_reoccur_availability`.`end_time` >= '$time_extended'*/
			AND `user_reoccur_availability`.`is_active` = '1' AND `user_availability`.`is_active` = '1'
			AND `user_reoccur_availability`.`range_reoccur` LIKE '%$day_like%'
			AND `user_availability`.`user_id` = '$user_id'
			ORDER BY `user_reoccur_availability`.`reoccur_id` DESC LIMIT 1");
		return $query;
	}

	public function fetch_admin_defaults(){
		$query = $this->db->query("SELECT * FROM `admin_defaults`");
		return $query;
	}

	public function add_new_user($login_name,$password,$user_first_name,$user_last_name,$user_gender,$user_department_id,$user_profile_photo,$user_timestamp_registered,$user_role_id,$user_email_id,$user_skype,$user_skype_password,$user_contact_number_id,$user_focus_company_id,$user_date_of_birth,$user_comments_id,$admin,$site_select,$contractor_employee){
		$this->db->query("INSERT INTO `users` (`login_name`, `password`, `user_first_name`, `user_last_name`, `user_gender`, `user_department_id`, `user_profile_photo`, `user_timestamp_registered`, `user_role_id`, `user_email_id`, `user_skype`,`user_skype_password`, `user_contact_number_id`, `user_focus_company_id`, `user_date_of_birth`, `user_comments_id`,`if_admin`,`site_access`,`is_third_party`)
		VALUES ('$login_name', '$password', '$user_first_name', '$user_last_name', '$user_gender', '$user_department_id', '$user_profile_photo', '$user_timestamp_registered', '$user_role_id', '$user_email_id', '$user_skype','$user_skype_password', '$user_contact_number_id', '$user_focus_company_id', '$user_date_of_birth', '$user_comments_id','$admin','$site_select','$contractor_employee')");
		$last_insert_id = $this->db->insert_id();
		return $last_insert_id;
	}
	// public function add_new_user($login_name,$password,$user_first_name,$user_last_name,$user_gender,$user_department_id,$user_profile_photo,$user_timestamp_registered,$user_role_id,$user_email_id,$user_skype,$user_skype_password,$user_contact_number_id,$user_focus_company_id,$user_date_of_birth,$user_comments_id,$admin){
	// 	$this->db->query("INSERT INTO `users` (`login_name`, `password`, `user_first_name`, `user_last_name`, `user_gender`, `user_department_id`, `user_profile_photo`, `user_timestamp_registered`, `user_role_id`, `user_email_id`, `user_skype`,`user_skype_password`, `user_contact_number_id`, `user_focus_company_id`, `user_date_of_birth`, `user_comments_id`,`if_admin`)
	// 	VALUES ('$login_name', '$password', '$user_first_name', '$user_last_name', '$user_gender', '$user_department_id', '$user_profile_photo', '$user_timestamp_registered', '$user_role_id', '$user_email_id', '$user_skype','$user_skype_password', '$user_contact_number_id', '$user_focus_company_id', '$user_date_of_birth', '$user_comments_id','$admin')");
	// 	$last_insert_id = $this->db->insert_id();
	// 	return $last_insert_id;
	// }

	public function update_contact_email($email_id,$email,$contact_number_id,$direct_number,$mobile_number,$after_hours){
		$query = $this->db->query("UPDATE `contact_number`,`email` SET `direct_number` = '$direct_number', `mobile_number` = '$mobile_number', `after_hours` = '$after_hours' , `general_email` = '$email' WHERE `contact_number`.`contact_number_id` = '$contact_number_id' AND `email`.`email_id` = '$email_id' ");
	}

	public function update_comments($user_comments_id,$comments){
		$query = $this->db->query("UPDATE `notes` SET `comments` = '$comments'  WHERE `notes`.`notes_id` = '$user_comments_id'  ");
	}

	public function update_user_details($user_id,$login_name,$user_first_name,$user_last_name,$user_skype,$user_skype_password,$user_gender,$user_date_of_birth,$department_id,$is_offshore,$user_focus_company_id,$user_comments_id,$profile,$supervisor_id,$contractor_employee){
		$query = $this->db->query("UPDATE `users` SET `login_name` = '$login_name', `user_first_name` = '$user_first_name', `user_last_name` = '$user_last_name',`user_profile_photo`='$profile',`user_comments_id` = '$user_comments_id', `user_skype` = '$user_skype',`user_skype_password` = '$user_skype_password',`user_focus_company_id`='$user_focus_company_id', `user_gender`='$user_gender',`user_date_of_birth`='$user_date_of_birth',`user_department_id` = '$department_id', `is_offshore` = '$is_offshore', `supervisor_id` = '$supervisor_id', `is_third_party` = '$contractor_employee'    WHERE `users`.`user_id` = '$user_id' ");
	}

	public function change_user_password($new_password,$user_id,$days_psswrd_exp){
		$new_password_md = md5($new_password);
		
		$query = $this->db->query("UPDATE `users` SET `password` = '$new_password_md' WHERE `users`.`user_id` = '$user_id' ");

		$exp_date = date('d/m/Y', strtotime("+".$days_psswrd_exp." days"));
		$this->db->query("UPDATE `user_passwords` SET `is_active` = '0' WHERE `user_passwords`.`is_active` = '1' AND `user_passwords`.`user_id` = '$user_id' ORDER BY `user_passwords`.`users_passwords_id` DESC LIMIT 1 ");
		$query = $this->db->query("INSERT INTO `user_passwords` (`user_id`, `is_active`, `expiration_date`, `password`) VALUES ('$user_id', '1', '$exp_date', '$new_password') ");
	}

	public function insert_user_password($new_password,$user_id){
		$query = $this->db->query("INSERT INTO `user_passwords` (`user_id`, `is_active`, `expiration_date`, `password`) VALUES ('$user_id', '1', '10/10/2011', '$new_password') ");
	}

	public function delete_user($user_id){
		$query = $this->db->query("UPDATE `users` SET `is_active` = '0' WHERE `users`.`user_id` = '$user_id' ");
		$this->db->query("UPDATE `user_activity` SET `is_active` = '0' WHERE `user_activity`.`user_id` = '$user_id' ");		
	}

	public function get_latest_user_password($user_id){
		$query = $this->db->query("SELECT *,UNIX_TIMESTAMP( STR_TO_DATE(`user_passwords`.`expiration_date`, '%d/%m/%Y') )  AS 'expiration_date_mod' FROM `user_passwords` WHERE `user_passwords`.`is_active` = '1' AND `user_passwords`.`user_id` = '$user_id' ORDER BY `user_passwords`.`users_passwords_id` DESC LIMIT 1");
		return $query;
	}

	public function fetch_user_passwords($user_id){
		$query = $this->db->query("SELECT * FROM `user_passwords` WHERE  `user_passwords`.`user_id` = '$user_id' ");
		return $query;
	}

	public function select_static_defaults(){
		$query = $this->db->query("SELECT * FROM `static_defaults` ORDER BY `static_defaults_id` DESC LIMIT 1");
		return $query;
	}

	public function set_user_activity($user_id,$time_stamp){
		$query = $this->db->query("UPDATE `user_activity` SET `time_stamp` = '$time_stamp' WHERE `user_activity`.`user_id` = '$user_id' ");
		return $query;
	}

	public function set_user_logged_time($user_id,$time_stamp){
		$query = $this->db->query("UPDATE `user_activity` SET `time_logged_in` = '$time_stamp' WHERE `user_activity`.`user_id` = '$user_id' ");
		return $query;
	}

	public function fetch_user_activity(){
		$query = $this->db->query("SELECT `user_activity`.*, `users`.`user_first_name`,`users`.`user_profile_photo`,`user_activity`.`time_stamp`,`user_activity`.`time_logged_in` FROM `user_activity`
			LEFT JOIN  `users` ON `users`.`user_id`  = `user_activity`.`user_id` 
			WHERE `user_activity`.`is_active` = '1' AND `users`.`is_active` = '1' AND  `users`.`user_login_status` = '1' AND  `user_activity`.`time_stamp`  > 0
			ORDER BY `user_activity`.`time_stamp`  DESC ");
		return $query;

	}

	public function fetch_user($user_id=''){
		if($user_id != ''){			
			$query = $this->db->query("SELECT `users`.*,`department`.`department_id`,`department`.`department_name`,`role`.`role_id`,`role`.`role_types`,`email`.`email_id`,`email`.`general_email`,`contact_number`.*,`company_details`.`company_id`,`company_details`.`company_name`,`notes`.`comments`,`users`.`if_admin`, `users`.`direct_company`
				FROM `users` 
				LEFT JOIN `department` ON `department`.`department_id` =`users`.`user_department_id`
				LEFT JOIN `role` ON `role`.`role_id` = `users`.`user_role_id`
				LEFT JOIN `email` ON `email`.`email_id` = `users`.`user_email_id`
				LEFT JOIN `contact_number` ON `contact_number`.`contact_number_id` = `users`.`user_contact_number_id`
				LEFT JOIN `company_details` ON `company_details`.`company_id` = `users`.`user_focus_company_id`
				LEFT JOIN `notes` ON `notes`.`notes_id` = `users`.`user_comments_id`
				WHERE  `users`.`user_id` = '$user_id' ");
		}else{
			$query = $this->db->query("SELECT `users`.*,`department`.`department_id`,`department`.`department_name`,`role`.`role_types`,`email`.`general_email`,`contact_number`.*,`company_details`.`company_name`,`notes`.`comments`,`users`.`if_admin`, `users`.`direct_company` ,`users`.`is_third_party`
				FROM `users` 
				LEFT JOIN `department` ON `department`.`department_id` =`users`.`user_department_id`
				LEFT JOIN `role` ON `role`.`role_id` = `users`.`user_role_id`
				LEFT JOIN `email` ON `email`.`email_id` = `users`.`user_email_id`
				LEFT JOIN `contact_number` ON `contact_number`.`contact_number_id` = `users`.`user_contact_number_id`
				LEFT JOIN `company_details` ON `company_details`.`company_id` = `users`.`user_focus_company_id`
				LEFT JOIN `notes` ON `notes`.`notes_id` = `users`.`user_comments_id`
				WHERE `users`.`is_active` = '1'  ORDER BY `users`.`user_focus_company_id` ASC,`users`.`user_first_name` ASC ");
		}
		return $query;
	}

	public function fetch_user_by_role($rode_id){
		$query = $this->db->query("SELECT * FROM `users` WHERE `users`.`user_role_id` = '$rode_id' AND `users`.`is_active` = '1' ");
		return $query;
	}

	public function log_out($user_id){
		delete_cookie("user_id");
		$query = $this->db->query("update users set user_login_status = 0, ip_address = 0 where user_id = '$user_id'");
	}

	public function fetch_login_user($order=''){
		$query = $this->db->query("SELECT * FROM `users` WHERE `users`.`user_login_status` = '1' $order");
		return $query;
	}

	public function insert_user_min_log($user_id,$date_log,$time_log){
		$this->db->query("UPDATE `users` SET `users`.`date_log` = '$date_log', `users`.`time_log` = '$time_log' WHERE `users`.`user_id` = '$user_id'");
	}

	public function update_site_access($user_id,$site_select){
		$this->db->query("UPDATE `users` SET `users`.`site_access` = '$site_select' WHERE `users`.`user_id` = '$user_id'");
	}

	public function fetch_leave_type(){
		$query = $this->db->query("SELECT * FROM `leave_type`");
		return $query;
	}

	public function fetch_leave_alloc($user_id){
		$query = $this->db->query("SELECT * FROM `leave_allocation` WHERE `user_id` = '" . $user_id . "'");
		return $query;	
	}

	public function update_user_supervisor($user_id,$supervisor_id){
		$query = $this->db->query("UPDATE `users` SET `supervisor_id` = '$supervisor_id' WHERE `users`.`user_id` = '$user_id'");
		return $query;
	}

	public function fetch_users_under_supervisor($supervisor_id){
		$query = $this->db->query(" SELECT * FROM `users` 
			LEFT JOIN `role` ON `role`.`role_id` = `users`.`user_role_id`
			WHERE `users`.`is_active` = '1' AND `users`.`supervisor_id` = '$supervisor_id' AND `users`.`user_id` <> '3' 
			ORDER BY `users`.`user_role_id` ASC ,   `users`.`user_first_name` ASC  ");
		return $query;
	}

	public function fetch_user_state($user_id){
		$query = $this->db->query("SELECT `user_id`, `user_focus_company_id` FROM `users` WHERE `user_id` = '$user_id'");
		return $query;
	}
	
	public function insert_leave_req($current_date, $user_id, $leave_type, $timestamp_start, $timestamp_end, $timestamp_return, $leave_details, $total_days_away, $user_supervisor_id, $partial_day, $partial_part, $partial_time, $applied_by) {
		$query = $this->db->query("INSERT INTO `leave_request`(`date`, `user_id`, `leave_type_id`, `start_day_of_leave`, `end_day_of_leave`, `date_return`, `details`, `total_days_away`, `is_approve_supervisor_id`, `partial_day`, `partial_part`, `partial_time`, `applied_by`) VALUES ('$current_date', '$user_id', '$leave_type', '$timestamp_start', '$timestamp_end', '$timestamp_return', '$leave_details', '$total_days_away', '$user_supervisor_id', $partial_day, $partial_part, '$partial_time', '$applied_by')");
		 return $query; //$this->db->insert_id(); no use
	}

	public function fetch_pending_leaves($user_id){
		$query = $this->db->query("SELECT t1.`leave_request_id`, t1.`user_id`, t1.`date`, t3.`leave_type`, t1.`start_day_of_leave`, t1.`end_day_of_leave`, t1.`date_return`, t1.`details`, t1.`total_days_away`, t1.`is_approve`, t1.`is_disapproved`, t2.`supervisor_id`, t1.`partial_day`, t1.`partial_part`, t1.`partial_time`
			FROM `leave_request` AS t1
			LEFT JOIN `users` AS t2
			ON t1.`user_id` = t2.`user_id` 
			LEFT JOIN `leave_type` AS t3
			ON t1.`leave_type_id` = t3.`leave_type_id`
			WHERE t2.`user_id` = '$user_id' AND t1.`is_approve` = '0' AND t1.`is_disapproved` = '0' AND t1.`is_active` = '1'
			ORDER BY t1.`leave_request_id` DESC");
		return $query;
	}

	public function fetch_approved_leaves($user_id){
		$query = $this->db->query("SELECT t1.`leave_actions_id`, t1.`leave_request_id`, t1.`supervisor_id`, t1.`action`, t1.`date` AS 'date_approved', t2.`date` AS 'date_applied', t2.`user_id`, t3.`leave_type`, t2.`start_day_of_leave`, t2.`end_day_of_leave`, t2.`date_return`, t2.`total_days_away`, t2.`details`, t4.`user_first_name` AS 'approved_fname', t4.`user_last_name` AS 'approved_lname', t2.`is_approve`, t2.`partial_day`, t2.`partial_part`, t2.`partial_time`
			FROM `leave_actions` AS t1
			LEFT JOIN `leave_request` AS t2
			ON t1.`leave_request_id` = t2.`leave_request_id`
			LEFT JOIN `leave_type` AS t3
			ON t2.`leave_type_id` = t3.`leave_type_id`
			LEFT JOIN `users` AS t4
			ON t1.`supervisor_id` = t4.`user_id`
			WHERE t2.`user_id` = '$user_id' AND t1.`action` = '1' AND t2.`is_active` = '1' AND t1.`supervisor_id` <> '3'
			ORDER BY date_approved DESC");
			return $query;
	}

	public function fetch_approved_leaves_by_md($user_id){
		$query = $this->db->query("SELECT t1.`leave_actions_id`, t1.`leave_request_id`, t1.`supervisor_id`, t1.`action`, t1.`date` AS 'date_approved', t2.`date` AS 'date_applied', t2.`user_id`, t3.`leave_type`, t2.`start_day_of_leave`, t2.`end_day_of_leave`, t2.`date_return`, t2.`total_days_away`, t2.`details`, t4.`user_first_name` AS 'md_fname', t4.`user_last_name` AS 'md_lname', t2.`partial_day`, t2.`partial_part`, t2.`partial_time`
			FROM `leave_actions` AS t1
			LEFT JOIN `leave_request` AS t2
			ON t1.`leave_request_id` = t2.`leave_request_id`
			LEFT JOIN `leave_type` AS t3
			ON t2.`leave_type_id` = t3.`leave_type_id`
			LEFT JOIN `users` AS t4
			ON t1.`supervisor_id` = t4.`user_id`
			WHERE t2.`user_id` = '$user_id' AND t1.`action` = '1' AND t2.`is_active` = '1' AND t1.`supervisor_id` = '3'
			ORDER BY date_approved DESC");
			return $query;
	}

	public function fetch_unapproved_leaves($user_id){
		$query = $this->db->query("SELECT t1.`leave_actions_id`, t1.`leave_request_id`, t1.`supervisor_id`, t1.`action`, t1.`date` AS 'date_approved', t1.`action_comments`, t2.`date` AS 'date_applied', t2.`user_id`, t3.`leave_type`, t2.`start_day_of_leave`, t2.`end_day_of_leave`, t2.`date_return`, t2.`total_days_away`, t2.`details`, t4.`user_first_name` AS 'approved_fname', t4.`user_last_name` AS 'approved_lname', t2.`partial_day`, t2.`partial_part`, t2.`partial_time`
			FROM `leave_actions` AS t1
			LEFT JOIN `leave_request` AS t2
			ON t1.`leave_request_id` = t2.`leave_request_id`
			LEFT JOIN `leave_type` AS t3
			ON t2.`leave_type_id` = t3.`leave_type_id`
			LEFT JOIN `users` AS t4
			ON t1.`supervisor_id` = t4.`user_id`
			WHERE t2.`user_id` = '$user_id' AND t1.`action` = '0' AND t2.`is_active` = '1' 
			ORDER BY date_approved DESC");
		return $query;
	}

	public function fetch_pending_leaves_by_supervisor_id($supervisor_id){
		$query = $this->db->query("SELECT t1.`leave_request_id`, t1.`user_id` as 'leave_user_id', t1.`date`, t2.`user_first_name` AS 'first_name', 
			t2.`user_last_name` AS 'last_name', t3.`leave_type`, t1.`start_day_of_leave`, t1.`end_day_of_leave`, t1.`date_return`, 
			t1.`details`, t1.`total_days_away`, t1.`is_approve`, t1.`is_disapproved`, t2.`supervisor_id`, t4.`user_first_name`, 
			t4.`user_last_name`, t5.`action`, t6.`user_first_name` AS 'superv_first_name', t6.`user_last_name` AS 'superv_last_name', t5.`action_comments`, t1.`partial_day`, t1.`partial_part`, t1.`partial_time`
			FROM `leave_request` AS t1
			LEFT JOIN `users` AS t2
			ON t1.`user_id` = t2.`user_id` 
			LEFT JOIN `leave_type` AS t3
			ON t1.`leave_type_id` = t3.`leave_type_id`
			LEFT JOIN `users` AS t4
			ON t1.`is_approve_supervisor_id`  = t4.`user_id`
			LEFT JOIN `leave_actions` AS t5
			ON t1.`leave_request_id` = t5.`leave_request_id`
			LEFT JOIN `users` AS t6
			ON t5.`supervisor_id` = t6.`user_id`
			WHERE t1.`is_approve_supervisor_id`= '$supervisor_id' AND t1.`is_approve` = '0' AND t1.`is_disapproved` = '0' 
			ORDER BY t1.`leave_request_id` DESC");
		return $query;
	}

	public function fetch_leave_req_by_id($leave_req_id){
		$query = $this->db->query("SELECT t1.`leave_request_id`, t1.`leave_type_id`, t1.`user_id`, t2.`leave_type`, t1.`start_day_of_leave`, t1.`end_day_of_leave`, t1.`date_return`, t1.`details`, t1.`total_days_away`, t1.`partial_day`, t1.`partial_part`, t1.`partial_time`
			FROM `leave_request` AS t1
			LEFT JOIN `leave_type` AS t2
			ON t1.`leave_type_id` = t2.`leave_type_id`
			WHERE `leave_request_id` = '$leave_req_id'");
		return $query;
	}

	public function approved_by_supervisor($leave_request_id, $user_supervisor_id){
		$query = $this->db->query("UPDATE `leave_request` SET `is_approve_supervisor_id`='$user_supervisor_id' WHERE `leave_request_id` = '$leave_request_id'");
		return $query;
	}

	public function disapproved_by_supervisor($leave_request_id, $is_disapproved){
		$query = $this->db->query("UPDATE `leave_request` SET `is_disapproved`='$is_disapproved' WHERE `leave_request_id` = '$leave_request_id'");
		return $query;
	}

	public function approved_by_gm($leave_request_id, $is_approve, $supervisor_id){
		$query = $this->db->query("UPDATE `leave_request` SET `is_approve`='$is_approve', `is_approve_supervisor_id`='$supervisor_id' WHERE `leave_request_id` = '$leave_request_id'");
		return $query;
	}

	public function disapproved_by_gm($leave_request_id, $is_disapproved, $supervisor_id){
		$query = $this->db->query("UPDATE `leave_request` SET `is_disapproved`='$is_disapproved', `is_approve_supervisor_id`='$supervisor_id' WHERE `leave_request_id` = '$leave_request_id'");
		return $query;
	}

	public function insert_leave_action($leave_request_id, $supervisor_id, $action, $date, $action_comments){
		$query = $this->db->query("INSERT INTO `leave_actions`(`leave_request_id`, `supervisor_id`, `action`, `date`, `action_comments`) VALUES ('$leave_request_id', '$supervisor_id', '$action', '$date', '$action_comments')");
		return $query;
	}

	public function fetch_data_for_email($leave_request_id, $supervisor_id){
		$query = $this->db->query("SELECT t1.`leave_actions_id`, t1.`leave_request_id`, t1.`supervisor_id`, t1.`action_comments`, t2.`user_id`, t3.`leave_type`, t2.`start_day_of_leave`, t2.`end_day_of_leave`, t2.`date_return`, t2.`total_days_away`
			FROM `leave_actions` AS t1
			LEFT JOIN `leave_request` AS t2
			ON t1.`leave_request_id` = t2.`leave_request_id`
			LEFT JOIN `leave_type` AS t3
			ON t2.`leave_type_id` = t3.`leave_type_id`
			WHERE t1.`leave_request_id` = '$leave_request_id' AND t1.`action` = '1' AND t2.`is_approve` = '1' AND t1.`supervisor_id` = '$supervisor_id'");
		return $query;
	}

	public function fetch_approved_and_disapproved(){
		$query = $this->db->query("SELECT t1.`leave_actions_id`, t1.`leave_request_id`, t2.`user_id`, t1.`supervisor_id`, t1.`action_comments`, t2.`date`, t3.`user_first_name`, t3.`user_last_name`, t4.`leave_type`, t2.`start_day_of_leave`, t2.`end_day_of_leave`, t2.`date_return`,
			t2.`details`, t2.`total_days_away`, t5.`user_first_name` AS 'superv_first_name', t5.`user_last_name` AS 'superv_last_name', t1.`action`, t2.`is_approve`, t2.`is_disapproved`, t7.`supervisor_id` AS 'user_supervisor_id', t2.`partial_day`, t2.`partial_part`, t2.`partial_time`
			FROM `leave_actions` AS t1
			LEFT JOIN `leave_request` AS t2
			ON t1.`leave_request_id` = t2.`leave_request_id`
			LEFT JOIN `users` AS t3
			ON t2.`user_id` = t3.`user_id`
			LEFT JOIN `leave_type` AS t4
			ON t2.`leave_type_id` = t4.`leave_type_id`
			LEFT JOIN `users` AS t5
			ON t1.`supervisor_id` = t5.`user_id`
			LEFT JOIN `leave_actions` AS t6
			ON t1.`leave_actions_id` = t6.`leave_actions_id`
			LEFT JOIN `users` AS t7
			ON t2.`user_id` = t7.`user_id`
			WHERE t2.`is_active` = '1' AND t1.`supervisor_id` <> '3' OR t7.`supervisor_id` = '3'");
		return $query;
	}

	public function fetch_approved_and_disapproved_by_md(){
		$query = $this->db->query("SELECT t1.`leave_actions_id`, t1.`leave_request_id`, t2.`user_id`, t1.`supervisor_id`, t1.`leave_request_id`, t1.`action_comments`, t2.`date`, t3.`user_first_name`, t3.`user_last_name`, t4.`leave_type`, t2.`start_day_of_leave`, t2.`end_day_of_leave`, t2.`date_return`,
			t2.`details`, t2.`total_days_away`, t5.`user_first_name` AS 'md_fname', t5.`user_last_name` AS 'md_lname', t1.`action`, t2.`partial_day`, t2.`partial_part`, t2.`partial_time`
			FROM `leave_actions` AS t1
			LEFT JOIN `leave_request` AS t2
			ON t1.`leave_request_id` = t2.`leave_request_id`
			LEFT JOIN `users` AS t3
			ON t2.`user_id` = t3.`user_id`
			LEFT JOIN `leave_type` AS t4
			ON t2.`leave_type_id` = t4.`leave_type_id`
			LEFT JOIN `users` AS t5
			ON t1.`supervisor_id` = t5.`user_id`
			WHERE t1.`supervisor_id` = '3'");
		return $query;
	}

	public function update_leave_req($leave_request_id, $leave_type_id, $start_day_of_leave, $end_day_of_leave, $date_return, $details, $total_days_away, $partial_day, $partial_part, $partial_time, $edited_by){
		$query = $this->db->query("UPDATE `leave_request` SET `leave_type_id` = '$leave_type_id', `start_day_of_leave` = '$start_day_of_leave', `end_day_of_leave` = '$end_day_of_leave', `date_return` = '$date_return', `details` = '$details', `total_days_away` = '$total_days_away', `is_disapproved` = '0', `partial_day` = '$partial_day', `partial_part` = '$partial_part', `partial_time` = '$partial_time', `edited_by` = '$edited_by' WHERE `leave_request_id` = '$leave_request_id'");
		return $query;
	}

	public function inactive_leave_req($leave_request_id){
		$query = $this->db->query("UPDATE `leave_request` SET `is_active` = '0' WHERE `leave_request_id` = '$leave_request_id'");
		return $query;
	}	

	public function remove_leave_action($leave_request_id){
		$query = $this->db->query("DELETE FROM `leave_actions` WHERE `leave_request_id` = '$leave_request_id'");
		return $query;
	}

	public function fetch_leave_defaults(){
		$query = $this->db->query("SELECT * FROM `leave_defaults` WHERE `leave_defaults_id` = '1'");
		return $query;
	}

	public function for_pdf_content($leave_request_id, $user_supervisor_id){

		if ($user_supervisor_id == 3){

			$query = $this->db->query("SELECT t1.`leave_request_id`, t1.`date`, t2.`user_first_name`, t2.`user_last_name`, t3.`role_types`, t1.`start_day_of_leave`, t1.`end_day_of_leave`, t1.`date_return`,
				t1.`leave_type_id`, t4.`leave_type`, t1.`details`, t1.`total_days_away`, t1.`partial_day`, t1.`partial_part`, t1.`partial_time`, t5.`user_first_name` AS 'superv_first_name', t5.`user_last_name` AS 'superv_last_name', 
				t6.`total_annual`, t6.`total_personal`, t6.`no_hrs_of_work`
				FROM `leave_request` AS t1
				LEFT JOIN `users` AS t2
				ON t1.`user_id` = t2.`user_id`
				LEFT JOIN `role` AS t3
				ON t2.`user_role_id` = t3.`role_id`
				LEFT JOIN `leave_type` AS t4
				ON t1.`leave_type_id` = t4.`leave_type_id`
				LEFT JOIN `users` AS t5
				ON t1.`is_approve_supervisor_id` = t5.`user_id`
				LEFT JOIN `leave_allocation` AS t6
				ON t1.`user_id` = t6.`user_id`
				WHERE t1.`leave_request_id` = '$leave_request_id'");
			return $query;
		} else {
			$query = $this->db->query("SELECT t1.`leave_request_id`, t1.`date`, t2.`user_first_name`, t2.`user_last_name`, t3.`role_types`, t1.`start_day_of_leave`, t1.`end_day_of_leave`, t1.`date_return`,
				t1.`leave_type_id`, t4.`leave_type`, t1.`details`, t1.`total_days_away`, t1.`partial_day`, t1.`partial_part`, t1.`partial_time`, t6.`user_first_name` AS 'superv_first_name', t6.`user_last_name` AS 'superv_last_name', 
				t7.`total_annual`, t7.`total_personal`, t7.`no_hrs_of_work`, t5.`action_comments`
				FROM `leave_request` AS t1
				LEFT JOIN `users` AS t2
				ON t1.`user_id` = t2.`user_id`
				LEFT JOIN `role` AS t3
				ON t2.`user_role_id` = t3.`role_id`
				LEFT JOIN `leave_type` AS t4
				ON t1.`leave_type_id` = t4.`leave_type_id`
				LEFT JOIN `leave_actions` AS t5
				ON t1.`leave_request_id` = t5.`leave_request_id`
				LEFT JOIN `users` AS t6
				ON t5.`supervisor_id` = t6.`user_id`
				LEFT JOIN `leave_allocation` AS t7
				ON t1.`user_id` = t7.`user_id`
				WHERE t5.`leave_request_id` = '$leave_request_id' AND t5.`action` = '1' AND t5.`supervisor_id` <> '3'");
			return $query;

		}
	}

	public function for_pdf_content_md($leave_request_id){
		$query = $this->db->query("SELECT t1.`leave_request_id`, t1.`date`, t2.`user_first_name`, t2.`user_last_name`, t3.`role_types`, t1.`start_day_of_leave`, t1.`end_day_of_leave`, t1.`date_return`,
			t1.`leave_type_id`, t4.`leave_type`, t1.`details`, t1.`total_days_away`, t6.`user_first_name` AS 'superv_first_name', t6.`user_last_name` AS 'superv_last_name', 
			t7.`total_annual`, t7.`total_personal`, t7.`no_hrs_of_work`, t5.`action_comments`
			FROM `leave_request` AS t1
			LEFT JOIN `users` AS t2
			ON t1.`user_id` = t2.`user_id`
			LEFT JOIN `role` AS t3
			ON t2.`user_role_id` = t3.`role_id`
			LEFT JOIN `leave_type` AS t4
			ON t1.`leave_type_id` = t4.`leave_type_id`
			LEFT JOIN `leave_actions` AS t5
			ON t1.`leave_request_id` = t5.`leave_request_id`
			LEFT JOIN `users` AS t6
			ON t5.`supervisor_id` = t6.`user_id`
			LEFT JOIN `leave_allocation` AS t7
			ON t1.`user_id` = t7.`user_id`
			WHERE t5.`leave_request_id` = '$leave_request_id' AND t5.`action` = '1' AND t5.`supervisor_id` = '3'");
		return $query;
	}

	public function fetch_leave_alloc_all(){
		$query = $this->db->query("SELECT * FROM `leave_allocation`");
		return $query;	
	}

	public function add_leave_alloc($current_date, $user_id, $annual_manual_entry, $personal_manual_entry, $checked_sched, $no_hrs_of_work, $current_date){
		$query = $this->db->query("INSERT INTO `leave_allocation` (`year`, `start_date_log`, `user_id`, `annual_manual_entry`, `personal_manual_entry`, `sched_of_work`, `no_hrs_of_work`, `date_log`) VALUES ('".date('Y')."', '$current_date', '$user_id', '$annual_manual_entry', '$personal_manual_entry', '$checked_sched', '$no_hrs_of_work', '$current_date')");
		return $query;
	}

	public function update_leave_alloc_sched($user_id, $annual_manual_entry, $personal_manual_entry, $checked_sched, $no_hrs_of_work, $current_date, $last_annual_accumulated, $last_personal_accumulated){
		$query = $this->db->query("UPDATE `leave_allocation` SET `annual_manual_entry`='$annual_manual_entry', `personal_manual_entry`='$personal_manual_entry', `sched_of_work`='$checked_sched', `no_hrs_of_work`='$no_hrs_of_work', `date_log`='$current_date', `last_annual_accumulated`='$last_annual_accumulated', `last_personal_accumulated`='$last_personal_accumulated' WHERE `user_id` = '$user_id'");
		return $query;
	}

	public function update_leave_alloc($user_id, $annual_manual_entry, $personal_manual_entry, $no_hrs_of_work, $current_date){
		$query = $this->db->query("UPDATE `leave_allocation` SET `annual_manual_entry`='$annual_manual_entry', `personal_manual_entry`='$personal_manual_entry', `no_hrs_of_work`='$no_hrs_of_work', `date_log`='$current_date' WHERE `user_id` = '$user_id'");
		return $query;
	}

	public function get_sched($user_id){
		$query = $this->db->query("SELECT `sched_of_work` FROM `leave_allocation` WHERE `user_id` = '$user_id'");
		return $query;
	}

	public function update_sched($checked_sched, $user_id){
		$query = $this->db->query("UPDATE `leave_allocation` SET `sched_of_work`='$checked_sched' WHERE `user_id` = '$user_id'");
		return $query;
	}

	public function update_total_leave($total_annual, $total_personal, $user_id){
		$query = $this->db->query("UPDATE `leave_allocation` SET `total_annual` = '$total_annual', `total_personal` = '$total_personal' WHERE `user_id` = '$user_id'");
		return $query;
	}

	public function update_earned_points($annual_accumulated, $personal_accumulated, $user_id){
		$query = $this->db->query("UPDATE `leave_allocation` SET `annual_accumulated` = '$annual_accumulated', `personal_accumulated` = '$personal_accumulated' WHERE `user_id` = '$user_id'");
		return $query;
	}

	public function update_earned_offshore($annual_earned_offshore, $personal_earned_offshore, $current_month, $user_id){
		$query = $this->db->query("UPDATE `leave_allocation` SET `annual_earned_offshore` = '$annual_earned_offshore', `personal_earned_offshore` = '$personal_earned_offshore', `last_month_update_offshore` = '$current_month' WHERE `user_id` = '$user_id'");
		return $query;
	}

	public function get_leave_alloc($user_id){
		$query = $this->db->query("SELECT * FROM `leave_actions` AS t1
			LEFT JOIN `leave_request` AS t2
			ON t1.`leave_request_id` = t2.`leave_request_id`
			LEFT JOIN `leave_allocation` AS t3
			ON t2.`user_id` = t3.`user_id`
			WHERE t2.`user_id` = '$user_id'");
		return $query;
	}
	
	public function get_total_leave_annual($user_id) {
		$query = $this->db->query("SELECT SUM(total_days_away) AS 'used_annual' FROM `leave_request` WHERE `is_approve` = '1' AND `leave_type_id` = '1'  AND `is_active` = '1' AND `user_id` = '$user_id'");
		return $query;
	}

	public function get_total_leave_personal($user_id) {
		$query = $this->db->query("SELECT SUM(total_days_away) AS 'used_personal' FROM `leave_request` WHERE `is_approve` = '1' AND `user_id` = '$user_id'  AND `is_active` = '1' AND `leave_type_id` IN('2','3','4')");
		return $query;
	}
}

?>