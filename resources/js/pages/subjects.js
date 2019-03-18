$( function() {
	checkAllCheckboxesInTable('.check_all','.check_item');
	programDropdown();
	departmentDropdown();
	getSubjects();

	$('#frm_subject').on('submit', function(e) {
		e.preventDefault();
		$('.loading').show();

		$.ajax({
			url: $(this).attr('action'),
			type: 'POST',
			dataType: 'JSON',
			data: $(this).serialize(),
		}).done(function(data, textStatus, xhr) {
			clear('.clear');
			msg(data.msg,data.status);
			getSubjects();
		}).fail(function(xhr, textStatus, errorThrown) {
			msg('Subject: '+ errorThrown,'error');
		}).always(function() {
			$('.loading').hide();
		});
		
	});

	$('#tbl_subjects_body').on('click', '.btn_edit_subject', function() {
		$('#id').val($(this).attr('data-id'));
		$('#code').val($(this).attr('data-code'));
		$('#description').val($(this).attr('data-description'));
		$('#program').val($(this).attr('data-program_id'));
		$('#department').val($(this).attr('data-dept_id'));
	});

	$('#btn_delete').on('click', function() {
		var ids = [];
		var msgs = 'Do you want to delete this Subject?';

		$('#tbl_subjects_body').find('.check_item:checked').each(function(index, el) {
			ids.push($(this).val());
		});

		if (ids.length > 1) {
			msgs = 'Do you want to delete these Subjects?';
		}

		bootbox.confirm({
		    message: msgs,
		    buttons: {
		        confirm: {
		            label: 'Yes',
		            className: 'btn-danger'
		        },
		        cancel: {
		            label: 'Cancel',
		            className: 'btn-secondary'
		        }
		    },
		    callback: function (result) {
		        if (result) {
		        	$.ajax({
		        		url: '../../maintenance/delete-subjects',
		        		type: 'POST',
		        		dataType: 'JSON',
		        		data: {ids: ids},
		        	}).done(function(data, textStatus, xhr) {
		        		clear('.clear');
		        		getSubjects();
		        		msg(data.msg,data.status);
		        	}).fail(function(xhr, textStatus, errorThrown) {
		        		msg('Subject: '+ errorThrown,'error');
		        	});
		        	
		        }
		    }
		});
	});
	
});

function getSubjects() {
	$.ajax({
		url: '../../maintenance/get-subjects',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		SubjectsTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Subjects: '+ errorThrown,'error');
	});
}

function SubjectsTable(arr) {
	$('#tbl_subjects').dataTable().fnClearTable();
    $('#tbl_subjects').dataTable().fnDestroy();
    $('#tbl_subjects').dataTable({
        data: arr,
        responsive: true,
        scrollX: true,
        columnDefs : [
        	{ targets: 0, sortable: false},
        	{ targets: 1, sortable: false},
        ],
        order: [[ 6, "desc" ]],
        columns: [
		    { data: function(data) {
		    	return '<input type="checkbox" class="check_item" value="'+data.id+'">';
		    } },
		    { data: function(data) {
		    	return "<button class='btn btn-sm btn-primary btn_edit_subject'"+
		    				"data-id='"+data.id+"' "+
		    				"data-code='"+data.code+"' "+
		    				"data-description='"+data.description+"' "+
		    				"data-department='"+data.department+"' "+
		    				"data-dept_id='"+data.dept_id+"' "+
		    				"data-program='"+data.program+"' "+
		    				"data-program_id='"+data.program_id+"' >"+
		    				"<i class='fa fa-edit'></i>"+
		    			"</button>";
		    } },
		    { data: 'code' },
		    { data: 'description' },
		    { data: 'program' },
		    { data: 'department' },
		    { data: 'created_at' }
		]
    });

}