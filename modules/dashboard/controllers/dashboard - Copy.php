<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jervy Zaballa */  /* NOTE: CI version used 2.1.2 */

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
		if($this->session->userdata('is_admin') == 1 || $user_role_id == 16 || $user_role_id == 3 || $user_role_id == 2 || $user_role_id == 7 || $user_role_id == 6 || $user_role_id == 8  || $this->session->userdata('user_id') == 6):
		else:		
			redirect('projects', 'refresh');
		endif;
// temporary restriction for dashboaed devf
   



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

	public function d_estimators_wip($es_id=''){
		//$this->dashboard('estimators')->estimators_wip($es_id='');

	//	echo "test";
	}

	function index(){
		$this->users->_check_user_access('dashboard',1);
		$data['assign_id'] = 0;
		$data['screen'] = 'Dashboard';
		$user_role_id = $this->session->userdata('user_role_id');
		//Grant acess to Operations Manager
		$data['pm_setter'] = '';

		if($this->session->userdata('is_admin') == 1 || $user_role_id == 16 || $this->session->userdata('user_id') == 9 || $this->session->userdata('user_id') == 6):

			$dash_type = $this->input->get('dash_view', TRUE);
			if( isset($dash_type) && $dash_type != ''){
				$dash_details = explode('-', $dash_type);
				$data['assign_id'] = $dash_details[0];

				$fetch_user = $this->user_model->fetch_user($dash_details[0]);
				$user_details = array_shift($fetch_user->result_array());
 				$data['pm_setter'] = $user_details['user_first_name'].' '.$user_details['user_last_name']; 
 				
 				if($dash_details[1] == 'pm'){
					$data['main_content'] = 'dashboard_pm';
 				}elseif($dash_details[1] == 'mn'){
					$data['main_content'] = 'dashboard_mn';
 				}elseif($dash_details[1] == 'es'){
					redirect('/dashboard/estimators?dash_view='.$dash_details[0].'-es');
 				}elseif($dash_details[1] == 'ad'){
						$data['main_content'] = 'dashboard_home';
				}else{
					$data['main_content'] = 'dashboard_home';
 				}

			}else{
				$data['main_content'] = 'dashboard_home';
			}

		elseif($user_role_id == 3):




			$dash_type = $this->input->get('dash_view', TRUE);
			if( isset($dash_type) && $dash_type != ''){
				if($this->session->userdata('user_id') == 15 ){
					$dash_details = explode('-', $dash_type);
					$data['assign_id'] = $dash_details[0];

					$fetch_user = $this->user_model->fetch_user($dash_details[0]);
					$user_details = array_shift($fetch_user->result_array());
					$data['pm_setter'] = $user_details['user_first_name'].' '.$user_details['user_last_name']; 

					if($dash_details[1] == 'pm'){
						$data['main_content'] = 'dashboard_pm';
					}elseif($dash_details[1] == 'mn'){
						$data['main_content'] = 'dashboard_mn';
					}elseif($dash_details[1] == 'ad'){
						$data['main_content'] = 'dashboard_home';
					}elseif($dash_details[1] == 'es'){
						redirect('/dashboard/estimators?dash_view='.$dash_details[0].'-es');
					}else{
						$data['main_content'] = 'dashboard_pm';
					}
				}else{
					$data['main_content'] = 'dashboard_pm';
				}

			}else{

				$data['main_content'] = 'dashboard_pm';
			}





		elseif($user_role_id == 2):
			$data['main_content'] = 'dashboard_pa';
		elseif($user_role_id == 6):
			$fetch_user= $this->user_model->list_user_short();
			$data['users'] = $fetch_user->result();
			$data['screen'] = 'User Availability';
			$data['main_content'] = 'users/availability';
		elseif($user_role_id == 7):
			$data['main_content'] = 'dashboard_mn';
		elseif($user_role_id == 8):
			redirect('/dashboard/estimators');
		else:		
			redirect('projects', 'refresh');
		endif;


	//	redirect('projects', 'refresh');

		$project_manager = $this->user_model->fetch_user_by_role(3); // ****--___--***
		
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




		$project_manager = $this->user_model->fetch_user_by_role(3); // ****--___--***
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

		$q_curr_maintenance_sales = $this->dashboard_m->fetch_pm_sales_year($this_year,29);
		$data['maintenance_current_sales'] = array_shift($q_curr_maintenance_sales->result_array());

		foreach ($q_focus_data_forecast_p as $ffcp){
			$forecast_percent_val = $ffcp->total * ( $ffcp->forecast_percent / 100 );
			$forecast_per_comp[$ffcp->comp_id] = $forecast_percent_val;
		}

		$data['focus_data_forecast_p'] = $forecast_per_comp;

		$this->load->view('page', $data);
	}



	function get_wip_personal($date_a,$date_b,$pm_id,$comp){
