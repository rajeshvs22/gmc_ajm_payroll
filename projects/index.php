<?php

include('../header.php');

if(isset($_SESSION['projects_operation'])){
	if($_SESSION['projects_operation'] == 1){
		echo "inserted";
		$_SESSION['projects_operation'] = 0;
	}else if($_SESSION['projects_operation'] == 2){
		echo "updated";
		$_SESSION['projects_operation'] = 0;
	}else if($_SESSION['projects_operation'] == 3){
		echo "deleted";
		$_SESSION['projects_operation'] = 0;
	}
}


//FILTER OPTION
$emp_name = $proj_name = $condition = "";
if(isset($_GET['proj_name']) && !empty($_GET['proj_name'])){
	$proj_name = $_GET['proj_name'];
	$condition .= " AND pd.proj_name LIKE '%".$proj_name."%'";
}
if(isset($_GET['emp_name']) && !empty($_GET['emp_name'])){
	$emp_name = $_GET['emp_name'];
	$condition .= " AND pe.ref_emp_id=".$emp_name;
}

?>


<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<div class="page-header">
			<div class="row align-items-center">
				<div class="col">
					<h3 class="page-title">Projects</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Projects</li>
					</ul>
				</div>
				<div class="col-auto float-right ml-auto">
					<a href="<?= WEB_URL ?>projects/add_projects.php" class="btn add-btn"><i class="fa fa-plus"></i> Add Projects</a>
				</div>
			</div>
		</div>
		
		<!-- Search Filter -->
		<form action="" method="GET">
			<div class="row filter-row">
				<div class="col-sm-6 col-md-3">    
					<div class="form-group form-focus">
						<input type="text" class="form-control floating" name="proj_name" value="<?= $proj_name ?>">
						<label class="focus-label">Project Name</label>
					</div>
				</div>
				
				<div class="col-sm-6 col-md-3">    
					<div class="form-group form-focus">
						<select name="emp_name" id="emp_name" class="select">
							<option value="" SELECTED>Employee Name</option><?php
							$getAllEmpQry = "SELECT * FROM employee WHERE work_status =".$work_status." AND employe_status = 0";
							$getAllEmpQryExe = mysqli_query($conn, $getAllEmpQry);
							if(mysqli_num_rows($getAllEmpQryExe) > 0){
								while($theAllEmpQryExe = mysqli_fetch_assoc($getAllEmpQryExe)){ ?>
									<option value="<?= $theAllEmpQryExe['emp_id'] ?>" <?php if($emp_name  == $theAllEmpQryExe['emp_id']){ echo "SELECTED"; } ?>><?= $theAllEmpQryExe['emp_name'] ?></option><?php
								}
							} ?>
							</select>
						
					</div>
				</div>
				
				
				<div class="col-sm-6 col-md-3">  
					<button type="submit" class="btn btn-success btn-block m-b-20"> Search </button>  
				</div> 
			</div>
		</form>
		<!-- /Search Filter -->
		
		<div class="row"><?php
		if(isset($_GET['emp_name']) && !empty($_GET['emp_name'])){
			$getProjDetsQry = "SELECT pd.* FROM project_details pd INNER JOIN project_employees pe ON pd.proj_id = pe.ref_proj_id INNER JOIN employee e ON e.emp_id=pe.ref_emp_id WHERE 1=1 AND e.work_status=".$work_status.$condition." GROUP BY pd.proj_id";
		}else{
			$getProjDetsQry = "SELECT pd.* FROM project_details pd WHERE 1=1". $condition;
		}
		
		$getProjDetsQryExe = mysqli_query($conn,$getProjDetsQry);
		
		if(mysqli_num_rows($getProjDetsQryExe) > 0){
			while($theProjDetsData = mysqli_fetch_assoc($getProjDetsQryExe)){ ?>
				<div class="col-lg-4 col-sm-6 col-md-4 col-xl-3">
					<div class="card">
						<div class="card-body">
							<div class="dropdown dropdown-action profile-action">
								<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
								<div class="dropdown-menu dropdown-menu-right">
									<a class="dropdown-item" href="<?= WEB_URL ?>projects/add_projects.php?proj_id=<?= $theProjDetsData['proj_id'] ?>" ><i class="fa fa-pencil m-r-5"></i> Edit </a>
									
									<a class="dropdown-item" href="" data-toggle="modal" data-target="#delete_project"><i class="fa fa-trash-o m-r-5"></i> Delete </a>
								</div>
							</div>
							<h4 class="project-title"><a href="project-view.html"><?= $theProjDetsData['proj_name'] ?></a></h4>
							<p class="text-muted"><?php
								$desc = implode(' ', array_slice(explode(' ', $theProjDetsData['proj_desc']), 0, 10)); 
								echo $desc; ?>
							</p>
							<div class="pro-deadline m-b-15">
								<div class="sub-title">
									Deadline:
								</div>
								<div class="text-muted"><?php
								$proj_end_dt = $theProjDetsData['proj_end_date'];
								$proj_end_dt = date('d M Y',strtotime($proj_end_dt));
								echo $proj_end_dt;
								?>
									
								</div>
							</div>
							<div class="pro-deadline m-b-15">
								<div class="sub-title">
									Team Count:
								</div>
								<div class="text-muted"><?php
								$projEmpCountQry = "SELECT count(proj_emp_id) as emp_count FROM project_employees WHERE ref_proj_id=".$theProjDetsData['proj_id'];
								$projEmpCountExe = mysqli_query($conn,$projEmpCountQry);
								$projEmpCount = mysqli_fetch_assoc($projEmpCountExe);
								echo $projEmpCount['emp_count'];
								?>
									
								</div>
							</div>
							
							<div class="pro-deadline m-b-15">
								<div class="sub-title">
									Project Status:
								</div>
								<div class="text-muted"><?php
								$projQuitedEmpCountQry = "SELECT count(proj_emp_id) as quited_emp_count FROM project_employees WHERE ref_proj_id=".$theProjDetsData['proj_id']." AND emp_proj_status = 2";
								$projQuitedEmpCountExe = mysqli_query($conn,$projQuitedEmpCountQry);
								$projQuitedEmpCount = mysqli_fetch_assoc($projQuitedEmpCountExe);
								if($projEmpCount['emp_count'] == $projQuitedEmpCount['quited_emp_count']){
									echo "Project Closed";
								}else{
									echo "On going";
								}
								?>
									
								</div>
							</div>
						</div>
					</div>
				</div><?php
			} 
		}else{ ?>
			<div class="col-lg-12 col-sm-12 col-md-12 col-xl-12">
				<div class="card p-2"><?php
					echo "No Data Found"; ?>
				</div>
			</div><?php
		} ?>
		</div>
	</div>
</div>
		

<?php
require '../footer.php'

?>
<script  src="<?php echo WEB_URL; ?>assets/js/c_projects.js"></script>
    </body>
</html>

