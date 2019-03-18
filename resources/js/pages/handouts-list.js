$( function() {
	getHandouts();
});

function getHandouts() {
	$('.loading').show();

	$.ajax({
		url: '../../monitoring/get-handouts',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		HandoutsTable(data);
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Handouts: '+ errorThrown,'error');
	}).always(function() {
		$('.loading').hide();
	});
}

function HandoutsTable(arr) {
	$('#tbl_handouts').dataTable().fnClearTable();
    $('#tbl_handouts').dataTable().fnDestroy();
    $('#tbl_handouts').dataTable({
        data: arr,
        responsive: true,
        scrollX: true,
        columnDefs : [
        	{ targets: 0, sortable: false},
        ],
        order: [[ 1, "asc" ]],
        columns: [
        	{ data: function(data) {
		    	return '<a href="/'+data.file_path+'" class="btn btn-sm btn-primary" target="_blank">'+
        					'<i class="fa fa-eye"></i>'+
        				'</a>'+
        				'<a href="/'+data.file_path+'" download class="btn btn-sm btn-info">'+
        					'<i class="fa fa-download"></i>'+
        				'</a>';
		    } },
		    { data: 'program' },
		    { data: 'subject' },
		    { data: 'description' },
		    
		]
    });
}