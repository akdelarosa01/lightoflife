$( function() {
	getConvo($('#subject_id').val());

	$('#msg_file').bind('change', function() {
		var files = $(this)[0].files;
		var htmlfiles = '';
		var label = '';

		$.each(files, function(i, x) {
			htmlfiles += '<button class="btn btn-sm btn-secondary" disabled>'+x.name+' | size: '+bytesToSize(x.size)+'</button>';
			label += x.name+'; ';
		});

		$('#reply_attachments').html(htmlfiles);
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
            getConvo($('#subject_id').val());
			$('#file_label').html('');
			$('#reply_attachments').html('');
		}).fail(function(xhr, textStatus, errorThrown) {
			msg('Send Message: '+ errorThrown,'error');
		}).always(function() {
			$('.loading').hide();
		});
   	});
});

function getConvo(id) {
	$('.loading').show();

	$.ajax({
		url: '../../messages/get-conversations',
		type: 'GET',
		dataType: 'JSON',
		data: {
			subject_id: id
		}
	}).done(function(data, textStatus, xhr) {
		var htmlfiles = '';
		$.each(data.attachments, function(i, x) {
			htmlfiles += '<a href="../../'+x.fullpath+'" class="btn btn-sm btn-secondary" target="_blank">'+x.filename+'</a>';
		});

		$('#attachments').html(htmlfiles);
		$('#user_to').val(data.recipient_id);
		MessageTable(data.msg,data.current_user);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Get Message: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

function MessageTable(arr,current_user) {
	var messages = '';
	$.each(arr, function(i, x) {
		

		if (x.user_id_from == current_user) {
			messages += '<div class="row">'+
							'<div class="col-md-6"></div>'+
							'<div class="col-md-6">'+
								'<small>'+x.user_from+' | '+x.date_sent+'</small>'+
								'<div class="alert alert-success" role="alert">'+
									x.message+
								'</div>'+
								
							'</div>'+
						'</div>';
		} else {
			messages += '<div class="row">'+
							'<div class="col-md-6">'+
								'<small>'+x.user_from+' | '+x.date_sent+'</small>'+
								'<div class="alert alert-secondary" role="alert">'+
									x.message+
								'</div>'+
							'</div>'+
							'<div class="col-md-6"></div>'+
						'</div>';
		}
			
	});

	$('#convo_msg').html(messages);

	var messageBody = document.querySelector('#convo_msg');
	messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
}