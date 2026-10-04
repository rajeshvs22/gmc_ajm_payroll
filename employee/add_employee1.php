<?php

include('../header.php');

$company = $emp_name = $father_name = $emp_gender = $emp_email = $e_nationality = $passport_number = $visa_number = $emirates_id = $division = $duty_type = $allowance = $home_phone = $emp_code = $emp_dob = $maritial_status = $emp_mobile = $city = $position = $salary = $over_time_hour_rate = $home_address = $employee_photo = $visa_copy = $passport_copy = $emirates_id_copy = $hra = $food_allowance = $conveyance_allowance = $medical_allowance = $housing_allowance = $employee_molid = $labor_card_no = $account_no = $corporate_account_no = $agent_bank_routing_code = $corporate_mol_estid = "";

$work_status = 1;

$passport_expiry = $visa_expiry = $emirates_id_expiry = '0000-00-00';



if(isset($_POST['submit']) ){
	
	$ref_comp_id = $_POST['company'];
	$emp_name = $_POST['emp_name'];
	$father_name = $_POST['father_name'];
	$emp_gender = $_POST['emp_gender'];
	$emp_email = $_POST['emp_email'];
	$e_nationality = $_POST['e_nationality'];
	$passport_number = $_POST['passport_number'];
	$visa_number = $_POST['visa_number'];
	$emirates_id = $_POST['emirates_id'];
	$division = $_POST['division'];
	$duty_type = $_POST['duty_type'];
	$allowance = $_POST['allowance'];
	$home_phone = $_POST['home_phone'];
	$emp_code = $_POST['emp_code'];
	
	if(!empty($_POST['emp_dob'])){
		$emp_dob = $_POST['emp_dob'];
		$emp_dob = str_replace('/', '-', $emp_dob);  
		$emp_dob = date("Y/m/d", strtotime($emp_dob)); 
	}
	
	$maritial_status = $_POST['maritial_status'];
	$emp_mobile = $_POST['emp_mobile'];
	$city = $_POST['city'];
	
	//$hra = $_POST['hra'];
	$food_allowance = $_POST['food_allowance'];
	$conveyance_allowance = $_POST['conveyance_allowance'];
	
	$medical_allowance = $_POST['medical_allowance'];
	$housing_allowance = $_POST['housing_allowance'];
	
	//bank details
	$employee_molid = $_POST['employee_molid'];
	$labor_card_no = $_POST['labor_card_no'];
	$account_no = $_POST['account_no'];
	$corporate_account_no = $_POST['corporate_account_no'];
	$agent_bank_routing_code = $_POST['agent_bank_routing_code'];
	$corporate_mol_estid = $_POST['corporate_mol_estid'];
	
	$work_status = $_POST['work_status'];
	
	
	
	if(!empty($_POST['passport_expiry'])){
		$passport_expiry = $_POST['passport_expiry'];
		$passport_expiry = str_replace('/', '-', $passport_expiry);  
		$passport_expiry = date("Y/m/d", strtotime($passport_expiry)); 
	}
	if(!empty($_POST['visa_expiry'])){
		$visa_expiry = $_POST['visa_expiry'];
		$visa_expiry = str_replace('/', '-', $visa_expiry);  
		$visa_expiry = date("Y/m/d", strtotime($visa_expiry)); 
	}
	if(!empty($_POST['emirates_id_expiry'])){
		$emirates_id_expiry = $_POST['emirates_id_expiry'];
		$emirates_id_expiry = str_replace('/', '-', $emirates_id_expiry);  
		$emirates_id_expiry = date("Y/m/d", strtotime($emirates_id_expiry)); 
	}
	
	$position = $_POST['position'];
	$salary = $_POST['salary'];
	$over_time_hour_rate = $_POST['over_time_hour_rate'];
	$home_address = $_POST['home_address'];
	
	if(!isset($_POST['ref_emp_id'])){
		// employee_photo, visa_copy, passport_copy, emirates_id_copy
		$addEmpQry = "INSERT INTO employee (ref_comp_id,emp_name, father_name, emp_gender, emp_email, e_nationality, passport_number, visa_number, emirates_id, division, duty_type, allowance, home_phone, emp_code, emp_dob,maritial_status, emp_mobile, city, passport_expiry, visa_expiry, emirates_id_expiry, position, salary, over_time_hour_rate, home_address, food_allowance, conveyance_allowance, medical_allowance, housing_allowance, employee_molid, labor_card_no, account_no, corporate_account_no, agent_bank_routing_code, corporate_mol_estid, work_status) VALUES ( $ref_comp_id, '$emp_name', '$father_name', '$emp_gender', '$emp_email', '$e_nationality', '$passport_number', '$visa_number', '$emirates_id', '$division', '$duty_type', '$allowance', '$home_phone', '$emp_code', '$emp_dob', '$maritial_status', '$emp_mobile', '$city', '$passport_expiry', '$visa_expiry', '$emirates_id_expiry', '$position', '$salary', '$over_time_hour_rate', '$home_address', '$food_allowance','$conveyance_allowance', '$medical_allowance','$housing_allowance','$employee_molid', '$labor_card_no', '$account_no', '$corporate_account_no', '$agent_bank_routing_code', '$corporate_mol_estid', $work_status )";
		
		
		mysqli_query($conn,$addEmpQry) or die(mysqli_error($addEmpQry));
		$emp_insert_id=mysqli_insert_id($conn);
		upload_attachment($conn, $emp_insert_id);
		$_SESSION['employee_operation'] = 1;//for insert
		$url = WEB_URL . 'employee/index.php';
		echo '<script>window.location.replace("'.$url.'");</script>';
		exit;
	}
	if(isset($_POST['ref_emp_id'])){
		$updateEmpQry = "UPDATE employee SET ref_comp_id ='$ref_comp_id', emp_name='$emp_name', father_name='$father_name', emp_gender='$emp_gender', emp_email='$emp_email', e_nationality='$e_nationality', passport_number='$passport_number', visa_number='$visa_number', emirates_id='$emirates_id', division='$division', duty_type='$duty_type', allowance='$allowance', home_phone='$home_phone', emp_code='$emp_code', emp_dob='$emp_dob',maritial_status='$maritial_status', emp_mobile='$emp_mobile', city='$city', passport_expiry='$passport_expiry', visa_expiry='$visa_expiry', emirates_id_expiry='$emirates_id_expiry', position='$position', salary='$salary', over_time_hour_rate='$over_time_hour_rate', home_address='$home_address', food_allowance = '$food_allowance', conveyance_allowance = '$conveyance_allowance', medical_allowance = '$medical_allowance', housing_allowance = '$housing_allowance', employee_molid = '$employee_molid', labor_card_no = '$labor_card_no', account_no = '$account_no', corporate_account_no ='$corporate_account_no', agent_bank_routing_code = '$agent_bank_routing_code', corporate_mol_estid = '$corporate_mol_estid', work_status = '$work_status' WHERE emp_id=".$_GET['eid'];
		//echo $updateEmpQry ; die;
		mysqli_query($conn,$updateEmpQry) or die(mysqli_error($updateEmpQry));
		upload_attachment($conn, $_GET['eid']);
		$_SESSION['employee_operation'] = 2;//for update
		$url = WEB_URL . 'employee/index.php?m=upp';
		echo '<script>window.location.replace("'.$url.'");</script>';
		exit;
	}
}

