<?php

class Reports_m extends CI_Model{


	public function select_list_company($company_type,$more_q,$order){
		$query = $this->db->query("SELECT * FROM  `company_details`			
			LEFT JOIN  `address_detail` ON  `address_detail`.`address_detail_id` =  `company_details`.`address_id` 
			LEFT JOIN  `address_general` ON  `address_general`.`general_address_id` =  `address_detail`.`general_address_id`
			LEFT JOIN `contact_person_company` ON `contact_person_company`.`company_id` = `company_details`.`company_id`
			LEFT JOIN  `contact_person` ON  `contact_person`.`contact_person_id` = `contact_person_company`.`contact_person_id`			
			LEFT JOIN `contact_number` ON `contact_number`.`contact_number_id` = `contact_person`.`contact_number_id`
			LEFT JOIN `email` ON `email`.`email_id` = `contact_person`.`email_id`
			LEFT JOIN  `states` ON  `states`.`id` =  `address_general`.`state_id` 
            LEFT JOIN `client_category` ON `client_category`.`client_category_id` = `company_details`.`activity_id`
			WHERE `contact_person_company`.`is_primary` = '1' AND `company_details`.`active` = '1'
			$company_type $more_q
			ORDER BY $order ");
		return $query;
	}
//`company_details`.`company_id` ASC

	public function select_list_invoice($has_where,$project_num_q,$client_q,$invoice_status_q,$progress_claim_q,$project_manager_q,$order_q){
		$query = $this->db->query("SELECT `invoice`.*,

			`project`.`project_name`,
			`company_details`.`company_name`,
			`project`.`project_id`,
			`project`.`project_manager_id`,
			`project`.`review_date`,

			UNIX_TIMESTAMP( STR_TO_DATE(`project`.`review_date`, '%d/%m/%Y') ) AS `unix_review_date`,
			`project`.`project_total`,
			`project`.`date_site_finish`,
			`project_cost_total`.`variation_total`,

			`payment`.`payment_id`,
			`payment`.`project_id` as `payment_project_id`,
			`invoice`.`project_id` as `invoice_project_id`,
			UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') )  AS `in_set_ord` ,
			UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') )  AS `unix_invoice_date_req`,

			`project`.quote_deadline_date


			FROM `invoice`
			
			LEFT JOIN `project` ON `project`.`project_id` = `invoice`.`project_id`
			LEFT JOIN `company_details` ON `company_details`.`company_id` = `project`.`client_id`
			LEFT JOIN `payment` ON `payment`.`invoice_id` = `invoice`.`invoice_id`
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id`
			$has_where $project_num_q $progress_claim_q $client_q $invoice_status_q $project_manager_q
			AND `project`.is_active = '1'
			AND `project`.`job_date` <> ''   $order_q ");
		return $query;


	}

	public function select_myob_names(){
		$query = $this->db->query("SELECT `company_details`.`company_name`, `company_details`.`myob_name`,  `company_details`.`abn`
			FROM `company_details`  
			WHERE  `company_details`.`active` = '1'  AND ( `company_details`.`company_type_id`= '2' || `company_details`.`company_type_id` = '3' )
			ORDER BY `company_details`.`company_name` ASC");
		return $query;
	}
 
	public function select_po_data($is_reconciled,$pm,$cpo_date,$reconciled_date,$focus_company_id,$order,$for_myob){

		$current_date = date("d/m/Y");




		$query = $this->db->query("SELECT `company_details`.`company_name`,`company_details`.`myob_name`, `works`.`works_id`, `works`.`price` , `works`.`project_id` , `works`.`work_cpo_date` , `works`.`reconciled_date`, `users`.`user_first_name`, `users`.`user_last_name`
			, UNIX_TIMESTAMP( STR_TO_DATE(`works`.`reconciled_date`, '%d/%m/%Y') )  AS `unix_reconciled_date`
			, UNIX_TIMESTAMP( STR_TO_DATE(`works`.`work_cpo_date`, '%d/%m/%Y') )  AS `unix_work_cpo_date`
			, `project`.`date_site_finish`,  `project`.`date_site_commencement`,  `works`.`po_set_report_date`,`works`.`other_work_desc`,

			IFNULL ( CASE            
				WHEN `works`.`contractor_type` = '2' THEN `job_category`.`myob_item_no`
				WHEN `works`.`contractor_type` = '3' THEN `supplier_cat`.`myob_item_no` 
				ELSE 'MISC'
				END, 'MISC')  AS `myob_item_name`


			FROM `works`  
			LEFT JOIN `company_details` ON `company_details`.`company_id`= `works`.`company_client_id` 
			LEFT JOIN `project` ON `project`.`project_id` = `works`.`project_id` 
			LEFT JOIN `users` on `users`.`user_id` = `project`.`project_manager_id`

			LEFT JOIN `job_sub_category` ON `job_sub_category`.`job_sub_cat_id` = `works`.`work_con_sup_id`
            LEFT JOIN `job_category` ON `job_category`.`job_category_id` = `job_sub_category`.`job_category_id` 
			LEFT JOIN `supplier_cat`  ON  `supplier_cat`.`supplier_cat_id` =  `works`.`work_con_sup_id` 

			WHERE `works`.`is_active` = '1'  
			AND `works`.`is_reconciled` = '$is_reconciled'  
			AND `works`.`work_cpo_date` != ''  
			AND `project`.`is_active` = '1'
			AND  `project`.`job_date`  != '' 

			".($pm != '' ? " AND `project`.`project_manager_id` = '$pm' " : "")."
			".($focus_company_id != '' ? " AND `project`.`focus_company_id` = '$focus_company_id' " : "")."
			".($cpo_date != '' ? " $cpo_date " : "")."
			".($reconciled_date != '' ? " $reconciled_date " : "")." 

			$order ");

if($for_myob == 1){


	$this->db->query("UPDATE `works` 

		LEFT JOIN `company_details` ON `company_details`.`company_id`= `works`.`company_client_id` 
		LEFT JOIN `project` ON `project`.`project_id` = `works`.`project_id` 
		LEFT JOIN `users` on `users`.`user_id` = `project`.`project_manager_id`

		SET `po_set_report_date` = '$current_date' 

		WHERE `works`.`is_active` = '1'  
		AND `works`.`is_reconciled` = '$is_reconciled'  
		AND `works`.`work_cpo_date` != ''  
		AND `project`.`is_active` = '1'
		AND  `project`.`job_date`  != '' 

		".($pm != '' ? " AND `project`.`project_manager_id` = '$pm' " : "")."
		".($focus_company_id != '' ? " AND `project`.`focus_company_id` = '$focus_company_id' " : "")."
		".($cpo_date != '' ? " $cpo_date " : "")."
		".($reconciled_date != '' ? " $reconciled_date " : "")."

		");

}


		return $query;
	}

 

	public function select_list_wip($wip_client_q,$wip_pm_q,$selected_cat_q,$order_q,$type,$status){
		$query = $this->db->query("SELECT `project`.*, `project_schedule_task`.`project_schedule_task_id`, `project_schedule`.`contruction_manager_id`,`project_schedule`.`leading_hand_id` ,  `users`.*,`company_details`.*,`project_cost_total`.`work_estimated_total`,`project_cost_total`.`variation_total`, UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') )  AS 'date_filter_mod', UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_commencement`, '%d/%m/%Y') )  AS 'start_date_filter_mod',
			UNIX_TIMESTAMP( STR_TO_DATE(`project`.`quote_deadline_date`, '%d/%m/%Y') )  AS 'unix_quote_deadline_date'
			FROM  `project`
			LEFT JOIN `company_details` ON `company_details`.`company_id` = `project`.`client_id` 
			LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id`
			LEFT JOIN `project_schedule` ON `project_schedule`.`project_id` =  `project`.`project_id`
			LEFT JOIN `project_schedule_task` ON `project_schedule_task`.`project_schedule_id` =  `project_schedule`.`project_schedule_id` 
			WHERE `project`.is_active = '1' $status $type 
			$wip_client_q $wip_pm_q $selected_cat_q  GROUP BY  `project`.`project_id` $order_q 

			");
		return $query;
	}

}