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
		$this->load->model('invoice_m');
		$this->load->module('wip');
		$this->load->model('wip_m');
		$this->load->module('project_schedule');
		$this->load->model('project_schedule_m');
		$this->load->module('induction_health_safety');
		$this->load->model('induction_health_safety_m');
		if(!$this->users->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;


	}
	

	public function index(){

		//$data['proj_t'] = $this->wip_m->display_all_wip_projects();
		$data['clients_list'] = $this->company_m->display_company_by_type(1);
		$data['users'] = $this->user_model->fetch_user();
		$data['pms'] = $this->user_model->fetch_pms_year(date("Y"));

		$this->users->_check_user_access('projects',1);

		$data['page_title'] = 'Project List';

		$data['main_content'] = 'projects_v';
		$data['screen'] = 'Projects';

		if(isset($_GET['fompj'])){
			$fompj['set_view_company_project'] = $_GET['fompj'];
			$this->session->set_userdata($fompj);
		}else{
			$fompj['set_view_company_project'] = $this->session->userdata('user_focus_company_id');
			$this->session->set_userdata($fompj);
		}


		$focus = $this->admin_m->fetch_all_company_focus(" AND `company_details`.`company_id` != '4' ");
		$data['focus'] = $focus->result();



			$comp_id = $this->session->userdata("set_view_company_project");


		//echo "<p><h1>".$comp_id."</h1></p>";




			if($comp_id != ''){
				$custom_fcom_query = " AND `project`.`focus_company_id` = '".$comp_id."' ";
			}else{

				$custom_fcom_query = "   ";
			}


			$q_comp_f = $this->projects_m->list_focus_company_main($custom_fcom_query);
			$focus_comp_main = array_shift($q_comp_f->result_array());



			if(isset($_GET['fompj'])){
				$data['focus_id_main_display'] = $_GET['fompj'];
			}else{
				$data['focus_id_main_display'] = $focus_comp_main['focus_company_id'];
			}




		$this->load->view('page', $data);

		
$user_id = $this->session->userdata('user_id');

 
		if($user_id == '72'){
			redirect('/dashboard');
		}

	}


	

	public function view_all(){

		//$data['proj_t'] = $this->wip_m->display_all_wip_projects();
		$data['clients_list'] = $this->company_m->display_company_by_type(1);
		$data['users'] = $this->user_model->fetch_user();
		$data['pms'] = $this->user_model->fetch_pms_year(date("Y"));

		$this->users->_check_user_access('projects',1);
		$data['main_content'] = 'projects_v_full';
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

	public function list_all_brands($form='table'){
		$brands_list = $this->projects_m->fetch_brands();
		
		if($form=='table'){
			foreach ($brands_list->result() as $row){
				echo '<tr><td><span id="brnd_name_'.$row->brand_id.'">'.$row->brand_name.'</span> <input type="text" id="edt_brnd_inpt_'.$row->brand_id.'"  style="width: 80%; display:none; float: left;" class="brand_name_edit form-control" value="'.$row->brand_name.'" />';
				echo '<button id="edt_'.$row->brand_id.'" class="btn pull-right btn-info btn-sm pad-5 m-left-5" style="padding-right: 1px;" onclick="update_brand(this)"><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></button>';
                echo '<button id="del_'.$row->brand_id.'" class="btn pull-right btn-danger btn-sm pad-5" onclick="delete_brand(this)"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></button>';
                if($row->has_brand_logo == 1){
                	echo '<button id="view_'.$row->brand_id.'" class="btn pull-right btn-success btn-sm pad-5 m-right-5" onclick="view_brand(this)" title = "View Brand Logo"><i class="fa fa-eye fa-lg" aria-hidden="true"></i></button>';
                }else{
                	echo '<button id="view_'.$row->brand_id.'" class="btn pull-right btn-success btn-sm pad-5 m-right-5" onclick="view_brand(this)" title = "Upload Brand Logo"><i class="fa fa-upload fa-lg" aria-hidden="true"></i></button>';
                }
                echo '<button id="save_'.$row->brand_id.'" class="btn pull-right btn-success btn-sm pad-5" onclick="edit_save(this)"  style=" display:none; "><i class="fa fa-save fa-lg" aria-hidden="true"></i></button></td></tr>';
			}
		}


		if($form=='select'){
			foreach ($brands_list->result() as $row){
				if( $row->brand_name != 'Other'){
					echo '<option value="'.$row->brand_id.'" >'.$row->brand_name.'</option>';
				}
			}
			echo '<option value="71" >Other</option>';
		}		
	}

	public function add_brand(){
		$brand_name = $_POST['ajax_var'];
		$brands_list = $this->projects_m->add_brand($brand_name);
		echo $this->list_all_brands();
	}

	public function update_brand(){
		$this->clear_apost();
		$brand_raw = explode('|',$_POST['ajax_var']);

		$brand_name = $brand_raw[0];
		$id = $brand_raw[1];

		$brands_list = $this->projects_m->update_brand($brand_name,$id);
	}

	public function delete_brand(){
		$brand_raw = explode('_',$_POST['ajax_var']);
		$brand_id = $brand_raw[1];
		$brands_list = $this->projects_m->delete_brand($brand_id);
	}
	
	public function read_csv_logs(){
		$time_Stamp = time();
		$filename = 'projects_removed_job_date_'.$time_Stamp.'.csv';
		$fetch_removed_jobdates_prj_q = $this->projects_m->fetch_removed_jobdates_prj_csv_report();

		$this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $data = $this->dbutil->csv_from_result($fetch_removed_jobdates_prj_q, $delimiter, $newline);
        force_download($filename, $data);
		
	}

	public function list_un_invoiced_rvw(){

		$static_defaults_q = $this->user_model->select_static_defaults();
		$static_defaults = array_shift($static_defaults_q->result() ) ;
		$day_revew_req = $static_defaults->prj_review_day;	
		$row_stat = '';	
		//$timestamp_day_revuew_req =  strtotime("$day_revew_req this week");

		$timestamp_day_revuew_req = (int)strtotime("$day_revew_req this week");
		$monday_revuew_req = (int)strtotime("Monday this week");
		$friday_revuew_req = (int)strtotime("Friday this week");
		$today_rvw_mrkr = (int)strtotime("Today");


		$timestamp_lwk_revuew_req = (int)strtotime("$day_revew_req last week");
		$timestamp_nxt_revuew_req = (int)strtotime("$day_revew_req next week");

		$total_project_value = 0;
		$total_outstading_value = 0;
		$content = '';
		$inv_date_type = '';
		$records_num = '';	
		$current_day = date('d/m/Y');

		$extra_query = '';
		if( $this->session->userdata('user_role_id') == 3 || $this->session->userdata('user_role_id') == 20   ){
			$user_pm_id = $this->session->userdata('user_id');
			$extra_query .= " AND `project`.`project_manager_id` = '$user_pm_id' ";
		}

		if( $this->session->userdata('user_role_id') == 2 || $this->session->userdata('user_role_id') == 7   ){
			$user_pa_id = $this->session->userdata('user_id');
			$extra_query .= " AND `project`.`project_admin_id` = '$user_pa_id' ";
		}

		$extra_query .= " AND `project`.`job_type` != 'Company' ";


		$order_q = " ORDER BY UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) ASC ";
		$has_where = " WHERE  `invoice`.`is_invoiced` = '0' AND `invoice`.`is_paid` = '0'     AND UNIX_TIMESTAMP( STR_TO_DATE(`invoice`.`invoice_date_req`, '%d/%m/%Y') ) <= UNIX_TIMESTAMP( STR_TO_DATE('$current_day', '%d/%m/%Y') ) 		$extra_query		";
		$table_q = $this->reports_m->select_list_invoice($has_where,'','','','','',$order_q);

		foreach ($table_q->result() as $row){

			if($row->label == 'VR'){
				$progress_order = 'Variation';
			}elseif($row->label != 'VR' && $row->label != ''){
				$progress_order = $row->label;
			}else{
				$progress_order = $row->invoice_project_id.'P'.$row->order_invoice;				
			}

			if($progress_order == 'Variation'){ 
				$project_total_percent = $row->variation_total;
			}else{

				if($row->is_paid == 1 ){
					$project_total_percent = $row->invoiced_amount;
				}else{
					$project_total_percent = $row->project_total * ($row->progress_percent/100);
				}
			}

			$project_total_percent = number_format($project_total_percent,2);


			if($timestamp_lwk_revuew_req < $today_rvw_mrkr &&   $today_rvw_mrkr <= $timestamp_day_revuew_req ){

				if( $timestamp_lwk_revuew_req < $row->unix_review_date && $row->unix_review_date <= $timestamp_day_revuew_req  ){
					$row_stat = 'posted_rev';
				} else{
					$row_stat = 'needed_rev';
				}

			}else{

				if( $timestamp_day_revuew_req <  $row->unix_review_date && $row->unix_review_date <= $timestamp_nxt_revuew_req  ){
					$row_stat = 'posted_rev';
				} else{
					$row_stat = 'needed_rev';
				}

			}

			echo '<tr class="prj_rvw_rw '.$row_stat.'" id="'.$row->invoice_project_id.'-uninvoiced_prj_view" >';
			echo '<td><strong>'.$row->date_site_finish.'</strong></td>';
			echo '<td><strong>'.$row->company_name.'</strong></td>';

			//echo '<td><strong class="prj_id_rvw">'.$row->invoice_project_id.'</strong> - '.$row->project_name.'</td>';

			echo '<td>
			<i class="fa fa-book  btn btn-sm btn-success view_notes_prjrvw" style="padding: 4px;"></i>
				<a href="'.base_url().'projects/update_project_details/'.$row->invoice_project_id.'?status_rvwprj=uninvoiced&pmr='.$row->project_manager_id.'" ><strong class="prj_id_rvw">'.$row->invoice_project_id.'</strong> - '.$row->project_name.'</a>
			</td>';


			echo '<td><strong class="unset">'.$row->invoice_date_req.'</strong></td>';
			echo '<td>'.$progress_order.'</td><td>'.number_format($row->progress_percent,2).'</td><td>'.$project_total_percent.'</td><td class="rw_pm_slct hide">pm_'.$row->project_manager_id.'</td></tr>';
		}	
	}

	public function display_all_projects($stat,$is_wpev = 0,$custom_q = ''){
		$admin_defaults = $this->admin_m->fetch_admin_defaults(); //1
		foreach ($admin_defaults->result() as $row){
			$data['unaccepted_date_categories'] = $row->unaccepted_date_categories;
			$data['unaccepted_no_days'] = $row->unaccepted_no_days;
		}

		$q_warranty_categories = $this->projects_m->fetch_warranty_categories();
		$data['warranty_categories'] = array_shift($q_warranty_categories->result_array());

		$raw_warranty_cat = implode(",",$data['warranty_categories']);
		$replaced_warranty_cat = str_replace(",", "','", $raw_warranty_cat)."'";
		$format_warranty_cat = substr_replace($replaced_warranty_cat, "", 0, 2);

		if($stat == 'unset' || $stat == 'quote'){
			$extra_query = ' AND `project`.`job_date` = ""  ';

		}elseif($stat == 'invoiced'){
			$extra_query = 'AND `project`.`job_date` != ""  AND `project`.`is_paid` = "0"   '; 

		}elseif($stat == 'paid'){
			$extra_query = ' AND `project`.`is_paid` = "1"  ';
		}elseif($stat == 'all'){
			$extra_query = '   ';
		}elseif($stat == 'warranty'){
			$extra_query = 'AND `project`.`is_paid` = "1" AND `project`.`job_category` IN ('.$format_warranty_cat.')';
		}else{
			$extra_query = 'AND `project`.`job_date` != ""  AND `project`.`is_paid` = "0"   '; 
		}


		$data['is_wpev'] = $is_wpev;
			$extra_order = '';

		if($is_wpev > 0){
			$extra_order = ' ORDER BY `unix_date_site_finish`  ASC ';
 

			$extra_query .= " AND `project`.`job_type` != 'Company' ";


			if( $this->session->userdata('user_role_id') == 3 || $this->session->userdata('user_role_id') == 20   ){
				$user_pm_id = $this->session->userdata('user_id');
				$extra_query .= " AND `project`.`project_manager_id` = '$user_pm_id' ";
			}

			if( $this->session->userdata('user_role_id') == 2 || $this->session->userdata('user_role_id') == 7   ){






				$user_pa_id = $this->session->userdata('user_id');

				$pa_data = $this->projects_m->fetch_pa_pm_assignment($user_pa_id);
				$assignment = array_shift($pa_data->result_array());

				$prime_pm = $assignment['project_manager_primary_id'];
				$group_pm = explode(',', $assignment['project_manager_ids']);


				$extra_query .= " AND (  `project`.`project_manager_id` = '$prime_pm'  ";

					foreach ($group_pm as $key => $value) {
						$extra_query .= " OR `project`.`project_manager_id` = '$value' ";
					}
					
				$extra_query .= " OR `project`.`project_admin_id` = '$user_pa_id' ";

				$extra_query .= " ) ";
 



			}
		

		
		}

		if($is_wpev > 0 && $stat == 'quote'){
			$extra_order = " ORDER BY    UNIX_TIMESTAMP( STR_TO_DATE(`project`.`quote_deadline_date`, '%d/%m/%Y') ) ASC ";
		}






			$comp_id = $this->session->userdata("set_view_company_project");
			$custom_fcom_query = " AND `project`.`focus_company_id` = '".$comp_id."' ";
			$q_comp_f = $this->projects_m->list_focus_company_main($custom_fcom_query);
			$focus_comp_main = array_shift($q_comp_f->result_array());


			$base_f_comp_id = $focus_comp_main['focus_company_id'];


	//		echo "<tr><td>$base_f_comp_id</td></tr>";

			if(isset($base_f_comp_id) && $base_f_comp_id != ''){
				$extra_query .= " AND `project`.`focus_company_id` = '".$base_f_comp_id."' ";
			}
/*
			$extra_query .= " AND `project`.`focus_company_id` = '".$base_f_comp_id."' ";



				$sub_con = $this->projects_m->select_particular_sub_contractor($job_catId);
					$con_arr = array_shift($sub_con->result_array());
					$conType = $con_arr['job_sub_cat'];

*/
			



		$data['proj_t'] = $this->projects_m->display_all_projects($extra_query,$extra_order);
		$this->load->view('tables_projects',$data);
	}

	public function removeWork($id){
		$removeId = $_POST['postVal'];
		$this->projects_m->removeWork($removeId);
	}

	public function display_all_works($project_id){
		$project_work_list = $this->projects_m->display_all_works($project_id);

		if($project_work_list->num_rows > 0){
			foreach ($project_work_list->result() as $row){
				$job_catId = $row->job_sub_cat_id;

				if($row->contractor_type == 'Sub-Contractor'){
					$sub_con = $this->projects_m->select_particular_sub_contractor($job_catId);
					$con_arr = array_shift($sub_con->result_array());
					$conType = $con_arr['job_sub_cat'];


				}elseif($row->contractor_type == 'Contractor'){
					$cont = $this->projects_m->select_particular_contractor($job_catId);
					$con_arr = array_shift($cont->result_array());
					$conType = $con_arr['job_category'];

				}else{
					$suppl = $this->projects_m->select_particular_supplier($job_catId);
					$con_arr = array_shift($suppl->result_array());
					$conType = $con_arr['supplier_cat_name'];
				}


				if($row->contractor_type == 'Sub-Contractor' || $row->contractor_type == 'Contractor'){
					$work_type_id = 2;
				}else{
					$work_type_id = 3;
				}


				echo'<tr ><td ><a href="#">'.$conType.'</a></td><td class=""><span class="set-company" id="'.$work_type_id.'" data-toggle="collapse" data-target=".select-company">';
				if($row->company_id==0){echo'Please select';}else{echo'Selected';}
				echo'</span></td><td class="">'.$row->cpo_number.'</td><td></td><td class="">'.$row->work_estimate.'</td><td class="">xx</td><td><div class="block btn btn-xs btn-danger remove-job" id="'.$row->works_id.'"> <strong>x</strong> </div></td></tr>';
			}
		}else{
			echo'<tr ><td colspan="7" align="center">No Works Yet</td></tr>';
			
		}		

	}

	public function list_users_removed_jobdate(){
		$fetch_removed_jobdates_prj_q = $this->projects_m->fetch_users_remove_job_date();
		foreach ($fetch_removed_jobdates_prj_q->result() as $prj_log){
			echo '<option value="'.strtolower(str_replace(' ','_', $prj_log->user_name_log)).'" >'.$prj_log->user_first_name.'</option>';
		}
	}

	public function list_removed_jobdate_prj($limit=''){
		$fetch_removed_jobdates_prj_q = $this->projects_m->fetch_removed_jobdates_prj($limit);
		foreach ($fetch_removed_jobdates_prj_q->result() as $prj_log){
			echo '<li class="list_rem_user_'.strtolower(str_replace(' ','_', $prj_log->user_name_log)).'" ><div><a href="'.base_url().'projects/view/'.$prj_log->project_id.'" class="news-item-title"><strong>'.$prj_log->project_id.'</strong></a> '.$prj_log->project_name.'  <p class="news-item-preview  tooltip-enabled" title="" data-html="true" data-placement="left" data-original-title="'.$prj_log->company_name.'<br />'.$prj_log->pm_name.'<br />$'.number_format($prj_log->project_total+$prj_log->variation_total,2).'<br />'.$prj_log->date_site_commencement.' - '.$prj_log->date_site_finish.'">  <i class="fa fa-calendar"></i>  '.$prj_log->user_name_log.'  </p></div></li>';
		}
	}

	public function list_users_pr_images(){
		$fetch_project_manager_q = $this->user_model->fetch_user_by_role_combine(3, 20);
		foreach ($fetch_project_manager_q->result() as $row){

			if ($row->user_id != '29'){
				echo '<option value="'.strtolower(str_replace(' ','_', $row->user_first_name.' '.$row->user_last_name)).'" >'.$row->user_first_name.'</option>';
			}
		}
	}

	public function list_recent_pr_images($limit=''){
		$fetch_recent_pr_images_q = $this->projects_m->fetch_recent_pr_images($limit);
		foreach ($fetch_recent_pr_images_q->result() as $row){
			echo '<li class="list_pr_img_'.strtolower(str_replace(' ','_', $row->pm_name)).'" ><div><a href="'.base_url().'projects/progress_reports/'.$row->project_id.'" class="news-item-title"><strong>'.$row->project_id.'</strong></a> '.$row->project_name.'  <br /><i class="fa fa-user-circle"></i> '.$row->pm_name.'<br /> <i class="fa fa-users"></i>  '.$row->pa_name.'  </div><br/ ></li>';
		}
	}

	public function fetch_shopping_center_state_sub($suburb='',$state='',$direct=0){

		if($direct == 0){
	      	$data = explode('|',$_POST['ajax_var']);
	      	$suburb = $data['0'];
	      	$state = $data['6'];
		}else{
	      	$suburb_arr = explode('|',$suburb);
	      	$suburb = $suburb_arr['0'];

	      	$state_arr = explode('|',$state);
	      	$state = $state_arr['3'];
		}

      	$suburb = strtoupper($suburb);
		$shopping_center_list = $this->projects_m->get_list_shopping_centers($state,$suburb);

		echo '<option value="" disabled="disabled" selected="selected">Choose a Shopping Center</option>';

      	foreach ($shopping_center_list->result() as $row){
      		echo '<option value="'.$row->detail_address_id.'">'.ucwords(strtolower($row->shopping_center_brand_name)).'</option>';
      	}
	}



	public function hasQuote($project_id){
		$project_work_list = $this->projects_m->display_all_works($project_id);
		if($project_work_list->num_rows > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	public function list_invoiced_items($project_id,$project_total,$variation_total){
		$this->invoice->list_invoiced_items($project_id,$project_total,$variation_total);
	}

	public function induction_project_exempted($project_id){
		$admin_defaults = $this->admin_m->fetch_admin_defaults(1);
		foreach ($admin_defaults->result() as $row){
			$induction_work_value = $row->induction_work_value;
			$induction_project_value = $row->induction_project_value;
			$induction_categories = $row->induction_categories;
		}

		$proj_q = $this->projects_m->fetch_complete_project_details($project_id);
		foreach ($proj_q->result() as $row) {	
			$job_category = $row->job_category;
			$project_value = $row->project_total;
			$project_estimate = $row->budget_estimate_total;
			$client_id = $row->client_id;
			$address_id = $row->address_id;
		}

		$q_client_company = $this->company_m->display_company_detail_by_id($client_id);
		$client_company = array_shift($q_client_company->result_array());

		$query_client_address = $this->company_m->fetch_complete_detail_address($address_id);
		$temp_data = array_shift($query_client_address->result_array());

		$project_is_exempted = $this->admin_m->project_is_exempted_induction($project_id);

		if($project_is_exempted == 0){
			$induction_categories_arr = explode (",", $induction_categories);
			$arr_num = count($induction_categories_arr);
			$x = 0;
			$exist = 0;
			while($x < $arr_num){
				if($job_category == $induction_categories_arr[$x]){
					$exist = 1;
				}
				$x++;
			}

			if($exist == 1){
				$post_code_is_exempted = $this->admin_m->postcode_excempted($temp_data['postcode']);
				if($post_code_is_exempted == 1){
					$induction_exempted = 1;
				}else{
					if($project_estimate >= $induction_project_value){
						$induction_exempted = 0;
					}else{
						if($project_estimate >= $induction_project_value){
							$induction_exempted = 0;
						}else{
							$induction_exempted = 1;
						}
					}
				}
			}else{
				$induction_exempted = 1;
			}
		}else{
			$induction_exempted = $project_is_exempted;
		}
		
		return $induction_exempted;
	}
	
	public function view(){


$user_id = $this->session->userdata('user_id');

 
		if($user_id == '72'){
			redirect('/dashboard');
		}



		$user_role_id = $this->session->userdata('user_role_id');

		if($user_role_id == '15'){
			redirect('/projects');
		}

		$project_id = preg_replace("/[^0-9,.]/", "", $this->uri->segment(3));


		$admin_defaults = $this->admin_m->fetch_admin_defaults(1);
		foreach ($admin_defaults->result() as $row){
			$unaccepted_no_days = $row->unaccepted_no_days;
			$induction_work_value = $row->induction_work_value;
			$induction_commencement_date = $row->induction_commencement_date;
			// $induction_project_value = $row->induction_project_value;
			// $induction_categories = $row->induction_categories;
		}

		$restricted_cat = 0;
		$admin_cat = 0;
		$proj_q = $this->projects_m->fetch_complete_project_details($project_id);
		foreach ($proj_q->result() as $row) {
			$start_date = $row->date_site_commencement;
			if($row->job_date !== '' && $row->is_paid !== 0){
				$restricted_cat = 1;
				$has_unaccepted_date = $this->projects_m->has_unaccepted_date($project_id);
				if($has_unaccepted_date == 1){
					$this->projects_m->remove_unaccepted_date($project_id);
				}
			}else{
				$job_category = $row->job_category;
				$admin_defaults = $this->admin_m->fetch_admin_defaults(1);
				foreach ($admin_defaults->result() as $row){
					$unaccepted_date_categories= $row->unaccepted_date_categories;
					$job_category_arr = explode(",",$unaccepted_date_categories);
					foreach ($job_category_arr as &$value) {
						if($value == $job_category){
							$admin_cat = 1;
						}
					}
				}

				if($admin_cat !== 1){
					$has_unaccepted_date = $this->projects_m->has_unaccepted_date($project_id);
					if($has_unaccepted_date == 0){
						
						if($start_date !== ""){
							$start_date_arr = explode('/',$start_date);
							$s_date_day = $start_date_arr[0];
							$s_date_month = $start_date_arr[1];
							$s_date_year = $start_date_arr[2];
							$start_date = $s_date_year.'-'.$s_date_month.'-'.$s_date_day;

							$today = date('Y-m-d');
							$unaccepteddate =strtotime ( '-'.$unaccepted_no_days.' day' , strtotime ( $start_date ) ) ;
							$unaccepteddate = date ( 'Y-m-d' , $unaccepteddate );

							$unaccepteddate_arr = explode('-',$unaccepteddate);
							$u_date_day = $unaccepteddate_arr[2];
							$u_date_month = $unaccepteddate_arr[1];
							$u_date_year = $unaccepteddate_arr[0];
							$unaccepted_ddate = $u_date_day.'/'.$u_date_month.'/'.$u_date_year;


							
							if($unaccepteddate < $today){
								$this->projects_m->insert_unaccepted_date($project_id,$unaccepted_ddate);
							}
						}
					}
				}
			}
		}




		$q_proj = $this->projects_m->fetch_complete_project_details($project_id);
		if($q_proj->num_rows > 0){
			$data = array_shift($q_proj->result_array());


			$data['job_date_history'] = '';

			if($data['job_date'] == ''){
				$histry_jb_raw = $this->projects_m->fetch_job_date_history($project_id);
				$job_date_history = array_shift($histry_jb_raw->result_array());
				$data['job_date_history'] = $job_date_history['actions'];
			}

			

			$admin_defaults_raw = $this->admin_m->latest_system_default($data['defaults_id']);
			$admin_defaults = array_shift($admin_defaults_raw->result_array());

			$markup_raw = $this->admin_m->fetch_markup($admin_defaults['markup_id']);
			$markup_defaults = array_shift($markup_raw->result_array());

			$q_project_notes = $this->projects_m->fetch_project_notes($data['notes_id']);
			$project_notes = array_shift($q_project_notes->result_array());
			$data['project_comments'] = $project_notes['comments'];

			$q_focus_company = $this->company_m->display_company_detail_by_id($data['focus_company_id']);
			$focus_company = array_shift($q_focus_company->result_array());
			$data['focus_company_id'] = $focus_company['company_id'];
			$data['focus_company_name'] = $focus_company['company_name'];

			$q_client_company = $this->company_m->display_company_detail_by_id($data['client_id']);
			$client_company = array_shift($q_client_company->result_array());
			$data['client_company_id'] = $client_company['company_id'];
			$data['client_company_name'] = $client_company['company_name'];

			$q_fetch_contact_details_primary = $this->company_m->fetch_contact_details_primary($client_company['company_id']);
			$company_contact_details_primary_detail = array_shift($q_fetch_contact_details_primary->result_array());

			$data['company_contact_details_area_code'] = $company_contact_details_primary_detail['area_code'];
			$data['company_contact_details_office_number'] = $company_contact_details_primary_detail['office_number'];
			$data['company_contact_details_direct_number'] = $company_contact_details_primary_detail['direct_number'];
			$data['company_contact_details_mobile_number'] = $company_contact_details_primary_detail['mobile_number'];
			$data['company_contact_details_after_hours'] = $company_contact_details_primary_detail['after_hours'];
			$data['company_contact_details_general_email'] = $company_contact_details_primary_detail['general_email'];
			$data['company_contact_details_direct'] = $company_contact_details_primary_detail['direct'];
			$data['company_contact_details_accounts'] = $company_contact_details_primary_detail['accounts'];
			$data['company_contact_details_maintenance'] = $company_contact_details_primary_detail['maintenance'];


			$query_client_address = $this->company_m->fetch_complete_detail_address($client_company['address_id']);
			$temp_data = array_shift($query_client_address->result_array());
			$data['query_client_address_postcode'] = $temp_data['postcode'];
			$data['query_client_address_suburb'] = ucwords(strtolower($temp_data['suburb']));
			$data['query_client_address_po_box'] = $temp_data['po_box'];
			$data['query_client_address_street'] = ucwords(strtolower($temp_data['street']));
			$data['query_client_address_unit_level'] = ucwords(strtolower($temp_data['unit_level']));
			$data['query_client_address_unit_number'] = $temp_data['unit_number'];
			$data['query_client_address_state'] = $temp_data['name'];


			$q_contact_person = $this->company_m->fetch_all_contact_persons($data['primary_contact_person_id']);
			$contact_person = array_shift($q_contact_person->result_array());
			$data['contact_person_id'] = $contact_person['contact_person_id'];
			$data['contact_person_fname'] = $contact_person['first_name'];
			$data['contact_person_lname'] = $contact_person['last_name'];

			$q_fetch_phone = $this->company_m->fetch_phone($contact_person['contact_number_id']);
			$contact_person_phone = array_shift($q_fetch_phone->result_array());

			

			if($contact_person_phone['office_number'] != ''): 
				$data['contact_person_phone_office'] = $contact_person_phone['area_code'].' '.$contact_person_phone['office_number'];
			else: $data['contact_person_phone_office'] = '';
			endif;


			if($contact_person_phone['direct_number'] != ''): 
				$data['contact_person_phone_direct'] = $contact_person_phone['area_code'].' '.$contact_person_phone['direct_number'];
			else: $data['contact_person_phone_direct'] = '';
			endif;

			if($contact_person_phone['mobile_number'] != ''):
				$data['contact_person_phone_mobile'] = $contact_person_phone['mobile_number'];
			else: $data['contact_person_phone_mobile'] = '';
			endif;

			if($contact_person_phone['after_hours'] != ''):
				$data['contact_person_phone_afterhours'] = $contact_person_phone['area_code'].' '.$contact_person_phone['after_hours'];
			else: $data['contact_person_phone_afterhours'] = '';
			endif;

			$query_address= $this->company_m->fetch_complete_detail_address($data['address_id']);
			$temp_data = array_shift($query_address->result_array());
			$data['postcode'] = $temp_data['postcode'];
			$data['suburb'] = ucwords(strtolower($temp_data['suburb']));
			$data['po_box'] = $temp_data['po_box'];
			$data['street'] = ucwords(strtolower($temp_data['street']));
			$data['unit_level'] = ucwords(strtolower($temp_data['unit_level']));
			$data['unit_number'] = $temp_data['unit_number'];
			$data['state'] = $temp_data['name'];
		// $data['address_id'] = $data['address_id'];

			$data['shortname'] = $temp_data['shortname'];
			$data['state_id'] =  $temp_data['state_id'];
			$data['phone_area_code'] = $temp_data['phone_area_code'];	

			$p_query_address = $this->company_m->fetch_complete_detail_address($data['invoice_address_id']);
			$p_temp_data = array_shift($p_query_address->result_array());
			$data['i_po_box'] = $p_temp_data['po_box'];
			$data['i_unit_level'] = ucwords(strtolower($p_temp_data['unit_level']));
			$data['i_unit_number'] = $p_temp_data['unit_number'];
			$data['i_street'] = ucwords(strtolower($p_temp_data['street']));
			$data['i_suburb'] = ucwords(strtolower($p_temp_data['suburb']));
			$data['i_state'] = $p_temp_data['name'];
			$data['i_postcode'] = $p_temp_data['postcode'];
//		$data['postal_address_id'] = $company_detail['postal_address_id'];

			$data['i_shortname'] = $p_temp_data['shortname'];
			$data['i_state_id'] =  $p_temp_data['state_id'];
			$data['i_phone_area_code'] = $p_temp_data['phone_area_code'];

			$q_project_manager = $this->user_model->fetch_user($data['project_manager_id']);
			$project_manager = array_shift($q_project_manager->result_array());
			$data['pm_user_id'] = $project_manager['user_id'];
			$data['pm_user_first_name'] = $project_manager['user_first_name'];
			$data['pm_user_last_name'] = $project_manager['user_last_name'];

			$q_project_admin = $this->user_model->fetch_user($data['project_admin_id']);
			$project_admin = array_shift($q_project_admin->result_array());
			$data['pa_user_id'] = $project_admin['user_id'];
			$data['pa_user_first_name'] = $project_admin['user_first_name'];
			$data['pa_user_last_name'] = $project_admin['user_last_name'];

			$q_project_estiamator_id = $this->user_model->fetch_user($data['project_estiamator_id']);
			$project_estiamator = array_shift($q_project_estiamator_id->result_array());
			$data['pe_user_id'] = $project_estiamator['user_id'];
			$data['pe_user_first_name'] = $project_estiamator['user_first_name'];
			$data['pe_user_last_name'] = $project_estiamator['user_last_name'];


			$q_project_cc_pm = $this->user_model->fetch_user($data['client_contact_person_id']);
			$project_cc_pm = array_shift($q_project_cc_pm->result_array());
			$data['cc_pm_user_id'] =  $project_cc_pm['user_id'];
			$data['cc_pm_user_first_name'] = $project_cc_pm['user_first_name'];
			$data['cc_pm_user_last_name'] = $project_cc_pm['user_last_name'];

			$lead_hand_id = $this->projects_m->get_project_sched_values($project_id);
			$ps_row1 = $lead_hand_id->row();

			if (!empty($ps_row1)){

				if ($ps_row1->leading_hand_id == 0){

					$q_proj_sched_details = $this->project_schedule_m->fetch_project_schedule($project_id);
					$data['proj_sched_details'] = $q_proj_sched_details->row();

					$q_proj_sched_details2 = $this->project_schedule_m->fetch_project_schedule($project_id);
					$proj_sched_details2 = $q_proj_sched_details2->row();

					$manual_const_details = $this->projects_m->get_manual_const($proj_sched_details2->project_schedule_id);
					$project_lead_hand = array_shift($manual_const_details->result_array());

					$data['lead_hand_user_id'] =  '0';
					$data['lead_hand_user_first_name'] = $project_lead_hand['lh_name'];

				} else {
					$q_project_lead_hand = $this->user_model->fetch_user($ps_row1->leading_hand_id);
					$project_lead_hand = array_shift($q_project_lead_hand->result_array());
					$data['lead_hand_user_id'] =  $project_lead_hand['user_id'];
					$data['lead_hand_user_first_name'] = $project_lead_hand['user_first_name'];
					$data['lead_hand_user_last_name'] = $project_lead_hand['user_last_name'];
				}
			}

			if($data['job_category'] == 'Company'){
				$pg_markup = array(0,0,0,0);
				$data['min_markup'] = $pg_markup[1];
			}else{
				$pg_markup_raw = $this->projects->fetch_mark_up_by($data['job_category'] , $markup_defaults['markup_id']);
				$pg_markup = explode('|',$pg_markup_raw);
				$data['min_markup'] = $pg_markup[1];
			}

// Joinery	
	            $joinery_user_id = $data['joinery_selected_sender'];	
	            $q_project_joinery= $this->user_model->fetch_user($joinery_user_id);	
	            $project_joinery = array_shift($q_project_joinery->result_array());		
	            //$data['lead_hand_user_id'] = $project_lead_hand['user_id'];		
	            $data['joinery_user_first_name'] = $project_joinery['user_first_name'];		
	            $data['joinery_user_last_name'] = $project_joinery['user_last_name'];		
// Joinery

			$shopping_center_q = $this->projects_m->select_shopping_center($data['address_id']);
			$shopping_center = array_shift($shopping_center_q->result_array());

			$data['shopping_center_id'] = $shopping_center['shopping_center_id'];
			$data['shopping_center_brand_name'] = $shopping_center['shopping_center_brand_name'];
			$data['shopping_common_name'] = $shopping_center['common_name'];


			$applied_admin_settings_raw = $this->display_project_applied_defaults($project_id);

			$project_totals_arr = $this->fetch_project_totals($project_id);

			$data = array_merge($data, $project_totals_arr);

			$data = array_merge($data, $applied_admin_settings_raw);

			$progress_report_defaults = $this->projects_m->fetch_progress_report_defaults($data['address_id']);
			$data['progress_report_defaults'] = array_shift($progress_report_defaults->result_array());

			$data['page_title'] = $data['project_id'].' - '.$data['project_name'];

			$data['restricted_cat'] = $restricted_cat;
			$data['main_content'] = 'projects_view';
			$data['screen'] = 'Project Details';

			$data['induction_exempted'] = $this->induction_project_exempted($data['project_id']);
			$data['induction_work_value'] = $induction_work_value;
			$video_generated = $this->induction_health_safety_m->fetch_induction_videos_generated($project_id);

			$data['induction_commencement_date'] = $induction_commencement_date;
			$data['video_generated'] = $video_generated;

			$this->load->view('page', $data);
		}else{
			redirect('/projects');
		}
	}

	public function fetch_project_total_values(){
		$project_id = $_POST['proj_id'];
		$project_totals_arr = $this->fetch_project_totals($project_id);
		$proj_default_arr = $this->display_project_applied_defaults($project_id);
		$admin_gst_rate = $proj_default_arr['admin_gst_rate'];
		$proj_ex_gst = round($project_totals_arr['final_total_quoted'],2);
		$proj_ex_gst_formatted = number_format($project_totals_arr['final_total_quoted'],2);
		$proj_inc_gst = number_format($proj_ex_gst+($proj_ex_gst*($admin_gst_rate/100)),2);
		//echo $project_id;
		echo $proj_ex_gst_formatted."|".$proj_inc_gst;
	}

	public function find_contact_person($comp_id=''){
		$post_ajax_arr = array();

		if(isset($_POST['ajax_var'])){
			$post_ajax_arr = explode('|',$_POST['ajax_var']);
		}elseif($comp_id!=''){
			$post_ajax_arr[1] = $comp_id;
		}else{
			return false;
		}		

		$contact_persons_q = $this->projects_m->fectch_contact_person_by_company_id($post_ajax_arr['1']);

		foreach ($contact_persons_q->result() as $row) {
			echo '<option value="'.$row->contact_person_id.'" '.($row->is_primary == 1 ? 'selected="selected"' : '').'  >'.$row->first_name.' '.$row->last_name.'</option>';
		}
	}

	public function fetch_address_company_invoice(){
		//$post_ajax_id = $_POST['ajax_var_i'];
		$post_ajax_arr = explode('|',$_POST['ajax_var']);

		$query_company_details = $this->company_m->fetch_all_company($post_ajax_arr[1]);
		$temp_company_details = array_shift($query_company_details->result_array());

		$query_complete_detail_address = $this->company_m->fetch_complete_detail_address($temp_company_details['address_id']);
		$temp_complete_detail_address = array_shift($query_complete_detail_address->result_array());


		echo ucwords(strtolower($temp_complete_detail_address['unit_level'])).'|';
		echo $temp_complete_detail_address['unit_number'].'|';
		echo ucwords(strtolower($temp_complete_detail_address['street'])).'|';
		echo $temp_complete_detail_address['po_box'].'|';

		echo $temp_complete_detail_address['shortname'].'|';
		echo $temp_complete_detail_address['name'].'|';
		echo $temp_complete_detail_address['phone_area_code'].'|';
		echo $temp_complete_detail_address['id'].'|';

		echo ucfirst(strtolower($temp_complete_detail_address['suburb'])).'|';
		echo $temp_complete_detail_address['postcode'];

	}

	public function set_jurisdiction($focus_id=''){
		$all_aud_states = $this->company_m->fetch_all_states();
		$data['all_aud_states'] = $all_aud_states->result();
		echo "<option value=''>Choose a State</option>";

		if(isset($_POST['ajax_var']) ){
			$post_ajax_arr = explode('|',$_POST['ajax_var']);

			$focus_id = $post_ajax_arr[0];
			$admin_company_details = $this->admin_m->fetch_single_company_focus($focus_id);
			$focus_detail_data = array_shift($admin_company_details->result_array() );

			$jurisdiction = explode(',', $focus_detail_data['admin_jurisdiction_state_ids']);


			foreach ($jurisdiction  as $jur_key => $jur_value): 
				foreach ($data['all_aud_states']  as $key => $value):
					if( $jur_value == $value->id ){ echo '<option value="'.$value->shortname.'|'.$value->name.'|'.$value->phone_area_code.'|'.$value->id.'">'.$value->name.'</option>';	}
				endforeach;
			endforeach;

		}elseif ($focus_id!='') {

			$focus_id = $focus_id;
			$admin_company_details = $this->admin_m->fetch_single_company_focus($focus_id);
			$focus_detail_data = array_shift($admin_company_details->result_array() );

			$jurisdiction = explode(',', $focus_detail_data['admin_jurisdiction_state_ids']);


			foreach ($jurisdiction  as $jur_key => $jur_value): 
				foreach ($data['all_aud_states']  as $key => $value):
					if( $jur_value == $value->id ){ echo '<option value="'.$value->shortname.'|'.$value->name.'|'.$value->phone_area_code.'|'.$value->id.'">'.$value->name.'</option>';	}
				endforeach;
			endforeach;
		}else{
			foreach ($data['all_aud_states']  as $key => $value):
				echo '<option value="'.$value->shortname.'|'.$value->name.'|'.$value->phone_area_code.'|'.$value->id.'">'.$value->name.'</option>';
			endforeach;
		}
	}

	public function set_jurisdiction_shoping_center($focus_id_set=''){
		$shopping_center_arr = array();

		if($focus_id_set==''){
			$focus_id = $_POST['ajax_var'];
		}else{
			$focus_id = $focus_id_set;
		}

		$focus_raw = $this->admin_m->fetch_single_company_focus($focus_id);
		$focus = array_shift($focus_raw->result_array() );

		$focus_jurisdiction =  explode(',', $focus['admin_jurisdiction_state_ids']);

		foreach ($focus_jurisdiction  as $jur_key => $state):
			$shopping_center = $this->projects_m->fetch_shopping_center_by_state($state);
			foreach ($shopping_center->result_array() as $row):
				if(!in_array($row['shopping_center_brand_name'], $shopping_center_arr)){
					array_push($shopping_center_arr,$row['shopping_center_brand_name']);
				}				
			endforeach;
		endforeach;

		foreach ($shopping_center_arr as $row => $value){
			echo '<option value="'.$value.'">'.$value.'</option>';
		}
	}


	public function set_jurisdiction_shoping_center_by_name_and_sate(){
		$shopping_center_arr = array();

		$focusdata_arr = explode('|', $_POST['ajax_var']);
		$focus_id = $focusdata_arr[0];
		$shopping_center_brand = $focusdata_arr[1];


		$focus_raw = $this->admin_m->fetch_single_company_focus($focus_id);
		$focus = array_shift($focus_raw->result_array() );

		$focus_jurisdiction =  explode(',', $focus['admin_jurisdiction_state_ids']);

		foreach ($focus_jurisdiction  as $jur_key => $state):
			$shopping_center = $this->projects_m->fetch_shopping_center_by_name_and_sate($shopping_center_brand,$state);
			foreach ($shopping_center->result_array() as $row):
				if(!in_array($row['suburb'], $shopping_center_arr)){
					$shopping_center_arr[$row['detail_address_id']] = $row['suburb'];
				}				
			endforeach;
		endforeach;

		foreach ($shopping_center_arr as $row => $value){
			echo '<option value="'.$row.'">'.$this->company->cap_first_word($value).'</option>';
		}
	}

	
	public function supplier_cat_list(){
		$suppier_cat_type = $this->projects_m->display_all_supplier_types('');		
		foreach ($suppier_cat_type->result() as $row){
			echo '<option value="sup|'.$row->supplier_cat_name.'|'.$row->supplier_cat_id.'">'.$row->supplier_cat_name.'</option>';
		}		
	}
	
	public function job_cat_list(){
		$jobs_cat_type = $this->projects_m->display_all_job_category_type('');		
		foreach ($jobs_cat_type->result() as $row){
			echo '<option value="job|'.$row->job_category.'|'.$row->job_category_id.'">'.$row->job_category.'</option>';
		}		
	}
	
	public function sub_job_cat_list(){
		$job_cat_id = $_POST['postVal'];
		$jobs_cat_type = $this->projects_m->display_all_job_sub_category_type($job_cat_id);

		if( $jobs_cat_type->num_rows()>0){
			foreach ($jobs_cat_type->result() as $row){
				echo '<option value="sub|'.$row->job_sub_cat.'|'.$row->job_sub_cat_id.'">'.$row->job_sub_cat.'</option>';
			}			
		}else{
			echo '<option value="">Work Description</option>';
		}
	}

	public function fetch_mark_up_by($job_cat='',$markup_id=''){
	 
		if($job_cat == ''){
			$job_cat = $_POST['job_cat'];

			$defaults_raw = $this->admin_m->latest_system_default();
			$defaults = $defaults_raw->result();
			$markup_id = $defaults[0]->markup_id;

			if($job_cat == 'Company'){
				echo '0|0|0';
			}else{
				$mark_up_q = $this->projects_m->fetch_mark_up_by_type($job_cat,$markup_id);
				$mark_up = array_shift($mark_up_q->result_array());
				echo implode("|",$mark_up);
			}

		}else{
			if($job_cat == 'Company'){
				return '0|0|0';
			}else{ 
				$mark_up_q = $this->projects_m->fetch_mark_up_by_type($job_cat,$markup_id);
				$mark_up = array_shift($mark_up_q->result_array());
				return implode("|",$mark_up);
			}
		}
 


	}


	public function set_date_review(){
		$project_id = $this->security->xss_clean($this->input->post('ajax_var'));
		$rev_date  = date("d/m/Y");
		$this->projects_m->set_project_date_review($project_id,$rev_date);	

		$static_defaults_q = $this->user_model->select_static_defaults();
		$static_defaults = array_shift($static_defaults_q->result() ) ;

		$day_revew_req = $static_defaults->prj_review_day;
		$timestamp_day_revuew_req = (int)strtotime("$day_revew_req this week");
		$date_day_revuew_req = date('d/m/Y',$timestamp_day_revuew_req);
		$this->projects_m->update_set_wip_rvw($project_id,$date_day_revuew_req,$rev_date);

		$monday_revuew_req = (int)strtotime("Monday this week");
		$friday_revuew_req = (int)strtotime("Friday this week");


$timestamp_lwk_revuew_req = (int)strtotime("$day_revew_req last week");
$timestamp_nxt_revuew_req = (int)strtotime("$day_revew_req next week");

		$today_rvw_mrkr = (int)strtotime("Today");

		/*
		if($today_rvw_mrkr > $timestamp_day_revuew_req){  
		// baddd

			if( $timestamp_day_revuew_req <  $today_rvw_mrkr && $today_rvw_mrkr <= $friday_revuew_req  ){
				$this->projects_m->prj_rvw_late($project_id,$date_day_revuew_req );
			} 
		}*/


		if($today_rvw_mrkr > $timestamp_day_revuew_req && $today_rvw_mrkr < $timestamp_nxt_revuew_req ){  // baddd
			if( $timestamp_day_revuew_req <  $today_rvw_mrkr && $today_rvw_mrkr <= $friday_revuew_req  ){
				$this->projects_m->prj_rvw_late($project_id,$date_day_revuew_req );
			}
		}

	}

	public function clear_apost(){
		foreach ($_POST as $key => $value) {
			$_POST[$key] = str_replace("'","&apos;",$value);
		}
	}
	
	public function add(){
		//var_dump($_POST);

		$this->users->_check_user_access('projects',2);

		if($this->session->userdata('company_project') == 1){
			redirect('/projects/add_company_project/');
		}

		$defaults_raw = $this->admin_m->latest_system_default();
		$defaults = $defaults_raw->result();
		$defaults_id = $defaults[0]->defaults_id;
		$admin_default_id = $defaults[0]->admin_default_id;

		//$comp_list = $this->company_m->fetch_all_company_type_id('1');

		$admin_defaults_q = $this->admin_m->fetch_admin_defaults($admin_default_id); 
		$admin_defaults = array_shift($admin_defaults_q->result_array());
		$days_quote_deadline = $admin_defaults['days_quote_deadline'];

	//	var_dump($defaults); 


		$all_company_list = $this->company_m->fetch_all_company_type_id('1');
		if($all_company_list->num_rows > 0){
			$data['all_company_list'] = $all_company_list->result();
		}

		$data['main_content'] = 'projects_add';

		$data['page_title'] = 'Add New Project';

		$all_aud_states = $this->company_m->fetch_all_states();
		$data['all_aud_states'] = $all_aud_states->result();

		$focus = $this->admin_m->fetch_all_company_focus();
		$data['focus'] = $focus->result();

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$data['project_manager'] = $project_manager->result();

		$account_manager = $this->user_model->fetch_user_by_role(20);
		$data['account_manager'] = $account_manager->result();

		$maintenance_administrator = $this->user_model->fetch_user_by_role(7);
		$data['maintenance_administrator'] = $maintenance_administrator->result();

		$project_administrator = $this->user_model->fetch_user_by_role(2);
		$data['project_administrator'] = $project_administrator->result();


		$shopping_center = $this->projects_m->fetch_shopping_center();
		$data['shopping_center'] = $shopping_center->result();

		$estimator = $this->user_model->fetch_user_by_role(8);
		$data['estimator'] = $estimator->result();

		$lead_hand = $this->user_model->fetch_user_by_role(15);
		$data['lead_hand'] = $lead_hand->result();

		$data['all_projects'] = $this->projects_m->display_all_projects();

		$company_project_item = array();
		foreach ($data['all_projects']->result_array() as $row){
			$company_project_item[$row['company_id']] = $row['company_name'];
		}
		asort($company_project_item);
		$data['all_company_project'] = $company_project_item;

		$q_warranty_categories = $this->projects_m->fetch_warranty_categories();
		$data['warranty_categories'] = array_shift($q_warranty_categories->result_array());


		$this->form_validation->set_rules('project_name', 'Project Name','trim|required|xss_clean|max_length[35]');
		$this->form_validation->set_rules('site_start', 'Site Start','trim|required|xss_clean');
		$this->form_validation->set_rules('site_finish', 'Site Finish','trim|required|xss_clean');
		$this->form_validation->set_rules('job_type', 'Job Type','trim|required|xss_clean');
		$this->form_validation->set_rules('brand_name', 'Brand','trim|required|xss_clean');
		$this->form_validation->set_rules('job_category', 'Job Category','trim|required|xss_clean');
		$this->form_validation->set_rules('project_date', 'Project Date','trim|required|xss_clean');


		if( $this->input->post('is_shopping_center') != 1){
			$this->form_validation->set_rules('street', 'Site Street','trim|required|xss_clean');
			$this->form_validation->set_rules('suburb_a', 'Site Project Address Suburb','trim|required|xss_clean');
			$this->form_validation->set_rules('state_a', 'Site State','trim|required|xss_clean');
			$this->form_validation->set_rules('postcode_a', 'Site Postcode','trim|required|xss_clean');
		}else{
			$this->form_validation->set_rules('shop_tenancy_number', 'Site Shop/Tenancy Number','trim|required|xss_clean');
			$this->form_validation->set_rules('brand_shopping_center', 'Site Brand/Shopping Center','trim|required|xss_clean');
		}		

		$this->form_validation->set_rules('street_b', 'Invoice Street','trim|required|xss_clean');
		$this->form_validation->set_rules('suburb_b', 'Invoice Address Suburb','trim|required|xss_clean');
		$this->form_validation->set_rules('state_b', 'Invoice State','trim|required|xss_clean');
		$this->form_validation->set_rules('postcode_b', 'Invoice Postcode','trim|required|xss_clean');



		$this->form_validation->set_rules('project_manager', 'Project Manager','trim|required|xss_clean');
		$this->form_validation->set_rules('project_admin', 'Project Admin','trim|required|xss_clean');
		$this->form_validation->set_rules('estimator', 'Estimator','trim|required|xss_clean');
		$this->form_validation->set_rules('company_prg', 'Company Client','trim|required|xss_clean');
		$this->form_validation->set_rules('contact_person', 'Contact Person','trim|required|xss_clean');
		$this->form_validation->set_rules('install_hrs', 'Site Hours','trim|xss_clean');
		$this->form_validation->set_rules('project_total', 'Project Estimate','trim|required|xss_clean');
		$this->form_validation->set_rules('labour_hrs_estimate', 'Site Labour Estimate','trim|required|xss_clean');
		$this->form_validation->set_rules('project_markup', 'Project Markup','trim|required|xss_clean');
		$this->form_validation->set_rules('leading_hand', 'Leading Hand','trim|xss_clean');
		$this->form_validation->set_rules('proj_joinery_user', 'Joinery Personel','trim|xss_clean');



		if( $this->input->post('job_category') != 'Maintenance' && $this->input->post('job_category') != 'Minor Works' && $this->input->post('job_category') != 'Strip Out' && $this->input->post('job_category') != 'Design Works' ){
			$this->form_validation->set_rules('project_area', 'Project Area','trim|required|xss_clean|greater_than[0]');
		}else{
			$this->form_validation->set_rules('project_area', 'Project Area','trim|xss_clean');	
		}


		if ($this->input->post('leading_hand') == 0 && $this->input->post('leading_hand') != ''){
			$this->form_validation->set_rules('lh_name', 'Leading Hand Full Name','trim|required|xss_clean');
			$this->form_validation->set_rules('lh_mobile_no', 'Leading Hand Mobile No.','trim|required|xss_clean');
		}


	$project_area = $this->input->post('project_area');

	$rounded_project_area = round($project_area,2);



		//echo $this->company->cap_first_word($this->company->if_set($this->input->post('project_name', true)));
		//var_dump($_POST);


		if($this->form_validation->run() === false){
			$this->clear_apost();
			$data['error' ] = validation_errors();
			$this->load->view('page', $data);
			//valid_input_simple
		}elseif( $this->input->post('job_category') != 'Maintenance' && $this->input->post('job_category') != 'Minor Works' && $this->input->post('job_category') != 'Strip Out' && $this->input->post('job_category') != 'Design Works' && $project_area < 10  ){

			$this->clear_apost();
			$data['error' ] = 'Invalid value for project area.';
			$this->load->view('page', $data);

		}else{
			$this->clear_apost();

			$focus_id = $this->input->post('focus');
			$project_name = $this->input->post('project_name');
			$client_po = $this->input->post('client_po');
			$job_type = $this->input->post('job_type');
			$job_category = $this->input->post('job_category');
			$project_date = $this->input->post('project_date');
			$job_date = $this->input->post('job_date');
			$site_start = $this->input->post('site_start');
			$site_finish = $this->input->post('site_finish');
			$brand_name = $this->input->post('brand_name');

			if($job_date != ''){
				$is_wip = 1;
			}else{
				$is_wip = 0;
			}

			$data['unit_level'] = $this->company->if_set($this->input->post('unit_level', true));
			$data['unit_number'] = $this->company->if_set($this->input->post('unit_number', true));
			$data['street'] = $this->company->cap_first_word($this->company->if_set($this->input->post('street', true)));
			$data['postcode_a'] = $this->company->if_set($this->input->post('postcode_a', true));

			$data['pobox'] = $this->company->if_set($this->input->post('pobox', true));
			$data['unit_level_b'] = $this->company->if_set($this->input->post('unit_level_b', true));
			$data['number_b'] = $this->company->if_set($this->input->post('number_b', true));			
			$data['street_b'] = $this->company->cap_first_word($this->company->if_set($this->input->post('street_b', true)));
			$data['postcode_b'] = $this->company->if_set($this->input->post('postcode_b', true));		

			$state_a_arr = explode('|', $this->input->post('state_a', true));
			$num = count($state_a_arr);
			if($num > 1){
				$data['state_a'] = $state_a_arr[3];
			}

			$suburb_a_ar = explode('|',$this->company->if_set($this->input->post('suburb_a', true)));
			$data['suburb_a'] = strtoupper($suburb_a_ar[0]);

			$state_b_arr = explode('|', $this->input->post('state_b', true));
			$data['state_b'] = $state_b_arr[3];

			$suburb_b_ar = explode('|',$this->company->if_set($this->input->post('suburb_b', true)));
			$data['suburb_b'] = strtoupper($suburb_b_ar[0]);

			$project_manager_id = $this->input->post('project_manager');
			$project_admin_id = $this->input->post('project_admin');
			$project_estiamator_id = $this->input->post('estimator');
			$project_leading_hand_id = $this->input->post('leading_hand');
			$proj_joinery_user = $this->input->post('proj_joinery_user');

			if ($project_leading_hand_id == 0){
				$project_leading_hand_full_name = $this->input->post('lh_name');
				$project_leading_hand_mobile_no = $this->input->post('lh_mobile_no');
			}

			$company_prg_arr =  explode('|',$this->input->post('company_prg'));
			$client_id = $company_prg_arr[1];
			$company_name = $company_prg_arr[0];

/*
			$sub_client_arr =  explode('|',$this->input->post('sub_client'));
			$sub_client_id = $sub_client_arr[1];

			if($sub_client_id == ''){
				$sub_client_id = $client_id;
			}
*/
		//	$sub_client_id = 0;

			
			$contact_person_id = $this->input->post('contact_person');
			$project_total = str_replace (',','', $this->input->post('project_total') );

			$install_hrs = $this->input->post('install_hrs');

			$project_markup = $this->input->post('project_markup');

			$project_area = $this->input->post('project_area');
			$comments = $this->input->post('comments');
			$project_status_id = 1;

			$copy_work_project_id = $this->input->post('copy_work_project_id');
			$include_work_estimate = $this->input->post('include_work_estimate');

			$shop_tenancy_number = $this->input->post('shop_tenancy_number'); 

			$focus_user_id = $this->session->userdata('user_id');


			$labour_hrs_estimate = $this->input->post('labour_hrs_estimate', true);

			$is_shopping_center = $this->input->post('is_shopping_center');

			$project_notes_id = $this->company_m->insert_notes($comments);

			$is_double_time = $this->input->post('is_double_time');
			$cc_pm_raw = $this->input->post('client_contact_project_manager');

			$cc_pm = ($cc_pm_raw == 0 ? $project_manager_id : $cc_pm_raw);

			if($is_shopping_center == 1){
				$site_address_id =  $this->input->post('brand_shopping_center');
			}else{

				$general_address_id_result_a = $this->company_m->fetch_address_general_by('postcode-suburb',$data['postcode_a'],$data['suburb_a']);
				foreach ($general_address_id_result_a->result() as $general_address_id_a){
					$general_address_a = $general_address_id_a->general_address_id;
				}
				$site_address_id = $this->company_m->insert_address_detail($data['street'],$general_address_a,$data['unit_level'],$data['unit_number']);
			}			

			$general_address_id_result_b = $this->company_m->fetch_address_general_by('postcode-suburb',$data['postcode_b'],$data['suburb_b']);
			foreach ($general_address_id_result_b->result() as $general_address_id_b){
				$general_address_b = $general_address_id_b->general_address_id;
			}

			$invoice_address_id = $this->company_m->insert_address_detail($data['street_b'],$general_address_b,$data['unit_level_b'],$data['number_b'],$data['pobox']);


			$formated_start_date = str_replace("/","-",$site_start);
			$date_quote_deadline =  date('d/m/Y', strtotime("$formated_start_date -$days_quote_deadline days"));

			$check_date_quote_deadline = date("w", strtotime( str_replace("/","-",$date_quote_deadline) ));

			if($check_date_quote_deadline == 0){
				$new_days_quote_deadline = $days_quote_deadline + 2;
				$date_quote_deadline =  date('d/m/Y', strtotime("$formated_start_date -$new_days_quote_deadline days"));
			}

			if($check_date_quote_deadline == 6){
				$new_days_quote_deadline = $days_quote_deadline + 1;
				$date_quote_deadline =  date('d/m/Y', strtotime("$formated_start_date -$new_days_quote_deadline days"));
			}

			$inserted_project_id = $this->projects_m->insert_new_project($project_name, $project_date, $contact_person_id, $project_total, $job_date,$brand_name, $is_wip, $client_po, $site_start, $site_finish, $job_category, $job_type, $focus_user_id ,$focus_id, $project_manager_id, $project_admin_id, $project_estiamator_id,$site_address_id, $invoice_address_id, $project_notes_id, $project_markup,$project_status_id, $client_id, $install_hrs, $project_area, $is_double_time, $labour_hrs_estimate, $shop_tenancy_number,$defaults_id,$cc_pm,$date_quote_deadline,$proj_joinery_user);


			$static_defaults_q = $this->user_model->select_static_defaults();
			$static_defaults = array_shift($static_defaults_q->result() ) ;
			$day_revew_req = $static_defaults->prj_review_day;
			$current_dead_line = date('d/m/Y', strtotime("$day_revew_req this week") );

			$this->projects_m->insert_wip_rvw($inserted_project_id, $current_dead_line, $project_date);

			if( strpos(implode(",",$data['warranty_categories']), $job_category) !== false ):
				$this->projects->set_warranty_date_after_paid($inserted_project_id);
			endif;

			$new_project_sched_id = $this->projects_m->insert_new_project_sched_for_pr($inserted_project_id, $project_leading_hand_id);

			if ($project_leading_hand_id == 0){
				$this->projects_m->insert_manual_lead($new_project_sched_id, ucwords($project_leading_hand_full_name), $project_leading_hand_mobile_no);
			}

//================= For Maintenance Site sheet ===============
			if($job_category == 'Maintenance'):
				$site_cont_person = $this->input->post('site_cont_person');
				$site_cont_number = $this->input->post('site_cont_number');
				$site_cont_mobile = $this->input->post('site_cont_mobile');
				$site_cont_email = $this->input->post('site_cont_email');

				$this->projects_m->insert_project_site_contact($inserted_project_id, $site_cont_person, $site_cont_number, $site_cont_mobile, $site_cont_email);
			
				date_default_timezone_set("Australia/Perth");
				$user_id = $this->session->userdata('user_id');
				$date = date("d/m/Y");
				$time = date("H:i:s");
				$type = "INSERT";
				$actions = "Insert project #".$inserted_project_id." site contacts with Contact Person: ".$site_cont_person.", Contact Number: ".$site_cont_number.", Mobile Number: ".$site_cont_mobile.", Email: ".$site_cont_email;
				$this->user_model->insert_user_log($user_id,$date,$time,$actions,$project_id,$type);
			endif;
//================= For Maintenance Site sheet ===============


			if($install_hrs != '' && $install_hrs > 0){
				$prj_install_hrs = $install_hrs;
			}else{
				$prj_install_hrs = $labour_hrs_estimate;
			}

			$this->insert_cost_total($inserted_project_id,$prj_install_hrs,$is_double_time);

			$this->session->set_flashdata('curr_tab', 'project-details');			

			$works_from_selected_project = $this->works_m->display_all_works($copy_work_project_id);

			foreach ($works_from_selected_project->result() as $row) {
				$work_notes_raw = $this->projects_m->fetch_project_notes($row->note_id);
				$work_notes = array_shift($work_notes_raw->result_array());

				$note_id = $this->works_m->insert_work_notes($work_notes['comments'],$work_notes['notes']);

				if($include_work_estimate == 1){
					$work_estimate = $row->work_estimate;
					$total_work_quote = $work_estimate + ($work_estimate*($project_markup/100));
				}else{
					$work_estimate = 0;
					$total_work_quote = $work_estimate + ($work_estimate*($project_markup/100));
				}				

				$work_id = $this->works_m->insert_new_works($row->contractor_type,$row->work_con_sup_id,$row->other_work_desc,$project_markup,$note_id,$row->is_deliver_office,'',$row->work_reply_date,$inserted_project_id,'0',$work_estimate,$total_work_quote);

				$works_joinery_raw = $this->works_m->display_all_works_joinery($row->works_id);
				foreach ($works_joinery_raw->result() as $joinery_row) {

					$joinery_notes_raw = $this->projects_m->fetch_project_notes($joinery_row->note_id);
					$joinery_notes = array_shift($joinery_notes_raw->result_array());

					$joinery_note_id = $this->works_m->insert_work_notes($joinery_notes['comments'],$joinery_notes['notes']);
					$added_new_joinery_id = $this->works_m->insert_works_joinery($work_id,$joinery_row->joinery_id,$project_markup,$joinery_note_id,$joinery_row->is_deliver_office,'',$joinery_row->work_reply_date);

				}

				$considerations_raw = $this->works_m->fetch_considerations($row->works_id);
				$consdr = array_shift($considerations_raw->result_array());
				$this->works_m->insert_considerations($work_id,0, $consdr['site_inspection_req'], $consdr['special_conditions'], $consdr['additional_visit_req'], $consdr['operate_during_install'], $consdr['week_work'], $consdr['weekend_work'], $consdr['after_hours_work'], $consdr['new_premises'], $consdr['free_access'], $consdr['other'], $consdr['otherdesc']);

				$this->session->set_flashdata('curr_tab', 'works');
			}
// ========================= EMAIL Notification for PA for INDUCTION ==================
			$is_exempted = $this->induction_project_exempted($inserted_project_id);
			
			if($is_exempted == 0):

				$proj_q = $this->projects_m->select_particular_project($inserted_project_id);
				foreach ($proj_q->result_array() as $row){
					$project_admin_id = $row['project_admin_id'];
				}

				$users_q = $this->user_model->fetch_user($project_admin_id);
				foreach ($users_q->result_array() as $users_row){
					$pm_name = $users_row['user_first_name']." ".$users_row['user_last_name'];
					$pm_email_id = $users_row['user_email_id'];
					$email_q = $this->company_m->fetch_email($pm_email_id);
					foreach ($email_q->result_array() as $email_row){
						$pm_email = $email_row['general_email'];
					}
				}

				$user_id = $this->session->userdata('user_id');
				$users_q = $this->user_model->fetch_user($user_id);
				foreach ($users_q->result_array() as $users_row){
					$user_name = $users_row['user_first_name']." ".$users_row['user_last_name'];
					$user_email_id = $users_row['user_email_id'];
					$email_q = $this->company_m->fetch_email($user_email_id);
					foreach ($email_q->result_array() as $email_row){
						$sender_user_email = $email_row['general_email'];
					}
				}

				$this->induction_health_safety_m->set_inductions_as_saved($inserted_project_id);
				$sender_name = $user_name;
				$email_from = $sender_user_email;
				$email_to = $pm_email;
				$subject = "Project: ".$inserted_project_id." ".$project_name.",".$company_name."is required for Induction";
				$message = "The new project created number: ".$inserted_project_id.", needs to have induction slides created. Please see: https://sojourn.focusshopfit.com.au/induction_health_safety/induction_slide_editor_view?project_id=".$inserted_project_id;

				require_once('PHPMailer/class.phpmailer.php');
				require_once('PHPMailer/PHPMailerAutoload.php');


				$mail = new phpmailer(true);
				$mail->host = "sojourn-focusshopfit-com-au.mail.protection.outlook.com";
				$mail->port = 587;
				$mail->setFrom($email_from, $sender_name);
		
				//$mail->setfrom('userconf@sojourn.focusshopfit.com.au', 'name');
				$mail->setFrom($email_from, $sender_name);

				//$mail->addreplyto('userconf@sojourn.focusshopfit.com.au', 'name');
				$mail->addReplyTo($email_from);
		
				$mail->addAddress($email_to);
				//$mail->addCC($email_cc);
				$mail->addBCC('mark.obis2012@gmail.com');
				$mail->addBCC('ian@focusshopfit.com.au');

				$mail->smtpdebug = 2;
				$mail->ishtml(true);

				$mail->Subject = $subject;
				$mail->Body    = $message;

				if(!$mail->send()) {
					//return 'Message could not be sent.'.' Mailer Error: ' . $mail->ErrorInfo;
				} else {
					//return "Email Send Successfully";
				}
			endif;
// ========================= EMAIL Notification for PA for INDUCTION ==================
			redirect('/projects/view/'.$inserted_project_id);
		}
	}



	
	public function add_company_project(){
		
		$data['page_title'] = 'Add New Company Project';

		if(  $this->session->userdata('is_admin') == 1 || $this->session->userdata('company_project') == 1):

		// var_dump($_POST); 

		$defaults_raw = $this->admin_m->latest_system_default();
		$defaults = $defaults_raw->result();
		$defaults_id = $defaults[0]->defaults_id;
		$admin_default_id = $defaults[0]->admin_default_id;

		//$comp_list = $this->company_m->fetch_all_company_type_id('1');

		$all_company_list = $this->company_m->fetch_all_company_type_id('1');
		if($all_company_list->num_rows > 0){
			$data['all_company_list'] = $all_company_list->result();
		}

		$data['main_content'] = 'projects_add_for_company';

 

		$admin_defaults_q = $this->admin_m->fetch_admin_defaults($admin_default_id); 
		$admin_defaults = array_shift($admin_defaults_q->result_array());
		$days_quote_deadline = $admin_defaults['days_quote_deadline'];





		$all_aud_states = $this->company_m->fetch_all_states();
		$data['all_aud_states'] = $all_aud_states->result();

		$focus = $this->admin_m->fetch_all_company_focus();
		$data['focus'] = $focus->result();

		$project_manager = $this->user_model->fetch_user_by_role(3);
		$data['project_manager'] = $project_manager->result();

		$maintenance_administrator = $this->user_model->fetch_user_by_role(7);
		$data['maintenance_administrator'] = $maintenance_administrator->result();

		$project_administrator = $this->user_model->fetch_user_by_role(2);
		$data['project_administrator'] = $project_administrator->result();


		$shopping_center = $this->projects_m->fetch_shopping_center();
		$data['shopping_center'] = $shopping_center->result();

		$estimator = $this->user_model->fetch_user_by_role(8);
		$data['estimator'] = $estimator->result();


		$data['all_projects'] = $this->projects_m->display_all_projects();

		$company_project_item = array();
		foreach ($data['all_projects']->result_array() as $row){
			$company_project_item[$row['company_id']] = $row['company_name'];
		}
		asort($company_project_item);
		$data['all_company_project'] = $company_project_item;

		$this->form_validation->set_rules('project_name', 'Project Name','trim|required|xss_clean|max_length[35]');
		$this->form_validation->set_rules('site_start', 'Site Start','trim|required|xss_clean');
		$this->form_validation->set_rules('site_finish', 'Site Finish','trim|required|xss_clean');
		$this->form_validation->set_rules('job_type', 'Job Type','trim|required|xss_clean');
		$this->form_validation->set_rules('job_category', 'Job Category','trim|required|xss_clean');
		$this->form_validation->set_rules('project_date', 'Project Date','trim|required|xss_clean');

		$this->form_validation->set_rules('project_manager', 'Project Manager','trim|required|xss_clean');
		$this->form_validation->set_rules('project_admin', 'Project Admin','trim|required|xss_clean');
		$this->form_validation->set_rules('estimator', 'Estimator','trim|required|xss_clean');
		$this->form_validation->set_rules('company_prg', 'Company Client','trim|required|xss_clean');
		$this->form_validation->set_rules('contact_person', 'Contact Person','trim|required|xss_clean');
		$this->form_validation->set_rules('install_hrs', 'Site Hours','trim|xss_clean');
		$this->form_validation->set_rules('project_total', 'Project Estimate','trim|required|xss_clean');
		$this->form_validation->set_rules('labour_hrs_estimate', 'Site Labour Estimate','trim|required|xss_clean');
		$this->form_validation->set_rules('project_markup', 'Project Markup','trim|required|xss_clean');

		if( $this->input->post('job_category') == 'Maintenance' ||  $this->input->post('job_category') == 'Kiosk' || $this->input->post('job_category') == 'Minor Works' || $this->input->post('job_category') == 'Strip Out'|| $this->input->post('job_category') == 'Design Works'  ){
			$this->form_validation->set_rules('project_area', 'Project Area','trim|xss_clean');
		}else{
			$this->form_validation->set_rules('project_area', 'Project Area','trim|required|xss_clean');
		}



		
		//echo $this->company->cap_first_word($this->company->if_set($this->input->post('project_name', true)));
		//var_dump($_POST);
		if($this->form_validation->run() === false){
 
		
 



			$this->clear_apost();
			$data['error' ] = validation_errors();
			$this->load->view('page', $data);
			//valid_input_simple
		}else{
			$this->clear_apost();

			$focus_id = $this->input->post('focus');
			$project_name = $this->input->post('project_name');
			$client_po = $this->input->post('client_po');
			$job_type = $this->input->post('job_type');
			$job_category = $this->input->post('job_category');
			$project_date = $this->input->post('project_date');
			$job_date = $this->input->post('job_date');
			$site_start = $this->input->post('site_start');
			$site_finish = $this->input->post('site_finish');

			if($job_date != ''){
				$is_wip = 1;
			}else{
				$is_wip = 0;
			}

			$data['unit_level'] = $this->company->if_set($this->input->post('unit_level', true));
			$data['unit_number'] = $this->company->if_set($this->input->post('unit_number', true));
			$data['street'] = $this->company->cap_first_word($this->company->if_set($this->input->post('street', true)));
			$data['postcode_a'] = $this->company->if_set($this->input->post('postcode_a', true));

			$data['pobox'] = $this->company->if_set($this->input->post('pobox', true));
			$data['unit_level_b'] = $this->company->if_set($this->input->post('unit_level_b', true));
			$data['number_b'] = $this->company->if_set($this->input->post('number_b', true));			
			$data['street_b'] = $this->company->cap_first_word($this->company->if_set($this->input->post('street_b', true)));
			$data['postcode_b'] = $this->company->if_set($this->input->post('postcode_b', true));		
/*
			$state_a_arr = explode('|', $this->input->post('state_a', true));
			$data['state_a'] = $state_a_arr[3];

			$suburb_a_ar = explode('|',$this->company->if_set($this->input->post('suburb_a', true)));
			$data['suburb_a'] = strtoupper($suburb_a_ar[0]);

			$state_b_arr = explode('|', $this->input->post('state_b', true));
			$data['state_b'] = $state_b_arr[3];

			$suburb_b_ar = explode('|',$this->company->if_set($this->input->post('suburb_b', true)));
			$data['suburb_b'] = strtoupper($suburb_b_ar[0]);
*/
			$project_manager_id = $this->input->post('project_manager');
			$project_admin_id = $this->input->post('project_admin');
			$project_estiamator_id = $this->input->post('estimator');
			
			$contact_person_id = $this->input->post('contact_person');
			$project_total = str_replace (',','', $this->input->post('project_total') );

			$install_hrs = $this->input->post('install_hrs');

			$project_markup = $this->input->post('project_markup');

			$project_area = $this->input->post('project_area');
			$comments = $this->input->post('comments');
			$project_status_id = 1;

			$copy_work_project_id = $this->input->post('copy_work_project_id');
			$include_work_estimate = $this->input->post('include_work_estimate');

			$shop_tenancy_number = $this->input->post('shop_tenancy_number'); 

			$focus_user_id = $this->session->userdata('user_id');


			$labour_hrs_estimate = $this->input->post('labour_hrs_estimate', true);

			$is_shopping_center = $this->input->post('is_shopping_center');

			$project_notes_id = $this->company_m->insert_notes($comments);

			$is_double_time = $this->input->post('is_double_time');

 


			$company_prg_arr =  explode('|',$this->input->post('company_prg'));


			$client_id = $company_prg_arr[1];

			$client_details = $this->company_m->fetch_company_details($client_id);
			foreach ($client_details->result() as $data){
				$site_address_id = $data->address_id;
				$invoice_address_id = $data->address_id;
			}


			$formated_start_date = str_replace("/","-",$site_start);
			$date_quote_deadline =  date('d/m/Y', strtotime("$formated_start_date -$days_quote_deadline days"));

			$inserted_project_id = $this->projects_m->insert_new_project($project_name, $project_date, $contact_person_id, $project_total, $job_date, '71',$is_wip, $client_po, $site_start, $site_finish, $job_category, $job_type, $focus_user_id ,$focus_id, $project_manager_id, $project_admin_id, $project_estiamator_id,$site_address_id, $invoice_address_id, $project_notes_id, $project_markup,$project_status_id, $client_id, $install_hrs, $project_area, $is_double_time, $labour_hrs_estimate, $shop_tenancy_number,$defaults_id,$project_manager_id, $date_quote_deadline);

			$project_leading_hand_id = 0;
			$this->projects_m->insert_new_project_sched_for_pr($inserted_project_id, $project_leading_hand_id);
			
			if($install_hrs != '' && $install_hrs > 0){
				$prj_install_hrs = $install_hrs;
			}else{
				$prj_install_hrs = $labour_hrs_estimate;
			}

			$this->insert_cost_total($inserted_project_id,$prj_install_hrs,$is_double_time);

			$this->session->set_flashdata('curr_tab', 'project-details');			
			redirect('/projects/view/'.$inserted_project_id);
		}

		else:

			redirect('/projects/');


		endif;
		 
	}





	public function insert_cost_total($inserted_project_id,$prj_install_hrs,$is_double_time){


		$project_details_raw = $this->projects_m->fetch_project_details($inserted_project_id);		
		$project_details = array_shift($project_details_raw->result_array());

		$system_default_raw = $this->admin_m->latest_system_default($project_details['defaults_id']);
		$system_default = array_shift($system_default_raw->result_array());


		$site_costs_raw = $this->admin_m->fetch_site_costs($system_default['site_cost_id']);
		$site_costs = array_shift($site_costs_raw->result_array());

		if($is_double_time > 0){
			$install_cost_total = $site_costs['total_double_time']*$prj_install_hrs;
		}else{
			$install_cost_total = $site_costs['total_amalgamated_rate']*$prj_install_hrs;
		}
		$this->projects_m->insert_cost_total($inserted_project_id,$install_cost_total);
	}


	public function update_install_cost_total($inserted_project_id,$prj_install_hrs,$is_double_time){


		$project_details_raw = $this->projects_m->fetch_project_details($inserted_project_id);		
		$project_details = array_shift($project_details_raw->result_array());

		$system_default_raw = $this->admin_m->latest_system_default($project_details['defaults_id']);
		$system_default = array_shift($system_default_raw->result_array());



		$site_costs_raw = $this->admin_m->fetch_site_costs($system_default['site_cost_id']);
		$site_costs = array_shift($site_costs_raw->result_array());

		if($is_double_time > 0){
			$install_cost_total = $site_costs['total_double_time']*$prj_install_hrs;
		}else{
			$install_cost_total = $site_costs['total_amalgamated_rate']*$prj_install_hrs;
		}
		$this->projects_m->update_install_cost_total($inserted_project_id,$install_cost_total);
	}



	public function fetch_project_totals($project_id){

		$project_details_raw = $this->projects_m->fetch_project_details($project_id);		
		$project_details = array_shift($project_details_raw->result_array());

		$system_default_raw = $this->admin_m->latest_system_default($project_details['defaults_id']);
		$system_default = array_shift($system_default_raw->result_array());

		$markup_raw = $this->admin_m->fetch_markup($system_default['markup_id']);
		$markup = array_shift($markup_raw->result_array());

		$site_costs_raw = $this->admin_m->fetch_site_costs($system_default['site_cost_id']);
		$site_costs = array_shift($site_costs_raw->result_array());

		$labour_cost_raw = $this->admin_m->fetch_labour_cost($system_default['labour_cost_id']);
		$labour_cost = array_shift($labour_cost_raw->result_array());

		$admin_defaults_raw = $this->admin_m->fetch_admin_defaults($system_default['admin_default_id']);
		$admin_defaults = array_shift($admin_defaults_raw->result_array());

		$project_cost_total_raw = $this->projects_m->get_project_cost_total($project_id);		
		$project_cost_total = array_shift($project_cost_total_raw->result_array());

		$leave_percentage = ($labour_cost['total_leave_days']/$labour_cost['total_work_days'])*100;
		$labour_cost['leave_percentage'] = round($leave_percentage, 2);
		$labour_cost_grand_total = $labour_cost['leave_percentage']+$labour_cost['superannuation']+$labour_cost['workers_compensation']+$labour_cost['payroll_tax']+$labour_cost['leave_loading']+$labour_cost['other']; 

		$payroll_tax = $labour_cost['payroll_tax'];
		$rate = $site_costs['rate'];

		$gp_on_cost_total_hr = $rate+(($rate*$labour_cost_grand_total)/100);
		$gp_on_cost_time_half_hr = $gp_on_cost_total_hr + ((0.5*$rate) + (((0.5*$rate)*$payroll_tax )/100));
		$gp_on_cost_time_double_hr = $gp_on_cost_total_hr + ($rate+(($rate*$payroll_tax)/100));

		//$gp_amalgamated_rate = ($admin_defaults['labor_split_time_and_half']/100)*$gp_on_cost_time_half_hr + ($admin_defaults['labor_split_double_time']/100)*$gp_on_cost_time_double_hr + ($admin_defaults['labor_split_standard']/100)*$gp_on_cost_total_hr;

		$gp_data_arr = $this->admin->get_double_amalgated_rate($site_costs,$labour_cost,$admin_defaults);

		if($project_details['install_time_hrs'] == 0){
			$total_install_rate = 0;
		}else{
			$total_install_rate = $project_cost_total['install_cost_total'];
		}

		//$total_install_rate = $project_cost_total['install_cost_total'];
		


		$total_estimated_cost = $total_install_rate + ($total_install_rate * ($admin_defaults['installation_labour_mark_up']/100));
		$total_quoted_cost = $total_estimated_cost + ($total_estimated_cost * ($project_details['markup']/100));

		if($project_id == '35951'){
			$total_quoted_cost = $total_quoted_cost - 0.87;
		}


		if($project_details['is_double_time']>0){
			$actual_cost_total = ($site_costs['rate']*2);
			//$gp_cost_actual = $gp_on_cost_time_half_hr;
			$gp_cost_actual = $gp_data_arr['gp_on_cost_time_double_hr'];
		}else{
			$actual_cost_total = $gp_data_arr['gp_amalgamated_rate'];
			$gp_cost_actual = $gp_data_arr['gp_amalgamated_rate'];
		}

		$prj_install_hrs = $project_details['install_time_hrs'];
/*
		if($project_details['install_time_hrs'] > 0 && $project_details['install_time_hrs'] != '' ){
			$prj_install_hrs = $project_details['install_time_hrs'];
		}else{
			//$prj_install_hrs = $project_details['labour_hrs_estimate'];
			$prj_install_hrs = 0;
		}
*/
		//echo "<script>alert('".$total_estimated_cost."');</script>";
		//echo "<script>alert('".$prj_install_hrs."');</script>";

		$actual_project_cost = ($actual_cost_total * $prj_install_hrs) + $project_cost_total['work_estimated_total'];

		/* gP variables*/
		//$gp_actual_cost_total = $gp_cost_actual + (($gp_cost_actual*$labour_cost_grand_total) / 100);
		$gp_actual_project_cost = ($gp_cost_actual * $prj_install_hrs) + $project_cost_total['work_estimated_total'];
		/* gP variables*/

		$final_total_cost = $project_cost_total['work_price_total'] + $total_install_rate;
		$final_total_estimated = $project_cost_total['work_estimated_total'] + $total_estimated_cost;
		$final_total_quoted = $project_cost_total['work_quoted_total'] + $total_quoted_cost;


/*
		if($project_details['install_time_hrs'] == 0.00 && $project_cost_total['work_estimated_total'] == 0.00){
			$actual_project_cost = $project_details['labour_hrs_estimate'] * $actual_cost_total;
			$final_total_quoted = $project_details['budget_estimate_total'];
		}

		if($project_details['install_time_hrs'] > 0.00 && $project_cost_total['work_estimated_total'] == 0.00){
			$final_total_quoted = $actual_project_cost;
		}
*/
		//echo "<script>alert('".$gp_actual_project_cost."');</script>";
if($final_total_quoted== 0 && $gp_actual_project_cost == 0){
$gp = 0;
}else{
		$gp = round(($final_total_quoted - $gp_actual_project_cost) / $final_total_quoted,4);

}


		if($project_id == '36207'){
			$final_total_quoted = $final_total_quoted + 0.22;
			$this->projects_m->update_project_total($project_id,$final_total_quoted);
		}

		if($project_id == '36813'){
			$final_total_quoted = $final_total_quoted - 0.33;
			$this->projects_m->update_project_total($project_id,$final_total_quoted);
		}

		if($project_id == 36561){
			$final_total_quoted = $final_total_quoted - 0.12;
		}

		if($project_id == 37258){
			$final_total_quoted = $final_total_quoted + 0.32;
		}

		if($project_id == 36364){
			$final_total_quoted = $final_total_quoted - 0.2;
		}

		if($project_id == 39826){
			$final_total_quoted = $final_total_quoted + 0.24;
			$this->projects_m->update_project_total($project_id,$final_total_quoted);
		}

		if($project_id == 38921){
			$final_total_quoted = $final_total_quoted - 0.23;
		}

		if($project_id == 37625){
			$final_total_quoted = $final_total_quoted - 45.02;
		}

		if($project_id == 38201){
			$final_total_quoted = $final_total_quoted + 5948.78;
		}

		if($project_id == 37770){
			$final_total_quoted = $final_total_quoted - 0.41;
		}

		if($project_id == 38609){
			$final_total_quoted = $final_total_quoted + 0.04;
		}

		if($project_details['project_total'] != $final_total_quoted){ //////////////// !!!!!!!!!!!!!!!!!! here update total when changed
			$this->projects_m->update_project_total($project_id,$final_total_quoted);
			$this->re_allocate_invoice($project_id,$final_total_quoted);
			// put change invoice here
		}



		if($project_id == 36410){
			$final_total_quoted = $final_total_quoted - 1.86;
			$this->projects_m->update_project_total($project_id,$final_total_quoted);
		}

		$result = array(
			"gp" => $gp,
			"final_total_cost" => $actual_project_cost,
			"final_total_estimated" => $final_total_estimated,
			"final_total_quoted" => $final_total_quoted,
			"final_labor_cost" => $total_quoted_cost,
			"labour_cost_grand_total" => $labour_cost_grand_total,
			"install_cost_total" => $project_cost_total['install_cost_total'],
			"variation_total" =>$project_cost_total['variation_total']
			);

		//var_dump($result);
		return $result;
	}

	public function display_project_applied_defaults($project_id){

		$project_details_raw = $this->projects_m->fetch_project_details($project_id);		
		$project_details = array_shift($project_details_raw->result_array());

		$system_default_raw = $this->admin_m->latest_system_default($project_details['defaults_id']);
		$system_default = array_shift($system_default_raw->result_array());

		$markup_raw = $this->admin_m->fetch_markup($system_default['markup_id']);
		$markup = array_shift($markup_raw->result_array());

		$site_costs_raw = $this->admin_m->fetch_site_costs($system_default['site_cost_id']);
		$site_costs = array_shift($site_costs_raw->result_array());

		$admin_defaults_raw = $this->admin_m->fetch_admin_defaults($system_default['admin_default_id']);
		$admin_defaults = array_shift($admin_defaults_raw->result_array());


		$labour_cost_raw = $this->admin_m->fetch_labour_cost($system_default['labour_cost_id']);
		$labour_cost = array_shift($labour_cost_raw->result_array());

		$gp_data_arr = $this->admin->get_double_amalgated_rate($site_costs,$labour_cost,$admin_defaults);

		$result = array(
			"admin_total_rate_amalgated" => $site_costs['total_amalgamated_rate'],
			"admin_total_rate_double" => $site_costs['total_double_time'],
			"admin_actual_rate_amalgate" => $gp_data_arr['gp_amalgamated_rate'],
			"admin_actual_rate_double" => $gp_data_arr['gp_on_cost_time_double_hr'],
			"admin_install_markup" => $admin_defaults['installation_labour_mark_up'],
			"admin_gst_rate" => $admin_defaults['gst_rate'],
			"admin_hourly_rate" => $site_costs['rate']
		);

		//var_dump($result);
		return $result;
	}

	public function re_allocate_invoice($project_id,$new_total){


		$q_invoice_list = $this->invoice_m->get_invoices($project_id);
		$extra_percent  = 0;
		$added_percent = 0;
		$curr_percent = 0;

		$inv_curr_id = 0;
 
		foreach ($q_invoice_list->result() as $invoice) {


			if($invoice->is_invoiced == '1' && $invoice->set_invoice_date != '' && $invoice->progress_percent != '100'){

				$new_percent = 100 / ( $new_total / $invoice->invoiced_amount );
				$this->invoice_m->update_progress_percent($invoice->invoice_id,$new_percent);
				$extra_percent = $extra_percent  + $new_percent;
		
			}else{

				if($invoice->progress_percent != '100'){


					$inv_remains = $this->projects_m->count_remain_un_nvoice($project_id);

					if($extra_percent > 0){


						if($inv_curr_id == 0){
							$inv_curr_id =  $invoice->invoice_id;
							$curr_percent = $invoice->progress_percent;
						}else{
							$extra_percent = $extra_percent + $invoice->progress_percent;
						}


					}

				}
			}
		}


			$added_percent = ABS(100-$extra_percent);
		$this->invoice_m->update_progress_percent($inv_curr_id,$added_percent);



	}


	// public function find_sub_client(){
	// 	$sub_client_company = explode('|', $_POST['ajax_var']);
	// 	$job_subcategory = $this->projects_m->fetch_sub_client($sub_client_company[1]);

	// 	if($job_subcategory->num_rows > 0){
	// 		echo "<option selected='' value=''>None</option>";
	// 		foreach ($job_subcategory->result() as $row) {
	// 			echo '<option value="'.$row->company_name.'|'.$row->company_id.'" >'.ucfirst(strtolower($row->company_name)).'</option>';
	// 		}
	// 	}else{
	// 		echo "<option selected='' value=''>None</option>";
	// 	}
	// }

	public function fetch_shopping_center($shopping_center_name_brand=''){

		if($shopping_center_name_brand!=''){
			$name_brand = $shopping_center_name_brand;
		}else{
			$name_brand = $_POST['ajax_var'];
		}

		if($name_brand!=''){
			$job_subcategory = $this->projects_m->fetch_shopping_center($name_brand);
			foreach ($job_subcategory->result() as $row) {
				echo '<option value="'.$row->shopping_center_detail_id.'" >'.ucfirst(strtolower($row->suburb)).'</option>';
			}
		}

		
	}

	public function list_job_subcategory(){
		$job_subcategory = $this->projects_m->display_job_subcategory();
		foreach ($job_subcategory->result_array() as $row){
			if($row['job_sub_cat'] !== 'Flooring'){
				echo '<option value="2_'.$row['job_sub_cat_id'].'">'.$row['job_sub_cat'].'</option>';
			}
		}
	}

	public function list_job_subcategory_no_other(){
		$job_subcategory = $this->projects_m->display_job_subcategory();
		foreach ($job_subcategory->result_array() as $row){
			if($row['job_sub_cat'] !== 'Other'){
				echo '<option value="2_'.$row['job_sub_cat_id'].'">'.$row['job_sub_cat'].'</option>';
			}
		}
	}

	public function job_cat_list_no_other(){
		$jobs_cat_type = $this->projects_m->display_all_job_category_type('');		
		foreach ($jobs_cat_type->result() as $row){
			//if($row->job_category !== 'Other'){
				echo '<option value="2_'.$row->job_category_id.'">'.$row->job_category.'</option>';
			//}
		}		
	}

	public function list_supplier_category(){
		$supplier_category = $this->projects_m->display_supplier_category();
		foreach ($supplier_category->result_array() as $row){
			echo '<option value="3_'.$row['supplier_cat_id'].'">'.$row['supplier_cat_name'].'</option>';
		}
	}

	public function project_comments_deleted(){
		$comment_id = $_POST['comments_id'];
		$project_id = $_POST['project_id'];
		$user_id = $_POST['user_id'];

		$type = 'Update';
		$actions = 'Deleted an amendment from Project:'.$project_id.' - '.$comment_id;

		date_default_timezone_set("Australia/Perth"); 
		$date = date("d/m/Y");
		$time = date("H:i:s");
		$this->user_model->insert_user_log($user_id,$date,$time,$actions,$project_id,$type);
		$project_comments = $this->projects_m->delete_comment($comment_id);
	}

	public function list_project_comments($project_id=''){


		if(isset($_POST['project_id'])){
			$project_id_set = $_POST['project_id'];
		}elseif($project_id!=''){
			$project_id_set = $project_id;
		}else{
			return false;
		}

		$is_prj_rvw = 1;

		if(isset($_POST['is_prj_rvw']) && $_POST['is_prj_rvw'] != ''){
			$is_prj_rvw = 0;
		}

		
		if(isset($_POST['is_prj_rvw']) && $_POST['is_prj_rvw'] != '' && $_POST['is_prj_rvw'] == 2 ){
			$is_prj_rvw = 2;
		}



		$project_comments = $this->projects_m->list_project_comments($project_id_set,$is_prj_rvw);

		$project_details = $this->projects_m->fetch_project_details($project_id_set);

		if($project_details->num_rows > 0){

			if($project_comments->num_rows > 0){

				foreach ($project_comments->result_array() as $row){

					$fetch_user= $this->user_model->fetch_user($row['user_id']);
					$user = array_shift($fetch_user->result_array());

					echo '<div class=" '.($row['is_active'] == 1 ? 'active' : 'deleted' ).'   notes_line user_postby_'.strtolower( str_replace(' ', '',  $user['user_first_name']) ).' comment_type_'.$row['is_project_comments'].'">';

					if($row['is_active'] == 1 && $row['is_project_comments'] == 2){
						echo '<div class="pull-right btn btn-danger view_delete btn-xs fa fa-trash" id="'.$row['project_comments_id'].'"></div>';
					}

					if($row['is_active'] == 0 && $row['is_project_comments'] == 2){
						echo '<div class="pull-right btn btn-warning view_deleted btn-xs fa fa-eye-slash"> </div>';
					}
					


					echo '<p class="">'.ucfirst (nl2br($row['project_comments'])).'</p><small><i class="fa fa-user"></i> '.$user['user_first_name'].' '.$user['user_last_name'].'<br /><i class="fa fa-calendar"></i> '.$row['date_posted'].'</small></div>';
				


				} 
			}else{
				echo '<div class="notes_line no_posted_comment"><p>No posts made!</p></div>';
			}

		}else{
			echo 'Error';
		}
	}

	public function list_recent_project_comment($project_id){
		$project_comments = $this->projects_m->list_project_comments($project_id);
		foreach ($project_comments->result_array() as $row){
			$fetch_user= $this->user_model->fetch_user($row['user_id']);
			$user = array_shift($fetch_user->result_array());
			echo '<p>'.$row['project_comments'].'</p><small><i class="fa fa-user"></i> '.$user['user_first_name'].' '.$user['user_last_name'].'<br /><i class="fa fa-calendar"></i> '.$row['date_posted'].'</small>';
			return;
		}
	}

	public function save_invoice_comments(){

		if(isset($_POST['invoice_comments'])){
			$this->clear_apost();

			$invoice_comments = $_POST['invoice_comments'];
			$prj_id = $_POST['prj_id'];
			$include_invoice_comments = $_POST['include_invoice_comments'];

			$current_url = $_POST['current_tab'];
			$current_tab = substr($current_url, strrpos($current_url, '#' )+1);

			$this->projects_m->add_invoice_comment($prj_id,$invoice_comments,$include_invoice_comments);
			// redirect('/projects/view/'.$prj_id.'?submit_invoice='.$prj_id);

			redirect('/projects/view/'.$prj_id.'?tab=invoice'); // by Mike

		}else{

			redirect('/projects');
		}
	}

	public function add_project_comment(){
		date_default_timezone_set("Australia/Perth");
		$this->clear_apost();
		$raw_project_comment = $_POST['ajax_var'];
		$project_comment = explode('`', $raw_project_comment);
		$datestring = "%l, %d%S, %F %Y %g:%i:%s %A"; $time = time();  

		$prj_rev = 1;

		if(isset($project_comment[3]) && $project_comment[3] != ''){
			$prj_rev = 0;
		}

		if(isset($project_comment[4]) && $project_comment[4] != ''){
			$prj_rev = 2;
		}

		$date_posted = mdate($datestring, $time);
		
		$supplier_category = $this->projects_m->add_project_comment($project_comment[1],$date_posted,$project_comment[2],$project_comment[0],$prj_rev);
		
		echo $date_posted;
	}

	public function update_project_details(){
		$this->clear_apost();

		$this->users->_check_user_access('projects',2);

		$project_id = $this->uri->segment(3);
		$data['project_id'] = $project_id;





		$q_proj = $this->projects_m->fetch_complete_project_details($project_id);
		if($q_proj->num_rows > 0){
			$data = array_shift($q_proj->result_array());




			$data['job_date_history'] = '';

			if($data['job_date'] == ''){
				$histry_jb_raw = $this->projects_m->fetch_job_date_history($project_id);
				$job_date_history = array_shift($histry_jb_raw->result_array());
				$data['job_date_history'] = $job_date_history['actions'];
			}



			$contact_person_name = "";
			$contact_person_number = "";
			$contact_person_mobile = "";
			$contact_person_email = "";

			$q_proj_site_cont = $this->projects_m->fetch_project_site_contact($project_id);
			foreach ($q_proj_site_cont->result_array() as $row){
				$contact_person_name = $row['contact_person_name'];
				$contact_person_number = $row['contact_person_number'];
				$contact_person_mobile = $row['contact_person_mobile'];
				$contact_person_email = $row['contact_person_email'];
			}

			//echo $contact_person_name;

			$data['contact_person_name'] = $contact_person_name;
			$data['contact_person_number'] = $contact_person_number;
			$data['contact_person_mobile'] = $contact_person_mobile;
			$data['contact_person_email'] = $contact_person_email;

			$system_default_raw = $this->admin_m->latest_system_default($data['defaults_id']);
			$system_default = array_shift($system_default_raw->result_array());

		$admin_defaults_q = $this->admin_m->fetch_admin_defaults($system_default['admin_default_id']); 
		$admin_defaults = array_shift($admin_defaults_q->result_array());
		$days_quote_deadline = $admin_defaults['days_quote_deadline'];


			$markup_raw = $this->admin_m->fetch_markup($system_default['markup_id']);
			$markup_defaults = array_shift($markup_raw->result_array());

			$q_project_notes = $this->projects_m->fetch_project_notes($data['notes_id']);
			$project_notes = array_shift($q_project_notes->result_array());
			$data['project_comments'] = $project_notes['comments'];

			$q_focus_company = $this->company_m->display_company_detail_by_id($data['focus_company_id']);
			$focus_company = array_shift($q_focus_company->result_array());
			$data['focus_company_id'] = $focus_company['company_id'];
			$data['focus_company_name'] = $focus_company['company_name'];

			
			$maintenance_administrator = $this->user_model->fetch_user_by_role(7);
			$data['maintenance_administrator'] = $maintenance_administrator->result();

			$q_client_company = $this->company_m->display_company_detail_by_id($data['client_id']);
			$client_company = array_shift($q_client_company->result_array());
			$data['client_company_id'] = $client_company['company_id'];
			$data['client_company_name'] = $client_company['company_name'];



/*
			$q_sub_client_company = $this->company_m->display_company_detail_by_id($data['sub_client_id']);
			$sub_client_company = array_shift($q_sub_client_company->result_array());
			$data['sub_client_company_id'] = $sub_client_company['company_id'];
			$data['sub_client_company_name'] = $sub_client_company['company_name'];
*/


			$q_contact_person = $this->company_m->fetch_all_contact_persons($data['primary_contact_person_id']);
			$contact_person = array_shift($q_contact_person->result_array());
			$data['contact_person_id'] = $contact_person['contact_person_id'];
			$data['contact_person_fname'] = $contact_person['first_name'];
			$data['contact_person_lname'] = $contact_person['last_name'];


			$query_address= $this->company_m->fetch_complete_detail_address($data['address_id']);
			$temp_data = array_shift($query_address->result_array());
			$data['postcode'] = $temp_data['postcode'];
			$data['suburb'] = $temp_data['suburb'];
			$data['po_box'] = $temp_data['po_box'];
			$data['street'] = ucwords(strtolower($temp_data['street']));
			$data['unit_level'] = ucwords(strtolower($temp_data['unit_level']));
			$data['unit_number'] = $temp_data['unit_number'];
			$data['state'] = $temp_data['name'];
// $data['address_id'] = $data['address_id'];

			$data['shortname'] = $temp_data['shortname'];
			$data['state_id'] =  $temp_data['state_id'];
			$data['phone_area_code'] = $temp_data['phone_area_code'];	

			$invoice_address_id = $data['invoice_address_id'];

			$p_query_address = $this->company_m->fetch_complete_detail_address($data['invoice_address_id']);
			$p_temp_data = array_shift($p_query_address->result_array());
			$data['i_po_box'] = $p_temp_data['po_box'];
			$data['i_unit_level'] = ucwords(strtolower($p_temp_data['unit_level']));
			$data['i_unit_number'] = $p_temp_data['unit_number'];
			$data['i_street'] = ucwords(strtolower($p_temp_data['street']));
			$data['i_suburb'] = $p_temp_data['suburb'];
			$data['i_state'] = $p_temp_data['name'];
			$data['i_postcode'] = $p_temp_data['postcode'];
			//$data['i_address_id'] = $p_temp_data['postal_address_id'];

			$data['i_shortname'] = $p_temp_data['shortname'];
			$data['i_state_id'] =  $p_temp_data['state_id'];
			$data['i_phone_area_code'] = $p_temp_data['phone_area_code']; 

			$q_project_manager = $this->user_model->fetch_user($data['project_manager_id']);
			$project_manager = array_shift($q_project_manager->result_array());
			$data['pm_user_id'] = $project_manager['user_id'];
			$data['pm_user_first_name'] = $project_manager['user_first_name'];
			$data['pm_user_last_name'] = $project_manager['user_last_name'];

			$q_project_admin = $this->user_model->fetch_user($data['project_admin_id']);
			$project_admin = array_shift($q_project_admin->result_array());
			$data['pa_user_id'] = $project_admin['user_id'];
			$data['pa_user_first_name'] = $project_admin['user_first_name'];
			$data['pa_user_last_name'] = $project_admin['user_last_name'];

			$q_project_estiamator_id = $this->user_model->fetch_user($data['project_estiamator_id']);
			$project_estiamator = array_shift($q_project_estiamator_id->result_array());
			$data['pe_user_id'] = $project_estiamator['user_id'];
			$data['pe_user_first_name'] = $project_estiamator['user_first_name'];
			$data['pe_user_last_name'] = $project_estiamator['user_last_name'];


			$q_project_cc_pm = $this->user_model->fetch_user($data['client_contact_person_id']);
			$project_cc_pm = array_shift($q_project_cc_pm->result_array());
			$data['cc_pm_user_id'] =  $project_cc_pm['user_id'];
			$data['cc_pm_user_first_name'] = $project_cc_pm['user_first_name'];
			$data['cc_pm_user_last_name'] = $project_cc_pm['user_last_name'];

			$q_proj_sched_pr = $this->projects_m->get_project_sched_for_pr($project_id);

			if($q_proj_sched_pr->num_rows > 0){

				$proj_sched_pr = $q_proj_sched_pr->row();

				$q_project_lead_hand = $this->user_model->fetch_user($proj_sched_pr->leading_hand_id);
				$project_lead_hand = array_shift($q_project_lead_hand->result_array());
				$data['lead_hand_user_id'] =  $project_lead_hand['user_id'];
				$data['lead_hand_user_first_name'] = $project_lead_hand['user_first_name'];
				$data['lead_hand_user_last_name'] = $project_lead_hand['user_last_name'];

				if ($this->input->post('leading_hand') == 0){
				
					$q_manual_lh = $this->projects_m->get_manual_const($proj_sched_pr->project_schedule_id);
					$manual_lh = array_shift($q_manual_lh->result_array());
					$data['manual_lh_name'] = $manual_lh['lh_name'];
					$data['manual_lh_contact'] = $manual_lh['lh_contact'];
				}
			} else {
				$new_project_sched_id = $this->projects_m->insert_new_project_sched_for_pr($project_id, 0);
				$q_proj_sched_pr = $this->projects_m->get_project_sched_for_pr($project_id);

				$proj_sched_pr = $q_proj_sched_pr->row();

				$q_project_lead_hand = $this->user_model->fetch_user($proj_sched_pr->leading_hand_id);
				$project_lead_hand = array_shift($q_project_lead_hand->result_array());
				$data['lead_hand_user_id'] =  $project_lead_hand['user_id'];
				$data['lead_hand_user_first_name'] = $project_lead_hand['user_first_name'];
				$data['lead_hand_user_last_name'] = $project_lead_hand['user_last_name'];

				if ($this->input->post('leading_hand') == 0){
				
					$q_manual_lh = $this->projects_m->get_manual_const($proj_sched_pr->project_schedule_id);
					$manual_lh = array_shift($q_manual_lh->result_array());
					$data['manual_lh_name'] = $manual_lh['lh_name'];
					$data['manual_lh_contact'] = $manual_lh['lh_contact'];
				}

			}

			if($data['job_category'] == 'Company'){
				$pg_markup = array(0,0,0,0);
				$data['min_markup'] = $pg_markup[1];
			}else{
				$pg_markup_raw = $this->projects->fetch_mark_up_by($data['job_category'] , $markup_defaults['markup_id']);
				$pg_markup = explode('|',$pg_markup_raw);
				$data['min_markup'] = $pg_markup[1];
			}



			$shopping_center_q = $this->projects_m->select_shopping_center($data['address_id']);
			$shopping_center = array_shift($shopping_center_q->result_array());

			$data['shopping_center_id'] = $shopping_center['shopping_center_id'];
			$data['shopping_center_brand_name'] = $shopping_center['shopping_center_brand_name'];
			$data['shopping_common_name'] = $shopping_center['common_name'];

			$all_company_list = $this->company_m->fetch_all_company(NULL);
			if($all_company_list->num_rows > 0){
				$data['all_company_list'] = $all_company_list->result();
			}

			$all_aud_states = $this->company_m->fetch_all_states();
			$data['all_aud_states'] = $all_aud_states->result();

			$focus = $this->admin_m->fetch_all_company_focus();
			$data['focus'] = $focus->result();

			$project_manager = $this->user_model->fetch_user_by_role(3);
			$data['project_manager'] = $project_manager->result();

			$account_manager = $this->user_model->fetch_user_by_role(20);
			$data['account_manager'] = $account_manager->result();

			$project_administrator = $this->user_model->fetch_user_by_role(2);
			$data['project_administrator'] = $project_administrator->result();

			$estimator = $this->user_model->fetch_user_by_role(8);
			$data['estimator'] = $estimator->result();		

			$leading_hand = $this->user_model->fetch_user_by_role(15);
			$data['leading_hand'] = $leading_hand->result();

			$shopping_center = $this->projects_m->fetch_shopping_center();
			$data['shopping_center'] = $shopping_center->result();

			$q_warranty_categories = $this->projects_m->fetch_warranty_categories();
			$data['warranty_categories'] = array_shift($q_warranty_categories->result_array());

			$this->form_validation->set_rules('project_name', 'Project Name','trim|required|xss_clean');
			$this->form_validation->set_rules('site_start', 'Site Start','trim|required|exact_length[10]|xss_clean');
			$this->form_validation->set_rules('site_finish', 'Site Finish','trim|required|exact_length[10]|xss_clean');
			$this->form_validation->set_rules('job_category', 'Job Category','trim|required|xss_clean');
			$this->form_validation->set_rules('brand_name', 'Brand','trim|required|xss_clean');
			$this->form_validation->set_rules('project_date', 'Project Date','trim|required|xss_clean');






			if( $this->input->post('job_category') != 'Maintenance' && $this->input->post('job_category') != 'Minor Works' && $this->input->post('job_category') != 'Strip Out' && $this->input->post('job_category') != 'Design Works' ){
				$this->form_validation->set_rules('project_area', 'Project Area','trim|required|xss_clean|greater_than[0]');
			}else{
				$this->form_validation->set_rules('project_area', 'Project Area','trim|xss_clean');	
			}



		$project_area = $this->input->post('project_area');






			$this->form_validation->set_rules('job_date', 'Job Date','trim|exact_length[10]|xss_clean');


			if( $this->input->post('is_shopping_center') != 1){
				$this->form_validation->set_rules('street', 'Site Street','trim|required|xss_clean');
				$this->form_validation->set_rules('suburb_a', 'Site Project Address Suburb','trim|required|xss_clean');
				$this->form_validation->set_rules('state_a', 'Site State','trim|required|xss_clean');
				$this->form_validation->set_rules('postcode_a', 'Site Postcode','trim|required|xss_clean');
			}else{
				$this->form_validation->set_rules('shop_tenancy_number', 'Site Shop/Tenancy Number','trim|required|xss_clean');
				$this->form_validation->set_rules('brand_shopping_center', 'Site Brand/Shopping Center','trim|required|xss_clean');
			}

			$this->form_validation->set_rules('street_b', 'Invoice Street','trim|required|xss_clean');
			$this->form_validation->set_rules('suburb_b', 'Invoice Address Suburb','trim|required|xss_clean');
			$this->form_validation->set_rules('state_b', 'Invoice State','trim|required|xss_clean');
			$this->form_validation->set_rules('postcode_b', 'Invoice Postcode','trim|required|xss_clean');



			$this->form_validation->set_rules('project_manager', 'Project Manager','trim|required|xss_clean');
			$this->form_validation->set_rules('project_admin', 'Project Admin','trim|required|xss_clean');
			$this->form_validation->set_rules('estimator', 'Estimator','trim|required|xss_clean');

			// if ($this->input->post('leading_hand') == 0){
			// 	$this->form_validation->set_rules('lh_name', 'Leading Hand Full Name','trim|required|xss_clean');
			// 	$this->form_validation->set_rules('lh_mobile_no', 'Leading Hand Mobile No.','trim|required|xss_clean');
			// }

			$this->form_validation->set_rules('company_prg', 'Company Client','trim|required|xss_clean');
			$this->form_validation->set_rules('contact_person', 'Contact Person','trim|required|xss_clean');
			$this->form_validation->set_rules('install_hrs', 'Site Hours','trim|xss_clean');
			$this->form_validation->set_rules('proj_joinery_user', 'Joinery Personel','trim|xss_clean');

//echo $this->company->cap_first_word($this->company->if_set($this->input->post('project_name', true)));
//var_dump($_POST);


			$data['main_content'] = 'project_detail_update';
			$data['screen'] = 'Update Project Details';

			$data['page_title'] = 'Update Project: '.$data['project_name'].' - '.$data['project_id'];

			if($this->form_validation->run() === false){
				$this->clear_apost();
				$data['error' ] = validation_errors();
				$this->load->view('page', $data);
//valid_input_simple
			}



			elseif( $this->input->post('job_category') != 'Maintenance' && $this->input->post('job_category') != 'Minor Works' && $this->input->post('job_category') != 'Strip Out' && $this->input->post('job_category') != 'Design Works' && $project_area < 10  ){

			$this->clear_apost();
			$data['error' ] = 'Invalid value for project area.';
			$this->load->view('page', $data);

		}
		else{
				$this->clear_apost();
				$focus_id = $this->input->post('focus');
				$project_name = $this->input->post('project_name');
				$client_po = $this->input->post('client_po');
				$job_type = $this->input->post('job_type');
				$job_category = $this->input->post('job_category');
				$project_date = $this->input->post('project_date');
				$site_start = $this->input->post('site_start');
				$site_finish = $this->input->post('site_finish');
				$proj_joinery_user = $this->input->post('proj_joinery_user');

				//================= For Maintenance Site sheet ===============
				if($job_category == 'Maintenance'):
					$site_cont_person = $this->input->post('site_cont_person');
					$site_cont_number = $this->input->post('site_cont_number');
					$site_cont_mobile = $this->input->post('site_cont_mobile');
					$site_cont_email = $this->input->post('site_cont_email');

					$proj_site_cont = $this->projects_m->fetch_project_site_contact($project_id);
					if($proj_site_cont->num_rows == 0){
						$this->projects_m->insert_project_site_contact($project_id, $site_cont_person, $site_cont_number, $site_cont_mobile, $site_cont_email);
						date_default_timezone_set("Australia/Perth");
						$user_id = $this->session->userdata('user_id');
						$date = date("d/m/Y");
						$time = date("H:i:s");
						$type = "INSERT";
						$actions = "Insert project #".$project_id." site contacts with Contact Person: ".$site_cont_person.", Contact Number: ".$site_cont_number.", Mobile Number: ".$site_cont_mobile.", Email: ".$site_cont_email;
						$this->user_model->insert_user_log($user_id,$date,$time,$actions,$project_id,$type);
					}else{
						$this->projects_m->update_project_site_contact($project_id, $site_cont_person, $site_cont_number, $site_cont_mobile, $site_cont_email);
						date_default_timezone_set("Australia/Perth");
						$user_id = $this->session->userdata('user_id');
						$date = date("d/m/Y");
						$time = date("H:i:s");
						$type = "Update";
						$actions = "Update project #".$project_id." site contacts with Contact Person: ".$site_cont_person.", Contact Number: ".$site_cont_number.", Mobile Number: ".$site_cont_mobile.", Email: ".$site_cont_email;
						$this->user_model->insert_user_log($user_id,$date,$time,$actions,$project_id,$type);
					}

					
				endif;
				//================= For Maintenance Site sheet =============== 

				$q_proj_prev = $this->projects_m->fetch_complete_project_details($project_id);
				$prev_project_details = array_shift($q_proj_prev->result_array());

				$attempt = 0;
				if($this->session->userdata('is_admin') == 1 || $this->session->userdata('job_date') == 1 || ( $this->session->userdata('user_role_id') == 7 && $prev_project_details['job_category'] == 'Maintenance' ) || ( $this->session->userdata('company_project') == 1 && $prev_project_details['job_category'] == 'Company' ) ){
					$job_date = $this->input->post('job_date');
				}else{
					if($prev_project_details['job_date'] == ''){
						$job_date = $this->input->post('job_date');
					}else{
						$job_date = $prev_project_details['job_date'];
						$attempt = 1;
					}
				}

				if($job_date != ''){
					$this->form_validation->set_rules('job_date', 'Job Date','trim|exact_length[10]|xss_clean');
				}

				$is_wip = ($job_date != '' ? 1 : 0);

				if(strlen($job_date) == 0 && $prev_project_details['job_date'] == ''){
					$actions = 'Updated project details, with no job date';
				}elseif($job_date == $prev_project_details['job_date']){
					$actions = 'Updated project details';
				}elseif($job_date != $prev_project_details['job_date'] && strlen($job_date) > 0 && strlen($prev_project_details['job_date']) > 0 ){
					$actions = 'Replaced job date from '.$prev_project_details['job_date'].' to '.$job_date;
				}elseif($job_date != $prev_project_details['job_date'] && strlen($job_date) == 0){
					$actions = 'Removed job date: '.$prev_project_details['job_date'];
				}else{

					$q_get_log_prjId = $this->projects_m->check_log_jobdate($project_id);
					$prj_log = array_shift($q_get_log_prjId->result_array());

					$prj_log_arr = explode(':', $prj_log['actions'] );
					$log_job_date = str_replace(' ','', $prj_log_arr[1]);

					//var_dump($prj_log);
					//var_dump($prj_log_arr);

					if( strtotime($job_date) == strtotime($log_job_date) ){
						$actions = 'Restored old job date: '.$job_date;
					}else{
						$actions = 'Added job new date: '.$job_date;
					}
 				}

				if($attempt == 1){
					$actions = 'Updated project details';
				}

				$data['unit_level'] = $this->company->if_set ($this->input->post('unit_level', true));
				$data['unit_number'] = $this->company->if_set($this->input->post('unit_number', true));
				$data['street'] = $this->company->cap_first_word($this->company->if_set($this->input->post('street', true)));
				$data['postcode_a'] = $this->company->if_set($this->input->post('postcode_a', true));

				$data['pobox'] = $this->company->if_set($this->input->post('pobox', true));
				$data['unit_level_b'] = $this->company->if_set($this->input->post('unit_level_b', true));
				$data['number_b'] = $this->company->if_set($this->input->post('number_b', true));			
				$data['street_b'] = $this->company->cap_first_word($this->company->if_set($this->input->post('street_b', true)));
				$data['postcode_b'] = $this->company->if_set($this->input->post('postcode_b', true));		

				$state_a_arr = explode('|', $this->input->post('state_a', true));	
				$data['state_a'] = $state_a_arr[3];

				$suburb_a_ar = explode('|',$this->company->if_set($this->input->post('suburb_a', true)));
				$data['suburb_a'] = strtoupper($suburb_a_ar[0]);

				$state_b_arr = explode('|', $this->input->post('state_b', true));
				$data['state_b'] = $state_b_arr[3];

				$suburb_b_ar = explode('|',$this->company->if_set($this->input->post('suburb_b', true)));
				$data['suburb_b'] = strtoupper($suburb_b_ar[0]);

				$project_manager_id = $this->input->post('project_manager');
				$project_admin_id = $this->input->post('project_admin');
				$project_estiamator_id = $this->input->post('estimator');
				$project_leading_hand_id = $this->input->post('leading_hand');

				if ($project_leading_hand_id == 0){
					$project_leading_hand_full_name = $this->input->post('lh_name');
					$project_leading_hand_mobile_no = $this->input->post('lh_mobile_no');
				}

				$company_prg_arr =  explode('|',$this->input->post('company_prg'));
				$client_id = $company_prg_arr[1];

				$contact_person_id = $this->input->post('contact_person');
				$brand_name = $this->input->post('brand_name');

				$est_amt = $this->input->post('project_total');
				$project_total = str_replace (',','', $est_amt);

				if($this->invoice->if_project_invoiced_full($project_id) == 1){
					$project_markup = $prev_project_details['markup'];
					$install_hrs = $prev_project_details['install_time_hrs'];
				}else{					
					$install_hrs = $this->input->post('install_hrs');
					$project_markup = $this->input->post('project_markup');
				}

				$project_area = $this->input->post('project_area');
				$comments = $this->input->post('comments');
				$project_status_id = 1;

				$shop_tenancy_number = $this->input->post('shop_tenancy_number'); 

				$focus_user_id = $this->session->userdata('user_id');

				$labour_hrs_estimate = $this->input->post('labour_hrs_estimate', true);

				$is_shopping_center = $this->input->post('is_shopping_center');
				$is_double_time = $this->input->post('is_double_time');

/*
				$sub_client_id_arr =  explode('|',$this->input->post('sub_client_id'));
				$sub_client_id = $sub_client_id_arr[1];
*/
 



//======= fetch database Mark-up ===
				$proj_q = $this->projects_m->select_particular_project($project_id);
				foreach ($proj_q->result_array() as $row){
					$proj_markup = $row['markup'];
				}


				if($is_shopping_center == 1){
					$site_address_id =  $this->input->post('brand_shopping_center');
				}else{
					$site_address_id = $data['address_id'];
					$shop_tenancy_number = '';
					$this->company_m->update_address_details($data['address_id'],$data['unit_number'],$data['unit_level'],$data['street'],$data['suburb_a'],$data['postcode_a']);
				}


				$cc_pm_raw = $this->input->post('client_contact_project_manager');
				$cc_pm = ($cc_pm_raw == 0 ? $project_manager_id : $cc_pm_raw);


				$this->company_m->update_address_details($invoice_address_id,$data['number_b'],$data['unit_level_b'],$data['street_b'],$data['suburb_b'],$data['postcode_b'],$data['pobox']);

/*
			$formated_start_date = str_replace("/","-",$site_start);
			$date_quote_deadline =  date('d/m/Y', strtotime("$formated_start_date -$days_quote_deadline days"));
*/

$rev_date  = date("d/m/Y");
				$this->projects_m->update_full_project_details($project_id,$project_name,$client_id,$contact_person_id,$client_po,$job_type,$brand_name,$job_category,$job_date,$site_start,$site_finish,$is_wip,$install_hrs,$is_double_time,$project_total,$labour_hrs_estimate,$project_markup,$project_area,$project_manager_id,$project_admin_id,$project_estiamator_id,$shop_tenancy_number,$site_address_id,$shop_tenancy_number,$site_address_id,$invoice_address_id,$focus_id,$cc_pm,$proj_joinery_user,$rev_date);

				if( strpos(implode(",",$data['warranty_categories']), $job_category) !== false ):
					$this->projects->set_warranty_date_after_paid($project_id);
				endif;

				

		$static_defaults_q = $this->user_model->select_static_defaults();
		$static_defaults = array_shift($static_defaults_q->result() ) ;

		$day_revew_req = $static_defaults->prj_review_day;

		$timestamp_day_revuew_req = (int)strtotime("$day_revew_req this week");
		$monday_revuew_req = (int)strtotime("Monday this week");
		$friday_revuew_req = (int)strtotime("Friday this week");
		$timestamp_lwk_revuew_req = (int)strtotime("$day_revew_req last week");
		$timestamp_nxt_revuew_req = (int)strtotime("$day_revew_req next week");

		$date_day_revuew_req = date('d/m/Y',$timestamp_day_revuew_req);
		$today_rvw_mrkr = (int)strtotime("Today");

		$q_prj_rvw = $this->projects_m->get_prj_rvw($date_day_revuew_req,$project_id);


		if($q_prj_rvw->num_rows === 1){ // if found data
			$this->projects_m->update_set_wip_rvw($project_id,$date_day_revuew_req,$rev_date);
		}else{
			$this->projects_m->insert_wip_rvw($project_id, $date_day_revuew_req, $rev_date);
		}

		if($today_rvw_mrkr > $timestamp_day_revuew_req && $today_rvw_mrkr < $timestamp_nxt_revuew_req ){  // baddd
			if( $timestamp_day_revuew_req <  $today_rvw_mrkr && $today_rvw_mrkr <= $friday_revuew_req  ){
				$this->projects_m->prj_rvw_late($project_id,$date_day_revuew_req );
			}
		}



				$this->projects_m->update_project_sched_for_pr_lead($project_id, $project_leading_hand_id);

				if ($project_leading_hand_id == 0){
					$q_proj_sched_id = $this->projects_m->get_project_sched_for_pr($project_id);
					$proj_sched_id = array_shift($q_proj_sched_id->result_array());

					$q_manual_lh_id = $this->projects_m->get_manual_const($proj_sched_id['project_schedule_id']);
					$manual_lh_id = array_shift($q_manual_lh_id->result_array());

					if($q_manual_lh_id->num_rows > 0){
						$this->projects_m->update_manual_lead($proj_sched_id['project_schedule_id'], ucwords($project_leading_hand_full_name), $project_leading_hand_mobile_no);
					} else {
						$this->projects_m->insert_manual_lead($proj_sched_id['project_schedule_id'], ucwords($project_leading_hand_full_name), $project_leading_hand_mobile_no);
					}
				}

				$this->company_m->update_notes_comments($data['notes_id'],$comments);

				if($site_finish != ''){
				//	$this->projects_m->update_vr_inv_date($site_finish,$site_finish,$project_id );
				}


				if($install_hrs != '' && $install_hrs > 0){
					$prj_install_hrs = $install_hrs;
				}else{
					$prj_install_hrs = $labour_hrs_estimate;
				}

				$this->update_install_cost_total($project_id,$prj_install_hrs,$is_double_time);
//===== Change markup for all the works of the selected project ===
				if($proj_markup !== $project_markup):
					$work_q = $this->works_m->display_all_works($project_id);
					foreach ($work_q->result_array() as $row){
						$works_id = $row['works_id'];
						if($row['work_con_sup_id'] == '53'){
							$joinery_works_id = $works_id;
						}
						$this->works_m->update_works(1,$works_id,"",$project_markup,"","","","","","","","","","","","","","","","","","","","","","");
						//$this->works_m->update_works(1,$works_id,"",$project_markup,"","","","","","","","","","","","","","","","","","","","","");
						$work_estimate = $row['work_estimate'];
						$quote = $work_estimate + ($work_estimate * ($project_markup/100));
						$this->works_m->update_works(8,$works_id,"","","","","","","","","","","","","","","","","","","","","",$work_estimate,$quote,"");
						//$this->works_m->update_works(8,$works_id,"","","","","","","","","","","","","","","","","","","","","",$work_estimate,$quote);
					}

					if($joinery_works_id !== "" ){
						$work_joinery_q = $this->works_m->display_all_works_joinery($joinery_works_id);
						foreach ($work_joinery_q->result_array() as $row){
							$joinery_id = $row['work_joinery_id'];
							$joinery_estimate = $row['j_estimate'];
		    				$joiner_quoted = $joinery_estimate +  ($joinery_estimate * ($project_markup/100));
		    				$this->works_m->update_joinery_markup($joinery_id,$project_markup,$joiner_quoted);
						}

						$work_joinery = $this->works_m->display_all_works_joinery($joinery_works_id);
				      	$t_price = 0;
				      	$t_estimate = 0;
				      	$t_quote = 0;
				      	foreach ($work_joinery->result_array() as $row)
						{
						   $t_price = $t_price + $row['j_price'];
						   $t_estimate = $t_estimate + $row['j_estimate'];
						   $t_quote = $t_quote + $row['j_quote'];
						}

						$this->works_m->update_work_joinery($joinery_works_id,$t_price,$t_estimate,$t_quote);
					}
					
		//== Total Works in project
					$works_q = $this->works_m->display_all_works($project_id);
					$t_price = 0;
					$t_estimate = 0;
					$t_quoted = 0;
					foreach ($works_q->result_array() as $row){
						$t_price = $t_price + $row['price'];
						$t_estimate = $t_estimate + $row['work_estimate'];
						$t_quoted = $t_quoted + $row['total_work_quote'];
					}

					$this->projects_m->update_project_cost_total($project_id,$t_price,$t_estimate,$t_quoted);
				endif;

				$this->session->set_flashdata('full_update', 'Project Details is now updated!');


				$type = 'Update';

				date_default_timezone_set("Australia/Perth");
				$user_id = $this->session->userdata('user_id');
				$date = date("d/m/Y");
				$time = date("H:i:s");
				$this->user_model->insert_user_log($user_id,$date,$time,$actions,$project_id,$type);


				if(isset($_GET['status_rvwprj']) && $_GET['status_rvwprj'] != '' ){
					redirect(base_url().'projects/projects_wip_review?prj_ret_rev='.$project_id.'-'.$_GET['status_rvwprj'].'_prj_view&pmr='.$project_manager_id);
				}



				redirect('/projects/view/'.$project_id);
			}

		}else{


			if(isset($_GET['status_rvwprj']) && $_GET['status_rvwprj'] != '' ){
				redirect(base_url().'projects/projects_wip_review?prj_ret_rev='.$project_id.'-'.$_GET['status_rvwprj'].'_prj_view&pmr='.$project_manager_id);
			}
			redirect('/projects');
		}
	}

	public function delete_project(){
		$project_id = $this->uri->segment(3);

		$this->session->set_flashdata('project_deleted', 'A project is been deleted. Project No.'.$project_id);
		$this->projects_m->delete_project_details($project_id);

		$type = 'Delete';
		$actions = 'Deleted project details';
		date_default_timezone_set("Australia/Perth");
		$user_id = $this->session->userdata('user_id');
		$date = date("d/m/Y");
		$time = date("H:i:s");
		$this->user_model->insert_user_log($user_id,$date,$time,$actions,$project_id,$type);

		redirect('/projects');
	}


	public function quick_update(){
		$this->clear_apost();
		$project_id = $_POST['project_id'];


		$project_details_raw = $this->projects_m->fetch_project_details($project_id);		
		$project_details = array_shift($project_details_raw->result_array());

		$system_default_raw = $this->admin_m->latest_system_default($project_details['defaults_id']);
		$system_default = array_shift($system_default_raw->result_array());

		$admin_defaults_q = $this->admin_m->fetch_admin_defaults($system_default['admin_default_id']); 
		$admin_defaults = array_shift($admin_defaults_q->result_array());
		$days_quote_deadline = $admin_defaults['days_quote_deadline'];

		$project_name = $this->input->post('project_name');
		$budget_estimate_total = str_replace (',','', $this->input->post('budget_estimate_total') );

		$client_po = $this->input->post('client_po');
		$site_labour_estimate = $this->input->post('site_labour_estimate');
		$site_start = $this->input->post('site_start');
		$site_finish = $this->input->post('site_finish');
		
		$is_double_time = $this->input->post('is_double_time');
		$type = 'Update';
		$attempt = 0;

		$job_date = '';

		$q_proj = $this->projects_m->fetch_complete_project_details($project_id);
		$prev_project_details = array_shift($q_proj->result_array());

		//if($this->invoice->if_project_invoiced_full($project_id) == 1){
			//$project_markup = $prev_project_details['markup'];
			//$install_time_hrs = $prev_project_details['install_time_hrs'];
		//}else{
			$project_markup = $this->input->post('project_markup');
			$install_time_hrs = $this->input->post('install_time_hrs');
		//}

		if($this->session->userdata('is_admin') == 1 || $this->session->userdata('job_date') == 1 || ( $this->session->userdata('user_role_id') == 7 && $prev_project_details['job_category'] == 'Maintenance' ) || ( $this->session->userdata('company_project') == 1 &&      $prev_project_details['job_category'] == 'Company'          ) ){
			
			if(isset( $_POST['job_date']  ) &&  $_POST['job_date'] != ''){
				$job_date = $_POST['job_date'];
			}else{
				$job_date = '';
			}

		}else{
			if($prev_project_details['job_date'] == ''){				

				if(isset( $_POST['job_date']  ) &&  $_POST['job_date'] != ''){
					$job_date = $_POST['job_date'];
				}else{
					$job_date = '';
				}


			}else{
				$job_date = $prev_project_details['job_date'];
				$attempt = 1;
			}
		}


		if($_POST['project_markup'] != $prev_project_details['markup']){
			$person_did = $this->session->userdata('user_id');
			$type = 'Project Markup';
			$date = date("d/m/Y");
			$time = date("H:i:s");
			$actions = 'Changed the project markup from '.$prev_project_details['markup'].'% to '.$_POST['project_markup'].'%';
			$this->user_model->insert_user_log($person_did,$date,$time,$actions,$project_id,$type);
		}

		$unaccepted_date = "";
		if(isset($_POST['unaccepted_date'])){
			$unaccepted_date = $_POST['unaccepted_date'];
		}

		if(strlen($job_date) > 0 && $job_date != ''){
			$is_wip = 1;
		}else{
			$is_wip = 0;
		}

		if($site_finish != ''){
		//	$this->projects_m->update_vr_inv_date($site_finish,$site_finish,$project_id );
		}

		if(strlen($job_date) == 0 && $prev_project_details['job_date'] == ''){
			$actions = 'Updated project details, with no job date';
		}elseif($job_date == $prev_project_details['job_date']){
			$actions = 'Updated project details';
		}elseif($job_date != $prev_project_details['job_date'] && strlen($job_date) > 0 && strlen($prev_project_details['job_date']) > 0 ){
			$actions = 'Replaced job date from '.$prev_project_details['job_date'].' to '.$job_date;
		}elseif($job_date != $prev_project_details['job_date'] && strlen($job_date) == 0){
			$actions = 'Removed job date: '.$prev_project_details['job_date'];
		}else{
		//	$actions = 'Added job new date: '.$job_date;

			$q_get_log_prjId = $this->projects_m->check_log_jobdate($project_id);
			$prj_log = array_shift($q_get_log_prjId->result_array());

			$prj_log_arr = explode(':', $prj_log['actions'] );
			$log_job_date = str_replace(' ','', $prj_log_arr[1]);

			if( strtotime($job_date) == strtotime($log_job_date) ){
				$actions = 'Restored old job date: '.$job_date;
			}else{
				$actions = 'Added job new date: '.$job_date;
			}
		}

		if($attempt == 1){
			$actions = 'Updated project details';
		}


		$has_updated_wip = 0;

		if($install_time_hrs != '' && $install_time_hrs > 0){
			$prj_install_hrs = $install_time_hrs;
		}else{
			$prj_install_hrs = $site_labour_estimate;
		}

		$proj_q = $this->projects_m->select_particular_project($project_id);
		foreach ($proj_q->result_array() as $row){
			$proj_markup = $row['markup'];
		}



		$days_quote_deadline = $admin_defaults['days_quote_deadline'];

		$formated_start_date = str_replace("/","-",$site_start);
		$date_quote_deadline =  date('d/m/Y', strtotime("$formated_start_date -$days_quote_deadline days"));

		if(isset($_POST['quote_deadline']) && $_POST['quote_deadline'] != ''){
			$date_quote_deadline = $_POST['quote_deadline'];
		}
		
		$has_updated_wip = $this->projects_m->set_wip_project($project_id,$is_wip);


		$rev_date  = date("d/m/Y");


		$this->projects_m->project_details_quick_update($project_id,$project_name,$budget_estimate_total,$job_date,$date_quote_deadline,$client_po,$install_time_hrs,$project_markup,$site_start,$site_finish,$unaccepted_date,$rev_date);
		
		$this->update_install_cost_total($project_id,$prj_install_hrs,$is_double_time);





//$prev_project_details['job_date']


		$static_defaults_q = $this->user_model->select_static_defaults();
		$static_defaults = array_shift($static_defaults_q->result() ) ;

		$day_revew_req = $static_defaults->prj_review_day;

		$timestamp_day_revuew_req = (int)strtotime("$day_revew_req this week");
		$monday_revuew_req = (int)strtotime("Monday this week");
		$friday_revuew_req = (int)strtotime("Friday this week");

		$date_day_revuew_req = date('d/m/Y',$timestamp_day_revuew_req);
		$today_rvw_mrkr = (int)strtotime("Today");

		$q_prj_rvw = $this->projects_m->get_prj_rvw($date_day_revuew_req,$project_id);


		if($q_prj_rvw->num_rows === 1){ // if found data
			$this->projects_m->update_set_wip_rvw($project_id,$date_day_revuew_req,$rev_date);
		}else{
			$this->projects_m->insert_wip_rvw($project_id, $date_day_revuew_req, $rev_date);
		}

		// if($today_rvw_mrkr > $timestamp_day_revuew_req){  // baddd
		// 	if( $timestamp_day_revuew_req <  $today_rvw_mrkr && $today_rvw_mrkr <= $friday_revuew_req  ){
		// 		$this->projects_m->prj_rvw_late($project_id,$date_day_revuew_req );
		// 	}
		// }



		$timestamp_lwk_revuew_req = (int)strtotime("$day_revew_req last week");
		$timestamp_nxt_revuew_req = (int)strtotime("$day_revew_req next week");

if($today_rvw_mrkr > $timestamp_day_revuew_req && $today_rvw_mrkr < $timestamp_nxt_revuew_req ){  // baddd
	if( $timestamp_day_revuew_req <  $today_rvw_mrkr && $today_rvw_mrkr <= $friday_revuew_req  ){
		$this->projects_m->prj_rvw_late($project_id,$date_day_revuew_req );
	}
}









		date_default_timezone_set("Australia/Perth");
		$user_id = $this->session->userdata('user_id');
		$date = date("d/m/Y");
		$time = date("H:i:s");
		$this->user_model->insert_user_log($user_id,$date,$time,$actions,$project_id,$type);
		

		if($proj_markup !== $project_markup):
			$work_q = $this->works_m->display_all_works($project_id);
			$joinery_works_id = "";
			foreach ($work_q->result_array() as $row){
				$works_id = $row['works_id'];
				if($row['work_con_sup_id'] == '53'){
					$joinery_works_id = $works_id;
				}
				$this->works_m->update_works(1,$works_id,"",$project_markup,"","","","","","","","","","","","","","","","","","","","","","");
				//$this->works_m->update_works(1,$works_id,"",$project_markup,"","","","","","","","","","","","","","","","","","","","","");
				$work_estimate = $row['work_estimate'];
				$quote = $work_estimate + ($work_estimate * ($project_markup/100));
				$this->works_m->update_works(8,$works_id,"","","","","","","","","","","","","","","","","","","","","",$work_estimate,$quote,"");
				//$this->works_m->update_works(8,$works_id,"","","","","","","","","","","","","","","","","","","","","",$work_estimate,$quote);
			}

			if($joinery_works_id !== "" ){
				$work_joinery_q = $this->works_m->display_all_works_joinery($joinery_works_id);
				foreach ($work_joinery_q->result_array() as $row){
					$joinery_id = $row['work_joinery_id'];
					$joinery_estimate = $row['j_estimate'];
    				$joiner_quoted = $joinery_estimate +  ($joinery_estimate * ($project_markup/100));
    				$this->works_m->update_joinery_markup($joinery_id,$project_markup,$joiner_quoted);
				}

				$work_joinery = $this->works_m->display_all_works_joinery($joinery_works_id);
		      	$t_price = 0;
		      	$t_estimate = 0;
		      	$t_quote = 0;
		      	foreach ($work_joinery->result_array() as $row)
				{
				   $t_price = $t_price + $row['j_price'];
				   $t_estimate = $t_estimate + $row['j_estimate'];
				   $t_quote = $t_quote + $row['j_quote'];
				}

				$this->works_m->update_work_joinery($joinery_works_id,$t_price,$t_estimate,$t_quote);
			}
			
//== Total Works in project
			$works_q = $this->works_m->display_all_works($project_id);
			$t_price = 0;
			$t_estimate = 0;
			$t_quoted = 0;
			foreach ($works_q->result_array() as $row){
				$t_price = $t_price + $row['price'];
				$t_estimate = $t_estimate + $row['work_estimate'];
				$t_quoted = $t_quoted + $row['total_work_quote'];
			}

			$this->projects_m->update_project_cost_total($project_id,$t_price,$t_estimate,$t_quoted);

		endif;




		$this->session->set_flashdata('curr_tab', 'project-details');	

		$this->session->set_flashdata('quick_update', 'Values are now updated!');

	//	echo $_POST['project_id'];


//echo "<p>".$prev_project_details['date_site_finish']." != $site_finish</p>";

		if($prev_project_details['date_site_finish'] != $site_finish){
			$this->users->notify_changed_completion_date($project_id,$project_name);
		}



	// header( "refresh:1; url=".base_url()."projects/view/$project_id" ); 

		if($has_updated_wip == 1){
 			redirect('/projects/view/'.$project_id);
		}
//		
	}

	public function list_years_uploaded($year){

		$q_list_year_uploads = $this->projects_m->list_year_uploads();
		$year_uploads = $q_list_year_uploads->result();

		echo "<option value='$year'> ".($year-1)." - ".$year."</option>";
		foreach ($year_uploads as $data){
			if($year != $data->year_list){
				echo "<option value='$data->year_list'>".($data->year_list-1)." - ".$data->year_list."</option>";
			}			
		}
	}



	public function document_storage(){
  
		$data['main_content'] = 'doc_storage';
		$data['screen'] = 'Document Storage';
		$data['page_title'] = 'Document Storage';
		$this->load->view('page', $data);

	}

	public function process_upload_file_storage(){
		$this->clear_apost();
		$time = time();
		$user_id = $this->session->userdata('user_id');
		$date_upload = date("d/m/Y"); 

		$path = "./docs/stored_docs";

		$is_prj_scrn = 0;

        $config = array();
	    $config['upload_path'] = $path."/";
        $config['allowed_types'] = '*';
        $config['max_size']      = '0';
        $config['overwrite']     = FALSE;


        $this->upload->initialize($config);
        $this->load->library('upload');

		if(!is_dir($path)){
			mkdir($path, 0755, true);
		}

        $files = $_FILES;
		$file_type = $_POST['doc_type_name'];
		$project_id = $_POST['doc_proj_id'];
		$is_prj_scrn = $_POST['is_prj_scrn'];

        $cpt = count($_FILES['doc_files']['name']);

        for($i=0; $i<$cpt; $i++){

        	$file_name = $files['doc_files']['name'][$i];
        	$path_parts = pathinfo($file_name);
        	$extension = strtolower($path_parts['extension']);

        	$data_file_name = $project_id.'_'.$path_parts['filename'].'_'.$time.'.'.$extension;
	    	$file_name_set = str_replace(' ', '_', $data_file_name);
	    	$file_name_set_final = str_replace("'", '`', $file_name_set);
	    	$file_name_amp = str_replace('&', '_and_', $file_name_set_final);


        	$_FILES['doc_files']['name']= $file_name_amp;
        	$_FILES['doc_files']['tmp_name']= $files['doc_files']['tmp_name'][$i];
        	$_FILES['doc_files']['type']= $files['doc_files']['type'][$i];
        	$_FILES['doc_files']['error']= $files['doc_files']['error'][$i];
        	$_FILES['doc_files']['size']= $files['doc_files']['size'][$i];  

        	if ( !$this->upload->do_upload('doc_files')) {
			   	echo $this->upload->display_errors();
			}else{
				$this->projects_m->insert_uploaded_file($file_name_amp,$file_type,$project_id,$date_upload,$user_id);
			}
        }

        if($is_prj_scrn == 1){
        	redirect('/projects/view/'.$project_id);
        }else{
        	redirect('/projects/document_storage');
        }
    }




	public function add_doc_type(){
		$this->clear_apost();
		$type_name = $this->input->post('type_name');

		if(isset($type_name) && $type_name!= ''){
			$this->projects_m->insert_doc_type($type_name);
		}


		redirect('projects/document_storage');
	}

	public function list_doc_type_storage( $view='select' ){
		$q_list_doc_type = $this->projects_m->list_doc_type();
		$doc_type = $q_list_doc_type->result();


		if($view == 'select'){
			foreach ($doc_type as $data){
				echo "<option value=\"$data->storage_doc_type_id\">".$data->doc_type_name."</option>";
			}
		}

		if($view == 'list_view'){
			foreach ($doc_type as $data){
				echo "<p id=\"".$data->storage_doc_type_id."\"><em class='fa fa-arrow-circle-right'></em> ".$data->doc_type_name;



 if($this->session->userdata('is_admin') == 1 || $this->session->userdata('user_id') == 6  ):
				echo '<em id="'.$data->storage_doc_type_id.'" class="pointer fa fa-pencil-square fa-lg pull-right edt_doctype" style="color: orange;   margin-top: 3px;"></em>';
endif;
				echo "</p>";

			}
		}







	}

	public function list_uploaded_files(){ //
		$proj_id = $_POST['proj_id'];
		$q_list_doc_type = $this->projects_m->list_uploaded_files($proj_id);


		$rows = $q_list_doc_type->num_rows;


		$list_doc_type = $q_list_doc_type->result();
		$doc_type = '';


		if($rows < 1){
			echo '<p class="m-top-10"><em class="fa fa-exclamation-circle"></em> No Files Uploaded</p>';
		}else{
			foreach ($list_doc_type as $stored_files){
				if($doc_type == ''){
					$doc_type = $stored_files->doc_type_name;
					echo "<p class=\"m-top-15\"><strong>$doc_type</strong></p>";
				}else{
					if($doc_type != $stored_files->doc_type_name){
						$doc_type = $stored_files->doc_type_name;
						echo "<p class=\"m-top-15\"><strong>$doc_type</strong></p>";
					}
				}
				echo '<p class="row_file_list clearfix pad-3 pad-left-5 pad-right-5">
				<a href="'.base_url().'docs/stored_docs/'.urlencode($stored_files->file_name).'" target="_blank" class="pull-left" id=""><em class="fa fa-chevron-circle-right"></em>&nbsp;'.$stored_files->file_name.'</a>';
			
 //if($this->session->userdata('is_admin') == 1 || $this->session->userdata('user_id') == 6  ):	

				echo '<span style=" background:#F7901E; font-size: 12px; padding: 1px 8px; float: right; border: 1px solid #864e11;  color: #fff;  height: 20px;    margin: 0px 5px;     border-radius: 10px;    display: block;"><em class="fa fa-calendar-o"></em> '.$stored_files->date_upload.' &nbsp; '.$stored_files->user_first_name.'</span>';
			//endif;

				echo '<em id="'.$stored_files->storage_files_id.'" class="pointer fa fa-trash fa-lg pull-right del_stored_file" style="color: red; display:none; margin-top: 3px;" onclick = "del_stored_file('.$stored_files->storage_files_id.')"></em>';
			echo '</p>';
			}

		}



	}

	public function remove_uploaded_file(){
		$this->projects_m->remove_uploaded_file($_POST['file_id']);
 

		$user_id = 	$this->session->userdata('user_id');

		$type = 'Update';
		$actions = 'Deleted a file ID:'.$_POST['file_id'];

		date_default_timezone_set("Australia/Perth"); 
		$date = date("d/m/Y");
		$time = date("H:i:s");
		$this->user_model->insert_user_log($user_id,$date,$time,$actions,'',$type);
	 
	}

	public function delete_doc_type($type_id){
		$this->projects_m->remove_doc_type($type_id);	

		$user_id = 	$this->session->userdata('user_id');

		$type = 'Update';
		$actions = 'Deleted a doc type ID:'.$type_id;

		date_default_timezone_set("Australia/Perth"); 
		$date = date("d/m/Y");
		$time = date("H:i:s");
		$this->user_model->insert_user_log($user_id,$date,$time,$actions,'',$type);

		redirect('/projects/document_storage');	
	}

	public function update_doc_type(){
		$this->clear_apost();
		$type_name = trim($_POST['type_name']);
		$type_id = trim($_POST['type_id']);
		
		$this->projects_m->update_type_name($type_name,$type_id);
		redirect('/projects/document_storage');	
	}

	public function list_projects_by_job_date($this_year=''){ 

	 	if($this_year == ''){
	 		$this_year = date('Y');
	 	}
 
	 	$last_year = $this_year - 1;

		$q_list_projects_by_job_date = $this->projects_m->list_projects_by_job_date($this_year,$last_year);


		$has_data = $q_list_projects_by_job_date->num_rows;

		$prj_line = 0;
		$doc_type = '';

		if($has_data > 0){

			$projects_by_job_date = $q_list_projects_by_job_date->result();
			foreach ($projects_by_job_date as $data){

				
				if($prj_line != $data->project_id){
		$doc_type = '';
				//	echo "<tr><td>";
					echo '<div class="pad-5 prj_files_group">
						<div class="btn btn-info btn-xs fa fa-code-fork prj_files_head" id="'.$data->project_id.'"  style="margin: -4px 0 0 0;" id=""></div> 
						&nbsp; '.$data->project_id.' - '.$data->project_name.' 
						<div class="pull-right btn btn-success btn-xs set_doc_storage" id="'.$data->project_id.'_project_set"  data-toggle="modal" data-target="#doc_storage" style="margin: -1px 0 0 0;">Upload</div> </div>';
					

					$prj_line = $data->project_id;
				}


				if($doc_type == ''){
					$doc_type = $data->doc_type_name;
					echo '<p class="uploaded_files_row no-m pad-5 '.$data->project_id.'_files" style="display:none;"><strong>'.$doc_type.'</strong></p>';
				}else{
					if($doc_type != $data->doc_type_name){
						$doc_type = $data->doc_type_name;
						echo '<p class="uploaded_files_row no-m pad-5 '.$data->project_id.'_files" style="display:none;"><strong>'.$doc_type.'</strong></p>';
					}
				}

 

echo '<div class="pad-5 '.$data->project_id.'_files uploaded_files_row" style="display:none;"> &nbsp; 

					<span><em class="fa fa-level-up fa-lg fa-rotate-90" style="color: #269ABC;"></em> &nbsp; 

					<a href="'.base_url().'docs/stored_docs/'.urlencode($data->file_name).'" target="_blank">'.$data->file_name.'</a></span> 

					<span style=" background:#F7901E; font-size: 12px; padding: 1px 8px;    float: right;     border: 1px solid #864e11;  color: #fff;  height: 20px;    margin: 0px 5px;     border-radius: 10px;    display: block;"><em class="fa fa-calendar-o"></em> '.$data->date_upload.' &nbsp; '.$data->user_first_name.'</span>';

// if($this->session->userdata('is_admin') == 1 || $this->session->userdata('user_id') == 6  ):

					echo '<em id="'.$data->storage_files_id.'" class="pointer fa fa-trash fa-lg pull-right del_stored_file" style="color: red; display:none; margin-top: 3px;"></em>';
		
//endif;

					echo '</div>';

 




			//	echo "</td></tr>";  

			}


		}else{
			echo '<div id="" class=""><p>No Uploaded Files</p></div>';
		}




	}




	public function projects_wip_review(){


    if(
    	$this->session->userdata('user_role_id') != 3  &&
    	$this->session->userdata('user_role_id') != 20 && 
    	$this->session->userdata('user_role_id') != 2  && 
    	$this->session->userdata('user_role_id') != 16 && 
    	$this->session->userdata('user_role_id') != 7 && 
    	$this->session->userdata('is_admin') != 1 && 
    	$this->session->userdata('user_id') != 6 ){
		redirect('/projects');
    }     

  
		$data['main_content'] = 'projects_wip_review';
		$data['screen'] = 'Projects WIP Review';
		$data['users'] = $this->user_model->fetch_user();
		$data['page_title'] = 'Projects WIP Review';
		$this->load->view('page', $data);

	}


	public function progress_reports() {

		$project_id = $this->uri->segment(3);

		$check_pr_details = $this->projects_m->check_pr_details($project_id);
		$latest_pr_version = $this->projects_m->latest_pr_version($project_id);

		$lead_hand_id = $this->projects_m->get_project_sched_values($project_id);
		
		if($lead_hand_id->num_rows == 0){
			$this->projects_m->insert_new_project_sched_for_pr($project_id, '0');
		}

		if($check_pr_details->num_rows > 0){
			
			$row = $check_pr_details->row_array();
			$pr_version_increment = $row['pr_version'] + 1;	
			$row2 = $latest_pr_version->row_array();
			
			if ($row['pr_version'] == $row2['pr_version'] && $row['sent'] == 1){
				$insert_blank_pr_details = $this->projects_m->insert_blank_pr_details($project_id, $pr_version_increment);
			}
		} else {
			$insert_blank_pr_details = $this->projects_m->insert_blank_pr_details($project_id, '1');	
		}

		$q_proj = $this->projects_m->fetch_complete_project_details($project_id);
		if($q_proj->num_rows > 0){
			$data = array_shift($q_proj->result_array());

			$q_client_company = $this->company_m->display_company_detail_by_id($data['client_id']);
			$client_company = array_shift($q_client_company->result_array());
			$data['client_company_id'] = $client_company['company_id'];
			$data['client_company_name'] = $client_company['company_name'];

			if ($data['has_brand_logo'] == 1):
				$data['client_company_logo_path'] = '/uploads/brand_logo/'.$data['brand_id'].'.jpg';
			else:
				$data['client_company_logo_path'] = $client_company['logo_path'];
			endif;

			$query_client_address = $this->company_m->fetch_complete_detail_address($client_company['address_id']);
			$temp_data = array_shift($query_client_address->result_array());
			$data['query_client_address_postcode'] = $temp_data['postcode'];
			$data['query_client_address_suburb'] = ucwords(strtolower($temp_data['suburb']));
			$data['query_client_address_po_box'] = $temp_data['po_box'];
			$data['query_client_address_street'] = ucwords(strtolower($temp_data['street']));
			$data['query_client_address_unit_level'] = ucwords(strtolower($temp_data['unit_level']));
			$data['query_client_address_unit_number'] = $temp_data['unit_number'];
			$data['query_client_address_state'] = $temp_data['name'];

			$q_contact_person = $this->company_m->fetch_all_contact_persons($data['primary_contact_person_id']);
			$contact_person = array_shift($q_contact_person->result_array());
			$data['contact_person_id'] = $contact_person['contact_person_id'];
			$data['contact_person_fname'] = $contact_person['first_name'];
			$data['contact_person_lname'] = $contact_person['last_name'];
			$data['contact_person_email_id'] = $contact_person['email_id'];

			$q_contact_person_email_pr = $this->company_m->fetch_email($data['contact_person_email_id']);
			$contact_person_email_pr = array_shift($q_contact_person_email_pr->result_array());

			$data['contact_person_email_pr'] = $contact_person_email_pr['general_email'];

		//  ["email_id"]=> string(3) "537" ["contact_number_id"]=> string(3) "556" }

			$q_fetch_phone = $this->company_m->fetch_phone($contact_person['contact_number_id']);
			$contact_person_phone = array_shift($q_fetch_phone->result_array());

			if($contact_person_phone['office_number'] != ''): 
				$data['contact_person_phone_office'] = $contact_person_phone['area_code'].' '.$contact_person_phone['office_number'];
			else: $data['contact_person_phone_office'] = '';
			endif;


			if($contact_person_phone['direct_number'] != ''): 
				$data['contact_person_phone_direct'] = $contact_person_phone['area_code'].' '.$contact_person_phone['direct_number'];
			else: $data['contact_person_phone_direct'] = '';
			endif;

			if($contact_person_phone['mobile_number'] != ''):
				$data['contact_person_phone_mobile'] = $contact_person_phone['mobile_number'];
			else: $data['contact_person_phone_mobile'] = '';
			endif;

			if($contact_person_phone['after_hours'] != ''):
				$data['contact_person_phone_afterhours'] = $contact_person_phone['area_code'].' '.$contact_person_phone['after_hours'];
			else: $data['contact_person_phone_afterhours'] = '';
			endif;

			$query_address= $this->company_m->fetch_complete_detail_address($data['address_id']);
			$temp_data = array_shift($query_address->result_array());
			$data['postcode'] = $temp_data['postcode'];
			$data['suburb'] = ucwords(strtolower($temp_data['suburb']));
			$data['po_box'] = $temp_data['po_box'];
			$data['street'] = ucwords(strtolower($temp_data['street']));
			$data['unit_level'] = ucwords(strtolower($temp_data['unit_level']));
			$data['unit_number'] = $temp_data['unit_number'];
			$data['state'] = $temp_data['name'];
		// $data['address_id'] = $data['address_id'];

			$data['shortname'] = $temp_data['shortname'];
			$data['state_id'] =  $temp_data['state_id'];
			$data['phone_area_code'] = $temp_data['phone_area_code'];	

			$p_query_address = $this->company_m->fetch_complete_detail_address($data['invoice_address_id']);
			$p_temp_data = array_shift($p_query_address->result_array());
			$data['i_po_box'] = $p_temp_data['po_box'];
			$data['i_unit_level'] = ucwords(strtolower($p_temp_data['unit_level']));
			$data['i_unit_number'] = $p_temp_data['unit_number'];
			$data['i_street'] = ucwords(strtolower($p_temp_data['street']));
			$data['i_suburb'] = ucwords(strtolower($p_temp_data['suburb']));
			$data['i_state'] = $p_temp_data['name'];
			$data['i_postcode'] = $p_temp_data['postcode'];
		//	$data['postal_address_id'] = $company_detail['postal_address_id'];

			$data['i_shortname'] = $p_temp_data['shortname'];
			$data['i_state_id'] =  $p_temp_data['state_id'];
			$data['i_phone_area_code'] = $p_temp_data['phone_area_code'];

			if($contact_person_phone['mobile_number'] != ''):
				$data['contact_person_phone_mobile'] = $contact_person_phone['mobile_number'];
			else: $data['contact_person_phone_mobile'] = '';
			endif;

			$q_project_manager = $this->user_model->fetch_user($data['project_manager_id']);
			$project_manager = array_shift($q_project_manager->result_array());
			$data['pm_user_id'] = $project_manager['user_id'];
			$data['pm_user_first_name'] = $project_manager['user_first_name'];
			$data['pm_user_last_name'] = $project_manager['user_last_name'];
			$data['pm_mobile_number'] = $project_manager['mobile_number'];
			$data['pm_email'] = $project_manager['general_email'];

			$q_project_cc_pm = $this->user_model->fetch_user($data['client_contact_person_id']);
			$project_cc_pm = array_shift($q_project_cc_pm->result_array());
			$data['cc_pm_user_id'] =  $project_cc_pm['user_id'];
			$data['cc_pm_user_first_name'] = $project_cc_pm['user_first_name'];
			$data['cc_pm_user_last_name'] = $project_cc_pm['user_last_name'];
			$data['cc_pm_mobile_number'] = $project_cc_pm['mobile_number'];

			$lead_hand_id = $this->projects_m->get_project_sched_values($project_id);
			$ps_row1 = $lead_hand_id->row();

			$q_project_lead_hand = $this->user_model->fetch_user($ps_row1->leading_hand_id);
			$project_lead_hand = array_shift($q_project_lead_hand->result_array());
			$data['lead_hand_user_id'] =  $project_lead_hand['user_id'];
			$data['lead_hand_user_first_name'] = $project_lead_hand['user_first_name'];
			$data['lead_hand_user_last_name'] = $project_lead_hand['user_last_name'];
			$data['lead_hand_mobile_number'] = $project_lead_hand['mobile_number'];

			$const_mngr_id = $this->projects_m->get_project_sched_values($project_id);
			$ps_row2 = $lead_hand_id->row();

			$q_cons_mngr = $this->user_model->fetch_user($ps_row2->contruction_manager_id);
			$cons_mngr = array_shift($q_cons_mngr->result_array());
			$data['cons_mngr_user_id'] =  $cons_mngr['user_id'];
			$data['cons_mngr_user_first_name'] = $cons_mngr['user_first_name'];
			$data['cons_mngr_user_last_name'] = $cons_mngr['user_last_name'];
			$data['cons_mngr_mobile_number'] = $cons_mngr['mobile_number'];

			$lead_hand_option = $this->user_model->fetch_user_by_role_with_number(15);
			$data['lead_hand_option'] = $lead_hand_option->result();

			$const_mngr_option = $this->user_model->fetch_user_by_role_with_number_combine(11, 19);
			$data['const_mngr_option'] = $const_mngr_option->result();

			$shopping_center_q = $this->projects_m->select_shopping_center($data['address_id']);
			$shopping_center = array_shift($shopping_center_q->result_array());

			$q_proj_sched_details = $this->project_schedule_m->fetch_project_schedule($project_id);
			$data['proj_sched_details'] = $q_proj_sched_details->row();

			$q_proj_sched_details2 = $this->project_schedule_m->fetch_project_schedule($project_id);
			$proj_sched_details2 = $q_proj_sched_details2->row();

			$manual_const_details = $this->projects_m->get_manual_const($proj_sched_details2->project_schedule_id);
			$data['manual_const_details'] = $manual_const_details->row();

			$pr_details = $this->projects_m->get_progress_report_details($project_id);
			$data['pr_details'] = $pr_details->row();

			$pr_images = $this->projects_m->get_progress_report_images($project_id);
			$data['pr_images'] = $pr_images->result_array();

			$pr_versions = $this->projects_m->fetch_all_pr_version($project_id);
			$data['pr_versions'] = $pr_versions->result_array();

			$this->projects_m->set_pr_to_viewed($project_id);

			$data['shopping_center_id'] = $shopping_center['shopping_center_id'];
			$data['shopping_center_brand_name'] = $shopping_center['shopping_center_brand_name'];
			$data['shopping_common_name'] = $shopping_center['common_name'];

			$q_end_user = $this->user_model->fetch_user($this->session->userdata('user_id'));
			$end_user = array_shift($q_end_user->result_array());
			$data['end_user_id'] = $end_user['user_id'];
			$data['end_user_first_name'] = $end_user['user_first_name'];
			$data['end_user_last_name'] = $end_user['user_last_name'];
			$data['end_user_mobile_number'] = $end_user['mobile_number'];
			$data['end_user_email'] = $end_user['general_email'];

			$data['main_content'] = 'progress_reports';
			$data['screen'] = 'Progress Reports';

			$data['page_title'] = 'Progress Report: '.$data['project_name'].' - '.$data['project_id'];

			$this->load->view('page', $data);
		}else{
			redirect('/projects');
		}
	}

	public function progress_report_pdf() {

		$data['pr_version'] = $this->input->post('pr_version');
		$data['project_id'] = $this->input->post('project_id');
		$data['project_name'] = $this->input->post('project_name');
		$data['client_company_name'] = $this->input->post('client_company_name');
		$data['client_company_logo_path'] = $this->input->post('client_company_logo_path');
		$data['site_address1'] = $this->input->post('site_address1');
		$data['site_address2'] = $this->input->post('site_address2');
		$data['scope_of_work'] = $this->input->post('scope_of_work');
		// $data['leading_hand'] = $this->input->post('leading_hand');

		if ($this->input->post('leading_hand') == 'other_leading_hand'){
			$data['leading_hand_name'] = $this->input->post('name_leading_hand');
			$data['leading_hand_contact'] = $this->input->post('mobile_no_leading_hand');
			$data['leading_hand'] = $data['leading_hand_name'].' - '.$data['leading_hand_contact'];
		} else {
			$data['leading_hand_id'] = $this->input->post('leading_hand');

			if ($data['leading_hand_id'] != ''){
				$q_leading_hand = $this->user_model->fetch_user($data['leading_hand_id']);
				$leading_hand = array_shift($q_leading_hand->result_array());
				$data['leading_hand_fname'] = $leading_hand['user_first_name'];
				$data['leading_hand_lname'] = $leading_hand['user_last_name'];
				$data['leading_hand_mobile_number'] = $leading_hand['mobile_number'];

				$data['leading_hand'] = $data['leading_hand_fname'].' '.$data['leading_hand_lname'].' - '.$data['leading_hand_mobile_number'];
			} else {
				$data['leading_hand'] = '-';
			}
		}

		if ($this->input->post('const_mngr') == 'other-offsite-superv'){
			$data['const_mngr_name'] = $this->input->post('name_const_mngr');
			$data['const_mngr_contact'] = $this->input->post('mobile_no_const_mngr');

			$data['const_mngr'] = $data['const_mngr_name'].' - '.$data['const_mngr_contact'];
		} else {
			$data['const_mngr_id'] = $this->input->post('const_mngr');

			if ($data['const_mngr_id'] != ''){
				$q_cons_mngr = $this->user_model->fetch_user($data['const_mngr_id']);
				$cons_mngr = array_shift($q_cons_mngr->result_array());
				$data['cons_mngr_fname'] = $cons_mngr['user_first_name'];
				$data['cons_mngr_lname'] = $cons_mngr['user_last_name'];
				$data['cons_mngr_mobile_number'] = $cons_mngr['mobile_number'];

				$data['const_mngr'] = $data['cons_mngr_fname'].' '.$data['cons_mngr_lname'].' - '.$data['cons_mngr_mobile_number'];
			} else {
				$data['const_mngr'] = '-';
			}
		}

		$data['project_manager'] = $this->input->post('project_manager');
		
		$progress_reports_images = $this->projects_m->get_progress_report_images_select($data['project_id']);		
		$data['progress_reports_images'] = $progress_reports_images->result_array();

		$progress_reports_images_group = $this->projects_m->get_progress_report_images_select_group($data['project_id']);
		$data['progress_reports_images_group'] = $progress_reports_images_group->result_array();


		$this->load->view('progress_report_pdf', $data);
	}

	public function crop_img(){

		$this->load->library('image_lib');

		$ajax_var = $this->input->post('ajax_var');
		$crop_coords = explode('|', $ajax_var);

		$project_id = $crop_coords[0];
		$crop_x = $crop_coords[1];
		$crop_y = $crop_coords[2];
		$crop_width = $crop_coords[3];
		$crop_height = $crop_coords[4];
		$crop_path = $crop_coords[5];
		$crop_new_image = $crop_coords[6];
		$image_name = $crop_coords[7];
		// $image_orientation = $crop_coords[8];
		$backup_path = $crop_coords[8];

		// chmod($crop_new_image, 0755);

		// $this->projects_m->set_image_orientation($image_orientation, $image_name);

		$config['image_library'] = 'gd2';
		$config['source_image'] = $crop_path;
		$config['new_image'] = $crop_new_image;
		$config['maintain_ratio'] = FALSE;
		$config['overwrite'] = TRUE;
		$config['x_axis'] = $crop_x;
		$config['y_axis'] = $crop_y;
		$config['width'] = $crop_width;
		$config['height'] = $crop_height;

		if (!file_exists(dirname($backup_path))){
			mkdir(dirname($backup_path), 0777);
			copy($crop_path, $backup_path);
		} else {
			if (!file_exists($backup_path)){
				copy($crop_path, $backup_path);
			}
		}

		$this->image_lib->initialize($config);

		if ( ! $this->image_lib->crop())
		{
		    echo $this->image_lib->display_errors();
		}
	}

	public function rotate(){

		$this->load->library('image_lib');

		$ajax_var = $this->input->post('ajax_var');
		$rotate_data = explode('|', $ajax_var);

		$project_id = $rotate_data[0];
		$rotate_path = dirname($rotate_data[1]);
		$rotate_angle = $rotate_data[2];
		$image_name0 = $rotate_data[3];
		$image_name = trim($rotate_data[3]);
		// $image_orientation = $rotate_data[4];
		$backup_path = $rotate_data[4];

		// $this->projects_m->set_image_orientation($image_orientation, $image_name0);

		$image_path = $rotate_path.DIRECTORY_SEPARATOR.$image_name;

		$config['image_library'] = 'gd2';		
		$config['source_image'] = $image_path;
		$config['rotation_angle'] = $rotate_angle;

		if (!file_exists(dirname($backup_path))){
			mkdir(dirname($backup_path), 0777);
			copy($image_path, $backup_path);
		} else {
			if (!file_exists($backup_path)){
				copy($image_path, $backup_path);
			}
		}

		$this->image_lib->initialize($config);

		if ( ! $this->image_lib->rotate())
		{
		    echo $this->image_lib->display_errors();
		}

	}

	public function delete_file(){
		
		$filepath = dirname($this->input->get('file'));
		$filename = trim($this->input->get('filename'));
		$backup_path = $this->input->get('backup_path');

		// $filename_only = trim($this->input->get('filename'));
		// $filetype = trim($this->input->get('filename'));
		// $del_path = $this->input->get('del_path');

		$file = $filepath.DIRECTORY_SEPARATOR.$filename;
		// $backup_file = $filepath.DIRECTORY_SEPARATOR.'backup_'.$filename;

		$response = array('status'=>false);

		// chmod($filepath, 0777);

		unlink($file);
		copy($backup_path.DIRECTORY_SEPARATOR.$filename, $file);

		$response['status'] = true;

		// if(file_exists($filepath)) {
		// 	// unlink($file); // delete the edited file
		// 	if (file_exists($file)){
		// 		if (file_exists($del_path.DIRECTORY_SEPARATOR.'copy_'.$filename)){
		// 			unlink($del_path.DIRECTORY_SEPARATOR.$filename);
		// 			copy($del_path.DIRECTORY_SEPARATOR.'copy_'.$filename, $del_path.DIRECTORY_SEPARATOR.$filename);
		// 			copy($file, $del_path.DIRECTORY_SEPARATOR.'copy_'.$filename);
		// 		} else {
		// 			copy($file, $del_path.DIRECTORY_SEPARATOR.$filename);
		// 		}
		// 	} else {
		// 		copy($file, $del_path.DIRECTORY_SEPARATOR.$filename);
		// 	}

		//    	rename($backup_file, $file);
		//    	$response['status'] = true;
		// }

		echo json_encode($response);
	}

	public function edit_image_label(){
		$this->clear_apost();
		$ajax_var = $this->input->post('ajax_var');

		$image_data = explode('|', $ajax_var);

		$image_label = ucfirst($image_data[0]);
		$image_name = trim($image_data[1]);

		$this->projects_m->edit_label_image($image_label, $image_name);

		$q_fetch_label_image = $this->projects_m->fetch_label_image($image_name);
		$fetch_label_image = array_shift($q_fetch_label_image->result_array());
		echo $fetch_label_image['image_label'];
	}

	public function add_leading_hand_from_pr(){
		$this->clear_apost();
		$ajax_var = $this->input->post('ajax_var');

		$lead_hand = explode('|', $ajax_var);

		$leading_hand_id = $lead_hand[0];
		$project_id = $lead_hand[1];

		$this->projects_m->add_leading_hand_from_pr($leading_hand_id, $project_id);
	}

	public function update_pr_details(){

		$this->clear_apost();
		$ajax_var = $this->input->post('ajax_var');

		$pr_details = explode('|', $ajax_var);

		$leading_hand = $pr_details[0];
		$const_mngr = $pr_details[1];
		$scope_of_work = $pr_details[2];
		$project_id = $pr_details[3];
		$pr_version = $pr_details[4];

		if ($leading_hand == '0' && $const_mngr == '0'){
			$name_leading_hand = ucwords($pr_details[5]);
			$mobile_no_leading_hand = $pr_details[6];
			$name_const_mngr = ucwords($pr_details[7]);
			$mobile_no_const_mngr = $pr_details[8];
		}

		if ($leading_hand == '0' && $const_mngr != '0'){
			$name_leading_hand = ucwords($pr_details[5]);
			$mobile_no_leading_hand = $pr_details[6];
		}

		if ($const_mngr == '0' && $leading_hand != '0'){
			$name_const_mngr = ucwords($pr_details[5]);
			$mobile_no_const_mngr = $pr_details[6];
		}

		$this->projects_m->update_project_sched_for_pr_lead($project_id, $leading_hand);
		$this->projects_m->update_project_sched_for_pr_const($project_id, $const_mngr);
		
		$this->projects_m->update_pr_details($scope_of_work, $project_id, $pr_version);

		if ($leading_hand == '0'){

			$proj_sched_details = $this->projects_m->get_project_sched_for_pr($project_id);
			$proj_sched_details_row = $proj_sched_details->row();

			$proj_sched_id = $proj_sched_details_row->project_schedule_id;

			$q_manual_const_details = $this->projects_m->get_manual_const($proj_sched_id);
			// $manual_const_details = $q_manual_const_details->row();

			if ($q_manual_const_details->num_rows() > 0) {
			   	$this->projects_m->update_manual_lead($proj_sched_id, $name_leading_hand, $mobile_no_leading_hand);
			} else {
				$this->projects_m->insert_manual_lead($proj_sched_id, $name_leading_hand, $mobile_no_leading_hand);
			}
		}

		if ($const_mngr == '0'){

			$proj_sched_details = $this->projects_m->get_project_sched_for_pr($project_id);
			$proj_sched_details_row = $proj_sched_details->row();

			$proj_sched_id = $proj_sched_details_row->project_schedule_id;

			$q_manual_const_details = $this->projects_m->get_manual_const($proj_sched_id);
			// $manual_const_details = $q_manual_const_details->row();

			if ($q_manual_const_details->num_rows() > 0) {
			   	$this->projects_m->update_manual_const($proj_sched_id, $name_const_mngr, $mobile_no_const_mngr);
			} else {
				$this->projects_m->insert_manual_const($proj_sched_id, $name_const_mngr, $mobile_no_const_mngr);
			}
		}

		echo 'true';
	}

	public function delete_pr_image(){

		$ajax_var = $this->input->post('ajax_var');
		$pr_image_details = explode('|', $ajax_var);

		$image_id = $pr_image_details[0];
		$project_id = $pr_image_details[1];

		$this->projects_m->delete_pr_image($image_id, $project_id);	
	}

	public function delete_sr_image(){

		$ajax_var = $this->input->post('ajax_var');
		$sr_image_details = explode('|', $ajax_var);

		$image_id = $sr_image_details[0];
		$project_id = $sr_image_details[1];

		$this->projects_m->delete_sr_image($image_id, $project_id);	
	}

	public function set_select_image(){

		$ajax_var = $this->input->post('ajax_var');
		$set_select_data = explode('|', $ajax_var);

		$image_id = $set_select_data[0];
		$project_id = $set_select_data[1];

		$q_selected = $this->projects_m->check_if_selected($image_id, $project_id);
		$img_selected = array_shift($q_selected->result_array());

		$is_selected = $img_selected['is_select'];

		$test = $this->projects_m->set_select_image($image_id, $project_id, $is_selected);

		echo $test;

	}

	public function set_edited_image(){

		$ajax_var = $this->input->post('ajax_var');
		$set_edited_data = explode('|', $ajax_var);

		$image_name = $set_edited_data[0];
		$project_id = $set_edited_data[1];
		$param = $set_edited_data[2];

		$this->projects_m->set_edited_image($image_name, $project_id, $param);
	}

	public function check_pdf(){

		$pr_path = $this->input->get('pr_path');

		if (!file_exists($pr_path)) {
			$response = array('status'=>false);
		} else {
			$response['status'] = true;
		}

		echo json_encode($response);
	}

	public function send_pr_pdf(){

		$ajax_var = $this->input->post('ajax_var');
		$sendpdf_data = explode('|', $ajax_var);

		$sendpdf_main_to = $sendpdf_data[0];
		$sendpdf_other_emails = $sendpdf_data[1];
		$sendpdf_from = $sendpdf_data[2];
		$sendpdf_from_name = $sendpdf_data[3];
		$sendpdf_subject = $sendpdf_data[4];
		$sendpdf_body = $sendpdf_data[5];
		$sendpdf_path = $sendpdf_data[6];
		$sendpdf_cc = $sendpdf_data[7];
		$sendpdf_bcc = $sendpdf_data[8];



		$sendpdf_main_to = str_replace(' ','', $sendpdf_main_to);
		$sendpdf_other_emails = str_replace(' ','', $sendpdf_other_emails);
		$sendpdf_cc = str_replace(' ','', $sendpdf_cc);
		$sendpdf_bcc = str_replace(' ','', $sendpdf_bcc);

		$sendpdf_main_to = str_replace(';',',', $sendpdf_main_to);
		$sendpdf_other_emails = str_replace(';',',', $sendpdf_other_emails);
		$sendpdf_cc = str_replace(';',',', $sendpdf_cc);
		$sendpdf_bcc = str_replace(';',',', $sendpdf_bcc);

		if ( !class_exists("PHPMailer") ){
			require('PHPMailer/class.phpmailer.php'); 
		}

		// PHPMailer class
		$user_mail = new PHPMailer;		
		$user_mail->Host = 'sojourn-focusshopfit-com-au.mail.protection.outlook.com';
		$user_mail->Port = 587;
		
		// $user_mail->SMTPAuth = true;                              		    // Enable SMTP authentication
		// $user_mail->Username = 'invoice@sojourn.focusshopfit.com.au';        // SMTP username
		// $user_mail->Password = '~A8vVJRLz(^]J)L>';                           // SMTP password
		// $user_mail->SMTPSecure = 'ssl';                            		    // Enable TLS encryption, `ssl` also accepted

		$user_mail->setFrom($sendpdf_from, $sendpdf_from_name);
		$user_mail->addReplyTo($sendpdf_from, $sendpdf_from_name);
		
		// $user_mail->setFrom('donot-reply@sojourn.focusshopfit.com.au', 'Sojourn - Accounts');
		// $user_mail->addReplyTo('donot-reply@sojourn.focusshopfit.com.au', 'Sojourn - Accounts');

		if (!empty($sendpdf_main_to)){

			$findthis = ',';
			$pos = strpos($sendpdf_main_to, $findthis);

			if ($pos === false){
				$user_mail->addAddress($sendpdf_main_to);
			} else {
				$sendpdf_main_to_arr = array();
				$sendpdf_main_to_arr = explode(",", $sendpdf_main_to);

				$sendpdf_main_to_arr = array_filter($sendpdf_main_to_arr);


				$sendpdf_main_to_count = count($sendpdf_main_to_arr);

				for ($i=0; $i < $sendpdf_main_to_count; $i++) { 
					$user_mail->addAddress($sendpdf_main_to_arr[$i]);	
				}
			}
		}

		if (!empty($sendpdf_other_emails)){

			$findthis = ',';
			$pos = strpos($sendpdf_other_emails, $findthis);

			if ($pos === false){
				$user_mail->addAddress($sendpdf_other_emails);
			} else {
				$sendpdf_other_emails_arr = array();
				$sendpdf_other_emails_arr = explode(",", $sendpdf_other_emails);
				$sendpdf_other_emails_arr = array_filter($sendpdf_other_emails_arr);
				$sendpdf_other_emails_count = count($sendpdf_other_emails_arr);

				for ($i=0; $i < $sendpdf_other_emails_count; $i++) { 
					$user_mail->addAddress($sendpdf_other_emails_arr[$i]);	
				}
			}
		}

		$user_mail->addReplyTo($sendpdf_from, $sendpdf_from_name);
		
		/*========= CC & BCC emails ========= */

		if (!empty($sendpdf_cc)){

			$findthis = ',';
			$pos = strpos($sendpdf_cc, $findthis);

			if ($pos === false){
				$user_mail->addCC($sendpdf_cc);
			} else {
				$sendpdf_cc_arr = array();
				$sendpdf_cc_arr = explode(",", $sendpdf_cc);
				$sendpdf_cc_arr = array_filter($sendpdf_cc_arr);
				$sendpdf_cc_count = count($sendpdf_cc_arr);

				for ($i=0; $i < $sendpdf_cc_count; $i++) { 
					$user_mail->addCC($sendpdf_cc_arr[$i]);	
				}
			}
		}

		if (!empty($sendpdf_bcc)){

			$findthis = ',';
			$pos = strpos($sendpdf_bcc, $findthis);

			if ($pos === false){
				$user_mail->addBCC($sendpdf_bcc);
			} else {
				$sendpdf_bcc_arr = array();
				$sendpdf_bcc_arr = explode(",", $sendpdf_bcc);
				$sendpdf_bcc_arr = array_filter($sendpdf_bcc_arr);
				$sendpdf_bcc_count = count($sendpdf_bcc_arr);

				for ($i=0; $i < $sendpdf_bcc_count; $i++) { 
					$user_mail->addBCC($sendpdf_bcc_arr[$i]);	
				}
			}
		}

		/*========= CC & BCC emails end ========= */

	//	$user_mail->addBCC('michael@focusshopfit.com.au');
		$user_mail->addBCC($sendpdf_from);

		// $user_mail->smtpdebug  = 2;
		$user_mail->isHTML(true);

		$user_mail->addAttachment($sendpdf_path);         				     // Add attachments
		//$user_mail->addAttachment('/tmp/image.jpg', 'new.jpg');    	     // Optional name
		
		$body_content = '<span style="font-family: "Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif">'.$sendpdf_body.'</span>';
		// $body_content = $this->session->userdata('user_focus_company_id');
		
		$body_content .= '<br><br><span style="font-family: "Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif"><br>Regards,<br><br><strong>'.$this->session->userdata('user_first_name').' '.$this->session->userdata('user_last_name').'</strong><br><br>'.$this->session->userdata('role_types').'<br><span style="color: "#FF3399";>'.$sendpdf_from.'</span></span><br><br>';

		if ($this->session->userdata('user_focus_company_id') == 4){
			// $body_content .= '<img src="'.base_url().'img/signatures/ian.png" /<img src="'.base_url().'img/signatures/FSFGroup.png" />'; // live
			$body_content .= '<img src="https://sojourn.focusshopfit.com.au/img/signatures/FSFGroup.png" />'; // local
		} else if ($this->session->userdata('user_focus_company_id') == 5) {
			// $body_content .= '<img src="'.base_url().'img/signatures/ian.png" /<img src="'.base_url().'img/signatures/FocusPty.png" />'; // live
			$body_content .= '<img src="https://sojourn.focusshopfit.com.au/img/signatures/FocusPty.png" />'; // local
		} else if ($this->session->userdata('user_focus_company_id') == 6) {
			// $body_content .= '<img src="'.base_url().'img/signatures/ian.png" /<img src="'.base_url().'img/signatures/FocusNSW.png" />'; // live
			$body_content .= '<img src="https://sojourn.focusshopfit.com.au/img/signatures/FocusNSW.png" />'; // local
		} else {

		}

		$user_mail->Subject = $sendpdf_subject;		
		$user_mail->Body    = '<span style="font-family: "Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif">'.$body_content."</span>";

		if(!$user_mail->send()) {
			// echo 'Message could not be sent.';
			// echo 'Mailer Error: ' . $user_mail->ErrorInfo;
			$response = array('status'=>false);
		} else {
			$response['status'] = true;
		}

		echo json_encode($response);
	}

	public function sentPRtoClient(){

		$ajax_var = $this->input->post('ajax_var');
		$sentPRtoClientInfo = explode('|', $ajax_var);

		$project_id = $sentPRtoClientInfo[0];
		$pr_version = $sentPRtoClientInfo[1];
		$upload_path = $sentPRtoClientInfo[2];

		$current_date = strtotime("now");

		$this->projects_m->set_pr_images_inactive($project_id, $pr_version);
		$this->projects_m->set_pr_details_sent($project_id, $pr_version, $current_date);

		array_map('unlink', glob($upload_path.'/*'));

		echo 'true';
	}

	public function getProject_PR_images(){

		$user_id = $this->session->userdata('user_id');

		$q_user_if_admin = $this->user_model->fetch_user($user_id);
		$user_if_admin = array_shift($q_user_if_admin->result_array());

		if ($user_if_admin['if_admin'] == '1'){

			$q_project_id_pr_images_admin = $this->projects_m->get_project_id_admin($user_id);
			$project_id_pr_images_admin = $q_project_id_pr_images_admin->result();

			if (!empty($project_id_pr_images_admin)){

				echo '<a href="#" id="drop3" role="button" class="pr-notif dropdown-toggle ave_status_text" data-toggle="dropdown" style="display: block;"><span style="color:white; background: red;"><i class="fa fa-line-chart"></i></span> <b class="caret"></b></a>';
				echo '<ul class="pr-notif-ul dropdown-menu" role="menu" aria-labelledby="drop3">';
							
				foreach ($project_id_pr_images_admin as $row){

					echo '<li role="">';
					echo	'<a href="'.base_url().'projects/progress_reports/'.$row->project_id.'" role="menuitem" tabindex="-1"  style="color: red;"><i class="fa fa-exclamation-circle"></i> '.$row->project_id.' </a>';
					echo '</li>';
				}

				echo '</ul>';

			}
		} else {


			
			if ($user_if_admin['user_role_id'] == '2'){ // PA
				
				$project_id_pr_images = $this->projects_m->get_project_id_pa($user_id);
				$project_id_pr_images = $project_id_pr_images->result();

				if (!empty($project_id_pr_images)){

					echo '<a href="#" id="drop3" role="button" class="pr-notif dropdown-toggle ave_status_text" data-toggle="dropdown" style="display: block;"><span style="color:white; background: red;"><i class="fa fa-line-chart"></i></span> <b class="caret"></b></a>';
					echo '<ul class="pr-notif-ul dropdown-menu" role="menu" aria-labelledby="drop3">';
								
					foreach ($project_id_pr_images as $row){

						echo '<li role="">';
						echo	'<a href="'.base_url().'projects/progress_reports/'.$row->project_id.'" role="menuitem" tabindex="-1"  style="color: red;"><i class="fa fa-exclamation-circle"></i> '.$row->project_id.' </a>';
						echo '</li>';
					}

					echo '</ul>';

				}

			} elseif ($user_if_admin['user_role_id'] == '3' || $user_if_admin['user_role_id'] == '20') { // PM and AM

				$project_id_pr_images = $this->projects_m->get_project_id_pm($user_id);
				$project_id_pr_images = $project_id_pr_images->result();

				if (!empty($project_id_pr_images)){

					echo '<a href="#" id="drop3" role="button" class="pr-notif dropdown-toggle ave_status_text" data-toggle="dropdown" style="display: block;"><span style="color:white; background: red;"><i class="fa fa-line-chart"></i></span> <b class="caret"></b></a>';
					echo '<ul class="pr-notif-ul dropdown-menu" role="menu" aria-labelledby="drop3">';
								
					foreach ($project_id_pr_images as $row){

						echo '<li role="">';
						echo	'<a href="'.base_url().'projects/progress_reports/'.$row->project_id.'" role="menuitem" tabindex="-1"  style="color: red;"><i class="fa fa-exclamation-circle"></i> '.$row->project_id.' </a>';
						echo '</li>';
					}

					echo '</ul>';

				}

			} else {

				echo '';

			}

			

		}
	}

	public function groupImg(){

		$ajax_var = $this->input->post('ajax_var');
		$groupImg = explode('|', $ajax_var);

		$image_name = $groupImg[0];
		$project_id = $groupImg[1];

		$group_img_select = $this->projects_m->get_progress_report_images_by_name($image_name, $project_id);
		$group_img_selected = array_shift($group_img_select->result_array());
		$group_img_selected = $group_img_selected['group_id'];

		$group_list = $this->projects_m->get_group_for_img();
		$group_list = $group_list->result();

		if (!empty($group_list)){

			// echo '<label id="groupLabel" style="color: #d8d8d8;font-size: 13px; display: inline;">Group: </label>';
			// echo '<select id="groupSelect" class="form-control input-sm" style="width: 50%; display: inline-block;">';
			echo '<option value="0">Select Group...</option>';

			foreach ($group_list as $row){

				if ($group_img_selected == $row->group_id){
					echo '<option value="'.$row->group_id.'" selected>';
					echo	$row->description;
					echo '</option>';
				} else {
					echo '<option value="'.$row->group_id.'">';
					echo	$row->description;
					echo '</option>';
				}
			}

			// echo '<option value="-1">Add New Group</option>';

			// echo '</select>';

		} else {
			echo '';
		}

	}

	public function updateGroupImg(){

		$ajax_var = $this->input->post('ajax_var');
		$selectedGroupImg = explode('|', $ajax_var);

		$image_name = $selectedGroupImg[0];
		$project_id = $selectedGroupImg[1];
		$selected_group_id = $selectedGroupImg[2];

		$this->projects_m->update_group_id($project_id, $image_name, $selected_group_id);
	}

	public function updateGroupImg2(){

		$ajax_var = $this->input->post('ajax_var');
		$selectedGroupImg = explode('|', $ajax_var);

		$image_name = $selectedGroupImg[0];
		$project_id = $selectedGroupImg[1];
		$selected_group_id = $selectedGroupImg[2];

		$this->projects_m->update_group_id2($project_id, $image_name, $selected_group_id);
	}

	public function addNewGroup(){

		$ajax_var = $this->input->post('ajax_var');
		$addNewGroup = explode('|', $ajax_var);

		$new_group = $addNewGroup[0];

		$this->projects_m->add_group($new_group);
	}

	public function download() {
	    // load download helder
	    $this->load->helper('download');
	    $file_name = $_GET['file_name'];
	    $proj_id = $_GET['proj_id'];
	    // read file contents
		$fileName = basename($file_name);
		$filePath = './reports/project_progress_report/'.$proj_id.'/'.$file_name;

		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$fileName");
		header("Content-Type: application/pdf");
		header("Content-Transfer-Encoding: binary");

		readfile($filePath);
		exit;

	    // $data = file_get_contents('./uploads/project_attachments/'.$proj_id.'/'.$file_name);
	    // force_download($data, NULL);
	}

	public function do_upload() {

		$project_id = $this->uri->segment(3);
		$upload_path = $_POST['upload_path'];
		
	    $this->load->library('upload');
	    $this->load->library('image_lib');

	    $files = $_FILES;
	    $cpt = count($_FILES['userfile']['name']);

	    for($i=0; $i<$cpt; $i++)
	    {
	    	$file_name =  $files['userfile']['name'][$i];
	    	$file_name = str_replace(' ', '_', $file_name);
	    	
	    	$pr_images = $this->projects_m->get_progress_report_images_all($project_id);
	    	
	    	// $file = explode('.', $file_name);
	    	// $filename = $file[0];
	    	// $extension = $file[1];
	    	// $filename = $path_parts['filename'];

	    	$path_parts = pathinfo($file_name);
			$filename = str_replace(' ', '_', $path_parts['filename']);
			$extension = strtolower($path_parts['extension']);

	    	$file_exist = 0;

	    	foreach ($pr_images->result_array() as $row){

	    		$db_file_name = basename($row['image_path']);
	    		$db_file = explode('.', $db_file_name);
		    	$db_filename = $db_file[0];
		    	$db_extension = $db_file[1];

		    	if (strpos($db_filename, $filename) !== false) {
		    		if($extension == $db_extension){
		    			$file_exist = $file_exist + 12345;
		    		}	
		    	}
	    	}

	    	$file_name = $filename.".".$extension;

	    	if($file_exist > 0){
	    		$filename = $filename."_".$file_exist;
	    		$file_name = $filename.".".$extension;
	    	}

	    	$image_path = $upload_path.$file_name;

		    $_FILES['userfile']['name']= $file_name;
		    $_FILES['userfile']['type']= $files['userfile']['type'][$i];
		    $_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
		    $_FILES['userfile']['error']= $files['userfile']['error'][$i];
		    $_FILES['userfile']['size']= $files['userfile']['size'][$i];    

		    $this->upload->initialize($this->set_upload_options($project_id));

		   	if ( !$this->upload->do_upload()) {
		   		echo $this->upload->display_errors();
		   	}else{

		   		$config['image_library'] = 'gd2';
	            $config['source_image'] = $this->upload->upload_path.$this->upload->file_name;
	            $config['maintain_ratio'] = TRUE;
				$config['width'] = '1000';
				$config['height'] = '1000';

	            $filename = $_FILES['userfile']['tmp_name'];

	            $imgdata=exif_read_data($this->upload->upload_path.$this->upload->file_name, 'IFD0');

	            // $this->load->library('image_lib',$config); 
	            $this->image_lib->initialize($config);

	            if (!$this->image_lib->resize()){  
	                echo "error";
	            }else{

	                $this->image_lib->clear();
	                $config=array();

	                $config['image_library'] = 'gd2';
	                $config['source_image'] = $this->upload->upload_path.$this->upload->file_name;

	                switch($imgdata['Orientation']) {
	                    case 3:
	                        $config['rotation_angle']='180';
	                        break;
	                    case 6:
	                        $config['rotation_angle']='270';
	                        break;
	                    case 8:
	                        $config['rotation_angle']='90';
	                        break;
	                }
	            }

	                $this->image_lib->initialize($config); 
	                $this->image_lib->rotate();
	        }

	        $this->projects_m->insert_pr_images($project_id,$image_path);
	    }

    	redirect('projects/progress_reports/'.$project_id);
	}

	private function set_upload_options($project_id) {   
	//  upload an image options
		$path = "./uploads/project_progress_report/".$project_id;
		if(!is_dir($path)){
			mkdir($path, 0755, true);
		}
	    $config = array();
	    $config['upload_path'] = $path."/";
	    $config['allowed_types'] = 'gif|jpg|png';
	    $config['max_size']      = '0';
	    $config['overwrite']     = FALSE;

	    return $config;
	}

	public function set_pa(){

		$selected_pm = $this->input->post('selected_pm');

		$q_fetch_pa = $this->projects_m->fetch_pa($selected_pm);
		$fetch_pa = array_shift($q_fetch_pa->result_array());

		$set_proj_admin = $fetch_pa['project_administrator_id'];


		echo $set_proj_admin;
	}

	public function set_warranty(){

		$q_admin_defaults = $this->admin_m->fetch_admin_defaults();
		$admin_defaults = array_shift($q_admin_defaults->result_array());

		$warranty_months = $admin_defaults['warranty_months'];
		$warranty_years = $admin_defaults['warranty_years'];

		$q_warranty_categories = $this->projects_m->fetch_warranty_categories();
		$data['warranty_categories'] = array_shift($q_warranty_categories->result_array());

		$q_warranty_categories = $this->projects_m->fetch_warranty_categories();
		$data['warranty_categories'] = array_shift($q_warranty_categories->result_array());

		$raw_warranty_cat = implode(",",$data['warranty_categories']);
		$replaced_warranty_cat = str_replace(",", "','", $raw_warranty_cat)."'";
		$format_warranty_cat = substr_replace($replaced_warranty_cat, "", 0, 2);
		
		$q_fetch_projects = $this->projects_m->display_all_projects('AND `project`.`job_category` IN ('.$format_warranty_cat.')');
		$fetch_projects = $q_fetch_projects->result_array();

		foreach ($fetch_projects as $row) {

			$date = $row['date_site_finish'];
			$date = str_replace('/', '-', $date);
			$date_finish = date('Y-m-d', strtotime($date));

			$warranty_date_year = date('Y-m-d', strtotime("+".$warranty_years." months", strtotime($date_finish)));
			$warranty_date = date('d/m/Y', strtotime("+".$warranty_months." months", strtotime($warranty_date_year)));

			$this->projects_m->update_warranty_date($warranty_date, $row['project_id']);
		}
	}

	public function set_warranty_date_after_paid($project_id){

		$q_admin_defaults = $this->admin_m->fetch_admin_defaults();
		$admin_defaults = array_shift($q_admin_defaults->result_array());

		$warranty_months = $admin_defaults['warranty_months'];
		$warranty_years = $admin_defaults['warranty_years'];
		
		$q_fetch_project = $this->projects_m->select_particular_project($project_id);
		$fetch_project = $q_fetch_project->result_array();

		foreach ($fetch_project as $row) {

			$date = $row['date_site_finish'];
			$date = str_replace('/', '-', $date);
			$date_finish = date('Y-m-d', strtotime($date));

			$warranty_date_year = date('Y-m-d', strtotime("+".$warranty_years." months", strtotime($date_finish)));
			$warranty_date = date('d/m/Y', strtotime("+".$warranty_months." months", strtotime($warranty_date_year)));

			$this->projects_m->update_warranty_date($warranty_date, $row['project_id']);
		}
	}

	public function delete_all_images(){
		$this->clear_apost();
		$ajax_var = $this->input->post('ajax_var');

		$progress_images = explode('|', $ajax_var);

		$project_id = $progress_images[0];

		$this->projects_m->delete_all_images($project_id);
	}

	public function select_all_images(){
		$this->clear_apost();
		$ajax_var = $this->input->post('ajax_var');

		$progress_images = explode('|', $ajax_var);

		$project_id = $progress_images[0];

		$this->projects_m->select_all_images($project_id);
	}

	public function induction_qrcode(){
		include('./phpqrcode/qrlib.php');
	
		//QRcode::png('code data text', 'filename.png'); // creates file 
		QRcode::png('https://sojourn.focusshopfit.com.au/direct_contractor_upload/contractor_induction_video?project_id=35021'); // creates code image and outputs it directly into browser
	
	} 

	public function induction_qrcode_file(){
		$project_id = $_POST['project_id'];
		include('./phpqrcode/qrlib.php');
		$tempDir = 'docs/tempqrcode/'.$project_id.'/'; 
     	if(!is_dir($tempDir)){
			mkdir($tempDir,0755,TRUE);
		}

	    $codeContents = 'https://sojourn.focusshopfit.com.au/direct_contractor_upload/contractor_induction_video?project_id='.$project_id; 
	     
	    // we need to generate filename somehow,  
	    // with md5 or with database ID used to obtains $codeContents... 
	    $fileName = 'qrcode.png'; 
	     
	    $pngAbsoluteFilePath = $tempDir.$fileName; 
	    //$urlRelativeFilePath = EXAMPLE_TMP_URLRELPATH.$fileName; 
	     
	    // generating 
	    if (!file_exists($pngAbsoluteFilePath)) { 
	        QRcode::png($codeContents, $pngAbsoluteFilePath); 
	        echo 'File generated!'; 
	        echo '<hr />'; 
	    } else { 
	    	$handle=opendir($tempDir);
			while (($file = readdir($handle))!==false) {
				@unlink($tempDir.'/'.$file);
			}
			QRcode::png($codeContents, $pngAbsoluteFilePath); 
	        echo 'File generated!'; 
	        echo '<hr />'; 

	        // echo 'File already generated! We can use this cached file to speed up site on common codes!'; 
	        // echo '<hr />'; 
	    } 
	     
	    echo 'Server PNG File: '.$pngAbsoluteFilePath; 
	    echo '<hr />'; 
	}

	public function service_report() {

		$project_id = $this->uri->segment(3);

		$q_proj = $this->projects_m->fetch_complete_project_details($project_id);
		if($q_proj->num_rows > 0){
			$data = array_shift($q_proj->result_array());

			$data['po_client'] = $data['client_po'];

			$q_client_company = $this->company_m->display_company_detail_by_id($data['client_id']);
			$client_company = array_shift($q_client_company->result_array());
			$data['client_company_id'] = $client_company['company_id'];
			$data['client_company_name'] = $client_company['company_name'];

			if ($data['has_brand_logo'] == 1):
				$data['client_company_logo_path'] = '/uploads/brand_logo/'.$data['brand_id'].'.jpg';
			else:
				$data['client_company_logo_path'] = $client_company['logo_path'];
			endif;

			$query_client_address = $this->company_m->fetch_complete_detail_address($client_company['address_id']);
			$temp_data = array_shift($query_client_address->result_array());
			$data['query_client_address_postcode'] = $temp_data['postcode'];
			$data['query_client_address_suburb'] = ucwords(strtolower($temp_data['suburb']));
			$data['query_client_address_po_box'] = $temp_data['po_box'];
			$data['query_client_address_street'] = ucwords(strtolower($temp_data['street']));
			$data['query_client_address_unit_level'] = ucwords(strtolower($temp_data['unit_level']));
			$data['query_client_address_unit_number'] = $temp_data['unit_number'];
			$data['query_client_address_state'] = $temp_data['name'];

			$q_contact_person = $this->company_m->fetch_all_contact_persons($data['primary_contact_person_id']);
			$contact_person = array_shift($q_contact_person->result_array());
			$data['contact_person_id'] = $contact_person['contact_person_id'];
			$data['contact_person_fname'] = $contact_person['first_name'];
			$data['contact_person_lname'] = $contact_person['last_name'];
			$data['contact_person_email_id'] = $contact_person['email_id'];

			$q_contact_person_email_pr = $this->company_m->fetch_email($data['contact_person_email_id']);
			$contact_person_email_pr = array_shift($q_contact_person_email_pr->result_array());

			$data['contact_person_email_pr'] = $contact_person_email_pr['general_email'];

		//  ["email_id"]=> string(3) "537" ["contact_number_id"]=> string(3) "556" }

			$q_fetch_phone = $this->company_m->fetch_phone($contact_person['contact_number_id']);
			$contact_person_phone = array_shift($q_fetch_phone->result_array());

			if($contact_person_phone['office_number'] != ''): 
				$data['contact_person_phone_office'] = $contact_person_phone['area_code'].' '.$contact_person_phone['office_number'];
			else: $data['contact_person_phone_office'] = '';
			endif;


			if($contact_person_phone['direct_number'] != ''): 
				$data['contact_person_phone_direct'] = $contact_person_phone['area_code'].' '.$contact_person_phone['direct_number'];
			else: $data['contact_person_phone_direct'] = '';
			endif;

			if($contact_person_phone['mobile_number'] != ''):
				$data['contact_person_phone_mobile'] = $contact_person_phone['mobile_number'];
			else: $data['contact_person_phone_mobile'] = '';
			endif;

			if($contact_person_phone['after_hours'] != ''):
				$data['contact_person_phone_afterhours'] = $contact_person_phone['area_code'].' '.$contact_person_phone['after_hours'];
			else: $data['contact_person_phone_afterhours'] = '';
			endif;

			$query_address= $this->company_m->fetch_complete_detail_address($data['address_id']);
			$temp_data = array_shift($query_address->result_array());
			$data['postcode'] = $temp_data['postcode'];
			$data['suburb'] = ucwords(strtolower($temp_data['suburb']));
			$data['po_box'] = $temp_data['po_box'];
			$data['street'] = ucwords(strtolower($temp_data['street']));
			$data['unit_level'] = ucwords(strtolower($temp_data['unit_level']));
			$data['unit_number'] = $temp_data['unit_number'];
			$data['state'] = $temp_data['name'];
		// $data['address_id'] = $data['address_id'];

			$data['shortname'] = $temp_data['shortname'];
			$data['state_id'] =  $temp_data['state_id'];
			$data['phone_area_code'] = $temp_data['phone_area_code'];	

			$p_query_address = $this->company_m->fetch_complete_detail_address($data['invoice_address_id']);
			$p_temp_data = array_shift($p_query_address->result_array());
			$data['i_po_box'] = $p_temp_data['po_box'];
			$data['i_unit_level'] = ucwords(strtolower($p_temp_data['unit_level']));
			$data['i_unit_number'] = $p_temp_data['unit_number'];
			$data['i_street'] = ucwords(strtolower($p_temp_data['street']));
			$data['i_suburb'] = ucwords(strtolower($p_temp_data['suburb']));
			$data['i_state'] = $p_temp_data['name'];
			$data['i_postcode'] = $p_temp_data['postcode'];
		//	$data['postal_address_id'] = $company_detail['postal_address_id'];

			$data['i_shortname'] = $p_temp_data['shortname'];
			$data['i_state_id'] =  $p_temp_data['state_id'];
			$data['i_phone_area_code'] = $p_temp_data['phone_area_code'];

			// if($contact_person_phone['mobile_number'] != ''):
			// 	$data['contact_person_phone_mobile'] = $contact_person_phone['mobile_number'];
			// else: $data['contact_person_phone_mobile'] = '';
			// endif;

			// $q_project_manager = $this->user_model->fetch_user($data['project_manager_id']);
			// $project_manager = array_shift($q_project_manager->result_array());
			// $data['pm_user_id'] = $project_manager['user_id'];
			// $data['pm_user_first_name'] = $project_manager['user_first_name'];
			// $data['pm_user_last_name'] = $project_manager['user_last_name'];
			// $data['pm_mobile_number'] = $project_manager['mobile_number'];
			// $data['pm_email'] = $project_manager['general_email'];

			// $q_project_cc_pm = $this->user_model->fetch_user($data['client_contact_person_id']);
			// $project_cc_pm = array_shift($q_project_cc_pm->result_array());
			// $data['cc_pm_user_id'] =  $project_cc_pm['user_id'];
			// $data['cc_pm_user_first_name'] = $project_cc_pm['user_first_name'];
			// $data['cc_pm_user_last_name'] = $project_cc_pm['user_last_name'];
			// $data['cc_pm_mobile_number'] = $project_cc_pm['mobile_number'];

			// $lead_hand_id = $this->projects_m->get_project_sched_values($project_id);
			// $ps_row1 = $lead_hand_id->row();

			// $q_project_lead_hand = $this->user_model->fetch_user($ps_row1->leading_hand_id);
			// $project_lead_hand = array_shift($q_project_lead_hand->result_array());
			// $data['lead_hand_user_id'] =  $project_lead_hand['user_id'];
			// $data['lead_hand_user_first_name'] = $project_lead_hand['user_first_name'];
			// $data['lead_hand_user_last_name'] = $project_lead_hand['user_last_name'];
			// $data['lead_hand_mobile_number'] = $project_lead_hand['mobile_number'];

			// $const_mngr_id = $this->projects_m->get_project_sched_values($project_id);
			// $ps_row2 = $lead_hand_id->row();

			// $q_cons_mngr = $this->user_model->fetch_user($ps_row2->contruction_manager_id);
			// $cons_mngr = array_shift($q_cons_mngr->result_array());
			// $data['cons_mngr_user_id'] =  $cons_mngr['user_id'];
			// $data['cons_mngr_user_first_name'] = $cons_mngr['user_first_name'];
			// $data['cons_mngr_user_last_name'] = $cons_mngr['user_last_name'];
			// $data['cons_mngr_mobile_number'] = $cons_mngr['mobile_number'];

			// $lead_hand_option = $this->user_model->fetch_user_by_role_with_number(15);
			// $data['lead_hand_option'] = $lead_hand_option->result();

			// $const_mngr_option = $this->user_model->fetch_user_by_role_with_number_combine(11, 19);
			// $data['const_mngr_option'] = $const_mngr_option->result();

			$shopping_center_q = $this->projects_m->select_shopping_center($data['address_id']);
			$shopping_center = array_shift($shopping_center_q->result_array());

			// $q_proj_sched_details = $this->project_schedule_m->fetch_project_schedule($project_id);
			// $data['proj_sched_details'] = $q_proj_sched_details->row();

			// $q_proj_sched_details2 = $this->project_schedule_m->fetch_project_schedule($project_id);
			// $proj_sched_details2 = $q_proj_sched_details2->row();

			// $manual_const_details = $this->projects_m->get_manual_const($proj_sched_details2->project_schedule_id);
			// $data['manual_const_details'] = $manual_const_details->row();

			// $pr_details = $this->projects_m->get_progress_report_details($project_id);
			// $data['pr_details'] = $pr_details->row();

			// $pr_images = $this->projects_m->get_progress_report_images($project_id);
			// $data['pr_images'] = $pr_images->result_array();

			// $pr_versions = $this->projects_m->fetch_all_pr_version($project_id);
			// $data['pr_versions'] = $pr_versions->result_array();

			// $this->projects_m->set_pr_to_viewed($project_id);

			$sr_details = $this->projects_m->get_service_report_details($project_id);
			$data['sr_details'] = $sr_details->row();

			$sr_images_si = $this->projects_m->fetch_service_report_images_si($project_id);
			$data['sr_images_si'] = $sr_images_si->result_array();

			$sr_images_comp = $this->projects_m->fetch_service_report_images_comp($project_id);
			$data['sr_images_comp'] = $sr_images_comp->result_array();

			$data['shopping_center_id'] = $shopping_center['shopping_center_id'];
			$data['shopping_center_brand_name'] = $shopping_center['shopping_center_brand_name'];
			$data['shopping_common_name'] = $shopping_center['common_name'];

			$q_end_user = $this->user_model->fetch_user($this->session->userdata('user_id'));
			$end_user = array_shift($q_end_user->result_array());
			$data['end_user_id'] = $end_user['user_id'];
			$data['end_user_first_name'] = $end_user['user_first_name'];
			$data['end_user_last_name'] = $end_user['user_last_name'];
			$data['end_user_mobile_number'] = $end_user['mobile_number'];
			$data['end_user_email'] = $end_user['general_email'];

			$data['main_content'] = 'service_report';
			$data['screen'] = 'Service Report';

			$data['page_title'] = 'Service Report: '.$data['project_name'].' - '.$data['project_id'];

			$this->load->view('page', $data);
		}else{
			redirect('/projects');
		}
	}

	public function service_report_img_upload() {

		$project_id = $this->uri->segment(3);
		$upload_path = $_POST['upload_path'];
		$is_inspection = $_POST['is_inspection'];
		$is_completion = $_POST['is_completion'];
		
	    $this->load->library('upload');
	    $this->load->library('image_lib');

	    $files = $_FILES;
	    $cpt = count($_FILES['userfile']['name']);

	    for($i=0; $i<$cpt; $i++)
	    {
	    	$file_name =  $files['userfile']['name'][$i];
	    	$file_name = str_replace(' ', '_', $file_name);
	    	
	    	$sr_images_all = $this->projects_m->fetch_service_report_images_all($project_id);
	    	
	    	// $file = explode('.', $file_name);
	    	// $filename = $file[0];
	    	// $extension = $file[1];
	    	// $filename = $path_parts['filename'];

	    	$path_parts = pathinfo($file_name);
			$filename = str_replace(' ', '_', $path_parts['filename']);
			$extension = strtolower($path_parts['extension']);

	    	$file_exist = 0;

	    	foreach ($sr_images_all->result_array() as $row){

	    		$db_file_name = basename($row['image_path']);
	    		$db_file = explode('.', $db_file_name);
		    	$db_filename = $db_file[0];
		    	$db_extension = $db_file[1];

		    	if (strpos($db_filename, $filename) !== false) {
		    		if($extension == $db_extension){
		    			$file_exist = $file_exist + 12345;
		    		}	
		    	}
	    	}

	    	$file_name = $filename.".".$extension;

	    	if($file_exist > 0){
	    		$filename = $filename."_".$file_exist;
	    		$file_name = $filename.".".$extension;
	    	}

	    	$image_path = $upload_path.$file_name;

		    $_FILES['userfile']['name']= $file_name;
		    $_FILES['userfile']['type']= $files['userfile']['type'][$i];
		    $_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
		    $_FILES['userfile']['error']= $files['userfile']['error'][$i];
		    $_FILES['userfile']['size']= $files['userfile']['size'][$i];    

		    $this->upload->initialize($this->set_upload_options_sr($project_id));

		   	if ( !$this->upload->do_upload()) {
		   		echo $this->upload->display_errors();
		   	}else{

		   		$config['image_library'] = 'gd2';
	            $config['source_image'] = $this->upload->upload_path.$this->upload->file_name;
	            $config['maintain_ratio'] = TRUE;
				$config['width'] = '1000';
				$config['height'] = '1000';

	            $filename = $_FILES['userfile']['tmp_name'];

	            $imgdata=exif_read_data($this->upload->upload_path.$this->upload->file_name, 'IFD0');

	            // $this->load->library('image_lib',$config); 
	            $this->image_lib->initialize($config);

	            if (!$this->image_lib->resize()){  
	                echo "error";
	            }else{

	                $this->image_lib->clear();
	                $config=array();

	                $config['image_library'] = 'gd2';
	                $config['source_image'] = $this->upload->upload_path.$this->upload->file_name;

	                switch($imgdata['Orientation']) {
	                    case 3:
	                        $config['rotation_angle']='180';
	                        break;
	                    case 6:
	                        $config['rotation_angle']='270';
	                        break;
	                    case 8:
	                        $config['rotation_angle']='90';
	                        break;
	                }
	            }

	                $this->image_lib->initialize($config); 
	                $this->image_lib->rotate();
	        }

			if ($is_inspection == '1'){
				$this->projects_m->insert_sr_images($project_id,$image_path,'1','0');
			} 

			if ($is_completion == '1'){
				$this->projects_m->insert_sr_images($project_id,$image_path,'0','1');
			}
	    }

    	redirect('projects/service_report/'.$project_id);
	}

	private function set_upload_options_sr($project_id) {   
	//  upload an image options
		$path = "./uploads/service_report_images/".$project_id;
		if(!is_dir($path)){
			mkdir($path, 0755, true);
		}
	    $config = array();
	    $config['upload_path'] = $path."/";
	    $config['allowed_types'] = 'gif|jpg|png|jpeg|JPG|PNG';
	    $config['max_size']      = '0';
	    $config['overwrite']     = FALSE;

	    return $config;
	}

	public function save_service_report_details() {

		$this->clear_apost();

		$ajax_var = $this->input->post('ajax_var');
		$service_report_details = explode('|', $ajax_var);
		
		$data['project_id'] = $service_report_details[0];
		$data['site_inspection_details'] = $service_report_details[1];
		$data['completion_details'] = $service_report_details[2];

		$check_sr_details = $this->projects_m->check_sr_details($data['project_id']);

		if($check_sr_details->num_rows == 0){
			$result = $this->projects_m->insert_sr_details($data['project_id'], $data['site_inspection_details'], $data['completion_details']);
		} else {
			$result = $this->projects_m->update_sr_details($data['project_id'], $data['site_inspection_details'], $data['completion_details']);
		}

		echo $result;
	}

	public function service_report_pdf() {
		
		$data['project_id'] = $this->input->post('project_id');
		$data['po_client'] = $this->input->post('po_client');

		$data['project_name_footer'] = rtrim(strtoupper($this->input->post('project_name_footer')));
		$data['project_suburb_footer'] = strtoupper($this->input->post('project_suburb_footer'));

		$data['project_footer'] = $data['project_name_footer'].' '.$data['project_suburb_footer'];

		$data['site_inspection_include'] = $this->input->post('site_inspection_include');
		$data['completion_include'] = $this->input->post('completion_include');

		$data['site_inspection_details'] = $this->input->post('site_inspection_details');
		$data['completion_details'] = $this->input->post('completion_details');

		$sr_images_si = $this->projects_m->fetch_service_report_images_si($data['project_id']);
		$data['sr_images_si'] = $sr_images_si->result_array();

		$sr_images_comp = $this->projects_m->fetch_service_report_images_comp($data['project_id']);
		$data['sr_images_comp'] = $sr_images_comp->result_array();

		$this->load->view('service_report_pdf', $data);
	}

	public function copy_report_to_docstroge(){
		$report_type = $_POST['report_type'];
		
		$proj_id = $_POST['project_id'];

		$user_id = $this->session->userdata('user_id');

		$time = time();

    	$date_upload = date("d/m/Y");

		$proj_q = $this->projects_m->select_particular_project($proj_id);
		foreach ($proj_q->result_array() as $row){
			$client_id = $row['client_id'];
			$compname = $row['company_name'];
			$company_name = str_replace(' ', '', $compname);
		}

		switch($report_type){
			case "pswc":
				$file_type = 27;
				$data_file_name = $proj_id.'_project_summary_w_cost'.$time.'.pdf';
				$file_name_set = str_replace(' ', '_', $data_file_name);
				$src_file = './reports/'.$client_id.'_'.$company_name.'/'.$proj_id.'/project_summary_w_cost.pdf';
				break;
			case "pswoc":
				$file_type = 27;
				$data_file_name = $proj_id.'_project_summary_wo_cost'.$time.'.pdf';
				$file_name_set = str_replace(' ', '_', $data_file_name);
				$src_file = './reports/'.$client_id.'_'.$company_name.'/'.$proj_id.'/project_summary_w_cost.pdf';
				break;
			case "jswc":
				$file_type = 33;
				$data_file_name = $proj_id.'_joinery_summary_w_cost'.$time.'.pdf';
				$file_name_set = str_replace(' ', '_', $data_file_name);
				$src_file = './reports/'.$client_id.'_'.$company_name.'/'.$proj_id.'/joinery_summary_w_cost.pdf';
				break;
			case "jswoc":
				$file_type = 33;
				$data_file_name = $proj_id.'_joinery_summary_wo_cost'.$time.'.pdf';
				$file_name_set = str_replace(' ', '_', $data_file_name);
				$src_file = './reports/'.$client_id.'_'.$company_name.'/'.$proj_id.'/joinery_summary_wo_cost.pdf';
				break;
			case "var_sum":
				$file_type = 28;
				$data_file_name = $proj_id.'_variation_summary'.$time.'.pdf';
				$file_name_set = str_replace(' ', '_', $data_file_name);
				$src_file = './reports/'.$client_id.'_'.$company_name.'/'.$proj_id.'/variation_summary.pdf';
				break;
			case "proj_details":
				$file_type = 34;
				$data_file_name = $proj_id.'_project_details'.$time.'.pdf';
				$file_name_set = str_replace(' ', '_', $data_file_name);
				$src_file = './reports/'.$client_id.'_'.$company_name.'/'.$proj_id.'/project_details.pdf';
				break;
		}

		$destination_file = './docs/stored_docs/'.$data_file_name;

		$this->projects_m->insert_uploaded_file($file_name_set,$file_type,$proj_id,$date_upload,$user_id);
		copy($src_file, $destination_file);
	}
}