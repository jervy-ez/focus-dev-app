<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Induction_health_safety extends MY_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->module('users');
		$this->load->model('user_model');
		$this->load->model('induction_health_safety_m');
		$this->load->module('company');
		$this->load->model('company_m');
		$this->load->module('projects');
		$this->load->model('projects_m');
		$this->load->module('admin');
		$this->load->model('admin_m');
		// $this->load->module('invoice');
		// $this->load->model('invoice_m');
		// $this->load->module('wip');
		// $this->load->model('wip_m');
		// $this->load->module('project_schedule');
		// $this->load->model('project_schedule_m');
		if(!$this->users->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;
		$this->load->library('session');
	}
	
	public function email_send($sender_name,$email_from,$email_to,$email_cc,$email_bcc,$subject,$message){
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
		
		//$mail->addaddress('jervy@focusshopfit.com.au', 'joe user');
		$addr = explode(',',$email_to);
		$count_addr = count($addr);
		if($count_addr > 1){
			foreach ($addr as $ad) {
				$mail->addAddress( trim($ad) );  
			}
		}else{
			$mail->addAddress($email_to);
		}

		//$mail->addcc('jean@ausconnect.net.au');
		$ccaddr = explode(',',$email_cc);
		$count_ccaddr = count($ccaddr);
		if($count_ccaddr > 1){
			foreach ($ccaddr as $ad) {
				$mail->addCC( trim($ad) );  
			}
		}else{
			if($email_cc !== ''){
				$mail->addCC($email_cc);
			}
			
		}

		$email_bcc_arr =  explode(',', $email_bcc);
		$no_arr = count($email_bcc_arr);
		$x = 0;
		while($x < $no_arr){
			$email_bcc = $email_bcc_arr[$x];
			$mail->addBCC($email_bcc);
			$x++;
		}


		$mail->smtpdebug = 2;
		$mail->ishtml(true);



		$mail->Subject = $subject;
		$mail->Body    = $message;

		if(!$mail->send()) {
			return 'Message could not be sent.'.' Mailer Error: ' . $mail->ErrorInfo;
		} else {

			return "Email Send Successfully";
		}


	}

	public function index(){

		if( $this->session->userdata('is_admin') ==  1 || $this->session->userdata('user_id') == 6 || $this->session->userdata('user_id') == 32  ){
			
		}elseif( $this->session->userdata('induction_archive_upload') == 1){

			 redirect('induction_health_safety/archive_documents');
		}
		else{
			 redirect('projects');
		}

		$fetch_archive_types = $this->admin_m->get_archive_types();
		$data['archive_types'] = $fetch_archive_types->result();

		$data['tab'] = $this->session->userdata('tab');
		$data['page_title'] = 'Induction Health and Safety';
		$data['main_content'] = 'inductions_v';
 
		$data['screen'] = 'Induction, Health and Safety';
		$this->load->view('page', $data);
	}



/*



*/



 	public function archive_documents(){


		if( $this->session->userdata('is_admin') ==  1 || $this->session->userdata('user_id') == 6 || $this->session->userdata('user_id') == 32    || $this->session->userdata('induction_archive_upload') == 1){
			
		}else{
			 redirect('projects');    // use this just to controll access
		}

		$user_id = $this->session->userdata('user_id');

		if($this->session->userdata('is_admin') == 1 ){
			$fetch_archive_types = $this->admin_m->get_archive_types();
			$data['archive_types_for_upload'] = $fetch_archive_types->result();
		}else{
			$fetch_fetch_archive_assigned_to_emp = $this->admin_m->fetch_archive_assigned_to_emp($user_id);
			$data['archive_types_for_upload'] = $fetch_fetch_archive_assigned_to_emp->result();
		}

		$data['tab'] = $this->session->userdata('tab');
		$data['main_content'] = 'inductions_archives';
		$data['screen'] = 'Induction, Health and Safety';
		$this->load->view('page', $data);
	}


	public function view_uploaded_files_arch($type_id,$view_old=0){

		$fetch_archive_files = $this->induction_health_safety_m->list_uploaded_files_arch($type_id,$view_old);
		$archive_files = $fetch_archive_files->result();

		foreach($archive_files as $key => $archive_data){
			echo '<p>
			<a href="'.base_url().'docs/doc_archives/'.$archive_data->file_name.'" target="_blank"><i class="fa fa-chevron-circle-right fa-lg"></i> &nbsp'.$archive_data->file_name.'</a>
			<span class="pull-right"><i class="fa fa-info-circle fa-lg tooltip-enabled pointer" title="" data-html="true" data-placement="top" data-original-title="Uploaded Date '.$archive_data->date.'"></i></span>
			 &nbsp; <a href="'.base_url().'induction_health_safety/remove_archive_doc/'.$archive_data->archive_documents_id.'" class="for_admin" id=""><i class="fa fa-times-circle fa-lg" style="color:red;"></i></a>
			</p>';
		}

	}

	public function remove_archive_doc($doc_id){
		$this->induction_health_safety_m->delete_archive($doc_id);
	 	redirect('/induction_health_safety/archive_documents');
	}


	public function upload_docs_ind(){
		$date = date("d/m/Y");    
		$time = time();    

		$archive_registry_types = $_POST['archive_registry_types'];
		$archive_registry_name =    substr( strtolower(  str_replace(' ','_',  $_POST['archive_registry_name'] )) ,  0, 5);
		$user_id = $this->session->userdata('user_id');



		$path = "./docs/doc_archives";
		if(!is_dir($path)){
			mkdir($path, 0755, true);
		}
        //upload an image options
        $config = array();
	    $config['upload_path'] = $path."/";
        $config['allowed_types'] = '*';
        $config['max_size']      = '0';
        $config['overwrite']     = FALSE;

        $this->upload->initialize($config);

        $this->load->library('upload');

        $files = $_FILES;
        $cpt = count($_FILES['archive_files']['name']);
        for($i=0; $i<$cpt; $i++){   

        	$file_name = $files['archive_files']['name'][$i];
        	$path_parts = pathinfo($file_name);
        	$extension = strtolower($path_parts['extension']);


        	$data_file_name = $archive_registry_name.'_'.$time.'_'.$i.'.'.$extension;
        	$_FILES['archive_files']['name']= $data_file_name;//$files['archive_files']['name'][$i];


            $_FILES['archive_files']['type']= $files['archive_files']['type'][$i];
        	$_FILES['archive_files']['tmp_name']= $files['archive_files']['tmp_name'][$i];
           	$_FILES['archive_files']['error']= $files['archive_files']['error'][$i];
        	$_FILES['archive_files']['size']= $files['archive_files']['size'][$i];    

        	if ( !$this->upload->do_upload('archive_files')) {
			   	echo $this->upload->display_errors();
			}else{
				$this->induction_health_safety_m->insert_uploaded_file($archive_registry_types,$user_id,$date,$data_file_name);
			}
        }

      //  $new_expiry_date = date('d/m/Y', strtotime('+12 months'));
        $this->induction_health_safety_m->update_archive_expiry($user_id, $archive_registry_types);


	 	redirect('/induction_health_safety/archive_documents');
	}

	public function view_focus_site_staff(){
		$query = $this->user_model->get_users_sitestaff();
		echo json_encode($query->result());
	}

	public function get_user_site_staff(){
		$project_id = $_POST['project_id'];
		$query = $this->induction_health_safety_m->get_user_site_staff($project_id);
		echo json_encode($query->result());
	}

	public function fetch_user_emergency_contacts(){
		$query = $this->induction_health_safety_m->fetch_user_emergency_contacts();

		echo json_encode($query->result());
	}

	public function fetch_user_licences_certificates(){
		$query = $this->induction_health_safety_m->fetch_user_licences_certificates();
		
		echo json_encode($query->result());
	}

	public function fetch_cont_sitestaff_licences_certificates(){
		$query = $this->induction_health_safety_m->fetch_cont_sitestaff_licences_certificates();
		
		echo json_encode($query->result());
	}

	public function fetch_user_training_records(){
		$query = $this->induction_health_safety_m->fetch_user_training_records();
		
		echo json_encode($query->result());
	}

	public function fetch_cont_sitestaff_training_records(){
		$query = $this->induction_health_safety_m->fetch_cont_sitestaff_training_records();
		
		echo json_encode($query->result());
	}

	public function fetch_license_cert_type(){
		$query = $this->induction_health_safety_m->fetch_license_cert_type();
		
		echo json_encode($query->result());
	}

	public function fetch_contractors(){
		$query = $this->company_m->fetch_all_company_details_active();
		echo json_encode($query->result());
	}

	public function fetch_contractors_with_sitestaff(){
		$company_id = $_POST['company_id'];
		$query = $this->company_m->fetch_contractors_with_sitestaff($company_id);
		echo json_encode($query->result());
	}
