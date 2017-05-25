<?php $this->load->view('template/header'); ?>
  <body>
  <?php
    $query  	= 'SELECT * FROM users WHERE is_active=1';
	$rs 		= $this->db->query($query);
	$data_array = $rs->result_array();
	$this->load->view('template/sidebar'); ?>
	<section id="container" class="">
      <section id="main-content">
          <section class="wrapper">
              <!-- page start-->
				<section class="panel">
					<header class="panel-heading">
						Group Details:
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
							  <th>Group Details</th>
							  <th>Action</th>
							</tr>
						</thead>
						
						<tbody>
							<?php 
							foreach($group_details as $value){ ?>
							<tr class="gradeX">
								<td><?php echo $value->group_name; ?></td>
								<td><?php 
								$user_name =array();
								foreach($data_array as $user){
									$grp_members = explode(',',$value->grp_members);
									
									if(in_array($user['id'],$grp_members)){
										$user_name[] = $user['user_name'];
									}

								}
								$username = implode(',',$user_name);
								echo  $username; ?></td>
								<td><a onclick="group_delete('<?php echo  $value->id; ?>')" href= "javacript:void(0)"> Delete </a> /
								<a href= "<?php echo base_url(); ?>admin/user/edit_group/<?php echo  $value->id; ?>"> Edit </a>
								</td>
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
	function group_delete(group_id){
		swal({
		  title: "Are you sure?",
		  text: "Your will not be able to recover this data!",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonClass: "btn-danger",
		  confirmButtonText: "Yes, delete it!",
		  closeOnConfirm: false
		},
		
		function(){
			exicute_delete(group_id);
		});
	}

	function exicute_delete(group_id){
		var filename = baseUrl+'admin/user/delete_group';

		var request  = $.ajax({
			url  : filename,
			type : "POST",
			data : {   
					group_id   	 : group_id, 
				},
			dataType : "html"
		});

		request.done(function(result){
			var output    = jQuery.parseJSON(result);
			if(output.flag == 1){
				swal("Saved!", "Successfully Deleted.!", "success");
				location.href = baseUrl+"admin/user/list_group";
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