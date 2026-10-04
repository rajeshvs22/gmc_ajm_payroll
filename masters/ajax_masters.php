<?php
require '../config.php';

//print_r($_POST);
if(isset($_POST)){
	if(isset($_POST['action']) && $_POST['action'] == 'add-department'){
		extract($_POST);
		$status = check_dep_exists($conn, $dep_name);
		if( $status == 1){
			$insertDepQry = "INSERT INTO department_master (department_name, ref_comp_id) VALUES ('$dep_name', '$ref_comp_id')";
			mysqli_query($conn,$insertDepQry) or die(mysqli_error($insertDepQry));
			echo "1";
		}else{
			echo "3";
		}
		
	}
	
	if(isset($_POST['action']) && $_POST['action'] == 'update-department'){
		extract($_POST);
		$checkDepExistsQry = "SELECT dep_id FROM department_master WHERE department_name = '$dep_name_edit' AND dep_id != '$ref_dep_id'";
		$checkDepExistsExe = mysqli_query($conn,$checkDepExistsQry);
		if(mysqli_num_rows($checkDepExistsExe) > 0){
			echo "3";
		}else{
			$updateDep = "UPDATE department_master SET department_name='$dep_name_edit' WHERE dep_id='$ref_dep_id'";
			$updateDepExe = mysqli_query($conn,$updateDep);
			echo "2";
		} 
		
		
	}
	else if(isset($_POST['action']) && $_POST['action'] == 'delet-department'){
		extract($_POST);
		$deletDepQry = "DELETE FROM department_master WHERE dep_id = '$delet_dep_ref_id'";
		if(mysqli_query($conn,$deletDepQry)){
			echo "4";
		}else{
			echo "5";
		}
	}
	
	if(isset($_POST['action']) && $_POST['action'] == 'add-position'){
		extract($_POST);
		$status = check_position_exists($conn, $position_name, $division);
		if( $status == 1){
			$insertPositionQry = "INSERT INTO position_master (position_name, ref_dep_id) VALUES ('$position_name', '$division')";
			mysqli_query($conn,$insertPositionQry);
			echo "1";
		}else{
			echo "3";
		}
		
	}
	
	if(isset($_POST['action']) && $_POST['action'] == 'update-position'){
		extract($_POST);
		$checkPositionExistsQry = "SELECT position_id FROM position_master WHERE position_name = '$position_name_edit' AND position_id != '$ref_position_id'";
		$checkPositionExistsExe = mysqli_query($conn,$checkPositionExistsQry);
		if(mysqli_num_rows($checkPositionExistsExe) > 0){
			echo "3";
		}else{
			$updatePosition = "UPDATE position_master SET position_name='$position_name_edit' WHERE position_id='$ref_position_id'";
			$updatePositionExe = mysqli_query($conn,$updatePosition);
			echo "2";
		} 
		
		
	}
	
	if(isset($_POST['action']) && $_POST['action'] == 'delet-position'){
		extract($_POST);
		$deletPositionQry = "DELETE FROM position_master WHERE position_id = '$delet_position_ref_id'";
		if(mysqli_query($conn,$deletPositionQry)){
			echo "4";
		}else{
			echo "5";
		}
	}
	
	//For company
	if(isset($_POST['action']) && $_POST['action'] == 'add-company'){
		extract($_POST);
		$status = check_company_exists($conn, $company_name);
		if( $status == 1){
			$insertCompanyQry = "INSERT INTO company_master (company_name) VALUES ('$company_name')";
			mysqli_query($conn,$insertCompanyQry) or die(mysqli_error($insertCompanyQry));
			echo "1";
		}else{
			echo "3";
		}
		
	}
	
	if(isset($_POST['action']) && $_POST['action'] == 'update-company'){
		extract($_POST);
		$checkCompanyExistsQry = "SELECT comp_id FROM company_master WHERE company_name = '$company_name_edit' AND comp_id != '$ref_company_id'";
		$checkCompanyExistsExe = mysqli_query($conn,$checkCompanyExistsQry);
		if(mysqli_num_rows($checkCompanyExistsExe) > 0){
			echo "3";
		}else{
			$updateCompany = "UPDATE company_master SET company_name='$company_name_edit' WHERE comp_id='$ref_company_id'";
			$updateCompanyExe = mysqli_query($conn,$updateCompany);
			echo "2";
		} 
		
		
	}
	
	if(isset($_POST['action']) && $_POST['action'] == 'delet-company'){
		extract($_POST);
		$deletCompanyQry = "DELETE FROM company_master WHERE comp_id = '$delet_company_ref_id'";
		if(mysqli_query($conn,$deletCompanyQry)){
			echo "4";
		}else{
			echo "5";
		}
	}
	
}

function check_dep_exists($conn, $value){
	$checkDepExistsQry = "SELECT dep_id FROM department_master WHERE department_name = '$value'";
	$checkDepExistsExe = mysqli_query($conn,$checkDepExistsQry);
	if(mysqli_num_rows($checkDepExistsExe) > 0){ 
		return "3";
	}else{
		return "1";
	}
}

function check_position_exists($conn, $position_name, $division){
	$checkPoisionExistsQry = "SELECT position_id FROM position_master WHERE position_name = '$position_name' AND ref_dep_id = '$division'";
	$checkPoisionExistsExe = mysqli_query($conn,$checkPoisionExistsQry);
	if(!empty($checkPoisionExistsExe) && mysqli_num_rows($checkPoisionExistsExe) > 0){ 
		return "3";
	}else{
		return "1";
	}
}

function check_company_exists($conn, $company_name){
	$checkCompanyExistsQry = "SELECT comp_id FROM company_master WHERE company_name = '$company_name'";
	$checkCompanyExistsExe = mysqli_query($conn,$checkCompanyExistsQry);
	if(mysqli_num_rows($checkCompanyExistsExe) > 0){ 
		return "3";
	}else{
		return "1";
	}
}

?>