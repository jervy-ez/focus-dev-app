<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Projects extends MY_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->module('users');
		$this->load->model('projects_m');
		$this->load->module('company');
		$this->load->module('works');
		$this->load->model('works_m');
		$this->load->module('admin');
		$this->load->model('admin_m');
		$this->load->module('invoice');
		if(!$this->users->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;
	}
	

	public function index(){
		$this->users->_check_user_access('projects',1);
		$data['main_content'] = 'projects_v';
		$data['screen'] = 'Projects';
		$this->load->view('page', $data);
	}
	
	public function display_work_attachments(){
		$this->works->display_work_attachments();
	}

	public function works_view(){
	  	$this->works->works_view();
	}

	public function show_project_invoice(){
	  	$this->invoice->project_invoice();		
	}
	
	public function variations_view(){
	  	$this->works->variations_view();
	}
	public function work_details(){
		$this->works->work_details();
	}
	
	public function display_all_works_query($prjc_id){
	  	$this->works->display_all_works_query($prjc_id);
	}
	
	public function display_all_variations_query($prjc_id){
	  	$this->works->display_all_variations_query($prjc_id);
	}

	public fun