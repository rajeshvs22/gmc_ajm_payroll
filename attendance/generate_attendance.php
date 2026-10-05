<?php

include('../header.php');

$company = filter_var($_GET['cmpy'] ?? null, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1)));
$param_month = filter_var($_GET['month'] ?? null, FILTER_VALIDATE_INT, array('options' => array('min_range' => 1, 'max_range' => 12)));
$param_year = filter_var($_GET['year'] ?? null, FILTER_VALIDATE_INT, array('options' => array('min_range' => 2020, 'max_range' => 2050)));
$valid_period = $company !== false && $param_month !== false && $param_year !== false;
$max_working_days = $valid_period ? cal_days_in_month(CAL_GREGORIAN, $param_month, $param_year) : 0;
$attendance_error = '';
$attendance_success = '';

// One JSON field avoids PHP truncating attendance for large employee lists.
if (isset($_POST['attendance_payload'])) {
    $payload = is_string($_POST['attendance_payload']) ? json_decode($_POST['attendance_payload'], true) : null;
    if (!is_array($payload) || json_last_error() !== JSON_ERROR_NONE) {
        $attendance_error = 'Attendance could not be read. Please reload the page and try again.';
    } else {
        foreach ($payload as $field => $value) {
            if (preg_match('/^(no_of_wdays|e_overtime)_\d+$/', $field)) {
                $_POST[$field] = $value;
            }
        }
    }
}

// Validate the entire submission before saving any employee's attendance.
if (isset($_POST['submit']) && $attendance_error === '') {
    $posted_month = filter_var($_POST['month'] ?? null, FILTER_VALIDATE_INT);
    $posted_year = filter_var($_POST['year'] ?? null, FILTER_VALIDATE_INT);
    if (!$valid_period || $posted_month !== $param_month || $posted_year !== $param_year) {
        $attendance_error = 'Please choose a valid company, month and year before saving attendance.';
    } else {
        foreach ($_POST as $field => $days) {
            if (preg_match('/^no_of_wdays_\d+$/', $field)) {
                if (!is_scalar($days) || !is_numeric($days) || !is_finite((float) $days)
                    || (float) $days < 0 || (float) $days > $max_working_days) {
                    $attendance_error = 'Working days must be a number between 0 and ' . $max_working_days . ' for the selected month. Attendance was not saved.';
                    break;
                }
                $overtime_field = str_replace('no_of_wdays_', 'e_overtime_', $field);
                $overtime = $_POST[$overtime_field] ?? '';
                if (!is_scalar($overtime) || (trim((string) $overtime) !== ''
                    && (!is_numeric($overtime) || !is_finite((float) $overtime) || (float) $overtime < 0))) {
                    $attendance_error = 'Overtime must be a non-negative number or left blank. Attendance was not saved.';
                    break;
                }
            }
        }
    }
}


