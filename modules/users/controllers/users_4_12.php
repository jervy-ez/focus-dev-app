<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set("Australia/Perth");
require('PHPMailer/class.phpmailer.php');
require('PHPMailer/PHPMailerAutoload.php');

require_once('dompdf/dompdf_config.inc.php');
spl_autoload_register('DOMPDF_autoload');

class Users extends MY_Controller{
	function __construct(){
		parent::__construct();
		$this->load->model('user_model');		
		$this->load->library('form_validation');
		$this->load->library('upload');
		$this->load->helper('cookie');
		$this->load->helper('file');

		$this->load->library('user_agent');  // load user agent library

    //Set session for the referrer url
	 $this->session->set_userdata('referrer_url', $this->agent->referrer() ); 


	}

 
 
	public function loop_compamy_group($company_id = ''){
		
	//	if($company_id != ''){


			$admin_company_details = $this->user_model->fetch_company_group($company_id,1);
			$data = array_shift($admin_company_details->result_array() );

			echo '<div class="col-lg-offset-4 col-lg-4 col-md-6 col-md-offset-3 col-xs-12 box-widget" >
				<div class="box wid-type-'.$data['admin_company_details_id'].'_comp_group" style="    border-radius: 22px !important;">
					<div class="widg-head box-widg-head pad-5">'.$data['state_name'].'<span class="sub-h pull-right"></span></div>							
					<div class=" pad-5 text-center m-bottom-10">';

					if($this->session->userdata('is_admin') ==  1){
						echo '<a href="'.base_url().'admin/admin_company/'.$data['admin_company_details_id'].'" class="" id=""><h3>'.$data['company_name'].'</h3></a>';
					}else{
						echo '<h3>'.$data['company_name'].'</h3>';
					}

					echo'</div>
				</div>
			</div>';

			echo '<div class="clearfix"></div>';


			echo '<style type="text/css">

				.wid-type-'.$data['admin_company_details_id'].'_comp_group .widg-head{
					background: #3EC4F7;
					color: #fff;
				}

				.wid-type-'.$data['admin_company_details_id'].'_comp_group{
					background: #00ADEF;
				}

			</style>';

	//	}else{


echo '<div id="" class="col-lg-2"><p>&nbsp;</p></div>';

			$admin_company_group_q = $this->user_model->fetch_company_group($company_id);


				if($admin_company_group_q->num_rows){

			$admin_company_group = $admin_company_group_q->result();


			foreach($admin_company_group as $key => $comp){



				echo '<div class=" col-lg-4 col-md-4 col-xs-12 box-widget" >
				<div class="box wid-type-'.$comp->admin_company_details_id.'_comp_group" style="    border-radius: 22px !important;">
					<div class="widg-head box-widg-head pad-5">'.$comp->state_name.'<span class="sub-h pull-right"></span></div>							
					<div class=" pad-5 text-center m-bottom-10">';



					if($this->session->userdata('is_admin') ==  1){
						echo '<a href="'.base_url().'admin/admin_company/'.$comp->admin_company_details_id.'" class="" id=""><h3>'.$comp->company_name.'</h3></a>';
					}else{
						echo '<h3>'.$comp->company_name.'</h3>';
					}



						
					echo'</div>
				</div>
			</div>'; 


			


 

			}

			}



	//	}

	}






	public function company_matrix(){
		if(!$this->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;

		$this->_check_user_access('users',1);


		$this->load->module('admin');
		$all_focus_company = $this->admin_m->fetch_all_company_focus();
		$data['all_focus_company'] = $all_focus_company->result();



		$data['main_content'] = 'matrix';
		$data['screen'] = 'FSF Group Sojourn';

		$data['page_title'] = 'Organizational Chart';

		$this->load->view('page', $data);
	}

 
 
 
	public function loop_user_supervisor($supervisor_id = '',$ext='',$pm_ids=''){





		$is_gray = '';

		if($supervisor_id == ''){
			$supervisor_id = 3;
			//$pm_ids = 3;
			$ext = '<div id="" class="default_blank blanks box-area  pad-10 m-10 pull-left" style=""></div>';

			$user_access_q = $this->user_model->fetch_user($supervisor_id);
			$user = array_shift($user_access_q->result_array());



			echo '<div class="box-area photo_frame pad-5 text-left"><div id="" class="user_'.$user['user_focus_company_id'].'_comp_group" style="border-radius: 60px !important;     margin: 0px 10px 0px 0px;    width: 60px;    height: 60px;    float: left;    overflow: hidden; ">
			<div style="float: left; overflow: hidden; height: 60px; "><a class="text-center" href="'.base_url().'users/account/'.$user['user_id'].'">';

				if($user['user_profile_photo'] == ''):

	 	echo '	<i class="m-left-10 fa fa-user fa-4x "></i>';
															

	 else: 

	 	echo '<img src="'.base_url().'uploads/users/'.$user['user_profile_photo'].'" style="width: 60px;   ">';
			endif;

			echo'</a></div></div>
																							
																<p><strong>'.$user['role_types'].'</strong></p>
																<p><a href="'.base_url().'users/account/'.$user['user_id'].'">'.$user['user_first_name'].' '.$user['user_last_name'].'</a></p>
																	<hr style="margin: 20px 0 0;">
															</div>';
	//	echo $ext;
		}	


		$fetch_user_sups_q= $this->user_model->fetch_users_under_supervisor($supervisor_id);
		$fetch_user_sups = $fetch_user_sups_q->result();



		foreach($fetch_user_sups as $key => $value){
 
	$div_tag = $pm_ids.'_'.$supervisor_id.'_'.$value->user_id;
 
// var_dump($pm_ids);
// var_dump($supervisor_id);


//echo $value->user_id.$ext.$value->user_first_name.'**'.$div_tag.'<br />';

$pm_group_arr = explode('_', $div_tag);
//unset($pm_group_arr[0]);

//array_filter($pm_group_arr);
$group_pms = implode(' direp_', $pm_group_arr);





			if($value->is_third_party == 1){
				$is_gray = 'gray_color';
			}else{
				$is_gray = '';
			}
 
//'.base_url('users/account/'.$value->user_id).'




			echo $ext.'
															<div class="box-area pad-5 text-left direp_'.$group_pms.' diruser_'.$value->user_id.'">
																<div id="" class="user_'.$value->user_focus_company_id.'_comp_group '.$is_gray.'" style="border-radius: 60px !important;     margin: 0px 10px 0px 0px;    width: 60px;    height: 60px;    float: left;    overflow: hidden; "><div style="float: left; overflow: hidden; height: 60px; ">
																															<div class="text-center user_dir pointer" id="user_'.$value->user_id.'"  >		';


if($value->user_profile_photo == ''):
	 	echo '	<i class="m-left-10 fa fa-user fa-4x "></i>';								
else: 
	 	echo '<img src="'.base_url().'uploads/users/'.$value->user_profile_photo.'" style="width: 60px;  ">';
endif;
																																	
																															

																																echo '</div></div></div>
																							
																<p><strong>'.$value->role_types.'</strong></p>
																<p> <a href="'.base_url('users/account/'.$value->user_id).'" class="m-left-5">'.$value->user_first_name.' '.$value->user_last_name.'</a></p>
																<hr style="margin: 20px 0 0;">';





															echo '</div>';


			$this->users->loop_user_supervisor($value->user_id,$ext.'<div id="" class="blanks box-area  pad-10 m-10 pull-left direp_'.$group_pms.'" style=""></div>',$supervisor_id.'_'.$pm_ids);







		}

	}
	


	
	function index(){
		//$data["users"] = $this->user_model->read();
		
		if(!$this->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;

		$this->_check_user_access('users',1);
 
		$this->calc_leave_points();

		$fetch_user= $this->user_model->fetch_user();
		$data['users'] = $fetch_user->result();
		$data['main_content'] = 'users';
		$data['screen'] = 'FSF Group Sojourn Users';

		$data['page_title'] = 'FSF Group Sojourn Users';

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
		$project_schedule = $_POST['project_schedule'];
		$labour_schedule = $_POST['labour_schedule'];
		$company_project = $_POST['company_project'];
		$shopping_center = $_POST['shopping_center'];

		$site_labour = $_POST['site_labour'];
		$site_labour_app = $_POST['site_labour_app'];
		$quick_quote = $_POST['quick_quote'];
		$quote_deadline = $_POST['quote_deadline'];
		$leave_requests = $_POST['leave_requests'];
		$job_date_access = $_POST['job_date_access'];
		$purchase_order_access = $_POST['purchase_order_access'];
		$progress_report_set = $_POST['progress_report_set'];
		$onboarding_set = $_POST['onboarding_set'];

		$role_raw = $_POST['role'];
		$role_arr = explode('|',$role_raw);
		$role_id = $role_arr[0];

		//echo "$user_id,$is_admin,$dashboard,$company,$projects,$wip,$purchase_orders,$invoice,$users";

		$this->user_model->update_user_access($user_id,$is_admin,$dashboard,$company,$projects,$wip,$purchase_orders,$invoice,$users,$role_id,$bulletin_board,$project_schedule,$labour_schedule,$company_project,$shopping_center,$site_labour,$site_labour_app,$quick_quote,$quote_deadline,$leave_requests,$job_date_access,$purchase_order_access, $progress_report_set, $onboarding_set);
		$this->session->set_flashdata('user_access', 'User Access is now updated.');


	redirect('/users/account/'.$user_id);
	}


	public function update_projects_default_view(){
		$this->clear_apost();
		$user_id = $_POST['user_id'];
		$projects_load_view = $_POST['projects_load_view'];

		$this->user_model->update_projects_dv($user_id,$projects_load_view);

		//var_dump($companies);
		$this->session->set_flashdata('user_access', 'Default projects landing page is updated.');
		redirect('/users/account/'.$user_id);
	}

	public function update_projects_pv(){
		$this->clear_apost();
		$user_id = $_POST['user_id'];
		$projects_load_view_personal = $_POST['projects_load_view_personal'];

		$this->user_model->update_projects_pv($user_id,$projects_load_view_personal);

		//var_dump($companies);
		$this->session->set_flashdata('user_access', 'Default projects view personal projects is updated.');
		redirect('/users/account/'.$user_id);
	}



	public function update_company_director(){
		$this->clear_apost();
		$user_id = $_POST['user_id'];

		

		if( isset($_POST['fcompd']) ) {
			$fcompd = $_POST['fcompd'];
			$companies = "'".implode(',', $fcompd)."'";
		}else{
			$companies = 'NULL';
		}


		$this->user_model->update_company_director($user_id,$companies);

		//var_dump($companies);
		$this->session->set_flashdata('user_access', 'Director Company is now updated');
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

		$static_defaults = $this->user_model->select_static_defaults();
		$data['static_defaults'] = $static_defaults->result();

		$data['error'] = '';
		$data['upload_error'] = '';

		
/*
		$access = $this->user_model->fetch_all_access();
		$data['all_access'] = $access->result();
*/
		$user_list = $this->user_model->list_user_short();
		$data['user_list'] = $user_list->result();

		$user_id = $this->uri->segment(3);

		$used_annual_total = $this->user_model->get_total_leave_annual($user_id);
		$data['used_annual_total'] = $used_annual_total->row();

		$used_personal_total = $this->user_model->get_total_leave_personal($user_id);
		$data['used_personal_total'] = $used_personal_total->row();

		$q_annual_ph_holidays = $this->user_model->get_annual_ph_holidays($user_id);
		$data['annual_ph_holidays'] = $q_annual_ph_holidays->row();

		$q_sick_ph_holidays = $this->user_model->get_sick_ph_holidays($user_id);
		$data['sick_ph_holidays'] = $q_sick_ph_holidays->row();

		$fetch_user = $this->user_model->fetch_user($user_id);
		$data['user'] = $fetch_user->result();

		$this->calc_leave_points();

		if( $data['user'][0]->is_active == 0){
			redirect('users', 'refresh');
		}

		$data['direct_company'] = $data['user'][0]->direct_company;
		
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
 

					if ( !class_exists("PHPMailer") ){
						require('PHPMailer/class.phpmailer.php'); 
						//
					}

					

		// PHPMailer class
		$user_mail = new PHPMailer;
		//$user_mail->SMTPDebug = 3;                               		// Enable verbose debug output
	//	$user_mail->isSMTP();                                      		// Set mailer to use SMTP
		$user_mail->Host = 'sojourn-focusshopfit-com-au.mail.protection.outlook.com';  		  		// Specify main and backup SMTP servers
		//$user_mail->SMTPAuth = true;                               		// Enable SMTP authentication
		//$user_mail->Username = 'invoice@sojourn.focusshopfit.com.au';   	// SMTP username
		//$user_mail->Password = '~A8vVJRLz(^]J)L>';                       // SMTP password
		//$user_mail->SMTPSecure = 'ssl';                            		// Enable TLS encryption, `ssl` also accepted
		$user_mail->Port = 587;    
		// PHPMailer class 

		$user_mail->setFrom('no-reply@sojourn.focusshopfit.com.au', 'Sojourn - Accounts');
		$user_mail->addAddress($send_to);    // Add a recipient
		//$user_mail->addAddress($user_email);               // Name is optional

		$user_mail->addReplyTo('no-reply@sojourn.focusshopfit.com.au', 'Sojourn - Accounts');
 
		$user_mail->isHTML(true);                                  // Set email format to HTML

		$year = date('Y');

		$user_mail->Subject = 'Password Change';
		$user_mail->Body    = "Do not reply in this email.<br /><br />Congratulations!<br /><br />Your New Password is : ****".substr($new_password,4)."<br /><br />&copy; FSF Group ".$year;

		if(!$user_mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $user_mail->ErrorInfo;
		} else {
			//echo 'Message has been sent';
			redirect('users/account/'.$user_id, 'refresh');
		}







				}else{
					$data['error'] = 'New Password and Confirm Password did not match';
				}

			}

		}



