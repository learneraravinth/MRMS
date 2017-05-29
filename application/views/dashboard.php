<?php 
$this->load->view('template/header_user');
 ?>
 	<link href="<?php echo base_url(); ?>css/bootstrap-datetimepicker.css" rel="stylesheet">
	<link href='<?php echo base_url();  ?>fullcalendar/fullcalendar.css' rel='stylesheet' />
	<link href='<?php echo base_url();  ?>fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
	<script src='<?php echo base_url(); ?>fullcalendar/lib/moment.min.js'></script>
	<script src='<?php echo base_url(); ?>fullcalendar/fullcalendar.min.js'></script>
	<script src='<?php echo base_url(); ?>js/bootstrap-datetimepicker.js'></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/font-awesome.min.css">
	<link href='<?php echo base_url();  ?>css/style_meeting.css' rel='stylesheet' />
		 <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
<style>


.modal-body {
    position: relative;
    padding: 26px;
}
.datetimepicker5{ z-index:1151 !important;}
.fc-unthemed .fc-today !impartant{
    background: #ffffff;
}
</style>
<script>

var baseUrl	='<?php echo base_url();?>';
var day		='<?php echo date('Y-m-d');?>';
		$(document).ready(function()
		{
			var date = new Date();
			var d = date.getDate();
			var m = date.getMonth();
			var y = date.getFullYear();
		});
  </script> 
  <script type="text/javascript">
 	$(function () {	
		$('.datetimepicker5').datetimepicker({
		
		})
		.on('changeDate', function(ev){                 
				$('.datetimepicker5').datetimepicker('hide');
		});
	});
