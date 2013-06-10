<?
	require_once '../../../config.php';

	if(!empty($_POST["login"]) and !empty($_POST["password"]) and !empty($_POST["email"])) {
		if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
			function generateRandomIdent() {
				$ident = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 5);

				$listUsers = DB::query("SELECT * FROM users WHERE ident = %s;", $ident);

				if(!empty($listUsers)) {
					$ident = generateRandomIdent();
				}

				return $ident;
			}

			$randomIdent = generateRandomIdent();
			$hash_for_user = Bcrypt::hash($_POST['password']);

			DB::insert('users', array(
				'username' => htmlspecialchars($_POST["login"]),
				'email' => htmlspecialchars($_POST["email"]),
				'passwordHash' => htmlspecialchars($hash_for_user),
				'ident' => $randomIdent
			));

			DB::query("CREATE TABLE IF NOT EXISTS `" . $randomIdent . "_projects` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(100) CHARACTER DEFAULT NULL,
			  `url` varchar(150) CHARACTER DEFAULT NULL,
			  `table_name` varchar(45) CHARACTER DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");

			header('Location: ' . $_SERVER['HTTP_REFERER'] . '#users');
		} else {
			die("Incorrect email format");
		}
	} else {
		die("Nothing was POSTed");
	}
?>