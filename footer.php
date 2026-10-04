</div>
		<!-- /Main Wrapper -->
		
		<!-- jQuery -->
        <script src="<?php echo WEB_URL; ?>assets/js/jquery-3.2.1.min.js"></script>
		
		<!-- Bootstrap Core JS -->
        <script src="<?php echo WEB_URL; ?>assets/js/popper.min.js"></script>
        <script src="<?php echo WEB_URL; ?>assets/js/bootstrap.min.js"></script>
		
		<!-- Slimscroll JS -->
		<script src="<?php echo WEB_URL; ?>assets/js/jquery.slimscroll.min.js"></script>
		
		<!-- Select2 JS -->
		<script src="<?php echo WEB_URL; ?>assets/js/select2.min.js"></script>
		
		<!-- Datetimepicker JS -->
		<script src="<?php echo WEB_URL; ?>assets/js/moment.min.js"></script>
		<script src="<?php echo WEB_URL; ?>assets/js/bootstrap-datetimepicker.min.js"></script>
		
		<!-- Datatable JS -->
		<script src="<?php echo WEB_URL; ?>assets/js/jquery.dataTables.min.js"></script>
		<script src="<?php echo WEB_URL; ?>assets/js/dataTables.bootstrap4.min.js"></script>
		
		<!-- Custom JS -->
		<script  src="<?php echo WEB_URL; ?>assets/js/app.js"></script>
		<script>
			$('#work_status').on('change', function(){
				var work_status = $(this).val();
				$.ajax({
					type: "POST",
					url: '<?php echo WEB_URL; ?>ajax_work_status_change.php',
					data:{ work_status:work_status},
					success: function(data){
						 location.reload();
					}
				});
			})
			
		</script>
		
