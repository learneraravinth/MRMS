 <?php 
 $controller = $this->uri->segment(2); // controller
 $fun_action = $this->uri->segment(3);
 ?>
 <aside>
  <div id="sidebar"  class="nav-collapse ">
	  <!-- sidebar menu start-->
		<ul class="sidebar-menu" id="nav-accordion">
			<li>
			  	<a href="<?php echo base_url('admin/dashboard');  ?>" class="dcjq-parent <?php if($controller== "dashboard"){ echo "active"; } ?>">
				  <i class="fa fa-dashboard"></i>
				  <span>Dashboard</span>
				</a>
			</li>
			<li>
			  	<a href="<?php echo base_url('admin/company_profile');  ?>" class="dcjq-parent <?php if($controller== "company_profile"){ echo "active"; } ?>">
				  <i class="fa  fa-building-o"></i>
				  <span>Company Profile</span>
				</a>
			</li>
			
			
			<li class="sub-menu dcjq-parent-li">
				<a href="<?php echo base_url('admin/meeting_room');  ?>" class="dcjq-parent <?php if($controller== "meeting_room"){ echo "active"; } ?>">
				  <i class="fa fa-sitemap" ></i>
				  <span>Room Info</span>
				  <span class="dcjq-icon"></span>
				</a>
				<ul class="sub" style="display: none;">
					<li class="<?php if($fun_action== "room_add"){ echo "active"; } ?>"> 
						<a class="<?php if($controller== "meeting_room"){ echo "active"; } ?>"  href="<?php echo base_url('admin/meeting_room/room_add');  ?>">Add New Room</a>
					</li>

					<li class="<?php if($fun_action== "room_list"){ echo "active"; } ?>"> 
						<a class="<?php if($controller== "meeting_room"){ echo "active"; } ?>"  href="<?php echo base_url('admin/meeting_room/room_list');  ?>">Room List</a>
					  </li> 
				</ul>
			</li>
			
			<li class="sub-menu dcjq-parent-li">
				<a href="<?php echo base_url('user/add_users');  ?>" class="dcjq-parent <?php if($controller== "user"){ echo "active"; } ?>">
				  <i class="fa fa-user" ></i>
				  <span>Employee</span>
				  <span class="dcjq-icon"></span>
				</a>
				<ul class="sub" style="display: none;">
					<li class="<?php if($fun_action== "add_users"){ echo "active"; } ?>"> 
						<a class="<?php if($controller== "user"){ echo "active"; } ?>"  href="<?php echo base_url('admin/user/add_users');  ?>">Add New User</a>
					</li>

					<li class="<?php if($fun_action== "user_list"){ echo "active"; } ?>"> 
						<a class="<?php if($controller== "user"){ echo "active"; } ?>"  href="<?php echo base_url('admin/user/user_list');  ?>">User List</a>
					</li> 
				</ul>
			</li>
			
			<li class="sub-menu dcjq-parent-li">
				<a href="<?php echo base_url('admin/user/add_users');  ?>" class="dcjq-parent <?php if($controller== "group_user"){ echo "active"; } ?>">
				  <i class="fa fa-users" ></i>
				  <span>Create Group</span>
				  <span class="dcjq-icon"></span>
				</a>
				<ul class="sub" style="display: none;">
					<li class="<?php if($fun_action== "create_group"){ echo "active"; } ?>"> 
						<a class="<?php if($controller== "group_user"){ echo "active"; } ?>"  href="<?php echo base_url('admin/group_user/create_group');  ?>">Create Group</a>
					</li>

					<li class="<?php if($fun_action== "list_group"){ echo "active"; } ?>"> 
						<a class="<?php if($controller== "group_user"){ echo "active"; } ?>"  href="<?php echo base_url('admin/group_user/list_group');  ?>">Group List</a>
					</li> 
				</ul>
			</li>
			
			<li class="sub-menu dcjq-parent-li">
				<a href="<?php echo base_url('admin/user/add_users');  ?>" class="dcjq-parent <?php if($controller== "booking"){ echo "active"; } ?>">
				  <i class="fa fa-info-circle" ></i>
				  <span>Room History</span>
				  <span class="dcjq-icon"></span>
				</a>
				<ul class="sub" style="display: none;">
					<li class="<?php if($fun_action== "history_booking"){
						echo "active"; 
						} ?>"> 
						<a class="<?php if($controller== "booking"){ echo "active"; } ?>"  href="<?php echo base_url('admin/booking/history_booking');  ?>">Room History</a>
					</li>
				</ul>
			</li>
		</ul>
	  <!-- sidebar menu end-->
  </div>
</aside>