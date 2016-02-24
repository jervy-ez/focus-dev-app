<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jervy Zaballa */

class Dashboard extends MY_Controller{
	
	function __construct(){
		parent::__construct();

		$this->load->module('users');
		$this->load->model('user_model');

		$this->load->module('projects');
		$this->load->model('projects_m');

		$this->load->module('admin');
		$this->load->model('admin_m');

		$this->load->model('dashboard_m');

		if(!$this->users->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;

		$this->load->library('session');

		if(isset($_GET['delete_rfc'])){
			$revenue_forecast_id = $_GET['delete_rfc'];
			$this->dashboard_m->deactivate_stored_revenue_forecast($revenue_forecast_id);
			$this->session->set_flashdata('record_update','Record is now deleted.');
			redirect('/dashboard/sales_forecast/');
		}

		if(isset($_GET['primary_rfc'])){
			$forecast =  explode('_',$_GET['primary_rfc']);

			list($id,$year) = $forecast;

			$this->dashboard_m->set_primary_revenue_forecast($id,$year);


			$this->session->set_flashdata('record_update','Record is now set to primary forecast.');
			redirect('/dashboard/sales_forecast/view_'.$id);
		}

		date_default_timezone_set("Australia/Perth");

		$this->_check_sales(); // automatically updates sales of the current month
	}	

	function index(){
		$this->users->_check_user_access('dashboard',1);
		$data['main_content'] = 'dashboard_home';
		$data['screen'] = 'Dashboard';
		$this->load->view('page', $data);
	}

	function _if_sales_changed($year,$proj_mngr_id,$focus_comp_id,$rev_month,$checkAmount){
		$q_sales = $this->dashboard_m->look_for_sales($year,$proj_mngr_id,$focus_comp_id,$rev_month);
		$rev_month = 'rev_'.strtolower(date('M'));

		$sales = array_shift($q_sales->result_array());

		if($sales[$rev_month] < $checkAmount){
			return $sales['revenue_id'];
		}elseif($sales[$rev_month] == $checkAmount){
			return 'no_change';
		}else{
			return 0;
		}
	}

	function _check_sales(){

		$currentDay = date("m");
		$currentMonth = date("d");
		$currentYear = date("Y");

		//$currentDateTmpsmpt = mktime(0, 0, 0, $currentMonth, $currentDay, $currentYear);

		$c_month = date("m");
		$c_year = date("Y");

		$n_month = date("m")+1;
		$n_year = $c_year;

		if($c_month == 12){
			$n_month = 1;
			$n_year = date("Y")+1;
		}

		$date_a_tmsp = mktime(0, 0, 0, $c_month,'01', $c_year);
		$date_b_tmsp = mktime(0, 0, 0, $n_month,'01', $n_year);

//		$date_a_tmsp = mktime(0, 0, 0, '06','01', '2015');
//		$date_b_tmsp = mktime(0, 0, 0, '07','01', '2015');

		$rev_month = 'rev_'.strtolower(date('M'));

		$q_maintenance_sales = $this->dashboard_m->get_maintenance_sales($date_a_tmsp,$date_b_tmsp);
		$maintenance_sales = $q_maintenance_sales->result();

		foreach ($maintenance_sales as $sales ){	
			$revenue_id = $this->_if_sales_changed($currentYear,$sales->user_id,$sales->focus_company_id,$rev_month,$sales->invoiced_amount);
			if($revenue_id > 0 && $revenue_id != 'no_change'){			
				$this->dashboard_m->update_sales($revenue_id,$rev_month,$sales->invoiced_amount);
			}elseif($revenue_id == 0 && $revenue_id != 'no_change'){
				$this->dashboard_m->set_sales($sales->user_id, $rev_month, $sales->invoiced_amount, $sales->focus_company_id, $c_year);
			}else{

			}
		}

		$q_pm_sales = $this->dashboard_m->get_pm_sales($date_a_tmsp,$date_b_tmsp);
		$pm_sales = $q_pm_sales->result();

		foreach ($pm_sales as $sales ){	
			$revenue_id = $this->_if_sales_changed($currentYear,$sales->user_id,$sales->focus_company_id,$rev_month,$sales->invoiced_amount);
			if($revenue_id > 0 && $revenue_id != 'no_change'){			
				$this->dashboard_m->update_sales($revenue_id,$rev_month,$sales->invoiced_amount);
			}elseif($revenue_id == 0 && $revenue_id != 'no_change'){
				$this->dashboard_m->set_sales($sales->user_id, $rev_month, $sales->invoiced_amount, $sales->focus_company_id, $c_year);
			}else{

			}
		}
	}

	function _fetch_pm_data($minYear,$maxYear){

		$project_manager_q = $this->user_model->fetch_user_by_role(3);
		$project_manager = $project_manager_q->result();


		if( date("m") > 6 ){
			// Jul-Dec current year to next year Jan-Jun
/*
			$minYear = date("Y");
			$maxYear = date("Y")+1;
*/
		}else{
			// Jul-Dec last year to Jan-Jun current year
/*
			$minYear = date("Y")-1;
			$maxYear = date("Y");
*/			
		}


		foreach ($project_manager as $row){
			if($row->user_id != '29'){
				echo "['".$row->user_first_name." ".$row->user_last_name."',";
				$pm_id = $row->user_id;


				for($i=0; $i < 12; $i++){

					if($i > 6){
						$year = $maxYear; 
						$month = $i-6;
					}else{
						$year = $minYear; 
						$month = 6+$i;
					}

					$date_a = '1-'.($month).'-'.($year);
					$date_b = '1-'.($month == 12 ? 1 : $month+1).'-'.($month == 12 ? $year+1 : $year);



					$date_a_tmsp = strtotime(str_replace('/', '-', $date_a));
					$date_b_tmsp = strtotime(str_replace('/', '-', $date_b));


					$getSales_perMonth_q = $this->dashboard_m->getSales_perMonth($pm_id,$date_a_tmsp,$date_b_tmsp);
					//$sales_perMonth = $getSales_perMonth_q->result();
					$sales_perMonth = array_shift($getSales_perMonth_q->result_array());

				//	$arr_prc = implode('', $sales_perMonth);

//var_dump($sales_perMonth);


					if($sales_perMonth){
						$arr_prc = implode('', $sales_perMonth);
						echo $arr_prc.',';
					}else{
						echo '0,';
					}




		//$data['stored_revenue_forecast'] = $stored_revenue_forecast->result();




										// echo $date_a.' ---- '.$date_b.' <br />';


				}


									//$minYear
									//$maxYear

				echo '],
				';

			}







		}


	}


	function _get_focus_splits($year,$company_id){

		$sales_focus_q = $this->dashboard_m->get_sales_focus($year);
		$sales_focus = array_shift($sales_focus_q->result_array());

		$sales_focus_comp_q = $this->dashboard_m->get_sales_focus($year,$company_id);
		$sales_focus_comp = array_shift($sales_focus_comp_q->result_array());


		if($sales_focus_comp['total_sales'] > 0){
			$shares_focus_comp =  100/($sales_focus['total_sales']/$sales_focus_comp['total_sales']);
		}else{
			$shares_focus_comp = 0;
		}

		return number_format($shares_focus_comp,2);
	}


	function _get_focus_pm_splits($year,$company_id,$pm_id){

		$sales_focus_q = $this->dashboard_m->get_sales_focus($year,$company_id);
		$sales_focus = array_shift($sales_focus_q->result_array());

		$get_sales_focus_pm_q = $this->dashboard_m->get_sales_focus_pm($year,$company_id,$pm_id);
		$get_sales_focus_pm = array_shift($get_sales_focus_pm_q->result_array());

		if($get_sales_focus_pm['total_sales'] > 0){
			$shares_focus_comp =  100/($sales_focus['total_sales']/$get_sales_focus_pm['total_sales']);
		}else{
			$shares_focus_comp = 0;
		}

		return number_format($shares_focus_comp,2);
	}

	public function sales_forecast_settings(){
		$data['main_content'] = 'sales_forecast_settings_page';
		$data['screen'] = 'Sales Forecast Settings';

		$focus = $this->admin_m->fetch_all_company_focus();
		$data['focus'] = $focus->result();

		$pm_names = $this->dashboard_m->get_pm_names();
		$data['pm_names'] = $pm_names->result();

		$this->load->view('page', $data);
	}




	public function sales_forecast(){

		$data['maintenance_id'] = 29;
		$data['tab_view'] = 'form';

		$data['main_content'] = 'sales_forecast_page';
		$data['screen'] = 'Sales Forecast';

		$focus = $this->admin_m->fetch_all_company_focus();
		$data['focus'] = $focus->result();

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$data['project_manager'] = $project_manager->result();

		$pm_names = $this->dashboard_m->get_pm_names();
		$data['pm_names'] = $pm_names->result();

		$ryear = $this->uri->segment(3);


		if(strlen($ryear)==4 && is_numeric($ryear) && $ryear!='' ){
			$data['form_toggle'] = 1;
			$old_year = $ryear-1;
			$data['ryear'] = $old_year;
		}else{
			$old_year = date("Y")-1; //once live year must be minus 1
			//$data['form_toggle'] = @$this->session->flashdata('form_toggle');
		}


		if($ryear!='' ){
			$view = explode('_', $ryear);

			if(isset($view[0]) && isset($view[1]) && is_numeric($view[1]) && strlen($view[0])==4 && $view[0] == 'view'){
				$forecast_id = $view[1];
				$data['forecast_id'] = $forecast_id;
				$data['tab_view'] = 'view';
				$data['form_toggle'] = 1;

				$saved_forecast_item = $this->dashboard_m->fetch_revenue_forecast($forecast_id);
				$data['saved_forecast_item'] = array_shift($saved_forecast_item->result_array());

				$year_id = $data['saved_forecast_item']['revenue_forecast_id'];
				$data['individual_forecast'] = $this->dashboard_m->fetch_individual_forecast($year_id);
			}


			if(isset($view[0]) && isset($view[1]) && is_numeric($view[1]) && strlen($view[0])==4 && $view[0] == 'edit'){
				$forecast_id = $view[1];
				$data['tab_view'] = 'edit';

				$saved_forecast_item = $this->dashboard_m->fetch_revenue_forecast($forecast_id);
				$data['saved_forecast_item'] = array_shift($saved_forecast_item->result_array());

				$old_year = $data['saved_forecast_item']['year']-1;

				$year_id = $data['saved_forecast_item']['revenue_forecast_id'];
				$data['individual_forecast'] = $this->dashboard_m->fetch_individual_forecast($year_id);
			}

		}




		$data['old_year'] = $old_year;
		$q_get_sales_focus_month = $this->dashboard_m->get_sales_focus_month($old_year);
		$sales_focus_month = array_shift($q_get_sales_focus_month->result_array());

		$sales_focus_q = $this->dashboard_m->get_sales_focus($old_year);
		$sales_focus = array_shift($sales_focus_q->result_array());
		//$sales_focus['total_sales'];

		$data['saved_forecast'] = $this->dashboard_m->fetch_revenue_forecast();

		$data['monthly_split'] = array();

		$current_month = date('n')-1;

		$loop_counter = 0;

		$has_zero_month = 0;

		$q_sales_focus_yearly = $this->dashboard_m->get_sales_focus_yearly($old_year);
		$sales_focus_yearly = array_shift($q_sales_focus_yearly->result_array());
		$sales_focus_yearly = $sales_focus_yearly['total_sales'];

		$q_sales_focus_yearly_older = $this->dashboard_m->get_sales_focus_yearly($old_year-1);
		$sales_focus_yearly_older = array_shift($q_sales_focus_yearly_older->result_array());
		$data['sales_focus_yearly_older'] = $sales_focus_yearly_older['total_sales'];

		$temp_total_past = 0;
		$months = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
		foreach ($sales_focus_month as $key => $value) {
			if($value <= 0 || ($loop_counter == $current_month && $old_year == date("Y") ) ){
				$has_zero_month = 1;
				$temp_total_past = $temp_total_past + $this->_check_monthly_share_adjust($old_year,'0.00',$months[$loop_counter]);
			}
			$loop_counter++;
		}

		if($has_zero_month > 0){
			$focus_total_sales = $sales_focus['total_sales'] + ($sales_focus_yearly_older['total_sales']*($temp_total_past/100) );

			$focus_total_sales = round($focus_total_sales,2);
		}else{
		//	$focus_total_sales = $sales_focus['total_sales'];
		}

		//$focus_total_sales = $sales_focus['total_sales'];
 
		$data['sales_focus_yearly'] = $focus_total_sales;
		$data['total_sales'] = $sales_focus['total_sales'];


		$loop_counter = 0;

		foreach ($sales_focus_month as $key => $value) {

			if($value > 0){
				$shares_split =  100/($focus_total_sales/$value);
			}else{
				$shares_split = '0.00';
			}

			if($loop_counter == $current_month && $old_year == date("Y")){
				$shares_split = '0.00';
			}

			$shares_split =  number_format($shares_split,2);
			array_push($data['monthly_split'], $shares_split);
			$loop_counter++;
		}

		if (!empty($_POST)) {
			$form_type = $this->input->post('form_type');

			if($form_type == 1){

				$data['tab_view'] = 'form';
				$data['form_toggle'] = 1;

				$form_result = $this->_add_data_sales_forecast($_POST);
				$data['error'] = 'Error: Please Complete the fields.';

				if(!empty($form_result)){
					$data['other'] = $form_result;
				}
			}


			if($form_type == 2){


			}

		}


		$this->load->view('page', $data);
	}

	function _check_monthly_share_adjust($year,$value,$month,$current=0,$percent=0){

		if($value == 0 ){
			$old_year = $year - 1;
			$rev_month = 'rev_'.strtolower($month);

			$q_old_month_sales = $this->dashboard_m->get_old_month_sales($rev_month,$old_year);
			$old_month_sales = array_shift($q_old_month_sales->result_array());

			$sales_focus_q = $this->dashboard_m->get_sales_focus($old_year);
			$sales_focus = array_shift($sales_focus_q->result_array());

			if($old_month_sales['sum_old_month'] > 0){
				$shares_split =  100/($sales_focus['total_sales']/$old_month_sales['sum_old_month']);
			}else{
				$shares_split = 0;
			}

			if($current>0 && $percent>0 && $shares_split>0){
				$shares_split = round($shares_split,2);
				$current = round($current,2);

				$past_amount = round($sales_focus['total_sales'],2) * ($shares_split/100);
				$past_amount = round($past_amount,2);
				$shares_split =  100/($current/$past_amount);
			}

			return number_format($shares_split,2);

		}else{
			return $value;
		}
	}

	function _clear_apost(){
		foreach ($_POST as $key => $value) {
			$_POST[$key] = str_replace("'","&apos;",$value);
		}
	}

	public function _add_data_sales_forecast($post_data){

		$has_errors = 0;
		$other_pm = array();

		foreach ($post_data as $key => $value){
			$this->form_validation->set_rules($key, $key,'trim|required|xss_clean');
			$other_smnts = explode('_', $key);
			if( isset($other_smnts[3])) {
				$index_other = '_'.$other_smnts[1].'_'.$other_smnts[2].'_';
				if($index_other == '_other_pm_'){
					$other_pm[$other_smnts[0].'_other_pmx_'.$other_smnts[3]] = $key;
				}	
			}
		}

		if($this->form_validation->run() === false){
			// form got errors neeeds all to be filled

			if(!empty($other_pm)){
				return $other_pm;
			}
		}else{
			// save the forecast

			$data_label = $this->input->post('data_label');
			$year = $this->input->post('data_year');
			$total = str_replace(',', '',$this->input->post('data_amount'));
			$months = array();

			for ($x=0; $x < 12; $x++){
				$months[$x] = str_replace(',', '',$this->input->post('month_'.$x));
			}

			//var_dump($months);

		 	list($forecast_jan, $forecast_feb, $forecast_mar, $forecast_apr, $forecast_may, $forecast_jun, $forecast_jul, $forecast_aug, $forecast_sep, $forecast_oct, $forecast_nov, $forecast_dec) = $months;
		 	$yearly_forecast_id = $this->dashboard_m->insert_revenue_forecast_y($data_label, $total, $year, $forecast_jan, $forecast_feb, $forecast_mar, $forecast_apr, $forecast_may, $forecast_jun, $forecast_jul, $forecast_aug, $forecast_sep, $forecast_oct, $forecast_nov, $forecast_dec);


		 	foreach ($_POST as $key => $value) {
		 		$focus_details = explode('_', $key);
		 		$focus_company = $focus_details[0].'_'.$focus_details[1];

		 		if($focus_company == 'focus_id'){
		 			$focus_company_id = $focus_details[2];
		 			$this->dashboard_m->insert_revenue_forecast($focus_company_id,'','', $value, $year, $yearly_forecast_id);
		 		}


		 		if(isset($focus_details[2])){
		 			$focus_pm = $focus_details[1].'_'.$focus_details[2];

		 			if($focus_pm == 'focus_pmid'){
		 				$focus_company_id = $focus_details[0];
		 				$focus_pm_id = $focus_details[3];

		 				$this->dashboard_m->insert_revenue_forecast($focus_company_id,$focus_pm_id,'', $value, $year, $yearly_forecast_id);
		 			}

		 			if($focus_pm == 'other_pm'){
		 				$focus_company_id = $focus_details[0];
		 				$other_pm = $this->input->post($focus_company_id.'_other_nm_'.$focus_details[3]);	 				

		 				$this->dashboard_m->insert_revenue_forecast($focus_company_id,'',$other_pm, $value, $year, $yearly_forecast_id);
		 			}




		 		}

		 	}


		 	$this->session->set_flashdata('save_success','Forecast Saved'); 

			redirect('/dashboard/sales_forecast', 'refresh');



			

		} 
 

	}

	public function update_sales_forecast(){

		$this->form_validation->set_rules('data_type', 'Data Type','trim|required|xss_clean');
		$this->form_validation->set_rules('data_year', 'Year','trim|required|xss_clean');
		$this->form_validation->set_rules('focus_company', 'Company','trim|required|xss_clean');
		$this->form_validation->set_rules('data_name', 'Data Name','trim|required|xss_clean');
		$this->form_validation->set_rules('data_amount', 'Total Amount','trim|required|xss_clean');

		if($this->form_validation->run() === false){
			$this->_clear_apost();
			$this->session->set_flashdata('error_add_fs', validation_errors());
			$this->session->set_flashdata('is_update','1');
		//	$this->_form_flash_data($_POST);
		}else{
			$data_type = $this->input->post('data_type');
			$data_year = $this->input->post('data_year');
			$focus_company = $this->input->post('focus_company');
			$data_name = $this->input->post('data_name');
			$data_amount = str_replace(',', '',$this->input->post('data_amount'));

			$jul = str_replace(',', '',$this->input->post('jul'));
			$aug = str_replace(',', '',$this->input->post('aug'));
			$sep = str_replace(',', '',$this->input->post('sep'));
			$oct = str_replace(',', '',$this->input->post('oct'));
			$nov = str_replace(',', '',$this->input->post('nov'));
			$dec = str_replace(',', '',$this->input->post('dec'));
			$jan = str_replace(',', '',$this->input->post('jan'));
			$feb = str_replace(',', '',$this->input->post('feb'));
			$mar = str_replace(',', '',$this->input->post('mar'));
			$apr = str_replace(',', '',$this->input->post('apr'));
			$may = str_replace(',', '',$this->input->post('may'));
			$jun = str_replace(',', '',$this->input->post('jun'));

			$revenue_forecast_id = $this->input->post('rfc_token');

			$this->session->set_flashdata('record_update','Record is now updated.');

			$this->dashboard_m->update_revenue_forecast($revenue_forecast_id,$data_type,$data_year,$focus_company,$data_name,$data_amount,$jul,$aug,$sep,$oct,$nov,$dec,$jan,$feb,$mar,$apr,$may,$jun);
		}

		redirect('/dashboard/sales_forecast', 'refresh');

	}
}