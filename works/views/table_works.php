<?php
$this->load->model('works_m');
$this->load->model('company_m');
foreach ($works_t->result_array() as $row){
	$cont_type = $row['contractor_type'];
	$work_con_sup_id = $row['work_con_sup_id'];
	$company_client_id = $row['company_client_id'];
	$company_q = $this->company_m->fetch_all_company($company_client_id);
	foreach ($company_q->result_array() as $comp_row){
		if($comp_row['public_liability_expiration'] !== ""){
			$ple_raw_data = $comp_row['public_liability_expiration'];
			$ple_arr =  explode('/',$ple_raw_data);
			$ple_day = $ple_arr[0];
			$ple_month = $ple_arr[1];
			$ple_year = $ple_arr[2];
			$ple_date = $ple_year.'-'.$ple_month.'-'.$ple_day;
		}

		if($comp_row['workers_compensation_expiration'] !== ""){
			$wce_raw_data = $comp_row['workers_compensation_expiration'];
			$wce_arr =  explode('/',$wce_raw_data);
			$wce_day = $wce_arr[0];
			$wce_month = $wce_arr[1];
			$wce_year = $wce_arr[2];
			$wce_date = $wce_year.'-'.$wce_month.'-'.$wce_day;
		}

		if($comp_row['income_protection_expiration'] !== ""){
			$ipe_raw_data = $comp_row['income_protection_expiration'];
			$ipe_arr =  explode('/',$ipe_raw_data);
			$ipe_day = $ipe_arr[0];
			$ipe_month = $ipe_arr[1];
			$ipe_year = $ipe_arr[2];
			$ipe_date = $ipe_year.'-'.$ipe_month.'-'.$ipe_day;
		}
		$today = date('Y-m-d');
		
		$complete = 0;
		$incomplete = 0;
		
		if($comp_row['company_type_id'] == '2'){
			if($comp_row['has_insurance_public_liability'] == 1){
				if($comp_row['public_liability_expiration'] !== ""){
					if($ple_date <= $today){
						$incomplete = 1;
					}else{
						if($comp_row['has_insurance_workers_compensation'] == 1){
							if($comp_row['workers_compensation_expiration'] !== ""){
								if($wce_date <= $today){
									$incomplete = 1;
								}else{
									$complete = 1;
								}
							}else{
								$incomplete = 1;
							}
						}else{
							if($comp_row['has_insurance_income_protection'] == 1){
								if($comp_row['income_protection_expiration'] !== ""){
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
	}
	$font_color = "";
	if($row['company_type_id'] == '2'){
		if($complete == 1){
			$font_color = "Blue";
		}else{
			if($incomplete == 1){
				$font_color = "Red";
			}
		}
	}

?>
	<input type="hidden" class = "row_works_mark_up_<?php echo $row['works_id']; ?>" value = "<?php echo $row['work_markup']; ?>">
	<tr id="row-work-<?php echo $row['works_id']; ?>" class = "work_list">
		<td width="20%" class="work-desc">
			<!--<span id="<?php //echo $row['works_id'] ?>" class="badge pull-right alert-danger remove-row pointer" title = "Delete"><i class="fa fa-trash"></i></span> -->
			<?php if($row['contractor_type'] == "3"): ?>
				<a href="<?php echo base_url() ?>works/update_work_details/<?php echo $projid ?>/<?php echo $row['works_id'] ?>"><?php echo $row['supplier_cat_name'] ?></a>
			<?php else: ?>
				<?php if($row['work_con_sup_id'] == "82"): ?>
					<a href="<?php echo base_url() ?>works/update_work_details/<?php echo $projid ?>/<?php echo $row['works_id'] ?>"><?php echo $row['other_work_desc'] ?></a>
				<?php else: ?>
					<a href="<?php echo base_url() ?>works/update_work_details/<?php echo $projid ?>/<?php echo $row['works_id'] ?>"><?php echo $row['job_sub_cat'] ?></a>
					<?php if($row['work_con_sup_id'] == "53"): ?>
							<input type="hidden" id = "joinery_works_id" value = "<?php echo $row['works_id'] ?>">
						<?php 
							$joinery_q = $this->works_m->display_all_works_joinery($row['works_id']); 
							if($joinery_q->num_rows > 0){
								$have_sub_item = 1;
							}else{
								$have_sub_item = 0;
							}

							$joinery_q = $this->works_m->display_all_works_joinery($row['works_id']); 
							if($joinery_q->num_rows > 0):
						?>
							<span class="badge alert-success pointer pull-right" title = "View Sub Items" onclick = "show_joinery('<?php echo $proj_id ?>')"><i class="fa fa-arrow-circle-down"></i></span>
						<?php endif; ?>	
					<?php endif; ?>
				<?php endif; ?>	
			<?php endif; ?>			
		</td>
		<td width="50%" class="work-set-comp-<?php echo $row['works_id']; ?>">
			<?php if($row['company_name'] != ''): ?>
				<a href="#" class = "work_contractor_click" onClick = "selwork(<?php echo $row['works_id'] ?>)" style = "color: <?php echo $font_color ?>"><?php echo $row['company_name']; ?></a>
				<?php 	if($job_date == ""): ?>
					<?php 	if($row['is_reconciled'] == '0'): ?>
				<span class="unset_comp_badge_<?php echo $row['works_id']; ?> badge alert-danger pointer pull-right" title = "Unselect Company" onClick = "unset_work_company(<?php echo $row['works_id'] ?>)"><i class="fa fa-times"></i></span>
					<?php endif; ?>
				<?php endif; ?>
				<span class="add_comp_badge_<?php echo $row['works_id']; ?> badge alert-success set-comp pointer work_contractor_click" title = "Add Company" onClick = "selwork_badge(<?php echo $row['works_id'] ?>)" style = "display: none">Set <i class="fa fa-user-plus"></i></span>
				<!-- <div><span id="<?php //echo $row['works_id'] ?>" class="badge pull-right alert-danger remove-row pointer" title = "Delete"><i class="fa fa-trash"></i></span></div>-->
			<?php else: ?>
				<a href="#" class = "work_contractor_click" onClick = "selwork(<?php echo $row['works_id'] ?>)" style = "color: <?php echo $font_color ?>"></a>
				<?php if($job_date == ""):	?>
					<?php 	if($row['is_reconciled'] == '0'): ?>
				<span class="unset_comp_badge_<?php echo $row['works_id']; ?> badge alert-danger pointer pull-right" title = "Unselect Company" onClick = "unset_work_company(<?php echo $row['works_id'] ?>)" style = "display: none"><i class="fa fa-times"></i></span>
					<?php endif; ?>
				<?php endif; ?>
				<span class=" add_comp_badge_<?php echo $row['works_id']; ?> badge alert-success set-comp pointer work_contractor_click" title = "Add Company" onClick = "selwork_badge(<?php echo $row['works_id'] ?>)">Set <i class="fa fa-user-plus"></i></span>
			<?php endif; ?>
		</td>
		<td width="10%" align = right>

			<!--<a href="#" class="work-set-price-<?php //echo $row['works_id']; ?>" onClick = "edit_price(<?php //echo $row['works_id'] ?>)"><?php //echo $row['price'] ?></a> -->
			<input type="text" onclick = "clk_work_price(<?php echo $row['works_id']; ?>)" onblur = "update_price(<?php echo $row['works_id']; ?>)" class="work-set-price-<?php echo $row['works_id']; ?> input_text text-right number_format price" value = "<?php echo number_format($row['price']) ?>" style = "width: 100%" disabled>
		</td>
		<td width="10%"align = right>
			<input type="text" onclick = "this.select()" onfocus = "focus_works_estimate(<?php echo $row['works_id']; ?>)" onkeyup = "ku_update_estimate(<?php echo $row['works_id']; ?>)" onblur = "update_estimate(<?php echo $row['works_id']; ?>)" class="work-set-estimate-<?php echo $row['works_id']; ?> input_text text-right number_format estimate" value = "<?php echo number_format($row['work_estimate']) ?>" style = "width: 100%" <?php if($row['company_name'] == ''){ if($row['work_con_sup_id'] == "53"){ if($have_sub_item == 1) { ?> disabled <?php } } }else{ if($job_date !== ""){ ?> disabled <?php } }?>>
		</td>
		<td width="10%" align = right class = "work-set-quote-<?php echo $row['works_id']; ?>"><span style = "width: 100%; display: block;"><?php echo number_format($row['total_work_quote']) ?></span></td>
	</tr>
	<?php 
		if(isset($_GET['show'])== 1){
			if($row['work_con_sup_id'] == 53){
	?>
	<input type="hidden" id = "show" value = "<?php echo $_GET['show'] ?>">
	<tr>
		<td colspan = 5 align = right>
			<table class = "table table-condensed table-bordered m-bottom-0" style = "width: 98%">
				<tbody>
					<?php echo $this->works->display_all_works_joinery_query($row['works_id']); ?>
				</tbody>
			</table>
		</td>
	</tr>
	<?php
			}
		} 
	?>
	
<?php
}
?>
