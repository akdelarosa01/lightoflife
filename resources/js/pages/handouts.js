$( function() {
	getClass();
	checkAllCheckboxesInTable('.check_all_handouts','.check_handout');

	$('#tbl_class_body').on('click', '.btn_view_handouts', function() {
		getHandouts($(this).attr('data-section_id'),$(this).attr('data-subject_id'),$(this).attr('data-program_id'));
		$('#program').val($(this).attr('data-program'));
		$('#program_id').val($(this).attr('data-program_id'));
		$('#subject').val($(this).attr('data-subject'));
		$('#subject_id').val($(this).attr('data-subject_id'));
		$('#section').val($(this).attr('data-section'));
		$('#section_id').val($(this).attr('data-section_id'));

		$('#handouts_modal').modal('show');
	});

	$('#handouts_modal').on('shown.bs.modal', function(e){
		$($.fn.dataTable.tables(true)).DataTable()
		.columns.adjust();
	});

	$('#handouts_file').on('change', function() {
		var pros = $(this).val().replace("C:\fakepath", "");
        var fileN = pros.substring(12, pros.length);

		$('#file_label').html(fileN);
	});

	$('#frm_handouts').on('submit', function(e) {
        $('.loading').show();
   		e.preventDefault();
   		var data = new FormData(this);

   		$.ajax({
			url: $(this).attr('action'),
			type: 'POST',
			dataType: 'JSON',
			data: data,
			mimeType:"multipart/form-data",
			contentType: false,
			cache: false,
			processData:false,
		}).done(function(data, textStatus, xhr) {
			//var return_data = jQuery.parseJSON(data);
            $('.loading').hide();
            msg(data.msg,data.status);
            getHandouts($('#section_id').val(),$('#subject_id').val(),$('#program_id').val());
            clear('.clear');
            $('#handouts_file').val('');
            $('#file_label').html('');
		}).fail(function(xhr, textStatus, errorThrown) {
            if(errorThrown == "Internal Server Error"){
                msg('Save Handout: ' +errorThrown,textStatus);
            }

            $('.loading').hide();
		}).always(function() {
			$('.loading').hide();
		});
   	});

   	$('#btn_delete').on('click', function() {
		var ids = [];
		var msgs = 'Do you want to delete this Handout?';

		$('#tbl_handouts_body').find('.check_handout:checked').each(function(index, el) {
			ids.push($(this).val());
		});

		if (ids.length > 1) {
			msgs = 'Do you want to delete these Handouts?';
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
		        		url: '../../handouts/delete-handouts',
		        		type: 'POST',
		        		dataType: 'JSON',
		        		data: {
		        			ids: ids
		        		},
		        	}).done(function(data, textStatus, xhr) {
		        		getHandouts($('#section_id').val(),$('#subject_id').val(),$('#program_id').val());
		        		msg(data.msg,data.status);
		        	}).fail(function(xhr, textStatus, errorThrown) {
		        		msg('Accounts: '+ errorThrown,'error');
		        	});
		        	
		        }
		    }
		});
	});
});

async function getClass() {
	$('.loading').show();

	await $.ajax({
				url: '../../handouts/get-class',
				type: 'GET',
				dataType: 'JSON',
			}).done(function(data, textStatus, xhr) {
				ClassTable(data);
			}).fail(function(xhr, textStatus, errorThrown) {
				msg('Class: '+ errorThrown,'error');
			}).always(function() {
				$('.loading').hide();
			});
}

function ClassTable(arr) {
	$('#tbl_class').dataTable().fnClearTable();
    $('#tbl_class').dataTable().fnDestroy();
    $('#tbl_class').dataTable({
        data: arr,
        responsive: true,
        scrollX: true,
        columnDefs : [
        	{ targets: 3, sortable: false},
        ],
        order: [[ 0, "asc" ]],
        columns: [
		    { data: 'program' },
		    { data: 'subject' },
		    { data: 'section' },
		    { data: function(data) {
		    	return '<button class="btn btn-sm btn-primary btn_view_handouts" '+
        					'data-program="'+data.program+'"'+
        					'data-program_id="'+data.program_id+'"'+
        					'data-subject="'+data.subject+'"'+
        					'data-subject_id="'+data.subject_id+'"'+
        					'data-section="'+data.section+'"'+
        					'data-section_id="'+data.section_id+'">'+
        					'<i class="fa fa-eye"></i>'+
        				'</button>';
		    } }
		]
    });
}

async function getHandouts(section_id,subject_id,program_id) {
	$('.loading').show();

	await $.ajax({
				url: '../../handouts/get-handouts',
				type: 'GET',
				dataType: 'JSON',
				data: {
					section_id: section_id,
					subject_id: subject_id,
					program_id: program_id
				}
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
        sorting: false,
        searching: false,
        lengthChange: false,
        scrollX: true,
        columnDefs : [
        	{ targets: 0, sortable: false},
        	{ targets: 1, sortable: false},
        ],
        order: [[ 4, "desc" ]],
        columns: [
        	{ data: function(data) {
        		return '<input type="checkbox" class="check_handout" value="'+data.id+'">';
        	},searchable: false, orderable: false },
        	{ data: function(data) {
		    	return '<a href="/'+data.file_path+'" class="btn btn-sm btn-primary" target="_blank">'+
        					'<i class="fa fa-eye"></i>'+
        				'</a>'+
        				'<a href="/'+data.file_path+'" download class="btn btn-sm btn-info">'+
        					'<i class="fa fa-download"></i>'+
        				'</a>';
		    },searchable: false, orderable: false },
		    { data: 'title' },
		    { data: 'description' },
		    { data: 'date_uploaded' },
		    
		]
    });
}