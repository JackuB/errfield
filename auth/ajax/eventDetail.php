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
    		<a data-project="<?=$whatProjectID?>" data-id="<?=$event[0]['id']?>" href="javascript:;">Ignore</a>&nbsp;&nbsp;&nbsp;<a data-project="<?=$whatProjectID?>" data-id="<?=$event[0]['id']?>" href="javascript:;">Solve</a>
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
			$occurences = DB::query("SELECT *, COUNT(*) as 'count' FROM $whatDBEvents WHERE text=%s GROUP BY DAY(from_unixtime(time)) ORDER BY time DESC LIMIT 8;",$eventText[0]['text']);
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
	    valueAxis.title = "Occurences per day";
	    chart.addValueAxis(valueAxis);

	    // GRAPH
	    graph = new AmCharts.AmGraph();
	    //graph.type = "smoothedLine"; // this line makes the graph smoothed line.
	    graph.lineColor = "#d1655d";
	    graph.valueField = "value";
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
	<div class="span6">
		<h2>Affected URLs</h2>
		<div class="affectedURLs">
			<?php
			$urls = DB::query("SELECT url, text, COUNT(*) as count FROM errfield.prj_jedenbod_events WHERE text = %s GROUP BY url ORDER BY count DESC LIMIT 15;",$eventText[0]['text']);
			foreach($urls as $url) {
				echo "<span>" . $url["count"] . "</span>" . "<a href=\"" . $url["url"] . "\" target=\"_blank\">" . $url["url"] . "</a><br />";
			}
			?>
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