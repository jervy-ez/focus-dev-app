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


		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><strong>'.number_format($display_total,2).'</strong></p>';


		$admin_defaults = $this->admin_m->fetch_admin_defaults(1);
		$cat_not_included = '';
		foreach ($admin_defaults->result() as $row){
			$labour_sched_categories = $row->labour_sched_categories;

		}

		$labour_sched_categories = explode(',',$labour_sched_categories);


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
/*
		$states = $this->dashboard_m->get_all_states();
		$states_list = $states->result();
		$quoted_states = array();
		$states_name = array();
		$cost_states = array();
		foreach ($states_list as $sts ) {
			$quoted_states[$sts->id] = 0;
			$cost_states[$sts->id] = 0;
			$states_name[$sts->id] = $sts->shortname;
		}
*/

		$is_restricted = 0;

		$admin_defaults = $this->admin_m->fetch_admin_defaults(1);
		foreach ($admin_defaults->result() as $row){
			$unaccepted_date_categories = $row->unaccepted_date_categories;
			$unaccepted_no_days = $row->unaccepted_no_days;
		}


		$all_projects_q = $this->dashboard_m->get_all_active_projects();


		foreach ($all_projects_q->result_array() as $row){

			$project_cost = 0;

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



			if($row['job_date'] == '' && $row['is_paid'] == 0){
				$job_category_arr = explode(",",$unaccepted_date_categories);
				foreach ($job_category_arr as $value) {
					if($value ==  $row['job_category']){
						$is_restricted = 1;
					}
				}

				$today = date('Y-m-d');
				$unaccepteddate =strtotime ( '-'.$unaccepted_no_days.' day' , strtotime ( $start_date ) ) ;
				$unaccepteddate = date ( 'Y-m-d' , $unaccepteddate );

				if(strtotime($unaccepteddate) < strtotime($today)){
					if($is_restricted == 1 && $unaccepted_date == ""){
						$status = 'quote';
					}
				}elseif($unaccepted_date == ""){
					$status = 'quote';
				}else{


				}




			}

			if($status == 'quote'){
				$quoted_estimator[$row['project_estiamator_id']]++;
				$quoted_pm[$row['project_manager_id']]++;
				$quoted_focus_company[$row['focus_company_id']]++;

				if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 || $row['variation_total'] > 0.00 ){
					$project_cost = $row['project_total'] + $row['variation_total'];
				}else{
					$project_cost = $row['budget_estimate_total'];
				}

				$cost_focus[$row['focus_company_id']] = $cost_focus[$row['focus_company_id']] + $project_cost;
				$cost_estimator[$row['project_estiamator_id']] = $cost_estimator[$row['project_estiamator_id']] + $project_cost;
				$cost_pm[$row['project_manager_id']] = $cost_pm[$row['project_manager_id']] + $project_cost;

			//	$cost_states[$row['state_id']] = $cost_states[$row['state_id']] + $project_cost;

			}
		}

		foreach ($focus_company as $company){
			if($quoted_focus_company[$company->company_id] > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$company->company_id]).'</span> <span class=\'col-xs-6\'>$  '.number_format($cost_focus[$company->company_id],2).' <span class=\'pull-right\'>'.$quoted_focus_company[$company->company_id].'</span></span></div>';
			}
		}
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';

		foreach ($project_manager_list as $pm ) {
			$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$quoted_pm_name[$pm->user_id].'</span> <span class=\'col-xs-6\'>$  '.number_format($cost_pm[$pm->user_id],2).' <span class=\'pull-right\'>'.$quoted_pm[$pm->user_id].'</span></span></div>';
		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';

		foreach ($estimator_list as $est ) {
			$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$quoted_estimator_name[$est->project_estiamator_id].'</span> <span class=\'col-xs-6\'>$ '.number_format($cost_estimator[$est->project_estiamator_id],2).' <span class=\'pull-right\'>'.$quoted_estimator[$est->project_estiamator_id].'</span></span></div>';
		
		}

		// $total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';

		// foreach ($states_list as $sts ) {
		// 	if($cost_states[$sts->id] > 0){
		// 		$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$states_name[$sts->id].'</span> <span class=\'col-xs-6\'>$ '.number_format($cost_states[$sts->id],2).'</span></div>';
		// 	}
		// }

		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><strong><i class="fa fa-usd"></i> '.number_format(array_sum($cost_focus),2).' <span class="pull-right">'.array_sum($quoted_focus_company).'</span></strong></p>';
	}




	public function focus_projects_by_type_widget_pa(){

		$assignment = $this->pa_assignment();
		$prime_pm = $assignment['project_manager_primary_id'];
		$group_pm = explode(',', $assignment['project_manager_ids']);

		$fetch_user = $this->user_model->fetch_user($prime_pm);
		$user_details = array_shift($fetch_user->result_array());

		$current_date = date("d/m/Y");
		$year = date("Y");
		$current_start_year = '01/01/'.$year;


		$comp_id = 0;

		$focus_arr = array();
		$focus_prjs = array();
		$focus_costs = array();


		$focus_catgy = array();
		$focus_catgy_name = array();
		$focus_catgy_costs = array();

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();
		foreach ($focus_company as $company){
			$focus_arr[$company->company_id] = $company->company_name;
			$focus_prjs[$company->company_id] = 0;
			$focus_costs[$company->company_id] = 0;
		}

		$q_work = $this->dashboard_m->get_work_types();
		foreach ($q_work->result_array() as $job_category) {
			$cat_id =  strtolower(str_replace(" ","_",$job_category['job_category']));
			$focus_catgy[$cat_id] = 0;
			$focus_catgy_costs[$cat_id] = 0;
			$focus_catgy_name[$cat_id] = $job_category['job_category'];
		}

		$cost = 0;
		$variation = 0; 
		$grand_prj_total = 0;

		$q_projects = $this->dashboard_m->get_projects_by_work_type($current_start_year, $current_date);
		foreach ($q_projects->result_array() as $project){

			if( $project['focus_company_id'] == $user_details['user_focus_company_id'] ){
				$cost = $cost + $project['project_total'];
				$variation = $variation + $project['variation_total'];
				$comp_id = $project['focus_company_id'];

				$focus_prjs[$comp_id]++;
				$cat_id =  strtolower(str_replace(" ","_",$project['job_category']));
				$focus_catgy[$cat_id]++;

				$focus_catgy_costs[$cat_id] = $focus_catgy_costs[$cat_id] + $project['project_total'] + $project['variation_total'];
				$focus_costs[$comp_id] = $focus_costs[$comp_id] + $project['project_total'] + $project['variation_total'];
				$grand_prj_total = $grand_prj_total +  $project['project_total'];
			}
		}

		$total_count_cat = array_sum($focus_catgy);

		foreach ($focus_catgy_name as $cat_id => $value){
			$cost = $focus_catgy_costs[$cat_id];
			$count = $focus_catgy[$cat_id];

			

			$grand_prj_total = ($grand_prj_total <= 1 ? 1 : $grand_prj_total);
			$cost = ($cost <= 1 ? 1 : $cost);


			if($cost>0){
				$percent = round(100/($grand_prj_total/$cost));
			}else{
				$percent = round(100/($grand_prj_total/1));
			}

			$grand_prj_total = ($grand_prj_total == 1 ? 0 : $grand_prj_total);
			$cost = ($cost == 1 ? 0 : $cost);

			if($grand_prj_total == 0 && $cost == 0){
				$percent = 0;
			}


			echo '<div id="" class="clearfix"><p><strong class="col-sm-2">'.$count.'</strong> <strong class="col-sm-4">'.$value.'</strong><strong class="col-sm-2">'.$percent.'% </strong><strong class="col-sm-4">$ '.number_format(round($cost)).'</strong></p></div>';
			echo '<div class="col-md-12"><hr class="block m-bottom-5 m-top-5"></div>';
		}


	}


	public function focus_top_ten_con_sup_pa($type){

		$assignment = $this->pa_assignment();
		$prime_pm = $assignment['project_manager_primary_id'];
		$group_pm = explode(',', $assignment['project_manager_ids']);

		$fetch_user = $this->user_model->fetch_user($prime_pm);
		$user_details = array_shift($fetch_user->result_array());
 
		$comp_q = '';
		$comp_q .= 'AND (';
			$comp_q .= '`project`.`focus_company_id` = '.$user_details['user_focus_company_id'].'';
		$comp_q .= ')';



	//if( in_array($project['focus_company_id'], $direct_company) ){


		$current_date = date("d/m/Y");
		$year = date("Y");

		$last_year = intval(date("Y")) - 1;

		$base_year = '01/01/'.$year;

		$next_year_date = '01/01/'.$last_year;
		$current_start_year = date("d/m/Y");
		$last_start_year = '01/01/'.$last_year;

		$q_companies = $this->dashboard_m->get_company_sales($type,$base_year,$current_start_year,'',$comp_q);
		$company_details  = $q_companies->result();
		$counter = 0;


		$list_total = 0;

		foreach ($company_details as $company) {
			$list_total = $list_total + $company->total_price;
		}

		foreach ($company_details as $company) {
			$counter ++;
			$total = $company->total_price;
			$percent = round(100/($list_total/$company->total_price));

			$q_clients_overall = $this->dashboard_m->get_company_sales_overall($company->company_id);
			$overall_cost = array_shift($q_clients_overall->result_array());
			$grand_total = $overall_cost['total_price'];

			echo '<div class="col-sm-8 col-md-8"><i class="fa fa-chevron-circle-right"></i>  &nbsp; ';

			$comp_name = $company->company_name;
			if(strlen($comp_name) > 40){
				echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$comp_name.'">'.substr($comp_name,0,40).'...</span>';
			}else{
				echo $comp_name;
			}

			$cmp_id = $company->company_id;

			$last_year_q = $this->dashboard_m->get_company_sales('',$base_year,$current_start_year,$cmp_id,$comp_q);
			$last_year_sale = array_shift($last_year_q->result_array());
			$lst_year_total = $last_year_sale['total_price'];

			echo ' </div><div class="col-md-1 col-sm-4"><strong>'.$percent.'%</strong></div>  <div class="col-md-3 col-sm-4 tooltip-test" title="" data-placement="left" data-original-title="Last Year : $ '.number_format($lst_year_total).'"><i class="fa fa-usd"></i> '.number_format($company->total_price).'</div><div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';
		}


	}


 


	public function focus_top_ten_clients_pa(){
		$assignment = $this->pa_assignment();
		$prime_pm = $assignment['project_manager_primary_id'];
		$group_pm = explode(',', $assignment['project_manager_ids']);

		$fetch_user = $this->user_model->fetch_user($prime_pm);
		$user_details = array_shift($fetch_user->result_array());
 
		$comp_q = '';
		$comp_q .= 'AND (';
		$comp_q .= '`project`.`focus_company_id` = '.$user_details['user_focus_company_id'].'';
		$comp_q .= ')';
		 
		$current_date = date("d/m/Y");
		$year = date("Y");
		$next_year_date = '01/01/'.($year+1);
		$current_start_year = '01/01/'.$year;
		$last_start_year = '01/01/'.($year-1);

		$q_clients = $this->dashboard_m->get_top_ten_clients($current_start_year, $current_date,'','',$comp_q);
		$client_details  = $q_clients->result();
		
		$list_total = 0;

		//if( in_array($project['focus_company_id'], $direct_company) ){

		foreach ($client_details as $company) {
			$q_vr_c_t = $this->dashboard_m->client_vr_value($current_start_year,$current_date,$company->client_id,$comp_q);
			$vr_val_t = array_shift($q_vr_c_t->result_array());
			$list_total = $list_total + $company->grand_total + $vr_val_t['total_variation'];
		}

		$last_year = intval(date("Y")) - 1;
		$this_month = date("m");
		$this_day = date("d");
 
		$date_a_last = "01/01/$last_year";
		$date_b_last = "$this_day/$this_month/$last_year";

		foreach ($client_details as $company) {
			$percent = round(100/($list_total/$company->grand_total));
			echo '<div class="col-sm-8 col-md-8"><i class="fa fa-chevron-circle-right"></i>  &nbsp; ';

			$q_vr_c = $this->dashboard_m->client_vr_value($current_start_year,$current_date,$company->client_id,$comp_q);
			$vr_val = array_shift($q_vr_c->result_array());

			$comp_name = $company->company_name;
			if(strlen($comp_name) > 40){
				echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$comp_name.'">'.substr($comp_name,0,40).'...</span>';
			}else{
				echo $comp_name;
			}

			$q_vr_c_u = $this->dashboard_m->client_vr_value($date_a_last,$date_b_last,$company->client_id,$comp_q);
			$vr_val_u = array_shift($q_vr_c_u->result_array());

			$last_year_q = $this->dashboard_m->get_top_ten_clients($date_a_last, $date_b_last,$company->company_id);
			$last_year_sale = array_shift($last_year_q->result_array());
			$lst_year_total = $last_year_sale['grand_total'] + $vr_val_u['total_variation'];
			echo ' </div><div class="col-md-1 col-sm-4"><strong>'.$percent.'%</strong></div>  <div class="col-md-3 col-sm-4 tooltip-test" title="" data-placement="left" data-original-title="Last Year : $ '.number_format($lst_year_total).'"><i class="fa fa-usd"></i> '.number_format($company->grand_total+ $vr_val['total_variation']).'</div><div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';
		}




	}



	public function average_date_invoice_pa(){

		$assignment = $this->pa_assignment();
		$prime_pm = $assignment['project_manager_primary_id'];
		$group_pm = explode(',', $assignment['project_manager_ids']);

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();

		$pm_data = array();
		$pm_name = array();

		$pms_averg = array();
		$pms_w_avg = array();

		foreach ($project_manager_list as $pm ){
			if( in_array($pm->user_id, $group_pm) ){
				$pm_data[$pm->user_id] = 0;
				$pm_name[$pm->user_id] = $pm->user_first_name;

				$pms_averg[$pm->user_id] = array();
				$pms_w_avg[$pm->user_id] = $pm->user_id;
			}
		}


		$c_year = date("Y");		
		$date_a = "01/01/$c_year";
		$date_b = date("d/m/Y");
		$days_dif = array();

		$long_day = 0;
		$size = 0;
		$short_day_day = 0;



		$fetch_user = $this->user_model->fetch_user($prime_pm);
		$user_details = array_shift($fetch_user->result_array());
		
 

 	//	foreach ($direct_company as $key => $comp_id) {
			$q_ave = $this->dashboard_m->get_maitenance_dates_pm($date_a,$date_b,$prime_pm,$user_details['user_focus_company_id']); //4
			$days_result = $q_ave->result();

			foreach ($days_result as $result){
						$diff = ($result->days_diff < 0 ? 0 : $result->days_diff);
				array_push($days_dif, $diff);
			}

		//	if($pm_type == 1){ // for director/pm
				foreach ($project_manager_list as $pm ) {


					if(in_array($pm->user_id, $group_pm) ){

					$q_ave_pm = $this->dashboard_m->get_maitenance_dates_pm($date_a,$date_b,$pm->user_id,$user_details['user_focus_company_id']); //4
					$days_result_pm = $q_ave_pm->result();

					foreach ($days_result_pm as $result_pm){
						$diff = ($result_pm->days_diff < 0 ? 0 : $result_pm->days_diff);
						array_push($pms_averg[$pm->user_id],$diff);
					}
				}
				}
		//	}
 	//	}



 

//		var_dump($days_dif);
 
		$size = count($days_dif);

		if($size > 0){
			$average = array_sum($days_dif) / $size;
			arsort($days_dif,1);
			$long_day =  max($days_dif);
			$short_day_day =  min($days_dif);
			$short_day_day = 1;
		}else{
			$average = 0;
			$long_day = 0;
			$short_day_day = 0;
		}

		$total_string = '';

 

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();


		foreach ($focus_company as $company){

			if( $company->company_id == $user_details['user_focus_company_id'] ){

				$days_dif_comp = array('');

				$q_ave = $this->dashboard_m->get_maitenance_dates_pm($date_a,$date_b,$company->company_id); //5
				$days_result = $q_ave->result();

				foreach ($days_result as $result){
					if($result->project_manager_id != 9){
						$diff = ($result->days_diff < 0 ? 0 : $result->days_diff);
						array_push($days_dif_comp, $diff);
					}
				}



				$size = count($days_dif_comp);
				$average_comp = array_sum($days_dif_comp) / $size;

				arsort($days_dif_comp,1);
 
				$long_day_comp =  max($days_dif_comp);
				$short_day_day_comp =  min($days_dif_comp);
				$short_day_day_comp = 1;
 
				// $total_string .= '<span>'.str_replace("Pty Ltd","",$company->company_name).' &nbsp; '.number_format($average_comp,1).'</span><br />';


				$total_string .= '<div class=\'row\'><span class=\'col-xs-8\'>'.str_replace("Pty Ltd","",$company->company_name).'</span><span class=\'col-xs-4\'>'.round($average_comp,1).'</span></div>';

			}
		}

// 	var_dump($pms_averg);
 

/*
		if($pm_type == 1 &&  in_array($company->company_id, $direct_company)     ){


		}


*/

	//		if($pm_type == 1){ // for director/pm

				$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';



				foreach ($project_manager_list as $pm ){

					if(in_array($pm->user_id, $group_pm) ){



					if( count($pms_averg[$pm->user_id]) > 0  ){
						//	var_dump($pms_averg[$pm->user_id]);
						//	echo "<p></p>"; 
						$size = count($pms_averg[$pm->user_id]);

						//if($size > 0){
						$pm_average = array_sum($pms_averg[$pm->user_id]) / $size;
						arsort($pms_averg[$pm->user_id],1);
						$pm_long_day =  max($pms_averg[$pm->user_id]);
						$pm_short_day_day =  min($pms_averg[$pm->user_id]);
						$pm_short_day_day = 1;
						/*}else{
							$pm_average = 0;
							$pm_long_day = 0;
							$pm_short_day_day = 0;
						}*/

						$pm_name = $pm->user_first_name;
						$total_string .= '<div class=\'row\'><span class=\'col-xs-8\'>'.$pm_name.'</span><span class=\'col-xs-4\'>'.round($pm_average,1).'</span></div>';
					}
				}
				}
		//	} 


		echo '<div id="" class="tooltip-enabled" title="" data-placement="bottom" data-html="true" data-original-title="'.$total_string .'">
				<input class="knob" data-width="100%" data-step=".1"  data-thickness=".13" value="'.number_format($average,1).'" readonly data-fgColor="#964dd7" data-angleOffset="-180"  data-max="'.$long_day.'">
				<div id="" class="clearfix m-top-10">
					<div id="" class="col-xs-6"><strong><p>'.$short_day_day.' min</p></strong></div>
					<div id="" class="col-xs-6 text-right"><strong><p>max '.$long_day.'</p></strong></div>
				</div>
			</div>';

	}




	public function pm_sales_widget_pa(){

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

		$grand_total_sales_cmp = 0;
		$grand_total_uninv_cmp = 0;
		$grand_total_over_cmp = 0;

		$c_year = date("Y");		
		$date_a = "01/01/$c_year";
		$date_b = date("d/m/Y");
		$n_year =  date("Y")+1;
		$set_new_date = '01/01/'.$n_year;
		$date_c = date("d/m/Y");
 

		$pm_set_data = array();
		$wip_pm_total = array();

		$overall_total_sales = 0;
		$sales_result = array();
		$focus_pms = array();
		$focus_pm_pic = array();
		$focus_pm_comp = array();

		$set_invoiced_amount = array();

		$total_invoiced_init = 0;
		$total_string = '';

		$return_total = 0;

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();

		foreach ($project_manager_list as $pm ) {
			$set_invoiced_amount[$pm->user_id] = 0;
			$sales_result[$pm->user_id] = 0;
			$focus_pms[$pm->user_id] = $pm->user_first_name;
			$focus_pm_pic[$pm->user_id] = $pm->user_profile_photo;
			$focus_pm_comp[$pm->user_id] = $pm->user_focus_company_id;

			$wip_pm_total[$pm->user_id] = 0;
			$sales_result[$pm->user_id] = 0;

			if( in_array($pm->user_id, $group_pm) ){
				$pm_set_data[$pm->user_id] = $pm->user_id;
			}
		}

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		foreach ($focus_company as $company){
 
				$q_dash_sales = $this->dashboard_m->dash_sales($date_a,$date_c,$company->company_id,1);

				if($q_dash_sales->num_rows >= 1){

					$grand_total_sales = 0;
					$sales_total = 0;

					$dash_sales = $q_dash_sales->result();

					foreach ($dash_sales as $sales){
						if( in_array($sales->project_manager_id, $group_pm) ){

							if($sales->label == 'VR'){
								$sales_total = $sales->variation_total;
							}else{
								$sales_total = $sales->project_total*($sales->progress_percent/100);
							}

							$set_invoiced_amount[$sales->project_manager_id] = $set_invoiced_amount[$sales->project_manager_id] + $sales_total;
							$pm_set_data[$sales->project_manager_id] = $sales->project_manager_id;

							$grand_total_sales_cmp = $grand_total_sales_cmp + $sales_total;

						}
					}					
				} 
		}

		$forecast_focus_total = 0; 
		foreach ($project_manager_list as $pm ) {

			if( in_array($pm->user_id, $group_pm) ){
				$total_sales = 0;
				$total_outstanding = 0;

				$q_pm_sales = $this->dashboard_m->dash_total_pm_sales($pm->user_id,$c_year,'',$date_a,$date_b,$pm->user_focus_company_id);
				$pm_sales = $q_pm_sales->result_array();

				foreach ($pm_sales as $sales => $value){

					if($value['label'] == 'VR'){
						$project_total_percent = $value['variation_total'];
					}else{
						$project_total_percent = $value['project_total'] * ($value['progress_percent']/100);
					}
				}

			}

		}
 

//		foreach ($direct_company as $key => $comp_id) {
		foreach ($focus_company as $company){

			foreach ($pm_set_data as $pm_id => $value){
				//ion get_wip_perso

				if( in_array($pm_id, $group_pm) ){
					$wip_amount = $this->get_wip_personal($date_a,$set_new_date,$pm_id,$company->company_id);
					$wip_pm_total[$pm_id] = $wip_pm_total[$pm_id] + $wip_amount;
					$sales_result[$pm_id] = $sales_result[$pm_id] + $wip_amount + $set_invoiced_amount[$pm_id];

				//	echo $comp_id.'---'.$pm_id.'---'.$wip_amount.'<br />';
				}
			}

		}


		arsort($sales_result);
		$total_wip = array_sum($wip_pm_total);

		$total_invoiced = array_sum($set_invoiced_amount);
		echo "<div style=\"overflow-y: auto; padding-right: 5px; height: 400px;\">";

		foreach ($sales_result as $pm_id => $sales){

			if( in_array($pm_id, $group_pm) ){

			//	if( $sales > 0){
					$comp_id_pm = $focus_pm_comp[$pm_id];

					//$pm_wip = (( $set_estimates[$pm_id] + $set_quotes[$pm_id] ) - $set_invoiced[$pm_id] );

					$q_current_forecast_comp = $this->dashboard_m->get_current_forecast($c_year,$comp_id_pm,'1');
					$comp_forecast = array_shift($q_current_forecast_comp->result_array());

					$q_current_forecast = $this->dashboard_m->get_current_forecast($c_year,$pm_id);
					$pm_forecast = array_shift($q_current_forecast->result_array());

					$total_forecast = ( $comp_forecast['total'] * (  $comp_forecast['forecast_percent']  /100  ) *  ($pm_forecast['forecast_percent']/100) );


					$pm_sales_value = $set_invoiced_amount[$pm_id] + $wip_pm_total[$pm_id];

					if( $pm_sales_value > 0 ){


				$pm_sales_value = ($pm_sales_value <= 1 ? 1 : $pm_sales_value);
				$total_forecast = ($total_forecast <= 1 ? 1 : $total_forecast);

				
						$status_forecast = round(100/($total_forecast/$pm_sales_value));
					}else{
						$status_forecast = 0;
					}


					echo '<div class="m-bottom-15 clearfix"><div class="pull-left m-right-10"  style="height: 50px; width:50px; border-radius:50px; overflow:hidden; border: 1px solid #999999;"><img class="user_avatar img-responsive img-rounded" src="'.base_url().'/uploads/users/'.$focus_pm_pic[$pm_id].'"" /></div>';
					echo '<div class="" id=""><p><strong>'.$focus_pms[$pm_id].'</strong><span class="pull-right"><span class="label pull-right m-bottom-3 m-top-3 small_orange_fixed"><i class="fa fa-usd"></i> '.number_format($set_invoiced_amount[$pm_id]).'</span> <br /> <span class="label pull-right m-bottom-3 small_green_fixed"><i class="fa fa-exclamation-triangle"></i> '.number_format($wip_pm_total[$pm_id]).'</span></span></p>';
					echo '<p><i class="fa fa-usd"></i> '.number_format($pm_sales_value).'</p>';

					echo '<div class="progress no-m m-top-3 clearfix tooltip-enabled" title="" data-original-title="'.$status_forecast.'% - $'.number_format($pm_sales_value).' / $'.number_format($total_forecast).'   " style="height: 7px;">
					<div class="progress-bar progress-bar-danger" style="width:'.$status_forecast.'%; background:red;"></div></div></div></div>';

					echo "<div class='clearfix'></div>";
					$forecast_focus_total = $forecast_focus_total + $total_forecast;


					if($prime_pm == $pm_id){
						$return_total = $status_forecast;
					}
				//}
			}
		}

		echo "</div>";
 
		$fetch_user = $this->user_model->fetch_user($prime_pm);
		$user_details = array_shift($fetch_user->result_array());


		$q_current_forecast_comp = $this->dashboard_m->get_current_forecast($c_year,$user_details['user_focus_company_id'],'1');
		$comp_forecast = array_shift($q_current_forecast_comp->result_array());

		$q_current_forecast = $this->dashboard_m->get_current_forecast($c_year,$prime_pm);
		$pm_forecast = array_shift($q_current_forecast->result_array());

		$total_forecast = ( $comp_forecast['total'] * (  $comp_forecast['forecast_percent']  /100  ) *  ($pm_forecast['forecast_percent']/100) );

		$pm_sales_value = $set_invoiced_amount[$prime_pm] + $wip_pm_total[$prime_pm];


		$pm_sales_value = ($pm_sales_value <= 1 ? 1 : $pm_sales_value);
		$total_forecast = ($total_forecast <= 1 ? 1 : $total_forecast);

		if( $pm_sales_value > 0 ){
			$status_forecast = round(100/($total_forecast/$pm_sales_value));
		}else{
			$status_forecast = 0;
		}

		

		//$status_forecast = round(100/($forecast_focus_total/ ($total_wip + $total_invoiced) ));

		echo '<div class="clearfix" style="padding-top: 6px;    border-top: 1px solid #eee;"><i class="fa fa-briefcase" style="font-size: 42px;float: left;margin-left: 7px;margin-right: 10px;"></i>';
		echo '<div class="" id=""><p><strong>Primary Overall</strong><span class="pull-right"><span class="label pull-right m-bottom-3 m-top-3 small_orange_fixed"><i class="fa fa-usd"></i> '.number_format($set_invoiced_amount[$prime_pm]).'</span> <br /> <span class="label pull-right m-bottom-3 small_green_fixed"><i class="fa fa-exclamation-triangle"></i> '.number_format( $wip_pm_total[$prime_pm]).'</span></span></p>';
		echo '<p><i class="fa fa-usd"></i> '.number_format( $pm_sales_value ).' <strong class="pull-right m-right-10"></strong></p> </p>';

		echo '<div class="progress no-m m-top-3 clearfix tooltip-enabled" title="" data-original-title="'.$status_forecast.'% - $'.number_format( $pm_sales_value ).' / $'.number_format($total_forecast).'   " style="height: 7px;">';
		echo '<div class="progress-bar progress-bar-danger" style="width:'.$status_forecast.'%; background:red;"></div></div></div></div>';
		echo "<div class='clearfix'></div>";
		return $return_total;

	}



	public function invoiced_mn(){
		$focus_company_maintenence_q = $this->dashboard_m->get_focus_companies_mntnc();
		$focus_company_maintenence = $focus_company_maintenence_q->result();

		$maintenacne_data = array(); 
		foreach ($focus_company_maintenence as $maintenance_data){
			$maintenacne_data[$maintenance_data->focus_company_id] = 0;
		}

		$c_year = date("Y");
		$n_year = $c_year+1;
		$date_a = "01/01/$c_year";
		$date_c = date("d/m/Y");

		$date_b = "01/01/".$c_year-1;

		$last_year = $c_year-1;
		$date_d = date('d/m/').$last_year;


		$grand_total_sales = 0;
		$sales_total = 0;
		$total_string = ''; 
		$last_year = 0;

		foreach ($focus_company_maintenence as $maintenance_data){

			$q_dash_sales = $this->dashboard_m->dash_sales($date_a,$date_c,$maintenance_data->focus_company_id,1);
			if($q_dash_sales->num_rows >= 1){

				$sales_total = 0;
				$dash_sales = $q_dash_sales->result();

				foreach ($dash_sales as $sales){

					if( $maintenance_data->project_manager_id == $sales->project_manager_id){
						if($sales->label == 'VR'){
							$sales_total = $sales->variation_total;
						}else{
							$sales_total = $sales->project_total*($sales->progress_percent/100);
						}

						$maintenacne_data[$maintenance_data->focus_company_id] = $maintenacne_data[$maintenance_data->focus_company_id] + $sales_total;
						$grand_total_sales = $grand_total_sales + $sales_total;
					}
				}
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$maintenance_data->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($maintenacne_data[$maintenance_data->focus_company_id],2).'</span></div>';
			}



		}
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; Last Year</div>';

		foreach ($focus_company_maintenence as $maintenance_data){
			$q_dash_sales = $this->dashboard_m->dash_sales($date_b,$date_d,$maintenance_data->focus_company_id,1);
			if($q_dash_sales->num_rows >= 1){

				$sales_total = 0;
				$dash_sales = $q_dash_sales->result();
				$last_year = 0;

				foreach ($dash_sales as $sales){

					if( $maintenance_data->project_manager_id == $sales->project_manager_id){
						if($sales->label == 'VR'){
							$sales_total = $sales->variation_total;
						}else{
							$sales_total = $sales->project_total*($sales->progress_percent/100);
						}

						$last_year = $last_year + $sales_total;
					}
				}
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$maintenance_data->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($last_year,2).'</span></div>';
			}
		}
		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($grand_total_sales,2).'</strong></p>';
	}



	public function uninvoiced_widget_mn(){
		$focus_company_maintenence_q = $this->dashboard_m->get_focus_companies_mntnc();
		$focus_company_maintenence = $focus_company_maintenence_q->result();

		$maintenacne_data = array(); 
		foreach ($focus_company_maintenence as $maintenance_data){
			$maintenacne_data[$maintenance_data->focus_company_id] = 0;
		}

		$c_year = date("Y");
		$n_year = $c_year+1;
		$date_a = "01/01/$c_year";
		$date_b = date("d/m/Y");
  
		$total_string = '';
		$personal_data = 0;


		$date_c = "01/01/".$c_year-1;

		$last_year = $c_year-1;
		$date_d = date('d/m/').$last_year;

		foreach ($focus_company_maintenence as $company){ 

				$q_dash_unvoiced = $this->dashboard_m->dash_unvoiced_per_date($date_a,$date_b,$company->focus_company_id);
				$dash_unvoiced = $q_dash_unvoiced->result();

				$unvoiced_total = 0;
				$unvoiced_grand_total = 0;

				foreach ($dash_unvoiced as $unvoiced) {

					if( $company->project_manager_id == $unvoiced->project_manager_id){

						if($unvoiced->label == 'VR'){
							$unvoiced_total = $unvoiced->variation_total;
						}else{
							$unvoiced_total = $unvoiced->project_total*($unvoiced->progress_percent/100);
						}

						$maintenacne_data[$company->focus_company_id] = $maintenacne_data[$company->focus_company_id] + $unvoiced_total;
						$personal_data = $personal_data + $unvoiced_total;
					}
				}

			$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($maintenacne_data[$company->focus_company_id],2).'</span></div>';
		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; Last Year</div>';



		foreach ($focus_company_maintenence as $company){
			$q_dash_sales = $this->dashboard_m->dash_unvoiced_per_date($date_c,$date_d,$company->focus_company_id,1);
		//	if($q_dash_sales->num_rows >= 1){

				$sales_total = 0;
				$dash_sales = $q_dash_sales->result();
				$last_year = 0;

				foreach ($dash_sales as $sales){

					if( $company->project_manager_id == $sales->project_manager_id){
						if($sales->label == 'VR'){
							$sales_total = $sales->variation_total;
						}else{
							$sales_total = $sales->project_total*($sales->progress_percent/100);
						}

						$last_year = $last_year + $sales_total;
					}
				}
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($last_year,2).'</span></div>';
		//	}
		}


		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';
	}




	public function pm_sales_widget_mn(){
		$grand_total_sales_cmp = 0;
		$grand_total_uninv_cmp = 0;
		$grand_total_over_cmp = 0;

		$c_year = date("Y");		
		$date_a = "01/01/$c_year";
		$date_b = date("d/m/Y");
		$n_year =  date("Y")+1;
		$set_new_date = '01/01/'.$n_year;
		$date_c = date("d/m/Y");


		$pm_data = $this->dashboard_m->fetch_project_pm_nomore();
		$pm_q = array_shift($pm_data->result_array());
		$not_pm_arr = explode(',',$pm_q['user_id'] );


		$pm_set_data = array();

		$wip_pm_total = array();

		$overall_total_sales = 0;
		$sales_result = array();
		$focus_pms = array();
		$focus_pm_pic = array();
		$focus_pm_comp = array();

		$set_invoiced_amount = array();

		$total_invoiced_init = 0;
		$total_string = '';

		$return_total = 0;

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();

		foreach ($project_manager_list as $pm ) {
			$set_invoiced_amount[$pm->user_id] = 0;
			$sales_result[$pm->user_id] = 0;
			$focus_pms[$pm->user_id] = $pm->user_first_name;
			$focus_pm_pic[$pm->user_id] = $pm->user_profile_photo;
			$focus_pm_comp[$pm->user_id] = $pm->user_focus_company_id;

			$wip_pm_total[$pm->user_id] = 0;
			$sales_result[$pm->user_id] = 0;

		//	if( in_array($pm->user_focus_company_id, $direct_company) ){
				$pm_set_data[$pm->user_id] = $pm->user_id;
		//	}
		}

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		foreach ($focus_company as $company){

		//	if( in_array($company->company_id, $direct_company) ){
				$q_dash_sales = $this->dashboard_m->dash_sales($date_a,$date_c,$company->company_id,1);

				if($q_dash_sales->num_rows >= 1){

					$grand_total_sales = 0;
					$sales_total = 0;

					$dash_sales = $q_dash_sales->result();

					foreach ($dash_sales as $sales){
						if( !in_array($sales->project_manager_id, $not_pm_arr) ){

							if($sales->label == 'VR'){
								$sales_total = $sales->variation_total;
							}else{
								$sales_total = $sales->project_total*($sales->progress_percent/100);
							}

							$set_invoiced_amount[$sales->project_manager_id] = $set_invoiced_amount[$sales->project_manager_id] + $sales_total;
							$pm_set_data[$sales->project_manager_id] = $sales->project_manager_id;

							$grand_total_sales_cmp = $grand_total_sales_cmp + $sales_total;

						}
					}					
				}
		//	}
		}

		$forecast_focus_total = 0; 
		foreach ($project_manager_list as $pm ) {
			$total_sales = 0;
			$total_outstanding = 0;

			$q_pm_sales = $this->dashboard_m->dash_total_pm_sales($pm->user_id,$c_year,'',$date_a,$date_b,$pm->user_focus_company_id);
			$pm_sales = $q_pm_sales->result_array();

			foreach ($pm_sales as $sales => $value){

				if($value['label'] == 'VR'){
					$project_total_percent = $value['variation_total'];
				}else{
					$project_total_percent = $value['project_total'] * ($value['progress_percent']/100);
				}
			}

		}


		$direct_company = array('5','6');
 

		foreach ($direct_company as $key => $comp_id) {

		//	foreach ($pm_set_data as $pm_id => $value){
//ion get_wip_perso
				$wip_amount = $this->get_wip_personal($date_a,$set_new_date,29,$comp_id);
				$wip_pm_total[29] = $wip_pm_total[29] + $wip_amount;
				$sales_result[29] = $sales_result[29] + $wip_amount + $set_invoiced_amount[29];

			//	echo $comp_id.'---'.$pm_id.'---'.$wip_amount.'<br />';
		//	}

		}


		arsort($sales_result);
		$total_wip = array_sum($wip_pm_total);

		$total_invoiced = array_sum($set_invoiced_amount);
		echo "<div style=\"overflow-y: auto; padding-right: 5px; height: 400px;\">";

		foreach ($sales_result as $pm_id => $sales){

			//$pm_id = '29';

			if( $sales > 0){
				$comp_id_pm = $focus_pm_comp[$pm_id];

				//$pm_wip = (( $set_estimates[$pm_id] + $set_quotes[$pm_id] ) - $set_invoiced[$pm_id] );

				$q_current_forecast_comp = $this->dashboard_m->get_current_forecast($c_year,$comp_id_pm,'1');
				$comp_forecast = array_shift($q_current_forecast_comp->result_array());

				$q_current_forecast = $this->dashboard_m->get_current_forecast($c_year,$pm_id);
				$pm_forecast = array_shift($q_current_forecast->result_array());

				$total_forecast = ( $comp_forecast['total'] * (  $comp_forecast['forecast_percent']  /100  ) *  ($pm_forecast['forecast_percent']/100) );


				$pm_sales_value = $set_invoiced_amount[$pm_id] + $wip_pm_total[$pm_id];

				if( $pm_sales_value > 0 ){
					$status_forecast = round(100/($total_forecast/$pm_sales_value));
				}else{
					$status_forecast = 0;
				}


				echo '<div class="m-bottom-15 clearfix"><div class="pull-left m-right-10"  style="height: 50px; width:50px; border-radius:50px; overflow:hidden; border: 1px solid #999999;"><img class="user_avatar img-responsive img-rounded" src="'.base_url().'/uploads/users/'.$focus_pm_pic[$pm_id].'"" /></div>';
				echo '<div class="" id=""><p><strong>'.$focus_pms[$pm_id].'</strong><span class="pull-right"><span class="label pull-right m-bottom-3 m-top-3 small_orange_fixed"><i class="fa fa-usd"></i> '.number_format($set_invoiced_amount[$pm_id]).'</span> <br /> <span class="label pull-right m-bottom-3 small_green_fixed"><i class="fa fa-exclamation-triangle"></i> '.number_format($wip_pm_total[$pm_id]).'</span></span></p>';
				echo '<p><i class="fa fa-usd"></i> '.number_format($pm_sales_value).'</p>';

				echo '<div class="progress no-m m-top-3 clearfix tooltip-enabled" title="" data-original-title="'.$status_forecast.'% - $'.number_format($pm_sales_value).' / $'.number_format($total_forecast).'   " style="height: 7px;">
				<div class="progress-bar progress-bar-danger" style="width:'.$status_forecast.'%; background:red;"></div></div></div></div>';

				echo "<div class='clearfix'></div>";
				$forecast_focus_total = $forecast_focus_total + $total_forecast;

				if('29' == $pm_id){
					$return_total = $status_forecast;
				}
			}
		}

		echo "</div>";


		$status_forecast = round(100/($forecast_focus_total/ ($total_wip + $total_invoiced) ));
/*
		$comp_code = '';
		if($pm_type == 2 || count($direct_company) === 1){ // for pm only

			if($user_details['user_focus_company_id'] == 5){
				$comp_code = 'WA';
			}

			if($user_details['user_focus_company_id'] == 6){
				$comp_code = 'NSW';
			}
		}


*/

		/*
		echo '<div class="clearfix" style="padding-top: 6px;    border-top: 1px solid #eee;"><i class="fa fa-briefcase" style="font-size: 42px;float: left;margin-left: 7px;margin-right: 10px;"></i>';
		echo '<div class="" id=""><p><strong>Overall Focus </strong><span class="pull-right"><span class="label pull-right m-bottom-3 m-top-3 small_orange_fixed"><i class="fa fa-usd"></i> '.number_format($grand_total_sales_cmp).'</span> <br /> <span class="label pull-right m-bottom-3 small_green_fixed"><i class="fa fa-exclamation-triangle"></i> '.number_format($total_wip).'</span></span></p>';
		echo '<p><i class="fa fa-usd"></i> '.number_format( ($total_wip + $total_invoiced) ).' <strong class="pull-right m-right-10"></strong></p> </p>';

		echo '<div class="progress no-m m-top-3 clearfix tooltip-enabled" title="" data-original-title="'.$status_forecast.'% - $'.number_format( ($total_wip + $total_invoiced) ).' / $'.number_format($forecast_focus_total).'   " style="height: 7px;">
		<div class="progress-bar progress-bar-danger" style="width:'.$status_forecast.'%; background:red;"></div></div></div></div>';
		echo "<div class='clearfix'></div>";



*/
		return $return_total;

	}





	public function average_date_invoice_mn(){
		$pm_type = $this->pm_type();
		$user_id = '29';
		$fetch_user = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($fetch_user->result_array());
/*
		if($pm_type == 1){ // for director/pm
			$direct_company = explode(',',$user_details['direct_company'] );
		}

		if($pm_type == 2){ // for pm only
			$direct_company = explode(',',$user_details['user_focus_company_id'] );
		}
*/
		$c_year = date("Y");		
		$date_a = "01/01/$c_year";
		$date_b = date("d/m/Y");
		$days_dif = array();

		$long_day = 0;
		$size = 0;
		$short_day_day = 0;




		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();

		$pms_averg = array();
		$pms_w_avg = array();
		foreach ($project_manager_list as $pm ) {
			$pms_averg[$pm->user_id] = array();
			$pms_w_avg[$pm->user_id] = $pm->user_id;
		}

		
		$direct_company = array('5','6');

 		foreach ($direct_company as $key => $comp_id) {
			$q_ave = $this->dashboard_m->get_maitenance_dates_pm($date_a,$date_b,$user_id,$comp_id); //4
			$days_result = $q_ave->result();

			foreach ($days_result as $result){
						$diff = ($result->days_diff < 0 ? 0 : $result->days_diff);
				array_push($days_dif, $diff);
			}

			if($pm_type == 1){ // for director/pm
				foreach ($project_manager_list as $pm ) {
					$q_ave_pm = $this->dashboard_m->get_maitenance_dates_pm($date_a,$date_b,$pm->user_id,$comp_id); //4
					$days_result_pm = $q_ave_pm->result();

					foreach ($days_result_pm as $result_pm){
						$diff = ($result_pm->days_diff < 0 ? 0 : $result_pm->days_diff);
						array_push($pms_averg[$pm->user_id],$diff);
					}
				}
			}
 		}



 

//		var_dump($days_dif);
 
		$size = count($days_dif);

		if($size > 0){
			$average = array_sum($days_dif) / $size;
			arsort($days_dif,1);
			$long_day =  max($days_dif);
			$short_day_day =  min($days_dif);
			$short_day_day = 1;
		}else{
			$average = 0;
			$long_day = 0;
			$short_day_day = 0;
		}

		$total_string = '';

 

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();


		foreach ($focus_company as $company){

			if( in_array($company->company_id, $direct_company) ){

				$days_dif_comp = array('');

				$q_ave = $this->dashboard_m->get_maitenance_dates_pm($date_a,$date_b,$company->company_id); //5
				$days_result = $q_ave->result();

				foreach ($days_result as $result){
					if($result->project_manager_id != 9){
						$diff = ($result->days_diff < 0 ? 0 : $result->days_diff);
						array_push($days_dif_comp, $diff);
					}
				}

				$size_comp = count($days_dif_comp);
				$average_comp = array_sum($days_dif_comp) / $size_comp;

				arsort($days_dif_comp,1);
 
				$long_day_comp =  max($days_dif_comp);
				$short_day_day_comp =  min($days_dif_comp);
				$short_day_day_comp = 1;
 
				// $total_string .= '<span>'.str_replace("Pty Ltd","",$company->company_name).' &nbsp; '.number_format($average_comp,1).'</span><br />';


				$total_string .= '<div class=\'row\'><span class=\'col-xs-8\'>'.str_replace("Pty Ltd","",$company->company_name).'</span><span class=\'col-xs-4\'>'.round($average_comp,1).'</span></div>';

			}
		}

// 	var_dump($pms_averg);
 

/*
		if($pm_type == 1 &&  in_array($company->company_id, $direct_company)     ){


		}


*/

			if($pm_type == 1){ // for director/pm

				$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';



				foreach ($project_manager_list as $pm ){

					if( count($pms_averg[$pm->user_id]) > 0  ){
						//	var_dump($pms_averg[$pm->user_id]);
						//	echo "<p></p>"; 
						$size = count($pms_averg[$pm->user_id]);

						//if($size > 0){
						$pm_average = array_sum($pms_averg[$pm->user_id]) / $size;
						arsort($pms_averg[$pm->user_id],1);
						$pm_long_day =  max($pms_averg[$pm->user_id]);
						$pm_short_day_day =  min($pms_averg[$pm->user_id]);
						$pm_short_day_day = 1;
						/*}else{
							$pm_average = 0;
							$pm_long_day = 0;
							$pm_short_day_day = 0;
						}*/

						$pm_name = $pm->user_first_name;
						$total_string .= '<div class=\'row\'><span class=\'col-xs-8\'>'.$pm_name.'</span><span class=\'col-xs-4\'>'.round($pm_average,1).'</span></div>';
					}
				}
			} 


		echo '<div id="" class="tooltip-enabled" title="" data-placement="bottom" data-html="true" data-original-title="'.$total_string .'">
				<input class="knob" data-width="100%" data-step=".1"  data-thickness=".13" value="'.number_format($average,1).'" readonly data-fgColor="#964dd7" data-angleOffset="-180"  data-max="'.$long_day.'">
				<div id="" class="clearfix m-top-10">
					<div id="" class="col-xs-6"><strong><p>'.$short_day_day.' min</p></strong></div>
					<div id="" class="col-xs-6 text-right"><strong><p>max '.$long_day.'</p></strong></div>
				</div>
			</div>';

	}






	public function outstanding_payments_widget_mn(){
		$focus_company_maintenence_q = $this->dashboard_m->get_focus_companies_mntnc();
		$focus_company_maintenence = $focus_company_maintenence_q->result();

		$maintenacne_data = array(); 
		foreach ($focus_company_maintenence as $maintenance_data){
			$maintenacne_data[$maintenance_data->focus_company_id] = 0;
		}

		$c_year = date("Y");
		$n_year = $c_year+1;
		$date_a = "01/01/$c_year";
		$date_b = date("d/m/Y");
  
		$total_string = '';
		$personal_data = 0;

		$display_each_value = 0;


		$date_c = "01/01/".$c_year-1;

		$last_year = $c_year-1;
		$date_d = date('d/m/').$last_year;

		foreach ($focus_company_maintenence as $company){

				$invoice_amount = 0;
				$total_paid = 0;


				$q_dash_oustanding_payments = $this->dashboard_m->dash_oustanding_payments($date_a,$date_b,$company->focus_company_id);
				$oustanding_payments = $q_dash_oustanding_payments->result();

				foreach ($oustanding_payments as $oustanding) {

					if( $company->project_manager_id == $oustanding->project_manager_id){
						if($oustanding->label == 'VR'){
							$invoice_amount = $oustanding->variation_total;
						}else{
							$invoice_amount = $oustanding->project_total*($oustanding->progress_percent/100);
						}

						$total_paid =  $oustanding->amount_exgst;
						$display_each_value = $invoice_amount - $total_paid;


						$maintenacne_data[$company->focus_company_id] = $maintenacne_data[$company->focus_company_id] + $display_each_value;
						$personal_data = $personal_data + $display_each_value;
					}
				}
 
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($maintenacne_data[$company->focus_company_id],2).'</span></div>';
			}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; Last Year</div>';





		foreach ($focus_company_maintenence as $company){

				$invoice_amount = 0;
				$total_paid = 0;


				$q_dash_oustanding_payments = $this->dashboard_m->dash_oustanding_payments($date_c,$date_d,$company->focus_company_id);
				$oustanding_payments = $q_dash_oustanding_payments->result();
				$last_year = 0;

				foreach ($oustanding_payments as $oustanding) {

					if( $company->project_manager_id == $oustanding->project_manager_id){
						if($oustanding->label == 'VR'){
							$invoice_amount = $oustanding->variation_total;
						}else{
							$invoice_amount = $oustanding->project_total*($oustanding->progress_percent/100);
						}

						$total_paid =  $oustanding->amount_exgst;
						$display_each_value = $invoice_amount - $total_paid;


					//	$maintenacne_data[$company->focus_company_id] = $maintenacne_data[$company->focus_company_id] + $display_each_value;


						$last_year = $last_year + $display_each_value;


						// $personal_data = $personal_data + $display_each_value;
					}
				}
 
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($last_year,2).'</span></div>';
			}




		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';
	}



/*


used for GP loading

	public function test(){


$query1 = $this->db->query("SELECT  `project`.`project_id` ,  `project`.`project_name` ,  `project`.`job_date` ,  `project`.`date_site_finish` ,  `users`.`user_first_name` ,  `project`.`project_total`, `project_cost_total`.`variation_total` , UNIX_TIMESTAMP( STR_TO_DATE(  `project`.`date_site_finish` , '%d/%m/%Y' ) ) AS  `date_sort` 
FROM  `project` 

LEFT JOIN  `users` ON  `users`.`user_id` =  `project`.`project_manager_id` 
LEFT JOIN  `project_cost_total` ON `project_cost_total`.`project_id` =  `project`.`project_id`

WHERE  `project`.`is_active` =  '1'
ORDER BY  `date_sort` ASC   ");


$q1_result = $query1->result();


				foreach ($q1_result as $projects) {

					$gp_data = $this->projects->fetch_project_totals($projects->project_id);

					echo $projects->project_id.'-'.$gp_data['gp'].'<br />';



				}



	}
*/



















	public function focus_get_po_widget_mn(){
		$focus_company_maintenence_q = $this->dashboard_m->get_focus_companies_mntnc();
		$focus_company_maintenence = $focus_company_maintenence_q->result();

		$maintenacne_data = array(); 
		foreach ($focus_company_maintenence as $maintenance_data){
			$maintenacne_data[$maintenance_data->focus_company_id] = 0;
			$pm_id = $maintenance_data->project_manager_id;
		}


		$year = date("Y");
		$current_date = '01/01/2019';
		$current_start_year = '01/01/2000';
		$set_cpo = array();

		$focus_arr = array();
		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		foreach ($focus_company as $company){
			$focus_arr[$company->company_id] = $company->company_name;
			$set_cpo[$company->company_id] = 0;
		}

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

			if($pm_id == $row['project_manager_id']){
				$personal_data = $personal_data + $out_standing;
			}

		}

		$total_string = '';

		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title=""><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';
	}







	public function pm_estimates_widget_mn(){
		$focus_company_maintenence_q = $this->dashboard_m->get_focus_companies_mntnc();
		$focus_company_maintenence = $focus_company_maintenence_q->result();

		$maintenacne_data = array(); 
		foreach ($focus_company_maintenence as $maintenance_data){
			$maintenacne_data[$maintenance_data->focus_company_id] = 0;
			$pm_id = $maintenance_data->project_manager_id;
		}


		$year = date("Y");
		$current_date = date("d/m/Y");
		$current_start_year = '01/01/'.$year;
		$total_string = '';
		$is_restricted = 0;



	

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
			if( $un_accepted->project_manager_id == $pm_id ){

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
				//	if( in_array($un_accepted->focus_company_id, $direct_company) ){
						if($un_accepted->install_time_hrs > 0 || $un_accepted->work_estimated_total > 0.00 || $un_accepted->variation_total > 0.00 ){
							$amnt =  $un_accepted->project_total + $un_accepted->variation_total;
							$project_cost[$un_accepted->focus_company_id] =  $project_cost[$un_accepted->focus_company_id] + $amnt;
						}else{
							$amnt = $un_accepted->budget_estimate_total;
							$project_cost[$un_accepted->focus_company_id] =  $project_cost[$un_accepted->focus_company_id] + $amnt; 
						}

						if( isset($unaccepted_amount[$un_accepted->project_estiamator_id])) {
							if($pm_id == $un_accepted->project_manager_id){
								$unaccepted_amount[$un_accepted->project_estiamator_id] = $unaccepted_amount[$un_accepted->project_estiamator_id] + $amnt;
							}
						}
				//	}


					if($pm_id == $un_accepted->project_manager_id){
						$personal_data = $personal_data + $amnt;
					}




					/*


					if( isset($pm_split[$un_accepted->project_manager_id])) {
						$pm_split[$un_accepted->project_manager_id] = $pm_split[$un_accepted->project_manager_id] + $amnt;
					}


					*/

				}
			}
		}

		//var_dump($project_cost);

		foreach ($focus_arr as $comp_id => $value ){
			$display_total_cmp = $project_cost[$comp_id];
			if($display_total_cmp > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cmp,2).'</span></div>';
			}
		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';





		foreach ($estimator_list as $est ) {
			$display_total_cmp = $unaccepted_amount[$est->project_estiamator_id];
			$pm_name = 'Maintenance';
			if($display_total_cmp > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name.'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cmp,2).'</span></div>';
			}
		}

		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';
	}


	public function focus_top_ten_con_sup_mn($type){
		$focus_company_maintenence_q = $this->dashboard_m->get_focus_companies_mntnc();
		$focus_company_maintenence = $focus_company_maintenence_q->result();

		$maintenacne_data = array(); 
		foreach ($focus_company_maintenence as $maintenance_data){
			$maintenacne_data[$maintenance_data->focus_company_id] = 0;
			$pm_id = $maintenance_data->project_manager_id;
		}

		$comp_q = " AND `project`.`job_category` = 'Maintenance' ";

		$current_date = date("d/m/Y");
		$year = date("Y");

		$last_year = intval(date("Y")) - 1;
		$base_year = '01/01/'.$year;

		$next_year_date = '01/01/'.$last_year;
		$current_start_year = date("d/m/Y");
		
		$last_start_year = '01/01/'.$last_year;
		$last_year_current_date = date("d/m/").$last_year;

		$q_companies = $this->dashboard_m->get_company_sales($type,$base_year,$current_start_year,'',$comp_q);
		$company_details  = $q_companies->result();
		$counter = 0;

		$list_total = 0;

		foreach ($company_details as $company) {
			$list_total = $list_total + round($company->total_price);
		}

		foreach ($company_details as $company) {
			$counter ++;
			$total = $company->total_price;
			$percent = round(100/($list_total/$company->total_price),1);

			$q_clients_overall = $this->dashboard_m->get_company_sales_overall($company->company_id);
			$overall_cost = array_shift($q_clients_overall->result_array());
			$grand_total = $overall_cost['total_price'];

			echo '<div class="col-sm-8 col-md-8"><i class="fa fa-chevron-circle-right"></i>  &nbsp; ';

			$comp_name = $company->company_name;
			if(strlen($comp_name) > 40){
				echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$comp_name.'">'.substr($comp_name,0,40).'...</span>';
			}else{
				echo $comp_name;
			}

			$cmp_id = $company->company_id;

			$last_year_q = $this->dashboard_m->get_company_sales('',$last_start_year,$base_year,$cmp_id,$comp_q);
			$last_year_sale = array_shift($last_year_q->result_array());
			$lst_year_total = $last_year_sale['total_price'];

			echo ' </div><div class="col-md-1 col-sm-4"><strong>'.number_format($percent,1).'%</strong></div>  <div class="col-md-3 col-sm-4 tooltip-test" title="" data-placement="left" data-original-title="Last Year : $ '.number_format($lst_year_total).'"><i class="fa fa-usd"></i> '.number_format($company->total_price).'</div><div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';
		}
	}




	public function focus_top_ten_clients_mn(){
		$focus_company_maintenence_q = $this->dashboard_m->get_focus_companies_mntnc();
		$focus_company_maintenence = $focus_company_maintenence_q->result();

		$maintenacne_data = array(); 
		foreach ($focus_company_maintenence as $maintenance_data){
			$maintenacne_data[$maintenance_data->focus_company_id] = 0;
			$pm_id = $maintenance_data->project_manager_id;
		}

		$comp_q = " AND `project`.`job_category` = 'Maintenance' ";

		$current_date = date("d/m/Y");
		$year = date("Y");
		$next_year_date = '01/01/'.($year+1);
		$current_start_year = '01/01/'.$year;
		$last_start_year = '01/01/'.($year-1);

		$q_clients = $this->dashboard_m->get_top_ten_clients($current_start_year, $current_date,'','',$comp_q);
		$client_details  = $q_clients->result();
		
		$list_total = 0;
		foreach ($client_details as $company) {
			$q_vr_c_t = $this->dashboard_m->client_vr_value($current_start_year,$current_date,$company->client_id,$comp_q);
			$vr_val_t = array_shift($q_vr_c_t->result_array());
			$list_total = $list_total + round($company->grand_total + $vr_val_t['total_variation']);
		}

		$last_year = intval(date("Y")) - 1;
		$this_month = date("m");
		$this_day = date("d");




		$comp_total = array();
		$comp_name = array();

		foreach ($client_details as $company) {
			$q_vr_c = $this->dashboard_m->client_vr_value($current_start_year,$current_date,$company->client_id,$comp_q);
			$vr_val = array_shift($q_vr_c->result_array());
			$cost_gtotl_amnt = round($company->grand_total+ $vr_val['total_variation']);
			$comp_total[$company->company_id] = $cost_gtotl_amnt;
			$comp_name[$company->company_id] = $company->company_name;

		}

		arsort($comp_total);

		$date_a_last = "01/01/$last_year";
		$date_b_last = "$this_day/$this_month/$last_year";

		foreach ($comp_total as $raw_id => $compute_amount) {
			$percent = round(100/($list_total/$compute_amount),1);
			echo '<div class="col-sm-8 col-md-8"><i class="fa fa-chevron-circle-right"></i>  &nbsp; ';

			$q_vr_c = $this->dashboard_m->client_vr_value($current_start_year,$current_date,$raw_id,$comp_q);
			$vr_val = array_shift($q_vr_c->result_array());

			$company_name = $comp_name[$raw_id];
			if(strlen($company_name) > 40){
				echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$company_name.'">'.substr($company_name,0,40).'...</span>';
			}else{
				echo $company_name;
			}

			$q_vr_c_u = $this->dashboard_m->client_vr_value($date_a_last,$current_start_year,$raw_id,$comp_q);
			$vr_val_u = array_shift($q_vr_c_u->result_array());

			$last_year_q = $this->dashboard_m->get_top_ten_clients($date_a_last, $current_start_year,$raw_id);
			$last_year_sale = array_shift($last_year_q->result_array());
			$lst_year_total = $last_year_sale['grand_total'] + $vr_val_u['total_variation'];
			echo ' </div><div class="col-md-1 col-sm-4"><strong>'.number_format($percent,1).'%</strong></div>  <div class="col-md-3 col-sm-4 tooltip-test" title="" data-placement="left" data-original-title="Last Year : $ '.number_format($lst_year_total).'"><i class="fa fa-usd"></i> '.number_format($compute_amount).'</div><div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';
		}
	}





	public function focus_projects_count_widget_mn(){

		$focus_company_maintenence_q = $this->dashboard_m->get_focus_companies_mntnc();
		$focus_company_maintenence = $focus_company_maintenence_q->result();

		$maintenacne_data = array(); 
		foreach ($focus_company_maintenence as $maintenance_data){
			$maintenacne_data[$maintenance_data->focus_company_id] = 0;
			$pm_id = $maintenance_data->project_manager_id;
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

		$focus_arr = array();

		$focus_invoiced = array();
		$focus_invoiced_old = array();

		$focus_comp_wip_count = array();

		foreach ($focus_company as $company){
			$focus_invoiced[$company->company_id] = 0;
			$focus_invoiced_old[$company->company_id] = 0;
			$focus_comp_wip_count[$company->company_id] = 0;
			$focus_arr[$company->company_id] = $company->company_name;
		}


	 
		$total_string_wip = '';
		$total_string_inv = '';

		$total_string_wip .= 'WIP Count'; 
		$total_string_inv .= 'Invoiced Count';


		$q_maint = $this->dashboard_m->get_maintenance_wip($current_start_year, $current_date);
		$q_data_maint = $q_maint->result();

		$personal_data_a = 0;
		foreach ($q_data_maint as $maintenance_data){
			if($this->invoice->if_invoiced_all($maintenance_data->project_id)  && $this->invoice->if_has_invoice($maintenance_data->project_id) > 0 ){
				$personal_data_a++;	
				$focus_invoiced[$maintenance_data->focus_company_id]++;					
			}
		}


		$q_maps = $this->dashboard_m->get_map_projects($current_start_year,$current_date,$pm_id);
		$map_details = $q_maps->result();
		foreach ($map_details as $map) {
			$personal_data_b++;
			$focus_comp_wip_count[$map->focus_company_id]++;
		}

		foreach ($focus_company_maintenence as $maintenance_data){
			$total_string_wip .= '<div class=\'row\'><span class=\'col-xs-7\'>'.str_replace("Pty Ltd","",$focus_arr[$maintenance_data->focus_company_id]).'</span> <span class=\'col-xs-5\'>'.number_format($focus_comp_wip_count[$maintenance_data->focus_company_id]).'</span></div>';
			$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-7\'>'.str_replace("Pty Ltd","",$focus_arr[$maintenance_data->focus_company_id]).'</span> <span class=\'col-xs-5\'>'.number_format($focus_invoiced[$maintenance_data->focus_company_id]).'</span></div>';
		}


		$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; Last Year</div>';

	 
		$q_maint_old = $this->dashboard_m->get_maintenance_wip($last_start_year, $current_start_year);
		$q_data_maint_old = $q_maint_old->result();

		foreach ($q_data_maint_old as $maintenance_data){
			if($this->invoice->if_invoiced_all($maintenance_data->project_id)  && $this->invoice->if_has_invoice($maintenance_data->project_id) > 0 ){
				$focus_invoiced_old[$maintenance_data->focus_company_id]++;					
			}
		}

		foreach ($focus_company_maintenence as $maintenance_data){
			$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-7\'>'.str_replace("Pty Ltd","",$focus_arr[$maintenance_data->focus_company_id]).'</span> <span class=\'col-xs-5\'>'.number_format($focus_invoiced_old[$maintenance_data->focus_company_id]).'</span></div>';
		}






		echo '<div id="" class="clearfix row">				
		<strong class="text-center col-xs-4"><p class="h5x value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string_inv.'"><i class="fa fa-list-alt"></i> &nbsp;'.$personal_data_a.'</p></strong>
		<strong class="text-center col-xs-4"><p class="h5x value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string_wip.'"><i class="fa fa-tasks"></i> &nbsp;'.$personal_data_b.'</p></strong>
		<strong class="text-center col-xs-4"></strong></div>';

	}




	public function focus_projects_by_type_widget_mn(){

		$focus_company_maintenence_q = $this->dashboard_m->get_focus_companies_mntnc();
		$focus_company_maintenence = $focus_company_maintenence_q->result();

		$maintenacne_data = array(); 
		foreach ($focus_company_maintenence as $maintenance_data){
			$maintenacne_data[$maintenance_data->focus_company_id] = 0;
			$pm_id = $maintenance_data->project_manager_id;
		}



		$current_date = date("d/m/Y");
		$year = date("Y");
		$current_start_year = '01/01/'.$year;


		$comp_id = 0;

		$focus_arr = array();
		$focus_prjs = array();
		$focus_costs = array();


		$focus_catgy = array();
		$focus_catgy_name = array();
		$focus_catgy_costs = array();

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();
		foreach ($focus_company as $company){
			$focus_arr[$company->company_id] = $company->company_name;
			$focus_prjs[$company->company_id] = 0;
			$focus_costs[$company->company_id] = 0;
		}

		$q_work = $this->dashboard_m->get_work_types();
		foreach ($q_work->result_array() as $job_category) {
			$cat_id =  strtolower(str_replace(" ","_",$job_category['job_category']));
			$focus_catgy[$cat_id] = 0;
			$focus_catgy_costs[$cat_id] = 0;
			$focus_catgy_name[$cat_id] = $job_category['job_category'];
		}

		$cost = 0;
		$variation = 0; 
		$grand_prj_total = 0;

		$q_projects = $this->dashboard_m->get_projects_by_work_type($current_start_year, $current_date);
		foreach ($q_projects->result_array() as $project){

		//	if( in_array($project['focus_company_id'], $direct_company) ){
				$cost = $cost + $project['project_total'];
				$variation = $variation + $project['variation_total'];
				$comp_id = $project['focus_company_id'];

				$focus_prjs[$comp_id]++;
				$cat_id =  strtolower(str_replace(" ","_",$project['job_category']));
				$focus_catgy[$cat_id]++;

				$focus_catgy_costs[$cat_id] = $focus_catgy_costs[$cat_id] + $project['project_total'] + $project['variation_total'];
				$focus_costs[$comp_id] = $focus_costs[$comp_id] + $project['project_total'] + $project['variation_total'];
				$grand_prj_total = $grand_prj_total +  $project['project_total'];
		//	}
		}

		$total_count_cat = array_sum($focus_catgy);

		foreach ($focus_catgy_name as $cat_id => $value){
			if($value == 'Maintenance'){
			$cost = $focus_catgy_costs[$cat_id];
			$count = $focus_catgy[$cat_id];



			$grand_prj_total = ($grand_prj_total <= 1 ? 1 : $grand_prj_total);
			$cost = ($cost <= 1 ? 1 : $cost);

			if($cost>0){
				$percent = round(100/($grand_prj_total/$cost));
			}else{
				$percent = round(100/($grand_prj_total/1));
			}


			$grand_prj_total = ($grand_prj_total == 1 ? 0 : $grand_prj_total);
			$cost = ($cost == 1 ? 0 : $cost);

			if($grand_prj_total == 0 && $cost == 0){
				$percent = 0;
			}

			echo '<div id="" class="clearfix"><p><strong class="col-sm-2">'.$count.'</strong> <strong class="col-sm-4">'.$value.'</strong><strong class="col-sm-2">'.$percent.'% </strong><strong class="col-sm-4">$ '.number_format(round($cost)).'</strong></p></div>';
			echo '<div class="col-md-12"><hr class="block m-bottom-5 m-top-5"></div>';
		}
		}
	}








	public function emp_get_locations_points(){

		$get_locations_points_q = $this->dashboard_m->get_locations_points();
		$focus_lcations = $get_locations_points_q->result();



//		var_dump($location_name);

		$count = 0;

		echo "[";
		foreach ($focus_lcations as $map){

				$get_employee_location_q = $this->dashboard_m->get_employee_location($map->location_address_id);
				$employee_location = $get_employee_location_q->result();


				echo '{"longitude":'.$map->y_coordinate.', "latitude": '.$map->x_coordinate.', "info_head": "<p><strong>'.$map->location.'</strong></p>", "info_text": "<p>';
 
				foreach ($employee_location as $employee){
					//var_dump($employee->user_last_name);
					echo $employee->user_first_name.' '.$employee->user_last_name.'<br />';
					//echo $employee->$user_last_name.'<br />'; //.' '.$employee->$user_last_name
				}
 
				echo '</p>" },';
				$count++;
			 
		}
		echo '],"count": '.$count.'';



 
	 /*

		$current_date = date("d/m/Y");
		$year = date("Y");
		$next_year_date = '01/01/'.($year+1);
		$current_start_year = '01/01/'.$year;
		$last_start_year = '01/01/'.($year-1);

		$q_maps = $this->dashboard_m->get_map_projects($current_start_year,$current_date,$user_id);
		$map_details = $q_maps->result();
		

		echo "[";
		foreach ($map_details as $map) {

			if($map->y_coordinates != '' && $map->x_coordinates != ''){

				echo '{"longitude":'.$map->y_coordinates.', "latitude": '.$map->x_coordinates.'},';
				$count++;
			}
		}
		echo '],"count": '.$count.'';*/
	}



	public function focus_get_map_locations_mn(){

		$focus_company_maintenence_q = $this->dashboard_m->get_focus_companies_mntnc();
		$focus_company_maintenence = $focus_company_maintenence_q->result();

		$maintenacne_data = array(); 
		foreach ($focus_company_maintenence as $maintenance_data){
			$maintenacne_data[$maintenance_data->focus_company_id] = 0;
			$pm_id = $maintenance_data->project_manager_id;
		}

 
		$user_id = $pm_id;
		  
	 

		$current_date = date("d/m/Y");
		$year = date("Y");
		$next_year_date = '01/01/'.($year+1);
		$current_start_year = '01/01/'.$year;
		$last_start_year = '01/01/'.($year-1);

		$q_maps = $this->dashboard_m->get_map_projects($current_start_year,$current_date,$user_id);
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





	public function wip_widget_mn(){
		$focus_company_maintenence_q = $this->dashboard_m->get_focus_companies_mntnc();
		$focus_company_maintenence = $focus_company_maintenence_q->result();

		$maintenacne_data = array(); 
		foreach ($focus_company_maintenence as $maintenance_data){
			$maintenacne_data[$maintenance_data->focus_company_id] = 0;
			$pm_id = $maintenance_data->project_manager_id;
		}

		$total_string = '';

		$start_date = "01/01/".date("Y");
		$n_year =  date("Y")+1;
		$set_new_date = '01/01/'.$n_year;

		$personal_data = 0;

		$q_wip_vales = $this->dashboard_m->get_wip_permonth($start_date,$set_new_date,$pm_id,'1');
		$wip_values = $q_wip_vales->result();

		foreach ($wip_values as $prj_wip){
			if($prj_wip->label == 'VR' ){
				$amount = $prj_wip->variation_total;
			}else{
				if($prj_wip->install_time_hrs > 0 || $prj_wip->work_estimated_total > 0.00   ){
					$amount = $prj_wip->project_total * ($prj_wip->progress_percent/100);
				}else{
					$amount = $prj_wip->budget_estimate_total * ($prj_wip->progress_percent/100);
				}
			}

			$maintenacne_data[$prj_wip->focus_company_id] = $maintenacne_data[$prj_wip->focus_company_id] + $amount;
			$personal_data = $personal_data + $amount;

		}

		foreach ($focus_company_maintenence as $maintenance_data){
			$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$maintenance_data->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($maintenacne_data[$maintenance_data->focus_company_id],2).'</span></div>';
		}

		$display_total = $this->get_wip_value_permonth($start_date,$set_new_date,$pm_id,'1');
		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';
	}


	public function invoiced_pm(){
		$pm_data = $this->dashboard_m->fetch_project_pm_nomore();
		$pm_q = array_shift($pm_data->result_array());
		$not_pm_arr = explode(',',$pm_q['user_id'] );

		$pm_type = $this->pm_type();
		$user_id = $this->session->userdata('user_id');
		$fetch_user = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($fetch_user->result_array());

		if($pm_type == 1){ // for director/pm
			$direct_company = explode(',',$user_details['direct_company'] );
		}

		if($pm_type == 2){ // for pm only
			$direct_company = explode(',',$user_details['user_focus_company_id'] );
		}

		$c_year = date("Y");
		$n_year = $c_year+1;
		$date_a = "01/01/$c_year";
		$date_b = "01/01/$n_year";
		$date_c = date("d/m/Y");

		$personal_data = 0;

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		$grand_total_sales = 0;
		$sales_total = 0;

		$total_string = '';

		foreach ($focus_company as $company){

			if( in_array($company->company_id, $direct_company) ){

				$q_dash_sales = $this->dashboard_m->dash_sales($date_a,$date_c,$company->company_id,1);

				if($q_dash_sales->num_rows >= 1){

					$grand_total_sales = 0;
					$sales_total = 0;

					$dash_sales = $q_dash_sales->result();
				 
					foreach ($dash_sales as $sales){

					//	$fetch_user = $this->user_model->fetch_user($sales->project_manager_id);
					//	$user_arr = array_shift($fetch_user->result_array());

					//	if( in_array($sales->focus_company_id, $direct_company) ){

						 	if( !in_array($sales->project_manager_id, $not_pm_arr) ){
								if($sales->label == 'VR'){
									$sales_total = $sales->variation_total;
								}else{
									$sales_total = $sales->project_total*($sales->progress_percent/100);
								}

								$grand_total_sales = $grand_total_sales + $sales_total;

								if($user_id == $sales->project_manager_id){
									$personal_data = $personal_data + $sales_total;
								}
							}
					//	}

					}

					$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($grand_total_sales,2).'</span></div>';
				}
			}
		}

		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';
	}





	public function pm_estimates_widget_pm(){
		$pm_type = $this->pm_type();
		$user_id = $this->session->userdata('user_id');
		$fetch_user = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($fetch_user->result_array());

		if($pm_type == 1){ // for director/pm
			$direct_company = explode(',',$user_details['direct_company'] );
		}

		if($pm_type == 2){ // for pm only
			$direct_company = explode(',',$user_details['user_focus_company_id'] );
		}

		$year = date("Y");
		$current_date = date("d/m/Y");
		$current_start_year = '01/01/'.$year;
		$total_string = '';
		$is_restricted = 0;



		$pm_data = $this->dashboard_m->fetch_project_pm_nomore();
		$pm_q = array_shift($pm_data->result_array());
		$not_pm_arr = explode(',',$pm_q['user_id'] );


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
			if( !in_array($un_accepted->project_manager_id, $not_pm_arr) ){

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
					if( in_array($un_accepted->focus_company_id, $direct_company) ){
						if($un_accepted->install_time_hrs > 0 || $un_accepted->work_estimated_total > 0.00 || $un_accepted->variation_total > 0.00 ){
							$amnt =  $un_accepted->project_total + $un_accepted->variation_total;
							$project_cost[$un_accepted->focus_company_id] =  $project_cost[$un_accepted->focus_company_id] + $amnt;
						}else{
							$amnt = $un_accepted->budget_estimate_total;
							$project_cost[$un_accepted->focus_company_id] =  $project_cost[$un_accepted->focus_company_id] + $amnt; 
						}

						if( isset($unaccepted_amount[$un_accepted->project_estiamator_id])) {
							if($user_id == $un_accepted->project_manager_id){
								$unaccepted_amount[$un_accepted->project_estiamator_id] = $unaccepted_amount[$un_accepted->project_estiamator_id] + $amnt;
							}
						}
					}


					if($user_id == $un_accepted->project_manager_id){
						$personal_data = $personal_data + $amnt;
					}

				}
			}
		}

		//var_dump($project_cost);

		foreach ($focus_arr as $comp_id => $value ){
			$display_total_cmp = $project_cost[$comp_id];
			if($display_total_cmp > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cmp,2).'</span></div>';
			}
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


	public function focus_get_po_widget_pm(){
		$pm_type = $this->pm_type();
		$user_id = $this->session->userdata('user_id');
		$fetch_user = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($fetch_user->result_array());

		if($pm_type == 1){ // for director/pm
			$direct_company = explode(',',$user_details['direct_company'] );
		}

		if($pm_type == 2){ // for pm only
			$direct_company = explode(',',$user_details['user_focus_company_id'] );
		}

		$year = date("Y");
		$current_date = '01/01/2019';
		$current_start_year = '01/01/2000';
		$set_cpo = array();

		$focus_arr = array();
		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		foreach ($focus_company as $company){
			$focus_arr[$company->company_id] = $company->company_name;
			$set_cpo[$company->company_id] = 0;
		}

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
			$comp_id = $row['focus_company_id'];

			if( in_array($comp_id, $direct_company) ){
				$set_cpo[$comp_id] = $set_cpo[$comp_id] + $out_standing;
			}

			if($user_id == $row['project_manager_id']){
				$personal_data = $personal_data + $out_standing;
			}
		}

		$total_string = '';

		foreach ($focus_arr as $comp_id => $value ){
			if( in_array($comp_id, $direct_company) ){
				$display_total_cpo = $set_cpo[$comp_id];

				if($display_total_cpo > 0){
					$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cpo,2).'</span></div>';
				}
			}
		}

		$display_total = array_sum($set_cpo);
		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';
	}






	public function focus_projects_count_widget_pm(){
		$pm_type = $this->pm_type();
		$user_id = $this->session->userdata('user_id');
		$fetch_user = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($fetch_user->result_array());

		if($pm_type == 1){ // for director/pm
			$direct_company = explode(',',$user_details['direct_company'] );
		}

		if($pm_type == 2){ // for pm only
			$direct_company = explode(',',$user_details['user_focus_company_id'] );
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

		$focus_arr = array();

		$focus_invoiced = array();
		$focus_invoiced_old = array();

		$focus_comp_wip_count = array();

		foreach ($focus_company as $company){
			$focus_invoiced[$company->company_id] = 0;
			$focus_invoiced_old[$company->company_id] = 0;
			$focus_comp_wip_count[$company->company_id] = 0;	
		}



		foreach ($focus_company as $company) {
			$focus_arr[$company->company_id] = $company->company_name;
			$focus_comp_wip_count[$company->company_id] = 0;

			$invoiced = 0;
			$invoiced_old = 0;

			$projects_qa = $this->dashboard_m->get_wip_invoiced_projects($current_start_year, $next_year_date, $company->company_id);
			$projects_ra = $projects_qa->result_array();

			foreach ($projects_ra as $result) {
				if($this->invoice->if_invoiced_all($result['project_id'])  && $this->invoice->if_has_invoice($result['project_id']) > 0 ){
					$invoiced++;


					if($user_id == $result['project_manager_id']){
						$personal_data_a++;
					}

				}
			}

			$projects_qb = $this->dashboard_m->get_wip_invoiced_projects($last_start_year, $current_start_year, $company->company_id);
			$projects_rb = $projects_qb->result_array();

			foreach ($projects_rb as $result) {
				if($this->invoice->if_invoiced_all($result['project_id'])  && $this->invoice->if_has_invoice($result['project_id']) > 0 ){
					$invoiced_old++;
				}
			}

			$focus_invoiced[$company->company_id] = $invoiced;
			$focus_invoiced_old[$company->company_id] = $invoiced_old;
		}

		$display_inv = array_sum($focus_invoiced);

		$total_string_wip = '';
		$total_string_inv = '';

		$total_string_wip .= 'WIP Count'; 
		$total_string_inv .= 'Invoiced Count';

		foreach ($focus_arr as $comp_id => $value ){
			if($focus_invoiced[$comp_id] > 0){
				if( in_array($comp_id, $direct_company) ){
					$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-7\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-5\'>'.number_format($focus_invoiced[$comp_id]).'</span></div>';
				}
			}
		}

		$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; Last Year</div>';

		$q_maps = $this->dashboard_m->get_map_projects($current_start_year,$current_date);

		//$proj_t = $this->wip_m->display_all_wip_projects();
		foreach ($q_maps->result_array() as $row){
			$comp_id = $row['focus_company_id'];
			$focus_comp_wip_count[$comp_id]++;

			if($user_id == $row['project_manager_id']){
				$personal_data_b++;
			} 
		}

		foreach ($focus_arr as $comp_id => $value ){
			if($focus_comp_wip_count[$comp_id] > 0){
				if( in_array($comp_id, $direct_company) ){
					$total_string_wip .= '<div class=\'row\'><span class=\'col-xs-7\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-5\'>'.number_format($focus_comp_wip_count[$comp_id]).'</span></div>';
				}
			}

			if($focus_invoiced[$comp_id] > 0){
				if( in_array($comp_id, $direct_company) ){
					$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-7\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-5\'>'.number_format($focus_invoiced_old[$comp_id]).'</span></div>';
				}
			}
		}

		$display_wip = array_sum($focus_comp_wip_count);

		echo '<div id="" class="clearfix row">				
		<strong class="text-center col-xs-4"><p class="h5x value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string_inv.'"><i class="fa fa-list-alt"></i> &nbsp;'.$personal_data_a.'</p></strong>
		<strong class="text-center col-xs-4"><p class="h5x value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string_wip.'"><i class="fa fa-tasks"></i> &nbsp;'.$personal_data_b.'</p></strong>
		<strong class="text-center col-xs-4"></strong></div>';

	}





	public function average_date_invoice(){
		$c_year = date("Y");		
		$date_a = "01/01/$c_year";
		$date_b = date("d/m/Y");


		// $date_a = "01/01/2016";
		// $date_b = "29/01/2016";
		$days_dif = array('');
 
		$q_ave = $this->dashboard_m->get_maitenance_dates_pm($date_a,$date_b); //1
		$days_result = $q_ave->result();

		foreach ($days_result as $result){
			$diff = ($result->days_diff < 0 ? 0 : $result->days_diff); 
			array_push($days_dif, $diff);
		}
   
		$size = count($days_dif);

		$size = ($size <= 1 ? 1 : $size);
		$average = array_sum($days_dif) / $size;

		arsort($days_dif,1);

		$long_day =  max($days_dif);
		$short_day_day =  min($days_dif);
		$short_day_day = 1;

		$total_string = '';

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();

		$pms_averg = array();
		$pms_w_avg = array();
		foreach ($project_manager_list as $pm ) {
			$pms_averg[$pm->user_id] = array();
			$pms_w_avg[$pm->user_id] = $pm->user_id;
		}



		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();
	
		foreach ($focus_company as $company){

		if($company->company_id != 4){

				$days_dif_comp = array('');

				$q_ave = $this->dashboard_m->get_maitenance_dates_pm($date_a,$date_b,$company->company_id); //5
				$days_result = $q_ave->result();

				foreach ($days_result as $result){
					if($result->project_manager_id != 9){

						$diff = ($result->days_diff < 0 ? 0 : $result->days_diff); 
						array_push($days_dif_comp,$diff);
					}
				}

				$size_comp = count($days_dif_comp);
				$average_comp = array_sum($days_dif_comp) / $size_comp;

				arsort($days_dif_comp,1);
 
				$days_dif_comp = ($days_dif_comp <= 1 ? 1 : $days_dif_comp);
				$long_day_comp =  max($days_dif_comp);
				$short_day_day_comp =  min($days_dif_comp);
				$short_day_day_comp = 1;
 

				$total_string .= '<div class=\'row\'><span class=\'col-xs-8\'>'.str_replace("Pty Ltd","",$company->company_name).'</span><span class=\'col-xs-4\'>'.round($average_comp,1).'</span></div>';

				
				foreach ($project_manager_list as $pm ) {
					$q_ave_pm = $this->dashboard_m->get_maitenance_dates_pm($date_a,$date_b,$pm->user_id,$company->company_id); //4
					$days_result_pm = $q_ave_pm->result();

					foreach ($days_result_pm as $result_pm){
						$diff = ($result_pm->days_diff < 0 ? 0 : $result_pm->days_diff); 
						array_push($pms_averg[$pm->user_id],$diff);
					}
				}
 			}
		}



		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';
 

		foreach ($project_manager_list as $pm ){

			if( count($pms_averg[$pm->user_id]) > 0  ){
				//	var_dump($pms_averg[$pm->user_id]);
				//	echo "<p></p>"; 
				$size = count($pms_averg[$pm->user_id]);

				//if($size > 0){
				$pm_average = array_sum($pms_averg[$pm->user_id]) / $size;
				arsort($pms_averg[$pm->user_id],1);
				$pm_long_day =  max($pms_averg[$pm->user_id]);
				$pm_short_day_day =  min($pms_averg[$pm->user_id]);
				$pm_short_day_day = 1;
				$pm_name = $pm->user_first_name;
				$total_string .= '<div class=\'row\'><span class=\'col-xs-8\'>'.$pm_name.'</span><span class=\'col-xs-4\'>'.round($pm_average,1).'</span></div>';
			}
		}



		$last_year = intval(date("Y")) - 1;
		$this_month = date("m");
		$this_day = date("d");

		$date_a_last = "01/01/$last_year";
		$date_b_last = "$this_day/$this_month/$last_year";
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; Last Year</div>';


		$pms_averg_old = array();	 
		foreach ($project_manager_list as $pm ) {
			$pms_averg_old[$pm->user_id] = array();			 
		}

		foreach ($focus_company as $company){
			if($company->company_id != 4){
				$days_dif_comp = array('');
				$q_ave = $this->dashboard_m->get_maitenance_dates_pm($date_a_last,$date_b_last,$company->company_id); //5
				$days_result = $q_ave->result();

				foreach ($days_result as $result){
					if($result->project_manager_id != 9){
						$diff = ($result->days_diff < 0 ? 0 : $result->days_diff); 
						array_push($days_dif_comp,$diff);
					}
				}

				$size_comp = count($days_dif_comp);
				$average_comp = array_sum($days_dif_comp) / $size_comp;

				arsort($days_dif_comp,1);
 
				$days_dif_comp = ($days_dif_comp <= 1 ? 1 : $days_dif_comp);
				$long_day_comp =  max($days_dif_comp);
				$short_day_day_comp =  min($days_dif_comp);
				$short_day_day_comp = 1;
 

				$total_string .= '<div class=\'row\'><span class=\'col-xs-8\'>'.str_replace("Pty Ltd","",$company->company_name).'</span><span class=\'col-xs-4\'>'.round($average_comp,1).'</span></div>';

				
				foreach ($project_manager_list as $pm ) {
					$q_ave_pm = $this->dashboard_m->get_maitenance_dates_pm($date_a_last,$date_b_last,$pm->user_id,$company->company_id); //4
					$days_result_pm = $q_ave_pm->result();

					foreach ($days_result_pm as $result_pm){
						$diff = ($result_pm->days_diff < 0 ? 0 : $result_pm->days_diff); 
						array_push($pms_averg_old[$pm->user_id],$diff);
					}
				}


 			}
		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';


		foreach ($project_manager_list as $pm ){

			if(array_key_exists($pm->user_id,$pms_averg_old)){

				$size = count($pms_averg_old[$pm->user_id]);

				if($size > 0){ 
				
				

				$pm_average = array_sum($pms_averg_old[$pm->user_id]) / $size;
				arsort($pms_averg_old[$pm->user_id],1);
				$pm_long_day =  max($pms_averg_old[$pm->user_id]);
				$pm_short_day_day =  min($pms_averg_old[$pm->user_id]);
				$pm_short_day_day = 1;
				$pm_name = $pm->user_first_name;

				$total_string .= '<div class=\'row\'><span class=\'col-xs-8\'>'.$pm_name.'</span><span class=\'col-xs-4\'>'.round($pm_average,1).'</span></div>';
			}
				}

 


	//	$size = ($size < 1 ? 1 : $size);


		}


		echo '<div id="" class="tooltip-enabled" title="" data-placement="bottom" data-html="true" data-original-title="'.$total_string .'">
			<input class="knob" data-width="100%" data-step=".1"  data-thickness=".13" value="'.number_format($average,1).'" readonly data-fgColor="#964dd7" data-angleOffset="-180"  data-max="'.$long_day.'">
			<div id="" class="clearfix m-top-10"><div id="" class="col-xs-6"><strong><p>'.$short_day_day.' min</p></strong></div><div id="" class="col-xs-6 text-right"><strong><p>max '.$long_day.'</p></strong></div></div></div>';

	}








	public function average_date_invoice_pm($pm_data_id=''){

		$user_id = ($pm_data_id == '' ? $this->session->userdata('user_id') : $pm_data_id);
		$fetch_user = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($fetch_user->result_array());
 
		if($pm_data_id != ''){
			if($user_details['user_role_id'] == 3 && $user_details['user_department_id'] == 1):
				$pm_type = 1;
			endif; //for directors 

			if($user_details['user_role_id'] == 3 && $user_details['user_department_id'] == 4): //for PM 
				$pm_type = 2;
			endif; //for PM 

			if($user_id == 29):
				$pm_type = 2;
			endif; //for maintenance manager 
		}else{
			$pm_type = $this->pm_type();
		}






		if($pm_type == 1){ // for director/pm
			$direct_company = explode(',',$user_details['direct_company'] );
		}

		if($pm_type == 2){ // for pm only
			$direct_company = explode(',',$user_details['user_focus_company_id'] );
		}

		if($user_id == 29):
			$direct_company = explode(',','5,6');
		endif; //for maintenance manager 

		$c_year = date("Y");		
		$date_a = "01/01/$c_year";
		$date_b = date("d/m/Y");
		$days_dif = array();

		$long_day = 0;
		$size = 0;
		$short_day_day = 0;




		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();

		$pms_averg = array();
		$pms_w_avg = array();
		foreach ($project_manager_list as $pm ) {
			$pms_averg[$pm->user_id] = array();
			$pms_w_avg[$pm->user_id] = $pm->user_id;
		}

 
 

 		foreach ($direct_company as $key => $comp_id) {
			$q_ave = $this->dashboard_m->get_maitenance_dates_pm($date_a,$date_b,$user_id,$comp_id); //4
			$days_result = $q_ave->result();

			foreach ($days_result as $result){

						$diff = ($result->days_diff < 0 ? 0 : $result->days_diff); 
				array_push($days_dif, $diff);
			}

			if($pm_type == 1){ // for director/pm
				foreach ($project_manager_list as $pm ) {
					$q_ave_pm = $this->dashboard_m->get_maitenance_dates_pm($date_a,$date_b,$pm->user_id,$comp_id); //4
					$days_result_pm = $q_ave_pm->result();

					foreach ($days_result_pm as $result_pm){
						$diff = ($result_pm->days_diff < 0 ? 0 : $result_pm->days_diff);
						array_push($pms_averg[$pm->user_id],$diff);
					}
				}
			}
 		}



 

//		var_dump($days_dif);
 
		$size = count($days_dif);

		if($size > 0){
			$average = array_sum($days_dif) / $size;
			arsort($days_dif,1);
			$long_day =  max($days_dif);
			$short_day_day =  min($days_dif);
			$short_day_day = 1;
		}else{
			$average = 0;
			$long_day = 0;
			$short_day_day = 0;
		}

		$total_string = '';


		$comp_custom_msge = '';
		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();


		foreach ($focus_company as $company){

			if( in_array($company->company_id, $direct_company) ){

				$days_dif_comp = array('');

				$q_ave = $this->dashboard_m->get_maitenance_dates_pm($date_a,$date_b,$company->company_id); //5
				$days_result = $q_ave->result();

				foreach ($days_result as $result){
					if($result->project_manager_id != 9){
						$diff = ($result->days_diff < 0 ? 0 : $result->days_diff);
						array_push($days_dif_comp, $diff);
					}
				}

				$size_comp = count($days_dif_comp);
				$average_comp = array_sum($days_dif_comp) / $size_comp;

				arsort($days_dif_comp,1);
 
				$long_day_comp =  max($days_dif_comp);
				$short_day_day_comp =  min($days_dif_comp);
				$short_day_day_comp = 1;
 
				// $total_string .= '<span>'.str_replace("Pty Ltd","",$company->company_name).' &nbsp; '.number_format($average_comp,1).'</span><br />';


				$total_string .= '<div class=\'row\'><span class=\'col-xs-8\'>'.str_replace("Pty Ltd","",$company->company_name).'</span><span class=\'col-xs-4\'>'.round($average_comp,1).'</span></div>';

				if($pm_data_id != ''){
					$comp_custom_msge .= '<br />'.str_replace("Pty Ltd","",$company->company_name).': '.round($average_comp,1);
				}

			}
		}

			if($pm_type == 1){ // for director/pm

				$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';



				foreach ($project_manager_list as $pm ){

					if( count($pms_averg[$pm->user_id]) > 0  ){
						//	var_dump($pms_averg[$pm->user_id]);
						//	echo "<p></p>"; 
						$size = count($pms_averg[$pm->user_id]);

						//if($size > 0){
						$pm_average = array_sum($pms_averg[$pm->user_id]) / $size;
						arsort($pms_averg[$pm->user_id],1);
						$pm_long_day =  max($pms_averg[$pm->user_id]);
						$pm_short_day_day =  min($pms_averg[$pm->user_id]);
						$pm_short_day_day = 1;
						/*}else{
							$pm_average = 0;
							$pm_long_day = 0;
							$pm_short_day_day = 0;
						}*/

						$pm_name = $pm->user_first_name;
						$total_string .= '<div class=\'row\'><span class=\'col-xs-8\'>'.$pm_name.'</span><span class=\'col-xs-4\'>'.round($pm_average,1).'</span></div>';
					}
				}
			} 


		echo '<div id="" class="tooltip-enabled" title="" data-placement="bottom" data-html="true" data-original-title="'.$total_string .'">
				<input class="knob" data-width="100%" data-step=".1"  data-thickness=".13" value="'.number_format($average,1).'" readonly data-fgColor="#964dd7" data-angleOffset="-180"  data-max="'.$long_day.'">
				<div id="" class="clearfix m-top-10">
					<div id="" class="col-xs-6"><strong><p>'.$short_day_day.' min</p></strong></div>
					<div id="" class="col-xs-6 text-right"><strong><p>max '.$long_day.'</p></strong></div>
				</div>';
				if($pm_data_id != ''){
					 echo "<center style='margin-top:-20px;'><span><strong>$comp_custom_msge</strong></span></center>";
				}

			echo '</div>';

	}


	public function uninvoiced_widget_pm(){
		$pm_type = $this->pm_type();
		$user_id = $this->session->userdata('user_id');
		$fetch_user = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($fetch_user->result_array());

		if($pm_type == 1){ // for director/pm
			$direct_company = explode(',',$user_details['direct_company'] );
		}

		if($pm_type == 2){ // for pm only
			$direct_company = explode(',',$user_details['user_focus_company_id'] );
		}

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

		foreach ($focus_company as $company){

			if( in_array($company->company_id, $direct_company) ){

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

					if($user_id == $unvoiced->project_manager_id){
						$personal_data = $personal_data + $unvoiced_total;
					}
				}

				if($unvoiced_grand_total > 0){
					$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($unvoiced_grand_total,2).'</span></div>';
				}
			}

		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();

		foreach ($project_manager_list as $pm ) {
			$total_outstanding = 0;
			if( in_array($pm->user_focus_company_id, $direct_company) ){			

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


			$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm->user_first_name.'</span> <span class=\'col-xs-6\'>$ '.number_format($total_outstanding,2).'</span></div>';


		}

		}


		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';
	}



	public function wip_widget_pm(){
		$pm_type = $this->pm_type();
		$user_id = $this->session->userdata('user_id');
		$fetch_user = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($fetch_user->result_array());

		if($pm_type == 1){ // for director/pm
			$direct_company = explode(',',$user_details['direct_company'] );
		}

		if($pm_type == 2){ // for pm only
			$direct_company = explode(',',$user_details['user_focus_company_id'] );
		}

		$total_string = '';

		$focus_arr = array();
		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		foreach ($focus_company as $company){
			$focus_arr[$company->company_id] = $company->company_name;
		}

		$start_date = "01/01/".date("Y");
		$n_year =  date("Y")+1;
		$set_new_date = '01/01/'.$n_year;


		foreach ($focus_arr as $comp_id => $value ){
			if( in_array($comp_id, $direct_company) ){
				$wip_amount = $this->get_wip_value_permonth($start_date,$set_new_date,$comp_id);

				if($wip_amount > 0){
					$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-6\'>$ '.number_format($wip_amount,2).'</span></div>';
				}
			}
		}
/*
		// for previous year
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; Last Year</div>';

		$last_year = intval(date("Y")) - 1;
		$this_month = date("m");
		$this_day = date("d");
 
		$date_a = "01/01/$last_year";
		$date_b = "01/01/".date("Y");

		foreach ($focus_arr as $comp_id => $value ){

			if( in_array($comp_id, $direct_company) ){

				$wip_amount = $this->get_wip_value_permonth($date_a,$date_b,$comp_id);

				if($wip_amount > 0){
					$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-6\'>$ '.number_format($wip_amount,2).'</span></div>';
				}
			}
		}
*/

		$display_total = 0;
		$fYear = intval(date("Y"))+2;

		$date_f = "01/01/$fYear";
		$date_ts = '01/01/1990';
		
		$display_total = $this->get_wip_value_permonth($start_date,$set_new_date,$user_id,'1');

		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($display_total,2).'</strong></p>';
 
	}



	public function outstanding_payments_widget_pm(){
		$pm_type = $this->pm_type();
		$user_id = $this->session->userdata('user_id');
		$fetch_user = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($fetch_user->result_array());

		if($pm_type == 1){ // for director/pm
			$direct_company = explode(',',$user_details['direct_company'] );
		}

		if($pm_type == 2){ // for pm only
			$direct_company = explode(',',$user_details['user_focus_company_id'] );
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

			if( in_array($company->company_id, $direct_company) ){

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
					//$total_invoice = $total_invoice + $invoice_amount;
					$total_paid =  $oustanding->amount_exgst;

					//$display_each_value = $invoice_amount - $total_paid;

					//if($user_id == $oustanding->project_manager_id){
						$display_each_value = $invoice_amount - $total_paid;
					//}

					//Outstanding

					$pm_outstanding[$oustanding->project_manager_id] = $pm_outstanding[$oustanding->project_manager_id] + $display_each_value;


					$each_comp_total[$oustanding->focus_company_id] = $each_comp_total[$oustanding->focus_company_id] + $display_each_value;
				}

				//var_dump($pm_outstanding);

				$personal_data = $pm_outstanding[$user_id];

				$total_outstanding =  $each_comp_total[$company->company_id];

				if($total_outstanding > 0){
					$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($total_outstanding,2).'</span></div>';
				}
			}
		}


		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';


		foreach ($project_manager_list as $pm ) {
 
			$pm_name = $pm->user_first_name;
			$amount = $pm_outstanding[$pm->user_id];

			//if( in_array($pm->user_focus_company_id, $direct_company) ){
			if( $amount > 0){

				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name.'</span> <span class=\'col-xs-6\'>$ '.number_format($amount,2).'</span></div>';
			}

		}

		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';
}


	public function pm_sales_widget_pm(){

		$pm_type = $this->pm_type();
		$user_id = $this->session->userdata('user_id');
		$fetch_user = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($fetch_user->result_array());

		if($pm_type == 1){ // for director/pm
			$direct_company = explode(',',$user_details['direct_company'] );
		}

		if($pm_type == 2){ // for pm only
			$direct_company = explode(',',$user_details['user_focus_company_id'] );
		}

		$grand_total_sales_cmp = 0;
		$grand_total_uninv_cmp = 0;
		$grand_total_over_cmp = 0;

		$c_year = date("Y");		
		$date_a = "01/01/$c_year";
		$date_b = date("d/m/Y");
		$n_year =  date("Y")+1;
		$set_new_date = '01/01/'.$n_year;
		$date_c = date("d/m/Y");


		$pm_data = $this->dashboard_m->fetch_project_pm_nomore();
		$pm_q = array_shift($pm_data->result_array());
		$not_pm_arr = explode(',',$pm_q['user_id'] );


		$pm_set_data = array();

		$wip_pm_total = array();

		$overall_total_sales = 0;
		$sales_result = array();
		$focus_pms = array();
		$focus_pm_pic = array();
		$focus_pm_comp = array();

		$set_invoiced_amount = array();

		$total_invoiced_init = 0;
		$total_string = '';

		$return_total = 0;

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();

		foreach ($project_manager_list as $pm ) {
			$set_invoiced_amount[$pm->user_id] = 0;
			$sales_result[$pm->user_id] = 0;
			$focus_pms[$pm->user_id] = $pm->user_first_name;
			$focus_pm_pic[$pm->user_id] = $pm->user_profile_photo;
			$focus_pm_comp[$pm->user_id] = $pm->user_focus_company_id;

			$wip_pm_total[$pm->user_id] = 0;
			$sales_result[$pm->user_id] = 0;

			if( in_array($pm->user_focus_company_id, $direct_company) ){
				$pm_set_data[$pm->user_id] = $pm->user_id;
			}
		}

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		foreach ($focus_company as $company){

			if( in_array($company->company_id, $direct_company) ){


				$q_dash_sales = $this->dashboard_m->dash_sales($date_a,$date_c,$company->company_id,1);

				if($q_dash_sales->num_rows >= 1){

					$grand_total_sales = 0;
					$sales_total = 0;

					$dash_sales = $q_dash_sales->result();

					foreach ($dash_sales as $sales){
						if( !in_array($sales->project_manager_id, $not_pm_arr) ){

							if($sales->label == 'VR'){
								$sales_total = $sales->variation_total;
							}else{
								$sales_total = $sales->project_total*($sales->progress_percent/100);
							}

							$set_invoiced_amount[$sales->project_manager_id] = $set_invoiced_amount[$sales->project_manager_id] + $sales_total;
							$pm_set_data[$sales->project_manager_id] = $sales->project_manager_id;

							$grand_total_sales_cmp = $grand_total_sales_cmp + $sales_total;

						}
					}					
				}
			}
		}

		$forecast_focus_total = 0; 
		foreach ($project_manager_list as $pm ) {
			$total_sales = 0;
			$total_outstanding = 0;

			$q_pm_sales = $this->dashboard_m->dash_total_pm_sales($pm->user_id,$c_year,'',$date_a,$date_b,$pm->user_focus_company_id);
			$pm_sales = $q_pm_sales->result_array();

			foreach ($pm_sales as $sales => $value){

				if($value['label'] == 'VR'){
					$project_total_percent = $value['variation_total'];
				}else{
					$project_total_percent = $value['project_total'] * ($value['progress_percent']/100);
				}
			}

		}
 

		foreach ($direct_company as $key => $comp_id) {

			foreach ($pm_set_data as $pm_id => $value){
//ion get_wip_perso
				$wip_amount = $this->get_wip_personal($date_a,$set_new_date,$pm_id,$comp_id);
				$wip_pm_total[$pm_id] = $wip_pm_total[$pm_id] + $wip_amount;
				$sales_result[$pm_id] = $sales_result[$pm_id] + $wip_amount + $set_invoiced_amount[$pm_id];

			//	echo $comp_id.'---'.$pm_id.'---'.$wip_amount.'<br />';
			}

		}


		arsort($sales_result);
		$total_wip = array_sum($wip_pm_total);

		$total_invoiced = array_sum($set_invoiced_amount);
		echo "<div style=\"overflow-y: auto; padding-right: 5px; height: 400px;\">";

		foreach ($sales_result as $pm_id => $sales){

			if( $sales > 0){
				$comp_id_pm = $focus_pm_comp[$pm_id];

				//$pm_wip = (( $set_estimates[$pm_id] + $set_quotes[$pm_id] ) - $set_invoiced[$pm_id] );

				$q_current_forecast_comp = $this->dashboard_m->get_current_forecast($c_year,$comp_id_pm,'1');
				$comp_forecast = array_shift($q_current_forecast_comp->result_array());

				$q_current_forecast = $this->dashboard_m->get_current_forecast($c_year,$pm_id);
				$pm_forecast = array_shift($q_current_forecast->result_array());

				$total_forecast = ( $comp_forecast['total'] * (  $comp_forecast['forecast_percent']  /100  ) *  ($pm_forecast['forecast_percent']/100) );


				$pm_sales_value = $set_invoiced_amount[$pm_id] + $wip_pm_total[$pm_id];

				$pm_sales_value = ($pm_sales_value <= 1 ? 1 : $pm_sales_value);
				$total_forecast = ($total_forecast <= 1 ? 1 : $total_forecast);

				if( $pm_sales_value > 0 ){
					$status_forecast = round(100/($total_forecast/$pm_sales_value));
				}else{
					$status_forecast = 0;
				}


				echo '<div class="m-bottom-15 clearfix"><div class="pull-left m-right-10"  style="height: 50px; width:50px; border-radius:50px; overflow:hidden; border: 1px solid #999999;"><img class="user_avatar img-responsive img-rounded" src="'.base_url().'/uploads/users/'.$focus_pm_pic[$pm_id].'"" /></div>';
				echo '<div class="" id=""><p><strong>'.$focus_pms[$pm_id].'</strong><span class="pull-right"><span class="label pull-right m-bottom-3 m-top-3 small_orange_fixed"><i class="fa fa-usd"></i> '.number_format($set_invoiced_amount[$pm_id]).'</span> <br /> <span class="label pull-right m-bottom-3 small_green_fixed"><i class="fa fa-exclamation-triangle"></i> '.number_format($wip_pm_total[$pm_id]).'</span></span></p>';
				echo '<p><i class="fa fa-usd"></i> '.number_format($pm_sales_value).'</p>';

				echo '<div class="progress no-m m-top-3 clearfix tooltip-enabled" title="" data-original-title="'.$status_forecast.'% - $'.number_format($pm_sales_value).' / $'.number_format($total_forecast).'   " style="height: 7px;">
				<div class="progress-bar progress-bar-danger" style="width:'.$status_forecast.'%; background:red;"></div></div></div></div>';

				echo "<div class='clearfix'></div>";
				$forecast_focus_total = $forecast_focus_total + $total_forecast;

				if($user_id == $pm_id){
					$return_total = $status_forecast;
				}
			}
		}

		echo "</div>";


		$status_forecast = round(100/($forecast_focus_total/ ($total_wip + $total_invoiced) ));

		$comp_code = '';
		if($pm_type == 2 || count($direct_company) === 1){ // for pm only

			if($user_details['user_focus_company_id'] == 5){
				$comp_code = 'WA';
			}

			if($user_details['user_focus_company_id'] == 6){
				$comp_code = 'NSW';
			}
		}

		echo '<div class="clearfix" style="padding-top: 6px;    border-top: 1px solid #eee;"><i class="fa fa-briefcase" style="font-size: 42px;float: left;margin-left: 7px;margin-right: 10px;"></i>';
		echo '<div class="" id=""><p><strong>Overall Focus '.$comp_code.'</strong><span class="pull-right"><span class="label pull-right m-bottom-3 m-top-3 small_orange_fixed"><i class="fa fa-usd"></i> '.number_format($grand_total_sales_cmp).'</span> <br /> <span class="label pull-right m-bottom-3 small_green_fixed"><i class="fa fa-exclamation-triangle"></i> '.number_format($total_wip).'</span></span></p>';
		echo '<p><i class="fa fa-usd"></i> '.number_format( ($total_wip + $total_invoiced) ).' <strong class="pull-right m-right-10"></strong></p> </p>';

		echo '<div class="progress no-m m-top-3 clearfix tooltip-enabled" title="" data-original-title="'.$status_forecast.'% - $'.number_format( ($total_wip + $total_invoiced) ).' / $'.number_format($forecast_focus_total).'   " style="height: 7px;">
		<div class="progress-bar progress-bar-danger" style="width:'.$status_forecast.'%; background:red;"></div></div></div></div>';
		echo "<div class='clearfix'></div>";
		return $return_total;

	}








	public function sales_widget(){
		$pm_data = $this->dashboard_m->fetch_project_pm_nomore();
		$pm_q = array_shift($pm_data->result_array());
		$not_pm_arr = explode(',',$pm_q['user_id'] );

		$c_year = date("Y");
		$n_year = $c_year+1;
		$date_a = "01/01/$c_year";
		$date_b = "01/01/$n_year";

		$date_c = date("d/m/Y");
		
		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		$grand_total_sales = 0;
		$sales_total = 0;

		$display_total = 0;
		$total_string = '';

		foreach ($focus_company as $company){
			$q_dash_sales = $this->dashboard_m->dash_sales($date_a,$date_c,$company->company_id,1);

				if($q_dash_sales->num_rows >= 1){

					$grand_total_sales = 0;
					$sales_total = 0;

					$dash_sales = $q_dash_sales->result();

					foreach ($dash_sales as $sales){
						if( !in_array($sales->project_manager_id, $not_pm_arr) ){

							if($sales->label == 'VR'){
								$sales_total = $sales->variation_total;
							}else{
								$sales_total = $sales->project_total*($sales->progress_percent/100);
							}
							$grand_total_sales = $grand_total_sales + $sales_total;

						}
					}
					$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($grand_total_sales,2).'</span></div>';
					$display_total = $display_total + $grand_total_sales;
				
			}
		}

		$last_year = intval(date("Y"))-1;
		$n_month = date("m");
		$n_day = date("d");

		$date_a_last = "01/01/$last_year";
		$date_b_last = "$n_day/$n_month/$last_year";

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; Last Year</div>';

		foreach ($focus_company as $company){
			$q_dash_sales = $this->dashboard_m->dash_sales($date_a_last,$date_b_last,$company->company_id,1);

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
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($grand_total_sales,2).'</span></div>';
			}
		}
		 
		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($display_total,2).'</strong></p>';
	}

	public function wip_widget(){
		$total_string = '';
		$focus_arr = array();
		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		foreach ($focus_company as $company){
			$focus_arr[$company->company_id] = $company->company_name;
		}

		$start_date = "01/01/".date("Y");
		$n_year =  date("Y")+1;
		$set_new_date = '01/01/'.$n_year;


		foreach ($focus_arr as $comp_id => $value ){

			$wip_amount = $this->get_wip_value_permonth($start_date,$set_new_date,$comp_id);

			if($wip_amount > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-6\'>$ '.number_format($wip_amount,2).'</span></div>';
			}
		}


		$display_total = 0;
		$fYear = intval(date("Y"))+2;

		$date_f = "01/01/$fYear";
		$date_ts = '01/01/1990';
		
		$display_total = $this->get_wip_value_permonth($date_ts,$date_f);

/*
$n_month = date("m");
		$n_day = date("d");

		$last_year = intval(date("Y"))-1;
		$date_a_last = "01/01/$last_year";
		$date_b_last = "$n_day/$n_month/$last_year";

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; Last Year</div>';


		foreach ($focus_arr as $comp_id => $value ){

			$wip_amount = $this->get_wip_value_permonth($date_a_last,$date_b_last,$comp_id);

			if($wip_amount > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-6\'>$ '.number_format($wip_amount,2).'</span></div>';
			}
		}
*/



		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($display_total,2).'</strong></p>';
 
	}


	public function uninvoiced_widget(){
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

		//$date_a_tmsp = mktime(0, 0, 0, $c_month, $c_day, $c_year);

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();
		$display_total = 0;

		foreach ($focus_company as $company){

		//	echo $company->company_id.'<br />';

			$q_dash_unvoiced = $this->dashboard_m->dash_unvoiced_per_date($date_a,$date_b,$company->company_id);
			$dash_unvoiced = $q_dash_unvoiced->result();

		//	var_dump($dash_unvoiced);

			$unvoiced_total = 0;
			$unvoiced_grand_total = 0;

			foreach ($dash_unvoiced as $unvoiced) {
				if($unvoiced->label == 'VR'){
					$unvoiced_total = $unvoiced->variation_total;
				}else{
					$unvoiced_total = $unvoiced->project_total*($unvoiced->progress_percent/100);
				}

				$unvoiced_grand_total = $unvoiced_grand_total + $unvoiced_total;
			}

			if($company->company_id != 4){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($unvoiced_grand_total,2).'</span></div>';
				$display_total = $display_total + $unvoiced_grand_total;
			}
		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';
		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();

		foreach ($project_manager_list as $pm ) {
			$total_outstanding = 0;
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

			$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm->user_first_name.'</span> <span class=\'col-xs-6\'>$ '.number_format($total_outstanding,2).'</span></div>';
		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; Last Year</div>';

		$last_year = intval(date("Y"))-1;
		$date_a_last = "01/01/$last_year";
		$date_b_last = "31/12/$last_year";

		$n_month = date("m");
		$n_day = date("d");
		$date_last_year_today = "$n_day/$n_month/$last_year";
		$date_b_full_end = "31/12/$c_year";

		foreach ($project_manager_list as $pm ) {
			$total_outstanding = 0;
			$q_pm_outstanding = $this->dashboard_m->dash_total_pm_sales($pm->user_id,$last_year,1,$date_a_last,$date_b_last);
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

			$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm->user_first_name.'</span> <span class=\'col-xs-6\'>$ '.number_format($total_outstanding,2).'</span></div>';
		}


		$total_alltime_uninvoiced = 0;
		foreach ($focus_company as $company){
			$q_dash_unvoiced = $this->dashboard_m->dash_unvoiced_per_date('01/01/2011',$date_b,$company->company_id);
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
			}

			if($company->company_id != 4){
				$total_alltime_uninvoiced = $total_alltime_uninvoiced + $unvoiced_grand_total;
			}
		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';
		$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>All Time</span> <span class=\'col-xs-6\'>$ '.number_format($total_alltime_uninvoiced,2).'</span></div>';

		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($total_alltime_uninvoiced,2).'</strong></p>';
	}


	public function outstanding_payments_widget(){
		$c_year = date("Y");
	
		$date_a = "01/01/$c_year";

		$n_year = date("Y");
		$n_month = date("m");
		$n_day = date("d");

		$date_b = "$n_day/$n_month/$n_year";
 
		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		$total_string = '';
		$display_total = 0;

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
			}

			$total_outstanding = $total_invoice - $total_paid;

			if($total_outstanding > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($total_outstanding,2).'</span></div>';
				$display_total = $display_total + $total_outstanding;
			}
		}


		$last_year = intval(date("Y"))-1;
		$date_a_last = "01/01/$last_year";
  	$date_b_last = "$n_day/$n_month/$last_year";
//		$date_b_last = "29/03/2016";

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; Last Year</div>';

 foreach ($focus_company as $company){
		 	if($company->company_id != 4){
			$invoice_amount= 0;
			$total_invoice= 0;
			$total_paid = 0;
			$total_outstanding = 0;

			$key_id = '';

			$q_dash_oustanding_payments = $this->dashboard_m->dash_oustanding_payments($date_a_last,$date_b_last,$company->company_id,1);
			$oustanding_payments = $q_dash_oustanding_payments->result();

			foreach ($oustanding_payments as $oustanding) {


			 $total_outstanding = $oustanding->lst_outstndg_amount;
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($total_outstanding,2).'</span></div>';




			
			}	
 	}

 	}





		$total_outstanding_all_time = 0;
		
		foreach ($focus_company as $company){
			$invoice_amount= 0;
			$total_invoice= 0;
			$total_paid = 0;
			$total_outstading_value = 0;

			$key_id = '';

			$q_dash_oustanding_payments = $this->dashboard_m->dash_oustanding_payments('01/01/2014',$date_b,$company->company_id);
			$oustanding_payments = $q_dash_oustanding_payments->result();

			foreach ($oustanding_payments as $oustanding) {
				if($oustanding->label == 'VR'){
					$invoice_amount = $oustanding->variation_total;
				}else{
					$invoice_amount = $oustanding->project_total*($oustanding->progress_percent/100);
				}

				$outstanding = $this->invoice->get_current_balance($oustanding->project_id,$oustanding->invoice_id,$invoice_amount);
				$total_outstading_value = $outstanding;

			$total_outstanding_all_time =  $total_outstanding_all_time + $total_outstading_value;
			}
	}


			$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';
			$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>All Time</span> <span class=\'col-xs-6\'>$ '.number_format($total_outstanding_all_time,2).'</span></div>';


		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($total_outstanding_all_time,2).'</strong></p>';
 //	echo "<p>$total_string </p>";

	}


	public function pm_sales_widget(){

		$pm_data = $this->dashboard_m->fetch_project_pm_nomore();
		$pm_q = array_shift($pm_data->result_array());
		$not_pm_arr = explode(',',$pm_q['user_id'] );


		$grand_total_sales_cmp = 0;
		$grand_total_uninv_cmp = 0;
		$grand_total_over_cmp = 0;

		$c_year = date("Y");		
		$date_a = "01/01/$c_year";
		$date_b = date("d/m/Y");

		$wip_pm_total = array();

		$overall_total_sales = 0;
		$sales_result = array();
		$focus_pms = array();
		$focus_pm_pic = array();
		$focus_pm_comp = array();

		$set_invoiced_amount = array();

		$total_invoiced_init = 0;
		$total_string = '';

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();

		foreach ($project_manager_list as $pm ) {
			$set_invoiced_amount[$pm->user_id] = 0;
			$sales_result[$pm->user_id] = 0;
			$focus_pms[$pm->user_id] = $pm->user_first_name;
			$focus_pm_pic[$pm->user_id] = $pm->user_profile_photo;
			$focus_pm_comp[$pm->user_id] = $pm->user_focus_company_id;
		}

		$forecast_focus_total = 0;

 
						



		foreach ($project_manager_list as $pm ) {
			$total_sales = 0;
			$total_outstanding = 0;


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

			$set_invoiced_amount[$pm->user_id] = $set_invoiced_amount[$pm->user_id] + $total_sales;
			
			$overall_total_sales = $total_sales;// + $total_outstanding;

			$q_current_forecast_comp = $this->dashboard_m->get_current_forecast($c_year,$pm->user_focus_company_id,'1');
			$comp_forecast = array_shift($q_current_forecast_comp->result_array());

			$q_current_forecast = $this->dashboard_m->get_current_forecast($c_year,$pm->user_id);
			$pm_forecast = array_shift($q_current_forecast->result_array());

			$total_forecast = ( $comp_forecast['total'] * (  $comp_forecast['forecast_percent']  /100  ) *  ($pm_forecast['forecast_percent']/100) );

			$grand_total_sales_cmp = $grand_total_sales_cmp + $total_sales;
			$grand_total_uninv_cmp = $grand_total_uninv_cmp + $total_outstanding;
			$grand_total_over_cmp = $grand_total_over_cmp + $overall_total_sales;

		}

		$n_year =  date("Y")+1;
		$set_new_date = '01/01/'.$n_year;

		foreach ($project_manager_list as $pm ) {
			$wip_amount = $this->get_wip_value_permonth($date_a,$set_new_date,$pm->user_id,1);
			$wip_pm_total[$pm->user_id] = $wip_amount;
			$sales_result[$pm->user_id] = /*(( $set_estimates[$pm->user_id] + $set_quotes[$pm->user_id] ) - $set_invoiced[$pm->user_id] ) +*/ $wip_amount +  $set_invoiced_amount[$pm->user_id];
		}

		arsort($sales_result);
		$total_wip = array_sum($wip_pm_total);

		$total_invoiced = array_sum($set_invoiced_amount);
		echo "<div style=\"overflow-y: auto; padding-right: 5px; height: 400px;\">";

		foreach ($sales_result as $pm_id => $sales){
			$comp_id_pm = $focus_pm_comp[$pm_id];

			//$pm_wip = (( $set_estimates[$pm_id] + $set_quotes[$pm_id] ) - $set_invoiced[$pm_id] );

			$q_current_forecast_comp = $this->dashboard_m->get_current_forecast($c_year,$comp_id_pm,'1');
			$comp_forecast = array_shift($q_current_forecast_comp->result_array());

			if($pm_id == '29'){
				$q_current_forecast_q = $this->dashboard_m->get_current_forecast($c_year,$pm_id,'',$pm_id);
				$pm_forecast = array_shift($q_current_forecast_q->result_array());

				$total_forecast = ( $comp_forecast['total'] * (  $pm_forecast['wa_fct']  /100  ) *  ($pm_forecast['forecast_percent']/100) );
				$total_forecast = $total_forecast + ( $comp_forecast['total'] * (  $pm_forecast['nws_fct']  /100  ) *  ($pm_forecast['nws_fct_b']/100) );

			}else{
				$q_current_forecast = $this->dashboard_m->get_current_forecast($c_year,$pm_id);
				$pm_forecast = array_shift($q_current_forecast->result_array());
				$total_forecast = ( $comp_forecast['total'] * (  $comp_forecast['forecast_percent']  /100  ) *  ($pm_forecast['forecast_percent']/100) );
			}







			if($sales > 0 && $total_forecast > 0 ){
				$status_forecast = round(100/($total_forecast/$sales));
			}else{
				$status_forecast = 0;
			}

			echo '<div class="m-bottom-15 clearfix"><div class="pull-left m-right-10"  style="height: 50px; width:50px; border-radius:50px; overflow:hidden; border: 1px solid #999999;"><img class="user_avatar img-responsive img-rounded" src="'.base_url().'/uploads/users/'.$focus_pm_pic[$pm_id].'"" /></div>';
			echo '<div class="" id=""><p><strong>'.$focus_pms[$pm_id].'</strong><span class="pull-right"><span class="label pull-right m-bottom-3 m-top-3 small_orange_fixed"><i class="fa fa-usd"></i> '.number_format($set_invoiced_amount[$pm_id]).'</span> <br /> <span class="label pull-right m-bottom-3 small_green_fixed"><i class="fa fa-exclamation-triangle"></i> '.number_format($wip_pm_total[$pm_id]).'</span></span></p>';
			echo '<p><i class="fa fa-usd"></i> '.number_format($sales).'</p>';

			echo '<div class="progress no-m m-top-3 clearfix tooltip-enabled" title="" data-original-title="'.$status_forecast.'% - $'.number_format($sales).' / $'.number_format($total_forecast).'   " style="height: 7px;">
			<div class="progress-bar progress-bar-danger" style="width:'.$status_forecast.'%; background:red;"></div></div></div></div>';

			echo "<div class='clearfix'></div>";
			$forecast_focus_total = $forecast_focus_total + $total_forecast;
		}

		echo "</div>";

 
		$total_wip = ($total_wip <= 1 ? 1 : $total_wip);
		$forecast_focus_total = ($forecast_focus_total <= 1 ? 1 : $comp_forecast['total']);

		$status_forecast = round(100/($forecast_focus_total/ ($total_wip + $total_invoiced) ));

		echo '<div class="clearfix" style="padding-top: 6px;    border-top: 1px solid #eee;"><i class="fa fa-briefcase" style="font-size: 42px;float: left;margin-left: 7px;margin-right: 10px;"></i>';
		echo '<div class="" id=""><p><strong>Overall Focus</strong><span class="pull-right"><span class="label pull-right m-bottom-3 m-top-3 small_orange_fixed"><i class="fa fa-usd"></i> '.number_format($grand_total_sales_cmp).'</span> <br /> <span class="label pull-right m-bottom-3 small_green_fixed"><i class="fa fa-exclamation-triangle"></i> '.number_format($total_wip).'</span></span></p>';
		echo '<p><i class="fa fa-usd"></i> '.number_format( ($total_wip + $total_invoiced) ).' <strong class="pull-right m-right-10"></strong></p> </p>';

		echo '<div class="progress no-m m-top-3 clearfix tooltip-enabled" title="" data-original-title="'.$status_forecast.'% - $'.number_format( ($total_wip + $total_invoiced) ).' / $'.number_format($forecast_focus_total).'   " style="height: 7px;">
		<div class="progress-bar progress-bar-danger" style="width:'.$status_forecast.'%; background:red;"></div></div></div></div>';
		echo "<div class='clearfix'></div>";
		return $status_forecast.'_'.number_format( ($total_wip + $total_invoiced) );

	}





	public function pm_estimates_widget(){
		$year = date("Y");
		$current_date = date("d/m/Y");
		$current_start_year = '01/01/'.$year;
		$total_string = '';
		$is_restricted = 0;

		$admin_defaults = $this->admin_m->fetch_admin_defaults(1);
		foreach ($admin_defaults->result() as $row){
			$unaccepted_date_categories = $row->unaccepted_date_categories;
			$unaccepted_no_days = $row->unaccepted_no_days;
		}


		$pm_data = $this->dashboard_m->fetch_project_pm_nomore();
		$pm_q = array_shift($pm_data->result_array());
		$not_pm_arr = explode(',',$pm_q['user_id'] );


		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();

		$pm_split = array();

		foreach ($project_manager_list as $pm ) {
			$pm_split[$pm->user_id] = 0;
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

		foreach ($projects as $un_accepted){

			if( !in_array($un_accepted->project_manager_id, $not_pm_arr) ){

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
					if($un_accepted->install_time_hrs > 0 || $un_accepted->work_estimated_total > 0.00 || $un_accepted->variation_total > 0.00 ){
						$amnt =  $un_accepted->project_total + $un_accepted->variation_total;
						$project_cost[$un_accepted->focus_company_id] =  $project_cost[$un_accepted->focus_company_id] + $amnt;
					}else{
						$amnt = $un_accepted->budget_estimate_total;
						$project_cost[$un_accepted->focus_company_id] =  $project_cost[$un_accepted->focus_company_id] + $amnt; 
					}

					if( isset($unaccepted_amount[$un_accepted->project_estiamator_id])) {
						$unaccepted_amount[$un_accepted->project_estiamator_id] = $unaccepted_amount[$un_accepted->project_estiamator_id] + $amnt;
					}


					if( isset($pm_split[$un_accepted->project_manager_id])) {
						$pm_split[$un_accepted->project_manager_id] = $pm_split[$un_accepted->project_manager_id] + $amnt;
					}



				}
			}
		}

		//var_dump($project_cost);

		foreach ($focus_arr as $comp_id => $value ){
			$display_total_cmp = $project_cost[$comp_id];
			if($display_total_cmp > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cmp,2).'</span></div>';
			}
		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';





		foreach ($estimator_list as $est ) {
			$display_total_cmp = $unaccepted_amount[$est->project_estiamator_id];
			$pm_name = $est->user_first_name;
			if($display_total_cmp > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name.'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cmp,2).'</span></div>';
			}
		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';


		foreach ($project_manager_list as $pm ) {
			$display_total_cmp = $pm_split[$pm->user_id];
			$pm_name = $pm->user_first_name;
			if($display_total_cmp > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name.'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cmp,2).'</span></div>';
			}
		}





 
		$display_total = array_sum($project_cost); 
		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($display_total,2).'</strong></p>';
	}



	public function focus_projects_count_widget(){
		$current_date = date("d/m/Y");
		$year = date("Y");
		$next_year_date = '01/01/'.($year+1);
		$current_start_year = '01/01/'.$year;
		$last_start_year = '01/01/'.($year-1);

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		$focus_arr = array();

		$focus_invoiced = array();
		$focus_invoiced_old = array();

		$focus_comp_wip_count = array();

		foreach ($focus_company as $company) {
			$focus_arr[$company->company_id] = $company->company_name;
			$focus_comp_wip_count[$company->company_id] = 0;

			$invoiced = 0;
			$invoiced_old = 0;

			$projects_qa = $this->dashboard_m->get_wip_invoiced_projects($current_start_year, $next_year_date, $company->company_id);
			$projects_ra = $projects_qa->result_array();

			foreach ($projects_ra as $result) {
				if($this->invoice->if_invoiced_all($result['project_id'])  && $this->invoice->if_has_invoice($result['project_id']) > 0 ){
					$invoiced++;
				}
			}

			$projects_qb = $this->dashboard_m->get_wip_invoiced_projects($last_start_year, $current_start_year, $company->company_id);
			$projects_rb = $projects_qb->result_array();

			foreach ($projects_rb as $result) {
				if($this->invoice->if_invoiced_all($result['project_id'])  && $this->invoice->if_has_invoice($result['project_id']) > 0 ){
					$invoiced_old++;
				}
			}

			$focus_invoiced[$company->company_id] = $invoiced;
			$focus_invoiced_old[$company->company_id] = $invoiced_old;
		}

		$display_inv = array_sum($focus_invoiced);

		$total_string_wip = '';
		$total_string_inv = '';

		$total_string_wip .= 'WIP Count'; 
		$total_string_inv .= 'Invoiced Count';

		foreach ($focus_arr as $comp_id => $value ){
			if($focus_invoiced[$comp_id] > 0){
				$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-7\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-5\'>'.number_format($focus_invoiced[$comp_id]).'</span></div>';
			}
		}

		$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; Last Year</div>';
/*
		$proj_t = $this->wip_m->display_all_wip_projects();
		foreach ($proj_t->result_array() as $row){

			if($this->invoice->if_invoiced_all($row['project_id'])  && $this->invoice->if_has_invoice($row['project_id']) > 0 ){

			}else{
				$comp_id = $row['focus_company_id'];
				$focus_comp_wip_count[$comp_id]++;
			}
		}
*/

		$q_maps = $this->dashboard_m->get_map_projects($current_start_year,$current_date);

		//$proj_t = $this->wip_m->display_all_wip_projects();
		foreach ($q_maps->result_array() as $row){
			$comp_id = $row['focus_company_id'];
			$focus_comp_wip_count[$comp_id]++;
		}




		foreach ($focus_arr as $comp_id => $value ){
			if($focus_comp_wip_count[$comp_id] > 0){
				$total_string_wip .= '<div class=\'row\'><span class=\'col-xs-7\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-5\'>'.number_format($focus_comp_wip_count[$comp_id]).'</span></div>';
			}

			if($focus_invoiced[$comp_id] > 0){
				$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-7\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-5\'>'.number_format($focus_invoiced_old[$comp_id]).'</span></div>';
			}
		}

		$display_wip = array_sum($focus_comp_wip_count);


		echo '<div id="" class="clearfix row">				
		<strong class="text-center col-xs-4"><p class="h5x value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string_inv.'"><i class="fa fa-list-alt"></i> &nbsp;'.$display_inv.'</p></strong>
		<strong class="text-center col-xs-4"><p class="h5x value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string_wip.'"><i class="fa fa-tasks"></i> &nbsp;'.$display_wip.'</p></strong>
		<strong class="text-center col-xs-4"></strong></div>';


	}

	public function focus_get_map_locations_pm(){
		$pm_type = $this->pm_type();
		$user_id = $this->session->userdata('user_id');
		$fetch_user = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($fetch_user->result_array());

		if($pm_type == 1){ // for director/pm
			$direct_company = explode(',',$user_details['direct_company'] );
		}

		if($pm_type == 2){ // for pm only
			$direct_company = explode(',',$user_details['user_focus_company_id'] );
		}


		$current_date = date("d/m/Y");
		$year = date("Y");
		$next_year_date = '01/01/'.($year+1);
		$current_start_year = '01/01/'.$year;
		$last_start_year = '01/01/'.($year-1);

		$q_maps = $this->dashboard_m->get_map_projects($current_start_year,$current_date,$user_id);
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
		$year = date("Y");
		$current_date = date("d/m/Y");
		$current_start_year = '01/01/2014';
		$set_cpo = array();

		$focus_arr = array();
		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		foreach ($focus_company as $company){
			$focus_arr[$company->company_id] = $company->company_name;
			$set_cpo[$company->company_id] = 0;
		}

		$po_list_ordered = $this->purchase_order_m->get_po_list_order_by_project($current_start_year,$current_date);

		foreach ($po_list_ordered->result_array() as $row){
			$work_id = $row['works_id'];

			$po_tot_inv_q = $this->purchase_order_m->get_po_total_paid($work_id);
			$invoiced = 0;
			foreach ($po_tot_inv_q->result_array() as $po_tot_row){
				$invoiced = $po_tot_row['total_paid'];
			}

			$out_standing = $row['price'] - $invoiced;

			$comp_id = $row['focus_company_id'];
			$set_cpo[$comp_id] = $set_cpo[$comp_id] + $out_standing;
		}


		$total_string = '';

		foreach ($focus_arr as $comp_id => $value ){
			$display_total_cpo = $set_cpo[$comp_id];
			if($display_total_cpo > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cpo,2).'</span></div>';
			}
		}

		$display_total = array_sum($set_cpo);
		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($display_total,2).'</strong></p>';


	}


	public function focus_top_ten_clients_pm($pm_data_id = '', $year_set=''){
		$user_id = ($pm_data_id == '' ? $this->session->userdata('user_id') : $pm_data_id);

		$fetch_user = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($fetch_user->result_array());
		$comp_q = '';
		$comp_q .= 'AND (';
		$limit = 0;
 
		if($pm_data_id != ''){
			if($user_details['user_role_id'] == 3 && $user_details['user_department_id'] == 1):
				$pm_type = 1;
			endif; //for directors 

			if($user_details['user_role_id'] == 3 && $user_details['user_department_id'] == 4): //for PM 
				$pm_type = 2;
			endif; //for PM 
		}else{
			$pm_type = $this->pm_type();
		}

		if($pm_type == 1){ // for director/pm
			$direct_company = explode(',',$user_details['direct_company'] );
			$size = count($direct_company );

			foreach ($direct_company as $key => $value) {
				$comp_q .= '`project`.`focus_company_id` = '.$value.'';
				$limit++;
				if($size != $limit){
					$comp_q .= ' OR ';
				}
			}
		}

		if($pm_type == 2){ // for pm only
			$comp_q .= ' `project`.`project_manager_id` = '.$user_id.'';
			//$direct_company = explode(',',$user_details['user_focus_company_id'] );
		}

		$comp_q .= ')';



if($year_set != ''){
	$current_date = date("d/m/").''.$year_set;
	$year = $year_set;
	$last_year = $year_set -1;
}else{
	$current_date = date("d/m/Y");
	$year = date("Y");
	$last_year = intval(date("Y")) - 1;
}


  
		$next_year_date = '01/01/'.($year+1);
		$current_start_year = '01/01/'.$year;
		$last_start_year = '01/01/'.($year-1);

		$q_clients = $this->dashboard_m->get_top_ten_clients($current_start_year, $current_date,'','',$comp_q);
		$client_details  = $q_clients->result();
		$list_total = 0;

		foreach ($client_details as $company) {
			$q_vr_c_t = $this->dashboard_m->client_vr_value($current_start_year,$current_date,$company->client_id,$comp_q);
			$vr_val_t = array_shift($q_vr_c_t->result_array());
			$list_total = $list_total + $company->grand_total + $vr_val_t['total_variation'];
		}
 
		$this_month = date("m");
		$this_day = date("d");
 
		$date_a_last = "01/01/$last_year";
		$date_b_last = "$this_day/$this_month/$last_year";

$comp_total = array();


		foreach ($client_details as $company) {
			$q_vr_c = $this->dashboard_m->client_vr_value($current_start_year,$current_date,$company->client_id,$comp_q);
			$vr_val = array_shift($q_vr_c->result_array());
			$cost_gtotl_amnt = round($company->grand_total+ $vr_val['total_variation']);
			$comp_total[$company->company_id] = $cost_gtotl_amnt;
			$comp_name[$company->company_id] = $company->company_name;

		}

		arsort($comp_total);

		foreach ($comp_total as $raw_id => $compute_amount) {
			echo '<div class="col-sm-8 col-md-8"><i class="fa fa-chevron-circle-right"></i>  &nbsp; ';
			$percent = round(100/($list_total/$compute_amount),1);
			$company_name = $comp_name[$raw_id];
 
			
			if($pm_data_id != ''){

				if(strlen($company_name) > 50){
					echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$company_name.'">'.substr($company_name,0,50).'...</span>';
				}else{
					echo $company_name;
				}

			}else{
				if(strlen($company_name) > 40){
					echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$company_name.'">'.substr($company_name,0,40).'...</span>';
				}else{
					echo $company_name;
				}
			}


			$q_vr_c_u = $this->dashboard_m->client_vr_value($date_a_last,$date_b_last,$raw_id);
			$vr_val_u = array_shift($q_vr_c_u->result_array());

			$last_year_q = $this->dashboard_m->get_top_ten_clients($date_a_last, $date_b_last,$raw_id);
			$last_year_sale = array_shift($last_year_q->result_array());
			$lst_year_total = $last_year_sale['grand_total'] + $vr_val_u['total_variation'];
			echo ' </div><div class="col-md-1 col-sm-4"><strong>'.number_format($percent,1).'%</strong></div>  <div class="col-md-3 col-sm-4 tooltip-test" title="" data-placement="left" data-original-title="Last Year : $ '.number_format($lst_year_total).'"><i class="fa fa-usd"></i> '.number_format($compute_amount).'</div><div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';
		}
	}










 public function focus_clients_report($pmid,$year_set,$alternator = ''){
 	$current_year_date = "01/01/$year_set";
	$next_year = $year_set+1;
	$next_year_date = "01/01/$next_year";

	$counter = 0;

//	echo "$current_year_date,$next_year_date,$pmid <br />";

 	$q_clients = $this->dashboard_m->get_top_comp_lists_name($current_year_date,$next_year_date,$pmid);
 	$client_details  = $q_clients->result();
 	$company_list_arr = array();
 	$company_cost_arr = array();

 	foreach ($client_details as $company){
 		$company_list_arr[$company->company_id] = $company->company_name;
// 		echo $company->company_name."<br />";
 		$company_cost_arr[$company->company_id] = 0;
 	}

 	$q_get_sales = $this->dashboard_m->get_sales($current_year_date,$next_year_date,$pmid);
 	$pm_get_sales = $q_get_sales->result();

 	foreach ($pm_get_sales as $pm_sales ){
 		$sales_total = 0;
 		if($pm_sales->label == 'VR'){
 			$sales_total = $pm_sales->variation_total;
 		}else{
 			$sales_total = $pm_sales->project_total*($pm_sales->progress_percent/100);
 		}
 		$company_cost_arr[$pm_sales->company_id] = $company_cost_arr[$pm_sales->company_id] + $sales_total;
 	}


 	arsort($company_cost_arr);

 	foreach ($company_cost_arr as $key => $value) {
 		if($counter > 19){
 			unset($company_cost_arr[$key]);
 		}
 		$counter++;
 	}


 	$counter = 0;
 	$total_sale = array_sum($company_cost_arr);


 	if($alternator != ''){
 		foreach ($company_cost_arr as $key => $value) {
 			$percent = round(100/($total_sale/$value),1);
 			echo "['". str_replace("'","&apos;",$company_list_arr[$key])."', ".$value."],";
 		}

 	}else{
 		foreach ($company_cost_arr as $key => $value) {
 			$percent = round(100/($total_sale/$value),1);
 			echo '<div class="col-sm-8 col-md-8"><i class="fa fa-chevron-circle-right"></i>  &nbsp; ';
 			$company_name = $company_list_arr[$key];

 			if(strlen($company_name) > 45){
 				echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$company_name.'">'.substr($company_name,0,45).'...</span>';
 			}else{
 				echo $company_name;
 			}

 			echo '</div><div class="col-md-1 col-sm-4"><strong>'.$percent.'%</strong></div>
 			<div class="col-md-3 col-sm-4"><i class="fa fa-usd"></i><strong>$ '.number_format($value).'</strong></div>
 			<div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';

 		}
 	}
 }




	public function focus_top_ten_clients_pm_donut($pm_data_id = '',$year_set=''){
		$user_id = ($pm_data_id == '' ? $this->session->userdata('user_id') : $pm_data_id);

		$fetch_user = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($fetch_user->result_array());
		$comp_q = '';
		$comp_q .= 'AND (';
		$limit = 0;
 
		if($pm_data_id != ''){
			if($user_details['user_role_id'] == 3 && $user_details['user_department_id'] == 1):
				$pm_type = 1;
			endif; //for directors 

			if($user_details['user_role_id'] == 3 && $user_details['user_department_id'] == 4): //for PM 
				$pm_type = 2;
			endif; //for PM 
		}else{
			$pm_type = $this->pm_type();
		}

		if($pm_type == 1){ // for director/pm
			$direct_company = explode(',',$user_details['direct_company'] );
			$size = count($direct_company );

			foreach ($direct_company as $key => $value) {
				$comp_q .= '`project`.`focus_company_id` = '.$value.'';
				$limit++;
				if($size != $limit){
					$comp_q .= ' OR ';
				}
			}
		}

		if($pm_type == 2){ // for pm only
			$comp_q .= ' `project`.`project_manager_id` = '.$user_id.'';
			//$direct_company = explode(',',$user_details['user_focus_company_id'] );
		}

		$comp_q .= ')';

		$current_date = date("d/m/Y");
		$year = date("Y");

		if($year_set != ''){
			$current_date = date("d/m/").''.$year_set;
			$year = $year_set;
			$last_year = $year_set -1;
		}else{
			$current_date = date("d/m/Y");
			$year = date("Y");
			$last_year = intval(date("Y")) - 1;
		}


		$next_year_date = '01/01/'.($year+1);
		$current_start_year = '01/01/'.$year;
		$last_start_year = '01/01/'.($year-1);

		$q_clients = $this->dashboard_m->get_top_ten_clients($current_start_year, $current_date,'','',$comp_q);
		$client_details  = $q_clients->result();
		$list_total = 0;

		foreach ($client_details as $company) {
			$q_vr_c_t = $this->dashboard_m->client_vr_value($current_start_year,$current_date,$company->client_id,$comp_q);
			$vr_val_t = array_shift($q_vr_c_t->result_array());
			$list_total = $list_total + $company->grand_total + $vr_val_t['total_variation'];
		}

		$this_month = date("m");
		$this_day = date("d");
 
		$date_a_last = "01/01/$last_year";
		$date_b_last = "$this_day/$this_month/$last_year";

		foreach ($client_details as $company) {
			//echo '<div class="col-sm-8 col-md-8"><i class="fa fa-chevron-circle-right"></i>  &nbsp; ';

			$q_vr_c = $this->dashboard_m->client_vr_value($current_start_year,$current_date,$company->client_id,$comp_q);
			$vr_val = array_shift($q_vr_c->result_array());
			$comp_name = $company->company_name;
/*
			if(strlen($comp_name) > 40){
				echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$comp_name.'">'.substr($comp_name,0,40).'...</span>';
			}else{
				
			}

*/
		//	$percent = round(100/($list_total/($company->grand_total+$vr_val['total_variation'])   ));
		//	echo "['wwww', $percent],";

			$cost = $company->grand_total+$vr_val['total_variation'];


			echo "['". str_replace("'","&apos;",$comp_name)."', ".$cost."],";

			//['Flight Centre Ltd', 2792461.453315]

/*
			$q_vr_c_u = $this->dashboard_m->client_vr_value($date_a_last,$date_b_last,$company->client_id,$comp_q);
			$vr_val_u = array_shift($q_vr_c_u->result_array());

			$last_year_q = $this->dashboard_m->get_top_ten_clients($date_a_last, $date_b_last,$company->company_id);
			$last_year_sale = array_shift($last_year_q->result_array());
			$lst_year_total = $last_year_sale['grand_total'] + $vr_val_u['total_variation'];
			echo ' </div><div class="col-md-1 col-sm-4"><strong>'.$percent.'%</strong></div>  <div class="col-md-3 col-sm-4 tooltip-test" title="" data-placement="left" data-original-title="Last Year : $ '.number_format($lst_year_total).'"><i class="fa fa-usd"></i> '.number_format($company->grand_total+ $vr_val['total_variation']).'</div><div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';
		
*/

		}
	}


































	public function focus_top_ten_clients(){
		$current_date = date("d/m/Y");
		$year = date("Y");
		$next_year_date = '01/01/'.($year+1);
		$current_start_year = '01/01/'.$year;
		$last_start_year = '01/01/'.($year-1);

		$q_clients = $this->dashboard_m->get_top_ten_clients($current_start_year, $current_date);
		$client_details  = $q_clients->result();
		
		$list_total = 0;

		foreach ($client_details as $company){
			$q_vr_c_t = $this->dashboard_m->client_vr_value($current_start_year,$current_date,$company->client_id);
			$vr_val_t = array_shift($q_vr_c_t->result_array());
			$list_total = $list_total + round($company->grand_total + $vr_val_t['total_variation']);
		}

		$last_year = intval(date("Y")) - 1;
		$this_month = date("m");
		$this_day = date("d");
		$date_a_last = "01/01/$last_year";
		$date_b_last = "$this_day/$this_month/$last_year";
		$comp_total = array();
		$comp_name = array();

		foreach ($client_details as $company) {
			$q_vr_c = $this->dashboard_m->client_vr_value($current_start_year,$current_date,$company->client_id);
			$vr_val = array_shift($q_vr_c->result_array());
			$cost_gtotl_amnt = round($company->grand_total+ $vr_val['total_variation']);
			$comp_total[$company->company_id] = $cost_gtotl_amnt;
			$comp_name[$company->company_id] = $company->company_name;

		}

		arsort($comp_total);

		foreach ($comp_total as $raw_id => $compute_amount) {
			echo '<div class="col-sm-8 col-md-8"><i class="fa fa-chevron-circle-right"></i>  &nbsp; ';
			$percent = round(100/($list_total/$compute_amount),1);
			$company_name = $comp_name[$raw_id];
 
			if(strlen($company_name) > 40){
				echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$company_name.'">'.substr($company_name,0,40).'...</span>';
			}else{
				echo $company_name;
			}

			$q_vr_c_u = $this->dashboard_m->client_vr_value($date_a_last,$date_b_last,$raw_id);
			$vr_val_u = array_shift($q_vr_c_u->result_array());

			$last_year_q = $this->dashboard_m->get_top_ten_clients($date_a_last, $date_b_last,$raw_id);
			$last_year_sale = array_shift($last_year_q->result_array());
			$lst_year_total = $last_year_sale['grand_total'] + $vr_val_u['total_variation'];
			echo ' </div><div class="col-md-1 col-sm-4"><strong>'.number_format($percent,1).'%</strong></div>  <div class="col-md-3 col-sm-4 tooltip-test" title="" data-placement="left" data-original-title="Last Year : $ '.number_format($lst_year_total).'"><i class="fa fa-usd"></i> '.number_format($compute_amount).'</div><div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';
		}
	}


