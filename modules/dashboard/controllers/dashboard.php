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
		//$this->_check_next_month_sales();
	}	

	function index(){
		//$this->users->_check_user_access('dashboard',1);


		if($this->session->userdata('is_admin') != 1 ):		
			redirect('', 'refresh');
		endif;



		$data['main_content'] = 'dashboard_home';
		$data['screen'] = 'Dashboard';
		$this->load->view('page', $data);
	}

	function _if_sales_changed($year,$proj_mngr_id,$focus_comp_id,$rev_month,$checkAmount){
		//echo "-----$year,$proj_mngr_id,$focus_comp_id,$rev_month,$checkAmount----<br />";

		$q_sales = $this->dashboard_m->look_for_sales($year,$proj_mngr_id,$focus_comp_id,$rev_month);
		//$rev_month = 'rev_'.strtolower(date('M'));

		$sales = array_shift($q_sales->result_array());

		if($sales[$rev_month] != $checkAmount){
			return $sales['revenue_id'];
		}elseif($sales[$rev_month] == $checkAmount){
			return 'no_change';
		}else{
			return 0;
		}
	}




	public function check_outstanding($year=''){

		if($year != ''){
			$c_year = $year;
		}else{
			$c_year = date("Y");
		}

 
		$n_year = $c_year+1;

		$date_a_tmsp = mktime(0, 0, 0, '01','01', $c_year);
		$date_b_tmsp = mktime(0, 0, 0, '01','01', $n_year);


	//	echo "--xx---$date_a_tmsp----$date_b_tmsp---xx-<br />";

	//	$date_a_tmsp=1454281200;$date_b_tmsp=1456786800;$user_id=24;$comp_id=5;

		$uninvoiced_amnt = 0;
		$uninvoiced_amnt_total = 0;

		$q_outstanding_pms = $this->dashboard_m->get_outstanding_invoice($date_a_tmsp,$date_b_tmsp);
		$outstanding_pms = $q_outstanding_pms->result();

		//	echo "---------<br />";




	
		foreach ($outstanding_pms as $pms){

			$uninvoiced_amnt_total = 0;


		$month_outs = array("out_jan"=>"0","out_feb"=>"0","out_mar"=>"0","out_apr"=>"0","out_may"=>"0","out_jun"=>"0","out_jul"=>"0","out_aug"=>"0","out_sep"=>"0","out_oct"=>"0","out_nov"=>"0","out_dec"=>"0");
		$months = array (1=>'jan',2=>'feb',3=>'mar',4=>'apr',5=>'may',6=>'jun',7=>'jul',8=>'aug',9=>'sep',10=>'oct',11=>'nov',12=>'dec');
 

			//var_dump($sales_out);

		//	echo '<hr/><br />'.$pms->user_id.' - '.$pms->focus_company_id.'<br />';


			$q_outstanding = $this->dashboard_m->get_outstanding_invoice($date_a_tmsp,$date_b_tmsp,$pms->user_id,$pms->focus_company_id);
			$outstanding_sales = $q_outstanding->result();


			foreach ($outstanding_sales as $outstanding){

				//echo $outstanding->user_id.' - '.$outstanding->focus_company_id.'<br />';

				if($outstanding->label == 'VR'){
					$uninvoiced_amnt = $outstanding->variation_total;
				}else{
					$uninvoiced_amnt = $outstanding->project_total*($outstanding->progress_percent/100);
				}
				$uninvoiced_amnt_total = $uninvoiced_amnt_total + $uninvoiced_amnt;
				
				$month_index = explode('/', $outstanding->invoice_date_req);
				$month_num = ltrim($month_index['1'], '0');

				$month_outs['out_'.$months[$month_num]] = $month_outs['out_'.$months[$month_num]] + $uninvoiced_amnt;

			}



			if($uninvoiced_amnt_total > 0){  


				//echo "---<br />";


				//rvar_dump($month_outs);

				foreach ($month_outs as $key => $value) {
				 	//echo "$key => $value <br />";

					$sales = round($value, 2);


					 $this->dashboard_m->check_outstanding_set($pms->user_id, $key, $sales, $pms->focus_company_id, $c_year);


				//	 echo "$pms->user_id, $key, $sales, $pms->focus_company_id, $c_year <br />";
				}


				//echo "---<br />";


				//$this->dashboard_m->update_outstanding($pms->user_id, $out_month, $outstanding_amount, $sales->focus_company_id, $currentYear);
		 	// echo "$date_a_tmsp---------------$date_b_tmsp ----$uninvoiced_amnt_total ----- $uninvoiced_amnt ------$pms->focus_company_id------$pms->user_id------------<br />";


			//	 echo "---<br />";



			}


		//	echo "<p><hr /></p><br />";
/*

			if($sales_out->label == 'VR'){
				$uninvoiced_amnt = $sales_out->variation_total;
			}else{
				$uninvoiced_amnt = $sales_out->project_total*($sales_out->progress_percent/100);
			}

			$uninvoiced_amnt_total = $uninvoiced_amnt_total + $uninvoiced_amnt;
*/
		//	echo "$date_a_tmsp---------------$date_b_tmsp ----$uninvoiced_amnt_total ----- $uninvoiced_amnt --- $user_id-------------$comp_id-----------<br />";

		}

		//return $uninvoiced_amnt_total;
	}



