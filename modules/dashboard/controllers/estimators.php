<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jervy Zaballa */  /* NOTE: CI version used 2.1.2 */

class Estimators extends MY_Controller{
	
	function __construct(){
		parent::__construct();

		$this->load->library('session');
		
		$this->load->module('admin');
		$this->load->model('admin_m');

		$this->load->module('users');
		$this->load->model('user_model');

		$this->load->model('dashboard_m');
		$this->load->model('dashboard_m_es');

		if(!$this->users->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;


	}

	function index(){
		$this->users->_check_user_access('dashboard',1);
		// temporary restriction for dashboaed dev
		$user_role_id = $this->session->userdata('user_role_id');
		if($this->session->userdata('is_admin') == 1  || $user_role_id == 8 || $user_role_id == 16 || $this->session->userdata('user_id') == 9 || $this->session->userdata('user_id') == 15  || $this->session->userdata('user_id') == 6 /*|| $user_role_id == 16 || $user_role_id == 3 || $user_role_id == 2 || $user_role_id == 7 || $user_role_id == 6*/):
		else:		
			redirect('projects', 'refresh');
		endif;
		// temporary restriction for dashboaed dev

		//dashboard_est

		$estimator = $this->dashboard_m->fetch_project_estimators();
		$data['estimator_list'] = $estimator->result();

		$data['es_id'] = $this->session->userdata('user_id');

if($this->session->userdata('is_admin') == 1 || $user_role_id == 8 || $user_role_id == 16 || $this->session->userdata('user_id') == 9 || $this->session->userdata('user_id') == 15  || $this->session->userdata('user_id') == 6 ):

		$dash_type = $this->input->get('dash_view', TRUE);
		if( isset($dash_type) && $dash_type != ''){
			$dash_details = explode('-', $dash_type);
			$data['assign_id'] = $dash_details[0];

			if($dash_details[1] == 'pm'){
				redirect(base_url().'dashboard?dash_view='.$dash_details[0].'-pm');
			}elseif($dash_details[1] == 'mn'){
				redirect(base_url().'dashboard?dash_view='.$dash_details[0].'-mn');
			}elseif($dash_details[1] == 'ad'){
				$data['main_content'] = 'dashboard_home';
			}elseif($dash_details[1] == 'es'){
				$data['es_id'] = $dash_details[0];

				$fetch_user = $this->user_model->fetch_user($data['es_id']);
				$user_details = array_shift($fetch_user->result_array());

				$data['es_setter_f_name'] = str_replace(' ','_',strtolower($user_details['user_first_name']));
				$data['es_setter'] = $user_details['user_first_name'].' '.$user_details['user_last_name']; 

			}else{
				redirect(base_url().'dashboard');
			}
		}

endif;



		$data['main_content'] = 'dashboard_est';
		$this->load->view('page', $data);
	}



	function list_deadlines($estimator_id=''){

		//$this_year = date("d/m/Y");

		$this_year =  date("d/m/Y", strtotime("- 5 days"));
 
		$estimator_dlq = $this->dashboard_m_es->fetch_upcoming_deadline($this_year,$estimator_id);
		$num_result = $estimator_dlq->num_rows();
		$estimator_dl = $estimator_dlq->result();
	 
		
		// echo'{ 
		// 	"name": "Test", 
		// 	"values": [
		// 		{"from": "2017/07/22", "to": "2017/07/26", "desc": "<b>Type</b>: Task<br/><b>name</b>: Task 8<br/><b>Description</b>: Task desc.", "customClass": "sandy"},
		// 		{"from": "2017/07/27", "to": "2017/07/27", "desc": "<b>Type</b>: Task<br/><b>name</b>: Task 9<br/><b>Description</b>: Task desc.", "customClass": "arcy"}, 
		// 	]
		// },  ';

		$current_day_line = date('Y/m/d');

		foreach ($estimator_dl as $est ) {

			$date_allowance = strtotime(date("Y-m-d", strtotime("- 5 days")) ) * 1000 ;

			if($date_allowance <= $est->project_date_unix){
				$use_date_stamp = $est->project_date_unix;
			}else{
				$use_date_stamp = $date_allowance;
			}

 
$quote_deadline_date_replaced = str_replace('/', '-', $est->quote_deadline_date);
$quote_deadline_date_reformated = date('Y/m/d', strtotime("$quote_deadline_date_replaced - 2 day"));


	$date_deadline_check_val = date('N',$est->deadline_unix);

	// var_dump($date_deadline_check_val);
	// echo "<p><br /></p>";

	if($date_deadline_check_val == 3){
		$deadline_unix = $est->deadline_unix - 259200000;
		$quote_deadline_date_reformated = date('Y/m/d', strtotime("$quote_deadline_date_replaced - 4 day"));
	}else{
		//$deadline_unix = $est->deadline_unix;
		$deadline_unix = $est->deadline_unix - 86400000;
	}
	   

		echo '{
                name: "<a href=\"'.base_url().'projects/view/'.$est->project_id.'\" target=\"_blank\">'.$est->project_id.'</a>", 
                dataObj: "'.$est->user_first_name.' : '.$est->project_name.'",
                values: [
                	{from: "/Date('.$use_date_stamp.')/", to: "/Date('.$quote_deadline_date_reformated.')/", "desc": "<strong>'.$est->user_first_name.'</strong> : '.$est->project_name.'<br /><strong>'.$est->client_name.'</strong> &nbsp;  &nbsp;  &nbsp;   &nbsp;  Brand : '.$est->brand_name.'",customClass: "'.$est->user_first_name.'"},
                	{from: "/Date('.$deadline_unix.')/", to: "/Date('.$deadline_unix.')/", "desc": "<strong>'.$est->user_first_name.'</strong> : '.$est->project_name.'<br /><strong>'.$est->client_name.'</strong> &nbsp;  &nbsp;  &nbsp;   &nbsp;  Brand : '.$est->brand_name.'",customClass: "red_deadline"},
                	{from: "'.$current_day_line.'", to: "'.$current_day_line.'", "desc": "<strong>'.$est->user_first_name.'</strong> : '.$est->project_name.'<br /><strong>'.$est->client_name.'</strong> &nbsp;  &nbsp;  &nbsp;   &nbsp;  Brand : '.$est->brand_name.'",customClass: "curr_date"},
					{from: "'.date('Y/m/d', strtotime('- 5 days')).'", to: "'.date('Y/m/d', strtotime('+ 20 days')).'",  customClass: "hide"}
                ]
        },';
 

		}

		//echo "<p><strong>$num_result</strong></p>";


		//echo "<p><strong>$num_result</strong></p>";



		for($looper = $num_result; $looper < 16 ; $looper ++ ){

		//echo "<p><strong>$looper</strong></p>";
			

			echo '

			{ values: [
					{from: "'.date('Y/m/d', strtotime('- 5 days')).'", to: "'.date('Y/m/d', strtotime('+ 5 days')).'",  customClass: "hide"},
					{from: "'.date('Y/m/d').'", to: "'.date('Y/m/d').'" ,customClass: "curr_date"},
					{from: "'.date('Y/m/d', strtotime('+ 5 days')).'", to: "'.date('Y/m/d', strtotime('+ 20 days')).'",  customClass: "hide"}
				]
			}, 


			';

		}



	}





