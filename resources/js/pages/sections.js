$( function() {
	checkAllCheckboxesInTable('.check_all','.check_item');
	programDropdown();
	getSections();

	$('#frm_section').on('submit', function(e) {
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
			getSections();
		}).fail(function(xhr, textStatus, errorThrown) {
			msg('Section: '+ errorThrown,'error');
		}).always(function() {
			$('.loading').hide();
		});
		
	});

	$('#tbl_section_body').on('click', '.btn_edit_section', function() {
		$('#id').val($(this).attr('data-id'));
		$('#title').val($(this).attr('data-section'));
		$('#program').val($(this).attr('data-program_id'));
	});

	$('#btn_delete').on('click', function() {
		var ids = [];
		var msgs = 'Do you want to delete this Section?';

		$('#tbl_section_body').find('.check_item:checked').each(function(index, el) {
			ids.push($(this).val());
		});

		if (ids.length > 1) {
			msgs = 'Do you want to delete these Sections?';
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
		        		url: '../../maintenance/delete-sections',
		        		type: 'POST',
		        		dataType: 'JSON',
		        		data: {ids: ids},
		        	}).done(function(data, textStatus, xhr) {
		        		clear('.clear');
		        		getSections();
		        		msg(data.msg,data.status);
		        	}).fail(function(xhr, textStatus, errorThrown) {
		        		msg('Section: '+ errorThrown,'error');
		        	});
		        	
		        }
		    }
		});
	});

});

function getSections() {
	$.ajax({
		url: '../../maintenance/get-sections',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		SectionsTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Sections: '+ errorThrown,'error');
	});
}

function SectionsTable(arr) {
	$('#tbl_section').dataTable().fnClearTable();
    $('#tbl_section').dataTable().fnDestroy();
    $('#tbl_section').dataTable({
        data: arr,
        responsive: true,
        scrollX: true,
        columnDefs : [
        	{ targets: 0, sortable: false},
        	{ targets: 1, sortable: false},
        ],
        order: [[ 4, "desc" ]],
        columns: [
		    { data: function(data) {
		    	return '<input type="checkbox" class="check_item" value="'+data.id+'">';
		    } },
		    { data: function(data) {
		    	return "<button class='btn btn-sm btn-primary btn_edit_section'"+
		    				"data-id='"+data.id+"' "+
		    				"data-section='"+data.section+"' "+
		    				"data-program='"+data.program+"' "+
		    				"data-program_id='"+data.program_id+"' >"+
		    				"<i class='fa fa-edit'></i>"+
		    			"</button>";
		    } },
		    { data: 'section' },
		    { data: 'program' },
		    { data: 'created_at' }
		]
    });
}