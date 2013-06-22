<?php
    require_once 'config.php';
?>
<!DOCTYPE html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>errfield - web error reporting and performance analysis</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=1440" />
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
            <?php if($currUser->id == "0") { ?>
                <a href="#users" class="settingButton users">Users</a>
            <?php } ?>
            <?php if(!empty($currUser->projects)) { ?>
                <ul>
                    <?php foreach($currUser->projects as $project) { ?>
                        <li>
                            <a href="#project/<?=$project["id"]?>">
                                <span class="title"><?=$project["name"]?></span><br />
                                <span class="url"><?=$project["url"]?></span>
                            </a>
                        </li>
                    <?php } ?>
                    <li>
                        <a href="#newProject">
                            <span class="title">+ Add new project</span><br />
                        </a>
                    </li>
                </ul>
            <?php } else { ?>
                <a href="#newProject" class="settingButton">New project</a>
            <?php } ?>
            <div class="clearfix"></div>
        </div>

        <div id="projectDetail">
        </div>

        <div id="content">
        </div>

    </div>
    <div class="clearfix"></div>

    <script type="text/javascript">
    head.js(
        "auth/js/jquery-1.8.3.min.js",
        "auth/js/amcharts/amcharts.js",
        "auth/js/shCore.js",
        "auth/js/shBrushJScript.js",
        "auth/js/shBrushXml.js",
        "auth/js/sammy-0.7.4.min.js",
        "auth/js/errfield.js"
    );
    </script>
    </body>
</html>