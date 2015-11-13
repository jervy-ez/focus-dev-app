<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bulletin_board extends MY_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->helper('cookie');

		$this->load->model('bulletin_board_m');
		$this->load->module('users');

		if(!$this->users->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;
	}

	public function index(){
		//$this->_check_user_access('bulletin_board',1);
		//$this->users->_check_user_access('invoice',1);

		$data['main_content'] = 'bulletin_board_home';
		$data['screen'] = 'Bulletin Board';
		$this->load->view('page', $data);
	}

	public function clear_apost(){
		foreach ($_POST as $key => $value){
			$_POST[$key] = str_replace("'","&apos;",$value);
		}
	}

	public function post(){
		if($this->session->userdata('is_admin') != 1 ){
			if( $this->session->userdata('bulletin_board') < 2){
				redirect('/bulletin_board', 'refresh');
			}
		}

		$is_urget = 0;

		date_default_timezone_set("Australia/Perth");
		$datestring = "%l, %d%S, %F %Y %g:%i:%s %A";
		$time = time();
		$date_posted = mdate($datestring, $time);

		$this->clear_apost();

		$title = $this->input->post('title');
		$my_post = $this->input->post('my_post');
		$user_id = $this->input->post('token');

		$my_post = trim($my_post);

		$this->session->set_flashdata('title', $title);
		$this->session->set_flashdata('my_post', $my_post);

		if($title == '' || $my_post == ''){
			$this->session->set_flashdata('error', 'Incomplete form, please check all fields.');
			redirect('/bulletin_board', 'refresh');
		}

		$date_arr = explode(',',$date_posted);

		if($date_arr['0'] == 'Saturday'){
			$expiry_date = date('d/m/Y', strtotime("+3 days"));
		}elseif($date_arr['0'] == 'Friday'){
			$expiry_date = date('d/m/Y', strtotime("+4 days"));
		}else{
			$expiry_date = date('d/m/Y', strtotime("+2 day"));
		}

		if(isset($_POST['set_urgent'])){
			$is_urget = 1;
			$expiry_date = date('d/m/Y', strtotime("+1 day"));
		}

		$this->bulletin_board_m->insert_post($title, $my_post, $user_id, $date_posted, $expiry_date, $is_urget);

		$this->session->set_flashdata('success_post', 'Post is now submitted and be expired at. '.$expiry_date);
		$this->session->set_flashdata('title','');
		$this->session->set_flashdata('my_post','');

		redirect('/bulletin_board', 'refresh');

	}

	public function update_post(){
		$this->clear_apost();
		date_default_timezone_set("Australia/Perth");

		$title = $this->input->post('post_title');
		$post_details = $this->input->post('post_content');
		$post_id = $this->input->post('post_id');
		$expiry_date = $this->input->post('expiry_date');
		$bulletin_board_id = $this->input->post('post_id');

		$is_urgent = 0;


		if(isset($_POST['set_urgent_edit'])){
			$is_urgent = 1;
			$expiry_date = date('d/m/Y', strtotime("+1 day"));
		}

		if($title == '' || $post_details == ''){
			$this->session->set_flashdata('error', 'Incomplete form, please check all fields.');
			redirect('/bulletin_board', 'refresh');
		}else{
			$this->bulletin_board_m->update_post($title,$post_details,$expiry_date,$is_urgent,$bulletin_board_id);
			redirect('/bulletin_board', 'refresh');
		}

	}

	public function list_post(){
		$list_post_q = $this->bulletin_board_m->list_post();

		foreach ($list_post_q->result() as $post) {

			echo '<div class="post_item row pad-10"><div class="col-sm-2 col-md-1"><div class="user_avatar pad-5">';
			echo '<img src="'.base_url().'uploads/users/'.$post->user_profile_photo.'" style="border-bottom: 1px solid #000;" class="img-circle img-responsive center-block"></div></div><div class="col-sm-10 col-md-11"><div class="post_content pad-left-5">';

			if($this->session->userdata('is_admin') == 1):	
				echo '<span class="pull-right">
					<a href="#" style="display:none;" class="delete_post badge pull-right pad-top-5 pad-bottom-5" id="'.$post->bulletin_board_id.'"><i class="fa fa-trash"></i></a>
					<a href="#" style="display:none;" class="edit_post badge pull-right m-right-5 pad-top-5 pad-bottom-5" id="'.$post->bulletin_board_id.'*'.$post->expiry_date.'*'.$post->is_urgent.'" data-target="#edit_post" data-toggle="modal" ><i class="fa fa-pencil"></i></a></span>';
			endif;

			echo '<p class="h4 post_title"><strong>'.($post->is_urgent == 1 ? '<i class="fa fa-exclamation-triangle"></i>' : '').' '.$post->title.'</strong></p><p>';

			//$post->post_details

			$word_count = strlen($post->post_details);

			if($word_count > 450){
				echo substr($post->post_details, 0,450).'<a href="#" class="read_more"><br /><strong>Read More</strong></a><span class="remain" style="display:none;">'.substr($post->post_details, 450).'</span><a href="#" class="read_less" style="display:none;"><br /><strong>Read Less</strong></a>';
			}else{
				echo $post->post_details;
			}

			echo '</p><span class="hide hidden_post_details">'.$post->post_details.'</span><small><i class="fa fa-calendar"></i> '.$post->date_posted.'</small></div></div></div>';
		}
	}

	public function remove_post(){
		if(isset($_POST['ajax_var'])){
			$post_id = $_POST['ajax_var'];			
			$this->bulletin_board_m->delete_post($post_id);
		}else{
			redirect('/bulletin_board', 'refresh');
		}
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