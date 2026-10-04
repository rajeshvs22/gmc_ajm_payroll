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
					<h3 class="page-title">Edit User</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Edit User</li>
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
									<th>Login ID</th> 
								</tr>
							</thead>
							<tbody>									
							            <tr>
											<td>Mani</td> 
											<td>
												<div class="form-group">
													<input type="text" class="form-control" name="" id="" value="1">
												</div>
											</td>   		
										</tr>
										<tr> 
											<td>Kumar</td> 
											<td>
												<div class="form-group">
													<input type="text" class="form-control" name="" id="" value="2">
												</div>
											</td>  
									    </tr>
									    <tr> 
											<td>Raj</td> 
											<td>
												<div class="form-group">
													<input type="text" class="form-control" name="" id="" value="3">
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