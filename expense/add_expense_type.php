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
					<h3 class="page-title">Add Expense Type</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Add Expense Type</li>
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
									<th>Date</th>
									<th>Name</th>
									<th>Expense Type</th>
									<th>Amount</th>
								</tr>
							</thead>
							<tbody>		<tr>
											<td>09/06/2022</td>
											<td>Mani</td>
											<td></td>
											<td>5000</td>						</tr>
										<tr>
											<td>07/06/2022</td>
											<td>Kumar</td>
											<td></td>
											<td>1000</td>							</tr>
										<tr>
										    <td>01/06/2022</td>
											<td>Raj</td>
											<td></td>
											<td>100</td>
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