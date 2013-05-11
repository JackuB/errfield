/* Init SyntaxHighlighter */
SyntaxHighlighter.all();

// DOM cache
var projectDetail = $("#projectDetail");
var content = $("#content");

// Client-side routes
Sammy(function() {
    this.get("project/:project_id",function() {
    	var projectId = this.params['project_id'];
        $.post("auth/ajax/calls/project_panel.php", {id: projectId}, function(data) {
    		projectDetail.html(data);
        	showProjectDetail();
        	$("#sidebar ul li").find('a[href="#project/'+projectId+'"]').addClass("active");
        	$.post("auth/ajax/eventLoop.php", {id: projectId}, function(data) {
        		content.html(data);
        	});
        });
    });

    this.get("home",function() {
        showOnlyHomepage();
    });

    this.get('', function() { this.app.runRoute('get', 'home'); });
}).run();




function showOnlyHomepage() {
	projectDetail.animate({"left":"0px","opacity":"0"});
	content.animate({"left":"-640px","opacity":"0"});
	$("#sidebar ul li").find("a").removeClass("active");
}

function showProjectDetail() {
	projectDetail.show().css("opacity","0").animate({"left":"320px","opacity":"1"});
	content.show().css("opacity","0").animate({"left":"0px","opacity":"1"});
}


// other behaviours
$("#sidebar ul li").on("click","a",function(e) {
	$("#sidebar ul li").find("a").removeClass("active");
	$(this).addClass("active");
});