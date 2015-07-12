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
		if(!$this->users->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;

		if(isset($_GET['reload'])){
			redirect('purchase_order', 'refresh');
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
	
	public function purchase_order_filtered(){
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

				echo '<tr><td>'.$row['work_invoice_date'].'</td><td>$'.$row['amount'].'</td><td>'.$row['invoice_no'].'</td><td style="display:none">'.$comments.'</td></tr>';
			}
		}else{
			echo '<tr><td colspan="3">No Transactions</td></tr>';
		}

		$last_history_trans_raw = $this->purchase_order_m->select_last_po_history($work_id,$joinery_id);
		$last_history_trans = array_shift($last_history_trans_raw->result_array());

		if($last_history_trans_raw->num_rows() > 0){
			echo '<tr><td colspan="3" class="clearfix"><button type="button" class="btn btn-danger" style="float:right" onClick="remove_last('.$last_history_trans['work_purchase_order_id'].','.$last_history_trans['work_id_po'].','.$last_history_trans['joinery_id'].');" ><i class="fa fa-exclamation-triangle"></i> Remove Last Transaction</button></td></tr>';
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

		$notes_id = $this->company_m->insert_notes($notes);
		$this->purchase_order_m->insert_work_invoice($work_invoice_date,$work_id_po,$joinery_id,$notes_id,$invoice_no,$amount);

		if($is_reconciled_value == 1){
			$this->purchase_order_m->po_set_reconciled($work_id_po,$work_invoice_date,$joinery_id);
		}

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


	// public function get_work_joinery(){

	// }
	
}