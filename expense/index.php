<?php
include('../header.php');
?>

<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<div class="page-header">
			<div class="row align-items-center">
				<div class="col">
					<h3 class="page-title">Employee Expenses</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Employee Expenses</li>
					</ul>
				</div>
				<div class="col-auto float-right ml-auto">
					<a href="<?php echo WEB_URL ?>expense/add_expense.php" class="btn add-btn"><i class="fa fa-plus"></i> Add Expenses</a>
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
								<th>Company</th>
								<th>Employee Name</th>
								<th>Employee Code</th>
								<th>Expense Type</th>
								<th>Expense Date</th>
								<th>Expense Amount</th>
								<th>Action</th>
							</tr>
						</thead><?php
						$getExpenseTypesdQry = "SELECT ee.*,(SELECT company_name FROM company_master WHERE comp_id=(SELECT ref_comp_id FROM employee WHERE emp_id=ee.ref_emp_id)) as company_name, (SELECt expense_name FROM expense_type_master WHERE expense_type_id=ref_expense_type_id) as expense_name, (SELECT emp_name FROM employee WHERE emp_id=ee.ref_emp_id) as emp_name, (SELECT emp_code FROM employee WHERE emp_id=ee.ref_emp_id) as emp_code FROM employee_expense ee";
						$getExpenseTypesdQryExe = mysqli_query($conn, $getExpenseTypesdQry); 
						$i=1;
						if(!empty($getExpenseTypesdQryExe) && mysqli_num_rows($getExpenseTypesdQryExe) > 0){ 
							while($row = mysqli_fetch_assoc($getExpenseTypesdQryExe)){ ?>
								<tr>
									<td><?= $i ?></td>
									<td><?= $row['company_name'] ?></td>
									<td><?= $row['emp_name'] ?></td>
									<td><?= $row['emp_code'] ?></td>
									<td><?= $row['expense_name'] ?></td>
									<td><?= date('d-m-Y', strtotime($row['expense_date'])); ?></td>
									<td><?= $row['expense_amount'] ?></td>
									<td class="text-right">
											<div class="dropdown dropdown-action">
												<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
												<div class="dropdown-menu dropdown-menu-right">
													
													<a class="dropdown-item" href="<?= WEB_URL ?>expense/add_expense.php?expense_id=<?=$row['expense_id'] ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
													
													<a class="dropdown-item delete_expense" href="#" data-toggle="modal" data-target="#delete_expense" data-expense-id="<?= $row['expense_id']; ?>" id="delete_expense_btn"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
												</div>
											</div>
										</td>
								</tr><?php
							}
						} ?>
					</table>
				</div>
			</div>
		</div>
	</div>
	
	<!---Delet model---->
	<div class="modal custom-modal fade" id="delete_expense" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<form id="delet_expense_fm" method="POST">
					<div class="modal-body">
						<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-delet" style="display:none">Expense Deleted Sucessfully
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
							<h3>Delete Expense</h3>
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
								


<?php
require '../footer.php'

?>
<script  src="<?php echo WEB_URL; ?>assets/js/c_expense.js"></script>
    </body>
</html>