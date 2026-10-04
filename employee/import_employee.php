<?php
//https://ajmcrm.livenettech.in/employee/import_employee.php
include('../header.php');
// Check if file exists before requiring
if (!file_exists('../includes/SimpleXLSX.php')) {
	die('SimpleXLSX.php not found. Please install the library first.');
}
require_once '../includes/SimpleXLSX.php';

// Use the namespaced class if SimpleXLSX uses namespaces
use Shuchkin\SimpleXLSX;
// Show PHP errors
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<!--- breadcrumb section --->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Add Employee</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Add Employee</li>
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
					<?php

									if(isset($_POST['submit']) && isset($_FILES['excel_file'])) {
										$file = $_FILES['excel_file']['tmp_name'];
										
										try {
											$xlsx = SimpleXLSX::parse($file);
											
											if(!$xlsx) {
												die('Error parsing file: ' . SimpleXLSX::parseError());
											}
											
											$rows = $xlsx->rows();
											
											
											// Skip header row
											foreach($rows as $i => $row) {
												if($i == 0) {
													continue;
												}
												$employee_code = mysqli_real_escape_string($conn, trim($row[1]));
												$company_id = mysqli_real_escape_string($conn, trim($row[4]));


												$joining_date = '';												
												if(!empty($row[3]) && $employee_code != '' && $company_id != '') {
													$joining_date_temp =  trim($row[3]);
													$joining_date_temp = str_replace('/', '-', $joining_date_temp);  
													$joining_date = date("Y-m-d", strtotime($joining_date_temp)); 
													//echo $joining_date;exit;
												
													// Check if employee exists
													$checkQry = "SELECT emp_id FROM employee WHERE emp_code = '$employee_code' AND ref_comp_id = '$company_id'	";
													$result = mysqli_query($conn, $checkQry);
													//echo $checkQry;exit;
													if(mysqli_num_rows($result) > 0) {
														$row = mysqli_fetch_assoc($result);
														$updateQry = "UPDATE employee SET joining_date = '$joining_date' WHERE emp_id = ".$row['emp_id'];
														//echo $updateQry;exit;
														mysqli_query($conn, $updateQry) or die(mysqli_error($conn));
														echo "Updated Employee Code: $employee_code with Joining Date: ".date('d-m-Y', strtotime($joining_date))." <br>";
													}else{
														echo "Employee Code: $employee_code not found. Skipping...<br>";
													}
												}
											}
											echo "Data imported successfully!";
											
										} catch(Exception $e) {
											die('Error loading file: ' . $e->getMessage());
										}
									}
									?>
					<form method="post" enctype="multipart/form-data">
					<div class="card-body">
						<form method="post" enctype="multipart/form-data">
							<div class="form-group">
								<input type="file" name="excel_file" class="form-control" accept=".xlsx,.xls" required>
							</div>
							<button type="submit" name="submit" class="btn btn-primary">Import</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php require '../footer.php'; ?>
