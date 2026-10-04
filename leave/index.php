<?php

include('../header.php'); ?>


<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<!--- breadcrumb section --->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Leave List</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Leave List</li>
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
								<th>Employee Code</th>
								<th>Nationality</th>
								<th>Department</th>
								<th>Start Date</th>
								<th>End Date</th>
								<th>Status</th>
								<th>Rejoing Date</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody> <?php
						$leaveDetailsQry = "SELECT *,(SELECT department_name FROM department_master WHERE dep_id =e.division) as department_name, e.emp_name,e.emp_code, e.e_nationality FROM vacation_details ld INNER JOIN employee e ON ld.ref_emp_id = e.emp_id";
						$leaveDetailsQryExe = mysqli_query($conn, $leaveDetailsQry);
						if(mysqli_num_rows($leaveDetailsQryExe) > 0){
							$sl_no = 1;
							while($row = mysqli_fetch_assoc($leaveDetailsQryExe)){ ?>
								<tr>
									<td><?= $sl_no ?></td>
									<td><?= $row['emp_code']; ?></td>
									<td><?= $row['emp_name']; ?></td>
									<td><?= $row['department_name']; ?></td>
									
									<td style="text-align:center"><?= date('d-m-Y',strtotime($row['leave_start_dt'])); ?></td>
									
									<td style="text-align:center"><?= date('d-m-Y',strtotime($row['leave_end_dt'])); ?></td>
									
									<td><?php if($row['leave_status'] == 1){ ?> 
										<button class="btn btn-info btn-sm">In Leave</button><?php  }else if($row['leave_status'] == 2){ ?> <button class="btn btn-primary btn-sm">Rejoined</button> <?php } ?></td>
										
									<td style="text-align:center"><?php if($row['rejoining_date'] == '0000-00-00'){ echo "-"; }else{ echo date('d-m-Y', strtotime($row['rejoining_date'])); } ?></td>
									
									<td class="text-right">
											<div class="dropdown dropdown-action">
												<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
												<div class="dropdown-menu dropdown-menu-right">
													
													<a class="dropdown-item" href="<?= WEB_URL ?>leave/add_vacation.php?lev_id=<?=$row['leave_id'] ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
													
													<a class="dropdown-item delete_leave" href="#" data-toggle="modal" data-target="#delete_leave" data-leave-id="<?= $row['leave_id']; ?>" id="delete_leave_btn"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
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
	<div class="modal custom-modal fade" id="delete_leave" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<form id="delet_leave_fm" method="POST">
					<div class="modal-body">
						<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-delet" style="display:none">Leave Deleted Sucessfully
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
							<h3>Delete Leave</h3>
							<p>Are you sure want to delete?</p>
						</div>
						<div class="modal-btn delete-action">
							<div class="row">
								<div class="col-6">
									<input type="hidden" name="delet_lev_ref_id" value="" id="delet_lev_ref_id">
									<button type="submit" class="btn btn-primary continue-btn" class="delete_lev_btn">Delete</button>
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

<script  src="<?php echo WEB_URL; ?>assets/js/c_add_vacation.js"></script>
    </body>
</html>