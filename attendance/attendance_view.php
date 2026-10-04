<?php

include('../header.php');


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
					<h3 class="page-title">Attendance View</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Attendance View</li>
					</ul>
				</div>
			</div>
		</div>
		
		<form name="" id="" method="GET">
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
					<button class="btn btn-success btn-block" name="submit" value="submit" type="submit">Go</button>
				</div>    
			</div>
		</form>
		
		
		<?php
		if(isset($_GET['month']) && isset($_GET['cmpy']) && isset($_GET['year'])){
			$param_month = $_GET['month'];
			$param_year = $_GET['year'];
		?>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
						<table class="table table-striped custom-table ">
							<thead>
								<tr>
									<th>Sl.No</th>
									<th>Name</th>
									<th>Employee Code </th>
									<th>Department</th>
									<th>Position </th>
									<th>Number of working days </th>
									<th>Overtime </th>
								</tr>
							</thead>
							<tbody><?php
							$dataRowCount = 0;	
							if(1==2){
								$getAllEmpQry = "SELECT e.*, (SELECT department_name FROM  department_master WHERE dep_id =e.division) as dep_name FROM employee e";
							}else{
								$getAllEmpQry = "SELECT e.*, 
												(SELECT department_name FROM  department_master WHERE dep_id =e.division) as dep_name, 
												(SELECT position_name FROM position_master WHERE position_id = e.position) as position_name 
												FROM employee e 
												LEFT JOIN employee_cancellation as EC ON EC.ref_emp_id = e.emp_id 
												WHERE work_status = '$work_status' AND 
												e.ref_comp_id = '$company' AND 
												(e.employe_status = 0 OR (e.employe_status = 1 AND YEAR(EC.cancel_date) >= '".$param_year."' AND MONTH(EC.cancel_date) >='".$param_month."'))
												ORDER BY e.emp_id";
												
							}
							
							
							$qryExe = mysqli_query($conn, $getAllEmpQry); 
							$dataRowCount = mysqli_num_rows($qryExe);
							if($dataRowCount > 0){ 
								$sl_no = 1;
								while($row = mysqli_fetch_assoc($qryExe)){
									$overtime = "";
									$emp_id = $row['emp_id'];
									$month = $_GET['month'];
									$year = $_GET['year'];
									$working_days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
									
									$getAttendanceQry = "SELECT * FROM employee_attendance WHERE ref_emp_id='$emp_id' AND month='$month' AND year='$year'";
									
									$qryExe1 = mysqli_query($conn, $getAttendanceQry); 
									if( mysqli_num_rows($qryExe1) > 0){ 
										$row1 = mysqli_fetch_assoc($qryExe1);
										$overtime = $row1['e_overtime'];
										$working_days = $row1['no_of_wdays'];
									}
									?>
									<tr>
										<td><?= $sl_no ?></td>
										<td><?= $row['emp_name'] ?></td>
										<td><?= $row['emp_code'] ?></td>
										<td><?= $row['dep_name'] ?></td>
										<td><?= $row['position_name'] ?></td>
										<td><?= $working_days ?></td>
										<td><?= $overtime ?></td>
									</tr><?php
									$sl_no++;
								}
							} else{ ?>
								<tr>
									<td colspan="6"><center>No data found</center></td>
								</tr><?php
							
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