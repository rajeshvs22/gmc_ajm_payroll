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
					<h3 class="page-title"></h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="">Dashboard</a></li>
						<li class="breadcrumb-item active">Department Master</li>
					</ul>
				</div>
			</div>
		</div>
		
		<div class="page-header">
	<div class="row align-items-center">
		<div class="col">
			<h3 class="page-title">Department Master</h3>
			<ul class="breadcrumb">
				<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
				<li class="breadcrumb-item active">Department Master</li>
			</ul>
		</div>
		<div class="col-auto float-right ml-auto">
			<a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_department"><i class="fa fa-plus"></i> Add Department</a>
		</div>
	</div>
</div>
		
		<div class="row">
			<div class="col-md-12">
					<div class="table-responsive">
						<table class="table table-striped custom-table datatable">
							<thead>
								<tr>
									<th>No</th>
									<th>ID</th>
									<th>Department Name</th>
									<th>Action</th>
								</tr>
							</thead><?php
							$getAllDepQry = "SELECT * FROM department_master";
							$qryExe = mysqli_query($conn, $getAllDepQry); 
							$i=1;
							if(mysqli_num_rows($qryExe) > 0){ 
								while($row = mysqli_fetch_assoc($qryExe)){ ?>
									<tr>
										<td><?= $i ?></td>
										<td><?= $row['dep_id'] ?></td>
										<td><?= $row['department_name'] ?></td>
										<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="material-icons">more_vert</i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item edit_department_btn" href="#" data-toggle="modal" data-target="#edit_department" data-dep-id="<?= $row['dep_id'] ?>" data-dep-name="<?= $row['department_name'] ?>" id="edit_department_btn"><i class="fa fa-pencil m-r-5"></i> Edit</a>
														<a class="dropdown-item delete_dep" href="#" data-toggle="modal" data-target="#delete_dep" data-dep-id="<?= $row['dep_id'] ?>" id="delete_dep_btn"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
													</div>
												</div>
											</td>
									</tr><?php
									$i++;
								}
							} ?>
							
						</table>
					</div>
			</div>
		</div>
		
	</div>
	<div class="modal custom-modal fade" id="add_department" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-body">
					<form id="department_master_fm" method="POST">
						<div class="row">
							<div class="col-md-12">
									<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-dep-added" style="display:none">Department Added Sucessfully
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="alert alert-danger alert-dismissible fade show small" role="alert" id="error-dep-exist" style="display:none">Department Already exists
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
								<div class="form-group">
									<label class="col-form-label">Department Name <span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="dep_name" id="dep_name" class="dep_name">
									<input type="hidden" name="ref_comp_id" id="ref_comp_id" value="<?= $ref_comp_id ?>">
									<input type="hidden" name="action" id="action" value="add-department">
								</div>
							</div>
							
						</div>
						<div class="text-right">
							<button type="submit" name="submit" value="submit" class="btn btn-primary">Save</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<!--- Edit Department model --->
	<div class="modal custom-modal fade" id="edit_department" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-body">
					<form id="department_master_edit_fm" method="POST">
						<div class="row">
							<div class="col-md-12">
									<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-dep-updated" style="display:none">Department Updated Sucessfully
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="alert alert-danger alert-dismissible fade show small" role="alert" id="error-dep-exist-onedit" style="display:none">Department Already exists
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
								<div class="form-group">
									<label class="col-form-label">Department Name <span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="dep_name_edit" id="dep_name_edit" class="dep_name">
								</div>
							</div>
							
						</div>
						<div class="text-right">
							<button type="submit" name="submit" value="submit" class="btn btn-primary">Update</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!---Delet model---->
	<div class="modal custom-modal fade" id="delete_dep" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<form id="delet_dep_fm" method="POST">
					<div class="modal-body">
						<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-delet" style="display:none">Department Deleted Sucessfully
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
							<h3>Delete Department</h3>
							<p>Are you sure want to delete?</p>
						</div>
						<div class="modal-btn delete-action">
							<div class="row">
								<div class="col-6">
									<input type="hidden" name="delet_dep_ref_id" value="" id="delet_dep_ref_id">
									<button type="submit" class="btn btn-primary continue-btn" class="delete_dep_btn">Delete</button>
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
<!-- /Page Wrapper -->


<?php
require '../footer.php'

?>
<script  src="<?php echo WEB_URL; ?>assets/js/c_masters.js"></script>
    </body>
</html>