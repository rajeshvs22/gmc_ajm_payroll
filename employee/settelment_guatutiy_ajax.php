<?php
include('../config.php');

$comp = $_POST['comp_id'];

if($_POST['action'] == 'get_employees'){
	$q = mysqli_query($conn, "SELECT emp_id, emp_name, emp_code FROM employee WHERE ref_comp_id='$comp' AND work_status=1");

	echo '<option value="">Select Employee</option>';

	while ($e = mysqli_fetch_assoc($q)) {
		echo '<option value="'.$e['emp_id'].'">'.$e['emp_name'].' - '.$e['emp_code'].'</option>';
	}
	exit;
}	

if($_POST['action'] == 'get_employee_details'){
	$emp = $_POST['emp_id'];

	$q = mysqli_query($conn, "SELECT salary, joining_date FROM employee WHERE emp_id='$emp' and employe_status=0");
	$d = mysqli_fetch_assoc($q);
	if($d && $d['joining_date']){
		$d['joining_date'] = date('d/m/Y', strtotime($d['joining_date']));
	}

	echo json_encode($d);
	exit;
}

if($_POST['action'] == 'calculate_gratuity'){
    $emp_id = $_POST['emp_id'];
    $cancel_date_raw = $_POST['cancel_date'];

    // Convert DD/MM/YYYY to YYYY-MM-DD for PHP
    $cancel_date = date("Y-m-d", strtotime(str_replace('/', '-', $cancel_date_raw)));

    // Fetch employee data
    $emp = mysqli_fetch_assoc(mysqli_query($conn, "SELECT salary, joining_date FROM employee WHERE emp_id='$emp_id'"));

    $salary = (float)$emp['salary'];
    $joining_date = $emp['joining_date']; // already in Y-m-d format

    // Validate dates
    if(strtotime($cancel_date) < strtotime($joining_date)){
        echo json_encode([
            'status' => 0,
            'message' => 'Last working day must be after joining date.'
        ]);
        exit;
    }

    // --- Calculate total service in days ---
    $start_ts = strtotime($joining_date);
    $end_ts = strtotime($cancel_date);

    $total_days = floor(($end_ts - $start_ts) / 86400);  // Difference in days

    // Convert to full years + remaining days
    $years = floor($total_days / 365);
    $extra_days = $total_days - ($years * 365);

    $service_period = "{$years} years, {$extra_days} days";

    // Daily rate
    $daily_rate = $salary / 30;

    // First 5 years → 21 days per year
    $first5_years = min($years, 5);
    $first5_amount = $first5_years * 21 * $daily_rate;

    // Years after 5 → 30 days per year
    $after5_years = max(0, $years - 5);
    $after5_amount = $after5_years * 30 * $daily_rate;

    // Extra days calculation (your formula keeps 0.083 multiplier)
    $extra_amount = $extra_days * $daily_rate * 0.083;

    // Total gratuity
    $total_amount = round($first5_amount + $after5_amount + $extra_amount, 2);

    echo json_encode([
        'status' => 1,
        'amount' => number_format($total_amount, 2, '.', ''),
        'service' => $service_period,
        'years' => $years,
        'extradays' => $extra_days,
        'first5' => number_format($first5_amount, 2, '.', ''),
        'after5' => number_format($after5_amount, 2, '.', ''),
        'extradays_amount' => number_format($extra_amount, 2, '.', ''),
        'daily_rate' => number_format($daily_rate, 4, '.', '')
    ]);
    exit;
}


if($_POST['action'] == 'save_gratuity'){
	$company_id = $_POST['company_id'];
	$emp_id = $_POST['emp_id'];
	$cancel_date = date("Y-m-d", strtotime(str_replace('/', '-', $_POST['cancel_date'])));
	$gratuity_amount = $_POST['gratuity_amount'];

	// Check if gratuity already exists for this employee using prepared statement
	$check_stmt = mysqli_prepare($conn, "SELECT id FROM employee_gratuity WHERE employee_id=? AND company_id=?");
	mysqli_stmt_bind_param($check_stmt, "ii", $emp_id, $company_id);
	mysqli_stmt_execute($check_stmt);
	$check_result = mysqli_stmt_get_result($check_stmt);
	
	if(mysqli_num_rows($check_result) > 0){
		echo json_encode(['status' => 0, 'message' => 'Gratuity already exists for this employee.']);
		exit;
	}


	// Insert using prepared statement
	$insert_stmt = mysqli_prepare($conn, "INSERT INTO employee_gratuity (company_id, employee_id, last_day, guatutiy_amount, created_date) VALUES (?, ?, ?, ?, NOW())");
	mysqli_stmt_bind_param($insert_stmt, "iisd", $company_id, $emp_id, $cancel_date, $gratuity_amount);
	
	
	if(mysqli_stmt_execute($insert_stmt)){
		echo json_encode(['status' => 1, 'message' => 'Gratuity saved successfully.']);
	} else {
		echo json_encode(['status' => 0, 'message' => 'Error saving gratuity.']);
	}
	exit;
}

if($_POST['action'] == 'delete_gratuity'){
	$guatuity_id = $_POST['guatuity_id'];

	$delete_stmt = mysqli_prepare($conn, "DELETE FROM employee_gratuity WHERE id=?");
	mysqli_stmt_bind_param($delete_stmt, "i", $guatuity_id);

	if(mysqli_stmt_execute($delete_stmt)){
		echo json_encode(['status' => 1, 'message' => 'Gratuity record deleted successfully.']);
	} else {
		echo json_encode(['status' => 0, 'message' => 'Error deleting gratuity record.']);
	}
	exit;
}


?>