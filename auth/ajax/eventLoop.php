<?php
	require_once '../../config.php';

	$type = DB::query("SELECT type, typename FROM types;");

	$results = DB::query("SELECT id, type, state, time, text, file, line, COUNT(*) as count, MAX(time) AS lastoccurence FROM events WHERE state='unsolved' GROUP BY text ORDER BY count DESC;");

	foreach ($results as $event) { ?>
	<div class="event">
	    <div class="type <?php if($event['type'] == 0 or $event['type'] == 1) {echo "typeError";} ?>">
	        <?php if($type[$event['type']]['typename'] != '') {echo $type[$event['type']]['typename'];} else {echo "Undefined Error type";} ?>
            <?php echo _('|'); ?>  <?php echo _('occured '); if($event['count'] == 1) {echo _('once');} else {echo $event['count']; echo _(' times'); } ?> | <?php echo _('Last occurence'); ?>: <?php echo FormatTime($event['lastoccurence']) ?>
	    </div>
	    <div class="description" data-attr="<?php echo $event['id'] ?>">
	        <?php echo $event['text'] ?><br />
	        <div class="fileInfo"><?php echo $event['file']; echo _(' line '); echo $event['line'] ?></div>
	    </div>
	    <div class="bottom">
	        <div class="pull-left">
		        <a href="#" class="button detail" data-attr="<?php echo $event['id'] ?>">
		            <?php echo _('Details'); ?>
		        </a>
		    </div>
	    	<div class="pull-right">
		        <a href="#" class="button ignore" data-attr="<?php echo $event['id'] ?>">
		            <?php echo _('Ignore'); ?>
		        </a>
		        <a href="#" class="button solve" data-attr="<?php echo $event['id'] ?>">
		            <?php echo _('Solve'); ?>
		        </a>
	        </div>
	        <div class="clearfix"></div>
	    </div>
	    <div class="clearfix"></div>
	</div>
<?php
	}
?>