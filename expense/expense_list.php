<?php

include('../header.php');


?>

<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<!--- breadcrumb section --->
		<div class="page-header">
			<div class="row align-items-center">
				<div class="col">
					<h3 class="page-title">Expense List</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Expense List</li>
					</ul>
				</div>
				<div class="col-auto float-right ml-auto">
					<a href="#" class="btn add-btn" data-toggle="modal" data-target="#add_expense_type"><i class="fa fa-plus"></i> Add Expense Type</a>
				</div>
			</div>
		</div> 
		<div class="row">
						<div class="col-md-12">
							<div>
								<table class="table table-striped custom-table mb-0 datatable">
									<thead>
										<tr>
											<th>Date</th>
											<th>Employee Name</th>
											<th>Expense Type</th>
											<th>Amount</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>09/06/2022</td>
											<td>Mani</td>
											<td></td>
											<td>5000</td>
											<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
													<div class="dropdown-menu dropdown-menu-right">
													    <a class="dropdown-item" href="<?= WEB_URL ?>expense/expense_view.php"><i class="fa fa-eye m-r-5"></i> View</a>
														<a class="dropdown-item" href="<?= WEB_URL ?>expense/expense_edit.php"><i class="fa fa-pencil m-r-5"></i> Edit</a>
														<a class="dropdown-item" href="#"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td>07/06/2022</td>
											<td>Kumar</td>
											<td></td>
											<td>1000</td>
											<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
													<div class="dropdown-menu dropdown-menu-right">
													    <a class="dropdown-item" href="<?= WEB_URL ?>expense/expense_view.php"><i class="fa fa-eye m-r-5"></i> View</a>
														<a class="dropdown-item" href="<?= WEB_URL ?>expense/expense_edit.php"><i class="fa fa-pencil m-r-5"></i> Edit</a>
														<a class="dropdown-item" href="#"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
													</div>
												</div>
											</td>
										</tr>
										<tr>
											<td>01/06/2022</td>
											<td>Raj</td>
											<td></td>
											<td>100</td>
											<td class="text-right">
												<div class="dropdown dropdown-action">
													<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
													<div class="dropdown-menu dropdown-menu-right">
													    <a class="dropdown-item" href="<?= WEB_URL ?>expense/expense_view.php"><i class="fa fa-eye m-r-5"></i> View</a>
														<a class="dropdown-item" href="<?= WEB_URL ?>expense/expense_edit.php"><i class="fa fa-pencil m-r-5"></i> Edit</a>
														<a class="dropdown-item" href="#"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
													</div>
												</div>
											</td>
										</tr>
										 
									</tbody>
								</table>
							</div>
						</div>
					</div> 
		
	</div>
	<!-- Add Department Modal -->
				<div id="add_expense_type" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Add Expense Type</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form>
									<div class="form-group">
										<label>Name <span class="text-danger">*</span></label>
										<input class="form-control" type="text">
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Save</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Add Department Modal -->
				
				<!-- Edit Department Modal -->
				<div id="edit_department" class="modal custom-modal fade" role="dialog">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title">Edit Department</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<form>
									<div class="form-group">
										<label>Department Name <span class="text-danger">*</span></label>
										<input class="form-control" value="IT Management" type="text">
									</div>
									<div class="submit-section">
										<button class="btn btn-primary submit-btn">Save</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<!-- /Edit Department Modal -->

				<!-- Delete Department Modal -->
				<div class="modal custom-modal fade" id="delete_department" role="dialog">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-body">
								<div class="form-header">
									<h3>Delete Department</h3>
									<p>Are you sure want to delete?</p>
								</div>
								<div class="modal-btn delete-action">
									<div class="row">
										<div class="col-6">
											<a href="javascript:void(0);" class="btn btn-primary continue-btn">Delete</a>
										</div>
										<div class="col-6">
											<a href="javascript:void(0);" data-dismiss="modal" class="btn btn-primary cancel-btn">Cancel</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /Delete Department Modal -->
</div>


<?php
require '../footer.php'

?>
 
    </body>
</html>