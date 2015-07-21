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
	
	public function fetch_all_company($comp_id){
		if($comp_id==''){
			$query = $this->db->query("SELECT * FROM  `company_details` WHERE  `company_type_id` <> 4 ORDER BY  `company_details`.`company_name` ASC");
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

	public function update_other_details($abn,$acn,$activity_id,$company_type_id,$parent_company_id,$company_id){
		$query = $this->db->query("UPDATE `company_details` SET `abn` = '$abn', `acn` = '$acn',`activity_id` = '$activity_id',`company_type_id` = '$company_type_id',`parent_company_id` = '$parent_company_id' WHERE `company_id` = '$company_id'");
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
		$query = $this->db->query("SELECT * FROM  `company_details` WHERE  `company_type_id` = '$comp_id' ORDER BY  `company_details`.`company_name` ASC ");
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
		$query = $this->db->query("SELECT `company_details`.`company_id`,`company_details`.`company_name` ,  `address_general`.`suburb` ,  `states`.`shortname` , `contact_number`.`area_code`, `contact_number`.`office_number`,`email`.`general_email`
			FROM  `company_details`			
			LEFT JOIN  `address_detail` ON  `address_detail`.`address_detail_id` =  `company_details`.`address_id` 
			LEFT JOIN  `address_general` ON  `address_general`.`general_address_id` =  `address_detail`.`general_address_id`
			LEFT JOIN `contact_person_company` ON `contact_person_company`.`company_id` = `company_details`.`company_id`
			LEFT JOIN  `contact_person` ON  `contact_person`.`contact_person_id` = `contact_person_company`.`contact_person_id`			
			LEFT JOIN `contact_number` ON `contact_number`.`contact_number_id` = `contact_person`.`contact_number_id`
			LEFT JOIN `email` ON `email`.`email_id` = `contact_person`.`email_id`
			LEFT JOIN  `states` ON  `states`.`id` =  `address_general`.`state_id` 
			WHERE  `company_details`.`company_type_id` =  '$type' AND `contact_person_company`.`is_primary` = '1' AND `company_details`.`active` = '1'
			ORDER BY  `company_details`.`company_id` ASC ");
		return $query;
	}
	
	public function display_company_detail_by_id($id){
		$query = $this->db->query("SELECT `company_details`.*

			FROM  `company_details`
			/*LEFT JOIN  `email` ON  `email`.`email_id` =  `company_details`.`email_id`*/
			/*LEFT JOIN  `contact_number` ON  `contact_number`.`contact_number_id` =  `company_details`.`contact_number_id`*/
			LEFT JOIN  `address_detail` ON  `address_detail`.`address_detail_id` =  `company_details`.`address_id`
			LEFT JOIN  `address_general` ON  `address_general`.`general_address_id` =  `address_detail`.`general_address_id`
			LEFT JOIN  `company_type` ON  `company_type`.`company_type_id` =  `company_details`.`company_type_id`
			WHERE   `company_details`.`company_id` =  '$id'");
		return $query;
	}	





	public function insert_email($general_email, $direct = '', $accounts='',$maintenance=''){
		$this->db->query("INSERT INTO `email` (`general_email`, `direct`, `accounts`, `maintenance`) VALUES ('$general_email', '$direct', '$accounts', '$maintenance')");
		$last_insert_id = $this->db->insert_id();
		return $last_insert_id;
	}

	public function insert_contact_number($area_code,$office_number,$direct_number='',$mobile_number='',$after_hours=''){
		$this->db->query("INSERT INTO `contact_number` (`area_code`, `office_number`, `direct_number`, `mobile_number`, `after_hours`) VALUES ('$area_code', '$office_number', '$direct_number', '$mobile_number', '$after_hours')");
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

	public function insert_company_details($company_name,$abn,$acn,$activity_id,$address_id,$postal_address_id,$company_type_id,$bank_account_id='',$notes_id='',$parent_company_id=''){
		$this->db->query("INSERT INTO `company_details` (`company_name`, `abn`, `acn`, `bank_account_id`, `activity_id`, `notes_id`, `address_id`, `postal_address_id`, `company_type_id`, `parent_company_id`) VALUES ('$company_name', '$abn', '$acn', '$bank_account_id', '$activity_id', '$notes_id','$address_id', '$postal_address_id', '$company_type_id', '$parent_company_id')");
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


	






	

}