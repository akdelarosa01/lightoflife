$( function() {
	checkAllCheckboxesInTable('.check_all','.check_item');
	getDepartment();

	$('#frm_department').on('submit', function(e) {
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
			getDepartment();
		}).fail(function(xhr, textStatus, errorThrown) {
			msg('Department: '+ errorThrown,'error');
		}).always(function() {
			$('.loading').hide();
		});
		
	});

	$('#tbl_dept_body').on('click', '.btn_edit_dept', function() {
		$('#id').val($(this).attr('data-id'));
		$('#department').val($(this).attr('data-department'));
	});

	$('#btn_delete').on('click', function() {
		var ids = [];
		var msgs = 'Do you want to delete this Department?';

		$('#tbl_dept_body').find('.check_item:checked').each(function(index, el) {
			ids.push($(this).val());
		});

		if (ids.length > 1) {
			msgs = 'Do you want to delete these Departments?';
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
		        		url: '../../maintenance/delete-departments',
		        		type: 'POST',
		        		dataType: 'JSON',
		        		data: {ids: ids},
		        	}).done(function(data, textStatus, xhr) {
		        		clear('.clear');
		        		getDepartment();
		        		msg(data.msg,data.status);
		        	}).fail(function(xhr, textStatus, errorThrown) {
		        		msg('Department: '+ errorThrown,'error');
		        	});
		        	
		        }
		    }
		});
	});
});

function getDepartment() {
	$.ajax({
		url: '../../maintenance/get-departments',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		DepartmentTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Department: '+ errorThrown,'error');
	});
	
}

function DepartmentTable(arr) {
	$('#tbl_dept').dataTable().fnClearTable();
    $('#tbl_dept').dataTable().fnDestroy();
    $('#tbl_dept').dataTable({
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
		    	return "<button class='btn btn-sm btn-primary btn_edit_dept'"+
		    				"data-id='"+data.id+"' "+
		    				"data-department='"+data.department+"' >"+
		    				"<i class='fa fa-edit'></i>"+
		    			"</button>";
		    } },
		    { data: 'department' },
		    { data: 'create_user' },
		    { data: 'created_at' }
		]
    });
}