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
					<h3 class="page-title">View Loan</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">View Loan</li>
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
									<th>Loan Amount</th>
								</tr>
							</thead>
							<tbody>		<tr>
											<td>1001</td>
											<td>Mani</td>
											<td></td>
											<td>IT</td>
											<td>50000</td>					</tr>
										<tr>
											<td>1002</td>
											<td>Kumar</td>
											<td></td>
											<td>Driver</td>
											<td>30000</td>					</tr>
										<tr>
										    <td>1003</td>
											<td>Raj</td>
											<td></td>
											<td>Admin</td>
											<td>70000</td>
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