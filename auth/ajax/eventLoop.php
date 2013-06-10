<?php
	require_once '../../config.php';

	// get POST
	$whatProjectID = $_POST['id'];

	// what project is in POST?
	$getProject = DB::query("SELECT id, name, url, table_name FROM $currUser->projects_db WHERE id = %i", $whatProjectID);

	// databases which should be used
	$whatDBEvents = $currUser->ident . "_" . $getProject[0]["table_name"] . "_events";
	$whatDBPerformance = $currUser->ident . "_" . $getProject[0]["table_name"] . "_performance";

	$results = DB::query("SELECT id, type, state, time, text, file, line, COUNT(*) as count, MAX(time) AS lastoccurence FROM $whatDBEvents WHERE state='unsolved' GROUP BY text ORDER BY count DESC;");

	foreach ($results as $event) { ?>
	<div class="event">
	    <div class="description" data-attr="<?php echo $event['id'] ?>">
	    	<a href="#project/<?=$whatProjectID?>/event/<?=$event['id']?>" class="detailClick"></a>
	    	<span class="eventMeta pull-left">
	            <?php echo _('occured '); if($event['count'] == 1) {echo _('once');} else {echo $event['count']; echo _(' times'); } ?> | <?php echo _('Last occurence'); ?>: <?php echo FormatTime($event['lastoccurence']) ?>
        	</span>
        	<span class="eventMeta pull-right">
        		<a class="detailLink" href="#project/<?=$whatProjectID?>/event/<?=$event['id']?>">Detail</a>&nbsp;&nbsp;&nbsp;<a class="updateLink" data-project="<?=$whatProjectID?>" data-method="ignore" data-id="<?=$event['id']?>" href="javascript:;">Ignore</a>&nbsp;&nbsp;&nbsp;<a class="updateLink" data-project="<?=$whatProjectID?>" data-method="solve" data-id="<?=$event['id']?>" href="javascript:;">Solve</a>
        	</span>
        	<span class="errorText">
	        	<?php echo $event['text'] ?>
        	</span>
	        <div class="fileInfo"><?php echo $event['file']; echo _(' line '); echo $event['line'] ?></div>
	    </div>
	</div>
<?php
	}
?>