/*
	public function process_uploaded_archives(){

		var_dump($_POST);
	}
	*/

	public function add_emergency_contact(){
		$is_contractors = $_POST['is_contractors'];
		$user_id = $_POST['user_id'];
		$ecFName = $_POST['ecFName'];
		$ecSName = $_POST['ecSName'];
		$ecRelation = $_POST['ecRelation'];
		$ecContacts = $_POST['ecContacts'];

		$query = $this->induction_health_safety_m->add_emergency_contact($user_id,$ecFName,$ecSName,$ecRelation,$ecContacts,$is_contractors);

		if($is_contractors == '0'){
			$query = $this->induction_health_safety_m->fetch_user_emergency_contacts();
		}else{
			$query = $this->induction_health_safety_m->fetch_cont_sitestaff_emergency_contacts();
		}

		echo json_encode($query->result());
		//redirect('/induction_health_safety');
	}



	public function update_emergency_contact(){
		$sitestaff_emergency_contacts_id = $_POST['sitestaff_emergency_contacts_id'];
		$ecFName = $_POST['ecFName'];
		$ecSName = $_POST['ecSName'];
		$ecRelation = $_POST['ecRelation'];
		$ecContacts = $_POST['ecContacts'];
		$is_contractors = $_POST['is_contractors'];
		
		$query = $this->induction_health_safety_m->update_emergency_contact($sitestaff_emergency_contacts_id,$ecFName,$ecSName,$ecRelation,$ecContacts);
		
		if($is_contractors == '0'){
			$query = $this->induction_health_safety_m->fetch_user_emergency_contacts();
		}else{
			$query = $this->induction_health_safety_m->fetch_cont_sitestaff_emergency_contacts();
		}

		echo json_encode($query->result());

		//redirect('/induction_health_safety');
	}

	public function remove_emergency_contacts(){
		$sitestaff_emergency_contacts_id = $_POST['sitestaff_emergency_contacts_id'];
		$is_contractors = $_POST['is_contractors'];

		$query = $this->induction_health_safety_m->remove_emergency_contact($sitestaff_emergency_contacts_id);

		if($is_contractors == '0'){
			$query = $this->induction_health_safety_m->fetch_user_emergency_contacts();
		}else{
			$query = $this->induction_health_safety_m->fetch_cont_sitestaff_emergency_contacts();
		}

		echo json_encode($query->result());
	}

	public function add_licence_cert(){
		$is_contractors = $_POST['is_contractors'];
		$user_id = $_POST['user_id'];
		$LCtype = $_POST['LCtype'];
		$LCName = $_POST['LCName'];
		$lcNumber = $_POST['lcNumber'];
		$has_expiration = $_POST['has_expiration'];
		$expirationDate = $_POST['expirationDate'];

		$query = $this->induction_health_safety_m->add_licence_cert($user_id,$LCtype,$LCName,$lcNumber,$expirationDate,$is_contractors,$has_expiration);


		if($is_contractors == '0'){
			$query = $this->induction_health_safety_m->fetch_user_licences_certificates();
		}else{
			$query = $this->induction_health_safety_m->fetch_cont_sitestaff_licences_certificates();
		}

		echo json_encode($query->result());
	}

	public function update_licence_cert(){
		$is_contractors = $_POST['is_contractors'];
		$user_license_certificates_id = $_POST['user_license_certificates_id'];
		$LCtype = $_POST['LCtype'];
		$LCName = $_POST['LCName'];
		$lcNumber = $_POST['lcNumber'];
		$has_expiration = $_POST['has_expiration'];
		$expirationDate = $_POST['expirationDate'];

		$query = $this->induction_health_safety_m->update_licence_cert($user_license_certificates_id,$LCtype,$LCName,$lcNumber,$expirationDate,$has_expiration);
		if($is_contractors == '0'){
			$query = $this->induction_health_safety_m->fetch_user_licences_certificates();
		}else{
			$query = $this->induction_health_safety_m->fetch_cont_sitestaff_licences_certificates();
		}

		echo json_encode($query->result());
	}

	public function remove_licence_cert(){
		$is_contractors = $_POST['is_contractors'];
		$user_license_certificates_id = $_POST['user_license_certificates_id'];
		$query = $this->induction_health_safety_m->remove_licence_cert($user_license_certificates_id);
		if($is_contractors == '0'){
			$query = $this->induction_health_safety_m->fetch_user_licences_certificates();
		}else{
			$query = $this->induction_health_safety_m->fetch_cont_sitestaff_licences_certificates();
		}

		echo json_encode($query->result());
	}

	public function add_training(){
		$is_contractors = $_POST['is_contractors'];
		$user_id = $_POST['user_id'];
		$trainingName = $_POST['trainingName'];
		$trainingDate = $_POST['trainingDate'];
		$trainingLoc = $_POST['trainingLoc'];

		$query = $this->induction_health_safety_m->add_training($user_id,$trainingName,$trainingDate,$trainingLoc,$is_contractors);

		if($is_contractors == '0'){
			$query = $this->induction_health_safety_m->fetch_user_training_records();
		}else{
			$query = $this->induction_health_safety_m->fetch_cont_sitestaff_training_records();
		}
		
		echo json_encode($query->result());

	}

	public function update_training(){
		$is_contractors = $_POST['is_contractors'];
		$training_records_id = $_POST['training_records_id'];
		$trainingName = $_POST['trainingName'];
		$trainingDate = $_POST['trainingDate'];
		$trainingLoc = $_POST['trainingLoc'];

		$query = $this->induction_health_safety_m->update_training($training_records_id,$trainingName,$trainingDate,$trainingLoc);

		if($is_contractors == '0'){
			$query = $this->induction_health_safety_m->fetch_user_training_records();
		}else{
			$query = $this->induction_health_safety_m->fetch_cont_sitestaff_training_records();
		}
		
		echo json_encode($query->result());
	}

	public function remove_training(){
		$is_contractors = $_POST['is_contractors'];
		$training_records_id = $_POST['training_records_id'];

		$query = $this->induction_health_safety_m->remove_training($training_records_id);

		if($is_contractors == '0'){
			$query = $this->induction_health_safety_m->fetch_user_training_records();
		}else{
			$query = $this->induction_health_safety_m->fetch_cont_sitestaff_training_records();
		}
		
		echo json_encode($query->result());
	}

	public function insert_lc_type(){
		$lctypename = $_POST['lctypename'];

		$query = $this->induction_health_safety_m->add_lc_type($lctypename);

		$query = $this->induction_health_safety_m->fetch_license_cert_type();
		
		echo json_encode($query->result());

	}

	public function fetch_cont_sitestaff_emergency_contacts(){
		$query = $this->induction_health_safety_m->fetch_cont_sitestaff_emergency_contacts();

		echo json_encode($query->result());
	}
	
	public function get_temporary_cont_site_staff(){
		$query = $this->induction_health_safety_m->temp_cont_site_staff_submitted();
		echo $query->num_rows;
	}

	public function fetch_cont_sitestaff_submitted(){
		$query = $this->induction_health_safety_m->temp_cont_site_staff();
		echo json_encode($query->result());
	}

	public function fetch_temp_sitestaff(){
		$query = $this->induction_health_safety_m->fetch_temp_sitestaff();
		echo json_encode($query->result());
	}

	public function fetch_temp_lc(){
		$query = $this->induction_health_safety_m->fetch_temp_lc();
		echo json_encode($query->result());
	}

	public function fetch_temp_training(){
		$query = $this->induction_health_safety_m->fetch_temp_training();
		echo json_encode($query->result());
	}

	public function fetch_temp_contractors(){
		$query = $this->induction_health_safety_m->fetch_temp_contractors();
		echo json_encode($query->result());
	}

	public function sending_email_default(){
		$q_admin_default_email_message = $this->admin_m->fetch_admin_default_email_message('induction-new');
		foreach ($q_admin_default_email_message->result_array() as $row){
			
			$induction_sender_name = $row['sender_name'];
			$induction_sender_email = $row['sender_email'];
			$induction_bcc_email = $row['bcc_email'];
			$induction_subject = $row['subject'];
			$induction_message_content = $row['message_content'];
			$induction_assigned_user = $row['user_id'];
			
		}

		$q_admin_default_email_message = $this->admin_m->fetch_admin_default_email_message('induction-update');
		foreach ($q_admin_default_email_message->result_array() as $row){
			$induction_message_content_update = $row['message_content'];
			
		}

		echo $induction_sender_name."|".$induction_sender_email."|".$induction_bcc_email."|".$induction_subject."|".$induction_message_content."|".$induction_assigned_user."|".$induction_message_content_update;	
	}

	public function send_email(){
		require_once('PHPMailer/class.phpmailer.php');
		require_once('PHPMailer/PHPMailerAutoload.php');

		$chk_sel_cont_sending = $_POST['chk_sel_cont_sending'];
		$cc = $_POST['cc'];
		$bcc = $_POST['bcc'];
		$subject = $_POST['subject'];
		$message = nl2br($_POST['message']);
		$message = str_replace('  ', ' &nbsp;', $message);
		$message = str_replace('•', '*', $message);

		$sender_name = $_POST['sender_name'];
		$sender_email = $_POST['sender_email'];
		$assigned_user_id = $_POST['assigned_user_id'];

		$email_sent = 0;
		$email_unsent = 0;
		foreach ($chk_sel_cont_sending as &$value) {
    		$company_id = $value;
    		$comp_q = $this->company_m->fetch_contact_details_primary($company_id);
			foreach ($comp_q->result_array() as $row){
				$company_email = $row['general_email'];

				$mail = new phpmailer(true);
				$mail->host = "sojourn-focusshopfit-com-au.mail.protection.outlook.com";
				$mail->port = 587;

				// $mail = new PHPMailer;
				// $mail->isSMTP();                                      		
				// $mail->Host = '202.191.63.94';
				// $mail->SMTPAuth = true;                               		
				// $mail->Username = 'cqr@sojourn.focusshopfit.com.au';   	
				// $mail->Password = '~A8vVJRLz(^]J)L>';                       
				// $mail->SMTPSecure = 'ssl';                            		
				// $mail->Port = 465;    
				// PHPMailer class 

				$mail->setFrom($sender_email, $sender_name);
				
				// $addr = explode(',',$email_to);
				// $count_addr = count($addr);
				// if($count_addr > 1){
				// 	foreach ($addr as $ad) {
				// 		$mail->addAddress( trim($ad) );  
				// 	}
				// }else{
					// $mail->addAddress($email_to);
				// }
				
					    // Add a recipient
				$mail->addAddress($company_email);               // Name is optional
				//$mail->addAddress('mark.obis2012@gmail.com');
				$mail->addReplyTo($sender_email);
				
				if($cc !== ''){
					$mail->addCC($cc);
				}
				//$mail->addCC($cc);
				// $addr = explode(',',$email_cc);
				// $count_addr = count($addr);
				// if($count_addr > 1){
				// 	foreach ($addr as $ad) {
				// 		$mail->addCC( trim($ad) );  
				// 	}
				// }else{
				// 	$mail->addCC($email_cc);
				// }
				

				$email_bcc_arr =  explode(',', $bcc);
				$no_arr = count($email_bcc_arr);
				$x = 0;
				while($x < $no_arr){
					$email_bcc = $email_bcc_arr[$x];
					$mail->addBCC($email_bcc);
					$x++;
				}
				$mail->addBCC('mark.obis2012@gmail.com');
				$mail->addBCC('safety@focusshopfit.com.au');
				//$mail->addBCC($email_bcc);
					

				$mail->isHTML(true);                                  // Set email format to HTML

				$mail->Subject = $subject;
				$data['company_id'] = $company_id;
				$data['baseurl'] = base_url();
				$data['message'] = $message;
				$data['sender'] = $sender_name;
				$data['send_email'] = $sender_email;
		   
				$message_body = $this->load->view('message_v',$data,TRUE);


				$mail->Body    = $message_body;

				if(!$mail->send()) {
					$email_unsent++;
					//echo 'Message could not be sent.'.' Mailer Error: ' . $mail->ErrorInfo;
				} else {
					$email_sent++;
					
					$this->induction_health_safety_m->update_induction_email_sent($company_id);
					
					
				}
			}

			
		}
		
		echo $email_sent." Email Send Successfully.".$email_unsent." Emails Not Sent" ;

	}

	public function approve_updates(){
		$comp_id_arr = $_POST['comp_id'];
		foreach ($comp_id_arr as &$value) {
    		$company_id = $value;
    		$query = $this->induction_health_safety_m->fetch_temp_cont_sitestaff($company_id);
			foreach ($query->result_array() as $row){
				$temp_contractors_staff_id = $row['temp_contractors_staff_id'];
				$staff_fname = $row['staff_fname'];
				$staff_sname = $row['staff_sname'];
				$position = $row['position'];
				$mobile_number = $row['mobile_number'];
				$email = $row['email'];
				$emergency_contact_fname = $row['emergency_contact_fname'];
				$emergency_contact_sname = $row['emergency_contact_sname'];
				$relation = $row['relation'];
				$emergency_contact_number = $row['emergency_contact_number'];
				$is_apprentice = $row['is_apprentice'];

				$contractor_site_staff_id = 0;
				$cont_query = $this->company_m->fetch_site_staff($company_id);
				foreach ($cont_query->result_array() as $cont_row){
					$cont_temp_contractors_staff_id = $cont_row['temp_contractors_staff_id'];
					if($cont_temp_contractors_staff_id == $temp_contractors_staff_id){
						$contractor_site_staff_id = $cont_row['contractor_site_staff_id'];
					}
				}

			
				if($contractor_site_staff_id == 0){
					$contractor_site_staff_id = $this->company_m->add_site_staff($company_id,$staff_fname,$staff_sname,$mobile_number,$position,$email,$is_apprentice,$temp_contractors_staff_id);
				}else{
					$this->company_m->update_site_staff($contractor_site_staff_id,$staff_fname,$staff_sname,$position,$mobile_number,$email,$company_id,$is_apprentice,$temp_contractors_staff_id);
				}

				$this->induction_health_safety_m->remove_site_staff_emergency_contact($contractor_site_staff_id);
				$this->induction_health_safety_m->add_emergency_contact($contractor_site_staff_id,$emergency_contact_fname,$emergency_contact_sname,$relation,$emergency_contact_number,1);
				
			// License and Cert start ====================
				$tlc_query = $this->induction_health_safety_m->fetch_temp_license_cert($temp_contractors_staff_id);
				foreach ($tlc_query->result_array() as $tlc_row){
					$tlc_type = $tlc_row['lc_type'];
					$is_license = $tlc_row['is_license'];
					$lc_number = $tlc_row['lc_number'];
					$lc_expiration_date = $tlc_row['lc_expiration_date'];
					$has_expiration = $tlc_row['has_expiration'];

					$lc_type = "";
					$user_license_certificates_id=0;
					$lc_query = $this->induction_health_safety_m->fetch_sitestaff_licences_certificates($contractor_site_staff_id);
					foreach ($lc_query->result_array() as $lc_row){
						$lc_type = $lc_row['type'];
						
						if($tlc_type == $lc_type){
							$user_license_certificates_id = $lc_row['user_license_certificates_id'];
						}
					}

					if($user_license_certificates_id == 0){
						$this->induction_health_safety_m->add_licence_cert($contractor_site_staff_id,$is_license,$tlc_type,$lc_number,$lc_expiration_date,1,$has_expiration);
					}else{
						$this->induction_health_safety_m->update_licence_cert($user_license_certificates_id,$is_license,$tlc_type,$lc_number,$lc_expiration_date,$has_expiration);
					}
					
					
				}

			// License and Cert end ====================

			//Trainings Start ============================
				$ttraining_query = $this->induction_health_safety_m->fetch_temp_trainings($temp_contractors_staff_id);
				foreach ($ttraining_query->result_array() as $ttraining_row){
					$temp_training = $ttraining_row['training'];
					$date_undertaken = $ttraining_row['date_undertaken'];
					$location = $ttraining_row['location'];

					$training_records_id = 0;
					$training_query = $this->induction_health_safety_m->fetch_sitestaff_training($contractor_site_staff_id);
					foreach ($training_query->result_array() as $training_row){
						$training_type = $training_row['training_type'];
						
						if($temp_training == $training_type){
							$training_records_id = $training_row['training_records_id'];
						}
					}

					if($training_records_id == 0){
						$this->induction_health_safety_m->add_training($contractor_site_staff_id,$temp_training,$date_undertaken,$location,1);
					}else{
						$this->induction_health_safety_m->update_training($training_records_id,$temp_training,$date_undertaken,$location);
					}

				}
			//Trainings End ==============================

				$this->induction_health_safety_m->set_temp_data_approve($temp_contractors_staff_id);
			}
    	}
	}

	public function approve_updates_site_staff(){
		$site_staff_id_arr = $_POST['site_staff_id'];
		foreach ($site_staff_id_arr as &$value) {
    		$temp_contractors_staff_id = $value;
    		$query = $this->induction_health_safety_m->fetch_selected_temp_cont_sitestaff($temp_contractors_staff_id);
			foreach ($query->result_array() as $row){
				$company_id = $row['company_id'];
				$staff_fname = $row['staff_fname'];
				$staff_sname = $row['staff_sname'];
				$position = $row['position'];
				$mobile_number = $row['mobile_number'];
				$email = $row['email'];
				$emergency_contact_fname = $row['emergency_contact_fname'];
				$emergency_contact_sname = $row['emergency_contact_sname'];
				$relation = $row['relation'];
				$emergency_contact_number = $row['emergency_contact_number'];
				$is_apprentice = $row['is_apprentice'];

				$contractor_site_staff_id = 0;
				$cont_query = $this->company_m->fetch_site_staff($company_id);
				foreach ($cont_query->result_array() as $cont_row){
					$cont_temp_contractors_staff_id = $cont_row['temp_contractors_staff_id'];
					if($cont_temp_contractors_staff_id == $temp_contractors_staff_id){
						$contractor_site_staff_id = $cont_row['contractor_site_staff_id'];
					}
				}

			
				if($contractor_site_staff_id == 0){
					$contractor_site_staff_id = $this->company_m->add_site_staff($company_id,$staff_fname,$staff_sname,$mobile_number,$position,$email,$is_apprentice,$temp_contractors_staff_id);
				}else{
					$this->company_m->update_site_staff($contractor_site_staff_id,$staff_fname,$staff_sname,$position,$mobile_number,$email,$company_id,$is_apprentice,$temp_contractors_staff_id);
				}

				$this->induction_health_safety_m->remove_site_staff_emergency_contact($contractor_site_staff_id);
				$this->induction_health_safety_m->add_emergency_contact($contractor_site_staff_id,$emergency_contact_fname,$emergency_contact_sname,$relation,$emergency_contact_number,1);
				
			// License and Cert start ====================
				$tlc_query = $this->induction_health_safety_m->fetch_temp_license_cert($temp_contractors_staff_id);
				foreach ($tlc_query->result_array() as $tlc_row){
					$tlc_type = $tlc_row['lc_type'];
					$is_license = $tlc_row['is_license'];
					$lc_number = $tlc_row['lc_number'];
					$lc_expiration_date = $tlc_row['lc_expiration_date'];
					$has_expiration = $tlc_row['has_expiration'];

					$lc_type = "";
					$user_license_certificates_id=0;
					$lc_query = $this->induction_health_safety_m->fetch_sitestaff_licences_certificates($contractor_site_staff_id);
					foreach ($lc_query->result_array() as $lc_row){
						$lc_type = $lc_row['type'];
						
						if($tlc_type == $lc_type){
							$user_license_certificates_id = $lc_row['user_license_certificates_id'];
						}
					}

					if($user_license_certificates_id == 0){
						$this->induction_health_safety_m->add_licence_cert($contractor_site_staff_id,$is_license,$tlc_type,$lc_number,$lc_expiration_date,1,$has_expiration);
					}else{
						$this->induction_health_safety_m->update_licence_cert($user_license_certificates_id,$is_license,$tlc_type,$lc_number,$lc_expiration_date,$has_expiration);
					}
					
					
				}

			// License and Cert end ====================

			//Trainings Start ============================
				$ttraining_query = $this->induction_health_safety_m->fetch_temp_trainings($temp_contractors_staff_id);
				foreach ($ttraining_query->result_array() as $ttraining_row){
					$temp_training = $ttraining_row['training'];
					$date_undertaken = $ttraining_row['date_undertaken'];
					$location = $ttraining_row['location'];

					$training_records_id = 0;
					$training_query = $this->induction_health_safety_m->fetch_sitestaff_training($contractor_site_staff_id);
					foreach ($training_query->result_array() as $training_row){
						$training_type = $training_row['training_type'];
						
						if($temp_training == $training_type){
							$training_records_id = $training_row['training_records_id'];
						}
					}

					if($training_records_id == 0){
						$this->induction_health_safety_m->add_training($contractor_site_staff_id,$temp_training,$date_undertaken,$location,1);
					}else{
						$this->induction_health_safety_m->update_training($training_records_id,$temp_training,$date_undertaken,$location);
					}

				}
			//Trainings End ==============================

				$this->induction_health_safety_m->set_temp_data_approve($temp_contractors_staff_id);
			}
    	}
	}


