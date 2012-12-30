<div id="content">
	<?php
		$type = DB::query("SELECT type, typename FROM types;");

		$results = DB::query("SELECT id, type, state, time, text, file, line, COUNT(*) as count, MAX(time) AS lastoccurence FROM events GROUP BY text ORDER BY count DESC;");

		foreach ($results as $event) {
	?>
	<div class="event" data-attr="<?php echo $event['id'] ?>">
	    <div class="type <?php if($event['type'] == 0 or $event['type'] == 1) {echo "typeError";} ?>">
	        <?php echo $type[$event['type']]['typename']; ?>
	    </div>
	    <div class="description">
	        <?php echo $event['text'] ?>	        
	    </div>
	    <div class="bottom">
	        <div class="pull-left">
		        <a href="#" class="button detail">
		            <?php echo _('Details'); ?>
		        </a>
		        <div class="metainfo hidden-phone">
		            <?php echo _('In'); ?> <a href="#"><?php echo $event['file'] ?> : <?php echo $event['line'] ?></a> | <?php echo _('Last occurence'); ?>: <strong><?php echo FormatTime($event['lastoccurence']) ?></strong>
		        </div>
		    </div>
	    	<div class="pull-right">
		        <a href="#" class="button ignore">
		            <?php echo _('Ignore'); ?>
		        </a>
		        <a href="#" class="button solved">
		            <?php echo _('Solved'); ?>
		        </a>                
	        </div>  
	        <div class="clearfix"></div>      
	    </div>
	    <div class="clearfix"></div>
	</div>
	<?php
		}
	?>
</div>