$(document).ready(function(){
	var expense_id = expense_name = '';
	$( "#add_expense_type_fm" ).submit(function( e ) {
		e.preventDefault(); 
		var expense_name = $('#expense_name').val();
		var action = $('#action').val();
		
		if(expense_name == ''){
			$('#expense_name').css('border-color','red');
		}else{
		$('#expense_name').css('border-color','');
			$.ajax({
				url: 'ajax_expense.php',
				type: 'post',
				data: {expense_name:expense_name, action:action},
				success: function(data){
					if(data == 1){
						$("#sucess-expense-added").css('display', 'block');
							$("#sucess-expense-added").fadeTo(2000, 500).slideUp(500, function(){ $("#sucess-expense-added").slideUp(500); });
							
							setTimeout(function () {
									location.reload();
							},1500);
							
					}else if(data == 3){
						$("#error-expense-exist").css('display', 'block');
							$("#error-expense-exist").fadeTo(2000, 500).slideUp(500, function(){ $("#error-expense-exist").slideUp(500); });
					}
				}
			})
		}
	});
	
	$('body').on('click', '.edit_expense_btn',function(){
		expense_id = $(this).data('expense-id');
		expense_name = $(this).data('expense-name');
		
	});
		
	$('#edit_expense').on('show.bs.modal', function(e) {
		$('#expense_name_edit').val(expense_name);
	});
	
	$( "#expense_type_edit_fm" ).submit(function( e ) {
		e.preventDefault(); 
		var expense_name_edit = $('#expense_name_edit').val();
		var ref_expense_id = expense_id;
		
		if(expense_name_edit == ''){
			$('#expense_name_edit').css('border-color','red');
		}else{
			$('#expense_name_edit').css('border-color','');
			$.ajax({
				url: 'ajax_expense.php',
				type: 'post',
				data: {expense_name_edit:expense_name_edit, ref_expense_id:ref_expense_id, action:'update-expense'},
				success: function(data){
					if(data == 2){
						$("#sucess-expense-updated").css('display', 'block');
							$("#sucess-expense-updated").fadeTo(2000, 500).slideUp(500, function(){ $("#sucess-expense-updated").slideUp(500); });
							
							setTimeout(function () {
									location.reload();
							},1500);
							
					}else if(data == 3){
						$("#error-expense-exist-onedit").css('display', 'block');
							$("#error-expense-exist-onedit").fadeTo(2000, 500).slideUp(500, function(){ $("#error-expense-exist-onedit").slideUp(500); });
					}
				}
			});
		}
	});
	
	
	$('body').on('click', '.delete_expense',function(){
		expense_id = $(this).data('expense-id');
	});
	
	$('#delete_expense').on('show.bs.modal', function(e) {
		$('#delet_expense_ref_id').val(expense_id);
	});
	
	$( "#delet_expense_type_fm" ).submit(function( e ) {
		e.preventDefault(); 
		var delet_expense_ref_id = $('#delet_expense_ref_id').val();
		$.ajax({
			url: 'ajax_expense.php',
			type: 'post',
			data: {delet_expense_ref_id:delet_expense_ref_id, action:'delet-expense'},
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
	
	$('#company').on('change', function(){
		get_employees();
	});
	
	function get_employees(){
		var ref_company = $('#company').val();
				
		$.ajax({
			type: "POST",
			url: 'ajax_expense.php',
			data: {ref_company:ref_company, action:'get_employees'},
			success: function(data){
				if(data != 0){
					$('#ref_emp_id').empty();
					$('#ref_emp_id').append(data);
				}
			}
		});
	};
	
	$('.form-submit-btn').on('click', function(){
		var ref_company = $('#company').val();
		var ref_emp_id = $('#ref_emp_id').val();
		var expense_type = $('#expense_type').val();
		var expense_amount = $('#expense_amount').val();
		var expense_dt = $('#expense_dt').val();
		
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
		
		if(expense_type == '' || expense_type == null){
			$('#select2-expense_type-container').css('border','1px solid red');
		}else{
			$('#select2-expense_type-container').css('border','');
		}
		
		if(expense_dt == ''){
			$('#expense_dt').css('border','1px solid red');
		}else{
			$('#expense_dt').css('border','');
		}
		
		if(expense_amount == '' || expense_amount < 1){
			$('#expense_amount').css('border','1px solid red');
		}else{
			$('#expense_amount').css('border','');
		}
		
		if(ref_company != '' && ref_company != null && ref_emp_id != '' && ref_emp_id != null && expense_dt != '' && expense_amount != '' && expense_amount > 0){
			document.getElementById("add_expense_form").submit();
		}
	});
	
	$('.delete_expense').on('click', function(){
		expense_id = $(this).data('expense-id');
	});
	
	$('#delete_expense').on('show.bs.modal', function(e) {
		$('#delet_expense_ref_id').val(expense_id);
	});
	
	
	$( "#delet_expense_fm" ).submit(function( e ) {
		e.preventDefault(); 
		var delet_expense_ref_id = $('#delet_expense_ref_id').val();
		$.ajax({
			url: 'ajax_expense.php',
			type: 'post',
			data: {delet_expense_ref_id:delet_expense_ref_id, action:'delet-expense-dets'},
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