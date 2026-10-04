<?php
require '../config.php';
//print_r($_POST);
if(isset($_POST['operation']) && !empty($_POST['operation'])){
		
	if($_POST['operation'] == 'get_position_by_division'){
		$ref_division_id = $_POST['ref_division_id'];
		$getPositionQry = "SELECT * FROM position_master WHERE ref_dep_id = ".$ref_division_id;
		//echo $getPositionQry;
		$getPositionQryExe = mysqli_query($conn,$getPositionQry); ?>
		<option selected disabled>Select Position *</option><?php
		if(mysqli_num_rows($getPositionQryExe) > 0){
			while($thePositionData = mysqli_fetch_assoc($getPositionQryExe)){ ?>
				<option value="<?= $thePositionData['position_id'] ?>"><?= $thePositionData['position_name'] ?></option><?php
			}
		}
	}
	if($_POST['operation'] == 'get_employees'){
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
	
	if($_POST['operation'] == 'is_loan_already'){
		$loan_date = $_POST['loan_date'];
		$loan_date = str_replace('/', '-', $loan_date);  
		$loan_date = date("Y/m/d", strtotime($loan_date)); 
		
		$condition="";
		if(isset($_POST['ref_loan_id'])){
			$condition = " AND loan_id !=".$_POST['ref_loan_id'];
		}
		
		$getEmployeesQry = "SELECT ld.loan_id,e.emp_id FROM loan_details ld INNER JOIN employee e ON ld.ref_emp_id = e.emp_id WHERE e.work_status = ".$_SESSION['work_status']." AND ld.ref_emp_id =".$_POST['ref_emp_id']." AND ld.loan_type=".$_POST['loan_type']." AND ld.loan_date='".$loan_date."' ".$condition;
		
		$getEmployeesQryExe = mysqli_query($conn,$getEmployeesQry);
		if(mysqli_num_rows($getEmployeesQryExe) > 0){
			$theEmpData = mysqli_fetch_assoc($getEmployeesQryExe) ?>
			<p class="edit-existing-loan">This loan configuration already exists. please <a href="<?php echo WEB_URL; ?>/loan/add_loan.php?loan_id=<?= $theEmpData['loan_id'] ?>">click here</a> to edit</p><?php
		}
	}
	
	if($_POST['operation'] == 'delet-loan'){
		extract($_POST);
		$deletLoanQry = "DELETE FROM loan_details WHERE loan_id  = '$delet_loan_ref_id'";
		if(mysqli_query($conn,$deletLoanQry)){
			echo "4";
		}else{
			echo "5";
		}
	}
}


?>