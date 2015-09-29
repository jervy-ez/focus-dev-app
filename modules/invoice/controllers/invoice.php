<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Invoice extends MY_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->model('invoice_m');
		$this->load->module('users');
		$this->load->module('company');
		$this->load->model('company_m');
		$this->load->module('projects');
		$this->load->model('projects_m');
		$this->load->module('purchase_order');
		if(!$this->users->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;

		if(isset($_GET['submit_invoice'])){
			$project_id = $_GET['submit_invoice'];
			$this->session->set_flashdata('curr_tab', 'invoice');	
			redirect('projects/view/'.$project_id, 'refresh');		
		}

		if(isset($_GET['submit_invoice_payment'])){
			//$project_id = $_GET['submit_invoice'];
			//$this->session->set_flashdata('curr_tab', 'invoice');	
			redirect('invoice/', 'refresh');		
		}


	}

	public function index(){
		$this->users->_check_user_access('invoice',1);
		$data['main_content'] = 'invoice_home';
		$data['screen'] = 'Invoice';
		$data['users'] = $this->user_model->fetch_user();
		$this->load->view('page', $data);
	}

	public function project_invoice(){

		if($this->session->userdata('invoice') >= 1){
			$data = '';
			$this->load->view('invoice_project', $data);
		}else{
			
		}
	}

	public function reload_invoiced_amount(){
		$invoice_q = $this->invoice_m->just_select_all_invoice();

		foreach ($invoice_q->result() as $invoice) {
			if($invoice->invoiced_amount == '0.00' && $invoice->label != 'VR'){
				
				$project_totals = $this->projects->fetch_project_totals($invoice->project_id);

				$current_amount = $invoice->project_total * ($invoice->progress_percent/100);

				$this->invoice_m->update_invoiced_amount($invoice->invoice_id,$current_amount);
			}
		}
	}

	public function if_project_invoiced_full($project_id,$with_vr=0){
		$invoices_q = $this->invoice_m->get_invoices($project_id);
		$not_completed = 0;

		foreach ($invoices_q->result() as $invoice){
			if($invoice->is_invoiced == 0){
				$not_completed = 1;
			}

			if($with_vr == 1 && $invoice->label == 'VR' ){
				if($invoice->is_invoiced == 0){
					$not_completed = 1;
				}
			}
		}

		if($not_completed == 0){
			return 1;
		}else{
			return 0;
		}
	}

	public function invoice_table(){
		$q_invoice_project = $this->invoice_m->list_invoice_project();
		$total_invoice = 0;
		$total_outstanding = 0;

		foreach ($q_invoice_project->result() as $invoice_project) {
			$q_unpaid_invoiced = $this->invoice_m->list_unpaid_invoiced($invoice_project->project_id);

			foreach ($q_unpaid_invoiced->result() as $invoice) {

				$project_totals = $this->projects->fetch_project_totals($invoice->project_id);
				$project_defaults = $this->projects->display_project_applied_defaults($invoice->project_id);

				$q_project_details = $this->projects_m->fetch_project_details($invoice->project_id);
				$project_details = array_shift($q_project_details->result_array());

				//$amount = $project_details['project_total']*($invoice->progress_percent/100);
				$amount = $invoice->invoiced_amount;

				$outstanding = $this->get_current_balance($invoice->project_id,$invoice->invoice_id,$amount);
				$total_outstanding = $total_outstanding + round($outstanding,2);
				$outstanding = number_format($outstanding,2);

				$progress_percent = $invoice->progress_percent;

				$client_details_raw = $this->company_m->fetch_company_details($project_details['client_id']);
				$client_details = array_shift($client_details_raw->result_array());


				if($invoice->label == 'VR'){
					$invoice_progress = $invoice->project_id.$invoice->label;
					$amount = $project_totals['variation_total'];

					$outstanding = $this->get_current_balance($invoice->project_id,$invoice->invoice_id,$amount);
					$total_outstanding = $total_outstanding + round($outstanding,2);
					$outstanding = number_format($outstanding,2);

					$progress_percent = '100.00';
				}elseif($invoice->label != ''){
					$invoice_progress = $invoice->label;
				}else{
					$invoice_progress = $invoice->project_id.'P'.$invoice->order_invoice;
				}

				if($invoice->label == 'VR'){
					$progress_id = $invoice->invoice_id.'_'.$invoice->project_id.'_vr_'.$invoice->order_invoice;
				}elseif($invoice->label != ''){
					$progress_id = $invoice->invoice_id.'_'.$invoice->project_id.'_f_'.$invoice->order_invoice;
				}else{
					$progress_id = $invoice->invoice_id.'_'.$invoice->project_id.'_p_'.$invoice->order_invoice;
				}
				$total_invoice = $total_invoice + $amount;

				echo '<tr id="'.$project_defaults['admin_gst_rate'].'"><td><a href="'.base_url().'projects/view/'.$invoice->project_id.'?submit_invoice='.$invoice->project_id.'">'.$invoice->project_id.'</a></td><td>'.$project_details['project_name'].'</td><td><a onclick="invoice_payment_modal(this)" href="#" data-toggle="modal" data-target="#invoice_payment_modal" data-backdrop="static" id="'.$progress_id.'">'.$invoice_progress.'</a></td><td>'.$client_details['company_name'].'</td><td>'.$progress_percent.'</td><td>'.$invoice->set_invoice_date.'</td><td><span class="ex-gst invocie_amount_total outsdng">'.number_format($amount,2).'</span><br /><span class="inc-gst">'.number_format($this->purchase_order->ext_to_inc_gst($amount,$project_defaults['admin_gst_rate']),2).'</span></td><td><span class="invocie_outstanding ex-gst outsding-b">'.$outstanding.'</span><br /><span class="inc-gst">'.number_format($this->purchase_order->ext_to_inc_gst($outstanding,$project_defaults['admin_gst_rate']),2).'</span></td></tr>';
			}
		}
		echo '<tr class="hidden hide"><td><input type="hidden" class="total-invoiced-row" id="total-invoiced-row" value="'.number_format($total_invoice,2).'" /></td><td><input type="hidden" class="total-invoiced-outstanding-row" id="total-invoiced-outstanding-row" value="'.number_format($total_outstanding,2).'" /></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
	}

	public function paid_table(){
		$q_invoice_project = $this->invoice_m->list_invoice_project_paid();
		$total_invoice = 0;
		$total_outstanding = 0;

		foreach ($q_invoice_project->result() as $invoice_project) {
			$q_unpaid_invoiced = $this->invoice_m->list_unpaid_invoiced($invoice_project->project_id,'1');

			foreach ($q_unpaid_invoiced->result() as $invoice) { 

				$project_totals = $this->projects->fetch_project_totals($invoice->project_id);
				$project_defaults = $this->projects->display_project_applied_defaults($invoice->project_id);

				$q_project_details = $this->projects_m->fetch_project_details($invoice->project_id);
				$project_details = array_shift($q_project_details->result_array());

				//$amount = $project_details['project_total']*($invoice->progress_percent/100);
				$amount = $invoice->invoiced_amount;

				$client_details_raw = $this->company_m->fetch_company_details($project_details['client_id']);
				$client_details = array_shift($client_details_raw->result_array());

				if($invoice->label == 'VR'){
					$invoice_progress = $invoice->project_id.$invoice->label;
					$amount = $project_totals['variation_total'];

					$outstanding = $this->get_current_balance($invoice->project_id,$invoice->invoice_id,$amount);
					$total_outstanding = $total_outstanding + round($outstanding,2);
					$outstanding = number_format($outstanding,2);

					$progress_percent = '100.00';
				}elseif($invoice->label != ''){
					$invoice_progress = $invoice->label;
				}else{
					$invoice_progress = $invoice->project_id.'P'.$invoice->order_invoice;
				}

				$outstanding = $this->get_current_balance($invoice->project_id,$invoice->invoice_id,$amount);
				$total_outstanding = $total_outstanding + round($outstanding,2);
				$outstanding = number_format($outstanding,2);

				if($invoice->label == 'VR'){
					$progress_id = $invoice->invoice_id.'_'.$invoice->project_id.'_vr_'.$invoice->order_invoice;
				}elseif($invoice->label != ''){
					$progress_id = $invoice->invoice_id.'_'.$invoice->project_id.'_f_'.$invoice->order_invoice;
				}else{
					$progress_id = $invoice->invoice_id.'_'.$invoice->project_id.'_p_'.$invoice->order_invoice;
				}

				$total_invoice = $total_invoice + $amount;


				echo '<tr id="'.$project_defaults['admin_gst_rate'].'"><td><a href="'.base_url().'projects/view/'.$invoice->project_id.'?submit_invoice='.$invoice->project_id.'">'.$invoice->project_id.'</a></td><td>'.$project_details['project_name'].'</td><td><a onclick="invoice_paid_modal(this)" href="#" data-toggle="modal" data-target="#invoice_paid_modal" data-backdrop="static" id="'.$progress_id.'">'.$invoice_progress.'</a></td><td>'.$client_details['company_name'].'</td><td>'.$invoice->set_invoice_date.'</td><td><span class="invocie_amount_total ex-gst">'.number_format($amount,2).'</span><br /><span class="inc-gst">'.number_format($this->purchase_order->ext_to_inc_gst($amount,$project_defaults['admin_gst_rate']),2).'</span></td><td><span class="ex-gst invocie_outstanding">'.$outstanding.'</span><br /><span class="inc-gst">'.number_format($this->purchase_order->ext_to_inc_gst($outstanding,$project_defaults['admin_gst_rate']),2).'</span></td></tr>';
			}
		}
		
		echo '<tr class="hidden hide"><td><input type="hidden" class="total-paid-row" id="total-paid-row" value="'.number_format($total_invoice,2).'" /></td><td><input type="hidden" class="total-paid-outstanding-row" id="total-paid-outstanding-row" value="'.number_format($total_outstanding,2).'" /></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
	}

	public function insert_invoice_progress(){
		$invoice_arr = explode('*',$_POST['ajax_var']);
		$invoice_date_req = $invoice_arr[4];
		$project_id = $invoice_arr[5];
		$progress_percent = $invoice_arr[0];
		$label =  ($invoice_arr[3] != 'undefined' ? $invoice_arr[3] : '');
		$order_invoice = $invoice_arr[1];
		$this->invoice_m->insert_new_invoice($invoice_date_req, $project_id, $progress_percent,$label,$order_invoice);
	}

	public function if_completed_invoice($project_id){
		$query_list_invoice = $this->invoice_m->list_invoice($project_id);
		$error = 0;

		foreach ($query_list_invoice->result() as $row) {
			if($row->invoice_date_req == ''){
				$error = 1;
			}
		}

		if($error == 1){
			return 0;
		}else{
			return 1;	
		}

	}

	public function insert_invoice_few_progress(){
		$invoice_arr = explode('*',$_POST['ajax_var']);
		$invoice_date_req = $invoice_arr[4];
		$project_id = $invoice_arr[5];
		$progress_percent = $invoice_arr[0];
		$label =  ($invoice_arr[3] != 'undefined' ? $invoice_arr[3] : '');
		$order_invoice = $invoice_arr[1];		

		$current_invoice = array();

		$query_list_invoice = $this->invoice_m->list_invoice($project_id);

		foreach ($query_list_invoice->result() as $row) {
			array_push($current_invoice, $row->order_invoice);
		}


		if(in_array($order_invoice,$current_invoice)){
			
		}else{
			$this->invoice_m->insert_new_invoice($invoice_date_req, $project_id, $progress_percent,$label,$order_invoice);
		}


	}

	public function delete_all_invoices(){
		$project_id = $_POST['ajax_var'];
		$this->invoice_m->delete_invoice($project_id);
	}

	public function delete_some_invoices(){
		$project_id = $_POST['ajax_var'];
		$this->invoice_m->delete_some_invoice($project_id);		
	}

	public function if_has_invoice($project_id){		
		$query_list_invoice = $this->invoice_m->list_invoice($project_id);
		return $query_list_invoice->num_rows();
	}

	public function if_invoiced_all($project_id){		
		$query_list_invoice = $this->invoice_m->list_uninvoiced_items($project_id);
		if($query_list_invoice->num_rows() > 0){
			return false;
		}else{
			return true;
		}
	}

	public function set_invoice_vr($project_id){

		$query_list_invoice = $this->invoice_m->list_invoice($project_id);
		$order_invoice = $query_list_invoice->num_rows() + 1;
		$label = 'VR';
		$progress_percent = '100';
		$invoice_date_req = date("d/m/Y");

		$this->invoice_m->insert_new_invoice($invoice_date_req, $project_id, $progress_percent,$label,$order_invoice);

	}

	public function set_project_as_paid($project_id=''){
		if($project_id==''){
			$project_id = $_POST['ajax_var'];
		}
		$this->invoice_m->set_project_as_paid($project_id);
	}

	public function set_project_as_fully_invoiced($project_id){
		$this->invoice_m->set_project_as_fully_invoiced($project_id);
	}

	public function is_all_paid($project_id){
		$query_list_invoice = $this->invoice_m->list_invoice($project_id);
		$is_not_paid = 0;

		foreach ($query_list_invoice->result() as $row) {
			if($row->is_paid == 0){
				$is_not_paid = 1;
			}
		}

		if($is_not_paid == 0){
			return true;
		}else{
			return false;
		}
	}

	public function if_invoiced($project_id){	
		$query_list_invoice = $this->invoice_m->list_invoiced_items($project_id);
		return $query_list_invoice->num_rows();		
	}

	public function show_job_book($project_id){
		$fetch_project_jobbook_raw = $this->invoice_m->fetch_project_jobbook($project_id);
		$project_jobbook = array_shift($fetch_project_jobbook_raw->result_array());
		return $project_jobbook;
	}

	public function list_invoiced_items($project_id,$project_total,$variation_total){
		$total = 0;
		$set_var_total = 0;
		$fetch_project_jobbook_raw = $this->invoice_m->list_invoiced_items($project_id);
		foreach ($fetch_project_jobbook_raw->result() as $row) {

			echo '<div class="invoices_list">';

			//$progress_percent = $row->progress_percent/100;
			$invoiced_amount = $row->invoiced_amount;

			if($row->label == ''){
				echo '<p class="mgn-b-10">'.$row->set_invoice_date.'<br /><strong>'.$row->project_id.'P'.$row->order_invoice.' &nbsp; &nbsp; ';

				echo ($row->is_paid == 1 ? '&#9745; Paid' : '');

				echo '</strong>  <strong class="pull-right"> EX-GST &nbsp; &nbsp; &nbsp; : $'.number_format($invoiced_amount,2).'</strong></p>';
			}else{

				if($row->label == 'VR'){
					echo '<p><br />Variation</p>';
					echo '<p>'.$row->set_invoice_date.'<br /><strong>'.$row->project_id.'VR &nbsp; &nbsp; ';
					echo ($row->is_paid == 1 ? '&#9745; Paid' : '');
					echo '</strong> &nbsp;   <strong class="pull-right"> EX-GST &nbsp; &nbsp; &nbsp; : $'.number_format($variation_total,2).'</strong></p>';
					$set_var_total = 1;

				}else{
					echo '<p><br />For Final Payment</p>';
					echo '<p>'.$row->set_invoice_date.'<br /><strong>'.$row->project_id.'F &nbsp; &nbsp; ';
					echo ($row->is_paid == 1 ? '&nbsp; &#9745; Paid' : '');
					echo '</strong> &nbsp;   <strong class="pull-right"> EX-GST &nbsp; &nbsp; &nbsp; : $'.number_format($invoiced_amount,2).'</strong></p>';
				}
				
			}
			echo '</div>';

			$total = $total + $invoiced_amount;

		}

		if($set_var_total == 0){
			$variation_total = 0;
		}

		echo '<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><hr /><p><strong class="pull-right">Total EX-GST  &nbsp; &nbsp; : $'.number_format($total+$variation_total,2).'</strong></p>';
	}


	public function progress_payment(){
		$post_ajax_arr = explode('*',$_POST['ajax_var']);

//		var_dump($post_ajax_arr);


//		array(8) { [0]=> string(10) "21/06/2015" [1]=> string(7) "35024_1" [2]=> string(10) "notessssss" [3]=> string(6) "reffff" [4]=> string(5) "4,000" [5]=> string(1) "0" [6]=> string(7) "$250.00" [7]=> string(1) "1" } 

//		array(7) { [0]=> string(9) "21/6/2015" [1]=> string(7) "35024_1" [2]=> string(11) "notesssssss" [3]=> string(7) "refffff" [4]=> string(5) "4,000" [5]=> string(1) "1" [6]=> string(7) "$250.00" } 


		$payment_date = $post_ajax_arr['0'];

		$progress_id_raw = explode('_',$post_ajax_arr['1']);
		$project_id = $progress_id_raw['0'];
		$order_invoice = $progress_id_raw['1'];


		$notes = $post_ajax_arr['2'];
		$reference_number = $post_ajax_arr['3'];

		$amount_exgst = $post_ajax_arr['4'];
		$amount_exgst = preg_replace("/,/", "", $amount_exgst);



		$outstanding = $post_ajax_arr['6'];	
		$outstanding = 	substr($outstanding,1);

		$outstanding = preg_replace("/,/", "", $outstanding);


		$is_paid = $post_ajax_arr['5'];
		$invoice_id = $post_ajax_arr['7'];

		$notes_id = $this->company_m->insert_notes($notes);
		$this->invoice_m->insert_payment($project_id,$notes_id,$amount_exgst,$invoice_id,$payment_date,$reference_number);
		$this->invoice_m->set_payment_invoice($invoice_id,$is_paid);

		$invoice_vr = $this->fetch_vr($project_id);
		//$invoice_vr['is_paid'] == 0


		if( $this->if_invoiced_all($project_id) && $this->is_all_paid($project_id) ){
			$this->invoice_m->set_project_as_paid($project_id);
		} 


	}


	public function get_project_invoiced($project_id,$project_total){
		$total = 0;
		$fetch_project_jobbook_raw = $this->invoice_m->list_invoiced_items($project_id);

		foreach ($fetch_project_jobbook_raw->result() as $row) {
			$progress_percent = $row->progress_percent/100;

			$total = $total + ($progress_percent*$project_total);
		}

		return $total;
	}

	public function get_current_balance($project_id,$invoice_id,$amount_to_pay){
		$raw_total_paid = $this->invoice_m->get_total_amount_paid($project_id,$invoice_id);


		$total_paid_q = array_shift($raw_total_paid->result_array());


		$has_paid = $raw_total_paid->num_rows();


		$total_paid = ($has_paid > 0 ? $total_paid_q['total_paid'] : 0);

		$outstanding = ($amount_to_pay - $total_paid);

		$outstanding = round($outstanding, 2); 

		return $outstanding;
	}


	public function get_amount_total_paid_invoice($project_id,$invoice_id){
		$raw_total_paid = $this->invoice_m->get_total_amount_paid($project_id,$invoice_id);
		$total_paid_q = array_shift($raw_total_paid->result_array());
		$has_paid = $raw_total_paid->num_rows();
		$total_paid = ($has_paid > 0 ? $total_paid_q['total_paid'] : 0);
		return $total_paid;
	}

	public function list_payment_history(){
		$payment_history = explode('*',$_POST['ajax_var']);

		$project_id = $payment_history['0'];
		$invoice_id = $payment_history['1'];

		$fetch_payment_history_raw = $this->invoice_m->fetch_payment_history($project_id,$invoice_id);
		$has_payments = $fetch_payment_history_raw->num_rows();

		if($has_payments > 0):

			foreach ($fetch_payment_history_raw->result() as $row) {
				echo '<tr><td>'.$row->payment_date.'</td><td>'.$row->amount_exgst.'</td><td>'.$row->reference_number.'</td></tr>';
				echo '<tr><td colspan="3"><strong>Notes: </strong>'.$row->comments.'</td></tr>';
			}

		endif;
		
	}

	public function check_invoice_progress($project_id,$project_total){
		$project_id_old = $this->session->userdata('project_id_old');
		$project_total_old = $this->session->userdata('project_total_old');
		$same_prj_id = 0;
		$same_total = 0;


		if(isset($project_id_old) && trim($project_id_old!='')){
			if($project_id == $project_id_old){
				$same_prj_id = 1;
			}else{
				$this->session->set_userdata('project_id_old',$project_id);
			}
		}else{
			$this->session->set_userdata('project_id_old',$project_id);
		}


		if(isset($project_total_old) && trim($project_total_old!='')){
			if( $project_total != 0 && $project_total_old != 0){
				if($project_total == $project_total_old){
					$same_total = 1;
				}else{
					$same_total = 2;
					$this->session->set_userdata('project_total_old',$project_total);
				}
				$this->session->set_userdata('project_total_old',$project_total);
			}
		}else{
			$this->session->set_userdata('project_total_old',$project_total);
		}

		$counter = 0;
		$un_invoiced_count = 0;
		$invoiced  = array();
		$invoiced_ammount  = array();
		$old_percents = 0;

		$invoiced_raw = 0;
		$invoiced_total_percent = 0;

		//$project_total = round($project_total,2);

		$progress_new = array();


		if($same_prj_id == 1 && $same_total == 2){


			$query_list_invoice = $this->invoice_m->list_invoice($project_id);

			foreach ($query_list_invoice->result() as $row) {
				//echo '<p>'.$row->invoice_id.' '.$row->is_invoiced.' '.$row->invoiced_amount.' '.$row->progress_percent.' '.$row->order_invoice.' '.$row->label.'</p>';

				if($row->label != 'VR'){

					if($row->is_invoiced != 1){
						$un_invoiced_count++;
					}else{
						//$invoiced_raw = $invoiced_raw + (100 / ($project_total / $row->invoiced_amount  ) );

						$invoiced_raw = round((100 / ($project_total / $row->invoiced_amount  ) ),2);
						$progress_new[$row->invoice_id] = $invoiced_raw;
						$invoiced_total_percent = $invoiced_total_percent + $invoiced_raw;

						$old_percents = $old_percents + $row->progress_percent;
					}    

					$counter++;

				}
			}

			$added_percent = ($old_percents - $invoiced_total_percent) / $un_invoiced_count;


			$query_list_invoice = $this->invoice_m->list_invoice($project_id);

			foreach ($query_list_invoice->result() as $row) {

				if($row->label != 'VR'){

					if($row->is_invoiced == 1){
						$this->invoice_m->update_progress_percent($row->invoice_id,$progress_new[$row->invoice_id]);
					}else{
						$this->invoice_m->update_progress_percent($row->invoice_id,$row->progress_percent+$added_percent);
					}
				}
			}



		}




		
	}

	public function remove_recent_payment(){
		$payment_history = explode('*',$_POST['ajax_var']);

		$project_id = $payment_history['0'];
		$invoice_id = $payment_history['1'];

		$q_fetch_to_remove = $this->invoice_m->fetch_list_to_remove($project_id,$invoice_id);


		$fetch_to_remove = array_shift($q_fetch_to_remove->result_array());

		$this->invoice_m->delete_payments($fetch_to_remove['payment_id'],$fetch_to_remove['notes_id'],$invoice_id);

	}

	public function set_invoice_progress(){
		$invoice_arr = explode('`',$_POST['ajax_var']);
 
		$project_id = $invoice_arr[0];
		$id_bttn_raw = $invoice_arr[1];
		$id_bttn = explode('_', $id_bttn_raw);
		$order_invoice = $id_bttn[1];


		$cc_emails = $invoice_arr[2];
		$invoice_notes = $invoice_arr[3];
		$date_set_invoice_data = $invoice_arr[4];
		$job_book_details_id = $invoice_arr[5];
		$invoice_item_amount = $invoice_arr[6];

		$invoice_item_amount = str_replace(',', '', $invoice_item_amount);


		$query_list_invoice = $this->invoice_m->list_invoice($project_id);
		$order_num = $query_list_invoice->num_rows() + 1;

		if($id_bttn[0] == 'VR'){
			$this->invoice_m->insert_invoiced_variation($date_set_invoice_data,$date_set_invoice_data,$project_id,$order_num);
		}else{
			$this->invoice_m->set_invoiced_progress($date_set_invoice_data,$order_invoice,$project_id,$invoice_item_amount);
		}

		$this->invoice_m->update_job_notes($job_book_details_id,$invoice_notes);

		echo "ok invocied";
	}

	public function fetch_vr($project_id){
		$invoice_vr_q = $this->invoice_m->fetch_invoice_vr($project_id);
		$invoice_vr = array_shift($invoice_vr_q->result_array());
		return $invoice_vr;
	}

	public function is_vr_invoiced($project_id){
		$num_vr = 0;
		$invoice_vr_q = $this->invoice_m->select_vr_invoice($project_id);
		$num_vr = $invoice_vr_q->num_rows();

		if($num_vr > 0){
			return true;
		}else{
			return  false;
		}
	}

	public function if_has_vr($project_id){
		$invoice_vr = $this->invoice_m->fetch_invoice_vr($project_id);
		return $invoice_vr->num_rows();
	}

	public function un_invoice_vr(){
		$invoice_item = explode('*',$_POST['ajax_var']);

		$project_id = $invoice_item['0'];
		$job_book_notes = $invoice_item['1'];

		$this->invoice_m->un_invoice_vr($project_id);

		$project_notes_q = $this->invoice_m->select_project_notes($project_id);
		$project_notes = array_shift($project_notes_q->result_array());

		$this->invoice_m->update_invoice_notes($project_notes["notes_id"],$job_book_notes);
	}

	public function un_invoice_item(){
		$invoice_item = explode('*',$_POST['ajax_var']);
		$invoice_id = $invoice_item['0'];
		$project_id = $invoice_item['1'];
		$job_book_notes = $invoice_item['2'];
		$this->invoice_m->un_invoice($invoice_id);

		$project_notes_q = $this->invoice_m->select_project_notes($project_id);
		$project_notes = array_shift($project_notes_q->result_array());

		$this->invoice_m->update_invoice_notes($project_notes["notes_id"],$job_book_notes);

	}

	public function get_total_amount_paid_project($project_id){
		$payment_q = $this->invoice_m->get_total_amount_paid_project($project_id);
		$total_paid_amount = array_shift($payment_q->result_array());

		return $total_paid_amount['total_paid'];

	}

	public function get_total_invoiced($project_id){
		$total_invoiced_raw = $this->invoice_m->fetch_total_invoiced($project_id);
		$total_invoiced = array_shift($total_invoiced_raw->result_array());

		return $total_invoiced['total_invoiced']/100;
	}

	public function invoice_list_table(){
		$list_invoice_project_number_q = $this->invoice_m->list_invoice_project_number();

		foreach ($list_invoice_project_number_q->result() as $row) {

			$invoice_by_project_number_q = $this->invoice_m->list_invoice_by_project_number($row->project_id);
			foreach ($invoice_by_project_number_q->result() as $list) {

				echo $list->project_id.' '.$list->project_name.' '.$list->order_invoice;

			}
		}
	}

	public function list_project_invoice($project_id){

		$project_costs = $this->projects->fetch_project_totals($project_id);


		$query_list_invoice = $this->invoice_m->list_invoice($project_id);

		$counter = 0;
		$un_invoiced = 0;

		foreach ($query_list_invoice->result() as $row) {
			$counter++;


			if($row->is_invoiced == 1){
				$progress_cost = $row->invoiced_amount;
			}else{
				$progress_cost = ($project_costs['final_total_quoted']*$row->progress_percent)/100;
				$progress_cost = round($progress_cost,2);
			}


//$progress_cost = ($project_costs['final_total_quoted']*$row->progress_percent)/100;


$outstanding = $this->get_current_balance($row->project_id,$row->invoice_id,$progress_cost);
$outstanding = number_format($outstanding,2);

$total_paid = $this->get_amount_total_paid_invoice($row->project_id,$row->invoice_id);

			echo '<tr><td scope="row" class="t-head" id="">';

			if($row->label != ''){
				echo '<div class=""><input type="text" class="form-control final_payment" value="'.$row->label.'" placeholder="Final Payment"></div>';
			}else{
				echo '<div class="m-top-10 progress-item" id="'.$row->invoice_id.'">Progress '.($row->label != '' ? '0' : $counter).'<span class="progress_counter"></span></div>';
			}


			echo '</th>
<td><div class="input-group"><div class="input-group-addon">%</div><input type="text" '.($row->is_invoiced > 0 ? 'disabled="disabled"' : '').' class="form-control progress-percent" onclick="getHighlight(\'progress-'.($row->label != '' ? '0' : $counter).'-percent\')" onchange="'.($row->label != '' ? 'final_progress' : 'progressPercent').'(this)" value="'.$row->progress_percent.'" placeholder="Percent" id="progress-'.($row->label != '' ? '0' : $counter).'-percent" name="progress-'.$counter.'-percent"/></div></td>
<td>';


			echo '<div class="progress-item" id="'.$row->invoice_id.'"><input type="hidden" name="outstanding" id="progress_outstanding" value="" /><div>';

echo '<input type="text" data-date-format="dd/mm/yyyy" placeholder="DD/MM/YYYY" value="'.$row->invoice_date_req.'" '.($row->is_invoiced > 0 ? 'disabled="disabled"' : '').' class="form-control date_daily text-left progress_date" id="progress-'.($row->label != '' ? '0' : $counter).'-date" name="progress-'.($row->label != '' ? '0' : $counter).'-date"></td>
<td><strong><div class="m-top-5">$<span class="total_cost_progress">'.number_format($progress_cost,2).'</span> ex-gst</div></strong></td><td>';

if($row->is_invoiced == 0 && $un_invoiced == 0 ){
	echo '<div class="progress_invoice_button"><button class="btn btn-primary-dark  m-right-5 progress_invoice" id="'.($row->label != '' ? 'F' : 'P').'_'.$row->order_invoice.'" data-toggle="modal" data-target="#set_invoice_modal"><i class="fa fa-file-text-o"></i> Set Invoice</button></div>';
	$un_invoiced++;
}elseif($row->is_invoiced != 0 ){


$item_progss = ($row->label != '' ? 'F' : 'P').'_'.$row->order_invoice;

if($outstanding > 0 ):

echo '<div class="btn-group pull-left m-right-10 progress_invoice_group">
		<button type="button" disabled="disabled" class="btn btn-primary-gray progress_invoice"><i class="fa fa-file-text-o"></i> Invoiced</button>
		<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			<span class="caret"></span>
			<span class="sr-only">Toggle Dropdown</span>
		</button>
		<ul class="dropdown-menu" role="menu">
		<li><a href="#" class="progress_invoice_resend"><i class="fa fa-files-o"></i> View Invoice</a></li>';

if($total_paid == 0 && $this->session->userdata('is_admin') == 1 ):
		echo '<li class="remove_link"><a href="#" id="'.$row->invoice_id.'-'.$item_progss.'-'.$row->project_id.'" class="remove_invoice"><i class="fa fa-exclamation-triangle"></i> Remove Invoice</a></li>';
endif;

endif;		
		echo '</ul>
	</div>';





 if($this->session->userdata('is_admin') == 1 || $this->session->userdata('user_role_id') == 5 || $this->session->userdata('user_role_id') == 6):

	if($row->is_paid == 1){
		echo '<button  class="btn btn-success progress_paid" id="'.$row->project_id.'"  data-toggle="modal" data-target="#payment_history_modal" data-backdrop="static" ><i class="fa fa-usd"></i> Paid</button>';
	}else{
		echo '<button  class="btn btn-danger progress_paid" id="'.$row->project_id.'_'.$row->order_invoice.'"  data-toggle="modal" data-target="#payment_modal" data-backdrop="static" ><i class="fa fa-usd"></i> Payment</button>';
	}
endif;


}else{

}


echo '</td><td><div class="m-top-5"><strong><span class="progress_outstanding">'.($row->is_invoiced == 1 ? '$'.$outstanding : '').'</span></strong></div></td></tr>';

			
			//echo '<option value="'.$row->contact_person_id.'" '.($row->is_primary == 1 ? 'selected="selected"' : '').'  >'.$row->first_name.' '.$row->last_name.'</option>';
		}


	}
}