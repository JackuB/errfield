var IDcontent = $('#content');

$(document).on("click", ".description, .button.detail, .metainfo a", function(e){
	e.preventDefault();
	var eventId = $(this).attr('data-attr');
	$.post('auth/ajax/test.html', function(data) {
	  $('.result').html(data);
	});	
	alert(eventId);
});

function loadErrors() {
	if(IDcontent.is(':empty')) {
		$.post('auth/ajax/eventLoop.php', function(data) {
		  IDcontent.html(data);
		});	
	}
}

loadErrors();