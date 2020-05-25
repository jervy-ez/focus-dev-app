<?php
class Users_m extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}
	
	public function get_user_details($user_id){
		$query = $this->db->query(" SELECT *,
			`users`.`email_id` AS `user_email_id`,`users`.`contact_number_id` AS `user_contact_number_id`,`users`.`role_id` AS `user_role_id`
		 	FROM `users` 
			INNER JOIN `email` ON `email`.`email_id` = `users`.`email_id`
			INNER JOIN `contact_number` ON `contact_number`.`contact_number_id`  = `users`.`contact_number_id`
			INNER JOIN `role` ON `role`.`role_id` = `users`.`role_id`
			WHERE `user_id` = '$user_id' ");
		return $query;
	}

	public function get_users(){
		$query = $this->db->query(" SELECT *,
			`users`.`email_id` AS `user_email_id`,`users`.`contact_number_id` AS `user_contact_number_id`,`users`.`role_id` AS `user_role_id`
			FROM `users` 
			INNER JOIN `email` ON `email`.`email_id` = `users`.`email_id`
			INNER JOIN `contact_number` ON `contact_number`.`contact_number_id`  = `users`.`contact_number_id`
			INNER JOIN `role` ON `role`.`role_id` = `users`.`role_id`
			WHERE  `users`.`is_active` = '1'  
			ORDER BY `users`.`first_name` ASC ");
		return $query;
	}

	public function delete_user($user_id){
		$this->db->query(" UPDATE `users` SET `is_active` = '0' WHERE `users`.`user_id` = '$user_id' ");
	}

	public function list_user_roles(){
		$query = $this->db->query(" SELECT * FROM `role` WHERE `role`.`is_active` = '1' ORDER BY `role`.`role_types` ASC ");
		return $query;
	}

	public function update_basic_details($first_name,$last_name,$role_id,$user_id){
		$query = $this->db->query(" UPDATE `users` 
			SET `first_name` = '$first_name', `last_name` = '$last_name', `role_id` = '$role_id' 
			WHERE `users`.`user_id` = '$user_id' ");
	}

	public function update_contact_numers($office_number,$office_ext,$mobile_number,$contact_number_id){
		$query = $this->db->query(" UPDATE `contact_number` 
			SET `office_number` = '$office_number', `office_ext` = '$office_ext', `mobile_number` = '$mobile_number' 
			WHERE `contact_number`.`contact_number_id` = '$contact_number_id' ");
	}

	public function update_email($general_email,$email_id){
		$query = $this->db->query(" UPDATE `email` SET `general_email` = '$general_email' WHERE `email`.`email_id` = '$email_id' ");
	}

	public function update_user_pwrd($user_id,$exp_date,$new_pwrd){
		$this->db->query(" UPDATE `users_pwd` SET `is_active` = '0' WHERE `users_pwd`.`user_id` = '$user_id' ");
		$this->db->query(" INSERT INTO `users_pwd` ( `user_id`, `date_expiry`, `pwd`) VALUES ( '$user_id', '$exp_date', '$new_pwrd') ");
		$this->db->query(" UPDATE `users` SET `password` = '$new_pwrd' WHERE `users`.`user_id` = '$user_id'  ");
	}


	public function update_user_name($user_id,$userName){
		$this->db->query(" UPDATE `users` SET `user_name` = '$userName' WHERE `users`.`user_id` = '$user_id'  ");
	}

	public function update_user_photo($user_id,$profile_photo){
		$this->db->query(" UPDATE `users` SET `profile_photo` = '$profile_photo' WHERE `users`.`user_id` = '$user_id' ");
	}

	public function insert_contact_numbers($office_number,$office_ext,$mobile_number){
		$this->db->query(" INSERT INTO `contact_number` (`office_number`, `office_ext`, `mobile_number`) VALUES ('$office_number', '$office_ext', '$mobile_number') ");
		return $this->db->insert_id();
	}

	public function insert_email($general_email){
		$this->db->query(" INSERT INTO `email` ( `general_email`) VALUES ( '$general_email') ");
		return $this->db->insert_id();
	}

	public function insert_new_user($user_name,$password,$first_name,$last_name,$profile_photo,$date_registered,$is_admin,$email_id,$contact_number_id,$role_id){
		$this->db->query(" INSERT INTO `users` ( `user_name`, `password`, `first_name`, `last_name`, `profile_photo`, `date_registered`, `is_admin`, `email_id`, `contact_number_id`, `user_login_status`, `role_id`)
			VALUES ('$user_name', '$password', '$first_name', '$last_name', '$profile_photo', '$date_registered', '$is_admin', '$email_id', '$contact_number_id', '0', '$role_id') ");
		return $this->db->insert_id();
	}

	public function insert_pword($user_id,$date_expiry,$pwd){
		$this->db->query(" INSERT INTO `users_pwd` ( `user_id`, `date_expiry`, `pwd`, `is_active`) VALUES ( '$user_id', '$date_expiry', '$pwd', '1') ");
		return $this->db->insert_id();
	}

	public function update_user_access($can_access,$can_control,$access_control_id){
		$this->db->query(" UPDATE `access_control` SET `can_access` = '$can_access', `can_control` = '$can_control' WHERE `access_control`.`access_control_id` = '$access_control_id' ");
	}

	public function insert_access($access_area_id,$can_access,$can_control,$user_id){
		$this->db->query("INSERT INTO `access_control` ( `access_area_id`, `can_access`, `can_control`, `user_id`) VALUES ( '$access_area_id', '$can_access', '$can_control', '$user_id') ");
	}

	public function update_admin_access($is_admin,$user_id){
		$this->db->query(" UPDATE `users` SET `is_admin` = '$is_admin' WHERE `users`.`user_id` = '$user_id' ");
	}
	
}