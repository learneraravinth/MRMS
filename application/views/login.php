
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>MRMS</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="<?php echo base_url(); ?>css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(); ?>css/style-responsive.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>css/background-slide.css" rel="stylesheet" />
	<script>
		var baseUrl='<?php echo base_url();?>';
	</script>	
	<script src="<?php echo base_url(); ?>js/1.10.min.js"></script>
    <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
  <script src="<?php echo base_url(); ?>js/jquery.validate.js"></script>
  <script src="<?php echo base_url(); ?>js/login.js"></script>

</head>

	<body class="login-body">
    <div class="container">
		<form class="form-signin clearfix" id="login_form" method="POST" action="#">
			 <div class="col-md-04 col-md-offset-4">
					<img src="<?php echo base_url('img/logo/logo.png'); ?>" alt="2nt logo"/>
			</div>
			<div class="login-wrap">
				<input type="text" class="form-control" placeholder="Email ID" name="admin_email" id="admin_email" value="" autofocus>
				<input type="password" class="form-control" placeholder="Password" name="admin_password" id="admin_password" value="">
				<label class="checkbox">
					<input type="checkbox" value="remember-me"> Remember me
					<span class="pull-right">
						<a data-toggle="modal" href="#myModal"> Forgot Password?</a>
					</span>
				</label>
				
				<!--<div class="registration">
					Don't have an account yet?
					<a class="" href="registration.html">
						Create an account
					</a>
				</div>-->

			</div>
		<button class="btn btn-lg btn-login btn-success pull-right" onclick ="login_user()">Sign in</button>
			  <!-- modal -->
		</form>
			
    </div>
        <div class="solar-syst">
      <div class="sun"></div>
      <div class="mercury"></div>
      <div class="venus"></div>
      <div class="earth"></div>
      <div class="mars"></div>
      <div class="jupiter"></div>
      <div class="saturn"></div>
      <div class="uranus"></div>
      <div class="neptune"></div>
      <div class="pluto"></div>
      <div class="asteroids-belt"></div>
    </div>
  </body>
  <style>
  .form-signin .btn-login {
  background: #27ace4;
    color: #fff;
    text-transform: uppercase;
    font-weight: 300;
    font-family: 'Open Sans', sans-serif;
    box-shadow: 0 4px #e02129;
    margin-bottom: 20px;
    margin-right: 16px;
    font-weight: bold;
    border: 0;
}

.form-signin {
    max-width: 330px;
   /* margin: 343px auto 0;*/
    /*background: #fff;*/
    border-radius: 5px;
    -webkit-border-radius: 5px;
}

.form-signin {
    max-width: 330px;
   /* margin: 343px auto 0;*/
    border:1px solid #fff;
    border-radius: 5px;
    -webkit-border-radius: 5px;
}
.form-signin {
    max-width: 330px;
    /* margin: 100px auto 0; */
    /*background: #f5f5f5;*/
    border-radius: 5px;
    -webkit-border-radius: 5px;
    margin: 50px auto;
}
.col-md-offset-4 {
    /* margin-left: 42.333333%; */
    text-align: center;
    margin: 0;
}
.form-signin h2.form-signin-heading {
    margin: 0;
    padding: 15px 15px;
    text-align: center;
    /*background: #27ace4;*/
    border-radius: 5px 5px 0 0;
    -webkit-border-radius: 5px 5px 0 0;
    color: #fff;
    font-size: 18px;
    text-transform: uppercase;
    font-weight: 300;
    font-family: 'Open Sans', sans-serif;
    border-bottom: 5px solid #e02129;
    font-weight:bold;
}
label {
    display: inline-block;
    max-width: 100%;
    margin-bottom: 5px;
    font-weight: 700;
    color: #ec990a;
}
</style>


<script src="<?php echo base_url(); ?>js/sweetalert.min.js"></script>
<script src="<?php echo base_url(); ?>js/sweetalert-dev.js"></script>
<link href="<?php echo base_url(); ?>css/sweetalert.css" rel="stylesheet">

</html>