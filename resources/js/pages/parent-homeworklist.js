$( function() {
	getPending();
	getFinished();
});

async function getPending() {
	$('.loading').show();

	await $.ajax({
		url: '../../parent-activities/get-pending-homeworks',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		PendingTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Pending: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

function PendingTable(arr) {
	$('#tbl_pending').dataTable().fnClearTable();
    $('#tbl_pending').dataTable().fnDestroy();
    $('#tbl_pending').dataTable({
        data: arr,
        responsive: true,
        scrollX: true,
        columnDefs : [
        	{ targets: 0, sortable: false},
        ],
        order: [[ 4, "desc" ]],
        columns: [
		    { data: function(data) {
		    	return '<button class="btn btn-sm btn-primary btn_answer_hw" data-id="'+data.id+'" '+
		    				'data-hw_id="'+data.hw_id+'"'+
		    				'>'+
		    				'<i class="fa fa-edit"></i>'+
		    			'</button>';
		    }, searchable: false, orderable: false },
		    { data: 'subject' },
		    { data: 'title' },
		    { data: 'status' },
		    { data: 'date_given' }
		]
    });
}

async function getFinished() {
	$('.loading').show();

	await $.ajax({
		url: '../../parent-activities/get-finished-homeworks',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		FinishedTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Finished: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

function FinishedTable(arr) {
	$('#tbl_finished').dataTable().fnClearTable();
    $('#tbl_finished').dataTable().fnDestroy();
    $('#tbl_finished').dataTable({
        data: arr,
        responsive: true,
        scrollX: true,
        columnDefs : [
        	{ targets: 0, sortable: false},
        ],
        order: [[ 4, "desc" ]],
        columns: [
		    { data: function(data) {
		    	return '<button class="btn btn-sm btn-primary btn_view_answer" data-id="'+data.id+'" '+
		    				'data-hw_id="'+data.hw_id+'"'+
		    				'>'+
		    				'<i class="fa fa-edit"></i>'+
		    			'</button>';
		    }, searchable: false, orderable: false },
		    { data: 'subject' },
		    { data: 'title' },
		    { data: 'status' },
		    { data: 'date_submitted' }
		]
    });
}