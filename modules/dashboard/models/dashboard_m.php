<?php

class Dashboard_m extends CI_Model{
	
	public function insert_revenue_forecast($data_type,$year,$focus_company_id,$data_name,$total_amount,$jul_breakdown,$aug_breakdown,$sep_breakdown,$oct_breakdown,$nov_breakdown,$dec_breakdown,$jan_breakdown,$feb_breakdown,$mar_breakdown,$apr_breakdown,$may_breakdown,$jun_breakdown){
		$this->db->query("INSERT INTO `revenue_forecast` (`data_type`, `year`, `focus_company_id`, `data_name`, `total_amount`, `jul_breakdown`, `aug_breakdown`, `sep_breakdown`, `oct_breakdown`, `nov_breakdown`, `dec_breakdown`, `jan_breakdown`, `feb_breakdown`, `mar_breakdown`, `apr_breakdown`, `may_breakdown`, `jun_breakdown`) 
			VALUES ('$data_type', '$year', '$focus_company_id', '$data_name', '$total_amount', '$jul_breakdown', '$aug_breakdown', '$sep_breakdown', '$oct_breakdown', '$nov_breakdown', '$dec_breakdown', '$jan_breakdown', '$feb_breakdown', '$mar_breakdown', '$apr_breakdown', '$may_breakdown', '$jun_breakdown')");
		return $this->db->insert_id();	
	}
/*
	public function list_stored_revenue_forecast(){
		$query = $this->db->query("SELECT `revenue_forecast`.*,`company_details`.`company_name` FROM `revenue_forecast` LEFT JOIN `company_details` ON `company_details`.`company_id` = `revenue_forecast`.`focus_company_id` WHERE `is_active` = '1' AND `revenue_forecast`.`data_type` = 'Forecast' ORDER BY `revenue_forecast`.`revenue_forecast_id` DESC");
		return $query;		
	}
*/
	public function diactivate_stored_revenue_forecast($id){
		$query = $this->db->query("UPDATE `revenue_forecast` SET `is_active` = '0' WHERE `revenue_forecast`.`revenue_forecast_id` = '$id'");
		return $query;		
	}
	
	public function update_revenue_forecast($revenue_forecast_id,$data_type,$year,$focus_company_id,$data_name,$total_amount,
		$jul_breakdown,$aug_breakdown,$sep_breakdown,$oct_breakdown,$nov_breakdown,$dec_breakdown,$jan_breakdown,$feb_breakdown,$mar_breakdown,$apr_breakdown,$may_breakdown,$jun_breakdown){
		$this->db->query("UPDATE `revenue_forecast` SET `data_type` = '$data_type', `year` = '$year', `focus_company_id` = '$focus_company_id', `data_name` = '$data_name', `total_amount` = '$total_amount', `jul_breakdown` = '$jul_breakdown', `aug_breakdown` = '$aug_breakdown', `sep_breakdown` = '$sep_breakdown', `oct_breakdown` = '$oct_breakdown', `nov_breakdown` = '$nov_breakdown', `dec_breakdown` = '$dec_breakdown', `jan_breakdown` = '$jan_breakdown', `feb_breakdown` = '$feb_breakdown', `mar_breakdown` = '$mar_breakdown', `apr_breakdown` = '$apr_breakdown', `may_breakdown` = '$may_breakdown', `jun_breakdown` = '$jun_breakdown' WHERE `revenue_forecast`.`revenue_forecast_id` = '$revenue_forecast_id' ");
		return $this->db->insert_id();	
	}
/*
	public function get_revenue_forecast($year){
		$query = $this->db->query("SELECT * FROM `revenue_forecast` WHERE `revenue_forecast`.`year` = '$year' ORDER BY `revenue_forecast`.`focus_company_id` ASC");
		return $query;		
	}
*/
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
		$query = $this->db->query("SELECT `users`.`user_id`,`users`.`user_focus_company_id`, CONCAT_WS(' ', `users`.`user_first_name`, `users`.`user_last_name`) AS `user_pm`,`company_details`.`company_name` FROM `users` LEFT JOIN `company_details` ON `company_details`.`company_id` = `users`.`user_focus_company_id` WHERE `users`.`user_role_id` = '3' AND `users`.`user_id` <> '29' AND `users`.`is_active` = '1' ORDER BY `users`.`user_focus_company_id` ASC");
		return $query;
	}


