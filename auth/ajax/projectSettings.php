<?php
    require_once '../../config.php';

    // get POST
    $whatProjectID = $_POST['id'];

    // what project is in POST?
    $getProject = DB::query("SELECT id, name, url, table_name FROM $currUser->projects_db WHERE id = %i", $whatProjectID);

    // databases which should be used
    $whatDBEvents = $currUser->ident . "_" . $getProject[0]["table_name"] . "_events";
    $whatDBPerformance = $currUser->ident . "_" . $getProject[0]["table_name"] . "_performance";
?>


<h1>Project settings</h1>

<h2>Tracking code <small>0,5kb</small></h2>
<br />
<textarea class="selectAll" style="width:100%" rows="15" readonly autofocus>
&lt;!-- errfield error tracking --&gt;
&lt;script type=&quot;text/javascript&quot;&gt;
(function(){function f(c,b){var a=new XMLHttpRequest;"withCredentials"in a?(a.open(c,b,!0),a.setRequestHeader("Content-type","application/x-www-form-urlencoded")):"undefined"!=typeof XDomainRequest?(a=new XDomainRequest,a.open(c,b)):a=null;return a}var e=(new Date).getTime(),g="&resolution="+screen.width+"x"+screen.height;window.onerror=function(c,b,a){window.onerror=function(){};var d=(new Date).getTime()-e;c="type=0&id=<?php echo $whatProjectID; ?>&user_id=<?php echo $currUser->id; ?>&text="+c+"&line="+a+"&file="+b+"&elapsedtime="+d+g;(b=f("post","http://errfield.com/gate.php"))&&
b.send(c);return!1};window.onload=function(){"undefined"!=typeof performance&&setTimeout(function(){if(0===performance.navigation.type){var c=performance.navigation.redirectCount,b=performance.timing.requestStart,a=performance.timing.responseStart,d=performance.timing.domInteractive,h=performance.timing.loadEventStart,j=performance.timing.loadEventEnd,e=b-performance.timing.navigationStart,l=performance.timing.responseEnd-a,m=d-performance.timing.domLoading,k=0;0<j&&(k=j-h);c="type=time&id=<?php echo $whatProjectID; ?>&user_id=<?php echo $currUser->id; ?>&redirectCount="+
c+"&redirectTime="+e+"&requestTime="+(a-b)+"&responseTime="+l+"&domProcessingTime="+m+"&domLoadingTime="+(h-d)+"&loadEventTime="+k+g;(b=f("post","http://errfield.com/gate.php"))&&b.send(c)}},350)}})();
&lt;/script&gt;
&lt;!-- /errfield error tracking --&gt;
</textarea>