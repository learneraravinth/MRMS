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
						Room Details:
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
							  <th>Room Name</th>
							  <th>Seating_capacity</th>
							  <th>Description</th>
							  <th>Room Amenity</th>
							  <th>Room Photo</th>
							  <th>Action</th>
							</tr>
						</thead>
						
						<tbody>
							<?php 
							foreach($room_details as $value){ ?>
							<tr class="gradeX">
								<td><?php echo $value->room_name; ?></td>
								<td><?php echo $value->seating_capacity; ?></td>
								<td><?php echo substr($value->description,0,20); ?></td>
								<td>
								<?php 
								$amenities_name =array();
								foreach($amenities_details as $amen){
									$grp_members = explode(',',$value->room_amenity);
									if(in_array($amen->id,$grp_members)){
										$amenities_name[] = $amen->amenities_name;
									}
								}
								$amenitiesname = implode(',',$amenities_name);
								echo  $amenitiesname; ?>
								</td>
								<td><?php echo $value->room_photo; ?></td>
								<td><a onclick="room_delete('<?php echo  $value->id; ?>')" href= "javacript:void(0)"> Delete </a> /
								<a href= "<?php echo base_url(); ?>admin/meeting_room/edit_meeting/<?php echo  $value->id; ?>"> Edit </a>
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
	function room_delete(meeting_id){
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
			exicute_delete(meeting_id);
		});
	}

	function exicute_delete(meeting_id){
		var filename = baseUrl+'admin/meeting_room/delete_meeting';

		var request  = $.ajax({
			url  : filename,
			type : "POST",
			data : {   
					meeting_id   	 : meeting_id, 
				},
			dataType : "html"
		});

		request.done(function(result){
			var output    = jQuery.parseJSON(result);
			if(output.flag == 1){
				swal("Saved!", "Successfully Deleted.!", "success");
				location.href = baseUrl+"admin/meeting_room/room_list";
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