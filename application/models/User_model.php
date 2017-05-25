<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class user_model extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	//Get Users
	public function get_users(){
		$query  = 'SELECT * FROM users WHERE is_active=1';
		$rs 	= $this->db->query($query);
		return $rs->result();
	}

	public function list_group(){
		$query  = 'SELECT * FROM group_info WHERE is_active=1';
		$rs 	= $this->db->query($query);
		return $rs->result();
	}
	
	public function company_details(){
		$query  = 'SELECT * FROM admin WHERE is_active=1';
		$rs 	= $this->db->query($query);
		return $rs->result();
	}
	
	public function delete_user($user_id){
		$this->db->where('id', $user_id);
		$this->db->delete('users'); 
		return true;
	}
	
	public function delete_group($group_id){
		$this->db->where('id', $group_id);
		$this->db->delete('group_info'); 
		return true;
	}
	
	public function edit_user($user_id){
		$query  = 'SELECT * FROM users WHERE is_active=1 and id ='.$user_id;
		$rs 	= $this->db->query($query);
		return $rs->result();
	}
	
	public function edit_group($group_id){
		$query  = 'SELECT * FROM group_info WHERE is_active=1 and id ='.$group_id;
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
	
	public function save_company($userdata){
		#	echo '<pre>';print_r($userdata);exit;
		$this->db->set('company_name',$userdata['company_name']);
		$this->db->set('mobile_no',$userdata['mobile_no']);
		$this->db->set('fb_username',$userdata['fb_username']);
		$this->db->set('fb_passsword',$userdata['fb_passsword']);
		$this->db->set('address',$userdata['company_address']);
		$this->db->set('description',$userdata['company_description']);
		$this->db->set('logo_name',$userdata['logo_name']);
		
		$this->db->where('id',0);
		$this->db->update('admin');
		$modeldata['msg']= 'Successfully save!';
		$modeldata['flag']=1;
		
		return $modeldata;
	}
	
	public function save_info($userdata){
		$this->db->set('admin_name',$userdata['admin_username']);
		$this->db->set('email',$userdata['admin_email']);
		$this->db->set('admin_password',$userdata['admin_passsword']);
		$this->db->set('user_photo',$userdata['user_photo_name']);

		$this->db->where('id',0);
		$this->db->update('admin');
		$modeldata['msg']= 'Successfully save!';
		$modeldata['flag']=1;
		
		return $modeldata;
	}
	
	public function save_group($userdata){
		#echo '<pre>';print_r($userdata);exit;
		$group_name  	= $userdata['group_name'];
		$grp_memberss  	= $userdata['grp_members'];
		if(!empty($grp_memberss)){
			$grp_members =  implode(",",$grp_memberss);
		}

		$query = "SELECT id FROM group_info WHERE is_active=1 AND group_name='".$group_name."'"." AND grp_members ='".$grp_members."'";
		$rs = $this->db->query($query);
		
		if($rs->num_rows() <= 0){
			$this->db->set('group_name',$group_name);
			$this->db->set('grp_members',$grp_members);
			if(!empty($userdata['group_id'])){
				$this->db->where('id',$userdata['group_id']);
				$this->db->update('group_info');
				$modeldata['msg']= 'Successfully Updated!';
				$modeldata['flag']=1;
				 #echo $this->db->last_query();exit;
			}else{
				$this->db->insert('group_info');
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
		}else{
			$modeldata['msg']= 'Error on insert';
			$modeldata['flag']=0;
		}
		return $modeldata;
	}
}