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
					<h3 class="page-title">Edit Vacation</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Edit Vacation</li>
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
									<th>Employee ID</th>
									<th>Name</th>
									<th>Nationality</th>
									<th>Department</th>
									<th>Start Date</th>
									<th>End date</th>
								</tr>
							</thead>
							<tbody>									<tr>
											<td>1001</td>
											<td>Mani</td>
											<td></td>
											<td>IT</td>
											<td>
												<div class="form-group">
													<input type="text" class="form-control" name="" id="" value="01/06/2021">
												</div>
											</td> 
											<td>
												<div class="form-group">
													<input type="text" class="form-control" name="" id="" value="07/06/2022">
												</div>
											</td> 		</tr>
											<tr>
											<td>1002</td>
											<td>Kumar</td>
											<td></td>
											<td>Driver</td>
											<td>
												<div class="form-group">
													<input type="text" class="form-control" name="" id="" value="20/04/2021">
												</div>
											</td> 
											<td>
												<div class="form-group">
													<input type="text" class="form-control" name="" id="" value="15/06/2022">
												</div>
											</td>
									    </tr>
									    <tr>
									        <td>1003</td> 
											<td>Raj</td>
											<td></td>
											<td>Admin</td>
											<td>
												<div class="form-group">
													<input type="text" class="form-control" name="" id="" value="09/09/2020">
												</div>
											</td>
											<td>
												<div class="form-group">
													<input type="text" class="form-control" name="" id="" value="19/02/2022">
												</div>
											</td>
											 							</tr>
											 							</tbody>
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