<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 
 * mobile related functions
 * @author Teamtweaks
 *
 */
 
class Mobile extends CI_Controller {  

 
	function __construct(){
        parent::__construct();
      	$this->load->helper(array('cookie','date','form','email','language'));
		//$this->load->library(array('encrypt','form_validation','resizeimage'));		
		$this->load->model('response_model');
	}	
	/** 
	 * 
	 * Loading Category Json Page
	 */
	
	public function index(){
		
	}
	

	public function mobile_signup_user(){
		$password = urldecode($_POST['u_password']);
		$mobile_no = urldecode($_POST['u_mobile_no']);
		$country_code = $_POST['u_country_code'];
		$flag = urldecode($_POST['u_flag']);
		$headerStringValue = apache_request_headers();
        $device_type = $headerStringValue['device_type'];
        $key = $headerStringValue['u_key'];
        $lang = $headerStringValue['lang'];
		$pwd = md5($password);
		$get_user_details = $this->common_model->get_all_details(USERS,array('phone_no' => $mobile_no));
		$user_id = $get_user_details->row()->id;
		$first_name = $get_user_details->row()->firstname;
		$last_name = $get_user_details->row()->lastname;
		$phone_no = $get_user_details->row()->phone_no;
		$user_image = $get_user_details->row()->image;
		if($user_image!=''){
			$image = base_url ().'images/users/'.$user_image;
		}else{
			$image = base_url ().'images/users/user_image.png';
		}
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$message = $this->lang->line('login_success');
				$error = $this->lang->line('invalid_login');
				$verify_code = $this->lang->line('verify_code');
				$otp_message = $this->lang->line('otp_message');
				
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$message = $this->lang->line('login_success');
				$error = $this->lang->line('invalid_login');
				$verify_code = $this->lang->line('verify_code');
				$otp_message = $this->lang->line('otp_message');
			}else{
				$this->lang->load('en', 'en');
				$message = $this->lang->line('login_success');
				$error = $this->lang->line('invalid_login');
				$verify_code = $this->lang->line('verify_code');
				$otp_message = $this->lang->line('otp_message');
			}
		if($phone_no !=''){
			$get_user_details = $this->common_model->get_all_details(USERS,array('id' => $user_id,'password' => $pwd,'phone_no' => $phone_no,'ph_country' => $country_code));
			if($get_user_details->num_rows() > 0){
			if($device_type == 'android'){
			if($key != ''){
				$data = array(
					'mobile_key'=>$key,'device_type' => $device_type
					);
				$condition = array(
					'phone_no'=>$mobile_no
					);
				$this->common_model->update_details(USERS ,$data ,$condition);
			}
		}
		if($device_type == 'ios') {
			if($key != '') {
				$data = array(
					'mobile_key'=>$key,'device_type' => $device_type
					);
				$condition = array(
					'phone_no'=>$mobile_no
					);
				$this->common_model->update_details(USERS ,$data ,$condition);
			}
		}
		$rating = $this->user_rating($user_id);
		if($rating!=''){
			$rating = $this->user_rating($user_id);
		}else{
			$rating = 0;
		}
		$ride_count = $this->user_ride_cnt($user_id);
		if($ride_count!='0'){
			$ride_count = $this->user_ride_cnt($user_id);
		}else{
			$ride_count = 0;
		}
			if($device_type == 'ios'){
				$jsonReturn['status'] = '1';
				$jsonReturn['message'] = $message;
				$jsonReturn['response'] = array('user_id' =>$user_id,'firstname' => $first_name,'lastname' => $last_name,'phone_no' => $phone_no,'country_code' => $country_code,'user_image' => $image,'flag' => $flag,'ride_count' => $ride_count,'ratings' => $rating);
			echo json_encode($jsonReturn);
			}else{
				$update_image = base_url().'images/icon/edit_img.png';
				$delete_image = base_url().'images/icon/delete_img.png';
				$cancel_image = base_url().'images/icon/cancel-img.png';
				$lang_image = base_url().'images/icon/language_img.png';
				$jsonReturn['status'] = '1';
				$jsonReturn['message'] = $message;
				$jsonReturn['response'] = array('user_id' =>$user_id,'firstname' => $first_name,'lastname' => $last_name,'phone_no' => $phone_no,'country_code' => $country_code,'user_image' => $image,'update_image' => $update_image,'delete_image' => $delete_image,'cancel_image' => $cancel_image,'lang_image' => $lang_image,'flag' => $flag,'ride_count' => $ride_count,'ratings' => $rating);
			echo json_encode($jsonReturn);
			}
			
			}else{
				$jsonReturn['status'] = '0';
				$jsonReturn['message'] = $error;
			echo json_encode($jsonReturn);
			}

		}else{

			$number_of_digits = mb_strlen($mobile_no);
			require_once('twilio/Services/Twilio.php');
			$version = "2010-04-01";
			$signup_otp = mt_rand(100000, 999999);
			$account_sid = 'AC0632d53f15fc64c4b34dcc5cabca584c'; 
			$auth_token = '0edc5359ac6d4232e445a4cbc266d569';
			$to=$country_code.''.$mobile_no;
			$client = new Services_Twilio($account_sid,$auth_token);
			$client->account->messages->create(array( 
			'From' => "8444523943",
			'To' => $to,	
			'Body' => $otp_message.$signup_otp
   			)); 
			$pwd = md5($password);
			$jsonReturn['status'] = '2';
			$jsonReturn['message'] = $verify_code;
			$jsonReturn['response'] = array('phone_no' => $mobile_no,'country_code' => $country_code,'mobile_verification_code'=>$signup_otp,'flag' => $flag);
			echo json_encode($jsonReturn);
		}
	}
	
	public function mobile_otp_verification(){
		$mobile_no = urldecode($_POST['u_mobile_no']);
		$password = urldecode($_POST['u_password']);
		$country_code = $_POST['u_country_code'];
		$otp = $_POST['u_otp'];
		$flag = urldecode($_POST['u_flag']);
		$headerStringValue= apache_request_headers();
        $device_type = $headerStringValue['device_type'];
        $key = $headerStringValue['u_key'];
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$message = $this->lang->line('register_success');
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$message = $this->lang->line('register_success');
			}else{
				$this->lang->load('en', 'en');
				$message = $this->lang->line('register_success');
			}
			$pwd = md5($password);
			$dataArr = array('password' => $pwd,'phone_no' => $mobile_no,'ph_country' => $country_code,'mobile_verification_code' => $otp,'mobile_key' => $key,'device_type' => $device_type,'user_flag' => $flag);
			$this->common_model->simple_insert(USERS,$dataArr);
			$image = base_url ().'images/users/user_image.png';
			$id = $this->db->insert_id();
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = $message;
			$jsonReturn['response'] = array('user_id' =>$id,'firstname'=>'','lastname' =>'','country_code' => $country_code,'phone_no' => $mobile_no,'user_image' => $image,'flag' => $flag,'ride_count' => '0','ratings' => '0');
			echo json_encode($jsonReturn);
		}
		
		public function mobile_facebook_login(){
			$facebook_id = urldecode($_POST['user_id']);
			$signup_type = urldecode($_POST['signup_type']);
			$headerStringValue= apache_request_headers();
			$device_type = $headerStringValue['device_type'];
			$key = $headerStringValue['u_key'];
			$lang = $headerStringValue['lang'];
			$condition = array('facebook_id' => $facebook_id,'signup_type' =>$signup_type);
			$get_user_details = $this->common_model->get_all_details(USERS,$condition);
			$user_id = $get_user_details->row()->id;
			$mobile_no = $get_user_details->row()->phone_no;
			$country_code = $get_user_details->row()->ph_country;
			$first_name = $get_user_details->row()->firstname;
			$last_name = $get_user_details->row()->lastname;
			$user_flag = $get_user_details->row()->user_flag;
			$user_image = $get_user_details->row()->image;
			if($lang == 'km'){
				$this->lang->load('km', $lang);
				$message = $this->lang->line('login_success');
				$register_yet = $this->lang->line('account_registerd');
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$message = $this->lang->line('login_success');
				$register_yet = $this->lang->line('account_registerd');
			}else{
				$this->lang->load('en', 'en');
				$message = $this->lang->line('login_success');
				$register_yet = $this->lang->line('account_registerd');
			}
			if($user_image!=''){
				$image = base_url ().'images/users/'.$user_image;
				}else{
				$image = base_url ().'images/users/user_image.png';
			 }
			if($get_user_details->num_rows() > 0){

			if($device_type == 'android'){
			if($key != ''){
				$data = array(
					'mobile_key'=>$key,'device_type' => $device_type
					);
				$condition = array(
					'phone_no'=>$mobile_no
					);
				$this->common_model->update_details(USERS ,$data ,$condition);
			}
		 }
		 if($device_type == 'ios') {
			if($key != '') {
				$data = array(
					'mobile_key'=>$key,'device_type' => $device_type
					);
				$condition = array(
					'phone_no'=>$mobile_no
					);
				$this->common_model->update_details(USERS ,$data ,$condition);
			}
		}
			$rating = $this->user_rating($user_id);
			if($rating!=''){
			$rating = $this->user_rating($user_id);
			}else{
			$rating = 0;
			}
			$ride_count = $this->user_ride_cnt($user_id);
			if($ride_count!='0'){
			$ride_count = $this->user_ride_cnt($user_id);
			}else{
			$ride_count = 0;
			}
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = $message;
			$jsonReturn['response'] = array('user_id' =>$user_id,'firstname'=>$first_name,'lastname' =>$last_name,'country_code' => $country_code,'phone_no' => $mobile_no,'signup_type' => $signup_type,'user_image' => $image,'ride_count' => $ride_count,'ratings' => $rating,'flag' => $user_flag);
			echo json_encode($jsonReturn);
		}else{
			$jsonReturn['status'] = '2';
			$jsonReturn['message'] = $register_yet;
			echo json_encode($jsonReturn);
		}	
		}
		
		public function mobile_facebook_signup(){
		 
		$mobile_no = urldecode($_POST['u_mobile_no']);
		$country_code = $_POST['u_country_code'];
		$signup_type = $_POST['u_signup_type'];
		$flag = $_POST['u_flag'];
		$headerStringValue= apache_request_headers();
        $device_type = $headerStringValue['device_type'];
        $key = $headerStringValue['u_key'];
        $lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$message = $this->lang->line('mobile_number_exist');
				$otp_message = $this->lang->line('otp_message');
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$message = $this->lang->line('mobile_number_exist');
				$otp_message = $this->lang->line('otp_message');	
			}else{
				$this->lang->load('en', 'en');
				$message = $this->lang->line('mobile_number_exist');
				$otp_message = $this->lang->line('otp_message');
			}
		$condition = array('phone_no' => $mobile_no);
		$get_user_details = $this->common_model->get_all_details(USERS,$condition);
		if($get_user_details->num_rows() > 0){
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $message;
			echo json_encode($jsonReturn);
		}else{
			$number_of_digits = mb_strlen($mobile_no);
			require_once('twilio/Services/Twilio.php');
			$version = "2010-04-01";
			$signup_otp = mt_rand(100000, 999999);
			$account_sid = 'AC0632d53f15fc64c4b34dcc5cabca584c'; 
			$auth_token = '0edc5359ac6d4232e445a4cbc266d569';
			$to=$country_code.''.$mobile_no;
			$client = new Services_Twilio($account_sid,$auth_token);
			$client->account->messages->create(array( 
			'From' => "8444523943",
			'To' => $to,	
			'Body' => $otp_message.$signup_otp
   			)); 
			$pwd = md5($password);
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = 'Please verify your code';
			$jsonReturn['response'] = array('phone_no' => $mobile_no,'country_code' => $country_code,'mobile_verification_code'=>$signup_otp,'flag' => $flag,'signup_type' => $signup_type);
			echo json_encode($jsonReturn);
		}	
		}

	public function mobile_facebook_otp_verification(){
		$user_id = urldecode($_POST['user_id']);
		$first_name = urldecode($_POST['u_firstname']);
		$last_name = urldecode($_POST['u_lastname']);
		$mobile_no = urldecode($_POST['u_mobile_no']);
		$password = urldecode($_POST['u_password']);
		$country_code = $_POST['u_country_code'];
		$signup_type = urldecode($_POST['u_signup_type']);
		$flag = urldecode($_POST['u_flag']);
		$otp = $_POST['u_otp'];
		$headerStringValue= apache_request_headers();
        $device_type = $headerStringValue['device_type'];
        $key = $headerStringValue['u_key'];
        $lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$message = $this->lang->line('register_success');
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$message = $this->lang->line('register_success');
			}else{
				$this->lang->load('en', 'en');
				$message = $this->lang->line('register_success');
			}
        if($device_type == 'ios'){
        	if($_FILES['u_image']!=''){
			$uploaddir = "images/users/";
			$data = file_get_contents($_FILES['u_image']['tmp_name']);
			$image = imagecreatefromstring($data);
			$imgname=time().".jpg";
			imagejpeg($image,$uploaddir.$imgname);
			}
        }else{
        	$img = $_POST['u_image'];
			$data = 'data:image/jpg;base64,'.$img.'';
			$data = str_replace('data:image/jpg;base64,', '', $data);
			$img_str = str_replace(' ', '+', $data);
			$data1 = base64_decode($img_str);
			$imgname=time().".jpg";
			$file = 'images/users/'.$imgname;
			$success = file_put_contents($file, $data1);
        }
		$pwd = md5($password);
		$dataArr = array('password' => $pwd,'phone_no' => $mobile_no,'facebook_id' => $user_id,'firstname' => $first_name,'lastname' => $last_name,'ph_country' => $country_code,'mobile_verification_code' => $otp,'mobile_key' => $key,'device_type' => $device_type,'signup_type' => $signup_type,'image' => $imgname,'user_flag' => $flag);
		$this->common_model->simple_insert(USERS,$dataArr);
		$id = $this->db->insert_id();
		$get_last_insert = $this->common_model->get_all_details(USERS,array('id' => $id));
		$user_image = $get_last_insert->row()->image;
		if($user_image!=''){
			$image = base_url ().'images/users/'.$user_image;
		}else{
			$image = base_url ().'images/users/user_image.png';
		}
		$jsonReturn['status'] = '1';
		$jsonReturn['message'] = $message;
		$jsonReturn['response'] = array('user_id' =>$id,'firstname'=>$first_name,'lastname' =>$last_name,'country_code' => $country_code,'phone_no' => $mobile_no,'user_image' => $image,'signup_type' => $signup_type,'flag' => $flag,'ride_count' => '0','ratings' => '0');
		echo json_encode($jsonReturn);
		
	}
	public function mobile_logout_user(){
		$user_id = urldecode($_POST['u_id']);
		$headerStringValue= apache_request_headers();
		$lang = $headerStringValue['lang'];
		$condition = array('id' => $user_id);
		$get_user_details = $this->common_model->get_all_details(USERS,$condition);
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$message = $this->lang->line('logout_success');
				$invalid_id = $this->lang->line('invalid_user_id');
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$message = $this->lang->line('logout_success');
				$invalid_id = $this->lang->line('invalid_user_id');
			}else{
				$this->lang->load('en', 'en');
				$message = $this->lang->line('logout_success');
				$invalid_id = $this->lang->line('invalid_user_id');
			}
		if($get_user_details->num_rows() > 0){
			$dataArr = array('mobile_key'=> '','device_type'=>'');
			$condition = array('id' => $user_id);
			$this->common_model->update_details(USERS,$dataArr,$condition);
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = $message;
			echo json_encode($jsonReturn);
		}else{
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $invalid_id;
			echo json_encode($jsonReturn);
		}
	}
	public function mobile_forgotpwd(){
		$mobile_no = urldecode($_POST['u_mobile_no']);
		$flag = urldecode($_POST['u_flag']);
		$country_code = $_POST['u_country_code'];
		$headerStringValue= apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$otp_message = $this->lang->line('otp_message');
				$wrong_number = $this->lang->line('wrong_number');
				$verify_code = $this->lang->line('verify_code');
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$otp_message = $this->lang->line('otp_message');
				$wrong_number = $this->lang->line('wrong_number');
				$verify_code = $this->lang->line('verify_code');
			}else{
				$this->lang->load('en', 'en');
				$otp_message = $this->lang->line('otp_message');
				$wrong_number = $this->lang->line('wrong_number');
				$verify_code = $this->lang->line('verify_code');
			}
		$condition = array('phone_no' => $mobile_no);
		$get_user_details = $this->common_model->get_all_details(USERS,$condition);
		if($get_user_details->num_rows() > 0){
			//$country_code = $get_user_details->row()->ph_country;
			$mobile_no = $get_user_details->row()->phone_no;
			$first_name = $get_user_details->row()->firstname;
			$last_name = $get_user_details->row()->lastname;
			require_once('twilio/Services/Twilio.php');
			$signup_otp = mt_rand(100000, 999999);
			$account_sid = 'AC0632d53f15fc64c4b34dcc5cabca584c'; 
			$auth_token = '0edc5359ac6d4232e445a4cbc266d569';
			$to=$country_code.''.$mobile_no;
			$client = new Services_Twilio($account_sid,$auth_token);
			$client->account->messages->create(array( 
			'From' => "8444523943",
			'To' => $to,	
			'Body' => $otp_message.$signup_otp));
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = $verify_code;
			$jsonReturn['response'] = array('firstname'=>$first_name,'lastname'=>$last_name,'country_code' => $country_code,'phone_no' => $mobile_no,'flag' => $flag,'mobile_verification_code' => $signup_otp);
			echo json_encode($jsonReturn);
		}
		else{
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $wrong_number;
			echo json_encode($jsonReturn);
		}
	}
	public function mobile_forgotpwd_confirmation(){

		$mobile_no = urldecode($_POST['u_mobile_no']);
		$country_code = $_POST['u_country_code'];
		$forgot_otp = $_POST['u_otp'];
		$headerStringValue= apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$verified_success = $this->lang->line('verified_success');
				$wrong_number = $this->lang->line('wrong_number');
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$verified_success = $this->lang->line('verified_success');
				$wrong_number = $this->lang->line('wrong_number');
			}else{
				$this->lang->load('en','en');
				$verified_success = $this->lang->line('verified_success');
				$wrong_number = $this->lang->line('wrong_number');
			}
		$condition = array('phone_no' => $mobile_no);
		$data = array('mobile_verification_code' => $forgot_otp);
		$this->common_model->update_details(USERS,$data,$condition);
		$get_user_details = $this->common_model->get_all_details(USERS,$condition);
		if($get_user_details->num_rows() > 0){
			$mobile_no = $get_user_details->row()->phone_no;
			$first_name = $get_user_details->row()->firstname;
			$last_name = $get_user_details->row()->lastname;
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = $verified_success;
			$jsonReturn['response'] = array('firstname' => $first_name,'lastname' =>$last_name,'phone_no' => $mobile_no,'country_code' => $country_code);
			echo json_encode($jsonReturn);
		}
		else {
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $wrong_number;
			echo json_encode($jsonReturn);
		}
		
	}
	public function mobile_reset_password(){

		$mobile_no = urldecode($_POST['u_mobile_no']);
		$coutry_code = $_POST['u_country_code'];
		$password = urldecode($_POST['u_password']);
		$headerStringValue= apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$password_reset_success = $this->lang->line('password_reset_success');
				$wrong_number = $this->lang->line('wrong_number');
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$password_reset_success = $this->lang->line('password_reset_success');
				$wrong_number = $this->lang->line('wrong_number');
			}else{
				$this->lang->load('en', 'en');
				$password_reset_success = $this->lang->line('password_reset_success');
				$wrong_number = $this->lang->line('wrong_number');
			}
		$pwd = md5($password);
		$condition = array('phone_no' => $mobile_no);
		$data = array('password' => $pwd);
		$this->common_model->update_details(USERS,$data,$condition);
		$get_user_details = $this->common_model->get_all_details(USERS,$condition);
		if($get_user_details->num_rows() > 0){
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = $password_reset_success;
			echo json_encode($jsonReturn);
		}else{
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $wrong_number;
			echo json_encode($jsonReturn);
		}
	}
	public function mobile_change_user_password(){
		$current_pwd = urldecode($_POST['u_password']);
		$new_pwd = urldecode($_POST['u_new_password']);
		$user_id = $_POST['u_id'];
		$headerStringValue= apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$password_change_success = $this->lang->line('password_change_success');
				$invalid_user_id = $this->lang->line('invalid_user_id');
				
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$password_change_success = $this->lang->line('password_change_success');
				$invalid_user_id = $this->lang->line('invalid_user_id');
			}else{
				$this->lang->load('en', 'en');
				$password_change_success = $this->lang->line('password_change_success');
				$invalid_user_id = $this->lang->line('invalid_user_id');
			}
		$old_password = md5($current_pwd);
		$condition = array('id' => $user_id,'password' => $old_password);
		$get_user_details = $this->common_model->get_all_details(USERS,$condition);
		if($get_user_details->num_rows() > 0){
			$pwd = md5($new_pwd);
			$dataArr = array('password' => $pwd);
			$condition = array('id' => $user_id);
			$this->common_model->update_details(USERS,$dataArr,$condition);
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = $password_change_success;
			echo json_encode($jsonReturn);
		}
		else{
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $invalid_user_id;
			echo json_encode($jsonReturn);
		}
	}
	public function mobile_user_profile_update(){
		$first_name = urldecode($_POST['u_firstname']);
		$last_name = urldecode($_POST['u_lastname']);
		$mobile_no = urldecode($_POST['u_mobile_no']);
		$country_code = $_POST['u_country_code'];
		$flag = urldecode($_POST['u_flag']);
		$user_id  = $_POST['u_id'];
		$headerStringValue= apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$user_profile_update = $this->lang->line('user_profile_update');
				$invalid_user_id = $this->lang->line('invalid_user_id');
				
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$user_profile_update = $this->lang->line('user_profile_update');
				$invalid_user_id = $this->lang->line('invalid_user_id');
			}else{
				$this->lang->load('en', 'en');
				$user_profile_update = $this->lang->line('user_profile_update');
				$invalid_user_id = $this->lang->line('invalid_user_id');
			}
		$condition = array('id' => $user_id);
		$get_user_details = $this->common_model->get_all_details(USERS,$condition);
		$mobile_no = $get_user_details->row()->phone_no;
		//$country_code = $get_user_details->row()->ph_country;
		if($get_user_details->row()->image !=''){
			$image = base_url ().'images/users/'.$get_user_details->row()->image;
		}else{
			$image = base_url ().'images/users/user_image.png';
		}
		if($get_user_details->num_rows() > 0){
			$dataArr = array('firstname' => $first_name,'lastname' => $last_name);
			$condition = array('id' => $user_id);
			$this->common_model->update_details(USERS,$dataArr,$condition);
			$rating = $this->user_rating($user_id);
			if($rating!=''){
			$rating = $this->user_rating($user_id);
			}else{
			$rating = 0;
			}
			$ride_count = $this->user_ride_cnt($user_id);
			if($ride_count!='0'){
			$ride_count = $this->user_ride_cnt($user_id);
			}else{
			$ride_count = 0;
			}
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = $user_profile_update;
			$jsonReturn['response'] = array('user_id' => $user_id,'firstname' => $first_name,'lastname' => $last_name,'user_image' => $image,'country_code' => $country_code,'phone_no' => $mobile_no,'flag' => $flag ,'ride_count' => $ride_count,'ratings' => $rating);
			echo json_encode($jsonReturn);
		}
		else{
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $invalid_user_id;
			echo json_encode($jsonReturn);
		}
	}
	public function mobile_update_mobile_no(){
		$country_code = $_POST['u_country_code'];
		$mobile_no = urldecode($_POST['u_mobile_no']);
		$user_id = $_POST['u_id'];
		$flag = urldecode($_POST['u_flag']);
		$headerStringValue= apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$otp_message = $this->lang->line('otp_message');
				$already_registered = $this->lang->line('already_registered');
				$verify_code = $this->lang->line('verify_code');
				
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$otp_message = $this->lang->line('otp_message');
				$already_registered = $this->lang->line('already_registered');
				$verify_code = $this->lang->line('verify_code');
			}else{
				$this->lang->load('en', 'en');
				$otp_message = $this->lang->line('otp_message');
				$already_registered = $this->lang->line('already_registered');
				$verify_code = $this->lang->line('verify_code');
			}
		$get_mobile_number = $this->common_model->get_all_details(USERS,array('phone_no' => $mobile_no));
		if($get_mobile_number->num_rows() == '0'){
			require_once('twilio/Services/Twilio.php');
			$signup_otp = mt_rand(100000, 999999);
			$account_sid = 'AC0632d53f15fc64c4b34dcc5cabca584c'; 
			$auth_token = '0edc5359ac6d4232e445a4cbc266d569';
			$to=$country_code.''.$mobile_no;
			$client = new Services_Twilio($account_sid,$auth_token);
			$client->account->messages->create(array( 
			'From' => "8444523943",
			'To' => $to,	
			'Body' => $otp_message.$signup_otp));
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = $verify_code;
			$jsonReturn['response'] = array('country_code' => $country_code,'phone_no' => $mobile_no,'mobile_verification_code' => $signup_otp);
			echo json_encode($jsonReturn);
		}else{
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $already_registered;
			echo json_encode($jsonReturn);
		}
		
	}
	public function mobile_user_confirmation_no(){
		$user_id = $_POST['u_id'];
		$otp = urldecode($_POST['u_otp']);
		$country_code = $_POST['u_country_code'];
		$mobile_no = urldecode($_POST['u_mobile_no']);
		$flag = $_POST['u_flag'];
		$headerStringValue= apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$verified_success = $this->lang->line('verified_success');
				$invalid_user_id = $this->lang->line('invalid_user_id');
				
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$verified_success = $this->lang->line('verified_success');
				$invalid_user_id = $this->lang->line('invalid_user_id');
			}else{
				$this->lang->load('en', 'en');
				$verified_success = $this->lang->line('verified_success');
				$invalid_user_id = $this->lang->line('invalid_user_id');
			}
		$condition = array('id' => $user_id);
		$dataArr = array('mobile_verification_code' => $otp,'ph_country' => $country_code,'phone_no' => $mobile_no);
		$this->common_model->update_details(USERS,$dataArr,$condition);
		$condition = array('id' => $user_id);
		$get_user_details = $this->common_model->get_all_details(USERS,$condition);
		if($get_user_details->num_rows() > 0){
			$first_name = $get_user_details->row()->firstname;
			$last_name = $get_user_details->row()->lastname;
			if($get_user_details->row()->image !=''){
			$image = base_url ().'images/users/'.$get_user_details->row()->image;
			}else{
			$image = base_url ().'images/users/user_image.png';
			}
			$rating = $this->user_rating($user_id);
			if($rating!=''){
			$rating = $this->user_rating($user_id);
			}else{
			$rating = 0;
			}
			$ride_count = $this->user_ride_cnt($user_id);
			if($ride_count!='0'){
			$ride_count = $this->user_ride_cnt($user_id);
			}else{
			$ride_count = 0;
			}
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = $verified_success;
			$jsonReturn['response'] = array('user_id' => $user_id,'firstname' =>$first_name,'lastname' => $last_name,'user_image' => $image,'country_code' => $country_code,'phone_no' => $mobile_no,'flag' => $flag,'ride_count' => $ride_count,'ratings' => $rating);
			echo json_encode($jsonReturn);
		}else{
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $invalid_user_id;
			echo json_encode($jsonReturn);
		}
	}
	public function mobile_user_image_upload(){
		$user_id = $_POST['u_id'];
		$headerStringValue= apache_request_headers();
        $device_type = $headerStringValue['device_type'];
        $key = $headerStringValue['u_key'];
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$upload_image_success = $this->lang->line('upload_image_success');
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$upload_image_success = $this->lang->line('upload_image_success');
			}else{
				$this->lang->load('en', 'en');
				$upload_image_success = $this->lang->line('upload_image_success');
			}
		if($device_type == 'ios'){
			if($_FILES['u_image'] !=''){
			$uploaddir = "images/users/";
			$data = file_get_contents($_FILES['u_image']['tmp_name']);
			$image = imagecreatefromstring($data);
			$imgname=time().".jpg";
			imagejpeg($image,$uploaddir.$imgname);
			$data = array ('image' => $imgname);
			$condition = array ('id' => $user_id );
			$this->common_model->update_details (USERS,$data,$condition);
			$get_user_details = $this->common_model->get_all_details(USERS,$condition);
			$first_name = $get_user_details->row()->firstname;
			$last_name = $get_user_details->row()->lastname;
			$mobile_no = $get_user_details->row()->phone_no;
			$country_code = $get_user_details->row()->ph_country;
			if($get_user_details->row()->image !=''){
				$image = base_url ().'images/users/'.$get_user_details->row()->image;
				}else{
				$image = base_url ().'images/users/user_image.png';
			}
			$rating = $this->user_rating($user_id);
			if($rating!=''){
			$rating = $this->user_rating($user_id);
			}else{
			$rating = 0;
			}
			$ride_count = $this->user_ride_cnt($user_id);
			if($ride_count!='0'){
			$ride_count = $this->user_ride_cnt($user_id);
			}else{
			$ride_count = 0;
			}
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = $upload_image_success;
			$jsonReturn['response'] = array('user_id' => $user_id,'firstname' =>$first_name,'lastname' => $last_name,'user_image' => $image,'country_code' => $country_code,'phone_no' => $mobile_no,'ride_count' => $ride_count,'ratings' => $rating);
			echo json_encode($jsonReturn);
		}
		}
		else{
			$img = $_POST['u_image'];
			$data = 'data:image/jpg;base64,'.$img.'';
			$data = str_replace('data:image/jpg;base64,', '', $data);
			$img_str = str_replace(' ', '+', $data);
			$data1 = base64_decode($img_str);
			$imgname=time().".jpg";
			$file = 'images/users/'.$imgname;
			$success = file_put_contents($file, $data1);
			$data = array ('image' => $imgname);
			$condition = array ('id' => $user_id );
			$this->common_model->update_details (USERS,$data,$condition);
			$get_user_details = $this->common_model->get_all_details(USERS,$condition);
			$first_name = $get_user_details->row()->firstname;
			$last_name = $get_user_details->row()->lastname;
			$mobile_no = $get_user_details->row()->phone_no;
			$country_code = $get_user_details->row()->ph_country;
			if($get_user_details->row()->image !=''){
				$image = base_url ().'images/users/'.$get_user_details->row()->image;
				}else{
				$image = base_url ().'images/users/user_image.png';
			}
			$rating = $this->user_rating($user_id);
			if($rating!=''){
			$rating = $this->user_rating($user_id);
			}else{
			$rating = 0;
			}
			$ride_count = $this->user_ride_cnt($user_id);
			if($ride_count!='0'){
			$ride_count = $this->user_ride_cnt($user_id);
			}else{
			$ride_count = 0;
			}
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = $upload_image_success;
			$jsonReturn['response'] = array('user_id' => $user_id,'firstname' =>$first_name,'lastname' => $last_name,'user_image' => $image,'country_code' => $country_code,'phone_no' => $mobile_no,'ride_count' => $ride_count,'ratings' => $rating);
			echo json_encode($jsonReturn);
			
		}
	}
	public function mobile_emergency_user(){
		$user_id = urldecode($_POST['u_id']);
		$username = urldecode($_POST['u_name']);
		$country_code = $_POST['u_country_code'];
		$flag = urldecode($_POST['u_flag']);
		$mobile_no = urldecode($_POST['u_mobile_no']);
		$headerStringValue= apache_request_headers();
        $device_type = $headerStringValue['device_type'];
        $key = $headerStringValue['u_key'];
        $lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$emergency_success = $this->lang->line('emergency_success');
				$exist_user = $this->lang->line('exist_user');
				$mobile_user_no = $this->lang->line('mobile_user_no');
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$emergency_success = $this->lang->line('emergency_success');
				$exist_user = $this->lang->line('exist_user');
				$mobile_user_no = $this->lang->line('mobile_user_no');
			}else{
				$this->lang->load('en', 'en');
				$emergency_success = $this->lang->line('emergency_success');
				$exist_user = $this->lang->line('exist_user');
				$mobile_user_no = $this->lang->line('mobile_user_no');
			}
		$get_user_table = $this->common_model->get_all_details(USERS,array('id' => $user_id));
		$phone_no = $get_user_table->row()->phone_no;
		if($phone_no == $mobile_no){
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $mobile_user_no;
			echo json_encode($jsonReturn);
		}else{
			$get_user_details = $this->common_model->get_all_details(EMERGENCY_CONTACT,array('mobile_no' => $mobile_no,'user_id' => $user_id));
			if($get_user_details->num_rows() =='0'){
			if($device_type == 'ios'){
				if($_FILES['u_image'] != ''){
				$uploaddir = "images/temp_users/";
				$data = file_get_contents($_FILES['u_image']['tmp_name']);
				$image = imagecreatefromstring($data);
				$imgname=time().".jpg";
				imagejpeg($image,$uploaddir.$imgname);
				}
				$dataArr = array('user_id' => $user_id,'username' => $username,'country_code' => $country_code,'mobile_no' => $mobile_no,'user_flag' => $flag,'contact_image' => $imgname,'mobile_key' => $key);
				$this->common_model->simple_insert(EMERGENCY_CONTACT,$dataArr);
				$jsonReturn['status'] = '1';
				$jsonReturn['message'] = $emergency_success;
				echo json_encode($jsonReturn);
			}else{
				$img = $_POST['u_image'];
				$data = 'data:image/jpg;base64,'.$img.'';
				$data = str_replace('data:image/jpg;base64,', '', $data);
				$img_str = str_replace(' ', '+', $data);
				$data1 = base64_decode($img_str);
				$imgname=time().".jpg";
				$file = 'images/temp_users/'.$imgname;
				$success = file_put_contents($file, $data1);
				$dataArr = array('user_id' => $user_id,'username' => $username,'country_code' => $country_code,'mobile_no' => $mobile_no,'user_flag' => $flag,'contact_image' => $imgname,'mobile_key' => $key);
				$this->common_model->simple_insert(EMERGENCY_CONTACT,$dataArr);
				$jsonReturn['status'] = '1';
				$jsonReturn['message'] = $emergency_success;
				echo json_encode($jsonReturn);
			}
			}
		else{
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $exist_user;
			echo json_encode($jsonReturn);
		}
		}
		
	}
	public function mobile_get_emergency_user(){
		$user_id = urldecode($_POST['u_id']);
		$headerStringValue= apache_request_headers();
		 $lang = $headerStringValue['lang'];
		 if($lang == 'km'){
				$this->lang->load('km', $lang);
				$emergency_contact_details = $this->lang->line('emergency_contact_details');
				$contact_empty = $this->lang->line('contact_empty');
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$emergency_contact_details = $this->lang->line('emergency_contact_details');
				$contact_empty = $this->lang->line('contact_empty');
			}else{
				$this->lang->load('en', 'en');
				$emergency_contact_details = $this->lang->line('emergency_contact_details');
				$contact_empty = $this->lang->line('contact_empty');
			}
		$get_user_details = $this->common_model->get_all_details(EMERGENCY_CONTACT,array('user_id' => $user_id));
		if($get_user_details->num_rows() > 0){
			foreach($get_user_details->result() as $row){
			if($row->contact_image !=''){
				$image = base_url ().'images/temp_users/'.$row->contact_image;
				}else{
				$image = base_url ().'images/users/user_image.png';
			}
			$jsonReturn[] = array('id' => $row->id,'user_id' =>$row->user_id,'username' => $row->username,'country_code' => $row->country_code,'phone_no' => $row->mobile_no,'image' => $image,'flag' => $row->user_flag);
			}
			$status = '1';
			$message = $emergency_contact_details;
			$json_encode = json_encode(array("status" => $status,"message" => $message ,"response"=>$jsonReturn));
			echo $json_encode;

		}else{
			$status = '0';
			$message = $contact_empty;
			$json_encode = json_encode(array("status" => $status,"message" => $message ,"response"=>array()));
			echo $json_encode;
		}
		
	}
	public function mobile_get_emergency_details(){

		$id = urldecode($_POST['id']);
		$headerStringValue= apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$emergency_user = $this->lang->line('emergency_user');
				$invalid_user_id = $this->lang->line('invalid_user_id');
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$emergency_user = $this->lang->line('emergency_user');
				$invalid_user_id = $this->lang->line('invalid_user_id');
			}else{
				$this->lang->load('en', 'en');
				$emergency_user = $this->lang->line('emergency_user');
				$invalid_user_id = $this->lang->line('invalid_user_id');
			}
		$get_emergency_details = $this->common_model->get_all_details(EMERGENCY_CONTACT,array('id' => $id));
		if($get_emergency_details->num_rows() > 0){
			$user_id = $get_emergency_details->row()->user_id;
			$user_name = $get_emergency_details->row()->username;
			$image = $get_emergency_details->row()->contact_image;
			$mobile_no = $get_emergency_details->row()->mobile_no;
			$country_code = $get_emergency_details->row()->country_code;
			if($image!=''){
				$imagename = base_url().'images/temp_users/'.$image;
			}else{
				$imagename = base_url().'images/temp_users/user_image.png';
			}
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = $emergency_user;
			$jsonReturn['response'] = array('id'=> $id,'user_id' => $user_id,'username' =>$user_name,'user_image' => $imagename,'country_code' => $country_code,'phone_no' => $mobile_no);
			echo json_encode($jsonReturn);

		}else{
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $invalid_user_id;
			echo json_encode($jsonReturn);
		}
		
	}
	public function mobile_update_emergency_user(){
		$id = urldecode($_POST['id']);
		$user_id = urldecode($_POST['u_id']);
		$username = urldecode($_POST['u_name']);
		$country_code = $_POST['u_country_code'];
		$mobile_no = urldecode($_POST['u_mobile_no']);
		$flag = urldecode($_POST['u_flag']);
		$headerStringValue= apache_request_headers();
		$lang = $headerStringValue['lang'];
		$get_user_details = $this->common_model->get_all_details(EMERGENCY_CONTACT,array('user_id' => $user_id,'mobile_no' => $mobile_no));
		$row_id = $get_user_details->row()->id;
		if($row_id == $id){
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$update_success = $this->lang->line('update_success');
				$mobile_user_no = $this->lang->line('mobile_user_no');
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$update_success = $this->lang->line('update_success');
				$mobile_user_no = $this->lang->line('mobile_user_no');
			}else{
				$this->lang->load('en', 'en');
				$update_success = $this->lang->line('update_success');
				$mobile_user_no = $this->lang->line('mobile_user_no');
			}
		if($_FILES['u_image'] != ''){
			$uploaddir = "images/temp_users/";
			$data = file_get_contents($_FILES['u_image']['tmp_name']);
			$image = imagecreatefromstring($data);
			$imgname=time().".jpg";
			imagejpeg($image,$uploaddir.$imgname);
		}
		if($_POST['u_image']!=''){
				$img = $_POST['u_image'];
				$data = 'data:image/jpg;base64,'.$img.'';
				$data = str_replace('data:image/jpg;base64,', '', $data);
				$img_str = str_replace(' ', '+', $data);
				$data1 = base64_decode($img_str);
				$imgname=time().".jpg";
				$file = 'images/temp_users/'.$imgname;
				$success = file_put_contents($file, $data1);
		}
		$image = base_url().'images/temp_users/'.$imgname;
		$condition = array('id' => $id);
		$dataArr = array('username' => $username,'country_code' => $country_code,'mobile_no' => $mobile_no,'user_flag' => $flag,'contact_image' => $imgname);
		$this->common_model->update_details(EMERGENCY_CONTACT,$dataArr,$condition);
		$jsonReturn['status'] = '1';
		$jsonReturn['message'] = $update_success;
		echo json_encode($jsonReturn);
		}
		else if($get_user_details->num_rows() == 0){
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$update_success = $this->lang->line('update_success');
				$mobile_user_no = $this->lang->line('mobile_user_no');
							
		}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$update_success = $this->lang->line('update_success');
				$mobile_user_no = $this->lang->line('mobile_user_no');
		}else{
			$this->lang->load('en', 'en');
				$update_success = $this->lang->line('update_success');
				$mobile_user_no = $this->lang->line('mobile_user_no');
		}
		if($_FILES['u_image'] != ''){
			$uploaddir = "images/temp_users/";
			$data = file_get_contents($_FILES['u_image']['tmp_name']);
			$image = imagecreatefromstring($data);
			$imgname=time().".jpg";
			imagejpeg($image,$uploaddir.$imgname);
		}
		if($_POST['u_image']!=''){
				$img = $_POST['u_image'];
				$data = 'data:image/jpg;base64,'.$img.'';
				$data = str_replace('data:image/jpg;base64,', '', $data);
				$img_str = str_replace(' ', '+', $data);
				$data1 = base64_decode($img_str);
				$imgname=time().".jpg";
				$file = 'images/temp_users/'.$imgname;
				$success = file_put_contents($file, $data1);
		}
		$image = base_url().'images/temp_users/'.$imgname;
		$condition = array('id' => $id);
		$dataArr = array('username' => $username,'country_code' => $country_code,'mobile_no' => $mobile_no,'user_flag' => $flag,'contact_image' => $imgname);
		$this->common_model->update_details(EMERGENCY_CONTACT,$dataArr,$condition);
		$jsonReturn['status'] = '1';
		$jsonReturn['message'] = $update_success;
		echo json_encode($jsonReturn);
		}else{
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $mobile_user_no;
			echo json_encode($jsonReturn);
		}

	}
	public function mobile_delete_emergency_user(){
		$id = urldecode($_POST['id']);
		$user_id = urldecode($_POST['u_id']);
		$headerStringValue= apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$delete_success = $this->lang->line('delete_success');
							
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$delete_success = $this->lang->line('delete_success');
			}else{
				$this->lang->load('en', 'en');
				$delete_success = $this->lang->line('delete_success');
			}
		
		$condition = array('id' => $id);
		$this->common_model->commonDelete(EMERGENCY_CONTACT,$condition);
		$jsonReturn['status'] = '1';
		$jsonReturn['message'] = $delete_success;
		echo json_encode($jsonReturn);
	}
	public function mobile_add_favorite_location(){
		$user_id = urldecode($_POST['u_id']);
		$lat = urldecode($_POST['u_lat']);
		$long = urldecode($_POST['u_lang']);
		$address = urldecode($_POST['u_address']);
		$title = urldecode($_POST['u_title']);
		$headerStringValue= apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$save_success = $this->lang->line('save_success');
				$exist_address = $this->lang->line('exist_address');
							
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$save_success = $this->lang->line('save_success');
				$exist_address = $this->lang->line('exist_address');
			}else{
				$this->lang->load('en', 'en');
				$save_success = $this->lang->line('save_success');
				$exist_address = $this->lang->line('exist_address');
			}
		$get_favorite_location = $this->common_model->get_all_details(FAVORITE_LOCATION,array('user_id' => $user_id,'lat' => $lat,'lang' => $long));
		if($get_favorite_location->num_rows() > 0){
			$jsonReturn['status'] ='0';
			$jsonReturn['message'] = $exist_address;
			echo json_encode($jsonReturn);
		}else{
			$dataArr = array('user_id'=> $user_id,'lat' => $lat,'lang'=> $long,'address' => $address,'title' => $title,'status' => 'Active');
			$this->common_model->simple_insert(FAVORITE_LOCATION,$dataArr);
			$jsonReturn['status'] ='1';
			$jsonReturn['message'] = $save_success;
			echo json_encode($jsonReturn);
		}
	}
	public function mobile_update_favorite_location(){
		$id = urldecode($_POST['id']);
		$user_id = urldecode($_POST['u_id']);
		$lat = urldecode($_POST['u_lat']);
		$long = urldecode($_POST['u_lang']);
		$address = urldecode($_POST['u_address']);
		$title = urldecode($_POST['u_title']);
		$headerStringValue= apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$update_success = $this->lang->line('update_success');
				$exist_address = $this->lang->line('exist_address');
							
		}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$update_success = $this->lang->line('update_success');
				$exist_address = $this->lang->line('exist_address');
		}else{
				$this->lang->load('en', 'en');
				$update_success = $this->lang->line('update_success');
				$exist_address = $this->lang->line('exist_address');
		}
		$get_favorite_location = $this->common_model->get_all_details(FAVORITE_LOCATION,array('user_id' => $user_id,'lat' => $lat,'lang' => $long));
		$row_id = $get_favorite_location->row()->id;
		if($row_id == $id){
			$condition = array('id' => $id);
			$dataArr = array('user_id'=> $user_id,'lat' => $lat,'lang'=> $long,'address' => $address,'title' => $title);
			$this->common_model->update_details(FAVORITE_LOCATION,$dataArr,$condition);
			$jsonReturn['status'] ='1';
			$jsonReturn['message'] = $update_success;
			echo json_encode($jsonReturn);
		}else if($get_favorite_location->num_rows() == '0'){
			$condition = array('id' => $id);
			$dataArr = array('user_id'=> $user_id,'lat' => $lat,'lang'=> $long,'address' => $address,'title' => $title);
			$this->common_model->update_details(FAVORITE_LOCATION,$dataArr,$condition);
			$jsonReturn['status'] ='1';
			$jsonReturn['message'] = $update_success;
			echo json_encode($jsonReturn);
		}else{
			$jsonReturn['status'] ='0';
			$jsonReturn['message'] = $exist_address;
			echo json_encode($jsonReturn);
		}
		
	}
	public function mobile_delete_favorite_location(){
		$id = urldecode($_POST['id']);
		$user_id = urldecode($_POST['u_id']);
		$headerStringValue= apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$delete_success = $this->lang->line('delete_success');
							
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$delete_success = $this->lang->line('delete_success');
			}else{
				$this->lang->load('en', 'en');
				$delete_success = $this->lang->line('delete_success');
			}
		$condition = array('id' => $id,'user_id' => $user_id);
		$this->common_model->commonDelete(FAVORITE_LOCATION,$condition);
		$jsonReturn['status'] = '1';
		$jsonReturn['message'] = $delete_success;
		echo json_encode($jsonReturn);
	}
	public function mobile_get_favorite_locations(){
		$user_id = urldecode($_POST['u_id']);
		$headerStringValue= apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$favorite_location = $this->lang->line('favorite_location');
							
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$favorite_empty = $this->lang->line('favorite_empty');
			}else{
				$this->lang->load('en', 'en');
				$favorite_empty = $this->lang->line('favorite_empty');
			}
		$condition = array('user_id' => $user_id); 
		$get_favorite_location = $this->common_model->get_all_details(FAVORITE_LOCATION,$condition);
		if($get_favorite_location->num_rows() > 0){
			foreach($get_favorite_location->result() as $row){

				$jsonReturn[] = array('id' => $row->id,'user_id' =>$row->user_id,'title' =>$row->title,'address' => $row->address,'lat' => $row->lat,'lang' => $row->lang);
			}
				$status = '1';
				$message = $favorite_location;
				$json_encode = json_encode(array("status" => $status,"message" => $message ,"response"=>$jsonReturn));
				echo $json_encode;
		}else{
				$status = '0';
				$message = $favorite_empty;
				$json_encode = json_encode(array("status" => $status,"message" => $message ,"response"=>array()));
			echo $json_encode;
		}
	}
	public function testNotification(){
		$deviceToken = $_POST['u_key'];
		$message = 'My first push notification!';
		//$this->send_ios_notification_user($deviceToken,$message);
		$this->send_ios_notification_driver($deviceToken,$message);
		$this->send_android_notification($deviceToken, $message);

	}
	public function send_ios_notification_user($deviceToken,$message,$jsondata='',$action=''){
		//CertificatesUSer.pem
		$passphrase = 'PushChat';
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', certificates.'/CertificatesUSer.pem');
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		// Open a connection to the APNS server
		$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err,  // For development
		// 'ssl://gateway.push.apple.com:2195', $err, // for production
		$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
 
		if (!$fp)
		exit("Failed to connect: $err $errstr" . PHP_EOL);
 
		//echo 'Connected to APNS' . PHP_EOL;
 
		// Create the payload body
		$body['aps'] = array(
						'alert' => trim($message),
						'sound' =>'default'
						);
		$body ['payload'] = $jsondata;
		$body ['action'] = $action;
		// Encode the payload as JSON
		$payload = json_encode($body);
 
		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', trim($deviceToken)) . pack('n', strlen($payload)) . $payload;
 
		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));
 
		if (!$result){
			//echo 'Message not delivered' . PHP_EOL;
		}
		else
		{
			//echo 'Message successfully delivered' . PHP_EOL;
		return $result;
		}
 
			// Close the connection to the server
		fclose($fp);
	}
	public function send_ios_notification_driver($deviceToken,$message,$jsondata='',$action=''){
		$passphrase = 'PushChat';
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', certificates.'/CertificatesPartner.pem');
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		// Open a connection to the APNS server
		$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err,  // For development
		// 'ssl://gateway.push.apple.com:2195', $err, // for production
		$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
 
		if (!$fp)
		exit("Failed to connect: $err $errstr" . PHP_EOL);
 
		//echo 'Connected to APNS' . PHP_EOL;
 
		// Create the payload body
		$body['aps'] = array(
						'alert' => trim($message),
						'sound' =>'default'
						);
		$body ['payload'] = $jsondata;
		$body ['action'] = $action;
		// Encode the payload as JSON
		$payload = json_encode($body);
 
		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', trim($deviceToken)) . pack('n', strlen($payload)) . $payload;
 
		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));
 
		if (!$result){
			//echo 'Message not delivered' . PHP_EOL;
		}
		else
		{
			//echo 'Message successfully delivered' . PHP_EOL;
		return $result;
		}
 
			// Close the connection to the server
		fclose($fp);
	}
	
	public function mobile_driver_login(){
		$mobile_no = urldecode($_POST['d_mobile_no']);
		$password = urldecode($_POST['d_password']);
		$country_code = $_POST['d_country_code'];
		$flag = urldecode($_POST['d_flag']);
		$pwd = md5($password);
		$headerStringValue = apache_request_headers();
        $device_type = $headerStringValue['device_type'];
        $key = $headerStringValue['d_key'];
		$lang = $headerStringValue['lang'];
		$coutry_explode = explode('+',$country_code);
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$message = $this->lang->line('login_success');
				$error = $this->lang->line('invalid_login');
				
		}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$message = $this->lang->line('login_success');
				$error = $this->lang->line('invalid_login');		
		}else{
				$this->lang->load('en', 'en');
				$message = $this->lang->line('login_success');
				$error = $this->lang->line('invalid_login');
		}
		
		$condition = array('driver_mobileno' => $mobile_no,'driver_password' => $pwd,'driver_location' => $coutry_explode[1],'is_active' => 1);
		$checkDriver = $this->common_model->get_all_details (DRIVERS, $condition);
		if ($checkDriver->num_rows() > 0) {

			if($device_type == 'android'){
			if($key != ''){
				$data = array(
					'mobile_key'=>$key,'device_type' => $device_type
					);
				$condition = array(
					'driver_mobileno'=>$mobile_no
					);
				$this->common_model->update_details(DRIVERS ,$data ,$condition);
			}
		}
		if($device_type == 'ios') {
			if($key != '') {
				$data = array(
					'mobile_key'=>$key,'device_type' => $device_type
					);
				$condition = array(
					'driver_mobileno'=>$mobile_no
					);
				$this->common_model->update_details(DRIVERS ,$data ,$condition);
			}
		}
				$id = $checkDriver->row()->id;
				$username = $checkDriver->row()->driver_name;
				$email_id = $checkDriver->row()->driver_email;
				$country = $checkDriver->row()->driver_location;
				$country_code = '+'.$country;
				$phone_no = $checkDriver->row()->driver_mobileno;
				$driver_category_id = $checkDriver->row()->driver_category;
				$vechicle_type = $checkDriver->row()->driver_vechicle_type;
				$vechicle_model = $checkDriver->row()->driver_vehicle_model;
				$vechicle_no = $checkDriver->row()->driver_vehicleno;
				$key = $checkDriver->row()->mobile_key;
			if($checkDriver->row()->driver_image != '') {
				$image = base_url ().'images/drivers/'.$checkDriver->row()->driver_image;
				
			}else{
				$image = base_url ().'images/drivers/user_image.png';
			}
		if($checkDriver->row()->vechicle_image != ''){
				$vechicle_image = base_url ().'images/vechicle_image/'.$checkDriver->row()->vechicle_image;
			}else {
				$vechicle_image = base_url ().'images/vechicle_image/QP-GranSport-Grigio-Metallo_Side.jpg';
			}
		$get_category_details = $this->common_model->get_all_details(DRIVER_CATEGORIES,array('id' =>$driver_category_id));
		$driver_category = $get_category_details->row()->category_name;
		$get_vechicle_type = $this->common_model->get_all_details(MODELLIST,array('id' => $vechicle_type));
		$model = $get_vechicle_type->row()->model_name;
		$jsonReturn['status'] = '1';
		$jsonReturn['message'] = $message;
		$jsonReturn['response'] = array('id' =>$id,'drivername'=>$username,'country_code' => $country_code,'phone_no' => $phone_no,'diver_image' => $image,'driver_ratings' => '0','category_name' => $driver_category ,'vechicle_model' => $vechicle_model,'vechicle_no' => $vechicle_no,'vechicle_image' => $vechicle_image,'flag' => $flag);
		echo json_encode($jsonReturn);
	}
	else{
		$jsonReturn['status'] = '0';
		$jsonReturn['message'] = $error;
		echo json_encode($jsonReturn);
	}
}
public function mobile_logout_driver(){
	$driver_id = urldecode($_POST['d_id']);
	$headerStringValue = apache_request_headers();
	$lang = $headerStringValue['lang'];
	if($lang == 'km'){
				$this->lang->load('km', $lang);
				$logout_success = $this->lang->line('logout_success');
				$invalid_driverid = $this->lang->line('invalid_driverid');
				
		}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$logout_success = $this->lang->line('logout_success');
				$invalid_driverid = $this->lang->line('invalid_driverid');
		}else{
			$this->lang->load('en', 'en');
			$logout_success = $this->lang->line('logout_success');
			$invalid_driverid = $this->lang->line('invalid_driverid');
		}
	$get_driver_details = $this->common_model->get_all_details(DRIVERS,$condition);
	if($get_driver_details->num_rows() > 0){
		$jsonReturn['status'] = '1';
		$jsonReturn['message'] = $logout_success;
		echo json_encode($jsonReturn);
	}
	else{
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $invalid_driverid;
			echo json_encode($jsonReturn);
		}
}
public function mobile_driver_forgot_password(){
	$mobile_no = urldecode($_POST['d_mobile_no']);
	$country_code = $_POST['d_country_code'];
	$flag = urldecode($_POST['d_flag']);
	$headerStringValue = apache_request_headers();
	$lang = $headerStringValue['lang'];
	if($lang == 'km'){
				$this->lang->load('km', $lang);
				$mobile_number_reg = $this->lang->line('mobile_number_reg');
				$verify_code = $this->lang->line('verify_code');
				
		}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$mobile_number_reg = $this->lang->line('mobile_number_reg');
				$verify_code = $this->lang->line('verify_code');
		}else{
				$this->lang->load('en', 'en');
				$mobile_number_reg = $this->lang->line('mobile_number_reg');
				$verify_code = $this->lang->line('verify_code');
		}
	$explode_country = explode('+',$country_code);
	$condition = array('driver_mobileno' => $mobile_no,'driver_location'=>$explode_country[1]);
	$get_driver_details = $this->common_model->get_all_details(DRIVERS,$condition);
	if($get_driver_details->num_rows() > 0){
		$username = $get_driver_details->row()->driver_name;
		$mobile_no = $get_driver_details->row()->driver_mobileno;
		$country = $get_driver_details->row()->driver_location;
		$driver_id = $get_driver_details->row()->id;
		$country_code = '+'.$country;
		require_once('twilio/Services/Twilio.php');
		$otp = mt_rand(100000, 999999);
		$account_sid = 'AC0632d53f15fc64c4b34dcc5cabca584c'; 
		$auth_token = '0edc5359ac6d4232e445a4cbc266d569';
		$to=$country_code.''.$mobile_no;
		$client = new Services_Twilio($account_sid,$auth_token);
		$client->account->messages->create(array( 
		'From' => "8444523943",
		'To' => $to,	
		'Body' => $otp_message.$otp));
		$jsonReturn['status'] = '1';
		$jsonReturn['message'] = $verify_code;
		$jsonReturn['response'] = array('driver_id' => $driver_id,'drivername'=>$username,'country_code' => $country_code,'phone_no' => $mobile_no,'mobile_verification_code' => $otp);
		echo json_encode($jsonReturn);
		}
		else{
		$jsonReturn['status'] = '0';
		$jsonReturn['message'] = $mobile_number_reg;
		echo json_encode($jsonReturn);
	 }
}
  public function mobile_driver_forgot_password_confirmation(){
  		$mobile_no = urldecode($_POST['d_mobile_no']);
  		$country_code = $_POST['d_country_code'];
  		$flag = urldecode($_POST['d_flag']);
		$forgot_otp = $_POST['d_otp'];
		$headerStringValue = apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$verified_success = $this->lang->line('verified_success');
				$wrong_number = $this->lang->line('wrong_number');
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$verified_success = $this->lang->line('verified_success');
				$wrong_number = $this->lang->line('wrong_number');
			}else{
				$this->lang->load('en', 'en');
				$verified_success = $this->lang->line('verified_success');
				$wrong_number = $this->lang->line('wrong_number');
			}
		$explode_country = explode('+',$country_code);
		$condition = array('driver_mobileno' => $mobile_no,'driver_location'=>$explode_country[1]);
		$data = array('mobile_verification_code' => $forgot_otp);
		$this->common_model->update_details(DRIVERS,$data,$condition);
		$get_driver_details = $this->common_model->get_all_details(DRIVERS,$condition);
		if($get_driver_details->num_rows() > 0){
			$mobile_no = $get_driver_details->row()->driver_mobileno;
			$user_name = $get_driver_details->row()->driver_name;
			$driver_id = $get_driver_details->row()->id;
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = $verified_success;
			$jsonReturn['response'] = array('driver_id' => $driver_id,'drivername' => $user_name,'phone_no' => $mobile_no,'country_code' => $country_code,'flag' => $flag);
			echo json_encode($jsonReturn);
		}
		else {
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $wrong_number;
			echo json_encode($jsonReturn);
		}
  }
  public function mobile_driver_change_password(){
  		$current_pwd = urldecode($_POST['d_password']);
		$new_pwd = urldecode($_POST['d_new_password']);
		$driver_id = urldecode($_POST['d_id']);
		$headerStringValue = apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$password_change_success = $this->lang->line('password_change_success');
				$invalid_driverid = $this->lang->line('invalid_driverid');
				
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$password_change_success = $this->lang->line('password_change_success');
				$invalid_driverid = $this->lang->line('invalid_driverid');
			}else{
				$this->lang->load('en', 'en');
				$password_change_success = $this->lang->line('password_change_success');
				$invalid_driverid = $this->lang->line('invalid_driverid');
			}
		$old_password = md5($current_pwd);
		$condition = array('id' => $driver_id);
		$get_driver_details = $this->common_model->get_all_details(DRIVERS,$condition);
		if($get_driver_details->num_rows() > 0){
			$pwd = md5($new_pwd);
			$dataArr = array('driver_password' => $pwd);
			$condition = array('id' => $driver_id,'driver_password' => $old_password);
			$this->common_model->update_details(DRIVERS,$dataArr,$condition);
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = $password_change_success;
			echo json_encode($jsonReturn);
		}else{
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $invalid_driverid;
			echo json_encode($jsonReturn);
		}
  }
  public function mobile_driver_reset_password(){
		$driver_id = urldecode($_POST['d_id']);
		$password = urldecode($_POST['d_password']);
		$headerStringValue = apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$password_reset_success = $this->lang->line('password_reset_success');
				$invalid_driverid = $this->lang->line('invalid_driverid');
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$password_reset_success = $this->lang->line('password_reset_success');
				$invalid_driverid = $this->lang->line('invalid_driverid');
			}else{
				$this->lang->load('en', 'en');
				$password_reset_success = $this->lang->line('password_reset_success');
				$invalid_driverid = $this->lang->line('invalid_driverid');
			}
		$cond = array('id' => $driver_id);
		$get_driver_details = $this->common_model->get_all_details(DRIVERS,$cond);
		if($get_driver_details->num_rows() > 0){
			$pwd = md5($password);
			$dataArr = array('driver_password' => $pwd);
			$cond = array('id' => $driver_id);
			$this->common_model->update_details(DRIVERS,$dataArr,$cond);
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = $password_reset_success;
			echo json_encode($jsonReturn);
		}
		else{
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $invalid_driverid;
			echo json_encode($jsonReturn);
		}
	}

 public function mobile_driver_reset_password_confirmation(){
 	$mobile_no = urldecode($_POST['d_mobile_no']);
 	$country_code = $_POST['d_country_code'];
	$flag = urldecode($_POST['d_flag']);
	$new_pwd = urldecode($_POST['d_password']);
	$otp = urldecode($_POST['d_otp']);
	$headerStringValue = apache_request_headers();
	$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$password_change_success = $this->lang->line('password_change_success');
				$wrong_number = $this->lang->line('wrong_number');
				
			}else if($lang ==  'en'){
				$this->lang->load('en', $lang);
				$password_change_success = $this->lang->line('password_change_success');
				$wrong_number = $this->lang->line('wrong_number');
			}else{
				$this->lang->load('en', 'en');
				$password_change_success = $this->lang->line('password_change_success');
				$wrong_number = $this->lang->line('wrong_number');
			}
	$explode_country = explode('+',$country_code);
	$condition = array('driver_mobileno' => $mobile_no,'driver_location' => $explode_country[1]);
	$get_driver_details = $this->common_model->get_all_details(DRIVERS,$condition);
	if($get_driver_details->num_rows() > 0){
			$pwd = md5($new_pwd);
			$dataArr = array('driver_password' => $pwd,'mobile_verification_code' => $otp);
			$condition = array('driver_mobileno' => $mobile_no);
			$this->common_model->update_details(DRIVERS,$dataArr,$condition);
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = $password_change_success;
			echo json_encode($jsonReturn);
	}else{
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $wrong_number;
			echo json_encode($jsonReturn);
	}
 }
  public function mobile_driver_profile_update(){
		$drivername = urldecode($_POST['d_name']);
		$driver_id  = urldecode($_POST['d_id']);
		$headerStringValue = apache_request_headers();
		$lang = $headerStringValue['lang'];
		$device_type = $headerStringValue['device_type'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$driver_profile_update = $this->lang->line('driver_profile_update');
				$invalid_driverid = $this->lang->line('invalid_driverid');
				
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$driver_profile_update = $this->lang->line('driver_profile_update');
				$invalid_driverid = $this->lang->line('invalid_driverid');
			}else{
				$this->lang->load('en', 'en');
				$driver_profile_update = $this->lang->line('driver_profile_update');
				$invalid_driverid = $this->lang->line('invalid_driverid');
			}
		$condition = array('id' => $driver_id);
		$get_driver_details = $this->common_model->get_all_details(DRIVERS,$condition);
		$mobile_no = $get_driver_details->row()->driver_mobileno;
		$vechicle_no = $get_driver_details->row()->driver_vehicleno;
		$vechicle_model = $get_driver_details->row()->driver_vehicle_model;
		$vechicle_type = $get_driver_details->row()->driver_vechicle_type;
		$country = $get_driver_details->row()->driver_location;
		$flag = $get_driver_details->row()->driver_flag;
		$country_code = '+'.$country;
		$driver_email = $get_driver_details->row()->driver_email;
		$driver_category_id = $get_driver_details->row()->driver_category;
		$get_category_details = $this->common_model->get_all_details(DRIVER_CATEGORIES,array('id' =>$driver_category_id));
		if($get_driver_details->num_rows() > 0){
			if($_FILES['d_image'] !=''){
					$uploaddir = "images/drivers/";
					$data = file_get_contents($_FILES['d_image']['tmp_name']);
					$image1 = imagecreatefromstring($data);
					$imgname=time().".jpg";
					imagejpeg($image1,$uploaddir.$imgname);
			}else if($_POST['d_image']!=''){
					$img = $_POST['d_image'];
					$data = 'data:image/jpg;base64,'.$img.'';
					$data = str_replace('data:image/jpg;base64,', '', $data);
					$img_str = str_replace(' ', '+', $data);
					$data1 = base64_decode($img_str);
					$imgname=time().".jpg";
					$file = 'images/drivers/'.$imgname;
					$success = file_put_contents($file, $data1);
			}
			if($_FILES['vechicle_image'] !=''){
					$uploaddir = "images/vechicle_image/";
					$data = file_get_contents($_FILES['vechicle_image']['tmp_name']);
					$image2 = imagecreatefromstring($data);
					$imgname1=time().".jpg";
					imagejpeg($image2,$uploaddir.$imgname1);
			}else if($_POST['vechicle_image']!=''){
					$img = $_POST['vechicle_image'];
					$data = 'data:image/jpg;base64,'.$img.'';
					$data = str_replace('data:image/jpg;base64,', '', $data);
					$img_str = str_replace(' ', '+', $data);
					$data1 = base64_decode($img_str);
					$imgname1=time().".jpg";
					$file = 'images/vechicle_image/'.$imgname;
					$success = file_put_contents($file, $data1);
			}
			$image = base_url ().'images/drivers/'.$imgname;
			$vechicle_image = base_url ().'images/vechicle_image/'.$imgname1;
			$dataArr = array('driver_name' => $drivername,'driver_image' => $imgname,'vechicle_image' => $imgname1);
			$this->common_model->update_details(DRIVERS, $dataArr, $condition);
			$category_name = $get_category_details->row()->category_name;
			$get_vechicle_type = $this->common_model->get_all_details(MODELLIST,array('id' => $vechicle_type));
			$model = $get_vechicle_type->row()->model_name;
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = $driver_profile_update;
			$jsonReturn['response'] = array('id' => $driver_id,'drivername' => $drivername,
				'emailid' => $driver_email,'driver_image' => $image,'country_code' => $country_code
				,'phone_no' => $mobile_no,'flag'=> $flag,'category_name' => $category_name,'vechicle_model' => $vechicle_model,'vechicle_no' => $vechicle_no,'vechicle_image' => $vechicle_image,
				'driver_ratings' => '0');
			echo json_encode($jsonReturn);
		}
		else{
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $invalid_driverid;
			echo json_encode($jsonReturn);
		}
	}

	public function mobile_category_list(){
		$headerStringValue = apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$category_list = $this->lang->line('category_list');
							
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$category_list = $this->lang->line('category_list');
			}else{
				$this->lang->load('en', 'en');
				$category_list = $this->lang->line('category_list');
			}
		$condition = array();
		$get_category_type = $this->common_model->get_all_details(DRIVER_CATEGORIES,$condition);
		foreach($get_category_type->result() as $row){
			
				$image = base_url ().'uploads/driver_category/'.$row->category_image;

				$jsonReturn[] = array('category_id' => $row->id,'category_name' => $row->category_name,'seat_capacity' => $row->seat_capacity,'per_kilometer' =>$row->far_perkilo,'category_image' => $image,'minimum_far' => $row->minimum_far,'charge_per_minute' => $row->timing_far,'currency_type' => 'KHR');
			}
			$status = '1';
			$message = $category_list;
			$json_encode = json_encode(array("status" => $status,"message" => $message ,"response"=>$jsonReturn));
			echo $json_encode;
	}
	public function mobile_user_estimationcost(){
		$user_id = urldecode($_POST['u_id']);
		$pickup_address = urldecode($_POST['u_pickup_address']);
		$pickup_lat = urldecode($_POST['u_pickup_lat']);
		$pickup_long = urldecode($_POST['u_pickup_long']);
		$drop_address = urldecode($_POST['u_drop_address']);
		$drop_lat = urldecode($_POST['u_drop_lat']);
		$drop_long = urldecode($_POST['u_drop_long']);
		$category_id = urldecode($_POST['category_id']);
		$headerStringValue = apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$user_estimation = $this->lang->line('user_estimation');
							
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$user_estimation = $this->lang->line('user_estimation');
			}else{
				$this->lang->load('en', 'en');
				$user_estimation = $this->lang->line('user_estimation');
			}
		$condition = array('id' => $category_id);
		$get_category_type = $this->common_model->get_all_details(DRIVER_CATEGORIES,$condition);
		$category_name = $get_category_type->row()->category_name;
		$catetory_price = $get_category_type->row()->far_perkilo;
		$geocode = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$pickup_lat.'+'.$pickup_long.'&destinations='.$drop_lat.'+'.$drop_long.'&key=AIzaSyAPG1EM5hpOkE3ZnGeT6EqBKd4xI1cQrac');
		$json = json_decode($geocode);
		$distance = $json->{'rows'}[0]->{'elements'}[0]->{'distance'}->{'text'};
		$duration = $json->{'rows'}[0]->{'elements'}[0]->{'duration'}->{'text'};
		$price_cal = $distance * $catetory_price;
		$min_price = round($price_cal);
		if($get_category_type->row()->category_name == 'Motorbike'){
			if($min_price <= 10){
				$max_price = $min_price + 2;
			}else if($min_price >= 10 && $min_price <= 20){
				$max_price = $min_price + 3;
			}else if($min_price >= 20 && $min_price <= 50){
				$max_price = $min_price + 4;
			}else if($min_price >= 50 && $min_price <= 100){
				$max_price = $min_price + 5;
			}else if($min_price >= 100){
				$max_price = $min_price + 6;
			}
		}
		else if($get_category_type->row()->category_name == 'Tuk Tuk'){
			if($min_price <= 10){
				$max_price = $min_price + 3;
			}else if($min_price >= 10 && $min_price <= 20){
				$max_price = $min_price + 4;
			}else if($min_price >= 20 && $min_price <= 50){
				$max_price = $min_price + 5;
			}else if($min_price >= 50 && $min_price <= 100){
				$max_price = $min_price + 6;
			}else if($min_price >= 100){
				$max_price = $min_price + 8;
			}
		}
		else if($get_category_type->row()->category_name == 'Taxi'){
			if($min_price <= 10){
				$max_price = $min_price + 4;
			}else if($min_price >= 10 && $min_price <= 20){
				$max_price = $min_price + 5;
			}else if($min_price >= 20 && $min_price <= 50){
				$max_price = $min_price + 6;
			}else if($min_price >= 50 && $min_price <= 100){
				$max_price = $min_price + 7;
			}else if($min_price >= 100){
				$max_price = $min_price + 10;
			}
		}
		else if($get_category_type->row()->category_name == 'Suv'){
			if($min_price <= 10){
				$max_price = $min_price + 5;
			}else if($min_price >= 10 && $min_price <= 20){
				$max_price = $min_price + 6;
			}else if($min_price >= 20 && $min_price <= 50){
				$max_price = $min_price + 7;
			}else if($min_price >= 50 && $min_price <= 100){
				$max_price = $min_price + 8;
			}else if($min_price >= 100){
				$max_price = $min_price + 12;
			}
		}
		//$max_price = $min_price + 1;
		$jsonReturn['status'] = '1';
		$jsonReturn['message'] = $user_estimation;
		$jsonReturn['response'] = array('id' =>$user_id,'category_name' => $category_name,'pickup_address' => $pickup_address,'pickup_lat' => $pickup_lat,'pickup_long' => $pickup_long,'drop_address' => $drop_address,'drop_lat' => $drop_lat,'drop_long' => $drop_long,'distance' => $distance,'duration' => $duration,'min_price' => $min_price,'max_price' =>$max_price,'currency_type' => 'KHR');
		echo json_encode($jsonReturn);
	}
	public function mobile_user_category_manage(){
		$lat = urldecode($_POST['u_lat']);
		$long = urldecode($_POST['u_long']);
		$user_id = urldecode($_POST['u_id']);
		$city = urldecode($_POST['address']);
		$headerStringValue = apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$user_category_list = $this->lang->line('user_category_list');
				$service_not_available = $this->lang->line('service_not_available');
							
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$user_category_list = $this->lang->line('user_category_list');
				$service_not_available = $this->lang->line('service_not_available');
			}else{
				$this->lang->load('en', 'en');
				$user_category_list = $this->lang->line('user_category_list');
				$service_not_available = $this->lang->line('service_not_available');
			}
		$get_region = $this->common_model->get_all_details(DRIVERS,array(''));
		$region = $get_region->row()->region;
        $sql ="SELECT d.id as driver_id,d.driver_name,dc.id,dc.category_name
        ,dc.category_image,dc.seat_capacity,dc.far_perkilo,dc.timing_far,dc.minimum_far,
        ACOS( SIN( RADIANS( d.driver_lat ) ) * SIN( RADIANS( ".$lat." ) ) + COS( RADIANS( d.driver_lat ) ) * COS( RADIANS( ".$lat." )) * COS( RADIANS( d.driver_long ) - RADIANS( ".$long." )) ) * 5000 AS `distance`
        FROM `drivers` as d left join driver_categories as dc on d.driver_category=dc.id
        WHERE ACOS( SIN( RADIANS( d.driver_lat ) ) * SIN( RADIANS( ".$lat." ) ) + COS( RADIANS( d.driver_lat ) ) * COS( RADIANS( ".$lat." )) * COS( RADIANS( d.driver_long ) - RADIANS(  ".$long." )) ) * 5000 < ".$region."
        and d.driver_online='yes'";

		$query = $this->db->query($sql);
		if($query->num_rows() > 0){
			foreach($query->result() as $row){
			$category_image = base_url().'uploads/driver_category/'.$row->category_image;
			$jsonReturn[] = array('category_id' =>$row->id ,'category_name' => $row->category_name,'seat_capacity' => $row->seat_capacity,'minimum_far' =>$row->minimum_far,'per_kilometer' =>$row->far_perkilo,'charge_per_minute' => $row->timing_far,'category_image' => $category_image,'currency_type' => 'KHR');
		}
		$status = '1';
		$message = $user_category_list;
		$json_encode = json_encode(array("status" => $status,"message" => $message ,"response"=>$jsonReturn));
		echo $json_encode;

		}else{
			$status = '0';
			$message = $service_not_available;
			$json_encode = json_encode(array("status" => $status,"message" => $message));
			echo $json_encode;
		}
		
	}
	public function mobile_user_getcategory(){
		$lat = urlencode($_POST['u_lat']);
		$long = urlencode($_POST['u_long']);
		$category_id = urlencode($_POST['category_id']);
		$user_id = urlencode($_POST['u_id']);
		$headerStringValue = apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$user_category_list = $this->lang->line('user_category_list');
										
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$user_category_list = $this->lang->line('user_category_list');	
			}else{
				$this->lang->load('en', 'en');
				$user_category_list = $this->lang->line('user_category_list');	
			}
		$get_region = $this->common_model->get_all_details(DRIVERS,array(''));
		$region = $get_region->row()->region;
		$sql = "SELECT `id`,`driver_category`,`driver_lat`,`driver_long`,`driver_rotat`, ACOS( SIN( RADIANS( `driver_lat` ) ) * SIN( RADIANS( ".$lat." ) ) + COS( RADIANS( `driver_lat` ) ) * COS( RADIANS( ".$lat." )) * COS( RADIANS( `driver_long` ) - RADIANS(".$long.")) ) * 5000 AS `distance` FROM `drivers` WHERE ACOS( SIN( RADIANS( `driver_lat` ) ) * SIN( RADIANS( ".$lat." ) ) + COS( RADIANS( `driver_lat` ) ) * COS( RADIANS( ".$lat." )) * COS( RADIANS( `driver_long` ) - RADIANS( ".$long.")) ) * 5000 < ".$region." and driver_online = 'yes' and driver_category = ".$category_id." ORDER BY `distance`";
		$query = $this->db->query($sql);
		$results = $query->result();
		$latitude =  $results[0]->driver_lat;
		$longitude = $results[0]->driver_long;
		$distance = $results[0]->distance;
		$category_name = $results[0]->driver_category;
		$condition = array("id" =>$category_name);
		$get_category_type = $this->common_model->get_all_details(DRIVER_CATEGORIES,$condition);
		$category_name = $get_category_type->row()->category_name;
		$min_distance_results = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$lat.'+'.$long.'&destinations='.$latitude.'+'.$longitude.'&key=AIzaSyAPG1EM5hpOkE3ZnGeT6EqBKd4xI1cQrac');
		$json_data = json_decode($min_distance_results);
		$mintues = $json_data->{'rows'}[0]->{'elements'}[0]->{'duration'}->{'text'};
		foreach($results as $row){
			$geocode = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$lat.'+'.$long.'&destinations='.$row->driver_lat.'+'.$row->driver_long.'&key=AIzaSyAPG1EM5hpOkE3ZnGeT6EqBKd4xI1cQrac');
				$json_results = json_decode($geocode);
				$newAddress = $json_results->{'rows'}[0]->{'elements'};
				foreach($newAddress as $rows){
					$duration =$rows->duration->text;
					$jsonReturn[] = array('category_id' => $category_id,'lat' =>$row->driver_lat,'long' => $row->driver_long,'distance' => $row->distance,'minutes' =>$duration,'rotation' => $row->driver_rotat);
				}
		}
		$status = '1';
		$message = $user_category_list;
		$cat_name = $category_name;
		$min_distance = $mintues;
		$json_encode = json_encode(array("status" => $status,"message" => $message ,"category_name" => $cat_name,"min_distance" =>$mintues,"response"=>$jsonReturn));
		echo $json_encode;
		
	}
	public function mobile_user_trip_history(){
		$user_id = urldecode($_POST['u_id']);
		$headerStringValue = apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$user_trip_history = $this->lang->line('user_trip_history');
				$no_trips = $this->lang->line('no_trips');					
		}else if($lang == 'en'){
			$this->lang->load('en', $lang);
			$user_trip_history = $this->lang->line('user_trip_history');
			$no_trips = $this->lang->line('no_trips');
		}else{
			$lang = 'en';
			$this->lang->load('en', 'en');
			$user_trip_history = $this->lang->line('user_trip_history');
			$no_trips = $this->lang->line('no_trips');
		}
		$condition = array('user_id' => $user_id,'cancelled_status' => 'no');
		$sortArr = array('request_time' => 'ASC');
		$query = 'SELECT *  FROM ride_details where user_id="'.$user_id.'" and cancelled_status = "no" order by request_time desc';
		$get_trip_details = $this->db->query($query);
		if($get_trip_details->num_rows() > 0){
			foreach($get_trip_details->result() as $row){
				$driver_id = $row->driver_id;
				$user_id = $row->user_id;
				$trip_id = $row->ride_no;
				$get_vechicle_model = $this->common_model->get_all_details(DRIVERS,array('id' => $driver_id));
				$get_user_details = $this->common_model->get_all_details(USERS,array('id' => $user_id));
				$payby = $row->payby;
				$date_explode = explode(' ',$row->request_time);
				$time = date("g:i a", strtotime($date_explode[1]));
				$Date = date("d-m-Y", strtotime($date_explode[0]));
				$price = $row->price;
				if($price!='0'){
					$amount = $price;
				}else{
					$amount = '0';
				}
				$vechicle_model = $get_vechicle_model->row()->driver_vehicle_model;
				if($vechicle_model!=''){
					$vechicle_name = $this->common_model->get_all_details(MODELLIST,array('id' => $vechicle_model));
					$vechicle_type = $vechicle_name->row()->model_name;
				}else{
					$vechicle_model = "";
				}
				if($get_vechicle_model->row()->driver_image != ''){
					$dirver_image = base_url().'images/drivers/'.$get_vechicle_model->row()->driver_image;
				}else{
					$dirver_image = base_url().'images/drivers/user_image.png';
				}
				if($row->status == 'Booked'){
					$lang_status = $this->lang->line('response_booked');
				}else if($row->status == 'Confirmed'){
					$lang_status = $this->lang->line('confirmed');
				}else if($row->status == 'Onride'){
					$lang_status = $this->lang->line('onride');
				}else if($row->status == 'Pending'){
					$lang_status = $this->lang->line('pending');
				}else if($row->status == 'Completed'){
					$lang_status = $this->lang->line('completed');
				}else if($row->status == 'Cancelled'){
					$lang_status = $this->lang->line('cancelled');
				}else if($row->status == 'Arrived'){
					$lang_status = $this->lang->line('response_arrived');
				}
				$status   = $row->status;
				if($row->status == 'Pending' || $row->status == 'Completed'){
					$jsonReturn[] = array('trip_id' =>$trip_id,'pickup_address' => $row->pickup_address,'pickup_lat' => $row->pickup_lat,'pickup_long' =>$row->pickup_long,'drop_address' => $row->drop_address,'drop_lat' => $row->drop_lat,'drop_long' => $row->drop_long,'date' => $Date,'time' => $time,'vechicle_model' => $vechicle_type,'amount' =>$amount,'payby' => $payby,'currency_type' => 'KHR','lang_status'=>$lang_status,'status' => $status,'driver_image'=>$dirver_image);

				}else{
					$jsonReturn[] = array('trip_id' =>$trip_id,'pickup_address' => $row->pickup_address,'pickup_lat' => $row->pickup_lat,'pickup_long' =>$row->pickup_long,'drop_address' => '','drop_lat' => '','drop_long' => '','date' => $Date,'time' => $time,'vechicle_model' => $vechicle_type,'amount' =>$amount,'currency_type' => 'KHR','lang_status'=>$lang_status,'status' =>$status,'driver_image'=>$dirver_image);
				}
				
			}
			$status = '1';
			$message = $user_trip_history;
			$json_encode = json_encode(array("status" => $status,"message" => $message ,"response"=>$jsonReturn));
			echo $json_encode;
		}else{
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $no_trips;
			echo json_encode($jsonReturn);
		}
	}
	public function mobile_user_trip_details(){
		$user_id = urldecode($_POST['u_id']);
		$trip_id = urldecode($_POST['trip_id']);
		$headerStringValue = apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$user_trip_details = $this->lang->line('user_trip_details');
				$no_trips = $this->lang->line('no_trips');
										
			}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$user_trip_details = $this->lang->line('user_trip_details');
				$no_trips = $this->lang->line('no_trips');
			}else{
				$lang = 'en';
				$this->lang->load('en', 'en');
				$user_trip_details = $this->lang->line('user_trip_details');
				$no_trips = $this->lang->line('no_trips');
			}
		$condition = array('user_id' => $user_id,'ride_no' => $trip_id);
		$get_user_trip_details = $this->common_model->get_all_details('ride_details',$condition);
		$pickup_address = $get_user_trip_details->row()->pickup_address;
		$drop_address = $get_user_trip_details->row()->drop_address;
		$pickup_lat = $get_user_trip_details->row()->pickup_lat;
		$pickup_long = $get_user_trip_details->row()->pickup_long;
		$drop_lat = $get_user_trip_details->row()->drop_lat;
		$drop_long = $get_user_trip_details->row()->drop_long;
		$driver_id = $get_user_trip_details->row()->driver_id;
		$price = $get_user_trip_details->row()->price;
		$payby = $get_user_trip_details->row()->payby;
		$get_driver_details = $this->common_model->get_all_details(DRIVERS,array('id' => $driver_id));
		$driver_vehicle_model = $get_driver_details->row()->driver_vehicle_model;
		$category_id = $get_driver_details->row()->driver_category;
		$condition = array("id" =>$category_id);
		$get_category_type = $this->common_model->get_all_details(DRIVER_CATEGORIES,$condition);
		$category_name = $get_category_type->row()->category_name;
		$date_explode = explode(' ',$get_user_trip_details->row()->request_time);
		$time = date("g:i a", strtotime($date_explode[1]));
		$Date = date("d-m-Y", strtotime($date_explode[0]));
		if($get_driver_details->row()->driver_image!=''){
			$image = base_url().'images/drivers/'.$get_driver_details->row()->driver_image;
		}else{
			$image = base_url().'images/drivers/user_image.png';
		}
		
		$json = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$pickup_lat.'+'.$pickup_long.'&destinations='.$drop_lat.'+'.$drop_long.'&key=AIzaSyAPG1EM5hpOkE3ZnGeT6EqBKd4xI1cQrac');
		$json_data = json_decode($json);
		$distance = $json_data->{'rows'}[0]->{'elements'}[0]->{'distance'}->{'text'};
		$duration = $json_data->{'rows'}[0]->{'elements'}[0]->{'duration'}->{'text'};
		if($distance!=''){
			$distance_data = $distance;
		}else{
			$distance_data = "";
		}
		if($duration!=''){
			$duration_data = $duration;
		}else{
			$duration_data = "";
		}
		if($price!='0'){
			$amount = $price;
		}else{
			$amount = '';
		}
		$driver_vehicle_model = $get_driver_details->row()->driver_vehicle_model;
		$vechicle_name_details = $this->common_model->get_all_details(MODELLIST,array('id' => $driver_vehicle_model));
		if($driver_vehicle_model!=''){
			$vechicle_name = $vechicle_name_details->row()->model_name;
		}else{
			$vechicle_name = '';
		}
		
		if($get_user_trip_details->row()->status == 'Booked'){
			$lang_status = $this->lang->line('response_booked');
		}else if($get_user_trip_details->row()->status == 'Confirmed'){
			$lang_status = $this->lang->line('confirmed');
		}else if($get_user_trip_details->row()->status == 'Onride'){
			$lang_status = $this->lang->line('onride');
		}else if($get_user_trip_details->row()->status == 'Pending'){
			$lang_status = $this->lang->line('pending');
		}else if($get_user_trip_details->row()->status == 'Completed'){
			$lang_status = $this->lang->line('completed');
		}else if($get_user_trip_details->row()->status == 'Cancelled'){
			$lang_status = $this->lang->line('cancelled');
		}else if($get_user_trip_details->row()->status == 'Arrived'){
			$lang_status = $this->lang->line('response_arrived');
		}
		$status   = $get_user_trip_details->row()->status;
				
		if($get_user_trip_details->num_rows() > 0){
			$jsonReturn['status'] = '1';
			$jsonReturn['message'] = $user_trip_details;
			if($get_user_trip_details->row()->status == 'Pending' || $get_user_trip_details->row()->status == 'Completed'){
				$jsonReturn['response'] = array('id' =>$user_id,'trip_id' => $trip_id,'driver_image' => $image ,'pickup_address' => $pickup_address,'pickup_lat' => $pickup_lat,'pickup_long' => $pickup_long,'drop_address' => $drop_address,'drop_lat' =>$drop_lat,'drop_long' => $drop_long,'date' => $Date,'time' => $time,'category_name' => $category_name,'vechicle_model' => $vechicle_name,'amount' =>$amount,'payby' => $payby,'currency_type' => 'KHR','lang_status'=>$lang_status,'status' => $status,'distance'=>$distance_data,'duration' => $duration_data);
			echo json_encode($jsonReturn);
				
			}else{
				$jsonReturn['response'] = array('id' =>$user_id,'trip_id' => $trip_id,'driver_image' => $image ,'pickup_address' => $pickup_address,'pickup_lat' => $pickup_lat,'pickup_long' => $pickup_long,'drop_address' => '','drop_lat' =>'','drop_long' => '','date' => $Date,'time' => $time,'category_name' => $category_name,'vechicle_model' => $vechicle_name,'amount' =>$amount,'currency_type' => 'KHR','lang_status'=>$lang_status,'status' => $status,'distance'=>$distance_data,'duration' => $duration_data);
			echo json_encode($jsonReturn);
			}
			
		}
		else{
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $no_trips;
			echo json_encode($jsonReturn);
		}
	}
	public function mobile_user_cancel_option(){
		$headerStringValue = apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$user_cancellation_list = $this->lang->line('user_cancellation_list');
				}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$user_cancellation_list = $this->lang->line('user_cancellation_list');
				}else{
				$this->lang->load('en', 'en');
				$user_cancellation_list = $this->lang->line('user_cancellation_list');
				}
		$condition = array('lang' => $lang,'status' => 'Active');
		$get_user_cancellation_list = $this->common_model->get_all_details('user_cancellation_list',$condition);
		foreach($get_user_cancellation_list->result() as $row){
			$jsonReturn[] = array('reason' =>$row->reason,'cancellation_id' => $row->cancellation_id);
		}
			$status = '1';
			$message = $user_cancellation_list;
			$json_encode = json_encode(array("status" => $status,"message" => $message ,"response"=>$jsonReturn));
			echo $json_encode;
	}
	public function mobile_user_cancel_accept(){
		$user_id = urldecode($_POST['u_id']);
		$trip_id = urldecode($_POST['trip_id']);
		$cancellation_id = urldecode($_POST['cancellation_id']);
		$headerStringValue = apache_request_headers();
		$lang = $headerStringValue['lang'];
		$condition = array('ride_no' => $trip_id);
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$trip_cancelled_success = $this->lang->line('trip_cancelled_success');
				$cancelled = $this->lang->line('cancelled');
				$trip_success = $this->lang->line('trip_success');
				$invalid_tripid = $this->lang->line('invalid_tripid');
				}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$trip_cancelled_success = $this->lang->line('trip_cancelled_success');
				$cancelled = $this->lang->line('cancelled');
				$trip_success = $this->lang->line('trip_success');
				$invalid_tripid = $this->lang->line('invalid_tripid');
				}else{
				$this->lang->load('en', 'en');
				$trip_cancelled_success = $this->lang->line('trip_cancelled_success');
				$cancelled = $this->lang->line('cancelled');
				$trip_success = $this->lang->line('trip_success');
				$invalid_tripid = $this->lang->line('invalid_tripid');
				}
		$get_trip_details = $this->common_model->get_all_details('ride_details',array('ride_no' => $trip_id));
		if($get_trip_details->num_rows() > 0){
			if($get_trip_details->row()->status == 'Booked' || $get_trip_details->row()->status == 'Confirmed' || $get_trip_details->row()->status == 'Arrived'){
				$driver_details = $this->common_model->get_all_details(DRIVERS,array('id' => $get_trip_details->row()->driver_id));
				$condition = array('ride_no' => $trip_id);
				$dataArr = array('ride_cancelid' => $cancellation_id,'status' => 'Cancelled');
				$this->common_model->update_details('ride_details',$dataArr,$condition);
				$mobile_key = $driver_details->row()->mobile_key;
				$jsonReturn = array('ride_id' => $trip_id,'message' => "Your trip has been cancelled by user");
				$driver_id = $get_trip_details->row()->driver_id;
				$message  = 'Your trip has been cancelled by user.!';
				$action   = 'Cancelled';
				$res ['payload']= $jsonReturn;
				$res ['action'] = $action;
				$res ['message']= $message;
				$res ['userType']	= 'driver';
				$res ['socket_id']	= $driver_id;
				$is_live = $driver_details->row()->is_live;
				if($is_live == '1'){
					$this->socket_chenda($res);
				}else{
					if($driver_details->row()->device_type =='android'){
					$message 	= "Your trip has been cancelled by user";
					$this->sendPushNotificationToAndroid($mobile_key,$message);
					}else{
					$message 	= "Your trip has been cancelled by user";
					$action 	= "Cancelled";
					$this->send_ios_notification_driver($mobile_key,$message,$jsonReturn,$action);
				}
				}
				
		}
		$cond 		= array('id' => $driver_details->row()->id);
		$dataArr1 	= array('status' => 'Available');
		$this->common_model->update_details(DRIVERS,$dataArr1,$cond);
		$jsonReturn['status'] = '1';
		$jsonReturn['message'] = $trip_success;
		echo json_encode($jsonReturn);
			
		}else{
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = $invalid_tripid;
			echo json_encode($jsonReturn);
		}
		
	}	
	function sendPushNotificationToAndroid($registration_ids, $message){
	   ignore_user_abort();
	   ob_start();
	   $message 			= array("data"=>$message);
	   $registration_ids 	= 'dxLR0iEO6nc:APA91bEl9zfHADyvMOyjm9pXhb4QhhSZ3duaYZgo2EpkdWK0BVPLEhjMsFForAdOowS3qIr1PQgoT1_1K8cT61kbSZI02DIRmVPZ7BItgfUYetglBxb96l5YbZcl5dkB7IEb8Owg5ZKM';
	   $url 				= 'https://fcm.googleapis.com/fcm/send';
	   define('GOOGLE_API_KEY', 'AIzaSyDm38hHoDRNwXWs3fqx9JO4YQSUxI63UWU');
	   $headers 			= array(
		  'Authorization:key='.GOOGLE_API_KEY,
		  'Content-Type: application/json'
	   );      

	   $ch			 = curl_init();
	   curl_setopt($ch, CURLOPT_URL, $url);
	   curl_setopt($ch, CURLOPT_POST, true);
	   curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	   curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false );

		curl_setopt($ch, CURLOPT_POSTFIELDS, "{ \"data\": ".json_encode($message).",    \"to\" : \"".$registration_ids."\"}");

	   $result = curl_exec($ch);
	   if($result === false)
		  die('Curl failed ' . curl_error());

	   curl_close($ch);
	   return $result;
	   ob_flush();
	}
	public function mobile_driver_cancel_option(){
		$headerStringValue = apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$driver_cancellation_list = $this->lang->line('driver_cancellation_list');
				}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$driver_cancellation_list = $this->lang->line('driver_cancellation_list');
			}else{
				$this->lang->load('en', 'en');
				$driver_cancellation_list = $this->lang->line('driver_cancellation_list');
			}
		$condition = array('lang' => $lang,'status' => 'Active');
		$get_driver_cancellation_list = $this->common_model->get_all_details('driver_cancellation_list',$condition);
		foreach($get_driver_cancellation_list->result() as $row){
			$jsonReturn[] = array('reason' =>$row->reason,'cancellation_id' => $row->cancellation_id);
		}
			$status = '1';
			$message = $driver_cancellation_list;
			$json_encode = json_encode(array("status" => $status,"message" => $message ,"response"=>$jsonReturn));
			echo $json_encode;
	}
	public function mobile_driver_cancel_accept(){
		$driver_id  = urldecode($_POST['d_id']);
		$trip_id 	= urldecode($_POST['trip_id']);
		$cancellation_id = urldecode($_POST['cancellation_id']);
		$headerStringValue = apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$trip_cancelled_driver = $this->lang->line('trip_cancelled_driver');
				$cancelled = $this->lang->line('cancelled');
				$trip_success = $this->lang->line('trip_success');
				$invalid_tripid = $this->lang->line('invalid_tripid');
				}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$trip_cancelled_driver = $this->lang->line('trip_cancelled_driver');
				$cancelled = $this->lang->line('cancelled');
				$trip_success = $this->lang->line('trip_success');
				$invalid_tripid = $this->lang->line('invalid_tripid');
				}else{
				$this->lang->load('en', 'en');
				$trip_cancelled_driver = $this->lang->line('trip_cancelled_driver');
				$cancelled = $this->lang->line('cancelled');
				$trip_success = $this->lang->line('trip_success');
				$invalid_tripid = $this->lang->line('invalid_tripid');
				}
		$condition = array('ride_no' => $trip_id);
		$get_trip_det = $this->common_model->get_all_details('ride_details',$condition);
		if($get_trip_det->num_rows() > 0){
		 if($get_trip_det->row()->status == 'Booked' || $get_trip_det->row()->status == 'Confirmed' || $get_trip_det->row()->status == 'Arrived'){
			$condition = array('ride_no' => $trip_id);
			$dataArr = array('ride_cancelid' => $cancellation_id,'status' => 'Cancelled');
			$this->common_model->update_details('ride_details',$dataArr,$condition);
			$user_details = $this->common_model->get_all_details(USERS,array('id' => $get_trip_det->row()->user_id));
			$mobile_key = $user_details->row()->mobile_key;
			$jsonReturn = array('ride_id' => $trip_id,'message' => "Your trip has been cancelled by driver");
				$message  = 'Your trip has been cancelled by user.!';
				$action   = 'Cancelled';
				$user_id = $user_details->row()->id;
				$res ['payload']= $jsonReturn;
				$res ['action'] = $action;
				$res ['message']= $message;
				$res ['userType'] = 'user';
				$res ['socket_id']	= $user_id;
				$is_live = $user_details->row()->is_live;
				if($is_live == '1'){
					$this->socket_chenda($res);
				}else{
					if($user_details->row()->device_type == 'android'){
					$message = "Your trip has been cancelled by driver";
					$this->sendPushNotificationToAndroid($mobile_key,$message);
				}else{
					$action =  "Cancelled";
					$message = "Your trip has been cancelled by driver";
					$this->send_ios_notification_user($mobile_key,$message,$jsonReturn,$action);
			}
			}
		}
		$cond 		= array('id' => $get_trip_det->row()->driver_id);
		$dataArr1 	= array('status' => 'Available');
		$this->common_model->update_details(DRIVERS,$dataArr1,$cond);
		$jsonReturn['status'] = '1';
		$jsonReturn['message'] = $trip_success;
		echo json_encode($jsonReturn);
		}else{
		$jsonReturn['status'] = '0';
		$jsonReturn['message'] = $invalid_tripid;
		echo json_encode($jsonReturn);	
		}
	}
	
	public function mobile_user_cancel_onbooking(){
		$user_id = urldecode($_POST['u_id']);
		$trip_id = urldecode($_POST['trip_id']);
		$headerStringValue = apache_request_headers();
		$lang = $headerStringValue['lang'];
		if($lang == 'km'){
				$this->lang->load('km', $lang);
				$user_cancelled_booking = $this->lang->line('user_cancelled_booking');
				}else if($lang == 'en'){
				$this->lang->load('en', $lang);
				$user_cancelled_booking = $this->lang->line('user_cancelled_booking');
				}else{
				$this->lang->load('en', 'en');
				$user_cancelled_booking = $this->lang->line('user_cancelled_booking');
				}
		$condition = array('ride_no' => $trip_id);
		$dataArr = array('status' => 'Cancelled','cancelled_status' => 'yes');
		$this->common_model->update_details('ride_details',$dataArr,$condition);
		$condition1 = array('ride_no' => $trip_id);
		$ride_details = $this->common_model->get_all_details('ride_details',$condition1);
		$driver_id = $ride_details->row()->driver_id;
		$driver_details = $this->common_model->get_all_details(DRIVERS,array('id' => $driver_id));
		$mobile_key = $driver_details->row()->mobile_key;
		$device_type = $driver_details->row()->device_type;
		$jsonReturn['status'] = '1';
		$jsonReturn['message'] = $user_cancelled_booking;
		echo json_encode($jsonReturn);
	}
	
	public function mobile_get_current_trip(){
		$driver_id = urldecode($_POST['d_id']);
		$current_date_time = date('Y-m-d');
		
		//$current_date_time = "2017-03-29";
		$query = 'SELECT * FROM `ride_details` WHERE driver_id = "'.$driver_id.'" and date(request_time) = "'.$current_date_time.'" and status = "Completed"  order by request_time DESC';
		$result = $this->db->query($query);
		//print_r($result->result());exit;
		$total_trips = $result->num_rows();
		$get_trip_details = $result->result_array();
		$total = '0';
		foreach($get_trip_details as $val){
			$total += $val['price'];
		}
		$tot_price = $total;
		if($tot_price!=0){
			$tot_price = $total;
		}else{
			$tot_price = 0;
		}
		if($result->num_rows() > 0){
			$driver_details = $this->common_model->get_all_details(DRIVERS,array('id' => $driver_id));
			$today_hours = $driver_details->row()->today_hours;
			if($today_hours!='0:00'){
			$hours = $today_hours;
			}else{
			$hours = 0;
			}
			$trip_count =count($get_trip_details);
			$last_trip_time = $get_trip_details[0]['request_time'];
			$newDate = date("d-m-Y g:i a", strtotime($last_trip_time));
			$explode = explode(" ",$newDate);
			$trip_price = $get_trip_details[0]['price'];
			if($trip_price!=0){
			$price = $trip_price;
			}else{
			$price = 0;
			}
			$jsonReturn['status'] = '1';
			$jsonReturn['Last trip'] = array('last_trip_date' => $explode[0],'last_trip_time' => $explode[1].' '.$explode[2],'price' => $price,'currency_type' => 'KHR');
			$jsonReturn['Total trips'] = array('total_trips' => $total_trips,'price' => $tot_price,'driver_online_hours' => $hours,'currency_type' => 'KHR');
			echo json_encode($jsonReturn);
		}else{
			$query = 'SELECT * FROM `ride_details` WHERE driver_id = "'.$driver_id.'" and status = "Completed" order by request_time DESC';
			$result = $this->db->query($query);
			$get_trip_details = $result->result_array();
			$trip_count =count($get_trip_details);
			$last_trip_time = $get_trip_details[0]['request_time'];
			$newDate = date("d-m-Y g:i a", strtotime($last_trip_time));
			$explode = explode(" ",$newDate);
			$trip_price = $get_trip_details[0]['price'];
			$driver_details = $this->common_model->get_all_details(DRIVERS,array('id' => $driver_id));
			$today_hours = $driver_details->row()->today_hours;
			if($today_hours!='0:00'){
			$hours = $today_hours;
			}else{
			$hours = 0;
			}
			if($trip_price!=0){
			$price = $trip_price;
			}else{
			 $price = 0;
			}
			$jsonReturn['status'] = '1';
			$jsonReturn['Last trip'] = array('last_trip_date' => $explode[0],'last_trip_time' => $explode[1].' '.$explode[2],'price' => $price,'currency_type' => 'KHR');
			$jsonReturn['Total trips'] = array('total_trips' => '0','price' => '0','driver_online_hours' => '0','currency_type' => 'KHR');
			echo json_encode($jsonReturn);
		}
	}
		public function mobile_driver_earnings(){
		$driver_id 			= urldecode($_POST['d_id']);
		$date  				= urldecode($_POST['req_date']);
		$req_date 			= date('Y-m-d', strtotime($date));
		$driver_details_data  = 'SELECT * FROM `drivers` WHERE id = "'.$driver_id.'" and date(createdon) <= "'.$req_date.'"';
		$query = $this->db->query($driver_details_data);
		$country_code = '+'.$query->row()->driver_location;
		if($query->num_rows() > 0){
			$today_trip  = 'SELECT * FROM `ride_details` WHERE driver_id = "'.$driver_id.'" and date(request_time) = "'.$req_date.'"  order by request_time DESC';
			$get_trip_det = $this->db->query($today_trip);
		if($get_trip_det->num_rows() > 0){
		$query = 'SELECT * FROM `ride_details` WHERE driver_id = "'.$driver_id.'" and date(request_time) = "'.$req_date.'" and status = "Completed"  order by request_time DESC';
		$driver_earnings = $this->db->query($query);
		$tot_count = $driver_earnings->num_rows();
		if($driver_earnings->num_rows() > 0){
			$price = 0;
			foreach($driver_earnings->result() as $row){
				$date = date('d-m-Y', strtotime($row->request_time));
				$time = date('g:i a', strtotime($row->request_time));
				$price += $row->price;
				$jsonReturn[] = array('pickup_address' => $row->pickup_address,'drop_address'=>$row->drop_address,'trip_date' => $date,'trip_time' => $time,'status' => $row->status,'duration'=>'15mins','distance' => '4.5kms','price' => $row->price,'currency_type' => 'KHR','country_code' => $country_code);
		}
		$driver_decline_status = 'SELECT * FROM `ride_details` WHERE driver_id = "'.$driver_id.'" and date(request_time) = "'.$req_date.'" and ride_cancelid != "" and status = "Cancelled" order by request_time DESC';
		$get_declined_status = $this->db->query($driver_decline_status);
		$driver_accepted_status = 'SELECT * FROM `ride_details` WHERE driver_id = "'.$driver_id.'" and date(request_time) = "'.$req_date.'" and cancelled_status ="no" and status !="Booked" order by request_time DESC';
		$get_accepted_status = $this->db->query($driver_accepted_status);
			$status = '1';
			$message = 'driver earnings details';
			$online_hours = '1 hr';
			$total_trips = $get_trip_det->num_rows();
			$earning = $price;
			$currency_type = 'KHR';
			$accepted = $get_accepted_status->num_rows();
			$declined = $get_declined_status->num_rows();
			$json_encode = json_encode(array("status" => $status,"message" => $message ,"online" => $online_hours,"total_trips" => $total_trips,"earnings" => $earning,"currency_type" =>$currency_type,"accepted" => $accepted,"declined" =>$declined,"response"=>$jsonReturn));
			echo $json_encode;
		}else{
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = 'not yet complete trip';
			echo json_encode($jsonReturn);
		}
		}else{
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = 'no trips found';
			echo json_encode($jsonReturn);
		}
		}else{
			$jsonReturn['status'] = '0';
			$jsonReturn['message'] = 'he not yet joining a driver';
			echo json_encode($jsonReturn);
		}
		
	}

	public function mobile_driver_earnings_month(){
		$driver_id = urldecode($_POST['d_id']);
		$year = date("Y");
		$query = 'SELECT * FROM `ride_details` WHERE driver_id = "'.$driver_id.'" order by month(request_time)';
		$driver_earnings = $this->db->query($query);
		foreach($driver_earnings->result() as $row){
			$month = $row->request_time;

			$query = 'SELECT * FROM `ride_details` WHERE driver_id = "'.$driver_id.'" group by month("'.$month.'")';

			$jsonReturn[] = array('total_earnings' => '10000','total_trips' => '20','confirm_trip' => '16','decline_trip' => '2');
		}
		$status = '1';
		$message = 'driver earnings for month';
		$json_encode = json_encode(array("status" => $status,"message" => $message ,"response"=>$jsonReturn));
		echo $json_encode;
	}
	
	

	public function mobile_get_request_latertrip(){
		
		$from_email 	= 'ponraj@2ntkh.com'; 
		$to_email 		=  'ruzzi44@gmail.com'; 
		$email_config 	= Array(
			'protocol'  => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => '465',
			'smtp_user' => 'ponraj@2ntkh.com',
			'smtp_pass' => 'ponraj123',
			'mailtype'  => 'html',
			'starttls'  => true,
			'newline'   => "\r\n"
		);
		$this->load->library('email', $email_config);
		$this->email->clear(TRUE);
		$this->email->from($from_email);
		$this->email->to($to_email);
		$this->email->subject('Mail testt');
   		 $this->email->send();
echo 'test';exit;

   		$current_date_time = date('Y-m-d');
		$query = 'SELECT * FROM `ride_details` WHERE ride_type = 1 and status = "Booked" and date(request_time) = "'.$current_date_time.'" order by date(request_time)';
		$get_trip_details = $this->db->query($query);
		foreach($get_trip_details->result() as $row){
			$pickup_lat = $row->pickup_lat;
			$pickup_long = $row->pickup_long;
			$pickup_address = $row->pickup_address;
			$cat_id = $row->category_id;
			$req_time = $row->request_time;
			$ride_id = $row->ride_no;
			$this->notify_drivers_later($pickup_lat,$pickup_long,$pickup_address,$cat_id,$req_time,$ride_id);
		}
	}
	public function notify_drivers_later($pickup_lat,$pickup_long,$pickup_address,$cat_id,$req_time,$ride_id){
		$current_date_time = date('Y-m-d H:i');
		$ride_time = date('Y-m-d H:i',strtotime($req_time));
		if($current_date_time == $ride_time){
		$ride_details = $this->response_model->ride_category_later($pickup_lat,$pickup_long,$pickup_address,$cat_id,$ride_time);
		foreach($ride_details as $value){
			$jsonReturn = array('ride_id'=>$ride_id,'user_lat' => $pickup_lat,'user_long' => $pickup_long,'address' =>$pickup_address,'category_name' => $value->category_name,'user_rating' => '5');
				$message 		= "You have! Trip Request";
				$driver_id 		= $value->driver_id;
				$device_key 	= $value->mobile_key;
				$device_type 	= $value->device_type;
				$action = "ride request";
				$res ['payload']= $jsonReturn;
				$res ['action'] = $action;
				$res ['userType']= 'driver';
				$res ['message']= $message;
				$is_live 		= $value->driver_live;
				$socket_id 		= $driver_id;
				$res ['socket_id']= $socket_id;
				if($is_live == '1'){
					$this->socket_chenda($res);
				}else{
					if($device_type == 'android'){ 
					$notify_msg = array("title" => "0001","screen" => 'Raja',"message" => $message,"timestamp" => date('Y-m-d h:i:s'),"image" => "'remo.jpg'",'ride_id'=>$ride_id,'user_lat' => $pickup_lat,'user_long' => $pickup_long,'address' =>$pickup_address,'category_name' => $value->category_name,'user_rating' => '5');
					$this->sendPushNotificationToAndroid($device_key,$notify_msg);
				}else if($device_type=='ios'){
					$this->send_ios_notification_driver($device_key,$message,$jsonReturn,$action);
				}
				}
		}
		$ride_type = '1';
		$Return['response'] = array('ride_id'=>$ride_id,'ride_type'=>$ride_type);
		$Return['status'] = 1;
		echo json_encode($Return);exit;
		}else{
			$jsonReturn['message'] = 'no trips';
			echo json_encode($jsonReturn);
		}
	}
	public function mobile_emergency_alert(){
	 $user_id = urldecode($_POST['u_id']);
	 $ride_id = urldecode($_POST['ride_id']);
	 $lat = urldecode($_POST['u_lat']);
	 $long = urldecode($_POST['u_long']);
	 $headerStringValue = apache_request_headers();
	 $lang = $headerStringValue['lang'];
	 $condition = array('user_id' => $user_id);
	 $get_emergency_details= $this->common_model->get_all_details(EMERGENCY_CONTACT,$condition);
	 $get_ride_details = $this->common_model->get_all_details('ride_details',array('ride_no' => $ride_id));
	 $get_user_details = $this->common_model->get_all_details(USERS,array('id' => $user_id));
	 $user_name = $get_user_details->row()->firstname;
	 $driver_id = $get_ride_details->row()->driver_id;
	 $trip_start_time = $get_ride_details->row()->request_time;
	 $trip_date = date("F jS, Y", strtotime($trip_start_time));
	 $date_explode = explode(' ',$trip_start_time);
	 $time = date("g:i a", strtotime($date_explode[1]));
	 $get_driver_details= $this->common_model->get_all_details(DRIVERS,array('id' => $driver_id));
	 $driver_name = $get_driver_details->row()->driver_name;
	 $vechicle_type = $get_driver_details->row()->driver_vehicle_type;
	 $get_vechicle_type = $this->common_model->get_all_details(MODELLIST,array('id' => $vechicle_type));
	 $vechicle_name = $get_vechicle_type->row()->model_name;
	 $driver_vehicleno = $get_driver_details->row()->driver_vehicleno;
	 if($lang == 'km'){
	 	$this->lang->load('km', $lang);
		$message_success = $this->lang->line('success_message');
	 }else if($lang == 'en'){
	 	$this->lang->load('en', $lang);
		$message_success = $this->lang->line('success_message');
	 }else{
	 	$this->lang->load('en', 'en');
		$message_success = $this->lang->line('success_message');
	 }
	 foreach($get_emergency_details->result() as $row){
			
	 		$mobile_no = $row->mobile_no;
	 		$country_code = $row->country_code;
	 		$number_of_digits = mb_strlen($mobile_no);
			require_once('twilio/Services/Twilio.php');
			$signup_otp = mt_rand(100000, 999999);
			$account_sid = 'AC0632d53f15fc64c4b34dcc5cabca584c'; 
			$auth_token = '0edc5359ac6d4232e445a4cbc266d569';
			$to=$country_code.''.$mobile_no;
			$client = new Services_Twilio($account_sid,$auth_token);
			$location = 'http://maps.google.com/?q='.$lat.','.$long.'';
			$client->account->messages->create(array( 
			'From' => "8444523943",
			'To' => $to,	
			'Body' => 'Hi this is Mr.'.ucfirst($user_name).' emergency contact details. Driver Name:'.ucfirst($driver_name).'   '.'Vechicle No:'.$driver_vehicleno.'   '.'Vechicle Type:'.$vechicle_name.'  '.'Ride No:'.$ride_id.'   '.'Location:'.$location.'   '.'Trip Start Date:'.$trip_date.'   '.'Trip Start time:'.$time.''
   			)); 
	 }
	$status = '1';
	$message = $message_success;
	$json_encode = json_encode(array("status" => $status,"message" => $message));
	echo $json_encode;
	}
	public function mobile_user_invoice(){
		$user_id = urldecode($_POST['u_id']);
		$ride_id = urldecode($_POST['ride_id']);
		$email_id = urldecode($_POST['u_email']);
		$headerStringValue = apache_request_headers();
	 	$lang = $headerStringValue['lang'];
	 	$condition = array('ride_no' => $ride_id);
	 	$get_ride_details = $this->common_model->get_all_details('ride_details',$condition);
		//print_r($get_ride_details->result());exit;
	 	$driver_id = $get_ride_details->row()->driver_id;
	 	$get_vechile_details = $this->common_model->get_all_details(DRIVERS,array('id'=> $driver_id));
	 	$vechicle_model = $get_vechile_details->row()->driver_vehicle_model;
	 	$get_vechile_type = $this->common_model->get_all_details(MODELLIST,array('id'=> $vechicle_model));
	 	$vechicle_name = $get_vechile_type->row()->model_name;
	 	$pickup_lat = $get_ride_details->row()->pickup_lat;
	 	$pickup_long = $get_ride_details->row()->pickup_long;
	 	$pickup_address = $get_ride_details->row()->pickup_address;
	 	$drop_lat = $get_ride_details->row()->drop_lat;
	 	$drop_long = $get_ride_details->row()->drop_long;
	 	$drop_address = $get_ride_details->row()->drop_address;
	 	$trip_start_time = date('H:i',$get_ride_details->row()->request_time);
	 	$trip_end_time = $get_ride_details->row()->trip_end_time;
	 	$start_time = date('g:i a',$trip_start_time);
	 	$end_time = date('g:i a',$trip_end_time);
	 	$price = $get_ride_details->row()->price;
	 	$geocode = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$pickup_lat.'+'.$pickup_long.'&destinations='.$drop_lat.'+'.$drop_long.'&key=AIzaSyAPG1EM5hpOkE3ZnGeT6EqBKd4xI1cQrac');
		$json = json_decode($geocode);
		$distance = $json->{'rows'}[0]->{'elements'}[0]->{'distance'}->{'text'};
		$duration = $json->{'rows'}[0]->{'elements'}[0]->{'duration'}->{'text'};
	 	$ExpArr[] = array("ride_id" =>$ride_id ,"start_time" => $start_time,"end_time" => $end_time,"trip_start_time" => $trip_start_time,"trip_end_time"=>$trip_end_time ,"vechicle_model" => $vechicle_name,"pickup_lat"=>$pickup_lat,"pickup_long"=>$pickup_long,"drop_lat" => $drop_lat,"drop_long" => $drop_long,"pickup_address"=>$pickup_address ,"drop_address"=>$drop_address,"price"=>$price,'service_price' => '0','total' => '100',"distance"=>$distance,"duration"=>$duration,'currency_type'=>'KHR');
		$this->mobdata['pageDetails'] = $ExpArr;
		$invoice = $this->load->view('admin/invoice/confirmation',$this->mobdata,TRUE);
		$this->load->library('m_pdf');
		$filenames 		= time().".pdf";
		$filepath 		= getcwd()."/uploads/invoice/";
		$pdf			= $this->m_pdf->load();
		$pdf->WriteHTML($invoice,2);
		$pdf->Output($filepath.$filenames, "F"); 
		$from_email 	= 'ponraj@2ntkh.com'; 
		$to_email 		=  $email_id; 
		$email_config 	= Array(
			'protocol'  => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => '465',
			'smtp_user' => 'ponraj@2ntkh.com',
			'smtp_pass' => 'ponraj123',
			'mailtype'  => 'html',
			'starttls'  => true,
			'newline'   => "\r\n"
		);
		// print_r($email_config);exit;
		$this->load->library('email', $email_config);
		$this->email->clear(TRUE);
		$this->email->from($from_email);
		$this->email->to($to_email);
		$this->email->subject('Mail Invoice');
   		$this->email->message($invoice);   
   		$this->email->attach($filepath .$filenames);
		$this->email->send();
		$jsonReturn['status'] ="1";
		$jsonReturn['message'] = "Successfully Email Sent";
		echo json_encode($jsonReturn);
		//$this->load->view('admin/invoice/confirmation',$this->mobdata);
	}
	public function socket_chenda($data){
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_PORT => "5000",
			CURLOPT_URL => "http://192.168.17.7:5000/broadcast",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode($data),
			CURLOPT_HTTPHEADER => array(
			"cache-control: no-cache",
			'Content-Type: application/json',
			"postman-token: 44c98fa9-2636-5159-f006-96ab36bc14c7"
		  ),
		));
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		/* if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		  echo $response;
		} */
	}
	public function mobile_test_sms(){
		$username = $_GET['username'];
		$password = $_GET['pass'];
		$sender = $_GET['sender'];
		$text = $_GET['smstext'];
		$is_flash = $_GET['is_flash'];
		$gsm = $_GET['gsm'];
		$pwd= md5($password);
		$headerStringValue = apache_request_headers();
	 	$content_type = $headerStringValue['content-type'];
		$url = 'http://client.mekongsms.com/api/sendsms.aspx?username='.$username.'&pass
		='.$pwd.'&sender='.$sender.'&smstext='.$text.'&isflash=0&gsm='.$gsm.'';
		$ch = curl_init();
		//print_r($url);exit;
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,
            "dispnumber=567567567&extension=6");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $content_type);
		// receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec ($ch);
		curl_close ($ch);
	}
	public function user_rating($user_id){
		$data 	   = $this->response_model->user_rating($user_id);
		$no_rating = $data->row()->total_rat;
		$rating    = $data->row()->cnt;
		$over_all  = $rating  / $no_rating;
		return ceil($over_all);
	} 
	
	public function driver_rating($driver_id){
		$data 	   = $this->response_model->driver_rating($driver_id);
		$no_rating = $data->row()->total_rat;
		$rating    = $data->row()->cnt;
		$over_all  =  $rating  / $no_rating;
		return ceil($over_all);
	} 
	public function user_ride_cnt($user_id){
		$data 	   = $this->response_model->user_ride_cnt($user_id);
		$no_rating = $data->row()->total_ride;
		return $no_rating;
	}
	
	public function driver_ride_cnt($driver_id){
		$data 	   = $this->response_model->driver_ride_cnt($driver_id);
		$no_rating = $data->row()->total_ride;
		return $no_rating;
	}	
	public function terms_and_condition(){
		$lang = $this->uri->segment(3);
		if($lang == 'en'){
			$this->load->view('terms_and_condition');
		}else{
			$this->load->view('terms_and_condition_km');
		}
		
	}
	public function privacy_policy(){
		$lang = $this->uri->segment(3);
		if($lang == 'en'){
			$this->load->view('privacy_policy');
		}else{
			$this->load->view('privacy_policy_km');
		}
	}
}
/* End of file mobile.php no_image.jpg */
/* Location: ./application/controllers/site/mobile.php */
?>