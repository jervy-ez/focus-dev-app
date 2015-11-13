<?php

class Bulletin_board_m extends CI_Model{

	public function insert_post($title, $post_details, $user_id, $date_posted, $expiry_date, $set_urgent ){
		$this->db->query("INSERT INTO `bulletin_board` (`title`, `post_details`, `user_id`, `date_posted`, `expiry_date`, `is_urgent`) VALUES ('$title', '$post_details', '$user_id', '$date_posted', '$expiry_date', '$set_urgent');");
		return $this->db->insert_id();	
	}

	public function list_post(){
		$query = $this->db->query("SELECT * FROM `bulletin_board` LEFT JOIN `users` ON `users`.`user_id` = `bulletin_board`.`user_id` WHERE `bulletin_board`.`is_active` = '1' ORDER BY `bulletin_board`.`bulletin_board_id` DESC");
		return $query;		
	}

	public function list_latest_post(){
		$query = $this->db->query("SELECT *,UNIX_TIMESTAMP( STR_TO_DATE(`bulletin_board`.`expiry_date`, '%d/%m/%Y') )  AS 'expiry_date_mod' FROM `bulletin_board` LEFT JOIN `users` ON `users`.`user_id` = `bulletin_board`.`user_id` WHERE `bulletin_board`.`is_active` = '1' ORDER BY `bulletin_board`.`bulletin_board_id` DESC");
		return $query;
	}

	public function delete_post($post_id){
		$query = $this->db->query("UPDATE `bulletin_board` SET `is_active` = '0' WHERE `bulletin_board`.`bulletin_board_id` = '$post_id'");
		return $query;
	}

	public function update_post($title,$post_details,$expiry_date,$is_urgent,$bulletin_board_id){
		$query = $this->db->query("UPDATE `bulletin_board` SET `title` = '$title', `post_details` = '$post_details', `expiry_date` = '$expiry_date', `is_urgent` = '$is_urgent' WHERE `bulletin_board`.`bulletin_board_id` = '$bulletin_board_id' ");
		return $query;
	}

	public function update_seen_post($bulletin_board_id,$seen_ids){
		$query = $this->db->query("UPDATE `bulletin_board` SET `seen_by_ids` = '$seen_ids' WHERE `bulletin_board`.`bulletin_board_id` = '$bulletin_board_id' ");
		return $query;		
	}
}