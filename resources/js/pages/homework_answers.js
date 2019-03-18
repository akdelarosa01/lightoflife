$( function() {
	StudentAnswers();

	$('#tbl_homeworks_body').on('click', '.btn_view_answer', function() {
		$('.loading').show();
		var td_subject = $(this).attr('data-subject');
		var td_title = $(this).attr('data-title');
		var td_question = $(this).attr('data-question');
		var td_answer = $(this).attr('data-answer');

		$.ajax({
			url: '../../activities/get-files',
			type: 'GET',
			dataType: 'JSON',
			data: {
				hw_id: $(this).attr('data-hw_id'),
				student_id: $(this).attr('data-student_id')
			}
		}).done(function(data, textStatus, xhr) {
			var htmlfiles = '';

			$.each(data.attachments, function(i, x) {
				htmlfiles += '<a href="../'+x.fullpath+'" class="btn btn-sm btn-secondary" target="_blank">'+x.filename+'</a>';
			});

			$('#attachments').html(htmlfiles);

			var st_att = '';

			$.each(data.student_att, function(i, x) {
				st_att += '<a href="../'+x.fullpath+'" class="btn btn-sm btn-secondary" target="_blank">'+x.filename+'</a>';
			});

			$('#student_attachments').html(st_att);

			$('#td_subject').html(td_subject);
			$('#td_title').html(td_title);
			$('#td_question').html(td_question);
			$('#td_answer').html(td_answer);

			$('#homework_modal').modal('show');
		}).fail(function(xhr, textStatus, errorThrown) {
			msg('Answers: '+ errorThrown,'error');
		}).always(function() {
			$('.loading').hide();
		});
		
	});
});

function StudentAnswers() {
	$('.loading').show();

	$.ajax({
		url: '../../activities/get-answer',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		AnswerTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Answers: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

function AnswerTable(arr) {
	$('#tbl_homeworks').dataTable().fnClearTable();
    $('#tbl_homeworks').dataTable().fnDestroy();
    $('#tbl_homeworks').dataTable({
        data: arr,
        responsive: true,
        scrollX: true,
        order: [[ 4, "desc" ]],
        columns: [
		    { data: function(data) {
		    	return '<button class="btn btn-sm btn-primary btn_view_answer"'+
		    					'data-hw_id="'+data.hw_id+'"'+
		    					'data-question="'+data.question+'"'+
		    					'data-student_id="'+data.student_id+'"'+
		    					'data-title="'+data.title+'"'+
		    					'data-section="'+data.section+'"'+
		    					'data-subject="'+data.subject+'"'+
		    					'data-student_name="'+data.student_name+'"'+
		    					'data-answer="'+data.answer+'"'+
		    					'data-date_submitted="'+data.date_submitted+'"'+
		    				'>'+
		    				'<i class="fa fa-edit"></i>'+
		    			'</button>';
		    }, searchable: false, orderable: false },
		    { data: 'subject' },
		    { data: 'section' },
		    { data: 'title' },
		    { data: 'student_name' },
		    { data: 'date_submitted' }
		]
    });
}