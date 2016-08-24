<?php
foreach ($works_attachment_t->result_array() as $row){
	$transfer = $proj_id."|".$work_id.'|'.$row['work_attachments_url'];
	$attach_type = $row['work_attachments_type'];
?>
	<tr id = "work_attachemnt_<?php echo $row['work_attachments_id'] ?>">
		<td><a href="#" onclick = "view_file('<?php echo $transfer ?>')"><?php echo $row['work_attachments_url'] ?></a></td>
		<td onclick = "get_attach_type('<?php echo $attach_type ?>')">
			<select name="attachment_type" onchange = "change_attach_type(<?php echo $row['work_attachments_id'] ?>)" id="attachment_type_<?php echo $row['work_attachments_id'] ?>" class = "input-sm pull-right attachment_type input_text">
				<option value="Plans" <?php if($row['work_attachments_type'] == "Plans" ){ ?>selected<?php }?> >Plans</option>
				<option value="Elevations" <?php if($row['work_attachments_type'] == "Elevations" ){ ?>selected<?php }?> >Elevations</option>
				<option value="Works Schedule" <?php if($row['work_attachments_type'] == "Works Schedule" ){ ?>selected<?php }?> >Works Schedule</option>
			</select></td>
		<td><?php echo $row['work_attachements_date'] ?></td>
	</tr>
<?php
}
?>
<?php //echo base_url().'uploads/project_attachments/'.$proj_id.'/'.$work_id.'/'.$row['work_attachments_url'] ?>