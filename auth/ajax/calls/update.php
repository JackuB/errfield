<?php
	require_once '../../../config.php';
?>

<?php

// get POST
$whatProjectID = $_POST['project_id'];

// what project is in POST?
$getProject = DB::query("SELECT id, name, url, table_name FROM $projects_db WHERE id = %i", $whatProjectID);

// databases which should be used
$whatDBEvents = $loggedUserIdent . "_" . $getProject[0]["table_name"] . "_events";
$whatDBPerformance = $loggedUserIdent . "_" . $getProject[0]["table_name"] . "_performance";

$whatID = $_POST['id'];

$how = $_POST['method'];

if($whatID != '' and $how != '') {
	$whatDB = DB::query("SELECT text FROM $whatDBEvents WHERE id=%i",$whatID);
	$whatText = $whatDB[0]["text"];
	if($how == 'solve') {
		DB::update($whatDBEvents, array('state' => 'solved'), "text=%s", $whatText);
		die("ok");
	} elseif ($how == 'ignore') {
		DB::update($whatDBEvents, array('state' => 'ignored'), "text=%s", $whatText);
		die("ok");
	} else {
		die('Error in POSTed info');
	}
} else {
	die('POSTed ID and/or method was empty');
}

?>