		if(isset($_POST['is_form_submit'])){

			$user_info = array_shift($fetch_user->result_array());


			$first_name = $this->company->cap_first_word($this->company->if_set($this->input->post('first_name', true)));
			$last_name = $this->company->cap_first_word($this->company->if_set($this->input->post('last_name', true)));
			$gender = $this->company->cap_first_word($this->company->if_set($this->input->post('gender', true)));
			$dob = $this->company->if_set($this->input->post('dob', true));
			$login_name = $this->company->if_set($this->input->post('login_name', true));
			$is_offshore = $this->input->post('is_offshore', true);
			$contractor_employee = $this->input->post('contractor_employee', true);
			$site_staff = $this->input->post('site_staff', true);
			$gi_date = $this->input->post('gi_date', true);

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
			$personal_mobile_number = $this->company->if_set($this->input->post('personal_mobile_number', true));
			$email = $this->company->if_set($this->input->post('email', true));
			$personal_email = $this->company->if_set($this->input->post('personal_email', true));
			$comments = $this->company->cap_first_word_sentence($this->company->if_set($this->input->post('comments', true)));
			$contact_number_id = $this->input->post('contact_number_id', true);
			$email_id = $this->input->post('email_id', true);
			$user_comments_id = $this->input->post('user_comments_id', true);
 
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

			if($department_id != 1){
				$this->user_model->update_company_director($user_id,'NULL');
			}

			$supervisor_id = $this->input->post('supervisor', true);

			$this->session->set_flashdata('account_update_msg', 'User account is now updated.');

			$this->user_model->update_user_details($user_id,$login_name,$first_name,$last_name,$skype_id,$skype_password,$gender,$dob,$department_id,$is_offshore,$focus_id,$user_comments_id,$profile,$supervisor_id,$contractor_employee,$site_staff,$gi_date);

			$this->user_model->update_contact_email($email_id,$email,$contact_number_id,$direct_landline,$mobile_number,$after_hours,$personal_mobile_number,$personal_email);

			redirect($this->uri->uri_string(),'refresh');

			

		}

		$data['page_title'] = 'Account Details: '.$data['user'][0]->user_first_name.' '.$data['user'][0]->user_last_name;

		$this->load->view('page', $data);

	}

	public function get_user_access($user_id){
		$user_access_q = $this->user_model->fetch_all_access($user_id);
		$user_access_arr = array_shift($user_access_q->result_array());
		return implode(',', $user_access_arr);
	}
	
	
	 

	public function resend_email(){

		$user_id = $this->uri->segment(3);

		$user_details_q = $this->user_model->fetch_user($user_id);
		$user_details = array_shift($user_details_q->result_array());

		$user_password_q = $this->user_model->get_latest_user_password($user_id);
		$user_password = array_shift($user_password_q->result_array());

// PHPMailer class
		$user_mail = new PHPMailer;
		//$user_mail->SMTPDebug = 3;                               		// Enable verbose debug output
	//	$user_mail->isSMTP();                                      		// Set mailer to use SMTP
		$user_mail->Host = 'sojourn-focusshopfit-com-au.mail.protection.outlook.com';  		  		// Specify main and backup SMTP servers
	//	$user_mail->SMTPAuth = true;                               		// Enable SMTP authentication
	//	$user_mail->Username = 'userconf@sojourn.focusshopfit.com.au';   	// SMTP username
	//	$user_mail->Password = 'wzVrX6sxcpXR%{jh';                       // SMTP password
	//	$user_mail->SMTPSecure = 'ssl';                            		// Enable TLS encryption, `ssl` also accepted
		$user_mail->Port = 587;    
		// PHPMailer class 

		$user_mail->setFrom('donot-reply@sojourn.focusshopfit.com.au', 'Sojourn - Accounts');
		$user_mail->addAddress($user_details['general_email']);    // Add a recipient

		$user_mail->addReplyTo('donot-reply@sojourn.focusshopfit.com.au', 'Sojourn - Accounts');
		
		$user_mail->isHTML(true);                                  // Set email format to HTML
		$year = date('Y');
		$user_mail->Subject = 'Account Details';

		//$user_details

		$user_mail->Body = 'Do not reply in this email.<br /><br />Welcome '.$user_details['user_first_name'].' to Sojourn, an FSF Group Project Management Application.<br /><br />';
		$user_mail->Body .= ' You have been added as a new user and provided with a temporary password.  Please sign-in right away where you will be required to change your password, then you will need to sign in again using your username & changed password. ';
		$user_mail->Body .= ' Use the link below.<br /><br /><a href="'.base_url().'" target="_blank">'.base_url().'</a><br /><br />Your User Name is : '.$user_details['login_name'].' and Password is : '.$user_password['password'].'<br /><br />';
		$user_mail->Body .= ' If you go to the USER section of the site you can personalise your settings and complete your set up.<br /><br />&copy; FSF Group '.$year;

		if(!$user_mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $user_mail->ErrorInfo;
		}  
	}

 




	function _upload_primary_photo($fileToUpload,$dir,$name_pref='user_'){
		$data['upload_error'] = '';

		$config['upload_path'] = './uploads/'.$dir.'/';
		$config['allowed_types'] = 'png|gif|jpg|jpeg|pjpeg|x-png';
		$config['max_size']	= '0';
		$config['max_width']  = '0';
		$config['max_height']  = '0';

		$time = mdate("%h%i%s%m%d%Y", time());
		$config['file_name']  = $name_pref.$time;

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

		$fetch_user= $this->user_model->list_user_short();
		$data['users'] = $fetch_user->result();

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$data['project_manager'] = $project_manager->result();
		
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
		$this->form_validation->set_rules('personal_mobile_number', 'Personal Mobile Number','trim|xss_clean');
		$this->form_validation->set_rules('email', 'Email','trim|required|xss_clean');
		$this->form_validation->set_rules('personal_email', 'Personal Email','trim|xss_clean');
		$this->form_validation->set_rules('skype_id', 'Skype ID','trim|required|xss_clean');
		$this->form_validation->set_rules('comments', 'Comments','trim|xss_clean');
		$this->form_validation->set_rules('super_visor', 'Direct Reports','trim|required|xss_clean');
		$this->form_validation->set_rules('is_offshore', 'Offshore Employee','trim|required|xss_clean');
		$this->form_validation->set_rules('contractor_employee', 'Contractor Employee','trim|required|xss_clean');




		$data['main_content'] = 'new_user';
		$data['screen'] = 'New User';


		$role_raw = $this->input->post('role', true);
		$role_arr = explode('|',$role_raw);
		$role_id = $role_arr[0];

		if($role_id == 2){
			$this->form_validation->set_rules('pm_for_pa', 'Primary PM','trim|required|xss_clean');
		}

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
				//$data['upload_error'] = $file_upload_arr[1];
			}

			$site_select = $this->input->post('site_select', true);
			$first_name = $this->company->cap_first_word($this->company->if_set($this->input->post('first_name', true)));
			$last_name = $this->company->cap_first_word($this->company->if_set($this->input->post('last_name', true)));
			$gender = $this->company->cap_first_word($this->company->if_set($this->input->post('gender', true)));
			$dob = $this->company->if_set($this->input->post('dob', true));
			$login_name = $this->company->if_set($this->input->post('login_name', true));

			$confirm_password = $this->company->if_set($this->input->post('confirm_password', true));

			$supervisor_id = $this->company->if_set($this->input->post('super_visor', true));
			$is_offshore = $this->company->if_set($this->input->post('is_offshore', true));

			$password = $this->company->if_set($this->input->post('password', true));
			$password = md5($password);

			$department_raw = $this->input->post('department', true);
			$department_arr = explode('|',$department_raw);
			$department_id = $department_arr[0];

			$focus_raw = $this->input->post('focus', true);
			$focus_arr = explode('|',$focus_raw);
			$focus_id = $focus_arr[0];


			$skype_id = $this->company->if_set($this->input->post('skype_id', true));
			$skype_password = $this->input->post('skype_password', true);

			$direct_landline = $this->company->if_set($this->input->post('direct_landline', true));
			$after_hours = $this->company->if_set($this->input->post('after_hours', true));
			$mobile_number = $this->company->if_set($this->input->post('mobile_number', true));
			$personal_mobile_number = $this->company->if_set($this->input->post('personal_mobile_number', true));
			$email = $this->company->if_set($this->input->post('email', true));
			$personal_email = $this->company->if_set($this->input->post('personal_email', true));

			$days_exp = $this->company->if_set($this->input->post('days_exp', true));

			$comments = $this->company->cap_first_word_sentence($this->company->if_set($this->input->post('comments', true)));


			if($comments){
				$user_notes_id = $this->company_m->insert_notes($comments);
			}else{
				$user_notes_id = 0;
			}

			$contact_number_id = $this->company_m->insert_contact_number('','',$direct_landline,$mobile_number,$after_hours,$personal_mobile_number);
			
			$email_id = $this->company_m->insert_email($email,$personal_email);

			$dashboard_access = $this->input->post('dashboard_access', true);
			$company_access = $this->input->post('company_access', true);
			$projects_access = $this->input->post('projects_access', true);
			$wip_access = $this->input->post('wip_access', true);
			$purchase_orders_access = $this->input->post('purchase_orders_access', true);
			$invoice_access = $this->input->post('invoice_access', true);
			$users_access = $this->input->post('users_access', true);
			$bulletin_board = $this->input->post('bulletin_board', true);
			$project_schedule = $this->input->post('project_schedule', true);
			$labour_schedule = $this->input->post('labour_schedule', true);
			$leave_requests = $this->input->post('leave_requests', true);
			$job_date_access = $this->input->post('job_date_access', true);
			$progress_report_set = $this->input->post('progress_report_set', true);
			$contractor_employee = $this->input->post('contractor_employee', true);



			date_default_timezone_set("Australia/Perth");
			$user_timestamp_registered = time();


			if($this->session->userdata('is_admin') ==  1){
				$admin = (isset($_POST['chk_is_peon']) && $_POST['chk_is_peon'] == 1 ? 1 : 0);
			}else{
				$admin = 0;
			}			

			$add_new_user_id = $this->user_model->add_new_user($login_name,$password,$first_name,$last_name,$gender,$department_id,$profile_photo,$user_timestamp_registered,$role_id,$email_id,$skype_id,$skype_password,$contact_number_id,$focus_id,$dob,$user_notes_id,$admin,$site_select,$contractor_employee,$is_offshore);

			$this->user_model->insert_user_access($add_new_user_id,$dashboard_access,$company_access,$projects_access,$wip_access,$purchase_orders_access,$invoice_access,$users_access,$bulletin_board,$project_schedule,$labour_schedule,$leave_requests,$job_date_access,$progress_report_set);

			$this->user_model->insert_user_password($confirm_password,$add_new_user_id);

			if($role_id == 2){
				$pm_id = $this->company->if_set($this->input->post('pm_for_pa', true));
				$this->admin_m->pm_pa_assignment($add_new_user_id, $pm_id, $pm_id);
			}

			$this->user_model->update_user_supervisor($add_new_user_id,$supervisor_id);

			$new_user_success = 'The user is now added.';
			$this->session->set_flashdata('new_user_success', $new_user_success);

			$send_to = $email;


