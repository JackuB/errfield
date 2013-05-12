(function () {
    var project_id = 0;
    var renderStart = new Date().getTime();
    var xmlhttp=[];
    var secondxmlhttp=[];
    if (window.XMLHttpRequest) {
        xmlhttp=new XMLHttpRequest();
        secondxmlhttp=new XMLHttpRequest();
    } else {
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        secondxmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    var userDetail = "&resolution="+screen.width+'x'+screen.height;
    window.onerror = function (msg, url, line) {
        window.onerror = function() {};
        var elapsed = new Date().getTime()-renderStart;
        var params = "type=0&id="+project_id+"&text="+msg+"&line="+line+"&file="+url+"&elapsedtime="+elapsed+userDetail;
        xmlhttp.open("POST","http://errfield.com/gate.php",true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(params);
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
                    var params = "type=time&id="+project_id+"&redirectCount="+perfRedir+"&redirectTime="+redirectTime+"&requestTime="+requestTime+"&responseTime="+responseTime+"&domProcessingTime="+domProcessingTime+"&domLoadingTime="+domLoadingTime+"&loadEventTime="+loadEventTime+userDetail;
                    secondxmlhttp.open("POST","http://errfield.com/gate.php",true);
                    secondxmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    secondxmlhttp.send(params);
                }
            }, 20);
        }
    };
}());