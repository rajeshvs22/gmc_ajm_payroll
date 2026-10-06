$(document).ready(function(){
	var emp_id = emp_code = emp_name = emp_dep = monthyr = cmpy = "";
	//alert();
	$( "#generate_attendance_fm" ).submit(function( e ) {
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
			window.location.replace(base_url+'attendance/generate_attendance.php?cmpy='+company+'&month='+month+'&year='+year);
		}
		
		
	});
	
	$('#generate_attenedance_popup').on('show.bs.modal', function(e) {
		//$(this).find('.edit_employee_for_salary').text(e.relatedTarget.id);
		
		$('#emp_name').val(emp_name);
		$('#division').val(emp_dep);
		$('#emp_code').val(emp_code);
		
			
		
		$.ajax({
			url: 'attendance_ajax.php',
			type: 'post',
			data: { emp_id:emp_id, monthyr:monthyr,action:'is-exists' },
			success: function(data){
				if(data != ''){
					data = JSON.parse(data);
					console.log(data.no_of_wdays);	
					$('#e_overtime').val(data.e_overtime);
					$('#no_of_wdays').val(data.no_of_wdays);
					$('#action').val('update-salary');
				}else{
					$('#e_overtime').val('');
					$('#no_of_wdays').val('');
					$('#action').val('insert-salary');
				}
			}
		});
		
		
	});
	
	$('.attendance-edit-btn').on('click', function(){
		$('#no_of_wdays').attr('readonly', false);
		$('#e_overtime').attr('readonly', false);
		emp_id = $(this).data('emp-id');
		emp_name = $(this).data('emp-name');
		emp_code = $(this).data('emp-code');
		emp_dep = $(this).data('emp-dep');
		monthyr = $(this).data('monthyr');
		
	})
	
	$('.submit-btn').not('#add-sal-form .submit-btn').on('click', function(){
		$('.loader').css('display', 'block');
	});

    $('#add-sal-form .attendance-working-days').on('input', function () {
        this.setCustomValidity('');
        var days = this.valueAsNumber;
        var maxDays = Number(this.max);
        if (this.value === '' || !Number.isFinite(days) || days < 0 || days > maxDays) {
            this.setCustomValidity('Working days must be between 0 and ' + maxDays + ' for the selected month.');
        }
    });

    var attendanceForm = document.getElementById('add-sal-form');
    if (attendanceForm) {
        // Native validation can block submit entirely; invalid does not bubble.
        attendanceForm.addEventListener('invalid', function () {
            $('.loader').hide();
        }, true);
    }

    $('#add-sal-form').on('click', 'button[type="submit"]', function (e) {
        var form = this.form;
        $('.loader').hide();
        if (form && !form.checkValidity()) {
            e.preventDefault();
            form.reportValidity();
        }
    }).on('submit', function (e) {
        e.preventDefault();
        if (document.readyState !== 'complete') {
            return;
        }
        var form = this;
        var buttons = $(form).find('button[type="submit"]');
        if (buttons.prop('disabled')) {
            return;
        }
        if (!this.checkValidity()) {
            e.preventDefault();
            this.reportValidity();
            $('.loader').hide();
            return;
        }
        var attendance = {};
        var fields = this.querySelectorAll('input[name^="no_of_wdays_"], input[name^="e_overtime_"]');
        fields.forEach(function (field) {
            attendance[field.name] = field.value;
        });
        var status = $('#attendance-save-status');
        status.hide().removeClass('alert-success alert-danger');
        buttons.prop('disabled', true).text('Saving...');
        fields.forEach(function (field) {
            field.disabled = true;
        });
        $('.loader').show();
        $.ajax({
            url: window.location.href,
            type: 'POST',
            dataType: 'json',
            data: {
                attendance_ajax: '1',
                submit: 'submit',
                month: form.querySelector('input[name="month"]').value,
                year: form.querySelector('input[name="year"]').value,
                attendance_payload: JSON.stringify(attendance)
            },
            success: function (data) {
                status.addClass(data.success ? 'alert-success' : 'alert-danger')
                    .text(data.message || 'Attendance could not be saved. Please try again.').show();
            },
            error: function (xhr) {
                status.addClass('alert-danger').text(
                    xhr.responseJSON && xhr.responseJSON.message
                        ? xhr.responseJSON.message
                        : 'Unable to confirm attendance was saved. Please try again.'
                ).show();
            },
            complete: function () {
                $('.loader').hide();
                fields.forEach(function (field) {
                    field.disabled = false;
                });
                buttons.prop('disabled', false).text('Save All');
            }
        });
    });
	
	
	$('#attendance-main-fm').submit(function( e ) {
		e.preventDefault(); 
		/* emp_id
		monthyr */
		var no_of_wdays = $('#no_of_wdays').val(); 
		var e_overtime = $('#e_overtime').val(); 
		var action = $('#action').val();
		if(no_of_wdays == ''){
			$('#no_of_wdays').css('border-color','red');
		}else{
			$('#no_of_wdays').css('border-color','');
		}
		
		if(e_overtime == ''){
			$('#e_overtime').css('border-color','red');
		}else{
			$('#e_overtime').css('border-color','');
		}
		if(no_of_wdays != '' && e_overtime != ''){
			console.log(emp_id +'-'+monthyr+'-'+no_of_wdays+'-'+e_overtime);
			$.ajax({
					url: 'attendance_ajax.php',
					type: 'post',
					data: { emp_id:emp_id, monthyr:monthyr, no_of_wdays:no_of_wdays, e_overtime:e_overtime, action:action },
					success: function(data){
						
						var msg = "";
						if(action == 'update-salary'){
							msg = "Updated Sucessfully";
						}else if(action == 'insert-salary'){
							msg = "Inserted Sucessfully";
						}
						
						if(data){
							
							$('#status-msg').text(msg);
							$("#attendance-sucess").css('display', 'block');
							$(".alert-success").fadeTo(2000, 500).slideUp(500, function(){
									$(".alert-success").slideUp(500);
									$('#status-msg').html('');
							});
						}
					}
			});
		}
	});
	
	$('.view-btn').on('click', function(){
		$('#no_of_wdays').attr('readonly', 'readonly');
		$('#e_overtime').attr('readonly', 'readonly');
		
	});
	
	$('.delete_attendance').on('click', function(){
		month = $(this).data('month');
		year = $(this).data('year');
		cmpy = $(this).data('cmpy');
		
	});
	
	$('#delete_attendance').on('show.bs.modal', function(e) {
		$('#delet_attendance_month').val(month);
		$('#delet_attendance_year').val(year);
		$('#delet_attendance_cmpy').val(cmpy);
	});
	
	$( "#delet_attendance_fm" ).submit(function( e ) {
		e.preventDefault(); 
		var cmpy = $('#delet_attendance_cmpy').val();
		var month = $('#delet_attendance_month').val();
		var year = $('#delet_attendance_year').val();
		$.ajax({
			url: 'attendance_ajax.php',
			type: 'post',
			data: { cmpy:cmpy, month:month, year:year, action:'delet-attendance' },
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

    // Wait for all page resources, not just the attendance form's markup.
    function enableAttendanceSubmitButtons() {
        $('#generate_attendance_fm button[type="submit"]').prop('disabled', false);
        if (attendanceForm && attendanceForm.querySelector('.attendance-working-days')) {
            $('#add-sal-form button[type="submit"]').prop('disabled', false);
        }
    }
    if (document.readyState === 'complete') {
        enableAttendanceSubmitButtons();
    } else {
        window.addEventListener('load', enableAttendanceSubmitButtons, { once: true });
    }
});
