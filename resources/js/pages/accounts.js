$( function() {
	$('.teacher').hide();
	$('.student').hide();
	$('#user_type').prop('readonly', false);

	checkAllCheckboxesInTable('.check_all_accounts','.check_account');
	getAccounts();
	programDropdown();
	departmentDropdown();

	$('#frm_accounts').on('submit', function(e) {
		e.preventDefault();
		
		if ($('#user_type').val() == 2) {
			if ($('#program').val() == '') {
				var errors = {
					'program': 'Program is required.',
				}
				showErrors(errors);
			} else if ($('#department').val() == '') {
				var errors = {
					'department': 'Department is required.',
				}
				showErrors(errors);
			} else {
				save($(this).attr('action'), $(this).serialize());
			}
		}

		if ($('#user_type').val() == 3) {
			if ($('#program').val() == '') {
				var errors = {
					'program': 'Program is required.',
				}
				showErrors(errors);
			} else {
				save($(this).attr('action'), $(this).serialize());
			}
		}

		if ($('#user_type').val() == 1) {
			save($(this).attr('action'), $(this).serialize());
		}
	});

	$('#user_type').on('change', function() {
		if ($(this).val() == 1 || $(this).val() == '') {
			$('.teacher').hide();
			$('.student').hide();
		}

		if ($(this).val() == 2) {
			$('.student').hide();
			$('.teacher').show();
		}

		if ($(this).val() == 3) {
			$('.teacher').hide();
			$('.student').show();
		}
	});

	$('#tbl_account_body').on('click', '.btn_edit_account', function() {
		$('#id').val($(this).attr('data-id'));
		$('#username').val($(this).attr('data-username'));
		$('#firstname').val($(this).attr('data-firstname'));
		$('#middlename').val($(this).attr('data-middlename'));
		$('#lastname').val($(this).attr('data-lastname'));
		$('#user_type').val($(this).attr('data-user_type'));

		if ($(this).attr('data-user_type') == 2) {
			$('.student').hide();
			$('.teacher').show();
			$('#program').val($(this).attr('data-program'));
			$('#department').val($(this).attr('data-department'));
		}

		if ($(this).attr('data-user_type') == 3) {
			$('.teacher').hide();
			$('.student').show();
			$('#program').val($(this).attr('data-program'));
		}

		if ($(this).attr('data-user_type') == 1 || $(this).attr('data-user_type') == '') {
			$('.teacher').hide();
			$('.student').hide();
		}

		$('#user_type').prop('readonly', true);
	});

	$('#btn_delete').on('click', function() {
		var ids = [];
		var msgs = 'Do you want to delete this Account?';

		$('#tbl_account_body').find('.check_account:checked').each(function(index, el) {
			ids.push($(this).val());
		});

		if (ids.length > 1) {
			msgs = 'Do you want to delete these Accounts?';
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
		        		url: '../../maintenance/delete-accounts',
		        		type: 'POST',
		        		dataType: 'JSON',
		        		data: {ids: ids},
		        	}).done(function(data, textStatus, xhr) {
		        		clear('.clear');
		        		getAccounts();
		        		msg(data.msg,data.status);
		        	}).fail(function(xhr, textStatus, errorThrown) {
		        		msg('Accounts: '+ errorThrown,'error');
		        	});
		        	
		        }
		    }
		});
	});

	$('#btn_enable').on('click', function() {
		var ids = [];
		var msgs = 'Do you want to enable this Account?';

		$('#tbl_account_body').find('.check_account:checked').each(function(index, el) {
			ids.push($(this).val());
		});

		if (ids.length > 1) {
			msgs = 'Do you want to enable these Accounts?';
		}

		bootbox.confirm({
		    message: msgs,
		    buttons: {
		        confirm: {
		            label: 'Yes',
		            className: 'btn-success'
		        },
		        cancel: {
		            label: 'Cancel',
		            className: 'btn-secondary'
		        }
		    },
		    callback: function (result) {
		        if (result) {
		        	$.ajax({
		        		url: '../../maintenance/enable-accounts',
		        		type: 'POST',
		        		dataType: 'JSON',
		        		data: {ids: ids},
		        	}).done(function(data, textStatus, xhr) {
		        		clear('.clear');
		        		getAccounts();
		        		msg(data.msg,data.status);
		        	}).fail(function(xhr, textStatus, errorThrown) {
		        		msg('Accounts: '+ errorThrown,'error');
		        	});
		        	
		        }
		    }
		});
	});

	$('#btn_clear').on('click', function() {
		clear('.clear');
	});

	$('#account_file').on('change', function() {
		var pros = $(this).val().replace("C:\fakepath", "");
        var fileN = pros.substring(12, pros.length);

		$('#file_label').html(fileN);
	});

	$('#frm_upload_accounts').on('submit', function(e) {
		e.preventDefault();

		var formURL = $(this).attr("action");
		var formData = new FormData(this);
		var fileName = $("#account_file").val();
		var ext = fileName.split('.').pop();

		if (fileName == '') {
			msg("Please browse for an excel file.","failed"); 
		} else {
			if (ext == 'xls' || ext == 'xlsx' || ext == 'XLS' || ext == 'XLSX') {
				$('.loading').show();

				$.ajax({
					url: $(this).attr('action'),
					type: 'POST',
					mimeType:"multipart/form-data",
					contentType: false,
					cache: false,
					processData:false,
					data: formData,
				}).done(function(data, textStatus, xhr) {
					var return_data = jQuery.parseJSON(data);

					msg(return_data.msg,return_data.status);

					getAccounts();

					$('#file_label').html('');

					clear('.clear');

					var not_saved = return_data.not_saved;

					if (not_saved.length > 0) {
						notSavedTable(not_saved);
						$('#not_saved_modal').modal('show');
					}
				}).fail(function(xhr, textStatus, errorThrown) {
					msg('Upload Accounts: '+ errorThrown,'error');
				}).always(function() {
					$('.loading').hide();
				});
			} else {
				$('.loading').hide();
				msg("Please select a valid excel file.","failed");
			}
		} 
	});

	$('#not_saved_modal').on('shown.bs.modal', function(e){
		$($.fn.dataTable.tables(true)).DataTable()
		.columns.adjust();
	});
});

