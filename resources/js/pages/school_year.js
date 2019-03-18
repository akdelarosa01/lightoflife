$( function() {
	checkAllCheckboxesInTable('.check_all','.check_yr');
	getSchoolYear();

	$('#frm_schoolyear').on('submit', function(e) {
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
			getSchoolYear();
		}).fail(function(xhr, textStatus, errorThrown) {
			msg('School Year: '+ errorThrown,'error');
		}).always(function() {
			$('.loading').hide();
		});
		
	});

	$('#from').on('change', function() {
		var from = parseInt($(this).val());
		var to = from + 1;

		$('#to').val(to);
	});

	$('#tbl_schoolyear_body').on('click', '.btn_edit_schoolyear', function() {
		$('#id').val($(this).attr('data-id'));
		$('#from').val($(this).attr('data-from'));
		$('#to').val($(this).attr('data-to'));
	});

	$('#btn_delete').on('click', function() {
		var ids = [];
		var msgs = 'Do you want to delete this School year?';

		$('#tbl_schoolyear_body').find('.check_yr:checked').each(function(index, el) {
			ids.push($(this).val());
		});

		if (ids.length > 1) {
			msgs = 'Do you want to delete these School years?';
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
		        		url: '../../maintenance/delete-school-year',
		        		type: 'POST',
		        		dataType: 'JSON',
		        		data: {ids: ids},
		        	}).done(function(data, textStatus, xhr) {
		        		getSchoolYear();
		        		msg(data.msg,data.status);
		        	}).fail(function(xhr, textStatus, errorThrown) {
		        		msg('School Year: '+ errorThrown,'error');
		        	});
		        	
		        }
		    }
		});
	});
});

function getSchoolYear() {
	$.ajax({
		url: '../../maintenance/get-school-year',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		SchoolYearTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('School Year: '+ errorThrown,'error');
	});
}

function SchoolYearTable(arr) {
	$('#tbl_schoolyear').dataTable().fnClearTable();
    $('#tbl_schoolyear').dataTable().fnDestroy();
    $('#tbl_schoolyear').dataTable({
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
		    	return '<input type="checkbox" class="check_yr" value="'+data.id+'">';
		    } },
		    { data: function(data) {
		    	return '<button class="btn btn-sm btn-primary btn_edit_schoolyear"'+
		    				'data-id="'+data.id+'" '+
		    				'data-from="'+data.from+'" '+
		    				'data-to="'+data.to+'" >'+
		    				'<i class="fa fa-edit"></i>'+
		    			'</button>';
		    } },
		    { data: 'from' },
		    { data: 'to' },
		    { data: 'created_at' }
		]
    });
}