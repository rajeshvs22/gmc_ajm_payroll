$(document).ready(function(){
	$('#division').on('change', function(){
		var ref_division_id = $(this).val();
		
		$.ajax({
			type: "POST",
			url: 'ajax_vacation_functions.php',
			data: {ref_division_id: ref_division_id, operation:'get_position_by_division'},
			success: function(data){
				$('#position').empty();
				$('#position').append(data);
			}
		});
	});
	
	$('#leave_status').on('change', function(){
		var leave_status = $(this).val();
		if(leave_status == 2){
			$('.rejoin-dt').css('display', 'block');
		}else{
			$('.rejoin-dt').css('display', 'none');
		}
	});
	
	$('#company').on('change', function(){
		get_employees();
	});
	$('#division').on('change', function(){
		get_employees();
	});
	$('#position').on('change', function(){
		get_employees();
	});
	
	
	function get_employees(){
		var ref_company = $('#company').val();
				
		$.ajax({
			type: "POST",
			url: 'ajax_vacation_functions.php',
			data: {ref_company:ref_company, operation:'get_employees'},
			success: function(data){
				if(data != 0){
					$('#ref_emp_id').empty();
					$('#ref_emp_id').append(data);
				}
			}
		});
	}
	
	
	$('.form-submit-btn').on('click', function(){
		var ref_company = $('#company').val();
		var ref_dep_id = $('#division').val();
		var ref_emp_id = $('#ref_emp_id').val();
		var position = $('#position').val();
		var leave_start_dt = $('#leave_start_dt').val();
		var leave_end_dt = $('#leave_end_dt').val();
		var leave_amount = $('#leave_amount').val();
		var ticket_amount = $('#ticket_amount').val();
		
		var re_joining_dt = $('#re_joining_dt').val();
		
		var leave_status = $('#leave_status').val();
		
		var ref_lev_id = $('#ref_lev_id').val();
		
		var rejoin_dt_err = 0;
		
		if(ref_company == '' || ref_company == null){
			$('#select2-company-container').css('border','1px solid red');
		}else{
			$('#select2-company-container').css('border','');
		}
		
		if(ref_dep_id == '' || ref_dep_id == null){
			$('#select2-division-container').css('border','1px solid red');
		}else{
			$('#select2-division-container').css('border','');
		}
		
		if(position == '' || position == null){
			$('#select2-position-container').css('border','1px solid red');
		}else{
			$('#select2-position-container').css('border','');
		}
		
		if(ref_emp_id == '' || ref_emp_id == null){
			$('#select2-ref_emp_id-container').css('border','1px solid red');
		}else{
			$('#select2-ref_emp_id-container').css('border','');
		}
		
		if(leave_start_dt == ''){
			$('#leave_start_dt').css('border','1px solid red');
		}else{
			$('#leave_start_dt').css('border','');
		}
		
		if(leave_end_dt == ''){
			$('#leave_end_dt').css('border','1px solid red');
		}else{
			$('#leave_end_dt').css('border','');
		}
		
		/* if(leave_amount == ''){
			$('#leave_amount').css('border','1px solid red');
		}else{
			$('#leave_amount').css('border','');
		}
		
		if(ticket_amount == ''){
			$('#ticket_amount').css('border','1px solid red');
		}else{
			$('#ticket_amount').css('border','');
		} */
		
		if(leave_status == 2){
			rejoin_dt_err = 1;
			if(re_joining_dt == ''){
				$('#re_joining_dt').css('border','1px solid red');
			}else{
				rejoin_dt_err = 0;
				$('#re_joining_dt').css('border','');
			}
		}
		
		if(ref_company != '' && ref_company != null && ref_emp_id !='' && ref_emp_id != null && leave_start_dt != '' && leave_end_dt != '' && rejoin_dt_err == 0){
			
			$.ajax({
				type: "POST",
				url: 'ajax_vacation_functions.php',
				data: {ref_emp_id:ref_emp_id, leave_start_dt:leave_start_dt, leave_end_dt:leave_end_dt, leave_status:leave_status, ref_lev_id:ref_lev_id,operation:'is_leave_already'  },
				success: function(data){
					if(data != ''){
						$('.existing-alert-on-add').empty();
						$('.existing-alert-on-add').append(data);
						
					}else{
						$('.existing-alert-on-add').empty();
						document.getElementById("loan_form").submit();
					}
					
				}
			});
				
		}
	});
	
	$('.delete_leave').on('click', function(){
		leave_id = $(this).data('leave-id');
	});
	
	$('#delete_leave').on('show.bs.modal', function(e) {
		$('#delet_lev_ref_id').val(leave_id);
	});
	
	
	$( "#delet_leave_fm" ).submit(function( e ) {
		e.preventDefault(); 
		var delet_lev_ref_id = $('#delet_lev_ref_id').val();
		$.ajax({
			url: 'ajax_vacation_functions.php',
			type: 'post',
			data: {delet_lev_ref_id:delet_lev_ref_id, operation:'delet-loan'},
			success: function(data){
				if(data == 4){
						$("#sucess-delet").css('display', 'block');
							$("#sucess-delet").fadeTo(2000, 500).slideUp(500, function(){ $("#sucess-delet").slideUp(500); });
							
							setTimeout(function () {
									location.reload();
							},1500);
							
					}else if(data == 5){
						$("#delet-error").css('display', 'block');
							$("#delet-error").fadeTo(2000, 500).slideUp(500, function(){ $("#delet-error").slideUp(500); });
					}
			}
		})
	});
});