if (isset($_POST['submit']) && $attendance_error === '') {
    $transaction_started = false;
    $statements = array();
    try {
        mysqli_begin_transaction($conn);
        $transaction_started = true;
        $employee_stmt = mysqli_prepare($conn, 'SELECT emp_id FROM employee WHERE ref_comp_id = ? AND work_status = ?');
        $statements[] = $employee_stmt;
        mysqli_stmt_bind_param($employee_stmt, 'is', $company, $work_status);
        mysqli_stmt_execute($employee_stmt);
        $employees = mysqli_stmt_get_result($employee_stmt);

        $find_stmt = mysqli_prepare($conn, 'SELECT attendance_id FROM employee_attendance WHERE ref_emp_id = ? AND month = ? AND year = ?');
        $statements[] = $find_stmt;
        $insert_stmt = mysqli_prepare($conn, 'INSERT INTO employee_attendance (ref_comp_id, ref_emp_id, no_of_wdays, e_overtime, month, year) VALUES (?, ?, ?, ?, ?, ?)');
        $statements[] = $insert_stmt;
        $update_stmt = mysqli_prepare($conn, 'UPDATE employee_attendance SET ref_comp_id = ?, no_of_wdays = ?, e_overtime = ? WHERE ref_emp_id = ? AND month = ? AND year = ?');
        $statements[] = $update_stmt;
        $saved_count = 0;
        while ($employee = mysqli_fetch_assoc($employees)) {
            $emp_id = (int) $employee['emp_id'];
            if (!isset($_POST['no_of_wdays_'.$emp_id])) {
                continue;
            }
            $no_of_wdays = (float) $_POST['no_of_wdays_'.$emp_id];
            // Blank overtime means no overtime, never an empty decimal value.
            $e_overtime = (float) ($_POST['e_overtime_'.$emp_id] ?? 0);
            mysqli_stmt_bind_param($find_stmt, 'iii', $emp_id, $param_month, $param_year);
            mysqli_stmt_execute($find_stmt);
            $existing = mysqli_stmt_get_result($find_stmt);
            $exists = mysqli_num_rows($existing) > 0;
            mysqli_free_result($existing);
            if ($exists) {
                mysqli_stmt_bind_param($update_stmt, 'iddiii', $company, $no_of_wdays, $e_overtime, $emp_id, $param_month, $param_year);
                mysqli_stmt_execute($update_stmt);
            } else {
                mysqli_stmt_bind_param($insert_stmt, 'iiddii', $company, $emp_id, $no_of_wdays, $e_overtime, $param_month, $param_year);
                mysqli_stmt_execute($insert_stmt);
            }
            $saved_count++;
        }
        mysqli_commit($conn);
        $transaction_started = false;
        $attendance_success = 'Attendance saved successfully for ' . $saved_count . ' employees.';
    } catch (Throwable $error) {
        if ($transaction_started) {
            mysqli_rollback($conn);
        }
        error_log('Attendance save failed: ' . $error->getMessage());
        $attendance_error = 'Attendance could not be saved. No changes were saved. Please try again.';
    } finally {
        foreach ($statements as $statement) {
            mysqli_stmt_close($statement);
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
					<h3 class="page-title">Generate Attendance</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Generate Attendance</li>
					</ul>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title mb-0">Choose month of year for attendance</h4>
					</div>
				</div>
			</div>
		</div>
		
		<form name="generate_attendance_fm" id="generate_attendance_fm" method="GET">
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
		if ($attendance_error !== '') { ?>
			<div class="alert alert-danger" role="alert"><?= htmlspecialchars($attendance_error, ENT_QUOTES, 'UTF-8') ?></div>
		<?php }
		if ($attendance_success !== '') { ?>
			<div class="alert alert-success" role="alert"><?= htmlspecialchars($attendance_success, ENT_QUOTES, 'UTF-8') ?></div>
		<?php }
		if($valid_period){
		?>
		<form id="add-sal-form" method="POST">
		<div class="row">
			<div class="col-md-12 text-right mb-3">
				<button class="btn btn-primary submit-btn" type="submit" name="submit">Save All</button>
			</div>
			<div class="col-md-12">
				
					<div class="table-responsive">
						<table class="table table-striped custom-table ">
							<thead>
								<tr>
									<th>Sl.No</th>
									<th>Name</th>
									<th>Code No</th>
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
									$month = $param_month;
									$year = $param_year;
									$working_days = $max_working_days;
									
									$getAttendanceQry = "SELECT * FROM employee_attendance WHERE ref_emp_id='$emp_id' AND month='$month' AND year='$year'";
									
									$qryExe1 = mysqli_query($conn, $getAttendanceQry); 
									if( mysqli_num_rows($qryExe1) > 0){ 
										$row1 = mysqli_fetch_assoc($qryExe1);
										$overtime = $row1['e_overtime'];
										$working_days = $row1['no_of_wdays'];
									}
									if ($attendance_error !== '' && isset($_POST['no_of_wdays_'.$emp_id]) && is_scalar($_POST['no_of_wdays_'.$emp_id])) {
										$working_days = $_POST['no_of_wdays_'.$emp_id];
									}
									if ($attendance_error !== '' && isset($_POST['e_overtime_'.$emp_id]) && is_scalar($_POST['e_overtime_'.$emp_id])) {
										$overtime = $_POST['e_overtime_'.$emp_id];
									}
									?>
									<tr>
										<td><?= $sl_no ?></td>
										<td><?= $row['emp_name'] ?></td>
										<td><?= $row['emp_code'] ?></td>
										<td><?= $row['position_name'] ?></td>
										<td>
											<div class="form-group">
												<input type="number" class="form-control attendance-working-days" name="no_of_wdays_<?= $row['emp_id']?>" id="no_of_wdays_<?= $row['emp_id']?>" min="0" max="<?= $max_working_days ?>" step="any" required value="<?= htmlspecialchars((string) $working_days, ENT_QUOTES, 'UTF-8') ?>">
											</div>
										</td>
										<td>
											<div class="form-group">
												<input type="text" class="form-control" name="e_overtime_<?= $row['emp_id']?>" id="e_overtime_<?= $row['emp_id']?>" value="<?= htmlspecialchars((string) $overtime, ENT_QUOTES, 'UTF-8') ?>">
											</div>
										</td>
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
					</div><?php
					if($dataRowCount > 0){ ?>
						<div class="submit-section">
							<input type="hidden" value="<?= $param_month ?>" name="month">
							<input type="hidden" value="<?= $param_year ?>" name="year">
							<button class="btn btn-primary submit-btn" type="submit" name="submit">Save All</button>
						</div><?php
					} ?>
				
			</div>
		</div>
		</form><?php
		} ?>
		
	</div>
</div>

<span class="loader" style="display: none;"></span>

<?php
require '../footer.php'

?>

<script src="<?= WEB_URL ?>assets/js/c_generate_attendance.js?v=<?= filemtime(__DIR__ . '/../assets/js/c_generate_attendance.js') ?>"></script>

<script>
$(document).ready(function(){
	$('.custom-table').dataTable({
		"bPaginate": false
	});
});
</script>
    </body>
</html>
