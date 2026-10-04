<?php

include('../header.php');


error_reporting(E_ALL);
ini_set('display_errors', 1);

$company = $month = $year = '';
$total_days = 0;"";
if(isset($_GET['cmpy']) && !empty($_GET['cmpy'])){
	$company = $_GET['cmpy'];
}
if(isset($_GET['month']) && isset($_GET['year'])) {
	$month = $_GET['month'];
	$year = $_GET['year'];
}


if(isset($_GET['month']) && !empty($_GET['month']) && isset($_GET['year']) && !empty($_GET['year'])){
	$total_days = cal_days_in_month(CAL_GREGORIAN,$month,$year);
	
}

$work_status = $_SESSION['work_status'];
?>

<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<!--- breadcrumb section --->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Report-1</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Report-1</li>
					</ul>
				</div>
				
			</div>
		</div> 
		
		
		<form name="salary_report_fm" id="generate_salary_fm" method="GET">
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
					<!-- <input type="hidden" name="base_url" id="base_url" value="<?= WEB_URL ?>"> -->
					<button class="btn btn-success btn-block" name="submit" value="submit" type="submit">Go</button>
				</div>    
			</div>
		</form>
		
		<?php
		if(isset($_GET['month']) && isset($_GET['cmpy']) && isset($_GET['year'])){ ?>
		<div class="row">
			<div class="col-auto float-right ml-auto mb-3">
				<div class="btn-group btn-group-sm">
					<a href="<?php echo WEB_URL ?>payroll/report1_pdf.php?cmpy=
					<?= $company ?>&month=<?= $month ?>&year=<?= $year ?>" class="btn btn-white">Download PDF</a>
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
									<th>Occupation</th>
									<th>Basic Salary</th>
									<th>Allowance</th>
									<th>Conveyance Allowance</th>
									<th>Food Allowance</th>
									<th>Medical Allowance</th>
									<th>Housing Allowance</th>
									<th>Total</th>
								</tr>
							</thead>
							<tbody><?php
								
								if(1==2){
									//$getAllEmpSalQry = "SELECT e.*, (SELECT department_name FROM  department_master WHERE dep_id =e.division) as dep_name, (SELECT position_name FROM  position_master WHERE position_id =e.position) as position_name, ea.*, ep.* FROM employee e INNER JOIN employee_attendance ea ON e.emp_id = ea.ref_emp_id INNER JOIN employee_payroll ep ON ea.attendance_id = ep.attendance_id WHERE ea.month='$month' AND ea.year = '$year' ORDER BY e.emp_id";
								}else{
									$getAllEmpSalQry = "SELECT 
											ep.*,e.passport_number
											FROM employee_payroll as ep 
											JOIN employee e ON e.emp_id = ep.emp_id_ 
											WHERE 
											ep.ref_comp_id = '$company' AND 
											ep.month='$month' AND 
											ep.year = '$year'  AND 
											ep.work_status_ = '".$work_status."'
											ORDER BY e.emp_id";
								}

								
								$qryExe1 = mysqli_query($conn, $getAllEmpSalQry); 
								
								$dataRowCount1 = mysqli_num_rows($qryExe1);
								if($dataRowCount1 > 0){ 
									$sl_no =1;
									$total_salary = 0;
									$total_allowence = 0;
									$total_conveyance_a = 0;
									$total_food_a = 0;
									$total_medical_a = 0;
									$total_housing_a = 0;
									$total_total_payable = 0;
									while($row = mysqli_fetch_assoc($qryExe1)){ 
										$housing_a = '';
										$allowence = (float)$row['food_a'] + (float)$row['conveyance_a'] + (float)$row['medical_a'] +(float)$row['housing_a'] +(float)$row['allowance'] ;
									
				                        $month = $row['month'];
										if(strlen($row['month']) == 1){
											$month = '0'.$row['month'];
										}
										if($row['year'].$month >= '202310'){
											$allowence = (float)$row['allowance'];
											$conveyance_a = (float)$row['conveyance_a'];
											$food_a = (float)$row['food_a'];
											$medical_a =  (float)$row['medical_a'];
											$housing_a = (float)$row['housing_a'];
										}else{
											$conveyance_a = '';
											$food_a = '';
											$medical_a =  '';
											//$housing_a = (float)$row['housing_a'];
										}
										
										// Accumulate totals
										$total_salary += (float)$row['salary_'];
										$total_allowence += (float)$allowence;
										$total_conveyance_a += (float)$conveyance_a;
										$total_food_a += (float)$food_a;
										$total_medical_a += (float)$medical_a;
										$total_housing_a += (float)$housing_a;
										$total_total_payable += (int)$row['total_payable'];
										
									?>
										
									
									<tr>
										<td><?= $sl_no ?></td>
										<td><?= $row['emp_code_'] ?></td>
										<td><?= $row['emp_name_'] ?></td>
										<td><?= $row['position_name_'] ?></td>
										<td><?= $row['salary_'] ?></td>
										<td><?= (float)$allowence; ?></td> 
										<td><?= (float)$conveyance_a; ?></td>
										<td><?= (float)$food_a; ?></td>
										<td><?= (float)$medical_a; ?></td>
										<td><?= (float)$housing_a; ?></td>
										<td><?= (int)$row['total_payable'] ?></td>
										<td></td> 
									</tr><?php 
									$sl_no++;
									}
									?>
									<tfoot>
									<tr>
										<th colspan="4">Total</th>
										<th><?= number_format($total_salary, 2) ?></th>
										<th><?= number_format($total_allowence, 2) ?></th>
										<th><?= number_format($total_conveyance_a, 2) ?></th>
										<th><?= number_format($total_food_a, 2) ?></th>
										<th><?= number_format($total_medical_a, 2) ?></th>
										<th><?= number_format($total_housing_a, 2) ?></th>
										<th><?= number_format($total_total_payable, 2) ?></th>
									</tr>
								</tfoot>

						<?php } ?>
								
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

<script>
$(document).ready(function(){
	$('.custom-table').dataTable({
		"bPaginate": false
	});//end
	
	$( "#generate_salary_fm" ).submit(function( e ) {
		//e.preventDefault(); 
		
		var month = $('#month').val();
		var year = $('#year').val();
		var base_url = $('#base_url').val();
		var cmpy = $('#choose_company').val();
		
		if(month  == '' || month === null){
			$('#select2-month-container').css('color','red');
		}else{
			$('#select2-month-container').css('color','');
		}
		
		if(year  == '' || year === null){
			$('#select2-year-container').css('color','red');
		}else{
			$('#select2-year-container').css('color','');
		}
		
		if(month  != '' && month != null && year  != '' && year != null){
			return true;
			//window.location.replace(base_url+'payroll/report1.php?cmpy='+cmpy+'&month='+month+'&year='+year);
		}else{
			return false;
		}
	});	//end
});//end doc
</script>
    </body>
</html>