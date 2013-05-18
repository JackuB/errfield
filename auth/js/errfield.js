/* Init SyntaxHighlighter */
SyntaxHighlighter.all();

// DOM cache
var projectDetail = $("#projectDetail");
var content = $("#content");

// Client-side routes
Sammy(function() {
    this.get("project/:project_id",function() {
        ajaxLoadState(this.params['project_id'],"error","eventLoop",'');
    });

    this.get("project/:project_id/settings",function() {
        ajaxLoadState(this.params['project_id'],"none","projectSettings",'');
    });

    this.get("project/:project_id/event/:event_id",function() {
        ajaxLoadState(this.params['project_id'],"error","eventDetail",this.params['event_id']);
    });

    this.get("project/:project_id/performance",function() {
        ajaxLoadState(this.params['project_id'],"stats","performance",'');
    });

    this.get("settings",function() {
        ajaxLoadSinglePage("auth/ajax/settings.php");
    });

    this.get("users",function() {
        ajaxLoadSinglePage("auth/ajax/users.php");
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
function ajaxLoadState(projectId,showWhat,urlToCall,event_id) {
    urlToCall = 'auth/ajax/' + urlToCall + '.php';
    content.html('');
    content.addClass("loading");
    $.post("auth/ajax/calls/project_panel.php", {id: projectId}, function(data) {
        projectDetail.html(data);
        showProjectDetail();
        if(showWhat === "error") {
            $(".projectSwitch.stats").removeClass("active");
            $(".projectSwitch.error").addClass("active");
        } else if (showWhat === "stats") {
            $(".projectSwitch.error").removeClass("active");
            $(".projectSwitch.stats").addClass("active");
        } else {
            $(".projectSwitch.error").removeClass("active");
            $(".projectSwitch.stats").removeClass("active");
        }
        $("#sidebar ul li").find('a[href="#project/'+projectId+'"]').addClass("active");
        $.post(urlToCall, {id: projectId, eventId: event_id}, function(data) {
            content.removeClass("loading");
            content.html(data);
            SyntaxHighlighter.highlight();
        });
    });
}

function ajaxLoadSinglePage(route) {
    projectDetail.animate({"left":"0px","opacity":"0"});
    content.show().animate({"left":"-320px","opacity":"1"});
    $("#sidebar ul li").find("a").removeClass("active");
    content.html('');
    projectDetail.html();
    content.addClass("loading");
    content.load(route,function() {
        content.removeClass("loading");
        SyntaxHighlighter.highlight();
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
//.detailClick, .detailLink
content.on("mouseenter",".detailClick",function() {
    $(this).parent().parent().addClass("active");
});
content.on("mouseleave",".detailClick",function() {
    $(this).parent().parent().removeClass("active");
});
content.on("mouseenter",".detailLink",function() {
    $(this).parent().parent().parent().addClass("active");
});
content.on("mouseleave",".detailLink",function() {
    $(this).parent().parent().parent().removeClass("active");
});
content.on("click",".updateLink",function() {
    var self = $(this);
    var project = $(this).attr("data-project");
    var eventId = $(this).attr("data-id");
    var method = $(this).attr("data-method");
    $.post("auth/ajax/calls/update.php",{project_id:project,id:eventId,method:method},function(data) {
        self.parent().parent().parent().animate({"opacity":"0"},600).slideUp(450);
        $("#projectDetail .error .number").text(parseInt($("#projectDetail .number").text(),10) - 1);
    });
});