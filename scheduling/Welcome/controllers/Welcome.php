<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jervy Zaballa */

class Welcome extends MX_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->module('functions');
		//$this->functions->_is_logged_in();
		$this->load->model('welcome_m'); //scheduling_m
	}	

	function index(){
		$data['main_content'] = 'welcome_main'; //scheduling_home
		$data['test'] = 'Test Page';
		$data['title'] = 'Welcome';
	//	$data['courses_data'] = $this->_fetch_courses();
		$this->load->view('page', $data);	
	}


}