<?
	require_once '../../../config.php';

	if(!empty($_POST["name"]) and !empty($_POST["url"])) {
		if (filter_var($_POST["url"], FILTER_VALIDATE_URL)) {
			function generateRandomIdent() {
				$ident = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 5);

				$listUsers = DB::query("SELECT * FROM users WHERE ident = %s;", $ident);

				if(!empty($listUsers)) {
					$ident = generateRandomIdent();
				}

				return $ident;
			}

			$randomIdent = generateRandomIdent();

			DB::insert($currUser->projects_db, array(
				'name' => htmlspecialchars($_POST["name"]),
				'url' => htmlspecialchars($_POST["url"]),
				'table_name' => $randomIdent
			));


			DB::query("CREATE TABLE IF NOT EXISTS `". $currUser->ident . "_" . $randomIdent . "_events` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `ip` varchar(15),
			  `url` varchar(300),
			  `elapsedtime` int(11) DEFAULT NULL,
			  `resolution` varchar(9),
			  `browser` varchar(45),
			  `browserVersion` varchar(45),
			  `OS` varchar(45),
			  `type` int(2) DEFAULT NULL,
			  `state` varchar(20),
			  `time` int(10) DEFAULT NULL,
			  `text` varchar(500),
			  `line` int(11) DEFAULT NULL,
			  `file` varchar(200),
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");

			DB::query("CREATE TABLE IF NOT EXISTS `". $currUser->ident . "_" . $randomIdent . "_performance` (
			  `id` int(9) NOT NULL AUTO_INCREMENT,
			  `time` int(11) DEFAULT NULL,
			  `url` varchar(300),
			  `ip` varchar(45),
			  `resolution` varchar(9),
			  `browser` varchar(45),
			  `browserVersion` varchar(45),
			  `OS` varchar(45),
			  `redirectCount` int(2) DEFAULT NULL,
			  `redirectTime` int(9) DEFAULT NULL,
			  `requestTime` int(9) DEFAULT NULL,
			  `responseTime` int(9) DEFAULT NULL,
			  `domProcessingTime` int(9) DEFAULT NULL,
			  `domLoadingTime` int(9) DEFAULT NULL,
			  `loadEventTime` int(9) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");

			header('Location: ' . $_SERVER['HTTP_REFERER']);
		} else {
			die("Incorrect URL");
		}
	} else {
		die("Nothing was POSTed");
	}
?>