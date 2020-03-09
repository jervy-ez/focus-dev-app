<?php

class Company_m extends CI_Model{
	
	public function fetch_all_suburb(){		
		$query = $this->db->query("SELECT  `states`.`name` ,  `address_general`.`suburb` ,  `states`.`phone_area_code` FROM  `address_general` ,  `states` WHERE  `address_general`.`state_id` =  `states`.`id` GROUP BY  `address_general`.`suburb` ORDER BY  `address_general`.`suburb` ASC");	
		//$query = $this->db->query("SELECT * FROM  `address_general` GROUP BY `suburb` ORDER BY  `suburb` ASC ");		
		return $query;
	}
	
	public function fetch_all_states(){
		$query = $this->db->query("SELECT * FROM  `states` ORDER BY  `states`.`name` ASC ");
		return $query;
	}


	public function fetch_complete_detail_address($address_detail_id){
		$query = $this->db->query("SELECT  `states`.*,   `address_general`.*,`address_detail`.*
									FROM `address_detail`
									LEFT JOIN `address_general` ON `address_general`.`general_address_id` =`address_detail`.`general_address_id`
									LEFT JOIN `states` ON `states`.`id` = `address_general`.`state_id`
									WHERE  `address_detail`.`address_detail_id`='$address_detail_id'");
		return $query;
	}


	public function update_myob_name_details($myob_name,$company_id){
		$query = $this->db->query(" UPDATE `company_details` SET `myob_name` = '$myob_name' WHERE `company_details`.`company_id` = '$company_id'  ");
	}


	public function update_address_details($address_detail_id,$unit_number,$unit_level,$street,$suburb,$postcode,$pobox=''){
		$query = $this->db->query("UPDATE `address_detail` SET `address_detail` .`unit_number` = '$unit_number', `address_detail` .`unit_level` = '$unit_level', `address_detail` .`street` = '$street',`address_detail`.`po_box` = '$pobox',
			`address_detail`.`general_address_id` = ( SELECT   `address_general`.`general_address_id`  FROM `address_general` WHERE `address_general`.`suburb` = '$suburb'  AND `address_general`.`postcode` = '$postcode' )
			WHERE `address_detail`.`address_detail_id` = '$address_detail_id'");
		return $query;		
	}

	public function update_contact_person($first_name,$last_name,$gender,$general_email,$office_number,$mobile_number,$after_hours,$type,$is_primary,$contact_person_id,$email_id,$contact_number_id){
		$query = $this->db->query("UPDATE `contact_person`, `email`, `contact_number`, `contact_person_company`
			SET
			`contact_person`.`first_name` = '$first_name', `contact_person`.`last_name` = '$last_name', `contact_person`.`gender` = '$gender', 			
			`email`.`general_email` = '$general_email',
			`contact_number`.`office_number` = '$office_number', `contact_number`.`mobile_number` = '$mobile_number', `contact_number`.`after_hours` = '$after_hours',
			`contact_person_company`.`type` = '$type', `contact_person_company`.`is_primary` = '$is_primary'

			WHERE `contact_person`.`contact_person_id` = '$contact_person_id'
			AND `contact_person_company`.`contact_person_id` = '$contact_person_id'
			AND `email`.`email_id` = '$email_id'
			AND `contact_number`.`contact_number_id` = '$contact_number_id' ");
		return $query;
	}

	public function update_primary_contact($contact_person_company_id,$is_primary){
		$query = $this->db->query("UPDATE `contact_person_company` SET `is_primary` = '$is_primary' WHERE `contact_person_company`.`contact_person_company_id` = '$contact_person_company_id' ");
		return $query;
	}

	public function select_client($client_id){
		$query = $this->db->query("SELECT * FROM `project` WHERE `project`.`client_id` = '$client_id'");
		return $query;
	}



	public function fetch_address_general_by($selector='',$value='',$suburb=''){
		if($selector == 'general_address_id'){
			$query = $this->db->query("SELECT * FROM `address_general` WHERE `address_general`.`general_address_id` = '$value' ORDER BY `address_general`.`suburb` ASC ORDER BY `address_general`.`suburb` ASC");

		}else if($selector == 'state_id'){
			$query = $this->db->query("SELECT * FROM `address_general` WHERE `address_general`.`state_id` = '$value' GROUP BY `address_general`.`suburb` ASC ORDER BY `address_general`.`suburb` ASC");

		}else if($selector == 'postcode'){
			$query = $this->db->query("SELECT * FROM `address_general` WHERE `address_general`.`postcode` = '$value' GROUP BY `address_general`.`suburb` ASC ORDER BY `address_general`.`suburb` ASC");

		}else if($selector == 'suburb'){
			$query = $this->db->query("SELECT * FROM `address_general` WHERE `address_general`.`suburb` = '$value' GROUP BY `address_general`.`suburb` ASC ORDER BY `address_general`.`suburb` ASC");

		}else if($selector == 'postcode-suburb'){
			$query = $this->db->query("SELECT * FROM `address_general` WHERE `address_general`.`postcode` ='$value' AND `address_general`.`suburb` ='$suburb'  GROUP BY `address_general`.`suburb` ASC ORDER BY `address_general`.`suburb` ASC");

		}else{
			$query = $this->db->query("SELECT * FROM `address_general` ORDER BY `address_general`.`suburb` ASC");
		}		
		return $query;
	}
	
	public function fetch_postcode($suburb){
		$query = $this->db->query("SELECT * FROM `address_general` WHERE `suburb` = '$suburb' ORDER BY  `suburb` ASC ");
		return $query;
	}
	
	public function fetch_all_company_types(){
		$query = $this->db->query("SELECT * FROM  `company_type`  WHERE  `company_type_id` <> '4' AND `company_type_id` <> '5' ORDER BY  `company_type`.`company_type_id` ASC  ");
		return $query;
	}
	
	public function fetch_all_company($comp_id=''){
		if($comp_id==''){
			$query = $this->db->query("SELECT * FROM  `company_details` WHERE  `company_type_id` <> 4 AND `active` = '1'  ORDER BY  `company_details`.`company_name` ASC");
			return $query;
		}else{
			$query = $this->db->query("SELECT * FROM  `company_details` WHERE  `company_id` = '$comp_id'");
			return $query;
		}
	}

	public function fetch_bank_account_details($bank_account_id){
		if($bank_account_id==''){
			$query = $this->db->query("SELECT * FROM `bank_account`");
			return $query;
		}else{
			$query = $this->db->query("SELECT * FROM `bank_account` WHERE  `bank_account_id` = '$bank_account_id'");
			return $query;
		}
	}

	public function update_other_details($abn,$acn,$activity_id,$company_type_id,$parent_company_id,$company_id,$sub_client_id){
		$query = $this->db->query("UPDATE `company_details` SET `abn` = '$abn', `acn` = '$acn',`activity_id` = '$activity_id',`company_type_id` = '$company_type_id',`parent_company_id` = '$parent_company_id',`sub_client_id` = '$sub_client_id' WHERE `company_id` = '$company_id'");
		return $query;
	}

	public function update_bank_account_details($id,$bank_account_name,$bank_account_number,$bank_name,$bank_bsb_number){
		$query = $this->db->query("UPDATE `bank_account` SET `bank_account_name` = '$bank_account_name', `bank_account_number` = '$bank_account_number', `bank_name` = '$bank_name', `bank_bsb_number` = '$bank_bsb_number' WHERE `bank_account`.`bank_account_id` = '$id'");
		return $query;
	}

	public function update_notes_comments($id,$comment,$notes=''){
		$query = $this->db->query("UPDATE `notes` SET `comments` = '$comment', `notes` = '$notes' WHERE `notes_id` = '$id' ");
		return $query;
	}	
	
	public function fetch_all_company_type_id($comp_id){
		$query = $this->db->query("SELECT * FROM  `company_details` WHERE  `company_type_id` = '$comp_id' AND `active` = '1' ORDER BY  `company_details`.`company_name` ASC ");
		return $query;
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

	public function fetch_all_company_details(){
		
			$query = $this->db->query("SELECT * FROM  `company_details` WHERE `company_details`.`company_type_id` = 2 ORDER BY `company_details`.`company_name` ASC");
			return $query;
		 
	}

	public function fetch_all_company_details_active(){
		
			$query = $this->db->query("SELECT * FROM  `company_details` WHERE `company_details`.`company_type_id` = 2 and active = 1 ORDER BY `company_details`.`company_name` ASC");
			return $query;
		 
	}

	public function fetch_contact_person_company($data){
		$query = $this->db->query("SELECT `contact_person_company`.*,`contact_person`.* FROM `contact_person_company`,`contact_person` WHERE`contact_person_company`.`company_id` ='$data' AND `contact_person_company`.`is_active` = '1' AND `contact_person`.`contact_person_id` = `contact_person_company`.`contact_person_id`");
		return $query;
	}


	public function delete_contact_person($contact_person_company_id){
		$query = $this->db->query("UPDATE `contact_person_company` SET `is_active` = '0' WHERE `contact_person_company`.`contact_person_company_id` = '$contact_person_company_id' ");
		return $query;
	}

	public function delete_company($company_id){
		$query = $this->db->query("UPDATE `company_details` SET `active` = '0' WHERE `company_details`.`company_id` = '$company_id'");
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

	public function update_company_name($id,$data){
		$query = $this->db->query("UPDATE `company_details` SET `company_name` = '$data' WHERE `company_details`.`company_id` = '$id' ");
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
	
	public function count_company_by_type(){
		$query = $this->db->query("SELECT COUNT(`company_details`.`company_type_id`) AS `counts`, `company_details`.`company_type_id`,`company_type`.`company_type`
			FROM `company_details`,`company_type`
			WHERE `company_type`.`company_type_id` = `company_details`.`company_type_id` GROUP BY `company_details`.`company_type_id`");
		return $query;
	}
	
	public function display_company_by_type($type){
		$query = $this->db->query("SELECT `company_details`.*,`company_details`.`company_id`,`company_details`.`company_name` ,  `address_general`.`suburb` ,  `states`.`shortname` , `company_logo`.logo_path

			/*`contact_number`.`area_code`, `contact_number`.`office_number`,`email`.`general_email`*/


			FROM  `company_details`			
			LEFT JOIN  `address_detail` ON  `address_detail`.`address_detail_id` =  `company_details`.`address_id` 
			LEFT JOIN  `address_general` ON  `address_general`.`general_address_id` =  `address_detail`.`general_address_id`
		
		/*
			LEFT JOIN `contact_person_company` ON `contact_person_company`.`company_id` = `company_details`.`company_id`
			LEFT JOIN  `contact_person` ON  `contact_person`.`contact_person_id` = `contact_person_company`.`contact_person_id`			
			LEFT JOIN `contact_number` ON `contact_number`.`contact_number_id` = `contact_person`.`contact_number_id`
*/

			/*LEFT JOIN `email` ON `email`.`email_id` = `contact_person`.`email_id`*/
			LEFT JOIN  `states` ON  `states`.`id` =  `address_general`.`state_id` 

			/* added by Mike Coros 05-28-18 */
			LEFT JOIN  `company_logo` ON  `company_logo`.`company_id` =  `company_details`.`company_id` 

			WHERE  `company_details`.`company_type_id` =  '$type'  AND `company_details`.`active` = '1' 
			 
			ORDER BY `company_details`.`company_name` ASC ");
		return $query;
	}

	public function function_get_contact_details($company_id){
		$query = $this->db->query("SELECT * FROM `contact_person_company`
			LEFT JOIN  `contact_person` ON  `contact_person`.`contact_person_id` =  `contact_person_company`.`contact_person_id`
			LEFT JOIN  `email` ON `email`.`email_id` =  `contact_person`.`email_id`
			LEFT JOIN `contact_number` ON  `contact_number`.`contact_number_id` = `contact_person`.`contact_number_id`
			WHERE `contact_person_company`.`company_id` = '$company_id' AND  `contact_person_company`.`is_active` = '1' ");
		return $query;
	}
	
	public function display_company_detail_by_id($id){
		$query = $this->db->query("SELECT `company_details`.*, `company_logo`.logo_path

			FROM  `company_details`
			/*LEFT JOIN  `email` ON  `email`.`email_id` =  `company_details`.`email_id`*/
			/*LEFT JOIN  `contact_number` ON  `contact_number`.`contact_number_id` =  `company_details`.`contact_number_id`*/
			LEFT JOIN  `address_detail` ON  `address_detail`.`address_detail_id` =  `company_details`.`address_id`
			LEFT JOIN  `address_general` ON  `address_general`.`general_address_id` =  `address_detail`.`general_address_id`
			LEFT JOIN  `company_type` ON  `company_type`.`company_type_id` =  `company_details`.`company_type_id`
			LEFT JOIN `company_logo` ON `company_logo`.company_id = `company_details`.company_id
			WHERE   `company_details`.`company_id` =  '$id'");
		return $query;
	}	





	public function insert_email($general_email='',$personal_email='', $direct = '', $accounts='',$maintenance=''){
		$this->db->query("INSERT INTO `email` (`general_email`, `personal_email`, `direct`, `accounts`, `maintenance`) VALUES ('$general_email', '$personal_email', '$direct', '$accounts', '$maintenance')");
		$last_insert_id = $this->db->insert_id();
		return $last_insert_id;
	}

	public function insert_contact_number($area_code,$office_number,$direct_number='',$mobile_number='',$after_hours='', $personal_mobile_number=''){
		$this->db->query("INSERT INTO `contact_number` (`area_code`, `office_number`, `direct_number`, `mobile_number`, `after_hours`, `personal_mobile_number`) VALUES ('$area_code', '$office_number', '$direct_number', '$mobile_number', '$after_hours', '$personal_mobile_number')");
		$last_insert_id = $this->db->insert_id();
		return $last_insert_id;
	}

	public function insert_contact_person($first_name,$last_name,$gender,$email_id,$contact_number_id){
		$this->db->query("INSERT INTO `contact_person` (`first_name`, `last_name`, `gender`, `email_id`, `contact_number_id`) VALUES ('$first_name', '$last_name', '$gender', '$email_id', '$contact_number_id')");
		$last_insert_id = $this->db->insert_id();
		return $last_insert_id;
	}

	public function insert_bank_account($bank_account_name,$bank_account_number,$bank_name,$bank_bsb_number){
		$this->db->query("INSERT INTO `bank_account` (`bank_account_name`, `bank_account_number`, `bank_name`, `bank_bsb_number`) VALUES ('$bank_account_name', '$bank_account_number', '$bank_name', '$bank_bsb_number')");
		$last_insert_id = $this->db->insert_id();
		return $last_insert_id;
	}

	public function insert_contact_person_company($company_id,$contact_person_id,$type,$is_primary=''){
		$this->db->query("INSERT INTO `contact_person_company` (`company_id`, `contact_person_id`, `type`,`is_primary`) VALUES ('$company_id', '$contact_person_id', '$type','$is_primary')");
		$last_insert_id = $this->db->insert_id();
		return $last_insert_id;
	}

	public function insert_notes($comments,$notes=''){
		$this->db->query("INSERT INTO `notes` (`comments`, `notes`) VALUES ('$comments', '$notes')");
		$last_insert_id = $this->db->insert_id();
		return $last_insert_id;
	}

	public function insert_address_detail($street,$general_address_id,$unit_level='',$unit_number='',$po_box=''){
		$this->db->query("INSERT INTO `address_detail` (`unit_number`, `unit_level`, `street`, `po_box`, `general_address_id`) VALUES ('$unit_number', '$unit_level', '$street', '$po_box', '$general_address_id')");
		$last_insert_id = $this->db->insert_id();
		return $last_insert_id;
	}

	public function insert_company_details($company_name,$abn,$acn,$activity_id,$address_id,$postal_address_id,$company_type_id,$bank_account_id='',$notes_id='',$parent_company_id='',$sub_client_id=''){
		$this->db->query("INSERT INTO `company_details` (`company_name`, `abn`, `acn`, `bank_account_id`, `activity_id`, `notes_id`, `address_id`, `postal_address_id`, `company_type_id`, `parent_company_id`, `sub_client_id`) VALUES ('$company_name', '$abn', '$acn', '$bank_account_id', '$activity_id', '$notes_id','$address_id', '$postal_address_id', '$company_type_id', '$parent_company_id', '$sub_client_id')");
		$last_insert_id = $this->db->insert_id();
		return $last_insert_id;
	}

	public function fetch_contact_details_primary($company_id){
		$query = $this->db->query("SELECT * FROM `contact_person_company`
			LEFT JOIN `contact_person` ON `contact_person`.`contact_person_id` = `contact_person_company`.`contact_person_id`
			LEFT JOIN `contact_number` ON `contact_number`.`contact_number_id` = `contact_person`.`contact_number_id`
			LEFT JOIN `email` ON `email`.`email_id` = `contact_person`.`email_id`
			WHERE `contact_person_company`.`company_id` = '$company_id' AND `contact_person_company`.`is_primary` = '1'");
		return $query;
	}

	public function update_company_details_insurance($comp_id,$insurance_type,$expiration){
		$start_date = date('d/m/Y');
		if($insurance_type == 1){
			$this->db->query("UPDATE `company_details` set `has_insurance_public_liability` = '1', `public_liability_start_date` = '$start_date', `public_liability_expiration` = '$expiration', `pl_email_stat` = 0 where company_id = '$comp_id'");	
		}else{
			if($insurance_type == 2){
				$this->db->query("UPDATE `company_details` set `has_insurance_workers_compensation` = '1', `workers_compensation_start_date` = '$start_date', `workers_compensation_expiration` = '$expiration', `wc_email_stat` = 0 where company_id = '$comp_id'");	
			}else{
				$this->db->query("UPDATE `company_details` set `has_insurance_income_protection` = '1', `income_protection_start_date` = '$start_date', `income_protection_expiration` = '$expiration', `email_send_status` = 0 where company_id = '$comp_id'");	
			}
		}	
	}

	public function remove_company_insurance($comp_id,$insurance_type){
		if($insurance_type == 1){
			$this->db->query("UPDATE `company_details` set `has_insurance_public_liability` = '0', `public_liability_start_date` = '', `public_liability_expiration` = '' where company_id = '$comp_id'");
		}else{
			if($insurance_type == 2){
				$this->db->query("UPDATE `company_details` set `has_insurance_workers_compensation` = '0', `workers_compensation_start_date` = '', `workers_compensation_expiration` = '' where company_id = '$comp_id'");	
			}else{
				$this->db->query("UPDATE `company_details` set `has_insurance_income_protection` = '0', `income_protection_start_date` = '', `income_protection_expiration` = '' where company_id = '$comp_id'");	
			}
		}
		
	}

	public function check_company_exist($abn,$type){
		$abn = preg_replace('/\s+/', '', $abn);
		if($type == '1'){
			$query = $this->db->query("SELECT replace(abn , ' ','') as abn from company_details where company_type_id = '$type' and active = '1'");
		}else{
			$query = $this->db->query("SELECT replace(abn , ' ','') as abn from company_details where company_type_id IN ('2', '3') and active = '1'");
		}
		//$query = $this->db->query("select replace(abn , ' ','') as abn from company_details where company_type_id = '$type'");
		$exist = 0;
		foreach ($query->result_array() as $row){
			if($abn == $row['abn']){
				$exist = 1;
			}			
		}

		return $exist;
		// $query = $this->db->query("select * from company_details where abn = '$abn'");
		// if($query->num_rows > 0){
		// 	return 1;
		// }else{
		// 	return 0;
		// }
	}

	public function check_company_exist_edit($abn,$type,$company_id){
		$abn = preg_replace('/\s+/', '', $abn);
		if($type == '1'){
			$query = $this->db->query("SELECT replace(abn , ' ','') as abn from company_details where company_type_id = '$type' and active = '1'");
		}else{
			$query = $this->db->query("SELECT company_id, replace(abn , ' ','') as abn from company_details where company_type_id IN ('2', '3') and active = '1'");
		}
		//$query = $this->db->query("select replace(abn , ' ','') as abn from company_details where company_type_id = '$type'");
		$exist = 0;
		foreach ($query->result_array() as $row){
			if($abn == $row['abn'] && $company_id != $row['company_id']){
				$exist = 1;
			}			
		}

		return $exist;
		// $query = $this->db->query("select * from company_details where abn = '$abn'");
		// if($query->num_rows > 0){
		// 	return 1;
		// }else{
		// 	return 0;
		// }
	}
	
	public function check_companyname_exist($company_name,$street,$suburb_a,$state_a,$postcode){
		$query = $this->db->query("SELECT * FROM  `company_details` WHERE `company_details`.`company_name` = '$company_name'");
		if($query->num_rows > 0){
			foreach ($query->result_array() as $row){
				$address_id = $row['address_id'];
				$add_query = $this->db->query("SELECT * FROM `address_detail`, `address_general` ,`states` WHERE ((`address_detail_id` = '$address_id' AND `address_general`.`general_address_id` = `address_detail`.`general_address_id`) AND `states`.`id` = `address_general`.`state_id`)");
				foreach ($add_query->result_array() as $add_row){
					if($street == $add_row['street'] && $suburb_a == $add_row['suburb'] && $state_a == $add_row['state_id'] && $state_a == $add_row['state_id'] && $postcode == $add_row['postcode']){
						return 1;
					}else{
						return 0;
					}
				}	
			}
		}else{
			return 0;
		}
	}

	public function update_contractor_send_status($company_id, $insurance_expired){
		switch($insurance_expired){
			case 1:
				$this->db->query("UPDATE `company_details` set `pl_email_stat` = 1 WHERE `company_details`.`company_id` = '$company_id'");
				break;
			case 2:
				$this->db->query("UPDATE `company_details` set `wc_email_stat` = 1 WHERE `company_details`.`company_id` = '$company_id'");
				break;
			case 3:
				$this->db->query("UPDATE `company_details` set `email_send_status` = 1 WHERE `company_details`.`company_id` = '$company_id'");
				break;
		}
		
	}

	public function fetch_admin_default_email_message(){
		$query = $this->db->query("SELECT * FROM `default_email_messages` where section = 'insurance'");
		return $query;
	}

	//Added Function in case Company note_id is 0 -- Edited Mark
	public function update_company_notes_id($company_id,$notes_id){
		$query = $this->db->query("UPDATE `company_details` set `notes_id` = '$notes_id' where `company_id` = '$company_id'");
		return $query;
	}
	//Added Function in case Company note_id is 0 -- Edited Mark
	
	//Added Function for Filtering Work's Contractor List -- Edited Mark
	public function fetch_all_company_activity_type($activity_id,$type){
		$query = $this->db->query("SELECT * FROM  `company_details` as a
										LEFT JOIN `address_detail` as b on b.`address_detail_id` = a.`address_id`
										LEFT JOIN `address_general` as c on c.`general_address_id` = b.`general_address_id`
										LEFT JOIN `states` as d on d.`id` = c.`state_id`
									WHERE a.`active` = '1'
										AND a.`company_type_id` = '$type'
										AND a.`activity_id` = '$activity_id'
									ORDER BY  c.`postcode` ASC");

		return $query;
	}

	public function fetch_all_company_search($search_text){
		$search_text = '%'.$search_text.'%';
		$query = $this->db->query("SELECT * FROM  `company_details` as a
										LEFT JOIN `address_detail` as b on b.`address_detail_id` = a.`address_id`
										LEFT JOIN `address_general` as c on c.`general_address_id` = b.`general_address_id`
										LEFT JOIN `states` as d on d.`id` = c.`state_id`
									WHERE a.`active` = '1'
										AND a.`company_type_id` in (2,3)
										AND a.company_name like '$search_text'
									ORDER BY  c.`postcode` ASC");
		return $query;
	}

	public function fetch_all_company_filter($activity_id,$state,$suburb,$postcode){
		if($state == '0'){
			if($suburb == '0'){
				if($postcode == '0'){
					$query = $this->db->query("SELECT * FROM  `company_details` as a
										LEFT JOIN `address_detail` as b on b.`address_detail_id` = a.`address_id`
										LEFT JOIN `address_general` as c on c.`general_address_id` = b.`general_address_id`
										LEFT JOIN `states` as d on d.`id` = c.`state_id`
									WHERE a.`active` = '1'
										AND a.`company_type_id` in (2,3)
										AND a.`activity_id` = '$activity_id'
									ORDER BY  c.`postcode` ASC");
				}else{
					$query = $this->db->query("SELECT * FROM  `company_details` as a
										LEFT JOIN `address_detail` as b on b.`address_detail_id` = a.`address_id`
										LEFT JOIN `address_general` as c on c.`general_address_id` = b.`general_address_id`
										LEFT JOIN `states` as d on d.`id` = c.`state_id`
									WHERE a.`active` = '1'
										AND a.`company_type_id` in (2,3)
										AND a.`activity_id` = '$activity_id'
										AND c.`postcode` = '$postcode'
									ORDER BY  c.`postcode` ASC");
				}
			}else{
				if($postcode == '0'){
					$query = $this->db->query("SELECT * FROM  `company_details` as a
										LEFT JOIN `address_detail` as b on b.`address_detail_id` = a.`address_id`
										LEFT JOIN `address_general` as c on c.`general_address_id` = b.`general_address_id`
										LEFT JOIN `states` as d on d.`id` = c.`state_id`
									WHERE a.`active` = '1'
										AND a.`company_type_id` in (2,3)
										AND a.`activity_id` = '$activity_id'
										AND c.`suburb` = '$suburb'
									ORDER BY  c.`postcode` ASC");
				}else{
					$query = $this->db->query("SELECT * FROM  `company_details` as a
										LEFT JOIN `address_detail` as b on b.`address_detail_id` = a.`address_id`
										LEFT JOIN `address_general` as c on c.`general_address_id` = b.`general_address_id`
										LEFT JOIN `states` as d on d.`id` = c.`state_id`
									WHERE a.`active` = '1'
										AND a.`company_type_id` in (2,3)
										AND a.`activity_id` = '$activity_id'
										AND c.`postcode` = '$postcode'
										AND c.`suburb` = '$suburb'
									ORDER BY  c.`postcode` ASC");
				}
			}
		}else{
			if($suburb == '0'){
				if($postcode == '0'){
					$query = $this->db->query("SELECT * FROM  `company_details` as a
										LEFT JOIN `address_detail` as b on b.`address_detail_id` = a.`address_id`
										LEFT JOIN `address_general` as c on c.`general_address_id` = b.`general_address_id`
										LEFT JOIN `states` as d on d.`id` = c.`state_id`
									WHERE a.`active` = '1'
										AND a.`company_type_id` in (2,3)
										AND a.`activity_id` = '$activity_id'
										AND c.`state_id` = '$state'
									ORDER BY  c.`postcode` ASC");
				}else{
					$query = $this->db->query("SELECT * FROM  `company_details` as a
										LEFT JOIN `address_detail` as b on b.`address_detail_id` = a.`address_id`
										LEFT JOIN `address_general` as c on c.`general_address_id` = b.`general_address_id`
										LEFT JOIN `states` as d on d.`id` = c.`state_id`
									WHERE a.`active` = '1'
										AND a.`company_type_id` in (2,3)
										AND a.`activity_id` = '$activity_id'
										AND c.`postcode` = '$postcode'
										AND c.`state_id` = '$state'
									ORDER BY  c.`postcode` ASC");
				}
			}else{
				if($postcode == '0'){
					$query = $this->db->query("SELECT * FROM  `company_details` as a
										LEFT JOIN `address_detail` as b on b.`address_detail_id` = a.`address_id`
										LEFT JOIN `address_general` as c on c.`general_address_id` = b.`general_address_id`
										LEFT JOIN `states` as d on d.`id` = c.`state_id`
									WHERE a.`active` = '1'
										AND a.`company_type_id` in (2,3)
										AND a.`activity_id` = '$activity_id'
										AND c.`suburb` = '$suburb'
										AND c.`state_id` = '$state'
									ORDER BY  c.`postcode` ASC");
				}else{
					$query = $this->db->query("SELECT * FROM  `company_details` as a
										LEFT JOIN `address_detail` as b on b.`address_detail_id` = a.`address_id`
										LEFT JOIN `address_general` as c on c.`general_address_id` = b.`general_address_id`
										LEFT JOIN `states` as d on d.`id` = c.`state_id`
									WHERE a.`active` = '1'
										AND a.`company_type_id` in (2,3)
										AND a.`activity_id` = '$activity_id'
										AND c.`postcode` = '$postcode'
										AND c.`suburb` = '$suburb'
										AND c.`state_id` = '$state'
									ORDER BY  c.`postcode` ASC");
				}
			}
		}
		
		return $query;
	}

	public function fetch_all_state_suburb(){		
		$query = $this->db->query("SELECT  `states`.`name` ,  `address_general`.`suburb` ,  `states`.`phone_area_code` FROM  `address_general` ,  `states` WHERE  `address_general`.`state_id` =  `states`.`id` ORDER BY  `address_general`.`suburb` ASC");	
		//$query = $this->db->query("SELECT * FROM  `address_general` GROUP BY `suburb` ORDER BY  `suburb` ASC ");		
		return $query;
	}

	//Added Function for Filtering Work's Contractor List -- Edited Mark

	public function insert_company_logo_path($company_id, $logo_path){
		$this->db->query("INSERT INTO company_logo (`company_id`, `logo_path`) VALUES ('$company_id', '$logo_path')");
	}

	public function get_company_logo($company_id){
		$query = $this->db->query("SELECT * FROM `company_logo` WHERE `company_id` = '$company_id'");	
		return $query;
	}

	public function edit_company_logo_path($company_id, $logo_path){
		$this->db->query("UPDATE company_logo SET `logo_path` = '$logo_path' WHERE `company_id` = '$company_id'");
	}

	public function delete_company_logo($company_id){
		$query = $this->db->query("DELETE FROM `company_logo` WHERE `company_id` = '$company_id'");	
		return $query;
	}

// Site Staff start ==============================
	public function fetch_site_staff($company_id=''){
		if($company_id==''){
			$query = $this->db->query("SELECT a.*, REPLACE(b.company_name, '&apos;', '`') as company_name from contractos_site_staff a 
											left join company_details b on b.company_id = a.company_id  
											-- left join contact_person_company c on c.company_id = b.company_id
											-- left join contact_person d on d.contact_person_id = c.contact_person_id
											-- left join email e on e.email_id = d.email_id
											-- LEFT JOIN (SELECT * from `induction_video_person_watch` where site_staff_type = 2) as ivpw ON ivpw.contractor_site_staff_id = a.contractor_site_staff_id
											-- LEFT JOIN (SELECT * from `induction_email_send` where site_staff_type = 2) as ies ON ies.site_staff_id = a.contractor_site_staff_id
											
											-- where c.is_primary = 1
											order by b.company_name
									");	
		}else{
			$query = $this->db->query("	SELECT a.*, REPLACE(b.company_name, '&apos;', '`') as company_name, c.*, d.*, e.* from contractos_site_staff a 
											left join company_details b on b.company_id = a.company_id  
											left join contact_person_company c on c.company_id = b.company_id
											left join contact_person d on d.contact_person_id = c.contact_person_id
											left join email e on e.email_id = d.email_id
											
										 where a.company_id = '$company_id'
										 	and c.is_primary = 1
										 order by b.company_name
									");	
		}
		
		
		return $query;	
	}

	public function add_site_staff($company_id,$ss_fname,$ss_sname,$mobile_number,$ss_position,$email,$is_apprentice,$temp_contractors_staff_id='0'){

		$this->db->query("INSERT INTO contractos_site_staff (company_id,site_staff_fname,site_staff_sname,position,mobile_number,email,temp_contractors_staff_id,is_apprentice) values('$company_id','$ss_fname','$ss_sname','$ss_position','$mobile_number','$email','$temp_contractors_staff_id','$is_apprentice') ");	
		$last_insert_id = $this->db->insert_id();
		$ss_fname = strtolower($ss_fname);
		$username = $company_id.$last_insert_id.str_replace(' ','',$ss_fname); 
		$this->db->query("UPDATE contractos_site_staff set safety_hub_username = '$username' where contractor_site_staff_id = '$last_insert_id' ");		

		return $last_insert_id;
	}

	public function update_site_staff($contractor_site_staff_id,$ss_fname,$ss_sname,$ss_position,$mobile_number,$email,$company_id,$is_apprentice,$temp_contractors_staff_id = '0',$gi_date){
			if($temp_contractors_staff_id == '0'){
				$this->db->query("UPDATE contractos_site_staff set company_id = '$company_id', site_staff_fname = '$ss_fname',site_staff_sname = '$ss_sname' ,position = '$ss_position', mobile_number= '$mobile_number', email = '$email', general_induction_date = '$gi_date', is_apprentice = '$is_apprentice' where contractor_site_staff_id = '$contractor_site_staff_id' ");		
			}else{
				$this->db->query("UPDATE contractos_site_staff set company_id = '$company_id', site_staff_fname = '$ss_fname',site_staff_sname = '$ss_sname' ,position = '$ss_position', mobile_number= '$mobile_number', email = '$email', temp_contractors_staff_id = '$temp_contractors_staff_id', general_induction_date = '$gi_date', is_apprentice = '$is_apprentice' where contractor_site_staff_id = '$contractor_site_staff_id' ");		
			}
			
	}

	public function delete_site_staff($contractor_site_staff_id){
		$this->db->query("DELETE from contractos_site_staff where contractor_site_staff_id = '$contractor_site_staff_id' ");		
	}

	public function fetch_contractors_with_sitestaff($company_id=''){
		if($company_id == ''){
			$query = $this->db->query("SELECT a.company_id, REPLACE(b.company_name, '&apos;', '`') as company_name, b.induction_date_sent, d.first_name, d.last_name, e.general_email, f.* from contractos_site_staff a 
												LEFT join company_details b on b.company_id = a.company_id
												LEFT join contact_person_company c on c.company_id = b.company_id
												LEFT JOIN contact_person d ON  d.contact_person_id =  c.contact_person_id
												LEFT JOIN email e ON e.email_id =  d.email_id
												LEFT JOIN contact_number f ON  f.contact_number_id = d.contact_number_id
											WHERE b.active = '1'
												and c.is_primary = 1
											group by b.company_name
											order by b.company_name
										");
		}else{
			$query = $this->db->query("SELECT a.company_id, REPLACE(b.company_name, '&apos;', '`') as company_name, b.induction_date_sent, d.first_name, d.last_name, e.general_email, f.* from contractos_site_staff a 
												LEFT join company_details b on b.company_id = a.company_id
												LEFT join contact_person_company c on c.company_id = b.company_id
												LEFT JOIN contact_person d ON  d.contact_person_id =  c.contact_person_id
												LEFT JOIN email e ON e.email_id =  d.email_id
												LEFT JOIN contact_number f ON  f.contact_number_id = d.contact_number_id
											WHERE b.active = '1'
												and c.is_primary = 1
												and a.company_id = '$company_id'
											group by b.company_name
											order by b.company_name
										");
		}
		return $query;	
	}

	public function filter_contractors_with_sitestaff($company_id=''){
		if($company_id == ''){
			$query = $this->db->query("SELECT a.company_id, REPLACE(b.company_name, '&apos;', '`') as company_name, b.induction_date_sent, d.first_name, d.last_name, e.general_email, f.* from contractos_site_staff a 
											LEFT join company_details b on b.company_id = a.company_id
											LEFT join contact_person_company c on c.company_id = b.company_id
											LEFT JOIN contact_person d ON  d.contact_person_id =  c.contact_person_id
											LEFT JOIN email e ON e.email_id =  d.email_id
											LEFT JOIN contact_number f ON  f.contact_number_id = d.contact_number_id
										WHERE b.active = '1'
											and c.is_primary = 1
										group by b.company_name
										order by b.company_name
									");
		}else{
			$query = $this->db->query("SELECT a.company_id, REPLACE(b.company_name, '&apos;', '`') as company_name, b.induction_date_sent, d.first_name, d.last_name, e.general_email, f.* from contractos_site_staff a 
											LEFT join company_details b on b.company_id = a.company_id
											LEFT join contact_person_company c on c.company_id = b.company_id
											LEFT JOIN contact_person d ON  d.contact_person_id =  c.contact_person_id
											LEFT JOIN email e ON e.email_id =  d.email_id
											LEFT JOIN contact_number f ON  f.contact_number_id = d.contact_number_id
										WHERE b.active = '1'
											and c.is_primary = 1
											and a.company_id = '$company_id'
										group by b.company_name
										order by b.company_name
									");
		}
		
		return $query;	
	}

// Site Staff end ==============================

	// Onboarding Queries start

	public function check_email_exist($email_add){
		$query = $this->db->query("SELECT * from `email` WHERE `general_email` = '$email_add'");
		
		if($query->num_rows == 0){
			return 0;
		}else{
			return 1;
		}
	}

	public function check_email_exist_in_onboarding($email_add){
		$query = $this->db->query("SELECT * from `onboarding` WHERE `onboarding_email` = '$email_add'");
		
		if($query->num_rows == 0){
			return 0;
		}else{
			return 1;
		}
	}

	public function insert_onboarding_data($onboarding_email_address, $onboarding_first_name, $onboarding_last_name, $onboarding_message){
		$this->db->query("INSERT INTO `onboarding`(`onboarding_email`, `onboarding_first_name`, `onboarding_last_name`, `onboarding_message`, `has_sent`) VALUES ('$onboarding_email_address', '$onboarding_first_name', '$onboarding_last_name', '$onboarding_message', 1)");

		$last_insert_id = $this->db->insert_id();
		return $last_insert_id;
	}

	public function fetch_pending_onboard(){
		$query = $this->db->query("SELECT t1.`company_id`, t1.`company_name`,t2.`company_type_id`, t2.`company_type`, t4.`suburb`, t5.`shortname`,
			 t6.`contact_person_id`, CONCAT(t7.`first_name`, ' ', t7.`last_name`) AS contact_full_name, t8.`general_email`, t6.`is_primary`, t1.`is_decline`, t1.`declined_message`
			FROM `company_details` AS t1
			LEFT JOIN `company_type` AS t2 ON t1.`company_type_id` = t2.`company_type_id`
			LEFT JOIN `address_detail` AS t3 ON t1.`address_id` = t3.`address_detail_id`
			LEFT JOIN `address_general` AS t4 ON t3.`general_address_id` = t4.`general_address_id`
			LEFT JOIN `states` AS t5 ON t4.`state_id` = t5.`id`
			LEFT JOIN `contact_person_company` AS t6 ON t1.`company_id` = t6.`company_id` AND t6.`is_primary` = '1'
			LEFT JOIN `contact_person` AS t7 ON t6.`contact_person_id` = t7.`contact_person_id`
			LEFT JOIN `email` AS t8 ON t7.`email_id` = t8.`email_id`
			WHERE t1.`is_pending` = '1' AND t1.`active` = '0' AND `is_disable` = '0' AND is_decline = '0'");
		return $query;
	}

	public function fetch_declined_onboard(){
		$query = $this->db->query("SELECT t1.`company_id`, t1.`company_name`,t2.`company_type_id`, t2.`company_type`, t4.`suburb`, t5.`shortname`,
			 t6.`contact_person_id`, CONCAT(t7.`first_name`, ' ', t7.`last_name`) AS contact_full_name, t8.`general_email`, t6.`is_primary`, t1.`is_decline`, t1.`declined_message`
			FROM `company_details` AS t1
			LEFT JOIN `company_type` AS t2 ON t1.`company_type_id` = t2.`company_type_id`
			LEFT JOIN `address_detail` AS t3 ON t1.`address_id` = t3.`address_detail_id`
			LEFT JOIN `address_general` AS t4 ON t3.`general_address_id` = t4.`general_address_id`
			LEFT JOIN `states` AS t5 ON t4.`state_id` = t5.`id`
			LEFT JOIN `contact_person_company` AS t6 ON t1.`company_id` = t6.`company_id` AND t6.`is_primary` = '1'
			LEFT JOIN `contact_person` AS t7 ON t6.`contact_person_id` = t7.`contact_person_id`
			LEFT JOIN `email` AS t8 ON t7.`email_id` = t8.`email_id`
			WHERE t1.`is_pending` = '1' AND t1.`active` = '0' AND `is_disable` = '0' AND is_decline = '1'");
		return $query;
	}

	public function fetch_pending_onboard_id($company_id){
		$query = $this->db->query("SELECT t1.`company_id`, t1.`company_name`, t1.`company_type_id`, t1.`address_id`, t1.`postal_address_id`, t1.`activity_id`, t9.`job_category`, t1.`parent_company_id`, t1.`sub_client_id`, t1.`abn`, t1.`acn`, t1.`notes_id`, t10.`comments`, t2.`company_type`, t4.`suburb`, t5.`shortname`, t6.`contact_person_id`, CONCAT(t7.`first_name`, ' ', t7.`last_name`) AS contact_full_name, t8.`general_email`, t11.`bank_account_id`, t11.`bank_account_name`, t11.`bank_account_number`, t11.`bank_name`, t11.`bank_bsb_number`, t6.`is_primary`, t1.`is_pending`, t12.*
			FROM `company_details` AS t1
			LEFT JOIN `company_type` AS t2 ON t1.`company_type_id` = t2.`company_type_id`
			LEFT JOIN `address_detail` AS t3 ON t1.`address_id` = t3.`address_detail_id`
			LEFT JOIN `address_general` AS t4 ON t3.`general_address_id` = t4.`general_address_id`
			LEFT JOIN `states` AS t5 ON t4.`state_id` = t5.`id`
			LEFT JOIN `contact_person_company` AS t6 ON t1.`company_id` = t6.`company_id` AND t6.`is_primary` = '1'
			LEFT JOIN `contact_person` AS t7 ON t6.`contact_person_id` = t7.`contact_person_id`
			LEFT JOIN `email` AS t8 ON t7.`email_id` = t8.`email_id`
			LEFT JOIN `job_category` AS t9 ON t1.`activity_id` = t9.`job_category_id`
			LEFT JOIN `notes` AS t10 ON t1.`notes_id` = t10.`notes_id`
			LEFT JOIN `bank_account` AS t11 ON t1.`bank_account_id` = t11.`bank_account_id`
			LEFT JOIN `onboarding_form_contractor` AS t12 ON t1.`company_id` = t12.`onboarding_id`
			WHERE t1.`company_id` = '$company_id' AND t1.`is_pending` = '1' AND t1.`active` = '0' AND `is_disable` = '0'");

			if ($query->num_rows() > 0){
			   return $query;
			} else {
			   return 0;
			}
	}

	public function fetch_company_ohs($company_id){
		$query = $this->db->query("SELECT * FROM `onboarding_form_contractor` WHERE onboarding_id = '$company_id'");
		return $query;
	}

	public function fetch_pending_address($company_id){
		$query = $this->db->query("SELECT t1.`company_name`, t2.`unit_number` AS physical_unit_number, t2.`unit_level` AS physical_unit_level, t2.`street` AS physical_street, t4.`suburb` AS physical_suburb, t4.`postcode` AS physical_postcode, t6.`name` AS physical_state, t3.`unit_number` AS postal_unit_number, t3.`unit_level` AS postal_unit_level, t3.`street` AS postal_street, t3.`po_box`, t5.`suburb` AS postal_suburb, t5.`postcode` AS postal_postcode, t7.`name` AS postal_state
			FROM `company_details` AS t1
			LEFT JOIN `address_detail` AS t2 ON t1.`address_id` = t2.`address_detail_id`
			LEFT JOIN `address_detail` AS t3 ON t1.`postal_address_id` = t3.`address_detail_id`
			LEFT JOIN `address_general` AS t4 ON t2.`general_address_id` = t4.`general_address_id`
			LEFT JOIN `address_general` AS t5 ON t3.`general_address_id` = t5.`general_address_id`
			LEFT JOIN `states` AS t6 ON t4.`state_id` = t6.`id`
			LEFT JOIN `states` AS t7 ON t5.`state_id` = t7.`id`
			WHERE t1.`company_id` = '$company_id'");
		return $query;
	}

	public function onboard_approved($company_id){
		$query = $this->db->query("UPDATE `company_details` SET `is_pending` = '0', `active` = '1' WHERE `company_id` = '$company_id'");
		return $query;
	}

	public function onboard_declined($company_id, $declined_message){
		$query = $this->db->query("UPDATE `company_details` SET `is_decline` = '1', `declined_message` = '$declined_message' WHERE `company_id` = '$company_id'");
		return $query;
	}

	public function onboard_removed($company_id){
		$query = $this->db->query("UPDATE `company_details` SET `is_disable` = '1' WHERE `company_id` = '$company_id'");
		return $query;
	}

	public function check_abn_onboarding($abn, $company_id){
		$query = $this->db->query("SELECT * FROM company_details WHERE company_type_id IN (2, 3) AND abn = '$abn' AND company_id <> '$company_id'");
		return $query;
	}

	public function select_static_defaults(){
		$query = $this->db->query("SELECT * FROM `static_defaults` ORDER BY `static_defaults_id` DESC LIMIT 1");
		return $query;
	}

	public function update_workplace_health_safety($onboarding_id,$workplace_health_safety,$workplace_health_safety_notes){
		$query = $this->db->query("UPDATE `onboarding_form_contractor` SET `workplace_health_safety`='$workplace_health_safety',`workplace_health_safety_notes`='$workplace_health_safety_notes' WHERE `onboarding_id` = '$onboarding_id'");
		return $query;		
	}

	public function update_swms($onboarding_id,$swms,$swms_notes){
		$query = $this->db->query("UPDATE `onboarding_form_contractor` SET `swms`='$swms',`swms_notes`='$swms_notes' WHERE `onboarding_id` = '$onboarding_id'");
		return $query;		
	}

	public function update_jsa($onboarding_id,$jsa,$jsa_notes){
		$query = $this->db->query("UPDATE `onboarding_form_contractor` SET `jsa`='$jsa',`jsa_notes`='$jsa_notes' WHERE `onboarding_id` = '$onboarding_id'");
		return $query;		
	}

	public function update_reviewed_swms($onboarding_id,$reviewed_swms,$reviewed_swms_notes){
		$query = $this->db->query("UPDATE `onboarding_form_contractor` SET `reviewed_swms`='$reviewed_swms',`reviewed_swms_notes`='$reviewed_swms_notes' WHERE `onboarding_id` = '$onboarding_id'");
		return $query;		
	}

	public function update_safety_related_convictions($onboarding_id,$safety_related_convictions,$safety_related_convictions_notes){
		$query = $this->db->query("UPDATE `onboarding_form_contractor` SET `safety_related_convictions`='$safety_related_convictions',`safety_related_convictions_notes`='$safety_related_convictions_notes' WHERE `onboarding_id` = '$onboarding_id'");
		return $query;		
	}

	public function update_confirm_licences_certifications($onboarding_id,$confirm_licences_certifications,$confirm_licences_certifications_notes){
		$query = $this->db->query("UPDATE `onboarding_form_contractor` SET `confirm_licences_certifications`='$confirm_licences_certifications',`confirm_licences_certifications_notes`='$confirm_licences_certifications_notes' WHERE `onboarding_id` = '$onboarding_id'");
		return $query;		
	}

	public function fetch_ohs_updated($company_id){
		$query = $this->db->query("SELECT * FROM `onboarding_form_contractor` WHERE `onboarding_id` = '$company_id'");
		return $query;
	}

	public function fetch_ohs_count($company_id){
		$query = $this->db->query("SELECT * FROM `onboarding_form_contractor` WHERE `onboarding_id` = '$company_id'");
		return $query->num_rows();
	}

	public function enable_ohs_form($company_id){
		$query = $this->db->query("INSERT INTO `onboarding_form_contractor` (`onboarding_id`) VALUES ('$company_id')");

		$last_insert_id = $this->db->insert_id();
		return $last_insert_id;
	}

	// public function insert_email_onboarding($email_add){
	// 	$query = $this->db->query("INSERT INTO `email`(`general_email`) VALUES ('$email_add')");
	// }

	// public function insert_contact_name_onboarding($email_add){
	// 	$query = $this->db->query("INSERT INTO `email`(`general_email`) VALUES ('$email_add')");
	// }

	// Onboarding Queries end
}