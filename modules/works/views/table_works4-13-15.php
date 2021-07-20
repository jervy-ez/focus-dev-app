<?php
foreach ($works_t->result_array() as $row){
	$cont_type = $row['contractor_type'];
?>
	<tr>
		<td>
			<!--<span id="<?php //echo $row['works_id'] ?>" class="badge pull-right alert-danger remove-row pointer" title = "Delete"><i class="fa fa-trash"></i></span> -->
			<?php if($row['contractor_type'] == "3"): ?>
				<a href="<?php echo base_url() ?>works/update_work_details/<?php echo $projid ?>/<?php echo $row['works_id'] ?>"><?php echo $row['supplier_cat_name'] ?></a>
			<?php else: ?>
				<a href="<?php echo base_url() ?>works/update_work_details/<?php echo $projid ?>/<?php echo $row['works_id'] ?>"><?php echo $row['job_sub_cat'] ?></a>
			<?php endif; ?>			
		</td>
		<td>
			<?php if($row['company_name'] != ''): ?>
				<a href="#" onClick = "selwork(<?php echo $row['works_id'] ?>)"><?php echo $row['company_name'] ?></a> 
				<!-- <div><span id="<?php //echo $row['works_id'] ?>" class="badge pull-right alert-danger remove-row pointer" title = "Delete"><i class="fa fa-trash"></i></span></div>-->
			<?php else: ?>
				<div class=""><span class="badge alert-success set-comp pointer" title = "Add Company" onClick = "selwork(<?php echo $row['works_id'] ?>)">Set <i class="fa fa-user-plus"></i></span></div>
			<?php endif; ?>
		</td>
		<td align = right><?php echo $row['price'] ?></td>
		<td align = right><?php echo $row['work_estimate'] ?></td>
		<td align = right><?php echo $row['total_work_quote'] ?></td>
	</tr>
<?php
}
?>