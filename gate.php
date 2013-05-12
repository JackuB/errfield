<?php

	// Allow cross domain origin
	header('Access-Control-Allow-Origin: *');

	require_once 'config.php';

    // get POST
    $whatProjectID = $_POST['id'];

    // what project is in POST?
    $getProject = DB::query("SELECT id, name, url, table_name FROM projects WHERE id = %i", $whatProjectID);

    // databases which should be used
    $whatDBEvents = "prj_" . $getProject[0]["table_name"] . "_events";
    $whatDBPerformance = "prj_" . $getProject[0]["table_name"] . "_performance";

	// Loads the class
	require 'libs/Browsecap/Browscap.php';

	// The Browscap class is in the phpbrowscap namespace, so import it
	use phpbrowscap\Browscap;

	// Create a new Browscap object (loads or creates the cache)
	$bc = new Browscap('libs/Browsecap/cache/');

	// Get information about the current browser's user agent
	$current_browser = $bc->getBrowser();

	// Get user IP
	$ip=$_SERVER['REMOTE_ADDR'];

	// Get referring URL
	$url=$_SERVER['HTTP_REFERER'];

	if($_POST["type"] == "time") {
		DB::insert('$whatDBPerformance', array(
			'time' => time(),
			'url' => htmlspecialchars($url),
			'ip' => htmlspecialchars($ip),
			'resolution' => htmlspecialchars($_POST["resolution"]),
			'browser' => htmlspecialchars($current_browser->Browser),
			'browserVersion' => htmlspecialchars($current_browser->Version),
			'OS' => htmlspecialchars($current_browser->Platform),
		  	'redirectCount' => htmlspecialchars($_POST["redirectCount"]),
		  	'redirectTime' => htmlspecialchars($_POST["redirectTime"]),
		  	'requestTime' => htmlspecialchars($_POST["requestTime"]),
		  	'responseTime' => htmlspecialchars($_POST["responseTime"]),
		  	'domProcessingTime' => htmlspecialchars($_POST["domProcessingTime"]),
		  	'domLoadingTime' => htmlspecialchars($_POST["domLoadingTime"]),
		  	'loadEventTime' => htmlspecialchars($_POST["loadEventTime"])
		));
	} elseif($_POST["type"] != "") {
		$text = htmlspecialchars($_POST["text"]);
		$results = DB::query("SELECT * FROM $whatDBEvents WHERE text='$text' AND state='ignored';");
		if(!empty($results)) {
			$newState = "ignored";
		} else {
			$newState = "unresolved";
		}
		DB::insert('$whatDBEvents', array(
			'ip' => htmlspecialchars($ip),
			'url' => htmlspecialchars($url),
		  	'elapsedtime' => htmlspecialchars($_POST["elapsedtime"]),
			'resolution' => htmlspecialchars($_POST["resolution"]),
			'browser' => htmlspecialchars($current_browser->Browser),
			'browserVersion' => htmlspecialchars($current_browser->Version),
			'OS' => htmlspecialchars($current_browser->Platform),
			'type' => htmlspecialchars($_POST["type"]),
			'state' => $newState,
			'time' => time(),
			'text' => $text,
			'line' => htmlspecialchars($_POST["line"]),
			'file' => htmlspecialchars($_POST["file"])
		));
	} else {
		die('Nothing was POSTed');
	}
?>