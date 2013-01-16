<?php
	require_once '../../config.php';
?>
	<?php
		$type = DB::query("SELECT type, typename FROM types;");
		$eventID = $_POST['id'];
		$event = DB::query("SELECT *, COUNT(*) as count, MAX(time) AS lastoccurence FROM events WHERE id=%i ORDER BY time DESC;",$eventID);
		$eventText = htmlspecialchars($event[0]['text']);
	?>


	<div class="event">
	    <div class="type <?php if($event['type'] == 0 or $event['type'] == 1) {echo "typeError";} ?>">
	        <?php if($type[$event[0]['type']]['typename'] != '') {echo $type[$event[0]['type']]['typename'];} else {echo "Undefined Error type";} ?>
            <?php echo _('|'); ?>  <?php echo _('occured '); if($event[0]['count'] == 1) {echo _('once');} else {echo $event[0]['count']; echo _(' times'); } ?> | <?php echo _('Last occurence'); ?>: <?php echo FormatTime($event[0]['lastoccurence']) ?>
	    </div>
	    <div class="description" data-attr="<?php echo $event[0]['id'] ?>">
	        <?php echo $event[0]['text'] ?><br />
	        <div class="fileInfo"><?php echo $event[0]['file']; echo _(' line '); echo $event[0]['line'] ?></div>
	    </div>
	    <div class="bottom">
	        <div class="pull-left">
		        <a href="#" class="button detail" data-attr="<?php echo $event[0]['id'] ?>">
		            <?php echo _('Details'); ?>
		        </a>
		    </div>
	    	<div class="pull-right">
		        <a href="#" class="button ignore">
		            <?php echo _('Ignore'); ?>
		        </a>
		        <a href="#" class="button solve">
		            <?php echo _('Solve'); ?>
		        </a>
	        </div>
	        <div class="clearfix"></div>
	    </div>
	    <div class="clearfix"></div>
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

	<?php
		$occurences = DB::query("SELECT id, type, state, time, text, file, line FROM events WHERE text=%s ORDER BY time DESC;", $eventText);
		if(!empty($occurences)) echo "<h2>Other occurences</h2>";
		foreach ($occurences as $occurence) {
	?>
		<div class="event">
		    <div class="type <?php if($occurence['type'] == 0 or $occurence['type'] == 1) echo "typeError"; ?>">
		        <?php echo $type[$occurence['type']]['typename']; ?>
		    </div>
		    <div class="description" data-attr="<?php echo $occurence['id'] ?>">
		        <?php echo $occurence['text'] ?>
		    </div>
		    <div class="bottom">
		        <div class="pull-left">
			        <div class="metainfo">
			            <?php echo _('In'); ?> <a href="#" data-attr="<?php echo $occurence['id'] ?>"><?php echo $occurence['file'] ?> : <?php echo $occurence['line'] ?></a> | <?php echo _('Occured'); ?>: <strong><?php echo FormatTime($occurence['time']) ?></strong>
			        </div>
			    </div>
		        <div class="clearfix"></div>
		    </div>
		    <div class="clearfix"></div>
		</div>
	<?php
		}
	?>