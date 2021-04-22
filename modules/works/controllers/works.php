<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Works extends MY_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');	
		$this->load->module('users');
		$this->load->module('company');
		$this->load->model('works_m');

		$this->load->module('invoice');
		$this->load->model('invoice_m');

		//$this->load->module('attachments');
		$this->load->module('send_emails');
		$this->load->model('send_emails_m');
		$this->load->module('variation');
		$this->load->model('variation_m');
		//if($this->session->userdata('is_admin') == 1 ):		
			$this->load->module('admin');
		 	$this->load->model('admin_m');
		//endif;
		$this->load->module('projects');

		$this->load->helper(array('form', 'url','html'));
		//$this->load->model('project_m');
	}
	function index(){
	}
	public function clear_data($data){
		foreach ($data as $key => $value) {
			$data[$key] = str_replace("'","&apos;",$value);
		}
		return $data;
	}

	function works_view(){
		$project_id = $this->uri->segment(3);

		$q_proj = $this->projects_m->fetch_project_details($project_id);
		if($q_proj->num_rows > 0){
			$data = array_shift($q_proj->result_array());
			$projid = $data['project_id'];
		}else{
			$data['error'] = 'Unable to locate record';
		}

		$proj_q = $this->projects_m->select_particular_project($project_id);
		foreach ($proj_q->result_array() as $row){
			$data['job_date'] = $row['job_date'];
			$address_id = $row['site_add'];
			$address_q = $this->company_m->fetch_complete_address($address_id);
			foreach ($address_q->result_array() as $add_row){
				$data['postcode'] = $add_row['postcode'];
				$data['state'] = $add_row['name'];
			}
		}

		$data['projid'] = $projid;
		$data['screen'] = "Works";
		$this->load->view('works_v',$data);
	}

	function variations_view(){		
		$project_id = $this->uri->segment(3);
		$variation_id = $this->uri->segment(4);//$this->session->flashdata('variation_id');
		$q_proj = $this->projects_m->fetch_project_details($project_id);
		if($q_proj->num_rows > 0){
			$data = array_shift($q_proj->result_array());
			$projid = $data['project_id'];
		}else{
			$data['error'] = 'Unable to locate record';
		}

		$proj_q = $this->projects_m->select_particular_project($project_id);
		foreach ($proj_q->result_array() as $row){
			$data['job_date'] = $row['job_date'];
			$address_id = $row['site_add'];
			$address_q = $this->company_m->fetch_complete_address($address_id);
			foreach ($address_q->result_array() as $add_row){
				$data['postcode'] = $add_row['postcode'];
				$data['state'] = $add_row['name'];
			}
		}
		$var_q = $this->variation_m->display_selected_variation($variation_id);
		foreach ($var_q->result_array() as $row){
			$data['acceptance_date'] = $row['acceptance_date'];
		}

		$data['projid'] = $projid;
		$data['screen'] = "Variation Works";
		$this->load->view('variations_v',$data);
	}

	public function list_supplier_category(){
		$supplier_category = $this->projects_m->display_supplier_category();
		foreach ($supplier_category->result_array() as $row){
			if($row['supplier_cat_name'] !== 'Other'){
				echo '<option value="3_'.$row['supplier_cat_id'].'">'.$row['supplier_cat_name'].'</option>';
			}
		}
	}

	function work_details(){
		$this->projects->clear_apost();
		$project_id = $this->uri->segment(3);
		$variation_id = $this->uri->segment(4);

		$q_proj = $this->projects_m->fetch_project_details($project_id);
		if($q_proj->num_rows > 0){
			$data = array_shift($q_proj->result_array());
			$projid = $data['project_id'];
			if($variation_id == ""):
				$projmarkup = $data['markup'];
			else:
				$var_q = $this->variation_m->display_selected_variation($variation_id);
				
				foreach ($var_q->result_array() as $var_row){
					$variation_markup = $var_row['variation_markup'];
				}
				$projmarkup = $variation_markup;
			endif;

			$data['projmarkup'] = $projmarkup;

			$job_cat = $data['job_category'];
			$data['min_markup'] = $this->get_minimum_markup($job_cat);
		}else{
			$data['error'] = 'Unable to locate record';
		}

		$data['job_cat'] = $this->works_m->display_job_category();
		$data['main_content'] = 'work_details_v';
		$data['workdescription'] = 'Full Fitout';
		$data['markup']= 0;
		$data['operation'] = 0;

		$exist = 0;
		if($variation_id == "" || $variation_id == 0){
			$work_q = $this->works_m->display_all_works($project_id);
			foreach ($work_q->result_array() as $row){
				$work_con_sup_id = $row['work_con_sup_id'];
				if($work_con_sup_id == 53){
					$exist = 1;
					$work_id = $row['works_id'];
				}
			}
		}else{
			$work_q = $this->works_m->display_all_works($project_id,1,$variation_id);
			foreach ($work_q->result_array() as $row){
				$work_con_sup_id = $row['work_con_sup_id'];
				if($work_con_sup_id == 53){
					$exist = 1;
					$work_id = $row['works_id'];
				}
			}
		}
		
		$data['exist'] = $exist;

		$is_joinery = $this->input->post('is_joinery');
		if($is_joinery !== 'on'){
			$this->form_validation->set_rules('worktype', 'Work Type','trim|required|xss_clean');
			if($this->input->post('worktype') == '2_82'){
				$this->form_validation->set_rules('other_work_description', 'Other Work Description','trim|required|xss_clean');
			}
		}else{
			$this->form_validation->set_rules('work_joinery_name', 'Work Joinery','trim|required|xss_clean');
		}
		//
		//$this->form_validation->set_rules('work_estimate', 'Work Estimate','trim|required|xss_clean');
		//$this->form_validation->set_rules('work_markup', 'Work Markup','trim|required|xss_clean');
		//$this->form_validation->set_rules('work_quote_val', 'Quote','trim|required|xss_clean');

		if($variation_id == "" || $variation_id == 0){ 
			$data['variations'] = 0;
			$data['screen'] = "Work";
		}else{
			$data['variations'] = 1;
			$data['screen'] = "Variation";
		}

		if($this->form_validation->run() === false){
			$data['error' ] = validation_errors();
			$this->load->view('page', $data);
			//valid_input_simple
		}else{
			$is_joinery = $this->input->post('is_joinery');
			if($is_joinery !== 'on'){
				$attachment_type =  $this->input->post('chck_attachment_type');
				$work_raw_type = $this->input->post('worktype');

				$w_type = explode('_', $work_raw_type);
				$contractor_type = $w_type[0];
				$work_con_sup_id = $w_type[1];
				if($work_raw_type == '2_82'){
					$other_work_desc = $this->input->post('other_work_description');
					$other_work_desc = $this->company->cap_first_word($other_work_desc);
				}else{
					$other_work_desc = "";
				}
			}else{
				$contractor_type = 0;
				$work_con_sup_id = 0;
				$joinery_name = $this->input->post('work_joinery_name');
				$joinery_name = $this->company->cap_first_word($joinery_name);
				$joinery_name_q = $this->works_m->verify_joinery_name($joinery_name);
				if($joinery_name_q->num_rows == 0){
					$joinery_id = $this->works_m->insert_joinery_name($joinery_name);
				}else{
					foreach ($joinery_name_q->result_array() as $row){
						$joinery_id = $row['joinery_id'];
					}
				}
			}

			//$work_estimate = $this->input->post('work_estimate');
			//$work_estimate = str_replace( ',', '', $work_estimate );
			$markup = $this->input->post('work_markup');
			$markup = str_replace( ',', '', $markup );
			//$total_work_quote = $this->input->post('work_quote_val');
			//$total_work_quote = str_replace( ',', '', $total_work_quote );

			$is_deliver_office = $this->input->post('chkdeltooffice');

			$work_cpo_date = $this->input->post('work_cpo_date');
			$work_reply_date = $this->input->post('work_replyby_date');

			$comments = $this->input->post('replyby_desc');
			$notes = $this->input->post('work_notes');

			$site_inspection_req = $this->input->post('chkcons_site_inspect');
			$special_conditions = $this->input->post('chckcons_spcl_condition');
			$additional_visit_req = $this->input->post('chckcons_addnl_visit');
			$operate_during_install = $this->input->post('chckcons_oprte_duringinstall');
			$week_work = $this->input->post('chckcons_week_work');
			$weekend_work = $this->input->post('chckcons_weekend_work');
			$after_hours_work = $this->input->post('chckcons_afterhrs_work');
			$new_premises = $this->input->post('chckcons_new_premises');
			$free_access = $this->input->post('chckcons_free_access');
			$other = $this->input->post('chckcons_others');
			$otherdesc = $this->input->post('other_consideration');

			$other_category =  $this->input->post('other_work_category');
			//$other_cat_arr = explode('_', $other_category);
			//$other_category = $other_cat_arr[1];

			$add_work = array(
							'work_reply_date' => $work_reply_date,
							'site_inspection_req' => $site_inspection_req,
							'special_conditions' => $special_conditions,
							'additional_visit_req' => $additional_visit_req,
							'operate_during_install' => $operate_during_install,
							'week_work' => $week_work,
							'weekend_work' => $weekend_work,
							'after_hours_work' => $after_hours_work,
							'new_premises' => $new_premises,
							'free_access' => $free_access,
							'other' => $other,
							'otherdesc' => $otherdesc,
							'comments' => $comments
						);
			$this->session->set_userdata($add_work);

			if($variation_id == "" || $variation_id == 0){ 
				$is_variation = 0;
				$variation_id = 0;
			}else{
				$is_variation = 1;
			}
			
			$note_id = $this->works_m->insert_work_notes($comments,$notes);

			if($is_joinery !== 'on'){
				$work_id = $this->works_m->insert_new_works($contractor_type,$work_con_sup_id,$other_work_desc,$markup,$note_id,$is_deliver_office,$work_cpo_date,$work_reply_date,$project_id,$is_variation,"","",$variation_id,$other_category);
				//$work_id = $this->works_m->insert_new_works($contractor_type,$work_con_sup_id,$other_work_desc,$markup,$note_id,$is_deliver_office,$work_cpo_date,$work_reply_date,$project_id,$is_variation,"","",$variation_id);
				if(isset($_POST['chck_attachment_type'])){
					$attachment_type =  $this->input->post('chck_attachment_type');
					$this->session->set_userdata($attachment_type);
					$x = 0;
					foreach($attachment_type as $chkval){
						$this->works_m->insert_work_attachment_type($work_id,$chkval);
						$x++;
					}
					$attachment_num = array(
							'attachment_num' => $x
						);
					$this->session->set_userdata($attachment_num);
				}
				$this->works_m->insert_considerations($work_id,0,$site_inspection_req, $special_conditions, $additional_visit_req, $operate_during_install, $week_work, $weekend_work, $after_hours_work, $new_premises, $free_access, $other, $otherdesc);
				
			}else{
				//$works_joinery_q = $this->works_m->verify_works_joinery($project_id);
				//if($works_joinery_q->num_rows == 0){
					//$work_id = $this->works_m->insert_new_works($contractor_type,$work_con_sup_id,$markup,$note_id,$is_deliver_office,$work_cpo_date,$work_reply_date,$project_id,$is_variation);
				//}
				$work_joinery_id = $this->works_m->insert_works_joinery($work_id,$joinery_id,$markup,$note_id,$is_deliver_office,$work_cpo_date,$work_reply_date);
				$this->works_m->insert_considerations($work_id,$work_joinery_id, $site_inspection_req, $special_conditions, $additional_visit_req, $operate_during_install, $week_work, $weekend_work, $after_hours_work, $new_premises, $free_access, $other, $otherdesc);
			}
			if($variation_id == "" || $variation_id == 0){
				$this->session->set_flashdata('curr_tab', 'works');
				if($is_joinery !== 'on'){
					redirect('/projects/view/'.$project_id.'?curr_tab=works');
				}else{
					redirect('/projects/view/'.$project_id.'?curr_tab=works&show=1');
				}
			}else{
				$this->session->set_flashdata('curr_tab', 'variations');
				// $this->session->set_flashdata('variation', 'variation');
				// $this->session->set_flashdata('variation_id', $variation_id);
				redirect('/projects/view/'.$project_id.'/'.$variation_id.'?curr_tab=variations&variation=variation');
			}		
			/* NOTE there is a contractor that has no work cat!! */

			

		}
		
	}
	function update_work_details(){
		$this->projects->clear_apost();
		$project_id = $this->uri->segment(3);
		$work_id = $this->uri->segment(4);
		$work_joinery_id = $this->uri->segment(5);

		$q_proj = $this->projects_m->fetch_project_details($project_id);
		if($q_proj->num_rows > 0){
			$data = array_shift($q_proj->result_array());
			$projid = $data['project_id'];
			$data['projmarkup'] = $data['markup'];
			$job_cat = $data['job_category'];
			$data['min_markup'] = $this->get_minimum_markup($job_cat);
		}else{
			$data['error'] = 'Unable to locate record';
		}

		$proj_q = $this->projects_m->select_particular_project($project_id);
		foreach ($proj_q->result_array() as $row){
			$data['job_date'] = $row['job_date'];
		}

		$data['job_cat'] = $this->works_m->display_job_category();
		$data['main_content'] = 'work_detail_update_v';
		
		if($work_joinery_id == ""){
			$works_q = $this->works_m->display_works_selected($work_id);
			foreach ($works_q->result_array() as $row){
				$contractor_type = $row['contractor_type'];
				$work_con_sup_id = $row['work_con_sup_id'];
				if($contractor_type == 3){
					$work_desc = $row['supplier_cat_name'];
				}else{
					if($work_con_sup_id == 82){
						$work_desc = $row['other_work_desc'];
						$work_desc = $this->company->cap_first_word($work_desc);
					}else{
						$work_desc = $row['job_sub_cat'];
					}
				}
			}
			$data['work_joinery_id'] = "";
		}else{
			$works_q = $this->works_m->display_selected_works_joinery($work_joinery_id);
			foreach ($works_q->result_array() as $row){
				$work_desc = $row['joinery_name'];
				$data['work_joinery_markup'] =  $row['joinery_markup'];
			}
			$work_con_sup_id = "";
			$data['work_joinery_id'] = $work_joinery_id;
		}
		
		$data['work_id'] = $work_id;
		$data['project_id'] = $project_id;
		$data['work_q'] = $works_q;
		$data['work_desc'] = $work_desc;
		//$data['markup']= 0;
		$data['operation'] = 0;
	
		$data['variations'] = 0;
		$data['screen'] = "Work";
		$data['work_con_sup_id'] = $work_con_sup_id;
		$work_attachment_type = $this->works_m->fetch_work_attachment_type($work_id);
		$data['attachment_type'] = $this->attachments_m->display_all_attachment_type();

		$data['work_attachment_type'] = $work_attachment_type;
		
		$this->load->view('page', $data);
		/*
		$project_id = $this->uri->segment(3);

		$q_proj = $this->projects_m->fetch_project_details($project_id);
		if($q_proj->num_rows > 0){
			$data = array_shift($q_proj->result_array());
			$projid = $data['project_id'];
			$data['projmarkup'] = $data['markup'];
			$job_cat = $data['job_category'];
			$data['min_markup'] = $this->get_minimum_markup($job_cat);
		}else{
			$data['error'] = 'Unable to locate record';
		}
		$data['job_cat'] = $this->works_m->display_job_category();
		$data['main_content'] = 'work_details_v';
		$data['workdescription'] = 'Full Fitout';
		$data['markup']= 0;
		$data['operation'] = 1;
		//print_r($data);
		$this->load->view('page', $data);
		*/
	}

	function update_other_work_desc(){
		$other_work_description = $_POST['other_work_description'];
		$other_work_category_id = $_POST['other_work_category_id'];
		$other_work_description = $this->company->cap_first_word($other_work_description);
		$work_id = $_POST['work_id'];
		$this->works_m->update_other_work_desc($work_id, $other_work_description,$other_work_category_id);
		//$this->works_m->update_other_work_desc($work_id, $other_work_description);
	}

	function get_minimum_markup($job_cat){
		$proj_id = $this->uri->segment(3);
		$proj_q = $this->projects_m->select_particular_project($proj_id);
		foreach ($proj_q->result_array() as $row){
			$defaults_id = $row['defaults_id'];
		}
		$defaults_q = $this->admin_m->latest_system_default($defaults_id);
		foreach ($defaults_q->result_array() as $row){
			$markup_id = $row['markup_id'];
		}
		$markup_q = $this->admin_m->fetch_markup($markup_id);
		//$q_citecost = $this->works_m->get_site_costs();
		$data = array_shift($markup_q->result_array());
		switch($job_cat){
			case "Kiosk":
				$min_markup = $data['min_kiosk'];
				break;
			case "Full Fitout":
				$min_markup = $data['min_full_fitout'];
				break;
			case "Refurbishment":
				$min_markup = $data['min_refurbishment'];
				break;
			case "Strip Out":
				$min_markup = $data['min_stripout'];
				break;
			case "Minor Works":
				$min_markup = $data['min_minor_works'];
				break;
			case "Maintenance":
				$min_markup = $data['min_maintenance'];
				break;
			case "Design Works":
				$min_markup = $data['min_design_works'];
				break;
			case "Joinery Only":
				$min_markup = $data['joinery_only'];
				break;
			case "Company":
				$min_markup = 0;
				break;
		}
		return $min_markup;
	}

	function get_work_markup(){
		$work_id = $_POST['work_id'];
		$work_q = $this->works_m->display_works_selected($work_id);
		foreach ($work_q->result_array() as $row){
			$markup = $row['work_markup'];
		}
		echo $markup;
	}

	function get_work_joinery_markup(){
		$work_joinery_id = $_POST['work_joinery_id'];
		$work_q = $this->works_m->display_selected_works_joinery($work_joinery_id);
		foreach ($work_q->result_array() as $row){
			$markup = $row['work_markup'];
		}
		echo $markup;
	}

	function display_job_sub_cat(){
		$job_cat_id = $_POST['job_cat'];
		$data['job_cat'] = $this->works_m->display_job_subcategory($job_cat_id);
		$data['type'] = 0;
		$this->load->view('list_of_jobsubcat_v', $data);
	}
	function diplay_sup_cat(){
		$data['sup_cat'] = $this->works_m->display_supplier_category();
		$data['type'] = 1;
		$this->load->view('list_of_jobsubcat_v', $data);
	}

	function display_all_works_query($proj_id){
		$data['proj_id'] = $proj_id;
		$proj_q = $this->projects_m->select_particular_project($proj_id);
		foreach ($proj_q->result_array() as $row){
			$data['job_date'] = $row['job_date'];
		}
		$data['works_t'] = $this->works_m->display_all_works($proj_id);
		
		$this->load->view('table_works', $data);
	}

	function display_all_variations_query($proj_id,$acceptanct_date){
		$data['proj_id'] = $proj_id;
		$proj_q = $this->projects_m->select_particular_project($proj_id);
		// foreach ($proj_q->result_array() as $row){
		// 	$data['job_date'] = $row['job_date'];
		// }
		$data['job_date'] = $acceptanct_date;
		$variation_id = $this->uri->segment(4);//$this->session->flashdata('variation_id');
		
		$data['works_t'] = $this->works_m->display_all_works($proj_id,1,$variation_id);
		$data['variation_id'] = $variation_id;
		$this->load->view('table_variations', $data);
	}

	function display_all_works_joinery_query($work_id){
		$data['work_id'] = $work_id;
		$proj_id = $this->uri->segment(3);
		$proj_q = $this->projects_m->select_particular_project($proj_id);
		foreach ($proj_q->result_array() as $row){
			$data['job_date'] = $row['job_date'];
		}

		$work_q = $this->works_m->display_works_selected($work_id);
		foreach ($work_q->result_array() as $row){
			$data['company_client_id'] = $row['company_client_id'];
			$data['is_variation'] = $row['is_variation'];
		}

		$data['works_joinery_t'] = $this->works_m->display_all_works_joinery($work_id);
		$this->load->view('table_works_joinery', $data);
	}
	
	// function display_all_variations_query($proj_id){
	// 	$data['works_t'] = $this->works_m->display_all_works($proj_id,'1');
	// 	$this->load->view('table_variations', $data);
	// }

	function display_work_form(){
		/*$job_sub_cat = $this->works_m->display_job_subcategory();
		foreach ($job_sub_cat->result() as $row)
		{
		    echo $row->job_sub_cat;
		}*/
		if(isset($_POST['work_id'])){
			$work_id = $_POST['work_id'];
			$data['works'] = $this->works_m->display_works_selected($work_id);
			$data['job_sub_cat'] = $this->works_m->display_job_subcategory();
			$data['stat'] = 1;
			$this->load->view('add_work_form', $data);
		}else{
			$data['job_sub_cat'] = $this->works_m->display_job_subcategory();
			$data['stat'] = 0;
			$this->load->view('add_work_form', $data);
		}
	}
	function proj_details(){
		$proj_id = $this->uri->segment(3);

		$proj_q = $this->projects_m->select_particular_project($proj_id);
		foreach ($proj_q->result_array() as $row){
			$data['proj_name'] = html_entity_decode(str_replace("&apos;","'",$row['project_name'])); 
			$tenancyno = $row['shop_tenancy_number'].' '.$row['shop_name'];
		   	$primary_contact_person_id = $row['primary_contact_person_id'];
		   	$client_id = $row['client_id'];
			$job_type = $row['job_type'];
			$data['start_date'] = $row['date_site_commencement'];
			$data['end_date'] = $row['date_site_finish'];
			$site_address_id= $row['site_add'];
			$data['project_total'] = $row['project_total'];

			$project_estiamator_id = $row['project_estiamator_id'];
			$users_q = $this->user_model->fetch_user($project_estiamator_id);
			foreach ($users_q->result_array() as $users_row){
				$data['contact_person'] = $users_row['user_first_name']." ".$users_row['user_last_name'];
				$user_email_id = $users_row['user_email_id'];
				$email_q = $this->company_m->fetch_email($user_email_id);
				foreach ($email_q->result_array() as $email_row){
				   $data['email'] = $email_row['general_email'];
				}
			}

			$focus_company_id = $row['focus_company_id'];
			$focus_comp_q = $this->admin_m->fetch_single_company_focus($focus_company_id);
			foreach ($focus_comp_q->result_array() as $focus_comp_row){
				$data['focus_logo'] = $focus_comp_row['logo'];
				$data['focus_comp'] = $focus_comp_row['company_name'];
				$officnumber = $focus_comp_row['office_number'];
				$prefix = substr($officnumber, 0, 4);

				if($prefix == '1300' || $prefix == '1800'){
					$data['office_no'] = $focus_comp_row['office_number'];
				}else{
					$data['office_no'] = $focus_comp_row['area_code']." ".$focus_comp_row['office_number'];
				}
				$data['acn'] = $focus_comp_row['acn'];
				$data['focus_abn'] = $focus_comp_row['abn'];
				$data['focus_email'] = $focus_comp_row['general_email'];
				$address_id = $focus_comp_row['postal_address_id'];
				$focus_comp_q = $this->company_m->fetch_complete_detail_address($address_id);
				foreach ($focus_comp_q->result_array() as $comp_address_row){
					$po_box = $comp_address_row['po_box'];
					if($po_box == ""){
						$data['po_box'] = "";
					}else{
						$data['po_box'] = "PO ".$comp_address_row['po_box'];
					}
					$data['focus_suburb'] = ucwords(strtolower($comp_address_row['suburb']))." ".$comp_address_row['shortname']." ".$comp_address_row['postcode'];
				}
			}

			if($job_type == "Shopping Center"){
		   		$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
				foreach ($site_address_q->result_array() as $client_add_row){
				
					$data['site_address_1st'] = $tenancyno;

						if($client_add_row['unit_level'] == ""){
							$data['site_address_2nd'] = $client_add_row['unit_number'].' '.$client_add_row['street'];
						}else{
							$data['site_address_2nd'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'].' '.$client_add_row['street'];
						}

					$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
				}	
		   	}else{
				$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
				foreach ($site_address_q->result_array() as $client_add_row){
					if($client_add_row['unit_level'] == ""){
						$data['site_address_1st'] = $client_add_row['unit_number'];
					}else{
						$data['site_address_1st'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'];
					}
					$data['site_address_2nd'] = $client_add_row['street'];
					$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
				}
		   	}
		   	
		}

		$comp_det_q = $this->company_m->display_company_detail_by_id($client_id);
		foreach ($comp_det_q->result_array() as $row){
		   $address_id = $row['address_id'];
		   $comp_add_q = $this->company_m->fetch_complete_detail_address($address_id);
		   foreach ($comp_add_q->result_array() as $comp_add_row){
		   		if($comp_add_row['unit_level'] == ""){
		   			$data['comp_address_1st'] = $comp_add_row['unit_number'];
		   		}else{
		   			$data['comp_address_1st'] = $comp_add_row['unit_level']." / ".$comp_add_row['unit_number'];
		   		}
				$data['comp_address_2nd'] = $comp_add_row['street'];
				$data['comp_address_3rd'] = ucfirst(strtolower($comp_add_row['suburb']))." ".$comp_add_row['shortname']." ".$comp_add_row['postcode'];
		   }
		}

		$comp_q = $this->company_m->fetch_all_contact_persons($primary_contact_person_id);
		foreach ($comp_q->result_array() as $row){
			$data['contact_person'] = $row['first_name']." ".$row['last_name'];
			//$email_id = $row['email_id'];
			$comp_contact_number_id = $row['contact_number_id'];
			$comp_phone_q = $this->company_m->fetch_phone($comp_contact_number_id);
			foreach ($comp_phone_q->result_array() as $phone_row){
				if($phone_row['office_number'] == ""){
					$data['comp_office_number'] = "";
				}else{
					$data['comp_office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
				}
				$data['mobile_number'] = $phone_row['mobile_number'];
			}

			$email_id = $row['email_id'];
			$email_q = $this->company_m->fetch_email($email_id);
			foreach ($email_q->result_array() as $email_row){
				$data['client_email'] = $email_row['general_email'];
			}

		}

		$proj_cost_total_t = $this->projects_m->get_project_cost_total($proj_id);
		foreach ($proj_cost_total_t->result_array() as $row){
			$data['install_cost_total'] = $row['install_cost_total'];
			$works_quoted_total = $row['work_quoted_total'];
		}

		$project_totals_arr = $this->projects->fetch_project_totals($proj_id);
		$data = array_merge($data, $project_totals_arr);

		$proj_variation_q = $this->variation_m->fetch_variation_total($proj_id);
		$array = explode("|",$proj_variation_q);
		
		$data['variation'] = $array[0];
		// $data['variation'] = 0;
		$data['contract_management'] = $works_quoted_total + $project_totals_arr['final_labor_cost'];

		

		$proj_summary_exist = 0;
		$proj_form_q = $this->send_emails_m->display_project_forms($proj_id);
		foreach ($proj_form_q->result_array() as $row){
			if($row['description'] == 'project_summary_w_cost.pdf'){
				$proj_summary_exist++;
			}
		}
		if($proj_summary_exist == 0){
			$this->send_emails_m->insert_project_forms($proj_id,'project_summary_w_cost.pdf');
		}else{
			$this->send_emails_m->update_project_forms($proj_id,'project_summary_w_cost.pdf');
		}

		$data['proj_id'] = $proj_id;
		//$data = $this->clear_data($data);
		$data['project_t'] = $proj_q;
		$data['works_t'] = $this->works_m->display_all_works($proj_id);
		$this->load->view('project_details', $data);
	}
	function proj_summary_w_cost(){
		$proj_id = $this->uri->segment(3);
		$is_pending_client = 0;
		$pending_cont_person = "";
		$pending_mobile_number = "";
		$pending_client_email = "";
		$proj_q = $this->projects_m->select_particular_project($proj_id);
		foreach ($proj_q->result_array() as $row){
			$is_pending_client = $row['is_pending_client'];
			$pending_cont_person = $row['pending_cont_person'];
			$pending_mobile_number = $row['pending_cont_number'];
			$pending_client_email = $row['pending_cont_email'];

			$data['proj_name'] = html_entity_decode(str_replace("&apos;","'",$row['project_name'])); 
			$tenancyno = $row['shop_tenancy_number'].' '.$row['shop_name'];
		   	$primary_contact_person_id = $row['primary_contact_person_id'];
		   	$client_id = $row['client_id'];
			$job_type = $row['job_type'];
			$data['start_date'] = $row['date_site_commencement'];
			$data['end_date'] = $row['date_site_finish'];
			$site_address_id= $row['site_add'];
			$data['project_total'] = $row['project_total'];

			$project_estiamator_id = $row['project_estiamator_id'];
			$users_q = $this->user_model->fetch_user($project_estiamator_id);
			foreach ($users_q->result_array() as $users_row){
				$data['contact_person'] = $users_row['user_first_name']." ".$users_row['user_last_name'];
				$email = "";
				$user_email_id = $users_row['user_email_id'];
				$email_q = $this->company_m->fetch_email($user_email_id);
				foreach ($email_q->result_array() as $email_row){
				   $email = $email_row['general_email'];
				}
				$data['email'] = $email;
			}

			$focus_company_id = $row['focus_company_id'];
			$focus_comp_q = $this->admin_m->fetch_single_company_focus($focus_company_id);
			foreach ($focus_comp_q->result_array() as $focus_comp_row){
				$data['focus_logo'] = $focus_comp_row['logo'];
				$data['focus_comp'] = $focus_comp_row['company_name'];
				$officnumber = $focus_comp_row['office_number'];
				$prefix = substr($officnumber, 0, 4);

				if($prefix == '1300' || $prefix == '1800'){
					$data['office_no'] = $focus_comp_row['office_number'];
				}else{
					$data['office_no'] = $focus_comp_row['area_code']." ".$focus_comp_row['office_number'];
				}
				$data['acn'] = $focus_comp_row['acn'];
				$data['focus_abn'] = $focus_comp_row['abn'];
				$data['focus_email'] = $focus_comp_row['general_email'];
				$address_id = $focus_comp_row['postal_address_id'];
				$focus_comp_q = $this->company_m->fetch_complete_detail_address($address_id);
				foreach ($focus_comp_q->result_array() as $comp_address_row){
					$po_box = $comp_address_row['po_box'];
					if($po_box == ""){
						$data['po_box'] = "";
					}else{
						$data['po_box'] = "PO ".$comp_address_row['po_box'];
					}
					$data['focus_suburb'] = ucwords(strtolower($comp_address_row['suburb']))." ".$comp_address_row['shortname']." ".$comp_address_row['postcode'];
				}
			}

			if($job_type == "Shopping Center"){
		   		$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
				foreach ($site_address_q->result_array() as $client_add_row){
					$data['site_address_1st'] = $tenancyno;

						if($client_add_row['unit_level'] == ""){
							$data['site_address_2nd'] = $client_add_row['unit_number'].' '.$client_add_row['street'];
						}else{
							$data['site_address_2nd'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'].' '.$client_add_row['street'];
						}

					$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
				}	
		   	}else{
				$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
				foreach ($site_address_q->result_array() as $client_add_row){
					if($client_add_row['unit_level'] == ""){
						$data['site_address_1st'] = $client_add_row['unit_number'];
					}else{
						$data['site_address_1st'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'];
					}
					$data['site_address_2nd'] = $client_add_row['street'];
					$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
				}
		   	}
		   	
		}

		if($is_pending_client == 0):

			$comp_det_q = $this->company_m->display_company_detail_by_id($client_id);
			foreach ($comp_det_q->result_array() as $row){
			   $address_id = $row['address_id'];
			   $comp_add_q = $this->company_m->fetch_complete_detail_address($address_id);
			   foreach ($comp_add_q->result_array() as $comp_add_row){
			   		if($comp_add_row['unit_level'] == ""){
			   			$data['comp_address_1st'] = $comp_add_row['unit_number'];
			   		}else{
			   			$data['comp_address_1st'] = $comp_add_row['unit_level']." / ".$comp_add_row['unit_number'];
			   		}
					$data['comp_address_2nd'] = $comp_add_row['street'];
					$data['comp_address_3rd'] = ucfirst(strtolower($comp_add_row['suburb']))." ".$comp_add_row['shortname']." ".$comp_add_row['postcode'];
			   }
			}

		
			$comp_q = $this->company_m->fetch_all_contact_persons($primary_contact_person_id);
			foreach ($comp_q->result_array() as $row){
				$data['contact_person'] = $row['first_name']." ".$row['last_name'];
				//$email_id = $row['email_id'];
				$comp_contact_number_id = $row['contact_number_id'];
				$comp_phone_q = $this->company_m->fetch_phone($comp_contact_number_id);
				foreach ($comp_phone_q->result_array() as $phone_row){
					if($phone_row['office_number'] == ""){
						$data['comp_office_number'] = "";
					}else{
						$data['comp_office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					}
					$data['mobile_number'] = $phone_row['mobile_number'];
				}
				$client_email = "";
				$email_id = $row['email_id'];
				$email_q = $this->company_m->fetch_email($email_id);
				foreach ($email_q->result_array() as $email_row){
					$client_email = $email_row['general_email'];
				}
				$data['client_email'] = $client_email;
			}
		else:
			$data['comp_address_1st'] = "";
			$data['comp_address_2nd'] = "";
			$data['comp_address_3rd'] = "";
			$data['contact_person'] = $pending_cont_person;
			$data['comp_office_number'] = "";
			$data['mobile_number'] = $pending_mobile_number;
			$data['client_email'] = $pending_client_email;

		endif;

		$proj_cost_total_t = $this->projects_m->get_project_cost_total($proj_id);
		foreach ($proj_cost_total_t->result_array() as $row){
			$data['install_cost_total'] = $row['install_cost_total'];
			$works_quoted_total = $row['work_quoted_total'];
		}

		$project_totals_arr = $this->projects->fetch_project_totals($proj_id);
		$data = array_merge($data, $project_totals_arr);

		$data['variation'] = 0;
		$data['contract_management'] = $works_quoted_total + $project_totals_arr['final_labor_cost'];

		

		$proj_summary_exist = 0;
		$proj_form_q = $this->send_emails_m->display_project_forms($proj_id);
		foreach ($proj_form_q->result_array() as $row){
			if($row['description'] == 'project_summary_w_cost.pdf'){
				$proj_summary_exist++;
			}
		}
		if($proj_summary_exist == 0){
			$this->send_emails_m->insert_project_forms($proj_id,'project_summary_w_cost.pdf');
		}else{
			$this->send_emails_m->update_project_forms($proj_id,'project_summary_w_cost.pdf');
		}

		$data['proj_id'] = $proj_id;
		//$data = $this->clear_data($data);
		$data['project_t'] = $proj_q;
		$data['works_t'] = $this->works_m->display_all_works($proj_id);
		$this->load->view('proj_summary_w_cost', $data);
	}

	function proj_summary_wo_cost(){
		$proj_id = $this->uri->segment(3);
		$is_pending_client = 0;
		$pending_cont_person = "";
		$pending_mobile_number = "";
		$pending_client_email = "";
		$proj_q = $this->projects_m->select_particular_project($proj_id);
		$data['project_t'] = $proj_q;
		foreach ($proj_q->result_array() as $row){
			$is_pending_client = $row['is_pending_client'];
			$pending_cont_person = $row['pending_cont_person'];
			$pending_mobile_number = $row['pending_cont_number'];
			$pending_client_email = $row['pending_cont_email'];

			$data['proj_name'] = html_entity_decode(str_replace("&apos;","'",$row['project_name'])); 
			$tenancyno = $row['shop_tenancy_number'].' '.$row['shop_name'];

		   	$primary_contact_person_id = $row['primary_contact_person_id'];
		   	$client_id = $row['client_id'];
			$job_type = $row['job_type'];
			$data['start_date'] = $row['date_site_commencement'];
			$data['end_date'] = $row['date_site_finish'];
			$site_address_id= $row['site_add'];
			$data['project_total'] = $row['project_total'];

			$project_estiamator_id = $row['project_estiamator_id'];
			$users_q = $this->user_model->fetch_user($project_estiamator_id);
			foreach ($users_q->result_array() as $users_row){
				$data['contact_person'] = $users_row['user_first_name']." ".$users_row['user_last_name'];
				$email = "";
				$user_email_id = $users_row['user_email_id'];
				$email_q = $this->company_m->fetch_email($user_email_id);
				foreach ($email_q->result_array() as $email_row){
				   $email = $email_row['general_email'];
				}
				$data['email'] = $email;
			}

			$focus_company_id = $row['focus_company_id'];
			$focus_comp_q = $this->admin_m->fetch_single_company_focus($focus_company_id);
			foreach ($focus_comp_q->result_array() as $focus_comp_row){
				$data['focus_logo'] = $focus_comp_row['logo'];
				$data['focus_comp'] = $focus_comp_row['company_name'];
				$officnumber = $focus_comp_row['office_number'];
				$prefix = substr($officnumber, 0, 4);

				if($prefix == '1300' || $prefix == '1800'){
					$data['office_no'] = $focus_comp_row['office_number'];
				}else{
					$data['office_no'] = $focus_comp_row['area_code']." ".$focus_comp_row['office_number'];
				}
				$data['acn'] = $focus_comp_row['acn'];
				$data['focus_abn'] = $focus_comp_row['abn'];
				$data['focus_email'] = $focus_comp_row['general_email'];
				$address_id = $focus_comp_row['postal_address_id'];
				$focus_comp_q = $this->company_m->fetch_complete_detail_address($address_id);
				foreach ($focus_comp_q->result_array() as $comp_address_row){
					$po_box = $comp_address_row['po_box'];
					if($po_box == ""){
						$data['po_box'] = "";
					}else{
						$data['po_box'] = "PO ".$comp_address_row['po_box'];
					}
					$data['focus_suburb'] = ucwords(strtolower($comp_address_row['suburb']))." ".$comp_address_row['shortname']." ".$comp_address_row['postcode'];
				}
			}

			if($job_type == "Shopping Center"){
		   		$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
				foreach ($site_address_q->result_array() as $client_add_row){
					$data['site_address_1st'] = $tenancyno;

						if($client_add_row['unit_level'] == ""){
							$data['site_address_2nd'] = $client_add_row['unit_number'].' '.$client_add_row['street'];
						}else{
							$data['site_address_2nd'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'].' '.$client_add_row['street'];
						}
					
					$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
				}	
		   	}else{
				$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
				foreach ($site_address_q->result_array() as $client_add_row){
					if($client_add_row['unit_level'] == ""){
						$data['site_address_1st'] = $client_add_row['unit_number'];
					}else{
						$data['site_address_1st'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'];
					}
					$data['site_address_2nd'] = $client_add_row['street'];
					$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
				}
		   	}
		   	
		}

		if($is_pending_client == 0):

			$comp_det_q = $this->company_m->display_company_detail_by_id($client_id);
			foreach ($comp_det_q->result_array() as $row){
			   $address_id = $row['address_id'];
			   $comp_add_q = $this->company_m->fetch_complete_detail_address($address_id);
			   foreach ($comp_add_q->result_array() as $comp_add_row){
			   		if($comp_add_row['unit_level'] == ""){
			   			$data['comp_address_1st'] = $comp_add_row['unit_number'];
			   		}else{
			   			$data['comp_address_1st'] = $comp_add_row['unit_level']." / ".$comp_add_row['unit_number'];
			   		}
					$data['comp_address_2nd'] = $comp_add_row['street'];
					$data['comp_address_3rd'] = ucfirst(strtolower($comp_add_row['suburb']))." ".$comp_add_row['shortname']." ".$comp_add_row['postcode'];
			   }
			}

			$comp_q = $this->company_m->fetch_all_contact_persons($primary_contact_person_id);
			foreach ($comp_q->result_array() as $row){
				$data['contact_person'] = $row['first_name']." ".$row['last_name'];
				//$email_id = $row['email_id'];
				$comp_contact_number_id = $row['contact_number_id'];
				$comp_phone_q = $this->company_m->fetch_phone($comp_contact_number_id);
				foreach ($comp_phone_q->result_array() as $phone_row){
					if($phone_row['office_number'] == ""){
						$data['comp_office_number'] = "";
					}else{
						$data['comp_office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					}
					$data['mobile_number'] = $phone_row['mobile_number'];
				}
				$client_email = "";
				$email_id = $row['email_id'];
				$email_q = $this->company_m->fetch_email($email_id);
				foreach ($email_q->result_array() as $email_row){
					$client_email = $email_row['general_email'];
				}
				
				$data['client_email'] = $client_email;
			}
		else:
			$data['comp_address_1st'] = "";
			$data['comp_address_2nd'] = "";
			$data['comp_address_3rd'] = "";
			$data['contact_person'] = $pending_cont_person;
			$data['comp_office_number'] = "";
			$data['mobile_number'] = $pending_mobile_number;
			$data['client_email'] = $pending_client_email;

		endif;

		$proj_cost_total_t = $this->projects_m->get_project_cost_total($proj_id);
		foreach ($proj_cost_total_t->result_array() as $row){
			$data['install_cost_total'] = $row['install_cost_total'];
			$works_quoted_total = $row['work_quoted_total'];
		}

		$project_totals_arr = $this->projects->fetch_project_totals($proj_id);
		$data = array_merge($data, $project_totals_arr);

		$data['variation'] = 0;
		$data['contract_management'] = $works_quoted_total + $project_totals_arr['final_labor_cost'];

		$data['works_t'] = $this->works_m->display_all_works($proj_id);

		$proj_summary_exist = 0;
		$proj_form_q = $this->send_emails_m->display_project_forms($proj_id);
		foreach ($proj_form_q->result_array() as $row){
			if($row['description'] == 'project_summary_wo_cost.pdf'){
				$proj_summary_exist++;
			}
		}
		if($proj_summary_exist == 0){
			$this->send_emails_m->insert_project_forms($proj_id,'project_summary_wo_cost.pdf');
		}else{
			$this->send_emails_m->update_project_forms($proj_id,'project_summary_wo_cost.pdf');
		}

		$data['proj_id'] = $proj_id;
		$this->load->view('proj_summary_wo_cost', $data);
	}

	function proj_joinery_summary_w_cost(){
		$proj_id = $this->uri->segment(3);
		$is_pending_client = 0;
		$pending_cont_person = "";
		$pending_mobile_number = "";
		$pending_client_email = "";

		$proj_q = $this->projects_m->select_particular_project($proj_id);
		$data['project_t'] = $proj_q;
		foreach ($proj_q->result_array() as $row){
			$is_pending_client = $row['is_pending_client'];
			$pending_cont_person = $row['pending_cont_person'];
			$pending_mobile_number = $row['pending_cont_number'];
			$pending_client_email = $row['pending_cont_email'];

			$data['proj_name'] = html_entity_decode(str_replace("&apos;","'",$row['project_name'])); 
			$tenancyno = $row['shop_tenancy_number'].' '.$row['shop_name'];

		   	$primary_contact_person_id = $row['primary_contact_person_id'];
		   	$client_id = $row['client_id'];
			$job_type = $row['job_type'];
			$data['start_date'] = $row['date_site_commencement'];
			$data['end_date'] = $row['date_site_finish'];
			$site_address_id= $row['site_add'];
			$data['project_total'] = $row['project_total'];

			$project_estiamator_id = $row['project_estiamator_id'];
			$users_q = $this->user_model->fetch_user($project_estiamator_id);
			foreach ($users_q->result_array() as $users_row){
				$data['contact_person'] = $users_row['user_first_name']." ".$users_row['user_last_name'];
				$user_email_id = $users_row['user_email_id'];
				$email_q = $this->company_m->fetch_email($user_email_id);
				foreach ($email_q->result_array() as $email_row){
				   $data['email'] = $email_row['general_email'];
				}
			}

			$focus_company_id = $row['focus_company_id'];
			$focus_comp_q = $this->admin_m->fetch_single_company_focus($focus_company_id);
			foreach ($focus_comp_q->result_array() as $focus_comp_row){
				$data['focus_logo'] = $focus_comp_row['logo'];
				$data['focus_comp'] = $focus_comp_row['company_name'];
				$officnumber = $focus_comp_row['office_number'];
				$prefix = substr($officnumber, 0, 4);

				if($prefix == '1300' || $prefix == '1800'){
					$data['office_no'] = $focus_comp_row['office_number'];
				}else{
					$data['office_no'] = $focus_comp_row['area_code']." ".$focus_comp_row['office_number'];
				}
				$data['acn'] = $focus_comp_row['acn'];
				$data['focus_abn'] = $focus_comp_row['abn'];
				$data['focus_email'] = $focus_comp_row['general_email'];
				$address_id = $focus_comp_row['postal_address_id'];
				$focus_comp_q = $this->company_m->fetch_complete_detail_address($address_id);
				foreach ($focus_comp_q->result_array() as $comp_address_row){
					$po_box = $comp_address_row['po_box'];
					if($po_box == ""){
						$data['po_box'] = "";
					}else{
						$data['po_box'] = "PO ".$comp_address_row['po_box'];
					}
					$data['focus_suburb'] = ucwords(strtolower($comp_address_row['suburb']))." ".$comp_address_row['shortname']." ".$comp_address_row['postcode'];
				}
			}

			if($job_type == "Shopping Center"){
		   		$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
				foreach ($site_address_q->result_array() as $client_add_row){
					$data['site_address_1st'] = $tenancyno;

						if($client_add_row['unit_level'] == ""){
							$data['site_address_2nd'] = $client_add_row['unit_number'].' '.$client_add_row['street'];
						}else{
							$data['site_address_2nd'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'].' '.$client_add_row['street'];
						}
					
					$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
				}	
		   	}else{
				$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
				foreach ($site_address_q->result_array() as $client_add_row){
					if($client_add_row['unit_level'] == ""){
						$data['site_address_1st'] = $client_add_row['unit_number'];
					}else{
						$data['site_address_1st'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'];
					}
					$data['site_address_2nd'] = $client_add_row['street'];
					$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
				}
		   	}
		   	
		}
		if($is_pending_client == 0):

			$comp_det_q = $this->company_m->display_company_detail_by_id($client_id);
			foreach ($comp_det_q->result_array() as $row){
			   $address_id = $row['address_id'];
			   $comp_add_q = $this->company_m->fetch_complete_detail_address($address_id);
			   foreach ($comp_add_q->result_array() as $comp_add_row){
			   		if($comp_add_row['unit_level'] == ""){
			   			$data['comp_address_1st'] = $comp_add_row['unit_number'];
			   		}else{
			   			$data['comp_address_1st'] = $comp_add_row['unit_level']." / ".$comp_add_row['unit_number'];
			   		}
					$data['comp_address_2nd'] = $comp_add_row['street'];
					$data['comp_address_3rd'] = ucfirst(strtolower($comp_add_row['suburb']))." ".$comp_add_row['shortname']." ".$comp_add_row['postcode'];
			   }
			}

			$comp_q = $this->company_m->fetch_all_contact_persons($primary_contact_person_id);
			foreach ($comp_q->result_array() as $row){
				//$data['contact_person'] = $row['first_name']." ".$row['last_name'];
				//$email_id = $row['email_id'];
				$comp_contact_number_id = $row['contact_number_id'];
				$comp_phone_q = $this->company_m->fetch_phone($comp_contact_number_id);
				foreach ($comp_phone_q->result_array() as $phone_row){
					if($phone_row['office_number'] == ""){
						$data['comp_office_number'] = "";
					}else{
						$data['comp_office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					}
					
				}

				$email_id = $row['email_id'];
				$email_q = $this->company_m->fetch_email($email_id);
				foreach ($email_q->result_array() as $email_row){
					$data['client_email'] = $email_row['general_email'];
				}
			}
		else:
			$data['comp_address_1st'] = "";
			$data['comp_address_2nd'] = "";
			$data['comp_address_3rd'] = "";
			$data['contact_person'] = $pending_cont_person;
			$data['comp_office_number'] = "";
			$data['mobile_number'] = $pending_mobile_number;
			$data['client_email'] = $pending_client_email;

		endif;

		$works_t = $this->works_m->display_all_works($proj_id);
		foreach ($works_t->result_array() as $row){
			$work_con_sup_id = $row['work_con_sup_id'];
			if($work_con_sup_id == 53){
				$work_id = $row['works_id'];
			}
		}
		$data['joinery_t'] = $this->works_m->display_all_works_joinery($work_id);

		$form_exist = 0;
		$proj_form_q = $this->send_emails_m->display_project_forms($proj_id);
		foreach ($proj_form_q->result_array() as $row){
			if($row['description'] == 'joinery_summary_w_cost.pdf'){
				$form_exist++;
			}
		}
		if($form_exist == 0){
			$this->send_emails_m->insert_project_forms($proj_id,'joinery_summary_w_cost.pdf');
		}else{
			$this->send_emails_m->update_project_forms($proj_id,'joinery_summary_w_cost.pdf');
		}

		$data['proj_id'] = $proj_id;
		$this->load->view('joinery_summary', $data);
	}

	function proj_joinery_summary_wo_cost(){
		$proj_id = $this->uri->segment(3);
		$is_pending_client = 0;
		$pending_cont_person = "";
		$pending_mobile_number = "";
		$pending_client_email = "";
			

		$proj_q = $this->projects_m->select_particular_project($proj_id);
		$data['project_t'] = $proj_q;
		foreach ($proj_q->result_array() as $row){
			$is_pending_client = $row['is_pending_client'];
			$pending_cont_person = $row['pending_cont_person'];
			$pending_mobile_number = $row['pending_cont_number'];
			$pending_client_email = $row['pending_cont_email'];

			$data['proj_name'] = html_entity_decode(str_replace("&apos;","'",$row['project_name'])); 
			$tenancyno = $row['shop_tenancy_number'].' '.$row['shop_name'];
			
		   	$primary_contact_person_id = $row['primary_contact_person_id'];
		   	$client_id = $row['client_id'];
			$job_type = $row['job_type'];
			$data['start_date'] = $row['date_site_commencement'];
			$data['end_date'] = $row['date_site_finish'];
			$site_address_id= $row['site_add'];
			$data['project_total'] = $row['project_total'];

			$project_estiamator_id = $row['project_estiamator_id'];
			$users_q = $this->user_model->fetch_user($project_estiamator_id);
			foreach ($users_q->result_array() as $users_row){
				$data['contact_person'] = $users_row['user_first_name']." ".$users_row['user_last_name'];
				$user_email_id = $users_row['user_email_id'];
				$email_q = $this->company_m->fetch_email($user_email_id);
				foreach ($email_q->result_array() as $email_row){
				   $data['email'] = $email_row['general_email'];
				}
			}

			$focus_company_id = $row['focus_company_id'];
			$focus_comp_q = $this->admin_m->fetch_single_company_focus($focus_company_id);
			foreach ($focus_comp_q->result_array() as $focus_comp_row){
				$data['focus_logo'] = $focus_comp_row['logo'];
				$data['focus_comp'] = $focus_comp_row['company_name'];
				$officnumber = $focus_comp_row['office_number'];
				$prefix = substr($officnumber, 0, 4);

				if($prefix == '1300' || $prefix == '1800'){
					$data['office_no'] = $focus_comp_row['office_number'];
				}else{
					$data['office_no'] = $focus_comp_row['area_code']." ".$focus_comp_row['office_number'];
				}
				$data['acn'] = $focus_comp_row['acn'];
				$data['focus_abn'] = $focus_comp_row['abn'];
				$data['focus_email'] = $focus_comp_row['general_email'];
				$address_id = $focus_comp_row['postal_address_id'];
				$focus_comp_q = $this->company_m->fetch_complete_detail_address($address_id);
				foreach ($focus_comp_q->result_array() as $comp_address_row){
					$po_box = $comp_address_row['po_box'];
					if($po_box == ""){
						$data['po_box'] = "";
					}else{
						$data['po_box'] = "PO ".$comp_address_row['po_box'];
					}
					$data['focus_suburb'] = ucwords(strtolower($comp_address_row['suburb']))." ".$comp_address_row['shortname']." ".$comp_address_row['postcode'];
				}
			}

			if($job_type == "Shopping Center"){
		   		$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
				foreach ($site_address_q->result_array() as $client_add_row){
					$data['site_address_1st'] = $tenancyno;

						if($client_add_row['unit_level'] == ""){
							$data['site_address_2nd'] = $client_add_row['unit_number'].' '.$client_add_row['street'];
						}else{
							$data['site_address_2nd'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'].' '.$client_add_row['street'];
						}
					
					$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
				}	
		   	}else{
				$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
				foreach ($site_address_q->result_array() as $client_add_row){
					if($client_add_row['unit_level'] == ""){
						$data['site_address_1st'] = $client_add_row['unit_number'];
					}else{
						$data['site_address_1st'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'];
					}
					$data['site_address_2nd'] = $client_add_row['street'];
					$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
				}
		   	}
		}
		if($is_pending_client == 0):

			$comp_det_q = $this->company_m->display_company_detail_by_id($client_id);
			foreach ($comp_det_q->result_array() as $row){
			   $address_id = $row['address_id'];
			   $comp_add_q = $this->company_m->fetch_complete_detail_address($address_id);
			   foreach ($comp_add_q->result_array() as $comp_add_row){
			   		if($comp_add_row['unit_level'] == ""){
			   			$data['comp_address_1st'] = $comp_add_row['unit_number'];
			   		}else{
			   			$data['comp_address_1st'] = $comp_add_row['unit_level']." / ".$comp_add_row['unit_number'];
			   		}
					$data['comp_address_2nd'] = $comp_add_row['street'];
					$data['comp_address_3rd'] = ucfirst(strtolower($comp_add_row['suburb']))." ".$comp_add_row['shortname']." ".$comp_add_row['postcode'];
			   }
			}

			$comp_q = $this->company_m->fetch_all_contact_persons($primary_contact_person_id);
			foreach ($comp_q->result_array() as $row){
				//$data['contact_person'] = $row['first_name']." ".$row['last_name'];
				//$email_id = $row['email_id'];
				$comp_contact_number_id = $row['contact_number_id'];
				$comp_phone_q = $this->company_m->fetch_phone($comp_contact_number_id);
				foreach ($comp_phone_q->result_array() as $phone_row){
					$data['comp_office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
				}

				$email_id = $row['email_id'];
				$email_q = $this->company_m->fetch_email($email_id);
				foreach ($email_q->result_array() as $email_row){
					$data['client_email'] = $email_row['general_email'];
				}
			}
		else:
			$data['comp_address_1st'] = "";
			$data['comp_address_2nd'] = "";
			$data['comp_address_3rd'] = "";
			$data['contact_person'] = $pending_cont_person;
			$data['comp_office_number'] = "";
			$data['mobile_number'] = $pending_mobile_number;
			$data['client_email'] = $pending_client_email;

		endif;

		$works_t = $this->works_m->display_all_works($proj_id);
		foreach ($works_t->result_array() as $row){
			$work_con_sup_id = $row['work_con_sup_id'];
			if($work_con_sup_id == 53){
				$work_id = $row['works_id'];
			}
		}

		$data['joinery_t'] = $this->works_m->display_all_works_joinery($work_id);

		$form_exist = 0;
		$proj_form_q = $this->send_emails_m->display_project_forms($proj_id);
		foreach ($proj_form_q->result_array() as $row){
			if($row['description'] == 'joinery_summary_wo_cost.pdf'){
				$form_exist++;
			}
		}
		if($form_exist == 0){
			$this->send_emails_m->insert_project_forms($proj_id,'joinery_summary_wo_cost.pdf');
		}else{
			$this->send_emails_m->update_project_forms($proj_id,'joinery_summary_wo_cost.pdf');
		}

		$data['proj_id'] = $proj_id;
		$this->load->view('joinery_summery_wo_cost', $data);
	}

	function variation_summary(){
		$proj_id = $this->uri->segment(3);

		$proj_q = $this->projects_m->select_particular_project($proj_id);
		foreach ($proj_q->result_array() as $row){
			$data['proj_name'] = html_entity_decode(str_replace("&apos;","'",$row['project_name'])); 
			$tenancyno = $row['shop_tenancy_number'].' '.$row['shop_name'];
		   	$primary_contact_person_id = $row['primary_contact_person_id'];
		   	$client_id = $row['client_id'];
			$job_type = $row['job_type'];
			$data['start_date'] = $row['date_site_commencement'];
			$data['end_date'] = $row['date_site_finish'];
			$site_address_id= $row['site_add'];
			$data['project_total'] = $row['project_total'];

			$project_estiamator_id = $row['project_estiamator_id'];
			$users_q = $this->user_model->fetch_user($project_estiamator_id);
			foreach ($users_q->result_array() as $users_row){
				$data['contact_person'] = $users_row['user_first_name']." ".$users_row['user_last_name'];
				$user_email_id = $users_row['user_email_id'];
				$email_q = $this->company_m->fetch_email($user_email_id);
				foreach ($email_q->result_array() as $email_row){
				   $data['email'] = $email_row['general_email'];
				}
			}

			$focus_company_id = $row['focus_company_id'];
			$focus_comp_q = $this->admin_m->fetch_single_company_focus($focus_company_id);
			foreach ($focus_comp_q->result_array() as $focus_comp_row){
				$data['focus_logo'] = $focus_comp_row['logo'];
				$data['focus_comp'] = $focus_comp_row['company_name'];
				$officnumber = $focus_comp_row['office_number'];
				$prefix = substr($officnumber, 0, 4);

				if($prefix == '1300' || $prefix == '1800'){
					$data['office_no'] = $focus_comp_row['office_number'];
				}else{
					$data['office_no'] = $focus_comp_row['area_code']." ".$focus_comp_row['office_number'];
				}
				$data['acn'] = $focus_comp_row['acn'];
				$data['focus_abn'] = $focus_comp_row['abn'];
				$data['focus_email'] = $focus_comp_row['general_email'];
				$address_id = $focus_comp_row['postal_address_id'];
				$focus_comp_q = $this->company_m->fetch_complete_detail_address($address_id);
				foreach ($focus_comp_q->result_array() as $comp_address_row){
					$po_box = $comp_address_row['po_box'];
					if($po_box == ""){
						$data['po_box'] = "";
					}else{
						$data['po_box'] = "PO ".$comp_address_row['po_box'];
					}
					$data['focus_suburb'] = ucwords(strtolower($comp_address_row['suburb']))." ".$comp_address_row['shortname']." ".$comp_address_row['postcode'];
				}
			}

			// if($job_type == "Shopping Center"){
		 //   		$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
			// 	foreach ($site_address_q->result_array() as $client_add_row){
			// 		if($client_add_row['unit_level'] == ""){
			// 			$data['site_address_1st'] = $tenancyno." ".$client_add_row['unit_number'];
			// 		}else{
			// 			$data['site_address_1st'] = $tenancyno." ".$client_add_row['unit_level']." / ".$client_add_row['unit_number'];
			// 		}
					
			// 		$data['site_address_2nd'] = $client_add_row['street'];
			// 		$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
			// 	}	
		 //   	}else{
			// 	$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
			// 	foreach ($site_address_q->result_array() as $client_add_row){
			// 		if($client_add_row['unit_level'] == ""){
			// 			$data['site_address_1st'] = $client_add_row['unit_number'];
			// 		}else{
			// 			$data['site_address_1st'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'];
			// 		}
			// 		$data['site_address_2nd'] = $client_add_row['street'];
			// 		$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
			// 	}
		 //   	}
		   	
		}

		// $comp_det_q = $this->company_m->display_company_detail_by_id($client_id);
		// foreach ($comp_det_q->result_array() as $row){
		//    $address_id = $row['address_id'];
		//    $comp_add_q = $this->company_m->fetch_complete_detail_address($address_id);
		//    foreach ($comp_add_q->result_array() as $comp_add_row){
		//    		if($comp_add_row['unit_level'] == ""){
		//    			$data['comp_address_1st'] = $comp_add_row['unit_number'];
		//    		}else{
		//    			$data['comp_address_1st'] = $comp_add_row['unit_level']." / ".$comp_add_row['unit_number'];
		//    		}
		// 		$data['comp_address_2nd'] = $comp_add_row['street'];
		// 		$data['comp_address_3rd'] = ucfirst(strtolower($comp_add_row['suburb']))." ".$comp_add_row['shortname']." ".$comp_add_row['postcode'];
		//    }
		// }

		// $comp_q = $this->company_m->fetch_all_contact_persons($primary_contact_person_id);
		// foreach ($comp_q->result_array() as $row){
		// 	//$data['contact_person'] = $row['first_name']." ".$row['last_name'];
		// 	//$email_id = $row['email_id'];
		// 	$comp_contact_number_id = $row['contact_number_id'];
		// 	$comp_phone_q = $this->company_m->fetch_phone($comp_contact_number_id);
		// 	foreach ($comp_phone_q->result_array() as $phone_row){
		// 		if($phone_row['office_number'] == ""){
		// 			$data['comp_office_number'] = "";
		// 		}else{
		// 			$data['comp_office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
		// 		}
				
		// 	}

		// 	$email_id = $row['email_id'];
		// 	$email_q = $this->company_m->fetch_email($email_id);
		// 	foreach ($email_q->result_array() as $email_row){
		// 		$data['client_email'] = $email_row['general_email'];
		// 	}

		// }

		// $proj_cost_total_t = $this->projects_m->get_project_cost_total($proj_id);
		// foreach ($proj_cost_total_t->result_array() as $row){
		// 	$data['install_cost_total'] = $row['install_cost_total'];
		// 	$works_quoted_total = $row['work_quoted_total'];
		// }

		// $project_totals_arr = $this->projects->fetch_project_totals($proj_id);
		// $data = array_merge($data, $project_totals_arr);

		// $data['variation'] = 0;
		//$data['contract_management'] = $works_quoted_total + $project_totals_arr['final_labor_cost'];

		

		// $proj_summary_exist = 0;
		// $proj_form_q = $this->send_emails_m->display_project_forms($proj_id);
		// foreach ($proj_form_q->result_array() as $row){
		// 	if($row['description'] == 'project_summary_w_cost.pdf'){
		// 		$proj_summary_exist++;
		// 	}
		// }
		// if($proj_summary_exist == 0){
		// 	$this->send_emails_m->insert_project_forms($proj_id,'project_summary_w_cost.pdf');
		// }else{
		// 	$this->send_emails_m->update_project_forms($proj_id,'project_summary_w_cost.pdf');
		// }

		$data['proj_id'] = $proj_id;
		//$data = $this->clear_data($data);
		$data['project_t'] = $proj_q;
		$accepted_values_exgst = 0;
		$accepted_values_incgst = 0;
		$variation_t = $this->variation_m->display_all_variation($proj_id);
		foreach ($variation_t->result_array() as $row){
			$gst = $row['variation_total'] * 0.1;
			if($row['acceptance_date'] !== ""){
				$accepted_values_exgst = $accepted_values_exgst + $row['variation_total'];
				$incgst = $row['variation_total'] + $gst;
				$accepted_values_incgst = $accepted_values_incgst + $incgst;
			}
		}
		$data['accepted_values_exgst'] = $accepted_values_exgst;
		$data['accepted_values_incgst'] = $accepted_values_incgst;
		$data['variation_t'] = $variation_t;

		$variation_summary_exist = 0;
		$proj_form_q = $this->send_emails_m->display_project_forms($proj_id);
		foreach ($proj_form_q->result_array() as $row){
			if($row['description'] == 'variation_summary.pdf'){
				$variation_summary_exist++;
			}
		}
		if($variation_summary_exist == 0){
			$this->send_emails_m->insert_project_forms($proj_id,'variation_summary.pdf');
		}else{
			$this->send_emails_m->update_project_forms($proj_id,'variation_summary.pdf');
		}

		$this->load->view('variation_summary', $data);
	}


	function contract_tot_rfntf(){
		$is_pending_client = 0;
		$proj_id = $this->uri->segment(3);

		$proj_q = $this->projects_m->select_particular_project($proj_id);
		foreach ($proj_q->result_array() as $row){
			$is_pending_client = $row['is_pending_client'];
			$pending_cont_person = $row['pending_cont_person'];
			$pending_mobile_number = $row['pending_cont_number'];
			$pending_client_email = $row['pending_cont_email'];

			$client_id = $row['client_id'];
			if($is_pending_client == 0):
				$compname = $row['company_name'];
			else:
				$compname = $row['pending_comp_name'];
			endif;

			$data['project_name'] = $row['project_name'];
			$data['project_date'] = $row['project_date'];
			$data['project_total'] = $row['project_total'];
			$focus_company_id = $row['focus_company_id'];
			$focus_comp_q = $this->admin_m->fetch_single_company_focus($focus_company_id);
			foreach ($focus_comp_q->result_array() as $focus_comp_row){
				$data['focus_logo'] = $focus_comp_row['logo'];
				$data['focus_comp'] = $focus_comp_row['company_name'];
			}

			$project_manager_id = $row['project_manager_id'];
			$users_q = $this->user_model->fetch_user($project_manager_id);
			foreach ($users_q->result_array() as $users_row){
				$data['proj_manager'] = $users_row['user_first_name']." ".$users_row['user_last_name'];
				$data['has_sign'] = $users_row['has_sign'];
				$data['project_manager_id'] = $project_manager_id; 
				/*$user_email_id = $users_row['user_email_id'];
				$email_q = $this->company_m->fetch_email($user_email_id);
				foreach ($email_q->result_array() as $email_row){
					$data['email'] = $email_row['general_email'];
				}*/
			}

			if($is_pending_client == 0):
				$primary_contact_person_id = $row['primary_contact_person_id'];
				$comp_q = $this->company_m->fetch_all_contact_persons($primary_contact_person_id);
				foreach ($comp_q->result_array() as $row){
					$data['client_contact_person'] = $row['first_name']." ".$row['last_name'];
					//$email_id = $row['email_id'];
					$comp_contact_number_id = $row['contact_number_id'];
					$comp_phone_q = $this->company_m->fetch_phone($comp_contact_number_id);
					foreach ($comp_phone_q->result_array() as $phone_row){
						if($phone_row['office_number'] == ""):
							$data['comp_office_number'] = "";
						else:
							$data['comp_office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
						endif;
					}

					$email_id = $row['email_id'];
					$email_q = $this->company_m->fetch_email($email_id);
					foreach ($email_q->result_array() as $email_row){
						$data['client_email'] = $email_row['general_email'];
					}
				}
			else:
				$data['client_contact_person'] = $pending_cont_person;
				$data['comp_office_number'] = $pending_mobile_number;
				$data['client_email'] = $pending_client_email;
			endif;

		}

		if($is_pending_client == 0):
			$comp_det_q = $this->company_m->display_company_detail_by_id($client_id);
			foreach ($comp_det_q->result_array() as $row){
			   $address_id = $row['address_id'];
			   $comp_add_q = $this->company_m->fetch_complete_detail_address($address_id);
			   foreach ($comp_add_q->result_array() as $comp_add_row){
			   		if($comp_add_row['unit_level'] == ""){
			   			$data['comp_address_1st'] = $comp_add_row['unit_number'];
			   		}else{
			   			$data['comp_address_1st'] = $comp_add_row['unit_level']." / ".$comp_add_row['unit_number'];
			   		}
					$data['comp_address_2nd'] = $comp_add_row['street'];
					$data['comp_address_3rd'] = ucfirst(strtolower($comp_add_row['suburb']))." ".$comp_add_row['shortname']." ".$comp_add_row['postcode'];
			   }
			}
		else:
			$data['comp_address_1st'] = "";
			$data['comp_address_2nd'] = "";
			$data['comp_address_3rd'] = "";
		endif;



		$data['invoice_q'] = $this->invoice_m->list_invoice($proj_id);

		$contract_notes_q = $this->works_m->fetch_contract_notes($proj_id);
		foreach ($contract_notes_q->result_array() as $row){
			$data['contract_date'] = $row['contract_date'];
			$data['contract_plans_elv_draw'] = $row['ped_note'];
			$data['sched_work_quote'] = $row['sowiiq'];
			$data['cond_quote_cont'] = $row['coqac'];
		}
		
		$applied_admin_settings_raw = $this->projects->display_project_applied_defaults($proj_id);
		$data = array_merge($data, $applied_admin_settings_raw);

		$proj_summary_exist = 0;
		$proj_form_q = $this->send_emails_m->display_project_forms($proj_id);
		foreach ($proj_form_q->result_array() as $row){
			if($row['description'] == 'quotation_contract_and_termsoftrade.pdf'){
				$proj_summary_exist++;
			}
		}
		if($proj_summary_exist == 0){
			$this->send_emails_m->insert_project_forms($proj_id,'quotation_contract_and_termsoftrade.pdf');
		}else{
			$this->send_emails_m->update_project_forms($proj_id,'quotation_contract_and_termsoftrade.pdf');
		}


		$data['project_t'] = $proj_q;
		$data['proj_id'] = $proj_id;

		//echo $proj_summary_exist;
		$this->load->view('contract_tot_rntf', $data);
	}

	function design_contract_tot_rntf(){
		$proj_id = $this->uri->segment(3);

		$proj_q = $this->projects_m->select_particular_project($proj_id);
		foreach ($proj_q->result_array() as $row){
			$client_id = $row['client_id'];
			$compname = $row['company_name'];
			$data['project_name'] = $row['project_name'];
			$data['project_date'] = $row['project_date'];
			$data['project_total'] = $row['project_total'];
			$focus_company_id = $row['focus_company_id'];
			$focus_comp_q = $this->admin_m->fetch_single_company_focus($focus_company_id);
			foreach ($focus_comp_q->result_array() as $focus_comp_row){
				$data['focus_logo'] = $focus_comp_row['logo'];
				$data['focus_comp'] = $focus_comp_row['company_name'];
			}

			$project_manager_id = $row['project_manager_id'];
			$users_q = $this->user_model->fetch_user($project_manager_id);
			foreach ($users_q->result_array() as $users_row){
				$data['proj_manager'] = $users_row['user_first_name']." ".$users_row['user_last_name'];
				$data['has_sign'] = $users_row['has_sign'];
				$data['project_manager_id'] = $project_manager_id; 
				/*$user_email_id = $users_row['user_email_id'];
				$email_q = $this->company_m->fetch_email($user_email_id);
				foreach ($email_q->result_array() as $email_row){
					$data['email'] = $email_row['general_email'];
				}*/
			}
			$primary_contact_person_id = $row['primary_contact_person_id'];
			$comp_q = $this->company_m->fetch_all_contact_persons($primary_contact_person_id);
			foreach ($comp_q->result_array() as $row){
				$data['client_contact_person'] = $row['first_name']." ".$row['last_name'];
				//$email_id = $row['email_id'];
				$comp_contact_number_id = $row['contact_number_id'];
				$comp_phone_q = $this->company_m->fetch_phone($comp_contact_number_id);
				foreach ($comp_phone_q->result_array() as $phone_row){
					if($phone_row['office_number'] == ""):
						$data['comp_office_number'] = "";
					else:
						$data['comp_office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					endif;
				}

				$email_id = $row['email_id'];
				$email_q = $this->company_m->fetch_email($email_id);
				foreach ($email_q->result_array() as $email_row){
					$data['client_email'] = $email_row['general_email'];
				}
			}

		}

		$comp_det_q = $this->company_m->display_company_detail_by_id($client_id);
		foreach ($comp_det_q->result_array() as $row){
		   $address_id = $row['address_id'];
		   $comp_add_q = $this->company_m->fetch_complete_detail_address($address_id);
		   foreach ($comp_add_q->result_array() as $comp_add_row){
		   		if($comp_add_row['unit_level'] == ""){
		   			$data['comp_address_1st'] = $comp_add_row['unit_number'];
		   		}else{
		   			$data['comp_address_1st'] = $comp_add_row['unit_level']." / ".$comp_add_row['unit_number'];
		   		}
				$data['comp_address_2nd'] = $comp_add_row['street'];
				$data['comp_address_3rd'] = ucfirst(strtolower($comp_add_row['suburb']))." ".$comp_add_row['shortname']." ".$comp_add_row['postcode'];
		   }
		}



		$data['invoice_q'] = $this->invoice_m->list_invoice($proj_id);

		$contract_notes_q = $this->works_m->fetch_contract_notes($proj_id);
		foreach ($contract_notes_q->result_array() as $row){
			$data['contract_date'] = $row['contract_date'];
			$data['contract_plans_elv_draw'] = $row['ped_note'];
			$data['sched_work_quote'] = $row['sowiiq'];
			$data['cond_quote_cont'] = $row['coqac'];
		}
		
		$applied_admin_settings_raw = $this->projects->display_project_applied_defaults($proj_id);
		$data = array_merge($data, $applied_admin_settings_raw);

		$proj_summary_exist = 0;
		$proj_form_q = $this->send_emails_m->display_project_forms($proj_id);
		foreach ($proj_form_q->result_array() as $row){
			if($row['description'] == 'quotation_contract_and_termsoftrade.pdf'){
				$proj_summary_exist++;
			}
		}
		if($proj_summary_exist == 0){
			$this->send_emails_m->insert_project_forms($proj_id,'quotation_contract_and_termsoftrade.pdf');
		}else{
			$this->send_emails_m->update_project_forms($proj_id,'quotation_contract_and_termsoftrade.pdf');
		}


		$data['project_t'] = $proj_q;
		$data['proj_id'] = $proj_id;

		//echo $proj_summary_exist;
		$this->load->view('design_contract_tot_rntf', $data);
	}
	
	function works_contractors(){
		$work_id = $_POST['work_id'];
		$work_sel_cont_list_q = $this->works_m->display_work_contructor($work_id);
		foreach ($work_sel_cont_list_q->result_array() as $work_sel_cont_list_row){
			$contractor_id = $work_sel_cont_list_row['company_id'];
			$is_pending =  $work_sel_cont_list_row['cs_is_pending'];
			echo $contractor_id."-".$is_pending."|";
		}
	}

	function fetch_works_contractors(){
		$work_contractor_id = $_POST['work_contractor_id'];
		$work_sel_cont_list_q = $this->works_m->display_selected_contractor($work_contractor_id);
		foreach ($work_sel_cont_list_q->result_array() as $work_sel_cont_list_row){
			$contractor_id = $work_sel_cont_list_row['company_id'];
			$work_id = $work_sel_cont_list_row['works_id'];
			$is_selected = $work_sel_cont_list_row['is_selected'];
			$cqr_created = $work_sel_cont_list_row['cqr_created'];
			$cpo_created = $work_sel_cont_list_row['cpo_created'];
			$is_pending = $work_sel_cont_list_row['is_pending'];
		}
		echo $contractor_id."|".$work_id."|".$is_selected."|".$cqr_created."|".$cpo_created."|".$is_pending;
	}

	function get_contractor_email(){
		$work_id = $_POST['work_id'];
		$comp_id = $_POST['comp_id'];
		$is_pending = $_POST['is_pending'];
		$work_sel_cont_q = $this->works_m->display_works_selected_contractor($is_pending,$work_id,$comp_id);
		foreach ($work_sel_cont_q->result_array() as $work_cont_row){
			if($is_pending == 0):
				$work_cont_contact_person_id = $work_cont_row['contact_person_id'];
				$work_cont_person_q = $this->company_m->fetch_all_contact_persons($work_cont_contact_person_id);
				foreach ($work_cont_person_q->result_array() as $work_cont_person_row){
				    $data['attention'] = $work_cont_person_row['first_name']." ".$work_cont_person_row['last_name'];
					   
					$contact_number_id = $work_cont_person_row['contact_number_id'];
					$phon_q = $this->company_m->fetch_phone($contact_number_id);
					foreach ($phon_q->result_array() as $phone_row){
						if($phone_row['office_number'] == ""):
							$data['office_number'] = "";
						else:
							$data['office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
						endif;
					}
					$cont_email = "";
					$contact_email_id = $work_cont_person_row['email_id'];
					$email_q = $this->company_m->fetch_email($contact_email_id);
					foreach ($email_q->result_array() as $email_row){
						$cont_email = $email_row['general_email'];
					}
				}
			else:
				$pending_comp_q = $this->company_m->fetch_selcted_temporary_comp($comp_id);
				foreach ($pending_comp_q->result_array() as $pending_comp_row){
					$cont_email = $pending_comp_row['email'];
				}
			endif;
		}
		echo $cont_email;
	}

	function get_contractor_email_cpo(){
		$work_id = $_POST['work_id'];
		$comp_id = $_POST['comp_id'];
		$work_sel_cont_q = $this->works_m->display_works_selected_contractor_selected($work_id,$comp_id);
		foreach ($work_sel_cont_q->result_array() as $work_cont_row){
			$work_cont_contact_person_id = $work_cont_row['contact_person_id'];
			$work_cont_person_q = $this->company_m->fetch_all_contact_persons($work_cont_contact_person_id);
			foreach ($work_cont_person_q->result_array() as $work_cont_person_row){
			    $data['attention'] = $work_cont_person_row['first_name']." ".$work_cont_person_row['last_name'];
				   
				$contact_number_id = $work_cont_person_row['contact_number_id'];
				$phon_q = $this->company_m->fetch_phone($contact_number_id);
				foreach ($phon_q->result_array() as $phone_row){
					if($phone_row['office_number'] == ""):
						$data['office_number'] = "";
					else:
						$data['office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					endif;
				}
				$cont_email = "";
				$contact_email_id = $work_cont_person_row['email_id'];
				$email_q = $this->company_m->fetch_email($contact_email_id);
				foreach ($email_q->result_array() as $email_row){
					$cont_email = $email_row['general_email'];
				}
			}
		}
		echo $cont_email;
	}
	

	function maintenance_site_sheet(){
		$proj_id = $this->uri->segment(3);
		$work_id = $this->uri->segment(4);

		$is_joinery = strstr($work_id, '-');
		if($is_joinery == ""){
			$joinery_id = "";
		}else{
			$array = explode("-",$work_id);
			$joinery_id = $array[1];
		}
		
		if($joinery_id == ""){
			$work_q = $this->works_m->display_works_selected($work_id);
			foreach ($work_q->result_array() as $row){
			   $is_deliver_office = $row['is_deliver_office'];
			   $company_client_id = $row['company_client_id'];
			   $contractor_id = $company_client_id;
			   $contractor_type = $row['contractor_type'];
			   $goods_deliver_by_date = $row['goods_deliver_by_date'];
			   $work_reply_date = $row['work_reply_date'];

			   $site_inspection_req = $row['site_inspection_req'];
			   if($site_inspection_req == 1){
			   		$data['site_inspection_req'] = "checkbox_checked.jpg";
			   }else{
			   		$data['site_inspection_req'] = "checkbox.jpg";
			   }
			   $special_conditions = $row['special_conditions'];
			   if($special_conditions == 1){
			   		$data['special_conditions'] = "checkbox_checked.jpg";
			   }else{
			   		$data['special_conditions'] = "checkbox.jpg";
			   }
			   $additional_visit_req = $row['additional_visit_req'];
			   if($additional_visit_req == 1){
			   		$data['additional_visit_req'] = "checkbox_checked.jpg";
			   }else{
			   		$data['additional_visit_req'] = "checkbox.jpg";
			   }
			   $operate_during_install = $row['operate_during_install'];
			   if($operate_during_install == 1){
			   		$data['operate_during_install'] = "checkbox_checked.jpg";
			   }else{
			   		$data['operate_during_install'] = "checkbox.jpg";
			   }
			   $week_work = $row['week_work'];
			   if($week_work == 1){
			   		$data['week_work'] = "checkbox_checked.jpg";
			   }else{
			   		$data['week_work'] = "checkbox.jpg";
			   }
			   $weekend_work = $row['weekend_work'];
			   if($weekend_work == 1){
			   		$data['weekend_work'] = "checkbox_checked.jpg";
			   }else{
			   		$data['weekend_work'] = "checkbox.jpg";
			   }
			   $after_hours_work = $row['after_hours_work'];
			   if($after_hours_work == 1){
			   		$data['after_hours_work'] = "checkbox_checked.jpg";
			   }else{
			   		$data['after_hours_work'] = "checkbox.jpg";
			   }
			   $new_premises = $row['new_premises'];
			   if($new_premises == 1){
			   		$data['new_premises'] = "checkbox_checked.jpg";
			   }else{
			   		$data['new_premises'] = "checkbox.jpg";
			   }
			   $free_access = $row['free_access'];
			   if($free_access == 1){
			   		$data['free_access'] = "checkbox_checked.jpg";
			   }else{
			   		$data['free_access'] = "checkbox.jpg";
			   }
			   $other = $row['other'];
			   if($other == 1){
			   		$data['other'] = "checkbox_checked.jpg";
			   		$data['otherdesc'] = $row['otherdesc'];
			   }else{
			   		$data['other'] = "checkbox.jpg";
			   		$data['otherdesc'] = "";
			   }
			   

			   if($goods_deliver_by_date == "" ){
			   		$data['goods_deliver_by_date'] = "";	
			   }else{
			   		$data['goods_deliver_by_date'] = $row['goods_deliver_by_date'];
			   }

			   if($work_reply_date == "" ){
			   		$data['work_reply_date'] = "";	
			   }else{
			   		$data['work_reply_date'] = $work_reply_date;
			   }

			   $note_id = $row['note_id'];
			   $note_q = $this->company_m->fetch_notes($note_id);
			   foreach ($note_q->result_array() as $note_row){
			 		$data['remarks'] = str_replace("&apos;","'",$note_row['comments']);
			   		$cqr_notes = str_replace("&apos;","'",$note_row['notes']);
			   		$data['notes'] = str_replace("â€¢","*",$cqr_notes);
			   }
			   if($contractor_type == 2){
			   		if($row['work_con_sup_id'] == 82){
			   			$data['work_desc'] = str_replace("&apos;","'",$row['other_work_desc']);
			   		}else{
			   			$data['work_desc'] = str_replace("&apos;","'",$row['job_sub_cat']);
			   		}
			   }else{
			   		$data['work_desc'] = str_replace("&apos;","'",$row['supplier_cat_name']);
			   }
			}
		}else{
			$work_q = $this->works_m->display_selected_works_joinery($joinery_id);
			foreach ($work_q->result_array() as $row){
			   $is_deliver_office = $row['is_deliver_office'];
			   $company_client_id = $row['work_joinery_contractor_id'];
			   $price = $row['wj_price'];
			   $data['price'] = $price;
			   $work_reply_date = $row['wj_work_reply_date'];
			   if($work_reply_date == "" ){
			   		$data['work_reply_date'] = "";	
			   }else{
			   		$data['work_reply_date'] = $work_reply_date;
			   }

			   $site_inspection_req = $row['site_inspection_req'];
			   if($site_inspection_req == 1){
			   		$data['site_inspection_req'] = "checkbox_checked.jpg";
			   }else{
			   		$data['site_inspection_req'] = "checkbox.jpg";
			   }
			   $special_conditions = $row['special_conditions'];
			   if($special_conditions == 1){
			   		$data['special_conditions'] = "checkbox_checked.jpg";
			   }else{
			   		$data['special_conditions'] = "checkbox.jpg";
			   }
			   $additional_visit_req = $row['additional_visit_req'];
			   if($additional_visit_req == 1){
			   		$data['additional_visit_req'] = "checkbox_checked.jpg";
			   }else{
			   		$data['additional_visit_req'] = "checkbox.jpg";
			   }
			   $operate_during_install = $row['operate_during_install'];
			   if($operate_during_install == 1){
			   		$data['operate_during_install'] = "checkbox_checked.jpg";
			   }else{
			   		$data['operate_during_install'] = "checkbox.jpg";
			   }
			   $week_work = $row['week_work'];
			   if($week_work == 1){
			   		$data['week_work'] = "checkbox_checked.jpg";
			   }else{
			   		$data['week_work'] = "checkbox.jpg";
			   }
			   $weekend_work = $row['weekend_work'];
			   if($weekend_work == 1){
			   		$data['weekend_work'] = "checkbox_checked.jpg";
			   }else{
			   		$data['weekend_work'] = "checkbox.jpg";
			   }
			   $after_hours_work = $row['after_hours_work'];
			   if($after_hours_work == 1){
			   		$data['after_hours_work'] = "checkbox_checked.jpg";
			   }else{
			   		$data['after_hours_work'] = "checkbox.jpg";
			   }
			   $new_premises = $row['new_premises'];
			   if($new_premises == 1){
			   		$data['new_premises'] = "checkbox_checked.jpg";
			   }else{
			   		$data['new_premises'] = "checkbox.jpg";
			   }
			   $free_access = $row['free_access'];
			   if($free_access == 1){
			   		$data['free_access'] = "checkbox_checked.jpg";
			   }else{
			   		$data['free_access'] = "checkbox.jpg";
			   }
			   $other = $row['other'];
			   if($other == 1){
			   		$data['other'] = "checkbox_checked.jpg";
			   		$data['otherdesc'] = $row['otherdesc'];
			   }else{
			   		$data['other'] = "checkbox.jpg";
			   		$data['otherdesc'] = "";
			   }

			   //$contractor_type = $row['contractor_type'];
			   $goods_deliver_by_date = $row['goods_deliver_by_date'];
			   if($goods_deliver_by_date == "" ){
			   		$data['goods_deliver_by_date'] = "";	
			   }else{
			   		$data['goods_deliver_by_date'] = $row['goods_deliver_by_date'];
			   }

			   $note_id = $row['wj_note_id'];
			   $note_q = $this->company_m->fetch_notes($note_id);
			   foreach ($note_q->result_array() as $note_row){
			   		$data['remarks'] = str_replace("&apos;","'",$note_row['comments']);
			   		$cqr_notes = str_replace("&apos;","'",$note_row['notes']);
			   		$data['notes'] = str_replace("â€¢","*",$cqr_notes);
			   }
			   
			   $data['work_desc'] = $row['joinery_name'];
			   
			  //  $work_sel_cont_q = $this->works_m->display_works_selected_contractor($work_joinery_id,$company_client_id);
			  //  foreach ($work_sel_cont_q->result_array() as $work_cont_row){
			  //  		$work_cont_contact_person_id = $work_cont_row['contact_person_id'];
			  //  		$work_cont_person_q = $this->company_m->fetch_all_contact_persons($work_cont_contact_person_id);
					// foreach ($work_cont_person_q->result_array() as $work_cont_person_row){
					//    $data['attention'] = $work_cont_person_row['first_name']." ".$work_cont_person_row['last_name'];
					//    $contact_number_id = $work_cont_person_row['contact_number_id'];
					//    $phon_q = $this->company_m->fetch_phone($contact_number_id);
					//    foreach ($phon_q->result_array() as $phone_row){
					//    		if($phone_row['office_number'] == ""):
					//    			$data['office_number'] = "";
					//    		else:
					//    			$data['office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					//    		endif;
					//    }
					// }
			  //  }
			}
		}

		$proj_site_cont_q = $this->projects_m->fetch_project_site_contact($proj_id);
		foreach ($proj_site_cont_q->result_array() as $row){
			$data['site_contact_person'] = $row['contact_person_name'];
			$data['comp_office_number'] = $row['contact_person_number'];
			$data['mobile_number'] = $row['contact_person_mobile'];
			$data['cont_email'] = $row['contact_person_email'];
		}

		$proj_q = $this->projects_m->select_particular_project($proj_id);
		$data['project_t'] = $proj_q;
		foreach ($proj_q->result_array() as $row){
			$data['proj_name'] = str_replace("&apos;", "'", $row['project_name']);
			$tenancyno = $row['shop_tenancy_number'].' '.$row['shop_name'];
		   	$primary_contact_person_id = $row['primary_contact_person_id'];
		   	$client_id = $row['client_id'];
			$job_type = $row['job_type'];
			$data['start_date'] = $row['date_site_commencement'];
			$data['end_date'] = $row['date_site_finish'];
			$site_address_id= $row['site_add'];
			$data['project_total'] = $row['project_total'];

			$project_estiamator_id = $row['project_estiamator_id'];
			if($project_estiamator_id == 0){
				$project_manager_id = $row['project_manager_id'];
				$users_q = $this->user_model->fetch_user($project_manager_id);
			}else{
				$users_q = $this->user_model->fetch_user($project_estiamator_id);
			}
			//$users_q = $this->user_model->fetch_user($project_estiamator_id);
			foreach ($users_q->result_array() as $users_row){
				$data['contact_person'] = $users_row['user_first_name']." ".$users_row['user_last_name'];
				$user_email_id = $users_row['user_email_id'];
				$email_q = $this->company_m->fetch_email($user_email_id);
				foreach ($email_q->result_array() as $email_row){
				   $data['email'] = $email_row['general_email'];
				}

			}

			$focus_company_id = $row['focus_company_id'];
			$focus_comp_q = $this->admin_m->fetch_single_company_focus($focus_company_id);
			foreach ($focus_comp_q->result_array() as $focus_comp_row){
				$data['focus_logo'] = $focus_comp_row['logo'];
				$data['focus_comp'] = $focus_comp_row['company_name'];
				$officnumber = $focus_comp_row['office_number'];
				$prefix = substr($officnumber, 0, 4);

				if($prefix == '1300' || $prefix == '1800'){
					$data['office_no'] = $focus_comp_row['office_number'];
				}else{
					$data['office_no'] = $focus_comp_row['area_code']." ".$focus_comp_row['office_number'];
				}
				$data['acn'] = $focus_comp_row['acn'];
				$data['focus_abn'] = $focus_comp_row['abn'];
				$data['focus_email'] = $focus_comp_row['general_email'];
				$address_id = $focus_comp_row['postal_address_id'];
				$physical_address = $focus_comp_row['address_id']; 
				$focus_comp_q = $this->company_m->fetch_complete_detail_address($address_id);
				foreach ($focus_comp_q->result_array() as $comp_address_row){
					$po_box = $comp_address_row['po_box'];
					if($po_box == ""){
						$data['po_box'] = "";
					}else{
						$data['po_box'] = "PO ".$comp_address_row['po_box'];
					}
					$data['focus_suburb'] = ucwords(strtolower($comp_address_row['suburb']))." ".$comp_address_row['shortname']." ".$comp_address_row['postcode'];
				}
			}

			if($is_deliver_office == 0){
				$site_address_id= $row['site_add'];
			}else{
				$site_address_id= $physical_address;
			}

			if($job_type == "Shopping Center"){
		   		$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
				foreach ($site_address_q->result_array() as $client_add_row){
					$data['site_address_1st'] = $tenancyno;

						if($client_add_row['unit_level'] == ""){
							$data['site_address_2nd'] = $client_add_row['unit_number'].' '.$client_add_row['street'];
						}else{
							$data['site_address_2nd'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'].' '.$client_add_row['street'];
						}
					
					$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
				}	
		   	}else{
				$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
				foreach ($site_address_q->result_array() as $client_add_row){
					if($client_add_row['unit_level'] == ""){
						$data['site_address_1st'] = $client_add_row['unit_number'];
					}else{
						$data['site_address_1st'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'];
					}
					$data['site_address_2nd'] = $client_add_row['street'];
					$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
				}
		   	}
		   	
		   	$project_attachemnt_db_link = $row['dropbox_share_link'];
		   	$data['proj_attach_db_link'] = $project_attachemnt_db_link;
		   	$has_attachment = $this->projects_m->has_attachment($proj_id);
		   	$data['has_attachment'] = $has_attachment;
		}

		// $comp_q = $this->company_m->fetch_all_contact_persons($primary_contact_person_id);
		// foreach ($comp_q->result_array() as $row){
		// 	$data['contact_person'] = $row['first_name']." ".$row['last_name'];
		// 	//$email_id = $row['email_id'];
		// 	$comp_contact_number_id = $row['contact_number_id'];
		// 	$comp_phone_q = $this->company_m->fetch_phone($comp_contact_number_id);
		// 	foreach ($comp_phone_q->result_array() as $phone_row){
		// 		if($phone_row['office_number'] == ""):
		// 			$data['comp_office_number'] = "";
		// 		else:
		// 			$data['comp_office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
		// 		endif;

		// 		if($phone_row['mobile_number'] == ""):
		// 			$data['mobile_number'] = "";
		// 		else:
		// 			$data['mobile_number'] = $phone_row['mobile_number'];
		// 		endif;
				
		// 	}
		// }
		// //$email_q = $this->company_m->fetch_email($email_id);
		// //foreach ($email_q->result_array() as $row){
		//  //  $data['email'] = $row['general_email'];
		// //}
		
		
			
		// $work_sel_cont_q = $this->works_m->display_works_selected_contractor($work_id,$contractor_id);
		// foreach ($work_sel_cont_q->result_array() as $work_cont_row){
		// 	$work_cont_contact_person_id = $work_cont_row['contact_person_id'];
		// 	$work_cont_person_q = $this->company_m->fetch_all_contact_persons($work_cont_contact_person_id);
		// 	foreach ($work_cont_person_q->result_array() as $work_cont_person_row){
		// 	    $data['attention'] = $work_cont_person_row['first_name']." ".$work_cont_person_row['last_name'];
				   
		// 		$contact_number_id = $work_cont_person_row['contact_number_id'];
		// 		$phon_q = $this->company_m->fetch_phone($contact_number_id);
		// 		foreach ($phon_q->result_array() as $phone_row){
		// 			if($phone_row['office_number'] == ""):
		// 				$data['office_number'] = "";
		// 			else:
		// 				$data['office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
		// 			endif;
					
		// 		}

		// 		$contact_email_id = $work_cont_person_row['email_id'];
		// 		$email_q = $this->company_m->fetch_email($contact_email_id);
		// 		foreach ($email_q->result_array() as $email_row){
		// 			$data['cont_email'] = $email_row['general_email'];
		// 		}
		// 	}
		// }

		$comp_det_q = $this->company_m->display_company_detail_by_id($contractor_id);
		foreach ($comp_det_q->result_array() as $row){
		   $data['work_company_name'] = str_replace("&apos;", "'", $row['company_name']);
		   $address_id = $row['address_id'];
		 //   //--- For Insurance ------
		 //   if($row['public_liability_expiration'] !== ""){
			// 	$ple_raw_data = $row['public_liability_expiration'];
			// 	$ple_arr =  explode('/',$ple_raw_data);
			// 	$ple_day = $ple_arr[0];
			// 	$ple_month = $ple_arr[1];
			// 	$ple_year = $ple_arr[2];
			// 	$ple_date = $ple_year.'-'.$ple_month.'-'.$ple_day;
			// }
		}
		// 	if($row['workers_compensation_expiration'] !== ""){
		// 		$wce_raw_data = $row['workers_compensation_expiration'];
		// 		$wce_arr =  explode('/',$wce_raw_data);
		// 		$wce_day = $wce_arr[0];
		// 		$wce_month = $wce_arr[1];
		// 		$wce_year = $wce_arr[2];
		// 		$wce_date = $wce_year.'-'.$wce_month.'-'.$wce_day;
		// 	}

		// 	if($row['income_protection_expiration'] !== ""){
		// 		$ipe_raw_data = $row['income_protection_expiration'];
		// 		$ipe_arr =  explode('/',$ipe_raw_data);
		// 		$ipe_day = $ipe_arr[0];
		// 		$ipe_month = $ipe_arr[1];
		// 		$ipeyear = $ipe_arr[2];
		// 		$ipe_date = $ipe_year.'-'.$ipe_month.'-'.$ipe_day;
		// 	}
		// 	$today = date('Y-m-d');
			
		// 	$complete = 0;
		// 	$incomplete = 0;
			
		// 	if($row['company_type_id'] == '2'){
		// 		if($row['has_insurance_public_liability'] == 1){
		// 			if($row['public_liability_expiration'] !== ""){
		// 				if($ple_date <= $today){
		// 					$incomplete = 1;
		// 				}else{
		// 					if($row['has_insurance_workers_compensation'] == 1){
		// 						if($row['workers_compensation_expiration'] !== ""){
		// 							if($wce_date <= $today){
		// 								$incomplete = 1;
		// 							}else{
		// 								$complete = 1;
		// 							}
		// 						}else{
		// 							$incomplete = 1;
		// 						}
		// 					}else{
		// 						if($row['has_insurance_income_protection'] == 1){
		// 							if($row['income_protection_expiration'] !== ""){
		// 								if($ipe_date <= $today){
		// 									$incomplete = 1;
		// 								}else{
		// 									$complete = 1;
		// 								}
		// 							}else{
		// 								$incomplete = 1;
		// 							}
		// 						}else{
		// 							$incomplete = 1;
		// 						}
		// 					}
		// 				}
		// 			}else{
		// 				$incomplete = 1;
		// 			}
					
		// 		}else{
		// 			$incomplete = 1;
		// 		}
		// 	}

		// 	$admin_q = $this->admin_m->fetch_admin_defaults();
		// 	foreach ($admin_q->result_array() as $admin_row){
		// 		$cqr_notes_no_insurance = $admin_row['cqr_notes_no_insurance'];
		// 		$cqr_notes_w_insurance = $admin_row['cqr_notes_w_insurance'];
		// 	}
			
		// 	if($row['company_type_id'] == '2'){
		// 		if($complete == 1){
		// 			$data['cqr_notes_insurance'] = $cqr_notes_w_insurance;
		// 			$data['insurance_stat'] = 1;
		// 		}else{
		// 			$data['cqr_notes_insurance'] = $cqr_notes_no_insurance;
		// 			$data['insurance_stat'] = 0;
		// 		}
		// 	}else{
		// 		$data['cqr_notes_insurance'] = $cqr_notes_w_insurance;
		// 		$data['insurance_stat'] = 1;
		// 	}

		//    //--- For Insurance ------
		//    $comp_add_q = $this->company_m->fetch_complete_detail_address($address_id);
		//    foreach ($comp_add_q->result_array() as $comp_add_row){
		//    		if($comp_add_row['unit_level'] == ""){
		//    			$data['comp_address_1st'] = $comp_add_row['unit_number'];
		//    		}else{
		//    			$data['comp_address_1st'] = $comp_add_row['unit_level']." / ".$comp_add_row['unit_number'];
		//    		}
		// 		$data['comp_address_2nd'] = $comp_add_row['street'];
		// 		$data['comp_address_3rd'] = ucfirst(strtolower($comp_add_row['suburb']))." ".$comp_add_row['shortname']." ".$comp_add_row['postcode'];
		//    }
		//    $data['abn'] = $row['abn'];
		// }
		
		// $work_attachement_q = $this->works_m->fetch_work_attachment_type($work_id);

		// $this->works_m->update_works_contractor_cqr($work_id,$contractor_id);

		$user_id = $this->session->userdata('user_id');

		$user_name = "";
		$users_q = $this->user_model->fetch_user($user_id);
		foreach ($users_q->result_array() as $users_row){
			$user_name = $users_row['user_first_name']." ".$users_row['user_last_name'];
		}
		$data['user_name'] = $user_name;
		$data['work_id'] = $work_id;
		//$data['work_attachement_t'] = $work_attachement_q;
		$data['proj_id'] = $proj_id;
		//$this->load->view('maintenance_site_sheet',$data);
		$this->load->view('maintenance_site_sheet',$data);
	}

	function contractor_quote_request(){
		$work_con_sup_id = 0;
		$other_category_id = 0;

		$proj_id = $this->uri->segment(3);
		$work_id = $this->uri->segment(4);
		$contractor_id = $this->uri->segment(5);
		$is_pending = $this->uri->segment(6);

		$is_joinery = strstr($work_id, '-');
		if($is_joinery == ""){
			$joinery_id = "";
		}else{
			$array = explode("-",$work_id);
			$joinery_id = $array[1];
		}
		
		if($joinery_id == ""){
			$work_q = $this->works_m->display_works_selected($work_id);
			foreach ($work_q->result_array() as $row){
				$work_con_sup_id = $row['work_con_sup_id'];
				$other_category_id = $row['other_category_id'];

			   $is_deliver_office = $row['is_deliver_office'];
			   $company_client_id = $row['company_client_id'];
			   $contractor_type = $row['contractor_type'];
			   $goods_deliver_by_date = $row['goods_deliver_by_date'];
			   $work_reply_date = $row['work_reply_date'];

			   $site_inspection_req = $row['site_inspection_req'];
			   if($site_inspection_req == 1){
			   		$data['site_inspection_req'] = "checkbox_checked.jpg";
			   }else{
			   		$data['site_inspection_req'] = "checkbox.jpg";
			   }
			   $special_conditions = $row['special_conditions'];
			   if($special_conditions == 1){
			   		$data['special_conditions'] = "checkbox_checked.jpg";
			   }else{
			   		$data['special_conditions'] = "checkbox.jpg";
			   }
			   $additional_visit_req = $row['additional_visit_req'];
			   if($additional_visit_req == 1){
			   		$data['additional_visit_req'] = "checkbox_checked.jpg";
			   }else{
			   		$data['additional_visit_req'] = "checkbox.jpg";
			   }
			   $operate_during_install = $row['operate_during_install'];
			   if($operate_during_install == 1){
			   		$data['operate_during_install'] = "checkbox_checked.jpg";
			   }else{
			   		$data['operate_during_install'] = "checkbox.jpg";
			   }
			   $week_work = $row['week_work'];
			   if($week_work == 1){
			   		$data['week_work'] = "checkbox_checked.jpg";
			   }else{
			   		$data['week_work'] = "checkbox.jpg";
			   }
			   $weekend_work = $row['weekend_work'];
			   if($weekend_work == 1){
			   		$data['weekend_work'] = "checkbox_checked.jpg";
			   }else{
			   		$data['weekend_work'] = "checkbox.jpg";
			   }
			   $after_hours_work = $row['after_hours_work'];
			   if($after_hours_work == 1){
			   		$data['after_hours_work'] = "checkbox_checked.jpg";
			   }else{
			   		$data['after_hours_work'] = "checkbox.jpg";
			   }
			   $new_premises = $row['new_premises'];
			   if($new_premises == 1){
			   		$data['new_premises'] = "checkbox_checked.jpg";
			   }else{
			   		$data['new_premises'] = "checkbox.jpg";
			   }
			   $free_access = $row['free_access'];
			   if($free_access == 1){
			   		$data['free_access'] = "checkbox_checked.jpg";
			   }else{
			   		$data['free_access'] = "checkbox.jpg";
			   }
			   $other = $row['other'];
			   if($other == 1){
			   		$data['other'] = "checkbox_checked.jpg";
			   		$data['otherdesc'] = $row['otherdesc'];
			   }else{
			   		$data['other'] = "checkbox.jpg";
			   		$data['otherdesc'] = "";
			   }
			   

			   if($goods_deliver_by_date == "" ){
			   		$data['goods_deliver_by_date'] = "";	
			   }else{
			   		$data['goods_deliver_by_date'] = $row['goods_deliver_by_date'];
			   }

			   if($work_reply_date == "" ){
			   		$data['work_reply_date'] = "";	
			   }else{
			   		$data['work_reply_date'] = $work_reply_date;
			   }

			   $note_id = $row['note_id'];
			   $note_q = $this->company_m->fetch_notes($note_id);
			   foreach ($note_q->result_array() as $note_row){
			 		$data['remarks'] = str_replace("&apos;","'",$note_row['comments']);
			   		$cqr_notes = str_replace("&apos;","'",$note_row['notes']);
			   		$data['notes'] = str_replace("â€¢","*",$cqr_notes);
			   }
			   if($contractor_type == 2){
			   		if($row['work_con_sup_id'] == 82){
			   			$data['work_desc'] = str_replace("&apos;","'",$row['other_work_desc']);
			   		}else{
			   			$data['work_desc'] = str_replace("&apos;","'",$row['job_sub_cat']);
			   		}
			   }else{
			   		$data['work_desc'] = str_replace("&apos;","'",$row['supplier_cat_name']);
			   }
			}
		}else{
			$work_q = $this->works_m->display_selected_works_joinery($joinery_id);
			foreach ($work_q->result_array() as $row){
				$work_con_sup_id = $row['work_con_sup_id'];
				$other_category_id = $row['other_category_id'];

			   $is_deliver_office = $row['is_deliver_office'];
			   $company_client_id = $row['work_joinery_contractor_id'];
			   $price = $row['wj_price'];
			   $data['price'] = $price;
			   $work_reply_date = $row['wj_work_reply_date'];
			   if($work_reply_date == "" ){
			   		$data['work_reply_date'] = "";	
			   }else{
			   		$data['work_reply_date'] = $work_reply_date;
			   }

			   $site_inspection_req = $row['site_inspection_req'];
			   if($site_inspection_req == 1){
			   		$data['site_inspection_req'] = "checkbox_checked.jpg";
			   }else{
			   		$data['site_inspection_req'] = "checkbox.jpg";
			   }
			   $special_conditions = $row['special_conditions'];
			   if($special_conditions == 1){
			   		$data['special_conditions'] = "checkbox_checked.jpg";
			   }else{
			   		$data['special_conditions'] = "checkbox.jpg";
			   }
			   $additional_visit_req = $row['additional_visit_req'];
			   if($additional_visit_req == 1){
			   		$data['additional_visit_req'] = "checkbox_checked.jpg";
			   }else{
			   		$data['additional_visit_req'] = "checkbox.jpg";
			   }
			   $operate_during_install = $row['operate_during_install'];
			   if($operate_during_install == 1){
			   		$data['operate_during_install'] = "checkbox_checked.jpg";
			   }else{
			   		$data['operate_during_install'] = "checkbox.jpg";
			   }
			   $week_work = $row['week_work'];
			   if($week_work == 1){
			   		$data['week_work'] = "checkbox_checked.jpg";
			   }else{
			   		$data['week_work'] = "checkbox.jpg";
			   }
			   $weekend_work = $row['weekend_work'];
			   if($weekend_work == 1){
			   		$data['weekend_work'] = "checkbox_checked.jpg";
			   }else{
			   		$data['weekend_work'] = "checkbox.jpg";
			   }
			   $after_hours_work = $row['after_hours_work'];
			   if($after_hours_work == 1){
			   		$data['after_hours_work'] = "checkbox_checked.jpg";
			   }else{
			   		$data['after_hours_work'] = "checkbox.jpg";
			   }
			   $new_premises = $row['new_premises'];
			   if($new_premises == 1){
			   		$data['new_premises'] = "checkbox_checked.jpg";
			   }else{
			   		$data['new_premises'] = "checkbox.jpg";
			   }
			   $free_access = $row['free_access'];
			   if($free_access == 1){
			   		$data['free_access'] = "checkbox_checked.jpg";
			   }else{
			   		$data['free_access'] = "checkbox.jpg";
			   }
			   $other = $row['other'];
			   if($other == 1){
			   		$data['other'] = "checkbox_checked.jpg";
			   		$data['otherdesc'] = $row['otherdesc'];
			   }else{
			   		$data['other'] = "checkbox.jpg";
			   		$data['otherdesc'] = "";
			   }

			   //$contractor_type = $row['contractor_type'];
			   $goods_deliver_by_date = $row['goods_deliver_by_date'];
			   if($goods_deliver_by_date == "" ){
			   		$data['goods_deliver_by_date'] = "";	
			   }else{
			   		$data['goods_deliver_by_date'] = $row['goods_deliver_by_date'];
			   }

			   $note_id = $row['wj_note_id'];
			   $note_q = $this->company_m->fetch_notes($note_id);
			   foreach ($note_q->result_array() as $note_row){
			   		$data['remarks'] = str_replace("&apos;","'",$note_row['comments']);
			   		$cqr_notes = str_replace("&apos;","'",$note_row['notes']);
			   		$data['notes'] = str_replace("â€¢","*",$cqr_notes);
			   }
			   
			   $data['work_desc'] = $row['joinery_name'];
			   
			  //  $work_sel_cont_q = $this->works_m->display_works_selected_contractor($work_joinery_id,$company_client_id);
			  //  foreach ($work_sel_cont_q->result_array() as $work_cont_row){
			  //  		$work_cont_contact_person_id = $work_cont_row['contact_person_id'];
			  //  		$work_cont_person_q = $this->company_m->fetch_all_contact_persons($work_cont_contact_person_id);
					// foreach ($work_cont_person_q->result_array() as $work_cont_person_row){
					//    $data['attention'] = $work_cont_person_row['first_name']." ".$work_cont_person_row['last_name'];
					//    $contact_number_id = $work_cont_person_row['contact_number_id'];
					//    $phon_q = $this->company_m->fetch_phone($contact_number_id);
					//    foreach ($phon_q->result_array() as $phone_row){
					//    		if($phone_row['office_number'] == ""):
					//    			$data['office_number'] = "";
					//    		else:
					//    			$data['office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					//    		endif;
					//    }
					// }
			  //  }
			}
		}

		$proj_q = $this->projects_m->select_particular_project($proj_id);
		$data['project_t'] = $proj_q;
		foreach ($proj_q->result_array() as $row){
			$is_pending_client = $row['is_pending_client'];
			$joinery_selected_sender = $row['joinery_selected_sender'];
			$proj_name = str_replace("&apos;", "'", $row['project_name']);
			$proj_name = str_replace("–","-", $proj_name);
			$data['proj_name'] = $proj_name;
			$tenancyno = $row['shop_tenancy_number'].' '.$row['shop_name'];
			if($is_pending_client == 0):
		   		$primary_contact_person_id = $row['primary_contact_person_id'];
		   		$pending_cont_number = "";
		   	else:
		   		$primary_contact_person_id = 0;
		   		$pending_cont_number = $row['pending_cont_number'];
		   	endif;
		   	$client_id = $row['client_id'];
			$job_type = $row['job_type'];
			$data['start_date'] = $row['date_site_commencement'];
			$data['end_date'] = $row['date_site_finish'];
			$site_address_id= $row['site_add'];
			$data['project_total'] = $row['project_total'];


			$project_estiamator_id = $row['project_estiamator_id'];
			if($project_estiamator_id == 0){
				$project_manager_id = $row['project_manager_id'];

				if($joinery_id == ""){
					if($work_con_sup_id == 53 || $other_category_id == '2_20'){
						$users_q = $this->user_model->fetch_user($joinery_selected_sender);
					}else{
						$users_q = $this->user_model->fetch_user($project_manager_id);	
					}
				}else{
					$users_q = $this->user_model->fetch_user($joinery_selected_sender);
				}
				
			}else{
				if($joinery_id == ""){
					if($work_con_sup_id == 53 || $other_category_id == '2_20'){
						$users_q = $this->user_model->fetch_user($joinery_selected_sender);
					}else{
						$users_q = $this->user_model->fetch_user($project_estiamator_id);
					}
					
				}else{
					$users_q = $this->user_model->fetch_user($joinery_selected_sender);
				}
			}
			// $project_estiamator_id = $row['project_estiamator_id'];
			// if($project_estiamator_id == 0){
			// 	$project_manager_id = $row['project_manager_id'];
			// 	$users_q = $this->user_model->fetch_user($project_manager_id);
			// }else{
			// 	$users_q = $this->user_model->fetch_user($project_estiamator_id);
			// }
			//$users_q = $this->user_model->fetch_user($project_estiamator_id);
			foreach ($users_q->result_array() as $users_row){
				$data['contact_person'] = $users_row['user_first_name']." ".$users_row['user_last_name'];
				$user_email_id = $users_row['user_email_id'];
				$email_q = $this->company_m->fetch_email($user_email_id);
				foreach ($email_q->result_array() as $email_row){
				   $data['email'] = $email_row['general_email'];
				}

			}
			
			$focus_company_id = $row['focus_company_id'];
			$focus_comp_q = $this->admin_m->fetch_single_company_focus($focus_company_id);
			foreach ($focus_comp_q->result_array() as $focus_comp_row){
				$data['focus_logo'] = $focus_comp_row['logo'];
				$data['focus_comp'] = $focus_comp_row['company_name'];
				$officnumber = $focus_comp_row['office_number'];
				$prefix = substr($officnumber, 0, 4);

				if($prefix == '1300' || $prefix == '1800'){
					$data['office_no'] = $focus_comp_row['office_number'];
				}else{
					$data['office_no'] = $focus_comp_row['area_code']." ".$focus_comp_row['office_number'];
				}
				$data['acn'] = $focus_comp_row['acn'];
				$data['focus_abn'] = $focus_comp_row['abn'];
				$data['focus_email'] = $focus_comp_row['general_email'];
				$address_id = $focus_comp_row['postal_address_id'];
				$physical_address = $focus_comp_row['address_id']; 
				$focus_comp_q = $this->company_m->fetch_complete_detail_address($address_id);
				foreach ($focus_comp_q->result_array() as $comp_address_row){
					$po_box = $comp_address_row['po_box'];
					if($po_box == ""){
						$data['po_box'] = "";
					}else{
						$data['po_box'] = "PO ".$comp_address_row['po_box'];
					}
					$data['focus_suburb'] = ucwords(strtolower($comp_address_row['suburb']))." ".$comp_address_row['shortname']." ".$comp_address_row['postcode'];
				}
			}

			if($is_deliver_office == 0){
				$site_address_id= $row['site_add'];
			}else{
				$site_address_id= $physical_address;
			}

			if($job_type == "Shopping Center"){
		   		$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
				foreach ($site_address_q->result_array() as $client_add_row){
					$data['site_address_1st'] = $tenancyno;

						if($client_add_row['unit_level'] == ""){
							$data['site_address_2nd'] = $client_add_row['unit_number'].' '.$client_add_row['street'];
						}else{
							$data['site_address_2nd'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'].' '.$client_add_row['street'];
						}
					
					$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
				}	
		   	}else{
				$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
				foreach ($site_address_q->result_array() as $client_add_row){
					if($client_add_row['unit_level'] == ""){
						$data['site_address_1st'] = $client_add_row['unit_number'];
					}else{
						$data['site_address_1st'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'];
					}
					$data['site_address_2nd'] = $client_add_row['street'];
					$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
				}
		   	}
		   	
		   	$project_attachemnt_db_link = $row['dropbox_share_link'];
		   	$data['proj_attach_db_link'] = $project_attachemnt_db_link;
		   	$has_attachment = $this->projects_m->has_attachment($proj_id);
		   	$data['has_attachment'] = $has_attachment;
		}

		if($is_pending_client == 0):
			$comp_q = $this->company_m->fetch_all_contact_persons($primary_contact_person_id);
			foreach ($comp_q->result_array() as $row){
				//$data['contact_person'] = $row['first_name']." ".$row['last_name'];
				//$email_id = $row['email_id'];
				$comp_contact_number_id = $row['contact_number_id'];
				$comp_phone_q = $this->company_m->fetch_phone($comp_contact_number_id);
				foreach ($comp_phone_q->result_array() as $phone_row){
					if($phone_row['office_number'] == ""):
						$data['comp_office_number'] = "";
					else:
						$data['comp_office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					endif;
				}
			}
		else:
			$data['comp_office_number'] = $pending_cont_number;
		endif;
		//$email_q = $this->company_m->fetch_email($email_id);
		//foreach ($email_q->result_array() as $row){
		 //  $data['email'] = $row['general_email'];
		//}
		
		$data['attention'] = "";
		$data['cont_email'] = "";	
		$data['office_number'] = "";
		$work_sel_cont_q = $this->works_m->display_works_selected_contractor($is_pending,$work_id,$contractor_id);
		foreach ($work_sel_cont_q->result_array() as $work_cont_row){

			if($is_pending == 0):
				$work_cont_contact_person_id = $work_cont_row['contact_person_id'];
				$work_cont_person_q = $this->company_m->fetch_all_contact_persons($work_cont_contact_person_id);
				foreach ($work_cont_person_q->result_array() as $work_cont_person_row){
				    $data['attention'] = $work_cont_person_row['first_name']." ".$work_cont_person_row['last_name'];
					   
					$contact_number_id = $work_cont_person_row['contact_number_id'];
					$phon_q = $this->company_m->fetch_phone($contact_number_id);
					foreach ($phon_q->result_array() as $phone_row){
						if($phone_row['office_number'] == ""):
							$data['office_number'] = "";
						else:
							$data['office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
						endif;
						
					}

					$contact_email_id = $work_cont_person_row['email_id'];
					$email_q = $this->company_m->fetch_email($contact_email_id);
					foreach ($email_q->result_array() as $email_row){
						$data['cont_email'] = $email_row['general_email'];
					}
				}
			else:
				$pending_comp_q =  $this->company_m->fetch_selcted_temporary_comp($contractor_id);
				foreach ($pending_comp_q->result_array() as $pending_comp_row){
					$work_company_name = $pending_comp_row['company_name'];
					$data['attention'] = $pending_comp_row['contact_person_fname']." ".$pending_comp_row['contact_person_sname'];
					$data['office_number'] = $pending_comp_row['contact_number'];
					$data['cont_email'] = $pending_comp_row['email'];
				}
			endif;
		}

		if($is_pending == 0):
			$comp_det_q = $this->company_m->display_company_detail_by_id($contractor_id);
			foreach ($comp_det_q->result_array() as $row){
			   $data['work_company_name'] = str_replace("&apos;", "'", $row['company_name']);
			   $address_id = $row['address_id'];
			   //--- For Insurance ------
			   if($row['public_liability_expiration'] !== ""){
					$ple_raw_data = $row['public_liability_expiration'];
					$ple_arr =  explode('/',$ple_raw_data);
					$ple_day = $ple_arr[0];
					$ple_month = $ple_arr[1];
					$ple_year = $ple_arr[2];
					$ple_date = $ple_year.'-'.$ple_month.'-'.$ple_day;
				}

				if($row['workers_compensation_expiration'] !== ""){
					$wce_raw_data = $row['workers_compensation_expiration'];
					$wce_arr =  explode('/',$wce_raw_data);
					$wce_day = $wce_arr[0];
					$wce_month = $wce_arr[1];
					$wce_year = $wce_arr[2];
					$wce_date = $wce_year.'-'.$wce_month.'-'.$wce_day;
				}

				if($row['income_protection_expiration'] !== ""){
					$ipe_raw_data = $row['income_protection_expiration'];
					$ipe_arr =  explode('/',$ipe_raw_data);
					$ipe_day = $ipe_arr[0];
					$ipe_month = $ipe_arr[1];
					$ipeyear = $ipe_arr[2];
					$ipe_date = $ipe_year.'-'.$ipe_month.'-'.$ipe_day;
				}
				$today = date('Y-m-d');
				
				$complete = 0;
				$incomplete = 0;
				
				if($row['company_type_id'] == '2'){
					if($row['has_insurance_public_liability'] == 1){
						if($row['public_liability_expiration'] !== ""){
							if($ple_date <= $today){
								$incomplete = 1;
							}else{
								if($row['has_insurance_workers_compensation'] == 1){
									if($row['workers_compensation_expiration'] !== ""){
										if($wce_date <= $today){
											$incomplete = 1;
										}else{
											$complete = 1;
										}
									}else{
										$incomplete = 1;
									}
								}else{
									if($row['has_insurance_income_protection'] == 1){
										if($row['income_protection_expiration'] !== ""){
											if($ipe_date <= $today){
												$incomplete = 1;
											}else{
												$complete = 1;
											}
										}else{
											$incomplete = 1;
										}
									}else{
										$incomplete = 1;
									}
								}
							}
						}else{
							$incomplete = 1;
						}
						
					}else{
						$incomplete = 1;
					}
				}

				$admin_q = $this->admin_m->fetch_admin_defaults();
				foreach ($admin_q->result_array() as $admin_row){
					$cqr_notes_no_insurance = $admin_row['cqr_notes_no_insurance'];
					$cqr_notes_w_insurance = $admin_row['cqr_notes_w_insurance'];
				}
				
				if($row['company_type_id'] == '2'){
					if($complete == 1){
						$data['cqr_notes_insurance'] = $cqr_notes_w_insurance;
						$data['insurance_stat'] = 1;
					}else{
						$data['cqr_notes_insurance'] = $cqr_notes_no_insurance;
						$data['insurance_stat'] = 0;
					}
				}else{
					$data['cqr_notes_insurance'] = $cqr_notes_w_insurance;
					$data['insurance_stat'] = 1;
				}

			   //--- For Insurance ------
			   $comp_add_q = $this->company_m->fetch_complete_detail_address($address_id);
			   foreach ($comp_add_q->result_array() as $comp_add_row){
			   		if($comp_add_row['unit_level'] == ""){
			   			$data['comp_address_1st'] = $comp_add_row['unit_number'];
			   		}else{
			   			$data['comp_address_1st'] = $comp_add_row['unit_level']." / ".$comp_add_row['unit_number'];
			   		}
					$data['comp_address_2nd'] = $comp_add_row['street'];
					$data['comp_address_3rd'] = ucfirst(strtolower($comp_add_row['suburb']))." ".$comp_add_row['shortname']." ".$comp_add_row['postcode'];
			   }
			   $data['abn'] = $row['abn'];
			}
		else:
			$data['work_company_name'] = $work_company_name;
			$data['cqr_notes_insurance'] = "";
			$data['insurance_stat'] = 0;
			$data['comp_address_1st'] = "";
			$data['comp_address_2nd'] = "";
			$data['comp_address_3rd'] = "";
			$data['abn'] = "";

		endif;
		
		$work_attachement_q = $this->works_m->fetch_work_attachment_type($work_id);

		$this->works_m->update_works_contractor_cqr($work_id,$contractor_id);
		$data['work_id'] = $work_id;
		$data['work_attachement_t'] = $work_attachement_q;
		$data['proj_id'] = $proj_id;
		$this->load->view('contractor_quote_request',$data);
	}
	function contractor_quote_request_nodisplay(){
		$work_con_sup_id = 0;
		$other_category_id = 0;

		$proj_id = $_GET['project_id'];//$this->uri->segment(3);
		$work_id = $_GET['work_id'];//$this->uri->segment(4);
		$contractor_id = $_GET['comp_id'];//$this->uri->segment(5);
		$is_pending = $_GET['is_pending'];
		$is_joinery = strstr($work_id, '-');
		if($is_joinery == ""){
			$joinery_id = "";
		}else{
			$array = explode("-",$work_id);
			$joinery_id = $array[1];
		}
		
		if($joinery_id == ""){
			$work_q = $this->works_m->display_works_selected($work_id);
			foreach ($work_q->result_array() as $row){
				$work_con_sup_id = $row['work_con_sup_id'];
				$other_category_id = $row['other_category_id'];

			   $is_deliver_office = $row['is_deliver_office'];
			   $company_client_id = $row['company_client_id'];
			   $contractor_type = $row['contractor_type'];
			   $goods_deliver_by_date = $row['goods_deliver_by_date'];
			   $work_reply_date = $row['work_reply_date'];

			   $site_inspection_req = $row['site_inspection_req'];
			   if($site_inspection_req == 1){
			   		$data['site_inspection_req'] = "checkbox_checked.jpg";
			   }else{
			   		$data['site_inspection_req'] = "checkbox.jpg";
			   }
			   $special_conditions = $row['special_conditions'];
			   if($special_conditions == 1){
			   		$data['special_conditions'] = "checkbox_checked.jpg";
			   }else{
			   		$data['special_conditions'] = "checkbox.jpg";
			   }
			   $additional_visit_req = $row['additional_visit_req'];
			   if($additional_visit_req == 1){
			   		$data['additional_visit_req'] = "checkbox_checked.jpg";
			   }else{
			   		$data['additional_visit_req'] = "checkbox.jpg";
			   }
			   $operate_during_install = $row['operate_during_install'];
			   if($operate_during_install == 1){
			   		$data['operate_during_install'] = "checkbox_checked.jpg";
			   }else{
			   		$data['operate_during_install'] = "checkbox.jpg";
			   }
			   $week_work = $row['week_work'];
			   if($week_work == 1){
			   		$data['week_work'] = "checkbox_checked.jpg";
			   }else{
			   		$data['week_work'] = "checkbox.jpg";
			   }
			   $weekend_work = $row['weekend_work'];
			   if($weekend_work == 1){
			   		$data['weekend_work'] = "checkbox_checked.jpg";
			   }else{
			   		$data['weekend_work'] = "checkbox.jpg";
			   }
			   $after_hours_work = $row['after_hours_work'];
			   if($after_hours_work == 1){
			   		$data['after_hours_work'] = "checkbox_checked.jpg";
			   }else{
			   		$data['after_hours_work'] = "checkbox.jpg";
			   }
			   $new_premises = $row['new_premises'];
			   if($new_premises == 1){
			   		$data['new_premises'] = "checkbox_checked.jpg";
			   }else{
			   		$data['new_premises'] = "checkbox.jpg";
			   }
			   $free_access = $row['free_access'];
			   if($free_access == 1){
			   		$data['free_access'] = "checkbox_checked.jpg";
			   }else{
			   		$data['free_access'] = "checkbox.jpg";
			   }
			   $other = $row['other'];
			   if($other == 1){
			   		$data['other'] = "checkbox_checked.jpg";
			   		$data['otherdesc'] = $row['otherdesc'];
			   }else{
			   		$data['other'] = "checkbox.jpg";
			   		$data['otherdesc'] = "";
			   }
			   

			   if($goods_deliver_by_date == "" ){
			   		$data['goods_deliver_by_date'] = "";	
			   }else{
			   		$data['goods_deliver_by_date'] = $row['goods_deliver_by_date'];
			   }

			   if($work_reply_date == "" ){
			   		$data['work_reply_date'] = "";	
			   }else{
			   		$data['work_reply_date'] = $work_reply_date;
			   }

			   $note_id = $row['note_id'];
			   $note_q = $this->company_m->fetch_notes($note_id);
			   foreach ($note_q->result_array() as $note_row){
			   		$data['remarks'] = str_replace("&apos;","'",$note_row['comments']);
			   		$cqr_notes = str_replace("&apos;","'",$note_row['notes']);
			   		$data['notes'] = str_replace("â€¢","*",$cqr_notes);
			   }
			   if($contractor_type == 2){
			   		if($row['work_con_sup_id'] == 82){
			   			$data['work_desc'] = str_replace("&apos;","'",$row['other_work_desc']);

			   		}else{
			   			$data['work_desc'] = str_replace("&apos;","'",$row['job_sub_cat']);
			   		}
			   }else{
			   		$data['work_desc'] = str_replace("&apos;","'",$row['supplier_cat_name']);
			   }
			}
		}else{
			$work_q = $this->works_m->display_selected_works_joinery($joinery_id);
			foreach ($work_q->result_array() as $row){
				$work_con_sup_id = $row['work_con_sup_id'];
				$other_category_id = $row['other_category_id'];

			   $is_deliver_office = $row['is_deliver_office'];
			   $company_client_id = $row['work_joinery_contractor_id'];
			   $price = $row['wj_price'];
			   $data['price'] = $price;
			   $work_reply_date = $row['wj_work_reply_date'];
			   if($work_reply_date == "" ){
			   		$data['work_reply_date'] = "";	
			   }else{
			   		$data['work_reply_date'] = $work_reply_date;
			   }

			   $site_inspection_req = $row['site_inspection_req'];
			   if($site_inspection_req == 1){
			   		$data['site_inspection_req'] = "checkbox_checked.jpg";
			   }else{
			   		$data['site_inspection_req'] = "checkbox.jpg";
			   }
			   $special_conditions = $row['special_conditions'];
			   if($special_conditions == 1){
			   		$data['special_conditions'] = "checkbox_checked.jpg";
			   }else{
			   		$data['special_conditions'] = "checkbox.jpg";
			   }
			   $additional_visit_req = $row['additional_visit_req'];
			   if($additional_visit_req == 1){
			   		$data['additional_visit_req'] = "checkbox_checked.jpg";
			   }else{
			   		$data['additional_visit_req'] = "checkbox.jpg";
			   }
			   $operate_during_install = $row['operate_during_install'];
			   if($operate_during_install == 1){
			   		$data['operate_during_install'] = "checkbox_checked.jpg";
			   }else{
			   		$data['operate_during_install'] = "checkbox.jpg";
			   }
			   $week_work = $row['week_work'];
			   if($week_work == 1){
			   		$data['week_work'] = "checkbox_checked.jpg";
			   }else{
			   		$data['week_work'] = "checkbox.jpg";
			   }
			   $weekend_work = $row['weekend_work'];
			   if($weekend_work == 1){
			   		$data['weekend_work'] = "checkbox_checked.jpg";
			   }else{
			   		$data['weekend_work'] = "checkbox.jpg";
			   }
			   $after_hours_work = $row['after_hours_work'];
			   if($after_hours_work == 1){
			   		$data['after_hours_work'] = "checkbox_checked.jpg";
			   }else{
			   		$data['after_hours_work'] = "checkbox.jpg";
			   }
			   $new_premises = $row['new_premises'];
			   if($new_premises == 1){
			   		$data['new_premises'] = "checkbox_checked.jpg";
			   }else{
			   		$data['new_premises'] = "checkbox.jpg";
			   }
			   $free_access = $row['free_access'];
			   if($free_access == 1){
			   		$data['free_access'] = "checkbox_checked.jpg";
			   }else{
			   		$data['free_access'] = "checkbox.jpg";
			   }
			   $other = $row['other'];
			   if($other == 1){
			   		$data['other'] = "checkbox_checked.jpg";
			   		$data['otherdesc'] = $row['otherdesc'];
			   }else{
			   		$data['other'] = "checkbox.jpg";
			   		$data['otherdesc'] = "";
			   }
			   
			   //$contractor_type = $row['contractor_type'];
			   $goods_deliver_by_date = $row['goods_deliver_by_date'];
			   if($goods_deliver_by_date == "" ){
			   		$data['goods_deliver_by_date'] = "";	
			   }else{
			   		$data['goods_deliver_by_date'] = $row['goods_deliver_by_date'];
			   }

			   $note_id = $row['wj_note_id'];
			   $note_q = $this->company_m->fetch_notes($note_id);
			   foreach ($note_q->result_array() as $note_row){
			   		$data['remarks'] = str_replace("&apos;","'",$note_row['comments']);
			   		$cqr_notes = str_replace("&apos;","'",$note_row['notes']);
			   		$data['notes'] = str_replace("â€¢","*",$cqr_notes);
			   }
			   
			   $data['work_desc'] = $row['joinery_name'];
			   
			  //  $work_sel_cont_q = $this->works_m->display_works_selected_contractor($work_joinery_id,$company_client_id);
			  //  foreach ($work_sel_cont_q->result_array() as $work_cont_row){
			  //  		$work_cont_contact_person_id = $work_cont_row['contact_person_id'];
			  //  		$work_cont_person_q = $this->company_m->fetch_all_contact_persons($work_cont_contact_person_id);
					// foreach ($work_cont_person_q->result_array() as $work_cont_person_row){
					//    $data['attention'] = $work_cont_person_row['first_name']." ".$work_cont_person_row['last_name'];
					//    $contact_number_id = $work_cont_person_row['contact_number_id'];
					//    $phon_q = $this->company_m->fetch_phone($contact_number_id);
					//    foreach ($phon_q->result_array() as $phone_row){
					//    		if($phone_row['office_number'] == ""):
					//    			$data['office_number'] = "";
					//    		else:
					//    			$data['office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					//    		endif;
					//    }
					// }
			  //  }
			}
		}


		$proj_q = $this->projects_m->select_particular_project($proj_id);
		$data['project_t'] = $proj_q;
		foreach ($proj_q->result_array() as $row){
			$is_pending_client = $row['is_pending_client'];

			$joinery_selected_sender = $row['joinery_selected_sender'];
			$data['proj_name'] = $row['project_name']; 
			$tenancyno = $row['shop_tenancy_number'].' '.$row['shop_name'];
		   	$primary_contact_person_id = $row['primary_contact_person_id'];
		   	if($is_pending_client == 0):
		   		$primary_contact_person_id = $row['primary_contact_person_id'];
		   		$pending_cont_number = "";
		   	else:
		   		$primary_contact_person_id = 0;
		   		$pending_cont_number = $row['pending_cont_number'];
		   	endif;
		   	$client_id = $row['client_id'];
			$job_type = $row['job_type'];
			$data['start_date'] = $row['date_site_commencement'];
			$data['end_date'] = $row['date_site_finish'];
			$site_address_id= $row['site_add'];
			$data['project_total'] = $row['project_total'];

			$project_estiamator_id = $row['project_estiamator_id'];
			if($project_estiamator_id == 0){
				$project_manager_id = $row['project_manager_id'];

				if($joinery_id == ""){
					if($work_con_sup_id == 53 || $other_category_id == '2_20'){
						$users_q = $this->user_model->fetch_user($joinery_selected_sender);
					}else{
						$users_q = $this->user_model->fetch_user($project_manager_id);	
					}
				}else{
					$users_q = $this->user_model->fetch_user($joinery_selected_sender);
				}
				
			}else{
				if($joinery_id == ""){
					if($work_con_sup_id == 53 || $other_category_id == '2_20'){
						$users_q = $this->user_model->fetch_user($joinery_selected_sender);
					}else{
						$users_q = $this->user_model->fetch_user($project_estiamator_id);
					}
					
				}else{
					$users_q = $this->user_model->fetch_user($joinery_selected_sender);
				}
			}

			// $project_estiamator_id = $row['project_estiamator_id'];
			// if($project_estiamator_id == 0){
			// 	$project_manager_id = $row['project_manager_id'];
			// 	$users_q = $this->user_model->fetch_user($project_manager_id);
			// }else{
			// 	$users_q = $this->user_model->fetch_user($project_estiamator_id);
			// }
			//$users_q = $this->user_model->fetch_user($project_estiamator_id);
			
			foreach ($users_q->result_array() as $users_row){
				$data['contact_person'] = $users_row['user_first_name']." ".$users_row['user_last_name'];
				$user_email_id = $users_row['user_email_id'];
				$email_q = $this->company_m->fetch_email($user_email_id);
				foreach ($email_q->result_array() as $email_row){
				   $data['email'] = $email_row['general_email'];
				}
			}

			$focus_company_id = $row['focus_company_id'];
			$focus_comp_q = $this->admin_m->fetch_single_company_focus($focus_company_id);
			foreach ($focus_comp_q->result_array() as $focus_comp_row){
				$data['focus_logo'] = $focus_comp_row['logo'];
				$data['focus_comp'] = $focus_comp_row['company_name'];
				$officnumber = $focus_comp_row['office_number'];
				$prefix = substr($officnumber, 0, 4);

				if($prefix == '1300' || $prefix == '1800'){
					$data['office_no'] = $focus_comp_row['office_number'];
				}else{
					$data['office_no'] = $focus_comp_row['area_code']." ".$focus_comp_row['office_number'];
				}
				$data['acn'] = $focus_comp_row['acn'];
				$data['focus_abn'] = $focus_comp_row['abn'];
				$data['focus_email'] = $focus_comp_row['general_email'];
				$address_id = $focus_comp_row['postal_address_id'];
				$physical_address = $focus_comp_row['address_id'];
				$focus_comp_q = $this->company_m->fetch_complete_detail_address($address_id);
				foreach ($focus_comp_q->result_array() as $comp_address_row){
					$po_box = $comp_address_row['po_box'];
					if($po_box == ""){
						$data['po_box'] = "";
					}else{
						$data['po_box'] = "PO ".$comp_address_row['po_box'];
					}
					$data['focus_suburb'] = ucwords(strtolower($comp_address_row['suburb']))." ".$comp_address_row['shortname']." ".$comp_address_row['postcode'];
				}
			}

			if($is_deliver_office == 0){
				$site_address_id= $row['site_add'];
			}else{
				$site_address_id= $physical_address;
			}

			if($job_type == "Shopping Center"){
		   		$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
				foreach ($site_address_q->result_array() as $client_add_row){
					$data['site_address_1st'] = $tenancyno;

						if($client_add_row['unit_level'] == ""){
							$data['site_address_2nd'] = $client_add_row['unit_number'].' '.$client_add_row['street'];
						}else{
							$data['site_address_2nd'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'].' '.$client_add_row['street'];
						}
					
					$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
				}	
		   	}else{
				$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
				foreach ($site_address_q->result_array() as $client_add_row){
					if($client_add_row['unit_level'] == ""){
						$data['site_address_1st'] = $client_add_row['unit_number'];
					}else{
						$data['site_address_1st'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'];
					}
					$data['site_address_2nd'] = $client_add_row['street'];
					$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
				}
		   	}
		   	$project_attachemnt_db_link = $row['dropbox_share_link'];
		   	$data['proj_attach_db_link'] = $project_attachemnt_db_link;
		   	$has_attachment = $this->projects_m->has_attachment($proj_id);
		   	$data['has_attachment'] = $has_attachment;
		}

		if($is_pending_client == 0):
			$comp_q = $this->company_m->fetch_all_contact_persons($primary_contact_person_id);
			foreach ($comp_q->result_array() as $row){
				//$data['contact_person'] = $row['first_name']." ".$row['last_name'];
				//$email_id = $row['email_id'];
				$comp_contact_number_id = $row['contact_number_id'];
				$comp_phone_q = $this->company_m->fetch_phone($comp_contact_number_id);
				foreach ($comp_phone_q->result_array() as $phone_row){
					if($phone_row['office_number'] == ""):
						$data['comp_office_number'] = "";
					else:
						$data['comp_office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					endif;
				}
			}
		else:
			$data['comp_office_number'] = $pending_cont_number;
		endif;
		//$email_q = $this->company_m->fetch_email($email_id);
		//foreach ($email_q->result_array() as $row){
		 //  $data['email'] = $row['general_email'];
		//}
		
		
			
		$work_sel_cont_q = $this->works_m->display_works_selected_contractor($is_pending,$work_id,$contractor_id);
		foreach ($work_sel_cont_q->result_array() as $work_cont_row){
			if($is_pending == 0):
			
				$work_cont_contact_person_id = $work_cont_row['contact_person_id'];
				$work_cont_person_q = $this->company_m->fetch_all_contact_persons($work_cont_contact_person_id);
				foreach ($work_cont_person_q->result_array() as $work_cont_person_row){
				    $data['attention'] = $work_cont_person_row['first_name']." ".$work_cont_person_row['last_name'];
					   
					$contact_number_id = $work_cont_person_row['contact_number_id'];
					$phon_q = $this->company_m->fetch_phone($contact_number_id);
					foreach ($phon_q->result_array() as $phone_row){
						if($phone_row['office_number'] == ""):
							$data['office_number'] = "";
						else:
							$data['office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
						endif;
						
					}

					$contact_email_id = $work_cont_person_row['email_id'];
					$email_q = $this->company_m->fetch_email($contact_email_id);
					foreach ($email_q->result_array() as $email_row){
						$data['cont_email'] = $email_row['general_email'];
					}
				}
			else:
				$pending_comp_q =  $this->company_m->fetch_selcted_temporary_comp($contractor_id);
				foreach ($pending_comp_q->result_array() as $pending_comp_row){
					$work_company_name = $pending_comp_row['company_name'];
					$data['attention'] = $pending_comp_row['contact_person_fname']." ".$pending_comp_row['contact_person_sname'];
					$data['office_number'] = $pending_comp_row['contact_number'];
					$data['cont_email'] = $pending_comp_row['email'];
				}
			endif;
		}

		if($is_pending == 0):
			
			$comp_det_q = $this->company_m->display_company_detail_by_id($contractor_id);
			foreach ($comp_det_q->result_array() as $row){
			   $data['work_company_name'] = $row['company_name'];
			   $address_id = $row['address_id'];

			   //--- For Insurance ------
			   if($row['public_liability_expiration'] !== ""){
					$ple_raw_data = $row['public_liability_expiration'];
					$ple_arr =  explode('/',$ple_raw_data);
					$ple_day = $ple_arr[0];
					$ple_month = $ple_arr[1];
					$ple_year = $ple_arr[2];
					$ple_date = $ple_year.'-'.$ple_month.'-'.$ple_day;
				}

				if($row['workers_compensation_expiration'] !== ""){
					$wce_raw_data = $row['workers_compensation_expiration'];
					$wce_arr =  explode('/',$wce_raw_data);
					$wce_day = $wce_arr[0];
					$wce_month = $wce_arr[1];
					$wce_year = $wce_arr[2];
					$wce_date = $wce_year.'-'.$wce_month.'-'.$wce_day;
				}

				if($row['income_protection_expiration'] !== ""){
					$ipe_raw_data = $row['income_protection_expiration'];
					$ipe_arr =  explode('/',$ipe_raw_data);
					$ipe_day = $ipe_arr[0];
					$ipe_month = $ipe_arr[1];
					$ipeyear = $ipe_arr[2];
					$ipe_date = $ipe_year.'-'.$ipe_month.'-'.$ipe_day;
				}
				$today = date('Y-m-d');
				
				$complete = 0;
				$incomplete = 0;
				
				if($row['company_type_id'] == '2'){
					if($row['has_insurance_public_liability'] == 1){
						if($row['public_liability_expiration'] !== ""){
							if($ple_date <= $today){
								$incomplete = 1;
							}else{
								if($row['has_insurance_workers_compensation'] == 1){
									if($row['workers_compensation_expiration'] !== ""){
										if($wce_date <= $today){
											$incomplete = 1;
										}else{
											$complete = 1;
										}
									}else{
										$incomplete = 1;
									}
								}else{
									if($row['has_insurance_income_protection'] == 1){
										if($row['income_protection_expiration'] !== ""){
											if($ipe_date <= $today){
												$incomplete = 1;
											}else{
												$complete = 1;
											}
										}else{
											$incomplete = 1;
										}
									}else{
										$incomplete = 1;
									}
								}
							}
						}else{
							$incomplete = 1;
						}
						
					}else{
						$incomplete = 1;
					}
				}

				$admin_q = $this->admin_m->fetch_admin_defaults();
				foreach ($admin_q->result_array() as $admin_row){
					$cqr_notes_no_insurance = $admin_row['cqr_notes_no_insurance'];
					$cqr_notes_w_insurance = $admin_row['cqr_notes_w_insurance'];
				}
				
				if($row['company_type_id'] == '2'){
					if($complete == 1){
						$data['cqr_notes_insurance'] = $cqr_notes_w_insurance;
						$data['insurance_stat'] = 1;
					}else{
						$data['cqr_notes_insurance'] = $cqr_notes_no_insurance;
						$data['insurance_stat'] = 0;
					}
				}else{
					$data['cqr_notes_insurance'] = $cqr_notes_w_insurance;
					$data['insurance_stat'] = 1;
				}

			   //--- For Insurance ------

			   $comp_add_q = $this->company_m->fetch_complete_detail_address($address_id);
			   foreach ($comp_add_q->result_array() as $comp_add_row){
			   		if($comp_add_row['unit_level'] == ""){
			   			$data['comp_address_1st'] = $comp_add_row['unit_number'];
			   		}else{
			   			$data['comp_address_1st'] = $comp_add_row['unit_level']." / ".$comp_add_row['unit_number'];
			   		}
					$data['comp_address_2nd'] = $comp_add_row['street'];
					$data['comp_address_3rd'] = ucfirst(strtolower($comp_add_row['suburb']))." ".$comp_add_row['shortname']." ".$comp_add_row['postcode'];
			   }
			   $data['abn'] = $row['abn'];
			}
		else:
			$data['work_company_name'] = $work_company_name;
			$data['cqr_notes_insurance'] = "";
			$data['insurance_stat'] = 0;
			$data['comp_address_1st'] = "";
			$data['comp_address_2nd'] = "";
			$data['comp_address_3rd'] = "";
			$data['abn'] = "";

		endif;
		
		$work_attachement_q = $this->works_m->fetch_work_attachment_type($work_id);

		$this->works_m->update_works_contractor_cqr($work_id,$contractor_id);
		$data['work_id'] = $work_id;
		$data['work_attachement_t'] = $work_attachement_q;
		$data['proj_id'] = $proj_id;
		$this->load->view('contractor_quote_request_nodisplay',$data);
	}

	function work_contractor_po(){
		$proj_id = $this->uri->segment(3);
		$work_id = $this->uri->segment(4);
		$work_joinery_id = $this->uri->segment(5);
		if($work_joinery_id == ""){
			//$this->works_m->create_cpo_date($work_id);
			$is_deliver_office = 0;
			$work_q = $this->works_m->display_works_selected($work_id);
			foreach ($work_q->result_array() as $row){
			   $is_deliver_office = $row['is_deliver_office'];
			   $company_client_id = $row['company_client_id'];
			   $price = $row['price'];
			   $data['price'] = $price;
			   $contractor_type = $row['contractor_type'];
			   $goods_deliver_by_date = $row['goods_deliver_by_date'];
			   if($goods_deliver_by_date == "" ){
			   		$data['goods_deliver_by_date'] = "";	
			   }else{
			   		$data['goods_deliver_by_date'] = $row['goods_deliver_by_date'];
			   }

			   $note_id = $row['note_id'];
			   $note_q = $this->company_m->fetch_notes($note_id);
			   foreach ($note_q->result_array() as $note_row){
			   		$data['notes'] = str_replace("&apos;","'",$note_row['notes']);
			   }
			   if($contractor_type == 2){
			   		if($row['work_con_sup_id'] == 82){
			   			$data['work_desc'] = str_replace("&apos;","'",$row['other_work_desc']);

			   		}else{
			   			$data['work_desc'] = str_replace("&apos;","'",$row['job_sub_cat']);
			   		}
			   }else{
			   		$data['work_desc'] = str_replace("&apos;","'",$row['supplier_cat_name']);
			   }


			   $is_pending = 0;

			   $work_sel_cont_q = $this->works_m->display_works_selected_contractor($is_pending,$work_id,$company_client_id);
			   foreach ($work_sel_cont_q->result_array() as $work_cont_row){
			   		$work_cont_contact_person_id = $work_cont_row['contact_person_id'];
			   		$work_cont_person_q = $this->company_m->fetch_all_contact_persons($work_cont_contact_person_id);
					foreach ($work_cont_person_q->result_array() as $work_cont_person_row){
					   $data['attention'] = $work_cont_person_row['first_name']." ".$work_cont_person_row['last_name'];
					   $contact_number_id = $work_cont_person_row['contact_number_id'];
					   $phon_q = $this->company_m->fetch_phone($contact_number_id);

					   foreach ($phon_q->result_array() as $phone_row){
					   		if($phone_row['office_number'] == ""):
					   			$data['office_number'] = "";
					   		else:
					   			$data['office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					   		endif;
					   }
					}
			   }
			}
			$proj_q = $this->projects_m->select_particular_project($proj_id);
			foreach ($proj_q->result_array() as $row){
			   	$primary_contact_person_id = $row['primary_contact_person_id'];
			   	$tenancyno = $row['shop_tenancy_number'].' '.$row['shop_name'];
			   	$client_id = $row['client_id'];
				$job_type = $row['job_type'];
				$data['start_date'] = $row['date_site_commencement'];
				$data['end_date'] = $row['date_site_finish'];
				
				$data['project_total'] = $row['project_total'];

				$project_manager_id = $row['project_manager_id'];
				$users_q = $this->user_model->fetch_user($project_manager_id);
				foreach ($users_q->result_array() as $users_row){
					$data['contact_person'] = $users_row['user_first_name']." ".$users_row['user_last_name'];
					$user_email_id = $users_row['user_email_id'];
					$email_q = $this->company_m->fetch_email($user_email_id);
					foreach ($email_q->result_array() as $email_row){
					   $data['email'] = $email_row['general_email'];
					}

				}

				$focus_company_id = $row['focus_company_id'];
				$focus_comp_q = $this->admin_m->fetch_single_company_focus($focus_company_id);
				foreach ($focus_comp_q->result_array() as $focus_comp_row){
					$data['focus_logo'] = $focus_comp_row['logo'];
					$data['focus_comp'] = $focus_comp_row['company_name'];
					$officnumber = $focus_comp_row['office_number'];
					$prefix = substr($officnumber, 0, 4);

					if($prefix == '1300' || $prefix == '1800'){
						$data['office_no'] = $focus_comp_row['office_number'];
					}else{
						if($focus_comp_row['office_number'] == ""):
							$data['office_no'] = "";
						else:
							$data['office_no'] = $focus_comp_row['area_code']." ".$focus_comp_row['office_number'];
						endif;
					}
					$data['acn'] = $focus_comp_row['acn'];
					$data['abn'] = $focus_comp_row['abn'];
					$data['focus_email'] = $focus_comp_row['general_email'];
					$address_id = $focus_comp_row['postal_address_id'];
					$physical_address = $focus_comp_row['address_id'];
					$focus_comp_q = $this->company_m->fetch_complete_detail_address($address_id);
					foreach ($focus_comp_q->result_array() as $comp_address_row){
						$po_box = $comp_address_row['po_box'];
						if($po_box == ""){
							$data['po_box'] = "";
						}else{
							$data['po_box'] = "PO ".$comp_address_row['po_box'];
						}
						$data['focus_suburb'] = ucwords(strtolower($comp_address_row['suburb']))." ".$comp_address_row['shortname']." ".$comp_address_row['postcode'];
					}
				}

				if($is_deliver_office == 0){
					$site_address_id= $row['site_add'];
				}else{
					$tenancyno = "";
					$site_address_id= $physical_address;
				}


				if($job_type == "Shopping Center"){
			   		$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
					foreach ($site_address_q->result_array() as $client_add_row){
					/*
						if($client_add_row['unit_level'] == ""){
							if($is_deliver_office == 0){
								$data['site_address_1st'] = $tenancyno." ".$client_add_row['unit_number'];
							}else{
								$data['site_address_1st'] = $client_add_row['unit_number'];
							}
						}else{
							if($is_deliver_office == 0){
								$data['site_address_1st'] = $tenancyno." ".$client_add_row['unit_level']." / ".$client_add_row['unit_number'];
							}else{
								$data['site_address_1st'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'];
							}
							
						}
						
						$data['site_address_2nd'] = $client_add_row['street'];

*/


					$data['site_address_1st'] = $tenancyno;

					if($client_add_row['unit_level'] == ""){
						$data['site_address_2nd'] = $client_add_row['unit_number'].' '.$client_add_row['street'];
					}else{
						$data['site_address_2nd'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'].' '.$client_add_row['street'];
					}
					



						$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
					}	
			   	}else{
					$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
					foreach ($site_address_q->result_array() as $client_add_row){
						if($client_add_row['unit_level'] == ""){
							$data['site_address_1st'] = $client_add_row['unit_number'];
						}else{
							$data['site_address_1st'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'];
						}
						$data['site_address_2nd'] = $client_add_row['street'];
						$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
					}
			   	}
			   	
			}


			$comp_q = $this->company_m->fetch_all_contact_persons($primary_contact_person_id);
			foreach ($comp_q->result_array() as $row){
				//$data['contact_person'] = $row['first_name']." ".$row['last_name'];
				//$email_id = $row['email_id'];
				$comp_contact_number_id = $row['contact_number_id'];
				$comp_phone_q = $this->company_m->fetch_phone($comp_contact_number_id);
				foreach ($comp_phone_q->result_array() as $phone_row){
					if($phone_row['office_number'] == ""):
						$data['comp_office_number'] = "";
					else:
						$data['comp_office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					endif;
					
				}
			}

			if($is_deliver_office == 1){
				$all_focus_company = $this->admin_m->fetch_all_company_focus();
				foreach ($all_focus_company->result_array() as $foc_comp_row){
					if($foc_comp_row['company_id'] == $focus_company_id){
						if($foc_comp_row['company_id'] == 3 || $foc_comp_row['company_id'] == 6 ){
							$data['comp_office_number'] = '1300 '.$foc_comp_row['office_number'];
						}else{
							$data['comp_office_number'] = '08 '.$foc_comp_row['office_number'];
						}
						
					}
				}
			}
			//$email_q = $this->company_m->fetch_email($email_id);
			//foreach ($email_q->result_array() as $row){
			 //  $data['email'] = $row['general_email'];
			//}


			$comp_det_q = $this->company_m->display_company_detail_by_id($company_client_id);
			foreach ($comp_det_q->result_array() as $row){
			   $data['work_company_name'] = $row['company_name'];
			   //--- For Insurance ------
			   if($row['public_liability_expiration'] !== ""){
					$ple_raw_data = $row['public_liability_expiration'];
					$ple_arr =  explode('/',$ple_raw_data);
					$ple_day = $ple_arr[0];
					$ple_month = $ple_arr[1];
					$ple_year = $ple_arr[2];
					$ple_date = $ple_year.'-'.$ple_month.'-'.$ple_day;
				}

				if($row['workers_compensation_expiration'] !== ""){
					$wce_raw_data = $row['workers_compensation_expiration'];
					$wce_arr =  explode('/',$wce_raw_data);
					$wce_day = $wce_arr[0];
					$wce_month = $wce_arr[1];
					$wce_year = $wce_arr[2];
					$wce_date = $wce_year.'-'.$wce_month.'-'.$wce_day;
				}

				if($row['income_protection_expiration'] !== ""){
					$ipe_raw_data = $row['income_protection_expiration'];
					$ipe_arr =  explode('/',$ipe_raw_data);
					$ipe_day = $ipe_arr[0];
					$ipe_month = $ipe_arr[1];
					$ipe_year = $ipe_arr[2];
					$ipe_date = $ipe_year.'-'.$ipe_month.'-'.$ipe_day;
				}
				$today = date('Y-m-d');
				
				$complete = 0;
				$incomplete = 0;
				
				if($row['company_type_id'] == '2'){
					if($row['has_insurance_public_liability'] == 1){
						if($row['public_liability_expiration'] !== ""){
							if($ple_date <= $today){
								$incomplete = 1;
							}else{
								if($row['has_insurance_workers_compensation'] == 1){
									if($row['workers_compensation_expiration'] !== ""){
										if($wce_date <= $today){
											$incomplete = 1;
										}else{
											$complete = 1;
										}
									}else{
										$incomplete = 1;
									}
								}else{
									if($row['has_insurance_income_protection'] == 1){
										if($row['income_protection_expiration'] !== ""){
											if($ipe_date <= $today){
												$incomplete = 1;
											}else{
												$complete = 1;
											}
										}else{
											$incomplete = 1;
										}
									}else{
										$incomplete = 1;
									}
								}
							}
						}else{
							$incomplete = 1;
						}
						
					}else{
						$incomplete = 1;
					}
				}

				$admin_q = $this->admin_m->fetch_admin_defaults();
				foreach ($admin_q->result_array() as $admin_row){
					$cpo_notes_no_insurance = $admin_row['cpo_notes_no_insurance'];
					$cpo_notes_w_insurance = $admin_row['cpo_notes_w_insurance'];
				}
				
				if($row['company_type_id'] == '2'){
					if($complete == 1){
						$data['cpo_notes_insurance'] = $cpo_notes_w_insurance;
						$data['insurance_stat'] = 1;
					}else{
						$data['cpo_notes_insurance'] = $cpo_notes_no_insurance;
						$data['insurance_stat'] = 0;
					}
				}else{
					$data['cpo_notes_insurance'] = $cpo_notes_w_insurance;
					$data['insurance_stat'] = 1;
				}

			   //--- For Insurance ------
			   $address_id = $row['address_id'];
			   $comp_add_q = $this->company_m->fetch_complete_detail_address($address_id);
			   foreach ($comp_add_q->result_array() as $comp_add_row){
			   		if($comp_add_row['unit_level'] == ""){
			   			$data['comp_address_1st'] = $comp_add_row['unit_number'];
			   		}else{
			   			$data['comp_address_1st'] = $comp_add_row['unit_level']." / ".$comp_add_row['unit_number'];
			   		}
					$data['comp_address_2nd'] = $comp_add_row['street'];
					$data['comp_address_3rd'] = ucfirst(strtolower($comp_add_row['suburb']))." ".$comp_add_row['shortname']." ".$comp_add_row['postcode'];
			   }
			}


			$data['project_t'] = $proj_q;
			$data['works_t'] = $work_q;
			$data['proj_id'] = $proj_id;
			$data['work_id'] = $work_id;

			$this->works_m->update_works_contractor_cpo($work_id,$company_client_id);

			$q_admin_defaults = $this->admin_m->fetch_admin_defaults();
			foreach ($q_admin_defaults->result_array() as $row){
				$gst_rate = $row['gst_rate'];
			}
			$data['gst_rate'] = $gst_rate;
			$percent_gst_rate = $price * ($gst_rate/100);
	    	$data['inc_gst'] = $price + $percent_gst_rate;
			$this->load->view('contractor_purchase_order', $data);
		}else{
			//$this->works_m->create_cpo_date($work_id);
			$array = explode("-",$work_joinery_id);
			$joinery_id = $array[1];
			$this->works_m->create_joinery_cpo_date($joinery_id);

			$work_q = $this->works_m->display_selected_works_joinery($joinery_id);
			foreach ($work_q->result_array() as $row){
			   $is_deliver_office = $row['is_deliver_office'];
			   $company_client_id = $row['work_joinery_contractor_id'];
			   $price = $row['wj_price'];
			   $data['price'] = $price;
			   //$contractor_type = $row['contractor_type'];
			   $goods_deliver_by_date = $row['goods_deliver_by_date'];
			   if($goods_deliver_by_date == "" ){
			   		$data['goods_deliver_by_date'] = "";	
			   }else{
			   		$data['goods_deliver_by_date'] = $row['goods_deliver_by_date'];
			   }

			   $note_id = $row['wj_note_id'];
			   $note_q = $this->company_m->fetch_notes($note_id);
			   foreach ($note_q->result_array() as $note_row){
			   		$data['notes'] = $note_row['notes'];
			   }
			   
			   $data['work_desc'] = $row['joinery_name'];
			   $is_pending = 0;
			   $work_sel_cont_q = $this->works_m->display_works_selected_contractor($is_pending,$work_joinery_id,$company_client_id);
			   foreach ($work_sel_cont_q->result_array() as $work_cont_row){
			   		$work_cont_contact_person_id = $work_cont_row['contact_person_id'];
			   		$work_cont_person_q = $this->company_m->fetch_all_contact_persons($work_cont_contact_person_id);
					foreach ($work_cont_person_q->result_array() as $work_cont_person_row){
					   $data['attention'] = $work_cont_person_row['first_name']." ".$work_cont_person_row['last_name'];
					   $contact_number_id = $work_cont_person_row['contact_number_id'];
					   $phon_q = $this->company_m->fetch_phone($contact_number_id);
					   foreach ($phon_q->result_array() as $phone_row){
					   		if($phone_row['office_number'] == ""):
					   			$data['office_number'] = "";
					   		else:
					   			$data['office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					   		endif;
					   }
					}
			   }
			}

			$proj_q = $this->projects_m->select_particular_project($proj_id);
			foreach ($proj_q->result_array() as $row){
			   	$primary_contact_person_id = $row['primary_contact_person_id'];
			   	$tenancyno = $row['shop_tenancy_number'].' '.$row['shop_name'];
			   	$client_id = $row['client_id'];
				$job_type = $row['job_type'];
				$data['start_date'] = $row['date_site_commencement'];
				$data['end_date'] = $row['date_site_finish'];
				
				$data['project_total'] = $row['project_total'];

				$project_manager_id = $row['project_manager_id'];
				$users_q = $this->user_model->fetch_user($project_manager_id);
				foreach ($users_q->result_array() as $users_row){
					$data['contact_person'] = $users_row['user_first_name']." ".$users_row['user_last_name'];
					$user_email_id = $users_row['user_email_id'];
					$email_q = $this->company_m->fetch_email($user_email_id);
					foreach ($email_q->result_array() as $email_row){
					   $data['email'] = $email_row['general_email'];
					}

				}

				$focus_company_id = $row['focus_company_id'];
				$focus_comp_q = $this->admin_m->fetch_single_company_focus($focus_company_id);
				foreach ($focus_comp_q->result_array() as $focus_comp_row){
					$data['focus_logo'] = $focus_comp_row['logo'];
					$data['focus_comp'] = $focus_comp_row['company_name'];
					$officnumber = $focus_comp_row['office_number'];
					$prefix = substr($officnumber, 0, 4);

					if($prefix == '1300' || $prefix == '1800'){
						$data['office_no'] = $focus_comp_row['office_number'];
					}else{
						$data['office_no'] = $focus_comp_row['area_code']." ".$focus_comp_row['office_number'];
					}
					$data['acn'] = $focus_comp_row['acn'];
					$data['abn'] = $focus_comp_row['abn'];
					$data['focus_email'] = $focus_comp_row['general_email'];
					$address_id = $focus_comp_row['address_id'];
					$physical_address = $focus_comp_row['address_id'];
					$focus_comp_q = $this->company_m->fetch_complete_detail_address($address_id);
					foreach ($focus_comp_q->result_array() as $comp_address_row){
						$po_box = $comp_address_row['po_box'];
						if($po_box == ""){
							$data['po_box'] = "";
						}else{
							$data['po_box'] = "PO ".$comp_address_row['po_box'];
						}
						$data['focus_suburb'] = ucwords(strtolower($comp_address_row['suburb']))." ".$comp_address_row['shortname']." ".$comp_address_row['postcode'];
					}
				}

				if($is_deliver_office == 0){
					$site_address_id= $row['site_add'];
				}else{
					$site_address_id= $physical_address;
				}

				if($job_type == "Shopping Center"){
			   		$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
					foreach ($site_address_q->result_array() as $client_add_row){

						$data['site_address_1st'] = $tenancyno;

					if($client_add_row['unit_level'] == ""){
						$data['site_address_2nd'] = $client_add_row['unit_number'].' '.$client_add_row['street'];
					}else{
						$data['site_address_2nd'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'].' '.$client_add_row['street'];
					}

						$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
					}	
			   	}else{
					$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
					foreach ($site_address_q->result_array() as $client_add_row){
						if($client_add_row['unit_level'] == ""){
							$data['site_address_1st'] = $client_add_row['unit_number'];
						}else{
							$data['site_address_1st'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'];
						}
						$data['site_address_2nd'] = $client_add_row['street'];
						$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
					}
			   	}
			   	
			}


			$comp_q = $this->company_m->fetch_all_contact_persons($primary_contact_person_id);
			foreach ($comp_q->result_array() as $row){
				//$data['contact_person'] = $row['first_name']." ".$row['last_name'];
				//$email_id = $row['email_id'];
				$comp_contact_number_id = $row['contact_number_id'];
				$comp_phone_q = $this->company_m->fetch_phone($comp_contact_number_id);
				foreach ($comp_phone_q->result_array() as $phone_row){
					if($phone_row['office_number'] == ""):
						$data['comp_office_number'] = "";
					else:
						$data['comp_office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					endif;
					
				}
			}

			if($is_deliver_office == 1){
				$all_focus_company = $this->admin_m->fetch_all_company_focus();
				foreach ($all_focus_company->result_array() as $foc_comp_row){
					if($foc_comp_row['company_id'] == $focus_company_id){
						if($foc_comp_row['company_id'] == 3 || $foc_comp_row['company_id'] == 6 ){
							$data['comp_office_number'] = '1300 '.$foc_comp_row['office_number'];
						}else{
							$data['comp_office_number'] = '08 '.$foc_comp_row['office_number'];
						}
						
					}
				}
			}
			//$email_q = $this->company_m->fetch_email($email_id);
			//foreach ($email_q->result_array() as $row){
			 //  $data['email'] = $row['general_email'];
			//}
			
			
			$comp_det_q = $this->company_m->display_company_detail_by_id($company_client_id);
			foreach ($comp_det_q->result_array() as $row){
			   $data['work_company_name'] = $row['company_name'];
			   //--- For Insurance ------
			   if($row['public_liability_expiration'] !== ""){
					$ple_raw_data = $row['public_liability_expiration'];
					$ple_arr =  explode('/',$ple_raw_data);
					$ple_day = $ple_arr[0];
					$ple_month = $ple_arr[1];
					$ple_year = $ple_arr[2];
					$ple_date = $ple_year.'-'.$ple_month.'-'.$ple_day;
				}

				if($row['workers_compensation_expiration'] !== ""){
					$wce_raw_data = $row['workers_compensation_expiration'];
					$wce_arr =  explode('/',$wce_raw_data);
					$wce_day = $wce_arr[0];
					$wce_month = $wce_arr[1];
					$wce_year = $wce_arr[2];
					$wce_date = $wce_year.'-'.$wce_month.'-'.$wce_day;
				}

				if($row['income_protection_expiration'] !== ""){
					$ipe_raw_data = $row['income_protection_expiration'];
					$ipe_arr =  explode('/',$ipe_raw_data);
					$ipe_day = $ipe_arr[0];
					$ipe_month = $ipe_arr[1];
					$ipe_year = $ipe_arr[2];
					$ipe_date = $ipe_year.'-'.$ipe_month.'-'.$ipe_day;
				}
				$today = date('Y-m-d');
				
				$complete = 0;
				$incomplete = 0;
				
				if($row['company_type_id'] == '2'){
					if($row['has_insurance_public_liability'] == 1){
						if($row['public_liability_expiration'] !== ""){
							if($ple_date <= $today){
								$incomplete = 1;
							}else{
								if($row['has_insurance_workers_compensation'] == 1){
									if($row['workers_compensation_expiration'] !== ""){
										if($wce_date <= $today){
											$incomplete = 1;
										}else{
											$complete = 1;
										}
									}else{
										$incomplete = 1;
									}
								}else{
									if($row['has_insurance_income_protection'] == 1){
										if($row['income_protection_expiration'] !== ""){
											if($ipe_date <= $today){
												$incomplete = 1;
											}else{
												$complete = 1;
											}
										}else{
											$incomplete = 1;
										}
									}else{
										$incomplete = 1;
									}
								}
							}
						}else{
							$incomplete = 1;
						}
						
					}else{
						$incomplete = 1;
					}
				}

				$admin_q = $this->admin_m->fetch_admin_defaults();
				foreach ($admin_q->result_array() as $admin_row){
					$cpo_notes_no_insurance = $admin_row['cpo_notes_no_insurance'];
					$cpo_notes_w_insurance = $admin_row['cpo_notes_w_insurance'];
				}
				
				if($row['company_type_id'] == '2'){
					if($complete == 1){
						$data['cpo_notes_insurance'] = $cpo_notes_w_insurance;
						$data['insurance_stat'] = 1;
					}else{
						$data['cpo_notes_insurance'] = $cpo_notes_no_insurance;
						$data['insurance_stat'] = 0;
					}
				}else{
					$data['cpo_notes_insurance'] = $cpo_notes_w_insurance;
					$data['insurance_stat'] = 1;
				}

			   //--- For Insurance ------
			   $address_id = $row['address_id'];
			   $comp_add_q = $this->company_m->fetch_complete_detail_address($address_id);
			   foreach ($comp_add_q->result_array() as $comp_add_row){
			   		if($comp_add_row['unit_level'] == ""){
			   			$data['comp_address_1st'] = $comp_add_row['unit_number'];
			   		}else{
			   			$data['comp_address_1st'] = $comp_add_row['unit_level']." / ".$comp_add_row['unit_number'];
			   		}
					$data['comp_address_2nd'] = $comp_add_row['street'];
					$data['comp_address_3rd'] = ucfirst(strtolower($comp_add_row['suburb']))." ".$comp_add_row['shortname']." ".$comp_add_row['postcode'];
			   }
			}


			$data['project_t'] = $proj_q;
			$data['works_t'] = $work_q;
			$data['proj_id'] = $proj_id;
			$data['work_id'] = $work_joinery_id;

			$this->works_m->update_works_contractor_cpo($work_joinery_id,$company_client_id);

			$q_admin_defaults = $this->admin_m->fetch_admin_defaults();
			foreach ($q_admin_defaults->result_array() as $row){
				$gst_rate = $row['gst_rate'];
			}
			$data['gst_rate'] = $gst_rate;
			$percent_gst_rate = $price * ($gst_rate/100);
	    	$data['inc_gst'] = $price + $percent_gst_rate;
			$this->load->view('contractor_purchase_order', $data);
		}
	
	}

	function work_contractor_po_docstorage(){
		$proj_id = $_POST['proj_id'];
		$work_id = $_POST['work_id'];
		$work_joinery_id = "";
		$cpo_already_created = 0;
		if(isset($_POST['work_joinery_id'])){
			$work_joinery_id = $_POST['joinery_work_id'];
		}
		// $proj_id = $this->uri->segment(3);
		// $work_id = $this->uri->segment(4);
		// $work_joinery_id = $this->uri->segment(5);
		if($work_joinery_id == ""){
			$is_deliver_office = 0;
			$work_q = $this->works_m->display_works_selected($work_id);
			foreach ($work_q->result_array() as $row){
			   $is_deliver_office = $row['is_deliver_office'];
			   $company_client_id = $row['company_client_id'];
			   $price = $row['price'];
			   $data['price'] = $price;
			   $contractor_type = $row['contractor_type'];
			   $goods_deliver_by_date = $row['goods_deliver_by_date'];
			   if($goods_deliver_by_date == "" ){
			   		$data['goods_deliver_by_date'] = "";	
			   }else{
			   		$data['goods_deliver_by_date'] = $row['goods_deliver_by_date'];
			   }

			   $note_id = $row['note_id'];
			   $note_q = $this->company_m->fetch_notes($note_id);
			   foreach ($note_q->result_array() as $note_row){
			   		$data['notes'] = str_replace("&apos;","'",$note_row['notes']);
			   }
			   if($contractor_type == 2){
			   		if($row['work_con_sup_id'] == 82){
			   			$data['work_desc'] = str_replace("&apos;","'",$row['other_work_desc']);

			   		}else{
			   			$data['work_desc'] = str_replace("&apos;","'",$row['job_sub_cat']);
			   		}
			   }else{
			   		$data['work_desc'] = str_replace("&apos;","'",$row['supplier_cat_name']);
			   }
			   $is_pending = 0;
			   $work_sel_cont_q = $this->works_m->display_works_selected_contractor($is_pending,$work_id,$company_client_id);
			   foreach ($work_sel_cont_q->result_array() as $work_cont_row){
			   		$cpo_created = $work_cont_row['cpo_created'];
			   		if($cpo_created == 1){
			   			$cpo_already_created = 1;
			   		}
			   		$work_cont_contact_person_id = $work_cont_row['contact_person_id'];
			   		$work_cont_person_q = $this->company_m->fetch_all_contact_persons($work_cont_contact_person_id);
					foreach ($work_cont_person_q->result_array() as $work_cont_person_row){
					   $data['attention'] = $work_cont_person_row['first_name']." ".$work_cont_person_row['last_name'];
					   $contact_number_id = $work_cont_person_row['contact_number_id'];
					   $phon_q = $this->company_m->fetch_phone($contact_number_id);
					   foreach ($phon_q->result_array() as $phone_row){
					   		if($phone_row['office_number'] == ""):
					   			$data['office_number'] = "";
					   		else:
					   			$data['office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					   		endif;
					   }
					}
			   }
			}
			$proj_q = $this->projects_m->select_particular_project($proj_id);
			foreach ($proj_q->result_array() as $row){
			   	$primary_contact_person_id = $row['primary_contact_person_id'];
			   	$tenancyno = $row['shop_tenancy_number'].' '.$row['shop_name'];
			   	$client_id = $row['client_id'];
				$job_type = $row['job_type'];
				$data['start_date'] = $row['date_site_commencement'];
				$data['end_date'] = $row['date_site_finish'];
				
				$data['project_total'] = $row['project_total'];

				$project_manager_id = $row['project_manager_id'];
				$users_q = $this->user_model->fetch_user($project_manager_id);
				foreach ($users_q->result_array() as $users_row){
					$data['contact_person'] = $users_row['user_first_name']." ".$users_row['user_last_name'];
					$user_email_id = $users_row['user_email_id'];
					$email_q = $this->company_m->fetch_email($user_email_id);
					foreach ($email_q->result_array() as $email_row){
					   $data['email'] = $email_row['general_email'];
					}

				}

				$focus_company_id = $row['focus_company_id'];
				$focus_comp_q = $this->admin_m->fetch_single_company_focus($focus_company_id);
				foreach ($focus_comp_q->result_array() as $focus_comp_row){
					$data['focus_logo'] = $focus_comp_row['logo'];
					$data['focus_comp'] = $focus_comp_row['company_name'];
					$officnumber = $focus_comp_row['office_number'];
					$prefix = substr($officnumber, 0, 4);

					if($prefix == '1300' || $prefix == '1800'){
						$data['office_no'] = $focus_comp_row['office_number'];
					}else{
						if($focus_comp_row['office_number'] == ""):
							$data['office_no'] = "";
						else:
							$data['office_no'] = $focus_comp_row['area_code']." ".$focus_comp_row['office_number'];
						endif;
					}
					$data['acn'] = $focus_comp_row['acn'];
					$data['abn'] = $focus_comp_row['abn'];
					$data['focus_email'] = $focus_comp_row['general_email'];
					$address_id = $focus_comp_row['postal_address_id'];
					$physical_address = $focus_comp_row['address_id'];
					$focus_comp_q = $this->company_m->fetch_complete_detail_address($address_id);
					foreach ($focus_comp_q->result_array() as $comp_address_row){
						$po_box = $comp_address_row['po_box'];
						if($po_box == ""){
							$data['po_box'] = "";
						}else{
							$data['po_box'] = "PO ".$comp_address_row['po_box'];
						}
						$data['focus_suburb'] = ucwords(strtolower($comp_address_row['suburb']))." ".$comp_address_row['shortname']." ".$comp_address_row['postcode'];
					}
				}

				if($is_deliver_office == 0){
					$site_address_id= $row['site_add'];
				}else{
					$site_address_id= $physical_address;
				}


				if($job_type == "Shopping Center"){
			   		$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
					foreach ($site_address_q->result_array() as $client_add_row){
						$data['site_address_1st'] = $tenancyno;

						if($client_add_row['unit_level'] == ""){
							$data['site_address_2nd'] = $client_add_row['unit_number'].' '.$client_add_row['street'];
						}else{
							$data['site_address_2nd'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'].' '.$client_add_row['street'];
						}

						$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
					}	
			   	}else{
					$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
					foreach ($site_address_q->result_array() as $client_add_row){
						if($client_add_row['unit_level'] == ""){
							$data['site_address_1st'] = $client_add_row['unit_number'];
						}else{
							$data['site_address_1st'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'];
						}
						$data['site_address_2nd'] = $client_add_row['street'];
						$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
					}
			   	}
			   	
			}


			$comp_q = $this->company_m->fetch_all_contact_persons($primary_contact_person_id);
			foreach ($comp_q->result_array() as $row){
				$comp_contact_number_id = $row['contact_number_id'];
				$comp_phone_q = $this->company_m->fetch_phone($comp_contact_number_id);
				foreach ($comp_phone_q->result_array() as $phone_row){
					if($phone_row['office_number'] == ""):
						$data['comp_office_number'] = "";
					else:
						$data['comp_office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					endif;
					
				}
			}

			$work_company_name = "";
			$comp_det_q = $this->company_m->display_company_detail_by_id($company_client_id);
			foreach ($comp_det_q->result_array() as $row){
			   $data['work_company_name'] = $row['company_name'];
			   $work_company_name = $row['company_name'];
			   //--- For Insurance ------
			   if($row['public_liability_expiration'] !== ""){
					$ple_raw_data = $row['public_liability_expiration'];
					$ple_arr =  explode('/',$ple_raw_data);
					$ple_day = $ple_arr[0];
					$ple_month = $ple_arr[1];
					$ple_year = $ple_arr[2];
					$ple_date = $ple_year.'-'.$ple_month.'-'.$ple_day;
				}

				if($row['workers_compensation_expiration'] !== ""){
					$wce_raw_data = $row['workers_compensation_expiration'];
					$wce_arr =  explode('/',$wce_raw_data);
					$wce_day = $wce_arr[0];
					$wce_month = $wce_arr[1];
					$wce_year = $wce_arr[2];
					$wce_date = $wce_year.'-'.$wce_month.'-'.$wce_day;
				}

				if($row['income_protection_expiration'] !== ""){
					$ipe_raw_data = $row['income_protection_expiration'];
					$ipe_arr =  explode('/',$ipe_raw_data);
					$ipe_day = $ipe_arr[0];
					$ipe_month = $ipe_arr[1];
					$ipeyear = $ipe_arr[2];
					$ipe_date = $ipe_year.'-'.$ipe_month.'-'.$ipe_day;
				}
				$today = date('Y-m-d');
				
				$complete = 0;
				$incomplete = 0;
				
				if($row['company_type_id'] == '2'){
					if($row['has_insurance_public_liability'] == 1){
						if($row['public_liability_expiration'] !== ""){
							if($ple_date <= $today){
								$incomplete = 1;
							}else{
								if($row['has_insurance_workers_compensation'] == 1){
									if($row['workers_compensation_expiration'] !== ""){
										if($wce_date <= $today){
											$incomplete = 1;
										}else{
											$complete = 1;
										}
									}else{
										$incomplete = 1;
									}
								}else{
									if($row['has_insurance_income_protection'] == 1){
										if($row['income_protection_expiration'] !== ""){
											if($ipe_date <= $today){
												$incomplete = 1;
											}else{
												$complete = 1;
											}
										}else{
											$incomplete = 1;
										}
									}else{
										$incomplete = 1;
									}
								}
							}
						}else{
							$incomplete = 1;
						}
						
					}else{
						$incomplete = 1;
					}
				}

				$admin_q = $this->admin_m->fetch_admin_defaults();
				foreach ($admin_q->result_array() as $admin_row){
					$cpo_notes_no_insurance = $admin_row['cpo_notes_no_insurance'];
					$cpo_notes_w_insurance = $admin_row['cpo_notes_w_insurance'];
				}
				
				if($row['company_type_id'] == '2'){
					if($complete == 1){
						$data['cpo_notes_insurance'] = $cpo_notes_w_insurance;
						$data['insurance_stat'] = 1;
					}else{
						$data['cpo_notes_insurance'] = $cpo_notes_no_insurance;
						$data['insurance_stat'] = 0;
					}
				}else{
					$data['cpo_notes_insurance'] = $cpo_notes_w_insurance;
					$data['insurance_stat'] = 1;
				}

			   //--- For Insurance ------
			   $address_id = $row['address_id'];
			   $comp_add_q = $this->company_m->fetch_complete_detail_address($address_id);
			   foreach ($comp_add_q->result_array() as $comp_add_row){
			   		if($comp_add_row['unit_level'] == ""){
			   			$data['comp_address_1st'] = $comp_add_row['unit_number'];
			   		}else{
			   			$data['comp_address_1st'] = $comp_add_row['unit_level']." / ".$comp_add_row['unit_number'];
			   		}
					$data['comp_address_2nd'] = $comp_add_row['street'];
					$data['comp_address_3rd'] = ucfirst(strtolower($comp_add_row['suburb']))." ".$comp_add_row['shortname']." ".$comp_add_row['postcode'];
			   }
			}


			$data['project_t'] = $proj_q;
			$data['works_t'] = $work_q;
			$data['proj_id'] = $proj_id;
			$data['work_id'] = $work_id;

			//$this->works_m->update_works_contractor_cpo($work_id,$company_client_id);

			$q_admin_defaults = $this->admin_m->fetch_admin_defaults();
			foreach ($q_admin_defaults->result_array() as $row){
				$gst_rate = $row['gst_rate'];
			}
			$data['gst_rate'] = $gst_rate;
			$percent_gst_rate = $price * ($gst_rate/100);
	    	$data['inc_gst'] = $price + $percent_gst_rate;

	    	// if($cpo_already_created == 0){
	    		$time = time();
	    		$work_company_name = str_replace(' ', '_', $work_company_name);
				$work_company_name = str_replace('/', '-', $work_company_name);
				$work_company_name = str_replace("'", "", $work_company_name);
	    		$file_name_set = $proj_id.'_'.$work_company_name.'-cpo'.'_'.$time.'.pdf';
	    		$file_type = 9;
	    		$date_upload = date('d/m/Y');
	    		$user_id = $this->session->userdata('user_id');
	    		//echo $work_company_name;
	    		$this->projects_m->insert_uploaded_file($file_name_set,$file_type,$proj_id,0,$date_upload,$user_id);
	    		$this->load->view('cpo_for_storage_pdf', $data);
	    	// }
			
		}else{
			$array = explode("-",$work_joinery_id);
			$joinery_id = $array[1];
			$this->works_m->create_joinery_cpo_date($joinery_id);

			$work_q = $this->works_m->display_selected_works_joinery($joinery_id);
			foreach ($work_q->result_array() as $row){
			   $is_deliver_office = $row['is_deliver_office'];
			   $company_client_id = $row['work_joinery_contractor_id'];
			   $price = $row['wj_price'];
			   $data['price'] = $price;
			   $goods_deliver_by_date = $row['goods_deliver_by_date'];
			   if($goods_deliver_by_date == "" ){
			   		$data['goods_deliver_by_date'] = "";	
			   }else{
			   		$data['goods_deliver_by_date'] = $row['goods_deliver_by_date'];
			   }

			   $note_id = $row['wj_note_id'];
			   $note_q = $this->company_m->fetch_notes($note_id);
			   foreach ($note_q->result_array() as $note_row){
			   		$data['notes'] = $note_row['notes'];
			   }
			   
			   $data['work_desc'] = $row['joinery_name'];
			   $is_pending = 0;
			   $work_sel_cont_q = $this->works_m->display_works_selected_contractor($is_pending,$work_joinery_id,$company_client_id);
			   foreach ($work_sel_cont_q->result_array() as $work_cont_row){
			   		$cpo_created = $work_cont_row['cpo_created'];
			   		if($cpo_created == 1){
			   			$cpo_already_created = 1;
			   		}
			   		$work_cont_contact_person_id = $work_cont_row['contact_person_id'];
			   		$work_cont_person_q = $this->company_m->fetch_all_contact_persons($work_cont_contact_person_id);
					foreach ($work_cont_person_q->result_array() as $work_cont_person_row){
					   $data['attention'] = $work_cont_person_row['first_name']." ".$work_cont_person_row['last_name'];
					   $contact_number_id = $work_cont_person_row['contact_number_id'];
					   $phon_q = $this->company_m->fetch_phone($contact_number_id);
					   foreach ($phon_q->result_array() as $phone_row){
					   		if($phone_row['office_number'] == ""):
					   			$data['office_number'] = "";
					   		else:
					   			$data['office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					   		endif;
					   }
					}
			   }
			}

			$proj_q = $this->projects_m->select_particular_project($proj_id);
			foreach ($proj_q->result_array() as $row){
			   	$primary_contact_person_id = $row['primary_contact_person_id'];
			   	$tenancyno = $row['shop_tenancy_number'].' '.$row['shop_name'];
			   	$client_id = $row['client_id'];
				$job_type = $row['job_type'];
				$data['start_date'] = $row['date_site_commencement'];
				$data['end_date'] = $row['date_site_finish'];
				
				$data['project_total'] = $row['project_total'];

				$project_manager_id = $row['project_manager_id'];
				$users_q = $this->user_model->fetch_user($project_manager_id);
				foreach ($users_q->result_array() as $users_row){
					$data['contact_person'] = $users_row['user_first_name']." ".$users_row['user_last_name'];
					$user_email_id = $users_row['user_email_id'];
					$email_q = $this->company_m->fetch_email($user_email_id);
					foreach ($email_q->result_array() as $email_row){
					   $data['email'] = $email_row['general_email'];
					}

				}

				$focus_company_id = $row['focus_company_id'];
				$focus_comp_q = $this->admin_m->fetch_single_company_focus($focus_company_id);
				foreach ($focus_comp_q->result_array() as $focus_comp_row){
					$data['focus_logo'] = $focus_comp_row['logo'];
					$data['focus_comp'] = $focus_comp_row['company_name'];
					$officnumber = $focus_comp_row['office_number'];
					$prefix = substr($officnumber, 0, 4);

					if($prefix == '1300' || $prefix == '1800'){
						$data['office_no'] = $focus_comp_row['office_number'];
					}else{
						$data['office_no'] = $focus_comp_row['area_code']." ".$focus_comp_row['office_number'];
					}
					$data['acn'] = $focus_comp_row['acn'];
					$data['abn'] = $focus_comp_row['abn'];
					$data['focus_email'] = $focus_comp_row['general_email'];
					$address_id = $focus_comp_row['address_id'];
					$physical_address = $focus_comp_row['address_id'];
					$focus_comp_q = $this->company_m->fetch_complete_detail_address($address_id);
					foreach ($focus_comp_q->result_array() as $comp_address_row){
						$po_box = $comp_address_row['po_box'];
						if($po_box == ""){
							$data['po_box'] = "";
						}else{
							$data['po_box'] = "PO ".$comp_address_row['po_box'];
						}
						$data['focus_suburb'] = ucwords(strtolower($comp_address_row['suburb']))." ".$comp_address_row['shortname']." ".$comp_address_row['postcode'];
					}
				}

				if($is_deliver_office == 0){
					$site_address_id= $row['site_add'];
				}else{
					$site_address_id= $physical_address;
				}

				if($job_type == "Shopping Center"){
			   		$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
					foreach ($site_address_q->result_array() as $client_add_row){
						$data['site_address_1st'] = $tenancyno;

						if($client_add_row['unit_level'] == ""){
							$data['site_address_2nd'] = $client_add_row['unit_number'].' '.$client_add_row['street'];
						}else{
							$data['site_address_2nd'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'].' '.$client_add_row['street'];
						}

						$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
					}	
			   	}else{
					$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
					foreach ($site_address_q->result_array() as $client_add_row){
						if($client_add_row['unit_level'] == ""){
							$data['site_address_1st'] = $client_add_row['unit_number'];
						}else{
							$data['site_address_1st'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'];
						}
						$data['site_address_2nd'] = $client_add_row['street'];
						$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
					}
			   	}
			   	
			}


			$comp_q = $this->company_m->fetch_all_contact_persons($primary_contact_person_id);
			foreach ($comp_q->result_array() as $row){
				$comp_contact_number_id = $row['contact_number_id'];
				$comp_phone_q = $this->company_m->fetch_phone($comp_contact_number_id);
				foreach ($comp_phone_q->result_array() as $phone_row){
					if($phone_row['office_number'] == ""):
						$data['comp_office_number'] = "";
					else:
						$data['comp_office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					endif;
					
				}
			}
			$work_company_name = "";
			$comp_det_q = $this->company_m->display_company_detail_by_id($company_client_id);
			foreach ($comp_det_q->result_array() as $row){
			   $data['work_company_name'] = $row['company_name'];
			   $work_company_name = $row['company_name'];
			   //--- For Insurance ------
			   if($row['public_liability_expiration'] !== ""){
					$ple_raw_data = $row['public_liability_expiration'];
					$ple_arr =  explode('/',$ple_raw_data);
					$ple_day = $ple_arr[0];
					$ple_month = $ple_arr[1];
					$ple_year = $ple_arr[2];
					$ple_date = $ple_year.'-'.$ple_month.'-'.$ple_day;
				}

				if($row['workers_compensation_expiration'] !== ""){
					$wce_raw_data = $row['workers_compensation_expiration'];
					$wce_arr =  explode('/',$wce_raw_data);
					$wce_day = $wce_arr[0]; 
					$wce_month = $wce_arr[1];
					$wce_year = $wce_arr[2];
					$wce_date = $wce_year.'-'.$wce_month.'-'.$wce_day;
				}

				if($row['income_protection_expiration'] !== ""){
					$ipe_raw_data = $row['income_protection_expiration'];
					$ipe_arr =  explode('/',$ipe_raw_data);
					$ipe_day = $ipe_arr[0];
					$ipe_month = $ipe_arr[1];
					$ipeyear = $ipe_arr[2];
					$ipe_date = $ipe_year.'-'.$ipe_month.'-'.$ipe_day;
				}
				$today = date('Y-m-d');
				
				$complete = 0;
				$incomplete = 0;
				
				if($row['company_type_id'] == '2'){
					if($row['has_insurance_public_liability'] == 1){
						if($row['public_liability_expiration'] !== ""){
							if($ple_date <= $today){
								$incomplete = 1;
							}else{
								if($row['has_insurance_workers_compensation'] == 1){
									if($row['workers_compensation_expiration'] !== ""){
										if($wce_date <= $today){
											$incomplete = 1;
										}else{
											$complete = 1;
										}
									}else{
										$incomplete = 1;
									}
								}else{
									if($row['has_insurance_income_protection'] == 1){
										if($row['income_protection_expiration'] !== ""){
											if($ipe_date <= $today){
												$incomplete = 1;
											}else{
												$complete = 1;
											}
										}else{
											$incomplete = 1;
										}
									}else{
										$incomplete = 1;
									}
								}
							}
						}else{
							$incomplete = 1;
						}
						
					}else{
						$incomplete = 1;
					}
				}

				$admin_q = $this->admin_m->fetch_admin_defaults();
				foreach ($admin_q->result_array() as $admin_row){
					$cpo_notes_no_insurance = $admin_row['cpo_notes_no_insurance'];
					$cpo_notes_w_insurance = $admin_row['cpo_notes_w_insurance'];
				}
				
				if($row['company_type_id'] == '2'){
					if($complete == 1){
						$data['cpo_notes_insurance'] = $cpo_notes_w_insurance;
						$data['insurance_stat'] = 1;
					}else{
						$data['cpo_notes_insurance'] = $cpo_notes_no_insurance;
						$data['insurance_stat'] = 0;
					}
				}else{
					$data['cpo_notes_insurance'] = $cpo_notes_w_insurance;
					$data['insurance_stat'] = 1;
				}

			   //--- For Insurance ------
			   $address_id = $row['address_id'];
			   $comp_add_q = $this->company_m->fetch_complete_detail_address($address_id);
			   foreach ($comp_add_q->result_array() as $comp_add_row){
			   		if($comp_add_row['unit_level'] == ""){
			   			$data['comp_address_1st'] = $comp_add_row['unit_number'];
			   		}else{
			   			$data['comp_address_1st'] = $comp_add_row['unit_level']." / ".$comp_add_row['unit_number'];
			   		}
					$data['comp_address_2nd'] = $comp_add_row['street'];
					$data['comp_address_3rd'] = ucfirst(strtolower($comp_add_row['suburb']))." ".$comp_add_row['shortname']." ".$comp_add_row['postcode'];
			   }
			}


			$data['project_t'] = $proj_q;
			$data['works_t'] = $work_q;
			$data['proj_id'] = $proj_id;
			$data['work_id'] = $work_joinery_id;

			//$this->works_m->update_works_contractor_cpo($work_joinery_id,$company_client_id);

			$q_admin_defaults = $this->admin_m->fetch_admin_defaults();
			foreach ($q_admin_defaults->result_array() as $row){
				$gst_rate = $row['gst_rate'];
			}
			$data['gst_rate'] = $gst_rate;
			$percent_gst_rate = $price * ($gst_rate/100);
	    	$data['inc_gst'] = $price + $percent_gst_rate;
	    	// if($cpo_already_created == 0){
	    		$time = time();
	    		$work_company_name = str_replace(' ', '_', $work_company_name);
				$work_company_name = str_replace('/', '-', $work_company_name);
				$work_company_name = str_replace("'", "", $work_company_name);
	    		$file_name_set = $proj_id.'_'.$work_company_name.'-cpo'.'_'.$time.'.pdf';
	    		$file_type = 9;
	    		$date_upload = date('d/m/Y');
	    		
	    		$user_id = $this->session->userdata('user_id');
	    		$this->projects_m->insert_uploaded_file($file_name_set,$file_type,$proj_id,$date_upload,0,$user_id);
	    		
	    		$this->load->view('cpo_for_storage_pdf', $data);
	    	// }
			
		}
	
	}
	
	function work_contractor_po_nodisplay(){
		$proj_id = $_GET['project_id'];
		$work_id_joinery_id = $_GET['work_id'];
		$array = explode("-",$work_id_joinery_id);
		$array_size = sizeof($array);
		$work_id = $array[0];
		$work_joinery_id = "";
		if($array_size > 1){
			$work_joinery_id = $array[1];	
		}
		

		if($work_joinery_id == ""){
			$this->works_m->create_cpo_date($work_id);

			$work_q = $this->works_m->display_works_selected($work_id);
			foreach ($work_q->result_array() as $row){
			   $is_deliver_office = $row['is_deliver_office'];
			   $company_client_id = $row['company_client_id'];
			   $price = $row['price'];
			   $data['price'] = $price;
			   $contractor_type = $row['contractor_type'];
			   $goods_deliver_by_date = $row['goods_deliver_by_date'];
			   if($goods_deliver_by_date == "" ){
			   		$data['goods_deliver_by_date'] = "";	
			   }else{
			   		$data['goods_deliver_by_date'] = $row['goods_deliver_by_date'];
			   }

			   $note_id = $row['note_id'];
			   $note_q = $this->company_m->fetch_notes($note_id);
			   foreach ($note_q->result_array() as $note_row){
			   		$data['notes'] = str_replace("&apos;","'",$note_row['notes']);
			   }
			   if($contractor_type == 2){
			   		if($row['work_con_sup_id'] == 82){
			   			$data['work_desc'] = str_replace("&apos;","'",$row['other_work_desc']);

			   		}else{
			   			$data['work_desc'] = str_replace("&apos;","'",$row['job_sub_cat']);
			   		}
			   }else{
			   		$data['work_desc'] = str_replace("&apos;","'",$row['supplier_cat_name']);
			   }
			   $is_pending = 0;
			   $work_sel_cont_q = $this->works_m->display_works_selected_contractor($is_pending,$work_id,$company_client_id);
			   foreach ($work_sel_cont_q->result_array() as $work_cont_row){
			   		$work_cont_contact_person_id = $work_cont_row['contact_person_id'];
			   		$work_cont_person_q = $this->company_m->fetch_all_contact_persons($work_cont_contact_person_id);
					foreach ($work_cont_person_q->result_array() as $work_cont_person_row){
					   $data['attention'] = $work_cont_person_row['first_name']." ".$work_cont_person_row['last_name'];
					   $contact_number_id = $work_cont_person_row['contact_number_id'];
					   $phon_q = $this->company_m->fetch_phone($contact_number_id);
					   foreach ($phon_q->result_array() as $phone_row){
					   		if($phone_row['office_number'] == ""):
					   			$data['office_number'] = "";
					   		else:
					   			$data['office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					   		endif;
					   }
					}
			   }
			}
			$proj_q = $this->projects_m->select_particular_project($proj_id);
			foreach ($proj_q->result_array() as $row){
			   	$primary_contact_person_id = $row['primary_contact_person_id'];
			   	$tenancyno = $row['shop_tenancy_number'].' '.$row['shop_name'];
			   	$client_id = $row['client_id'];
				$job_type = $row['job_type'];
				$data['start_date'] = $row['date_site_commencement'];
				$data['end_date'] = $row['date_site_finish'];
				
				$data['project_total'] = $row['project_total'];

				$project_manager_id = $row['project_manager_id'];
				$users_q = $this->user_model->fetch_user($project_manager_id);
				foreach ($users_q->result_array() as $users_row){
					$data['contact_person'] = $users_row['user_first_name']." ".$users_row['user_last_name'];
					$user_email_id = $users_row['user_email_id'];
					$email_q = $this->company_m->fetch_email($user_email_id);
					foreach ($email_q->result_array() as $email_row){
					   $data['email'] = $email_row['general_email'];
					}

				}

				$focus_company_id = $row['focus_company_id'];
				$focus_comp_q = $this->admin_m->fetch_single_company_focus($focus_company_id);
				foreach ($focus_comp_q->result_array() as $focus_comp_row){
					$data['focus_logo'] = $focus_comp_row['logo'];
					$data['focus_comp'] = $focus_comp_row['company_name'];
					$officnumber = $focus_comp_row['office_number'];
					$prefix = substr($officnumber, 0, 4);

					if($prefix == '1300' || $prefix == '1800'){
						$data['office_no'] = $focus_comp_row['office_number'];
					}else{
						$data['office_no'] = $focus_comp_row['area_code']." ".$focus_comp_row['office_number'];
					}
					$data['acn'] = $focus_comp_row['acn'];
					$data['abn'] = $focus_comp_row['abn'];
					$data['focus_email'] = $focus_comp_row['general_email'];
					$address_id = $focus_comp_row['postal_address_id'];
					$physicaladd_id = $focus_comp_row['address_id'];
					$focus_comp_q = $this->company_m->fetch_complete_detail_address($address_id);
					foreach ($focus_comp_q->result_array() as $comp_address_row){
						$po_box = $comp_address_row['po_box'];
						if($po_box == ""){
							$data['po_box'] = "";
						}else{
							$data['po_box'] = "PO ".$comp_address_row['po_box'];
						}
						$data['focus_suburb'] = ucwords(strtolower($comp_address_row['suburb']))." ".$comp_address_row['shortname']." ".$comp_address_row['postcode'];
					}
				}

				if($is_deliver_office == 0){
					$site_address_id= $row['site_add'];
				}else{
					$site_address_id= $physicaladd_id;
				}

				if($job_type == "Shopping Center"){
			   		$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
					foreach ($site_address_q->result_array() as $client_add_row){
						$data['site_address_1st'] = $tenancyno;

						if($client_add_row['unit_level'] == ""){
							$data['site_address_2nd'] = $client_add_row['unit_number'].' '.$client_add_row['street'];
						}else{
							$data['site_address_2nd'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'].' '.$client_add_row['street'];
						}

						$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
					}	
			   	}else{
					$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
					foreach ($site_address_q->result_array() as $client_add_row){
						if($client_add_row['unit_level'] == ""){
							$data['site_address_1st'] = $client_add_row['unit_number'];
						}else{
							$data['site_address_1st'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'];
						}
						$data['site_address_2nd'] = $client_add_row['street'];
						$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
					}
			   	}
			   	
			}


			$comp_q = $this->company_m->fetch_all_contact_persons($primary_contact_person_id);
			foreach ($comp_q->result_array() as $row){
				//$data['contact_person'] = $row['first_name']." ".$row['last_name'];
				//$email_id = $row['email_id'];
				$comp_contact_number_id = $row['contact_number_id'];
				$comp_phone_q = $this->company_m->fetch_phone($comp_contact_number_id);
				foreach ($comp_phone_q->result_array() as $phone_row){
					if($phone_row['office_number'] == ""):
						$data['comp_office_number'] = "";
					else:
						$data['comp_office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					endif;
					
				}
			}
			//$email_q = $this->company_m->fetch_email($email_id);
			//foreach ($email_q->result_array() as $row){
			 //  $data['email'] = $row['general_email'];
			//}
			

			$comp_det_q = $this->company_m->display_company_detail_by_id($company_client_id);
			foreach ($comp_det_q->result_array() as $row){
			   $data['work_company_name'] = $row['company_name'];
			   //--- For Insurance ------
			   if($row['public_liability_expiration'] !== ""){
					$ple_raw_data = $row['public_liability_expiration'];
					$ple_arr =  explode('/',$ple_raw_data);
					$ple_day = $ple_arr[0];
					$ple_month = $ple_arr[1];
					$ple_year = $ple_arr[2];
					$ple_date = $ple_year.'-'.$ple_month.'-'.$ple_day;
				}

				if($row['workers_compensation_expiration'] !== ""){
					$wce_raw_data = $row['workers_compensation_expiration'];
					$wce_arr =  explode('/',$wce_raw_data);
					$wce_day = $wce_arr[0];
					$wce_month = $wce_arr[1];
					$wce_year = $wce_arr[2];
					$wce_date = $wce_year.'-'.$wce_month.'-'.$wce_day;
				}

				if($row['income_protection_expiration'] !== ""){
					$ipe_raw_data = $row['income_protection_expiration'];
					$ipe_arr =  explode('/',$ipe_raw_data);
					$ipe_day = $ipe_arr[0];
					$ipe_month = $ipe_arr[1];
					$ipeyear = $ipe_arr[2];
					$ipe_date = $ipe_year.'-'.$ipe_month.'-'.$ipe_day;
				}
				$today = date('Y-m-d');
				
				$complete = 0;
				$incomplete = 0;
				
				if($row['company_type_id'] == '2'){
					if($row['has_insurance_public_liability'] == 1){
						if($row['public_liability_expiration'] !== ""){
							if($ple_date <= $today){
								$incomplete = 1;
							}else{
								if($row['has_insurance_workers_compensation'] == 1){
									if($row['workers_compensation_expiration'] !== ""){
										if($wce_date <= $today){
											$incomplete = 1;
										}else{
											$complete = 1;
										}
									}else{
										$incomplete = 1;
									}
								}else{
									if($row['has_insurance_income_protection'] == 1){
										if($row['income_protection_expiration'] !== ""){
											if($ipe_date <= $today){
												$incomplete = 1;
											}else{
												$complete = 1;
											}
										}else{
											$incomplete = 1;
										}
									}else{
										$incomplete = 1;
									}
								}
							}
						}else{
							$incomplete = 1;
						}
						
					}else{
						$incomplete = 1;
					}
				}

				$admin_q = $this->admin_m->fetch_admin_defaults();
				foreach ($admin_q->result_array() as $admin_row){
					$cpo_notes_no_insurance = $admin_row['cpo_notes_no_insurance'];
					$cpo_notes_w_insurance = $admin_row['cpo_notes_w_insurance'];
				}
				
				
				if($row['company_type_id'] == '2'){

					if($complete == 1){
						$data['cpo_notes_insurance'] = $cpo_notes_w_insurance;
						$data['insurance_stat'] = 1;
					}else{
						$data['cpo_notes_insurance'] = $cpo_notes_no_insurance;
						$data['insurance_stat'] = 0;
					}
				}else{
					$data['cpo_notes_insurance'] = $cpo_notes_w_insurance;
					$data['insurance_stat'] = 1;
				}

			   //--- For Insurance ------
			   $address_id = $row['address_id'];
			   $comp_add_q = $this->company_m->fetch_complete_detail_address($address_id);
			   foreach ($comp_add_q->result_array() as $comp_add_row){
			   		if($comp_add_row['unit_level'] == ""){
			   			$data['comp_address_1st'] = $comp_add_row['unit_number'];
			   		}else{
			   			$data['comp_address_1st'] = $comp_add_row['unit_level']." / ".$comp_add_row['unit_number'];
			   		}
					$data['comp_address_2nd'] = $comp_add_row['street'];
					$data['comp_address_3rd'] = ucfirst(strtolower($comp_add_row['suburb']))." ".$comp_add_row['shortname']." ".$comp_add_row['postcode'];
			   }
			}


			$data['project_t'] = $proj_q;
			$data['works_t'] = $work_q;
			$data['proj_id'] = $proj_id;
			$data['work_id'] = $work_id;

			$this->works_m->update_works_contractor_cpo($work_id,$company_client_id);

			$q_admin_defaults = $this->admin_m->fetch_admin_defaults();
			foreach ($q_admin_defaults->result_array() as $row){
				$gst_rate = $row['gst_rate'];
			}
			$data['gst_rate'] = $gst_rate;
			$percent_gst_rate = $price * ($gst_rate/100);
	    	$data['inc_gst'] = $price + $percent_gst_rate;
			$this->load->view('contractor_purchase_order_nodisplay', $data);
		}else{
			$this->works_m->create_cpo_date($work_id);
			//$array = explode("-",$work_joinery_id);
			$joinery_id = $work_joinery_id;
			$this->works_m->create_joinery_cpo_date($joinery_id);

			$work_q = $this->works_m->display_selected_works_joinery($joinery_id);
			foreach ($work_q->result_array() as $row){
			   $is_deliver_office = $row['is_deliver_office'];
			   $company_client_id = $row['work_joinery_contractor_id'];
			   $price = $row['wj_price'];
			   $data['price'] = $price;
			   //$contractor_type = $row['contractor_type'];
			   $goods_deliver_by_date = $row['goods_deliver_by_date'];
			   if($goods_deliver_by_date == "" ){
			   		$data['goods_deliver_by_date'] = "";	
			   }else{
			   		$data['goods_deliver_by_date'] = $row['goods_deliver_by_date'];
			   }

			   $note_id = $row['wj_note_id'];
			   $note_q = $this->company_m->fetch_notes($note_id);
			   foreach ($note_q->result_array() as $note_row){
			   		$data['notes'] = $note_row['notes'];
			   }
			   
			   $data['work_desc'] = $row['joinery_name'];
			   
			   $is_pending = 0;
			   $work_sel_cont_q = $this->works_m->display_works_selected_contractor($is_pending,$work_joinery_id,$company_client_id);
			   foreach ($work_sel_cont_q->result_array() as $work_cont_row){
			   		$work_cont_contact_person_id = $work_cont_row['contact_person_id'];
			   		$work_cont_person_q = $this->company_m->fetch_all_contact_persons($work_cont_contact_person_id);
					foreach ($work_cont_person_q->result_array() as $work_cont_person_row){
					   $data['attention'] = $work_cont_person_row['first_name']." ".$work_cont_person_row['last_name'];
					   $contact_number_id = $work_cont_person_row['contact_number_id'];
					   $phon_q = $this->company_m->fetch_phone($contact_number_id);
					   foreach ($phon_q->result_array() as $phone_row){
					   		if($phone_row['office_number'] == ""):
					   			$data['office_number'] = "";
					   		else:
					   			$data['office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					   		endif;
					   }
					}
			   }
			}

			$proj_q = $this->projects_m->select_particular_project($proj_id);
			foreach ($proj_q->result_array() as $row){
			   	$primary_contact_person_id = $row['primary_contact_person_id'];
			   	$tenancyno = $row['shop_tenancy_number'].' '.$row['shop_name'];
			   	$client_id = $row['client_id'];
				$job_type = $row['job_type'];
				$data['start_date'] = $row['date_site_commencement'];
				$data['end_date'] = $row['date_site_finish'];
				
				$data['project_total'] = $row['project_total'];

				$project_manager_id = $row['project_manager_id'];
				$users_q = $this->user_model->fetch_user($project_manager_id);
				foreach ($users_q->result_array() as $users_row){
					$data['contact_person'] = $users_row['user_first_name']." ".$users_row['user_last_name'];
					$user_email_id = $users_row['user_email_id'];
					$email_q = $this->company_m->fetch_email($user_email_id);
					foreach ($email_q->result_array() as $email_row){
					   $data['email'] = $email_row['general_email'];
					}

				}

				$focus_company_id = $row['focus_company_id'];
				$focus_comp_q = $this->admin_m->fetch_single_company_focus($focus_company_id);
				foreach ($focus_comp_q->result_array() as $focus_comp_row){
					$data['focus_logo'] = $focus_comp_row['logo'];
					$data['focus_comp'] = $focus_comp_row['company_name'];
					$officnumber = $focus_comp_row['office_number'];
					$prefix = substr($officnumber, 0, 4);

					if($prefix == '1300' || $prefix == '1800'){
						$data['office_no'] = $focus_comp_row['office_number'];
					}else{
						$data['office_no'] = $focus_comp_row['area_code']." ".$focus_comp_row['office_number'];
					}
					$data['acn'] = $focus_comp_row['acn'];
					$data['abn'] = $focus_comp_row['abn'];
					$data['focus_email'] = $focus_comp_row['general_email'];
					$address_id = $focus_comp_row['postal_address_id'];
					$physicaladd_id = $focus_comp_row['address_id'];
					$focus_comp_q = $this->company_m->fetch_complete_detail_address($address_id);
					foreach ($focus_comp_q->result_array() as $comp_address_row){
						$po_box = $comp_address_row['po_box'];
						if($po_box == ""){
							$data['po_box'] = "";
						}else{
							$data['po_box'] = "PO ".$comp_address_row['po_box'];
						}
						$data['focus_suburb'] = ucwords(strtolower($comp_address_row['suburb']))." ".$comp_address_row['shortname']." ".$comp_address_row['postcode'];
					}
				}

				if($is_deliver_office == 0){
					$site_address_id= $row['site_add'];
				}else{
					$site_address_id= $physicaladd_id;
				}

				if($job_type == "Shopping Center"){
			   		$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
					foreach ($site_address_q->result_array() as $client_add_row){
						$data['site_address_1st'] = $tenancyno;

						if($client_add_row['unit_level'] == ""){
							$data['site_address_2nd'] = $client_add_row['unit_number'].' '.$client_add_row['street'];
						}else{
							$data['site_address_2nd'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'].' '.$client_add_row['street'];
						}

						$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
					}	
			   	}else{
					$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
					foreach ($site_address_q->result_array() as $client_add_row){
						if($client_add_row['unit_level'] == ""){
							$data['site_address_1st'] = $client_add_row['unit_number'];
						}else{
							$data['site_address_1st'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'];
						}
						$data['site_address_2nd'] = $client_add_row['street'];
						$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
					}
			   	}
			   	
			}


			$comp_q = $this->company_m->fetch_all_contact_persons($primary_contact_person_id);
			foreach ($comp_q->result_array() as $row){
				//$data['contact_person'] = $row['first_name']." ".$row['last_name'];
				//$email_id = $row['email_id'];
				$comp_contact_number_id = $row['contact_number_id'];
				$comp_phone_q = $this->company_m->fetch_phone($comp_contact_number_id);
				foreach ($comp_phone_q->result_array() as $phone_row){
					if($phone_row['office_number'] == ""):
						$data['comp_office_number'] = "";
					else:
						$data['comp_office_number'] = "(".$phone_row['area_code'].") ".$phone_row['office_number'];
					endif;
				}
			}
			//$email_q = $this->company_m->fetch_email($email_id);
			//foreach ($email_q->result_array() as $row){
			 //  $data['email'] = $row['general_email'];
			//}
			
			
			$comp_det_q = $this->company_m->display_company_detail_by_id($company_client_id);
			foreach ($comp_det_q->result_array() as $row){
			   $data['work_company_name'] = $row['company_name'];
			   //--- For Insurance ------
			   if($row['public_liability_expiration'] !== ""){
					$ple_raw_data = $row['public_liability_expiration'];
					$ple_arr =  explode('/',$ple_raw_data);
					$ple_day = $ple_arr[0];
					$ple_month = $ple_arr[1];
					$ple_year = $ple_arr[2];
					$ple_date = $ple_year.'-'.$ple_month.'-'.$ple_day;
				}

				if($row['workers_compensation_expiration'] !== ""){
					$wce_raw_data = $row['workers_compensation_expiration'];
					$wce_arr =  explode('/',$wce_raw_data);
					$wce_day = $wce_arr[0];
					$wce_month = $wce_arr[1];
					$wce_year = $wce_arr[2];
					$wce_date = $wce_year.'-'.$wce_month.'-'.$wce_day;
				}

				if($row['income_protection_expiration'] !== ""){
					$ipe_raw_data = $row['income_protection_expiration'];
					$ipe_arr =  explode('/',$ipe_raw_data);
					$ipe_day = $ipe_arr[0];
					$ipe_month = $ipe_arr[1];
					$ipeyear = $ipe_arr[2];
					$ipe_date = $ipe_year.'-'.$ipe_month.'-'.$ipe_day;
				}
				$today = date('Y-m-d');
				
				$complete = 0;
				$incomplete = 0;
				
				if($row['company_type_id'] == '2'){
					if($row['has_insurance_public_liability'] == 1){
						if($row['public_liability_expiration'] !== ""){
							if($ple_date <= $today){
								$incomplete = 1;
							}else{
								if($row['has_insurance_workers_compensation'] == 1){
									if($row['workers_compensation_expiration'] !== ""){
										if($wce_date <= $today){
											$incomplete = 1;
										}else{
											$complete = 1;
										}
									}else{
										$incomplete = 1;
									}
								}else{
									if($row['has_insurance_income_protection'] == 1){
										if($row['income_protection_expiration'] !== ""){
											if($ipe_date <= $today){
												$incomplete = 1;
											}else{
												$complete = 1;
											}
										}else{
											$incomplete = 1;
										}
									}else{
										$incomplete = 1;
									}
								}
							}
						}else{
							$incomplete = 1;
						}
						
					}else{
						$incomplete = 1;
					}
				}

				$admin_q = $this->admin_m->fetch_admin_defaults();
				foreach ($admin_q->result_array() as $admin_row){
					$cpo_notes_no_insurance = $admin_row['cpo_notes_no_insurance'];
					$cpo_notes_w_insurance = $admin_row['cpo_notes_w_insurance'];
				}
				
				if($row['company_type_id'] == '2'){
					if($complete == 1){
						$data['cpo_notes_insurance'] = $cpo_notes_w_insurance;
						$data['insurance_stat'] = 1;
					}else{
						$data['cpo_notes_insurance'] = $cpo_notes_no_insurance;
						$data['insurance_stat'] = 0;
					}
				}else{
					$data['cpo_notes_insurance'] = $cpo_notes_w_insurance;
					$data['insurance_stat'] = 1;
				}

			   //--- For Insurance ------
			   $address_id = $row['address_id'];
			   $comp_add_q = $this->company_m->fetch_complete_detail_address($address_id);
			   foreach ($comp_add_q->result_array() as $comp_add_row){
			   		if($comp_add_row['unit_level'] == ""){
			   			$data['comp_address_1st'] = $comp_add_row['unit_number'];
			   		}else{
			   			$data['comp_address_1st'] = $comp_add_row['unit_level']." / ".$comp_add_row['unit_number'];
			   		}
					$data['comp_address_2nd'] = $comp_add_row['street'];
					$data['comp_address_3rd'] = ucfirst(strtolower($comp_add_row['suburb']))." ".$comp_add_row['shortname']." ".$comp_add_row['postcode'];
			   }
			}


			$data['project_t'] = $proj_q;
			$data['works_t'] = $work_q;
			$data['proj_id'] = $proj_id;
			$data['work_id'] = $work_id_joinery_id;

			$this->works_m->update_works_contractor_cpo($work_id_joinery_id,$company_client_id);

			$q_admin_defaults = $this->admin_m->fetch_admin_defaults();
			foreach ($q_admin_defaults->result_array() as $row){
				$gst_rate = $row['gst_rate'];
			}
			$data['gst_rate'] = $gst_rate;
			$percent_gst_rate = $price * ($gst_rate/100);
	    	$data['inc_gst'] = $price + $percent_gst_rate;
			$this->load->view('contractor_purchase_order_nodisplay', $data);
		}
	}
	function select_work_contractor(){
		$works_contrator_id = $_POST['work_contractor_id'];
		$q_selected_contractor = $this->works_m->display_selected_contractor($works_contrator_id);
		foreach ($q_selected_contractor->result_array() as $row){
			$date_added = $row['date_added'];
			$company_id = $row['company_id'];
			$work_id = $row['works_id'];
			$q_selected_company = $this->company_m->fetch_all_company($company_id);	
			foreach ($q_selected_company->result_array() as $comp_row){
				$company_name = $comp_row['company_name'];
			}
			$contact_person_id = $row['contact_person_id'];
			$ex_gst = $row['ex_gst'];
			$inc_gst = $row['inc_gst'];
			$is_selected = $row['is_selected'];
			$contractor_notes = $row['contractor_notes'];

		}

		$work_q = $this->works_m->display_works_selected($work_id);
		foreach ($work_q->result_array() as $row){
			$is_reconciled = $row['is_reconciled'];
		}

		echo $date_added."|".ucwords(strtolower($company_name))."|".$company_id."|".$ex_gst."|".$inc_gst."|".$is_selected."|".$is_reconciled."|".$contact_person_id."|".$contractor_notes;

	}
	function job_date_entered(){
		$proj_id = $_POST['proj_id'];
		$job_date = "";
		$proj_q = $this->projects_m->select_particular_project($proj_id);
		foreach ($proj_q->result_array() as $row){
			$job_date = $row['job_date'];
		}
		echo $job_date;
	}
	function display_work_contractor(){
		// $jobdate_disabled = $_POST['jobdate_disabled'];

		// $data['jobdate_disabled'] = $jobdate_disabled;
		
		$work_id = $_POST['work_id'];
	
		$works_t = $this->works_m->display_works_selected($work_id);
		foreach ($works_t->result_array() as $row){
			$cont_type = $row['contractor_type'];
			$data['work_estimate'] = $row['work_estimate'];
			$data['is_variation'] = $row['is_variation'];
			$data['is_reconciled'] = $row['is_reconciled'];
		}

		if($cont_type == "Contractor"){
			$cont_type = 2;
		}else{
			$cont_type = 3;
		}

		$proj_id = $_POST['proj_id'];
		$proj_q = $this->projects_m->select_particular_project($proj_id);
		foreach ($proj_q->result_array() as $row){
			$data['job_date'] = $row['job_date'];
		}

		$works_contructors_t = $this->works_m->display_work_contructor($work_id);
		$num_rows = $works_contructors_t->num_rows;

		$data['works_contructors_t'] = $this->works_m->display_work_contructor($work_id);
	
		$this->load->view("work_contractor_t",$data);
	}

	function display_var_work_contractor(){
		$work_id = $_POST['work_id'];
		$acceptance_date = "";
		if(isset($_POST['var_acceptance_date'])){
			$acceptance_date = $_POST['var_acceptance_date'];
		}
		$data['acceptance_date'] = $acceptance_date;
		$works_t = $this->works_m->display_works_selected($work_id);
		foreach ($works_t->result_array() as $row){
			$cont_type = $row['contractor_type'];
			$data['work_estimate'] = $row['work_estimate'];
			$data['is_variation'] = $row['is_variation'];
		}

		if($cont_type == "Contractor"){
			$cont_type = 2;
		}else{
			$cont_type = 3;
		}

		$proj_id = $_POST['proj_id'];
		$proj_q = $this->projects_m->select_particular_project($proj_id);
		foreach ($proj_q->result_array() as $row){
			$data['job_date'] = $row['job_date'];
		}

		$works_contructors_t = $this->works_m->display_work_contructor($work_id);
		$num_rows = $works_contructors_t->num_rows;

		$data['works_contructors_t'] = $this->works_m->display_work_contructor($work_id);
	
		$this->load->view("var_work_contractor",$data);
	}

	function insert_contractor(){
		$work_id = $_POST['work_id'];
	    $date_added = $_POST['date_added'];
	    $comp_id = $_POST['comp_id'];
	    $contact_person_id = $_POST['contact_person_id'];
		$works_cont_t = $this->works_m->insert_works_contractor($work_id,$date_added,$comp_id,$contact_person_id);
	    $works_t = $this->works_m->display_works_selected($work_id);
		foreach ($works_t->result_array() as $row){
			$cont_type = $row['contractor_type'];
			$data['work_estimate'] = $row['work_estimate'];
			$data['is_variation'] = $row['is_variation'];
			$data['is_reconciled'] = $row['is_reconciled'];
		}
		if($cont_type == "Contractor"){
			$cont_type = 2;
		}else{
			$cont_type = 3;
		}


		$proj_id = $_POST['proj_id'];
		$proj_q = $this->projects_m->select_particular_project($proj_id);
		foreach ($proj_q->result_array() as $row){
			$data['job_date'] = $row['job_date'];
		}

		// $acceptance_date = "";
		// if(isset($_POST['var_acceptance_date'])){
		// 	$acceptance_date = $_POST['var_acceptance_date'];
		// }
		// $data['acceptance_date'] = $acceptance_date;


		$data['works_contructors_t'] = $this->works_m->display_work_contructor($work_id);
		$this->load->view("work_contractor_t",$data);
	}

	function var_insert_contractor(){
		$work_id = $_POST['work_id'];
	    $date_added = $_POST['date_added'];
	    $comp_id = $_POST['comp_id'];
	    $contact_person_id = $_POST['contact_person_id'];
		$works_cont_t = $this->works_m->insert_works_contractor($work_id,$date_added,$comp_id,$contact_person_id);
	    $works_t = $this->works_m->display_works_selected($work_id);
		foreach ($works_t->result_array() as $row){
			$cont_type = $row['contractor_type'];
			$data['work_estimate'] = $row['work_estimate'];
			$data['is_variation'] = $row['is_variation'];
			$data['is_reconciled'] = $row['is_reconciled'];
		}
		if($cont_type == "Contractor"){
			$cont_type = 2;
		}else{
			$cont_type = 3;
		}


		$proj_id = $_POST['proj_id'];
		$proj_q = $this->projects_m->select_particular_project($proj_id);
		foreach ($proj_q->result_array() as $row){
			$data['job_date'] = $row['job_date'];
		}

		$acceptance_date = "";
		if(isset($_POST['var_acceptance_date'])){
			$acceptance_date = $_POST['var_acceptance_date'];
		}
		$data['acceptance_date'] = $acceptance_date;


		$data['works_contructors_t'] = $this->works_m->display_work_contructor($work_id);
		$this->load->view("work_contractor_t",$data);
	}

	function update_contractor(){
		$work_is_selected = $_POST['work_is_selected'];
		$work_contractor_id = $_POST['work_contractor_id'];
		$work_id = $_POST['work_id'];
	    $date_added = $_POST['date_added'];
	    $comp_id = $_POST['comp_id'];
	    $contact_person_id = $_POST['contact_person_id'];
	    // $inc_gst = $_POST['inc_gst'];
	    // $ex_gst = $_POST['ex_gst'];
		$works_cont_t = $this->works_m->update_works_contractor_details($work_contractor_id,$work_id,$date_added,$comp_id,$contact_person_id,$work_is_selected);//,$ex_gst,$inc_gst);

	    $works_t = $this->works_m->display_works_selected($work_id);
		foreach ($works_t->result_array() as $row){
			$cont_type = $row['contractor_type'];
			$data['work_estimate'] = $row['work_estimate'];
			$data['is_variation'] = $row['is_variation'];
			$data['is_reconciled'] = $row['is_reconciled'];
		}
		if($cont_type == "Contractor"){
			$cont_type = 2;
		}else{
			$cont_type = 3;
		}

		$proj_id = $_POST['proj_id'];
		$proj_q = $this->projects_m->select_particular_project($proj_id);
		foreach ($proj_q->result_array() as $row){
			$data['job_date'] = $row['job_date'];
		}

		$acceptance_date = "";
		if(isset($_POST['var_acceptance_date'])){
			$acceptance_date = $_POST['var_acceptance_date'];
		}
		$data['acceptance_date'] = $acceptance_date;

		$data['works_contructors_t'] = $this->works_m->display_work_contructor($work_id);
		$this->load->view("work_contractor_t",$data);
	}

	function update_var_contractor(){
		$work_is_selected = $_POST['work_is_selected'];
		$work_contractor_id = $_POST['work_contractor_id'];
		$work_id = $_POST['work_id'];
	    $date_added = $_POST['date_added'];
	    $comp_id = $_POST['comp_id'];
	    $contact_person_id = $_POST['contact_person_id'];
	    // $inc_gst = $_POST['inc_gst'];
	    // $ex_gst = $_POST['ex_gst'];
		$works_cont_t = $this->works_m->update_works_contractor_details($work_contractor_id,$work_id,$date_added,$comp_id,$contact_person_id,$work_is_selected);//,$ex_gst,$inc_gst);

	    $works_t = $this->works_m->display_works_selected($work_id);
		foreach ($works_t->result_array() as $row){
			$cont_type = $row['contractor_type'];
			$data['work_estimate'] = $row['work_estimate'];
			$data['is_variation'] = $row['is_variation'];
		}
		if($cont_type == "Contractor"){
			$cont_type = 2;
		}else{
			$cont_type = 3;
		}

		$proj_id = $_POST['proj_id'];
		$proj_q = $this->projects_m->select_particular_project($proj_id);
		foreach ($proj_q->result_array() as $row){
			$data['job_date'] = $row['job_date'];
		}

		$acceptance_date = "";
		if(isset($_POST['var_acceptance_date'])){
			$acceptance_date = $_POST['var_acceptance_date'];
		}
		$data['acceptance_date'] = $acceptance_date;

		$data['works_contructors_t'] = $this->works_m->display_work_contructor($work_id);
		$this->load->view("var_work_contractor",$data);
	}

	function update_contractor_gst(){
		$works_contrator_id = $_POST['works_contrator_id'];
	    $inc_gst = $_POST['inc_gst'];
	    $ex_gst = $_POST['ex_gst'];
	    $q_selected_contractor = $this->works_m->display_selected_contractor($works_contrator_id);
		foreach ($q_selected_contractor->result_array() as $row){
			$date_added = $row['date_added'];
			$comp_id = $row['company_id'];
			$work_id = $row['works_id'];
			$date_added = $row['date_added']; 
			$contact_person_id = $row['contact_person_id'];
			$is_selected = $row['is_selected'];
		}
		$works_cont_t = $this->works_m->update_works_contractor($works_contrator_id,$work_id,$date_added,$comp_id,$contact_person_id,$ex_gst,$inc_gst,$is_selected);
		echo $is_selected;
	}

	function delete_contractor(){
		$work_contractor_id = $_POST['work_contractor_id'];
		$work_id = $_POST['work_id'];
		$work_id = $_POST['work_id'];
		
		$works_t = $this->works_m->display_works_selected($work_id);
		foreach ($works_t->result_array() as $row){
			$data['work_estimate'] = $row['work_estimate'];
		}

		$works_cont_t = $this->works_m->display_selected_contractor($work_contractor_id);
		foreach ($works_cont_t->result_array() as $row){
			$is_selected = $row['is_selected'];
		}
		if($is_selected == 1){
?>
		<script>
			alert("Cannot delete Contractor! Contracotr is selected for a work!");
		</script>
<?php
			$works_t = $this->works_m->display_works_selected($work_id);
			foreach ($works_t->result_array() as $row){
				$cont_type = $row['contractor_type'];
				$data['is_variation'] = $row['is_variation'];
				$data['is_reconciled'] = $row['is_reconciled'];
			}
			if($cont_type == "Contractor"){
				$cont_type = 2;
			}else{
				$cont_type = 3;
			}

			$proj_id = $_POST['proj_id'];
			$proj_q = $this->projects_m->select_particular_project($proj_id);
			foreach ($proj_q->result_array() as $row){
				$data['job_date'] = $row['job_date'];
			}

			$acceptance_date = "";
			if(isset($_POST['var_acceptance_date'])){
				$acceptance_date = $_POST['var_acceptance_date'];
			}
			$data['acceptance_date'] = $acceptance_date;

			$data['works_contructors_t'] = $this->works_m->display_work_contructor($work_id);
			$this->load->view("work_contractor_t",$data);
		}else{
			$this->works_m->delete_works_contractor($work_contractor_id);
		    $works_t = $this->works_m->display_works_selected($work_id);
			foreach ($works_t->result_array() as $row){
				$cont_type = $row['contractor_type'];
				$data['is_variation'] = $row['is_variation'];
				$data['is_reconciled'] = $row['is_reconciled'];
			}
			if($cont_type == "Contractor"){
				$cont_type = 2;
			}else{
				$cont_type = 3;
			}

			$proj_id = $_POST['proj_id'];
			$proj_q = $this->projects_m->select_particular_project($proj_id);
			foreach ($proj_q->result_array() as $row){
				$data['job_date'] = $row['job_date'];
			}

			$acceptance_date = "";
			if(isset($_POST['var_acceptance_date'])){
				$acceptance_date = $_POST['var_acceptance_date'];
			}
			$data['acceptance_date'] = $acceptance_date;

			$data['works_contructors_t'] = $this->works_m->display_work_contructor($work_id);
			$this->load->view("work_contractor_t",$data);
		}
	}

	function delete_var_contractor(){
		$work_contractor_id = $_POST['work_contractor_id'];
		$work_id = $_POST['work_id'];
		$work_id = $_POST['work_id'];
		
		$works_t = $this->works_m->display_works_selected($work_id);
		foreach ($works_t->result_array() as $row){
			$data['work_estimate'] = $row['work_estimate'];
		}

		$works_cont_t = $this->works_m->display_selected_contractor($work_contractor_id);
		foreach ($works_cont_t->result_array() as $row){
			$is_selected = $row['is_selected'];
		}
		if($is_selected == 1){
?>
		<script>
			alert("Cannot delete Contractor! Contracotr is selected for a work!");
		</script>
<?php
			$works_t = $this->works_m->display_works_selected($work_id);
			foreach ($works_t->result_array() as $row){
				$cont_type = $row['contractor_type'];
				$data['is_variation'] = $row['is_variation'];
				$data['is_reconciled'] = $row['is_reconciled'];
			}
			if($cont_type == "Contractor"){
				$cont_type = 2;
			}else{
				$cont_type = 3;
			}

			$proj_id = $_POST['proj_id'];
			$proj_q = $this->projects_m->select_particular_project($proj_id);
			foreach ($proj_q->result_array() as $row){
				$data['job_date'] = $row['job_date'];
			}

			$acceptance_date = "";
			if(isset($_POST['var_acceptance_date'])){
				$acceptance_date = $_POST['var_acceptance_date'];
			}
			$data['acceptance_date'] = $acceptance_date;

			$data['works_contructors_t'] = $this->works_m->display_work_contructor($work_id);
			$this->load->view("work_contractor_t",$data);
		}else{
			$this->works_m->delete_works_contractor($work_contractor_id);
		    $works_t = $this->works_m->display_works_selected($work_id);
			foreach ($works_t->result_array() as $row){
				$cont_type = $row['contractor_type'];
				$data['is_variation'] = $row['is_variation'];
			}
			if($cont_type == "Contractor"){
				$cont_type = 2;
			}else{
				$cont_type = 3;
			}

			$proj_id = $_POST['proj_id'];
			$proj_q = $this->projects_m->select_particular_project($proj_id);
			foreach ($proj_q->result_array() as $row){
				$data['job_date'] = $row['job_date'];
			}

			$acceptance_date = "";
			if(isset($_POST['var_acceptance_date'])){
				$acceptance_date = $_POST['var_acceptance_date'];
			}
			$data['acceptance_date'] = $acceptance_date;

			$data['works_contructors_t'] = $this->works_m->display_work_contructor($work_id);
			$this->load->view("var_work_contractor",$data);
		}
	}

	function fetch_gst_rate(){
		$q_admin_defaults = $this->admin_m->fetch_admin_defaults();
		foreach ($q_admin_defaults->result_array() as $row){
			$gst_rate = $row['gst_rate'];
		}
		echo $gst_rate;
	}

	function update_work(){
		$this->projects->clear_apost();

		$update_stat = $_POST['update_stat'];
		$work_id = $_POST['work_id'];
		$proj_id = 0;
		if(isset($_POST['proj_id'])){
			$proj_id = $_POST['proj_id'];
		}
		switch($update_stat){
			case 1:
			    $work_markup = $_POST['work_markup'];
			    $work_q = $this->works_m->display_works_selected($work_id);
			    foreach ($work_q->result_array() as $row){
			    	$work_con_sup_id = $row['work_con_sup_id'];
			    	$work_estimate = $row['work_estimate'];
			    	$quote = $work_estimate + ($work_estimate * ($work_markup/100));
			    }
			    $joinery_id = $_POST['joinery_id'];
			    if($joinery_id == ""){
			    	$query = $this->works_m->update_works($update_stat,$work_id,"",$work_markup,$quote,"","","","","","","","","","","","","","","","","","","","");
			    	if($work_con_sup_id == '53'){
			    		$work_joinery_q = $this->works_m->display_all_works_joinery($work_id);
						foreach ($work_joinery_q->result_array() as $row){
							$joinery_id = $row['work_joinery_id'];
							$joinery_estimate = $row['j_estimate'];
		    				$joiner_quoted = $joinery_estimate +  ($joinery_estimate * ($work_markup/100));
		    				$this->works_m->update_joinery_markup($joinery_id,$work_markup,$joiner_quoted);
						}

						$work_joinery = $this->works_m->display_all_works_joinery($work_id);
				      	$t_price = 0;
				      	$t_estimate = 0;
				      	$t_quote = 0;
				      	foreach ($work_joinery->result_array() as $row)
						{
						   $t_price = $t_price + $row['j_price'];
						   $t_estimate = $t_estimate + $row['j_estimate'];
						   $t_quote = $t_quote + $row['j_quote'];
						}

						$this->works_m->update_work_joinery($work_id,$t_price,$t_estimate,$t_quote);

						//== Total Works in project
						$works_q = $this->works_m->display_all_works($proj_id);
						$t_price = 0;
						$t_estimate = 0;
						$t_quoted = 0;
						foreach ($works_q->result_array() as $row){
							$t_price = $t_price + $row['price'];
							$t_estimate = $t_estimate + $row['work_estimate'];
							$t_quoted = $t_quoted + $row['total_work_quote'];
						}

						$this->projects_m->update_project_cost_total($proj_id,$t_price,$t_estimate,$t_quoted);
			    	}
			    }else{
			    	$joinery_q = $this->works_m->display_selected_works_joinery($joinery_id);
			    	foreach ($joinery_q->result_array() as $row){
			    		$joinery_estimate = $row['wj_work_estimate'];
    					$joiner_quoted = $joinery_estimate +  ($joinery_estimate * ($work_markup/100));
			    	}
			    	$query = $this->works_m->update_joinery_markup($joinery_id,$work_markup,$joiner_quoted);
			    }
			    
				echo $query;
				break;
			case 2:
				$work_joinery_id = $_POST['work_joinery_id'];
			    $work_replyby_date = $_POST['work_replyby_date'];
			    $update_replyby_desc = $_POST['update_replyby_desc'];
			    $chkdeltooffice = $_POST['chkdeltooffice'];
			    $goods_deliver_by_date = $_POST['goods_deliver_by_date'];				 
			    $this->works_m->update_works($update_stat,$work_id,"","","",$work_replyby_date,$update_replyby_desc,$chkdeltooffice,$goods_deliver_by_date,"","","","","","","","","","","","","","","","",$work_joinery_id);
			   	
				break;
			case 3:
				$work_joinery_id = $_POST['work_joinery_id'];
			    $update_work_notes = $_POST['update_work_notes'];
			 	
			    $this->works_m->update_works($update_stat,$work_id,"","","","","","","",$update_work_notes,"","","","","","","","","","","","","","","",$work_joinery_id);
				break;
			case 4:
				$work_joinery_id = $_POST['work_joinery_id'];
				$chkcons_site_inspect = $_POST['chkcons_site_inspect'];
			    $chckcons_week_work = $_POST['chckcons_week_work'];
			    $chckcons_spcl_condition = $_POST['chckcons_spcl_condition'];
			    $chckcons_weekend_work = $_POST['chckcons_weekend_work'];
			    $chckcons_addnl_visit = $_POST['chckcons_addnl_visit'];
			    $chckcons_afterhrs_work = $_POST['chckcons_afterhrs_work'];
			    $chckcons_oprte_duringinstall = $_POST['chckcons_oprte_duringinstall'];
			    $chckcons_new_premises = $_POST['chckcons_new_premises'];
			    $chckcons_free_access = $_POST['chckcons_free_access'];
			    $chckcons_others = $_POST['chckcons_others'];
			    $other_consideration = $_POST['other_consideration'];
			    							
			    $this->works_m->update_works($update_stat,$work_id,"","","","","","","","",$chkcons_site_inspect,$chckcons_week_work,$chckcons_spcl_condition,$chckcons_weekend_work,$chckcons_addnl_visit,$chckcons_afterhrs_work,$chckcons_oprte_duringinstall,$chckcons_new_premises,$chckcons_free_access,$chckcons_others,$other_consideration,"","","","",$work_joinery_id);
				
				break;
			case 5:
				$work_type = $_POST['work_type'];
      			$work_con_sup_id = $_POST['work_con_sup_id'];
      			$this->works_m->update_works($update_stat,$work_id,"","","","","","","","","","","","","","","","","","","",$work_type,$work_con_sup_id);
				break;
			case 6:							 
				$this->works_m->update_works($update_stat,$work_id,"","","","","","","","","","","","","","","","","","","","","","","","");
				$this->works_m->remove_all_works_joinery($work_id);
				break;
			case 7:
				$price = $_POST['price'];
				$this->works_m->update_works($update_stat,$work_id,"","","","","","","","","","","","","","","","","","","","","",$price,"","");
				break;
			case 8:
				$price = $_POST['price'];
				$quoted = $_POST['quoted'];
				$this->works_m->update_works($update_stat,$work_id,"","","","","","","","","","","","","","","","","","","","","",$price,$quoted,"");
				break;
		}
	}
	function select_contractor(){
		$project_id = $_POST['proj_id'];
		$work_id = $_POST['work_id'];
		$selected_work_contractor_id = $_POST['selected_work_contractor_id'];
		$cpo_set = $this->works_m->set_selected_contractor($selected_work_contractor_id,$work_id );

// Logs=============================================
		if($cpo_set == '0'){
				date_default_timezone_set("Australia/Perth");
				$user_id = $this->session->userdata('user_id');
				$date = date("d/m/Y");
				$time = date("H:i:s");
				$action = 'Assigned CPO: '.$work_id;
	
				$this->user_model->insert_user_log($user_id,$date,$time,$action,$project_id,'Update');
		}
// Logs=============================================

		$works_t = $this->works_m->display_works_selected($work_id);
		foreach ($works_t->result_array() as $row){
			$cont_type = $row['contractor_type'];
			$data['is_variation'] = $row['is_variation'];
			$data['is_reconciled'] = $row['is_reconciled'];
			//$data['work_estimate'] = $row['work_estimate'];
		}
		if($cont_type == "Contractor"){
			$cont_type = 2;
		}else{
			$cont_type = 3;
		}
		if(isset($_POST['all'])){
			$this->works_m->set_all_joinery_subitem_contractor($work_id,$selected_work_contractor_id);
		}

		
		$proj_q = $this->projects_m->select_particular_project($project_id);
		foreach ($proj_q->result_array() as $row){
			$data['job_date'] = $row['job_date'];
		}

		$acceptance_date = "";
		if(isset($_POST['var_acceptance_date'])){
			$acceptance_date = $_POST['var_acceptance_date'];
		}
		$data['acceptance_date'] = $acceptance_date;


		$data['works_contructors_t'] = $this->works_m->display_work_contructor($work_id);
		$this->load->view("work_contractor_t",$data);
	}

	function select_var_contractor(){
		$project_id = $_POST['proj_id'];
		$work_id = $_POST['work_id'];
		$selected_work_contractor_id = $_POST['selected_work_contractor_id'];
		$cpo_set =$this->works_m->set_selected_contractor($selected_work_contractor_id,$work_id );

// Logs=============================================
		if($cpo_set == '0'){
				date_default_timezone_set("Australia/Perth");
				$user_id = $this->session->userdata('user_id');
				$date = date("d/m/Y");
				$time = date("H:i:s");
				$action = 'Assigned CPO: '.$work_id;
	
				$this->user_model->insert_user_log($user_id,$date,$time,$action,$project_id,'Update');
		}
// Logs=============================================

		$works_t = $this->works_m->display_works_selected($work_id);
		foreach ($works_t->result_array() as $row){
			$cont_type = $row['contractor_type'];
			$data['is_variation'] = $row['is_variation'];
			//$data['work_estimate'] = $row['work_estimate'];
		}
		if($cont_type == "Contractor"){
			$cont_type = 2;
		}else{
			$cont_type = 3;
		}
		if(isset($_POST['all'])){
			$this->works_m->set_all_joinery_subitem_contractor($work_id,$selected_work_contractor_id);
		}

		
		$proj_q = $this->projects_m->select_particular_project($project_id);
		foreach ($proj_q->result_array() as $row){
			$data['job_date'] = $row['job_date'];
		}

		$acceptance_date = "";
		if(isset($_POST['var_acceptance_date'])){
			$acceptance_date = $_POST['var_acceptance_date'];
		}
		$data['acceptance_date'] = $acceptance_date;


		$data['works_contructors_t'] = $this->works_m->display_work_contructor($work_id);
		$this->load->view("var_work_contractor",$data);
	}

	function view_works_list(){
		$this->session->set_flashdata('curr_tab', 'works');
	}

	function view_send_pdf(){
		$this->session->set_flashdata('curr_tab', 'send_pdf');
	}

	function works_total(){
		$proj_id = $_POST['proj_id'];
		$works_q = $this->works_m->display_all_works($proj_id);
		$t_price = 0;
		$t_estimate = 0;
		$t_quoted = 0;
		foreach ($works_q->result_array() as $row){
			$t_price = $t_price + $row['price'];
			$t_estimate = $t_estimate + $row['work_estimate'];
			$t_quoted = $t_quoted + $row['total_work_quote'];
		}

		$this->projects_m->update_project_cost_total($proj_id,$t_price,$t_estimate,$t_quoted);
		
		echo number_format($t_price)."/".number_format($t_estimate)."/".number_format($t_quoted);
	}

	function var_works_total(){
		$proj_id = $_POST['proj_id'];
		$variation_id = $_POST['variation_id'];
		$works_q = $this->works_m->display_all_works($proj_id,1,$variation_id);
		$t_price = 0;
		$t_estimate = 0;
		$t_quoted = 0;
		foreach ($works_q->result_array() as $row){
			$t_price = $t_price + $row['price'];
			$t_estimate = $t_estimate + $row['work_estimate'];
			$t_quoted = $t_quoted + $row['total_work_quote'];
		}

		$var_cost = $t_quoted;
		
		$this->variation_m->update_variation_cost($variation_id,$var_cost);
		
		echo number_format($t_price)."/".number_format($t_estimate)."/".number_format($t_quoted);
	}

	function display_work_attachments(){
		$proj_id = $this->uri->segment(3);
		$work_id = $this->uri->segment(4);
		$works_attachment_t = $this->works_m->display_work_attachments($work_id);
		$data['works_attachment_t'] = $this->works_m->display_work_attachments($work_id);
		$data['proj_id'] = $proj_id;
		$data['work_id'] = $work_id;
		$this->load->view('table_work_attachment', $data);
	}

	function display_attachment_type_list(){

	}

	function do_upload()
	{
		$project_id = $this->uri->segment(3);
		$work_id = $this->uri->segment(4);
		$attachment_type = $_POST['attachment_type'];

	    $this->load->library('upload');

	    $files = $_FILES;
	    $cpt = count($_FILES['userfile']['name']);
	    for($i=0; $i<$cpt; $i++)
	    {
	    	$file_name =  $files['userfile']['name'][$i];
	    	$file_name = str_replace(' ', '_', $file_name);
	    	$work_attach_q = $this->works_m->display_work_attachments($work_id);
	    	$file = explode('.', $file_name);
	    	$filename = $file[0];
	    	$extension = $file[1];
	    	$file_exist = 0;
	    	foreach ($work_attach_q->result_array() as $row){
	    		$db_file_name = $row['work_attachments_url'];
	    		$db_file = explode('.', $db_file_name);
		    	$db_filename = $db_file[0];
		    	$db_extension = $db_file[1];
		    	if (strpos($db_filename, $filename) !== false) {
		    		if($extension == $db_extension){
		    			$file_exist = $file_exist + 1;
		    		}	
		    	}
	    	}

	    	if($file_exist > 0){
	    		$filename = $filename.$file_exist;
	    		$file_name = $filename.".".$extension;
	    	}

		    $_FILES['userfile']['name']= $file_name;
		    $_FILES['userfile']['type']= $files['userfile']['type'][$i];
		    $_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
		    $_FILES['userfile']['error']= $files['userfile']['error'][$i];
		    $_FILES['userfile']['size']= $files['userfile']['size'][$i];    

		    $this->upload->initialize($this->set_upload_options($project_id,$work_id));
		    $this->upload->do_upload();
	    	$this->works_m->insert_work_attachments($work_id,$attachment_type,$_FILES['userfile']['name']);
	    }
	    $this->session->set_flashdata('works_curr_tab', 'attachments');
	    redirect('works/update_work_details/'.$project_id."/".$work_id);
	}
	private function set_upload_options($proj_id,$work_id)
	{   
	//  upload an image options
		$path = "./uploads/project_attachments/".$proj_id."/".$work_id;
		mkdir($path, 0755, true);
	    $config = array();
	    $config['upload_path'] = $path."/";
	    $config['allowed_types'] = '*';
	    $config['max_size']      = '0';
	    $config['overwrite']     = FALSE;


	    return $config;
	}
	function download_file(){
		$proj_id = $_POST['proj_id'];
		$work_id = $_POST['work_id'];
		$file_name = $_POST['file_name'];


		$this->load->library('ftp');

		$config['hostname'] = 'cp178.ezyreg.com';
		$config['username'] = 'soso1713';
		$config['password'] = 'f*0e^cr3';
		$config['debug']	= TRUE;

		$con = $this->ftp->connect($config);
	
		echo is_array(ftp_nlist($con, ".")) ? 'Connected!' : 'not Connected! :(';

		/*$server_path = '/public_html/uploads/project_attachments/'.$proj_id.'/'.$work_id.'/'.$file_name;
		
		$this->ftp->download($server_path, 'C:\Users\MarkO\Documents', 'ascii');

		$this->ftp->close();
		*/
		



		/*$ftp_server = "cp178.ezyreg.com";
		$ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
		$login = ftp_login($ftp_conn, "soso1713", "f*0e^cr3");

		$local_file = "local.zip";
		$proj_id = $_POST['proj_id'];
		$work_id = $_POST['work_id'];
		$file_name = $_POST['file_name'];
		$server_file ='/public_html/uploads/project_attachments/'.$proj_id.'/'.$work_id.'/'.$file_name;

		
		if (ftp_get($ftp_conn, $local_file, $server_file, FTP_ASCII))
		  {
		  echo "Successfully written to $local_file.";
		  }
		else
		  {
		  echo "Error downloading $server_file.";
		  }

		
		ftp_close($ftp_conn);
		*/
	} 
	function delete_attachment(){
		$work_id = $_POST['work_id'];
		$file_name = $_POST['file_name'];
		$this->works_m->remove_work_attachments($work_id,$file_name);
		$this->session->set_flashdata('works_curr_tab', 'attachments');
	}

	function edit_attachment_type(){
		$work_attachment_id = $_POST['work_attachment_id'];
    	$attach_type = $_POST['attach_type'];
    	$this->works_m->update_work_attachments($work_attachment_id,$attach_type);
    	echo $work_attachment_id."/".$attach_type;
	}

	function update_work_attachment_type(){
		$work_id = $_POST['work_id'];

		$this->works_m->delete_work_attachment_type($work_id);
		$checkboxValues =  $_POST['checkboxValues'];
		foreach($checkboxValues as $chkval){
			$this->works_m->insert_work_attachment_type($work_id,$chkval);
		}
	}

	function fetch_joinery(){
		$joinery = array();
		$joinery_q = $this->works_m->fetch_joinery();
		foreach ($joinery_q->result_array() as $row)
		{
		   array_push($joinery, $row['joinery_name']);
		}
		echo json_encode($joinery);
	}


	function update_work_joinery(){
		$work_joinery_id = $_POST['work_joinery_id'];
		$price = $_POST['price'];
		$quoted = $_POST['quoted'];        
        
        $this->works_m->update_works_joinery_estimate($work_joinery_id,$price,$quoted);
        $work_joinery_q = $this->works_m->display_selected_works_joinery($work_joinery_id);
        foreach ($work_joinery_q->result_array() as $row)
		{
		   $works_id = $row['works_id'];
		}
		$work_joinery_q = $this->works_m->display_all_works_joinery($works_id);
		$total_estimate = 0;
		$total_quote = 0;
		foreach ($work_joinery_q->result_array() as $row)
		{
		   $total_estimate = $total_estimate + $row['j_estimate'];
		   $total_quote = $total_quote + $row['j_quote'];
		}
		$update_stat = 8;
		$this->works_m->update_works($update_stat,$works_id,"","","","","","","","","","","","","","","","","","","","","",$total_estimate,$total_quote);

		echo $total_estimate."|".$total_quote."|".$works_id;
	}

	function update_joinery_name(){
		$joinery_name = $_POST['joinery_name'];
		$work_joinery_id = $_POST['work_joinery_id'];
		$joinery_name_q = $this->works_m->verify_joinery_name($joinery_name);
		if($joinery_name_q->num_rows == 0){
			$joinery_id = $this->works_m->insert_joinery_name($joinery_name);
		}else{
			foreach ($joinery_name_q->result_array() as $row){
				$joinery_id = $row['joinery_id'];
			}
		}
		$this->works_m->update_selected_joinery_name($work_joinery_id,$joinery_id);
	}

	function is_joinery(){
		$works_id = $_POST['work_id'];
		$work_q = $this->works_m->display_works_selected($works_id);
		foreach ($work_q->result_array() as $row)
		{
		   $is_joinery = $row['work_con_sup_id'];
		}
		echo $is_joinery;
	}

	function get_joinery_work_id(){
		$work_joinery_id = $_POST['work_joinery_id'];
		$work_q = $this->works_m->display_selected_works_joinery($work_joinery_id);
		foreach ($work_q->result_array() as $row){
			$works_id = $row['works_id'];
			$distinct_character = $row['distinct_character'];
		}
		echo $works_id.$distinct_character;
	}

	function unset_work_contractor(){
		$project_id = 0;
		$work_id = $_POST['work_id'];
		$work_q = $this->works_m->display_works_selected($work_id);

		foreach ($work_q->result_array() as $row)
		{
		   $comp_id = $row['company_client_id'];
		   $work_con_sup_id = $row['work_con_sup_id'];
		   $project_id = $row['project_id'];
		}
		$this->works_m->unset_selected_contractor($work_id,$comp_id);
		if($work_con_sup_id == 53){
			$work_joinery_q = $this->works_m->display_all_works_joinery($work_id);
			foreach ($work_joinery_q->result_array() as $row){
				$work_joinery_id = $row['work_joinery_id'];
				$this->works_m->unset_joinery_contractor($work_joinery_id,$work_id);
			}
		}
// Logs=============================================
		
		date_default_timezone_set("Australia/Perth");
		$user_id = $this->session->userdata('user_id');
		$date = date("d/m/Y");
		$time = date("H:i:s");
		$action = 'CPO Unset: '.$work_id;
	
		$this->user_model->insert_user_log($user_id,$date,$time,$action,$project_id,'Update');
		
// Logs=============================================
	}

	function have_sub_item(){
		$works_id = $_POST['work_id'];
		$joinery_q = $this->works_m->display_all_works_joinery($works_id); 
		if($joinery_q->num_rows > 0){
			echo 1;
		}else{
			echo 0;
		}
	
	}
	function update_joinery_selected_subitem_price(){
		$work_joinery_id = $_POST['work_joinery_id'];
		$unit_price = $_POST['work_joinery_unit_price'];
		$price = $_POST['t_price'];
		$qty = $_POST['joinery_qty'];
		$this->works_m->update_work_joinery_item_price($work_joinery_id,$unit_price,$price,$qty);
	}

	function update_joinery_selected_subitem_estimate(){
		$work_joinery_id = $_POST['work_joinery_id'];
      	$work_id = $_POST['work_id'];
      	$work_joinery_unit_estimated = $_POST['work_joinery_unit_estimated'];
      	$t_estimate = $_POST['t_estimate'];
      	$quoted = $_POST['quoted'];
      	$this->works_m->update_work_joinery_item_estimate($work_joinery_id,$work_id,$work_joinery_unit_estimated,$t_estimate,$quoted);
	}

	function get_joinery_totals(){
		$work_id = $_POST['work_id'];
		$work_joinery = $this->works_m->display_all_works_joinery($work_id);
      	$t_price = 0;
      	$t_estimate = 0;
      	$t_quote = 0;
      	foreach ($work_joinery->result_array() as $row)
		{
		   $t_price = $t_price + $row['j_price'];
		   $t_estimate = $t_estimate + $row['j_estimate'];
		   $t_quote = $t_quote + $row['j_quote'];
		}

		$this->works_m->update_work_joinery($work_id,$t_price,$t_estimate,$t_quote);
		echo $t_price."|".$t_estimate."|".$t_quote;
	}

	function set_joinery_subitem_contractor(){
		$work_joinery_id = $_POST['work_joinery_id'];
		$work_id = $_POST['work_id'];
		$qty = $_POST['qty'];
		$unit_price = $_POST['unit_price'];
		$t_price = $_POST['t_price'];
		$company_id = $_POST['company_id'];
		$joinery_work_id = $_POST['joinery_work_id'];

		$work_id = $_POST['work_id'];
		$works_t = $this->works_m->display_works_selected($work_id);
		foreach ($works_t->result_array() as $row){
			$cont_type = $row['contractor_type'];
			$data['work_estimate'] = $row['work_estimate'];
			$data['is_variation'] = $row['is_variation'];
			$data['is_reconciled'] = $row['is_reconciled'];
		}

		$project_id = $_POST['proj_id'];
		$proj_q = $this->projects_m->select_particular_project($project_id);
		foreach ($proj_q->result_array() as $row){
			$data['job_date'] = $row['job_date'];
		}

		$this->works_m->update_work_joinery_item_price($work_joinery_id,$unit_price,$t_price,$qty);
		if($company_id !== ""){
			$this->works_m->set_joinery_contractor($work_joinery_id,$work_id,$company_id,$t_price,$joinery_work_id);
		}
		
		$acceptance_date = "";
		if(isset($_POST['var_acceptance_date'])){
			$acceptance_date = $_POST['var_acceptance_date'];
		}
		$data['acceptance_date'] = $acceptance_date;


		$data['works_contructors_t'] = $this->works_m->display_work_contructor($joinery_work_id);
		$this->load->view("work_contractor_t",$data);
	}

	function set_var_joinery_subitem_contractor(){
		$work_joinery_id = $_POST['work_joinery_id'];
		$work_id = $_POST['work_id'];
		$qty = $_POST['qty'];
		$unit_price = $_POST['unit_price'];
		$t_price = $_POST['t_price'];
		$company_id = $_POST['company_id'];
		$joinery_work_id = $_POST['joinery_work_id'];

		$work_id = $_POST['work_id'];
		$works_t = $this->works_m->display_works_selected($work_id);
		foreach ($works_t->result_array() as $row){
			$cont_type = $row['contractor_type'];
			$data['work_estimate'] = $row['work_estimate'];
			$data['is_variation'] = $row['is_variation'];
		}

		$project_id = $_POST['proj_id'];
		$proj_q = $this->projects_m->select_particular_project($project_id);
		foreach ($proj_q->result_array() as $row){
			$data['job_date'] = $row['job_date'];
		}

		$this->works_m->update_work_joinery_item_price($work_joinery_id,$unit_price,$t_price,$qty);
		if($company_id !== ""){
			$this->works_m->set_joinery_contractor($work_joinery_id,$work_id,$company_id,$t_price,$joinery_work_id);
		}
		
		$acceptance_date = "";
		if(isset($_POST['var_acceptance_date'])){
			$acceptance_date = $_POST['var_acceptance_date'];
		}
		$data['acceptance_date'] = $acceptance_date;


		$data['works_contructors_t'] = $this->works_m->display_work_contructor($joinery_work_id);
		$this->load->view("var_work_contractor",$data);
	}

	function unset_joinery_subitem_contractor(){
		$work_joinery_id = $_POST['work_joinery_id'];
		$work_id = $_POST['work_id'];

		$works_t = $this->works_m->display_works_selected($work_id);
		foreach ($works_t->result_array() as $row){
			$cont_type = $row['contractor_type'];
			$data['work_estimate'] = $row['work_estimate'];
			$data['is_variation'] = $row['is_variation'];
			$data['is_reconciled'] = $row['is_reconciled'];
		}

		$this->works_m->unset_joinery_contractor($work_joinery_id,$work_id);
		$joinery_work_id = $work_id."-".$work_joinery_id;

		$acceptance_date = "";
		if(isset($_POST['var_acceptance_date'])){
			$acceptance_date = $_POST['var_acceptance_date'];
		}
		$data['acceptance_date'] = $acceptance_date;

		$proj_id = $_POST['proj_id'];
		$proj_q = $this->projects_m->select_particular_project($proj_id);
		foreach ($proj_q->result_array() as $row){
			$data['job_date'] = $row['job_date'];
		}

		$data['works_contructors_t'] = $this->works_m->display_work_contructor($joinery_work_id);
		$this->load->view("work_contractor_t",$data);
	}

	function delete_selected_joinery_subitem(){
		$work_joinery_id = $_POST['work_joinery_id'];
		$this->works_m->delete_selected_joinery_subitem($work_joinery_id);
	}

	function get_contract_notes(){
		$project_id = $_POST['project_id'];
		$proj_note = $this->works_m->fetch_contract_notes($project_id);
		$cont_date = "";
		$plans_elv_draw = "";
		$sched_works_qoute = "";
		$cond_quote_cont= "";
		foreach ($proj_note->result_array() as $row){
			$cont_date = $row['contract_date'];;
			$plans_elv_draw = $row['ped_note'];;
			$sched_works_qoute = $row['sowiiq'];;
			$cond_quote_cont= $row['coqac'];;
		}

		echo $cont_date.'|'.$plans_elv_draw.'|'.$sched_works_qoute.'|'.$cond_quote_cont;
	}

	function insert_contract_notes(){
		$project_id = $_POST['project_id'];
		$cont_date = $_POST['cont_date'];
		$plans_elv_draw = $_POST['plans_elv_draw'];
		$sched_works_qoute = $_POST['sched_works_qoute'];
		$cond_quote_cont= $_POST['cond_quote_cont'];

		$proj_note = $this->works_m->fetch_contract_notes($project_id);
		if($proj_note->num_rows > 0){
			$this->works_m->update_contract_notes($project_id,$cont_date,$plans_elv_draw,$sched_works_qoute,$cond_quote_cont);
		}else{
			$this->works_m->insert_contract_notes($project_id,$cont_date,$plans_elv_draw,$sched_works_qoute,$cond_quote_cont);
		}
	}
	
	function update_work_comments(){
		$works_id = $_POST['works_id'];
		$work_comments = $_POST['work_comments'];
		$this->works_m->update_work_comments($works_id,$work_comments);
	}

	function update_contractor_notes(){
		$work_contractor_id = $_POST['work_contractor_id'];
		$contractor_notes = $_POST['contractor_notes'];
		$this->works_m->update_work_contractor_notes($work_contractor_id,$contractor_notes);
	}
	
// Works Contractos Filter (NEW)
	function fetch_comp_suburb(){
		$state_name = "";
		if(isset($_POST['state_name'])){
			$state_name = $_POST['state_name'];
		}
		$comp_q = $this->company_m->fetch_all_state_suburb();
		foreach ($comp_q->result() as $row){
			if($state_name == ""){
				$result[] = array(
					'suburb' => $row->suburb
				);
			}else{
				if($row->name == $state_name){
					$result[] = array(
						'suburb' => $row->suburb
					);
				}
			}
			
		}

		echo json_encode($result);
	}

	function fetch_work_type(){
		$work_id = $_POST['work_id'];
		$works_list = $this->works_m->display_works_selected($work_id);
		foreach ($works_list->result() as $row){
			$work_con_sup_id = $row->work_con_sup_id;
			if($work_con_sup_id == 82){
				$other_category_id= $row->other_category_id;
				$other_category_id_arr = explode("_", $other_category_id);
				$contractor_type = $other_category_id_arr[0];
				if($contractor_type == 2){

					$job_sub_cat_id = $other_category_id_arr[1];
					//if($job_sub_cat_id == 32){
						$work_con_sup_id = $job_sub_cat_id;
					//}else{
						// $job_cat_q = $this->company_m->fetch_job_cat($job_sub_cat_id);
					 // 	foreach ($job_cat_q->result() as $job_cat_row){
					 // 		$work_con_sup_id = $job_cat_row->job_category_id;
					 // 	}
					//}
				 	
				}else{
					$work_con_sup_id = $other_category_id_arr[1];
				}
			}else{
				$contractor_type = $row->contractor_type;
				if($contractor_type == 2){
					$work_con_sup_id = $row->job_category_id;
				}else{
					$work_con_sup_id = $row->work_con_sup_id;
				}
				$other_category_id = $row->other_category_id;
			}
			
		}
		echo $contractor_type.'|'.$work_con_sup_id.'|'.$other_category_id;
	}

	function fetch_comp_activity(){
		$comp_type = $_POST['comp_type'];
		if($comp_type == 2){
			$comp_q = $this->company_m->fetch_all_contractor_types();
			foreach ($comp_q->result() as $row){
				$result[] = array(
				    'id' => $row->job_category_id,
				    'name' => $row->job_category
				);
			}
		}else{
			$comp_q = $this->company_m->fetch_all_supplier_types();
			foreach ($comp_q->result() as $row){
				$result[] = array(
				    'id' => $row->supplier_cat_id,
				    'name' => $row->supplier_cat_name
				);
			}
		}

		echo json_encode($result);
	}

	function add_contractors_list(){
		$state = $_POST['state'];
		$proj_post_code = $_POST['proj_post_code'];
		$activity = $_POST['activity'];
		$type = $_POST['comp_type'];
	
		$con_sup_list_q = $this->company_m->fetch_all_company_activity_type($activity,$type);
		$con_sup_num_rows = $con_sup_list_q->num_rows();

		echo '<table id="myTable" class = "table table-condensed table-bordered m-bottom-0" style = "font-size: 12px">';
		echo '<tr class="header">';
		echo '<th></th>';
		echo '<th>Company Name</th>';
		echo '<th>Contact Person</th>';
		echo '<th>Post Code</th>';
		echo '<th>Suburb</th>';
		echo '<th>State</th>';
		echo '</tr>';
		$company_num = 0;
		foreach ($con_sup_list_q->result() as $row){
			if($row->postcode == $proj_post_code){
				$company_num++;
				$result[] = array(
								    'company_id' => $row->company_id,
								    'company_name' => $row->company_name,
								    'postcode'=> $row->postcode,
								    'suburb'=> $row->suburb,
								    'name'=> $row->name
								);
			}
		}

		$result = [];

		if($company_num < $con_sup_num_rows){
			
			$contractor_exist = $company_num;

			// while($contractor_exist <= $con_sup_num_rows){
				if($contractor_exist < 10){
					foreach ($con_sup_list_q->result() as $row){
						if($contractor_exist < 10){
							if($row->postcode !== $proj_post_code){
								if($row->name == $state){
									$contractor_exist++;
									$result[] = array(
													    'company_id' => $row->company_id,
													    'company_name' => $row->company_name,
													    'postcode'=> $row->postcode,
													    'suburb'=> $row->suburb,
													    'name'=> $row->name
													);
								}
								
							}
						}
						// else{
						// 	break;
						// }
						
					}
				}
				
				if($contractor_exist < 10){

					foreach ($con_sup_list_q->result() as $row){
						if($contractor_exist < 10){
							if($row->postcode !== $proj_post_code){
								if($row->name !== $state){
									$contractor_exist++;
									$result[] = array(
													    'company_id' => $row->company_id,
													    'company_name' => $row->company_name,
													    'postcode'=> $row->postcode,
													    'suburb'=> $row->suburb,
													    'name'=> $row->name
													);
								}
							}
						}
						// else{
						// 	break;
						// }
						
					}
				}

		
				
			// }
			
		}
		


		foreach ($result as $row) {
			echo '<tr>';
				echo '<td><input type="checkbox" value = '.$row['company_id'].' name = "chk_work_contractors"></td>';
				echo '<td>'.ucwords(strtolower($row['company_name'])).'</td>';
				$contractors_contacts = $this->company_m->fetch_contact_person_company($row['company_id']);
				echo '<td>';
				echo '<select class = "form-control input-sm" id = "'.$row['company_id'].'_cont_person">';
				foreach ($contractors_contacts->result() as $contact_row){
					if($contact_row ->is_primary == '1'){
						echo '<option value = "'.$contact_row->contact_person_id.'" selected = "Selected">'.$contact_row->first_name." ".$contact_row->last_name.'</option>';
					}else{
						echo '<option value = "'.$contact_row->contact_person_id.'">'.$contact_row->first_name." ".$contact_row->last_name.'</option>';	
					}
					
				}
				echo '</select>';
				echo '</td>';
				echo '<td>'.$row['postcode'].'</td>';
				echo '<td>'.$row['suburb'].'</td>';
				echo '<td>'.$row['name'].'</td>';
						//echo '<td>'..'</td>'
			echo '</tr>';
		}

		echo '</table>';


		
	}

	function search_add_contractors_list(){
		$search_text = $_POST['text_search'];
		$contractors_list = $this->company_m->fetch_all_company_search($search_text);
		$num_rows = $contractors_list->num_rows();
		echo '<table id="myTable" class = "table table-condensed table-bordered m-bottom-0" style = "font-size: 12px">';
		echo '<tr class="header">';
		echo '<th></th>';
		echo '<th>Company Name</th>';
		echo '<th>Contact Person</th>';
		echo '<th>Post Code</th>';
		echo '<th>Suburb</th>';
		echo '<th>State</th>';
		echo '</tr>';
		foreach ($contractors_list->result() as $row){
			echo '<tr>';
				echo '<td><input type="checkbox" value = '.$row->company_id.' name = "chk_work_contractors"></td>';
				echo '<td>'.ucwords(strtolower($row->company_name)).'</td>';
				$contractors_contacts = $this->company_m->fetch_contact_person_company($row->company_id);
				echo '<td>';
				echo '<select class = "form-control input-sm" id = "'.$row->company_id.'_cont_person">';
				foreach ($contractors_contacts->result() as $contact_row){
					if($contact_row ->is_primary == '1'){
						echo '<option value = "'.$contact_row->contact_person_id.'" selected = "Selected">'.$contact_row->first_name." ".$contact_row->last_name.'</option>';
					}else{
						echo '<option value = "'.$contact_row->contact_person_id.'">'.$contact_row->first_name." ".$contact_row->last_name.'</option>';	
					}
				}
				echo '</select>';
				echo '</td>';
				echo '<td>'.$row->postcode.'</td>';
				echo '<td>'.$row->suburb.'</td>';
				echo '<td>'.$row->name.'</td>';
					
			echo '</tr>';
		}
		echo '</table>';
	}

	function filter_con_sup_list(){
		$activity_id = $_POST['activity_id'];
		$state = 0;
		$suburb = 0;
		$postcode = 0;

		if(isset($_POST['state'])){
			$state = $_POST['state'];
		}

		if(isset($_POST['suburb'])){
			$suburb = $_POST['suburb'];
		}

		if(isset($_POST['postcode'])){
			$postcode = $_POST['postcode'];
		}

		// echo $activity_id."|".$state."|".$suburb."|".$postcode;

		$contractors_list = $this->company_m->fetch_all_company_filter($activity_id,$state,$suburb,$postcode);
		$num_rows = $contractors_list->num_rows();
		echo '<table id="myTable" class = "table table-condensed table-bordered m-bottom-0" style = "font-size: 12px">';
		echo '<tr class="header">';
		echo '<th></th>';
		echo '<th>Company Name</th>';
		echo '<th>Contact Person</th>';
		echo '<th>Post Code</th>';
		echo '<th>Suburb</th>';
		echo '<th>State</th>';
		echo '</tr>';
		foreach ($contractors_list->result() as $row){
			echo '<tr>';
				echo '<td><input type="checkbox" value = '.$row->company_id.' name = "chk_work_contractors"></td>';
				echo '<td>'.ucwords(strtolower($row->company_name)).'</td>';
				$contractors_contacts = $this->company_m->fetch_contact_person_company($row->company_id);
				echo '<td>';
				echo '<select class = "form-control input-sm" id = "'.$row->company_id.'_cont_person">';
				foreach ($contractors_contacts->result() as $contact_row){
					if($contact_row ->is_primary == '1'){
						echo '<option value = "'.$contact_row->contact_person_id.'" selected = "Selected">'.$contact_row->first_name." ".$contact_row->last_name.'</option>';
					}else{
						echo '<option value = "'.$contact_row->contact_person_id.'">'.$contact_row->first_name." ".$contact_row->last_name.'</option>';	
					}
				}
				echo '</select>';
				echo '</td>';
				echo '<td>'.$row->postcode.'</td>';
				echo '<td>'.$row->suburb.'</td>';
				echo '<td>'.$row->name.'</td>';
					
			echo '</tr>';
		}
		echo '</table>';
	}

	function safe_site_observation(){
		$proj_id = $this->uri->segment(3);
		
		$proj_q = $this->works_m->select_particular_project($proj_id);

		foreach ($proj_q->result_array() as $row){
			$primary_contact_person_id = $row['primary_contact_person_id'];
			$tenancyno = $row['shop_tenancy_number'].' '.$row['shop_name'];
			$client_id = $row['client_id'];
			$job_type = $row['job_type'];
			$data['start_date'] = $row['date_site_commencement'];
			$data['end_date'] = $row['date_site_finish'];
				
			$data['project_total'] = $row['project_total'];

			$project_manager_id = $row['project_manager_id'];
			$users_q = $this->user_model->fetch_user($project_manager_id);
			foreach ($users_q->result_array() as $users_row){
				$data['contact_person'] = $users_row['user_first_name']." ".$users_row['user_last_name'];
				$user_email_id = $users_row['user_email_id'];
				$email_q = $this->company_m->fetch_email($user_email_id);
				foreach ($email_q->result_array() as $email_row){
					$data['email'] = $email_row['general_email'];
				}

			}

			$focus_company_id = $row['focus_company_id'];
			$focus_comp_q = $this->admin_m->fetch_single_company_focus($focus_company_id);
			foreach ($focus_comp_q->result_array() as $focus_comp_row){
				$data['focus_logo'] = $focus_comp_row['logo'];
				$data['focus_comp'] = $focus_comp_row['company_name'];
				$officnumber = $focus_comp_row['office_number'];
				$prefix = substr($officnumber, 0, 4);

				if($prefix == '1300' || $prefix == '1800'){
					$data['office_no'] = $focus_comp_row['office_number'];
				}else{
					$data['office_no'] = $focus_comp_row['area_code']." ".$focus_comp_row['office_number'];
				}
				$data['acn'] = $focus_comp_row['acn'];
				$data['abn'] = $focus_comp_row['abn'];
				$data['focus_email'] = $focus_comp_row['general_email'];
				$address_id = $focus_comp_row['postal_address_id'];
				$physicaladd_id = $focus_comp_row['address_id'];
				$focus_comp_q = $this->company_m->fetch_complete_detail_address($address_id);
				foreach ($focus_comp_q->result_array() as $comp_address_row){
					$po_box = $comp_address_row['po_box'];
					if($po_box == ""){
						$data['po_box'] = "";
					}else{
						$data['po_box'] = "PO ".$comp_address_row['po_box'];
					}
					$data['focus_suburb'] = ucwords(strtolower($comp_address_row['suburb']))." ".$comp_address_row['shortname']." ".$comp_address_row['postcode'];
				}
			}
			
			$site_address_id= $row['site_add'];


			if($job_type == "Shopping Center"){
				$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
				foreach ($site_address_q->result_array() as $client_add_row){
					$data['site_address_1st'] = $tenancyno;

					if($client_add_row['unit_level'] == ""){
						$data['site_address_2nd'] = $client_add_row['unit_number'].' '.$client_add_row['street'];
					}else{
						$data['site_address_2nd'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'].' '.$client_add_row['street'];
					}
						
					$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
				}	
			}else{
				$site_address_q = $this->company_m->fetch_complete_detail_address($site_address_id);
				foreach ($site_address_q->result_array() as $client_add_row){
					if($client_add_row['unit_level'] == ""){
						$data['site_address_1st'] = $client_add_row['unit_number'];
					}else{
						$data['site_address_1st'] = $client_add_row['unit_level']." / ".$client_add_row['unit_number'];
					}
					$data['site_address_2nd'] = $client_add_row['street'];
					$data['site_address_3rd'] = ucfirst(strtolower($client_add_row['suburb']))." ".$client_add_row['shortname']." ".$client_add_row['postcode'];
				}
			}
			   	
		}
		$data['proj_id'] = $proj_id;
		$data['project_t'] = $proj_q;
		$this->load->view('safe_work_observation_pdf', $data);
	}

	function insert_work_pending_company(){
		$data = json_decode(file_get_contents("php://input"), true);
		$works_id = $data['works_id'];
		$company_id = $data['company_id'];
		foreach ($company_id as $value) {
		 	$this->works_m->insert_work_pending_company($works_id,$value);
		}
		
	}

	function update_temporary_cont_sup(){
		$data = json_decode(file_get_contents("php://input"), true);
		$work_contractor_id = $data['work_contractor_id'];
		$temp_comp_id = $data['temp_comp_id'];
		$this->works_m->update_temporary_cont_sup($work_contractor_id,$temp_comp_id);
	}
	
	function remove_temporary_cont_sup(){
		$data = json_decode(file_get_contents("php://input"), true);
		$work_contractor_id = $data['work_contractor_id'];
		$this->works_m->remove_temporary_cont_sup($work_contractor_id);
	}
}