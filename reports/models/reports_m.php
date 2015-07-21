<?php

class Reports_m extends CI_Model{


	public function select_list_company($company_type,$more_q,$order){
		$query = $this->db->query("SELECT * FROM  `company_details`			
			LEFT JOIN  `address_detail` ON  `address_detail`.`address_detail_id` =  `company_details`.`address_id` 
			LEFT JOIN  `address_general` ON  `address_general`.`general_address_id` =  `address_detail`.`general_address_id`
			LEFT JOIN `contact_person_company` ON `contact_person_company`.`company_id` = `company_details`.`company_id`
			LEFT JOIN  `contact_person` ON  `contact_person`.`contact_person_id` = `contact_person_company`.`contact_person_id`			
			LEFT JOIN `contact_number` ON `contact_number`.`contact_number_id` = `contact_person`.`contact_number_id`
			LEFT JOIN `email` ON `email`.`email_id` = `contact_person`.`email_id`
			LEFT JOIN  `states` ON  `states`.`id` =  `address_general`.`state_id` 
            LEFT JOIN `client_category` ON `client_category`.`client_category_id` = `company_details`.`activity_id`
			WHERE `contact_person_company`.`is_primary` = '1' AND `company_details`.`active` = '1'
			$company_type $more_q
			ORDER BY $order ");
		return $query;
	}
//`company_details`.`company_id` ASC

}