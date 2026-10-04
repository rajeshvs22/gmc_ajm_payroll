<?php

include('../header.php');

$company = "";

$bonus_amount = $leave_salary = '';

if(isset($_GET['cmpy'])){
	$company = $_GET['cmpy'];
}

?>

<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<!--- breadcrumb section --->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Leave Salary & Bonus </h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Leave Salary & Bonus</li>
					</ul>
				</div>
				<div class="col-auto float-right ml-auto">
					<a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_leave_salary_bonus"><i class="fa fa-plus"></i> Add Leave Salary & Bonus</a>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped custom-table datatable-no-sorting" id="datatable-no-sorting2">
						<thead>
							<tr>
								<th>Sl.No</th>
								<th>Company</th>
								<th>Employee Name</th>
								<th>Employee Code</th>
								<th>Bonus</th>
								<th>Leave Salary</th>
								<th>Month</th>
								<th>Year</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody><?php
							$sl_no=1;
							$getLvsalBonusQry = "SELECT lsb.*, (SELECT company_name FROM company_master WHERE comp_id=lsb.ref_comp_id) as company_name, (SELECT emp_name FROM employee WHERE emp_id=lsb.ref_emp_id) as emp_name, (SELECT emp_code FROM employee WHERE emp_id=lsb.ref_emp_id) as emp_code FROM leave_sal_bonus lsb";
							//echo $getLvsalBonusQry;
							$getLvsalBonusQryExe = mysqli_query($conn, $getLvsalBonusQry);
							if(mysqli_num_rows($getLvsalBonusQryExe) > 0){
								while($theLvsalBonusQry = mysqli_fetch_assoc($getLvsalBonusQryExe)){ ?>
									<tr>
										<td><?= $sl_no ?></td>
										<td><?= $theLvsalBonusQry['company_name'] ?></td>
										<td><?= $theLvsalBonusQry['emp_name'] ?></td>
										<td><?= $theLvsalBonusQry['emp_code'] ?></td>
										<td><?= $theLvsalBonusQry['bonus'] ?></td>
										<td><?= $theLvsalBonusQry['leave_Salary'] ?></td>
										<td><?= $theLvsalBonusQry['month'] ?></td>
										<td><?= $theLvsalBonusQry['year'] ?></td>
										<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="material-icons">more_vert</i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item edit_lvsal_bonus_btn" href="#" data-toggle="modal" data-target="#edit_lvsal_bonus" data-bonus-id="<?= $theLvsalBonusQry['bonus_id'] ?>" data-ref-emp-id="<?= $theLvsalBonusQry['ref_emp_id'] ?>" id="edit_lvsal_bonus_btn"><i class="fa fa-pencil m-r-5"></i> Edit</a>
														<a class="dropdown-item delete_lvsal_bonus" href="#" data-toggle="modal" data-target="#delete_lvsal_bouns" data-bonus-id="<?= $theLvsalBonusQry['bonus_id'] ?>" id="delete_lvsal_bonus_btn"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
													</div>
												</div>
											</td>
										
									</tr><?php
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
	
	<div class="modal custom-modal fade" id="add_leave_salary_bonus" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="add_lv_salary_bonus_form" method="POST">
						<div class="row">
							<div class="col-md-12">
								<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-lv_salary_bonus" style="display:none">Leave Salary & Bonus Added Sucessfully
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">×</span>
									</button>
								</div>
								<div class="alert alert-danger alert-dismissible fade show small" role="alert" id="error-lv_salary_bonus-exist" style="display:none">Already Leave Salary & Bonus Added
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">×</span>
									</button>
								</div>
								<div class="form-group">
									<div class="form-group">
										<label  >Employee *</label>
										<select name="ref_emp_id" id="ref_emp_id" class="select">
											<option selected disabled>Select Employee *</option><?php
											$getEmployeesQry = "SELECT * FROM employee WHERE work_status = ".$_SESSION['work_status']." AND ref_comp_id = ".$company." AND employe_status = 0";
												$getEmployeesQryExe = mysqli_query($conn,$getEmployeesQry);
												if(mysqli_num_rows($getEmployeesQryExe) > 0){
													while($theEmpData = mysqli_fetch_assoc($getEmployeesQryExe)){ ?>
														<option value="<?= $theEmpData['emp_id'] ?>"><?= $theEmpData['emp_name']." - ".$theEmpData['emp_code'] ?></option><?php
													}
												}
											?>
										</select>
									</div>
								</div>
							
								<div class="form-group">
									<label>Bonus Amount</label>
									<input type="text" class="form-control" name="bonus_amount" id="bonus_amount" value="0">
								</div>
								
								<div class="form-group">
									<label>Leave Salary</label>
									<input type="text" class="form-control" name="leave_salary" id="leave_salary" value="0">
								</div>
							
								</div>
							<div class="text-right">
								<input type="hidden" name="cmpy" id="cmpy" value="<?= $_GET['cmpy'] ?>">
								<input type="hidden" name="month" id="month" value="<?= $_GET['month'] ?>">
								<input type="hidden" name="year" id="year" value="<?= $_GET['year'] ?>">
								<button type="submit" name="submit" value="submit" class="btn btn-primary">Save</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	
	<div class="modal custom-modal fade" id="edit_lvsal_bonus" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="edit_lv_salary_bonus_form" method="POST">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-12">
										<div class="alert alert-success alert-dismissible fade show small" role="alert" id="edit-sucess-lv_salary_bonus" style="display:none">Leave Salary & Bonus Updated Sucessfully
											<button type="button" class="close" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
										
										
										<div class="form-group">
											<div class="form-group">
												<label  >Employee *</label>
												<select name="edit_ref_emp_id" id="edit_ref_emp_id" class="select" disabled>
													<option selected disabled>Select Employee *</option><?php
													$getEmployeesQry = "SELECT * FROM employee WHERE work_status = ".$_SESSION['work_status']." AND ref_comp_id = ".$company;
														$getEmployeesQryExe = mysqli_query($conn,$getEmployeesQry);
														if(mysqli_num_rows($getEmployeesQryExe) > 0){
															while($theEmpData = mysqli_fetch_assoc($getEmployeesQryExe)){ ?>
																<option value="<?= $theEmpData['emp_id'] ?>"><?= $theEmpData['emp_name']." - ".$theEmpData['emp_code'] ?></option><?php
															}
														}
													?>
												</select>
											</div>
										</div>
							
										<div class="form-group">
											<label>Bonus Amount</label>
											<input type="text" class="form-control" name="edit_bonus_amount" id="edit_bonus_amount" value="0">
										</div>
										
										<div class="form-group">
											<label>Leave Salary</label>
											<input type="text" class="form-control" name="edit_leave_salary" id="edit_leave_salary" value="0">
										</div>
								
									</div>
							
								</div>
								<div class="text-right">
									<input type="hidden" name="ref_bonus_id" id="ref_bonus_id">
									<button type="submit" name="submit" value="submit" class="btn btn-primary">Update</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!---Delet model---->
	<div class="modal custom-modal fade" id="delete_lvsal_bouns" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<form id="delet_lvsal_bonus_fm" method="POST">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-delet" style="display:none">Leave Salary & Bonus Deleted Sucessfully
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
							<h3>Delete Leave Salary & Bonus</h3>
							<p>Are you sure want to delete?</p>
						</div>
						<div class="modal-btn delete-action">
							<div class="row">
								<div class="col-6">
									<input type="hidden" name="delet_levsal_bonus_id" value="" id="delet_levsal_bonus_id">
									<button type="submit" class="btn btn-primary continue-btn" class="delete_company_btn">Delete</button>
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
<script>
	$(document).ready( function() {
		$('#datatable-no-sorting2').DataTable({
			"ordering": false
		});
	})
</script>
    </body>
</html>