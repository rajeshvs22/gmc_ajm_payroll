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
					<h3 class="page-title">View Department</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">View Department</li>
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
									<th>#</th>
									<th>Department Name</th> 
								</tr>
							</thead>
							<tbody>		<tr>
											<td>1</td>
											<td>Web Development</td>			</tr>
										<tr>
											<td>2</td>
											<td>Application Development</td>	</tr>
										<tr>
											<td>3</td>
											<td>IT Management</td>	
										</tr>
										<tr>
											<td>4</td>
											<td>Accounts Management</td>	
										</tr>
										<tr>
										    <td>5</td>
											<td>Support Management</td>
										</tr>
										<tr>
										    <td>6</td>
											<td>Marketing</td>
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