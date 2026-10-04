<?php
require '../config.php';

if(isset($_POST['operation'])){
	if($_POST['operation'] == 'check_and_get_emp_name_by_id'){
		$getEmpNameQry = "SELECT emp_name FROM employee WHERE emp_id=".$_POST['emp_id']." AND employe_status = 0";
		
		$getEmpNameQryExe = mysqli_query($conn,$getEmpNameQry);
		$theEmpNameData = mysqli_fetch_assoc($getEmpNameQryExe);
		$data['emp_name'] = $theEmpNameData['emp_name'];
		
		$checkEmpMapingQry = "SELECT proj_emp_id FROM project_employees WHERE ref_emp_id=".$_POST['emp_id']." AND emp_proj_status=1";
		$checkEmpMapingQryExe = mysqli_query($conn,$checkEmpMapingQry);
		if(mysqli_num_rows($checkEmpMapingQryExe) > 0){
			$data['emp_proj_status'] = 1;
		}else{
			$data['emp_proj_status'] = 2;
		}
		
		echo json_encode($data);
	}
	
	if($_POST['operation'] == 'insert-project'){
		
		$proj_name = $_POST['proj_name'];
		$proj_loc = $_POST['proj_loc'];
		
		$proj_end_dt = $_POST['proj_end_dt'];
		$proj_end_dt = str_replace('/', '-', $proj_end_dt);  
		$proj_end_dt = date("Y/m/d", strtotime($proj_end_dt)); 
		
		$proj_desc = $_POST['proj_desc'];
		
		$insertProjQry = "INSERT INTO project_details (proj_name, proj_location, proj_desc, proj_end_date) VALUES('$proj_name', '$proj_loc', '$proj_desc', '$proj_end_dt')";
		mysqli_query($conn,$insertProjQry);
		$ref_proj_id = mysqli_insert_id($conn);
		if(isset($_POST['emp_id'])){
			$len = count($_POST['emp_id']);
		
			for($i=0; $i< $len; $i++){
				$emp_id = $_POST['emp_id'][$i];
				
				$join_dt = $_POST['join_dt'][$i];
				$join_dt = str_replace('/', '-', $join_dt);  
				$join_dt = date("Y/m/d", strtotime($join_dt)); 
				
				if(isset($_POST['quit_dt'][$i]) && !empty($_POST['quit_dt'][$i])){
					$quit_dt = $_POST['quit_dt'][$i];
					$quit_dt = str_replace('/', '-', $quit_dt);  
					$quit_dt = date("Y/m/d", strtotime($quit_dt)); 
				}else{
					$quit_dt = '0000-00-00';
				}
				
				$insertProjEmpQry = "INSERT INTO project_employees (ref_emp_id, ref_proj_id,  emp_joined_date, emp_quite_date, emp_proj_status) VALUES('$emp_id', '$ref_proj_id', '$join_dt', '$quit_dt', 1)";
				mysqli_query($conn,$insertProjEmpQry);
			}
		}
		echo WEB_URL.'/projects/add_projects.php?proj_id='.$ref_proj_id;
	}
	
	if($_POST['operation'] == 'update-project'){
		
		$ref_proj_id = $_POST['ref_proj_id'];
		
		$proj_name = $_POST['proj_name'];
		$proj_loc = $_POST['proj_loc'];
		
		$proj_end_dt = $_POST['proj_end_dt'];
		$proj_end_dt = str_replace('/', '-', $proj_end_dt);  
		$proj_end_dt = date("Y/m/d", strtotime($proj_end_dt)); 
		
		$proj_desc = $_POST['proj_desc'];
		$emp_proj_status = 1;
		
		$updateProjQry = "UPDATE project_details SET proj_name='$proj_name', proj_location='$proj_loc', proj_desc='$proj_desc', proj_end_date='$proj_end_dt' WHERE proj_id=".$ref_proj_id;
		mysqli_query($conn,$updateProjQry);
		
		$deletProjQry = "DELETE FROM project_employees WHERE ref_proj_id = '$ref_proj_id'";
		mysqli_query($conn,$deletProjQry);
		
		if(isset($_POST['emp_id'])){
			$len = count($_POST['emp_id']);
		
			for($i=0; $i< $len; $i++){
				$emp_id = $_POST['emp_id'][$i];
				
				$join_dt = $_POST['join_dt'][$i];
				$join_dt = str_replace('/', '-', $join_dt);  
				$join_dt = date("Y/m/d", strtotime($join_dt)); 
				
				
				
				if(isset($_POST['quit_dt'][$i]) && !empty($_POST['quit_dt'][$i])){
					$quit_dt = $_POST['quit_dt'][$i];
					$quit_dt = str_replace('/', '-', $quit_dt);  
					$quit_dt = date("Y/m/d", strtotime($quit_dt)); 
					$emp_proj_status = 2;
				}else{
					$quit_dt = '0000-00-00';
					$emp_proj_status = 1;
				}
				
				$insertProjEmpQry = "INSERT INTO project_employees (ref_emp_id, ref_proj_id,  emp_joined_date, emp_quite_date, emp_proj_status) VALUES('$emp_id', '$ref_proj_id', '$join_dt', '$quit_dt', '$emp_proj_status')";
				mysqli_query($conn,$insertProjEmpQry);
			}
		}
		echo WEB_URL.'/projects/add_projects.php?proj_id='.$ref_proj_id;
	}
	
	if($_POST['operation'] == 'get_proj_dets'){
		$proj_id = $_POST['proj_id'];
		$getProjDetsQry = "SELECT *,(SELECT emp_name FROM employee WHERE emp_id = ref_emp_id) as emp_name FROM project_employees WHERE ref_proj_id=".$proj_id;
		$getProjDetsQryExe = mysqli_query($conn,$getProjDetsQry);
		$theProjDetsData = mysqli_fetch_assoc($getProjDetsQryExe);
		
		$data['emp_id'] = $theProjDetsData['ref_emp_id'];
		$data['emp_name'] = $theProjDetsData['emp_name'];
		$data['emp_joined_date'] = date('d-m-Y',strtotime($theProjDetsData['emp_joined_date']));
		if($theProjDetsData['emp_quite_date'] != '0000-00-00' ){
			$data['emp_quite_date'] = date('d-m-Y',strtotime($theProjDetsData['emp_quite_date']));
		}else{
			$data['emp_quite_date'] = '';
		}
		
		echo json_encode($data);
	}
	if($_POST['operation'] == 'get_proj_dets_for_edit'){
		$proj_id = $_POST['proj_id'];
		$emp_id = $_POST['emp_id'];
		$getProjDetsQry = "SELECT *,(SELECT emp_name FROM employee WHERE emp_id = ref_emp_id) as emp_name FROM project_employees WHERE ref_proj_id=".$proj_id." AND ref_emp_id=".$emp_id;
		$getProjDetsQryExe = mysqli_query($conn,$getProjDetsQry);
		$theProjDetsData = mysqli_fetch_assoc($getProjDetsQryExe);
		
		$data['emp_id'] = $theProjDetsData['ref_emp_id'];
		$data['emp_name'] = $theProjDetsData['emp_name'];
		$data['emp_joined_date'] = date('d-m-Y',strtotime($theProjDetsData['emp_joined_date']));
		if($theProjDetsData['emp_quite_date'] != '0000-00-00' ){
			$data['emp_quite_date'] = date('d-m-Y',strtotime($theProjDetsData['emp_quite_date']));
		}else{
			$data['emp_quite_date'] = '';
		}
		
		echo json_encode($data);
	}
	
}

?>