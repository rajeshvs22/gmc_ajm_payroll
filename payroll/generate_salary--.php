<?php

include('../header.php');
ini_set('max_execution_time', 0);

$company ="";
if(isset($_GET['cmpy']) && !empty($_GET['cmpy'])){
	$company = $_GET['cmpy'];
}
?>

<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<!--- breadcrumb section --->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Generate Salary</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Generate Salary</li>
					</ul>
				</div>
			</div>
		</div> 
		<form name="generate_salary_fm" id="generate_salary_fm" method="POST">
			<div class="row filter-row">
				<div class="col-sm-4 col-md-3"> 
					<div class="form-group form-focus select-focus">
					
						<select class="select select-company" name="cmpy" id="choose_company"><?php
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
		if(isset($_GET['month']) && isset($_GET['cmpy']) && isset($_GET['year'])){ ?>
		<div class="row">
			<div class="col-md-12 text-right mb-3">
				<button class="btn btn-primary submit-btn" type="submit" name="submit" value="submit">Save All</button>
			</div>
			<div class="col-md-12">
				<!--- <form id="generate_salary_fm" method="POST"> --->
					<div class="table-responsive">
						<table class="table table-striped custom-table ">
							<thead>
								<tr>
									<th>Sl.No</th>
									<th>Employee Code</th>
									<th>Name</th>
									<th>Position</th> 
									<th>Number of working days </th>
									<th>Overtime (Hour) </th>
									<th>Monthly Salary</th>
									<th>Payable for an hour</th>
									<th>Salary for this month</th>
									<th>Amount of over time</th>
									<th>Allowance</th>
									<th>Food Allowance</th>
									<th>Conveyance Allowance</th>
									<th>Medical Allowance</th>
									<th>Housing Allowance</th>
									<th>Total Payable</th>
									<th>Loan Amount </th>
									<th>Deduct loan </th>
									<th>Net Payable</th>
								</tr>
							</thead>
							<tbody>	<?php
							$dataRowCount = 0;	
							$month = $_GET['month'];
							$year = $_GET['year'];
							if(1==2){
								$getAllEmpSalQry = "SELECT e.*, (SELECT department_name FROM  department_master WHERE dep_id =e.division) as dep_name, ea.* FROM employee e INNER JOIN employee_attendance ea ON e.emp_id = ea.ref_emp_id WHERE ea.month='$month' AND ea.year = '$year'";
							}else{
								$getAllEmpSalQry = "SELECT e.*, (SELECT department_name FROM  department_master WHERE dep_id =e.division) as dep_name, (SELECT position_name FROM position_master WHERE position_id = e.position) as position_name,ea.* FROM employee e INNER JOIN employee_attendance ea ON e.emp_id = ea.ref_emp_id WHERE e.work_status = '$work_status' AND e.ref_comp_id = '$company' AND ea.month='$month' AND ea.year = '$year' ORDER BY e.emp_id";
							}
													
							$qryExe1 = mysqli_query($conn, $getAllEmpSalQry); 
							$dataRowCount1 = mysqli_num_rows($qryExe1);
							if($dataRowCount1 > 0){ 
								$sl_no = 1;
								while($row = mysqli_fetch_assoc($qryExe1)){
								$attendance_id = $row['attendance_id'];
								
								$salary_for_this_month = $overtime_sal_for_curent_month = $allowance = $total_payable = $deduct_loan = $net_payable = '';
								
								$getPayrolQry = "SELECT * FROM employee_payroll WHERE attendance_id = '$attendance_id'";
									
									$getPayrolQryExe = mysqli_query($conn, $getPayrolQry); 
									if( mysqli_num_rows($getPayrolQryExe) > 0){ 
										$row1 = mysqli_fetch_assoc($getPayrolQryExe);
										$salary_for_this_month = $row1['salary_for_this_month'];
										$overtime_sal_for_curent_month = $row1['overtime_sal_for_curent_month'];
										$allowance = $row1['allowance'];
										
										$total_payable = $row1['total_payable'];
										$deduct_loan = $row1['deduct_loan'];
										$net_payable = $row1['net_payable'];
									}
									
									
								?>							
								<tr>
								
									<td><?= $sl_no ?></td>
									<td><?= $row['emp_code'] ?></td>
									<td><?= $row['emp_name'] ?></td>
									<td><?= $row['position_name'] ?></td> 
									<td>
										<div class="form-group">
											<input type="text" class="form-control no_of_wdays_<?= $attendance_id ?>" name="no_of_wdays[]" id="" value="<?= $row['no_of_wdays'] ?>" readonly>
										</div>
									</td>
									<td>
										<div class="form-group">
											<input type="text" class="form-control e_overtime_<?= $attendance_id ?>" name="e_overtime[]" id="" value="<?= $row['e_overtime'] ?>" readonly>
										</div>
									</td>
									<td>
										<div class="form-group">
											<input type="text" class="form-control salary_<?= $attendance_id ?>" name="salary[]" id="" value="<?= $row['salary'] ?>" readonly>
										</div>
									</td>	
									<td>
										<div class="form-group">
											<input type="text" class="form-control over_time_hour_rate_<?= $attendance_id ?>" name="over_time_hour_rate[]" id="" value="<?php echo $row['over_time_hour_rate']; ?>" readonly>
										</div>
									</td>	
									<td>
										<div class="form-group"><?php
										$total_days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
										$salaryPerDay = $row['salary'] / $total_days; 
										if(!isset($salary_for_this_month) || empty($salary_for_this_month)){
											$salary_for_this_month = $row['no_of_wdays'] * $salaryPerDay;
										}
										 ?>
											<input type="text" class="form-control salary_for_this_month salary_for_this_month_<?= $attendance_id ?>" name="salary_for_this_month[]" data-id="<?= $attendance_id ?>" value="<?= $salary_for_this_month ?>" >
										</div>
									</td>	<td>
										<div class="form-group"><?php
										
										$overtime_sal_for_curent_month = round($row['over_time_hour_rate'] * $row['e_overtime'] ); ?>
											<input type="text" class="form-control overtime_sal overtime_sal_for_curent_month_<?= $attendance_id ?>" name="overtime_sal_for_curent_month[]" data-id="<?= $attendance_id ?>" value="<?= $overtime_sal_for_curent_month ?>" readonly>
										</div>
									</td>	
									
									<td>
										<div class="form-group"><?php
										if(!isset($allowance) || empty($allowance)){
											if($salary_for_this_month > 0 && $overtime_sal_for_curent_month > 0){
												$allowance = $row['allowance'];
											}else{
												$allowance = 0;
											}
											
											
										} ?>
											<input type="text" class="form-control allowance allowance_<?= $attendance_id ?>" name="allowance[]" data-id="<?= $attendance_id ?>" value="<?= $allowance ?>" >
										</div>
									</td>
									
									<td>
										<div class="form-group"><?php
											if($salary_for_this_month > 0 && $overtime_sal_for_curent_month > 0){
												$food_allowance = $row['food_allowance'];
											}else{
												$food_allowance = 0;
											}
											
										 ?>
											<input type="text" class="form-control food_allowance food_allowance_<?= $food_allowance ?>" name="food_allowance[]" data-id="<?= $food_allowance ?>" value="<?= $food_allowance ?>" readonly>
										</div>
									</td>
									
									<td>
										<div class="form-group"><?php
											if($salary_for_this_month > 0 && $overtime_sal_for_curent_month > 0){
												$conveyance_allowance = $row['conveyance_allowance'];
											}else{
												$conveyance_allowance = 0;
											}
											
									?>
											<input type="text" class="form-control conveyance_allowance conveyance_allowance_<?= $conveyance_allowance ?>" name="conveyance_allowance[]" data-id="<?= $conveyance_allowance ?>" value="<?= $conveyance_allowance ?>" readonly>
										</div>
									</td>
									
									
									<td>
										<div class="form-group"><?php
											if($salary_for_this_month > 0 && $overtime_sal_for_curent_month > 0){
												$medical_allowance = $row['medical_allowance'];
											}else{
												$medical_allowance = 0;
											}
											 ?>
											<input type="text" class="form-control medical_allowance medical_allowance_<?= $medical_allowance ?>" name="medical_allowance[]" data-id="<?= $medical_allowance ?>" value="<?= $medical_allowance ?>" readonly>
										</div>
									</td>
									
									<td>
										<div class="form-group"><?php
											if($salary_for_this_month > 0 && $overtime_sal_for_curent_month > 0){
												$housing_allowance = $row['housing_allowance'];
											}else{
												$housing_allowance = 0;
											}
											 ?>
											<input type="text" class="form-control housing_allowance housing_allowance_<?= $housing_allowance ?>" name="housing_allowance[]" data-id="<?= $housing_allowance ?>" value="<?= $housing_allowance ?>" readonly>
										</div>
									</td>
									
									

									<td>
										<div class="form-group"><?php
										$total_payable = $salary_for_this_month + $overtime_sal_for_curent_month + $allowance ; ?>
											<input type="text" class="form-control total_payable_<?= $attendance_id ?>" name="total_payable[]" id="" value="<?= $total_payable ?>" readonly>
										</div>
									</td>	
									<td><?php
									$empLoanTotalQry = "SELECT sum(loan_amount) as total_loan FROM loan_details WHERE ref_emp_id=".$row['emp_id'];
									$empLoanTotalQryExe = mysqli_query($conn, $empLoanTotalQry); 
									$loan_total = $loan_paid_total = 0;
									if(mysqli_num_rows($empLoanTotalQryExe) > 0){
										$row2 = mysqli_fetch_assoc($empLoanTotalQryExe);
										$loan_total = $row2['total_loan'];
									}
									$empLoanPaidTotalQry = "SELECT sum(paid_amount) as paid_amount FROM loan_payment_history WHERE ref_emp_id=".$row['emp_id'];
									$empLoanPaidTotalQryExe = mysqli_query($conn, $empLoanPaidTotalQry); 
									if(mysqli_num_rows($empLoanPaidTotalQryExe) > 0){
										$row3 = mysqli_fetch_assoc($empLoanPaidTotalQryExe);
										$loan_paid_total = $row3['paid_amount'];
									}

									?>
										<div class="form-group">
											<input type="text" class="form-control loan_amount loan_amount_<?= $attendance_id ?>" name="loan_amount[]" data-id="<?= $attendance_id ?>" value="<?= $loan_total - $loan_paid_total ?>" readonly>
										</div>
									</td>	
									<td>
										<div class="form-group"><?php
										if(!isset($deduct_loan) || empty($deduct_loan)){
											$deduct_loan = '0';
										} ?>
											<input type="text" class="form-control deduct_loan deduct_loan_<?= $attendance_id ?>" name="deduct_loan[]" data-id="<?= $attendance_id ?>" value="<?= $deduct_loan ?>" <?php if(($loan_total - $loan_paid_total) < 1 ){ echo "Readonly"; } ?>>
										</div>
									</td>	
									<td>
										<div class="form-group"><?php
										$net_payable = intval($total_payable) - intval($deduct_loan); ?>
											<input type="text" class="form-control net_payable_<?= $attendance_id ?>" name="net_payable[]" id="" value="<?= $net_payable+ $food_allowance + $conveyance_allowance + $medical_allowance + $housing_allowance ?>" readonly>
											<input type="hidden" name="emp_id[]" value="<?= $row['emp_id'] ?>" class="emp_id emp_id_<?= $row['attendance_id'] ?>">
											<input type="hidden" name="attendance_id[]" value="<?= $attendance_id ?>" class="ref_attendance_id ref_attendance_id_<?= $attendance_id ?>">
										</div>
									</td>	
								</tr><?php
								$sl_no++;
								}
							}?>
							</tbody>
						</table>
					</div>
					<div class="submit-section">
						<input type="hidden" value="<?= $_GET['cmpy'] ?>" id="cmpy" name="cmpy">
						<input type="hidden" value="<?= $_GET['month'] ?>" id="salary_month" name="salary_month">
						<input type="hidden" value="<?= $_GET['year'] ?>" id="salary_year" name="salary_year">
						<button class="btn btn-primary submit-btn" type="submit" name="submit" value="submit">Save All</button>
					</div>
				<!---- </form> --->
			</div>
		</div> <?php
		} ?>
		
	</div>
</div>

<span class="loader" style="display: none;"></span>

<?php
require '../footer.php'

?>
 <script  src="<?php echo WEB_URL; ?>assets/js/c_generate_salary.js"></script>
<script>
$(document).ready(function(){
	$('.custom-table').dataTable({
		"bPaginate": false
	});
});
</script>
    </body>
</html>