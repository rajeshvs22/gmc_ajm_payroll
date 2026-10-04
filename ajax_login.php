<?php
include('config.php');
extract($_POST);
$loginQry = "SELECT * FROM users WHERE username='$uname' AND password='$pswd'";
$qryExe = mysqli_query($conn, $loginQry); 
if(mysqli_num_rows($qryExe) > 0){ 
	$row = mysqli_fetch_assoc($qryExe);
	$_SESSION['logged_in'] = 1;
	$details = array(
				'uid' => $row['uid'],
				'full_name' => $row['full_name'],
				'username' => $row['username'],
				'password' => $row['password'],
				'role' => $row['role'],
				'company_id' => $row['ref_comp_id']
			);
	$_SESSION['user'] = $details;
	$_SESSION['work_status'] = 1;
	echo "1";
}else{
	$_SESSION['logged_in'] = 0;
	echo "0";
}



?>