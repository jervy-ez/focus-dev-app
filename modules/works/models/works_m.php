<?php

class Works_m extends CI_Model{
	public function display_all_works($projid,$is_variation='0',$vairiation_id='0'){
		$query = $this->db->query("select *, if(contractor_type = 3, supplier_cat_name, if(work_con_sup_id = 82,other_work_desc,job_sub_cat)) as work_desc from works a 
				left join project b on a.project_id = b.project_id
				left join job_sub_category c on a.work_con_sup_id = c.job_sub_cat_id
				left join supplier_cat f on a.work_con_sup_id = f.supplier_cat_id
				left join company_details d on a.company_client_id = d.company_id
				left join notes e on a.note_id = e.notes_id
				where a.project_id = '$projid' AND a.is_variation = '$is_variation' AND a.variation_id = '$vairiation_id' AND a.is_active = '1' order by work_desc ");
		return $query;
	}

	public function display_all_works_with_var($projid){
		$query = $this->db->query("select *, if(contractor_type = 3, supplier_cat_name, if(work_con_sup_id = 82,other_work_desc,job_sub_cat)) as work_desc from works a 
				left join project b on a.project_id = b.project_id
				left join job_sub_category c on a.work_con_sup_id = c.job_sub_cat_id
				left join supplier_cat f on a.work_con_sup_id = f.supplier_cat_id
				left join company_details d on a.company_client_id = d.company_id
				left join notes e on a.note_id = e.notes_id
				where a.project_id = '$projid' AND a.is_active = '1' order by work_desc ");
		return $query;
	}

	public function display_works_selected($workid){
		$query = $this->db->query("SELECT * from works a 
				left join project b on a.project_id = b.project_id 
				left join job_sub_category c on a.work_con_sup_id = c.job_sub_cat_id
				left join supplier_cat d on a.work_con_sup_id = d.supplier_cat_id 
				left join company_details e on a.company_client_id = e.company_id 
				left join notes f on a.note_id = f.notes_id
				left join considerations g on a.works_id = g.work_id
				where a.works_id = '$workid'");
		return $query;
	}

	public function display_job_category(){
		$query = $this->db->query("select * from job_category");
		return $query;
	}

	public function display_job_subcategory($job_cat_id){
		$query = $this->db->query("select * from job_sub_category where job_category_id = '$job_cat_id' ");
		return $query;
	}
	public function display_supplier_category(){
		$query = $this->db->query("select * from supplier_cat");
		return $query;
	}
	public function get_site_costs(){
		$query = $this->db->query("select * from site_costs");
		return $query;
	}

	public function insert_work_notes($comments,$notes){
		$this->db->query("INSERT INTO `notes` (`comments`, `notes`) 
			VALUES ('$comments', '$notes')");
		
		$notes_id = $this->db->insert_id();
		return $notes_id;
	}

	public function insert_considerations($work_id,$woks_joinery_id = 0,$site_inspection_req, $special_conditions, $additional_visit_req, $operate_during_install, $week_work, $weekend_work, $after_hours_work, $new_premises, $free_access, $other, $otherdesc){
		$this->db->query("INSERT INTO `considerations` ( `work_id`, `work_joinery_id`,`site_inspection_req`, `special_conditions`, `additional_visit_req`, `operate_during_install`, `week_work`, `weekend_work`, `after_hours_work`, `new_premises`, `free_access`, `other`, `otherdesc`) 
			VALUES ('$work_id','$woks_joinery_id','$site_inspection_req','$special_conditions','$additional_visit_req','$operate_during_install','$week_work','$weekend_work','$after_hours_work','$new_premises','$free_access','$other','$otherdesc')");
		$considerations_id = $this->db->insert_id();
		return $considerations_id;
	}

	public function update_other_work_desc($work_id,$other_work_desc,$other_work_category_id){
		$query = $this->db->query("UPDATE works set contractor_type = 2, work_con_sup_id = 82, other_work_desc = '$other_work_desc', other_category_id = '$other_work_category_id' where works_id = '$work_id'");
	}
	// public function update_other_work_desc($work_id, $other_work_desc){
	// 	$query = $this->db->query("UPDATE works set other_work_desc = '$other_work_desc' where works_id = '$work_id'");
	// }
/*	public function verify_works_joinery($proj_id){
		$query = $this->db->query("SELECT * from works where project_id = '$proj_id' and contractor_type = 0");
		return $query;
	}
*/
	public function verify_joinery_name($joinery_name){
		$query = $this->db->query("SELECT * from joinery where joinery_name = '$joinery_name'");
		return $query;
	}

	public function insert_joinery_name($joinery_name){
		$query = $this->db->query("INSERT INTO joinery (joinery_name) VALUES('$joinery_name')");
		$joinery_id = $this->db->insert_id();
		return $joinery_id;
	}

	public function insert_new_works($contractor_type,$work_con_sup_id,$other_work_desc,$markup,$note_id,$is_deliver_office,$work_cpo_date,$work_reply_date,$project_id,$is_variation,$work_estimate,$total_work_quote,$variation_id=0,$other_category=0){
		$this->db->query("INSERT INTO `works` (`contractor_type`, `work_con_sup_id`,`other_category_id`,`other_work_desc`, `work_markup`, `note_id`,`is_deliver_office`, `work_cpo_date`, `work_reply_date`, `project_id`, `is_variation`,`variation_id`,`work_estimate`,`total_work_quote`) 
			VALUES ('$contractor_type', '$work_con_sup_id','$other_category','$other_work_desc', '$markup', '$note_id', '$is_deliver_office', '$work_cpo_date', '$work_reply_date', '$project_id', '$is_variation','$variation_id', '$work_estimate', '$total_work_quote')");
		
		$work_id = $this->db->insert_id();
		return $work_id;
	}

	// public function insert_new_works($contractor_type,$work_con_sup_id,$other_work_desc,$markup,$note_id,$is_deliver_office,$work_cpo_date,$work_reply_date,$project_id,$is_variation,$work_estimate,$total_work_quote,$variation_id=0){
	// 	$this->db->query("INSERT INTO `works` (`contractor_type`, `work_con_sup_id`,`other_work_desc`, `work_markup`, `note_id`,`is_deliver_office`, `work_cpo_date`, `work_reply_date`, `project_id`, `is_variation`,`variation_id`,`work_estimate`,`total_work_quote`) 
	// 		VALUES ('$contractor_type', '$work_con_sup_id','$other_work_desc', '$markup', '$note_id', '$is_deliver_office', '$work_cpo_date', '$work_reply_date', '$project_id', '$is_variation','$variation_id', '$work_estimate', '$total_work_quote')");
		
	// 	$work_id = $this->db->insert_id();
	// 	return $work_id;
	// }

	public function insert_works_joinery($works_id,$joinery_id,$work_markup,$note_id,$is_deliver_office,$work_cpo_date,$work_reply_date){
		$work_q = $this->db->query("SELECT * from `work_joinery` where works_id = '$works_id'");
		if($work_q->num_rows() > 0){
			$num_rows = $work_q->num_rows() + 1;
			$work_row = 1;
			for ($column='A'; $column!='ZZ'; $column++){ 
            	if($num_rows == $work_row){
            		$this->db->query("INSERT INTO `work_joinery` (`works_id`, `joinery_id`, `work_markup`, `note_id`,`is_deliver_office`, `work_cpo_date`, `work_reply_date`,`distinct_character`) 
							VALUES ('$works_id', '$joinery_id', '$work_markup', '$note_id', '$is_deliver_office', '$work_cpo_date', '$work_reply_date', '$column')");
            		break;
            	}
            	$work_row++;
        	} 
		}else{
			$this->db->query("INSERT INTO `work_joinery` (`works_id`, `joinery_id`, `work_markup`, `note_id`,`is_deliver_office`, `work_cpo_date`, `work_reply_date`,`distinct_character`) 
				VALUES ('$works_id', '$joinery_id', '$work_markup', '$note_id', '$is_deliver_office', '$work_cpo_date', '$work_reply_date', 'A')");
			
		}

		//$this->db->query("INSERT INTO `work_joinery` (`works_id`, `joinery_id`, `work_markup`, `note_id`,`is_deliver_office`, `work_cpo_date`, `work_reply_date`) 
		//	VALUES ('$works_id', '$joinery_id', '$work_markup', '$note_id', '$is_deliver_office', '$work_cpo_date', '$work_reply_date')");
		
		$work_id = $this->db->insert_id();
		return $work_id;
	}

	public function display_work_contructor($work_id){
		$query = $this->db->query("SELECT *, if(a.is_pending = 0, b.company_name, c.company_name ) AS comp_name, a.is_pending AS cs_is_pending FROM work_contractors a 
										LEFT JOIN company_details b ON a.company_id = b.company_id
										LEFT JOIN company_details_temp c ON c.company_details_temp_id = a.company_id
									WHERE works_id = '$work_id'
								");
		return $query;
	}

	public function insert_works_contractor($works_id,$date_added,$company_id,$contact_person_id){

		$query = $this->db->query("SELECT * from work_contractors where works_id = '$works_id' and company_id = '$company_id'");
		
		// if($query->num_rows == 0){

			$this->db->query("INSERT INTO `work_contractors` (`works_id`, `date_added`, `company_id`, `contact_person_id`) 
				VALUES ('$works_id', '$date_added', '$company_id', '$contact_person_id')");
			
			// $work_id = $this->db->insert_id();
			// return $work_id;
		// }

	}

	public function update_works_contractor($work_contractor_id,$works_id,$date_added,$company_id,$contact_person_id,$ex_gst,$inc_gst,$work_is_selected){//,){
		$this->db->query("UPDATE `work_contractors` set `date_added` = '$date_added', `company_id` = '$company_id', `contact_person_id` = '$contact_person_id', `ex_gst` = '$ex_gst',`inc_gst` = '$inc_gst' where works_contrator_id = '$work_contractor_id'");//, `ex_gst` = '$ex_gst',`inc_gst` = '$inc_gst' where works_contrator_id = '$work_contractor_id'");
		if($work_is_selected == 1){
			$this->db->query("UPDATE `works` set `company_client_id` = '$company_id' where works_id = '$works_id' ");
		}
	}

	public function update_works_contractor_details($work_contractor_id,$works_id,$date_added,$company_id,$contact_person_id,$work_is_selected){//,){
		$this->db->query("UPDATE `work_contractors` set `date_added` = '$date_added', `company_id` = '$company_id', `contact_person_id` = '$contact_person_id' where works_contrator_id = '$work_contractor_id'");//, `ex_gst` = '$ex_gst',`inc_gst` = '$inc_gst' where works_contrator_id = '$work_contractor_id'");
		if($work_is_selected == 1){
			$this->db->query("UPDATE `works` set `company_client_id` = '$company_id' where works_id = '$works_id' ");
		}
	}

	public function update_works_contractor_cqr($works_id,$company_id){
		$this->db->query("UPDATE `work_contractors` set `cqr_created` = 1 where `company_id` = '$company_id' and works_id = '$works_id'");
	}

	public function update_works_contractor_cpo($works_id,$company_id){
		$this->db->query("UPDATE `work_contractors` set `cpo_created` = 1 where `company_id` = '$company_id' and works_id = '$works_id'");

	}
	public function update_works_contractor_cqr_send($works_id,$company_id){
		$date_send = date('d/m/Y');
		$this->db->query("UPDATE `work_contractors` set `cqr_send` = 1, cqr_send_date = '$date_send' where `company_id` = '$company_id' and works_id = '$works_id'");
	}

	public function update_works_contractor_cpo_send($works_id,$company_id){
		$date_send = date('d/m/Y');
		$this->db->query("UPDATE `work_contractors` set `cpo_send` = 1, cpo_send_date = '$date_send' where `company_id` = '$company_id' and works_id = '$works_id'");

	}

	public function delete_works_contractor($work_contractor_id){
		$this->db->query("DELETE from `work_contractors` where works_contrator_id = '$work_contractor_id'");
	}
	public function update_works($update_stat,$work_id,$work_estimate,$work_markup,$work_quote_val,$work_replyby_date,$update_replyby_desc,$chkdeltooffice,$goods_deliver_by_date,$update_work_notes,$chkcons_site_inspect,$chckcons_week_work,$chckcons_spcl_condition,$chckcons_weekend_work,$chckcons_addnl_visit,$chckcons_afterhrs_work,$chckcons_oprte_duringinstall,$chckcons_new_premises,$chckcons_free_access,$chckcons_others,$other_consideration,$work_type,$work_con_sup_id,$price,$quoted,$work_joinery_id){
		switch($update_stat){
			case 1:
				$this->db->query("UPDATE `works` set `work_markup`= '$work_markup', `total_work_quote`='$work_quote_val' where works_id = '$work_id'");
				echo $work_id;
				break;
			case 2:
				if($work_joinery_id == "" || $work_joinery_id == 0){
					$this->db->query("UPDATE `works` set `work_reply_date` = '$work_replyby_date', `goods_deliver_by_date` = '$goods_deliver_by_date', `is_deliver_office`= '$chkdeltooffice' where works_id = '$work_id'");
					$work_q = $this->db->query("select * from `works` where works_id = '$work_id'");
					foreach ($work_q->result_array() as $row){
						$note_id = $row['note_id'];
					}
					$this->db->query("UPDATE `notes` set `comments` = '$update_replyby_desc' where notes_id = '$note_id'");
				 }else{
					$this->db->query("UPDATE `work_joinery` set `work_reply_date` = '$work_replyby_date', `goods_delivery_by_date` = '$goods_deliver_by_date', `is_deliver_office`= '$chkdeltooffice' where work_joinery_id = '$work_joinery_id'");
					$work_q = $this->db->query("select * from `work_joinery` where work_joinery_id = '$work_joinery_id'");
					foreach ($work_q->result_array() as $row){
						$note_id = $row['note_id'];
					}
					$this->db->query("UPDATE `notes` set `comments` = '$update_replyby_desc' where notes_id = '$note_id'");
				}
				
				break;
			case 3:
				if($work_joinery_id == 0 || $work_joinery_id == ""){
					$work_q = $this->db->query("select * from `works` where works_id = '$work_id'");
				}else{
					$work_q = $this->db->query("select * from `work_joinery` where work_joinery_id = '$work_joinery_id'");
				}
				
				foreach ($work_q->result_array() as $row){
					$note_id = $row['note_id'];
				}

				$this->db->query("UPDATE `notes` set `notes` = '$update_work_notes' where notes_id = '$note_id'");
				break;
			case 4:
				if($work_joinery_id == 0 || $work_joinery_id == ""){
					$this->db->query("UPDATE `considerations` set site_inspection_req = '$chkcons_site_inspect', special_conditions = '$chckcons_spcl_condition', additional_visit_req = '$chckcons_addnl_visit', operate_during_install = '$chckcons_oprte_duringinstall', week_work = '$chckcons_week_work', weekend_work = '$chckcons_weekend_work', after_hours_work = '$chckcons_afterhrs_work', new_premises = '$chckcons_new_premises', free_access = '$chckcons_free_access', other = '$chckcons_others', otherdesc = '$other_consideration' where work_id = '$work_id'");
				
				}else{
					$work_q = $this->db->query("select * from `considerations` where work_joinery_id = '$work_joinery_id'");
					if($work_q->num_rows !== 0){
						$this->db->query("UPDATE `considerations` set site_inspection_req = '$chkcons_site_inspect', special_conditions = '$chckcons_spcl_condition', additional_visit_req = '$chckcons_addnl_visit', operate_during_install = '$chckcons_oprte_duringinstall', week_work = '$chckcons_week_work', weekend_work = '$chckcons_weekend_work', after_hours_work = '$chckcons_afterhrs_work', new_premises = '$chckcons_new_premises', free_access = '$chckcons_free_access', other = '$chckcons_others', otherdesc = '$other_consideration' where work_joinery_id = '$work_joinery_id'");
						
					}else{
						$this->db->query("INSERT INTO `considerations` (`work_joinery_id`,`site_inspection_req`,`special_conditions`,`additional_visit_req`,`operate_during_install`,`week_work`,`weekend_work`,`after_hours_work`,`new_premises`,`free_access`,`other`,`otherdesc`) values('$work_joinery_id','$chkcons_site_inspect','$chckcons_spcl_condition','$chckcons_addnl_visit','$chckcons_oprte_duringinstall','$chckcons_week_work','$chckcons_weekend_work','$chckcons_afterhrs_work','$chckcons_new_premises','$chckcons_free_access','$chckcons_others','$other_consideration')");
					}
				}
				break;
			case 5:
				$this->db->query("UPDATE `works` set contractor_type = '$work_type', work_con_sup_id = '$work_con_sup_id', other_category_id = 0, other_work_desc = '' where works_id = '$work_id'");
				break;
			case 6:
				$this->db->query("UPDATE `works` set is_active = 0 where works_id = '$work_id'");
				break;
			case 7:
				$this->db->query("UPDATE `works` set price = '$price' where works_id = '$work_id'");
				break;
			case 8:
				$this->db->query("UPDATE `works` set work_estimate = '$price',total_work_quote = '$quoted' where works_id = '$work_id'");
				break;
		}
	}

	public function update_joinery_markup($joinery_id, $mark_up, $quoted){
		$this->db->query("UPDATE `work_joinery` set `work_markup`= '$mark_up', total_work_quote = '$quoted' where work_joinery_id = '$joinery_id'");
	}

	public function display_selected_contractor($works_contrator_id){
		$query = $this->db->query("select * from work_contractors where works_contrator_id = '$works_contrator_id'");
		return $query;
	}

	public function display_works_selected_contractor($is_pending = '0',$works_id,$company_id){
		$query = $this->db->query("select * from work_contractors where works_id = '$works_id' and company_id = '$company_id' and is_pending = '$is_pending'");
		return $query;
	}

	public function display_works_selected_contractor_selected($works_id,$company_id){
		$query = $this->db->query("select * from work_contractors where works_id = '$works_id' and company_id = '$company_id' and is_selected = 1");
		return $query;
	}

	public function set_selected_contractor($selected_work_contractor_id,$work_id){
		$cpo_set = 0;
		$this->db->query("update work_contractors set is_selected = 0 where works_id = '$work_id'");
		$this->db->query("update work_contractors set is_selected = 1 where works_contrator_id = '$selected_work_contractor_id'");

		$query = $this->db->query("select * from work_contractors where works_contrator_id = '$selected_work_contractor_id'");
		foreach ($query->result_array() as $row){
			$ex_gst = 	$row['ex_gst'];
			$company_id = $row['company_id'];			
		}
		$query = $this->db->query("select * from works where works_id = '$work_id'");
		foreach ($query->result_array() as $row){
			$company_client_id = $row['company_client_id'];	
			$work_cpo_date = $row['work_cpo_date'];	
		}

		if($company_client_id == $company_id){
			if($work_cpo_date == ""){
				$cpo_set = 0;
				$cpo_date = date('d/m/Y');
				$this->db->query("update works set price = '$ex_gst', company_client_id = '$company_id', work_cpo_date = '$cpo_date' where works_id = '$work_id'");
			}else{
				$cpo_set = 1;
				$this->db->query("update works set price = '$ex_gst', company_client_id = '$company_id' where works_id = '$work_id'");
			}
		}else{
			$cpo_date = date('d/m/Y');
			$this->db->query("update works set price = '$ex_gst', company_client_id = '$company_id', work_cpo_date = '$cpo_date' where works_id = '$work_id'");
		}
		
		return $cpo_set;
	}

	public function unset_selected_contractor($works_id, $comp_id){
		$this->db->query("UPDATE works set price = '', company_client_id = '', work_cpo_date = '' where works_id = '$works_id'");
		$this->db->query("UPDATE work_contractors set is_selected = 0 where works_id = '$works_id' and company_id = '$comp_id'");
		$this->db->query("DELETE from project_schedule_task where works_id = '$works_id'");
	}

	public function create_cpo_date($works_id){
		$date = date('d/m/Y');
		$query = $this->db->query("select * from works where works_id = '$works_id'");
		foreach ($query->result_array() as $row){
			$work_cpo_date = $row['work_cpo_date'];			
		}
		if($work_cpo_date == ""){
			$this->db->query("UPDATE works set work_cpo_date = '$date' where works_id = '$works_id'");
		}
	}

	public function create_joinery_cpo_date($work_joinery_id){
		$date = date('d/m/Y');
		$query = $this->db->query("select * from work_joinery where work_joinery_id = '$work_joinery_id'");
		foreach ($query->result_array() as $row){
			$work_cpo_date = $row['work_cpo_date'];			
		}
		if($work_cpo_date == ""){
			$this->db->query("UPDATE work_joinery set work_cpo_date = '$date' where work_joinery_id = '$work_joinery_id'");
		}
	}

	public function display_work_attachments($work_id){
		$query = $this->db->query("select * from work_attachments where works_id = '$work_id'");
		return $query;
	}
	public function insert_work_attachments($work_id,$attachment_type,$attachment_url){
		$attachment_date = date('d/m/Y');
		$this->db->query("INSERT INTO `work_attachments` (`works_id`, `work_attachments_type`, `work_attachments_url`, `work_attachements_date`) 
			VALUES ('$work_id', '$attachment_type', '$attachment_url', '$attachment_date')");
	}
	public function remove_work_attachments($work_id,$attachment_url){
		$this->db->query("DELETE FROM `work_attachments` where `works_id` = '$work_id' and `work_attachments_url` = '$attachment_url'");
	}

	public function update_work_attachments($work_attachment_id,$attachment_type){
		$this->db->query("UPDATE `work_attachments` SET `work_attachments_type` = '$attachment_type' WHERE `work_attachments_id` = '$work_attachment_id'");
	}

	public function fetch_considerations($work_id){
		$query = $this->db->query("SELECT * FROM `considerations` WHERE work_id = '$work_id' ");
		return $query;

	}

	public function insert_work_attachment_type($work_id,$attachment_type_id){
		$this->db->query("INSERT INTO `work_attachment_type` (`works_id`, `attachment_type_id`) 
			VALUES ('$work_id', '$attachment_type_id')");
	}

	public function fetch_work_attachment_type($work_id){
		$query = $this->db->query("SELECT * from work_attachment_type a LEFT JOIN attachment_type b on a.attachment_type_id = b.attachment_type_id WHERE a.works_id = '$work_id' ");
		return $query;
	}

	public function delete_work_attachment_type($work_id){
		$query = $this->db->query("DELETE from work_attachment_type  WHERE works_id = '$work_id' ");
		return $query;
	}

	public function fetch_joinery(){
		$query = $this->db->query("SELECT * FROM `joinery`");
		return $query;
	}

	public function display_all_works_joinery($works_id){
		$alphabet = "";
		$loop_no = 1;
		$last_letter = "";
		$joinery_q = $this->db->query("SELECT * from work_joinery where works_id = '$works_id' order by work_joinery_id");
		foreach ($joinery_q->result_array() as $row){
			$works_joinery_id = $row['work_joinery_id'];
			$distinct_character = $row['distinct_character'];
			if($distinct_character == ""){
				$work_row = 1;
				for ($column='A'; $column!='ZZ'; $column++){ 
	            	if($loop_no == $work_row){
	            		$this->db->query("UPDATE `work_joinery` set `distinct_character` = '$column' where work_joinery_id = '$works_joinery_id'");
	            		break;
	            	}
	            	$work_row++;
	        	} 
			}
			
			$loop_no++;
		}

		$query = $this->db->query("SELECT *, 
					a.work_estimate as j_estimate,
					a.total_work_quote as j_quote,
					a.price as j_price,
					a.company_client_id as j_contrator,
					a.work_markup as j_markup 
				FROM work_joinery a left join works b on a.works_id = b.works_id 
				left join joinery c on a.joinery_id = c.joinery_id 
				left join company_details d on a.company_client_id = d.company_id 
				WHERE a.works_id = '$works_id'");
		return $query;
	}

	public function display_selected_works_joinery($works_joinery_id){
		$query = $this->db->query("SELECT *, 
					a.company_client_id as work_joinery_contractor_id,
					a.price as wj_price,
					a.work_reply_date as wj_work_reply_date,
					a.work_markup as joinery_markup,
					a.work_estimate as wj_work_estimate,
					a.is_deliver_office as wj_is_deliver_office,
					a.work_cpo_date as wj_work_cpo_date,
					a.goods_delivery_by_date as wj_goods_deliver_by_date,
					a.note_id as wj_note_id
				FROM work_joinery a 
				left join works b on a.works_id = b.works_id 
				left join joinery c on a.joinery_id = c.joinery_id 
				left join company_details d on a.company_client_id = d.company_id 
				left join notes f on a.note_id = f.notes_id
				left join considerations g on a.work_joinery_id = g.work_joinery_id
				WHERE a.work_joinery_id = '$works_joinery_id'");
		return $query;
	}

	public function update_works_joinery_estimate($work_joinery_id,$work_estimate,$total_work_quote){
		$this->db->query("UPDATE `work_joinery` set  `work_estimate` = '$work_estimate', `total_work_quote` = '$total_work_quote' where `work_joinery_id` = '$work_joinery_id'");
	}

	public function remove_all_works_joinery($work_id){
		$this->db->query("DELETE FROM `work_joinery` where `works_id` = '$work_id'");
	}

	public function update_selected_joinery_name($work_joinery_id, $joinery_id){
		$this->db->query("UPDATE `work_joinery` SET  `joinery_id` = '$joinery_id' where `work_joinery_id` = '$work_joinery_id'");
	}

	public function set_all_joinery_subitem_contractor($work_id,$contractor_id){
		$query = $this->db->query("SELECT * from work_contractors where works_contrator_id = '$contractor_id'");
		foreach ($query->result_array() as $row){
			$company_id = $row['company_id'];			
		}
		$this->db->query("UPDATE `work_joinery` SET  `company_client_id` = '$company_id' where `works_id` = '$work_id'");

		$query = $this->db->query("SELECT * from work_joinery where works_id = '$work_id'");
		foreach ($query->result_array() as $row){
			$work_joinery_id = $row['work_joinery_id'];
			$joinery_work_id = $work_id."-".$work_joinery_id;
			$this->db->query("UPDATE `work_contractors` SET  `is_selected` = 0 where `works_id` = '$joinery_work_id'");			
		}
	}

	public function update_work_joinery_item_price($work_joinery_id,$unit_price,$price,$qty){
		$this->db->query("UPDATE `work_joinery` SET  `qty` = '$qty', `unit_price` = '$unit_price', `price` = '$price' where `work_joinery_id` = '$work_joinery_id'");
	}

	public function update_work_joinery_item_estimate($work_joinery_id,$work_id,$work_joinery_unit_estimated,$t_estimate,$quoted){
		$this->db->query("UPDATE `work_joinery` SET  `work_estimate` = '$t_estimate', `unit_estimate` = '$work_joinery_unit_estimated', `total_work_quote` = '$quoted' where `work_joinery_id` = '$work_joinery_id'");
	}

	public function update_work_joinery($work_id,$t_price,$t_estimate,$t_quote){
		if($t_estimate == 0){
			$this->db->query("UPDATE `works` SET  `price` = '$t_price' where `works_id` = '$work_id'");
		}else{
			$this->db->query("UPDATE `works` SET  `work_estimate` = '$t_estimate', `price` = '$t_price', `total_work_quote` = '$t_quote' where `works_id` = '$work_id'");
		}
	}

	public function set_joinery_contractor($work_joinery_id,$work_id,$comp_id,$t_price,$joinery_work_id){
		$query = $this->db->query("select * from work_contractors where works_contrator_id = '$comp_id'");
		foreach ($query->result_array() as $row){
			$company_id = $row['company_id'];			
		}
		$this->db->query("UPDATE `work_joinery` SET  `company_client_id` = '$company_id' where `work_joinery_id` = '$work_joinery_id'");
		$this->db->query("UPDATE works set price = '$t_price', company_client_id = 0, work_cpo_date = '' where works_id = '$work_id'");
		$this->db->query("UPDATE work_contractors set is_selected = 0 where works_id = '$work_id'");
		$this->db->query("UPDATE work_contractors set is_selected = 0 where works_id = '$joinery_work_id'");
		$this->db->query("UPDATE work_contractors set is_selected = 1 where works_contrator_id = '$comp_id'");
	}

	public function unset_joinery_contractor($work_joinery_id,$work_id){
		$joinery_works_id = $work_id."-".$work_joinery_id;
		$this->db->query("UPDATE `work_joinery` SET  `company_client_id` = 0, `unit_price` = 0, `price` = 0  where `work_joinery_id` = '$work_joinery_id'");
		$this->db->query("UPDATE `work_contractors` SET  `is_selected` = 0  where `works_id` = '$joinery_works_id'");
	}

	public function delete_selected_joinery_subitem($work_joinery_id){
		$this->db->query("DELETE from `work_joinery` where `work_joinery_id` = '$work_joinery_id'");
	}

	public function fetch_contract_notes($proj_id){
		$query = $this->db->query("select * from project_contract_notes where project_id = '$proj_id'");
		return $query;
	}

	public function insert_contract_notes($proj_id,$contract_date,$plans_elv_draw,$sched_work_quotation,$condition_quote_contract){
		$this->db->query("insert into project_contract_notes(project_id,ped_note,sowiiq,coqac,contract_date)values('$proj_id','$plans_elv_draw','$sched_work_quotation','$condition_quote_contract','$contract_date')");
	}

	public function update_contract_notes($proj_id,$contract_date,$plans_elv_draw,$sched_work_quotation,$condition_quote_contract){
		$this->db->query("update project_contract_notes set ped_note = '$plans_elv_draw',sowiiq = '$sched_work_quotation', coqac = '$condition_quote_contract', contract_date = '$contract_date' where project_id = '$proj_id'");
	}

	public function view_project_contractors($proj_id){
		$query = $this->db->query("SELECT *,
										if(works.is_variation = 1,variation.variation_name,'Works' ) as work_type, 
										if(work_contractors.is_pending = 0 , company_details.company_name, company_details_temp.company_name) AS comp_name from work_contractors 
									left join works on works.works_id = work_contractors.works_id
									left join job_sub_category  on job_sub_category.job_sub_cat_id = works.work_con_sup_id
									left join supplier_cat on works.work_con_sup_id = supplier_cat.supplier_cat_id
									left join company_details on work_contractors.company_id = company_details.company_id
									LEFT JOIN company_details_temp ON company_details_temp.company_details_temp_id = work_contractors.company_id
									left join notes on works.note_id = notes.notes_id
									left join variation on variation.variation_id = works.variation_id
								where works.project_id = '$proj_id'
									and works.is_active = 1
								ORDER BY comp_name");
		return $query;
	}

	public function view_project_contractors_cpo($proj_id){
		$query = $this->db->query("SELECT *,if(works.is_variation = 1,variation.variation_name,'Works' ) as work_type from work_contractors
									left join works on works.works_id = work_contractors.works_id
									left join job_sub_category  on job_sub_category.job_sub_cat_id = works.work_con_sup_id
									left join supplier_cat on works.work_con_sup_id = supplier_cat.supplier_cat_id
									left join company_details on work_contractors.company_id = company_details.company_id
									left join notes on works.note_id = notes.notes_id
									left join variation on variation.variation_id = works.variation_id
								where works.project_id = '$proj_id'
									and works.is_active = 1 and work_contractors.is_selected = 1
								order by company_name");
		return $query;
	}

	public function update_work_comments($works_id,$work_comments){
		$this->db->query("UPDATE `works` SET  `works_comments` = '$work_comments' where `works_id` = '$works_id'");
	}

	public function update_work_contractor_notes($works_contrator_id,$contractor_notes){
		$this->db->query("UPDATE `work_contractors` SET  `contractor_notes` = '$contractor_notes' where `works_contrator_id` = '$works_contrator_id'");
	}

	public function select_particular_project($id){
		$query = $this->db->query("SELECT a.*, b.*, c.*, b.address_id as site_add, d.user_first_name, d.user_last_name, f.user_first_name AS lh_fname, f.user_last_name AS lh_sname, g.role_types
									FROM safe_site_work_observation a 
										LEFT JOIN project b ON a.project_id = b.project_id
										LEFT JOIN company_details c ON c.company_id = b.client_id 
										LEFT JOIN users d ON d.user_id = a.user_id
										LEFT JOIN project_schedule e ON e.project_id = b.project_id
										LEFT JOIN (SELECT * FROM users) f ON f.user_id = e.leading_hand_id
										LEFT JOIN role g ON g.role_id = d.user_role_id
									WHERE a.project_id = '$id'
								");
		return $query;
	}

	//============== Temporary Company Function ==================
	public function insert_work_pending_company($works_id,$company_id){
		$this->db->query("DELETE FROM `work_contractors` WHERE `works_id` = '$works_id' AND `company_id` = '$company_id' AND `is_pending` = 1");
		$date_added = date('Y-m-d');
		$this->db->query("INSERT INTO `work_contractors` (`works_id`, `date_added`, `company_id`, `contact_person_id`, `is_pending`) 
				VALUES ('$works_id', '$date_added', '$company_id', 0, 1)");
	}
	public function update_temporary_cont_sup($work_contractor_id,$temp_comp_id){
		$this->db->query("UPDATE `work_contractors` SET `company_id` = '$temp_comp_id' WHERE `works_contrator_id` = '$work_contractor_id'");
	}
	public function remove_temporary_cont_sup($work_contractor_id){
		$this->db->query("DELETE FROM `work_contractors` WHERE `works_contrator_id` = '$work_contractor_id'");
	}
//============== Temporary Company Function ==================
}