$( function() {
	checkAllCheckboxesInTable('.check_all','.check_item');
	getAnnouncement();

	$('#frm_announcement').on('submit', function(e) {
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
			getAnnouncement();
		}).fail(function(xhr, textStatus, errorThrown) {
			msg('Announcement: '+ errorThrown,'error');
		}).always(function() {
			$('.loading').hide();
		});
		
	});

	$('#tbl_announcement_body').on('click', '.btn_edit_announcement', function() {
		$('#id').val($(this).attr('data-id'));
		$('#title').val($(this).attr('data-title'));
		$('#announcement').val($(this).attr('data-announcement'));
	});

	$('#btn_delete').on('click', function() {
		var ids = [];
		var msgs = 'Do you want to delete this Announcement?';

		$('#tbl_announcement_body').find('.check_item:checked').each(function(index, el) {
			ids.push($(this).val());
		});

		if (ids.length > 1) {
			msgs = 'Do you want to delete these Announcements?';
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
		        		url: '../../maintenance/delete-announcement',
		        		type: 'POST',
		        		dataType: 'JSON',
		        		data: {ids: ids},
		        	}).done(function(data, textStatus, xhr) {
		        		clear('.clear');
		        		getAnnouncement();
		        		msg(data.msg,data.status);
		        	}).fail(function(xhr, textStatus, errorThrown) {
		        		msg('Announcement: '+ errorThrown,'error');
		        	});
		        	
		        }
		    }
		});
	});
});

function getAnnouncement() {
	$.ajax({
		url: '../../maintenance/get-announcement',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		AnnouncementTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Announcement: '+ errorThrown,'error');
	});
	
}

function AnnouncementTable(arr) {
	$('#tbl_announcement').dataTable().fnClearTable();
    $('#tbl_announcement').dataTable().fnDestroy();
    $('#tbl_announcement').dataTable({
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
		    	return "<button class='btn btn-sm btn-primary btn_edit_announcement'"+
		    				"data-id='"+data.id+"' "+
		    				"data-title='"+data.title+"' "+
		    				"data-announcement='"+data.announcement+"' >"+
		    				"<i class='fa fa-edit'></i>"+
		    			"</button>";
		    } },
		    { data: 'title' },
		    { data: 'create_user' },
		    { data: 'created_at' }
		]
    });
}