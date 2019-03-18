$( function() {
	getRecipients();
	getMessages();

	checkAllCheckboxesInTable('.check_all','.check_msg');

	$('#btn_new').on('click', function() {
		$('#message_modal').modal('show');
	});

	$('#msg_file').bind('change', function() {
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

	$('#frm_message').on('submit', function(e) {
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
            getMessages();
			$('#file_label').html('');
			$('#attachements').html('');
			$('#message_modal').modal('hide');
		}).fail(function(xhr, textStatus, errorThrown) {
			msg('Send Message: '+ errorThrown,'error');
		}).always(function() {
			$('.loading').hide();
		});
   	});

   	$('#btn_delete').on('click', function() {
		var ids = [];
		var msgs = 'Do you want to delete this Message?';

		$('#tbl_messages_body').find('.check_msg:checked').each(function(index, el) {
			ids.push($(this).val());
		});

		if (ids.length > 1) {
			msgs = 'Do you want to delete these Messages?';
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
		        		url: '../../messages/delete',
		        		type: 'POST',
		        		dataType: 'JSON',
		        		data: {ids: ids},
		        	}).done(function(data, textStatus, xhr) {
		        		clear('.clear');
		        		getMessages();
		        		msg(data.msg,data.status);
		        	}).fail(function(xhr, textStatus, errorThrown) {
		        		msg('Accounts: '+ errorThrown,'error');
		        	});
		        	
		        }
		    }
		});
	});
});

function getMessages() {
	$('.loading').show();

	$.ajax({
		url: '../../messages/get-messages',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		MessageTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Get Message: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

function MessageTable(arr) {
	$('#tbl_messages').dataTable().fnClearTable();
    $('#tbl_messages').dataTable().fnDestroy();
    $('#tbl_messages').dataTable({
        data: arr,
        responsive: true,
        scrollX: true,
        order: [[ 4, "desc" ]],
        columns: [
        	{ data: function(data) {
		    	return '<input type="checkbox" class="check_msg" value="'+data.subject_id+'">';
		    }, searchable: false, orderable: false },
		    { data: function(data) {
		    	return '<a href="../../messages/conversation/'+data.subject_id+'" class="btn btn-sm btn-primary btn_convo"'+
		    				'data-subject_id="'+data.subject_id+'"'+
		    				'>'+
		    				'<i class="fa fa-comments"></i>'+
		    			'</a>';
		    }, searchable: false, orderable: false },
		    { data: 'user_from' },
		    { data: 'subject_msg' },
		    { data: 'date_sent' }
		]
    });
}

function getRecipients() {
	$('.loading').show();
	var options = '<option value=""></option>';
	$('#recipients').html(options);
	$.ajax({
		url: '../../messages/get-all-recipients',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		$.each(data, function(i, x) {
			options += '<option value="'+x.id+'">'+x.fullname+'</option>';
		});
		$('#recipients').html(options);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Recipients: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}