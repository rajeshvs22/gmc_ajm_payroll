<?php

include('../header.php');  

error_reporting(E_ALL);



?>

<div class="page-wrapper">
	<!-- Page Content -->
	<div class="content container-fluid">
		<!--- breadcrumb section --->
		<div class="page-header">
			<div class="row">
				<div class="col">
					<h3 class="page-title">Add Employee Cancellation</h3>
					<ul class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo WEB_URL ?>dashboard.php">Dashboard</a></li>
						<li class="breadcrumb-item active">Add Employee Cancellation</li>
					</ul>
				</div>
			</div>
		</div>
		<!--- END: breadcrumb section --->
		
		<div class="row">
			<div class="col-md-12">
				<!-- Start Guatuity Settlement Form -->
					<div class="card">
						<div class="card-header">
							<h4 class="card-title mb-0">Employee Gratuity Calculation</h4>
						</div>

						<div class="card-body">
							<div class="row">
								<!-- Left Side: Form -->
								<div class="col-md-6">
									<!-- Company -->
									<div class="form-group">
										<label>Company *</label>
										<select name="company" id="company" class="form-control">
											<option value="">Select Company</option>
											<?php
											$companyQry = mysqli_query($conn, "SELECT * FROM company_master WHERE visibility=1");
											while ($c = mysqli_fetch_assoc($companyQry)) {
												echo '<option value="' . $c['comp_id'] . '">' . $c['company_name'] . '</option>';
											}
											?>
										</select>
									</div>

									<!-- Employee -->
									<div class="form-group">
										<label>Employee *</label>
										<select name="ref_emp_id" id="ref_emp_id" class="form-control">
											<option value="">Select Employee</option>
										</select>
									</div>

									<!-- Joining Date -->
									<div class="form-group">
										<label>Joining Date</label>
										<input type="text" id="joining_date" class="form-control datetimepicker" readonly>
									</div>

									<!-- Salary -->
									<div class="form-group">
										<label>Basic Salary (AED)</label>
										<input type="text" id="salary" class="form-control" readonly>
									</div>

									<!-- Last Working Day -->
									<div class="form-group">
										<label>Last Working Day *</label>
										<input type="text" class="form-control datetimepicker" name="cancel_date" id="cancel_date">
									</div>

									<!-- Output -->
									<div class="form-group">
										<button type="button" name="save_gratuity" id="save_gratuity" class="btn btn-primary">Pay Gratuity</button>
									</div>
								</div>

								<!-- Right Side: Calculation Result -->
								<div class="col-md-6">
									<!-- Breakdown -->
									<div id="breakdown" style="display:none; margin-top:20px; padding:20px; background:#f8f9fa; border-radius:8px; border-left:4px solid #007bff;">
										<h5 style="color:#007bff; margin-bottom:20px; border-bottom:2px solid #007bff; padding-bottom:10px;">Calculation Breakdown</h5>
										
										<div style="margin-bottom:15px;">
											<p style="margin-bottom:8px;"><b>Service Period:</b> <span id="service_period" style="color:#333;"></span></p>
											<p style="margin-bottom:8px;"><b>Total Years:</b> <span id="years" style="color:#333;"></span></p>
											<p style="margin-bottom:8px;"><b>Extra Days:</b> <span id="extradays" style="color:#333;"></span></p>
											<p style="margin-bottom:8px;"><b>Daily Rate:</b> <span id="daily_rate" style="color:#333;"></span> AED</p>
										</div>
										
										<div style="background:#fff; padding:15px; border-radius:5px; margin-top:20px;">
											<p style="margin-bottom:12px;">
												<b>First 5 Years Gratuity:</b> 
												<span id="first5" style="font-size:20px; font-weight:bold; color:#28a745;"></span> <span style="font-size:18px; color:#28a745;">AED</span>
											</p>
											<p style="margin-bottom:12px;">
												<b>After 5 Years Gratuity:</b> 
												<span id="after5" style="font-size:20px; font-weight:bold; color:#17a2b8;"></span> <span style="font-size:18px; color:#17a2b8;">AED</span>
											</p>
											<p style="margin-bottom:12px;">
												<b>Extra Days Amount:</b> 
												<span id="extradays_amount" style="font-size:20px; font-weight:bold; color:#fd7e14;"></span> <span style="font-size:18px; color:#fd7e14;">AED</span>
											</p>
											<hr style="margin:15px 0;">
											<p style="margin-bottom:0;">
												<b style="font-size:18px;">Total Gratuity Amount:</b> 
												<span id="gratuity_amount" style="font-size:24px; font-weight:bold; color:#dc3545;"></span> <span style="font-size:22px; color:#dc3545;">AED</span>
											</p>
										</div>
										
										<!-- Hidden input to store gratuity amount -->
										<input type="hidden" id="gratuity_amount_hidden" name="gratuity_amount">
									</div>
									<!-- end Breakdown -->
								</div>
							</div>
						</div>
					</div>
				<!-- End Guatuity Settlement Form -->
			</div>
		</div>
	</div>
