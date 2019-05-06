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
/*
		$user_role_id = $this->session->userdata('user_role_id');
		if($this->session->userdata('is_admin') == 1 || $user_role_id == 16 || $user_role_id == 20 || $user_role_id == 3 || $user_role_id == 2 || $user_role_id == 7 || $user_role_id == 6 || $user_role_id == 8  || $this->session->userdata('user_id') == 6):
		else:		
			redirect('projects', 'refresh');
		endif;
*/
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
		$user_department_id = $this->session->userdata('user_department_id');
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
				}elseif($dash_details[1] == 'ch'){
					$data['main_content'] = 'dashboard_general_hammond';
				}elseif($dash_details[1] == 'jp'){
					$data['main_content'] = 'dashboard_joinery_procurement';
				}elseif($dash_details[1] == 'acnt'){
					$data['main_content'] = 'dashboard_accnt';
				}else{
					$data['main_content'] = 'dashboard_home';
 				}

			}else{
				$data['main_content'] = 'dashboard_home';
			}

		elseif($user_role_id == 3 || $user_role_id == 20):




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
					}elseif($dash_details[1] == 'ch'){
						$data['main_content'] = 'dashboard_general_hammond';
					}elseif($dash_details[1] == 'jp'){
						$data['main_content'] = 'dashboard_joinery_procurement';
					}elseif($dash_details[1] == 'acnt'){
						$data['main_content'] = 'dashboard_accnt';
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




		elseif($this->session->userdata('user_id') == 32):
			$fetch_user= $this->user_model->list_user_short();
			$data['users'] = $fetch_user->result();
			$data['screen'] = 'User Availability';
			$data['main_content'] = 'users/availability';
		elseif($user_role_id == 8):
			redirect('/dashboard/estimators');

		elseif($user_role_id == 2):
			$data['main_content'] = 'dashboard_pa';
		elseif($user_role_id == 7):
			$data['main_content'] = 'dashboard_mn';
		elseif($user_role_id == 9):
			$data['main_content'] = 'dashboard_joinery_procurement';
		elseif($user_department_id == 3):
			$data['main_content'] = 'dashboard_accnt';
		elseif( $this->session->userdata('user_id') == 72 ):
			$data['main_content'] = 'dashboard_general_hammond';
		elseif( $this->session->userdata('dashboard') == 1 ):
			$data['main_content'] = 'dashboard_general';
		else:		
			redirect('projects', 'refresh');
		endif;


	//	redirect('projects', 'refresh');
		$this_year = date("Y");

		$project_manager = $this->dashboard_m->fetch_pms_year($this_year); // ****--___--***
		
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




		$project_manager = $this->dashboard_m->fetch_pms_year($this_year); // ****--___--***
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
	//	$data['focus_indv_comp_outstanding'] = $this->dashboard_m->get_focus_outstanding($this_year,1); /// remove this !!!!!!!!!!!!!!!

	//	$inv_fcs_overall_sales = array();

/*
		foreach ($data['focus_indv_comp_outstanding']->result_array() as $indv_comp_outs){
			for($i=0; $i < 12 ; $i++){
				$counter = $i;
				$month_index = 'out_'.strtolower($months[$counter]);
				//echo $indv_comp_outs[$month_index].'<br />';

				$inv_fcs_overall_sales[$indv_comp_outs['company_name']][$month_index] = $indv_comp_outs[$month_index];
			}
		}
*/
/*
		foreach ($data['focus_indv_comp_sales']->result_array() as $indv_comp_sales){
			for($i=0; $i < 12 ; $i++){
				$counter = $i;
				$month_index = 'rev_'.strtolower($months[$counter]);
				//echo $indv_comp_sales[$month_index].'<br />';

				$inv_fcs_overall_sales[$indv_comp_sales['company_name']][$month_index] = $indv_comp_sales[$month_index];
			}
		}

*/


//		$data['inv_fcs_overall_sales'] = $inv_fcs_overall_sales;

		$data['focus_indv_comp_forecast'] = $this->dashboard_m->fetch_indv_comp_forecast($this_year);

		$rev_month = 'rev_'.strtolower(date('M'));
		$out_month = 'out_'.strtolower(date('M'));


		$this_year = date("Y");

		$data['pms_sales_c_year'] = $this->dashboard_m->fetch_pm_sales_year($this_year);

		$data['pms_sales_last_year'] = $this->dashboard_m->fetch_pm_sales_year($this_year-1);

		$data['pms_outstanding_c_year'] = $this->dashboard_m->fetch_pm_oustanding_year($this_year); // !!!!!!!!!!!!!!!!!!!!!!!!!!!! needs removing

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

		$data['page_title'] = 'Dashboard';

		$this->load->view('page', $data);
	}





	public function get_count_per_week($return_total = 0, $set_year = '', $set_emp_id = '' ){
 

		$q_leave_types = $this->user_model->fetch_leave_type();
		$leave_types = $q_leave_types->result();
		$this_year = date("Y");

		if(isset($set_year) && $set_year != ''){
			$this_year = $set_year;
		}
$custom_q = '';

$added_data = new StdClass();
$added_data->{"leave_type_id"} = '0';
$added_data->{"leave_type"} = 'Philippines Public Holiday';
$added_data->{"remarks"} = '';


		array_push($leave_types, $added_data);

 
 
		$leave_typess_arr = array('');

		$user_id_logger = '';



						$leave_typess_arr[] = array();


						if(isset($set_emp_id) && $set_emp_id != '' ){

							$list_user_short_q = $this->user_model->fetch_user($set_emp_id);
							$user_list = $list_user_short_q->result();
							$user_id_logger = $set_emp_id;

						}else{

							$list_user_short_q = $this->user_model->list_user_short();
							$user_list = $list_user_short_q->result();
						}


					
						if(isset($user_id_logger) && $user_id_logger != '' ){
							$set_emp_id = '';

							if($user_id_logger == 72){
								$custom_q = " AND `users`.`user_department_id` = '10' ";
							}

							if($user_id_logger == 16){
								$custom_q = " AND `users`.`user_focus_company_id` = '6' ";
							}

							if($user_id_logger == 15){
								$custom_q = " AND `users`.`user_focus_company_id` = '5' ";
							}

							if($user_id_logger == 72 || $user_id_logger == 16 || $user_id_logger == 15){
								$list_user_short_q = $this->user_model->list_user_short($custom_q);
								$user_list = $list_user_short_q->result();
							}

							if($user_id_logger == 21){
								$custom_q = " AND `users`.`user_department_id` = '3' ";
								$list_user_short_q = $this->user_model->list_user_short($custom_q);
								$user_list = $list_user_short_q->result();
							}

							if($return_total == 2){
								$list_user_short_q = $this->user_model->fetch_user($user_id_logger);
								$user_list = $list_user_short_q->result();

							}

							if($return_total == 3){

								if($user_id_logger == 72){
									$custom_q = " AND `users`.`user_department_id` = '10' ";
								}

								if($user_id_logger == 16){
									$custom_q = " AND `users`.`user_focus_company_id` = '6' ";
								}

								if($user_id_logger == 15){
									$custom_q = " AND `users`.`user_focus_company_id` = '5' ";
								}

								if($user_id_logger == 72 || $user_id_logger == 16 || $user_id_logger == 15){
									$list_user_short_q = $this->user_model->list_user_short($custom_q);
									$user_list = $list_user_short_q->result();
								}

								if($user_id_logger == 21){
									$custom_q = " AND `users`.`user_department_id` = '3' ";
									$list_user_short_q = $this->user_model->list_user_short($custom_q);
									$user_list = $list_user_short_q->result();
								}

							}


						}




						foreach ($user_list as $users_data){

							$user_name = $users_data->user_first_name.' '.$users_data->user_last_name;

							foreach ($leave_types as $leave_data) {

								$leave_type_id = $leave_data->leave_type_id;




if($return_total == 1){

		 	echo "['$user_name $leave_data->leave_type',";
}


					$total_days_away = 0;
					$days_numbr = 0;
			for($x=1;$x<=52;$x++){

				$year = $this_year;
				$week_no = $x;

				$date = new DateTime();
				$date->setISODate($year,$week_no);



				$dateParam =  $date->format('Y-m-d');
				$date_a =  $date->format('d/m/Y');


				$week = date('w', strtotime($dateParam));
				$date_mod = new DateTime($dateParam);

				$date_a_x = $date_mod->modify("-".$week." day")->format("d/m/Y");
				$date_b = $date_mod->modify("+5 day")->format("d/m/Y");

				$stamp_a = strtotime($date->format('Y-m-d') );
				$stamp_b = strtotime($date_mod->format('Y-m-d') );



	//	echo "<p> $date_a,$date_b,$users_data->primary_user_id,****$leave_type_id**$x**</p>";

					$get_weekly_leaves_q = $this->dashboard_m->get_weekly_leaves($date_a,$date_b,$users_data->primary_user_id,$leave_type_id );
				//	$leave_data = $get_weekly_leaves_q->result();

					$days_count = 'null';


 
if($get_weekly_leaves_q->num_rows() ){



					//foreach ($leave_data as $leave_numbers){

					foreach ($get_weekly_leaves_q->result() as $leave_numbers){
						
						if($leave_numbers->partial_day == 1){

							$days_numbr =  number_format( ($leave_numbers->total_days_away / 8) , 2 );

						}else{

							if($leave_numbers->total_days_away == 8){
								$days_numbr = 1;
							}else{

								$start_day_of_leave =   new DateTime (date("Y-m-d", $leave_numbers->start_day_of_leave) );
								$end_day_of_leave =   new DateTime (date("Y-m-d", $leave_numbers->end_day_of_leave) );

								$data_stamp_a =  new DateTime (date("Y-m-d", $stamp_a) );
								$data_stamp_b =  new DateTime (date("Y-m-d", $stamp_b) );
								$total_days_away = $leave_numbers->total_days_away;


								if($total_days_away > 80){
									



									if($stamp_b <=  $leave_numbers->start_day_of_leave ){
										$days_numbr = abs($start_day_of_leave->diff($data_stamp_b)->format("%a")) + 1;
										
									}elseif($stamp_a <=  $leave_numbers->end_day_of_leave ){
										$days_numbr = abs($end_day_of_leave->diff($data_stamp_a)->format("%a")) + 1;
										$total_days_away = 0;
										//$days_numbr = $days_numbr + 
									}else{
										$days_numbr = 5;
									}




								}else{
									if($stamp_a <=  $leave_numbers->start_day_of_leave && $stamp_b >=  $leave_numbers->end_day_of_leave ){
										$days_numbr = abs($start_day_of_leave->diff($end_day_of_leave)->format("%a"))+1;
									}


									elseif($stamp_a <=  $leave_numbers->start_day_of_leave){
										$days_numbr =  (abs( $stamp_b - $leave_numbers->start_day_of_leave) / 86400) + 1;
									}else{
										$days_numbr = (abs($leave_numbers->end_day_of_leave - $stamp_a ) / 86400) + 1;
									}



/*
									elseif($stamp_a <=  $leave_numbers->end_day_of_leave){

										$days_numbr = abs($end_day_of_leave->diff($data_stamp_a)->format("%a"))+50;
									//$days_numbr = $days_numbr + 
									}

									elseif($stamp_b >=  $leave_numbers->start_day_of_leave){
										$days_numbr = abs($start_day_of_leave->diff($data_stamp_b)->format("%a")) + 1;
									//$days_numbr = $days_numbr + 
									}*/


								}

						



							}

						}


						$days_count = $days_count + $days_numbr;
						$days_count = floatval($days_count);
					}

				}else{

					if($total_days_away > 80){
						$days_count = 5;
						$total_days_away = 0;
					//	echo "<p>TEST</p>";

					}

				}

					// if($total_days_away > 80){
					// 	$days_count = 5;
					// }

				if($x > 2){

					if($return_total == 1){
						echo $days_count.",";
					}
				} 







						$key_loop = $x-1;

						// if (   	isset($leave_typess_arr[$key_loop]) && array_key_exists($leave_type_id,$leave_typess_arr[$key_loop])) {
						
						 if(isset($leave_typess_arr[$key_loop][$leave_type_id])){

							$leave_typess_arr[$key_loop][$leave_type_id] = $leave_typess_arr[$key_loop][$leave_type_id] + floatval($days_count);
						}else{

							$leave_typess_arr[$key_loop][$leave_type_id] = floatval($days_count);
						}


					$days_count = 'null';


				}



				if($return_total == 1){

					echo "],";
				}
			}








		} 
	//	echo "<p>----------------</p>";

	//	$test_looper=1;

		$return_arr = array('');
		$iteam_leave_total = 0;
		$last_value_arr = '';


		foreach ($leave_types as $leave_data ) {



			$ounter_loop = 0;


			if($return_total == 1){
				echo "['Overall $leave_data->leave_type',";
			}

			foreach ($leave_typess_arr as $row => $value) {

		 	//var_dump($value);
				$ounter_loop++;

				//	echo "<p>".$leave_data->leave_type_id."</p>";

					if($return_total == 1){

						if($ounter_loop > 2){
							echo  ($value[$leave_data->leave_type_id] == 0 ? 'null,' : $value[$leave_data->leave_type_id]."," );
						}
					}

					if($return_total == 2 || $return_total == 3){
							//echo ($value[$leave_data->leave_type_id] == 0 ? 0 : $value[$leave_data->leave_type_id]."," );
							$iteam_leave_total = $iteam_leave_total +  ($value[$leave_data->leave_type_id] == 0 ? 0 : $value[$leave_data->leave_type_id] );
					}

					/*if($set_emp_id != ''){
							$iteam_leave_total = $iteam_leave_total +  ($value[$leave_data->leave_type_id] == 0 ? 0 : $value[$leave_data->leave_type_id] );
					}*/

		 	}
				


		 	if($return_total == 1){
		 		echo "],";
		 	}


		 	if($return_total == 2 || $return_total == 3){
//echo "<p> $leave_data->leave_type -  $iteam_leave_total</p>";

		 		$return_arr[$leave_data->leave_type_id] = $iteam_leave_total;
				$last_value_arr .= $leave_data->leave_type_id.'-'.$iteam_leave_total.'|';
				$iteam_leave_total = 0;
		 	}

		}

	


		if( isset($set_year) ){
			echo substr($last_value_arr, 0, -1);
		}

					if($return_total == 2 || $return_total == 3){
			return $return_arr;
		}

		


	}


	

	public function get_count_maint_per_week($this_year,$is_ave=0){

		$counter_loop = 0;

		$ave_year = 0;

		if($is_ave == 1){
			$counter_loop = date("Y") - $this_year; 
			$ave_year = $counter_loop;
			// $counter_loop--;
		}






//echo "***$counter_loop<br /><br />";



		$list_data = array(0);

		while($counter_loop >= 0){

//echo "<br />$counter_loop<br /><br />";

			for($x=1;$x<=52;$x++){
		//	echo "<br/>";



				$year = $this_year;
				$week_no = $x;

				$date = new DateTime();
				$date->setISODate($year,$week_no);
	//echo $date->format('d-M-Y');


				$dateParam =  $date->format('Y-m-d');
				$date_a =  $date->format('d/m/Y');


				$week = date('w', strtotime($dateParam));
				$date_mod = new DateTime($dateParam);

				$date_a_x = $date_mod->modify("-".$week." day")->format("d/m/Y");
				$date_b = $date_mod->modify("+5 day")->format("d/m/Y");


			//	$date_gg = $date_mod->modify("+6 day")->format("d/m/Y");


				$query = $this->dashboard_m->get_maintenance_counts($date_a,$date_b);
				$result_q = $query->result();

//echo "<p>$testD ----- $date_b  ******</p>";


				if(array_key_exists( $x-1, $list_data )){
					$list_data[$x-1] =  $list_data[$x-1] + $result_q[0]->num_projects;

				}else{
					$list_data[$x-1] =   $result_q[0]->num_projects ;

				}



			//	 echo "<p>$date_a ______ $date_b ____ ".$result_q[0]->num_projects." </p>";

				 //array_push($list_data, $result_q[0]->num_projects);


			}

//var_dump($list_data);


			$counter_loop--;
			$this_year++;
		}






			for($x=0;$x<52;$x++){

				if($is_ave == 1){

					$list_data[$x] =    ( ($list_data[$x]  / $ave_year )   ) /5 ;

				} else{


					$list_data[$x] =     ($list_data[$x]  )  / 5;

				}

		}

//var_dump($list_data);

		return implode(',', $list_data);


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

	public function po_joinery_list($return_val=0){



		$q_get_joinery_list = $this->dashboard_m->get_joinery_list();
		$get_joinery_list = $q_get_joinery_list->result();

		$val_amount = 0;

		if($return_val == 0){

		foreach ($get_joinery_list as $joinery){

 
	//		echo "<tr><td> -------- $joinery->project_id --------- $joinery->work_cpo_date ------------ $joinery->price</tr>"; //$joinery->company_name 

			// echo '  <div class="row"  style="border-bottom: 1px solid #ccc;    padding: 4px;">
			// <div class="col-sm-5"><strong><a href="'.base_url().'projects/view/'.$joinery->project_id.'" target="_blank">'.$joinery->project_id.' &nbsp; '.$joinery->project_name.'</a></strong></div>
			// <div class="col-sm-3"><span>'.$joinery->works_id.'  &nbsp;  '.$joinery->company_name.'</span></div>
			// 	<div class="col-sm-1">'.$joinery->goods_deliver_by_date.'</div>
			// <div class="col-sm-1">'.$joinery->work_cpo_date.'</div>
			// <div class="col-sm-2 text-right"><strong>$ '. number_format($joinery->price,2).'</strong></div>


			// </div>';



echo '<tr role="row">
<td><a href="'.base_url().'projects/view/'.$joinery->project_id.'" target="_blank">'.$joinery->project_id.' &nbsp; '.$joinery->project_name.'</a></strong></td>
<td class="">'.$joinery->works_id.'  &nbsp;  '.$joinery->company_name.'</td>
<td class="">'.$joinery->goods_deliver_by_date.'</td>
<td class="">'.$joinery->work_cpo_date.'</td>
<td class=""><strong>$ '. number_format($joinery->price,2).'</strong></td>

<td class="hide">'.$joinery->unix_goods_deliver_by_date.'</td>
<td class="hide">'.$joinery->unix_cpo_date.'</td></tr>';






		} 
	}else{

		foreach ($get_joinery_list as $joinery){

			$val_amount = $val_amount + $joinery->price;

 



	} 


	return number_format($val_amount,2);


	}



	}
 



	function list_projects_progress($pm_id='',$custom='',$is_joinery=0){

		//$this_year = date("d/m/Y");

		$this_year =  date("d/m/Y", strtotime("- 5 days"));

 

		if($is_joinery == 1){

 
/*
echo '<tr role="row">
<td><a href="'.base_url().'projects/view/'.$joinery->project_id.'" target="_blank">'.$joinery->project_id.' &nbsp; '.$joinery->project_name.'</a></strong></td>
<td class="">'.$joinery->works_id.'  &nbsp;  '.$joinery->company_name.'</td>
<td class="">'.$joinery->goods_deliver_by_date.'</td>
<td class="">'.$joinery->work_cpo_date.'</td>
<td class=""><strong>$ '. number_format($joinery->price,2).'</strong></td>

<td class="hide">'.$joinery->unix_goods_deliver_by_date.'</td>
<td class="hide">'.$joinery->unix_cpo_date.'</td></tr>';

*/



			$q_get_project_progress_list = $this->dashboard_m->get_joinery_list(1);



		}else{

			$q_get_project_progress_list = $this->dashboard_m->get_project_progress_list($this_year,$pm_id,$custom);
			
		}


		$num_result = $q_get_project_progress_list->num_rows();
		$progress_list = $q_get_project_progress_list->result();

		$use_date_stamp = 0;

		$current_day_line = date('Y/m/d');
		$date_allowance = strtotime(date("Y-m-d", strtotime("- 5 days")) ) * 1000 ;

		foreach ($progress_list as $prj ) {

			$quote_deadline_date_replaced = str_replace('/', '-', $prj->date_site_finish);
			$quote_deadline_date_reformated = date('Y/m/d', strtotime("$quote_deadline_date_replaced "));

			$quote_start_date_replaced = str_replace('/', '-', $prj->date_site_commencement);
			$quote_start_date_reformated = strtotime("$quote_start_date_replaced ");

			$quote_start_date_reformated = $quote_start_date_reformated."000";


			if($date_allowance <= $quote_start_date_reformated){
				$use_date_stamp = $quote_start_date_reformated;
			}else{
				$use_date_stamp = $date_allowance;
			}

			$date_deadline_check_val = date('N',$prj->project_unix_end_date);

			echo '{
				name: "<a href=\"'.base_url().'projects/view/'.$prj->project_id.'\" target=\"_blank\">'.$prj->project_id.'</a>", 
				dataObj: "'.$prj->pm_name.' : '.$prj->project_name.'",
				values: [
				{from: "/Date('.$use_date_stamp.')/", to: "'.$quote_deadline_date_reformated.'", "desc": "<strong>'.$prj->pm_name.'</strong> : '.$prj->project_name.'<br /><strong>'.$prj->client_name.'</strong>&nbsp;  &nbsp;  &nbsp;   &nbsp;  Brand : '.$prj->brand_name.'",customClass: "'.strtolower(str_replace(' ','',$prj->job_category)).'"},
				{from: "'.$current_day_line.'", to: "'.$current_day_line.'", "desc": "<strong>'.$prj->pm_name.'</strong> : '.$prj->project_name.'<br /><strong>'.$prj->client_name.'</strong>&nbsp;  &nbsp;  &nbsp;   &nbsp;  Brand : '.$prj->brand_name.'",customClass: "curr_date"}
				]
			},';
		}



		for($looper = $num_result; $looper < 16 ; $looper ++ ){

		//echo "<p><strong>$looper</strong></p>";
			

			echo '

			{ values: [
					{from: "'.date('Y/m/d', strtotime('- 5 days')).'", to: "'.date('Y/m/d', strtotime('+ 5 days')).'",  customClass: "hide"},
					{from: "'.date('Y/m/d').'", to: "'.date('Y/m/d').'" ,customClass: "curr_date"},
					{from: "'.date('Y/m/d', strtotime('+ 5 days')).'", to: "'.date('Y/m/d', strtotime('+ 65 days')).'",  customClass: "hide"}
				]
			}, 


			';

		}


	}




	public function top_joinery_list($year='',$is_pie=''){


		if($year != ''){
			$c_year = $year;
		}else{
			$c_year = date("Y");
		}

		$total_pie = 0;




		$q_get_top_joinery_list = $this->dashboard_m->get_top_joinery_list($c_year);
		$get_top_joinery_list = $q_get_top_joinery_list->result();


		foreach ($get_top_joinery_list as $joinery){
			$total_pie = $total_pie + round($joinery->total_price,1);
		} 


		foreach ($get_top_joinery_list as $joinery){


			if($is_pie != ''){

				echo "['".$joinery->company_name."',".round($joinery->total_price,2)."  ],";

			}else{

				$join_percent_val  = round( 100 / ($total_pie / round($joinery->total_price,1) ) ,1);

				echo '<div class="col-sm-8 col-md-7"><i class="fa fa-chevron-circle-right"></i>  &nbsp; ';

				$comp_name = $joinery->company_name;
				if(strlen($comp_name) > 30){
					echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="right" data-original-title="'.$comp_name.'">'.substr($comp_name,0,30).'...</span>';
				}else{
					echo $comp_name;
				}

				echo ' </div><div class="col-md-2 col-sm-4"><strong>'.$join_percent_val.'%</strong></div>  <div class="col-md-3 col-sm-4 " ><i class="fa fa-usd"></i> '.number_format($joinery->total_price).'</div><div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';


			}




//['Ultimate Interiors', 393826.10],



	 	//	echo "<p><td> -------- $joinery->company_name --------- $join_percent_val --------- ".number_format($joinery->total_price)."</p>"; 




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





	public function progressBar($assign_id=''){
		$user_id = '';
		$type = '';
		$init_f_total = 0;
		$init_c_total = 0;
		

		if(isset($assign_id) && $assign_id != '' && $assign_id != 0){
			$user_id = $assign_id;
			$type = 1;
		}else{
			$user_id = '';
			$type = '';
		}

		$c_year = date("Y");
		$c_month = date("m")+1;

		$date_a = "01/01/$c_year";
		$date_b = date("d/$c_month/Y");
		$date_c = "01/$c_month/$c_year";

		$months = array("jan","feb","mar","apr","may","jun","jul","aug","sep","oct","nov","dec");

		$focus_wip_overall = $this->get_wip_value_permonth($date_a,$date_c,$user_id,$type);   //--------------------


		$q_current_invoiced_amount = $this->dashboard_m->fetch_current_total_invoices($c_year,$user_id);   //--------------------
		$current_invoiced_amount =  array_shift($q_current_invoiced_amount->result_array());

 	//	var_dump($current_invoiced_amount);
		//var_dump($q_current_invoiced_amount );


		$q_forecast = $this->dashboard_m->fetch_forecast($c_year,'1');   //--------------------
		$forecast =  array_shift($q_forecast->result_array());

		$c_month_text = strtolower(date('M'));
		$percent_total = 0;


		for ($i=0; $i<12; $i++){

			$percent_total = $percent_total + $forecast['forecast_'.$months[$i]];

			if($c_month_text == $months[$i]){
				break;
			}
		}

 //	var_dump($focus_wip_overall);


		$current_standing = floatval($focus_wip_overall)+floatval($current_invoiced_amount['current_sales']);

	//	echo "<p>$current_standing</p>";

		if(isset($assign_id) && $assign_id != '' && $assign_id != 0){
			$q_pm_forecast = $this->dashboard_m->fetch_pm_forecast_details($c_year,$forecast['revenue_forecast_id'],$user_id);
			$pm_forecast = $q_pm_forecast->result();

			foreach ($pm_forecast as $pm_val_forecast){

				$init_temp = ($forecast['total'] * ($pm_val_forecast->f_comp_forecast_percent/100) ) * ($pm_val_forecast->forecast_percent / 100);

				$init_c_total = $init_c_total + $init_temp;

			//	echo "<br />".$init_c_total."<br />";

			//	$init_c_total = $init_c_total + ( $forecast['total'] * ($pm_val_forecast->f_comp_forecast_percent/100) );
			//	$init_f_total = $init_f_total + ( $forecast['total'] * ($pm_val_forecast->f_comp_forecast_percent/100) ) * ($pm_val_forecast->forecast_percent/100);
			}

// 			echo "<p>----</p>";
// var_dump($init_f_total);

// 			echo "<p>----</p>";
// var_dump($init_c_total);


			$forecasted_amount = $init_c_total * ($percent_total/100);

			if($current_standing > 0 && $forecasted_amount > 0){
				$current_progress = 100 / ( $forecasted_amount / $current_standing );
			}elseif($forecasted_amount > 0 && $current_standing <= 0 ){
				$current_progress = 100 / ( $forecasted_amount );
			}elseif($forecasted_amount <= 0 && $current_standing > 0 ){
				$current_progress = 100 / ( $current_standing );
			}else{
				$current_progress = 100 / 1;
			}


// var_dump($current_progress);
// 			echo "<p>----</p>";
		}else{
			$forecasted_amount = $forecast['total'] * ($percent_total/100);
			$current_progress = 100 / ( $forecasted_amount / $current_standing );
		}

		// $percent_total = 75;
		// $current_progress = 50;

		//  $forecasted_amount = 1000000;
		//  $current_standing = 700000;


			if($current_standing > 0 && $forecasted_amount > 0){
				$current_amnt_progress = 100 / ( $forecasted_amount / $current_standing );
			}elseif($forecasted_amount > 0 && $current_standing <= 0 ){
				$current_amnt_progress = 100 / ( $forecasted_amount );
			}elseif($forecasted_amount <= 0 && $current_standing > 0 ){
				$current_amnt_progress = 100 / ( $current_standing );
			}else{
				$current_amnt_progress = 100 / 1;
			}




		$final_width = abs($current_progress - $percent_total);

		if($current_standing > $forecasted_amount){

			if($forecasted_amount > 0){
				$less_amount_percent = 100 / ( $current_standing / $forecasted_amount );



			echo '<div class="x progress-bar progress-bar-danger active progress-bar-striped tooltip-enabled" data-html="true" data-placement="bottom" data-original-title="Current Standing plus YTD WIP<br />$'.number_format($current_standing).'"  style="position: absolute; width: 97.2%; background-color: #1c61a7; border-radius: 20px; height: 30px; text-align: right; padding-right: 10px; ">'.number_format($current_amnt_progress).'%</div>
			<div class="progress-bar progress-bar-danger active progress-bar-striped tooltip-enabled" data-html="true" data-original-title="Cumulative Forecast YTD<br />$'.number_format($forecasted_amount).'<br /><br />Current Standing plus YTD WIP<br />$'.number_format($current_standing).' at '.number_format($current_amnt_progress).'%" style="z-index: 1; position: absolute; width: '.($less_amount_percent-2).'%; background-color: #002C8F; border-radius: 0px 10px 10px 0px; height: 30px;">Cumulative Forecast YTD</div> ';
	


			}else{
				$less_amount_percent = 100 / ( $current_standing / 1 );
				$forecasted_amount = 0;




			echo '<div class="y progress-bar progress-bar-danger active progress-bar-striped tooltip-enabled" data-html="true" data-placement="bottom" data-original-title="Current Standing plus YTD WIP<br />$'.number_format($current_standing).' at 100%"  style="left: 30px; position: absolute; width: 97.9%; background-color: #1c61a7; border-radius: 20px; height: 30px; text-align: right; padding-right: 10px; ">100%</div>';
	



			}



		}else{

	//		$less_amount_percent = 100 / ( $forecasted_amount / $current_standing );



			if($current_standing > 0 && $forecasted_amount > 0){
				$less_amount_percent = 100 / ( $forecasted_amount / $current_standing );
			}elseif($forecasted_amount > 0 && $current_standing <= 0 ){
				$less_amount_percent = 100 / ( $forecasted_amount );
			}elseif($forecasted_amount <= 0 && $current_standing > 0 ){
				$less_amount_percent = 100 / ( $current_standing );
			}else{
				$less_amount_percent = 100 / 1;
			}


			echo '<div class="z progress-bar progress-bar-danger active progress-bar-striped tooltip-enabled" data-html="true" data-original-title="Current Standing plus YTD WIP<br />$'.number_format($current_standing).' at '.number_format($current_amnt_progress).'%<br /><br />Cumulative Forecast YTD<br />$'.number_format($forecasted_amount).'"  style="z-index: 1; position: absolute; width: '.($current_progress-2).'%; background-color: #1c61a7; border-radius: 0px 10px 10px 0px; height: 30px;">'.number_format($current_amnt_progress).'%</div>
			<div class="progress-bar progress-bar-danger active progress-bar-striped tooltip-enabled" data-html="true" data-placement="bottom" data-original-title="Cumulative Forecast YTD<br />$'.number_format($forecasted_amount).'" style="position: absolute; width: 97.2%; background-color: #002C8F; border-radius: 20px; height: 30px; text-align: right; padding-right: 10px; ">Cumulative Forecast YTD</div> ';
		}


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

	/* auto compute sales update and push live */

	public function pm_type($user_id=''){
		$pm_type = 0;

		if(isset($user_id) && $user_id != ''){

			$raw_user_dat = $this->user_model->fetch_user_role_dept($user_id);
			$user_data =  array_shift($raw_user_dat->result_array());

			if($user_data['user_role_id'] == 3 && $user_data['user_department_id'] == 1):
				$pm_type = 1;
			endif; //for directors 

			if($user_data['user_role_id'] == 3 && $user_data['user_department_id'] == 4): //for PM 
				$pm_type = 2;
			endif; //for PM 

			if($user_data['user_role_id'] == 20 && $user_data['user_department_id'] == 4): //for PM 
				$pm_type = 2;
			endif; //for PM 

		}else{
			if($this->session->userdata('user_role_id') == 3 && $this->session->userdata('user_department_id') == 1):
				$pm_type = 1;
			endif; //for directors 

			if($this->session->userdata('user_role_id') == 3 && $this->session->userdata('user_department_id') == 4): //for PM 
				$pm_type = 2;
			endif; //for PM 

			if($this->session->userdata('user_role_id') == 20 && $this->session->userdata('user_department_id') == 4): //for PM 
				$pm_type = 2;
			endif; //for PM 
		}

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

		$project_manager = $this->dashboard_m->fetch_pms_year($c_year); // ****--___--***
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
		$total_string = '<div class=\'row\'><span class=\'col-xs-12\'> ('.$c_year.')</div>';

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
			$pm_data[$key] = 0;
		}

		$last_year = intval(date("Y"))-1;
		$n_month = date("m");
		$n_day = date("d");

		$date_a_last = "01/01/$last_year";
		$date_b_last = "$n_day/$n_month/$last_year";

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';


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

					if( in_array($sales->project_manager_id, $pm_data) ){
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


		$total_string = '<div class=\'row\'><span class=\'col-xs-12\'> ('.$c_year.')</div>';

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();
		$personal_data = 0;

		$project_manager = $this->dashboard_m->fetch_pms_year($n_year); // ****--___--***
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

				if( in_array($unvoiced->project_manager_id, $pm_data) ){
					$pm_data[$unvoiced->project_manager_id] = $pm_data[$unvoiced->project_manager_id] + $unvoiced_total;
				}
			}
		}

		foreach ($pm_data as $key => $value) {
			$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name[$key].'</span> <span class=\'col-xs-6\'>$ '.number_format($value,2).'</span></div>';
			$pm_data[$key] = 0;
		}
 



		$last_year = intval(date("Y"))-1;
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';

		$date_a_last = "01/01/$last_year";
		$date_b_last = "31/12/$last_year";

		$n_month = date("m");
		$n_day = date("d");
		$date_last_year_today = "$n_day/$n_month/$last_year";
		$date_b_full_end = "31/12/$last_year";


		foreach ($focus_company as $company){
			$q_dash_unvoiced = $this->dashboard_m->dash_unvoiced_per_date($date_a_last,$date_b_full_end,$company->company_id);
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

				if( in_array($unvoiced->project_manager_id, $pm_data) ){
					$pm_data[$unvoiced->project_manager_id] = $pm_data[$unvoiced->project_manager_id] + $unvoiced_total;
				}
			}
		}



		foreach ($pm_data as $key => $value) {


			if (array_key_exists($pm_name[$key],$pm_name))  {
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name[$key].'</span> <span class=\'col-xs-6\'>$ '.number_format($value,2).'</span></div>';
			}


		}

		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';


 

	}





	public function outstanding_payments_widget_pa(){
		$assignment = $this->pa_assignment();
		$prime_pm = $assignment['project_manager_primary_id'];
		$group_pm = explode(',', $assignment['project_manager_ids']);

		$c_year = date("Y");
		$project_manager = $this->dashboard_m->fetch_pms_year($c_year); // ****--___--***
		$project_manager_list = $project_manager->result();

		$pm_data = array();
		$pm_name = array();

		foreach ($project_manager_list as $pm ){
			if( in_array($pm->user_id, $group_pm) ){
				$pm_data[$pm->user_id] = 0;
				$pm_name[$pm->user_id] = $pm->user_first_name;
			}
		}

		$date_a = "01/01/$c_year";
		$n_year = date("Y");
		$n_month = date("m");
		$n_day = date("d");

		$date_b = "$n_day/$n_month/$n_year";
 
		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		$total_string = '<div class=\'row\'><span class=\'col-xs-12\'>('.$c_year.')</div>';
		$personal_data = 0;

		$pm_outstanding = array();
		$display_each_value = 0;

		$project_manager = $this->dashboard_m->fetch_pms_year($c_year); // ****--___--***
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


				if ( array_key_exists($prime_pm, $pm_outstanding) ) {
					$personal_data_latest = $pm_outstanding[$prime_pm];
				}
 



				
			}


		}

		foreach ($pm_data as $key => $value) {
			$amount = $pm_outstanding[$key];
			$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name[$key].'</span> <span class=\'col-xs-6\'>$ '.number_format($amount,2).'</span></div>';
			$pm_outstanding[$key] = 0;
		}



		$last_year = intval(date("Y"))-1;
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';

		$date_a_last = "01/01/$last_year";
		$date_b_last = "31/12/$last_year";

		$n_month = date("m");
		$n_day = date("d");
		$date_last_year_today = "$n_day/$n_month/$last_year";
		$date_b_full_end = "31/12/$last_year";



		foreach ($focus_company as $company){
			$each_comp_total[$company->company_id] = 0;

			$invoice_amount= 0;
			$total_invoice= 0;
			$total_paid = 0;
			$total_outstanding = 0;

			$key_id = '';

			$q_dash_oustanding_payments = $this->dashboard_m->dash_oustanding_payments($date_a_last,$date_b_full_end,$company->company_id);
			$oustanding_payments = $q_dash_oustanding_payments->result();

			foreach ($oustanding_payments as $oustanding) {

				if($oustanding->label == 'VR'){
					$invoice_amount = $oustanding->variation_total;
				}else{
					$invoice_amount = $oustanding->project_total*($oustanding->progress_percent/100);
				}

				$total_paid =  $oustanding->amount_exgst;
				$display_each_value = $invoice_amount - $total_paid;





				if( in_array($oustanding->project_manager_id, $pm_outstanding) ){
					$pm_outstanding[$oustanding->project_manager_id] = $pm_outstanding[$oustanding->project_manager_id] + $display_each_value;
				}


				if (in_array($prime_pm,$pm_outstanding))  {
					$personal_data =  $pm_outstanding[$prime_pm] + $personal_data_latest;
				}





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

		$project_manager = $this->dashboard_m->fetch_pms_year(date("Y")); // ****--___--***
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

		$project_manager = $this->dashboard_m->fetch_pms_year(date("Y")); // ****--___--***
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

			if( in_array($pm_id, $pm_data_b) ){
				$pm_data_b[$pm_id]++;
			}
		}

		 
		$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-12\'> ('.$year.') Invoiced</div>';
		$total_string_wip .= '<div class=\'row\'><span class=\'col-xs-12\'> ('.$year.') WIP</div>';


		$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';
		$total_string_wip .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';

		foreach ($group_pm as $key => $value) {


			if ( array_key_exists($value, $pm_name)   &&    array_key_exists($value, $pm_data_a)   &&    array_key_exists($value, $pm_data_b) ) {
				$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-7\'>'.$pm_name[$value].'</span> <span class=\'col-xs-5\'>'.$pm_data_a[$value].'</span></div>'; $pm_data_a[$value] = 0;
				$total_string_wip .= '<div class=\'row\'><span class=\'col-xs-7\'>'.$pm_name[$value].'</span> <span class=\'col-xs-5\'>'.$pm_data_b[$value].'</span></div>';


			}
		}












		$last_year = intval(date("Y"))-1; 
		$n_month = date("m");
		$n_day = date("d");
		$date_last_year_today = "$n_day/$n_month/$last_year";
		$set_date_b = '01/01/'.$last_year;


		$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';

		foreach ($focus_company as $company) {
			$invoiced = 0;
			$invoiced_old = 0;

			$projects_qa = $this->dashboard_m->get_wip_invoiced_projects($set_date_b, $date_last_year_today, $company->company_id);
			$projects_ra = $projects_qa->result_array();

			foreach ($projects_ra as $result) {
				if($this->invoice->if_invoiced_all($result['project_id'])  && $this->invoice->if_has_invoice($result['project_id']) > 0 ){
					$invoiced++;



					if( in_array($result['project_manager_id'], $pm_data_a) ){
						$pm_data_a[ $result['project_manager_id'] ]++;
					}
				}
			}
		}



		foreach ($group_pm as $key => $value) {


			if ( array_key_exists($value, $pm_name)   &&    array_key_exists($value, $pm_data_a)   ) {
			$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-7\'>'.$pm_name[$value].'</span> <span class=\'col-xs-5\'>'.$pm_data_a[$value].'</span></div>'; $pm_data_a[$value] = 0;
	

		}

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

		$project_manager = $this->dashboard_m->fetch_pms_year(date("Y")); // ****--___--***
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
		
		$total_string = '<div class=\'row\'><span class=\'col-xs-12\'> ('.$year.')</div>';
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
		$personal_data = 0;

	 	$q_projects = $this->dashboard_m->get_unaccepted_projects($current_start_year,$current_date);
		$projects = $q_projects->result();


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


									if( in_array($un_accepted->project_manager_id, $pm_data) ){


															$pm_data[$un_accepted->project_manager_id] = $pm_data[$un_accepted->project_manager_id] + $amnt;
														}
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
			$project_cost[$comp_id] = 0;
		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';

		foreach ($pm_data as $key => $value) {
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name[$key].'</span> <span class=\'col-xs-6\'>$ '.number_format($value,2).'</span></div>';
				$pm_data[$key] = 0;
		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';


		foreach ($estimator_list as $est ) {
			$display_total_cmp = $unaccepted_amount[$est->project_estiamator_id];
			$est_name = $est->user_first_name;
			if($display_total_cmp > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$est_name.'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cmp,2).'</span></div>';
			}

			$unaccepted_amount[$est->project_estiamator_id] = 0;
		}











		$last_year = intval(date("Y"))-1;
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';

		$n_month = date("m");
		$n_day = date("d");
		$date_last_year_today = "$n_day/$n_month/$last_year";
		$set_date_b = '01/01/'.$last_year;
	 

	 	$q_projects = $this->dashboard_m->get_unaccepted_projects($set_date_b,$date_last_year_today);
		$projects = $q_projects->result();


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
 


						
					if( in_array($un_accepted->project_manager_id, $pm_data) ){
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
				if($value > 0){
					$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name[$key].'</span> <span class=\'col-xs-6\'>$ '.number_format($value,2).'</span></div>';
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



	public function focus_get_po_widget_pa(){
		$assignment = $this->pa_assignment();
		$prime_pm = $assignment['project_manager_primary_id'];
		$group_pm = explode(',', $assignment['project_manager_ids']);

		$project_manager = $this->dashboard_m->fetch_pms_year(date("Y")); // ****--___--***
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
			
			if( in_array($row['project_manager_id'], $group_pm) ){


							if( in_array($row['project_manager_id'], $pm_data) ){


								$pm_data[$row['project_manager_id']] = $pm_data[$row['project_manager_id']] + $out_standing;
							}
					}

			if($prime_pm == $row['project_manager_id']){
				$personal_data = $personal_data + $out_standing;
			}
		}


		$total_string = '<div class=\'row\'><span class=\'col-xs-12\'> ('.$year.')</div>';


		foreach ( $pm_data as $key => $value) {
			$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name[$key].'</span> <span class=\'col-xs-6\'>$ '.number_format($value,2).'</span></div>';
			$pm_data[$key] = 0;
		}






		$last_year = intval(date("Y"))-1;
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';

		$n_month = date("m");
		$n_day = date("d");
		$date_last_year_today = "$n_day/$n_month/$last_year";
		$set_date_b = '01/01/'.$last_year;
 

		$po_list_ordered = $this->purchase_order_m->get_po_list_order_by_project($set_date_b,$date_last_year_today);

		foreach ($po_list_ordered->result_array() as $row){
			$work_id = $row['works_id'];

			$po_tot_inv_q = $this->purchase_order_m->get_po_total_paid($work_id);
			$invoiced = 0;
			foreach ($po_tot_inv_q->result_array() as $po_tot_row){
				$invoiced = $po_tot_row['total_paid'];
			}

			$out_standing = $row['price'] - $invoiced;
			
			if( in_array($row['project_manager_id'], $group_pm) ){
				$pm_data[$row['project_manager_id']] = $pm_data[$row['project_manager_id']] + $out_standing;

			}

			if($prime_pm == $row['project_manager_id']){
				$personal_data = $personal_data + $out_standing;
			}
		}

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

		$current_year = date("Y");
		$total_string .= '<div class=\'row\'>&nbsp; ('.$current_year.')</div>';

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
				$focus_company_hrs[$company->company_id] = 0;
			}
		}


		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';

		foreach ($states_list as $sts){
			if($hrs_states[$sts->id] > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$states_name[$sts->id].'</span> <span class=\'col-xs-6\'>'.number_format($hrs_states[$sts->id],2).'</span></div>';
				$hrs_states[$sts->id] = 0;
			}
		}


		$last_year = intval(date("Y"))-1;
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';

		

		if( date('m') < 12 ){
			$old_month = intval(date('m'))+3;
			$old_year_set = $last_year;
		}else{
			$old_year_set = $last_year + 1;
			$old_month = date('m');
		}

		$old_year = $old_year_set.'-'.date('m-d');
		$old_year_limit = "$old_year_set-$old_month-".date('d');

		$days_q = $this->dashboard_m->get_site_labour_hrs($old_year,$old_year_limit);
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



	public function wid_quoted($es_id = ''){
		//var_dump($es_id);

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


		if($es_id != ''){
			$quoted_estimator[$es_id] = 0;
			$cost_estimator[$es_id] = 0;
		}


		$quoted_estimator_name[0] = '';

		$project_manager = $this->dashboard_m->fetch_pms_year(date("Y")); // ****--___--***
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

				if (array_key_exists($row['project_manager_id'], $quoted_pm)) {
					$quoted_pm[$row['project_manager_id']]++;
				}

				$quoted_focus_company[$row['focus_company_id']]++;

				if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 || $row['variation_total'] > 0.00 ){
					$project_cost = $row['project_total'] + $row['variation_total'];
				}else{
					$project_cost = $row['budget_estimate_total'];
				}

				$cost_focus[$row['focus_company_id']] = $cost_focus[$row['focus_company_id']] + $project_cost;
				$cost_estimator[$row['project_estiamator_id']] = $cost_estimator[$row['project_estiamator_id']] + $project_cost;
			

				if (array_key_exists($row['project_manager_id'], $cost_pm)) {
					$cost_pm[$row['project_manager_id']] = $cost_pm[$row['project_manager_id']] + $project_cost;
				}




			}
		}

		$display_final = array_sum($quoted_focus_company);
		$display_cost = array_sum($cost_focus);

		$current_year = intval(date("Y"));
		$total_string .= '<div class=\'row\'>&nbsp; ('.$current_year.')</div>';

		foreach ($focus_company as $company){
			if($quoted_focus_company[$company->company_id] > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$company->company_id]).'</span> <span class=\'col-xs-6\'>$  '.number_format($cost_focus[$company->company_id],2).' <span class=\'pull-right\'>'.$quoted_focus_company[$company->company_id].'</span></span></div>';
			}
			$quoted_focus_company[$company->company_id] = 0;
			$cost_focus[$company->company_id] = 0;
		}
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';

		foreach ($project_manager_list as $pm ) {
			$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$quoted_pm_name[$pm->user_id].'</span> <span class=\'col-xs-6\'>$  '.number_format($cost_pm[$pm->user_id],2).' <span class=\'pull-right\'>'.$quoted_pm[$pm->user_id].'</span></span></div>';
			$cost_pm[$pm->user_id] = 0;
			$quoted_pm[$pm->user_id] = 0;
		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';


		if($es_id != ''){
			$display_cost = $cost_estimator[$es_id];
			$display_final = $quoted_estimator[$es_id];
		}

		foreach ($estimator_list as $est ) {
			$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$quoted_estimator_name[$est->project_estiamator_id].'</span> <span class=\'col-xs-6\'>$ '.number_format($cost_estimator[$est->project_estiamator_id],2).' <span class=\'pull-right\'>'.$quoted_estimator[$est->project_estiamator_id].'</span></span></div>';
			$cost_estimator[$est->project_estiamator_id] = 0;
			$quoted_estimator[$est->project_estiamator_id] = 0;
		}





		$last_year = intval(date("Y"))-1;
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';

		$n_month = date("m");
		$n_day = date("d");
		$date_last_year_today = "$n_day/$n_month/$last_year";

		$m_month = $n_month+2;
		$year_odl_set = $last_year;

		if($m_month > 12){
			$m_month - 12;
			$year_odl_set = $last_year + 1;
		}

		$date_last_year_next = "01/$m_month/$year_odl_set";

		$all_projects_q = $this->dashboard_m->get_all_active_projects($date_last_year_today,$date_last_year_next);
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

			//var_dump($row['project_estiamator_id']);
			//var_dump($row['project_manager_id']);
			//var_dump($row['focus_company_id']);
			//var_dump($row['project_id']);

			//if($status == 'quote'){
				$quoted_estimator[$row['project_estiamator_id']]++;


				if (array_key_exists($row['project_manager_id'], $quoted_pm)) {
					$quoted_pm[$row['project_manager_id']]++;
				}


				if (array_key_exists($row['project_manager_id'], $quoted_pm)) {
					$quoted_pm[$row['project_manager_id']]++;
				}



				$quoted_focus_company[$row['focus_company_id']]++;

				if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 || $row['variation_total'] > 0.00 ){
					$project_cost = $row['project_total'] + $row['variation_total'];
				}else{
					$project_cost = $row['budget_estimate_total'];
				}

				$cost_focus[$row['focus_company_id']] = $cost_focus[$row['focus_company_id']] + $project_cost;
				$cost_estimator[$row['project_estiamator_id']] = $cost_estimator[$row['project_estiamator_id']] + $project_cost;
				 


				if (array_key_exists($row['project_manager_id'], $cost_pm)) {
					$cost_pm[$row['project_manager_id']] = $cost_pm[$row['project_manager_id']] + $project_cost;
				}


			//}
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


		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><strong><i class="fa fa-usd"></i> '.number_format($display_cost,2).' <span class="pull-right">'.number_format($display_final).'</span></strong></p>';
	}




	public function focus_projects_by_type_widget_pa($is_pie = ''){

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

/*
			echo '<div id="" class="clearfix"><p><strong class="col-sm-2">'.$count.'</strong> <strong class="col-sm-4">'.$value.'</strong><strong class="col-sm-2">'.$percent.'% </strong><strong class="col-sm-4">$ '.number_format(round($cost)).'</strong></p></div>';
			echo '<div class="col-md-12"><hr class="block m-bottom-5 m-top-5"></div>';
*/


			if($is_pie != ''){

				echo "['".str_replace("'","&apos;",$value)."',".$cost."],";

			}else{

				echo '<div id="" class="clearfix"><p><span class="col-sm-7"><i class="fa fa-chevron-circle-right"></i> &nbsp; '.$value.'</span><strong class="col-sm-5">$ '.number_format($cost).'</strong></p></div>';
				echo '<div class="col-md-12"><hr class="block m-bottom-5 m-top-5"></div>';
			}


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

			echo '<div class="col-sm-8 col-md-7"><i class="fa fa-chevron-circle-right"></i>  &nbsp; ';

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

			echo ' </div><div class="col-md-2 col-sm-4"><strong>'.$percent.'%</strong></div>  <div class="col-md-3 col-sm-4 tooltip-test" title="" data-placement="left" data-original-title="Last Year : $ '.number_format($lst_year_total).'"><i class="fa fa-usd"></i> '.number_format($company->total_price).'</div><div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';
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
			echo '<div class="col-sm-8 col-md-7"><i class="fa fa-chevron-circle-right"></i>  &nbsp; ';

			$q_vr_c = $this->dashboard_m->client_vr_value($current_start_year,$current_date,$company->client_id,$comp_q);
			$vr_val = array_shift($q_vr_c->result_array());

			$comp_name = $company->company_name;
			if(strlen($comp_name) > 30){
				echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$comp_name.'">'.substr($comp_name,0,30).'...</span>';
			}else{
				echo $comp_name;
			}

			$q_vr_c_u = $this->dashboard_m->client_vr_value($date_a_last,$date_b_last,$company->client_id,$comp_q);
			$vr_val_u = array_shift($q_vr_c_u->result_array());

			$last_year_q = $this->dashboard_m->get_top_ten_clients($date_a_last, $date_b_last,$company->company_id);
			$last_year_sale = array_shift($last_year_q->result_array());
			$lst_year_total = $last_year_sale['grand_total'] + $vr_val_u['total_variation'];
			echo ' </div><div class="col-md-2 col-sm-4"><strong>'.$percent.'%</strong></div>  <div class="col-md-3 col-sm-4 tooltip-test" title="" data-placement="left" data-original-title="Last Year : $ '.number_format($lst_year_total).'"><i class="fa fa-usd"></i> '.number_format($company->grand_total+ $vr_val['total_variation']).'</div><div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';
		}




	}



	public function average_date_invoice_pa(){

		$assignment = $this->pa_assignment();
		$prime_pm = $assignment['project_manager_primary_id'];
		$group_pm = explode(',', $assignment['project_manager_ids']);

		$project_manager = $this->dashboard_m->fetch_pms_year(date("Y")); // ****--___--***
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




	public function pm_sales_widget_pa($is_term=''){

		$assignment = $this->pa_assignment();
		$prime_pm = $assignment['project_manager_primary_id'];
		$group_pm = explode(',', $assignment['project_manager_ids']);

		$project_manager = $this->dashboard_m->fetch_pms_year(date("Y")); // ****--___--***
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

		$project_manager = $this->dashboard_m->fetch_pms_year(date("Y")); // ****--___--***
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
		
		if($is_term == ''){
			echo "<div style=\"overflow-y: auto; padding-right: 5px; height: 400px;\">";
		}

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


					if($is_term == ''){
						echo '<div class="m-bottom-15 clearfix"><div class="pull-left m-right-10"  style="height: 50px; width:50px; border-radius:50px; overflow:hidden; border: 1px solid #999999;"><img class="user_avatar img-responsive img-rounded" src="'.base_url().'/uploads/users/'.$focus_pm_pic[$pm_id].'"" /></div>';
						echo '<div class="" id=""><p><strong>'.$focus_pms[$pm_id].'</strong><span class="pull-right"><span class="label pull-right m-bottom-3 m-top-3 small_orange_fixed"><i class="fa fa-usd"></i> '.number_format($set_invoiced_amount[$pm_id]).'</span> <br /> <span class="label pull-right m-bottom-3 small_green_fixed"><i class="fa fa-exclamation-triangle"></i> '.number_format($wip_pm_total[$pm_id]).'</span></span></p>';
						echo '<p><i class="fa fa-usd"></i> '.number_format($pm_sales_value).'</p>';

						echo '<div class="progress no-m m-top-3 clearfix tooltip-enabled" title="" data-original-title="'.$status_forecast.'% - $'.number_format($pm_sales_value).' / $'.number_format($total_forecast).'   " style="height: 7px;">
						<div class="progress-bar progress-bar-danger" style="width:'.$status_forecast.'%; background:red;"></div></div></div></div>';

						echo "<div class='clearfix'></div>";
					}
					$forecast_focus_total = $forecast_focus_total + $total_forecast;


					if($prime_pm == $pm_id){
						$return_total = $status_forecast;
					}
				//}
			}
		}
		if($is_term == ''){
			echo "</div>";
		}
		$fetch_user = $this->user_model->fetch_user($prime_pm);
		$user_details = array_shift($fetch_user->result_array());


		$q_current_forecast_comp = $this->dashboard_m->get_current_forecast($c_year,$user_details['user_focus_company_id'],'1');
		$comp_forecast = array_shift($q_current_forecast_comp->result_array());

		$q_current_forecast = $this->dashboard_m->get_current_forecast($c_year,$prime_pm);
		$pm_forecast = array_shift($q_current_forecast->result_array());

		$total_forecast = ( $comp_forecast['total'] * (  $comp_forecast['forecast_percent']  /100  ) *  ($pm_forecast['forecast_percent']/100) );



		if ( array_key_exists($prime_pm, $set_invoiced_amount)   &&    array_key_exists($prime_pm, $wip_pm_total) ) {
			$pm_sales_value = $set_invoiced_amount[$prime_pm] + $wip_pm_total[$prime_pm];
		}else{
			$pm_sales_value = 0;
		}





		$pm_sales_value = ($pm_sales_value < 1 ? 0 : $pm_sales_value);
		$total_forecast = ($total_forecast < 1 ? 0 : $total_forecast);

		if($total_forecast > 0 && $pm_sales_value > 0){
			$status_forecast = round(100/($total_forecast/$pm_sales_value));

		}elseif($total_forecast > 0  && $pm_sales_value <= 0 ){
			$status_forecast = round(100/($total_forecast));
		}else{
			$status_forecast = 0;
		}

		

		//$status_forecast = round(100/($forecast_focus_total/ ($total_wip + $total_invoiced) ));

		if($is_term == ''){
			echo '<div class="clearfix" style="padding-top: 6px;    border-top: 1px solid #eee;"><i class="fa fa-briefcase" style="font-size: 42px;float: left;margin-left: 7px;margin-right: 10px;"></i>';
		
			if ( array_key_exists($prime_pm, $set_invoiced_amount)   &&    array_key_exists($prime_pm, $wip_pm_total) ) {


				echo '<div class="" id=""><p><strong>Primary Overall</strong><span class="pull-right"><span class="label pull-right m-bottom-3 m-top-3 small_orange_fixed"><i class="fa fa-usd"></i> '.number_format($set_invoiced_amount[$prime_pm]).'</span> <br /> <span class="label pull-right m-bottom-3 small_green_fixed"><i class="fa fa-exclamation-triangle"></i> '.number_format( $wip_pm_total[$prime_pm]).'</span></span></p>';
		
			}else{
				
				echo '<div class="" id=""><p><strong>Primary Overall</strong><span class="pull-right"><span class="label pull-right m-bottom-3 m-top-3 small_orange_fixed"><i class="fa fa-usd"></i> 0.00</span> <br /> <span class="label pull-right m-bottom-3 small_green_fixed"><i class="fa fa-exclamation-triangle"></i> 0.00</span></span></p>';
		
			}


		echo '<p><i class="fa fa-usd"></i> '.number_format( $pm_sales_value ).' <strong class="pull-right m-right-10"></strong></p> </p>';

		echo '<div class="progress no-m m-top-3 clearfix tooltip-enabled" title="" data-original-title="'.$status_forecast.'% - $'.number_format( $pm_sales_value ).' / $'.number_format($total_forecast).'   " style="height: 7px;">';
		echo '<div class="progress-bar progress-bar-danger" style="width:'.$status_forecast.'%; background:red;"></div></div></div></div>';
		echo "<div class='clearfix'></div>";
		
	}
		//return $return_total;




		if ( array_key_exists($prime_pm, $set_invoiced_amount)   &&    array_key_exists($prime_pm, $wip_pm_total) ) {

			$pm_overall_display = $pm_sales_value = $set_invoiced_amount[$prime_pm] + $wip_pm_total[$prime_pm];  


		}else{
			$pm_overall_display = $pm_sales_value;
		}


		if($is_term == ''){

		return $return_total.'_'.number_format( $pm_overall_display );
			}else{

		echo $return_total.'_'.number_format( $pm_overall_display );
			}


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
		//$last_year = 0;


		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'> ('.$c_year.')</div>';

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
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';

		foreach ($focus_company_maintenence as $maintenance_data){
			$q_dash_sales = $this->dashboard_m->dash_sales($date_b,$date_d,$maintenance_data->focus_company_id,1);
			if($q_dash_sales->num_rows >= 1){

				$sales_total = 0;
				$dash_sales = $q_dash_sales->result();
				$last_year_amnt = 0;

				foreach ($dash_sales as $sales){

					if( $maintenance_data->project_manager_id == $sales->project_manager_id){
						if($sales->label == 'VR'){
							$sales_total = $sales->variation_total;
						}else{
							$sales_total = $sales->project_total*($sales->progress_percent/100);
						}

						$last_year_amnt = $last_year_amnt + $sales_total;
					}
				}
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$maintenance_data->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($last_year_amnt,2).'</span></div>';
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
		$date_d = "31/12/".$last_year;

		$total_string .= '<div class=\'row\'> &nbsp; ('.$c_year.')</div>';

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

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';



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
						$personal_data = $personal_data + $sales_total;
					}
				}
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($last_year,2).'</span></div>';
		//	}
		}


		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';
	}




	public function pm_sales_widget_mn($is_term=''){
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

		$project_manager = $this->dashboard_m->fetch_pms_year(date("Y")); // ****--___--***
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

		if($is_term == ''){
			echo "<div style=\"overflow-y: auto; padding-right: 5px; height: 400px;\">";
		}

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

if($is_term == ''){
				echo '<div class="m-bottom-15 clearfix"><div class="pull-left m-right-10"  style="height: 50px; width:50px; border-radius:50px; overflow:hidden; border: 1px solid #999999;"><img class="user_avatar img-responsive img-rounded" src="'.base_url().'/uploads/users/'.$focus_pm_pic[$pm_id].'"" /></div>';
				echo '<div class="" id=""><p><strong>'.$focus_pms[$pm_id].'</strong><span class="pull-right"><span class="label pull-right m-bottom-3 m-top-3 small_orange_fixed"><i class="fa fa-usd"></i> '.number_format($set_invoiced_amount[$pm_id]).'</span> <br /> <span class="label pull-right m-bottom-3 small_green_fixed"><i class="fa fa-exclamation-triangle"></i> '.number_format($wip_pm_total[$pm_id]).'</span></span></p>';
				echo '<p><i class="fa fa-usd"></i> '.number_format($pm_sales_value).'</p>';

				echo '<div class="progress no-m m-top-3 clearfix tooltip-enabled" title="" data-original-title="'.$status_forecast.'% - $'.number_format($pm_sales_value).' / $'.number_format($total_forecast).'   " style="height: 7px;">
				<div class="progress-bar progress-bar-danger" style="width:'.$status_forecast.'%; background:red;"></div></div></div></div>';

				echo "<div class='clearfix'></div>";
			}
				$forecast_focus_total = $forecast_focus_total + $total_forecast;

				if('29' == $pm_id){
					$return_total = $status_forecast;
					$return_other = $set_invoiced_amount[$pm_id] + $wip_pm_total[$pm_id];
				}
			}
		}

