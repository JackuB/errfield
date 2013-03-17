<h1>Application settings</h1>

<h2>Tracking code <em>(minified)</em></h2>
<?php
	echo "<textarea style=\"width:100%;\" rows=\"13\">";
	echo htmlspecialchars(file_get_contents("./../../tracking_js/errfield.min.js"));
	echo "</textarea>";

	echo "<pre class=\"brush: js; toolbar: false; html-script: true; syntaxhighlighter  javascript\">";
	echo htmlspecialchars(file_get_contents("./../../tracking_js/errfield.js"));
	echo "</pre>";
?>