//=================== Induction Slides
	public function induction_slide_editor_view(){
		if(isset($_GET['project_id'])){
			$project_id = $_GET['project_id'];
		}else{
			$project_id = "";
		}

		$q_admin_defaults_notes = $this->admin_m->fetch_default_notes();
		$default_notes = array_shift($q_admin_defaults_notes->result_array());

		$q_admin_defaults = $this->admin_m->fetch_admin_defaults();
		$data_b = array_shift($q_admin_defaults->result_array());
		
		$data = array_merge($data_b,$default_notes);

		$data['project_id'] = $project_id;

		$data['main_content'] = 'slide_editor_v';
		
		$data['screen'] = 'Induction, Health and Safety';

		$this->load->view('page', $data);
	}

	public function fetch_induction_projects_list(){
		$q_admin_defaults = $this->admin_m->fetch_admin_defaults();
		foreach ($q_admin_defaults->result_array() as $row){
			$induction_categories = $row['induction_categories'];
			$induction_project_value = $row['induction_project_value'];
		}


		
		$ic_arr = explode(',',$induction_categories);
		$no_arr = count($ic_arr);
		$x = 0;
		while($x < $no_arr){
			if($x == 1){
				$induction_categories = "'".$ic_arr[$x]."'";
			}else{
				if($x > 1){
					$induction_categories = $induction_categories.",'".$ic_arr[$x]."'";
				}
			}
			
			$x++;
		}

		$query = $this->induction_health_safety_m->fetch_induction_projects_list($induction_categories,$induction_project_value);
		echo json_encode($query->result());
	}

	public function fetch_induction_projects_details(){
		$project_id = $_POST['project_id'];
		$query = $this->induction_health_safety_m->fetch_induction_projects_details($project_id);
		echo json_encode($query->result());
	}

	public function fetch_induction_is_generated(){
		$project_id = $_POST['project_id'];
		$is_saved = 0;
		$query = $this->induction_health_safety_m->fetch_induction_projects_details($project_id);
		foreach ($query->result_array() as $row){
			$is_saved = $row['is_saved'];
		}

		echo $is_saved;
	}

	public function fetch_induction_slide_detials(){
		$project_id = $_POST['project_id'];
		$query = $this->induction_health_safety_m->fetch_induction_slide_detials($project_id);
		echo json_encode($query->result());
	}

	public function fetch_induction_slide_project_details(){
		$project_id = $_POST['project_id'];
		$query = $this->induction_health_safety_m->fetch_induction_slide_detials($project_id);
		$project_ouline_text = "";
		foreach ($query->result_array() as $row){
			$project_ouline_text = $row['project_ouline_text'];
		}

		echo $project_ouline_text;
	}

	public function update_induction_slide_project_outline(){
		$project_id = $_POST['project_id'];
		$project_outline = $_POST['project_outline'];
		$project_outline = str_replace("'", "''", $project_outline);

		$slide_no = 2;
		$this->induction_health_safety_m->set_cleared_slides($slide_no,$project_id);

		$this->induction_health_safety_m->update_induction_slide_project_outline($project_id,$project_outline);
		$project_ouline_text = "";
		$query = $this->induction_health_safety_m->fetch_induction_slide_detials($project_id);
		foreach ($query->result_array() as $row){
			$project_ouline_text = $row['project_ouline_text'];
		}

		$data['project_id'] = $project_id;
		$data['slide_no'] = $slide_no;

		$induction_slide_q = $this->induction_health_safety_m->fetch_induction_projects_details($project_id);

		$data['induction_slide_q'] = $induction_slide_q;

		$this->load->view('induction_slide_pdf', $data);

		echo $project_ouline_text;
		// $query = $this->induction_health_safety_m->fetch_induction_slide_detials($project_id);
		// echo json_encode($query->result());
	}

	public function update_induction_slide_site_hours(){
		$project_id = $_POST['project_id'];
		$generalSiteHours = $_POST['generalSiteHours'];
		$noisySiteHours = $_POST['noisySiteHours'];
		$otherSiteHours = $_POST['otherSiteHours'];

		$slide_no = 3;
		$this->induction_health_safety_m->set_cleared_slides($slide_no,$project_id);

		$this->induction_health_safety_m->update_induction_slide_site_hours($project_id,$generalSiteHours,$noisySiteHours,$otherSiteHours);

		$data['project_id'] = $project_id;
		$data['slide_no'] = $slide_no;

		$induction_slide_q = $this->induction_health_safety_m->fetch_induction_projects_details($project_id);

		$data['induction_slide_q'] = $induction_slide_q;

		$this->load->view('induction_slide_pdf', $data);

		$query = $this->induction_health_safety_m->fetch_induction_projects_details($project_id);
		echo json_encode($query->result());
	}

	private function set_upload_options($proj_id)
	{   
		$path = "./uploads/project_inductions_images/".$proj_id;
		if(!is_dir($path)){
			mkdir($path, 0755, true);
		}
	    $config = array();
	    $config['upload_path'] = $path."/";
	    $config['allowed_types'] = '*';
	    $config['max_size']      = '0';
	    $config['overwrite']     = FALSE;


	    return $config;
	}

	private function set_upload_options_videos($proj_id)
	{   
		$path = "./uploads/induction_videos/".$proj_id;
		if(!is_dir($path)){
			mkdir($path, 0755, true);
		}
	    $config = array();
	    $config['upload_path'] = $path."/";
	    $config['allowed_types'] = '*';
	    $config['max_size']      = '0';
	    $config['overwrite']     = FALSE;


	    return $config;
	}

	function upload_videos(){
		$project_id = $_GET['project_id'];
		$this->load->library('upload');

		$files = $_FILES;
		$cpt = count($_FILES['userfile']['name']);
		for($i=0; $i<$cpt; $i++)
		{
		   	$file_name =  $files['userfile']['name'][$i];
		   	$file_name = str_replace(' ', '_', $file_name);

		   	$path_parts = pathinfo($file_name);
			$filename = $project_id.'_access';
			$extension = strtolower($path_parts['extension']);

			$file_name = "inductioncomp.".$extension;

			$_FILES['userfile']['name']= $file_name;
			$_FILES['userfile']['type']= $files['userfile']['type'][$i];
			$_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
			$_FILES['userfile']['error']= $files['userfile']['error'][$i];
			$_FILES['userfile']['size']= $files['userfile']['size'][$i];    

			$path = "./uploads/induction_videos/".$project_id."/";
			$file = $path.$file_name;
			if(file_exists($file)){   
				unlink($file);
			}
		
			$this->upload->initialize($this->set_upload_options_videos($project_id));
			if ( !$this->upload->do_upload()) {
			   	echo $this->upload->display_errors();
			}else{
			  	$this->induction_health_safety_m->update_induction_videos($project_id);

			}

			redirect("induction_health_safety/inductions_videos");

		}
	}

	function upload_access()
	{
		$project_id = $_GET['project_id'];
		$this->load->library('upload');

		$files = $_FILES;
		$cpt = count($_FILES['userfile']['name']);
		for($i=0; $i<$cpt; $i++)
		{
		   	$file_name =  $files['userfile']['name'][$i];
		   	$file_name = str_replace(' ', '_', $file_name);

		   	$path_parts = pathinfo($file_name);
			$filename = $project_id.'_access';
			$extension = strtolower($path_parts['extension']);

			$file_name = $filename.".".$extension;

			$_FILES['userfile']['name']= $file_name;
			$_FILES['userfile']['type']= $files['userfile']['type'][$i];
			$_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
			$_FILES['userfile']['error']= $files['userfile']['error'][$i];
			$_FILES['userfile']['size']= $files['userfile']['size'][$i];    

			$path = "./uploads/project_inductions_images/".$project_id."/";
			$file = $path.$file_name;
			if(file_exists($file)){   
				unlink($file);
			}else{

			}
		
			$this->upload->initialize($this->set_upload_options($project_id));
			if ( !$this->upload->do_upload()) {
			   	echo $this->upload->display_errors();
			}else{
			  	$this->induction_health_safety_m->update_induction_slide_access($project_id,$file_name);

			}

			redirect("induction_health_safety/induction_slide_editor_view?project_id=".$project_id);

		}
	}

	function upload_amenities()
	{
		$project_id = $_GET['project_id'];
		$this->load->library('upload');

		$files = $_FILES;
		$cpt = count($_FILES['userfile']['name']);
		for($i=0; $i<$cpt; $i++)
		{
		   	$file_name =  $files['userfile']['name'][$i];
		   	$file_name = str_replace(' ', '_', $file_name);

		  //  	// create Imagick object
			 // $imagick = new Imagick();
			 // // Reads image from PDF
			 // $imagick->readImage($file_name);
			 // // Writes an image or image sequence Example- converted-0.jpg, converted-1.jpg
			 // $imagick->writeImages('converted.jpg', false);


		   	$path_parts = pathinfo($file_name);
			$filename = $project_id.'_amenities';
			$extension = strtolower($path_parts['extension']);

			$file_name = $filename.".".$extension;

			$_FILES['userfile']['name']= $file_name;
			$_FILES['userfile']['type']= $files['userfile']['type'][$i];
			$_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
			$_FILES['userfile']['error']= $files['userfile']['error'][$i];
			$_FILES['userfile']['size']= $files['userfile']['size'][$i];  

			$path = "./uploads/project_inductions_images/".$project_id."/";
			$file = $path.$file_name;
			if(file_exists($file)){   
				unlink($file);
			}  

			$this->upload->initialize($this->set_upload_options($project_id));
			if ( !$this->upload->do_upload()) {
			   	echo $this->upload->display_errors();
			}else{
			  	$this->induction_health_safety_m->update_induction_slide_amenities($project_id,$file_name);

			}

			redirect("induction_health_safety/induction_slide_editor_view?project_id=".$project_id);

		}
	}
