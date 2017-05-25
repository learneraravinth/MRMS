<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company_profile extends CI_Controller {
    function __construct() {
        parent::__construct();
		$this->load->model('user_model'); 
		$this->load->library('session');                             	 	 	
		$session_userdata = $this->session->userdata('loginCheck');
		if(!isset($session_userdata['admin_id'])){
			redirect(base_url().'admin/login');
		}
    }
	
	public function index(){
		$ctrldata['company_details'] = $this->user_model->company_details(); 
		$this->load->view('company_details',$ctrldata);
	}
	
	public function save_company(){
		$data = $this->input->post();
		$ctrldata['company_details'] = $this->user_model->save_company($data); 
		echo json_encode($ctrldata);
	}
	
	public function save_info(){
		$data = $this->input->post();
		$ctrldata['company_details'] = $this->user_model->save_info($data); 
		echo json_encode($ctrldata);
	}
	
	public function company_logo_upload(){
			$msg 			  	= "";
			$file_element_name 	= $this->input->post('filename');
			$upload_file_name	= $_FILES[$file_element_name]['name'];
			//TimeStamp		
			$date 				= new DateTime();
			$time_filename 		= $date->getTimestamp()."_".$upload_file_name;
			
			$config['upload_path'] 		= './uploads/company_logo_upload';
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
	
	public function admin_upload(){
		#echo '<pre>';print_r($_FILES);exit;
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
