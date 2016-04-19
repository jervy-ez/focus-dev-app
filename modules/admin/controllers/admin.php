<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin extends MY_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('upload');		
		$this->load->module('users'); 			
		$this->load->module('company'); 	

		$this->load->model('admin_m');
		if(!$this->users->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;
		/*if($this->session->userdata('is_admin') != 1 ):		
			redirect('', 'refresh');
		endif;*/
	}
	
	public function index($dataPass = array()){	
		if($this->session->userdata('is_admin') != 1 ):		
			redirect('', 'refresh');
		endif;

		$data_c['main_content'] = 'admin_v';
		$data_c['screen'] = 'Admin Defaults';
		
		//fetch site cost
		$q_site_cost = $this->admin_m->fetch_site_costs();
		$data_a = array_shift($q_site_cost->result_array());

		if($data_a==''){
			$data_a = array();
		}
		//fetch site cost

		//admin_defaulst
		$q_admin_defaults = $this->admin_m->fetch_admin_defaults();
		$data_b = array_shift($q_admin_defaults->result_array());
		//admin_defaulst

		//default email message
		$q_admin_default_email_message = $this->admin_m->fetch_admin_default_email_message();
		$data_e = array_shift($q_admin_default_email_message->result_array());	
		$data['emai_message'] = $this->admin_m->fetch_admin_default_email_message();
		//default email message

		//markups
		$q_admin_markup = $this->admin_m->fetch_markup();
		$data_d = array_shift($q_admin_markup->result_array());		
		//markups

		//labour cost matrix
		$q_labour_cost = $this->admin_m->fetch_labour_cost();
		$labour_cost = array_shift($q_labour_cost->result_array());
		//labour cost matrix

	
		$data = array_merge($data_a,$data_b,$data_c,$data_d,$labour_cost,$dataPass,$data_e);
		$gp_data_arr = $this->get_double_amalgated_rate($data_a,$labour_cost,$data_b);

		$data['gp_on_cost_total_hr'] = $gp_data_arr['gp_on_cost_total_hr'];
		$data['gp_on_cost_time_half_hr'] = $gp_data_arr['gp_on_cost_time_half_hr'];
		$data['gp_on_cost_time_double_hr'] = $gp_data_arr['gp_on_cost_time_double_hr'];
		$data['gp_amalgamated_rate'] = $gp_data_arr['gp_amalgamated_rate'];
		$data['grand_total'] = $gp_data_arr['gp_grand_total'];
		$data['leave_percentage'] = $gp_data_arr['gp_leave_percentage'];

		
		$static_defaults = $this->user_model->select_static_defaults();
		$data['static_defaults'] = $static_defaults->result();

		$this->load->view('page', $data);
	}


	public function fetch_users_list(){
		$user_list = $this->user_model->fetch_user();
		
		//echo '<option value="">'.$select_user.'</option>';
		foreach ($user_list->result_array() as $row){
			
			echo '<option value="'.$row['user_id'].'">'.$row['user_first_name'].' '.$row['user_last_name'].'</option>';
			
		}
	}


	public function get_double_amalgated_rate($site_costs,$labour_cost,$admin_defaults){
		$rate = $site_costs['rate'];

		$leave_percentage = ($labour_cost['total_leave_days']/$labour_cost['total_work_days'])*100;
		$leave_percentage = round($leave_percentage, 2);
		$gp_grand_total = $leave_percentage+$labour_cost['superannuation']+$labour_cost['workers_compensation']+$labour_cost['payroll_tax']+$labour_cost['leave_loading']+$labour_cost['other'];  

		$payroll_tax = $labour_cost['payroll_tax'];

		$gp_on_cost_total_hr = round($rate+(($rate*$gp_grand_total)/100),2);
		$gp_on_cost_time_half_hr = round($gp_on_cost_total_hr + ((0.5*$rate) + (((0.5*$rate)*$payroll_tax )/100)),2);
		$gp_on_cost_time_double_hr = round($gp_on_cost_total_hr + ($rate+(($rate*$payroll_tax)/100)),2);

		$amalgamated_rate = ($admin_defaults['labor_split_time_and_half']/100)*$gp_on_cost_time_half_hr + ($admin_defaults['labor_split_double_time']/100)*$gp_on_cost_time_double_hr + ($admin_defaults['labor_split_standard']/100)*$gp_on_cost_total_hr;

		$gp_amalgamated_rate = round($amalgamated_rate, 2);

		$result = array(
			"gp_on_cost_total_hr" => $gp_on_cost_total_hr,
			"gp_on_cost_time_half_hr" => $gp_on_cost_time_half_hr,
			"gp_on_cost_time_double_hr" => $gp_on_cost_time_double_hr,
			"gp_amalgamated_rate" => $gp_amalgamated_rate,
			"gp_grand_total" => $gp_grand_total,
			"gp_leave_percentage" => $leave_percentage
			);

		return $result;
	}

	public function matrix(){


		//$data_c['main_content'] = 'admin_v';
		//$this->load->view('page', $data);

		$this->form_validation->set_rules('total_days', 'Total Days','trim|required|xss_clean');
		$this->form_validation->set_rules('rate', 'Rate','trim|required|xss_clean');
		$this->form_validation->set_rules('hours', 'Hours','trim|required|xss_clean');
		$this->form_validation->set_rules('superannuation', 'Superannuation','trim|required|xss_clean');
		$this->form_validation->set_rules('workers-comp', 'Workers Compensation','trim|required|xss_clean');
		$this->form_validation->set_rules('public-holidays', 'Public Holidays','trim|required|xss_clean');
		$this->form_validation->set_rules('rdos', 'Rdos','trim|required|xss_clean');
		$this->form_validation->set_rules('sick-leave', 'Sick Leave','trim|required|xss_clean');
		$this->form_validation->set_rules('carers-leave', 'Carers Leave','trim|required|xss_clean');
		$this->form_validation->set_rules('annual-leave', 'Annual Leave','trim|required|xss_clean');
		$this->form_validation->set_rules('downtime', 'Downtime','trim|required|xss_clean');
		$this->form_validation->set_rules('leave-loading', 'Leave Loading','trim|required|xss_clean');


		$admin_defaults_raw = $this->admin_m->fetch_admin_defaults();
		$admin_defaults = array_shift($admin_defaults_raw->result_array());

		$markup_raw = $this->admin_m->fetch_markup();
		$markup = array_shift($markup_raw->result_array());

		$labour_cost_raw = $this->admin_m->fetch_labour_cost();
		$labour_cost = array_shift($labour_cost_raw->result_array());



		if($this->form_validation->run() === false){
			$passArr['matrix_errors'] = validation_errors();
			$this->index($passArr);
		}else{

			$hour_side = array();
			$time_half_side = array();
			$double_time = array();

			$data['total_days'] = $this->input->post('total_days', true);

			$data['hour_rate'] = $this->input->post('rate', true);
			$data['time_half_rate'] = $data['hour_rate'] + ($data['hour_rate'] * 0.5);
			$data['double_time_rate'] = $data['hour_rate'] + ($data['hour_rate'] * 1);

			$data['hours'] = $this->input->post('hours', true);
			$data['superannuation'] = $this->input->post('superannuation', true);
			$data['workers-comp'] = $this->input->post('workers-comp', true);

			$data['public-holidays_raw'] = $this->input->post('public-holidays', true);			
			$data['rdos_raw'] = $this->input->post('rdos', true);			
			$data['sick-leave_raw'] = $this->input->post('sick-leave', true);			
			$data['carers-leave_raw'] = $this->input->post('carers-leave', true);			
			$data['annual-leave_raw'] = $this->input->post('annual-leave', true);

			$data['public-holidays'] = round(($this->input->post('public-holidays', true)/$data['total_days'])*100, 2 );			
			$data['rdos'] = round(($this->input->post('rdos', true)/$data['total_days'])*100, 2 );			
			$data['sick-leave'] = round(($this->input->post('sick-leave', true)/$data['total_days'])*100, 2 );			
			$data['carers-leave'] = round(($this->input->post('carers-leave', true)/$data['total_days'])*100, 2 );			
			$data['annual-leave'] = round(($this->input->post('annual-leave', true)/$data['total_days'])*100, 2 );

			$data['downtime'] = $this->input->post('downtime', true);
			$data['leave-loading'] = $this->input->post('leave-loading', true);

			array_push($hour_side, $data['hour_rate']);
			array_push($hour_side, round(($data['hour_rate']*$data['superannuation'] )/100, 2 ) );
			array_push($hour_side, round(($data['hour_rate']*$data['workers-comp'] )/100, 2));

			array_push($hour_side, round(($data['hour_rate']* $data['public-holidays'] )/100, 2));
			array_push($hour_side, round(($data['hour_rate']*$data['rdos'] )/100, 2));
			array_push($hour_side, round(($data['hour_rate']*$data['sick-leave'] )/100, 2));
			array_push($hour_side, round(($data['hour_rate']*$data['carers-leave'] )/100, 2));
			array_push($hour_side, round((($data['hour_rate']*$data['annual-leave'])+0.17 *($data['hour_rate']*$data['annual-leave'])  ) /100, 2) );			
			array_push($hour_side, round(($data['hour_rate']*$data['downtime'] )/100, 2));
			array_push($hour_side, round(($data['hour_rate']*$data['leave-loading'] )/100, 2));

			array_push($time_half_side, $data['time_half_rate']);
			array_push($time_half_side, round(($data['time_half_rate']*$data['superannuation'] )/100, 2 ) );
			array_push($time_half_side, round(($data['time_half_rate']*$data['workers-comp'] )/100, 2));

			array_push($time_half_side, $hour_side[3]);
			array_push($time_half_side, $hour_side[4]);
			array_push($time_half_side, $hour_side[5]);
			array_push($time_half_side, $hour_side[6]);
			array_push($time_half_side, $hour_side[7]);
			array_push($time_half_side, $hour_side[8]);
			array_push($time_half_side, $hour_side[9]);

			array_push($double_time, $data['double_time_rate']);
			array_push($double_time, round(($data['double_time_rate']*$data['superannuation'] )/100, 2 ) );
			array_push($double_time, round(($data['double_time_rate']*$data['workers-comp'] )/100, 2));

			array_push($double_time, $hour_side[3]);
			array_push($double_time, $hour_side[4]);
			array_push($double_time, $hour_side[5]);
			array_push($double_time, $hour_side[6]);
			array_push($double_time, $hour_side[7]);
			array_push($double_time, $hour_side[8]);
			array_push($double_time, $hour_side[9]);

			$data['hour_rate_comp'] = array_sum($hour_side);
			$data['time_half_rate_comp'] = array_sum($time_half_side);
			$data['double_time_rate_comp'] = array_sum($double_time);

			$standard = $data['hour_rate_comp'] * ($admin_defaults['labor_split_standard']/100);
			$time_half = $data['time_half_rate_comp'] * ($admin_defaults['labor_split_time_and_half']/100);
			$double_time_comp = $data['double_time_rate_comp'] * ($admin_defaults['labor_split_double_time']/100);

			$data['total_amalgamated_rate'] = $standard + $time_half + $double_time_comp;

			$new_site_cost_id = $this->admin_m->update_site_costs($data);

			$this->admin_m->insert_latest_system_default($new_site_cost_id,$admin_defaults['admin_default_id'],$markup['markup_id'],$labour_cost['labour_cost_id']);

			$update_success = 'The record is now updated.';
			//$this->session->set_flashdata('update_company_id', $company_id);
			$this->session->set_flashdata('update_matrix', $update_success);
			//$this->index($passArr);
			redirect('/admin');

		}

//		redirect('/admin');
	}

	public function defaults(){
		$passArr = array();

		$this->form_validation->set_rules('gst-rate', 'GST Rate','trim|required|xss_clean');
		$this->form_validation->set_rules('installation-labour', 'Installation Labour','trim|required|xss_clean');
		$this->form_validation->set_rules('time-half', 'Time & Half Labour','trim|required|xss_clean');
		$this->form_validation->set_rules('double-time', 'Double Time','trim|required|xss_clean');
		$this->form_validation->set_rules('standard-labour', 'Standard Labour','trim|required|xss_clean');


		$admin_defaults_raw = $this->admin_m->fetch_admin_defaults();
		$admin_defaults = array_shift($admin_defaults_raw->result_array());

		$site_costs_raw = $this->admin_m->fetch_site_costs();
		$site_costs = array_shift($site_costs_raw->result_array());

		$markup_raw = $this->admin_m->fetch_markup();
		$markup = array_shift($markup_raw->result_array());

		$labour_cost_raw = $this->admin_m->fetch_labour_cost();
		$labour_cost = array_shift($labour_cost_raw->result_array());

		if($this->form_validation->run() === false){
			$passArr['default_errors'] = validation_errors();
			$this->index($passArr);
		}else{			

			$data['gst-rate'] = $this->input->post('gst-rate', true);
			$data['installation-labour'] = $this->input->post('installation-labour', true);
			$data['time-half'] = $this->input->post('time-half', true);
			$data['double-time'] = $this->input->post('double-time', true);
			$data['standard-labour'] = $this->input->post('standard-labour', true);

			$data['labor_split_standard'] = 100 - ($data['time-half'] + $data['double-time']);
			
			$new_admin_defaults_id = $this->admin_m->update_admin_defaults($data);



			$standard = $site_costs['total_hour'] * ($data['labor_split_standard']/100);
			$time_half = $site_costs['total_time_half'] * ($data['time-half']/100);
			$double_time_comp = $site_costs['total_double_time'] * ($data['double-time']/100);

			$amalgamated_rate = $standard + $time_half + $double_time_comp;

			//echo '<script type="text/javascript">alert("'.$data['labor_split_standard'].' '.$data['time-half'].' '.$data['double-time'].'");</script>';

			$this->admin_m->update_amalgamated_rate($amalgamated_rate);

			$update_success = 'The record is now updated.';
			//$this->session->set_flashdata('update_company_id', $company_id);
			$this->session->set_flashdata('update_default', $update_success);	


			$this->admin_m->insert_latest_system_default($site_costs['site_cost_id'],$new_admin_defaults_id,$markup['markup_id'],$labour_cost['labour_cost_id']);



			redirect('/admin');
		}
	}

	public function project_mark_up(){


		$admin_defaults_raw = $this->admin_m->fetch_admin_defaults();
		$admin_defaults = array_shift($admin_defaults_raw->result_array());

		$site_costs_raw = $this->admin_m->fetch_site_costs();
		$site_costs = array_shift($site_costs_raw->result_array());

		$labour_cost_raw = $this->admin_m->fetch_labour_cost();
		$labour_cost = array_shift($labour_cost_raw->result_array());
		

		$passArr = array();

		$this->form_validation->set_rules('kiosk', 'Kiosk','trim|required|xss_clean');
		$this->form_validation->set_rules('full-fitout', 'Full Fitout','trim|required|xss_clean');
		$this->form_validation->set_rules('refurbishment', 'Refurbishment','trim|required|xss_clean');
		$this->form_validation->set_rules('stripout', 'Stripout','trim|required|xss_clean');
		$this->form_validation->set_rules('maintenance', 'Maintenance','trim|required|xss_clean');
		$this->form_validation->set_rules('minor-works', 'Minor Works','trim|required|xss_clean');

		$this->form_validation->set_rules('min_kiosk', 'Minimum Kiosk Markup','trim|xss_clean');
		$this->form_validation->set_rules('min_full_fitout', 'Minimum Full Fitout Markup','trim|xss_clean');
		$this->form_validation->set_rules('min_refurbishment', 'Minimum Refurbishment Markup','trim|xss_clean');
		$this->form_validation->set_rules('min_stripout', 'Minimum Stripout Markup','trim|xss_clean');
		$this->form_validation->set_rules('min_maintenance', 'Minimum Maintenance Markup','trim|xss_clean');
		$this->form_validation->set_rules('min_minor_works', 'Minimum Minor Works Markup','trim|xss_clean');



		$this->form_validation->set_rules('design_works', 'Design Works','trim|required|xss_clean');

		$this->form_validation->set_rules('min_design_works', 'Minimum Design Works','trim|xss_clean');

		if($this->form_validation->run() === false){
			$passArr['default_errors'] = validation_errors();
			$this->index($passArr);
		}else{			

			$kiosk = $this->input->post('kiosk', true);
			$full_fitout = $this->input->post('full-fitout', true);
			$refurbishment = $this->input->post('refurbishment', true);
			$stripout = $this->input->post('stripout', true);
			$maintenance = $this->input->post('maintenance', true);
			$minor_works = $this->input->post('minor-works', true);


			$min_kiosk = $this->input->post('min_kiosk', true);
			$min_full_fitout = $this->input->post('min_full_fitout', true);
			$min_refurbishment = $this->input->post('min_refurbishment', true);
			$min_stripout = $this->input->post('min_stripout', true);
			$min_maintenance = $this->input->post('min_maintenance', true);
			$min_minor_works = $this->input->post('min_minor_works', true);



			$design_works = $this->input->post('design_works', true);
			$min_design_works = $this->input->post('min_design_works', true);
						
			$new_mark_up_id = $this->admin_m->updat_project_mark_up($kiosk,$full_fitout,$refurbishment,$stripout,$maintenance,$minor_works,$min_kiosk,$min_full_fitout,$min_refurbishment,$min_stripout,$min_maintenance,$min_minor_works,$design_works,$min_design_works);

			$this->admin_m->insert_latest_system_default($site_costs['site_cost_id'],$admin_defaults['admin_default_id'],$new_mark_up_id,$labour_cost['labour_cost_id']);

			$update_success = 'The project mark-up is now updated.';
			//$this->session->set_flashdata('update_company_id', $company_id);
			$this->session->set_flashdata('update_prj_mrk', $update_success);
			//$this->index($passArr);
			
			redirect('/admin');
		}	
	}

	public function invoice_email(){
		$passArr = array();

		$this->form_validation->set_rules('recipient_email', 'Days Password Expiration','trim|required|xss_clean');
		$this->form_validation->set_rules('optional_cc_email', 'Temporary User Password','trim|xss_clean');

		if($this->form_validation->run() === false){
			$passArr['invoice_email_errors'] = validation_errors();
			$this->index($passArr);
		}else{
			$recipient_email = $this->input->post('recipient_email', true);
			$optional_cc_email = $this->input->post('optional_cc_email', true);
			
			$this->admin_m->update_static_settings_invoice_email($recipient_email,$optional_cc_email);

			$update_success = 'The User Accounts Setting is now updated.';
			$this->session->set_flashdata('invoice_default_email', $update_success);
			redirect('/admin');
		}
	}


	public function user_settings(){
		$passArr = array();

		$this->form_validation->set_rules('days_exp', 'Days Password Expiration','trim|required|xss_clean');
		$this->form_validation->set_rules('temp_password', 'Temporary User Password','trim|required|xss_clean');


		if($this->form_validation->run() === false){
			$passArr['error'] = validation_errors();
			$this->index($passArr);
		}else{

			$days_exp = $this->input->post('days_exp', true);
			$temp_password = $this->input->post('temp_password', true);
			
			$this->admin_m->update_static_settings($days_exp,$temp_password);

			$update_success = 'The User Accounts Setting is now updated.';
			$this->session->set_flashdata('update_user_settings', $update_success);
			redirect('/admin');
		}	


	}





	public function labour_cost_matrix(){
		$passArr = array();

		$admin_defaults_raw = $this->admin_m->fetch_admin_defaults();
		$admin_defaults = array_shift($admin_defaults_raw->result_array());

		$site_costs_raw = $this->admin_m->fetch_site_costs();
		$site_costs = array_shift($site_costs_raw->result_array());

		$markup_raw = $this->admin_m->fetch_markup();
		$markup = array_shift($markup_raw->result_array());

		$this->form_validation->set_rules('superannuation', 'Superannuation','trim|required|xss_clean');
		$this->form_validation->set_rules('workers_compensation', 'Workers Compensation','trim|required|xss_clean');
		$this->form_validation->set_rules('payroll_tax', 'Payroll Tax','trim|required|xss_clean');
		$this->form_validation->set_rules('leave_loading', 'Leave Loading','trim|required|xss_clean');
		$this->form_validation->set_rules('other', 'Other','trim|required|xss_clean');
		$this->form_validation->set_rules('total_leave_days', 'Total Leave Days','trim|required|xss_clean');
		$this->form_validation->set_rules('total_work_days', 'Total Work Days','trim|required|xss_clean');


		if($this->form_validation->run() === false){
			$passArr['cost_labour_matrix_errors'] = validation_errors();
			$this->index($passArr);
		}else{

			$superannuation = $this->input->post('superannuation', true);
			$workers_compensation = $this->input->post('workers_compensation', true);
			$payroll_tax = $this->input->post('payroll_tax', true);
			$leave_loading = $this->input->post('leave_loading', true);
			$other = $this->input->post('other', true);
			$total_leave_days = $this->input->post('total_leave_days', true);
			$total_work_days = $this->input->post('total_work_days', true);

			$new_labour_cost_matrix = $this->admin_m->insert_labour_cost_matrix($superannuation,$workers_compensation,$payroll_tax,$leave_loading,$other,$total_leave_days,$total_work_days);

			$this->admin_m->insert_latest_system_default($site_costs['site_cost_id'],$admin_defaults['admin_default_id'],$markup['markup_id'],$new_labour_cost_matrix);

			$update_success = 'The Site Labour Cost Matrix is now updated.';
			$this->session->set_flashdata('update_labour_cost_matrix', $update_success);
			redirect('/admin');
		}	


	}

	public function company(){
		if($this->session->userdata('is_admin') != 1 ):		
			redirect('', 'refresh');
		endif;
				
		$data['main_content'] = 'admin_company';
		$data['screen'] = 'Focus Company';		

		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$data['all_focus_company'] = $all_focus_company->result();

		if($data['all_focus_company']==''){
			$data['all_focus_company'] = array();
		}

		$this->load->view('page', $data);
	}

	public function admin_company(){

		$curr_admin_id = $this->uri->segment(3);

		$admin_company_details = $this->admin_m->fetch_single_company_focus($curr_admin_id);


		$config['upload_path'] = './uploads/misc/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '1024';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';

		$time = mdate("%h%i%s%m%d%Y", time());
		$config['file_name']  = 'focus_'.$time;

		$this->upload->initialize($config);

		$this->load->library('upload', $config);
		$this->upload->initialize($config);	


		if(isset($_POST['company_id_data'])){
			if ( ! $this->upload->do_upload('focus_company_logo')){
				$upload_error = array('error' => $this->upload->display_errors());
				//var_dump($upload_error);
				//$data['upload_error'] = $upload_error;
				echo '<script type="text/javascript">alert("';foreach ($upload_error as $key => $value) {echo strip_tags($value).'\n';}echo '");</script>';
				//$upload_has_error = 1;
			}else{
				$up_data = array('upload_data' => $this->upload->data());
				$logo = $up_data['upload_data']['file_name'];
				//$upload_has_error = 0;

				$comp_id = $_POST['company_id_data'];

				$this->admin_m->updat_admin_comp_logo($comp_id,$logo);
			}
		}



		$data = array_shift($admin_company_details->result_array() );
		$data['main_content'] = 'admin_v_company';
		$data['screen'] = 'Focus Company';


		$all_aud_states = $this->company_m->fetch_all_states();
		$data['all_aud_states'] = $all_aud_states->result();


		$query_address= $this->company_m->fetch_complete_detail_address($data['address_id']);
		$temp_data = array_shift($query_address->result_array());
		$data['postcode'] = $temp_data['postcode'];
		$data['suburb'] = $temp_data['suburb'];
		$data['po_box'] = $temp_data['po_box'];
		$data['street'] = ucwords(strtolower($temp_data['street']));
		$data['unit_level'] = ucwords(strtolower($temp_data['unit_level']));
		$data['unit_number'] = $temp_data['unit_number'];
		$data['state'] = $temp_data['name'];
		// $data['address_id'] = $data['address_id'];

		$data['shortname'] = $temp_data['shortname'];
		$data['state_id'] =  $temp_data['state_id'];
		$data['phone_area_code'] = $temp_data['phone_area_code'];	

		$p_query_address = $this->company_m->fetch_complete_detail_address($data['postal_address_id']);
		$p_temp_data = array_shift($p_query_address->result_array());
		$data['p_po_box'] = $p_temp_data['po_box'];
		$data['p_unit_level'] = ucwords(strtolower($p_temp_data['unit_level']));
		$data['p_unit_number'] = $p_temp_data['unit_number'];
		$data['p_street'] = ucwords(strtolower($p_temp_data['street']));
		$data['p_suburb'] = $p_temp_data['suburb'];
		$data['p_state'] = $p_temp_data['name'];
		$data['p_postcode'] = $p_temp_data['postcode'];
//		$data['postal_address_id'] = $company_detail['postal_address_id'];

		$data['p_shortname'] = $p_temp_data['shortname'];
		$data['p_state_id'] =  $p_temp_data['state_id'];
		$data['p_phone_area_code'] = $p_temp_data['phone_area_code'];

		$this->load->view('page', $data);
	}





	public function add(){

		$this->form_validation->set_rules('company_name', 'Company Name','trim|required|xss_clean');

		$this->form_validation->set_rules('unit_level', 'Physical Unit/Level', 'trim|xss_clean');
		$this->form_validation->set_rules('unit_number', 'Physical Number', 'trim|xss_clean');
		$this->form_validation->set_rules('street', 'Physical Street', 'trim|required|xss_clean');
		$this->form_validation->set_rules('state_a', 'State', 'trim|required|xss_clean');
		$this->form_validation->set_rules('suburb_a', 'Suburb', 'trim|required|xss_clean');
		$this->form_validation->set_rules('postcode_a', 'Postcode', 'trim|required|xss_clean');

		$this->form_validation->set_rules('pobox', 'PO Box', 'trim|xss_clean');
		$this->form_validation->set_rules('unit_level_b', 'Postal Unit/Level', 'trim|xss_clean');
		$this->form_validation->set_rules('number_b', 'Postal Number', 'trim|xss_clean');
		$this->form_validation->set_rules('street_b', 'Postal Street', 'trim|xss_clean');
		$this->form_validation->set_rules('state_b', 'Postal State', 'trim|xss_clean');
		$this->form_validation->set_rules('suburb_b', 'Postal Suburb', 'trim|xss_clean');
		$this->form_validation->set_rules('postcode_b', 'Postal Postcode', 'trim|xss_clean');

		$this->form_validation->set_rules('abn', 'ABN', 'trim|required|xss_clean');
		$this->form_validation->set_rules('acn', 'ACN', 'trim|required|xss_clean');
		$this->form_validation->set_rules('areacode', 'Phone Areacode', 'trim|required|xss_clean');
		$this->form_validation->set_rules('contact_number', 'Contact Number', 'trim|required|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');

		$this->form_validation->set_rules('account-name', 'Account Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('bank-name', 'Bank Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('account-number', 'Account Number', 'trim|required|xss_clean');
		$this->form_validation->set_rules('bsb-number', 'BSB Number', 'trim|required|xss_clean');	


		$config['upload_path'] = './uploads/misc/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '1024';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';

		$time = mdate("%h%i%s%m%d%Y", time());
		$config['file_name']  = 'focus_'.$time;

		$this->upload->initialize($config);

		$this->load->library('upload', $config);
		$this->upload->initialize($config);	

		$upload_has_error = 1;	


		if(isset($_POST['is_form_submit'])){
			if ( ! $this->upload->do_upload('company_logo')){
				$upload_error = array('error' => $this->upload->display_errors());
				$data['upload_error' ] = $upload_error;
				$upload_has_error = 1;
			}else{
				$up_data = array('upload_data' => $this->upload->data());
				$logo = $up_data['upload_data']['file_name'];
				$upload_has_error = 0;
			}
		}else{
			$upload_has_error = 1;
		}







		if($this->form_validation->run() === false || $upload_has_error == 1){

			$data['main_content'] = 'new_company';
			$data['screen'] = 'New Focus Company';

			$all_aud_states = $this->company_m->fetch_all_states();
			$data['all_aud_states'] = $all_aud_states->result();

			$data['error' ] = validation_errors();
			$this->load->view('page', $data);
		}else{
			$data['company_name'] = $this->cap_first_word($this->if_set($this->input->post('company_name', true)));

			$data['unit_level'] = $this->if_set($this->input->post('unit_level', true));
			$data['unit_number'] = $this->if_set($this->input->post('unit_number', true));
			$data['street'] = $this->cap_first_word($this->if_set($this->input->post('street', true)));

			$state_a_arr = explode('|', $this->input->post('state_a', true));
			$data['state_a'] = $state_a_arr[3];

			$suburb_a_ar = explode('|',$this->if_set($this->input->post('suburb_a', true)));
			$data['suburb_a'] = strtoupper($suburb_a_ar[0]);

			$data['postcode_a'] = $this->if_set($this->input->post('postcode_a', true));

			$data['pobox'] = $this->if_set($this->input->post('pobox', true));

			$data['unit_level_b'] = $this->if_set($this->input->post('unit_level_b', true));
			$data['number_b'] = $this->if_set($this->input->post('number_b', true));
			$data['street_b'] = $this->cap_first_word($this->if_set($this->input->post('street_b', true)));

			$state_b_arr = explode('|', $this->input->post('state_b', true));
			$data['state_b'] = $state_b_arr[3];

			$suburb_b_ar = explode('|',$this->if_set($this->input->post('suburb_b', true)));
			$data['suburb_b'] = strtoupper($suburb_b_ar[0]);


			$data['postcode_b'] = $this->if_set($this->input->post('postcode_b', true));


			$data['email'] = $this->if_set($this->input->post('email', true));
			$data['contact_number'] = $this->if_set($this->input->post('contact_number', true));
			$data['mobile_number'] = $this->if_set($this->input->post('mobile_number', true));
			$data['areacode'] = $this->if_set($this->input->post('areacode', true));

			$data['abn'] = $this->if_set($this->input->post('abn', true));
			$data['acn'] = $this->if_set($this->input->post('acn', true));
			
			$data['bank-account-name'] = $this->cap_first_word($this->if_set($this->input->post('account-name',true)));
			$data['bank-name'] = $this->cap_first_word($this->if_set($this->input->post('bank-name',true)));
			$data['bank-account-number'] = $this->cap_first_word($this->if_set($this->input->post('account-number',true)));
			$data['bsb-number'] = $this->cap_first_word($this->if_set($this->input->post('bsb-number',true)));


			




			$general_address_id_result_a = $this->company_m->fetch_address_general_by('postcode-suburb',$data['postcode_a'],$data['suburb_a']);
			foreach ($general_address_id_result_a->result() as $general_address_id_a){
				$general_address_a = $general_address_id_a->general_address_id;
			}

			$general_address_id_result_b = $this->company_m->fetch_address_general_by('postcode-suburb',$data['postcode_b'],$data['suburb_b']);
			foreach ($general_address_id_result_b->result() as $general_address_id_b){
				$general_address_b = $general_address_id_b->general_address_id;
			}

			$jurisdiction_raw = $this->input->post('jurisdiction',true);
			$jurisdiction = '';
			foreach ($jurisdiction_raw as $key => $value) {
				$jur_arr = explode('|', $value);
				$jurisdiction .= $jur_arr[3].',';
			}
			$jurisdiction = substr($jurisdiction, 0, -1);


			$bank_account_id = $this->company_m->insert_bank_account($data['bank-account-name'],$data['bank-account-number'],$data['bank-name'],$data['bsb-number']);



			$address_id = $this->company_m->insert_address_detail($data['street'],$general_address_a,$data['unit_level'],$data['unit_number']);

			$postal_address_id = $this->company_m->insert_address_detail($data['street_b'],$general_address_b,$data['unit_level_b'],$data['number_b'],$data['pobox']);
			

			$new_company_id = $this->company_m->insert_company_details($data['company_name'],$data['abn'],$data['acn'] ,'0',$address_id,$postal_address_id,'5',$bank_account_id,'0','0');


			$contact_number_id = $this->company_m->insert_contact_number($data['areacode'],$data['contact_number'],'',$data['mobile_number'],'');

			$email_id = $this->company_m->insert_email($data['email']);


			$this->admin_m->insert_focus_company_details($new_company_id,$contact_number_id,$email_id,$jurisdiction,$logo);
			$this->session->set_flashdata('new_focus_company', 'New Focus Company is now added.');
			redirect('/admin/company');
			//process input


		}


	}

	
	public function view(){
		//$data['new_compamy_id'] = $this->session->userdata('item');
		if($this->session->flashdata('new_company_id') == $this->uri->segment(3)  && $this->uri->segment(3)!='' ){					
			$company_id = $this->session->flashdata('new_company_id');
			$q_comp = $this->company_m->fetch_company_details($company_id);
			
			if($q_comp->num_rows > 0){
				
				$data = array_shift($q_comp->result_array());
				//echo $qArr['company_name'];
			}else{
				$data['error'] = 'Unable to locate record';
			}
			$data['success'] = 'New Company is now added, you can see the details here, to edit just click the button below.';
			
		}else if(($this->session->flashdata('new_company_id') != $this->uri->segment(3)) ){
			$company_id = $this->uri->segment(3);
			$q_comp = $this->company_m->fetch_company_details($company_id);
			if($q_comp->num_rows > 0){
				$data = array_shift($q_comp->result_array());
				//echo $data['company_name'];
			}else{
				$data['error'] = 'Unable to locate record';
			}
			//echo $company_id;
		}else{
			$data['error'] = 'Unable to locate record';
			//echo 'Unable to locate record';
		}
		
		$query_notes = $this->company_m->fetch_notes($data['notes_id']);
		$temp_data = array_shift($query_notes->result_array());
		$data['comments'] = $temp_data['comments'];
		
		$query_email = $this->company_m->fetch_email($data['email_id']);
		$temp_data = array_shift($query_email->result_array());
		$data['general_email'] = $temp_data['general_email'];
		$data['direct'] = $temp_data['direct'];
		$data['accounts'] = $temp_data['accounts'];
		$data['maintenance'] = $temp_data['maintenance'];		
		
		$query_address= $this->company_m->fetch_complete_address($data['address_id']);
		$temp_data = array_shift($query_address->result_array());
		$data['postcode'] = $temp_data['postcode'];
		$data['suburb'] = $temp_data['suburb'];
		$data['po_box'] = $temp_data['po_box'];
		$data['street'] = ucwords(strtolower($temp_data['street']));
		$data['unit_level'] = ucwords(strtolower($temp_data['unit_level']));
		$data['unit_number'] = $temp_data['unit_number'];
		$data['state'] = $temp_data['name'];
		
		$p_query_address = $this->company_m->fetch_complete_address($data['postal_address_id']);
		$p_temp_data = array_shift($p_query_address->result_array());
		$data['p_po_box'] = $p_temp_data['po_box'];
		$data['p_unit_level'] = ucwords(strtolower($p_temp_data['unit_level']));
		$data['p_unit_number'] = $p_temp_data['unit_number'];
		$data['p_street'] = ucwords(strtolower($p_temp_data['street']));
		$data['p_suburb'] = $p_temp_data['suburb'];
		$data['p_state'] = $p_temp_data['name'];
		$data['p_postcode'] = $p_temp_data['postcode'];
		
		//echo $data['primary_contact_person_id'];
		//$data['primary_contact_person_id'] = $temp_data['primary_contact_person_id'];
		
		$temp_q_contact_person = $this->company_m->fetch_all_contact_persons($data['primary_contact_person_id']);
		$temp_q_contact_person = array_shift($temp_q_contact_person->result_array());
		$data['contact_person_f_name'] = $temp_q_contact_person['first_name'];
		$data['contact_person_l_name'] = $temp_q_contact_person['last_name'];		
		//var_dump( $temp_q_contact_person);
		
		

		$suburb_list = $this->company_m->fetch_all_suburb();
		$data['suburb_list'] = $suburb_list->result();

		$comp_type_list = $this->company_m->fetch_all_company_types();
		$all_company_list = $this->company_m->fetch_all_company(NULL);

		$company_detail_q = $this->company_m->display_company_detail_by_id($company_id);
		$temp_company_detail = array_shift($company_detail_q->result_array());
		
		//$query_phone= $this->company_m->fetch_phone($data['contact_number_id']);
		//$temp_data = array_shift($query_phone->result_array());
		
		//$query_email = $this->company_m->fetch_email($data['email_id']);
		//$temp_data = array_shift($query_email->result_array());
		
		$data['general_email'] = $temp_company_detail['general_email'];
		$data['direct'] = $temp_company_detail['direct'];
		$data['accounts'] = $temp_company_detail['accounts'];
		$data['maintenance'] = $temp_company_detail['maintenance'];
		$data['company_id'] = $company_id;
		
		$data['area_code'] = $temp_company_detail['area_code'];
		$data['office_number'] = $temp_company_detail['office_number'];
		$data['direct_number'] = $temp_company_detail['direct_number'];
		$data['mobile_number'] = $temp_company_detail['mobile_number'];
		$data['contact_number_id'] = $temp_company_detail['contact_number_id'];
		$data['address_id'] = $temp_company_detail['address_id'];
		$data['postal_address_id'] = $temp_company_detail['postal_address_id'];
		
		$data['company_type'] = $temp_company_detail['company_type'];

		$temp_q_activity = $this->company_m->fetch_company_activity_name_by_type($temp_company_detail['company_type'],$temp_company_detail['activity_id']);
		$data['company_activity'] = $temp_q_activity;		
		
		//$data['hid_ids'] = $data['address_id'].'|'.$data['postal_address_id'].'|'.$data['company_type_id'].'|'.$temp_company_detail['activity_id'].'|'.$company_id.'|'.$temp_company_detail['parent_company_id'];

		$temp_parent_q = $this->company_m->fetch_all_company($temp_company_detail['parent_company_id']);
		$temp_company_parent = array_shift($temp_parent_q->result_array());
		$data['company_parent'] = $temp_company_parent['company_name'];
		//var_dump( array_shift($temp_parent_comp->result_array()) );

		if($all_company_list->num_rows > 0){
			$data['all_company_list'] = $all_company_list->result();
		}else{
			$data['all_company_list'] = '';
		}

		$data['comp_type_list'] = $comp_type_list->result();
		$data['main_content'] = 'company_view';
		
		$this->form_validation->set_rules('company_name', 'Company Name','trim|required|xss_clean');
		$this->form_validation->set_rules('unit_level', 'Unit/Level', 'trim|xss_clean');
		$this->form_validation->set_rules('unit_number', 'Number', 'trim|xss_clean');
		$this->form_validation->set_rules('street', 'Street', 'trim|required|xss_clean');
		$this->form_validation->set_rules('suburb_a', 'Suburb', 'trim|required|xss_clean');
		$this->form_validation->set_rules('state_a', 'State', 'trim|required|xss_clean');
		$this->form_validation->set_rules('postcode_a', 'Postcode', 'trim|required|xss_clean');
		$this->form_validation->set_rules('pobox', 'PO Box', 'trim|xss_clean');
		$this->form_validation->set_rules('unit_level_b', 'Unit/Level', 'trim|xss_clean');
		$this->form_validation->set_rules('number_b', 'Number', 'trim|xss_clean');
		$this->form_validation->set_rules('street_b', 'Street', 'trim|required|xss_clean');
		$this->form_validation->set_rules('suburb_b', 'Suburb', 'trim|required|xss_clean');
		$this->form_validation->set_rules('state_b', 'State', 'trim|required|xss_clean');
		$this->form_validation->set_rules('postcode_b', 'Postcode', 'trim|required|xss_clean');
		$this->form_validation->set_rules('abn', 'ABN', 'trim|required|xss_clean');
		$this->form_validation->set_rules('acn', 'ACN', 'trim|required|xss_clean');
		$this->form_validation->set_rules('staxnum', 'Stax', 'trim|required|xss_clean');
		$this->form_validation->set_rules('activity', 'Activity', 'trim|required|xss_clean');
		$this->form_validation->set_rules('parent', 'Parent', 'trim|xss_clean');
		$this->form_validation->set_rules('officeNumber', 'Office Number', 'trim|required|xss_clean');
		$this->form_validation->set_rules('directNumber', 'Direct Number', 'trim|xss_clean');
		$this->form_validation->set_rules('mobileNumber', 'Mobile Number', 'trim|xss_clean');
		$this->form_validation->set_rules('generalEmail', 'General Email', 'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('directEmail', 'Direct Email', 'trim|valid_email|xss_clean');
		$this->form_validation->set_rules('accountsEmail', 'Accounts Email', 'trim|valid_email|xss_clean');
		$this->form_validation->set_rules('maintenanceEmail', 'Maintenance Email', 'trim|valid_email|xss_clean');
		$this->form_validation->set_rules('comments', 'Comments', 'trim|xss_clean');
		$this->form_validation->set_rules('type', 'Company Type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('areacode', 'Phone Areacode', 'trim|required|xss_clean');
		$this->form_validation->set_rules('contactperson', 'Contact Person', 'trim|required|xss_clean');
		
		//echo validation_errors();
		if($this->form_validation->run() === false){
			$data['error' ] = validation_errors();
			
			$suburb_list = $this->company_m->fetch_all_suburb();
			$comp_type_list = $this->company_m->fetch_all_company_types();
			$all_company_list = $this->company_m->fetch_all_company(NULL);
			
			if($all_company_list->num_rows > 0){
				$data['all_company_list'] = $all_company_list->result();
			}else{
				$data['all_company_list'] = '';
			}
			
			$data['suburb_list'] = $suburb_list->result();
			$data['comp_type_list'] = $comp_type_list->result();
			$this->load->view('page', $data);
			//valid_input_simple
		}else{
			$data['company_name'] = $this->cap_first_word($this->if_set($this->input->post('company_name', true)));
			$data['unit_level'] = $this->if_set($this->input->post('unit_level', true));
			$data['unit_number'] = $this->if_set($this->input->post('unit_number', true));
			$data['street'] = $this->cap_first_word($this->if_set($this->input->post('street', true)));
			$data['state_a'] = $this->cap_first_word($this->if_set($this->input->post('state_a', true)));
			$data['postcode_a'] = $this->if_set($this->input->post('postcode_a', true));
			$data['pobox'] = $this->if_set($this->input->post('pobox', true));
			$data['unit_level_b'] = $this->if_set($this->input->post('unit_level_b', true));
			$data['number_b'] = $this->if_set($this->input->post('number_b', true));
			$data['street_b'] = $this->cap_first_word($this->if_set($this->input->post('street_b', true)));

			$suburb_a_ar = explode('|',$this->if_set($this->input->post('suburb_a', true)));
			$data['suburb_a'] = strtoupper($suburb_a_ar[0]);

			$suburb_b_ar = explode('|',$this->if_set($this->input->post('suburb_b', true)));
			$data['suburb_b'] = strtoupper($suburb_b_ar[0]);

			$data['state_b'] = $this->cap_first_word($this->if_set($this->input->post('state_b', true)));
			$data['postcode_b'] = $this->if_set($this->input->post('postcode_b', true));
			$data['abn'] = $this->if_set($this->input->post('abn', true));
			$data['acn'] = $this->if_set($this->input->post('acn', true));
			$data['staxnum'] = $this->if_set($this->input->post('staxnum', true));
			$data['activity'] = $this->cap_first_word($this->if_set($this->input->post('activity', true)));
			
			$data['parent'] = $this->if_set($this->input->post('parent', true));			
			
			$data['areacode'] = $this->if_set($this->input->post('areacode', true));
			$data['officeNumber'] = $this->if_set($this->input->post('officeNumber', true));
			$data['directNumber'] = $this->if_set($this->input->post('directNumber', true));
			$data['mobileNumber'] = $this->if_set($this->input->post('mobileNumber', true));
			$data['generalEmail'] = $this->if_set($this->input->post('generalEmail', true));
			$data['directEmail'] = $this->if_set($this->input->post('directEmail', true));
			$data['accountsEmail'] = $this->if_set($this->input->post('accountsEmail', true));
			$data['maintenanceEmail'] = $this->if_set($this->input->post('maintenanceEmail', true));
			$data['comments'] = $this->cap_first_word_sentence($this->if_set($this->input->post('comments', true)));
			$data['type'] = $this->if_set($this->input->post('type', true));
			$data['contactperson'] = $this->if_set($this->input->post('contactperson', true)); 
			//var_dump($data);
			
			//echo '<script>alert("'.$data['contactperson'].'");</script>';
			
			$this->company_m->update_company($data);
			$update_success = 'The record is now updated.';
			//$this->session->set_flashdata('update_company_id', $company_id);
			$this->session->set_flashdata('update_message', $update_success);
			
			redirect(current_url());
		}		
	}
	
	public function add_contact(){
		//var_dump($_POST);
		
		$data['contact_first_name'] = $this->input->post('first_name');
		$data['contact_last_name'] = $this->input->post('last_name');
		$data['contact_gender'] = $this->input->post('gender');
		$data['contact_email'] = $this->input->post('email');
		$data['contact_contact_number'] = $this->input->post('contact_number');
		$data['contact_company'] = $this->input->post('company');
		//echo $data['contact_email'];
		$this->company_m->insert_new_contact_person($data);
	}

	public function cap_first_word($str){
		return ucwords(strtolower($str));
	}
	
	public function cap_first_word_sentence($str){
		//first we make everything lowercase, and 
		//then make the first letter if the entire string capitalized
		$str = ucfirst(strtolower($str));		
		//now capitalize every letter after a . ? and ! followed by space
		$str = preg_replace_callback('/[.!?] .*?\w/', create_function('$matches', 'return strtoupper($matches[0]);'), $str);		
		//print the result
		return $str;
	}

	public function if_set($val){
		//echo $val.'<br />';
		if(isset($val)){
			return ascii_to_entities($val);
		}else{
			return NULL;
		}
	}

	public function suburb_list($wrap=''){
		$suburb_list = $this->company_m->fetch_all_suburb();
		
		if($wrap == 'dropdown'){
			foreach ($suburb_list->result() as $row){
				echo '<option value="'.$row->suburb.'|'.$row->name.'|'.$row->phone_area_code.'">'.ucwords(strtolower($row->suburb)).'</option>';
			   //echo $row->general_address_id;   //echo $row->suburb;   //echo $row->postcode;   //echo $row->state_id;
			}
		}else if($wrap == 'list'){
			foreach ($suburb_list->result() as $row){
				echo '<li>'.$row->suburb.'</li>';
			}
		}else{
			foreach ($suburb_list->result() as $row){
				echo '<div>'.$row->suburb.'</div>';
			}		
		}
	}
	
	public function contact_person_list(){
		$contact_person_list = $this->company_m->fetch_all_contact_persons('');
		
		foreach ($contact_person_list->result() as $row){
			echo '<option value="'.$row->first_name.'|'.$row->last_name.'|'.$row->contact_person_id.'">'.$row->first_name.' '.$row->last_name.'</option>';
		}		
	}
	
	public function company_list($wrap=''){
		$comp_list = $this->company_m->fetch_all_company_type_id('1');
		
		if($wrap == 'dropdown'){
			foreach ($comp_list->result() as $row){
				echo '<option value="'.$row->company_name.'|'.$row->company_id.'">'.ucwords(strtolower($row->company_name)).'</option>';
			   //echo $row->general_address_id;   //echo $row->suburb;   //echo $row->postcode;   //echo $row->state_id;
			}
		}else if($wrap == 'list'){
			foreach ($comp_list->result() as $row){
				echo '<li>'.$row->company_name.'</li>';
			}
		}else{
			foreach ($comp_list->result() as $row){
				echo '<div>'.$row->company_name.'</div>';
			}		
		}
	}
	
	public function state_list($wrap=''){
		$states_list = $this->company_m->fetch_all_states();
		
		if($wrap == 'dropdown'){
			foreach ($states_list->result() as $row){
				echo '<option value="'.$row->id.'">'.ucwords(strtolower($row->name)).'</option>';
			   //echo $row->general_address_id;   //echo $row->suburb;   //echo $row->postcode;   //echo $row->state_id;
			}
		}else if($wrap == 'list'){
			foreach ($states_list->result() as $row){
				echo '<li>'.$row->name.'</li>';
			}
		}else{
			foreach ($states_list->result() as $row){
				echo '<div>'.$row->name.'</div>';
			}		
		}
	}
	
	public function get_list_view(){
		$suburb_value =  $this->input->post('suburb');
		$state_value =  $this->input->post('state');
		$phonecode_value =  $this->input->post('phonecode');
		$post_code_list = $this->company_m->fetch_postcode($suburb_value);
		$counter=0;
		if($post_code_list->num_rows()>1){
			$post_code_items = '<option value="">Choose a Postcode...</option>';
		}
		foreach ($post_code_list->result() as $row){
			$post_code_items .= '<option value="'.$row->postcode.'">'.ucwords(strtolower($row->postcode)).'</option>';
			$counter++;
		}
		
		
		echo $post_code_items.'|'.$state_value.'|'.$phonecode_value;
	}
	
	public function set_suburb($value){		
		$suburb = explode('|',$value);
		return ucwords(strtolower($suburb[0]));
	}
	
	public function display_company_by_type($type){
		$data['com_c'] = $this->company_m->display_company_by_type($type);
		$this->load->view('tables_client',$data);
	}
	
	public function donut_cart_companies(){
		$data['com_q'] = $this->company_m->count_company_by_type();
		$this->load->view('chart',$data);
	}
	
	public function activity(){
		$suburb_value = $this->security->xss_clean($this->input->post('ajax_var'));
		if($suburb_value=='Client'){
			$query = $this->company_m->fetch_all_client_types();
			//var_dump($query);	
			//$all_client_list = '';	
			//$counter = 0;
			echo '<option value="">Choose a Activity...</option>';
			echo '<option value="Add Activity">Add Activity</option>';
			foreach ($query->result() as $row){
				echo '<option value="'.ucwords(strtolower($row->client_category_name)).'">'.ucwords(strtolower($row->client_category_name)).'</option>';				
			}
		}else if($suburb_value=='Contractor'){
			$query = $this->company_m->fetch_all_contractor_types();
			echo '<option value="">Choose a Activity...</option>';
			echo '<option value="Add Activity">Add Activity</option>';
			foreach ($query->result() as $row){
				echo '<option value="'.ucwords(strtolower($row->job_category)).'">'.ucwords(strtolower($row->job_category)).'</option>';				
			}
		}else if($suburb_value=='Supplier'){
			$query = $this->company_m->fetch_all_supplier_types();
			echo '<option value="">Choose a Activity...</option>';
			echo '<option value="Add Activity">Add Activity</option>';
			foreach ($query->result() as $row){
				echo '<option value="'.ucwords(strtolower($row->supplier_cat_name)).'">'.ucwords(strtolower($row->supplier_cat_name)).'</option>';				
			}
		}else{
			echo '';
		}
	}

	public function default_notes(){
		$cqr_notes_w_ins = $this->input->post('cqr_notes_w_ins', true);
		$cqr_notes_no_ins = $this->input->post('cqr_notes_no_ins', true);
		$cpo_notes_w_ins = $this->input->post('cpo_notes_w_ins', true);
		$cpo_notes_no_ins = $this->input->post('cpo_notes_no_ins', true);

		$var = $this->admin_m->update_defaults_notes($cqr_notes_w_ins,$cqr_notes_no_ins,$cpo_notes_w_ins,$cpo_notes_no_ins);

		redirect('/admin');

	}

	public function default_email_message(){
		$sender_name_no_insurance = $this->input->post('sender_name_no_insurance', true);
		$sender_email_no_insurnace = $this->input->post('sender_email_no_insurnace', true);
		$subject_no_insurnace = $this->input->post('subject_no_insurnace', true);
		$email_msg_no_insurance = $this->input->post('email_msg_no_insurance', true);
		$bcc_email_no_insurnace = $this->input->post('bcc_email_no_insurnace', true);
		$user_id = $this->input->post('user_assigned_forinsurance', true);

		$this->admin_m->update_admin_default_email_message($sender_name_no_insurance,$sender_email_no_insurnace,$subject_no_insurnace,$email_msg_no_insurance,$bcc_email_no_insurnace,$user_id);

		redirect('/admin');

	}
}