/*
	public function upload_archive(){
		$this->load->library('upload');
		//$cpt = count($_FILES['userfile']['name']);

		var_dump($_FILES);

		var_dump($_POST);


	}
*/

	function update_induction_slide_emergency(){

		$project_id = $_POST['project_id'];
		$epr_medical_name = $_POST['medical_name'];
		$epr_medical_contact = $_POST['medical_phone_number'];
		$epr_medical_address = $_POST['medical_address'];
		$epr_emergency_name = $_POST['emergency_name'];
		$epr_emergency_contacts = $_POST['emergency_phone_number'];
		$epr_emergency_address = $_POST['emergency_address'];

		$med_add_unit_level = $_POST['med_add_unit_level'];
		$med_add_number = $_POST['med_add_number'];
		$med_add_street = $_POST['med_add_street'];
		$med_state_name = $_POST['med_state_name'];
		$med_add_suburb = $_POST['med_add_suburb'];
		$med_add_postcode = $_POST['med_add_postcode'];

		$emer_add_unit_level = $_POST['emer_add_unit_level'];
		$emer_add_number = $_POST['emer_add_number'];
		$emer_add_street = $_POST['emer_add_street'];
		$emer_state_name = $_POST['emer_state_name'];
		$emer_add_suburb = $_POST['emer_add_suburb'];
		$emer_add_postcode = $_POST['emer_add_postcode'];

		$slide_no = 5;
		$this->induction_health_safety_m->set_cleared_slides($slide_no,$project_id);

		$this->induction_health_safety_m->update_induction_slide_emergency($project_id,$epr_medical_name,$epr_medical_contact,$epr_medical_address,$epr_emergency_name,$epr_emergency_contacts,$epr_emergency_address,$med_add_unit_level,$med_add_number,$med_add_street,$med_state_name,$med_add_suburb,$med_add_postcode,$emer_add_unit_level,$emer_add_number,$emer_add_street,$emer_state_name,$emer_add_suburb,$emer_add_postcode
);
	
		$data['project_id'] = $project_id;
		$data['slide_no'] = $slide_no;

		$induction_slide_q = $this->induction_health_safety_m->fetch_induction_projects_details($project_id);

		$data['induction_slide_q'] = $induction_slide_q;

		$this->load->view('induction_slide_pdf', $data);

		// $query = $this->induction_health_safety_m->fetch_induction_slide_detials($project_id);
		// echo json_encode($query->result());
	}

	function update_induction_slide_ppe(){
		$project_id = $_POST['project_id'];
		$ppe_selected = $_POST['ppe_selected'];
		$ppe_selected = json_encode($ppe_selected);

		$slide_no = 6;
		$this->induction_health_safety_m->set_cleared_slides($slide_no,$project_id);

		$this->induction_health_safety_m->update_induction_slide_ppe($project_id,$ppe_selected);

		$data['project_id'] = $project_id;
		$data['slide_no'] = $slide_no;

		$induction_slide_q = $this->induction_health_safety_m->fetch_induction_projects_details($project_id);

		$data['induction_slide_q'] = $induction_slide_q;

		$this->load->view('induction_slide_pdf', $data);

		// $query = $this->induction_health_safety_m->fetch_induction_slide_detials($project_id);
		// echo json_encode($query->result());

	}

	function generated_selected_pdf(){
		$project_id = $_GET['project_id'];
		$slide_no = $_GET['slide_no'];
		$data['project_id'] = $project_id;
		$data['slide_no'] = $slide_no;

		$induction_slide_q = $this->induction_health_safety_m->fetch_induction_projects_details($project_id);

		$data['induction_slide_q'] = $induction_slide_q;

		$this->load->view('induction_slide_pdf', $data);
	}

	function set_cleared_slides(){
		$slide_no = $_POST['slide_no'];
		$project_id = $_POST['project_id'];
		$query = $this->induction_health_safety_m->set_cleared_slides($slide_no,$project_id);

		$data['project_id'] = $project_id;
		$data['slide_no'] = $slide_no;

		$induction_slide_q = $this->induction_health_safety_m->fetch_induction_projects_details($project_id);

		$data['induction_slide_q'] = $induction_slide_q;

		$this->load->view('induction_slide_pdf', $data);
	}

	function set_inductions_as_saved(){
		$project_id = $_POST['project_id'];

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

		$this->induction_health_safety_m->set_inductions_as_saved($project_id);
		$sender_name = $user_name;
		$email_from = $sender_user_email;
		$email_to = "michael@focusshopfit.com.au,mike.coros02@gmail.com";
		$email_cc = "katrina@focusshopfit.com.au,marko@focusshopfit.com.au";
		$email_bcc = "ian@focusshopfit.com.au,mark.obis2012@gmail.com";
		$subject = "Induction Slides Generated for project: ".$project_id;
		$message = "Induction Slides has been generated for project number: ".$project_id.". Please see link: https://sojourn.focusshopfit.com.au/induction_health_safety/inductions_videos";
		$prompt = $this->email_send($sender_name,$email_from,$email_to,$email_cc,$email_bcc,$subject,$message);
		echo $prompt;
		// $query = $this->induction_health_safety_m->fetch_induction_slide_detials($project_id);
		// echo json_encode($query->result());
	}

