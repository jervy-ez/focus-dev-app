<?php

class Dashboard_m_es extends CI_Model{
	
	public function fetch_estimators_wip(){
		$query = $this->db->query("SELECT `project`.`project_id` , `project`.`project_name`, `project`.`project_total`,  `project_cost_total`.`variation_total`, `project`.`project_estiamator_id` 

			FROM `project`
			LEFT JOIN `invoice` ON `invoice`.`project_id` = `project`.`project_id`
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id`

			WHERE `project`.`is_active` = '1'
			
			AND `project`.`is_paid` = '0'
			AND `project`.`job_type` != 'Company'
			AND `project`.`job_date` != ''
			AND  `invoice`.`is_invoiced` = '0'
			GROUP BY `invoice`.`project_id`");
		return $query;
	}


	public function fetch_completed($date_a,$date_b,$estiamator_id){
		$query = $this->db->query("SELECT `project`.`project_id`, `project`.`date_site_commencement`, `project`.`date_site_finish`,`project_cost_total`.`variation_total`,  `project`.`project_total`

			FROM `project`

			LEFT JOIN  `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id`
			WHERE `project`.`is_active` = '1' AND `project`.`job_date` != ''
			AND `project`.`project_estiamator_id` = '$estiamator_id'

			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) ");
		return $query;
	}


	public function fetch_upcoming_deadline($date='',$estiamator_id = ''){
		$query = $this->db->query("SELECT `project`.`project_id`,`project`.`project_name` , `project`.`quote_deadline_date` ,`users`.`user_first_name`, UNIX_TIMESTAMP( STR_TO_DATE(`project`.`quote_deadline_date`, '%d/%m/%Y') )*1000 AS `deadline_unix`,`project`.`project_date`,
			UNIX_TIMESTAMP( STR_TO_DATE(`project`.`project_date`, '%d/%m/%Y') )*1000 AS `project_date_unix`	,`company_details`.`company_name` AS `client_name`, `brand`.`brand_name`,`project`.`project_estiamator_id`
			FROM `project`
			LEFt JOIN  `users` ON `users`.`user_id` = `project`.`project_estiamator_id` 
            LEFT JOIN `company_details` ON `company_details`.`company_id` =  `project`.`client_id` 
            LEFT JOIN `brand` ON `brand`.`brand_id` =  `project`.`brand_id`
			WHERE `project`.`is_active` = '1' AND  `project`.`job_date` = '' AND  `project`.`project_estiamator_id` != '0' AND `project`.`project_estiamator_id`  != '8'
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`quote_deadline_date`, '%d/%m/%Y') ) > UNIX_TIMESTAMP( STR_TO_DATE('$date', '%d/%m/%Y') )

			".($estiamator_id != '' ? " AND  `project`.`project_estiamator_id` = '$estiamator_id' " : "")."

			
			ORDER BY `deadline_unix` ASC LIMIT 15");
		return $query;
	}



}