</script>
  <body>
  	<?php $this->load->view('template/sidebar_user'); ?>
      <!--sidebar end-->
      <!--main content start-->
		<section id="main-content">
			<section class="wrapper">
              <!--state overview start-->
				<div class="row state-overview">
					<div class="col-lg-6 col-sm-8 bg height-a">
						<section class="panel bg">
							<div class="container">
								<div class="row">
									<div id="carousel-example-generic" class="carousel slide width" data-ride="carousel">
									  <!-- Indicators -->
										<div class="carousel-inner height" role="listbox">
										  <?php $i = 1; 
										  foreach($room_details as $value){ ?>
											<div class="item <?php if ($i==1){ echo 'active'; } ?>" data-id="<?php echo $value->id ?>">
												<h2 class="center">
												<?php  echo $value->room_name; ?>
												</h2>
												<div id="show_val_<?php echo $value->id; ?>" class="center show_cls"></div>
											</div>
											<?php
											$i = $i+1;
											} ?>
										</div>
										
										<div class="row" id = "show_booked">
											<div class="col-md-6 center">
												<input type="button" name="Extend" onclick ="end_meeting()" value="End" class="btn-default btn-down btnclr">
											</div>	
											<div class="col-md-6 center">
												<input type="button" name="End" value="Extend" class="btn-default btn-down btnclr" onclick ="ex_meeting()" >
											</div>	
										</div>
										
										<div id="lclose">
										  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
											<span class="glyphicon glyphicon-chevron-left fa fa-chevron-left" aria-hidden="true"></span>
											<span class="sr-only">Previous</span>
										  </a>
										</div>
									  
										<div id="rclose">
										  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
											<span class="glyphicon glyphicon-chevron-right  fa fa-chevron-right" aria-hidden="true"></span>
											<span class="sr-only">Next</span>
										  </a>
										</div>
									</div>
								</div>
							</div>
						</section>
					</div>	

					<div class="col-lg-6 col-sm-8 height-b">
						<section class="panel">
							<div id='calendar'></div>		
						</section>
					</div>
					</div>
                  <input class="form-control" id="from_date" name="from_date" type="hidden">
				  <input class="form-control" id="to_date" name="to_date" type="hidden">
				  <input class="form-control" id="room_id" name="room_id" type="hidden">
				  <input class="form-control" id="hid_flag" name="hid_flag" type="hidden">
				
		</section>
	</section>
	<div class="modal fade " id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				
				<div class="modal-body">
					<div class="col-lg-15S">
						<section class="panel">
							<div class="panel-body">
							 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
								<div class="row">
							<div class="col-md-12 bg-full">
								<h2 class="text-center"><div id="remos">Add New Meeting</div></h2>
								<div class="row margin-top">
									<div class="col-md-1">
										<i class="fa fa-building-o" aria-hidden="true"></i>
									</div>
									<div class="col-md-2">
										<label for="inputlg">Room Name :</label>
									</div>
									<div class="col-md-9">
										<select id= "room_names" disabled name="room_names" class="form-control selectpicker width">
											<?php foreach($room_details as $value){ ?>
										<option value="<?php echo $value->id; ?>"> <?php echo $value->room_name; ?></option>
										<?php }  ?>
										</select>
									</div>
								</div>
								
								<div class="row margin-top">
									<div class="col-md-1">
										<i class="fa fa-clock-o" aria-hidden="true"></i>
									</div>
									<div class="col-md-5">
										<div class="form-group">
											<div class="input-group date form_time">
												<input class="form-control datetimepicker5"  id="start_dt" name="start_dt" size="16" type="text" value="" disabled placeholder="Start Date">
												<span class="input-group-addon">
												<span class="fa-clock-o"></span>
												</span>
											</div>
										</div>
									</div>
									<div class="col-md-1">
										<i class="fa fa-clock-o" aria-hidden="true"></i>
									</div>
									<div class="form-group">
										<div class="input-group date form_time col-md-5">
											<input class="form-control datetimepicker5" size="16" type="text" id="end_dt" name="end_dt" value="" disabled placeholder="End Date">
											<span class="input-group-addon"><span class="fa-clock-o"></span></span>
										</div>
									</div>
								</div>
								
								<div class="row margin-top">
									<div class="col-md-1"><i class="fa fa-magic" aria-hidden="true"></i></div>
									<div class="col-md-11">
										<div class="form-group margin-top">
											<input class="form-control" id="meeting_title" name="meeting_title" placeholder="Enter Ttile">
										</div>
									</div>
								</div>
								
								<div class="row margin-top">
									<div class="col-md-1"><i class="fa fa-ticket" aria-hidden="true"></i></div>
									<div class="col-md-11">
										<div class="form-group">
											<input class="form-control" id="meeting_agenda" name="meeting_agenda" placeholder="Enter Agenda">
										</div>
									</div>
								</div>
								<div class="row margin-top">
									<div class="col-md-1">
										<i class="fa fa-mail-forward (alias)" aria-hidden="true"></i>
									</div>
									<div class="col-md-2">
										<label for="inputlg">Send By :</label>
									</div>
									<div class="col-md-9">
										<select id= "send_by" name="send_by" onchange="choose_sendby()" class="form-control selectpicker width">
											<option value="user" > User</option>
											<option value="group" > Group</option>
										</select>
									</div>
								</div>
								
								<div class="row margin-top" id="user_div">
									<div class="col-md-1"><i class="fa fa-user" aria-hidden="true"></i></div>
									<div class="col-md-11">
										<div class="form-group margin-top">
											<select id = "room_invities_u" multiple name="room_invities_u" class="form-control user_sel">
												<?php foreach($users_details as $value){ ?>
												<option value="<?php echo $value->id; ?>"> <?php echo $value->user_name; ?></option>
												<?php }  ?>
											</select>
										</div>
									</div>
								</div>
								
								<div class="row margin-top" id="goup_div">
									<div class="col-md-1"><i class="fa fa-group (alias)" aria-hidden="true"></i></div>
									<div class="col-md-11">
										<div class="form-group margin-top">
											<select id = "room_invities_g" multiple name="room_invities_g" class="form-control group_sel">
												<?php foreach($group_details as $value){ ?>
												<option value="<?php echo $value->id; ?>"> <?php echo $value->group_name; ?></option>
												<?php }  ?>
											</select>
										</div>
									</div>
								</div>
								
								<div class="row margin-top">
									<button onclick="save_meeting()" class="btn btn-primary btn-center-get">Submit</button>
								</div>
								<div id="show_div" style="display:none;">
								<img src="<?php echo base_url(); ?>/img/loader.gif" alt="Smiley face" height="42" width="42">
							</div>
							</div>
						</div>	
						</section>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal -->
		<div class="modal fade full-width-modal-right" id="myModal17" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-sm">
			  <div class="modal-content-wrap">
				  <div class="modal-content">
						<div class="modal-header">
						  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						  <h4 class="modal-title"><span id="sroom_name"> </span> Booking Details</h4>
						</div>
						
						<div class="modal-body"> 
							<div class="row"> 
								<p><span>Start Date </span>: <span id="ssdate"></span></p>
							</div>
						</div>
						<div class="modal-body"> 
							<div class="row">
								<p><span>End Date </span>: <span id="sedate"></span></p>
							</div>
						</div><div class="modal-body"> 
							<div class="row">
								<p><span>Title </span>: <span id="stitle"></span></p>
							</div>
						</div>
						
						<div class="modal-body">
							<div class="row">
								<p><span>Agenda </span>: <span id="sagenda"></span></p>
							</div>
						</div>
						
						<div class="modal-body">
							<div class="row">
								<p><span>Invities </span>: <span id="sinvities"></span></p>
							</div>
						</div>
						
						<div class="modal-footer">
							<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
						</div>
				  </div>

			  </div>
		  </div>
		</div>
		<!-- modal -->
		<style>
		.disabled .fc-day-content {
			background-color: #123959;
			color: #FFFFFF;
			cursor: default;
		}
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
			
			$(".fc-bg").addClass("disabled");


			$('.carousel').carousel({
					interval: false
			}); 
			
			$(".user_sel").select2({
			  placeholder: "Select Users",
			  allowClear: true
			});
			
			$(".group_sel").select2({
			  placeholder: "Select Groups",
			  allowClear: true
			});
			
			var wid  = $("#room_names").val();
			$("#room_id").val(wid);
			$("#lclose").hide();
			calendar_show();
			var heightHeader   = $("header.header").height() + 20;
			var activeEmployee = $(window).height() - heightHeader;
			
			$(".auto-height>section").css("height", activeEmployee);
				$(".right.carousel-control").click(function (){
				var rid = $(".item.active").next().data('id');
				$("#room_id").val(rid);
				$("#room_names").val(rid);
				if($(".item.active").next().next().data('id')==null){
					$("#rclose").hide();
				}
				$("#lclose").show();
				calendar_show();
			});
			
			$(".left.carousel-control").click(function (){
				var lid = $(".item.active").prev().data('id');
				$("#room_id").val(lid);
				$("#room_names").val(lid);
				if($(".item.active").prev().prev().data('id')==null){
					$("#lclose").hide();
				}
				$("#rclose").show();
				calendar_show();
			});
		});
		
		function end_meeting(){
			swal({
			  title: "Are you sure?",
			  text: "The Meeting will end now!",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonClass: "btn-warning",
			  confirmButtonText: "Yes, End Now!",
			  closeOnConfirm: false
			},
			function(){
				exicute_code();
			return false;
			});
		}
		
		function exicute_code(){
			var filename = baseUrl+'dashboard_user/end_meeting';
			var request  = $.ajax({
				url  : filename,
				type : "POST",
				data : {       
					room_id   : $("#room_id").val() 		
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
		
		function ex_meeting(){
			$("#remos").text("");
			$("#remos").text("Extend Meeting");
			$("#end_dt").prop("disabled", false);
			$("#myModal").modal();
		}
		function extend_meeting(){
			var filename = baseUrl+'dashboard_user/extend_meeting';
			var request  = $.ajax({
				url  : filename,
				type : "POST",
				data : {       
					room_id     : $("#room_id").val(),		
					startdate   : $("#from_date").val(), 		
					enddate     : $("#to_date").val(), 		
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

		function calendar_show(){
			var room_id = $("#room_id").val();
			var filename = baseUrl+'dashboard_user/load_calendar';
			var request  = $.ajax({
				url  : filename,
				type : "POST",
				data : {       
					room_id  		: room_id
				},
				dataType : "html"
			});
			
			request.done(function(result){
				var output    = jQuery.parseJSON(result);
					$("#show_val_"+room_id).html("");
					if(output.status=='Available'){
						$("#show_booked").hide();
					}else{
						$("#show_booked").show();
					}
					$("#from_date").val(output.strdate);
					$("#to_date").val(output.strdate);
					$("#meeting_title").val(output.stitle);
					$("#meeting_agenda").val(output.sagenda);
					$("#send_by").val(output.send_by);
					$("#room_invities").val(output.grp_user);
					
					$("#start_dt").val(output.strdate);
					$("#end_dt").val(output.enddate);
					choose_sendby();
					$("#show_val_"+room_id).append(output.status);
					$('#calendar').fullCalendar('destroy');
					var calendar = $('#calendar').fullCalendar(
					{
						header: {
							left: 'prev,next today',
							center: 'title',
							right: 'month,agendaWeek,agendaDay'
						},
						
						defaultDate: day,
						defaultView: 'basicDay',
						selectable: true,
						selectHelper: true,
						slotDuration: '00:15:00',
						slotLabelInterval: 15,
						slotLabelFormat: 'h(:mm)a',
						selectOverlap: function(event) {
							return event.rendering === 'background';
						},
						
						disableDragging: false,
						eventClick:  function(event, jsEvent, view) {
							var start_time  = moment(event.start).format("YYYY-MM-DD HH:mm:ss");
							var end_time 	= moment(event.end).format("YYYY-MM-DD HH:mm:ss");
							var title 		= event.title;
							view_meeting(start_time,end_time,title);
						},
						
						dayClick: function (date, jsEvent, view) {
							var str = 0;
							if(view.type == "basicDay"){
								 str = 1;
							}
							$("#hid_flag").val(str);
						},
					
						editable: false,
				
						select: function(start, end, allDay,today)
						{
							var start1	 = start.format("YYYY-MM-DD HH:mm:ss");
							var end1	 = end.format("YYYY-MM-DD HH:mm:ss");
							 $("#remos").text("");
							$("#remos").text("Add New Meeting"); 
							$("#from_date").val("");
							$("#from_date").val(start1);
							$("#start_dt").val(start1);
							$("#end_dt").val(end1);
							$("#to_date").val("");
							$("#to_date").val(end1);
							var str = $("#hid_flag").val();
							if(str!=1){
								$("#myModal").modal();
							}
							if (title)
							{
								calendar.fullCalendar('renderEvent',
								{
										title: title,
										start: start,
										end: end,
										allDay: allDay
								},
								true // make the event "stick"
								);
							}
							calendar.fullCalendar('unselect');
						},
						editable: true,
						events: output.event_array
					});
					
				});
			
			request.fail(function(jqXHR,textStatus){
				alert(textStatus);
			});	
		}
		
		function save_meeting(){
			var send_by = $("#send_by").val();
			var room_invities = '';
			if(send_by=='user'){
				room_invities = $("#room_invities_u").val();
			}else{	
				room_invities = $("#room_invities_g").val();
			}
			$('#show_div').css({'display':'block'});
			var filename = baseUrl+'dashboard_user/save_meeting';
			var room_id = $("#room_id").val();
			var request  = $.ajax({
				url  : filename,
				type : "POST",
				data : {       
					room_id  		: room_id, 	
					room_names  	: $("#room_names").val(), 	
					meeting_title   : $("#meeting_title").val(), 	
					meeting_agenda  : $("#meeting_agenda").val(), 	
					room_invities   : room_invities, 	
					send_by   		: send_by, 	
					from_date    	: $("#from_date").val(), 	
					to_date   	 	: $("#to_date").val(), 	
				},
				dataType : "html"
			});
			
			request.done(function(result){
				$('#show_div').css({'display':'none'});	
				$("#meeting_title").val("");
				$("#meeting_agenda").val("");
				$(".room_invities").select2("val", "");
				$(".close").trigger("click");
				calendar_show(room_id);
				
			});
			
			request.fail(function(jqXHR,textStatus){
				alert(textStatus);
			});			
		}
		
		function view_meeting(start,end,title){
			var filename = baseUrl+'dashboard_user/view_meeting';
			var request  = $.ajax({
				url  : filename,
				type : "POST",
				data : {       
					room_id   : $("#room_id").val(), 	
					start_time: start, 	
					end_time  : end, 	
					title     : title, 	
				},
				dataType : "html"
			});
			
			request.done(function(result){   
				var output    = jQuery.parseJSON(result);
				$("#sroom_name").text('');  
				$("#ssdate").text(output.data[0]['start_date']);
				$("#sedate").text(output.data[0]['end_date']);
				$("#sroom_name").text(output.data[0]['rname']);
				$("#stitle").text(output.data[0]['title']);
				$("#sagenda").text(output.data[0]['agenda']);
				$("#sinvities").text(output.invities);
				$("#myModal17").modal();
			});
			
			request.fail(function(jqXHR,textStatus){
				alert(textStatus);
			});			
		}
		
		function choose_sendby(){
			var send_by = $("#send_by").val();
			if(send_by=="user"){
				$("#user_div").show();
				$("#goup_div").hide();
			}else{
				$("#user_div").hide();
				$("#goup_div").show();
			}
		}
</script>
  </body>
<?php 

$this->load->view('template/footer'); ?>
		