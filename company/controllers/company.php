<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Company extends MY_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');		
		$this->load->module('users'); 	
		$this->load->model('company_m');	
		if($this->session->userdata('is_admin') == 1 ):		
			$this->load->module('admin');
		endif;	
		if(!$this->users->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif; 
	}
	
	public function index(){
		$this->users->_check_user_access('company',1);
		$data['main_content'] = 'company_v';
		$data['new_compamy_id'] = $this->session->userdata('item');
		$data['comp_type'] = 1;
		$data['screen'] = 'Client';

		$all_aud_states = $this->company_m->fetch_all_states();
		$data['all_aud_states'] = $all_aud_states->result();

		$this->load->view('page', $data);
	}	
	
	public function contractor(){	
		$this->users->_check_user_access('company',1);			
		$data['main_content'] = 'company_v';
		$data['new_compamy_id'] = $this->session->userdata('item');
		$data['comp_type'] = 2;
		$data['screen'] = 'Contractor';

		$all_aud_states = $this->company_m->fetch_all_states();
		$data['all_aud_states'] = $all_aud_states->result();

		$complete = 0;
		$incomplete = 0;
		$no_insurance = 0;
		$comp_expired = 0;

		$company_q = $this->company_m->display_company_by_type(2);
		$today = date('d/m/Y');
		foreach ($company_q->result_array() as $row){
			$expired = 0;
			if($row['public_liability_expiration'] !== ""){
				if($row['public_liability_expiration'] > $today){
					$expired = 1;
				}
			}
			if($row['workers_compensation_expiration'] !== ""){
				if($row['workers_compensation_expiration'] <= $today){
					$expired = 1;
				}
			}
			if($row['income_protection_expiration'] !== ""){
				if($row['income_protection_expiration'] <= $today){
					$expired = 1;
				}
			}
			if($expired == 1){
				$comp_expired++;
			}
			if($row['has_insurance_public_liability'] == 1){
				if($row['has_insurance_workers_compensation'] == 1){
					$complete++;
				}else{
					if($row['has_insurance_income_protection'] == 1){
						$complete++;
					}else{
						$incomplete++;
					}
				}
			}else{
				
				if($row['has_insurance_workers_compensation'] == 1){
					$incomplete++;
				}else{
					if($row['has_insurance_income_protection'] == 1){
						$incomplete++;
					}else{
						$no_insurance++;
					}
				}
			}
		}
		
		$data['complete'] = $complete;
		$data['incomplete'] = $incomplete;
		$data['no_insurance'] = $no_insurance;
		$data['comp_expired'] = $comp_expired;

		$this->load->view('page', $data);
	}
	
	public function supplier(){	
		$this->users->_check_user_access('company',1);			
		$data['main_content'] = 'company_v';
		$data['new_compamy_id'] = $this->session->userdata('item');
		$data['comp_type'] = 3;
		$data['screen'] = 'Supplier';

		$all_aud_states = $this->company_m->fetch_all_states();
		$data['all_aud_states'] = $all_aud_states->result();
		
		$this->load->view('page', $data);
	}
	
	public function view(){

		$curr_company_id = $this->uri->segment(3);


		$comp_type_list = $this->company_m->fetch_all_company_types();
		$data['comp_type_list'] = $comp_type_list->result();

		$suburb_list = $this->company_m->fetch_all_suburb();
		$data['suburb_list'] = $suburb_list->result();

		$all_company_list = $this->company_m->fetch_all_company(NULL);
		if($all_company_list->num_rows > 0){
			$data['all_company_list'] = $all_company_list->result();
		}else{
			$data['all_company_list'] = '';
		}

		$all_aud_states = $this->company_m->fetch_all_states();
		$data['all_aud_states'] = $all_aud_states->result();


		$company_detail_q = $this->company_m->fetch_all_company($curr_company_id);			
		$company_detail = array_shift($company_detail_q->result_array());

		$data['company_name'] = $company_detail['company_name'];
		$data['abn'] = $company_detail['abn'];
		$data['acn'] = $company_detail['acn'];
		$data['company_id'] = $company_detail['company_id'];

		//INSURANCE
		$public_liability = $company_detail['has_insurance_public_liability'];
		$data['public_liability'] = $public_liability;
		if($public_liability == 1){
			$data['pl_start_date'] = $company_detail['public_liability_start_date'];
			$data['pl_expiration'] = $company_detail['public_liability_expiration'];
		}else{
			$data['pl_start_date'] = "";
			$data['pl_expiration'] = "";
		}

		$workers_compensation = $company_detail['has_insurance_workers_compensation'];
		$data['workers_compensation'] = $workers_compensation;
		if($workers_compensation == 1){
			$data['wc_start_date'] = $company_detail['workers_compensation_start_date'];
			$data['wc_expiration'] = $company_detail['workers_compensation_expiration'];
		}else{
			$data['wc_start_date'] = "";
			$data['wc_expiration'] = "";
		}

		$income_protection = $company_detail['has_insurance_income_protection'];
		$data['income_protection'] = $income_protection;
		if($workers_compensation == 1){
			$data['ip_start_date'] = $company_detail['income_protection_start_date'];
			$data['ip_expiration'] = $company_detail['income_protection_expiration'];
		}else{
			$data['ip_start_date'] = "";
			$data['ip_expiration'] = "";
		}
		//INSURANCE

		$data['main_content'] = 'company_view';
		$data['screen'] = 'Company Detail';


		$bank_account_details_q = $this->company_m->fetch_bank_account_details($company_detail['bank_account_id']);			
		$bank_account_details = array_shift($bank_account_details_q->result_array());

		$data['bank_account_id'] = $bank_account_details['bank_account_id'];
		$data['bank_account_name'] = $bank_account_details['bank_account_name'];
		$data['bank_name'] = $bank_account_details['bank_name'];
		$data['bank_account_number'] = $bank_account_details['bank_account_number'];
		$data['bank_bsb_number'] = $bank_account_details['bank_bsb_number'];


		$query_address= $this->company_m->fetch_complete_detail_address($company_detail['address_id']);
		$temp_data = array_shift($query_address->result_array());
		$data['postcode'] = $temp_data['postcode'];
		$data['suburb'] = $temp_data['suburb'];
		$data['po_box'] = $temp_data['po_box'];
		$data['street'] = ucwords(strtolower($temp_data['street']));
		$data['unit_level'] = ucwords(strtolower($temp_data['unit_level']));
		$data['unit_number'] = $temp_data['unit_number'];
		$data['state'] = $temp_data['name'];
		$data['address_id'] = $company_detail['address_id'];

		$data['shortname'] = $temp_data['shortname'];
		$data['state_id'] =  $temp_data['state_id'];
		$data['phone_area_code'] = $temp_data['phone_area_code'];	

		$p_query_address = $this->company_m->fetch_complete_detail_address($company_detail['postal_address_id']);
		$p_temp_data = array_shift($p_query_address->result_array());
		$data['p_po_box'] = $p_temp_data['po_box'];
		$data['p_unit_level'] = ucwords(strtolower($p_temp_data['unit_level']));
		$data['p_unit_number'] = $p_temp_data['unit_number'];
		$data['p_street'] = ucwords(strtolower($p_temp_data['street']));
		$data['p_suburb'] = $p_temp_data['suburb'];
		$data['p_state'] = $p_temp_data['name'];
		$data['p_postcode'] = $p_temp_data['postcode'];
		$data['postal_address_id'] = $company_detail['postal_address_id'];

		$data['p_shortname'] = $p_temp_data['shortname'];
		$data['p_state_id'] =  $p_temp_data['state_id'];
		$data['p_phone_area_code'] = $p_temp_data['phone_area_code'];


		$parent_company_detail_q = $this->company_m->fetch_all_company($company_detail['parent_company_id']); //parent company		
		$parent_company_detail = array_shift($parent_company_detail_q->result_array());
		$data['parent_company_name'] = $parent_company_detail['company_name'];
		$data['parent_company_id'] = $company_detail['parent_company_id'];


		if($company_detail['company_type_id'] == 1){
			$company_type = 'Client';
		}else if($company_detail['company_type_id'] == 2){
			$company_type = 'Contractor';
		}else if($company_detail['company_type_id'] == 3){
			$company_type = 'Supplier';
		}else{}		

		$data['company_type'] = $company_type;
		$data['company_type_id'] = $company_detail['company_type_id'];

		$company_activity = $this->company_m->fetch_company_activity_name_by_type($company_type,$company_detail['activity_id']);
		$data['company_activity'] = $company_activity;
		$data['company_activity_id'] = $company_detail['activity_id'];



		$contact_person_company_q = $this->company_m->fetch_contact_person_company($company_detail['company_id']); //parent company		
		$contact_person_company = $contact_person_company_q->result_array();
		$data['contact_person_company'] = $contact_person_company;

		$data['notes_id'] = $company_detail['notes_id'];
		$query_notes = $this->company_m->fetch_notes($data['notes_id']);
		$temp_data = array_shift($query_notes->result_array());
		$data['comments'] = $temp_data['comments'];


		$this->load->view('page', $data);

		
	}

	public function clear_apost(){
		foreach ($_POST as $key => $value) {
			$_POST[$key] = str_replace("'","&apos;",$value);
		}
	}
	
	public function add(){
		$this->users->_check_user_access('company',2);
		$data['main_content'] = 'company_add';
		$data['add'] = 1;
		$data['addIsSet'] = false;

		$this->form_validation->set_rules('company_name', 'Company Name','trim|required|xss_clean');
		$this->form_validation->set_rules('unit_level', 'Physical Unit/Level', 'trim|xss_clean');
		$this->form_validation->set_rules('unit_number', 'Physical Number', 'trim|xss_clean');
		$this->form_validation->set_rules('street', 'Physical Street', 'trim|required|xss_clean');
		$this->form_validation->set_rules('suburb_a', 'Physical Suburb', 'trim|required|xss_clean');
		$this->form_validation->set_rules('state_a', 'Physical State', 'trim|required|xss_clean');
		$this->form_validation->set_rules('postcode_a', 'Physical Postcode', 'trim|required|xss_clean');
		$this->form_validation->set_rules('pobox', 'PO Box', 'trim|xss_clean');
		$this->form_validation->set_rules('unit_level_b', 'Postal Unit/Level', 'trim|xss_clean');
		$this->form_validation->set_rules('number_b', 'Postal Number', 'trim|xss_clean');
		$this->form_validation->set_rules('street_b', 'Postal Street', 'trim|xss_clean');
		$this->form_validation->set_rules('suburb_b', 'Postal Suburb', 'trim|required|xss_clean');
		$this->form_validation->set_rules('state_b', 'Postal State', 'trim|required|xss_clean');
		$this->form_validation->set_rules('postcode_b', 'Postal Postcode', 'trim|required|xss_clean');
		$this->form_validation->set_rules('abn', 'ABN', 'trim|required|xss_clean');
		$this->form_validation->set_rules('acn', 'ACN', 'trim|required|xss_clean');
		$this->form_validation->set_rules('activity', 'Activity', 'trim|required|xss_clean');
		$this->form_validation->set_rules('parent', 'Parent Company', 'trim|xss_clean');
		$this->form_validation->set_rules('comments', 'Comments', 'trim|xss_clean');
		$this->form_validation->set_rules('type', 'Company Type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('areacode', 'Phone Areacode', 'trim|required|xss_clean');


		if(isset($_POST["type"]) && $_POST["type"]!=''){
			$this->form_validation->set_rules('account-name', 'Account Name', 'trim|xss_clean');
			$this->form_validation->set_rules('bank-name', 'Bank Name', 'trim|xss_clean');
			$this->form_validation->set_rules('account-number', 'Account Number', 'trim|xss_clean');
			$this->form_validation->set_rules('bsb-number', 'BSB Number', 'trim|xss_clean');
		}else{
			$this->form_validation->set_rules('account-name', 'Account Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('bank-name', 'Bank Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('account-number', 'Account Number', 'trim|required|xss_clean');
			$this->form_validation->set_rules('bsb-number', 'BSB Number', 'trim|required|xss_clean');
		}


		if(isset($_POST["add-contact-impt"]) && $_POST["add-contact-impt"]!=''){
			$add_contact_impt_raw = $_POST["add-contact-impt"];
			$add_contact_impt = explode(',', $add_contact_impt_raw);
			$add_contact_impt = array_filter($add_contact_impt);


			foreach ($add_contact_impt as $key => $value) {
				$this->form_validation->set_rules('contact_f_name_'.$value, 'Contact First Name', 'trim|required|xss_clean');
				$this->form_validation->set_rules('contact_l_name_'.$value, 'Contact Last Name', 'trim|required|xss_clean');
				$this->form_validation->set_rules('contact_gender_'.$value, 'Contact Gender', 'trim|required|xss_clean');
				$this->form_validation->set_rules('contact_email_'.$value, 'Contact Email', 'trim|required|xss_clean');


				if($_POST['contact_number_'.$value] == ''){
					if($_POST['mobile_number_'.$value] == '' ){
						$this->form_validation->set_rules('mobile_number_'.$value,'Mobile Number', 'trim|required|xss_clean');
					}else{
						$this->form_validation->set_rules('mobile_number_'.$value,'Mobile Number', 'trim|xss_clean');
					}
				}

				if($_POST['mobile_number_'.$value] == ''){
					if($_POST['contact_number_'.$value] == '' ){
						$this->form_validation->set_rules('contact_number_'.$value,'Office Contact Number', 'trim|required|xss_clean');
					}else{
						$this->form_validation->set_rules('contact_number_'.$value,'Office Contact Number', 'trim|xss_clean');
					}
				}

				$this->form_validation->set_rules('after_hours_'.$value,'After Hours Contact', 'trim|xss_clean');
				$this->form_validation->set_rules('contact_type_'.$value,'Contact Type', 'trim|required|xss_clean');

			}
		}



		
		//echo validation_errors();
		if($this->form_validation->run() === false){
			$this->clear_apost();
			$data['error' ] = validation_errors();
			
			$suburb_list = $this->company_m->fetch_all_suburb();		
			$comp_type_list = $this->company_m->fetch_all_company_types();
			$all_company_list = $this->company_m->fetch_all_company(NULL);
			$all_aud_states = $this->company_m->fetch_all_states();
			
			if($all_company_list->num_rows > 0){
				$data['all_company_list'] = $all_company_list->result();
			}else{
				$data['all_company_list'] = '';
			}
			
			$data['suburb_list'] = $suburb_list->result();
			$data['comp_type_list'] = $comp_type_list->result();
			$data['all_aud_states'] = $all_aud_states->result();




			$this->load->view('page', $data);

			

			//print_r($_POST);
			


			
		}else{
			$this->clear_apost();
			$data['company_name'] = $this->cap_first_word($this->if_set($this->input->post('company_name', true)));

			$data['unit_level'] = $this->if_set($this->input->post('unit_level', true));
			$data['unit_number'] = $this->if_set($this->input->post('unit_number', true));
			$data['street'] = $this->cap_first_word($this->if_set($this->input->post('street', true)));
			$data['postcode_a'] = $this->if_set($this->input->post('postcode_a', true));

			$data['pobox'] = $this->if_set($this->input->post('pobox', true));
			$data['unit_level_b'] = $this->if_set($this->input->post('unit_level_b', true));
			$data['number_b'] = $this->if_set($this->input->post('number_b', true));			
			$data['street_b'] = $this->cap_first_word($this->if_set($this->input->post('street_b', true)));
			$data['postcode_b'] = $this->if_set($this->input->post('postcode_b', true));

			$data['abn'] = $this->if_set($this->input->post('abn', true));
			$data['acn'] = $this->if_set($this->input->post('acn', true));
			$data['activity'] = $this->cap_first_word($this->if_set($this->input->post('activity', true)));

			$data['parent'] = $this->if_set($this->input->post('parent', true));


			$data['account-name'] = $this->cap_first_word($this->if_set($this->input->post('account-name',true)));
			$data['bank-name'] = $this->cap_first_word($this->if_set($this->input->post('bank-name',true)));
			$data['account-number'] = $this->cap_first_word($this->if_set($this->input->post('account-number',true)));
			$data['bsb-number'] = $this->cap_first_word($this->if_set($this->input->post('bsb-number',true)));


			$data['areacode'] = $this->if_set($this->input->post('areacode', true));

			$data['comments'] = $this->cap_first_word_sentence($this->if_set($this->input->post('comments', true)));
			
			$state_a_arr = explode('|', $this->input->post('state_a', true));
			$data['state_a'] = $state_a_arr[3];

			$suburb_a_ar = explode('|',$this->if_set($this->input->post('suburb_a', true)));
			$data['suburb_a'] = strtoupper($suburb_a_ar[0]);

			$state_b_arr = explode('|', $this->input->post('state_b', true));
			$data['state_b'] = $state_b_arr[3];

			$suburb_b_ar = explode('|',$this->if_set($this->input->post('suburb_b', true)));
			$data['suburb_b'] = strtoupper($suburb_b_ar[0]);



			$type_arr = explode('|', $this->input->post('type', true));

			if($type_arr[1] != ''){
				$data['company_type'] = $type_arr[1];
			}else{
				$data['company_type'] = 0;
			}


			//print_r($_POST);
			//var_dump($data);



			if($data['comments']!=''){
				$company_notes_id = $this->company_m->insert_notes($data['comments']);
			}else{
				$company_notes_id = 0;
			}
			


			$activity_id_arr = explode('|',$data['activity']);




			$general_address_id_result_a = $this->company_m->fetch_address_general_by('postcode-suburb',$data['postcode_a'],$data['suburb_a']);
			foreach ($general_address_id_result_a->result() as $general_address_id_a){
				$general_address_a = $general_address_id_a->general_address_id;
			}


			$general_address_id_result_b = $this->company_m->fetch_address_general_by('postcode-suburb',$data['postcode_b'],$data['suburb_b']);
			foreach ($general_address_id_result_b->result() as $general_address_id_b){
				$general_address_b = $general_address_id_b->general_address_id;
			}


			if($data['company_type']>1 ){
				$bank_account_id = $this->company_m->insert_bank_account($data['account-name'],$data['account-number'],$data['bank-name'],$data['bsb-number']);
			}else{
				$bank_account_id = 0;
			}


			$address_id = $this->company_m->insert_address_detail($data['street'],$general_address_a,$data['unit_level'],$data['unit_number']);

			$postal_address_id = $this->company_m->insert_address_detail($data['street_b'],$general_address_b,$data['unit_level_b'],$data['number_b'],$data['pobox']);


			$parent_arr_id = explode('|',$data['parent']);

			if($parent_arr_id[1]!=''){
				$parent_set_id = $parent_arr_id[1];
			}else{
				$parent_set_id = 0;
			}


			$new_company_id = $this->company_m->insert_company_details($data['company_name'],$data['abn'],$data['acn'] ,$activity_id_arr[1],$address_id,$postal_address_id,$data['company_type'],$bank_account_id,$company_notes_id,$parent_set_id);





			if(isset($_POST["add-contact-impt"]) && $_POST["add-contact-impt"]!=''){
				$add_contact_impt_raw = $_POST["add-contact-impt"];
				$add_contact_impt = explode(',', $add_contact_impt_raw);
				$add_contact_impt = array_filter($add_contact_impt);

				$data['add-contact-impt'] = $add_contact_impt;

				foreach ($add_contact_impt as $key => $value) {

					

					$assigned_contact_f_name = $this->cap_first_word($this->if_set($this->input->post('contact_f_name_'.$value,true)));   
					$assigned_contact_l_name = $this->cap_first_word($this->if_set($this->input->post('contact_l_name_'.$value,true)));   
					$assigned_contact_gender = $this->cap_first_word($this->if_set($this->input->post('contact_gender_'.$value,true)));   
					$assigned_contact_email = $this->if_set($this->input->post('contact_email_'.$value,true));   
					$assigned_contact_number = $this->cap_first_word($this->if_set($this->input->post('contact_number_'.$value,true))); 

					$assigned_contact_mobile_number = $this->cap_first_word($this->if_set($this->input->post('mobile_number_'.$value,true))); 
					$assigned_contact_after_hours = $this->cap_first_word($this->if_set($this->input->post('after_hours_'.$value,true))); 

					$assigned_contact_type = $this->cap_first_word($this->if_set($this->input->post('contact_type_'.$value,true)));

					if($this->input->post('set_as_primary_'.$value,true) == 'on'){
						$is_primary = 1;
					}else{
						$is_primary = 0;
					}


					$contact_number_id = $this->company_m->insert_contact_number($data['areacode'],$assigned_contact_number,'',$assigned_contact_mobile_number,$assigned_contact_after_hours);

					if($assigned_contact_email!=''){
						$email_id = $this->company_m->insert_email($assigned_contact_email);
					}else{
						$email_id = 0;
					}

					$contact_person_id = $this->company_m->insert_contact_person($assigned_contact_f_name,$assigned_contact_l_name,$assigned_contact_gender,$email_id,$contact_number_id);
					$this->company_m->insert_contact_person_company($new_company_id,$contact_person_id,$assigned_contact_type,$is_primary);

				}
			}





			$this->session->set_flashdata('new_company_id', $new_company_id);
			$this->session->set_flashdata('new_company_msg', 'New company is now added');
			redirect('/company');

			//redirect('/company/view/'.$new_company_insert_id);
		}
	}

	public function add_contact(){
		//var_dump($_POST);
		
		$data['contact_first_name'] = $this->input->post('first_name');
		$data['contact_last_name'] = $this->input->post('last_name');
		$data['contact_gender'] = $this->input->post('gender');
		$data['contact_email'] = $this->input->post('contact_email');
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

      public function add_new_contact_dynamic(){
      	$data_val = explode('|',$_POST['ajax_var']);

      	$contact_number_id = $this->company_m->insert_contact_number($data_val[8],$data_val[4],'',$data_val[6],$data_val[5]);
      	$email_id = $this->company_m->insert_email($data_val[7]);
		$contact_person_id = $this->company_m->insert_contact_person($data_val[0],$data_val[1],$data_val[2],$email_id,$contact_number_id);
		$this->company_m->insert_contact_person_company($data_val[9],$contact_person_id,$data_val[3],'0');


      }
 



	// this was for the admin company ajax edit func
	public function update_abn_acn_jurisdiction(){
		$this->clear_apost();
		$data_val = explode('_',$_POST['ajax_var']);

		$jurisdiction_raw = explode(',',$data_val[2]);
		$jurisdiction = '';
		foreach ($jurisdiction_raw as $key => $value) {
			$jur_arr = explode('|', $value);
			$jurisdiction .= $jur_arr[3].',';
		}
		$jurisdiction = substr($jurisdiction, 0, -1);

		$this->admin_m->update_abn_acn_jurisdiction($data_val[0],$data_val[1],$jurisdiction,$data_val[3]);		
	}


	public function updat_admin_contact_email(){
		$data_val = explode('|',$_POST['ajax_var']);
		$this->admin_m->updat_admin_contact_email($data_val[0],$data_val[1],$data_val[2],$data_val[3],$data_val[4]);	
	}

	// this was for the admin company ajax edit func


	

	public function delete_person_contact(){
		$data_val = explode('|',$_POST['ajax_var']);
		$this->company_m->delete_contact_person($data_val[0]);
	}

	public function delete_company(){
		$data_val = explode('|',$_POST['ajax_var']);
		$this->company_m->delete_company($data_val[0]);
		redirect('/company');
	}

	public function update_name_company(){
		$this->clear_apost();
		$data_val = explode('|',$_POST['ajax_var']);
		$this->company_m->update_company_name($data_val[0],$data_val[1]);
	}

	public function update_details_address(){
		$this->clear_apost();
		$data_val = explode('|',$_POST['ajax_var']);
		$this->company_m->update_address_details($data_val[0],$data_val[1],$data_val[2],$data_val[3],$data_val[4],$data_val[5],$data_val[6]);
	}

	public function update_bank_details_account(){
		$this->clear_apost();
		$data_val = explode('|',$_POST['ajax_var']);
		$this->company_m->update_bank_account_details($data_val[0],$data_val[1],$data_val[2],$data_val[3],$data_val[4]);
	}

	public function update_details_other(){
		$this->clear_apost();
		$data_val = explode('|',$_POST['ajax_var']);
		$this->company_m->update_other_details($data_val[0],$data_val[1],$data_val[2],$data_val[3],$data_val[4],$data_val[5]);
	}

	public function update_comments_notes(){
		$this->clear_apost();
		$data_val = explode('|',$_POST['ajax_var']);
		$this->company_m->update_notes_comments($data_val[0],$data_val[1]);
	}


	public function update_person_contact(){
		$this->clear_apost();
		$data_val = explode('|',$_POST['ajax_var']);
		$this->company_m->update_contact_person($data_val[0],$data_val[1],$data_val[2],$data_val[3],$data_val[4],$data_val[5],$data_val[6],$data_val[7],$data_val[8],$data_val[9],$data_val[10],$data_val[11]);
	}

	public function update_contact_primary(){
		$this->clear_apost();
		$data_val = explode('|',$_POST['ajax_var']);
		$this->company_m->update_primary_contact($data_val[0],$data_val[1],$data_val[2]);
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

	public function get_suburb_list($data_post=''){
		if(isset( $_POST['ajax_var'] ) && $_POST['ajax_var']!=''){
			$data_raw = explode('|',$this->security->xss_clean($this->input->post('ajax_var')));
			$state_id = $data_raw[0];
			$wrap = $data_raw[1];
			$selector = $data_raw[2];
			$state = $data_raw[3];
			$phone_area_code = $data_raw[4];
		}else{
			$data_raw = explode('|',$this->security->xss_clean($data_post));
			$state_id = $data_raw[5];
			$wrap = $data_raw[0];
			$selector = $data_raw[1];
			$state = $data_raw[3];
			$phone_area_code = $data_raw[4];
		}
		
		$suburb_list = $this->company_m->fetch_address_general_by($selector,$state_id);
		
		if($wrap == 'dropdown'){

				echo '<option value="">Choose a Suburb...</option>';
			foreach ($suburb_list->result() as $row){

				echo '<option value="'.$row->suburb.'|'.$state.'|'.$phone_area_code.'">'.ucwords(strtolower($row->suburb)).'</option>';
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

	public function get_post_code_list($data_post=''){

		if(isset( $_POST['ajax_var'] ) && $_POST['ajax_var']!=''){
			$data_raw = $this->security->xss_clean($this->input->post('ajax_var'));
			$suburb_value =  $data_raw;
		}else{
			$suburb_value =  $data_post;
		}

		$post_code_list = $this->company_m->fetch_postcode($suburb_value);

		echo '<option value="">Choose a Postcode...</option>';
		

		foreach ($post_code_list->result() as $row){
			echo '<option value="'.$row->postcode.'">'.ucwords(strtolower($row->postcode)).'</option>';
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
	
	public function activity($parent_type = ''){

		if($parent_type!=''){
			$activity_value = $parent_type;
		}else{
			$activity_value = $this->security->xss_clean($this->input->post('ajax_var'));
		}

		

		if($activity_value=='Client'){
			$query = $this->company_m->fetch_all_client_types();
			//var_dump($query);	
			//$all_client_list = '';	
			//$counter = 0;
			//echo '<option value="">Choose a Activity...</option>';
			foreach ($query->result() as $row){
				echo '<option value="'.ucwords(strtolower($row->client_category_name)).'|'.$row->client_category_id.'">'.ucwords(strtolower($row->client_category_name)).'</option>';				
			}
		}else if($activity_value=='Contractor'){
			$query = $this->company_m->fetch_all_contractor_types();
			//echo '<option value="">Choose a Activity...</option>';
			foreach ($query->result() as $row){
				echo '<option value="'.ucwords(strtolower($row->job_category)).'|'.$row->job_category_id.'">'.ucwords(strtolower($row->job_category)).'</option>';				
			}
		}else if($activity_value=='Supplier'){
			$query = $this->company_m->fetch_all_supplier_types();
			//echo '<option value="">Choose a Activity...</option>';
			foreach ($query->result() as $row){
				echo '<option value="'.ucwords(strtolower($row->supplier_cat_name)).'|'.$row->supplier_cat_id.'">'.ucwords(strtolower($row->supplier_cat_name)).'</option>';				
			}
		}else{
			//echo '<option value="">Choose a Activity...</option>';
		}
	}




	public function company_by_type($type_id=''){
		if($type_id!=''){
			$type_value = $type_id;
		}else{
			$type_value = $this->security->xss_clean($this->input->post('ajax_var'));
		}

		$query = $this->company_m->fetch_all_company_type_id($type_value);
		//var_dump($query);	
		//$all_client_list = '';	
		//$counter = 0;
		echo '<option value="">Choose a Parent...</option>';
		foreach ($query->result() as $row){
			echo '<option value="'.ucwords(strtolower($row->company_name)).'|'.$row->company_id.'">'.ucwords(strtolower($row->company_name)).'</option>';				
		}
		

	}
	public function works_company_by_type($type_id=''){
		if($type_id!=''){
			$type_value = $type_id;
		}else{
			$type_value = $this->security->xss_clean($this->input->post('ajax_var'));
		}

		$query = $this->company_m->fetch_all_company_type_id($type_value);
		//var_dump($query);	
		//$all_client_list = '';	
		//$counter = 0;
		foreach ($query->result() as $row){
			echo '<option value="'.ucwords(strtolower($row->company_name)).'|'.$row->company_id.'">'.ucwords(strtolower($row->company_name)).'</option>';				
		}
		

	}

	// public function upload_insurance(){
	// 	$comp_id = $_POST['company_id'];
	// 	$insurance_type = $_POST['insurance_type'];
	// 	$expiration_date = $_POST['attach_expiration'];



	//     if($insurance_type == 1){
	//     	$filename = $comp_id."_Public_Liability" ;
	//     }else{
	//     	if($insurance_type == 2){
	//     		$filename = $comp_id."_Workers_Compesation" ;
	//     	}else{
	//     		$filename = $comp_id."_Income Protection" ;
	//     	}
	//     }


	// 	$config['upload_path'] = './uploads/company/insurance';
	// 	$config['allowed_types'] = 'gif|jpg|png';
	// 	$config['max_size']	= '1024';
	// 	$config['max_width']  = '1024';
	// 	$config['max_height']  = '768';

	// 	$config['file_name']  = $filename;

	// 	$this->upload->initialize($config);

	// 	$this->load->library('upload', $config);
	// 	$this->upload->initialize($config);	



	
	// 		if ( ! $this->upload->do_upload()){
	// 			$upload_error = array('error' => $this->upload->display_errors());
	// 			$upload_has_error = 1;
	// 		}else{
	// 			
	// 			redirect('/company/view/'.$comp_id);
	// 			// $up_data = array('upload_data' => $this->upload->data());
	// 			// $logo = $up_data['upload_data']['file_name'];
	// 		}
	 
	// }

	function upload_insurance()
	{
		$comp_id = $_POST['company_id'];
		$insurance_type = $_POST['insurance_type'];
		$expiration_date = $_POST['attach_expiration'];

	    $this->load->library('upload');

	    $files = $_FILES;
	    $cpt = count($_FILES['userfile']['name']);
	    for($i=0; $i<$cpt; $i++)
	    {
	    	$file_name =  $files['userfile']['name'][$i];
	    	$file_name = str_replace(' ', '_', $file_name);
	    	//$proj_attach_q = $this->attachments_m->display_selected_project_attachments($project_id);
	    	$file = explode('.', $file_name);
	    	$filename = $file[0];
	    	$extension = $file[1];
	    	$file_exist = 0;
	    	
		    if (strpos($filename) !== false) {
		    		$file_exist = $file_exist + 1;
		    }
	    	//}

	    	if($file_exist > 0){
	    		$filename = $filename.$file_exist;
	    		$file_name = $filename.".".$extension;
	    	}

	    	if($insurance_type == 1){
		    	$filename = $comp_id."_Public_Liability" ;
		    }else{
		    	if($insurance_type == 2){
		    		$filename = $comp_id."_Workers_Compensation" ;
		    	}else{
		    		$filename = $comp_id."_Income Protection" ;
		    	}
		    }

		    $_FILES['userfile']['name']= $filename.'.'.$extension;
		    $_FILES['userfile']['type']= $files['userfile']['type'][$i];
		    $_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
		    $_FILES['userfile']['error']= $files['userfile']['error'][$i];
		    $_FILES['userfile']['size']= $files['userfile']['size'][$i];    

		    $this->upload->initialize($this->set_upload_options($comp_id));
		    $this->upload->do_upload();
	    	$this->company_m->update_company_details_insurance($comp_id,$insurance_type,$expiration_date);
	    }
	   	redirect('/company/view/'.$comp_id);
	}
	private function set_upload_options($comp_id)
	{   
	//  upload an image options
		$path = "./uploads/company/insurance/".$comp_id;
		mkdir($path, 0755, true);
	    $config = array();
	    $config['upload_path'] = $path."/";
	    $config['allowed_types'] = 'pdf';
	    $config['max_size']      = '0';
	    $config['overwrite']     = TRUE;


	    return $config;
	}

	public function update_insurance_exp_date(){
		$comp_id = $_POST['comp_id'];
		$insurance_type = $_POST['insurance_type'];
		$expiration_date = $_POST['expiration'];
		$this->company_m->update_company_details_insurance($comp_id,$insurance_type,$expiration_date);
	}

	public function filter_contractor_list(){
		$filter = $_POST['filter'];
		$company_q = $this->company_m->display_company_by_type(2);
		$today = date('d/m/Y');
		switch($filter){
			case 1:	
				echo '<table class = "table table-striped table-bordered"><th>Contractor Name</th><th>Public Liability</th><th>Workers Compensation</th><th>Income Protection</th>';
				foreach ($company_q->result_array() as $row){
					// $expired = 0;
					// if($row['public_liability_expiration'] !== ""){
					// 	if($row['public_liability_expiration'] > $today){
					// 		$expired = 1;
					// 	}
					// }
					// if($row['workers_compensation_expiration'] !== ""){
					// 	if($row['workers_compensation_expiration'] <= $today){
					// 		$expired = 1;
					// 	}
					// }
					// if($row['income_protection_expiration'] !== ""){
					// 	if($row['income_protection_expiration'] <= $today){
					// 		$expired = 1;
					// 	}
					// }
					// if($expired == 1){
					// 	$comp_expired++;
					// }
					
					if($row['has_insurance_public_liability'] == 1){
						if($row['has_insurance_workers_compensation'] == 1){
							echo '<tr><td colspan =>'.$row['company_name'].'</td><td>'.$row['public_liability_expiration'].'</td><td>'.$row['workers_compensation_expiration'].'</td><td>No File</td></tr>';
						}else{
							if($row['has_insurance_income_protection'] == 1){
								echo '<tr><td colspan =>'.$row['company_name'].'</td><td>'.$row['public_liability_expiration'].'</td><td>No File</td><td>'.$row['income_protection_expiration'].'</td></tr>';
							}
						}
					}
				}
				echo '</table>';
				break;
			case 2:
				echo '<table class = "table table-striped table-bordered"><th>Contractor Name</th><th>Public Liability</th><th>Workers Compensation</th><th>Income Protection</th>';
				foreach ($company_q->result_array() as $row){
					// $expired = 0;
					// if($row['public_liability_expiration'] !== ""){
					// 	if($row['public_liability_expiration'] > $today){
					// 		$expired = 1;
					// 	}
					// }
					// if($row['workers_compensation_expiration'] !== ""){
					// 	if($row['workers_compensation_expiration'] <= $today){
					// 		$expired = 1;
					// 	}
					// }
					// if($row['income_protection_expiration'] !== ""){
					// 	if($row['income_protection_expiration'] <= $today){
					// 		$expired = 1;
					// 	}
					// }
					// if($expired == 1){
					// 	$comp_expired++;
					// }
					
					if($row['has_insurance_public_liability'] == 1){
						if($row['has_insurance_workers_compensation'] == 0){
							if($row['has_insurance_income_protection'] == 0){
								echo '<tr><td colspan =>'.$row['company_name'].'</td><td>'.$row['public_liability_expiration'].'</td><td>No File</td><td>No File</td></tr>';
							}
						}
					}else{
						if($row['has_insurance_workers_compensation'] == 1){
							echo '<tr><td colspan =>'.$row['company_name'].'</td><td>No File</td><td>'.$row['workers_compensation_expiration'].'</td><td>No File</td></tr>';
						}else{
							if($row['has_insurance_income_protection'] == 1){
								echo '<tr><td colspan =>'.$row['company_name'].'</td><td>No File</td><td>No File</td><td>'.$row['income_protection_expiration'].'</td></tr>';
							}
						}
					}
				}
				echo '</table>';
				break;
			case 3:
				echo '<table class = "table table-striped table-bordered"><th>Contractor Name</th><th>Public Liability</th><th>Workers Compensation</th><th>Income Protection</th>';
				foreach ($company_q->result_array() as $row){
					// $expired = 0;
					// if($row['public_liability_expiration'] !== ""){
					// 	if($row['public_liability_expiration'] > $today){
					// 		$expired = 1;
					// 	}
					// }
					// if($row['workers_compensation_expiration'] !== ""){
					// 	if($row['workers_compensation_expiration'] <= $today){
					// 		$expired = 1;
					// 	}
					// }
					// if($row['income_protection_expiration'] !== ""){
					// 	if($row['income_protection_expiration'] <= $today){
					// 		$expired = 1;
					// 	}
					// }
					// if($expired == 1){
					// 	$comp_expired++;
					// }
					
					if($row['has_insurance_public_liability'] == 0){
						if($row['has_insurance_workers_compensation'] == 0){
							if($row['has_insurance_income_protection'] == 0){
								echo '<tr><td colspan =>'.$row['company_name'].'</td><td>No File</td><td>No File</td><td>No File</td></tr>';
							}
						}
					}
				}
				echo '</table>';
				break;
			case 4:
				echo '<table class = "table table-striped table-bordered"><th>Contractor Name</th><th>Public Liability</th><th>Workers Compensation</th><th>Income Protection</th>';
				foreach ($company_q->result_array() as $row){
					$expired = 0;
					$pl_expiration = $row['public_liability_expiration'];
					$wc_expiration = $row['workers_compensation_expiration'];
					$ip_expiration = $row['income_protection_expiration'];

					$color1 = "";
					$color2 = "";
					$color3 = "";
					if($row['public_liability_expiration'] !== ""){
						if($row['public_liability_expiration'] > $today){
							$expired = 1;
							$color1 = "Red";
						}
					}
					if($row['workers_compensation_expiration'] !== ""){
						if($row['workers_compensation_expiration'] <= $today){
							$expired = 1;
							$color2 = "Red";
						}
					}
					if($row['income_protection_expiration'] !== ""){
						if($row['income_protection_expiration'] <= $today){
							$expired = 1;
							$color3 = "Red";
						}
					}
					if($expired == 1){
						echo '<tr><td colspan =>'.$row['company_name'].'</td><td style = "color: '.$color1.'">'.$pl_expiration.'</td><td style = "color: '.$color2.'">'.$wc_expiration.'</td><td style = "color: '.$color3.'">'.$ip_expiration.'</td></tr>';
					}
					
					// if($row['has_insurance_public_liability'] == 0){
					// 	if($row['has_insurance_workers_compensation'] == 0){
					// 		if($row['has_insurance_income_protection'] == 0){
								
					// 		}
					// 	}
					// }
				}
				echo '</table>';
				break;
		}
	}
}