<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('dompdf/dompdf_config.inc.php');
spl_autoload_register('DOMPDF_autoload');

class Reports extends MY_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper('file');
		$this->load->helper('download');
		$this->load->module('users');
		$this->load->model('user_model');
		$this->load->model('reports_m');
		$this->load->module('works');
		$this->load->model('works_m');
		$this->load->module('projects');
		$this->load->model('projects_m');
		$this->load->module('invoice');
		$this->load->module('wip');
		$this->load->model('wip_m');


		$this->load->module('admin');
		$this->load->model('admin_m');
		
		$this->load->module('company');
		$this->load->model('company_m');

		date_default_timezone_set("Australia/Perth");

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
		$html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

		$dompdf->set_paper($paper,$orientation);
		$dompdf->load_html($html);
		$dompdf->render();

		$canvas = $dompdf->get_canvas();
		$date_gen = date("jS F, Y");

		$user_prepared = ucfirst($this->session->userdata('user_first_name')).' '.ucfirst($this->session->userdata('user_last_name'));

		if($orientation == 'portrait'){
			$font = Font_Metrics::get_font("helvetica", "bold");
			$canvas->page_text(535,10, "Page: {PAGE_NUM} of {PAGE_COUNT} ", $font, 8, array(0,0,0));
			$canvas->page_text(15, 810, "Page: {PAGE_NUM} of {PAGE_COUNT}                   Produced: $date_gen                   Prepared By: $user_prepared", $font, 8, array(0,0,0));
			
		}else{
			$font = Font_Metrics::get_font("helvetica", "bold");
			$canvas->page_text(780,10, "Page: {PAGE_NUM} of {PAGE_COUNT} ", $font, 8, array(0,0,0));
			$canvas->page_text(20, 570, "Page: {PAGE_NUM} of {PAGE_COUNT}                   Produced: $date_gen                   Prepared By: $user_prepared", $font, 8, array(0,0,0));
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

	public function html_form($content,$orientation,$paper,$file_name,$folder,$auto_clear=TRUE){
		$document = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><title></title>';
	//	$document .= '<link type="text/css" href="'.base_url().'css/pdf.css" rel="stylesheet" />';
	
	$document .= '<style type="text/css">
        *,body,html{margin:0;padding:0}.container:after,.editor_body .clearfix:after,.editor_body .container:after{clear:both}*{font-family:Arial,Helvetica,sans-serif;font-size:12px}body{margin:45px 0!important}table{border-collapse:collapse}tbody tr:nth-child(odd){background-color:#ccc}table,td,th{border:1px solid #000}td,th{height:25px;vertical-align:middle;padding:0 5px}h1{font-size:20px!important;font-weight:700}.header{border-bottom:2px solid #000}.text-right{text-align:right!important;display:block}.text-left{text-align:left!important;display:block}.notes_line{border-bottom:1px solid #010C13;padding:10px 0}.pull-right{float:right!important;display:block}.pull-left{float:left!important;display:block}.container:after,.container:before{content:"";display:table}.hidden,.hide{display:none}.container{zoom:1}.footer{position:absolute;bottom:0;background:#ccc;padding:0 10px;border:1px solid #000}.green-estimate{color:green!important;font-weight:700!important}.invoiced,tr.invoiced td,tr.invoiced td a{color:#ce03cb!important;font-weight:700!important}.paid,tr.paid td,tr.paid td a{color:#0398BD!important;font-weight:700!important}.wip,tr.wip td,tr.wip td a{color:#C16601!important;font-weight:700!important}.wip_b,tr.wip_b td,tr.wip_b td a{color:#000!important}.editor_body,.editor_body p{font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;line-height:1.42857143;color:#333}.hidden{visibility:hidden}.editor_body{margin:-20px 10px 20px;position:relative;font-size:14px}.editor_body p{margin-bottom:0;font-size:12px}.editor_body strong{font-weight:700!important}.editor_body .green-estimate,.editor_body .green-estimate label{color:green!important;border-color:green!important;font-weight:700!important}.editor_body .green-estimate .form-control{border-color:green!important;color:green!important;font-weight:700}.editor_body .green-estimate .input-group-addon{color:green!important;border-color:#555!important;background:#B0EDB0;font-weight:700}.editor_body td.green-estimate{font-weight:700;color:green!important}.editor_body table{width:100%}.editor_body table,.editor_body table tr,.editor_body table tr td{border:0;padding:0;margin:0;background:#fff}.editor_body table th{font-size:12px;margin-bottom:10px;border-bottom:1px solid #000!important;text-align:left;padding-left:5px;font-weight:700}.editor_body .header,.editor_body .totals{border:2px solid #000;margin-bottom:10px}.editor_body table td{padding:5px}.editor_body .clearfix:after,.editor_body .clearfix:before{content:" "}.editor_body img{margin-bottom:10px}.editor_body .totals{padding:10px;font-size:18px;background:#DDD}.editor_body .m-left-20,.editor_body .m-right-20{margin-right:20px}.editor_body .footer{border-top:2px solid #000;padding:10px;font-size:18px;position:absolute;bottom:0;width:96%}.editor_body .print_bttn{background:#429742;color:#fff;border-radius:5px;padding:10px 15px;text-decoration:none;position:fixed;right:10px;top:10px}.editor_body hr{border:none;border-top:1px solid #000;margin-bottom:5px;display:block;width:100%;height:1px;background:#000}.editor_body fieldset{border:none}.editor_body fieldset legend{background:#fff;width:auto;border-bottom:0;margin-bottom:-5;font-size:14px!important;font-weight:700!important}.editor_body .full{width:100%;display:block}.editor_body .one-fourth{width:25%;float:left}.editor_body .one-third{width:33.33%;float:left}.editor_body .one-half{width:50%;float:left}.editor_body .two-third{width:66.67%;float:left}.editor_body .border-1{border:1px solid #000}.editor_body .border-2{border:2px solid #000}.editor_body .border-left-1{border-left:1px solid #000}.editor_body .border-left-2{border-left:2px solid #000}.editor_body .border-right-1{border-right:1px solid #000}.editor_body .border-right-2{border-right:2px solid #000}.editor_body .mgn-top-10{margin-top:10px}.editor_body .mgn-top-15{margin-top:15px}.editor_body .mgn-top-20{margin-top:20px}.editor_body .mgn-top-25{margin-top:25px}.editor_body .pad-10{padding:10px}.editor_body .mgn-10{margin:10px}.editor_body .pad-15{padding:15px}.editor_body .mgn-15{margin:15px}.editor_body .pad-20{padding:20px}.editor_body .mgn-20{margin:20px}.editor_body .pad-r-5{padding-right:5}.editor_body .pad-l-10{padding-left:10px}.editor_body .mgn-l-5{margin-left:5px}.editor_body .pad-r-10{padding-right:10px}.editor_body .mgn-r-5{margin-right:5px}.editor_body .mgn-b-10{margin-bottom:10px}.editor_body .pdf_highlight_doc_mod{color:red;background:#ff0}#highlight_line{height:24px;margin-left:-5px!important}#draggable{background:0 0!important;border:none;color:red;padding:10px;margin-top:0!important}#draggable textarea:hover{overflow:visible}.c_edit{margin-top:10px!important}.d_edit{margin-top:3px!important}.f_edit{margin-top:-5px!important}.g_edit{margin-top:5px!important}.e_edit{margin-top:0!important}.h_edit{margin-top:-10px!important}.i_edit{margin-top:-20px!important}.j_edit{margin-top:12px!important}.k_edit{margin-top:7px!important}.l_edit{margin-top:15px!important}.m_edit{margin-top:-20px!important}.pad-20{padding:20px}.def_page{margin:20px 15px 50px}.def_page table{margin:5px 0 10px}.def_page table td{padding:0 5px}.def_page .footer{padding:5px}.job_book_notes .notes_line p span.pull-right{float:none!important;display:block}.notes_line br{display:none}.block,.notes_line br.block{display:block!important}.notes_line br.block{width:100%!important;height:auto!important;float:none!important}.editor_body .invoices_list_item{font-family:DejaVu Sans,Arial,Helvetica,sans-serif!important}
        </style>';


		$document .= '</head><body>';
		$document .= $content;
		$document .= '</body></html>';
		return $this->pdf_create($document,$orientation,$paper,$file_name,$folder,$auto_clear);
	}



	public function pdf($content='',$file_name){

		if($content != ''){
			$post_value = $content;
		}else{
			$post_value  = $this->input->post('content');
		}

		$formatted = str_replace('pdf_mrkp_styl="','style="',$post_value);

		$content = '<div class="editor_body"><div class="clearfix">'.$formatted.'</div></div>';
		$my_pdf = $this->html_form($content,'portrait','A4',$file_name,'inv_jbs',FALSE);

		//echo $post_value;
		return $my_pdf;
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

$content .= '<div class="def_page"><div class="clearfix header"><img src="./img/focus-logo-print.png" align="left" class="block" style="margin-top:-30px; " /><h1 class="text-right block" style="margin-top:-10px; margin-bottom:10px;" ><br />Company List Report</h1></div><br />';

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
$content .= '<strong> Records Found :</strong> '.$records_num.'</p></div></div>';

$my_pdf = $this->html_form($content,'landscape','A4','companies','temp');
echo $my_pdf;

}

public function invoice_report(){

	if(isset($_GET['ajax_var'])){
		$data_val = explode('*',$_GET['ajax_var']);

	}else{
		$data_val = explode('*',$_POST['ajax_var']);
	}


	$content = '';

	$project_number = $data_val['0'];
	$progress_claim = $data_val['1'];
	$clinet = $data_val['2'];
	$invoice_date_a = $data_val['3'];
	$invoice_date_b = $data_val['4'];
	$invoice_status = $data_val['5'];
	$invoice_sort = $data_val['6'];
	$project_manager = $data_val['7'];

	$invoiced_date_a = $data_val['8'];
	$invoiced_date_b = $data_val['9'];


	$doc_type = $data_val['10'];

	$curr_year = date('Y');
	$inv_date_type = 0;


	if($invoice_date_a == ''){
		$date_a = strtotime(str_replace('/', '-', '10/10/1990'));
		$date_filter_a = '';
	}else{
		$date_a = strtotime(str_replace('/', '-', $invoice_date_a));
		$date_filter_a = $invoice_date_a;
		$inv_date_type = 1;
	}

	if($invoice_date_b == ''){
		$date_b = strtotime(str_replace('/', '-', '31/12/'.$curr_year));
		$date_filter_b = '';
	}else{
		$date_b = strtotime(str_replace('/', '-', $invoice_date_b));
		$date_filter_b = $invoice_date_b;
		$inv_date_type = 1;
	}



	if($invoiced_date_a != ''){
		$date_a = strtotime(str_replace('/', '-', $invoiced_date_a));
		$date_filter_a = $invoiced_date_a;
		$inv_date_type = 2;
	}

	if($invoiced_date_b != ''){
		$date_b = strtotime(str_replace('/', '-', $invoiced_date_b));
		$date_filter_b = $invoiced_date_b;
		$inv_date_type = 2;
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

	//	$invoice_status_arr = explode(',',$invoice_status);
	//	$value_invoice_stat_loops = count($invoice_status_arr);

//		$invoice_status_q .= '(';

		//	foreach ($invoice_status_arr as $stat_key => $value_invoice_stat) {
				if($invoice_status == '1'){
					$invoice_status_q .= '`invoice`.`is_invoiced` = \'0\' AND `invoice`.`is_paid` = \'0\'';	
					$status_filter .= 'Un-Invoiced';			
				}elseif($invoice_status == '2'){
					$invoice_status_q .= '`invoice`.`is_invoiced` = \'1\' ';
					$status_filter .= 'Invoiced';
				}elseif($invoice_status == '3'){
					$invoice_status_q .= '`invoice`.`is_paid` = \'1\' ';
					$status_filter .= 'Paid';
				}elseif($invoice_status == '4'){
					$invoice_status_q .= '`invoice`.`is_invoiced` = \'1\' AND `invoice`.`is_paid` = \'0\'';
					$status_filter .= 'Outstanding';
				}elseif($invoice_status == '5'){
					$invoice_status_q .= '`invoice`.`is_invoiced` = \'0\' AND `invoice`.`is_paid` = \'0\'';	
					$status_filter .= 'Future Invoice';
				}else{

				}
/*
				if($stat_key < $value_invoice_stat_loops-1){
					$invoice_status_q .= " OR ";
					$status_filter .= ' and ';
				}
*/				
		//	}
//			$invoice_status_q .= ')';
}else{
	$status_filter = 'All Invoice Status';

}




			

			if($invoice_status == '1' || $invoice_status == '5'){

				$date_sort_filter = " AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) >= $date_a
				AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) <= $date_b ";

			}else{

				$date_sort_filter = " AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= $date_a
				AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) <= $date_b ";

			
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


/*/
if($inv_date_type ==2  ){
	$date_sort_filter = " AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$invoice_date_a', '%d/%m/%Y') )
	AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$invoice_date_b', '%d/%m/%Y') ) ";
}elseif($inv_date_type == 1){
	if($invoice_date_a == ''){
		$date_sort_filter = " AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('01/01/2000', '%d/%m/%Y') )
		AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$invoice_date_b', '%d/%m/%Y') ) ";
	}
	if($invoice_date_b == ''){
		$date_sort_filter = " AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$invoice_date_a', '%d/%m/%Y') )
		AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('31/12/".$curr_year.", '%d/%m/%Y') )   ";
	}
}else{
	$date_sort_filter = " AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('01/01/1990', '%d/%m/%Y') )
	AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`set_invoice_date`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('31/12/".$curr_year.", '%d/%m/%Y') )   ";
}


*/






$order_q = '';
$sort = '';
if($invoice_sort == 'clnt_asc'){
	$order_q = '  GROUP BY `invoice`.`invoice_id`  ORDER BY `company_details`.`company_name` ASC';
	$sort = 'Client Name A-Z';
}elseif($invoice_sort == 'clnt_desc'){
	$order_q = '  GROUP BY `invoice`.`invoice_id`  ORDER BY `company_details`.`company_name` DESC';
	$sort = 'Client Name Z-A';
}elseif($invoice_sort == 'inv_d_asc'){
	$order_q = '  GROUP BY `invoice`.`invoice_id`  ORDER BY `in_set_ord` DESC';
	$sort = 'Invoiced Date DESC';
}elseif($invoice_sort == 'inv_d_desc'){
	$order_q = '  GROUP BY `invoice`.`invoice_id`  ORDER BY `in_set_ord` ASC';
	$sort = 'Invoiced Date ASC';
}elseif($invoice_sort == 'prj_num_asc'){
	$order_q = '  GROUP BY `invoice`.`invoice_id`  ORDER BY `invoice`.`project_id` ASC';
	$sort = 'Project Number Asc';
}elseif($invoice_sort == 'prj_num_desc'){
	$order_q = '  GROUP BY `invoice`.`invoice_id`  ORDER BY `invoice`.`project_id` DESC';
	$sort = 'Project Number Desc';
}elseif($invoice_sort == 'invcing_d_desc'){
	$order_q = 'ORDER BY `unix_invoice_date_req` DESC';
	$sort = 'Invoicing Date Desc';
}elseif($invoice_sort == 'invcing_d_asc'){
	$order_q = 'ORDER BY `unix_invoice_date_req` ASC';
	$sort = 'Invoicing Date Asc';
}else { }

$has_where = '';
if($project_num_q != '' || $progress_claim_q != '' || $client_q != '' || $invoice_status_q != '' || $project_manager_q != ''){
	$has_where = 'WHERE';
}


$table_q = $this->reports_m->select_list_invoice($has_where,$project_num_q,$client_q,$invoice_status_q.' '.$date_sort_filter ,$progress_claim_q,$project_manager_q,$order_q);





//$content .="-- -$date_sort_filter";


$records_num = 0;

$content .= '<div class="def_page"><div class="clearfix header"><img src="./img/focus-logo-print.png" align="left" class="block" style="margin-top:-30px; " /><h1 class="text-right block"  style="margin-top:-10px; margin-bottom:10px;" ><br />'.$status_filter.' List Report</h1></div><br />';
$content .= '<table id="" class="table table-striped table-bordered" cellspacing="0" width="100%"><thead><tr><th width="20%">Client Name</th><th width="20%">Project Name</th><th>Project No</th>';

if($inv_date_type == 2){
	$content .= '<th>Invoiced Date</th>';

}else{
	$content .= '<th>Invoicing Date</th>';

}




$content .= '<th>Finish Date</th><th>Progress Claim</th><th>Percent</th><th>Amount</th><th>Outstanding</th></tr></thead><tbody>';


		//var_dump($_POST['ajax_var']);

$total_project_value = 0;
$total_outstading_value = 0;


if($doc_type == 'pdf'){

foreach ($table_q->result() as $row){


				$project_total_values = $this->projects->fetch_project_totals($row->invoice_project_id);

				if($row->label == 'VR'){
					$progress_order = 'Variation';
				}elseif($row->label != 'VR' && $row->label != ''){
					$progress_order = $row->label;
				}else{
					$progress_order = $row->invoice_project_id.'P'.$row->order_invoice;				
				}


				if($progress_order == 'Variation'){
					//$project_total_percent = $project_total_values['variation_total'];
					$project_total_percent = $row->variation_total;
				}else{

					if($row->is_paid == 1 ){

						$project_total_percent = $row->invoiced_amount;

					}else{

						$project_total_percent = $row->project_total * ($row->progress_percent/100);

					}



				}

				$outstanding = $this->invoice->get_current_balance($row->invoice_project_id,$row->invoice_id,$project_total_percent);

				if( $row->is_invoiced == '0'){
					$outstanding = '0.00';
				}

				$total_project_value = $total_project_value + $project_total_percent;
				$total_outstading_value = $outstanding + $total_outstading_value;

				$project_total_percent = number_format($project_total_percent,2);
				$outstanding = number_format($outstanding,2);

					$content .= '<tr><td>'.$row->company_name.'</td><td>'.$row->project_name.'</td><td>'.$row->invoice_project_id.'</td>';

					if($inv_date_type == 2){
						$content .= '<td>'.$row->set_invoice_date.'</td>';
					}else{
						$content .= '<td>'.$row->invoice_date_req.'</td>';
					}

					$content .= '<td>'.$row->date_site_finish.'</td><td>'.$progress_order.'</td><td>'.number_format($row->progress_percent,2).'</td><td>'.$project_total_percent.'</td><td>'.$outstanding.'</td></tr>';
				
				$records_num++;

//}


		//	}


	//	}
	//}

}	

$content .= '</tbody></table>';

$content .= '<hr /><p style="margin-top:10px;"><strong>Project Manager:</strong> '.$project_manager_filter.' &nbsp;  &nbsp;  &nbsp; <strong> Note:</strong> All Prices are EX-GST &nbsp;  &nbsp;  &nbsp; <strong>Project Total Ex-GST:</strong> $'.number_format($total_project_value,2).'  &nbsp;  &nbsp;  &nbsp; <strong>Outstading Ex-GST:</strong> $'.number_format($total_outstading_value,2).'   <br /></p><br />';







$content .= '<div class="footer"><p>Applied Filters &nbsp; &nbsp; &nbsp; &nbsp; ';
$content .= '<strong> Client :</strong> '.$client_filter.' | ';
$content .= '<strong> Project :</strong> '.$project_num_filter.' | ';

if($date_filter_a != '' || $date_filter_b != ''){
	$content .= '<strong> Date Filter :</strong> '.$date_filter_a.' - '.$date_filter_b.' |';
}


$content .= '<strong> Invoice Status :</strong> '.$status_filter.' |';
$content .= '<strong> Sort :</strong> '.$sort.' |';
$content .= '<strong> Records Found :</strong> '.$records_num.'</p></div></div>';


	$my_pdf = $this->html_form($content,'landscape','A4','invoices','temp');
	echo $my_pdf;
}else{

$content = "client_name,project_name,project_no,invoiced_date,finish_date,progress_claim,percent,amount,outstanding\n";


foreach ($table_q->result() as $row){


$project_total_values = $this->projects->fetch_project_totals($row->invoice_project_id);

if($row->label == 'VR'){
$progress_order = 'Variation';
}elseif($row->label != 'VR' && $row->label != ''){
$progress_order = $row->label;
}else{
$progress_order = $row->invoice_project_id.'P'.$row->order_invoice;				
}


if($progress_order == 'Variation'){
//$project_total_percent = $project_total_values['variation_total'];
$project_total_percent = $row->variation_total;
}else{

if($row->is_paid == 1 ){

$project_total_percent = $row->invoiced_amount;

}else{

$project_total_percent = $row->project_total * ($row->progress_percent/100);

}



}

$outstanding = $this->invoice->get_current_balance($row->invoice_project_id,$row->invoice_id,$project_total_percent);

if( $row->is_invoiced == '0'){
$outstanding = '0.00';
}

$total_project_value = $total_project_value + $project_total_percent;
$total_outstading_value = $outstanding + $total_outstading_value;

//$project_total_percent = number_format($project_total_percent,2);
//$outstanding = number_format($outstanding,2);

$content .=  str_replace(',', ' ', $row->company_name).','. str_replace(',', ' ', $row->project_name).','.$row->invoice_project_id.',';

if($inv_date_type == 2){
$content .= $row->set_invoice_date.',';
}else{
$content .= $row->invoice_date_req.',';
}

$content .= $row->date_site_finish.','.$progress_order.','.$row->progress_percent.','.$project_total_percent.','.$outstanding."\n";


}	

$log_time = time();

$fname = strtolower( str_replace(' ','_', $status_filter)  );

$name = $fname.'_'.$log_time.'.csv';
force_download($name, $content,TRUE);
}


      	//echo "$has_where - $project_num_q - $progress_claim_q - $order_q";

}

























public function purchase_order_report(){

	if(isset($_GET['ajax_var'])){
		$data_val = explode('*',$_GET['ajax_var']);
	}else{
		$data_val = explode('*',$_POST['ajax_var']);
	}

	$content = '';
	$pdf_content = '';
	$csv_content = '';
	$total_project_value = 0;
	
	$current_date = date("d/m/Y");

	$project_manager 	= $data_val['0'];
	$status 			= $data_val['1'];
	$cpo_date_a 		= $data_val['2'];
	$cpo_date_b 		= $data_val['3'];

	$reconciled_date_a 	= $data_val['4'];
	$reconciled_date_b 	= $data_val['5'];
	$doc_type 			= $data_val['6'];
	$po_sort 			= $data_val['7'];
	$focus_company		= $data_val['8'];
	$for_myob			= $data_val['9'];
	$include_duplicate  = $data_val['10'];


	if($cpo_date_a == ''){
		$cpo_date_a = '01/01/2000';
	}

	if($cpo_date_b == ''){
		$cpo_date_b = $current_date;
	}


	if($status == 1){
		$status_filter = 'Reconciled';
	}else{
		$status_filter = 'Outstanding';
	}


	$focus_company_data = explode('|', $focus_company);
	$focus_company_filter = $focus_company_data[0];
	$focus_company_id = $focus_company_data[1];

	$pm_data = explode('|', $project_manager);
	$project_manager_filter = $pm_data[0];
	$date_filter_a = '';
	$date_filter_b = '';

	switch ($po_sort) {
		case "clnt_asc":
		$order = ' ORDER BY `company_details`.`company_name` ASC ';
		$sort = 'Company Name A-Z';
		break;
		case "clnt_desc":
		$order = ' ORDER BY `company_details`.`company_name` DESC ';
		$sort = 'Company Name Z-A';
		break;
		case "cpo_d_asc":
		$order = ' ORDER BY `unix_work_cpo_date` ASC ';
		$sort = 'CPO Date ASC';
		break;
		case "cpo_d_desc":
		$order = ' ORDER BY `unix_work_cpo_date` DESC ';
		$sort = 'CPO Date DESC';
		break;
		case "prj_num_asc":
		$order = ' ORDER BY `works`.`project_id` ASC ';
		$sort = 'Project ID ASC';
		break;
		case "prj_num_desc":
		$order = ' ORDER BY `works`.`project_id` DESC ';
		$sort = 'Project ID DESC';
		break;
		case "reconciled_d_asc":
		$order = ' ORDER BY `unix_reconciled_date` ASC ';
		$sort = 'Reconciled Date ASC';
		break;
		case "reconciled_d_desc":
		$order = ' ORDER BY `unix_reconciled_date` DESC ';
		$sort = 'Reconciled Date DESC';
		break;
		default:
		$order = ' ORDER BY `company_details`.`company_name` ASC ';
		$sort = 'Company Name A-Z';
	}

	if($cpo_date_a != '' && $cpo_date_b != ''){
		$cpo_date = "
		AND UNIX_TIMESTAMP( STR_TO_DATE(`works`.`work_cpo_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$cpo_date_a', '%d/%m/%Y') ) 
		AND UNIX_TIMESTAMP( STR_TO_DATE(`works`.`work_cpo_date`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$cpo_date_b', '%d/%m/%Y') ) 
		";
		$date_filter_a = " [CPO Date] $cpo_date_a-$cpo_date_b ";
	}else{
		$cpo_date = '';
	}

	if($reconciled_date_a != '' && $reconciled_date_b != ''){
		$reconciled_date = "
		AND UNIX_TIMESTAMP( STR_TO_DATE(`works`.`reconciled_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$reconciled_date_a', '%d/%m/%Y') ) 
		AND UNIX_TIMESTAMP( STR_TO_DATE(`works`.`reconciled_date`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$reconciled_date_b', '%d/%m/%Y') ) 
		";
		$date_filter_b = " [Reconciled Date] $reconciled_date_a-$reconciled_date_b ";
	}else{
		$reconciled_date = '';
	}


	$table_q = $this->reports_m->select_po_data($status,$pm_data[1],$cpo_date,$reconciled_date,$focus_company_id,$order,$for_myob);
	$records_num = 0;


//echo "$status,$pm_data[1],$cpo_date,$reconciled_date,$focus_company_id,$order,$for_myob,$include_duplicate";




	$content .= '<div class="def_page"><div class="clearfix header"><img src="./img/focus-logo-print.png" align="left" class="block" style="margin-top:-30px; " /><h1 class="text-right block"  style="margin-top:-10px; margin-bottom:10px;" ><br />'.$status_filter.' List Report</h1></div><br />';
	$content .= '<table id="" class="table table-striped table-bordered" cellspacing="0" width="100%"><thead><tr>';
	$content .= '<th width="20%">Company Name</th><th width="20%">MYOB Name</th><th>PO Number</th><th>Price</th><th>Project ID</th><th>CPO Date</th><th>Reconciled</th><th>Start Date</th><th>Finished Date</th></tr></thead><tbody>';





	//$csv_content .= '^Co./Last Name,First Name,Inclusive,Purchase No,Date,Supplier Invoice No,Delivery Status,Item Number,Quantity,Description,Price,Job,Purchase Status,Order,^Card ID'."\n";


	//$csv_content .= 'Co./Last Name,First Name,Addr 1 - Line 1,Addr 1 - Line 2,Addr 1 - Line 3,Addr 1 - Line 4,Inclusive,Invoice No.,Date,Customer PO,Ship Via,Delivery Status,Item Number,Quantity,Description,Price,Discount,Total,Job,Comment,Journal Memo,Salesperson Last Name,Salesperson First Name,Shipping Date,Referral Source,Tax Code,Tax Amount,Freight Amount,Freight Tax Code,Freight Tax Amount,Sale Status,Terms - Payment is Due,           - Discount Days,           - Balance Due Days,           - % Discount,           - % Monthly Charge,Amount Paid,Payment Method,Payment Notes,Name on Card,Card Number,Authorisation Code,BSB,Account Number,Drawer/Account Name,Cheque Number,Category,Card ID,Record ID,Purchase Status,Order'."\n";
 
	if($for_myob == 1){
		//$csv_content .= '{}'."\n";
		
//Co./Last Name	Inclusive	Purchase No.	Date	Supplier Invoice No.	Delivery Status	Item Number	Quantity	Price	Total	Job	Tax Code	Tax Amount	Purchase Status	Order
	 $csv_content .= 'Co./Last Name,Inclusive,Purchase #,Date,Supplier Invoice #,Delivery Status,Item Number,Quantity,Price,Total,Job,Tax Code,Tax Amount,Purchase Status,Order'."\n";


		//$csv_content .= 'Co./Last Name,First Name,Addr 1 - Line 1,Addr 1 - Line 2,Addr 1 - Line 3,Addr 1 - Line 4,Inclusive,Purchase No.,Date,Supplier Invoice No.,Ship Via,Delivery Status,Item Number,Quantity,Description,Price,Discount,Total,Job,Comment,Journal Memo,Shipping Date,Tax Code,Tax Amount,Freight Amount,Freight Tax Code,Freight Tax Amount,Purchase Status,  - Payment is Due,'."  - Discount Days".','."  - Balance Due Days".',  - % Discount,Amount Paid,Category,Order,Received,Billed,Card ID,Record ID'."\n";
	}else{

		$csv_content .= 'company_name,myob_name,po_number,price,project_id,work_cpo_date,reconciled_date,date_site_commencement,date_site_finish'."\n"; 
	}


//	$csv_content .= 'Co./Last Name,First Name,Addr 1 - Line 1,Addr 1 - Line 2,Addr 1 - Line 3,Addr 1 - Line 4,PO_Number,Price,Project_Number,CPO_Date,Reconciled_Date,Site_Start_Date,Site_Finish_Date,Report_Date_Made'."\n";
	
	//include_duplicate

 

	foreach ($table_q->result() as $row){



		if($for_myob == 1){


		if(  ($include_duplicate == 1 && $row->po_set_report_date != '')  || $row->po_set_report_date == '' ){

			$tax_amount = $row->price*0.1;



				$csv_content .= "\"$row->myob_name\"".',X,'."\"00$row->works_id\"".','.$row->work_cpo_date.','.$row->project_id.',A,'.$row->myob_item_name.',1,"$'.$row->price.'","$'.$row->price.'",'.$row->project_id.',GST,"$'.$tax_amount.'",o,1'."\n";
				$csv_content .= ',,,,,,,,,,,,,,'."\n";


}
		}else{

 

			if($doc_type == 'pdf'){

				$pdf_content .= '<tr><td>'.$row->company_name.'</td><td>'.$row->myob_name.'</td>';

				if($row->po_set_report_date != ''){
					$pdf_content .= '<td style="color:green!important;font-weight:700!important;">'.$row->works_id.'</td>';
				}else{
					$pdf_content .= '<td>'.$row->works_id.'</td>';
				}

				$pdf_content .= '<td>'. number_format($row->price,2).'</td>';
				$pdf_content .= '<td>'.$row->project_id.'</td><td>'.$row->work_cpo_date.'</td><td>'.$row->reconciled_date.'</td><td>'.$row->date_site_commencement.'</td><td>'.$row->date_site_finish.'</td></tr>';

			}else{


			//	$csv_content .= '<td>'.$row->project_id.'</td><td>'.$row->work_cpo_date.'</td><td>'.$row->reconciled_date.'</td><td>'.$row->date_site_commencement.'</td><td>'.$row->date_site_finish.'</td></tr>';

				$csv_content .= "\"$row->company_name\"".','."\"$row->myob_name\"".','.$row->works_id.','.$row->price.','.$row->project_id.','.$row->work_cpo_date.','.$row->reconciled_date.','.$row->date_site_commencement.','.$row->date_site_finish."\n";


				// ',,,,,,X,"'.$row->works_id.'","'.$row->work_cpo_date.'","'.$row->project_id.'",Australia Post,A,CAT_NOS,1,,"'.$row->price.','.$row->project_id.',,,,,,,,,O,,,,,,,1,,,,'."\n";
	 

	 
				// $csv_content .= "\"$row->myob_name\"".',,,,,,X,"'.$row->works_id.'","'.$row->work_cpo_date.'","'.$row->project_id.'",Australia Post,A,"'.$row->myob_item_name.'",1,"';

				// if($row->myob_item_name == 'MISC'){
				// 	$csv_content .= $row->other_work_desc;
				// }else{
				// 	$csv_content .= '';
				// }

				// $csv_content .='","$'.$row->price.'",,,"'.$row->project_id.'",,,,,,,,,,O,,,,,,1,,,,'."\n";

				//$csv_content .= "\"$row->myob_name\"".',,X,'.$row->works_id.',,"'.$row->project_id.'",A,,1,,'.$row->price.',,O,1,'."\n";
			}

			$total_project_value = $total_project_value + round($row->price,2);
			$records_num++;

		}

	}

	if($doc_type == 'pdf'){
		$content .=	$pdf_content;
	}

	$content .= '</tbody></table>';
	$content .= '<hr /><p style="margin-top:10px;"><strong>Project Manager:</strong> '.$project_manager_filter.'  &nbsp;  &nbsp;  &nbsp;  <strong>Project Total Ex-GST:</strong> $'.number_format($total_project_value,2).'  &nbsp;  &nbsp;  &nbsp; <strong> Note:</strong> All Prices are EX-GST, PO Number is <span style="color:green!important;font-weight:700!important;">GREEN</span> if report is already made. <br /></p><br />';
	$content .= '<div class="footer"><p>Applied Filters &nbsp; &nbsp; &nbsp; &nbsp; ';
	$content .= '<strong> Focus Company :</strong> '.$focus_company_filter.' |';
	$content .= '<strong> Date Filter :</strong> '.$date_filter_a.'  '.$date_filter_b.' |';
	$content .= '<strong> Status :</strong> '.$status_filter.' |';
	$content .= '<strong> Sort :</strong> '.$sort.' |';
	$content .= '<strong> Records Found :</strong> '.$records_num.'</p></div></div>';



 

	if($doc_type == 'pdf'){

		$my_pdf = $this->html_form($content,'landscape','A4','invoices','temp');
		echo $my_pdf;
	}else{
		$content = $csv_content;
		$log_time = time();
		$fname = strtolower( str_replace(' ','_', $status_filter)  );
		$name = $fname.'_'.$log_time.'.txt';
		force_download($name, $content,TRUE);
	}

}










public function myob_names(){


	$content = '';
	$table_q = $this->reports_m->select_myob_names();





	$content .= 'company_name,myob_name,abn'."\n";

	foreach ($table_q->result() as $row){
	//	$content .= $row->company_name.','.$row->myob_name."\n";


		$format_abn = $row->abn;
		$format_abn = trim(str_replace(' ', '', $format_abn)); 

		$data_abn = substr($format_abn,0,2)." ".substr($format_abn,2,3)." ".substr($format_abn,5,3)." ".substr($format_abn,8,3); 


		$content .= "\"$row->company_name\"".','."\"$row->myob_name\"".','."\"$data_abn\""."\n";
	}


	//	$content = $csv_content;
		$log_time = time();
	//	$fname = strtolower( str_replace(' ','_', $status_filter)  );
		$name = 'company_myob_names_'.$log_time.'.csv';
		force_download($name, $content,TRUE);

	

}



public function process_wip_review_uni($pm_id){

		$static_defaults_q = $this->user_model->select_static_defaults();
		$static_defaults = array_shift($static_defaults_q->result() ) ;
		$day_revew_req = $static_defaults->prj_review_day;	
		$row_stat = '';	


		$q_prj_rvw_data = $this->projects_m->get_project_rev_data($row['project_id'], $deadline_day);
		$has_logged_rvw = $q_prj_rvw_data->num_rows;
		$prj_rvw_data = array_shift($q_prj_rvw_data->result_array());


		//$timestamp_day_revuew_req =  strtotime("$day_revew_req this week");

		$content = '';

		$timestamp_day_revuew_req = (int)strtotime("$day_revew_req this week");
		$monday_revuew_req = (int)strtotime("Monday this week");
		$friday_revuew_req = (int)strtotime("Friday this week");
		$today_rvw_mrkr = (int)strtotime("Today");


		$timestamp_lwk_revuew_req = (int)strtotime("$day_revew_req last week");
		$timestamp_nxt_revuew_req = (int)strtotime("$day_revew_req next week");

		$total_project_value = 0;
		$total_outstading_value = 0;
		$content = '';
		$inv_date_type = '';
		$records_num = '';	
		$current_day = date('d/m/Y');

		$extra_query = '';
		
		$extra_query .= " AND `project`.`project_manager_id` = '$pm_id' ";

		$extra_query .= " AND `project`.`job_type` != 'Company' ";


		$order_q = " ORDER BY UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) ASC ";
		$has_where = " WHERE  `invoice`.`is_invoiced` = '0' AND `invoice`.`is_paid` = '0'     AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$current_day', '%d/%m/%Y') ) 		$extra_query		";
		$table_q = $this->reports_m->select_list_invoice($has_where,'','','','','',$order_q);

		foreach ($table_q->result() as $row){

			if($row->label == 'VR'){
				$progress_order = 'Variation';
			}elseif($row->label != 'VR' && $row->label != ''){
				$progress_order = $row->label;
			}else{
				$progress_order = $row->invoice_project_id.'P'.$row->order_invoice;				
			}

			if($progress_order == 'Variation'){ 
				$project_total_percent = $row->variation_total;
			}else{

				if($row->is_paid == 1 ){
					$project_total_percent = $row->invoiced_amount;
				}else{
					$project_total_percent = $row->project_total * ($row->progress_percent/100);
				}
			}

			$project_total_percent = number_format($project_total_percent,2);

/*
			if($timestamp_lwk_revuew_req < $today_rvw_mrkr &&   $today_rvw_mrkr <= $timestamp_day_revuew_req ){

				if( $timestamp_lwk_revuew_req < $row->unix_review_date && $row->unix_review_date <= $timestamp_day_revuew_req  ){
					$row_stat = 'posted_rev';
				} else{
					$row_stat = 'needed_rev';
				}

			}else{

				if( $timestamp_day_revuew_req <  $row->unix_review_date && $row->unix_review_date <= $timestamp_nxt_revuew_req  ){
					$row_stat = 'posted_rev';
				} else{
					$row_stat = 'needed_rev';
				}

			}
*/

			if($has_logged_rvw == 0){
				$row_stat = 'needed_rev';
			}else{
				if($timestamp_lwk_revuew_req < $row->unix_review_date && $row->unix_review_date <= $timestamp_day_revuew_req ){
					$row_stat = 'posted_rev';
				}else{
					$row_stat = 'needed_rev';
				}
			}

			$content .= '<tr class="prj_rvw_rw '.$row_stat.'" id="'.$row->invoice_project_id.'-uninvoiced_prj_view" >';
			$content .= '<td><strong>'.$row->date_site_finish.'</strong></td>';
			$content .= '<td><strong>'.$row->company_name.'</strong></td>';

			//echo '<td><strong class="prj_id_rvw">'.$row->invoice_project_id.'</strong> - '.$row->project_name.'</td>';

			$content .= '<td>
				<div class=" btn btn-sm btn-success view_notes_prjrvw" style="padding: 4px;"><i class="fa fa-book"></i></div>
				<a href="'.base_url().'projects/update_project_details/'.$row->invoice_project_id.'?status_rvwprj=uninvoiced" ><strong class="prj_id_rvw">'.$row->invoice_project_id.'</strong> - '.$row->project_name.'</a>
			</td>';


			$content .= '<td><strong class="unset">'.$row->invoice_date_req.'</strong></td>';
			$content .= '<td>'.$progress_order.'</td><td>'.number_format($row->progress_percent,2).'</td><td>'.$project_total_percent.'</td><td class="rw_pm_slct hide">pm_'.$row->project_manager_id.'</td></tr>';
		}	
return $content;


}

public function process_wip_review_qw($state,$pm_id){

	// display_all_projects($stat,$is_wpev = 0,$custom_q = '')
	// echo $this->projects->display_all_projects('wip',1); 

//	echo "<tr><td>$pm_id)</td></tr>";


	$row_stat = '';

	$static_defaults_q = $this->user_model->select_static_defaults();
	$static_defaults = array_shift($static_defaults_q->result() ) ;

	$day_revew_req = $static_defaults->prj_review_day;

	$timestamp_day_revuew_req = (int)strtotime("$day_revew_req this week");
	$timestamp_lwk_revuew_req = (int)strtotime("$day_revew_req last week");
	$timestamp_nxt_revuew_req = (int)strtotime("$day_revew_req next week");
	$monday_revuew_req = (int)strtotime("Monday this week");
	$friday_revuew_req = (int)strtotime("Friday this week");
	$today_rvw_mrkr = (int)strtotime("Today");
	$deadline_day = date('d/m/Y',$timestamp_day_revuew_req);



	$content = '';
	$extra_query = '';
	$extra_order = '';

	$admin_defaults = $this->admin_m->fetch_admin_defaults(); //1
	foreach ($admin_defaults->result() as $row){
		$unaccepted_date_categories = $row->unaccepted_date_categories;
		$unaccepted_no_days = $row->unaccepted_no_days;
	}
 
	if($state == 'unset' || $state == 'quote'){
		$extra_query = ' AND `project`.`job_date` = ""  ';
		$extra_order = " ORDER BY    UNIX_TIMESTAMP( STR_TO_DATE(`project`.`quote_deadline_date`, '%d/%m/%Y') ) ASC ";
	}else{
		$extra_query = 'AND `project`.`job_date` != ""  AND `project`.`is_paid` = "0"   '; 
		$extra_order = ' ORDER BY `unix_date_site_finish`  ASC ';
	}

	$extra_query .= " AND `project`.`job_type` != 'Company' ";
	$extra_query .= " AND `project`.`project_manager_id` = '$pm_id' /*AND `project_wip_review`.`current_dead_line` = '$deadline_day'  */";
	$extra_join = " /* LEFT JOIN `project_wip_review` ON `project_wip_review`.`project_id` = `project`.`project_id`  */";

			

	$proj_t = $this->projects_m->display_all_projects($extra_query,$extra_order,$extra_join);
	$rowcount = $proj_t->num_rows();
	//$this->load->view('tables_projects',$data);




//$content .= '<tr><td>'.var_dump($proj_t).'</td></tr>';


	//echo strtotime("Thursday this week")."<p><br /></p>";
 
	$today_date =  strtotime(date('Y-m-d'));

	$is_restricted = 0;
	foreach ($proj_t->result_array() as $row){

		$q_prj_rvw_data = $this->projects_m->get_project_rev_data($row['project_id'], $deadline_day);
		$has_logged_rvw = $q_prj_rvw_data->num_rows;
		$prj_rvw_data = array_shift($q_prj_rvw_data->result_array());


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

		if($row['job_date'] != '' ){ $status = 'wip'; }
		if($this->invoice->if_invoiced_all($row['project_id'])  && $this->invoice->if_has_invoice($row['project_id']) > 0 ){ $status = 'invoiced'; }
		if($row['is_paid'] == 1){ $status = 'paid'; }

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


//	$content .= '<tr><td>'.$status.'</td></tr>';


		if($status != 'unset' && $status != 'paid'  && $status != 'invoiced'){
			
		//	$total_invoiced_init = $this->invoice->get_project_invoiced($row['project_id'],$row['project_total'],$row['variation_total']);

			$row_stat = 'needed_rev';

			if($has_logged_rvw == 0){
				$row_stat = 'needed_rev';
			}else{
				if($timestamp_lwk_revuew_req < $row['unix_review_date'] && $row['unix_review_date'] <= $timestamp_day_revuew_req ){
					$row_stat = 'posted_rev';
				}else{
					$row_stat = 'needed_rev';
				}
			}


			
/*
			else{

				if( $timestamp_day_revuew_req <  $row['unix_review_date'] && $row['unix_review_date'] <= $timestamp_nxt_revuew_req  ){
					$row_stat = 'posted_rev';
				} else{
					$row_stat = 'needed_rev';
				}

			}
*/
			$content .= '<tr class="'.$status.' prj_rvw_rw '.$row_stat.'"  id="'.$row['project_id'].'-'.$status.'_prj_view" >';


			$site_finish_tmspt = strtotime(date_format(date_create_from_format('d/m/Y', $row['date_site_finish']), 'Y-m-d' ));

			if($site_finish_tmspt < $today_date){
				$content .= '<td><strong class="unset">'.$row['date_site_finish'].'</strong></td>';
			}else{
				$content .= '<td>'.$row['date_site_finish'].'</td>';
			}


			$content .= '<td>'.$row['date_site_commencement'].'</td>';
			$content .= '<td><strong>'.$row['company_name'].'</strong></td>';


			$content .= '<td>
			<div class=" btn btn-sm btn-success view_notes_prjrvw" style="padding: 4px;"><i class="fa fa-book"></i></div>
			<a href="'.base_url().'projects/update_project_details/'.$row['project_id'].'?status_rvwprj='.$status.'"><strong class="prj_id_rvw">'.$row['project_id'].' '.'</strong> - '.$row['project_name'].'</a>';
			$content .= '</td>';

			if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 || $row['variation_total'] > 0.00 ){
				$content .= '<td>'.number_format($row['project_total']+$row['variation_total']).'</td>';
			}else{
				$content .= '<td><strong class="green-estimate">'.number_format($row['budget_estimate_total']).'<strong></td>';
			}

			if($row['job_date'] == ''){

				$quote_dealine_tmspt = strtotime(date_format(date_create_from_format('d/m/Y', $row['quote_deadline_date']), 'Y-m-d' ));
				if($quote_dealine_tmspt < $today_date){
					$content .= '<td><strong class="unset">'.$row['quote_deadline_date'].'</strong></td>';
				}else{
					$content .= '<td>'.$row['quote_deadline_date'].'</td>';
				}

			}else{
				$content .= '<td>'.$row['job_date'].'</td>';
			}

				$total_invoiced_init = $this->invoice->get_project_invoiced($row['project_id'],$row['project_total'],$row['variation_total']);

			//$content .= '<td><strong>'.$row['install_time_hrs'].'<strong></td>';
			$content .= '<td>'.number_format($total_invoiced_init,2).'</td>';
			$content .= '</tr>';

		}

		$is_restricted = 0;
	}

	return $content;
}



public function process_wip_review(){

	$pm_arr = explode('|',$_GET['pm_id']);
	$pm_id = $pm_arr[1];

	$content = '';

	$content .= '<div class="def_page"><h1 class="block" style="margin-top:-40px; margin-bottom:10px; font-size:16px !important;" >Project Review Report</h1>';
	$content .= '<h2>WIP - '.$pm_arr[0].'</h2>';
	$content .= '<table id="" class="table table-striped table-bordered tbl-cstm" cellspacing="0" width="100%"><thead><tr>

	<th width="5%">Finish</th><th width="5%">Start</th><th width="30%">Client</th><th width="30%">Project</th><th width="5%">Total</th><th>Job Date </th><th >Invoiced $</th> 

	</tr> </thead><tbody>';				
	$content .= $this->process_wip_review_qw('wip',$pm_id);
	$content .= '</tbody></table>';

	$content .= '<p style="page-break-after: always;"></p>';
	$content .= '<h2>QUOTES - '.$pm_arr[0].'</h2>';
	$content .= '<table id="" class="table table-striped table-bordered tbl-cstm" cellspacing="0" width="100%"><thead><tr>

	<th  width="5%">Finish</th> <th  width="5%">Start</th><th width="30%">Client</th> <th  width="30%">Project</th> <th width="5%">Total</th> <th>Quote Deadline</th> <th>Invoiced $</th> 

	</tr> </thead><tbody>';				
	$content .= $this->process_wip_review_qw('quote',$pm_id);
	$content .= '</tbody></table>';





	$content .= '<p style="page-break-after: always;"></p>';
	$content .= '<h2>UN-INVOICED - '.$pm_arr[0].'</h2>';
	$content .= '<table id="" class="table table-striped table-bordered tbl-cstm" cellspacing="0" width="100%"><thead><tr>
 
    <th width="5%">Finish</th> <th  width="30%"> Client</th>  <th  width="30%">Project</th> <th width="5%">Invoicing</th> <th>Progress</th> <th width="5%">Percent</th> <th>Amount</th>  


	</tr> </thead><tbody>';				
	$content .= $this->process_wip_review_uni($pm_id);
	$content .= '</tbody></table>';




	$content .= '<style type="text/css">  table.tbl-cstm, table.tbl-cstm tr td{ font-size: 12px !important; font-weight: normal !important; text-decoration: none !important; }</style>';

	$content .= '<style type="text/css">

	table.prj_rvw_tbl{ font-size: 14px; }
	table tr.needed_rev{ background-color: #424141; color: #F7901E !important; }
	table tr.posted_rev{ background-color: #ffa9e6; }
	table tr.posted_rev td, table tr.posted_rev td a{ color: #000 !important;  text-decoration: none !important; }
	.prj_qut_rvw, .prj_qut_rvw tr td, .prj_qut_rvw tr td a { color: #F7901E  !important;  text-decoration: none !important; }
    .un_invoiced_rvw, .un_invoiced_rvw tr td, .un_invoiced_rvw tr td a { color: #F7901E  !important;  text-decoration: none !important; }

    </style>';


	$my_pdf = $this->html_form($content,'landscape','A4','wip_review','temp');
	$file_url = base_url().'docs/temp/'.$my_pdf.'.pdf';

	$file_name = $my_pdf.'.pdf';
	header('Content-Type: application/octet-stream');
	header("Content-Transfer-Encoding: Binary"); 
	header("Content-disposition: attachment; filename=\"".$file_name."\""); 
	echo file_get_contents($file_url);
	die;
}




public function print_project_amendments(){

	$content = '';

	if(isset($_GET['project_id'])  &&  $_GET['project_id']!= ''){
		$project_id = $_GET['project_id'];




		$proj_q = $this->projects_m->fetch_complete_project_details($project_id);
		$proj_details = array_shift($proj_q->result_array());

/*
		$q_focus_company = $this->company_m->display_company_detail_by_id($proj_details['focus_company_id']);
		$focus_company = array_shift($q_focus_company->result_array());
		$focus_company_name = $focus_company['company_name'];
*/
	#	$abn = $focus_company['abn'];
	#	$acn = $focus_company['acn'];





	$admin_company_details = $this->admin_m->fetch_single_company_focus($proj_details['focus_company_id']);



	$company_data = array_shift($admin_company_details->result_array() );



	$p_query_address = $this->company_m->fetch_complete_detail_address($company_data['postal_address_id']);
	$p_temp_data = array_shift($p_query_address->result_array());


	$p_po_box = $p_temp_data['po_box'];
	$p_unit_level = ucwords(strtolower($p_temp_data['unit_level']));
	$unit_number = $p_temp_data['unit_number'];
	$street= ucwords(strtolower($p_temp_data['street']));

	$p_suburb = $p_temp_data['suburb'];
	$shortname = $p_temp_data['shortname'];
	$postcode = $p_temp_data['postcode'];



	$abn = $company_data['abn'];
	$acn = $company_data['acn'];

	$focus_company_name  = $company_data['company_name'];

	$office_number = $company_data['office_number'];
	$email = $company_data['general_email'];









		$q_client_company = $this->company_m->display_company_detail_by_id($proj_details['client_id']);
		$client_company = array_shift($q_client_company->result_array());
		$client_company_name = $client_company['company_name'];

		$project_name = $proj_details['project_name'];



		//$proj_details['actions'];




		$content .= '<div class="def_page">
		<img src="./uploads/misc/focus_02595003112015.jpg" width="34%" style="margin-left:45px; margin-top:-20px;"/>

		<table style="background:#fff !important;  float:right; border: 0 !important; width:58%; text-align:left; margin-top:-20px;"bgcolor="#fff" border="0">
			<tr>
				<td width="50%" style="background:#fff !important;" ><p style="font-size:14px !important;">'.$focus_company_name.'<br />PO '.$p_po_box.' '.$p_unit_level.' '.$unit_number.' '.$street.'<br />'.ucwords(strtolower($p_suburb)).' '.$shortname.' '.$postcode.'</p></td>
				<td width="50%" style="background:#fff !important;" ><p style="font-size:14px !important;">Tel: '.$office_number.'<br />ACN: '.$acn.'<br />ABN: '.$abn.'</p></td>
			</tr>
			<tr><td colspan="2"  style="background:#fff !important;" ><p style="font-size:14px !important;"><br />E-mail: '.$email.'</p></td></tr>
		</table>';






		$content .= '<div class="" style="display: block; clear:both;  margin-top:-55px;  border: 1px solid #000; height:40px;    padding: 5px 20px;">';
		$content .= '<div class="text-left left pull-left"> <span style=" font-size:16px !important;">Client: '.$client_company_name.' <br />Project: '.$project_name.'<span> </div>';
		$content .= '<div class="text-right right pull-right"> <h1 class="" >Project Amendments</h1><span class="margin-top:-30px;" style=" font-size:14px !important;">Project ID: '.$project_id.'</span></div>';
		$content .= '</div><p style="clear:both;"><br /></p>';

		$project_comments = $this->projects_m->list_project_comments($project_id,'2','1');
		if($project_comments->num_rows > 0){

			foreach ($project_comments->result_array() as $row){

				$fetch_user= $this->user_model->fetch_user($row['user_id']);
				$user = array_shift($fetch_user->result_array());

				$content .=  '<div class=" '.($row['is_active'] == 1 ? 'active' : 'deleted' ).'   notes_line user_postby_'.strtolower( str_replace(' ', '',  $user['user_first_name']) ).' comment_type_'.$row['is_project_comments'].'">';

				if($row['is_active'] == 1 && $row['is_project_comments'] == 2){
					$content .=  '<div class="pull-right btn btn-danger view_delete btn-xs fa fa-trash" id="'.$row['project_comments_id'].'"></div>';
				}

				if($row['is_active'] == 0 && $row['is_project_comments'] == 2){
					$content .=  '<div class="pull-right btn btn-warning view_deleted btn-xs fa fa-eye-slash"> </div>';
				}

				$content .=  '<p class="">'.ucfirst (nl2br($row['project_comments'])).'</p><small><i class="fa fa-user"></i> '.$user['user_first_name'].' '.$user['user_last_name'].'<br /><i class="fa fa-calendar"></i> '.$row['date_posted'].'</small></div>';
				
			} 
		}else{
			$content .=  '<div class="notes_line no_posted_comment"><p>No posts made!</p></div>';
		}

		$content .= '<style type="text/css">  table.tbl-cstm, table.tbl-cstm tr td{ font-size: 12px !important; font-weight: normal !important; text-decoration: none !important; }</style></div>';

		$my_pdf = $this->html_form($content,'portrait','A4','project_amendments_'.$project_id,'temp');
		$file_url = base_url().'docs/temp/'.$my_pdf.'.pdf';

		$file_name = $my_pdf.'.pdf';

 

		header('Content-Type: application/octet-stream');
		header("Content-Transfer-Encoding: Binary"); 
		header("Content-disposition: attachment; filename=\"".$file_name."\""); 
		echo file_get_contents($file_url);
		die;
	}
}















public function wip_report(){

	$admin_defaults = $this->admin_m->fetch_admin_defaults(1);
	foreach ($admin_defaults->result() as $row){
		$unaccepted_date_categories = $row->unaccepted_date_categories;
		$unaccepted_no_days = $row->unaccepted_no_days;
	}


	if(isset($_GET['ajax_var'])){
		$data_val = explode('*',$_GET['ajax_var']);
	}else{
		$data_val = explode('*',$_POST['ajax_var']);
	}




	//$data_val = explode('*',$_POST['ajax_var']);
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


	$unaccepted_date_a = $data_val['17'];	
	$unaccepted_date_b = $data_val['18'];	

	$output_file = $data_val['19'];
	$csv_content = '';


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
	if (is_numeric($wip_client)) {
		if($wip_client != ''){
			$wip_client_q = 'AND `company_details`.`company_id` =  \''.$wip_client.'\'';
			$wip_client_filter = ' ID: '.$wip_client;
		}else{
			$wip_client_filter = 'All Clients';
		}
	}else{
		if($wip_client != ''){
			$wip_client_q = 'AND `company_details`.`company_name` =  \''.$wip_client.'\'';
			$wip_client_filter = $wip_client;
		}else{
			$wip_client_filter = 'All Clients';
		}
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
	$sort = 'Start Date Ascending';
}elseif($wip_sort == 'srtrt_d_desc'){
	$order_q = 'ORDER BY `start_date_filter_mod` DESC';
	$sort = 'Start Date Descending';
}elseif($wip_sort == 'prj_num_asc'){
	$order_q = 'ORDER BY `project`.`project_id` ASC';
	$sort = 'Project Number Asc';
}elseif($wip_sort == 'prj_num_desc'){
	$order_q = 'ORDER BY `project`.`project_id` DESC';
	$sort = 'Project Number Desc';
}elseif($wip_sort == 'qte_num_asc'){
	$order_q = 'ORDER BY `unix_quote_deadline_date` ASC';
	$sort = 'Quote Deadline Asc';
}elseif($wip_sort == 'qte_num_desc'){
	$order_q = 'ORDER BY `unix_quote_deadline_date` DESC';
	$sort = 'Quote Deadline Desc';
}else { $order_q = ''; }

$type = '';

if($doc_type == 'WIP'){
	$type = ' AND `project`.`job_date` <> \'\' ';
	$file_prefix = 'wip';
}else{
	$file_prefix = 'project';
			//$type = '';
}

$status = '';
$show_invoiced = 0;
$show_un_invoiced = 0;
$page_type = '';


if($prj_status != ''){

	$status_list = explode(',',$prj_status);
	$prj_status_arr_count = count($status_list);


/*
	selected
	unset
*/
//if($prj_status == 'notwip'){

	if($prj_status == 'quote' || $prj_status == 'unaccepted'){
		$doc_type = ucfirst($prj_status);

		$status .= ' AND `project`.`is_paid` = \'0\' ';
		$status .= ' AND `project`.`job_date` = \'\' ';
		$show_invoiced = 0;
		$show_un_invoiced = 1;

	}elseif($prj_status == 'wip'){
		$doc_type = 'WIP Projects';
		$show_invoiced = 0;

		$show_un_invoiced = 1;

		$status .= ' AND `project`.`is_paid` = \'0\' ';
		$status .= ' AND `project`.`job_date` <> \'\' ';

	}elseif($prj_status == 'invoiced'){
		$doc_type = 'Invoiced Projects';
		$status .= ' AND `project`.`job_date` <> \'\' ';
		$show_invoiced = 1;

	}elseif($prj_status == 'paid'){
		$doc_type = 'Paid Projects';

		$status .= ' AND `project`.`is_paid` = \'1\' ';
		$status .= ' AND `project`.`job_date` <> \'\' ';
		$show_invoiced = 1;

	}else{
		$doc_type = '';
	}

	if($prj_status == 'unaccepted'){

		$status .= " AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`unaccepted_date`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('$unaccepted_date_a', '%d/%m/%Y') ) 
			AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`unaccepted_date`, '%d/%m/%Y') ) < UNIX_TIMESTAMP( STR_TO_DATE('$unaccepted_date_b', '%d/%m/%Y') ) ";
	}


/*

unaccepted_date_a
unaccepted_date_b


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
*/

	/*		if (!in_array("wip", $status_list) && !in_array("paid", $status_list) ){
				$status = ' AND `project`.`job_date` <> \'\' AND `project`.`is_paid` = \'1\'  ';
			}
*/







			if (in_array("wip", $status_list) || in_array("notwip", $status_list) ){
			// 	$show_un_invoiced = 1;
			}

			if (in_array("paid", $status_list) || in_array("invoiced", $status_list) ){
			//	$show_invoiced = 1;
			}
			

/*
			if($prj_status_arr_count == 4 || $prj_status_arr_count == 0 ){
				$status = '';
				$show_invoiced = 1;
				$show_un_invoiced = 1;
				$page_type = '';
			}
			*/


		}else{

			/*
			$status = '';
			$show_invoiced = 1;
			$show_un_invoiced = 1;
			$page_type = '';

			*/
		}

		$change_date_type = '';

		if ($doc_type == 'Quote'){

			$change_date_type = 'Quote Deadline';

		} else {

			$change_date_type = 'Job Date';
		}

		$content .= '<div class="def_page"><div class="clearfix header"><img src="./img/focus-logo-print.png" align="left" class="block" style="margin-top:-30px; "  /><h1 class="text-right block" style="margin-top:-10px; margin-bottom:10px;" ><br />'.$page_type.' '.$doc_type.' List Report</h1></div><br />';
		$content .= '<table id="" class="table table-striped table-bordered" cellspacing="0" width="100%"><thead><tr><th>Finish</th><th>Start</th><th>Client</th><th>Project</th><th>Total</th><th width="150px">'.$change_date_type.'</th><th>Install Hrs</th><th>Project No</th><th>Invoiced $</th></tr></thead><tbody>';				
		//var_dump($_POST['ajax_var']);
		$wip_list_q = $this->reports_m->select_list_wip($wip_client_q,$wip_pm_q,$selected_cat_q,$order_q,$type,$status);
		$total_list = 0;
		
		$arrs = array();
		$color = '';

		$total_estimate = 0;
		$total_invoiced = 0;
		$total_quoted = 0;

		$total_invoiced_init = 0;
		$is_restricted = 0;

		$has_sched = '';
 

	$csv_content = 'site_finish,site_start,client,project,total,';

	if ($doc_type == 'Quote'){ 
		$csv_content .= 'quote_deadline_date';
	} else { 
		$csv_content .= 'job_date';
	}

	$csv_content .=',install_hrs,project_no,invoiced'."\n"; 
 
		foreach ($wip_list_q->result_array() as $row){

			if($row['job_date'] == '' ){
				$color = 'notwip';
			}


			if( isset($row['project_schedule_task_id'])     ){
				$has_sched = '<span style="color:red!important;font-weight:700!important;">*';
			}else{
				$has_sched = '<span>';
			}


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
						$status = 'quote';
					}else{
						$status = 'unaccepted';
					}
				}else{
					$status = 'unaccepted';
				}

			}else{
				if($unaccepted_date == ""){
					$status = 'quote';
				}else{
					$status = 'unaccepted';
				}

			}



			if($row['job_date'] != '' ){
				$color = 'wip';
				$status =  'wip';
			}

			if($this->invoice->if_invoiced_all($row['project_id'])  && $this->invoice->if_has_invoice($row['project_id']) > 0 ){
				$color = 'invoiced';
				$status = 'invoiced';
			}

			if($row['is_paid'] == 1){
				$color = 'paid';
				$status =  'paid';
			}


 
			$date_site_finish = $row['date_filter_mod'];
			$date_site_start = $row['start_date_filter_mod'];
			$date_project_date = strtotime(str_replace('/', '-', $row['project_date']));


		//	if(in_array($color, $status_list)){

				if($doc_type == 'WIP'){
					$color = 'wip_b';
				}

				if($date_a_s <= $date_site_start){
					if($date_site_start <= $date_b_s){

						if($date_a <= $date_site_finish){
							if($date_site_finish <= $date_b){

								if($date_c <= $date_project_date ){
									if( $date_project_date <= $date_d ){


 


										if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 || $row['variation_total'] > 0.00 ){
											$total_list = $row['project_total']+$row['variation_total'];
										}else{
											$total_list = $row['budget_estimate_total'];
										}

										if($wip_cost_total_q >= $total_list){





											if($prj_status == $status){




											if($this->invoice->if_invoiced_all($row['project_id']) && $this->invoice->if_has_invoice($row['project_id']) > 0 ){

												if($show_invoiced == 1){


													if($output_file == 'pdf'){



														$prj_total_current = $row['project_total']+$row['variation_total'];

														$content .= '<tr class="'.$color.'"><td>'.$row['date_site_finish'].'</td><td>'.$row['date_site_commencement'].'</td><td>'.$row['company_name'].'</td><td>'.$row['project_name'].'</td>';

														if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 ){

															$total_quoted = $total_quoted + $prj_total_current;
															$content .= '<td>'.number_format($prj_total_current ,2).'</td>';
														}else{

															$total_estimate = $total_estimate + $row['budget_estimate_total'];
															$content .= '<td class="green-estimate" style="color:green!important;font-weight:700!important;">'.number_format($row['budget_estimate_total'],2).'</td>';
														}

														$content .= '<td>'.$row['job_date'].'</td>';

														if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 ){
															$content .= '<td>'.number_format($row['install_time_hrs'],2).'</td>';
														}else{
															$content .= '<td class="green-estimate" style="color:green!important;font-weight:700!important;">'.number_format($row['labour_hrs_estimate'],2).'</td>';
														}


														$total_invoiced_init = $this->invoice->get_project_invoiced($row['project_id'],$row['project_total'],$row['variation_total']);
														$total_invoiced = $total_invoiced + $total_invoiced_init;

														$content .= '<td>'.$has_sched.$row['project_id'].'</span></td>';
														$content .= '<td>'.number_format($total_invoiced_init,2).'</td></tr>';			
														$records_num++; 

													}else{

														$csv_content .= $row['date_site_finish'].','.$row['date_site_commencement'].','.$row['company_name'].','.$row['project_name'].',';
	                  									$prj_total_current = $row['project_total']+$row['variation_total'];
 
														if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 ){

															$total_quoted = $total_quoted + $prj_total_current;															 
															$csv_content .= $prj_total_current;
														}else{

															$total_estimate = $total_estimate + $row['budget_estimate_total'];
															$csv_content .= $row['budget_estimate_total'];
														}

														$csv_content .= ','.$row['job_date'].',';

														if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 ){ 
															$csv_content .= $row['install_time_hrs'];
														}else{
															$csv_content .= $row['labour_hrs_estimate']; 
														}

														$total_invoiced_init = $this->invoice->get_project_invoiced($row['project_id'],$row['project_total'],$row['variation_total']);
														$total_invoiced = $total_invoiced + $total_invoiced_init;

														$csv_content .= ','.$row['project_id'].','.$total_invoiced_init."\n"; 
														$records_num++; 
													}
												}

											}else{		

												if($show_un_invoiced == 1){ 


													if($output_file == 'pdf'){

														$prj_total_current = $row['project_total']+$row['variation_total'];

														$content .= '<tr class="'.$color.'"><td>'.$row['date_site_finish'].'</td><td>'.$row['date_site_commencement'].'</td><td>'.$row['company_name'].'</td><td>'.$row['project_name'].'</td>';



														if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 || $row['variation_total'] > 0.00 ){
															$total_quoted = $total_quoted + $prj_total_current;
															$content .= '<td>'.number_format($prj_total_current,2).'</td>';
														}else{
															$total_estimate = $total_estimate + $row['budget_estimate_total'];
															$content .= '<td class="green-estimate" style="color:green!important;font-weight:700!important;">'.number_format($row['budget_estimate_total'],2).'</td>';
														}

														if ($doc_type == 'Quote'){
															$content .= '<td>'.$row['quote_deadline_date'].'</td>';
														} else {
															$content .= '<td>'.$row['job_date'].'</td>';

														}

														if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 ){
															$content .= '<td>'.number_format($row['install_time_hrs'],2).'</td>';
														}else{
															$content .= '<td class="green-estimate" style="color:green!important;font-weight:700!important;">'.number_format($row['labour_hrs_estimate'],2).'</td>';
														}



														$total_invoiced_init = $this->invoice->get_project_invoiced($row['project_id'],$row['project_total'],$row['variation_total']);
														$total_invoiced = $total_invoiced + $total_invoiced_init;


														$content .= '<td>'.$has_sched.$row['project_id'].'</span></td>';
														$content .= '<td>'.number_format($total_invoiced_init,2).'</td></tr>';	


														$records_num++;


													}else{

														$csv_content .= $row['date_site_finish'].','.$row['date_site_commencement'].','.$row['company_name'].','.$row['project_name'].',';
														$prj_total_current = $row['project_total']+$row['variation_total'];

														if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 ){

															$total_quoted = $total_quoted + $prj_total_current;															 
															$csv_content .= $prj_total_current;
														}else{

															$total_estimate = $total_estimate + $row['budget_estimate_total'];
															$csv_content .= $row['budget_estimate_total'];
														}




														if ($doc_type == 'Quote'){ 
															$csv_content .= ','.$row['quote_deadline_date'].',';
														} else { 
															$csv_content .= ','.$row['job_date'].',';
															
														}



														if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 ){ 
															$csv_content .= $row['install_time_hrs'];
														}else{
															$csv_content .= $row['labour_hrs_estimate']; 
														}

														$total_invoiced_init = $this->invoice->get_project_invoiced($row['project_id'],$row['project_total'],$row['variation_total']);
														$total_invoiced = $total_invoiced + $total_invoiced_init;

														$csv_content .= ','.$row['project_id'].','.$total_invoiced_init."\n"; 
														$records_num++; 
													}




												}

											}


}
 



										}
									}
								}
							}}
						}
					}
				}
		//	}




		//var_dump($arrs);


			$content .= '</tbody></table>';

			$arrs = implode(',', $arrs);

			$overall_project_total = $total_estimate + $total_quoted;

			$content .= '<p><br /><hr /><p><br /></p><span><strong> All Prices are EXT-GST</strong> &nbsp; &nbsp;</span> &nbsp; &nbsp;   <span><strong>Project Total:</strong> '.number_format($overall_project_total,2).'</span> &nbsp; &nbsp;   <span class="green-estimate"><strong>Total Estimated:</strong> '.number_format($total_estimate,2).'</span> &nbsp; &nbsp;   <span><strong>Total Quoted:</strong> '.number_format($total_quoted,2).'</span></span> &nbsp; &nbsp;   <span><strong>Total Invoiced:</strong> '.number_format($total_invoiced,2).'</span><br /></p><p><br /><span><strong>Project Manager:</strong> '.$wip_pm_filter.'</span> &nbsp;  &nbsp;   &nbsp;  &nbsp; <strong>Color Codes:</strong> &nbsp;  &nbsp;  <strong class="invoiced">Invoiced</strong> &nbsp;  &nbsp;  <strong class="paid">Paid</strong> &nbsp;  &nbsp;  <strong class="wip">WIP</strong> &nbsp;  &nbsp;   <span style="color:red!important;font-weight:700!important;">*Has Project Schedule</span>  </p>';
# './*$this->wip->sum_total_wip_cost($arrs)*/.'
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




			if($output_file == 'pdf'){
				  $my_pdf = $this->html_form($content,'landscape','A4',$prj_status,'temp');
				  echo $my_pdf;
			}else{
				$content = $csv_content;
				$log_time = time();
				$fname = strtolower( str_replace(' ','_', $prj_status)  );
				$name = $fname.'_'.$log_time.'.csv';
				force_download($name, $content,TRUE);
			}


			

		}
	}