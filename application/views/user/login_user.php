<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="2NT Meeting Room Managment system,mrms,combaodia">
    <meta name="author" content="2NTKH">
    <meta name="keyword" content="2NT Meeting Room Managment system,mrms,combaodia">
    <link rel="shortcut icon" href="img/favicon.png">
    <title><?php 
		 $controller = $this->uri->segment(1); 
		 if(!empty($controller)){
			 if($controller == 'user'){
				$title = 'Employee';	 
			 }else{
				 $title = ucfirst($controller);	 
			 }
			 
		 }else{
			 $title= '2NT Meeting Room';
		 }
	echo $title;
	$data = admindata();

	if(empty($data[0]['logo_name'])){
		$logo_name = 'img/logo/logo.png';
	}else{
		$logo_name = 'uploads/company_logo_upload/'.$data[0]['logo_name'];
	} ?>
	</title>
  <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
   <link href="<?php echo base_url(); ?>css/font-awesome.css" rel="stylesheet" />
   <link href="<?php echo base_url(); ?>css/sweetalert.css" rel="stylesheet">
   <script src="<?php echo base_url(); ?>js/1.10.min.js"></script>
   <script src="<?php echo base_url(); ?>js/jquery.validate.js"></script>
   <script src="<?php echo base_url(); ?>js/sweetalert.min.js"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo  base_url(); ?>css/style-form.css">
	<section id="container" class="">
      <section id="main-content">
          <section class="wrapper">
              <!-- page start-->
			<section class="panel">
					<div class="container-fluid first-bg">
					<div class="container">
					<div class="row">
						<div class="col-md-6 col-md-offset-3 bg-secon">
								<h1 class="text-center">LogIn</h1>
								<img src="<?php echo base_url().$logo_name;?>" alt="" class="text-center">
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-md-offset-3 bg-secon">
							<div class="form-group">
								<input type="text" class="form-control bg-form" id="user_name" name="user_name" placeholder="@ User Name">
							</div>
							<button type="button" onclick="check_username()" class="btn btn-default pull-right bg-bt">Next</button>
						</div>
					</div>

					</div>
				</div>
			</section>
              <!-- page end-->
          </section>
		</section>
      <!--main content end-->
      <!-- Right Slidebar end -->
	</section>
	<script>
	var baseUrl='<?php echo base_url();?>';

	function check_username(){
			var user_name = $("#user_name").val();
		if(user_name==''){
		swal(
				  'Oops...',
				  'Please Enter User Name.!',
				  'error'
				);
		}
		var filename = baseUrl+'loginuser/check_username';

		var request  = $.ajax({
			url  : filename,
			type : "POST",
			data : {   
				user_name   	 : user_name, 
			},
			dataType : "html"
		});

		request.done(function(result){
			var output    = jQuery.parseJSON(result);
			if(output.flag == 1){
				//swal("Saved!", "Successfully .!", "success");
				location.href = baseUrl+"dashboard_user";
			}else{
				swal(
			  'Oops...',
			  'Wrong User Name!',
			  'error'
			);
			}			
		});
		
		request.fail(function(jqXHR,textStatus){
			alert(textStatus);
		});			
	}
</script>
<style>
	span.highlight {
		background-color:#ff6b5f;
		color:#ffffff;
	}
</style>
</body>
</html>