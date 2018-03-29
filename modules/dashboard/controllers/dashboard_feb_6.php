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


// temporary restriction for dashboaed dev
		$user_role_id = $this->session->userdata('user_role_id');
		if($this->session->userdata('is_admin') == 1 || $user_role_id == 16 || $user_role_id == 3 || $user_role_id == 2 || $user_role_id == 7 || $user_role_id == 6):
		else:		
			redirect('projects', 'refresh');
		endif;
// temporary restriction for dashboaed dev


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

	//	for ($my_year = 2015; $my_year <= $c_year; $my_year++){
			for ($month=1; $month < 13; $month++) { 
			//	if($month > $c_month && $c_year == $c_year){
			//	}else{
				//	echo "$my_year,$month<br />";
					$this->_check_sales($c_year,$month); // automatically updates sales of the current month
				}
		//	}
	//		$this->check_outstanding($my_year);
	//		$this->check_estimates($my_year);
	//	}

	}	

	function index(){
		$this->users->_check_user_access('dashboard',1);

		$data['screen'] = 'Dashboard';
		$user_role_id = $this->session->userdata('user_role_id');
		//Grant acess to Operations Manager

		if($this->session->userdata('is_admin') == 1 || $user_role_id == 16):
			$data['main_content'] = 'dashboard_home';
		elseif($user_role_id == 3):
			$data['main_content'] = 'dashboard_pm';
		elseif($user_role_id == 2):
			$data['main_content'] = 'dashboard_pa';
		elseif($user_role_id == 6):
			$fetch_user= $this->user_model->list_user_short();
			$data['users'] = $fetch_user->result();
			$data['screen'] = 'User Availability';
			$data['main_content'] = 'users/availability';
		elseif($user_role_id == 7):
			$data['main_content'] = 'dashboard_mn';
		else:		
			redirect('projects', 'refresh');
		endif;


	//	redirect('projects', 'refresh');

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$data['project_manager'] = $project_manager->result();

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$data['focus_company'] = $all_focus_company->result();

		$post_months = array("jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec");
		$months = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"); 


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

		$swout = array('');
		$focus_overall_indv = array();
		$focus_wip_overall = array();
		$focus_comp_wip = array();
		$focus_pm_wip = array();


		$wip_date_a = '';
		$wip_date_b = '';

		for ($i=1; $i<13; $i++){

			$month = $i;

			if($i == 12){
				$wip_date_a = "01/$month/$this_year";
				$wip_date_b = "01/01/".($this_year+1);

			}else{
				$wip_date_a = "01/$month/$this_year"; 
				$wip_date_b = "01/".($month+1)."/$this_year";
			}

			$key = $i - 1;
		 	$focus_wip_overall[$key] = $this->get_wip_value_permonth($wip_date_a,$wip_date_b);
		}

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		foreach ($focus_company as $company){
			$forecast_per_comp[$company->company_id] = 0;

			for ($i=1; $i<13; $i++){

				$month = $i;

				if($i == 12){
					$wip_date_a = "01/$month/$this_year";
					$wip_date_b = "01/01/".($this_year+1);

				}else{
					$wip_date_a = "01/$month/$this_year"; 
					$wip_date_b = "01/".($month+1)."/$this_year";
				}

				$key = $i - 1;
				$focus_comp_wip[$company->company_name][$key] = $this->get_wip_value_permonth($wip_date_a,$wip_date_b,$company->company_id);
			}
		}

		$data['focus_comp_wip'] = $focus_comp_wip;
		//var_dump($focus_comp_wip);echo "<br />";




		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();


		foreach ($project_manager_list as $pm ) {
			$pm_name = $pm->user_first_name.' '.$pm->user_last_name;
			for ($i=1; $i<13; $i++){

				$month = $i;

				if($i == 12){
					$wip_date_a = "01/$month/$this_year";
					$wip_date_b = "01/01/".($this_year+1);

				}else{
					$wip_date_a = "01/$month/$this_year"; 
					$wip_date_b = "01/".($month+1)."/$this_year";
				}

				$key = $i - 1;
				$focus_pm_wip[$pm_name][$key] = $this->get_wip_value_permonth($wip_date_a,$wip_date_b,$pm->user_id,1);
			}

		}

		$data['focus_pm_wip'] = $focus_pm_wip;
		$data['focus_wip_overall'] = $focus_wip_overall;

		foreach ($post_months as $key => $value) {
			//echo "$key => $value<br />";

			$samount_val = $sales_focus_company['rev_'.$value];
			$samount_val = ($samount_val <= 0 ? 0 : $samount_val);
			$swout['sales_data_'.$value] = $samount_val;
		} //overall sales computation

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

		$data['pms_sales_last_year'] = $this->dashboard_m->fetch_pm_sales_old_year($this_year-1);
		$data['pms_outstanding_c_year'] = $this->dashboard_m->fetch_pm_oustanding_year($this_year);

		$data['focus_indv_pm_month_sales'] = $this->dashboard_m->fetch_pms_month_sales($rev_month,$this_year);
		$data['focus_indv_focu_month_sales'] = $this->dashboard_m->fetch_comp_month_sales($rev_month,$this_year);
		$data['focus_indv_focu_month_outs'] = $this->dashboard_m->fetch_comp_month_outs($out_month,$this_year);

		$q_focus_pm_forecast = $this->dashboard_m->get_pm_forecast($this_year);
		$data['focus_pm_forecast'] = $q_focus_pm_forecast->result();

		$q_current_forecast_q = $this->dashboard_m->get_current_forecast($this_year,'29','',29);
		$pm_forecast = array_shift($q_current_forecast_q->result_array());
		$total_forecast_maintenance = 0;
		$total_forecast_maintenance = ( $pm_forecast['total'] * (  $pm_forecast['wa_fct']  /100  ) *  ($pm_forecast['forecast_percent']/100) );
		$total_forecast_maintenance = $total_forecast_maintenance + ( $pm_forecast['total'] * (  $pm_forecast['nws_fct']  /100  ) *  ($pm_forecast['nws_fct_b']/100) );
		$data['amount_for_maintenance'] = $total_forecast_maintenance;

		$focus_data_forecast_p = $this->dashboard_m->get_focus_comp_forecast($this_year);
		$q_focus_data_forecast_p = $focus_data_forecast_p->result();
		$forecast_per_comp = array();
		$focus_company = array();

		foreach ($q_focus_data_forecast_p as $ffcp){
			$forecast_percent_val = $ffcp->total * ( $ffcp->forecast_percent / 100 );
			$forecast_per_comp[$ffcp->comp_id] = $forecast_percent_val;
		}

		$data['focus_data_forecast_p'] = $forecast_per_comp;

		$this->load->view('page', $data);
	}



	function get_wip_personal($date_a,$date_b,$pm_id,$comp){
		$q_wip_vales = $this->dashboard_m->get_personal_wip($date_a,$date_b,$pm_id, $comp);
		$wip_values = $q_wip_vales->result();

		$amount = 0;
		$total = 0;
		$count = 0;

		foreach ($wip_values as $prj_wip){
/*
			if($prj_wip->install_time_hrs > 0 || $prj_wip->work_estimated_total > 0.00){
				$amount = $prj_wip->project_total * ($prj_wip->progress_percent/100);
			}else{
				$amount = $prj_wip->budget_estimate_total * ($prj_wip->progress_percent/100);
			}

			if($prj_wip->label == 'VR' && $prj_wip->variation_total > 0.00){
				$amount = $prj_wip->variation_total;
			}

*/
			if($prj_wip->label == 'VR' ){
				$amount = $prj_wip->variation_total;
			}else{
				if($prj_wip->install_time_hrs > 0 || $prj_wip->work_estimated_total > 0.00   ){
					$amount = $prj_wip->project_total * ($prj_wip->progress_percent/100);
				}else{
					$amount = $prj_wip->budget_estimate_total * ($prj_wip->progress_percent/100);
				}
			}



			$total = $total + $amount;
			$count++;

		}

		return $total;
	}


	function get_wip_value_permonth($date_a,$date_b,$comp_id='',$type=''){
		$q_wip_vales = $this->dashboard_m->get_wip_permonth($date_a,$date_b,$comp_id, $type);
		$wip_values = $q_wip_vales->result();

		$amount = 0;
		$total = 0;

		$count = 0;

		foreach ($wip_values as $prj_wip){

			//if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 || $row['variation_total'] > 0.00 ){

/*

			if($prj_wip->install_time_hrs > 0 || $prj_wip->work_estimated_total > 0.00  || $prj_wip->variation_total > 0.00   ){
				$amount = $prj_wip->project_total * ($prj_wip->progress_percent/100);
			}else{
				$amount = $prj_wip->budget_estimate_total * ($prj_wip->progress_percent/100);
			}
*/
/*
			if($prj_wip->project_total > 0   ){
				$amount = $prj_wip->project_total * ($prj_wip->progress_percent/100);
			}else{
				$amount = $prj_wip->budget_estimate_total * ($prj_wip->progress_percent/100);
			}
*/


			if($prj_wip->label == 'VR' ){
				$amount = $prj_wip->variation_total;
			}else{
				if($prj_wip->install_time_hrs > 0 || $prj_wip->work_estimated_total > 0.00   ){
					$amount = $prj_wip->project_total * ($prj_wip->progress_percent/100);
				}else{
					$amount = $prj_wip->budget_estimate_total * ($prj_wip->progress_percent/100);
				}
			}

			$total = $total + $amount;
			$count++;

		}

		return $total;
	}

	function _if_sales_changed($year,$proj_mngr_id,$focus_comp_id,$rev_month,$checkAmount){
	//	echo "-----$year,$proj_mngr_id,$focus_comp_id,$rev_month,$checkAmount----<br />";

		$q_sales = $this->dashboard_m->look_for_sales($year,$proj_mngr_id,$focus_comp_id);
		//$rev_month = 'rev_'.strtolower(date('M'));

		$sales = array_shift($q_sales->result_array());

		//var_dump($sales);
	//	echo "<br />";

		$checkAmount = round($checkAmount,2);

	//	echo "<br /><br />";
	//	echo "<br />$sales[$rev_month] != $checkAmount***$year,$proj_mngr_id,$focus_comp_id,$rev_month,$checkAmount<br />";

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

		$n_month = $c_month+1;
		$n_year = $c_year;

		if($c_month == 12){
			$n_month = 1;
			$n_year = $c_year+1;
		}

		$date_a = "01/$c_month/$c_year";
		$date_b = "01/$n_month/$n_year";

		$mons = array(1 => "jan", 2 => "feb", 3 => "mar", 4 => "apr", 5 => "may", 6 => "jun", 7 => "jul", 8 => "aug", 9 => "sep", 10 => "oct", 11 => "nov", 12 => "dec");

		$rev_month = 'rev_'.$mons[$c_month];
		$q_list_pms = $this->dashboard_m->list_pm_bysales($date_a,$date_b);

//	 echo "$date_a,$date_b,$rev_month ***$c_year,$c_month<br />";


		$pm_data = $this->dashboard_m->fetch_project_pm_nomore();
		$pm_q = array_shift($pm_data->result_array());
		$not_pm_arr = explode(',',$pm_q['user_id'] );



	 $this->dashboard_m->reset_revenue($rev_month,$c_year);




		$list_pms = $q_list_pms->result();
		foreach ($list_pms as $pm_data ){


			$pm_id = $pm_data->user_id;
			$comp_id = $pm_data->focus_company_id;
			if( !in_array($pm_id, $not_pm_arr) ){

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

			$revenue_id = intval($this->_if_sales_changed($currentYear,$pm_data->user_id,$pm_data->focus_company_id,$rev_month,$pm_sale_total));
 



//echo "$currentYear,$pm_data->user_id,$pm_data->focus_company_id,$rev_month,$pm_sale_total **<br />";



			if($revenue_id > 1){
				$this->dashboard_m->update_sales($revenue_id,$rev_month,$pm_sale_total);
				$pm_sale_total= 0;
			}elseif($revenue_id == 0){
				$sales_id = $this->dashboard_m->set_sales($pm_data->user_id, $rev_month, $pm_sale_total, $pm_data->focus_company_id, $currentYear);
				$pm_sale_total= 0;
			}else{

			}	

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

//get sales
	function _get_focus_pm_splits($year,$company_id,$pm_id){

/*
		$date_a = "01/01/$year";
		$date_b = "31/12/$year";
		$total_wip = 0;

			$total_sales = 0;
			$q_pm_sales = $this->dashboard_m->dash_total_pm_sales($pm_id,$year,'',$date_a,$date_b);

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

			$wip_amount = $this->get_wip_value_permonth($date_a,$date_b,$pm_id,1);
			$total_wip = $total_wip + $wip_amount;


$pm_sales = $total_sales+$total_wip;




 

if($focus_copm_total != ''){

	if($pm_sales > 0){
		$shares_focus_comp =  100/($focus_copm_total/$pm_sales);
	}else{
		$shares_focus_comp = 0;
	}

	//return number_format($shares_focus_comp,2);
}else{



}
*/



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

//		
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
		$focus_arr = array(); 
		$focus_invoiced = array(); 

		$c_year = date("Y");
		$c_month = '01';

		$n_year = $c_year+1;
		$n_month = '01';
		
		$date_a = "01/$c_month/$c_year";
		$date_b = "01/$n_month/$n_year";

		$date_c = date("d/m/Y");

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		$grand_total_sales = 0;
		$sales_total = 0;

		$display_total = 0;

		foreach ($focus_company as $company){
			$focus_arr[$company->company_id] = $company->company_name;
			//	echo $company->company_id.'<br />';
			$q_dash_sales = $this->dashboard_m->dash_sales($date_a,$date_c,$company->company_id,1);
			if($q_dash_sales->num_rows >= 1){

				$grand_total_sales = 0;
				$sales_total = 0;

				$dash_sales = $q_dash_sales->result();
				foreach ($dash_sales as $sales){
					if($sales->label == 'VR'){
						$sales_total = $sales->variation_total;
					}else{
						$sales_total = $sales->project_total*($sales->progress_percent/100);
					}

					$grand_total_sales = $grand_total_sales + $sales_total;
				}

				$display_total = $display_total + $grand_total_sales;
			}

			$focus_invoiced[$company->company_id] = $display_total;
			$display_total = 0;
		}

	//	echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($display_total,2).'</strong></p>';


		$proj_t = $this->wip_m->display_all_wip_projects();

		$total_invoiced_init = 0;
		$total_string = '';

		$set_estimates = array();
		$set_quotes = array();
		$set_invoiced = array();

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		foreach ($focus_company as $company){ 
			$set_estimates[$company->company_id] = 0;
			$set_quotes[$company->company_id] = 0;
			$set_invoiced[$company->company_id] = 0;
		}

		foreach ($proj_t->result_array() as $row){
			$quoted = 0;
			$estimated = 0;

			if($this->invoice->if_invoiced_all($row['project_id'])  && $this->invoice->if_has_invoice($row['project_id']) > 0 ){

			}else{
				$comp_id = $row['focus_company_id'];

				if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 || $row['variation_total'] > 0.00 ){
					$quoted = $row['project_total']+$row['variation_total'];
					$set_quotes[$comp_id] = $set_quotes[$comp_id] + $quoted;
				}else{
					$estimated = $row['budget_estimate_total'];
					$set_estimates[$comp_id] = $set_estimates[$comp_id] + $estimated;
				}

				$total_invoiced_init = $this->invoice->get_project_invoiced($row['project_id'],$row['project_total'],$row['variation_total']);
				$set_invoiced[$comp_id] = $set_invoiced[$comp_id] + $total_invoiced_init;
			}
		}

		$total_wip = array();
		foreach ($focus_arr as $comp_id => $value ){
			$total_wip[$comp_id] = ( $set_estimates[$comp_id] + $set_quotes[$comp_id] ) - $set_invoiced[$comp_id];
		}

		foreach ($focus_arr as $comp_id => $value ){
			$company_name = str_replace("Pty Ltd","",$focus_arr[$comp_id]);
			$display_total = $focus_invoiced[$comp_id] + $total_wip[$comp_id];
			if($display_total > 0){
				echo '[\''.$company_name.'\','.$display_total.'],';
			}
		}
	}

	public function pm_type(){
		$pm_type = 0;

		if($this->session->userdata('user_role_id') == 3 && $this->session->userdata('user_department_id') == 1):
			$pm_type = 1;
		endif; //for directors 

		if($this->session->userdata('user_role_id') == 3 && $this->session->userdata('user_department_id') == 4): //for PM 
			$pm_type = 2;
		endif; //for PM 

		return $pm_type;
	}

	public function pa_assignment(){
		$user_id = $this->session->userdata('user_id');
		$pa_data = $this->dashboard_m->fetch_pa_assignment($user_id);
		return array_shift($pa_data->result_array());
	}

	public function invoiced_pa(){
		$assignment = $this->pa_assignment();
		$prime_pm = $assignment['project_manager_primary_id'];
		$group_pm = explode(',', $assignment['project_manager_ids']);

		$c_year = date("Y");
		$n_year = $c_year+1;
		$date_a = "01/01/$c_year";
		$date_b = "01/01/$n_year";
		$date_c = date("d/m/Y");

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();

		$pm_data = array();
		$pm_name = array();

		foreach ($project_manager_list as $pm ){
			if( in_array($pm->user_id, $group_pm) ){
				$pm_data[$pm->user_id] = 0;
				$pm_name[$pm->user_id] = $pm->user_first_name;
			}
		}

		$personal_data = 0;

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();
		$grand_total_sales = 0;
		$sales_total = 0;
		$total_string = '';

		foreach ($focus_company as $company){
			$q_dash_sales = $this->dashboard_m->dash_sales($date_a,$date_c,$company->company_id,1);

			if($q_dash_sales->num_rows >= 1){

				$grand_total_sales = 0;
				$sales_total = 0;

				$dash_sales = $q_dash_sales->result();

				foreach ($dash_sales as $sales){

					if($sales->label == 'VR'){
						$sales_total = $sales->variation_total;
					}else{
						$sales_total = $sales->project_total*($sales->progress_percent/100);
					}

					$grand_total_sales = $grand_total_sales + $sales_total;

					if($prime_pm == $sales->project_manager_id){
						$personal_data = $personal_data + $sales_total;
					}

					if( in_array($sales->project_manager_id, $group_pm) ){
						$pm_data[$sales->project_manager_id] = $pm_data[$sales->project_manager_id] + $sales_total;
					}

				}
			} 
		}

		foreach ($pm_data as $key => $value) {
			$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name[$key].'</span> <span class=\'col-xs-6\'>$ '.number_format($value,2).'</span></div>';
		}

		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';
	}





	public function uninvoiced_widget_pa(){
		$assignment = $this->pa_assignment();
		$prime_pm = $assignment['project_manager_primary_id'];
		$group_pm = explode(',', $assignment['project_manager_ids']);

		$c_year = date("Y");
		$c_month = '01';

		$date_a = "01/01/$c_year";

		$n_year = date("Y");
		$n_month = date("m");
		$n_day = date("d");

		$date_b = "$n_day/$n_month/$n_year";

		$unvoiced_total_arr = array();
		$key_id = '';

		$total_string = '';

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();
		$personal_data = 0;

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();

		$pm_data = array();
		$pm_name = array();

		foreach ($project_manager_list as $pm ){
			if( in_array($pm->user_id, $group_pm) ){
				$pm_data[$pm->user_id] = 0;
				$pm_name[$pm->user_id] = $pm->user_first_name;
			}
		}

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

				$unvoiced_grand_total = $unvoiced_grand_total + $unvoiced_total;

				if($prime_pm == $unvoiced->project_manager_id){
					$personal_data = $personal_data + $unvoiced_total;
				}

				if( in_array($unvoiced->project_manager_id, $group_pm) ){
					$pm_data[$unvoiced->project_manager_id] = $pm_data[$unvoiced->project_manager_id] + $unvoiced_total;
				}
			}
		}

		foreach ($pm_data as $key => $value) {
			$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name[$key].'</span> <span class=\'col-xs-6\'>$ '.number_format($value,2).'</span></div>';
		}

		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';
	}





	public function outstanding_payments_widget_pa(){
		$assignment = $this->pa_assignment();
		$prime_pm = $assignment['project_manager_primary_id'];
		$group_pm = explode(',', $assignment['project_manager_ids']);

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();

		$pm_data = array();
		$pm_name = array();

		foreach ($project_manager_list as $pm ){
			if( in_array($pm->user_id, $group_pm) ){
				$pm_data[$pm->user_id] = 0;
				$pm_name[$pm->user_id] = $pm->user_first_name;
			}
		}

		$c_year = date("Y");
		$date_a = "01/01/$c_year";
		$n_year = date("Y");
		$n_month = date("m");
		$n_day = date("d");

		$date_b = "$n_day/$n_month/$n_year";
 
		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		$total_string = '';
		$personal_data = 0;

		$pm_outstanding = array();
		$display_each_value = 0;

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();

		foreach ($project_manager_list as $pm ) {
			$pm_outstanding[$pm->user_id] = 0;
		}

		$each_comp_total = array();

		foreach ($focus_company as $company){
			$each_comp_total[$company->company_id] = 0;

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

				$total_paid =  $oustanding->amount_exgst;
				$display_each_value = $invoice_amount - $total_paid;
				$pm_outstanding[$oustanding->project_manager_id] = $pm_outstanding[$oustanding->project_manager_id] + $display_each_value;


				$personal_data = $pm_outstanding[$prime_pm];
			}


		}

		foreach ($pm_data as $key => $value) {
			$amount = $pm_outstanding[$key];
			$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name[$key].'</span> <span class=\'col-xs-6\'>$ '.number_format($amount,2).'</span></div>';
		}

		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';
	}






	public function wip_widget_pa(){

		$assignment = $this->pa_assignment();
		$prime_pm = $assignment['project_manager_primary_id'];
		$group_pm = explode(',', $assignment['project_manager_ids']);

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();

		$pm_data = array();
		$pm_name = array();

		foreach ($project_manager_list as $pm ){
			if( in_array($pm->user_id, $group_pm) ){
				$pm_data[$pm->user_id] = 0;
				$pm_name[$pm->user_id] = $pm->user_first_name;
			}
		}

		$total_string = '';
		$start_date = "01/01/".date("Y");
		$n_year =  date("Y")+1;
		$set_new_date = '01/01/'.$n_year;


		$display_total = 0;
		$fYear = intval(date("Y"))+2;

		$date_f = "01/01/$fYear";
		$date_ts = '01/01/1990';


		foreach ($pm_data as $key => $value) {
			$amount = $this->get_wip_value_permonth($start_date,$set_new_date,$key,'1'); 
			$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name[$key].'</span> <span class=\'col-xs-6\'>$ '.number_format($amount,2).'</span></div>';
		}
		
		$display_total = $this->get_wip_value_permonth($start_date,$set_new_date,$prime_pm,'1');

		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($display_total,2).'</strong></p>';
 
	}




	public function focus_projects_count_widget_pa(){
		$assignment = $this->pa_assignment();
		$prime_pm = $assignment['project_manager_primary_id'];
		$group_pm = explode(',', $assignment['project_manager_ids']);

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();

		$pm_data_a = array();
		$pm_data_b = array();

		$pm_name = array();

		foreach ($project_manager_list as $pm ){
			if( in_array($pm->user_id, $group_pm) ){
				$pm_data_a[$pm->user_id] = 0;
				$pm_data_b[$pm->user_id] = 0;
				$pm_name[$pm->user_id] = $pm->user_first_name;
			}
		}

		$personal_data_a = 0;
		$personal_data_b = 0;

		$current_date = date("d/m/Y");
		$year = date("Y");
		$next_year_date = '01/01/'.($year+1);
		$current_start_year = '01/01/'.$year;
		$last_start_year = '01/01/'.($year-1);

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		$total_string_inv  = '';
		$total_string_wip  = '';

		foreach ($focus_company as $company) {
			$invoiced = 0;
			$invoiced_old = 0;

			$projects_qa = $this->dashboard_m->get_wip_invoiced_projects($current_start_year, $next_year_date, $company->company_id);
			$projects_ra = $projects_qa->result_array();

			foreach ($projects_ra as $result) {
				if($this->invoice->if_invoiced_all($result['project_id'])  && $this->invoice->if_has_invoice($result['project_id']) > 0 ){
					$invoiced++;

					if( in_array($result['project_manager_id'], $group_pm) ){
						$pm_data_a[ $result['project_manager_id'] ]++;
					}

					if($prime_pm == $result['project_manager_id']){
						$personal_data_a++;
					}

				}
			}
		}


		$q_maps = $this->dashboard_m->get_map_projects($current_start_year,$current_date);

		//$proj_t = $this->wip_m->display_all_wip_projects();
		foreach ($q_maps->result_array() as $row){
			$comp_id = $row['focus_company_id'];

			if($prime_pm == $row['project_manager_id']){
				$personal_data_b++;
			}

			$pm_id = $row['project_manager_id'];

			if( in_array($pm_id, $group_pm) ){
				$pm_data_b[$pm_id]++;
			}
		}

		 


		$total_string_inv .= 'Invoiced<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';
		$total_string_wip .= 'WIP<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';

		foreach ($group_pm as $key => $value) {
			$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-7\'>'.$pm_name[$value].'</span> <span class=\'col-xs-5\'>'.$pm_data_a[$value].'</span></div>';

			$total_string_wip .= '<div class=\'row\'><span class=\'col-xs-7\'>'.$pm_name[$value].'</span> <span class=\'col-xs-5\'>'.$pm_data_b[$value].'</span></div>';
		}





		echo '<div id="" class="clearfix row">				
		<strong class="text-center col-xs-4"><p class="h5x value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string_inv.'"><i class="fa fa-list-alt"></i> &nbsp;'.$personal_data_a.'</p></strong>
		<strong class="text-center col-xs-4"><p class="h5x value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string_wip.'"><i class="fa fa-tasks"></i> &nbsp;'.$personal_data_b.'</p></strong>
		<strong class="text-center col-xs-4"></strong></div>';

	}




	public function pm_estimates_widget_pa(){
		$assignment = $this->pa_assignment();
		$prime_pm = $assignment['project_manager_primary_id'];
		$group_pm = explode(',', $assignment['project_manager_ids']);

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();

		$pm_data = array();
		$pm_name = array();

		foreach ($project_manager_list as $pm ){
			if( in_array($pm->user_id, $group_pm) ){
				$pm_data[$pm->user_id] = 0;
				$pm_name[$pm->user_id] = $pm->user_first_name;
			}
		}

		$year = date("Y");
		$current_date = date("d/m/Y");
		$current_start_year = '01/01/'.$year;
		$total_string = '';
		$is_restricted = 0;

		$fetch_user = $this->user_model->fetch_user($prime_pm);
		$user_details = array_shift($fetch_user->result_array());

	//	$pm_data = $this->dashboard_m->fetch_project_pm_nomore();
	//	$pm_q = array_shift($pm_data->result_array());
	//	$not_pm_arr = explode(',',$pm_q['user_id'] );


		$admin_defaults = $this->admin_m->fetch_admin_defaults(1);
		foreach ($admin_defaults->result() as $row){
			$unaccepted_date_categories = $row->unaccepted_date_categories;
			$unaccepted_no_days = $row->unaccepted_no_days;
		}

		$amnt = 0;

		//lists estimators not PM sorry
		$unaccepted_amount = array();
		$estimator = $this->dashboard_m->fetch_project_estimators();
		$estimator_list = $estimator->result();

		foreach ($estimator_list as $est ) {
			$unaccepted_amount[$est->project_estiamator_id] = 0;
		}

		$exemp_cat = explode(',', $unaccepted_date_categories);

		$focus_arr = array();
		$project_cost = array();
		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		foreach ($focus_company as $company){
			$focus_arr[$company->company_id] = $company->company_name;
			$project_cost[$company->company_id] = 0;
		}

	 	$q_projects = $this->dashboard_m->get_unaccepted_projects($current_start_year,$current_date);
		$projects = $q_projects->result();

		$personal_data = 0;

		foreach ($projects as $un_accepted){
		//	if( !in_array($un_accepted->project_manager_id, $not_pm_arr) ){

				$unaccepted_date = $un_accepted->unaccepted_date;
				if($unaccepted_date !== ""){
					$unaccepted_date_arr = explode('/',$unaccepted_date);
					$u_date_day = $unaccepted_date_arr[0];
					$u_date_month = $unaccepted_date_arr[1];
					$u_date_year = $unaccepted_date_arr[2];
					$unaccepted_date = $u_date_year.'-'.$u_date_month.'-'.$u_date_day;
				}

				$start_date = $un_accepted->date_site_commencement;
				if($start_date !== ""){
					$start_date_arr = explode('/',$start_date);
					$s_date_day = $start_date_arr[0];
					$s_date_month = $start_date_arr[1];
					$s_date_year = $start_date_arr[2];
					$start_date = $s_date_year.'-'.$s_date_month.'-'.$s_date_day;
				} 

				if( in_array($un_accepted->job_category, $exemp_cat)  ){
					$is_restricted = 1;
				}else{
					$is_restricted = 0;
				}

				$today = date('Y-m-d');
				$unaccepteddate = strtotime ( '-'.$unaccepted_no_days.' day' , strtotime ( $start_date ) ) ;
				$unaccepteddate = date ( 'Y-m-d' , $unaccepteddate );

				if(strtotime($unaccepteddate) < strtotime($today)){
					if($is_restricted == 1){
						if($unaccepted_date == ""){
							$status = 'quote';
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

				if ($status == 'unset'){
					
					if( $un_accepted->focus_company_id == $user_details['user_focus_company_id'] ){
						if($un_accepted->install_time_hrs > 0 || $un_accepted->work_estimated_total > 0.00 || $un_accepted->variation_total > 0.00 ){
							$amnt =  $un_accepted->project_total + $un_accepted->variation_total;
							$project_cost[$un_accepted->focus_company_id] =  $project_cost[$un_accepted->focus_company_id] + $amnt;
						}else{
							$amnt = $un_accepted->budget_estimate_total;
							$project_cost[$un_accepted->focus_company_id] =  $project_cost[$un_accepted->focus_company_id] + $amnt; 
						}

						if( isset($unaccepted_amount[$un_accepted->project_estiamator_id])) {
							

							if($prime_pm == $un_accepted->project_manager_id){
								$unaccepted_amount[$un_accepted->project_estiamator_id] = $unaccepted_amount[$un_accepted->project_estiamator_id] + $amnt;
							}




						}
					}
 
					if( in_array($un_accepted->project_manager_id, $group_pm) ){
						$pm_data[$un_accepted->project_manager_id] = $pm_data[$un_accepted->project_manager_id] + $amnt;
					}

					if($prime_pm == $un_accepted->project_manager_id){
						$personal_data = $personal_data + $amnt;
					}

				}
			//}
		}

		//var_dump($project_cost);

		foreach ($focus_arr as $comp_id => $value ){
			$display_total_cmp = $project_cost[$comp_id];
			if($display_total_cmp > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cmp,2).'</span></div>';
			}
		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';

		foreach ($pm_data as $key => $value) {
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name[$key].'</span> <span class=\'col-xs-6\'>$ '.number_format($value,2).'</span></div>';
		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';


		foreach ($estimator_list as $est ) {
			$display_total_cmp = $unaccepted_amount[$est->project_estiamator_id];
			$pm_name = $est->user_first_name;
			if($display_total_cmp > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name.'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cmp,2).'</span></div>';
			}
		}

		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';
	}



	public function focus_get_po_widget_pa(){
		$assignment = $this->pa_assignment();
		$prime_pm = $assignment['project_manager_primary_id'];
		$group_pm = explode(',', $assignment['project_manager_ids']);

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();

		$pm_data = array();
		$pm_name = array(); 

		foreach ($project_manager_list as $pm ){
			if( in_array($pm->user_id, $group_pm) ){
				$pm_data[$pm->user_id] = 0;
				$pm_name[$pm->user_id] = $pm->user_first_name;
			}
		}

		$year = date("Y");
		$current_date = '01/01/2019';
		$current_start_year = '01/01/2000';
	//	$set_cpo = array();
/*
		$focus_arr = array();
		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		foreach ($focus_company as $company){
			$focus_arr[$company->company_id] = $company->company_name;
			$set_cpo[$company->company_id] = 0;
		}
*/
		$personal_data = 0;

		$po_list_ordered = $this->purchase_order_m->get_po_list_order_by_project($current_start_year,$current_date);

		foreach ($po_list_ordered->result_array() as $row){
			$work_id = $row['works_id'];

			$po_tot_inv_q = $this->purchase_order_m->get_po_total_paid($work_id);
			$invoiced = 0;
			foreach ($po_tot_inv_q->result_array() as $po_tot_row){
				$invoiced = $po_tot_row['total_paid'];
			}

			$out_standing = $row['price'] - $invoiced;
			
/*
			$comp_id = $row['focus_company_id'];

			if( in_array($comp_id, $direct_company) ){
				$set_cpo[$comp_id] = $set_cpo[$comp_id] + $out_standing;
			}
*/
			if( in_array($row['project_manager_id'], $group_pm) ){
				$pm_data[$row['project_manager_id']] = $pm_data[$row['project_manager_id']] + $out_standing;

			}

			if($prime_pm == $row['project_manager_id']){
				$personal_data = $personal_data + $out_standing;
			}
		}

		$total_string = '';
/*
		foreach ($focus_arr as $comp_id => $value ){
			if( in_array($comp_id, $direct_company) ){
				$display_total_cpo = $set_cpo[$comp_id];

				if($display_total_cpo > 0){
					$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cpo,2).'</span></div>';
				}
			}
		}
*/
		foreach ( $pm_data as $key => $value) {
			$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name[$key].'</span> <span class=\'col-xs-6\'>$ '.number_format($value,2).'</span></div>';
		}

 
		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';
	}





	public function focus_get_map_locations_pa(){

		$assignment = $this->pa_assignment();
		$prime_pm = $assignment['project_manager_primary_id'];
		$group_pm = explode(',', $assignment['project_manager_ids']);

  
 

		$current_date = date("d/m/Y");
		$year = date("Y");
		$next_year_date = '01/01/'.($year+1);
		$current_start_year = '01/01/'.$year;
		$last_start_year = '01/01/'.($year-1);

		$q_maps = $this->dashboard_m->get_map_projects($current_start_year,$current_date,$prime_pm);
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


	public function wid_site_labour_hrs(){
		$current_date = date("Y-m-d");
		$total_string = '';
		$display_total = 0;

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();
		$focus_company_hrs = array();
		foreach ($focus_company as $company){
			$focus_arr[$company->company_id] = $company->company_name;
			$focus_company_hrs[$company->company_id] = 0;
		}

		$states = $this->dashboard_m->get_all_states();
		$states_list = $states->result();
		$states_name = array();
		$hrs_states = array();
		foreach ($states_list as $sts ) {
			$hrs_states[$sts->id] = 0;
			$states_name[$sts->id] = $sts->shortname;
		}



		$days_q = $this->dashboard_m->get_site_labour_hrs($current_date);
		foreach ($days_q->result_array() as $labor_hrs) {

			$comp_id = $labor_hrs['focus_company_id'];
			$state_id = $labor_hrs['state_id'];
			$focus_company_hrs[$comp_id] = $focus_company_hrs[$comp_id] + $labor_hrs['time'];
			//$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-6\'>'.number_format($labor_hrs['time'],2).'</span></div>';
			$display_total = $display_total + $labor_hrs['time'];
			$hrs_states[$state_id] = $hrs_states[$state_id] + $labor_hrs['time']; 
		}

		foreach ($focus_company as $company){
			if($focus_company_hrs[$company->company_id] > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$company->company_id]).'</span> <span class=\'col-xs-6\'>'.number_format($focus_company_hrs[$company->company_id],2).'</span></div>';
			}
		}


		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';

		foreach ($states_list as $sts){
			if($hrs_states[$sts->id] > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$states_name[$sts->id].'</span> <span class=\'col-xs-6\'>'.number_format($hrs_states[$sts->id],2).'</span></div>';
			}
		}


		$last_year = intval(date("Y"))-1;
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; Last Year '.$last_year.'</div>';

		$old_year = $last_year.'-'.date('m-d');

		$days_q = $this->dashboard_m->get_site_labour_hrs($current_date);
		foreach ($days_q->result_array() as $labor_hrs) {

			$comp_id = $labor_hrs['focus_company_id'];
			$state_id = $labor_hrs['state_id'];
			$focus_company_hrs[$comp_id] = $focus_company_hrs[$comp_id] + $labor_hrs['time'];
			//$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-6\'>'.number_format($labor_hrs['time'],2).'</span></div>';
			//$display_total = $display_total + $labor_hrs['time'];
			$hrs_states[$state_id] = $hrs_states[$state_id] + $labor_hrs['time']; 
		}

		foreach ($focus_company as $company){
			if($focus_company_hrs[$company->company_id] > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$company->company_id]).'</span> <span class=\'col-xs-6\'>'.number_format($focus_company_hrs[$company->company_id],2).'</span></div>';
			}
		}


		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';

		foreach ($states_list as $sts){
			if($hrs_states[$sts->id] > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$states_name[$sts->id].'</span> <span class=\'col-xs-6\'>'.number_format($hrs_states[$sts->id],2).'</span></div>';
			}
		}




		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><strong>'.number_format($display_total,2).'</strong></p>';

 /*
		$admin_defaults = $this->admin_m->fetch_admin_defaults(1);
		$cat_not_included = '';
		foreach ($admin_defaults->result() as $row){
			$labour_sched_categories = $row->labour_sched_categories;

		}

		$labour_sched_categories = explode(',',$labour_sched_categories);
 */

	//	var_dump($labour_sched_categories);
	}



	public function wid_quoted(){
		$total_string = '';
		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();
		$focus_arr = array();
		$quoted_focus_company = array();
		$cost_focus = array();
		foreach ($focus_company as $company){
			$focus_arr[$company->company_id] = $company->company_name;
			$quoted_focus_company[$company->company_id] = 0;
			$cost_focus[$company->company_id] = 0;
		}

		$estimator = $this->dashboard_m->fetch_project_estimators();
		$estimator_list = $estimator->result();
		$quoted_estimator = array();
		$quoted_estimator_name = array();
		$cost_estimator = array();
		foreach ($estimator_list as $est ) {
			$quoted_estimator[$est->project_estiamator_id] = 0;
			$cost_estimator[$est->project_estiamator_id] = 0;
			$quoted_estimator_name[$est->project_estiamator_id] = $est->user_first_name;
		}
		$quoted_estimator_name[0] = '';

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();
		$quoted_pm = array();
		$quoted_pm_name = array();
		$cost_pm = array();
		foreach ($project_manager_list as $pm ) {
			$quoted_pm[$pm->user_id] = 0;
			$cost_pm[$pm->user_id] = 0;
			$quoted_pm_name[$pm->user_id] = $pm->user_first_name;
		}

		$is_restricted = 0;

		$admin_defaults = $this->admin_m->fetch_admin_defaults(1);
		foreach ($admin_defaults->result() as $row){
			