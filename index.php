<?php
require 'config.php';

if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == 1){
	header('Location:'.WEB_URL.'dashboard.php');
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta name="description" content="Smarthr - Bootstrap Admin Template">
		<meta name="keywords" content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern, accounts, invoice, html5, responsive, CRM, Projects">
        <meta name="author" content="Dreamguys - Bootstrap Admin Template">
        <meta name="robots" content="noindex, nofollow">
        <title>Login - HRMS</title>
		
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
		
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
		
		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="assets/css/font-awesome.min.css">
		
		<!-- Main CSS -->
        <link rel="stylesheet" href="assets/css/style.css">
		
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="assets/js/html5shiv.min.js"></script>
			<script src="assets/js/respond.min.js"></script>
		<![endif]-->
		<meta name="googlebot" content="index">
    </head>
    <body class="account-page">
	
		<!-- Main Wrapper -->
        <div class="main-wrapper">
			<div class="account-content">
				
				<div class="container">
				
					<!-- Account Logo -->
					<div class="account-logo">
						<a href="index.html"><img src="assets/img/logo.png" alt="Livenet Technologies"></a>
					</div>
					<!-- /Account Logo -->
					
					<div class="account-box">
						<div class="account-wrapper">
							<h3 class="account-title">Login</h3>
							<p class="account-subtitle">Access to our dashboard</p>
							<div class="alert alert-danger alert-dismissible fade show small" role="alert" style="display:none">
								Invalid Credentials
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							
							<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-login-in" style="display:none">
								logging in..
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<!-- Account Form -->
							<form action="" id="login_form" method="POST">
								<div class="form-group">
									<label>Email Address</label>
									<input class="form-control" type="text" name="uname" id="uname">
								</div>
								<div class="form-group">
									<div class="row">
										<div class="col">
											<label>Password</label>
										</div>
										<!---<div class="col-auto">
											<a class="text-muted" href="forgot-password.html">
												Forgot password?
											</a>
										</div> --->
									</div>
									<input class="form-control" type="password" name="pswd" id="pswd">
								</div>
								<div class="form-group text-center">
									<button class="btn btn-primary account-btn" type="submit">Login</button>
								</div>
							</form>
							<!-- /Account Form -->
						</div>
					</div>
				</div>
			</div>
        </div>
		<!-- /Main Wrapper -->
		
		<!-- jQuery -->
        <script src="assets/js/jquery-3.2.1.min.js"></script>
		
		<!-- Bootstrap Core JS -->
        <script src="assets/js/popper.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
		
		<!-- Custom JS -->
		<script src="assets/js/app.js"></script>
		<script>
		$(document).ready(function(){
			$( "#login_form" ).submit(function( e ) {
				e.preventDefault();
				var regxEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,3}$/i;
				var uname = $('#uname').val();
				var pswd = $('#pswd').val();
				
				if(uname == ''){
					$('#uname').css('border-color','red');
				}else{
					if (regxEmail.test(uname)){
						$('#uname').css('border-color','');
					}else{
						$('#uname').css('border-color','red');
					}
				}
				
				if(pswd == ''){
					$('#pswd').css('border-color','red');
				}else{
					$('#pswd').css('border-color','');
				}
				
				if(regxEmail.test(uname) && pswd != ''){
					$.ajax({
						url: 'ajax_login.php',
						type: 'post',
						data: {uname:uname, pswd:pswd},
						success: function(data){
							if(data == 1){
								setTimeout(function () {
									window.location.replace("<?= WEB_URL ?>dashboard.php");
								},2000)
								
								$(".alert-success").css('display', 'block');
								$(".alert-success").fadeTo(2000, 500).slideUp(500, function(){
										$(".alert-success").slideUp(500);
								});
							}else{
								
								$(".alert-danger").css('display', 'block');
								$(".alert-danger").fadeTo(2000, 500).slideUp(500, function(){
									$(".alert-danger").slideUp(500);
								});
							}
						}
					});
				}
			});
		})
		</script>
    </body>
</html>