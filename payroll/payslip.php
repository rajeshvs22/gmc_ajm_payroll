<?php

include('../header.php'); 

$company ="";



//FILTER OPTION
$dep = $emp_name = $cmpy = $emp_code = $condition = $company ="";

if(isset($_GET['dep']) && !empty($_GET['dep'])){
	$dep = $_GET['dep'];
	$condition .= 'AND division = '.$dep;
}
if(isset($_GET['emp_name']) && !empty($_GET['emp_name'])){
	$emp_name = $_GET['emp_name'];
	$condition .= "AND emp_name LIKE '%".$emp_name."%'";
}

if(isset($_GET['cmpy']) && !empty($_GET['cmpy'])){
	$company = $_GET['cmpy'];
	//$condition .= 'AND ref_comp_id = '.$company;
}

/* if(isset($_GET['cmpy']) && !empty($_GET['cmpy'])){
	$company = $_GET['cmpy'];
	$condition .= 'AND ref_comp_id = '.$company;
} */

if(isset($_GET['emp_code']) && !empty($_GET['emp_code'])){
	$emp_code = $_GET['emp_code'];
	$condition .= 'AND emp_code = '.$emp_code;
}
?>

<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<!--- breadcrumb section --->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Payslip</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Payslip</li>
					</ul>
				</div>
			</div>
		</div>
		
		<form action="" method="GET">
		<div class="row filter-row">
			
				<div class="col-sm-6 col-md-3"> 
					<div class="form-group form-focus select-focus">
					
						<select class="select select-company" name="cmpy"> 
							<option value="" <?php if(empty($company)){ echo "Selected"; } ?>>Select Company</option><?php
							$getAllCmpyQry = "SELECT * FROM company_master WHERE 1=1";
							$qryExe = mysqli_query($conn, $getAllCmpyQry); 
							if(mysqli_num_rows($qryExe) > 0){
								$sl_no = 1;
								while($cmpy = mysqli_fetch_assoc($qryExe)){ ?>
									<option value="<?= $cmpy['comp_id'] ?>" <?php if($company == $cmpy['comp_id']){ echo "SELECTED"; } ?>><?= $cmpy['company_name'] ?></option><?php
								}
							} ?>
						</select>
						<label class="focus-label">Company</label>
					</div>
				</div>

				<div class="col-sm-3 col-md-3"> 
					<div class="form-group form-focus select-focus focused">
						<select class="select floating select2-hidden-accessible" id="month" name="month"> 
							<option Disabled <?php if(!isset($_GET['month'])){ echo "SELECTED"; } ?>>Select Month</option>
							
							<option value="1" <?php if(isset($_GET['month']) && $_GET['month'] == '1' ){ echo "SELECTED"; } ?>>JANUARY</option>
							<option value="2" <?php if(isset($_GET['month']) && $_GET['month'] == '2' ){ echo "SELECTED"; } ?>>FEBRUARY</option>
							<option value="3" <?php if(isset($_GET['month']) && $_GET['month'] == '3' ){ echo "SELECTED"; } ?>>MARCH</option>
							<option value="4" <?php if(isset($_GET['month']) && $_GET['month'] == '4' ){ echo "SELECTED"; } ?>>APRIL</option>
							<option value="5" <?php if(isset($_GET['month']) && $_GET['month'] == '5' ){ echo "SELECTED"; } ?>>MAY</option>
							<option value="6" <?php if(isset($_GET['month']) && $_GET['month'] == '6' ){ echo "SELECTED"; } ?>>JUNE</option>
							<option value="7" <?php if(isset($_GET['month']) && $_GET['month'] == '7' ){ echo "SELECTED"; } ?>>JULY</option>
							<option value="8" <?php if(isset($_GET['month']) && $_GET['month'] == '8' ){ echo "SELECTED"; } ?>>AUGUST</option>
							<option value="9" <?php if(isset($_GET['month']) && $_GET['month'] == '9' ){ echo "SELECTED"; } ?>>SEPTEMBER</option>
							<option value="10" <?php if(isset($_GET['month']) && $_GET['month'] == '10' ){ echo "SELECTED"; } ?>>OCTOBER</option>
							<option value="11" <?php if(isset($_GET['month']) && $_GET['month'] == '11' ){ echo "SELECTED"; } ?>>NOVEMBER</option>
							<option value="12" <?php if(isset($_GET['month']) && $_GET['month'] == '12' ){ echo "SELECTED"; } ?>>DECEMBER</option>
						</select>
						<label class="focus-label">Month</label>
					</div>
				</div>
				
				<div class="col-sm-3 col-md-3"> 
					<div class="form-group form-focus select-focus focused">
						<select class="select floating select2-hidden-accessible" id="year" name="year"> 
							<option Disabled <?php if(!isset($_GET['year'])){ echo "SELECTED"; } ?>>Select Year</option><?php
							for($i=2020; $i<= 2050; $i++){?>
								<option value="<?= $i ?>" <?php if(isset($_GET['year']) && $_GET['year'] == $i ){ echo "SELECTED"; } ?>><?= $i ?></option><?php
							} ?>
							
						</select>
						<label class="focus-label">Year</label>
					</div>
				</div>


				<div class="col-sm-6 col-md-3">  
					<div class="form-group form-focus">
						<input type="text" class="form-control floating" name="emp_code" value=<?= $emp_code ?>>
						<label class="focus-label">Employee CODE</label>
					</div>
				</div>
				<div class="col-sm-6 col-md-3">    
					<div class="form-group form-focus">
						<input type="text" class="form-control floating" name="emp_name" value="<?= $emp_name ?>">
						<label class="focus-label">Employee Name</label>
					</div>
				</div>

			

				
				<!-- <input type="hidden" name="month" value="<?= $_GET['month'] ?>">
				<input type="hidden" name="year" value="<?= $_GET['year'] ?>"> -->
				<div class="col-sm-6 col-md-3">  
					<button type="submit" class="btn btn-success btn-block m-b-20"> Search </button>  
				</div> 
			
		</div>
		</form>
		<!-- /Search Filter -->
		
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped custom-table ">
						<thead>
							<tr>
								<th>Sl.No</th>
								<th>Name</th>
								<th>Employee Code</th>
								<th>Company</th>
								<th>Month</th>
								<th>Year</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody><?php
						$month = $_GET['month'];
						$year = $_GET['year'];
								
						$getAllEmpSalQry = "SELECT e.*, (
						SELECT department_name FROM  department_master WHERE dep_id =e.division) as dep_name,
						 ea.*, ep.*, (SELECT company_name FROM company_master WHERE comp_id = e.ref_comp_id) as company_name 
						 FROM employee e 
						 INNER JOIN employee_attendance ea ON e.emp_id = ea.ref_emp_id 
						 INNER JOIN employee_payroll ep ON ea.attendance_id = ep.attendance_id 
						 LEFT JOIN employee_cancellation as EC ON EC.ref_emp_id = e.emp_id
						 WHERE work_status = '$work_status' 
						 AND e.ref_comp_id = '$company' 
						 AND ea.month='$month' 
						 AND ea.year = '$year' 
						 AND (e.employe_status = 0 OR (e.employe_status = 1 AND (YEAR(EC.cancel_date) > '".$year."' OR (YEAR(EC.cancel_date) = '".$year."' AND MONTH(EC.cancel_date) >= '".$month."')))) 
						 ".$condition." ORDER BY e.emp_id";
						//echo $getAllEmpSalQry;
						$qryExe = mysqli_query($conn, $getAllEmpSalQry); 
						if(mysqli_num_rows($qryExe) > 0){ 
							$sl_no = 1;
							while($row = mysqli_fetch_assoc($qryExe)){ ?>
								<tr>
									<td><?= $sl_no ?></td>
									<td><?= $row['emp_name']; ?></td>
									<td><?= $row['emp_code'] ?></td>
									<td><?= $row['company_name']; ?></td>
									<td><?= $month; ?></td>
									<td><?= $year; ?></td>
									<td>
										<ul class="print_actions">
											<li>
												<a class="dropdown-item" href="<?= WEB_URL ?>payroll/view_payslip.php?p_id=<?= $row['payroll_id'] ?>"><i class="fa fa-eye m-r-5"></i></a>
											</li>
											<li>
												<a class="dropdown-item" href="<?= WEB_URL ?>payroll/payslip_pdf.php?p_id=<?= $row['payroll_id'] ?>"><i class="fa fa-print m-r-5"></i></a>
											</li>
										</ul>
									</td>
								</tr><?php
								$sl_no++;
							} 
						} ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>



<?php
require '../footer.php'; ?>

<script  src="<?php echo WEB_URL; ?>assets/js/c_generate_attendance.js"></script>
<script>
$(document).ready(function(){
	$('.custom-table').dataTable({
		"bPaginate": false
	});
});
</script>
    </body>
</html>