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
                              <form class="form-horizontal tasi-form" name="meeting_form" method="post" action="<?php echo base_url();?>admin/meeting_room/save_meeting_details">
									<div class="form-group has-success">
                                      <label class="col-sm-2 control-label col-lg-2" for="inputSuccess">Room Name</label>
                                      <div class="col-lg-10">
                                          <input type="text" placeholder = "Room Name" class="form-control" id="room_name" name="room_name" value="<?php echo $meeting_data[0]->room_name; ?>">
                                      </div>
									</div>
								  
									<div class="form-group has-success">
                                      <label class="col-sm-2 control-label col-lg-2" for="inputSuccess">Seating Capacity</label>
                                      <div class="col-lg-10">
                                          <input type="text" placeholder = "Seating Capacity" class="form-control" id="seat_capacity" name="seat_capacity" value="<?php echo $meeting_data[0]->	seating_capacity; ?>">
                                      </div>
									</div>
								  
									<div class="form-group has-warning">
                                      <label class="col-sm-2 control-label col-lg-2" for="inputWarning">Description</label>
                                      <div class="col-lg-10">
                                       <textarea id="room_description" name="room_description" placeholder="Room Descriprtion" class="form-control" cols="10" rows="4"><?php echo $meeting_data[0]->	description; ?></textarea>
                                      </div>
									</div>
									
									<div class="form-group">
                                      <label class="col-sm-2 control-label col-lg-2" for="inputSuccess">Room Amenity</label>
                                      <div class="col-lg-10">
											<label class="checkbox-inline">
											<?php foreach ($amenities_details as $value){ ?>
											  <input type="checkbox"  name ="room_amenity[]" id="amenities_show_<?php echo $value->id; ?>" value="<?php echo $value->id; ?>"><?php echo $value->amenities_name; } ?>
											 
											   <a href="#myModal" data-toggle="modal" class="btn btn-xs btn-success first_de">
													<i class="fa fa-plus"></i>
												</a>
												 <div id = "append_div" ></div>
											</label>
											
                                      </div>
									</div>
									
									<div class="form-group">
                                          <label class="control-label col-md-3">Room Photo</label>
                                          <div class="col-md-4">
                                              <input type="file" class="default" id="meeting_photo" name="meeting_photo">
                                          </div>
										   
                                      </div>
									  <input type="hidden" id="room_id" name="room_id" value="<?php echo $meeting_data[0]->	id; ?>"> 
									<div class="col-lg-offset-2 col-lg-10">
                                              <button class="btn btn-danger" type="submit">Update</button>
                                              <button class="btn btn-default" onclick="clear_fields()" type="button">Cancel</button>
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
	function save_amenities(){
	var is_valid  = $('#amenities_form').valid();
    if(!is_valid)
    {
      return false;
    }
    var filename = baseUrl+'meeting_room/save_amenities';
    var amenities_name = $("#amenities_name").val();
    var request  = $.ajax({
      url  : filename,
      type : "POST",
      data : {                
            amenities_name       : amenities_name, 
        },
        dataType : "html"
    });
    
    request.done(function(result){
		var output    = jQuery.parseJSON(result);
		if(output.flag == 1){
			var html = "";
			html += "<label class='checkbox-inline'>";
			html += "<input type='checkbox' name= 'room_amenity[]'  checked id='amenities_show_"+output.id+"'  name='amenities_show_"+output.id+"' value='"+output.id+"'>'"+amenities_name+"</label>";
			$("#append_div").append(html);
			$( ".close" ).click();
		}
        if(output.flag == 3){
		   swal(
			  'Oops...',
			  'Amenity Already Exist!',
			  'error'
			);
		}   
    });
    
    request.fail(function(jqXHR,textStatus){
      alert(textStatus);
    });     		
}

		$(document).ready(function(){   
			$("form[name='meeting_form']").validate({
				rules: {
					seat_capacity : "required",
					room_name	  : "required"
				},  
				
				submitHandler: function(form) {
					form.submit();
				}
			});
		
			$("#amenities_form").validate({
			  rules: {
				amenities_name: {
				   required: true,
				},
			  
			  },
      
			  submitHandler: function (form) { 
				return false;
			  }
			}); 
  
			var heightHeader = $("header.header").height() + 20;
			var activeEmployee = $(window).height() - heightHeader;
			$(".auto-height>section").css("height", activeEmployee);
		});
		
		var baseUrl='<?php echo base_url();?>';
	
	
	
	function clear_fields(){
		$("input[type=text], textarea").val("");
	}
	</script>
  </body>
<?php 

$this->load->view('template/footer'); ?>