// PHPMailer class
		$user_mail = new PHPMailer;
		//$user_mail->SMTPDebug = 3;                               		// Enable verbose debug output
	//	$user_mail->isSMTP();                                      		// Set mailer to use SMTP
		$user_mail->Host = 'sojourn-focusshopfit-com-au.mail.protection.outlook.com';  		  		// Specify main and backup SMTP servers
	//	$user_mail->SMTPAuth = true;                               		// Enable SMTP authentication
	//	$user_mail->Username = 'userconf@sojourn.focusshopfit.com.au';   	// SMTP username
	//	$user_mail->Password = 'wzVrX6sxcpXR%{jh';                       // SMTP password
	//	$user_mail->SMTPSecure = 'ssl';                            		// Enable TLS encryption, `ssl` also accepted
		$user_mail->Port = 587;    
		// PHPMailer class 

		$user_mail->setFrom('no-reply@sojourn.focusshopfit.com.au', 'Sojourn - Accounts');
		$user_mail->addAddress($send_to);    // Add a recipient

		$user_mail->addReplyTo('no-reply@sojourn.focusshopfit.com.au', 'Sojourn - Accounts');
		

		

		$user_mail->isHTML(true);                                  // Set email format to HTML

		$year = date('Y');

		$user_mail->Subject = 'Account Details';
		$user_mail->Body    = 'Do not reply in this email.<br /><br />Welcome '.$first_name.' to Sojourn, an FSF Group Project Management Application.<br /><br />You have been added as a new user and provided with a temporary password.  Please sign-in right away where you will be required to change your password, then you will need to sign in again using your username & changed password.  Use the link below.<br /><br /><a href="'.base_url().'" target="_blank">'.base_url().'</a><br /><br />Your User Name is : '.$login_name.' and Password is : '.$confirm_password.'<br /><br />If you go to the USER section of the site you can personalise your settings and complete your set up.<br /><br />&copy; FSF Group '.$year;

		if(!$user_mail->send()) {
			echo 'Message could not be sent.';
			echo 'Mailer Error: ' . $user_mail->ErrorInfo;
		} else {
			//echo 'Message has been sent';
 redirect('/users', 'refresh');
		}
















			
		}

	}



	public function display_login_bg(){

		$today = date("d/m/Y");
		$year = date("Y");

		$q_bgfile = $this->user_model->get_latest_bg($today,$year);

		if($q_bgfile->num_rows === 1){
			$img_bg = array_shift($q_bgfile->result_array() );

			return base_url()."uploads/login_bg/".$img_bg['file_name'];
		}else{



			$q_bgfile = $this->user_model->get_latest_bg($today,$year,1);
			$img_bg = array_shift($q_bgfile->result_array() );

			return base_url()."uploads/login_bg/".$img_bg['file_name'];

			//return base_url()."img/sojourn_bg_signin.png";
		}

	}


	function signin(){

	//	var_dump($this->session->userdata);
		$this->clear_apost();

		$data['page_title'] = 'Sign In';

	 $bg_photo = $this->display_login_bg();

	// echo $bg_photo;

	 $data['bg_file'] = $bg_photo;   
		//Redirect
		if($this->_is_logged_in()){
			$user_role_id = $this->session->userdata('user_role_id');

	if($this->session->userdata('dashboard') >= 1 ){	
					redirect('dashboard');
				}elseif($user_role_id == 21){
					redirect('users'); //dashboard
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
			

				$ip_add = $_SERVER['REMOTE_ADDR'];
				$data['ip_add'] = $ip_add;



				$userdata = $this->user_model->validate($user_name, md5($password), $ip_add);
 
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

					date_default_timezone_set("Australia/Perth"); 
					$time_stamp=time();
					$this->user_model->set_user_logged_time($userdata_session->user_id,$time_stamp);

					$data['signin_error'] = "User is already logged in on another pc, Do you want to log-off Previous logged-in Account? <button type = 'button' class = 'btn btn-success pull-right' id = 'log_out_prev_user'>Yes</button>";
					$data['main_content'] = 'signin';
					$this->load->view('page', $data);
					break;
				default:

					$this->_confirm_active_password($userdata->user_id,$password);

					date_default_timezone_set("Australia/Perth"); 
					$time_stamp=time();
					$this->user_model->set_user_logged_time($userdata->user_id,$time_stamp);

					$raw_user_access = $this->user_model->fetch_all_access($userdata->user_id);
					$user_access = array_shift($raw_user_access->result_array());
					$this->session->set_userdata($user_access);

					$data['user_id'] = $userdata->user_id;
					$data['user_role_id'] = $userdata->user_role_id;
					//$data['user_access_group_id'] = $userdata->user_access_group_id;
					$data['user_first_name'] = $userdata->user_first_name;
					$data['user_last_name'] = $userdata->user_last_name;
					$data['user_profile_photo'] = $userdata->user_profile_photo;
					$data['user_department_id'] = $userdata->user_department_id;
					$data['user_focus_company_id'] = $userdata->user_focus_company_id;
					$data['is_active'] = $userdata->is_active;
					$data['logged_in'] = true;
					$data['logged_in_user'] = 1;
					$data['is_admin'] = $userdata->if_admin;
					$data['user_name'] = $user_name;
					$data['password'] = $password;
					$data['role_types'] = $userdata->role_types;
					$data['user_site'] = $userdata->site_access;
					$data['user_supervisor_id'] = $userdata->supervisor_id;


					if( $userdata->is_active == 0){
						$this->logout_user();
						redirect('');
					}
				


					$this->session->set_userdata($data);

 

					$this->session->set_userdata('default_projects_landing', $userdata->projects_load_view ); 
					$this->session->set_userdata('default_projects_view_personal', $userdata->projects_load_view_personal ); 
 





					//if($user_id!=''){

					delete_cookie('user_id'); // dapat kasi ma reset an cookie very time

						$cookie = array(
							'name'   => 'user_id',
							'value'  => $userdata->user_id,
							'expire' => 86500 * 2, // dito nmn gumamit ka ng ' kapag kasi naka single quote hndi gagana ang multiplication mo, babsahin niya as text mas okay kung 17000 buo tuloy na value
							'secure' => false
							);
						$this->input->set_cookie($cookie);


					$date_log = date('Y-m-d');
					$time_log = date('H:i:s');
					$this->user_model->insert_user_min_log($userdata->user_id,$date_log,$time_log);



					$person_did = $userdata->user_id;
					$type = 'User Login';

					$date = date("d/m/Y");
					$time = date("H:i:s");

					$user_ip = $this->input->ip_address();
					$actions = 'Logged in IP '.$user_ip;

					$this->user_model->insert_user_log($person_did,$date,$time,$actions,'',$type,'8');
			        $this->_fetch_system_defaults();

			        $emails_user = array();
			        $q_po_review_users = $this->user_model->list_main_po_review(); 
			        foreach ($q_po_review_users->result_array() as $row){
			        	array_push($emails_user, $row['general_email']);
			        }

			        $send_to = implode(",", $emails_user);

			        $test_email = 'jervy@focusshopfit.com.au,michael@focusshopfit.com.au,ian@focusshopfit.com.au';

			        $test_email_arr = explode(",", $test_email);


				//	if( $userdata->user_role_id == 6){
					if( $userdata->user_id == 2){


						$mail = new PHPMailer;                                		
						$mail->Host = 'sojourn-focusshopfit-com-au.mail.protection.outlook.com';
						$mail->Port = 587;

						$mail->setFrom('noreply@focusshopfit.com.au', 'Sojourn Reminder - PO Review');
						$mail->addReplyTo('noreply@focusshopfit.com.au', 'Do Not Reply');

					//	$mail->addAddress($send_to);

						foreach ($test_email_arr as $key => $value) {
							$mail->addAddress($value);
						}



						$mail->isHTML(true);                               


						$content = '<p>Hi this is an automated email reminder for your purchase orders to settle in Sojourn.<br />You can click <a href="'.base_url().'purchase_order" target="_blank" title="Go to Sojourn - Purchase Orders"><strong>this link</strong></a> to go to the purchase order screen.';

						$mail->Subject = 'Sojourn Reminder - PO Review';
						$mail->Body    = $content.'<br /><br />Sent via Sojourn auto-email service, you have a purchase order that needs action.<br />Please log-in to Sojourn and apply the necessary changes as per details above.<br /><br />&copy; FSF Group ';

						if(!$mail->send()) {
							redirect('', 'refresh');
						} else {
							redirect('', 'refresh'); 
						}

					}else{
						redirect('', 'refresh'); 
					}




					break;
			}
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

					if($new_password == $confirm_password && $new_password != $this->session->userdata('re_pass_curr')){	

						$this->session->set_flashdata('new_pass_msg', 'You successfully changed your password. Try login your new password.');
						$this->user_model->change_user_password($new_password,$user_id,$static_defaults['days_psswrd_exp']);
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

		$person_did = $userid;
		$type = 'User Logout';

		$date = date("d/m/Y");
		$time = date("H:i:s");

		$user_ip = $this->input->ip_address();
		$actions = 'Logged in IP '.$user_ip;

		$this->user_model->insert_user_log($person_did,$date,$time,$actions,'',$type);
		delete_cookie("user_id");

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
			$output = true;			
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
	}



	public function set_availalbility_on_leave($leave_req_id=''){

		$fetch_leave_req_q = $this->user_model->fetch_leave_req($leave_req_id);
		$fetch_leave_req = array_shift($fetch_leave_req_q->result_array());

		$re_occur = 0;

		if($fetch_leave_req['partial_day'] == 1){

			if($fetch_leave_req['partial_part'] == 1){
				$date_a = date("Y-m-d", $fetch_leave_req['start_day_of_leave']   ).' 07:00 AM';
				$date_b = date("Y-m-d", $fetch_leave_req['end_day_of_leave']   ).' '.$fetch_leave_req['partial_time'];
			}

			if($fetch_leave_req['partial_part'] == 2){
				$date_a = date("Y-m-d", $fetch_leave_req['start_day_of_leave']   ).' '.$fetch_leave_req['partial_time'];
				$date_b = date("Y-m-d", $fetch_leave_req['end_day_of_leave']   ).' 05:00 PM';
			}

		}else{
			$date_a = date("Y-m-d", $fetch_leave_req['start_day_of_leave']   ).' 07:00:00am';
			$date_b = date("Y-m-d", $fetch_leave_req['end_day_of_leave']   ).' 05:00:00pm';

			if($fetch_leave_req['total_days_away'] - 8 > 0){
				$re_occur = 1;
			}
		}

		$details = $fetch_leave_req['details'];
//echo "$date_a<br />$date_b";


		if( $fetch_leave_req['leave_type_id'] == 1 ||  $fetch_leave_req['leave_type_id'] == 4 ||  $fetch_leave_req['leave_type_id'] == 5){
			$status = 'Leave';
		}

		if( $fetch_leave_req['leave_type_id'] == 2 || $fetch_leave_req['leave_type_id'] == 3){
			$status = 'Sick';
		}


//echo "<p></p>";

//var_dump($fetch_leave_req);

		$date_time_stamp_a = strtotime($date_a);
		$date_time_stamp_b = strtotime($date_b);


		if( $re_occur == 1 ){
			$reoccur_id = $this->user_model->insert_user_availability_reoccur('0700','1700','daily','','mon,tue,wed,thu,fri',$date_time_stamp_a,$date_time_stamp_b,'0','');
			$this->user_model->inset_availability($fetch_leave_req['user_id'],$status,  $fetch_leave_req['details'],$date_time_stamp_a,$date_time_stamp_b,$reoccur_id);
		}else{
			$this->user_model->inset_availability($fetch_leave_req['user_id'],$status,  $fetch_leave_req['details'],$date_time_stamp_a,$date_time_stamp_b,0);
		}
		

	}

	function delete_user_ava(){
		$this->clear_apost();
		$ava_id = $_POST['ajax_var'];
		$this->user_model->delete_ava($ava_id);
	}

	function delete_user_ava_rec(){
		$this->clear_apost();
		$ava_id = $_POST['ajax_var'];
		$this->user_model->delete_ava_rec($ava_id);
	}


	function update_availability(){
		$this->clear_apost();
		$ave = explode('`', $_POST['ajax_var']);


		$date_a = $ave[0];
		$date_b = $ave[1];
		$notes = $ave[2];
		$user_id = $ave[3];
		$pathname = $ave[4];
		$status = $ave[5];
		$ava_id = $ave[6];



		$time_stamp_a = $this->date_formater_to_timestamp($date_a);
		$time_stamp_b = $this->date_formater_to_timestamp($date_b);

	//	echo "$ava_id,$notes, $time_stamp_a , $time_stamp_b";

		$this->user_model->update_ava($ava_id,$notes, $time_stamp_a , $time_stamp_b);

		$user_q = $this->user_model->fetch_user($user_id);
		$user_detail = array_shift($user_q->result());
		$user_name = ucfirst($user_detail->user_first_name).' '.ucfirst($user_detail->user_last_name);

		$person_did = $this->session->userdata('user_id');
		$type = 'User Availability';
		$actions = 'Availability: '.$status.' is updated to.'.$user_name;
		$date = date("d/m/Y");
		$time = date("H:i:s");
		$this->user_model->insert_user_log($person_did,$date,$time,$actions,'',$type,'7');




/*
		echo "$ava_id,$notes, $time_stamp_a , $time_stamp_b";

		if (strpos($pathname, 'users') !== false) {
			echo '1';
		}
*/

	//	var_dump($ave);
	}


	function reset_reoccur_avaialbility(){
		$this->load->module('admin');
		$this->load->model('admin_m');

		$person_did = $this->session->userdata('user_id');
		$fetch_user_loc = $this->admin_m->fetch_user_location($person_did);
		$user_location = array_shift($fetch_user_loc->result_array());

		$user_state_raw = explode(',', $user_location['location']);
		$user_site = trim($user_state_raw[1]);
		switch ($user_site) {
			case "NSW": 
			date_default_timezone_set('Australia/Sydney');
			break;

			case "QLD":
			date_default_timezone_set('Australia/Perth');
			break;

			case "WA":
			date_default_timezone_set('Australia/Perth');
			break;
		}


		$current_timestamp = strtotime(date("Y-m-d"));
		//$current_timestamp = strtotime("2016-12-05");
		$reoccur_q = $this->user_model->list_active_reoccur_availability($current_timestamp);

		foreach ($reoccur_q->result() as $reoccur){

			switch ($reoccur->pattern_type) {
				case "weekly": 
				$date_future = strtotime(date("Y-m-d").' + '.$reoccur->limits.' week');
				break;

				case "monthly":
				$date_future = strtotime(date("Y-m").'-'.$reoccur->range_reoccur.' + '.$reoccur->limits.' month');
				break;

				case "yearly":
				$date_future = strtotime(date("Y").'-'.$reoccur->limits.'-'.$reoccur->range_reoccur.' + 1 year');
				break;
			}

			$this->user_model->update_future_reoccur_present_date($current_timestamp,$date_future,$reoccur->reoccur_id);
		}
	}

	function ordinalSuffix($num) {
		$suffixes = array("st", "nd", "rd");
		$lastDigit = $num % 10;
		if(($num < 20 && $num > 9) || $lastDigit == 0 || $lastDigit > 3) return "th";
		return $suffixes[$lastDigit - 1];
	}


	function set_availability_reoccur(){
		$this->clear_apost();
		$this->load->module('admin');
		$this->load->model('admin_m');

		$person_did = $this->session->userdata('user_id');
		$fetch_user_loc = $this->admin_m->fetch_user_location($person_did);
		$user_location = array_shift($fetch_user_loc->result_array());

		$user_state_raw = explode(',', $user_location['location']);
		$user_site = trim($user_state_raw[1]);
		switch ($user_site) {
			case "NSW": 
			date_default_timezone_set('Australia/Sydney');
			break;

			case "QLD":
			date_default_timezone_set('Australia/Perth');
			break;

			case "WA":
			date_default_timezone_set('Australia/Perth');
			break;
		}


		$ave = explode('`', $_POST['ajax_var']);

		$date_a = $ave[0];
		$date_b = $ave[1];
		$notes = $ave[2];
		$user_id = $ave[3];
		$pathname = $ave[4];
		$status = $ave[5];


		$occur = explode('`', $_POST['reoccur']);

		//var_dump($ave);
		//var_dump($occur);


		$raw_start_time = explode(' ', $occur[0]);
		$start_time_raw = str_replace(':','',$raw_start_time[0]);
		$start_time = ($raw_start_time[1] == 'PM' ? $start_time_raw + 1200 : $start_time_raw);

		$raw_end_time = explode(' ', $occur[1]);
		$end_time_raw = str_replace(':','',$raw_end_time[0]);
		$end_time = ($raw_end_time[1] == 'PM' ? $end_time_raw + 1200 : $end_time_raw);

		$pattern_type = $occur[2];
		$limits = $occur[3];
		$range_reoccur = $occur[4];

		$data_rage_raw_a = explode('/', $occur[5]);
		$date_range_a =  strtotime($data_rage_raw_a[2].'-'.$data_rage_raw_a[1].'-'.$data_rage_raw_a[0]);


		$is_no_end = $occur[7];

		if($is_no_end == 1){
			$date_range_b = 32503698000; //3000-01-01
		}else{
			$data_rage_raw_b = explode('/', $occur[6]);
			$date_range_b = strtotime($data_rage_raw_b[2].'-'.$data_rage_raw_b[1].'-'.$data_rage_raw_b[0].' '.$end_time);
		}

		switch ($pattern_type) {
			case "daily":
			$date_future = '';
			break;

			case "weekly": 
			$date_future = strtotime($data_rage_raw_a[2].'-'.$data_rage_raw_a[1].'-'.$data_rage_raw_a[0].' + '.$limits.' week');
			//$date_future = strtotime(date('Y-m-d',$pDate)); 
			break;

			case "monthly":
			$date_future = strtotime($data_rage_raw_a[2].'-'.$data_rage_raw_a[1].'-'.$range_reoccur.' + '.$limits.' month');
			//$date_future = strtotime(date('Y-m-d',$pDate)); 
			break;

			case "yearly":
			$date_future = strtotime($data_rage_raw_a[2].'-'.$limits.'-'.$range_reoccur.' + 1 year');
			//$date_future = strtotime(date('Y-m-d',$pDate)); 
			break;
		}

		$reoccur_id = $this->user_model->insert_user_availability_reoccur($start_time,$end_time,$pattern_type,$limits,$range_reoccur,$date_range_a,$date_range_b,$is_no_end,$date_future);


		/* reoccur set */
		$user_q = $this->user_model->fetch_user($user_id);
		$user_detail = array_shift($user_q->result());
		$user_name = ucfirst($user_detail->user_first_name).' '.ucfirst($user_detail->user_last_name);


		$type = 'User Availability';
		$actions = 'Availability: '.$status.' is been set to.'.$user_name;
		//

		//date_default_timezone_set("Australia/Perth");




		

		$date = date("d/m/Y");
		$time = date("H:i:s");
		$this->user_model->insert_user_log($person_did,$date,$time,$actions,'',$type,'7');


		//var_dump($date_a);

		$time_stamp_a = $this->date_formater_to_timestamp($date_a);
		$time_stamp_b = $this->date_formater_to_timestamp($date_b);
		$this->user_model->inset_availability($user_id,$status,$notes,$time_stamp_a,$time_stamp_b,$reoccur_id);

		if (strpos($pathname, 'users') !== false) {
			echo '1';
		}else{
			echo $this->get_user_availability($user_id);
		}
		/* reoccur set */



	}


	function set_availability($innit_ave = ''){
		$this->clear_apost();
		$ave = explode('`', $_POST['ajax_var']);

		if($innit_ave != ''){
			$ave = explode('`',$innit_ave);
		}
		
		$date_a = $ave[0];
		$date_b = $ave[1];
		$notes = $ave[2];
		$user_id = $ave[3];
		$pathname = $ave[4];
		$status = $ave[5];

		$user_q = $this->user_model->fetch_user($user_id);
		$user_detail = array_shift($user_q->result());
		$user_name = ucfirst($user_detail->user_first_name).' '.ucfirst($user_detail->user_last_name);

		$person_did = $this->session->userdata('user_id');

		$type = 'User Availability';
		$actions = 'Availability: '.$status.' is been set to.'.$user_name;
		//

		//date_default_timezone_set("Australia/Perth");



		$this->load->module('admin');
		$this->load->model('admin_m');

		
		$fetch_user_loc = $this->admin_m->fetch_user_location($person_did);
		$user_location = array_shift($fetch_user_loc->result_array());

		$user_state_raw = explode(',', $user_location['location']);
		$user_site = trim($user_state_raw[1]);
		switch ($user_site) {
			case "NSW": 
			date_default_timezone_set('Australia/Sydney');
			break;

			case "QLD":
			date_default_timezone_set('Australia/Perth');
			break;

			case "WA":
			date_default_timezone_set('Australia/Perth');
			break;
		}



		$date = date("d/m/Y");
		$time = date("H:i:s");
		$this->user_model->insert_user_log($person_did,$date,$time,$actions,'',$type,'7');


		//var_dump($date_a);

		$time_stamp_a = $this->date_formater_to_timestamp($date_a);
		$time_stamp_b = $this->date_formater_to_timestamp($date_b);
		$this->user_model->inset_availability($user_id,$status,$notes,$time_stamp_a,$time_stamp_b);

		if (strpos($pathname, 'users') !== false) {
			echo '1';
		}else{
			echo $this->get_user_availability($user_id);
		}

	}


	function reset_availability(){
		$data_reset = explode('`', $_POST['ajax_var']);
		$pathname = $data_reset[0];
		$user_id = $data_reset[1];

		$availability_id = 0;
		$type = 0;

		if (strpos($pathname, 'users') !== false) {
			echo '1';
		}

/*
		$data_reset = explode('`', $_POST['ajax_var']);
		$pathname = $data_reset[0];
		$user_id = $data_reset[1];
		if (strpos($pathname, 'users') !== false) {
			echo '1';
		}
		$current_date_time = strtotime(date("Y-m-d h:i A"));
		echo "$user_id,$current_date_time<br />";
		$this->user_model->remove_availability($user_id,$current_date_time);



*/



		$current_date_time = strtotime(date("Y-m-d h:i A"));

		$current_date = date('Y/m/d');
		$tomorrow = date('Y-m-d',strtotime($current_date . "+1 days"));


	//	echo "$tomorrow---";

		$this->reset_reoccur_avaialbility();

		$availability_id = 0;
		$type = 0;

		$is_available = 0;
		$stage_b = 1;

		$user_ave_q = $this->user_model->get_user_availability($user_id,$current_date_time);
		$user_ave = array_shift($user_ave_q->result_array());

		if($user_ave_q->num_rows === 1){
			$availability_id = $user_ave['user_availability_id']; 
			$stage_b = 0;
			$type = 1;
		//	echo "$availability_id,$type xxx  remove regular<br />";
			$this->user_model->remove_availability($availability_id);
		}else{

/*
			foreach ($reoccur_q->result() as $reoccur){

				switch ($reoccur->pattern_type) {
					case "weekly": 
					$date_future = strtotime(date("Y-m-d").' + '.$reoccur->limits.' week');
					break;

					case "monthly":
					$date_future = strtotime(date("Y-m").'-'.$reoccur->range_reoccur.' + '.$reoccur->limits.' month');
					break;

					case "yearly":
					$date_future = strtotime(date("Y").'-'.$reoccur->limits.'-'.$reoccur->range_reoccur.' + 1 year');
					break;
				}

				$this->user_model->update_future_reoccur_present_date($current_timestamp,$date_future,$reoccur->reoccur_id);
			}

*/


		}
 
		$current_timestamp = strtotime(date("Y-m-d h:i A"));
	//	$current_timestamp = strtotime(date("2017-11-01"));
		$time_extended = date("Hi");
		$day_like = strtolower(date("D") );

		if($stage_b == 1){

			$reoccur_q = $this->user_model->get_reoccur_ave_year_month($current_timestamp,$time_extended,$user_id);
			if($reoccur_q->num_rows === 1){

				$reoccur = array_shift($reoccur_q->result_array());
				$availability_id = $reoccur['reoccur_id'];
				$pattern_type = $reoccur['pattern_type']; 
				$type = 2;

				//var_dump($reoccur);

				//echo " ***$availability_id,$type reoccur a*** <br />";




				switch ($pattern_type) {

					case "weekly": 
						$date_future = strtotime(date("Y-m-d").' + '.$reoccur['limits'].' week');
					break;


					case "monthly":
					$date_future = strtotime(date("Y-m").'-'.$reoccur['range_reoccur'].' + '.$reoccur['limits'].' month');
					//$date_future_more = strtotime(date("Y-m", $date_future).'-'.$reoccur['range_reoccur'].' + '.$reoccur['limits'].' month' );
					break;

					case "yearly":
					$date_future = strtotime(date("Y").'-'.$reoccur['limits'].'-'.$reoccur['range_reoccur'].' + 1 year');
					//$date_future_more = strtotime(date("Y", $date_future).'-'.$reoccur['limits'].'-'.$reoccur['range_reoccur'].' + 1 year');
					break;
				}

				$this->user_model->update_future_reoccur_present_date($date_future,$reoccur['date_range_b'],$availability_id);





				//$this->user_model->remove_availability($availability_id,$type);

			}else{
				$is_available = 1;
			}
		}

		//echo "($current_date_time, $time_extended, $day_like,$user_id)";

		if($is_available == 1){

			$current_date_time = strtotime(date("Y-m-d h:i A"));
			$user_ave_roc_q = $this->user_model->get_reoccur_availability($current_date_time, $time_extended, $day_like,$user_id);


			if($user_ave_roc_q->num_rows === 1){
				$reoccur = array_shift($user_ave_roc_q->result_array());
				$availability_id = $reoccur['reoccur_id']; 
				$type = 2;
				$pattern_type = $reoccur['pattern_type']; 

				//echo '****'.$reoccur['limits'].'****';


				switch ($pattern_type) {

					case "weekly": 
						$date_future = strtotime(date("Y-m-d").' + '.$reoccur['limits'].' week');
						$this->user_model->update_future_reoccur_present_date($date_future,$reoccur['date_range_b'],$availability_id);
					break;


					case "daily": 
						$date_future = strtotime(date("Y-m-d").' + 1 day');
						$this->user_model->update_future_reoccur_present_date($date_future,$reoccur['date_range_b'],$availability_id);
					break;


				}

					 

				//echo "$date_future, $availability_id";

				//$this->user_model->remove_availability($availability_id,$type);


			}
		}


	}

	function availability(){
		if(!$this->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;

		$this->_check_user_access('users',1);

		$fetch_user= $this->user_model->list_user_short();
		$data['users'] = $fetch_user->result();
		$data['main_content'] = 'availability';

		$data['screen'] = 'User Availability';

		$data['page_title'] = 'User Availability';

		$this->load->view('page', $data);		
	}


	function if_user_is_available($user_id){
		$current_date_time = strtotime(date("Y-m-d h:i A"));
		$user_ave_q = $this->user_model->get_user_availability($user_id,$current_date_time);
		if($user_ave_q->num_rows === 1){
			return false;
		}else{
			return true;
		}
	}

	function fetch_user_ave_data($user_id){
		$current_date_time = strtotime(date("Y-m-d h:i A"));
		$user_ave_q = $this->user_model->get_user_availability($user_id,$current_date_time);
		$user_ave = array_shift($user_ave_q->result_array());

		if($user_ave_q->num_rows === 1){
			return $user_ave;
		}else{


			return $this->get_user_reoccur_ave($user_id);
		}


	}

	function fetch_user_future_availability($user_id){
		$current_date_time = strtotime(date("Y-m-d h:i A"));
		$user_ave_q = $this->user_model->fetch_future_availability($user_id,$current_date_time);
		return $user_ave_q;
	}

	function fetch_user_future_reocc_ava($user_id){
		$user_ave_q = $this->user_model->get_upcomming_reoccuring_ave($user_id);
		return $user_ave_q;
	}

	
	function get_user_availability($user_id,$mod=''){

		$this->reset_reoccur_avaialbility();
		$current_date_time = strtotime(date("Y-m-d h:i A"));


		//$current_date_time = strtotime(date("2017-11-01"));
		$is_available = 0;
		$stage_b = 1;

		$user_ave_q = $this->user_model->get_user_availability($user_id,$current_date_time);
		$user_ave = array_shift($user_ave_q->result_array());

		if($mod != ''){

			if($user_ave_q->num_rows === 1){

				if($user_ave['status'] == 'Busy'){
					echo '<span style="color:white; background: red;"><i class="fa fa-exclamation-circle"></i>';
				}elseif($user_ave['status'] == 'Sick'){
					echo '<span style="color:white; background: purple;"><i class="fa fa-times-circle"></i>';
				}elseif($user_ave['status'] == 'Leave'){
					echo '<span style="color:white; background: gray;"><i class="fa fa-minus-circle"></i>';
				}elseif($user_ave['status'] == 'Out of Office'){
					echo '<span style="color:white; background: orange;"><i class="fa fa-arrow-circle-left"></i>';
				}else{ }

				echo ' '.$user_ave['status'].'</span>';
				$stage_b = 0;

			}

		}else{

			if($user_ave_q->num_rows === 1){

				if($user_ave['status'] == 'Busy'){
					echo '<span style="color: red;"><i class="fa fa-exclamation-circle"></i>';
				}elseif($user_ave['status'] == 'Sick'){
					echo '<span style="color: purple;"><i class="fa fa-times-circle"></i>';
				}elseif($user_ave['status'] == 'Leave'){
					echo '<span style="color: gray;"><i class="fa fa-minus-circle"></i>';
				}elseif($user_ave['status'] == 'Out of Office'){
					echo '<span style="color: orange;"><i class="fa fa-arrow-circle-left"></i>';
				}else{ }

				echo ' '.$user_ave['status'].'</span>';
				$stage_b = 0;
			}
		}

 
		$current_timestamp = strtotime(date("Y-m-d h:i A"));
	//	$current_timestamp = strtotime(date("2017-11-01"));
		$time_extended = date("Hi");
		$day_like = strtolower(date("D") );


	//	echo "$current_timestamp $time_extended $day_like";

		if($stage_b == 1){

			$reoccur_q = $this->user_model->get_reoccur_ave_year_month($current_timestamp,$time_extended,$user_id);
			if($reoccur_q->num_rows === 1){

				$reoccur = array_shift($reoccur_q->result_array());



				if($reoccur['status'] == 'Busy'){
					echo '<span style="color: red;"><i class="fa fa-exclamation-circle"></i>';
				}elseif($reoccur['status'] == 'Sick'){
					echo '<span style="color: purple;"><i class="fa fa-times-circle"></i>';
				}elseif($reoccur['status'] == 'Leave'){
					echo '<span style="color: gray;"><i class="fa fa-minus-circle"></i>';
				}elseif($reoccur['status'] == 'Out of Office'){
					echo '<span style="color: orange;"><i class="fa fa-arrow-circle-left"></i>';
				}else{ }

				echo ' '.$reoccur['status'].'</span>';

			}else{
				$is_available = 1;
			}
		}




		if($is_available == 1){

			$current_date_time = strtotime(date("Y-m-d h:i A"));
			$user_ave_roc_q = $this->user_model->get_reoccur_availability($current_date_time, $time_extended, $day_like,$user_id);
			$reoccur_ave = array_shift($user_ave_roc_q->result_array());


			if($user_ave_roc_q->num_rows === 1){

				if($reoccur_ave['status'] == 'Busy'){
					echo '<span style="color: red;"><i class="fa fa-exclamation-circle"></i>';
				}elseif($reoccur_ave['status'] == 'Sick'){
					echo '<span style="color: purple;"><i class="fa fa-times-circle"></i>';
				}elseif($reoccur_ave['status'] == 'Leave'){
					echo '<span style="color: gray;"><i class="fa fa-minus-circle"></i>';
				}elseif($reoccur_ave['status'] == 'Out of Office'){
					echo '<span style="color: orange;"><i class="fa fa-arrow-circle-left"></i>';
				}else{ }

				echo ' '.$reoccur_ave['status'].'</span>';

			}else{
				echo '<span style="color: green;"><i class="fa fa-check-circle"></i>';
				echo ' Available </span>';
			}
			
		}

	}


	function get_user_reoccur_ave($user_id){
		$time_extended = date("Hi");
		$day_like = strtolower(date("D") );
		$current_date_time = strtotime(date("Y-m-d h:i A"));
		$current_timestamp = strtotime(date("Y-m-d"));  

		$reoccur_q = $this->user_model->get_reoccur_ave_year_month($current_date_time,$time_extended,$user_id);
			if($reoccur_q->num_rows === 1){

				$reoccur_ave = array_shift($reoccur_q->result_array());

			}else{
			$current_date_time = strtotime(date("Y-m-d h:i A"));
				$user_ave_roc_q = $this->user_model->get_reoccur_availability($current_date_time, $time_extended, $day_like,$user_id);
				$reoccur_ave = array_shift($user_ave_roc_q->result_array());

			}

		return $reoccur_ave;
	}

	function get_user_ave_comments($user_id){


		$fetch_user_loc = $this->admin_m->fetch_user_location($user_id);
		$user_location = array_shift($fetch_user_loc->result_array());

		$user_state_raw = explode(',', $user_location['location']);
		$user_site = trim($user_state_raw[1]);
		switch ($user_site) {
			case "NSW": 
			date_default_timezone_set('Australia/Sydney');
			break;

			case "QLD":
			date_default_timezone_set('Australia/Perth');
			break;

			case "WA":
			date_default_timezone_set('Australia/Perth');
			break;
		}





		$current_date_time = strtotime(date("Y-m-d h:i A"));

		$user_ave_q = $this->user_model->get_user_availability($user_id,$current_date_time);
		$user_ave = array_shift($user_ave_q->result_array());


		if($user_ave_q->num_rows === 1){
			


			if( strlen($user_ave['notes']) > 0 ){
				echo '<span style="color:#1F3A4D;" class=" tooltip-enabled" title="" data-original-title="'.$user_ave['notes'].' Return:'.date('l jS \of F Y h:iA',$user_ave['date_time_stamp_b']).'"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
			}else{
				if($user_ave['status']!= ''){
					echo '<span style="color:#1F3A4D;" class=" tooltip-enabled" title="" data-original-title="Return: '.date('l jS \of F Y h:iA',$user_ave['date_time_stamp_b']).'"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
				}
			}


		}else{



			$reoccur_ave = $this->users->get_user_reoccur_ave($user_id);

			$hr = substr($reoccur_ave['end_time'] ,0,2);

			if($hr > 12){
				$end_time = $hr-12;
				$min = substr($reoccur_ave['end_time'] ,-2).'PM';
			}else{
				$end_time = $hr;
				$min = substr($reoccur_ave['end_time'] ,-2).'AM';
			}

			//$dis_time = $end_time.':'.$min;


			

			if( $reoccur_ave['is_no_end'] == 1){
$date_end = date("l ",$reoccur_ave['date_range_b']);//.' '.$reoccur_ave['user_availability_id'];
			}else{

$date_end = date("l jS \of F h:iA",$reoccur_ave['date_range_b']);//.' '.$reoccur_ave['user_availability_id'];
			}



			echo '<span style="color:#1F3A4D;" class=" tooltip-enabled" title="" data-original-title="'.$reoccur_ave['notes'].' Return:'.$date_end.'"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';



/*
			$hr = substr($reoccur_ave['end_time'] ,0,2);

			if($hr > 12){
				$end_time = $hr-12;
				$min = substr($reoccur_ave['end_time'] ,-2).'PM';
			}else{
				$end_time = $hr;
				$min = substr($reoccur_ave['end_time'] ,-2).'AM';
			}

			$dis_time = $end_time.':'.$min;


			

			

			if( $reoccur_ave['is_no_end'] == 1){
$date_end = date("l ",$reoccur_ave['date_range_b']);
			}else{

$date_end = date("l jS \of F h:iA",$reoccur_ave['date_range_b']);
			}




			echo '<span style="color:#1F3A4D;" class=" tooltip-enabled" title="" data-original-title="'.$reoccur_ave['notes'].' Return:'.$date_end.'"><i class="fa fa-info-circle" aria-hidden="true"></i></span>';
*/

		}



	}


	function date_formater_to_timestamp($input_datetime){

		$this->load->module('admin');
		$this->load->model('admin_m');


		//05/10/2016 03:53 PM
		$set = explode(' ',$input_datetime);
		$date = explode('/', $set[0]);
		$time = explode(':', $set[1]);
/*
		if($set[2] == 'PM'){
			$time[0] = $time[0] + 12;
		}
*/


		$userid = $this->session->userdata('user_id');

		$fetch_user_loc = $this->admin_m->fetch_user_location($userid);
		$user_location = array_shift($fetch_user_loc->result_array());

		$user_state_raw = explode(',', $user_location['location']);
		$user_site = trim($user_state_raw[1]);
		switch ($user_site) {
			case "NSW": 
			date_default_timezone_set('Australia/Sydney');
			break;

			case "QLD":
			date_default_timezone_set('Australia/Perth');
			break;

			case "WA":
			date_default_timezone_set('Australia/Perth');
			break;
		}


		$date_formatted = $date[2].'-'.$date[1].'-'.$date[0].' '.$time[0].':'.$time[1].' '.$set[2];
		//  '2016-10-05 15:00 AM';
		$timestamp = strtotime("$date_formatted");
		return $timestamp;
	}


	function user_activity_list(){
		$admin = $this->session->userdata('is_admin');
		$log_in_users = $this->user_model->fetch_user_activity();




		$time_stamp=time();
		$set_time = strtotime(  date("Y-m-d h:i:s",$time_stamp)   );
 
		echo '<ul style="padding:0; margin:0;">';
		foreach ($log_in_users->result() as $users){

			$from_time = strtotime(date("Y-m-d h:i:s",$users->time_stamp));
			$minute_active = round(abs($set_time - $from_time) / 86400,2);

			$logged_time = date("h:i A",$users->time_logged_in);

			//echo "<p>$minute_active</p>";

			if($minute_active < 260){

				if($admin == 1){
					echo '<li style="float:left; width:20%; position:relative; display:block;"><span class="" style="height: 100px; width: 100px; position: relative; display: block; overflow: hidden;">
						<img src = "'.base_url().'uploads/users/'.$users->user_profile_photo.'" style = "width: 150px; display: block; margin-top: -10px; margin-left: -30px;"></span>
						<div style="margin-bottom:20px;">
						<span class="m-right-15">'.$users->user_first_name.'</span> 
						<button type = "button" title = "Log-out User" class = "btn btn-danger btn-xs m-left-15" onclick = "btn_logout_user('.$users->user_id.')" style=" z-index: 1; left: 58px; padding: 3px 3px 3px 4px; position: absolute; bottom: 45px;"><i class = "fa fa-sign-out fa-sm"></i></button></div></li>';
				}else{
					echo '<li style="float:left; width:20%; position:relative; display:block;"><span class="" style="height: 100px; width: 100px; position: relative; display: block; overflow: hidden;">
						<img src = "'.base_url().'uploads/users/'.$users->user_profile_photo.'" style = "width: 150px; display: block; margin-top: -10px; margin-left: -30px;"></span>
						<div style="margin-bottom:20px;">
						<span class="">'.$users->user_first_name.'</span></div></li>';
				}

			}
		}
		echo "</ul>";
	}

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
					echo '<li><img src = "'.base_url().'uploads/users/'.$row['user_profile_photo'].'" style = "width: 40px">'." ".$row['user_first_name'].'<button type = "button" title = "Log-out User" class = "btn btn-danger btn-xs pull-right" onclick = "btn_logout_user('.$row['user_id'].')"><i class = "fa fa-sign-out fa-sm"></i></button></li>';
				}else{
					echo '<li><img src = "'.base_url().'uploads/users/'.$row['user_profile_photo'].'" style = "width: 40px">'." ".$row['user_first_name'].'</li>';
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

	function update_user_site(){
		$user_id = $this->uri->segment(3);
		$site_select = $this->input->post('site_select');

		$log_in_users = $this->user_model->update_site_access($user_id,$site_select);

		redirect('users/account/'.$user_id);
	}

	public function fetch_user($user_id){
		$fetch_user = $this->user_model->fetch_user($user_id);
		$fetch_user = $fetch_user->result();

		return $fetch_user;
	}

	public function leave_type(){
		$leave_type = $this->user_model->fetch_leave_type();
		$leave_type = $leave_type->result();

		return $leave_type;
	}

	public function get_sched($user_id_page){
		$get_sched_of_work = $this->user_model->get_sched($user_id_page);
		$get_sched_of_work = $get_sched_of_work->row();

		return $get_sched_of_work;
	}


	public function leave_alloc($user_id_page){
		$leave_alloc = $this->user_model->fetch_leave_alloc($user_id_page);
		$leave_alloc = $leave_alloc->row();

		return $leave_alloc;
	}

	public function for_approval_count(){
		$user_id = $this->session->userdata('user_id');
		$fetch_pending_by_superv = $this->user_model->fetch_pending_leaves_by_supervisor_id($user_id);
		return $fetch_pending_by_superv->num_rows();
	}

	public function leave_remaining($user_id){

		if ($user_id == ''){
			$user_id = $this->session->userdata('user_id');	
		}
		
		$leave_remaining = $this->user_model->fetch_leave_alloc($user_id, date('Y'));
		$leave_remaining = $leave_remaining->row();
		
		return $leave_remaining;
	}

	/*public function approval_notice_days(){
		$leave_approval_notice = $this->user_model->fetch_leave_approval_notice();
		$leave_approval_notice = $leave_approval_notice->row();
		
		return $leave_approval_notice;
	}*/

	public function user_state($user_id){
		$user_state = $this->user_model->fetch_user_state($user_id);
		$user_state = $user_state->row();
		
		return $user_state;
	}

	public function leave_details($user_id) {
		//$this->users->_check_user_access('sample_module',1);
		
		//$departments = $this->user_model->fetch_all_departments();
		//$data['departments'] = $departments->result();

		//$roles = $this->user_model->fetch_all_roles();
		//$data['roles'] = $roles->result();

		$user_id = $this->uri->segment(3);

		$fetch_user = $this->user_model->fetch_user($user_id);
		$data['user'] = $fetch_user->result();

		$fetch_leave_alloc = $this->user_model->fetch_leave_alloc($user_id, '2017');

		if ($fetch_leave_alloc != '0') {
			$data['leave_alloc'] = $fetch_leave_alloc->result();	
		} else {
			$data['leave_alloc'] = '0';
		}
		
		$fetch_pending_leaves = $this->user_model->fetch_pending_leaves($user_id);
		$data['pending_leaves'] = $fetch_pending_leaves->result();

		$fetch_approved_leaves = $this->user_model->fetch_approved_leaves($user_id);
		$data['approved_leaves'] = $fetch_approved_leaves->result();

		$fetch_approved_leaves_all = $this->user_model->fetch_approved_leaves_all($user_id);
		$data['approved_leaves_all'] = $fetch_approved_leaves_all->result();

		$fetch_approved_leaves_by_md = $this->user_model->fetch_approved_leaves_by_md($user_id);
		$data['approved_leaves_by_md'] = $fetch_approved_leaves_by_md->result();

		$fetch_approved_leaves_by_md_all = $this->user_model->fetch_approved_leaves_by_md_all($user_id);
		$data['approved_leaves_by_md_all'] = $fetch_approved_leaves_by_md_all->result();

		$fetch_unapproved_leaves = $this->user_model->fetch_unapproved_leaves($user_id);
		$data['unapproved_leaves'] = $fetch_unapproved_leaves->result();

		//$fetch_leave_request = $this->user_model->fetch_leave_request($user_id);
		//$data['leave_req'] = $fetch_leave_request->result();

		$data['main_content'] = 'emp_leave_v';
		$data['screen'] = 'Employee Leave Details';
		$data['comp_type'] = 1;

		$data['page_title'] = 'Leave Details: '. $data['user'][0]->user_first_name.' '.$data['user'][0]->user_last_name;

		$this->load->view('page', $data);
	}

	public function leave_approvals($user_id){

		$fetch_pending_by_superv = $this->user_model->fetch_pending_leaves_by_supervisor_id($user_id);
		$data['pending_by_superv'] = $fetch_pending_by_superv->result();

		// $fetch_approved_and_disapproved = $this->user_model->fetch_approved_and_disapproved();
		// $data['approved_and_disapproved'] = $fetch_approved_and_disapproved->result();

		// $fetch_approved_and_disapproved_by_md = $this->user_model->fetch_approved_and_disapproved_by_md();
		// $data['approved_and_disapproved_by_md'] = $fetch_approved_and_disapproved_by_md->result();

		$data['main_content'] = 'leave_approvals';
		$data['screen'] = 'For Approval Leaves';
		$data['comp_type'] = 1;

		$data['page_title'] = 'Leave Approvals';

		$this->load->view('page', $data);
	}

	public function apply_leave($user_id){

		$this->clear_apost();

		$leave_type_count = 0;
		$current_date = strtotime("now");
		//$current_year = date('Y');

		$ajax_var = $this->input->post('ajax_var');
		$insert_data = explode('|', $ajax_var);

		$leave_type = $insert_data[0];

		$date_start = str_replace('/', '-', $insert_data[1]);
		$timestamp_start = strtotime($date_start);

		$date_end = str_replace('/', '-', $insert_data[2]);
		$timestamp_end = strtotime($date_end);

		$date_return = str_replace('/', '-', $insert_data[3]);
		$timestamp_return = strtotime($date_return);

		$leave_details = $insert_data[4];
		$total_hrs_leave = $insert_data[5];
		$partial_day = $insert_data[6];
		$partial_part = $insert_data[7];
		$partial_time = $insert_data[8];
		$ph_holidays = $insert_data[9];
		$applied_by = $insert_data[10];

		$fetch_user = $this->user_model->fetch_user($user_id);
		$qArr = array_shift($fetch_user->result_array());
		$user_supervisor_id = $qArr['supervisor_id'];

		$this->user_model->insert_leave_req($current_date, $user_id, $leave_type, $timestamp_start, $timestamp_end, $timestamp_return, $leave_details, $total_hrs_leave, $user_supervisor_id, $partial_day, $partial_part, $partial_time, $ph_holidays, $applied_by);
	}

	public function approve_leave($supervisor_id){
		
		$this->clear_apost();
		$current_date = strtotime("now");
		
		$ajax_var = $this->input->post('ajax_var');
		$approved_data = explode('|', $ajax_var);

		$leave_req_id = $approved_data[0];
		$action_comments = $approved_data[1];
		$leave_user_id = $approved_data[2];

		$fetch_user = $this->user_model->fetch_user($supervisor_id);
		$data['user'] = $fetch_user->result();

		$user_supervisor_id = $data['user'][0]->supervisor_id;


		if ($supervisor_id != '3'){
			$result = $this->user_model->approved_by_supervisor($leave_req_id, '3');

			if ($result == 1) {
				$this->user_model->insert_leave_action($leave_req_id, $supervisor_id, '1', $current_date, $action_comments);
				// redirect(base_url().'users/leave_approvals/'.$supervisor_id);

				echo 'You successfully approved the leave request!';
			} else {
				echo 'The leave request is already approved!';
			}
		} else {
			$result_gm = $this->user_model->approved_by_gm($leave_req_id, '1', $supervisor_id);

			if ($result_gm == 1) {
			
				$this->user_model->insert_leave_action($leave_req_id, $supervisor_id, '1', $current_date, $action_comments);

				$this->set_availalbility_on_leave($leave_req_id);

				$fetch_leave_defaults = $this->user_model->fetch_leave_defaults();
				$data['leave_defaults'] = $fetch_leave_defaults->result();

				if ( !class_exists("PHPMailer") ){
					require('PHPMailer/class.phpmailer.php'); 
				}

				// PHPMailer class
				$user_mail = new PHPMailer;
				$user_mail->Host = 'sojourn-focusshopfit-com-au.mail.protection.outlook.com';
				$user_mail->Port = 587;
				$user_mail->SMTPDebug = 2;
				// PHPMailer class 

				$user_mail->setFrom('ian@focusshopfit.com.au', 'Ian Gamble');
				$user_mail->addAddress($data['leave_defaults'][0]->recipient_email);  // Add a recipient
				//$user_mail->addAddress($user_email);                                // Name is optional

				$user_mail->addReplyTo('ian@focusshopfit.com.au', 'Ian Gamble');
				
				$cc_emails = array();
				$cc_emails = explode(",", $data['leave_defaults'][0]->cc_email);
				$cc_emails_count = count($cc_emails);

				for ($i=0; $i <= $cc_emails_count; $i++) { 
					$user_mail->addCC($cc_emails[$i]);	
				}

				$bcc_emails = array();
				$bcc_emails = explode(",", $data['leave_defaults'][0]->bcc_email);
				$bcc_emails_count = count($bcc_emails);

				for ($i=0; $i <= $bcc_emails_count; $i++) { 
					$user_mail->addBCC($bcc_emails[$i]);
				}

				$user_mail->addBCC('michael@focusshopfit.com.au');
				$this->generate_leave_form($leave_req_id, $leave_user_id);
				$path_file = './docs/leave_form/leave_form_'.$leave_req_id.'.pdf';
				$user_mail->addAttachment($path_file);         				// Add attachments
				//$user_mail->addAttachment('/tmp/image.jpg', 'new.jpg');    	// Optional name
				
				$user_mail->isHTML(true);                                   // Set email format to HTML

				$body_content = $data['leave_defaults'][0]->message;

				/*if ($action_comments != ''):
					$body_content .= '<br><br>Notes:<br>'.$action_comments;
				endif;*/

				$body_content .= '<p style="font-family: "Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif !important"><br><br><br><br>Regards,<br><br><strong>'.$this->session->userdata('user_first_name').' '.$this->session->userdata('user_last_name').'</strong><br><br>'.$this->session->userdata('role_types').'<br>0413 053 500<br><span style="color: "#FF3399 !important";>ian@focusshopfit.com.au</span></p>';

				// for live'
				$body_content .= '<br><img src="'.base_url().'img/signatures/FSFGroup.png" />';

				// for local
				// $body_content .= '<br><img src="https://sojourn.focusshopfit.com.au/img/signatures/FSFGroup.png" />';

				$user_mail->Subject = 'RE: Application of Leave';
				$user_mail->Body    = '<span style="font-family: "Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif">'.$body_content."</span>";

				if(!$user_mail->send()) {
				 	// echo 'Message could not be sent.';
					// echo 'Mailer Error: ' . $user_mail->ErrorInfo;
				} else {
					// echo 'Message could not be sent.';
				}
			} else {
				echo '0';
			}

// $fetch_leave_req_by_id_q = $this->user_model->fetch_leave_req_by_id($leave_req_id);
// $leave_req_data = array_shift($fetch_leave_req_by_id_q->result_array());

// if($leave_req_data['with_halfday'] == 1 && $leave_req_data['halfday_part'] == 1){
// 	$time_a = '07:00 AM';
// 	$time_b = '11:59 AM';
// }else{
// 	$time_a = '07:00 AM';
// 	$time_b = '05:00 PM';
// }

// $date_a = date('d/m/Y', $leave_req_data['start_day_of_leave']).' '.$time_a;
// $date_b = date('d/m/Y', $leave_req_data['date_return']).' '.$time_b;
// $detail = $leave_req_data['details'];
// $user_id = $leave_req_data['user_id'];
// $pathname = 'leave_details';

// if($leave_req_data['leave_type_id'] == 2){
// 	$type = 'Sick';
// }else{
// 	$type = 'Leave';
// }

// $availability_init = ''; 
// $availability_init .= $date_a.'`';
// $availability_init .= $date_b.'`';
// $availability_init .= $detail.'`';
// $availability_init .= $user_id.'`';
// $availability_init .= $pathname.'`';
// $availability_init .= $type;

// $this->set_availability($availability_init);
		}
	}

	public function disapproved_leave($supervisor_id){
		
		$this->clear_apost();
		$current_date = strtotime("now");

		$ajax_var = $this->input->post('ajax_var');
		$disapproved_data = explode('|', $ajax_var);

		$leave_req_id = $disapproved_data[0];
		$action_comments = $disapproved_data[1];

		var_dump($disapproved_data);

		if ($supervisor_id != '3'){
			$this->user_model->disapproved_by_supervisor($leave_req_id, '1');
		} else {
			$this->user_model->disapproved_by_gm($leave_req_id, '1', $supervisor_id);
		}

		$this->user_model->insert_leave_action($leave_req_id, $supervisor_id, '0', $current_date, $action_comments);
		
		redirect(base_url().'users/leave_approvals/'.$supervisor_id, 'refresh');
	}

	public function edit_leave_req(){
		$this->clear_apost();

		$leave_req_id = $this->input->post('leave_req_id');

		$fetch_leave_req_by_id = $this->user_model->fetch_leave_req_by_id($leave_req_id);
		$data['leave_req_by_id'] = $fetch_leave_req_by_id->result();

		foreach ($data['leave_req_by_id'] as $row) {
			echo $row->leave_request_id.'|'.$row->leave_type_id.'|'.$row->leave_type.'|'.date('d/m/Y', $row->start_day_of_leave).'|'.date('d/m/Y', $row->end_day_of_leave).'|'.date('d/m/Y', $row->date_return).'|'.str_replace('&apos;', '\'', $row->details).'|'.$row->total_days_away.'|'.$row->partial_day.'|'.$row->partial_part.'|'.$row->partial_time;
		
		}
	}

	public function update_leave_req($user_id){
		$this->clear_apost();
		
		$ajax_var = $this->input->post('ajax_var');
		$update_data = explode('|', $ajax_var);

		$leave_req_id = $update_data[0];
		$leave_type_id = $update_data[1];

		$date_start = str_replace('/', '-', $update_data[2]);
		$timestamp_start = strtotime($date_start);

		$date_end = str_replace('/', '-', $update_data[3]);
		$timestamp_end = strtotime($date_end);

		$date_return = str_replace('/', '-', $update_data[4]);
		$timestamp_return = strtotime($date_return);

		$leave_details = $update_data[5];
		$total_of_days = $update_data[6];

		$partial_day = $update_data[7];
		$partial_part = $update_data[8];
		$partial_time = $update_data[9];

		$edited_by = $update_data[10];		

		$this->user_model->update_leave_req($leave_req_id, $leave_type_id, $timestamp_start, $timestamp_end, $timestamp_return, $leave_details, $total_of_days, $partial_day, $partial_part, $partial_time, $edited_by);
		$this->user_model->remove_leave_action($leave_req_id);		
	}

	public function inactive_leave_req($leave_req_id){
		$this->clear_apost();
		$this->user_model->inactive_leave_req($leave_req_id);
	}

	public function add_leave_alloc($user_id_page){
		$this->clear_apost();

		$current_date = date("d-m-Y");
		$timestamp_current = strtotime($current_date);
		$yesterday_date = strtotime($current_date."-1 days");
		$annual_manual_entry = $this->input->post('annual_manual_entry', true);
		$personal_manual_entry = $this->input->post('personal_manual_entry', true);
		$checked_sched = $this->input->post('sched');
		$no_hrs_of_work = $this->input->post('no_hrs_of_work');

		if (isset($checked_sched)){
			$checked_sched = implode(',', $checked_sched);
		} else {
			$checked_sched = '';
		}

		$static_defaults = $this->user_model->select_static_defaults();
		$static_defaults = $static_defaults->row();

		$annual_leave_daily_rate = $static_defaults->annual_leave_daily_rate;
		$personal_leave_daily_rate = $static_defaults->personal_leave_daily_rate;

		$leave_alloc = $this->user_model->fetch_leave_alloc($user_id_page);
		$num_rows = $leave_alloc->num_rows();

		if ($num_rows == 0){
			$result = $this->user_model->add_leave_alloc($timestamp_current, $user_id_page, $annual_manual_entry, $personal_manual_entry, $checked_sched, $no_hrs_of_work, $timestamp_current);	

			$update_success = 'User account is now updated.';
			$this->session->set_flashdata('total_leave', $update_success);
			redirect('/users/account/'.$user_id_page);
		} else {
			$update_success = 'User account is now updated.';
			$this->session->set_flashdata('total_leave', $update_success);
			redirect('/users/account/'.$user_id_page);
		}
	}

	public function update_leave_alloc($user_id_page){
		$this->clear_apost();

		$annual_manual_entry = $this->input->post('annual_manual_entry', true);
		$personal_manual_entry = $this->input->post('personal_manual_entry', true);
		$checked_sched = $this->input->post('sched');
		$no_hrs_of_work = $this->input->post('no_hrs_of_work');

		$is_offshore_update = $this->input->post('is_offshore_update');

		$leave_alloc = $this->user_model->fetch_leave_alloc($user_id_page);
		$row = $leave_alloc->row();

		$current_date = date("d-m-Y");
		$timestamp_current = strtotime($current_date);
		//$timestamp_current = strtotime("2017-06-15");
		$annual_accumulated = $row->annual_accumulated;
		$personal_accumulated = $row->personal_accumulated;
		$last_annual_accumulated = $row->last_annual_accumulated;
		$last_personal_accumulated = $row->last_personal_accumulated;
		$sched_of_work = $row->sched_of_work;

		if (isset($checked_sched)){
			$checked_sched = implode(',', $checked_sched);
		} else {
			$checked_sched = '';
		}

		if ($checked_sched <> $sched_of_work){
			$last_annual_accumulated = $annual_accumulated + $last_annual_accumulated;
			$last_personal_accumulated = $personal_accumulated + $last_personal_accumulated;

			$this->user_model->update_approved_leave_to_inactive($user_id_page);
			$result = $this->user_model->update_leave_alloc_sched($user_id_page, $annual_manual_entry, $personal_manual_entry, $checked_sched, $no_hrs_of_work, $timestamp_current, $last_annual_accumulated, $last_personal_accumulated);
		} else {

			if ($is_offshore_update == '1'){
				$this->user_model->update_earned_offshore('0', '0', date('n'), $user_id_page);
			}

			$this->user_model->update_approved_leave_to_inactive($user_id_page);
			$result = $this->user_model->update_leave_alloc($user_id_page, $annual_manual_entry, $personal_manual_entry, $no_hrs_of_work, $timestamp_current);
		}

		$update_success = 'User account is now updated.';
		$this->session->set_flashdata('total_leave', $update_success);
		redirect('/users/account/'.$user_id_page);
	}

	public function update_sched($user_id_page){
		$this->clear_apost();

		$checked_sched = $this->input->post('sched');
		$checked_sched = implode(',', $checked_sched);

	    $this->user_model->update_sched($checked_sched, $user_id_page);

	    $update_success = 'User account is now updated.';
		$this->session->set_flashdata('total_leave', $update_success);
		redirect('/users/account/'.$user_id_page);
	}

	/*public function leave_alloc_all(){
		$leave_alloc_all = $this->user_model->fetch_leave_alloc_all(date('Y'));
		$leave_alloc_all = $leave_alloc_all->result();

		return $leave_alloc_all;
	}*/

	/*public function check_users(){

		$leave_alloc_all = $this->user_model->fetch_leave_alloc_all(date('Y'));
		$leave_alloc_all = $leave_alloc_all->result();

		foreach ($leave_alloc_all as $row) {
			
			$user_id = $row->user_id;
			$sched_work = $row->sched_of_work;
			$log_last_work = date('Y-m-d', $row->date_log_last_work);
			$day_yesterday = date('Y-m-d',strtotime("-1 days"));

			$log_last_work_conv = date_create($log_last_work);
			$day_yesterday_conv = date_create($day_yesterday);
			$diff = date_diff($log_last_work_conv, $day_yesterday_conv);
			$date_diff_converted = $diff->format('%d');
			$next_day = $log_last_work;
			$accumulation = $row->annual_earning_counter;

			for ($i=0; $i < $date_diff_converted; $i++) { 

				$next_day = date('Y-m-d', strtotime('+1 day', strtotime($next_day)));
				$day_in_week = date('w', strtotime($next_day));
				$check_sched_day = strpos($sched_work, $day_in_week);

				if ($check_sched_day !== false){
					//echo $sched_work."|".$day_in_week."<br>";
					$accumulation = $accumulation + 1;
				}
			}

			if ($log_last_work < $next_day){
				$earned_points = $accumulation * $row->default_annual_points;
				$annual_earning_points = $row->annual_points_earned + $earned_points;
				$last_day_earned = strtotime($next_day);

				echo $earned_points."<br>";
				echo $annual_earning_points."<br>";
				echo date('d/m/Y',$last_day_earned)."<br>";
//
				$this->user_model->update_earned_annual($last_day_earned, $accumulation, $annual_earning_points, $user_id,  date('Y'));
			}
		}
	}*/

	public function generate_leave_form($leave_req_id, $leave_user_id){

		//$leave_req_id = $this->uri->segment(3);

		$this->calc_leave_points();

		$fetch_user = $this->user_model->fetch_user($leave_user_id);
		$data['user'] = $fetch_user->result();

		$leave_alloc = $this->user_model->fetch_leave_alloc($leave_user_id);
		$data['leave_alloc'] = $leave_alloc->result();

		$user_supervisor_id = $data['user'][0]->supervisor_id;

		$for_pdf_content = $this->user_model->for_pdf_content($leave_req_id, $user_supervisor_id);
		$row = $for_pdf_content->row();

		$for_pdf_content_md = $this->user_model->for_pdf_content_md($leave_req_id);
		$row2 = $for_pdf_content_md->row();

		$leave_request_id = $row->leave_request_id;
		$date_applied = date('F j, Y', $row->date);
		$first_name = $row->user_first_name;
		$last_name = $row->user_last_name;
		$role = $row->role_types;
		$start_day = date('F j, Y', $row->start_day_of_leave);
		$end_day = date('F j, Y', $row->end_day_of_leave);
		$date_return = date('F j, Y', $row->date_return);
		$leave_type_id = $row->leave_type_id;
		$leave_type = $row->leave_type;
		$details = $row->details;
		$partial_day = $row->partial_day;
		$partial_part = $row->partial_part;
		$partial_time = $row->partial_time;
		$superv_first_name = $row->superv_first_name;
		$superv_last_name = $row->superv_last_name;
		$total_annual = $row->total_annual;
		$total_personal = $row->total_personal;
		$no_hrs_of_work = $row->no_hrs_of_work;
		$total_days_away = $row->total_days_away;
		$holiday_leave = $row->holiday_leave;
		$direct_report_comments = $row->action_comments;

		$md_comments = $row2->action_comments;

		if ($leave_type_id == '1'){
			$total_leave = $data['leave_alloc'][0]->total_annual;
		} else {
			$total_leave = $data['leave_alloc'][0]->total_personal;
		}

		if ($partial_day == 1){
			if ($partial_part == 1){
				$part_of_day = 'Arrived Late';
			} else if ($partial_part == 2) {
				$part_of_day = 'Depart Early';
			}
		} else {
			$part_of_day = '';
		}

		if ($partial_day == 1){

			$get_hrs = substr($total_days_away, 0, 1);
			$get_mins = substr($total_days_away, 1, 3);

			switch ($get_mins) {
				case '.25':
					$total_days_away = $get_hrs.' hrs & 15 mins';
					break;
				case '.50':
					$total_days_away = $get_hrs.' hrs & 30 mins';
					break;
				case '.75':
					$total_days_away = $get_hrs.' hrs & 45 mins';
					break;
				default:
					$total_days_away = $get_hrs.' hrs';
					break;
			}


			$total_leave_pdf = $total_days_away.' ('.$part_of_day.', '.$partial_time.')';
		} else {

			if ($leave_type_id >= 5){

				if(!isset($no_hrs_of_work)){
					$total_leave_pdf = $total_days_away / 8 .' day(s)';
				} else {
					$total_leave_pdf = $total_days_away / $no_hrs_of_work.' day(s)';
				}

			} else {

				if(!isset($no_hrs_of_work)){
					$total_leave_pdf = $total_days_away / 8 .' day(s)';
				} else {
					$total_leave_pdf = $total_days_away / $no_hrs_of_work.' day(s)';
				}
				
			}
		}

		$approved_by = ucfirst($this->session->userdata('user_first_name')).' '.ucfirst($this->session->userdata('user_last_name'));

		$content = '<h1 class="title">LEAVE REQUEST FORM</h1>';
		$content .= '<img src="./img/logo_leave_form.png" class="logo" />';
		$content .= '<div class="body1">';
			$content .= '<h1>APPLICATION INFORMATION:</h1><br><br>';
			$content .= '<span class="leave-info">Date Applied: </span><span class="leave-info-data" style="position: absolute; left: 150px;">'.$date_applied.'</span>';
			$content .= '<span class="leave-info" style="position: absolute; left: 370px;">Start Date of Leave: </span><span class="leave-info-data" style="position: absolute; left: 560px;">'.$start_day.'</span><br><br>';
			
			$content .= '<span class="leave-info">Name: </span><span class="leave-info-data" style="position: absolute; left: 150px;">'.$first_name.' '.$last_name.'</span>';
			$content .= '<span class="leave-info" style="position: absolute; left: 370px;">End Date of Leave: </span><span class="leave-info-data" style="position: absolute; left: 560px;">'.$end_day.'</span><br><br>';

			$content .= '<span class="leave-info">Position: </span><span class="leave-info-data" style="position: absolute; left: 150px;">'.$role.'</span>';
			$content .= '<span class="leave-info" style="position: absolute; left: 370px;">Date Return: </span><span class="leave-info-data" style="position: absolute; left: 560px;">'.$date_return.'</span><br><br>';

		$content .= '</div>';
		$content .= '<div class="body2">';
			$content .= '<h1>LEAVE INFORMATION:</h1><br><br>';
			$content .= '<span class="leave-info">Type of Leave: </span>';
			$content .= '<span class="leave-info-data" style="position: absolute; left: 400px;">'.$leave_type.'</span><br><br>';

			$content .= '<span class="leave-info">No. of Days Leave: </span>';
			$content .= '<span class="leave-info-data" style="position: absolute; left: 400px;">'.$total_leave_pdf.'</span><br><br>';

			$content .= '<span class="leave-info">No. of Public Holidays Included: </span>';
			$content .= '<span class="leave-info-data" style="position: absolute; left: 400px;">'.$holiday_leave.' day(s)</span><br><br>';

		if ($leave_type_id < '5'){

			if ($holiday_leave == 0){
				$content .= '<span class="leave-info">'.$leave_type.' Remaining: </span>';
				$content .= '<span class="leave-info-data" style="position: absolute; left: 400px;">'.round($total_leave / $no_hrs_of_work, 2).' days ('.$total_leave.' hrs)</span><br><br>';
			} else {

				$total_leave_holiday_days = round($total_leave / $no_hrs_of_work, 2);
				$holiday_hrs = $holiday_leave * $no_hrs_of_work;
				$total_leave_holiday_hrs = $total_leave;

				$content .= '<span class="leave-info">'.$leave_type.' Remaining: </span>';
				$content .= '<span class="leave-info-data" style="position: absolute; left: 400px;">'.$total_leave_holiday_days.' days ('.$total_leave_holiday_hrs.' hrs)</span><br><br>';
			}
		}

			$content .= '<span class="leave-info">Purpose:</span><br>';
			$content .= '<span class="leave-info-data"> &nbsp;&nbsp;&nbsp;&nbsp; '.$details.'</span><br><br>';

			if ($user_supervisor_id != 3){
				$content .= '<span class="leave-info">Direct Report Comments:</span><br>';
				$content .= '<span class="leave-info-data"> &nbsp;&nbsp;&nbsp;&nbsp; '.$direct_report_comments.'</span><br><br>';
				$content .= '<span class="leave-info">Managing Director Comments:</span><br>';
				$content .= '<span class="leave-info-data"> &nbsp;&nbsp;&nbsp;&nbsp; '.$md_comments.'</span><br><br>';
			} else {
				$content .= '<span class="leave-info">Managing Director Comments:</span><br>';
				$content .= '<span class="leave-info-data"> &nbsp;&nbsp;&nbsp;&nbsp; '.$md_comments.'</span><br><br>';
			}

		$content .= '</div>';
		$content .= '<div class="body3">';
			$content .= '<span class="approval">Approved by (Direct Reporting): </span><span class="approval_name" style="position: absolute; left: 380px;">'.$superv_first_name.' '.$superv_last_name.'</span><br><br>';
			$content .= '<span class="approval">Managing Director Authorisation: </span><span class="approval_name" style="position: absolute; left: 380px;">'.$approved_by.'</span>';
		$content .= '</div>';

		$file_name = 'leave_form_'.$leave_request_id;
		$my_pdf = $this->html_form($content,'portrait','A4',$file_name,'leave_form');
		echo $my_pdf;
	}

	private function html_form($content,$orientation,$paper,$file_name,$folder,$auto_clear=TRUE){
		$document = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><title></title>';
		$document .= '<link type="text/css" href="./css/pdf.css" rel="stylesheet" />';
		$document .= '</head><body>';
		$document .= $content;
		$document .= '</body></html>';
		return $this->pdf_create($document,$orientation,$paper,$file_name,$folder,$auto_clear);
	}

	private function pdf_create($html, $orientation='portrait', $paper='A4',$filename,$folder_type='general' ,$auto_clear=TRUE,$stream=TRUE){
		
		$dompdf = new DOMPDF();
		$html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

		$dompdf->set_paper($paper,$orientation);
		$dompdf->load_html($html);
		$dompdf->render();

		$canvas = $dompdf->get_canvas();
		$date_gen = date("jS F, Y");

		$user_prepared = ucfirst($this->session->userdata('user_first_name')).' '.ucfirst($this->session->userdata('user_last_name'));

		if($orientation == 'portrait'){
			$font = Font_Metrics::get_font("helvetica", "bold");
			$canvas->page_text(535,10, "Page: {PAGE_NUM} of {PAGE_COUNT} ", $font, 8, array(0,0,0));
			$canvas->page_text(15, 810, "Page: {PAGE_NUM} of {PAGE_COUNT}                   Produced: $date_gen                   Prepared By: $user_prepared", $font, 8, array(0,0,0));
			
		}else{
			$font = Font_Metrics::get_font("helvetica", "bold");
			$canvas->page_text(780,10, "Page: {PAGE_NUM} of {PAGE_COUNT} ", $font, 8, array(0,0,0));
			$canvas->page_text(20, 800, "Page: {PAGE_NUM} of {PAGE_COUNT}                   Produced: $date_gen                   Prepared By: $user_prepared", $font, 8, array(0,0,0));
		}

		$output = $dompdf->output();

		//$filename .= '-'.date("d-m-Y-His");

		if($auto_clear){
			$this->delete_dir('docs/'.$folder_type);  #remove folder and contents
		}

		//create the folder if it's not already exists
		if(!is_dir('docs/'.$folder_type)){
			mkdir('docs/'.$folder_type,0755,TRUE);
		}
		write_file('docs/'.$folder_type.'/'.$filename.'.pdf','');

		file_put_contents('docs/'.$folder_type.'/'.$filename.'.pdf', $output);

		//return $filename;
		//unlink('docs/'.$folder_type.'/'.$filename.'.pdf');
		$dompdf->stream($filename.'.pdf', array("Attachment" => false));
	}

	private function delete_dir($dir) {
		$handle=opendir($dir);
		while (($file = readdir($handle))!==false) {
			@unlink($dir.'/'.$file);
		}
		closedir($handle);
		rmdir($dir);
	}

	public function check_pending_leave_count(){

		$check_pending = explode('|',$_POST['ajax_var']);
		
		$user_id = $check_pending['0'];
		$type = $check_pending['1'];
		$no_hrs_of_work = $check_pending['2'];

		if ($type == 1){

			$pending_total_annual = $this->user_model->check_pending_total_annual($user_id);
			$data['pending_total_annual'] = $pending_total_annual->result();
			
			$pending_total_annual = $data['pending_total_annual'][0]->pending_total_annual;

			$pending_total_holiday_annual = $this->user_model->check_pending_total_annual_holiday($user_id);
			$data['pending_total_holiday_annual'] = $pending_total_holiday_annual->result();
			$pending_total_holiday_annual = $data['pending_total_holiday_annual'][0]->holiday_annual;

			$total_annual_holiday = $no_hrs_of_work * $pending_total_holiday_annual;
			$total_pending_annual = $pending_total_annual - $total_annual_holiday;

			echo $total_pending_annual;

		} else if ($type == 2 || $type == 3 || $type == 4) {

			$pending_total_personal = $this->user_model->check_pending_total_personal($user_id);
			$data['pending_total_personal'] = $pending_total_personal->result();

			$pending_total_personal = $data['pending_total_personal'][0]->pending_total_personal;

			$pending_total_holiday_personal = $this->user_model->check_pending_total_personal_holiday($user_id);
			$data['pending_total_holiday_personal'] = $pending_total_holiday_personal->result();
			$pending_total_holiday_personal = $data['pending_total_holiday_personal'][0]->holiday_personal;

			$total_personal_holiday = $no_hrs_of_work * $pending_total_holiday_personal;
			$total_pending_personal = $pending_total_personal - $total_personal_holiday;

			echo $total_pending_personal;

		} else {
			echo 'others to, walang gagawin';
		}

		
	}

	public function fetch_all_leave_dates(){

		$exists = '';

		$check_leave_date = explode('|',$_POST['ajax_var']);
		
		$user_id = $check_leave_date['0'];
		$apply_date =  date('d/m/Y', strtotime($check_leave_date['1']));

		$format_apply_date = DateTime::createFromFormat('d/m/Y', $apply_date);
		$apply_date = $format_apply_date->format('Y-m-d');
		$get_apply_date = new DateTime($apply_date);

		$all_leave_dates_by_user = $this->user_model->fetch_all_leave_dates($user_id);
		$data['all_leave_dates_by_user'] = $all_leave_dates_by_user->result();

		foreach ($data['all_leave_dates_by_user'] as $row) {

			$f_start_date = date('d/m/Y', $row->start_day_of_leave);
			$f_end_date = date('d/m/Y', $row->end_day_of_leave);

			$format_start_date = DateTime::createFromFormat('d/m/Y', $f_start_date);
			$format_end_date = DateTime::createFromFormat('d/m/Y', $f_end_date);
			
			$start_date = $format_start_date->format('Y-m-d');
			$end_date = $format_end_date->format('Y-m-d');

			$get_start_date = new DateTime($start_date);
			$get_end_date = new DateTime($end_date);

			if(($get_apply_date->getTimestamp() >= $get_start_date->getTimestamp()) && ($get_apply_date->getTimestamp() <= $get_end_date->getTimestamp())){
				$exists = 1;
			}
		}

		echo $exists;

	}

	public function calc_leave_points(){

		$leave_alloc_all = $this->user_model->fetch_leave_alloc_all();
		$leave_alloc_all = $leave_alloc_all->result();

		// var_dump($leave_alloc_all);

		$static_defaults = $this->user_model->select_static_defaults();
		$static_defaults = $static_defaults->result();	

		$annual_default_points = $static_defaults[0]->annual_leave_daily_rate;
		$personal_default_points = $static_defaults[0]->personal_leave_daily_rate;

		$vacation_default_points = $static_defaults[0]->vacation_leave_daily_rate;
		$sick_default_points = $static_defaults[0]->sick_leave_daily_rate;

		foreach ($leave_alloc_all as $row) {

			$user_id = $row->user_id;			
			$annual_manual_entry = $row->annual_manual_entry;			
			$personal_manual_entry = $row->personal_manual_entry;
			$last_annual_accumulated = $row->annual_accumulated;			
			$last_personal_accumulated = $row->personal_accumulated;
			$sched_work = $row->sched_of_work;
			$no_hrs_of_work = $row->no_hrs_of_work;
			$date_log = date('Y-m-d', $row->date_log);
			$today = date('Y-m-d');
			$day_yesterday = date('Y-m-d',strtotime("-1 days"));
			//$day_yesterday = date('Y-m-d',strtotime("2017-06-14"));
			
			$annual_consumed = $this->user_model->get_total_leave_annual($user_id);
			$annual_consumed = $annual_consumed->row();

			$personal_consumed = $this->user_model->get_total_leave_personal($user_id);
			$personal_consumed = $personal_consumed->row();

			$q_annual_ph_holidays = $this->user_model->get_annual_ph_holidays($user_id);
			$annual_ph_holidays = $q_annual_ph_holidays->row();

			$q_sick_ph_holidays = $this->user_model->get_sick_ph_holidays($user_id);
			$sick_ph_holidays = $q_sick_ph_holidays->row();	

			if (strtotime($date_log) <= strtotime($day_yesterday)){
				$days = (strtotime($date_log) - strtotime($day_yesterday)) / (60 * 60 * 24);
			} else {
				$days = 0;
			}

			$date_diff_converted = abs($days);
			$counted_days = 0;

			//echo '<script>alert("'.$date_diff_converted.'");</script>';

			for ($i=0; $i <= $date_diff_converted; $i++) { 				
				$day_in_week = date('w', strtotime($date_log));
				$check_sched_day = strpos($sched_work, $day_in_week);

				if ($check_sched_day !== false && $date_diff_converted != 0){					
					$counted_days = $counted_days + 1;								
				}
				$date_log = date('Y-m-d', strtotime('+1 day', strtotime($date_log)));	
			}

			//echo '<script>alert("'.$counted_days.'");</script>';

			$fetch_user = $this->user_model->fetch_user($user_id);
			$data['user'] = $fetch_user->result();

			if( $data['user'][0]->is_offshore == 0){		
				//echo '<script>alert("local");</script>';

				$annual_ph_holidays_count = $annual_ph_holidays->ph_holidays * $no_hrs_of_work;
				$sick_ph_holidays_count = $sick_ph_holidays->ph_holidays * $no_hrs_of_work;

				$starting_annual_hrs = $annual_manual_entry * $no_hrs_of_work;
				$starting_personal_hrs = $personal_manual_entry * $no_hrs_of_work;

				$annual_accumulated = $counted_days * $annual_default_points;
				$personal_accumulated = $counted_days * $personal_default_points;
				
				$total_annual = $annual_accumulated + $starting_annual_hrs;
				$total_personal = $personal_accumulated + $starting_personal_hrs;

				$total_annual_final = $total_annual - $annual_consumed->used_annual + $annual_ph_holidays_count;
				$total_personal_final = $total_personal - $personal_consumed->used_personal + $sick_ph_holidays_count;

				$this->user_model->update_earned_points($annual_accumulated, $personal_accumulated, $user_id);
				$this->user_model->update_total_leave(round($total_annual_final, 2), round($total_personal_final, 2), $user_id);

			} else {
				// echo '<script>alert("offshore");</script>';

				$last_month_update_offshore = $row->last_month_update_offshore;
				$annual_earned_offshore = $row->annual_earned_offshore;			
				$personal_earned_offshore = $row->personal_earned_offshore;

				$annual_ph_holidays_count = $annual_ph_holidays->ph_holidays * $no_hrs_of_work;
				$sick_ph_holidays_count = $sick_ph_holidays->ph_holidays * $no_hrs_of_work;

				// print_r($user_id.'|'.$annual_ph_holidays->ph_holidays.'<br><br>');

				// $date_today = date('2018-07-01');
				$date_today = date('Y-m-d');
				$date = date_create($date_today);
				date_modify($date, '-1 month');
				$last_month = date_format($date,"m");

				$current_month = date('m');
				// $current_month = '10';

				if (ltrim($current_month, '0') != $last_month_update_offshore){
					if ($last_month < $current_month){
						$annual_earned_offshore = $annual_earned_offshore + $no_hrs_of_work;
						$personal_earned_offshore = $personal_earned_offshore + $no_hrs_of_work * 0.58;

						$total_annual_points = ($annual_manual_entry * $no_hrs_of_work) + $annual_earned_offshore + $annual_ph_holidays_count;
						$total_annual = $total_annual_points - $annual_consumed->used_annual;

						$total_personal_points = ($personal_manual_entry * $no_hrs_of_work) + $personal_earned_offshore + $sick_ph_holidays_count;
						$total_personal = $total_personal_points - $personal_consumed->used_personal;

						$this->user_model->update_total_leave($total_annual, $total_personal, $user_id);
						$this->user_model->update_earned_offshore($annual_earned_offshore, $personal_earned_offshore, $current_month, $user_id);
					} else {
						$total_annual_points = ($annual_manual_entry * $no_hrs_of_work) + $annual_earned_offshore + $annual_ph_holidays_count;
						$total_annual = $total_annual_points - $annual_consumed->used_annual;

						$total_personal_points = ($personal_manual_entry * $no_hrs_of_work) + $personal_earned_offshore + $sick_ph_holidays_count;
						$total_personal = $total_personal_points - $personal_consumed->used_personal;

						$this->user_model->update_total_leave($total_annual, $total_personal, $user_id);
					}
				} else {
						
					$total_annual_points = ($annual_manual_entry * $no_hrs_of_work) + $annual_earned_offshore + $annual_ph_holidays_count;
					$total_annual = $total_annual_points - $annual_consumed->used_annual;

					$total_personal_points = ($personal_manual_entry * $no_hrs_of_work) + $personal_earned_offshore + $sick_ph_holidays_count;
					$total_personal = $total_personal_points - $personal_consumed->used_personal;

					$this->user_model->update_total_leave($total_annual, $total_personal, $user_id);
				}
			}
		}
		
	}
}

?>