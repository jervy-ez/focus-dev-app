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

	public function fetch_pm_forecast_details($year,$forecast_id,$pm_id,$comp_id=''){
		$query = $this->db->query("SELECT `revenue_forecast_individual`.* , `rev_for_indvl`.`forecast_percent` AS `f_comp_forecast_percent` , `rev_for_indvl`.`comp_id` 
			FROM `revenue_forecast_individual` 
			LEFT JOIN `revenue_forecast_individual` `rev_for_indvl` ON `rev_for_indvl`.`comp_id` = `revenue_forecast_individual`.`comp_id` 
			
			WHERE `revenue_forecast_individual`.`year` = '$year' 
			AND `revenue_forecast_individual`.`revenue_forecast_id` = '$forecast_id' 

			


			".($comp_id == '' ? " AND `revenue_forecast_individual`.`pm_id` = '$pm_id'  " :  " AND `revenue_forecast_individual`.`pm_id` = '0'    AND `revenue_forecast_individual`.`comp_id` = '$comp_id'   "  )."





			AND `rev_for_indvl`.`year` = '$year' 
			AND `rev_for_indvl`.`revenue_forecast_id` = '$forecast_id' 

			GROUP BY `rev_for_indvl`.`comp_id` ORDER BY `revenue_forecast_individual`.`pm_id` ASC");

		return $query;
	}


	public function get_maintenance_counts($date_a,$date_b){
		$query = $this->db->query("SELECT AVG(a.ress) AS `num_projects` 
			FROM (SELECT COUNT(`project`.`project_id`) AS `ress` FROM `project`
				WHERE `project`.`is_active` = '1' AND `project`.`job_category` = 'Maintenance' AND `project`.`job_date` != ''

				AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`job_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
				AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`job_date`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )

			)AS a
		CROSS JOIN `project`  
		GROUP BY `project`.`project_id` LIMIT 1

		");
			
		/*
				$query = $this->db->query("SELECT  COUNT(`project`.`project_id`) AS `num_projects` FROM `project`
					WHERE `project`.`is_active` = '1' AND `project`.`job_category` = 'Maintenance' AND `project`.`job_date` != ''
					AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`job_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
					AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`job_date`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )");


		*/
		return $query;
	}

	public function list_maintenance_projects_jb($date_a,$date_b){
		$query = $this->db->query(" SELECT `project`.`project_id`,`project`.`job_date`
			FROM `project`
			WHERE `project`.`is_active` = '1' AND `project`.`job_category` = 'Maintenance' AND `project`.`job_date` != ''
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`job_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`job_date`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )");
		return $query;
	}



	public function get_weekly_leaves($date_a,$date_b,$custom_q){

		$q = "SELECT `leave_request`.`start_day_of_leave`, `leave_request`.`end_day_of_leave`, `leave_request`.`user_id`,`leave_request`.`leave_request_id`,`leave_request`.`partial_day`,


		


		IF(`leave_request`.`holiday_leave` > '0' , '0'  , `leave_request`.`leave_type_id` ) AS `emp_leave_type_id`





		,`leave_request`.`holiday_leave`  ,`leave_request`.`total_days_away`  
, `leave_allocation`.`no_hrs_of_work` 

		FROM `leave_request` 

INNER JOIN `leave_allocation`  ON  `leave_allocation`.`user_id` = `leave_request`.`user_id`
INNER JOIN `users` ON `users`.`user_id` = `leave_request`.`user_id`

WHERE `leave_request`.`is_approve` = '1' 
		AND(
			(
				`leave_request`.`start_day_of_leave` >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y'))
			AND
				`leave_request`.`start_day_of_leave` < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y')) 
			) 

			OR ( `leave_request`.`end_day_of_leave` >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y'))
				AND `leave_request`.`end_day_of_leave` < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y')
				)   
			)
		)";

	//	if($leave_id > 0){
	//		$q .= "  AND  `leave_request`.`holiday_leave` = '0' ";
	//	}else{
	//		$q .= " AND  `leave_request`.`holiday_leave` = '1' ";

		//	$q .= " AND `leave_request`.`leave_type_id` = '6' AND `leave_request`.`user_id` = '37' ";
	//	}

		$q .= $custom_q;

		$q .=" AND `leave_allocation`.`is_active` = '1' AND  `leave_request`.`is_disabled` = '0' ORDER BY `leave_request`.`user_id` ASC, `leave_request`.`leave_type_id` ASC   ";

		$query = $this->db->query($q);
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

	public function reset_revenue($month,$year){
		$query = $this->db->query(" UPDATE `revenue_focus` SET `$month` = '0.00' WHERE `revenue_focus`.`year` = '$year' ");
		return $query;
	}

	public function fetch_pm_sales_year($year,$pm_id = ''){

if($pm_id != ''){

			$query = $this->db->query("SELECT CONCAT_WS(' ', `users`.`user_first_name`, `users`.`user_last_name`) AS `user_pm_name`,
`revenue_focus`.`focus_comp_id`,`revenue_focus`.`year`,`revenue_focus`.`revenue_id`,`revenue_focus`.`proj_mngr_id`,
SUM(`revenue_focus`.`rev_jan`) AS `rev_jan`,SUM(`revenue_focus`.`rev_feb`) AS `rev_feb`,
SUM(`revenue_focus`.`rev_mar`) AS `rev_mar`,SUM(`revenue_focus`.`rev_apr`) AS `rev_apr`,
SUM(`revenue_focus`.`rev_may`) AS `rev_may`,SUM(`revenue_focus`.`rev_jun`) AS `rev_jun`,
SUM(`revenue_focus`.`rev_jul`) AS `rev_jul`,SUM(`revenue_focus`.`rev_aug`) AS `rev_aug`,
SUM(`revenue_focus`.`rev_sep`) AS `rev_sep`,SUM(`revenue_focus`.`rev_oct`) AS `rev_oct`,
SUM(`revenue_focus`.`rev_nov`) AS `rev_nov`,SUM(`revenue_focus`.`rev_dec`) AS `rev_dec`


				FROM `revenue_focus` 
				LEFT JOIN `users` ON `users`.`user_id` = `revenue_focus`.`proj_mngr_id`
				WHERE `revenue_focus`.`year` = '$year'
				AND  `revenue_focus`.`proj_mngr_id` = '$pm_id'
				GROUP BY  `revenue_focus`.`proj_mngr_id`
				ORDER BY `revenue_focus`.`proj_mngr_id` ASC");
			return $query;		
	}else{
			$query = $this->db->query("SELECT `revenue_focus`.* , CONCAT_WS(' ', `users`.`user_first_name`, `users`.`user_last_name`) AS `user_pm_name`
				FROM `revenue_focus` 
				LEFT JOIN `users` ON `users`.`user_id` = `revenue_focus`.`proj_mngr_id`
				WHERE `revenue_focus`.`year` = '$year' 
				ORDER BY `revenue_focus`.`proj_mngr_id` ASC  ");
		}

		return $query;
	}

	public function get_focus_comp_forecast($year){
		$query = $this->db->query("SELECT `revenue_forecast`.`total`,`revenue_forecast_individual`.* 
			FROM `revenue_forecast`
			LEFT JOIN `revenue_forecast_individual` ON `revenue_forecast_individual`.`revenue_forecast_id` = `revenue_forecast`.`revenue_forecast_id`
			WHERE `revenue_forecast`.`is_active` = '1'
			AND `revenue_forecast`.`is_primary` = '1' AND  `revenue_forecast_individual`.`pm_id` = '0'
			AND `revenue_forecast`.`year` = '$year' AND  `revenue_forecast_individual`.`year` = '$year'
			AND `revenue_forecast_individual`.`forecast_percent` > '0' ");
		return $query;
	}

	public function get_pm_forecast($year,$pm_id=''){
		$query = $this->db->query("SELECT `revenue_forecast`.*,`revenue_forecast_individual`.* , CONCAT_WS(' ', `users`.`user_first_name`, `users`.`user_last_name`) AS `user_pm_name`
			FROM `revenue_forecast`
			LEFT JOIN `revenue_forecast_individual` ON `revenue_forecast_individual`.`revenue_forecast_id` = `revenue_forecast`.`revenue_forecast_id`
			LEFT JOIN `users` ON `users`.`user_id` = `revenue_forecast_individual`.`pm_id`
			WHERE `revenue_forecast`.`is_active` = '1'
			AND `revenue_forecast`.`is_primary` = '1' AND  `revenue_forecast_individual`.`pm_id` > '0'
			".($pm_id != '' ? " AND  `revenue_forecast_individual`.`pm_id` = '$pm_id' " : "")."
			AND `revenue_forecast`.`year` = '$year' AND  `revenue_forecast_individual`.`year` = '$year'
			AND `revenue_forecast_individual`.`forecast_percent` > '0' ");
		return $query;
	}

	public function fetch_pm_sales_old_year($year,$pm_id=''){
		$query = $this->db->query(" SELECT `revenue_focus`.* , CONCAT_WS(' ', `users`.`user_first_name`, `users`.`user_last_name`) AS `user_pm_name`,
SUM(`revenue_focus`.`rev_jan`) AS `rev_jn`,SUM(`revenue_focus`.`rev_feb`) AS `rev_fb`,
SUM(`revenue_focus`.`rev_mar`) AS `rev_mr`,SUM(`revenue_focus`.`rev_apr`) AS `rev_ar`,
SUM(`revenue_focus`.`rev_may`) AS `rev_my`,SUM(`revenue_focus`.`rev_jun`) AS `rev_jn`,
SUM(`revenue_focus`.`rev_jul`) AS `rev_jl`,SUM(`revenue_focus`.`rev_aug`) AS `rev_ag`,
SUM(`revenue_focus`.`rev_sep`) AS `rev_sp`,SUM(`revenue_focus`.`rev_oct`) AS `rev_ot`,
SUM(`revenue_focus`.`rev_nov`) AS `rev_nv`,SUM(`revenue_focus`.`rev_dec`) AS `rev_dc`

			FROM `revenue_focus` 
			LEFT JOIN `users` ON `users`.`user_id` = `revenue_focus`.`proj_mngr_id`
			WHERE `revenue_focus`.`year` = '$year'
			".($pm_id != '' ? " AND  `revenue_focus`.`proj_mngr_id` = '$pm_id' " : "")."
            GROUP BY  `revenue_focus`.`proj_mngr_id`
            ORDER BY `revenue_focus`.`proj_mngr_id` ASC  ");
		return $query;
	}

	public function fetch_pm_oustanding_year($year){
		$query = $this->db->query(" SELECT `outstanding_focus`.* , CONCAT_WS(' ', `users`.`user_first_name`, `users`.`user_last_name`) AS `user_pm_name`
			FROM `outstanding_focus` 
			LEFT JOIN `users` ON `users`.`user_id` = `outstanding_focus`.`proj_mngr_id`
			WHERE `outstanding_focus`.`year` = '$year' ORDER BY `outstanding_focus`.`proj_mngr_id` ASC   ");
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
			WHERE `invoice`.`is_invoiced` = '1' AND `project`.`is_active` = '1' AND  `project`.`job_category` != 'Company'
			AND  `project`.`project_manager_id` = '$project_manager_id'
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= '$date_a' AND  UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) < '$date_b'
			GROUP BY `project`.`project_manager_id` ");
		return $query;
	}

	public function get_pm_names(){
		$query = $this->db->query("SELECT `users`.`user_id`,`users`.`user_focus_company_id`, CONCAT_WS(' ', `users`.`user_first_name`, `users`.`user_last_name`) AS `user_pm`,`company_details`.`company_name` 
			FROM `users` 
			LEFT JOIN `company_details` ON `company_details`.`company_id` = `users`.`user_focus_company_id` 
			WHERE `users`.`is_active` = '1'  AND `users`.`user_id` <> '29' AND   ( `users`.`user_role_id` = '3' OR  `users`.`user_role_id` = '20')  ORDER BY `users`.`user_focus_company_id` ASC");
		return $query;
	}

	public function count_maintenance_projects($date_a,$date_b){
		$query = $this->db->query("SELECT  COUNT( `project`.`project_id`) AS `prj_numbers` , SUM(`project`.`project_total`) + SUM( `project_cost_total`.`variation_total`)  AS `total_proj_exgst`
			FROM `project`  
			LEFT JOIN  `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id`
			WHERE `project`.`job_category` = 'Maintenance'  
			AND `project`.`job_date` != ''  
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) ");
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
            AND `project`.`job_date` <> '' AND  `project`.`job_category` != 'Company'
			AND  `users`.`user_id` = '$user_id'
			AND `project`.`focus_company_id` = '$comp_id' 

			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )

			");

		}else{

			$query = $this->db->query("SELECT `users`.`user_id`,`project`.`focus_company_id`
				FROM `project`
				LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
				LEFT JOIN `invoice` ON `invoice`.`project_id`= `project`.`project_id`
				LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id`
				WHERE `project`.`is_active` = '1'  AND  `project`.`job_category` != 'Company'
				AND `invoice`.`is_invoiced` = '0' 
				AND `project`.`job_date` <> ''

			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 


				GROUP BY `users`.`user_id`,`project`.`focus_company_id`
				ORDER BY `users`.`user_id` ASC");
		}

		return $query;
	}


	public function get_site_labour_hrs($currect_date,$date_project_created = '',$custom=''){

		$query = $this->db->query("SELECT  UNIX_TIMESTAMP( STR_TO_DATE(`project_labour_sched`.`labour_sched_date`, '%Y-%m-%d') ) AS `date_set`, SUM(`project_labour_sched`.`number_of_hrs`) AS `time` , `project`.`focus_company_id`,`states`.`shortname`,`address_general`.`state_id`
			FROM `project_labour_sched`
			
            
            LEFT JOIN `project` ON `project`.`project_id` = `project_labour_sched`.`project_id`
            LEFT JOIN `address_detail` ON `address_detail`.`address_detail_id` = `project`.`address_id`
			LEFT JOIN `address_general` ON `address_general`.`general_address_id` =  `address_detail`.`general_address_id`
			LEFT JOIN `states` ON `states`.`id` = `address_general`.`state_id`

			 /* YYYY-MM-DD */
			WHERE   UNIX_TIMESTAMP( STR_TO_DATE(`project_labour_sched`.`labour_sched_date`, '%Y-%m-%d') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$currect_date', '%Y-%m-%d') )


			 
		".($date_project_created != '' ? "  AND   UNIX_TIMESTAMP( STR_TO_DATE(`project`.`project_date`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_project_created', '%Y-%m-%d') )     " : " AND `project`.`is_paid` = '0' " )." 



			AND   `project`.`job_date` <> '' AND `project`.`is_active` = '1'  AND `project`.`unaccepted_date` = '' 
			 AND `project`.`job_category` != 'Design Works'
			 AND `project`.`job_category` != 'Minor Works'
			 AND `project`.`job_category` != 'Maintenance'
			 AND `project`.`job_category` != 'Company'
			 AND `project`.`job_category` != 'Joinery Only'

			 ".$custom."


			GROUP BY `project`.`project_id`");
		return $query;
	}

		public function get_all_states(){
			$query = $this->db->query(" SELECT * FROM `states` ");
			return $query;
		}


		public function fetch_pms_year($year,$is_active=0,$custom=''){
			$query = $this->db->query(" SELECT `project`.`project_manager_id`,`users`.*  FROM `project`
				LEFT JOIN `users` ON`users`.`user_id` = `project`.`project_manager_id`

				WHERE `project`.`is_active` = '1' AND  `project`.`project_date` LIKE '%$year' AND `project`.`job_category` <> 'Company'
				".$custom."
				".($is_active == 1 ? " AND `users`.`is_active` = '1' " : " ")." GROUP BY `project`.`project_manager_id` ");
			return $query;
		}

	public function get_all_active_projects($old_date='',$old_date_b='',$est_id = ''){

		if($old_date != ''){


			$query = $this->db->query("SELECT `project`.`project_id`,`states`.`shortname`,`address_general`.`state_id`,`project`.`unaccepted_date`,`project`.`date_site_commencement`,`project`.`job_date`,`project`.`is_paid`,`project`.`job_category`,`project`.`project_estiamator_id`,`project`.`project_manager_id`,`project_cost_total`.`work_estimated_total`,`project`.`focus_company_id`,`project_cost_total`.`variation_total`,`project`.`install_time_hrs`,`project`.`project_total`,`project`.`budget_estimate_total`   
			FROM `project` 
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id`
			LEFT JOIN `address_detail` ON `address_detail`.`address_detail_id` = `project`.`address_id`
			LEFT JOIN `address_general` ON `address_general`.`general_address_id` =  `address_detail`.`general_address_id`
			LEFT JOIN `states` ON `states`.`id` = `address_general`.`state_id`
			WHERE `project`.`is_active` = '1' AND  `project`.`job_category` != 'Company'
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`job_date`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$old_date', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`project_date`, '%d/%m/%Y') ) > UNIX_TIMESTAMP( STR_TO_DATE('$old_date', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`project_date`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$old_date_b', '%d/%m/%Y') ) "); 





		}else{
			$query = $this->db->query("SELECT `project`.`project_id`,`states`.`shortname`,`address_general`.`state_id`,`project`.`unaccepted_date`,`project`.`date_site_commencement`,`project`.`job_date`,`project`.`is_paid`,`project`.`job_category`,`project`.`project_estiamator_id`,`project`.`project_manager_id`,`project_cost_total`.`work_estimated_total`,`project`.`focus_company_id`,`project_cost_total`.`variation_total`,`project`.`install_time_hrs`,`project`.`project_total`,`project`.`budget_estimate_total`   
			FROM `project` 
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id`
			LEFT JOIN `address_detail` ON `address_detail`.`address_detail_id` = `project`.`address_id`
			LEFT JOIN `address_general` ON `address_general`.`general_address_id` =  `address_detail`.`general_address_id`
			LEFT JOIN `states` ON `states`.`id` = `address_general`.`state_id`
			WHERE `project`.`is_active` = '1' AND  `project`.`job_category` != 'Company' ".$est_id." ");
		}




		return $query;
		
	}

	public function get_estimates($date_a,$date_b,$user_id='',$comp_id=''){

		if($user_id!='' && $comp_id!=''){

			$query = $this->db->query("SELECT
				`project`.`project_id`,
				`project`.`project_total`,
				`project_cost_total`.`variation_total`,
				`project`.`budget_estimate_total`,
				`project`.`date_site_commencement` ,
				`project`.`date_site_finish`, 
				`project`.`focus_company_id`,
				`project`.`project_manager_id`,
				`users`.`user_first_name`,`users`.`user_last_name`, `project`.`project_date`

				FROM `project`
				LEFT JOIN  `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id`
				LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`

				WHERE `project`.`is_active` = '1' AND `project`.`job_date` = '' AND  `project`.`is_paid` = '0'


				AND  `project`.`project_manager_id` = '$user_id' AND  `project`.`job_category` != 'Company'
				AND `project`.`focus_company_id` = '$comp_id' 

				AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`project_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
				AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`project_date`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )

				ORDER BY  `project`.`project_manager_id` ");

		}else{

			$query = $this->db->query("SELECT `project`.`project_id`,`project`.`project_total`,`project_cost_total`.`variation_total`,`project`.`budget_estimate_total`,`project`.`date_site_commencement` , `project`.`date_site_finish`,  `project`.`focus_company_id`,`project`.`project_manager_id`,  `users`.`user_first_name`,`users`.`user_last_name`
				FROM `project`
				LEFT JOIN  `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id`
				LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
				WHERE `project`.`is_active` = '1' AND `project`.`job_date` = '' AND  `project`.`is_paid` = '0' AND  `project`.`job_category` != 'Company'

				AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`project_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
				AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`project_date`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )
				
				GROUP BY `users`.`user_id`,`project`.`focus_company_id`
				ORDER BY  `project`.`project_manager_id`");
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
array(1) { 



array(1) { [0]=> object(stdClass)#59 (6) { ["project_id"]=> string(5) "35687" ["user_first_name"]=> string(11) "Maintenance" ["user_last_name"]=> string(7) "Manager" ["focus_company_id"]=> string(1) "5" ["user_id"]=> string(2) "29" ["invoiced_amount"]=> string(8) "31584.24" } } 





array(4) { 

[0]=> object(stdClass)#61 (6) { ["project_id"]=> string(5) "35344" ["user_first_name"]=> string(4) "Alan" ["user_last_name"]=> string(7) "Liddell" ["focus_company_id"]=> string(1) "5" ["user_id"]=> string(2) "15" ["invoiced_amount"]=> string(9) "112779.84" } 
[1]=> object(stdClass)#62 (6) { ["project_id"]=> string(5) "35743" ["user_first_name"]=> string(9) "Krzysztof" ["user_last_name"]=> string(6) "Kiezun" ["focus_company_id"]=> string(1) "5" ["user_id"]=> string(2) "24" ["invoiced_amount"]=> string(9) "167437.68" } 
[2]=> object(stdClass)#63 (6) { ["project_id"]=> string(5) "35719" ["user_first_name"]=> string(13) "Pyi Paing Aye" ["user_last_name"]=> string(3) "Win" ["focus_company_id"]=> string(1) "5" ["user_id"]=> string(2) "23" ["invoiced_amount"]=> string(9) "141972.24" } 
[3]=> object(stdClass)#64 (6) { ["project_id"]=> string(5) "35736" ["user_first_name"]=> string(6) "Stuart" ["user_last_name"]=> string(7) "Hubrich" ["focus_company_id"]=> string(1) "6" ["user_id"]=> string(2) "16" ["invoiced_amount"]=> string(8) "29306.94" } }



	}
*/

	
 


	public function list_pm_bysales($date_a,$date_b){

		$query = $this->db->query("SELECT `project`.`project_id`,`users`.`user_first_name`,`users`.`user_last_name`, `project`.`focus_company_id`, `users`.`user_id`

			FROM `project`

			LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
			LEFT JOIN `invoice` ON `invoice`.`project_id`= `project`.`project_id`
			WHERE `project`.`is_active` = '1' AND`project`.`job_date` <> '' AND `invoice`.`is_invoiced` = '1'  AND  `project`.`job_category` != 'Company'
            #AND `users`.`user_id` <> '29'

			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) <  UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )
			/* with in btwn months*/

			GROUP BY `project`.`project_manager_id`, `project`.`focus_company_id`            
			ORDER BY `project`.`focus_company_id` ASC , `users`.`user_first_name` ASC");

		return $query;

	}



	public function get_sales($date_a,$date_b,$pm_id,$comp_id=''){
		$query = $this->db->query("SELECT `project`.`project_id`,`users`.`user_first_name`,`users`.`user_last_name`, `project`.`focus_company_id`, `users`.`user_id`,`invoice`.`progress_percent`,`invoice`.`label`,`project_cost_total`.`variation_total`,`project`.`project_total`,`company_details`.`company_id`
			FROM `invoice`
			LEFT JOIN `project` ON `project`.`project_id` = `invoice`.`project_id`
			LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
			LEFT JOIN `company_details` ON `company_details`.`company_id` = `project`.`client_id`
			LEFT JOIN `payment` ON `payment`.`invoice_id` = `invoice`.`invoice_id`
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id`
			WHERE `project`.`is_active` = '1' AND`project`.`job_date` <> '' AND `invoice`.`is_invoiced` = '1' AND `users`.`user_id` = '$pm_id'   AND  `project`.`job_category` != 'Company'
			".($comp_id != '' ? " AND  `project`.`focus_company_id` = '$comp_id' " : "")." 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) <  UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )
			GROUP BY `invoice`.`invoice_id`");
		return $query;
	}


/*


SELECT `project`.`project_id`,`users`.`user_first_name`,`users`.`user_last_name`, `project`.`focus_company_id`, `users`.`user_id`,
			#SUM(`invoice`.`invoiced_amount`) AS `invoiced_amount`, SUM(`project_cost_total`.`variation_total`) AS `vr_total`,
			`invoice`.`progress_percent`,`invoice`.`label`,
			`project_cost_total`.`variation_total`,`project`.`project_total`
			FROM `project`

			LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
			LEFT JOIN `invoice` ON `invoice`.`project_id`= `project`.`project_id`
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id`
			WHERE `project`.`is_active` = '1' AND`project`.`job_date` <> '' AND `invoice`.`is_invoiced` = '1'  AND  `project`.`job_category` != 'Company'

			AND `users`.`user_id` = '$pm_id' AND  `project`.`focus_company_id` = '$comp_id'
/*
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= '$date_a' 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) < '$date_b' 
*/

/*
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) <  UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )

			/* with in btwn months*/

			#GROUP BY `project`.`project_manager_id`, `project`.`focus_company_id`
		/*	ORDER BY `project`.`focus_company_id` ASC , `users`.`user_first_name` ASC



*/










	public function look_for_sales($year,$proj_mngr_id,$focus_comp_id){
		$query = $this->db->query("SELECT * FROM `revenue_focus` WHERE `year` = '$year' AND `proj_mngr_id` = '$proj_mngr_id' AND `focus_comp_id` = '$focus_comp_id' ");
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

		$query = $this->db->query("SELECT * FROM `outstanding_focus` 
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



	public function get_current_forecast($year,$id,$get_comp='',$is_maintenance = ''){

		if($is_maintenance != ''){

			$query = $this->db->query("SELECT `revenue_forecast`.*, `rev_ind_a`.* , `rev_ind_b`.`forecast_percent` AS `wa_fct`, `rev_ind_c`.`forecast_percent` AS `nws_fct`, `rev_ind_d`.`forecast_percent` AS `nws_fct_b`

FROM `revenue_forecast`
LEFT JOIN  `revenue_forecast_individual` AS `rev_ind_a` ON `rev_ind_a`.`revenue_forecast_id` =  `revenue_forecast`.`revenue_forecast_id`
LEFT JOIN  `revenue_forecast_individual` AS `rev_ind_b` ON `rev_ind_b`.`revenue_forecast_id` =  `revenue_forecast`.`revenue_forecast_id`
LEFT JOIN  `revenue_forecast_individual` AS `rev_ind_c` ON `rev_ind_c`.`revenue_forecast_id` =  `revenue_forecast`.`revenue_forecast_id`
LEFT JOIN  `revenue_forecast_individual` AS `rev_ind_d` ON `rev_ind_d`.`revenue_forecast_id` =  `revenue_forecast`.`revenue_forecast_id`

LEFT JOIN `revenue_forecast` AS `rev_tbl_b` ON `rev_tbl_b`.`revenue_forecast_id` = `revenue_forecast`.`revenue_forecast_id`
                
WHERE `revenue_forecast`.`year` = '$year'
AND `revenue_forecast`.`is_primary` = '1'
AND `rev_ind_a`.`year` = '$year'
AND `rev_ind_a`.`other` = ''
AND  `rev_ind_a`.`pm_id` = '$id' AND `rev_ind_b`.`comp_id` = '5'  AND `rev_ind_c`.`comp_id` = '6'  
AND `rev_ind_d`.`comp_id` = '6' AND  `rev_ind_d`.`pm_id` = '$id'
             
GROUP BY `rev_ind_a`.`year`  ");

		}else{
			if($get_comp!=''){
				$query = $this->db->query("SELECT * FROM `revenue_forecast` 
					LEFT JOIN  `revenue_forecast_individual` ON `revenue_forecast_individual`.`revenue_forecast_id` =  `revenue_forecast`.`revenue_forecast_id`
					WHERE `revenue_forecast`.`year` = '$year'
					AND `revenue_forecast`.`is_primary` = '1'
					AND `revenue_forecast_individual`.`year` = '$year'
					AND `revenue_forecast_individual`.`other` = ''
					AND `revenue_forecast_individual`.`pm_id` = '0'
					AND `revenue_forecast_individual`.`comp_id` = '$id' ");
			}else{
				$query = $this->db->query("SELECT *, AVG(`revenue_forecast_individual`.`forecast_percent`) AS `forecast_value` FROM `revenue_forecast` 
					LEFT JOIN  `revenue_forecast_individual` ON `revenue_forecast_individual`.`revenue_forecast_id` =  `revenue_forecast`.`revenue_forecast_id`
					WHERE `revenue_forecast`.`year` = '$year'
					AND `revenue_forecast`.`is_primary` = '1'
					AND `revenue_forecast_individual`.`year` = '$year'
					AND `revenue_forecast_individual`.`other` = ''
					AND `revenue_forecast_individual`.`pm_id` = '$id' ");
			}
		}
		return $query;
	}

	public function check_estimates_set($proj_mngr_id, $est_month, $sales, $focus_comp_id, $year){

		$query = $this->db->query("SELECT * FROM `estimates_focus` 
			WHERE `estimates_focus`.`proj_mngr_id` = '$proj_mngr_id' 
			AND `estimates_focus`.`focus_comp_id` = '$focus_comp_id' 
			AND `estimates_focus`.`year` = '$year' ");

		if($query->num_rows >= 1){

			$query = $this->db->query("UPDATE `estimates_focus` SET `$est_month` = '$sales' 
				WHERE `estimates_focus`.`focus_comp_id` = '$focus_comp_id' 
				AND `estimates_focus`.`year` = '$year' 
				AND `estimates_focus`.`proj_mngr_id` = '$proj_mngr_id' ");
		}else{

			$query = $this->db->query("INSERT INTO `estimates_focus` (`proj_mngr_id`, `$est_month`, `focus_comp_id`, `year`) 
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

	public function fetch_project_pm_nomore(){
		$query = $this->db->query("SELECT `users`.`user_id`, `users`.`user_focus_company_id`,  `project`.`focus_company_id`  FROM `users` 
			LEFT JOIN `project` ON `project`.`project_manager_id`  =  `users`.`user_id` 
			WHERE `users`.`user_role_id` != '3' AND `users`.`is_active` = '1' AND `project`.`is_active` = '1' AND  `project`.`job_category` != 'Company'
			GROUP BY `project`.`project_manager_id` ");
		return $query;
	}

	public function fetch_pa_assignment($pa_id){
		$query = $this->db->query("SELECT * FROM `project_administrator_manager` WHERE `project_administrator_manager`.`project_administrator_id` = '$pa_id' ");
		return $query;
	}

	public function dash_sales($date_a,$date_b,$focus_company_id,$is_invoiced=''){



		if($is_invoiced != ''){

		
			$query = $this->db->query("SELECT `invoice`.`project_id`, `invoice`.`progress_percent`,`project`.`project_total`,`project_cost_total`.`variation_total`, `invoice`.`label`,`project`.`focus_company_id`,`project`.`project_manager_id`,`project`.`project_admin_id`, `project`.`job_category`
				FROM `invoice`
				LEFT JOIN `project` ON `project`.`project_id` = `invoice`.`project_id`
				LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id`
				WHERE `invoice`.`set_invoice_date` <> ''
				AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') ) 
				AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 
				AND `invoice`.`is_invoiced` = '$is_invoiced'
				#display invoiced only
				AND `project`.`is_active` = '1' 
				AND `project`.`job_date` <> '' AND  `project`.`job_category` != 'Company'
				AND `project`.`focus_company_id` = '$focus_company_id' ");
		}else{

			$query = $this->db->query("SELECT `invoice`.`project_id`, `invoice`.`progress_percent`,`project`.`project_total`,`project_cost_total`.`variation_total`, `invoice`.`label`,`project`.`focus_company_id`,`project`.`project_manager_id`,`project`.`project_admin_id`, `project`.`job_category`
				FROM `invoice`
				LEFT JOIN `project` ON `project`.`project_id` = `invoice`.`project_id`
				LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id`
				WHERE `invoice`.`is_invoiced` = '0'

				AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
				AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )
				
				AND `project`.`is_active` = '1'  AND  `project`.`job_category` != 'Company'
				AND `project`.`job_date` <> ''
				AND `project`.`focus_company_id` = '$focus_company_id' ");
		}

		return $query;
	}

	public function dash_unvoiced_per_date($date_a,$date_b,$comp_id){
		$query = $this->db->query("SELECT `invoice`.`project_id`, `invoice`.`invoice_date_req`, `invoice`.`set_invoice_date`, `project`.`project_total`,`project_cost_total`.`variation_total`, `invoice`.`progress_percent`, `invoice`.`label` ,`project`.`focus_company_id`,`invoice`.`invoice_date_req`,`project`.`project_manager_id`, `project`.`job_category`
			FROM `invoice`
			LEFT JOIN `project` ON `project`.`project_id` = `invoice`.`project_id`
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id`
			WHERE `project`.`job_date` <> ''
 

			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )

			AND `project`.`is_active` = '1' AND  `invoice`.`is_invoiced` = '0' AND `project`.`is_paid` = '0'  /*AND  `project`.`job_category` != 'Company'*/
			AND `project`.`focus_company_id` = '$comp_id' ");
		return $query;
	}

	public function dash_oustanding_payments($date_a,$date_b,$comp_id,$for_last_year=''){
		if($for_last_year != ''){
 
			$query = $this->db->query("SELECT  `invoice`.`invoice_id`, `invoice`.`set_invoice_date`, `invoice`.`project_id`,`invoice`.`label` , `invoice`.`progress_percent`,SUM( `payment`.`amount_exgst`) AS `lst_outstndg_amount`, `project`.`project_total`, `payment`.`payment_date`
FROM `invoice`
LEFT JOIN `payment` ON `payment`.`invoice_id` = `invoice`.`invoice_id`
LEFT JOIN `project` ON `project`.`project_id` = `invoice`.`project_id`


WHERE `project`.`focus_company_id` = '$comp_id' AND `project`.`is_active` = '1' AND  `invoice`.`is_invoiced` = '1' AND  `invoice`.`is_paid` = '0' AND  `project`.`job_category` != 'Company' 

AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )

/*
AND UNIX_TIMESTAMP( STR_TO_DATE(`payment`.`payment_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('01/01/2016', '%d/%m/%Y') )*/
AND UNIX_TIMESTAMP( STR_TO_DATE(`payment`.`payment_date`, '%d/%m/%Y') ) > UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )

AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`project_date`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )
 
/*GROUP BY `invoice`.`invoice_id` */
ORDER BY  `invoice`.`project_id` ASC,   `payment`.`payment_id` DESC ");
			return $query;
		}else{ 
			$query = $this->db->query("SELECT `payment`.`payment_id`, `project`.`focus_company_id`,`payment`.`amount_exgst`,`payment`.`payment_date`,`invoice`.`invoice_date_req`,`invoice`.`project_id`,`invoice`.`invoice_id` ,`project_cost_total`.`variation_total` , `project`.`project_total`,`invoice`.`progress_percent` ,`invoice`.`label`,`project`.`project_manager_id`
				FROM `invoice`
				LEFT JOIN `project` ON `project`.`project_id` = `invoice`.`project_id`
				LEFT JOIN `payment` ON `payment`.`invoice_id` = `invoice`.`invoice_id`
				LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id`
				WHERE `project`.`job_date` <> ''
				AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
				AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )
				AND `project`.`is_active` = '1' AND  `invoice`.`is_paid` = '0' AND  `invoice`.`is_invoiced` = '1'   
				AND  `project`.`focus_company_id` = '$comp_id'   AND  `project`.`job_category` != 'Company' 
				GROUP BY `invoice`.`invoice_id`
				ORDER BY `invoice`.`project_id` ASC");
			return $query;
		}
	}


	public function get_project_progress_list($date,$pm_id='',$custom=''){
		$query = $this->db->query(" SELECT  `project`.`project_id`, `project`.`project_name`,  `project`.`job_date`,  `project`.`date_site_commencement`,   `project`.`date_site_finish`,   `project`.`job_category`,    `project`.`job_type`, `company_details`.`company_name` AS `client_name`, 
			`project`.`focus_company_id`,  CONCAT(`users`.`user_first_name`,' ',`users`.`user_last_name`) AS `pm_name` ,
			`project`.`project_manager_id`,  `project`.`client_id`,UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_commencement`, '%d/%m/%Y') ) AS `project_unix_start_date`, `brand`.`brand_name`
			,UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) AS `project_unix_end_date`
			FROM `project`  
			LEFT JOIN `company_details` ON `company_details`.`company_id` = `project`.`client_id`
			LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
            LEFT JOIN `brand` ON `brand`.`brand_id` =  `project`.`brand_id`
			WHERE `project`.`is_active` = '1'  
			AND `project`.`job_date` != ''


			".($pm_id != '' ? " AND `project`.`project_manager_id` = '$pm_id' " : " ")." 

			".$custom."



			

			
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date', '%d/%m/%Y') )
			AND  ( `project`.`job_category` = 'Full Fitout' OR `project`.`job_category` = 'Refurbishment' OR `project`.`job_category` = 'Kiosk' ".($pm_id == '29' ? " OR `project`.`job_category` = 'Maintenance' " : " ")."  )
			
			ORDER BY `project_unix_end_date` ASC ,  `project`.`project_id` ASC 	LIMIT 50 ");
		return $query; 

	}


	public function get_joinery_list($is_reconciled_control='',$custom=''){
		$query = $this->db->query(" SELECT 



`works`.`project_id`,
`project`.`project_name`,
`works`.`other_work_desc`,`works`.`goods_deliver_by_date`,
`works`.`works_id`,
`works`.`work_cpo_date`,
`works`.`price`,`company_details`.`company_name`,

CONCAT(`contact_person`.`first_name`,' ',`contact_person`.`last_name`) AS `contact_personel`, 
CONCAT( `users`.`user_first_name`,' ',`users`.`user_last_name`) AS `pm_name`,


`contact_number`.`office_number` 

,  UNIX_TIMESTAMP( STR_TO_DATE(`works`.`goods_deliver_by_date`, '%d/%m/%Y') ) AS `unix_goods_deliver_by_date`
			,  UNIX_TIMESTAMP( STR_TO_DATE(`works`.`work_cpo_date`, '%d/%m/%Y') ) AS `unix_cpo_date` ,




  `project`.`job_date`,  `project`.`date_site_commencement`,   `project`.`date_site_finish`,   `project`.`job_category`,    `project`.`job_type`, `company_details`.`company_name` AS `client_name`, 
	
			`project`.`project_manager_id`,  `project`.`client_id`,UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_commencement`, '%d/%m/%Y') ) AS `project_unix_start_date`, `brand`.`brand_name`
			,UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) AS `project_unix_end_date`


			FROM `works` 


			LEFT JOIN `work_contractors` ON `work_contractors`.`works_id` = `works`.`works_id`  
			LEFT JOIN `project` ON `project`.`project_id`  =  `works`.`project_id`     
			 LEFT JOIN `brand` ON `brand`.`brand_id` =  `project`.`brand_id`
			LEFT JOIN `company_details` ON `company_details`.`company_id` =  `works`.`company_client_id` 
			LEFT JOIN `contact_person_company` ON `contact_person_company`.`company_id` = `works`.`company_client_id` 
			LEFT JOIN `contact_person` ON `contact_person`.`contact_person_id` = `work_contractors`.`contact_person_id`





			LEFT JOIN `contact_number` ON `contact_number`.`contact_number_id` = `contact_person`.`contact_number_id`
			LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`

			WHERE `works`.`other_work_desc` LIKE '%Joinery%'  AND `company_details`.`active` = '1' 
			AND `works`.`is_active` = '1'   AND  `project`.`is_active` = '1' AND `project`.`job_date`  != ''
			AND `works`.`work_cpo_date` != '' 

			

			".($is_reconciled_control ==''   ? "AND  `works`.`is_reconciled` = '0' " : "" )."


			AND `work_contractors`.`is_selected` = '1'
			AND `company_details`.`activity_id`  = '20'  
			AND `company_details`.`company_type_id` = '3'  

			".$custom."
			GROUP BY `works`.`works_id` 
			ORDER BY UNIX_TIMESTAMP( STR_TO_DATE(`works`.`work_cpo_date`, '%d/%m/%Y') ) ASC   ");
return $query; 
}




	public function get_top_joinery_list($year){

		$year2 = $year+1;

		$query = $this->db->query("	SELECT 

`works`.`project_id`,
`project`.`project_name`, 
`works`.`works_id`,
`works`.`work_cpo_date`,
`works`.`company_client_id`,`works`.`price`,`company_details`.`company_name`, SUM(`works`.`price`) AS `total_price`


			,  UNIX_TIMESTAMP( STR_TO_DATE(`works`.`work_cpo_date`, '%d/%m/%Y') ) AS `unix_cpo_date` FROM `works` 
			
			LEFT JOIN `project` ON `project`.`project_id`  =  `works`.`project_id` 
			LEFT JOIN `company_details` ON `company_details`.`company_id` =   `works`.`company_client_id`
            
            

			WHERE  `works`.`is_active` = '1'   AND  `project`.`is_active` = '1' AND `project`.`job_date`  != ''
			AND `works`.`work_cpo_date` != '' AND  `works`.`is_reconciled` = '1' AND `company_details`.`active` = '1'
            
            AND `company_details`.`activity_id`  = '20'  
AND `company_details`.`company_type_id` = '3'  
AND `company_details`.`active` = '1'
            
			AND UNIX_TIMESTAMP( STR_TO_DATE(`works`.`work_cpo_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('01/01/$year', '%d/%m/%Y') ) 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`works`.`work_cpo_date`, '%d/%m/%Y') ) <  UNIX_TIMESTAMP( STR_TO_DATE('01/01/$year2', '%d/%m/%Y') ) 
            
            
            GROUP BY `works`.`company_client_id`
			ORDER BY `total_price` DESC ");
		return $query; 
	}


 


	public function pm_per_comp_prj($date_a,$date_b,$comp_id){

		$query = $this->db->query("SELECT `project`.`project_manager_id` , `project`.`focus_company_id` FROM `project`
			LEFT JOIN `users` ON  `users`.`user_id` =  `project`.`project_manager_id` 
			WHERE `project`.`is_active` = '1'
			AND `project`.`focus_company_id` = '$comp_id' AND  `users`.`user_role_id` = '3' AND  `project`.`job_date` <> '' AND  `project`.`job_category` != 'Company'
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') ) 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) <  UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 
			GROUP BY `project`.`project_manager_id`  ");
		return $query; 
		
	}

	public function dash_total_pm_sales($proj_mngr_id,$year='',$is_out='',$date_a='',$date_b='', $comp_id = ''){


		if($comp_id != ''){

			$query = $this->db->query("


SELECT `invoice`.`project_id`, `invoice`.`progress_percent`,`project`.`project_total`,`project_cost_total`.`variation_total`, `invoice`.`label`,`project`.`focus_company_id`,`project`.`project_manager_id` 
,`invoice`.`invoiced_amount` AS `invoiced_amount`
FROM `invoice` LEFT JOIN `project` ON `project`.`project_id` = `invoice`.`project_id` LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id` 
WHERE `invoice`.`set_invoice_date` <> '' 
AND `invoice`.`is_invoiced` = '1' AND `project`.`is_active` = '1' 
AND `project`.`job_date` <> '' 

".($proj_mngr_id == '' ? '' :  " AND `project`.`project_manager_id` = '$proj_mngr_id'  " )."





AND `project`.`focus_company_id` = '$comp_id' AND  `project`.`job_category` != 'Company'

AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') ) 
AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 


			");

		}else{



		if($is_out!=''){

			$query = $this->db->query("SELECT *  ,`invoice`.`project_id` as `invoice_project_id`,  `invoice`.`invoice_id` AS `invoice_top_id`  FROM `invoice`
			LEFT JOIN `project` ON `project`.`project_id` = `invoice`.`project_id`
			 
	 
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id` AND  `project`.`job_category` != 'Company'
			WHERE `invoice`.`is_invoiced` = '0'
			AND `invoice`.`is_paid` = '0'
			AND `project`.`project_manager_id` = '$proj_mngr_id'
			AND `project`.is_active = '1'
			AND `project`.`job_date` <> '' 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) ");

		}else{

		 #display invoiced only
			$query = $this->db->query("


SELECT `invoice`.`project_id`, `invoice`.`progress_percent`,`project`.`project_total`,`project_cost_total`.`variation_total`, `invoice`.`label`,`project`.`focus_company_id`,`project`.`project_manager_id` 
,`invoice`.`invoiced_amount` AS `invoiced_amount`
FROM `invoice` LEFT JOIN `project` ON `project`.`project_id` = `invoice`.`project_id` LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id` 
WHERE `invoice`.`set_invoice_date` <> '' 
AND `invoice`.`is_invoiced` = '1' AND `project`.`is_active` = '1'  AND  `project`.`job_category` != 'Company'
AND `project`.`job_date` <> '' AND `project`.`project_manager_id` = '$proj_mngr_id'

AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') ) 
AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 

			");
		}	
		}	
		return $query;
	}

	//($pm->user_id,$last_year,1,$date_a_last,$date_b_last)

	public function dash_total_pm_sales_uninvoiced($proj_mngr_id,$year='',$is_out='',$date_a='',$date_b='', $comp_id = ''){
		$query = $this->db->query("SELECT *  ,`invoice`.`project_id` as `invoice_project_id`,  `invoice`.`invoice_id` AS `invoice_top_id`  FROM `invoice`
			LEFT JOIN `project` ON `project`.`project_id` = `invoice`.`project_id`
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id` AND  `project`.`job_category` != 'Company'
			WHERE  `project`.`project_manager_id` = '$proj_mngr_id'
			AND `project`.is_active = '1'
			AND `project`.`job_date` <> '' 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') ) 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) > UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) ");
		return $query;

	}


	public function dash_total_pm_estimates($date_a,$date_b){
		$query = $this->db->query("SELECT `project`.*,`users`.*,`company_details`.*,`project_cost_total`.`work_estimated_total`,`project_cost_total`.`variation_total`, UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') )  AS 'date_filter_mod', UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_commencement`, '%d/%m/%Y') )  AS 'start_date_filter_mod'
			FROM  `project`
			LEFT JOIN `company_details` ON `company_details`.`company_id` = `project`.`client_id` 
			LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id`
			WHERE `project`.is_active = '1' 
            
			AND `project`.`is_paid` = '0' AND `project`.`job_date` = ''  AND  `project`.`job_category` != 'Company'

			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`project_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') ) 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`project_date`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 

			ORDER BY `project`.`project_id` ASC ");
		return $query;
	}

	public function quotes_unacepted($date_a,$date_b){
		$query = $this->db->query("SELECT * FROM `project`
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` =  `project`.`project_id`
			WHERE UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) >=  UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) AND  `project`.`job_category` != 'Company'
			AND `project`.`job_date` = '' AND `project`.`is_active` = '1'");
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


	public function get_focus_companies_mntnc(){
		$query = $this->db->query(" SELECT `project`.`focus_company_id`, `company_details`.`company_name`,`project`.`project_manager_id`   FROM `project`
			LEFT JOIN `company_details` ON `company_details`.`company_id` = `project`.`focus_company_id`
			WHERE `project`.`job_category` = 'Maintenance'
			AND `project`.`is_active` = '1' AND  `project`.`job_category` != 'Company'
			GROUP BY `project`.`focus_company_id` ");
		return $query;
	}

	public function get_finished_projects($date_a, $date_b, $focus_id){
		$query = $this->db->query("SELECT * FROM `project`
			WHERE `project`.`is_active` = '1'
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 
			AND `project`.`focus_company_id` = '$focus_id' AND `project`.`job_date` <> '' AND  `project`.`job_category` != 'Company' ");
		return $query->num_rows;
	}

	public function get_wip_invoiced_projects($date_a, $date_b, $focus_id){
		$query = $this->db->query("SELECT * FROM `project` 
			WHERE `project`.`is_active` = '1' 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') ) 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 
			AND `project`.`focus_company_id` = '$focus_id' AND `project`.`job_date` <> '' AND  `project`.`job_category` != 'Company' ");
		return $query;
	}

	public function get_work_types(){
		$query = $this->db->query("SELECT * FROM `project` WHERE `project`.`job_category` != 'Company' GROUP BY `project`.`job_category` ORDER BY `project`.`job_category` ASC");
		return $query;
	}

	public function get_projects_by_work_type($date_a, $date_b){
		$query = $this->db->query("SELECT  * FROM `project`
			LEFT JOIN  `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id`
			WHERE `project`.`is_active` = '1' AND `project`.`job_category` != 'Company'
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') ) 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 
			AND `project`.`job_date` <> '' ");
		return $query;
	}

	public function get_po_per_focus($date_a, $date_b, $focus_id){

		$query = $this->db->query("SELECT `works`.`works_id`,`project`.`project_id`,`works`.`price`,`works`.`work_cpo_date`,`works`.`work_con_sup_id`, `project`.`focus_company_id` 
			, SUM(`works`.`price`) AS `total_price`
			FROM `works`
			LEFT JOIN `project` ON `project`.`project_id` = `works`.`project_id` 
			WHERE `works`.`is_reconciled` = '0' AND `works`.`is_active` = '1' AND TRIM(`works`.`work_cpo_date`) <> '' AND TRIM(`project`.`job_date`) <> ''  AND `project`.`is_active` = '1'   AND  `project`.`job_category` != 'Company'


			AND  `project`.`focus_company_id` = '$focus_id'");
		return $query;
	}


	public function get_map_projects($date_a, $date_b,$pm_id=''){

		if($pm_id != ''){
			$query = $this->db->query("SELECT `project`.`address_id`, `address_general`.`x_coordinates`,`address_general`.`y_coordinates`, `project`.`focus_company_id` ,`project`.`project_manager_id` ,`project`.`project_id` FROM `project`
				LEFT JOIN `address_detail` ON `address_detail`.`address_detail_id` = `project`.`address_id`
				LEFT JOIN `address_general` ON `address_general`.`general_address_id` = `address_detail`.`general_address_id`
				WHERE `project`.`job_date` <> '' AND `project`.is_active = '1' AND `project`.project_manager_id = '$pm_id'  AND  `project`.`job_category` != 'Company'
				
				AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 
		#	AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 
				
				ORDER BY `project`.`project_id` DESC");
		}else{


			$query = $this->db->query("SELECT `project`.`address_id`, `address_general`.`x_coordinates`,`address_general`.`y_coordinates`, `project`.`focus_company_id` ,`project`.`project_manager_id` ,`project`.`project_id` FROM `project`
				LEFT JOIN `address_detail` ON `address_detail`.`address_detail_id` = `project`.`address_id`
				LEFT JOIN `address_general` ON `address_general`.`general_address_id` = `address_detail`.`general_address_id`
				WHERE `project`.`job_date` <> '' AND `project`.is_active = '1'  AND  `project`.`job_category` != 'Company'

				AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 
		#	AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 

				ORDER BY `project`.`project_id` DESC");
		}


		return $query;
	}

	public function get_unaccepted_projects($date_a, $date_b){
		$query = $this->db->query("
			SELECT * FROM `project`
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` =  `project`.`project_id`
			WHERE UNIX_TIMESTAMP( STR_TO_DATE(`project`.`unaccepted_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') ) 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`unaccepted_date`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )
			AND `project`.`is_active` = '1' AND `project`.`job_date` = '' AND  `project`.`is_paid` = '0' AND  `project`.`job_category` != 'Company' ");
		return $query;
	}


	public function get_locations_points(){
		$query = $this->db->query("SELECT `users`.`user_id`, `users`.`user_first_name`, `users`.`user_last_name`,`location_address`.`location` ,`location_address`.`location_address_id` ,`location_address`.`x_coordinate`,`location_address`.`y_coordinate`
			FROM `employee_location`
			LEFT JOIN `location_address` ON `location_address`.`location_address_id` = `employee_location`.`location_address_id`
			LEFT JOIN  `users` ON `users`.`user_id` =  `employee_location`.`user_id` 
			WHERE  `users`.`is_active`  = '1' AND  `location_address`.`active` = '1'
			GROUP BY `location_address`.`location` ");
		return $query;
	}


	public function get_employee_location($id){
		$query = $this->db->query("SELECT `users`.`user_id`, `users`.`user_first_name`, `users`.`user_last_name`
			FROM `employee_location`
			LEFT JOIN `location_address` ON `location_address`.`location_address_id` = `employee_location`.`location_address_id`
			LEFT JOIN  `users` ON `users`.`user_id` =  `employee_location`.`user_id` 
			WHERE  `users`.`is_active`  = '1' AND  `location_address`.`active` = '1'
			AND  `employee_location`.`location_address_id` = '$id' ORDER BY `users`.`user_first_name` ASC ");
		return $query;
	}


	public function get_personal_wip($date_a,$date_b,$id='',$comp){
		$query = $this->db->query(" SELECT * FROM `invoice`
			LEFT JOIN `project` ON `project`.`project_id` =  `invoice`.`project_id`
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id`

			WHERE `invoice`.`is_invoiced` = '0'
			AND `invoice`.`is_paid` = '0' AND `project`.`is_active` = '1' AND  `project`.`job_category` != 'Company'
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') ) 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 
			AND `project`.`job_date` <> '' 
			".($id != '' ? " AND  `project`.`project_manager_id` = '$id'  " : '')."
			AND  `project`.`focus_company_id` = '$comp'  GROUP BY `invoice`.`invoice_id` ");

		return $query;
	}

	public function get_maintenance_wip($date_a,$date_b){
		$query = $this->db->query("SELECT `project`.`focus_company_id` , `project`.`project_manager_id`,`project`.`project_id` 
			FROM `project`
			WHERE `project`.`is_paid` = '0' 
			AND `project`.`job_date` <> ''
			AND `project`.`project_manager_id` = '29'  AND  `project`.`job_category` != 'Company'
			AND `project`.`job_category` = 'Maintenance'  AND `project`.`is_active` = '1'
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') ) 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) ");
		return $query;
	}

	public function fetch_current_total_invoices($year = '',$pm_id = '',$comp_id=''){
		$this->db->flush_cache();
		$query = $this->db->query("SELECT
			SUM(`revenue_focus`.`rev_jan`) + SUM(`revenue_focus`.`rev_feb`) +
			SUM(`revenue_focus`.`rev_mar`) + SUM(`revenue_focus`.`rev_apr`) +
			SUM(`revenue_focus`.`rev_may`) + SUM(`revenue_focus`.`rev_jun`) +
			SUM(`revenue_focus`.`rev_jul`) + SUM(`revenue_focus`.`rev_aug`) +
			SUM(`revenue_focus`.`rev_sep`) + SUM(`revenue_focus`.`rev_oct`) +
			SUM(`revenue_focus`.`rev_nov`) + SUM(`revenue_focus`.`rev_dec`) AS `current_sales`

			FROM `revenue_focus`
			WHERE `revenue_focus`.`year` = '$year' 
			".($pm_id != '' ? " AND  `revenue_focus`.`proj_mngr_id` = '$pm_id' " : "")."
			".($comp_id != '' ? " AND  `revenue_focus`.`focus_comp_id` = '$comp_id' " : "")."



			  ");

		return $query;
	}


	public function get_wip_permonth($date_a,$date_b,$id='',$type='' ){

		if($id != ''){

			if($type != ''){

				$query = $this->db->query(" SELECT * FROM `invoice`
					LEFT JOIN `project` ON `project`.`project_id` =  `invoice`.`project_id`
					LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id`

					WHERE `invoice`.`is_invoiced` = '0'
					AND `invoice`.`is_paid` = '0' AND `project`.`is_active` = '1' AND  `project`.`job_category` != 'Company'
					AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') ) 
					AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 
					AND `project`.`job_date` <> '' AND  `project`.`project_manager_id` = '$id'  GROUP BY `invoice`.`invoice_id` ");

			}else{

				$query = $this->db->query(" SELECT * FROM `invoice`
					LEFT JOIN `project` ON `project`.`project_id` =  `invoice`.`project_id`
					LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id`

					WHERE `invoice`.`is_invoiced` = '0' AND  `project`.`job_category` != 'Company'
					AND `invoice`.`is_paid` = '0' AND `project`.`is_active` = '1'
					AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') ) 
					AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 
					AND `project`.`job_date` <> '' AND  `project`.`focus_company_id` = '$id'   GROUP BY `invoice`.`invoice_id` ");
			}  

		}else{

			$query = $this->db->query(" SELECT * FROM `invoice`
				LEFT JOIN `project` ON `project`.`project_id` =  `invoice`.`project_id`
				LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id`

				WHERE `invoice`.`is_invoiced` = '0' AND  `project`.`job_category` != 'Company'
				AND `invoice`.`is_paid` = '0' AND `project`.`is_active` = '1'
				AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') ) 
				AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 
				AND `project`.`job_date` <> ''   GROUP BY `invoice`.`invoice_id` ");

		}

		return $query;


	}



	public function get_wip_last_year($date_a,$date_b,$comp_id=''){
		$query = $this->db->query("SELECT * FROM `invoice`
			LEFT JOIN `project` ON `project`.`project_id` =  `invoice`.`project_id`
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id`

			WHERE   AND  `project`.`job_category` != 'Company'
			AND `project`.`is_active` = '1'
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') ) 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`project_date`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 
			AND `project`.`job_date` <> '' AND  `project`.`focus_company_id` = '$comp_id'   GROUP BY `invoice`.`invoice_id` ");
		return $query;
	}




	public function client_vr_value($date_a,$date_b,$comp_id,$extra_q=''){

		if($extra_q != ''){

	$query = $this->db->query("SELECT  `project`.`client_id`,`company_details`.`company_name` , SUM(   `project`.`project_total` *(`invoice`.`progress_percent`/100) ) AS `total_invoiced`,SUM(`project_cost_total`.`variation_total`) AS `total_variation` 
			FROM `invoice`
			LEFT JOIN `project` ON `project`.`project_id`=  `invoice`.`project_id`
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id`
			LEFT JOIN  `company_details` ON `company_details`.`company_id` = `project`.`client_id`

			WHERE `invoice`.`is_invoiced` = '1' AND `invoice`.`set_invoice_date` <> ''

			AND `project`.`client_id` = '$comp_id' AND  `project`.`job_category` != 'Company'
			AND `invoice`.`label`  = 'VR' AND `project`.`job_date` <> '' AND `project`.`is_active` = '1' 
			$extra_q

			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )");


		}else{

		$query = $this->db->query("SELECT  `project`.`client_id`,`company_details`.`company_name` , SUM(   `project`.`project_total` *(`invoice`.`progress_percent`/100) ) AS `total_invoiced`,SUM(`project_cost_total`.`variation_total`) AS `total_variation` 
			FROM `invoice`
			LEFT JOIN `project` ON `project`.`project_id`=  `invoice`.`project_id`
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id`
			LEFT JOIN  `company_details` ON `company_details`.`company_id` = `project`.`client_id`

			WHERE `invoice`.`is_invoiced` = '1' AND `invoice`.`set_invoice_date` <> ''

			AND `project`.`client_id` = '$comp_id' AND  `project`.`job_category` != 'Company'
			AND `invoice`.`label`  = 'VR' AND `project`.`job_date` <> '' AND `project`.`is_active` = '1' 

			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )");
		}



		return $query;
	}

	public function get_top_comp_lists_name($date_a,$date_b,$pm_id = ''){
		$query = $this->db->query("SELECT `company_details`.`company_id` ,`company_details`.`company_name`  ,`company_details`.`parent_company_id` ,

IF(`company_details`.`parent_company_id` > 0 , `company_details`.`parent_company_id` , `company_details`.`company_id` ) AS `company_parent_solo_id`
FROM `invoice`
			LEFT JOIN `project` ON `project`.`project_id` = `invoice`.`project_id`
			LEFT JOIN `company_details` ON `company_details`.`company_id` = `project`.`client_id`
			WHERE `invoice`.`is_invoiced` = '1' AND `invoice`.`set_invoice_date` <> ''	AND `project`.is_active = '1' AND `project`.`job_date` <> ''
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )
			".($pm_id != '' ? " AND  `project`.`project_manager_id` = '$pm_id' " : "")." 
			GROUP BY `company_parent_solo_id` ");
		return $query;
	}



	public function get_top_ten_clients($date_a,$date_b,$comp_id='',$f_comp_id='',$extra_q=''){

if($f_comp_id != ''){


$query = $this->db->query("SELECT  `project`.`client_id`,`company_details`.`company_name` , SUM(   `project`.`project_total` *(`invoice`.`progress_percent`/100) ) AS `grand_total`, SUM(`project_cost_total`.`variation_total`) AS `total_variation` ,`company_details`.`sub_client_id` ,

IF(`company_details`.`sub_client_id` > 0 , `company_details`.`sub_client_id` , `company_details`.`company_id` ) AS `company_parent_solo_id`
			FROM `invoice`
			LEFT JOIN `project` ON `project`.`project_id`=  `invoice`.`project_id`
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id`
			LEFT JOIN  `company_details` ON `company_details`.`company_id` = `project`.`client_id`

			WHERE `invoice`.`is_invoiced` = '1' AND `invoice`.`set_invoice_date` <> ''

			AND `project`.`client_id` = '$comp_id' AND  `project`.`job_category` != 'Company'
			AND `invoice`.`label`  != 'VR' AND `project`.`job_date` <> '' AND `project`.`is_active` = '1'  AND `project`.`focus_company_id` = '$f_comp_id' 
 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 
	GROUP BY  `company_parent_solo_id`");


}else{


	if($comp_id != ''){
		$query = $this->db->query("SELECT  `project`.`client_id`,`company_details`.`company_name` , SUM(   `project`.`project_total` *(`invoice`.`progress_percent`/100) ) AS `grand_total`, SUM(`project_cost_total`.`variation_total`) AS `total_variation` ,`company_details`.`sub_client_id` ,

IF(`company_details`.`sub_client_id` > 0 , `company_details`.`sub_client_id` , `company_details`.`company_id` ) AS `company_parent_solo_id`
				FROM `invoice`
				LEFT JOIN `project` ON `project`.`project_id`=  `invoice`.`project_id`
				LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id`
				LEFT JOIN  `company_details` ON `company_details`.`company_id` = `project`.`client_id`

				WHERE `invoice`.`is_invoiced` = '1' AND `invoice`.`set_invoice_date` <> ''

				AND `project`.`client_id` = '$comp_id' AND  `project`.`job_category` != 'Company'
				AND `invoice`.`label`  != 'VR' AND `project`.`job_date` <> '' AND `project`.`is_active` = '1' 

				AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
				AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )
	GROUP BY  `company_parent_solo_id`");
	}else{

			$query = $this->db->query("SELECT  `project`.`client_id`,`company_details`.`company_name`, `company_details`.`company_id` , SUM(   `project`.`project_total` *(`invoice`.`progress_percent`/100) ) AS `grand_total`,SUM(`project_cost_total`.`variation_total`) AS `total_variation` 


,`company_details`.`sub_client_id`,IF(`company_details`.`sub_client_id` > 0 , `parent_company`.`company_name` , `company_details`.`company_name` ) AS `company_name_group`

	FROM `invoice`
	LEFT JOIN `project` ON `project`.`project_id`=  `invoice`.`project_id`
	LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id`
	LEFT JOIN  `company_details` ON `company_details`.`company_id` = `project`.`client_id`
	LEFT JOIN  `company_details` `parent_company` ON `parent_company`.`company_id` = `company_details`.`sub_client_id`

	WHERE `invoice`.`is_invoiced` = '1' AND `invoice`.`set_invoice_date` <> '' AND  `project`.`job_category` != 'Company'
	 
	AND `invoice`.`label` != 'VR' AND `project`.`job_date` <> '' AND `project`.`is_active` = '1' 

	AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
	AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )
	 $extra_q
	             

	GROUP BY  `company_name_group`
	ORDER BY `grand_total` DESC
	LIMIT 0,20



	");
	}

}

		return $query;
	}





	public function get_company_sales($type,$date_a,$date_b,$cmp_id='',$comp_q=''){ #$type 3 supplier or 2 contractor

		if($cmp_id!=''){



$query = $this->db->query("SELECT `company_details`.`company_id`, `company_details`.`company_name` , SUM(`works`.`price`) AS `total_price` ,  `project`.`project_manager_id` 
FROM `works`
LEFT JOIN `company_details`  ON `company_details`.`company_id` = `works`.`company_client_id`
LEFT JOIN `project` ON `project`.`project_id` = `works`.`project_id` 
WHERE `works`.`reconciled_date` IS NOT NULL 
AND  `works`.`is_active` = '1'
AND UNIX_TIMESTAMP( STR_TO_DATE(`works`.`reconciled_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
AND UNIX_TIMESTAMP( STR_TO_DATE(`works`.`reconciled_date`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )

AND `works`.`company_client_id` = '$cmp_id' AND  `project`.`job_category` != 'Company'
$comp_q
GROUP BY `works`.`company_client_id`
ORDER BY `total_price` DESC
LIMIT 0,20  ");
		 

		}else{


$query = $this->db->query("SELECT `company_details`.`company_id`, `company_details`.`company_name` , SUM(`works`.`price`) AS `total_price`
FROM `works`
LEFT JOIN `company_details`  ON `company_details`.`company_id` = `works`.`company_client_id`
LEFT JOIN `project` ON `project`.`project_id` = `works`.`project_id` 
WHERE `works`.`reconciled_date` IS NOT NULL 
AND  `works`.`is_active` = '1' AND  `project`.`job_category` != 'Company'
AND `company_details`.`company_type_id` = '$type' #3 supplier or 2 contractor
AND UNIX_TIMESTAMP( STR_TO_DATE(`works`.`reconciled_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
AND UNIX_TIMESTAMP( STR_TO_DATE(`works`.`reconciled_date`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )
$comp_q
GROUP BY `works`.`company_client_id`
ORDER BY `total_price` DESC
LIMIT 0,20  ");
		 


		}


 
		return $query;
	}



	public function get_company_sales_overall($id){
		$query = $this->db->query("	SELECT `company_details`.`company_id`, `works`.`works_id`,`project`.`project_id`,`project`.`job_date`,`project`.`project_name`,`company_details`.`company_type_id`, `company_details`.`company_name` as `contractor_name`,   `works`.`price`,  `works`.`contractor_type`, `works`.`work_cpo_date`
            ,SUM(`works`.`price`) AS `total_price` FROM `works`
			LEFT JOIN `project` ON `project`.`project_id` = `works`.`project_id`
			LEFT JOIN `company_details`  ON `company_details`.`company_id` = `works`.`company_client_id`
			WHERE  `works`.`is_active` = '1' AND TRIM(`works`.`work_cpo_date`) <> '' AND TRIM(`project`.`job_date`) <> ''  AND `project`.`is_active` = '1' AND  `project`.`job_category` != 'Company'
			AND `company_details`.`active` = '1' AND `company_details`.`company_id`= '$id' ");
		return $query;
	}


	public function get_maitenance_dates_pm($date_a,$date_b,$id='',$type=''){

		if($id != ''){

			if($type != ''){


				$query = $this->db->query("SELECT `set_inv_data`.`set_invoice_date`,`project`.`date_site_finish`,`project`.`project_id`,`project`.`project_manager_id`,`users`.`user_first_name` ,`project`.`focus_company_id`
					,DATEDIFF(DATE_FORMAT(STR_TO_DATE(`set_inv_data`.`set_invoice_date`, '%d/%m/%Y'), '%Y-%m-%d'), DATE_FORMAT(STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y'), '%Y-%m-%d')) AS `days_diff`
					FROM (
						SELECT *,UNIX_TIMESTAMP( STR_TO_DATE(`set_invoice_date`, '%d/%m/%Y') ) AS `set_inv_date`  FROM `invoice` WHERE `set_invoice_date` <> '' AND `label` <> '' ORDER BY `set_inv_date` DESC
					) `set_inv_data`

					LEFT JOIN `project` ON `project`.`project_id` =  `set_inv_data`.`project_id`
					LEFT JOIN  `users` ON `users`.`user_id` =  `project`.`project_manager_id` 
					WHERE UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') ) 
					AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 
					AND `project`.`project_manager_id` = '$id'
					AND `project`.`focus_company_id` = '$type' 
					AND `project`.`job_date` <> ''
					AND `project`.`is_active` = '1'  AND  `project`.`job_category` != 'Company'
					GROUP BY `set_inv_data`.`project_id`
				");



/*
				$query = $this->db->query("SELECT `project_data`.`project_id`,`project_data`.`project_date`,`project_data`.`date_site_finish`,`project_data`.`project_name`,`project_data`.`date_site_commencement`,`project_data`.`date_site_finish`,`project_data`.`focus_company_id`,`project_data`.`job_category`,`project_data`.`job_date`,`invoice_table`.`set_invoice_date`,`company_details`.`company_name`, `project_data`.`project_manager_id`
					DATEDIFF(DATE_FORMAT(STR_TO_DATE(`invoice_table`.`set_invoice_date`, '%d/%m/%Y'), '%Y-%m-%d'), DATE_FORMAT(STR_TO_DATE(`project_data`.`date_site_finish`, '%d/%m/%Y'), '%Y-%m-%d')) AS `total_days`
					FROM `project` AS `project_data`
					LEFT JOIN (select `project_id`, `set_invoice_date` FROM `invoice` WHERE `label` <> '' AND `label` <> 'VR' ) AS `invoice_table` ON `invoice_table`.`project_id` = `project_data`.`project_id`
					LEFT JOIN `company_details` ON `company_details`.`company_id` =  `project_data`.`client_id`
					WHERE UNIX_TIMESTAMP( STR_TO_DATE(`project_data`.`date_site_finish`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') ) 
					AND UNIX_TIMESTAMP( STR_TO_DATE(`project_data`.`date_site_finish`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 
					AND `project_data`.`is_active` = 1 
					AND `project_data`.`job_date` <> ''
					AND `project_data`.`project_manager_id` = '$id' 
					AND `project_data`.`focus_company_id` = '$type' 
					AND `invoice_table`.`set_invoice_date` <> ''
					ORDER BY `project_data`.`project_id` ");
*/


			}else{

				$query = $this->db->query("SELECT `set_inv_data`.`set_invoice_date`,`project`.`date_site_finish`,`project`.`project_id`,`project`.`project_manager_id`,`users`.`user_first_name` ,`project`.`focus_company_id`
					,DATEDIFF(DATE_FORMAT(STR_TO_DATE(`set_inv_data`.`set_invoice_date`, '%d/%m/%Y'), '%Y-%m-%d'), DATE_FORMAT(STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y'), '%Y-%m-%d')) AS `days_diff`
					FROM (
						SELECT *,UNIX_TIMESTAMP( STR_TO_DATE(`set_invoice_date`, '%d/%m/%Y') ) AS `set_inv_date`  FROM `invoice` WHERE `set_invoice_date` <> '' AND `label` <> '' ORDER BY `set_inv_date` DESC
					) `set_inv_data`

					LEFT JOIN `project` ON `project`.`project_id` =  `set_inv_data`.`project_id`
					LEFT JOIN  `users` ON `users`.`user_id` =  `project`.`project_manager_id` 
					WHERE UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') ) 
					AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 
					AND `project`.`focus_company_id` = '$id'
					AND `project`.`job_date` <> ''
					AND `project`.`is_active` = '1'  AND  `project`.`job_category` != 'Company'
					GROUP BY `set_inv_data`.`project_id`
				");
			}

		}else{


				$query = $this->db->query("SELECT `set_inv_data`.`set_invoice_date`,`project`.`date_site_finish`,`project`.`project_id`,`project`.`project_manager_id`,`users`.`user_first_name` ,`project`.`focus_company_id`
					,DATEDIFF(DATE_FORMAT(STR_TO_DATE(`set_inv_data`.`set_invoice_date`, '%d/%m/%Y'), '%Y-%m-%d'), DATE_FORMAT(STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y'), '%Y-%m-%d')) AS `days_diff`
					FROM (
						SELECT *,UNIX_TIMESTAMP( STR_TO_DATE(`set_invoice_date`, '%d/%m/%Y') ) AS `set_inv_date`  FROM `invoice` WHERE `set_invoice_date` <> '' AND `label` <> '' ORDER BY `set_inv_date` DESC
					) `set_inv_data`

					LEFT JOIN `project` ON `project`.`project_id` =  `set_inv_data`.`project_id`
					LEFT JOIN  `users` ON `users`.`user_id` =  `project`.`project_manager_id` 
					WHERE UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') ) 
					AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 
					AND `project`.`job_date` <> '' AND  `project`.`job_category` != 'Company'
					AND `project`.`is_active` = '1' 
					GROUP BY `set_inv_data`.`project_id`
				");

		}


		return $query;


	}


	public function get_maitenance_dates($date_a,$date_b){
		$query = $this->db->query("


select 
 a.project_id,
 a.project_date,
 a.date_site_finish,
 a.project_name,        a.date_site_commencement, a.date_site_finish, a.focus_company_id, `company_details`.`company_name`,
 a.job_category,
 a.job_date,
 invoice_table.set_invoice_date,
 datediff(date_format(str_to_date(invoice_table.set_invoice_date, '%d/%m/%Y'), '%Y-%m-%d'),date_format(str_to_date(a.date_site_commencement, '%d/%m/%Y'), '%Y-%m-%d')) as total_days 
 from project as a
  left join (select project_id, set_invoice_date from invoice where label <> '' and label <> 'VR' ) as invoice_table on invoice_table.project_id = a.project_id 

			LEFT JOIN `company_details` ON `company_details`.`company_id` =  a.client_id  

 where UNIX_TIMESTAMP( STR_TO_DATE(a.date_site_finish, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') ) 
and UNIX_TIMESTAMP( STR_TO_DATE(a.date_site_finish, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 

   and a.is_active = 1 and a.job_date <> '' and a.job_category ='Maintenance' and invoice_table.set_invoice_date <> ''
  order by a.project_id

 ");
		return $query;

		/*
			SELECT `project`.`date_site_commencement`, `project`.`date_site_finish`, `project`.`project_id`,
			`company_details`.`company_name`, `project`.`project_name`,`project`.`job_date`,`invoice`.`set_invoice_date`,`project`.`focus_company_id`,
			UNIX_TIMESTAMP( STR_TO_DATE(`project`.`job_date`, '%d/%m/%Y') ) AS `date_accepted`,
			UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) AS `inv_set_date`,
			(UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) - UNIX_TIMESTAMP( STR_TO_DATE(`project`.`job_date`, '%d/%m/%Y') )) AS `diff`,
			DATE_FORMAT(STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y'), '%m/%d/%Y') AS `inv_date`,
			DATE_FORMAT(STR_TO_DATE(`project`.`job_date`, '%d/%m/%Y'), '%m/%d/%Y') AS `jb_date`

			FROM `project`
			LEFT JOIN `invoice` ON `invoice`.`project_id` = `project`.`project_id`
			LEFT JOIN `company_details` ON `company_details`.`company_id` = `project`.`client_id`
			
			WHERE  `project`.`job_category`  = 'Maintenance' 
			AND `project`.`is_active` = '1'  AND `project`.`job_date` <> '' AND  `invoice`.`is_invoiced` = '1' 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') ) 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') ) 

			ORDER BY  `diff` DESC*/
	}


	public function fetch_project_estimators(){
		$this_year = date("Y");

		$query = $this->db->query("SELECT `project`.`project_estiamator_id`  ,`users`.`user_first_name`,`users`.`user_profile_photo`
			FROM `project`
			LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_estiamator_id`  
			WHERE `project`.`job_category` != 'Company'
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`project_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('01/01/$this_year', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`project_date`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('31/12/$this_year', '%d/%m/%Y') )
			GROUP BY `project`.`project_estiamator_id`");
		return $query;
	}

	public function get_wip_list_base_jobdate($date_a,$date_b){ // I will use this query to get the past WIP ONLY!
		$query = $this->db->query("SELECT * FROM `project`
			LEFT JOIN `company_details` ON `company_details`.`company_id` = `project`.`client_id` 
			LEFT JOIN `users` ON `users`.`user_id` = `project`.`project_manager_id`
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id`
			WHERE `project`.`job_date` <> '' AND `project`.`is_active` = '1' AND  `project`.`job_category` != 'Company'
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`job_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$date_a', '%d/%m/%Y') )
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`job_date`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$date_b', '%d/%m/%Y') )");
		return $query;
	}

}


/*

select 
 a.project_id,
 a.project_date,
 a.date_site_finish,
 a.project_name,        a.date_site_commencement, a.date_site_finish, a.focus_company_id, `company_details`.`company_name`,
 a.job_category,
 a.job_date,
 invoice_table.set_invoice_date,
 datediff(date_format(str_to_date(invoice_table.set_invoice_date, '%d/%m/%Y'), '%Y-%m-%d'),date_format(str_to_date(a.job_date, '%d/%m/%Y'), '%Y-%m-%d')) as total_days 
 from project as a
  left join (select project_id, set_invoice_date from invoice where label != '' and label != 'VR' ) as invoice_table on invoice_table.project_id = a.project_id 
LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = a.project_id 

 where UNIX_TIMESTAMP( STR_TO_DATE(a.date_site_finish, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('01/01/2016', '%d/%m/%Y') ) 
and UNIX_TIMESTAMP( STR_TO_DATE(a.date_site_finish, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('01/01/2017', '%d/%m/%Y') ) 

   and a.is_active = 1 and a.job_date != "" and a.job_category = "Maintenance" and invoice_table.set_invoice_date != ""
  order by a.project_id

  */