<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 * mobile related functions
 * @author Aravinth
 *
 */
 
class Response extends CI_Controller {  
	function __construct(){
        parent::__construct();
		date_default_timezone_set('Asia/Phnom_Penh');
		$this->load->model('response_model');
		
	}	
	
	public function ip_check(){
		$ip_address     = urldecode($_POST['ip_address']);
		$ip 			= $this->input->ip_address();
		if($ip_address == $ip){
			$status = '1';
			$message = 'Valide Ip Address';
		}else{
			$status  = '0';
			$message = 'InValide Ip Address';
		}
		$json_encode = json_encode(array("status" => $status,"message" => $message));
		echo $json_encode;
	}
	
	public function name_check(){
		$user_name      = urldecode($_POST['name_id']);
		$user_details   = $this->response_model->spe_users($user_name);
		$admin_details  = $this->response_model->admin_details();
		$company_name 	= $admin_details->row()->company_name;
		$logo_name 	  	= base_url().'uploads/admin_upload'.$admin_details->row()->logo_name;
		$todaybook = '';
		if($user_details->num_rows() > 0){
			$room_details   = $this->response_model->get_room_det();
			//echo '<pre>';print_r($users_details->result());exit;
			$users_details  = $this->response_model->get_users(); 
			$amen_details   = $this->response_model->get_amenities();
			if($room_details->num_rows() > 0){
				$i = 1;
				foreach($room_details->result() as $room){
					$room_amenity = '';
					if(!empty($room->room_amenity)){
						foreach($amen_details as $amen){
							$amens_arr = explode(',',$room->room_amenity);
							if(in_array($amen->id,$amens_arr)){
								$amne_val[] = $amen->amenities_name;
							}
						}
						$room_amenity 	= implode(',',$amne_val);
					}
					$booking = '';
					$booking_details  = $this->response_model->booking_list($room->id);
					if($booking_details['today']->num_rows() > 0){ 
						$today 	 = $booking_details['today'];
							$booking = '';	
							$booking_id  = $today->row()->id;
							$title  	 = $today->row()->title;
							$agenda  	 = $today->row()->agenda;
							$today_start_date  = strtotime($today->row()->start_date);
							$today_end_date    = strtotime($today->row()->end_date);	
							if(!empty($today->row()->invities)){
								$user_name   = array();
								$invities    = $today->row()->invities;
								foreach($users_details->result() as $user){
									$grp_members = explode(',',$invities);
									if(in_array($user->id,$grp_members)){
										$user_name[] = $user->user_name;
									}
								}
							}
											
							$created_by = $today->row()->created_by;
							if($created_by==0){
								$created_room = 'Admin';
							}else{
								foreach($users_details->result() as $user){
									if($user->id==$created_by){
										$usname = $user->user_name;
									}
								}
								$created_room = $usname;
							}
							
							$booking[] 		= array('booking_id'=>$booking_id,'title'=>$title,'purpose_meeting'=>$agenda,'participants'=>$user_name,'booking_organizer'=>$created_room,'start_time'=>date('h:i a',$today_start_date),'end_time'=> date('h:i a',$today_end_date),'date'=> date('Y-m-d',$today_end_date));
							
							
							foreach($booking_details['booking_list']->result() as $value){
							//echo '<pre>';print_r($booking_details['booking_list']->result());exit;
								$start_date  = strtotime($value->start_date);
								$end_date    = strtotime($value->end_date);
								
								
							if($start_date !=$today_start_date && $end_date!=$today_end_date){
								$booking_id  = $value->id;
								$title  	 = $value->title;
								$agenda  	 = $value->agenda;
								if(!empty($value->invities)){
									$user_name   = array();
									$invities    = $value->invities;
									foreach($users_details->result() as $user){
										$grp_members = explode(',',$invities);
										if(in_array($user->id,$grp_members)){
											$user_name[] = $user->user_name;
										}
									}
								}
								
								$created_by = $value->created_by;
								if($created_by==0){
									$created_room = 'Admin';
								}else{
									foreach($users_details->result() as $user){
										if($user->id==$created_by){
											$uname = $user->user_name;
										}
									}
									$created_room = $uname;
								} 
								
								$booking[] = array('booking_id'=>$booking_id,'title'=>$title,'purpose_meeting'=>$agenda,'participants'=>$user_name,'booking_organizer'=>$created_room,'start_time'=>date('h:i a',$start_date),'end_time'=> date('h:i a',$end_date),'date'=> date('Y-m-d',$end_date));
							}
						
						}
						
							$status = 'Booked';
							/*Today Detail Start */	
							
							
							//$booking 		= array_pad($today,$booking_data);
							#echo '<pre>';print_r($today);
							#echo '<pre>';print_r($booking_data);exit;
							
							/*Today Detail End */
						}else{
							$status = 'Available';
						}
						
						$data_arr[] 	= array('room_id' => $room->id,'currentstatus' => $status,'room_name' => $room->room_name,'seating_capacity' => $room->seating_capacity,'description' => $room->description,'room_amenity' => rtrim($room_amenity,','),'booking' => $booking);
					
						$i = $i+1;
					
				}
			}
			//$jsonReturn['booking_details']  = $booking_details;
			$jsonReturn['user_details'] 	= array('user_id'=>$user_details->row()->id,'name_id'=>$user_details->row()->name_id,'user_name'=>$user_details->row()->user_name,'job_title'=>$user_details->row()->job_title,'face_bookid'=>$user_details->row()->face_bookid,'description'=>$user_details->row()->description);
			
			$jsonReturn['company_name'] = $company_name;
			$jsonReturn['company_logo'] = $logo_name;
			$jsonReturn['room_details'] = $data_arr;
			$jsonReturn['current_booking'] = $todaybook;
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = 'Successfully logged in.!';
		}else{
			$jsonReturn['status']  = '0';
			$jsonReturn['message'] = 'InValide User Name.!';
		}
		echo json_encode($jsonReturn); 
	}
	
