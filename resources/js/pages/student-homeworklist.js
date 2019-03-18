$( function() {
	getPending();
	getFinished();

	$('#tbl_pending_body').on('click', '.btn_answer_hw', function() {
		$('#hw_id').val($(this).attr('data-hw_id'));
		$('#id').val($(this).attr('data-id'));

		getHomeworkDetails($(this).attr('data-hw_id'));
		$('#answer').prop('readonly', false);
		$('.hide').show();

		$('#homework_modal').modal('show');
	});

	$('#tbl_finished_body').on('click', '.btn_view_answer', function() {

		$('#hw_id').val($(this).attr('data-hw_id'));
		$('#id').val($(this).attr('data-id'));

		getHomeworkDetails($(this).attr('data-hw_id'));
		getHomeworkAnswer($(this).attr('data-hw_id'));
		$('#answer').prop('readonly', true);
		$('.hide').hide();

		$('#homework_modal').modal('show');
	});

	$('#homework_file').bind('change', function() {
		var files = $(this)[0].files;
		var htmlfiles = '';
		var label = '';

		$.each(files, function(i, x) {
			htmlfiles += '<button class="btn btn-sm btn-secondary" disabled>'+x.name+' | size: '+bytesToSize(x.size)+'</button>';
			label += x.name+'; ';
		});

		$('#student_attachments').html(htmlfiles);
		$('#file_label').html(label);
	});

	$('#frm_homework').on('submit', function(e) {
        $('.loading').show();
   		e.preventDefault();
   		var data = new FormData(this);

   		$.ajax({
			url: $(this).attr('action'),
			type: 'POST',
			dataType: 'JSON',
			data: data,
			mimeType:"multipart/form-data",
			contentType: false,
			cache: false,
			processData:false,
		}).done(function(data, textStatus, xhr) {
			// var return_data = jQuery.parseJSON(data);
            $('.loading').hide();
            clear('.clear');
            msg(data.msg,data.status);
            getPending();
            getFinished();
			$('#file_label').html('');
			$('#attachments').html('');
			$('#student_attachments').html('');
			$('#homework_modal').modal('hide');
		}).fail(function(xhr, textStatus, errorThrown) {
			msg('Save Homework: '+ errorThrown,'error');
		}).always(function() {
			$('.loading').hide();
		});
   	});

});

async function getHomeworkDetails(hw_id) {
	$('.loading').show();

	await $.ajax({
		url: '../../student-activities/get-details-homeworks',
		type: 'GET',
		dataType: 'JSON',
		data: {
			hw_id: hw_id
		}
	}).done(function(data, textStatus, xhr) {
		var details = data.details;
		$('#subject_id').val(details.subject_id);
		$('#subject').val(details.subject);
		$('#title').val(details.title);
		$('#question').val(details.question);

		var htmlfiles = '';

		$.each(data.attachments, function(i, x) {
			htmlfiles += '<a href="../'+x.fullpath+'" class="btn btn-sm btn-secondary" target="_blank">'+x.filename+'</a>';
		});

		$('#attachments').html(htmlfiles);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Pending: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

async function getHomeworkAnswer(hw_id) {
	$('.loading').show();

	await $.ajax({
		url: '../../student-activities/get-answer-homeworks',
		type: 'GET',
		dataType: 'JSON',
		data: {
			hw_id: hw_id
		}
	}).done(function(data, textStatus, xhr) {
		var answers = data.answers;
		$('#answer').val(answers.answer);

		var htmlfiles = '';

		$.each(data.attachments, function(i, x) {
			htmlfiles += '<a href="../'+x.fullpath+'" class="btn btn-sm btn-secondary" target="_blank">'+x.filename+'</a>';
		});

		$('#student_attachments').html(htmlfiles);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Pending: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

async function getPending() {
	$('.loading').show();

	await $.ajax({
		url: '../../student-activities/get-pending-homeworks',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		PendingTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Pending: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

function PendingTable(arr) {
	$('#tbl_pending').dataTable().fnClearTable();
    $('#tbl_pending').dataTable().fnDestroy();
    $('#tbl_pending').dataTable({
        data: arr,
        responsive: true,
        scrollX: true,
        columnDefs : [
        	{ targets: 0, sortable: false},
        ],
        order: [[ 4, "desc" ]],
        columns: [
		    { data: function(data) {
		    	return '<button class="btn btn-sm btn-primary btn_answer_hw" data-id="'+data.id+'" '+
		    				'data-hw_id="'+data.hw_id+'"'+
		    				'>'+
		    				'<i class="fa fa-edit"></i>'+
		    			'</button>';
		    }, searchable: false, orderable: false },
		    { data: 'subject' },
		    { data: 'title' },
		    { data: 'status' },
		    { data: 'date_given' }
		]
    });
}

async function getFinished() {
	$('.loading').show();

	await $.ajax({
		url: '../../student-activities/get-finished-homeworks',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		FinishedTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Finished: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

function FinishedTable(arr) {
	$('#tbl_finished').dataTable().fnClearTable();
    $('#tbl_finished').dataTable().fnDestroy();
    $('#tbl_finished').dataTable({
        data: arr,
        responsive: true,
        scrollX: true,
        columnDefs : [
        	{ targets: 0, sortable: false},
        ],
        order: [[ 4, "desc" ]],
        columns: [
		    { data: function(data) {
		    	return '<button class="btn btn-sm btn-primary btn_view_answer" data-id="'+data.id+'" '+
		    				'data-hw_id="'+data.hw_id+'"'+
		    				'>'+
		    				'<i class="fa fa-edit"></i>'+
		    			'</button>';
		    }, searchable: false, orderable: false },
		    { data: 'subject' },
		    { data: 'title' },
		    { data: 'status' },
		    { data: 'date_submitted' }
		]
    });
}