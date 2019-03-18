$( function() {
	announcement();
	getEnrolledSubjects();

	$('#tbl_enrolled_body').on('click', '.btn_handouts', function() {
		getHandouts($(this).attr('data-section_id'),$(this).attr('data-subject_id'),$(this).attr('data-program_id'));

		$('#handouts_modal').modal('show');
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

function getEnrolledSubjects() {
	$.ajax({
		url: '../../home-enrolled-subjects',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		EnrolledTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Enrolled Subjects: '+ errorThrown,'error');
	});
}

function EnrolledTable(arr) {
	$('#tbl_enrolled').dataTable().fnClearTable();
    $('#tbl_enrolled').dataTable().fnDestroy();
    $('#tbl_enrolled').dataTable({
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
        		return '<button class="btn btn-sm btn-primary btn_handouts" '+
        					'data-section_id="'+data.section_id+'"'+
        					'data-program_id="'+data.program_id+'"'+
        					'data-subject_id="'+data.subject_id+'">'+
        					'<i class="fa fa-eye"></i>'+
        				'</button>';
        	}, searchable: false, orderable: false },
		    { data: 'subject' },
		    { data: 'teacher_name' }
		]
    });
}

async function getHandouts(section_id,subject_id,program_id) {
	$('.loading').show();

	await $.ajax({
				url: '../../home-student-handouts',
				type: 'GET',
				dataType: 'JSON',
				data: {
					section_id: section_id,
					subject_id: subject_id,
					program_id: program_id
				}
			}).done(function(data, textStatus, xhr) {
				HandoutsTable(data);
			}).fail(function(xhr, textStatus, errorThrown) {
				msg('Handouts: '+ errorThrown,'error');
			}).always(function() {
				$('.loading').hide();
			});
}

function HandoutsTable(arr) {
	$('#tbl_handouts').dataTable().fnClearTable();
    $('#tbl_handouts').dataTable().fnDestroy();
    $('#tbl_handouts').dataTable({
        data: arr,
        responsive: true,
        sorting: false,
        searching: false,
        lengthChange: false,
        scrollX: true,
        columnDefs : [
        	{ targets: 0, sortable: false},
        ],
        order: [[ 3, "desc" ]],
        columns: [
        	{ data: function(data) {
		    	return '<a href="/'+data.file_path+'" class="btn btn-sm btn-primary" target="_blank">'+
        					'<i class="fa fa-eye"></i>'+
        				'</a>'+
        				'<a href="/'+data.file_path+'" download class="btn btn-sm btn-info">'+
        					'<i class="fa fa-download"></i>'+
        				'</a>';
		    },searchable: false, orderable: false },
		    { data: 'title' },
		    { data: 'description' },
		    { data: 'date_uploaded' },
		]
    });
}