	public function add_new_meeting(){
		$room_id   	  = urldecode($_POST['room_id']);
		$title        = urldecode($_POST['title']);
		$agenda       = urldecode($_POST['agenda']);
		$invites      = urldecode($_POST['invites']);
		$start_titming = urldecode($_POST['start_titming']);
		$end_titming   = urldecode($_POST['end_titming']);
		$created_by   = urldecode($_POST['created_by']);
		
		$data 	= array('room_id' => $room_id,
					'title'		  => 'title',
					'agenda'	  => $agenda,
					'invites'	  => $invites,
					'start_titming'	=>	$start_titming,
					'created_by'	=>	$created_by,
					'end_titming'	=>	$end_titming);
		$id = $this->response_model->save_meeting($data);
		if($id > 0){
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = 'Meeting Successfully Added';
		}else{
			$jsonReturn['status']  = '0';
			$jsonReturn['message'] = 'Error on insert.!';
		}
		echo json_encode($jsonReturn); 
	}
	
	public function room_details(){
		$room_id      = urldecode($_POST['room_id']);
		$detail_type  = urldecode($_POST['detail_type']);
		$detail_date  = urldecode($_POST['detail_date']);
		$jsonReturn['status']   = '1';//Return For room details
		$jsonReturn['response'] = array('room_id' => $room_id);
		echo json_encode($jsonReturn); 
	}
	
	public function employee_details(){
		$name_id        = urldecode($_POST['name_id']);
		$user_details   = $this->response_model->spe_users($name_id);
		if($user_details->num_rows() > 0){
			$jsonReturn['response'] = 	array('name_id'=>$user_details->row()->name_id,'user_name'=>$user_details->row()->user_name,'job_title'=>$user_details->row()->job_title,'face_bookid'=>$user_details->row()->face_bookid,'description'=>$user_details->row()->description);
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = 'Valide User Name';
		}else{
			$jsonReturn['status']  = '0';
			$jsonReturn['message'] = 'InValide User Name.!';
		}
		echo json_encode($jsonReturn); 
	}
	
	public function room_info_user_info(){
		$room_details   = $this->response_model->get_room_det();
		
		$user_details   = $this->response_model->get_users();
		$roomdata 	= array();
		foreach($room_details->result() as $value){
			$roomdata[] 	= array('room_id'=>$value->id,'room_name'=>$value->room_name);
		}
		$userdata = array();
		foreach($user_details->result() as $value){
			$userdata[] 	= array('user_name'=>$value->user_name,'user_id'=>$value->id);
		}
		$jsonReturn['user_details'] = $userdata;
		$jsonReturn['room_details'] = $roomdata;
		$jsonReturn['status'] = '1';
		$jsonReturn['message'] = 'Available data.!';
		echo json_encode($jsonReturn); 
	}
}
/* End of file */
/* Location: ./application/controllers/site/response.php */
?>