<?php
foreach ($proj_t->result_array() as $row){
	echo '<tr><td><a onmouseover="showProjectCmmnts('.$row['project_id'].')" href="'.base_url().'projects/view/'.$row['project_id'].'" >'.$row['project_id'].'</a></td><td>'.$row['project_name'].'</td><td>'.$row['company_name'].'</td><td>'.$row['job_category'].'</td><td>'.($row['job_date'] == '' ? 'Unset' : $row['job_date']).'</td>';


	if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 ){
		echo '<td>'.number_format($row['project_total']).'</td></tr>';

	}else{
		echo '<td><strong class="green-estimate">'.number_format($row['budget_estimate_total']).'<strong></td></tr>';
	}

	
}
?>
