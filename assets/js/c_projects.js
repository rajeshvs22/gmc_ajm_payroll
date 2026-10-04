$(document).ready(function(){
	var proj_id;
	var row_id;
	$( ".add_proj_emp_btn" ).on('click', function(){
		var emp_id = $('#emp_name').val();
		
		var emp_add_dt = $('#emp_add_dt').val();
		var emp_quit_dt = $('#emp_quit_dt').val();
		
		if(emp_name == ''){
			$('#emp_name').css('border-color','red');
		}else{
			$('#emp_name').css('border-color','');
		}
		
		if(emp_add_dt == ''){
			$('#emp_add_dt').css('border-color','red');
		}else{
			$('#emp_add_dt').css('border-color','');
		}
		
		if(emp_name != '' && emp_add_dt != ''){
			
			$.ajax({
				type: "POST",
				url: 'ajax_project.php',
				data: { emp_id:emp_id, operation:'check_and_get_emp_name_by_id' },
				success: function(data){
					var emp_dets = jQuery.parseJSON(data);
					if(emp_dets.emp_proj_status == 2){
						var append_from = '<div class="row"><div class="col-xl-5"><div class="form-group"><input type="text" name="emp[]" id="" class="form-control" value="'+emp_dets.emp_name+'" READONLY><input type="hidden" name="emp_id[]" value="'+emp_id+'" id="emp_id"></div></div><div class="col-xl-2"><div class="form-group"><input type="text" name="join_dt[]" class="form-control datetimepicker" id="join_dt" value="'+emp_add_dt+'" READONLY></div></div><div class="col-xl-2"><div class="form-group"><input type="text" name="quit_dt[]" class="form-control datetimepicker" id="quit_dt" value="'+emp_quit_dt+'" READONLY></div></div><div class="col-xl-1"></div><div class="col-xl-1"><button type="button" class="btn btn-danger proj-emp-remove-btn">Remove</button></div></div>';
					
						$('.list-of-proj-employees').append(append_from);
						$('#emp_name').val('').trigger("change");
						$('#add_proj_emp_fm').trigger("reset");
					}else{
						$('#error-employee-busy').css('display', 'block');
						setTimeout(function () {
								$('#error-employee-busy').css('display', 'none');
							},1500);
					}
					
				}
			});
			
			
			
			
			
		}
		
	});
	
	
	
	
	$( ".save-project" ).on('click', function(  ) {
		
		$('.loader').css('display','block');
		var proj_name = $('#proj_name').val();
		var proj_loc = $('#proj_loc').val();
		var proj_end_dt = $('#proj_end_dt').val();
		var proj_desc = $('#proj_desc').val();
		
		var emp_id = $("input[name='emp_id[]']").map(function(){return $(this).val();}).get();
		var join_dt = $("input[name='join_dt[]']").map(function(){return $(this).val();}).get();
		var quit_dt = $("input[name='quit_dt[]']").map(function(){return $(this).val();}).get();
		
		if(proj_name == ''){
			$('#proj_name').css('border-color', 'red');
		}else{
			$('#proj_name').css('border-color', '');
		}
		
		if(proj_loc == ''){
			$('#proj_loc').css('border-color','red');
		}else{
			$('#proj_loc').css('border-color','');
		}
		
		if(proj_end_dt == ''){
			$('#proj_end_dt').css('border-color','red');
		}else{
			$('#proj_end_dt').css('border-color','');
		}
		
		/* if(proj_desc == ''){
			$('#proj_desc').css('border-color','red');
		}else{
			$('#proj_desc').css('border-color','');
		} */
		
		if(proj_name != '' && proj_loc != '' && proj_end_dt !=''){
			var formData = new FormData(document.getElementById('project_add_form'));
			formData.append('operation', 'insert-project');
			
			console.log(formData);
			$.ajax({
				type: "POST",
				url: 'ajax_project.php',
				data: { proj_name:proj_name, proj_loc:proj_loc, proj_end_dt:proj_end_dt, proj_desc:proj_desc, emp_id:emp_id, join_dt:join_dt, quit_dt:quit_dt, operation:'insert-project' },
				success: function(data){
					console.log('working');
					$('.loader').css('display','none');
					window.location.href = data;
				}
			});
		}else{
			$('.loader').css('display','none');
		}
		
	});
	
	
	$( ".update-project" ).on('click', function(  ) {
		
		$('.loader').css('display','block');
		var proj_name = $('#proj_name').val();
		var proj_loc = $('#proj_loc').val();
		var proj_end_dt = $('#proj_end_dt').val();
		var proj_desc = $('#proj_desc').val();
		var ref_proj_id = $('#ref_proj_id').val();
		
		var emp_id = $("input[name='emp_id[]']").map(function(){return $(this).val();}).get();
		var join_dt = $("input[name='join_dt[]']").map(function(){return $(this).val();}).get();
		var quit_dt = $("input[name='quit_dt[]']").map(function(){return $(this).val();}).get();
		
		if(proj_name == ''){
			$('#proj_name').css('border-color', 'red');
		}else{
			$('#proj_name').css('border-color', '');
		}
		
		if(proj_loc == ''){
			$('#proj_loc').css('border-color','red');
		}else{
			$('#proj_loc').css('border-color','');
		}
		
		if(proj_end_dt == ''){
			$('#proj_end_dt').css('border-color','red');
		}else{
			$('#proj_end_dt').css('border-color','');
		}
		
		/* if(proj_desc == ''){
			$('#proj_desc').css('border-color','red');
		}else{
			$('#proj_desc').css('border-color','');
		} */
		
		if(proj_name != '' && proj_loc != '' && proj_end_dt !=''){
			var formData = new FormData(document.getElementById('project_add_form'));
			formData.append('operation', 'insert-project');
			
			console.log(formData);
			$.ajax({
				type: "POST",
				url: 'ajax_project.php',
				data: { ref_proj_id:ref_proj_id, proj_name:proj_name, proj_loc:proj_loc, proj_end_dt:proj_end_dt, proj_desc:proj_desc, emp_id:emp_id, join_dt:join_dt, quit_dt:quit_dt, operation:'update-project' },
				success: function(data){
					console.log('working');
					$('.loader').css('display','none');
					window.location.href = data;
				}
			});
		}else{
			$('.loader').css('display','none');
		}
		
	});
	
	$('body').on('click', '.proj-emp-remove-btn', function(){
		$(this).closest("div.row").remove();
	});
	
	$('body').on('click', '.proj-emp-quit-btn', function(){
		
	});
	
	$('body').on('click', '.proj-emp-edit-btn', function(){
		proj_id = $(this).data('proj-id');
		row_id = $(this).data('ref-emp-id');
		$('#proj_id').val(proj_id);
		$('#row-id').val(row_id);
		
	});
	
	$('#edit-project').on('show.bs.modal', function(e) {
		$.ajax({
			type: "POST",
			url: 'ajax_project.php',
			data: { emp_id:row_id, proj_id:proj_id, operation:'get_proj_dets_for_edit' },
			success: function(data){
				var proj_dets = jQuery.parseJSON(data);
				console.log(proj_dets.emp_id);
				
				$('#edit_emp_id').val(proj_dets.emp_id);
				$('#row_id').val(proj_dets.emp_id);
				$('#edit_emp_name').val(proj_dets.emp_name);
				
				var emp_joined_date = $('#join_dt_'+proj_dets.emp_id).val();
				var emp_quite_date =  $('#quit_dt_'+proj_dets.emp_id).val();
				$('#edit_emp_add_dt').val(emp_joined_date);
				$('#edit_emp_quit_dt').val(emp_quite_date);
			}
		})
		
	});
	
	$('body').on('click', '.edit_proj_emp_btn', function(){
		var emp_name = $('#edit_emp_name').val();
		
		var emp_add_dt = $('#edit_emp_add_dt').val();
		var emp_quit_dt = $('#edit_emp_quit_dt').val();
		
		var row_id = $('#row_id').val();
		var emp_id = row_id;
		
		if(emp_name == ''){
			$('#emp_name').css('border-color','red');
		}else{
			$('#emp_name').css('border-color','');
		}
		
		if(emp_add_dt == ''){
			$('#emp_add_dt').css('border-color','red');
		}else{
			$('#emp_add_dt').css('border-color','');
		}
		
		if(emp_name != '' && emp_add_dt != ''){
			
		}
		
		
		$('.row-'+row_id).empty();
				
		$('.row-'+row_id).html('<div class="col-xl-5"><div class="form-group"><input type="text" name="emp[]" id="'+emp_name+'" class="form-control" value="'+emp_name+'" READONLY><input type="hidden" name="emp_id[]" value="'+emp_id+'" id="emp_id"></div></div><div class="col-xl-2"><div class="form-group"><input type="text" name="join_dt[]" class="form-control datetimepicker" id="join_dt_'+emp_id+'" value="'+emp_add_dt+'" READONLY></div></div><div class="col-xl-2"><div class="form-group"><input type="text" name="quit_dt[]" class="form-control datetimepicker" id="quit_dt_'+emp_id+'" value="'+emp_quit_dt+'" READONLY></div></div><div class="col-xl-1"><button type="button" class="btn btn-primary proj-emp-edit-btn" data-toggle="modal" data-target="#edit-project" data-proj-id="'+proj_id+'" data-ref-emp-id="'+emp_id+'">Edit</button></div><div class="col-xl-1"><button type="button" class="btn btn-danger proj-emp-remove-btn">Remove</button></div>');
		
		//<button type="button" class="btn btn-primary proj-emp-edit-btn" data-toggle="modal" data-target="#edit-project" data-proj-id="'+proj_id+'" data-ref-emp-id="'+emp_id+'">Edit</button>
	});
	
	
	
})