	public function get_maintenance_sales($date_a,$date_b){
		$query = $this->db->query("SELECT `project`.`project_id`,`users`.`user_first_name`,`users`.`user_last_name`, `project`.`focus_company_id`, `users`.`user_id`,
			SUM(`invoice`.`invoiced_amount`) AS `invoiced_amount`
			FROM `project`

			LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
			LEFT JOIN `invoice` ON `invoice`.`project_id`= `project`.`project_id`
			WHERE `project`.`is_active` = '1' AND `invoice`.`is_invoiced` = '1' AND `users`.`user_id` = '29'

			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= '$date_a' 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) < '$date_b' 
			/* with in btwn months*/

			GROUP BY `project`.`focus_company_id`,`project`.`project_manager_id`
			ORDER BY `project`.`focus_company_id` ASC , `users`.`user_first_name` ASC");
		return $query;
	}

	public function get_pm_sales($date_a,$date_b){
		$query = $this->db->query("SELECT `project`.`project_id`,`users`.`user_first_name`,`users`.`user_last_name`, `project`.`focus_company_id`, `users`.`user_id`,
			SUM(`invoice`.`invoiced_amount`) AS `invoiced_amount`
			FROM `project`

			LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
			LEFT JOIN `invoice` ON `invoice`.`project_id`= `project`.`project_id`
			WHERE `project`.`is_active` = '1' AND `invoice`.`is_invoiced` = '1' AND `users`.`user_id` <> '29'

			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= '$date_a' 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) < '$date_b' 
			/* with in btwn months*/

			GROUP BY `project`.`project_manager_id`
			ORDER BY `project`.`focus_company_id` ASC , `users`.`user_first_name` ASC");

		return $query;
	}

	public function look_for_sales($year,$proj_mngr_id,$focus_comp_id,$rev_month){
		$query = $this->db->query("SELECT * FROM `revenue_focus` WHERE `year` = '$year' AND `proj_mngr_id` = '$proj_mngr_id' AND `focus_comp_id` = '$focus_comp_id'");
		return $query;
	}

	public function update_sales($revenue_id,$rev_month,$sales){
		$query = $this->db->query("UPDATE `revenue_focus` SET `$rev_month` = '$sales' WHERE `revenue_focus`.`revenue_id` = '$revenue_id' ");
		return $query;
	}

	public function set_sales($proj_mngr_id, $rev_month, $sales, $focus_comp_id, $year){
		$query = $this->db->query("INSERT INTO `revenue_focus` ( `proj_mngr_id`, `$rev_month`, `focus_comp_id`, `year`) VALUES ( '$proj_mngr_id',  '$sales', '$focus_comp_id', '$year')");
		return $query;
	}

