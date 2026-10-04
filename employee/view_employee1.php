<?php

include('../header.php'); ?>

<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<!--- breadcrumb section --->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Employee Details</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Employee Details</li>
					</ul>
				</div>
			</div>
		</div><?php
		if(isset($_GET['eid']) && !empty(($_GET['eid']))){ 
			$getEmployeeDetails = "SELECT *,(SELECT department_name FROM department_master WHERE dep_id =division ) as department_name, (SELECT position_name FROM position_master WHERE position_id  = position) as position_name  FROM employee WHERE emp_id =".$_GET['eid'];
			$qryExe = mysqli_query($conn, $getEmployeeDetails); 
			$dataRowCount = mysqli_num_rows($qryExe);
			if($dataRowCount > 0){ 
				while($row = mysqli_fetch_assoc($qryExe)){ ?>
					<div class="card mb-0">
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<div class="profile-view">
										<div class="profile-img-wrap">
											<div class="profile-img">
												<a href="#"><img alt="" src="<?php if(!empty($row['employee_photo'])){ echo WEB_URL.'uploads/employee/employee_photo/'.$row['employee_photo']; }else{ echo WEB_URL.'assets/img/profiles/avatar-02.jpg'; } ?>"></a>
											</div>
										</div>
										<div class="profile-basic">
											<div class="row">
												<div class="col-md-5">
													<div class="profile-info-left">
														<h3 class="user-name m-t-0 mb-0"><?php echo $row['emp_name']; ?></h3>
														<h6 class="text-muted"><?php echo $row['department_name']; ?></h6>
														<small class="text-muted"><?php echo $row['position_name']; ?></small>
														<div class="staff-id">Employee Code : <?php echo $row['emp_code']; ?></div>
														<div class="staff-msg"><a class="btn btn-custom" href="<?= WEB_URL ?>employee/add_employee.php?eid=<?=$row['emp_id'] ?>">Edit Employee</a></div>
													</div>
												</div>
												<div class="col-md-7">
													<ul class="personal-info">
														<li>
															<div class="title">Phone:</div>
															<div class="text"><a href=""><?php echo $row['emp_mobile']; ?></a></div>
														</li>
														<li>
															<div class="title">Email:</div>
															<div class="text"><a href=""><?php echo $row['emp_email']; ?></a></div>
														</li>
														<li>
															<div class="title">Birthday:</div>
															<div class="text"><?php echo $row['emp_dob']; ?></div>
														</li>
														<li>
															<div class="title">Address:</div>
															<div class="text"><?php echo $row['home_address']; ?></div>
														</li>
														<li>
															<div class="title">Gender:</div>
															<div class="text"><?php if($row['emp_gender'] == 'm'){echo "Male"; }else if($row['emp_gender'] == 'f'){ echo "Female"; } ?></div>
														</li>
													</ul>
												</div>
											</div>
										</div>
										
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="card tab-box">
						<div class="row user-tabs">
							<div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
								<ul class="nav nav-tabs nav-tabs-bottom">
									<li class="nav-item"><a href="#emp_profile" data-toggle="tab" class="nav-link active">Profile</a></li>
									<li class="nav-item"><a href="#emp_projects" data-toggle="tab" class="nav-link">Projects</a></li>
									
								</ul>
							</div>
						</div>
					</div>
					
					<div class="tab-content">
						<!-- Profile Info Tab -->
						<div id="emp_profile" class="pro-overview tab-pane fade show active">
							<div class="row">
								<div class="col-md-6 d-flex">
									<div class="card profile-box flex-fill">
										<div class="card-body">
											<h3 class="card-title">Personal Informations </h3>
											<div class="table-responsive">
											<table class="table table-bordered">
											    <tbody>
											         
												<tr>
													<td>Passport No.</td>
													<td><?php echo $row['passport_number']; ?></td>
												</tr>
												<tr>
													<td>Passport Exp Date.</td>
													<td><?php echo $row['passport_expiry']; ?></td>
												</tr>
												<tr>
													<td>Visa No.</td>
													<td><?php echo $row['visa_number']; ?></td>
												</tr>
												<tr>
													<td>Visa Exp Date.</td>
													<td><?php echo $row['visa_expiry']; ?></td>
												</tr>
												<tr>
													<td>Emirates ID.</td>
													<td><?php echo $row['emirates_id']; ?></td>
												</tr>
												<tr>
													<td>Emirates ID Exp.</td>
													<td><?php echo $row['emirates_id_expiry']; ?></td>
												</tr>
												<tr>
													<td>Phone.</td>
													<td><?php echo $row['home_phone']; ?></td>
												</tr>
												<tr>
													<td>Nationality.</td>
													<td><?php echo $row['e_nationality']; ?></td>
												</tr>
												<tr>
													<td>Marital status.</td>
													<td><?php echo $row['maritial_status']; ?></td>
												</tr>
												
										 
											    </tbody>
											</table> 
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 d-flex">
									<div class="card profile-box flex-fill">
										<div class="card-body">
											<h3 class="card-title">Increment Details </h3>
											<div class="table-responsive">
											<table class="table table-bordered mb-3"><?php
											$getInitialSalAllQry = "SELECT * FROM salary_increment WHERE ref_emp_id=". $_GET['eid']." LIMIT 1";
											$getInitialSalAllQryExe = mysqli_query($conn,$getInitialSalAllQry);
											if(mysqli_num_rows($getInitialSalAllQryExe) > 0){
												$theInitialSalAll = mysqli_fetch_assoc($getInitialSalAllQryExe); ?>
											    <tbody>
											        <tr>
														<th>Initial Salary</th>
														<td><?php echo $theInitialSalAll['previous_salary']; ?></td>
													</tr>
													<tr>
														<th>Initial Allowance</th>
														<td><?php echo $theInitialSalAll['previous_allowance']; ?></td>
													</tr>
												</tbody><?php
											}else{ ?>
												 <tbody>
											        <tr>
														<th>Initial Salary</th>
														<td><?php echo $row['salary']; ?></td>
													</tr>
													<tr>
														<th>Initial Allowance</th>
														<td><?php echo $row['allowance']; ?></td>
													</tr>
												</tbody><?php
												
											} ?>
											
											</table>
											
											<table class="table table-bordered mb-3">
											    <tbody>
											        <tr>
														<th>Current Salary</th>
														<td><?php echo $row['salary']; ?></td>
													</tr>
													<tr>
														<th>Current Allowance</th>
														<td><?php echo $row['allowance']; ?></td>
													</tr>
												</tbody>
											</table>
											<table class="table table-bordered">
											    <tbody>
											        <tr>
    													<th>Increment Date</th>
    													<th>Salary</th>
    													<th>Allowance</th>
    													
    												</tr><?php
													
													$getIncrementQry = "SELECT * FROM salary_increment WHERE ref_emp_id=".$_GET['eid']; 
													$getIncrementQryExe = mysqli_query($conn, $getIncrementQry);
													if(mysqli_num_rows($getIncrementQryExe) > 0){
														while($theIncrementList = mysqli_fetch_assoc($getIncrementQryExe)){ ?>
														<tr>
															<td><?php echo $theIncrementList['increment_dt'] ?></td>
															<td style="text-align:center"><?= $theIncrementList['increment_amt'] ?></td>
															<td style="text-align:center"><?= $theIncrementList['increment_allowance'] ?></td>
														</tr><?php
														
														}
													} ?>
    											</tbody>
											</table> 
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 d-flex">
									<div class="card profile-box flex-fill">
										<div class="card-body">
											<h3 class="card-title">Bank Informations </h3>
											<div class="table-responsive">
											<table class="table table-bordered">
											    <tbody>
												<tr>
													<td>Employee Molid.</td>
													<td><?php echo $row['employee_molid']; ?></td>
												</td>
												<tr>
													<td>Labor Card No.</td>
													<td><?php echo $row['labor_card_no']; ?></td>
												</td>
												<tr>
													<td>Account No.</td>
													<td><?php echo $row['account_no']; ?></td>
												</td>
												<tr>
													<td>Corporate Account No.</td>
													<td><?php echo $row['corporate_account_no']; ?></td>
												</td>
												<tr>
													<td>Agent Bank Routing Code.</td>
													<td><?php echo $row['agent_bank_routing_code']; ?></td>
												</td>
												<tr>
													<td>Corporate Mol estid.</td>
													<td><?php echo $row['corporate_mol_estid']; ?></td>
												</td>
												</tbody>
												</table>
												</div>
										</div>
									</div>
								</div>
								
								<div class="col-md-6 d-flex">
									<div class="card profile-box flex-fill">
										<div class="card-body">
											<h3 class="card-title">Allowances</h3>
											<div class="table-responsive">
											<table class="table table-bordered">
											    <tbody>
												<tr>
													<td>Allowance.</td>
													<td><?php echo $row['allowance']; ?></td>
												</tr>
												<tr>
													<td>Conveyance Allowance.</td>
													<td><?php echo $row['conveyance_allowance']; ?></td>
												</tr>
												<tr>
													<td>Food Allowance.</td>
													<td><?php echo $row['food_allowance']; ?></td>
												</tr>
												<tr>
													<td>Medical Allowance.</td>
													<td><?php echo $row['medical_allowance']; ?></td>
												</tr>
												<tr>
													<td>Housing Allowance.</td>
													<td><?php echo $row['housing_allowance']; ?></td>
												</tr>
												
											</tbody>
											</table>
											</div>
										</div>
									</div>
								</div>
								
								<div class="col-md-6 d-flex">
									<div class="card profile-box flex-fill">
										<div class="card-body">
											<h3 class="card-title">Loan Details</h3>
											<div class="table-responsive">
												<table class="table table-bordered">
											<?php
											$sumOfLoanPenality =0;
											$getLoanListQry = "SELECT *,sum(loan_amount) as loan_amount FROM loan_details WHERE ref_emp_id=".$row['emp_id']." GROUP BY loan_type"; 
											
											$getLoanListQryExe = mysqli_query($conn, $getLoanListQry);
											if(mysqli_num_rows($getLoanListQryExe) > 0){
												while($theLoanList = mysqli_fetch_assoc($getLoanListQryExe)){ ?>
												
											    
												<tr>
													<td><?php if($theLoanList['loan_type'] == 1){ echo "Loan"; }else{ echo "Penality"; } ?></td>
													<td><?php echo $theLoanList['loan_amount']; 
													$sumOfLoanPenality = $sumOfLoanPenality + $theLoanList['loan_amount']; ?></td>
												</tr><?php
												} 
											}
											$empLoanPaidTotalQry = "SELECT sum(paid_amount) as paid_amount FROM loan_payment_history WHERE ref_emp_id=".$row['emp_id'];
											$empLoanPaidTotalQryExe = mysqli_query($conn, $empLoanPaidTotalQry); 
											if( mysqli_num_rows($empLoanPaidTotalQryExe) > 0 ){
												$row3 = mysqli_fetch_assoc($empLoanPaidTotalQryExe);
												$loan_paid_total = $row3['paid_amount'];
											} ?>
											<tr>
											<td>Total Loan Paid.</td>
											<td><?php if(!empty($loan_paid_total)){ echo $loan_paid_total; } else{ echo "0"; } ?></td>
											</tr>
											<tr>	
												<td>Pending Loan.</td>
												<td><?php echo $sumOfLoanPenality -  $loan_paid_total; ?></td>
											</tr>	
											
											</table>
											</div>
										</div>
									</div>
								</div>
								
								
								<div class="col-md-6 d-flex">
									<div class="card profile-box flex-fill">
										<div class="card-body">
											<h3 class="card-title">Vactoin Details</h3>
											<div class="table-responsive">
											<table class="table table-bordered">
											<thead>
												<tr>
													<th>Leave Start Date</th>
													<th>Leave End Date</th>
													<th>Rejoined Date</th>
													<th>Leave Days</th>
												<tr>
											</thead>
											<tbody>
											<?php
											$getVactionListQry="SELECT * FROM  vacation_details WHERE ref_emp_id=".$row['emp_id'];
											$getVactionListQryExe = mysqli_query($conn, $getVactionListQry);
											if(mysqli_num_rows($getVactionListQryExe) > 0){
												while($theVactionList = mysqli_fetch_assoc( $getVactionListQryExe )){ 
													$rejoin_dt = $total_lv_days = '';
													
													$lv_start_dt = $theVactionList['leave_start_dt'];
													$lv_end_dt = $theVactionList['leave_end_dt'];
													if($theVactionList['rejoining_date'] != '0000-00-00'){	
														$rejoin_dt = date('d-m-Y',strtotime( $theVactionList['rejoining_date']));
														
														$total_lv_days = strtotime($theVactionList['rejoining_date']) - strtotime($lv_start_dt);
														$total_lv_days = ($total_lv_days/(60*60*24))+1;
													} ?>
													<tr>
														<td><?php echo date('d-m-Y', strtotime($lv_start_dt)) ?></td>
														<td><?php echo date('d-m-Y', strtotime($lv_end_dt)) ?></td>
														<td><?php echo $rejoin_dt ?></td>
														<td><?php echo $total_lv_days ?></td>
													</tr><?php
												}
											}?>
											</tbody>	
											</table>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 d-flex">
									<div class="card profile-box flex-fill">
										<div class="card-body">
											<h3 class="card-title">Emergency Contact </h3>
											<div class="table-responsive">
											<table class="table table-bordered">
											    <tbody>
											        <tr>
    													<td>Home Phone.</td>
    													<td style="text-align:center"><?php echo $row['home_phone'] == '' ? '-':$row['home_phone'] ; ?></td>
    												</tr>
    												<tr>
    													<td>Home Address.</td>
    													<td style="text-align:center"><?php  echo $row['home_address'] == '' ? '-':$row['home_address'] ;
														?></td>
    												</tr>
											    </tbody>
											</table> 
											</div>
										</div>
									</div>
								</div>
								
								
							</div>
							<div class="row"><?php
								if(!empty($row['passport_copy'])){ ?>
								
									<div class="col-md-6 d-flex">
										<div class="card profile-box flex-fill">
											<div class="card-body">
												<h3 class="card-title">Passport Copy </h3>
												<img src="<?php echo WEB_URL.'uploads/employee/passport_copy/'.$row['passport_copy']; ?>" style="height:150px;width:100%">
											</div>
										</div>
									</div><?php
								}
								if(!empty($row['visa_copy'])){ ?>
								
									<div class="col-md-6 d-flex">
										<div class="card profile-box flex-fill">
											<div class="card-body">
												<h3 class="card-title">Visa Copy </h3>
												<img src="<?php echo WEB_URL.'uploads/employee/visa_copy/'.$row['visa_copy']; ?>" style="height:150px;width:100%">
											</div>
										</div>
									</div><?php
								}
								if(!empty($row['emirates_id_copy'])){ ?>
								
									<div class="col-md-6 d-flex">
										<div class="card profile-box flex-fill">
											<div class="card-body">
												<h3 class="card-title">Emirates ID Copy </h3>
												<img src="<?php echo WEB_URL.'uploads/employee/emirates_id_copy/'.$row['emirates_id_copy']; ?>" style="height:150px;width:100%">
											</div>
										</div>
									</div><?php
								} ?>

							</div>
						</div>
						<!-- /Profile Info Tab -->
						
						<!-- Projects Tab -->
						<div class="tab-pane fade" id="emp_projects">
							<div class="row">
								<div class="col-lg-12 col-sm-12 col-md-12 col-xl-12">
									<div class="card">
										<div class="card-body">
											<table class="table table-bordered">
												<thead>
												<tr>
													<th>Projetc Name</th>
													<th>Description</th>
													<th>Join Date</th>
													<th>Quite Date</th>
													<th>Total Days</th>
												</tr>
												</thead><?php
											$getEmpIndividualProjQry = "SELECT pe.*, pd.* FROM project_employees pe INNER JOIN project_details pd ON pe.ref_proj_id = pd.proj_id WHERE pe.ref_emp_id=".$_GET['eid']; 
											
											//echo $getEmpIndividualProjQry;
											
											$getEmpIndividualProjExe = mysqli_query($conn,$getEmpIndividualProjQry); 
											
											if(mysqli_num_rows($getEmpIndividualProjExe) > 0){ 
												while($theProjDetsData = mysqli_fetch_assoc($getEmpIndividualProjExe)){ ?>
													<tr>
														<td><?= $theProjDetsData['proj_name'] ?>
														</td>
														<td><?php
																echo $theProjDetsData['proj_desc']; ?>
														</td>
														<td><?php
															$emp_joined_date = $theProjDetsData['emp_joined_date'];
															$emp_joined_date = date('d M Y',strtotime($emp_joined_date));
															echo $emp_joined_date;
															?>
														</td>
														<td><?php
															$emp_quite_date = $theProjDetsData['emp_quite_date'];
															$emp_quite_date = date('d M Y',strtotime($emp_quite_date));
															echo $emp_quite_date;
															?>
														</td>
														<td><?php
															$daysCount = strtotime($emp_quite_date) - strtotime($emp_joined_date);
															
															$daysCount = ($daysCount/(60*60*24))+1;
															echo $daysCount;
														?>
															
														</td>
														
													</tr>
																
																
															<?php
												}
											} ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- /Projects Tab -->
					</div><?php
				}
			}
		}?>
	
					
					
	</div>
</div>

<?php
require '../footer.php'

?>
	</body>
</html>