<?php
require '../config.php';

$work_status = $_SESSION['work_status'];

$company = $month = $year = "";
if(isset($_GET['cmpy']) && !empty($_GET['cmpy'])){
	$company = $_GET['cmpy'];
}
if(isset($_GET['month']) && !empty($_GET['month'])){
	$month = $_GET['month'];
}
if(isset($_GET['year']) && !empty($_GET['year'])){
	$year = $_GET['year'];
}

$total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);

$filename = "WPS_Salary_Report";
$file_ending = "xls";
$sep = "\t";

//header info for browser
header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename.xls");  
header("Pragma: no-cache"); 
header("Expires: 0"); 

echo "Sl.no \tEmployee MOLID \t NAME \t ACCOUNT NO \t SALARY \t VARIABLE PAY \t From Date (dd/mm/yyyy) \t To Date (dd/mm/yyyy) \t Days on Leave \t Labor Card No \t AGENT BANK ROUTING CODE \t Corporate MOL ESTID \t Corporate AccountNo \t Housing Allowance \t Conveyance Allowance \t Medical Allowance \t Annual Passage Allowance \t Overtime Allowances \t All Other Allowances \t Leave Encashment \t";
echo "\n"; 

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
						
$getWPS_ReportExe1 = mysqli_query($conn, $getWPS_Report); 
if(mysqli_num_rows($getWPS_ReportExe1) > 0){
	$sl_no =1;
	$conveyance_allowance = 0;
	while($row = mysqli_fetch_assoc($getWPS_ReportExe1)){
	    
	    $conveyance_allowance = 0;	

		$emp_id = $row['emp_id'];
		$firstDate = '01/'.$month.'/'.$year;
		$lDate = cal_days_in_month(CAL_GREGORIAN,$month,$year);
		$lastDate = $lDate.'/'.$month.'/'.$year;
		$daysOnLeave = $lDate - $row['no_of_wdays_'];
		
		//leave salary or bonus
		$leavSalary = $bonus = $levSal_Bonus = 0;
		$getLvsalBonusByIdQry = "SELECT * FROM leave_sal_bonus WHERE ref_emp_id='$emp_id' AND month='$month' AND year='$year'";
		$getLvsalBonusByIdQryExe = mysqli_query($conn, $getLvsalBonusByIdQry);
		$data = '';
		
		if(mysqli_num_rows($getLvsalBonusByIdQryExe) > 0){
			$theLvsalBonusData = mysqli_fetch_assoc($getLvsalBonusByIdQryExe);
			$leavSalary = $theLvsalBonusData['leave_Salary'];
			$bonus = $theLvsalBonusData['bonus'];
			$levSal_Bonus = (int)$leavSalary;
		}
		
		
		$housing_allowance = $row['housing_a'];
			
		if($row['salary_for_this_month'] == 0 && $row['overtime_sal_for_curent_month'] == 0){
			$pallowance = 0;
			$otSalary = 0;
			$variablePay = $levSal_Bonus;
			
			$salary = round($row['housing_a'] + $row['salary_for_this_month']) - $row['deduct_loan'];
			$variablePay = round($row['housing_a'] + $row['salary_for_this_month']) - $row['deduct_loan'];
			
		}else{
			$pallowance = (int)$row['allowance'] + $row['food_a'];
			$otSalary = $row['overtime_sal_for_curent_month'];
			
			/*$perDay_conveyance_allowance = $row['conveyance_a']/$total_days;
			$conveyance_allowance = round($perDay_conveyance_allowance * $row['no_of_wdays_']);*/
			$conveyance_allowance = $row['conveyance_a'];
			
			$variablePay =  $pallowance + $conveyance_allowance + $row['medical_a'] + $row['housing_a'] + $otSalary + $levSal_Bonus;
			
			$variablePay = (int)$variablePay;
		
			$salary = round($variablePay + $row['salary_for_this_month']) - $row['deduct_loan'];
		}
		
		
		
		
		
		
		
		$employee_molid = !empty($row['employee_molid_'])? $row['employee_molid_']:'0' ;
		$account_no = !empty($row['account_no_'])? (string)$row['account_no_']:'0' ;
		$labor_card_no = !empty($row['labor_card_no_'])? (string)$row['labor_card_no_']:'0' ;
		$corporate_mol_estid = !empty($row['corporate_mol_estid_'])? (string)$row['corporate_mol_estid_']:'0' ;
		$corporate_account_no = !empty($row['corporate_account_no_'])? (string)$row['corporate_account_no_']:'0' ;
		
		
		echo $sl_no."\t".$employee_molid."\t".$row['emp_name_']."\t".$account_no."\t".$salary."\t".$variablePay."\t".$firstDate."\t".$lastDate."\t".$daysOnLeave."\t".$labor_card_no."\t".$row['agent_bank_routing_code_']."\t".$corporate_mol_estid."\t".$row['corporate_account_no_']."\t".$housing_allowance."\t".$conveyance_allowance."\t".$row['medical_a']."\t 0 \t ".$otSalary." \t". $pallowance." \t".$levSal_Bonus." \n";
		$sl_no++;
		
	}
}

?>