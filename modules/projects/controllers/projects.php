<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Projects extends MY_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->module('users');
		$this->load->model('projects_m');
		$this->load->module('company');
		$this->load->module('works');
		$this->load->model('works_m');
		$this->load->module('admin');
		$this->load->model('admin_m');
		$this->load->module('invoice');
		$this->load->module('wip');
		$this->load->model('wip_m');
		if(!$this->users->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;
	}
	

	public function index(){

		$data['proj_t'] = $this->wip_m->display_all_wip_projects();
		$data['users'] = $this->user_model->fetch_user();
		$this->users->_check_user_access('projects',1);
		$data['main_content'] = 'projects_v';
		$data['screen'] = 'Projects';
		$this->load->view('page', $data);
	}
	
	public function display_work_attachments(){
		$this->works->display_work_attachments();
	}

	public function works_view(){
	  	$this->works->works_view();
	}

	public function show_project_invoice(){
	  	$this->invoice->project_invoice();		
	}
	
	public function variations_view(){
	  	$this->works->variations_view();
	}
	public function work_details(){
		$this->works->work_details();
	}
	
	public function display_all_works_query($prjc_id){
	  	$this->works->display_all_works_query($prjc_id);
	}
	
	public function display_all_variations_query($prjc_id){
	  	$this->works->display_all_variations_query($prjc_id);
	}

	public function display_all_projects(){
		$data['proj_t'] = $this->projects_m->display_all_projects();
		$this->load->view('tables_projects',$data);
	}

	public function removeWork($id){
		$removeId = $_POST['postVal'];
		$this->projects_m->removeWork($removeId);
	}

	public function display_all_works($project_id){
		$project_work_list = $this->projects_m->display_all_works($project_id);

		if($project_work_list->num_rows > 0){
			foreach ($project_work_list->result() as $row){
				$job_catId = $row->job_sub_cat_id;

				if($row->contractor_type == 'Sub-Contractor'){
					$sub_con = $this->projects_m->select_particular_sub_contractor($job_catId);
					$con_arr = array_shift($sub_con->result_array());
					$conType = $con_arr['job_sub_cat'];


				}elseif($row->contractor_type == 'Contractor'){
					$cont = $this->projects_m->select_particular_contractor($job_catId);
					$con_arr = array_shift($cont->result_array());
					$conType = $con_arr['job_category'];

				}else{
					$suppl = $this->projects_m->select_particular_supplier($job_catId);
					$con_arr = array_shift($suppl->result_array());
					$conType = $con_arr['supplier_cat_name'];
				}


				if($row->contractor_type == 'Sub-Contractor' || $row->contractor_type == 'Contractor'){
					$work_type_id = 2;
				}else{
					$work_type_id = 3;
				}


				echo'<tr ><td ><a href="#">'.$conType.'</a></td><td class=""><span class="set-company" id="'.$work_type_id.'" data-toggle="collapse" data-target=".select-company">';
				if($row->company_id==0){echo'Please select';}else{echo'Selected';}
				echo'</span></td><td class="">'.$row->cpo_number.'</td><td></td><td class="">'.$row->work_estimate.'</td><td class="">xx</td><td><div class="block btn btn-xs btn-danger remove-job" id="'.$row->works_id.'"> <strong>x</strong> </div></td></tr>';
			}
		}else{
			echo'<tr ><td colspan="7" align="center">No Works Yet</td></tr>';
			
		}		

	}



	public function fetch_shopping_center_state_sub($suburb='',$state='',$direct=0){

		if($direct == 0){
	      	$data = explode('|',$_POST['ajax_var']);
	      	$suburb = $data['0'];
	      	$state = $data['6'];
		}else{
	      	$suburb_arr = explode('|',$suburb);
	      	$suburb = $suburb_arr['0'];

	      	$state_arr = explode('|',$state);
	      	$state = $state_arr['3'];
		}

      	$suburb = strtoupper($suburb);
		$shopping_center_list = $this->projects_m->get_list_shopping_centers($state,$suburb);

		echo '<option value="" disabled="disabled" selected="selected">Choose a Shopping Center</option>';

      	foreach ($shopping_center_list->result() as $row){
      		echo '<option value="'.$row->detail_address_id.'">'.ucwords(strtolower($row->shopping_center_brand_name)).'</option>';
      	}
	}



	public function hasQuote($project_id){
		$project_work_list = $this->projects_m->display_all_works($project_id);
		if($project_work_list->num_rows > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	public function list_invoiced_items($project_id,$project_total,$variation_total){
		$this->invoice->list_invoiced_items($project_id,$project_total,$variation_total);
	}
	
	public function view(){
		$project_id = $this->uri->segment(3);

		$q_proj = $this->projects_m->fetch_complete_project_details($project_id);
		if($q_proj->num_rows > 0){
			$data = array_shift($q_proj->result_array());

			$admin_defaults_raw = $this->admin_m->latest_system_default($data['defaults_id']);
			$admin_defaults = array_shift($admin_defaults_raw->result_array());

			$markup_raw = $this->admin_m->fetch_markup($admin_defaults['markup_id']);
			$markup_defaults = array_shift($markup_raw->result_array());

			$q_project_notes = $this->projects_m->fetch_project_notes($data['notes_id']);
			$project_notes = array_shift($q_project_notes->result_array());
			$data['project_comments'] = $project_notes['comments'];

			$q_focus_company = $this->company_m->display_company_detail_by_id($data['focus_company_id']);
			$focus_company = array_shift($q_focus_company->result_array());
			$data['focus_company_id'] = $focus_company['company_id'];
			$data['focus_company_name'] = $focus_company['company_name'];

			$q_client_company = $this->company_m->display_company_detail_by_id($data['client_id']);
			$client_company = array_shift($q_client_company->result_array());
			$data['client_company_id'] = $client_company['company_id'];
			$data['client_company_name'] = $client_company['company_name'];

			$q_fetch_contact_details_primary = $this->company_m->fetch_contact_details_primary($client_company['company_id']);
			$company_contact_details_primary_detail = array_shift($q_fetch_contact_details_primary->result_array());

			$data['company_contact_details_area_code'] = $company_contact_details_primary_detail['area_code'];
			$data['company_contact_details_office_number'] = $company_contact_details_primary_detail['office_number'];
			$data['company_contact_details_direct_number'] = $company_contact_details_primary_detail['direct_number'];
			$data['company_contact_details_mobile_number'] = $company_contact_details_primary_detail['mobile_number'];
			$data['company_contact_details_after_hours'] = $company_contact_details_primary_detail['after_hours'];
			$data['company_contact_details_general_email'] = $company_contact_details_primary_detail['general_email'];
			$data['company_contact_details_direct'] = $company_contact_details_primary_detail['direct'];
			$data['company_contact_details_accounts'] = $company_contact_details_primary_detail['accounts'];
			$data['company_contact_details_maintenance'] = $company_contact_details_primary_detail['maintenance'];

/*

contact_person_company_id
company_id
contact_person_id
type
is_primary
is_active
contact_person_id
first_name
last_name
gender
email_id
contact_number_id
contact_number_id
area_code

office_number
direct_number
mobile_number
after_hours
email_id
general_email
direct
accounts
maintenance



*/



			$query_client_address = $this->company_m->fetch_complete_detail_address($client_company['address_id']);
			$temp_data = array_shift($query_client_address->result_array());
			$data['query_client_address_postcode'] = $temp_data['postcode'];
			$data['query_client_address_suburb'] = ucwords(strtolower($temp_data['suburb']));
			$data['query_client_address_po_box'] = $temp_data['po_box'];
			$data['query_client_address_street'] = ucwords(strtolower($temp_data['street']));
			$data['query_client_address_unit_level'] = ucwords(strtolower($temp_data['unit_level']));
			$data['query_client_address_unit_number'] = $temp_data['unit_number'];
			$data['query_client_address_state'] = $temp_data['name'];

			/*

			fetch_contact_person_company($company_id) 

["company_id"]=> string(3) "523" 
["company_name"]=> string(25) "Retail Food Group Limited" 
["abn"]=> string(14) "13 106 840 082" 
["acn"]=> string(11) "106 840 082" 
["bank_account_id"]=> string(1) "0" 
["activity_id"]=> string(1) "6" 
["notes_id"]=> string(1) "0"  
["postal_address_id"]=> string(4) "1285" 
["company_type_id"]=> string(1) "1" 
["parent_company_id"]=> string(1) "0" 
["active"]=> string(1) "1" }


address
["address_id"]=> string(4) "1284"
phone
email
			*/

			$q_contact_person = $this->company_m->fetch_all_contact_persons($data['primary_contact_person_id']);
			$contact_person = array_shift($q_contact_person->result_array());
			$data['contact_person_id'] = $contact_person['contact_person_id'];
			$data['contact_person_fname'] = $contact_person['first_name'];
			$data['contact_person_lname'] = $contact_person['last_name'];

//  ["email_id"]=> string(3) "537" ["contact_number_id"]=> string(3) "556" }

			$q_fetch_phone = $this->company_m->fetch_phone($contact_person['contact_number_id']);
			$contact_person_phone = array_shift($q_fetch_phone->result_array());

			

			if($contact_person_phone['office_number'] != ''): 
				$data['contact_person_phone_office'] = $contact_person_phone['area_code'].' '.$contact_person_phone['office_number'];
			else: $data['contact_person_phone_office'] = '';
			endif;


			if($contact_person_phone['direct_number'] != ''): 
				$data['contact_person_phone_direct'] = $contact_person_phone['area_code'].' '.$contact_person_phone['direct_number'];
			else: $data['contact_person_phone_direct'] = '';
			endif;

			if($contact_person_phone['mobile_number'] != ''):
				$data['contact_person_phone_mobile'] = $contact_person_phone['mobile_number'];
			else: $data['contact_person_phone_mobile'] = '';
			endif;

			if($contact_person_phone['after_hours'] != ''):
				$data['contact_person_phone_afterhours'] = $contact_person_phone['area_code'].' '.$contact_person_phone['after_hours'];
			else: $data['contact_person_phone_afterhours'] = '';
			endif;

			$query_address= $this->company_m->fetch_complete_detail_address($data['address_id']);
			$temp_data = array_shift($query_address->result_array());
			$data['postcode'] = $temp_data['postcode'];
			$data['suburb'] = ucwords(strtolower($temp_data['suburb']));
			$data['po_box'] = $temp_data['po_box'];
			$data['street'] = ucwords(strtolower($temp_data['street']));
			$data['unit_level'] = ucwords(strtolower($temp_data['unit_level']));
			$data['unit_number'] = $temp_data['unit_number'];
			$data['state'] = $temp_data['name'];
		// $data['address_id'] = $data['address_id'];

			$data['shortname'] = $temp_data['shortname'];
			$data['state_id'] =  $temp_data['state_id'];
			$data['phone_area_code'] = $temp_data['phone_area_code'];	

			$p_query_address = $this->company_m->fetch_complete_detail_address($data['invoice_address_id']);
			$p_temp_data = array_shift($p_query_address->result_array());
			$data['i_po_box'] = $p_temp_data['po_box'];
			$data['i_unit_level'] = ucwords(strtolower($p_temp_data['unit_level']));
			$data['i_unit_number'] = $p_temp_data['unit_number'];
			$data['i_street'] = ucwords(strtolower($p_temp_data['street']));
			$data['i_suburb'] = ucwords(strtolower($p_temp_data['suburb']));
			$data['i_state'] = $p_temp_data['name'];
			$data['i_postcode'] = $p_temp_data['postcode'];
//		$data['postal_address_id'] = $company_detail['postal_address_id'];

			$data['i_shortname'] = $p_temp_data['shortname'];
			$data['i_state_id'] =  $p_temp_data['state_id'];
			$data['i_phone_area_code'] = $p_temp_data['phone_area_code'];

			$q_project_manager = $this->user_model->fetch_user($data['project_manager_id']);
			$project_manager = array_shift($q_project_manager->result_array());
			$data['pm_user_id'] = $project_manager['user_id'];
			$data['pm_user_first_name'] = $project_manager['user_first_name'];
			$data['pm_user_last_name'] = $project_manager['user_last_name'];

			$q_project_admin = $this->user_model->fetch_user($data['project_admin_id']);
			$project_admin = array_shift($q_project_admin->result_array());
			$data['pa_user_id'] = $project_admin['user_id'];
			$data['pa_user_first_name'] = $project_admin['user_first_name'];
			$data['pa_user_last_name'] = $project_admin['user_last_name'];

			$q_project_estiamator_id = $this->user_model->fetch_user($data['project_estiamator_id']);
			$project_estiamator = array_shift($q_project_estiamator_id->result_array());
			$data['pe_user_id'] = $project_estiamator['user_id'];
			$data['pe_user_first_name'] = $project_estiamator['user_first_name'];
			$data['pe_user_last_name'] = $project_estiamator['user_last_name'];

			$pg_markup_raw = $this->projects->fetch_mark_up_by($data['job_category'] , $markup_defaults['markup_id']);
			$pg_markup = explode('|',$pg_markup_raw);
			$data['min_markup'] = $pg_markup[1];

			$shopping_center_q = $this->projects_m->select_shopping_center($data['address_id']);
			$shopping_center = array_shift($shopping_center_q->result_array());

			$data['shopping_center_id'] = $shopping_center['shopping_center_id'];
			$data['shopping_center_brand_name'] = $shopping_center['shopping_center_brand_name'];


			$applied_admin_settings_raw = $this->display_project_applied_defaults($project_id);

			$project_totals_arr = $this->fetch_project_totals($project_id);

			$data = array_merge($data, $project_totals_arr);

			$data = array_merge($data, $applied_admin_settings_raw);


			$data['main_content'] = 'projects_view';
			$data['screen'] = 'Project Details';

			$this->load->view('page', $data);
		}else{
			redirect('/projects');
		}
	}

	public function fetch_project_total_values(){
		$project_id = $_POST['proj_id'];
		$project_totals_arr = $this->fetch_project_totals($project_id);
		$proj_default_arr = $this->display_project_applied_defaults($project_id);
		$admin_gst_rate = $proj_default_arr['admin_gst_rate'];
		$proj_ex_gst = round($project_totals_arr['final_total_quoted'],2);
		$proj_ex_gst_formatted = number_format($project_totals_arr['final_total_quoted'],2);
		$proj_inc_gst = number_format($proj_ex_gst+($proj_ex_gst*($admin_gst_rate/100)),2);
		//echo $project_id;
		echo $proj_ex_gst_formatted."|".$proj_inc_gst;
	}

	public function find_contact_person($comp_id=''){
		$post_ajax_arr = array();

		if(isset($_POST['ajax_var'])){
			$post_ajax_arr = explode('|',$_POST['ajax_var']);
		}elseif($comp_id!=''){
			$post_ajax_arr[1] = $comp_id;
		}else{
			return false;
		}		

		$contact_persons_q = $this->projects_m->fectch_contact_person_by_company_id($post_ajax_arr['1']);

		foreach ($contact_persons_q->result() as $row) {
			echo '<option value="'.$row->contact_person_id.'" '.($row->is_primary == 1 ? 'selected="selected"' : '').'  >'.$row->first_name.' '.$row->last_name.'</option>';
		}
	}

	public function fetch_address_company_invoice(){
		//$post_ajax_id = $_POST['ajax_var_i'];
		$post_ajax_arr = explode('|',$_POST['ajax_var']);

		$query_company_details = $this->company_m->fetch_all_company($post_ajax_arr[1]);
		$temp_company_details = array_shift($query_company_details->result_array());

		$query_complete_detail_address = $this->company_m->fetch_complete_detail_address($temp_company_details['address_id']);
		$temp_complete_detail_address = array_shift($query_complete_detail_address->result_array());


		echo ucwords(strtolower($temp_complete_detail_address['unit_level'])).'|';
		echo $temp_complete_detail_address['unit_number'].'|';
		echo ucwords(strtolower($temp_complete_detail_address['street'])).'|';
		echo $temp_complete_detail_address['po_box'].'|';

		echo $temp_complete_detail_address['shortname'].'|';
		echo $temp_complete_detail_address['name'].'|';
		echo $temp_complete_detail_address['phone_area_code'].'|';
		echo $temp_complete_detail_address['id'].'|';

		echo ucfirst(strtolower($temp_complete_detail_address['suburb'])).'|';
		echo $temp_complete_detail_address['postcode'];

	}

	public function set_jurisdiction($focus_id=''){
		$all_aud_states = $this->company_m->fetch_all_states();
		$data['all_aud_states'] = $all_aud_states->result();
		echo "<option value=''>Choose a State</option>";

		if(isset($_POST['ajax_var']) ){
			$post_ajax_arr = explode('|',$_POST['ajax_var']);

			$focus_id = $post_ajax_arr[0];
			$admin_company_details = $this->admin_m->fetch_single_company_focus($focus_id);
			$focus_detail_data = array_shift($admin_company_details->result_array() );

			$jurisdiction = explode(',', $focus_detail_data['admin_jurisdiction_state_ids']);


			foreach ($jurisdiction  as $jur_key => $jur_value): 
				foreach ($data['all_aud_states']  as $key => $value):
					if( $jur_value == $value->id ){ echo '<option value="'.$value->shortname.'|'.$value->name.'|'.$value->phone_area_code.'|'.$value->id.'">'.$value->name.'</option>';	}
				endforeach;
			endforeach;

		}elseif ($focus_id!='') {

			$focus_id = $focus_id;
			$admin_company_details = $this->admin_m->fetch_single_company_focus($focus_id);
			$focus_detail_data = array_shift($admin_company_details->result_array() );

			$jurisdiction = explode(',', $focus_detail_data['admin_jurisdiction_state_ids']);


			foreach ($jurisdiction  as $jur_key => $jur_value): 
				foreach ($data['all_aud_states']  as $key => $value):
					if( $jur_value == $value->id ){ echo '<option value="'.$value->shortname.'|'.$value->name.'|'.$value->phone_area_code.'|'.$value->id.'">'.$value->name.'</option>';	}
				endforeach;
			endforeach;
		}else{
			foreach ($data['all_aud_states']  as $key => $value):
				echo '<option value="'.$value->shortname.'|'.$value->name.'|'.$value->phone_area_code.'|'.$value->id.'">'.$value->name.'</option>';
			endforeach;
		}
	}

	public function set_jurisdiction_shoping_center($focus_id_set=''){
		$shopping_center_arr = array();

		if($focus_id_set==''){
			$focus_id = $_POST['ajax_var'];
		}else{
			$focus_id = $focus_id_set;
		}

		$focus_raw = $this->admin_m->fetch_single_company_focus($focus_id);
		$focus = array_shift($focus_raw->result_array() );

		$focus_jurisdiction =  explode(',', $focus['admin_jurisdiction_state_ids']);

		foreach ($focus_jurisdiction  as $jur_key => $state):
			$shopping_center = $this->projects_m->fetch_shopping_center_by_state($state);
			foreach ($shopping_center->result_array() as $row):
				if(!in_array($row['shopping_center_brand_name'], $shopping_center_arr)){
					array_push($shopping_center_arr,$row['shopping_center_brand_name']);
				}				
			endforeach;
		endforeach;

		foreach ($shopping_center_arr as $row => $value){
			echo '<option value="'.$value.'">'.$value.'</option>';
		}
	}


	public function set_jurisdiction_shoping_center_by_name_and_sate(){
		$shopping_center_arr = array();

		$focusdata_arr = explode('|', $_POST['ajax_var']);
		$focus_id = $focusdata_arr[0];
		$shopping_center_brand = $focusdata_arr[1];


		$focus_raw = $this->admin_m->fetch_single_company_focus($focus_id);
		$focus = array_shift($focus_raw->result_array() );

		$focus_jurisdiction =  explode(',', $focus['admin_jurisdiction_state_ids']);

		foreach ($focus_jurisdiction  as $jur_key => $state):
			$shopping_center = $this->projects_m->fetch_shopping_center_by_name_and_sate($shopping_center_brand,$state);
			foreach ($shopping_center->result_array() as $row):
				if(!in_array($row['suburb'], $shopping_center_arr)){
					$shopping_center_arr[$row['detail_address_id']] = $row['suburb'];
				}				
			endforeach;
		endforeach;

		foreach ($shopping_center_arr as $row => $value){
			echo '<option value="'.$row.'">'.$this->company->cap_first_word($value).'</option>';
		}
	}

	
	public function supplier_cat_list(){
		$suppier_cat_type = $this->projects_m->display_all_supplier_types('');		
		foreach ($suppier_cat_type->result() as $row){
			echo '<option value="sup|'.$row->supplier_cat_name.'|'.$row->supplier_cat_id.'">'.$row->supplier_cat_name.'</option>';
		}		
	}
	
	public function job_cat_list(){
		$jobs_cat_type = $this->projects_m->display_all_job_category_type('');		
		foreach ($jobs_cat_type->result() as $row){
			echo '<option value="job|'.$row->job_category.'|'.$row->job_category_id.'">'.$row->job_category.'</option>';
		}		
	}
	
	public function sub_job_cat_list(){
		$job_cat_id = $_POST['postVal'];
		$jobs_cat_type = $this->projects_m->display_all_job_sub_category_type($job_cat_id);

		if( $jobs_cat_type->num_rows()>0){
			foreach ($jobs_cat_type->result() as $row){
				echo '<option value="sub|'.$row->job_sub_cat.'|'.$row->job_sub_cat_id.'">'.$row->job_sub_cat.'</option>';
			}			
		}else{
			echo '<option value="">Work Description</option>';
		}
	}

	public function fetch_mark_up_by($job_cat='',$markup_id=''){

		if($job_cat == ''){
			$job_cat = $_POST['job_cat'];

			$defaults_raw = $this->admin_m->latest_system_default();
			$defaults = $defaults_raw->result();
			$markup_id = $defaults[0]->markup_id;

			$mark_up_q = $this->projects_m->fetch_mark_up_by_type($job_cat,$markup_id);
			$mark_up = array_shift($mark_up_q->result_array());
			echo implode("|",$mark_up);
		}else{
			$type = $job_cat;
			$mark_up_q = $this->projects_m->fetch_mark_up_by_type($type,$markup_id);
			$mark_up = array_shift($mark_up_q->result_array());
			return implode("|",$mark_up);
		}

		
	}

	public function clear_apost(){
		foreach ($_POST as $key => $value) {
			$_POST[$key] = str_replace("'","&apos;",$value);
		}
	}
	
	public function add(){
		//var_dump($_POST);

		$this->users->_check_user_access('projects',2);

		$defaults_raw = $this->admin_m->latest_system_default();
		$defaults = $defaults_raw->result();
		$defaults_id = $defaults[0]->defaults_id;

		$all_company_list = $this->company_m->fetch_all_company(NULL);
		if($all_company_list->num_rows > 0){
			$data['all_company_list'] = $all_company_list->result();
		}

		$data['main_content'] = 'projects_add';


		$all_aud_states = $this->company_m->fetch_all_states();
		$data['all_aud_states'] = $all_aud_states->result();

		$focus = $this->admin_m->fetch_all_company_focus();
		$data['focus'] = $focus->result();

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$data['project_manager'] = $project_manager->result();

		$maintenance_administrator = $this->user_model->fetch_user_by_role(7);
		$data['maintenance_administrator'] = $maintenance_administrator->result();

		$project_administrator = $this->user_model->fetch_user_by_role(2);
		$data['project_administrator'] = $project_administrator->result();


		$shopping_center = $this->projects_m->fetch_shopping_center();
		$data['shopping_center'] = $shopping_center->result();

		$estimator = $this->user_model->fetch_user_by_role(8);
		$data['estimator'] = $estimator->result();


		$data['all_projects'] = $this->projects_m->display_all_projects();

		$company_project_item = array();
		foreach ($data['all_projects']->result_array() as $row){
			$company_project_item[$row['company_id']] = $row['company_name'];
		}
		asort($company_project_item);
		$data['all_company_project'] = $company_project_item;




		$this->form_validation->set_rules('project_name', 'Project Name','trim|required|xss_clean|max_length[35]');
		$this->form_validation->set_rules('site_start', 'Site Start','trim|required|xss_clean');
		$this->form_validation->set_rules('site_finish', 'Site Finish','trim|required|xss_clean');
		$this->form_validation->set_rules('job_category', 'Job Category','trim|required|xss_clean');
		$this->form_validation->set_rules('project_date', 'Project Date','trim|required|xss_clean');


		if( $this->input->post('is_shopping_center') != 1){
			$this->form_validation->set_rules('street', 'Site Street','trim|required|xss_clean');
			$this->form_validation->set_rules('suburb_a', 'Site Project Address Suburb','trim|required|xss_clean');
			$this->form_validation->set_rules('state_a', 'Site State','trim|required|xss_clean');
			$this->form_validation->set_rules('postcode_a', 'Site Postcode','trim|required|xss_clean');
		}else{
			$this->form_validation->set_rules('shop_tenancy_number', 'Site Shop/Tenancy Number','trim|required|xss_clean');
			$this->form_validation->set_rules('brand_shopping_center', 'Site Brand/Shopping Center','trim|required|xss_clean');
		}		

		$this->form_validation->set_rules('street_b', 'Invoice Street','trim|required|xss_clean');
		$this->form_validation->set_rules('suburb_b', 'Invoice Address Suburb','trim|required|xss_clean');
		$this->form_validation->set_rules('state_b', 'Invoice State','trim|required|xss_clean');
		$this->form_validation->set_rules('postcode_b', 'Invoice Postcode','trim|required|xss_clean');



		$this->form_validation->set_rules('project_manager', 'Project Manager','trim|required|xss_clean');
		$this->form_validation->set_rules('project_admin', 'Project Admin','trim|required|xss_clean');
		$this->form_validation->set_rules('estimator', 'Estimator','trim|required|xss_clean');
		$this->form_validation->set_rules('company_prg', 'Company Client','trim|required|xss_clean');
		$this->form_validation->set_rules('contact_person', 'Contact Person','trim|required|xss_clean');
		$this->form_validation->set_rules('install_hrs', 'Site Hours','trim|xss_clean');
		$this->form_validation->set_rules('project_total', 'Project Estimate','trim|required|xss_clean');
		$this->form_validation->set_rules('labour_hrs_estimate', 'Site Labour Estimate','trim|required|xss_clean');
		$this->form_validation->set_rules('project_markup', 'Project Markup','trim|required|xss_clean');

		if( $this->input->post('job_category') == 'Maintenance' ||  $this->input->post('job_category') == 'Kiosk' || $this->input->post('job_category') == 'Minor Works' || $this->input->post('job_category') == 'Strip Out'  ){
			$this->form_validation->set_rules('project_area', 'Project Area','trim|xss_clean');
		}else{
			$this->form_validation->set_rules('project_area', 'Project Area','trim|required|xss_clean');
		}







		
		//echo $this->company->cap_first_word($this->company->if_set($this->input->post('project_name', true)));
		//var_dump($_POST);
		if($this->form_validation->run() === false){
			$this->clear_apost();
			$data['error' ] = validation_errors();
			$this->load->view('page', $data);
			//valid_input_simple
		}else{
			$this->clear_apost();

			$focus_id = $this->input->post('focus');
			$project_name = $this->input->post('project_name');
			$client_po = $this->input->post('client_po');
			$job_type = $this->input->post('job_type');
			$job_category = $this->input->post('job_category');
			$project_date = $this->input->post('project_date');
			$job_date = $this->input->post('job_date');
			$site_start = $this->input->post('site_start');
			$site_finish = $this->input->post('site_finish');

			if($job_date != ''){
				$is_wip = 1;
			}else{
				$is_wip = 0;
			}

			$data['unit_level'] = $this->company->if_set($this->input->post('unit_level', true));
			$data['unit_number'] = $this->company->if_set($this->input->post('unit_number', true));
			$data['street'] = $this->company->cap_first_word($this->company->if_set($this->input->post('street', true)));
			$data['postcode_a'] = $this->company->if_set($this->input->post('postcode_a', true));

			$data['pobox'] = $this->company->if_set($this->input->post('pobox', true));
			$data['unit_level_b'] = $this->company->if_set($this->input->post('unit_level_b', true));
			$data['number_b'] = $this->company->if_set($this->input->post('number_b', true));			
			$data['street_b'] = $this->company->cap_first_word($this->company->if_set($this->input->post('street_b', true)));
			$data['postcode_b'] = $this->company->if_set($this->input->post('postcode_b', true));		

			$state_a_arr = explode('|', $this->input->post('state_a', true));
			$data['state_a'] = $state_a_arr[3];

			$suburb_a_ar = explode('|',$this->company->if_set($this->input->post('suburb_a', true)));
			$data['suburb_a'] = strtoupper($suburb_a_ar[0]);

			$state_b_arr = explode('|', $this->input->post('state_b', true));
			$data['state_b'] = $state_b_arr[3];

			$suburb_b_ar = explode('|',$this->company->if_set($this->input->post('suburb_b', true)));
			$data['suburb_b'] = strtoupper($suburb_b_ar[0]);

			$project_manager_id = $this->input->post('project_manager');
			$project_admin_id = $this->input->post('project_admin');
			$project_estiamator_id = $this->input->post('estimator');
			$company_prg_arr =  explode('|',$this->input->post('company_prg'));
			$client_id = $company_prg_arr[1];
			
			$contact_person_id = $this->input->post('contact_person');
			$project_total = str_replace (',','', $this->input->post('project_total') );

			$install_hrs = $this->input->post('install_hrs');

			$project_markup = $this->input->post('project_markup');

			$project_area = $this->input->post('project_area');
			$comments = $this->input->post('comments');
			$project_status_id = 1;

			$copy_work_project_id = $this->input->post('copy_work_project_id');
			$include_work_estimate = $this->input->post('include_work_estimate');

			$shop_tenancy_number = $this->input->post('shop_tenancy_number'); 

			$focus_user_id = $this->session->userdata('user_id');


			$labour_hrs_estimate = $this->input->post('labour_hrs_estimate', true);

			$is_shopping_center = $this->input->post('is_shopping_center');

			$project_notes_id = $this->company_m->insert_notes($comments);

			$is_double_time = $this->input->post('is_double_time');




			if($is_shopping_center == 1){
				$site_address_id =  $this->input->post('brand_shopping_center');
			}else{

				$general_address_id_result_a = $this->company_m->fetch_address_general_by('postcode-suburb',$data['postcode_a'],$data['suburb_a']);
				foreach ($general_address_id_result_a->result() as $general_address_id_a){
					$general_address_a = $general_address_id_a->general_address_id;
				}
				$site_address_id = $this->company_m->insert_address_detail($data['street'],$general_address_a,$data['unit_level'],$data['unit_number']);
			}			

			$general_address_id_result_b = $this->company_m->fetch_address_general_by('postcode-suburb',$data['postcode_b'],$data['suburb_b']);
			foreach ($general_address_id_result_b->result() as $general_address_id_b){
				$general_address_b = $general_address_id_b->general_address_id;
			}

			$invoice_address_id = $this->company_m->insert_address_detail($data['street_b'],$general_address_b,$data['unit_level_b'],$data['number_b'],$data['pobox']);


			$inserted_project_id = $this->projects_m->insert_new_project($project_name, $project_date, $contact_person_id, $project_total, $job_date, $is_wip, $client_po, $site_start, $site_finish, $job_category, $job_type, $focus_user_id ,$focus_id, $project_manager_id, $project_admin_id, $project_estiamator_id,$site_address_id, $invoice_address_id, $project_notes_id, $project_markup,$project_status_id, $client_id, $install_hrs, $project_area, $is_double_time, $labour_hrs_estimate, $shop_tenancy_number,$defaults_id);
//================= For Maintenance Site sheet ===============
			$site_cont_person = $this->input->post('site_cont_person');
			$site_cont_number = $this->input->post('site_cont_number');
			$site_cont_mobile = $this->input->post('site_cont_mobile');
			$site_cont_email = $this->input->post('site_cont_email');

			$this->projects_m->insert_project_site_contact($inserted_project_id, $site_cont_person, $site_cont_number, $site_cont_mobile, $site_cont_email);

//================= For Maintenance Site sheet ===============


			if($install_hrs != '' && $install_hrs > 0){
				$prj_install_hrs = $install_hrs;
			}else{
				$prj_install_hrs = $labour_hrs_estimate;
			}

			$this->insert_cost_total($inserted_project_id,$prj_install_hrs,$is_double_time);

			$this->session->set_flashdata('curr_tab', 'project-details');			

			$works_from_selected_project = $this->works_m->display_all_works($copy_work_project_id);

			foreach ($works_from_selected_project->result() as $row) {
				$work_notes_raw = $this->projects_m->fetch_project_notes($row->note_id);
				$work_notes = array_shift($work_notes_raw->result_array());

				$note_id = $this->works_m->insert_work_notes($work_notes['comments'],$work_notes['notes']);

				if($include_work_estimate == 1){
					$work_estimate = $row->work_estimate;
					$total_work_quote = $work_estimate + ($work_estimate*($project_markup/100));
				}else{
					$work_estimate = 0;
					$total_work_quote = $work_estimate + ($work_estimate*($project_markup/100));
				}				

				$work_id = $this->works_m->insert_new_works($row->contractor_type,$row->work_con_sup_id,$row->other_work_desc,$project_markup,$note_id,$row->is_deliver_office,'',$row->work_reply_date,$inserted_project_id,'0',$work_estimate,$total_work_quote);

				$works_joinery_raw = $this->works_m->display_all_works_joinery($row->works_id);
				foreach ($works_joinery_raw->result() as $joinery_row) {

					$joinery_notes_raw = $this->projects_m->fetch_project_notes($joinery_row->note_id);
					$joinery_notes = array_shift($joinery_notes_raw->result_array());

					$joinery_note_id = $this->works_m->insert_work_notes($joinery_notes['comments'],$joinery_notes['notes']);
					$added_new_joinery_id = $this->works_m->insert_works_joinery($work_id,$joinery_row->joinery_id,$project_markup,$joinery_note_id,$joinery_row->is_deliver_office,'',$joinery_row->work_reply_date);

				}

				$considerations_raw = $this->works_m->fetch_considerations($row->works_id);
				$consdr = array_shift($considerations_raw->result_array());
				$this->works_m->insert_considerations($work_id,0, $consdr['site_inspection_req'], $consdr['special_conditions'], $consdr['additional_visit_req'], $consdr['operate_during_install'], $consdr['week_work'], $consdr['weekend_work'], $consdr['after_hours_work'], $consdr['new_premises'], $consdr['free_access'], $consdr['other'], $consdr['otherdesc']);

				$this->session->set_flashdata('curr_tab', 'works');
			}


			redirect('/projects/view/'.$inserted_project_id);
		}
		 
	}


	public function insert_cost_total($inserted_project_id,$prj_install_hrs,$is_double_time){
		$site_costs_raw = $this->admin_m->fetch_site_costs();
		$site_costs = array_shift($site_costs_raw->result_array());

		if($is_double_time > 0){
			$install_cost_total = $site_costs['total_double_time']*$prj_install_hrs;
		}else{
			$install_cost_total = $site_costs['total_amalgamated_rate']*$prj_install_hrs;
		}
		$this->projects_m->insert_cost_total($inserted_project_id,$install_cost_total);
	}


	public function update_install_cost_total($inserted_project_id,$prj_install_hrs,$is_double_time){
		$site_costs_raw = $this->admin_m->fetch_site_costs();
		$site_costs = array_shift($site_costs_raw->result_array());

		if($is_double_time > 0){
			$install_cost_total = $site_costs['total_double_time']*$prj_install_hrs;
		}else{
			$install_cost_total = $site_costs['total_amalgamated_rate']*$prj_install_hrs;
		}
		$this->projects_m->update_install_cost_total($inserted_project_id,$install_cost_total);
	}


	public function fetch_project_totals($project_id){

		$project_details_raw = $this->projects_m->fetch_project_details($project_id);		
		$project_details = array_shift($project_details_raw->result_array());

		$system_default_raw = $this->admin_m->latest_system_default($project_details['defaults_id']);
		$system_default = array_shift($system_default_raw->result_array());

		$markup_raw = $this->admin_m->fetch_markup($system_default['markup_id']);
		$markup = array_shift($markup_raw->result_array());

		$site_costs_raw = $this->admin_m->fetch_site_costs($system_default['site_cost_id']);
		$site_costs = array_shift($site_costs_raw->result_array());

		$labour_cost_raw = $this->admin_m->fetch_labour_cost($system_default['labour_cost_id']);
		$labour_cost = array_shift($labour_cost_raw->result_array());

		$admin_defaults_raw = $this->admin_m->fetch_admin_defaults($system_default['admin_default_id']);
		$admin_defaults = array_shift($admin_defaults_raw->result_array());

		$project_cost_total_raw = $this->projects_m->get_project_cost_total($project_id);		
		$project_cost_total = array_shift($project_cost_total_raw->result_array());

		$leave_percentage = ($labour_cost['total_leave_days']/$labour_cost['total_work_days'])*100;
		$labour_cost['leave_percentage'] = round($leave_percentage, 2);
		$labour_cost_grand_total = $labour_cost['leave_percentage']+$labour_cost['superannuation']+$labour_cost['workers_compensation']+$labour_cost['payroll_tax']+$labour_cost['leave_loading']+$labour_cost['other']; 

		$payroll_tax = $labour_cost['payroll_tax'];
		$rate = $site_costs['rate'];

		$gp_on_cost_total_hr = $rate+(($rate*$labour_cost_grand_total)/100);
		$gp_on_cost_time_half_hr = $gp_on_cost_total_hr + ((0.5*$rate) + (((0.5*$rate)*$payroll_tax )/100));
		$gp_on_cost_time_double_hr = $gp_on_cost_total_hr + ($rate+(($rate*$payroll_tax)/100));

		//$gp_amalgamated_rate = ($admin_defaults['labor_split_time_and_half']/100)*$gp_on_cost_time_half_hr + ($admin_defaults['labor_split_double_time']/100)*$gp_on_cost_time_double_hr + ($admin_defaults['labor_split_standard']/100)*$gp_on_cost_total_hr;

		$gp_data_arr = $this->admin->get_double_amalgated_rate($site_costs,$labour_cost,$admin_defaults);

		if($project_details['install_time_hrs'] == 0){
			$total_install_rate = 0;
		}else{
			$total_install_rate = $project_cost_total['install_cost_total'];
		}

		//$total_install_rate = $project_cost_total['install_cost_total'];
		


		$total_estimated_cost = $total_install_rate + ($total_install_rate * ($admin_defaults['installation_labour_mark_up']/100));
		$total_quoted_cost = $total_estimated_cost + ($total_estimated_cost * ($project_details['markup']/100));

		if($project_details['is_double_time']>0){
			$actual_cost_total = ($site_costs['rate']*2);
			//$gp_cost_actual = $gp_on_cost_time_half_hr;
			$gp_cost_actual = $gp_data_arr['gp_on_cost_time_double_hr'];
		}else{
			$actual_cost_total = $gp_data_arr['gp_amalgamated_rate'];
			$gp_cost_actual = $gp_data_arr['gp_amalgamated_rate'];
		}

		$prj_install_hrs = $project_details['install_time_hrs'];
/*
		if($project_details['install_time_hrs'] > 0 && $project_details['install_time_hrs'] != '' ){
			$prj_install_hrs = $project_details['install_time_hrs'];
		}else{
			//$prj_install_hrs = $project_details['labour_hrs_estimate'];
			$prj_install_hrs = 0;
		}
*/
		//echo "<script>alert('".$total_estimated_cost."');</script>";
		//echo "<script>alert('".$prj_install_hrs."');</script>";

		$actual_project_cost = ($actual_cost_total * $prj_install_hrs) + $project_cost_total['work_estimated_total'];

		/* gP variables*/
		//$gp_actual_cost_total = $gp_cost_actual + (($gp_cost_actual*$labour_cost_grand_total) / 100);
		$gp_actual_project_cost = ($gp_cost_actual * $prj_install_hrs) + $project_cost_total['work_estimated_total'];
		/* gP variables*/

		$final_total_cost = $project_cost_total['work_price_total'] + $total_install_rate;
		$final_total_estimated = $project_cost_total['work_estimated_total'] + $total_estimated_cost;
		$final_total_quoted = $project_cost_total['work_quoted_total'] + $total_quoted_cost;


/*
		if($project_details['install_time_hrs'] == 0.00 && $project_cost_total['work_estimated_total'] == 0.00){
			$actual_project_cost = $project_details['labour_hrs_estimate'] * $actual_cost_total;
			$final_total_quoted = $project_details['budget_estimate_total'];
		}

		if($project_details['install_time_hrs'] > 0.00 && $project_cost_total['work_estimated_total'] == 0.00){
			$final_total_quoted = $actual_project_cost;
		}
*/
		//echo "<script>alert('".$gp_actual_project_cost."');</script>";
if($final_total_quoted== 0 && $gp_actual_project_cost == 0){
$gp = 0;
}else{
		$gp = round(($final_total_quoted - $gp_actual_project_cost) / $final_total_quoted,4);

}


		if($project_details['project_total'] != $final_total_quoted){
			$this->projects_m->update_project_total($project_id,$final_total_quoted);
		}

		$result = array(
			"gp" => $gp,
			"final_total_cost" => $actual_project_cost,
			"final_total_estimated" => $final_total_estimated,
			"final_total_quoted" => $final_total_quoted,
			"final_labor_cost" => $total_quoted_cost,
			"labour_cost_grand_total" => $labour_cost_grand_total,
			"install_cost_total" => $project_cost_total['install_cost_total'],
			"variation_total" =>$project_cost_total['variation_total']
			);

		//var_dump($result);
		return $result;
	}

	public function display_project_applied_defaults($project_id){

		$project_details_raw = $this->projects_m->fetch_project_details($project_id);		
		$project_details = array_shift($project_details_raw->result_array());

		$system_default_raw = $this->admin_m->latest_system_default($project_details['defaults_id']);
		$system_default = array_shift($system_default_raw->result_array());

		$markup_raw = $this->admin_m->fetch_markup($system_default['markup_id']);
		$markup = array_shift($markup_raw->result_array());

		$site_costs_raw = $this->admin_m->fetch_site_costs($system_default['site_cost_id']);
		$site_costs = array_shift($site_costs_raw->result_array());

		$admin_defaults_raw = $this->admin_m->fetch_admin_defaults($system_default['admin_default_id']);
		$admin_defaults = array_shift($admin_defaults_raw->result_array());


		$labour_cost_raw = $this->admin_m->fetch_labour_cost($system_default['labour_cost_id']);
		$labour_cost = array_shift($labour_cost_raw->result_array());

		$gp_data_arr = $this->admin->get_double_amalgated_rate($site_costs,$labour_cost,$admin_defaults);

		$result = array(
			"admin_total_rate_amalgated" => $site_costs['total_amalgamated_rate'],
			"admin_total_rate_double" => $site_costs['total_double_time'],
			"admin_actual_rate_amalgate" => $gp_data_arr['gp_amalgamated_rate'],
			"admin_actual_rate_double" => $gp_data_arr['gp_on_cost_time_double_hr'],
			"admin_install_markup" => $admin_defaults['installation_labour_mark_up'],
			"admin_gst_rate" => $admin_defaults['gst_rate'],
			"admin_hourly_rate" => $site_costs['rate']
		);

		//var_dump($result);
		return $result;
	}

	public function fetch_shopping_center($shopping_center_name_brand=''){

		if($shopping_center_name_brand!=''){
			$name_brand = $shopping_center_name_brand;
		}else{
			$name_brand = $_POST['ajax_var'];
		}

		if($name_brand!=''){
			$job_subcategory = $this->projects_m->fetch_shopping_center($name_brand);
			foreach ($job_subcategory->result() as $row) {
				echo '<option value="'.$row->shopping_center_detail_id.'" >'.ucfirst(strtolower($row->suburb)).'</option>';
			}
		}

		
	}

	public function list_job_subcategory(){
		$job_subcategory = $this->projects_m->display_job_subcategory();
		foreach ($job_subcategory->result_array() as $row){
			echo '<option value="2_'.$row['job_sub_cat_id'].'">'.$row['job_sub_cat'].'</option>';
		}
	}

	public function job_cat_list_no_other(){
		$jobs_cat_type = $this->projects_m->display_all_job_category_type('');		
		foreach ($jobs_cat_type->result() as $row){
			//if($row->job_category !== 'Other'){
				echo '<option value="2_'.$row->job_category_id.'">'.$row->job_category.'</option>';
			//}
		}		
	}

	public function list_supplier_category(){
		$supplier_category = $this->projects_m->display_supplier_category();
		foreach ($supplier_category->result_array() as $row){
			echo '<option value="3_'.$row['supplier_cat_id'].'">'.$row['supplier_cat_name'].'</option>';
		}
	}

	public function list_project_comments($project_id=''){


		if(isset($_POST['project_id'])){
			$project_id_set = $_POST['project_id'];
		}elseif($project_id!=''){
			$project_id_set = $project_id;
		}else{
			return false;
		}

		$project_comments = $this->projects_m->list_project_comments($project_id_set);

		$project_details = $this->projects_m->fetch_project_details($project_id_set);

		if($project_details->num_rows > 0){

			if($project_comments->num_rows > 0){

				foreach ($project_comments->result_array() as $row){

					$fetch_user= $this->user_model->fetch_user($row['user_id']);
					$user = array_shift($fetch_user->result_array());

					echo '<div class="notes_line"><p class="">'.ucfirst (nl2br($row['project_comments'])).'</p><small><i class="fa fa-user"></i> '.$user['user_first_name'].' '.$user['user_last_name'].'<br /><i class="fa fa-calendar"></i> '.$row['date_posted'].'</small></div>';
				} 
			}else{
				echo '<div class="notes_line no_posted_comment"><p>No posted comments yet!</p></div>';
			}

		}else{
			echo 'Error';
		}
	}

	public function list_recent_project_comment($project_id){
		$project_comments = $this->projects_m->list_project_comments($project_id);
		foreach ($project_comments->result_array() as $row){
			$fetch_user= $this->user_model->fetch_user($row['user_id']);
			$user = array_shift($fetch_user->result_array());
			echo '<p>'.$row['project_comments'].'</p><small><i class="fa fa-user"></i> '.$user['user_first_name'].' '.$user['user_last_name'].'<br /><i class="fa fa-calendar"></i> '.$row['date_posted'].'</small>';
			return;
		}
	}

	public function add_project_comment(){
		date_default_timezone_set("Australia/Perth");
		$this->clear_apost();
		$raw_project_comment = $_POST['ajax_var'];
		$project_comment = explode('`', $raw_project_comment);
		$datestring = "%l, %d%S, %F %Y %g:%i:%s %A"; $time = time();  

		$date_posted = mdate($datestring, $time);
		$supplier_category = $this->projects_m->add_project_comment($project_comment[1],$date_posted,$project_comment[2],$project_comment[0]);
		
		echo $date_posted;
	}

	public function update_project_details(){
		$this->clear_apost();

		$this->users->_check_user_access('projects',2);

		$project_id = $this->uri->segment(3);
		$data['project_id'] = $project_id;



		$q_proj = $this->projects_m->fetch_complete_project_details($project_id);
		if($q_proj->num_rows > 0){
			$data = array_shift($q_proj->result_array());

			$contact_person_name = "";
			$contact_person_number = "";
			$contact_person_mobile = "";
			$contact_person_email = "";

			$q_proj_site_cont = $this->projects_m->fetch_project_site_contact($project_id);
			foreach ($q_proj_site_cont->result_array() as $row){
				$contact_person_name = $row['contact_person_name'];
				$contact_person_number = $row['contact_person_number'];
				$contact_person_mobile = $row['contact_person_mobile'];
				$contact_person_email = $row['contact_person_email'];
			}

			//echo $contact_person_name;

			$data['contact_person_name'] = $contact_person_name;
			$data['contact_person_number'] = $contact_person_number;
			$data['contact_person_mobile'] = $contact_person_mobile;
			$data['contact_person_email'] = $contact_person_email;

			$admin_defaults_raw = $this->admin_m->latest_system_default($data['defaults_id']);
			$admin_defaults = array_shift($admin_defaults_raw->result_array());

			$markup_raw = $this->admin_m->fetch_markup($admin_defaults['markup_id']);
			$markup_defaults = array_shift($markup_raw->result_array());

			$q_project_notes = $this->projects_m->fetch_project_notes($data['notes_id']);
			$project_notes = array_shift($q_project_notes->result_array());
			$data['project_comments'] = $project_notes['comments'];

			$q_focus_company = $this->company_m->display_company_detail_by_id($data['focus_company_id']);
			$focus_company = array_shift($q_focus_company->result_array());
			$data['focus_company_id'] = $focus_company['company_id'];
			$data['focus_company_name'] = $focus_company['company_name'];

			
			$maintenance_administrator = $this->user_model->fetch_user_by_role(7);
			$data['maintenance_administrator'] = $maintenance_administrator->result();

			$q_client_company = $this->company_m->display_company_detail_by_id($data['client_id']);
			$client_company = array_shift($q_client_company->result_array());
			$data['client_company_id'] = $client_company['company_id'];
			$data['client_company_name'] = $client_company['company_name'];

			$q_contact_person = $this->company_m->fetch_all_contact_persons($data['primary_contact_person_id']);
			$contact_person = array_shift($q_contact_person->result_array());
			$data['contact_person_id'] = $contact_person['contact_person_id'];
			$data['contact_person_fname'] = $contact_person['first_name'];
			$data['contact_person_lname'] = $contact_person['last_name'];


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

			$invoice_address_id = $data['invoice_address_id'];

			$p_query_address = $this->company_m->fetch_complete_detail_address($data['invoice_address_id']);
			$p_temp_data = array_shift($p_query_address->result_array());
			$data['i_po_box'] = $p_temp_data['po_box'];
			$data['i_unit_level'] = ucwords(strtolower($p_temp_data['unit_level']));
			$data['i_unit_number'] = $p_temp_data['unit_number'];
			$data['i_street'] = ucwords(strtolower($p_temp_data['street']));
			$data['i_suburb'] = $p_temp_data['suburb'];
			$data['i_state'] = $p_temp_data['name'];
			$data['i_postcode'] = $p_temp_data['postcode'];
			//$data['i_address_id'] = $p_temp_data['postal_address_id'];

			$data['i_shortname'] = $p_temp_data['shortname'];
			$data['i_state_id'] =  $p_temp_data['state_id'];
			$data['i_phone_area_code'] = $p_temp_data['phone_area_code']; 

			$q_project_manager = $this->user_model->fetch_user($data['project_manager_id']);
			$project_manager = array_shift($q_project_manager->result_array());
			$data['pm_user_id'] = $project_manager['user_id'];
			$data['pm_user_first_name'] = $project_manager['user_first_name'];
			$data['pm_user_last_name'] = $project_manager['user_last_name'];

			$q_project_admin = $this->user_model->fetch_user($data['project_admin_id']);
			$project_admin = array_shift($q_project_admin->result_array());
			$data['pa_user_id'] = $project_admin['user_id'];
			$data['pa_user_first_name'] = $project_admin['user_first_name'];
			$data['pa_user_last_name'] = $project_admin['user_last_name'];

			$q_project_estiamator_id = $this->user_model->fetch_user($data['project_estiamator_id']);
			$project_estiamator = array_shift($q_project_estiamator_id->result_array());
			$data['pe_user_id'] = $project_estiamator['user_id'];
			$data['pe_user_first_name'] = $project_estiamator['user_first_name'];
			$data['pe_user_last_name'] = $project_estiamator['user_last_name'];


			$pg_markup_raw = $this->projects->fetch_mark_up_by($data['job_category'] , $markup_defaults['markup_id']);
			$pg_markup = explode('|',$pg_markup_raw);
			$data['min_markup'] = $pg_markup[1];


			$shopping_center_q = $this->projects_m->select_shopping_center($data['address_id']);
			$shopping_center = array_shift($shopping_center_q->result_array());

			$data['shopping_center_id'] = $shopping_center['shopping_center_id'];
			$data['shopping_center_brand_name'] = $shopping_center['shopping_center_brand_name'];

			$all_company_list = $this->company_m->fetch_all_company(NULL);
			if($all_company_list->num_rows > 0){
				$data['all_company_list'] = $all_company_list->result();
			}

			$all_aud_states = $this->company_m->fetch_all_states();
			$data['all_aud_states'] = $all_aud_states->result();

			$focus = $this->admin_m->fetch_all_company_focus();
			$data['focus'] = $focus->result();

			$project_manager = $this->user_model->fetch_user_by_role(3);
			$data['project_manager'] = $project_manager->result();

			$project_administrator = $this->user_model->fetch_user_by_role(2);
			$data['project_administrator'] = $project_administrator->result();

			$estimator = $this->user_model->fetch_user_by_role(8);
			$data['estimator'] = $estimator->result();		

			$shopping_center = $this->projects_m->fetch_shopping_center();
			$data['shopping_center'] = $shopping_center->result();

			$this->form_validation->set_rules('project_name', 'Project Name','trim|required|xss_clean');
			$this->form_validation->set_rules('site_start', 'Site Start','trim|required|exact_length[10]|xss_clean');
			$this->form_validation->set_rules('site_finish', 'Site Finish','trim|required|exact_length[10]|xss_clean');
			$this->form_validation->set_rules('job_category', 'Job Category','trim|required|xss_clean');
			$this->form_validation->set_rules('project_date', 'Project Date','trim|required|xss_clean');


			$this->form_validation->set_rules('job_date', 'Job Date','trim|exact_length[10]|xss_clean');


			if( $this->input->post('is_shopping_center') != 1){
				$this->form_validation->set_rules('street', 'Site Street','trim|required|xss_clean');
				$this->form_validation->set_rules('suburb_a', 'Site Project Address Suburb','trim|required|xss_clean');
				$this->form_validation->set_rules('state_a', 'Site State','trim|required|xss_clean');
				$this->form_validation->set_rules('postcode_a', 'Site Postcode','trim|required|xss_clean');
			}else{
				$this->form_validation->set_rules('shop_tenancy_number', 'Site Shop/Tenancy Number','trim|required|xss_clean');
				$this->form_validation->set_rules('brand_shopping_center', 'Site Brand/Shopping Center','trim|required|xss_clean');
			}

			$this->form_validation->set_rules('street_b', 'Invoice Street','trim|required|xss_clean');
			$this->form_validation->set_rules('suburb_b', 'Invoice Address Suburb','trim|required|xss_clean');
			$this->form_validation->set_rules('state_b', 'Invoice State','trim|required|xss_clean');
			$this->form_validation->set_rules('postcode_b', 'Invoice Postcode','trim|required|xss_clean');



			$this->form_validation->set_rules('project_manager', 'Project Manager','trim|required|xss_clean');
			$this->form_validation->set_rules('project_admin', 'Project Admin','trim|required|xss_clean');
			$this->form_validation->set_rules('estimator', 'Estimator','trim|required|xss_clean');
			$this->form_validation->set_rules('company_prg', 'Company Client','trim|required|xss_clean');
			$this->form_validation->set_rules('contact_person', 'Contact Person','trim|required|xss_clean');
			$this->form_validation->set_rules('install_hrs', 'Site Hours','trim|xss_clean');

//echo $this->company->cap_first_word($this->company->if_set($this->input->post('project_name', true)));
//var_dump($_POST);


			$data['main_content'] = 'project_detail_update';
			$data['screen'] = 'Update Project Details';


			if($this->form_validation->run() === false){
				$this->clear_apost();
				$data['error' ] = validation_errors();
				$this->load->view('page', $data);
//valid_input_simple
			}else{
				$this->clear_apost();
				$focus_id = $this->input->post('focus');
				$project_name = $this->input->post('project_name');
				$client_po = $this->input->post('client_po');
				$job_type = $this->input->post('job_type');
				$job_category = $this->input->post('job_category');
				$project_date = $this->input->post('project_date');
				$site_start = $this->input->post('site_start');
				$site_finish = $this->input->post('site_finish');

				//================= For Maintenance Site sheet ===============
					$site_cont_person = $this->input->post('site_cont_person');
					$site_cont_number = $this->input->post('site_cont_number');
					$site_cont_mobile = $this->input->post('site_cont_mobile');
					$site_cont_email = $this->input->post('site_cont_email');

					$proj_site_cont = $this->projects_m->fetch_project_site_contact($project_id);
					if($project_work_list->num_rows == 0){
						$this->projects_m->insert_project_site_contact($project_id, $site_cont_person, $site_cont_number, $site_cont_mobile, $site_cont_email);
					}else{
						$this->projects_m->update_project_site_contact($project_id, $site_cont_person, $site_cont_number, $site_cont_mobile, $site_cont_email);
					}
				//================= For Maintenance Site sheet ===============

				$is_wip = ($job_date != '' ? 1 : 0); 

				$q_proj_prev = $this->projects_m->fetch_complete_project_details($project_id);
				$prev_project_details = array_shift($q_proj_prev->result_array());

				$attempt = 0;
				if($this->session->userdata('is_admin') == 1 || $this->session->userdata('user_role_id') == 3 || ( $this->session->userdata('user_role_id') == 7 && $prev_project_details['job_category'] == 'Maintenance' )  ){
					$job_date = $this->input->post('job_date');
				}else{
					if($prev_project_details['job_date'] == ''){
						$job_date = $this->input->post('job_date');
					}else{
						$job_date = $prev_project_details['job_date'];
						$attempt = 1;
					}
				}

				if($job_date != ''){
					$this->form_validation->set_rules('job_date', 'Job Date','trim|exact_length[10]|xss_clean');
				}

				if(strlen($job_date) == 0 && $prev_project_details['job_date'] == ''){
					$actions = 'Updated project details, with no job date';
				}elseif($job_date == $prev_project_details['job_date']){
					$actions = 'Updated project details';
				}elseif($job_date != $prev_project_details['job_date'] && strlen($job_date) > 0 && strlen($prev_project_details['job_date']) > 0 ){
					$actions = 'Replaced job date';
				}elseif($job_date != $prev_project_details['job_date'] && strlen($job_date) == 0){
					$actions = 'Removed job date';
				}else{
					$actions = 'Added job date';
				}

				if($attempt == 1){
					$actions = 'Updated project details';
				}

				$data['unit_level'] = $this->company->if_set($this->input->post('unit_level', true));
				$data['unit_number'] = $this->company->if_set($this->input->post('unit_number', true));
				$data['street'] = $this->company->cap_first_word($this->company->if_set($this->input->post('street', true)));
				$data['postcode_a'] = $this->company->if_set($this->input->post('postcode_a', true));

				$data['pobox'] = $this->company->if_set($this->input->post('pobox', true));
				$data['unit_level_b'] = $this->company->if_set($this->input->post('unit_level_b', true));
				$data['number_b'] = $this->company->if_set($this->input->post('number_b', true));			
				$data['street_b'] = $this->company->cap_first_word($this->company->if_set($this->input->post('street_b', true)));
				$data['postcode_b'] = $this->company->if_set($this->input->post('postcode_b', true));		

				$state_a_arr = explode('|', $this->input->post('state_a', true));
				$data['state_a'] = $state_a_arr[3];

				$suburb_a_ar = explode('|',$this->company->if_set($this->input->post('suburb_a', true)));
				$data['suburb_a'] = strtoupper($suburb_a_ar[0]);

				$state_b_arr = explode('|', $this->input->post('state_b', true));
				$data['state_b'] = $state_b_arr[3];

				$suburb_b_ar = explode('|',$this->company->if_set($this->input->post('suburb_b', true)));
				$data['suburb_b'] = strtoupper($suburb_b_ar[0]);

				$project_manager_id = $this->input->post('project_manager');
				$project_admin_id = $this->input->post('project_admin');
				$project_estiamator_id = $this->input->post('estimator');
				$company_prg_arr =  explode('|',$this->input->post('company_prg'));
				$client_id = $company_prg_arr[1];

				$contact_person_id = $this->input->post('contact_person');

				$est_amt = $this->input->post('project_total');
				$project_total = str_replace (',','', $est_amt);

				if($this->invoice->if_project_invoiced_full($project_id) == 1){
					$project_markup = $prev_project_details['markup'];
					$install_hrs = $prev_project_details['install_time_hrs'];
				}else{					
					$install_hrs = $this->input->post('install_hrs');
					$project_markup = $this->input->post('project_markup');
				}

				$project_area = $this->input->post('project_area');
				$comments = $this->input->post('comments');
				$project_status_id = 1;

				$shop_tenancy_number = $this->input->post('shop_tenancy_number'); 

				$focus_user_id = $this->session->userdata('user_id');

				$labour_hrs_estimate = $this->input->post('labour_hrs_estimate', true);

				$is_shopping_center = $this->input->post('is_shopping_center');
				$is_double_time = $this->input->post('is_double_time');



//======= fetch database Mark-up ===
				$proj_q = $this->projects_m->select_particular_project($project_id);
				foreach ($proj_q->result_array() as $row){
					$proj_markup = $row['markup'];
				}


				if($is_shopping_center == 1){
					$site_address_id =  $this->input->post('brand_shopping_center');
				}else{
					$site_address_id = $data['address_id'];
					$shop_tenancy_number = '';
					$this->company_m->update_address_details($data['address_id'],$data['unit_number'],$data['unit_level'],$data['street'],$data['suburb_a'],$data['postcode_a']);
				}


				$this->company_m->update_address_details($invoice_address_id,$data['number_b'],$data['unit_level_b'],$data['street_b'],$data['suburb_b'],$data['postcode_b'],$data['pobox']);

				$this->projects_m->update_full_project_details($project_id,$project_name,$client_id,$contact_person_id,$client_po,$job_type,$job_category,$job_date,$site_start,$site_finish,$is_wip,$install_hrs,$is_double_time,$project_total,$labour_hrs_estimate,$project_markup,$project_area,$project_manager_id,$project_admin_id,$project_estiamator_id,$shop_tenancy_number,$site_address_id,$shop_tenancy_number,$site_address_id,$invoice_address_id,$focus_id);
				

				$this->company_m->update_notes_comments($data['notes_id'],$comments);

				if($site_finish != ''){
					$this->projects_m->update_vr_inv_date($site_finish,$site_finish,$project_id );
				}


				if($install_hrs != '' && $install_hrs > 0){
					$prj_install_hrs = $install_hrs;
				}else{
					$prj_install_hrs = $labour_hrs_estimate;
				}

				$this->update_install_cost_total($project_id,$prj_install_hrs,$is_double_time);
//===== Change markup for all the works of the selected project ===
				if($proj_markup !== $project_markup):
					$work_q = $this->works_m->display_all_works($project_id);
					foreach ($work_q->result_array() as $row){
						$works_id = $row['works_id'];
						if($row['work_con_sup_id'] == '53'){
							$joinery_works_id = $works_id;
						}
						$this->works_m->update_works(1,$works_id,"",$project_markup,"","","","","","","","","","","","","","","","","","","","","","");
						//$this->works_m->update_works(1,$works_id,"",$project_markup,"","","","","","","","","","","","","","","","","","","","","");
						$work_estimate = $row['work_estimate'];
						$quote = $work_estimate + ($work_estimate * ($project_markup/100));
						$this->works_m->update_works(8,$works_id,"","","","","","","","","","","","","","","","","","","","","",$work_estimate,$quote,"");
						//$this->works_m->update_works(8,$works_id,"","","","","","","","","","","","","","","","","","","","","",$work_estimate,$quote);
					}

					if($joinery_works_id !== "" ){
						$work_joinery_q = $this->works_m->display_all_works_joinery($joinery_works_id);
						foreach ($work_joinery_q->result_array() as $row){
							$joinery_id = $row['work_joinery_id'];
							$joinery_estimate = $row['j_estimate'];
		    				$joiner_quoted = $joinery_estimate +  ($joinery_estimate * ($project_markup/100));
		    				$this->works_m->update_joinery_markup($joinery_id,$project_markup,$joiner_quoted);
						}

						$work_joinery = $this->works_m->display_all_works_joinery($joinery_works_id);
				      	$t_price = 0;
				      	$t_estimate = 0;
				      	$t_quote = 0;
				      	foreach ($work_joinery->result_array() as $row)
						{
						   $t_price = $t_price + $row['j_price'];
						   $t_estimate = $t_estimate + $row['j_estimate'];
						   $t_quote = $t_quote + $row['j_quote'];
						}

						$this->works_m->update_work_joinery($joinery_works_id,$t_price,$t_estimate,$t_quote);
					}
					
		//== Total Works in project
					$works_q = $this->works_m->display_all_works($project_id);
					$t_price = 0;
					$t_estimate = 0;
					$t_quoted = 0;
					foreach ($works_q->result_array() as $row){
						$t_price = $t_price + $row['price'];
						$t_estimate = $t_estimate + $row['work_estimate'];
						$t_quoted = $t_quoted + $row['total_work_quote'];
					}

					$this->projects_m->update_project_cost_total($project_id,$t_price,$t_estimate,$t_quoted);
				endif;

				$this->session->set_flashdata('full_update', 'Project Details is now updated!');


				$type = 'Update';

				date_default_timezone_set("Australia/Perth");
				$user_id = $this->session->userdata('user_id');
				$date = date("d/m/Y");
				$time = date("H:i:s");
				$this->user_model->insert_user_log($user_id,$date,$time,$actions,$project_id,$type);



				redirect('/projects/view/'.$project_id);
			}

		}else{
			redirect('/projects');
		}
	}

	public function delete_project(){
		$project_id = $this->uri->segment(3);

		$this->session->set_flashdata('project_deleted', 'A project is been deleted. Project No.'.$project_id);
		$this->projects_m->delete_project_details($project_id);
		redirect('/projects');
	}


	public function quick_update(){
		$this->clear_apost();

		$project_id = $_POST['project_id'];
		$project_name = $this->input->post('project_name');
		$budget_estimate_total = str_replace (',','', $this->input->post('budget_estimate_total') );

		$client_po = $this->input->post('client_po');
		$site_labour_estimate = $this->input->post('site_labour_estimate');
		$site_start = $this->input->post('site_start');
		$site_finish = $this->input->post('site_finish');
		
		$is_double_time = $this->input->post('is_double_time');
		$type = 'Update';
		$attempt = 0;

		$q_proj = $this->projects_m->fetch_complete_project_details($project_id);
		$prev_project_details = array_shift($q_proj->result_array());

		//if($this->invoice->if_project_invoiced_full($project_id) == 1){
			//$project_markup = $prev_project_details['markup'];
			//$install_time_hrs = $prev_project_details['install_time_hrs'];
		//}else{
			$project_markup = $this->input->post('project_markup');
			$install_time_hrs = $this->input->post('install_time_hrs');
		//}

		if($this->session->userdata('is_admin') == 1 || $this->session->userdata('user_role_id') == 3 || ( $this->session->userdata('user_role_id') == 7 && $prev_project_details['job_category'] == 'Maintenance' )  ){
			$job_date = $_POST['job_date'];
		}else{
			if($prev_project_details['job_date'] == ''){
				$job_date = $_POST['job_date'];
			}else{
				$job_date = $prev_project_details['job_date'];
				$attempt = 1;
			}
		}

		if(strlen($job_date) > 0 && $job_date != ''){
			$is_wip = 1;
		}else{
			$is_wip = 0;
		}

		if($site_finish != ''){
			$this->projects_m->update_vr_inv_date($site_finish,$site_finish,$project_id );
		}

		if(strlen($job_date) == 0 && $prev_project_details['job_date'] == ''){
			$actions = 'Updated project details, with no job date';
		}elseif($job_date == $prev_project_details['job_date']){
			$actions = 'Updated project details';
		}elseif($job_date != $prev_project_details['job_date'] && strlen($job_date) > 0 && strlen($prev_project_details['job_date']) > 0 ){
			$actions = 'Replaced job date';
		}elseif($job_date != $prev_project_details['job_date'] && strlen($job_date) == 0){
			$actions = 'Removed job date';
		}else{
			$actions = 'Added job date';
		}

		if($attempt == 1){
			$actions = 'Updated project details';
		}

		$has_updated_wip = 0;

		if($install_time_hrs != '' && $install_time_hrs > 0){
			$prj_install_hrs = $install_time_hrs;
		}else{
			$prj_install_hrs = $site_labour_estimate;
		}

		$proj_q = $this->projects_m->select_particular_project($project_id);
		foreach ($proj_q->result_array() as $row){
			$proj_markup = $row['markup'];
		}

		
		$has_updated_wip = $this->projects_m->set_wip_project($project_id,$is_wip);

		$this->projects_m->project_details_quick_update($project_id,$project_name,$budget_estimate_total,$job_date,$client_po,$install_time_hrs,$project_markup,$site_start,$site_finish);

		$this->update_install_cost_total($project_id,$prj_install_hrs,$is_double_time);

		date_default_timezone_set("Australia/Perth");
		$user_id = $this->session->userdata('user_id');
		$date = date("d/m/Y");
		$time = date("H:i:s");
		$this->user_model->insert_user_log($user_id,$date,$time,$actions,$project_id,$type);
		

		if($proj_markup !== $project_markup):
			$work_q = $this->works_m->display_all_works($project_id);
			$joinery_works_id = "";
			foreach ($work_q->result_array() as $row){
				$works_id = $row['works_id'];
				if($row['work_con_sup_id'] == '53'){
					$joinery_works_id = $works_id;
				}
				$this->works_m->update_works(1,$works_id,"",$project_markup,"","","","","","","","","","","","","","","","","","","","","","");
				//$this->works_m->update_works(1,$works_id,"",$project_markup,"","","","","","","","","","","","","","","","","","","","","");
				$work_estimate = $row['work_estimate'];
				$quote = $work_estimate + ($work_estimate * ($project_markup/100));
				$this->works_m->update_works(8,$works_id,"","","","","","","","","","","","","","","","","","","","","",$work_estimate,$quote,"");
				//$this->works_m->update_works(8,$works_id,"","","","","","","","","","","","","","","","","","","","","",$work_estimate,$quote);
			}

			if($joinery_works_id !== "" ){
				$work_joinery_q = $this->works_m->display_all_works_joinery($joinery_works_id);
				foreach ($work_joinery_q->result_array() as $row){
					$joinery_id = $row['work_joinery_id'];
					$joinery_estimate = $row['j_estimate'];
    				$joiner_quoted = $joinery_estimate +  ($joinery_estimate * ($project_markup/100));
    				$this->works_m->update_joinery_markup($joinery_id,$project_markup,$joiner_quoted);
				}

				$work_joinery = $this->works_m->display_all_works_joinery($joinery_works_id);
		      	$t_price = 0;
		      	$t_estimate = 0;
		      	$t_quote = 0;
		      	foreach ($work_joinery->result_array() as $row)
				{
				   $t_price = $t_price + $row['j_price'];
				   $t_estimate = $t_estimate + $row['j_estimate'];
				   $t_quote = $t_quote + $row['j_quote'];
				}

				$this->works_m->update_work_joinery($joinery_works_id,$t_price,$t_estimate,$t_quote);
			}
			
//== Total Works in project
			$works_q = $this->works_m->display_all_works($project_id);
			$t_price = 0;
			$t_estimate = 0;
			$t_quoted = 0;
			foreach ($works_q->result_array() as $row){
				$t_price = $t_price + $row['price'];
				$t_estimate = $t_estimate + $row['work_estimate'];
				$t_quoted = $t_quoted + $row['total_work_quote'];
			}

			$this->projects_m->update_project_cost_total($project_id,$t_price,$t_estimate,$t_quoted);

		endif;




		$this->session->set_flashdata('curr_tab', 'project-details');	

		$this->session->set_flashdata('quick_update', 'Values are now updated!');

	// header( "refresh:1; url=".base_url()."projects/view/$project_id" ); 

		if($has_updated_wip == 1){
			redirect('/projects/view/'.$project_id);
		}
//		
	}
}