<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('dompdf/dompdf_config.inc.php');
spl_autoload_register('DOMPDF_autoload');

class Reports extends MY_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper('file');
		$this->load->module('users');
		$this->load->model('reports_m');
		$this->load->module('company');
		$this->load->model('company_m');
		$this->load->module('works');
		$this->load->model('works_m');
		$this->load->module('projects');
		$this->load->module('invoice');
		$this->load->module('wip');

	}

	public function index(){}

	private function delete_dir($dir) {
		$handle=opendir($dir);
		while (($file = readdir($handle))!==false) {
			@unlink($dir.'/'.$file);
		}
		closedir($handle);
		rmdir($dir);
	}

	private function pdf_create($html, $orientation='portrait', $paper='A4',$filename='report',$folder_type='general' ,$auto_clear=TRUE,$stream=TRUE){
		$dompdf = new DOMPDF();

		$dompdf->set_paper($paper,$orientation);
		$dompdf->load_html($html);
		$dompdf->render();

		$canvas = $dompdf->get_canvas();
		$date_gen = date("jS F, Y");


		if($orientation == 'portrait'){
			$font = Font_Metrics::get_font("helvetica", "bold");
			$canvas->page_text(535,10, "Page: {PAGE_NUM} of {PAGE_COUNT} ", $font, 8, array(0,0,0));
			$canvas->page_text(15, 810, "Page: {PAGE_NUM} of {PAGE_COUNT}                   Produced: $date_gen", $font, 8, array(0,0,0));
			
		}else{
			$font = Font_Metrics::get_font("helvetica", "bold");
			$canvas->page_text(780,10, "Page: {PAGE_NUM} of {PAGE_COUNT} ", $font, 8, array(0,0,0));
			$canvas->page_text(20, 570, "Page: {PAGE_NUM} of {PAGE_COUNT}                   Produced: $date_gen", $font, 8, array(0,0,0));
		}




		$output = $dompdf->output();

		$filename .= '-'.date("d-m-Y-His");

		if($auto_clear){
			$this->delete_dir('docs/'.$folder_type);  #remove folder and contents
		}

		//create the folder if it's not already exists
		if(!is_dir('docs/'.$folder_type)){
			mkdir('docs/'.$folder_type,0755,TRUE);
		}
		//create the folder if it's not already exists

		write_file('docs/'.$folder_type.'/'.$filename.'.pdf','');

		file_put_contents('docs/'.$folder_type.'/'.$filename.'.pdf', $output);

		return $filename;


		//unlink('docs/'.$folder_type.'/'.$filename.'.pdf');

		//$dompdf->stream($filename.'.pdf');
	}

	private function html_form($content,$orientation,$paper,$file_name,$folder,$auto_clear=TRUE){
		$document = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><title></title>';
		$document .= '<link type="text/css" href="'.base_url().'css/pdf.css" rel="stylesheet" />';
		$document .= '</head><body>';
		$document .= $content;
		$document .= '</body></html>';
		return $this->pdf_create($document,$orientation,$paper,$file_name,$folder,$auto_clear);
	}



	public function pdf(){
		$post_value  = $this->input->post('content');
		$formatted = str_replace('pdf_mrkp_styl="','style="',$post_value);

		//$content = '<div class="editor_body" style="position: relative;">'.$_POST['content'].'</div>';

		$content = '<p style="height:20px; width:10px; display:block; "></p><div class="editor_body" style="margin-top:-10px;"><div class="clearfix">'.$formatted.'</div></div>';

	/*	$content = '<div class="editor_body" style="position: relative;"><p>test</p><p>test</p><p>test</p><p>test</p><p>test</p><p>test</p><p>test</p><p>test</p><div id="highlight_line" class="counter_1 draggable ui-draggable ui-draggable-handle" style="left: 30px; height: 75px; opacity: 0.5; top: 100px; width: 300px; display: block;background-color: red;position: absolute;"></div>
<div id="highlight_line" class="counter_1 draggable ui-draggable ui-draggable-handle" style="left: 0px; top: 33px; width: 730px; display: block; opacity: 0.5; border:1px solid #000; height: 216px; background-color: blue; position: absolute;"></div></div>
';*/

		$my_pdf = $this->html_form($content,'portrait','A4','job_book','inv_jbs',FALSE);


	//echo $post_value;
	//	echo $my_pdf;

	}


	public function company_report(){
		$data_val = explode('*',$_POST['ajax_var']);
     	//var_dump($_POST['ajax_var']);

		if($data_val['3'] == ''){
			$data_val['3'] = 'A';
		}

		if($data_val['4'] == ''){
			$data_val['4'] = 'Z';
		}

		$letter_segment_a = $data_val['3'];
		$letter_segment_b = $data_val['4'];

		$segment_a = ord(strtolower($data_val['3']));
		$segment_b = ord(strtolower($data_val['4']));
		$company_type = '';
		

		if($data_val['1'] != ''){
			$company_type_arr = explode('|',$data_val['1']);
			$company_type_id = $company_type_arr['1'];
			$my_company_type = $company_type_arr['0'];

			if($company_type_id != '' && $company_type_id != 8){
				$company_type = "AND `company_details`.`company_type_id` = '$company_type_id'";
			}
		}else{
			$my_company_type = 'All Company Types';
		}

		$query = '';
		$content = '';
		$my_activity = '';

		if($data_val['2'] != ''){
			$query .= " AND (";
				$activity_arr = explode(',',$data_val['2']);
				$activity_loops = count($activity_arr);

				$client_category_q = '';
				foreach ($activity_arr as $activity_key => $activity_value) {
					$activity_item_arr = explode('|',$activity_value);
					$client_category_q .= '`company_details`.`activity_id` = \''.$activity_item_arr["1"].'\'';
					$my_activity .= $activity_item_arr["0"].', ';

					if($activity_key < $activity_loops-1){
						$client_category_q .= " OR ";
					}
				}

				$query .= $client_category_q;
				$query .= " )";
}else{
	$my_activity .= 'All Activities';
}



$my_states = '';
if($data_val['0'] != ''){
	$query .= " AND (";
		$state_arr = explode(',',$data_val['0']);
		$state_loops = count($state_arr);

		$state_q = '';
		foreach ($state_arr as $state_key => $state_value) {
			$state_item_arr = explode('|',$state_value);
			$state_q .= '`states`.`id` = \''.$state_item_arr["3"].'\'';
			$my_states .= $state_item_arr["1"].', ';

			if($state_key < $state_loops-1){
				$state_q .= " OR ";
			}
		}

		$query .= $state_q;
		$query .= " )";
}else{
	$my_states .= 'All States';
}





if($data_val['5'] == 'cm_asc'){
	$order_q = '`company_details`.`company_name` ASC';
	$sort = 'Company Name A-Z';
}elseif($data_val['5'] == 'cm_desc'){
	$order_q = '`company_details`.`company_name` DESC';
	$sort = 'Company Name Z-A';
}elseif($data_val['5'] == 'sub_asc'){
	$order_q = '`address_general`.`suburb` ASC';
	$sort = 'Suburb A-Z';
}elseif($data_val['5'] == 'sub_desc'){
	$order_q = '`address_general`.`suburb` DESC';
	$sort = 'Suburb Z-A';
}elseif($data_val['5'] == 'state_asc'){
	$order_q = '`states`.`id` ASC';
	$sort = 'States A-Z';
}elseif($data_val['5'] == 'state_desc'){
	$order_q = '`states`.`id` DESC';
	$sort = 'States Z-A';
}elseif($data_val['5'] == 'act_asc'){
	$order_q = '`company_details`.`activity_id` ASC';
	$sort = 'Activity A-Z';;
}elseif($data_val['5'] == 'act_desc'){
	$order_q = '`company_details`.`activity_id` DESC';
	$sort = 'Activity Z-A';
}else { }




		//echo "$company_type,$query,$order_q";

		//echo "$company_type $query $order_q";


$table_q = $this->reports_m->select_list_company($company_type,$query,$order_q);

$records_num = 0;


$content .= '<div class="clearfix header"><img src="./img/focus-logo-print.png" align="left" class="block" /><h1 class="text-right block"><br />Company List Report</h1></div><br />';


$content .= '<table id="" class="table table-striped table-bordered" cellspacing="0" width="100%"><thead><tr><th  width="30%">Company Name</th><th width="15%">Activity</th><th width="15%">Suburb</th><th width="15%">State</th><th width="15%">Contact Number</th><th width="25%">Email</th></tr></thead><tbody>';				

foreach ($table_q->result() as $row){

	$start_letter = strtolower(substr($row->company_name,0, 1));
	$segment = ord($start_letter);

	if($segment_a <= $segment){
		if($segment_b >= $segment){
			$content .= '<tr><td>'.$row->company_name.'</td>';

			if($company_type_id != '' && $company_type_id != 8){
				$company_type = "AND `company_details`.`company_type_id` = '$company_type_id'";
				$activity_type = $this->company_m->fetch_company_activity_name_by_type($company_type_arr['0'],$row->activity_id);
				$content .= '<td>'.$activity_type.'</td>';
			}else{
				$content .= '<td></td>';
			}

			$content .= '<td>'.ucwords(strtolower($row->suburb)).'</td><td>'.$row->name.'</td><td>';

			$check_num = explode(' ',$row->office_number);

			if($check_num['0'] == '1300' || $check_num['0'] == '1800'){
				$row->area_code = '';
			}

			$content .= ($row->office_number != ''? $row->area_code.' '.$row->office_number : '');

			$content .= '</td><td><a href="mailto:'.strtolower($row->general_email).'?Subject=Inquiry" >'.strtolower($row->general_email).'</a></td></tr>';
			$records_num ++;
		}				
	}

}

$content .= '</tbody></table>';

$content .= '<div class="footer"><p>Applied Filters &nbsp; &nbsp; &nbsp; &nbsp; ';
$content .= '<strong> State :</strong> '.$my_states.' | ';
$content .= '<strong> Company Type :</strong> '.$my_company_type.' | ';
$content .= '<strong> Activity :</strong> '.$my_activity.' |';
$content .= '<strong> Letter Segments :</strong> '.$letter_segment_a.' - '.$letter_segment_b.' |';
$content .= '<strong> Sort :</strong> '.$sort.' |';
$content .= '<strong> Records Found :</strong> '.$records_num.'</p></div>';

$my_pdf = $this->html_form($content,'landscape','A4','companies','temp');
echo $my_pdf;

}

