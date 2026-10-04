<?php

include('../header.php');

$division='';
?>

<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<!--- breadcrumb section --->
		<div class="page-header">
			<div class="row align-items-center">
				<div class="col">
					<h3 class="page-title">Position Master</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Position Master</li>
					</ul>
				</div>
				<div class="col-auto float-right ml-auto">
					<a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_position"><i class="fa fa-plus"></i> Add Position</a>
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
									<th>Position</th>
									<th>Action</th>
								</tr>
							</thead><?php
							$getAllPositionQry = "SELECT *, department_name FROM position_master INNER JOIN department_master ON ref_dep_id = dep_id ";
							$qryExe = mysqli_query($conn, $getAllPositionQry); 
							$i=1;
							if(!empty($qryExe) && mysqli_num_rows($qryExe) > 0){ 
								while($row = mysqli_fetch_assoc($qryExe)){ ?>
									<tr>
										<td><?= $i ?></td>
										<td><?= $row['position_id'] ?></td>
										<td><?= $row['department_name'] ?></td>
										<td><?= $row['position_name'] ?></td>
										<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="material-icons">more_vert</i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item edit_position_btn" href="#" data-toggle="modal" data-target="#edit_position" data-dep-id="<?= $row['ref_dep_id'] ?>" data-position-id="<?= $row['position_id'] ?>" data-position-name="<?= $row['position_name'] ?>" id="edit_position_btn"><i class="fa fa-pencil m-r-5"></i> Edit</a>
														<a class="dropdown-item delete_position" href="#" data-toggle="modal" data-target="#delete_position" data-position-id="<?= $row['position_id'] ?>" id="delete_position_btn"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
	<div class="modal custom-modal fade" id="add_position" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-body">
					<form id="position_master_fm" method="POST">
						<div class="row">
							<div class="col-md-12">
									<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-position-added" style="display:none">Position Added Sucessfully
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="alert alert-danger alert-dismissible fade show small" role="alert" id="error-position-exist" style="display:none">Position Already exists
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									
								<div class="form-group">
									<label  >Department *</label>
									
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
								
								<div class="form-group">
									<label class="col-form-label">Position Name <span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="position_name" id="position_name" class="position_name">
									<input type="hidden" name="ref_comp_id" id="ref_comp_id" value="<?= $ref_comp_id ?>">
									<input type="hidden" name="action" id="action" value="add-position">
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
	<div class="modal custom-modal fade" id="edit_position" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-body">
					<form id="position_master_edit_fm" method="POST">
						<div class="row">
							<div class="col-md-12">
									<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-position-updated" style="display:none">Position Updated Sucessfully
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="alert alert-danger alert-dismissible fade show small" role="alert" id="error-position-exist-onedit" style="display:none">Position Already exists
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									
								<div class="form-group">
									<label  >Department *</label>
									
									<select name="division-edit" id="division-edit" class="select"><?php
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
								
								<div class="form-group">
									<label class="col-form-label">Position Name <span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="position_name_edit" id="position_name_edit" class="position_name">
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
	<div class="modal custom-modal fade" id="delete_position" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<form id="delet_position_fm" method="POST">
					<div class="modal-body">
						<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-delet" style="display:none">Position Deleted Sucessfully
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
							<h3>Delete Position</h3>
							<p>Are you sure want to delete?</p>
						</div>
						<div class="modal-btn delete-action">
							<div class="row">
								<div class="col-6">
									<input type="hidden" name="delet_position_ref_id" value="" id="delet_position_ref_id">
									<button type="submit" class="btn btn-primary continue-btn" class="delete_position_btn">Delete</button>
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