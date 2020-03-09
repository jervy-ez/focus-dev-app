<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Company extends MY_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');		
		$this->load->module('users'); 	
		$this->load->model('company_m');
		if(!$this->users->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;
	}
	
	public function index(){				
		$data['main_content'] = 'company_v';
		$data['new_compamy_id'] = $this->session->userdata('item');
		$data['comp_type'] = 1;
		$data['screen'] = 'Client';
		$this->load->view('page', $data);
	}	
	
	public function contractor(){				
		$data['main_content'] = 'company_v';
		$data['new_compamy_id'] = $this->session->userdata('item');
		$data['comp_type'] = 2;
		$data['screen'] = 'Contractor';
		$this->load->view('page', $data);
	}
	
	public function supplier(){				
		$data['main_content'] = 'company_v';
		$data['new_compamy_id'] = $this->session->userdata('item');
		$data['comp_type'] = 3;
		$data['screen'] = 'Supplier';
		$this->load->view('page', $data);
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
	
	public function add(){
		$data['main_content'] = 'company_add';
		$data['add'] = 1;
		$data['addIsSet'] = false;

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
			//valid_input_simple
			
			//var_dump($_POST);
		}else{
			//print_r($_POST);
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
			//var_dump($_POST);
			$new_company_insert_id = $this->company_m->insert_new_company($data);
			$this->session->set_flashdata('new_company_id', $new_company_insert_id);
			redirect('/company/view/'.$new_company_insert_id);
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
}