	public function up_coming_deadline($estimator_id = ''){
		// $c_year = date("Y");		
		// $date_a = "01/01/$c_year";
		// $date_b = date("d/m/Y");

		$estimator_name = array();
		$estimators_val = array();
		$total_string = '';

		$estimator = $this->dashboard_m->fetch_project_estimators();
		$estimator_list = $estimator->result();
		foreach ($estimator_list as $est ) {
			$estimator_name[$est->project_estiamator_id] = $est->user_first_name;
			$estimators_val[$est->project_estiamator_id] = 0;
		}

		$diff_a = 0;
		$diff_b = 0;

		$this_year = date("d/m/Y");

		$estimator_dlq = $this->dashboard_m_es->fetch_upcoming_deadline($this_year);
		$estimator_dl = $estimator_dlq->result();

		$current_day_line = date('Y/m/d');

		$no_selected = 0;

		$dStart = new DateTime( date('Y-m-d') );

		foreach ($estimator_dl as $est ) {

			if(   array_key_exists($est->project_estiamator_id, $estimators_val) ){
				if( $estimators_val[$est->project_estiamator_id] == 0  ){

					$quote_deadline_date_replaced = str_replace('/', '-', $est->quote_deadline_date);

					$quote_deadline_date_checker = date('N', strtotime("$quote_deadline_date_replaced"));

					if($quote_deadline_date_checker == 1){
						$dEnd = new DateTime( date('Y-m-d', strtotime("$quote_deadline_date_replaced - 2 day")));
					}else{
						$dEnd = new DateTime( date('Y-m-d', strtotime("$quote_deadline_date_replaced")));
					}

					$dDiff = $dStart->diff($dEnd);

					$project_date_date_replaced = str_replace('/', '-', $est->project_date);
					$pdEnd = new DateTime( date('Y-m-d', strtotime("$project_date_date_replaced")));
					$pdDiff = $dEnd->diff($pdEnd);

					if($est->project_estiamator_id == $estimator_id){
						$diff_a = intval($dDiff->days) - 1;
						$diff_b = $pdDiff->days;
					}

					$days_remains = intval($dDiff->days) - 1;

					if($days_remains > 0){	


						if($estimator_id == '' && $no_selected == 0){
							$diff_a = intval($dDiff->days) - 1;
							$diff_b = $pdDiff->days;
							$no_selected = 1;
						}			


						$estimators_val[$est->project_estiamator_id] = 1;
						$total_string .= '<div class=\'row\'><span class=\'col-xs-4\'>'.$est->user_first_name.'</span><span class=\'col-xs-8\'>'.$days_remains.' day(s) before dealine.</span></div>';
					}

				}
			}
		}

		//$final_number = $diff_b - $diff_a;

		echo '<div id="" class="tooltip-enabled" title="" data-placement="bottom" data-html="true" data-original-title="'.$total_string.'">
		<input class="knob" data-width="100%" data-step=".1"  data-thickness=".15" value="'.$diff_a.'" readonly data-fgColor="#058EB4" data-angleOffset="-180"  data-max="'.$diff_b.'">
		<div id="" class="clearfix m-top-10 text-center"><strong><p><br />'.$diff_a.' day(s) before next deadline.</p></strong></div></div>';
	}


