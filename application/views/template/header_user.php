<?php 
	$session_user = $this->session->userdata('loginuser');
	$user_idch = $session_user['user_id'];
//echo $this->config->item('logo_image'); die;?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="2NT Meeting Room Managment system,mrms,combaodia">
    <meta name="author" content="2NTKH">
    <meta name="keyword" content="2NT Meeting Room Managment system,mrms,combaodia">
    <link rel="shortcut icon" href="img/favicon.png">
    <title><?php 
		 $controller = $this->uri->segment(1); 
		if(!empty($controller)){
			if($controller == 'user'){
				$title = 'Employee';	 
			}else{
				$title = ucfirst($controller);	 
			}
		}else{
			 $title= '2NT Meeting Room';
		}
		echo $title;
		$data 	   = admindata();
		$user_det  = remouser($user_idch);
		$user_name = $user_det[0]['user_name'];
		$user_photo = $user_det[0]['user_photo'];
		$users_id 	= $user_det[0]['id'];
		
	?>
	</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="<?php echo base_url(); ?>css/font-awesome.css" rel="stylesheet" />
   
    <link href="<?php echo base_url(); ?>css/demo_page.css" rel="stylesheet" />
	
	 <link href="<?php echo base_url(); ?>css/demo_table.css" rel="stylesheet" />
	 
    <link href="<?php echo base_url(); ?>css/DT_bootstrap.css" rel="stylesheet" />
	
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/owl.carousel.css" type="text/css">
	
	<link href="<?php echo base_url(); ?>css/sweetalert.css" rel="stylesheet">
    <!--right slidebar-->
    <link href="<?php echo base_url(); ?>css/slidebars.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/retina.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->

    <link href="<?php echo base_url(); ?>css/style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/style-responsive.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>css/select2.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>css/datepicker.css" rel="stylesheet" />

	<script src="<?php echo base_url(); ?>js/1.10.min.js"></script>
	<script type='text/javascript' src='<?php echo base_url(); ?>js/jquery.cookie.js'></script>
	<script type='text/javascript' src='<?php echo base_url(); ?>js/jquery.hoverIntent.minified.js'></script> 
	<script type='text/javascript' src='<?php echo base_url(); ?>js/accodianyy.js'></script>
	<script src="<?php echo base_url(); ?>js/sweetalert.min.js"></script>
	<script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
	<!---<script src="<?php echo base_url(); ?>js/jquery.scrollTo.min.js"></script>--->
	<!--right slidebar-->
	<script src="<?php echo base_url(); ?>js/slidebars.min.js"></script>
	<script src="<?php echo base_url(); ?>js/bootstrap-datepicker.js"></script>
	<!--<script src="<?php echo base_url(); ?>js/jquery.dcjqaccordion.2.7.js"></script>
	<script src="<?php echo base_url(); ?>js/respond.min.js"></script>--->
	<script src="<?php echo base_url(); ?>js/jquery.dataTables.js"></script>
	<script src="<?php echo base_url(); ?>js/DT_bootstrap.js"></script>
	<script src="<?php echo base_url(); ?>js/dynamic_table_init.js"></script>
	<script>
	var baseUrl='<?php echo base_url();?>';
	</script>
	<script src="<?php echo base_url(); ?>js/ajaxfileupload.js"></script>
	<script src="<?php echo base_url(); ?>js/uploadfiles.js"></script>
	<script src="<?php echo base_url(); ?>js/jquery.validate.js"></script>
	<script src="<?php echo base_url(); ?>js/select2.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/chart/jquery.canvasjs.min.js"></script>  
	<script src="<?php echo base_url(); ?>js/common-scripts.js"></script>
	<script src="<?php echo base_url(); ?>js/jquery.timepicker.js"></script>
  </head>
 
    <header class="header white-bg">
			<div class="sidebar-toggle-box">
                <div data-original-title="Toggle Navigation" data-placement="right" class="icon-reorder tooltips"></div>
            </div>
			<?php
			if(empty($data[0]['logo_name'])){
				$logo_name = 'img/logo/logo.png';
			}else{
				$logo_name = 'uploads/company_logo_upload/'.$data[0]['logo_name'];
			} ?>
          <a href="<?php echo base_url(); ?>" class="logo" ><img src="<?php echo base_url().$logo_name;?>" style="width:90px;height:70px;" ></a>
          <!--logo end-->
          <div class="nav notify-row" id="top_menu">
            <ul class="nav top-menu">
          </ul>
          </div>
          <div class="top-nav">
				<ul class="nav pull-right top-menu">
                  <li>
                      <input type="text" class="form-control search" placeholder="Search">
                  </li>
                  <!-- user login dropdown start-->
					<li class="dropdown">
						<a data-toggle="dropdown" class="dropdown-toggle" href="#">
							<?php if(empty($user_det[0]['user_photo'])){
								$user_photo = 'img/user_unknown.jpg';
							}else{
								$user_photo = 'uploads/admin_upload/'.$user_det[0]['user_photo'];
							} ?>
							<img alt="" src="<?php echo base_url().$user_photo; ?>" style="width:30;height:30px;">
                          <span class="username"> <?php echo $user_name; ?></span>
                          <b class="caret"></b>
						</a>
						
						<ul class="dropdown-menu extended logout">
                          <div class="log-arrow-up"></div>
                          <li>
						  <a href="<?php echo base_url('user_profile').'/'.$user_idch; ?>"><i class=" fa fa-suitcase"></i>Profile</a>
						  </li>
                         <li><a href="<?php echo base_url('admin/logout/logout_user'); ?>"><i class="fa fa-key"></i> Log Out</a></li>
						</ul>
					</li>
				</ul>
          </div>
      </header>