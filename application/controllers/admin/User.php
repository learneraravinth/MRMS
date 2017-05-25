<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    function __construct() {
        parent::__construct();
		$this->load->model('user_model'); 
		$this->load->library('session');                             	 	 	
		$session_userdata = $this->session->userdata('loginCheck');
		if(!isset($session_userdata['admin_id'])){
			redirect(base_url().'/admin/login');
		}
    }
	
	public function index(){
		$ctrldata['users_details'] = $this->user_model->get_users(); 
		$this->load->view('user/user_list',$ctrldata);
	}
	
	public function add_users(){
		$this->load->view('user/add_user');
	}
	public function user_list(){
		$ctrldata['users_details'] = $this->user_model->get_users(); 
		$this->load->view('user/user_list',$ctrldata);
	}
	
	public function delete_user(){
		$user_id = $this->input->post('user_id');
		$this->user_model->delete_user($user_id); 
		$ctrldata['flag'] = 1;
		echo json_encode($ctrldata);
	}
	
	public function edit_user($user_id){
		$ctrldata['user_data'] = $this->user_model->edit_user($user_id); 
		$this->load->view('user/edit_user',$ctrldata);
	}
	
	
	public function save_users(){
		$data = $this->input->post();
		$ctrldata = $this->user_model->save_users($data); 
		echo json_encode($ctrldata);
	}
	
	public function create_group(){
		$ctrldata['users_details'] = $this->user_model->get_users(); 
		$this->load->view('user/create_group',$ctrldata);
	}
	
	public function save_group(){
		$data = $this->input->post();
		$ctrldata = $this->user_model->save_group($data); 
		echo json_encode($ctrldata);
	}
	
	public function list_group(){
		$ctrldata['group_details']= $this->user_model->list_group(); 
		$this->load->view('user/list_group',$ctrldata);
	}
	public function edit_group($group_id){
		$ctrldata['users_details'] = $this->user_model->get_users(); 
		$ctrldata['group_data'] = $this->user_model->edit_group($group_id); 
		$this->load->view('user/edit_group',$ctrldata);
	}
	
	public function delete_group(){
		$group_id = $this->input->post('group_id');
		$this->user_model->delete_group($group_id); 
		$ctrldata['flag'] = 1;
		echo json_encode($ctrldata);
	}
}
