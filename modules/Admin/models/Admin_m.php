<?php

class Admin_m extends CI_Model{

	
	function __construct(){
		parent::__construct();
	}
	
	function get_login($user_name, $password ){
		$query = $this->db->query("SELECT * , UNIX_TIMESTAMP( STR_TO_DATE(`users_pwd`.`date_expiry`, '%d/%m/%Y') ) AS `pwrd_unix_epry`
			FROM `users` 
			INNER JOIN `role` ON `role`.`role_id` = `users`.`role_id`
			INNER JOIN `users_pwd` ON `users_pwd`.`user_id` = `users`.`user_id`
			WHERE `user_name` = '$user_name' AND `password` = '$password' AND `users_pwd`.`is_active` = '1' ");
		return $query;
	}

	public function fetch_all_departments(){
		$query = $this->db->query("SELECT * FROM `department` ORDER BY `department`.`department_name` ASC");
		return $query;
	}

	public function fetch_all_roles(){
		$query = $this->db->query("SELECT * FROM `role` ORDER BY `role`.`role_types` ASC");
		return $query;
	}

	public function fetch_access_areas(){
		$query = $this->db->query("SELECT * FROM `access_area` WHERE `access_area`.`is_active` = '1' ");
		return $query;
	}

	public function insert_access_control($access_area_id,$user_id){
		$this->db->query("INSERT INTO `access_control` ( `access_area_id`, `can_access`, `can_control`, `user_id`) VALUES ( '$access_area_id', 'off', 'off', '$user_id') ");
	}

	public function user_access_list($user_id){
			$query = $this->db->query(" SELECT * FROM `access_control` 
				INNER JOIN `access_area` ON `access_area`.`access_area_id` = `access_control`.`access_area_id`
				WHERE `access_control`.`user_id` = '$user_id' ");
		return $query;
	}

	public function fetch_admin_defaults(){
		$query = $this->db->query("SELECT * FROM `admin_settings` INNER JOIN `email` ON `email`.`email_id` = `admin_settings`.`admin_email_id` WHERE `admin_settings`.`admin_settings_id` = '1' ");
		return $query;
	}

	public function log_out($user_id){
		$query = $this->db->query("UPDATE `users` SET `user_login_status` = 0 WHERE `user_id` = '$user_id'");
	}
/*
	public function add_new_user($login_name,$password,$user_first_name,$user_last_name,$user_gender,$user_department_id,$user_profile_photo,$user_timestamp_registered,$user_role_id,$user_email_id,$user_skype,$user_skype_password,$user_contact_number_id,$user_focus_company_id,$user_date_of_birth,$user_comments_id,$admin){
		$this->db->query("INSERT INTO `users` (`user_name`, `password`, `user_first_name`, `user_last_name`, `user_gender`, `user_department_id`, `user_profile_photo`, `user_timestamp_registered`, `user_role_id`, `user_email_id`, `user_skype`,`user_skype_password`, `user_contact_number_id`, `user_focus_company_id`, `user_date_of_birth`, `user_comments_id`,`if_admin`)
		VALUES ('$login_name', '$password', '$user_first_name', '$user_last_name', '$user_gender', '$user_department_id', '$user_profile_photo', '$user_timestamp_registered', '$user_role_id', '$user_email_id', '$user_skype','$user_skype_password', '$user_contact_number_id', '$user_focus_company_id', '$user_date_of_birth', '$user_comments_id','$admin')");
		$last_insert_id = $this->db->insert_id();
		return $last_insert_id;
	}

	public function update_contact_email($email_id,$email,$contact_number_id,$direct_number,$mobile_number,$after_hours){
		$query = $this->db->query("UPDATE `contact_number`,`email` SET `direct_number` = '$direct_number', `mobile_number` = '$mobile_number', `after_hours` = '$after_hours' , `general_email` = '$email' WHERE `contact_number`.`contact_number_id` = '$contact_number_id' AND `email`.`email_id` = '$email_id' ");
	}

	public function update_comments($user_comments_id,$comments){
		$query = $this->db->query("UPDATE `notes` SET `comments` = '$comments'  WHERE `notes`.`notes_id` = '$user_comments_id'  ");
	}

	public function update_user_details($user_id,$login_name,$user_first_name,$user_last_name,$user_role_id,$user_skype,$user_skype_password,$user_gender,$user_date_of_birth,$department_id,$user_focus_company_id,$user_comments_id,$profile,$admin){
		$query = $this->db->query("UPDATE `users` SET `user_name` = '$login_name', `user_first_name` = '$user_first_name', `user_last_name` = '$user_last_name',`user_profile_photo`='$profile' ,`user_role_id` = '$user_role_id',`user_comments_id` = '$user_comments_id', `user_skype` = '$user_skype',`user_skype_password` = '$user_skype_password',`user_focus_company_id`='$user_focus_company_id', `user_gender`='$user_gender',`user_date_of_birth`='$user_date_of_birth',`user_department_id` = '$department_id', `if_admin` = '$admin'  WHERE `users`.`user_id` = '$user_id' ");
	}

	public function change_user_password($new_password,$user_id){
		$query = $this->db->query("UPDATE `users` SET `password` = '$new_password' WHERE `users`.`user_id` = '$user_id' ");
	}

	public function fetch_user_by_role($rode_id){
		$query = $this->db->query("SELECT * FROM `users` WHERE `users`.`user_role_id` = '$rode_id' AND `users`.`is_active` = '1' ");
		return $query;
	}


	public function fetch_login_user(){
		$query = $this->db->query("SELECT * FROM `users` WHERE `users`.`user_login_status` = 1");
		return $query;
	}
*/

}