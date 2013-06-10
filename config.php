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
$loggedUserIdent = DB::query("SELECT id, ident FROM users WHERE id=%s;",$_SESSION['errfieldUserID']);

$currUser = (object) array(
	'id' => $_SESSION['errfieldUserID'],
	'ident' => $loggedUserIdent[0]["ident"],
	'projects_db' => $loggedUserIdent[0]["ident"] . "_projects",
	'projects' => DB::query("SELECT id, name, url, table_name FROM " . $loggedUserIdent[0]["ident"] . "_projects" )
);

?>