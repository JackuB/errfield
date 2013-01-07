<?php
    require_once '../../config.php'; 
?>
<h1>Application performance report</h1>

<div id="chartdiv" style="width: 100%; height: 362px;"></div>

<script type='text/javascript'>
var chartData = [<?php
$chart = DB::query("SELECT time, redirectTime, requestTime, responseTime, domProcessingTime, domLoadingTime, loadEventTime, from_unixtime(time,'%Y-%m-%d') FROM performance ORDER BY from_unixtime(time,'%Y-%m-%d') DESC;");
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

foreach($groups as $date) {
    if($date["count"] > 1) {
        $count = round($date["count"]/2);
    } else {
        $count = 0;
    }

    echo "{";
    echo "datetime: \"" . gmdate("Y-m-d", $date["items"][$count]["time"]) . "\",";

    aasort($date["items"],"redirectTime");
    echo "redirectTime: " . $date["items"][$count]["redirectTime"] . ",";

    aasort($date["items"],"requestTime");
    echo "requestTime: " . $date["items"][$count]["requestTime"] . ",";

    aasort($date["items"],"responseTime");
    echo "responseTime: " . $date["items"][$count]["responseTime"] . ",";

    aasort($date["items"],"domProcessingTime");
    echo "domProcessingTime: " . $date["items"][$count]["domProcessingTime"] . ",";

    aasort($date["items"],"domLoadingTime");
    echo "domLoadingTime: " . $date["items"][$count]["domLoadingTime"] . ",";

    aasort($date["items"],"loadEventTime");
    echo "loadEventTime: " . $date["items"][$count]["loadEventTime"];

    echo "},";
}
unset($date);
?>

];


    // SERIAL CHART
    chart = new AmCharts.AmSerialChart();
    
    chart.pathToImages = "http://www.amcharts.com/lib/images/";
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
    categoryAxis.gridAlpha = 0.07;
    categoryAxis.axisColor = "#DADADA";
    categoryAxis.startOnAxis = true;
    categoryAxis.showLastLabel = false;

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