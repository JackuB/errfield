<?php
	require_once '../../config.php';
?>
	<?php
		$eventID = $_POST['id'];
		$event = DB::query("SELECT * FROM events WHERE id=%s;",$eventID);
		$eventText = htmlspecialchars($event[0]['text']);
	?>

	<h1><?php echo $eventText; ?></h1>

	<h2>Other occurences</h2>

	<?php
		$type = DB::query("SELECT type, typename FROM types;");

		$occurences = DB::query("SELECT id, type, state, time, text, file, line FROM events WHERE text=%s ORDER BY time DESC;", $eventText);

		foreach ($occurences as $occurence) {
	?>
	<div class="event">
	    <div class="type <?php if($occurence['type'] == 0 or $occurence['type'] == 1) {echo "typeError";} ?>">
	        <?php echo $type[$occurence['type']]['typename']; ?>
	    </div>
	    <div class="description" data-attr="<?php echo $occurence['id'] ?>">
	        <?php echo $occurence['text'] ?>	        
	    </div>
	    <div class="bottom">
	        <div class="pull-left">
		        <a href="#" class="button detail" data-attr="<?php echo $occurence['id'] ?>">
		            <?php echo _('Details'); ?>
		        </a>
		        <div class="metainfo hidden-phone">
		            <?php echo _('In'); ?> <a href="#" data-attr="<?php echo $occurence['id'] ?>"><?php echo $occurence['file'] ?> : <?php echo $occurence['line'] ?></a> | <?php echo _('Occurenced'); ?>: <strong><?php echo FormatTime($occurence['time']) ?></strong>
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