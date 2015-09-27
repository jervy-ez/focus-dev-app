<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Purchase_order extends MY_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->module('users');
		$this->load->model('purchase_order_m');
		$this->load->module('company');
		$this->load->model('company_m');
		$this->load->module('works');
		$this->load->model('works_m');
		$this->load->model('admin_m');
		$this->load->model('user_model');
		if(!$this->users->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;

		if(isset($_GET['reload'])){
			redirect('purchase_order', 'refresh');
		}
	}


	public function clear_apost(){
		foreach ($_POST as $key => $value) {
			$_POST[$key] = str_replace("'","&apos;",$value);
		}
	}

	public function index(){
		$data['po_list'] = $this->purchase_order_m->get_po_list();
		$data['work_joinery_list'] = $this->purchase_order_m->get_work_joinery_list();
		$data['reconciled_list'] = $this->purchase_order_m->get_reconciled_list();	
		$data['reconciled_list_joinery'] = $this->purchase_order_m->get_reconciled_work_joinery_list();	
		$data['users'] = $this->user_model->fetch_user();
		$data['main_content'] = 'purchase_order_home';
		$data['screen'] = 'Purchase Order';
		$this->load->view('page', $data);
	}

	public function containsDecimal( $value ) {
		if ( strpos( $value, "." ) !== false ) {
			return true;
		}
		return false;
	}

	public function ext_to_inc_gst($value,$gst){

		if($this->containsDecimal($gst)){
			$gst = $gst/100;
		}

		$gst = round($gst,2);
		$value = str_replace(',', '', $value);
		$converted = $value + ($value*$gst);
		$converted = round($converted,2);
		return $converted;
	}

	public function inc_to_ex_gst($value,$gst){

		if(!$this->containsDecimal($gst)){
			$gst = $gst*100;
		}

		$gst = round($gst,2);
		$value = str_replace(',', '', $value);

		$converted = $value - ($value/(($gst+100)/$gst));
		
		$converted = round($converted,2);

		return $converted;
	}
	
	public function purchase_order_filtered(){
		$this->clear_apost();
		$q_admin_defaults = $this->admin_m->fetch_admin_defaults();
		foreach ($q_admin_defaults->result_array() as $row){
			$data['gst_rate'] = $row['gst_rate'];
		}
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		$data['po_list'] = $this->purchase_order_m->get_po_list();
		$data['po_list_ordered'] = $this->purchase_order_m->get_po_list_order_by_project($start_date,$end_date);
		$data['work_joinery_list'] = $this->purchase_order_m->get_work_joinery_list();
		$data['reconciled_list'] = $this->purchase_order_m->get_reconciled_list();	
		$data['reconciled_list_joinery'] = $this->purchase_order_m->get_reconciled_work_joinery_list();	
		$data['users'] = $this->user_model->fetch_user();
		$data['main_content'] = 'purchase_order_home';
		//$data['screen'] = 'Purchase Order';
		$this->load->view('po_filtered_v', $data);
	}

	public function set_po($po_number){
		$address_id = $this->purchase_order_m->set_po($po_number);	
	}

	public function update_po_work_date($work_id_po,$work_po_date){
		$this->purchase_order_m->update_po_work_date($work_id_po,$work_po_date);
	}

	public function po_history(){
		$this->clear_apost();
		$ajax_var_raw = $_POST['ajax_var'];

		if (strpos($ajax_var_raw,'-') !== false) {

			$work_id_arr = explode('-',$ajax_var_raw);
			$work_id = $work_id_arr[0];

			$joinery_id_arr = explode('/',$work_id_arr[1]);
			$joinery_id = $joinery_id_arr[0];

		}else{
			$work_id_arr = explode('/',$ajax_var_raw);
			$work_id = $work_id_arr[0];
			$joinery_id = 0;
		}


		$get_work_raw = $this->purchase_order_m->select_po_history($work_id,$joinery_id);


		if($get_work_raw->num_rows() > 0){
			foreach ($get_work_raw->result_array() as $row){

				$query_notes = $this->company_m->fetch_notes($row['notes_id']);
				$temp_data = array_shift($query_notes->result_array());
				$comments = $temp_data['comments'];

				echo '<tr><td>'.$row['work_invoice_date'].'</td><td>$'.$row['amount'].'</td><td>'.$row['invoice_no'].'</td><td>'.$comments.'</td></tr>';
			}
		}else{
			echo '<tr><td colspan="4">No Transactions</td></tr>';
		}

		$last_history_trans_raw = $this->purchase_order_m->select_last_po_history($work_id,$joinery_id);
		$last_history_trans = array_shift($last_history_trans_raw->result_array());

		if($last_history_trans_raw->num_rows() > 0){
			echo '<tr><td colspan="4" class="clearfix"><button type="button" class="btn btn-danger" style="float:right" onClick="remove_last('.$last_history_trans['work_purchase_order_id'].','.$last_history_trans['work_id_po'].','.$last_history_trans['joinery_id'].');" ><i class="fa fa-exclamation-triangle"></i> Remove Last Transaction</button></td></tr>';
		}
	}

	public function remove_last_trans(){
		$post_ajax_arr = explode('*',$_POST['ajax_var']);
		$work_purchase_order_id = $post_ajax_arr[0];		
		$work_id_po = $post_ajax_arr[1];
		$joinery_id = $post_ajax_arr[2];

		$this->purchase_order_m->remove_po($work_purchase_order_id);
		$this->purchase_order_m->po_set_outstanding($work_id_po,$joinery_id);
	}

	public function set_outstanding_po(){
		$this->clear_apost();

		$post_ajax_arr = array();
		$post_ajax_arr = explode('*',$_POST['ajax_var']);

		$work_invoice_date = $post_ajax_arr[0];
		$work_id_po = $post_ajax_arr[1];
		$notes = $post_ajax_arr[2];
		$invoice_no = $post_ajax_arr[3];
		$amount = str_replace( ',', '',$post_ajax_arr[4] );
		$is_reconciled_value = $post_ajax_arr[5];

		if (strpos($work_id_po,'-') !== false) {

			$work_id_arr = explode('-',$work_id_po);
			$work_id = $work_id_arr[0];

			$joinery_id_arr = explode('/',$work_id_arr[1]);
			$joinery_id = $joinery_id_arr[0];

		}else{
			$work_id_arr = explode('/',$work_id_po);
			$work_id = $work_id_arr[0];
			$joinery_id = 0;
		}

		$this->purchase_order_m->po_set_outstanding($work_id_po,$work_invoice_date,$joinery_id);
		//$this->purchase_order_m->update_work_invoice($work_invoice_date,$work_id_po,$joinery_id,$invoice_no,$amount);
	}

	public function insert_work_invoice(){
		$this->clear_apost();

		//$post_ajax_arr = array();

		//$post_ajax_arr = explode('*',$_POST['ajax_var']);

		//po_date_value+'*'+po_number_item+'*'+po_notes_value+'*'+po_reference_value+'*'+po_amount_value+'*'+po_is_reconciled_value;

		if (isset($_POST['is_reconciled'])) {
			$is_reconciled = 1;
		}else{
			$is_reconciled = 0;
		}

		//var_dump($_POST);

		$work_invoice_date = $_POST['po_date_value'];
		$work_id_po = $_POST['po_number_item'];
		$notes = $_POST['po_notes_value'];
		$invoice_no = $_POST['po_reference_value'];
		$amount = str_replace( ',', '',$_POST['po_amount_value']);
		$is_reconciled_value = $is_reconciled;


		if (strpos($work_id_po,'-') !== false) {

			$work_id_arr = explode('-',$work_id_po);
			$work_id = $work_id_arr[0];

			$joinery_id_arr = explode('/',$work_id_arr[1]);
			$joinery_id = $joinery_id_arr[0];

		}else{
			$work_id_arr = explode('/',$work_id_po);
			$work_id = $work_id_arr[0];
			$joinery_id = 0;
		}

		$notes_id = $this->company_m->insert_notes($notes);

		$this->purchase_order_m->insert_work_invoice($work_invoice_date,$work_id_po,$joinery_id,$notes_id,$invoice_no,$amount);

		if($is_reconciled_value == 1){
			$this->purchase_order_m->po_set_reconciled($work_id_po,$work_invoice_date,$joinery_id);
		}

		redirect('purchase_order', 'refresh');

	}


	public function check_balance_po($work_id_po,$joinery_id=0){

		$get_work_raw = $this->purchase_order_m->get_work($work_id_po);		
		$work_details = array_shift($get_work_raw->result_array());

		$total_paid_raw = $this->purchase_order_m->get_po_total_paid($work_id_po,$joinery_id);		
		$total_paid = array_shift($total_paid_raw->result_array());

		if($joinery_id!=0){

			$get_joinery_raw = $this->purchase_order_m->get_joinery($joinery_id,$work_id_po);		
			$joinery_details = array_shift($get_joinery_raw->result_array());

			return ($joinery_details['unit_price']*$joinery_details['qty']) - $total_paid["total_paid"];

		}else{
			return $work_details['price'] - $total_paid["total_paid"];
		}

	}


	public function no_insurance_send_email(){
		$po_number = $_POST['po_no'];
		$works_q = $this->works_m->display_works_selected($po_number);
		foreach ($works_q->result_array() as $row){
			$contractor_id = $row['company_client_id'];
			$proj_id = $row['project_id'];
		}

		$proj_q = $this->projects_m->select_particular_project($proj_id);
		foreach ($proj_q->result_array() as $row){
			$client_id = $row['company_id'];
			$compname = $row['company_name'];
//=== Focus Company Details =========
			$focus_company_id = $row['focus_company_id'];

			$project_manager_id = $row['project_manager_id'];

			$data['focus_company_id'] = $focus_company_id;
			$focus_comp_q = $this->admin_m->fetch_single_company_focus($focus_company_id);
			foreach ($focus_comp_q->result_array() as $focus_comp_row){
				$data['focus_logo'] = $focus_comp_row['logo'];
				$data['focus_comp'] = $focus_comp_row['company_name'];
				$data['office_no'] = $focus_comp_row['area_code']." ".$focus_comp_row['office_number'];
				$data['acn'] = $focus_comp_row['acn'];
				$data['focus_abn'] = $focus_comp_row['abn'];
				$data['focus_email'] = $focus_comp_row['general_email'];
				$address_id = $focus_comp_row['address_id'];
				$focus_comp_q = $this->company_m->fetch_complete_detail_address($address_id);
				foreach ($focus_comp_q->result_array() as $comp_address_row){
					$po_box = $comp_address_row['po_box'];
					if($po_box == ""){
						$data['po_box'] = "";
					}else{
						$data['po_box'] = "PO".$comp_address_row['po_box'];
					}
					$data['focus_suburb'] = ucfirst(strtolower($comp_address_row['suburb']))." ".$comp_address_row['shortname']." ".$comp_address_row['postcode'];
				}
			}
//=== Focuse Company Details =========
		}


		$contractor_q = $this->works_m->display_works_selected_contractor($po_number,$contractor_id);
		foreach ($contractor_q->result_array() as $row){
			$contact_person_id = $row['contact_person_id'];
			$comp_q = $this->company_m->fetch_all_contact_persons($contact_person_id);
			foreach ($comp_q->result_array() as $cont_row){
				$email_id = $cont_row['email_id'];
				$email_q = $this->company_m->fetch_email($email_id);
				foreach ($email_q->result_array() as $email_row){
					$e_mail = $email_row['general_email'];
				}
			}
		}
		$company_q = $this->company_m->fetch_all_company($contractor_id);
		foreach ($company_q->result_array() as $comp_row){
			if($comp_row['public_liability_expiration'] !== ""){
				$ple_raw_data = $comp_row['public_liability_expiration'];
				$ple_arr =  explode('/',$ple_raw_data);
				$ple_day = $ple_arr[0];
				$ple_month = $ple_arr[1];
				$ple_year = $ple_arr[2];
				$ple_date = $ple_year.'-'.$ple_month.'-'.$ple_day;
			}

			if($comp_row['workers_compensation_expiration'] !== ""){
				$wce_raw_data = $comp_row['workers_compensation_expiration'];
				$wce_arr =  explode('/',$wce_raw_data);
				$wce_day = $wce_arr[0];
				$wce_month = $wce_arr[1];
				$wce_year = $wce_arr[2];
				$wce_date = $wce_year.'-'.$wce_month.'-'.$wce_day;
			}

			if($comp_row['income_protection_expiration'] !== ""){
				$ipe_raw_data = $comp_row['income_protection_expiration'];
				$ipe_arr =  explode('/',$ipe_raw_data);
				$ipe_day = $ipe_arr[0];
				$ipe_month = $ipe_arr[1];
				$ipe_year = $ipe_arr[2];
				$ipe_date = $ipe_year.'-'.$ipe_month.'-'.$ipe_day;
			}
			$today = date('Y-m-d');
			
			$complete = 0;
			$incomplete = 0;
			
			if($comp_row['company_type_id'] == '2'){
				if($comp_row['has_insurance_public_liability'] == 1){
					if($comp_row['public_liability_expiration'] !== ""){
						if($ple_date <= $today){
							$incomplete = 1;
						}else{
							if($comp_row['has_insurance_workers_compensation'] == 1){
								if($comp_row['workers_compensation_expiration'] !== ""){
									if($wce_date <= $today){
										$incomplete = 1;
									}else{
										$complete = 1;
									}
								}else{
									$incomplete = 1;
								}
							}else{
								if($comp_row['has_insurance_income_protection'] == 1){
									if($comp_row['income_protection_expiration'] !== ""){
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
		}


		// $has_invoice = $this->purchase_order_m->has_purchase_order_invoice($po_number);
		// if($has_invoice == 0){
			if($incomplete == 1){

				$default_msg_q = $this->admin_m->fetch_admin_default_email_message();
				foreach ($default_msg_q->result_array() as $row){
					$message_content = $row['message_content'];
					$sender_name = $row['sender_name'];
					$sender_email = $row['sender_email'];
					$bcc_email = $row['bcc_email'];
					$subject = $row['subject'];
				}

				$data['message'] = $message_content;
				$data['sender'] = $sender_name;
				$data['send_email'] = $sender_email;
				$data['comp_phone'] = "Ph. 08 6305 0991";
				$data['comp_address_line1'] = "Unit 3 / 86 Inspiration Drive";
				$data['comp_address_line2'] = "Wangara WA 6065";
				$data['comp_address_line3'] = "PO Box 1326 Wangara DC WA 6947";

				$data['comp_name'] = "FSF Group Pty Ltd";
				$data['abn1'] = "ABN 61 167 776 678";
				$data['comp_name2'] = "Focus Shopfit Pty Ltd";
				$data['abn2'] = "ABN 16 159 087 984";
				$data['comp_name3'] = "Focus Shopfit NSW Pty Ltd";
				$data['abn3'] = "ABN 17 164 759 102";

		    	$this->load->library('email');

		    	$this->email->set_mailtype("html");
				$message = $this->load->view('message_view',$data,TRUE);

		    	$this->load->library('email');

				$this->email->from($sender_email, $sender_name);
				$this->email->to($e_mail); 
				//$this->email->cc($cc); 
				$this->email->bcc($bcc_email); 

				$this->email->subject($subject);
				$this->email->message($message);

				if ( ! $this->email->send())
				{
				    echo "E-mail Not Sent";
				}else{
					echo "E-mail Successfully Sent";
				}
			}else{
				echo 0;
			}
		//}
	}
	
}