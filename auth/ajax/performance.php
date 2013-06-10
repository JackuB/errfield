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

<h1>Application performance report</h1>

<div id="chartdiv" style="width: 100%; height: 400px;"></div>

<script type='text/javascript'>
var chartData = [<?php
$chart = DB::query("SELECT time, redirectTime, requestTime, responseTime, domProcessingTime, domLoadingTime, loadEventTime, from_unixtime(time,'%Y-%m-%d'), (redirectTime + requestTime + responseTime + domProcessingTime + domLoadingTime + loadEventTime) as fullLoadTime FROM $whatDBPerformance WHERE from_unixtime(time,'%Y-%m-%d') BETWEEN DATE_SUB(CURDATE(), INTERVAL 6 DAY) AND CURDATE() ORDER BY from_unixtime(time,'%Y-%m-%d') ASC;");

$groups = array();
foreach ($chart as $item) {
    $key = $item["from_unixtime(time,'%Y-%m-%d')"];
    if (!isset($groups[$key])) {
        $groups[$key] = array(
            'items' => array($item),
            'count' => 1,
        );
    } else {
        $groups[$key]['items'][] = $item;
        $groups[$key]['count'] += 1;
    }
}
$i = 1;
foreach($groups as $date) {
    if($date["count"] > 1) {
        $count = round($date["count"]/2);
    } else {
        $count = 0;
    }

    echo "{";
    echo "datetime: \"" . gmdate("m/d", $date["items"][$count]["time"]) . "\",\n";

    usort($date["items"], "cmpfullTime");
    echo "fullLoadTime: " . $date["items"][$count]["fullLoadTime"] . ",\n";

    usort($date["items"], "cmpredirectTime");
    echo "redirectTime: " . $date["items"][$count]["redirectTime"] . ",\n";

    usort($date["items"], "cmprequestTime");
    echo "requestTime: " . $date["items"][$count]["requestTime"] . ",\n";

    usort($date["items"], "cmpresponseTime");
    echo "responseTime: " . $date["items"][$count]["responseTime"] . ",\n";


    usort($date["items"], "cmpdomProcessingTime");
    echo "domProcessingTime: " . $date["items"][$count]["domProcessingTime"] . ",\n";

    usort($date["items"], "cmpdomLoadingTime");
    echo "domLoadingTime: " . $date["items"][$count]["domLoadingTime"] . ",\n";

    usort($date["items"], "cmploadEventTime");
    echo "loadEventTime: " . $date["items"][$count]["loadEventTime"];

    echo "},\n\n";
    /*
    * TODO: remove ',' for last entry
    */
}
unset($date);
?>
];
    // SERIAL CHART
    chart = new AmCharts.AmSerialChart();

    chart.pathToImages = "http://errfield.com/auth/img/amcharts/";
    chart.zoomOutButton = {
        backgroundColor: '#000000',
        backgroundAlpha: 0.15
    };
    chart.dataProvider = chartData;
    chart.marginTop = 10;
    chart.autoMarginOffset = 3;
    chart.marginRight = 0;
    chart.categoryField = "datetime";

    // AXES
    // Category
    var categoryAxis = chart.categoryAxis;
    categoryAxis.axisColor = "#DADADA";
    categoryAxis.startOnAxis = true;
    categoryAxis.showLastLabel = false;
    categoryAxis.gridAlpha = 0;


    // Value
    var valueAxis = new AmCharts.ValueAxis();
    valueAxis.stackType = "regular"; // this line makes the chart "stacked"
    valueAxis.gridAlpha = 0.07;
    valueAxis.title = "Time in ms";
    chart.addValueAxis(valueAxis);

    // GRAPHS
    var graph = new AmCharts.AmGraph();
    graph.type = "line";
    graph.hidden = false;
    graph.stackable = false;
    graph.title = "fullLoadTime";
    graph.valueField = "fullLoadTime";
    graph.lineAlpha = 1;
    graph.lineThickness = 3;
    graph.fillAlphas = 0; // setting fillAlphas to > 0 value makes it area graph
    chart.addGraph(graph);


    var graph = new AmCharts.AmGraph();
    graph.type = "line";
    graph.hidden = false;
    graph.title = "redirectTime";
    graph.valueField = "redirectTime";
    graph.lineAlpha = 1;
    graph.fillAlphas = 0.6; // setting fillAlphas to > 0 value makes it area graph
    chart.addGraph(graph);

    graph = new AmCharts.AmGraph();
    graph.type = "line";
    graph.title = "requestTime";
    graph.valueField = "requestTime";
    graph.lineAlpha = 1;
    graph.fillAlphas = 0.6;
    chart.addGraph(graph);

    graph = new AmCharts.AmGraph();
    graph.type = "line";
    graph.title = "responseTime";
    graph.valueField = "responseTime";
    graph.lineAlpha = 1;
    graph.fillAlphas = 0.6;
    chart.addGraph(graph);

    graph = new AmCharts.AmGraph();
    graph.type = "line";
    graph.title = "domProcessingTime";
    graph.valueField = "domProcessingTime";
    graph.lineAlpha = 1;
    graph.fillAlphas = 0.6;
    chart.addGraph(graph);

    graph = new AmCharts.AmGraph();
    graph.type = "line";
    graph.title = "domLoadingTime";
    graph.valueField = "domLoadingTime";
    graph.lineAlpha = 1;
    graph.fillAlphas = 0.6;
    chart.addGraph(graph);

    graph = new AmCharts.AmGraph();
    graph.type = "line";
    graph.title = "loadEventTime";
    graph.valueField = "loadEventTime";
    graph.lineAlpha = 1;
    graph.fillAlphas = 0.6;
    chart.addGraph(graph);

    // LEGEND
    var legend = new AmCharts.AmLegend();
    legend.position = "top";
    chart.addLegend(legend);

    // CURSOR
    var chartCursor = new AmCharts.ChartCursor();
    chartCursor.zoomable = false; // as the chart displayes not too many values, we disabled zooming
    chartCursor.cursorAlpha = 0;
    chart.addChartCursor(chartCursor);

    // WRITE
    chart.write("chartdiv");
    chart.invalidateSize();
</script>