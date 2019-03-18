$( function() {
	getGivenHomeworks();
	checkAllCheckboxesInTable('.check_all_homeworks','.check_homework');
	subjects();

	$('#btn_create').on('click', function() {
		$('#post_status').val('ADD');
		clear('.clear');
		$('#give_homework_modal').modal('show');
	});

	$('#subject').on('change', function() {
		sections($(this).val());
		homeworks($(this).val());
	});

	$('#tbl_homeworks_body').on('click', '.btn_edit_homework', function() {
		$('#post_status').val('EDIT');
		
		$('#hw_given_id').val($(this).attr('data-hw_given_id'));
		$('#subject').val($(this).attr('data-subject'));

		sections($(this).attr('data-subject'),$(this).attr('data-section_id'));
		homeworks($(this).attr('data-subject'),$(this).attr('data-hw_id'));

		$('#due_date').val($(this).attr('data-due_date'));
		$('#due_time').val($(this).attr('data-due_time'));
		$('#section').val($(this).attr('data-section_id'));
		$('#hw_id').val($(this).attr('data-hw_id'));
		$('#title').val($(this).attr('data-title'));

		$('#give_homework_modal').modal('show');
	});

	$('#frm_homework').on('submit', function(e) {
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
			getGivenHomeworks();
			$('#give_homework_modal').modal('hide');
		}).fail(function(xhr, textStatus, errorThrown) {
			msg('Save Homework: '+ errorThrown,'error');
		}).always(function() {
			$('.loading').hide();
		});
	});

	$('#btn_delete').on('click', function() {
		var ids = [];
		var msgs = 'Do you want to delete this Given Homework?';

		$('#tbl_homeworks_body').find('.check_homework:checked').each(function(index, el) {
			ids.push($(this).val());
		});

		if (ids.length > 1) {
			msgs = 'Do you want to delete these Given Homeworks?';
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
		        		url: '../../activities/delete-give-homeworks',
		        		type: 'POST',
		        		dataType: 'JSON',
		        		data: {ids: ids},
		        	}).done(function(data, textStatus, xhr) {
		        		clear('.clear');
		        		getGivenHomeworks();
		        		msg(data.msg,data.status);
		        	}).fail(function(xhr, textStatus, errorThrown) {
		        		msg('Delete Homework: '+ errorThrown,'error');
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
		url: '../../activities/give-homework-subjects',
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
		url: '../../activities/give-homework-sections',
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

async function homeworks(subject_id,hw_id) {
	var option = '<option value=""></option>';
	$('#hw_id').html(option);
	await $.ajax({
		url: '../../activities/give-homework-homeworks',
		type: 'GET',
		dataType: 'JSON',
		data: {
			subject_id: subject_id
		}
	}).done(function(data, textStatus, xhr) {
		$.each(data, function(i, x) {
			if (parseInt(hw_id) == parseInt(x.id)) {
				option += '<option value="'+x.id+'" selected>'+x.title+'</option>';
			} else {
				option += '<option value="'+x.id+'">'+x.title+'</option>';
			}
			
		});
		$('#hw_id').html(option);

		if (hw_id !== '') {
			$('#hw_id').val(hw_id);
		}
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Homeworks: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

function getGivenHomeworks() {
	$('.loading').show();

	$.ajax({
		url: '../../activities/get-give-homeworks',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		HomeworksTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Activity Logs: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

function HomeworksTable(arr) {
	$('#tbl_homeworks').dataTable().fnClearTable();
    $('#tbl_homeworks').dataTable().fnDestroy();
    $('#tbl_homeworks').dataTable({
        data: arr,
        responsive: true,
        scrollX: true,
        order: [[ 4, "desc" ]],
        columns: [
        	{ data: function(data) {
        		return '<input type="checkbox" class="check_homework" value="'+data.id+'">';
        	}, searchable: false, orderable: false },
		    { data: function(data) {
		    	return '<button class="btn btn-sm btn-primary btn_edit_homework"'+
		    				'data-hw_given_id="'+data.id+'" '+
		    				'data-subject="'+data.subject_id+'" '+
		    				'data-due_date="'+data.due_date+'" '+
		    				'data-due_time="'+data.due_time+'" '+
		    				'data-section_id="'+data.section_id+'"'+
		    				'data-hw_id="'+data.hw_id+'"'+
		    				'data-title="'+data.title+'">'+
		    				'<i class="fa fa-edit"></i>'+
		    			'</button>';
		    }, searchable: false, orderable: false },
		    { data: 'subject' },
		    { data: 'title' },
		    { data: 'date_given' }

		]
    });
}