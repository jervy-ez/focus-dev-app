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
		$contact_name = "";
		$contact_no = "";
		if($works_contructors_t->num_rows == 0){
		?>
		<tr>
			<td colspan = 3>
		<?php
			echo "No Company Selected";
		?>
			</td>
		</tr>
		<?php
		}else{
			foreach ($works_contructors_t->result_array() as $row){
					//Insurance ================
					if($row['public_liability_expiration'] !== ""){
						$ple_raw_data = $row['public_liability_expiration'];
						$ple_arr =  explode('/',$ple_raw_data);
						$ple_day = $ple_arr[0];
						$ple_month = $ple_arr[1];
						$ple_year = $ple_arr[2];
						$ple_date = $ple_year.'-'.$ple_month.'-'.$ple_day;
					}

					if($row['workers_compensation_expiration'] !== ""){
						$wce_raw_data = $row['workers_compensation_expiration'];
						$wce_arr =  explode('/',$wce_raw_data);
						$wce_day = $wce_arr[0];
						$wce_month = $wce_arr[1];
						$wce_year = $wce_arr[2];
						$wce_date = $wce_year.'-'.$wce_month.'-'.$wce_day;
					}

					if($row['income_protection_expiration'] !== ""){
						$ipe_raw_data = $row['income_protection_expiration'];
						$ipe_arr =  explode('/',$ipe_raw_data);
						$ipe_day = $ipe_arr[0];
						$ipe_month = $ipe_arr[1];
						$ipe_year = $ipe_arr[2];
						$ipe_date = $ipe_year.'-'.$ipe_month.'-'.$ipe_day;
					}
					$today = date('Y-m-d');
					
					$complete = 0;
					$incomplete = 0;
					
					if($row['company_type_id'] == '2'){
						if($row['has_insurance_public_liability'] == 1){
							if($row['public_liability_expiration'] !== ""){
								if($ple_date <= $today){
									$incomplete = 1;
								}else{
									if($row['has_insurance_workers_compensation'] == 1){
										if($row['workers_compensation_expiration'] !== ""){
											if($wce_date <= $today){
												$incomplete = 1;
											}else{
												$complete = 1;
											}
										}else{
											$incomplete = 1;
										}
									}else{
										if($row['has_insurance_income_protection'] == 1){
											if($row['income_protection_expiration'] !== ""){
												if($ipe_date <= $today){
													$incomplete = 1;
												}else{
													$complete = 1;
												}
											}else{
												$incomplete = 1;
											}
										}else{
											$incomplete = 1;
										}
									}
								}
							}else{
								$incomplete = 1;
							}
							
						}else{
							$incomplete = 1;
						}
					}

					$font_color = "Black";
					if($row['company_type_id'] == '2'){
						if($complete == 1){
							$font_color = "Blue";
						}else{
							if($incomplete == 1){
								$font_color = "Red";
							}
						}
					}
					//Insurance ================
		
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
					<?php if($row['cs_is_pending'] == 0): ?>
						<?php if($job_date !== ""): ?>
							<?php if($is_variation == 0): ?>
								<?php if($is_reconciled == 0): ?>
									<input type="radio" id = "selcomp" name = "selcomp" value = "<?php echo $row['works_contrator_id'] ?>" <?php if($is_selected == 1){ ?>checked="checked"<?php } ?> onClick = "sel_work_con(<?php echo $row['works_contrator_id'] ?>)" >
								<?php endif; ?>
							<?php else:
								if($acceptance_date !== ""): ?>
								 	<input type="radio" id = "selcomp" name = "selcomp" value = "<?php echo $row['works_contrator_id'] ?>" <?php if($is_selected == 1){ ?>checked="checked"<?php } ?> onClick = "sel_work_con(<?php echo $row['works_contrator_id'] ?>)" >
								<?php endif; ?>
							<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>
					<?php if($row['cs_is_pending'] == 0): ?>
					<a href="#" title = "<?php echo $contact_name.'  ('.$contact_no.')' ?>" <?php if($is_variation == 0){ ?>onClick = "selcontractor(<?php echo $row['works_contrator_id'] ?>)" data-toggle="modal" data-target="#addContractor_Modal"<?php }else{ ?> onClick = "sel_var_contractor(<?php echo $row['works_contrator_id'] ?>)" data-toggle="modal" data-target="#add_var_Contractor_Modal"<?php }?> id = "work_contractor_name" style = "color: <?php echo $font_color ?>"><?php echo $row['comp_name'] ?></a>
					<?php else: ?>
					<a href="#" onClick = "select_contractor(<?php echo $row['works_contrator_id'] ?>,<?php echo $row['company_id'] ?>)" data-toggle="modal" data-target="#frm_pending_cont_sup_update" id = "work_contractor_name" style = "color: #FC8114"><?php echo $row['comp_name'] ?></a>
					<?php endif; ?>
					
					<?php if($row['contractor_notes'] !== ""): ?>
					<span class="badge alert-success pointer pull-right tooltip-enabled" title = "<?php echo $row['contractor_notes'] ?>"><i class="fa fa-pencil-square-o"></i></span>
					<?php endif; ?>
				</td>
				<td class="item-cont-<?php echo $row['works_contrator_id']; ?>-exprce" align = right>
					<input type="text" onclick = "this.select()" onkeyup = "ku_update_exgst(<?php echo $row['works_contrator_id']; ?>)" onblur = "update_exgst(<?php echo $row['works_id']; ?>)" class="work-set-exgst-<?php echo $row['works_contrator_id']; ?> input_text text-right number_format price" value = "<?php echo number_format($row['ex_gst'],2) ?>" style = "width: 100%" <?php if($is_reconciled == 1){ ?>disabled<?php } ?>>
				</td>
				<td class="item-cont-<?php echo $row['works_contrator_id']; ?>-incprice" align = right>
					<input type="text" onclick = "this.select()" onkeyup = "ku_update_incgst(<?php echo $row['works_contrator_id']; ?>)" onblur = "update_incgst(<?php echo $row['works_id']; ?>)" class="work-set-incgst-<?php echo $row['works_contrator_id']; ?> input_text text-right number_format price" value = "<?php echo number_format($row['inc_gst'],2) ?>" style = "width: 100%" <?php if($is_reconciled == 1){ ?>disabled<?php } ?>>
				</td>
			</tr>
			<?php
			}
		}
		?>
	</tbody>
</table>