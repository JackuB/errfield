<?php
	require_once 'config.php';

	if($_POST["type"] === "time") {
		DB::insert('performance', array(
			'time' => time(),
		  	'elapsedtime' => htmlspecialchars($_POST["elapsedtime"]),
		  	'url' => htmlspecialchars($_POST["url"]),
		  	'useragent' => htmlspecialchars($_POST["ua"]),
		  	'ip' => htmlspecialchars($_POST["ip"])
		));
		echo "OK!";
	} elseif($_POST["type"] == "0") {
		DB::insert('events', array(
			'type' => htmlspecialchars($_POST["type"]),
			'state' => "unresolved",
			'text' => htmlspecialchars($_POST["text"]),
			'time' => time(),
			'file' => htmlspecialchars($_POST["file"]),
			'line' => htmlspecialchars($_POST["line"]),
		  	'elapsedtime' => htmlspecialchars($_POST["elapsedtime"]),
		  	'url' => htmlspecialchars($_POST["url"]),
		  	'useragent' => htmlspecialchars($_POST["ua"]),
		  	'resolution' => htmlspecialchars($_POST["resolution"]),
		  	'ip' => htmlspecialchars($_POST["ip"])
		));
		echo "OK! inserted to events";
	} else {
		die('No error was POSTed');
	}



?>


