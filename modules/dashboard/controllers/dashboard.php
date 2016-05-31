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


		$this->load->module('wip');
		$this->load->model('wip_m');

		$this->load->module('invoice');


		$this->load->module('purchase_order');
		$this->load->model('purchase_order_m');

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

		$c_month = date("m");
		$c_year = date("Y");

		for ($my_year = 2015; $my_year <= $c_year; $my_year++){
			for ($month=1; $month < 13; $month++) { 
				if($month > $c_month && $c_year == $my_year){
				}else{
					//echo "$my_year,$month<br />";
					$this->_check_sales($my_year,$month); // automatically updates sales of the current month
				}
			}
			$this->check_outstanding($my_year);
			$this->check_estimates($my_year);
		}
	}	

	function index(){
		//$this->users->_check_user_access('dashboard',1);

		if($this->session->userdata('is_admin') != 1 ):		
			redirect('', 'refresh');
		endif;

		$post_months = array("jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec");
		$months = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"); 

		$data['main_content'] = 'dashboard_home';
		$data['screen'] = 'Dashboard';

		$data['calendar_view'] = 2;

		if(isset($_GET['calendar_view'])){
			$calendar_view = $_GET['calendar_view'];
			$data['calendar_view'] = $calendar_view;
		}

		$old_year = date("Y")-1;
		$this_year = date("Y");


		$fetch_forecast = $this->dashboard_m->fetch_forecast($this_year,1);
		$data['fetch_forecast'] = array_shift($fetch_forecast->result_array());



		$sales_focus_company_q = $this->dashboard_m->get_sales_focus_company($old_year);
		$sales_focus_company = array_shift($sales_focus_company_q->result_array());
		$sales_focus_company['company_name'] = 'Last Year Sales';
		$data['fcsO'] = $sales_focus_company;




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

		$data['pms_sales_c_year'] = $this->dashboard_m->fetch_pm_sales_year($this_year);

		$data['focus_indv_pm_month_sales'] = $this->dashboard_m->fetch_pms_month_sales($rev_month,$this_year);
		$data['focus_indv_focu_month_sales'] = $this->dashboard_m->fetch_comp_month_sales($rev_month,$this_year);
		$data['focus_indv_focu_month_outs'] = $this->dashboard_m->fetch_comp_month_outs($out_month,$this_year);

		$this->load->view('page', $data);
	}

	function _if_sales_changed($year,$proj_mngr_id,$focus_comp_id,$rev_month,$checkAmount){
	//	echo "-----$year,$proj_mngr_id,$focus_comp_id,$rev_month,$checkAmount----<br />";

		$q_sales = $this->dashboard_m->look_for_sales($year,$proj_mngr_id,$focus_comp_id,$rev_month);
		//$rev_month = 'rev_'.strtolower(date('M'));

		$sales = array_shift($q_sales->result_array());

		//var_dump($sales);
	//	echo "<br />";

		$checkAmount = round($checkAmount,2);

	//	echo "<br />$sales[$rev_month] != $checkAmount<br />";

		if($sales[$rev_month] != $checkAmount){
			return $sales['revenue_id'];
		}elseif($sales[$rev_month] == $checkAmount){
			return 1;
		}else{
			return 0;
		}
	}





	public function check_estimates($year=''){

		if($year != ''){
			$c_year = $year;
		}else{
			$c_year = date("Y");
		}

		$n_year = $c_year+1;

		$date_a_tmsp = mktime(0, 0, 0, '01','01', $c_year);
		$date_b_tmsp = mktime(0, 0, 0, '01','01', $n_year);

		$date_a = "01/01/$c_year";
		$date_b = "01/01/$n_year";

		$estimated_amnt = 0;
		//$estimated_amnt_total = 0;

		$q_estimates_pms = $this->dashboard_m->get_estimates($date_a,$date_b);
		$estimates_pms = $q_estimates_pms->result();

		foreach ($estimates_pms as $pms){

			//$estimated_amnt_total = 0;
			$month_est = array("est_jan"=>"0","est_feb"=>"0","est_mar"=>"0","est_apr"=>"0","est_may"=>"0","est_jun"=>"0","est_jul"=>"0","est_aug"=>"0","est_sep"=>"0","est_oct"=>"0","est_nov"=>"0","est_dec"=>"0");
			$months = array (1=>'jan',2=>'feb',3=>'mar',4=>'apr',5=>'may',6=>'jun',7=>'jul',8=>'aug',9=>'sep',10=>'oct',11=>'nov',12=>'dec');

			$q_estimates = $this->dashboard_m->get_estimates($date_a,$date_b,$pms->project_manager_id,$pms->focus_company_id);
			$estimated_sales = $q_estimates->result();


			foreach ($estimated_sales as $estimated){

/*
project_id
project_total
variation_total
budget_estimate_total
date_site_commencement
date_site_finish
focus_company_id
project_manager_id Ascending
user_first_name
user_last_name
*/

			//	echo $estimated->project_total.'******'.$estimated->variation_total.'******'.$estimated->budget_estimate_total.'******'.$estimated->project_date.'*****'.$estimated->project_manager_id.'******'.$estimated->focus_company_id.'******<br />';


if($estimated->project_total > 0){
	$estimated_amnt = $estimated->project_total + $estimated->variation_total;
}else{
	$estimated_amnt = $estimated->budget_estimate_total + $estimated->variation_total;
}

				//$estimated_amnt_total = $estimated_amnt_total + $estimated_amnt;


$month_index = explode('/', $estimated->project_date);
$month_num = ltrim($month_index['1'], '0');

$month_est['est_'.$months[$month_num]] = $month_est['est_'.$months[$month_num]] + $estimated_amnt;


			//	echo $months[$month_num].'.---.'.$month_est['est_'.$months[$month_num]].'---'.$pms->project_manager_id.'--------'.$pms->focus_company_id.'<br />';



/*
				if($estimated->label == 'VR'){
					$estimated_amnt = $estimated->variation_total;
				}else{
					$estimated_amnt = $estimated->project_total*($estimated->progress_percent/100);
				}
				$estimated_amnt_total = $estimated_amnt_total + $estimated_amnt;
				
				$month_index = explode('/', $estimated->invoice_date_req);
				$month_num = ltrim($month_index['1'], '0');

				$month_outs['est_'.$months[$month_num]] = $month_outs['est_'.$months[$month_num]] + $estimated_amnt;
*/



			}





			foreach ($month_est as $key => $value) {
				$sales = round($value, 2);


			//	echo $key.'----'.$sales.'-----'.$pms->project_manager_id.'--------'.$pms->focus_company_id.'-------'.$c_year.'<br />';




				$this->dashboard_m->check_estimates_set($pms->project_manager_id, $key, $sales, $pms->focus_company_id, $c_year);
			}



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


		$date_a = "01/01/$c_year";
		$date_b = "01/01/$n_year";


	//	echo "--xx---$date_a_tmsp----$date_b_tmsp---xx-<br />";

	//	$date_a_tmsp=1454281200;$date_b_tmsp=1456786800;$user_id=24;$comp_id=5;

		$uninvoiced_amnt = 0;
		$uninvoiced_amnt_total = 0;

		$q_outstanding_pms = $this->dashboard_m->get_outstanding_invoice($date_a,$date_b);
		$outstanding_pms = $q_outstanding_pms->result();

		//	echo "---------<br />";


		foreach ($outstanding_pms as $pms){

			$uninvoiced_amnt_total = 0;


			$month_outs = array("out_jan"=>"0","out_feb"=>"0","out_mar"=>"0","out_apr"=>"0","out_may"=>"0","out_jun"=>"0","out_jul"=>"0","out_aug"=>"0","out_sep"=>"0","out_oct"=>"0","out_nov"=>"0","out_dec"=>"0");
			$months = array (1=>'jan',2=>'feb',3=>'mar',4=>'apr',5=>'may',6=>'jun',7=>'jul',8=>'aug',9=>'sep',10=>'oct',11=>'nov',12=>'dec');


			//var_dump($sales_out);

		//	echo '<hr/><br />'.$pms->user_id.' - '.$pms->focus_company_id.'<br />';


			$q_outstanding = $this->dashboard_m->get_outstanding_invoice($date_a,$date_b,$pms->user_id,$pms->focus_company_id);
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


			foreach ($month_outs as $key => $value) {
				$sales = round($value, 2);
				$this->dashboard_m->check_outstanding_set($pms->user_id, $key, $sales, $pms->focus_company_id, $c_year);
			}
		}
	}



	function _check_sales($c_year,$c_month){

		$see_outstanding_mn = 0;
		$see_outstanding_pm = 0;
		$init_invoied_amount = 0;


		$currentYear = $c_year;

		//$currentDateTmpsmpt = mktime(0, 0, 0, $currentMonth, $currentDay, $currentYear);



		//$c_month = date("m");
		//$c_year = date("Y");

		$n_month = $c_month+1;
		$n_year = $c_year;

		if($c_month == 12){
			$n_month = 1;
			$n_year = $c_year+1;
		}

/*
		$date_a_tmsp = mktime(0, 0, 0, $c_month,'01', $c_year);
		$date_b_tmsp = mktime(0, 0, 0, $n_month,'01', $n_year);
		$rev_month = 'rev_'.strtolower(date('M'));
*/

	//	echo "$date_a_tmsp,$date_b_tmsp";
/*
		$c_month = '12';
		$date_a_tmsp = mktime(0, 0, 0, '12','01', '2015'); # manual update of sales set date
		$date_b_tmsp = mktime(0, 0, 0, '01','01', '2016'); # manual update of sales set date
		$rev_month = 'rev_dec'; # manual update of sales set date
		$currentYear = '2015'; # manual update of sales set date
*/

		$date_a = "01/$c_month/$c_year";
		$date_b = "01/$n_month/$n_year";

		$mons = array(1 => "jan", 2 => "feb", 3 => "mar", 4 => "apr", 5 => "may", 6 => "jun", 7 => "jul", 8 => "aug", 9 => "sep", 10 => "oct", 11 => "nov", 12 => "dec");


		$rev_month = 'rev_'.$mons[$c_month];


//echo "$date_a,-------  --------$date_b<br />";




		$q_list_pms = $this->dashboard_m->list_pm_bysales($date_a,$date_b);
		$list_pms = $q_list_pms->result();



		//echo "$date_a_tmsp,$date_b_tmsp <br />";

		foreach ($list_pms as $pm_data ){

			$pm_id = $pm_data->user_id;
			$comp_id = $pm_data->focus_company_id;


			$q_get_sales = $this->dashboard_m->get_sales($date_a,$date_b,$pm_id,$comp_id);
			$pm_get_sales = $q_get_sales->result();

			$pm_sale_total= 0;


			foreach ($pm_get_sales as $pm_sales ){

				if($pm_sales->label == 'VR'){
					$sales_total = $pm_sales->variation_total;
				}else{
					$sales_total = $pm_sales->project_total*($pm_sales->progress_percent/100);
				}

				$pm_sale_total = $pm_sale_total + $sales_total;


			}

			$revenue_id =  intval($this->_if_sales_changed($currentYear,$pm_data->user_id,$pm_data->focus_company_id,$rev_month,$pm_sale_total));

			//echo "$pm_sale_total ----  $pm_data->user_first_name----$revenue_id---<br />";

			if($revenue_id > 1){
				$this->dashboard_m->update_sales($revenue_id,$rev_month,$pm_sale_total);
			}elseif($revenue_id == 0){
				$sales_id = $this->dashboard_m->set_sales($pm_data->user_id, $rev_month, $pm_sale_total, $pm_data->focus_company_id, $currentYear);
			}else{

			}	

		//	echo "<br />$revenue_id<br />";

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

	public function donut_sales($formatted=''){

		

		if (isset($_POST['set_month_dshbrd'])){

			$c_month = $this->input->post('set_month_dshbrd');
			$c_year = date("Y");

			$n_month = $c_month+1;
			$n_year = $c_year;

			if($n_month > 12){
				$n_month = $n_month - 12;
				$n_year = $c_year+1;
			}

		}else{
			$c_year = date("Y");
			$c_month = '01';

			$n_year = $c_year+1;
			$n_month = '01';
		}

		$date_a = "01/$c_month/$c_year";
		$date_b = "01/$n_month/$n_year";

		$date_a_tmsp = mktime(0, 0, 0, $c_month,'01', $c_year);
		$date_b_tmsp = mktime(0, 0, 0, $n_month,'01', $n_year);


		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		$grand_total_sales = 0;
		$sales_total = 0;

		$grand_total_outstanding = 0;
		$outstanding_total = 0;

		$sales_total_arr = array();
		$key_id = '';

		foreach ($focus_company as $company){
			$q_dash_sales = $this->dashboard_m->dash_sales($date_a,$date_b,$company->company_id,1);

			if($q_dash_sales->num_rows >= 1){

				$grand_total_sales = 0;
				$sales_total = 0;

				$dash_sales = $q_dash_sales->result();

				foreach ($dash_sales as $sales){
					//var_dump($sales);
					//echo "<br />$company->company_id<hr /><br />";

					if($sales->label == 'VR'){
						$sales_total = $sales->variation_total;
					}else{
						$sales_total = $sales->project_total*($sales->progress_percent/100);
					}

					$grand_total_sales = $grand_total_sales + $sales_total;
				}

				if($company->company_id == 5){
					$key_id = 'WA';
				}

				if($company->company_id == 6){
					$key_id = 'NSW';
				}

				$sales_total_arr[$key_id] = $grand_total_sales;				
			}


			$q_dash_unvoiced = $this->dashboard_m->dash_unvoiced_per_date($date_a,$date_b,$company->company_id);
			$dash_unvoiced = $q_dash_unvoiced->result();

			$unvoiced_total = 0;
			$unvoiced_grand_total = 0;


			foreach ($dash_unvoiced as $unvoiced) {
				if($unvoiced->label == 'VR'){
					$unvoiced_total = $unvoiced->variation_total;
				}else{
					$unvoiced_total = $unvoiced->project_total*($unvoiced->progress_percent/100);
				}


				$unvoiced_grand_total = $unvoiced_grand_total + $unvoiced_total;

				if($unvoiced->focus_company_id == 5){
					$key_id = 'WA';
				}

				if($unvoiced->focus_company_id == 6){
					$key_id = 'NSW';
				}
			}

			if($key_id != ''){
				$unvoiced_total_arr[$key_id] = $unvoiced_grand_total;
			}





		}


		if($formatted != ''){
			foreach ($sales_total_arr as $key => $value) {
				//echo "$key => $value <br /><br /><br />";
				$temp_total = $unvoiced_total_arr[$key]+$sales_total_arr[$key];
				echo '<div id="" class=""><div class="col-sm-5 text-right"><strong class="pad-right-10">'.$key.'</strong></div><div class="col-sm-7"> <i class="fa fa-usd"></i> '.number_format($temp_total,2).'</div></div>';
			}
		}else{
			foreach ($sales_total_arr as $key => $value) {
			//echo "$key => $value <br /><br /><br />";
				$temp_total = $unvoiced_total_arr[$key]+$sales_total_arr[$key];
				echo "['".$key."',".$temp_total."],";
			}
		}

	//	var_dump($sales_total_arr);
	//	var_dump($unvoiced_total_arr);

	}

	public function sales_widget(){

		if (isset($_POST['set_month_dshbrd'])){

			$c_month = $this->input->post('set_month_dshbrd');
			$c_year = date("Y");

			$n_month = $c_month+1;
			$n_year = $c_year;

			if($n_month > 12){
				$n_month = $n_month - 12;
				$n_year = $c_year+1;
			}

		}else{
			$c_year = date("Y");
			$c_month = '01';

			$n_year = $c_year+1;
			$n_month = '01';
		}

		$date_a = "01/$c_month/$c_year";
		$date_b = "01/$n_month/$n_year";

		$date_a_tmsp = mktime(0, 0, 0, $c_month,'01', $c_year);
		$date_b_tmsp = mktime(0, 0, 0, $n_month,'01', $n_year);


		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		$grand_total_sales = 0;
		$sales_total = 0;

		$grand_total_outstanding = 0;
		$outstanding_total = 0;

		$sales_total_arr = array();

		foreach ($focus_company as $company){
			$q_dash_sales = $this->dashboard_m->dash_sales($date_a,$date_b,$company->company_id,1);

			if($q_dash_sales->num_rows >= 1){

				$grand_total_sales = 0;
				$sales_total = 0;

				$dash_sales = $q_dash_sales->result();

				foreach ($dash_sales as $sales){
					//var_dump($sales);
					//echo "<br />$company->company_id<hr /><br />";

					if($sales->label == 'VR'){
						$sales_total = $sales->variation_total;
					}else{
						$sales_total = $sales->project_total*($sales->progress_percent/100);
					}

					$grand_total_sales = $grand_total_sales + $sales_total;
				}

				if($company->company_id == 5){
					$key_id = 'WA';
				}

				if($company->company_id == 6){
					$key_id = 'NSW';
				}

				$sales_total_arr[$key_id] = $grand_total_sales;				
			}
		}

		foreach ($sales_total_arr as $key => $value) {
			//echo "$key => $value <br /><br /><br />";
			echo '<p class="value"><span class="col-xs-3">'.$key.'</span> <span class="col-xs-9"><i class="fa fa-usd"></i> <strong>'.number_format($value,2).'</strong></span></p>';
		}
	}



	public function wip_widget(){

		$proj_t = $this->wip_m->display_all_wip_projects();

		$estimated_wa = 0;
		$quote_wa = 0;

		$estimated_nsw = 0;
		$quote_nsw = 0;

		$wip_total = 0;

		$total_invoiced_init_wa = 0;
		$total_invoiced_wa = 0;

		$total_invoiced_init_nsw = 0;
		$total_invoiced_nsw = 0;


		foreach ($proj_t->result_array() as $row){
			$quoted = 0;
			$estimated = 0;

			if($this->invoice->if_invoiced_all($row['project_id'])  && $this->invoice->if_has_invoice($row['project_id']) > 0 ){

			}else{
				if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 || $row['variation_total'] > 0.00 ){
					$quoted = $row['project_total']+$row['variation_total'];
				}else{
					$estimated = $row['budget_estimate_total'];
				}



				if( $row['focus_company_id'] == 5 ){
					$estimated_wa = $estimated_wa + $estimated;
					$quote_wa = $quote_wa + $quoted;

					$total_invoiced_init_wa = $this->invoice->get_project_invoiced($row['project_id'],$row['project_total'],$row['variation_total']);
					$total_invoiced_wa = $total_invoiced_wa + $total_invoiced_init_wa;
				}


				if( $row['focus_company_id'] == 6 ){
					$estimated_nsw = $estimated_nsw + $estimated;
					$quote_nsw = $quote_nsw + $quoted;

					$total_invoiced_init_nsw = $this->invoice->get_project_invoiced($row['project_id'],$row['project_total'],$row['variation_total']);
					$total_invoiced_nsw = $total_invoiced_nsw + $total_invoiced_init_nsw;
				}




			}
		}

		echo '<p class="value"><span class="col-xs-3">WA</span> <span class="col-xs-9"><i class="fa fa-usd"></i> <strong>'.number_format( ($estimated_wa+$quote_wa) - $total_invoiced_wa,2).'</strong></span></p>';
		echo '<p class="value"><span class="col-xs-3">NSW</span> <span class="col-xs-9"><i class="fa fa-usd"></i> <strong>'.number_format( ($estimated_nsw+$quote_nsw) - $total_invoiced_nsw,2).'</strong></span></p>';
	}


	public function uninvoiced_widget(){

		
		$c_year = date("Y");
		$c_month = '01';



		$date_a = "01/$c_month/$c_year";

		$n_year = date("Y");
		$n_month = date("m");
		$n_day = date("d")+1;


		$date_b = "$n_day/$n_month/$n_year";

		$unvoiced_total_arr = array();
		$key_id = '';

		//$date_a_tmsp = mktime(0, 0, 0, $c_month, $c_day, $c_year);

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		foreach ($focus_company as $company){

			$q_dash_unvoiced = $this->dashboard_m->dash_unvoiced_per_date($date_a,$date_b,$company->company_id);
			$dash_unvoiced = $q_dash_unvoiced->result();

			$unvoiced_total = 0;
			$unvoiced_grand_total = 0;


			foreach ($dash_unvoiced as $unvoiced) {
				if($unvoiced->label == 'VR'){
					$unvoiced_total = $unvoiced->variation_total;
				}else{
					$unvoiced_total = $unvoiced->project_total*($unvoiced->progress_percent/100);
				}


		//	echo "$unvoiced_total ---- $company->company_id  ------------  $unvoiced->invoice_date_req   ----------  $unvoiced->project_id<br />";

				$unvoiced_grand_total = $unvoiced_grand_total + $unvoiced_total;

				if($unvoiced->focus_company_id == 5){
					$key_id = 'WA';
				}

				if($unvoiced->focus_company_id == 6){
					$key_id = 'NSW';
				}
			}

			if($key_id != ''){

				$unvoiced_total_arr[$key_id] = $unvoiced_grand_total;
			}
		}

		foreach ($unvoiced_total_arr as $key => $value) {
			//echo "$key => $value <br /><br /><br />";
			echo '<p class="value"><span class="col-xs-3">'.$key.'</span> <span class="col-xs-9"><i class="fa fa-usd"></i> <strong>'.number_format($value,2).'</strong></span></p>';
		}
	}


	public function outstanding_payments_widget(){

		
		$c_year = date("Y");
		$c_month = '01';



		$date_a = "01/$c_month/$c_year";

		$n_year = date("Y");
		$n_month = date("m");
		$n_day = date("d")+1;


		$date_b = "$n_day/$n_month/$n_year";
		 


		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		foreach ($focus_company as $company){

			$invoice_amount= 0;
			$total_invoice= 0;
			$total_paid = 0;
			$total_outstanding = 0;

			$key_id = '';

			$q_dash_oustanding_payments = $this->dashboard_m->dash_oustanding_payments($date_a,$date_b,$company->company_id);
			$oustanding_payments = $q_dash_oustanding_payments->result();

			foreach ($oustanding_payments as $oustanding) {

				if($oustanding->label == 'VR'){
					$invoice_amount = $oustanding->variation_total;
				}else{
					$invoice_amount = $oustanding->project_total*($oustanding->progress_percent/100);
				}

				$total_invoice = $total_invoice + $invoice_amount;

				$total_paid = $total_paid + $oustanding->amount_exgst;

			#	$overall_outstanding = $grand_outstanding_sales - $oustanding->amount_exgst;
			#	echo "$oustanding->project_id ---  $oustanding->amount_exgst --- $invoice_amount<br />";

			}

			$total_outstanding = $total_invoice - $total_paid;

			if($company->company_id == 5){
				$key_id = 'WA';
				echo '<p class="value"><span class="col-xs-3">'.$key_id.'</span> <span class="col-xs-9"><i class="fa fa-usd"></i> <strong>'.number_format($total_outstanding,2).'</strong></span></p>';
			}elseif($company->company_id == 6){
				$key_id = 'NSW';
				echo '<p class="value"><span class="col-xs-3">'.$key_id.'</span> <span class="col-xs-9"><i class="fa fa-usd"></i> <strong>'.number_format($total_outstanding,2).'</strong></span></p>';
			}else{

			}
		}
	}





	public function pm_sales_widget(){

		$grand_total_sales_cmp = 0;
		$grand_total_uninv_cmp = 0;
		$grand_total_over_cmp = 0;

		if (isset($_POST['set_month_dshbrd'])){

			$c_month = $this->input->post('set_month_dshbrd');
			$c_year = date("Y");

			$n_month = $c_month+1;
			$n_year = $c_year;

			if($n_month > 12){
				$n_month = $n_month - 12;
				$n_year = $c_year+1;
			}

		}else{
			$c_year = date("Y");
			$c_month = '01';

			$n_year = $c_year+1;
			$n_month = '01';
		}
		$c_year = date("Y");
		
		$date_a = "01/01/$c_year";


		$c_year = date("Y");
		$c_month = date("m");
		$c_day = date("d");


		$date_b = "$c_day/$c_month/$c_year";


		$overall_total_sales = 0;



		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();

	//	var_dump($project_manager_list);
//echo $pm->user_id.


$forecast_focus_total = 0; 
		foreach ($project_manager_list as $pm ) {
			$total_sales = 0;
			$total_outstanding = 0;

			//$q_pm_sales = $this->dashboard_m->dash_total_pm_sales($pm->user_id,$c_year);

			$q_pm_sales = $this->dashboard_m->dash_total_pm_sales($pm->user_id,$c_year,'',$date_a,$date_b);

			if($q_pm_sales->num_rows >= 1){

				$pm_sales = $q_pm_sales->result_array();



				foreach ($pm_sales as $sales => $value){



					if($value['label'] == 'VR'){
						$project_total_percent = $value['variation_total'];
					}else{
						$project_total_percent = $value['project_total'] * ($value['progress_percent']/100);
					}



 
 
					$total_sales = $total_sales + $project_total_percent;

				}








			}else{
				$total_sales = $total_sales + 0;
			}




			$q_pm_outstanding = $this->dashboard_m->dash_total_pm_sales($pm->user_id,$c_year,1,$date_a,$date_b);


			if($q_pm_outstanding->num_rows >= 1){

				$pm_outstanding = $q_pm_outstanding->result_array();

				foreach ($pm_outstanding as $sales => $value){

					if($value['label'] == 'VR'){
						$project_total_percent = $value['variation_total'];
					}else{
						$project_total_percent = $value['project_total'] * ($value['progress_percent']/100);
					}



					$outstanding = $this->invoice->get_current_balance($value['project_id'],$value['invoice_top_id'],$project_total_percent);

					$total_outstanding = $total_outstanding + $outstanding;



				}


			}else{
				$total_outstanding = $total_outstanding + 0;
			}



 










			

			$overall_total_sales = $total_sales + $total_outstanding;

			$q_current_forecast_comp = $this->dashboard_m->get_current_forecast($c_year,$pm->user_focus_company_id,'1');
			$comp_forecast = array_shift($q_current_forecast_comp->result_array());

			$q_current_forecast = $this->dashboard_m->get_current_forecast($c_year,$pm->user_id);
			$pm_forecast = array_shift($q_current_forecast->result_array());

			$total_forecast = ( $comp_forecast['total'] * (  $comp_forecast['forecast_percent']  /100  ) *  ($pm_forecast['forecast_percent']/100) );


			if($overall_total_sales > 0){
				$status_forecast = round(100/($total_forecast/$overall_total_sales));
			}else{
				$status_forecast = 0;
			}

			$forecast_focus_total = $forecast_focus_total + $total_forecast; // $comp_forecast['total'];

			echo '<div class="m-bottom-15 clearfix"><div class="pull-left m-right-10"  style="height: 50px; width:50px; border-radius:50px; overflow:hidden; border: 1px solid #999999;"><img class="user_avatar img-responsive img-rounded" src="'.base_url().'/uploads/users/'.$pm->user_profile_photo.'"" /></div>';
			echo '<div class="" id=""><p><strong>'.$pm->user_first_name.'</strong><span class="pull-right"><span class="label pull-right m-bottom-3 m-top-3 small_orange_fixed"><i class="fa fa-usd"></i> '.number_format($total_sales).'</span> <br /> <span class="label pull-right m-bottom-3 small_blue_fixed"><i class="fa fa-exclamation-triangle"></i> '.number_format($total_outstanding).'</span></span></p>';
			echo '<p><i class="fa fa-usd"></i> '.number_format($overall_total_sales).'</p>';
			
			echo '<div class="value-bar clearfix tooltip-enabled" title="" data-original-title="'.$status_forecast.'% - $'.number_format($overall_total_sales).' / $'.number_format($total_forecast).'   " style="float: left;    margin: -7px 0px 0px 60px;    width: 85%;"><div class="value pull-left" style="width:'.$status_forecast.'%"></div></div>			</div></div>';
			echo "<div class='clearfix'></div>";

			$grand_total_sales_cmp = $grand_total_sales_cmp + $total_sales;
			$grand_total_uninv_cmp = $grand_total_uninv_cmp + $total_outstanding;
			$grand_total_over_cmp = $grand_total_over_cmp + $overall_total_sales;

		}
			$status_forecast = round(100/($forecast_focus_total/$grand_total_over_cmp));

			echo '<div class="clearfix" style="margin-top:-10px;"><hr class="block m-bottom-5 m-top-5"><i class="fa fa-briefcase" style="font-size: 42px;float: left;margin-left: 7px;margin-right: 10px;"></i>';
			echo '<div class="" id=""><p><strong>Overall Focus</strong><span class="pull-right"><span class="label pull-right m-bottom-3 m-top-3 small_orange_fixed"><i class="fa fa-usd"></i> '.number_format($grand_total_sales_cmp).'</span> <br /> <span class="label pull-right m-bottom-3 small_blue_fixed"><i class="fa fa-exclamation-triangle"></i> '.number_format($grand_total_uninv_cmp).'</span></span></p>';
			echo '<p><i class="fa fa-usd"></i> '.number_format($grand_total_over_cmp).' <strong class="pull-right m-right-10"></strong></p> </p>';

			echo '<div class="value-bar clearfix tooltip-enabled" title="" data-original-title="'.$status_forecast.'% - $'.number_format($grand_total_over_cmp).' / $'.number_format($forecast_focus_total).'   " style="float: left; margin: 4px 0px 0px 0px; width: 100%;"><div class="value pull-left" style="width:'.$status_forecast.'%"></div></div>			</div></div>';
			echo "<div class='clearfix'></div>";

	}








	public function pm_estimates_widget(){

		
		$year = date("Y");
		$current_date = date("d/m/Y");
		$current_start_year = '01/01/'.$year;

		$overall_total_sales = 0;
		$total_quoted = 0;
		$prj_total_current = 0;

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		$gt_cpo_wa  = 0;
		$gt_cpo_nsw = 0;

	//	foreach ($focus_company as $company) {
			$q_total_pm_estimates = $this->dashboard_m->dash_total_pm_estimates($current_start_year,$current_date);
			$pm_sales = $q_total_pm_estimates->result();
			//$total_estimates = array_shift($q_total_pm_estimates->result_array());
			foreach ($pm_sales as $un_accepted) {

			//	$total_estimated = $total_estimates['total_estimates'];
			//var_dump($total_estimates);

			# code...
				$prj_total_current = 0;
 
				$prj_total_current = $un_accepted->project_total + $un_accepted->variation_total;

 

				if($un_accepted->install_time_hrs > 0 || $un_accepted->work_estimated_total > 0 || $un_accepted->variation_total > 0 ){
					$total_quoted =  $prj_total_current;
				}else{
					$total_quoted =  0;
				}



			//	$total_quoted = $total_quoted + $prj_total_current;

			//	$project_total = $un_accepted->project_total;

        //    $gt_outstanding = $gt_outstanding + $out_standing;


				if( $un_accepted->focus_company_id == 5 ){
					$gt_cpo_wa = $gt_cpo_wa + $total_quoted;	
				}


				if( $un_accepted->focus_company_id == 6 ){
					$gt_cpo_nsw = $gt_cpo_nsw + $total_quoted;
				}



			}


			echo '<p class="value"><span class="col-xs-3">WA</span> <span class="col-xs-9"><i class="fa fa-usd"></i> <strong>'.number_format($gt_cpo_wa,2).'</strong></span></p>';


			echo '<p class="value"><span class="col-xs-3">NSW</span> <span class="col-xs-9"><i class="fa fa-usd"></i> <strong>'.number_format($gt_cpo_nsw,2).'</strong></span></p>';




 

	//	}






	}


	public function focus_projects_count_widget(){

		$current_date = date("d/m/Y");
		$year = date("Y");
		$next_year_date = '01/01/'.($year+1);
		$current_start_year = '01/01/'.$year;
		$last_start_year = '01/01/'.($year-1);

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();


		foreach ($focus_company as $company) {


			$wip = 0;
			$invoiced = 0;

			$wip_old = 0;
			$invoiced_old = 0;

			$projects_current = $this->dashboard_m->get_finished_projects($current_start_year,$current_date,$company->company_id);
			$projects_old = $this->dashboard_m->get_finished_projects($last_start_year,$current_start_year,$company->company_id);

			$projects_qa = $this->dashboard_m->get_wip_invoiced_projects($current_start_year, $next_year_date, $company->company_id);
			$projects_ra = $projects_qa->result_array();


			foreach ($projects_ra as $result) {
				
				if($result['job_date'] != '' ){
					$wip++;
				}

				if($this->invoice->if_invoiced_all($result['project_id'])  && $this->invoice->if_has_invoice($result['project_id']) > 0 ){
					$wip--;
					$invoiced++;
				}
			}



			$projects_qb = $this->dashboard_m->get_wip_invoiced_projects($last_start_year, $current_start_year, $company->company_id);
			$projects_rb = $projects_qb->result_array();


			foreach ($projects_rb as $result) {
				
				if($result['job_date'] != '' ){
					$wip_old++;
				}

				if($this->invoice->if_invoiced_all($result['project_id'])  && $this->invoice->if_has_invoice($result['project_id']) > 0 ){
					$wip_old--;
					$invoiced_old++;
				}
			}

		//	$result = $projects_q->result_array();



/*
			$q_total_pm_estimates = $this->dashboard_m->dash_total_pm_estimates($company->company_id,$c_year);
			$total_estimates = array_shift($q_total_pm_estimates->result_array());
			$total_estimated = $total_estimates['total_estimates'];
			<strong class="text-center col-xs-3 popover-test" data-placement="bottom" data-content="Focus Company" ><i class="fa fa-users fa-lg"></i></strong> 
*/ 


			if($company->company_id == 5){
				$key_id = 'WA';
				echo '<div id="" class="clearfix row">
					<strong class="text-center col-xs-4"><p>WA</p></strong>					
					<strong class="text-center col-xs-4 tooltip-test pointer" data-placement="top" data-original-title="Invoiced Last Year : '.$invoiced_old.'"><p class="h5x">'.$invoiced.'</p></strong>
					<strong class="text-center col-xs-4 tooltip-test pointer" data-placement="left" data-original-title="WIP Last Year : '.$wip_old.'"><p class="h5x">'.$wip.'</p></strong>
				</div>';
			}elseif($company->company_id == 6){
				$key_id = 'NSW';
				echo '<hr class="block m-bottom-3 m-top-5">';
				echo '<div id="" class="clearfix row" style="margin-top:4px;">
					<strong class="text-center col-xs-4"><p>NSW</p></strong>
					<strong class="text-center col-xs-4 tooltip-test pointer" data-placement="top" data-original-title="Invoiced Last Year : '.$invoiced_old.'"><p class="h5x">'.$invoiced.'</p></strong>
					<strong class="text-center col-xs-4 tooltip-test pointer" data-placement="left" data-original-title="WIP Last Year : '.$wip_old.'"><p class="h5x">'.$wip.'</p></strong>
				</div>';
			}else{

			}
		}
	}

	public function focus_get_map_locations(){



		$current_date = date("d/m/Y");
		$year = date("Y");
		$next_year_date = '01/01/'.($year+1);
		$current_start_year = '01/01/'.$year;
		$last_start_year = '01/01/'.($year-1);


		$q_maps = $this->dashboard_m->get_map_projects($current_start_year,$current_date);
		$map_details = $q_maps->result();
		$count = 0;


echo "[";
		foreach ($map_details as $map) {

			if($map->y_coordinates != '' && $map->x_coordinates != ''){

				echo '{"longitude":'.$map->y_coordinates.', "latitude": '.$map->x_coordinates.'},';
				$count++;
			}
		}


		echo '],"count": '.$count.'';
	}


	public function focus_get_po_widget(){

		$po_list = $this->purchase_order_m->get_po_list();
		$work_joinery_list = $this->purchase_order_m->get_work_joinery_list();



		$total_price_exgst = 0;
		$total_price_incgst = 0;


		$total_wa_price = 0;
		$total_nsw_price = 0;


		$wa_po_price = 0;
		$wa_po_j_price = 0;

		$nsw_po_price = 0;
		$nsw_po_j_price = 0;

		$gt_cpo_wa = 0;
		$gt_cpo_nsw = 0;



		$year = date("Y");
		$current_date = date("d/m/Y");
		$current_start_year = '01/01/2014';


		$po_list_ordered = $this->purchase_order_m->get_po_list_order_by_project($current_start_year,$current_date);

		foreach ($po_list_ordered->result_array() as $row){
            $work_id = $row['works_id'];


            $po_tot_inv_q = $this->purchase_order_m->get_po_total_paid($work_id);
            $invoiced = 0;
            foreach ($po_tot_inv_q->result_array() as $po_tot_row){
            	$invoiced = $po_tot_row['total_paid'];
            }


            $out_standing = $row['price'] - $invoiced;

        //    $gt_outstanding = $gt_outstanding + $out_standing;


			if( $row['focus_company_id'] == 5 ){
				$gt_cpo_wa = $gt_cpo_wa + $out_standing;
			}


			if( $row['focus_company_id'] == 6 ){
				$gt_cpo_nsw = $gt_cpo_nsw + $out_standing;
			}
		}

		//var_dump($gt_cpo);

/*
		foreach ($po_list->result_array() as $row){

			$price_exgst = $row['price'];
			//$total_wa_price = $total_wa_price + $price_exgst ;

			if( $row['focus_company_id'] == 5 ){
			//	 $wa_po_price = $wa_po_price + $price_exgst;

				$wa_po_price = $wa_po_price + $this->purchase_order->check_balance_po($row['works_id']);


			}


			if( $row['focus_company_id'] == 6 ){

			//	 $nsw_po_price = $nsw_po_price + $price_exgst;


				$nsw_po_price = $nsw_po_price + $this->purchase_order->check_balance_po($row['works_id']);
			}
		}

*/


/*

		foreach ($work_joinery_list->result_array() as $row_j){

			$price_exgst_j = $row['price'];
		//	$total_nsw_price = $total_nsw_price + $price_exgst_j ;

			if( $row['focus_company_id'] == 5 ){



			//	$wa_po_j_price = $wa_po_j_price + $price_exgst_j;


              	$wa_po_j_price = $wa_po_j_price + $this->purchase_order->check_balance_po($row['works_id'],$row['work_joinery_id']);
			}


			if( $row['focus_company_id'] == 6 ){
			//	$nsw_po_j_price = $nsw_po_j_price + $price_exgst_j;

				$nsw_po_j_price = $nsw_po_j_price + $this->purchase_order->check_balance_po($row['works_id'],$row['work_joinery_id']);
			}



		} 
*/

		echo '<p class="value"><span class="col-xs-3">WA</span> <span class="col-xs-9"><i class="fa fa-usd"></i> <strong>'.number_format($gt_cpo_wa,2).'</strong></span></p>';
		echo '<p class="value"><span class="col-xs-3">NSW</span> <span class="col-xs-9"><i class="fa fa-usd"></i> <strong>'.number_format($gt_cpo_nsw,2).'</strong></span></p>';



/*
		$current_date = date("d/m/Y");
		$year = date("Y");
		$next_year_date = '01/01/'.($year+1);
		$current_start_year = '01/01/'.$year;
		$last_start_year = '01/01/'.($year-1);

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();


		foreach ($focus_company as $company) {

			$q_pos = $this->dashboard_m->get_po_per_focus($current_start_year, $next_year_date, $company->company_id);

			$total_pos = array_shift($q_pos->result_array());

			if($company->company_id == 5){
				$key_id = 'WA';
				echo '<p class="value clearfix block"><span class="col-xs-4">WA</span> <span class="col-xs-8"><strong>$ '.number_format($total_pos['total_price']).'</strong></span></p>';
			}elseif($company->company_id == 6){
				$key_id = 'NSW';
				echo '<p class="value clearfix block"><span class="col-xs-4">NSW</span> <span class="col-xs-8"><strong>$ '.number_format($total_pos['total_price']).'</strong></span></p>';
			}else{

			}
		}
*/



	}


	public function focus_top_ten_clients(){

		$current_date = date("d/m/Y");
		$year = date("Y");
		$next_year_date = '01/01/'.($year+1);
		$current_start_year = '01/01/'.$year;
		$last_start_year = '01/01/'.($year-1);

		$q_clients = $this->dashboard_m->get_top_ten_clients($current_start_year, $current_date);
		$client_details  = $q_clients->result();
		$counter = 0;

		foreach ($client_details as $company) {
			$counter ++;
			$total = $company->total_project  + $company->vr_total;
			$q_clients_overall = $this->dashboard_m->get_top_ten_clients_overall($company->company_id);
			$overall_cost = array_shift($q_clients_overall->result_array());
			$grand_total = $overall_cost['total_project']  +  $overall_cost['vr_total'];
			echo '<p> <div class="col-sm-8 col-md-9"><i class="fa fa-chevron-circle-right"></i>  '.$company->company_name.' </div>  <div class="col-sm-4 col-md-3 tooltip-test" title="" data-original-title="All Time: $ '.number_format($grand_total).'"><i class="fa fa-usd"></i> '. number_format($total) .'</p></div><div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';
		}
	}



	public function focus_top_ten_con_sup($type){

		$current_date = date("d/m/Y");
		$year = date("Y");
		$next_year_date = '01/01/'.($year+1);
		$current_start_year = '01/01/'.$year;
		$last_start_year = '01/01/'.($year-1);

		$q_companies = $this->dashboard_m->get_company_sales($type,$current_start_year, $next_year_date);
		$company_details  = $q_companies->result();
		$counter = 0;

		foreach ($company_details as $company) {
			$counter ++;
			$total = $company->total_price;
 
			$q_clients_overall = $this->dashboard_m->get_company_sales_overall($company->company_id);
			$overall_cost = array_shift($q_clients_overall->result_array());
			$grand_total = $overall_cost['total_price'];

			echo '<p> <div class="col-sm-8 col-md-9"><i class="fa fa-chevron-circle-right"></i>  '.$company->contractor_name.' </div>  <div class="col-sm-4 col-md-3 tooltip-test" title="" data-original-title="All Time: $ '.number_format($grand_total).'"><i class="fa fa-usd"></i> '. number_format($total) .'</p></div><div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';
		}
	}


	public function focus_projects_by_type_widget(){

		$current_date = date("d/m/Y");
		$year = date("Y");
		$next_year_date = '01/01/'.($year+1);
		$current_start_year = '01/01/'.$year;
		$last_start_year = '01/01/'.($year-1);

		$q_work = $this->dashboard_m->get_work_types();

		$loop = 0;

		//$projects_rb = $projects_qb->result_array();

		foreach ($q_work->result_array() as $job_category) {
			$cost = 0;
			$variation = 0;
			$grand_total = 0;
			$count = 0;

			$q_projects = $this->dashboard_m->get_projects_by_work_type($current_start_year, $current_date, $job_category['job_category']);
 
			foreach ($q_projects->result_array() as $project) {
				$cost = $cost + $project['project_total'];
				$variation = $variation + $project['variation_total'];
				$count++;
			}

			$grand_total = $cost+$variation;
			$loop++;

 //<strong ><p>WA</p></strong>

			echo '<div id="" class="clearfix"><p> <strong class="col-md-2">'.$count.'</strong><span class="col-md-6 col-sm-4">'.$job_category['job_category'].'</span> <strong class="col-md-4 col-sm-4">$'.number_format($grand_total).'</strong></p>';

				echo '</div>'; 
 
				echo '<div class="col-md-12"><hr class="block m-bottom-5 m-top-5"></div>';
		 



			 
		}
	}

	public function maintanance_average(){

		$days_dif = array();

		$year = date("Y");
		$current_date = date("d/m/Y");
		$current_start_year = '01/01/'.$year;

		$q_maintenance = $this->dashboard_m->get_maitenance_dates($current_start_year,$current_date);
		$maintenance_details  = $q_maintenance->result();


		foreach ($maintenance_details as $maintenance) {
			$date_a = str_replace('/','-',$maintenance->job_date);
			$job_date = date('Y-m-d' , strtotime($date_a));

			$date_b = str_replace('/','-',$maintenance->set_invoice_date);
			$inv_date = date('Y-m-d' , strtotime($date_b));

			$date1 = date_create($job_date);
			$date2 = date_create($inv_date);
			$diff = date_diff($date1,$date2);

		// 	echo   $diff->days.'<br />';
		 	array_push($days_dif, $diff->days);
		}
 
		$size = count($days_dif);
		$average = array_sum($days_dif) / $size;



		arsort($days_dif,1);
	//	var_dump($days_dif);
		$long_day =  max($days_dif);
		$short_day_day =  min($days_dif);

		//over ride
		$short_day_day = 1;

		echo '<p class="value">'.number_format($average,2).' Days <br></p>';
		echo '<p class="">Shortest:'.$short_day_day.' &nbsp; Longest:'.$long_day.'</p>';

 




 
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

		$data['pms_sales_c_year'] = $this->dashboard_m->fetch_pm_sales_year($this_year);

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

				if( $pms_percent[1] == 'focus' ) {
					$comp_id = $pms_percent[0];
					//$val = $pms_percent[3];
					$this->dashboard_m->update_revenue_forecast_indv($value,$comp_id);
				}
			}

			$this->session->set_flashdata('save_success','Forecast Updated'); 
			redirect('/dashboard/sales_forecast/view_'.$forecast_id, 'refresh');

		}

//		redirect('/dashboard/sales_forecast', 'refresh');

	}
}