async function getAccounts() {
	$('.loading').show();

	await $.ajax({
			url: '../../maintenance/get-accounts',
			type: 'GET',
			dataType: 'JSON',
		}).done(function(data, textStatus, xhr) {
			AccountsTable(data);
		}).fail(function(xhr, textStatus, errorThrown) {
			msg('Accounts: '+ errorThrown,'error');
		}).always(function() {
			$('.loading').hide();
		});
}

function AccountsTable(arr) {
	$('#tbl_account').dataTable().fnClearTable();
    $('#tbl_account').dataTable().fnDestroy();
    $('#tbl_account').dataTable({
        data: arr,
        responsive: true,
        scrollX: true,
        columnDefs : [
        	{ targets: 0, sortable: false},
        	{ targets: 1, sortable: false},
        ],
        order: [[ 5, "asc" ]],
        columns: [
        	{ data: function(data) {
        		return '<input type="checkbox" class="check_account" value="'+data.id+'">';
        	}, searchable: false, orderable: false },
        	{ data: function(data) {
        		return '<button class="btn btn-sm btn-primary btn_edit_account" '+
        					'data-id="'+data.id+'" '+
        					'data-username="'+data.username+'" '+
        					'data-firstname="'+data.firstname+'" '+
        					'data-middlename="'+data.middlename+'" '+
        					'data-lastname="'+data.lastname+'" '+
        					'data-program="'+data.program+'" '+
        					'data-department="'+data.department+'" '+
        					'data-user_type="'+data.user_type+'" >'+
        					'<i class="fa fa-edit"></i>'+
        				'</button>';
        	}, searchable: false, orderable: false },
		    { data: 'username' },
		    { data: 'firstname' },
		    { data: 'middlename' },
		    { data: 'lastname' },
		    { data: function(data) {
		    	if (data.user_type == 1) {
		    		return 'Administrator';
		    	}

		    	if (data.user_type == 2) {
		    		return 'Teacher';
		    	}

		    	if (data.user_type == 3) {
		    		return 'Student';
		    	}
		    } },
		    { data: 'actual_password' },
		],
		createdRow: function (row, data, dataIndex) {
            if (data.is_deleted > 0) {
                $(row).css('background-color', '#ff6266');
                $(row).css('color', '#fff');
            }
        }
    });
}

function save(action, inputs) {
	$('.loading').show();

	$.ajax({
		url: action,
		type: 'POST',
		dataType: 'JSON',
		data: inputs,
	}).done(function(data, textStatus, xhr) {
		getAccounts();
		msg(data.msg,data.status);
		clear('.clear');

		$('#user_type').prop('readonly', false);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Accounts: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

function notSavedTable(arr) {
	$('#tbl_not_saved').dataTable().fnClearTable();
    $('#tbl_not_saved').dataTable().fnDestroy();
    $('#tbl_not_saved').dataTable({
        data: arr,
        responsive: true,
        scrollX: true,
        order: [[ 2, "asc" ]],
        columns: [
		    { data: 'firstname' },
		    { data: 'middlename' },
		    { data: 'lastname' }
		]
    });
}