$( function() {
	getAssigned();
	SectionTable([]);
	SubjectTable([]);

	checkAllCheckboxesInTable('.check_all_sections','.check_section');
	checkAllCheckboxesInTable('.check_all_subjects','.check_subject');
	checkAllCheckboxesInTable('.check_all_sec','.check_sec');

	programDropdown();

	$('#btn_assign').on('click', function() {
		$('#assign_subject_modal').modal('show');
	});

	$('#assign_subject_modal').on('shown.bs.modal', function(e){
		$($.fn.dataTable.tables(true)).DataTable()
		.columns.adjust();
	});

	$('#program').on('change', function() {
		getSubjectSection($(this).val())
	});

	$('#frm_subtosec').on('submit', function(e) {
		e.preventDefault();
		$('.loading').show();

		$.ajax({
			url: $(this).attr('action'),
			type: 'POST',
			dataType: 'JSON',
			data: $(this).serialize()
		}).done(function(data, textStatus, xhr) {
			msg(data.msg,data.status);
			clear('.clear');
			SectionTable([]);
			SubjectTable([]);
			getAssigned();
		}).fail(function(xhr, textStatus, errorThrown) {
			msg('Save Assigned subjects: '+ errorThrown,'error');
		}).always(function() {
			$('.loading').hide();
		});
	});

	$('#btn_delete').on('click', function() {
		var ids = [];
		var msgs = 'Do you want to delete all subject to this section?';

		$('#tbl_assigned_body').find('.check_sec:checked').each(function(index, el) {
			ids.push($(this).val());
		});

		if (ids.length > 1) {
			msgs = 'Do you want to delete all subject to this sections?';
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
		        		url: '../../transaction/delete-assign-subjects',
		        		type: 'POST',
		        		dataType: 'JSON',
		        		data: {ids: ids},
		        	}).done(function(data, textStatus, xhr) {
		        		clear('.clear');
		        		getAssigned();
		        		msg(data.msg,data.status);
		        	}).fail(function(xhr, textStatus, errorThrown) {
		        		msg('Delete Assigned Subjects: '+ errorThrown,'error');
		        	});
		        	
		        }
		    }
		});
	});
});

function getAssigned() {
	$('.loading').show();

	$.ajax({
		url: '../../transaction/get-assigned-subject',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		AssignedTables(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Assigned subjects: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

function AssignedTables(arr) {
	$('#tbl_assigned').dataTable().fnClearTable();
    $('#tbl_assigned').dataTable().fnDestroy();
    $('#tbl_assigned').dataTable({
        data: arr,
        responsive: true,
        scrollX: true,
        columnDefs : [
        	{ targets: 0, sortable: false},
        ],
        order: [[ 3, "desc" ]],
        columns: [
        	{ data: function(data) {
        		return '<input type="checkbox" class="check_sec" value="'+data.id+'">';
        	}, searchable: false, orderable: false },
		    { data: 'program' },
		    { data: 'section' },
		    { data: 'created_at' }
		]
    });
}

function getSubjectSection(program_id) {
	$('.loading').show();

	$.ajax({
		url: '../../transaction/get-subject-and-section',
		type: 'GET',
		dataType: 'JSON',
		data: {
			program_id: program_id
		}
	}).done(function(data, textStatus, xhr) {
		SectionTable(data.sections);
		SubjectTable(data.subjects);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Subjects and Sections: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

function SectionTable(arr) {
	$('#tbl_section').dataTable().fnClearTable();
    $('#tbl_section').dataTable().fnDestroy();
    $('#tbl_section').dataTable({
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

function SubjectTable(arr) {
	$('#tbl_subject').dataTable().fnClearTable();
    $('#tbl_subject').dataTable().fnDestroy();
    $('#tbl_subject').dataTable({
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
        		return '<input type="checkbox" class="check_subject" name="subject[]" value="'+data.id+'">';
        	}, searchable: false, orderable: false },
		    { data: 'code' },
		    { data: 'description' },
		]
    });
}