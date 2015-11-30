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

		$this->load->model('dashboard_m');

		if(!$this->users->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;

		if(isset($_GET['delete_rfc'])){
			$revenue_forecast_id = $_GET['delete_rfc'];
			$this->dashboard_m->diactivate_stored_revenue_forecast($revenue_forecast_id);
			$this->session->set_flashdata('record_update','Record is now deleted.');
			redirect('/dashboard/sales_forecast');
		}

		date_default_timezone_set("Australia/Perth");
	}	

	function index(){
		$this->users->_check_user_access('dashboard',1);
		$data['main_content'] = 'dashboard_home';
		$data['screen'] = 'Dashboard';
		$this->load->view('page', $data);
	}

	function _fetch_pm_data($minYear,$maxYear){

		$project_manager_q = $this->user_model->fetch_user_by_role(3);
		$project_manager = $project_manager_q->result();


		if( date("m") > 6 ){
			// Jul-Dec current year to next year Jan-Jun
/*
			$minYear = date("Y");
			$maxYear = date("Y")+1;
*/
		}else{
			// Jul-Dec last year to Jan-Jun current year
/*
			$minYear = date("Y")-1;
			$maxYear = date("Y");
*/			
		}


		foreach ($project_manager as $row){
			if($row->user_id != '29'){
				echo "['".$row->user_first_name." ".$row->user_last_name."',";
				$pm_id = $row->user_id;


				for($i=0; $i < 12; $i++){

					if($i > 6){
						$year = $maxYear; 
						$month = $i-6;
					}else{
						$year = $minYear; 
						$month = 6+$i;
					}

					$date_a = '1-'.($month).'-'.($year);
					$date_b = '1-'.($month == 12 ? 1 : $month+1).'-'.($month == 12 ? $year+1 : $year);

					$date_a_tmsp = strtotime(str_replace('/', '-', $date_a));
					$date_b_tmsp = strtotime(str_replace('/', '-', $date_b));


					$getSales_perMonth_q = $this->dashboard_m->getSales_perMonth($pm_id,$date_a_tmsp,$date_b_tmsp);
					//$sales_perMonth = $getSales_perMonth_q->result();
					$sales_perMonth = array_shift($getSales_perMonth_q->result_array());

				//	$arr_prc = implode('', $sales_perMonth);

//var_dump($sales_perMonth);


				if($sales_perMonth){
					$arr_prc = implode('', $sales_perMonth);
					echo $arr_prc.',';
				}else{
					echo '0,';
				}




		//$data['stored_revenue_forecast'] = $stored_revenue_forecast->result();




										// echo $date_a.' ---- '.$date_b.' <br />';


				}


									//$minYear
									//$maxYear

				echo '],
				';

			}







	}


	}




	public function sales_forecast(){

		$data['main_content'] = 'sales_forecast_page';
		$data['screen'] = 'Sales Forecast';

		$focus = $this->admin_m->fetch_all_company_focus();
		$data['focus'] = $focus->result();

		$stored_revenue_forecast = $this->dashboard_m->list_stored_revenue_forecast();
		$data['stored_revenue_forecast'] = $stored_revenue_forecast->result();

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$data['project_manager'] = $project_manager->result();

		if( date("m") > 6 ){
			// Jul-Dec current year to next year Jan-Jun
			$minYear = date("Y");
			$maxYear = date("Y")+1;
		}else{
			// Jul-Dec last year to Jan-Jun current year
			$minYear = date("Y")-1;
			$maxYear = date("Y");
		}

		$data['minYear'] = $minYear;
		$data['maxYear'] = $maxYear;

		if($this->uri->segment(3) == 'year_selection' && $this->uri->segment(4) != ''){
			$data['year_selection'] = $this->uri->segment(4);

			$year_slctn_arr = explode('-', $this->uri->segment(4));

			$minYear = $year_slctn_arr[0];
			$maxYear = $year_slctn_arr[1];

			$data['minYear'] = $minYear;
			$data['maxYear'] = $maxYear;


		}else{
			$data['year_selection'] = $minYear.'-'.$maxYear;
		}

		$foreCastYear = $minYear.'-'.$maxYear;

		$revenue_forecast = $this->dashboard_m->get_revenue_forecast($foreCastYear);
		$data['revenue_forecast'] = $revenue_forecast->result();

		$pm_names = $this->dashboard_m->get_pm_names();
		$data['pm_names'] = $pm_names->result();

		$this->load->view('page', $data);
	}

	function _clear_apost(){
		foreach ($_POST as $key => $value) {
			$_POST[$key] = str_replace("'","&apos;",$value);
		}
	}

	function _form_flash_data($arr_data = array()){
		$this->_clear_apost();
		foreach ($arr_data as $key => $value) {
			if($_POST[$key] == ''){
				$this->session->set_flashdata('error_'.$key,'error');
			}else{				
				$this->session->set_flashdata($key,$value);
			}
		}
	}

	public function add_data_sales_forecast(){

		$this->form_validation->set_rules('data_type', 'Data Type','trim|required|xss_clean');
		$this->form_validation->set_rules('data_year', 'Year','trim|required|xss_clean');
		$this->form_validation->set_rules('focus_company', 'Company','trim|required|xss_clean');
		$this->form_validation->set_rules('data_name', 'Data Name','trim|required|xss_clean');
		$this->form_validation->set_rules('data_amount', 'Total Amount','trim|required|xss_clean');

		if($this->form_validation->run() === false){
			$this->_clear_apost();
			$this->session->set_flashdata('error_add_fs', validation_errors());
			$this->_form_flash_data($_POST);
		}else{
			$data_type = $this->input->post('data_type');
			$data_year = $this->input->post('data_year');
			$focus_company = $this->input->post('focus_company');
			$data_name = $this->input->post('data_name');
			$data_amount = str_replace(',', '',$this->input->post('data_amount'));

			$jul = str_replace(',', '',$this->input->post('jul'));
			$aug = str_replace(',', '',$this->input->post('aug'));
			$sep = str_replace(',', '',$this->input->post('sep'));
			$oct = str_replace(',', '',$this->input->post('oct'));
			$nov = str_replace(',', '',$this->input->post('nov'));
			$dec = str_replace(',', '',$this->input->post('dec'));
			$jan = str_replace(',', '',$this->input->post('jan'));
			$feb = str_replace(',', '',$this->input->post('feb'));
			$mar = str_replace(',', '',$this->input->post('mar'));
			$apr = str_replace(',', '',$this->input->post('apr'));
			$may = str_replace(',', '',$this->input->post('may'));
			$jun = str_replace(',', '',$this->input->post('jun'));


			$this->session->set_flashdata('record_update','Record is now submited.');

			$this->dashboard_m->insert_revenue_forecast($data_type,$data_year,$focus_company,$data_name,$data_amount,$jul,$aug,$sep,$oct,$nov,$dec,$jan,$feb,$mar,$apr,$may,$jun);
		}

		redirect('/dashboard/sales_forecast');
	}

	public function update_sales_forecast(){

		$this->form_validation->set_rules('data_type', 'Data Type','trim|required|xss_clean');
		$this->form_validation->set_rules('data_year', 'Year','trim|required|xss_clean');
		$this->form_validation->set_rules('focus_company', 'Company','trim|required|xss_clean');
		$this->form_validation->set_rules('data_name', 'Data Name','trim|required|xss_clean');
		$this->form_validation->set_rules('data_amount', 'Total Amount','trim|required|xss_clean');

		if($this->form_validation->run() === false){
			$this->_clear_apost();
			$this->session->set_flashdata('error_add_fs', validation_errors());
			$this->session->set_flashdata('is_update','1');
			$this->_form_flash_data($_POST);
		}else{
			$data_type = $this->input->post('data_type');
			$data_year = $this->input->post('data_year');
			$focus_company = $this->input->post('focus_company');
			$data_name = $this->input->post('data_name');
			$data_amount = str_replace(',', '',$this->input->post('data_amount'));

			$jul = str_replace(',', '',$this->input->post('jul'));
			$aug = str_replace(',', '',$this->input->post('aug'));
			$sep = str_replace(',', '',$this->input->post('sep'));
			$oct = str_replace(',', '',$this->input->post('oct'));
			$nov = str_replace(',', '',$this->input->post('nov'));
			$dec = str_replace(',', '',$this->input->post('dec'));
			$jan = str_replace(',', '',$this->input->post('jan'));
			$feb = str_replace(',', '',$this->input->post('feb'));
			$mar = str_replace(',', '',$this->input->post('mar'));
			$apr = str_replace(',', '',$this->input->post('apr'));
			$may = str_replace(',', '',$this->input->post('may'));
			$jun = str_replace(',', '',$this->input->post('jun'));

			$revenue_forecast_id = $this->input->post('rfc_token');

			$this->session->set_flashdata('record_update','Record is now updated.');

			$this->dashboard_m->update_revenue_forecast($revenue_forecast_id,$data_type,$data_year,$focus_company,$data_name,$data_amount,$jul,$aug,$sep,$oct,$nov,$dec,$jan,$feb,$mar,$apr,$may,$jun);
		}

		redirect('/dashboard/sales_forecast', 'refresh');

	}
}