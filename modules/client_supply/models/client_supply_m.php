<?php
class Client_supply_m extends CI_Model{

	public function inset_new_supply($supply_name,$project_id,$quantity,$date_goods_expected,$date_goods_arrived,$delivered_by,$to_be_advised,$delivery_date,$is_deliver_to_site,$address,$photos,$description,$warehouse,$user_id){
		$this->db->query("INSERT INTO `client_supply` ( `supply_name`, `project_id`, `quantity`, `date_goods_expected`, `date_goods_arrived`, `delivered_by`, `to_be_advised`, `delivery_date`, `is_deliver_to_site`, `address`, `photos`, `description`, `warehouse`,`added_by_user`) VALUES 
			('$supply_name', '$project_id', '$quantity', '$date_goods_expected', '$date_goods_arrived', '$delivered_by', '$to_be_advised', '$delivery_date', '$is_deliver_to_site', '$address', '$photos', '$description', '$warehouse', '$user_id')");
	}

	public function list_client_supply($custom=''){
		$query = $this->db->query(" SELECT `client_supply`.* ,  UNIX_TIMESTAMP( STR_TO_DATE(`client_supply`.`date_goods_expected`, '%d/%m/%Y') ) AS `unix_dt_gds_expt`, `company_details`.`company_name`, `project`.`project_name`
			,  UNIX_TIMESTAMP( STR_TO_DATE(`client_supply`.`date_goods_arrived`, '%d/%m/%Y') ) AS `unix_dt_gds_arv`,  UNIX_TIMESTAMP( STR_TO_DATE(`client_supply`.`delivery_date`, '%d/%m/%Y') ) AS `unix_dlvy_dt`  , `project`.`focus_company_id`
			FROM `client_supply` 
            INNER JOIN `project` ON `project`.`project_id` = `client_supply`.`project_id` 
            INNER JOIN `company_details` ON `company_details`.`company_id` = `project`.`client_id` 
            WHERE `client_supply`.`is_active` = '1' 
            ". ($custom != '' ? $custom : " ORDER BY `client_supply`.`client_supply_id` ASC "  )." ");
		return $query;
	}

	public function update_photos($id,$photos){
		$this->db->query(" UPDATE `client_supply` SET `photos` = '$photos' WHERE `client_supply`.`client_supply_id` = '$id' ");
	}

	public function set_auto_delivered($set_delivered_date,$date_limit){
		$this->db->query(" UPDATE `client_supply` SET `is_delivered_date` = '$set_delivered_date' 
			WHERE `client_supply`.`is_active` = '1'  
			AND `client_supply`.`is_delivered_date`  IS NULL
			AND UNIX_TIMESTAMP( STR_TO_DATE(`client_supply`.`delivery_date`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$date_limit', '%d/%m/%Y') ) ");
	}



	
	public function get_client_supply_logo($warehouse_id){
	/*	$query = $this->db->query("SELECT `client_storage`.*, `client`.`company_name` AS `client_name`,`client`.`company_id` AS `client_id`, `f_company`.`company_name`,`warehouse`.`location`
			FROM `client_storage`
			INNER JOIN `company_details` `client` ON `client`.`company_id` = `client_storage`.`client_brand_id`
			INNER JOIN `warehouse` ON `warehouse`.`warehouse_id` = `client_storage`.`warehouse_id`
			INNER JOIN `company_details` `f_company` ON `f_company`.`company_id` = `warehouse`.`focus_company_id` WHERE `client_storage`.`is_active` = '1'  
			AND  `client_storage`.`client_storage_id` = '$warehouse_id' ");*/


$query = $this->db->query(" SELECT `client_storage`.* , `company_details`.`company_name`
	FROM `client_storage`  
	INNER JOIN `company_details` ON `company_details`.`company_id` = `client_storage`.`client_brand_id`
	WHERE `client_storage`.`is_active` = '1'
	AND  `client_storage`.`warehouse_id` = '$warehouse_id'  ");

return $query;
}

	public function get_supply_data($supply_id){
		$query = $this->db->query(" SELECT `client_supply`.*, CONCAT(`users`.`user_first_name`, ' ',`users`.`user_last_name`) AS `user_posted`
			FROM `client_supply` 
			INNER JOIN `users` ON `users`.`user_id` = `client_supply`.`added_by_user`


			WHERE `client_supply`.`client_supply_id` = '$supply_id'  ");
		return $query;
	}

	public function set_delivered($id,$set_date){
		$this->db->query(" UPDATE `client_supply` SET `is_delivered_date` = '$set_date' WHERE `client_supply`.`client_supply_id` = '$id' ");
	}

	public function set_arrived($id,$set_date){
		$this->db->query(" UPDATE `client_supply` SET `date_goods_arrived` = '$set_date' WHERE `client_supply`.`client_supply_id` = '$id' ");
	}





	


	public function get_static_supply_data(){
		$query = $this->db->query(" SELECT `static_defaults`.`weeks_delivery` FROM `static_defaults` LIMIT 1  ");
		return $query;
	}

	public function update_supply_details($supply_id,$supply_name,$project_id,$quantity,$date_goods_expected,$date_goods_arrived,$delivered_by,$to_be_advised,$delivery_date,$is_deliver_to_site,$address,$description,$warehouse){
		$query = $this->db->query(" UPDATE `client_supply` 

			SET `supply_name` = '$supply_name', `project_id` = '$project_id',  `quantity` = '$quantity', `date_goods_expected` = '$date_goods_expected', 
			`date_goods_arrived` = '$date_goods_arrived', 
			`delivered_by` = '$delivered_by', `to_be_advised` = '$to_be_advised', `delivery_date` = '$delivery_date',
			 `is_deliver_to_site` = '$is_deliver_to_site', `address` = '$address', `description` = '$description', `warehouse` = '$warehouse' WHERE `client_supply`.`client_supply_id` = '$supply_id' ");
	}

	public function list_photos($supply_id){
		$query = $this->db->query(" SELECT `photos` FROM `client_supply`WHERE `client_supply`.`client_supply_id` = '$supply_id'  ");
		return $query;
	}

	public function delete_supply($supply_id){
		$query = $this->db->query("  UPDATE `client_supply` SET `is_active` = '0' WHERE `client_supply`.`client_supply_id` = '$supply_id'  ");
	}



}
