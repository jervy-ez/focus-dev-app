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

	private function pdf_create($html, $orientation='portrait', $paper='A4',$filename='report',$folder_type='general' ,$stream=TRUE){
		$dompdf = new DOMPDF();

		$dompdf->set_paper($paper,$orientation);
		$dompdf->load_html($html);
		$dompdf->render();

		$canvas = $dompdf->get_canvas();
		$date_gen = date("jS F, Y");


		$font = Font_Metrics::get_font("helvetica", "bold");
		$canvas->page_text(780,10, "Page: {PAGE_NUM} of {PAGE_COUNT} ", $font, 8, array(0,0,0));
		$canvas->page_text(20, 570, "Page: {PAGE_NUM} of {PAGE_COUNT}                   Produced: $date_gen", $font, 8, array(0,0,0));



		$output = $dompdf->output();

		$filename .= '-'.date("d-m-Y-s");


		$this->delete_dir('docs/'.$folder_type);  #remove folder and contents

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

	private function html_form($content,$orientation,$paper,$file_name,$folder){
		$document = '<!DOCTYPE html><html><head><title></title>';
		$document .= '<link type="text/css" href="'.base_url().'css/pdf.css" rel="stylesheet" />';
		$document .= '</head><body>';
		$document .= $content;
		$document .= '</body></html>';
		return $this->pdf_create($document,$orientation,$paper,$file_name,$folder);
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
			$sort = 'Company Name ASC';
		}elseif($data_val['5'] == 'cm_desc'){
			$order_q = '`company_details`.`company_name` DESC';
			$sort = 'Company Name DESC';
		}elseif($data_val['5'] == 'sub_asc'){
			$order_q = '`address_general`.`suburb` ASC';
			$sort = 'Suburb ASC';
		}elseif($data_val['5'] == 'sub_desc'){
			$order_q = '`address_general`.`suburb` DESC';
			$sort = 'Suburb DESC';
		}elseif($data_val['5'] == 'state_asc'){
			$order_q = '`states`.`id` ASC';
			$sort = 'States ASC';
		}elseif($data_val['5'] == 'state_desc'){
			$order_q = '`states`.`id` DESC';
			$sort = 'States DESC';
		}elseif($data_val['5'] == 'act_asc'){
			$order_q = '`company_details`.`activity_id` ASC';
			$sort = 'Activity ASC';
		}elseif($data_val['5'] == 'act_desc'){
			$order_q = '`company_details`.`activity_id` DESC';
			$sort = 'Activity DESC';
		}else { }


		

		//echo "$company_type,$query,$order_q";

		//echo "$company_type $query $order_q";


		$table_q = $this->reports_m->select_list_company($company_type,$query,$order_q);


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
				}				
			}

		}

		$content .= '</tbody></table>';

		$content .= '<div class="footer"><p>Applied Filters &nbsp; &nbsp; &nbsp; &nbsp; ';
		$content .= '<strong> State :</strong> '.$my_states.' | ';
		$content .= '<strong> Company Type :</strong> '.$my_company_type.' | ';
		$content .= '<strong> Activity :</strong> '.$my_activity.' |';
		$content .= '<strong> Letter Segments :</strong> '.$letter_segment_a.' - '.$letter_segment_b.' |';
		$content .= '<strong> Sort :</strong> '.$sort.'</p>';

      	$my_pdf = $this->html_form($content,'landscape','A4','companies','temp');
      	echo $my_pdf;

      }
}