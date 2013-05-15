<?php
	require_once '../../config.php';

	// get POST
	$whatProjectID = $_POST['id'];

	// what project is in POST?
	$getProject = DB::query("SELECT id, name, url, table_name FROM projects WHERE id = %i", $whatProjectID);

	// databases which should be used
	$whatDBEvents = "prj_" . $getProject[0]["table_name"] . "_events";
	$whatDBPerformance = "prj_" . $getProject[0]["table_name"] . "_performance";

	$eventID = $_POST['eventId'];
	$eventText = DB::query("SELECT text FROM $whatDBEvents WHERE id=%i;",$eventID);

	$event = DB::query("SELECT * FROM $whatDBEvents WHERE text=%s ORDER BY time DESC;",$eventText[0]['text']);
?>

<div class="event detail">
    <div class="description" data-attr="<?php echo $event['id'] ?>">
    	<span class="eventMeta pull-left">
            <?php echo _('occured '); if(count($event) == 1) {echo _('once');} else {echo count($event); echo _(' times'); } ?> | <?php echo _('Last occurence'); ?>: <?php echo FormatTime($event[0]['time']) ?>
    	</span>
    	<span class="eventMeta pull-right">
    		<a class="updateLink" data-project="<?=$whatProjectID?>" data-id="<?=$event[0]['id']?>" data-method="ignore class="updateLink""  href="javascript:;">Ignore</a>&nbsp;&nbsp;&nbsp;<a class="updateLink" data-project="<?=$whatProjectID?>" data-id="<?=$event[0]['id']?>" data-method="solve" href="javascript:;">Solve</a>
    	</span>
    	<span class="errorText">
        	<?php echo $event[0]['text'] ?>
    	</span>
        <div class="fileInfo"><?php echo $event[0]['file']; echo _(' line '); echo $event[0]['line'] ?></div>
    </div>
</div>


<div class="row-fluid">
	<div class="span6">
		<h2>Occurences <small>last 7 days</small></h2>
		<div id="chartdiv" style="width: 100%; height: 400px;"></div>
		<script>
		var chartData = [
		<?php
			$occurences = DB::query("SELECT time, text, COUNT(*) as 'count' FROM $whatDBEvents WHERE text=%s GROUP BY DAY(from_unixtime(time)) ORDER BY time DESC LIMIT 8;",$eventText[0]['text']);
			$timestamp = time() - (7*24*3600);
			for ($i = 7 ; $i >= 0; $i--) {
				echo "{date: \"" . date('m-d', $timestamp) . "\", value: ";
			    if(date('Y-m-d', $timestamp) == date('Y-m-d', $occurences[$i]["time"])) {
			    	echo $occurences[$i]["count"];
		    	} else {
		    		echo "0";
		    	}
			    $timestamp += 24 * 3600;
			    if($i != 0) {echo "},";} else {echo "}";}
			}
		?>
		];
	    // SERIAL CHART
	    chart = new AmCharts.AmSerialChart();

	    chart.pathToImages = "http://errfield.com/auth/img/amcharts/";
	    chart.dataProvider = chartData;
	    chart.marginTop = 10;
	    chart.autoMarginOffset = 3;
	    chart.marginRight = 0;
	    chart.categoryField = "date";

	    // AXES
	    // Category
	    var categoryAxis = chart.categoryAxis;
	    categoryAxis.gridAlpha = 0.07;
	    categoryAxis.axisColor = "#DADADA";
	    categoryAxis.startOnAxis = true;
	    categoryAxis.showLastLabel = false;
	    categoryAxis.gridAlpha = 0;

	    // Value
	    var valueAxis = new AmCharts.ValueAxis();
	    valueAxis.gridAlpha = 0.07;
	    chart.addValueAxis(valueAxis);

	    // GRAPH
	    graph = new AmCharts.AmGraph();
	    //graph.type = "smoothedLine"; // this line makes the graph smoothed line.
	    graph.lineThickness = 3;
	    graph.lineColor = "#d1655d";
	    graph.valueField = "value";
	    graph.bullet = "round";
	    graph.bulletColor = "#FFFFFF";
	    graph.bulletBorderColor = "#d1655d";
	    graph.bulletBorderThickness = 2;
	    graph.bulletSize = 7;
	    chart.addGraph(graph);

	    // CURSOR
	    var chartCursor = new AmCharts.ChartCursor();
	    chartCursor.cursorAlpha = 0;
	    chartCursor.cursorPosition = "mouse";
	    chart.addChartCursor(chartCursor);

	    // WRITE
	    chart.write("chartdiv");
	    chart.invalidateSize();
		</script>
	</div>
	<div class="span5 offset1">
		<h2>Affected URLs</h2>
		<div class="affectedURLs">
			<ul>
			<?php
			$urls = DB::query("SELECT url, text, COUNT(*) as count FROM errfield.prj_jedenbod_events WHERE text = %s GROUP BY url ORDER BY count DESC LIMIT 10;",$eventText[0]['text']);
			foreach($urls as $url) {
				echo "<li><span>" . $url["count"] . "</span>" . "<a href=\"" . $url["url"] . "\" target=\"_blank\">" . $url["url"] . "</a></li>";
			}
			?>
			</ul>
		</div>
	</div>
</div>

<h2>Code</h2>
<?php
	if($event[0]['line'] != 0) {
		echo "<pre class=\"brush: js; highlight: [".$event[0]['line']."]; toolbar: false; html-script: true; first-line: ".($event[0]['line']-2)."\">";
		echo get_data($event[0]['file'],$event[0]['line']);
		echo "</pre>";
	} else {
		echo "<h3 class=\"well\">Looks like error happened in external JS file or there was error with reported line and/or url.</h3>";
	}
?>