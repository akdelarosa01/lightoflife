
$( function() {
    $.ajaxPrefilter(function(options, originalOptions, xhr) {
        var token = $('meta[name="csrf_token"]').attr('content');

        if (token) {
            return xhr.setRequestHeader('X-XSRF-TOKEN', token);
        }
    });

    $('.validate').on('keyup', function(e) {
        var no_error = $(this).attr('id');
        hideErrors(no_error)
    });

    $('.select-validate').on('change', function(e) {
        var no_error = $(this).attr('id');
        hideErrors(no_error)
    });
});

jQuery.fn.extend({
    live: function (event, callback) {
       if (this.selector) {
            jQuery(document).on(event, this.selector, callback);
        }
    }
});

function showErrors(errors) {
	$.each(errors, function(i, x) {
		switch(i) {
			case i:
				$('#'+i).addClass('is-invalid');
				$('#'+i+'_feedback').addClass('invalid-feedback');
                $('#'+i+'_feedback').html(x);
			break;
		}
	});
}

function hideErrors(error) {
	$('#'+error).removeClass('is-invalid');
	$('#'+error+'_feedback').removeClass('invalid-feedback');
	$('#'+error+'_feedback').html('');
}

function jsUcfirst(word) {
    return word.charAt(0).toUpperCase() + word.slice(1);
}

function bytesToSize(bytes) {
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Byte';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
};

/**
 * Open Message
 * @param  {String} msg_content [message content]
 * @param  {String} status [is it 'success','failed', or 'error']
 */
function msg(msg_content,status) {
    if (status == '') {
        //swal("Oops!", msg_content);
        $.toast({
            heading: 'Oops!',
            text: msg_content,
            position: 'top-right',
            loaderBg: '#ff6849',
            icon: 'warning',
            hideAfter: 3000,
            stack: 6
        });
    } else {
        //swal(jsUcfirst(status)+"!", msg_content, status);
        switch(status) {
            case 'success':
               $.toast({
                    heading: jsUcfirst(status)+"!",
                    text: msg_content,
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'success',
                    hideAfter: 5000,
                    stack: 6
                });
            break;

            case 'failed':
               $.toast({
                    heading: jsUcfirst(status)+"!",
                    text: msg_content,
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'warning',
                    hideAfter: 5000,
                    stack: 6
                });
            break;

            case 'warning':
               $.toast({
                    heading: jsUcfirst(status)+"!",
                    text: msg_content,
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'warning',
                    hideAfter: 5000,
                    stack: 6
                });
            break;

            case 'error':
               $.toast({
                    heading: jsUcfirst(status)+"!",
                    text: msg_content,
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'danger',
                    hideAfter: 5000,
                    stack: 6
                });
            break;

            case 'notification':
               $.toast({
                    heading: jsUcfirst(status)+"!",
                    text: msg_content,
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: 'info',
                    hideAfter: 5000,
                    stack: 6
                });
            break;
        }
    }
}

function checkAllCheckboxesInTable(checkAllClass,checkItemClass) {
    $(checkAllClass).on('change', function(e) {
        $('input:checkbox'+checkItemClass).not(this).prop('checked', this.checked);
    });
}

function ellipsis(string,string_count){
    if (string.length > string_count)
        return string.substring(0,string_count)+'...';
    else
        return string;
};

function clear(elem) {
    if (elem.constructor === Array) {
        $.each(elem, function(i, x) {
            $(x).val('');
        });
    } else {
        $(elem).val('');
    }
}

async function programDropdown() {
    var options = '<option value=""></option>';
    $('#program').html(options);
    await $.ajax({
            url: '../../global/get-programs',
            type: 'GET',
            dataType: 'JSON',
        }).done(function(data, textStatus, xhr) {
            $.each(data, function(i, x) {
                options += '<option value="'+x.id+'">'+x.program+'</option>';
            });

            $('#program').html(options);
        }).fail(function(xhr, textStatus, errorThrown) {
            msg('Programs: '+ errorThrown,'error');
        });
}

async function departmentDropdown() {
    var options = '<option value=""></option>';
    $('#department').html(options);
    await $.ajax({
            url: '../../global/get-departments',
            type: 'GET',
            dataType: 'JSON',
        }).done(function(data, textStatus, xhr) {
            $.each(data, function(i, x) {
                options += '<option value="'+x.id+'">'+x.department+'</option>';
            });

            $('#department').html(options);
        }).fail(function(xhr, textStatus, errorThrown) {
            msg('Departments: '+ errorThrown,'error');
        });
}

async function sectionDropdown(prog_id,sec_id) {
    var options = '<option value=""></option>';
    $('#section').html(options);
    await $.ajax({
            url: '../../global/get-sections',
            type: 'GET',
            dataType: 'JSON',
            data: {
                prog_id: prog_id
            }
        }).done(function(data, textStatus, xhr) {
            $.each(data, function(i, x) {
                options += '<option value="'+x.id+'">'+x.section+'</option>';
            });

            $('#section').html(options);

            if (sec_id != '') {
                $('#section').val(sec_id);
            }
        }).fail(function(xhr, textStatus, errorThrown) {
            msg('Sections: '+ errorThrown,'error');
        });
}

async function schoolYearDropdown() {
    var options = '<option value=""></option>';
    $('#school_year').html(options);
    await $.ajax({
            url: '../../global/get-school-year',
            type: 'GET',
            dataType: 'JSON',
        }).done(function(data, textStatus, xhr) {
            $.each(data, function(i, x) {
                options += '<option value="'+x.school_year+'">'+x.school_year+'</option>';
            });

            $('#school_year').html(options);
        }).fail(function(xhr, textStatus, errorThrown) {
            msg('School Year: '+ errorThrown,'error');
        });
}

function hasId(data, id) {
    return data.some(function (el) {
        if (el.tuote !== undefined) {
            return el.tuote.id === id;
        } else {
            return undefined;
        }
    });
}

async function getPendingHomeworkCount() {
    await $.ajax({
        url: '../../student-activities/get-count-homeworks',
        type: 'GET',
        dataType: 'JSON',
    }).done(function(data, textStatus, xhr) {
        if (data.homework_count == 0) {
        } else {
            $('#hw_count').html(data.homework_count);
        }
        
    }).fail(function(xhr, textStatus, errorThrown) {
        msg('Homework Count: '+ errorThrown,'error');
    }).always(function() {
        $('.loading').hide();
    });
}

async function getPendingQuizCount() {
    await $.ajax({
        url: '../../student-activities/get-count-quizzes',
        type: 'GET',
        dataType: 'JSON',
    }).done(function(data, textStatus, xhr) {
        if (data.quiz_count == 0) {
        } else {
            $('#quiz_count').html(data.quiz_count);
        }
    }).fail(function(xhr, textStatus, errorThrown) {
        msg('Quiz Count: '+ errorThrown,'error');
    }).always(function() {
        $('.loading').hide();
    });
}