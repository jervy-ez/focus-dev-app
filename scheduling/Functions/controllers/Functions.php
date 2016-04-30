<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* Author: Jervy Zaballa */

class Functions extends MX_Controller{
	
	function __construct(){
		parent::__construct();
		//$this->load->library('pbkdf2');
		$this->load->model('functions_m');		
		//$this->_check_user_signin();   // checks user is logged in

	//	$this->_is_logged_in(); // to be tested
 



	}		




	function index(){
		$data['main_content'] = 'main';	
		$data['test'] = 'Test Page';
	}


	function _check_user_signin($my_ip,$email){
		//$my_ip = $this->_getUserIP();

	//	echo $my_ip.'-';

		$data_arr_ip = $this->_fetch_user_ip($my_ip,$email);
		$my_data_ip = $data_arr_ip[0];


		$my_date_stamp = explode(' ', $my_data_ip->time_stamp);
		$my_login_status = explode('-', $my_data_ip->description);

		$date = new DateTime();
		//$date->modify("+6 hours");   // +6 hrs in local needs testing
		$user_date_stamp = explode(' ',$date->format('Y-m-d H:i:s'));

		$is_registrar = 0;

		$q_regs_check = $this->functions_m->check_user_is_regstrar($my_data_ip->user_id);

		//var_dump($q_regs_check->result());

		foreach ($q_regs_check->result() as $result){
			$mystring = strtolower($result->description);
			$findme   = 'registrar';
			$pos = strpos($mystring, $findme);

			// Note our use of ===.  Simply == would not work as expected
			// because the position of 'a' was the 0th (first) character.
			if ($pos === false) {
				//echo "The string '$findme' was not found in the string '$mystring'";
			} else {
				//echo "The string '$findme' was found in the string '$mystring'";
				//echo " and exists at position $pos";
				$is_registrar = 1;
			}
		}

		//echo $my_date_stamp[0]." == ".$user_date_stamp[0]." && ".$my_login_status[1]." == 'in' && $is_registrar == 1 <br />";

		if($my_date_stamp[0] == $user_date_stamp[0] && $my_login_status[1] == 'in' && $is_registrar == 1){

			//echo $my_date_stamp[1].'****'.$user_date_stamp[1];

			$my_time_stamp = explode(':', $my_date_stamp[1]);
			$user_time_stamp = explode(':', $user_date_stamp[1]);

//	 	echo '<br />'.$my_time_stamp[0].' -- '.$user_time_stamp[0];

//			if($my_time_stamp[0] == $user_time_stamp[0]){

				$diff = $user_time_stamp[1] - $my_time_stamp[1];

				//echo $user_time_stamp[1].'  ---'.$my_time_stamp[1];
/*
				if($diff > 10){
					$this->session->sess_destroy();
					echo '<script> window.location = "https://my.bicol-u.edu.ph/alpha/default/user/logout"; </script>';
//					echo "logout 3";
				}else{
//						echo "logged in";
*/

					$this->_user_data($my_data_ip->user_id);

					$link = base_url().'Scheduling';
					echo '<script> window.location = "'.$link.'"; </script>';
					//if(!$this->session->userdata('logged_in')){
						//$this->session->set_userdata('user_id',$my_data_ip->user_id); // it shoud be from session login
						
		// 	redirect(base_url().'signin');
				//	}else{


		 	//redirect(base_url().'welcome');

				//	}

				//}
/*
			}else{
				$this->session->sess_destroy();
				echo '<script> window.location = "https://my.bicol-u.edu.ph/alpha/default/user/logout"; </script>';
//				echo "logout 2";
			}
*/
		}else{
			$this->session->sess_destroy();
			echo '<script> window.location = "https://my.bicol-u.edu.ph/alpha/default/user/logout"; </script>';
//			echo "logout 1";
		}
	}



	function _fetch_user_ip($user_ip,$email){
		$q_ip_check = $this->functions_m->check_my_ip($user_ip,$email);
		$ip_arr = $q_ip_check->result();
		//$user_ip_data = $ip_arr[0];
		return $ip_arr;
	}

	function logout_user_data($id,$user_id){
		$description = "User $user_id Logged-out";
		$this->functions_m->logout_user($id,$description);

	}


	function _getUserIP(){
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'Unknown IP Address';

		return $ipaddress;
	}

	function _get_day_to_digit($day){
		$day = strtoupper($day);
		$days = array("TBA","SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT");
		$key = array_search($day, $days);
		return $key;
	}


	function _clear_apost(){
		foreach ($_POST as $key => $value) {
			$_POST[$key] = str_replace("'","&apos;",$value);
		}
	}

	function _user_data($user_id){
		// this where the session data will be fetched

		$q_user_data = $this->functions_m->get_user_data($user_id);
		$user_data_arr = $q_user_data->result();
		$user_data = $user_data_arr[0];

		//var_dump($user_data);

 
			$user = $user_data->first_name.' '.$user_data->last_name;
			$this->session->set_userdata('user_name',$user);
			$this->session->set_userdata('id',$user_id);
			$this->session->set_userdata('user_id',$user_id);
			$this->session->set_userdata('logged_in',1);      
		 



		//object(stdClass)#479 (9) { ["id"]=> int(1314) ["first_name"]=> string(5) "Jervy" ["last_name"]=> string(7) "Zaballa" ["email"]=> string(24) "jezaballa@bicol-u.edu.ph" ["password"]=> string(80) "pbkdf2(1000,20,sha512)$b064517cc0599758$e0a9fee0a634183bf62505879b4df9a05fa77660" ["registration_key"]=> string(0) "" ["reset_password_key"]=> string(0) "" ["registration_id"]=> string(0) "" ["signatory_id"]=> NULL }



	}

	function toggle_sidebar(){
		if($this->session->userdata('toggle_sidebar')){
			$this->session->unset_userdata('toggle_sidebar');
		}else{
			$this->session->set_userdata('toggle_sidebar', '1');
		}
	}

	function army_to_standard_time($army_time_str){

		if(strlen($army_time_str)==3){
			$army_time_str = '0'.$army_time_str;
		}

		$time_b = substr($army_time_str, 2);
		$time_a = substr($army_time_str, 0,-2);


		if($time_a > 12){
			$time_a = intval($time_a) - 12;
		}
		$time_to_convert = intval($time_a).':'.$time_b.':00';

		return $time_to_convert;





/*

		return date( 'g:i', strtotime( $time_to_convert ) );

*/

	}

	function get_time_record(){
		return date("Y-m-d h:i:s");
	}

	function _is_logged_in(){

		if(!$this->session->userdata('logged_in')){
		// 	redirect(base_url().'signin');
		}else{
		 	//redirect(base_url().'welcome');

		}

	}

	
}