//get_company_sales($type,$date_a,$date_b,$cmp_id='')



	public function focus_top_ten_con_sup_pm($type,$pm_data_id = ''){
		$user_id = ($pm_data_id == '' ? $this->session->userdata('user_id') : $pm_data_id);

		$direct_company = '';
		$fetch_user = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($fetch_user->result_array());



		if($pm_data_id != ''){
			if($user_details['user_role_id'] == 3 && $user_details['user_department_id'] == 1):
				$pm_type = 1;
			endif; //for directors 

			if($user_details['user_role_id'] == 3 && $user_details['user_department_id'] == 4): //for PM 
				$pm_type = 2;
			endif; //for PM 
		}else{
			$pm_type = $this->pm_type();
		}





		if($pm_type == 1){ // for director/pm
			$direct_company = explode(',',$user_details['direct_company'] );
		}

		if($pm_type == 2){ // for pm only
			$direct_company = explode(',',$user_details['user_focus_company_id'] );
		}


		$size = count($direct_company);
		$limit = 0;

		$comp_q = '';

		$comp_q .= 'AND (';
		foreach ($direct_company as $key => $value) {
			$comp_q .= '`project`.`focus_company_id` = '.$value.'';
			$limit++;

			if($size != $limit){
				$comp_q .= ' OR ';
			}


		}
		$comp_q .= ')';


	//if( in_array($project['focus_company_id'], $direct_company) ){


		$current_date = date("d/m/Y");
		$year = date("Y");

		$last_year = intval(date("Y")) - 1;

		$base_year = '01/01/'.$year;

		$next_year_date = '01/01/'.$last_year;
		$current_start_year = date("d/m/Y");
		$last_start_year = '01/01/'.$last_year;

		$q_companies = $this->dashboard_m->get_company_sales($type,$base_year,$current_start_year,'',$comp_q);
		$company_details  = $q_companies->result();
		$counter = 0;


		$list_total = 0;

		foreach ($company_details as $company) {
			$list_total = $list_total + $company->total_price;
		}

		foreach ($company_details as $company) {
			$counter ++;
			$total = $company->total_price;
			$percent = round(100/($list_total/$company->total_price));

			$q_clients_overall = $this->dashboard_m->get_company_sales_overall($company->company_id);
			$overall_cost = array_shift($q_clients_overall->result_array());
			$grand_total = $overall_cost['total_price'];

			echo '<div class="col-sm-8 col-md-8"><i class="fa fa-chevron-circle-right"></i>  &nbsp; ';

			$comp_name = $company->company_name;
			if(strlen($comp_name) > 40){
				echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$comp_name.'">'.substr($comp_name,0,40).'...</span>';
			}else{
				echo $comp_name;
			}

			$cmp_id = $company->company_id;

			$last_year_q = $this->dashboard_m->get_company_sales('',$base_year,$current_start_year,$cmp_id,$comp_q);
			$last_year_sale = array_shift($last_year_q->result_array());
			$lst_year_total = $last_year_sale['total_price'];

			echo ' </div><div class="col-md-1 col-sm-4"><strong>'.$percent.'%</strong></div>  <div class="col-md-3 col-sm-4 tooltip-test" title="" data-placement="left" data-original-title="Last Year : $ '.number_format($lst_year_total).'"><i class="fa fa-usd"></i> '.number_format($company->total_price).'</div><div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';
		}
	}


	public function focus_top_ten_con_sup($type){
		$current_date = date("d/m/Y");
		$year = date("Y");

		$last_year = intval(date("Y")) - 1;
		$base_year = '01/01/'.$year;

		$next_year_date = '01/01/'.$last_year;
		$current_start_year = date("d/m/Y");
		$last_start_year = '01/01/'.$last_year;
		$last_year_current_date = date("d/m/").$last_year;

		$q_companies = $this->dashboard_m->get_company_sales($type,$base_year,$current_start_year);
		$company_details  = $q_companies->result();
		$counter = 0;

		$list_total = 0;

		foreach ($company_details as $company) {
			$list_total = $list_total + round($company->total_price);
		}

		foreach ($company_details as $company) {
			$counter ++;
			$total = $company->total_price;
			$percent = round(100/($list_total/ round($company->total_price) ),1);

			$q_clients_overall = $this->dashboard_m->get_company_sales_overall($company->company_id);
			$overall_cost = array_shift($q_clients_overall->result_array());
			$grand_total = $overall_cost['total_price'];

			echo '<div class="col-sm-8 col-md-8"><i class="fa fa-chevron-circle-right"></i>  &nbsp; ';

			$comp_name = $company->company_name;
			if(strlen($comp_name) > 40){
				echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$comp_name.'">'.substr($comp_name,0,40).'...</span>';
			}else{
				echo $comp_name;
			}

			$cmp_id = $company->company_id;

			$last_year_q = $this->dashboard_m->get_company_sales('',$last_start_year,$base_year);
			$last_year_sale = array_shift($last_year_q->result_array());
			$lst_year_total = $last_year_sale['total_price'];

			echo ' </div><div class="col-md-1 col-sm-4"><strong>'.number_format($percent,1).'%</strong></div>  <div class="col-md-3 col-sm-4 tooltip-test" title="" data-placement="left" data-original-title="Last Year : $ '.number_format($lst_year_total).'"><i class="fa fa-usd"></i> '.number_format($company->total_price).'</div><div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';
		}
	}


	public function focus_projects_by_type_widget_pm(){


		$pm_type = $this->pm_type();
		$user_id = $this->session->userdata('user_id');
		$fetch_user = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($fetch_user->result_array());

		if($pm_type == 1){ // for director/pm
			$direct_company = explode(',',$user_details['direct_company'] );
		}

		if($pm_type == 2){ // for pm only
			$direct_company = explode(',',$user_details['user_focus_company_id'] );
		}

		$current_date = date("d/m/Y");
		$year = date("Y");
		$current_start_year = '01/01/'.$year;


		$comp_id = 0;

		$focus_arr = array();
		$focus_prjs = array();
		$focus_costs = array();


		$focus_catgy = array();
		$focus_catgy_name = array();
		$focus_catgy_costs = array();

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();
		foreach ($focus_company as $company){
			$focus_arr[$company->company_id] = $company->company_name;
			$focus_prjs[$company->company_id] = 0;
			$focus_costs[$company->company_id] = 0;
		}

		$q_work = $this->dashboard_m->get_work_types();
		foreach ($q_work->result_array() as $job_category) {
			$cat_id =  strtolower(str_replace(" ","_",$job_category['job_category']));
			$focus_catgy[$cat_id] = 0;
			$focus_catgy_costs[$cat_id] = 0;
			$focus_catgy_name[$cat_id] = $job_category['job_category'];
		}

		$cost = 0;
		$variation = 0; 
		$grand_prj_total = 0;

		$q_projects = $this->dashboard_m->get_projects_by_work_type($current_start_year, $current_date);
		foreach ($q_projects->result_array() as $project){

			if( in_array($project['focus_company_id'], $direct_company) ){
				$cost = $cost + $project['project_total'];
				$variation = $variation + $project['variation_total'];
				$comp_id = $project['focus_company_id'];

				$focus_prjs[$comp_id]++;
				$cat_id =  strtolower(str_replace(" ","_",$project['job_category']));
				$focus_catgy[$cat_id]++;

				$focus_catgy_costs[$cat_id] = $focus_catgy_costs[$cat_id] + $project['project_total'] + $project['variation_total'];
				$focus_costs[$comp_id] = $focus_costs[$comp_id] + $project['project_total'] + $project['variation_total'];
				$grand_prj_total = $grand_prj_total +  $project['project_total'];
			}
		}

		$total_count_cat = array_sum($focus_catgy);

		foreach ($focus_catgy_name as $cat_id => $value){
			$cost = $focus_catgy_costs[$cat_id];
			$count = $focus_catgy[$cat_id];





			$grand_prj_total = ($grand_prj_total <= 1 ? 1 : $grand_prj_total);
			$cost = ($cost <= 1 ? 1 : $cost);


			if($cost>0){
				$percent = round(100/($grand_prj_total/$cost));
			}else{
				$percent = round(100/($grand_prj_total/1));
			}

			$grand_prj_total = ($grand_prj_total == 1 ? 0 : $grand_prj_total);
			$cost = ($cost == 1 ? 0 : $cost);

			if($grand_prj_total == 0 && $cost == 0){
				$percent = 0;
			}
			

			echo '<div id="" class="clearfix"><p><strong class="col-sm-2">'.$count.'</strong> <strong class="col-sm-4">'.$value.'</strong><strong class="col-sm-2">'.$percent.'% </strong><strong class="col-sm-4">$ '.number_format(round($cost)).'</strong></p></div>';
			echo '<div class="col-md-12"><hr class="block m-bottom-5 m-top-5"></div>';
		}
	}



	public function focus_projects_by_type_widget(){
		$current_date = date("d/m/Y");
		$year = date("Y");
		$current_start_year = '01/01/'.$year;


		$comp_id = 0;

		$focus_arr = array();
		$focus_prjs = array();
		$focus_costs = array();


		$focus_catgy = array();
		$focus_catgy_name = array();
		$focus_catgy_costs = array();

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();
		foreach ($focus_company as $company){
			$focus_arr[$company->company_id] = $company->company_name;
			$focus_prjs[$company->company_id] = 0;
			$focus_costs[$company->company_id] = 0;
		}

		$q_work = $this->dashboard_m->get_work_types();
		foreach ($q_work->result_array() as $job_category) {
			$cat_id =  strtolower(str_replace(" ","_",$job_category['job_category']));
			$focus_catgy[$cat_id] = 0;
			$focus_catgy_costs[$cat_id] = 0;
			$focus_catgy_name[$cat_id] = $job_category['job_category'];
		}

		$cost = 0;
		$variation = 0; 
		$grand_prj_total = 0;

		$q_projects = $this->dashboard_m->get_projects_by_work_type($current_start_year, $current_date);
		foreach ($q_projects->result_array() as $project){
			$cost = $cost + $project['project_total'];
			$variation = $variation + $project['variation_total'];
			$comp_id = $project['focus_company_id'];

			$focus_prjs[$comp_id]++;
			$cat_id =  strtolower(str_replace(" ","_",$project['job_category']));
			$focus_catgy[$cat_id]++;

			$focus_catgy_costs[$cat_id] = $focus_catgy_costs[$cat_id] + $project['project_total'] + $project['variation_total'];
			$focus_costs[$comp_id] = $focus_costs[$comp_id] + $project['project_total'] + $project['variation_total'];
			$grand_prj_total = $grand_prj_total +  $project['project_total'];
		}



		$total_count_cat = array_sum($focus_catgy);
		$total_cost_cat = array_sum($focus_catgy_costs);

	//	echo "$total_cost_cat**";

		foreach ($focus_catgy_name as $cat_id => $value){
			$cost = $focus_catgy_costs[$cat_id];
			$count = $focus_catgy[$cat_id];

			$total_cost_cat = ($total_cost_cat <= 1 ? 1 : $total_cost_cat);
			$cost = ($cost <= 1 ? 1 : $cost);

			$percent = round(100/($total_cost_cat/$cost),1);


			$total_cost_cat = ($total_cost_cat == 1 ? 0 : $total_cost_cat);
			$cost = ($cost == 1 ? 0 : $cost);

			
			if($total_cost_cat == 0 && $cost == 0){
				$percent = 0;
			}

			echo '<div id="" class="clearfix"><p><strong class="col-sm-2">'.$count.'</strong> <strong class="col-sm-4">'.$value.'</strong><strong class="col-sm-2">'.$percent.'% </strong><strong class="col-sm-4">$ '.number_format($cost).'</strong></p></div>';
			echo '<div class="col-md-12"><hr class="block m-bottom-5 m-top-5"></div>';
		}
	}






	public function focus_top_ten_clients_donut(){
		$current_date = date("d/m/Y");
		$year = date("Y");
		$next_year_date = '01/01/'.($year+1);
		$current_start_year = '01/01/'.$year;
		$last_start_year = '01/01/'.($year-1);

		$q_clients = $this->dashboard_m->get_top_ten_clients($current_start_year, $current_date);
		$client_details  = $q_clients->result();
		
		$list_total = 0;

		foreach ($client_details as $company) {
			$q_vr_c_t = $this->dashboard_m->client_vr_value($current_start_year,$current_date,$company->client_id);
			$vr_val_t = array_shift($q_vr_c_t->result_array());
			$list_total = $list_total + $company->grand_total + $vr_val_t['total_variation'];
		}

		$last_year = intval(date("Y")) - 1;
		$this_month = date("m");
		$this_day = date("d");
 
		$date_a_last = "01/01/$last_year";
		$date_b_last = "$this_day/$this_month/$last_year";

		foreach ($client_details as $company) {
			$percent = round(100/($list_total/$company->grand_total));

			$q_vr_c = $this->dashboard_m->client_vr_value($current_start_year,$current_date,$company->client_id);
			$vr_val = array_shift($q_vr_c->result_array());
			$comp_name = $company->company_name;
		
			$q_vr_c_u = $this->dashboard_m->client_vr_value($date_a_last,$date_b_last,$company->client_id);
			$vr_val_u = array_shift($q_vr_c_u->result_array());

			$last_year_q = $this->dashboard_m->get_top_ten_clients($date_a_last, $date_b_last,$company->company_id);
			$last_year_sale = array_shift($last_year_q->result_array());
			$lst_year_total = $last_year_sale['grand_total'] + $vr_val_u['total_variation'];

			$client_cost = $company->grand_total+ $vr_val['total_variation'];
			echo "['". str_replace("'","&apos;",$comp_name)."', ".$client_cost."],";
		}
	}



