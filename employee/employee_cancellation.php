<?php

include('../header.php');  
$company = $ref_emp_id = $cancel_date = "";

if(isset($_POST['save']) ){
	$company = $_POST['company'];
	$ref_emp_id = $_POST['ref_emp_id'];
	if(!empty($_POST['cancel_date'])){
		$cancel_date = $_POST['cancel_date'];
		$cancel_date = str_replace('/', '-', $cancel_date);  
		$cancel_date = date("Y/m/d", strtotime($cancel_date)); 
	}
	
	if(!isset($_POST['cancel_id'])){
		$addCancellationQry = "INSERT INTO employee_cancellation (ref_comp_id, ref_emp_id, cancel_date) VALUES ('$company', '$ref_emp_id', '$cancel_date')";
		mysqli_query($conn,$addCancellationQry);
		
		mysqli_query($conn,"UPDATE employee SET employe_status =1 WHERE emp_id ='$ref_emp_id'");
		
		$_SESSION['cancel_operation'] = 1;//for insert
		$url = WEB_URL . 'employee/cancelled_employee_list.php';
		echo '<script>window.location.replace("'.$url.'");</script>';
		exit;
	}else{
		$updateCancellationQry = "UPDATE employee_cancellation SET cancel_date='$cancel_date' WHERE ref_comp_id ='$company' AND ref_emp_id='$ref_emp_id'";
		mysqli_query($conn,$updateCancellationQry);
		$_SESSION['cancel_operation'] = 2;//for insert
		$url = WEB_URL . 'employee/cancelled_employee_list.php';
		echo '<script>window.location.replace("'.$url.'");</script>';
		exit;
	}
}


if(isset($_GET['cancel_id'])){
	echo "aaaaa";
	$cancelDetsQry = "SELECT * FROM employee_cancellation WHERE emp_cancel_id=".$_GET['cancel_id'];
	$cancelDetsExe = mysqli_query($conn, $cancelDetsQry);
	if(mysqli_num_rows($cancelDetsExe) > 0){
		$thecancelDets = mysqli_fetch_assoc($cancelDetsExe);
		$company = $thecancelDets['ref_comp_id'];
		$ref_emp_id = $thecancelDets['ref_emp_id'];
		
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
					<h3 class="page-title">Add Employee Cancellation</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Add Employee Cancellation</li>
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
					<form action="" method="POST" id="cancellation_form" enctype="multipart/form-data">
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
											if(isset($_GET['cancel_id']) && !empty($_GET['cancel_id'])){
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
								
								<div class="col-xl-12">
									<div class="form-group">
										<label  >Cancel Date *</label>
										<input type="text" class="form-control datetimepicker" name="cancel_date" id="cancel_date" value="<?= $loan_date ?>">
									</div>
								</div>
							</div>
						</div>
						
						<div class="card-body">
							<input type="hidden" name="save" value="save">
							<?php 
							if(!isset($_GET['cancel_id']) && empty($_GET['cancel_id'])){ ?>
								<div class="text-right">
									<button type="button" name="submit-btn"  class="btn btn-primary form-submit-btn">Save</button>
								</div><?php
							} ?>
							
							<?php 
							if(isset($_GET['cancel_id']) && !empty($_GET['cancel_id'])){ ?>
								<input type="hidden" name="cancel_id" value="<?= $_GET['cancel_id']; ?>" id="cancel_id">
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

<script  src="<?php echo WEB_URL; ?>assets/js/c_add_employee.js"></script>

	</body>
</html>