<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loginuser extends CI_Controller {
    function __construct() {
        parent::__construct();
      	$this->load->model ('login_model');
      	$this->load->library ('session');
		$session_user = $this->session->userdata('loginuser');
		if(isset($session_user['user_id'])){
			redirect('dashboard_user');
		} 
    }
	
	public function index(){
		$this->load->view('user/login_user');
	}

	public function check_username(){
		$user_name = $this->input->post('user_name');
		$output    = $this->login_model->check_login_user($user_name);
		if(count($output->result()) > 0){
			$login_details = $output->result_array();
			$user_data = array(
				'user_type'   => 'user',
				'user_id'   => $login_details[0]['id'],
				'user_name'   => $login_details[0]['user_name'],
				'face_bookid'   => $login_details[0]['face_bookid']
			);
			$this->session->set_userdata('loginuser',$user_data);	
			$msg_data['flag'] = '1';									
		}else{
			$msg_data['msg'] = 'Password not match!';
			$msg_data['flag'] = '0';	
		}
		echo json_encode($msg_data);
	}
	
}
