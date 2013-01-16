/* Cache */
var IDcontent = $('#content');

/* History.js */
(function(window,undefined){
    // Prepare
    var History = window.History; // Note: We are using a capital H instead of a lower h
    if ( !History.enabled ) {
         // History.js is disabled for this browser.
         // This is because we can optionally choose to support HTML4 browsers or not.
        return false;
    }
    // Bind to StateChange Event
    History.Adapter.bind(window,'statechange',function(){ // Note: We are using statechange instead of popstate
        var State = History.getState(); // Note: We are using History.getState() instead of event.state
        //History.log(State.data, State.title, State.url);
		if(State.title == "Homepage" || State.title == "") {
			loadErrors();
		} else if (State.title == "Reports") {
			loadReports();
		} else if (State.title == "Settings") {
			loadSettings();
		} else {
		 	IDcontent.stop().fadeOut(50);
			IDcontent.html('');
			$.post('auth/ajax/eventDetail.php', {id: State.title}, function(data) {
				IDcontent.html(data).fadeIn(600);
				SyntaxHighlighter.highlight();
				History.pushState({Detail: State.title}, State.title, "?detail="+State.title);
			});
		}
    });
})(window);

/* Clickity click */
$(document).on("click", ".description, .button.detail, .metainfo a", function(e){
	e.preventDefault();
	var eventId = $(this).attr('data-attr');
	History.pushState({Detail: eventId}, eventId, "?detail="+eventId);
});

$(document).on("click", ".button.ignore, .button.solve", function(e){
	e.preventDefault();
	/*
	 *
	 * TODO: action confirmation + better feedback (# of occurences)
	 *
	*/
	var eventId = $(this).attr('data-attr');
	var $thisElem = $(this);
	if($(this).hasClass('ignore')) {
		var methodPar = "ignore";
	} else {
		var methodPar = "solve";
	}
	$.post('auth/ajax/update.php', {id: eventId, method: methodPar}, function() {
		$thisElem.parent().parent().parent().slideUp("slow");
	});
});

function loadErrors() {
	IDcontent.stop().fadeOut(50);
	IDcontent.html('');
	$.post('auth/ajax/eventLoop.php', function(data) {
		IDcontent.html(data).fadeIn(600);
		$('#sidebar a').removeClass("active");
		$('#sidebar a[href="#errors"]').addClass("active");
		SyntaxHighlighter.highlight();
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
	History.pushState({}, "Homepage", "?");
});
$(document).on("click", "#sidebar a[href='#reports']", function(){
	History.pushState({}, "Reports", "?reports");
});
$(document).on("click", "#sidebar a[href='#settings']", function(){
	History.pushState({}, "Settings", "?settings");
});

/* Initial page load from pushed history state as get param */
if(window.location.search == "?" || window.location.search == "") {
	loadErrors();
} else if (window.location.search == "?reports") {
	loadReports();
} else if (window.location.search == "?settings") {
	loadSettings();
} else {
	var eventId = /\d+(?:\.\d+)?/.exec(window.location.search);
 	IDcontent.stop().fadeOut(50);
	IDcontent.html('');
	$.post('auth/ajax/eventDetail.php', {id: eventId[0]}, function(data) {
		IDcontent.html(data).fadeIn(600);
		SyntaxHighlighter.highlight();
	});
}

/* Init SyntaxHighlighter */
SyntaxHighlighter.all();