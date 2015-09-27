<?php

class Invoice_m extends CI_Model{

	public function insert_new_invoice($invoice_date_req, $project_id, $progress_percent, $label, $order_invoice){
		$this->db->query("INSERT INTO `invoice` (`invoice_date_req`, `project_id`,`progress_percent`, `label`,`order_invoice`) VALUES ('$invoice_date_req','$project_id','$progress_percent','$label','$order_invoice')");
		return $this->db->insert_id();	
	}

	public function list_invoice($project_id){
		$query = $this->db->query("SELECT * FROM `invoice` WHERE `invoice`.`project_id` = '$project_id' AND `invoice`.`label` <> 'VR'  ORDER BY `invoice`.`order_invoice` ASC ");
		return $query;		
	}

	public function list_invoiced($project_id){
		$query = $this->db->query("SELECT * FROM `invoice` WHERE `is_invoiced` = '1' AND `project_id` = '$project_id' LIMIT 1");
		return $query;
	}

	public function get_invoices($project_id){
		$query = $this->db->query("SELECT * FROM `invoice` WHERE `invoice`.`project_id` = '$project_id' ORDER BY `invoice`.`order_invoice` ASC");
		return $query;		
	}

	public function delete_invoice($project_id){
		$query = $this->db->query("DELETE FROM `invoice` WHERE `invoice`.`project_id` = '$project_id'");
		return $query;		
	}

	public function delete_some_invoice($project_id){
		$query = $this->db->query("DELETE FROM `invoice` WHERE `invoice`.`project_id` = '$project_id' AND `invoice`.`is_invoiced` = '0' ");
		return $query;		
	}

	public function fetch_project_jobbook($project_id){
		$query = $this->db->query("SELECT `project`.`project_id`,`notes`.`notes`,`notes`.`notes_id` FROM `project` LEFT JOIN `notes` ON `notes`.`notes_id` = `project`.`notes_id` WHERE `project`.`project_id` = '$project_id' ");
		return $query;		
	}

	public function update_job_notes($notes_id,$notes){
		$query = $this->db->query("UPDATE `notes` SET `notes` = '$notes' WHERE `notes`.`notes_id` = '$notes_id'");
		return $query;
	}

	public function set_invoiced_progress($set_invoice_date,$order_invoice,$project_id,$invoice_item_amount){
		$query = $this->db->query("UPDATE `invoice` SET `set_invoice_date` = '$set_invoice_date', `is_invoiced` = '1', `invoiced_amount` = '$invoice_item_amount' WHERE `invoice`.`order_invoice` = '$order_invoice' AND `invoice`.`project_id` = '$project_id' ");
		return $query;
	}

	public function list_invoiced_items($project_id){
		$query = $this->db->query("SELECT * FROM `invoice` WHERE `invoice`.`project_id` = '$project_id' AND `invoice`.`is_invoiced` = '1' ORDER BY `invoice`.`order_invoice` ASC");
		return $query;
	}

	public function update_progress_percent($invoice_id,$progress_percent){
		$query = $this->db->query("UPDATE `invoice` SET `progress_percent` = '$progress_percent' WHERE `invoice`.`invoice_id` = '$invoice_id'");
		return $query;
	}

	public function list_uninvoiced_items($project_id=''){
		if($project_id != ''){
			$query = $this->db->query("SELECT * FROM `invoice` WHERE `invoice`.`project_id` = '$project_id' AND `invoice`.`is_invoiced` = '0' ORDER BY `invoice`.`order_invoice` ASC");
		}else{
			$query = $this->db->query("SELECT * FROM `invoice` WHERE `invoice`.`is_invoiced` = '0' AND `invoice`.`is_paid` = '0' ORDER BY `invoice`.`project_id`,`invoice`.`order_invoice` ASC");
		}

		return $query;
	}

