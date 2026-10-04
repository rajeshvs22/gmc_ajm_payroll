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
					<h3 class="page-title">Edit Attendance</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Edit Attendance</li>
					</ul>
				</div>
			</div>
		</div> 
	<div class="row">
			<div class="col-md-12">
				<form id="" method="POST">
					<div class="table-responsive">
						<table class="table table-striped custom-table ">
							<thead>
								<tr>
									<th>Name</th>
									<th>Code No</th>
									<th>Department</th>
									<th>Position </th>
									<th>Number of working days </th>
									<th>Overtime </th>
								</tr>
							</thead>
							<tbody>									<tr>
											<td>Emp1</td>
											<td>emp001</td>
											<td>Development</td>
											<td>dvlpr</td>
											<td>
												<div class="form-group">
													<input type="text" class="form-control" name="" id="" value="31">
												</div>
											</td>
											<td>
												<div class="form-group">
													<input type="text" class="form-control" name="" id="" value="10">
												</div>
											</td>									</tr><tr>
											<td>Emp 2</td>
											<td>emp002</td>
											<td>Development</td>
											<td>dvlpr</td>
											<td>
												<div class="form-group">
													<input type="text" class="form-control" name="" id="" value="28">
												</div>
											</td>
											<td>
												<div class="form-group">
													<input type="text" class="form-control" name="" id="" value="5">
												</div>
											</td>							</tr></tbody>
						</table>
					</div>
				</form>
			</div>
		</div>
		
	</div>
</div>


<?php
require '../footer.php'

?>
 
    </body>
</html>