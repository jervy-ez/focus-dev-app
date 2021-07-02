<?php

class Admin_m extends CI_Model{
	
	public function fetch_site_costs($id=0){	
		if( $id>0 ){
			$query = $this->db->query("SELECT *, `site_costs`.`leave_loading` AS `sc_leave_loading` FROM `site_costs` WHERE `site_cost_id` = '$id' ");
		}else{
			$query = $this->db->query("SELECT *, `site_costs`.`leave_loading` AS `sc_leave_loading` FROM `site_costs` ORDER BY `site_cost_id` DESC LIMIT 1");
		}
		return $query;
	}
	
	public function fetch_admin_defaults($id=0){
		if( $id != 0 ){
			$query = $this->db->query("SELECT * FROM `admin_defaults` WHERE `admin_default_id` = '$id' ");			
		}else{
			$query = $this->db->query("SELECT * FROM `admin_defaults` ORDER BY `admin_default_id` DESC LIMIT 1");
		}
		return $query;
	}
	
	public function fetch_markup($id=0){
		if( $id>0 ){
			$query = $this->db->query("SELECT * FROM `markup` WHERE `markup_id` = '$id' ");			
		}else{
			$query = $this->db->query("SELECT * FROM `markup` ORDER BY `markup_id` DESC LIMIT 1");
		}
		return $query;
	}
	
	public function latest_system_default($defaults_id=''){
		if($defaults_id!='' && $defaults_id >  0){
			$query = $this->db->query("SELECT * FROM `defaults` WHERE `defaults_id` = '$defaults_id' ");
		}else{
			$query = $this->db->query("SELECT * FROM `defaults` ORDER BY `defaults_id` DESC LIMIT 1");
		}
		return $query;
	}

	public function add_season($seasons_label,$date_start,$date_finish){
		$query = $this->db->query(" INSERT INTO `seasons` ( `seasons_label`, `is_active`, `date_start`, `date_finish`) VALUES ( '$seasons_label', '1', '$date_start', '$date_finish') ");
	}

	public function list_seasons($year){
		$query = $this->db->query("SELECT *, UNIX_TIMESTAMP( STR_TO_DATE( CONCAT( `seasons`.`date_start`, '/$year') , '%d/%m/%Y') ) AS `date_unix` FROM `seasons` WHERE `seasons`.`is_active` = '1' ORDER BY `seasons`.`seasons_label` ASC");
		return $query;
	}

	public function insert_login_bg($file_name,$seasons_id){
		$query = $this->db->query("	INSERT INTO `seasons_background` ( `file_name`, `seasons_id`, `is_active`, `is_selected`) VALUES ( '$file_name', '$seasons_id',   '1', '0') ");
	}

	public function pickup_season_bg($season_id=''){

		if($season_id!= ''){
			$query = $this->db->query(" SELECT * FROM `seasons_background` WHERE `seasons_background`.`is_active` = '1' AND `seasons_background`.`seasons_id` = '$season_id' ");

		}else{
			$query = $this->db->query(" SELECT * FROM `seasons_background` WHERE `seasons_background`.`is_active` = '1' AND `seasons_background`.`seasons_id` = '$season_id' ");

		}
		return $query;
	}

	public function update_bg_login($season_name,$bg_date_start,$bg_date_end,$id){
		$query = $this->db->query("UPDATE `seasons` SET `seasons_label` = '$season_name', `date_start` = '$bg_date_start', `date_finish` = '$bg_date_end' WHERE `seasons`.`seasons_id` = '$id' ");
		return $query;
	}

	public function delete_login_bg($id){
		$this->db->query("UPDATE `seasons` SET `is_active` = '0' WHERE `seasons`.`seasons_id` = '$id' ");
	}

	public function set_bg_background($img_id,$season_id){
		$this->db->query("UPDATE `seasons_background` SET `is_selected` = '0' WHERE `seasons_background`.`seasons_id` = '$season_id' ");
		$this->db->query("UPDATE `seasons_background` SET `is_selected` = '1' WHERE `seasons_background`.`seasons_background_id` = '$img_id' "); 
	}


	public function get_warehouse_locations(){
		$query = $this->db->query(" SELECT `warehouse`.*, `company_details`.`company_name` FROM `warehouse` LEFT JOIN `company_details` ON `company_details`.`company_id` = `warehouse`.`focus_company_id` ");
		return $query;
	}

