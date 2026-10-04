$(document).ready(function(){
	$('#division').on('change', function(){
		var ref_division_id = $(this).val();
		
		$.ajax({
			type: "POST",
			url: 'ajax_loan_functions.php',
			data: {ref_division_id: ref_division_id, operation:'get_position_by_division'},
			success: function(data){
				$('#position').empty();
				$('#position').append(data);
			}
		});
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
		var ref_dep_id = $('#division').val();
		var position = $('#position').val();
		
		$.ajax({
			type: "POST",
			url: 'ajax_loan_functions.php',
			data: {ref_company:ref_company, operation:'get_employees'},
			success: function(data){
				if(data != 0){
					$('#ref_emp_id').empty();
					$('#ref_emp_id').append(data);
				}
			}
		});
	}
	
	
	//$('#loan_form').submit(function(e){
	$('.form-submit-btn').on('click', function(){
		var ref_company = $('#company').val();
		
		var ref_emp_id = $('#ref_emp_id').val();
		
		var loan_type = $('#loan_type').val();
		var loan_date = $('#loan_date').val();
		var loan_amount = $('#loan_amount').val();
		
		var ref_loan_id = $('#ref_loan_id').val(); 
		
		if(ref_company == '' || ref_company == null){
			$('#select2-company-container').css('border','1px solid red');
		}else{
			$('#select2-company-container').css('border','');
		}
		
		if(ref_emp_id == '' || ref_emp_id == null){
			$('#select2-ref_emp_id-container').css('border','1px solid red');
		}else{
			$('#select2-ref_emp_id-container').css('border','');
		}
		
		if(loan_type == '' || loan_type == null){
			$('#select2-loan_type-container').css('border','1px solid red');
		}else{
			$('#select2-loan_type-container').css('border','');
		}
		
		if(loan_date == ''){
			$('#loan_date').css('border','1px solid red');
		}else{
			$('#loan_date').css('border','');
		}
		
		if(loan_amount == ''){
			$('#loan_amount').css('border','1px solid red');
		}else{
			$('#loan_amount').css('border','');
		}
		
		if(ref_company != '' && ref_company != null && ref_emp_id !='' && ref_emp_id != null && loan_type != '' && loan_type !=null && loan_date != '' && loan_amount != ''){
			
			$.ajax({
				type: "POST",
				url: 'ajax_loan_functions.php',
				data: {ref_emp_id:ref_emp_id, loan_type:loan_type, loan_date:loan_date, ref_loan_id:ref_loan_id, operation:'is_loan_already'  },
				success: function(data){
					if(data != ''){
						$('.existing-alert-on-add').empty();
						$('.existing-alert-on-add').append(data);
						
					}else{
						//$('#loan_form').submit();
						document.getElementById("loan_form").submit();
					}
					
				}
			});
		}
	});
	
	
	$('.delete_loan').on('click', function(){
		loan_id = $(this).data('loan-id');
	});
	
	$('#delete_loan').on('show.bs.modal', function(e) {
		$('#delet_loan_ref_id').val(loan_id);
	});
	
	
	$( "#delet_loan_fm" ).submit(function( e ) {
		e.preventDefault(); 
		var delet_loan_ref_id = $('#delet_loan_ref_id').val();
		$.ajax({
			url: 'ajax_loan_functions.php',
			type: 'post',
			data: {delet_loan_ref_id:delet_loan_ref_id, operation:'delet-loan'},
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