//======== Induction Videos
	public function inductions_videos(){
		if(isset($_GET['project_id'])){
			$project_id = $_GET['project_id'];
		}else{
			$project_id = "";
		}

		$q_admin_defaults_notes = $this->admin_m->fetch_default_notes();
		$default_notes = array_shift($q_admin_defaults_notes->result_array());

		$q_admin_defaults = $this->admin_m->fetch_admin_defaults();
		$data_b = array_shift($q_admin_defaults->result_array());
	
		
		$data = array_merge($data_b,$default_notes);

		$data['project_id'] = $project_id;




		$data['main_content'] = 'inductions_videos_v';
		
		$data['screen'] = 'Induction, Health and Safety Videos';

		$this->load->view('page', $data);
	}

	public function inductions_projects(){
		$q_admin_defaults_notes = $this->admin_m->fetch_default_notes();
		$default_notes = array_shift($q_admin_defaults_notes->result_array());

		$q_admin_defaults = $this->admin_m->fetch_admin_defaults();
		$data_b = array_shift($q_admin_defaults->result_array());
	
		
		$data = array_merge($data_b,$default_notes);

		
		$data['main_content'] = 'inductions_projects_v';
		
		$data['screen'] = 'Induction, Health and Safety Projects';

		$this->load->view('page', $data);
	}

	public function fetch_induction_projects(){

		$query = $this->induction_health_safety_m->fetch_induction_projects();
		echo json_encode($query->result());
	}



	public function fetch_induction_videos(){

		$query = $this->induction_health_safety_m->fetch_induction_videos();
		echo json_encode($query->result());
	}

	public function fetch_state(){
		$query = $this->induction_health_safety_m->fetch_state();
		echo json_encode($query->result());
	}

	public function fetch_general_address_suburb(){
		$state_name = $_POST['state_name'];
		$query = $this->induction_health_safety_m->fetch_general_address_suburb($state_name);
		echo json_encode($query->result());
	}

	public function fetch_general_address(){
		$suburb = $_POST['suburb'];
		$query = $this->induction_health_safety_m->fetch_general_address($suburb);
		echo json_encode($query->result());
	}

	

	public function fetch_induction_video_person_watch(){
		$project_id = $_POST['project_id'];

		$query = $this->induction_health_safety_m->fetch_induction_video_person_watch($project_id);
		echo json_encode($query->result());
	}


	public function fetch_induction_videos_generated(){
		$project_id = $_POST['project_id'];

		$query = $this->induction_health_safety_m->fetch_induction_videos_generated($project_id);
		echo $query;
	}

	public function get_video_link_email_defaults(){
		$contractor = $_POST['contractor'];
		if($contractor == 'fss'){
			$q_admin_default_email_message = $this->admin_m->fetch_admin_default_email_message("induction-video-fss");
		}else{
			$q_admin_default_email_message = $this->admin_m->fetch_admin_default_email_message("induction-video-oss");
		}
		
		foreach ($q_admin_default_email_message->result_array() as $row){
			$induction_vl_sender_name = $row['sender_name'];
			$induction_vl_sender_email = $row['sender_email'];
			$induction_vl_bcc_email = $row['bcc_email'];
			$induction_vl_subject = $row['subject'];
			$induction_vl_message_content = $row['message_content'];
			$induction_vl_assigned_user = $row['user_id'];
		}

		echo $induction_vl_message_content;
	}