/*

	function _check_next_month_sales(){
	


		$months = array (1=>'jan',2=>'feb',3=>'mar',4=>'apr',5=>'may',6=>'jun',7=>'jul',8=>'aug',9=>'sep',10=>'oct',11=>'nov',12=>'dec');

		$c_month = date("m")+1;
		$c_year = date("Y");

		if($c_month > 12){
			$c_year++;
			$c_month = $c_month % 12;
		}

		$n_month = $c_month+1;
		$n_year = $c_year;

		if($n_month > 12){
			$n_year++;
			$n_month = $n_month % 12;
		}

		$date_a_tmsp = mktime(0, 0, 0, $c_month,'01', $c_year);
		$date_b_tmsp = mktime(0, 0, 0, $n_month,'01', $n_year);

		$rev_month = 'rev_'.strtolower($months[$c_month]);
	 	$out_month = 'out_'.strtolower($months[$c_month]);

	 	$q_pms = $this->dashboard_m->get_outstanding_advanced($date_a_tmsp,$date_b_tmsp);
	 	$pms = $q_pms->result();

	 	$uninvoiced_amnt_total = 0;

		//	echo "---------<br />";
	
		foreach ($pms as $pm_data){


			$q_outstanding_advanced = $this->dashboard_m->get_outstanding_advanced($date_a_tmsp,$date_b_tmsp,$pm_data->user_id,$pm_data->focus_company_id);
			$outstanding_advanced = $q_outstanding_advanced->result();
 

			foreach ($outstanding_advanced as $sales_out){

				if($sales_out->label == 'VR'){
					$uninvoiced_amnt = $sales_out->variation_total;
				}else{
					$uninvoiced_amnt = $sales_out->project_total*($sales_out->progress_percent/100);
				}

			 	$uninvoiced_amnt_total = $uninvoiced_amnt_total + $uninvoiced_amnt;

		 	// echo "$date_a_tmsp---------------$date_b_tmsp ----$uninvoiced_amnt_total ----- $uninvoiced_amnt --- $pm_data->user_id -------------$pm_data->focus_company_id--------<br />";

			}


			$revenue_id = $this->_if_sales_changed($c_year,$pm_data->user_id,$pm_data->focus_company_id,$rev_month,$uninvoiced_amnt_total);
			if($revenue_id > 0 && $revenue_id != 'no_change'){	
				$this->dashboard_m->update_outstanding($pm_data->user_id, $out_month, $uninvoiced_amnt_total, $pm_data->focus_company_id, $c_year);

			}elseif($revenue_id == 0 && $revenue_id != 'no_change'){
				$this->dashboard_m->set_outstanding($pm_data->user_id, $out_month, $uninvoiced_amnt_total, $pm_data->focus_company_id, $c_year);

			}else{

			}  
			$uninvoiced_amnt_total = 0;

			//$uninvoiced_amnt_total = $uninvoiced_amnt_total + $uninvoiced_amnt;

		//	echo "$date_a_tmsp---------------$date_b_tmsp ----$uninvoiced_amnt_total ----- $uninvoiced_amnt --- $user_id-------------$comp_id-----------<br />";

		}

	}
*/


	function _check_sales(){

		$see_outstanding_mn = 0;
		$see_outstanding_pm = 0;

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
		$rev_month = 'rev_'.strtolower(date('M'));
	 	$out_month = 'out_'.strtolower(date('M'));


//	 	echo $date_a_tmsp.'-------'.$date_b_tmsp.'<br />';

/*
		$date_a_tmsp = mktime(0, 0, 0, '01','01', '2016'); # manual update of sales set date
		$date_b_tmsp = mktime(0, 0, 0, '02','01', '2016'); # manual update of sales set date
		$rev_month = 'rev_jan'; # manual update of sales set date
		$out_month = 'out_jan'; # manual update of sales set date
		$currentYear = '2016'; # manual update of sales set date
*/

		$q_maintenance_sales = $this->dashboard_m->get_maintenance_sales($date_a_tmsp,$date_b_tmsp);
		$maintenance_sales = $q_maintenance_sales->result();

		foreach ($maintenance_sales as $sales ){
		//	$outstanding_amount = $this->check_outstanding($sales->user_id,$sales->focus_company_id);
			$revenue_id = $this->_if_sales_changed($currentYear,$sales->user_id,$sales->focus_company_id,$rev_month,$sales->invoiced_amount);


				// 	echo $revenue_id.'<br />';
		

			if($revenue_id > 0 && $revenue_id != 'no_change'){	

				$this->dashboard_m->update_sales($revenue_id,$rev_month,$sales->invoiced_amount);
				$see_outstanding_mn = 1;
			//	$this->dashboard_m->update_outstanding($sales->user_id, $out_month, $outstanding_amount, $sales->focus_company_id, $currentYear);
			}elseif($revenue_id == 0 && $revenue_id != 'no_change'){

				$sales_id = $this->dashboard_m->set_sales($sales->user_id, $rev_month, $sales->invoiced_amount, $sales->focus_company_id, $currentYear);
				$see_outstanding_mn = 1;
			//	$this->dashboard_m->set_outstanding($sales->user_id, $out_month, $outstanding_amount, $sales->focus_company_id, $currentYear);
			}else{

			}
	
		}


		$q_pm_sales = $this->dashboard_m->get_pm_sales($date_a_tmsp,$date_b_tmsp);
		$pm_sales = $q_pm_sales->result();

		foreach ($pm_sales as $sales ){	
		//	$outstanding_amount = $this->check_outstanding($sales->user_id,$sales->focus_company_id);
			$revenue_id = $this->_if_sales_changed($currentYear,$sales->user_id,$sales->focus_company_id,$rev_month,$sales->invoiced_amount);

				// 	echo $revenue_id.'<br />';

			if($revenue_id > 0 && $revenue_id != 'no_change'){

				$this->dashboard_m->update_sales($revenue_id,$rev_month,$sales->invoiced_amount);
				$see_outstanding_pm = 1;
			//	$this->dashboard_m->update_outstanding($sales->user_id, $out_month, $outstanding_amount, $sales->focus_company_id, $currentYear);
			}elseif($revenue_id == 0 && $revenue_id != 'no_change'){
				
				$sales_id = $this->dashboard_m->set_sales($sales->user_id, $rev_month, $sales->invoiced_amount, $sales->focus_company_id, $currentYear);
				$see_outstanding_pm = 1;
			//	$this->dashboard_m->set_outstanding($sales->user_id, $out_month, $outstanding_amount, $sales->focus_company_id, $currentYear);
			}else{

			}
		}



	 	//if($see_outstanding_mn == 1 || $see_outstanding_pm == 1){

		//	echo "<br /><br />outstanding show<br /><br /><br />";

			$this->check_outstanding($c_month);

	// }

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

		if($this->session->userdata('is_admin') != 1 ):		
			redirect('', 'refresh');
		endif;

		$data['maintenance_id'] = 29;

		$post_months = array("jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec");
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
		
		if(isset($_GET['calendar_year'])){
			$old_year = $_GET['calendar_year']-1;
			$data['ryear'] = $_GET['calendar_year'];
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
				$data['forecast_id'] = $forecast_id;
				$saved_forecast_item = $this->dashboard_m->fetch_revenue_forecast($forecast_id);
				$data['saved_forecast_item'] = array_shift($saved_forecast_item->result_array());

				$old_year = $data['saved_forecast_item']['year']-1;

				$year_id = $data['saved_forecast_item']['revenue_forecast_id'];
				$data['individual_forecast'] = $this->dashboard_m->fetch_individual_forecast($year_id);
			}

		}

		$data['saved_forecast_pmData'] = $this->dashboard_m->fetch_revenue_by_year($old_year+1);

		$data['calendar_view'] = 2;

		if(isset($_GET['calendar_view'])){
			$calendar_view = $_GET['calendar_view'];
			$data['calendar_view'] = $calendar_view;
		}


		$fetch_forecast = $this->dashboard_m->fetch_forecast($old_year+1,1);
		$data['fetch_forecast'] = array_shift($fetch_forecast->result_array());

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
			$focus_total_sales = $sales_focus['total_sales'];
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

			$this->_clear_apost();
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
				$this->_update_sales_forecast($_POST);
				$data['error'] = 'Update Error: Please Complete the fields.';
			}

		}

		//$old_year = $data['old_year'];



		$sales_focus_company_q = $this->dashboard_m->get_sales_focus_company($old_year);
		$sales_focus_company = array_shift($sales_focus_company_q->result_array());
		$sales_focus_company['company_name'] = 'Last Year Sales';
		$data['fcsO'] = $sales_focus_company;


		$this_year = $old_year+1;

		$sales_focus_company_q = $this->dashboard_m->get_sales_focus_company($this_year);
		$sales_focus_company = array_shift($sales_focus_company_q->result_array());

		$outstanding_focus_company_q = $this->dashboard_m->get_focus_outstanding($this_year);
		$outstanding_focus_company = array_shift($outstanding_focus_company_q->result_array());

		$swout = array();
		$focus_overall_indv = array();

		foreach ($post_months as $key => $value) {
			//echo "$key => $value<br />";
			$swout['sales_data_'.$value] = $sales_focus_company['rev_'.$value] + $outstanding_focus_company['out_'.$value];
		}

		$sales_focus_company['company_name'] = "Focus Sales";
		$outstanding_focus_company['company_name'] = "Outstanding";

		$swout['company_name'] = "Overall Sales";
		$data['fcsC'] = $sales_focus_company;
		$data['fcsOT'] = $outstanding_focus_company;
		$data['swout'] = $swout;


		$data['focus_indv_comp_sales_old'] = $this->dashboard_m->get_sales_focus_company($old_year,1);

		$data['focus_indv_comp_sales'] = $this->dashboard_m->get_sales_focus_company($this_year,1);
		$data['focus_indv_comp_outstanding'] = $this->dashboard_m->get_focus_outstanding($this_year,1);

		$inv_fcs_overall_sales = array();

		foreach ($data['focus_indv_comp_outstanding']->result_array() as $indv_comp_outs){
			for($i=0; $i < 12 ; $i++){
				$counter = $i;
				$month_index = 'out_'.strtolower($months[$counter]);
				//echo $indv_comp_outs[$month_index].'<br />';

				$inv_fcs_overall_sales[$indv_comp_outs['company_name']][$month_index] = $indv_comp_outs[$month_index];
			}
		}

		foreach ($data['focus_indv_comp_sales']->result_array() as $indv_comp_sales){
			for($i=0; $i < 12 ; $i++){
				$counter = $i;
				$month_index = 'rev_'.strtolower($months[$counter]);
				//echo $indv_comp_sales[$month_index].'<br />';

				$inv_fcs_overall_sales[$indv_comp_sales['company_name']][$month_index] = $indv_comp_sales[$month_index];
			}
		}




		$data['inv_fcs_overall_sales'] = $inv_fcs_overall_sales;

		$data['focus_indv_comp_forecast'] = $this->dashboard_m->fetch_indv_comp_forecast($this_year);

		$rev_month = 'rev_'.strtolower(date('M'));
		$out_month = 'out_'.strtolower(date('M'));


		$this_year = date("Y");

		$data['focus_indv_pm_month_sales'] = $this->dashboard_m->fetch_pms_month_sales($rev_month,$this_year);
		$data['focus_indv_focu_month_sales'] = $this->dashboard_m->fetch_comp_month_sales($rev_month,$this_year);
		$data['focus_indv_focu_month_outs'] = $this->dashboard_m->fetch_comp_month_outs($out_month,$this_year);


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

	public function _update_sales_forecast($post_data){
		$forecast_id = $this->input->post('forecast_id');

		foreach ($post_data as $key => $value){
			$this->form_validation->set_rules($key, $key,'trim|required|xss_clean');
		}

		if($this->form_validation->run() === false){

		}else{

			$data_label = $this->input->post('data_label_edt');
			$total = str_replace(',', '',$this->input->post('data_amount_edt'));
			$forecast_id = $this->input->post('forecast_id');
			$months = array();

			for ($x=0; $x < 12; $x++){
				$months[$x] = str_replace(',', '',$this->input->post('month_edt_'.$x));
			}

		 	list($forecast_jan, $forecast_feb, $forecast_mar, $forecast_apr, $forecast_may, $forecast_jun, $forecast_jul, $forecast_aug, $forecast_sep, $forecast_oct, $forecast_nov, $forecast_dec) = $months;
		 	$this->dashboard_m->update_revenue_forecast($forecast_id, $data_label, $total, $forecast_jan, $forecast_feb, $forecast_mar, $forecast_apr, $forecast_may, $forecast_jun, $forecast_jul, $forecast_aug, $forecast_sep, $forecast_oct, $forecast_nov, $forecast_dec );

		 	foreach ($post_data as $key => $value){
		 		$pms_percent = explode('_', $key);

		 		if( isset($pms_percent[3])) {
		 			$indv_id = $pms_percent[3];
		 			$this->dashboard_m->update_revenue_forecast_indv($value,$indv_id);
		 		}
		 	}

		 	$this->session->set_flashdata('save_success','Forecast Updated'); 
			redirect('/dashboard/sales_forecast/view_'.$forecast_id, 'refresh');

		}

//		redirect('/dashboard/sales_forecast', 'refresh');

	}
}