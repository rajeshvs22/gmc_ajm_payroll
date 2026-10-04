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
					<h3 class="page-title">Add User</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Add User</li>
					</ul>
				</div>
			</div>
		</div> 
	 <div class="row">
			<div class="col-md-12">
				<div class="card"> 
					<div class="card-body">
						
						<form action="" method="POST">
							<div class="row"> 
									
									<div class="form-group col-md-6">
									    <label>Name  </label>
										<input type="text" class="form-control" name="" id="" value="">
										
									</div>
									<div class="form-group col-md-6">
									    <label>Login ID </label>
										<input type="text" class="form-control" name="" id="" value="">
										
									</div>
									<div class="form-group col-md-6">
									    <label>Password  </label>
										<input type="text" class="form-control" name="" id="" value="">
										
									</div> 
								</div> 
									<div class="text-right">
									<button type="submit" name="submit" value="submit" class="btn btn-primary">Save</button>
								</div>							
													</form>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>


<?php
require '../footer.php'

?>
 
    </body>
</html>