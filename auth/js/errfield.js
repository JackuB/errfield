/* Init SyntaxHighlighter */
SyntaxHighlighter.all();

// DOM cache
var projectDetail = $("#projectDetail");
var content = $("#content");

// Client-side routes
Sammy(function() {
    this.get("project/:project_id",function() {
        ajaxLoadState(this.params['project_id'],true,false);
    });

    this.get("project/:project_id/event/:event_id",function() {
        console.log("Hello");
    });

    this.get("project/:project_id/performance",function() {
        ajaxLoadState(this.params['project_id'],false,false);
    });

    this.get("home",function() {
        showOnlyHomepage();
    });

    this.get('', function() { this.app.runRoute('get', 'home'); });
}).run();

/*
    ProjectId
    Is this error or stat tab?
    Is event detail called?
*/
function ajaxLoadState(projectId,error,eventDetail) {
    var urlToCall = '';
    if(error === true) {
        urlToCall = "auth/ajax/eventLoop.php";
    } else {
        urlToCall = "auth/ajax/performance.php";
    }
    content.html('');
    content.addClass("loading");
    $.post("auth/ajax/calls/project_panel.php", {id: projectId}, function(data) {
        projectDetail.html(data);
        showProjectDetail();
        if(error === true) {
            $(".projectSwitch.stats").removeClass("active");
            $(".projectSwitch.error").addClass("active");
        } else {
            $(".projectSwitch.error").removeClass("active");
            $(".projectSwitch.stats").addClass("active");
        }
        $("#sidebar ul li").find('a[href="#project/'+projectId+'"]').addClass("active");
        $.post(urlToCall, {id: projectId}, function(data) {
            content.removeClass("loading");
            content.html(data);
        });
    });
}

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