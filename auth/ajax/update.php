<?php
	require_once '../../config.php';
?>

<?php

$whatID = $_POST['id'];

$how = $_POST['method'];

if($whatID != '' and $how != '') {
	$whatDB = DB::query("SELECT text FROM events WHERE id=%i",$whatID);
	$whatText = $whatDB[0]["text"];
	if($how == 'solve') {
		DB::update('events', array('state' => 'solved'), "text=%s", $whatText);
	} elseif ($how == 'ignore') {
		DB::update('events', array('state' => 'ignored'), "text=%s", $whatText);
	} else {
		die('Error in POSTed info');
	}

} else {
	die('POSTed ID and/or method was empty');
}

?>