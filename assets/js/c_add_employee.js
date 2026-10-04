$(document).ready(function(){
	var emp_id, increment_id;
	$('#employee_photo').change(function(){
		$('.employee_photo-prev-div').show();
        const file = this.files[0];
        if (file){
			let reader = new FileReader();
			reader.onload = function(event){
				$('.employee_photo-prev-img').attr('src', event.target.result);
			}
			reader.readAsDataURL(file);
        }
    });
	
	$('#visa_copy').change(function(){
		$('.visa_copy-prev-div').show();
        const file = this.files[0];
        if (file){
			let reader = new FileReader();
			reader.onload = function(event){
				$('.visa_copy-prev-img').attr('src', event.target.result);
			}
			reader.readAsDataURL(file);
        }
    });
	
	$('#passport_copy').change(function(){
		$('.passport_copy-prev-div').show();
        const file = this.files[0];
        if (file){
			let reader = new FileReader();
			reader.onload = function(event){
				$('.passport_copy-prev-img').attr('src', event.target.result);
			}
			reader.readAsDataURL(file);
        }
    });
	
	$('#emirates_id_copy').change(function(){
		$('.emirates_id_copy-prev-div').show();
        const file = this.files[0];
        if (file){
			let reader = new FileReader();
			reader.onload = function(event){
				$('.emirates_id_copy-prev-img').attr('src', event.target.result);
			}
			reader.readAsDataURL(file);
        }
    });
	
	$('#salary').on('change', function(){
		var sal = $(this).val();
		//var hour_rate = Math.round( (((sal/30)) / 9) * 1.3);
		var hour_rate =  (((sal/30)) / 9) * 1.3;
		hour_rate = hour_rate.toFixed(2);
		$('#over_time_hour_rate').val(hour_rate);
	})
	
	$("#emp_mobile").on('input focus keydown', function(value){
		this.value = this.value.replace(/\D/g, '');
	})
	$( "#employee_form" ).submit(function( e ) {
		
		var regxEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,3}$/i;
		
		var company_name = $('#company').val();
		var emp_name = $('#emp_name').val();
		var emp_code = $('#emp_code').val();
		var emp_dob = $('#emp_dob').val();
		var father_name = $('#father_name').val();
		var emp_email = $('#emp_email').val();
		var emp_mobile = $('#emp_mobile').val();
		var e_nationality = $('#e_nationality').val();
		var city = $('#city').val();
		var division = $('#division').val();
		var position = $('#position').val();
		var duty_type = $('#duty_type').val();
		var salary = $('#salary').val();
		var allowance = $('#allowance').val();
		var over_time_hour_rate = $('#over_time_hour_rate').val();
		
		if(company_name == '' || company_name == null){
			$('#select2-company-container').css('border','1px solid red');
		}else{
			$('#select2-company-container').css('border','');
		}
		
		if(company_name != '' && company_name != null){
			$(':input[type="submit"]').prop('disabled', false);
		}else{
			e.preventDefault(); 
		}
	
	})
	
	$( "#import_employees_fm" ).submit(function( e ) {
		e.preventDefault(); 
		$('#import-in-progress').css('display','block');
		var formData = new FormData();
		var uploadFiles = document.getElementById('emp-list').files;
		formData.append("emp_list", uploadFiles[0]);
		$.ajax({
			type: "POST",
			url: 'ajax_employee_import.php',
			data: formData,
			dataType: 'json',
			contentType: false,
			processData: false,
			success: function(data){
				if(data == 1 || data == '1'){
					$('#import-in-progress').css('display','none');
					$('#import-complete').css('display','block');
					setTimeout(function () {
							location.reload();
					},1500);
				}else{
					$('#import-in-progress').css('display','none');
					$('#import-error').css('display','block');
				}
				
			}
		});
	
	});
	
	
	$('body').on('click', '#delete_emp',function(){
		emp_id = $(this).data('emp_id');
	});
	
	$('#delete_employee').on('show.bs.modal', function(e) {
		$('#delet_emp_ref_id').val(emp_id);
	});
	
	
	$( "#delet_emp_fm" ).submit(function( e ) {
		e.preventDefault(); 
		var delet_emp_ref_id = $('#delet_emp_ref_id').val();
		
		$.ajax({
			url: 'ajax_employee.php',
			type: 'post',
			data: {delet_emp_ref_id:delet_emp_ref_id, action:'delet-employee-hard'},
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
	
	$( "#salary_increment_fm" ).submit(function( e ) {
		
		var increment_date = $('#increment_date').val();
		var increment_amount = $('#increment_amount').val();
		
		
		if(increment_date == ''){
			$('#increment_date').css('border-color','red');
		}else{
			$('#increment_date').css('border-color','');
		}
		
		if(increment_amount == ''){
			$('#increment_amount').css('border-color','red');
		}else{
			$('#increment_amount').css('border-color','');
		}
		
		if(increment_date != '' && increment_amount != ''){
			$( "#salary_increment_fm" ).submit();
		}else{
			e.preventDefault();
		}
		
	});
	
	$('body').on('click', '#increment-remove-btn',function(){
		increment_id = $(this).data('increment-id');
		
	});
	
	$('#delete_increment').on('show.bs.modal', function(e) {
		$('#delet_incremnt_id').val(increment_id);
	});
	
	$( "#delet_inc_fm" ).submit(function( e ) {
		
		e.preventDefault(); 
		var delet_incremnt_id = $('#delet_incremnt_id').val();
		
		$.ajax({
			url: 'ajax_employee.php',
			type: 'post',
			data: {delet_incremnt_id:delet_incremnt_id, action:'delet-increment'},
			success: function(data){
				if(data == 4){
						$("#sucess-delet").css('display', 'block');
							$("#sucess-delet").fadeTo(2000, 500).slideUp(500, function(){ $("#sucess-delet").slideUp(500); });
							
							setTimeout(function () {
									window.location = window.location;
							},1500);
							
					}else if(data == 5){
						$("#delet-error").css('display', 'block');
							$("#delet-error").fadeTo(2000, 500).slideUp(500, function(){ $("#delet-error").slideUp(500); });
					}
			}
		})
	});
	
	$('#company').on('change', function(){
		get_employees();
	});
	
	function get_employees(){
		var ref_company = $('#company').val();
		var ref_dep_id = $('#division').val();
		var position = $('#position').val();
		
		$.ajax({
			type: "POST",
			url: 'ajax_employee.php',
			data: {ref_company:ref_company, action: 'get_employees'},
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
		var ref_emp_id = $('#ref_emp_id').val();
		var cancel_date = $('#cancel_date').val();
		
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
		
		if(cancel_date == ''){
			$('#cancel_date').css('border','1px solid red');
		}else{
			$('#cancel_date').css('border','');
		}
		
		if(ref_company != '' && ref_company != null && ref_emp_id != '' && ref_emp_id != null && cancel_date != ''){
			document.getElementById('cancellation_form').submit();
		}
		
	});
	
})