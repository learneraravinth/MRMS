<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class response_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	//Get Amenities
	public function get_amenities(){
		$query  = 'SELECT id,amenities_name FROM amenities WHERE is_active=1';
		$rs 	= $this->db->query($query);
		return $rs->result();
	}

	public function admin_details(){
		$query  = 'SELECT * FROM admin';
		return  $rs 	= $this->db->query($query);
	}	
	
	public function get_users(){
		$query  = 'SELECT * FROM users WHERE is_active=1';
		return	$rs 	= $this->db->query($query);
	}
	
	public function spe_users($name_id){
		$query  = "SELECT * FROM users WHERE is_active=1 and name_id='".$name_id."'";
		return	$rs 	= $this->db->query($query);
	}
	
	public function get_room_det(){
		$query  = 'SELECT * FROM room_info WHERE is_active=1';
		return $rs 	= $this->db->query($query);
	}	
	
	public function booking_list($room_id){
		$today  	 = date('Y-m-d H:i:s');
		$today_date  = date('Y-m-d');
		$query  = "SELECT * FROM ".$room_id."_details WHERE start_date <= '".$today."'  and end_date  >= '".$today."'";
		$rs 	= $this->db->query($query);
		$modeldata['today'] = $rs;
		
		$query  = "SELECT * FROM ".$room_id."_details WHERE date(start_date)<= '".$today_date."'  and date(end_date)  >= '".$today_date."'";
		$rs 	= $this->db->query($query);
		$modeldata['booking_list'] = $rs;
		
		
		return $modeldata;
	}
	
	public function save_meeting($userdata){
		#echo '<pre>';print_r($userdata);exit;
		$this->db->set('booking_id',$userdata['room_id']);
		$this->db->set('room_name',$userdata['room_id']);
		$this->db->set('title',$userdata['title']);
		$this->db->set('agenda',$userdata['agenda']);
		$this->db->set('start_date',$userdata['start_titming']);
		$this->db->set('end_date',$userdata['end_titming']);
		$this->db->set('created_by',$userdata['created_by']);
		
		$user_list 	= $userdata['invites'];
		
		$this->db->set('invities',$user_list);
		$this->db->insert($userdata['room_id'].'_details');
		$id = $this->db->insert_id();
		if($id > 0)	
		{
			$modeldata['msg']= 'Successfully save!';
			$modeldata['flag']=1;
		}
		else
		{		
			$modeldata['msg']= 'Error on insert';
			$modeldata['flag']=0;
		}	
		return $modeldata;
	}
}