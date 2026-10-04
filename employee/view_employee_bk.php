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
											
											<!--<ul class="personal-info">-->
											<!--	<li>-->
											<!--		<div class="title">Passport No.</div>-->
											<!--		<div class="text"><?php echo $row['passport_number']; ?></div>-->
											<!--	</li>-->
											<!--	<li>-->
											<!--		<div class="title">Passport Exp Date.</div>-->
											<!--		<div class="text"><?php echo $row['passport_expiry']; ?></div>-->
											<!--	</li>-->
											<!--	<li>-->
											<!--		<div class="title">Visa No.</div>-->
											<!--		<div class="text"><?php echo $row['visa_number']; ?></div>-->
											<!--	</li>-->
											<!--	<li>-->
											<!--		<div class="title">Visa Exp Date.</div>-->
											<!--		<div class="text"><?php echo $row['visa_expiry']; ?></div>-->
											<!--	</li>-->
											<!--	<li>-->
											<!--		<div class="title">Emirates ID.</div>-->
											<!--		<div class="text"><?php echo $row['emirates_id']; ?></div>-->
											<!--	</li>-->
											<!--	<li>-->
											<!--		<div class="title">Emirates ID Exp.</div>-->
											<!--		<div class="text"><?php echo $row['emirates_id_expiry']; ?></div>-->
											<!--	</li>-->
											<!--	<li>-->
											<!--		<div class="title">Phone.</div>-->
											<!--		<div class="text"><?php echo $row['home_phone']; ?></div>-->
											<!--	</li>-->
											<!--	<li>-->
											<!--		<div class="title">Nationality.</div>-->
											<!--		<div class="text"><?php echo $row['e_nationality']; ?></div>-->
											<!--	</li>-->
											<!--	<li>-->
											<!--		<div class="title">Marital status.</div>-->
											<!--		<div class="text"><?php echo $row['maritial_status']; ?></div>-->
											<!--	</li>-->
												
											<!--</ul>-->
											</table>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 d-flex">
									<div class="card profile-box flex-fill">
										<div class="card-body">
											<h3 class="card-title">Emergency Contact </h3>
											<ul class="personal-info">
												<li>
													<div class="title">Home Phone.</div>
													<div class="text"><?php echo $row['home_phone']; ?></div>
												</li>
												<li>
													<div class="title">Home Address.</div>
													<div class="text"><?php echo $row['home_address']; ?></div>
												</li>
											</ul>
										</div>
									</div>
								</div>
								<div class="col-md-6 d-flex">
									<div class="card profile-box flex-fill">
										<div class="card-body">
											<h3 class="card-title">Bank Informations </h3>
											<ul class="personal-info">
												<li>
													<div class="title">Employee Molid.</div>
													<div class="text"><?php echo $row['employee_molid']; ?></div>
												</li>
												<li>
													<div class="title">Labor Card No.</div>
													<div class="text"><?php echo $row['labor_card_no']; ?></div>
												</li>
												<li>
													<div class="title">Account No.</div>
													<div class="text"><?php echo $row['account_no']; ?></div>
												</li>
												<li>
													<div class="title">Corporate Account No.</div>
													<div class="text"><?php echo $row['corporate_account_no']; ?></div>
												</li>
												<li>
													<div class="title">Agent Bank Routing Code.</div>
													<div class="text"><?php echo $row['agent_bank_routing_code']; ?></div>
												</li>
												<li>
													<div class="title">Corporate Mol estid.</div>
													<div class="text"><?php echo $row['corporate_mol_estid']; ?></div>
												</li>
											</ul>
										</div>
									</div>
								</div>
								
								<div class="col-md-6 d-flex">
									<div class="card profile-box flex-fill">
										<div class="card-body">
											<h3 class="card-title">Allowances</h3>
											<ul class="personal-info">
												<li>
													<div class="title">Allowance.</div>
													<div class="text"><?php echo $row['allowance']; ?></div>
												</li>
												<li>
													<div class="title">Conveyance Allowance.</div>
													<div class="text"><?php echo $row['conveyance_allowance']; ?></div>
												</li>
												<li>
													<div class="title">Food Allowance.</div>
													<div class="text"><?php echo $row['food_allowance']; ?></div>
												</li>
												<li>
													<div class="title">Medical Allowance.</div>
													<div class="text"><?php echo $row['medical_allowance']; ?></div>
												</li>
												<li>
													<div class="title">Housing Allowance.</div>
													<div class="text"><?php echo $row['housing_allowance']; ?></div>
												</li>
												
											</ul>
										</div>
									</div>
								</div>
								
								<div class="col-md-6 d-flex">
									<div class="card profile-box flex-fill">
										<div class="card-body">
											<h3 class="card-title">Loan Details</h3>
											<ul class="personal-info"><?php
											
											$getLoanListQry = "SELECT * FROM loan_details WHERE ref_emp_id=".$row['emp_id']; 
											$getLoanListQryExe = mysqli_query($conn, $getLoanListQry);
											if(mysqli_num_rows($getLoanListQryExe) > 0){
												while($theLoanList = mysqli_fetch_assoc($getLoanListQryExe)){ ?>
												<li>
													<div class="title">Food Allowance.</div>
													<div class="text"><?php echo $theLoanList['loan_amount']; ?></div>
												</li><?php
												} 
											}
											$empLoanPaidTotalQry = "SELECT sum(paid_amount) as paid_amount FROM loan_payment_history WHERE ref_emp_id=".$row['emp_id'];
											$empLoanPaidTotalQryExe = mysqli_query($conn, $empLoanPaidTotalQry); 
											if( mysqli_num_rows($empLoanPaidTotalQryExe) > 0 ){
												$row3 = mysqli_fetch_assoc($empLoanPaidTotalQryExe);
												$loan_paid_total = $row3['paid_amount'];
											} ?>
											<li>
											<div class="title">Total Loan Paid.</div>
											<div class="text"><?php if(!empty($loan_paid_total)){ echo $loan_paid_total; } else{ echo "0"; } ?></div>
											</li>
												
											</ul>
										</div>
									</div>
								</div>
								
								
								<div class="col-md-6 d-flex">
									<div class="card profile-box flex-fill">
										<div class="card-body">
											<h3 class="card-title">Vactoin Details</h3>
											<ul class="personal-info"><?php
											$getVactionListQry="SELECT * FROM  vacation_details WHERE ref_emp_id=".$row['emp_id'];
											$getVactionListQryExe = mysqli_query($conn, $getVactionListQry);
											if(mysqli_num_rows($getVactionListQryExe) > 0){
												while($theVactionList = mysqli_fetch_assoc( $getVactionListQryExe )){ ?>
													<div class="title">Vacation Date.</div>
													<div class="text"><?php 
													
													$vacation_start_dt = $theVactionList['leave_start_dt'] == '0000-00-00' ? '' : date('d-m-Y',strtotime($theVactionList['leave_start_dt']));
													
													$vacation_end_dt = $theVactionList['leave_end_dt'] == '0000-00-00' ? '' : date('d-m-Y',strtotime($theVactionList['leave_end_dt']));
													
													echo $vacation_start_dt." to ".$vacation_end_dt;  ?></div><?php
												}
											}?>
												
											</ul>
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
							<div class="row"><?php
								$getEmpIndividualProjQry = "SELECT pe.*, pd.* FROM project_employees pe INNER JOIN project_details pd ON pe.ref_proj_id = pd.proj_id WHERE pe.ref_emp_id=".$_GET['eid']; 
								
								//echo $getEmpIndividualProjQry;
								
								$getEmpIndividualProjExe = mysqli_query($conn,$getEmpIndividualProjQry); 
								
								if(mysqli_num_rows($getEmpIndividualProjExe) > 0){ 
									while($theProjDetsData = mysqli_fetch_assoc($getEmpIndividualProjExe)){ ?>
										<div class="col-lg-4 col-sm-6 col-md-4 col-xl-3">
											<div class="card">
												<div class="card-body">
													
													<h4 class="project-title"><a href="project-view.html"><?= $theProjDetsData['proj_name'] ?></a></h4>
													<p class="text-muted"><?php
														$desc = implode(' ', array_slice(explode(' ', $theProjDetsData['proj_desc']), 0, 10)); 
														echo $desc; ?>
													</p>
													<div class="pro-deadline m-b-15">
														<div class="sub-title">
															Deadline:
														</div>
														<div class="text-muted"><?php
														$proj_end_dt = $theProjDetsData['proj_end_date'];
														$proj_end_dt = date('d M Y',strtotime($proj_end_dt));
														echo $proj_end_dt;
														?>
															
														</div>
													</div>
													<div class="pro-deadline m-b-15">
														<div class="sub-title">
															Project Status:
														</div>
														<div class="text-muted"><?php
															$projQuitedEmpCountQry = "SELECT count(proj_emp_id) as quited_emp_count FROM project_employees WHERE ref_proj_id=".$theProjDetsData['proj_id']." AND emp_proj_status = 1";
															
															$projQuitedEmpCountExe = mysqli_query($conn,$projQuitedEmpCountQry);
															$projQuitedEmpCount = mysqli_fetch_assoc($projQuitedEmpCountExe);
															if( $projQuitedEmpCount['quited_emp_count'] < 1 ){
																echo "Project Closed";
															}else{
																echo "On going";
															}
															?>
															
														</div>
													</div>
												</div>
											</div>
										</div><?php
									}
								} ?>
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