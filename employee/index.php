<?php

include('../header.php');
if(isset($_SESSION['employee_operation'])){
	if($_SESSION['employee_operation'] == 1){
		echo "inserted";
		$_SESSION['employee_operation'] = 0;
	}else if($_SESSION['employee_operation'] == 2){
		echo "updated";
		$_SESSION['employee_operation'] = 0;
	}else if($_SESSION['employee_operation'] == 3){
		echo "deleted";
		$_SESSION['employee_operation'] = 0;
	}
}

//FILTER OPTION
$dep = $emp_name = $cmpy = $emp_code = $condition = $company ="";

if(isset($_GET['dep']) && !empty($_GET['dep'])){
	$dep = $_GET['dep'];
	$condition .= 'AND division = '.$dep;
}
if(isset($_GET['emp_name']) && !empty($_GET['emp_name'])){
	$emp_name = $_GET['emp_name'];
	$condition .= "AND emp_name LIKE '%".$emp_name."%'";
}
if(isset($_GET['cmpy']) && !empty($_GET['cmpy'])){
	$company = $_GET['cmpy'];
	$condition .= 'AND ref_comp_id = '.$company;
}
if(isset($_GET['emp_code']) && !empty($_GET['emp_code'])){
	$emp_code = $_GET['emp_code'];
	$condition .= 'AND emp_code = '.$emp_code;
}


	
?>
<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<div class="page-header">
			<div class="row align-items-center">
				<div class="col">
					<h3 class="page-title">Employee List</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Employee List</li>
					</ul>
				</div>
				<div class="col-auto float-right ml-auto">
					<a href="<?= WEB_URL ?>employee/add_employee.php" class="btn add-btn"><i class="fa fa-plus"></i> Add Employee</a>
				</div>
				<div class="dropdown dropdown-action">
					<a href="#" class="btn dropdown-toggle add-btn bulk_action" data-toggle="dropdown" aria-expanded="false"><i class="la la-edit"></i>Bulk Action</a>
					<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(103px, 32px, 0px);">
						<a class="dropdown-item" href="" data-toggle="modal" data-target="#import_employee"><i class="fa fa-upload m-r-5"></i> Import</a>
						<a class="dropdown-item" href="<?= WEB_URL ?>import_stucture.xlsx"><i class="fa fa-download m-r-5"></i>Download Structure</a>
						
					</div>
				</div>
			</div>
		</div>
		
		<form action="" method="GET">
		<div class="row filter-row">
			
				<div class="col-sm-6 col-md-3"> 
					<div class="form-group form-focus select-focus">
					
						<select class="select select-company" name="cmpy"> 
							<option value="" <?php if(empty($company)){ echo "Selected"; } ?>>Select Company</option><?php
							$getAllCmpyQry = "SELECT * FROM company_master WHERE 1=1";
							$qryExe = mysqli_query($conn, $getAllCmpyQry); 
							if(mysqli_num_rows($qryExe) > 0){
								$sl_no = 1;
								while($cmpy = mysqli_fetch_assoc($qryExe)){ ?>
									<option value="<?= $cmpy['comp_id'] ?>" <?php if($company == $cmpy['comp_id']){ echo "SELECTED"; } ?>><?= $cmpy['company_name'] ?></option><?php
								}
							} ?>
						</select>
						<label class="focus-label">Company</label>
					</div>
				</div>
				<div class="col-sm-6 col-md-3">  
					<div class="form-group form-focus">
						<input type="text" class="form-control floating" name="emp_code" value=<?= $emp_code ?>>
						<label class="focus-label">Employee CODE</label>
					</div>
				</div>
				<div class="col-sm-6 col-md-3">    
					<div class="form-group form-focus">
						<input type="text" class="form-control floating" name="emp_name" value="<?= $emp_name ?>">
						<label class="focus-label">Employee Name</label>
					</div>
				</div>
				<div class="col-sm-6 col-md-3">   
					<div class="form-group form-focus select-focus">
						<select class="select select-department" name="dep"> 
							<option value="" <?php if(empty($dep)){ echo "Selected"; } ?>>Select Department</option><?php
							$getAllDepQry = "SELECT * FROM department_master WHERE 1=1";
							$qryExe = mysqli_query($conn, $getAllDepQry); 
							if(mysqli_num_rows($qryExe) > 0){
								$sl_no = 1;
								while($deps = mysqli_fetch_assoc($qryExe)){ ?>
									<option value="<?= $deps['dep_id'] ?>" <?php if($dep == $deps['dep_id']){ echo "SELECTED"; } ?>><?= $deps['department_name'] ?></option><?php
								}
							} ?>
						</select>
						<label class="focus-label">Designation</label>
					</div>
				</div>
				<div class="col-sm-6 col-md-3">  
					<button type="submit" class="btn btn-success btn-block m-b-20"> Search </button>  
				</div> 
			
		</div>
		</form>
		<!-- /Search Filter -->
		
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped custom-table datatable-no-sorting" id="datatable-no-sorting2">
						<thead>
							<tr>
								<th>S.No</th>
								<th>Company</th>
								<th>Name</th>
								<th>Employee Code</th>
								<th>Department</th>
								<th>Position </th>
								<th class="text-right no-sort">Action</th>
							</tr>
						</thead>
						<tbody><?php
							$getAllEmpQry = "SELECT e.*, (SELECT department_name FROM  department_master WHERE dep_id =e.division) as dep_name, (SELECT position_name FROM position_master WHERE position_id = e.position) as position_name,(SELECT company_name FROM company_master WHERE comp_id  = e.ref_comp_id) as company_name FROM employee e WHERE work_status = '$work_status' AND 1=1 AND employe_status = 0 $condition";
							
							
							$qryExe = mysqli_query($conn, $getAllEmpQry); 
							if(mysqli_num_rows($qryExe) > 0){
								$sl_no = 1;
								while($row = mysqli_fetch_assoc($qryExe)){ ?>
									<tr>
										<td><?= $sl_no ?></td>
										<td><?= $row['company_name'] ?></td>
										<td><?= $row['emp_name'] ?></td>
										<td><?= $row['emp_code'] ?></td>
										<td><?= $row['dep_name'] ?></td>
										<td><?= $row['position_name'] ?></td>
										<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
													<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(103px, 32px, 0px);">
														<a class="dropdown-item" href="<?= WEB_URL ?>employee/add_employee.php?eid=<?=$row['emp_id'] ?>" ><i class="fa fa-pencil m-r-5"></i> Edit</a>
														<a class="dropdown-item" href="<?= WEB_URL ?>employee/view_employee.php?eid=<?=$row['emp_id'] ?>" ><i class="fa fa-eye m-r-5"></i> View</a>
														<a class="dropdown-item" href="<?= WEB_URL ?>employee/add_increment.php?eid=<?=$row['emp_id'] ?>" ><i class="fa fa-money m-r-5"></i> Increment</a>
														<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_employee" data-emp_id="<?= $row['emp_id'] ?>" id="delete_emp"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
													</div>
												</div>
											</td>
									</tr><?php
									$sl_no++;
								}
							} ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	
	<!--- Import model -->
	<div class="modal custom-modal fade" id="import_employee" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<form id="import_employees_fm" method="POST" enctype="multipart/form-data" novalidate>
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="alert alert-success alert-dismissible fade show small" role="alert" id="import-in-progress" style="display:none">File import is in progress. please wait..
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						
						<div class="alert alert-success alert-dismissible fade show small" role="alert" id="import-complete" style="display:none">File imported sucessfully.
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="alert alert-danger alert-dismissible fade show small" role="alert" id="import-error" style="display:none">File missing / Somthing Went Wrong, Try Again..
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">×</span>
							</button>
						</div>
						<div class="row">
							<div class="col-md-12 mt-4 mb-4">
								<input type="file" class="form-control form-control-sm" id="emp-list" name="import-file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
								<span class="emp-list-err err"></span>
							</div>
							<div class="col-md-12">
								<button type="submit" name="submit" value="submit" class="btn btn-primary mt-4 action-btn">Import</button>	
							</div>
						</div>
						
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<!---Delet model---->
	<div class="modal custom-modal fade" id="delete_employee" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<form id="delet_emp_fm" method="POST">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-delet" style="display:none">Employee Deleted Sucessfully
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
							<h3>Delete Employee</h3>
							<p>Are you sure want to delete?</p>
						</div>
						<div class="modal-btn delete-action">
							<div class="row">
								<div class="col-6">
									<input type="hidden" name="delet_emp_ref_id" value="" id="delet_emp_ref_id">
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

<script  src="<?php echo WEB_URL; ?>assets/js/c_add_employee.js"></script>
<script>
	$(document).ready( function() {
		$('#datatable-no-sorting2').DataTable({
			"ordering": false
		});
	})
</script>
    </body>
</html>