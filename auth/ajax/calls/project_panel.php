<?php
	require_once '../../../config.php';

	// get POST
	$whatProjectID = $_POST['id'];

	// what project is in POST?
	$getProject = DB::query("SELECT id, name, url, table_name FROM $currUser->projects_db WHERE id = %i", $whatProjectID);

	// databases which should be used
	$whatDBEvents = $currUser->ident . "_" . $getProject[0]["table_name"] . "_events";
	$whatDBPerformance = $currUser->ident . "_" . $getProject[0]["table_name"] . "_performance";

	// get array of errors with state "unsolved"
	$unsolvedErrors = DB::query("SELECT text, state FROM $whatDBEvents WHERE state = 'unsolved' GROUP BY text");

	// get array of all performance data for last day
	$loadTimes = DB::query("SELECT time, redirectTime, requestTime, responseTime, domProcessingTime, domLoadingTime, loadEventTime, (redirectTime + requestTime + responseTime + domProcessingTime + domLoadingTime + loadEventTime) as fullLoadTime FROM $whatDBPerformance WHERE from_unixtime(time,'%Y-%m-%d') BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND CURDATE()");
	if(count($loadTimes) != 0) {
		// get only sum of performane.timing times
		$median = array();
		foreach ($loadTimes as $loadTime) {
			// int only
			array_push($median, intval($loadTime["fullLoadTime"]));
		}
		// sort array
		sort($median);
	}
	if($whatProjectID != '') { ?>
        <h2><?=$getProject[0]["name"]?> <a href="#project/<?=$whatProjectID?>/settings" class="projectLabel">Settings</a></h2>
        <a class="projectSwitch error" href="#project/<?=$whatProjectID?>">
            <span class="number">
                <?=count($unsolvedErrors);?>
            </span><br />
            <span class="text">
                Unsolved error<?php if(count($unsolvedErrors) != 1) { echo "s"; } ?>
            </span>
        </a>

        <a class="projectSwitch stats" href="#project/<?=$whatProjectID?>/performance">
            <span class="number">
            	<?php if(count($loadTimes) != 0) { ?>
                	<?=number_format($median[count($median)/2], 0, ',', ' ');?> ms
		            </span><br />
		            <span class="text">
		                Median load time
            	<?php } else { echo 'No results</span><br /><span class="text">for last 24 hours'; } ?>
            </span>
        </a>
	<?php } else {
		die('POSTed ID was empty');
	}

?>