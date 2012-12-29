<div id="content">
	<?php
		$results = DB::query("SELECT type, state, datetime, text, file, line, COUNT(*) as count FROM events GROUP BY text ORDER BY count DESC;");
		foreach ($results as $row) {
	?>
	<div class="event">
	    <div class="type typeError">
	        Javascript Error
	    </div>
	    <div class="description">
	        <?php echo $row['text'] ?>	        
	    </div>
	    <div class="bottom">
	        <div class="pull-left">
		        <a href="#" class="button detail">
		            <?php echo _('Details'); ?>
		        </a>
		        <div class="metainfo">
		            <?php echo _('In'); ?> <a href="#"><?php echo $row['file'] ?> : <?php echo $row['line'] ?></a> | <?php echo _('Last occurence'); ?>: <strong><?php echo $row['datetime'] ?></strong>
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
	    </div>
	</div>
	<?php
		}
	?>
</div>