public function invoice_report(){
	$data_val = explode('*',$_POST['ajax_var']);
	$content = '';

	$project_number = $data_val['0'];
	$progress_claim = $data_val['1'];
	$clinet = $data_val['2'];
	$invoice_date_a = $data_val['3'];
	$invoice_date_b = $data_val['4'];
	$invoice_status = $data_val['5'];
	$invoice_sort = $data_val['6'];
	$project_manager = $data_val['7'];

	$curr_year = date('Y');


	if($invoice_date_a == ''){
		$date_a = strtotime(str_replace('/', '-', '10/10/1990'));
		$date_filter_a = '';
	}else{
		$date_a = strtotime(str_replace('/', '-', $invoice_date_a));
		$date_filter_a = $invoice_date_a;
	}

	if($invoice_date_b == ''){
		$date_b = strtotime(str_replace('/', '-', '31/12/'.$curr_year));
		$date_filter_b = '';
	}else{
		$date_b = strtotime(str_replace('/', '-', $invoice_date_b));
		$date_filter_b = $invoice_date_b;
	}



	$project_num_q = '';
	if($project_number != ''){
		$project_num_q = '`invoice`.`project_id` =  \''.$project_number.'\'';
		$project_num_filter = $project_number;
	}else{
		$project_num_filter = 'All Projects';
	}




	$client_q = '';

	$clinet_arr = explode('|',$clinet);

	if($clinet != ''){
		$client_q = ($project_num_q != '' ? 'AND ' : '');
		$client_q .= '`project`.`client_id` = \''.$clinet_arr['1'].'\' ';
		$client_filter = $clinet_arr['0'];
	}else{
		$client_filter = 'All Clients';
	}


	$invoice_status_q = '';
	$status_filter = '';
	if($invoice_status != ''){
		$invoice_status_q = ($project_num_q != '' || $client_q != '' ? 'AND ' : '');

		$invoice_status_arr = explode(',',$invoice_status);
		$value_invoice_stat_loops = count($invoice_status_arr);

		$invoice_status_q .= '(';

			foreach ($invoice_status_arr as $stat_key => $value_invoice_stat) {
				if($value_invoice_stat == '1'){
					$invoice_status_q .= '`invoice`.`is_invoiced` = \'0\' AND `invoice`.`is_paid` = \'0\'';	
					$status_filter .= 'Un-Invoiced';			
				}elseif($value_invoice_stat == '2'){
					$invoice_status_q .= '`invoice`.`is_invoiced` = \'1\' AND `invoice`.`is_paid` = \'0\'';
					$status_filter .= 'Invoiced';
				}elseif($value_invoice_stat == '3'){
					$invoice_status_q .= '`invoice`.`is_paid` = \'1\' ';
					$status_filter .= 'Paid';
				}else{

				}

				if($stat_key < $value_invoice_stat_loops-1){
					$invoice_status_q .= " OR ";
					$status_filter .= ' and ';
				}
				
			}
			$invoice_status_q .= ')';
}else{
	$status_filter = 'All Invoice Status';

}




$progress_claim_q = '';

if($progress_claim != ''){

	$progress_claim_q = ($project_num_q != '' || $client_q != '' || $invoice_status_q != '' ? 'AND ' : '');

	$progress_claim_arr = explode(',',$progress_claim);
	$progress_claim_loops = count($progress_claim_arr);


	$progress_claim_q .= '(';

				//$progress_claim_q .= '`invoice`.`order_invoice` = \''.$progress_claim.'\' ';

		foreach ($progress_claim_arr as $progress_claim_key => $progress_claim_val) {

			if($progress_claim_val == 'VR'){
				$progress_claim_q .= '`invoice`.`label` = \'VR\' ';
			}elseif($progress_claim_val == 'F'){
				$progress_claim_q .= '(`invoice`.`label` <> \'VR\' AND `invoice`.`label` <> \'\' )';
			}else{
				$progress_claim_q .= '`invoice`.`order_invoice` = \''.$progress_claim_val.'\' ';
			}

			if($progress_claim_key < $progress_claim_loops-1){
				$progress_claim_q .= " OR ";
			}
		}

		$progress_claim_q .= ')';

}else{

}





$project_manager_q = '';
$project_manager_arr = explode('|',$project_manager);
if($project_manager != ''){
	$project_manager_q = ($project_num_q != '' || $client_q != '' || $invoice_status_q != '' || $progress_claim_q != '' ? 'AND ' : '');
	$project_manager_q .= '`project`.`project_manager_id` =  \''.$project_manager_arr['1'].'\'';
	$project_manager_filter = $project_manager_arr['0'];
}else{
	$project_manager_filter = 'All Project Managers';
}








$order_q = '';
$sort = '';
if($invoice_sort == 'clnt_asc'){
	$order_q = 'ORDER BY `company_details`.`company_name` ASC';
	$sort = 'Client Name A-Z';
}elseif($invoice_sort == 'clnt_desc'){
	$order_q = 'ORDER BY `company_details`.`company_name` DESC';
	$sort = 'Client Name Z-A';
}elseif($invoice_sort == 'inv_d_asc'){
	$order_q = 'ORDER BY `invoice`.`invoice_date_req` ASC';
	$sort = 'Invoiced Date Oldest First';
}elseif($invoice_sort == 'inv_d_desc'){
	$order_q = 'ORDER BY `invoice`.`invoice_date_req` DESC';
	$sort = 'Invoiced Date Newest First';
}elseif($invoice_sort == 'prj_num_asc'){
	$order_q = 'ORDER BY `invoice`.`project_id` ASC';
	$sort = 'Project Number Asc';
}elseif($invoice_sort == 'prj_num_desc'){
	$order_q = 'ORDER BY `invoice`.`project_id` DESC';
	$sort = 'Project Number Desc';
}else { }

$has_where = '';
if($project_num_q != '' || $progress_claim_q != '' || $client_q != '' || $invoice_status_q != '' || $project_manager_q != ''){
	$has_where = 'WHERE';
}



$table_q = $this->reports_m->select_list_invoice($has_where,$project_num_q,$client_q,$invoice_status_q,$progress_claim_q,$project_manager_q,$order_q);
$records_num = 0;

$content .= '<div class="clearfix header"><img src="./img/focus-logo-print.png" align="left" class="block" /><h1 class="text-right block"><br />Invoices List Report</h1></div><br />';
$content .= '<table id="" class="table table-striped table-bordered" cellspacing="0" width="100%"><thead><tr><th width="20%">Client Name</th><th width="20%">Project Name</th><th>Project No</th><th>Invoicing Date</th><th>Finish Date</th><th>Progress Claim</th><th>Percent</th><th>Amount</th><th>Outstanding</th></tr></thead><tbody>';				
		//var_dump($_POST['ajax_var']);

$total_project_value = 0;
$total_outstading_value = 0;


foreach ($table_q->result() as $row){
	$invoiced_date = strtotime(str_replace('/', '-', $row->invoice_date_req));

	if($date_a <= $invoiced_date){
		if($invoiced_date <= $date_b){



			$project_total_values = $this->projects->fetch_project_totals($row->project_id);

			if($row->label == 'VR'){
				$progress_order = 'Variation';
			}elseif($row->label != 'VR' && $row->label != ''){
				$progress_order = $row->label;
			}else{
				$progress_order = $row->project_id.'P'.$row->order_invoice;				
			}

			if($progress_order == 'Variation'){
				$project_total_percent = $project_total_values['variation_total'];
			}else{
				$project_total_percent = $row->project_total * ($row->progress_percent/100);
			}




			$outstanding = $this->invoice->get_current_balance($row->project_id,$row->invoice_id,$project_total_percent);

			$total_project_value = $project_total_percent + $total_project_value;
			$total_outstading_value = $outstanding + $total_outstading_value;

			$project_total_percent = number_format($project_total_percent,2);
			$outstanding = number_format($outstanding,2);

			$content .= '<tr><td>'.$row->company_name.'</td><td>'.$row->project_name.'</td><td>'.$row->project_id.'</td><td>'.$row->invoice_date_req.'</td><td>'.$row->date_site_finish.'</td><td>'.$progress_order.'</td><td>'.$row->progress_percent.'</td><td>'.$project_total_percent.'</td><td>'.$outstanding.'</td></tr>';
			$records_num++;
		}
	}

}
$content .= '</tbody></table>';

$content .= '<p><br /><hr /><strong>Project Manager:</strong> '.$project_manager_filter.' &nbsp;  &nbsp;  &nbsp; <strong> Note:</strong> All Prices are EX-GST &nbsp;  &nbsp;  &nbsp; <strong>Project Total Ex-GST:</strong> $'.number_format($total_project_value,2).'  &nbsp;  &nbsp;  &nbsp; <strong>Outstading Ex-GST:</strong> $'.number_format($total_outstading_value,2).'   <br /></p><br />';







$content .= '<div class="footer"><p>Applied Filters &nbsp; &nbsp; &nbsp; &nbsp; ';
$content .= '<strong> Client :</strong> '.$client_filter.' | ';
$content .= '<strong> Project :</strong> '.$project_num_filter.' | ';

if($date_filter_a != '' || $date_filter_b != ''){
	$content .= '<strong> Date Filter :</strong> '.$date_filter_a.' - '.$date_filter_b.' |';
}


$content .= '<strong> Invoice Status :</strong> '.$status_filter.' |';
$content .= '<strong> Sort :</strong> '.$sort.' |';
$content .= '<strong> Records Found :</strong> '.$records_num.'</p></div>';


$my_pdf = $this->html_form($content,'landscape','A4','invoices','temp');
echo $my_pdf;

      	//echo "$has_where - $project_num_q - $progress_claim_q - $order_q";

}


