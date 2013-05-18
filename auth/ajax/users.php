<?php
    require_once '../../config.php';
?>

<h1>Users</h1>

<?php
	$listUsers = DB::query("SELECT * FROM users;");
	foreach($listUsers as $user) {
		echo "<h2>" . $user["username"] . " <small>" . $user["email"] . "</small></h2>";
	}
?>