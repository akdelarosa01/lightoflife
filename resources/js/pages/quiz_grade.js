$( function() {
	getQuizzes();

	$('#tbl_quiz_body').on('click', '.btn_view_grade', function() {
		$('.loading').show();
		var items = '';

		$('#quiz_id').val($(this).attr('data-quiz_id'))
		$('#quiz_title').val($(this).attr('data-quiz_title'))
		$('#quiz_type').val($(this).attr('data-quiz_type'))
		$('#subject').val($(this).attr('data-subject'))
		$('#section').val($(this).attr('data-section'))
		$('#section_id').val($(this).attr('data-section_id'))
		$('#subject_id').val($(this).attr('data-subject_id'))


		$.ajax({
			url: '../../activities/get-quiz-grade-details',
			type: 'GET',
			dataType: 'JSON',
			data: {
				quiz_id: $(this).attr('data-quiz_id'),
				quiz_title: $(this).attr('data-quiz_title'),
				quiz_type: $(this).attr('data-quiz_type'),
				subject: $(this).attr('data-subject'),
				section: $(this).attr('data-section'),
				section_id: $(this).attr('data-section_id'),
				subject_id: $(this).attr('data-subject_id')
			}
		}).done(function(data, textStatus, xhr) {
			var qgr = data.qg_results;
			var qgi = data.qg_item;
			$('#td_teacher').html(qgr.teacher);
			$('#td_subject').html(qgr.subject);
			$('#td_section').html(qgr.section);
			$('#td_quiz_title').html(qgr.quiz_title);
			$('#td_quiz_type').html(qgr.quiz_type);
			$('#td_start_date').html(qgr.start_date);
			$('#td_due_date').html(qgr.due_date);
			$('#td_max_attempt').html(qgr.max_attempt);
			$('#td_timer').html(qgr.timer);
			$('#td_late_submission').html(qgr.late_submission);
			$('#td_instruction').html(qgr.instruction);

			if (qgi.length < 1) {
				$('#tbl_details_body').html('<tr><td colspan="5">No student have taken this quiz</td></tr>');
			} else {
				$.each(qgi, function(i, x) {
					items += '<tr>'+
		                        '<td>'+x.student+'</td>'+
		                        '<td>'+x.attempt_no+'</td>'+
		                        '<td>'+x.total_points+ '/'+x.max_score+'</td>'+
		                        '<td>'+x.grade_percent+'</td>'+
		                        '<td>'+x.remarks+'</td>'+
		                    '</tr>';
				});
				$('#tbl_details_body').html(items);
			}

			$('#quiz_grade_modal').modal('show');
			
		}).fail(function(xhr, textStatus, errorThrown) {
			msg('Quiz Details: '+ errorThrown,'error');
		}).always(function() {
			$('.loading').hide();
		});
	});

	$('#btn_print').on('click', function() {
		window.location.href = '../../activities/quiz-grade-print?quiz_id='+$('#quiz_id').val()+
								'&&quiz_title='+$('#quiz_title').val()+
								'&&quiz_type='+$('#quiz_type').val()+
								'&&subject='+$('#subject').val()+
								'&&section='+$('#section').val()+
								'&&section_id='+$('#section_id').val()+
								'&&subject_id='+$('#subject_id').val();
	});
});

function getQuizzes() {
	$('.loading').show();

	$.ajax({
		url: '../../activities/get-given-quiz-grade',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		QuizzesTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Quizzes: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

function QuizzesTable(arr) {
	$('#tbl_quiz').dataTable().fnClearTable();
    $('#tbl_quiz').dataTable().fnDestroy();
    $('#tbl_quiz').dataTable({
        data: arr,
        responsive: true,
        scrollX: true,
        columnDefs : [
        	{ targets: 0, sortable: false},
        ],
        order: [[ 1, "desc" ]],
        columns: [
		    { data: function(data) {
		    	return '<button class="btn btn-sm btn-primary btn_view_grade" data-quiz_id="'+data.quiz_id+'"'+
		    				'data-quiz_title="'+data.quiz_title+'"'+
		    				'data-quiz_type="'+data.quiz_type+'"'+
		    				'data-subject="'+data.subject+'"'+
		    				'data-section="'+data.section+'"'+
		    				'data-section_id="'+data.section_id+'"'+
		    				'data-subject_id="'+data.subject_id+'"'+
		    				'>'+
		    				'<i class="fa fa-edit"></i>'+
		    			'</button>';
		    }, searchable: false, orderable: false },
		    { data: 'subject' },
		    { data: 'section' },
		    { data: 'quiz_title' },
		    { data: 'quiz_type' }
		]
    });
}