</div>

<?php
require '../footer.php'

?>

		<script>
			$("#company").change(function() {
				let comp_id = $(this).val();

				$("#ref_emp_id").html('<option>Loading...</option>');

				$.post("settelment_guatutiy_ajax.php", { action: 'get_employees', comp_id: comp_id }, function(data) {
					$("#ref_emp_id").html(data);
				});
				$('#breakdown').hide();
			});

			// Auto load employee salary & joining date
			$("#ref_emp_id").change(function() {
				let emp_id = $(this).val();

				$.post("settelment_guatutiy_ajax.php", { action: 'get_employee_details', emp_id: emp_id }, function(data) {
					let emp = JSON.parse(data);

					$("#salary").val(emp.salary);
					$("#joining_date").val(emp.joining_date);

					calculateGratuity();
				});
			});

			$("#cancel_date").on("changeDate blur", function() {
				console.log("Change detected");
				calculateGratuity();
			});

			function calculateGratuity() {
				let emp_id = $("#ref_emp_id").val();
				let cancel_date = $("#cancel_date").val();

				if (emp_id && cancel_date) {
					$.ajax({
						url: "settelment_guatutiy_ajax.php",
						type: "POST",
						data: { action: 'calculate_gratuity', emp_id: emp_id, cancel_date: cancel_date },
						dataType: "json",
						success: function(res) {
							$("#gratuity_amount").val(res.amount);

							// Breakdown
							if (res.status == 1) {
								$("#service_period").text(res.service);
								$("#years").text(res.years);
								$("#extradays").text(res.extradays);
								$("#daily_rate").text(res.daily_rate);
								$("#first5").text(res.first5);
								$("#after5").text(res.after5);
								$("#extradays_amount").text(res.extradays_amount);
								$("#gratuity_amount").text(res.amount);
								$("#gratuity_amount_hidden").val(res.amount);
								$("#breakdown").show();
							} else {
								$("#breakdown").hide();
							}
						}
					});
				}
			}

			$("#save_gratuity").click(function() {
				let company_id = $("#company").val();
				let emp_id = $("#ref_emp_id").val();
				let cancel_date = $("#cancel_date").val();
				let gratuity_amount = $("#gratuity_amount").val();

				if (!company_id || !emp_id || !cancel_date || !gratuity_amount) {
					alert("Please fill all required fields and calculate gratuity before saving.");
					return;
				}

				$.ajax({
					url: "settelment_guatutiy_ajax.php",
					type: "POST",
					data: { action: 'save_gratuity', company_id: company_id, emp_id: emp_id, cancel_date: cancel_date, gratuity_amount: gratuity_amount },
					dataType: "json",
					success: function(response) {
						if (response.status == 1) {
							alert(response.message);
							resetForm();
						} else {
							alert(response.message || "Error saving gratuity.");
						}
					}
				});
			});

			function resetForm() {
				$("#ref_emp_id").val('');
				$("#joining_date").val('');
				$("#salary").val('');
				$("#cancel_date").val('');
				$("#gratuity_amount").val('');
				$("#breakdown").hide();
			}
			</script>



	</body>
</html>