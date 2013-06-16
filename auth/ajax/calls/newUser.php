<?
	require_once '../../../config.php';

	if(!empty($_POST["login"]) and !empty($_POST["password"]) and !empty($_POST["email"]) and $currUser->id == "0") {
		if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {


			$userEmailExist = DB::query("SELECT * FROM users WHERE email = %s",$_POST["email"]);
			$userLoginExist = DB::query("SELECT * FROM users WHERE username = %s",$_POST["login"]);

			if(!empty($userEmailExist)) {
				die("Email address already registered.");
			}
			if(!empty($userLoginExist)) {
				die("Username already exists.");
			}


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
			  `name` varchar(100) DEFAULT NULL,
			  `url` varchar(150) DEFAULT NULL,
			  `table_name` varchar(45) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;");


			$htmlEmail = file_get_contents("../../../emails/default-header.html");
			$htmlEmail .= '
			    <h1>Hey there!</h1>
			    <div align="left" class="article-content">
			        <p>You just created new account on <a href="http://errfield.com/">Errfield.com</a>.</p>
			    </div>
			    <div align="left" class="article-content">
			        <p>Your login name is: <strong>' . $_POST["login"] . '</strong></p>
			    </div>
			    <div align="left" class="article-content">
			        <p>Your password is: <strong>' . $_POST["password"] . '</strong></p>
			    </div>
			    <div align="left" class="article-content">
			        <p><a href="http://errfield.com/">Login now!</a></p>
			    </div>
			    ';
			$htmlEmail .= file_get_contents("../../../emails/default-footer.html");
			$postmark = new Postmark($postmarkAPIkey,$postmarkFromEmail,$postmarkFromEmail);

			$result = $postmark->to($_POST["login"] . " <" . $_POST["email"] . ">")
				->subject("Welcome to Errfield.com")
				->html_message($htmlEmail)
				->send();

			if($result === false)
				die("Something went wrong with sending email. Check your Postmark account.");

			header('Location: ' . $_SERVER['HTTP_REFERER'] . '#users');
		} else {
			die("Incorrect email format");
		}
	} else {
		die("Nothing was POSTed");
	}
?>