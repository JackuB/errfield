<?php

	// Allow cross domain origin
	header('Access-Control-Allow-Origin: *');

	require_once 'config.php';

	if(isset($HTTP_RAW_POST_DATA)) {
	  	parse_str($HTTP_RAW_POST_DATA); // gets some variables
	} else {
		$id = $_POST['id'];
		$user_id = $_POST['user_id'];
		$type = $_POST["type"];
		$resolution = $_POST["resolution"];
		$redirectCount = $_POST["redirectCount"];
		$redirectTime = $_POST["redirectTime"];
		$requestTime = $_POST["requestTime"];
		$responseTime = $_POST["responseTime"];
		$domProcessingTime = $_POST["domProcessingTime"];
		$domLoadingTime = $_POST["domLoadingTime"];
		$loadEventTime = $_POST["loadEventTime"];
		$elapsedtime = $_POST["elapsedtime"];
		$resolution = $_POST["resolution"];
		$type = $_POST["type"];
		$line = $_POST["line"];
		$file = $_POST["file"];
		$text = $_POST["text"];
	}

	$userIdent = DB::query("SELECT id, ident FROM users WHERE id=%s;",$user_id);
	$userIdent = $userIdent[0]["ident"];
	$projects_db = $userIdent . "_projects";

    // what project is in POST?
    $getProject = DB::query("SELECT id, name, url, table_name FROM $projects_db WHERE id = %i", $id);

    // databases which should be used
    $whatDBEvents = $userIdent . "_" . $getProject[0]["table_name"] . "_events";
    $whatDBPerformance = $userIdent . "_" . $getProject[0]["table_name"] . "_performance";

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

	if($type == "time") {
		DB::insert($whatDBPerformance, array(
			'time' => time(),
			'url' => htmlspecialchars($url),
			'ip' => htmlspecialchars($ip),
			'resolution' => htmlspecialchars($resolution),
			'browser' => htmlspecialchars($current_browser->Browser),
			'browserVersion' => htmlspecialchars($current_browser->Version),
			'OS' => htmlspecialchars($current_browser->Platform),
		  	'redirectCount' => htmlspecialchars($redirectCount),
		  	'redirectTime' => htmlspecialchars($redirectTime),
		  	'requestTime' => htmlspecialchars($requestTime),
		  	'responseTime' => htmlspecialchars($responseTime),
		  	'domProcessingTime' => htmlspecialchars($domProcessingTime),
		  	'domLoadingTime' => htmlspecialchars($domLoadingTime),
		  	'loadEventTime' => htmlspecialchars($loadEventTime)
		));
	} elseif($type != "") {
		$text = htmlspecialchars($text);
		$results = DB::query("SELECT * FROM $whatDBEvents WHERE text='$text' AND state='ignored';");
		if(!empty($results)) {
			$newState = "ignored";
		} else {
			$newState = "unsolved";
		}
		DB::insert($whatDBEvents, array(
			'ip' => htmlspecialchars($ip),
			'url' => htmlspecialchars($url),
		  	'elapsedtime' => htmlspecialchars($elapsedtime),
			'resolution' => htmlspecialchars($resolution),
			'browser' => htmlspecialchars($current_browser->Browser),
			'browserVersion' => htmlspecialchars($current_browser->Version),
			'OS' => htmlspecialchars($current_browser->Platform),
			'type' => htmlspecialchars($type),
			'state' => $newState,
			'time' => time(),
			'text' => $text,
			'line' => htmlspecialchars($line),
			'file' => htmlspecialchars($file)
		));
	} else {
		die('Nothing was POSTed');
	}
?>