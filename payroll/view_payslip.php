<?php

include('../header.php'); 

$company_name = $emp_name = $position = $emp_code = $joining_date = $salary_month = $basic_salary = $house_rent = $conveyance = $food_allowance = $medical_allowance = $other_allowances = $loan_deductions = $total_earnings = "";

$getSalaryDetailsQry = "SELECT
			ep.*, 
			( SELECT company_name FROM company_master WHERE comp_id = ep.ref_comp_id ) as company_name
			FROM employee_payroll ep 
			WHERE ep.payroll_id=".$_GET['p_id'];


$getSalaryDetailsQryExe = mysqli_query($conn, $getSalaryDetailsQry); 
if(mysqli_num_rows($getSalaryDetailsQryExe) > 0){ 
	while($getSalaryDetailsData = mysqli_fetch_assoc($getSalaryDetailsQryExe)){ 
		//echo "<pre>";print_r($getSalaryDetailsData);exit;
		$emp_id = $getSalaryDetailsData['emp_id_'];
		$company_name = $getSalaryDetailsData['company_name'];
		$emp_name = $getSalaryDetailsData['emp_name_'];
		$position = $getSalaryDetailsData['position_name_'];
		$emp_code = $getSalaryDetailsData['emp_code_'];
		
		$month  = $getSalaryDetailsData['month'];
		$dateObj   = DateTime::createFromFormat('!m', $month);
		$year = $getSalaryDetailsData['year'];
		$salary_month = $dateObj->format('F')." ".$year;
		
		$total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		
		$basic_salary = $getSalaryDetailsData['salary_for_this_month'];
		//$over_time_salary = $getSalaryDetailsData['e_overtime_'] * $getSalaryDetailsData['over_time_hour_rate_'];
		$over_time_salary  = $getSalaryDetailsData['overtime_sal_for_curent_month'];
		
				$basic_salary = (int)$basic_salary;
		
		$house_rent = $getSalaryDetailsData['housing_a'];
		
		if($basic_salary > 0 || $over_time_salary > 0){
			
		//$conveyance = $getSalaryDetailsData['conveyance_allowance'];
			/*
			$perDay_conveyance_allowance = $getSalaryDetailsData['conveyance_a']/$total_days;
			$conveyance = round($perDay_conveyance_allowance * $getSalaryDetailsData['no_of_wdays_']); 
			*/
			$conveyance = $getSalaryDetailsData['conveyance_a'];
			
			//echo $getSalaryDetailsData['conveyance_a'];exit;
			$food_allowance = $getSalaryDetailsData['food_a'];
			$medical_allowance = $getSalaryDetailsData['medical_a'];
			$other_allowances = $getSalaryDetailsData['allowance'];
			$other_allowances = (int)$other_allowances;
		}else{
			
			$conveyance = 0;
			$food_allowance = 0;
			$medical_allowance = 0;
			$other_allowances = 0;
		}
		
		$loan_deductions = $getSalaryDetailsData['deduct_loan'];
		
		//leave salary or bonus
		$getLvsalBonusByIdQry = "SELECT * FROM leave_sal_bonus WHERE ref_emp_id='$emp_id' AND month='$month' AND year='$year'";
		$getLvsalBonusByIdQryExe = mysqli_query($conn, $getLvsalBonusByIdQry);
		$data = '';
		$leavSalary = $bonus = 0;
		if(mysqli_num_rows($getLvsalBonusByIdQryExe) > 0){
			$theLvsalBonusData = mysqli_fetch_assoc($getLvsalBonusByIdQryExe);
			$leavSalary = $theLvsalBonusData['leave_Salary'];
			$bonus = $theLvsalBonusData['bonus'];
		}
		
		
		$total_earnings = ($basic_salary + $house_rent + $conveyance + $food_allowance + $medical_allowance + $other_allowances + $over_time_salary + $leavSalary + $bonus) - $loan_deductions;
		$total_earnings = round($total_earnings);
		
		
		$total_wdays = $leave_days = $over_time_hrs = 0;
	
		$over_time_hrs = $getSalaryDetailsData['e_overtime_'];
		
		if($total_days == $getSalaryDetailsData['no_of_wdays_']){
			$total_wdays = $getSalaryDetailsData['no_of_wdays_'];
		}else{
			$total_wdays =$getSalaryDetailsData['no_of_wdays_'];
			$leave_days = $total_days - $getSalaryDetailsData['no_of_wdays_'];
		}
	}
}



