<?php

	require_once 'config.php';

	echo "<h1>Errors</h1>";

	$results = DB::query("SELECT type, data, meta,COUNT(*) as count FROM events GROUP BY data ORDER BY count DESC;");
	foreach ($results as $row) {
		echo "<h2 style=\"font-weight:400\">" . $row['type'] . ": \n";
		echo "<strong> " . $row['data'] . "</strong> (".$row['count'] .")</h2>\n";
		echo "Line: " . $row['meta'] . "<br />\n";
		echo "-------------<br />\n";
	}


?>