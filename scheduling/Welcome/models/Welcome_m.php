<?php

class Welcome_m extends CI_Model{
	
	function test(){
		$query = $this->db->query("SELECT * FROM room");
		return $query;	
	}
}