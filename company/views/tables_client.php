<?php
foreach ($com_c->result_array() as $row){
	//echo '<tr><td><a href="'.base_url().'company/view/'.$row['company_id'].'" >'.$row['company_name'].'</a></td><td>'.ucwords(strtolower($row['suburb'])).' '.$row['shortname'].'</td><td>'.$row['area_code'].' '.$row['office_number'].'</td><td>'. strtolower($row['general_email']).'</td></tr>';
	echo '<tr><td><a href="'.base_url().'company/view/'.$row['company_id'].'" >'.$row['company_name'].'</a></td><td>'.ucwords(strtolower($row['suburb'])).' '.$row['shortname'].'</td><td>';
	echo ($row['office_number'] != ''? $row['area_code'].' '.$row['office_number'] : '');
	echo '</td><td><a href="mailto:'.strtolower($row['general_email']).'?Subject=Inquiry" >'.strtolower($row['general_email']).'</a></td></tr>';
}


?>