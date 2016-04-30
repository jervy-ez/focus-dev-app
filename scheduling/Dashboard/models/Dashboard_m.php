<?php

class Functions_m extends CI_Model{
	
	public function test(){
		$query = $this->db->query("SELECT * FROM room");
		return $query;	
	}
	
}