public function focus_top_ten_con_sup_donut($type){
		$current_date = date("d/m/Y");
		$year = date("Y");

		$last_year = intval(date("Y")) - 1;

		$base_year = '01/01/'.$year;

		$next_year_date = '01/01/'.$last_year;
		$current_start_year = date("d/m/Y");
		$last_start_year = '01/01/'.$last_year;

		$q_companies = $this->dashboard_m->get_company_sales($type,$base_year,$current_start_year);
		$company_details  = $q_companies->result();
		$counter = 0;


		$list_total = 0;

		foreach ($company_details as $company) {
			$list_total = $list_total + $company->total_price;
		}

		foreach ($company_details as $company) {
			$counter ++;
			$total = $company->total_price;
			$percent = round(100/($list_total/$company->total_price));

			$q_clients_overall = $this->dashboard_m->get_company_sales_overall($company->company_id);
			$overall_cost = array_shift($q_clients_overall->result_array());
			$grand_total = $overall_cost['total_price'];

		//	echo '<p> <div class="col-sm-8 col-md-8"><i class="fa fa-chevron-circle-right"></i>  &nbsp; ';

			$comp_name = $company->company_name;
		/*	if(strlen($comp_name) > 40){
				echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$comp_name.'">'.substr($comp_name,0,40).'...</span>';
			}else{
				echo $comp_name;
			}*/

			$cmp_id = $company->company_id;

			$last_year_q = $this->dashboard_m->get_company_sales('',$base_year,$current_start_year,$current_start_year);
			$last_year_sale = array_shift($last_year_q->result_array());
			$lst_year_total = $last_year_sale['total_price'];


echo "['". str_replace("'","&apos;",$comp_name)."', ".$company->total_price."],";

		}
	}



	public function focus_top_ten_clients_mn_donut(){
		$focus_company_maintenence_q = $this->dashboard_m->get_focus_companies_mntnc();
		$focus_company_maintenence = $focus_company_maintenence_q->result();

		$maintenacne_data = array(); 
		foreach ($focus_company_maintenence as $maintenance_data){
			$maintenacne_data[$maintenance_data->focus_company_id] = 0;
			$pm_id = $maintenance_data->project_manager_id;
		}


		$limit = 0;
		$comp_q = " AND `project`.`job_category` = 'Maintenance' ";


		$current_date = date("d/m/Y");
		$year = date("Y");
		$next_year_date = '01/01/'.($year+1);
		$current_start_year = '01/01/'.$year;
		$last_start_year = '01/01/'.($year-1);

		$q_clients = $this->dashboard_m->get_top_ten_clients($current_start_year, $current_date,'','',$comp_q);
		$client_details  = $q_clients->result();
		
		$list_total = 0;

		//if( in_array($project['focus_company_id'], $direct_company) ){

		foreach ($client_details as $company) {
			$q_vr_c_t = $this->dashboard_m->client_vr_value($current_start_year,$current_date,$company->client_id,$comp_q);
			$vr_val_t = array_shift($q_vr_c_t->result_array());
			$list_total = $list_total + $company->grand_total + $vr_val_t['total_variation'];
		}

		$last_year = intval(date("Y")) - 1;
		$this_month = date("m");
		$this_day = date("d");
 
		$date_a_last = "01/01/$last_year";
		$date_b_last = "$this_day/$this_month/$last_year";

		foreach ($client_details as $company) {
			$percent = round(100/($list_total/$company->grand_total));
			 
			$q_vr_c = $this->dashboard_m->client_vr_value($current_start_year,$current_date,$company->client_id,$comp_q);
			$vr_val = array_shift($q_vr_c->result_array());

			$comp_name = $company->company_name;


			$q_vr_c_u = $this->dashboard_m->client_vr_value($date_a_last,$date_b_last,$company->client_id,$comp_q);
			$vr_val_u = array_shift($q_vr_c_u->result_array());

			$last_year_q = $this->dashboard_m->get_top_ten_clients($date_a_last, $date_b_last,$company->company_id);
			$last_year_sale = array_shift($last_year_q->result_array());
			$lst_year_total = $last_year_sale['grand_total'] + $vr_val_u['total_variation'];

			$total_price = $company->grand_total+ $vr_val['total_variation'];


echo "['". str_replace("'","&apos;",$comp_name)."', ".$total_price."],";


}
	}




	public function focus_top_ten_con_sup_mn_donut($type){

		
		$focus_company_maintenence_q = $this->dashboard_m->get_focus_companies_mntnc();
		$focus_company_maintenence = $focus_company_maintenence_q->result();

		$maintenacne_data = array(); 
		foreach ($focus_company_maintenence as $maintenance_data){
			$maintenacne_data[$maintenance_data->focus_company_id] = 0;
			$pm_id = $maintenance_data->project_manager_id;
		}

		$limit = 0;

		$comp_q = " AND `project`.`job_category` = 'Maintenance' ";


	//if( in_array($project['focus_company_id'], $direct_company) ){


		$current_date = date("d/m/Y");
		$year = date("Y");

		$last_year = intval(date("Y")) - 1;

		$base_year = '01/01/'.$year;

		$next_year_date = '01/01/'.$last_year;
		$current_start_year = date("d/m/Y");
		$last_start_year = '01/01/'.$last_year;

		$q_companies = $this->dashboard_m->get_company_sales($type,$base_year,$current_start_year,'',$comp_q);
		$company_details  = $q_companies->result();
		$counter = 0;


		$list_total = 0;

		foreach ($company_details as $company) {
			$list_total = $list_total + $company->total_price;
		}

		foreach ($company_details as $company) {
			$counter ++;
			$total = $company->total_price;
			$percent = round(100/($list_total/$company->total_price));

			$q_clients_overall = $this->dashboard_m->get_company_sales_overall($company->company_id);
			$overall_cost = array_shift($q_clients_overall->result_array());
			$grand_total = $overall_cost['total_price'];

			 
			$comp_name = $company->company_name;
			 

			$cmp_id = $company->company_id;

			$last_year_q = $this->dashboard_m->get_company_sales('',$base_year,$current_start_year,$cmp_id,$comp_q);
			$last_year_sale = array_shift($last_year_q->result_array());
			$lst_year_total = $last_year_sale['total_price'];



			$total_price = $company->total_price;


echo "['". str_replace("'","&apos;",$comp_name)."', ".$total_price."],";




}
	}




	

	public function maintanance_average_pm(){

		$pm_type = $this->pm_type();
		$user_id = $this->session->userdata('user_id');
		$fetch_user = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($fetch_user->result_array());

		if($pm_type == 1){ // for director/pm
			$direct_company = explode(',',$user_details['direct_company'] );
		}

		if($pm_type == 2){ // for pm only
			$direct_company = explode(',',$user_details['user_focus_company_id'] );
		}

		$days_dif = array();
		$days_dif_old = array();

		$year = date("Y");
		$current_date = '01/01/'.intval($year+1);
		$current_start_year = '01/01/'.$year;


		$date_b = date("d/m/Y");

		$focus_arr = array();
		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		foreach ($focus_company as $company){
			$focus_arr[$company->company_id] = $company->company_name;
		}

		$q_maintenance = $this->dashboard_m->get_maitenance_dates($current_start_year,$date_b);
		$maintenance_details  = $q_maintenance->result();

		foreach ($maintenance_details as $maintenance) {
			if( in_array($maintenance->focus_company_id, $direct_company) ){
				array_push($days_dif, $maintenance->total_days);
			}
		}

		$size = count($days_dif);
		$average = array_sum($days_dif) / $size;

		arsort($days_dif,1);
		//	var_dump($days_dif);
		$long_day =  max($days_dif);
		$short_day_day =  min($days_dif);

		//over ride
		$short_day_day = 1;


		$last_year = intval(date("Y"))-1;
		$n_month = date("m");
		$n_day = date("d");

		$date_a_last = "01/01/$last_year"; 

		$date_b_last = "$n_day/$n_month/$last_year";
 

		$q_maintenance = $this->dashboard_m->get_maitenance_dates($date_a_last,$date_b_last);
		$maintenance_details  = $q_maintenance->result();

		foreach ($maintenance_details as $maintenance) {
			if( in_array($maintenance->focus_company_id, $direct_company) ){
				array_push($days_dif_old, $maintenance->total_days);
			}
		}

		$size_old = count($days_dif_old);
		if($size_old > 0){
			$average_old = array_sum($days_dif_old) / $size_old;
		}else{
			$average_old = ' No Data Yet';
		}
  

		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="Last Year : '.round($average_old,2).'">'.number_format($average,2).' Days';
		echo '<span class="pull-right">'.$short_day_day.'  <i class="fa fa-arrows-h" aria-hidden="true"></i> '.$long_day.'</span></p>';
	}







	public function maintanance_average(){
		$days_dif = array('');
		$days_dif_old = array('');

		$year = date("Y");
		$current_date = '01/01/'.intval($year+1);
		$current_start_year = '01/01/'.$year;


		$date_b = date("d/m/Y");

		$focus_arr = array();
		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		foreach ($focus_company as $company){
			$focus_arr[$company->company_id] = $company->company_name;
		}

		$q_maintenance = $this->dashboard_m->get_maitenance_dates($current_start_year,$date_b);
		$maintenance_details  = $q_maintenance->result();

		foreach ($maintenance_details as $maintenance) {
			array_push($days_dif, $maintenance->total_days);
		}

		$size = count($days_dif);
		$size = ($size <= 1 ? 1 : $size);
		$average = (array_sum($days_dif) / $size) + 0.0000;

		arsort($days_dif,1);
		//	var_dump($days_dif);
		$long_day =  max($days_dif);
		$short_day_day =  min($days_dif);

		
		/* //over ride */ $short_day_day = 1; // 1 actual
		/*  //over ride */	//$long_day = 0; // 


		$last_year = intval(date("Y"))-1;
		$n_month = date("m");
		$n_day = date("d");

		$date_a_last = "01/01/$last_year"; 
		$date_b_last = "$n_day/$n_month/$last_year";
 

		$q_maintenance = $this->dashboard_m->get_maitenance_dates($date_a_last,$date_b_last);
		$maintenance_details  = $q_maintenance->result();

		foreach ($maintenance_details as $maintenance) {
			array_push($days_dif_old, $maintenance->total_days);
		}

		$size_old = count($days_dif_old);
		if($size_old > 0){
			$average_old = ( array_sum($days_dif_old) / $size_old ) + 0.00000;
		}else{
			$average_old = ' No Data Yet';
		}

  

		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="Last Year : '.number_format($average_old,2).'">'.number_format($average,2).' Days';
		echo '<span class="pull-right">'.$short_day_day.'  <i class="fa fa-arrows-h" aria-hidden="true"></i> '.$long_day.'</span></p>';
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
/*
		$q_sales_focus_yearly = $this->dashboard_m->get_sales_focus_yearly($old_year);
		$sales_focus_yearly = array_shift($q_sales_focus_yearly->result_array());
		$sales_focus_yearly = $sales_focus_yearly['total_sales'];
*/












//---------------------------------------------

		$grand_total_sales_cmp = 0;
		 
//$old_year 


		$c_year = $old_year;
		$p_year = $c_year-1;	
		$date_a = "01/01/$c_year";
		$date_b = "31/12/$old_year";
		$total_wip = 0;


  
		$pm_data = $this->dashboard_m->fetch_project_pm_nomore();
		$pm_q = array_shift($pm_data->result_array());
		$not_pm_arr = explode(',',$pm_q['user_id'] );



		$project_manager = $this->user_model->fetch_user_by_role(3);
		$project_manager_list = $project_manager->result();
 
 
		foreach ($project_manager_list as $pm ) {
			$total_sales = 0;
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

			$wip_amount = $this->get_wip_value_permonth($date_a,$date_b,$pm->user_id,1);
			$total_wip = $total_wip + $wip_amount;


			$grand_total_sales_cmp = $grand_total_sales_cmp + $total_sales;
		}







//	$grand_total_sales_cmp


	//	var_dump($grand_total_sales_cmp);



//---------------------------------------------

 
 

// --------------------------------------------

















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

		$data['sales_focus_yearly'] = $grand_total_sales_cmp + $total_wip; //focus_total_sales
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


	public function management_report(){
	//	echo $this->uri->segment(3);
		//redirect('/dashboard', 'refresh');

		$report_arr = explode("_", $this->uri->segment(3));
		$report_year = $report_arr[0];
		$pm_id = $report_arr[1];


		$fetch_user = $this->user_model->fetch_user($pm_id);
		$data['user_details'] = array_shift($fetch_user->result_array());

		$data['report_year'] = $report_arr[0];
		$data['pm_id'] = $report_arr[1];


		$q_focus_pm_forecast = $this->dashboard_m->get_pm_forecast($report_year,$pm_id);
		$data['pm_forecast'] = array_shift($q_focus_pm_forecast->result_array());

	  
		$focus_data_forecast_p = $this->dashboard_m->get_focus_comp_forecast($report_year);
		$q_focus_data_forecast_p = $focus_data_forecast_p->result();
		$forecast_per_comp = array();
		

		foreach ($q_focus_data_forecast_p as $ffcp){
			$forecast_percent_val = $ffcp->total * ( $ffcp->forecast_percent / 100 );
			$forecast_per_comp[$ffcp->comp_id] = $forecast_percent_val;
		}

		$data['focus_comp_forecast'] = $forecast_per_comp;

		$pm_actual_sales_q = $this->dashboard_m->fetch_pm_sales_year($report_year,$pm_id);
		$data['pm_actual_sales'] = array_shift($pm_actual_sales_q->result_array());

		$pms_sales_last_year_q = $this->dashboard_m->fetch_pm_sales_old_year($report_year-1,$pm_id);
		$data['pm_last_year_sales'] = array_shift($pms_sales_last_year_q->result_array());

		$this->load->view('dashboard_report', $data);
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