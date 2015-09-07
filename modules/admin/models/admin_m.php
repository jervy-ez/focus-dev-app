<?php

class Admin_m extends CI_Model{
	
	public function fetch_site_costs($id=0){	
		if( $id>0 ){
			$query = $this->db->query("SELECT * FROM `site_costs` WHERE `site_cost_id` = '$id' ");
		}else{
			$query = $this->db->query("SELECT * FROM `site_costs` ORDER BY `site_cost_id` DESC LIMIT 1");
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



	
	public function fetch_labour_cost($id=0){
		if( $id>0 ){
			$query = $this->db->query("SELECT * FROM `labour_cost` WHERE `labour_cost_id` = '$id' ");			
		}else{
			$query = $this->db->query("SELECT * FROM `labour_cost` ORDER BY `labour_cost_id` DESC LIMIT 1");
		}
		return $query;
	}

	public function fetch_all_company_focus(){
		$query = $this->db->query("SELECT `company_details`.`company_id`,`company_details`.`company_name` ,  `address_general`.`suburb` ,  `states`.`name` AS `state_name`  , `contact_number`.`area_code`,  `contact_number`.`office_number`,`email`.`general_email`
			FROM  `company_details`			
			LEFT JOIN  `address_detail` ON  `address_detail`.`address_detail_id` =  `company_details`.`address_id` 
			LEFT JOIN  `address_general` ON  `address_general`.`general_address_id` =  `address_detail`.`general_address_id`
			LEFT JOIN `admin_company` ON `admin_company`.`admin_company_details_id` = `company_details`.`company_id`
			LEFT JOIN `contact_number` ON `contact_number`.`contact_number_id` = `admin_company`.`admin_contact_number_id`
			LEFT JOIN `email` ON `email`.`email_id` = `admin_company`.`admin_email_id`
			LEFT JOIN  `states` ON  `states`.`id` =  `address_general`.`state_id`
			WHERE  `company_details`.`company_type_id` =  '5' AND `company_details`.`active` = '1'
			ORDER BY  `company_details`.`company_id` ASC");
		return $query;
	}

	public function fetch_single_company_focus($id){
		$query = $this->db->query("SELECT `admin_company`.`admin_contact_number_id`,`admin_company`.`admin_email_id`,`company_details`.`company_id`,`company_details`.`company_name`,`company_details`.`abn`,`company_details`.`acn`,`company_details`.`bank_account_id`,`company_details`.`address_id`,`company_details`.`postal_address_id`,`admin_company`.`logo`,`admin_company`.`admin_jurisdiction_state_ids`,`bank_account`.*,`contact_number`.`contact_number_id`,`contact_number`.`area_code`,`contact_number`.`office_number`,`contact_number`.`mobile_number`,`email`.`general_email`
			FROM `company_details`			
			LEFT JOIN  `admin_company` ON  `admin_company`.`admin_company_details_id` =  `company_details`.`company_id`
			LEFT JOIN  `bank_account` ON  `bank_account`.`bank_account_id` =  `company_details`.`bank_account_id`
			LEFT JOIN  `contact_number` ON  `contact_number`.`contact_number_id` =  `admin_company`.`admin_contact_number_id`
			LEFT JOIN   `email` ON  `email`.`email_id` = `admin_company`.`admin_email_id`
			WHERE `company_details`.`company_id` = '$id'");
		return $query;
	}

	public function insert_focus_company_details($admin_company_details_id,$admin_contact_number_id,$admin_email_id,$admin_jurisdiction_state_ids,$logo){
		$this->db->query("INSERT INTO `admin_company` (`admin_company_details_id`, `admin_contact_number_id`, `admin_email_id`, `admin_jurisdiction_state_ids`,`logo`) VALUES ('$admin_company_details_id', '$admin_contact_number_id', '$admin_email_id', '$admin_jurisdiction_state_ids','$logo')");
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

	public function updat_project_mark_up($kiosk,$full_fitout,$refurbishment,$stripout,$maintenance,$minor_works,$min_kiosk,$min_full_fitout,$min_refurbishment,$min_stripout,$min_maintenance,$min_minor_works){
		$query = $this->db->query("INSERT INTO `markup` (`kiosk`, `full_fitout`, `refurbishment`, `stripout`, `maintenance`, `minor_works`, `min_kiosk`, `min_full_fitout`, `min_refurbishment`, `min_stripout`, `min_maintenance`, `min_minor_works`) VALUES
			('$kiosk', '$full_fitout', '$refurbishment', '$stripout', '$maintenance', '$minor_works','$min_kiosk', '$min_full_fitout', '$min_refurbishment', '$min_stripout', '$min_maintenance', '$min_minor_works')");
		return $this->db->insert_id();
	}

	public function update_admin_defaults($val){
		$gst_rate = $val['gst-rate'];
		$installation_labour = $val['installation-labour'];
		$time_half = $val['time-half'];
		$double_time = $val['double-time'];
		$standard_labour = $val['standard-labour'];

		$query = $this->db->query("SELECT * FROM  `admin_defaults` order by admin_default_id desc");
		$qArr = array_shift($query->result_array());

		$cqr_notes_w_insurance = $qArr['cqr_notes_w_insurance'];
		$cqr_notes_no_insurance = $qArr['cqr_notes_no_insurance'];
		$cpo_notes_w_insurance = $qArr['cpo_notes_w_insurance'];
		$cpo_notes_no_insurance = $qArr['cpo_notes_no_insurance'];

		$this->db->query("INSERT INTO `admin_defaults` (`gst_rate`, `installation_labour_mark_up`, `labor_split_standard`, `labor_split_time_and_half`,`labor_split_double_time`,`cqr_notes_w_insurance`,`cqr_notes_no_insurance`,`cpo_notes_w_insurance`,`cpo_notes_no_insurance`) VALUES ('$gst_rate', '$installation_labour','$standard_labour', '$time_half', '$double_time', '$cqr_notes_w_insurance', '$cqr_notes_no_insurance', '$cpo_notes_w_insurance', '$cpo_notes_no_insurance')");
		return $this->db->insert_id();
	}


	public function insert_latest_system_default($site_cost_id,$admin_default_id,$markup_id,$labour_cost_id){
		$query = $this->db->query("INSERT INTO `defaults` (`site_cost_id`, `admin_default_id`, `markup_id`, `labour_cost_id`) VALUES ('$site_cost_id', '$admin_default_id', '$markup_id', '$labour_cost_id')");
		return $this->db->insert_id();
	}

	public function insert_labour_cost_matrix($superannuation,$workers_compensation,$payroll_tax,$leave_loading,$other,$total_leave_days,$total_work_days){
		$query = $this->db->query("INSERT INTO `labour_cost` (`superannuation`, `payroll_tax`, `total_leave_days`, `workers_compensation`, `leave_loading`, `total_work_days`, `other`) VALUES ('$superannuation','$payroll_tax','$total_leave_days','$workers_compensation','$leave_loading','$total_work_days','$other')");
		return $this->db->insert_id();
	}


	public function update_abn_acn_jurisdiction($abn,$acn,$jurisdiction_id,$comp_id){
		$query = $this->db->query("UPDATE `company_details` ,`admin_company` SET `company_details`.`abn` = '$abn', `company_details`.`acn` = '$acn', `admin_company`.`admin_jurisdiction_state_ids` = '$jurisdiction_id' WHERE `company_details`.`company_id` = '$comp_id' AND `admin_company`.`admin_company_details_id` = '$comp_id'");
		return $query;
	}

	public function update_amalgamated_rate($amalgamated_rate){
		$query = $this->db->query("UPDATE `site_costs` SET `total_amalgamated_rate` = '$amalgamated_rate' ORDER BY `site_cost_id` DESC LIMIT 1");
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
		$query = $this->db->query("SELECT * FROM  `admin_defaults` order by admin_default_id desc");
		$qArr = array_shift($query->result_array());

		$gst_rate = $qArr['gst_rate'];
		$installation_labour_mark_up = $qArr['installation_labour_mark_up'];
		$labor_split_standard = $qArr['labor_split_standard'];
		$labor_split_time_and_half = $qArr['labor_split_time_and_half'];
		$labor_split_double_time = $qArr['labor_split_double_time'];

		$this->db->query("INSERT INTO `admin_defaults` (`gst_rate`,`installation_labour_mark_up`,`labor_split_standard`,`labor_split_time_and_half`,`labor_split_double_time`,`cqr_notes_w_insurance`,`cqr_notes_no_insurance`,`cpo_notes_w_insurance`,`cpo_notes_no_insurance`) VALUES ( '$gst_rate','$installation_labour_mark_up','$labor_split_standard','$labor_split_time_and_half','$labor_split_double_time','$cqr_notes_w_ins','$cqr_notes_no_ins','$cpo_notes_w_ins','$cpo_notes_no_ins') ");
	}
}