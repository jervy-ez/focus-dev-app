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

	public function insert_user_access($user_id,$dashboard,$company,$projects,$wip,$purchase_orders,$invoice,$users,$bulletin_board,$project_schedule,$labour_schedule){
		$query = $this->db->query("INSERT INTO `user_access` (`user_id`, `dashboard`, `company`, `projects`, `wip`, `purchase_orders`, `invoice`, `users`, `bulletin_board`, `project_schedule`, `labour_schedule`)
				 VALUES ( '$user_id', '$dashboard', '$company', '$projects', '$wip', '$purchase_orders', '$invoice', '$users', '$bulletin_board', '$project_schedule', '$labour_schedule')	");
		return $query;			
	}

	public function update_user_access($user_id,$is_admin,$dashboard,$company,$projects,$wip,$purchase_orders,$invoice,$users,$role_id,$bulletin_board,$project_schedule,$labour_schedule,$company_project,$shopping_center){
		$query = $this->db->query("UPDATE `user_access` SET `dashboard` = '$dashboard', `company` = '$company', `projects` = '$projects', `wip` = '$wip', `purchase_orders` = '$purchase_orders', `invoice` = '$invoice', `users` = '$users', `bulletin_board` = '$bulletin_board', `project_schedule` = '$project_schedule', `labour_schedule` = '$labour_schedule', `company_project` = '$company_project', `shopping_centre` = '$shopping_center' WHERE `user_access`.`user_id` = '$user_id' ");
		$this->db->flush_cache();
		$query = $this->db->query("UPDATE `users` SET `if_admin` = '$is_admin', `user_role_id` = '$role_id' WHERE `users`.`user_id` ='$user_id' ");
		return $query;
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
		$query = $this->db->query("SELECT `users`.`user_id` AS `primary_user_id` , `users`.`user_first_name`, `users`.`user_last_name` , `users`.`user_focus_company_id`,`users`.`user_profile_photo`, `users`.`user_focus_company_id`, `company_details`.`company_name` FROM `users`
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
			AND `user_reoccur_availability`.`start_time` <= '$time_extended'
			AND `user_reoccur_availability`.`end_time` >= '$time_extended'
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

	public function add_new_user($login_name,$password,$user_first_name,$user_last_name,$user_gender,$user_department_id,$user_profile_photo,$user_timestamp_registered,$user_role_id,$user_email_id,$user_skype,$user_skype_password,$user_contact_number_id,$user_focus_company_id,$user_date_of_birth,$user_comments_id,$admin,$site_select){
		$this->db->query("INSERT INTO `users` (`login_name`, `password`, `user_first_name`, `user_last_name`, `user_gender`, `user_department_id`, `user_profile_photo`, `user_timestamp_registered`, `user_role_id`, `user_email_id`, `user_skype`,`user_skype_password`, `user_contact_number_id`, `user_focus_company_id`, `user_date_of_birth`, `user_comments_id`,`if_admin`,`site_access`)
		VALUES ('$login_name', '$password', '$user_first_name', '$user_last_name', '$user_gender', '$user_department_id', '$user_profile_photo', '$user_timestamp_registered', '$user_role_id', '$user_email_id', '$user_skype','$user_skype_password', '$user_contact_number_id', '$user_focus_company_id', '$user_date_of_birth', '$user_comments_id','$admin','$site_select')");
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

	public function update_user_details($user_id,$login_name,$user_first_name,$user_last_name,$user_skype,$user_skype_password,$user_gender,$user_date_of_birth,$department_id,$user_focus_company_id,$user_comments_id,$profile){
		$query = $this->db->query("UPDATE `users` SET `login_name` = '$login_name', `user_first_name` = '$user_first_name', `user_last_name` = '$user_last_name',`user_profile_photo`='$profile',`user_comments_id` = '$user_comments_id', `user_skype` = '$user_skype',`user_skype_password` = '$user_skype_password',`user_focus_company_id`='$user_focus_company_id', `user_gender`='$user_gender',`user_date_of_birth`='$user_date_of_birth',`user_department_id` = '$department_id'  WHERE `users`.`user_id` = '$user_id' ");
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
		$this->db->query("UPDATE `user_activity` SET `is_active` = '0' WHERE `users`.`user_id` = '$user_id' ");		
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
			$query = $this->db->query("SELECT `users`.*,`department`.`department_id`,`department`.`department_name`,`role`.`role_types`,`email`.`general_email`,`contact_number`.*,`company_details`.`company_name`,`notes`.`comments`,`users`.`if_admin`, `users`.`direct_company`
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
}

?>