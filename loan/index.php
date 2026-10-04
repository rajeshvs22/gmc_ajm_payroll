<?php

include('../header.php'); ?>


<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<!--- breadcrumb section --->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Loan List</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Loan List</li>
					</ul>
				</div>
			</div>
		</div>
		
		
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped custom-table datatable dataTable no-footer">
						<thead>
							<tr>
								<th>Sl.No</th>
								<th>Company</th>
								<th>Employee Code</th>
								<th>Employee Name</th>
								<th>Date</th>
								<th>Amount</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody> <?php
						$loanDetailsQry = "SELECT *,(SELECT company_name FROM company_master WHERE comp_id=e.ref_comp_id) as company_name, e.emp_name,e.emp_code FROM loan_details ld INNER JOIN employee e ON ld.ref_emp_id = e.emp_id";
						$loanDetailsQryExe = mysqli_query($conn, $loanDetailsQry);
						if(mysqli_num_rows($loanDetailsQryExe) > 0){
							$sl_no = 1;
							while($row = mysqli_fetch_assoc($loanDetailsQryExe)){ ?>
								<tr>
									<td><?= $sl_no ?></td>
									<td><?= $row['company_name']; ?></td>
									<td><?= $row['emp_code']; ?></td>
									<td><?= $row['emp_name']; ?></td>
									<td><?= date('d-m-Y',strtotime($row['loan_date'])); ?></td>
									<td><?= $row['loan_amount']; ?></td>
									<td class="text-right">
											<div class="dropdown dropdown-action">
												<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
												<div class="dropdown-menu dropdown-menu-right">
													
													<a class="dropdown-item" href="<?= WEB_URL ?>loan/add_loan.php?loan_id=<?=$row['loan_id'] ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
													
													<a class="dropdown-item delete_loan" href="#" data-toggle="modal" data-target="#delete_loan" data-loan-id="<?= $row['loan_id']; ?>" id="delete_loan_btn"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
	
	<!---Delet model---->
	<div class="modal custom-modal fade" id="delete_loan" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<form id="delet_loan_fm" method="POST">
					<div class="modal-body">
						<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-delet" style="display:none">Loan Deleted Sucessfully
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
							<h3>Delete Loan</h3>
							<p>Are you sure want to delete?</p>
						</div>
						<div class="modal-btn delete-action">
							<div class="row">
								<div class="col-6">
									<input type="hidden" name="delet_loan_ref_id" value="" id="delet_loan_ref_id">
									<button type="submit" class="btn btn-primary continue-btn" class="delete_loan_btn">Delete</button>
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

<script  src="<?php echo WEB_URL; ?>assets/js/c_add_loan.js"></script>
    </body>
</html>