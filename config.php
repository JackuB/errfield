<?php

require_once 'libs/meekro/meekrodb.2.1.class.php';

DB::$user = 'root';
DB::$password = '';
DB::$dbName = 'errfield';
DB::$encoding = 'utf8'; // defaults to latin1 if omitted

if(empty(DB::$dbName) and empty(DB::$user)) {
	Echo "No database selected. Edit file config.php";
	die();
}

require_once 'libs/functions.php';

if (strpos($_SERVER['REQUEST_URI'],'gate.php') === false) {
    require_once 'loginForm/users.php';
}

?>