subs_arr = [];
$( function() {
	getTeachers();

	$('#tbl_teachers_body').on('click', '.btn_edit', function() {
		$('#fullname_label').html($(this).attr('data-fullname'));
		$('#user_id').val($(this).attr('data-id'));
		$('#id_number').val($(this).attr('data-id_number'));
		$('#dept_id').val($(this).attr('data-dept_id'));
		$('#fullname').val($(this).attr('data-fullname'));

		getHandles($(this).attr('data-id'),$(this).attr('data-dept_id'));

		getSubjects($(this).attr('data-dept_id'));
		SectionTable([]);

		$('#subject_handle_modal').modal('show');
	});

	$('#subject_handle_modal').on('shown.bs.modal', function(e){
		$($.fn.dataTable.tables(true)).DataTable()
		.columns.adjust();
	});

	$('#subject').on('change', function() {
		$('#subj_id').val($(this).val());
		$('#program_id').val($(this).find('option:selected').attr('data-program_id'));
		$('#program').val($(this).find('option:selected').attr('data-program'));
		
		getSections([{
			id: $(this).val(),
			dept_id: $(this).find('option:selected').attr('data-dept_id'),
			program_id: $(this).find('option:selected').attr('data-program_id'),
		}]);
	});

	$('#frm_subhandle').on('submit', function(e) {
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
			getTeachers();
			$('#subject_handle_modal').modal('hide');
		}).fail(function(xhr, textStatus, errorThrown) {
			msg('Save handles: '+ errorThrown,'error');
		}).always(function() {
			$('.loading').hide();
		});
	});
});

function getTeachers() {
	$('.loading').show();

	$.ajax({
		url: '../../transaction/get-subject-handle-teachers',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		TeachersTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Teachers: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

function TeachersTable(arr) {
	$('#tbl_teachers').dataTable().fnClearTable();
    $('#tbl_teachers').dataTable().fnDestroy();
    $('#tbl_teachers').dataTable({
        data: arr,
        responsive: true,
        scrollX: true,
        columnDefs : [
        	{ targets: 0, sortable: false},
        ],
        order: [[ 1, "asc" ]],
        columns: [
        	{ data: function(data) {
        		return '<button class="btn btn-sm btn-primary btn_edit" data-id="'+data.id+'" '+
        					'data-id_number="'+data.id_number+'"'+
        					'data-fullname="'+data.fullname+'"'+
        					'data-department="'+data.department+'"'+
        					'data-dept_id="'+data.dept_id+'">'+
        					'<i class="fa fa-edit"></i>'+
        				'</button>';

        	}, searchable: false, orderable: false },
		    { data: 'id_number' },
		    { data: 'fullname' },
		    { data: 'department' },
		]
    });
}

function getSubjects(dept_id) {
	var option = '<option value=""></option>';
	$('#subject').html(option);
	$.ajax({
		url: '../../transaction/get-subject-handle-subjects',
		type: 'GET',
		dataType: 'JSON',
		data: {
			dept_id: dept_id
		}
	}).done(function(data, textStatus, xhr) {
		$.each(data.subjects, function(i, x) {
			option += '<option value="'+x.id+'" '+
						'data-dept_id="'+x.dept_id+'" '+
        				'data-program_id="'+x.program_id+'"'+
        				'data-program="'+x.program+'"'+
						'>'+x.code+' - '+x.description+'</option>';
		});

		$('#subject').html(option);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Subjects: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

function getSections(sub_data) {
	$.ajax({
		url: '../../transaction/get-subject-handle-sections',
		type: 'GET',
		dataType: 'JSON',
		data: {
			sub_data: sub_data
		}
	}).done(function(data, textStatus, xhr) {
		SectionTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Sections: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

function SectionTable(arr) {
	$('#tbl_sections').dataTable().fnClearTable();
    $('#tbl_sections').dataTable().fnDestroy();
    $('#tbl_sections').dataTable({
        data: arr,
        responsive: true,
        sorting: false,
        searching: false,
        lengthChange: false,
        scrollX: true,
        columnDefs : [
        	{ targets: 0, sortable: false},
        ],
        order: [[ 1, "asc" ]],
        columns: [
        	{ data: function(data) {
        		return '<input type="checkbox" class="check_section" name="section[]" value="'+data.id+'">';
        	}, searchable: false, orderable: false },
		    { data: 'program' },
		    { data: 'section' },
		]
    });
}

function getHandles(teacher_id,dept_id) {
	$('.loading').show();
	$.ajax({
		url: '../../transaction/get-subject-handle',
		type: 'GET',
		dataType: 'JSON',
		data: {
			id: parseInt(teacher_id),
			dept_id: parseInt(dept_id)
		},
	}).done(function(data, textStatus, xhr) {
		HandlesTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Subject Handle: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

function HandlesTable(arr) {
	$('#tbl_handles').dataTable().fnClearTable();
    $('#tbl_handles').dataTable().fnDestroy();
    $('#tbl_handles').dataTable({
        data: arr,
        responsive: true,
        sorting: false,
        searching: false,
        lengthChange: false,
        scrollX: true,
        order: [[ 0, "asc" ]],
        columns: [
		    { data: 'program' },
		    { data: 'section' },
		    { data: 'subject' }
		]
    });
}