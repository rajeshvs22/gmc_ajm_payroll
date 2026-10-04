<?php

include('../header.php');


?>

<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<!--- breadcrumb section --->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Salary List</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Salary List</li>
					</ul>
				</div>
			</div>
		</div> 
		
		<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-close-salary" style="display:none">Salary generate is closed
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">×</span>
			</button>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				
					<div class="table-responsive">
						<table class="table table-striped custom-table ">
							<thead>
								<tr>
									<th>Sl.No</th>
									<th>Company</th>
									<th>Month</th>
									<th>Year</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody> <?php
							$getAttendanceQry = "SELECT ea.*,ep.payroll_id,(SELECT company_name FROM company_master WHERE comp_id = ea.ref_comp_id) as company_name   FROM employee_attendance ea INNER JOIN employee_payroll ep ON ea.attendance_id  = ep.attendance_id GROUP BY ea.month, ea.year, ea.ref_comp_id ORDER BY ea.year DESC, ea.month+0 DESC";
							$qryExe = mysqli_query($conn, $getAttendanceQry); 
							if(mysqli_num_rows($qryExe) > 0){ 
								$sl_no = 1;
								while($row = mysqli_fetch_assoc($qryExe)){ ?>
									<tr>
										<td><?= $sl_no ?></td>
										<td><?= $row['company_name']; ?></td>
										<td><?= $row['month']; ?></td>
										<td><?= $row['year']; ?></td>
										<td class="text-right">
											<div class="dropdown dropdown-action">
												<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
												<div class="dropdown-menu dropdown-menu-right"><?php 
												$cmpy = $row['ref_comp_id'];
												/*
													$getAllCmpyQry = "SELECT * FROM company_master WHERE 1=1 LIMIT 1";
													$getAllCmpyQryExe = mysqli_query($conn, $getAllCmpyQry); 
													if(mysqli_num_rows($getAllCmpyQryExe) > 0){
													$cmpys = mysqli_fetch_assoc($getAllCmpyQryExe); 
													$cmpy = $cmpys['comp_id'];
													} */
													
													$getPayrollQry = "SELECT employee_payroll_close_id FROM employee_payroll_close WHERE month = '".$row['month']."' AND year='".$row['year']."' AND company_id='".$cmpy."' AND payroll_status='1'";
													$getPayrollQryExe = mysqli_query($conn, $getPayrollQry); 
													$salary_close_cls = '';
													$salary_close_css = 'color:#ccc';
													if(mysqli_num_rows($getPayrollQryExe) == 0){ 
														$salary_close_cls = 'cloase_salary';
														$salary_close_css = '';
													}
													
													
													?>
													<a class="dropdown-item" href="<?= WEB_URL ?>payroll/salary_report.php?cmpy=<?= $cmpy ?>&month=<?= $row['month']; ?>&year=<?= $row['year']; ?>"><i class="fa fa-eye m-r-5"></i> View</a>
													<a class="dropdown-item" href="<?= WEB_URL ?>payroll/generate_salary.php?cmpy=<?= $cmpy ?>&month=<?= $row['month']; ?>&year=<?= $row['year']; ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
													
													<a class="dropdown-item" href="<?= WEB_URL ?>payroll/export_salary.php?cmpy=<?= $cmpy ?>&month=<?= $row['month']; ?>&year=<?= $row['year']; ?>"><i class="fa fa-pencil m-r-5"></i> Export Salary</a>
													
													<a class="dropdown-item" href="<?= WEB_URL ?>payroll/payslip.php?cmpy=<?= $cmpy ?>&month=<?= $row['month']; ?>&year=<?= $row['year']; ?>"><i class="fa fa-pencil m-r-5"></i> Payslip</a>
													
													<a class="dropdown-item" href="<?= WEB_URL ?>payroll/leave_bonus_salry.php?cmpy=<?= $cmpy ?>&month=<?= $row['month']; ?>&year=<?= $row['year']; ?>"><i class="fa fa-pencil m-r-5"></i> Leave Salary/Bonus</a>
													
													<a class="dropdown-item <?php echo $salary_close_cls; ?>" href="#"  data-month="<?= $row['month']; ?>" data-year="<?= $row['year']; ?>" 
													data-cmpy="<?= $cmpy ?>" style="<?php echo $salary_close_css; ?>"><i class="fa fa-pencil m-r-5"></i> Close Salare</a>
													
													<a class="dropdown-item delete_salary" href="#" data-toggle="modal" data-target="#delete_salary" data-month="<?= $row['month']; ?>" data-year="<?= $row['year']; ?>" 
													data-cmpy="<?= $cmpy ?>" id="delete_salary_btn"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
													
													
												</div>
											</div>
										</td><?php
										$sl_no++;
								}
							}
							?>
									
							</tbody>
						</table>
					</div>
			</div>
		</div> 
	</div>
	
	<!---Delet model---->
	<div class="modal custom-modal fade" id="delete_salary" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<form id="delet_salary_fm" method="POST">
					<div class="modal-body">
						<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-delet" style="display:none">Salary Deleted Sucessfully
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						
						<div class="alert alert-danger alert-dismissible fade show small" role="alert" id="delet-error" style="display:none">Somthing Went Wrong, Try Again..
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="form-header1">
							<h3>Delete Salary</h3>
							<p>Are you sure want to delete?</p>
						</div>
						<div class="modal-btn delete-action">
							<div class="row">
								<div class="col-6">
									<input type="hidden" name="delet_salary_month" value="" id="delet_salary_month">
									<input type="hidden" name="delet_salary_year" value="" id="delet_salary_year">
									<input type="hidden" name="delet_salary_cmpy" value="" id="delet_salary_cmpy">
									<button type="submit" class="btn btn-primary continue-btn" class="delete_salary_btn">Delete</button>
								</div>
								<div class="col-6">
									<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<?php
require '../footer.php'

?>

<script  src="<?php echo WEB_URL; ?>assets/js/c_generate_salary.js"></script>
    </body>
</html>