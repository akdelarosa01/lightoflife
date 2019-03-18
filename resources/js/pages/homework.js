$( function() {
	getHomeworks();
	getSubjectHandled();

	checkAllCheckboxesInTable('.check_all_homeworks','.check_homework');

	$('#btn_create').on('click', function() {
		clear('.clear');
		$('#file_label').html('');
		$('#attachements').html('');
		$('#homework_modal').modal('show');
	});

	$('#tbl_homeworks_body').on('click', '.btn_edit_homework', function() {
		$('#hw_id').val($(this).attr('data-hw_id'));
		$('#subject').val($(this).attr('data-subject'));
		$('#title').val($(this).attr('data-title'));
		$('#question').val($(this).attr('data-question'));
		$('#points').val($(this).attr('data-points'));

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

		$('#attachements').html(htmlfiles);
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
            getHomeworks();
			$('#file_label').html('');
			$('#attachements').html('');
			$('#homework_modal').modal('hide');
		}).fail(function(xhr, textStatus, errorThrown) {
			msg('Save Homework: '+ errorThrown,'error');
		}).always(function() {
			$('.loading').hide();
		});
   	});

	$('#btn_delete').on('click', function() {
		var ids = [];
		var msgs = 'Do you want to delete this Homework?';

		$('#tbl_homeworks_body').find('.check_homework:checked').each(function(index, el) {
			ids.push($(this).val());
		});

		if (ids.length > 1) {
			msgs = 'Do you want to delete these Homeworks?';
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
		        		url: '../../activities/delete-homeworks',
		        		type: 'POST',
		        		dataType: 'JSON',
		        		data: {ids: ids},
		        	}).done(function(data, textStatus, xhr) {
		        		clear('.clear');
		        		getHomeworks();
		        		msg(data.msg,data.status);
		        	}).fail(function(xhr, textStatus, errorThrown) {
		        		msg('Homeworks: '+ errorThrown,'error');
		        	});
		        	
		        }
		    }
		});
	});
});


function getHomeworks() {
	$('.loading').show();

	$.ajax({
		url: '../../activities/get-homeworks',
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
		    				'data-hw_id="'+data.id+'" '+
		    				'data-subject="'+data.subject_id+'" '+
		    				'data-title="'+data.title+'" '+
		    				'data-question="'+data.question+'" '+
		    				'data-points="'+data.points+'">'+
		    				'<i class="fa fa-edit"></i>'+
		    			'</button>';
		    }, searchable: false, orderable: false },
		    { data: 'subject' },
		    { data: 'title' },
		    { data: 'created_at' }
		]
    });
}

function getSubjectHandled() {
	var option = '<option value=""></option>';
	$('#subject').html(option);
	$.ajax({
		url: '../../activities/get-subject-handle-hw',
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