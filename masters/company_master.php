<?php

include('../header.php');


?>

<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<div class="page-header">
			<div class="row align-items-center">
				<div class="col">
					<h3 class="page-title">Company Master</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Company Master</li>
					</ul>
				</div>
				<div class="col-auto float-right ml-auto">
					<a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_company"><i class="fa fa-plus"></i> Add Company</a>
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
									<th>Company Name</th>
									<th>Action</th>
								</tr>
							</thead><?php
							$getAllCompaniesQry = "SELECT * FROM company_master WHERE visibility = 1";
							$qryExe = mysqli_query($conn, $getAllCompaniesQry); 
							$i=1;
							if(!empty($qryExe) && mysqli_num_rows($qryExe) > 0){ 
								while($row = mysqli_fetch_assoc($qryExe)){ ?>
									<tr>
										<td><?= $i ?></td>
										<td><?= $row['comp_id'] ?></td>
										<td><?= $row['company_name'] ?></td>
										<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="material-icons">more_vert</i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item edit_company_btn" href="#" data-toggle="modal" data-target="#edit_company" data-company-id="<?= $row['comp_id'] ?>" data-company-name="<?= $row['company_name'] ?>" id="edit_company_btn"><i class="fa fa-pencil m-r-5"></i> Edit</a>
														<a class="dropdown-item delete_company" href="#" data-toggle="modal" data-target="#delete_company" data-company-id="<?= $row['comp_id'] ?>" id="delete_position_btn"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
	<div class="modal custom-modal fade" id="add_company" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-body">
					<form id="add_company_master_fm" method="POST">
						<div class="row">
							<div class="col-md-12">
									<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-company-added" style="display:none">Company Added Sucessfully
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="alert alert-danger alert-dismissible fade show small" role="alert" id="error-company-exist" style="display:none">Company Already exists
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
								<div class="form-group">
									<label class="col-form-label">Company Name <span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="company_name" id="company_name" class="position_name">
									<input type="hidden" name="action" id="action" value="add-company">
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
	<div class="modal custom-modal fade" id="edit_company" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-body">
					<form id="company_master_edit_fm" method="POST">
						<div class="row">
							<div class="col-md-12">
									<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-company-updated" style="display:none">Company Updated Sucessfully
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="alert alert-danger alert-dismissible fade show small" role="alert" id="error-company-exist-onedit" style="display:none">Company Already exists
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
								<div class="form-group">
									<label class="col-form-label">Company Name <span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="company_name_edit" id="company_name_edit" class="company_name">
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
	<div class="modal custom-modal fade" id="delete_company" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<form id="delet_company_fm" method="POST">
					<div class="modal-body">
						<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-delet" style="display:none">Company Deleted Sucessfully
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
							<h3>Delete Company</h3>
							<p>Are you sure want to delete?</p>
						</div>
						<div class="modal-btn delete-action">
							<div class="row">
								<div class="col-6">
									<input type="hidden" name="delet_company_ref_id" value="" id="delet_company_ref_id">
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
<!-- /Page Wrapper -->


<?php
require '../footer.php'

?>
<script  src="<?php echo WEB_URL; ?>assets/js/c_masters.js"></script>
    </body>
</html>