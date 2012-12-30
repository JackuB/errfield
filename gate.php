<?php
	require_once 'config.php';

	if($_POST["type"] == "time") {
		DB::insert('performance', array(
			'time' => time(),
		  	'elapsedtime' => htmlspecialchars($_POST["elapsedtime"]),
		  	'url' => htmlspecialchars($_POST["url"]),
		  	'useragent' => htmlspecialchars($_POST["ua"]),
		  	'ip' => htmlspecialchars($_POST["ip"])
		));
		echo "OK!";
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
		echo "OK! inserted to events";
		echo $newState;
	} else {
		die('No error was POSTed');
	}


$browser = get_browser(null, true);
print_r($browser);
?>


