var IDcontent = $('#content');

$(document).on("click", ".description, .button.detail, .metainfo a", function(e){
	e.preventDefault();
	var eventId = $(this).attr('data-attr');
	IDcontent.stop().fadeOut(50);
	IDcontent.html('');	
	$.post('auth/ajax/eventDetail.php', {id: eventId}, function(data) {
		IDcontent.html(data).fadeIn(600);
	});	
});

function loadErrors() {
	IDcontent.stop().fadeOut(50);
	IDcontent.html('');	
	$.post('auth/ajax/eventLoop.php', function(data) {
		IDcontent.html(data).fadeIn(600);
		$('#sidebar a').removeClass("active");		
		$('#sidebar a[href="#errors"]').addClass("active");
	});			
}

function loadReports() {
	IDcontent.stop().fadeOut(50);
	IDcontent.html('');
	$.post('auth/ajax/reports.php', function(data) {
		IDcontent.html(data).fadeIn(600);
		$('#sidebar a').removeClass("active");
		$('#sidebar a[href="#reports"]').addClass("active");
	});			
}

function loadSettings() {
	IDcontent.stop().fadeOut(50);
	IDcontent.html('');
	$.post('auth/ajax/settings.php', function(data) {
		IDcontent.html(data).fadeIn(600);
		$('#sidebar a').removeClass("active");
		$('#sidebar a[href="#settings"]').addClass("active");
	});			
}
$(document).on("click", "#logo a, #sidebar a", function(e){
	e.preventDefault();
});
$(document).on("click", "#logo a, #sidebar a[href='#errors']", function(){
	loadErrors();
});
$(document).on("click", "#sidebar a[href='#reports']", function(){
	loadReports();
});
$(document).on("click", "#sidebar a[href='#settings']", function(){
	loadSettings();
});


loadErrors();