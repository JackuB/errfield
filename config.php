<?php

require_once 'db/meekrodb.2.1.class.php';

DB::$user = 'root';
DB::$password = '';
DB::$dbName = 'test';

if(empty(DB::$dbName) and empty(DB::$user)) {
	Echo "No database selected. Edit file config.php";
	die();
}

require_once 'functions.php';

require_once 'users.php';

?>