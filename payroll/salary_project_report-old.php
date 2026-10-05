<?php

include('../header.php');


$project_id ="";
if(isset($_GET['project_id']) && !empty($_GET['project_id'])){
	$project_id = $_GET['project_id'];
}
$month = $_GET['month'];
$year = $_GET['year'];

if(isset($_GET['month']) && !empty($_GET['month']) && isset($_GET['year']) && !empty($_GET['year'])){
	$total_days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
}

?>

<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<!--- breadcrumb section --->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Project Salary Report</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Project Salary Report</li>
					</ul>
				</div>
				
			</div>
		</div> 
		
		
		<form name="projectsalary_report_fm" id="generate_project_salary_fm" method="POST">
			<div class="row filter-row">
				<div class="col-sm-4 col-md-3"> 
					<div class="form-group form-focus select-focus">
					
						<select class="select select-company" name="project_id" id="choose_project"><?php
							$getAllCmpyQry = "SELECT * FROM project_details WHERE 1=1";
							$qryExe = mysqli_query($conn, $getAllCmpyQry); 
							if(mysqli_num_rows($qryExe) > 0){
								$sl_no = 1;
								while($cmpy = mysqli_fetch_assoc($qryExe)){ ?>
									<option value="<?= $cmpy['proj_id'] ?>" <?php if($project_id == $cmpy['proj_id']){ echo "SELECTED"; } ?>><?= $cmpy['proj_name'] ?></option><?php
								}
							} ?>
						</select>
						<label class="focus-label">Project</label>
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
					</div>
				</div>
				
				<div class="col-sm-3 col-md-3">  
					<input type="hidden" name="base_url" id="base_url" value="<?= WEB_URL ?>">
					<button class="btn btn-success btn-block" name="submit" value="submit" type="submit">Go</button>
				</div>    
			</div>
		</form>
		
		<?php
		if(isset($_GET['month']) && isset($_GET['project_id']) && isset($_GET['year'])){ ?>
		<div class="row">
			<div class="col-auto float-right ml-auto mb-3">
				<div class="btn-group btn-group-sm">
					<a href="<?php echo WEB_URL ?>payroll/salary_project_repport_pdf.php?project_id=
					<?= $project_id ?>&month=<?= $month ?>&year=<?= $year ?>" class="btn btn-white">Download PDF</a>
					<!----<button class="btn btn-white"><i class="fa fa-print fa-lg"></i> Print</button> ---->
				</div>
			</div>
			<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped custom-table ">
							<thead>
								<tr>
									<th>Sl.No</th>
									<th>Employee Code</th>
									<th>Name</th>
									<th>Passport No</th> 
									<th>Designation</th>
									<th>Number of Working Days </th>
									<th>Overtime (Hour) </th>
									<th>Monthly Salary</th>
									<th>Overtime for an Hour</th>
									<th>Salary for This Month</th>
									<th>Amount of Over Time</th>
									<th>Allowance</th>
									<th>Total Payable</th>
									<th>Advance / Loan </th>
									<th>Net Payable</th>
									<th>Signature</th>
								</tr>
							</thead>
							<tbody><?php
								/*
								if(1==2){
									//$getAllEmpSalQry = "SELECT e.*, (SELECT department_name FROM  department_master WHERE dep_id =e.division) as dep_name, (SELECT position_name FROM  position_master WHERE position_id =e.position) as position_name, ea.*, ep.* FROM employee e INNER JOIN employee_attendance ea ON e.emp_id = ea.ref_emp_id INNER JOIN employee_payroll ep ON ea.attendance_id = ep.attendance_id WHERE ea.month='$month' AND ea.year = '$year' ORDER BY e.emp_id";
								}else{
									
									
									//Get project Salary Details
									$getAllEmpSalQry = "SELECT 
											e.*, 
											(SELECT department_name FROM  department_master WHERE dep_id =e.division) as dep_name, 
											(SELECT position_name FROM  position_master WHERE position_id =e.position) as position_name, 
											ea.*, ep.* 
											FROM employee e 
											INNER JOIN employee_attendance ea ON e.emp_id = ea.ref_emp_id 
											INNER JOIN employee_payroll ep ON ea.attendance_id = ep.attendance_id 
											LEFT JOIN employee_cancellation as EC ON EC.ref_emp_id = e.emp_id 
											WHERE 
											work_status = '$work_status' AND 
											ea.month='$month' AND 
											ea.year = '$year' AND 
											ea.ref_emp_id IN ($employee_id) AND 
											(e.employe_status = 0 OR (e.employe_status = 1 AND YEAR(EC.cancel_date) >= '".$year."' AND MONTH(EC.cancel_date) >='".$month."'))

											ORDER BY e.emp_id";
								}*/

								$getAllEmpSalQry = "SELECT 
													ep.*,e.passport_number
													FROM employee_payroll as ep 
													JOIN employee e ON e.emp_id = ep.emp_id_ 
													WHERE 
													ep.month='$month' AND 
													ep.year = '$year'  AND 
													ep.project_id_ = '$project_id' AND
													ep.work_status_ = '".$work_status."'
													ORDER BY e.emp_id";
//echo $getAllEmpSalQry;exit;
								
								$qryExe1 = mysqli_query($conn, $getAllEmpSalQry); 
								
								$dataRowCount1 = mysqli_num_rows($qryExe1);
								if($dataRowCount1 > 0){ 
									$sl_no =1;
									while($row = mysqli_fetch_assoc($qryExe1)){ ?>
										
									
									<tr>
										<td><?= $sl_no ?></td>
										<td><?= $row['emp_code_'] ?></td>
										<td><?= $row['emp_name_'] ?></td>
										<td><?= $row['passport_number'] ?></td>
										<td><?= $row['position_name_'] ?></td>
										<td><?= $row['no_of_wdays_'] ?></td>
										<td><?= $row['e_overtime_'] ?></td>
										<td><?= $row['salary_'] ?></td>
										<td><?= $row['over_time_hour_rate_'] ?></td>
										<td><?= (int)$row['salary_for_this_month'] ?></td>
										<td><?= (int)$row['overtime_sal_for_curent_month'] ?></td>
										<td><?= $row['deduct_loan'] ?></td> 
										<td><?= (int)$row['total_payable'] ?></td>
										<td><?= $row['deduct_loan'] ?></td> 
										<td><?= $row['net_payable'] ?></td>
										<td></td> 
									</tr><?php 
									$sl_no++;
									}
								} ?>
							</tbody>
						</table>
					</div>
			</div>
		</div><?php
		} ?>
		
	</div>
</div>


<?php
require '../footer.php'

?>
<script  src="<?php echo WEB_URL; ?>assets/js/c_salary_report.js"></script>
<script>
$(document).ready(function(){
	$('.custom-table').dataTable({
		"bPaginate": false
	});
});
</script>
    </body>
</html>