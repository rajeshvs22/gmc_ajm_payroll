$(document).ready(function(){
	
	$( "#generate_salary_fm" ).submit(function( e ) {
		e.preventDefault(); 
		var month = $('#month').val();
		var year = $('#year').val();
		var base_url = $('#base_url').val();
		var cmpy = $('#choose_company').val();
		
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
			window.location.replace(base_url+'payroll/salary_report.php?cmpy='+cmpy+'&month='+month+'&year='+year);
		}
	});	
	
	$( "#generate_project_salary_fm" ).submit(function( e ) {
        e.preventDefault();
        var month = $('#month').val();
        var year = $('#year').val();
        var project_id = $('#choose_project').val();
        var company = $('#choose_company').val();
        var base_url = $('#base_url').val();

        $('#select2-month-container').css('color', month ? '' : 'red');
        $('#select2-year-container').css('color', year ? '' : 'red');
        $('#select2-choose_project-container').css('color', project_id ? '' : 'red');

        if (month && year && project_id) {
            window.location.replace(base_url + 'payroll/salary_project_report.php?' + $.param({
                cmpy: company || '', project_id: project_id, month: month, year: year
            }));
        }
    });

	$( "#wps_salary_report_fm" ).submit(function( e ) {
		e.preventDefault(); 
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
			window.location.replace(base_url+'payroll/wps_salary_report.php?cmpy='+company+'&month='+month+'&year='+year);
		}
	});
	
	
	
});