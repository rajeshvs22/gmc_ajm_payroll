<?php

include('../header.php');
if(isset($_SESSION['employee_operation'])){
	if($_SESSION['employee_operation'] == 1){
		echo "inserted";
		$_SESSION['employee_operation'] = 0;
	}else if($_SESSION['employee_operation'] == 2){
		echo "updated";
		$_SESSION['employee_operation'] = 0;
	}else if($_SESSION['employee_operation'] == 3){
		echo "deleted";
		$_SESSION['employee_operation'] = 0;
	}
}

//FILTER OPTION
$dep = $emp_name = $cmpy = $emp_code = $condition = $company ="";

if(isset($_GET['dep']) && !empty($_GET['dep'])){
	$dep = $_GET['dep'];
	$condition .= ' AND division = '.$dep;
}
if(isset($_GET['emp_name']) && !empty($_GET['emp_name'])){
	$emp_name = $_GET['emp_name'];
	$condition .= " AND emp_name LIKE '%".$emp_name."%'";
}
if(isset($_GET['cmpy']) && !empty($_GET['cmpy'])){
	$company = $_GET['cmpy'];
	$condition .= ' AND ref_comp_id = '.$company;
}
if(isset($_GET['emp_code']) && !empty($_GET['emp_code'])){
	$emp_code = $_GET['emp_code'];
	$condition .= ' AND emp_code = '.$emp_code;
}

?>
<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<div class="page-header">
			<div class="row align-items-center">
				<div class="col">
					<h3 class="page-title">Employee Gratuity Settlement List</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Gratuity Settlement List</li>
					</ul>
				</div>
			</div>
		</div>
		
		<form action="" method="GET">
		<div class="row filter-row">
			
				<div class="col-sm-6 col-md-3"> 
					<div class="form-group form-focus select-focus">
					
						<select class="select select-company" name="cmpy"> 
							<option value="" <?php if(empty($company)){ echo "Selected"; } ?>>Select Company</option><?php
							$getAllCmpyQry = "SELECT * FROM company_master WHERE 1=1";
							$qryExe = mysqli_query($conn, $getAllCmpyQry); 
							if(mysqli_num_rows($qryExe) > 0){
								$sl_no = 1;
								while($cmpy = mysqli_fetch_assoc($qryExe)){ ?>
									<option value="<?= $cmpy['comp_id'] ?>" <?php if($company == $cmpy['comp_id']){ echo "SELECTED"; } ?>><?= $cmpy['company_name'] ?></option><?php
								}
							} ?>
						</select>
						<label class="focus-label">Company</label>
					</div>
				</div>
				<div class="col-sm-6 col-md-3">  
					<div class="form-group form-focus">
						<input type="text" class="form-control floating" name="emp_code" value="<?= $emp_code ?>">
						<label class="focus-label">Employee CODE</label>
					</div>
				</div>
				<div class="col-sm-6 col-md-3">    
					<div class="form-group form-focus">
						<input type="text" class="form-control floating" name="emp_name" value="<?= $emp_name ?>">
						<label class="focus-label">Employee Name</label>
					</div>
				</div>
				
				<div class="col-sm-6 col-md-3">  
					<button type="submit" class="btn btn-success btn-block m-b-20"> Search </button>  
				</div> 
			
		</div>
		</form>
		<!-- /Search Filter -->
		
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table table-striped custom-table datatable-no-sorting" id="datatable-no-sorting2">
						<thead>
							<tr>
								<th>S.No</th>
								<th>Company</th>
								<th>Name</th>
								<th>Employee Code</th>
								<th>Joining Date</th>
								<th>Guatuity Amount</th>
								<th>Guatuity Settlement Date</th>
								<th class="text-right no-sort">Action</th>
							</tr>
						</thead>
						<tbody><?php

							$getAllEmpQry = "SELECT G.*, e.emp_name, e.emp_code, cm.company_name, e.joining_date
											FROM employee_gratuity as G
											JOIN employee e ON e.emp_id = G.employee_id
											LEFT JOIN company_master cm ON cm.comp_id = G.company_id
											WHERE 1 $condition";

							$qryExe = mysqli_query($conn, $getAllEmpQry); 
							if(mysqli_num_rows($qryExe) > 0){
								$sl_no = 1;
								while($row = mysqli_fetch_assoc($qryExe)){ ?>
									<tr>
										<td><?= $sl_no ?></td>
										<td><?= $row['company_name'] ?></td>
										<td><?= $row['emp_name'] ?></td>
										<td><?= $row['emp_code'] ?></td>
										<td><?= date('d-m-Y', strtotime($row['joining_date'])) ?></td>									
										<td><?= $row['guatutiy_amount'] ?></td>
										<td><?= date('d-m-Y', strtotime($row['last_day'])) ?></td>
										<td class="text-right">
											
											<!-- <a class="dropdown-item" href="<?= WEB_URL ?>employee/settelment_guatutiy.php?id=<?=$row['id'] ?>"><i class="fa fa-pencil m-r-5"></i> Edit</a> -->
											<a class="dropdown-item delete_guatuity" guatuity_id="<?=$row['id'] ?>" href="javascript:void(0);" ><i class="fa fa-trash m-r-5"></i> Delete</a>
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
</div>
<?php
require '../footer.php'
?>

<script src="<?php echo WEB_URL; ?>assets/js/c_add_employee.js"></script>
<script>
	$(document).ready( function() {
		$('#datatable-no-sorting2').DataTable({
			"ordering": false
		});

		$(document).on('click', '.delete_guatuity', function(e) {
			e.preventDefault();
			
			if(!confirm('Are you sure you want to delete this record?')) {
				return false;
			}
			
	
			var row = $(this).closest('tr');
			
			$.ajax({
				url: "settelment_guatutiy_ajax.php",
				type: 'POST',
				data: { action: 'delete_gratuity', guatuity_id: $(this).attr('guatuity_id') },
				success: function(response) {
					row.fadeOut(400, function() {
						$(this).remove();
					});
					alert('Record deleted successfully');
				},
				error: function() {
					alert('Error deleting record');
				}
			});
		});
	})
</script>
</body>
</html>
