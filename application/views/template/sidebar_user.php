 <?php 
 $controller = $this->uri->segment(2); // controller
 $fun_action = $this->uri->segment(3);
 $session_user = $this->session->userdata('loginuser');
	$user_idch = $session_user['user_id'];
 ?>
 <aside>
  <div id="sidebar"  class="nav-collapse ">
	  <!-- sidebar menu start-->
		<ul class="sidebar-menu" id="nav-accordion">
			<li>
			  	<a href="<?php echo base_url('dashboard_user');  ?>" class="dcjq-parent <?php if($controller== "dashboard_user"){ echo "active"; } ?>">
				  <i class="fa fa-dashboard"></i>
				  <span>Dashboard</span>
				</a>
			</li>
			<li>
			  	<a href="<?php echo base_url().'user_profile/'.$user_idch;  ?>" class="dcjq-parent <?php if($controller== "user_profile"){ echo "active"; } ?>">
				  <i class="fa fa-suitcase"></i>
				  <span>Profile</span>
				</a>
			</li>
			
		</ul>
	  <!-- sidebar menu end-->
  </div>
</aside>