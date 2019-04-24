<?php

class Purchase_order_m extends CI_Model{

	public function get_po_list(){

		$query = $this->db->query("SELECT `job_sub_category`.`job_sub_cat`,`supplier_cat`.`supplier_cat_name`, `project`.`date_site_finish`,`works`.`company_client_id`,`works`.`works_id`,`project`.`project_id`,`project`.`job_date`,`project`.`project_name`,`project`.`project_manager_id`,`project`.`project_admin_id`,`cd`.`company_name` as `client_name`,`cn`.`company_name` as `contractor_name`,`users`.`user_first_name`,`users`.`user_last_name`,`works`.`price`,`works`.`other_work_desc`,`works`.`contractor_type`,`works`.`work_cpo_date`, `project`.`focus_company_id`,`project`.`project_manager_id`
			,UNIX_TIMESTAMP( STR_TO_DATE(`works`.`work_cpo_date`, '%d/%m/%Y') ) AS `cpo_tmpstp_date`,UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) AS `workfinish_tmpstp_date`
			FROM `works`
			LEFT JOIN `project` ON `project`.`project_id` = `works`.`project_id`
			LEFT JOIN `job_sub_category` ON `job_sub_category`.`job_sub_cat_id` = `works`.`work_con_sup_id`
			LEFT JOIN `supplier_cat` ON `supplier_cat`.`supplier_cat_id` = `works`.`work_con_sup_id`
			LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
			LEFT JOIN `company_details` `cd` ON `cd`.`company_id` = `project`.`client_id`
			LEFT JOIN `company_details` `cn` ON `cn`.`company_id` = `works`.`company_client_id`
			WHERE `works`.`is_reconciled` = '0' AND `works`.`is_active` = '1' AND TRIM(`works`.`work_cpo_date`) <> '' AND TRIM(`project`.`job_date`) <> ''  AND `project`.`is_active` = '1' ");
		return $query;
	}