function upload_attachment($conn, $ref_emp_id){
	if(!empty ($_FILES['employee_photo']['name'])){
		$tmpName = $_FILES['employee_photo']['tmp_name'];
		$employee_photo = rand(11, 99) . basename( $_FILES['employee_photo']['name']);
		$location = "../uploads/employee/employee_photo/".$employee_photo;
		if(move_uploaded_file($tmpName, $location)){
			$updatePicQry = "UPDATE employee SET employee_photo='$employee_photo' WHERE emp_id =  $ref_emp_id";
			mysqli_query($conn,$updatePicQry) or die(mysqli_error($updatePicQry));
		}
	}
	
	if(!empty ($_FILES['visa_copy']['name'])){
		$tmpName = $_FILES['visa_copy']['tmp_name'];
		$visa_copy = rand(11, 99) . basename($_FILES['visa_copy']['name']);
		$location = "../uploads/employee/visa_copy/".$visa_copy;
		if(move_uploaded_file($tmpName, $location)){
			$updatePicQry = "UPDATE employee SET visa_copy='$visa_copy' WHERE emp_id =  $ref_emp_id";
			mysqli_query($conn,$updatePicQry) or die(mysqli_error($updatePicQry));
		}
	}
	
	if(!empty ($_FILES['passport_copy']['name'])){
		$tmpName = $_FILES['passport_copy']['tmp_name'];
		$passport_copy = rand(11, 99) . basename($_FILES['passport_copy']['name']);
		$location = "../uploads/employee/passport_copy/".$passport_copy;
		if(move_uploaded_file($tmpName, $location)){
			$updatePicQry = "UPDATE employee SET passport_copy='$passport_copy' WHERE emp_id =  $ref_emp_id";
			mysqli_query($conn,$updatePicQry) or die(mysqli_error($updatePicQry));
		}
	}
	
	if(!empty ($_FILES['emirates_id_copy']['name'])){
		$tmpName = $_FILES['emirates_id_copy']['tmp_name'];
		$emirates_id_copy = rand(11, 99) . basename($_FILES['emirates_id_copy']['name']);
		$location = "../uploads/employee/emirates_id_copy/".$emirates_id_copy;
		if(move_uploaded_file($tmpName, $location)){
			$updatePicQry = "UPDATE employee SET emirates_id_copy='$emirates_id_copy' WHERE emp_id =  $ref_emp_id";
			mysqli_query($conn,$updatePicQry) or die(mysqli_error($updatePicQry));
		}
	}
}
if(isset($_GET['eid'])){
	$employeeDetailsQry = "SELECT * FROM employee WHERE emp_id=".$_GET['eid'];
	$qryExe = mysqli_query($conn, $employeeDetailsQry);
	if(mysqli_num_rows($qryExe) > 0){
		$theEmpData = mysqli_fetch_assoc($qryExe);
		$company = $theEmpData['ref_comp_id'];
		$emp_name = $theEmpData['emp_name'];
		$father_name = $theEmpData['father_name'];
		$emp_gender = $theEmpData['emp_gender'];
		$emp_email = $theEmpData['emp_email'];
		$e_nationality = $theEmpData['e_nationality'];
		$passport_number = $theEmpData['passport_number'];
		$visa_number = $theEmpData['visa_number'];
		$emirates_id = $theEmpData['emirates_id'];
		$division = $theEmpData['division'];
		$duty_type = $theEmpData['duty_type'];
		$allowance = $theEmpData['allowance'];
		$home_phone = $theEmpData['home_phone'];
		$emp_code = $theEmpData['emp_code'];
		
		$emp_dob = $theEmpData['emp_dob'] == '0000-00-00' ? '' : date('d-m-Y',strtotime($theEmpData['emp_dob']));
		
		$maritial_status = $theEmpData['maritial_status'];
		$emp_mobile = $theEmpData['emp_mobile'];
		$city = $theEmpData['city'];
		
		$medical_allowance = $theEmpData['medical_allowance'];
		$housing_allowance = $theEmpData['housing_allowance'];
		
		$employee_molid = $theEmpData['employee_molid'];
		$labor_card_no = $theEmpData['labor_card_no'];
		$account_no = $theEmpData['account_no'];
		$corporate_account_no = $theEmpData['corporate_account_no'];
		$agent_bank_routing_code = $theEmpData['agent_bank_routing_code'];
		$corporate_mol_estid = $theEmpData['corporate_mol_estid'];
		
		$passport_expiry = $theEmpData['passport_expiry'] == '0000-00-00' ? '' : date('d-m-Y',strtotime($theEmpData['passport_expiry']));
		
		$visa_expiry = $theEmpData['visa_expiry'] == '0000-00-00' ? '' : date('d-m-Y',strtotime($theEmpData['visa_expiry']));
		
		$emirates_id_expiry = $theEmpData['emirates_id_expiry'] == '0000-00-00' ? '' : date('d-m-Y',strtotime($theEmpData['emirates_id_expiry']));
		
		$position = $theEmpData['position'];
		$salary = $theEmpData['salary'];
		$over_time_hour_rate = $theEmpData['over_time_hour_rate'];
		$home_address = $theEmpData['home_address'];
		$employee_photo	 = $theEmpData['employee_photo'];
		$passport_copy = $theEmpData['passport_copy'];
		$visa_copy = $theEmpData['visa_copy'];
		$emirates_id_copy = $theEmpData['emirates_id_copy'];
		
		$food_allowance = $theEmpData['food_allowance'];
		$conveyance_allowance = $theEmpData['conveyance_allowance'];
		
		$work_status = $theEmpData['work_status'];
	}	
}

