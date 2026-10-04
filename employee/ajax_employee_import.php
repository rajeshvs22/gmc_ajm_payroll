<?php
require '../config.php';
require '../libraries/simplexlsx-master/src/SimpleXLSX.php';

$excelMimes = array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
if(!empty($_FILES['emp_list']['name']) && in_array($_FILES['emp_list']['type'], $excelMimes)){
	$xlsx = SimpleXLSX::parse($_FILES['emp_list']['tmp_name']);
	$dim = $xlsx->dimension();
	$cols = $dim[0];
	$counter = 0;
	$emp_id = $ref_comp_id = $emp_name = $father_name = $emp_code = $emp_dob = $emp_gender = $maritial_status = $emp_email = $emp_mobile = $e_nationality = $city = $passport_number = $passport_expiry = $visa_number = $visa_expiry = $emirates_id = $emirates_id_expiry = $division = $position = $salary = $duty_type = $over_time_hour_rate = $allowance = $home_address = $home_phone = $employee_photo = $passport_copy = $visa_copy = $emirates_id_copy = $employe_status = $food_allowance = $conveyance_allowance = '';
	
	foreach ( $xlsx->rows() as $k => $row ) {
		if($counter > 0){
			
			$ref_comp_id = isset($row['1']) ? $row['1'] : '';
			$work_status = isset($row['2']) ? $row['2'] : '';
			$emp_name = isset($row['3']) ? $row['3'] : '';
			$father_name = isset($row['4']) ? $row['4'] : '';
			$emp_code = isset($row['5']) ? $row['5'] : '';
			$emp_dob = isset($row['6']) ? date('Y/m/d',strtotime($row['6'])) : '0000-00-00';
			$emp_gender = isset($row['7']) ? $row['7'] : '';
			$maritial_status = isset($row['8']) ? $row['8'] : '';
			$emp_email = isset($row['9']) ? $row['9'] : '';
			$emp_mobile = isset($row['10']) ? $row['10'] : '';
			$e_nationality = isset($row['11']) ? $row['11'] : '';
			$city = isset($row['12']) ? $row['12'] : '';
			$passport_number = isset($row['13']) ? $row['13'] : '';
			
			$passport_expiry = isset($row['14']) ? date('Y-m-d',strtotime($row['14'])) : '0000-00-00';
			$visa_number = isset($row['15']) ? $row['15'] : '';
			
			$visa_expiry = isset($row['16']) ? date('Y-m-d',strtotime($row['16'])) : '0000-00-00';
			$emirates_id = isset($row['17']) ? $row['17'] : '';
			
			$emirates_id_expiry = isset($row['18']) ? date('Y-m-d',strtotime($row['18'])) : '0000-00-00';
			
			
			$division = isset($row['19']) ? $row['19'] : '';
			$position = isset($row['20']) ? $row['20'] : '';
			$salary = isset($row['21']) ? $row['21'] : '0';
			$duty_type = isset($row['22']) ? $row['22'] : '';
			
			$over_time_hour_rate = (($salary/30) / 9) * 1.3;
			$over_time_hour_rate =number_format((float)$over_time_hour_rate, 2, '.', '');
			//$over_time_hour_rate = number_format((float)$over_time_hour_rate, 2, '.', '');
			
			$allowance = isset($row['24']) ? $row['24'] : '';
			$home_address = isset($row['25']) ? $row['25'] : '';
			$home_phone = isset($row['26']) ? $row['26'] : '';
			$employee_photo = isset($row['27']) ? $row['27'] : '';
			$passport_copy = isset($row['28']) ? $row['28'] : '';
			$visa_copy = isset($row['29']) ? $row['29'] : '';
			$emirates_id_copy = isset($row['30']) ? $row['30'] : '';
			$employe_status = isset($row['31']) ? $row['31'] : '';
						
			$food_allowance = isset($row['32']) ? $row['32'] : '';
			$conveyance_allowance = isset($row['33']) ? $row['33'] : '';
			
			$medical_allowance = isset($row['34']) ? $row['34'] : '';
			$housing_allowance = isset($row['35']) ? $row['35'] : '';
			
			
			$employee_molid = isset($row['36']) ? $row['36'] : '';
			$labor_card_no = isset($row['37']) ? $row['37'] : '';
			$account_no = isset($row['38']) ? $row['38'] : '';
			$corporate_account_no = isset($row['39']) ? $row['39'] : '';
			$agent_bank_routing_code = isset($row['40']) ? $row['40'] : '';
			$corporate_mol_estid = isset($row['41']) ? $row['41'] : '';
			
			$checkEmployeeExistsQry = "SELECT emp_id FROM employee WHERE emp_code = '$emp_code'";
			$checkEmployeeExistsExe = mysqli_query($conn,$checkEmployeeExistsQry);
			if(!empty($checkEmployeeExistsExe) && mysqli_num_rows($checkEmployeeExistsExe) > 0){ 
				$updateEmployeeQry = "UPDATE employee SET ref_comp_id = '$ref_comp_id', work_status = '$work_status', emp_name = '$emp_name', father_name = '$father_name', emp_code = '$emp_code', emp_dob = '$emp_dob', emp_gender = '$emp_gender', maritial_status = '$maritial_status', emp_email = '$emp_email', emp_mobile = '$emp_mobile', e_nationality = '$e_nationality', city = '$city', passport_number ='$passport_number', passport_expiry = '$passport_expiry', visa_number = '$visa_number', visa_expiry = '$visa_expiry', emirates_id = '$emirates_id', emirates_id_expiry = '$emirates_id_expiry', division = '$division', position = '$position', salary = '$salary', duty_type = '$duty_type', over_time_hour_rate = '$over_time_hour_rate', allowance = '$allowance', home_address = '$home_address', home_phone = '$home_phone', employee_photo = '$employee_photo', passport_copy = '$passport_copy', visa_copy = '$visa_copy', emirates_id_copy = '$emirates_id_copy', employe_status = '$employe_status', food_allowance = '$food_allowance', conveyance_allowance = '$conveyance_allowance', medical_allowance = '$medical_allowance', housing_allowance = '$housing_allowance', employee_molid = '$employee_molid', labor_card_no = '$labor_card_no', account_no = '$account_no', corporate_account_no ='$corporate_account_no', agent_bank_routing_code ='$agent_bank_routing_code', corporate_mol_estid = '$corporate_mol_estid' WHERE emp_code = '$emp_code'";
				mysqli_query($conn,$updateEmployeeQry) or die(mysqli_error($updateEmployeeQry));
			}else{
				$insertEmployeeQry = "INSERT INTO employee (ref_comp_id, work_status, emp_name, father_name, emp_code, emp_dob, emp_gender, maritial_status, emp_email, emp_mobile, e_nationality, city, passport_number, passport_expiry, visa_number, visa_expiry, emirates_id, emirates_id_expiry, division, position, salary, duty_type, over_time_hour_rate, allowance, home_address, home_phone, employee_photo, passport_copy, visa_copy, emirates_id_copy, employe_status, food_allowance, conveyance_allowance, medical_allowance, housing_allowance, employee_molid, labor_card_no, account_no, corporate_account_no, agent_bank_routing_code, corporate_mol_estid ) VALUES ('$ref_comp_id' , '$work_status', '$emp_name', '$father_name', '$emp_code', '$emp_dob', '$emp_gender', '$maritial_status', '$emp_email', '$emp_mobile', '$e_nationality', '$city', '$passport_number', '$passport_expiry', '$visa_number', '$visa_expiry', '$emirates_id', '$emirates_id_expiry', '$division', '$position', '$salary', '$duty_type', '$over_time_hour_rate', '$allowance', '$home_address', '$home_phone', '$employee_photo', '$passport_copy', '$visa_copy', '$emirates_id_copy', '$employe_status', '$food_allowance','$conveyance_allowance', '$medical_allowance', '$housing_allowance', '$employee_molid', '$labor_card_no', '$account_no', '$corporate_account_no', '$agent_bank_routing_code', '$corporate_mol_estid')";
				mysqli_query($conn,$insertEmployeeQry) or die(mysqli_error($insertEmployeeQry));
			}
		}
		$counter++;
	}
	echo "1";
}else{
	echo "2";
}

?>