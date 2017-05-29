<?php 
//echo '<pre>';print_r($user_data);exit;
$this->load->view('template/header_user');

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
  	<?php $this->load->view('template/sidebar_user'); ?>
      <!--sidebar end-->
      <!--main content start-->
		<section id="main-content">
			<section class="wrapper">
              <section class="panel">
				<div class="panel-body">
						<form class="form-horizontal tasi-form" name= "user_form"  id="user_form" method="post" action="<?php echo base_url();?>loginuser/save_users">
						
							<div class="form-group">
								 <label class="col-lg-2 control-label">User Name :</label>
								 <div class="col-lg-4">
									  <input type="text" placeholder = "Enter User Name" class="form-control" id="user_name" name="user_name" value="<?php echo $user_data[0]->user_name; ?>">
								  </div>

								  <label class="col-lg-2 control-label">Short Name :</label>
								  <div class="col-lg-4">
								  <input type="text" placeholder = "Enter Short Name" class="form-control" id="short_name" name="short_name"  value="<?php echo $user_data[0]->short_name; ?>">
								 </div>
							</div>
							
							<div class="form-group">
								<label class="col-lg-2 control-label">Job Title :</label>
								<div class="col-lg-4">
									  <input type="text" placeholder = "Enter job Title" class="form-control" id="job_title" name="job_title"  value="<?php echo $user_data[0]->job_title; ?>">
								</div>

								<label class="col-lg-2 control-label">FaceBook Id :</label>
								<div class="col-lg-4">
									<input type="text" placeholder = "Enter facebook id" class="form-control" id="facebook_id" name="facebook_id" value="<?php echo $user_data[0]->face_bookid; ?>">
								</div>
							</div>
							<div class="form-group">
								 
								  <label class="col-lg-2 control-label">Description :</label>
								  <div class="col-lg-4">
								    <textarea id="user_description" name="user_description" placeholder="Enter Descriprtion" class="form-control" cols="10" rows="4"><?php echo $user_data[0]->description; ?></textarea>
								 </div>

								 <label class="col-lg-2 control-label">Mobile No :</label>
								 <div class="col-lg-4">
									  <input type="text" class="form-control" name="mobile_no" id="mobile_no" placeholder="Enter Mobile No" value="<?php echo $user_data[0]->mobile_no; ?>" >
								  </div>
							</div>
						</form>
						<div class="form-group remos">
							<label class="control-label col-md-3">User Photo</label>
							<form enctype="multipart/form-data" id= "admin_photofrm1" name="admin_photofrm1" class= "form-horizontal tasi-form">
								<div class="col-md-4">
									<input type="file" class="default" id="user_photo1" name="user_photo1" onchange="auto_upload_photo('user_photo1');">
									<input type="hidden" id="user_photo_name" name="user_photo_name" value="<?php echo $user_data[0]->user_photo; ?>">
								</div>
							</form> 
							<div id="show_img_user">
								<?php if(!empty($user_data[0]->user_photo)){
									$user_photo = base_url().'uploads/admin_upload/'.$user_data[0]->user_photo;	
								}else{
									$user_photo = base_url().'img/user_unknown.jpg';
								}
								?>
								<img height="89" width="90" id="theImg" href="<?php echo$user_photo  ?>">
							</div>
						</div>
						<input type="hidden" id="user_id" name="user_id" value="<?php echo  $user_data[0]->id;?>">
						<div class="col-lg-offset-2 col-lg-10">
						  <button class="btn btn-danger" type="button" onclick="save_users()">Update</button>
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
		var filename 		= baseUrl+'dashboard_user/save_users';
		var request  = $.ajax({
		  url  : filename,
		  type : "POST",
		  data : {     
				user_id     	:  $("#user_id").val(), 
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
			location.reload();
		});
		
		request.fail(function(jqXHR,textStatus){
		  alert(textStatus);
		});     		
	}
	</script>
  </body>
<?php 

$this->load->view('template/footer'); ?>