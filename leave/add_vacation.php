<?php

include('../header.php'); 


$company = $division = $position = $ref_emp_id = $leave_start_dt = $leave_end_dt = $leave_amount = $ticket_amount = $leave_status = '';
$re_joining_dt = '0000-00-00';

if(isset($_POST['save']) ){
	$company = $_POST['company'];
	$division = $_POST['division'];
	$position = $_POST['position'];
	$ref_emp_id = $_POST['ref_emp_id'];
	
	$leave_amount = $_POST['leave_amount'];
	$ticket_amount = $_POST['ticket_amount'];
	
	$leave_start_dt = $_POST['leave_start_dt'];
	$leave_start_dt = str_replace('/', '-', $leave_start_dt);  
	$leave_start_dt = date("Y/m/d", strtotime($leave_start_dt)); 
	
	$leave_end_dt = $_POST['leave_end_dt'];
	$leave_end_dt = str_replace('/', '-', $leave_end_dt);  
	$leave_end_dt = date("Y/m/d", strtotime($leave_end_dt)); 
	
	if(isset($_POST['re_joining_dt']) && !empty($_POST['re_joining_dt'])){
		$re_joining_dt = $_POST['re_joining_dt'];
		$re_joining_dt = str_replace('/', '-', $re_joining_dt);  
		$re_joining_dt = date("Y/m/d", strtotime($re_joining_dt)); 
	}else{
		$re_joining_dt = '0000-00-00';
	}
	
	$leave_status = $_POST['leave_status'];
	
	if(!isset($_POST['ref_lev_id'])){
		$addLeaveQry = "INSERT INTO vacation_details (ref_emp_id, leave_start_dt, leave_end_dt, leave_amount, ticket_amount, rejoining_date, leave_status) VALUES ('$ref_emp_id', '$leave_start_dt', '$leave_end_dt', '$leave_amount', '$ticket_amount', '$re_joining_dt', '$leave_status')";
		mysqli_query($conn,$addLeaveQry);
		$_SESSION['leave_operation'] = 1;//for insert
		$url = WEB_URL . 'leave/index.php';
		echo '<script>window.location.replace("'.$url.'");</script>';
		exit;
	}else{
		
		$updateLeaveQry = "UPDATE vacation_details SET ref_emp_id='$ref_emp_id', leave_start_dt='$leave_start_dt', leave_end_dt='$leave_end_dt', leave_amount='$leave_amount', ticket_amount='$ticket_amount', rejoining_date='$re_joining_dt', leave_status='$leave_status' WHERE leave_id = ".$_POST['ref_lev_id'];
		
		mysqli_query($conn,$updateLeaveQry);
		$_SESSION['leave_operation'] = 1;//for insert
		$url = WEB_URL . 'leave/index.php';
		echo '<script>window.location.replace("'.$url.'");</script>';
		exit;
	}


}

if(isset($_GET['lev_id'])){
	$leaveDetailsQry = "SELECT vd.*,e.ref_comp_id as ref_company, e.division as ref_dep_id,e.position FROM vacation_details vd INNER JOIN employee e ON vd.ref_emp_id = e.emp_id WHERE leave_id =".$_GET['lev_id'];
	
	$leaveDetailsQryExe = mysqli_query($conn, $leaveDetailsQry);
	if(mysqli_num_rows($leaveDetailsQryExe) > 0){
		$theLeaveData = mysqli_fetch_assoc($leaveDetailsQryExe);
		$company = $theLeaveData['ref_company'];
		$division = $theLeaveData['ref_dep_id'];
		$position = $theLeaveData['position'];
		$ref_emp_id = $theLeaveData['ref_emp_id'];
		
		$leave_start_dt = $theLeaveData['leave_start_dt'];
		$leave_start_dt = date('d-m-Y',strtotime($leave_start_dt));
		
		$leave_end_dt = $theLeaveData['leave_end_dt'];
		$leave_end_dt = date('d-m-Y',strtotime($leave_end_dt));
		
		
		$leave_amount = $theLeaveData['leave_amount'];
		$ticket_amount = $theLeaveData['ticket_amount'];
		
		if($theLeaveData['rejoining_date'] != '0000-00-00'){
			$re_joining_dt = $theLeaveData['rejoining_date'];
			$re_joining_dt = date('d-m-Y',strtotime($re_joining_dt));
		}else{
			$re_joining_dt = '';
		}
		
		$leave_status = $theLeaveData['leave_status'];
	}
		
}

?>


