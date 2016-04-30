<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jervy Zaballa */

class Dashboard extends MX_Controller{
	
	function __construct(){
		parent::__construct();
		//$this->load->model('dashboard_m');
	}	

	function index(){
		$data['main_content'] = 'home';	
		$data['test'] = 'Test Page';
		$this->load->view('page', $data);
	}
/*
	public function blah(){

	}
*/

	
}