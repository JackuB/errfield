<!DOCTYPE html>
<html class="no-js">
<head>
    <script type="text/javascript">
        var renderStart = new Date().getTime();
        window.onload=function() { 
           var elapsed = new Date().getTime()-renderStart;
        } 
    </script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>errfield - web error reporting and performance analysis</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width">
    <link rel="stylesheet" href="auth/css/style.css">
</head>
<body>
    <div id="sidebar">
        <div id="logo">
            <a href="#">
                <img src="auth/img/errfield_logo.jpg">
            </a>
        </div>
        <ul>
            <li><a href="#errors"><?php echo _('Errors'); ?></a></li>
            <li><a href="#events"><?php echo _('Events'); ?></a></li>
            <li><a href="#reports"><?php echo _('Reports'); ?></a></li>
            <li><a href="#settings"><?php echo _('Settings'); ?></a></li>
        </ul>
    </div>