<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dev_notes extends MY_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->helper('cookie');

		$this->load->model('Dev_notes_m');
		$this->load->module('Admin');
		$this->load->module('Users');
		$this->load->model('Users_m');


 
	}

	public function index(){
		if($this->session->is_admin != 'on'){
			redirect();
		}

		if(!$this->admin->_is_logged_in() ): 		
	  		redirect('signin', 'refresh');
		endif;

		$data['main_content'] = 'dev_notes_home';
		$data['added_scripts'] = 'js_file';
		$data['page_title'] = 'Development Notes'; 

		$q_list_programmes = $this->Dev_notes_m->list_programmes();
		$data['programmers'] = $q_list_programmes->result();
 
		$list_q = $this->Dev_notes_m->fetch_sections();
		$data['sections'] = $list_q->result();

		$this->load->view('page', $data);
	}



	public function view_post(){
		$post_id = $this->uri->segment(3);
		$data['main_content'] = 'dev_notes_view_detail';
		$data['screen'] = 'Development Notes';


		$q_list_programmes = $this->Dev_notes_m->list_programmes();
		$data['programmers'] = $q_list_programmes->result();

		$q_fetched = $this->Dev_notes_m->view_post_detail($post_id);
		$view_post_detail_arr = $q_fetched->result_array();
		$data['post_detail'] = array_shift($view_post_detail_arr);

		$data['page_title'] = 'Development Notes';
		$data['added_scripts'] = 'js_file';

		$list_q = $this->Dev_notes_m->fetch_sections();
		$data['sections'] = $list_q->result();

		$this->load->view('page', $data);
	}



	public function clear_apost(){
		foreach ($_POST as $key => $value){
			$_POST[$key] = str_replace("'","&apos;",$value);
		}
	}

	public function add_section(){

		//var_dump($_POST);
		$this->admin->clear_apost();

		$dn_section = $this->input->post('dn_n_section');

		$this->Dev_notes_m->insert_new_section($dn_section );
		redirect('/Dev_notes', 'refresh');

	}


	public function post_comment(){

		$dn_post_date = date("d/m/Y");

		$dn_tread_id = $this->input->post('dn_tread_id');
		$dn_post_details = $this->input->post('comments');

		//echo "$dn_post_date,$dn_user_post,$dn_tread_id,$dn_post_details";
		$q_fetched = $this->dev_notes_m->view_post_detail($dn_tread_id);
		$post_detail = array_shift($q_fetched->result_array());


		date_default_timezone_set("Australia/Perth");
		$user_id = $this->session->userdata('user_id');
		$date = date("d/m/Y");
		$time = date("H:i:s");
		$type = "Comment";
		$actions = "New comments for: ".$post_detail['dn_title'];
		$this->user_model->insert_user_log($user_id,$date,$time,$actions,$dn_tread_id,$type);

		$this->dev_notes_m->post_comment($dn_post_date,$user_id,$dn_tread_id,$dn_post_details);
		redirect('/dev_notes/view_post/'.$dn_tread_id, 'refresh');

	}


	public function post(){


		//var_dump($_POST);
		$this->clear_apost();

/*
		date_default_timezone_set("Australia/Perth");
		$datestring = "%l, %d%S, %F %Y %g:%i:%s %A";
		$time = time();
		$date_posted = mdate($datestring, $time);	
*/

		$dn_user_post = $this->session->userdata('user_id');
		$dn_title = $this->input->post('notes_title');
		$dn_post_details = $this->input->post('comments');
		$dn_category = $this->input->post('dn_category');
		$dn_date_posted = date('d/m/Y');
		$dn_date_commence = $this->input->post('date_stamp');
		$dn_prgm_user_id = $this->input->post('dn_assnmt');
		$dn_section = $this->input->post('dn_section');
		$dn_bugs = $this->input->post('dn_bugs');


/*
		date_default_timezone_set("Australia/Perth");
		$user_id = $this->session->userdata('user_id');
		$date = date("d/m/Y");
		$time = date("H:i:s");
		$type = "New Notes";
		$actions = "Insert new note is added: ".$dn_title;
*/

		$post_id = $this->Dev_notes_m->insert_post($dn_user_post,$dn_title, $dn_post_details, $dn_category, $dn_date_posted ,  $dn_date_commence, $dn_prgm_user_id, $dn_section, $dn_bugs );

//		$this->user_model->insert_user_log($user_id,$date,$time,$actions,$post_id,$type);
		

		redirect('/Dev_notes', 'refresh');



	}

	public function update_section(){
		$this->clear_apost();
		$ajax_var = $this->input->post('ajax_var');
		$update_data = explode('|', $ajax_var);
		$this->dev_notes_m->update_section($update_data[1],$update_data[0]);
	}

	public function update_post(){ 


		$dn_title = $this->input->post('notes_title');
		$dn_category = $this->input->post('dn_category');
		$dn_id = $this->input->post('post_id');
		$dn_prgm_user_id = $this->input->post('dn_assnmt');
		$dn_date_commence = $this->input->post('date_stamp');
		$dn_post_details = $this->input->post('comments');
		$dn_date_complete = $this->input->post('dn_date_complete'); 
		$dn_section = $this->input->post('dn_section'); 
/*
		if($dn_date_complete != ''){
			date_default_timezone_set("Australia/Perth");
			$user_id = $this->session->userdata('user_id');
			$date = date("d/m/Y");
			$time = date("H:i:s");
			$type = "Completed";
			$actions = "Note is been completed: ".$dn_title;
			$this->user_model->insert_user_log($user_id,$date,$time,$actions,$dn_id,$type);
		}
*/
		$this->Dev_notes_m->update_post($dn_title,$dn_post_details,$dn_category,$dn_date_commence,$dn_date_complete,$dn_prgm_user_id,$dn_id,$dn_section);



		redirect('/Dev_notes/view_post/'.$dn_id, 'refresh');

	}


	public function list_post_comments($post_id){


		$list_post_q = $this->dev_notes_m->list_comments($post_id);
 

		if($list_post_q->num_rows >= 1){

			foreach ($list_post_q->result() as $post) {

				$fetch_user = $this->user_model->fetch_user($post->dn_post_user_id);
				$user_details = array_shift($fetch_user->result_array());
/*
				$pm->user_first_name
$pm->user_profile_photo
*/
				echo '<div class="clearfix m-bottom-10 post_container">';
				echo '<div class="pull-left m-right-10"  style="height: 50px; width:50px; border-radius:10px; overflow:hidden; border: 1px solid #999999;"><img class="user_avatar img-responsive img-rounded" src="'.base_url().'/uploads/users/'.$user_details['user_profile_photo'].'"" /></div>';
				echo '<div id="" class="post_content" style="margin-left:60px; padding: 5px;  margin-bottom:10px;  background: #eee;    border-radius: 10px;    border: 1px solid #e2e2e2; ">'.nl2br($post->dn_post_details).'</div><p class="show_text pointer" style="font-weight:bold; display:none; margin-left:60px;">Show More</p></div>';
			}

		}else{
			echo '<p style="padding: 10px;    background: #eee;    border-radius: 6px;    border: 1px solid #e2e2e2;">No Posts Yet.</p>';
		}
		
	}

	public function list_post($is_bugs = ''){
		$list_post_q = $this->Dev_notes_m->list_post($is_bugs);

		$cat_sign = ''; 
		$sign = '';

		foreach ($list_post_q->result() as $post) {

			if($post->dn_date_complete > 0){
				$status = 'Completed';
			}else{
				$status = 'Outstanding';
			}

			if($post->dn_prgm_user_id > 0){ 

				$fetch_user = $this->Users_m->get_user_details($post->dn_prgm_user_id);
				$fetch_user_arr = $fetch_user->result_array();

				$user_details = array_shift($fetch_user_arr);

				$programmer_name = ($post->dn_date_commence != '' && $status == 'Outstanding' ? '<strong>' : '');
					$programmer_name .= $user_details['first_name'];
				$programmer_name .= ($post->dn_date_commence != '' && $status == 'Outstanding' ? '</strong>' : '');

			}else{
				$programmer_name = 'Un-Assigned';
			}


			switch ($post->dn_category) {
				case 'Urgent':
				$sign = '<i class="fa fa-exclamation-triangle" aria-hidden="true" style="color: red;"></i> ';
				$cat_sign = '<span Style="color: red; font-weight:bold">'.$post->dn_category.'</span>';
				break;

				case 'Important':
				$sign = '';
				$cat_sign = '<span Style="color: orange; font-weight:bold">'.$post->dn_category.'</span>';
				break;

				case 'When Time Permits':
				$sign = '';
				$cat_sign = '<span Style="color: #009688; font-weight:bold">'.$post->dn_category.'</span>';
				break;

				case 'Maybe':
				$sign = '';
				$cat_sign = '<span Style="color: #3f51b5; font-weight:bold">'.$post->dn_category.'</span>';
				break;
			}

			$is_ongoing = '';

			if($post->dn_date_commence != '' && $status == 'Outstanding'){
				$is_ongoing = '<span style="color:green;" ><i class="fa fa-cog"></i></span>';
			}else{
				$is_ongoing = '';
			}

			echo '<tr><td style="display:none;">'.$post->priority_sort.'</td><td class=""><a href="'.base_url().'Dev_notes/view_post/'.$post->dn_id.'">'.$is_ongoing.' '.$sign.' ';
			
			echo ($post->dn_date_commence != '' ? '<span style="font-weight:bold;" >' : '');
			echo $post->dn_title;
			echo ($post->dn_date_commence != '' ? '</span>' : '');

			echo '</a></td><td>'.$cat_sign.'</td><td class="">'.$status.'</td><td class="">'.$post->dn_date_posted.'</td><td>'.$programmer_name.'</td><td>'.$post->dn_section_label.'</td><td>'.$post->unix_posted.'</td></tr>';
		}
	}

	public function delete_section(){
		$section_id = $this->uri->segment(3);
		$this->dev_notes_m->delete_section($section_id);
		redirect('/dev_notes', 'refresh');
	}

	public function delete_post(){
		$post_id = $this->uri->segment(3);
		$this->dev_notes_m->delete_post($post_id);
		redirect('/dev_notes', 'refresh');
	}


	public function list_latest_post(){

		date_default_timezone_set("Australia/Perth");

		$curr_date = date('d/m/Y');
		$curr_date_tmsmp = strtotime(str_replace('/', '-', $curr_date));

		$limit_date = date('d/m/Y', strtotime("+1 day"));
		$limit_date_tmsmp = strtotime(str_replace('/', '-', $limit_date));

		$list_post_q = $this->bulletin_board_m->list_latest_post();
		$has_post = 0;
		$post_ids = '';
		$has_urgent_post = 0;
		$set_flash = 0;
		$show_post = 0;
		$is_show_counter_bb = 0;

		$user_id = $this->session->userdata('user_id');

		//delete_cookie('show_post');
		//delete_cookie('is_read_post');

		foreach ($list_post_q->result() as $post) {
			if($curr_date_tmsmp <= $post->expiry_date_mod && $limit_date_tmsmp > $post->expiry_date_mod){
				$has_post ++;
				$post_ids .= $post->bulletin_board_id.',';
			}
		}

		$post_ids = rtrim($post_ids, ',');
		$post_ids_arr = explode(',', $post_ids);
		asort($post_ids_arr);
		$post_ids = implode(',', $post_ids_arr);
		$this->session->set_userdata('is_read_post_list',$post_ids);
		$show_post = 0;

		if($has_post > 0){

			echo '<div id="board" class="modal fade" tabindex="-1" data-width="760" style="display: none; overflow: hidden;"><div class="modal-dialog"><div class="modal-content">
			<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title">Bulletin Board</h4></div><div class="modal-body"><div class="row"><div class="col-md-12">';

			foreach ($list_post_q->result() as $post) {
				if($curr_date_tmsmp <= $post->expiry_date_mod && $limit_date_tmsmp > $post->expiry_date_mod){

					$viewed = explode(',',$post->seen_by_ids);

					if(!in_array($user_id,$viewed)){
						$show_post = 1; // not urgent

						array_push($viewed, $user_id);
						$seen_ids = implode(',', $viewed);
						$this->bulletin_board_m->update_seen_post($post->bulletin_board_id,$seen_ids);
						echo '<h4>'.($post->is_urgent == 1 ? '<i class="fa fa-exclamation-triangle"></i>' : '').' '.$post->title.'</h4><p>'.$post->post_details.'<br /><small class="block m-top-5"><i class="fa fa-user"></i> '.$post->user_first_name.' '.$post->user_last_name.' &nbsp; &nbsp; &nbsp; &nbsp; <i class="fa fa-calendar"></i> '.$post->date_posted.'</small></p><p><hr /></p>';
						$this->session->set_userdata('is_show_test','1');
						$is_show_counter_bb++;
					}
				}
			}
			echo '</div></div></div></div></div></div>';
		}else{
			$this->session->set_userdata('is_show_test','0');
		}


		$this->session->set_userdata('is_show_counter_bb',$is_show_counter_bb);

	}
} 