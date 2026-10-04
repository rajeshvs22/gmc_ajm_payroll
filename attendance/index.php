<?php

include('../header.php'); ?>


<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<!--- breadcrumb section --->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Generate Attendance</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Generate Attendance</li>
					</ul>
				</div>
			</div>
		</div>
		<!--- END: breadcrumb section --->
		
		<form name="generate_attendance_fm" id="generate_attendance_fm" method="GET">
			<div class="row filter-row">
				<div class="col-sm-6 col-md-6"> 
					<div class="form-group form-focus select-focus focused">
						<select class="select floating select2-hidden-accessible" id="month_of_year" name="month_of_year"> 
							<option Disabled <?php if(!isset($_GET['monthyr'])){ echo "SELECTED"; } ?>>Select Month Of Year</option>
							<option value="jan-2022" <?php if(isset($_GET['monthyr']) && $_GET['monthyr'] == ''){ echo "SELECTED"; } ?>>JAN 2022</option>
							<option value="feb-2022" <?php if(isset($_GET['monthyr']) && $_GET['monthyr'] == 'feb-2022'){ echo "SELECTED"; } ?>>FEB 2022</option>
							<option value="mar-2022" <?php if(isset($_GET['monthyr']) && $_GET['monthyr'] == 'mar-2022'){ echo "SELECTED"; } ?>>MAR 2022</option>
							<option value="april-2022" <?php if(isset($_GET['monthyr']) && $_GET['monthyr'] == 'april-2022'){ echo "SELECTED"; } ?>>APRIL 2022</option>
							<option value="may-2022" <?php if(isset($_GET['monthyr']) && $_GET['monthyr'] == 'may-2022'){ echo "SELECTED"; } ?>>MAY 2022</option>
							<option value="june-2022" <?php if(isset($_GET['monthyr']) && $_GET['monthyr'] == 'june-2022'){ echo "SELECTED"; } ?>>JUNE 2022</option>
							<option value="jul-2022" <?php if(isset($_GET['monthyr']) && $_GET['monthyr'] == 'jul-2022'){ echo "SELECTED"; } ?>>JUL 2022</option>
							<option value="aug-2022" <?php if(isset($_GET['monthyr']) && $_GET['monthyr'] == 'aug-2022'){ echo "SELECTED"; } ?>>AUG 2022</option>
							<option value="sep-2022" <?php if(isset($_GET['monthyr']) && $_GET['monthyr'] == 'sep-2022'){ echo "SELECTED"; } ?>>SEP 2022</option>
							<option value="oct-2022" <?php if(isset($_GET['monthyr']) && $_GET['monthyr'] == 'oct-2022'){ echo "SELECTED"; } ?>>OCT 2022</option>
							<option value="nov-2022" <?php if(isset($_GET['monthyr']) && $_GET['monthyr'] == 'nov-2022'){ echo "SELECTED"; } ?>>NOV 2022</option>
							<option value="dec-2022" <?php if(isset($_GET['monthyr']) && $_GET['monthyr'] == 'dec-2022'){ echo "SELECTED"; } ?>>DEC 2022</option>
						</select>
						
					</div>
				</div>
				<div class="col-sm-6 col-md-3">  
					<input type="hidden" name="base_url" id="base_url" value="<?= WEB_URL ?>">
					<button class="btn btn-success btn-block" name="submit" value="submit" type="submit">Go</button>
				</div>    
			</div>
		</form><?php
		if(isset($_GET['monthyr'])){ ?>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped custom-table datatable">
						<thead>
							<tr>
								<th>Name</th>
								<th>Code No</th>
								<th>Department</th>
								<th>Position </th>
								<th class="text-right no-sort">Action</th>
							</tr>
						</thead>
						<tbody>
						<?php
							
							$getAllEmpQry = "SELECT * FROM employee";
							$qryExe = mysqli_query($conn, $getAllEmpQry); 
							if(mysqli_num_rows($qryExe) > 0){ 
								while($row = mysqli_fetch_assoc($qryExe)){ ?>
									<tr>
										<td><?= $row['emp_name'] ?></td>
										<td><?= $row['emp_code'] ?></td>
										<td><?= $row['division'] ?></td>
										<td><?= $row['position'] ?></td>
										<td class="text-right">
											<div class="dropdown dropdown-action">
												<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
												
												<div class="dropdown-menu dropdown-menu-right" x-placement="top-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(71px, -2px, 0px);">
													<a class="dropdown-item attendance-edit-btn" href="#" data-toggle="modal" data-target="#generate_attenedance_popup" data-emp-id = "<?= $row['emp_id'] ?>" data-emp-name="<?= $row['emp_name'] ?>" data-emp-code="<?= $row['emp_code'] ?>" data-emp-dep="<?= $row['division'] ?>" data-monthyr="<?= $_GET['monthyr'] ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a>
													
													<a  class="dropdown-item attendance-edit-btn view-btn" href="#" data-toggle="modal" data-target="#generate_attenedance_popup" data-emp-id = "<?= $row['emp_id'] ?>" data-emp-name="<?= $row['emp_name'] ?>" data-emp-code="<?= $row['emp_code'] ?>" data-emp-dep="<?= $row['division'] ?>" data-monthyr="<?= $_GET['monthyr'] ?>" data-view="yes"><i class="fa fa-pencil m-r-5" ></i> view</a>
													
													<a class="dropdown-item" href="#" data-toggle="modal" data-target="#delete_employee"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
												</div>
											</div>
										</td>
									</tr><?php
								}
							} ?>
						</tbody>
					</table>
				</div>
			</div>
		</div><?php
		} ?>
	</div>
	
	<!-- Add Employee Modal -->
	<div id="generate_attenedance_popup" class="modal custom-modal fade" role="dialog">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
				<!---<div class="modal-header">
					 <h5 class="modal-title">Add Employee</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div> ---->
				<div class="modal-body edit_employee_for_salary">
					<div class="alert alert-success alert-dismissible fade show small" role="alert" id="attendance-sucess" style="display:none">
						<span id="status-msg"></span>
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<form id="attendance-main-fm">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="needs-validation">Name</label>
									<input type="text" class="form-control" name="emp_name" id="emp_name" value="" readonly>
								</div>
								<div class="form-group">
									<label  >Department</label>
									<input type="text" class="form-control" name="division" id="division" value="" readonly>
								</div>
								<div class="form-group">
									<label  >Overtime *</label>
									<input type="text" class="form-control" name="e_overtime" id="e_overtime" value="">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label  >CODE NO</label>
									<input type="text" class="form-control" name="emp_code" id="emp_code" value="" readonly>
								</div>
								<div class="form-group">
									<label  >Number of working days *</label>
									<input type="text" class="form-control" name="no_of_wdays" id="no_of_wdays" value="">
								</div>
							</div>
						</div>
						
						<div class="submit-section">
							<input type="hidden" value="" id="action" name="action">
							<button class="btn btn-primary submit-btn">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<!-- /Add Employee Modal -->
</div>
<!-- /Page Wrapper -->




<?php
require '../footer.php'

?>

<script  src="<?php echo WEB_URL; ?>assets/js/c_generate_attendance.js"></script>
    </body>
</html>