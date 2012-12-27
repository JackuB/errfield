<?php
	require_once 'config.php';

	if(isset($_POST["type"])) {
		DB::insert('events', array(
		  'type' => htmlspecialchars($_POST["type"]),
		  'data' => htmlspecialchars($_POST["data"]),
		  'meta' => htmlspecialchars($_POST["meta"])
		));

		echo "Posted info:<br />";
		echo $_POST["type"] . "<br />";
		echo $_POST["data"] . "<br />";
		echo $_POST["meta"];
	} else {
		die('No error was POSTed');
	}

?>