<?php
// Copyright 2015 The Smpe Authors. All rights reserved.
// Use of this source code is governed by a BSD-style
// license that can be found in the LICENSE file.
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Smpe - Simple and performance MVC framework written in PHP</title>
    <link href="<?php echo Smpe_Mvc_Url::pub('/src/bootstrap/css/bootstrap.css')?>" rel="stylesheet">
    <link href="<?php echo Smpe_Mvc_Url::theme('/style.css')?>" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="<?php echo Smpe_Mvc_Url::pub('/lib/ie/html5shiv.min.js')?>"></script>
    <script src="<?php echo Smpe_Mvc_Url::pub('/lib/ie/respond.min.js')?>"></script>
    <![endif]-->
    <script src="<?php echo Smpe_Mvc_Url::pub('/src/ie/ie10-viewport-bug-workaround.js')?>"></script>
    <script src="<?php echo Smpe_Mvc_Url::pub('/src/jquery/jquery.js')?>"></script>
    <script src="<?php echo Smpe_Mvc_Url::pub('/src/bootstrap/js/bootstrap.js')?>"></script>
</head>
<body>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo Smpe_Mvc_Url::http()?>">Smpe</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="menu-Home"><a href="<?php echo Smpe_Mvc_Url::http()?>">Home</a></li>
                <li class="menu-Help"><a href="<?php echo Smpe_Mvc_Url::http('Support', 'Help', 'Browse')?>">Documentation</a></li>
                <li class="menu-About"><a href="<?php echo Smpe_Mvc_Url::http('Support', 'About')?>">About</a></li>
            </ul>
        </div>
    </div>
</nav>
<?php $this->view();?>
<script>
    var c = "<?php echo Smpe_Mvc_Bootstrap::$request['controller']?>"
    switch(c) {
        case "Help": c = "Help"; break
        case "About": c = "About"; break
        default: c = "Home"; break
    }
    $(".menu-"+c).addClass("active")
</script>
</body>
</html>
