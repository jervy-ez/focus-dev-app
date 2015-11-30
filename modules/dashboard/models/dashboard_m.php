<?php

class Dashboard_m extends CI_Model{
	
	public function insert_revenue_forecast($data_type,$year,$focus_company_id,$data_name,$total_amount,$jul_breakdown,$aug_breakdown,$sep_breakdown,$oct_breakdown,$nov_breakdown,$dec_breakdown,$jan_breakdown,$feb_breakdown,$mar_breakdown,$apr_breakdown,$may_breakdown,$jun_breakdown){
		$this->db->query("INSERT INTO `revenue_forecast` (`data_type`, `year`, `focus_company_id`, `data_name`, `total_amount`, `jul_breakdown`, `aug_breakdown`, `sep_breakdown`, `oct_breakdown`, `nov_breakdown`, `dec_breakdown`, `jan_breakdown`, `feb_breakdown`, `mar_breakdown`, `apr_breakdown`, `may_breakdown`, `jun_breakdown`) 
			VALUES ('$data_type', '$year', '$focus_company_id', '$data_name', '$total_amount', '$jul_breakdown', '$aug_breakdown', '$sep_breakdown', '$oct_breakdown', '$nov_breakdown', '$dec_breakdown', '$jan_breakdown', '$feb_breakdown', '$mar_breakdown', '$apr_breakdown', '$may_breakdown', '$jun_breakdown')");
		return $this->db->insert_id();	
	}

	public function list_stored_revenue_forecast(){
		$query = $this->db->query("SELECT `revenue_forecast`.*,`company_details`.`company_name` FROM `revenue_forecast` LEFT JOIN `company_details` ON `company_details`.`company_id` = `revenue_forecast`.`focus_company_id` WHERE `is_active` = '1' ORDER BY `revenue_forecast`.`revenue_forecast_id` DESC");
		return $query;		
	}

	public function diactivate_stored_revenue_forecast($id){
		$query = $this->db->query("UPDATE `revenue_forecast` SET `is_active` = '0' WHERE `revenue_forecast`.`revenue_forecast_id` = '$id'");
		return $query;		
	}
	
	public function update_revenue_forecast($revenue_forecast_id,$data_type,$year,$focus_company_id,$data_name,$total_amount,
		$jul_breakdown,$aug_breakdown,$sep_breakdown,$oct_breakdown,$nov_breakdown,$dec_breakdown,$jan_breakdown,$feb_breakdown,$mar_breakdown,$apr_breakdown,$may_breakdown,$jun_breakdown){
		$this->db->query("UPDATE `revenue_forecast` SET `data_type` = '$data_type', `year` = '$year', `focus_company_id` = '$focus_company_id', `data_name` = '$data_name', `total_amount` = '$total_amount', `jul_breakdown` = '$jul_breakdown', `aug_breakdown` = '$aug_breakdown', `sep_breakdown` = '$sep_breakdown', `oct_breakdown` = '$oct_breakdown', `nov_breakdown` = '$nov_breakdown', `dec_breakdown` = '$dec_breakdown', `jan_breakdown` = '$jan_breakdown', `feb_breakdown` = '$feb_breakdown', `mar_breakdown` = '$mar_breakdown', `apr_breakdown` = '$apr_breakdown', `may_breakdown` = '$may_breakdown', `jun_breakdown` = '$jun_breakdown' WHERE `revenue_forecast`.`revenue_forecast_id` = '$revenue_forecast_id' ");
		return $this->db->insert_id();	
	}

	public function get_revenue_forecast($year){
		$query = $this->db->query("SELECT * FROM `revenue_forecast` WHERE `revenue_forecast`.`year` = '$year' ORDER BY `revenue_forecast`.`focus_company_id` ASC");
		return $query;		
	}

	public function getSales_perMonth($project_manager_id,$date_a,$date_b){
		$query = $this->db->query("SELECT SUM(`invoice`.`invoiced_amount`) AS `invoiced_amount_total`
			FROM  `invoice`
			LEFT JOIN `project` ON  `project`.`project_id` = `invoice`.`project_id`
			WHERE `invoice`.`is_invoiced` = '1' AND `project`.`is_active` = '1'
			AND  `project`.`project_manager_id` = '$project_manager_id'
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= '$date_a' AND  UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) < '$date_b'
			GROUP BY `project`.`project_manager_id` ");
		return $query;
	}

	public function get_pm_names(){
		$query = $this->db->query("SELECT `users`.`user_id`,`users`.`user_focus_company_id`, CONCAT_WS(' ', `users`.`user_first_name`, `users`.`user_last_name`) AS `user_pm` FROM `users` WHERE `users`.`user_role_id` = '3' AND `users`.`user_id` <> '29' AND `users`.`is_active` = '1' ORDER BY `users`.`user_focus_company_id` ASC");
		return $query;
	}

}