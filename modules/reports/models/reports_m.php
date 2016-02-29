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
		$query = $this->db->query("SELECT *,`payment`.`payment_id`,`payment`.`project_id` as `payment_project_id`,`invoice`.`project_id` as `invoice_project_id` FROM `invoice`
			LEFT JOIN `project` ON `project`.`project_id` = `invoice`.`project_id`
			LEFT JOIN `company_details` ON `company_details`.`company_id` = `project`.`client_id`
			LEFT JOIN `payment` ON `payment`.`invoice_id` = `invoice`.`invoice_id`
			$has_where $project_num_q $progress_claim_q $client_q $invoice_status_q $project_manager_q AND `project`.`job_date` <> '' $order_q");
		return $query;


	}

	public function select_list_wip($wip_client_q,$wip_pm_q,$selected_cat_q,$order_q,$type,$status){
		$query = $this->db->query("SELECT `project`.*,`users`.*,`company_details`.*,`project_cost_total`.`work_estimated_total`,`project_cost_total`.`variation_total`, UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') )  AS 'date_filter_mod', UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_commencement`, '%d/%m/%Y') )  AS 'start_date_filter_mod'
			FROM  `project`
			LEFT JOIN `company_details` ON `company_details`.`company_id` = `project`.`client_id` 
			LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id`
			WHERE `project`.is_active = '1' $status $type 
			$wip_client_q $wip_pm_q $selected_cat_q $order_q");
		return $query;
	}

}