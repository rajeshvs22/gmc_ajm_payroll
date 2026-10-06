$(document).ready(function(){
	var month = year = cmpy = "";
	
	$( "#generate_salary_fm" ).submit(function( e ) {
		e.preventDefault(); 
		if (document.readyState !== 'complete') {
			return;
		}
		var month = $('#month').val();
		var year = $('#year').val();
		var base_url = $('#base_url').val();
		var company = $('#choose_company').val();
		
		if(month  == '' || month === null){
			$('#select2-month-container').css('color','red');
		}else{
			$('#select2-month-container').css('color','');
		}
		
		if(year  == '' || year === null){
			$('#select2-year-container').css('color','red');
		}else{
			$('#select2-year-container').css('color','');
		}
		
		if(month  != '' && month != null && year  != '' && year != null){
			//console.log(month+'asd'+year);
			window.location.replace(base_url+'payroll/generate_salary.php?cmpy='+company+'&month='+month+'&year='+year);
		}
	});


	//for ontype value changes fo netpayment calculation
	var salary_for_this_month = allowance = deduct_loan = overtime_sal = 0;
	
	$('.salary_for_this_month').on('keyup', function(){
		var row_id = $(this).attr("data-id");
		net_pay_calcultion(row_id);
	})
	
	$('.allowance').on('keyup', function(){
		var row_id = $(this).attr("data-id");
		net_pay_calcultion(row_id);
	})
	
	$('.deduct_loan').on('keyup', function(){
		var row_id = $(this).attr("data-id");
		net_pay_calcultion(row_id);
	})
	
	function net_pay_calcultion(row_id){
		salary_for_this_month = $('.salary_for_this_month_'+row_id).val();
		
		allowance = parseInt($('.allowance_'+row_id).val());
		food_allowance = parseInt($('.food_allowance_'+row_id).val());
		conveyance_allowance = parseInt($('.conveyance_allowance_'+row_id).val());
		medical_allowance = parseInt($('.medical_allowance_'+row_id).val());
		housing_allowance = parseInt($('.housing_allowance_'+row_id).val());
		
		console.log(food_allowance+'xx');
		
		var total_allowance =  allowance + food_allowance + conveyance_allowance + medical_allowance + housing_allowance ;
		
		deduct_loan = $('.deduct_loan_'+row_id).val();
		overtime_sal = $('.overtime_sal_for_curent_month_'+row_id).val();
		
		
		if(isNaN(salary_for_this_month) || salary_for_this_month == '') {
			salary_for_this_month = 0;
		}else{
			salary_for_this_month = parseFloat(salary_for_this_month);
		}
		
		if(isNaN(overtime_sal) || overtime_sal == '') {
			overtime_sal = 0;
		}else{
			overtime_sal = parseFloat(overtime_sal);
		}
		
		if(isNaN(allowance) || allowance == '') {
			allowance = 0;
		}else{
			allowance = parseFloat(allowance);
		}
		
		if(isNaN(deduct_loan) || deduct_loan == '') {
			deduct_loan = 0;
		}else{
			deduct_loan = parseFloat(deduct_loan);
		}
		
		var total_payable = salary_for_this_month + total_allowance + overtime_sal;
		var net_payable =  total_payable - deduct_loan;
	
		$('.total_payable_'+row_id).val(total_payable);
		$('.net_payable_'+row_id).val(net_payable);
	}
	
	$('#generate_salary_btn').on('submit', function(e){
		e.preventDefault();
		if (document.readyState !== 'complete') {
			return;
		}
		var buttons = $(this).find('.generate_salary_submit-btn');
		if (buttons.prop('disabled')) { return; }
		var payload = {};
		$(this).serializeArray().forEach(function(field) {
			if (field.name.endsWith('[]')) {
				var name = field.name.slice(0, -2);
				if (!payload[name]) { payload[name] = []; }
				payload[name].push(field.value);
			} else {
				payload[field.name] = field.value;
			}
		});
		buttons.prop('disabled', true).val('Saving...');
		$('.loader').show();

		$.ajax({
			type: "POST",
			url: 'ajax_generate_salary.php',
			data: JSON.stringify(payload),
			contentType: 'application/json; charset=utf-8',
			dataType: 'json',
			success: function(data){
				if (data.success && data.saved_count === payload.attendance_id.length) {
					alert('Saved salary for ' + data.saved_count + ' employees.');
					location.reload();
				} else {
					alert(data.message || 'Salary was not saved completely. Please try again.');
				}
			},
			error: function(xhr){
				alert(xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Unable to save salary. Please try again.');
			},
			complete: function(){
				$('.loader').hide();
				buttons.prop('disabled', false).val('Save All');
			}
		});
	});//end form submit
	
	$('.submit-btn').on('click', function(){
		
		var cmpy = $('#cmpy').val();
		var salary_month = $('#salary_month').val();
		var salary_year = $('#salary_year').val();
		var total_length = $('.ref_attendance_id').length;
		var curent_id;
		var no_of_wdays = []; var e_overtime = []; var salary = []; var over_time_hour_rate = []; var salary_for_this_month1 = []; var overtime_sal_for_curent_month = []; var allowance1 = []; var total_payable1 = []; var loan_amount = []; var deduct_loan1 = []; var net_payable1  = []; var emp_id = []; var ref_attendance_id = [];
		var emp_code = []; var emp_name = []; var position_name = []; var work_status = [];
		var count = 1;
		$('.ref_attendance_id').each(function(i, obj) {
				
			curent_id = $(this).val();
			
			emp_code[curent_id] = $('.emp_code_'+curent_id).val();
			emp_name[curent_id] = $('.emp_name_'+curent_id).val();
			position_name[curent_id] = $('.position_name_'+curent_id).val();
			work_status[curent_id] = $('.work_status'+curent_id).val();	
			no_of_wdays[curent_id] = $('.no_of_wdays_'+curent_id).val();			
			e_overtime[curent_id] = $('.e_overtime_'+curent_id).val();			
			salary[curent_id] = $('.salary_'+curent_id).val();			
			over_time_hour_rate[curent_id] = $('.over_time_hour_rate_'+curent_id).val();
			
			salary_for_this_month1[curent_id] = $('.salary_for_this_month_'+curent_id).val();
			
			overtime_sal_for_curent_month[curent_id] = $('.overtime_sal_for_curent_month_'+curent_id).val();
			
			allowance1[curent_id] = $('.allowance_'+curent_id).val();
			
			total_payable1[curent_id] = $('.total_payable_'+curent_id).val();
			
			loan_amount[curent_id] = $('.loan_amount_'+curent_id).val();
			
			deduct_loan1[curent_id] =  $('.deduct_loan_'+curent_id).val();
			
			net_payable1[curent_id] =  $('.net_payable_'+curent_id).val();
			
			emp_id[curent_id] = $('.emp_id_'+curent_id).val();
			
			ref_attendance_id[curent_id] = $('.ref_attendance_id_'+curent_id).val();

			
			if(((count%60) == 0) || (total_length == count)){
				
				emp_code 		= emp_code.filter(item => item);
				emp_name 		= emp_name.filter(item => item);
				position_name 	= position_name.filter(item => item);
				work_status 		= work_status.filter(item => item);
						
				net_payable1 = net_payable1.filter(item => item);
				deduct_loan1 = deduct_loan1.filter(item => item);
				loan_amount = loan_amount.filter(item => item);
				total_payable1 = total_payable1.filter(item => item);
				allowance1 = allowance1.filter(item => item);
				overtime_sal_for_curent_month = overtime_sal_for_curent_month.filter(item => item);
				salary_for_this_month1 = salary_for_this_month1.filter(item => item);
				over_time_hour_rate = over_time_hour_rate.filter(item => item);
				salary = salary.filter(item => item);
				e_overtime = e_overtime.filter(item => item);
				no_of_wdays = no_of_wdays.filter(item => item);
				emp_id = emp_id.filter(item => item);
				ref_attendance_id = ref_attendance_id.filter(item => item);
				

				$.ajax({
					type: "POST",
					url: 'ajax_generate_salary.php',
					data: { 
						cmpy:cmpy, 
						salary_month:salary_month, 
						salary_year:salary_year, 
						emp_code:emp_code,
						emp_name:emp_name,
						position_name:position_name,
						work_status:work_status,
						no_of_wdays:no_of_wdays, 
						e_overtime:e_overtime, 
						salary:salary, 
						over_time_hour_rate:over_time_hour_rate, 
						salary_for_this_month1:salary_for_this_month1, 
						overtime_sal_for_curent_month:overtime_sal_for_curent_month, 
						allowance1:allowance1, 
						total_payable1:total_payable1, 
						loan_amount:loan_amount, 
						deduct_loan1:deduct_loan1, 
						net_payable1:net_payable1, 
						emp_id:emp_id, 
						ref_attendance_id:ref_attendance_id },
					beforeSend: function(){
						
					},
					success: function(data){
						$('.loader').css('display', 'block');
					}
				});
				//empty the all array
				no_of_wdays = []; e_overtime = []; salary = []; over_time_hour_rate = []; salary_for_this_month1 = []; overtime_sal_for_curent_month = [];  allowance1 = []; total_payable1 = []; loan_amount = []; deduct_loan1 = []; net_payable1 = []; emp_id = []; ref_attendance_id = [];
				emp_code = []; emp_name = []; position_name = []; work_status = [];
			}
			
			count++;
		});
		location.reload();
	})
	
	
	
	$('.cloase_salary').on('click', function(){
		
		if(confirm("Are you sure to close the salary?")) {
			var that = $(this);
			month 	= $(this).data('month');
			year 	= $(this).data('year');
			cmpy 	= $(this).data('cmpy');
			$.ajax({
				url: 'payroll_ajax.php',
				type: 'post',
				data: { cmpy:cmpy, month:month, year:year, action:'close-salary' },
				success: function(data){
					that.css({'color':'#ccc'});
					that.removeClass('cloase_salary');
					$("#sucess-close-salary").css('display', 'block');
					$("#sucess-close-salary").fadeTo(2000, 500).slideUp(500, function(){ $("#sucess-close-salary").slideUp(500); });
				}
			
			});//end ajax	
		}		
		
	});//end fun
	
	
	$('.delete_salary').on('click', function(){
		month = $(this).data('month');
		year = $(this).data('year');
		cmpy = $(this).data('cmpy');
		
	});
	
	$('#delete_salary').on('show.bs.modal', function(e) {
		$('#delet_salary_month').val(month);
		$('#delet_salary_year').val(year);
		$('#delet_salary_cmpy').val(cmpy);
	});
	
	$( "#delet_salary_fm" ).submit(function( e ) {
		e.preventDefault(); 
		var cmpy = $('#delet_salary_cmpy').val();
		var month = $('#delet_salary_month').val();
		var year = $('#delet_salary_year').val();
		$.ajax({
			url: 'payroll_ajax.php',
			type: 'post',
			data: { cmpy:cmpy, month:month, year:year, action:'delet-salary' },
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
		
		});
	});
	
	$( "#add_lv_salary_bonus_form" ).submit(function( e ) {
		var ref_emp_id = $('#ref_emp_id').val();
		var bonus_amount = $('#bonus_amount').val();
		var leave_salary = $('#leave_salary').val();
		var cmpy = $('#cmpy').val();
		var month = $('#month').val();
		var year = $('#year').val();
		
		
		if(ref_emp_id == '' || ref_emp_id == null){
			$('#select2-ref_emp_id-container').css('border','1px solid red');
		}else{
			$('#select2-ref_emp_id-container').css('border','');
		}
		
		if(bonus_amount == ''){
			$('#bonus_amount').css('border','1px solid red');
		}else{
			$('#bonus_amount').css('border','');
		}
		
		if(leave_salary == ''){
			$('#leave_salary').css('border','1px solid red');
		}else{
			$('#leave_salary').css('border','');
		}
		
		if(ref_emp_id != '' && ref_emp_id != null && leave_salary != '' && bonus_amount !=''){
			$.ajax({
				type: "POST",
				url: 'ajax_lv_salary_bonus.php',
				data: {ref_emp_id:ref_emp_id, bonus_amount:bonus_amount, cmpy:cmpy, leave_salary:leave_salary, month:month, year:year, operation:'save_lv_sal_bonus'},
				success: function(data){
					if(data == 1){
						$("#sucess-lv_salary_bonus").css('display', 'block');
							$("#sucess-lv_salary_bonus").fadeTo(2000, 500).slideUp(500, function(){ $("#sucess-lv_salary_bonus").slideUp(500); });
							
							setTimeout(function () {
									location.reload();
							},1500);
							
					}else if(data == 3){
						$("#error-lv_salary_bonus-exist").css('display', 'block');
							$("#error-lv_salary_bonus-exist").fadeTo(2000, 500).slideUp(500, function(){ $("#error-lv_salary_bonus-exist").slideUp(500); });
					}
				}
			});
		}
		
		e.preventDefault(); 
	});
	
	var bonus_id,ref_emp_id;
	$('body').on('click', '.edit_lvsal_bonus_btn', function(){
		bonus_id = $(this).data('bonus-id');
		ref_emp_id = $(this).data('ref-emp-id');
		$('#edit_ref_emp_id').val(ref_emp_id); 
		$('#edit_ref_emp_id').trigger('change');
		
		
	});
	
	$('#edit_lvsal_bonus').on('show.bs.modal', function(e) {
		$('#ref_bonus_id').val(bonus_id);
		$.ajax({
			type: "POST",
			url: 'ajax_lv_salary_bonus.php',
			data: {bonus_id:bonus_id, operation:'get_lv_sal_bonus'},
			success: function(data){
				var data=JSON.parse(data);
				$('#edit_bonus_amount').val(data.bonus);
				$('#edit_leave_salary').val(data.leave_Salary);
			}
		});
	});
	
	$( "#edit_lv_salary_bonus_form" ).submit(function( e ) {
		var bonus_amount = $('#edit_bonus_amount').val();
		var leave_salary = $('#edit_leave_salary').val();
		var ref_bonus_id = $('#ref_bonus_id').val();
		
		if(bonus_amount == ''){
			$('#bonus_amount').css('border','1px solid red');
		}else{
			$('#bonus_amount').css('border','');
		}
		
		if(leave_salary == ''){
			$('#leave_salary').css('border','1px solid red');
		}else{
			$('#leave_salary').css('border','');
		}
		
		if(leave_salary != '' && bonus_amount !=''){
			$.ajax({
				type: "POST",
				url: 'ajax_lv_salary_bonus.php',
				data: { ref_bonus_id:ref_bonus_id, bonus_amount:bonus_amount, cmpy:cmpy, leave_salary:leave_salary, operation:'update_lv_sal_bonus'},
				success: function(data){
					if(data == 2){
						$("#edit-sucess-lv_salary_bonus").css('display', 'block');
							$("#edit-sucess-lv_salary_bonus").fadeTo(2000, 500).slideUp(500, function(){ $("#edit-sucess-lv_salary_bonus").slideUp(500); });
							
							setTimeout(function () {
									location.reload();
							},1500);
							
					}
				}
			})
		}
		
		
		e.preventDefault(); 
	});
	
	
	$('body').on('click', '.delete_lvsal_bonus', function(){
		bonus_id = $(this).data('bonus-id');
		$('#delet_levsal_bonus_id').val(bonus_id);
	});
	
	
	$( "#delet_lvsal_bonus_fm" ).submit(function( e ) {
		e.preventDefault(); 
		var ref_bonus_id = $('#delet_levsal_bonus_id').val();
		
		$.ajax({
			url: 'ajax_lv_salary_bonus.php',
			type: 'post',
			data: { ref_bonus_id:ref_bonus_id, operation:'delet_levsal_bonus' },
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
		
		});
		
	});

	
	
    // Enable submission only after the whole page and its resources have loaded.
    function enableSalarySubmitButtons() {
        $('#generate_salary_fm button[type="submit"]').prop('disabled', false);
        if ($('#generate_salary_btn input[name="attendance_id[]"]').length > 0) {
            $('#generate_salary_btn .generate_salary_submit-btn').prop('disabled', false);
        }
    }
    if (document.readyState === 'complete') {
        enableSalarySubmitButtons();
    } else {
        window.addEventListener('load', enableSalarySubmitButtons, { once: true });
    }
})
