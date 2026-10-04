<?php
require 'config.php';
if(!isset($_SESSION) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != 1){
	header('Location:'.WEB_URL);
	exit;
}
$work_status = $_SESSION['work_status'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
        <meta name="robots" content="noindex, nofollow">
        <title>HR Management Software</title>
		
		<!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo WEB_URL; ?>assets/img/favicon.png">
		
		<!-- Bootstrap CSS -->
        <link rel="stylesheet" href="<?php echo WEB_URL; ?>assets/css/bootstrap.min.css">
		
		<!-- Fontawesome CSS -->
        <link rel="stylesheet" href="<?php echo WEB_URL; ?>assets/css/font-awesome.min.css">
		
		<!-- Lineawesome CSS -->
        <link rel="stylesheet" href="<?php echo WEB_URL; ?>assets/css/line-awesome.min.css">
		
		<!-- Chart CSS -->
		<link rel="stylesheet" href="<?php echo WEB_URL; ?>assets/plugins/morris/morris.css">
		
		<!-- Datatable CSS -->
		<link rel="stylesheet" href="<?php echo WEB_URL; ?>assets/css/dataTables.bootstrap4.min.css">
		
		<link rel="stylesheet" href="<?php echo WEB_URL; ?>assets/css/select2.min.css">
		
		<!-- Datetimepicker CSS -->
		<link rel="stylesheet" href="<?php echo WEB_URL; ?>assets/css/bootstrap-datetimepicker.min.css">
		
		<!-- Main CSS -->
        <link rel="stylesheet" href="<?php echo WEB_URL; ?>assets/css/style.css">
		
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="<?php echo WEB_URL; ?>assets/js/html5shiv.min.js"></script>
			<script src="<?php echo WEB_URL; ?>assets/js/respond.min.js"></script>
		<![endif]-->
		
		
    </head>
	
    <body>
		<!-- Main Wrapper -->
        <div class="main-wrapper">
		
			<!-- Header -->
            <div class="header">
			
				<!-- Logo -->
                <div class="header-left">
                    <a href="<?php echo WEB_URL; ?>" class="logo">
						<img src="<?php echo WEB_URL; ?>assets/img/logo.png" width="auto" height="60" alt="">
					</a>
                </div>
				<!-- /Logo -->
				
				<a id="toggle_btn" href="javascript:void(0);">
					<span class="bar-icon">
						<span></span>
						<span></span>
						<span></span>
					</span>
				</a>
				
				<!-- Header Title -->
                <div class="page-title-box">
					<h3>Livenet Technologies</h3>
                </div>
				<!-- /Header Title -->
				
				<a id="mobile_btn" class="mobile_btn" href="#sidebar"><i class="fa fa-bars"></i></a>
				
				<!-- Header Menu -->
				<ul class="nav user-menu">
				
					<!-- Search -->
					<li class="nav-item">
						<div class="form-group mt-2">
							<select name="work_status" id="work_status" class="">
								<option value="1" <?php if($work_status == 1){ echo "SELECTED"; } ?>>Permenant Employees</option>
								<option value="2" <?php if($work_status == 2){ echo "SELECTED"; } ?>>Temporary Employees</option>
							</select>
						</div>
					</li>
					<!-- /Search -->
				
					<!-- Flag -->
					<li class="nav-item dropdown has-arrow flag-nav">
						<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button">
							<img src="<?php echo WEB_URL; ?>assets/img/flags/us.png" alt="" height="20"> <span>English</span>
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<a href="javascript:void(0);" class="dropdown-item">
								<img src="<?php echo WEB_URL; ?>assets/img/flags/us.png" alt="" height="16"> English
							</a>
							<a href="javascript:void(0);" class="dropdown-item">
								<img src="<?php echo WEB_URL; ?>assets/img/flags/fr.png" alt="" height="16"> French
							</a>
							<a href="javascript:void(0);" class="dropdown-item">
								<img src="<?php echo WEB_URL; ?>assets/img/flags/es.png" alt="" height="16"> Spanish
							</a>
							<a href="javascript:void(0);" class="dropdown-item">
								<img src="<?php echo WEB_URL; ?>assets/img/flags/de.png" alt="" height="16"> German
							</a>
						</div>
					</li>
					<!-- /Flag -->
				
					<!-- Notifications -->
					<!--- <li class="nav-item dropdown">
						<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
							<i class="fa fa-bell-o"></i> <span class="badge badge-pill">3</span>
						</a>
						<div class="dropdown-menu notifications">
							<div class="topnav-dropdown-header">
								<span class="notification-title">Notifications</span>
								<a href="javascript:void(0)" class="clear-noti"> Clear All </a>
							</div>
							<div class="noti-content">
								<ul class="notification-list">
									<li class="notification-message">
										<a href="activities.html">
											<div class="media">
												<span class="avatar">
													<img alt="" src="<?php echo WEB_URL; ?>assets/img/profiles/avatar-02.jpg">
												</span>
												<div class="media-body">
													<p class="noti-details"><span class="noti-title">John Doe</span> added new task <span class="noti-title">Patient appointment booking</span></p>
													<p class="noti-time"><span class="notification-time">4 mins ago</span></p>
												</div>
											</div>
										</a>
									</li>
									<li class="notification-message">
										<a href="activities.html">
											<div class="media">
												<span class="avatar">
													<img alt="" src="<?php echo WEB_URL; ?>assets/img/profiles/avatar-03.jpg">
												</span>
												<div class="media-body">
													<p class="noti-details"><span class="noti-title">Tarah Shropshire</span> changed the task name <span class="noti-title">Appointment booking with payment gateway</span></p>
													<p class="noti-time"><span class="notification-time">6 mins ago</span></p>
												</div>
											</div>
										</a>
									</li>
									<li class="notification-message">
										<a href="activities.html">
											<div class="media">
												<span class="avatar">
													<img alt="" src="<?php echo WEB_URL; ?>assets/img/profiles/avatar-06.jpg">
												</span>
												<div class="media-body">
													<p class="noti-details"><span class="noti-title">Misty Tison</span> added <span class="noti-title">Domenic Houston</span> and <span class="noti-title">Claire Mapes</span> to project <span class="noti-title">Doctor available module</span></p>
													<p class="noti-time"><span class="notification-time">8 mins ago</span></p>
												</div>
											</div>
										</a>
									</li>
									<li class="notification-message">
										<a href="activities.html">
											<div class="media">
												<span class="avatar">
													<img alt="" src="<?php echo WEB_URL; ?>assets/img/profiles/avatar-17.jpg">
												</span>
												<div class="media-body">
													<p class="noti-details"><span class="noti-title">Rolland Webber</span> completed task <span class="noti-title">Patient and Doctor video conferencing</span></p>
													<p class="noti-time"><span class="notification-time">12 mins ago</span></p>
												</div>
											</div>
										</a>
									</li>
									<li class="notification-message">
										<a href="activities.html">
											<div class="media">
												<span class="avatar">
													<img alt="" src="<?php echo WEB_URL; ?>assets/img/profiles/avatar-13.jpg">
												</span>
												<div class="media-body">
													<p class="noti-details"><span class="noti-title">Bernardo Galaviz</span> added new task <span class="noti-title">Private chat module</span></p>
													<p class="noti-time"><span class="notification-time">2 days ago</span></p>
												</div>
											</div>
										</a>
									</li>
								</ul>
							</div>
							<div class="topnav-dropdown-footer">
								<a href="activities.html">View all Notifications</a>
							</div>
						</div>
					</li> --->
					<!-- /Notifications -->
					
					<!-- Message Notifications -->
				<!---	<li class="nav-item dropdown">
						<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
							<i class="fa fa-comment-o"></i> <span class="badge badge-pill">8</span>
						</a>
						<div class="dropdown-menu notifications">
							<div class="topnav-dropdown-header">
								<span class="notification-title">Messages</span>
								<a href="javascript:void(0)" class="clear-noti"> Clear All </a>
							</div>
							<div class="noti-content">
								<ul class="notification-list">
									<li class="notification-message">
										<a href="chat.html">
											<div class="list-item">
												<div class="list-left">
													<span class="avatar">
														<img alt="" src="<?php echo WEB_URL; ?>assets/img/profiles/avatar-09.jpg">
													</span>
												</div>
												<div class="list-body">
													<span class="message-author">Richard Miles </span>
													<span class="message-time">12:28 AM</span>
													<div class="clearfix"></div>
													<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
												</div>
											</div>
										</a>
									</li>
									<li class="notification-message">
										<a href="chat.html">
											<div class="list-item">
												<div class="list-left">
													<span class="avatar">
														<img alt="" src="<?php echo WEB_URL; ?>assets/img/profiles/avatar-02.jpg">
													</span>
												</div>
												<div class="list-body">
													<span class="message-author">John Doe</span>
													<span class="message-time">6 Mar</span>
													<div class="clearfix"></div>
													<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
												</div>
											</div>
										</a>
									</li>
									<li class="notification-message">
										<a href="chat.html">
											<div class="list-item">
												<div class="list-left">
													<span class="avatar">
														<img alt="" src="<?php echo WEB_URL; ?>assets/img/profiles/avatar-03.jpg">
													</span>
												</div>
												<div class="list-body">
													<span class="message-author"> Tarah Shropshire </span>
													<span class="message-time">5 Mar</span>
													<div class="clearfix"></div>
													<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
												</div>
											</div>
										</a>
									</li>
									<li class="notification-message">
										<a href="chat.html">
											<div class="list-item">
												<div class="list-left">
													<span class="avatar">
														<img alt="" src="<?php echo WEB_URL; ?>assets/img/profiles/avatar-05.jpg">
													</span>
												</div>
												<div class="list-body">
													<span class="message-author">Mike Litorus</span>
													<span class="message-time">3 Mar</span>
													<div class="clearfix"></div>
													<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
												</div>
											</div>
										</a>
									</li>
									<li class="notification-message">
										<a href="chat.html">
											<div class="list-item">
												<div class="list-left">
													<span class="avatar">
														<img alt="" src="<?php echo WEB_URL; ?>assets/img/profiles/avatar-08.jpg">
													</span>
												</div>
												<div class="list-body">
													<span class="message-author"> Catherine Manseau </span>
													<span class="message-time">27 Feb</span>
													<div class="clearfix"></div>
													<span class="message-content">Lorem ipsum dolor sit amet, consectetur adipiscing</span>
												</div>
											</div>
										</a>
									</li>
								</ul>
							</div>
							<div class="topnav-dropdown-footer">
								<a href="chat.html">View all Messages</a>
							</div>
						</div>
					</li>  --->
					<!-- /Message Notifications -->

					<li class="nav-item dropdown has-arrow main-drop">
						<a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
							<span class="user-img"><img src="<?php echo WEB_URL; ?>assets/img/profiles/avatar-21.jpg" alt="">
							<span class="status online"></span></span>
							<span>Admin</span>
						</a>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="profile.html">My Profile</a>
							<a class="dropdown-item" href="settings.html">Settings</a>
							<a class="dropdown-item" href="<?php echo WEB_URL; ?>logout.php">Logout</a>
						</div>
					</li>
				</ul>
				<!-- /Header Menu -->
				
				<!-- Mobile Menu -->
				<div class="dropdown mobile-user-menu">
					<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
					<div class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item" href="profile.html">My Profile</a>
						<a class="dropdown-item" href="settings.html">Settings</a>
						<a class="dropdown-item" href="<?php echo WEB_URL; ?>logout.php">Logout</a>
					</div>
				</div>
				<!-- /Mobile Menu -->
				
            </div>
			<!-- /Header -->
			
			<!-- Sidebar -->
            <div class="sidebar" id="sidebar">
                <div class="sidebar-inner slimscroll">
					<div id="sidebar-menu" class="sidebar-menu">
						<ul>
							<li class="menu-title"> 
								<span></span>
							</li>
							<li> 
								<a href="<?php echo WEB_URL; ?>"><i class="la la-dashboard"></i> <span>Dashboard</span></a>
							</li>
							<li class="menu-title"> 
								<span></span>
							</li>
							<li class="submenu">
								<a href="#" class=""><i class="la la-user"></i> <span> Master</span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
									<li><a href="<?= WEB_URL ?>masters/company_master.php">Company</a></li>
									<li><a href="<?= WEB_URL ?>masters/department_master.php">Departments</a></li>
									<li><a href="<?= WEB_URL ?>masters/position_master.php">Position</a></li>
								</ul>
							</li>
							<li class="menu-title"> 
								<span></span>
							</li>
							<li class="submenu">
								<a href="#" class=""><i class="la la-user"></i> <span> Employees</span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
									<li><a href="<?= WEB_URL ?>employee/index.php">Employee List</a></li>
									<li><a href="<?= WEB_URL ?>employee/add_employee.php">Add Employee</a></li>
									<li><a href="<?= WEB_URL ?>employee/cancelled_employee_list.php">Cancelled Employee List</a></li>
									<li><a href="<?= WEB_URL ?>employee/employee_cancellation.php">Employee Cancellation</a></li>
									<li><a href="<?= WEB_URL ?>employee/settelment_guatutiy_list.php">Settlement Gratuity List</a></li>
									<li><a href="<?= WEB_URL ?>employee/settelment_guatutiy.php">Add Settlement Gratuity</a></li>
									<li><a href="<?= WEB_URL ?>employee/guatutiy_report.php">Gratuity Report</a></li>
									
								</ul>
							</li>
							<li class="menu-title"> 
								<span></span>
							</li>
							
							</li>
							<li class="menu-title"> 
								<span></span>
							</li>
							<li class="submenu">
								<a href="#" class=""><i class="la la-user"></i> <span> Attendance</span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
									<li><a href="<?= WEB_URL ?>attendance/attendance_list.php">Attendance List</a></li>
									<li><a href="<?= WEB_URL ?>attendance/generate_attendance.php">Generate Attendance</a></li>
								</ul>
							</li>
							
							<li class="menu-title"> 
								<span></span>
							</li>
							<li class="submenu">
								<a href="#" class=""><i class="la la-money"></i> <span> Payroll</span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
									<li><a href="<?= WEB_URL ?>payroll/generate_salary.php">Generate Salary </a></li>
									<li><a href="<?= WEB_URL ?>payroll/salary_list.php">Salary List</a></li>
									<li><a href="<?= WEB_URL ?>payroll/payslip.php">Payslip List</a></li>
									<li><a href="<?= WEB_URL ?>payroll/salary_project_report.php">Project Salary report</a></li>
									<li><a href="<?= WEB_URL ?>payroll/salary_report.php">Salary report</a></li>
									<li><a href="<?= WEB_URL ?>payroll/wps_salary_report.php">WPS Salary report</a></li>
									<li><a href="<?= WEB_URL ?>payroll/report1.php">Report-1</a></li>
									<li><a href="<?= WEB_URL ?>payroll/report2.php">Report -2</a></li>
								</ul>
							</li>
							
							<li class="menu-title"> 
								<span></span>
							</li>
							<li class="submenu">
								<a href="#" class=""><i class="la la-user"></i> <span> Projects</span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
									<li><a href="<?= WEB_URL ?>projects/">Projects List</a></li>
								</ul>
							</li>
							
							<li class="menu-title"> 
								<span></span>
							</li>
							<li class="submenu">
								<a href="#" class=""><i class="la la-object-ungroup"></i> <span> Expense</span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
									<li><a href="<?= WEB_URL ?>expense/index.php">Expense List</a></li>
									<li><a href="<?= WEB_URL ?>expense/add_expense.php">Add Expense </a></li>
									<li><a href="<?= WEB_URL ?>expense/add_expense_types.php">Expense Types</a></li>
									
								</ul>
							</li>
							<li class="menu-title"> 
								<span></span>
							</li>
							<li class="submenu">
								<a href="#" class=""><i class="la la-times-circle"></i> <span> Leave</span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
									<li><a href="<?= WEB_URL ?>leave">Vacation List</a></li>
									<li><a href="<?= WEB_URL ?>leave/add_vacation.php">Add Vacation</a></li>
								</ul>
							</li>
							<li class="menu-title"> 
								<span></span>
							</li>
							<li class="submenu">
								<a href="#" class=""><i class="la la-file-text"></i> <span> Loan</span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
									<li><a href="<?= WEB_URL ?>loan/add_loan.php">Add Loan</a></li>
									<li><a href="<?= WEB_URL ?>loan">Loan List</a></li>
								</ul>
							</li>
							<li class="menu-title"> 
								<span></span>
							</li>
							<li class="submenu">
								<a href="#" class=""><i class="la la-pie-chart"></i> <span> Reports</span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
									<li><a href="<?= WEB_URL ?>reports/collected_loan_report.php">Collected Loan Report</a></li>
									<li><a href="<?= WEB_URL ?>reports/employee_expanse_report.php">Employee Expanse Report</a></li>
									
								</ul>
							</li>
							<li class="menu-title"> 
								<span></span>
							</li>
							<li class="submenu">
								<a href="#" class=""><i class="la la-cog"></i> <span> Setting</span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
									<li><a href="#">Application Setting</a></li>
									<li><a href="#">User List</a></li>
									<li><a href="#">Add User</a></li>
									<li><a href="#">Add User</a></li>
								</ul>
							</li>
							
						</ul>
					</div>
                </div>
            </div>
			<!-- /Sidebar -->
			
		