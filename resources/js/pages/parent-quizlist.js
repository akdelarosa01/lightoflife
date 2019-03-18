$( function() {
	getPending();
	getFinished();

	$('#tbl_pending_body').on('click', '.btn_check_quiz', function() {
		getQuizDetails($(this).attr('data-quiz_id'));
	});

	$('#tbl_finished_body').on('click', '.btn_view_results', function() {
		$.ajax({
			url: '../../parent-activities/view-quiz-results',
			type: 'GET',
			dataType: 'JSON',
			data: {
				quiz_id: $(this).attr('data-quiz_id'),
				qg_id: $(this).attr('data-qg_id'),
				subject_id: $(this).attr('data-subject_id'),
				section_id: $(this).attr('data-section_id'),
				subject: $(this).attr('data-subject'),
				quiz_title: $(this).attr('data-quiz_title'),
				status: $(this).attr('data-status'),
				student_id: $(this).attr('data-student_id'),
				teacher_id: $(this).attr('data-teacher_id'),
				date_submitted: $(this).attr('data-date_submitted')
			}
		}).done(function(data, textStatus, xhr) {
			var quiz_given = data.quiz_given;
			var qg_results = data.qg_results;
			var qg_item = data.qg_item;

			$('#td_subject').html(data.subject);
			$('#td_date_taken').html(qg_results.date_taken+' '+qg_results.time_taken);
			$('#td_quiz_type').html(qg_results.quiz_type);
			$('#td_date_submitted').html(qg_results.date_submitted+' '+qg_results.time_submitted);
			$('#td_max_score').html(qg_results.max_score);
			$('#td_student_score').html(qg_results.total_points+' out of '+qg_results.max_score);
			$('#td_start_date').html(quiz_given.start_date+' '+quiz_given.start_time);
			$('#td_final_grade').html(qg_results.grade_percent +'%');
			$('#td_due_date').html(quiz_given.due_date+' '+quiz_given.due_time);
			$('#td_remarks').html(qg_results.remarks);
			$('#td_timer').html(quiz_given.timer+' minutes');
			$('#td_max_attempt').html(quiz_given.max_attempt);
			$('#td_user_attempt').html(quiz_given.user_attempt);
			$('#td_late_submission').html(quiz_given.late_submission);
			$('#td_status').html(quiz_given.status);
			$('#td_instruction').html(quiz_given.instruction);

			var items = '';

			$.each(qg_item, function(i, x) {
				var color = '<i class="fa fa-check" style="color: #0b8e0d"></i>';
				if (x.student_answer == x.correct_answer) {
					color = '<i class="fa fa-check" style="color: #0b8e0d"></i>';
				} else {
					color = '<i class="fa fa-times" style="color: #bd0707"></i>';
				}

				items += '<tr>'+
                            '<td colspan="2">'+x.question_num+'. '+x.question+'</td>'+
                            '<td>Your Answer is: '+x.student_answer+'</td>'+
                            '<td>Score: '+x.score+' out of '+x.max_score+'</td>'+
                        '</tr>';
			});

			$('#question_body').html(items);

			$('#results_modal').modal('show');
		}).fail(function(xhr, textStatus, errorThrown) {
			msg('Pending: '+ errorThrown,'error');
		}).always(function() {
			$('.loading').hide();
		});
	});

});

function getQuizDetails(id) {
	$('.loading').show();

	$.ajax({
		url: '../../parent-activities/get-quiz-details',
		type: 'GET',
		dataType: 'JSON',
		data: {
			quiz_id: id
		}
	}).done(function(data, textStatus, xhr) {
		var details = data.details;
		$('#quiz_id').val(id);
		$('#quiz_title').html(details.quiz_title);
		$('#quiz_type').html(details.quiz_type);
		$('#max_score').html(details.max_score);
		$('#start').html(details.start_date+' '+details.start_time);
		$('#due').html(details.due_date+' '+details.due_time);
		$('#time_timit').html(details.timer);
		$('#allowed_attempts').html(details.max_attempt);
		$('#committed_attempts').html(details.user_attempt);
		$('#late_submission').html(details.late_submission);
		$('#instruction').html(details.instruction);

		if (data.take_quiz) {
			$('#take_quiz').prop('disabled', false);
		} else {
			$('#take_quiz').prop('disabled', true);
		}

		$('#quiz_modal').modal('show');
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Get Quiz Details: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

async function getPending() {
	$('.loading').show();

	await $.ajax({
		url: '../../parent-activities/get-pending-quizzes',
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
		    	return '<button class="btn btn-sm btn-primary btn_check_quiz" data-quiz_id="'+data.quiz_id+'">'+
		    				'<i class="fa fa-edit"></i>'+
		    			'</button>';
		    }, searchable: false, orderable: false },
		    { data: 'subject' },
		    { data: 'quiz_title' },
		    { data: 'status' },
		    { data: 'date_given' }
		]
    });
}

async function getFinished() {
	$('.loading').show();

	await $.ajax({
		url: '../../parent-activities/get-finished-quizzes',
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
		    	return '<button class="btn btn-sm btn-primary btn_view_results" data-quiz_id="'+data.quiz_id+'"'+
		    				'data-qg_id="'+data.id+'"'+
		    				'data-subject_id="'+data.subject_id+'"'+
		    				'data-section_id="'+data.section_id+'"'+
		    				'data-subject="'+data.subject+'"'+
		    				'data-quiz_title="'+data.quiz_title+'"'+
		    				'data-status="'+data.status+'"'+
		    				'data-student_id="'+data.student_id+'"'+
		    				'data-teacher_id="'+data.teacher_id+'"'+
		    				'data-date_submitted="'+data.date_submitted+'"'+
		    			'>'+
		    				'<i class="fa fa-edit"></i>'+
		    			'</button>';
		    }, searchable: false, orderable: false },
		    { data: 'subject' },
		    { data: 'quiz_title' },
		    { data: 'status' },
		    { data: 'date_submitted' }
		]
    });
}