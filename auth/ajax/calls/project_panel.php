<?php
	require_once '../../../config.php';

	// get POST
	$whatID = $_POST['id'];

	// what project is in POST?
	$getProject = DB::query("SELECT id, name, url, table_name FROM projects WHERE id = %i", $whatID);

	// databases which should be used
	$whatDBEvents = "prj_" . $getProject[0]["table_name"] . "_events";
	$whatDBPerformance = "prj_" . $getProject[0]["table_name"] . "_performance";

	// get array of errors with state "unsolved"
	$unsolvedErrors = DB::query("SELECT text, state FROM $whatDBEvents WHERE state = 'unsolved' GROUP BY text");

	// get array of all performance data for last day
	$loadTimes = DB::query("SELECT time, redirectTime, requestTime, responseTime, domProcessingTime, domLoadingTime, loadEventTime, (redirectTime + requestTime + responseTime + domProcessingTime + domLoadingTime + loadEventTime) as fullLoadTime FROM $whatDBPerformance WHERE from_unixtime(time,'%Y-%m-%d') BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND CURDATE()");

	// get only sum of performane.timing times
	$median = array();
	foreach ($loadTimes as $loadTime) {
		// int only
		array_push($median, intval($loadTime["fullLoadTime"]));
	}
	// sort array
	sort($median);

	if($whatID != '') { ?>
        <h2><?=$getProject[0]["name"]?></h2>
        <a class="projectSwitch error" href="javascript:;">
            <span class="number">
                <?=count($unsolvedErrors);?>
            </span><br />
            <span class="text">
                Unsolved error<?php if(count($unsolvedErrors) != 1) { echo "s"; } ?>
            </span>
        </a>

        <a class="projectSwitch stats" href="javascript:;">
            <span class="number">
                <?=$median[count($median)/2]?> ms
            </span><br />
            <span class="text">
                Median load time
            </span>
        </a>
	<?php } else {
		die('POSTed ID was empty');
	}

?>