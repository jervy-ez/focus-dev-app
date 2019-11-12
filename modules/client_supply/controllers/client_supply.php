<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Client_supply extends MY_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('session');

		$this->load->model('client_supply_m');
		$this->load->module('users');
		$this->load->module('company');
		$this->load->model('company_m');
		$this->load->module('projects');
		$this->load->model('projects_m');


		if(!$this->users->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;
 
 

	}

 


	public function index(){
		$this->users->_check_user_access('projects',1);
		$data['main_content'] = 'client_supply_home';
		$data['screen'] = 'Client Supply';
		//$data['users'] = $this->user_model->fetch_user();

		$select_static_defaults_q = $this->user_model->select_static_defaults();
		$data['static_data'] = array_shift($select_static_defaults_q->result_array());

		$client_supply_reminder_dys = $data['static_data']['client_supply_reminder_dys'];
		$date_today = date('d/m/Y');
		$date_limit_name = date('D', strtotime("today -$client_supply_reminder_dys days"));

		if($date_limit_name == 'Sun'){
			$client_supply_reminder_dys = $client_supply_reminder_dys-1;
			$date_limit = date('d/m/Y', strtotime("-$client_supply_reminder_dys days")); 
		}elseif($date_limit_name == 'Sat'){
			$client_supply_reminder_dys = $client_supply_reminder_dys+1;
			$date_limit = date('d/m/Y', strtotime("-$client_supply_reminder_dys days")); 
		}else{
			$date_limit = date('d/m/Y', strtotime("-$client_supply_reminder_dys days")); 
		}

		$this->client_supply_m->set_auto_delivered($date_today,$date_limit);
		
		$data['page_title'] = 'Client Supply';
		$this->load->view('page', $data);
	}

	public function display_client_logo($warehouse_id){




		$clinent_logo_q = $this->client_supply_m->get_client_supply_logo($warehouse_id);


		//var_dump($clinent_logo_q);



		$clinent_logo = array_shift($clinent_logo_q->result_array());


	//	$has_brand_logo = $clinent_logo['has_brand_logo'];
		$brand_name = $clinent_logo['company_name'];

/*
		if($has_brand_logo == 1){
			echo '<img src="'.base_url().'uploads/brand_logo/'.$clinent_logo['client_brand_id'].'.jpg" style="width: 75%; display: block; margin: 5px auto; text-align: center;" />';
		}else{
			if($brand_name != ''){
				echo '<span class="badge badge-info  btn-info pad-5 block m-10">'.$brand_name.'</span>';
			}
		}

*/


echo '<span class=" btn-info pad-5 block m-10" style="border-radius: 6px;     font-size: 12px;     padding: 0px 5px;     margin: 5px;">'.$brand_name.'</span>';


	}



	public function process_form_supply(){

		$this->users->clear_apost();

		$supply_name = $_POST["supply_name"];



		$project_data = explode('_', $_POST["project_data"]);

		$project_number = $project_data['0'];
//		$client_id = $project_data['1'];
	//	$project_completion_date = $project_data['2'];


		$delivered_by = $_POST["delivered_by"];
		$to_be_advised = $_POST["to_be_advised"];
		$date_goods_expected = $_POST["date_goods_expected"];
		$date_goods_arrived = $_POST["date_goods_arrived"];
		$qty = $_POST["qty"];
		$delivery_date = $_POST["delivery_date"];
		$is_deliver_to_site_select = $_POST["is_deliver_to_site_select"];
		$set_address = trim(preg_replace('/\s+/',' ', $_POST["set_address"]));
		$warehouse_selected =  str_replace('&nbsp;', '',strip_tags(trim(preg_replace('/\s+/',' ', $_POST["warehouse_selected"]))));


		if($warehouse_selected == ''){
			$warehouse_selected = 'Un-Allocated';
		}


		$description = $_POST["description"];




		


		$select_static_defaults_q = $this->user_model->select_static_defaults();
		$data['static_data'] = array_shift($select_static_defaults_q->result_array());

		$project_finish = $project_data['2'];

		$srchDate = date_format(date_create_from_format('d/m/Y', "$project_finish"), 'Y-m-d');


		$client_supply_reminder_dys = $data['static_data']['client_supply_reminder_dys'];

		$date_limit_name = date('D', strtotime("$srchDate -$client_supply_reminder_dys days"));

		if($date_limit_name == 'Sun'){

			$client_supply_reminder_dys = $client_supply_reminder_dys-1;
			$set_delivery_date =  date('d/m/Y', strtotime($srchDate."  -$client_supply_reminder_dys days"));


		}elseif($date_limit_name == 'Sat'){

			$client_supply_reminder_dys = $client_supply_reminder_dys+1;
			$set_delivery_date =  date('d/m/Y', strtotime($srchDate."  -$client_supply_reminder_dys days"));

		}else{
			$set_delivery_date =  date('d/m/Y', strtotime($srchDate."  -$client_supply_reminder_dys days"));
		}


		if($date_goods_expected == ''){
			$date_goods_expected = $set_delivery_date;
		}

		if($delivery_date == ''){
			$delivery_date = $set_delivery_date;
		}






	//	$unix_project_completion = strtotime(date_format(date_create_from_format('d/m/Y', $project_completion_date), 'Y-m-d') );
	//	$unix_delivery_date = strtotime(date_format(date_create_from_format('d/m/Y', $delivery_date), 'Y-m-d') );

		//echo $unix_project_completion.' ____ '.$unix_delivery_date;
/*
		if($unix_delivery_date > $unix_project_completion){
			$this->session->set_flashdata('error_add', '<strong>Error:</strong> Delivery Date <strong>('.$delivery_date .')</strong> should not exceed the completion date of the selected project.<br />Project Number: <strong>'.$project_number.'</strong> &nbsp;  &nbsp; Completion Date: <strong>'.$project_completion_date.'</strong> ');
		}else{
*/
			if ( array_sum($_FILES['supply_photos']['error']) > 0 ){
				$photos = '';
			}else{
				$photos = $this->processUpload('supply_photos','client_supply',$project_number.'_supply',1);
			}

		$this->client_supply_m->inset_new_supply($supply_name,$project_number,$qty,$date_goods_expected,$date_goods_arrived,$delivered_by,$to_be_advised,$delivery_date,$is_deliver_to_site_select,$set_address,$photos,$description,$warehouse_selected);
	

	//	}

		

	redirect('/client_supply');
	}

	public function del_photo_supply($supply_id,$photo){
		 

		$get_supply_data_q = $this->client_supply_m->list_photos($supply_id);
		$supply_data = array_shift($get_supply_data_q->result_array());

		$arr_photos = explode(',', $supply_data['photos']);

		$photos = array_diff($arr_photos, [$photo]);


		if(($key = array_search($photo, $arr_photos)) !== false) {
			unset($arr_photos[$key]);
			$photos = array_values($arr_photos);
		}

		$data_photos = implode(',', $photos);
		$this->client_supply_m->update_photos($supply_id,$data_photos);

	}




	public function update_form_supply(){

		$this->users->clear_apost();

//	var_dump($_POST);

		$supply_name = $_POST["supply_name"];
		$supply_data_id = $_POST["supply_data_id"];
	//	$project_id = $_POST["project_id"];
		$init_project_id = $_POST["init_project_id"];
		$date_goods_expected = $_POST["date_goods_expected"];
		$date_goods_arrived = $_POST["date_goods_arrived"];
		$quantity = $_POST["qty"];
		$delivered_by = $_POST["delivered_by"];
		$to_be_advised = $_POST["to_be_advised"];
		$delivery_date = $_POST["delivery_date"];
		$is_deliver_to_site_select = $_POST["is_deliver_to_site_select"];
		$set_address = $_POST["set_address"];
		$warehouse_selected = $_POST["ups_warehouse_selected"];
		$description = $_POST["description"];



		$project_data = explode('_', $_POST["project_id"]);
		$data_project_set = $project_data['0'];

	

		if(isset($_POST["project_id"]) && $_POST["project_id"]!=''){
			if($data_project_set != $init_project_id){
				$data_project_id = $data_project_set;
			}else{
				$data_project_id = $init_project_id;
			}
		}else{
			$data_project_id = $init_project_id;
		}

		$client_id = 0;

		if($date_goods_arrived == ''){
		//	$date_goods_arrived = 'NULL';
		}





		


		$select_static_defaults_q = $this->user_model->select_static_defaults();
		$data['static_data'] = array_shift($select_static_defaults_q->result_array());

		$project_finish = $project_data['2'];

		$srchDate = date_format(date_create_from_format('d/m/Y', "$project_finish"), 'Y-m-d');

		


		$client_supply_reminder_dys = $data['static_data']['client_supply_reminder_dys'];

		$date_limit_name = date('D', strtotime("$srchDate -$client_supply_reminder_dys days"));

		if($date_limit_name == 'Sun'){
			$client_supply_reminder_dys = $client_supply_reminder_dys-1;
			$set_delivery_date =  date('d/m/Y', strtotime($srchDate."  -$client_supply_reminder_dys days"));

		}elseif($date_limit_name == 'Sat'){
			$client_supply_reminder_dys = $client_supply_reminder_dys+1;
			$set_delivery_date =  date('d/m/Y', strtotime($srchDate."  -$client_supply_reminder_dys days"));

		}else{
			$set_delivery_date =  date('d/m/Y', strtotime($srchDate."  -$client_supply_reminder_dys days"));
		}


		if($date_goods_expected == ''){
			$date_goods_expected = $set_delivery_date;
		}

		if($delivery_date == ''){
			$delivery_date = $set_delivery_date;
		}


		$this->client_supply_m->update_supply_details($supply_data_id,$supply_name,$data_project_id,$quantity,$date_goods_expected,$date_goods_arrived,$delivered_by,$to_be_advised,$delivery_date,$is_deliver_to_site_select,$set_address,$description,$warehouse_selected);

 		//var_dump($_FILES);

		$get_supply_data_q = $this->client_supply_m->list_photos($supply_data_id);
		$supply_data = array_shift($get_supply_data_q->result_array());




		if ( array_sum($_FILES['supply_photos']['error']) > 0 ){

		}else{
			$photos = $this->processUpload('supply_photos','client_supply',$supply_data_id.'_supply',1);
			$data_photos = $supply_data['photos'].','.$photos;
			$this->client_supply_m->update_photos($supply_data_id,$data_photos);
		}

		redirect('/client_supply');
	}

	public function delete_supply($id){
	//	var_dump($id);

		$this->client_supply_m->delete_supply($id);
		redirect('/client_supply');
	}


	public function set_as_delivered($id){
		$set_date = date("d/m/Y");

		$this->client_supply_m->set_delivered($id,$set_date);
		redirect('/client_supply');
	}


	public function upload_photos(){
		$client_supply_id = $_POST['client_supply_id'];
		$photos = $this->processUpload('supply_photos','client_supply',$client_supply_id.'_supply',1);
		$this->client_supply_m->update_photos($client_supply_id,$photos);
		redirect('/client_supply');
	}

	public function view_supply($supply_id=''){
		$get_supply_data_q = $this->client_supply_m->get_supply_data($supply_id);
		$supply_data = array_shift($get_supply_data_q->result_array());

		echo $supply_data['client_supply_id'].'|';
		echo $supply_data['supply_name'].'|';
		echo $supply_data['project_id'].'|';
		echo $supply_data['client_id'].'|';
		echo $supply_data['quantity'].'|';
		echo $supply_data['date_goods_expected'].'|';
		echo $supply_data['date_goods_arrived'].'|';
		echo $supply_data['delivered_by'].'|';
		echo $supply_data['to_be_advised'].'|';
		echo $supply_data['delivery_date'].'|';
		echo $supply_data['is_deliver_to_site'].'|';
		echo $supply_data['address'].'|';
		echo $supply_data['photos'].'|';
		echo $supply_data['description'].'|';
		echo $supply_data['warehouse'].'|';
		echo $supply_data['is_active'].'|';
		echo $supply_data['is_delivered_date'];


	}




	public function processUpload($file_data,$folder,$file_name_set='',$return_fname=''){

		$date = date("d/m/Y");    
		$time = time();    

		if(isset($file_name_set) && $file_name_set!=''){
			$archive_registry_name = $file_name_set;
		}else{
			$archive_registry_name = 'file_upload';
		}


		$path = "./docs/".$folder;
		if(!is_dir($path)){
			mkdir($path, 0755, true);
		}

		$data_fname_arr = array();

        $config = array();
	    $config['upload_path'] = $path."/";
        $config['allowed_types'] = '*';
        $config['max_size']      = '0';
        $config['overwrite']     = FALSE;

        $this->upload->initialize($config);
        $this->load->library('upload');

        $files = $_FILES;
        $cpt = count($_FILES[$file_data]['name']);
        for($i=0; $i<$cpt; $i++){   

        	$file_name = $files[$file_data]['name'][$i];
        	$path_parts = pathinfo($file_name);
        	$extension = strtolower($path_parts['extension']);

        	$data_file_name = $archive_registry_name.'_'.$time.'_'.($i+1).'.'.$extension;
        	$_FILES[$file_data]['name']= $data_file_name;//$files['archive_files']['name'][$i];

        	array_push($data_fname_arr, $data_file_name);


            $_FILES[$file_data]['type']= $files[$file_data]['type'][$i];
        	$_FILES[$file_data]['tmp_name']= $files[$file_data]['tmp_name'][$i];
           	$_FILES[$file_data]['error']= $files[$file_data]['error'][$i];
        	$_FILES[$file_data]['size']= $files[$file_data]['size'][$i];    

        	if ( !$this->upload->do_upload($file_data)) {
			   	echo $this->upload->display_errors();
			}
        }


        if(isset($return_fname) && $return_fname==1){
        	return implode(',', $data_fname_arr);
        }
	}




}