	public function get_sales_focus($year,$company_id=''){

		if($company_id != ''){
			$query = $this->db->query("SELECT SUM(`rev_jan`) + SUM(`rev_feb`) + SUM(`rev_mar`) + SUM(`rev_apr`) + SUM(`rev_may`) + SUM(`rev_jun`) + SUM(`rev_jul`) + SUM(`rev_aug`) + SUM(`rev_sep`) + SUM(`rev_oct`) + SUM(`rev_nov`) + SUM(`rev_dec`) AS `total_sales`
				FROM `revenue_focus`
				WHERE `revenue_focus`.`year` = '$year'
				AND `revenue_focus`.`focus_comp_id` = '$company_id' ");
		}else{
			$query = $this->db->query("SELECT SUM(`rev_jan`) + SUM(`rev_feb`) + SUM(`rev_mar`) + SUM(`rev_apr`) + SUM(`rev_may`) + SUM(`rev_jun`) + SUM(`rev_jul`) + SUM(`rev_aug`) + SUM(`rev_sep`) + SUM(`rev_oct`) + SUM(`rev_nov`) + SUM(`rev_dec`) AS `total_sales`
				FROM `revenue_focus`
				WHERE `revenue_focus`.`year` = '$year' ");
		}

		return $query;
	}


	public function get_sales_focus_pm($year,$company_id,$pm_id){
		$query = $this->db->query("SELECT SUM(`rev_jan`) + SUM(`rev_feb`) + SUM(`rev_mar`) + SUM(`rev_apr`) + SUM(`rev_may`) + SUM(`rev_jun`) + SUM(`rev_jul`) + SUM(`rev_aug`) + SUM(`rev_sep`) + SUM(`rev_oct`) + SUM(`rev_nov`) + SUM(`rev_dec`) AS `total_sales`
			FROM `revenue_focus`
			WHERE `revenue_focus`.`year` = '$year'
			AND `revenue_focus`.`proj_mngr_id` = '$pm_id' 
			AND `revenue_focus`.`focus_comp_id` = '$company_id'");
		return $query;
	}


	public function get_sales_focus_month($year){
		$query = $this->db->query("SELECT 
			SUM(`rev_jan`) AS `sum_jan`,SUM(`rev_feb`) AS `sum_feb`,SUM(`rev_mar`) AS `sum_mar`,SUM(`rev_apr`) AS `sum_apr`,SUM(`rev_may`) AS `sum_may`,SUM(`rev_jun`) AS `sum_jun`,
			SUM(`rev_jul`) AS `sum_jul`,SUM(`rev_aug`) AS `sum_aug`,SUM(`rev_sep`) AS `sum_sep`,SUM(`rev_oct`) AS `sum_oct`,SUM(`rev_nov`) AS `sum_nov`, SUM(`rev_dec`)  AS `sum_dec`
			FROM `revenue_focus`
			WHERE `revenue_focus`.`year` = '$year'");
		return $query;
	}


	public function get_old_month_sales($rev_month,$year){
		$query = $this->db->query("SELECT SUM(`$rev_month`) AS `sum_old_month` FROM `revenue_focus` WHERE `revenue_focus`.`year` = '$year'");
		return $query;
	}


/*

#for maintenance only


*/




/*


#for PM non maintenance
SELECT `project`.`project_id`,`users`.`user_first_name`,`users`.`user_last_name`, `project`.`focus_company_id`, `users`.`user_id`,
SUM(`invoice`.`invoiced_amount`) AS `invoiced_amount`
FROM `project`

LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
LEFT JOIN `invoice` ON `invoice`.`project_id`= `project`.`project_id`
WHERE `project`.`is_active` = '1' AND `invoice`.`is_invoiced` = '1' AND `users`.`user_id` <> '29'

AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= '1443672000' 
AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) < '1446350400' 

# with in btwn months

GROUP BY `project`.`project_manager_id`
ORDER BY `project`.`focus_company_id` ASC , `users`.`user_first_name` ASC


*/



/*
#for focus company

SELECT  `project`.`focus_company_id`, 
SUM(`invoice`.`invoiced_amount`) AS `invoiced_amount`
FROM `project`

LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
LEFT JOIN `invoice` ON `invoice`.`project_id`= `project`.`project_id`
WHERE `project`.`is_active` = '1' AND `invoice`.`is_invoiced` = '1' AND `users`.`user_id` <> '29'

AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= '1443672000' 
AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) < '1446350400' 

# with in btwn months

GROUP BY `project`.`focus_company_id`
ORDER BY `project`.`focus_company_id` ASC 

*/


}