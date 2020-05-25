<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Users extends MY_Controller{
	
	function __construct(){
		parent::__construct();	
		$this->load->library('form_validation');
		$this->load->library('upload');
		$this->load->library('session');
		$this->load->helper('cookie');
		$this->load->helper('form');
		$this->load->helper('url');

		$this->load->model('Users_m');	
		$this->load->module('Admin');
		date_default_timezone_set("Australia/Perth");
	}
	
	function index(){
		if(!$this->admin->_is_logged_in() ): 		
	  		redirect('signin', 'refresh');
		endif;

		$q_get_users = $this->Users_m->get_users();
		$data['data_users_list'] = $q_get_users;

		$data['main_content'] = 'user_list';
		$data['added_scripts'] = 'js_file';
		$data['side_bar_primary'] = 'sb_users_list';
		$this->load->view('page', $data);
	}

	public function list_user_roles($view='select'){
		$q_get_user_details = $this->Users_m->list_user_roles();

		switch ($view) {
			case "red":
				echo "Your favorite color is red!";
			break;

			case "blue":
				echo "Your favorite color is blue!";
			break;

			case "green":
				echo "Your favorite color is green!";
			break;

			default:
			foreach ($q_get_user_details->result() as $data):
				echo "<option value=\"".$data->role_id."\" >$data->role_types</option>";
			endforeach;
		}
	}

	public function delete_user($user_id){
		$this->Users_m->delete_user($user_id);
		redirect('Users');
	}

	public function account($useridSet=''){
		if(!$this->admin->_is_logged_in() ): 		
	  		redirect('signin', 'refresh');
		endif;
		
		$data['side_bar_primary'] = ''; // highlight the active nav's page

		if(isset($useridSet) && $useridSet != ''){
			$userid = $useridSet;
		}else{
			$userid = $this->session->userdata('user_id');
			$data['side_bar_primary'] = 'sb_my_profile'; // highlight the active nav's page
		}

		$q_user_access_list = $this->admin->get_user_access($userid);

		$data['user_access'] = array();
		foreach ($q_user_access_list->result() as $access_location){

			$data['user_access'][$access_location->local_name] = array( 
				"access_control_id" => $access_location->access_control_id,  
				"access_area_id" => $access_location->access_area_id,  
				"can_access" => $access_location->can_access, 
				"can_control" => $access_location->can_control, 
				"user_id" => $access_location->user_id, 
				"area_name" => $access_location->area_name, 
				"local_name" => $access_location->local_name
			);
		}

		$q_get_all_access_area = $this->admin->get_all_access_area();
		$data['access_areas'] = $q_get_all_access_area;

		$q_get_user_details = $this->Users_m->get_user_details($userid);
		$r_get_user_details = $q_get_user_details->result_array();
		$user_details = array_shift($r_get_user_details);

		$data['user_details'] = $user_details;

		$data['main_content'] = 'account';
		$data['added_scripts'] = 'js_file';
		$this->load->view('page', $data);
	}

	public function updateUserName(){
		$this->admin->clear_apost();
		$set_user_id = $this->input->post('userId');

		if(!isset($set_user_id) || $set_user_id == ''){
			redirect('Users');
		}

		$this->form_validation->set_rules('userLoginName', 'User Name', 'required|min_length[8]');
		$this->form_validation->set_error_delimiters('<p class="p-0 m-0"> &nbsp;  <em class="fas fa-exclamation-circle"></em> &nbsp;', '</p>');


		if ($this->form_validation->run() == FALSE){
			$str_msgs = strval( validation_errors() );
			$this->session->set_flashdata('form_error_login', $str_msgs);
			redirect('Users/account/'.$set_user_id.'#loginDetails');
		}else{
			$this->Users_m->update_user_name($set_user_id,$this->input->post('userLoginName'));
			$this->session->set_flashdata('form_success_login','Username is now updated. New login details will take effect on next login.');
			redirect('Users/account/'.$set_user_id.'#loginDetails');
		}

	}

	public function updateLoginDetails(){
		$this->admin->clear_apost();
		$set_user_id = $this->input->post('userId');

		if(!isset($set_user_id) || $set_user_id == ''){
			redirect('Users');
		}

		$this->form_validation->set_rules(
			'userPassword', 'Password',
			'required|min_length[8]',
			array(
				'required' 	=> 'You have not provided %s.',
				'is_unique' => 'This %s already exists. Please try another.'
			)
		);

		$this->form_validation->set_rules('userConfirmPassword', 'Password Confirmation', 'required|min_length[8]|matches[userPassword]');
		$this->form_validation->set_error_delimiters('<p class="p-0 m-0"> &nbsp;  <em class="fas fa-exclamation-circle"></em> &nbsp;', '</p>');

	
		$pwd_exp = $this->session->admin_pwrd_life_exp;
		$date_pwd_exp = new DateTime('now');
		$date_pwd_exp->modify('+'.$pwd_exp);
		$date_pwd_exp = $date_pwd_exp->format('d/m/Y');


		if ($this->form_validation->run() == FALSE){
			$str_msgs = strval( validation_errors() );
			$this->session->set_flashdata('form_error_login', $str_msgs);
			redirect('Users/account/'.$set_user_id.'#loginDetails');

		}else{

			$this->Users_m->update_user_pwrd($set_user_id,$date_pwd_exp,$this->input->post('userConfirmPassword'));
			$this->session->set_flashdata('form_success_login','New password details is now updated, this will take effect on next login.');
			redirect('Users/account/'.$set_user_id.'#loginDetails');
		}
	}

	public function add(){
		if(!$this->admin->_is_logged_in() ): 		
	  		redirect('signin', 'refresh');
		endif;

		$data['main_content'] = 'new_user';
		$data['added_scripts'] = 'js_file';
		$this->load->view('page', $data);
	}

	public function updateAccessControl(){
		$this->admin->clear_apost();
		$set_user_id = $this->input->post('userId');

		$q_get_all_access_area = $this->admin->get_all_access_area();
		$access_areas = $q_get_all_access_area;

		foreach ($access_areas->result() as $access_location){

			$ac_id_post = $access_location->local_name.'_ac_id';
			$can_access_post = $access_location->local_name.'_ca';
			$can_control_post = $access_location->local_name.'_cc';

			$ac_id = intval($this->input->post($ac_id_post));
			$can_access = $this->input->post($can_access_post);
			$can_control = $this->input->post($can_control_post);

			if($ac_id > 0){
				$this->Users_m->update_user_access($can_access,$can_control,$ac_id);
			}else{
				$this->Users_m->insert_access($access_location->access_area_id,$can_access,$can_control,$set_user_id);
			}
		}

		$isAdmin = $this->input->post('isAdmin');
		$this->Users_m->update_admin_access($isAdmin,$set_user_id);

		$this->session->set_flashdata('form_success_access','User access details is now updated. The new access will take effect on the next login.');

		redirect('Users/account/'.$set_user_id.'#form_success_access');
	}

	public function updateUserPhoto(){
		$this->admin->clear_apost();
		$set_user_id = $this->input->post('userId');
		$time = time();

		if(!isset($set_user_id) || $set_user_id == ''){
			redirect('Users');
		}

		$path = "./uploads/user_photo/";

		$config['upload_path'] = $path;
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size'] = 10000;
		$config['max_width'] = 2024;
		$config['max_height'] = 2024;
		$config['file_name'] = $set_user_id.'_'.$time;

		$this->upload->initialize($config);
		$this->load->library('upload');
		$this->upload->do_upload('userPhotoFile');

		$new_profilePhoto = $this->upload->data('file_name'); 
		$this->Users_m->update_user_photo($set_user_id,$new_profilePhoto);

		if($this->session->userdata('user_id') == $set_user_id){
			$this->session->set_userdata('user_profile_photo', $new_profilePhoto);
		}

		redirect('Users/account/'.$set_user_id);
	}

	public function post_to_flash($post_val,$var_name){
		$this->session->set_flashdata($var_name, $post_val);
	}

	public function newUserDetails(){
		$this->admin->clear_apost();

		$this->form_validation->set_rules('firstName', 'First Name', 'required');
		$this->form_validation->set_rules('lastName', 'Last Name', 'required');
		$this->form_validation->set_rules('emp_position', 'Position', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');

		$this->form_validation->set_rules('officeExt', 'Office Ext', 'numeric');
		$this->form_validation->set_rules('officeNumber', 'Office Number', 'alpha_numeric_spaces');
		$this->form_validation->set_rules('mobileNumber', 'Mobile Number', 'alpha_numeric_spaces');

		$this->form_validation->set_rules('userLoginName', 'User Name', 'required|is_unique[users.user_name]');

		$this->form_validation->set_message('is_unique', "Value for {field} is used, please try a different one.");
		$this->form_validation->set_message('alpha_numeric_spaces', "Value for {field} is invalid.");
		$this->form_validation->set_error_delimiters('<p class="p-0 m-0"> &nbsp;  <em class="fas fa-exclamation-circle"></em> &nbsp;', '</p>');


		if ($this->form_validation->run() == FALSE){

			$str_msgs = strval( validation_errors() );
			$this->session->set_flashdata('form_error_msg', $str_msgs);

			$this->post_to_flash( $this->input->post('firstName',true) ,'firstName');
			$this->post_to_flash( $this->input->post('lastName',true) ,'lastName');
			$this->post_to_flash( $this->input->post('emp_position',true) ,'emp_position');
			$this->post_to_flash( $this->input->post('email',true) ,'email');
			$this->post_to_flash( $this->input->post('officeExt',true) ,'officeExt');
			$this->post_to_flash( $this->input->post('officeNumber',true) ,'officeNumber');
			$this->post_to_flash( $this->input->post('mobileNumber',true) ,'mobileNumber');
			$this->post_to_flash( $this->input->post('userLoginName',true) ,'userLoginName');
			$this->post_to_flash( $this->input->post('isAdmin',true) ,'isAdmin');

			redirect('Users/add');

		}else{

			$date_pwd_exp = new DateTime('now');
			$pwd_exp = $this->session->admin_pwrd_life_exp;

			$officeNumber = $this->input->post('officeNumber',true);
			$officeExt = $this->input->post('officeExt',true);
			$mobileNumber = $this->input->post('mobileNumber',true);
			
			$email = $this->input->post('email',true);

			$userLoginName = $this->input->post('userLoginName',true);
			$pwd = $this->session->admin_pwrd_default;
			$firstName = $this->input->post('firstName',true);
			$lastName = $this->input->post('lastName',true);

			$date_today = $date_pwd_exp->format('d/m/Y');
			$isAdmin = $this->input->post('isAdmin',true);
			$emp_position = $this->input->post('emp_position',true);

			$date_pwd_exp->modify('+'.$pwd_exp);
			$date_pwd_exp = $date_pwd_exp->format('d/m/Y');

			//var_dump($_POST);

			$contact_number_id = $this->Users_m->insert_contact_numbers($officeNumber,$officeExt,$mobileNumber);
			$email_id = $this->Users_m->insert_email($email);

			$user_id = $this->Users_m->insert_new_user($userLoginName,$pwd,$firstName,$lastName,'',$date_today,$isAdmin,$email_id,$contact_number_id,$emp_position);
			$this->Users_m->insert_pword($user_id,'01/01/2000',$pwd);

			$this->set_min_access_control($user_id);
			$this->session->set_flashdata('form_success_msg','New user is now added.');
			redirect('Users/account/'.$user_id);
			
		}

		
	}

	public function set_min_access_control($user_id){
		$q_access_area = $this->admin->get_all_access_area();
		foreach ($q_access_area->result() as $data):
			$this->admin->insert_access_areas($data->access_area_id,$user_id);
		endforeach;
	}


	public function updateUserDetails(){
		$this->admin->clear_apost();
		$set_user_id = $this->input->post('userId');

		if(!isset($set_user_id) || $set_user_id == ''){
			redirect('Users');
		}

		$this->form_validation->set_rules('officeExt', 'Office Ext', 'numeric');
		$this->form_validation->set_rules('officeNumber', 'Office Number', 'alpha_numeric_spaces');
		$this->form_validation->set_rules('mobileNumber', 'Mobile Number', 'alpha_numeric_spaces');

		$this->form_validation->set_message('alpha_numeric_spaces', "Value for {field} is invalid.");
		$this->form_validation->set_error_delimiters('<p class="p-0 m-0"> &nbsp;  <em class="fas fa-exclamation-circle"></em> &nbsp;', '</p>');

		if ($this->form_validation->run() == FALSE){
			$str_msgs = strval( validation_errors() );
			$this->session->set_flashdata('form_error_msg', $str_msgs);
			redirect('Users/account/'.$set_user_id);

		}else{

			$this->Users_m->update_basic_details($this->input->post('firstName'),$this->input->post('lastName'),$this->input->post('emp_position'),$this->input->post('userId'));
			$this->Users_m->update_contact_numers($this->input->post('officeNumber'),$this->input->post('officeExt'),$this->input->post('mobileNumber'),$this->input->post('user_contact_number_id'));
			$this->Users_m->update_email($this->input->post('email'),$this->input->post('user_email_id'));

			$this->session->set_flashdata('form_success_msg','User details is now updated.');
			redirect('Users/account/'.$set_user_id);
		}
	}
}