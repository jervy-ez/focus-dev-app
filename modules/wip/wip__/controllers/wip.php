<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Wip extends MY_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->module('users');
		$this->load->module('projects');
		$this->load->module('company');
		$this->load->module('works');
		$this->load->module('invoice');
		$this->load->model('wip_m');
		$this->load->model('projects_m');
		if(!$this->users->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;
	}
	

	public function index(){

		$data['proj_t'] = $this->wip_m->display_all_wip_projects();

		$data['users'] = $this->user_model->fetch_user();

		
		//$this->load->view('tables_projects',$data);

		$data['main_content'] = 'wip_home';
		$data['screen'] = 'Work In Progress';
		$this->load->view('page', $data);
	}

	public function sum_total_wip_cost($arrs = ''){

	/*	if($arrs == ''){
			
			$wip_list = $this->wip_m->display_all_wip_projects();
			$arrs = array();

			foreach ($wip_list->result_array() as $row){
				array_push($arrs, $row['project_id']);				
			}	



		}else{*/
			$arr = explode(',', $arrs);

	//	}

		// var_dump($arrs);


		$totals_raw = $arr;

		$project_total = 0;
		$project_estimate = 0;
		$project_quoted = 0;
		$total_invoice = 0;
		$total_invoice_percent = 0;


		foreach ($totals_raw as $key => $id) {

			$project_cost_total_raw = $this->projects_m->get_project_cost_total($id);			
			$project_cost_total = array_shift($project_cost_total_raw->result_array());

			$fetch_project_details_raw = $this->projects_m->fetch_project_details($id);		
			$project_details = array_shift($fetch_project_details_raw->result_array());	


			if($project_details['install_time_hrs'] > 0 || $project_cost_total['work_estimated_total'] > 0){
				$project_quoted = $project_details['project_total'] + $project_quoted;

				$total_invoice_percent = $this->invoice->get_total_invoiced($project_details['project_id']);

				if($total_invoice_percent > 0){
					$total_invoice = $total_invoice + (  $project_details['project_total'] * $total_invoice_percent);
				}

			}else{
				$project_estimate = $project_details['budget_estimate_total'] + $project_estimate;
			}


		}

		$project_total = $project_quoted + $project_estimate;

		return '<span class="wip_project_total" style="font-size: 16px;"><strong>Project Total: </strong> $'.number_format($project_total,2).'</span> &nbsp;&nbsp;&nbsp;&nbsp; <span class="wip_project_estimate green-estimate"><strong>Total Estimated:</strong> $'.number_format($project_estimate,2).'</span> &nbsp;&nbsp;&nbsp;&nbsp; <span class="wip_project_quoted"><strong>Total Quoted: </strong> $'.number_format($project_quoted,2).'</span> &nbsp;&nbsp;&nbsp;&nbsp; <span class="wip_project_total_invoiced" ><strong>Total Invoiced:</strong> $'.number_format($total_invoice,2).'</span>';							

	}

 
	public function dynamic_wip_table(){

		$prjtotal = 0;
		$total_invoice = 0;

		if(isset($_POST['ajax_var'])){

			$filters = explode('*',$_POST['ajax_var']);
			$wip_table = $this->wip_m->display_all_wip_projects();
 
			echo '<thead><tr><th>Finish</th><th>Start</th><th>Client</th><th>Project</th><th>Total</th><th>Job Date</th><th>Install Hrs</th><th>Project Number</th><th>Invoiced $</th></tr></thead><tbody>';

			foreach ($wip_table->result_array() as $row){

				if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 ){
					$prjtotal = $row['project_total'];
				}else{
					$prjtotal = $row['budget_estimate_total'];
				}

				if($filters[5]!=''){
					$total_filter = str_replace(',', '',$filters[5]);
				}



if($filters[3]!=''){
	$dt = DateTime::createFromFormat('d/m/Y',$filters[3]);
	$fDateA = $dt->getTimestamp();

	$dt = DateTime::createFromFormat('d/m/Y',$row['date_site_finish']);
	$dateA = $dt->getTimestamp();
}

if($filters[4]!=''){
	$dt = DateTime::createFromFormat('d/m/Y',$filters[4]);
	$fDateB = $dt->getTimestamp();

	$dt = DateTime::createFromFormat('d/m/Y',$row['date_site_finish']);
	$dateB = $dt->getTimestamp();
}

#first filter
if($filters[0]==$row['company_name'] || $filters[0]==''){
#first filter


#second filter
if($filters[1]==$row['user_first_name'].' '.$row['user_last_name'] || $filters[1]==''){
#second filter

#third filter
if(strpos($filters[2], $row['job_category']) !== FALSE || $filters[2]==''){
#third filter

#fourth filter
if($fDateA <= $dateA || $filters[3]==''){
#fourth filter

#fifth filter
if($fDateB >= $dateB || $filters[4]==''){
#fifth filter

#sixth filter
if($total_filter >= $prjtotal || $total_filter==''){
#sixth filter



				echo '<tr><td>'.$row['date_site_finish'].'</td><td>'.$row['date_site_commencement'].'</td><td>'.$row['company_name'].'</td><td>'.$row['project_name'].'</td>';

				if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 ){
					echo '<td>'.number_format($row['project_total']).'</td>';
					$wip_total = $row['project_total'];
				}else{
					echo '<td class="green-estimate">'.number_format($row['budget_estimate_total']).'</td>';
					$wip_total = $row['budget_estimate_total'];
				}

				echo '<td>'.$row['job_date'].'</td>';

				if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 ){
					echo '<td>'.number_format($row['install_time_hrs']).'</td>';
				}else{
					echo '<td class="green-estimate">'.number_format($row['labour_hrs_estimate']).'</td>';
				}

				echo '<td>'.$row['project_id'].'</td><td>'.number_format( $wip_total*$this->invoice->get_total_invoiced($row['project_id']) ).'</td></tr>';


#sixth filter
}
#sixth filter

#fifth filter
}
#fifth filter

#fourth filter
}
#fourth filter

#third filter
}
#third filter


#second filter
}
#second filter

#first filter
}
#first filter

			}

			echo '</tbody>';


		}

	}
	
	
}