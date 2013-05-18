<?php
    require_once 'config.php';

    $listProject = DB::query("SELECT id, name, url, table_name FROM projects");
?>
<!DOCTYPE html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>errfield - web error reporting and performance analysis</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="auth/css/style.css">
    <script type="text/javascript" src="auth/js/head.load.min.js"></script>
</head>
<body>
    <div id="wrapper">
    <div id="sidebar">
        <div id="logo">
            <a href="#home">
                <img src="auth/img/errfield_logo.jpg">
            </a>
        </div>
        <a href="#settings" class="settingButton settings">Settings</a>
        <a href="#users" class="settingButton users">Users</a>
        <ul>
            <?php foreach($listProject as $project) { ?>
                <li>
                    <a href="#project/<?=$project["id"]?>">
                        <span class="title"><?=$project["name"]?></span><br />
                        <span class="url"><?=$project["url"]?></span>
                    </a>
                </li>
            <?php } ?>
        </ul>
        <div class="clearfix"></div>
    </div>

    <div id="projectDetail">
    </div>

    <div id="content">