	public function set_payment_invoice($invoice_id,$is_paid){
		$query = $this->db->query("UPDATE `invoice` SET `is_paid` = '$is_paid' WHERE `invoice`.`invoice_id` = '$invoice_id' ");
		return $query;
	}

	public function select_vr_invoice($project_id){
		$query = $this->db->query("SELECT * FROM `invoice` WHERE `invoice`.`project_id`='$project_id' AND `invoice`.`label` = 'VR' AND `invoice`.`is_invoiced` = '1'");
		return $query;
	}

	public function insert_payment($project_id,$notes_id,$amount_exgst,$invoice_id,$payment_date,$reference_number){
		$query = $this->db->query("INSERT INTO `payment` (`project_id`, `notes_id`, `amount_exgst`, `invoice_id`, `payment_date`, `reference_number`) VALUES ('$project_id', '$notes_id', '$amount_exgst', '$invoice_id', '$payment_date', '$reference_number')");
		return $this->db->insert_id();		
	}

	public function get_total_amount_paid($project_id,$invoice_id){
		$query = $this->db->query("SELECT SUM(`payment`.`amount_exgst`) AS `total_paid` FROM `payment` WHERE `payment`.`project_id` = '$project_id' AND `payment`.`invoice_id` ='$invoice_id' ");
		return $query;
	}

	public function get_total_amount_paid_project($project_id){
		$query = $this->db->query("SELECT SUM(`payment`.`amount_exgst`) AS `total_paid` FROM `payment` WHERE `payment`.`project_id` = '$project_id' ");
		return $query;
	}

	public function just_select_all_invoice(){
		$query = $this->db->query("SELECT `invoice`.* ,`project`.`project_total` FROM `invoice` LEFT JOIN `project` ON  `project`.`project_id` = `invoice`.`project_id`  ");
		return $query;
	}

	public function fetch_payment_history($project_id,$invoice_id){
		$query = $this->db->query("SELECT * FROM `payment` LEFT JOIN `notes` ON `notes`.`notes_id` =  `payment`.`notes_id` WHERE `payment`.`project_id` = '$project_id' AND `payment`.`invoice_id` ='$invoice_id' ORDER BY `payment`.`payment_id`  ASC ");
		return $query;
	}

	public function set_project_as_paid($project_id){
		$query = $this->db->query("UPDATE `project` SET `is_paid` = '1', `is_wip` = '0' WHERE `project`.`project_id` = '$project_id'");
		return $query;
	}

	public function set_project_as_fully_invoiced($project_id){
		$query = $this->db->query("UPDATE `project` SET `is_wip` = '0' WHERE `project`.`project_id` = '$project_id'");
		return $query;
	}

	public function fetch_list_to_remove($project_id,$invoice_id){
		$query = $this->db->query("SELECT * FROM `payment` WHERE `payment`.`project_id` = '$project_id'  AND `payment`.`invoice_id` = '$invoice_id' ORDER BY `payment`.`payment_id` DESC LIMIT 1 ");
		return $query;
	}

	public function delete_payments($payment_id,$notes_id,$invoice_id){
		$this->db->query("DELETE FROM `notes` WHERE `notes`.`notes_id` = '$notes_id'");
		$this->db->query("DELETE FROM `payment` WHERE `payment`.`payment_id` = '$payment_id' ");
		$this->db->query("UPDATE `invoice` SET `is_paid` = '0' WHERE `invoice`.`invoice_id` = '$invoice_id'");
	}

	public function un_invoice($invoice_id){
		$query = $this->db->query("UPDATE `invoice` SET `set_invoice_date` = '', `is_paid` = '0', `is_invoiced` = '0',`invoiced_amount` = '0.00'  WHERE `invoice`.`invoice_id` = '$invoice_id'");
		return $query;
	}

	public function fetch_total_invoiced($project_id){
		$query = $this->db->query("SELECT SUM(`invoice`.`progress_percent`) AS `total_invoiced` FROM `invoice` WHERE `invoice`.`project_id` = '$project_id' AND `invoice`.`is_invoiced` = '1'");
		return $query;
	}

