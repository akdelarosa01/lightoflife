$( function() {
	subjects();
	getQuizzes();

	checkAllCheckboxesInTable('.check_all_quizzes','.check_quiz');

	$('#btn_create').on('click', function() {
		$('#post_status').val('ADD');
		clear('.clear');
		$('#give_quiz_modal').modal('show');
	});

	$('#subject').on('change', function() {
		sections($(this).val());
		quizzes($(this).val());
	});

	$('#tbl_quizzes_body').on('click', '.btn_edit_quiz', function() {
		$('#post_status').val('EDIT');

		$('#subject').val($(this).attr('data-subject_id'));

		sections($(this).attr('data-subject_id'),$(this).attr('data-section_id'));
		quizzes($(this).attr('data-subject_id'),$(this).attr('data-id'));

		
		$('#start_date').val($(this).attr('data-start_date'));
		$('#start_time').val($(this).attr('data-start_time'));
		$('#due_date').val($(this).attr('data-due_date'));
		$('#due_time').val($(this).attr('data-due_time'));
		$('#timer').val($(this).attr('data-timer'));
		$('#max_attempt').val($(this).attr('data-max_attempt'));
		$('#instruction').val($(this).attr('data-instruction'));
		$('#late_submission').val($(this).attr('data-late_submission'));
		$('#give_quiz_modal').modal('show');

		$('#section').val($(this).attr('data-section_id'));
		$('#quiz_id').val($(this).attr('data-id'));
	});

	$('#frm_quiz').on('submit', function(e) {
		e.preventDefault();
		$('.loading').show();

		$.ajax({
			url: $(this).attr('action'),
			type: 'POST',
			dataType: 'JSON',
			data: $(this).serialize(),
		}).done(function(data, textStatus, xhr) {
			clear('.clear');
			$('#post_status').val('ADD');
			msg(data.msg,data.status);
			getQuizzes();
			$('#give_quiz_modal').modal('hide');
		}).fail(function(xhr, textStatus, errorThrown) {
			msg('Save Quiz: '+ errorThrown,'error');
		}).always(function() {
			$('.loading').hide();
		});
	});

	$('#btn_delete').on('click', function() {
		var ids = [];
		var msgs = 'Do you want to delete this Given Quiz?';

		$('#tbl_quizzes_body').find('.check_quiz:checked').each(function(index, el) {
			ids.push($(this).val());
		});

		if (ids.length > 1) {
			msgs = 'Do you want to delete these Given Quizzes?';
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
		        		url: '../../activities/delete-give-quizzes',
		        		type: 'POST',
		        		dataType: 'JSON',
		        		data: {ids: ids},
		        	}).done(function(data, textStatus, xhr) {
		        		clear('.clear');
		        		getQuizzes();
		        		msg(data.msg,data.status);
		        	}).fail(function(xhr, textStatus, errorThrown) {
		        		msg('Quizzes: '+ errorThrown,'error');
		        	});
		        	
		        }
		    }
		});
	});
});

async function subjects() {
	var option = '<option value=""></option>';
	$('#subject').html(option);
	await $.ajax({
		url: '../../activities/give-quiz-subjects',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		$.each(data, function(i, x) {
			option += '<option value="'+x.id+'">'+x.subject+'</option>';
		});
		$('#subject').html(option);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Subjects: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

async function sections(subject_id,section_id) {
	var option = '<option value=""></option>';
	$('#section').html(option);
	await $.ajax({
		url: '../../activities/give-quiz-sections',
		type: 'GET',
		dataType: 'JSON',
		data: {
			subject_id: subject_id
		}
	}).done(function(data, textStatus, xhr) {
		$.each(data, function(i, x) {
			if (parseInt(section_id) == parseInt(x.id)) {
				option += '<option value="'+x.id+'" selected>'+x.section+'</option>';
			} else {
				option += '<option value="'+x.id+'">'+x.section+'</option>';
			}
		});
		$('#section').html(option);

		if (option !== '') {
			$('#section').val(section_id);
		}
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Sections: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

async function quizzes(subject_id,quiz_id) {
	var option = '<option value=""></option>';
	$('#quiz_id').html(option);
	await $.ajax({
		url: '../../activities/give-quiz-quizzes',
		type: 'GET',
		dataType: 'JSON',
		data: {
			subject_id: subject_id
		}
	}).done(function(data, textStatus, xhr) {
		$.each(data, function(i, x) {
			if (parseInt(quiz_id) == parseInt(x.id)) {
				option += '<option value="'+x.id+'" selected>'+x.quiz_title+'</option>';
			} else {
				option += '<option value="'+x.id+'">'+x.quiz_title+'</option>';
			}
			
		});
		$('#quiz_id').html(option);

		if (quiz_id !== '') {
			$('#quiz_id').val(quiz_id);
		}
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Quizzes: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

async function getQuizzes() {
	$('.loading').show();

	await $.ajax({
		url: '../../activities/get-given-quiz',
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
	$('#tbl_quizzes').dataTable().fnClearTable();
    $('#tbl_quizzes').dataTable().fnDestroy();
    $('#tbl_quizzes').dataTable({
        data: arr,
        responsive: true,
        scrollX: true,
        columnDefs : [
        	{ targets: 0, sortable: false},
        	{ targets: 1, sortable: false},
        ],
        order: [[ 5, "desc" ]],
        columns: [
		    { data: function(data) {
		    	return '<input type="checkbox" class="check_quiz" value="'+data.quiz_id+'">';
		    }, searchable: false, orderable: false },
		    { data: function(data) {
		    	return '<button class="btn btn-sm btn-primary btn_edit_quiz" data-id="'+data.quiz_id+'"'+
		    				'data-section_id="'+data.section_id+'"'+
		    				'data-subject_id="'+data.subject_id+'"'+
		    				'data-quiz_title="'+data.quiz_title+'"'+
		    				'data-quiz_type="'+data.quiz_type+'"'+
		    				'data-start_date="'+data.start_date+'"'+
		    				'data-start_time="'+data.start_time+'"'+
		    				'data-due_date="'+data.due_date+'"'+
		    				'data-due_time="'+data.due_time+'"'+
		    				'data-timer="'+data.timer+'"'+
		    				'data-max_attempt="'+data.max_attempt+'"'+
		    				'data-instruction="'+data.instruction+'"'+
		    				'data-late_submission="'+data.late_submission+'"'+
		    				'>'+
		    				'<i class="fa fa-edit"></i>'+
		    			'</button>';
		    }, searchable: false, orderable: false },
		    { data: 'subject' },
		    { data: 'quiz_title' },
		    { data: 'quiz_type' },
		    { data: 'created_at' }
		]
    });
}