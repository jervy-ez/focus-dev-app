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
		$query = $this->db->query("SELECT * FROM `job_sub_category` WHERE `job_sub_cat_id` = $id");
		return $query;
	}

	public function select_particular_contractor($id){
		$query = $this->db->query("SELECT * FROM `job_category` WHERE `job_category_id` = $id");
		return $query;
	}

	public function select_shopping_center($id){
		$query = $this->db->query("SELECT * FROM `shopping_center` WHERE `shopping_center`.`detail_address_id` = '$id' ");
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

	public function fetch_shopping_center_by_state($state){
		$query = $this->db->query("SELECT DISTINCT * FROM `shopping_center`
			LEFT JOIN `address_detail` ON `address_detail`.`address_detail_id` = `shopping_center`.`detail_address_id`
			LEFT JOIN `address_general` ON `address_general`.`general_address_id` = `address_detail`.`general_address_id`
			WHERE `address_general`.`state_id` = '$state' GROUP BY `shopping_center`.`shopping_center_brand_name` ");
		return $query;
	}

	public function update_vr_inv_date($invoice_date_req,$set_invoice_date,$project_id ){
		$this->db->query("UPDATE `invoice` SET `invoice_date_req` = '$invoice_date_req', `set_invoice_date` = '$set_invoice_date' WHERE `invoice`.`label` = 'VR' AND `invoice`.`project_id` = '$project_id'");
	}

	public function fetch_shopping_center_by_name_and_sate($shopping_center_brand_name,$state_id){
		$query = $this->db->query("SELECT DISTINCT * FROM `shopping_center`
			LEFT JOIN `address_detail` ON `address_detail`.`address_detail_id` = `shopping_center`.`detail_address_id`
			LEFT JOIN `address_general` ON `address_general`.`general_address_id` = `address_detail`.`general_address_id`
			WHERE `shopping_center`.`shopping_center_brand_name` = '$shopping_center_brand_name' AND `address_general`.`state_id` = '$state_id' ");
		return $query;
	}

	public function fetch_complete_project_details($project_id){
		$query = $this->db->query("SELECT * from `project`
			LEFT JOIN `contact_person` ON `contact_person`.`contact_person_id` =  `project`.`primary_contact_person_id`
			LEFT JOIN `users` ON `users`.`user_id` =  `project`.`focus_user_id`
			LEFT JOIN `notes` ON `notes`.`notes_id` = `project`.`notes_id`
			LEFT JOIN `project_status` ON `project_status`.`project_status_id` = `project`.`project_status_id`
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id`
			WHERE `project`.`project_id` = '$project_id' AND `project`.is_active = '1'  ");
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

	public function display_supplier_category(){
		$query = $this->db->query("SELECT * FROM `supplier_cat` ORDER BY `supplier_cat`.`supplier_cat_name` ASC");
		return $query;
	}

	public function fetch_mark_up_by_type($type,$markup_id){

		if($type == 'Kiosk'){
			$query = $this->db->query("SELECT `kiosk`,`min_kiosk` FROM `markup` WHERE `markup_id`='$markup_id'");
			return $query;
		}elseif($type == 'Full Fitout'){
			$query = $this->db->query("SELECT `full_fitout`,`min_full_fitout` FROM `markup` WHERE `markup_id`='$markup_id'");
			return $query;
		}elseif($type == 'Refurbishment'){
			$query = $this->db->query("SELECT `refurbishment`,`min_refurbishment` FROM `markup` WHERE `markup_id`='$markup_id'");
			return $query;
		}elseif($type == 'Strip Out'){
			$query = $this->db->query("SELECT `stripout`,`min_stripout` FROM `markup` WHERE `markup_id`='$markup_id'");
			return $query;
		}elseif($type == 'Minor Works'){
			$query = $this->db->query("SELECT `minor_works`,`min_minor_works` FROM `markup` WHERE `markup_id`='$markup_id'");
			return $query;
		}elseif($type == 'Maintenance'){
			$query = $this->db->query("SELECT `maintenance`,`min_maintenance` FROM `markup` WHERE `markup_id`='$markup_id'");
			return $query;

		}else{
			return false;
		}

	}
	
	public function insert_new_project($project_name, $project_date, $primary_contact_person_id, $budget_estimate_total, $job_date, $is_wip, $client_po, $date_site_commencement, $date_site_finish, $job_category, $job_type, $focus_user_id ,$focus_company_id, $project_manager_id, $project_admin_id, $project_estiamator_id, $address_id, $invoice_address_id, $notes_id, $markup,$project_status_id, $client_id, $install_time_hrs, $project_area, $is_double_time, $labour_hrs_estimate, $shop_tenancy_number,$defaults_id){

		$this->db->query("INSERT INTO `project` (`project_name`, `project_date`, `primary_contact_person_id`, `budget_estimate_total`, `job_date`, `is_wip`, `client_po`, `date_site_commencement`, `date_site_finish`, `job_category`, `job_type`, `focus_user_id`,`focus_company_id`, `project_manager_id`, `project_admin_id`, `project_estiamator_id`, `address_id`, `invoice_address_id`, `notes_id`, `markup`,`project_status_id`, `client_id`, `install_time_hrs`, `project_area`, `is_double_time`, `labour_hrs_estimate`, `shop_tenancy_number` , `defaults_id`) 
			VALUES ('$project_name', '$project_date', '$primary_contact_person_id', '$budget_estimate_total', '$job_date', '$is_wip', '$client_po', '$date_site_commencement', '$date_site_finish', '$job_category', '$job_type','$focus_user_id' ,'$focus_company_id', '$project_manager_id', '$project_admin_id', '$project_estiamator_id', '$address_id', '$invoice_address_id', '$notes_id', '$markup', '$project_status_id', '$client_id', '$install_time_hrs', '$project_area', '$is_double_time', '$labour_hrs_estimate', '$shop_tenancy_number', '$defaults_id')");
		return $this->db->insert_id();
		#inserts a new project and returns a project_id		
	}

	public function fetch_shopping_center($shopping_center_name_brand=''){

		if($shopping_center_name_brand==''){
			$query = $this->db->query("SELECT * FROM `shopping_center` GROUP BY `shopping_center`.`shopping_center_brand_name` ASC");

		}else{
			$query = $this->db->query("SELECT `shopping_center`.*, `shopping_center`.`detail_address_id` as `shopping_center_detail_id` ,`address_detail`.* ,`address_general`.*
				FROM `shopping_center` 
				LEFT JOIN `address_detail` ON `address_detail`.`address_detail_id` = `shopping_center`.`detail_address_id` 
				LEFT JOIN `address_general` ON `address_general`.`general_address_id` = `address_detail`.`general_address_id`
				WHERE `shopping_center`.`shopping_center_brand_name` = '$shopping_center_name_brand' ");
		}
		return $query;
	}


	public function project_details_quick_update($project_id,$project_name,$budget_estimate_total,$job_date,$client_po,$install_time_hrs,$project_markup,$site_start,$site_finish){
		$this->db->query("UPDATE `project` SET `project_name` = '$project_name', `budget_estimate_total` = '$budget_estimate_total', `job_date` = '$job_date', `client_po` = '$client_po', `install_time_hrs` = '$install_time_hrs', `markup` = '$project_markup',`date_site_commencement`='$site_start',`date_site_finish`='$site_finish'
		 WHERE `project`.`project_id` = '$project_id' ");
		$this->db->flush_cache();
	}

	public function set_wip_project($project_id,$is_wip){
		$this->db->query("UPDATE `project` SET `project`.`is_wip` = '$is_wip' WHERE `project`.`project_id` = '$project_id'");
		return 1;
	}

	public function delete_project_details($project_id){
		$this->db->query("UPDATE `project` SET `is_active` = '0' WHERE `project`.`project_id` = '$project_id'");		
	}

	public function update_full_project_details($project_id,$project_name,$client_id,$contact_person_id,$client_po,$job_type,$job_category,$job_date,$site_start,$site_finish,$is_wip,$install_hrs,$is_double_time,$project_total,$labour_hrs_estimate,$project_markup,$project_area,$project_manager_id,$project_admin_id,$project_estiamator_id,$shop_tenancy_number,$site_address_id,$shop_tenancy_number,$site_address_id,$invoice_address_id,$focus_id){
		$this->db->query("UPDATE `project` SET `project_name` = '$project_name', `client_id` = '$client_id', `primary_contact_person_id` = '$contact_person_id', `client_po` = '$client_po', `job_type` = '$job_type', `job_category` = '$job_category', `job_date` = '$job_date', `date_site_commencement` = '$site_start', `date_site_finish` = '$site_finish', `is_wip` = '$is_wip', `shop_tenancy_number` = '$shop_tenancy_number', `address_id` = '$site_address_id',`focus_company_id` = '$focus_id', `invoice_address_id` = '$invoice_address_id', `install_time_hrs` = '$install_hrs', `is_double_time` = '$is_double_time', `budget_estimate_total` = '$project_total', `labour_hrs_estimate` = '$labour_hrs_estimate', `markup` = '$project_markup', `project_area` = '$project_area', `project_manager_id` = '$project_manager_id', `project_admin_id` = '$project_admin_id', `project_estiamator_id` = '$project_estiamator_id'
			WHERE `project`.`project_id` = '$project_id' ");

	}

	public function add_project_comment($project_id,$date_posted,$project_comments,$user_id){
		$this->db->query("INSERT INTO `project_comments` (`project_id`, `date_posted`, `project_comments`, `user_id`) VALUES ('$project_id', '$date_posted', '$project_comments', '$user_id')");		
		$project_id = $this->db->insert_id();
		return $project_id;
	}

	public function list_project_comments($project_id){
		$query = $this->db->query("SELECT * FROM `project_comments` WHERE `project_comments`.`project_id` = '$project_id' ORDER BY `project_comments`.`project_comments_id` DESC");
		return $query;
	}

	public function get_project_cost_total($project_id){
		$query = $this->db->query("SELECT * FROM `project_cost_total` WHERE `project_cost_total`.`project_id` = '$project_id' ");
		return $query;
	}

	public function insert_cost_total($project_id,$install_cost_total){
		$this->db->query("INSERT INTO `project_cost_total` (`project_id`,`install_cost_total`) VALUES ('$project_id','$install_cost_total')");		
		$cost_total_id = $this->db->insert_id();
		return $cost_total_id;
	}

	public function update_install_cost_total($project_id,$install_cost_total){
		$this->db->query("UPDATE `project_cost_total` SET `install_cost_total` = '$install_cost_total' WHERE `project_cost_total`.`project_id` = '$project_id' ");
	}

	public function update_project_cost_total($proj_id,$work_price_total,$work_estimated_total,$work_quoted_total){
		$this->db->query("UPDATE `project_cost_total` SET `work_price_total` = '$work_price_total', `work_estimated_total` = '$work_estimated_total',`work_quoted_total` = '$work_quoted_total' WHERE `project_id` = '$proj_id'");	
	}

	public function update_project_total($project_id,$project_total){
		$this->db->query("UPDATE `project` SET `project_total` = '$project_total' WHERE `project`.`project_id` = '$project_id' ");
	}

	public function get_list_shopping_centers($state_id,$suburb){
		$query = $this->db->query("SELECT * FROM `address_general` 
			LEFT JOIN `address_detail` ON  `address_detail`.`general_address_id` = `address_general`.`general_address_id`
			LEFT JOIN `shopping_center` ON `shopping_center`.`detail_address_id` = `address_detail`.`address_detail_id`
			WHERE `address_general`.`state_id` = '$state_id' AND `address_general`.`suburb` = '$suburb' AND `shopping_center`.`shopping_center_brand_name` IS NOT NULL
			ORDER BY `shopping_center`.`shopping_center_brand_name` ASC");
		return $query;
	}

	public function insert_dropbox_shared_link($project_id,$dropbox_link){
		$this->db->query("UPDATE `project` SET `dropbox_share_link` = '$dropbox_link' WHERE `project_id` = '$project_id'");	
	}

	public function get_project_dropbox_link($project_id){
		$query = $this->db->query("SELECT `dropbox_share_link` from `project` WHERE `project_id` = '$project_id'");	
		$dropbox_link = "";
		foreach ($query->result_array() as $row){
			$dropbox_link = $row['dropbox_share_link'];
		}
		return $dropbox_link;
	}

	public function has_attachment($project_id){
		$query = $this->db->query("SELECT * from `project_attachments` WHERE `project_id` = '$project_id'");	
		if($query->num_rows == 0){
			return 0;
		}else{
			return 1;
		}

	}
}