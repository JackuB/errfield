<?php
	require_once '../../config.php';

	// get POST
	$whatProjectID = $_POST['id'];

	// what project is in POST?
	$getProject = DB::query("SELECT id, name, url, table_name FROM projects WHERE id = %i", $whatProjectID);

	// databases which should be used
	$whatDBEvents = "prj_" . $getProject[0]["table_name"] . "_events";
	$whatDBPerformance = "prj_" . $getProject[0]["table_name"] . "_performance";

	$eventID = $_POST['eventId'];
	$event = DB::query("SELECT *, COUNT(*) as count, MAX(time) AS lastoccurence FROM $whatDBEvents WHERE id=%i ORDER BY time DESC;",$eventID);
?>

<div class="event detail">
    <div class="description" data-attr="<?php echo $event['id'] ?>">
    	<span class="eventMeta pull-left">
            <?php echo _('occured '); if($event[0]['count'] == 1) {echo _('once');} else {echo $event[0]['count']; echo _(' times'); } ?> | <?php echo _('Last occurence'); ?>: <?php echo FormatTime($event[0]['lastoccurence']) ?>
    	</span>
    	<span class="eventMeta pull-right">
    		<a data-project="<?=$whatProjectID?>" data-id="<?=$event[0]['id']?>" href="javascript:;">Ignore</a>&nbsp;&nbsp;&nbsp;<a data-project="<?=$whatProjectID?>" data-id="<?=$event[0]['id']?>" href="javascript:;">Solve</a>
    	</span>
    	<span class="errorText">
        	<?php echo $event[0]['text'] ?>
    	</span>
        <div class="fileInfo"><?php echo $event[0]['file']; echo _(' line '); echo $event[0]['line'] ?></div>
    </div>
</div>

<?php
	if($event[0]['line'] != 0) {
		$line = $event[0]['line'];
		$lines = file($event[0]['file']);
		if(isset($lines[$line+5])) {
			echo "<pre class=\"brush: js; highlight: [".$event[0]['line']."]; toolbar: false; html-script: true; first-line: ".($line-4)."\">";
			echo htmlspecialchars($lines[$line-5]);
			echo htmlspecialchars($lines[$line-4]);
			echo htmlspecialchars($lines[$line-3]);
			echo htmlspecialchars($lines[$line-2]);
			echo htmlspecialchars($lines[$line-1]);
			echo htmlspecialchars($lines[$line]);
			echo htmlspecialchars($lines[$line+1]);
			echo htmlspecialchars($lines[$line+2]);
			echo htmlspecialchars($lines[$line+3]);
			echo htmlspecialchars($lines[$line+4]);
			echo htmlspecialchars($lines[$line+5]);
			echo "</pre>";
		} else {
			echo "<h3 class=\"well\">File has changed or couldn't be fetched</h3>";
		}
	} else {
		echo "<h3 class=\"well\">Looks like error happened in external JS file or there was error with reported line and/or url.</h3>";
	}
?>