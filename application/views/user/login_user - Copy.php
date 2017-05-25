<?php $this->load->view('template/header'); ?>
  <body>
  <?php
  $this->load->view('template/sidebar'); ?>
	<section id="container" class="">
      <section id="main-content">
          <section class="wrapper">
              <!-- page start-->
				<section class="panel">
					<header class="panel-heading">
						User Details:
						<span class="pull-right"></span> 
						<div class="form-group"> 
							<div class="col-md-12"></div><br>
						</div>
					</header>
					<table class="display table table-bordered table-striped" id="dynamic-table">
						<h4>
							<p id="date_text"> </p>
						</h4>
						
						<thead>
							<tr>
							  <th>User Name</th>
							  <th>Short Name</th>
							  <th>Job Title</th>
							  <th>Facebook Id</th>
							  <th>Description</th>
							  <th>Action</th>
							</tr>
						</thead>
						
						<tbody>
							<?php 
							foreach($users_details as $value){ ?>
							<tr class="gradeX">
								<td><?php echo $value->user_name; ?></td>
								<td><?php echo $value->short_name; ?></td>
								<td><?php echo $value->job_title; ?></td>
								<td><?php echo $value->face_bookid; ?></td>
								<td><?php echo $value->description; ?></td>
								<td><a onclick="user_delete('<?php echo  $value->id; ?>')" href= "javacript:void(0)"> Delete </a> /
								<a href= "<?php echo base_url(); ?>user/edit_user/<?php echo  $value->id; ?>"> Edit </a></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</section>
              <!-- page end-->
          </section>
		</section>
      <!--main content end-->
      <!-- Right Slidebar end -->
	</section>
	<script>
	var baseUrl='<?php echo base_url();?>';
	function user_delete(user_id){
		swal({
		  title: "Are you sure?",
		  text: "Your will not be able to recover this imaginary file!",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonClass: "btn-danger",
		  confirmButtonText: "Yes, delete it!",
		  closeOnConfirm: false
		},
		
		function(){
			exicute_delete(user_id);
		});
	}

	function exicute_delete(user_id){
		var filename = baseUrl+'user/delete_user';

		var request  = $.ajax({
			url  : filename,
			type : "POST",
			data : {   
					user_id   	 : user_id, 
				},
			dataType : "html"
		});

		request.done(function(result){
			var output    = jQuery.parseJSON(result);
			if(output.flag == 1){
				swal("Saved!", "Successfully Deleted.!", "success");
				location.href = baseUrl+"user/user_list";
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
<?php $this->load->view('template/footer'); ?>
</body>
</html>