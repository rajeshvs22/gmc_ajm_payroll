<?php

include('../header.php');



$getAllEmployes = "SELECT emp_id, salary FROM employee";
$result = mysqli_query($conn,$getAllEmployes);
while($row = mysqli_fetch_assoc($result)){
	$salary = $row['salary'];
	$over_time_hour_rate = (($salary/30) / 9) * 1.3;
	$over_time_hour_rate = number_format((float)$over_time_hour_rate, '2', '.', '');
	$updateHrRt = "UPDATE employee SET over_time_hour_rate=".$over_time_hour_rate." WHERE emp_id=".$row['emp_id'];
	mysqli_query($conn,$updateHrRt);
}

require '../footer.php'

?>