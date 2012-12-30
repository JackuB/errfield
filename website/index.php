<!DOCTYPE html>
<html class="no-js">
    <head>
        <meta charset="utf-8">
        <script>
        var userIP = "<?php echo $_SERVER['REMOTE_ADDR']?>";        
        var xmlhttp;
        if (window.XMLHttpRequest) {
            xmlhttp=new XMLHttpRequest();
            secondxmlhttp=new XMLHttpRequest();
        } else {
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            secondxmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }          
        var userDetail = "&ip="+userIP+"&url="+document.URL+"&ua="+navigator.userAgent+"&resolution="+screen.width+'x'+screen.height;
        window.onerror = function (msg, url, line) {
            window.onerror = function() {};
            var elapsed = new Date().getTime()-renderStart;
            var params = "type=0&text="+msg+"&line="+line+"&file="+url+"&elapsedtime="+elapsed+userDetail;
            xmlhttp.open("POST","/errfield/gate.php",true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
            xmlhttp.send(params);
            return false;
        } 
        var renderStart = new Date().getTime();
        window.onload=function() { 
            var elapsed = new Date().getTime()-renderStart;
            var params = "type=time&elapsedtime="+elapsed+userDetail;
            secondxmlhttp.open("POST","/errfield/gate.php",true);
            secondxmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); 
            secondxmlhttp.send(params);   
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
        <h1>Hello World</h1>
        <?php $browser = get_browser(null, true);
print_r($browser);echo $_SERVER['HTTP_USER_AGENT'] . "\n\n"; ?>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.3.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/main.js"></script>



        <script type="text/javascript">$txhis is aper('¨);</script>
    </body>
</html>