	public function estimators_quotes_completed($es_id = ''){ 
		$total_string = '';

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$focus_company = $all_focus_company->result();
		$focus_arr = array();
		$quoted_focus_company = array();
		$cost_focus = array();
		$cost_focus_b = array();
		foreach ($focus_company as $company){
			$focus_arr[$company->company_id] = $company->company_name;
			$quoted_focus_company[$company->company_id] = 0;
			$cost_focus[$company->company_id] = 0;
			$cost_focus_b[$company->company_id] = 0;
		}

		$estimator = $this->dashboard_m->fetch_project_estimators();
		$estimator_list = $estimator->result();
		$quoted_estimator = array();
		$quoted_estimator_name = array();
		$cost_estimator = array();
		$cost_estimator_b = array();
		foreach ($estimator_list as $est ) {
			$quoted_estimator[$est->project_estiamator_id] = 0;
			$cost_estimator[$est->project_estiamator_id] = 0;
			$cost_estimator_b[$est->project_estiamator_id] = 0;
			$quoted_estimator_name[$est->project_estiamator_id] = $est->user_first_name;
		}
		$quoted_estimator_name[0] = '';

		$is_restricted = 0;

		$admin_defaults = $this->admin_m->fetch_admin_defaults(1);
		foreach ($admin_defaults->result() as $row){
			$unaccepted_date_categories = $row->unaccepted_date_categories;
			$unaccepted_no_days = $row->unaccepted_no_days;
		}


		$all_projects_q = $this->dashboard_m->get_all_active_projects();
		foreach ($all_projects_q->result_array() as $row){
			if($row['project_estiamator_id'] != '0'){

				if($row['project_estiamator_id'] != '8'){

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
						//$quoted_estimator[$row['project_estiamator_id']]++;
		 				//$quoted_focus_company[$row['focus_company_id']]++;

						if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 || $row['variation_total'] > 0.00 ){
							$project_cost = $row['project_total'] + $row['variation_total'];
						}else{
							$project_cost = $row['budget_estimate_total'];
						}

						//$cost_focus[$row['focus_company_id']] = $cost_focus[$row['focus_company_id']] + $project_cost;
						//$cost_estimator[$row['project_estiamator_id']] = $cost_estimator[$row['project_estiamator_id']] + $project_cost;




						if ( array_key_exists($row['focus_company_id'], $cost_focus) ) {
							$cost_focus[$row['focus_company_id']] = $cost_focus[$row['focus_company_id']] + $project_cost;
						}

						if ( array_key_exists($row['project_estiamator_id'], $cost_estimator) ) {
							$cost_estimator[$row['project_estiamator_id']] = $cost_estimator[$row['project_estiamator_id']] + $project_cost;
						}



					}
				}
			}
		}

		$current_year = intval(date("Y"));
		$last_year = intval(date("Y"))-1; 

		$n_month = date("m");
		$n_day = date("d");
		$date_last_year_today = "$n_day/$n_month/$last_year";

		$m_month = $n_month+2;
		$year_odl_set = $last_year;

		if($m_month > 12){
			$m_month = $m_month - 12;
			$year_odl_set = $last_year + 1;
			$date_last_year_next = "01/$m_month/$year_odl_set";
		}else{
			$date_last_year_next = "01/$m_month/$last_year";
		}



		$date_last_year_today_exx = "01/01/$current_year";

		$date_last_year_today_err = "$n_day/$n_month/$current_year";


		//echo "<p>$date_last_year_today_exx,$date_last_year_today_err</p>";


		foreach ($estimator_list as $est ) {


			if($est->project_estiamator_id != '0'){
				if($est->project_estiamator_id != '8'){




			if( $cost_estimator[$est->project_estiamator_id] == 0){


				$est_q_cst = "  AND `project`.`project_estiamator_id` = '$est->project_estiamator_id' ";





		$all_projects_q = $this->dashboard_m->get_all_active_projects($date_last_year_today_exx,$date_last_year_today_err,$est_q_cst);
		foreach ($all_projects_q->result_array() as $row){

			if($row['project_estiamator_id'] != '0'){
				if($row['project_estiamator_id'] != '8'){

					$project_cost = 0;

					$quoted_estimator[$row['project_estiamator_id']]++; 
					$quoted_focus_company[$row['focus_company_id']]++;

					if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 || $row['variation_total'] > 0.00 ){
						$project_cost = $row['project_total'] + $row['variation_total'];
					}else{
						$project_cost = $row['budget_estimate_total'];
					}

					$cost_focus[$row['focus_company_id']] = $cost_focus[$row['focus_company_id']] + $project_cost;
					$cost_estimator[$row['project_estiamator_id']] = $cost_estimator[$row['project_estiamator_id']] + $project_cost; 
				}
			}
		}




			}

		} 



			}

		} 



		$all_projects_q = $this->dashboard_m->get_all_active_projects($date_last_year_today,$date_last_year_next);
		foreach ($all_projects_q->result_array() as $row){

			if($row['project_estiamator_id'] != '0'){
				if($row['project_estiamator_id'] != '8'){

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


					if ( array_key_exists($row['project_estiamator_id'], $quoted_estimator) ) {
						$quoted_estimator[$row['project_estiamator_id']]++;
					}

					if ( array_key_exists($row['focus_company_id'], $quoted_focus_company) ) {
						$quoted_focus_company[$row['focus_company_id']]++;
					}


					if($row['install_time_hrs'] > 0 || $row['work_estimated_total'] > 0.00 || $row['variation_total'] > 0.00 ){
						$project_cost = $row['project_total'] + $row['variation_total'];
					}else{
						$project_cost = $row['budget_estimate_total'];
					}

					$cost_focus_b[$row['focus_company_id']] = $cost_focus_b[$row['focus_company_id']] + $project_cost;


					if ( array_key_exists($row['project_estiamator_id'], $cost_estimator_b) ) {
						$cost_estimator_b[$row['project_estiamator_id']] = $cost_estimator_b[$row['project_estiamator_id']] + $project_cost; 
					}


				}
			}
		}

		$base_url = base_url();

		echo '<div style="overflow-y: auto; padding-right: 5px; height: 410px;">';

		foreach ($estimator_list as $est ) {

			if($est->project_estiamator_id != '0'):
				if($est->project_estiamator_id != '8'):

				echo '<div class="m-bottom-5 clearfix" style="border-bottom: 1px solid #ecf0f5;padding: 5px 0px;">
						<div class="pull-left m-right-5" style="height: 50px; width:50px; border-radius:50px; overflow:hidden; border: 1px solid #999999;">
							<img class="user_avatar img-responsive img-rounded" src="'.$base_url.'uploads/users/'.$est->user_profile_photo.'">
						</div>

						<div class="" id="">
							<p>
								<span class="pull-right">
									<span class="label pull-right m-bottom-3 m-top-3 small_orange_fixed tooltip-enabled" title="" data-placement="left" data-html="true" data-original-title="'.$current_year.'" style="width: 150px;"><i class="fa fa-usd"></i> '.number_format($cost_estimator[$est->project_estiamator_id],2).'</span> <br> 
									<span class="label pull-right m-bottom-3 small_green_fixed tooltip-enabled" title="" data-placement="left" data-html="true" data-original-title="'.$last_year.'" style="width: 150px; background-color: #aaa !important;"><i class="fa fa-usd"></i> '.number_format($cost_estimator_b[$est->project_estiamator_id],2).'</span>
								</span>	 
								<strong style="padding-top: 10px; display: block; color:#fff; "><span class="'.str_replace(' ','_',strtolower($quoted_estimator_name[$est->project_estiamator_id])).'" style="padding: 3px 6px; border-radius: 10px;" >'.$quoted_estimator_name[$est->project_estiamator_id].'</span></strong>
							</p>
						</div>
					</div>';			
				endif;
			endif;
		}

