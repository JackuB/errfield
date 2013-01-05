<?php
	require_once 'config.php';



// Loads the class
require 'Browsecap/Browscap.php';

// The Browscap class is in the phpbrowscap namespace, so import it
use phpbrowscap\Browscap;

// Create a new Browscap object (loads or creates the cache)
$bc = new Browscap('Browsecap/cache/');

// Get information about the current browser's user agent
$current_browser = $bc->getBrowser();

// Output the result
echo '<pre>'; // some formatting issues ;)
print_r($current_browser);
echo '</pre>';


	if($_POST["type"] == "time") {
		DB::insert('performance', array(
			'time' => time(),
			'browser' => htmlspecialchars($browser),
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
		$results = DB::query("SELECT * FROM events WHERE text='$text' AND state='ignored';");
		if(!empty($results)) {
			$newState = "ignored";
		} else {
			$newState = "unresolved";
		}
		DB::insert('events', array(
			'type' => htmlspecialchars($_POST["type"]),
			'state' => $newState,
			'text' => $text,
			'time' => time(),
			'file' => htmlspecialchars($_POST["file"]),
			'line' => htmlspecialchars($_POST["line"]),
		  	'elapsedtime' => htmlspecialchars($_POST["elapsedtime"]),
		  	'url' => htmlspecialchars($_POST["url"]),
		  	'useragent' => htmlspecialchars($_POST["ua"]),
		  	'resolution' => htmlspecialchars($_POST["resolution"]),
		  	'ip' => htmlspecialchars($_POST["ip"])
		));
	} else {
		die('Nothing was POSTed');
	}
?>