$(document).ready(function(){
	
	$('#company').on('change', function(){
		get_employees();
	});
	
	function get_employees(){
		var ref_company = $('#company').val();
				
		$.ajax({
			type: "POST",
			url: 'ajax_reports.php',
			data: {ref_company:ref_company, operation:'get_employees'},
			success: function(data){
				if(data != 0){
					$('#ref_emp_id').empty();
					$('#ref_emp_id').append(data);
				}
			}
		});
	}
	
	$( "#expanse-report-fm" ).submit(function( e ) {
		var ref_company = $('#company').val();
		var ref_emp_id = $('#ref_emp_id').val();
		
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
		
		if(ref_company != '' && ref_company != null && ref_emp_id !='' && ref_emp_id != null){
			document.getElementById("expanse-report-fm").submit();
		}else{
			e.preventDefault(); 
		}
		
	});
	
	
	
	
});