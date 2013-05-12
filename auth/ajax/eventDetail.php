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
	$eventText = DB::query("SELECT text FROM $whatDBEvents WHERE id=%i;",$eventID);

	$event = DB::query("SELECT * FROM $whatDBEvents WHERE text=%s ORDER BY time DESC;",$eventText[0]['text']);
?>

<div class="event detail">
    <div class="description" data-attr="<?php echo $event['id'] ?>">
    	<span class="eventMeta pull-left">
            <?php echo _('occured '); if(count($event) == 1) {echo _('once');} else {echo count($event); echo _(' times'); } ?> | <?php echo _('Last occurence'); ?>: <?php echo FormatTime($event[0]['time']) ?>
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


<div class="row-fluid">
	<div class="span4">
		<h2>Occurences <small>last 7 days</small></h2>
		<?php
		$occurences = DB::query("SELECT text, time, from_unixtime(time,'%Y-%m-%d'), COUNT(*) as 'count' FROM $whatDBEvents WHERE text = %s GROUP BY DAY(from_unixtime(time)) ORDER BY time DESC LIMIT 7;",$eventText[0]['text']);
		var_dump($occurences);
		$timestamp = time();
		for ($i = 0 ; $i < 7 ; $i++) {
		    if(date('Y-m-d', $timestamp) == $occurences[$i]["from_unixtime(time,'%Y-%m-%d')"]) {
		    	echo $occurences[$i]["count"] . "<br />";
	    	} else {
	    		echo "0";
	    	}
		    $timestamp -= 24 * 3600;
		}
		?>
	</div>
	<div class="span4">
		<h2>URLs</h2>
	</div>
	<div class="span4">
		<h2>Browsers</h2>
	</div>
</div>

<?php /*var_dump($occurences); */?>

<h2>Occurences</h2>

<?php
	if($event[0]['line'] != 0) {
		echo "<pre class=\"brush: js; highlight: [".$event[0]['line']."]; toolbar: false; html-script: true; first-line: ".($line-1)."\">";
		echo get_data($event[0]['file'],$event[0]['line']);
		echo "</pre>";
	} else {
		echo "<h3 class=\"well\">Looks like error happened in external JS file or there was error with reported line and/or url.</h3>";
	}
?>