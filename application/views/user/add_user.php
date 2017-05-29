<?php 

$this->load->view('template/header'); ?>

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
				<div class="panel-body">
						<form class="form-horizontal tasi-form" name= "user_form"  id="user_form" method="post" action="<?php echo base_url();?>admin/user/save_users">
							<div class="form-group">
								 <label class="col-lg-2 control-label">User Name :</label>
								 <div class="col-lg-4">
									  <input type="text" class="form-control" name="user_name" id="user_name" placeholder="Enter Name" value="" >
								  </div>

								  <label class="col-lg-2 control-label">Short Name :</label>
								  <div class="col-lg-4">
								  <input type="text" class="form-control" name="short_name" id="short_name" placeholder="Enter Short Name" value="">
								 </div>
							</div>
							
							<div class="form-group">
								 <label class="col-lg-2 control-label">Job Title :</label>
								 <div class="col-lg-4">
									  <input type="text" class="form-control" name="job_title" id="job_title" placeholder="Enter job Title" value="" >
								  </div>

								  <label class="col-lg-2 control-label">FaceBook Id :</label>
								  <div class="col-lg-4">
								  <input type="text" class="form-control" name="facebook_id" id="facebook_id" placeholder="Enter facebook id" value="">
								 </div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Description :</label>
								<div class="col-lg-4">
								<input type="text" class="form-control" name="user_description" id="user_description" placeholder="Enter Descriprtion" value="">
								</div>

								<label class="col-lg-2 control-label">Mobile No :</label>
								<div class="col-lg-4">
									<input type="text" class="form-control" name="mobile_no" id="mobile_no" placeholder="Enter Mobile No" value="" >
								</div>
							</div>
							</form>
							<div class="form-group remos">
								<label class="control-label col-md-3">User Photo</label>
								<form enctype="multipart/form-data" id= "admin_photofrm" name="admin_photofrm" class= "form-horizontal tasi-form">
									<div class="col-md-4">
										<input type="file" class="defaults" id="user_photo" name="user_photo" onchange="auto_upload_photo('user_photo');">
										<input type="hidden" id="user_photo_name" name="user_photo_name" value="">
									</div>
								</form> 
								<div id="show_img_user">
									
								</div>
							</div>
							<input type="hidden" id="user_id" name="user_id" value="">
							<div class="col-lg-offset-2 col-lg-10 remoss">
							  <button class="btn btn-danger" type="button" onclick="save_users()">Save</button>
							  <button class="btn btn-default" onclick="clear_fields()"  type="button">Clear</button>
							</div>
						
				</div>
			  </section>
		</section>
	</section>
	<div class="modal fade " id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				  <h4 class="modal-title">Add New Amenities</h4>
				</div>
				<div class="modal-body">
					<div class="col-lg-15S">
						<section class="panel">													<div class="panel-body">
								<form id= "amenities_form">
									<div class="form-group">
										<label for="exampleInputEmail1">Amenities</label>
										<input class="form-control" id="amenities_name" name="amenities_name" placeholder="Enter amenities_name" type="text">
									</div> 
								</form>
								<button class="btn btn-success" onclick="save_amenities()">Save</button>
								</div>
						</section>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
	var baseUrl='<?php echo base_url();?>';
	$(function() {
		$("form[name='user_form']").validate({
			rules: {
					user_name : "required"
			},  
			
			submitHandler: function(form) {
				form.submit();
			}
		});
	});
	
	function auto_upload_photo(type){
		$("#"+type ).submit();	
	}
	
	function clear_fields(){
		$("input[type=text], textarea").val("");
	}
	
	function save_users(){
		var filename 		= baseUrl+'admin/user/save_users';
		var request  = $.ajax({
		  url  : filename,
		  type : "POST",
		  data : {     
				user_name     	:  $("#user_name").val(), 
				short_name     	:  $("#short_name").val(), 
				job_title     	:  $("#job_title").val(), 
				facebook_id     :  $("#facebook_id").val(), 
				user_description:  $("#user_description").val(), 
				mobile_no 		:  $("#mobile_no").val(),
				user_photo_name :  $("#user_photo_name").val()

			},
			dataType : "html"
		});
		
		request.done(function(result){
			var output    = jQuery.parseJSON(result);
			location.href = baseUrl+"admin/user/user_list";
		});
		
		request.fail(function(jqXHR,textStatus){
		  alert(textStatus);
		});     		
	}
	</script>
  </body>
<?php 

$this->load->view('template/footer'); ?>