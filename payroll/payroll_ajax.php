<?php
include('../config.php');
//print_r($_POST);
extract($_POST);


if(isset($_POST['action'])){
	if(isset($_POST['action']) && $_POST['action'] == 'delet-salary'){
		$deletAttendanceQry = "DELETE FROM employee_payroll WHERE month = '$month' AND year='$year' AND ref_comp_id='$cmpy'";
		if(mysqli_query($conn, $deletAttendanceQry)){
			$deletDetectLoanQry = "DELETE FROM loan_payment_history WHERE month = '$month' AND year='$year' ";
			mysqli_query($conn, $deletDetectLoanQry);
			echo "4";
		}else{
			echo "5";
		}
		
	}//end
	
	if(isset($_POST['action']) && $_POST['action'] == 'close-salary'){
		$getPayrollQry = "SELECT employee_payroll_close_id FROM employee_payroll_close WHERE month = '$month' AND year='$year' AND company_id='$cmpy'";
		$getPayrollQryExe = mysqli_query($conn, $getPayrollQry); 
		
		if(mysqli_num_rows($getPayrollQryExe) == 0){ 
			$insertPayrollQry = "INSERT INTO employee_payroll_close (company_id, month, year) VALUES ('$cmpy', '$month', '$year')";
			
			mysqli_query($conn,$insertPayrollQry);
			
			
		}else{
			//$updatePayrollQry = "UPDATE employee_payroll_close SET salary_for_this_month = '$salary_for_this_month',overtime_sal_for_curent_month='$overtime_sal_for_curent_month',allowance='$allowance', total_payable = '$total_payable', deduct_loan = '$deduct_loan', net_payable = '$net_payable' WHERE attendance_id = '$attendance_id'";
			
			//mysqli_query($conn,$updatePayrollQry);
			
		}
		
	}//end
}