	public function get_po_list_order_by_project($start_date,$end_date){
		$start_date_arr = explode("/", $start_date);
		$start_date = $start_date_arr[2]."-".$start_date_arr[1]."-".$start_date_arr[0];
		
		$end_date_arr = explode("/", $end_date);
		$end_date = $end_date_arr[2]."-".$end_date_arr[1]."-".$end_date_arr[0];
		$query = $this->db->query("SELECT `job_sub_category`.`job_sub_cat`,`supplier_cat`.`supplier_cat_name`,`works`.`works_id`,`project`.`project_id`,`project`.`job_date`,`project`.`project_name`,`cd`.`company_name` as `client_name`,`cn`.`company_name` as `contractor_name`,`users`.`user_first_name`,`users`.`user_last_name`,`works`.`price`,`works`.`other_work_desc`,`works`.`contractor_type`,`works`.`work_cpo_date`,`works`.`work_reply_date`,`company_details`.`company_name`, `project`.`focus_company_id`,`project`.`project_manager_id`
			FROM `works`
			LEFT JOIN `project` ON `project`.`project_id` = `works`.`project_id`
			LEFT JOIN `company_details` on `company_details`.`company_id` = `project`.`client_id`
			LEFT JOIN `job_sub_category` ON `job_sub_category`.`job_sub_cat_id` = `works`.`work_con_sup_id`
			LEFT JOIN `supplier_cat` ON `supplier_cat`.`supplier_cat_id` = `works`.`work_con_sup_id`
			LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
			LEFT JOIN `company_details` `cd` ON `cd`.`company_id` = `project`.`client_id`
			LEFT JOIN `company_details` `cn` ON `cn`.`company_id` = `works`.`company_client_id`
			WHERE `works`.`is_reconciled` = '0' AND `works`.`is_active` = '1' AND TRIM(`works`.`work_cpo_date`) <> '' AND TRIM(`project`.`job_date`) <> ''  AND `project`.`is_active` = '1' AND STR_TO_DATE(`works`.`work_cpo_date`,'%d/%m/%Y') between '$start_date' and '$end_date'
			ORDER BY `project`.project_id");
		return $query;
	}

	public function get_reconciled_list($limit=2000,$like_work_id=''){

		$query = $this->db->query("SELECT `job_sub_category`.`job_sub_cat`,`supplier_cat`.`supplier_cat_name`,`works`.`works_id`,`project`.`project_id`,`project`.`job_date`,`project`.`project_name`,`cd`.`company_name` as `client_name`,`cn`.`company_name` as `contractor_name`,`users`.`user_first_name`,`users`.`user_last_name`,`works`.`price`,`works`.`other_work_desc`,`works`.`contractor_type`,`works`.`work_cpo_date`,`works`.`reconciled_date`,UNIX_TIMESTAMP( STR_TO_DATE(`works`.`work_cpo_date`, '%d/%m/%Y') ) AS `cpo_tmpstp_date`
			FROM `works`
			LEFT JOIN `project` ON `project`.`project_id` = `works`.`project_id`
			LEFT JOIN `job_sub_category` ON `job_sub_category`.`job_sub_cat_id` = `works`.`work_con_sup_id`
			LEFT JOIN `supplier_cat` ON `supplier_cat`.`supplier_cat_id` = `works`.`work_con_sup_id`
			LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
			LEFT JOIN `company_details` `cd` ON `cd`.`company_id` = `project`.`client_id`
			LEFT JOIN `company_details` `cn` ON `cn`.`company_id` = `works`.`company_client_id`
			WHERE `works`.`is_reconciled` = '1' AND `works`.`is_active` = '1' AND TRIM(`works`.`work_cpo_date`) <> '' AND TRIM(`project`.`job_date`) <> ''  AND `project`.`is_active` = '1'
			".($like_work_id != '' ? " AND `works`.`works_id` LIKE '%$like_work_id%' " : " " )." 
			ORDER BY `works`.`works_id` DESC
			LIMIT 0, $limit  ");
		return $query;
	} /* forced the reconciled to limit from nov 2016 */


	public function set_po($po_number,$joiery_id=0){
		$this->db->query("INSERT INTO `work_purchase_order` (`work_id_po`,`joinery_id`) VALUES ('$po_number','$joiery_id')");
		$project_id = $this->db->insert_id();
		return $project_id;
	}

	public function check_po_reviewer($po_number,$date_a,$date_b){
		$query = $this->db->query(" SELECT * FROM `po_review`  
			WHERE `po_review`.`po_number` = '$po_number'  
			AND UNIX_TIMESTAMP( STR_TO_DATE(`po_review`.`date_set`, '%d/%m/%Y') ) >=  UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`po_review`.`date_set`, '%d/%m/%Y') )  <   UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )  
			ORDER BY `po_review`.`po_review_id`  DESC LIMIT 1 ");
		return $query;
	}


