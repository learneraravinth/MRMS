<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_user extends CI_Controller {
    function __construct() {
        parent::__construct();
		$this->load->model('meeting_model'); 
		$this->load->library('session');                             	 	 	
		$session_user = $this->session->userdata('loginuser');
		if(!isset($session_user['user_id']) ){
			redirect('/loginuser');
		}
    }
	
	public function index(){
		$ctrldata['room_details']  = $this->meeting_model->get_room_det(); 
		$ctrldata['users_details'] = $this->meeting_model->get_users(); 
		$ctrldata['group_details'] = $this->meeting_model->list_group(); 
		$this->load->view('dashboard',$ctrldata);
	}
	
	public function load_calendar(){
		$room_id 		= $this->input->post('room_id');
		$day_booking  	= $this->meeting_model->today_booking($room_id); 
		$room_details   = $this->meeting_model->booking_details($room_id); 
		$today_rslt 	= $day_booking->result_array();
		if($day_booking->num_rows() > 0){
			$ctrldata['status'] = 'Booked';
		}else{
			$ctrldata['status'] = 'Available';
		}
		$data_rslt 	    = $room_details->result_array();
		$event_array    = array();
        foreach($data_rslt as $record ){
            $event_array[] = array(
                'title' => $record['title'],
                'start' => $record['start_date'],
                'end' => $record['end_date']
            );
        }
		$ctrldata['event_array'] = $event_array;
		echo json_encode($ctrldata);
	}
	
	public function save_meeting(){
		$data = $this->input->post();
		#echo '<pre>';print_r($data);exit;
		$room_id  		= $this->input->post('room_id');
		$room_invities  = $this->input->post('room_invities');
		$user_list 		= implode(',',$room_invities);
		$user_data 		= $this->meeting_model->get_overall($user_list); 
		foreach ($user_data as $data){
			$user_arr[] = $data->face_bookid;
		}
		$data = $this->input->post();
		$room_det 			= $this->meeting_model->get_room_det($room_id); 
		//echo '<pre>';print_r($room_det);exit;
		$ctrldata 			= $this->meeting_model->save_meeting($data,'user'); 
 		$room_name 			= $room_det[0]->room_name;
 		$strtdt 			= strtotime($data['from_date']); 
		$start_date  		= date('Y-m-d h:i:s a',$strtdt);
		$data['from_date']  = $start_date;
		$data['room_name']  = $room_name;
		
		$enddt 			 = strtotime($data['to_date']); 
		$end_date   	 = date('Y-m-d h:i:s a',$enddt);
		$data['to_date'] = $end_date;
		$this->facebook_chenda($user_arr,$data);
		echo json_encode($ctrldata);
	}
	
	public function facebook_chenda($data_arry,$all_data){
		//echo '<pre>';print_r($all_data);exit;
		//$data = array("users"=>array('remo.castro.940','samaiyan.cse'));
		$data = array("users"=>$data_arry,'all_data'=>$all_data);
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_PORT => "5000",
		CURLOPT_URL => "http://127.0.0.1:5000/sent_to_multi_user",
		CURLOPT_RETURNTRANSFER  => true,
		CURLOPT_ENCODING 		=> "",
		CURLOPT_MAXREDIRS 		=> 10,
		CURLOPT_TIMEOUT 		=> 30,
		CURLOPT_HTTP_VERSION 	=> CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST 	=> "POST",
		CURLOPT_POSTFIELDS 		=> json_encode($data),
		CURLOPT_HTTPHEADER	 	=> array(
			"cache-control: no-cache",
			'Content-Type: application/json',
			"postman-token: 44c98fa9-2636-5159-f006-96ab36bc14c7"
		),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		 if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		}
	}
	
	public function view_meeting(){
		$data = $this->input->post();
		$ctrldata['data'] 	= $this->meeting_model->view_meeting($data);
		$users_details		= $this->meeting_model->get_users();	
		$ctrldata['invities']	 = '';	
		if(!empty($ctrldata['data'][0]['invities'])){
			$invities = $ctrldata['data'][0]['invities'];
			$user_name =array();
			foreach($users_details as $user){
				$grp_members = explode(',',$invities);
				if(in_array($user->id,$grp_members)){
					$user_name[] = $user->user_name;
				}
			}
			$username = implode(',',$user_name);
			$ctrldata['invities'] 	= $username;
		}
		
 		echo json_encode($ctrldata);
		
	}
	
	public function user_profile($user_id){
		$ctrldata['user_data'] = $this->meeting_model->user_profile($user_id); 
		$this->load->view('user_profile',$ctrldata);
	}
	
	public function end_meeting(){
		$room_id = $this->input->post('room_id');
		$ctrldata['user_data'] = $this->meeting_model->end_meeting($room_id); 
		echo json_encode($ctrldata);
	}
	
	public function save_users(){
		$data = $this->input->post();
		$ctrldata = $this->meeting_model->save_users($data); 
		echo json_encode($ctrldata);
	}
	
	public function admin_upload(){
		$msg 			  	= "";
		$file_element_name 	= $this->input->post('filename');
		$upload_file_name	= $_FILES[$file_element_name]['name'];
		//TimeStamp		
		$date 				= new DateTime();
		$time_filename 		= $date->getTimestamp()."_".$upload_file_name;
		
		$config['upload_path'] 		= './uploads/admin_upload';
		$config['overwrite'] = TRUE;
		$config["allowed_types"] = 'gif|jpg|png|jpeg';
		//$config["max_size"] = 1024;
		$config['file_name'] 		= $time_filename; //
		$config['remove_spaces']	= TRUE;	//Remove Spaces
		  
		$this->load->library('upload', $config);
	
		if (!$this->upload->do_upload($file_element_name)){
			 $status 	= "error";
			 $msg 		= $this->upload->display_errors('', '');
			 $formdata['msg']		= $msg;
		}
		else
		{
			// Sample file Read Information
			$getname	= $this->upload->data();
			$filename		= $getname["client_name"];
			$store_filename	= $getname["file_name"];
			$formdata['msg']		= 'Successfully uploaded.';
			$formdata['display_filename']	= $filename;
			$formdata['store_filename']		= $store_filename;
		}
	  echo json_encode($formdata);
	}
}
