<?php

class Signin_m extends CI_Model{

	function student_signin($student_code,$password_md5){
		$query = $this->db->query("SELECT * FROM student WHERE stud_code = '$student_code' AND stud_password = '$password_md5' ");
		return $query;
	}


	function get_user_event($email,$user_ip){
		$query = $this->db->query("SELECT * FROM auth_user
			LEFT JOIN auth_event ON auth_event.user_id = auth_user.id
			LEFT JOIN auth_membership ON auth_membership.user_id = auth_user.id
			LEFT JOIN auth_group ON auth_group.id = auth_membership.group_id
			WHERE auth_user.email = '$email'
			AND auth_event.client_ip = '$user_ip'
			ORDER BY auth_event.id DESC LIMIT 1");
		return $query;
	}


	
}