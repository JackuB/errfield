<h1>Create new project</h1>

<form class="form-horizontal" method="post" action="auth/ajax/calls/newProject.php">
	<div class="control-group">
		<label class="control-label" for="name">Name:</label>
		<div class="controls">
	      <input type="text" name="name" placeholder="Personal blog" autofocus>
	    </div>
	</div>

	<div class="control-group">
		<label class="control-label" for="url">URL:</label>
		<div class="controls">
			<input type="url" name="url" placeholder="http://www.google.com">
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
			<input type="submit"  class="btn btn-primary" value="Create new project">
		</div>
	</div>
</form>