	public function update_invoice_notes($notes_id,$notes){
		$query = $this->db->query("UPDATE `notes` SET `notes` = '$notes' WHERE `notes`.`notes_id` = '$notes_id' ");
		return $query;
	}

	public function update_invoiced_amount($invoice_id,$invoiced_amount){
		$query = $this->db->query("UPDATE `invoice` SET `invoiced_amount` = '$invoiced_amount' WHERE `invoice`.`invoice_id` = '$invoice_id' ");
		return $query;
	}

	public function select_project_notes($project_id){
		$query = $this->db->query("SELECT `notes_id` FROM `project` WHERE `project`.`project_id` = '$project_id' ");
		return $query;
	}

	public function list_invoice_project_number(){



		//$query = $this->db->query("SELECT * FROM `invoice` LEFT JOIN `project` ON `project`.`project_id` = `invoice`.`project_id` WHERE `project`.`job_date` <> '' AND `project`.`is_wip` = '1' GROUP BY `invoice`.`project_id`");
		

		$query = $this->db->query("SELECT * FROM `invoice` GROUP BY `invoice`.`project_id`");
		return $query;
	}

	public function list_invoice_by_project_number($project_id){
		$query = $this->db->query("SELECT * FROM `invoice` LEFT JOIN  `project` ON `project`.`project_id` = '$project_id' WHERE `invoice`.`project_id` = '$project_id' ORDER BY `invoice`.`order_invoice` ASC");
		return $query;
	}

	public function insert_invoiced_variation($invoice_date_req,$set_invoice_date,$project_id,$order_invoice){
		$this->db->query("UPDATE .`invoice` SET `invoice_date_req` = '$invoice_date_req', `set_invoice_date` = '$set_invoice_date', `progress_percent` = '100.00', `is_invoiced` = '1' 
			WHERE `invoice`.`project_id` = '$project_id' AND `invoice`.`label` = 'VR'");
		return $this->db->insert_id();		
	}

	public function fetch_invoice_vr($project_id){
		$query = $this->db->query("SELECT * FROM `invoice` WHERE `invoice`.`project_id` = '$project_id' AND `invoice`.`label` = 'VR'");
		return $query;
	}

	public function un_invoice_vr($project_id){
		$query = $this->db->query("DELETE FROM `invoice` WHERE `invoice`.`project_id` = '$project_id' AND `invoice`.`label` = 'VR'");
		return $query;
	}

	public function list_unpaid_invoiced($project_id,$is_paid=0){
		$query = $this->db->query("SELECT * FROM `invoice` WHERE `invoice`.`is_invoiced` = '1' AND `invoice`.`is_paid` = '$is_paid' AND `invoice`.`project_id` = '$project_id' ORDER BY `invoice`.`order_invoice` ASC");
		return $query;
	}

	public function list_invoice_project($is_paid=0){
		$query = $this->db->query("SELECT `invoice`.`project_id` FROM `invoice` LEFT JOIN `project` ON `project`.`project_id` = `invoice`.`project_id` WHERE `project`.`job_date` <> '' AND `invoice`.`is_invoiced` = '1' AND `invoice`.`is_paid` = '$is_paid' AND `project`.`is_active` = '1' GROUP BY `invoice`.`project_id` ORDER BY `invoice`.`project_id` DESC");
		return $query;		
	}

	public function list_invoice_project_paid(){
		$query = $this->db->query("SELECT `invoice`.`project_id` FROM `invoice` LEFT JOIN `project` ON `project`.`project_id` = `invoice`.`project_id` WHERE `invoice`.`is_invoiced` = '1' AND `invoice`.`is_paid` = '1' AND `project`.`is_active` = '1' GROUP BY `invoice`.`project_id` ORDER BY `invoice`.`project_id` DESC");
		return $query;		
	}


}