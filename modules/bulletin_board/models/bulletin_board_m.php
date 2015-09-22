<?php

class Bulletin_board_m extends CI_Model{

	public function insert_post($title, $post_details, $user_id, $date_posted, $expiry_date){
		$this->db->query("INSERT INTO `bulletin_board` (`title`, `post_details`, `user_id`, `date_posted`, `expiry_date`) VALUES ('$title', '$post_details', '$user_id', '$date_posted', '$expiry_date');");
		return $this->db->insert_id();	
	}

	public function list_post(){
		$query = $this->db->query("SELECT * FROM `bulletin_board` LEFT JOIN `users` ON `users`.`user_id` = `bulletin_board`.`user_id` ORDER BY `bulletin_board`.`bulletin_board_id` DESC");
		return $query;		
	}

	public function list_latest_post(){
		$query = $this->db->query("SELECT *,UNIX_TIMESTAMP( STR_TO_DATE(`bulletin_board`.`expiry_date`, '%d/%m/%Y') )  AS 'expiry_date_mod' FROM `bulletin_board` ORDER BY `bulletin_board`.`bulletin_board_id` DESC");
		return $query;
	}

}