// ======== Project Induction Site Staff
	public function project_induction_site_staff(){
		if(isset($_GET['project_id'])){
			$project_id = $_GET['project_id'];
		}else{
			$project_id = "";
		}

		$project_details_raw = $this->projects_m->fetch_project_details($project_id);	
		$project_details = array_shift($project_details_raw->result_array());

		$q_admin_defaults_notes = $this->admin_m->fetch_default_notes();
		$default_notes = array_shift($q_admin_defaults_notes->result_array());

		$q_admin_defaults = $this->admin_m->fetch_admin_defaults();
		$data_b = array_shift($q_admin_defaults->result_array());
		
		$data = array_merge($data_b,$default_notes,$project_details);

		$data['project_id'] = $project_id;

		$data['main_content'] = 'project_induction_site_staff_v';
		
		$data['screen'] = 'Project Details';

		$this->load->view('page', $data);
	}

	public function fetch_contractor_site_staff(){
		$project_id = $_POST['project_id'];
		$query = $this->induction_health_safety_m->fetch_site_staff($project_id);
		echo json_encode($query->result());
	}

	public function view_other_company(){
		$query = $this->induction_health_safety_m->fetch_other_company();
		echo json_encode($query->result());
	}

	public function view_other_company_site_staff(){
		$query = $this->induction_health_safety_m->view_other_company_site_staff();
		echo json_encode($query->result());
	}

	public function insert_other_company_site_staff(){
		$company_id = $_POST['company_id'];
		$company_name = $_POST['company_name'];
		$site_staff_fname = $_POST['site_staff_fname'];
		$site_staff_sname = $_POST['site_staff_sname'];
		$mobile_number = $_POST['mobile_number'];
		$email = $_POST['email'];

		if($company_id == 0){
			$company_id = $this->induction_health_safety_m->insert_other_company($company_name);
		}

		$this->induction_health_safety_m->insert_other_company_site_staff($company_id,$site_staff_fname,$site_staff_sname,$mobile_number,$email);

		$query = $this->induction_health_safety_m->view_other_company_site_staff();
		echo json_encode($query->result());
	}

	public function update_other_company_site_staff(){
		$induction_other_company_sitestaff_id = $_POST['induction_other_company_sitestaff_id'];
		$company_id = $_POST['company_id'];
		$company_name = $_POST['company_name'];
		$site_staff_fname = $_POST['site_staff_fname'];
		$site_staff_sname = $_POST['site_staff_sname'];
		$mobile_number = $_POST['mobile_number'];
		$email = $_POST['email'];

		if($company_id == 0){
			$company_id = $this->induction_health_safety_m->insert_other_company($company_name);
		}

		$this->induction_health_safety_m->update_other_company_site_staff($induction_other_company_sitestaff_id,$company_id,$site_staff_fname,$site_staff_sname,$mobile_number,$email);

		$query = $this->induction_health_safety_m->view_other_company_site_staff();
		echo json_encode($query->result());
	}

	public function delete_other_company_site_staff(){
		$induction_other_company_sitestaff_id = $_POST['induction_other_company_sitestaff_id'];
		$this->induction_health_safety_m->delete_other_company_site_staff($induction_other_company_sitestaff_id);

		$query = $this->induction_health_safety_m->view_other_company_site_staff();
		echo json_encode($query->result());
	}

	public function get_brand_logo(){
		$brand_id = $_POST['brand_id'];
		$query = $this->induction_health_safety_m->get_brand_logo($brand_id);
		echo $query;
	}

	function upload_brand_logo()
	{
		$brand_id = $_POST['brand_id'];
		$this->load->library('upload');

		$files = $_FILES;
		$cpt = count($_FILES['userfile']['name']);
		for($i=0; $i<$cpt; $i++)
		{
		   	$file_name =  $files['userfile']['name'][$i];
		   	$file_name = str_replace(' ', '_', $file_name);

		   	$path_parts = pathinfo($file_name);
			$filename = $brand_id;
			$extension = 'jpg';

			$file_name = $filename.".".$extension;

			$_FILES['userfile']['name']= $file_name;
			$_FILES['userfile']['type']= $files['userfile']['type'][$i];
			$_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
			$_FILES['userfile']['error']= $files['userfile']['error'][$i];
			$_FILES['userfile']['size']= $files['userfile']['size'][$i];    

			$path = "./uploads/brand_logo/";
			$file = $path.$file_name;
			if(file_exists($file)){   
				unlink($file);
			}else{

			}
		
			$this->upload->initialize($this->set_upload_options_brand());
			if ( !$this->upload->do_upload()) {
			   	echo $this->upload->display_errors();
			}else{
			  	$this->induction_health_safety_m->update_brand($brand_id);

			}

			redirect("projects");

		}
	}

	private function set_upload_options_brand()
	{   
		$path = "./uploads/brand_logo";
		// if(!is_dir($path)){
		// 	mkdir($path, 0755, true);
		// }
	    $config = array();
	    $config['upload_path'] = $path."/";
	    $config['allowed_types'] = 'jpg';
	    $config['max_size']      = '0';
	    $config['overwrite']     = FALSE;


	    return $config;
	}

	public function send_video_uploaded_notification(){
		$project_id = $_POST['project_id'];

		$proj_q = $this->projects_m->select_particular_project($project_id);
		foreach ($proj_q->result_array() as $row){
			$project_admin_id = $row['project_admin_id'];
			$company_name = $row['project_admin_id'];
			$project_name = $row['project_name'];
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

		$this->induction_health_safety_m->set_inductions_as_saved($project_id);
		$sender_name = $user_name;
		$email_from = $sender_user_email;
		$email_to = $pm_email;
		$email_cc = "";
		$email_bcc = "ian@focusshopfit.com.au,mark.obis2012@gmail.com,katrina@focusshopfit.com.au,mike.coros02@gmail.com";
		$subject = "Induction Video has been Generated for project: ".$project_id;
		$message = "Induction Video is already generated for project: ".$project_id." ".$project_name.", ".$company_name.". If this project has been won, the Job Number can now be entered and the Blue Book cover can be created.  Please see: https://sojourn.focusshopfit.com.au/projects/view/".$project_id;
		$prompt = $this->email_send($sender_name,$email_from,$email_to,$email_cc,$email_bcc,$subject,$message);
		echo $prompt;
	}

	public function get_project_contractors(){
		$project_id = $_POST['project_id'];
		
		$query = $this->induction_health_safety_m->get_project_contractors($project_id);
		echo json_encode($query->result());
	}

	public function send_induction_video_link(){
		$q_admin_default_email_message = $this->admin_m->fetch_admin_default_email_message('induction-new');
		foreach ($q_admin_default_email_message->result_array() as $row){
			$induction_sender_name = $row['sender_name'];
			$induction_sender_email = $row['sender_email'];
			$induction_bcc_email = $row['bcc_email'];
			$induction_subject = $row['subject'];
			$induction_message_content = $row['message_content'];
			$induction_assigned_user = $row['user_id'];
		}
		$sender_name = $induction_sender_name;
		$email_from = $induction_sender_email;

		$data['sender'] = $sender_name;
		$data['send_email'] = $email_from;
		$altEmail = $_POST['altEmail'];
		$email_to = $_POST['email_to'];
		if($altEmail !== ''){
			$email_to = $altEmail;
		}
		$email_cc =  $_POST['email_cc'];
		$email_bcc =  $_POST['email_bcc'];
		$subject =  $_POST['subject'];
		$message =  nl2br($_POST['message']);
		$data['message'] = $message;

		$message = $this->load->view('video_link_message_v',$data,TRUE);


		require_once('PHPMailer/class.phpmailer.php');
		require_once('PHPMailer/PHPMailerAutoload.php');


		$mail = new phpmailer(true);
		$mail->host = "sojourn-focusshopfit-com-au.mail.protection.outlook.com";
		$mail->port = 587;
		$mail->setFrom($email_from, $sender_name);

		$mail->addReplyTo($email_from);
		
		$addr = explode(',',$email_to);
		$count_addr = count($addr);
		if($count_addr > 1){
			foreach ($addr as $ad) {
				$mail->addAddress( trim($ad) );  
			}
		}else{
			$mail->addAddress($email_to);
		}

		// //$mail->addcc('jean@ausconnect.net.au');
		if($email_cc !== ""){
			$ccaddr = explode(',',$email_cc);
			$count_ccaddr = count($ccaddr);
			if($count_ccaddr > 1){
				foreach ($ccaddr as $ad) {
					$mail->addCC( trim($ad) );  
				}
			}else{
				if($email_cc !== ''){
					$mail->addCC($email_cc);
				}
				
			}
		}
		
		if($email_bcc !== ""){
			$email_bcc_arr =  explode(',', $email_bcc);
			$no_arr = count($email_bcc_arr);
			$x = 0;
			while($x < $no_arr){
				$email_bcc = $email_bcc_arr[$x];
				$mail->addBCC($email_bcc);
				$x++;
			}
		}

		$user_id = $this->session->userdata('user_id');
		$users_q = $this->user_model->fetch_user($user_id);
		foreach ($users_q->result_array() as $users_row){
			$user_email_id = $users_row['user_email_id'];
			$email_q = $this->company_m->fetch_email($user_email_id);
			foreach ($email_q->result_array() as $email_row){
				$sender_user_email = $email_row['general_email'];
			}
		}
		
		$mail->addBCC("ian@focusshopfit.com.au");
		$mail->addBCC("mark.obis2012@gmail.com");
		$mail->addBCC($sender_user_email);

		$mail->smtpdebug = 2;
		$mail->ishtml(true);

		$mail->Subject = $subject;
		$mail->Body    = $message;

		if(!$mail->send()) {
			echo 'Message could not be sent.'.' Mailer Error: ' . $mail->ErrorInfo;
		} else {
			$selectedSiteStaff = $_POST['selectedSiteStaff'];
			$project_id = $_POST['project_id'];
			$site_staff_type = $_POST['siteStaffType'];
			$site_staff_arr = explode(',',$selectedSiteStaff);
			$count_addr = count($site_staff_arr);
			$num = 0;
			while($num < $count_addr){
				$site_staff_id = $site_staff_arr[$num];
				$num++;
				$this->induction_health_safety_m->save_induction_video_link_sent($project_id,$site_staff_id,$site_staff_type);
			}
			
			echo "Email Send Successfully";
		}
	}
	
	public function qrcode_blue_book(){
		$project_id = $_POST['project_id'];

		$proj_q = $this->projects_m->fetch_complete_project_details($project_id);
		foreach ($proj_q->result() as $row) {	
			$project_name = $row->project_name;

		}

		$document = '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><title></title>';
		
		$document .= '<style type="text/css">
        	*,html, body{margin:0; padding:0}
        </style>';

        $img="./img/blue_book_cover.jpg";
        $qr_img = 'docs/tempqrcode/'.$project_id.'/qrcode.png';
		$document .= '</head><body>';
		$document .= '<h1 style = "position: absolute; color: white; top: 250px; left: 180px">'.$project_id.'&nbsp;&nbsp;&nbsp;&nbsp;'.$project_name.'</h1>';
		$document .= '<img src = "'.$qr_img.'" style = "position: absolute !important; top: 890px; left: 100px">';
		$document .= '<img src = "'.$img.'" style = "top:0; left: 0; width: 100%; height: 1122px">';
		$document .= '</body></html>';


		$dompdf = new DOMPDF();
		$html = mb_convert_encoding($document, 'HTML-ENTITIES', 'UTF-8');

		$dompdf->set_paper('A4','portrait');
		$dompdf->load_html($document);
		$dompdf->render();

		$canvas = $dompdf->get_canvas();
		$date_gen = date("jS F, Y");
	
		$output = $dompdf->output();


		$dir = "docs/temp";
		$handle=opendir($dir);
		while (($file = readdir($handle))!==false) {
			@unlink($dir.'/'.$file);
		}
		closedir($handle);
		rmdir($dir);


		if(!is_dir('docs/temp')){
			mkdir('docs/temp',0755,TRUE);
		}

		write_file('docs/temp/blue_book_cover.pdf','');

		file_put_contents('docs/temp/blue_book_cover.pdf', $output);

		echo 'blue_book_cover.pdf';

	}

	public function fetch_project_induction_other_sitestaff(){
		$project_id = $_POST['project_id'];
		$query = $this->induction_health_safety_m->fetch_project_induction_other_sitestaff($project_id);
		echo json_encode($query->result());
	}

	public function insert_induction_project_other_site_staff(){
		$project_id = $_POST['project_id'];
		$induction_other_company_site_staff_id_arr = $_POST['checkedOCompanyList'];

		$arr_num = count($induction_other_company_site_staff_id_arr);
		$x = 0;
		while($x < $arr_num){
			$this->induction_health_safety_m->insert_induction_project_other_site_staff($project_id,$induction_other_company_site_staff_id_arr[$x]);
			$x++;
		}

		$query = $this->induction_health_safety_m->fetch_project_induction_other_sitestaff($project_id);
		echo json_encode($query->result());
	}

	public function remove_induction_project_other_site_staff(){
		$project_id = $_POST['project_id'];
		$induction_project_other_site_staff_id = $_POST['induction_project_other_site_staff_id'];
		$this->induction_health_safety_m->remove_induction_project_other_site_staff($induction_project_other_site_staff_id);

		$query = $this->induction_health_safety_m->fetch_project_induction_other_sitestaff($project_id);
		echo json_encode($query->result());
	}

	public function project_is_exempted_induction(){
		$query = $this->induction_health_safety_m->project_is_exempted_induction();
		echo json_encode($query->result());
	}

	public function fetch_induction_postcode_filters(){
		$query = $this->induction_health_safety_m->fetch_induction_postcode_filters();
		echo json_encode($query->result());
	}

	public function fetch_all_pa(){
		$query = $this->induction_health_safety_m->fetch_all_pa();
		echo json_encode($query->result());
	}
}