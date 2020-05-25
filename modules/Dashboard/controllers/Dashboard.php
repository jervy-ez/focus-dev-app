<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jervy Zaballa */

class Dashboard extends MY_Controller{
	
	function __construct(){
		parent::__construct();		
		$this->load->module('Admin');

		if(!$this->admin->_is_logged_in() ): 		
	  		redirect('signin', 'refresh');
		endif;
		date_default_timezone_set("Australia/Perth");

	}
	

	function index(){
		//var_dump( $this->session->userdata('role_id') );

		$data['main_content'] = 'dashboard_home';

		$data['added_scripts'] = 'js_file';

		//$data['menu'] = 'menu';
		//$this->load->module('menu/menu');
		//$this->menu->index();
		$this->load->view('page', $data);
	}		
}