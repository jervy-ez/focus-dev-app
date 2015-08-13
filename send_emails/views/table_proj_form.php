<?php
foreach ($proj_form_t->result_array() as $row){
?>
	<tr>
		<td style = "padding-left: 20px">
			<input type="checkbox" name = "chk_proj_forms" value = "<?php echo $row['projects_forms_id'] ?>">		
		</td>
		<td>
			<a href="#" onClick = "sel_proj_form(<?php echo $row['projects_forms_id'] ?>)"><?php echo $row['description'] ?></a>
		</td>
		<td><?php echo $row['date_modified'] ?></td>
		<td><?php echo $row['status'] ?></td>
	</tr>
<?php
}
?>
