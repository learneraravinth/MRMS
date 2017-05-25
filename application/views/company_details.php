<?php 
$this->load->view('template/header');
#echo '<pre>';print_r($company_details);exit;
 ?>

<style>
.first_de{
	margin : 5px;
	padding : 5px;
}

.error{
	color : red;
}
</style>
  <body>
  	<?php $this->load->view('template/sidebar'); ?>
      <!--sidebar end-->
      <!--main content start-->
		<section id="main-content">
			<section class="wrapper">
				<section class="panel">
                          <header class="panel-heading tab-bg-dark-navy-blue ">
                              <ul class="nav nav-tabs">
                                  <li class="">
                                      <a data-toggle="tab" class="remo" href="#home" aria-expanded="false">Company Profile</a>
                                  </li>
                                  <li class="">
                                      <a data-toggle="tab" href="#about" aria-expanded="false">Account Details</a>
                                  </li>
                                
                              </ul>
                          </header>
                          <div class="panel-body">
                              <div class="tab-content">
                                  <div id="home" class="tab-pane">
                                      <div class="panel-body">
										<form class="form-horizontal tasi-form" name="meeting_form" method="post" action="<?php echo base_url();?>company_profile/save_company_details">
											<div class="form-group">
												 <label class="col-lg-2 control-label">Company Name:</label>
												 <div class="col-lg-4">
													  <input type="text" class="form-control" name="company_name" id="company_name" placeholder="Enter Company Name" value="<?php echo $company_details[0]->company_name; ?>" >
												  </div>

												  <label class="col-lg-2 control-label">Mobile No:</label>
												  <div class="col-lg-4">
												  <input type="text" class="form-control" name="mobile_no" id="mobile_no" placeholder="Enter Mobile No" value="<?php echo $company_details[0]->mobile_no; ?>">
												 </div>
											</div>
												
											<div class="form-group">
												<label class="col-lg-2 control-label">Facebook Username : </label>
												<div class="col-lg-4">
													  <input type="text" class="form-control" name="fb_username" id="fb_username" placeholder="FB Username"value="<?php echo $company_details[0]->fb_username; ?>" >
												</div>
												<label class="col-lg-2 control-label">Facebook Password : </label>
												  <div class="col-lg-4">
													<input type="password" class="form-control" name="fb_passsword" id="fb_passsword" placeholder="FB Password" value="<?php echo $company_details[0]->fb_passsword; ?>">
												</div>
											</div>

											<div class="form-group">
												 <label class="col-lg-2 control-label">Address : </label>
												 <div class="col-lg-4">
													  <input type="text" class="form-control" name="company_address" id="company_address" placeholder="Enter Address" value="<?php echo $company_details[0]->address; ?>" >
												  </div>

												  <label class="col-lg-2 control-label">Description : </label>
												  <div class="col-lg-4">
												  <textarea id="company_description" name="company_description" placeholder="Company Descriprtion" class="form-control" cols="10" rows="4"><?php echo $company_details[0]->description; ?></textarea>
												 </div>
											</div>
										</form>
										<form enctype="multipart/form-data" id= "form_logo" name="form_logo" class= "form-horizontal tasi-form">
											<div class="form-group">
												<label class="control-label col-md-3">Logo</label>
													<div class="col-md-4">
														<input type="file" class="default" id="company_logo" name="company_logo" onchange="auto_upload_photo('company_logo');">
														<input type="hidden" id="imgage_name" name="imgage_name" value="<?php echo $company_details[0]->logo_name; ?>">
													</div>
												</form> 	
													<div id="show_img">
														<img height="89" width="90" id="theImg" href="<?php echo base_url(); ?>uploads/company_logo_upload/<?php echo $company_details[0]->logo_name; ?>">
													</div>
											</div>
										
										<div class="col-lg-offset-2 col-lg-8 center">
											<button class="btn btn-primary" type="button" onclick="save_company()">Update</button>
										</div>
									</div>
                                  </div>
                                  <div id="about" class="tab-pane">
									  <form class="form-horizontal tasi-form" name="info_form" method="post" action="<?php echo base_url();?>company_profile/save_company_info">
										<div class="form-group">
											<label class="col-lg-2 control-label"> Admin Name : </label>
											<div class="col-lg-4">
												  <input type="text" class="form-control" name="admin_username" id="admin_username" placeholder="Username"value="<?php echo $company_details[0]->admin_name; ?>" >
											</div>
											<label class="col-lg-2 control-label">Password : </label>
											  <div class="col-lg-4"> 
												<input type="password" class="form-control" name="admin_passsword" id="admin_passsword" placeholder="FB Password" value="<?php echo $company_details[0]->admin_password; ?>">
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-2 control-label"> Email Id: </label>
											<div class="col-lg-4">
												  <input type="text" class="form-control" name="admin_email" id="admin_email" placeholder="Enter email"value="<?php echo $company_details[0]->email; ?>" >
											</div>
											
										</div>
										</form>
										<div class="form-group">
											<label class="control-label col-md-3">Photo</label>
											<form enctype="multipart/form-data" id= "admin_photofrm" name="admin_photofrm" class= "form-horizontal tasi-form">
												<div class="col-md-4">
													<input type="file" class="default" id="user_photo" name="user_photo" onchange="auto_upload_photo('user_photo');">
													<input type="hidden" id="user_photo_name" name="user_photo_name" value="<?php echo $company_details[0]->user_photo; ?>">
												</div>
											</form> 
											<div id="show_img_user">
												<?php if(!empty($company_details[0]->user_photo)){
													$user_photo = $company_details[0]->user_photo;	
												}else{
													$user_photo = '/img/user_unknown.jpg';
												}
												?>
												<img height="89" width="90" id="theImg" href="uploads/admin_upload/<?php echo$user_photo  ?>">
											</div>
										</div>
										<div class="col-lg-offset-2 col-lg-8 center">
											<button class="btn btn-primary" type="button" onclick="save_info()">Update</button>
										</div>
									</div>
								</div>
                          </div>
                      </section>
		</section>
	</section>
	<script> 
	var baseUrl='<?php echo base_url();?>';
	function save_company(){
	/* var is_valid  = $('#company_form').valid();
    if(!is_valid)
    {
      return false;
    } */
    var filename 		= baseUrl+'admin/company_profile/save_company';
    var request  = $.ajax({
      url  : filename,
      type : "POST",
      data : {     
			 company_name     	:  $("#company_name").val(), 
			 mobile_no     		:  $("#mobile_no").val(), 
            fb_username     	:  $("#fb_username").val(), 
            fb_passsword     	:  $("#fb_passsword").val(), 
            company_address     :  $("#company_address").val(), 
            company_description :  $("#company_description").val(), 
            logo_name     		:  $("#imgage_name").val(),

        },
        dataType : "html"
    });
    
    request.done(function(result){
		var output    = jQuery.parseJSON(result);
		location.reload();
	});
    
    request.fail(function(jqXHR,textStatus){
      alert(textStatus);
    });     		
}

function save_info(){
    var filename 		= baseUrl+'admin/company_profile/save_info';
    var request  = $.ajax({
      url  : filename,
      type : "POST",
      data : {                
			admin_username     	:  $("#admin_username").val(), 
			admin_passsword     :  $("#admin_passsword").val(), 
			admin_email     	:  $("#admin_email").val(), 
			user_photo_name     :  $("#user_photo_name").val()
            
        },
        dataType : "html"
    });
    
    request.done(function(result){
		var output    = jQuery.parseJSON(result);
		location.reload();
	});
    
    request.fail(function(jqXHR,textStatus){
      alert(textStatus);
    });     		
}

function auto_upload_photo(type){
  $("#"+type ).submit();	
}
$(document).ready(function(){ 
$( ".remo" ).trigger( "click" );
 });   
</script>

  </body>
<?php 

$this->load->view('template/footer'); ?>