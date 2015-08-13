<?php
if($works_joinery_t->num_rows == 0){
	echo '<p class= "pull-left" style = "font-size: 11px">No Sub Items</p>';
}else{
foreach ($works_joinery_t->result_array() as $row){
	$cont_type = $row['contractor_type'];
	$company_client_id = $row['company_client_id'];
?>
	
	<input type="hidden" id = "work_joinery_contractor_id" value = "<?php echo $company_client_id ?>">
	<?php if($is_variation == 1 ){ ?>
	<input type="hidden" id = "var_works_id" value = "<?php echo $row['works_id']; ?>">
	<?php } ?>
	<input type="hidden" id = "works_id" value = "<?php echo $row['works_id']; ?>">
	<input type="hidden" id = "joinery_markup_<?php echo $row['work_joinery_id'] ?>" value = "<?php echo $row['j_markup']; ?>">
	<tr id="row-work-joinery-<?php echo $row['work_joinery_id']; ?>">
		<td width ="5%"><input type="text" onclick = "this.select()" onkeyup = "ku_work_joinery_qty(<?php echo $row['work_joinery_id']; ?>)" <?php if($is_variation == 0 ){ ?>onblur = "update_joinery_qty(<?php echo $row['work_joinery_id']; ?>)"<?php }else{ ?>onblur = "update_var_joinery_qty(<?php echo $row['work_joinery_id']; ?>)"<?php } ?> class="work_joinery_set_qty_<?php echo $row['work_joinery_id']; ?> input_text text-right number_format estimate" value = "<?php echo $row['qty']; ?>" style = "width: 100%" title = "Joinery Qty" <?php if($job_date !== ""){ ?>disabled<?php } ?>></td>
		<td width="15%" class="work-desc">
				<a href="<?php echo base_url() ?>works/update_work_details/<?php echo $projid ?>/<?php echo $row['works_id'] ?>/<?php echo $row['work_joinery_id'] ?>"><?php echo $row['joinery_name'] ?></a>		
		</td>
		<td width="30%" class="work_joinery_set_comp_<?php echo $row['work_joinery_id']; ?>">
			<?php if($row['company_name'] != ''): ?>
				<a href="#" <?php if($is_variation == 0 ){ ?>onClick = "selwork_joinery(<?php echo $row['work_joinery_id'] ?>)"<?php }else{ ?>onClick = "sel_var_work_joinery(<?php echo $row['work_joinery_id'] ?>)"<?php } ?>><?php echo $row['company_name'] ?></a>
				<?php if($company_client_id == "" || $company_client_id == 0): ?><span class="unset_joinery_comp_badge_<?php echo $row['work_joinery_id']; ?> badge alert-danger pointer pull-right" title = "Unselect Company" onClick = "unset_work_joinery_company(<?php echo $row['work_joinery_id'] ?>)"><i class="fa fa-times"></i></span><?php endif; ?>
				<span class="add_comp_joinery_badge_<?php echo $row['work_joinery_id']; ?> badge alert-success set-comp pointer" title = "Add Company" <?php if($is_variation == 0 ){ ?>onClick = "selwork_joinery(<?php echo $row['work_joinery_id'] ?>)"<?php }else{ ?>onClick = "sel_var_work_joinery(<?php echo $row['work_joinery_id'] ?>)"<?php } ?> style = "display: none">Set <i class="fa fa-user-plus"></i></span>
				<!-- <div><span id="<?php //echo $row['works_id'] ?>" class="badge pull-right alert-danger remove-row pointer" title = "Delete"><i class="fa fa-trash"></i></span></div>-->
			<?php else: ?>
				<span class="add_comp_joinery_badge_<?php echo $row['work_joinery_id']; ?> badge alert-success set-comp pointer" title = "Add Company"  <?php if($is_variation == 0 ){ ?>onClick = "selwork_joinery(<?php echo $row['work_joinery_id'] ?>)"<?php }else{ ?>onClick = "sel_var_work_joinery(<?php echo $row['work_joinery_id'] ?>)" <?php } ?>>Set <i class="fa fa-user-plus"></i></span>
				<a href="#" <?php if($is_variation == 0 ){ ?>onClick = "selwork_joinery(<?php echo $row['work_joinery_id'] ?>)"<?php }else{ ?>onClick = "sel_var_work_joinery(<?php echo $row['work_joinery_id'] ?>)"<?php } ?>></a>
				<?php if($company_client_id == "" || $company_client_id == 0): ?><span class="unset_joinery_comp_badge_<?php echo $row['work_joinery_id']; ?> badge alert-danger pointer pull-right" title = "Unselect Company" onClick = "unset_work_joinery_company(<?php echo $row['work_joinery_id'] ?>)" style = "display: none"><i class="fa fa-times"></i></span><?php endif; ?>
			<?php endif; ?>
		</td>
		<td width="10%" align = right>
			<div class = "tooltip-test" title = "Unit Price"><input type="text" onclick = "this.select()" onkeyup = "ku_work_joinery_unit_price(<?php echo $row['work_joinery_id']; ?>)" onblur = "update_joinery_unit_price(<?php echo $row['work_joinery_id']; ?>)" class="work_joinery_set_unit_price_<?php echo $row['work_joinery_id']; ?> input_text text-right number_format price " value = "<?php echo number_format($row['unit_price']) ?>" style = "width: 100%" <?php if($company_client_id == "" || $company_client_id == 0){ ?>disabled<?php } ?>></div>
			<!--<a href="#" class="work-set-price-<?php //echo $row['works_id']; ?>" onClick = "edit_price(<?php //echo $row['works_id'] ?>)"><?php //echo $row['price'] ?></a> -->
			
		</td>
		<td width="10%" align = right>
			<!--<a href="#" class="work-set-price-<?php //echo $row['works_id']; ?>" onClick = "edit_price(<?php //echo $row['works_id'] ?>)"><?php //echo $row['price'] ?></a> -->
			<input type="text" onclick = "this.select()" onkeyup = "ku_work_joinery_unit_estimate(<?php echo $row['work_joinery_id']; ?>)"  <?php if($is_variation == 0 ){ ?>onblur = "update_joinery_unit_estimate(<?php echo $row['work_joinery_id']; ?>)"<?php }else{ ?>onblur = "update_var_joinery_unit_estimate(<?php echo $row['work_joinery_id']; ?>)"<?php } ?> class="work_joinery_set_unit_estimate_<?php echo $row['work_joinery_id']; ?> input_text text-right number_format price tooltip-test" value = "<?php echo number_format($row['unit_estimate']) ?>" style = "width: 100%" title = "Unit Estimate" <?php if($job_date !== ""){ ?>disabled<?php } ?>>
		</td>
		<td width="10%" align = right>
			<!--<a href="#" class="work-set-price-<?php //echo $row['works_id']; ?>" onClick = "edit_price(<?php //echo $row['works_id'] ?>)"><?php //echo $row['price'] ?></a> -->
			<input type="text" onclick = "clk_work_joinery_total_price(<?php echo $row['work_joinery_id']; ?>)" onblur = "update_joinery_total_price(<?php echo $row['work_joinery_id']; ?>)" class="work_joinery_set_total_price_<?php echo $row['work_joinery_id']; ?> input_text text-right number_format price tooltip-test" value = "<?php echo number_format($row['j_price']) ?>" title = "Total Price" style = "width: 100%" disabled>
		</td>
		<td width="10%"align = right>
			<input type="text" onclick = "clk_work_joinery_total_estimate(<?php echo $row['work_joinery_id']; ?>)" onblur = "update_joinery_estimate(<?php echo $row['work_joinery_id']; ?>)" class="work-joinery-set-estimate-<?php echo $row['work_joinery_id']; ?> input_text text-right number_format estimate tooltip-test" value = "<?php echo number_format($row['j_estimate']) ?>" title = "Total Estimate" style = "width: 100%" disabled>
		</td>
		<td width="10%" align = right class = "work-joinery-set-quote-<?php echo $row['work_joinery_id']; ?>"><?php echo number_format($row['j_quote']) ?></td>
	</tr>
<?php
}
}
?>
