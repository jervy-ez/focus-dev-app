<?php
foreach ($proj_attachment_t->result_array() as $row){
	//$transfer = $proj_id."|".$work_id.'|'.$row['project_attachments_url'];
	$attach_type = $row['attachment_type'];
?>
	<tr id = "work_attachemnt_<?php echo $row['project_attachments_id'] ?>">
		<td><a href="#" onclick = "view_file('<?php echo $row['project_attachments_id'] ?>')" data-toggle="modal" data-target="#show_attachment_modal"><?php echo $row['project_attachments_url'] ?></a></td>
		<td id = "proj_attachment_<?php echo $row['project_attachments_id'] ?>">
			<select style = "width: 100%" onclick = "clk_attach_type(<?php echo $row['project_attachments_id'] ?>)" onchange = "change_attach_type(<?php echo $row['attachment_type_id'] ?>)" id="sel_attachment_type_<?php echo $row['project_attachments_id'] ?>" class = "input-sm pull-right attachment_type input_text">
				<?php $this->attachments->view_attachment_type_list() ?>
			</select>
			<script type="text/javascript">$("select#sel_attachment_type_<?php echo $row['project_attachments_id'] ?>").val("<?php echo $row['attachment_type_id'] ?>");</script>
		</td>
		<td><?php echo $row['project_attachements_date'] ?></td>
		<td class = "text-center"><input type = "checkbox" onclick = "chk_select_attachment(<?php echo $row['project_attachments_id'] ?>)" id = "chck_isselected_<?php echo $row['project_attachments_id'] ?>" <?php echo ($row['is_selected']==1 ? 'checked' : '');?>></td>
	</tr>
<?php
}
?>
<?php //echo base_url().'uploads/project_attachments/'.$proj_id.'/'.$work_id.'/'.$row['work_attachments_url'] ?>
