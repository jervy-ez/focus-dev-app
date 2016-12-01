<?php

class Shopping_center_m extends CI_Model{
	
	public function display_all_projects(){
		$query = $this->db->query("SELECT `project`.`project_id`, `project`.`project_name`, `company_details`.`company_name`, `project`.`job_category`, `project`.`project_status_id`, `project`.`job_date`, `project`.`budget_estimate_total`
			FROM  `project`,`company_details` 
			WHERE `company_details`.`company_id` = `project`.`client_id` 
			ORDER BY `project`.`project_id`  DESC");
		return $query;
	}

	public function select_particular_project($id){
		$query = $this->db->query("select * from project a left join company_details b on a.client_id = b.company_id where project_id = '$id'");
		return $query;
	}

	public function select_particular_supplier($id){
		$query = $this->db->query("SELECT * FROM `supplier_cat` WHERE `supplier_cat_id` = $id");
		return $query;
	}

	public function select_particular_sub_contractor($id){
		$query = $this->db->query("SELECT * FROM `job_sub_category` WHERE `job_sub_cat_id` = $id");
		return $query;
	}

	public function select_particular_contractor($id){
		$query = $this->db->query("SELECT * FROM `job_category` WHERE `job_category_id` = $id");
		return $query;
	}
	
	public function display_all_supplier_types(){
		$query = $this->db->query("SELECT * FROM `supplier_cat` ORDER BY `supplier_cat`.`supplier_cat_name` ASC");
		return $query;
	}

	public function removeWork($id){
		$this->db->query("DELETE FROM `considerations` WHERE `considerations`.`considerations_id` = '$id'");
		$this->db->query("DELETE FROM `attachments` WHERE `attachments`.`attachments_id` = '$id'");
		$this->db->query("DELETE FROM `works` WHERE `works`.`works_id` = '$id'");
	}
	
	public function display_all_job_category_type(){
		$query = $this->db->query("SELECT * FROM `job_category`");
		return $query;
	}
	
	public function display_all_job_sub_category_type($job_cat_id){
		$query = $this->db->query("SELECT * FROM `job_sub_category` WHERE `job_category_id` = '$job_cat_id'");
		return $query;
	}

	public function fectch_contact_person_by_company_id($company_id){
		$query = $this->db->query("SELECT `contact_person`.`contact_person_id`, `contact_person`.`first_name`, `contact_person`.`last_name`, `contact_person_company`.`is_primary`
			FROM `contact_person`
			LEFT JOIN  `contact_person_company` ON   `contact_person_company`.`contact_person_id` =  `contact_person`.`contact_person_id`
			WHERE `contact_person_company`.`company_id` = '$company_id'");
		return $query;
	}
	
	#use left join!!!!!!!!!!!!!!!!!!!!
	public function fetch_project_details($data){
		if(isset($data)){
			$query = $this->db->query("SELECT * from `project` WHERE `project`.`project_id` = '$data'");
			return $query;
		}else{
			$query = $this->db->query("SELECT * FROM  `project` ORDER BY  `project`.`project_id` DESC");
			return $query;
		}
	}

	public function fetch_complete_project_details($project_id){
		$query = $this->db->query("SELECT * from `project`
			LEFT JOIN `contact_person` ON `contact_person`.`contact_person_id` =  `project`.`primary_contact_person_id`
			LEFT JOIN `users` ON `users`.`user_id` =  `project`.`focus_user_id`
			LEFT JOIN `notes` ON `notes`.`notes_id` = `project`.`notes_id`
			LEFT JOIN `project_status` ON `project_status`.`project_status_id` = `project`.`project_status_id`
			WHERE `project`.`project_id` = '$project_id'");
		return $query;
	}

	public function fetch_project_notes($notes_id){
		$query = $this->db->query("SELECT * FROM `notes` WHERE `notes`.`notes_id` = '$notes_id' ");
		return $query;
	}

	public function display_all_works($project_id){
		$query = $this->db->query("SELECT * FROM `works` WHERE `works`.`project_id`='$project_id' ORDER BY `works`.`works_id` DESC");
		return $query;
	}

	public function display_job_subcategory(){
		$query = $this->db->query("SELECT * FROM `job_sub_category` ORDER BY `job_sub_category`.`job_sub_cat` ASC ");
		return $query;
	}


	public function fetch_shopping_center_details($shopping_center_id=''){

		if($shopping_center_id!=''){
			$query = $this->db->query("SELECT `shopping_center`.*,`address_detail`.*,`address_general`.* ,`states`.*
				FROM `shopping_center`
				LEFT JOIN `address_detail` ON `address_detail`.`address_detail_id` =  `shopping_center`.`detail_address_id`
				LEFT JOIN `address_general` ON `address_general`.`general_address_id` =  `address_detail`.`general_address_id`
				LEFT JOIN `states` ON `states`.`id` =  `address_general`.`state_id`
				WHERE `shopping_center`.`shopping_center_id` = '$shopping_center_id' ");

		}else{
			$query = $this->db->query("SELECT `shopping_center`.*,`address_detail`.*,`address_general`.* ,`states`.*
				FROM `shopping_center`
				LEFT JOIN `address_detail` ON `address_detail`.`address_detail_id` =  `shopping_center`.`detail_address_id`
				LEFT JOIN `address_general` ON `address_general`.`general_address_id` =  `address_detail`.`general_address_id`
				LEFT JOIN `states` ON `states`.`id` =  `address_general`.`state_id` 
				ORDER BY `shopping_center`.`shopping_center_brand_name` ASC ");
		}

		return $query;
	}
	
	public function insert_new_shopping_center($shopping_center_brand_name,$common_name='',$detail_address_id){
		$this->db->query("INSERT INTO `shopping_center` (`shopping_center_brand_name`,`common_name`,`detail_address_id`) VALUES ( '$shopping_center_brand_name','$common_name','$detail_address_id')");
		$project_id = $this->db->insert_id();
		return $project_id;
		#inserts a new project and returns a project_id		
	}

	public function update_shopping_center($shopping_center_id,$shopping_center_brand_name,$common_name='',$detail_address_id){
		$this->db->query("UPDATE `shopping_center` SET `shopping_center_brand_name` = '$shopping_center_brand_name', `common_name` = '$common_name' ,`detail_address_id` = '$detail_address_id' WHERE `shopping_center`.`shopping_center_id` = '$shopping_center_id' ");
	}

	public function delete_shopping_center($shopping_center_id){
		$this->db->query("DELETE FROM `shopping_center` WHERE `shopping_center`.`shopping_center_id` = '$shopping_center_id' ");
	}

	public function find_like_shopping_center_brand($shopping_center_brand_name){
		$query = $this->db->query("SELECT * FROM `shopping_center` 
			LEFT JOIN `address_detail` ON `address_detail`.`address_detail_id` = `shopping_center`.`detail_address_id` 
			WHERE `shopping_center`.`shopping_center_brand_name` = '$shopping_center_brand_name' ");
		return $query;
	}

	public function find_like_shopping_center_common($common_name){
		$query = $this->db->query("SELECT * FROM `shopping_center` 
			LEFT JOIN `address_detail` ON `address_detail`.`address_detail_id` = `shopping_center`.`detail_address_id` 
			WHERE `shopping_center`.`common_name` = '$common_name' ");
		return $query;
	}


	

}