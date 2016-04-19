<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set("Australia/Perth");
class Users extends MY_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->model('user_model');		
		$this->load->library('form_validation');
		$this->load->library('upload');
		$this->load->helper('cookie');
$config = Array(
			'protocol' => 'smtp',
			'smtp_host' => 'cp178.ezyreg.com',
			'smtp_port' => 587,
			'smtp_user' => 'accounts@sojourn.focusshopfit.com.au',
			'smtp_pass' => 'CyMOCrP6',
			'mailtype'  => 'html', 
			'charset'   => 'iso-8859-1'
			);
		$this->load->library('email', $config);

 //echo $this->input->cookie('user_id', false);


	}
	
	function index(){
		//$data["users"] = $this->user_model->read();
		
		if(!$this->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;

		$this->_check_user_access('users',1);
/*
		if($this->session->userdata('is_admin') != 1 ):		
			redirect('', 'refresh');
		endif;
*/
//		$this->_check_user_access('users',1);

		$fetch_user= $this->user_model->fetch_user();
		$data['users'] = $fetch_user->result();
		$data['main_content'] = 'users';
		$data['screen'] = 'FSF Group Sojourn Users';
		$this->load->view('page', $data);		

	}

	public function user_logs(){
		if($this->session->userdata('is_admin') != 1 ):		
			redirect('', 'refresh');
		endif;

		$order = 'ORDER BY `users`.`user_first_name` ASC';
		$data['users_q'] = $this->user_model->fetch_login_user($order);
		$user_logs = $this->user_model->fetch_user_logs();
		$data['user_logs'] = $user_logs;
		$data['main_content'] = 'user_logs';
		$data['screen'] = 'User Logs';
		$this->load->view('page', $data);
	}

	public function clear_apost(){
		foreach ($_POST as $key => $value) {
			$_POST[$key] = str_replace("'","&apos;",$value);
		}
	}

	public function fetch_user_access($preset_name = ''){
		$this->clear_apost();

		if($preset_name == ''){
			$role_arr = explode('|',$_POST['ajax_var']);
			$preset_name = $role_arr['1'];
		}


		$user_access_q = $this->user_model->fetch_role_access($preset_name);
		$user_access = array_shift($user_access_q->result_array());

		echo implode(',', $user_access);

	}

	public function update_user_access(){
		$this->clear_apost();
		$user_id = $_POST['user_id_access'];

		if($this->session->userdata('is_admin') ==  1){
			$is_admin = $_POST['chk_is_peon'];
		}else{
			$fetch_user= $this->user_model->fetch_user($user_id);
			$user_details = array_shift($fetch_user->result_array());
			$is_admin = $user_details['if_admin'];
		}

		$dashboard = $_POST['dashboard_access'];
		$company = $_POST['company_access'];
		$projects = $_POST['projects_access'];
		$wip = $_POST['wip_access'];
		$purchase_orders = $_POST['purchase_orders_access'];
		$invoice = $_POST['invoice_access'];
		$users = $_POST['users_access'];
		$bulletin_board = $_POST['bulletin_board'];


		$role_raw = $_POST['role'];
		$role_arr = explode('|',$role_raw);
		$role_id = $role_arr[0];

		//echo "$user_id,$is_admin,$dashboard,$company,$projects,$wip,$purchase_orders,$invoice,$users";

		$this->user_model->update_user_access($user_id,$is_admin,$dashboard,$company,$projects,$wip,$purchase_orders,$invoice,$users,$role_id,$bulletin_board);
		$this->session->set_flashdata('user_access', 'User Access is now updated.');


	redirect('/users/account/'.$user_id);
	}

	public function account(){
		$this->_check_user_access('users',1);

		$this->clear_apost();

		$this->load->module('admin');
		$this->load->module('company');

		$data['main_content'] = 'account';
		$data['screen'] = 'Account Details';

		$departments = $this->user_model->fetch_all_departments();
		$data['departments'] = $departments->result();

		$roles = $this->user_model->fetch_all_roles();
		$data['roles'] = $roles->result();

		$focus = $this->admin_m->fetch_all_company_focus();
		$data['focus'] = $focus->result();
		$error_password = 0;
/*
		$access = $this->user_model->fetch_all_access();
		$data['all_access'] = $access->result();
*/
		$user_id = $this->uri->segment(3);

		$fetch_user= $this->user_model->fetch_user($user_id);
		$data['user'] = $fetch_user->result();

		if($data['user'][0]->user_date_of_birth != ''){

			$dob_arr = explode('/',$data['user'][0]->user_date_of_birth);
			$curr_year = date('Y');
			$curr_month = date('m');
			$curr_day = date('d');

			$data['age'] = ($curr_year - $dob_arr[2]) - 1;

			if($curr_month.$curr_day >= $dob_arr[1].$dob_arr[0]){
				$data['age']++;			
			}

		}else{
			$data['age'] = '';
		}

		$re_password = $this->user_model->get_latest_user_password($user_id);
		$re_password_arr = array_shift($re_password->result_array());

		$data['current_password'] = $re_password_arr['password'];

		if($this->input->post('update_password')){

			//$this->form_validation->set_rules('current_password', 'Current Password','trim|required|xss_clean');
			$this->form_validation->set_rules('new_password', 'New Password','trim|required|xss_clean');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password','trim|required|xss_clean');

			$new_password = $this->input->post('new_password', true);
			$confirm_password = $this->input->post('confirm_password', true);

			$old_passwords_q = $this->user_model->fetch_user_passwords($user_id);

			foreach ($old_passwords_q->result_array() as $row){
				if($row['password'] == $new_password){
					$error_password = 1;
				}
			}



			if($this->form_validation->run() === false || $error_password == 1){
				$data['error'] = validation_errors();

				if($error_password == 1){
					$data['error'] .= '<p><strong>New Password Error:</strong> This password is already used. Please Try again.</p>';
				}

			}else{

				//$current_password_raw = $this->input->post('current_password', true);
				//$current_password = md5($current_password_raw);

				$static_defaults_q = $this->user_model->select_static_defaults();
				$static_defaults = array_shift($static_defaults_q->result_array());

				if($new_password == $confirm_password){					

					$this->user_model->change_user_password($new_password,$user_id,$static_defaults['days_psswrd_exp']);
					//$data['user_password_updated'] = 'Your password is now changed';
					$this->session->set_flashdata('new_pass_msg', 'An email was sent for confirmation. Please sign-in with your new password.');

					$send_to = $data['user'][0]->general_email;

					$this->email->from('no-reply@sojourn.focusshopfit.com.au', 'Sojourn - Accounts');
					$this->email->to($send_to); 
						//$this->email->cc('another@another-example.com'); 
						//$this->email->bcc('them@their-example.com'); 

					$this->email->subject('Password Change');
					$curr_year = date('Y');
					$this->email->message("Do not reply in this email.<br /><br />Congratulations!<br /><br />Your New Password is : ****".substr($new_password,4)."<br /><br />&copy; FSF Group ".$curr_year);	
					$this->email->send();
					redirect('users/account/'.$user_id, 'refresh');

				}else{
					$data['error'] = 'New Password and Confirm Password did not match';
				}

			}

		}



		$this->load->view('page', $data);


		if(isset($_POST['is_form_submit'])){

			$user_info = array_shift($fetch_user->result_array());


			$first_name = $this->company->cap_first_word($this->company->if_set($this->input->post('first_name', true)));
			$last_name = $this->company->cap_first_word($this->company->if_set($this->input->post('last_name', true)));
			$gender = $this->company->cap_first_word($this->company->if_set($this->input->post('gender', true)));
			$dob = $this->company->if_set($this->input->post('dob', true));
			$login_name = $this->company->if_set($this->input->post('login_name', true));

			$department_raw = $this->input->post('department', true);
			$department_arr = explode('|',$department_raw);

			$department_id = $department_arr[0];

			$focus_raw = $this->input->post('focus', true);
			$focus_arr = explode('|',$focus_raw);
			$focus_id = $focus_arr[0];

			if($this->session->userdata('is_admin') != 1 ){
				$department_id = $user_info['user_department_id'];
				$focus_id = $user_info['user_focus_company_id'];
			}

			$skype_id = $this->company->if_set($this->input->post('skype_id', true));
			$skype_password = $this->input->post('skype_password', true);

			$direct_landline = $this->company->if_set($this->input->post('direct_landline', true));
			$after_hours = $this->company->if_set($this->input->post('after_hours', true));
			$mobile_number = $this->company->if_set($this->input->post('mobile_number', true));
			$email = $this->company->if_set($this->input->post('email', true));

			$comments = $this->company->cap_first_word_sentence($this->company->if_set($this->input->post('comments', true)));

			$contact_number_id = $this->input->post('contact_number_id', true);
			$email_id = $this->input->post('email_id', true);
			$user_comments_id = $this->input->post('user_comments_id', true);
/*
			$access_raw = $this->input->post('access',true);
			$access = '';
			foreach ($access_raw as $key => $value) {
				$accss_arr = explode('|', $value);
				$access .= $accss_arr[0].',';
			}
			$access_id = substr($access, 0, -1);
*/
			if($user_comments_id > 0 && $user_comments_id!=''){
				$this->user_model->update_comments($user_comments_id,$comments);
			}else if($user_comments_id!='' && $comments){
				$user_comments_id = $this->company_m->insert_notes($comments);
			}else{

			}


			$profile_raw = $this->input->post('profile_raw',true);

			$file_upload_arr = array('');
			if(isset($_FILES['profile_photo'])){
				if($_FILES['profile_photo']['name'] != ''){
					$file_upload_raw = $this->_upload_primary_photo('profile_photo','users');
					$file_upload_arr = explode('|',$file_upload_raw);
				}
			}

	//		var_dump($file_upload_arr);
/*
			if($file_upload_arr[0] == 'success'){
				$profile = $file_upload_arr[1];
			}else{
				$profile = $profile_raw;
				$data['upload_error'] = $file_upload_arr[1];
			}

 */

			if($file_upload_arr[0] == 'error'){
				$profile = $profile_raw;
				$data['upload_error'] = $file_upload_arr[1];
			}

			if($file_upload_arr[0] == 'success'){
				$profile = $file_upload_arr[1];
			}else{
				$profile = $profile_raw;
				if(array_key_exists('1',$file_upload_arr)){
					$data['upload_error'] = $file_upload_arr[1];
				}
			}

			$this->session->set_flashdata('account_update_msg', 'User account is now updated.');

			$this->user_model->update_user_details($user_id,$login_name,$first_name,$last_name,$skype_id,$skype_password,$gender,$dob,$department_id,$focus_id,$user_comments_id,$profile);

			$this->user_model->update_contact_email($email_id,$email,$contact_number_id,$direct_landline,$mobile_number,$after_hours);

			redirect($this->uri->uri_string(),'refresh');

			

		}

	}

	public function get_user_access($user_id){
		$user_access_q = $this->user_model->fetch_all_access($user_id);
		$user_access_arr = array_shift($user_access_q->result_array());
		return implode(',', $user_access_arr);
	}

	function _upload_primary_photo($fileToUpload,$dir){
		$data['upload_error'] = '';

		$config['upload_path'] = './uploads/'.$dir.'/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size']	= '0';
		$config['max_width']  = '0';
		$config['max_height']  = '0';

		$time = mdate("%h%i%s%m%d%Y", time());
		$config['file_name']  = 'user_'.$time;

		$this->upload->initialize($config);

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

			if ( ! $this->upload->do_upload($fileToUpload)){
				$upload_error = array('error' => $this->upload->display_errors());

				foreach ($upload_error as $key => $value) {
					$data['upload_error'] .= $value;
				}
				$upload_error = $data['upload_error'];
				return 'error|'.$upload_error;

			}else{
				$up_data = array('upload_data' => $this->upload->data());
				$img_upload_name = $up_data['upload_data']['file_name'];
				return 'success|'.$img_upload_name;
			}
	}

	public function delete_user(){
		$user_id = $this->uri->segment(3);
		$this->user_model->delete_user($user_id);
		redirect('users', 'refresh');
	}

	public function add(){
		$this->clear_apost();

		if(!$this->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;

		#if($this->session->userdata('is_admin') != 1 || $this->session->userdata('users') <= 1):
		if( $this->session->userdata('users') <= 1):		
			redirect('', 'refresh');
		endif;

	//	$this->_check_user_access('users',2);

		$this->load->module('admin');
		$this->load->module('company');


		$departments = $this->user_model->fetch_all_departments();
		$data['departments'] = $departments->result();

		$roles = $this->user_model->fetch_all_roles();
		$data['roles'] = $roles->result();

		$focus = $this->admin_m->fetch_all_company_focus();
		$data['focus'] = $focus->result();

		
		$static_defaults = $this->user_model->select_static_defaults();
		$data['static_defaults'] = $static_defaults->result();
/*
		$access = $this->user_model->fetch_all_access();
		$data['all_access'] = $access->result();
*/

		$this->form_validation->set_rules('first_name', 'First Name','trim|required|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name','trim|required|xss_clean');
		$this->form_validation->set_rules('gender', 'Gender','trim|required|xss_clean');
		$this->form_validation->set_rules('dob', 'Date of Birth','trim|xss_clean');
		$this->form_validation->set_rules('login_name', 'Login Name','trim|required|xss_clean');
		$this->form_validation->set_rules('password', 'Password','trim|required|xss_clean');
		$this->form_validation->set_rules('department', 'Department','trim|required|xss_clean');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password','trim|required|xss_clean');
		$this->form_validation->set_rules('role', 'Role','trim|required|xss_clean');
		$this->form_validation->set_rules('focus', 'Focus','trim|required|xss_clean');
		$this->form_validation->set_rules('direct_landline', 'Direct Landline','trim|required|xss_clean');
		$this->form_validation->set_rules('after_hours', 'After Hours','trim|xss_clean');
		$this->form_validation->set_rules('mobile_number', 'Mobile Number','trim|xss_clean');
		$this->form_validation->set_rules('email', 'Email','trim|required|xss_clean');
		$this->form_validation->set_rules('skype_id', 'Skype ID','trim|required|xss_clean');
		$this->form_validation->set_rules('comments', 'Comments','trim|xss_clean');

		$data['main_content'] = 'new_user';
		$data['screen'] = 'New User';


		$file_upload_arr = array('');
		if(isset($_FILES['profile_photo'])){
			if($_FILES['profile_photo']['name'] != ''){
				$file_upload_raw = $this->_upload_primary_photo('profile_photo','users');
				$file_upload_arr = explode('|',$file_upload_raw);
			}
		}


		if($this->form_validation->run() === false){
			$data['error'] = validation_errors();
			$password = $this->company->if_set($this->input->post('password', true));
			$confirm_password = $this->company->if_set($this->input->post('confirm_password', true));

			//$access = $this->input->post('access', true);

			if(isset($_POST['is_form_submit'])){
				if($password!=$confirm_password || $password == ''){
					$data['pword'] = '<p>Please confirm your Password.</p>';
				}
/*
				if(!$access){
					$data['access_err'] = '<p>Please assign access for the user, at least view access?</p>';
				}
*/
				if($file_upload_arr[0] == 'error'){
					$profile_photo = '';
					$data['upload_error'] = $file_upload_arr[1];
				}
			}


			$this->load->view('page', $data);
		}else{

			if($file_upload_arr[0] == 'success'){
				$profile_photo = $file_upload_arr[1];
			}else{
				$profile_photo = '';
				$data['upload_error'] = $file_upload_arr[1];
			}

			$first_name = $this->company->cap_first_word($this->company->if_set($this->input->post('first_name', true)));
			$last_name = $this->company->cap_first_word($this->company->if_set($this->input->post('last_name', true)));
			$gender = $this->company->cap_first_word($this->company->if_set($this->input->post('gender', true)));
			$dob = $this->company->if_set($this->input->post('dob', true));
			$login_name = $this->company->if_set($this->input->post('login_name', true));

			$confirm_password = $this->company->if_set($this->input->post('confirm_password', true));

			$password = $this->company->if_set($this->input->post('password', true));
			$password = md5($password);

			$department_raw = $this->input->post('department', true);
			$department_arr = explode('|',$department_raw);
			$department_id = $department_arr[0];

			$role_raw = $this->input->post('role', true);
			$role_arr = explode('|',$role_raw);
			$role_id = $role_arr[0];

			$focus_raw = $this->input->post('focus', true);
			$focus_arr = explode('|',$focus_raw);
			$focus_id = $focus_arr[0];


			$skype_id = $this->company->if_set($this->input->post('skype_id', true));
			$skype_password = $this->input->post('skype_password', true);

			$direct_landline = $this->company->if_set($this->input->post('direct_landline', true));
			$after_hours = $this->company->if_set($this->input->post('after_hours', true));
			$mobile_number = $this->company->if_set($this->input->post('mobile_number', true));
			$email = $this->company->if_set($this->input->post('email', true));

			$days_exp = $this->company->if_set($this->input->post('days_exp', true));

			$comments = $this->company->cap_first_word_sentence($this->company->if_set($this->input->post('comments', true)));


			if($comments){
				$user_notes_id = $this->company_m->insert_notes($comments);
			}else{
				$user_notes_id = 0;
			}

			$contact_number_id = $this->company_m->insert_contact_number('','',$direct_landline,$mobile_number,$after_hours);
			
			$email_id = $this->company_m->insert_email($email);

			$dashboard_access = $this->input->post('dashboard_access', true);
			$company_access = $this->input->post('company_access', true);
			$projects_access = $this->input->post('projects_access', true);
			$wip_access = $this->input->post('wip_access', true);
			$purchase_orders_access = $this->input->post('purchase_orders_access', true);
			$invoice_access = $this->input->post('invoice_access', true);
			$users_access = $this->input->post('users_access', true);
			$bulletin_board = $this->input->post('bulletin_board', true);

			date_default_timezone_set("Australia/Perth");
			$user_timestamp_registered = time();


			if($this->session->userdata('is_admin') ==  1){
				$admin = (isset($_POST['chk_is_peon']) && $_POST['chk_is_peon'] == 1 ? 1 : 0);
			}else{
				$admin = 0;
			}			

			$add_new_user_id = $this->user_model->add_new_user($login_name,$password,$first_name,$last_name,$gender,$department_id,$profile_photo,$user_timestamp_registered,$role_id,$email_id,$skype_id,$skype_password,$contact_number_id,$focus_id,$dob,$user_notes_id,$admin);

			$this->user_model->insert_user_access($add_new_user_id,$dashboard_access,$company_access,$projects_access,$wip_access,$purchase_orders_access,$invoice_access,$users_access,$bulletin_board);

			$this->user_model->insert_user_password($confirm_password,$add_new_user_id);

			$new_user_success = 'The user is now added.';
			$this->session->set_flashdata('new_user_success', $new_user_success);

			$send_to = $email;

			$this->email->from('no-reply@sojourn.focusshopfit.com.au', 'Sojourn - Accounts');
			$this->email->to($send_to); 
						//$this->email->cc('another@another-example.com'); 
						//$this->email->bcc('them@their-example.com'); 

			$this->email->subject('Account Details');
			$this->email->message('Do not reply in this email.<br /><br />Welcome '.$first_name.' to Sojourn, an FSf Group Project Management Application.<br /><br />You have been added as a new user and provided with a temporary password.  Please sign-in right away where you will be required to change your password, then you will need to sign in again using your username & changed password.  Use the link below.<br /><br /><a href="'.base_url().'" target="_blank">'.base_url().'</a><br /><br />Your User Name is : '.$login_name.' and Password is : '.$confirm_password.'<br /><br />If you go to the USER section of the site you can personalise your settings and complete your set up.<br /><br />&copy; FSF Group 2015');	
			$this->email->send();

			redirect('/users');
		}

	}


	function signin(){
		$this->clear_apost();
		//Redirect
		if($this->_is_logged_in()){

			if($this->session->userdata('user_role_id') == 1 ){
				redirect('dashboard'); 
			}else{
				redirect('projects'); //dashboard
			}
		}
		
		$config = array(
			array('field' => 'user_name','label' => 'User Name','rules' => 'trim|required'),
			array('field' => 'password','label' => 'Password','rules' => 'trim|required'
			)
		);
		
		$this->form_validation->set_rules($config);
			
		if($this->form_validation->run() === false){
			$data['error' ] = validation_errors();
			$data['main_content'] = 'signin';
			$this->load->view('page', $data);
		}else{
			$user_name = $this->input->post('user_name', true);
			$password 	= $this->input->post('password', true);
			$remember = $this->input->post('remember', true);
			if($remember == ""){
				$data['remember'] = 0;
			}else{
				$data['remember'] = 1;
			}
			


			//$user_id = $this->input->cookie('user_id', false); // assign mo nalang ang value para isahan ka lang mag babasa ng cookie
			//if($user_id ==''){
				//$userdata = $this->user_model->validate($user_name, md5($password));
			//}else{
				//$cookie_user_id = $user_id;
				$ip_add = $_SERVER['REMOTE_ADDR'];
				$data['ip_add'] = $ip_add;
			// $user_log_str = file_get_contents(base_url().'js/users_log.json');
			// $user_log_json = json_decode($user_log_str,true);



			// foreach ($user_log_json['users'] as $log_key => $log_entry) {
			// 	$user_id =  $user_log_json['users'][$log_key]['user_id'];
			// 	$user_ipadd = $user_log_json['users'][$log_key]['user_ipadd'];

			//     if ($log_entry['date_log'] !== date('Y-m-d')) {
			//         $user_log_json['users'][$log_key]['date_log'] = "";

			//         $str = file_get_contents(base_url().'js/users.json');
			// 		$json = json_decode($str,true);

			//         foreach ($json['users'] as $key => $entry) {
			// 		    if ($entry['user_id'] == $user_id) {
			// 		        $json['users'][$key]['login_stat'] = 0;
			// 		        $this->user_model->log_out($user_id);
			// 		        if($ip_add == $user_ipadd){
			// 		        	$this->session->sess_destroy();
			// 		        }
			// 		    }
			// 		}

			// 		file_put_contents('./js/users.json',json_encode($json));
			//     }else{

			//     	$timeFirst  = strtotime(date('Y-m-d H:i:s'));
			// 		$timeSecond = strtotime(date('Y-m-d').' '.$log_entry['time_log']);
			// 		$differenceInSeconds = $timeFirst - $timeSecond;
			//     	$diffinmin = $differenceInSeconds / 60;

			//     	if($diffinmin > 15){
			//     		$str = file_get_contents(base_url().'js/users.json');
			// 			$json = json_decode($str,true);


			// 	        foreach ($json['users'] as $key => $entry) {
			// 			    if ($entry['user_id'] == $user_id) {
			// 			        $json['users'][$key]['login_stat'] = 0;
			// 			        $this->user_model->log_out($user_id);
			// 			        if($ip_add == $user_ipadd){
			// 			        	$this->session->sess_destroy();
			// 			        }
			// 			    }
			// 			}

			// 			file_put_contents('./js/users.json',json_encode($json));
			//     	}
			//     }
			// }


				$userdata = $this->user_model->validate($user_name, md5($password), $ip_add);
			//}
				//Validation
			switch($userdata){
				case "0":
					$data['error'] = "Wrong Username or Password";
					$data['main_content'] = 'signin';
					$this->load->view('page', $data);
					break;
				case "1";
					$userdata_session = $this->user_model->get_user_id($user_name, md5($password), $ip_add);
					$data['user_id'] = $userdata_session->user_id;
					$this->session->set_userdata($data);

					$data['signin_error'] = "User is already logged in on another pc, Do you want to log-off Previous logged-in Account? <button type = 'button' class = 'btn btn-success pull-right' id = 'log_out_prev_user'>Yes</button>";
					$data['main_content'] = 'signin';
					$this->load->view('page', $data);
					break;
				default:

					$this->_confirm_active_password($userdata->user_id,$password);



					$data['user_id'] = $userdata->user_id;
					$data['user_role_id'] = $userdata->user_role_id;
					//$data['user_access_group_id'] = $userdata->user_access_group_id;
					$data['user_first_name'] = $userdata->user_first_name;
					$data['user_last_name'] = $userdata->user_last_name;
					$data['user_profile_photo'] = $userdata->user_profile_photo;
					$data['user_department_id'] = $userdata->user_department_id;
					$data['user_focus_company_id'] = $userdata->user_focus_company_id;
					$data['logged_in'] = true;
					$data['is_admin'] = $userdata->if_admin;
					$data['user_name'] = $user_name;
					$data['password'] = $password;



					$raw_user_access = $this->user_model->fetch_all_access($data['user_id']);
					$user_access = array_shift($raw_user_access->result_array());
					$this->session->set_userdata($user_access);

					//if($user_id!=''){

					delete_cookie('user_id'); // dapat kasi ma reset an cookie very time

						$cookie = array(
							'name'   => 'user_id',
							'value'  => $userdata->user_id,
							'expire' => 86500 * 2, // dito nmn gumamit ka ng ' kapag kasi naka single quote hndi gagana ang multiplication mo, babsahin niya as text mas okay kung 17000 buo tuloy na value
							'secure' => false
							);
						$this->input->set_cookie($cookie);
  
					// $rawdata = file_get_contents('php://input');

					// $rawdata = json_decode($rawdata,true);

  			// 		$str = file_get_contents(base_url().'js/users.json');
					// $json = json_decode($str,true);

					// foreach ($json['users'] as $key => $entry) {
					//     if ($entry['user_id'] == $userdata->user_id) {
					//         $json['users'][$key]['login_stat'] = 1;
					//     }
					// }

					// file_put_contents('./js/users.json',json_encode($json));

					$date_log = date('Y-m-d');
					$time_log = date('H:i:s');
					$this->user_model->insert_user_min_log($userdata->user_id,$date_log,$time_log);
/*
					$user_ind_log_str = file_get_contents(base_url().'js/user_json/'.$userdata->user_id.'.json');
					$user_ind_log_json = json_decode($user_ind_log_str,true);

					foreach ($user_ind_log_json[$userdata->user_id] as $user_ind_log_key => $user_ind_log_entry) {
					    if ($user_ind_log_entry['user_id'] == $userdata->user_id) {
						    $user_ind_log_json[$userdata->user_id][$user_ind_log_key]['date_log'] = date('Y-m-d');
					        $user_ind_log_json[$userdata->user_id][$user_ind_log_key]['time_log'] = date('H:i:s');
					        $user_ind_log_json[$userdata->user_id][$user_ind_log_key]['user_ipadd'] = $ip_add;
						}
					}
					file_put_contents('./js/user_json/'.$userdata->user_id.'.json',json_encode($user_ind_log_json));
*/
					// $user_log_str = file_get_contents(base_url().'js/users_log.json');
					// $user_log_json = json_decode($user_log_str,true);

					// foreach ($user_log_json['users'] as $log_key => $log_entry) {
					// 	if ($log_entry['user_id'] == $userdata->user_id) {
					//         $user_log_json['users'][$log_key]['date_log'] = date('Y-m-d');
					//         $user_log_json['users'][$log_key]['time_log'] = date('H:i:s');
					//         $user_log_json['users'][$log_key]['user_ipadd'] = $ip_add;
					//     }
					// }

					
					/*$exist = 0;
					$stat = 0;
					foreach($json as $key => $value){
						if($key == 'userid' && $value == $userdata->user_id){
							if($key == 'status' && $value == '1'){
								$stat = 1;
							}
							$exist ++;
						}
					}
					if($exist == 0){
							$jsonval = array(
							    'userid'   => $userdata->user_id,
							    'username'  => $userdata->user_first_name.' '.$userdata->user_last_name,
							    'no_minutes' => 0,
							    'user_img' => $userdata->user_profile_photo,
							    'status'=> 1
							);
							file_put_contents('./js/users.json', json_encode($jsonval),FILE_APPEND);
					}else{
						if($stat == 0){
							$file = file_get_contents(base_url().'js/users.json');
							$data_json = json_decode($file,true);
							unset($file);//prevent memory leaks for large json.
							//insert data here
							$data_json[] = array('status'=>'1');
							//save the file
							file_put_contents(base_url().'js/users.json',json_encode($data_json));
							unset($data_json);//release memory
						}
					}*/

			       // }
			        //$data['user_id'] = $this->input->cookie('user_id', false);
			        //$user_id = $this->input->cookie('user_id', false);
			        //echo $user_id;
			        //print_r($user_id);

			        $this->_fetch_system_defaults();
					$this->session->set_userdata($data);
					redirect('', 'refresh');
					break;
			}
			/*if($userdata){
				if($userdata){
					$data['user_id'] = $userdata->user_id;
					$data['user_role_id'] = $userdata->user_role_id;
					$data['user_access_group_id'] = $userdata->user_access_group_id;
					$data['user_first_name'] = $userdata->user_first_name;
					$data['user_last_name'] = $userdata->user_last_name;
					$data['user_profile_photo'] = $userdata->user_profile_photo;
					$data['user_department_id'] = $userdata->user_department_id;
					$data['user_focus_company_id'] = $userdata->user_focus_company_id;
					$data['logged_in'] = true;
					$data['is_admin'] = $userdata->if_admin;

					//if($user_id!=''){


					delete_cookie('user_id'); // dapat kasi ma reset an cookie very time

						$cookie = array(
							'name'   => 'user_id',
							'value'  => $userdata->user_id,
							'expire' => 86500 * 2, // dito nmn gumamit ka ng ' kapag kasi naka single quote hndi gagana ang multiplication mo, babsahin niya as text mas okay kung 17000 buo tuloy na value
							'secure' => false
							);
						$this->input->set_cookie($cookie);
  
			       // }
			        //$data['user_id'] = $this->input->cookie('user_id', false);
			        //$user_id = $this->input->cookie('user_id', false);
			        //echo $user_id;
			        //print_r($user_id);
			        $this->_fetch_system_defaults();
					$this->session->set_userdata($data);
					redirect('');
				}else{
					$data['error'] = "Not validated!";
					$data['main_content'] = 'signin';
					$this->load->view('page', $data);
				}
			}else{
				if($userdata == 1){
					$data['signin_error'] = "User is already logged in on another pc, Please make sure to log-off your account right after use";
				}else{
					$data['error'] = "Wrong Username or Password";
				}
				 
				$data['main_content'] = 'signin';
				$this->load->view('page', $data);
			}*/
		}
	}

	public function re_password(){

		$user_id = $this->uri->segment(3);
		$error_password = 0;

		if(!$user_id){
			redirect('');			
		}

		if($user_id != $this->session->userdata('re_pass_user_id')){
			redirect('');			
		}

		$re_password = $this->user_model->get_latest_user_password($user_id);
		$re_password_arr = array_shift($re_password->result_array());

		$current_date = date("d-m-Y");
		$timestamp_curr = strtotime($current_date);

		$timestamp_passwrd = $re_password_arr['expiration_date_mod'];
		
		if(!$timestamp_passwrd){
			redirect('');			
		}


		$user_details_q = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($user_details_q->result_array());


		if($timestamp_passwrd <= $timestamp_curr){
			$data['main_content'] = 're_password';
			


			if($this->input->post('update_password')){
				
				$this->form_validation->set_rules('new_password', 'New Password','trim|required|xss_clean');
				$this->form_validation->set_rules('confirm_password', 'Confirm Password','trim|required|xss_clean');

				$new_password = $this->input->post('new_password', true);
				$confirm_password = $this->input->post('confirm_password', true);

				$old_passwords_q = $this->user_model->fetch_user_passwords($user_id);

				foreach ($old_passwords_q->result_array() as $row){
					if($row['password'] == $new_password){
						$error_password = 1;
					}
				}



				if($this->form_validation->run() === false || $error_password == 1){
					$data['error'] = validation_errors();

					if($error_password == 1){
						$data['error'] .= '<p><strong>New Password Error:</strong> This password is already used. Please Try again.</p>';
					}

				}else{

					$current_password_raw = $this->input->post('current_password', true);
					$current_password = md5($current_password_raw);


					$static_defaults_q = $this->user_model->select_static_defaults();
					$static_defaults = array_shift($static_defaults_q->result_array());

					//var_dump($static_defaults);

					if($new_password == $confirm_password && $new_password != $this->session->userdata('re_pass_curr')){	

						$this->session->set_flashdata('new_pass_msg', 'An email was sent for confirmation. Please sign-in with your new password.');

						$this->user_model->change_user_password($new_password,$user_id,$static_defaults['days_psswrd_exp']);


						$send_to = $user_details['general_email'];


						$this->email->from('no-reply@sojourn.focusshopfit.com.au', 'Sojourn - Accounts');
						$this->email->to($send_to); 
						//$this->email->cc('another@another-example.com'); 
						//$this->email->bcc('them@their-example.com'); 

						$this->email->subject('Password Change');
						$curr_year = date('Y');
						$this->email->message("Do not reply in this email.<br /><br />Congratulations!<br /><br />Your New Password is : ****".substr($new_password,4)."<br /><br />&copy; FSF Group".$curr_year);	
						$this->email->send();
						redirect('');

					}else{
						$data['error'] = 'Please complete the form and confirm the new password.';
					}

				}

			}
			$this->load->view('page', $data);


		}else{
			redirect('');
		}		
	}

	function _confirm_active_password($user_id,$user_password){

		$this->session->set_userdata('re_pass_user_id',$user_id);
		$this->session->set_userdata('re_pass_curr',$user_password);

		$re_password = $this->user_model->get_latest_user_password($user_id);
		$re_password_arr = array_shift($re_password->result_array());

		$current_date = date("d-m-Y");
		$timestamp_curr = strtotime($current_date);

		$timestamp_passwrd = $re_password_arr['expiration_date_mod'];

		if($timestamp_passwrd <= $timestamp_curr){
			 redirect('/users/re_password/'.$user_id);
		}

	}
	
	function logout(){
		$userid = $this->session->userdata('user_id');
		delete_cookie("user_id");

		// $str = file_get_contents(base_url().'js/users.json');
		// $json = json_decode($str,true);

		// foreach ($json['users'] as $key => $entry) {
		//     if ($entry['user_id'] == $userid) {
		//         $json['users'][$key]['login_stat'] = 0;
		//     }
		// }

		// file_put_contents('./js/users.json',json_encode($json));

		$this->user_model->log_out($userid);
		$this->session->sess_destroy();
		redirect('');
	}
	
//Hidden Methods not allowed by url request

	function _fetch_system_defaults(){
		$system_defaults_raw = $this->user_model->fetch_admin_defaults();
		$system_defaults_arr = array_shift($system_defaults_raw->result_array());
		$this->session->set_userdata($system_defaults_arr);
	}

	function _member_area(){
		if(!$this->_is_logged_in()){
			redirect('signin');
		}
	}
	
	function _is_logged_in(){
		
		if($this->session->userdata('logged_in')){
			$idleTime = $this->session->userdata('user_log_min');
			if($idleTime == 1){
				$data['user_log_min'] = 0;
				$this->session->set_userdata($data);
				$this->logout();
				$output = false;
			}else{
				$output = true;
			}
			// $home = $this->uri->segment(1);
			// if($home == ""){



				$ip_add = $_SERVER['REMOTE_ADDR'];
				// $str = file_get_contents(base_url().'js/users.json');
				// $json = json_decode($str,true);
				$user_id = $this->session->userdata('user_id');
				if($user_id !== ""){
					$log_in_users = $this->user_model->fetch_user($user_id);
					foreach ($log_in_users->result_array() as $row)
					{
						
						$log_date = $row['date_log'];
						$time_log = $row['time_log'];
						$user_ipadd = $row['ip_address'];
						if ($log_date !== date('Y-m-d')) {
							
					      //   foreach ($json['users'] as $key => $entry) {
							    // if ($entry['user_id'] == $user_id) {
							    //   //  $json['users'][$key]['login_stat'] = 0;
							        $this->user_model->log_out($user_id);
							        //if($ip_add == $user_ipadd){
							        	$this->session->sess_destroy();
							        	redirect('');
							        //}
							//     }
							// }

							
					    }else{

					    	$timeFirst  = strtotime(date('Y-m-d H:i:s'));
							$timeSecond = strtotime(date('Y-m-d').' '.$time_log);
							$differenceInSeconds = $timeFirst - $timeSecond;
					    	$diffinmin = $differenceInSeconds / 60;

					    	if($diffinmin > 14){

						      //   foreach ($json['users'] as $key => $entry) {
								    // if ($entry['user_id'] == $user_id) {
								    //     $json['users'][$key]['login_stat'] = 0;
								        $this->user_model->log_out($user_id);
								       // if($ip_add == $user_ipadd){
								        	$this->session->sess_destroy();
								        	redirect('');
								        //}
								//     }
								// }
					    	}
					    }  
					}

					$sess_ip_add = $this->session->userdata('ip_add');
					if($sess_ip_add !== $user_ipadd){
						$this->session->sess_destroy();
						redirect('');
					}
				}
				/*
				$user_log_str = file_get_contents(base_url().'js/users_log.json');
				$user_log_json = json_decode($user_log_str,true);

				$str = file_get_contents(base_url().'js/users.json');
				$json = json_decode($str,true);

				foreach ($user_log_json['users'] as $log_key => $log_entry) {
					$user_id =  $user_log_json['users'][$log_key]['user_id'];
					

					$user_ind_log_str = file_get_contents(base_url().'js/user_json/'.$user_id.'.json');
					$user_ind_log_json = json_decode($user_ind_log_str,true);

					foreach ($user_ind_log_json[$user_id] as $user_ind_log_key => $user_ind_log_entry) {
					    if ($user_ind_log_entry['user_id'] == $user_id) {
						    $user_ipadd = $user_ind_log_json[$user_id][$user_ind_log_key]['user_ipadd'];
						    $log_date = $user_ind_log_json[$user_id][$user_ind_log_key]['date_log'];
						    $time_log = $user_ind_log_json[$user_id][$user_ind_log_key]['time_log'];
						}
					}
					

				    if ($log_date !== date('Y-m-d')) {

				        foreach ($json['users'] as $key => $entry) {
						    if ($entry['user_id'] == $user_id) {
						        $json['users'][$key]['login_stat'] = 0;
						        $this->user_model->log_out($user_id);
						        if($ip_add == $user_ipadd){
						        	$this->session->sess_destroy();
						        }
						    }
						}

						
				    }else{

				    	$timeFirst  = strtotime(date('Y-m-d H:i:s'));
						$timeSecond = strtotime(date('Y-m-d').' '.$time_log);
						$differenceInSeconds = $timeFirst - $timeSecond;
				    	$diffinmin = $differenceInSeconds / 60;

				    	if($diffinmin > 2){

					        foreach ($json['users'] as $key => $entry) {
							    if ($entry['user_id'] == $user_id) {
							        $json['users'][$key]['login_stat'] = 0;
							        $this->user_model->log_out($user_id);
							        if($ip_add == $user_ipadd){
							        	$this->session->sess_destroy();
							        }
							    }
							}
				    	}
				    }
				}
				file_put_contents('./js/users.json',json_encode($json));
				*/
			//}
			
		}else{
			$output = false;
		}
		return $output;
	}

	function _check_user_access($area,$access){
		// $area,$acces_value

		/*
			#list of areas

			dashboard
			company
			projects
			wip
			purchase_orders
			invoice
			users
			admin_controls
		*/

		if($this->session->userdata($area) >= $access ){

		}else{
			redirect(base_url());
		}

		
	}
	
	function userdata(){
		if($this->_is_logged_in()){
			return $this->user_model->user_by_id($this->session->userdata('userid'));
		}else{
			return false;
		}
	}
	
	function _is_admin(){
		if(@$this->users->userdata()->role === 1){
			return true;
		}else{
			return false;
		}
	}

	function login_users(){
		$data['log_users_q'] = $this->user_model->fetch_login_user();
		$this->load->view("login_users_t",$data);
	}
	
	function logout_user(){
		$this->clear_apost();
		$userid = $_POST['user_id'];
		$this->user_model->log_out($userid);

		// $str = file_get_contents(base_url().'js/users.json');
		// $json = json_decode($str,true);

		// foreach ($json['users'] as $key => $entry) {
		//     if ($entry['user_id'] == $userid) {
		//         $json['users'][$key]['login_stat'] = 0;
		//     }
		// }

		// file_put_contents('./js/users.json',json_encode($json));

		// $str = file_get_contents(base_url().'js/users.json');
		// $json = json_decode($str,true);
		// $admin = $this->session->userdata('is_admin');
		// $users=$json['users'];
		// echo '<ul style = "list-style-type: none">';
		// foreach($users as $user)
		// {
		// 	if($user['login_stat'] == 1){
		// 		if($admin == 1){
		// 			echo '<li><img src = "./uploads/users/'.$user['user_img'].'" style = "width: 30px">'." ".$user['user_name'].'<button type = "button" class = "btn btn-danger btn-xs pull-right" onclick = "btn_logout_user('.$user['user_id'].')"><i class = "fa fa-sign-out fa-sm"></i></button></li>';
		// 		}else{
		// 			echo '<li><img src = "./uploads/users/'.$user['user_img'].'" style = "width: 30px">'." ".$user['user_name'].'</li>';
		// 		}
		// 	}
		// }
		// echo '</ul>';
		// $data['log_users_q'] = $this->user_model->fetch_login_user();
		// $this->load->view("login_users_t",$data);
	}

	// function set_user_time(){
	// 	$data['user_log_min'] = 0;
	// 	$this->session->set_userdata($data);


	// 	// $userid = $this->session->userdata('user_id');
	// 	// $str = file_get_contents(base_url().'js/users.json');
	// 	// $json = json_decode($str,true);

	// 	// foreach ($json['users'] as $key => $entry) {
	// 	//     if ($entry['user_id'] == $userid) {
	// 	//     	$json['users'][$key]['min_login'] = 0;
	// 	// 	}
	// 	// }

	// 	// echo json_encode($json);
	// 	// file_put_contents('./js/users.json',json_encode($json));

	// 	//echo 0;
	// }

	// function count_user_log_min(){
	// 	$idleTime = $this->session->userdata('user_log_min') + 1;
	
	// 	$data['user_log_min'] = $idleTime;
	// 	$this->session->set_userdata($data);

	// 	// $userid = $this->session->userdata('user_id');
	// 	// $str = file_get_contents(base_url().'js/users.json');
	// 	// $json = json_decode($str,true);

	// 	// $users=$json['users'];
	// 	// foreach($users as $user)
	// 	// {
	// 	// 	if($user['user_id'] == $userid){
	// 	// 		$idleTime = $user['min_login'] + 1;
	// 	// 	}
	// 	// }

	// 	// foreach ($json['users'] as $key => $entry) {
	// 	//     if ($entry['user_id'] == $userid) {
	// 	//         $json['users'][$key]['min_login'] = $idleTime;
	// 	//     }
	// 	// }

	// 	// file_put_contents('./js/users.json',json_encode($json));

		
	// 	echo $idleTime;
	// }

	// function reset_user_log_min(){
	// 	$data['user_log_min'] = 0;
	// 	$this->session->set_userdata($data);
	// }

	// function fetch_log_modal_shown(){
	// 	$log_modal_shown = $this->session->userdata('log_modal_shown');
	// 	echo $log_modal_shown;
	// }

	// function set_log_modal_show(){
	// 	$data['log_modal_shown'] = 1;
	// 	$this->session->set_userdata($data);

	// 	$log_modal_shown = $this->session->userdata('log_modal_shown');
	// 	echo $log_modal_shown;
	// }

	// function set_log_modal_hidden(){
	// 	$data['log_modal_shown'] = 2;
	// 	$this->session->set_userdata($data);

	// 	$data['user_log_min'] = 0;
	// 	$this->session->set_userdata($data);

	// 	$log_modal_shown = $this->session->userdata('log_modal_shown');
	// 	echo $log_modal_shown;

	// }

	function user_login(){
		// $str = file_get_contents(base_url().'js/users.json');
		// $json = json_decode($str,true);
		$admin = $this->session->userdata('is_admin');
		echo '<ul style = "list-style-type: none">';
		$log_in_users = $this->user_model->fetch_user();
		foreach ($log_in_users->result_array() as $row)
		{
		// $users=$json['users'];
		// echo '<ul style = "list-style-type: none">';
		// foreach($users as $user)
		// {
			if($row['user_login_status'] == 1){
				if($admin == 1){
					echo '<li><img src = "'.base_url().'uploads/users/'.$row['user_profile_photo'].'" style = "width: 30px">'." ".$row['user_first_name'].'<button type = "button" title = "Log-out User" class = "btn btn-danger btn-xs pull-right" onclick = "btn_logout_user('.$row['user_id'].')"><i class = "fa fa-sign-out fa-sm"></i></button></li>';
				}else{
					echo '<li><img src = "'.base_url().'uploads/users/'.$row['user_profile_photo'].'" style = "width: 30px">'." ".$row['user_first_name'].'</li>';
				}
			}
		}
		echo '</ul>';
	}
	
	function set_user_log(){
		$userid = $this->session->userdata('user_id');

		$date_log = date('Y-m-d');
		$time_log = date('H:i:s');
		$this->user_model->insert_user_min_log($userid,$date_log,$time_log);

		$sess_ipadd = $this->session->userdata('ip_add');

		$log_in_users = $this->user_model->fetch_user($userid);
		foreach ($log_in_users->result_array() as $row)
		{
			$user_ipadd = $row['ip_address'];
			if($user_ipadd !== $sess_ipadd){
				$this->session->sess_destroy();
				$output = 1;
			}else{
				$output = 0;
			}
		}

		echo $output;

		//echo $user_ipadd."|".$sess_ipadd;
		/*$user_ind_log_str = file_get_contents(base_url().'js/user_json/'.$userid.'.json');
		$user_ind_log_json = json_decode($user_ind_log_str,true);

		foreach ($user_ind_log_json[$userid] as $user_ind_log_key => $user_ind_log_entry) {
		    if ($user_ind_log_entry['user_id'] == $userid) {
			    $user_ind_log_json[$userid][$user_ind_log_key]['date_log'] = date('Y-m-d');
		        $user_ind_log_json[$userid][$user_ind_log_key]['time_log'] = date('H:i:s');
			}
		}


		file_put_contents('./js/user_json/'.$userid.'.json',json_encode($user_ind_log_json));
		*/
		// $str = file_get_contents(base_url().'js/users_log.json');
		// $user_log_json = json_decode($str,true);

		// foreach ($user_log_json['users'] as $log_key => $log_entry) {
		// 	if ($log_entry['user_id'] == $userid) {
		//         $user_log_json['users'][$log_key]['date_log'] = date('Y-m-d');
	 //            $user_log_json['users'][$log_key]['time_log'] = date('H:i:s');
		//     }
		// }

		// file_put_contents('./js/users_log.json',json_encode($user_log_json));

	}
	function set_user_log_min(){
		$data['user_log_min'] = 1;
		$this->session->set_userdata($data);

		$remember = $this->session->userdata('remember');
		if($remember == 1){
			$username = $this->session->userdata('user_name');
			$password = $this->session->userdata('password');
		}else{
			$username = "";
			$password = "";
		}

		echo $username."|".$password."|".$remember;
	}

	function re_login_user(){
		$this->clear_apost();
		// $username = $this->session->userdata('user_name');
		// $password = $this->session->userdata('password');
		$ip_add = $_SERVER['REMOTE_ADDR'];
		$uname = $_POST['uname'];
		$upass = $_POST['upass'];
		$remember = $_POST['remember'];

		$userdata = $this->user_model->validate($uname, md5($upass), $ip_add);
		switch($userdata){
			case "0":
				echo 0;
				break;
			default:
				$data['user_log_min'] = 0;
				$this->session->set_userdata($data);
				echo 1;
				break;
		}
		$data['remember'] = $remember; 
		$this->session->set_userdata($data);
		// if($uname == $username && $upass == $password){
			
			
		// }else{
		// 	echo 0;
		// }
	}
	// function sample(){
		
	// 	$str = file_get_contents(base_url().'js/users.json');
	// 	$json = json_decode($str,true);

	// 	echo $json;

	// 	$user_log_str = file_get_contents(base_url().'js/users_log.json');
	// 	$user_log_json = json_decode($user_log_str,true);

	// 	$output = "";
	// 	foreach ($user_log_json['users'] as $log_key => $log_entry) {
	// 		    if ($log_entry['date_log'] !== date('Y-m-d')) {
	// 		    	$user_id =  $user_log_json['users'][$log_key]['user_id'];
	// 		        $user_log_json['users'][$log_key]['date_log'] = "";

	// 		        $str = file_get_contents(base_url().'js/users.json');
	// 				$json = json_decode($str,true);

	// 				//echo $str;
	// 		  //       foreach ($json['users'] as $key => $entry) {
	// 			 //      	if ($entry['user_id'] == $user_id) {
	// 			 //         	$json['users'][$key]['login_stat'] = 0;
	// 				// //         $this->user_model->log_out($user_id);
	// 				//     }
	// 				//     echo $json['users'][$key]['user_id'].'/';
	// 				// }

	// 				// file_put_contents('./js/users.json',json_encode($json));
	// 		    }else{
	// 		    	$user_id =  $user_log_json['users'][$log_key]['user_id'];

	// 		    	$timeFirst  = strtotime(date('Y-m-d H:i:s'));
	// 				$timeSecond = strtotime(date('Y-m-d').' '.$log_entry['time_log']);
	// 				$differenceInSeconds = $timeFirst - $timeSecond;
	// 		    	$diffinmin = $differenceInSeconds / 60;

	// 		   //  	if($diffinmin > 15){
	// 		   //  		$str = file_get_contents(base_url().'js/users.json');
	// 					// $json = json_decode($str,true);


	// 			  //       foreach ($json['users'] as $key => $entry) {
	// 					//     if ($entry['user_id'] == $user_id) {
	// 					//         $json['users'][$key]['login_stat'] = 0;
	// 					//         $this->user_model->log_out($user_id);
	// 					//     }
	// 					// }

	// 					// file_put_contents('./js/users.json',json_encode($json));
	// 		   //  	}
	// 		    }
	// 		}

	// }

	function check_user_if_remembered(){
		$remember = $this->session->userdata('remember');
		echo $remember;
	}
}

?>