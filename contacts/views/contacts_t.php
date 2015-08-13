<?php
foreach ($cont_c->result_array() as $row){
	//echo '<tr><td><a href="'.base_url().'company/view/'.$row['company_id'].'" >'.$row['company_name'].'</a></td><td>'.ucwords(strtolower($row['suburb'])).' '.$row['shortname'].'</td><td>'.$row['area_code'].' '.$row['office_number'].'</td><td>'. strtolower($row['general_email']).'</td></tr>';
?>
	<tr>
		<td>
			<a href="" onclick = "contact_details(<?php echo $row['contact_person_id'] ?>)" data-toggle="modal" data-target="#contact_information" ><?php echo $row['first_name']." ".$row['last_name'] ?></a>
		</td>
		<td><?php echo $row['company_name']?></td>
		<td>
			<?php
				if($row['company_type_id'] == 2):
					echo $row['job_category'];
				else:
					echo $row['supplier_cat_name'];
				endif; 
			?>
		</td>
		<td><?php echo ucwords(strtolower($row['suburb']))?></td>
		<td><?php echo $row['shortname']; ?></td>
		<td>
			<?php if($row['office_number'] != ''): ?>
			<?php echo $row['area_code'].' '.$row['office_number'] ?>
			<?php endif; ?>
		</td>
		<td><a href="mailto:<?php echo strtolower($row['general_email']) ?>" ><?php echo strtolower($row['general_email']) ?></a></td>
	</tr>
<?php
}


?>