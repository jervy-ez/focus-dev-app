<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MY_Controller{

	

	function __construct(){

		parent::__construct();

		$this->load->library('form_validation');

		$this->load->library('upload');

		$this->load->library('session');

		$this->load->model('Admin_m');

		date_default_timezone_set("Australia/Perth");

	}



	public function index(){	

		if(!$this->_is_logged_in() ): 		

	  		redirect('signin', 'refresh');

	 	else:

	 	 redirect('Dashboard','refresh');

		endif;

	}



	public function clear_apost(){

		foreach ($_POST as $key => $value) {

			$_POST[$key] = str_replace("'","&apos;",$value);

		}

	}



	public function signin(){

		if($this->_is_logged_in() ):

			redirect('','refresh');

		endif;



		$userdata = 0;



		$this->clear_apost();



		$config = array(

			array('field' => 'user_name','label' => 'User Name','rules' => 'trim|required'),

			array('field' => 'password','label' => 'Password','rules' => 'trim|required'

			)

		);



		$password = $this->input->post('password', true);

		/*

			$encrypted_string = $this->encrypt->encode($password);

			echo "<p>$encrypted_string </p>";

			$decrypted_string = $this->encrypt->decode($encrypted_string);

			echo "<p>$decrypted_string </p>";

		*/

		$this->form_validation->set_rules($config);

			

		if($this->form_validation->run() === false){



			$data['error'] = validation_errors();

			$data['main_content'] = 'signin';

			$this->load->view('page', $data);



		}else{

			$user_name = $this->input->post('user_name', true);

			$password 	= $this->input->post('password', true);



			$q_get_login = $this->Admin_m->get_login($user_name,$password);

			$user_data_arr = $q_get_login->result();





			if($user_data_arr){

				$userdata = $user_data_arr[0];



				if($userdata->pwrd_unix_epry < time()){

					$userdata = 1;

				}

			}



			switch($userdata){

				case "0":

					$data['error'] = "Wrong Username or Password";

					$data['main_content'] = 'signin';

					$this->load->view('page', $data);

					break;

				case "1":

					$data['error'] = "Your password needs updating";

					$data['main_content'] = 'repass';

					$data['user_id'] = $user_data_arr[0]->user_id;

					$data['user_name'] = $user_data_arr[0]->user_name;

					$this->load->view('page', $data);

					break;

				default:		



					$data['user_id'] = $userdata->user_id;					 

					$data['user_role_id'] = $userdata->role_id;

					$data['user_first_name'] = $userdata->first_name;

					$data['user_last_name'] = $userdata->last_name;

					$data['user_profile_photo'] = $userdata->profile_photo; 

					$data['user_role_type'] = $userdata->role_types;

					$data['is_admin'] = $userdata->is_admin;

					$data['logged_in'] = true;



					$q_fetch_admin_defaults = $this->Admin_m->fetch_admin_defaults();

					$admin_defaults_arr = $q_fetch_admin_defaults->result();

					$admin_defaults = $admin_defaults_arr[0];



					$data['admin_pwrd_default'] = $admin_defaults->pwrd_default;

					$data['admin_pwrd_life_exp'] = $admin_defaults->pwrd_life_exp;

					$data['admin_general_email'] = $admin_defaults->general_email;





				//	var_dump($data);



			    //  $this->_fetch_system_defaults();

					$this->session->set_userdata($data);

					redirect('Dashboard');

					break;

			}

		}

	}



	public function changePwrd(){
		$this->clear_apost();
		$userdata = '';

		$data['main_content'] = 'repass';


		$set_user_id = $this->input->post('userId');
		$userName = $this->input->post('userName');
		$rePassword = $this->input->post('rePassword');


		$data['user_id'] = $set_user_id;
		$data['user_name'] = $userName;


		$this->form_validation->set_rules(
			'newPassword', 'New Password',
			'required|min_length[8]|is_unique[users_pwd.pwd]',
			array(
				'required' 	=> 'You have not provided %s.',
				'is_unique' => 'This %s already exists. Please try another.'
				)
			);

		$this->form_validation->set_rules('rePassword', 'Password Confirmation', 'required|min_length[8]|matches[newPassword]');
		$this->form_validation->set_error_delimiters('<p class="p-0 m-0"> &nbsp;  <em class="fas fa-exclamation-circle"></em> &nbsp;', '</p>');


		if ($this->form_validation->run() == FALSE){
			$str_msgs = strval( validation_errors() );
			$data['error'] = $str_msgs;
			$this->load->view('page', $data);
		}else{
			$this->load->module('Users');
			$this->load->model('Users_m');

			$q_fetch_admin_defaults = $this->Admin_m->fetch_admin_defaults();
			$admin_defaults_arr = $q_fetch_admin_defaults->result();
			$admin_defaults = $admin_defaults_arr[0];


			$data['admin_pwrd_default'] = $admin_defaults->pwrd_default;
			$data['admin_pwrd_life_exp'] = $admin_defaults->pwrd_life_exp;
			$data['admin_general_email'] = $admin_defaults->general_email;


			$pwd_exp = $admin_defaults->pwrd_life_exp;
			$date_pwd_exp = new DateTime('now');
			$date_pwd_exp->modify('+'.$pwd_exp);
			$date_pwd_exp = $date_pwd_exp->format('d/m/Y');

			$this->Users_m->update_user_pwrd($set_user_id,$date_pwd_exp,$rePassword);

			$q_get_login = $this->Admin_m->get_login($userName,$rePassword);
			$user_data_arr = $q_get_login->result();

			$userdata = $user_data_arr[0];

			$data['user_id'] = $set_user_id;					 
			$data['user_role_id'] = $userdata->role_id;
			$data['user_first_name'] = $userdata->first_name;
			$data['user_last_name'] = $userdata->last_name;
			$data['user_profile_photo'] = $userdata->profile_photo; 
			$data['user_role_type'] = $userdata->role_types;
			$data['is_admin'] = $userdata->is_admin;
			$data['logged_in'] = true;


			$q_user_access_list = $this->Admin_m->user_access_list($set_user_id);

			foreach ($q_user_access_list->result() as $user_access):
				if( $user_access->can_control == 'on' ){
					$data[$user_access->local_name] = 2;
				}elseif( $user_access->can_access == 'on' ){
					$data[$user_access->local_name] = 1;
				}else{
					$data[$user_access->local_name] = 0;
				}
			endforeach;


			$this->session->set_userdata($data);
			redirect('Dashboard');
		}
	}


	public function get_user_access($set_user_id){

		return $this->Admin_m->user_access_list($set_user_id);

	}





	public function get_all_access_area(){

		return $this->Admin_m->fetch_access_areas();

	}



	public function insert_access_areas($access_area_id,$user_id){

		 $this->Admin_m->insert_access_control($access_area_id,$user_id);

	}





	public function verCheck(){

		  echo CI_VERSION;

	}



	public function signout(){

		$userid = $this->session->userdata('user_id');

		$this->Admin_m->log_out($userid);

		$this->session->sess_destroy();

		redirect('');

	}



	public function test(){

		echo "<p>Test</p>";

	}



	function _is_logged_in(){

		if($this->session->userdata('logged_in')){

			return true;

		}else{

			return false;

		}

	}

}