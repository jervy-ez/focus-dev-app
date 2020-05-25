<?php

class Dev_notes_m extends CI_Model{

	public function insert_post($dn_user_post,$dn_title, $dn_post_details, $dn_category, $dn_date_posted ,  $dn_date_commence, $dn_prgm_user_id,$dn_section_id, $dn_bugs  ){
		$this->db->query("INSERT INTO `dev_notes_tread` ( `dn_user_post`, `dn_title`, `dn_post_details`, `dn_category`, `dn_date_posted`, `dn_date_commence`, `dn_prgm_user_id`, `dn_section_id`, `is_bug_report` ) 
		VALUES ( '$dn_user_post,', '$dn_title','$dn_post_details', '$dn_category','$dn_date_posted' ,'$dn_date_commence', '$dn_prgm_user_id' , '$dn_section_id' , '$dn_bugs' ) ");
		return $this->db->insert_id();	
	}

	public function get_admins(){
		$query = $this->db->query("SELECT * FROM `users` WHERE `users`.`is_admin` = 'on' ");
		return $query;
	}

	public function list_programmes(){
		$query = $this->db->query("SELECT * FROM `users`  WHERE `users`.`role_id` = '13' AND `users`.`is_active` = '1' ");
		return $query;
	}

 

  
	public function list_post($is_bugs=''){
		$query = $this->db->query("SELECT  *,  UNIX_TIMESTAMP( STR_TO_DATE(`dev_notes_tread`.`dn_date_posted`, '%d/%m/%Y') ) AS `unix_posted` , CASE `dev_notes_tread`.`dn_category`
			WHEN 'Urgent' THEN 1
			WHEN 'Important' THEN 2
			WHEN 'When Time Permits' THEN 3
			WHEN 'Maybe' THEN 4
			END AS `priority_sort` 
            FROM `dev_notes_tread` 
			LEFT JOIN `dev_notes_section` ON `dev_notes_section`.`dn_section_id` = `dev_notes_tread`.`dn_section_id` 
			WHERE `dev_notes_tread`.`is_active` = '1'
			".($is_bugs != '' ? " AND `dev_notes_tread`.`is_bug_report`  = '1' " : " AND  `dev_notes_tread`.`is_bug_report`  = '0' ")."
			ORDER BY `priority_sort` ASC , `dev_notes_tread`.`dn_id` ASC");
		return $query;		
	}

	public function view_post_detail($post_id){
		$query = $this->db->query("SELECT  `dev_notes_tread`.*,  CONCAT(  `users`.`first_name` ,' ', `users`.`last_name`  ) AS `posted_by` 
			FROM `dev_notes_tread` LEFT JOIN `users` ON `users`.`user_id` = `dev_notes_tread`.`dn_user_post` WHERE `dev_notes_tread`.`dn_id` = '$post_id' AND `dev_notes_tread`.`is_active` = '1' ");
		return $query;
	}

	public function delete_post($post_id){
		$query = $this->db->query("UPDATE `dev_notes_tread` SET `is_active` = '0' WHERE `dev_notes_tread`.`dn_id` = '$post_id'");
		return $query;
	}

	public function delete_section($id){
		$query = $this->db->query("UPDATE`dev_notes_section` SET `is_active` = '0' WHERE `dev_notes_section`.`dn_section_id` = '$id'  ");
		return $query;
	}

	public function update_section($id,$section_label){
		$query = $this->db->query("UPDATE `dev_notes_section` SET `dn_section_label` = '$section_label' WHERE `dev_notes_section`.`dn_section_id` = '$id'  ");
		return $query;
	}


	public function post_comment($dn_post_date,$dn_post_user_id,$dn_tread_id,$dn_post_details){
		$query = $this->db->query("INSERT INTO `dev_notes` (`dn_post_date`, `dn_post_user_id`, `dn_tread_id`, `dn_post_details`) VALUES ('$dn_post_date','$dn_post_user_id','$dn_tread_id','$dn_post_details'  ) ");
		return $this->db->insert_id();	
		return $query;
	}

	public function list_comments($post_id){
		$query = $this->db->query(" SELECT * FROM `dev_notes` WHERE `dev_notes`.`is_active`  = '1' AND `dev_notes`.`dn_tread_id` = '$post_id' ORDER BY `dev_notes`.`dn_post_id` ASC ");
		return $query;
	}


	public function update_post($dn_title,$dn_post_details,$dn_category,$dn_date_commence,$dn_date_complete,$dn_prgm_user_id,$dn_id,$dn_section_id ){
		$query = $this->db->query("UPDATE `dev_notes_tread` SET `dn_title` = '$dn_title', `dn_post_details` = '$dn_post_details', `dn_category` = '$dn_category',`dn_section_id` = '$dn_section_id', `dn_date_commence` = '$dn_date_commence', `dn_date_complete` = '$dn_date_complete', `dn_prgm_user_id` = '$dn_prgm_user_id' WHERE `dev_notes_tread`.`dn_id` = '$dn_id' ");
		return $query;
	}

	public function fetch_sections(){
		$query = $this->db->query("SELECT * FROM `dev_notes_section` WHERE `dev_notes_section`.`is_active` = '1' ORDER BY `dev_notes_section`.`dn_section_label` ASC ");
		return $query;
	}

	public function insert_new_section($section){
		$query = $this->db->query("INSERT INTO `dev_notes_section` (  `dn_section_label` ) VALUES ( '$section' )");
		return $query;
	}
 
/*



	public function list_latest_post(){
		$query = $this->db->query("SELECT *,UNIX_TIMESTAMP( STR_TO_DATE(`bulletin_board`.`expiry_date`, '%d/%m/%Y') )  AS 'expiry_date_mod' FROM `bulletin_board` LEFT JOIN `users` ON `users`.`user_id` = `bulletin_board`.`user_id` WHERE `bulletin_board`.`is_active` = '1' ORDER BY `bulletin_board`.`bulletin_board_id` DESC");
		return $query;
	}


	public function update_seen_post($bulletin_board_id,$seen_ids){
		$query = $this->db->query("UPDATE `bulletin_board` SET `seen_by_ids` = '$seen_ids' WHERE `bulletin_board`.`bulletin_board_id` = '$bulletin_board_id' ");
		return $query;		
	}

	*/
}