$total_present = 0;
$total_past = 0;


		foreach ($focus_company as $company){
			if($quoted_focus_company[$company->company_id] > 0){




				echo '<div class="m-bottom-5 clearfix" style="border-bottom: 1px solid #ecf0f5;padding: 5px 0px;">		

						<i class="fa fa-user" style="font-size: 42px;float: left;margin-left: 7px;margin-right: 10px;"></i>

						<div class="" id="">
							<p>
								<span class="pull-right">
									<span class="label pull-right m-bottom-3 m-top-3 small_orange_fixed tooltip-enabled" title="" data-placement="left" data-html="true" data-original-title="'.$current_year.'"  style="width: 150px;"><i class="fa fa-usd"></i> '.number_format($cost_focus[$company->company_id],2).'</span> <br> 
									<span class="label pull-right m-bottom-3 small_green_fixed tooltip-enabled" title="" data-placement="left" data-html="true" data-original-title="'.$last_year.'"  style="width: 150px; background-color: #aaa !important;"><i class="fa fa-usd"></i> '.number_format($cost_focus_b[$company->company_id],2).'</span>
								</span> 
								<strong style="padding-top: 10px; display: block;">'.str_replace("Pty Ltd","",$focus_arr[$company->company_id]).'</strong>
							</p>
						</div>
					</div>';

					$total_present = $total_present + $cost_focus[$company->company_id];
					$total_past = $total_past + $cost_focus_b[$company->company_id];
			}
		}


	echo '</div>';



	echo '<div class="clearfix" style="padding-top: 6px;    border-top: 1px solid #eee;">
	<i class="fa fa-briefcase" style="font-size: 42px;float: left;margin-left: 7px;margin-right: 10px;"></i>
	<div class="" id="">
		<p>
			<span class="pull-right">
				<span class="label pull-right m-bottom-3 m-top-3 small_orange_fixed tooltip-enabled" title="" data-placement="left" data-html="true" data-original-title="'.$current_year.'" style="width: 150px;"><i class="fa fa-usd"></i> '.number_format($total_present,2).'</span> <br> 
				<span class="label pull-right m-bottom-3 small_green_fixed tooltip-enabled" title="" data-placement="left" data-html="true" data-original-title="'.$last_year.'" style="width: 150px; background-color: #aaa !important;"><i class="fa fa-exclamation-triangle"></i> '.number_format($total_past,2).'</span>
			</span>
			<strong style="padding-top: 10px; display: block;">Overall Focus</strong>
		</p>

	</div>
			</div>';



	}






	function estimators_wip($es_id=''){
		$estimators_cost = array();
		$estimator_name = array();
		$estimator_counter = array();
		$total_string = '';

		$estimator = $this->dashboard_m->fetch_project_estimators();
		$estimator_list = $estimator->result();
		foreach ($estimator_list as $est ) {
			$estimators_cost[$est->project_estiamator_id] = 0;
			$estimator_counter[$est->project_estiamator_id] = 0;
			$estimator_name[$est->project_estiamator_id] = $est->user_first_name;
		}

		if($es_id != ''){
			$estimators_cost[$es_id] = 0;
			$estimator_counter[$es_id] = 0;
		}

		$estimators_cost[0] = 0;
		$estimator_counter[0] = 0;
		$estimator_name[0] = '';

		$all_estimators_wip_q = $this->dashboard_m_es->fetch_estimators_wip();
		$all_estimators_wip = $all_estimators_wip_q->result();

		//var_dump($all_estimators_wip);

		$loop_counter = 0;

		foreach ($all_estimators_wip as $es_wip){
			
				if ( array_key_exists($es_wip->project_estiamator_id, $estimators_cost) ) {		
					$total_cost = $es_wip->project_total + $es_wip->variation_total + $estimators_cost[$es_wip->project_estiamator_id];
					$estimators_cost[$es_wip->project_estiamator_id] = $total_cost;	
				}

				if ( array_key_exists($es_wip->project_estiamator_id, $estimator_counter) ) {		
					$estimator_counter[$es_wip->project_estiamator_id]++;
				}
		}

		if($es_id != ''){
			$display_cost = $estimators_cost[$es_id];
			$display_counter = $estimator_counter[$es_id];
		}else{
			$display_cost = array_sum($estimators_cost);
			$display_counter = array_sum($estimator_counter);
		}

		foreach ($estimator_list as $est ) {
			$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$estimator_name[$est->project_estiamator_id].'</span> <span class=\'col-xs-6\'>$ '.number_format($estimators_cost[$est->project_estiamator_id],2).' <span class=\'pull-right\'>'.$estimator_counter[$est->project_estiamator_id].'</span></span></div>';
		}

		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><strong><i class="fa fa-usd"></i> '.number_format($display_cost,2).' <span class="pull-right">'.number_format($display_counter).'</span></strong></p>';
	}



	function completed_prjs($es_id=''){
		$total_string = '';
		
		$this_year = date("Y");
		$n_year = $this_year+1;
		$date_b = "01/01/$n_year";

		$this_year_a = "01/01/$this_year";
		$this_year_b = date("d/m/Y");

		$old_year = intval(date("Y"))-1;
		$old_month = date("m");
		$old_day = date("d");

		$date_a_last = "01/01/$old_year";
		$date_b_last = "$old_day/$old_month/$old_year";

		$display_cost = 0;
		$display_counter = 0;

		$total_string .= '<div class=\'row\'> &nbsp; ('.$this_year.')</div>';

		$estimator = $this->dashboard_m->fetch_project_estimators();
		$estimator_list = $estimator->result();
		foreach ($estimator_list as $est ) {
			$cost_total = 0;
			$counter = 0;
		//	$estimators_cost[$est->project_estiamator_id] = 0;
		//	$estimator_counter[$est->project_estiamator_id] = 0;
		//	$estimator_name[$est->project_estiamator_id] = $est->user_first_name;

			$q_est_completed = $this->dashboard_m_es->fetch_completed($this_year_a,$this_year_b,$est->project_estiamator_id);
			$completed = $q_est_completed->result();

			foreach ($completed as $est_cmp ) {

				$cost_total = $cost_total  + $est_cmp->variation_total + $est_cmp->project_total;
				$counter++;
			}

			if($es_id != '' && $es_id == $est->project_estiamator_id){
				$display_cost = $cost_total;
				$display_counter = $counter;
			}

			if($es_id == ''){
				$display_cost = $display_cost + $cost_total;
				$display_counter = $display_counter + $counter;
			}

			$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$est->user_first_name.'</span> <span class=\'col-xs-6\'>$ '.number_format($cost_total,2).' <span class=\'pull-right\'>'.$counter.'</span></span></div>';
		}

		$total_string .= '<div class=\'row\'><span class=\'col-xs-12\'><hr style=\'margin:4px 0px;\' /></span> &nbsp; ('.$old_year.')</div>';

		foreach ($estimator_list as $est ) {
			$cost_total = 0;
			$counter = 0;
		//	$estimators_cost[$est->project_estiamator_id] = 0;
		//	$estimator_counter[$est->project_estiamator_id] = 0;
		//	$estimator_name[$est->project_estiamator_id] = $est->user_first_name;

			$q_est_completed = $this->dashboard_m_es->fetch_completed($date_a_last,$date_b_last,$est->project_estiamator_id);
			$completed = $q_est_completed->result();

			foreach ($completed as $est_cmp ) {

				$cost_total = $cost_total  + $est_cmp->variation_total + $est_cmp->project_total;
				$counter++;
			}

			// if($es_id != '' && $es_id == $est->project_estiamator_id){
			// 	$display_cost = $cost_total;
			// 	$display_counter = $counter;
			// }

			$total_string .= '<div class=\'row\'><span class=\'col-xs-6\'>'.$est->user_first_name.'</span> <span class=\'col-xs-6\'>$ '.number_format($cost_total,2).' <span class=\'pull-right\'>'.$counter.'</span></span></div>';
		}

		echo '<p class="value tooltip-enabled" title="" data-html="true" data-placement="bottom" data-original-title="'.$total_string.'"><strong><i class="fa fa-usd"></i> '.number_format($display_cost,2).' <span class="pull-right">'.number_format($display_counter).'</span></strong></p>';
	}
}