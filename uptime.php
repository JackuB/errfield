<?php
	require_once 'config.php';

	$allUsers = DB::query("SELECT ident FROM users");

	$allProjects = [];

	foreach($allUsers as $user) {
		$ident = $user["ident"];
		$projectsDb = $ident . "_projects";
		$projectURL = DB::query("SELECT * FROM $projectsDb");
		$projectURL = $projectURL["url"];
		array_push($allProjects, $projectURL);
	}

	foreach($allProjects as $project) {
		$curl = curl_init(); // Initialize libcurl
		// set options:
		curl_setopt ($curl, CURLOPT_URL, $project ); // URL to visit
		curl_setopt ($curl, CURLOPT_RETURNTRANSFER, TRUE); // returns a string instead of echoing to screen
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE); // follows redirects (recursive)
		curl_setopt($curl, CURLOPT_NOBODY, TRUE); // Only get headers, not content (saves on time)
		$result = curl_exec($curl);
		$errno = curl_errno($curl);
		if ( $errno != 0 ) { // curl_errno returns 0 if no error, otherwise returns the error code
			echo "error loading site";
		} else {
			$http = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get the HTTP return code
			$return['code'] = $http;
			if ( $http >= 200 && $http < 300 ) { // An HTTP code greater than 200 and less than 300 means a successful load
				echo "success";
			} else {
				echo "site down";
			}
		}
		curl_close($curl);
	}
?>