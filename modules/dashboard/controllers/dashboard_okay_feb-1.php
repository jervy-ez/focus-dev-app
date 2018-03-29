<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jervy Zaballa */

class Dashboard extends MY_Controller{
	
	function __construct(){
		parent::__construct();

		$this->load->module('users');
		$this->load->model('user_model');

		$this->load->module('projects');
		$this->load->model('projects_m');

		$this->load->module('admin');
		$this->load->model('admin_m');


		$this->load->module('wip');
		$this->load->model('wip_m');

		$this->load->module('invoice');


		$this->load->module('purchase_order');
		$this->load->model('purchase_order_m');

		$this->load->model('dashboard_m');

		if(!$this->users->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;

		$this->load->library('session');


// temporary restriction for dashboaed dev
		$user_role_id = $this->session->userdata('user_role_id');
		if($this->session->userdata('is_admin') == 1 || $user_role_id == 16 || $user_role_id == 3 || $user_role_id == 2 || $user_role_id == 7 || $user_role_id == 6):
		else:		
			redirect('projects', 'refresh');
		endif;
// temporary restriction for dashboaed dev


		if(isset($_GET['delete_rfc'])){
			$revenue_forecast_id = $_GET['delete_rfc'];
			$this->dashboard_m->deactivate_stored_revenue_forecast($revenue_forecast_id);
			$this->session->set_flashdata('record_update','Record is now deleted.');
			redirect('/dashboard/sales_forecast/');
		}

		if(isset($_GET['