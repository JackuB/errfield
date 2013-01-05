<?php
	require_once '../../config.php';
?>
	<?php
		$type = DB::query("SELECT type, typename FROM types;");	
		$eventID = $_POST['id'];
		$event = DB::query("SELECT * FROM events WHERE id=%s;",$eventID);
		$eventText = htmlspecialchars($event[0]['text']);
	?>
	<div class="event">
	    <div class="type <?php if($event[0]['type'] == 0 or $event[0]['type'] == 1) echo "typeError"; ?>">
	        <?php echo $type[$event[0]['type']]['typename']; ?>
	    </div>
	    <div class="description" data-attr="<?php echo $event[0]['id'] ?>">
	        <?php echo $event[0]['text'] ?>	        
	    </div>
	    <div class="bottom">
	        <div class="pull-left">
		        <div class="metainfo">
		            <?php echo _('In'); ?> <a href="#" data-attr="<?php echo $event[0]['id'] ?>"><?php echo $event[0]['file'] ?> : <?php echo $event[0]['line'] ?></a> | <?php echo _('Occured'); ?>: <strong><?php echo FormatTime($event[0]['time']) ?></strong>
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