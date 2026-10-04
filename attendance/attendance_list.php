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
					<h3 class="page-title">Attendance List</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Attendance List</li>
					</ul>
				</div>
			</div>
		</div> 
		<div class="row">
			<div class="col-md-12">
				
					<div class="table-responsive">
						<table class="table table-striped custom-table ">
							<thead>
								<tr>
									<th>Sl.No</th>
									<th>Company</th>
									<th>Month</th>
									<th>Year</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody> <?php
							$getAttendanceQry = "SELECT ea.month, ea.year, ea.ref_comp_id, cm.company_name
                                FROM employee_attendance ea
                                LEFT JOIN company_master cm ON cm.comp_id = ea.ref_comp_id
                                GROUP BY ea.month, ea.year, ea.ref_comp_id, cm.company_name
                                ORDER BY ea.year DESC, ea.month+0 DESC, ea.ref_comp_id ASC";
							$qryExe = mysqli_query($conn, $getAttendanceQry); 
							if(mysqli_num_rows($qryExe) > 0){ 
								$sl_no = 1;
								while($row = mysqli_fetch_assoc($qryExe)){ ?>
									<tr>
										<td><?= $sl_no; ?></td>
										<td><?= $row['company_name']; ?></td>
										<td><?= $row['month']; ?></td>
										<td><?= $row['year']; ?></td>
										<td class="text-right">
											<div class="dropdown dropdown-action">
												<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
												<div class="dropdown-menu dropdown-menu-right"><?php
													$cmpy = $row['ref_comp_id'];?>
													<a class="dropdown-item" href="<?= WEB_URL ?>attendance/attendance_view.php?cmpy=<?= $cmpy ?>&month=<?= $row['month']; ?>&year=<?= $row['year']; ?>"><i class="fa fa-eye m-r-5"></i> View</a>
													<a class="dropdown-item" href="<?= WEB_URL ?>attendance/generate_attendance.php?cmpy=<?= $cmpy ?>&month=<?= $row['month']; ?>&year=<?= $row['year']; ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
													<a class="dropdown-item" href="<?= WEB_URL ?>attendance/export_attendance.php?cmpy=<?= $cmpy ?>&month=<?= $row['month']; ?>&year=<?= $row['year']; ?>"><i class="fa fa-download m-r-5"></i> Export</a>
													
													<a class="dropdown-item delete_attendance" href="#" data-toggle="modal" data-target="#delete_attendance" data-cmpy="<?= $cmpy ?>"  data-month="<?= $row['month']; ?>" data-year="<?= $row['year']; ?>" id="delete_attendance_btn"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
												</div>
											</div>
										</td>
									</tr><?php
									$sl_no++;
								}
							}
							?>
									
							</tbody>
						</table>
					</div>
			</div>
		</div> 
	</div>
	
	<!---Delet model---->
	<div class="modal custom-modal fade" id="delete_attendance" role="dialog">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<form id="delet_attendance_fm" method="POST">
					<div class="modal-body">
						<div class="alert alert-success alert-dismissible fade show small" role="alert" id="sucess-delet" style="display:none">Attendance Deleted Sucessfully
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
							<h3>Delete Attendance</h3>
							<p>Are you sure want to delete?</p>
						</div>
						<div class="modal-btn delete-action">
							<div class="row">
								<div class="col-6">
									<input type="hidden" name="delet_attendance_month" value="" id="delet_attendance_month">
									<input type="hidden" name="delet_attendance_year" value="" id="delet_attendance_year">
									<input type="hidden" name="delet_attendance_cmpy" value="" id="delet_attendance_cmpy">
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

<script  src="<?php echo WEB_URL; ?>assets/js/c_generate_attendance.js"></script>
    </body>
</html>
