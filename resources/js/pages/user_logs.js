$( function() {
	getUserLogs();
});

function getUserLogs() {
	$('.loading').show();

	$.ajax({
		url: '../../monitoring/get-user-logs',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		UserLogsTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('User Logs: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

function UserLogsTable(arr) {
	$('#tbl_user').dataTable().fnClearTable();
    $('#tbl_user').dataTable().fnDestroy();
    $('#tbl_user').dataTable({
        data: arr,
        responsive: true,
        scrollX: true,
        order: [[ 3, "desc" ]],
        columns: [
		    { data: 'username' },
		    { data: 'fullname' },
		    { data: 'log_type' },
		    { data: 'logged_at' },
		]
    });
}