<?php

require '../config.php';

$work_status = $_SESSION['work_status'];
$company = $_GET['cmpy'];
$month = $_GET['month'];
$year = $_GET['year'];
$total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

$getSalaryeQry = "SELECT (SELECT emp_code FROM employee WHERE emp_id = ea.ref_emp_id) as emp_code, (SELECT emp_name FROM employee WHERE emp_id = ea.ref_emp_id) as emp_name,(SELECT passport_number FROM employee WHERE emp_id = ea.ref_emp_id) as passport_number,(SELECT position_name FROM position_master WHERE position_id = e.position) as position_name, (SELECT no_of_wdays FROM employee_attendance WHERE attendance_id  = ep.attendance_id) as no_of_wdays,(SELECT e_overtime FROM employee_attendance WHERE attendance_id  = ep.attendance_id) as e_overtime,(SELECT salary FROM employee WHERE emp_id = ea.ref_emp_id) as salary, (SELECT over_time_hour_rate FROM employee WHERE emp_id = ea.ref_emp_id) as over_time_hour_rate, ep.salary_for_this_month, ep.overtime_sal_for_curent_month, ep.allowance, (SELECT food_allowance FROM employee WHERE emp_id = ea.ref_emp_id) as food_allowance, (SELECT conveyance_allowance FROM employee WHERE emp_id = ea.ref_emp_id) as conveyance_allowance, (SELECT medical_allowance FROM employee WHERE emp_id = ea.ref_emp_id) as medical_allowance, (SELECT housing_allowance FROM employee WHERE emp_id = ea.ref_emp_id) as housing_allowance, ep.total_payable, ep.net_payable  FROM employee_payroll ep INNER JOIN employee_attendance ea ON ep.attendance_id = ea.attendance_id INNER JOIN employee e ON e.emp_id = ea.ref_emp_id WHERE e.work_status = '$work_status' AND e.ref_comp_id = '$company' AND ea.month='$month' AND ea.year='$year' AND e.employe_status = 0 ORDER BY e.emp_id";

$result = mysqli_query($conn, $getSalaryeQry);
$filename = "employee_salary";
$file_ending = "xls";
$sep = "\t";
//header info for browser

header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");




echo "SL.NO \t EMPLOYEE CODE \t EMPLOYEE NAME \t POSITION NAME \t NUMBER OF WORKING DAYS \t EMPLOYEE OVERTIME \t SALRAY \t OVERTIME HOUR RATE \t SALARY FOR THIS MONTH \t OVERTIME SALARY FOR THIS MONTH \t ALLOWANCE \t TOTAL PAYABLE \t NET PAYABLE ";


echo "\n"; 
$sl_no = 1;

if (mysqli_num_rows($result) > 0) {
	while($tenantDets = mysqli_fetch_assoc($result)){
		
		if($tenantDets['salary_for_this_month'] == 0 && $tenantDets['overtime_sal_for_curent_month'] == 0){
			$totalAllowance = 0;
		}else{
			$perDay_conveyance_allowance = $tenantDets['conveyance_allowance'] / $total_days;
			$conveyance_allowance = $tenantDets['no_of_wdays'] * $perDay_conveyance_allowance;
			
			$totalAllowance = (float)$tenantDets['allowance'] + (float)$tenantDets['food_allowance'] + (float)$conveyance_allowance + (float)$tenantDets['medical_allowance'] + (float)$tenantDets['housing_allowance'];
		}
		
		echo $sl_no."\t".$tenantDets['emp_code'];
		echo "\t".$tenantDets['emp_name'];
		//echo "\t".$tenantDets['passport_number'];
		echo "\t".$tenantDets['position_name'];
		echo "\t".$tenantDets['no_of_wdays'];
		echo "\t".$tenantDets['e_overtime'];
		echo "\t".$tenantDets['salary'];
		echo "\t".$tenantDets['over_time_hour_rate'];
		echo "\t".(int)$tenantDets['salary_for_this_month'];
		echo "\t".(int)$tenantDets['overtime_sal_for_curent_month'];
		echo "\t".(int)$totalAllowance ;
		echo "\t".(int)$tenantDets['total_payable'];
		echo "\t".(int)$tenantDets['net_payable']."\n";
		$sl_no++;
	}
}

