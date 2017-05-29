<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class meeting_model extends CI_Model {
	public function __construct(){
		parent::__construct();
		date_default_timezone_set('Asia/Phnom_Penh');
	}
	
	public function booking_history($userdata){
		$room_id = $userdata['room_searchs'];
		$today 	 = date('Y-m-d',strtotime($userdata['meeting_date']));
		$query  = "SELECT rm.*,rf.room_name as name_of_room FROM ".$room_id."_details as rm left join  room_info  as rf on rf.id = rm.room_name WHERE date(start_date) = '".$today."'";
		return $rs 	= $this->db->query($query);
	}
	
	public function list_group(){
		$query  = 'SELECT * FROM group_info WHERE is_active=1';
		$rs 	= $this->db->query($query);
		return $rs->result();
	}
	
	//Get Amenities
	public function get_amenities(){
		$query  = 'SELECT id,amenities_name FROM amenities WHERE is_active=1';
		$rs 	= $this->db->query($query);
		return $rs->result();
	}	
	//Get Users
	public function get_users(){
		$query  = 'SELECT * FROM users WHERE is_active=1';
		$rs 	= $this->db->query($query);
		return $rs->result();
	}
	
	public function view_meeting($userdata){
		$query  = "SELECT rd.*,ri.room_name as rname ,ri.seating_capacity FROM ".$userdata['room_id']."_details as rd LEFT JOIN  room_info as ri on ri.id=rd.booking_id  WHERE  rd.title ='".$userdata['title']."'"."  and rd.start_date='".$userdata['start_time']."'"."  and rd.end_date='".$userdata['end_time']."'";
		$rs 	= $this->db->query($query);
		return $rs->result_array();
	}
	
	public function get_room_det($room_id=''){
		if(!empty($room_id)){
			$querys = ' and id='.$room_id; 
		}else{
			$querys = '';
		}
		$query  = 'SELECT * FROM room_info WHERE is_active=1'.$querys;
		$rs 	= $this->db->query($query);
		return $rs->result();
	}
	public function get_overall($ids){
		$query  = 'SELECT * FROM users WHERE is_active=1 and id IN ('.$ids.')';
		$rs 	= $this->db->query($query);
		return $rs->result();
	}	
	
	public function end_meeting($room_id){
		$today  	 = date('Y-m-d H:i:s');
		$query  = "SELECT * FROM ".$room_id."_details WHERE start_date <= '".$today."'  and end_date  >= '".$today."'";
		$rs 	= $this->db->query($query);
		$end_id = $rs->row()->id;
		$this->db->set('end_date',$today);
		$this->db->where('id',$end_id);
		$this->db->update($room_id.'_details');
		$modeldata['msg']= 'Successfully save!';
		$modeldata['flag']=1;
		return $modeldata;	
	}
	
	public function extend_meeting($userdata){
		$room_id 	= $userdata['room_id'];
		$startdate  = $userdata['startdate'];
		$enddate 	= $userdata['enddate'];
		$query 		= 'SELECT id FROM '.$room_id.'_details WHERE start_date='.$startdate.' AND  end_date = '.$enddate;
	} 
	
	public function save_amenities($userdata){
		$query  = 'select amenities_name from amenities where amenities_name="'.$userdata['amenities_name'].'"';
		$rs 	= $this->db->query($query);
		$cnt 	=  $rs->num_rows();
		if($cnt >0){
				$modeldata['msg']= 'Amenities already present!';
				$modeldata['flag']=3;
		}else{
			$this->db->set('amenities_name',$userdata['amenities_name']);
			$this->db->insert('amenities');
			$id = $this->db->insert_id();
			if($id > 0)	
			{
				$modeldata['msg']= 'Successfully save!';
				$modeldata['flag']=1;
				$modeldata['id']=$id;
			}
			else
			{		
				$modeldata['msg']= 'Error on insert';
				$modeldata['flag']=0;
			}	
		}
		return $modeldata;
	}
	public function save_meeting_details($userdata){
		$this->db->set('room_name',$userdata['room_name']);
		$this->db->set('seating_capacity',$userdata['seat_capacity']);
		$this->db->set('description',$userdata['room_description']);
		if(!empty($userdata['room_amenity'])){
			$room_amenity =  implode(",",$userdata['room_amenity']);
			$this->db->set('room_amenity',$room_amenity);
		}
		$this->db->set('room_photo',$userdata['meeting_photo']);
		if(!empty($userdata['room_id'])){
			$this->db->where('id',$userdata['room_id']);
			$this->db->update('room_info');
			$modeldata['msg']= 'Successfully Updated!';
			$modeldata['flag']=1;
		}else{
			$this->db->insert('room_info');
			$id = $this->db->insert_id();
			$query1 = "CREATE TABLE IF NOT EXISTS ".$id."_details (`id` INTEGER(11) NOT NULL AUTO_INCREMENT,`booking_id` varchar(255) NOT NULL DEFAULT '',  `room_name` varchar(255) NOT NULL DEFAULT '',  `title` varchar(255) NOT NULL DEFAULT '',  `agenda` varchar(255) NOT NULL DEFAULT '',`invities` varchar(255) NOT NULL DEFAULT '',`group_data` varchar(255) NOT NULL DEFAULT '',`send_by` varchar(255) NOT NULL DEFAULT '',`start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',`end_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',`created_by` INT(11) NOT NULL DEFAULT '0',`is_active` tinyint(12) NOT NULL DEFAULT '1',`created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,PRIMARY KEY (`id`)) ENGINE=InnoDB;";
			$this->db->query($query1);
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
		}
		return $modeldata;
	}
	
	public function delete_meeting($room_id){
		$detete_query = 'DROP TABLE '.$room_id.'_details';
		$this->db->query($detete_query);
		
		$this->db->where('id', $room_id);
		$this->db->delete('room_info'); 
		return true;
	}
	
	public function edit_meeting($room_id){
		$query  = 'SELECT * FROM room_info WHERE is_active=1 and id ='.$room_id;
		$rs 	= $this->db->query($query);
		return $rs->result();
	}
	
	public function save_meeting($userdata,$user=''){
		//echo '<pre>';print_r($userdata);exit;
		$this->db->set('booking_id',$userdata['room_id']);
		$this->db->set('room_name',$userdata['room_names']);
		$this->db->set('title',$userdata['meeting_title']);
		$this->db->set('send_by',$userdata['send_by']);
		
		$this->db->set('agenda',$userdata['meeting_agenda']);
		$this->db->set('start_date',$userdata['from_date']);
		$this->db->set('end_date',$userdata['to_date']);
		
		if($userdata['send_by'] == 'group'){
			$rm =  implode(',',$userdata['room_invities']);
			$this->db->set('group_data',$rm);
			$user_list = '';
			if(!empty($userdata['room_invities'])){	
				$room_invities = $userdata['room_invities'];
				$dataarr = '';
				foreach($room_invities as $val){
					$query  = 'SELECT * FROM group_info WHERE id='.$val;
					$rs 	= $this->db->query($query);
					$dataarr .= ','.$rs->row()->grp_members;
				}
				$user_str 		= ltrim($dataarr,',');
				$room_invitie   = explode(',',$user_str);
				$name_pr = array_unique($room_invitie);
				$user_list 	= implode(',',$name_pr);
			}
		}else{
			$user_list = '';
			if(!empty($userdata['room_invities'])){	
				$user_list 	= implode(',',$userdata['room_invities']);
			}
		}
		
		if(!empty($user)){
			$session_user = $this->session->userdata('loginuser');
			$this->db->set('created_by',$session_user['user_id']);
		}
		
		$this->db->set('invities',$user_list);
		//echo $userdata['room_id'];exit;
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
	
	public function socket_chenda($data){
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_PORT => "5000",
			CURLOPT_URL => "http://192.168.17.7:5000/broadcast",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode($data),
			CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache",
			'Content-Type: application/json',
			"postman-token: 44c98fa9-2636-5159-f006-96ab36bc14c7"
		  ),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		/* if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		} */
	}

	public function booking_details($room_id){
		$query  = 'SELECT * FROM '.$room_id.'_details';
		return $rs 	= $this->db->query($query);
	}
	
	public function today_booking($room_id){
		$today = date('Y-m-d H:i:s');
		$query  = "SELECT * FROM ".$room_id."_details WHERE start_date <= '".$today."'  and end_date  >= '".$today."'";
		return $rs 	= $this->db->query($query);
	}
	
	public function booking_list($room_id){
		$today  	 = date('Y-m-d H:i:s');
		$today_date  = date('Y-m-d');
		$query  = "SELECT * FROM ".$room_id."_details WHERE start_date <= '".$today."'  and end_date  >= '".$today."'";
		$rs 	= $this->db->query($query);
		if($rs->num_rows() > 0){
			$query  = "SELECT * FROM ".$room_id."_details WHERE date(start_date)<= '".$today_date."'  and date(end_date)  >= '".$today_date."'";
			$rs 	= $this->db->query($query);
		}
		return $rs;
	}
	
	public function user_profile($user_id){
		$query  = 'SELECT * FROM users WHERE is_active=1 and id ='.$user_id;
		$rs 	= $this->db->query($query);
		return $rs->result();
	}
	
	public function save_users($userdata){
		$this->db->set('user_name',$userdata['user_name']);
		$this->db->set('short_name',$userdata['short_name']);
		$this->db->set('job_title',$userdata['job_title']);
		$this->db->set('face_bookid',$userdata['facebook_id']);
		$this->db->set('user_photo',$userdata['user_photo_name']);
		$this->db->set('mobile_no',$userdata['mobile_no']);
		$this->db->set('description',$userdata['user_description']);
		if(!empty($userdata['user_id'])){
			$this->db->set('name_id',$userdata['user_name'].'_'.$userdata['user_id']);
			$this->db->where('id',$userdata['user_id']);
			$this->db->update('users');
			$modeldata['msg']= 'Successfully Updated!';
			$modeldata['flag']=1;
		}else{
			$this->db->insert('users');
			$id = $this->db->insert_id();
			$this->db->set('name_id',$userdata['user_name'].'_'.$id);
			$this->db->where('id',$id);
			$this->db->update('users');
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
		}
		return $modeldata;
	}
}