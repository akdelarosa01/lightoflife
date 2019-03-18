$( function() {
	checkAllCheckboxesInTable('.check_all','.check_item');
	getPrograms();

	$('#frm_program').on('submit', function(e) {
		e.preventDefault();
		$('.loading').show();

		$.ajax({
			url: $(this).attr('action'),
			type: 'POST',
			dataType: 'JSON',
			data: $(this).serialize(),
		}).done(function(data, textStatus, xhr) {
			clear('.clear');
			msg(data.msg,data.status);
			getPrograms();
		}).fail(function(xhr, textStatus, errorThrown) {
			msg('Programs: '+ errorThrown,'error');
		}).always(function() {
			$('.loading').hide();
		});
		
	});

	$('#tbl_programs_body').on('click', '.btn_edit_program', function() {
		$('#id').val($(this).attr('data-id'));
		$('#program').val($(this).attr('data-program'));
	});

	$('#btn_delete').on('click', function() {
		var ids = [];
		var msgs = 'Do you want to delete this Programs?';

		$('#tbl_programs_body').find('.check_item:checked').each(function(index, el) {
			ids.push($(this).val());
		});

		if (ids.length > 1) {
			msgs = 'Do you want to delete these Programs?';
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
		        		url: '../../maintenance/delete-programs',
		        		type: 'POST',
		        		dataType: 'JSON',
		        		data: {ids: ids},
		        	}).done(function(data, textStatus, xhr) {
		        		clear('.clear');
		        		getPrograms();
		        		msg(data.msg,data.status);
		        	}).fail(function(xhr, textStatus, errorThrown) {
		        		msg('Programs: '+ errorThrown,'error');
		        	});
		        	
		        }
		    }
		});
	});
});

function getPrograms() {
	$.ajax({
		url: '../../maintenance/get-programs',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		AnnouncementTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Programs: '+ errorThrown,'error');
	});
	
}

function AnnouncementTable(arr) {
	$('#tbl_programs').dataTable().fnClearTable();
    $('#tbl_programs').dataTable().fnDestroy();
    $('#tbl_programs').dataTable({
        data: arr,
        responsive: true,
        scrollX: true,
        columnDefs : [
        	{ targets: 0, sortable: false},
        	{ targets: 1, sortable: false},
        ],
        order: [[ 4, "asc" ]],
        columns: [
		    { data: function(data) {
		    	return '<input type="checkbox" class="check_item" value="'+data.id+'">';
		    } },
		    { data: function(data) {
		    	return "<button class='btn btn-sm btn-primary btn_edit_program'"+
		    				"data-id='"+data.id+"' "+
		    				"data-program='"+data.program+"' >"+
		    				"<i class='fa fa-edit'></i>"+
		    			"</button>";
		    } },
		    { data: 'program' },
		    { data: 'create_user' },
		    { data: 'created_at' }
		]
    });
}