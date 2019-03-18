$( function() {
	getEnrolled();
	programDropdown();
	schoolYearDropdown();

	checkAllCheckboxesInTable('.check_all_students','.check_student');

	$('#btn_enroll_students').on('click', function() {
		$('.check_all_students').prop('checked',false);
		$('.check_student').prop('checked',false);

		StudentsTable([]);
		SubjectsTable([]);

		clear('.clear');

		$('#enroll_students_modal').modal('show');
	});

	$('#enroll_students_modal').on('shown.bs.modal', function(e){
		$($.fn.dataTable.tables(true)).DataTable()
		.columns.adjust();
	});

	$('#tbl_students_body').on('change', '.check_student', function() {
		var count = 0;

		if ($('#no_of_students').val() > 0) {
			$('.check_student:checked').each(function(index, el) {
				count++;
			});

			if (count > $('#no_of_students').val()) {
				$(this).prop('checked',false);
				msg("Select only "+$('#no_of_students').val()+" students","failed");
			}
		} else {
			msg("Please select number of students.","failed");
			$(this).prop('checked',false);
		}
	});

	$('.check_all_students').on('change', function() {
		var count = 0;

		if ($('#no_of_students').val() > 0) {
			$('.check_student:checked').each(function(index, el) {
				count++;
			});

			if (count > $('#no_of_students').val()) {
				$('.check_student:checked').prop('checked',false);
				msg("Select only "+$('#no_of_students').val()+" students","failed");
			}
		} else {
			msg("Please select number of students.","failed");
			$('.check_student:checked').prop('checked',false);
		}
	});

	$('#program').on('change', function() {
		sectionDropdown($(this).val());
		getSubjects($(this).val());
		getStudents($(this).val());
	});

	$('#section').on('change', function() {
		getStudents($('#program').val(),$(this).val());
	});

	$('#tbl_enrolled_body').on('click', '.btn_edit_enrolled', function() {
		sectionDropdown($(this).attr('data-program'),$(this).attr('data-section'));
		getSubjects($(this).attr('data-program'));
		getStudents($(this).attr('data-program'),$(this).attr('data-section'));

		$('#es_id').val($(this).attr('data-id'));
		$('#program').val($(this).attr('data-program'));
		//$('#section').val($(this).attr('data-section'));
		$('#school_year').val($(this).attr('data-school_year'));
		$('#no_of_students').val($(this).attr('data-no_of_students'));

		$('.check_all_students').prop('checked',false);
		$('.check_student').prop('checked',false);

		$('#enroll_students_modal').modal('show');
	});

	$('#frm_enroll').on('submit', function(e) {
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
			getEnrolled();
			StudentsTable();
			SubjectsTable();
		}).fail(function(xhr, textStatus, errorThrown) {
			msg('Save enrollment: '+ errorThrown,'error');
		}).always(function() {
			$('.loading').hide();
		});
	});
});

async function getEnrolled() {
	$('.loading').show();

	await $.ajax({
			url: '../../transaction/get-enrolled',
			type: 'GET',
			dataType: 'JSON',
		}).done(function(data, textStatus, xhr) {
			EnrolledTable(data);
		}).fail(function(xhr, textStatus, errorThrown) {
			msg('Enrolled: '+ errorThrown,'error');
		}).always(function() {
			$('.loading').hide();
		});
}

function EnrolledTable(arr) {
	$('#tbl_enrolled').dataTable().fnClearTable();
    $('#tbl_enrolled').dataTable().fnDestroy();
    $('#tbl_enrolled').dataTable({
        data: arr,
        responsive: true,
        scrollX: true,
        columnDefs : [
        	{ targets: 5, sortable: false},
        ],
        order: [[ 0, "asc" ]],
        columns: [
		    { data: 'program' },
		    { data: 'section' },
		    { data: 'school_year' },
		    { data: 'no_of_students' },
		    { data: 'no_of_students_enrolled' },
		    { data: function(data) {
		    	return '<button class="btn btn-sm btn-primary btn_edit_enrolled"'+
		    				'data-id="'+data.id+'"'+
		    				'data-program="'+data.program_id+'"'+
		    				'data-section="'+data.section_id+'"'+
		    				'data-school_year="'+data.school_year+'"'+
		    				'data-no_of_students="'+data.no_of_students+'">'+
		    				'<i class="fa fa-edit"></i>'+
		    			'</button>';
		    }, searchable: false, orderable: false}
		]
    });
}

async function getSubjects(prog_id) {
	$('.loading').show();
    await $.ajax({
	        url: '../../transaction/get-enrolled-subjects',
	        type: 'GET',
	        dataType: 'JSON',
	        data: {
	            prog_id: prog_id
	        }
	    }).done(function(data, textStatus, xhr) {
	        SubjectsTable(data);
	    }).fail(function(xhr, textStatus, errorThrown) {
	        msg('Subjects: '+ errorThrown,'error');
	    }).always(function() {
			$('.loading').hide();
		});
}

function SubjectsTable(arr) {
	$('#tbl_subjects').dataTable().fnClearTable();
    $('#tbl_subjects').dataTable().fnDestroy();
    $('#tbl_subjects').dataTable({
        data: arr,
        responsive: true,
        sorting: false,
        searching: false,
        lengthChange: false,
        order: [[ 0, "asc" ]],
        columns: [
		    { data: 'code' },
		    { data: 'description' }
		]
    });
}

async function getStudents(prog_id,sec_id) {
	$('.loading').show();
    await $.ajax({
	        url: '../../transaction/get-enrolled-students',
	        type: 'GET',
	        dataType: 'JSON',
	        data: {
	            prog_id: prog_id,
	            sec_id: sec_id
	        }
	    }).done(function(data, textStatus, xhr) {
	        StudentsTable(data);
	    }).fail(function(xhr, textStatus, errorThrown) {
	        msg('Students: '+ errorThrown,'error');
	    }).always(function() {
			$('.loading').hide();
		});
}

function StudentsTable(arr) {
	$('#tbl_students').dataTable().fnClearTable();
    $('#tbl_students').dataTable().fnDestroy();
    $('#tbl_students').dataTable({
        data: arr,
        responsive: true,
        sorting: false,
        searching: false,
        lengthChange: false,
        pageLength: 50,
        scrollX: true,
        columnDefs : [
        	{ targets: 0, sortable: false},
        ],
        order: [[ 1, "asc" ]],
        columns: [
        	{ data: function(data) {
        		var checked = '';
        		if (data.section_id > 0) {
        			checked = 'checked';
        		}
        		return '<input type="checkbox" class="check_student" name="student_user_id[]" value="'+data.id+'" '+checked+'>';
        	}, searchable: false, orderable: false },
		    { data: 'id_number' },
		    { data: 'fullname' }
		],
		createdRow: function (row, data, dataIndex) {
            if (data.section_id > 0) {
                $(row).css('background-color', '#17a2b8');
                $(row).css('color', '#fff');
            }
        }
    });
}