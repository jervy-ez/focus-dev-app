<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set("Australia/Perth");
class Users extends MY_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->model('user_model');		
		$this->load->library('form_validation');
		$this->load->library('upload');
		$this->load->helper('cookie');

 //echo $this->input->cookie('user_id', false);



	}
	
	function index(){
		//$data["users"] = $this->user_model->read();
		
		if(!$this->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;

		if($this->session->userdata('is_admin') != 1 ):		
			redirect('', 'refresh');
		endif;

		$fetch_user= $this->user_model->fetch_user();
		$data['users'] = $fetch_user->result();
		$data['main_content'] = 'users';
		$data['screen'] = 'User Accounts';
		$this->load->view('page', $data);		

	}

	public function user_logs(){
		$order = 'ORDER BY `users`.`user_first_name` ASC';
		$data['users_q'] = $this->user_model->fetch_login_user($order);
		$user_logs = $this->user_model->fetch_user_logs();
		$data['user_logs'] = $user_logs;
		$data['main_content'] = 'user_logs';
		$data['screen'] = 'User Logs';
		$this->load->view('page', $data);

	}

	public function account(){
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
/*
		$access = $this->user_model->fetch_all_access();
		$data['all_access'] = $access->result();
*/
		$user_id = $this->uri->segment(3);

		$fetch_user= $this->user_model->fetch_user($user_id);
		$data['user'] = $fetch_user->result();


		if($this->input->post('update_password')){

			$this->form_validation->set_rules('current_password', 'Current Password','trim|required|xss_clean');
			$this->form_validation->set_rules('new_password', 'New Password','trim|required|xss_clean');
			$this->form_validation->set_rules('confirm_password', 'Confirm Password','trim|required|xss_clean');

			if($this->form_validation->run() === false){
				$data['error'] = validation_errors();

			}else{

				$current_password_raw = $this->input->post('current_password', true);
				$current_password = md5($current_password_raw);

				$new_password = $this->input->post('new_password', true);
				$confirm_password = $this->input->post('confirm_password', true);

				if($new_password == $confirm_password){

					$new_password_md = md5($new_password);

					$this->user_model->change_user_password($new_password_md,$user_id);
					$data['user_password_updated'] = 'Your password is now changed';

				}else{
					$data['error'] = 'New Password and Confirm Password did not match';
				}

			}

		}


		$this->load->view('page', $data);


		if(isset($_POST['is_form_submit'])){
			$first_name = $this->company->cap_first_word($this->company->if_set($this->input->post('first_name', true)));
			$last_name = $this->company->cap_first_word($this->company->if_set($this->input->post('last_name', true)));
			$gender = $this->company->cap_first_word($this->company->if_set($this->input->post('gender', true)));
			$dob = $this->company->if_set($this->input->post('dob', true));
			$login_name = $this->company->if_set($this->input->post('login_name', true));

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

			if($file_upload_arr[0] == 'success'){
				$profile = $file_upload_arr[1];
			}else{
				$profile = $profile_raw;
				$data['upload_error'] = $file_upload_arr[1];
			}

			if(isset($_POST['upt_chk_is_admin'])){
				$admin = 1;
			}else{
				$admin = 0;
			}

			$this->user_model->update_user_details($user_id,$login_name,$first_name,$last_name,$role_id,$skype_id,$skype_password,$gender,$dob,$department_id,$focus_id,$user_comments_id,$profile,$admin);

			$this->user_model->update_contact_email($email_id,$email,$contact_number_id,$direct_landline,$mobile_number,$after_hours);
			 
			redirect($this->uri->uri_string());

		}

	}

	function _upload_primary_photo($fileToUpload,$dir){
		$data['upload_error'] = '';

		$config['upload_path'] = './uploads/'.$dir.'/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_size']	= '1024';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';

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

	public function add(){
		if(!$this->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;

		if($this->session->userdata('is_admin') != 1 ):		
			redirect('', 'refresh');
		endif;

		$this->load->module('admin');
		$this->load->module('company');


		$departments = $this->user_model->fetch_all_departments();
		$data['departments'] = $departments->result();

		$roles = $this->user_model->fetch_all_roles();
		$data['roles'] = $roles->result();

		$focus = $this->admin_m->fetch_all_company_focus();
		$data['focus'] = $focus->result();
/*
		$access = $this->user_model->fetch_all_access();
		$data['all_access'] = $access->result();
*/

		$this->form_validation->set_rules('first_name', 'First Name','trim|required|xss_clean');
		$this->form_validation->set_rules('last_name', 'Last Name','trim|required|xss_clean');
		$this->form_validation->set_rules('gender', 'Gender','trim|required|xss_clean');
		$this->form_validation->set_rules('dob', 'Date of Birth','trim|required|xss_clean');
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

			$comments = $this->company->cap_first_word_sentence($this->company->if_set($this->input->post('comments', true)));


			if($comments){
				$user_notes_id = $this->company_m->insert_notes($comments);
			}else{
				$user_notes_id = 0;
			}

			$contact_number_id = $this->company_m->insert_contact_number('','',$direct_landline,$mobile_number,$after_hours);
			
			$email_id = $this->company_m->insert_email($email);
/*
			$access_raw = $this->input->post('access',true);
			$access = '';
			foreach ($access_raw as $key => $value) {
				$accss_arr = explode('|', $value);
				$access .= $accss_arr[0].',';
			}
			$access_id = substr($access, 0, -1);
*/
			date_default_timezone_set("Australia/Perth");
			$user_timestamp_registered = time();
			if(isset($_POST['chk_is_admin'])){
				$admin = 1;
			}else{
				$admin = 0;
			}
			$add_new_user_id = $this->user_model->add_new_user($login_name,$password,$first_name,$last_name,$gender,$department_id,$profile_photo,$user_timestamp_registered,$role_id,$email_id,$skype_id,$skype_password,$contact_number_id,$focus_id,$dob,$user_notes_id,$admin);

			$new_user_success = 'The user is now added.';
			$this->session->set_flashdata('new_user_success', $new_user_success);

			redirect('/users');
		}

	}


	function signin(){
		//Redirect
		if($this->_is_logged_in()){
			redirect('projects'); //dashboard
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

  					$str = file_get_contents(base_url().'js/users.json');
					$json = json_decode($str,true);

					foreach ($json['users'] as $key => $entry) {
					    if ($entry['user_id'] == $userdata->user_id) {
					        $json['users'][$key]['login_stat'] = 1;
					    }
					}

					file_put_contents('./js/users.json',json_encode($json));

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
					redirect('');
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
	
	function logout(){
		$userid = $this->session->userdata('user_id');
		delete_cookie("user_id");

		$str = file_get_contents(base_url().'js/users.json');
		$json = json_decode($str,true);

		foreach ($json['users'] as $key => $entry) {
		    if ($entry['user_id'] == $userid) {
		        $json['users'][$key]['login_stat'] = 0;
		    }
		}

		file_put_contents('./js/users.json',json_encode($json));

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
				$str = file_get_contents(base_url().'js/users.json');
				$json = json_decode($str,true);
				$user_id = $this->session->userdata('user_id');
				if($user_id !== ""){
					$log_in_users = $this->user_model->fetch_user($user_id);
					foreach ($log_in_users->result_array() as $row)
					{
						
						$log_date = $row['date_log'];
						$time_log = $row['time_log'];
						$user_ipadd = $row['ip_address'];
						if ($log_date !== date('Y-m-d')) {
							
					        foreach ($json['users'] as $key => $entry) {
							    if ($entry['user_id'] == $user_id) {
							        $json['users'][$key]['login_stat'] = 0;
							        $this->user_model->log_out($user_id);
							        //if($ip_add == $user_ipadd){
							        	$this->session->sess_destroy();
							        	redirect('');
							        //}
							    }
							}

							
					    }else{

					    	$timeFirst  = strtotime(date('Y-m-d H:i:s'));
							$timeSecond = strtotime(date('Y-m-d').' '.$time_log);
							$differenceInSeconds = $timeFirst - $timeSecond;
					    	$diffinmin = $differenceInSeconds / 60;

					    	if($diffinmin > 14){

						        foreach ($json['users'] as $key => $entry) {
								    if ($entry['user_id'] == $user_id) {
								        $json['users'][$key]['login_stat'] = 0;
								        $this->user_model->log_out($user_id);
								       // if($ip_add == $user_ipadd){
								        	$this->session->sess_destroy();
								        	redirect('');
								        //}
								    }
								}
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
			redirect('../');
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
		$userid = $_POST['user_id'];
		$this->user_model->log_out($userid);

		$str = file_get_contents(base_url().'js/users.json');
		$json = json_decode($str,true);

		foreach ($json['users'] as $key => $entry) {
		    if ($entry['user_id'] == $userid) {
		        $json['users'][$key]['login_stat'] = 0;
		    }
		}

		file_put_contents('./js/users.json',json_encode($json));

		$str = file_get_contents(base_url().'js/users.json');
		$json = json_decode($str,true);
		$admin = $this->session->userdata('is_admin');
		$users=$json['users'];
		echo '<ul style = "list-style-type: none">';
		foreach($users as $user)
		{
			if($user['login_stat'] == 1){
				if($admin == 1){
					echo '<li><img src = "./uploads/users/'.$user['user_img'].'" style = "width: 30px">'." ".$user['user_name'].'<button type = "button" class = "btn btn-danger btn-xs pull-right" onclick = "btn_logout_user('.$user['user_id'].')"><i class = "fa fa-sign-out fa-sm"></i></button></li>';
				}else{
					echo '<li><img src = "./uploads/users/'.$user['user_img'].'" style = "width: 30px">'." ".$user['user_name'].'</li>';
				}
			}
		}
		echo '</ul>';
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
		$str = file_get_contents(base_url().'js/users.json');
		$json = json_decode($str,true);
		$admin = $this->session->userdata('is_admin');
		$users=$json['users'];
		echo '<ul style = "list-style-type: none">';
		foreach($users as $user)
		{
			if($user['login_stat'] == 1){
				if($admin == 1){
					echo '<li><img src = "'.base_url().'uploads/users/'.$user['user_img'].'" style = "width: 30px">'." ".$user['user_name'].'<button type = "button" title = "Log-out User" class = "btn btn-danger btn-xs pull-right" onclick = "btn_logout_user('.$user['user_id'].')"><i class = "fa fa-sign-out fa-sm"></i></button></li>';
				}else{
					echo '<li><img src = "'.base_url().'uploads/users/'.$user['user_img'].'" style = "width: 30px">'." ".$user['user_name'].'</li>';
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