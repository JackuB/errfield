<?php
session_start();
$userTest = DB::query("SELECT id FROM users;");

/* Are there any users at all? */
if(empty($userTest)) {
	/* If not, we should create one by including create new user form */
	/* But first check, if this is not already a request to create new user */
	if(empty($_POST['password']) and empty($_POST["login"]) and empty($_POST["email"])) {
		include 'loginHeader.php';
		?>
			<h1>Create new user</h1>
			<form method="post">
				<label for="login">Username: <input type="login" name="login" autofocus></label>
				<label for="email">Email: <input type="email" name="email"></label>
				<label for="password">Password: <input type="password" name="password"></label>
				<input type="submit" value="Create new user and login">
			</form>
		<?php
		include 'loginFooter.php';
		die();
	} else {
		/* Insert new user from POST to db */

		/* first validate email */
		if (filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
			/* generate random ident for db tables */
			function generateRandomIdent() {
				$ident = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 5)), 0, 5);

				$listUsers = DB::query("SELECT * FROM users WHERE ident = %s;", $ident);

				if(!empty($listUsers)) {
					$ident = generateRandomIdent();
				}

				return $ident;
			}

			$hash_for_user = Bcrypt::hash($_POST['password']);

			DB::insert('users', array(
				'username' => htmlspecialchars($_POST["login"]),
				'email' => htmlspecialchars($_POST["email"]),
				'passwordHash' => htmlspecialchars($hash_for_user),
				'ident' => generateRandomIdent()
			));

			/* Create empty projects table */
			DB::query("CREATE TABLE IF NOT EXISTS `" . generateRandomIdent() . "_projects` (
			  `id` int(11) NOT NULL,
			  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_czech_ci DEFAULT NULL,
			  `url` varchar(150) CHARACTER SET utf8 COLLATE utf8_czech_ci DEFAULT NULL,
			  `table_name` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;");

			/* login our new user automatically */
			$_SESSION['errfieldHash']=$hash_for_user;
			$_SESSION['errfieldUserID']=0;

			/* redirect to admin area */
			header('Location: #');
		} else {
			die("Incorrect email format");
		}
	}
} else {
/* Users already exists */

/*
*
* TODO: temporary hash key to be used in $_SESSION?
* TODO: Session timeout?
*
*/
	/* if user is already logged in */
	if(!empty($_SESSION['errfieldHash']) and ($_SESSION['errfieldUserID']) != '') {
		/* check db hash against session hash */
		$hashCheck = DB::query("SELECT id, passwordHash FROM users WHERE id=%s;",$_SESSION['errfieldUserID']);
		if($hashCheck[0]['passwordHash'] !== $_SESSION['errfieldHash']) {
			session_destroy();
			/* on mismatch */
			header('HTTP/1.1 401 Unauthorized');
			die('Unathorized access, reload this page and try to login again');
		}
	} else {
		/* session is not set, and this request does not include POST data - show login screen */
		if(empty($_POST['password']) and empty($_POST['login'])) {
			include 'loginHeader.php';
			?>
					<form method="post">
						<label for="login">Username: <input type="login" placeholder="Username or email" name="login" autofocus></label>
						<label for="password">Password: <input type="password" name="password"></label>
						<input type="submit" value="Login">
					</form>
			<?php
			include 'loginFooter.php';
			die();
		} else {
			/* Check POST against db */
			$postLogin = $_POST['login'];
			$loginUsername = DB::query("SELECT id, username, passwordHash FROM users WHERE username=%s;",$postLogin);
			$loginEmail = DB::query("SELECT id, email, passwordHash FROM users WHERE email=%s;",$postLogin);

			/* allow login with email and username */
			if(!empty($loginUsername)) {
				$errfieldHash = $loginUsername[0]['passwordHash'];
				$errfieldUserID = $loginUsername[0]['id'];
			} elseif (!empty($loginEmail)) {
				$errfieldHash = $loginEmail[0]['passwordHash'];
				$errfieldUserID = $loginEmail[0]['id'];
			} else {
				/* searching for both username match and email match failed */
				header('HTTP/1.1 401 Unauthorized');
				die('No user found');
			}

			/* check password hash */
			$is_correct = Bcrypt::check($_POST['password'], $errfieldHash);

			if($is_correct) {
				/* set session and redirect */
				$_SESSION['errfieldHash']=$errfieldHash;
				$_SESSION['errfieldUserID']=$errfieldUserID;
				header('Location: #');
       			exit;
			} else {
				/* hash mismatch */
				header('HTTP/1.1 401 Unauthorized');
				die('Incorect password');
			}
		}
	}
}

?>