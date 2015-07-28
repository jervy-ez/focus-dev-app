<?php

class User_model extends CI_Model{	
	
	function __construct(){
		parent::__construct();
	}
	
	function validate($user_name, $password, $ip_add = 0){
		$query = $this->db->query("SELECT * FROM `users` WHERE `login_name` = '$user_name' AND `password` = '$password'");
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

	public function fetch_all_departments(){
		$query = $this->db->query("SELECT * FROM `department` ORDER BY `department`.`department_name` ASC");
		return $query;
	}

	public function fetch_all_roles(){
		$query = $this->db->query("SELECT * FROM `role` ORDER BY `role`.`role_types` ASC");
		return $query;
	}

	public function fetch_all_access($user_id){
		$query = $this->db->query("SELECT * FROM `user_access` WHERE `user_access`.`user_id` = '$user_id' ORDER BY `user_id` ASC");
		return $query;
	}

	public function fetch_admin_defaults(){
		$query = $this->db->query("SELECT * FROM `admin_defaults`");
		return $query;
	}

	public function add_new_user($login_name,$password,$user_first_name,$user_last_name,$user_gender,$user_department_id,$user_profile_photo,$user_timestamp_registered,$user_role_id,$user_email_id,$user_skype,$user_skype_password,$user_contact_number_id,$user_focus_company_id,$user_date_of_birth,$user_comments_id,$admin){
		$this->db->query("INSERT INTO `users` (`login_name`, `password`, `user_first_name`, `user_last_name`, `user_gender`, `user_department_id`, `user_profile_photo`, `user_timestamp_registered`, `user_role_id`, `user_email_id`, `user_skype`,`user_skype_password`, `user_contact_number_id`, `user_focus_company_id`, `user_date_of_birth`, `user_comments_id`,`if_admin`)
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
		$query = $this->db->query("UPDATE `users` SET `login_name` = '$login_name', `user_first_name` = '$user_first_name', `user_last_name` = '$user_last_name',`user_profile_photo`='$profile' ,`user_role_id` = '$user_role_id',`user_comments_id` = '$user_comments_id', `user_skype` = '$user_skype',`user_skype_password` = '$user_skype_password',`user_focus_company_id`='$user_focus_company_id', `user_gender`='$user_gender',`user_date_of_birth`='$user_date_of_birth',`user_department_id` = '$department_id', `if_admin` = '$admin'  WHERE `users`.`user_id` = '$user_id' ");
	}

	public function change_user_password($new_password,$user_id){
		$query = $this->db->query("UPDATE `users` SET `password` = '$new_password' WHERE `users`.`user_id` = '$user_id' ");
	}

	public function fetch_user($user_id=''){
		if($user_id != ''){			
			$query = $this->db->query("SELECT `users`.*,`department`.`department_id`,`department`.`department_name`,`role`.`role_id`,`role`.`role_types`,`email`.`email_id`,`email`.`general_email`,`contact_number`.*,`company_details`.`company_id`,`company_details`.`company_name`,`notes`.`comments`
				FROM `users` 
				LEFT JOIN `department` ON `department`.`department_id` =`users`.`user_department_id`
				LEFT JOIN `role` ON `role`.`role_id` = `users`.`user_role_id`
				LEFT JOIN `email` ON `email`.`email_id` = `users`.`user_email_id`
				LEFT JOIN `contact_number` ON `contact_number`.`contact_number_id` = `users`.`user_contact_number_id`
				LEFT JOIN `company_details` ON `company_details`.`company_id` = `users`.`user_focus_company_id`
				LEFT JOIN `notes` ON `notes`.`notes_id` = `users`.`user_comments_id`
				WHERE  `users`.`user_id` = '$user_id' ");
		}else{
			$query = $this->db->query("SELECT `users`.*,`department`.`department_id`,`department`.`department_name`,`role`.`role_types`,`email`.`general_email`,`contact_number`.*,`company_details`.`company_name`,`notes`.`comments`
				FROM `users` 
				LEFT JOIN `department` ON `department`.`department_id` =`users`.`user_department_id`
				LEFT JOIN `role` ON `role`.`role_id` = `users`.`user_role_id`
				LEFT JOIN `email` ON `email`.`email_id` = `users`.`user_email_id`
				LEFT JOIN `contact_number` ON `contact_number`.`contact_number_id` = `users`.`user_contact_number_id`
				LEFT JOIN `company_details` ON `company_details`.`company_id` = `users`.`user_focus_company_id`
				LEFT JOIN `notes` ON `notes`.`notes_id` = `users`.`user_comments_id`
				WHERE `users`.`is_active` = '1' ");
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

	public function fetch_login_user(){
		$query = $this->db->query("SELECT * FROM `users` WHERE `users`.`user_login_status` = 1");
		return $query;
	}

	public function insert_user_min_log($user_id,$date_log,$time_log){
		$this->db->query("UPDATE `users` SET `users`.`date_log` = '$date_log', `users`.`time_log` = '$time_log' WHERE `users`.`user_id` = '$user_id'");
	}
}

?>