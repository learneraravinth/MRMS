<?php $this->load->view('template/header'); ?>
  <body>
  <?php
 
  //echo '<pre>';print_r($room_history->result());exit;
  $this->load->view('template/sidebar'); ?>
  <style>
  .timeline {
  list-style: none;
  padding: 20px 0 20px;
  position: relative;
}
.timeline:before {
  top: 0;
  bottom: 0;
  position: absolute;
  content: " ";
  width: 3px;
  background-color: #eeeeee;
  left: 50%;
  margin-left: -1.5px;
}
.timeline > li {
  margin-bottom: 20px;
  position: relative;
}
.timeline > li:before,
.timeline > li:after {
  content: " ";
  display: table;
}
.timeline > li:after {
  clear: both;
}
.timeline > li:before,
.timeline > li:after {
  content: " ";
  display: table;
}
.timeline > li:after {
  clear: both;
}
.timeline > li > .timeline-panel {
  width: 46%;
  float: left;
  border: 1px solid #d4d4d4;
  border-radius: 2px;
  padding: 20px;
  position: relative;
  -webkit-box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
  box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
}
.timeline > li > .timeline-panel:before {
  position: absolute;
  top: 26px;
  right: -15px;
  display: inline-block;
  border-top: 15px solid transparent;
  border-left: 15px solid #ccc;
  border-right: 0 solid #ccc;
  border-bottom: 15px solid transparent;
  content: " ";
}
.timeline > li > .timeline-panel:after {
  position: absolute;
  top: 27px;
  right: -14px;
  display: inline-block;
  border-top: 14px solid transparent;
  border-left: 14px solid #fff;
  border-right: 0 solid #fff;
  border-bottom: 14px solid transparent;
  content: " ";
}
.timeline > li > .timeline-badge {
  color: #fff;
  width: 50px;
  height: 50px;
  line-height: 50px;
  font-size: 1.4em;
  text-align: center;
  position: absolute;
  top: 16px;
  left: 50%;
  margin-left: -25px;
  background-color: #999999;
  z-index: 100;
  border-top-right-radius: 50%;
  border-top-left-radius: 50%;
  border-bottom-right-radius: 50%;
  border-bottom-left-radius: 50%;
}
.timeline > li.timeline-inverted > .timeline-panel {
  float: right;
}
.timeline > li.timeline-inverted > .timeline-panel:before {
  border-left-width: 0;
  border-right-width: 15px;
  left: -15px;
  right: auto;
}
.timeline > li.timeline-inverted > .timeline-panel:after {
  border-left-width: 0;
  border-right-width: 14px;
  left: -14px;
  right: auto;
}
.timeline-badge.primary {
  background-color: #2e6da4 !important;
}
.timeline-badge.success {
  background-color: #3f903f !important;
}
.timeline-badge.warning {
  background-color: #f0ad4e !important;
}
.timeline-badge.danger {
  background-color: #d9534f !important;
}
.timeline-badge.info {
  background-color: #5bc0de !important;
}
.timeline-title {
  margin-top: 0;
  color: inherit;
}
.timeline-body > p,
.timeline-body > ul {
  margin-bottom: 0;
}
.timeline-body > p + p {
  margin-top: 5px;
}
.timeline-badge img {
    width: 94%;
}
li.timeline-inverted {
    width: 92%;
}
.timeline:before {
    top: 0;
    bottom: 0;
    position: absolute;
    content: " ";
    width: 1px;
    background-color: #eeeeee;
    left: 48.5%;
    margin-left: -1.5px;
}s
  </style>
  <script>
   $(document).ready(function(){    
	$('.datepicker9').datepicker({
		format: "yyyy/mm/dd",
	})
	.on('changeDate', function(ev){                 
		$('.datepicker9').datepicker('hide');
	});
		$(".room_sel").select2({
			  placeholder: "Select Users",
			  allowClear: true
		});
		
});
</script>
	<section id="container" class="">
      <section id="main-content">
          <section class="wrapper">
              <!-- page start-->
				<section class="panel">
				<header class="panel-heading">
						Search by Meeting Room Details:
						<span class="pull-right">
							<button class="btn btn-warning" onclick= "reports_download()"> Download Report</button>
						</span> 
						<div class="form-group"> 
							<div class="col-md-12">
							<form id="serach_meeting" name="serach_meeting" action="<?php echo base_url ('admin/meeting_room/search_room'); ?>" method="post">
								<?php 
								if(isset($search_data['meeting_date'] )){
									$data_date = $search_data['meeting_date'] ;
								}else{
									$data_date = "";
								}
								
								?>
								<input type="text" class="datepicker9" id="meeting_date" name="meeting_date" value="<?php echo $data_date; ?>">
								<select class="attendance_sel room_sel" id="room_searchs" name="room_searchs">
									<option value="">--Select--</option> 
									<?php
									$rm_name = '';
									foreach($room_details as $value){ 
										$sel_data = '';
										if(!empty($search_data['room_searchs'])){
											if($search_data['room_searchs'] == $value->id){
												$rm_name = $value->room_name;
												$sel_data = 'selected';
											}else{
												$sel_data = '';
											}
										}
									?>
										<option <?php echo  $sel_data; ?> value="<?php echo $value->id; ?>"><?php echo $value->room_name; ?></option> 
								<?php } ?>
								</select>
								<button class="btn btn-primary" type="submit" > Search </button>
							</form>
							</div>
							<br>
						</div>
				</header>
				<?php if(isset($room_history)) { ?>
				<div class="col-lg-12">
                      <section class="panel ">
							<div class="container">
								<div class="page-header">
									<h1 id="timeline"><?php echo $rm_name; ?></h1>
								</div>
								<ul class="timeline">
								<?php foreach ( $room_history->result_array() as $key=>$value){ 
								if ($key % 2 == 0) {
								  $type = "";
								}else{
								 $type = "timeline-inverted";	
								}
								?>
								<li class="<?php echo $type; ?>">
								  <div class="timeline-badge">
								  <img src="<?php echo base_url(); ?>img/logo/logo.png">
								  <i class="glyphicon glyphicon-check"></i></div>
								  <div class="timeline-panel">
									<div class="timeline-body a">
										<div class="bio-desk">
										  <h4 class="terques"><?php echo $value['agenda']; ?></h4>
										  <?php 
										  $start_date  = strtotime($value['start_date']);
										  $end_date    = strtotime($value['end_date']);?>
										  <p><i class=" fa fa-clock-o"></i></i> Time : <?php echo date('h:i:s a',$start_date).' - '.date('h:i:s a',$end_date); ?> </p>
										  <p>Title : <?php echo $value['title']; ?></p>
										  <p>Organizer : <?php
											$created_by = $value['created_by'];
											if($created_by==0){
												$created_room = 'Admin';
											}else{
												$created_room = '';
											}
										  echo $created_room; ?>
										  </p>
										  <p>Participants : <?php 
											if(!empty($value['invities'])){
												$user_name   = array();
												$invities    = $value['invities'];
												foreach($users_details as $user){
													$grp_members = explode(',',$invities);
													if(in_array($user->id,$grp_members)){
														$user_name[] = $user->user_name;
													}
												}
											}
											echo implode(',',$user_name);
											?></p>
										</div>
									</div>
								  </div>
								</li>
									<?php }	?>
									
									
								</ul>
							</div>

                      </section>
                  </div>
				<?php  } ?>
                      </section>
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
		var filename = baseUrl+'meeting_room/delete_meeting';

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
				location.href = baseUrl+"meeting_room/room_list";
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