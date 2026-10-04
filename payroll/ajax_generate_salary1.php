<?php

require '../config.php';

$work_status = $_SESSION['work_status'];
print_r($_POST['net_payable1']);
//print_r($_SESSION);

if(isset($_POST['cmpy']) && isset($_POST['salary_month']) && isset($_POST['salary_year'])){
	
	$company_id = $_POST['cmpy'];
	$month = $_POST['salary_month'];
	$year = $_POST['salary_year'];
	//	echo "<pre>";print_r($_POST);echo '<pre>';exit;
	
	
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

		// Start employee project name
		$getAllCmpyQry = "SELECT pe.ref_proj_id ,pd.proj_name FROM project_employees as pe 
							JOIN project_details as pd ON pd.proj_id = pe.ref_proj_id	
						WHERE 1=1 AND pe.ref_emp_id = '".$emp_id."' AND pe.emp_proj_status ='1' " ;
					
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
					salary_for_this_month = '".$salary_for_this_month."',
					overtime_sal_for_curent_month='".$overtime_sal_for_curent_month."',
					allowance='".$allowance."',
					total_payable = '".$total_payable."',
					deduct_loan = '".$deduct_loan."',
					net_payable = '".$net_payable."'
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
	
	 header('Location: salary_list.php', true);
	 die();
	
}


?>