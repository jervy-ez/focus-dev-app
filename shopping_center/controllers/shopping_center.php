<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Shopping_center extends MY_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->module('users');
		$this->load->model('shopping_center_m');
		$this->load->module('company');
		$this->load->module('works');
		$this->load->model('works_m');
		if(!$this->users->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;
	}
	

	public function index(){		
		$this->users->_check_user_access('company',1);
		$suburb_list = $this->company_m->fetch_all_suburb();		
		$all_aud_states = $this->company_m->fetch_all_states();		

		$data['suburb_list'] = $suburb_list->result();
		$data['all_aud_states'] = $all_aud_states->result();

		$data['main_content'] = 'shopping_center_home';
		$data['screen'] = 'Shopping Center';

		$this->form_validation->set_rules('brand', 'Brand/Shopping Center','trim|required|xss_clean');
		$this->form_validation->set_rules('street_number', 'Street Number', 'trim|xss_clean');
		$this->form_validation->set_rules('street', 'Street', 'trim|required|xss_clean');
		$this->form_validation->set_rules('suburb_a', 'Suburb', 'trim|required|xss_clean');
		$this->form_validation->set_rules('state_a', 'State', 'trim|required|xss_clean');
		$this->form_validation->set_rules('postcode_a', 'Postcode', 'trim|required|xss_clean');


		if($this->form_validation->run() === false){
			$data['error' ] = validation_errors();
			$this->load->view('page', $data);
		}else{
			$is_submit = $this->company->if_set($this->input->post('is_submit', true));

			$brand = $this->company->if_set($this->input->post('brand', true));
			$street_number = $this->company->if_set($this->input->post('street_number', true));
			$street = $this->company->if_set($this->input->post('street', true));

			$state_a_arr = explode('|', $this->input->post('state_a', true));
			$state = $state_a_arr[3];

			$suburb_a_ar = explode('|',$this->company->if_set($this->input->post('suburb_a', true)));
			$suburb = strtoupper($suburb_a_ar[0]);

			$postcode = $this->company->if_set($this->input->post('postcode_a', true));

			$general_address_id_result_a = $this->company_m->fetch_address_general_by('postcode-suburb',$postcode,$suburb);
			foreach ($general_address_id_result_a->result() as $general_address_id_a){
				$general_address_a = $general_address_id_a->general_address_id;
			}

			if($is_submit){
				$address_id = $this->company_m->insert_address_detail($street,$general_address_a,'',$street_number);
				$this->shopping_center_m->insert_new_shopping_center($brand,$address_id);
				$this->session->set_flashdata('success_add', 'New Shopping center is added');
				redirect('/shopping_center');
			}

		}
	}


	public function dynamic_add_shopping_center(){
		$ajax_var_raw = $_POST['ajax_var'];
		$post_data = explode('*', $ajax_var_raw);

		$brand = $post_data[0];
		$street_number = $post_data[1];
		$street = $post_data[2];

		$state_a_arr = explode('|', $post_data[3]);
		$state = $state_a_arr[3];

		$suburb_a_ar = explode('|',$post_data[4]);
		$suburb = strtoupper($suburb_a_ar[0]);

		$postcode = $post_data[5];

		$general_address_id_result_a = $this->company_m->fetch_address_general_by('postcode-suburb',$postcode,$suburb);
		foreach ($general_address_id_result_a->result() as $general_address_id_a){
			$general_address_a = $general_address_id_a->general_address_id;
		}

		$address_id = $this->company_m->insert_address_detail($street,$general_address_a,'',$street_number);
		$this->shopping_center_m->insert_new_shopping_center($brand,$address_id);
	}

	public function display_shopping_center(){
		$all_shopping_center_q = $this->shopping_center_m->fetch_shopping_center_details();
		$all_shopping_center = $all_shopping_center_q->result();
		foreach ($all_shopping_center as $key ) {
			echo '<tr><td><span class="item-link" onClick="toggleShoppingCenterDetails('.$key->shopping_center_id.')">'.$key->shopping_center_brand_name.'</span></td><td>'.$key->street.'</td><td>'.ucfirst(strtolower($key->suburb)).'</td><td>'.$key->name.'</td></tr>';
		}		
	}

	public function get_shopping_center_detail(){
		$shopping_center_id = $_POST['shopping_center_id'];

		$all_shopping_center_q = $this->shopping_center_m->fetch_shopping_center_details($shopping_center_id);
		$shopping_center = array_shift($all_shopping_center_q->result_array());

		echo $shopping_center['shopping_center_brand_name'].'|'.$shopping_center['unit_number'].'|'.$shopping_center['street'].'|'.$shopping_center['postcode'].'|'.$shopping_center['suburb'].'|'.$shopping_center['name'].'|'.$shopping_center['shortname'].'|'.$shopping_center['state_id'].'|'.$shopping_center['phone_area_code'].'|'.$shopping_center['shopping_center_id'].'|'.ucwords(strtolower($shopping_center['suburb']));

	}

	public function update(){


		$brand = $this->company->if_set($this->input->post('edit_brand', true));
		$street_number = $this->company->if_set($this->input->post('edit_street_number', true));
		$street = $this->company->if_set($this->input->post('edit_street', true));

		$state_a_arr = explode('|', $this->input->post('edit_state_a', true));
		$state = $state_a_arr[3];

		$suburb_a_ar = explode('|',$this->company->if_set($this->input->post('edit_suburb_a', true)));
		$suburb = strtoupper($suburb_a_ar[0]);

		$postcode = $this->company->if_set($this->input->post('edit_postcode_b', true));

		$general_address_id_result_a = $this->company_m->fetch_address_general_by('postcode-suburb',$postcode,$suburb);
		foreach ($general_address_id_result_a->result() as $general_address_id_a){
			$general_address_a = $general_address_id_a->general_address_id;
		}
		$shopping_center_id = $this->input->post('shopping_center_id', true);
		$is_submit = $this->company->if_set($this->input->post('is_submit', true));


		if($is_submit){
			$address_id = $this->company_m->insert_address_detail($street,$general_address_a,'',$street_number);
			$this->shopping_center_m->update_shopping_center($shopping_center_id,$brand,$address_id);
			$this->session->set_flashdata('success_add', 'Shopping center info is updated');
			redirect('/shopping_center');
		}



	}

	public function delete(){
		$shopping_center_id = $this->uri->segment(3);
		$this->shopping_center_m->delete_shopping_center($shopping_center_id);
		$this->session->set_flashdata('success_remove', 'You just removed a shopping center.');
		redirect('/shopping_center');		
	}



	
	
}