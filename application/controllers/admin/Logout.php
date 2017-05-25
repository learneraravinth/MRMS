<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Logout extends CI_Controller{
	public function __construct(){
		parent::__construct();
	}
	
	// Logout Distroy functionality
	public function index(){
		$this->session->sess_destroy();
		redirect('admin/login');
	}

	public function logout_user(){
		$this->session->sess_destroy();
		redirect('');
	}
	
}
?>