public function wip_report(){

	$data_val = explode('*',$_POST['ajax_var']);
	$content = '';

	$wip_client = $data_val['0'];
	$wip_pm = $data_val['1'];
	$wip_find_start_finish_date = $data_val['2'];
	$wip_find_finish_date = $data_val['3'];
	$wip_cost_total = $data_val['4'];
	$selected_cat = $data_val['5'];

	$wip_project_total = $data_val['6'];
	$wip_project_estimate = $data_val['7'];
	$wip_project_quoted = $data_val['8'];
	$wip_project_total_invoiced = $data_val['9'];

	$wip_sort = $data_val['10'];

	$date_start_a = $data_val['11'];
	$date_start_b = $data_val['12'];

	$wip_cost_total = str_replace(',', '', $wip_cost_total); 

	$records_num = 0;

	$curr_year = date('Y')+2;


	$doc_type = $data_val['13'];

	$date_created_start = $data_val['14'];
	$date_created = $data_val['15'];	

	$prj_status = $data_val['16'];	



	if($date_start_a == ''){
		$date_a_s = strtotime(str_replace('/', '-', '10/10/1900'));
		$date_start_filter_a = '';
	}else{
		$date_a_s = strtotime(str_replace('/', '-', $date_start_a));
		$date_start_filter_a = $date_start_a;
	}

	if($date_start_b == ''){
		$date_b_s = strtotime(str_replace('/', '-', '31/12/'.$curr_year));
		$date_start_filter_b = '';
	}else{
		$date_b_s = strtotime(str_replace('/', '-', $date_start_b));
		$date_start_filter_b = $date_start_b;
	}



	if($wip_find_start_finish_date == ''){
		$date_a = strtotime(str_replace('/', '-', '10/10/1900'));
		$date_filter_a = '';
	}else{
		$date_a = strtotime(str_replace('/', '-', $wip_find_start_finish_date));
		$date_filter_a = $wip_find_start_finish_date;
	}
	$curr_year = date('Y')+2;

	if($wip_find_finish_date == ''){
		$date_b = strtotime(str_replace('/', '-', '31/12/'.$curr_year));
		$date_filter_b = '';
	}else{
		$date_b = strtotime(str_replace('/', '-', $wip_find_finish_date));
		$date_filter_b = $wip_find_finish_date;
	}


	if($date_created_start == ''){
		$date_c = strtotime(str_replace('/', '-', '31/12/1900'));
		$date_filter_c = '';
	}else{
		$date_c = strtotime(str_replace('/', '-', $date_created_start));
		$date_filter_c = $date_created_start;
	}
	$curr_year = date('Y')+2;

	if($date_created == ''){
		$date_d = strtotime(str_replace('/', '-', '31/12/'.$curr_year));
		$date_filter_d = '';
	}else{
		$date_d = strtotime(str_replace('/', '-', $date_created));
		$date_filter_d = $date_created;
	}


	$wip_client_q = '';
	if($wip_client != ''){
		$wip_client_q = 'AND `company_details`.`company_name` =  \''.$wip_client.'\'';
		$wip_client_filter = $wip_client;
	}else{
		$wip_client_filter = 'All Clients';
	}

	$wip_pm_q = '';
	if($wip_pm != ''){
		$wip_pm_arr = explode('|', $wip_pm);
		$wip_pm_q = 'AND `project`.`project_manager_id` =  \''.$wip_pm_arr['1'].'\'';
		$wip_pm_filter = $wip_pm_arr['0'];
	}else{
		$wip_pm_filter = 'All Project Managers';
	}

	$wip_cost_total_q = '';
	if($wip_cost_total == ''){
		$wip_cost_total_q = 100000000000000;
		$wip_cost_filter = 'All Prices';
	}else{
		$wip_cost_total_q = $wip_cost_total;
		$wip_cost_filter = $wip_cost_total;
	}

	$selected_cat_q = '';

	if($selected_cat != ''){


		$selected_cat_arr = explode(',',$selected_cat);
		$selected_cat_loops = count($selected_cat_arr);


		$selected_cat_q .= 'AND (';

				//$selected_cat_q .= '`invoice`.`order_invoice` = \''.$progress_claim.'\' ';

			foreach ($selected_cat_arr as $selected_cat_key => $selected_cat_val) {

				$selected_cat_q .= '`project`.`job_category` = \''.$selected_cat_val.'\'';

				if($selected_cat_key < $selected_cat_loops-1){
					$selected_cat_q .= " OR ";
				}
			}

			$selected_cat_q .= ')';

}else{

}





$order_q = '';
$sort = '';
if($wip_sort == 'clnt_asc'){
	$order_q = 'ORDER BY `company_details`.`company_name` ASC';
	$sort = 'Client Name A-Z';
}elseif($wip_sort == 'clnt_desc'){
	$order_q = 'ORDER BY `company_details`.`company_name` DESC';
	$sort = 'Client Name Z-A';
}elseif($wip_sort == 'fin_d_asc'){
	$order_q = 'ORDER BY `date_filter_mod` ASC';
	$sort = 'Finish Date Descending';
}elseif($wip_sort == 'fin_d_desc'){
	$order_q = 'ORDER BY `date_filter_mod` DESC';
	$sort = 'Finish Date Ascending';
}elseif($wip_sort == 'srtrt_d_asc'){
	$order_q = 'ORDER BY `start_date_filter_mod` ASC';
	$sort = 'Start Date Descending';
}elseif($wip_sort == 'srtrt_d_desc'){
	$order_q = 'ORDER BY `start_date_filter_mod` DESC';
	$sort = 'Start Date Ascending';
}elseif($wip_sort == 'prj_num_asc'){
	$order_q = 'ORDER BY `project`.`project_id` ASC';
	$sort = 'Project Number Asc';
}elseif($wip_sort == 'prj_num_desc'){
	$order_q = 'ORDER BY `project`.`project_id` DESC';
	$sort = 'Project Number Desc';
}else { $order_q = ''; }

$type = '';

if($doc_type == 'WIP'){
	$type = ' AND `project`.`job_date` <> \'\' ';
}else{
			//$type = '';
}

$status = '';
$show_invoiced = 0;
$show_un_invoiced = 0;
$page_type = '';


if($prj_status != ''){

	$status_list = explode(',',$prj_status);
	$prj_status_arr_count = count($status_list);




	if (in_array("wip", $status_list) || in_array("paid", $status_list) || in_array("invoiced", $status_list)){
		if (!in_array("notwip", $status_list) ){
			$status .= ' AND `project`.`job_date` <> \'\'  ';
		}else{
			$status .= ' AND (`project`.`job_date` = \'\' OR  `project`.`job_date` <> \'\') ';
		}
	}

	if(in_array("wip", $status_list) || in_array("notwip", $status_list) || in_array("invoiced", $status_list)){
		if (!in_array("paid", $status_list) ){;
			$status .= ' AND  `project`.`is_paid` = \'0\'  ';
		}else{
			$status .= ' AND  ( `project`.`is_paid` = \'0\' OR  `project`.`is_paid` = \'1\'  )';						
		}
	}


	/*		if (!in_array("wip", $status_list) && !in_array("paid", $status_list) ){
				$status = ' AND `project`.`job_date` <> \'\' AND `project`.`is_paid` = \'1\'  ';
			}
*/







			if (in_array("wip", $status_list) || in_array("notwip", $status_list) ){
				$show_un_invoiced = 1;
			}

			if (in_array("paid", $status_list) || in_array("invoiced", $status_list) ){
				$show_invoiced = 1;
			}
			


			if($prj_status_arr_count == 4 || $prj_status_arr_count == 0 ){
				$status = '';
				$show_invoiced = 1;
				$show_un_invoiced = 1;
				$page_type = '';
			}
			
		}else{
			$status = '';
			$show_invoiced = 1;
			$show_un_invoiced = 1;
			$page_type = '';
		}

		$content .= '<div class="clearfix header"><img src="./img/focus-logo-print.png" align="left" class="block" /><h1 class="text-right block"><br />'.$page_type.' '.$doc_type.' List Report</h1></div><br />';
		$content .= '<table id="" class="table table-striped table-bordered" cellspacing="0" width="100%"><thead><tr><th>Finish</th><th>Start</th><th>Client</th><th>Project</th><th>Total</th><th>Job Date</th><th>Instal Hrs</th><th>Project No</th><th>Invoiced $</th></tr></thead><tbody>';				
		//var_dump($_POST['ajax_var']);
		$wip_list_q = $this->reports_m->select_list_wip($wip_client_q,$wip_pm_q,$selected_cat_q,$order_q,$type,$status);
		$total_list = 0;
		
		$arrs = array();
		$color = '';

//		$content .= '<tr><td colspan="5">'.$status.' '.$type.' '.$wip_client_q.' '.$wip_pm_q.' '.$selected_cat_q.' '.$order_q.'</td></tr>';

		foreach ($wip_list_q->result_array() as $row){

			if($row['job_date'] == '' ){
				$color = 'notwip';
			}



			if($row['job_date'] != '' ){
				$color = 'wip';
			}

			if($this->invoice->if_invoiced_all($row['project_id'])  && $this->invoice->if_has_invoice($row['project_id']) > 0 ){
				$color = 'invoiced';
			}

			if($row['is_paid'] == 1){
				$color = 'paid';
			}




			$date_site_finish = $row['date_filter_mod'];
			$date_site_start = $row['start_date_filter_mod'];
			$date_project_date = strtotime(str_replace('/', '-', $row['project_date']));


			if(in_array($color, $status_list)){

				if($doc_type == 'WIP'){
					$color = 'wip_b';
				}

				if($date_a_s <= $date_site_start){
					if($date_site_start <= $date_b_s){

						if($date_a <= $date_site_finish){
							if($date_site_finish <= $date_b){

								if($date_c <= $date_project_date ){
									if( $date_project_date <= $date_d ){

										if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 ){
											$total_list = $row['project_total'];
										}else{
											$total_list = $row['budget_estimate_total'];
										}

										if($wip_cost_total_q >= $total_list){

											if($this->invoice->if_invoiced_all($row['project_id']) && $this->invoice->if_has_invoice($row['project_id']) > 0 ){

												if($show_invoiced == 1){

													$content .= '<tr class="'.$color.'"><td>'.$row['date_site_finish'].'</td><td>'.$row['date_site_commencement'].'</td><td>'.$row['company_name'].'</td><td>'.$row['project_name'].'</td>';

													if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 ){
														$content .= '<td>'.number_format($row['project_total'],2).'</td>';
													}else{
														$content .= '<td class="green-estimate">'.number_format($row['budget_estimate_total'],2).'</td>';
													}

													$content .= '<td>'.$row['job_date'].'</td>';

													if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 ){
														$content .= '<td>'.number_format($row['install_time_hrs'],2).'</td>';
													}else{
														$content .= '<td class="green-estimate">'.number_format($row['labour_hrs_estimate'],2).'</td>';
													}

													$content .= '<td>'.$row['project_id'].'</td><td>'.number_format($this->invoice->get_project_invoiced($row['project_id'],$row['project_total']),2).'</td></tr>';			
													$records_num++;
													array_push($arrs,$row['project_id']);
												}

											}else{		

												if($show_un_invoiced == 1){ 

													$content .= '<tr class="'.$color.'"><td>'.$row['date_site_finish'].'</td><td>'.$row['date_site_commencement'].'</td><td>'.$row['company_name'].'</td><td>'.$row['project_name'].'</td>';

													if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 ){
														$content .= '<td>'.number_format($row['project_total'],2).'</td>';
													}else{
														$content .= '<td class="green-estimate">'.number_format($row['budget_estimate_total'],2).'</td>';
													}

													$content .= '<td>'.$row['job_date'].'</td>';

													if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 ){
														$content .= '<td>'.number_format($row['install_time_hrs'],2).'</td>';
													}else{
														$content .= '<td class="green-estimate">'.number_format($row['labour_hrs_estimate'],2).'</td>';
													}

													$content .= '<td>'.$row['project_id'].'</td><td>'.number_format($this->invoice->get_project_invoiced($row['project_id'],$row['project_total']),2).'</td></tr>';			
													$records_num++;
													array_push($arrs,$row['project_id']);
												}

											}
										}
									}
								}
							}}
						}
					}
				}
			}


		//var_dump($arrs);


			$content .= '</tbody></table>';

			$arrs = implode(',', $arrs);

			$content .= '<p><br /><hr /><span><strong> All Prices are EXT-GST</strong> &nbsp; &nbsp; '.$this->wip->sum_total_wip_cost($arrs).'</span><br /></p><p><br /><span><strong>Project Manager:</strong> '.$wip_pm_filter.'</span> &nbsp;  &nbsp;   &nbsp;  &nbsp; <strong>Color Codes:</strong> &nbsp;  &nbsp;  <strong class="invoiced">Invoiced</strong> &nbsp;  &nbsp;  <strong class="paid">Paid</strong> &nbsp;  &nbsp;  <strong class="wip">WIP</strong></p>';

			$content .= '<div class="footer"><p>';
			$content .= '<strong> Client :</strong> '.$wip_client_filter.' | ';

			$content .= '<strong> Category :</strong> '.$selected_cat.' | ';

			if($date_start_filter_a != '' || $date_start_filter_b != ''){
				$content .= '<strong> Start Date Filter :</strong> '.$date_start_filter_a.' - '.$date_start_filter_b.' |';
			}

			if($date_filter_a != '' || $date_filter_b != ''){
				$content .= '<strong> Finish Date Filter :</strong> '.$date_filter_a.' - '.$date_filter_b.' |';
			}

			if($wip_cost_total != ''){
				$content .= '<strong> Total Limit :</strong> '.$wip_cost_total.' |';
			}

			$content .= '<strong> Cost Range :</strong> '.$wip_cost_filter.' |';
			$content .= '<strong> Sort :</strong> '.$sort.' |';
			$content .= '<strong> Records Found :</strong> '.$records_num.'</p></div>';

			$my_pdf = $this->html_form($content,'landscape','A4','wip','temp');
			echo $my_pdf;

		}
	}