?>
<style>
.img-holder {
    border: 1px solid #fd7e14;
    margin-top: 20px;
}
</style>
<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<!--- breadcrumb section --->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Add Employee</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Add Employee</li>
					</ul>
				</div>
			</div>
		</div>
		<!--- END: breadcrumb section --->
			
			
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title mb-0">Employee Details</h4>
					</div>
					<form action="" method="POST" id="employee_form" enctype="multipart/form-data">
						<div class="card-body">
							<div class="row">
								<div class="col-xl-12">
									<div class="form-group">
										
										<div class="form-check form-check-inline">
											<input class="form-check-input work-status" type="radio" name="work_status" id="wrk_permenant" value="1" <?php if(!empty($work_status)){ if($work_status == '1'){ echo "checked"; } }else{ echo "checked"; } ?>>
											<label class="form-check-label" for="wrk_permenant">
											Permenant
											</label>
										</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input work-status" type="radio" name="work_status" id="wrk_temprary" value="2" <?php if($work_status == '2'){ echo "checked"; } ?>>
											<label class="form-check-label" for="wrk_temprary">
											Temporary
											</label>
										</div>
									</div>
								</div>
								<div class="col-xl-12">
									<div class="form-group">
										<label  >Company *</label>
										<select name="company" id="company" class="select"><?php
										if(empty($company)){ ?>
											<option value="" disabled SELECTED>Select</option><?php
										} 
										$companiesQry = "SELECT * FROM company_master WHERE visibility=1";
										$qryExe1 = mysqli_query($conn, $companiesQry);
										if(mysqli_num_rows($qryExe1) > 0){
											while($theCompData = mysqli_fetch_assoc($qryExe1)){ ?>
												<option value="<?= $theCompData['comp_id'] ?>" <?php if($company == $theCompData['comp_id']){ echo "SELECTED"; } ?>><?= $theCompData['company_name'] ?></option><?php
											}
										}?>
										
										</select>
									</div>
								</div>
								
							</div>	
						
							<div class="row">
								<div class="col-xl-6">
									<div class="form-group">
										<label class="needs-validation">Name </label>
										<input type="text" class="form-control" name="emp_name" id="emp_name" value="<?= $emp_name ?>">
										
									</div>
									<div class="form-group">
										<label>Father Name </label>
										<input type="text" class="form-control" name="father_name" id="father_name" value="<?= $father_name ?>">
										
									</div>
									<div class="form-group">
										<label  >Gender</label>
										<div class="form-check form-check-inline">
												<input class="form-check-input employe-gender" type="radio" name="emp_gender" id="gender_male" value="m" <?php if(!empty($emp_gender)){ if($emp_gender == 'm'){ echo "checked"; } }else{ echo "checked"; } ?>>
												<label class="form-check-label" for="gender_male">
												Male
												</label>
											</div>
											<div class="form-check form-check-inline">
												<input class="form-check-input employe-gender" type="radio" name="emp_gender" id="gender_female" value="f" <?php if($emp_gender == 'f'){ echo "checked"; } ?>>
												<label class="form-check-label" for="gender_female">
												Female
												</label>
											</div>
									</div>
									<div class="form-group">
										<label  >Email </label>
										<input type="text" class="form-control" name="emp_email" id="emp_email" placeholder="eg. username@business.com" value="<?= $emp_email ?>">
									</div><?php
									
									$arr = array('Afghan','Albanian','Algerian','American','Andorran','Angolan','Antiguans','Argentinean','Armenian','Australian','Austrian','Azerbaijani','Bahamian','Bahraini','Bangladeshi','Barbadian','Barbudans','Batswana','Belarusian','Belgian','Belizean','Beninese','Bhutanese','Bolivian','Bosnian','Brazilian','British','Bruneian','Bulgarian','Burkinabe','Burmese','Burundian','Cambodian','Cameroonian','Canadian','Cape Verdean','Central African','Chadian','Chilean','Chinese','Colombian','Comoran','Congolese','Costa Rican','Croatian','Cuban','Cypriot','Czech','Danish','Djibouti','Dominican','Dutch','East Timorese','Ecuadorean','Egyptian','Emirian','Equatorial Guinean','Eritrean','Estonian','Ethiopian','Fijian','Filipino','Finnish','French','Gabonese','Gambian','Georgian','German','Ghanaian','Greek','Grenadian','Guatemalan','Guinea-Bissauan','Guinean','Guyanese','Haitian','Herzegovinian','Honduran','Hungarian','Icelander','Indian','Indonesian','Iranian','Iraqi','Irish','Israeli','Italian','Ivorian','Jamaican','Japanese','Jordanian','Kazakhstani','Kenyan','Kittian and Nevisian','Kuwaiti','Kyrgyz','Laotian','Latvian','Lebanese','Liberian','Libyan','Liechtensteiner','Lithuanian','Luxembourger','Macedonian','Malagasy','Malawian','Malaysian','Maldivan','Malian','Maltese','Marshallese','Mauritanian','Mauritian','Mexican','Micronesian','Moldovan','Monacan','Mongolian','Moroccan','Mosotho','Motswana','Mozambican','Namibian','Nauruan','Nepalese','Netherlander','New Zealander','Ni-Vanuatu','Nicaraguan','Nigerian','Nigerien','North Korean','Northern Irish','Norwegian','Omani','Palestine','Pakistani','Palauan','Panamanian','Papua New Guinean','Paraguayan','Peruvian','Polish','Portuguese','Qatari','Romanian','Russian','Rwandan','Saint Lucian','Salvadoran','Samoan','San Marinese','Sao Tomean','Saudi','Scottish','Senegalese','Serbian','Seychellois','Sierra Leonean','Singaporean','Slovakian','Slovenian','Solomon Islander','Somali','South African','South Korean','Spanish','Sri Lankan','Sudanese','Surinamer','Swazi','Swedish','Swiss','Syrian','Taiwanese','Tajik','Tanzanian','Thai','Togolese','Tongan','Trinidadian or Tobagonian','Tunisian','Turkish','Tuvaluan','Ugandan','Ukrainian','Uruguayan','Uzbekistani','Venezuelan','Vietnamese','Welsh','Yemenite','Zambian','Zimbabwean'); ?>
									<div class="form-group nationality">
										<label  >Country </label>
										<select name="e_nationality" id="e_nationality" class="select"><?php
											if(empty($e_nationality)){ ?>
												<option disabled Selected>Select</option><?php
											} 
											
											for($i=0; $i<count($arr); $i++) {
												$selecteds = $e_nationality;
												$selected = ($arr[$i] == $selecteds) ? "selected='selected'" : "";
												echo "<option value='$arr[$i]' $selected>$arr[$i]</option>"; 
											} 	?>
											</select>
									</div>
									<div class="form-group">
										<label  >Passport Number</label>
										<input type="text" class="form-control" name="passport_number" id="passport_number" value="<?= $passport_number ?>">
									</div>
									<div class="form-group">
										<label  >Visa Number</label>
										<input type="text" class="form-control" name="visa_number" id="visa_number" value="<?= $visa_number ?>">
									</div>
									<div class="form-group">
										<label  >Emirates ID</label>
										<input type="text" class="form-control" name="emirates_id" id="emirates_id" value="<?= $emirates_id ?>">
									</div>
									<div class="form-group">
										<label  >Department </label>
										<!--- <input type="text" class="form-control" name="division" id="division" value="<?= $division ?>"> ---->
										<select name="division" id="division" class="select"><?php
										if(empty($division)){ ?>
											<option value="" disabled SELECTED>Select</option><?php
										} 
										$departmentsQry = "SELECT * FROM department_master";
										$qryExe1 = mysqli_query($conn, $departmentsQry);
										if(mysqli_num_rows($qryExe1) > 0){
											while($theDepData = mysqli_fetch_assoc($qryExe1)){ ?>
												<option value="<?= $theDepData['dep_id'] ?>" <?php if($division == $theDepData['dep_id']){ echo "SELECTED"; } ?>><?= $theDepData['department_name'] ?></option><?php
											}
										}?>
										
										</select>
									</div>
									<div class="form-group duty-type">
										<label  >Duty Type </label>
										<select name="duty_type" id="duty_type" class="select"><?php
											if(empty($duty_type)){ ?>
												<option value="" disabled Selected>Select</option><?php
											} ?>
												<option value="fulltime" <?php if($duty_type == 'fulltime'){ echo "Selected"; } ?>>Full Time</option><option value="parttime" <?php if($duty_type == 'parttime'){ echo "Selected"; } ?>>Part Time</option>	 	
											</select>
									</div>
									<div class="form-group">
										<label  >Allowance</label>
										<input type="text" class="form-control" name="allowance" id="allowance" value="<?= $allowance ?>">
									</div>
									<div class="form-group">
										<label  >Food Allowance</label>
										<input type="text" class="form-control" name="food_allowance" id="food_allowance" value="<?= $food_allowance ?>">
									</div>
									<div class="form-group">
										<label  >Medical Allowance</label>
										<input type="text" class="form-control" name="medical_allowance" id="medical_allowance" value="<?= $medical_allowance ?>">
									</div>
									<div class="form-group">
										<label  >Housing Allowance</label>
										<input type="text" class="form-control" name="housing_allowance" id="housing_allowance" value="<?= $housing_allowance ?>">
									</div>
									<div class="form-group">
										<label>Over Time Hour Rate</label>
										<input type="text" class="form-control" name="over_time_hour_rate" id="over_time_hour_rate" value="<?= $over_time_hour_rate ?>" readonly>
									</div>
									<div class="form-group">
										<label  >Home Phone</label>
										<input type="text" class="form-control" name="home_phone" id="home_phone" value="<?= $home_phone ?>">
									</div>
									
								</div>
								<div class="col-xl-6">
									<div class="form-group">
										<label  >Employee CODE </label>
										<input type="text" class="form-control" name="emp_code" id="emp_code" value="<?= $emp_code ?>">
									</div>
									<div class="form-group">
										<label  >Date of Birth</label>
										<input type="text" class="form-control datetimepicker" name="emp_dob" id="emp_dob" value="<?= $emp_dob ?>">
									</div>
									<div class="form-group">
										<label  >Marital Status</label>
										<div class="form-check form-check-inline">
											<input class="form-check-input maritial-status" type="radio" name="maritial_status" id="m-unmarried" value="unmarried" <?php if(!empty($maritial_status)){ if($maritial_status == 'unmarried'){ echo "checked"; } }else{ echo "checked"; } ?>>
											<label class="form-check-label" for="m-unmarried">
												Unmarried
												</label>
										</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input maritial-status" type="radio" name="maritial_status" id="m-maried" value="married" <?php if($maritial_status == 'married'){ echo "checked"; } ?>>
											<label class="form-check-label" for="m-maried">
												Married
											</label>
										</div>
									</div>
									<div class="form-group">
										<label  >Mobile</label>
										<input type="text" class="form-control" name="emp_mobile" id="emp_mobile" value="<?= $emp_mobile ?>">
									</div>
									<div class="form-group">
										<label  >City</label>
										<input type="text" class="form-control" name="city" id="city" value="<?= $city ?>">
									</div>
									<div class="form-group">
										<label  >Passport Expiry</label>
										<input type="text" class="form-control datetimepicker" name="passport_expiry" id="passport_expiry" value="<?= $passport_expiry ?>">
									</div>
									<div class="form-group">
										<label  >Visa Expiry</label>
										<input type="text" class="form-control datetimepicker" name="visa_expiry" id="visa_expiry" value="<?= $visa_expiry ?>">
									</div>
									<div class="form-group">
										<label  >Emirates ID Expiry</label>
										<input type="text" class="form-control datetimepicker" name="emirates_id_expiry" id="emirates_id_expiry" value="<?= $emirates_id_expiry ?>">
									</div>
																		
									<div class="form-group">
										<label  >Position</label>
										<select name="position" id="position" class="select"><?php
										if(empty($position)){ ?>
											<option value="" disabled SELECTED>Select</option><?php
										} 
										$positionsQry = "SELECT * FROM position_master WHERE visibility=1";
										$qryExe1 = mysqli_query($conn, $positionsQry);
										if(mysqli_num_rows($qryExe1) > 0){
											while($thePositionData = mysqli_fetch_assoc($qryExe1)){ ?>
												<option value="<?= $thePositionData['position_id'] ?>" <?php if($position == $thePositionData['position_id']){ echo "SELECTED"; } ?>><?= $thePositionData['position_name'] ?></option><?php
											}
										}?>
										
										</select>
									</div>
									
									
									<div class="form-group">
										<label  >Salary</label>
										<input type="text" class="form-control" name="salary" id="salary" value="<?= $salary ?>">
									</div>
									
									<div class="form-group">
										<label  >Conveyance Allowance</label>
										<input type="text" class="form-control" name="conveyance_allowance" id="conveyance_allowance" value="<?= $conveyance_allowance ?>">
									</div>
									
									<div class="form-group">
										<label  >Home Address</label>
										<input type="text" class="form-control" name="home_address" id="home_address" value="<?= $home_address ?>">
									</div>
								</div>
							</div>
						</div>
						<div class="card-header">
							<h4 class="card-title mb-0">Attachement</h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-xl-6">
									<div class="form-group ">
										<label class="col-form-label">Employee Photo</label>
										<input class="form-control" type="file" name="employee_photo" id="employee_photo">
										
										<div class="img-holder employee_photo-prev-div" <?php if(empty($employee_photo)){ ?>style="display:none" <?php } ?>>
											<img src="<?php if(!empty($employee_photo)){ echo WEB_URL."/uploads/employee/employee_photo/".$employee_photo; } ?>" style="height:150px;width:100%" class="employee_photo-prev-img">
										</div>
									</div>
									<div class="form-group ">
										<label class="col-form-label">Visa Copy</label>
										<input class="form-control" type="file" name="visa_copy" id="visa_copy">
										
										<div class="img-holder visa_copy-prev-div" <?php if(empty($visa_copy)){ ?>style="display:none" <?php } ?>>
											<img src="<?php if(!empty($visa_copy)){ echo WEB_URL."/uploads/employee/visa_copy/".$visa_copy; }?>" style="height:150px;width:100%" class="visa_copy-prev-img">
										</div>
									</div>
								</div>
								<div class="col-xl-6">
									<div class="form-group ">
										<label class="col-form-label">Passport Copy</label>
										<input class="form-control" type="file" name="passport_copy" id="passport_copy">
										
										<div class="img-holder passport_copy-prev-div" <?php if(empty($passport_copy)){ ?>style="display:none" <?php } ?>>
											<img src="<?php if(!empty($passport_copy)){ echo WEB_URL."/uploads/employee/passport_copy/".$passport_copy;}?>" style="height:150px;width:100%" class="passport_copy-prev-img">
										</div>
									</div>
									<div class="form-group ">
										<label class="col-form-label">Emirates ID Copy</label>
										<input class="form-control" type="file" name="emirates_id_copy" id="emirates_id_copy">
										<div class="img-holder emirates_id_copy-prev-div" <?php if(empty($emirates_id_copy)){ ?>style="display:none" <?php } ?>>
											<img src="<?php if(!empty($emirates_id_copy)){ echo  WEB_URL."/uploads/employee/emirates_id_copy/".$emirates_id_copy;}?>" style="height:150px;width:100%" class="emirates_id_copy-prev-img">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-header">
							<h4 class="card-title mb-0">Bank Details</h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-xl-6">
									<div class="form-group">
										<label>Employee Molid</label>
										<input type="text" class="form-control" name="employee_molid" id="employee_molid" value="<?= $employee_molid ?>">
									</div>
									<div class="form-group">
										<label>Account No</label>
										<input type="text" class="form-control" name="account_no" id="account_no" value="<?= $account_no ?>">
									</div>
									<div class="form-group">
										<label>Agent Bank Routing Code</label>
										<input type="text" class="form-control" name="agent_bank_routing_code" id="agent_bank_routing_code" value="<?= $agent_bank_routing_code ?>">
									</div>
								</div>
								<div class="col-xl-6">
									<div class="form-group">
										<label>Labor Card No</label>
										<input type="text" class="form-control" name="labor_card_no" id="labor_card_no" value="<?= $labor_card_no ?>">
									</div>
									<div class="form-group">
										<label>Corporate Account No</label>
										<input type="text" class="form-control" name="corporate_account_no" id="corporate_account_no" value="<?= $corporate_account_no ?>">
									</div>
									<div class="form-group">
										<label>Corporate Mol estid</label>
										<input type="text" class="form-control" name="corporate_mol_estid" id="corporate_mol_estid" value="<?= $corporate_mol_estid ?>">
									</div>
								</div>
							</div>
						</div>
						<div class="card-body">
							<?php 
							if(!isset($_GET['eid']) && empty($_GET['eid'])){ ?>
								<div class="text-right">
									<button type="submit" name="submit" value="submit" class="btn btn-primary">Save</button>
								</div><?php
							} ?>
							
							<?php 
							if(isset($_GET['eid']) && !empty($_GET['eid'])){ ?>
								<input type="hidden" name="ref_emp_id" value="<?= $_GET['eid']; ?>">
								<div class="text-right">
									<button type="submit" name="submit" value="submit" class="btn btn-primary">Update</button>
								</div><?php
							} ?>
						</div>
						
					</form>
				</div>
			</div>
		</div>
	</div>
</div>


<?php
require '../footer.php'

?>

<script  src="<?php echo WEB_URL; ?>assets/js/c_add_employee.js"></script>
    </body>
</html>