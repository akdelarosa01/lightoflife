$( function() {
	$('#frm_password').on('submit', function(e) {
		e.preventDefault();
		$('.loading').show();
		$.ajax({
			url: $(this).attr('action'),
			type: 'POST',
			dataType: 'JSON',
			data: $(this).serialize(),
		}).done(function(data, textStatus, xhr) {
			msg(data.msg,data.status);
			clear('.clear');
		}).fail(function(xhr, textStatus, errorThrown) {
			msg('Change Password: ' + errorThrown,'error');
		}).always(function() {
			$('.loading').hide();
		});
		 
	});

	$('#old_password').on('change', function() {
		if ($(this).val() != '') {
			checkIfSameAsOldPassword($(this).val());
		}
		
	});

	$('#new_password').on('change', function() {
		if ($(this).val() != '' && $('#confirm_password').val() != '') {
			checkIfNewConfirmIsSame($(this).val(),$('#confirm_password').val());
		}
	});

	$('#confirm_password').on('change', function() {
		if ($('#new_password').val() != '' && $(this).val() != '') {
			checkIfNewConfirmIsSame($('#new_password').val(),$(this).val());
		}
		
	});
});

function checkIfNewConfirmIsSame(newp,confirm) {
	if (newp != confirm) {
		var errors = {
			'confirm_password': 'Password did not matched.',
			'new_password': 'Password did not matched.'
		}
		showErrors(errors);
		$('#btn_save').prop('disabled', true);
	} else {
		$('#btn_save').prop('disabled', false);
	}
}

function checkIfSameAsOldPassword(password) {
	$.ajax({
		url: '../../settings/check-password',
		type: 'GET',
		dataType: 'JSON',
		data: {
			password: password
		},
	}).done(function(data, textStatus, xhr) {
		console.log(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		var errors = xhr.responseJSON.errors;

		if (errors != undefined) {
			showErrors(errors);
		} else {
			msg('Check Password: '+errorThrown,'error');
		}
	});
}