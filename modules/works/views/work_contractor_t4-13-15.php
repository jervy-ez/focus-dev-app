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
			?>
			<tr <?php if($is_selected == 1){ ?>style = "color: #CC6666"<?php } ?>>
				<td>
					<input type="radio" id = "selcomp" name = "selcomp" value = "<?php echo $row['works_contrator_id'] ?>" <?php if($is_selected == 1){ ?>checked="checked"<?php } ?> onClick = "sel_work_con(<?php echo $row['works_contrator_id'] ?>)" >
					<a href="#" onClick = "selcontractor(<?php echo $row['works_contrator_id'] ?>)" data-toggle="modal" data-target="#addContractor_Modal"><?php echo $row['company_name'] ?></a>
				</td>
				<td align = right><?php echo $row['ex_gst'] ?></td>
				<td align = right><?php echo $row['inc_gst'] ?></td>
			</tr>
			<?php
			}
		}
		?>
	</tbody>
</table>