<table class="table table-striped table-bordered" style = "font-size: 11px">
	<thead>
		<tr>
			<th style = "width: 10px"></th>
			<th>User Names</th>
		</tr>
	</thead>
	<tbody>
<?php
foreach ($log_users_q->result_array() as $row){
?>
	<tr>
		<td style = "width: 10px"><input type="radio" name = "sel_user_id" onClick = "select_user_id('<?php echo $row['user_id']; ?>')"></td>
		<td><?php echo $row['user_first_name']." ".$row['user_last_name'] ?></td>
	</tr>
<?php
}
?>
	</tbody>
</table>