<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<!--- breadcrumb section --->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Add Vacation</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Add Vacation</li>
					</ul>
				</div>
			</div>
		</div>
		<!--- END: breadcrumb section --->
		
		
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title mb-0">Employee Details</h4>
					</div>
					<form action="" method="POST" id="loan_form" enctype="multipart/form-data">
						<div class="card-body">
							<div class="row">
								<div class="col-xl-12">
									<div class="form-group">
										<label  >Company *</label>
										<select name="company" id="company" class="select"><?php
										if(empty($company)){ ?>
											<option value="" disabled SELECTED>Select</option><?php
										} 
										$companiesQry = "SELECT * FROM company_master WHERE visibility=1";
										$qryExe1 = mysqli_query($conn, $companiesQry);
										if(mysqli_num_rows($qryExe1) > 0){
											while($theCompData = mysqli_fetch_assoc($qryExe1)){ ?>
												<option value="<?= $theCompData['comp_id'] ?>" <?php if($company == $theCompData['comp_id']){ echo "SELECTED"; } ?>><?= $theCompData['company_name'] ?></option><?php
											}
										}?>
										
										</select>
									</div>
								</div>
								
								<div class="col-xl-12">
									<div class="form-group">
										<label  >Employee *</label>
										<select name="ref_emp_id" id="ref_emp_id" class="select">
											<option selected disabled>Select Employee *</option><?php
											if(isset($_GET['lev_id']) && !empty($_GET['lev_id'])){
												$getEmployeesQry = "SELECT * FROM employee WHERE work_status = ".$_SESSION['work_status']." AND ref_comp_id = ".$company;
												$getEmployeesQryExe = mysqli_query($conn,$getEmployeesQry);
												if(mysqli_num_rows($getEmployeesQryExe) > 0){
													while($theEmpData = mysqli_fetch_assoc($getEmployeesQryExe)){ ?>
														<option value="<?= $theEmpData['emp_id'] ?>" <?php if($theEmpData['emp_id'] == $ref_emp_id){ echo  "SELECTED"; } ?>><?= $theEmpData['emp_name']." - ".$theEmpData['emp_code'] ?></option><?php
													}
												}
											} ?>
										</select>
									</div>
								</div>
								<div class="col-xl-6">
									<div class="form-group">
										<label>Start Date *</label>
										<input type="text" class="form-control datetimepicker" name="leave_start_dt" id="leave_start_dt" value="<?= $leave_start_dt ?>">
									</div>
								</div>
								<div class="col-xl-6">
									<div class="form-group">
										<label>End Date *</label>
										<input type="text" class="form-control datetimepicker" name="leave_end_dt" id="leave_end_dt" value="<?= $leave_end_dt ?>">
									</div>
								</div>
								<div class="col-xl-6">
									<div class="form-group">
										<label>Leave Salary </label>
										<input type="text" class="form-control" name="leave_amount" id="leave_amount" value="<?= $leave_amount ?>">
									</div>
								</div>
								<div class="col-xl-6">
									<div class="form-group">
										<label>Air Ticket </label>
										<input type="text" class="form-control" name="ticket_amount" id="ticket_amount" value="<?= $ticket_amount ?>">
									</div>
								</div>
								
								<div class="col-xl-12">
									<div class="form-group">
										<label  >Leave Status *</label>
										<select name="leave_status" id="leave_status" class="select">
											<option value="1" <?php if($leave_status == 1){ echo "SELECTED"; } ?>>In Leave</option>
											<option value="2" <?php if($leave_status == 2){ echo "SELECTED"; } ?>>Leave Completed</option>
										</select>
									</div>
								</div>
								<div class="col-xl-6 rejoin-dt" style="display:<?php if(isset($_GET['lev_id'])){ if($leave_status == 1){ echo "none"; }else{ echo "block"; } }else{ echo "none"; } ?>">
									<div class="form-group">
										<label>Rejoining Date *</label>
										<input type="text" class="form-control datetimepicker" name="re_joining_dt" id="re_joining_dt" value="<?= $re_joining_dt ?>">
									</div>
								</div>
								
								<div class="col-xl-12 existing-alert-on-add">
									
								</div>
							</div>
						</div>
						<div class="card-body">
							<input type="hidden" name="save" value="save">
							<?php 
							if(!isset($_GET['lev_id']) && empty($_GET['lev_id'])){ ?>
								<div class="text-right">
									<button type="button" name="submit-btn"  class="btn btn-primary form-submit-btn">Save</button>
								</div><?php
							} ?>
							
							<?php 
							if(isset($_GET['lev_id']) && !empty($_GET['lev_id'])){ ?>
								<input type="hidden" name="ref_lev_id" id="ref_lev_id" value="<?= $_GET['lev_id']; ?>">
								<div class="text-right">
									<button type="button" name="submit-btn" class="btn btn-primary form-submit-btn">Update</button>
								</div><?php
							} ?>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
require '../footer.php'

?>

<script  src="<?php echo WEB_URL; ?>assets/js/c_add_vacation.js"></script>
    </body>
</html>