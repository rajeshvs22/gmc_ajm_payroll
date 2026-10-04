<?php
///Warning: Unknown: Input variables exceeded 1000. To increase the limit change max_input_vars in php.ini. in Unknown on line 0
require '../config.php';

$work_status = $_SESSION['work_status'];
print_r($_POST['net_payable1']);
//print_r($_SESSION);

$getAllEmpSalQry = "
				SELECT e.*, 
				(SELECT department_name FROM  department_master WHERE dep_id =e.division) as dep_name, 
				(SELECT position_name FROM position_master WHERE position_id = e.position) as position_name,
				ea.* 
				FROM employee e 
				INNER JOIN employee_attendance ea ON e.emp_id = ea.ref_emp_id 
				LEFT JOIN employee_cancellation as EC ON EC.ref_emp_id = e.emp_id 
				WHERE 
				(e.employe_status = 0 OR (e.employe_status = 1 AND YEAR(EC.cancel_date) >= ea.year AND MONTH(EC.cancel_date) >=ea.month))
				ORDER BY ea.attendance_id ";
$qryExe = mysqli_query($conn, $getAllEmpSalQry); 
		// Fetch all
$res = mysqli_fetch_all($qryExe, MYSQLI_ASSOC);
							

		
		foreach($res as $row){
			//echo "<pre>"; print_r($row);exit;
			$total_days = cal_days_in_month(CAL_GREGORIAN,$row['month'],$row['year']);	
			$attendance_id = $row['attendance_id'];		
			$salary_for_this_month = $overtime_sal_for_curent_month = $allowance = $total_payable = $deduct_loan = $net_payable = '';
			$getPayrolQry = "SELECT * FROM employee_payroll WHERE attendance_id = '$attendance_id'";
												
			$getPayrolQryExe = mysqli_query($conn, $getPayrolQry); 
			if( mysqli_num_rows($getPayrolQryExe) > 0){ 
				$row1 = mysqli_fetch_assoc($getPayrolQryExe);
				
				$salaryPerDay = $row['salary']/$total_days;
				$salary_for_this_month = round($row['no_of_wdays'] * $salaryPerDay);
				//$salary_for_this_month = $row1['salary_for_this_month'];
				
				
				$overtime_sal_for_curent_month = $row1['overtime_sal_for_curent_month'];
				
				//$allowance = $row1['allowance'];
				
				if($row['no_of_wdays'] > 0){
					$allowance = (int)$row['allowance']/(int)$row['no_of_wdays'];
					$allowance = '';
					
				}else{
					$allowance =0;
				}
				
				
				$total_payable = $row1['total_payable'];
				$deduct_loan = $row1['deduct_loan'];
				$net_payable = $row1['net_payable'];
			}

			$salaryPerDay = $row['salary'] / $total_days;
			
			if(!isset($salary_for_this_month) || empty($salary_for_this_month)){
				
				$salary_for_this_month = round($row['no_of_wdays'] * $salaryPerDay);
				
				//$salary_for_this_month = number_format((float)$salary_for_this_month, 2, '.', '');
			}	
			
			$overtime_sal_for_curent_month = round($row['over_time_hour_rate'] * $row['e_overtime'] );	

			if(!isset($allowance) || empty($allowance)){
					
				//if($salary_for_this_month > 0 && $overtime_sal_for_curent_month > 0){
				if($salary_for_this_month > 0 ){
				
					$perDayAllowance = (int)$row['allowance']/(int)$total_days;
					
					$allowance = round($perDayAllowance * $row['no_of_wdays']);
					//$allowance = number_format((float)$allowance,'2','.','');
				}else{
					$allowance = 0;
				}
			}
			

			if($salary_for_this_month > 0 ){
				$food_allowance = $row['food_allowance'];
			}else{
				$food_allowance = 0;
			}

			if($salary_for_this_month > 0 ){
				$perDay_conveyance_allowance = $row['conveyance_allowance']/$total_days;
				$conveyance_allowance = round($perDay_conveyance_allowance * $row['no_of_wdays']); 
			}else{
				$conveyance_allowance = 0;
			}

			if($salary_for_this_month > 0 ){
				$medical_allowance = $row['medical_allowance'];
			}else{
				$medical_allowance = 0;
			}

			if($salary_for_this_month > 0 ){
				$housing_allowance = $row['housing_allowance'];
			}else{
				$housing_allowance = $row['housing_allowance'];
			}

			$total_payable = round($salary_for_this_month + $overtime_sal_for_curent_month + $allowance + $food_allowance + $conveyance_allowance + $medical_allowance + $housing_allowance); 

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

			if(!isset($deduct_loan) || empty($deduct_loan)){
																	$deduct_loan = '0';
																}

			$net_payable = $total_payable - $deduct_loan; 
			$net_payable = round($net_payable);	
			
			/*******************************************************************************************************/
			
			$company_id	= $row['ref_comp_id'];
			$month 		= $row['month'];
			$year 		= $row['year'];
			$emp_id 	= $row['emp_id'];
			
			// Start employee project name
			$getAllCmpyQry = "SELECT pe.ref_proj_id ,pd.proj_name 
								FROM project_employees as pe 
								JOIN project_details as pd ON pd.proj_id = pe.ref_proj_id	
						WHERE 1=1 AND pe.ref_emp_id = '".$row['emp_id']."' " ; /// AND pe.emp_proj_status ='1'
			$qryExe = mysqli_query($conn, $getAllCmpyQry); 
			$ref_proj_id = '';
			$proj_name = '';
			if(mysqli_num_rows($qryExe) > 0){
													
				while($project_row = mysqli_fetch_row($qryExe)){ 
					$ref_proj_id = $project_row[0];
					$proj_name = $project_row[1];
				}
			} 
			// End
			
			$insertPayrollQry = "INSERT INTO employee_payroll SET 
			ref_comp_id 		= '".$company_id."',
			emp_id_ 			= '".$row['emp_id']."',			
			attendance_id 		= '".$attendance_id."',	
			project_id_ 		= '".$ref_proj_id."',
			proj_name_ 			= '".$proj_name."',
			work_status_ 		= '".$row['work_status']."',		
			emp_code_ 			= '".$row['emp_code']."',
			emp_name_ 			= '".$row['emp_name']."',

			employee_molid_ 	= '".$row['employee_molid']."',
			account_no_ 		= '".$row['account_no']."',
			labor_card_no_ 		= '".$row['labor_card_no']."',
			agent_bank_routing_code_ = '".$row['agent_bank_routing_code']."',
			corporate_mol_estid_ = '".$row['corporate_mol_estid']."',
			corporate_account_no_ = '".$row['corporate_account_no']."',

			position_name_ 		= '".$row['position_name']."',
			dep_name_ 			= '".$row['dep_name']."',
			employe_status_  	= '".$row['employe_status']."',

			no_of_wdays_ 		= '".$row['no_of_wdays']."',
			e_overtime_ 		= '".$row['e_overtime']."',
			salary_ 			= '".$row['salary']."',
			over_time_hour_rate_ ='".$row['over_time_hour_rate']."',
			salary_for_this_month = '".$salary_for_this_month."',
			overtime_sal_for_curent_month = '".$overtime_sal_for_curent_month."',

			allowance 			= '".$allowance."',
			food_a				= '".$food_allowance."',
			conveyance_a		= '".$conveyance_allowance."',
			medical_a			= '".$medical_allowance."',
			housing_a			= '".$housing_allowance."',

			total_payable 		= '".$total_payable."',
			loan_amount_ 		= '".$loan_amount."',
			deduct_loan 		= '".$deduct_loan."',
			net_payable 		= '".$net_payable."',
			month 				= '".$month."', 
			year 				= '".$year."'
			";

			mysqli_query($conn,$insertPayrollQry);
		
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
		
		
		
		
		
		
		
		
		
	
	echo "ssssssssssssssssssssss";
	//echo "<pre>";print_r($_POST);echo '<pre>';exit;
	
	 die();
	



?>