	public function get_contact_user($user_id){ // temporaty used for pulling out email
		$query = $this->db->query("SELECT `users`.`user_email_id`, `email`.`general_email`  FROM `users`  
			LEFT JOIN  `email` ON `email`.`email_id` =  `users`.`user_email_id`
			WHERE  `users`.`user_id` = '$user_id'");
		return $query;
	}


	public function remove_po($work_purchase_order_id){
		$query = $this->db->query("DELETE FROM `work_purchase_order` WHERE `work_purchase_order`.`work_purchase_order_id` = '$work_purchase_order_id' ");
		return $query;
	}

	public function select_po_history($work_id_po,$joinery_id=0){
		$query = $this->db->query("SELECT * FROM `work_purchase_order` WHERE `work_purchase_order`.`work_id_po` = '$work_id_po' AND `work_purchase_order`.`joinery_id` = '$joinery_id' ORDER BY `work_purchase_order`.`work_purchase_order_id` DESC");
		return $query;
	}

	public function select_last_po_history($work_id_po,$joinery_id=0){
		$query = $this->db->query("SELECT * FROM `work_purchase_order` WHERE `work_purchase_order`.`work_id_po` = '$work_id_po' AND `work_purchase_order`.`joinery_id` = '$joinery_id' ORDER BY `work_purchase_order`.`work_purchase_order_id` DESC LIMIT 1");
		return $query;
	}

	public function insert_po_review($po_number,$project_id,$date_set,$estimate_price_wgst,$action){
		$this->db->query("INSERT INTO `po_review` ( `po_number`, `project_id`, `date_set`, `estimate_price_wgst`, `action`) VALUES ( '$po_number', '$project_id', '$date_set', '$estimate_price_wgst', '$action')");
		$inserted_id = $this->db->insert_id();
		return $inserted_id;
	}

	public function update_po_work_date($work_id_po,$work_po_date){
		$query = $this->db->query("UPDATE `work_purchase_order` SET `work_po_date` = '$work_po_date' WHERE `work_purchase_order`.`work_purchase_order_id` = '$work_id_po' ");
		return $query;
	}

	public function po_set_outstanding($work_id_po,$joinery_id=0){

		if($joinery_id==0){
			$query = $this->db->query("UPDATE `works` SET `is_reconciled` = '0', `reconciled_date` = NULL WHERE `works`.`works_id` = '$work_id_po' ");
		}else{
			$query = $this->db->query("UPDATE `work_joinery` SET `is_reconciled` = '0', `reconciled_date` = NULL WHERE `work_joinery`.`works_id` = '$work_id_po' AND `work_joinery`.`work_joinery_id` = '$joinery_id' ");
		}
		return $query;
	}

	public function po_set_reconciled($work_id_po,$reconciled_date,$joinery_id){

		if($joinery_id==0){
			$query = $this->db->query("UPDATE `works` SET `is_reconciled` = '1', `reconciled_date` = '$reconciled_date' WHERE `works`.`works_id` = '$work_id_po' ");
		}else{
			$query = $this->db->query("UPDATE `work_joinery` SET `is_reconciled` = '1', `reconciled_date` = '$reconciled_date' WHERE `work_joinery`.`works_id` = '$work_id_po' AND `work_joinery`.`work_joinery_id` = '$joinery_id' ");
		}
		return $query;
	}

	public function update_work_invoice($work_invoice_date,$work_id_po,$joinery_id=0,$invoice_no,$amount){
		$this->db->query("UPDATE `work_purchase_order` SET `work_invoice_date` = '$work_invoice_date', `invoice_no` = '$invoice_no', `amount` = '$amount' WHERE `work_purchase_order`.`work_id_po` = '$work_id_po' AND `joinery_id` = '$joinery_id' ORDER BY `work_purchase_order`.`work_purchase_order_id` DESC LIMIT 1 ");
		return $project_id;
	}

	public function insert_work_invoice($work_invoice_date,$work_id_po,$joinery_id,$notes_id,$invoice_no,$amount){
		$this->db->query("INSERT INTO `work_purchase_order` ( `work_invoice_date`, `work_id_po`,`joinery_id`, `notes_id`,`invoice_no`,`amount`) VALUES ('$work_invoice_date', '$work_id_po','$joinery_id', '$notes_id', '$invoice_no','$amount')");
		$project_id = $this->db->insert_id();
		return $project_id;
	}

	public function get_work_joinery_list($start_date='',$end_date=''){
		if($start_date !== ''){
			$start_date_arr = explode("/", $start_date);
			$start_date = $start_date_arr[2]."-".$start_date_arr[1]."-".$start_date_arr[0];
			
			$end_date_arr = explode("/", $end_date);
			$end_date = $end_date_arr[2]."-".$end_date_arr[1]."-".$end_date_arr[0];
		}

		$query = $this->db->query("SELECT `work_joinery`.`work_joinery_id`,`work_joinery`.`joinery_id`, `project`.`date_site_finish`,`work_joinery`.`company_client_id`,`joinery`.`joinery_name`,`work_joinery`.`works_id`,`project`.`project_id`,`project`.`job_date`,`project`.`project_name`,`cd`.`company_name` as `client_name`,`cn`.`company_name` as `contractor_name`,`users`.`user_first_name`,`users`.`user_last_name`,`work_joinery`.`price`,`work_joinery`.`work_cpo_date`,`project`.`project_manager_id`
			,UNIX_TIMESTAMP( STR_TO_DATE(`work_joinery`.`work_cpo_date`, '%d/%m/%Y') ) AS `cpo_tmpstp_date`,UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) AS `workfinish_tmpstp_date`
			FROM `work_joinery` 
			LEFT JOIN `works` ON `works`.`works_id` = `work_joinery`.`works_id`
			LEFT JOIN `joinery` ON `joinery`.`joinery_id` = `work_joinery`.`joinery_id`
			LEFT JOIN `project` ON `project`.`project_id` = `works`.`project_id`
			LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
			LEFT JOIN `company_details` `cd` ON `cd`.`company_id` = `project`.`client_id`
			LEFT JOIN `company_details` `cn` ON `cn`.`company_id` = `work_joinery`.`company_client_id`
			WHERE `work_joinery`.`work_cpo_date` <> '' 
				AND `works`.`work_con_sup_id` = '53' 
				AND `work_joinery`.`is_reconciled` = '0'  
				AND `project`.`is_active` = '1'
				AND STR_TO_DATE(`works`.`work_cpo_date`,'%d/%m/%Y') between '$start_date' and '$end_date'
		");
		return $query;
	}

	public function get_reconciled_work_joinery_list($like_work_id=''){
		$query = $this->db->query("SELECT `work_joinery`.`work_joinery_id`,`work_joinery`.`joinery_id`,`joinery`.`joinery_name`,`work_joinery`.`works_id`,`project`.`project_id`,`project`.`job_date`,`project`.`project_name`,`cd`.`company_name` as `client_name`,`cn`.`company_name` as `contractor_name`,`users`.`user_first_name`,`users`.`user_last_name`,`work_joinery`.`price`,`work_joinery`.`work_cpo_date`,`work_joinery`.`reconciled_date`,UNIX_TIMESTAMP( STR_TO_DATE(`work_joinery`.`work_cpo_date`, '%d/%m/%Y') ) AS `cpo_tmpstp_date`
			FROM `work_joinery` 
			LEFT JOIN `works` ON `works`.`works_id` = `work_joinery`.`works_id`
			LEFT JOIN `joinery` ON `joinery`.`joinery_id` = `work_joinery`.`joinery_id`
			LEFT JOIN `project` ON `project`.`project_id` = `works`.`project_id`
			LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
			LEFT JOIN `company_details` `cd` ON `cd`.`company_id` = `project`.`client_id`
			LEFT JOIN `company_details` `cn` ON `cn`.`company_id` = `work_joinery`.`company_client_id`
			WHERE `work_joinery`.`work_cpo_date` <> '' AND `works`.`work_con_sup_id` = '53' AND `work_joinery`.`is_reconciled` = '1'  AND `project`.`is_active` = '1' 
			".($like_work_id != '' ? " AND `work_joinery`.`works_id` LIKE '%$like_work_id%' " : " " )." ");
		return $query;
	}

	public function get_work($works_id){
		$query = $this->db->query("SELECT * FROM `works` WHERE `works`.`works_id` = $works_id");
		return $query;
	}

	public function get_joinery($work_joinery_id,$works_id){
		$query = $this->db->query("SELECT * FROM `work_joinery` WHERE `work_joinery`.`work_joinery_id` = '$work_joinery_id' AND `work_joinery`.`works_id` = '$works_id'");
		return $query;
	}

	public function get_po_total_paid($work_id_po,$joinery_id=0){
		$query = $this->db->query("SELECT SUM(`work_purchase_order`.`amount`) AS `total_paid` FROM `work_purchase_order` WHERE `work_purchase_order`.`work_id_po` = '$work_id_po' AND `work_purchase_order`.`joinery_id` = '$joinery_id' ");
		return $query;
	}



}