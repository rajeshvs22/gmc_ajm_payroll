<?php

include('../header.php');


$company = $month = $year = "";
if(isset($_GET['cmpy']) && !empty($_GET['cmpy'])){
	$company = $_GET['cmpy'];
}
if(isset($_GET['month']) && !empty($_GET['month'])){
	$month = $_GET['month'];
	$month = (int)$month;
}
if(isset($_GET['year']) && !empty($_GET['year'])){
	$year = $_GET['year'];
	$year = (int)$year;
}
if(isset($_GET['month']) && !empty($_GET['month']) && isset($_GET['year']) && !empty($_GET['year'])){
	$total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
}

	

?>
<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<!--- breadcrumb section --->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">WPS Salary Report</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">WPS Salary Report</li>
					</ul>
				</div>
			</div>
		</div> 
		
		
		<form name="wps_salary_report_fm" id="wps_salary_report_fm" method="POST">
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
				<a href="<?php echo WEB_URL; ?>payroll/export_wps_salary_report.php?cmpy=<?= $company ?>&month=<?= $month ?>&year=<?= $year ?>" class="btn btn-primary">Export</a>
			</div>
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped custom-table datatable-no-sorting">
						<thead>
							<tr>
								<th>Sl.No</th>
								<th>Employee MOLID</th>
								<th>Name</th>
								<th>ACCOUNT NO</th>
								<th>SALARY</th>
								<th>VARIABLE PAY</th>
								<th>From Date</th>
								<th>To Date</th>
								<th>Days on Leave</th>
								<th>Labor Card No</th>
								<th>AGENT BANK ROUTING CODE</th>
								<th>Corporate MOL ESTID</th>
								<th>Corporate AccountNo</th>
								<th>Housing Allowance</th>
								<th>Conveyance Allowance</th>
								<th>Medical Allowance</th>
								<th>Annual Passage Allowance</th>
								<th>Overtime Allowances</th>
								<th>All Other Allowances</th>
								<th>Leave Encashment</th>
							</tr>
						</thead>
						<tbody><?php
						$getWPS_Report = "SELECT 
												ep.*,
												e.emp_id
											FROM employee e 
											INNER JOIN employee_attendance ea ON e.emp_id=ea.ref_emp_id 
											INNER JOIN employee_payroll ep ON ea.attendance_id=ep.attendance_id 
											LEFT JOIN employee_cancellation as EC ON EC.ref_emp_id = e.emp_id
											WHERE ep.month = ".$month." AND 
											ep.year=".$year." AND 
											ep.ref_comp_id=".$company." AND 
											(e.employe_status = 0 OR (e.employe_status = 1 AND YEAR(EC.cancel_date) >= '".$year."' AND MONTH(EC.cancel_date) >='".$month."')) AND
											ep.work_status_ = 1
											ORDER BY e.emp_id";
						//echo $getWPS_Report;exit;
						$getWPS_ReportExe1 = mysqli_query($conn, $getWPS_Report); 
	
						$dataRowCount1 = mysqli_num_rows($getWPS_ReportExe1);
						if($dataRowCount1 > 0){
							$sl_no =1;
							while($row = mysqli_fetch_assoc($getWPS_ReportExe1)){ 
							    //echo "<pre>";print_r($row);exit;
								$emp_id = $row['emp_id'];
								$firstDate = '01/'.$month.'/'.$year;
								$lDate = cal_days_in_month(CAL_GREGORIAN,$month,$year);
								$lastDate = $lDate.'/'.$month.'/'.$year;
								$daysOnLeave = $lDate - $row['no_of_wdays_'];
								if(empty($daysOnLeave)){
									$daysOnLeave = 0;
								}
								
								$overtime_sal_for_curent_month = $row['overtime_sal_for_curent_month'];
								
								if($row['salary_for_this_month'] == 0 && $overtime_sal_for_curent_month == 0){
									$pallowance = $row['allowance'];
								}else{
									$pallowance = $row['allowance'];
								}
								
								//leave salary or bonus
								$leavSalary = $bonus = 0;
								$getLvsalBonusByIdQry = "SELECT * FROM leave_sal_bonus WHERE ref_emp_id='$emp_id' AND month='$month' AND year='$year'";
								$getLvsalBonusByIdQryExe = mysqli_query($conn, $getLvsalBonusByIdQry);
								$data = '';
								
								if(mysqli_num_rows($getLvsalBonusByIdQryExe) > 0){
									$theLvsalBonusData = mysqli_fetch_assoc($getLvsalBonusByIdQryExe);
									$leavSalary = $theLvsalBonusData['leave_Salary'];
									$bonus = $theLvsalBonusData['bonus'];
									$leavSalary = (int)$leavSalary;
								}else{
									$leavSalary = 0;
								}
								
									/*
								if($row['salary_for_this_month'] == 0 && $overtime_sal_for_curent_month == 0){
									$housing_allowance =  $row['housing_allowance']; 
								}else{
									if(empty($row['housing_allowance'])){ 
										$housing_allowance = 0; 
									}else{ 
										$housing_allowance =  $row['housing_allowance']; 
									}
								}*/
								$housing_allowance =  $row['housing_a']; 
								
								
								if($row['salary_for_this_month'] == 0 && $overtime_sal_for_curent_month == 0){
									$vpay = $leavSalary + $row['housing_a'];
								}else{
									
									/*$perDay_conveyance_allowance = $row['conveyance_a']/$total_days;
									$conveyance_allowance = round($perDay_conveyance_allowance * $row['no_of_wdays_']);*/
									
									$vpay =  $pallowance + $row['food_a']+ $row['conveyance_a'] + $row['medical_a'] + $row['housing_a'] + $overtime_sal_for_curent_month + $leavSalary;
									
								}
								
						        
							
							?>
							
								<tr>
									<td><?= $sl_no ?></td>
									<td><?= $row['employee_molid_'] ?></td>
									<td><?= $row['emp_name_'] ?></td>
									<td><?= $row['account_no_'] ?></td>
									
									<td><?= round(($row['salary_for_this_month'] + $vpay)-$row['deduct_loan']) ?></td>
									<td><?php echo (int)$vpay; ?></td>
									
									<td><?= $firstDate ?></td>
									<td><?= $lastDate ?></td>
									<td><?= $daysOnLeave ?></td>
									<td><?php if(empty($row['labor_card_no_'])){ echo "0"; }else{ echo $row['labor_card_no_']; }  ?></td>
									<td><?php if(empty($row['agent_bank_routing_code_'])){ echo "0"; }else{ echo $row['agent_bank_routing_code_']; }  ?></td>
									<td><?php if(empty($row['corporate_mol_estid_'])){ echo "0"; }else{ echo $row['corporate_mol_estid_']; }  ?></td>
									<td><?php if(empty($row['corporate_account_no_'])){ echo "0"; }else{ echo $row['corporate_account_no_']; }  ?></td>
									<td><?= $housing_allowance ?></td>
									<td><?php if(empty($row['conveyance_a'])){ echo "0"; }else{ echo $row['conveyance_a'];  }  ?></td>
									<td><?php if(empty($row['medical_a'])){ echo "0"; }else{ echo $row['medical_a']; }  ?></td>
									<td>0</td>
									<td><?= $overtime_sal_for_curent_month ?></td>
									<td><?= $pallowance + $row['food_a']; ?></td>
									<td><?= $leavSalary ?></td>
									
								</tr><?php
								$sl_no++;
							}
						}
						?>
						
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-12 align-right mt-3">
				<a href="<?php echo WEB_URL; ?>payroll/export_wps_salary_report.php?cmpy=<?= $company ?>&month=<?= $month ?>&year=<?= $year ?>" class="btn btn-primary">Export</a>
			</div>
		</div><?php
		} ?>
	</div>
</div>



<?php
require '../footer.php'

?>
<script  src="<?php echo WEB_URL; ?>assets/js/c_salary_report.js"></script>

    </body>
</html>