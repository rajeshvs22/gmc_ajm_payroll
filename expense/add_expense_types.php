<?php

include('../header.php');


?>

<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<div class="page-header">
			<div class="row align-items-center">
				<div class="col">
					<h3 class="page-title">Expense Type Master</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Expense Type Master</li>
					</ul>
				</div>
				<div class="col-auto float-right ml-auto">
					<a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_expense_type"><i class="fa fa-plus"></i> Expense Type Master</a>
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
									<th>Expense Type</th>
									<th>Action</th>
								</tr>
							</thead><?php
							$getExpenseTypesdQry = "SELECT * FROM expense_type_master WHERE visibility = 1";
							$getExpenseTypesdQryExe = mysqli_query($conn, $getExpenseTypesdQry); 
							$i=1;
							if(!empty($getExpenseTypesdQryExe) && mysqli_num_rows($getExpenseTypesdQryExe) > 0){ 
								while($row = mysqli_fetch_assoc($getExpenseTypesdQryExe)){ ?>
									<tr>
										<td><?= $i ?></td>
										<td><?= $row['expense_name'] ?></td>
										<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="material-icons">more_vert</i></a>
													<div class="dropdown-menu dropdown-menu-right">
														<a class="dropdown-item edit_expense_btn" href="#" data-toggle="modal" data-target="#edit_expense" data-expense-id="<?= $row['expense_type_id'] ?>" data-expense-name="<?= $row['expense_name'] ?>" id="edit_expense_btn"><i class="fa fa-pencil m-r-5"></i> Edit</a>
														<a class="dropdown-item delete_expense" href="#" data-toggle="modal" data-target="#delete_expense" data-expense-id="<?= $row['expense_type_id'] ?>" id="delete_expense_btn"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
	<div class="modal custom-modal fade" id="add_expense_type" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="add_expense_type_fm" method="POST">
						<div class="row">
							<div class="col-md-12">
									<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-expense-added" style="display:none">Expense Type Added Sucessfully
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="alert alert-danger alert-dismissible fade show small" role="alert" id="error-expense-exist" style="display:none">Expense Type Already exists
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
								<div class="form-group">
									<label class="col-form-label">Expense Type Name <span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="expense_name" id="expense_name" class="expense_name">
									<input type="hidden" name="action" id="action" value="add-expense">
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
	<div class="modal custom-modal fade" id="edit_expense" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<form id="expense_type_edit_fm" method="POST">
						<div class="row">
							<div class="col-md-12">
									<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-expense-updated" style="display:none">Expense Type Updated Sucessfully
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
									<div class="alert alert-danger alert-dismissible fade show small" role="alert" id="error-expense-exist-onedit" style="display:none">Expense Type Already exists
										<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<span aria-hidden="true">×</span>
										</button>
									</div>
								<div class="form-group">
									<label class="col-form-label">Expense Name <span class="text-danger">*</span></label>
									<input class="form-control" type="text" name="expense_name_edit" id="expense_name_edit" class="expense_name_edit">
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
	<div class="modal custom-modal fade" id="delete_expense" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<form id="delet_expense_type_fm" method="POST">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-delet" style="display:none">Expense Type Deleted Sucessfully
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
							<h3>Delete Expense Type</h3>
							<p>Are you sure want to delete?</p>
						</div>
						<div class="modal-btn delete-action">
							<div class="row">
								<div class="col-6">
									<input type="hidden" name="delet_expense_ref_id" value="" id="delet_expense_ref_id">
									<button type="submit" class="btn btn-primary continue-btn" class="delete_expense_btn">Delete</button>
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
<script  src="<?php echo WEB_URL; ?>assets/js/c_expense.js"></script>
    </body>
</html>