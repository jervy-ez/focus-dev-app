<?php
foreach ($proj_t->result_array() as $row){
	echo '<tr><td><a href="'.base_url().'projects/view/'.$row['project_id'].'" >'.$row['project_number'].'</a></td><td>'.$row['project_name'].'</td><td>'.$row['company_name'].'</td><td>'.$row['job_category'].'</td><td>'.$row['job_date'].'</td><td>'.$row['budget_estimate_total'].'</td></tr>';
}
?>
