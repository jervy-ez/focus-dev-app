<?php

class Wip_m extends CI_Model{

	public function display_all_wip_projects(){
		$query = $this->db->query("SELECT `project`.*,`users`.*,`company_details`.*,`project_cost_total`.`work_estimated_total`
			FROM  `project`
			LEFT JOIN `company_details` ON `company_details`.`company_id` = `project`.`client_id` 
			LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id`
			WHERE `project`.`job_date` <> '' AND `project`.is_active = '1' AND  `project`.`is_paid` = '0'
			ORDER BY `project`.`project_id`  DESC");
		return $query;
	}

}