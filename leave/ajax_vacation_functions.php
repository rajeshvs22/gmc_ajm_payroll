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
	
	if($_POST['operation'] == 'is_leave_already'){
		$leave_start_dt = $_POST['leave_start_dt'];
		$leave_start_dt = str_replace('/', '-', $leave_start_dt);  
		$leave_start_dt = date("Y/m/d", strtotime($leave_start_dt)); 
		
		$leave_end_dt = $_POST['leave_end_dt'];
		$leave_end_dt = str_replace('/', '-', $leave_end_dt);  
		$leave_end_dt = date("Y/m/d", strtotime($leave_end_dt)); 
		
		$condition="";
		if(isset($_POST['ref_lev_id'])){
			$condition = " AND leave_id !=".$_POST['ref_lev_id'];
		}
		$leaveExistCheckQry = "SELECT leave_id FROM vacation_details WHERE ref_emp_id =".$_POST['ref_emp_id']." AND leave_start_dt='".$leave_start_dt."' AND leave_end_dt='".$leave_end_dt."' ".$condition;
		$getLeaveExistCheckExe = mysqli_query($conn,$leaveExistCheckQry);
		if(mysqli_num_rows($getLeaveExistCheckExe) > 0){
			$theLeaveData = mysqli_fetch_assoc($getLeaveExistCheckExe) ?>
			<p class="edit-existing-loan">This loan configuration already exists. please <a href="<?php echo WEB_URL; ?>/leave/add_vacation.php?lev_id=<?= $theLeaveData['leave_id'] ?>">click here</a> to edit</p><?php
		}
	}
	
	
	if($_POST['operation'] == 'delet-loan'){
		extract($_POST);
		$deletLoanQry = "DELETE FROM vacation_details WHERE leave_id  = '$delet_lev_ref_id'";
		if(mysqli_query($conn,$deletLoanQry)){
			echo "4";
		}else{
			echo "5";
		}
	}
}