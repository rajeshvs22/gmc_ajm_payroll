<?php

include('../header.php'); 

$company = $division = $position = $loan_type = $loan_date = $loan_amount = $ref_emp_id = '';

if(isset($_POST['save']) ){
	$company = $_POST['company'];
	
	$ref_emp_id = $_POST['ref_emp_id'];
	$loan_type = $_POST['loan_type'];
	
	$loan_date = $_POST['loan_date'];
	$loan_date = str_replace('/', '-', $loan_date);  
	$loan_date = date("Y/m/d", strtotime($loan_date)); 
	
	$loan_amount = $_POST['loan_amount'];
	
	if(!isset($_POST['ref_loan_id'])){
		$addLoanQry = "INSERT INTO loan_details ( ref_emp_id, loan_type, loan_date, loan_amount) VALUES ('$ref_emp_id', '$loan_type', '$loan_date', '$loan_amount')";
		mysqli_query($conn,$addLoanQry);
		$_SESSION['loan_operation'] = 1;//for insert
		$url = WEB_URL . 'loan/index.php';
		echo '<script>window.location.replace("'.$url.'");</script>';
		exit;
	}else{
		$updateLoanQry = "UPDATE loan_details SET loan_type='$loan_type', loan_date = '$loan_date', loan_amount = '$loan_amount', ref_emp_id = '$ref_emp_id' WHERE loan_id =".$_POST['ref_loan_id'];
		mysqli_query($conn,$updateLoanQry);
		$_SESSION['loan_operation'] = 2;//for update
		$url = WEB_URL . 'loan/index.php';
		echo '<script>window.location.replace("'.$url.'");</script>';
		exit;
	}
	
}

if(isset($_GET['loan_id'])){
	$loanDetailsQry = "SELECT *,e.ref_comp_id as ref_company, e.division as ref_dep_id,e.position FROM loan_details ld INNER JOIN employee e ON ld.ref_emp_id = e.emp_id WHERE loan_id=".$_GET['loan_id'];
	$loanDetailsQryExe = mysqli_query($conn, $loanDetailsQry);
	if(mysqli_num_rows($loanDetailsQryExe) > 0){
		$theLoanData = mysqli_fetch_assoc($loanDetailsQryExe);
		$company = $theLoanData['ref_company'];
		$division = $theLoanData['ref_dep_id'];
		$position = $theLoanData['position'];
		$loan_type = $theLoanData['loan_type'];
		$loan_date = $theLoanData['loan_date'];
		$loan_date = date('d-m-Y',strtotime($loan_date));
		$loan_amount = $theLoanData['loan_amount'];
		$ref_emp_id = $theLoanData['ref_emp_id'];
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
					<h3 class="page-title">Add Loan</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Add Loan</li>
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
											if(isset($_GET['loan_id']) && !empty($_GET['loan_id'])){
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
										<label  >Type *</label>
										<select name="loan_type" id="loan_type" class="select">
											<option selected disabled>Select Type *</option>
											<option value="1" <?php if($loan_type == '1'){ echo "SELECTED"; } ?>>Loan</option>
											<option value="2" <?php if($loan_type == '2'){ echo "SELECTED"; } ?>>Penality</option>
										</select>
									</div>
								</div>
								<div class="col-xl-12">
									<div class="form-group">
										<label  >Loan Date *</label>
										<input type="text" class="form-control datetimepicker" name="loan_date" id="loan_date" value="<?= $loan_date ?>">
									</div>
								</div>
								<div class="col-xl-12">
									<div class="form-group">
										<label>Loan Amount *</label>
										<input type="text" class="form-control" name="loan_amount" id="loan_amount" value="<?= $loan_amount ?>">
									</div>
								</div>
								
								
								<div class="col-xl-12 existing-alert-on-add">
									
								</div>
							</div>
						</div>
						
						
						<div class="card-body">
							<input type="hidden" name="save" value="save">
							<?php 
							if(!isset($_GET['loan_id']) && empty($_GET['loan_id'])){ ?>
								<div class="text-right">
									<button type="button" name="submit-btn"  class="btn btn-primary form-submit-btn">Save</button>
								</div><?php
							} ?>
							
							<?php 
							if(isset($_GET['loan_id']) && !empty($_GET['loan_id'])){ ?>
								<input type="hidden" name="ref_loan_id" value="<?= $_GET['loan_id']; ?>" id="ref_loan_id">
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

<script  src="<?php echo WEB_URL; ?>assets/js/c_add_loan.js"></script>
    </body>
</html>