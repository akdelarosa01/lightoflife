$( function() {
	announcement();
	getSubjectHandled();

	$('#tbl_handled_body').on('click', '.btn_handles', function() {
		getClass($(this).attr('data-section_id'),$(this).attr('data-subject_id'));

		$('#class_modalLabel').html($(this).attr('data-subject')+' | '+$(this).attr('data-section'));

		$('#class_modal').modal('show');
	});

	$('#class_modal').on('shown.bs.modal', function(e){
		$($.fn.dataTable.tables(true)).DataTable()
		.columns.adjust();
	});
});

function announcement() {
	$.ajax({
		url: '../../home-announcement',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		if (data.title != undefined) {
			$('#title').html('<i class="fa fa-exclamation-triangle"></i> '+data.title);
			$('#announcement').html(data.announcement);
		} else {
			$('#title').html('<i class="fa fa-exclamation-triangle"></i> Announcement');
			$('#announcement').html('');
		}
		
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Announcement: '+ errorThrown,'error');
	});
}

function getSubjectHandled() {
	$.ajax({
		url: '../../home-handled-subjects',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		HandledTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Subjects Handled: '+ errorThrown,'error');
	});
}

function HandledTable(arr) {
	$('#tbl_handled').dataTable().fnClearTable();
    $('#tbl_handled').dataTable().fnDestroy();
    $('#tbl_handled').dataTable({
        data: arr,
        responsive: true,
        sorting: false,
        searching: false,
        lengthChange: false,
        scrollX: true,
        language: {
        	emptyTable: "No Subjects were assigned to you.",
        },
        columnDefs : [
        	{ targets: 0, sortable: false},
        ],
        order: [[ 1, "asc" ]],
        columns: [
        	{ data: function(data) {
        		return '<button class="btn btn-sm btn-primary btn_handles" '+
        					'data-section_id="'+data.section_id+'"'+
        					'data-subject_id="'+data.subject_id+'"'+
        					'data-subject="'+data.subject+'"'+
        					'data-section="'+data.section+'">'+
        					'<i class="fa fa-eye"></i>'+
        				'</button>';
        	}, searchable: false, orderable: false },
		    { data: 'subject' },
		    { data: 'section' }
		]
    });
}

function getClass(section_id,subject_id) {
	$.ajax({
		url: '../../home-handled-class',
		type: 'GET',
		dataType: 'JSON',
		data: {
			section_id: section_id,
			subject_id: subject_id
		}
	}).done(function(data, textStatus, xhr) {
		classTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Class Handled: '+ errorThrown,'error');
	});
}

function classTable(arr) {
	$('#tbl_class').dataTable().fnClearTable();
    $('#tbl_class').dataTable().fnDestroy();
    $('#tbl_class').dataTable({
        data: arr,
        responsive: true,
        sorting: false,
        searching: false,
        lengthChange: false,
        scrollX: true,
        language: {
        	emptyTable: "No Students were enrolled in this class.",
        },
        order: [[ 1, "asc" ]],
        columns: [
		    { data: 'student_id' },
		    { data: 'student_name' }
		]
    });
}