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

<h1>Create new user</h1>
<form class="form-horizontal" method="post" action="auth/ajax/calls/newUser.php">
	<div class="control-group">
		<label class="control-label" for="login">Username:</label>
		<div class="controls">
	      <input type="text" name="login" autofocus>
	    </div>
	</div>

	<div class="control-group">
		<label class="control-label" for="email">Email:</label>
		<div class="controls">
			<input type="email" name="email">
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="password">Password:</label>
		<div class="controls">
			<input type="password" name="password">
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
			<input type="submit"  class="btn btn-primary" value="Create new user">
		</div>
	</div>
</form>