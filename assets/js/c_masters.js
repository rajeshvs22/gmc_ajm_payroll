$(document).ready(function(){
	var dep_name = dep_id = "";
	$( "#department_master_fm" ).submit(function( e ) {
		e.preventDefault(); 
		var dep_name = $('#dep_name').val();
		var ref_comp_id = $('#ref_comp_id').val();
		var action = $('#action').val();
		console.log('working');
		if(dep_name == ''){
			$('#dep_name').css('border-color','red');
		}else{
			$('#dep_name').css('border-color','');
			$.ajax({
				url: 'ajax_masters.php',
				type: 'post',
				data: {dep_name:dep_name, ref_comp_id:ref_comp_id, action:action},
				success: function(data){
					if(data == 1){
						$("#sucess-dep-added").css('display', 'block');
							$("#sucess-dep-added").fadeTo(2000, 500).slideUp(500, function(){ $("#sucess-dep-added").slideUp(500); });
							
							setTimeout(function () {
									location.reload();
							},1500);
							
					}else if(data == 3){
						$("#error-dep-exist").css('display', 'block');
							$("#error-dep-exist").fadeTo(2000, 500).slideUp(500, function(){ $("#error-dep-exist").slideUp(500); });
					}
				}
			})
		}
	});
	
	$('body').on('click', '.edit_department_btn', function(){
		
		dep_id = $(this).data('dep-id');
		dep_name = $(this).data('dep-name');
		console.log(dep_id, dep_name);
	});
	
	$('#edit_department').on('show.bs.modal', function(e) {
		$('#dep_name_edit').val(dep_name);
	});
	
	
	$( "#department_master_edit_fm" ).submit(function( e ) {
		e.preventDefault(); 
		var dep_name_edit = $('#dep_name_edit').val();
		var ref_dep_id = dep_id;
		var action = $('#action').val();
		console.log('workingas'+dep_id);
		if(dep_name_edit == ''){
			$('#dep_name_edit').css('border-color','red');
		}else{
			$('#dep_name_edit').css('border-color','');
			$.ajax({
				url: 'ajax_masters.php',
				type: 'post',
				data: {dep_name_edit:dep_name_edit, ref_dep_id:ref_dep_id, action:'update-department'},
				success: function(data){
					if(data == 2){
						$("#sucess-dep-updated").css('display', 'block');
							$("#sucess-dep-added").fadeTo(2000, 500).slideUp(500, function(){ $("#sucess-dep-added").slideUp(500); });
							
							setTimeout(function () {
									location.reload();
							},1500);
							
					}else if(data == 3){
						$("#error-dep-exist-onedit").css('display', 'block');
							$("#error-dep-exist-onedit").fadeTo(2000, 500).slideUp(500, function(){ $("#error-dep-exist-onedit").slideUp(500); });
					}
				}
			});
		}
	});
	
	$('body').on('click', '.delete_dep',function(){
		dep_id = $(this).data('dep-id');
	});
	
	$('#delete_dep').on('show.bs.modal', function(e) {
		$('#delet_dep_ref_id').val(dep_id);
	});
	
	
	$( "#delet_dep_fm" ).submit(function( e ) {
		e.preventDefault(); 
		var delet_dep_ref_id = $('#delet_dep_ref_id').val();
		$.ajax({
			url: 'ajax_masters.php',
			type: 'post',
			data: {delet_dep_ref_id:delet_dep_ref_id, action:'delet-department'},
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
	
	
	//for position master
	$( "#position_master_fm" ).submit(function( e ) {
		e.preventDefault(); 
		var position_name = $('#position_name').val();
		var division = $('#division').val();
		var action = $('#action').val();
		
		
		if(position_name == ''){
			$('#position_name').css('border-color','red');
		}else{
			$('#position_name').css('border-color','');
		}
		
		if(division == '' || division == null){
			$('#select2-division-container').css('border','1px solid red');
		}else{
			$('#select2-division-container').css('border','');
		}
		
		if(position_name != '' && ( division != '' || division != null )){
			
			$.ajax({
				url: 'ajax_masters.php',
				type: 'post',
				data: {position_name:position_name, division:division, action:action},
				success: function(data){
					if(data == 1){
						$("#sucess-position-added").css('display', 'block');
							$("#sucess-position-added").fadeTo(2000, 500).slideUp(500, function(){ $("#sucess-position-added").slideUp(500); });
							
							setTimeout(function () {
									location.reload();
							},1500);
							
					}else if(data == 3){
						$("#error-position-exist").css('display', 'block');
							$("#error-position-exist").fadeTo(2000, 500).slideUp(500, function(){ $("#error-position-exist").slideUp(500); });
					}
				}
			})
		}
	});
	
	$('body').on('click', '.edit_position_btn',function(){
		dep_id = $(this).data('dep-id');
		position_id = $(this).data('position-id');
		position_name = $(this).data('position-name');
	});
	
	$('#edit_position').on('show.bs.modal', function(e) {
		$('#position_name_edit').val(position_name);
		$('#division-edit').val(dep_id).trigger('change');
	});
	
	$( "#position_master_edit_fm" ).submit(function( e ) {
		e.preventDefault(); 
		var position_name_edit = $('#position_name_edit').val();
		var ref_position_id = position_id;
		///var action = $('#action').val();
		if(position_name_edit == ''){
			$('#position_name_edit').css('border-color','red');
		}else{
			$('#position_name_edit').css('border-color','');
			$.ajax({
				url: 'ajax_masters.php',
				type: 'post',
				data: {position_name_edit:position_name_edit, ref_position_id:ref_position_id, action:'update-position'},
				success: function(data){
					if(data == 2){
						$("#sucess-position-updated").css('display', 'block');
							$("#sucess-position-updated").fadeTo(2000, 500).slideUp(500, function(){ $("#sucess-position-updated").slideUp(500); });
							
							setTimeout(function () {
									location.reload();
							},1500);
							
					}else if(data == 3){
						$("#error-position-exist-onedit").css('display', 'block');
							$("#error-position-exist-onedit").fadeTo(2000, 500).slideUp(500, function(){ $("#error-position-exist-onedit").slideUp(500); });
					}
				}
			});
		}
	});
	
	$('body').on('click', '.delete_position',function(){
		position_id = $(this).data('position-id');
	});
	
	$('#delete_position').on('show.bs.modal', function(e) {
		$('#delet_position_ref_id').val(position_id);
	});
	
	
	$( "#delet_position_fm" ).submit(function( e ) {
		e.preventDefault(); 
		var delet_position_ref_id = $('#delet_position_ref_id').val();
		$.ajax({
			url: 'ajax_masters.php',
			type: 'post',
			data: {delet_position_ref_id:delet_position_ref_id, action:'delet-position'},
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
	
	
	//company
	$( "#add_company_master_fm" ).submit(function( e ) {
		e.preventDefault(); 
		var company_name = $('#company_name').val();
		var action = $('#action').val();
		
		if(company_name == ''){
			$('#company_name').css('border-color','red');
		}else{
			$('#company_name').css('border-color','');
			$.ajax({
				url: 'ajax_masters.php',
				type: 'post',
				data: {company_name:company_name, action:action},
				success: function(data){
					if(data == 1){
						$("#sucess-company-added").css('display', 'block');
							$("#sucess-company-added").fadeTo(2000, 500).slideUp(500, function(){ $("#sucess-company-added").slideUp(500); });
							
							setTimeout(function () {
									location.reload();
							},1500);
							
					}else if(data == 3){
						$("#error-company-exist").css('display', 'block');
							$("#error-company-exist").fadeTo(2000, 500).slideUp(500, function(){ $("#error-company-exist").slideUp(500); });
					}
				}
			})
		}
	});
	
	$('body').on('click', '.edit_company_btn',function(){
		company_id = $(this).data('company-id');
		company_name = $(this).data('company-name');
	});
	
	$('#edit_company').on('show.bs.modal', function(e) {
		$('#company_name_edit').val(company_name);
	});
	
	$( "#company_master_edit_fm" ).submit(function( e ) {
		e.preventDefault(); 
		var company_name_edit = $('#company_name_edit').val();
		var ref_company_id = company_id;
		///var action = $('#action').val();
		if(company_name_edit == ''){
			$('#company_name_edit').css('border-color','red');
		}else{
			$('#company_name_edit').css('border-color','');
			$.ajax({
				url: 'ajax_masters.php',
				type: 'post',
				data: {company_name_edit:company_name_edit, ref_company_id:ref_company_id, action:'update-company'},
				success: function(data){
					if(data == 2){
						$("#sucess-company-updated").css('display', 'block');
							$("#sucess-company-updated").fadeTo(2000, 500).slideUp(500, function(){ $("#sucess-company-updated").slideUp(500); });
							
							setTimeout(function () {
									location.reload();
							},1500);
							
					}else if(data == 3){
						$("#error-company-exist-onedit").css('display', 'block');
							$("#error-company-exist-onedit").fadeTo(2000, 500).slideUp(500, function(){ $("#error-company-exist-onedit").slideUp(500); });
					}
				}
			});
		}
	});
	
	$('body').on('click', '.delete_company',function(){
		company_id = $(this).data('company-id');
	});
	
	$('#delete_company').on('show.bs.modal', function(e) {
		$('#delet_company_ref_id').val(company_id);
	});
	
	
	$( "#delet_company_fm" ).submit(function( e ) {
		e.preventDefault(); 
		var delet_company_ref_id = $('#delet_company_ref_id').val();
		$.ajax({
			url: 'ajax_masters.php',
			type: 'post',
			data: {delet_company_ref_id:delet_company_ref_id, action:'delet-company'},
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