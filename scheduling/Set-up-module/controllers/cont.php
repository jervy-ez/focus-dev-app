<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jervy Zaballa */

class Scheduling extends MX_Controller{
	
	function __construct(){
		parent::__construct();
		$this->load->module('functions');
		$this->load->model('model_m'); //scheduling_m
	}	

	function index(){
		$data['main_content'] = 'page_main'; //scheduling_home
		$data['test'] = 'Test Page';
	//	$data['courses_data'] = $this->_fetch_courses();
		$this->load->view('page', $data);	
	}

	function blah(){
		$test_q = $this->functions_m->test();
		$query = $test_q->result();
		// var_dump($query);
	}
}