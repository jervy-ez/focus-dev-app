<?php

class Projects_m extends CI_Model{
	
	public function display_all_projects(){
		$query = $this->db->query("SELECT `project`.`project_id`, `project`.`project_total`,`project`.`project_name`, `project_cost_total`.`work_estimated_total`, `project`.`install_time_hrs`,`company_details`.`company_name`,`company_details`.`company_id`, `project`.`job_category`, `project`.`project_status_id`, `project`.`job_date`, `project`.`budget_estimate_total`,`project`.`project_manager_id`,`project`.`project_admin_id`,`project`.`project_estiamator_id`,`project`.`is_wip`, `project`.`is_paid`
			FROM  `project`,`company_details`,`project_cost_total` 
			WHERE `company_details`.`company_id` = `project`.`client_id` AND `project`.is_active = '1' AND `project_cost_total`.`project_id` = `project`.`project_id`
			ORDER BY `project`.`project_id`  DESC");
		return $query;
	}

	public function select_particular_project($id){
		$query = $this->db->query("select a.*, b.*, a.address_id as site_add  from project a left join company_details b on a.client_id = b.company_id where project_id = '$id'");
		return $query;
	}

	public function select_particular_supplier($id){
		$query = $this->db->query("SELECT * FROM `supplier_cat` WHERE `supplier_cat_id` = $id");
		return $query;
	}

	public function select_particular_sub_contractor($id){
		$query = $this->db->query("SELECT * FROM `job_sub_cat                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    `.`first_name`, `contact_person`.`last_name`, `contact_person_company`.`is_primary`
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

	public function fetch_shopping_center_by_state($state){
		$query = $this->db->query("SELECT DISTINCT * FROM `shopping_center`
			LEFT JOIN `address_detail` ON `address_detail`.`address_detail_id` = `shopping_center`.`detail_address_id`
			LEFT JOIN `address_general` ON `address_general`.`general_address_id` = `address_detail`.`general_address_id`
			WHERE `address_general`.`state_id` = '$state' GROUP BY `shopping_center`.`shopping_center_brand_name` ");
		return $query;
	}

	public function fetch_shopping_center_by_name_and_sate($shopping_center_brand_name,$state_id){
		$query = $this->db->query("SELECT DISTINCT * FROM `shopping_center`
			LEFT JOIN `address_detail` ON `address_detail`.`address_d