<?php
require '../config.php';

//print_r($_POST);

if(isset($_POST)){
	if(isset($_POST['action']) && $_POST['action'] == 'add-expense'){
		extract($_POST);
		$status = check_expense_exists($conn, $expense_name);
		if( $status == 1){
			$insertExpenseQry = "INSERT INTO expense_type_master (expense_name) VALUES ('$expense_name')";
			mysqli_query($conn,$insertExpenseQry) or die(mysqli_error($insertExpenseQry));
			echo "1";
		}else{
			echo "3";
		}
		
	}
	
	if(isset($_POST['action']) && $_POST['action'] == 'update-expense'){
		extract($_POST);
		$checkExpenseTypeExistsQry = "SELECT expense_type_id FROM expense_type_master WHERE expense_name = '$expense_name_edit' AND expense_type_id  != '$ref_expense_id'";
		$checkExpenseTypeExistsQryExe = mysqli_query($conn,$checkExpenseTypeExistsQry);
		if(mysqli_num_rows($checkExpenseTypeExistsQryExe) > 0){
			echo "3";
		}else{
			$updateExpenseType = "UPDATE expense_type_master SET expense_name='$expense_name_edit' WHERE expense_type_id ='$ref_expense_id'";
			$updateExpenseTypeExe = mysqli_query($conn,$updateExpenseType);
			echo "2";
		} 
		
		
	}
	
	if(isset($_POST['action']) && $_POST['action'] == 'delet-expense'){
		extract($_POST);
		$deletExpenseQry = "DELETE FROM expense_type_master WHERE expense_type_id = '$delet_expense_ref_id'";
		if(mysqli_query($conn,$deletExpenseQry)){
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
		if(isset($_POST['ref_dep_id']) && !empty($_POST['ref_dep_id'])){
			$ref_dep_id = $_POST['ref_dep_id'];
		}
		if(isset($_POST['position']) && !empty($_POST['position'])){
			$position = $_POST['position'];
		}
		
		$getEmployeesQry = "SELECT * FROM employee WHERE work_status = ".$_SESSION['work_status']." AND ref_comp_id = ".$ref_company." AND employe_status = 0 ORDER BY emp_name";
		
		$getEmployeesQryExe = mysqli_query($conn,$getEmployeesQry); ?>
		<option selected disabled>Select Employee *</option><?php
		if(mysqli_num_rows($getEmployeesQryExe) > 0){
			while($theEmpData = mysqli_fetch_assoc($getEmployeesQryExe)){ ?>
				<option value="<?= $theEmpData['emp_id']; ?>"><?= $theEmpData['emp_name']." - ".$theEmpData['emp_code'] ?></option><?php
			}
		}else{
			echo "0";
		}
	}
	if($_POST['action'] == 'delet-expense-dets'){
		extract($_POST);
		$deletExpenseQry = "DELETE FROM employee_expense WHERE expense_id  = '$delet_expense_ref_id'";
		if(mysqli_query($conn,$deletExpenseQry)){
			echo "4";
		}else{
			echo "5";
		}
	}
}

function check_expense_exists($conn, $expense_name){
	$checkExpenseExistsQry = "SELECT expense_type_id FROM expense_type_master WHERE expense_name = '$expense_name'";
	$checkExpenseExistsQryExe = mysqli_query($conn,$checkExpenseExistsQry);
	if(mysqli_num_rows($checkExpenseExistsQryExe) > 0){ 
		return "3";
	}else{
		return "1";
	}
}

?>