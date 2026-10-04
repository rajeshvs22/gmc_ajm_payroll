<?php

require '../config.php';

$work_status = $_SESSION['work_status'];

if(isset($_POST['operation'])){
	extract($_POST);
	if($_POST['operation'] == 'save_lv_sal_bonus'){
		$isExistsQry = "SELECT bonus_id FROM leave_sal_bonus WHERE ref_emp_id='$ref_emp_id' AND ref_comp_id='$cmpy' AND month='$month' AND year='$year'";
		
		$isExistsQryExe = mysqli_query($conn, $isExistsQry);
		if(mysqli_num_rows($isExistsQryExe) == 0){
			$insertLvsalBousQry = "INSERT INTO leave_sal_bonus (ref_emp_id, leave_Salary, bonus, ref_comp_id, month, year) VALUES('$ref_emp_id', '$leave_salary','$bonus_amount','$cmpy','$month','$year')";
			
			$insertLvsalBousQryExe = mysqli_query($conn, $insertLvsalBousQry);
			if(mysqli_insert_id($conn) > 0){
				echo "1";
			}
		}else{
			echo "3";
		}
	}
	
	if($_POST['operation'] == 'get_lv_sal_bonus'){
		$getLvsalBonusByIdQry = "SELECT * FROM leave_sal_bonus WHERE bonus_id='$bonus_id'";
		$getLvsalBonusByIdQryExe = mysqli_query($conn, $getLvsalBonusByIdQry);
		$data = '';
		if(mysqli_num_rows($getLvsalBonusByIdQryExe) > 0){
			$theLvsalBonusData = mysqli_fetch_assoc($getLvsalBonusByIdQryExe);
			echo '<pre>';
			print_r($theLvsalBonusData);
			echo '</pre>';
			$data['bonus'] = $theLvsalBonusData["bonus"];
			$data['leave_Salary'] = $theLvsalBonusData["leave_Salary"];
		}
		echo json_encode($data);
	}
	if($_POST['operation'] == 'update_lv_sal_bonus'){
		$updateLvsalBonusQry = "UPDATE leave_sal_bonus SET bonus='$bonus_amount', leave_Salary='$leave_salary' WHERE bonus_id ='$ref_bonus_id'";
		if(mysqli_query($conn, $updateLvsalBonusQry)){
			echo "2";
		}
	}
	
	if($_POST['operation'] == 'delet_levsal_bonus'){
		$deletLvsalBonusQry = "DELETE FROM leave_sal_bonus WHERE bonus_id='$ref_bonus_id'";
		//echo $deletLvsalBonusQry;
		if(mysqli_query($conn,$deletLvsalBonusQry)){
			echo "4";
		}else{
			echo "5";
		}
	}
}
	
?>