<?php
include('../config.php');
//print_r($_POST);
extract($_POST);

if(isset($_POST['action'])){
	if(isset($_POST['action']) && $_POST['action']== 'insert-salary'){
		
		$insertAttendanceQry = "INSERT INTO employee_attendance(ref_emp_id, no_of_wdays, e_overtime, monthyr) VALUES('$emp_id','$no_of_wdays','$e_overtime','$monthyr')";
		mysqli_query($conn,$insertAttendanceQry) or die(mysqli_error($insertAttendanceQry));
		$attendance_insert_id=mysqli_insert_id($conn);
		if($attendance_insert_id > 0){
			echo "1";//inserted sucessfully
		}
	}
	if(isset($_POST['action']) && $_POST['action'] == 'update-salary'){
		$updateAttendanceQry = "UPDATE employee_attendance SET no_of_wdays='$no_of_wdays',e_overtime='$e_overtime' WHERE ref_emp_id='$emp_id' AND monthyr='$monthyr'";
		mysqli_query($conn,$updateAttendanceQry) or die(mysqli_error($updateAttendanceQry));
		echo "2";
	}
	if(isset($_POST['action']) && $_POST['action'] == 'is-exists'){
		$getAttendanceQry = "SELECT * FROM employee_attendance WHERE ref_emp_id='$emp_id' AND monthyr='$monthyr'";
		
		
		$qryExe = mysqli_query($conn, $getAttendanceQry); 
		if(mysqli_num_rows($qryExe) > 0){ 
			$row = mysqli_fetch_assoc($qryExe);
			//print_r($row);
			echo json_encode($row);
			
		}
	}
	
	if(isset($_POST['action']) && $_POST['action'] == 'delet-attendance'){
		$deletAttendanceQry = "DELETE FROM employee_attendance WHERE month = '$month' AND year='$year' AND ref_comp_id='$cmpy'";
		if(mysqli_query($conn, $deletAttendanceQry)){
			echo "4";
		}else{
			echo "5";
		}
		
	}
}

?>