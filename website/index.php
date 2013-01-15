<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <script>
            var xmlhttp;
            var renderStart = new Date().getTime();
            if (window.XMLHttpRequest) {
                xmlhttp=new XMLHttpRequest();
                secondxmlhttp=new XMLHttpRequest();
            } else {
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                secondxmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            var userDetail = "&url="+document.URL+"&resolution="+screen.width+'x'+screen.height;
            window.onerror = function (msg, url, line) {
                window.onerror = function() {};
                var elapsed = new Date().getTime()-renderStart;
                var params = "type=0&text="+msg+"&line="+line+"&file="+url+"&elapsedtime="+elapsed+userDetail;
                xmlhttp.open("POST","/errfield/gate.php",true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send(params);
                return false;
            }
            window.onload=function() {
                if (typeof performance != 'undefined') {
                    setTimeout(function(){
                        if(performance.navigation.type == 0) {
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
                            if(perfLoadEnd > 0) {
                                var loadEventTime = perfLoadEnd - perfLoadStart;
                            } else {
                                var loadEventTime = 0;
                            }

                            var params = "type=time&redirectCount="+perfRedir+"&redirectTime="+redirectTime+"&requestTime="+requestTime+"&responseTime="+responseTime+"&domProcessingTime="+domProcessingTime+"&domLoadingTime="+domLoadingTime+"&loadEventTime="+loadEventTime+userDetail;
                            secondxmlhttp.open("POST","/errfield/gate.php",true);
                            secondxmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                            secondxmlhttp.send(params);
                        }
                    }, 20);
                }
            }
        </script>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/main.css">

        <!--[if lt IE 9]>
            <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
            <script>window.html5 || document.write('<script src="js/vendor/html5shiv.js"><\/script>')</script>
        <![endif]-->
    </head>
    <body>
        <h1>Hello World - <?php echo $_SERVER['HTTP_REFERER']; ?></h1>

        <img src="http://www.echenique.com/wp-content/uploads/2009/12/articles040010010_DSC3862.jpg" />
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.3.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/main.js"></script>



        <!--<script type="text/javascript">

           window.onload=function() {
                if (typeof performance != 'undefined') {
                    setTimeout(function(){
                        alert(perfif)
                    }, 2000);
                }
            }  


    </script>-->
    </body>
</html>
