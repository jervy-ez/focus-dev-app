<table class="table table-striped table-bordered">
<?php 
	foreach ($var_q->result_array() as $row){
?>
	<tr>
		<td width="30%">
			<a href="<?php echo base_url() ?>variation/view_variation_works/<?php echo $row['project_id'] ?>/<?php echo $row['variation_id'] ?>" onclick="clk_variation_name(<?php echo $row['variation_id'] ?>)"><?php echo $row['variation_name'] ?></a>
			<span class="unset_comp_badge_<?php echo $row['variation_id']; ?> badge alert-success pointer pull-right" title = "Edit Variation" onClick = "clk_edit_variation(<?php echo $row['variation_id'] ?>)"><i class="fa fa-pencil-square-o"></i></span>
		</td>
		<td width="10%"><?php echo $row['variation_date'] ?></td>
		<td width="10%" align=right><?php echo number_format($row['labor_cost']) ?></td>
		<td width="10%" align=right><?php echo number_format($row['variation_credit']) ?></td>
		<td width="10%" align=right><?php echo number_format($row['variation_cost']) ?></td>
		<td width="10%" align=right><?php if($row['acceptance_date'] == ""){ echo number_format($row['variation_total']); }else{ echo 0; } ?></td>
		<td width="10%" align=right><?php if($row['acceptance_date'] == ""){ echo 0; }else{ echo number_format($row['variation_total']); } ?></td>
	</tr>
<?php
	}
?>	
</table>