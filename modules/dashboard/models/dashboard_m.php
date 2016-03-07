<?php

class Dashboard_m extends CI_Model{
	
	public function insert_revenue_forecast_y($forecast_label, $total, $year, $forecast_jan, $forecast_feb, $forecast_mar, $forecast_apr, $forecast_may, $forecast_jun, $forecast_jul, $forecast_aug, $forecast_sep, $forecast_oct, $forecast_nov, $forecast_dec){
		$this->db->query("INSERT INTO `revenue_forecast` (`forecast_label`, `total`, `year`, `forecast_jan`, `forecast_feb`, `forecast_mar`, `forecast_apr`, `forecast_may`, `forecast_jun`, `forecast_jul`, `forecast_aug`, `forecast_sep`, `forecast_oct`, `forecast_nov`, `forecast_dec`)
			VALUES ('$forecast_label', '$total', '$year', '$forecast_jan', '$forecast_feb',  '$forecast_mar', '$forecast_apr', '$forecast_may', '$forecast_jun', '$forecast_jul', '$forecast_aug', '$forecast_sep', '$forecast_oct', '$forecast_nov', '$forecast_dec')");
		return $this->db->insert_id();	
	}

	public function insert_revenue_forecast($comp_id, $pm_id, $other, $forecast_percent, $year, $revenue_forecast_id){
		$this->db->query("INSERT INTO `revenue_forecast_individual` (`comp_id`, `pm_id`, `other`, `forecast_percent`, `year`, `revenue_forecast_id`) VALUES ('$comp_id', '$pm_id', '$other', '$forecast_percent', '$year', '$revenue_forecast_id')");
		return $this->db->insert_id();	
	}

	public function fetch_revenue_forecast($id=''){
		if($id != ''){
			$query = $this->db->query("SELECT * FROM `revenue_forecast`
				WHERE `revenue_forecast`.`is_active` = '1' AND `revenue_forecast`.`revenue_forecast_id` = '$id'
				ORDER BY `revenue_forecast`.`revenue_forecast_id` ");

		}else{
			$query = $this->db->query("SELECT * FROM `revenue_forecast`
				WHERE `revenue_forecast`.`is_active` = '1'
				ORDER BY `revenue_forecast`.`revenue_forecast_id` ");
		}
		return $query;
	}

	public function fetch_individual_forecast($revenue_forecast_id){
		$query = $this->db->query("SELECT `revenue_forecast_individual`.*, CONCAT( `users`.`user_first_name`,' ',`users`.`user_last_name`) AS `pm_name`,`company_details`.`company_name`
			FROM `revenue_forecast_individual`
			LEFT JOIN `company_details` ON `company_details`.`company_id` = `revenue_forecast_individual`.`comp_id`
			LEFT JOIN `users` ON `users`.`user_id` = `revenue_forecast_individual`.`pm_id`
			WHERE `revenue_forecast_individual`.`revenue_forecast_id` = '$revenue_forecast_id'
			ORDER BY `revenue_forecast_individual`.`revenue_forecast_individual_id`  ASC");
		return $query;
	}

	public function deactivate_stored_revenue_forecast($id){
		$query = $this->db->query("UPDATE `revenue_forecast` SET `is_active` = '0' WHERE `revenue_forecast`.`revenue_forecast_id` = '$id'");
		return $query;		
	}

	public function set_primary_revenue_forecast($id,$year){
		$this->db->query("UPDATE `revenue_forecast` SET `revenue_forecast`.`is_primary` = '0' WHERE `revenue_forecast`.`year` = '$year' ");
		$this->db->query("UPDATE `revenue_forecast` SET `revenue_forecast`.`is_primary` = '1' WHERE `revenue_forecast`.`revenue_forecast_id` = '$id' ");
	}
	
	public function update_revenue_forecast($revenue_forecast_id, $forecast_label, $total, $forecast_jan, $forecast_feb, $forecast_mar, $forecast_apr, $forecast_may, $forecast_jun, $forecast_jul, $forecast_aug, $forecast_sep, $forecast_oct, $forecast_nov, $forecast_dec ){
		$this->db->query("UPDATE `revenue_forecast` SET `forecast_label` = '$forecast_label', `total` = '$total', `forecast_jan` = '$forecast_jan', `forecast_feb` = '$forecast_feb', `forecast_mar` = '$forecast_mar', `forecast_apr` = '$forecast_apr', `forecast_may` = '$forecast_may', `forecast_jun` = '$forecast_jun', `forecast_jul` = '$forecast_jul', `forecast_aug` = '$forecast_aug', `forecast_sep` = '$forecast_sep', `forecast_oct` = '$forecast_oct', `forecast_nov` = '$forecast_nov', `forecast_dec` = '$forecast_dec' WHERE `revenue_forecast`.`revenue_forecast_id` = '$revenue_forecast_id' ");
		return $this->db->insert_id();	
	}

	public function update_revenue_forecast_indv($forecast_percent,$revenue_forecast_individual_id){
		$this->db->query("UPDATE `revenue_forecast_individual` SET `forecast_percent` = '$forecast_percent' WHERE `revenue_forecast_individual`.`revenue_forecast_individual_id` = '$revenue_forecast_individual_id' ");
		return $this->db->insert_id();	
	}

	public function fetch_revenue_by_year($year){
		$query = $this->db->query("SELECT  `revenue_focus`.*, CONCAT_WS(' ',`users`.`user_first_name`,`users`.`user_last_name`) AS `pm_name` , `revenue_focus`.`focus_comp_id`
			FROM `revenue_focus`
			LEFT JOIN `users` ON `users`.`user_id` = `revenue_focus`.`proj_mngr_id`
			WHERE `revenue_focus`.`year` = '$year'
			ORDER BY `revenue_focus`.`focus_comp_id` ASC, `revenue_focus`.`proj_mngr_id` ASC");
		return $query;
	}

	public function fetch_forecast($year,$is_primary = '',$forecast_id = ''){

		if($is_primary != ''){
			$query = $this->db->query("SELECT * FROM `revenue_forecast` WHERE `revenue_forecast`.`is_primary` = '$is_primary' AND `revenue_forecast`.`year` = '$year' ");
		}

		return $query;
	}

	public function fetch_indv_comp_forecast($year){
		$query = $this->db->query("SELECT  `revenue_forecast`.*,`revenue_forecast_individual`.`forecast_percent`,`company_details`.`company_name` 
			FROM `revenue_forecast`
			LEFT JOIN `revenue_forecast_individual` ON `revenue_forecast_individual`.`revenue_forecast_id` = `revenue_forecast`.`revenue_forecast_id`
			LEFT JOIN `company_details` ON `company_details`.`company_id` = `revenue_forecast_individual`.`comp_id`
			WHERE `revenue_forecast`.`is_primary` = '1'
			AND `revenue_forecast_individual`.`pm_id` = '0'
			AND `revenue_forecast`.`year` = '$year'");
		return $query;		
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


	public function get_outstanding_invoice($date_a,$date_b,$user_id='',$comp_id=''){

		if($user_id!='' && $comp_id!=''){

			$query = $this->db->query("SELECT `project`.`project_id`,`users`.`user_first_name`,`users`.`user_last_name`, `project`.`focus_company_id`, `users`.`user_id`, `invoice`.`progress_percent`,`invoice`.`label`,`project_cost_total`.`variation_total`,`project_cost_total`.`work_quoted_total`,`project`.`project_total`,`invoice`.`invoice_date_req`
			FROM `project`
			LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
			LEFT JOIN `invoice` ON `invoice`.`project_id`= `project`.`project_id`
            LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id`
			WHERE `project`.`is_active` = '1' 
            AND `invoice`.`is_invoiced` = '0' 
            AND `project`.`job_date` <> ''
			AND  `users`.`user_id` = '$user_id'
			AND `project`.`focus_company_id` = '$comp_id' 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) >= '$date_a'
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) < '$date_b' ");

		}else{

			$query = $this->db->query("SELECT `users`.`user_id`,`project`.`focus_company_id`
				FROM `project`
				LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
				LEFT JOIN `invoice` ON `invoice`.`project_id`= `project`.`project_id`
				LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id`
				WHERE `project`.`is_active` = '1' 
				AND `invoice`.`is_invoiced` = '0' 
				AND `project`.`job_date` <> ''
				AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) >= '$date_a'
				AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) < '$date_b'  
				GROUP BY `users`.`user_id`,`project`.`focus_company_id`
				ORDER BY `users`.`user_id` ASC");
		}

		return $query;
	}



/*
	public function get_outstanding_advanced($date_a,$date_b,$user_id='',$focus_company_id=''){

		if($user_id!=''){

			$query = $this->db->query("SELECT `project`.`project_id`,`users`.`user_first_name`,`users`.`user_last_name`, `project`.`focus_company_id`, `users`.`user_id`, `invoice`.`progress_percent`,`invoice`.`label`,`project_cost_total`.`variation_total`,`project_cost_total`.`work_quoted_total`, `invoice`.`invoice_date_req`, `users`.`user_id`, `project`.`focus_company_id`,`project`.`project_total`
				FROM `project`
				LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
				LEFT JOIN `invoice` ON `invoice`.`project_id`= `project`.`project_id`
				LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id`
				WHERE `project`.`is_active` = '1' 
				AND `invoice`.`is_invoiced` = '0' 
				AND `project`.`job_date` <> ''
			 	AND  `users`.`user_id` = '$user_id'
			 	AND `project`.`focus_company_id` = '$focus_company_id' 
				AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) >= '$date_a' 
				AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) < '$date_b'  
				ORDER BY `users`.`user_id` ASC");

		}else{
			$query = $this->db->query("SELECT `project`.`project_id`,`users`.`user_first_name`,`users`.`user_last_name`, `project`.`focus_company_id`, `users`.`user_id`, `invoice`.`progress_percent`,`invoice`.`label`,`project_cost_total`.`variation_total`,`project_cost_total`.`work_quoted_total`, `invoice`.`invoice_date_req`, `users`.`user_id`, `project`.`focus_company_id`

			FROM `project`
			LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
			LEFT JOIN `invoice` ON `invoice`.`project_id`= `project`.`project_id`
            LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id`
			WHERE `project`.`is_active` = '1' 
            AND `invoice`.`is_invoiced` = '0' 
            AND `project`.`job_date` <> ''
				AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) >= '$date_a' 
				AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) < '$date_b'  
            GROUP BY `users`.`user_id`");

		}



		return $query;
	}
*/

	public function get_maintenance_sales($date_a,$date_b){
		$query = $this->db->query("SELECT `project`.`project_id`,`users`.`user_first_name`,`users`.`user_last_name`, `project`.`focus_company_id`, `users`.`user_id`,
			SUM(`invoice`.`invoiced_amount`) AS `invoiced_amount`
			FROM `project`

			LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
			LEFT JOIN `invoice` ON `invoice`.`project_id`= `project`.`project_id`
			WHERE `project`.`is_active` = '1' AND  `project`.`job_date` <> '' AND `invoice`.`is_invoiced` = '1' AND `users`.`user_id` = '29'

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
			WHERE `project`.`is_active` = '1' AND`project`.`job_date` <> '' AND `invoice`.`is_invoiced` = '1' AND `users`.`user_id` <> '29'

			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= '$date_a' 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) < '$date_b' 
			/* with in btwn months*/

			GROUP BY `project`.`project_manager_id`, `project`.`focus_company_id`
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
		return $this->db->insert_id();
	}



	public function check_outstanding_set($proj_mngr_id, $out_month, $sales, $focus_comp_id, $year){

		$query = $this->db->query(" SELECT * FROM `outstanding_focus` 
			WHERE `outstanding_focus`.`proj_mngr_id` = '$proj_mngr_id' 
			AND `outstanding_focus`.`focus_comp_id` = '$focus_comp_id' 
			AND `outstanding_focus`.`year` = '$year' ");

		if($query->num_rows >= 1){

			$query = $this->db->query("UPDATE `outstanding_focus` SET `$out_month` = '$sales' 
				WHERE `outstanding_focus`.`focus_comp_id` = '$focus_comp_id' 
				AND `outstanding_focus`.`year` = '$year' 
				AND `outstanding_focus`.`proj_mngr_id` = '$proj_mngr_id' ");
		}else{

			$query = $this->db->query("INSERT INTO `outstanding_focus` (`proj_mngr_id`, `$out_month`, `focus_comp_id`, `year`) 
				VALUES ('$proj_mngr_id', '$sales', '$focus_comp_id', '$year')");

		}

	}

/*

	public function set_outstanding($proj_mngr_id, $out_month, $sales, $focus_comp_id, $year){
		$query = $this->db->query("INSERT INTO `outstanding_focus` (`proj_mngr_id`, `$out_month`, `focus_comp_id`, `year`) VALUES ('$proj_mngr_id',  '$sales', '$focus_comp_id', '$year')");
		return $this->db->insert_id();
	}


	public function update_outstanding($proj_mngr_id, $out_month, $outstanding, $focus_comp_id, $year){
		$query = $this->db->query("UPDATE `outstanding_focus` SET `$out_month` = '$outstanding' WHERE `outstanding_focus`.`focus_comp_id` = '$focus_comp_id' AND `outstanding_focus`.`year` = '$year' AND `outstanding_focus`.`proj_mngr_id` = '$proj_mngr_id' ");
		return $query;
	}
*/

	public function fetch_pms_month_sales($rev_month,$year){
		$query = $this->db->query("SELECT `revenue_focus`.`$rev_month` AS `sales_month`, CONCAT( `users`.`user_first_name`,' ',`users`.`user_last_name`) AS `pm_name` 
			FROM `revenue_focus` 
			LEFT JOIN `users` ON `users`.`user_id` = `revenue_focus`.`proj_mngr_id` 
			WHERE `revenue_focus`.`year` = '$year' AND `revenue_focus`.`$rev_month` > 0 ORDER BY `revenue_focus`.`rev_feb` DESC");
		return $query;
	}

	public function fetch_comp_month_sales($rev_month,$year){
		$query = $this->db->query("SELECT `company_details`.`company_name` , SUM(`revenue_focus`.`$rev_month`) AS `total_sales`
			FROM `revenue_focus`
			LEFT JOIN `company_details` ON `company_details`.`company_id` = `revenue_focus`.`focus_comp_id`
			WHERE `revenue_focus`.`year` = '$year'
			GROUP BY `revenue_focus`.`focus_comp_id`");
		return $query;
	}


	public function fetch_comp_month_outs($out_month,$year){
		$query = $this->db->query("SELECT `company_details`.`company_name` , SUM(`outstanding_focus`.`$out_month`) AS `total_outstanding`
			FROM `outstanding_focus`
			LEFT JOIN `company_details` ON `company_details`.`company_id` = `outstanding_focus`.`focus_comp_id`
			WHERE `outstanding_focus`.`year` = '$year'
			GROUP BY `outstanding_focus`.`focus_comp_id`");
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

	public function get_sales_focus_company($year,$company_id=''){
		if($company_id != ''){
			$query = $this->db->query("SELECT SUM(`rev_jan`) AS `rev_jan`,SUM(`rev_feb`) AS `rev_feb`,SUM(`rev_mar`) AS `rev_mar`,SUM(`rev_apr`) AS `rev_apr`,SUM(`rev_may`) AS `rev_may`,SUM(`rev_jun`) AS `rev_jun`,SUM(`rev_jul`) AS `rev_jul`,SUM(`rev_aug`) AS `rev_aug`,SUM(`rev_sep`) AS `rev_sep`,SUM(`rev_oct`) AS `rev_oct`,SUM(`rev_nov`) AS `rev_nov`,SUM(`rev_dec`) AS `rev_dec`,`company_details`.`company_name`
				FROM `revenue_focus`
				LEFT JOIN `company_details` ON `company_details`.`company_id` = `revenue_focus`.`focus_comp_id`
				WHERE `revenue_focus`.`year` = '$year'
				GROUP BY `revenue_focus`.`focus_comp_id`");
		}else{
			$query = $this->db->query("SELECT SUM(`rev_jan`) AS `rev_jan`, SUM(`rev_feb`) AS `rev_feb`, SUM(`rev_mar`) AS `rev_mar`, SUM(`rev_apr`) AS `rev_apr`, SUM(`rev_may`) AS `rev_may`, SUM(`rev_jun`) AS `rev_jun`, SUM(`rev_jul`) AS `rev_jul`, SUM(`rev_aug`) AS `rev_aug`, SUM(`rev_sep`) AS `rev_sep`, SUM(`rev_oct`) AS `rev_oct`, SUM(`rev_nov`) AS `rev_nov`, SUM(`rev_dec`) AS `rev_dec` 
				FROM `revenue_focus` 
				WHERE `revenue_focus`.`year` = '$year' ");
		}
		return $query;
	}

	public function get_focus_outstanding($year,$company_id=''){
		if($company_id != ''){
			$query = $this->db->query("SELECT SUM(`out_jan`) AS `out_jan`,SUM(`out_feb`) AS `out_feb`,SUM(`out_mar`) AS `out_mar`,SUM(`out_apr`) AS `out_apr`,SUM(`out_may`) AS `out_may`,SUM(`out_jun`) AS `out_jun`,SUM(`out_jul`) AS `out_jul`,SUM(`out_aug`) AS `out_aug`,SUM(`out_sep`) AS `out_sep`,SUM(`out_oct`) AS `out_oct`,SUM(`out_nov`) AS `out_nov`,SUM(`out_dec`) AS `out_dec`,`company_details`.`company_name`
				FROM `outstanding_focus`
				LEFT JOIN `company_details` ON `company_details`.`company_id` = `outstanding_focus`.`focus_comp_id`
				WHERE `outstanding_focus`.`year` = '$year'
				GROUP BY `outstanding_focus`.`focus_comp_id`");

		}else{
			$query = $this->db->query("SELECT SUM(`out_jan`) AS `out_jan`, SUM(`out_feb`) AS `out_feb`, SUM(`out_mar`) AS `out_mar`, SUM(`out_apr`) AS `out_apr`, SUM(`out_may`) AS `out_may`, SUM(`out_jun`) AS `out_jun`, SUM(`out_jul`) AS `out_jul`, SUM(`out_aug`) AS `out_aug`, SUM(`out_sep`) AS `out_sep`, SUM(`out_oct`) AS `out_oct`, SUM(`out_nov`) AS `out_nov`, SUM(`out_dec`) AS `out_dec` 
				FROM `outstanding_focus` 
				WHERE `outstanding_focus`.`year` = '$year' ");
		}
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


	public function get_sales_focus_yearly($year){
		$query = $this->db->query("SELECT SUM(`rev_jan`) + SUM(`rev_feb`) + SUM(`rev_mar`) + SUM(`rev_apr`) + SUM(`rev_may`) + SUM(`rev_jun`) + SUM(`rev_jul`) + SUM(`rev_aug`) + SUM(`rev_sep`) + SUM(`rev_oct`) + SUM(`rev_nov`) + SUM(`rev_dec`) AS `total_sales`
			FROM `revenue_focus` WHERE `revenue_focus`.`year` = '$year'");
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