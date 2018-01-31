<?php
//$this->load->model('projects_m');
//$this->load->module('invoice');

$get_table_status = $this->input->get('status');

if(isset($get_table_status ) && $get_table_status != '' ){


	$get_table_status = $this->input->get('status');

}else{
	//$get_table_status = 'wip';


	$project_status_view = $this->session->userdata('default_projects_landing');

	if ($project_status_view == 0) {
		$get_table_status = 'all';
	} elseif ($project_status_view == 1) {
		$get_table_status = 'wip';
	} elseif ($project_status_view == 2) {
		$get_table_status = 'quote';
	} elseif ($project_status_view == 3) {
		$get_table_status = 'unset';
	} elseif ($project_status_view == 4) {
		$get_table_status = 'invoiced';
	} elseif ($project_status_view == 5) {
		$get_table_status = 'paid';
	}



}



$is_restricted = 0;
foreach ($proj_t->result_array() as $row){
	$unaccepted_date = $row['unaccepted_date'];
	if($unaccepted_date !== ""){
		$unaccepted_date_arr = explode('/',$unaccepted_date);
		$u_date_day = $unaccepted_date_arr[0];
		$u_date_month = $unaccepted_date_arr[1];
		$u_date_year = $unaccepted_date_arr[2];
		$unaccepted_date = $u_date_year.'-'.$u_date_month.'-'.$u_date_day;
	}
	
	$start_date = $row['date_site_commencement'];
	if($start_date !== ""){
		$start_date_arr = explode('/',$start_date);
		$s_date_day = $start_date_arr[0];
		$s_date_month = $start_date_arr[1];
		$s_date_year = $start_date_arr[2];
		$start_date = $s_date_year.'-'.$s_date_month.'-'.$s_date_day;
	}


	$status = '';

	if($row['job_date'] != '' ){
		$status = 'wip';
	}

	if($this->invoice->if_invoiced_all($row['project_id'])  && $this->invoice->if_has_invoice($row['project_id']) > 0 && $get_table_status == 'invoiced'){
		$status = 'invoiced';
	}

	if($row['is_paid'] == 1){
		$status = 'paid';
	}



if($get_table_status == 'unset' || $get_table_status == 'quote' ){
	if($row['job_date'] == '' && $row['is_paid'] == 0 ){
		$job_category_arr = explode(",",$unaccepted_date_categories);
		foreach ($job_category_arr as &$value) {
			if($value ==  $row['job_category']){
				$is_restricted = 1;
			}
		}

		$today = date('Y-m-d');
		$unaccepteddate =strtotime ( '-'.$unaccepted_no_days.' day' , strtotime ( $start_date ) ) ;
		$unaccepteddate = date ( 'Y-m-d' , $unaccepteddate );

		if(strtotime($unaccepteddate) < strtotime($today)){
			if($is_restricted == 1){
				if($unaccepted_date == ""){
					if(strtotime($start_date) < strtotime($today)){
						$unaccepteddate_arr = explode('-',$today);
						$ud_date_day = $unaccepteddate_arr[2];
						$ud_date_month = $unaccepteddate_arr[1];
						$ud_date_year = $unaccepteddate_arr[0];
						$unaccepted_date = $ud_date_day.'/'.$ud_date_month.'/'.$ud_date_year;
						$this->projects_m->insert_unaccepted_date($row['project_id'],$unaccepted_date);
						$status = 'unset';
					}else{
						$status = 'quote';
					}
				}else{
					$status = 'unset';
				}
			}else{
				$status = 'unset';
			}
			
		}else{
			if($unaccepted_date == ""){
				$status = 'quote';
			}else{
				$status = 'unset';
			}
			
		}
		

		if($status == 'unset' ){
			if($row['unaccepted_date'] == ""){
				$unaccepteddate_arr = explode('-',$unaccepteddate);
				$ud_date_day = $unaccepteddate_arr[2];
				$ud_date_month = $unaccepteddate_arr[1];
				$ud_date_year = $unaccepteddate_arr[0];
				$unaccepted_date = $ud_date_day.'/'.$ud_date_month.'/'.$ud_date_year;
				if($is_restricted == 0){
					$this->projects_m->insert_unaccepted_date($row['project_id'],$unaccepted_date);
				}

			}
		}

	}

	}

	

if($status == $get_table_status || $get_table_status == 'all'):

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

	// if($row['job_date'] == '' && $row['is_paid'] == 0){
	// 	$status = 'unset';
	// }
	echo '<td>'.$status.'</td></tr>';

	endif;

	$is_restricted = 0;
}
?>
