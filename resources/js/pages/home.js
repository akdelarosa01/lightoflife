$( function() {
	$.ajax({
		url: '../../home-announcement',
		type: 'GET',
		dataType: 'JSON',
	}).done(function(data, textStatus, xhr) {
		if (data.title != undefined) {
			$('#title').html('<i class="fa fa-exclamation-triangle"></i> '+data.title);
			$('#announcement').html(data.announcement);
		} else {
			$('#title').html('<i class="fa fa-exclamation-triangle"></i> Announcement');
			$('#announcement').html('');
		}
		
	}).fail(function(xhr, textStatus, errorThrown) {
		msg('Announcement: '+ errorThrown,'error');
	});
});