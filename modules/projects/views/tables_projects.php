<?php

$this->load->module('invoice');

foreach ($proj_t->result_array() as $row){

	$status = '';

	if($row['job_date'] != '' ){
		$status = 'wip';
	}

	if($this->invoice->if_invoiced_all($row['project_id'])  && $this->invoice->if_has_invoice($row['project_id']) > 0 ){
		$status = 'invoiced';
	}

	if($row['is_paid'] == 1){
		$status = 'paid';
	}


	



	echo '<tr class="'.$status.'"><td><a href="'.base_url().'projects/view/'.$row['project_id'].'" >'.$row['project_id'].'</a></td><td>'.$row['project_name'].'</td><td>'.$row['company_name'].'</td><td>'.$row['job_category'].'</td><td>'.($row['job_date'] == '' ? 'Unset' : $row['job_date']).'</td>';


	if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 || $row['variation_total'] > 0.00 ){
		echo '<td>'.number_format($row['project_total']+$row['variation_total']).'</td>';

	}else{
		echo '<td><strong class="green-estimate">'.number_format($row['budget_estimate_total']).'<strong></td>';
	}


	if($this->session->userdata('user_id') == $row['project_manager_id'] ){
		echo '<td>PM</td>';
	}else if($this->session->userdata('user_id') == $row['project_admin_id'] ){
		echo '<td>PA</td>';
	}elseif($this->session->userdata('user_id') == $row['project_estiamator_id'] ){
		echo '<td>EST</td>';
	}else{
		echo '<td>ORD</td>';
	}

	if($row['job_date'] == '' && $row['is_paid'] == 0){
		$status = 'unset';
	}
	echo '<td>'.$status.'</td></tr>';
	
}
?>
