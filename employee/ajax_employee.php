<?php
require '../config.php';

if(isset($_POST['action'])){
	if($_POST['action'] == 'delet-employee-hard'){
		
		$checkEmpExistsQry = "DELETE FROM employee WHERE emp_id =".$_POST['delet_emp_ref_id'];
		
		if( mysqli_query($conn,$checkEmpExistsQry) ){
			echo "4";
		}else{
			echo "5";
		}
		
		
	}
	
	if($_POST['action'] == 'delet-increment'){
		$inc_id = $_POST['delet_incremnt_id'];
		
		$getDeletedIncQry = "SELECT * FROM salary_increment WHERE increment_id=".$inc_id;
		$getDeletedIncQryExe = mysqli_query($conn, $getDeletedIncQry);
		if(mysqli_num_rows($getDeletedIncQryExe) > 0){
			$theEmpsalary = mysqli_fetch_assoc($getDeletedIncQryExe);
			$increment_amt = $theEmpsalary['increment_amt'];
			$previous_salary = $theEmpsalary['previous_salary'];
			$previous_allowance = $theEmpsalary['previous_allowance'];
			$emp_id = $theEmpsalary['ref_emp_id'];
		}
		
		$new_over_time_rate = (($previous_salary/30) / 9) * 1.3;
		$new_over_time_rate =number_format((float)$new_over_time_rate, 2, '.', '');
		
		$updatesalaryQry = "UPDATE employee SET salary = '$previous_salary', allowance = '$previous_allowance', over_time_hour_rate= '$new_over_time_rate' WHERE emp_id = ".$emp_id;
		mysqli_query($conn,$updatesalaryQry);
		
		$deleteIncQry = "DELETE FROM salary_increment WHERE increment_id =".$inc_id;
		
		if( mysqli_query($conn,$deleteIncQry) ){
			echo "4";
		}else{
			echo "5";
		}
	}
	
	if($_POST['action'] == 'get_employees'){
		$ref_company = $ref_dep_id = $position = 0;
		
		if(isset($_POST['ref_company']) && !empty($_POST['ref_company'])){
			$ref_company = $_POST['ref_company'];
		}
				
		$getEmployeesQry = "SELECT * FROM employee WHERE work_status = ".$_SESSION['work_status']." AND ref_comp_id = ".$ref_company." AND employe_status = 0";
		
		$getEmployeesQryExe = mysqli_query($conn,$getEmployeesQry); ?>
		<option selected disabled>Select Employee *</option><?php
		if(mysqli_num_rows($getEmployeesQryExe) > 0){
			while($theEmpData = mysqli_fetch_assoc($getEmployeesQryExe)){ ?>
				<option value="<?= $theEmpData['emp_id'] ?>"><?= $theEmpData['emp_name']." - ".$theEmpData['emp_code'] ?></option><?php
			}
		}else{
			echo "0";
		}
	}
}


?>