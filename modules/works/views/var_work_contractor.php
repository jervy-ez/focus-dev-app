<table class="table table-striped table-bordered" style = "font-size: 11px">
	<thead>
		<tr>
			<th>Requested Quotes</th>
			<th>Price ex GST</th>
			<th>Inc GST</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if($works_contructors_t->num_rows == 0){
		?>
		<tr>
			<td colspan = 3>
		<?php
			echo "No Contructor Selected";
		?>
			</td>
		</tr>
		<?php
		}else{
			foreach ($works_contructors_t->result_array() as $row){
				$is_selected = $row['is_selected'];

					$work_cont_contact_person_id = $row['contact_person_id'];
					$work_cont_person_q = $this->company_m->fetch_all_contact_persons($work_cont_contact_person_id);
					foreach ($work_cont_person_q->result_array() as $work_cont_person_row){
					    $contact_name = $work_cont_person_row['first_name']." ".$work_cont_person_row['last_name'];
						   
						$contact_number_id = $work_cont_person_row['contact_number_id'];
						$phon_q = $this->company_m->fetch_phone($contact_number_id);
						foreach ($phon_q->result_array() as $phone_row){
							if($phone_row['office_number'] == ""):
								$contact_no = "";
							else:
								$contact_no = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
							endif;
							
						}

						// $contact_email_id = $work_cont_person_row['email_id'];
						// $email_q = $this->company_m->fetch_email($contact_email_id);
						// foreach ($email_q->result_array() as $email_row){
						// 	$data['cont_email'] = $email_row['general_email'];
						// }
					}
			?>
			<tr class="cont-<?php echo $row['works_contrator_id']; ?>" <?php if($is_selected == 1){ ?>style = "color: #CC6666"<?php } ?>>
				<td class="item-cont-<?php echo $row['works_contrator_id']; ?>-comp">
					<?php if($job_date !== ""): ?>
						<?php if($acceptance_date !== ""): ?>
							<input type="radio" id = "selcomp" name = "selcomp" value = "<?php echo $row['works_contrator_id'] ?>" <?php if($is_selected == 1){ ?>checked="checked"<?php } ?> onClick = "sel_var_work_con(<?php echo $row['works_contrator_id'] ?>)" >
						<?php endif; ?>
					<?php endif; ?>
					<a href="#" title = "<?php echo $contact_name.'  ('.$contact_no.')' ?>" <?php if($is_variation == 0){ ?>onClick = "selcontractor(<?php echo $row['works_contrator_id'] ?>)" data-toggle="modal" data-target="#addContractor_Modal"<?php }else{ ?> onClick = "sel_var_contractor(<?php echo $row['works_contrator_id'] ?>)" data-toggle="modal" data-target="#add_var_Contractor_Modal"<?php }?>><?php echo $row['company_name'] ?></a>
				</td>
				<td class="item-cont-<?php echo $row['works_contrator_id']; ?>-exprce" align = right>
					<input type="text" onclick = "this.select()" onkeyup = "ku_update_exgst(<?php echo $row['works_contrator_id']; ?>)" onblur = "update_exgst(<?php echo $row['works_contrator_id']; ?>)" class="work-set-exgst-<?php echo $row['works_contrator_id']; ?> input_text text-right number_format price" value = "<?php echo number_format($row['ex_gst'],2) ?>" style = "width: 100%">
				</td>
				<td class="item-cont-<?php echo $row['works_contrator_id']; ?>-incprice" align = right>
					<input type="text" onclick = "this.select()" onkeyup = "ku_update_incgst(<?php echo $row['works_contrator_id']; ?>)" onblur = "update_incgst(<?php echo $row['works_contrator_id']; ?>)" class="work-set-incgst-<?php echo $row['works_contrator_id']; ?> input_text text-right number_format price" value = "<?php echo number_format($row['inc_gst'],2) ?>" style = "width: 100%">
				</td>
			</tr>
			<?php
			}
		}
		?>
	</tbody>
</table>