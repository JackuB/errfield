(function () {
    function createCORSRequest(method, url) {
        var xhr = new XMLHttpRequest();
        if ("withCredentials" in xhr) {
            xhr.open(method, url, true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        } else if (typeof XDomainRequest != "undefined") {
            xhr = new XDomainRequest();
            xhr.open(method, url);
        } else {
            xhr = null;
        }
        return xhr;
    }
    var project_id = 0;
    var user_id = 0;
    var renderStart = new Date().getTime();
    var userDetail = "&resolution="+screen.width+'x'+screen.height;
    window.onerror = function (msg, url, line) {
        window.onerror = function() {};
        var elapsed = new Date().getTime()-renderStart;
        var params = "type=0&id="+project_id+"&user_id="+user_id+"&text="+msg+"&line="+line+"&file="+url+"&elapsedtime="+elapsed+userDetail;
        var onErrorRequest = createCORSRequest("post", "http://errfield.com/gate.php");
        if (onErrorRequest) {
            onErrorRequest.send(params);
        }
        return false;
    };
    window.onload=function() {
        if (typeof performance != 'undefined') {
            setTimeout(function(){
                if(performance.navigation.type === 0) {
                    var perfRedir = performance.navigation.redirectCount;

                    var perfStart = performance.timing.navigationStart;
                    var perfReqStart = performance.timing.requestStart;
                    var perfResStart = performance.timing.responseStart;
                    var perfResEnd = performance.timing.responseEnd;
                    var perfDomLoading = performance.timing.domLoading;
                    var perfDomInter = performance.timing.domInteractive;
                    var perfLoadStart = performance.timing.loadEventStart;
                    var perfLoadEnd = performance.timing.loadEventEnd;

                    var redirectTime = perfReqStart - perfStart;
                    var requestTime = perfResStart - perfReqStart;
                    var responseTime = perfResEnd - perfResStart;
                    var domProcessingTime = perfDomInter - perfDomLoading;
                    var domLoadingTime = perfLoadStart - perfDomInter;
                    var loadEventTime = 0;
                    if(perfLoadEnd > 0) {
                        loadEventTime = perfLoadEnd - perfLoadStart;
                    }
                    var params = "type=time&id="+project_id+"&user_id="+user_id+"&redirectCount="+perfRedir+"&redirectTime="+redirectTime+"&requestTime="+requestTime+"&responseTime="+responseTime+"&domProcessingTime="+domProcessingTime+"&domLoadingTime="+domLoadingTime+"&loadEventTime="+loadEventTime+userDetail;
                    var performanceRequest = createCORSRequest("post", "http://errfield.com/gate.php");
                    if (performanceRequest) {
                        performanceRequest.send(params);
                    }
                }
            }, 350);
        }
    };
}());