<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Meeting_room extends CI_Controller {
    function __construct() {
        parent::__construct();
		$this->load->model('meeting_model'); 
		$this->load->library('session');                             	 	 	
		$session_userdata = $this->session->userdata('loginCheck');
		if(!isset($session_userdata['admin_id'])){
			redirect(base_url().'/admin/login');
		}
    }
	
	public function index(){
		$ctrldata['amenities_details'] = $this->meeting_model->get_amenities(); 
		$this->load->view('meeting_room/meeting_room_add',$ctrldata);
	}
	public function room_add(){
		$ctrldata['amenities_details'] = $this->meeting_model->get_amenities(); 
		$this->load->view('meeting_room/meeting_room_add',$ctrldata);
	}
	
	public function room_list(){
		$ctrldata['room_details'] = $this->meeting_model->get_room_det(); 
		$ctrldata['amenities_details'] = $this->meeting_model->get_amenities(); 
		$this->load->view('meeting_room/listing',$ctrldata);
	}
	
	public function save_amenities(){
		$data = $this->input->post();
		$ctrldata = $this->meeting_model->save_amenities($data); 
 		echo json_encode($ctrldata);	
	}
	
	public function save_meeting_details(){
		$data = $this->input->post();
		$this->meeting_model->save_meeting_details($data); 
		redirect('admin/meeting_room/room_list'); 
	}
	
	public function delete_meeting(){
		$meeting_id = $this->input->post('meeting_id');
		$this->meeting_model->delete_meeting($meeting_id); 
		$ctrldata['flag'] = 1;
		echo json_encode($ctrldata);
	}
	
	public function edit_meeting($meeting_id){
		$ctrldata['amenities_details'] = $this->meeting_model->get_amenities();
		$ctrldata['meeting_data'] = $this->meeting_model->edit_meeting($meeting_id); 
		$this->load->view('meeting_room/meeting_room_edit',$ctrldata);
	}
	
	public function create_booking(){
		$ctrldata['users_details'] = $this->meeting_model->get_users(); 
		$ctrldata['room_details']  = $this->meeting_model->get_room_det(); 
		$this->load->view('meeting_room/booking_add',$ctrldata);
	}
	
	public function history_booking(){
		$this->config->set_item('gender', 'test');
		$ctrldata['room_details']  = $this->meeting_model->get_room_det();  
		$this->load->view('meeting_room/history',$ctrldata);
	}
	
	public function search_room(){
		$data 		= $this->input->post();
		if($data['room_searchs']!='' && $data['meeting_date']!=''){
			$ctrldata['room_history']   = $this->meeting_model->booking_history($data);
			$ctrldata['room_details']  = $this->meeting_model->get_room_det();		
			$ctrldata['search_data']   = $data;
			$ctrldata['users_details'] = $this->meeting_model->get_users(); 
			$ctrldata['amen_details']  = $this->meeting_model->get_amenities(); 
			$this->load->view('meeting_room/history',$ctrldata);	
		}else{
			 redirect('/admin/meeting_room/history_booking', 'refresh');
		}
	}
	
}
