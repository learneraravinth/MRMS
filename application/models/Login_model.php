<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class login_model extends CI_Model {

	public function __construct(){
		parent::__construct();

	}
	public function check_adminlogin($getdata){
		$email 	= trim($getdata['admin_email']);
		$pwd    = trim($getdata['admin_password']);
		$this->db->select(array('id','admin_name','user_photo','email'));
		$this->db->from('admin');
		$this->db->where('email',ltrim($email));
		$this->db->where('admin_password',ltrim($pwd));
		$this->db->where('is_active','1');
		$rs = $this->db->get();
		return $rs;
	}
	
	public function check_login_user($user_name){
		$user_name 	= trim($user_name);
		$this->db->select(array('id','user_name','short_name','name_id','face_bookid','job_title','user_photo'));
		$this->db->from('users');
		$this->db->where('user_name',$user_name);
		$this->db->or_where('short_name',$user_name);
		$rs = $this->db->get();
		return $rs;
	}
	
	
}