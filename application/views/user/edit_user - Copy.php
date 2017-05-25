<?php 
//echo '<pre>';print_r($user_data);exit;
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
							<div class="form-group has-success">
							  <label class="col-sm-2 control-label col-lg-2" for="inputSuccess">User Name</label>
							  <div class="col-lg-10">
								  <input type="text" placeholder = "Enter Name" class="form-control" id="user_name" name="user_name" value="<?php echo $user_data[0]->user_name; ?>">
							  </div>
							</div>
						  
							<div class="form-group has-success">
							  <label class="col-sm-2 control-label col-lg-2" for="inputSuccess">Short Name</label>
							  <div class="col-lg-10">
								  <input type="text" placeholder = "Enter Short Name" class="form-control" id="short_name" name="short_name"  value="<?php echo $user_data[0]->short_name; ?>">
							  </div>
							</div>
							<div class="form-group has-success">
							  <label class="col-sm-2 control-label col-lg-2" for="inputSuccess">Job Title</label>
							  <div class="col-lg-10"> 
								  <input type="text" placeholder = "Enter job Title" class="form-control" id="job_title" name="job_title"  value="<?php echo $user_data[0]->job_title; ?>">
							  </div>
							</div>
							<div class="form-group has-success">
							  <label class="col-sm-2 control-label col-lg-2" for="inputSuccess">FaceBook Id</label>
							  <div class="col-lg-10">
								  <input type="text" placeholder = "Enter facebook id" class="form-control" id="facebook_id" name="facebook_id" value="<?php echo $user_data[0]->face_bookid; ?>">
							  </div>
							</div>
						  
							<div class="form-group has-warning">
							  <label class="col-sm-2 control-label col-lg-2" for="inputWarning">Description</label>
							  <div class="col-lg-10">
							   <textarea id="user_description" name="user_description" placeholder="Enter Descriprtion" class="form-control" cols="10" rows="4"><?php echo $user_data[0]->description; ?></textarea>
							  </div>
							</div>
							<input type="hidden" id="user_id" name="user_id" value="<?php echo  $user_data[0]->id;?>">
							<div class="col-lg-offset-2 col-lg-10">
							  <button class="btn btn-danger" type="submit">Update</button>
							  <button class="btn btn-default" onclick="clear_fields()"  type="button">Clear</button>
							</div>
						</form>
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
	
	
	function clear_fields(){
		$("input[type=text], textarea").val("");
	}
	</script>
  </body>
<?php 

$this->load->view('template/footer'); ?>