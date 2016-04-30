<?php

class Functions_m extends CI_Model{
	
	public function test(){
		$query = $this->db->query("SELECT * FROM room");
		return $query;	
	}

	public function signin($user_id,$password,$type){

		if($type == 'students'){
			$query = $this->db->query("SELECT * FROM student WHERE student.stud_num	= '$user_id' AND student.stud_password= '$password' ");
		}else{
			//$query = $this->db->query("SELECT * FROM room");
		}

		return $query->num_rows;
	}

	public function check_my_ip($ip_address,$email){
		$query = $this->db->query("SELECT * FROM auth_event
			LEFT JOIN auth_user ON auth_user.id = auth_event.user_id
			LEFT JOIN signatories ON signatories.id = auth_user.signatory_id
			WHERE auth_event.client_ip = '$ip_address' AND auth_user.email = '$email'
			ORDER BY auth_event.id DESC
			");
		return $query;
	}

	public function check_user_is_regstrar($user_id){
		$query = $this->db->query("SELECT * FROM auth_user
			LEFT JOIN auth_membership ON auth_membership.user_id = auth_user.id
			LEFT JOIN auth_group ON auth_group.id = auth_membership.group_id
			WHERE auth_user.id = '$user_id'");
		return $query;
	}

	public function get_user_data($user_id){
		$query = $this->db->query("SELECT * FROM auth_user WHERE id = '$user_id' ");
		return $query;
	}


	public function logout_user($id,$description){
		$query = $this->db->query("UPDATE auth_event SET description = '$description' WHERE  id = '$id' ");
		return $query;
	}
}