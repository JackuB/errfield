<?php
	require_once '../../config.php';
?>
	<?php
		$type = DB::query("SELECT type, typename FROM types;");
		$eventID = $_POST['id'];
		$event = DB::query("SELECT *, COUNT(*) as count, MAX(time) AS lastoccurence FROM events WHERE id=%s;",$eventID);
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
$homepage = file_get_contents($event[0]['file']);
echo "<pre data-line=\"14\"><code class=\"language-markup\">";
echo htmlspecialchars($homepage);
echo "</code></pre>";

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