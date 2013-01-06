<?php
session_start();
$userTest = DB::query("SELECT id FROM users;");

if(empty($userTest)) {
	if(empty($_POST['password'])) {
		include 'loginHeader.php';
		?>
			<h1>Create new user</h1>
			<form method="post">
				Login: <input type="login" name="login">
				Email: <input type="email" name="email">
				Password: <input type="password" name="password">
				<input type="submit">
			</form>
		<?php
		include 'loginFooter.php';
		die();
	} else {
		$hash_for_user = Bcrypt::hash($_POST['password']);
		DB::insert('users', array(
			'username' => htmlspecialchars($_POST["login"]),
			'email' => htmlspecialchars($_POST["email"]),
			'passwordHash' => htmlspecialchars($hash_for_user)
		));
		$_SESSION['errfieldHash']=$hash_for_user;
		$_SESSION['errfieldUserID']=0;		
	}

} else {

/*
*
* TODO: temporary hash key to be used in $_SESSION?
*
*/

	if(!empty($_SESSION['errfieldHash']) and ($_SESSION['errfieldUserID']) != '') {
		$hashCheck = DB::query("SELECT id, passwordHash FROM users WHERE id=%s;",$_SESSION['errfieldUserID']);
		if($hashCheck[0]['passwordHash'] !== $_SESSION['errfieldHash']) {
			session_destroy();
			die('Unathorized access, reload this page and try to login again');
		}
	} else {
		if(empty($_POST['password']) and empty($_POST['login'])) {
			include 'loginHeader.php';
			?>
					<h1>Login</h1>
					<form method="post">
						Login (username or email): <input type="login" name="login">
						Password: <input type="password" name="password">
						<input type="submit">
					</form>
			<?php
			include 'loginFooter.php';
			die();
		} else {
			$postLogin = $_POST['login'];
			$loginUsername = DB::query("SELECT id, username, passwordHash FROM users WHERE username=%s;",$postLogin);
			$loginEmail = DB::query("SELECT id, email, passwordHash FROM users WHERE email=%s;",$postLogin);
			if(!empty($loginUsername)) {
				$errfieldHash = $loginUsername[0]['passwordHash'];
				$errfieldUserID = $loginUsername[0]['id'];
			} elseif (!empty($loginEmail)) {
				$errfieldHash = $loginEmail[0]['passwordHash'];
				$errfieldUserID = $loginEmail[0]['id'];
			} else {
				die('No user found');
			}

			$is_correct = Bcrypt::check($_POST['password'], $errfieldHash);

			if($is_correct) {
				$_SESSION['errfieldHash']=$errfieldHash;
				$_SESSION['errfieldUserID']=$errfieldUserID;
			} else {
				die('Incorect password');
			}
		}
	}
}

?>