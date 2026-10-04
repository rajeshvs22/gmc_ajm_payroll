<?php

require '../config.php';

$work_status = $_SESSION['work_status'];
$company = $_GET['cmpy'];
$month = $_GET['month'];
$year = $_GET['year'];

$getAttendanceQry = "SELECT (SELECT emp_code FROM employee WHERE emp_id = ea.ref_emp_id) as emp_code,(SELECT emp_name FROM employee WHERE emp_id = ea.ref_emp_id) as emp_name, (SELECT department_name FROM department_master WHERE dep_id  = e.division) as department_name, (SELECT position_name FROM position_master WHERE position_id = e.position) as position_name, no_of_wdays, e_overtime FROM employee_attendance ea INNER JOIN employee e ON ref_emp_id = emp_id WHERE e.work_status = '$work_status' AND e.ref_comp_id = '$company' AND ea.month='$month' AND ea.year='$year' AND e.employe_status = 0";

$result = mysqli_query($conn, $getAttendanceQry);
$filename = "employee_attendance";
$file_ending = "xls";
$sep = "\t";
//header info for browser
header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");

$getCompNameQry = "SELECT company_name FROM company_master WHERE comp_id=".$company;
$getCompNameQryExe = mysqli_query($conn, $getCompNameQry);
$theCompNameQryExe = mysqli_fetch_assoc($getCompNameQryExe );

$dateObj   = DateTime::createFromFormat('!m', $month);
$monthName = $dateObj->format('F'); // March
echo $theCompNameQryExe['company_name']."\n";

echo "ATTENDANCE DETAILS - ".$monthName."-".$year;
echo "\n"; 


for ($i = 0; $i < mysqli_num_fields($result); $i++) {
//echo mysqli_fetch_field_direct($result,$i)->name . "\t";
}
echo "EMPLOYEE CODE \t EMPLOYEE NAME \t DEPARTMENT \t POSITION \t NO OF WORKING DAYS \t OVERTIME \t";
echo "\n"; 

while($row = mysqli_fetch_row($result))
{
	$schema_insert = "";
	for($j=0; $j<mysqli_num_fields($result);$j++)
	{
		if(!isset($row[$j]))
			$schema_insert .= "NULL".$sep;
		elseif ($row[$j] != "")
			$schema_insert .= $row[$j].$sep;
		else
			$schema_insert .= "".$sep;
	}
	$schema_insert = str_replace($sep."$", "", $schema_insert);
	$schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
	$schema_insert .= "\t";
	print(trim($schema_insert));
	print "\n";
}