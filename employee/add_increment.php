<?php

include('../header.php'); 
$emp_id = $_GET['eid'];
$emp_name = $current_salary = $previous_salary = $increment_date = $increment_amount = $previous_allowance = $increment_allowance = "";



if(isset($_POST['submit'])){
	$previous_salary = $_POST['previous_salary'];
	$increment_date = $_POST['increment_date'];
	$increment_amount = $_POST['increment_amount'];
	$increment_allowance = ($_POST['increment_allowance'] != '' ? $_POST['increment_allowance'] : 0);
	$previous_allowance = $_POST['previous_allowance'];
	
	$current_inc_salary = $previous_salary + $increment_amount;
	$current_inc_allowance = $previous_allowance + $increment_allowance;
	
	if(!empty($increment_date)){
		$increment_date = str_replace( '/', '-', $increment_date );  
		$increment_date = date( "Y/m/d", strtotime($increment_date) ); 
	}
	
	$insertIncrementQry = "INSERT INTO salary_increment (ref_emp_id, previous_salary,previous_allowance, increment_amt, current_inc_salary, increment_allowance, current_inc_allowance,increment_dt) VALUES ('$emp_id', '$previous_salary', '$previous_allowance', '$increment_amount', '$current_inc_salary','$increment_allowance' , '$current_inc_allowance', '$increment_date' )";
	
	mysqli_query($conn,$insertIncrementQry);
	$inserted_increment_sal_id = mysqli_insert_id($conn);
	echo "aaa".$inserted_increment_sal_id;
	if( $inserted_increment_sal_id  > 0 ){
		$new_over_time_rate = (($current_inc_salary/30) / 9) * 1.3;
		$new_over_time_rate =number_format((float)$new_over_time_rate, 2, '.', '');
		
		$updatesalaryQry = "UPDATE employee SET salary = '$current_inc_salary', allowance = '$current_inc_allowance', over_time_hour_rate= '$new_over_time_rate' WHERE emp_id = ".$emp_id;
		mysqli_query($conn,$updatesalaryQry);
		
	}
}


$getEmpCurrentSalQry = "SELECT emp_name, salary, allowance FROM employee WHERE emp_id=".$emp_id;
$getEmpCurrentSalExe = mysqli_query($conn, $getEmpCurrentSalQry);
if(mysqli_num_rows($getEmpCurrentSalExe) > 0){
	$theEmpsalary = mysqli_fetch_assoc($getEmpCurrentSalExe);
	$emp_name = $theEmpsalary['emp_name'];
	$current_salary = $theEmpsalary['salary'];
	$previous_allowance = $theEmpsalary['allowance'];
	
	echo $previous_allowance."aaaa";
}

?>


