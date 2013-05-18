<?php
    require_once '../../config.php';
?>

<h1>Application settings</h1>

<h2>Tracking code <em>(minified)</em></h2>
<?php
	echo "<textarea style=\"width:100%;font-family:consolas,monospace;\" rows=\"13\">";
	echo "<!-- errfield error tracking -->";
	echo "<script>";
	echo htmlspecialchars(file_get_contents("./../../tracking_js/errfield.min.js"));
	echo "<script>";
	echo "<!-- /errfield error tracking -->";
	echo "</textarea>";

	echo "<pre class=\"brush: js; toolbar: false; syntaxhighlighter: javascript\">";
	echo htmlspecialchars(file_get_contents("./../../tracking_js/errfield.js"));
	echo "</pre>";
?>