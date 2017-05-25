<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
      	$this->load->model ('login_model');
      	$this->load->library ('session');
		$session_userdata = $this->session->userdata('loginCheck');
		if(isset($session_userdata['admin_id'])){
			redirect('admin/dashboard');
		}
    }
	
	public function index(){
		$this->load->view('login');
	}
	
	public function login_user(){
		$output   = $this->login_model->check_adminlogin($this->input->post());
		
		if(count($output->result()) > 0){
			$login_details = $output->result_array();
			#echo '<pre>';print_r($login_details );exit;
			$user_data = array(
				'user_type'        				 => 'admin',
				'admin_id'        				 => $login_details[0]['id'],
				'email_id'  				  	 => $login_details[0]['email'],
				'admin_name'					 => $login_details[0]['admin_name'],
				'user_photo'					 => $login_details[0]['user_photo']
			);
			
			$this->session->set_userdata('loginCheck',$user_data);	
			$msg_data['flag'] = '1';									
		}else{
			$msg_data['msg'] = 'Password not match!';
			$msg_data['flag'] = '0';	
		}
		echo json_encode($msg_data);
	}
	
	
}
