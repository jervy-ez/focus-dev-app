<?php

class Projects_m extends CI_Model{
	
	public function display_all_projects($extra='',$extra_order='',$extra_join=''){
		$query = $this->db->query("SELECT `project`.`project_id`, `project`.`project_total`,`project`.`project_name`,`project`.`client_id`,

			UNIX_TIMESTAMP( STR_TO_DATE(`project`.`review_date`, '%d/%m/%Y') ) AS `unix_review_date`,
			UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_commencement`, '%d/%m/%Y') ) AS `unix_start_date`

			, `project`.`quote_deadline_date`, `project_cost_total`.`work_estimated_total`,`project_cost_total`.`variation_total`, `project`.`install_time_hrs`,`project`.`unaccepted_date`,`project`.`date_site_commencement`,`company_details`.`company_name`,`company_details`.`company_id`, `project`.`job_category`, `project`.`project_status_id`, `project`.`job_date`, `project`.`budget_estimate_total`,`project`.`project_manager_id`,`project`.`project_admin_id`,`project`.`project_estiamator_id`,`project`.`is_wip`, `project`.`is_paid`, `project`.date_site_finish, `project`.warranty_date,
			UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) AS `unix_date_site_finish`,
			`company_details_temp`.`company_name` AS `pending_comp_name`,
			`project`.`is_pending_client`
			FROM  `project`

			LEFT JOIN `company_details` ON `company_details`.`company_id` = `project`.`client_id`
			LEFT JOIN `company_details_temp` ON `company_details_temp`.`company_details_temp_id` = `project`.`client_id`
			INNER JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id` 

			".$extra_join."

			WHERE `project`.is_active = '1'  
			".($extra != '' ? " $extra " : "")." 

			".($extra_order != '' ? " $extra_order " : " ORDER BY `project`.`project_id`  DESC ")." ");
		return $query;
	}

	public function select_particular_project($id){
		$query = $this->db->query("SELECT a.*, b.*, 
									c.company_name AS pending_comp_name,
									concat(c.contact_person_fname,' ',c.contact_person_sname) AS pending_cont_person,
									c.contact_number AS pending_cont_number,
									c.email AS pending_cont_email, a.address_id as site_add  
									FROM project a 
										LEFT JOIN company_details b on b.company_id  = a.client_id
										LEFT JOIN company_details_temp c ON c.company_details_temp_id = a.client_id
									WHERE project_id = '$id'
								");
		return $query;
	}

	public function list_doc_type($doc_type='0'){
		$query = $this->db->query("SELECT * FROM `storage_doc_type` WHERE `storage_doc_type`.`is_active` = '1' 
			AND  `storage_doc_type`.`doc_type_area` = '$doc_type' 
			ORDER BY `storage_doc_type`.`doc_type_name` ASC ");
		return $query;
	}

	public function insert_uploaded_file($file_name,$file_type,$project_id,$client_id,$date_upload,$user_id,$will_replace_existing){
		if($will_replace_existing == 1):
			$is_authorized = 0;
		else:
			$is_authorized = 1;
		endif;
		$query = $this->db->query("INSERT INTO `storage_files` ( `file_name`, `file_type`, `project_id`, `client_id`, `date_upload`, `user_id`,`is_authorized`,`will_replace_existing`) VALUES ( '$file_name', '$file_type', $project_id, $client_id, '$date_upload', '$user_id','$is_authorized','$will_replace_existing') ");
		

		if($will_replace_existing == 1):
			$is_authorized = 0;
			$storage_files_id = $this->db->insert_id();
			$this->db->query("UPDATE `storage_files` set `replace_by_storage_files_id` = '$storage_files_id' where `for_replacement` = 1 and project_id = '$project_id' and `replace_by_storage_files_id` = 1");

		endif;

		return $query;
	}

	public function remove_uploaded_file($file_id){
		$query = $this->db->query("UPDATE `storage_files` SET `is_active` = '0' WHERE `storage_files`.`storage_files_id` = '$file_id' ");
		return $query;		
	}

	public function remove_uploaded_file_cqr($filename){
		$filename = '%'.$filename.'%';
		$query = $this->db->query("UPDATE `storage_files` SET `is_active` = '0' WHERE `storage_files`.`file_name` like '$filename'");
		return $query;		
	}

	public function remove_doc_type($type_id){
		$query = $this->db->query(" UPDATE `storage_doc_type` SET `is_active` = '0' WHERE `storage_doc_type`.`storage_doc_type_id` = '$type_id' ");
		return $query;
	}

	public function update_type_name($doc_type_name,$type_id){
		$query = $this->db->query(" UPDATE `storage_doc_type` SET `doc_type_name` = '$doc_type_name' WHERE `storage_doc_type`.`storage_doc_type_id` = '$type_id' ");
		return $query;
	}

	public function list_year_uploads(){
		$query = $this->db->query(" SELECT SUBSTRING(`storage_files`.`date_upload`,-4) AS `year_list`
			FROM `storage_files` WHERE `storage_files`.`is_active` = '1' 
			GROUP BY SUBSTRING(`storage_files`.`date_upload`,-4)  
			ORDER BY `year_list`  DESC ");
		return $query;
	}

	public function list_projects_by_client($current_year='',$last_year=''){
		$query = $this->db->query("SELECT `storage_files`.`storage_files_id`, `storage_files`.`client_id` ,`company_details`.`company_name` ,`storage_files`.`file_name`,`storage_files`.`date_upload`,  `storage_doc_type`.`doc_type_name`, `users`.`user_first_name`
			
FROM `storage_files`  
INNER JOIN  `storage_doc_type` ON `storage_doc_type`.`storage_doc_type_id` =  `storage_files`.`file_type`
		 

INNER JOIN `company_details` ON `company_details`.`company_id` = `storage_files`.`client_id`
        
			INNER JOIN 	`users` ON `users`.`user_id` =  `storage_files`.`user_id`

			WHERE `storage_files`.`is_active` = '1'  AND  `company_details`.`active` = '1' 
			
			AND UNIX_TIMESTAMP( STR_TO_DATE(`storage_files`.`date_upload`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('01/01/$last_year', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`storage_files`.`date_upload`, '%d/%m/%Y') ) <  UNIX_TIMESTAMP( STR_TO_DATE('31/12/$current_year', '%d/%m/%Y') )
			
			
ORDER BY  `storage_files`.`client_id`  DESC, `storage_doc_type`.`doc_type_name` ASC, UNIX_TIMESTAMP( STR_TO_DATE(`storage_files`.`date_upload`, '%d/%m/%Y') ) DESC ");
		return $query;

	}

	public function list_projects_by_job_date($current_year='',$last_year=''){
		$query = $this->db->query(" SELECT `storage_files`.`storage_files_id`,`storage_files`.`project_id`,`project`.`project_name` ,`storage_files`.`file_name`,`storage_files`.`date_upload`,  `storage_doc_type`.`doc_type_name`, `users`.`user_first_name`, `storage_files`.`is_project_attachment`,`storage_files`.`is_authorized`,`storage_files`.`is_updated` 
			FROM `storage_files`  
			INNER JOIN  `storage_doc_type` ON `storage_doc_type`.`storage_doc_type_id` =  `storage_files`.`file_type`
			INNER JOIN  `project` ON `project`.`project_id` =  `storage_files`.`project_id`
			INNER JOIN 	`users` ON `users`.`user_id` =  `storage_files`.`user_id`

			WHERE `storage_files`.`is_active` = '1'  AND  `project`.`is_active` = '1' 
			
			AND UNIX_TIMESTAMP( STR_TO_DATE(`storage_files`.`date_upload`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('01/01/$last_year', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`storage_files`.`date_upload`, '%d/%m/%Y') ) <  UNIX_TIMESTAMP( STR_TO_DATE('31/12/$current_year', '%d/%m/%Y') )
			
			
			ORDER BY  `storage_files`.`project_id`  DESC, `storage_doc_type`.`doc_type_name` ASC, UNIX_TIMESTAMP( STR_TO_DATE(`storage_files`.`date_upload`, '%d/%m/%Y') ) DESC ");
		return $query;
	}

	public function list_uploaded_files($proj_id){
		$query = $this->db->query(" SELECT   `storage_files`.`storage_files_id`,`storage_files`.`project_id`,`project`.`project_name` ,`storage_files`.`file_name`,`storage_files`.`date_upload`,  `storage_doc_type`.`doc_type_name`, `users`.`user_first_name`,`storage_files`.`is_project_attachment`,`storage_files`.`is_authorized`,`storage_files`.`is_updated`, `storage_files`.`will_replace_existing`, `storage_files`.`for_replacement` 

			FROM `storage_files` 
			INNER JOIN  `storage_doc_type` ON `storage_doc_type`.`storage_doc_type_id` =  `storage_files`.`file_type`
			INNER JOIN  `project` ON `project`.`project_id` =  `storage_files`.`project_id`
			INNER JOIN 	`users` ON `users`.`user_id` =  `storage_files`.`user_id`

			WHERE `storage_files`.`project_id` = '$proj_id' 
			AND `storage_files`.`is_active` = '1' 
		

			ORDER BY  `storage_files`.`project_id`  DESC, `storage_doc_type`.`doc_type_name` ASC, `storage_files`.`file_name` ASC ");


		return $query;
	}

	public function insert_doc_type($type_name,$doc_type){
		$query = $this->db->query("INSERT INTO `storage_doc_type` ( `doc_type_name`,  `doc_type_area`, `is_active`) VALUES ( '$type_name', '$doc_type', '1')");
		return $query;
	}

	public function fetch_user_pa_assignment($pa_id){
		$query = $this->db->query("SELECT * FROM `project_administrator_manager` WHERE `project_administrator_manager`.`project_administrator_id` = '$pa_id' ");
		return $query;
	}
	

	public function fetch_pa_pm_assignment($pa_id){
		$query = $this->db->query("SELECT * FROM `project_administrator_manager` WHERE `project_administrator_manager`.`project_administrator_id` = '$pa_id' ");
		return $query;
	}

	public function count_remain_un_nvoice($project_id){
		$query = $this->db->query(" SELECT * FROM `invoice` WHERE `invoice`.`project_id` = '$project_id' AND `invoice`.`label` != 'VR' AND `invoice`.`is_invoiced` != '1' ");
		return $query->num_rows();
	}

	public function list_focus_company_main($custom=''){
		$query = $this->db->query("SELECT `project`.`focus_company_id` FROM `project` WHERE `project`.`is_active` = '1' AND `project`.`job_category` != 'Company' AND `project`.`job_type` != 'Company'  
			".$custom." 
			GROUP BY `project`.`focus_company_id`");
		return $query;
	}

	public function list_pm_wiprp(){

		$query = $this->db->query("SELECT  `project`.`project_manager_id`, CONCAT(  `users`.`user_first_name`,' ' ,`users`.`user_last_name`) AS `name_log`
			FROM  `project`
			LEFT JOIN `users` ON `users`.`user_id` =   `project`.`project_manager_id`
			WHERE `project`.is_active = '1'  
			AND `project`.`job_date` != '' AND `project`.`is_paid` ='0'  AND `project`.`job_type` != 'Company'  AND `project`.`project_manager_id` != '20'
			GROUP BY  `project`.`project_manager_id`  
			ORDER BY `name_log` ASC ");
		return $query;
	}



	public function fetch_removed_jobdates_prj_csv_report(){
		$query = $this->db->query("SELECT `user_log`.`user_log_id`, `user_log`.`date`,`user_log`.`time`, `user_log`.`actions`, CONCAT(  `users`.`user_first_name`,' ' ,`users`.`user_last_name`) AS `name_log` , `project`.`project_id`, `project`.`project_name` ,`company_details`.`company_name` AS `client_name` ,`project`.`date_site_commencement`,`project`.`date_site_finish`, `project`.`project_total`,`project_cost_total`.`variation_total`, CONCAT(  `pm_data`.`user_first_name`,' ' ,`pm_data`.`user_last_name`) AS `pm_name` 
			FROM `project`
			INNER JOIN(

				SELECT MAX(`user_log`.`user_log_id`) AS `latest_log_id` , `user_log`.`project_id` AS `log_project_id`
				FROM `user_log`
				WHERE `user_log`.`actions` LIKE '%Removed job date%'
				GROUP BY `user_log`.`project_id` 

			) `logs` ON `logs`.`log_project_id` = `project`.`project_id` 

		LEFT JOIN `user_log` ON `user_log`.`user_log_id` = `logs`.`latest_log_id`
		LEFT JOIN `users` ON `users`.`user_id` = `user_log`.`user_id` 
        LEFT JOIN `company_details` ON `company_details`.`company_id` =  `project`.`client_id`
        LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id`
        LEFT JOIN `users` `pm_data` ON `pm_data`.`user_id` = `project`.`project_manager_id` 

		WHERE `project`.`job_date` = ''
		AND `project`.`is_active` = '1' 

		ORDER BY `logs`.`latest_log_id` DESC");
		return $query;
	}

	public function fetch_recent_pr_images($limit=''){
		$query = $this->db->query("SELECT t1.`project_id`, t2.`project_name`, t2.`project_manager_id`, CONCAT(t3.`user_first_name`, ' ', t3.`user_last_name`) AS pm_name, t2.`project_admin_id`, CONCAT(t4.`user_first_name`, ' ', t4.`user_last_name`) AS pa_name
								FROM `progress_report_images` AS t1
								LEFT JOIN `project` AS t2
								ON t1.`project_id` = t2.`project_id`
								LEFT JOIN `users` AS t3
								ON t2.`project_manager_id` = t3.`user_id`
								LEFT JOIN `users` AS t4
								ON t2.`project_admin_id` = t4.`user_id`
								WHERE t1.`is_active` = 1 AND t2.`is_paid` = 0
								GROUP BY t1.`project_id`
								ORDER BY t1.`project_id` DESC
								".($limit != '' ? " LIMIT 0,$limit " : "")." ");
		return $query;
	}

	public function fetch_removed_jobdates_prj($limit=''){
		$query = $this->db->query("SELECT `user_log`.*, CONCAT(  `users`.`user_first_name`,' ' ,`users`.`user_last_name`) AS `user_name_log` , `project`.`project_name` ,`company_details`.`company_name`,`project`.`date_site_commencement`,`project`.`date_site_finish`, `project`.`project_total`,`project_cost_total`.`variation_total`, CONCAT(  `pm_data`.`user_first_name`,' ' ,`pm_data`.`user_last_name`) AS `pm_name` 
			FROM `project`
			INNER JOIN(

				SELECT MAX(`user_log`.`user_log_id`) AS `latest_log_id` , `user_log`.`project_id` AS `log_project_id`
				FROM `user_log`
				WHERE `user_log`.`actions` LIKE '%Removed job date%'
				GROUP BY `user_log`.`project_id` 

			) `logs` ON `logs`.`log_project_id` = `project`.`project_id` 

		LEFT JOIN `user_log` ON `user_log`.`user_log_id` = `logs`.`latest_log_id`
		LEFT JOIN `users` ON `users`.`user_id` = `user_log`.`user_id` 
        LEFT JOIN `company_details` ON `company_details`.`company_id` =  `project`.`client_id`
        LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id`
        LEFT JOIN `users` `pm_data` ON `pm_data`.`user_id` = `project`.`project_manager_id` 

		WHERE `project`.`job_date` = ''
		AND `project`.`is_active` = '1' 

		ORDER BY `logs`.`latest_log_id` DESC
			".($limit != '' ? " LIMIT 0,$limit " : "")." ");
		return $query;
	}


	public function fetch_users_remove_job_date(){
		$query = $this->db->query("
			SELECT `user_log`.*, CONCAT(  `users`.`user_first_name`,' ' ,`users`.`user_last_name`) AS `user_name_log` ,   `users`.`user_first_name`
			FROM `project`
			INNER JOIN(

				SELECT MAX(`user_log`.`user_log_id`) AS `latest_log_id` , `user_log`.`project_id` AS `log_project_id`
				FROM `user_log`
				WHERE `user_log`.`actions` LIKE '%Removed job date%'
				GROUP BY `user_log`.`project_id` 

				) `logs` ON `logs`.`log_project_id` = `project`.`project_id` 

		LEFT JOIN `user_log` ON `user_log`.`user_log_id` = `logs`.`latest_log_id`
		LEFT JOIN `users` ON `users`.`user_id` = `user_log`.`user_id` 
		LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id` 
		WHERE `project`.`job_date` = '' AND `project`.`is_active` = '1' 
		GROUP BY  `users`.`user_first_name`
		ORDER BY  `users`.`user_first_name` ASC");
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

	public function fetch_job_date_history($id){
		$query = $this->db->query(" SELECT  `user_log`.`actions` FROM `user_log` WHERE `user_log`.`project_id` = '$id' AND `user_log`.`actions` LIKE '%Removed job date%' ORDER BY `user_log`.`user_log_id` DESC LIMIT 1 ");
		return $query;
	}

	public function check_log_jobdate($project_id){
		$query = $this->db->query("SELECT * FROM `user_log` WHERE `user_log`.`actions` LIKE '%Removed job date%' AND `user_log`.`project_id` = '$project_id' ORDER BY `user_log`.`user_log_id` DESC LIMIT 1");
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
			LEFT JOIN `brand` ON `brand`.`brand_id` = `project`.`brand_id`
			LEFT JOIN `project_status` ON `project_status`.`project_status_id` = `project`.`project_status_id`
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id`
			LEFT JOIN `company_details_temp` ON `company_details_temp`.`company_details_temp_id` = `project`.`client_id`
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
		}elseif($type == 'Design Works'){
			$query = $this->db->query("SELECT `design_works`,`min_design_works` FROM `markup` WHERE `markup_id`='$markup_id'");
			return $query;
		}elseif($type == 'Joinery Only'){
			$query = $this->db->query("SELECT `joinery_only`,`min_joinery_only` FROM `markup` WHERE `markup_id`='$markup_id'");
			return $query;
		}else{
			return false;
		}

	}

	public function fetch_sub_client($id){
		$query = $this->db->query(" SELECT * FROM `company_details` WHERE `company_details`.`sub_client_id` = '$id' AND `company_details`.`active` = '1' ");
		return $query;
	}

	public function add_brand($brand_name){
		$query = $this->db->query("SELECT * from brand where brand_name = '$brand_name'");
		if($query->num_rows == 0){
			$this->db->query(" INSERT INTO `brand` ( `brand_name` ) VALUES ( '$brand_name' )");
		}
	}

	public function delete_brand($id){
		$query = $this->db->query("UPDATE  `brand` SET `brand`.`is_active` = '0' WHERE `brand`.`brand_id` = '$id' ");
		return $query;
	}


	public function update_brand($brand_name,$id){
		$query = $this->db->query("UPDATE  `brand` SET `brand`.`brand_name` = '$brand_name' WHERE `brand`.`brand_id` = '$id' ");
		return $query;
	}

	public function fetch_brands(){
		$query = $this->db->query("SELECT * FROM `brand` WHERE `brand`.`is_active` = '1' ORDER BY `brand`.`brand_name` ASC");
		return $query;
	}

	public function get_project_rev_data($project_id, $deadline){
		$query = $this->db->query(" SELECT * FROM `project_wip_review` WHERE `project_wip_review`.`current_dead_line` = '13/02/2019' AND `project_wip_review`.`project_id` = '35022' ");
		return $query;
	}

	public function set_project_date_review($project_id,$date_rev){
		$query = $this->db->query("UPDATE  `project` SET `project`.`review_date` = '$date_rev' WHERE `project`.`project_id` = '$project_id' ");
		return $query;		
	}

	public function insert_wip_rvw($project_id, $current_dead_line, $date_set){
		$this->db->query("INSERT INTO `project_wip_review` (`project_id`, `current_dead_line`, `date_set`) VALUES ('$project_id', '$current_dead_line', '$date_set')");
		return $this->db->insert_id();
	}

	public function update_set_wip_rvw($project_id,$current_dead_line,$date_set){
		$query = $this->db->query("UPDATE `project_wip_review` SET `project_wip_review`.`date_set` = '$date_set' WHERE `project_wip_review`.`project_id` = '$project_id' AND `project_wip_review`.`current_dead_line` = '$current_dead_line' ");
		return $query;
	}

	public function prj_rvw_late($project_id,$current_dead_line ){
	$query = $this->db->query("UPDATE `project_wip_review` SET `project_wip_review`.`is_revw_late` = '1' WHERE `project_wip_review`.`project_id` = '$project_id' AND `project_wip_review`.`current_dead_line` = '$current_dead_line' ");
		return $query;
	}	

	public function get_prj_rvw($current_dead_line,$project_id){
		$query = $this->db->query("SELECT * FROM `project_wip_review` WHERE `project_wip_review`.`current_dead_line` = '$current_dead_line' AND  `project_wip_review`.`project_id` = '$project_id' ");
		return $query;
	}

	public function insert_new_project($project_name, $project_date, $primary_contact_person_id, $budget_estimate_total, $job_date, $brand,$is_wip, $client_po, $date_site_commencement, $date_site_finish, $job_category, $job_type, $focus_user_id ,$focus_company_id, $project_manager_id, $project_admin_id, $project_estiamator_id, $address_id, $invoice_address_id, $notes_id, $markup,$project_status_id, $client_id, $install_time_hrs, $project_area, $is_double_time, $labour_hrs_estimate, $shop_tenancy_number,$defaults_id,$cc_pm,$quote_deadline_date,$proj_joinery_user,$client_type){

		$this->db->query("INSERT INTO `project` (`project_name`, `project_date`, `primary_contact_person_id`, `budget_estimate_total`, `job_date`, `brand_id`, `is_wip`, `client_po`, `date_site_commencement`, `date_site_finish`, `job_category`, `job_type`, `focus_user_id`,`focus_company_id`, `project_manager_id`, `project_admin_id`, `project_estiamator_id`, `address_id`, `invoice_address_id`, `notes_id`, `markup`,`project_status_id`, `client_id` , `install_time_hrs`, `project_area`, `is_double_time`, `labour_hrs_estimate`, `shop_tenancy_number` , `defaults_id`, `client_contact_person_id`, `quote_deadline_date`,`joinery_selected_sender`,`review_date`,`is_pending_client`) 
			VALUES ('$project_name', '$project_date', '$primary_contact_person_id', '$budget_estimate_total', '$job_date','$brand', '$is_wip', '$client_po', '$date_site_commencement', '$date_site_finish', '$job_category', '$job_type','$focus_user_id' ,'$focus_company_id', '$project_manager_id', '$project_admin_id', '$project_estiamator_id', '$address_id', '$invoice_address_id', '$notes_id', '$markup', '$project_status_id', '$client_id','$install_time_hrs', '$project_area', '$is_double_time', '$labour_hrs_estimate', '$shop_tenancy_number', '$defaults_id', '$cc_pm', '$quote_deadline_date', '$proj_joinery_user','$project_date','$client_type')");
		return $this->db->insert_id();
		#inserts a new project and returns a project_id		
	}

	public function insert_project_site_contact($project_id, $contact_person, $contact_person_number, $contact_mobile, $contact_email){

		$this->db->query("INSERT INTO `project_site_contacts` (`project_id`,`contact_person_name`, `contact_person_number`, `contact_person_mobile`, `contact_person_email`) 
			VALUES ('$project_id', '$contact_person', '$contact_person_number', '$contact_mobile', '$contact_email')");
		#inserts a new project and returns a project_id		
	}

	public function update_project_site_contact($project_id, $contact_person, $contact_person_number, $contact_mobile, $contact_email){

		$this->db->query("UPDATE `project_site_contacts` SET `contact_person_name` = '$contact_person', `contact_person_number`= '$contact_person_number', `contact_person_mobile` = '$contact_mobile', `contact_person_email` = '$contact_email' 
			WHERE `project_id` = '$project_id'");
		#inserts a new project and returns a project_id		
	}

	public function fetch_project_site_contact($project_id){
		$query = $this->db->query("SELECT * FROM `project_site_contacts` WHERE `project_id` = '$project_id'");
		return $query;
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


	public function project_details_quick_update($project_id,$project_name,$budget_estimate_total,$job_date,$quote_deadline_date,$client_po,$install_time_hrs,$project_markup,$site_start,$site_finish,$unaccepted_date,$rev_date){
		$query = $this->db->query("SELECT * from `project` where `project_id` = '$project_id'");
		foreach ($query->result_array() as $row){
			$site_hours = $row['install_time_hrs'];	
			$start_date = $row['date_site_commencement'];	
			$finish_date = $row['date_site_finish'];	
		}	

		if($site_hours !== $install_time_hrs || $start_date !==  $site_start || $finish_date !== $site_finish){
			$this->db->query("DELETE from project_labour_sched where `project_id` = '$project_id'");
		}

		$this->db->query("UPDATE project_schedule set site_start_date = '$site_start', site_finish_date = '$site_finish' where `project_id` = '$project_id'");

		$this->db->query("UPDATE `project` SET `project_name` = '$project_name', `budget_estimate_total` = '$budget_estimate_total',`quote_deadline_date` = '$quote_deadline_date', `job_date` = '$job_date', `review_date` = '$rev_date', `client_po` = '$client_po', `install_time_hrs` = '$install_time_hrs', `markup` = '$project_markup',`date_site_commencement`='$site_start',`date_site_finish`='$site_finish',`unaccepted_date` = '$unaccepted_date'
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

	public function update_full_project_details($project_id,$project_name,$client_id,$contact_person_id,$client_po,$job_type,$brand,$job_category,$job_date,$site_start,$site_finish,$is_wip,$install_hrs,$is_double_time,$project_total,$labour_hrs_estimate,$project_markup,$project_area,$project_manager_id,$project_admin_id,$project_estiamator_id,$shop_tenancy_number,$site_address_id,$shop_tenancy_number,$site_address_id,$invoice_address_id,$focus_id,$cc_pm,$proj_joinery_user,$rev_date,$client_type){
		$query = $this->db->query("SELECT * from `project` where `project_id` = '$project_id'");
		foreach ($query->result_array() as $row){
			$site_hours = $row['install_time_hrs'];
			$start_date = $row['date_site_commencement'];
			$finish_date = $row['date_site_finish'];
		}

		if($site_hours !== $install_hrs || $start_date !==  $site_start || $finish_date !== $site_finish){
			$this->db->query("DELETE from project_labour_sched where `project_id` = '$project_id'");
		}

		$this->db->query("UPDATE project_schedule set site_start_date = '$site_start', site_finish_date = '$site_finish' where `project_id` = '$project_id'");
		
		$this->db->query("UPDATE `project` SET `project_name` = '$project_name', `client_id` = '$client_id',  `primary_contact_person_id` = '$contact_person_id', `client_po` = '$client_po', `job_type` = '$job_type', `brand_id` = '$brand', `job_category` = '$job_category', `job_date` = '$job_date', `date_site_commencement` = '$site_start', `date_site_finish` = '$site_finish', `is_wip` = '$is_wip', `shop_tenancy_number` = '$shop_tenancy_number', `address_id` = '$site_address_id', `review_date` = '$rev_date',`focus_company_id` = '$focus_id', `invoice_address_id` = '$invoice_address_id', `install_time_hrs` = '$install_hrs', `is_double_time` = '$is_double_time', `budget_estimate_total` = '$project_total', `labour_hrs_estimate` = '$labour_hrs_estimate', `markup` = '$project_markup', `project_area` = '$project_area', `project_manager_id` = '$project_manager_id', `project_admin_id` = '$project_admin_id', `project_estiamator_id` = '$project_estiamator_id', `client_contact_person_id` = '$cc_pm', `joinery_selected_sender` = '$proj_joinery_user', `is_pending_client`= '$client_type'
			WHERE `project`.`project_id` = '$project_id' ");

	}

	public function add_project_comment($project_id,$date_posted,$project_comments,$user_id,$is_project_comments=1){
		$this->db->query("INSERT INTO `project_comments` (`project_id`, `date_posted`, `project_comments`, `user_id`, `is_project_comments` ) VALUES ('$project_id', '$date_posted', '$project_comments', '$user_id', '$is_project_comments')");		
		$project_id = $this->db->insert_id();
		return $project_id;
	}

	public function delete_comment($comment_id){
		$this->db->query(" UPDATE `project_comments` SET `project_comments`.`is_active` = '0' WHERE `project_comments`.`project_comments_id` = '$comment_id'");
	}

	public function list_project_comments($project_id,$is_project_comments=1,$is_active=''){
		$query = $this->db->query("SELECT * FROM `project_comments` WHERE `project_comments`.`project_id` = '$project_id' 

			".($is_project_comments == 1 ? "  " : " AND `project_comments`.`is_project_comments`='$is_project_comments' ")."
			".($is_active == 1 ? "  AND  `project_comments`.`is_active` = '1'   " : "  ")."



			ORDER BY `project_comments`.`project_comments_id` DESC");
		return $query;
	}


	public function duplicate_address_row($address_detail_id){

		$this->db->query(" INSERT INTO `address_detail` (`unit_number`,`unit_level`,`street`,`po_box`,`general_address_id`)
			SELECT `unit_number`,`unit_level`,`street`,`po_box`,`general_address_id`
			FROM `address_detail` WHERE `address_detail`.`address_detail_id` = '$address_detail_id' ");		
		$new_add_id = $this->db->insert_id();
		return $new_add_id;
	}

	public function set_shop_name($project_id,$shop_name){
		$this->db->query(" UPDATE `project` SET `shop_name` = '$shop_name' WHERE `project`.`project_id` = '$project_id' ");
	}

	public function add_invoice_comment($project_id,$comment,$include_invoice_comments){
		$this->db->query("UPDATE `project` SET `invoice_comments` = '$comment', `project`.`include_invoice_comments` = '$include_invoice_comments' WHERE `project`.`project_id` = '$project_id' ");
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
		// $query = $this->db->query("SELECT * from `project_attachments` WHERE `project_id` = '$project_id'");	
		$query = $this->db->query("SELECT * FROM `storage_files`
									WHERE `is_project_attachment` = 1
										AND `project_id` = '$project_id'");	

		if($query->num_rows == 0){
			return 0;
		}else{
			return 1;
		}

	}

	public function has_unaccepted_date($project_id){
		$query = $this->db->query("SELECT * from `project` WHERE `project_id` = '$project_id' and unaccepted_date = ''");	
		if($query->num_rows == 0){
			return 1;
		}else{
			return 0;
		}

	}

	public function insert_unaccepted_date($project_id,$unaccepted_date){
		$this->db->query("UPDATE `project` set `unaccepted_date` = '$unaccepted_date' WHERE `project_id` = '$project_id'");	
	}

	public function remove_unaccepted_date($project_id){
		$this->db->query("UPDATE `project` set `unaccepted_date` = '' WHERE `project_id` = '$project_id'");	
	}

	public function fetch_progress_report_defaults(){
		$query = $this->db->query("SELECT `progress_report_categories` FROM `admin_defaults` WHERE `admin_default_id` = '4'");	
		return $query;
	}

	public function get_progress_report_images($project_id){
		$query = $this->db->query("SELECT * FROM `progress_report_images` as t1 LEFT JOIN `progress_report_group` as t2 ON `t1`.group_id = `t2`.group_id WHERE `t1`.`project_id` = '$project_id' AND `t1`.`is_active` = '1' ORDER BY `t1`.`group_id`");
		return $query;
	}

	public function get_progress_report_images_all($project_id){
		$query = $this->db->query("SELECT * FROM `progress_report_images` as t1 LEFT JOIN `progress_report_group` as t2 ON `t1`.group_id = `t2`.group_id WHERE `t1`.`project_id` = '$project_id' ORDER BY `t1`.`group_id`");
		return $query;
	}

	public function get_progress_report_images_select($project_id){
		$query = $this->db->query("SELECT * FROM `progress_report_images` WHERE `project_id` = '$project_id' AND `progress_report_images`.`is_select` = '1' AND `progress_report_images`.`is_active` = '1' ORDER BY `group_id`");
		return $query;
	}

	public function get_progress_report_images_select_group($project_id){
		$query = $this->db->query("SELECT `group_id` FROM `progress_report_images` WHERE `project_id` = '$project_id' AND `progress_report_images`.`is_select` = '1' AND `progress_report_images`.`is_active` = '1' ORDER BY `group_id`");
		return $query;
	}

	public function fetch_label_image($image_name){
		$query = $this->db->query("SELECT * FROM `progress_report_images` WHERE image_path LIKE '%$image_name'");
		return $query;
	}

	public function edit_label_image($image_label, $image_name){
		$query = $this->db->query("UPDATE `progress_report_images` SET image_label = '$image_label' WHERE image_path LIKE '%$image_name'");
	}

	public function add_leading_hand_from_pr($leading_hand_id, $project_id){
		$query = $this->db->query("UPDATE `project` SET project_leading_hand_id = '$leading_hand_id' WHERE `project_id` = '$project_id'");
	}

	public function get_progress_report_details($project_id){
		$query = $this->db->query("SELECT * FROM `progress_report_details` WHERE `project_id` = '$project_id' AND `sent` = '0' ORDER BY `pr_version` DESC ");
		return $query;
	}

	public function get_service_report_details($project_id){
		$query = $this->db->query("SELECT * FROM `service_report_details` WHERE `project_id` = '$project_id'");
		return $query;
	}

	public function update_pr_details($scope_of_work, $project_id, $pr_version){
		$query = $this->db->query("UPDATE `progress_report_details` SET `scope_of_work` = '$scope_of_work' WHERE `project_id` = '$project_id' AND `pr_version` = '$pr_version'");
	}

	public function update_sr_details($project_id, $site_inspection_details, $completion_details){
		$query = $this->db->query("UPDATE `service_report_details` SET `site_inspection_details` = '$site_inspection_details', `completion_details` = '$completion_details' WHERE `project_id` = '$project_id'");
		return $query;
	}

	public function delete_pr_image($image_id, $project_id){
		$query = $this->db->query("UPDATE `progress_report_images` SET `is_active` = '0', `is_select` = '0' WHERE `progress_report_images_id` = '$image_id' AND `project_id` = '$project_id'");
	}

	public function delete_sr_image($image_id, $project_id){
		$query = $this->db->query("UPDATE `service_report_images` SET `is_active` = '0', `is_select` = '0' WHERE `id` = '$image_id' AND `project_id` = '$project_id'");
	}

	public function check_if_selected($image_id, $project_id){
		$query = $this->db->query("SELECT * FROM `progress_report_images` WHERE `progress_report_images_id` = '$image_id' AND `project_id` = '$project_id'");
		return $query;
	}

	public function set_select_image($image_id, $project_id, $is_selected){

		if ($is_selected == '1'){
			$this->db->query("UPDATE `progress_report_images` SET `is_select` = '0' WHERE `progress_report_images_id` = '$image_id' AND `project_id` = '$project_id'");
		} else {
			$this->db->query("UPDATE `progress_report_images` SET `is_select` = '1' WHERE `progress_report_images_id` = '$image_id' AND `project_id` = '$project_id'");
		}
	}

	public function set_edited_image($image_name, $project_id, $param){
		$this->db->query("UPDATE `progress_report_images` SET `is_edited` = '$param' WHERE image_path LIKE '%$image_name' AND `project_id` = '$project_id'");
	}

	public function check_pr_details($project_id){
		$query = $this->db->query("SELECT * FROM `progress_report_details` WHERE `project_id` = '$project_id' ORDER BY `pr_version` DESC");
		return $query;
	}

	public function check_sr_details($project_id){
		$query = $this->db->query("SELECT * FROM `service_report_details` WHERE `project_id` = '$project_id'");
		return $query;
	}

	public function insert_blank_pr_details($project_id, $pr_version){
		$this->db->query("INSERT INTO `progress_report_details`(`project_id`, `scope_of_work`, `pr_version`) VALUES ('$project_id','','$pr_version')");
	}

	public function insert_blank_sr_details($project_id){
		$this->db->query("INSERT INTO `service_report_details`(`project_id`, `site_inspection_details`, `completion_details`) VALUES ('$project_id','','')");
	}

	public function insert_sr_details($project_id, $site_inspection_details, $completion_details){
		$query = $this->db->query("INSERT INTO `service_report_details`(`project_id`, `site_inspection_details`, `completion_details`) VALUES ('$project_id','$site_inspection_details','$completion_details')");
		return $query;
	}

	public function latest_pr_version($project_id){
		$query = $this->db->query("SELECT * FROM `progress_report_details` WHERE `project_id` = '$project_id' ORDER BY `pr_version` DESC ");
		return $query;
	}

	public function set_image_orientation($image_orientation, $image_name){

		// $image_orientation == '1' is portrait
		if ($image_orientation == '1'){
			$query = $this->db->query("UPDATE `progress_report_images` SET `image_orientation` = 1 WHERE image_path LIKE '%$image_name'");
		} else {
			$query = $this->db->query("UPDATE `progress_report_images` SET `image_orientation` = 0 WHERE image_path LIKE '%$image_name'");
		}
	}
	
	public function get_project_sched_values($project_id){
		$query = $this->db->query("SELECT * FROM `project_schedule` WHERE `project_id` = $project_id");
		return $query;
	}

	public function get_project_sched_for_pr($project_id){
		$query = $this->db->query("SELECT * FROM `project_schedule` WHERE `project_id` = '$project_id'");
		return $query;
	}

	public function insert_new_project_sched_for_pr($project_id, $leading_hand_id){
		$this->db->query("INSERT INTO `project_schedule`(`project_id`, `contruction_manager_id`, `leading_hand_id`, `site_start_date`, `site_finish_date`) VALUES ('$project_id','0','$leading_hand_id','0','0')");	
		$new_project_sched_id = $this->db->insert_id();
		return $new_project_sched_id;
	}

	public function update_project_sched_for_pr_lead($project_id, $leading_hand_id){
		$this->db->query("UPDATE `project_schedule` SET `leading_hand_id` = '$leading_hand_id' WHERE `project_id` = '$project_id'");
	}

	public function update_project_sched_for_pr_const($project_id, $const_mngr){
		$this->db->query("UPDATE `project_schedule` SET `contruction_manager_id` = '$const_mngr' WHERE `project_id` = '$project_id'");
	}

	public function insert_manual_lead($proj_sched_id, $name_leading_hand, $mobile_no_leading_hand){
		$this->db->query("INSERT INTO `manual_entry_project_schedule`(`project_schedule_id`, `lh_name`, `lh_contact`) VALUES ('$proj_sched_id', '$name_leading_hand', '$mobile_no_leading_hand')");
	}

	public function insert_manual_const($proj_sched_id, $name_const_mngr, $mobile_no_const_mngr){
		$this->db->query("INSERT INTO `manual_entry_project_schedule`(`project_schedule_id`, `cm_name`, `cm_contact`) VALUES ('$proj_sched_id', '$name_const_mngr', '$mobile_no_const_mngr')");
	}

	public function update_manual_lead($proj_sched_id, $name_leading_hand, $mobile_no_leading_hand){
		$this->db->query("UPDATE `manual_entry_project_schedule` SET `lh_name` = '$name_leading_hand', `lh_contact` = '$mobile_no_leading_hand' WHERE `project_schedule_id` = '$proj_sched_id'");
	}

	public function update_manual_const($proj_sched_id, $name_const_mngr, $mobile_no_const_mngr){
		$this->db->query("UPDATE `manual_entry_project_schedule` SET `cm_name` = '$name_const_mngr', `cm_contact` = '$mobile_no_const_mngr' WHERE `project_schedule_id` = '$proj_sched_id'");
	}

	public function get_manual_const($proj_sched_id){
		$query = $this->db->query("SELECT * FROM `manual_entry_project_schedule` WHERE `project_schedule_id` = '$proj_sched_id'");
		return $query;
	}

	public function set_pr_images_inactive($project_id, $pr_version){
		$query = $this->db->query("UPDATE `progress_report_images` SET `pr_version` = '$pr_version', `is_active` = '0' WHERE `project_id` = '$project_id'");
	}

	public function set_pr_details_sent($project_id, $pr_version, $current_date){
		$query = $this->db->query("UPDATE `progress_report_details` SET `sent` = '1', `date_sent` = '$current_date' WHERE `project_id` = '$project_id' AND `pr_version` = '$pr_version'");
	}

	public function get_project_id_admin($user_id){
		$query = $this->db->query("SELECT `t1`.`project_id`, `t2`.`project_manager_id`, `t2`.`project_admin_id` FROM `progress_report_images` as t1 LEFT JOIN `project` as t2 ON `t1`.`project_id` = `t2`.`project_id` WHERE `t1`.`is_viewed` = '0' GROUP BY `t1`.`project_id`");
		
		return $query;
	}

	public function get_project_id_pa($user_id){
		$query = $this->db->query("SELECT `t1`.`project_id`, `t2`.`project_manager_id`, `t2`.`project_admin_id` FROM `progress_report_images` as t1 LEFT JOIN `project` as t2 ON `t1`.`project_id` = `t2`.`project_id` WHERE `t1`.`is_viewed` = '0' AND `t2`.`project_admin_id` = '$user_id' GROUP BY `t1`.`project_id`");
		
		return $query;
	}

	public function get_project_id_pm($user_id){
		$query = $this->db->query("SELECT `t1`.`project_id`, `t2`.`project_manager_id`, `t2`.`project_admin_id` FROM `progress_report_images` as t1 LEFT JOIN `project` as t2 ON `t1`.`project_id` = `t2`.`project_id` WHERE `t1`.`is_viewed` = '0' AND `t2`.`project_manager_id` = '$user_id' GROUP BY `t1`.`project_id`");
		
		return $query;
	}

	public function set_pr_to_viewed($project_id){
		$query = $this->db->query("UPDATE `progress_report_images` SET `is_viewed` = '1' WHERE `project_id` = '$project_id'");
	}

	public function get_group_for_img(){
		$query = $this->db->query("SELECT * FROM `progress_report_group` ORDER BY `progress_report_group`.`group_id` ASC");
		return $query;
	}

	public function get_progress_report_images_by_name($image_name, $project_id){
		$query = $this->db->query("SELECT * FROM `progress_report_images` as t1 LEFT JOIN `progress_report_group` as t2 ON `t1`.group_id = `t2`.group_id WHERE `t1`.`project_id` = '$project_id' AND `image_path` LIKE '%$image_name' AND `t1`.`is_active` = '1'");
		return $query;
	}

	public function update_group_id($project_id, $image_name, $selected_group_id){
		$query = $this->db->query("UPDATE `progress_report_images` SET `group_id` = '$selected_group_id' WHERE `project_id` = '$project_id' AND `image_path` LIKE '%$image_name'");
	}

	public function update_group_id2($project_id, $image_name, $selected_group_id){
		$query = $this->db->query("UPDATE `service_report_images` SET `group_id` = '$selected_group_id' WHERE `project_id` = '$project_id' AND `image_path` LIKE '%$image_name'");
	}

	public function add_group($new_group){
		$query = $this->db->query("INSERT INTO `progress_report_group`(`description`) VALUES ('$new_group')");
	}

	public function fetch_all_pr_version($project_id){
		$query = $this->db->query("SELECT * FROM `progress_report_details` WHERE `project_id` = '$project_id' AND `sent` = '1' ORDER BY `pr_version` DESC");
		return $query;
	}

	public function insert_pr_images($project_id,$image_path){
		$this->db->query("INSERT INTO `progress_report_images` (`project_id`, `image_path`) VALUES ('$project_id', '$image_path')");
	}

	public function fetch_pa($selected_pm){
		$query = $this->db->query("SELECT t1.`project_administrator_id`, t2.`is_active` FROM `project_administrator_manager` AS t1 LEFT JOIN `users` AS t2 ON t1.`project_administrator_id` = t2.`user_id` WHERE t1.`project_manager_primary_id` = '$selected_pm' AND t2.`is_active` = 1");

		return $query;
	}

	public function update_warranty_date($warranty_date, $project_id){
		$query = $this->db->query("UPDATE `project` SET warranty_date = '$warranty_date' WHERE project_id = '$project_id'");
	}

	public function fetch_warranty_categories(){
		$query = $this->db->query("SELECT `warranty_categories` FROM `admin_defaults` WHERE `admin_default_id` = '4'");	
		return $query;
	}

	public function delete_all_images($project_id){
		$query = $this->db->query("UPDATE `progress_report_images` SET `is_active` = '0', `is_select` = '0' WHERE `project_id` = '$project_id'");
	}

	public function select_all_images($project_id){
		$query = $this->db->query("UPDATE `progress_report_images` SET `is_select` = '1' WHERE `project_id` = '$project_id' AND `is_active` = '1'");
	}

	public function insert_sr_images($project_id,$image_path,$is_site_inspection,$is_completion){
		$this->db->query("INSERT INTO `service_report_images` (`project_id`, `image_path`,`is_site_inspection`,`is_completion`) VALUES ('$project_id', '$image_path','$is_site_inspection','$is_completion')");
	}

	public function fetch_service_report_images_si($project_id){
		$query = $this->db->query("SELECT * FROM `service_report_images` AS t1 LEFT JOIN `service_report_group` AS t2 ON `t1`.group_id = `t2`.group_id WHERE `t1`.`project_id` = '$project_id' AND `is_site_inspection` = '1' AND `is_completion` = '0' AND `t1`.`is_active` = '1' ORDER BY `t1`.`group_id`");
		return $query;
	}

	public function fetch_service_report_images_comp($project_id){
		$query = $this->db->query("SELECT * FROM `service_report_images` AS t1 LEFT JOIN `service_report_group` AS t2 ON `t1`.group_id = `t2`.group_id WHERE `t1`.`project_id` = '$project_id' AND `is_site_inspection` = '0' AND `is_completion` = '1' AND `t1`.`is_active` = '1'ORDER BY `t1`.`group_id`");
		return $query;
	}

	public function fetch_service_report_images_all($project_id){
		$query = $this->db->query("SELECT * FROM `service_report_images` as t1 LEFT JOIN `service_report_group` as t2 ON `t1`.group_id = `t2`.group_id WHERE `t1`.`project_id` = '$project_id' ORDER BY `t1`.`group_id`");
		return $query;
	}

	public function attach_storage_file_to_project($storage_files_id){
		$this->db->query("UPDATE `storage_files` SET `is_project_attachment` = 1 WHERE `storage_files_id` = '$storage_files_id'");
	}

	public function unattach_storage_file_to_project($storage_files_id){
		$this->db->query("UPDATE `storage_files` SET `is_project_attachment` = 0 WHERE `storage_files_id` = '$storage_files_id'");
	}

	public function approve_storage_file_to_project($storage_files_id){
		$this->db->query("UPDATE `storage_files` SET `is_authorized` = 1 WHERE `storage_files_id` = '$storage_files_id'");
	}

	public function fetch_project_required_doc_type_file($project_id,$doc_type){
		$query = $this->db->query("SELECT * FROM storage_files a
								LEFT JOIN storage_doc_type b ON b.storage_doc_type_id = a.file_type
							WHERE a.is_active = 1
								AND a.project_id = '$project_id'
								AND a.file_type = '$doc_type'
								AND a.is_project_attachment = 1
						");
		return $query;
	}

	public function set_file_for_replacement($storage_files_id){
			$this->db->query("UPDATE `storage_files` SET `for_replacement` = 1, will_replace_existing = 0, replace_by_storage_files_id = 1  WHERE `storage_files_id` = '$storage_files_id'");
	}

	public function check_file_for_replacement($project_id){
		$query = $this->db->query("SELECT * FROM storage_files a
								LEFT JOIN storage_doc_type b ON b.storage_doc_type_id = a.file_type
							WHERE a.is_active = 1
								AND a.project_id = '$project_id'
								AND a.for_replacement = '1'
						");
		return $query;
	}

	public function unselect_doc_file($project_id){
		$this->db->query("UPDATE `storage_files` SET `is_project_attachment` = 0 WHERE `project_id` = '$project_id' AND `for_replacement` = 1");
	}

	public function approve_doc_file_selected($storage_files_id){
		$this->db->query("UPDATE `storage_files` SET `is_authorized` = 1, is_project_attachment = 1 WHERE `storage_files_id` = '$storage_files_id'");
	}

	public function fetch_storage_file_details($storage_files_id){
		$query = $this->db->query("SELECT * FROM storage_files a
										LEFT JOIN storage_doc_type b ON b.storage_doc_type_id = a.file_type
									WHERE a.storage_files_id = '$storage_files_id'
						");
		return $query;
	}

	public function fetch_storage_liles_need_authorization($project_id){
		$query = $this->db->query("SELECT * FROM storage_files a
								LEFT JOIN storage_doc_type b ON b.storage_doc_type_id = a.file_type
							WHERE a.is_active = 1
								AND a.project_id = '$project_id'
								AND a.is_authorized = '0'
							order by b.doc_type_name
						");
		return $query;
	}

	public function approve_file_to_be_attached($storage_files_id){
		$this->db->query("UPDATE storage_files 
								set is_project_attachment = 1,
									is_authorized = 1,
									for_replacement = 0
									WHERE storage_files_id = '$storage_files_id'");

		$this->db->query("UPDATE storage_files 
								set is_project_attachment = 0,
									is_authorized = 1,
									will_replace_existing = 0,
									replace_by_storage_files_id = 0
									WHERE replace_by_storage_files_id = '$storage_files_id'");
	}
}