if($is_term == ''){
		echo "</div>";
}

		$status_forecast = round(100/($forecast_focus_total/ ($total_wip + $total_invoiced) ));
 
 if($is_term == ''){
		return $return_total.'_'.number_format( $return_other );
}else{

		echo $return_total.'_'.number_format( $return_other );
}
	//	return $return_total;

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

		$project_manager = $this->dashboard_m->fetch_pms_year(date("Y")); // ****--___--***
		$project_manager_list = $project_manager->result();

		$pms_averg = array();
		$pms_w_avg = array();
		
		foreach ($project_manager_list as $pm ) {
			$pms_averg[$pm->user_id] = array();
			$pms_w_avg[$pm->user_id] = $pm->user_id;

			if($user_id == $pm->user_id){
				$pm_set_name = $pm->user_first_name;
			}
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
				$total_string .= '<div class=\'row\'><span class=\'col-xs-8\'>'.str_replace("Pty Ltd","",$company->company_name).'</span><span class=\'col-xs-4\'>'.round($average_comp,1).'</span></div>';

			}
		} 

			if($pm_type == 1){ // for director/pm

				$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';



				foreach ($project_manager_list as $pm ){

					if( count($pms_averg[$pm->user_id]) > 0  ){
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
			} 








		$last_year = intval(date("Y")) - 1;
		$this_month = date("m");
		$this_day = date("d");

		$date_a_last = "01/01/$last_year";
		$date_b_last = "$this_day/$this_month/$last_year";
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';


		$pms_averg_old = array();	 
		foreach ($project_manager_list as $pm ) {
			$pms_averg_old[$pm->user_id] = array();			 
		}

		foreach ($focus_company as $company){

			if( in_array($company->company_id, $direct_company) ){

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
		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';


		//foreach ($project_manager_list as $pm ){
			if(array_key_exists($user_id,$pms_averg_old)){
				$size = count($pms_averg_old[$user_id]);
				if($size > 0){ 
					$pm_average = array_sum($pms_averg_old[$user_id]) / $size;
					arsort($pms_averg_old[$user_id],1);
					$pm_long_day =  max($pms_averg_old[$user_id]);
					$pm_short_day_day =  min($pms_averg_old[$user_id]);
					$pm_short_day_day = 1;
					//$pm_name = $pm->user_first_name;

					$total_string .= '<div class=\'row\'><span class=\'col-xs-8\'>'.$pm_set_name.'</span><span class=\'col-xs-4\'>'.round($pm_average,1).'</span></div>';
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
		$date_d = "31/12/".$last_year;


		$total_string .= '<div class=\'row\'> &nbsp; ('.$c_year.')</div>';

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

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';





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
						$personal_data = $personal_data + $display_each_value;


						// $personal_data = $personal_data + $display_each_value;
					}
				}
 
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($last_year,2).'</span></div>';
			}




		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';
	}
 














	public function focus_get_po_widget_mn(){
		$focus_company_maintenence_q = $this->dashboard_m->get_focus_companies_mntnc();
		$focus_company_maintenence = $focus_company_maintenence_q->result();

		$maintenacne_data = array(); 
		foreach ($focus_company_maintenence as $maintenance_data){
			$maintenacne_data[$maintenance_data->focus_company_id] = 0;
			$pm_id = $maintenance_data->project_manager_id;
		}


		$year = date("Y");
		$current_date = '01/01/2030';
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



		$total_string = '';
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'>&nbsp;('.$year.')</div>';

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

				$comp_id = $row['focus_company_id'];

				$set_cpo[$comp_id] = $set_cpo[$comp_id] + $out_standing;
			}
		}


		foreach ($focus_company as $company){
			$display_total_cmp = $set_cpo[$company->company_id];
			if($display_total_cmp > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cmp,2).'</span></div>';
			}
			$set_cpo[$company->company_id] = 0;
		}



		$last_year = intval(date("Y"))-1;
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';

		$date_a_last = "01/01/$last_year";
		$date_b_last = "31/12/$last_year";
 
		$po_list_ordered = $this->purchase_order_m->get_po_list_order_by_project($current_start_year,$date_b_last);
		foreach ($po_list_ordered->result_array() as $row){
			$work_id = $row['works_id'];

			$po_tot_inv_q = $this->purchase_order_m->get_po_total_paid($work_id);
			$invoiced = 0;
			foreach ($po_tot_inv_q->result_array() as $po_tot_row){
				$invoiced = $po_tot_row['total_paid'];
			}

			$out_standing = $row['price'] - $invoiced;

			if($pm_id == $row['project_manager_id']){
				//$personal_data = $personal_data + $out_standing;
				$comp_id = $row['focus_company_id'];

				$set_cpo[$comp_id] = $set_cpo[$comp_id] + $out_standing;
			}
		}


		foreach ($focus_company as $company){
			$display_total_cmp = $set_cpo[$company->company_id];
			if($display_total_cmp > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cmp,2).'</span></div>';
			}
		}






		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';
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


		$total_string .= '<div class=\'row\'> &nbsp; ('.$year.')</div>';

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
		$personal_data = 0;

	 	$q_projects = $this->dashboard_m->get_unaccepted_projects($current_start_year,$current_date);
		$projects = $q_projects->result();
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
				}
			}
		}

		//var_dump($project_cost);

		foreach ($focus_arr as $comp_id => $value ){
			$display_total_cmp = $project_cost[$comp_id];
			if($display_total_cmp > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cmp,2).'</span></div>';
			}
			$project_cost[$comp_id] = 0;
		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';


		foreach ($estimator_list as $est ) {
			$display_total_cmp = $unaccepted_amount[$est->project_estiamator_id];
			$pm_name = 'Maintenance';
			if($display_total_cmp > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name.'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cmp,2).'</span></div>';
			}
			$unaccepted_amount[$est->project_estiamator_id] = 0;
		}





		$last_year = intval(date("Y"))-1;
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';

		$date_a_last = "01/01/$last_year";
		$date_b_last = "31/12/$last_year";

		$n_month = date("m");
		$n_day = date("d");
		$date_last_year_today = "$n_day/$n_month/$last_year";


	 	$q_projects = $this->dashboard_m->get_unaccepted_projects($date_a_last,$date_last_year_today);
		$projects = $q_projects->result();
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
/*

					if($pm_id == $un_accepted->project_manager_id){
						$personal_data = $personal_data + $amnt;
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


	public function focus_top_ten_con_sup_mn($type,$pie=''){
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
			
			$comp_name = $company->company_name;
			if($pie == ''){
				echo '<div class="col-sm-8 col-md-7"><i class="fa fa-chevron-circle-right"></i>  &nbsp; ';
		
				if(strlen($comp_name) > 30){
					echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$comp_name.'">'.substr($comp_name,0,30).'...</span>';
				}else{
					echo $comp_name;
				}
			}

			$cmp_id = $company->company_id;

			$last_year_q = $this->dashboard_m->get_company_sales('',$last_start_year,$base_year,$cmp_id,$comp_q);
			$last_year_sale = array_shift($last_year_q->result_array());
			$lst_year_total = $last_year_sale['total_price'];

			if($pie != ''){ 
				echo "['". str_replace("'","&apos;",$comp_name)."', ".$company->total_price."],";

			}else{
				echo ' </div><div class="col-md-2 col-sm-4"><strong>'.number_format($percent,1).'%</strong></div>  <div class="col-md-3 col-sm-4 tooltip-test" title="" data-placement="left" data-original-title="Last Year : $ '.number_format($lst_year_total).'"><i class="fa fa-usd"></i> '.number_format($company->total_price).'</div><div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';
			}
		}
	}


	public function focus_top_ten_clients_mn($is_pie = '',$year_set='',$is_mr=''){
		$focus_company_maintenence_q = $this->dashboard_m->get_focus_companies_mntnc();
		$focus_company_maintenence = $focus_company_maintenence_q->result();

		$maintenacne_data = array(); 
		foreach ($focus_company_maintenence as $maintenance_data){
			$maintenacne_data[$maintenance_data->focus_company_id] = 0;
			$pm_id = $maintenance_data->project_manager_id;
		}

		$comp_q = " AND `project`.`job_category` = 'Maintenance' ";

		$current_date = date("d/m/Y");
	//	$year = date("Y");




$current_year = date("Y");



if($year_set != ''){
	if($current_year == $year_set){
		$current_date = date("d/m/Y");
		$year = date("Y");
		$last_year = intval(date("Y")) - 1;		
	}else{
		$current_date = '31/12/'.$year_set;
		$year = $year_set;
		$last_year = $year_set -1;
	}
}else{
		$current_date = date("d/m/Y");
		$year = date("Y");
		$last_year = intval(date("Y")) - 1;	

}




		// if($year_set != ''){
		// 	$year = $year_set;
		// 	$current_date = date("d/m/").$year_set;
		// }

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

		// $last_year = intval(date("Y")) - 1;
		$this_month = date("m");
		$this_day = date("d");

		// if($year_set != ''){
		// 	$last_year = intval($year_set)-1;
		// //	$current_date = date("d/m/").$year_set;
		// }


		$comp_total = array();
		$comp_name = array();

		foreach ($client_details as $company) {
			$q_vr_c = $this->dashboard_m->client_vr_value($current_start_year,$current_date,$company->client_id,$comp_q);
			$vr_val = array_shift($q_vr_c->result_array());
			$cost_gtotl_amnt = round($company->grand_total+ $vr_val['total_variation']);
			$comp_total[$company->company_id] = $cost_gtotl_amnt;
		//	$comp_name[$company->company_id] = $company->company_name;
			$comp_name[$company->company_id] = $company->company_name_group;

		}

		arsort($comp_total);

		$date_a_last = "01/01/$last_year";
		$date_b_last = "$this_day/$this_month/$last_year";

		if(count($comp_total) == 0 && $report_sheet != 'pie'){
			echo "<p><center><strong>No Records Yet.</strong></csnter></p>";
		}

		foreach ($comp_total as $raw_id => $compute_amount) {
			$percent = round(100/($list_total/$compute_amount),1);


			if($is_pie == ''){
				echo '<div class="mr_comp_name col-sm-6 col-md-7"><i class="fa fa-chevron-circle-right"></i>  &nbsp; ';
			}

			$q_vr_c = $this->dashboard_m->client_vr_value($current_start_year,$current_date,$raw_id,$comp_q);
			$vr_val = array_shift($q_vr_c->result_array());

			$company_name = $comp_name[$raw_id];
			$company_name_group = $comp_name[$raw_id];

			if($is_pie == ''){
				if(strlen($company_name_group) > 30){
					echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$company_name_group.'">'.substr($company_name_group,0,30).'...</span>';
				}else{
					echo $company_name_group;
				}
			}

			$q_vr_c_u = $this->dashboard_m->client_vr_value($date_a_last,$current_start_year,$raw_id,$comp_q);
			$vr_val_u = array_shift($q_vr_c_u->result_array());

			$last_year_q = $this->dashboard_m->get_top_ten_clients($date_a_last, $current_start_year,$raw_id);
			$last_year_sale = array_shift($last_year_q->result_array());
			$lst_year_total = $last_year_sale['grand_total'] + $vr_val_u['total_variation'];
			

			if($is_pie != ''){
				echo "['". str_replace("'","&apos;",$company_name_group)."', ".$compute_amount."],";
			}else{
				echo ' </div><div class="mr_comp_val col-sm-4 col-md-2"><strong>'.number_format($percent,1).'%</strong></div>  <div class="mr_comp_val col-sm-4 col-md-3 tooltip-test" title="" data-placement="left" data-original-title="Last Year : $ '.number_format($lst_year_total).'"><i class="fa fa-usd"></i> '.number_format($compute_amount).'</div><div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';
			}


		}
	}





	public function focus_projects_count_widget_mn(){

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


		$last_year = intval(date("Y"))-1;
		$n_month = date("m");
		$n_day = date("d");

		$date_a_last = "01/01/$last_year";
		$date_b_last = "$n_day/$n_month/$last_year";



		$focus_company_maintenence_q = $this->dashboard_m->get_focus_companies_mntnc();
		$focus_company_maintenence = $focus_company_maintenence_q->result();

		$maintenacne_data = array(); 
		foreach ($focus_company_maintenence as $maintenance_data){
			$maintenacne_data[$maintenance_data->focus_company_id] = 0;
			$pm_id = $maintenance_data->project_manager_id;
		}




		foreach ($focus_company as $company) {
			$focus_arr[$company->company_id] = $company->company_name;
			$focus_comp_wip_count[$company->company_id] = 0;

			$invoiced = 0;
			$invoiced_old = 0;

			$projects_qa = $this->dashboard_m->get_wip_invoiced_projects($current_start_year, $next_year_date, $company->company_id);
			$projects_ra = $projects_qa->result_array();

			foreach ($projects_ra as $result) {

				if($result['job_category'] == 'Maintenance' && $this->invoice->if_invoiced_all($result['project_id'])  && $this->invoice->if_has_invoice($result['project_id']) > 0 ){
					$invoiced++;
				}
			}

			$projects_qb = $this->dashboard_m->get_wip_invoiced_projects($date_a_last, $date_b_last, $company->company_id);
			$projects_rb = $projects_qb->result_array();

			foreach ($projects_rb as $result) {
				if($result['job_category'] == 'Maintenance' && $this->invoice->if_invoiced_all($result['project_id'])  && $this->invoice->if_has_invoice($result['project_id']) > 0 ){
					$invoiced_old++;
				}
			}

			$focus_invoiced[$company->company_id] = $invoiced;
			$focus_invoiced_old[$company->company_id] = $invoiced_old;
		}

		$display_inv = array_sum($focus_invoiced);

		$total_string_wip = '';
		$total_string_inv = '';

		$total_string_wip .= '('.$year.') WIP Count'; 
		$total_string_inv .= '('.$year.') Invoiced Count';

		foreach ($focus_arr as $comp_id => $value ){
			if($focus_invoiced[$comp_id] > 0){
				$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-7\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-5\'>'.number_format($focus_invoiced[$comp_id]).'</span></div>';
			}
		}

		$lat_old_year = $year-1;
		$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$lat_old_year.')</div>';



		$q_maps = $this->dashboard_m->get_map_projects($current_start_year,$current_date,$pm_id);
		$map_details = $q_maps->result();
		foreach ($map_details as $map) {
		//	$personal_data_b++;
			$focus_comp_wip_count[$map->focus_company_id]++;
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



/*
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

		$total_string_wip .= '<div class=\'row\'>&nbsp; ('.$year.') &nbsp; WIP Count</div>';
		$total_string_inv .= '<div class=\'row\'>&nbsp; ('.$year.') &nbsp; Invoiced Count</div>';

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

		$lat_old_year = $year-1;
		$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$lat_old_year.')</div>';

	 
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
*/
	}




	public function focus_projects_by_type_widget_mn($is_pie=''){

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



			if($is_pie != ''){

				echo "['".str_replace("'","&apos;",$value)."',".$cost."],";

			}else{

				echo '<div id="" class="clearfix"><p><span class="col-sm-7"><i class="fa fa-chevron-circle-right"></i> &nbsp; '.$value.'</span><strong class="col-sm-5">$ '.number_format($cost).'</strong></p></div>';
				echo '<div class="col-md-12"><hr class="block m-bottom-5 m-top-5"></div>';
			}

			 
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


	public function invoiced_pm($assign_id=''){
		$pm_data = $this->dashboard_m->fetch_project_pm_nomore();
		$pm_q = array_shift($pm_data->result_array());
		$not_pm_arr = explode(',',$pm_q['user_id'] );

		if(isset($assign_id) && $assign_id != '' && $assign_id > 0){
			$user_id = $assign_id;
		}else{
			$user_id = $this->session->userdata('user_id');
		}

		$pm_type = $this->pm_type($user_id);

		$fetch_user = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($fetch_user->result_array());

		if($pm_type == 1){ // for director/pm
			$direct_company = explode(',',$user_details['direct_company'] );
		}

		if($pm_type == 2){ // for pm only
			$direct_company = explode(',',$user_details['user_focus_company_id'] );
		}

	//	var_dump($direct_company);


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
		$total_string .= '<div class=\'row\'>  &nbsp; ('.$c_year.')</div>';

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

							$grand_total_sales = $grand_total_sales + $sales_total;

							if($user_id == $sales->project_manager_id){
								$personal_data = $personal_data + $sales_total;
							}
						}
					}

					$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($grand_total_sales,2).'</span></div>';
				}
			}
		}


		$last_year = intval(date("Y"))-1;
		$n_month = date("m");
		$n_day = date("d");
		$date_a_last = "01/01/$last_year";
		$date_b_last = "$n_day/$n_month/$last_year";
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';

		foreach ($focus_company as $company){
			if( in_array($company->company_id, $direct_company) ){
				$q_dash_sales = $this->dashboard_m->dash_sales($date_a_last,$date_b_last,$company->company_id,1);

			//	if($q_dash_sales->num_rows >= 1){
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
			//	}
			}
		}
		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';
	}

	public function pm_estimates_widget_pm($assign_id=''){
		if(isset($assign_id) && $assign_id != '' && $assign_id != 0){
			$user_id = $assign_id;
		}else{
			$user_id = $this->session->userdata('user_id');
		}

		$pm_type = $this->pm_type($user_id); 

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
		$total_string = '('.$year.')';
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
			if( !in_array($un_accepted->project_manager_id, $not_pm_arr)      &&   in_array($un_accepted->focus_company_id, $direct_company)       ){

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
					if( in_array($un_accepted->focus_company_id, $direct_company) &&  $user_id == $un_accepted->project_manager_id   ){
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

			$project_cost[$comp_id] = 0;
		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';





		foreach ($estimator_list as $est ) {
			$display_total_cmp = $unaccepted_amount[$est->project_estiamator_id];
			$pm_name = $est->user_first_name;
			if($display_total_cmp > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name.'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cmp,2).'</span></div>';
			}

			$unaccepted_amount[$est->project_estiamator_id] = 0;
		}



 


		$last_year = intval(date("Y"))-1;
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';

		$n_month = date("m");
		$n_day = date("d");
		$date_last_year_today = "$n_day/$n_month/$last_year";


	 	$q_projects = $this->dashboard_m->get_unaccepted_projects("01/01/$last_year",$date_last_year_today);
		$projects = $q_projects->result();
		foreach ($projects as $un_accepted){

			if( !in_array($un_accepted->project_manager_id, $not_pm_arr)  &&   in_array($un_accepted->focus_company_id, $direct_company)     ){

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


					if( in_array($un_accepted->focus_company_id, $direct_company)    &&  $user_id == $un_accepted->project_manager_id   ){
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
		}



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


	public function focus_get_po_widget_pm($assign_id=''){
		
		if(isset($assign_id) && $assign_id != '' && $assign_id != 0){
			$user_id = $assign_id;
		}else{
			$user_id = $this->session->userdata('user_id');
		}

		$pm_type = $this->pm_type($user_id); 

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

		$total_string = '';
		$po_list_ordered = $this->purchase_order_m->get_po_list_order_by_project($current_start_year,$current_date);
		
		$total_string .= '<div class=\'row\'> &nbsp; ('.$year.')</div>';

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


		foreach ($focus_arr as $comp_id => $value ){
			if( in_array($comp_id, $direct_company) ){
				$display_total_cpo = $set_cpo[$comp_id];

				if($display_total_cpo > 0){
					$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cpo,2).'</span></div>';
				}

				$set_cpo[$comp_id] = 0;
			}
		}

		$last_year = intval(date("Y"))-1;
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';

		$n_month = date("m");
		$n_day = date("d");
		$date_last_year_today = "$n_day/$n_month/$last_year";
		$set_date_b = '01/01/'.$last_year;
	 

		$po_list_ordered = $this->purchase_order_m->get_po_list_order_by_project($set_date_b,$date_last_year_today);
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
		
		foreach ($focus_arr as $comp_id => $value ){
			if( in_array($comp_id, $direct_company) ){
				$display_total_cpo = $set_cpo[$comp_id];
				if($display_total_cpo > 0){
					$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cpo,2).'</span></div>';
				}
			}
		}

		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';
	}

	public function focus_projects_count_widget_pm($assign_id=''){
		if(isset($assign_id) && $assign_id != ''  && $assign_id != 0){
			$user_id = $assign_id;
		}else{
			$user_id = $this->session->userdata('user_id');
		}

		$pm_type = $this->pm_type($user_id);

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

		$total_string_wip = '('.$year.') WIP Count'; 
		$total_string_inv = '('.$year.') Invoiced Count';

		foreach ($focus_arr as $comp_id => $value ){
			if($focus_invoiced[$comp_id] > 0){
				if( in_array($comp_id, $direct_company) ){
					$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-7\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-5\'>'.number_format($focus_invoiced[$comp_id]).'</span></div>';
				}
			}
		}

		$lat_old_year = $year-1;
		$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$lat_old_year.')</div>';

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

		$project_manager = $this->dashboard_m->fetch_pms_year(date("Y")); // ****--___--***
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
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';


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
		}


		echo '<div id="" class="tooltip-enabled" title="" data-placement="bottom" data-html="true" data-original-title="'.$total_string .'">
			<input class="knob" data-width="100%" data-step=".1"  data-thickness=".13" value="'.number_format($average,1).'" readonly data-fgColor="#964dd7" data-angleOffset="-180"  data-max="'.$long_day.'">
			<div id="" class="clearfix m-top-10"><div id="" class="col-xs-6"><strong><p>'.$short_day_day.' min</p></strong></div><div id="" class="col-xs-6 text-right"><strong><p>max '.$long_day.'</p></strong></div></div></div>';

	}








	public function average_date_invoice_pm($pm_data_id='',$report_year=''){

		$user_id = ($pm_data_id == '' ||  $pm_data_id == 0 ? $this->session->userdata('user_id') : $pm_data_id);
		$fetch_user = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($fetch_user->result_array());
		$pm_name_solo = $user_details['user_first_name'];

 
		if($pm_data_id != ''){
			if($user_details['user_role_id'] == 3 && $user_details['user_department_id'] == 1):
				$pm_type = 1;
			endif; //for directors 

			if($user_details['user_role_id'] == 3 && $user_details['user_department_id'] == 4): //for PM 
				$pm_type = 2;
			endif; //for PM 

			if($user_details['user_role_id'] == 20 && $user_details['user_department_id'] == 4): //for PM 
				$pm_type = 2;
			endif; //for PM or AM

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


		
		if($report_year != ''){
			$c_year = $report_year;

			$date_a = "01/01/$c_year";
			$date_b = date("d/m/").$c_year;
		}else{
			$c_year = date("Y");

			$date_a = "01/01/$c_year";
			$date_b = date("d/m/Y");

		}


		$days_dif = array();

		$long_day = 0;
		$size = 0;
		$short_day_day = 0;




		$project_manager = $this->dashboard_m->fetch_pms_year(date("Y")); // ****--___--***
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
					$size = count($pms_averg[$pm->user_id]);

					$pm_average = array_sum($pms_averg[$pm->user_id]) / $size;
					arsort($pms_averg[$pm->user_id],1);
					$pm_long_day =  max($pms_averg[$pm->user_id]);
					$pm_short_day_day =  min($pms_averg[$pm->user_id]);
					$pm_short_day_day = 1;
					$pm_name = $pm->user_first_name;
					$total_string .= '<div class=\'row\'><span class=\'col-xs-8\'>'.$pm_name.'</span><span class=\'col-xs-4\'>'.round($pm_average,1).'</span></div>';
				}
			}
		}

		$this_month = date("m");
		$this_day = date("d");


		if($report_year != ''){
			$last_year = intval($report_year) - 1;

		}else{
			$last_year = intval(date("Y")) - 1;


		}

		$date_a_last = "01/01/$last_year";
		$date_b_last = "$this_day/$this_month/$last_year";


		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';


		$pms_averg_old = array();	 
		foreach ($project_manager_list as $pm ) {
			$pms_averg_old[$pm->user_id] = array();			 
		}

		foreach ($focus_company as $company){

			if( in_array($company->company_id, $direct_company) ){

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
		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';




	if($pm_type == 1){ // for director/pm

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
		}
	}else{

		if(array_key_exists($user_id,$pms_averg_old)){
				$size = count($pms_averg_old[$user_id]);
				if($size > 0){ 
					$pm_average = array_sum($pms_averg_old[$user_id]) / $size;
					arsort($pms_averg_old[$user_id],1);
					$pm_long_day =  max($pms_averg_old[$user_id]);
					$pm_short_day_day =  min($pms_averg_old[$user_id]);
					$pm_short_day_day = 1;

					$total_string .= '<div class=\'row\'><span class=\'col-xs-8\'>'.$pm_name_solo.'</span><span class=\'col-xs-4\'>'.round($pm_average,1).'</span></div>';
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


	public function uninvoiced_widget_pm($assign_id=''){

		if(isset($assign_id) && $assign_id != '' && $assign_id > 0){
			$user_id = $assign_id;
		}else{
			$user_id = $this->session->userdata('user_id');
		}

		$pm_type = $this->pm_type($user_id);

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


		$total_string .= '<div class=\'row\'> &nbsp; ('.$c_year.')</div>';

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

				//if($unvoiced_grand_total > 0){
					$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($unvoiced_grand_total,2).'</span></div>';
				//}
			}

		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';

		$project_manager = $this->dashboard_m->fetch_pms_year(date("Y")); // ****--___--***
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


		$last_year = intval(date("Y"))-1;
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';

		$date_a_last = "01/01/$last_year";
		$date_b_last = "31/12/$last_year";

		$n_month = date("m");
		$n_day = date("d");
		$date_last_year_today = "$n_day/$n_month/$last_year";

		foreach ($project_manager_list as $pm ) {
			$total_outstanding = 0;

			if( in_array($pm->user_focus_company_id, $direct_company) ){
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

				if($user_id == $pm->user_id){
					$personal_data = $personal_data + $total_outstanding;
				}
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm->user_first_name.'</span> <span class=\'col-xs-6\'>$ '.number_format($total_outstanding,2).'</span></div>';
			}
		}

		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';
	}



	public function wip_widget_pm($assign_id=''){
		
		if(isset($assign_id) && $assign_id != '' && $assign_id > 0){
			$user_id = $assign_id;
		}else{
			$user_id = $this->session->userdata('user_id');
		}

		$pm_type = $this->pm_type($user_id);

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



	public function outstanding_payments_widget_pm($assign_id=''){  
		
		if(isset($assign_id) && $assign_id != '' && $assign_id > 0){
			$user_id = $assign_id;
		}else{
			$user_id = $this->session->userdata('user_id');
		}

		$pm_type = $this->pm_type($user_id);

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


		$project_manager = $this->dashboard_m->fetch_pms_year(date("Y")); // ****--___--***
		$project_manager_list = $project_manager->result();

		foreach ($project_manager_list as $pm ) {
			$pm_outstanding[$pm->user_id] = 0;
		}

		$each_comp_total = array();

		$total_string .= '<div class=\'row\'> &nbsp; ('.$c_year.')</div>';
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
					$total_paid =  $oustanding->amount_exgst;
					$display_each_value = $invoice_amount - $total_paid;
					$pm_outstanding[$oustanding->project_manager_id] = $pm_outstanding[$oustanding->project_manager_id] + $display_each_value;
					$each_comp_total[$oustanding->focus_company_id] = $each_comp_total[$oustanding->focus_company_id] + $display_each_value;
				}


				if (array_key_exists($user_id, $pm_outstanding)) {

					$personal_data = $pm_outstanding[$user_id];

					$total_outstanding =  $each_comp_total[$company->company_id];
					if($total_outstanding > 0){
						$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($total_outstanding,2).'</span></div>';
					}


				}


			}
		}


		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';
		foreach ($project_manager_list as $pm ) {
			$pm_name = $pm->user_first_name;
			$amount = $pm_outstanding[$pm->user_id];
			if( $amount > 0){

				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name.'</span> <span class=\'col-xs-6\'>$ '.number_format($amount,2).'</span></div>';
			}

		}



		for ($i=date("Y"); $i>=2015 ; $i--) { 
			if($i != date("Y")){ 
				$c_year = $i;
				$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$c_year.')</div>';
				
						foreach ($project_manager_list as $pm ) {
							$pm_outstanding[$pm->user_id] = 0;
						}

			//	echo "XXXtest****";

				$date_a = "01/01/$c_year";
				$date_b = "31/12/$c_year";

				foreach ($focus_company as $company){

					if( in_array($company->company_id, $direct_company) ){
						$each_comp_total[$company->company_id] = 0;
						$invoice_amount= 0;
						$total_invoice= 0;
						$total_paid = 0;
						$total_outstanding = 0;
						$key_id = '';
						$outstanding = 0;
						$q_dash_oustanding_payments = $this->dashboard_m->dash_oustanding_payments($date_a,$date_b,$company->company_id);
						$oustanding_payments = $q_dash_oustanding_payments->result();
						




/*
						foreach ($oustanding_payments as $oustanding) {

							if($oustanding->label == 'VR'){
								$invoice_amount = $oustanding->variation_total;
							}else{
								$invoice_amount = $oustanding->project_total*($oustanding->progress_percent/100);
							}
							$total_paid =  $oustanding->amount_exgst;
							$display_each_value = $invoice_amount - $total_paid;
							$pm_outstanding[$oustanding->project_manager_id] = $pm_outstanding[$oustanding->project_manager_id] + $display_each_value;
							$each_comp_total[$oustanding->focus_company_id] = $each_comp_total[$oustanding->focus_company_id] + $display_each_value;



						}
*/
						foreach ($oustanding_payments as $oustanding) {
							if($oustanding->label == 'VR'){
								$invoice_amount = $oustanding->variation_total;
							}else{
								$invoice_amount = $oustanding->project_total*($oustanding->progress_percent/100);
							}

							$outstanding = $this->invoice->get_current_balance($oustanding->project_id,$oustanding->invoice_id,$invoice_amount);




							if (array_key_exists($oustanding->project_manager_id, $pm_outstanding)) {
								$pm_outstanding[$oustanding->project_manager_id] = $pm_outstanding[$oustanding->project_manager_id] + $outstanding;
							}




							$each_comp_total[$oustanding->focus_company_id] = $each_comp_total[$oustanding->focus_company_id] + $outstanding;
						}

						$total_outstanding =  $each_comp_total[$company->company_id];
					//	if($total_outstanding > 0){
							$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($total_outstanding,2).'</span></div>';
					//	}
						$each_comp_total[$company->company_id] = 0;
					}
				}


				$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';
				foreach ($project_manager_list as $pm ){
					$pm_name = $pm->user_first_name;





					if (array_key_exists($pm->user_id, $pm_outstanding)) {



						$amount = $pm_outstanding[$pm->user_id];
						
						if( $amount > 0){
							$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name.'</span> <span class=\'col-xs-6\'>$ '.number_format($amount,2).'</span></div>';
						//$personal_data = $personal_data + $amount;
						}

					}

					//$pm_outstanding[$pm->user_id] = 0;
				}



				if (array_key_exists($user_id, $pm_outstanding)) {
					$personal_data = $personal_data + $pm_outstanding[$user_id];
				}else{
					$personal_data = $personal_data + 0;
				}
 
			}
		}

		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($personal_data,2).'</strong></p>';
}


	public function pm_sales_widget_pm($assign_id='',$is_thermo=''){
		if(isset($assign_id) && $assign_id != '' &&  $assign_id > 0){
			$user_id = $assign_id;
		}else{
			$user_id = $this->session->userdata('user_id');
		}

		$pm_type = $this->pm_type($user_id);

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

		$project_manager = $this->dashboard_m->fetch_pms_year(date("Y")); // ****--___--***
		$project_manager_list = $project_manager->result();

		foreach ($project_manager_list as $pm ) {

			//echo "<p>$pm->user_first_name</p>";

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
		//	var_dump($pm);

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

			// 	echo $comp_id.'<br />';

			foreach ($pm_set_data as $pm_id => $value){
//ion get_wip_perso
				$wip_amount = $this->get_wip_personal($date_a,$set_new_date,$pm_id,$comp_id);
				$wip_pm_total[$pm_id] = $wip_pm_total[$pm_id] + $wip_amount;
				$sales_result[$pm_id] = $sales_result[$pm_id] + $wip_amount + $set_invoiced_amount[$pm_id];

			}

		}


		arsort($sales_result);
		$total_wip = array_sum($wip_pm_total);

		$total_invoiced = array_sum($set_invoiced_amount);

		if($is_thermo == ''){
			echo "<div style=\"overflow-y: auto; padding-right: 5px; height: 400px;\">";
		}

		$pm_overall_display = 0;

	//	foreach ($sales_result as $pm_id => $sales){


		foreach ($project_manager_list as $pm ) {

			$pm_id = $pm->user_id;
			$sales = $sales_result[$pm_id];

		//	if( $sales > 0){

			


			if( in_array($pm->user_focus_company_id, $direct_company) ){
			
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

				$pm_sales_value = ($pm_sales_value == 1 ? 0 : $pm_sales_value);
				if($is_thermo == ''){
					echo '<div class="m-bottom-15 clearfix"><div class="pull-left m-right-10"  style="height: 50px; width:50px; border-radius:50px; overflow:hidden; border: 1px solid #999999;"><img class="user_avatar img-responsive img-rounded" src="'.base_url().'/uploads/users/'.$focus_pm_pic[$pm_id].'"" /></div>';
					echo '<div class="" id=""><p><strong>'.$focus_pms[$pm_id].'</strong><span class="pull-right"><span class="label pull-right m-bottom-3 m-top-3 small_orange_fixed"><i class="fa fa-usd"></i> '.number_format($set_invoiced_amount[$pm_id]).'</span> <br /> <span class="label pull-right m-bottom-3 small_green_fixed"><i class="fa fa-exclamation-triangle"></i> '.number_format($wip_pm_total[$pm_id]).'</span></span></p>';
					echo '<p><i class="fa fa-usd"></i> '.number_format($pm_sales_value).'</p>';

					echo '<div class="progress no-m m-top-3 clearfix tooltip-enabled" title="" data-original-title="'.$status_forecast.'% - $'.number_format($pm_sales_value).' / $'.number_format($total_forecast).'   " style="height: 7px;">
					<div class="progress-bar progress-bar-danger" style="width:'.$status_forecast.'%; background:red;"></div></div></div></div>';

					echo "<div class='clearfix'></div>";
				}


				$forecast_focus_total = $forecast_focus_total + $total_forecast;

				if($user_id == $pm_id){
					$return_total = $status_forecast;
				}
	 		}
		}

		if($is_thermo == ''){
			echo "</div>";
		}

		$forecast_focus_total = ($forecast_focus_total <= 1 ? 1 : $forecast_focus_total);
		//$total_wip = ($total_wip <= 1 ? 1 : $total_wip);

		if($total_wip + $total_invoiced >= 1){
			$status_forecast = round(100/($forecast_focus_total/ ($total_wip + $total_invoiced) ));
		}else{
				$status_forecast = round(100/($forecast_focus_total/ 1 ));
		}

		$comp_code = '';
		if($pm_type == 2 || count($direct_company) === 1){ // for pm only

			if($user_details['user_focus_company_id'] == 5){
				$comp_code = 'WA';
			}

			if($user_details['user_focus_company_id'] == 6){
				$comp_code = 'NSW';
			}
		}

		if($is_thermo == ''){
			echo '<div class="clearfix" style="padding-top: 6px;    border-top: 1px solid #eee;"><i class="fa fa-briefcase" style="font-size: 42px;float: left;margin-left: 7px;margin-right: 10px;"></i>';
			echo '<div class="" id=""><p><strong>Overall Focus '.$comp_code.'</strong><span class="pull-right"><span class="label pull-right m-bottom-3 m-top-3 small_orange_fixed"><i class="fa fa-usd"></i> '.number_format($grand_total_sales_cmp).'</span> <br /> <span class="label pull-right m-bottom-3 small_green_fixed"><i class="fa fa-exclamation-triangle"></i> '.number_format($total_wip).'</span></span></p>';
			echo '<p><i class="fa fa-usd"></i> '.number_format( ($total_wip + $total_invoiced) ).' <strong class="pull-right m-right-10"></strong></p> </p>';

			echo '<div class="progress no-m m-top-3 clearfix tooltip-enabled" title="" data-original-title="'.$status_forecast.'% - $'.number_format( ($total_wip + $total_invoiced) ).' / $'.number_format($forecast_focus_total).'   " style="height: 7px;">
			<div class="progress-bar progress-bar-danger" style="width:'.$status_forecast.'%; background:red;"></div></div></div></div>';
			echo "<div class='clearfix'></div>";
		}
	//	return $return_total;

		if ( array_key_exists($user_id, $set_invoiced_amount)  &&  array_key_exists($user_id, $wip_pm_total) ) {
			$pm_overall_display = $pm_sales_value = $set_invoiced_amount[$user_id] + $wip_pm_total[$user_id]; 
		}
 
		if($is_thermo != ''){
			echo $return_total.'_'.number_format( $pm_overall_display );
		}else{
			return $return_total.'_'.number_format( $pm_overall_display );
		}
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
		$total_string .= '<div class=\'row\'>&nbsp; ('.$c_year.')</div>';

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

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';

		foreach ($focus_company as $company){

		 	if($company->company_id != 4){
			$q_dash_sales = $this->dashboard_m->dash_sales($date_a_last,$date_b_last,$company->company_id,1);

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

		$display_total = 0;


		foreach ($focus_arr as $comp_id => $value ){

			$wip_amount = $this->get_wip_value_permonth($start_date,$set_new_date,$comp_id);

			if($wip_amount > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-6\'>$ '.number_format($wip_amount,2).'</span></div>';
				$display_total = $display_total + $wip_amount;
			}
		}
/*
		$fYear = intval(date("Y"))+2;

		$date_f = "01/01/$fYear";
		$date_ts = '01/01/1990';
		
		$display_total = $this->get_wip_value_permonth($date_ts,$date_f);
*/
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

		$total_string = '<div class=\'row\'> &nbsp; ('.$c_year.')</div>';

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
		$project_manager = $this->dashboard_m->fetch_pms_year(date("Y")); // ****--___--***
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

		$last_year = intval(date("Y"))-1;
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';

		$date_a_last = "01/01/$last_year";
		$date_b_last = "31/12/$last_year";

		$n_month = date("m");
		$n_day = date("d");
		$date_last_year_today = "$n_day/$n_month/$last_year";

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
		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		$total_string = '';
		$display_total = 0;
		$total_string .= '<div class=\'row\'>&nbsp; ('.date("Y").')</div>';

		$total_outstanding_all_time = 0;
 

		for ($i=date("Y"); $i>=2015 ; $i--) { 


			if($i == date("Y")){

				$c_year = $i;
				$n_month = date("m");
				$n_day = date("d");

			//	echo "exst****";

				$date_a = "01/01/$c_year";
				$date_b = "$n_day/$n_month/$c_year";

			//	echo "$date_a***$date_b***<br /><br /><br />";

				foreach ($focus_company as $company){
					if($company->company_id != 4){
						$invoice_amount= 0;
						$outstanding = 0;

						$q_dash_oustanding_payments = $this->dashboard_m->dash_oustanding_payments($date_a,$date_b,$company->company_id);
						$oustanding_payments = $q_dash_oustanding_payments->result();

						foreach ($oustanding_payments as $oustanding) {
							if($oustanding->label == 'VR'){
								$invoice_amount = $oustanding->variation_total;
							}else{
								$invoice_amount = $oustanding->project_total*($oustanding->progress_percent/100);
							}

							$outstanding = $outstanding + $this->invoice->get_current_balance($oustanding->project_id,$oustanding->invoice_id,$invoice_amount);
						}

						if($outstanding != 0){
							$total_outstanding_all_time =  $total_outstanding_all_time + $outstanding;
							$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($outstanding,2).'</span></div><p></p>';
						}

					}
				}

			}else{
				$c_year = $i;
				$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$c_year.')</div>';
				

			//	echo "XXXtest****";

				$date_a = "01/01/$c_year";
				$date_b = "31/12/$c_year";

			//	echo "$date_a***$date_b***<br /><br /><br />";

				foreach ($focus_company as $company){
					if($company->company_id != 4){
						$invoice_amount= 0;
						$outstanding = 0;

						$q_dash_oustanding_payments = $this->dashboard_m->dash_oustanding_payments($date_a,$date_b,$company->company_id);
						$oustanding_payments = $q_dash_oustanding_payments->result();

						foreach ($oustanding_payments as $oustanding) {
							if($oustanding->label == 'VR'){
								$invoice_amount = $oustanding->variation_total;
							}else{
								$invoice_amount = $oustanding->project_total*($oustanding->progress_percent/100);
							}

							$outstanding = $outstanding + $this->invoice->get_current_balance($oustanding->project_id,$oustanding->invoice_id,$invoice_amount);
						}


						if($outstanding != 0){
							$total_outstanding_all_time =  $total_outstanding_all_time + $outstanding;
							$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$company->company_name).'</span> <span class=\'col-xs-6\'>$ '.number_format($outstanding,2).'</span></div><p></p>';
						}
					}
				}
			}
		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';
		$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>All Time</span> <span class=\'col-xs-6\'>$ '.number_format($total_outstanding_all_time,2).'</span></div>';
		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($total_outstanding_all_time,2).'</strong></p>';
	}


	public function pm_sales_widget($termo_val=''){

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

		$project_manager = $this->dashboard_m->fetch_pms_year(date("Y")); // ****--___--***
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
		
		if($termo_val == ''):
			echo "<div style=\"overflow-y: auto; padding-right: 5px; height: 400px;\">";
		endif;

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

			if($termo_val == ''):
				echo '<div class="m-bottom-15 clearfix"><div class="pull-left m-right-10"  style="height: 50px; width:50px; border-radius:50px; overflow:hidden; border: 1px solid #999999;"><img class="user_avatar img-responsive img-rounded" src="'.base_url().'/uploads/users/'.$focus_pm_pic[$pm_id].'"" /></div>';
				echo '<div class="" id=""><p><strong>'.$focus_pms[$pm_id].'</strong><span class="pull-right"><span class="label pull-right m-bottom-3 m-top-3 small_orange_fixed"><i class="fa fa-usd"></i> '.number_format($set_invoiced_amount[$pm_id]).'</span> <br /> <span class="label pull-right m-bottom-3 small_green_fixed"><i class="fa fa-exclamation-triangle"></i> '.number_format($wip_pm_total[$pm_id]).'</span></span></p>';
				echo '<p><i class="fa fa-usd"></i> '.number_format($sales).'</p>';

				echo '<div class="progress no-m m-top-3 clearfix tooltip-enabled" title="" data-original-title="'.$status_forecast.'% - $'.number_format($sales).' / $'.number_format($total_forecast).'   " style="height: 7px;">
			<div class="progress-bar progress-bar-danger" style="width:'.$status_forecast.'%; background:red;"></div></div></div></div>';

				echo "<div class='clearfix'></div>";
			endif;

			$forecast_focus_total = $forecast_focus_total + $total_forecast;
		}

		if($termo_val == ''):
			echo "</div>";
		endif;
 
		$total_wip = ($total_wip <= 1 ? 1 : $total_wip);
		$forecast_focus_total = ($forecast_focus_total <= 1 ? 1 : $comp_forecast['total']);

		$status_forecast = round(100/($forecast_focus_total/ ($total_wip + $total_invoiced) ));


		if($termo_val == ''):
			echo '<div class="clearfix" style="padding-top: 6px;    border-top: 1px solid #eee;"><i class="fa fa-briefcase" style="font-size: 42px;float: left;margin-left: 7px;margin-right: 10px;"></i>';
			echo '<div class="" id=""><p><strong>Overall Focus</strong><span class="pull-right"><span class="label pull-right m-bottom-3 m-top-3 small_orange_fixed"><i class="fa fa-usd"></i> '.number_format($grand_total_sales_cmp).'</span> <br /> <span class="label pull-right m-bottom-3 small_green_fixed"><i class="fa fa-exclamation-triangle"></i> '.number_format($total_wip).'</span></span></p>';
			echo '<p><i class="fa fa-usd"></i> '.number_format( ($total_wip + $total_invoiced) ).' <strong class="pull-right m-right-10"></strong></p> </p>';

			echo '<div class="progress no-m m-top-3 clearfix tooltip-enabled" title="" data-original-title="'.$status_forecast.'% - $'.number_format( ($total_wip + $total_invoiced) ).' / $'.number_format($forecast_focus_total).'   " style="height: 7px;">
			<div class="progress-bar progress-bar-danger" style="width:'.$status_forecast.'%; background:red;"></div></div></div></div>';
			echo "<div class='clearfix'></div>";
		else:
			echo $status_forecast.'_'.number_format( ($total_wip + $total_invoiced) );
			
		endif;

		return $status_forecast.'_'.number_format( ($total_wip + $total_invoiced) );

	}





	public function pm_estimates_widget($es_id = ''){
		$year = date("Y");
		$current_date = date("d/m/Y");
		$current_start_year = '01/01/'.$year;

		$total_string = '';
		$is_restricted = 0;

		$estimator_value_counter = array();


		$total_string .= '<div class=\'row\'>&nbsp; ('.$year.')</div>';

		$admin_defaults = $this->admin_m->fetch_admin_defaults(1);
		foreach ($admin_defaults->result() as $row){
			$unaccepted_date_categories = $row->unaccepted_date_categories;
			$unaccepted_no_days = $row->unaccepted_no_days;
		}


		$pm_data = $this->dashboard_m->fetch_project_pm_nomore();
		$pm_q = array_shift($pm_data->result_array());
		$not_pm_arr = explode(',',$pm_q['user_id'] );


		$project_manager = $this->dashboard_m->fetch_pms_year(date("Y")); // ****--___--***
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
			$estimator_value_counter[$est->project_estiamator_id] = 0;
		}

		
		if($es_id != ''){
			$unaccepted_amount[$es_id] = 0;
			$estimator_value_counter[$es_id] = 0;
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


					if( isset($pm_split[$un_accepted->project_manager_id])) {
						$pm_split[$un_accepted->project_manager_id] = $pm_split[$un_accepted->project_manager_id] + $amnt;
					}
					
					if( isset($unaccepted_amount[$un_accepted->project_estiamator_id])) {
						$unaccepted_amount[$un_accepted->project_estiamator_id] = $unaccepted_amount[$un_accepted->project_estiamator_id] + $amnt;
						$estimator_value_counter[$un_accepted->project_estiamator_id]++;
					}




				}
			}
		}

		//var_dump($project_cost);

		$display_total = array_sum($project_cost); 
		foreach ($focus_arr as $comp_id => $value ){
			$display_total_cmp = $project_cost[$comp_id];
			if($display_total_cmp > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cmp,2).'</span></div>';
			}
			$project_cost[$comp_id] = 0;
		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';



		if($es_id != ''){
			$display_total = $unaccepted_amount[$es_id];
			$display_counter = $estimator_value_counter[$es_id];
		}else{
			$display_counter = '';
		}

		foreach ($project_manager_list as $pm ) {
			$display_total_cmp = $pm_split[$pm->user_id];
			$pm_name = $pm->user_first_name;
			if($display_total_cmp > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name.'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cmp,2).'</span></div>';
			}
			$pm_split[$pm->user_id] = 0;
		}


		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';






		foreach ($estimator_list as $est ) {
			$display_total_cmp = $unaccepted_amount[$est->project_estiamator_id];
			$pm_name = $est->user_first_name;
			if($display_total_cmp > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name.'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cmp,2).'</span></div>';
			}
			$unaccepted_amount[$est->project_estiamator_id] = 0;
		}


		$last_year = intval(date("Y"))-1;
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';

		$n_month = date("m");
		$n_day = date("d");
		$date_last_year_today = "$n_day/$n_month/$last_year";


	 	$q_projects = $this->dashboard_m->get_unaccepted_projects("01/01/$last_year",$date_last_year_today);
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



		foreach ($focus_arr as $comp_id => $value ){
			$display_total_cmp = $project_cost[$comp_id];
			if($display_total_cmp > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cmp,2).'</span></div>';
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

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span></div>';





		foreach ($estimator_list as $est ) {
			$display_total_cmp = $unaccepted_amount[$est->project_estiamator_id];
			$pm_name = $est->user_first_name;
			if($display_total_cmp > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$pm_name.'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cmp,2).'</span></div>';
			} 
		}

 
		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($display_total,2).' <span class="pull-right">'.$display_counter.'</span></strong></p>';
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


		$last_year = intval(date("Y"))-1;
		$n_month = date("m");
		$n_day = date("d");

		$date_a_last = "01/01/$last_year";
		$date_b_last = "$n_day/$n_month/$last_year";



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

			$projects_qb = $this->dashboard_m->get_wip_invoiced_projects($date_a_last, $date_b_last, $company->company_id);
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

		$total_string_wip .= '('.$year.') WIP Count'; 
		$total_string_inv .= '('.$year.') Invoiced Count';

		foreach ($focus_arr as $comp_id => $value ){
			if($focus_invoiced[$comp_id] > 0){
				$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-7\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-5\'>'.number_format($focus_invoiced[$comp_id]).'</span></div>';
			}
		}

		$lat_old_year = $year-1;
		$total_string_inv .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$lat_old_year.')</div>';
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

	public function focus_get_map_locations_pm($assign_id=''){
		if(isset($assign_id) && $assign_id != ''){
			$user_id = $assign_id;
		}else{
			$user_id = $this->session->userdata('user_id');
		}

		$pm_type = $this->pm_type($user_id);

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

		$set_date_a = '01/01/'.$year;

		$focus_arr = array();
		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();

		foreach ($focus_company as $company){
			$focus_arr[$company->company_id] = $company->company_name;
			$set_cpo[$company->company_id] = 0;
		}
		$total_string = "($year)";

		$po_list_ordered = $this->purchase_order_m->get_po_list_order_by_project($set_date_a,$current_date);
		foreach ($po_list_ordered->result_array() as $row){
			$work_id = $row['works_id'];

			$po_tot_inv_q = $this->purchase_order_m->get_po_total_paid($work_id);
			$invoiced = 0;
			foreach ($po_tot_inv_q->result_array() as $po_tot_row){
				$invoiced = $po_tot_row['total_paid'];
			}

			$out_standing = $row['price'] - $invoiced;

			$comp_id = $row['focus_company_id'];
			if(isset($set_cpo[$comp_id])){
				$set_cpo[$comp_id] = $set_cpo[$comp_id] + $out_standing;
			}
		}

		$display_total = array_sum($set_cpo);
		foreach ($focus_arr as $comp_id => $value ){
			$display_total_cpo = $set_cpo[$comp_id];
			if($display_total_cpo > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cpo,2).'</span></div>';
			}
			$set_cpo[$comp_id] = 0;
		}



		$last_year = intval(date("Y"))-1;
		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$last_year.')</div>';

		$n_month = date("m");
		$n_day = date("d");
		$date_last_year_today = "$n_day/$n_month/$last_year";
		$set_date_b = '01/01/'.$last_year;
	 


		$po_list_ordered = $this->purchase_order_m->get_po_list_order_by_project($set_date_b,$date_last_year_today);
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
		
		foreach ($focus_arr as $comp_id => $value ){
			$display_total_cpo = $set_cpo[$comp_id];
			if($display_total_cpo > 0){
				$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.str_replace("Pty Ltd","",$focus_arr[$comp_id]).'</span> <span class=\'col-xs-6\'>$ '.number_format($display_total_cpo,2).'</span></div>';
			}
		}



		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><i class="fa fa-usd"></i> <strong>'.number_format($display_total,2).'</strong></p>';


	}


	public function focus_top_ten_clients_pm($pm_data_id='', $year_set='', $report_sheet='',$is_mr=''){

	//	echo "*** $pm_data_id , $year_set , $report_sheet ,$is_mr ";


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

			if($user_details['user_role_id'] == 20 && $user_details['user_department_id'] == 4): //for PM 
				$pm_type = 2;
			endif; //for PM or AM
		}else{
			$pm_type = $this->pm_type();
		}

		if($is_mr == 'mr'){
			$pm_type = 2;
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

$current_year = date("Y");



if($year_set != ''){
	if($current_year == $year_set){
		$current_date = date("d/m/Y");
		$year = date("Y");
		$last_year = intval(date("Y")) - 1;		
	}else{
		$current_date = '31/12/'.$year_set;
		$year = $year_set;
		$last_year = $year_set -1;
	}
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
		$comp_name = array();


		foreach ($client_details as $company) {
			$q_vr_c = $this->dashboard_m->client_vr_value($current_start_year,$current_date,$company->client_id,$comp_q);
			$vr_val = array_shift($q_vr_c->result_array());
			$cost_gtotl_amnt = round($company->grand_total+ $vr_val['total_variation']);
			$comp_total[$company->company_id] = $cost_gtotl_amnt;
			$comp_name[$company->company_id] = $company->company_name_group;

		}

		arsort($comp_total);


		if(count($comp_total) == 0 && $report_sheet != 'pie'){
			echo "<p><center><strong>No Records Yet.</strong></csnter></p>";
		}

		foreach ($comp_total as $raw_id => $compute_amount) {
			if($report_sheet == '' || $report_sheet == 'list'){
				echo '<div class="mr_comp_name col-sm-4 col-md-7"><i class="fa fa-chevron-circle-right"></i>  &nbsp; ';
			}

			$percent = round(100/($list_total/$compute_amount),1);
			$company_name = $comp_name[$raw_id];
			$company_name_group = $comp_name[$raw_id];
 
		if($is_mr == 'mr' && $is_mr == ''){

			if(strlen($company_name_group) > 55){
						echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$company_name_group.'">'.substr($company_name_group,0,55).'...</span>';
					}else{
						echo $company_name_group;
					}


		}elseif($report_sheet == 'pie' && $is_mr == 'mr'){

		}
		else{

 
			if($report_sheet == 'list' || $report_sheet == ''){
				if($pm_data_id != ''){
					if(strlen($company_name_group) > 30){
						echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$company_name_group.'">'.substr($company_name_group,0,30).'...</span>';
					}else{
						echo $company_name_group;
					}
				}else{
					if(strlen($company_name_group) > 30){
						echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$company_name_group.'">'.substr($company_name_group,0,30).'...</span>';
					}else{
						echo $company_name_group;
					}
				}
			}
		}




			$q_vr_c_u = $this->dashboard_m->client_vr_value($date_a_last,$date_b_last,$raw_id);
			$vr_val_u = array_shift($q_vr_c_u->result_array());

			$last_year_q = $this->dashboard_m->get_top_ten_clients($date_a_last, $date_b_last,$raw_id);
			$last_year_sale = array_shift($last_year_q->result_array());
			$lst_year_total = $last_year_sale['grand_total'] + $vr_val_u['total_variation'];
		

			if($report_sheet == 'list' ){

				echo ' </div><div class="mr_comp_val col-sm-4 col-md-2"><strong>'.number_format($percent,1).'%</strong></div><div class="mr_comp_val col-sm-4 col-md-3"><i class="fa fa-usd"></i><strong>$ '.number_format($compute_amount).'</strong></div>';

			}elseif($report_sheet == 'pie' ){
				echo " ['".str_replace("'","&apos;",$comp_name[$raw_id] )."', ".$compute_amount."], ";
			}else{
				echo ' </div><div class="col-sm-4 col-md-2"><strong>'.number_format($percent,1).'%</strong></div>  <div class="col-sm-4 col-md-3 tooltip-test" title="" data-placement="left" data-original-title="Last Year : $ '.number_format($lst_year_total).'"><i class="fa fa-usd"></i> '.number_format($compute_amount).'</div><div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';
			}


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

			if($user_details['user_role_id'] == 20 && $user_details['user_department_id'] == 4): //for PM 
				$pm_type = 2;
			endif; //for PM or AM
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
		$comp_name = array();

		foreach ($client_details as $company) {
			$q_vr_c = $this->dashboard_m->client_vr_value($current_start_year,$current_date,$company->client_id,$comp_q);
			$vr_val = array_shift($q_vr_c->result_array());
			$cost_gtotl_amnt = round($company->grand_total+ $vr_val['total_variation']);
			$comp_total[$company->company_id] = $cost_gtotl_amnt;
		//	$comp_name[$company->company_id] = $company->company_name;
			$comp_name[$company->company_id] = $company->company_name_group;

		}

		arsort($comp_total);

		foreach ($comp_total as $raw_id => $compute_amount) {
			$percent = round(100/($list_total/$compute_amount),1);
			//$company_name = $comp_name[$raw_id];
			$company_name_group = $comp_name[$raw_id];


			echo "['". str_replace("'","&apos;",$company_name_group)."', ".$compute_amount."],";

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
			$comp_name[$company->company_id] = $company->company_name_group;

		}

		arsort($comp_total);

		foreach ($comp_total as $raw_id => $compute_amount) {
			echo '<div class="col-sm-8 col-md-7"><i class="fa fa-chevron-circle-right"></i>  &nbsp; ';
			$percent = round(100/($list_total/$compute_amount),1);
			$company_name_group = $comp_name[$raw_id];
 
			if(strlen($company_name_group) > 30){
				echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$company_name_group.'">'.substr($company_name_group,0,30).'...</span>';
			}else{
				echo $company_name_group;
			}

			$q_vr_c_u = $this->dashboard_m->client_vr_value($date_a_last,$date_b_last,$raw_id);
			$vr_val_u = array_shift($q_vr_c_u->result_array());

			$last_year_q = $this->dashboard_m->get_top_ten_clients($date_a_last, $date_b_last,$raw_id);
			$last_year_sale = array_shift($last_year_q->result_array());
			$lst_year_total = $last_year_sale['grand_total'] + $vr_val_u['total_variation'];
			echo ' </div><div class="col-md-2 col-sm-4"><strong>'.number_format($percent,1).'%</strong></div>  <div class="col-md-3 col-sm-4 tooltip-test" title="" data-placement="left" data-original-title="Last Year : $ '.number_format($lst_year_total).'"><i class="fa fa-usd"></i> '.number_format($compute_amount).'</div><div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';
		}
	}


//get_company_sales($type,$date_a,$date_b,$cmp_id='')


	




	public function focus_top_ten_con_sup_pm_donut($type,$pm_data_id = ''){
		
		if(isset($pm_data_id) && $pm_data_id != ''){
			$user_id = $pm_data_id;
		}else{
			$user_id = $this->session->userdata('user_id');
		}

		$direct_company = '';
		$fetch_user = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($fetch_user->result_array());



		if($user_id != ''){
			if($user_details['user_role_id'] == 3 && $user_details['user_department_id'] == 1):
				$pm_type = 1;
			endif; //for directors 

			if($user_details['user_role_id'] == 3 && $user_details['user_department_id'] == 4): //for PM 
				$pm_type = 2;
			endif; //for PM 

			if($user_details['user_role_id'] == 20 && $user_details['user_department_id'] == 4): //for PM 
				$pm_type = 2;
			endif; //for PM or AM
		}else{

			$pm_type = $this->pm_type($user_id);
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

			//echo '<div class="col-sm-8 col-md-8"><i class="fa fa-chevron-circle-right"></i>  &nbsp; ';

			$comp_name = $company->company_name; 



			echo "['". str_replace("'","&apos;",$comp_name)."', ".$company->total_price."],";



			$cmp_id = $company->company_id;

			$last_year_q = $this->dashboard_m->get_company_sales('',$base_year,$current_start_year,$cmp_id,$comp_q);
			$last_year_sale = array_shift($last_year_q->result_array());
			$lst_year_total = $last_year_sale['total_price'];

			//echo ' </div><div class="col-md-1 col-sm-4"><strong>'.$percent.'%</strong></div>  <div class="col-md-3 col-sm-4 tooltip-test" title="" data-placement="left" data-original-title="Last Year : $ '.number_format($lst_year_total).'"><i class="fa fa-usd"></i> '.number_format($company->total_price).'</div><div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';
		}
	}




	public function focus_top_ten_con_sup_pm($type,$pm_data_id = ''){
		
		if(isset($pm_data_id) && $pm_data_id != ''){
			$user_id = $pm_data_id;
		}else{
			$user_id = $this->session->userdata('user_id');
		}

		$direct_company = '';
		$fetch_user = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($fetch_user->result_array());



		if($user_id != ''){
			if($user_details['user_role_id'] == 3 && $user_details['user_department_id'] == 1):
				$pm_type = 1;
			endif; //for directors 

			if($user_details['user_role_id'] == 3 && $user_details['user_department_id'] == 4): //for PM 
				$pm_type = 2;
			endif; //for PM 

			if($user_details['user_role_id'] == 20 && $user_details['user_department_id'] == 4): //for PM 
				$pm_type = 2;
			endif; //for PM or AM
		}else{

			$pm_type = $this->pm_type($user_id);
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

			echo '<div class="col-sm-8 col-md-7"><i class="fa fa-chevron-circle-right"></i>  &nbsp; ';

			$comp_name = $company->company_name;
			if(strlen($comp_name) > 30){
				echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$comp_name.'">'.substr($comp_name,0,30).'...</span>';
			}else{
				echo $comp_name;
			}

			$cmp_id = $company->company_id;

			$last_year_q = $this->dashboard_m->get_company_sales('',$base_year,$current_start_year,$cmp_id,$comp_q);
			$last_year_sale = array_shift($last_year_q->result_array());
			$lst_year_total = $last_year_sale['total_price'];

			echo ' </div><div class="col-md-2 col-sm-4"><strong>'.$percent.'%</strong></div>  <div class="col-md-3 col-sm-4 tooltip-test" title="" data-placement="left" data-original-title="Last Year : $ '.number_format($lst_year_total).'"><i class="fa fa-usd"></i> '.number_format($company->total_price).'</div><div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';
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

			echo '<div class="col-sm-8 col-md-7"><i class="fa fa-chevron-circle-right"></i>  &nbsp; ';

			$comp_name = $company->company_name;
			if(strlen($comp_name) > 30){
				echo '<span class="tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$comp_name.'">'.substr($comp_name,0,30).'...</span>';
			}else{
				echo $comp_name;
			}

			$cmp_id = $company->company_id;

			$last_year_q = $this->dashboard_m->get_company_sales('',$last_start_year,$base_year);
			$last_year_sale = array_shift($last_year_q->result_array());
			$lst_year_total = $last_year_sale['total_price'];

			echo ' </div><div class="col-md-2 col-sm-4"><strong>'.number_format($percent,1).'%</strong></div>  <div class="col-md-3 col-sm-4 tooltip-test" title="" data-placement="left" data-original-title="Last Year : $ '.number_format($lst_year_total).'"><i class="fa fa-usd"></i> '.number_format($company->total_price).'</div><div class="col-sm-12"><hr class="block m-bottom-5 m-top-5"></div>';
		}
	}


	public function focus_projects_by_type_widget_pm($assign_id='',$is_pie=''){

		if(isset($assign_id) && $assign_id != ''){
			$user_id = $assign_id;
		}else{
			$user_id = $this->session->userdata('user_id');
		}


		$pm_type = $this->pm_type($user_id); 

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

				if($pm_type == 1){// for director/pm
					$focus_catgy_costs[$cat_id] = $focus_catgy_costs[$cat_id] + $project['project_total'] + $project['variation_total'];
					$focus_costs[$comp_id] = $focus_costs[$comp_id] + $project['project_total'] + $project['variation_total'];
					$grand_prj_total = $grand_prj_total +  $project['project_total'];
				}

				if($pm_type == 2 && $project['project_manager_id'] == $user_id ){// for pm only
					$focus_catgy_costs[$cat_id] = $focus_catgy_costs[$cat_id] + $project['project_total'] + $project['variation_total'];
					$focus_costs[$comp_id] = $focus_costs[$comp_id] + $project['project_total'] + $project['variation_total'];
					$grand_prj_total = $grand_prj_total +  $project['project_total'];
				}


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
			

			if($is_pie != ''){
				echo "['".str_replace("'","&apos;", $value)."',".$cost."],";
			}else{



				echo '<div id="" class="clearfix"><p><span class="col-sm-7"><i class="fa fa-chevron-circle-right"></i> &nbsp; '.$value.'</span><strong class="col-sm-5">$ '.number_format($cost).'</strong></p></div>';
				echo '<div class="col-md-12"><hr class="block m-bottom-5 m-top-5"></div>'; 
			}


		}
	}



	public function focus_projects_by_type_widget($is_pie=''){
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


			if($is_pie != ''){
				echo "['".str_replace("'","&apos;",$value)."',".$cost."],";
			}else{
				echo '<div id="" class="clearfix"><p><span class="col-sm-7"><i class="fa fa-chevron-circle-right"></i> &nbsp; '.$value.'</span><strong class="col-sm-5">$ '.number_format($cost).'</strong></p></div>';
				echo '<div class="col-md-12"><hr class="block m-bottom-5 m-top-5"></div>';
			}


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
			$comp_name = $company->company_name_group;
		
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




	

	public function maintanance_average_pm($assign_id=''){


		if(isset($assign_id) && $assign_id != '' && $assign_id != 0){
			$user_id = $assign_id;
		}else{
			$user_id = $this->session->userdata('user_id');
		}
		$pm_type = $this->pm_type($user_id); 


		$fetch_user = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($fetch_user->result_array());

		if($pm_type == 1){ // for director/pm
			$direct_company = explode(',',$user_details['direct_company'] );
		}

		if($pm_type == 2){ // for pm only
			$direct_company = explode(',',$user_details['user_focus_company_id'] );
		}

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
		$short_day_day = 1; /// sould be $short_day_day = 1;

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
		$average_old = array_sum($days_dif_old) / $size_old;

		if($average_old <= 0){
			$average_old = 0;
			$short_init = 0;
			$long_day_old = 0;
		}else{
			$short_init = 1;
			$long_day_old =  max($days_dif_old); 
		}


		if($average <= 0){
			$short_day_day = 0;
			$long_day = 0;
		} 


  
/*
		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="('.$last_year.')'.round($average_old,2).'">'.number_format($average,2).' Days';
		echo '<span class="pull-right">'.$short_day_day.'  <i class="fa fa-arrows-h" aria-hidden="true"></i> '.$long_day.'</span></p>';
*/


		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title=" ('.$last_year.') &nbsp; '.number_format($average_old,2).'  &nbsp; ['.$short_init.' - '.$long_day_old.'] ">'.number_format($average,2).' Days';
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

			arsort($days_dif_old,1);
			//	var_dump($days_dif_old);
			$long_day_old =  max($days_dif_old);
			$short_day_day_old =  min($days_dif_old);

			/* //over ride */ $short_day_day_old = 1; // 1 actual
			/*  //over ride */	//$long_day = 0; // 
			//echo "<p>$long_day_old***$short_day_day_old</p>";

		}else{
			$average_old = ' No Data Yet';
		}

  

		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title=" ('.$last_year.') &nbsp; '.number_format($average_old,2).'  &nbsp; ['.$short_day_day_old.' - '.$long_day_old.'] ">'.number_format($average,2).' Days';
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

		$project_manager = $this->dashboard_m->fetch_pms_year(date("Y")); // ****--___--***
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



		$project_manager = $this->dashboard_m->fetch_pms_year(date("Y")); // ****--___--***
		$project_manager_list = $project_manager->result();
 
 
				foreach ($project_manager_list as $pm ) {
	/*		$total_sales = 0;
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
*/
			$wip_amount = $this->get_wip_value_permonth($date_a,$date_b,$pm->user_id,1);
			$total_wip = $total_wip + $wip_amount;


		//	$grand_total_sales_cmp = $grand_total_sales_cmp + $total_sales;
		}


$q_get_sales_yearly = $this->dashboard_m->get_sales_focus($c_year);
$get_sales_yearly = array_shift($q_get_sales_yearly->result_array());


$grand_total_sales_cmp = $get_sales_yearly['total_sales'];








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

		$data['page_title'] = 'Sales Forecast';

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


/*
if($pm_id = 29){

		$q_curr_maintenance_sales = $this->dashboard_m->fetch_pm_sales_year($report_year,29);
		$data['pm_actual_sales'] = array_shift($q_curr_maintenance_sales->result_array());
}else{*/

		$pm_actual_sales_q = $this->dashboard_m->fetch_pm_sales_year($report_year,$pm_id);
		$data['pm_actual_sales'] = array_shift($pm_actual_sales_q->result_array());
/*}*/






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










	public function test_data_rep(){




$query = $this->db->query("SELECT `invoice`.`project_id`, `invoice`.`invoice_date_req`, `invoice`.`set_invoice_date`, `project`.`project_total`,`project_cost_total`.`variation_total`, `invoice`.`progress_percent`, `invoice`.`label` ,`project`.`focus_company_id`,`invoice`.`invoice_date_req`,`project`.`project_manager_id`, `project`.`job_category`
			FROM `invoice`
			LEFT JOIN `project` ON `project`.`project_id` = `invoice`.`project_id`
			LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `invoice`.`project_id`
			WHERE `project`.`is_active` = '1' AND  `invoice`.`is_invoiced` = '0' 
 

		


/*
AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('01/07/2016', '%d/%m/%Y') )
AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('31/08/2017', '%d/%m/%Y') )
*/

  ORDER BY `project`.`project_id` ASC ");

$unvoiced_total = 0;
$id_1 = 0;
$unvoiced_total_init = 0;
 
$dash_unvoiced = $query->result();
foreach ($dash_unvoiced as $unvoiced) {

	if($unvoiced->label == 'VR'){
		$unvoiced_total_init = $unvoiced->variation_total;
	}else{
		$unvoiced_total_init = $unvoiced->project_total*($unvoiced->progress_percent/100);
	}
 


	echo "<p>$unvoiced->project_id".'_'."$unvoiced_total_init</p>";



//	$unvoiced_grand_total = $unvoiced_grand_total + $unvoiced_total;
}






	}

	public function test_data_gp(){




$query = $this->db->query("SELECT `project`.`project_id`, `project`.`project_name`,`project`.`job_date`,`project`.`date_site_finish`,`project`.`project_total`,`project_cost_total`.`variation_total`,`notes`.`comments`
,`company_details`.`company_name` , CONCAT( `users`.`user_first_name`,' ',`users`.`user_last_name`) AS `pm_name`

FROM `project`

LEFT JOIN `notes` ON `notes`.`notes_id` = `project`.`notes_id`
LEFT JOIN `company_details` ON `company_details`.`company_id` = `project`.`focus_company_id`
LEFT JOIN `users` ON `users`.`user_id` =  `project`.`project_manager_id`
LEFT JOIN `project_cost_total` ON `project_cost_total`.`project_id` = `project`.`project_id`
WHERE `project`.`is_active` = '1' 		

/*
AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) >= UNIX_TIMESTAMP( STR_TO_DATE('01/07/2016', '%d/%m/%Y') )
AND UNIX_TIMESTAMP( STR_TO_DATE(`project`.`date_site_finish`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('31/08/2017', '%d/%m/%Y') )
*/


			AND `project`.`is_active` = '1' 
            
            
            
 ORDER BY `project`.`project_id` ASC");

 
$result_q = $query->result();
foreach ($result_q as $data) {

	


	$prj_data = $this->projects->fetch_project_totals($data->project_id);
 


	echo "<p>$data->project_id-".$prj_data['gp']."</p>";

//	$unvoiced_grand_total = $unvoiced_grand_total + $unvoiced_total;
}





}


public function test_send(){ 

	$send_to = 'jervyezaballa@gmail.com';
	$cc_mail = 'jean@ausconnect.net.au';
	$from = 'userconf@sojourn.focusshopfit.com.au';
	$another_email = 'jervy@focusshopfit.com.au';


	$this->load->library('email');


//$config['protocol'] = 'sendmail';
$config['mailpath'] = '/usr/sbin/sendmail -t -i';
$config['charset'] = 'iso-8859-1';
$config['wordwrap'] = TRUE;
$config['smtp_host'] = 'sojourn-focusshopfit-com-au.mail.protection.outlook.com';
$config['smtp_user']     = '';
$config['smtp_pass']     = '';
$config['protocol']     = 'mail';
$config['port'] = 587;

$this->email->initialize($config);



	$this->email->from('no-reply@focusshopfit.com.au');
	$this->email->to($another_email);
	$this->email->cc($cc_mail);
	$this->email->bcc($send_to);

	$this->email->subject('Email Test');
	$this->email->message('Testing the email class.');
 

if ( ! $this->email->send())
{
      echo "error";
}else{
	echo "email sent";
}



$to = "jervy@focusshopfit.com.au, jervyezaballa@gmail.com";
$subject = "HTML email";

$message = "
<html>
<head>
<title>HTML email</title>
</head>
<body>
<p>This email contains HTML Tags!</p>
<table>
<tr>
<th>Firstname</th>
<th>Lastname</th>
</tr>
<tr>
<td>John</td>
<td>Doe</td>
</tr>
</table>
</body>
</html>
";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// More headers
$headers .= 'From: <userconf@sojourn.focusshopfit.com.au>' . "\r\n";
$headers .= 'Cc: jean@ausconnect.net.au' . "\r\n";

mail($to,$subject,$message,$headers);




require_once('PHPMailer/class.phpmailer.php');
require_once('PHPMailer/PHPMailerAutoload.php');


$mail = new phpmailer(true);
$mail->host = "sojourn-focusshopfit-com-au.mail.protection.outlook.com";
$mail->port = 587;
$mail->setfrom('userconf@sojourn.focusshopfit.com.au', 'name');
$mail->addreplyto('userconf@sojourn.focusshopfit.com.au', 'name');
$mail->addaddress('jervy@focusshopfit.com.au', 'joe user');
$mail->addcc('jean@ausconnect.net.au');
$mail->smtpdebug = 2;
$mail->ishtml(true);
$mail->msghtml('this is a test');
//$mail->send();



if(!$mail->send()) {
    echo 'message could not be sent.';
    echo 'mailer error: ' . $mail->errorinfo;
} else {
    echo 'message has been sent';
}





/*




require_once('PHPMailer/class.phpmailer.php');
require_once('PHPMailer/PHPMailerAutoload.php');

$mail = new PHPMailer;

$mail->isSMTP();                                      
$mail->Host = 'sojourn-focusshopfit-com-au.mail.protection.outlook.com';  
//$mail->Username = '';                 						
//$mail->Password = '';                          
$mail->Port = 25;                                    
$mail->SMTPDebug = 3;
$mail->SMTPAuth = false;
$mail->SMTPSecure = false;
$mail->protocol = 'sendmail';
$mail->mailpath = '/usr/sbin/sendmail -t -i';



$mail->setFrom($from, 'Mailer');
$mail->addAddress($another_email, 'Joe User');     // Add a recipient
$mail->addAddress($send_to);               // Name is optional
$mail->addReplyTo('no-reply@example.com', 'Information');
$mail->addCC($cc_mail);
//$mail->addBCC('bcc@example.com');
/*
$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
*/
/*
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}









// another test email



$to = $another_email;
$subject = "My subject";
$txt = "Hello world!";
$headers = "From: webmaster@example.com" . "\r\n" .
"CC: $cc_mail";

mail($to,$subject,$txt,$headers);

		
 
*/

}




}