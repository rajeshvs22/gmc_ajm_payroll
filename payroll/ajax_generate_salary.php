<?php
require '../config.php';

header('Content-Type: application/json; charset=utf-8');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$transaction_started = false;

try {
    if (empty($_SESSION['logged_in'])) {
        http_response_code(401);
        throw new RuntimeException('Please sign in again before saving salary.');
    }
    if (stripos($_SERVER['CONTENT_TYPE'] ?? '', 'application/json') === 0) {
        $_POST = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
    }
    if (!is_array($_POST) || empty($_POST['cmpy']) || empty($_POST['salary_month']) || empty($_POST['salary_year'])) {
        throw new InvalidArgumentException('Company, month and year are required.');
    }
    $company_id = filter_var($_POST['cmpy'], FILTER_VALIDATE_INT);
    $month = filter_var($_POST['salary_month'], FILTER_VALIDATE_INT);
    $year = filter_var($_POST['salary_year'], FILTER_VALIDATE_INT);
    if (!$company_id || $company_id < 1 || !$month || $month < 1 || $month > 12 || !$year || $year < 2020 || $year > 2050) {
        throw new InvalidArgumentException('Invalid company or payroll period.');
    }
    $fields = ['attendance_id', 'emp_id', 'no_of_wdays', 'e_overtime', 'salary', 'over_time_hour_rate',
        'salary_for_this_month', 'overtime_sal_for_curent_month', 'allowance', 'loan_amount',
        'deduct_loan', 'net_payable', 'total_payable', 'work_status', 'emp_code', 'emp_name',
        'employee_molid', 'account_no', 'labor_card_no', 'agent_bank_routing_code', 'corporate_mol_estid',
        'corporate_account_no', 'position_name', 'dept_name', 'employe_status', 'food_allowance',
        'conveyance_allowance', 'medical_allowance', 'housing_allowance'];
    if (empty($_POST['attendance_id']) || !is_array($_POST['attendance_id'])) {
        throw new InvalidArgumentException('No employee salary rows were received.');
    }
    $count = count($_POST['attendance_id']);
    foreach ($fields as $field) {
        if (!isset($_POST[$field]) || !is_array($_POST[$field]) || count($_POST[$field]) !== $count || array_keys($_POST[$field]) !== range(0, $count - 1)) {
            throw new InvalidArgumentException('Incomplete salary data. Refresh the page and try again.');
        }
        foreach ($_POST[$field] as &$value) {
            if (!is_scalar($value)) {
                throw new InvalidArgumentException('Invalid employee salary data.');
            }
            $value = $conn->real_escape_string((string)$value);
        }
        unset($value);
    }
    if (count(array_unique($_POST['attendance_id'])) !== $count) {
        throw new InvalidArgumentException('Duplicate employee attendance rows.');
    }
    $closed = $conn->query("SELECT employee_payroll_close_id FROM employee_payroll_close WHERE company_id='$company_id' AND month='$month' AND year='$year' AND payroll_status=1");
    if ($closed->num_rows > 0) {
        throw new RuntimeException('This payroll period is closed and cannot be saved.');
    }
    $conn->begin_transaction();
    $transaction_started = true;

if(isset($_POST['cmpy']) && isset($_POST['salary_month']) && isset($_POST['salary_year'])){
	
	$company_id = $_POST['cmpy'];
	$month = $_POST['salary_month'];
	$year = $_POST['salary_year'];
	//echo "<pre>";print_r($_POST);echo '<pre>';exit;
	
	
	$count = count($_POST['attendance_id']);
	$deduct_loan = '';
	for($i=0; $i<$count; $i++){
		$no_of_wdays = $_POST['no_of_wdays'][$i];
		$e_overtime = $_POST['e_overtime'][$i];
		$salary = $_POST['salary'][$i];
		$over_time_hour_rate = $_POST['over_time_hour_rate'][$i];
		$salary_for_this_month = $_POST['salary_for_this_month'][$i];
		$salary_for_this_month = number_format((float)$salary_for_this_month, 2, '.', '');
		$overtime_sal_for_curent_month = $_POST['overtime_sal_for_curent_month'][$i];
		
		$allowance = $_POST['allowance'][$i];
		
		
		
		$total_payable = $_POST['total_payable'][$i];
		$loan_amount = $_POST['loan_amount'][$i];
		//print_r($_POST['deduct_loan1']);
		if(isset($_POST['deduct_loan'])){
			$deduct_loan = $_POST['deduct_loan'][$i];
		}
		
		$net_payable = $_POST['net_payable'][$i];
		$emp_id = $_POST['emp_id'][$i];
		$attendance_id = $_POST['attendance_id'][$i];
        $attendance = $conn->query("SELECT attendance_id FROM employee_attendance WHERE attendance_id='$attendance_id' AND ref_emp_id='$emp_id' AND ref_comp_id='$company_id' AND month='$month' AND year='$year'");
        if ($attendance->num_rows !== 1) {
            throw new InvalidArgumentException('Employee attendance does not match this company and payroll period.');
        }


		// Start employee project name
		$getAllCmpyQry = "SELECT pe.ref_proj_id ,pd.proj_name FROM project_employees as pe 
							JOIN project_details as pd ON pd.proj_id = pe.ref_proj_id	
						WHERE 1=1 AND pe.ref_emp_id = '".$emp_id."' AND pe.emp_proj_status ='1' " ;
		$qryExe = mysqli_query($conn, $getAllCmpyQry); 
		$ref_proj_id = 0;
		$proj_name = '';
		if(mysqli_num_rows($qryExe) > 0){
													
			while($project_row = mysqli_fetch_row($qryExe)){ 
				$ref_proj_id = $project_row[0];
				$proj_name = $conn->real_escape_string($project_row[1]);
			}
		} 
		// End

		$getPayrollQry = "SELECT payroll_id FROM employee_payroll WHERE attendance_id = '$attendance_id'";
		$getPayrollQryExe = mysqli_query($conn, $getPayrollQry); 
	
		if(mysqli_num_rows($getPayrollQryExe) == 0){ 
			$insertPayrollQry = "INSERT INTO employee_payroll SET 
			ref_comp_id = '".$company_id."',
			emp_id_ = '".$emp_id."',			
			attendance_id = '".$attendance_id."',	
			project_id_ = '".$ref_proj_id."',
			proj_name_ = '".$proj_name."',
			work_status_ = '".$_POST['work_status'][$i]."',		
			emp_code_ = '".$_POST['emp_code'][$i]."',
			emp_name_ = '".$_POST['emp_name'][$i]."',

			employee_molid_ = '".$_POST['employee_molid'][$i]."',
			account_no_ = '".$_POST['account_no'][$i]."',
			labor_card_no_ = '".$_POST['labor_card_no'][$i]."',
			agent_bank_routing_code_ = '".$_POST['agent_bank_routing_code'][$i]."',
			corporate_mol_estid_ = '".$_POST['corporate_mol_estid'][$i]."',
			corporate_account_no_ = '".$_POST['corporate_account_no'][$i]."',

			position_name_ = '".$_POST['position_name'][$i]."',
			dep_name_ = '".$_POST['dept_name'][$i]."',
			employe_status_  = '".$_POST['employe_status'][$i]."',

			no_of_wdays_ = '".$_POST['no_of_wdays'][$i]."',
			e_overtime_ = '".$_POST['e_overtime'][$i]."',
			salary_ = '".$_POST['salary'][$i]."',
			over_time_hour_rate_ ='".$_POST['over_time_hour_rate'][$i]."',
			salary_for_this_month = '".$salary_for_this_month."',
			overtime_sal_for_curent_month = '".$overtime_sal_for_curent_month."',

			allowance = '".$allowance."',
			food_a	= '".$_POST['food_allowance'][$i]."',
			conveyance_a	= '".$_POST['conveyance_allowance'][$i]."',
			medical_a	= '".$_POST['medical_allowance'][$i]."',
			housing_a	= '".$_POST['housing_allowance'][$i]."',

			total_payable = '".$total_payable."',
			loan_amount_ = '".$loan_amount."',
			deduct_loan = '".$deduct_loan."',
			net_payable = '".$net_payable."',
			month = '".$month."', 
			year = '".$year."'
			";

			mysqli_query($conn,$insertPayrollQry);
			
		}else{
			$updatePayrollQry = "UPDATE employee_payroll SET 
					project_id_ = '".$ref_proj_id."',
					proj_name_ = '".$proj_name."',
					work_status_ = '".$_POST['work_status'][$i]."',		
					emp_code_ = '".$_POST['emp_code'][$i]."',
					emp_name_ = '".$_POST['emp_name'][$i]."',

					employee_molid_ = '".$_POST['employee_molid'][$i]."',
					account_no_ = '".$_POST['account_no'][$i]."',
					labor_card_no_ = '".$_POST['labor_card_no'][$i]."',
					agent_bank_routing_code_ = '".$_POST['agent_bank_routing_code'][$i]."',
					corporate_mol_estid_ = '".$_POST['corporate_mol_estid'][$i]."',
					corporate_account_no_ = '".$_POST['corporate_account_no'][$i]."',

					position_name_ = '".$_POST['position_name'][$i]."',
					dep_name_ = '".$_POST['dept_name'][$i]."',
					employe_status_  = '".$_POST['employe_status'][$i]."',

					no_of_wdays_ = '".$_POST['no_of_wdays'][$i]."',
					e_overtime_ = '".$_POST['e_overtime'][$i]."',
					salary_ = '".$_POST['salary'][$i]."',
					over_time_hour_rate_ ='".$_POST['over_time_hour_rate'][$i]."',
					salary_for_this_month = '".$salary_for_this_month."',
					overtime_sal_for_curent_month = '".$overtime_sal_for_curent_month."',

					allowance = '".$allowance."',
					food_a	= '".$_POST['food_allowance'][$i]."',
					conveyance_a	= '".$_POST['conveyance_allowance'][$i]."',
					medical_a	= '".$_POST['medical_allowance'][$i]."',
					housing_a	= '".$_POST['housing_allowance'][$i]."',

					total_payable = '".$total_payable."',
					loan_amount_ = '".$loan_amount."',
					deduct_loan = '".$deduct_loan."',
					net_payable = '".$net_payable."',
					month = '".$month."', 
					year = '".$year."'
					WHERE attendance_id = '".$attendance_id."'";
			
			
			mysqli_query($conn,$updatePayrollQry);
			
		}
		
		//deduct loan
				
				$empLoanTotalQry = "SELECT sum(loan_amount) as total_loan FROM loan_details WHERE ref_emp_id=".$emp_id;
				$empLoanTotalQryExe = mysqli_query($conn, $empLoanTotalQry); 
				$loan_total = $loan_paid_total = 0;
				if(mysqli_num_rows($empLoanTotalQryExe) > 0){
					$row2 = mysqli_fetch_assoc($empLoanTotalQryExe);
					$loan_total = $row2['total_loan'];
				}
				$empLoanPaidTotalQry = "SELECT sum(paid_amount) as paid_amount FROM loan_payment_history WHERE ref_emp_id=".$emp_id;
				$empLoanPaidTotalQryExe = mysqli_query($conn, $empLoanPaidTotalQry); 
				if(mysqli_num_rows($empLoanPaidTotalQryExe) > 0){
					$row3 = mysqli_fetch_assoc($empLoanPaidTotalQryExe);
					$loan_paid_total = $row3['paid_amount'];
				}
				
				if(($loan_total - $loan_paid_total) > 0){
					$getLoanHistryQry = "SELECT loan_history_id FROM loan_payment_history WHERE ref_emp_id =".$emp_id." AND month=".$month." AND year=".$year;
					$getLoanHistryQryExe = mysqli_query($conn, $getLoanHistryQry); 
					if(mysqli_num_rows($getLoanHistryQryExe) == 0){
						$insertLoanPayment = "INSERT INTO loan_payment_history (ref_emp_id, month, year, paid_amount) VALUES ('$emp_id', '$month', '$year', '$deduct_loan')";
						mysqli_query($conn,$insertLoanPayment);
					}else{
						$updateLoanPayment = "UPDATE loan_payment_history SET paid_amount='$deduct_loan' WHERE ref_emp_id =".$emp_id." AND month=".$month." AND year=".$year;
						mysqli_query($conn,$updateLoanPayment);
					}
				}
	}
	
    $conn->commit();
    $transaction_started = false;
    echo json_encode(['success' => true, 'saved_count' => $count]);
	
}


} catch (Throwable $error) {
    if ($transaction_started) {
        $conn->rollback();
    }
    if (http_response_code() !== 401) {
        http_response_code($error instanceof InvalidArgumentException || $error instanceof JsonException ? 400 : 500);
    }
    error_log('Payroll save failed: ' . $error->getMessage());
    $message = $error instanceof mysqli_sql_exception ? 'Salary could not be saved. No changes were applied. Please check the server error log.' : $error->getMessage();
    echo json_encode(['success' => false, 'message' => $message]);
}
