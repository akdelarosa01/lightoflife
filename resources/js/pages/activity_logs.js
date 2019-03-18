$( function() {
	getActivityLogs();
});

function getActivityLogs() {
	$('.loading').show();

	$.ajax({
		url: '../../monitoring/get-activity-logs',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		ActivityLogsTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Activity Logs: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

function ActivityLogsTable(arr) {
	$('#tbl_activity').dataTable().fnClearTable();
    $('#tbl_activity').dataTable().fnDestroy();
    $('#tbl_activity').dataTable({
        data: arr,
        responsive: true,
        scrollX: true,
        order: [[ 4, "desc" ]],
        columns: [
		    { data: 'username' },
		    { data: 'fullname' },
		    { data: 'module' },
		    { data: 'activity' },
		    { data: 'created_at' }
		]
    });
}