	public function get_assigned_warehouse(){
		$query = $this->db->query(" SELECT `client_storage`.*, `client`.`company_name` AS `client_name`,`client`.`company_id` AS `client_id`, `f_company`.`company_name`,`warehouse`.`location`
			FROM `client_storage`
			INNER JOIN `company_details` `client` ON `client`.`company_id` = `client_storage`.`client_brand_id`
			INNER JOIN `warehouse` ON `warehouse`.`warehouse_id` = `client_storage`.`warehouse_id`
			INNER JOIN `company_details` `f_company` ON `f_company`.`company_id` = `warehouse`.`focus_company_id` WHERE `client_storage`.`is_active` = '1'
			ORDER BY `client_name` ASC ");
		return $query;
	}

	public function remove_warehouse_assignment($id){
		$this->db->query(" UPDATE `client_storage` SET `is_active` = '0' WHERE `client_storage`.`client_storage_id` = '$id'");
	}

	public function insert_company_warehouse($client_brand_id,$warehouse_id){
		$this->db->query(" INSERT INTO `client_storage` (`client_brand_id`, `warehouse_id`) VALUES ( '$client_brand_id', '$warehouse_id') ");
	}

	public function update_defailt_lead_days($days){
		$this->db->query(" UPDATE `static_defaults` SET `client_supply_reminder_dys` = '$days' ");
	}

	public function update_defailt_weeks_delivery($weeks){
		$this->db->query(" UPDATE `static_defaults` SET `weeks_delivery` = '$weeks' ");
	}

	public function get_employee_supply_reminder($focus_company){
		$query = $this->db->query("   SELECT * FROM `client_supply_settings` WHERE `client_supply_settings`.`focus_company` = '$focus_company'  ");
		return $query;
	}

	public function update_employee_supply_reminder($user_id,$reminder_lead_days,$client_supply_settings_id,$cc_email){
		$this->db->query("  UPDATE `client_supply_settings` SET `user_id` = '$user_id', `reminder_lead_days` = '$reminder_lead_days', `cc_email` = '$cc_email' WHERE `client_supply_settings`.`client_supply_settings_id` = '$client_supply_settings_id' ");
	}

	public function add_archive_doc($registry_type){
		$this->db->query(" INSERT INTO `archive_registry_types` ( `registry_name`) VALUES ( '$registry_type') ");
	}

	public function set_assignmnt_archive($user_id,$date_expiry_edt,$archive_id){
 		$this->db->query(" INSERT INTO `archive_registry` ( `registry_type_id`, `user_id`, `expiry`) VALUES ( '$archive_id', '$user_id', '$date_expiry_edt') ");
	}

	public function delete_archive_details($registry_type_id){
	//	$this->db->query(" UPDATE `archive_registry_types` SET `archive_registry_types`.`is_active` = '0' WHERE `archive_registry_types`.`registry_type_id` = '$registry_type_id' ");
	}

	public function update_static_archive_reminder($remind_cc_email,$remind_late_email,$no_of_weeks,$day_remind){
		$this->db->query(" UPDATE `static_defaults` SET `remind_cc_email` = '$remind_cc_email', `remind_late_email` = '$remind_late_email', `no_of_weeks` = '$no_of_weeks', `day_remind` = '$day_remind' WHERE `static_defaults`.`static_defaults_id` = 1 ");
	}

	public function get_archive_types(){
		$query = $this->db->query(" SELECT * FROM `archive_registry_types` WHERE `archive_registry_types`.`is_active` = '1' ORDER BY `archive_registry_types`.`registry_name` ASC ");
		return $query;
	}

	public function fetch_archive_assigned_emp($registry_type_id){
		$query = $this->db->query(" SELECT `archive_registry`.* , CONCAT(`users`.`user_first_name`, ' ', `users`.`user_last_name`) AS `employee_name`, `archive_registry_types`.`registry_name`,`users`.`user_first_name`, `archive_registry_types`.`registry_types_id`
			FROM `archive_registry`  
			Left JOIN `users` ON `users`.`user_id` = `archive_registry`.`user_id`
			LEFT JOIN `archive_registry_types` ON `archive_registry_types`.`registry_types_id` =  `archive_registry`.`registry_type_id`
			WHERE `archive_registry`.`is_active` = '1' AND `archive_registry`.`registry_type_id` = '$registry_type_id' ");
		return $query;
	}

	public function fetch_archive_assigned_to_emp($user_id){
		$query = $this->db->query(" SELECT `archive_registry_types`.`registry_name`, `archive_registry`.`user_id`,`archive_registry`.`registry_type_id`,`archive_registry`.`expiry`,   `archive_registry_types`.`registry_types_id`
			FROM `archive_registry` 
			LEFT JOIN `archive_registry_types` ON `archive_registry_types`.`registry_types_id` = `archive_registry`.`registry_type_id`
			WHERE `archive_registry`.`user_id` = '$user_id'  AND `archive_registry_types`.`is_active` = '1' AND  `archive_registry`.`is_active` = '1'
			ORDER BY `archive_registry_types`.`registry_name` ASC ");
		return $query;
	}



	public function update_doc_type($registry_name,$registry_types_id){
		$this->db->query(" UPDATE `archive_registry_types` SET `registry_name` = '$registry_name' WHERE `archive_registry_types`.`registry_types_id` = '$registry_types_id' ");
	}

	public function remove_archive_name($registry_types_id){
		$this->db->query(" UPDATE `archive_registry_types` SET `is_active` = '0' WHERE `archive_registry_types`.`registry_types_id` = '$registry_types_id' ");
	}

	public function update_archive_details($archive_registry_id,$user_id,$expiry){
		$this->db->query(" UPDATE `archive_registry` SET `user_id` = '$user_id', `expiry` = '$expiry' WHERE `archive_registry`.`archive_registry_id` = '$archive_registry_id' ");
	}

	public function del_archive_details($archive_registry_id){
		$this->db->query(" UPDATE `archive_registry` SET `is_active` = '0' WHERE `archive_registry`.`archive_registry_id` = '$archive_registry_id' ");
	}


/*


;

*/








	public function fetch_labour_cost($id=0){
		if( $id>0 ){
			$query = $this->db->query("SELECT * , `labour_cost`.`leave_loading` AS `lc_leave_loading` FROM `labour_cost` WHERE `labour_cost_id` = '$id' ");			
		}else{
			$query = $this->db->query("SELECT * , `labour_cost`.`leave_loading` AS `lc_leave_loading` FROM `labour_cost` ORDER BY `labour_cost_id` DESC LIMIT 1");
		}
		return $query;
	}

	public function fetch_all_company_focus($custom=''){
		$query = $this->db->query("SELECT `company_details`.`company_id`,`company_details`.`company_name` ,  `address_general`.`suburb` ,  `states`.`name` AS `state_name`  , `contact_number`.`area_code`,  `contact_number`.`office_number`,`email`.`general_email`,`company_details`.`abn`,`company_details`.`acn`,`admin_company`.`parent`, `states`.`shortname`
			FROM  `company_details`			
			LEFT JOIN  `address_detail` ON  `address_detail`.`address_detail_id` =  `company_details`.`address_id` 
			LEFT JOIN  `address_general` ON  `address_general`.`general_address_id` =  `address_detail`.`general_address_id`
			LEFT JOIN `admin_company` ON `admin_company`.`admin_company_details_id` = `company_details`.`company_id`
			LEFT JOIN `contact_number` ON `contact_number`.`contact_number_id` = `admin_company`.`admin_contact_number_id`
			LEFT JOIN `email` ON `email`.`email_id` = `admin_company`.`admin_email_id`
			LEFT JOIN  `states` ON  `states`.`id` =  `address_general`.`state_id`
			WHERE  `company_details`.`company_type_id` =  '5' AND `company_details`.`active` = '1' ".$custom."
			ORDER BY  `company_details`.`company_id` ASC");
		return $query;
	}

	public function fetch_single_company_focus($id){
		$query = $this->db->query("SELECT `admin_company`.`admin_contact_number_id`,`admin_company`.`admin_email_id`,  `states`.`name` AS `state_name`,`company_details`.`company_id`,`company_details`.`company_name`,`company_details`.`abn`,`company_details`.`acn`,`company_details`.`bank_account_id`,`company_details`.`address_id`,`company_details`.`postal_address_id`,`admin_company`.`logo`,`admin_company`.`admin_jurisdiction_state_ids`,`bank_account`.*,`contact_number`.`contact_number_id`,`contact_number`.`area_code`,`contact_number`.`office_number`,`contact_number`.`mobile_number`,`email`.`general_email`
			FROM `company_details`			
			LEFT JOIN  `admin_company` ON  `admin_company`.`admin_company_details_id` =  `company_details`.`company_id`
			LEFT JOIN  `bank_account` ON  `bank_account`.`bank_account_id` =  `company_details`.`bank_account_id`
			LEFT JOIN  `contact_number` ON  `contact_number`.`contact_number_id` =  `admin_company`.`admin_contact_number_id`
			LEFT JOIN   `email` ON  `email`.`email_id` = `admin_company`.`admin_email_id`
			LEFT JOIN  `address_detail` ON  `address_detail`.`address_detail_id` =  `company_details`.`address_id` 
			LEFT JOIN  `address_general` ON  `address_general`.`general_address_id` =  `address_detail`.`general_address_id`
			LEFT JOIN  `states` ON  `states`.`id` =  `address_general`.`state_id`
			WHERE `company_details`.`company_id` = '$id'");
		return $query;
	}

	public function insert_focus_company_details($admin_company_details_id,$admin_contact_number_id,$admin_email_id,$admin_jurisdiction_state_ids,$logo){
		$this->db->query("INSERT INTO `admin_company` (`admin_company_details_id`, `admin_contact_number_id`, `admin_email_id`, `admin_jurisdiction_state_ids`,`logo`) VALUES ('$admin_company_details_id', '$admin_contact_number_id', '$admin_email_id', '$admin_jurisdiction_state_ids','$logo')");
		$last_insert_id = $this->db->insert_id();
		return $last_insert_id;
	}



	public function fetch_user_location($user_id=''){
		if($user_id != ''){
			$query = $this->db->query("SELECT * FROM `employee_location`
				LEFT JOIN  `location_address` ON `location_address`.`location_address_id` =  `employee_location`.`location_address_id` 
				WHERE `employee_location`.`user_id`  = '$user_id'  ");
		}else{
			$query = $this->db->query("SELECT * FROM `location_address` WHERE `location_address`.`active` = '1' ");
		}

		return $query;
	}


	public function remove_location_assignment($user_id){
		$query = $this->db->query("DELETE FROM `employee_location` WHERE `employee_location`.`user_id` = '$user_id' ");
		return $query;
	}

	public function fetch_set_user_location(){
		$query = $this->db->query("SELECT * FROM `employee_location` ORDER BY `employee_location`.`user_id` ASC ");
		return $query;
	}

	public function fetch_users_set_location(){
		$query = $this->db->query("SELECT `users`.`user_id`, `users`.`user_first_name`, `users`.`user_last_name`,`location_address`.`location` FROM `employee_location`
			LEFT JOIN `location_address` ON `location_address`.`location_address_id` = `employee_location`.`location_address_id`
			LEFT JOIN  `users` ON `users`.`user_id` =  `employee_location`.`user_id` 
			WHERE  `users`.`is_active`  = '1' AND  `location_address`.`active` = '1'
			ORDER BY `location_address`.`location` ASC , `users`.`user_first_name` ASC ");
		return $query;
	}

	public function set_user_location($location_address_id,$user_id){
		$this->db->query("INSERT INTO `employee_location` (`location_address_id`, `user_id`) VALUES ( '$location_address_id', '$user_id')");
		$last_insert_id = $this->db->insert_id();
		return $last_insert_id;
	}

	public function add_user_location($location,$x_coordinate,$y_coordinate){
		$this->db->query("INSERT INTO `location_address` (`location`, `x_coordinate`, `y_coordinate`) VALUES ('$location', '$x_coordinate', '$y_coordinate')");
		$last_insert_id = $this->db->insert_id();
		return $last_insert_id;
	}

	public function updat_admin_contact_email($contact_number_id,$email_id,$office_number,$mobile_number,$general_email){
		$query = $this->db->query("UPDATE `contact_number` ,`email` SET `contact_number`.`office_number` = '$office_number',`contact_number`.`mobile_number` = '$mobile_number' ,`email`.`general_email` = '$general_email' WHERE `contact_number`.`contact_number_id` = '$contact_number_id'AND `email`.`email_id` = '$email_id' ");
		return $query;
	}

	public function updat_admin_comp_logo($comp_id,$logo){
		$query = $this->db->query("UPDATE `admin_company` SET `logo` = '$logo' WHERE `admin_company`.`admin_company_details_id` = '$comp_id'");
		return $query;
	}

	public function updat_project_mark_up($kiosk,$full_fitout,$refurbishment,$stripout,$maintenance,$minor_works,$min_kiosk,$min_full_fitout,$min_refurbishment,$min_stripout,$min_maintenance,$min_minor_works,$design_works,$min_design_works,$joinery_only,$min_joinery_only){
		$query = $this->db->query("INSERT INTO `markup` (`kiosk`, `full_fitout`, `refurbishment`, `stripout`, `maintenance`, `minor_works`, `min_kiosk`, `min_full_fitout`, `min_refurbishment`, `min_stripout`, `min_maintenance`, `min_minor_works` , `design_works`,`min_design_works`, `joinery_only`, `min_joinery_only`) VALUES
			('$kiosk', '$full_fitout', '$refurbishment', '$stripout', '$maintenance', '$minor_works','$min_kiosk', '$min_full_fitout', '$min_refurbishment', '$min_stripout', '$min_maintenance', '$min_minor_works' , '$design_works', '$min_design_works', '$joinery_only', '$min_joinery_only')");
		return $this->db->insert_id();
	}

	public function update_insert_quote_deadline($days_deadline=''){


		$q_admin_defaults = $this->fetch_admin_defaults();
		$admin_defs = array_shift($q_admin_defaults->result_array());

		array_shift( $admin_defs  );

		//$admin_defs['admin_default_id'] = $admin_defs['admin_default_id'] + 1;

		// var_dump($admin_defs);
		//echo "<p><strong>------</strong></p>";

		$admin_tbls = implode("`,`",array_keys($admin_defs) ) ;
		$admin_vals = implode("','",$admin_defs) ;

	//	echo "<p><strong>------</strong></p>";

	//	echo "`$admin_tbls`";

	//	echo "<p><strong>------</strong></p>";
	//	echo "'$admin_vals'";


		$query = $this->db->query("INSERT INTO `admin_defaults`(`$admin_tbls`)VALUES('$admin_vals') ");
		$added_id = $this->db->insert_id();

		$this->db->query("UPDATE `admin_defaults` SET `days_quote_deadline` = '$days_deadline' WHERE `admin_defaults`.`admin_default_id` = $added_id ");
		return $added_id;
	}

	public function update_prj_day_rev($day){
		$this->db->query(" UPDATE `static_defaults` SET `prj_review_day` = '$day'  ");
	}

	public function update_po_rev_settings($weeks_old,$reminder_day_no,$days_wip_report,$set_cc_porw,$send_wip_rrtime){



		$this->db->query(" UPDATE `static_defaults` SET  `po_rev_prj_age` = '$weeks_old', `po_rev_day` = '$reminder_day_no', `prj_review_day` = '$days_wip_report', `po_email_cc` = '$set_cc_porw', `send_wip_rrtime` = '$send_wip_rrtime'  ");

 
	}

	public function update_admin_defaults($val){
		$gst_rate = $val['gst-rate'];
		$installation_labour = $val['installation-labour'];
		$standard_labour = $val['standard-labour'];
		$time_half = $val['time-half'];
		$double_time = $val['double-time'];



		$q_admin_defaults = $this->fetch_admin_defaults();
		$admin_defs = array_shift($q_admin_defaults->result_array());

		array_shift( $admin_defs  );
		$admin_tbls = implode("`,`",array_keys($admin_defs) ) ;
		$admin_vals = implode("','",$admin_defs) ;
		$query = $this->db->query("INSERT INTO `admin_defaults`(`$admin_tbls`)VALUES('$admin_vals') ");


	//	$query = $this->db->query("INSERT INTO `admin_defaults` SELECT `$admin_tbls` FROM `admin_defaults` ORDER BY `admin_default_id` DESC LIMIT 1");
		



		$added_id = $this->db->insert_id();




		$this->db->query("UPDATE `admin_defaults` SET `gst_rate` = '$gst_rate', `installation_labour_mark_up` = '$installation_labour', `labor_split_standard` = '$standard_labour', `labor_split_time_and_half` = '$time_half', `labor_split_double_time` = '$double_time'
			WHERE `admin_defaults`.`admin_default_id` = $added_id ");
		return $added_id;



/*
		$query = $this->db->query("SELECT * FROM  `admin_defaults` order by admin_default_id desc");
		$qArr = array_shift($query->result_array());

		$cqr_notes_w_insurance = $qArr['cqr_notes_w_insurance'];
		$cqr_notes_no_insurance = $qArr['cqr_notes_no_insurance'];
		$cpo_notes_w_insurance = $qArr['cpo_notes_w_insurance'];
		$cpo_notes_no_insurance = $qArr['cpo_notes_no_insurance'];

		$this->db->query("INSERT INTO `admin_defaults` (`gst_rate`, `installation_labour_mark_up`, `labor_split_standard`, `labor_split_time_and_half`,`labor_split_double_time`,`cqr_notes_w_insurance`,`cqr_notes_no_insurance`,`cpo_notes_w_insurance`,`cpo_notes_no_insurance`) VALUES ('$gst_rate', '$installation_labour','$standard_labour', '$time_half', '$double_time', '$cqr_notes_w_insurance', '$cqr_notes_no_insurance', '$cpo_notes_w_insurance', '$cpo_notes_no_insurance')");
		return $this->db->insert_id();
*/

	}


	public function insert_latest_system_default($site_cost_id,$admin_default_id,$markup_id,$labour_cost_id){
		$query = $this->db->query("INSERT INTO `defaults` (`site_cost_id`, `admin_default_id`, `markup_id`, `labour_cost_id`) VALUES ('$site_cost_id', '$admin_default_id', '$markup_id', '$labour_cost_id')");
		return $this->db->insert_id();
	}

	public function insert_update_latest_system_defaults($section,$value){

		switch($section){
			case 'admin_default':

					$query = $this->db->query("INSERT INTO `defaults` SELECT NULL, `site_cost_id`, `admin_default_id`, `markup_id`, `labour_cost_id` FROM `defaults` ORDER BY `defaults_id` DESC LIMIT 1");
					$added_id = $this->db->insert_id();

					$this->db->query("UPDATE `defaults` SET `admin_default_id` = '$value' WHERE `defaults`.`defaults_id` = '$added_id' ");
					return $added_id;

				break;
			/*case 'variations':
				break;
			case 'asdase':
				break;*/
			default:
				break;
		}

	}

	public function insert_labour_cost_matrix($superannuation,$workers_compensation,$payroll_tax,$leave_loading,$other,$total_leave_days,$total_work_days){
		$query = $this->db->query("INSERT INTO `labour_cost` (`superannuation`, `payroll_tax`, `total_leave_days`, `workers_compensation`, `leave_loading`, `total_work_days`, `other`) VALUES ('$superannuation','$payroll_tax','$total_leave_days','$workers_compensation','$leave_loading','$total_work_days','$other')");
		return $this->db->insert_id();
	}

	public function update_static_settings($days_psswrd_exp,$temp_user_psswrd){
		$query = $this->db->query("UPDATE `static_defaults` SET `days_psswrd_exp` = '$days_psswrd_exp', `temp_user_psswrd` = '$temp_user_psswrd' WHERE `static_defaults`.`static_defaults_id` = 1 ");
	}

	public function select_leave_rates($leave_total_rates_id){
		$query = $this->db->query("SELECT * FROM `leave_total_rates` WHERE `leave_total_rates_id` = '$leave_total_rates_id'");
		return $query;
	}

	public function update_static_settings_leave_rates($annual_leave,$personal_leave,$leave_total_rates_id){
		$query = $this->db->query("UPDATE `leave_total_rates` SET `annual_leave` = '$annual_leave', `personal_leave` = '$personal_leave' WHERE leave_total_rates_id = '$leave_total_rates_id'");
	}

	public function update_static_settings_leave_rates_rdo($annual_leave,$personal_leave,$rdo_leave,$leave_total_rates_id){
		$query = $this->db->query("UPDATE `leave_total_rates` SET `annual_leave` = '$annual_leave', `personal_leave` = '$personal_leave', `rdo_leave` = '$rdo_leave' WHERE leave_total_rates_id = '$leave_total_rates_id'");
	}

	public function fetch_users_by_leave_rate($leave_rate_type){
		$query = $this->db->query("SELECT `user_id` FROM `leave_allocation` WHERE `leave_rate_type` = '$leave_rate_type' AND `is_active` = '1' GROUP BY `user_id`");
		return $query;
	}

	public function update_leave_request_to_disabled($user_id){
		$query = $this->db->query("UPDATE `leave_request` SET `is_active` = '0' WHERE `user_id` = '$user_id'");
	}

	public function update_leave_alloc_to_inactive($leave_total_rates_id){
		$query = $this->db->query("UPDATE `leave_allocation` SET `is_active` = '0' WHERE `leave_rate_type` = '$leave_total_rates_id' AND `is_active` = '1'");
	}

	public function update_static_settings_onboarding_email($recipient_email,$optional_cc_email){
		$query = $this->db->query("UPDATE `static_defaults` SET `onboarding_to` = '$recipient_email', `onboarding_cc` = '$optional_cc_email' WHERE `static_defaults`.`static_defaults_id` = 1");
	}

	public function update_static_settings_invoice_email($recipient_email,$optional_cc_email){
		$query = $this->db->query("UPDATE `static_defaults` SET `invoice_to` = '$recipient_email', `invoice_cc` = '$optional_cc_email' WHERE `static_defaults`.`static_defaults_id` = 1");
	}

	public function update_static_settings_onboarding_contractor_msg($onboarding_contractor_msg){
		$query = $this->db->query("UPDATE `static_defaults` SET `onboarding_contractor_msg` = '$onboarding_contractor_msg' WHERE `static_defaults`.`static_defaults_id` = 1");
	}

	public function update_static_settings_onboarding_general_msg($onboarding_general_msg){
		$query = $this->db->query("UPDATE `static_defaults` SET `onboarding_general_msg` = '$onboarding_general_msg' WHERE `static_defaults`.`static_defaults_id` = 1");
	}

	public function update_static_settings_workplace_health_safety_msg($workplace_health_safety_msg){
		$query = $this->db->query("UPDATE `static_defaults` SET `workplace_health_safety_msg` = '$workplace_health_safety_msg' WHERE `static_defaults`.`static_defaults_id` = 1");
	}

	public function update_static_settings_swms_msg($swms_msg){
		$query = $this->db->query("UPDATE `static_defaults` SET `swms_msg` = '$swms_msg' WHERE `static_defaults`.`static_defaults_id` = 1");
	}

	public function update_static_settings_jsa_msg($jsa_msg){
		$query = $this->db->query("UPDATE `static_defaults` SET `jsa_msg` = '$jsa_msg' WHERE `static_defaults`.`static_defaults_id` = 1");
	}

	public function update_static_settings_reviewed_swms_msg($reviewed_swms_msg){
		$query = $this->db->query("UPDATE `static_defaults` SET `reviewed_swms_msg` = '$reviewed_swms_msg' WHERE `static_defaults`.`static_defaults_id` = 1");
	}

	public function update_static_settings_safety_related_convictions_msg($safety_related_convictions_msg){
		$query = $this->db->query("UPDATE `static_defaults` SET `safety_related_convictions_msg` = '$safety_related_convictions_msg' WHERE `static_defaults`.`static_defaults_id` = 1");
	}

	public function update_static_settings_confirm_licences_certifications_msg($confirm_licences_certifications_msg){
		$query = $this->db->query("UPDATE `static_defaults` SET `confirm_licences_certifications_msg` = '$confirm_licences_certifications_msg' WHERE `static_defaults`.`static_defaults_id` = 1");
	}

	public function update_abn_acn_jurisdiction($abn,$acn,$jurisdiction_id,$comp_id){
		$query = $this->db->query("UPDATE `company_details` ,`admin_company` SET `company_details`.`abn` = '$abn', `company_details`.`acn` = '$acn', `admin_company`.`admin_jurisdiction_state_ids` = '$jurisdiction_id' WHERE `company_details`.`company_id` = '$comp_id' AND `admin_company`.`admin_company_details_id` = '$comp_id'");
		return $query;
	}

	public function update_amalgamated_rate($amalgamated_rate){
		$query = $this->db->query("UPDATE `site_costs` SET `total_amalgamated_rate` = '$amalgamated_rate' ORDER BY `site_cost_id` DESC LIMIT 1");
		return $query;
	}

	public function pm_pa_assignment($project_administrator_id, $project_manager_ids,$primary_pm){
		$query = $this->db->query("INSERT INTO `project_administrator_manager` (`project_administrator_id`, `project_manager_ids` ,`project_manager_primary_id`) 
			VALUES ( '$project_administrator_id', '$project_manager_ids' ,'$primary_pm') 
			ON DUPLICATE KEY UPDATE `project_manager_ids` = '$project_manager_ids' , `project_manager_primary_id` = '$primary_pm' ");
		return $query;
	}

	public function fetch_pa_assignment($pa_id){
		$query = $this->db->query("SELECT * FROM `project_administrator_manager` WHERE `project_administrator_manager`.`project_administrator_id` = '$pa_id' ");
		return $query;
	}
	
	public function update_site_costs($val){
		$total_days = $val['total_days'];
		$hour_rate = $val['hour_rate'];
		$hours = $val['hours'];
		$superannuation = $val['superannuation'];
		$workers_comp = $val['workers-comp'];
		$public_holidays_raw = $val['public-holidays_raw'];
		$rdos_raw = $val['rdos_raw'];
		$sick_leave_raw = $val['sick-leave_raw'];
		$carers_leave_raw = $val['carers-leave_raw'];
		$annual_leave_raw = $val['annual-leave_raw'];
		$downtime = $val['downtime'];
		$leave_loading = $val['leave-loading'];
		$hour_rate_comp = $val['hour_rate_comp'];
		$time_half_rate_comp = $val['time_half_rate_comp'];
		$double_time_rate_comp = $val['double_time_rate_comp'];
		$total_amalgamated_rate = $val['total_amalgamated_rate'];

		$this->db->query("INSERT INTO `site_costs` (`total_days`,`rate`,`total_amalgamated_rate`,`hours`,`super_annuation`,`worker_compensation`,`public_holidays`,`rdos`,`sick_leave`, `carers_leave`, `annual_leave`, `down_time`,`leave_loading`,`total_hour`,`total_time_half`,`total_double_time` ) VALUES
			('$total_days','$hour_rate','$total_amalgamated_rate','$hours','$superannuation','$workers_comp','$public_holidays_raw','$rdos_raw','$sick_leave_raw','$carers_leave_raw','$annual_leave_raw','$downtime','$leave_loading','$hour_rate_comp','$time_half_rate_comp','$double_time_rate_comp')");

		return $this->db->insert_id();
		
	}
	
	public function fetch_all_company($comp_id){
		if($comp_id==''){
			$query = $this->db->query("SELECT * FROM  `company_details` WHERE  `company_type_id` <> 4");
			return $query;
		}else{
			$query = $this->db->query("SELECT * FROM  `company_details` WHERE  `company_id` = '$comp_id'");
			return $query;
		}
	}
	
	public function fetch_all_company_type_id($comp_id){
		$query = $this->db->query("SELECT * FROM  `company_details` WHERE  `company_type_id` = '$comp_id' ORDER BY  `company_details`.`company_name` ASC ");
		return $query;
	}
	
	public function insert_new_contact_person($val){
		#insert new contact number if has value and get the contact_number_id after
		
		$first_name = $val['contact_first_name'];
		$last_name = $val['contact_last_name'];
		$gender = $val['contact_gender'];
		$email = $val['contact_email'];
		$contact_number = $val['contact_contact_number'];
		$company = $val['contact_company'];
		$contact_number_id = 0;
		$contact_comp_id = 0;
		$this->db->query("INSERT INTO `email` (`general_email`, `direct`, `accounts`, `maintenance`) VALUES ('$email', '', '', '')");
		$contact_email_id = $this->db->insert_id();
		
		$this->db->query("INSERT INTO `contact_number` (`contact_number_id`, `area_code`, `office_number`, `direct_number`, `mobile_number`) VALUES (NULL, '0', '0', '$contact_number', '0')");
		$contact_number_id = $this->db->insert_id();
		
		$select_id_q = $this->db->query("SELECT  `company_id` FROM  `company_details` WHERE  `company_name` =  '$company'");
		foreach ($select_id_q->result_array() as $row){
		   $contact_comp_id = $row['company_id'];
		}
		
		$this->db->query("INSERT INTO `contact_person` (`first_name`, `last_name`, `gender`, `email_id`, `contact_number_id`, `company_id`) VALUES ('$first_name', '$last_name', '$gender', '$contact_email_id', '$contact_number_id', '$contact_comp_id')");
		//$contact_number_id = $this->db->insert_id();
		#insert new contact number if has value and get the contact_number_id after		
	}
	
	public function fetch_all_client_types(){
		$query = $this->db->query("SELECT * FROM  `client_category` ORDER BY  `client_category`.`client_category_name` ASC");
		return $query;
	}
	
	public function fetch_all_contact_persons($id){
		if($id==''){
			$query = $this->db->query("SELECT * FROM  `contact_person` ORDER BY  `contact_person`.`first_name` ASC ");
		}else{
			$query = $this->db->query("SELECT * FROM  `contact_person` WHERE `contact_person`.`contact_person_id`='$id' ");
		}return $query;
	}
	
	public function fetch_all_contractor_types(){
		$query = $this->db->query("SELECT * FROM  `job_category` ORDER BY  `job_category`.`job_category` ASC ");
		return $query;
	}
	
	public function fetch_all_supplier_types(){
		$query = $this->db->query("SELECT * FROM  `supplier_cat` ORDER BY  `supplier_cat`.`supplier_cat_name` ASC ");
		return $query;
	}
	
	public function fetch_company_details($data){
		if(isset($data)){
			$query = $this->db->query("SELECT * FROM `company_details` WHERE `company_details`.`company_id` = '$data'");
			return $query;
		}else{
			$query = $this->db->query("SELECT * FROM  `company_details` ORDER BY  `company_details`.`company_name` ASC");
			return $query;
		}		 
	}
	
	public function count_company_by_type(){
		$query = $this->db->query("SELECT COUNT(`company_details`.`company_type_id`) AS `counts`, `company_details`.`company_type_id`,`company_type`.`company_type`
			FROM `company_details`,`company_type`
			WHERE `company_type`.`company_type_id` = `company_details`.`company_type_id` GROUP BY `company_details`.`company_type_id`");
		return $query;
	}
	
	public function display_company_by_type($type){
		$query = $this->db->query("SELECT `company_details`.`company_id`,`company_details`.`company_name` ,  `address_general`.`suburb` ,  `states`.`shortname` ,  `contact_number`.`area_code` , `contact_number`.`office_number` ,  `email`.`general_email` 
			FROM  `company_details` INNER JOIN  `email` ON  `email`.`email_id` =  `company_details`.`email_id` 
			LEFT JOIN  `contact_number` ON  `contact_number`.`contact_number_id` =  `company_details`.`contact_number_id` 
			LEFT JOIN  `address_detail` ON  `address_detail`.`address_detail_id` =  `company_details`.`address_id` 
			LEFT JOIN  `address_general` ON  `address_general`.`general_address_id` =  `address_detail`.`general_address_id` 
			LEFT JOIN  `states` ON  `states`.`id` =  `address_general`.`state_id` WHERE  `company_details`.`company_type_id` =  '$type'
			ORDER BY  `company_details`.`company_name` ASC ");
		return $query;
	}
	
	public function display_company_detail_by_id($id){
		$query = $this->db->query("SELECT `address_general`.`suburb`,`address_general`.`postcode`,`contact_number`.`area_code`,`contact_number`.*,`email`.*,`company_type`.`company_type`,`company_details`.`activity_id`,`company_details`.`parent_company_id` ,`company_details`.`company_type_id`,  `company_details`.`notes_id`,`company_details`.`email_id`, `company_details`.`contact_number_id`,`company_details`.`postal_address_id`,`company_details`.`address_id`
			FROM  `company_details`
			LEFT JOIN  `email` ON  `email`.`email_id` =  `company_details`.`email_id`
			LEFT JOIN  `contact_number` ON  `contact_number`.`contact_number_id` =  `company_details`.`contact_number_id`
			LEFT JOIN  `address_detail` ON  `address_detail`.`address_detail_id` =  `company_details`.`address_id`
			LEFT JOIN  `address_general` ON  `address_general`.`general_address_id` =  `address_detail`.`general_address_id`
			LEFT JOIN  `company_type` ON  `company_type`.`company_type_id` =  `company_details`.`company_type_id`
			WHERE   `company_details`.`company_id` =  '$id'");
		return $query;
	}
	
	
	public function fetch_notes($data){
		$query = $this->db->query("SELECT * FROM  `notes` WHERE  `notes`.`notes_id` = '$data'");
		return $query;
	}
	
	public function fetch_email($data){
		$query = $this->db->query("SELECT * FROM  `email` WHERE  `email`.`email_id` = '$data'");
		return $query;
	}
	
	public function fetch_phone($data){
		$query = $this->db->query("SELECT * FROM `contact_number` WHERE `contact_number`.`contact_number_id` = '$data'");
		return $query;
	}
	
	public function fetch_complete_address($data){
		$query = $this->db->query("SELECT * FROM `address_detail`, `address_general` ,`states` WHERE ((`address_detail_id` = '$data' AND `address_general`.`general_address_id` = `address_detail`.`general_address_id`) AND `states`.`id` = `address_general`.`state_id`)");
		return $query;
	}
	
	public function fetch_company_activity_name_by_type($company_type_name,$type){
		if($company_type_name == 'Client'){
			$query = $this->db->query("SELECT `client_category`.`client_category_name` AS `activity`  FROM `client_category` WHERE `client_category`.`client_category_id` = '$type'");
			$qArr = array_shift($query->result_array());
			return $qArr['activity'];
		}else if($company_type_name == 'Contractor'){
			$query = $this->db->query("SELECT `job_category`.`job_category` AS `activity` FROM `job_category` WHERE `job_category`.`job_category_id` = '$type'");
			$qArr = array_shift($query->result_array());
			return $qArr['activity'];			
		}else if($company_type_name == 'Supplier'){
			$query = $this->db->query("SELECT `supplier_cat`.`supplier_cat_name` AS `activity` FROM `supplier_cat` WHERE  `supplier_cat`.`supplier_cat_id` =  '$type'");
			$qArr = array_shift($query->result_array());
			return $qArr['activity'];
		}else{}
	}
	
	public function fetch_activity_id_by_company_type($company_type_id,$type){
		if($company_type_id == 1){
			$query = $this->db->query("SELECT * FROM  `client_category` WHERE  `client_category`.`client_category_name` =  '$type'");
			$qArr = array_shift($query->result_array());
			return $qArr['client_category_id'];
		}else if($company_type_id == 2){
			$query = $this->db->query("SELECT * FROM `job_category` WHERE `job_category`.`job_category` = '$type'");
			$qArr = array_shift($query->result_array());
			return $qArr['job_category_id'];			
		}else if($company_type_id == 3){
			$query = $this->db->query("SELECT * FROM  `supplier_cat` WHERE  `supplier_cat`.`supplier_cat_name` =  '$type'");
			$qArr = array_shift($query->result_array());
			return $qArr['supplier_cat_id'];
		}else{}
	}
	
	public function update_company($data){
		$company_id = $data['company_id'];
		
		$company_name = $data['company_name'];
		
		$unit_level_a = $data['unit_level'];
		$unit_number_a = $data['unit_number'];
		$street_a = $data['street'];
		$suburb_a = $data['suburb_a'];
		$state_a = $data['state_a'];
		$postcode_a = $data['postcode_a'];
				
		$pobox = $data['pobox'];
		$unit_level_b = $data['unit_level_b'];
		$number_b = $data['number_b'];
		$street_b = $data['street_b'];
		$suburb_b = $data['suburb_b'];
		$state_b = $data['state_b'];
		$postcode_b = $data['postcode_b'];
		
		$abn = $data['abn'];
		$acn = $data['acn'];
		$staxnum = $data['staxnum'];
		$activity = $data['activity'];
		$parent = $data['parent'];
		
		$areacode = $data['areacode'];
		$officeNumber = $data['officeNumber'];
		$directNumber = $data['directNumber'];
		$mobileNumber = $data['mobileNumber'];
		
		$generalEmail = $data['generalEmail'];
		$directEmail = $data['directEmail'];
		$accountsEmail = $data['accountsEmail'];
		$maintenanceEmail = $data['maintenanceEmail'];
		
		$contact_number_id = $data['contact_number_id'];
		$email_id = $data['email_id'];
		$comments = $data['comments'];
		$type = $data['type'];
		
		$address_id = $data['address_id'];
		$postal_address_id = $data['postal_address_id'];
		
		$contactperson = $data['contactperson'];
				
		$notes_id = $data['notes_id'];
				
		//$this->db->trans_begin();
		//echo "SELECT `general_address_id` FROM `address_general` WHERE `suburb` = '$suburb_a' AND `postcode`='$postcode_a'";
		#select the general_address_id SET A
		$query = $this->db->query("SELECT `general_address_id` FROM `address_general` WHERE `suburb` = '$suburb_a' AND `postcode`='$postcode_a' ");
		$qArr = array_shift($query->result_array());
		$general_address_id_a = $qArr['general_address_id'];
				
		//echo $general_address_id_a;
		#select the general_address_id SET A
				
		#update address_detail_id if has value and get the address_detail_id after SET A
		//echo "UPDATE `address_detail` SET `unit_number` =  '$unit_number_a',`unit_level` =  '$unit_level_a',`street` =  '$street_a',`po_box` =  '' WHERE `address_detail_id` = '$address_id' ";
		$query = $this->db->query("UPDATE `address_detail` SET `unit_number` =  '$unit_number_a',`unit_level` =  '$unit_level_a',`street` =  '$street_a',`po_box` =  '',`general_address_id`='$general_address_id_a' WHERE `address_detail_id` = '$address_id' ");
		//$address_detail_id_a = $this->db->insert_id();
		#update address_detail_id if has value and get the address_detail_id after SET A
		
		#select the general_address_id SET B
		$query = $this->db->query("SELECT `general_address_id` FROM `address_general` WHERE `suburb` = '$suburb_b' AND `postcode`='$postcode_b' ");
		$qArr = array_shift($query->result_array());
		$general_address_id_b = $qArr['general_address_id'];
		#select the general_address_id SET B
		
		#update address_detail_id if has value and get the address_detail_id after SET B
		$query = $this->db->query("UPDATE  `address_detail` SET `unit_number` =  '$number_b',`unit_level` =  '$unit_level_b',`street` =  '$street_b',`po_box` =  '$pobox',`general_address_id`='$general_address_id_b' WHERE  `address_detail`.`address_detail_id` = '$postal_address_id' ");
		#update address_detail_id if has value and get the address_detail_id after SET B
		
		#update contact number if has value and get the contact_number_id after
		$this->db->query("UPDATE  `contact_number` SET `area_code`='$areacode' , `office_number` =  '$officeNumber', `direct_number` =  '$directNumber', `mobile_number` =  '$mobileNumber' WHERE  `contact_number_id` ='$contact_number_id' ");
		#update contact number if has value and get the contact_number_id after
				
		#insert new email if has value and get the email ID after
		$this->db->query("UPDATE  `email` SET  `general_email`='$generalEmail', `direct`='$directEmail', `accounts`= '$accountsEmail', `maintenance`='$maintenanceEmail' WHERE `email_id` = '$email_id' ");
		//$email_id = $this->db->insert_id();
		#insert new email if has value and get the email ID after
				
		//$address_detail_id_b = $this->db->insert_id();
		#insert address_detail_id if has value and get the address_detail_id after SET B
		
		#select the company_type_id
		$query = $this->db->query("SELECT `company_type`.`company_type_id` FROM `company_type` WHERE `company_type`.`company_type` = '$type' ");
		$qArr = array_shift($query->result_array());
		$company_type_id = $qArr['company_type_id'];
		#select the company_type_id
				
		#select the activity_id
		$activity_id = $this->fetch_activity_id_by_company_type($company_type_id,$activity);
		#select the activity_id
		
		#UPDATE notes if has value and get the notes ID after		
		$this->db->query("UPDATE  `notes` SET  `comments` =  '$comments' WHERE  `notes`.`notes_id` ='$notes_id' ");
		//$notes_id = $this->db->insert_id();
		#UPDATE notes if has value and get the notes ID after
					
		#select the company_id from the company details
		$query = $this->db->query("SELECT `company_id` FROM  `company_details` WHERE  `company_details`.`company_name` =  '$parent'");
		$qArr = array_shift($query->result_array());
		$parent_id = $qArr['company_id'];
		#select the company_id from the company details
		
		#select the company_type_id
		$query = $this->db->query("SELECT `company_type`.`company_type_id` FROM `company_type` WHERE `company_type`.`company_type` = '$type' ");
		$qArr = array_shift($query->result_array());
		$company_type_id = $qArr['company_type_id'];
		#select the company_type_id
		
		#select the activity_id
		$activity_id = $this->fetch_activity_id_by_company_type($company_type_id,$activity);
		#select the activity_id
		
		#select the contact_person_id from the contact_person
		$arr_con_name = explode('|',$contactperson);
		$con_f_name = trim($arr_con_name[0]);
		$con_l_name = trim($arr_con_name[1]);
		
		//echo $con_f_name.'-'.$con_l_name;
		
		$query = $this->db->query("SELECT `contact_person`.`contact_person_id` FROM  `contact_person` WHERE `contact_person`.`first_name` = '$con_f_name' AND `contact_person`.`last_name` = '$con_l_name' ");
		$qArr = array_shift($query->result_array());
		$contact_pserson_id = $qArr['contact_person_id'];
		#select the contact_person_id from the contact_person
		 
		#update the company details
		$this->db->query("UPDATE `company_details` SET `company_name` = '$company_name', `abn` = '$abn', `acn` = '$acn',`primary_contact_person_id`='$contact_pserson_id', `stax_number` = '$staxnum', `parent_company_id`='$parent_id',  `activity_id`='$activity_id', `company_type_id`='$company_type_id' WHERE `company_details`.`company_id` = '$company_id'");
		#update the company details
	}
	
	public function insert_new_company($data){
		$company_name = $data['company_name'];
		
		$unit_level_a = $data['unit_level'];
		$unit_number_a = $data['unit_number'];
		$street_a = $data['street'];
		$suburb_a = $data['suburb_a'];
		$state_a = $data['state_a'];
		$postcode_a = $data['postcode_a'];
		
		$pobox = $data['pobox'];
		$unit_level_b = $data['unit_level_b'];
		$number_b = $data['number_b'];
		$street_b = $data['street_b'];
		$suburb_b = $data['suburb_b'];
		$state_b = $data['state_b'];
		$postcode_b = $data['postcode_b'];
		
		$abn = $data['abn'];
		$acn = $data['acn'];
		$staxnum = $data['staxnum'];
		$activity = $data['activity'];
		$parent = $data['parent'];
		
		$areacode = $data['areacode'];
		$officeNumber = $data['officeNumber'];
		$directNumber = $data['directNumber'];
		$mobileNumber = $data['mobileNumber'];
		
		$generalEmail = $data['generalEmail'];
		$directEmail = $data['directEmail'];
		$accountsEmail = $data['accountsEmail'];
		$maintenanceEmail = $data['maintenanceEmail'];
		
		$contactperson = $data['contactperson'];
			
		$comments = $data['comments'];		
		
		$type = $data['type'];
				
		$this->db->trans_begin();
		
		#select the general_address_id SET A
		$query = $this->db->query("SELECT `general_address_id` FROM `address_general` WHERE `suburb` = '$suburb_a' AND `postcode`='$postcode_a' ");
		$qArr = array_shift($query->result_array());
		$general_address_id_a = $qArr['general_address_id'];
		#select the general_address_id SET A
		
		#insert address_detail_id if has value and get the address_detail_id after SET A
		$query = $this->db->query("INSERT INTO `address_detail` (`address_detail_id`, `unit_number`, `unit_level`, `street`, `po_box`, `general_address_id`) VALUES ('NULL', '$unit_number_a', '$unit_level_a', '$street_a', '', '$general_address_id_a')");
		$address_detail_id_a = $this->db->insert_id();
		#insert address_detail_id if has value and get the address_detail_id after SET A
		
		#select the general_address_id SET B
		$query = $this->db->query("SELECT `general_address_id` FROM `address_general` WHERE `suburb` = '$suburb_b' AND `postcode`='$postcode_b' ");
		$qArr = array_shift($query->result_array());
		$general_address_id_b = $qArr['general_address_id'];
		#select the general_address_id SET B
		
		#insert address_detail_id if has value and get the address_detail_id after SET B
		$query = $this->db->query("INSERT INTO `address_detail` (`address_detail_id`, `unit_number`, `unit_level`, `street`, `po_box`, `general_address_id`) VALUES ('NULL', '$number_b', '$unit_level_b', '$street_b', '$pobox', '$general_address_id_b')");
		$address_detail_id_b = $this->db->insert_id();
		#insert address_detail_id if has value and get the address_detail_id after SET B
		
		#select the company_type_id
		$query = $this->db->query("SELECT `company_type`.`company_type_id` FROM `company_type` WHERE `company_type`.`company_type` = '$type' ");
		$qArr = array_shift($query->result_array());
		$company_type_id = $qArr['company_type_id'];
		#select the company_type_id		
		
		#select the activity_id
		$activity_id = $this->fetch_activity_id_by_company_type($company_type_id,$activity);
		#select the activity_id
				
		#select the company_id from the company details
		$query = $this->db->query("SELECT `company_id` FROM  `company_details` WHERE  `company_details`.`company_name` =  '$parent'");
		$qArr = array_shift($query->result_array());
		$company_id = $qArr['company_id'];
		#select the company_id from the company details
		
		#select the contact_person_id from the contact_person
		$arr_con_name = explode('|',$contactperson);
		$con_f_name = trim($arr_con_name[0]);
		$con_l_name = trim($arr_con_name[1]);
		
		//echo $con_f_name.'-'.$con_l_name;
		
		$query = $this->db->query("SELECT `contact_person`.`contact_person_id` FROM  `contact_person` WHERE `contact_person`.`first_name` = '$con_f_name' AND `contact_person`.`last_name` = '$con_l_name' ");
		$qArr = array_shift($query->result_array());
		$contact_pserson_id = $qArr['contact_person_id'];
		#select the contact_person_id from the contact_person
		
		#insert notes if has value and get the notes ID after		
		$this->db->query("INSERT INTO `notes` (`notes_id`, `comments`, `notes`) VALUES (NULL, '$comments', '') ");
		$notes_id = $this->db->insert_id();
		#insert notes if has value and get the notes ID after
		
		#insert new email if has value and get the email ID after
		$this->db->query("INSERT INTO `email` (`email_id`, `general_email`, `direct`, `accounts`, `maintenance`) VALUES (NULL, '$generalEmail', '$directEmail', '$accountsEmail', '$maintenanceEmail')");
		$email_id = $this->db->insert_id();
		#insert new email if has value and get the email ID after
				
		#insert new contact number if has value and get the contact_number_id after
		$this->db->query("INSERT INTO `contact_number` (`contact_number_id`, `area_code`, `office_number`, `direct_number`, `mobile_number`) VALUES (NULL, '$areacode', '$officeNumber', '$directNumber', '$mobileNumber')");
		$contact_number_id = $this->db->insert_id();
		#insert new contact number if has value and get the contact_number_id after
				
		$query = $this->db->query("INSERT INTO `company_details`
		(`company_id`, `company_name`, `abn`, `acn`, `stax_number`, `activity_id`, `notes_id`, `email_id`, `contact_number_id`, `primary_contact_person_id`, `address_id`,`postal_address_id`, `company_type_id`, `parent_company_id`) VALUES
		(NULL,'$company_name' , '$abn', '$acn', '$staxnum', '$activity_id', '$notes_id', '$email_id', '$contact_number_id', '$contact_pserson_id', '$address_detail_id_a','$address_detail_id_b', '$company_type_id', '$company_id')");
		$last_insert_id = $this->db->insert_id();
		$this->db->trans_complete();
		return $last_insert_id;
	}	

	public function insert_defaults_notes($notes){
		$this->db->query("INSERT INTO `notes` (`notes`) VALUES ( '$notes') ");
		$notes_id = $this->db->insert_id();
		return $notes_id;
	}

	public function update_defaults_notes($cqr_notes_w_ins,$cqr_notes_no_ins,$cpo_notes_w_ins,$cpo_notes_no_ins){
		// $query = $this->db->query("INSERT INTO `admin_defaults` SELECT NULL, `gst_rate`, `installation_labour_mark_up`, `labor_split_standard`, `labor_split_time_and_half`, `labor_split_double_time`, `cqr_notes_w_insurance`, `cqr_notes_no_insurance`, `cpo_notes_w_insurance`, `cpo_notes_no_insurance`, `unaccepted_no_days`, `days_quote_deadline`, `unaccepted_date_categories`, `labour_sched_categories` FROM `admin_defaults` ORDER BY `admin_default_id` DESC LIMIT 1");
		// $added_id = $this->db->insert_id();

		// $this->db->query("UPDATE `admin_defaults` SET `cqr_notes_w_insurance` = '$cqr_notes_w_ins', `cqr_notes_no_insurance` = '$cqr_notes_no_ins', `cpo_notes_w_insurance` = '$cpo_notes_w_ins', `cpo_notes_no_insurance` = '$cpo_notes_no_ins'
		// 	WHERE `admin_defaults`.`admin_default_id` = $added_id ");
		$this->db->query("UPDATE `admin_defaults` SET `cqr_notes_w_insurance` = '$cqr_notes_w_ins', `cqr_notes_no_insurance` = '$cqr_notes_no_ins', `cpo_notes_w_insurance` = '$cpo_notes_w_ins', `cpo_notes_no_insurance` = '$cpo_notes_no_ins'");
		return $added_id;
	}

	public function fetch_admin_default_email_message($section = ''){
		if($section == ''){
			$query = $this->db->query("SELECT * FROM `default_email_messages` where section = 'insurance'");
		}else{
			$query = $this->db->query("SELECT * FROM `default_email_messages` where section = '$section'");
		}
		
		return $query;
	}

	public function update_admin_default_email_message($sender_name_no_insurance,$sender_email_no_insurnace,$subject_no_insurnace,$email_msg_no_insurance,$bcc_email_no_insurnace,$user_id){
		$this->db->query("UPDATE `default_email_messages` SET `message_content` = '$email_msg_no_insurance', `sender_name` = '$sender_name_no_insurance', `sender_email` = '$sender_email_no_insurnace',`bcc_email` = '$bcc_email_no_insurnace', `subject` =  '$subject_no_insurnace', user_id = '$user_id'  WHERE default_email_messages_id = 1 ");
	}

	public function update_admin_default_email_message_induction_new($sender_name_no_insurance,$sender_email_no_insurnace,$subject_no_insurnace,$email_msg_no_insurance,$bcc_email_no_insurnace,$user_id){
		$this->db->query("UPDATE `default_email_messages` SET `message_content` = '$email_msg_no_insurance', `sender_name` = '$sender_name_no_insurance', `sender_email` = '$sender_email_no_insurnace',`bcc_email` = '$bcc_email_no_insurnace', `subject` =  '$subject_no_insurnace', user_id = '$user_id'  WHERE default_email_messages_id = 2 ");
	}

	public function update_admin_default_email_message_induction_update($sender_name_no_insurance,$sender_email_no_insurnace,$subject_no_insurnace,$email_msg_no_insurance,$bcc_email_no_insurnace,$user_id){
		$this->db->query("UPDATE `default_email_messages` SET `message_content` = '$email_msg_no_insurance', `sender_name` = '$sender_name_no_insurance', `sender_email` = '$sender_email_no_insurnace',`bcc_email` = '$bcc_email_no_insurnace', `subject` =  '$subject_no_insurnace', user_id = '$user_id'  WHERE default_email_messages_id = 3 ");
	}

	public function update_admin_default_email_message_induction_video($sender_name_induction,$sender_email_induction,$subject_induction,$email_msg_induction_video,$bcc_email_induction,$user_id,$default_email_messages_id){
		$this->db->query("UPDATE `default_email_messages` SET `message_content` = '$email_msg_induction_video', `sender_name` = '$sender_name_no_insurance', `sender_email` = '$sender_email_no_insurnace',`bcc_email` = '$bcc_email_no_insurnace', `subject` =  '$subject_no_insurnace', user_id = '$user_id'  WHERE default_email_messages_id = '$default_email_messages_id' ");
	}

	public function update_admin_default_email_message_onboarding_clients($sender_name_onboarding_clients,$sender_email_onboarding_clients,$subject_onboarding_clients,$email_msg_onboarding_clients,$bcc_email_onboarding_clients,$user_id_clients){
		$this->db->query("UPDATE `default_email_messages` SET `message_content` = '$email_msg_onboarding_clients', `sender_name` = '$sender_name_onboarding_clients', `sender_email` = '$sender_email_onboarding_clients',`bcc_email` = '$bcc_email_onboarding_clients', `subject` =  '$subject_onboarding_clients', user_id = '$user_id_clients'  WHERE default_email_messages_id = 11 ");
	}

	public function update_admin_default_email_message_onboarding($sender_name_onboarding,$sender_email_onboarding,$subject_onboarding,$email_msg_onboarding,$bcc_email_onboarding,$user_id){
		$this->db->query("UPDATE `default_email_messages` SET `message_content` = '$email_msg_onboarding', `sender_name` = '$sender_name_onboarding', `sender_email` = '$sender_email_onboarding',`bcc_email` = '$bcc_email_onboarding', `subject` =  '$subject_onboarding', user_id = '$user_id'  WHERE default_email_messages_id = 4 ");
	}

	public function update_admin_default_email_message_onboarding_bank($sender_name_onboarding_bank,$sender_email_onboarding_bank,$subject_onboarding_bank,$email_msg_onboarding_bank,$bcc_email_onboarding_bank,$user_id){
		$this->db->query("UPDATE `default_email_messages` SET `message_content` = '$email_msg_onboarding_bank', `sender_name` = '$sender_name_onboarding_bank', `sender_email` = '$sender_email_onboarding_bank',`bcc_email` = '$bcc_email_onboarding_bank', `subject` =  '$subject_onboarding_bank', user_id = '$user_id'  WHERE default_email_messages_id = 14 ");
	}

	public function update_admin_default_email_message_onboarding_notif($sender_name_onboarding_notif,$sender_email_onboarding_notif,$subject_onboarding_notif,$email_msg_onboarding_notif,$bcc_email_onboarding_notif,$user_id){
		$this->db->query("UPDATE `default_email_messages` SET `message_content` = '$email_msg_onboarding_notif', `sender_name` = '$sender_name_onboarding_notif', `sender_email` = '$sender_email_onboarding_notif',`bcc_email` = '$bcc_email_onboarding_notif', `subject` =  '$subject_onboarding_notif', user_id = '$user_id'  WHERE default_email_messages_id = 6 ");
	}

	public function update_admin_default_email_message_onboarding_approved_clients($sender_name_onboarding_approved,$sender_email_onboarding_approved,$subject_onboarding_approved,$email_msg_onboarding_approved,$bcc_email_onboarding_approved,$user_id){
		$this->db->query("UPDATE `default_email_messages` SET `message_content` = '$email_msg_onboarding_approved', `sender_name` = '$sender_name_onboarding_approved', `sender_email` = '$sender_email_onboarding_approved',`bcc_email` = '$bcc_email_onboarding_approved', `subject` =  '$subject_onboarding_approved', user_id = '$user_id'  WHERE default_email_messages_id = 12 ");
	}

	public function update_admin_default_email_message_onboarding_approved($sender_name_onboarding_approved,$sender_email_onboarding_approved,$subject_onboarding_approved,$email_msg_onboarding_approved,$bcc_email_onboarding_approved,$user_id){
		$this->db->query("UPDATE `default_email_messages` SET `message_content` = '$email_msg_onboarding_approved', `sender_name` = '$sender_name_onboarding_approved', `sender_email` = '$sender_email_onboarding_approved',`bcc_email` = '$bcc_email_onboarding_approved', `subject` =  '$subject_onboarding_approved', user_id = '$user_id'  WHERE default_email_messages_id = 7 ");
	}

	public function update_admin_default_email_message_onboarding_declined_clients($sender_name_onboarding_declined,$sender_email_onboarding_declined,$subject_onboarding_declined,$email_msg_onboarding_declined,$bcc_email_onboarding_declined,$user_id){
		$this->db->query("UPDATE `default_email_messages` SET `message_content` = '$email_msg_onboarding_declined', `sender_name` = '$sender_name_onboarding_declined', `sender_email` = '$sender_email_onboarding_declined',`bcc_email` = '$bcc_email_onboarding_declined', `subject` =  '$subject_onboarding_declined', user_id = '$user_id'  WHERE default_email_messages_id = 13 ");
	}

	public function update_admin_default_email_message_onboarding_declined($sender_name_onboarding_declined,$sender_email_onboarding_declined,$subject_onboarding_declined,$email_msg_onboarding_declined,$bcc_email_onboarding_declined,$user_id){
		$this->db->query("UPDATE `default_email_messages` SET `message_content` = '$email_msg_onboarding_declined', `sender_name` = '$sender_name_onboarding_declined', `sender_email` = '$sender_email_onboarding_declined',`bcc_email` = '$bcc_email_onboarding_declined', `subject` =  '$subject_onboarding_declined', user_id = '$user_id'  WHERE default_email_messages_id = 8 ");
	}

	public function update_admin_default_unaccepted_proj($unaccepted_no_days,$unaccepted_date_categories){
		$this->db->query("UPDATE `admin_defaults` SET `unaccepted_no_days` = '$unaccepted_no_days', `unaccepted_date_categories` = '$unaccepted_date_categories'");
	}

	public function update_admin_default_labour_sched($labour_sched_categories){
		$this->db->query("UPDATE `admin_defaults` SET `labour_sched_categories` = '$labour_sched_categories'");
	}

	public function update_admin_default_progress_report($progress_report_categories){
		$this->db->query("UPDATE `admin_defaults` SET `progress_report_categories` = '$progress_report_categories'");
	}

	public function update_admin_warranty($warranty_categories){
		$this->db->query("UPDATE `admin_defaults` SET `warranty_categories` = '$warranty_categories'");
	}

	public function update_site_labour_work_day($work_day){
		$query = $this->db->query("UPDATE `static_defaults` SET `site_labour_week_day` = '$work_day' WHERE `static_defaults`.`static_defaults_id` = 1");
	}

	public function fetch_app_user_rate($user_id){
		$query = $this->db->query("SELECT * FROM `site_labour_employee_rate` where user_id = '$user_id'");
		return $query;
	}

	public function fetch_user_rate_set(){
		$query = $this->db->query("SELECT * FROM `employee_rate_set` where is_active = 1");
		return $query;
	}

	public function insert_rate_set($rate_set_name,$normal_rate,$time_half_rate,$double_time_rate,$double_time_half_rate,$travel_allowance,$meal_allowance,$lafh_allowance){
		$rate_set_name = $_POST['rate_set_name'];
		$normal_rate = $_POST['normal_rate'];
		$time_half_rate = $_POST['time_half_rate'];
		$double_time_rate = $_POST['double_time_rate']; 
		$double_time_half_rate = $_POST['double_time_half_rate'];
		$travel_allowance = $_POST['travel_allowance'];
		$meal_allowance = $_POST['meal_allowance'];
		$lafh_allowance = $_POST['lafh_allowance'];


		$this->db->query("INSERT INTO `employee_rate_set` (`rate_set_name`,`normal_rate`,`time_half_rate`,`double_time_rate`,`double_time_half_rate`,`travel`,`meal`,`living_away_from_home`) 
			VALUES ( '$rate_set_name','$normal_rate','$time_half_rate','$double_time_rate','$double_time_half_rate','$travel_allowance','$meal_allowance','$lafh_allowance') ");
	}

	public function fetch_rate_set_seleted($employee_rate_set_id){
		$query = $this->db->query("SELECT * FROM `employee_rate_set` where  `employee_rate_set_id` = '$employee_rate_set_id'");
		return $query;
	}

	public function update_rate_set($employee_rate_set_id,$rate_set_name,$normal_rate,$time_half_rate,$double_time_rate,$double_time_half_rate,$travel_allowance,$meal_allowance,$lafh_allowance){
		$this->db->query("UPDATE `employee_rate_set`  
								set `rate_set_name` = '$rate_set_name',
								`normal_rate` = '$normal_rate',
								`time_half_rate` = '$time_half_rate',
								`double_time_rate` = '$double_time_rate',
								`double_time_half_rate` = '$double_time_half_rate',
								`travel` = '$travel_allowance',
								`meal` = '$meal_allowance',
								`living_away_from_home` = '$lafh_allowance' 
								where `employee_rate_set_id` = '$employee_rate_set_id'
						");
	
	}

	public function remove_rate_set($employee_rate_set_id){
		$this->db->query("UPDATE `employee_rate_set`  
								set `is_active` = 0
								where `employee_rate_set_id` = '$employee_rate_set_id'
						");
	
	}

	public function insert_employee_rate($user_id,$employee_rate_set_id){
		$this->db->query("INSERT INTO `employee_rate` 
								(`user_id`,`employee_rate_set_id`) 
							VALUES ( '$user_id','$employee_rate_set_id') ");
	}

	public function fetch_user_rate(){
		$query = $this->db->query("SELECT a.*, b.user_first_name,b.user_last_name,c.rate_set_name from employee_rate a
										left join users b on b.user_id = a.user_id
										left join employee_rate_set c on c.employee_rate_set_id = a.employee_rate_set_id
										where b.is_active = 1
									");
		return $query;
	}

	
	public function fetch_emp_rate_seleted($employee_rate_id){
		$query = $this->db->query("SELECT a.*, b.user_first_name,b.user_last_name,c.rate_set_name from employee_rate a
										left join users b on b.user_id = a.user_id
										left join employee_rate_set c on c.employee_rate_set_id = a.employee_rate_set_id
									where employee_rate_id = '$employee_rate_id'
									");
		return $query;
	}

	public function update_employee_rate($employee_rate_id,$user_id,$employee_rate_set_id){
		$this->db->query("UPDATE `employee_rate` 
								set `user_id` = '$user_id',`employee_rate_set_id` = '$employee_rate_set_id'
							WHERE employee_rate_id = '$employee_rate_id' ");
	}

	public function remove_employee_rate($employee_rate_id){
		$this->db->query("DELETE from `employee_rate` WHERE employee_rate_id = '$employee_rate_id' ");
	}

	public function fetch_leave_email_defaults(){
		$query = $this->db->query("SELECT * FROM `leave_defaults`");
		return $query;
	}

	public function update_leave_email_defaults($recipient_email, $cc_email, $bcc_email, $message_content){
		$this->db->query("UPDATE `leave_defaults` SET recipient_email = '$recipient_email', cc_email = '$cc_email', bcc_email = '$bcc_email', message = '$message_content' WHERE `leave_defaults_id` = '1'");
	}

	public function get_notice_days($leave_type_id){
		$query = $this->db->query("SELECT * FROM `leave_days_notice` WHERE `leave_type_id` = '$leave_type_id'");
		return $query;
	}

	public function update_leave_notice_defaults($days_notice, $leave_type_id, $leave_list){
		$this->db->query("UPDATE `leave_days_notice` SET days_advance_notice = '$days_notice' WHERE `leave_type_id` = '$leave_type_id' AND `days_of_leave_list` = '$leave_list'");
	}

	public function search_user($filter){
		$filter = '%'.$filter.'%';
		$query = $this->db->query("SELECT `users`.*,`department`.`department_id`,`department`.`department_name`,`role`.`role_types`,`email`.`general_email`,`contact_number`.*,`company_details`.`company_name`,`notes`.`comments`,`users`.`if_admin`, `users`.`direct_company` ,`users`.`is_third_party`
				FROM `users` 
				LEFT JOIN `department` ON `department`.`department_id` =`users`.`user_department_id`
				LEFT JOIN `role` ON `role`.`role_id` = `users`.`user_role_id`
				LEFT JOIN `email` ON `email`.`email_id` = `users`.`user_email_id`
				LEFT JOIN `contact_number` ON `contact_number`.`contact_number_id` = `users`.`user_contact_number_id`
				LEFT JOIN `company_details` ON `company_details`.`company_id` = `users`.`user_focus_company_id`
				LEFT JOIN `notes` ON `notes`.`notes_id` = `users`.`user_comments_id`
				WHERE CONCAT(`users`.`user_first_name`, ' ', `users`.`user_last_name`) like '$filter'
					and `users`.`is_active` = '1' 
				ORDER BY `users`.`user_focus_company_id` ASC,`users`.`user_first_name` ASC ");
		return $query;
	}

	public function joinery_selected_user(){
		$query = $this->db->query("SELECT * from joinery_user_responsible a
										left join users b on b.user_id = a.user_id
								");
		return $query;
	}

	public function insert_joinery_user($user_id){
		$query = $this->db->query("SELECT * from joinery_user_responsible a where user_id = '$user_id'");

		if($query->num_rows == 0){
			$this->db->query("INSERT INTO `joinery_user_responsible` (user_id) values('$user_id') ");
		}

	}

	public function joinery_set_primary($joinery_user_responsible_id){
		$this->db->query("UPDATE `joinery_user_responsible` set `is_primary` = 0");
		$this->db->query("UPDATE `joinery_user_responsible` set `is_primary` = 1 where `joinery_user_responsible_id` = '$joinery_user_responsible_id'");
	}

	public function joinery_remove_user($joinery_user_responsible_id){
		$this->db->query("DELETE from `joinery_user_responsible` where `joinery_user_responsible_id` = '$joinery_user_responsible_id'");
	}

	public function update_warranty_setup($warranty_months, $warranty_years){
		$query = $this->db->query("UPDATE `admin_defaults` SET `warranty_months` = '$warranty_months', `warranty_years` = '$warranty_years'");
		return $query;
	}

	public function display_required_license_cert(){
		$query = $this->db->query("SELECT 
										a.*, 
										b.job_category, 
										c.lc_type_name, 
										if(a.state_id = 0, 'All', d.name) AS state_name 
									FROM required_license_certificate a
										LEFT JOIN states d ON d.id = a.state_id
										LEFT JOIN job_category b on b.job_category_id = a.job_category_id
										LEFT JOIN licences_certs_types c on c.licences_certs_types_id = a.licenses_certs_types_id
								");
		return $query;

	}

	public function add_required_license_cert($activity_id,$license_cert_id,$state_id){
		$this->db->query("INSERT INTO `required_license_certificate` (job_category_id,licenses_certs_types_id,state_id) values('$activity_id','$license_cert_id','$state_id') ");
	}

	public function remove_required_license_cert($required_license_certificate_id){
		$this->db->query("DELETE from `required_license_certificate` where required_license_certificate_id = '$required_license_certificate_id' ");
	}

	public function fetch_default_notes(){
		$query = $this->db->query("SELECT * from default_notes");
		return $query;
	}

	public function update_induction_slide_notes($induction_slide4_notes,$induction_slide6_notes){
		$this->db->query("UPDATE `default_notes` SET `induction_slide4_notes` = '$induction_slide4_notes', `induction_slide6_notes` = '$induction_slide6_notes'");
	}

	public function update_default_induction_project($induction_categories,$work_value,$project_total){
		$this->db->query("UPDATE `admin_defaults` SET `induction_categories` = '$induction_categories' ,`induction_work_value` = '$work_value', `induction_project_value` = '$project_total'");
	}

	public function fetch_exempted_project_list(){
		$query = $this->db->query("SELECT * from induction_exempted_projects a
										left join project b on b.project_id = a.project_id
								");
		return $query;
	}

	public function project_is_exempted_induction($project_id){
		$query = $this->db->query("SELECT * from induction_exempted_projects a
										left join project b on b.project_id = a.project_id
										where a.project_id = '$project_id'
								");
		if($query->num_rows == 0){
			return 0;
		}else{
			return 1;
		}
	}

	public function add_exempted_project($project_number){
		$this->db->query("INSERT INTO `induction_exempted_projects` (project_id) values('$project_number') ");
	}

	public function remove_exempted_project($induction_exempted_projects_id){
		$this->db->query("DELETE from `induction_exempted_projects` where induction_exempted_projects_id = '$induction_exempted_projects_id'");
	}

	public function fetch_exempted_postcode(){
		$query = $this->db->query("SELECT * from induction_postcode_filters a
										left join states b on b.id = a.state_id
								");
		return $query;
	}

	public function add_exempted_postcode($induction_filter_state,$induction_filter_start_postcode,$induction_filter_ends_postcode){
		$this->db->query("INSERT INTO `induction_postcode_filters` (state_id,start_postcode,end_postcode) values('$induction_filter_state','$induction_filter_start_postcode','$induction_filter_ends_postcode') ");
	}

	public function remove_exempted_postcode($induction_postcode_filters_id){
		$this->db->query("DELETE from `induction_postcode_filters` where induction_postcode_filters_id = '$induction_postcode_filters_id'");
	}

	public function postcode_excempted($postcode){
		$query = $this->db->query("SELECT * from induction_postcode_filters 
										where start_postcode - 1 < '$postcode' 
											and end_postcode + 1 > '$postcode'
								");
		if($query->num_rows == 0){
			return 0;
		}else{
			return 1;
		}
	}

	public function fetch_doc_storage_required_notification(){
		$query = $this->db->query("SELECT * FROM doc_storage_required_notification a LEFT JOIN storage_doc_type b on b.storage_doc_type_id = a.storage_doc_type_id");
		return $query;
	}

	public function insert_required_doc_type($doc_type){
		$this->db->query("INSERT INTO doc_storage_required_notification (storage_doc_type_id) values('$doc_type')");
	}

	public function delete_required_doc_type($doc_storage_required_notification_id){
		$this->db->query("DELETE FROM doc_storage_required_notification WHERE doc_storage_required_notification_id = '$doc_storage_required_notification_id'");
	}

	public function fetch_default_doc_storage(){
		$query = $this->db->query("SELECT * FROM default_doc_storage");
		return $query;
	}

	public function save_default_doc_storage($authorize_role_id,$email_subject,$email_content){
		$query = $this->db->query("SELECT * FROM default_doc_storage");
		if($query->num_rows == 0){
			$this->db->query("INSERT INTO default_doc_storage (authorize_role_id,email_subject,email_content) values('$authorize_role_id','$email_subject','$email_content')");
		}else{
			$this->db->query("UPDATE default_doc_storage SET authorize_role_id = '$authorize_role_id',email_subject = '$email_subject', email_content = '$email_content'");
		}
	}

	public function save_default_doc_storage_receiver($to_pa,$to_pm,$to_set_out,$to_joinery,$cm_wa,$cm_nsw){
		$this->db->query("UPDATE default_doc_storage SET to_pa = '$to_pa',to_pm = '$to_pm', to_set_out = '$to_set_out', to_joinery = '$to_joinery', cm_wa = '$cm_wa', cm_nsw = '$cm_nsw'");
		
	}
}