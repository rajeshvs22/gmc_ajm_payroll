<?php
include('../header.php'); 

$proj_name = $proj_loc = $proj_end_dt = $proj_desc ="";
if(isset($_GET['proj_id'])){
	$getProjDetsQry = "SELECT * FROM project_details WHERE proj_id=".$_GET['proj_id'];
	$getProjDetsQryExe = mysqli_query($conn,$getProjDetsQry);
	
	if(mysqli_num_rows($getProjDetsQryExe) > 0){
		
		$theProjDetsData = mysqli_fetch_assoc($getProjDetsQryExe);
		
		$proj_name = $theProjDetsData['proj_name'];
		$proj_loc = $theProjDetsData['proj_location'];
		
		$proj_end_dt = $theProjDetsData['proj_end_date'];
		$proj_end_dt = date('d-m-Y',strtotime($proj_end_dt));
				
		$proj_desc = $theProjDetsData['proj_desc'];
	}
	
}



?>


<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<!--- breadcrumb section --->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Add Projects</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Add Projects</li>
					</ul>
				</div>
			</div>
		</div>
		<!--- END: breadcrumb section --->
		
		
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<form action="" method="POST" id="project_add_form" enctype="multipart/form-data">
						<div class="card-header">
							<h4 class="card-title mb-0">Project Details</h4>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-xl-6">
									<div class="form-group">
										<label class="needs-validation">Project Name *</label>
										<input type="text" class="form-control" name="proj_name" id="proj_name" value="<?= $proj_name ?>">
									</div>
								</div>
								
								<div class="col-xl-6">
									<div class="form-group">
										<label class="needs-validation">Project Location *</label>
										<input type="text" class="form-control" name="proj_loc" id="proj_loc" value="<?= $proj_loc ?>">
									</div>
								</div>
								
								<div class="col-xl-6">
									<div class="form-group">
										<label class="needs-validation">Project End Date *</label>
										<input type="text" class="form-control datetimepicker" name="proj_end_dt" id="proj_end_dt" value="<?php echo $proj_end_dt ?>">
									</div>
								</div>
								
								<div class="col-xl-6">
									<div class="form-group">
										<label class="needs-validation">Project Description </label>
										<textarea class="form-control" name="proj_desc" id="proj_desc"><?= $proj_desc ?></textarea>
									</div>
								</div>
							</div>
						</div>
					
						<div class="card-header">
							<h4 class="card-title mb-0">Employees</h4>
						</div>
						
						<div class="text-right mt-3 mb-5">
							<a class="dropdown-item edit_company_btn" href="#" data-toggle="modal" data-target="#add-project"><i class="fa fa-plus m-r-5"></i> Add Employee</a>
						</div>
						
						<div class="container list-of-proj-employees mt-5"><?php
							if(isset($_GET['proj_id'])){
								$getProjEmpDetsQry = "SELECT *,(SELECT emp_name FROM employee WHERE emp_id = ref_emp_id) as emp_name FROM project_employees WHERE ref_proj_id=".$_GET['proj_id'];
								$getProjEmpDetsQryExe = mysqli_query($conn,$getProjEmpDetsQry);
								if(mysqli_num_rows($getProjEmpDetsQryExe) > 0){
									while($theProjEmpDets = mysqli_fetch_assoc($getProjEmpDetsQryExe)){ ?>
										<div class="row row-<?= $theProjEmpDets['ref_emp_id'] ?>">
											<div class="col-xl-5">
												<div class="form-group">
													<input type="text" name="emp[]" id="" class="form-control" value="<?= $theProjEmpDets['emp_name'] ?>" READONLY>
													<input type="hidden" name="emp_id[]" value="<?= $theProjEmpDets['ref_emp_id'] ?>" id="emp_id">
												</div>
											</div>
											<div class="col-xl-2">
												<div class="form-group">
													<input type="text" name="join_dt[]" class="form-control datetimepicker" id="join_dt_<?= $theProjEmpDets['ref_emp_id'] ?>" value="<?= date('d-m-Y',strtotime($theProjEmpDets['emp_joined_date'])); ?>" READONLY>
												</div>
											</div>
											<div class="col-xl-2">
												<div class="form-group">
													<input type="text" name="quit_dt[]" class="form-control datetimepicker" id="quit_dt_<?= $theProjEmpDets['ref_emp_id'] ?>" value="<?php if($theProjEmpDets['emp_quite_date'] != '0000-00-00') echo date('d-m-Y',strtotime($theProjEmpDets['emp_quite_date'])); ?>" READONLY>
												</div>
											</div>
											<div class="col-xl-1">
												<button type="button" class="btn btn-primary proj-emp-edit-btn" data-toggle="modal" data-target="#edit-project" data-proj-id="<?= $theProjEmpDets['ref_proj_id'] ?>" data-ref-emp-id="<?= $theProjEmpDets['ref_emp_id'] ?>">Edit</button>
											</div>
											<div class="col-xl-1">
												<button type="button" class="btn btn-danger proj-emp-remove-btn">Delete</button>
											</div>
										</div><?php
									}
								} 
							} ?>
						</div>
						
						<div class="card-body">
							<?php 
							if(!isset($_GET['proj_id']) && empty($_GET['proj_id'])){ ?>
								<div class="text-right">
									<button type="button" name="button" class="btn btn-primary save-project">Save</button>
								</div><?php
							} ?>
							
							<?php 
							if(isset($_GET['proj_id']) && !empty($_GET['proj_id'])){ ?>
								<input type="hidden" name="ref_proj_id" id="ref_proj_id" value="<?= $_GET['proj_id']; ?>">
								<div class="text-right">
									<button type="button" name="button" class="btn btn-primary update-project">Update</button>
								</div><?php
							} ?>
						</div>
						
					</form>
				</div>
			</div>
		</div>
		
		<div class="modal custom-modal fade" id="add-project" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<form id="add_proj_emp_fm" method="POST">
							<div class="row">
								<div class="col-md-12">
										<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-employee-added" style="display:none">Employee Added Sucessfully
											<button type="button" class="close" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
										<div class="alert alert-danger alert-dismissible fade show small" role="alert" id="error-employee-busy" style="display:none">Employee working in project
											<button type="button" class="close" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									<div class="form-group">
										<label class="col-form-label">Employee Name <span class="text-danger">*</span></label>
										<select name="emp_name" id="emp_name" class="select"><?php
										if(empty($employee_name )){ ?>
											<option value="" disabled SELECTED>Select</option><?php
										}
										$getAllEmpQry = "SELECT * FROM employee WHERE work_status =".$work_status." AND employe_status = 0";
										$getAllEmpQryExe = mysqli_query($conn, $getAllEmpQry);
										if(mysqli_num_rows($getAllEmpQryExe) > 0){
											while($theAllEmpQryExe = mysqli_fetch_assoc($getAllEmpQryExe)){ ?>
												<option value="<?= $theAllEmpQryExe['emp_id'] ?>"><?= $theAllEmpQryExe['emp_name']." - ".$theAllEmpQryExe['emp_code'] ?></option><?php
											}
										} ?>
										</select>
									</div>
									<div class="form-group">
										<label class="col-form-label">Employee Added Date Name <span class="text-danger">*</span></label>
										<input class="form-control emp_add_dt datetimepicker" type="text" name="emp_add_dt" id="emp_add_dt">
									</div>
									<div class="form-group">
										<label class="col-form-label">Employee Quit Date<span class="text-danger">*</span></label>
										<input class="form-control emp_quit_dt datetimepicker" type="text" name="emp_quit_dt" id="emp_quit_dt" >
									</div>
								</div>
								
							</div>
							<div class="text-right">
									<button type="button" name="submit" value="submit" class="btn btn-primary add_proj_emp_btn">ADD</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		
		<div class="modal custom-modal fade" id="edit-project" role="dialog">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<form id="add_proj_emp_fm" method="POST">
							<div class="row">
								<div class="col-md-12">
										<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-employee-added" style="display:none">Employee Added Sucessfully
											<button type="button" class="close" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
										<div class="alert alert-danger alert-dismissible fade show small" role="alert" id="error-employee-exist" style="display:none">Employee Already exists
											<button type="button" class="close" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">×</span>
											</button>
										</div>
									<div class="form-group">
										<label class="col-form-label">Employee Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text" name="edit_emp_name" id="edit_emp_name" READONLY>
										
									</div>
									<div class="form-group">
										<label class="col-form-label">Employee Added Date Nameq <span class="text-danger">*</span></label>
										<input class="form-control emp_add_dt datetimepicker" type="text" name="edit_emp_add_dt" id="edit_emp_add_dt">
									</div>
									<div class="form-group">
										<label class="col-form-label">Employee Quit Date<span class="text-danger">*</span></label>
										<input class="form-control emp_quit_dt datetimepicker" type="text" name="edit_emp_quit_dt" id="edit_emp_quit_dt" >
									</div>
								</div>
								
							</div>
							<div class="text-right">
								<input type="hidden" name="" id="edit_emp_id">
								<input type="hidden" name="row-id" id="row_id">
								<input type="hidden" name="proj_id" id="proj_id">
								<button type="button" name="submit" value="submit" class="btn btn-primary edit_proj_emp_btn">Save</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<span class="loader" style="display: none;"></span>

<?php
require '../footer.php'; ?>

<script  src="<?php echo WEB_URL; ?>assets/js/c_projects.js"></script>
    </body>
</html>