<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<!--- breadcrumb section --->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Add Increment</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Add Increment</li>
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
					<form action="" method="POST" id="salary_increment_fm" enctype="multipart/form-data">
						<div class="card-body">
							<div class="row">
								<div class="col-xl-12">
									<div class="form-group">
										<label class="needs-validation">Current salary </label>
										<input type="text" class="form-control" name="current_salary" id="current_salary" value="<?= $current_salary ?>" readonly>
										
									</div>
								</div>
								<div class="col-xl-6">
									<div class="form-group">
										<label class="needs-validation">Employee Name </label>
										<input type="text" class="form-control" name="emp_name" id="emp_name" value="<?= $emp_name ?>" readonly>
										
									</div>
								</div>
							</div>
						</div>
						
						<div class="card-header">
							<h4 class="card-title mb-0">Increament History</h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-2">
									<label class="needs-validation">Previous salary </label>
								</div>
								<div class="col-md-2">
									<label class="needs-validation">Previous Allowance </label>
								</div>
								<div class="col-md-2">
									<label class="needs-validation">Increment Date</label>
								</div>
								<div class="col-md-2">
									<label class="needs-validation">Increment Salary</label>
								</div>
								<div class="col-md-2">
									<label class="needs-validation">Increment Allowance</label>
								</div>
								<div class="col-md-2">
									<label class="needs-validation">Action </label>
								</div>
							</div><?php
						// get salary history
						$getIncrementHistryQry = "SELECT * FROM salary_increment WHERE ref_emp_id =".$_GET['eid'];
						$getIncrementHistryExe = mysqli_query($conn, $getIncrementHistryQry);
						
						$i = 1;
						if(mysqli_num_rows($getIncrementHistryExe) > 0){
							$rowsCount = mysqli_num_rows($getIncrementHistryExe);
							while( $theIncrementHistry = mysqli_fetch_assoc($getIncrementHistryExe) ){ 
								$incrementDt = $theIncrementHistry['increment_dt'] == '0000-00-00' ? '' : date('d-m-Y',strtotime($theIncrementHistry['increment_dt'])); 
								$current_salary = $theIncrementHistry['current_inc_salary']; 
								$previous_allowance = $theIncrementHistry['current_inc_allowance']; 
								?>
								<div class="row">
									<div class="col-xl-2">
										<div class="form-group">
											<input type="text" class="form-control" name="" id="" value="<?= $theIncrementHistry['previous_salary'] ?>" readonly >
										</div>
									</div>
									
									<div class="col-xl-2">
										<div class="form-group">
											<input type="text" class="form-control" name="" id="" value="<?= $theIncrementHistry['previous_allowance'] ?>" readonly >
										</div>
									</div>
									
									<div class="col-xl-2">
										<div class="form-group">
											<input type="text" class="form-control datetimepicker" name="" id="" value="<?= $incrementDt ?>" Readonly>
										</div>
									</div>
									<div class="col-xl-2">
										<div class="form-group">
											
											<input type="text" class="form-control" name="" id="" value="<?= $theIncrementHistry['increment_amt'] ?>" Readonly>
										</div>
									</div>
									<div class="col-xl-2">
										<div class="form-group">
											
											<input type="text" class="form-control" name="" id="" value="<?= $theIncrementHistry['increment_allowance'] ?>" Readonly>
										</div>
									</div><?php
									if($rowsCount == $i){ ?>
										<div class="col-xl-2">
											<button type="button" data-toggle="modal" data-target="#delete_increment" class="form-control btn   btn-danger increment-remove-btn" id="increment-remove-btn" data-increment-id="<?= $theIncrementHistry['increment_id'] ?>" >Remove</button>
										</div><?php
									} ?>
									
								</div><?php
								$i++;
							}
							
						} ?>
						
							<div class="row">
								<div class="col-xl-2">
									<div class="form-group">
										<input type="text" class="form-control" name="previous_salary" id="previous_salary" value="<?= $current_salary ?>" readonly>
									</div>
								</div>
								
								<div class="col-xl-2">
									<div class="form-group">
										<input type="text" class="form-control" name="previous_allowance" id="previous_allowance" value="<?= $previous_allowance ?>" readonly>
									</div>
								</div>
								
								<div class="col-xl-2">
									<div class="form-group">
										<input type="text" class="form-control datetimepicker" name="increment_date" id="increment_date" value="">
									</div>
								</div>
								<div class="col-xl-2">
									<div class="form-group">
										<input type="text" class="form-control" name="increment_amount" id="increment_amount" value="" >
										
									</div>
								</div>
								
								<div class="col-xl-2">
									<div class="form-group">
										<input type="text" class="form-control" name="increment_allowance" id="increment_allowance" value="" >
										
									</div>
								</div>
								
							</div>
						</div>
						<div class="card-body">
							<div class="text-right">
								<button type="submit" name="submit" value="submit" class="btn btn-primary">Save</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		
		<!---Delet model---->
		<div class="modal custom-modal fade" id="delete_increment" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<form id="delet_inc_fm" method="POST">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-delet" style="display:none">Increment Deleted Sucessfully
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="alert alert-danger alert-dismissible fade show small" role="alert" id="delet-error" style="display:none">Somthing Went Wrong, Try Again..
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">×</span>
								</button>
							</div>
							<div class="form-header1">
								<h3>Delete Employee Increment</h3>
								<p>Are you sure want to delete?</p>
							</div>
							<div class="modal-btn delete-action">
								<div class="row">
									<div class="col-6">
										<input type="hidden" name="delet_incremnt_id" value="" id="delet_incremnt_id">
										<button type="submit" class="btn btn-primary continue-btn" class="delet_incremnt_btn">Delete</button>
									</div>
									<div class="col-6">
										<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
									</div>
								</div>
							</div>
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