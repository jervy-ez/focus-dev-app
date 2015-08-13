<?php
$this->load->model('works_m');
foreach ($works_t->result_array() as $row){
	$cont_type = $row['contractor_type'];
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
						<?php 
							$joinery_q = $this->works_m->display_all_works_joinery($row['works_id']); 
							if($joinery_q->num_rows > 0):
						?>
								<span class="badge alert-success pointer pull-right" title = "View Sub Items" onclick = "show_variation_joinery('<?php echo $proj_id ?>')"><i class="fa fa-arrow-circle-down"></i></span>
						<?php endif; ?>	
					<?php endif; ?>
				<?php endif; ?>	
			<?php endif; ?>			
		</td>
		<td width="50%" class="work-set-comp-<?php echo $row['works_id']; ?>">
			<?php if($row['company_name'] != ''): ?>
				<a href="#" class = "work_contractor_click" onClick = "selvar_work(<?php echo $row['works_id'] ?>)"><?php echo $row['company_name']; ?></a>
				<span class="unset_comp_badge_<?php echo $row['works_id']; ?> badge alert-danger pointer pull-right" title = "Unselect Company" onClick = "unset_var_work_company(<?php echo $row['works_id'] ?>)"><i class="fa fa-times"></i></span>
				<span class="add_comp_badge_<?php echo $row['works_id']; ?> badge alert-success set-comp pointer work_contractor_click" title = "Add Company" onClick = "sel_var_work_badge(<?php echo $row['works_id'] ?>)" style = "display: none">Set <i class="fa fa-user-plus"></i></span>
				<!-- <div><span id="<?php //echo $row['works_id'] ?>" class="badge pull-right alert-danger remove-row pointer" title = "Delete"><i class="fa fa-trash"></i></span></div>-->
			<?php else: ?>
				<a href="#" class = "work_contractor_click" onClick = "selvar_work(<?php echo $row['works_id'] ?>)"></a>
				<span class="unset_comp_badge_<?php echo $row['works_id']; ?> badge alert-danger pointer pull-right" title = "Unselect Company" onClick = "unset_var_work_company(<?php echo $row['works_id'] ?>)" style = "display: none"><i class="fa fa-times"></i></span>
				<span class=" add_comp_badge_<?php echo $row['works_id']; ?> badge alert-success set-comp pointer work_contractor_click" title = "Add Company" onClick = "sel_var_work_badge(<?php echo $row['works_id'] ?>)">Set <i class="fa fa-user-plus"></i></span>
			<?php endif; ?>
		</td>
		<td width="10%" align = right>
			<!--<a href="#" class="work-set-price-<?php //echo $row['works_id']; ?>" onClick = "edit_price(<?php //echo $row['works_id'] ?>)"><?php //echo $row['price'] ?></a> -->
			<input type="text" onclick = "clk_work_price(<?php echo $row['works_id']; ?>)" onblur = "update_price(<?php echo $row['works_id']; ?>)" class="work-set-price-<?php echo $row['works_id']; ?> input_text text-right number_format price" value = "<?php echo number_format($row['price']) ?>" style = "width: 100%" disabled>
		</td>
		<td width="10%"align = right>
			<input type="text" onclick = "this.select()" onfocus = "focus_works_estimate(<?php echo $row['works_id']; ?>)" onkeyup = "ku_update_estimate(<?php echo $row['works_id']; ?>)" onblur = "update_estimate(<?php echo $row['works_id']; ?>)" class="work-set-estimate-<?php echo $row['works_id']; ?> input_text text-right number_format estimate" value = "<?php echo number_format($row['work_estimate']) ?>" style = "width: 100%" <?php if($job_date !== ""){ ?> disabled <?php } ?>>
		</td>
		<td width="10%" align = right class = "work-set-quote-<?php echo $row['works_id']; ?>"><span style = "width: 100%; display: block;"><?php echo number_format($row['total_work_quote']) ?></span></td>
	</tr>
	<?php 
		if(isset($_GET['show'])== 1){
			if($row['work_con_sup_id'] == 53){
	?>
	<input type="hidden" id = "var_show" value = "<?php echo $_GET['show'] ?>">
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
