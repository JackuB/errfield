<?php
	require_once 'config.php';

	$allUsers = DB::query("SELECT * FROM users");

	foreach($allUsers as $user) {
		$ident = $user["ident"];
		$projectsDb = $ident . "_projects";
		$projects = DB::query("SELECT * FROM $projectsDb");
		foreach($projects as $project) {
			$curl = curl_init(); // Initialize libcurl
			curl_setopt ($curl, CURLOPT_URL, $project["url"] ); // URL to visit
			curl_setopt ($curl, CURLOPT_RETURNTRANSFER, TRUE); // returns a string instead of echoing to screen
			curl_setopt($curl, CURLOPT_NOBODY, TRUE); // Only get headers, not content (saves on time)
			$result = curl_exec($curl);
			$errno = curl_errno($curl);
			if ( $errno != 0 ) { // curl_errno returns 0 if no error, otherwise returns the error code
				//echo "error loading site";
			} else {
				$http = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get the HTTP return code
				$return['code'] = $http;
				if ( $http >= 200 && $http < 300 ) { // An HTTP code greater than 200 and less than 300 means a successful load
					//echo "success";
				} else {
	 				$htmlEmail = file_get_contents("emails/default-header.html");
					$htmlEmail .= '
					    <h1>Errfield thinks, that one of your sites is down</h1>
					    <div align="left" class="article-content">
					        <p><a href="' . $project["url"] .'">' . $project["url"] .'</a> returned code ' . $http . ' (instead of 200) on ' . date('l jS \of F Y h:i:s A') . '</p>
					    </div>
					    ';
					$htmlEmail .= file_get_contents("emails/noimage-footer.php");
					$postmark = new Postmark($postmarkAPIkey,$postmarkFromEmail,$postmarkFromEmail);

					$result = $postmark->to($user["username"] . " <" . $user["email"] . ">")
						->subject($project["url"] . " is down")
						->html_message($htmlEmail)
						->send();
				}
			}
			curl_close($curl);
		}
	}

?>