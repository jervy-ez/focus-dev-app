<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jervy Zaballa */

class Signin extends MX_Controller{
	
	function __construct(){
		parent::__construct();

		$this->load->model('signin_m');
		$this->load->module('Functions'); // keep lowercase for module addition and function calls


		if($this->session->userdata('id')){
			$link = base_url().'Scheduling';
			echo '<script> window.location = "'.$link.'"; </script>';
		}	

	}

	function logout(){
		$this->session->sess_destroy();
		echo '<script> window.location = "https://my.bicol-u.edu.ph/alpha/default/user/logout"; </script>';
	}

	function index(){
		$data['main_content'] = 'login_form';
		$my_ip = $this->functions->_getUserIP();

	
		$this->functions->_clear_apost();

		$user_email = $this->input->post('user_email', true);

		$this->form_validation->set_rules('user_email', 'User Email', 'trim|required|xss_clean|valid_email');

		//$password = md5($password);


		if($this->form_validation->run() === false){
			$data['error' ] = validation_errors();
			$this->load->view('page', $data);
			//error details goes here
		}else{


			// database process and data usage goes here


			// this part should be once logged in
		//	$q_user_data = $this->signin_m->get_user_event($user_email,$my_ip);
		//	$u_data_obj = $q_user_data->result();
		//	$user_data =  $u_data_obj[0];
			// this part should be once logged in


			$this->functions->_check_user_signin($my_ip,$user_email);




		}



	}




 

	
}