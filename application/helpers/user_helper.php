<?php

function admindata()
{
	$ci=& get_instance();
	$ci->load->database(); 
	
	$sql = "SELECT * 
	FROM admin"; 
	$query = $ci->db->query($sql);
	$row = $query->result_array();
	return $row;
}

function remouser($user_id)
{
	$ci=& get_instance();
	$ci->load->database(); 
	
	$sql = "SELECT * 
	FROM users WHERE id = ".$user_id; 
	$query = $ci->db->query($sql);
	$row = $query->result_array();
	return $row;
}
?>