//$getDateMonth = "SELECT * FROM employee_attendance WHERE attendance_id=".$_GET[''];


?>


<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<!--- breadcrumb section --->
		<div class="page-header">
			<div class="row align-items-center">
				<div class="col">
					<h3 class="page-title">Payslip</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>">Dashboard</a></li>
						<li class="breadcrumb-item active">Payslip</li>
					</ul>
				</div>
				<div class="col-auto float-right ml-auto">
					<div class="btn-group btn-group-sm">
						<a href="<?php echo WEB_URL ?>payroll/payslip_pdf.php?p_id=<?= $_GET['p_id'] ?>" class="btn btn-white">Download PDF</a>
						<!----<button class="btn btn-white"><i class="fa fa-print fa-lg"></i> Print</button> ---->
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<h4 class="payslip-title">Payslip for the month of <?= $salary_month ?></h4>
						<div class="row">
							<div class="col-sm-6 m-b-20">
								<img src="<?php echo WEB_URL ?>assets/img/logo.png" class="inv-logo" alt="">
								<ul class="list-unstyled mb-0">
									<li><?= $company_name ?></li>
								</ul>
							</div>
							<div class="col-sm-6 m-b-20">
								<div class="invoice-details">
									<ul class="list-unstyled">
										<li>Salary Month: <span><?= $salary_month ?></span></li>
									</ul>
								</div>
							</div>
						</div>
						<div class="row m-b-20">
							<div class="col-lg-6">
								<ul class="list-unstyled">
									<li><h5 class="mb-0"><strong><?= $emp_name ?></strong></h5></li>
									<li><span><?= $position  ?></span></li>
									<li>Employee ID: <?= $emp_code ?></li>
								</ul>
							</div>
							<div class="col-lg-6 text-right">
								<ul class="list-unstyled">
									<li><strong>No Of Working Days:</strong> <?= $total_wdays ?></li>
									<li><strong>Leave Days: </strong><?= $leave_days ?></li>
									<li><strong>Over Time Hours:</strong> <?= $over_time_hrs ?></li>
								</ul>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div>
									<h4 class="m-b-10"><strong>Earnings</strong></h4>
									<table class="table table-bordered">
										<tbody>
											<tr>
												<td><strong>Basic Salary</strong> <span class="float-right"><?= (int)$basic_salary ?></span></td>
											</tr>
											<tr>
												<td><strong>Overtime Salary</strong> <span class="float-right"><?= round($over_time_salary) ?></span></td>
											</tr>
											<tr>
												<td><strong>House Rent Allowance (H.R.A.)</strong> <span class="float-right"><?= $house_rent ?></span></td>
											</tr>
											<tr>
												<td><strong>Conveyance</strong> <span class="float-right"><?= $conveyance  ?></span></td>
											</tr>
											<tr>
												<td><strong>Food  Allowance</strong> <span class="float-right"><?= $food_allowance ?></span></td>
											</tr>
											<tr>
												<td><strong>Medical  Allowance</strong> <span class="float-right"><?= $medical_allowance ?></span></td>
											</tr>
											<tr>
												<td><strong>Allowance</strong> <span class="float-right"><?= (int)$other_allowances ?></span></td>
											</tr>
											<tr>
												<td><strong>Bonus</strong> <span class="float-right"><?= (int)$bonus ?></span></td>
											</tr>
												<tr>
												<td><strong>Leave Salary</strong> <span class="float-right"><?= (int)$leavSalary ?></span></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="col-sm-6">
								<div>
									<h4 class="m-b-10"><strong>Deductions</strong></h4>
									<table class="table table-bordered">
										<tbody>
											<tr>
												<td><strong>Loan Paid</strong> <span class="float-right"><?= $loan_deductions ?></span></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<div class="col-md-12">
								<table class="table table-bordered">
									<tbody>
										<tr>
											<td><strong>Total Payable</strong> <span class="float-right"><?= $total_earnings ?></span></td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="col-sm-12">
								<p style="text-transform: capitalize;"><strong>Net Salary: <?= $total_earnings ?></strong> ( <?php echo numbersToWords($total_earnings); ?> )</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
		
<?php
require '../footer.php'; ?>

<script  src="<?php echo WEB_URL; ?>assets/js/c_generate_attendance.js"></script>
    </body>
</html>