<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Bulletin_board extends MY_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('session');

		$this->load->model('bulletin_board_m');
		$this->load->module('users');

		if(!$this->users->_is_logged_in() ): 		
			redirect('', 'refresh');
		endif;
	}

	public function index(){
		//$this->users->_check_user_access('invoice',1);

		$data['main_content'] = 'bulletin_board_home';
		$data['screen'] = 'Bulletin Board';
		$this->load->view('page', $data);
	}

	public function clear_apost(){
		foreach ($_POST as $key => $value) {
			$_POST[$key] = str_replace("'","&apos;",$value);
		}
	}

	public function post(){
		date_default_timezone_set("Australia/Perth");
		$datestring = "%l, %d%S, %F %Y %g:%i:%s %A";
		$time = time();
		$date_posted = mdate($datestring, $time);

		$this->clear_apost();

		$title = $this->input->post('title');
		$my_post = $this->input->post('my_post');
		$user_id = $this->input->post('token');

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

		$this->bulletin_board_m->insert_post($title, $my_post, $user_id, $date_posted, $expiry_date);

		$this->session->set_flashdata('success_post', 'Post is now submitted and be expired at. '.$expiry_date);
		$this->session->set_flashdata('title','');
		$this->session->set_flashdata('my_post','');

		redirect('/bulletin_board', 'refresh');
	}

	public function list_post(){
		$list_post_q = $this->bulletin_board_m->list_post();

		foreach ($list_post_q->result() as $post) {

			echo '<div class="post_item row"><div class="col-sm-2 col-md-1"><div class="user_avatar pad-5">';
			echo '<img src="'.base_url().'uploads/users/'.$post->user_profile_photo.'" class="img-circle img-responsive center-block"></div></div><div class="col-sm-10 col-md-11"><div class="post_content pad-left-5">';
			echo '<p class="h4"><strong>'.$post->title.'</strong></p><p>';

			//$post->post_details

			$word_count = strlen($post->post_details);

			if($word_count > 350){
				echo substr($post->post_details, 0,350).'<a href="#" class="read_more"><br /><strong>Read More</strong></a><span class="remain" style="display:none;">'.substr($post->post_details, 350).'</span><a href="#" class="read_less" style="display:none;"><br /><strong>Read Less</strong></a>';
			}else{
				echo $post->post_details;
			}

			echo '</p><small><i class="fa fa-calendar"></i> '.$post->date_posted.'</small></div></div></div><div class="clearfix"><hr /></div>';
		}
	}


	public function list_latest_post(){
		date_default_timezone_set("Australia/Perth");

		$curr_date = date('d/m/Y');
		$curr_date_tmsmp = strtotime(str_replace('/', '-', $curr_date));

		$list_post_q = $this->bulletin_board_m->list_latest_post();
		$has_post = 0;

		echo '<div id="board" class="modal fade" tabindex="-1" data-width="760" style="display: none; overflow: hidden;"><div class="modal-dialog"><div class="modal-content">
		<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title">Bulletin Board</h4></div><div class="modal-body"><div class="row"><div class="col-md-12">';


		foreach ($list_post_q->result() as $post) {
			if($curr_date_tmsmp < $post->expiry_date_mod){
				echo '<h4>'.$post->title.'</h4><p>'.$post->post_details.'</p><p><hr /></p>';
				$has_post ++;
			}
		}
		echo '</div></div></div></div></div></div>';
		if($has_post > 0){
			if($this->session->userdata('show_latest_post') != 2){
				$this->session->set_userdata('show_latest_post', '1');
			}
		}
	}


}