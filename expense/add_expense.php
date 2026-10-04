<?php
include('../header.php');
$company = $ref_emp_id = $ref_expense_type_id = $expense_amount = "";
$expense_dt = '0000-00-00';

if(isset($_POST['save']) ){
	$company = $_POST['company'];
	$ref_emp_id = $_POST['ref_emp_id'];
	$ref_expense_type_id = $_POST['expense_type'];
	$expense_amount = $_POST['expense_amount'];
	
	if(!empty($_POST['expense_dt'])){
		$expense_dt = $_POST['expense_dt'];
		$expense_dt = str_replace('/', '-', $expense_dt);  
		$expense_dt = date("Y/m/d", strtotime($expense_dt));
	}
	
	if(!isset($_GET['expense_id'])){
		$insertExpenseQry = "INSERT INTO employee_expense (ref_emp_id, ref_expense_type_id, expense_amount, expense_date) VALUES('$ref_emp_id', '$ref_expense_type_id', '$expense_amount', '$expense_dt')";
		mysqli_query($conn,$insertExpenseQry);
		$_SESSION['expense_operation'] = 1;//for insert
		$url = WEB_URL . 'expense/index.php';
		echo '<script>window.location.replace("'.$url.'");</script>';
		exit;
	}else{
		
		$updateExpenseQry = "UPDATE employee_expense SET ref_emp_id='$ref_emp_id', ref_expense_type_id='$ref_expense_type_id', expense_amount='$expense_amount', expense_date='$expense_dt' WHERE expense_id =".$_GET['expense_id'];
		mysqli_query($conn,$updateExpenseQry);
		$_SESSION['expense_operation'] = 2;//for update
		$url = WEB_URL . 'expense/index.php';
		echo '<script>window.location.replace("'.$url.'");</script>';
		exit;
	}
	
}

if(isset($_GET['expense_id'])){
	$expenseDetailsQry = "SELECT *, (SELECT ref_comp_id FROM employee WHERE emp_id=ref_emp_id) as company,(SELECT expense_name FROM expense_type_master WHERE expense_type_id =ref_expense_type_id) as expense_name FROM employee_expense WHERE expense_id =".$_GET['expense_id'];
	echo $expenseDetailsQry;
	$expenseDetailsQryExe = mysqli_query($conn, $expenseDetailsQry);
	if(mysqli_num_rows($expenseDetailsQryExe) > 0){
		$theExpenseData = mysqli_fetch_assoc($expenseDetailsQryExe);
		$company = $theExpenseData['company'];
		$ref_emp_id = $theExpenseData['ref_emp_id'];
		$ref_expense_type_id = $theExpenseData['ref_expense_type_id'];
		$expense_amount = $theExpenseData['expense_amount'];
		
		if($theExpenseData['expense_date'] != '0000-00-00'){
			$expense_dt = $theExpenseData['expense_date'];
			$expense_dt = date('d/m/Y',strtotime($expense_dt));
		}else{
			$expense_dt = '';
		}
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
					<h3 class="page-title">Add Expense</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Add Expense</li>
					</ul>
				</div>
			</div>
		</div>
		<!--- END: breadcrumb section --->
		
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title mb-0">Expense Details</h4>
					</div>
					<form action="" method="POST" id="add_expense_form" enctype="multipart/form-data">
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
											if(isset($_GET['expense_id']) && !empty($_GET['expense_id'])){
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
										<label  >Expense Type *</label>
										<select name="expense_type" id="expense_type" class="select"><?php
										if(empty($ref_expense_type_id)){ ?>
											<option value="" disabled SELECTED>Select</option><?php
										} 
										$expenseTypeQry = "SELECT * FROM expense_type_master WHERE visibility=1";
										echo $expenseTypeQry;
										$expenseTypeQryExe1 = mysqli_query($conn, $expenseTypeQry);
										if(mysqli_num_rows($expenseTypeQryExe1) > 0){
											while($theExpenseType = mysqli_fetch_assoc($expenseTypeQryExe1)){ ?>
												<option value="<?= $theExpenseType['expense_type_id'] ?>" <?php if($ref_expense_type_id == $theExpenseType['expense_type_id']){ echo "SELECTED"; } ?>><?= $theExpenseType['expense_name'] ?></option><?php
											}
										}?>
										
										</select>
									</div>
								</div>
								
								<div class="col-xl-6">
									<div class="form-group">
										<label>Expense Amount</label>
										<input type="text" class="form-control" name="expense_amount" id="expense_amount" value="<?= $expense_amount ?>">
									</div>
								</div>
								
								<div class="col-xl-6">
									<div class="form-group">
										<label>Expense Date *</label>
										<input type="text" class="form-control datetimepicker" name="expense_dt" id="expense_dt" value="<?php echo $expense_dt; ?>">
									</div>
								</div>
								
							</div>
						</div>
						
						<div class="card-body">
							<input type="hidden" name="save" value="save">
							<?php 
							if(!isset($_GET['expense_id']) && empty($_GET['expense_id'])){ ?>
								<div class="text-right">
									<button type="button" name="submit-btn"  class="btn btn-primary form-submit-btn">Save</button>
								</div><?php
							} ?>
							
							<?php 
							if(isset($_GET['expense_id']) && !empty($_GET['expense_id'])){ ?>
								<input type="hidden" name="ref_expense_id" id="ref_expense_id" value="<?= $_GET['expense_id']; ?>">
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
<script  src="<?php echo WEB_URL; ?>assets/js/c_expense.js"></script>
    </body>
</html>