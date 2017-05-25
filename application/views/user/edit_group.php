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
						<form class="form-horizontal tasi-form" name= "group_form"  id="group_form">
							<div class="form-group has-success">
							  <label class="col-sm-2 control-label col-lg-2" for="inputSuccess">Group Name</label>
							  <div class="col-lg-10">
								  <input type="text" placeholder = "Enter Group Name" class="form-control" id="group_name" name="group_name" value="<?php echo $group_data[0]->group_name; ?>">
							  </div>
							</div>
						  
							<div class="form-group has-success">
								<label class="col-sm-2 control-label col-lg-2" for="inputSuccess">Member's</label>
								<div class="col-lg-10">
									  <select class="form-control user_sel" multiple id="grp_members" name="grp_members">
									  <option value= "">--Select--</option>
									  <?php 
										foreach($users_details as $value){
											$grp_det = $group_data[0]->grp_members;
											$arr_data = explode(",",$grp_det);
											$sel = '';
											if(in_array($value->id,$arr_data)){
												$sel = 'selected';
											}
									  ?>
  										<option <?php echo $sel; ?> value= "<?php echo $value->id; ?>"><?php echo $value->user_name; ?></option>  
									  <?php
									  } 
									  ?>
									  </select>
								</div>
							</div>
							
							<input type="hidden" id="group_id" name="group_id" value="<?php echo$group_data[0]->id; ?>">
							
							<div class="col-lg-offset-2 col-lg-10">
							  <button class="btn btn-danger" onclick = "save_group()" type="button">Update</button>
							  <button class="btn btn-default" onclick="clear_fields()"  type="button">Clear</button>
							</div>
						</form>
				</div>
			  </section>
		</section>
	</section>
	
	<script>
	var baseUrl='<?php echo base_url();?>';
	$(document).ready(function(){
		$(".user_sel").select2({
			  placeholder: "Select Users",
			  allowClear: true
		});
		
		$("#group_form").validate({
		rules: {
			group_name: {
				required:true,
			},
			grp_members: {
				required:true,
			},
			
		},
     	 
		submitHandler: function(form){
			form.submit();
		}
     
});
	});

	function clear_fields(){
		$(".user_sel").select2("val", "");
		$("input[type=text], textarea").val("");
	}
	function save_group(){
		var is_valid	= $('#group_form').valid();
		if(!is_valid)
		{
			return false;
		}
		var filename = baseUrl+'admin/user/save_group';
		var request  = $.ajax({
			url  : filename,
			type : "POST",
			data : {			 					
					group_name   		 : $("#group_name").val(),  
					grp_members   		 : $("#grp_members").val(), 
					group_id   			 : $("#group_id").val() 
			},
				dataType : "html"
		});
		
		request.done(function(result){
			var output    = jQuery.parseJSON(result);
			if(output.flag==0){
				swal("Oops...", "Group details already exist!", "error");
			}else{
				location.href = baseUrl+"admin/user/list_group";
			}
		});
		
		request.fail(function(jqXHR,textStatus){
			alert(textStatus);
		});		
	}
	</script>
  </body>
<?php 

$this->load->view('template/footer'); ?>